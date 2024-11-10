<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Character;
use App\Services\Base\ImportService;

class CharacterService extends ImportService
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
        return 'characters';
    }

    protected function getModel(): string
    {
        return Character::class;
    }

protected function mapData(array $data): array
{
    // Get house name from API response
    $houseName = $data['hogwartsHouse'];

    // Search house in the database based on the name
    $house = \App\Models\House::where('house', $houseName)->first();

    // If house was found, assign house_id
    $houseId = $house ? $house->id : null;  // ID of house (if available)

    return [
        'fullName'       => $data['fullName'],
        'nickname'       => $data['nickname'],
        'interpretedBy'  => $data['interpretedBy'],
        'children'       => $data['children'],
        'image'          => $data['image'],
        'birthdate'      => $data['birthdate'],
        'api_index'      => $data['index'],
        'house_id'       => $houseId,
        'last_synced_at' => now(),
    ];
}

    
}