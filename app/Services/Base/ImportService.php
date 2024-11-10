<?php

namespace App\Services\Base;

use App\Services\ApiService;
use App\Services\Interfaces\Importable;
use Illuminate\Support\Facades\Log;

abstract class ImportService extends ApiService implements Importable
{
    // Tracks statistics for the import process
    protected array $stats = [
        'total' => 0,
        'created' => 0,
        'updated' => 0,
        'failed' => 0
    ];

     // Abstract methods that must be implemented by child classes
    abstract protected function mapData(array $data): array;
    abstract protected function getModel(): string;


    // Runs the import process and returns statistics
    public function import(): array
    {
        try {
            // Perform the import
            $items = $this->fetch();
            $this->stats['total'] = count($items);

            foreach ($items as $item) {
                try {
                    $this->processItem($item);
                } catch (\Exception $error) {
                    $this->handleError($error, $item);
                }
            }

            $this->logImportResults();
            return $this->stats;

        } catch (\Exception $error) {
            Log::error("Import failed: " . $error->getMessage());
            throw $error;
        }
    }

    protected function processItem(array $item): void
    {
        $modelClass = $this->getModel();
    
        // Check if the object based on `api_index` exists in the database
        $existingModel = $modelClass::where('api_index', $item['index'])->first();
    
        if ($existingModel) {
            // Object exists: 
            // 1. transfer data from the API
            // 2. Update `last_synced_at` to save the time of the last API synchronisation
            // `updated_at` is also automatically updated by the update
            $existingModel->update(
                array_merge(
                    $this->mapData($item),         // New data from the API
                    ['last_synced_at' => now()] 
                )
            );
            $this->stats['updated']++;
        } else {
            // Object does not exist:
            // Create new object and initialise both timestamps:
            // - `created_at` and `updated_at` are set automatically
            $modelClass::create(
                array_merge(
                    $this->mapData($item),
                    ['last_synced_at' => now()] 
                )
            );
            $this->stats['created']++;
        }
    }
    
    

    protected function handleError(\Exception $error, array $item): void
    {
        $this->stats['failed']++;
        Log::error("Failed to import item", [
            'error' => $error->getMessage(),
            'item' => $item
        ]);
    }

    protected function logImportResults(): void
    {
        Log::info("Import completed", [
            'model' => $this->getModel(),
            'stats' => $this->stats
        ]);
    }
}