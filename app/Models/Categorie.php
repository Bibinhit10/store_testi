<?php

// Bibinhit_10 ***

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    
    protected $table = 'categories';

    protected $fillable = [
        'title',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo($this,'parent_id');
    }

    public function SubCategorie()
    {
        return $this->hasMany($this,'parent_id');
    }
    

}
