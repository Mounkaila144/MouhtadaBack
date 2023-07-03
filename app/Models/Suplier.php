<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suplier extends Model
{
    public function products()
    {
        return $this->hasMany(Product::class, 'suplier');
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($suplier) {
            $suplier->products()->delete();
        });
    }
    protected $fillable = [
        'name',
        'adresse',
        'phone'
    ];
}
