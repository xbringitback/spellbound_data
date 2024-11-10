<?php

namespace App\Services\Interfaces;

interface Importable
{

    // Import method that must be implemented by all import services
    // @return array statistics about the import [‘total’ => x, ‘created’ => y, ...]


    public function import(): array;
}