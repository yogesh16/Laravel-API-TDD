<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LikesCollection extends ResourceCollection
{

    protected $post;

    public function setPost($post){
        $this->post = $post;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'status' => true,
            'id' => $this->post->id,
            'title' => $this->post->title,
            'date' => $this->post->created_at->format('d-M-Y H:i'),
            'author' => $this->post->user->name,
            'total_likes' => $this->collection->count(),
            'likes' => LikeResource::collection($this->collection)
        ];
    }
}
