<?php

// Bibinhit_10 ***

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Oreder;

class OrderItem extends Model
{
    use HasFactory;

    protected $table='order_items';
    
    protected $fillable = [
        'order_id',
        'user_id',
        'product_id',
        'count'
    ];
    
    // public function Order()
    // {
    //     return $this->belongsTo(Order::class,'parent_id');
    // }



}
