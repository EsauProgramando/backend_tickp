<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Submodule;

class Rolpermiso extends Model
{
  use HasFactory;
  protected $table = 'rolpermiso';
  //created_at y updated_at no se incluyen en el modelo
  public $timestamps = false;
  //Relacion uno a muchos(inversa)

  public function Rol()
  {
    return $this->belongsTo('App\Models\Rol');
  }

  //Relacion uno a muchos(inversa)

  public function Submodeles()
  {
    return $this->belongsTo('App\Models\Submodeles');
  }
  public function submodulo()
  {
    return $this->belongsTo(Submodule::class);
  }
}
