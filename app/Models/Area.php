<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// ADD
use Spatie\Permission\Models\Role;

class Area extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    /*******************************************************************************************************************************/
    //relacion area ->espacios (uno a muchos)
    public function espacios()
    {
        return $this->belongsToMany(Espacio::class, 'espacio_area', 'id_area', 'id_espacio');
    }

    /*******************************************************************************************************************************/
    //relacion areas -> user ()
    public function users() //user -> en caso falle
    {
        return $this->belongsToMany(User::class, 'area_user', 'id_area', 'id_user');
    }
}
