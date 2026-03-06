<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'voucher_number' => $this->voucher_number,
            'date' => $this->date,
            'cash' => $this->cash,
            'change' => $this->change,
            'total' => $this->total,
            'tax' => $this->tax,
            'net_total' => $this->net_total,
            'voucher_items_count' => $this->voucher_items_count,
            'order_type' => $this->order_type?->value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'records' => $this->records()->get(),
            // Include related records (e.g., associated products) if applicable
            'voucher_items' => VoucherItemResource::collection($this->voucherItems()->get()),
        ];
    }

     public function withResponse($request, $response)
    {
        $originalData = $response->getData(true); // convert resource to array

        $response->setData([
            'success' => $originalData,
            'message' => 'Voucher by ID is fetched successfully',
        ]);
    }
}
