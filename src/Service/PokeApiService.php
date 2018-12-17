<?php

namespace App\Service;


use App\Model\Pokemon;

class PokeApiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    public function getPokemons()
    {
        try {
            $request = $this->client->request('get', 'https://pokeapi.co/api/v2/pokemon/');
            $response = json_decode($request->getBody());

            $pokemons = array();
            foreach ($response->results as $result) {
                $pokemons[] = new Pokemon($result->name, $result->url);
            }

            return $pokemons;

        } catch (\Exception $e) {
            return array();
        }

    }

    public function getImage($url)
    {
        try {
            $request = $this->client->request('get', $url);
            $response = json_decode($request->getBody());

            return $response->sprites->front_default;

        } catch (\Exception $e) {
            return null;
        }
    }
}