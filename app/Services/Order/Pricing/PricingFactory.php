<?php
// app/Services/Pricing/PricingStrategyFactory.php
namespace App\Services\Order\Pricing;

use App\Models\Customer;
use App\Services\Order\Pricing\PricingStrategyInterface;
use App\Services\Order\Pricing\B2CStrategy;
use App\Services\Order\Pricing\B2BStrategy;
class PricingFactory
{
    public static function createForCustomer(Customer $customer): PricingStrategyInterface
    {
        return $customer->isB2B() 
            ? new B2BStrategy()
            : new B2CStrategy();
    }
}