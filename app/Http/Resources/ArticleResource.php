<?php

// Bibinhit_10 ***

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'image' => 'images/'.$this->image,
            'title' => $this->title,
            'text' => $this->text,
            'is_important'=> $this->is_important
        ];
    }
}
