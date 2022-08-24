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

    public function getApi($vars,$language,$filter=null): array
    {
        $token = $this->params->get('token');
        $apikey = $this->params->get('api_key');
        try {
            $response = $this->client->request(
                'GET',
                'https://api.themoviedb.org/3/'.$vars.'?api_key='.$apikey.'&'.'language='.$language.'&with_genres='.$filter,[
                    'auth_bearer' => $token ,
                ]
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }
}