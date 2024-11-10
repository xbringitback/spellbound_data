<?php
namespace App\Console\Commands;

use App\Services\CharacterService;
use Illuminate\Console\Command;

class ImportCharactersCommand extends Command
{
    protected $signature = 'characters:import';
    protected $description = 'Import characters from API';

    private CharacterService $characterImporter;

    public function __construct(CharacterService $characterImporter)
    {
        // Call constructor
        parent::__construct();

        $this->characterImporter = $characterImporter;
    }

    // Execute the import command
    public function handle()
    {
        $this->info('Starting character import...');

        try {

            // Import characters and get statistics
            $stats = $this->characterImporter->import();

            $this->info('Import completed!');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Total', $stats['total']],
                    ['Created', $stats['created']],
                    ['Updated', $stats['updated']],
                    ['Failed', $stats['failed']],
                ]
            );

        } catch (\Exception $error) {
            $this->error("Import failed: " . $error->getMessage());
            return 1;
        }

        return 0;
    }
}
