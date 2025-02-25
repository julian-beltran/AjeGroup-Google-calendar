<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    //relacion cargo -> user (uno a uno)
    public function users() //user -> si en caso falla
    {
        return $this->belongsToMany(User::class, 'cargo_user', 'id_cargo', 'id_user');
        //return $this->belongsTo(User::class, 'id_user');
    }

    //relacion cargo -> espacios (uno a muchos)
    public function espacios()
    {
        return $this->belongsToMany(Espacio::class, 'espacio_cargo', 'id_cargo', 'id_espacio');
    }

}
