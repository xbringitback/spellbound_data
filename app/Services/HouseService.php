<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\House;
use App\Services\Base\ImportService;

class HouseService extends ImportService
{

    public function __construct(Client $client = null)
    {
        if ($client) {
            $this->client = $client;
        } else {
            parent::__construct();
        }
    }
    protected function getEndpoint(): string
    {
        return 'houses';
    }

    protected function getModel(): string
    {
        return House::class;
    }

    protected function mapData(array $data): array
    {
        return [
            'house'          => $data['house'],            
            'emoji'          => $data['emoji'],            
            'founder'        => $data['founder'],         
            'colors'         => $data['colors'],         
            'animal'         => $data['animal'],
            'api_index'      => $data['index'],
            'last_synced_at' => now(),   
        ];
    }
}