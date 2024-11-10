<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDBConnection extends Command
{
    protected $signature = 'db:test';
    protected $description = 'Test the database connection';

    public function handle()
    {
        try {
            DB::connection()->getPdo();
            
        } catch (\Exception $error) {
            $this->error("✕ Verbindungsfehler: " . $error->getMessage());
        }
    }
}