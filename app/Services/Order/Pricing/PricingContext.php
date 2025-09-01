<?php
// app/Services/Pricing/PricingContext.php
namespace App\Services\Order\Pricing;

use App\Models\Product;
use App\Services\Order\Pricing\PricingStrategyInterface;

class PricingContext
{
    private PricingStrategyInterface $strategy;

    public function __construct(PricingStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function setStrategy(PricingStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function calculateProductPrice(Product $product, int $quantity = 1): array
    {
        return $this->strategy->calculatePrice($product, $quantity);
    }
}