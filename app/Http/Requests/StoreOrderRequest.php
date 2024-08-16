<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'table_id' => 'required',
            'reservation_id' => 'required',
            'customer_id' => 'required',
            'user_id' => 'required',
            'meals' => 'required|array',
            'total' => 'required|numeric',
            'paid' => 'required|numeric|lte:total',
            'date' => 'required',
        ];
    }
}
