<?php

namespace App\Console\Commands;

use App\Services\CharacterService;
use App\Services\HouseService;
use App\Services\SpellService;
use Illuminate\Console\Command;

class CombinedImportCommand extends Command
{

    protected $signature = 'import:all';
    protected $description = 'Import characters, houses, and spells from API';

    private CharacterService $characterImporter;
    private HouseService $houseImporter;
    private SpellService $spellImporter;

    public function __construct(
        CharacterService $characterImporter,
        HouseService $houseImporter,
        SpellService $spellImporter
    )
    {
        // Call constructor
        parent::__construct();
        $this->characterImporter = $characterImporter;
        $this->houseImporter = $houseImporter;
        $this->spellImporter = $spellImporter;
    }


     // Execute the import command
    public function handle()
    {
        // Step 1: characters 
        $this->info('Starting character import...');
        try {
            // Import characters and get statistics
            $characterStats = $this->characterImporter->import();
            $this->info('Character import completed!');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Total', $characterStats['total']],
                    ['Created', $characterStats['created']],
                    ['Updated', $characterStats['updated']],
                    ['Failed', $characterStats['failed']],
                ]
            );
        } catch (\Exception $error) {
            $this->error("Character import failed: " . $error->getMessage());
            return 1;
        }

        // Step 1: houses 
        $this->info('Starting house import...');
        try {
            $houseStats = $this->houseImporter->import();
            $this->info('House import completed!');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Total', $houseStats['total']],
                    ['Created', $houseStats['created']],
                    ['Updated', $houseStats['updated']],
                    ['Failed', $houseStats['failed']],
                ]
            );
        } catch (\Exception $error) {
            $this->error("House import failed: " . $error->getMessage());
            return 1;
        }

        // Step 3: spells 
        $this->info('Starting spell import...');
        try {
            $spellStats = $this->spellImporter->import();
            $this->info('Spell import completed!');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Total', $spellStats['total']],
                    ['Created', $spellStats['created']],
                    ['Updated', $spellStats['updated']],
                    ['Failed', $spellStats['failed']],
                ]
            );
        } catch (\Exception $error) {
            $this->error("Spell import failed: " . $error->getMessage());
            return 1;
        }

        return 0; // Success
    }
}
