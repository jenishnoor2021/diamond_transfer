<?php

namespace App\Models;

use App\Models\BrokerMemoItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diamond extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Diamond belongs to a location
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Diamond transfers
    public function transfers()
    {
        return $this->hasMany(DiamondTransfer::class);
    }

    // Many-to-many with brokers
    public function brokers()
    {
        return $this->belongsToMany(
            Broker::class,
            'broker_diamonds'
        )->withPivot('issue_date', 'status')
            ->withTimestamps();
    }

    public function brokerMemoItems()
    {
        return $this->hasMany(BrokerMemoItem::class, 'diamond_id');
    }

    public function diamondTransfers()
    {
        return $this->hasMany(DiamondTransfer::class);
    }
}
