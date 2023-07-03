<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie');
    }
   public function suplier()
    {
        return $this->belongsTo(Suplier::class, 'suplier');
    }

    protected $fillable = [
        'name',
        'picture',
        'price',
        'stock',
        'suplier',
        'vendue',
        'alert',
        'categorie'
    ];
}
