<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
//add
use Spatie\Permission\Traits\HasRoles;
// For users
use Illuminate\Support\Facades\Auth;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    //add
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    //****************************************************************************************************************************** */
    //relacion user -> corporativo (muchos a uno)
    public function corporativos()
    {
        return $this->belongsToMany(Corporativo::class, 'corporativo_users', 'id_user', 'id_corporativo');
    }

    public function hasAnyCorporativo($corporativo)
    {
        return null !== $this->corporativos()->where('corporativos.id', $corporativo)->first();
    }

    //****************************************************************************************************************************** */
    //relacion user -> cargo
    public function cargos()
    {
        return $this->belongsToMany(Cargo::class, 'cargo_user', 'id_user', 'id_cargo');
        //return $this->hasOne(Cargo::class, 'id_user');
    }

    //Verificando si el usuario tiene un cargo:
    public function hasAnyCargo($cargo)
    {
        return null !== $this->cargos()->where('cargos.id', $cargo)->first();
    }

    //****************************************************************************************************************************** */
    //relacion user -> area ()
    public function areas()
    {
        //return $this->hasMany(Area::class, 'id_user');
        return $this->belongsToMany(Area::class, 'area_user', 'id_user', 'id_area');
    }

    public function hasAnyArea($area)
    {
        return null !== $this->areas()->where('areas.id', $area)->first();
    }
    //****************************************************************************************************************************** */
    //relacion de usuarios a agendas
    /*public function agendas()
    {
        return $this->hasMany(Agenda::class, 'id_user');
    }*/
    public function agendas()
    {
        return $this->belongsToMany(Agenda::class, 'agenda_invitados', 'id_user', 'id_agenda');
    }

    //****************************************************************************************************************************** */



    // Par mostrar info del usuario en el nabvar
    public function adminlte_image()
    {
        $user = Auth::user();
        if ($user) {
            return $user->profile_photo_url;
        } else {
            Log::error('No existe usuario logueado');
            return null;
        }
    }

    /*
     * public function adminlte_image()
        {
            return 'https://picsum.photos/300/300';
        }
    */

    public function adminlte_desc()
    {
        $user = Auth::user();

        if ($user) {
            // Obtener los roles del usuario logueado
            $roles = $user->roles;
            Log::info('roles del user log: '.$roles);

            // Verificar si el usuario tiene roles
            if ($roles->isNotEmpty()) {
                // Obtener el primer rol (asumiendo que un usuario puede tener solo un rol por ahora)
                $primerRol = $roles->first();

                // Obtener el nombre del rol
                $nombreRol = $primerRol->name;
                Log::info('Rol del usuario: '.$nombreRol);
            } else {
                // Loguear un mensaje si el usuario no tiene roles asignados
                Log::info('El usuario logueado no tiene roles asignados');
            }
        } else {
            // Loguear un mensaje si no hay usuario logueado
            Log::info('No hay usuario logueado');
        }

        return $nombreRol;
    }

    public function adminlte_profile_url()
    {
        //return 'profile/update-profile-information-form';
        return route('admin.usuario.profile');
        //return view('config.user.editProfileUser');
    }
}
