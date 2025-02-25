<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;

    protected $fillable =['nombre'];

    //Relacion de pais -> corporativo (uno a muchos)
    public function corportativos()
    {
        return $this->hasMany(Corporativo::class, 'id_pais');
    }
}
