<?php

namespace App\Models\Definitions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
        'lat',
        'long'
    ];

    /**
     * @return BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
