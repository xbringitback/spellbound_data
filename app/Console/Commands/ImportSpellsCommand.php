<?php

namespace App\Console\Commands;

use App\Services\SpellService;
use Illuminate\Console\Command;

class ImportSpellsCommand extends Command
{
    protected $signature = 'spells:import';
    protected $description = 'Import spells from  API';

    private SpellService $spellImporter;

    public function __construct(SpellService $spellImporter)
    {
        parent::__construct();
        $this->spellImporter = $spellImporter;
    }

    public function handle()
    {
        $this->info('Starting spell import...');

        try {
            $stats = $this->spellImporter->import();

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
