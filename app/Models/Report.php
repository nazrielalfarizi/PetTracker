<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'resident_id',
        'type',
        'title',
        'description',
        'image',
        'latitude',
        'longitude',
        'address',
        'last_seen_location',
        'pet_characteristics',
        'pet_condition',
        'status',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    // Helper biar rapi
    public function isLost()
    {
        return $this->type === 'kehilangan';
    }

    public function isFound()
    {
        return $this->type === 'temuan';
    }
}

