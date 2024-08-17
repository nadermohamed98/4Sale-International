<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;

class MealController extends Controller
{
    public function index()
    {
        $meals = Meal::all();
        if($meals){
            $res = array(
                'status' => 'success',
                'data' => $meals,
                'code' => 200
            );
        }else{
            $res = array(
                'status' => 'error',
                'data' => [],
                'code' => 400
            );
        }
        return json_encode($res);
    }
}
