<?php

namespace App\Models\Notification;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'notified_user_id',
        'message',
        'is_that_read'
    ];

    public function notifyUser()
    {
        return $this->belongsTo(User::class, 'notified_user_id', 'id');
    }
}
