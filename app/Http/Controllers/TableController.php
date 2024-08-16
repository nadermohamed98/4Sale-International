<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TableController extends Controller
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
     * @param  \App\Http\Requests\StoreTableRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTableRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function show(Table $table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function edit(Table $table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTableRequest  $request
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTableRequest $request, Table $table)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function destroy(Table $table)
    {
        //
    }

    public function checkAvilability(Request $request)
    {
        $request_data = $request->all();
        $table = Table::findOrFail($request_data['table_id']);

        if ($table->capacity < $request_data['guest_no']) {
            $res = array(
                'status' => 'success',
                'message' => 'Guests Number Is More Than Table Capacity',
                'is_available' => false,
                'code' => 200
            );
        } else {
            $reservation = Reservation::where('table_id', $request_data['table_id'])
                ->where('from_time', '<=', $request_data['date'])
                ->where('to_time', '>=', $request_data['date'])
                ->first();

            if ($reservation) {
                $res = array(
                    'status' => 'success',
                    'message' => 'Table is NOT Available in this time',
                    'is_available' => false,
                    'code' => 200
                );
            } else {
                $res = array(
                    'status' => 'success',
                    'message' => 'Table Is Available In This Time',
                    'is_available' => true,
                    'code' => 200
                );
            }
        }

        return $res;
    }

    public function reserveTable(Request $request)
    {
        $checkAvilability = $this->checkAvilability($request);
        
        if($checkAvilability['is_available']){
            $request_data = request()->all();
            $reservation = new Reservation();

            $time_from = Carbon::createFromFormat('Y-m-d H:i:s',$request_data['date']);

            $reservation->table_id = $request_data['table_id'];
            $reservation->customer_id = $request_data['customer_id'];
            $reservation->from_time = $time_from;
            $reservation->to_time = (clone $time_from)->modify('+2 hours');

            $reservation->save();

            $res = [
                'status' => 'success', 
                'message' => 'Table Reserved Successfully',
                'data' => $reservation,
                'code' => 200
            ];
        }else{
            $res = [
                'status' => 'success', 
                'message' => $checkAvilability['message'],
                'data' => [],
                'code' => 200
            ];
        }
        return json_encode($res);
    }
}
