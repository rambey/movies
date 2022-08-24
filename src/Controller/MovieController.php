<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(): Response
    {
        $vars = 'movie/550/lists';
        $language = 'en-US';
        $allMovies = $this->callApiService->getApi($vars,$language);
        dd($allMovies);
        return $this->render('attendance/index.html.twig', [
        ]);
    }

    /**
     * list all movies'categories
     * @Route("/categories", name="app_movies_categories", methods={"GET"})
     * @return Response
     */
    public  function  allCategories(): Response{
        $vars = 'genre/movie/list';
        $language = 'en-US';
        $allMovies = $this->callApiService->getApi($vars,$language);
        dd($allMovies);
    }

    /**
     * list popular movie
     * @Route("/popular", name="app_movies_popular", methods={"GET"})
     * @return Response
     */
    public  function  popularMovie(): Response{
        $vars = 'movie/popular';
        $language = 'en-US';
        $popularMovies = $this->callApiService->getApi($vars,$language);
        dd($popularMovies);
    }

    /**
     * get details for a specific movie
     * @Route("/movie/{id}", name="app_movie_details", methods={"GET"})
     * @param $id
     * @return Response
     */
    public function getMovieDetails($id): Response{
        $vars = 'movie/'.$id;
        $language = 'en-US';
        $movieDetails = $this->callApiService->getApi($vars,$language);
        dd($movieDetails);
        // video informations
        //https://api.themoviedb.org/3/movie/550/videos?api_key=e81c6c67ee604f117f04f5b39775f2ec&language=en-US
    }

    /**
     * get details for a specific movie
     * @Route("/movies/filter", name="app_movie_filter", methods={"GET"})
     * @return Response
     */
    public function getMoviesByCategories(): Response{
        //https://api.themoviedb.org/3/discover/movie?api_key=e81c6c67ee604f117f04f5b39775f2ec&with_genres=36,14
        $vars = 'discover/movie';
        $language = 'en-US';
        $filter = '36,14';
        $movies= $this->callApiService->getApi($vars,$language,$filter);
        dd($movies);
    }
}