<?php

// Bibinhit_10 ***

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountUser extends Model
{
    use HasFactory;

    protected $table = 'discount_users';

    protected $fillable = [
        'user_id',
        'discount_id'
    ];


}
