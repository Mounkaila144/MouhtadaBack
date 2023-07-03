<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'type',
        'nom',
        'price',
        'quantite',
        'identifiant',
        'users_id'
    ];
}
