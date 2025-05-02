<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSpouseCandidate extends Model
{
    use HasFactory;

    protected $table = 'user_spouse_candidate';

    protected $fillable = [
        'user_id',
        'about',
        'tall',
        'weight',
        'eye_color',
        'hair_color',
        'skin_color',
        'body_type',
        'want_a_child',
        'looking_qualities',
        'age_range',
        'marital_status',
        'have_a_child',
        'use_cigarette',
        'use_alcohol',
        'education_status',
        'salary',
        'not_compromise',
        'community',
        'sect',
        'do_you_pray',
        'physical_disability'
    ];
}
