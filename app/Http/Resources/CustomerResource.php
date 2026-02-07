<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    protected string $message;

    public function __construct($resource, string $message = '')
    {
        parent::__construct($resource);
        $this->message = $message;
    }

    public function toArray($request)
    {
        return [
            'data' => [
                'customer' => [
                    'id' => $this->id,
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'date_of_birth' => $this->date_of_birth,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                    'owner' => $this->user,
                ],
                'message' => $this->message,
            ]
        ];
    }
}
