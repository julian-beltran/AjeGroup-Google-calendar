<?php

namespace App\Http\Controllers\agendas;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Espacio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

/*
 * Este controller contiene todos los métodos correspondientes a la funciondalidad de usuarios normales con cargos de colaboradores, etc.
 * Los cuales pueden entrar a ver sus agendas programadas, ver las evidencias que tienen las agendas.
*/
class AgendaInivitadoController extends Controller
{
    // proteccion de ruta
    public function __consruct(){
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->can('Ver invitaciones')
                && !Auth::user()->can('Ver reportes')
                && !Auth::user()->can('Ver agendas')
                && !Auth::user()->can('Ver todo')
                && !Auth::user()->can('Administracion')) {
                    return response()->view('usuarios_vistas.users.error_403', [], 403);
            }
            return $next($request);
        });
    }

    public function index() {
        $usuario = Auth::user();
        $username = $usuario->name;
        $cargosUser = $usuario->cargos;
        $areasUser = $usuario->areas;
        // Para filtro de espacios
        $espacios = Espacio::all();

        return view('usuarios_vistas.agenda_invitados.lista_agenda_invitados', [
            'username' => $username,
            'cargosUser' => $cargosUser,
            'areasUser' => $areasUser,
            'espacios' => $espacios,
        ]);
    }

    // Método para verificar la lista de las agendas que tiene un usuario logueado: (invitado) -> estos son usuarios invitados.
    public function lista_agendas_programadas(Request $request)
    {
        $usuario = Auth::user();
        $id_user_log = $usuario->id;

        //Selecciona las agendas que tiene el usuario logueado a partir del agenda_invitados.id_user ya que tiene los id de usuarios invitados
        $query = DB::table('agenda_invitados')
            ->select(
                'agendas.id as id', 'agendas.id_user as anfitrion_id',
                DB::raw('(select name from users where users.id = agendas.id_user) as nombre_anfitrion'), 'agenda_invitados.id_user as invitado_id',
                DB::raw('(select name from users where users.id = agenda_invitados.id_user) as nombre_invitado'),
                'agendas.fecha_hora_meet as fecha_hora', 'areas.id as area', 'areas.nombre as area_nombre',
                'espacios.id as espacio', 'espacios.nombre as espacio_nombre', 'espacios.config as tipo',
                'corporativos.nombre as corporativo_nombre', 'agendas.estado as estado',
                'agendas.hangoutLink as meet_link', 'agenda_invitados.id as id_agenda_invitados'
            )
            ->join('agendas', 'agendas.id', '=', 'agenda_invitados.id_agenda')
            ->join('users', 'users.id', '=', 'agenda_invitados.id_user')
            ->join('areas', 'areas.id', '=', 'agendas.id_area')
            ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
            ->join('corporativos', 'corporativos.id', '=', 'agendas.id_corporativo')
            ->where('agenda_invitados.id_user', $id_user_log);

        // Aplicar los filtros
        if($request->estado != ''){
            $query->where('agendas.estado', $request->estado);
        }
        if($request->fecha_desde != '' && $request->fecha_hasta != ''){
            $query->whereBetween('agendas.fecha_hora_meet', [$request->fecha_desde, $request->fecha_hasta]);
        }
        if($request->espacio != ''){
            $query->where('espacios.id', $request->espacio);
        }

        $userAgendas = $query;

        return DataTables::of($userAgendas)
            ->addColumn('action', function ($data){
                $dropdown = '<div class="dropdown">';
                $dropdown .= '<button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" id="dropdownMenuButton" aria-expanded="false">';
                $dropdown .= 'Acciones';
                $dropdown .= '</button>';
                $dropdown .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="padding: 3px;">';
                $dropdown .= '<button onclick="verEvidencias('.$data->id.')" class="dropdown-item btn btn-outline-info" title="Ver evidencias" style="color:#0d6efd; background-color: #B4E4FF;"><i class="fas fa-eye" style="font-size: 18px;"></i> Evidencias</button>';
                $dropdown .= '</div>';
                $dropdown .= '</div>';

                return $dropdown;
            })
            ->toJson();
    }

    // Método para obtener los datos de la agenda:
    public function ver_evidencias_de_agenda($id)
    {
        if (request()->ajax()) {
            // Obtener la agenda con los archivos asociados
            $agenda = Agenda::select(
                'ag.id',
                'us.name as user_name',
                'agar.archivos as archivos',
                'ag.fecha_hora_meet'
            )
                ->from('agendas as ag')
                ->join('agenda_archivos as agar', 'agar.id_agenda', '=', 'ag.id')
                ->join('users as us', 'us.id', '=', 'agar.id_user')
                ->where('ag.id', $id)
                ->first();

            if (!$agenda) {
                return response()->json(['error' => 'No se encontró la agenda solicitada.']);
            }

            // Decodificar el JSON de archivos
            $archivos = json_decode($agenda->archivos, true);

            // Preparar la lista de descargables
            $descargables = [];
            foreach ($archivos as $nombreArchivo) {
                $urlDescarga = '/'.$nombreArchivo; // Storage::url($nombreArchivo);
                $descargables[] = ['nombre' => $nombreArchivo, 'url' => $urlDescarga];
            }

            return response()->json(['agenda' => $agenda, 'descargables' => $descargables]);
        }
    }

}
