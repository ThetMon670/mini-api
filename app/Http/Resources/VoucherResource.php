<?php

namespace App\Http\Resources;

use App\Enums\OrderType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
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
            'date' => $this->date,
            'total' => $this->total,
            'tax' => $this->tax,
            'net_total' => $this->net_total,
            'voucher_items_count' => $this->voucher_items_count,
            'type' => config('base.type')[$this->type] ?? $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function withResponse($request, $response)
    {
        $originalData = $response->getData(true); // convert resource to array

        $response->setData([
            'success' => $originalData,
            'message' => 'Vouchers are fetched successfully',
        ]);
    }
}
