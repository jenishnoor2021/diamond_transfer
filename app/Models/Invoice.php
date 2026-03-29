<?php

namespace App\Models;

use App\Models\Invoicedata;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Invoicedata::class, 'invoice_id');
    }
}
