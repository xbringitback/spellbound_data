<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Spell;
use App\Services\Base\ImportService;

class SpellService extends ImportService
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
        return 'spells';
    }

    protected function getModel(): string
    {
        return Spell::class;
    }

    protected function mapData(array $data): array
    {
        return [
            'spell' => $data['spell'],
            'use' => $data['use'],
            'api_index' => $data['index'],
            'last_synced_at' => now()
        ];
    }
}