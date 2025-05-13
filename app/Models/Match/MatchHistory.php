<?php

namespace App\Models\Match;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'shown_user_id',
        'activity_user_id'
    ];

        /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shown_user_id', 'id');
    }
}
