<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Meal;
use App\Models\OrderDetail;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Array_;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
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
            $order->date = Carbon::createFromFormat('Y-m-d H:i:s',$request_data['date']);
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
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
