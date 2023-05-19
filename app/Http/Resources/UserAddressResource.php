<?php

// Bibinhit_10 ***

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        
        return [
            'id' => $this->id,
            'description' => $this->description,
            'province' => $this->province,
            'city' => $this->city,
        ];
    }
}
