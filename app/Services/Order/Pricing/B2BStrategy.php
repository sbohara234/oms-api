<?php
// app/Services/Pricing/B2BPricingStrategy.php
namespace App\Services\Order\Pricing;

use App\Models\Product;
use App\Services\Order\Pricing\PricingStrategyInterface;

class B2BStrategy implements PricingStrategyInterface
{
    public function calculatePrice(Product $product, int $quantity = 1): array
    {
        /** we can add other discounts or benefit for B2B customer 
         * suppose for B2B customer shipping cost is  0
         * can add reverse charge etc for international customers etc 
         */
        $discount = $this->calculateDiscount($quantity);
        $basePrice = floatval($product->b2b_price_per_unit);
         $subtotal =  $basePrice  * $quantity;
        $retunData['subtotal'] = $subtotal;
        $retunData['discount_amount'] =  $subtotal *  $discount;
        /**tax amount 13% */
        $retunData['tax_amount'] =$basePrice * 0.13;
        
        $retunData['shipping_cost'] =0;
        $retunData['unit_price'] =$basePrice;



        

        return $retunData;
    }

    private function calculateDiscount(int $quantity): float
    {
        if ($quantity >= 100) return 0.20; // 20% discount
        if ($quantity >= 50) return 0.15;  // 15% discount
        if ($quantity >= 10) return 0.10;  // 10% discount
        return 0.05; // 5% default discount
    }

    public function getStrategyName(): string
    {
        return 'b2b';
    }
}