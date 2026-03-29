<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoicedata extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function diamond()
    {
        return $this->belongsTo(Diamond::class);
    }
}
