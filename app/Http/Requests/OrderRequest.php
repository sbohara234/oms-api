<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // $rules = [
        //     'customer_id' => 'required|integer',
        //     'shipping_address'=>'required|string|max:100',
        //     'billing_address'=>'required|string|max:100',
        //     'payment_methos'=>'required'

        // ];


        // return $rules;
        return [];
    }
}
