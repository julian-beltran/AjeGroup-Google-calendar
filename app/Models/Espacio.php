<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_corporativo',
        'nombre',
        'descripcion',
        'config',
        'tipo_reunion',
        'frecuencia',
        'adjunto',
        'guia'
    ];

    /*******************************************************************************************************************************/
    //Relacion de espacios -> cargo (muchos a uno)
    public function cargos()
    {
        return $this->belongsToMany(Cargo::class, 'espacio_cargo', 'id_espacio', 'id_cargo');
    }

    public function hasAnyCargo($cargo)
    {
        return null !== $this->cargos()->where('cargos.id', $cargo)->first();
    }

    /*******************************************************************************************************************************/
    //relacion espacios -> corporativo (muchos a uno)
    public function corporativo()
    {
        return $this->belongsTo(Corporativo::class, 'id_corporativo');
    }

    /*******************************************************************************************************************************/
    //relacion espacios -> area (muchos a uno
    public function areas()
    {
        return $this->belongsToMany(Area::class, 'espacio_area', 'id_espacio', 'id_area');
    }

    public function hasAnyArea($area)
    {
        return null !== $this->areas()->where('areas.id', $area)->first();
    }
}
