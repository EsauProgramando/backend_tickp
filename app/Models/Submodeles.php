<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submodeles extends Model
{
    use HasFactory;
     protected $table = 'submodules';
    //Relacion uno a muchos(inversa)

    public function Module()
    {
        return $this->belongsTo('App\Models\Module');
    }

    //Relacion uno a muchos
     public function Rolpermiso(){
        return $this->hasMany('App\Models\Rolpermiso');
     }
}
