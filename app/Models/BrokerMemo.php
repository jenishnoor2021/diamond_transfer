<?php

namespace App\Models;

use App\Models\Broker;
use App\Models\BrokerMemoItem;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrokerMemo extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function items()
    {
        return $this->hasMany(BrokerMemoItem::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
