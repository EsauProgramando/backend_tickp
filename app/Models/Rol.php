<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{

    use HasFactory;

    protected $table = 'rols';
    //created_at y updated_at no se incluyen en el modelo
    public $timestamps = false;

    //Relacion uno a muchos
    public function User()
    {
        return $this->hasMany('App\Models\User');
    }

    //Relacion uno a muchos
     public function Rolpermiso(){
        return $this->hasMany('App\Models\Rolpermiso');
     }
}
