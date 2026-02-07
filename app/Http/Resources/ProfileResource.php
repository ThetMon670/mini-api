<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // Actual profile data
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile_image' => $this->profile_image ? Storage::url($this->profile_image) : asset('images/profile-placeholder.png'),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Wrap the response in the structure you want
     */
    public function withResponse($request, $response)
    {
        $originalData = $response->getData(true); // convert resource to array

        $response->setData([
            'success' => $originalData,
            'message' => 'Profile fetched successfully',
        ]);
    }
}
