<?php

// Bibinhit_10 ***

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'title',
        'description',
        'features',
        'image_1',
        'image_2',
        'image_3',
        'subcategory_id',
        'home_page',
        'stock',
        'price',
        'after_discount',
        'discount_percent',
        'sold_count',
    ];
    
    public function parent()
    {
        return $this->belongsTo(Categorie::class,'subcategory_id','id');
    }

    public function SubCategorie()
    {
        return $this->belongsTo(Categorie::class,'subcategory_id','parent_id');
    }
    

}
