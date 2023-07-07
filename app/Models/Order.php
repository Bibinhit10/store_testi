<?php

// Bibinhit_10 ***

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;

    protected $table='orders';
    
    protected $fillable = [
        'status',
        'user_id',
        'addres_id',
        'payment_type',
        'before_discount',
        'discount_id',
        'total_amount',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class , 'order_id' , 'id');
    }
}
