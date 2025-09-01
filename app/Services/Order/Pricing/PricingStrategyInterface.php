<?php

namespace App\Services\Order\Pricing;

use App\Models\Product;

interface PricingStrategyInterface
{
    public function calculatePrice(Product $product, int $quantity = 1): array;
    public function getStrategyName(): string;
}
