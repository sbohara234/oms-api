<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Services\Order\OrderService;
use App\Trait\JsonApiResponseTrait;
use Exception;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *     title="Order Management API",
 *     version="1.0.0",
 *     description="API documentation for Order Management System"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your JWT token in the format **Bearer <token>**"
 * )
 */
class OrderController extends Controller
{
    use JsonApiResponseTrait;
    public function __construct(
        private OrderService $orderservice
    ) {
        $this->orderservice = $orderservice;
    }


    /**
     * @OA\Get(
     *     path="/api/v1/orders",
     *     summary="Get a list of orders",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter orders by status (e.g., pending, completed)",
     *         @OA\Schema(type="string", example="pending")
     *     ),
     *      @OA\Parameter(
     *         name="customer_id",
     *         in="query",
     *         required=false,
     *         description="Filter orders by customer",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=false,
     *         description="Filter orders created after this date",
     *         @OA\Schema(type="string", format="date", example="2025-09-01")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=false,
     *         description="Filter orders created before this date",
     *         @OA\Schema(type="string", format="date", example="2025-09-30")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of orders",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="amount", type="number", example=150.50)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filters = $request->all(); 
        // $filters['customer_id'] = intval($request->customer_id);
        $data = $this->orderservice->getOrders($user->tenant_id,$filters);
        return $this->successResponse($data);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/orders",
     *     summary="Create a new order with items",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"customer_id","items"},
     *             @OA\Property(property="customer_id", type="integer", example=5, description="ID of the customer placing the order"),
     *             @OA\Property(property="billing_address_id", type="string", example="Baneshor", description="address for billing"),
     *             @OA\Property(property="shipping_address", type="string", example="Koteshor", description="address for shipping"),


     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 description="List of order items",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"product_id","quantity"},
     *                     @OA\Property(property="product_id", type="integer", example=101, description="ID of the product"),
     *                     @OA\Property(property="quantity", type="integer", example=2, description="Number of units")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="customer_id", type="integer", example=5),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="product_id", type="integer", example=101),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="price", type="number", example=150.75)
     *                 )
     *             ),
     *             @OA\Property(property="total_amount", type="number", example=301.50)
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid input"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(OrderRequest $request)
    {

        try {
            $user = Auth::user();

            $orderData = [
                'customer_id' => $request->customer_id,
                'user_id' => $user->id,
                'tenant_id' => $user->tenant_id,
                'shipping_address' => $request->customer_id,
                'billing_address' => $request->customer_id,
                'order_items' => $request->items
            ];
            $order = $this->orderservice->createOrder($orderData);
            return $this->successResponse($order);
        } catch (\Exception $e) {
            logger($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }



    /**
     * @OA\Patch(
     *     path="/api/v1/orders/{id}/status",
     *     summary="Change the status of an order",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the order to update",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"status"},
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 enum={"pending","confirmed","processing","shipped","delivered","completed","cancelled","refunded"},
     *                 example="completed",
     *                 description="New status of the order"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Order status updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=15),
     *             @OA\Property(property="status", type="string", example="completed")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid status"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function updateStatus(Request $request, string $id)
    {
        try{
        $user = Auth::user();

        $order = $this->orderservice->findOrderById($id);
        if(!$order){
            throw new Exception('Order not found',404);
        }
        $status = $request->status;
        $this->orderservice->updateOrderStatus($order,$status);
        return $this->messageResponse('Order status updated successfully.' );
        } catch (\Exception $e) {
            logger($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
