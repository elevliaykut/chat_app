<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'photo_path',
        'media_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'id', 'post_id');
    }
}
