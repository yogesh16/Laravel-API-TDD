<?php


namespace App\Traits;


use App\Models\Like;

trait Likes
{
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
