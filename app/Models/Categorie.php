<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    public function products()
    {
        return $this->hasMany(Product::class, 'categorie');
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($categorie) {
            $categorie->products()->delete();
        });
    }

    protected $fillable = [
        'name',
    ];
}
