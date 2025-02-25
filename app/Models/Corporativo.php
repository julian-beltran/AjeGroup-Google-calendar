<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corporativo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'id_pais'];

    //****************************************************************************************************************************** */
    //relacion a Pais (muchos a uno)
    public function pais()
    {
        return $this->belongsTo(Pais::class, 'id_pais');
    }

    //****************************************************************************************************************************** */
    //relacion de corporativo -> users (uno a muchos)
    public function users()
    {
        return $this->belongsToMany(User::class, 'corporativo_users', 'id_corporativo', 'id_user');
    }

    //****************************************************************************************************************************** */
    //relacion corporativo -> espacios (uno a muchos)
    public function espacios()
    {
        return $this->hasMany(Espacio::class, 'id_corporativo');
    }
}
