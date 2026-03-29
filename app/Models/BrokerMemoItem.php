<?php

namespace App\Models;

use App\Models\BrokerMemo;
use App\Models\Diamond;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrokerMemoItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function diamond()
    {
        return $this->belongsTo(Diamond::class);
    }

    public function memo()
    {
        return $this->belongsTo(BrokerMemo::class, 'broker_memo_id');
    }
}
