<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    
    protected $table = 'users_addresses';

    protected $fillable = [
        'user_id',
        'description',
        'province',
        'city'
    ];

    public function User()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
