<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => 'array',
    ];
    
    protected $fillable = [
        'user_id',
        'creator_user_id',
        'type',
        'description',
        'post_id'
    ];
}
