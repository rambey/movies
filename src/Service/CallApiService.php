<?php
namespace App\Service;

use DateTime;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;
    private $params;

    public function __construct(HttpClientInterface $client,ParameterBagInterface $params)
    {
        $this->client = $client;
        $this->params = $params;
    }

    public function getApi($vars,$language,$filter=null,$query=null,$page=null): array
    {
        $apikey = $_ENV['TMDB_API_KEY'];
        $token = $_ENV['TMDB_BEARER_TOKEN'];

        try {
            $pages = '';
            if ($page != null) {
                $pages .= '&page='.$page ;
            }
            $queries = '';
            if ($query != null) {
                $queries .= '&query='.$query ;
            }
            $filters = '';
            if ($filter != null) {
                $filters .= '&with_genres='.$filter ;
            }
            $languages = '';
            if ($language != null) {
                $languages .= '&language='.$language ;
            }
           $response = $this->client->request(
                'GET',
                'https://api.themoviedb.org/3/'.$vars.'?api_key='.$apikey.$pages.$languages.$filters,[
                    'auth_bearer' => $token ,
                ]
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $content = $response->toArray();
        return $content;
    }
}
