<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class register_inventaries extends Model
{
    use HasFactory;

    protected $table = 'register_inventaries';
    protected $fillable = [
        'codigo_patrimonial',
        'denominacion_bien',
        'nro_doc_adquisicion',
        'cta_con_seguro',
        'fecha_adquisicion',
        'valor_adquisicion',
        
        'nro_cuenta_contable',
        'estado_bien',
        'condicion',
        'valor_adquis',
        'valor_neto',
        'desc_area',
        'marca',
        'modelo',
        'dimension',
        'serie',
        'color',
        'user_id',
    ];
    //Relacion uno a muchos(inversa)
    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }
}
