<?php

namespace App\Models;

use App\Models\Diamond;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiamondTransfer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function diamond()
    {
        return $this->belongsTo(Diamond::class);
    }

    // From location
    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    // To location
    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }
}
