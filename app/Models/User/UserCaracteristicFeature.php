<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCaracteristicFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question_one',
        'question_two',
        'question_three',
        'question_four',
        'question_five',
        'question_six',
        'question_seven',
        'question_eight',
        'question_nine',
        'question_ten',
        'question_eleven',
    ];
}
