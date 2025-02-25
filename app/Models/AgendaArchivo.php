<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaArchivo extends Model
{
    use HasFactory;
    protected $fillable = ['id_agenda', 'archivos', 'id_user']; //'url_archivo'

    //relacion de agendaArchivos a Agenda
    public function agendas()
    {
        return $this->belongsTo(Agenda::class, 'id_agenda');
    }
}
