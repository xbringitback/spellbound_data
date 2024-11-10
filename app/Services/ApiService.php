<?php

namespace App\Services; 

use GuzzleHttp\Client; 
use Illuminate\Support\Facades\Log; 

// Abstract base class for all API services
abstract class ApiService
{
    protected Client $client;  // Guzzle HTTP Client
    protected string $baseUrl = 'https://potterapi-fedeperin.vercel.app/en/'; 

    public function __construct()
    {

        $this->client = new Client([
            'base_uri' => $this->baseUrl, 
            'timeout'  => 20.0,          
        ]);
    }

    // Each child class must implement this method
    // e.g. return 'spells' or 'characters'
    abstract protected function getEndpoint(): string;

    // Shared method for API calls
    public function fetch()
    {
        try {
            // Stores the endpoint in a variable to be able to add further endpoints
            $endpoint = $this->getEndpoint();
            $url = "{$this->baseUrl}{$endpoint}";

            // Makes GET request to endpoint
            $res = $this->client->get($url);
            return json_decode($res->getBody(), true);
        } catch (\Exception $error) {
            Log::error("API Error: " . $error->getMessage());

            throw $error;
        }
    }
}