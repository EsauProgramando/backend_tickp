<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
  use HasFactory;
  protected $table = 'modules';
  //Relacion uno a muchos
  public function Submodeles()
  {
    return $this->hasMany('App\Models\Submodeles');
  }
}
