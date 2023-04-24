<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class register_inventaries extends Model
{
    use HasFactory;
    //Relacion uno a muchos(inversa)
    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }
}
