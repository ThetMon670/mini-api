<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherItemResource extends JsonResource
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
            'voucher_id' => $this->voucher_id,
            'menu' => $this->menu,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'cost' => $this->cost,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        
    }

    public function withResponse($request, $response)
    {
        $originalData = $response->getData(true); // convert resource to array

        $response->setData([
            'success' => $originalData,
            'message' => 'VoucherItems are fetched successfully',
        ]);
    }
}
