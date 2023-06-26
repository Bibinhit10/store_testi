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
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class , 'order_id' , 'id');
    }
}
