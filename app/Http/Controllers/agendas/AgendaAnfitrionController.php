<?php

namespace App\Http\Controllers\agendas;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Espacio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
//add for img
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Agenda;
use App\Models\Area;
use App\Models\AgendaArchivo;

// Para generar eventos en Google Calendar
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use GuzzleHttp\Client;


/*
 * Este controller contiene todos los métodos correspondientes para los usuarios que tienen cargos de jefes, lider, etc.
 * Los cuales pueden generar las agendas de un espacio según su cargo, asimismo pueden agregar las evidencias de esas agendas,
 * pueden ver las evidencias que subieron.
*/

class AgendaAnfitrionController extends Controller
{
    // Método para obtener el servicio de google calendar y realizar las actualizaciones y delete de agendas:
    function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig('client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);

        $guzzleClient = new Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);
        $this->client=$client;

        // Proteccion de rutas:
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->can('Ver todo')
                && !Auth::user()->can('Administracion')
                && !Auth::user()->can('Ver agendas')) {
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
        // Para filtros:
        $espacios = Espacio::all();
        $areas = Area::all();

        return view('usuarios_vistas.agenda_anfitriones.lista_agenda_jefes', [
            'username' => $username,
            'cargosUser' => $cargosUser,
            'areasUser' => $areasUser,
            'espacios' => $espacios,
            'areas' => $areas,
        ]);
    }

    // Método para obtener la lista de agendas que a relizado el anfitrion logueado:
    public function ver_agendas_realizadas(Request $request)
    {
        $usuario = Auth::user();
        $idUser = $usuario->id;
        $areasUser = User::find($idUser)->areas;
        // Log::info('Areas obtenidas: '.json_encode($areasUser));

        $query = Agenda::select('agendas.id as agenda_id', 'agendas.id_user AS userLog', 'users.name as usuario_log', 'agendas.fecha_hora_meet AS fecha_hora', 'areas.id', 'areas.nombre AS areas', 'espacios.nombre as espacio_nombre', 'espacios.config as tipo', 'corporativos.nombre as corporativo_nombre','agendas.location as location', 'agendas.estado AS estado')
            ->join('areas', 'areas.id', '=', 'agendas.id_area')
            ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
            ->join('corporativos', 'corporativos.id', '=', 'agendas.id_corporativo')
            ->join('users', 'users.id', '=', 'agendas.id_user');

        $query->where('agendas.id_user', $idUser);

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
        if($request->area != ''){
            $query->where('areas.id', $request->area);
        }

        $dataAgendas = $query;

        return DataTables::of($dataAgendas)
            ->addIndexColumn()
            ->toJson();
    }

    // Método para ver los datos de la agenda (obtiene sus datos con AJAX para ver en un modal):
    public function show_datos_agenda(int $id)
    {
        try {
            if(request()->ajax()){
                $agenda = Agenda::find($id);

                if ($agenda) {
                    Log::info('Agenda obtenida: '.json_encode($agenda));
                    return response()->json(['agenda'=>$agenda]);
                } else {
                    Log::error('Agenda no obtenida.');
                    return response()->json(['error' => 'Agenda no encontrada'], 404);
                }
            }
        } catch (\Exception $ex) {
            Log::error('Error en el acceso a las agendas: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error en el servidor.'], 500);
        }
    }

    // Método para obtener los usuarios invitados de una agenda
    public function ver_invitados_de_agenda(int $id)
    {
        try {
            // Log::info('ID_AGENDA RECIBIDA: '.json_encode($id));

            if(request()->ajax()){
                $invitados = DB::table('agenda_invitados')
                    ->select(
                        'agenda_invitados.id AS id_agenda_invitado',
                        'agendas.fecha_hora_meet AS fecha_hora_meet',
                        'agenda_invitados.id_agenda AS agenda_id',
                        'espacios.nombre AS nombre_espacio',
                        'areas.nombre AS nombre_area',
                        DB::raw('(SELECT name FROM users WHERE id = agendas.id_user) AS nombre_anfitrion'),
                        DB::raw('(SELECT name FROM users WHERE id = agenda_invitados.id_user) AS nombre_invitado')
                    )
                    ->join('agendas', 'agendas.id', '=', 'agenda_invitados.id_agenda')
                    ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                    ->join('areas', 'areas.id', '=', 'agendas.id_area')
                    ->where('agenda_invitados.id_agenda', $id)
                    ->get();

                Log::info('Invitados de la agenda obtenida: ['. $invitados .']');
                return response()->json(['agenda'=>$invitados]);
            }
        } catch (\Exception $ex) {
            Log::error('Error en el acceso a los invitados de la agenda: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los invitados.'], 500);
        }
    }

    // Método para subir la evidencia de una agenda -> solo el anfitrion genera las evidencias de las agendas que ha realizado
    public function subir_evidencia_agenda(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'agenda_evidencia_file.*' => 'required|file|max:2048',
                'id_agenda' => 'required|exists:agendas,id',
                'nombre_archivo' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Error de validacion', 'errors' => $validator->errors()], 422);
            }
            // Verificar si el usuario está autenticado
            if (!Auth::check()) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }
            $archivos = $request->file('agenda_evidencia_file');
            $id_agenda = $request->input('id_agenda');
            $nombre_archivo = $request->input('nombre_archivo');

            $urls_archivos = [];
            foreach ($archivos as $archivo) {
                $rutaGuardarImg = 'storage/'; // Carpeta donde se guardarán los archivos
                $nombreAdjunto = $nombre_archivo .'_'. uniqid() .'.' . $archivo->getClientOriginalExtension();
                $archivo->move($rutaGuardarImg, $nombreAdjunto); // Mover el archivo a la carpeta de almacenamiento

                $url_archivo = $nombreAdjunto; //$rutaGuardarImg .  ....
                $urls_archivos[] = $url_archivo;
            }

            // Convertir el array de URLs a JSON
            $json_urls_archivos = json_encode($urls_archivos);

            // Obtener el ID del usuario autenticado
            $idUser = Auth::id();
            // Guardar en la base de datos
            $agenda_archivo = new AgendaArchivo([
                'archivos' => $json_urls_archivos,
                'id_agenda' => $id_agenda,
                'id_user' => $idUser
            ]);
            $agenda_archivo->save();

            // Validacion para la actualización de la agenda por su id [estado a terminado]
            try {
                $agenda = Agenda::findOrFail($id_agenda);
                $agenda->estado = 'terminado';
                $agenda->save();

                Log::info('Estado de la agenda actualizada correctamente: '.$agenda);
            } catch (\Exception $ex) {
                Log::error('Error al actualizar el estado de la agenda: ' . $ex->getMessage());
            }

            Log::info('Evidencia agregada correctamente: ID: [' . $agenda_archivo->id . '] ' . 'Nombre: ' . $nombreAdjunto . '. AGENDA CAMBIADA DE ESTADO: ['.$id_agenda.' +++ ' .$agenda. ']');
            return response()->json(['message' => 'Evidencia agregada correctamente'], 200);
        } catch (\Exception $ex) {
            Log::error('Error al subir la evidencia: ' . $ex->getMessage());
            return response()->json(['error' => 'Error al subir la evidencia'], 500);
        }
    }

    // Método para ver las evidencias de una agenda y poder descargarlas -> el anfitrion puede agregar evidencias y ver las evidencias.
    public function ver_evidencias_agendas(int $id)
    {
        try{
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
                    $urlDescarga = Storage::url($nombreArchivo);
                    $descargables[] = ['nombre' => $nombreArchivo, 'url' => $urlDescarga];
                }
                return response()->json(['agenda' => $agenda, 'descargables' => $descargables]);
            }
        } catch (\Exception $ex) {
            Log::error('Error la acceder a las evidencias de la agenda: ' . $ex->getMessage());
            return response()->json(['error' => 'Error la acceder a las evidencias de la agenda'], 500);
        }
    }

    //-------------------------------------------------------------------------------------------------------------------------------
    // Administracionn de las agendas creadas en agendas, google calendar y agenda_invitados.
    public function editar_agenda(int $id)
    {
        Log::info('llegó al controller para editar la agenda: ');
        try{
            if(request()->ajax()){
                $agenda = Agenda::find($id);
                Log::info('Datos obtenidos de la agenda: '.$agenda);
                return response()->json(['agenda'=>$agenda]);
            }
        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos de la agenda (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
    public function update_agenda(Request $request)
    {
        Log::info('Llego a controller para editar agenda');
        try{
            session_start();
            Log::info('ha pasado la sesion');
            if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);

                Log::info('Pasó la condicional de token');

                $id_agenda = $request->input('id_agenda');
                Log::info('ID_AGENDA: '.json_encode($id_agenda));

                $agenda_update = Agenda::findOrFail($id_agenda);
                Log::info('Agenda obtenida para edición => '. json_encode($agenda_update));

                if (!$agenda_update) {
                    return response()->json(['error' => true, 'message' => 'La agenda no se encontró en la base de datos'], 404);
                }

                $validator = Validator::make($request->all(), [
                    'input_fecha_hora' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error' => true, 'errors' => $validator->errors()]);
                }

                $fecha_hora = $request->input('input_fecha_hora');

                // Definiendo las fechas/horas:
                $startDateTime = Carbon::parse($fecha_hora);
                $endDataTime   = Carbon::parse($fecha_hora)->addHour();

                // El id del calendar_API
                $google_event_id = Agenda::where('id', $id_agenda)->value('event_google_id');
                Log::info('Id del evento en google calendar obtenida: '.$google_event_id);


                // Recuperacion evento de la API
                $event = $service->events->get('primary', $google_event_id);


                $start = new Google_Service_Calendar_EventDateTime();
                $start->setDateTime($startDateTime);
                $start->setTimeZone('America/Bogota');
                $event->setStart($start);


                $end    = new Google_Service_Calendar_EventDateTime();
                $end->setDateTime($endDataTime);
                $end->setTimeZone('America/Bogota');
                $event->setEnd($end);

                // Procede a actualizar la fecha y hora del evento:
                // $updateEventGoogle = $service->events->update('primary', $event->Id(), $event);

                $updateEventGoogle = $service->events->update('primary', $event->id, $event);


                if (!$updateEventGoogle) {
                    return response()->json(['status' => 'error', 'message' => 'Ha sucedido un error']);
                }

                // Actualizamos la fecha/hora en la base de datos
                $agenda_update->fecha_hora_meet = $fecha_hora;
                $agenda_update->save();

                Log::info('Agenda actualizada correctamente: ');
                return response()->json(['success' => true, 'message' => 'Agenda actualizada correctamente']);

            }else{
                return redirect('/oauth');
            }

        } catch (\Exception $ex) {
            Log::error('Error al actualizar la agenda: ' . $ex->getMessage());
            return response()->json(['error' => true, 'message' => 'Ocurrió un error al actualizar la agenda'], 500);
        }
    }
    // Para eliminar las agendas:
    public function delete_agenda(int $id){
        try{
            Log::info('llegó al controller para eliminar agenda');

            if(request()->ajax()){
                session_start();

                if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                    $this->client->setAccessToken($_SESSION['access_token']);

                    $agenda_id = Agenda::findOrFail($id);
                    Log::info('Datos obtenidos del pais: '.$agenda_id);

                    $google_event_id = Agenda::where('id', $id)->value('event_google_id');
                    Log::info('Id del evento en google obtenida: '.$google_event_id);

                    $service = new Google_Service_Calendar($this->client);
                    $service->events->delete('primary', $google_event_id);
                    Log::info('evento de google calendar eliminado');

                    // Eliminar los registros de agenda_invitados que tengan el id_agenda
                    DB::table('agenda_invitados')->where('id_agenda', $id)->delete();
                    Log::info('eventos en agenda_invitados eliminados');

                    $agenda_id->delete();
                    Log::info('agenda eliminada');

                    return response()->json(['message' => 'La agenda ha sido eliminada correctamente']);
                } else {
                    return redirect('/oauth'); // Si no hay sesión iniciada tiene que iniciar sesion en google acounts
                }
            }
        }catch(\Exception $ex){
            Log::error(['error' => 'Error al acceder a los datos de la agenda (administracion: ' . $ex->getMessage()]);
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

}
