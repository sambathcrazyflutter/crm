<?php

namespace App\Http\Resources\Products;
use Illuminate\Http\Resources\Json\JsonResource;
class ResourceController extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [

            'name' => $this->name,
            'description' => $this->details,
            'stock' => $this->stock,
            'rating' => $this->reviews->count() > 0 ? round($this->reviews->sum('star')/$this->reviews->count(),2) : 0 ,
            'href' =>[
                'reviews' => route('reviews.index',$this->id)
            ]
        ];
    }
}
