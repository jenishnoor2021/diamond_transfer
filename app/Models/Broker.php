<?php

namespace App\Models;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Broker belongs to location
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Many diamonds
    public function diamonds()
    {
        return $this->belongsToMany(
            Diamond::class,
            'broker_diamonds'
        )->withPivot('issue_date', 'status')
            ->withTimestamps();
    }
}
