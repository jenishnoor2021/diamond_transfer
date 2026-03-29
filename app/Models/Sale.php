<?php

namespace App\Models;

use App\Models\Diamond;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function diamond()
    {
        return $this->belongsTo(Diamond::class);
    }
}
