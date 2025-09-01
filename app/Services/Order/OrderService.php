<?php
// app/Services/PricingService.php
namespace App\Services\Order;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\Order\Pricing\PricingFactory;
use App\Services\Order\Pricing\PricingContext;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Events\TransactionBeginning;

class OrderService
{

    public function getOrders($tenant_id,$filter=[] ,$perpage=10)
    {
        return $orders = Order::with('orderItems','customer')
        ->where('tenant_id',$tenant_id)
    ->when(!empty($filter['status']), function ($query) use ($filter) {
        $query->where('status', $filter['status']);
    })
    ->when(!empty($filter['customer_id']), function ($query) use ($filter) {
        $query->where('customer_id', $filter['customer_id']);
    })
    
    ->when(!empty($filter['start_date']) && !empty($filter['end_date']), function ($query) use ($filter) {
        $query->whereBetween('created_at', [$filter['start_date'], $filter['end_date']]);
    })
    ->paginate($perpage);
    }
    public function createOrder($inputData)
    {
        DB::beginTransaction();

        try {
            $customer = Customer::find($inputData['customer_id']);


            $amounts['discount'] = 0;
            $amounts['tax'] = 0;
            $amounts['subtotal'] = 0;
            $amounts['shippingCost'] = 0;

            $strategy = PricingFactory::createForCustomer($customer);
            $pricingContext = new PricingContext($strategy);

            $orderItemData = [];
            foreach ($inputData['order_items'] as $key => $item) {

                $product = Product::find($item['product_id']);
                $orderItemData[$key]['tenant_id'] = $inputData['tenant_id'];
                $orderItemData[$key]['product_id'] = $item['product_id'];
                $orderItemData[$key]['quantity'] = $item['quantity'];

                $productPriceData = $pricingContext->calculateProductPrice(
                    $product,
                    $item['quantity']
                );
                $orderItemData[$key]['unit_price'] = $productPriceData['unit_price'];
                $orderItemData[$key]['total_price'] = $productPriceData['subtotal'];
                $orderItemData[$key]['tax_amount'] = $productPriceData['tax_amount'];
                $orderItemData[$key]['discount_amount'] = $productPriceData['discount_amount'];

                $amounts['discount'] += $productPriceData['discount_amount'];
                $amounts['tax'] += $productPriceData['tax_amount'];
                $amounts['subtotal'] += $productPriceData['subtotal'];
                $amounts['shippingCost'] += $productPriceData['shipping_cost'];;
            }
            $order = $this->saveProductData($inputData, $orderItemData, $amounts);

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function findOrderById($id){
        return Order::find($id);
    }

    public function updateOrderStatus(Order $order, $status)
    {        
        $order->status = $status;
    if (in_array($status, ['paid', 'refunded'])) {
        $order->payment_status = $status;
    }
    return $order->save();
    }

    private function saveProductData($inputData, $orderItemData, $amounts)
    {
        $order = new Order();
        $order->tenant_id = $inputData['tenant_id'];
        $order->user_id = $inputData['user_id'];
        $order->customer_id = $inputData['customer_id'];
        $order->billing_address = $inputData['billing_address'];
        $order->notes = isset($inputData['notes']) ?? $inputData['notes'];
        $order->payment_method = isset($inputData['payment_method']) ?? $inputData['payment_method'];
        $order->shipping_address = $inputData['shipping_address'];


        $order->shipping_cost = $amounts['shippingCost'];
        $order->subtotal = $amounts['subtotal'];
        $order->tax_amount = $amounts['tax'];
        $order->discount_amount = $amounts['discount'];
        $totalAmount = $amounts['subtotal'] + $amounts['tax'] + $amounts['shippingCost'] - $amounts['discount'];
        $order->total_amount = $totalAmount;

        /** generate unique order number serially */
        $orderId = \App\Models\Order::max('id') + 1;
        $orderNo = 'ORD-' . str_pad($orderId, 6, '0', STR_PAD_LEFT);

        $order->order_number = $orderNo;
        $order->save();
        $orderId = $order->id;

        $orderItemData = array_map(function ($item) use ($orderId) {
            $item['order_id'] = $orderId;
            return $item;
        }, $orderItemData);

        OrderItem::insert($orderItemData);
        $order->load('orderItems');
        return $order;
    }
}
