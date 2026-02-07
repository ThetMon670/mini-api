<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // This is the main category data
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'owner' => $this->user,
        ];
    }

    /**
     * Customize the full JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $originalData = $response->getData(true); // get the array form

        $response->setData([
            'data' => [
                'success' => $originalData,   // wrap the actual resource
                'message' => 'Category fetched successfully',
            ]
        ]);
    }
}
