<?php

namespace App\Http\Requests;

use App\Enums\OrderType;
use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|integer',
            'date' => 'required|date',
            'order_type' => 'required|in:dine_in,take_away',
            'voucher_items' => 'required|array',
            'voucher_items.*.menu_id' => 'required|integer|exists:menus,id',
            'voucher_items.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function withResponse($request, $response)
    {
        $originalData = $response->getData(true); // convert resource to array

        $response->setData([
            'success' => $originalData,
            'message' => 'New Voucher is fetched successfully',
        ]);
    }
}
