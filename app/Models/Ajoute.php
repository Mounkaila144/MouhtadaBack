<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ajoute extends Model
{
    use HasFactory;
    protected $fillable = [
        'contenue',
        'user_id'
    ];
}
