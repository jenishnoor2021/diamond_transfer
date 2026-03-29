<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $guarded = [];

    // One location has many diamonds
    public function diamonds()
    {
        return $this->hasMany(Diamond::class);
    }

    // Brokers in this location
    public function brokers()
    {
        return $this->hasMany(Broker::class);
    }

    // Transfers FROM this location
    public function transfersFrom()
    {
        return $this->hasMany(DiamondTransfer::class, 'from_location_id');
    }

    // Transfers TO this location
    public function transfersTo()
    {
        return $this->hasMany(DiamondTransfer::class, 'to_location_id');
    }
}
