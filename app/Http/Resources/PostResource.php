<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => url('storage/images/'.$this->image),
            'description' => $this->description,
            'date' => $this->created_at->format('d-M-Y H:i'),
            'author' => $this->user->name,
            'total_likes' => $this->likes()->count(),
            'likes' => LikeResource::collection($this->likes()->latest('created_at')->limit(5)->get())
        ];
    }
}
