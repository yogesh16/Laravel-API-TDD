<?php

namespace App\Models;

use App\Contracts\Likeable;
use App\Traits\Likes;
use App\Traits\UUIDs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements Likeable
{
    use HasFactory, UUIDs, Likes;

    protected $dates = [
        'created_at', 'updated_at'
    ];

    protected $fillable = [
        'title', 'description', 'image', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
