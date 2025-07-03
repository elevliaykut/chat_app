<?php

namespace App\Models\Report;

use App\Models\User;
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

    /**
     * @return HasMany
     */
    public function creatorUser()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
