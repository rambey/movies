<?php

namespace App\Controller;

use App\Service\CallApiService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @var CallApiService
     */
    private $callApiService;

    /**
     * @param CallApiService $callApiService
     */
    public function __construct(CallApiService $callApiService)
    {
        $this->callApiService = $callApiService;
    }

    /**
     * @Route("/movies", name="app_movies_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $page = $request->query->get('page');
        return $this->render('movies/list_movies.html.twig', [
            'categories' => $this->getCategories(),
            'trailer' => $this->getTopMovieDetails((int)$this->getTopRatedMovie()['id']),
            'topRatedDetails' => $this->getTopRatedMovie(),
            'popularMovies' => $this->getTopMovies($page),
            'countTotatl' => $this->getTopMovies($page)['total_results'],
            'page' => $this->getTopMovies($page)['page'],
            'pages' => $this->getTopMovies($page)['total_pages'],
        ]);
    }

    /**
     * return all categories
     * @return array
     */
    private function getCategories(): array{
        $vars = 'genre/movie/list';
        $language = 'en-US';
        $categories = $this->callApiService->getApi($vars,$language,null,null);
        return $categories['genres'];
    }

    /**
     * list popular movie
     * @return array
     */
    private  function  getTopRatedMovie(): array{
        $popularMovies = $this->getTopMovies(null);
        $moviesList = array_reverse($popularMovies['results']);
        return array_pop($moviesList);
    }

    /**
     * list all popular movies
     * @return array
     */
    private function getTopMovies($page): array{
        $vars = 'discover/movie';
        $language = 'en-US';
        return $this->callApiService->getApi($vars,$language,null,(int)$page);
    }

    /**
     * get details for a specific movie
     * @param $id
     * @return mixed
     */
    private function getTopMovieDetails($id){
        $vars = 'movie/'.(int)$id.'/videos';
        $language = 'en-US';
        $data = $this->callApiService->getApi($vars,$language);
        foreach ($data['results'] as  $element){
            if($element['type'] = "Trailer" && $element['site'] = "YouTube"  && $element['official'] = "true" ){
                 return $element;
            }
        }
    }

    /**
     * get movies by categories
     * @Route("/movies/filter", name="app_movie_filter", methods={"POST"})
     */
    public function getMoviesByCategories(Request $request): Response{
        $vars = 'discover/movie';
        $language = 'en-US';
        $ids = $request->request->all();
        $chossenIds = json_decode($ids['ids']);
        $filter = '';
        foreach ($chossenIds as $id){
            $filter.=$id.',';
        }
        $filter = substr_replace($filter ,"", -1);
        $movies= $this->callApiService->getApi($vars,$language,$filter);
        return $this->json($movies);
    }

    /**
     * get details for a specific movie
     * @Route("/movies/query", name="app_movie_query", methods={"POST"})
     */
    public function getMoviesByQuery(Request $request): Response{
        $vars = 'discover/movie';
        $language = 'en-US';
        $ids = $request->request->all();
        $filter = 'hor: Love';
        $movies= $this->callApiService->getApi($vars,$language,$filter);
        return $this->json($movies);
    }
}