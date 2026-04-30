<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->Title,                 
            'description' => $this->Description,     
            'sentiment' => $this->Sentiment,         
            'actor' => $this->Actor,                 
            'category' => $this->Category,           
            'priority' => $this->Priority,           
            'tag' => $this->Tag,                     
            'region' => $this->Region,
            'location' => $this->Location,           
            'latitude' => $this->Latitude,           
            'longitude' => $this->Longitude,         
            'view_count' => $this->ViewCount,        
            'published_at' => $this->PublishedDate,  
            'escalated_at' => $this->EscalatedDate,  
            'created_by' => $this->creator ? [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
                'email' => $this->creator->email
            ] : null,
            'attachments' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($image) {
                    return [
                        'id'          => $image->id,
                        'path'        => $image->Path,
                        'description' => $image->Description,
                        'order'       => $image->lsOrder,
                        'url'         => $image->Path
                            ? asset('storage/'.$image->Path)
                            : null,
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}