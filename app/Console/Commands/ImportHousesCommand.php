<?php

namespace App\Console\Commands;

use App\Services\HouseService;
use Illuminate\Console\Command;

class ImportHousesCommand extends Command
{
    protected $signature = 'houses:import';
    protected $description = 'Import houses from API';

    private HouseService $houseImporter;

    public function __construct(HouseService $houseImporter)
    {
        parent::__construct();
        $this->houseImporter = $houseImporter;
    }

    public function handle()
    {
        $this->info('Starting house import...');

        try {
            $stats = $this->houseImporter->import();

            $this->info('Import completed!');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Total', $stats['total']],
                    ['Created', $stats['created']],
                    ['Updated', $stats['updated']],
                    ['Failed', $stats['failed']]
                ]
            );

        } catch (\Exception $error) {
            $this->error("Import failed: " . $error->getMessage());
            return 1;
        }

        return 0;
    }
}
