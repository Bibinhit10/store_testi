<?php

// Bibinhit_10 ***

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'discounts';

    protected $fillable = [
        'code',
        'discount_percent',
        'max_amount',
        'expiresat',
    ];
    

}
