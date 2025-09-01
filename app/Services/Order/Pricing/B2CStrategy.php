<?php
// app/Services/Pricing/B2CPricingStrategy.php
namespace App\Services\Order\Pricing;

use App\Models\Product;
use App\Services\Order\Pricing\PricingStrategyInterface;

class B2CStrategy implements PricingStrategyInterface
{
    public function calculatePrice(Product $product, int $quantity = 1): array
    {
                $basePrice = floatval($product->b2b_price_per_unit);

                $subtotal =  $basePrice  * $quantity;
                $retunData['subtotal'] = $subtotal;
                /**can add other charges like shipping cost etc here and other 
                 * discounts here specially for B2C customer here */
                /**shipping charge suppose 1% */
                $retunData['shipping_cost'] = $subtotal* 0.001;
                $retunData['discount_amount'] = 0;

                /** 13% tax added  */
                $retunData['tax_amount'] =$subtotal*0.13 ;
                $retunData['unit_price'] =$basePrice;

        return $retunData;
    }

    public function getStrategyName(): string
    {
        return 'b2c';
    }
}