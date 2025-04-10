<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBlocked extends Model
{
    use HasFactory;

    protected $fillable = [
        'blocker_id',
        'blocked_id'
    ];

}
