<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Meal;
use App\Models\OrderDetail;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Cast\Array_;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request_data = $request->all();
        $orderTotal = 0;
        
        $reservation = Reservation::find($request_data['reservation_id']);
        if($reservation){
            $meals = $request_data['meals'];
    
            $order = new Order();
            $order->reservation_id = $reservation->id;
            $order->table_id = $reservation->table_id;
            $order->customer_id = $reservation->customer_id;
            $order->user_id = $request_data['user_id'];
            $order->date = Carbon::now()->format('Y-m-d H:i:s');
            $order->save();

            foreach($meals as $one_meal){
                $meal = Meal::find($one_meal['meal_id']);
                $mealPrice = $meal->price;
                $mealDiscount = $meal->discount;
                $mealQuantity = $one_meal['quantity'];
                $mealTotal = ($mealPrice * $mealQuantity) - ($mealDiscount * $mealQuantity);
                $orderTotal += $mealTotal;
    
                $order_item = new OrderDetail();
                $order_item->order_id = $order->id;
                $order_item->meal_id = $meal->id;
                $order_item->amount_to_pay = $mealTotal;
                $order_item->save();
            }
    
            $order->total = number_format($orderTotal,2);
            $order->save();

            $res = [
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => $order
            ];
        }else{
            $res = [
                'status' => 'success',
                'message' => 'Reservation is not found',
                'data' => []
            ];
        }

        return json_encode($res);
    }
    
    public function checkout(Request $request){
        $request_data = $request->all();
        $auth = Auth::user();
        $order = Order::find($request_data['order_id'])->where('user_id',$auth->id)->where('status','LIKE','unpaid')->first();
        if($order){
            $table = Table::find($order->table_id);
            $customer = Customer::find($order->customer_id);
        
            if (!$order) {
                return response()->json(['message' => 'No order found'], 404);
            }
        
            $totalAmount = $order->total;
            $taxAmount = $totalAmount * (14 / 100);
            $serviceCharge1 = $totalAmount * (20 / 100);
            $serviceCharge2 = $totalAmount * (15 / 100);
    
            $serviceCharge = $request_data['checkout_type'] == 1 ? $serviceCharge1 : $serviceCharge2;
            $finalAmount = $request_data['checkout_type'] == 1 ? $totalAmount + $taxAmount + $serviceCharge : $totalAmount + $serviceCharge ;
    
            $order->update([
                'paid' => $finalAmount,
                'total' => $finalAmount,
                'status' => 'paid',
            ]);
        
            $invoiceData = [
                'table' => $table->id,
                'customer' => $customer->name,
                'subtotal' => number_format($totalAmount, 2),
                'tax' => $request_data['checkout_type'] == 1 ? number_format($taxAmount, 2) : null,
                'service_charge' => number_format($serviceCharge, 2),
                'total' => number_format($finalAmount, 2),
                'status' => 'Paid',
            ];
    
            return response()->json([
                'message' => 'Checkout completed successfully.',
                'invoice' => $invoiceData,
            ]);
        }else{
            return response()->json([
                'message' => 'There is no unpaid order with this number.',
                'invoice' => [],
            ]);
        }
    }
}
