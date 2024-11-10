<?php

declare(strict_types=1);

class ProductParser
{
    private function validateProduct(array $product): void 
    {
        // Check if a product number is available
        if (!isset($product['number'])) {
            throw new Exception('Unknown SKU');
        }

        // Check if the product group exists in the configured categories
        $categoryId = (int) $product['merchandiseGroup'];
        if (!isset(config('product_categories')[$categoryId])) {
            throw new Exception('Unknown category');
        }
    }

    private function formatEan(?string $ean): ?string 
    {
        // If no EAN available, return null
        if (empty($ean)) {
            return null;
        }

        // Add leading 0 if EAN starts with 7 and has 12 digits
        if (str_starts_with($ean, '7') && strlen($ean) === 12) {
            return '0' . $ean;
        }

        return $ean;
    }

    private function determineSkuType(array $product, string $category): string 
    {
        // Check for upsell products (ending with _X or product group 12)
        if (str_ends_with($product['number'], needle: '_X') || $product['merchandiseGroup'] === '12') {
            return 'upsell';
        }

        // Check for matrix products
        if ($product['isMatrixProduct']) {
            return 'main';
        }

        // Check for special categories that are always main
        if (in_array($category, ['bundles', 'external-services', 'services', 'discounts', 'digitals'])) {
            return 'main';
        }

        // Check for unlabeled products (ending with _UL)
        if (str_ends_with($product['number'], '_UL')) {
            return 'unlabeled';
        }

        // Standard case: single product
        return 'single';
    }


    // Converts measurements into the correct format
    private function formatMeasurements(array $measurements): array 
    {
        return [
            'width' => intval($measurements['width'] * 10),
            'height' => intval($measurements['height'] * 10),
            'length' => intval($measurements['length'] * 10),
            'weight' => intval($measurements['weight'] * 1000000)
        ];
    }


    // Then parse the product
    public function parse(array $product): array 
    {
        // First validate the input data
        $this->validateProduct($product);
        
        // Determine the category
        $categoryId = (int) $product['merchandiseGroup'];
        $category = config('product_categories')[$categoryId];
        
        // Format all required data
        $measurements = $this->formatMeasurements($product['measurements']);
        $ean = $this->formatEan($product['ean'] ?? null);
        $name = $product['name'] ?? null;
        $skuType = $this->determineSkuType($product, $category);

        // Create and return the final product array
        return [
            'ean' => $ean,
            'category' => $category,
            'sku' => [
                'sku' => $product['number'],
                'name' => $name,
                'type' => $skuType,
            ],
            'width' => $measurements['width'],
            'height' => $measurements['height'],
            'length' => $measurements['length'],
            'weight' => $measurements['weight'],
        ];
    }
}