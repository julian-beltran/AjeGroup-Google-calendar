<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;
    protected $table = 'agendas';

    protected $fillable = [
        'id_user',
        'id_corporativo',
        'id_espacio',
        'id_area',
        'fecha_hora_meet',
        'fecha_hora_termino',
        'estado',
        'summary',
        'location',
        'event_google_id',
        'hangoutLink',
        'htmlLink'
    ];

    //relacion de agenda a AgendaArchivos
    public function agendaArchivos()
    {
        return $this->hasMany(AgendaArchivo::class, 'id_agenda');
    }
    /********************************************************************************************/
    //relacion de agendas a users (muchos a muchos)
    public function users()
    {
        return $this->belongsToMany(User::class, 'agenda_invitados', 'id_agenda', 'id_user');
    }
    public function hasAnyUser($user)
    {
        return null !== $this->users()->where('users.id', $user)->first();
    }
}
