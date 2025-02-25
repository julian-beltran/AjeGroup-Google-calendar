<?php

namespace App\Http\Controllers\calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Mail\SendAgendaMail;
use App\Models\Agenda;
use App\Models\Area;
use App\Models\Espacio;
use App\Models\User;
use App\Models\AgendaArchivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;


// Para generar eventos en Google Calendar
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Carbon\Carbon;
use Google_Service_Calendar_EventDateTime;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use Illuminate\Pagination\Paginator;

class DashboardAgendasOffController extends Controller
{
    function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig('client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);

        $guzzleClient = new Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);
        $this->client=$client;
    }

    public function index_google_calendar()
    {
        session_start();
        try{
            if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);

                $calendarId = 'primary'; // por default jala calendario del usuario logueado en google accounts
                $optParams = array(
                    'maxResults' => 10,
                    'orderBy' => 'startTime',
                    'singleEvents' => true,
                    'timeMin' => date('c'),
                );
                $results = $service->events->listEvents($calendarId, $optParams);
                $events = $results->getItems();
                return view('usuarios_vistas.calendar.listar_agendas_google', compact('events', 'calendarId'));
            }else{
                return redirect()->route('calendar.oauth');
            }
        }catch(\Exception $ex){
            return redirect()->route('calendar.oauth');
        }
    }

    public function oauth()
    {
        session_start();
        $rurl = route('calendar.oauth');
        $this->client->setRedirectUri($rurl);

        if(!isset($_GET['code'])){
            $auth_url = $this->client->createAuthUrl();
            $filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
            return redirect($filtered_url);
        }else{
            $this->client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();
            return redirect()->route('anfitrion.dashboard.index'); //anfitrion.dashboard.index
        }
    }
    // TO HEAR
    public function index(){
        session_start();
        // try{
            // Log::info('Controller de indexx dashboard');
            if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);

                $calendarId = 'primary'; // por default jala calendario del usuario logueado en google accounts
                $optParams = array(
                    'maxResults' => 10,
                    'orderBy' => 'startTime',
                    'singleEvents' => true,
                    'timeMin' => date('c'),
                );
                $results = $service->events->listEvents($calendarId, $optParams);
                $events = $results->getItems();

                $usuario_log = Auth::user();
                $id_user_log = $usuario_log->id;
                $username = $usuario_log->name;
                $areasUser = User::find($id_user_log)->areas;
                // Obtener los cargos del usuario autenticado
                $id_areas = $areasUser->pluck('id')->toArray();

                // PARTE 1: ------------------------------------------------------------------------------------------
                // 1.
                $resultadoConsulta_1 = DB::select("
                    SELECT
                        (
                            SELECT COUNT(DISTINCT users.id)
                            FROM users
                            JOIN agenda_invitados ON agenda_invitados.id_user = users.id
                            JOIN agendas ON agendas.id = agenda_invitados.id_agenda
                            JOIN areas ON areas.id = agendas.id_area
                            WHERE areas.id IN (" . implode(separator:",", array:$id_areas) . ") AND agendas.fecha_hora_meet <= NOW()
                            AND users.id <> " . $id_user_log . "
                        ) AS totalUsuariosConYSinAgendas,
                        (
                            SELECT COUNT(*)
                            FROM users
                            JOIN area_user ON area_user.id_user = users.id
                            JOIN areas ON areas.id = area_user.id_area
                            WHERE areas.id IN (" . implode(separator:",", array:$id_areas) . ")
                            AND users.id <> " . $id_user_log . "
                        ) AS totalUsuariosArea;
                ")[0];

                $totalUsuariosConYSinAgendas = $resultadoConsulta_1->totalUsuariosConYSinAgendas;
                $totalUsuariosArea1 = $resultadoConsulta_1->totalUsuariosArea;

                // PARTE 2: ------------------------------------------------------------------------------------------
                $data_section_2 = DB::table('agendas')
                    ->select('agendas.id as id',
                        'agendas.fecha_hora_meet as fecha_hora',
                        'agendas.estado as estado',
                        'agendas.summary as summary',
                        'agendas.id_area as area',
                        'areas.nombre as area_name',
                        'agendas.id_espacio as espacio',
                        'espacios.nombre as espacio_name',
                        DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) as anfitrion'),
                        DB::raw('GROUP_CONCAT(users_invitados.name) as invitados'),
                        'espacios.adjunto as adjunto',
                        'agendas.hangoutLink as link_meet'
                    )

                    ->join('agenda_invitados', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                    ->join('users AS users_invitados', 'users_invitados.id', '=', 'agenda_invitados.id_user')
                    ->join('users', 'users.id', '=', 'agendas.id_user')
                    ->join('areas', 'areas.id', '=', 'agendas.id_area')
                    ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                    ->where('agendas.id_user', $id_user_log)
                    ->whereBetween('agendas.fecha_hora_meet', [now(), now()->addHours(24)])
                    ->groupBy('agendas.id',
                        'fecha_hora',
                        'estado',
                        'summary',
                        'area',
                        'area_name',
                        'espacio',
                        'espacio_name',
                        'anfitrion',
                        'adjunto',
                        'link_meet'
                    )
                    ->orderBy('agendas.fecha_hora_meet', 'ASC')
                    ->get();

                // PARTE 3: ------------------------------------------------------------------------------------------
                $data_section_3 = DB::table('agendas')
                    ->select('agendas.id as id', 'agendas.fecha_hora_meet as fecha_hora', 'espacios.id as espacio', 'espacios.nombre as espacio_name',
                        'espacios.tipo_reunion as tipo_reunion','areas.id as area', 'areas.nombre as area_name', 'agendas.estado as estado',
                        DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) as anfitrion'),
                        DB::raw('GROUP_CONCAT(users_invitados.name) as invitados'), 'espacios.adjunto as adjunto')
                    ->join('agenda_invitados', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                    ->join('users as users_invitados', 'users_invitados.id', '=', 'agenda_invitados.id_user')
                    ->join('users', 'users.id', '=', 'agendas.id_user')
                    ->join('areas', 'areas.id', '=', 'agendas.id_area')
                    ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                    ->where('agendas.id_user', '=', $id_user_log)
                    ->where('agendas.fecha_hora_meet', '<=', DB::raw('now()'))
                    ->where('agendas.estado', '=', 'pendiente')
                    ->groupBy('agendas.id', 'agendas.fecha_hora_meet', 'agendas.estado', 'agendas.summary',
                        'area', 'area_name', 'espacio', 'espacio_name', 'anfitrion', 'adjunto', 'tipo_reunion')
                    ->orderBy('fecha_hora_meet', 'ASC')
                    ->get();

                // PARTE 4: ------------------------------------------------------------------------------------------
                $usuarios_de_area_individual  = $this->datosPorTipoEspacio('individual', $id_areas, $id_user_log);
                $usuarios_de_area_country     = $this->datosPorTipoEspacio('country', $id_areas, $id_user_log);
                $usuarios_de_area_primario    = $this->datosPorTipoEspacio('primario', $id_areas, $id_user_log);
                $usuarios_de_area_compras     = $this->datosPorTipoEspacio('compras', $id_areas, $id_user_log);
                $usuarios_de_area_merco       = $this->datosPorTipoEspacio('merco', $id_areas, $id_user_log);
                $usuarios_de_area_indicadores = $this->datosPorTipoEspacio('indicadores', $id_areas, $id_user_log);
                $usuarios_de_area_sostenibilidad   = $this->datosPorTipoEspacio('sostenibilidad', $id_areas, $id_user_log);

                $espacios_de_usuario_log = DB::table('espacios')
                    ->select('espacios.id as espacio_id', 'espacios.nombre as espacio_name', 'espacios.descripcion as espacio_descripcion', 'espacios.config as config', 'espacios.tipo_reunion as tipo_reunion', 'espacios.frecuencia as frecuencia', 'espacios.adjunto as adjunto', 'cargos.nombre as cargo_name') // 'areas.id AS area_id', 'areas.nombre AS area_name'
                    ->join('espacio_cargo', 'espacio_cargo.id_espacio', '=', 'espacios.id')
                    ->join('cargos', 'cargos.id', '=', 'espacio_cargo.id_cargo')
                    ->join('cargo_user', 'cargo_user.id_cargo', '=', 'cargos.id')
                    ->join('users', 'users.id', '=', 'cargo_user.id_user')
                    //->join('area_user', 'area_user.id_user', '=', 'users.id')
                    //->join('areas', 'areas.id', '=', 'area_user.id_area')
                    ->where('users.id', $id_user_log)
                    ->get();
                // Log::info('Espacios de usuario log'.$espacios_de_usuario_log);


                $espacios_de_usuario_log_grupal = $this->espaciosDeUsuarioLogGrupal($id_user_log);


                return view('oficial.dashboard', [
                    'username'                          => $username,
                    'events'                            => $events,
                    'calendarId'                        => $calendarId,
                    'totalCSnAgendas_parte_1'           => $totalUsuariosConYSinAgendas,
                    'totalUsuariosArea_parte_1'         => $totalUsuariosArea1,
                    'data_slide_seccion_2'              => $data_section_2,
                    'data_slide_seccion_3'              => $data_section_3,
                    'usuarios_de_area_individual'       => $usuarios_de_area_individual,
                    'usuarios_de_area_primario'         => $usuarios_de_area_primario,
                    'usuarios_de_area_country'          => $usuarios_de_area_country,
                    'usuarios_de_area_compras'          => $usuarios_de_area_compras,
                    'usuarios_de_area_merco'            => $usuarios_de_area_merco,
                    'usuarios_de_area_indicadores'      => $usuarios_de_area_indicadores,
                    'usuarios_de_area_sostenibilidad'   => $usuarios_de_area_sostenibilidad,
                    'espacios_de_usuario_log'           => $espacios_de_usuario_log,
                    'espacios_de_usuario_log_grupal'    => $espacios_de_usuario_log_grupal
                ]);
            }else{
                return redirect()->route('calendar.oauth');
            }
        /*}catch(\Exception $ex){
            return redirect()->route('calendar.oauth');
        }*/

    }

    // Para seleccionar usuarios con agendas tanto individual como grupales:
    private function datosPorTipoEspacio($tipo, $id_areas, $id_user_log) {
        $data_de_usuarios = DB::table('users')
            ->leftJoin('agenda_invitados', 'users.id', '=', 'agenda_invitados.id_user')
            ->leftJoin('agendas', 'agenda_invitados.id_agenda', '=', 'agendas.id')
            ->leftJoin('espacios', 'agendas.id_espacio', '=', 'espacios.id')
            ->leftJoin('areas', 'areas.id', '=', 'agendas.id_area')
            ->whereIn('areas.id', $id_areas)
            ->where('espacios.id', function ($query) use($tipo) {
                $query->select('id')
                    ->from('espacios')
                    ->where('tipo_reunion', $tipo)->limit(1);
            })
            ->where('users.id', '!=', $id_user_log)
            ->groupBy('users.id', 'name', 'email')
            ->havingRaw('TIMESTAMPDIFF(DAY, MAX(agendas.fecha_hora_meet), NOW()) >= (SELECT frecuencia FROM espacios WHERE tipo_reunion = ? LIMIT 1)', [$tipo])
            ->select('users.id as user_id', 'users.name as name', 'users.email as email', DB::raw('GROUP_CONCAT(areas.id) as area_id'), DB::raw('GROUP_CONCAT(areas.nombre) as area_name'), DB::raw('TIMESTAMPDIFF(DAY, MAX(agendas.fecha_hora_meet), NOW()) as ultimo_acceso'));

        $usuarios_con_agendas = DB::table('users')
            ->leftJoin('area_user', 'users.id', '=', 'area_user.id_user')
            ->leftJoin('areas', 'area_user.id_area', '=', 'areas.id')
            ->whereNotExists(function ($query) use ($id_areas, $tipo) {
                $query->select(DB::raw(1))
                    ->from('agenda_invitados')
                    ->join('agendas', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                    ->join('areas', 'agendas.id_area', '=', 'areas.id')
                    ->join('espacios', 'agendas.id_espacio', '=', 'espacios.id')
                    ->whereRaw('agenda_invitados.id_user = users.id')
                    ->whereIn('areas.id', $id_areas)
                    ->where('espacios.id', function ($query) use($tipo) {
                        $query->select('id')
                            ->from('espacios')
                            ->where('tipo_reunion', $tipo)->limit(1);
                    });
            })
            ->whereExists(function ($query) use ($id_areas) {
                $query->select(DB::raw(1))
                    ->from('area_user')
                    ->whereColumn('area_user.id_user', 'users.id')
                    ->whereIn('area_user.id_area', $id_areas);
            })
            ->where('users.id', '!=', $id_user_log)
            ->groupBy('users.id', 'users.name', 'users.email')
            ->select('users.id', 'users.name', 'users.email', DB::raw('GROUP_CONCAT(areas.id) as area_id'), DB::raw('GROUP_CONCAT(areas.nombre) as area_name'), DB::raw('IFNULL("No tiene", "Null") as ultimo_acceso'));

        return $data_de_usuarios->unionAll($usuarios_con_agendas)->get();
    }
    // Obtiene los espacios grupales del usuario logueado
    private function espaciosDeUsuarioLogGrupal($id_user_log){
        $espacios_de_usuario_log_grupal = DB::table('espacios')
                ->select(
                    'espacios.id as espacio_id', 'espacios.nombre as espacio_name', 'espacios.descripcion as espacio_descripcion', 'espacios.config as config',
                    'espacios.tipo_reunion as tipo_reunion', 'espacios.frecuencia as frecuencia', 'espacios.adjunto as adjunto',
                    'cargos.nombre as cargo_name', DB::raw('GROUP_CONCAT(areas.id) as area_id'), DB::raw('GROUP_CONCAT(areas.nombre) as area_name')
                )
                ->join('espacio_cargo', 'espacio_cargo.id_espacio', '=', 'espacios.id')
                ->join('cargos', 'cargos.id', '=', 'espacio_cargo.id_cargo')
                ->join('cargo_user', 'cargo_user.id_cargo', '=', 'cargos.id')
                ->join('users', 'users.id', '=', 'cargo_user.id_user')
                ->join('area_user', 'area_user.id_user', '=', 'users.id')
                ->join('areas', 'areas.id', '=', 'area_user.id_area')
                ->where('users.id', $id_user_log)
                ->groupBy( 'espacios.id', 'espacios.nombre', 'espacios.descripcion', 'espacios.config',
                    'espacios.tipo_reunion', 'espacios.frecuencia', 'espacios.adjunto', 'cargos.nombre')
                ->get();

        return $espacios_de_usuario_log_grupal;
    }


    // Método que manda la data filtrada por mes para los 3 cards de cabecera
    public function contenidos_cards(Request $request)
    {
        Log::info('Llegó al controller: ');

        if(request()->ajax()){
            $usuario_log = Auth::user();
            $id_user_log = $usuario_log->id;
            $username = $usuario_log->name;
            $areasUser = User::find($id_user_log)->areas;
            // Obtener los cargos del usuario autenticado
            $id_areas = $areasUser->pluck('id')->toArray();

            // Recepcion de la data:
            $fecha_input = $request->input('mes');

            $mes= substr($fecha_input, -2);

            Log::info('Mes recibido: '.json_encode($mes));

            $resultadoConsulta_2 = DB::select("
                SELECT
                    (
                        SELECT COUNT(*)
                        FROM agendas
                        WHERE fecha_hora_meet >= NOW()
                        AND id_user = " . $id_user_log . "
                        AND estado = 'pendiente'
                        AND MONTH(agendas.fecha_hora_meet) = ?
                    ) AS usuariosAgendadosArea,
                    (
                        SELECT COUNT(*)
                        FROM agendas
                        WHERE id_user = " . $id_user_log . "
                        AND MONTH(agendas.fecha_hora_meet) = ?
                    ) AS totalUsuariosAgendados;
                ", [$mes, $mes]);

            $usuariosAgendadosArea = $resultadoConsulta_2[0]->usuariosAgendadosArea;
            $totalUsuariosArea2 = $resultadoConsulta_2[0]->totalUsuariosAgendados;

            Log::info('Data 2: '.json_encode($usuariosAgendadosArea.' DE '.$totalUsuariosArea2));


            $resultadoConsulta_3 = DB::select("
                    SELECT
                        (
                            SELECT COUNT(*)
                            FROM agendas
                            WHERE fecha_hora_meet < NOW()
                            AND id_user = " . $id_user_log . "
                            AND estado = 'pendiente'
                            AND MONTH(agendas.fecha_hora_meet) = ?
                        ) AS agendas_cumplidas,
                        (
                            SELECT COUNT(*)
                            FROM agendas
                            WHERE id_user = " . $id_user_log . "
                            AND MONTH(agendas.fecha_hora_meet) = ?
                        ) AS total_agendas;
                    ", [$mes, $mes]);

            $agendas_cumplidas = $resultadoConsulta_3[0]->agendas_cumplidas;
            $total_agendas = $resultadoConsulta_3[0]->total_agendas;

            Log::info('Data 3: '.json_encode($agendas_cumplidas.' DE '.$total_agendas));

            $resultadoConsulta_4 = DB::select("
                    SELECT
                        (
                            SELECT COUNT(*)
                            FROM agendas
                            WHERE id_user = " . $id_user_log . "
                            AND estado = 'terminado'
                            AND MONTH(agendas.fecha_hora_meet) = ?
                        ) AS agendas_con_evidencia,
                        (
                            SELECT COUNT(*)
                            FROM agendas
                            WHERE id_user = " . $id_user_log . "
                            AND MONTH(agendas.fecha_hora_meet) = ?
                        ) AS total_agendas_csn_evidencia;
                    ", [$mes, $mes]);
            $agendas_con_evidencia = $resultadoConsulta_4[0]->agendas_con_evidencia;
            $total_agendas_csn_evidencia = $resultadoConsulta_4[0]->total_agendas_csn_evidencia;

            Log::info('Data 4: '.json_encode($agendas_con_evidencia.' DE '.$total_agendas_csn_evidencia));

            return response()->json([
                'usuariosAgendadosArea'     => $usuariosAgendadosArea,
                'totalUsuariosArea2'        => $totalUsuariosArea2,
                'agendas_cumplidas'         => $agendas_cumplidas,
                'total_agendas'             => $total_agendas,
                'agendas_con_evidencia'     => $agendas_con_evidencia,
                'total_agendas_csn_evidencia'  => $total_agendas_csn_evidencia,
            ]);
        }
    }

    /********************************************************************************************************************/
    // Para obtener datos para modal y agendar un evento en google calendar y en la  base de datos:
    public function ver_datos_para_agendar_evento($userId, $espacioId, $areaId)
    {
        try{
            if (request()->ajax()) {
                $user       = User::findOrFail($userId);
                $espacio    = Espacio::findOrFail($espacioId);
                $area       = Area::findOrFail($areaId);

                return response()->json([
                    'usuario'   => $user,
                    'espacio'   => $espacio,
                    'area'      => $area
                ]);
            }
        }catch (\Exception $ex){
            Log::error('Error en el acceso a los datos: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }
    public function guardar_agenda_individual(Request $request)
    {
        try {
            session_start();

            $validator = Validator::make($request->all(), [
                'id_user'               => 'required',
                'user_name'             => 'required',
                'user_email'            => 'required',
                'id_espacio'            => 'required',
                'espacio'               => 'required',
                'descripcion_espacio'   => 'required',
                'id_area'               => 'required',
                'area_name'             => 'required',
                'id_corporativo'        => 'required',
                'location'              => 'required',
                'fecha_hora_meet'       => 'required',
            ]);

            if ($validator->fails()) {
                Log::error('Error en la validacion: ');
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Obtener los datos del formulario
            $id_user_invitado       = $request->input('id_user');
            $user_name              = $request->input('user_name');
            $user_email             = $request->input('user_email');
            $id_corporativo         = $request->input('id_corporativo');
            $id_espacio             = $request->input('id_espacio');
            $espacio_nombre         = $request->input('espacio');
            $id_area                = $request->input('id_area');
            $espacio_descripcion    = $request->input('descripcion_espacio'); // --------------
            $location               = $request->input('location'); // -------------------------
            $fecha_hora_meet        = $request->input('fecha_hora_meet');
            // Para relacionar al usuario logueado con la agenda
            $user = Auth::user();
            $id_user_log = $user->id;

            $fecha_hora_termino = date('Y-m-d\TH:i', strtotime($fecha_hora_meet . '+1 hour')); // Agrega 1 hora para la validación y así no generar una agenda a menos que pase la hora:

            $agendas_validate = DB::table('agendas')
                ->join('agenda_invitados', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                ->where('agendas.id_user', $id_user_log)
                ->whereRaw("'$fecha_hora_meet' BETWEEN agendas.fecha_hora_meet AND agendas.fecha_hora_termino")
                ->exists();

            if ($agendas_validate) {
                Log::error('Ya existe una reunión para esa fecha y hora');
                // return response()->json(['error' => 'Ya existe una reunión agendada para la fecha y hora especificadas, elige otra hora.'], 400);
                return response()->json(['error' => 'Ya existe una reunión agendada para la fecha y hora especificadas, elige otra hora.'], 400);
            }


            // Iniciar la transacción
            DB::beginTransaction();

            if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);

                $calendarId = 'primary';
                $event = new Google_Service_Calendar_Event([
                    'summary' => $espacio_nombre,
                    'location' =>  $location,
                    'description' => $espacio_descripcion,
                    'start' => [
                        'dateTime' => Carbon::parse($fecha_hora_meet),
                        'timeZone' => 'America/Bogota',
                    ],
                    'end' => [
                        'dateTime' => Carbon::parse($fecha_hora_meet)->addHour(),
                        'timeZone' => 'America/Bogota',
                    ],
                    "conferenceData" => [
                        "createRequest" => [
                            "conferenceId" => [
                                "type" => "hangoutsMeet" // "type" => "eventNamedHangout"
                            ],
                            "requestId" => "123"
                        ]
                    ],
                    'attendees' => [
                        array('email' => $user_email),
                    ],
                    'reminders' => [
                        'useDefault' => FALSE,
                        'overrides' => [
                            array('method' => 'email', 'minutes' => 24 * 60),
                            array('method' => 'popup', 'minutes' => 10),
                        ],
                    ],
                ]);

                Log::info("Evento creado en google calendar: ----[".json_encode($event)."]----");

                $results = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);

                if ($results) {
                    $agenda = new Agenda([
                        'id_user'           => $id_user_log,
                        'id_corporativo'    => $id_corporativo,
                        'id_espacio'        => $id_espacio,
                        'fecha_hora_meet'   => $fecha_hora_meet,
                        'fecha_hora_termino'=> $fecha_hora_termino,
                        'id_area'           => $id_area,
                        // Agregado para probar calendar edit y delete
                        'summary'           => $results->summary,
                        'location'          => $results->location,
                        'event_google_id'   => $results->id,
                        'hangoutLink'       => $results->hangoutLink,
                        'htmlLink'          => $results->htmlLink,
                    ]);

                    // Guardar la agenda principal
                    $agenda->save();
                    $agenda->users()->attach($id_user_invitado);

                    // Datos preparados para envío de correos electrónicos
                    $dataMail = [
                        'id_corporativo'    => $id_corporativo,
                        'id_espacio'        => $id_espacio,
                        'espacio_nombre'    => $espacio_nombre,
                        'id_user_invitado'  => $id_user_invitado,
                        'user_name'         => $user_name,
                        'email_user'        => $user_email,
                        'fecha_hora_meet'   => $fecha_hora_meet,
                        'id_area'           => $id_area,
                        'meet_link'         => $results->hangoutLink,
                    ];

                    // Envío del correo electrónico
                    Mail::to($user_email)->send(new SendAgendaMail($dataMail));

                } else {
                    return response()->json(['status' => 'error', 'message' => 'Ha sucedido un error']);
                }
            } else {
                throw new Exception('Error al crear evento en Google Calendar');
            }
            // Confirmar transacción
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Agenda creada exitosamente.']);

        } catch (\Exception $ex) {
            Log::error('Error en el acceso de datos. ' . $ex->getMessage());
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }
    /********************************************************************************************************************/
    // Ver datos de una agenda para luego subir las evidencias
    public function ver_datos_de_agenda_para_subir_evidencia(int $id)
    {
        try {

            if(request()->ajax()){
                $agenda = Agenda::find($id);

                if ($agenda) {
                    return response()->json(['agenda'=>$agenda]);
                } else {
                    Log::error('Agenda no obtenida.');
                    return response()->json(['error' => 'Agenda no encontrada'], 404);
                }
            }
        } catch (\Exception $ex) {
            Log::error('Error en el acceso a las agendas: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }
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
                $rutaGuardarImg = 'evidencias/'; // $rutaGuardarImg = 'storage/'; // Carpeta donde se guardarán los archivos
                $nombreAdjunto = $nombre_archivo .'_'. uniqid() .'.' . $archivo->getClientOriginalExtension();
                $archivo->move($rutaGuardarImg, $nombreAdjunto); // Mover el archivo a la carpeta de almacenamiento

                $url_archivo = $rutaGuardarImg.$nombreAdjunto; // $url_archivo = $nombreAdjunto; 
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

    /* Culminar agenda de evidencia opcional */
    public function ver_agenda_para_culimar(int $id)
    {
        try {

            if(request()->ajax()){
                $agenda = Agenda::find($id);
                if ($agenda) {
                    return response()->json(['agenda'=>$agenda]);
                } else {
                    Log::error('Agenda no obtenida.');
                    return response()->json(['error' => 'Agenda no encontrada'], 404);
                }
            }
        } catch (\Exception $ex) {
            Log::error('Error en el acceso a las agendas: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }
    public function culminar_agendas(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_agenda' => 'required|exists:agendas,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Error de validacion', 'errors' => $validator->errors()], 422);
            }

            // Validacion para la actualización de la agenda por su id [estado a terminado]
            $id_agenda = $request->input('id_agenda');
            $agenda = Agenda::findOrFail($id_agenda);
            if($agenda){
                $agenda->estado = 'terminado';
                $agenda->save();
             }

            return response()->json(['message' => 'Evidencia agregada correctamente'], 200);

        } catch (\Exception $ex) {
            Log::error('Error al subir la evidencia: ' . $ex->getMessage());
            return response()->json(['error' => 'Error al subir la evidencia'], 500);
        }
    }


    /********************************************************************************************************************/
    // VER DATOS Y AGREGAR EVENTOS PARA ESPACIOS DE TIPO: PARES | MAX 10 | RANKING | RETROALIMENTACION
    // AGREGAR EVENTO DE TIPO: PARES -> Cafe con pares
    public function ver_data_cafe_con_pares($espacioId, $areaId)
    {
        Log::info('LLegó al controller de data grupal para pares:');
        try{
            Log::info('Acceso al Try-Catch de pares.');
            if (request()->ajax()) {
                $espacio    = Espacio::findOrFail($espacioId);
                Log::info('Espacio encontrado para select usuarios: '.json_encode($espacio));
                $area       = Area::findOrFail($areaId);
                Log::info('Area encontrada para user select: '.json_encode($area));
                $user = Auth::user();
                $id_user_log = $user->id;
                $areasUser = Area::all();
                // $areasUser = User::find($id_user_log)->areas;
                Log::info('Areas del usuario: '.json_encode($areasUser));

                // Cargos y áreas del usuario logueado:
                $cargosUser = User::find($id_user_log)->cargos;
                $nombresCargo = $cargosUser->pluck('nombre')->toArray();
                $espacios = Espacio::whereHas('cargos', function ($query) use ($nombresCargo){
                                $query->whereIn('nombre', $nombresCargo);
                            })
                            ->with('cargos', 'areas')
                            ->get();


                $usuarios = $this->datosTipoEspacioModal('pares', $areaId, $id_user_log);

                Log::info('USUARIO OBTENIDO: '.json_encode($usuarios));

                return response()->json([
                    'usuarios'  => $usuarios,
                    'espacio'   => $espacio,
                    'area'      => $area,
                    'areasUser' => $areasUser,
                    'espacios'  => $espacios
                ]);
            }
        }catch (\Exception $ex){
            Log::error('Error en el acceso a los datos grupales: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }
    // AGREGAR EVENTO DE TIPO: MAXIMO 10 PERSONAS -> Una big con el equipo
    public function ver_data_agenda_max_10($espacioId, $areaId) // ver_data_agenda_grupal_eventos
    {
        Log::info('LLegó al controller de data grupal para MAX - 10:');
        try{
            Log::info('Acceso al Try-Catch de select usuarios.');
            if (request()->ajax()) {
                $espacio    = Espacio::findOrFail($espacioId);
                $area       = Area::findOrFail($areaId);
                $user = Auth::user();
                $id_user_log = $user->id;
                $areasUser = User::find($id_user_log)->areas;
                // Cargos y áreas del usuario logueado:
                $cargosUser = User::find($id_user_log)->cargos;
                $nombresCargo = $cargosUser->pluck('nombre')->toArray();
                $espacios = Espacio::whereHas('cargos', function ($query) use ($nombresCargo){
                    $query->whereIn('nombre', $nombresCargo);
                })
                ->with('cargos', 'areas')
                ->get();

                $usuarios = $this->datosTipoEspacioModal('max 10', $areaId, $id_user_log);
                Log::info('USUARIO OBTENIDO: '.json_encode($usuarios));

                return response()->json([
                    'usuarios'  => $usuarios,
                    'espacio'   => $espacio,
                    'area'      => $area,
                    'areasUser'=> $areasUser,
                    'espacios'  => $espacios
                ]);
            }
        }catch (\Exception $ex){
            Log::error('Error en el acceso a los datos grupales: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }
    // AGREGAR EVENTO DE TIPO: RANKING-> Ranking
    public function ver_data_ranking($espacioId, $areaId)
    {
        try{
            if (request()->ajax()) {
                $espacio    = Espacio::findOrFail($espacioId);
                $area       = Area::findOrFail($areaId);
                $user = Auth::user();
                $id_user_log = $user->id;
                $areasUser = User::find($id_user_log)->areas;
                // Cargos y áreas del usuario logueado:
                $cargosUser = User::find($id_user_log)->cargos;
                $nombresCargo = $cargosUser->pluck('nombre')->toArray();
                $espacios = Espacio::whereHas('cargos', function ($query) use ($nombresCargo){
                    $query->whereIn('nombre', $nombresCargo);
                })
                ->with('cargos', 'areas')
                ->get();

                $usuarios = $this->datosTipoEspacioModal('ranking', $areaId, $id_user_log);
                Log::info('USUARIO OBTENIDO: '.json_encode($usuarios));

                return response()->json([
                    'usuarios'  => $usuarios,
                    'espacio'   => $espacio,
                    'area'      => $area,
                    'areasUser'=> $areasUser,
                    'espacios'  => $espacios
                ]);
            }
        }catch (\Exception $ex){
            Log::error('Error en el acceso a los datos grupales: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }
    // AGREGAR EVENTO DE TIPO: RETROALIMENTACION -> Retroalimentación
    public function ver_data_retroalimentacion($espacioId, $areaId)
    {
        try{
            if (request()->ajax()) {
                $espacio    = Espacio::findOrFail($espacioId);
                $area       = Area::findOrFail($areaId);
                $user = Auth::user();
                $id_user_log = $user->id;
                $areasUser = User::find($id_user_log)->areas;
                $cargosUser = User::find($id_user_log)->cargos;
                $nombresCargo = $cargosUser->pluck('nombre')->toArray();
                $espacios = Espacio::whereHas('cargos', function ($query) use ($nombresCargo){
                    $query->whereIn('nombre', $nombresCargo);
                })
                ->with('cargos', 'areas')
                ->get();

                $usuarios = $this->datosTipoEspacioModal('retroalimentacion', $areaId, $id_user_log);
                Log::info('USUARIO OBTENIDO: '.json_encode($usuarios));

                return response()->json([
                    'usuarios'  => $usuarios,
                    'espacio'   => $espacio,
                    'area'      => $area,
                    'areasUser' => $areasUser,
                    'espacios'  => $espacios
                ]);
            }
        }catch (\Exception $ex){
            Log::error('Error en el acceso a los datos grupales: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }

    // Método que sirve para guardar las agendas para los tipos de espacios: PARES | MAX 10 | RANKING | RETROALIMENTACION
    public function guardar_agenda_seleccion_usuarios(Request $request)
    {
        Log::info('Llego al controller para agregar eventos grupales de tipo: PARES | MAX 10 | RANKING | RETROALIMENTACION');
        try {
            session_start();
            // Validación de datos
            $validator = Validator::make($request->all(), [
                'id_corporativo_grupal' => 'required',
                'id_espacio_grupal'     => 'required',
                'espacio_grupal'        => 'required',
                'desc_esp_grupal'       => 'required',
                'id_area_grupal'        => 'required',
                'location'              => 'required',
                'users'                 => 'required',
                'fecha_hora_meet'       => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Obtener los datos del formulario
            $id_corporativo         = $request->input('id_corporativo_grupal');
            $id_espacio             = $request->input('id_espacio_grupal');
            $espacio_nombre         = $request->input('espacio_grupal');
            $descripcion_esp        = $request->input('desc_esp_grupal');
            $id_area                = $request->input('id_area_grupal'); // --------------
            $location               = $request->input('location'); // -------------------------
            $users_seleccionados    = $request->input('users');
            $fecha_hora_meet        = $request->input('fecha_hora_meet');

            $user = Auth::user();
            $user_log_id = $user->id;

            $fecha_hora_termino = date('Y-m-d\TH:i', strtotime($fecha_hora_meet . '+1 hour')); // Agrega 1 hora para la validación y así no generar una agenda a menos que pase la hora:

            $agendas_validate = DB::table('agendas')
                ->join('agenda_invitados', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                ->whereRaw("'$fecha_hora_meet' BETWEEN agendas.fecha_hora_meet AND agendas.fecha_hora_termino")
                // ->where('agendas.fecha_hora_meet', $fecha_hora_meet)
                ->where('agendas.id_user', $user_log_id)
                ->where('agendas.id_espacio', $id_espacio)
                ->exists();

            Log::info('agenda grupal de selección de usuarios validada: '.json_encode($agendas_validate));
            if ($agendas_validate) {
                Log::error('Ya existe una reunión para esa fecha y hora');
                return response()->json(['error' => 'Ya existe una reunión en el rango de la hora seleccionada para el espacio, elige otra hora.'], 400);
            }
            if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);

                $calendarId = 'primary';

                // Auth user
                $user = Auth::user();
                $id_user_log = $user->id;

                // Configurar los asistentes
                $usuarios = User::whereIn('id', $users_seleccionados)->get();
                $emails = [];
                foreach($usuarios as $user){
                    $emails[]  = $user->email;
                }
                $attendees = [];
                foreach($emails as $email){
                    $attendees[] = ['email'=>$email];
                }
                // Crear el evento
                $event = new Google_Service_Calendar_Event([
                    'summary' => $espacio_nombre,
                    'location' => $location,
                    'description' => $descripcion_esp,
                    'start' => [
                        'dateTime' => Carbon::parse($fecha_hora_meet),
                        'timeZone' => 'America/Bogota',
                    ],
                    'end' => [
                        'dateTime' => Carbon::parse($fecha_hora_meet)->addHour(),
                        'timeZone' => 'America/Bogota',
                    ],
                    "conferenceData" => [
                        "createRequest" => [
                            "conferenceId" => [
                                "type" => "hangoutsMeet"
                            ],
                            "requestId" => "123"
                        ]
                    ],
                    'attendees' => $attendees,
                    'reminders' => [
                        'useDefault' => FALSE,
                        'overrides' => [
                            array('method' => 'email', 'minutes' => 24 * 60),
                            array('method' => 'popup', 'minutes' => 10),
                        ],
                    ],
                ]);

                // Insertar el evento en Google Calendar
                $results = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);

                if ($results) {
                    $agenda = new Agenda([
                        'id_user'           => $id_user_log,
                        'id_corporativo'    => $id_corporativo,
                        'id_espacio'        => $id_espacio,
                        'fecha_hora_meet'   => $fecha_hora_meet,
                        'fecha_hora_termino'=> $fecha_hora_termino,
                        'id_area'           => $id_area,
                        // Agregado para probar calendar edit y delete
                        'summary'           => $results->summary,
                        'location'          => $results->location,
                        'event_google_id'   => $results->id,
                        'hangoutLink'       => $results->hangoutLink,
                        'htmlLink'          => $results->htmlLink,
                    ]);
                    // Guardar la agenda en la base de datos
                    $agenda->save();
                    // Enviar correo electrónico a los invitados con el enlace de la reunión única
                    foreach ($usuarios as $user) {
                        $id_user_invitado = $user->id;
                        $dataMail = [
                            'id_corporativo'    => $id_corporativo,
                            'id_espacio'        => $id_espacio,
                            'espacio_nombre'    => $espacio_nombre,
                            'id_user_invitado'  => $user['id'],
                            'user_name'         => $user['name'],
                            'email_user'        => $user['email'],
                            'fecha_hora_meet'   => $fecha_hora_meet,
                            'id_area'           => $id_area,
                            'meet_link'         => $results->hangoutLink,
                        ];

                        // Enviar correo electrónico
                        Mail::to($user['email'])->send(new SendAgendaMail($dataMail));

                        //Relacionando la $agenda al invitado
                        $agenda->users()->attach($id_user_invitado);
                    }
                    Log::info('Agenda grupal agregada: ' . json_encode($agenda));

                    Log::info('Evento creado en Google Calendar: ' . json_encode($results));
                    return response()->json(['status' => 'success']);
                } else {
                    Log::error('No se pudo crear el evento en Google Calendar.');
                    return response()->json(['error' => 'No se pudo crear el evento en Google Calendar.'], 500);
                }
            }
        }catch(\Exception $ex){
            Log::error('Error en el acceso de datos. ' . $ex->getMessage());
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }
    /*****************************************************************************************************************************************/
    // Seccion 2 para agendas todos usuarios por area:
    /*****************************************************************************************************************************************/
    public function ver_data_country($userIds, $espacioId, $areaId)
    {
         try{
            if (request()->ajax()) {
                $users_ids = explode(',', $userIds);

                $users = User::whereIn('id', $users_ids)->get();
                $espacio    = Espacio::findOrFail($espacioId);
                $area       = Area::findOrFail($areaId);
                $user = Auth::user();
                $id_user_log = $user->id;
                $areasUser = User::find($id_user_log)->areas;
                $cargosUser = User::find($id_user_log)->cargos;
                $nombresCargo = $cargosUser->pluck('nombre')->toArray();
                $espacios = Espacio::whereHas('cargos', function ($query) use ($nombresCargo){
                    $query->whereIn('nombre', $nombresCargo);
                })
                ->with('cargos', 'areas')
                ->get();

                $usuarios = $this->datosTipoEspacioModal('country', $areaId, $id_user_log);
                Log::info('Contry: '.json_encode($usuarios));

                return response()->json([
                    'usuarios'  => $usuarios,
                    'espacio'   => $espacio,
                    'area'      => $area,
                    'areasUser' => $areasUser,
                    'espacios'  => $espacios
                ]);
            }
        }catch (\Exception $ex){
            Log::error('Error en el acceso a los datos grupales: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }
    public function ver_data_primario($userIds, $espacioId, $areaId)
    {
        try{
            if (request()->ajax()) {
                $users_ids = explode(',', $userIds);

                $users = User::whereIn('id', $users_ids)->get();
                $espacio    = Espacio::findOrFail($espacioId);
                $area       = Area::findOrFail($areaId);
                 $user = Auth::user();
                $id_user_log = $user->id;
                $areasUser = User::find($id_user_log)->areas;
                $cargosUser = User::find($id_user_log)->cargos;
                $nombresCargo = $cargosUser->pluck('nombre')->toArray();
                $espacios = Espacio::whereHas('cargos', function ($query) use ($nombresCargo){
                    $query->whereIn('nombre', $nombresCargo);
                })
                ->with('cargos', 'areas')
                ->get();

                $usuarios = $this->datosTipoEspacioModal('pares', $areaId, $id_user_log);
                Log::info('pares: '.json_encode($usuarios));

                return response()->json([
                    'usuarios'  => $usuarios,
                    'espacio'   => $espacio,
                    'area'      => $area,
                    'areasUser' => $areasUser,
                    'espacios'  => $espacios
                ]);
            }
        }catch (\Exception $ex){
            Log::error('Error en el acceso a los datos grupales: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }
    public function ver_data_compras($userIds, $espacioId, $areaId)
    {
        try{
            if (request()->ajax()) {
                $users_ids = explode(',', $userIds);

                $users = User::whereIn('id', $users_ids)->get();
                $espacio    = Espacio::findOrFail($espacioId);
                $area       = Area::findOrFail($areaId);
                $user = Auth::user();
                $id_user_log = $user->id;
                $areasUser = User::find($id_user_log)->areas;
                $cargosUser = User::find($id_user_log)->cargos;
                $nombresCargo = $cargosUser->pluck('nombre')->toArray();
                $espacios = Espacio::whereHas('cargos', function ($query) use ($nombresCargo){
                    $query->whereIn('nombre', $nombresCargo);
                })
                ->with('cargos', 'areas')
                ->get();

                $usuarios = $this->datosTipoEspacioModal('compras', $areaId, $id_user_log);
                Log::info('compras: '.json_encode($usuarios));

                return response()->json([
                    'usuarios'  => $usuarios,
                    'espacio'   => $espacio,
                    'area'      => $area,
                    'areasUser' => $areasUser,
                    'espacios'  => $espacios
                ]);
            }
        }catch (\Exception $ex){
            Log::error('Error en el acceso a los datos grupales: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }
    public function ver_data_merco($userIds, $espacioId, $areaId)
    {
        try{
            if (request()->ajax()) {
                $users_ids = explode(',', $userIds);

                $users = User::whereIn('id', $users_ids)->get();
                $espacio    = Espacio::findOrFail($espacioId);
                $area       = Area::findOrFail($areaId);
                $user = Auth::user();
                $id_user_log = $user->id;
                $areasUser = User::find($id_user_log)->areas;
                $cargosUser = User::find($id_user_log)->cargos;
                $nombresCargo = $cargosUser->pluck('nombre')->toArray();
                $espacios = Espacio::whereHas('cargos', function ($query) use ($nombresCargo){
                    $query->whereIn('nombre', $nombresCargo);
                })
                ->with('cargos', 'areas')
                ->get();

                $usuarios = $this->datosTipoEspacioModal('merco', $areaId, $id_user_log);
                Log::info('merco: '.json_encode($usuarios));

                return response()->json([
                    'usuarios'  => $usuarios,
                    'espacio'   => $espacio,
                    'area'      => $area,
                    'areasUser' => $areasUser,
                    'espacios'  => $espacios
                ]);
            }
        }catch (\Exception $ex){
            Log::error('Error en el acceso a los datos grupales: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }
    public function ver_data_indicadores($userIds, $espacioId, $areaId)
    {
        try{
            if (request()->ajax()) {
                $users_ids = explode(',', $userIds);

                $users = User::whereIn('id', $users_ids)->get();
                $espacio    = Espacio::findOrFail($espacioId);
                $area       = Area::findOrFail($areaId);
                $user = Auth::user();
                $id_user_log = $user->id;
                $areasUser = User::find($id_user_log)->areas;
                $cargosUser = User::find($id_user_log)->cargos;
                $nombresCargo = $cargosUser->pluck('nombre')->toArray();
                $espacios = Espacio::whereHas('cargos', function ($query) use ($nombresCargo){
                    $query->whereIn('nombre', $nombresCargo);
                })
                ->with('cargos', 'areas')
                ->get();

                $usuarios = $this->datosTipoEspacioModal('indicadores', $areaId, $id_user_log);
                Log::info('indicadores: '.json_encode($usuarios));

                return response()->json([
                    'usuarios'  => $usuarios,
                    'espacio'   => $espacio,
                    'area'      => $area,
                    'areasUser' => $areasUser,
                    'espacios'  => $espacios
                ]);
            }
        }catch (\Exception $ex){
            Log::error('Error en el acceso a los datos grupales: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }
    public function ver_data_sostenibilidad($userIds, $espacioId, $areaId)
    {
        try{
             if (request()->ajax()) {
                $users_ids = explode(',', $userIds);

                $users = User::whereIn('id', $users_ids)->get();
                $espacio    = Espacio::findOrFail($espacioId);
                $area       = Area::findOrFail($areaId);
                $user = Auth::user();
                $id_user_log = $user->id;
                $areasUser = User::find($id_user_log)->areas;
                $cargosUser = User::find($id_user_log)->cargos;
                $nombresCargo = $cargosUser->pluck('nombre')->toArray();
                $espacios = Espacio::whereHas('cargos', function ($query) use ($nombresCargo){
                    $query->whereIn('nombre', $nombresCargo);
                })
                ->with('cargos', 'areas')
                ->get();


                $usuarios = $this->datosTipoEspacioModal('sostenibilidad', $areaId, $id_user_log);
                Log::info('sostenibilidad: '.json_encode($usuarios));

                return response()->json([
                    'usuarios'  => $usuarios,
                    'espacio'   => $espacio,
                    'area'      => $area,
                    'areasUser' => $areasUser,
                    'espacios'  => $espacios
                ]);
            }
        }catch (\Exception $ex){
            Log::error('Error en el acceso a los datos grupales: ' . $ex->getMessage());
            return response()->json(['error' => 'Ocurrió un error al acceder a los datos.'], 500);
        }
    }

    // Método que agregará eventos generales por área.
    public function agregar_evento_grupal_por_area(Request $request)
    {
        // Log::info('Llego al controller para agregar eventos grupales de tipo: PRIMARIO | COUNTRY | COMPRAS | MERCO | INDICADORES | SOSTENIBILIDAD');
        try {
            session_start();
             $validator = Validator::make($request->all(), [
                'id_corporativo_grupal' => 'required',
                'id_espacio_grupal'     => 'required',
                'espacio_grupal'        => 'required',
                'desc_esp_grupal'       => 'required',
                'id_area_grupal'        => 'required',
                'location'              => 'required',
                'users'                 => 'required',
                'fecha_hora_meet'       => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $user = Auth::user();
            $id_user_log = $user->id;

            // Obtener los datos del formulario
            $id_corporativo         = $request->input('id_corporativo_grupal');
            $id_espacio             = $request->input('id_espacio_grupal');
            $espacio_nombre         = $request->input('espacio_grupal');
            $descripcion_esp        = $request->input('desc_esp_grupal');
            $id_area                = $request->input('id_area_grupal');
            $location               = $request->input('location');
            $users_seleccionados    = $request->input('users');
            $fecha_hora_meet        = $request->input('fecha_hora_meet');
            $fecha_hora_termino = date('Y-m-d\TH:i', strtotime($fecha_hora_meet . '+1 hour')); // Agrega 1 hora para la validación y así no generar una agenda a menos que pase la hora:

            $agendas_validate = DB::table('agendas')
                ->join('agenda_invitados', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                ->whereRaw("'$fecha_hora_meet' BETWEEN agendas.fecha_hora_meet AND agendas.fecha_hora_termino")
                //->where('agendas.fecha_hora_meet', $fecha_hora_meet)
                ->where('agendas.id_user', $id_user_log)
                ->where('agendas.id_espacio', $id_espacio)
                ->exists();


            if ($agendas_validate) {
                Log::error('Ya existe una reunión para esa fecha y hora');
                return response()->json(['error' => 'Ya existe una reunión agendada en el rango de la hora seleccionada para el espacio, elige otra hora.'], 400);
            }

            if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);

                $calendarId = 'primary';

                // Auth user
                $user = Auth::user();
                $id_user_log = $user->id;

                // Configurar los asistentes
                $usuarios = User::whereIn('id', $users_seleccionados)->get();
                $emails = [];
                foreach($usuarios as $user){
                    $emails[]  = $user->email;
                }
                $attendees = [];
                foreach($emails as $email){
                    $attendees[] = ['email'=>$email];
                }
                // Crear el evento
                $event = new Google_Service_Calendar_Event([
                    'summary' => $espacio_nombre,
                    'location' => $location,
                    'description' => $descripcion_esp,
                    'start' => [
                        'dateTime' => Carbon::parse($fecha_hora_meet),
                        'timeZone' => 'America/Bogota',
                    ],
                    'end' => [
                        'dateTime' => Carbon::parse($fecha_hora_meet)->addHour(),
                        'timeZone' => 'America/Bogota',
                    ],
                    "conferenceData" => [
                        "createRequest" => [
                            "conferenceId" => [
                                "type" => "hangoutsMeet"
                            ],
                            "requestId" => "123"
                        ]
                    ],
                    'attendees' => $attendees,
                    'reminders' => [
                        'useDefault' => FALSE,
                        'overrides' => [
                            array('method' => 'email', 'minutes' => 24 * 60),
                            array('method' => 'popup', 'minutes' => 10),
                        ],
                    ],
                ]);

                // Insertar el evento en Google Calendar
                $results = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);

                if ($results) {
                    $agenda = new Agenda([
                        'id_user'           => $id_user_log,
                        'id_corporativo'    => $id_corporativo,
                        'id_espacio'        => $id_espacio,
                        'fecha_hora_meet'   => $fecha_hora_meet,
                        'fecha_hora_termino'=> $fecha_hora_termino,
                        'id_area'           => $id_area,
                        'summary'           => $results->summary,
                        'location'          => $results->location,
                        'event_google_id'   => $results->id,
                        'hangoutLink'       => $results->hangoutLink,
                        'htmlLink'          => $results->htmlLink,
                    ]);
                    $agenda->save();
                    // EMAIL con link meet
                    foreach ($usuarios as $user) {
                        $id_user_invitado = $user->id;
                        $dataMail = [
                            'id_corporativo'    => $id_corporativo,
                            'id_espacio'        => $id_espacio,
                            'espacio_nombre'    => $espacio_nombre,
                            'id_user_invitado'  => $user['id'],
                            'user_name'         => $user['name'],
                            'email_user'        => $user['email'],
                            'fecha_hora_meet'   => $fecha_hora_meet,
                            'id_area'           => $id_area,
                            'meet_link'         => $results->hangoutLink,
                        ];

                        // Enviar correo electrónico
                        Mail::to($user['email'])->send(new SendAgendaMail($dataMail));

                        //Relacionando la $agenda al invitado
                        $agenda->users()->attach($id_user_invitado);

                    }

                    Log::info('Agenda grupal agregada: ' . json_encode($agenda));

                    Log::info('Evento creado en Google Calendar: ' . json_encode($results));
                    return response()->json(['status' => 'success']);
                } else {
                    Log::error('No se pudo crear el evento en Google Calendar.');
                    return response()->json(['error' => 'No se pudo crear el evento en Google Calendar.'], 500);
                }
            }
        }catch(\Exception $ex){
            Log::error('Error en el acceso de datos. ' . $ex->getMessage());
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }


    // Obtiene los datos en base a parametros para los espacios: ******************************************************************************************************************************
    private function datosTipoEspacioModal($tipo, $areaId, $id_user_log){
        $data_de_usuarios = DB::table('users')
                    ->leftJoin('agenda_invitados', 'users.id', '=', 'agenda_invitados.id_user')
                    ->leftJoin('agendas', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                    ->leftJoin('espacios', 'agendas.id_espacio', '=', 'espacios.id')
                    ->leftJoin('areas', 'areas.id', '=', 'agendas.id_area')
                    ->where('areas.id', '=', $areaId)
                    ->where('espacios.id', function ($query) use($tipo) {
                        $query->select('id')
                            ->from('espacios')
                            ->where('tipo_reunion', $tipo)->limit(1);
                    })
                    ->where('users.id', '!=', $id_user_log)
                    ->groupBy('users.id', 'name', 'email')
                    ->havingRaw('TIMESTAMPDIFF(DAY, MAX(agendas.fecha_hora_meet), NOW()) >= (SELECT frecuencia FROM espacios WHERE tipo_reunion = ? LIMIT 1)', [$tipo])
                    ->select('users.id as user_id', 'users.name as name', 'users.email as email', DB::raw('GROUP_CONCAT(areas.id) as area_id'), DB::raw('GROUP_CONCAT(areas.nombre) as area_name'), DB::raw('TIMESTAMPDIFF(DAY, MAX(agendas.fecha_hora_meet), NOW()) as ultimo_acceso'));

                $usuarios_con_agendas = DB::table('users')
                    ->leftJoin('area_user', 'users.id', '=', 'area_user.id_user')
                    ->leftJoin('areas', 'area_user.id_area', '=', 'areas.id')
                    ->whereNotExists(function ($query) use ($areaId, $tipo) {
                        $query->select(DB::raw(1))
                            ->from('agenda_invitados')
                            ->join('agendas', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                            ->join('areas', 'agendas.id_area', '=', 'areas.id')
                            ->join('espacios', 'agendas.id_espacio', '=', 'espacios.id')
                            ->whereRaw('agenda_invitados.id_user = users.id')
                            ->where('areas.id', '=', $areaId)
                            ->where('espacios.id', function ($query) use($tipo) {
                                $query->select('id')
                                    ->from('espacios')
                                    ->where('tipo_reunion', $tipo)->limit(1);
                            });
                    })
                    ->whereExists(function ($query) use ($areaId) {
                        $query->select(DB::raw(1))
                            ->from('area_user')
                            ->whereColumn('area_user.id_user', 'users.id')
                            ->where('area_user.id_area', '=', $areaId);
                    })
                    ->where('users.id', '!=', $id_user_log)
                    ->groupBy('users.id', 'users.name', 'users.email')
                    ->select('users.id', 'users.name', 'users.email', DB::raw('GROUP_CONCAT(areas.id) as area_id'), DB::raw('GROUP_CONCAT(areas.nombre) as area_name'), DB::raw('IFNULL("No tiene", "Null") as ultimo_acceso'));

                return $data_de_usuarios->unionAll($usuarios_con_agendas)->get();
    }

    /*******************************************************************************************************************************************************************************************/
    /*******************************************************************************************************************************************************************************************/
    // Codigo para mostrar lass agendas de acuerdo al button seleccionado: Todo funciona con AJAX:
    public function lista_agendas_general(Request $request)
    {
        session_start(); // ADD
        try{ // ADD
            if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
                $this->client->setAccessToken($_SESSION['access_token']);
                $service = new Google_Service_Calendar($this->client);

                $calendarId = 'primary'; // por default jala calendario del usuario logueado en google accounts
                $optParams = array(
                    'maxResults' => 10,
                    'orderBy' => 'startTime',
                    'singleEvents' => true,
                    'timeMin' => date('c'),
                );
                $results = $service->events->listEvents($calendarId, $optParams);
                $events = $results->getItems();


                $usuario    = Auth::user();
                $idUserLog  = $usuario->id;
                $username   = $usuario->name;
                // Cargos y áreas del usuario logueado:
                $cargosUser = User::find($idUserLog)->cargos;
                // Obtener los cargos del usuario loguaeado:
                $nombresCargo = $cargosUser->pluck('nombre')->toArray();
                // Se usará para los select (filtros)
                $areasUser  = User::find($idUserLog)->areas;
                $id_areas = $areasUser->pluck('id')->toArray();
                 $usuarios = DB::table('users')
                    ->select('users.id as id',
                        'users.name as name',
                        'users.email as email'
                    )
                    ->join('area_user', 'area_user.id_user', '=', 'users.id')
                    ->join('areas', 'areas.id', '=', 'area_user.id_area')
                    ->whereIn('areas.id', $id_areas)
                    ->where('users.id', '!=', $idUserLog)
                    ->get();

                // Obteniendo los espacios que le han sido asignados al usuario según su cargo:
                $espacios = Espacio::whereHas('cargos', function ($query) use ($nombresCargo){
                    $query->whereIn('nombre', $nombresCargo);
                })
                    ->with('cargos', 'areas')
                    ->get();
                // Fecha y hora actual:
                $fecha_hora_actual = Carbon::now();

                // Data para redireccion a agendas
                $tipo = null;
                $tipo = $request->input('tipo');

                return view('oficial/admin_agendas_lider', [
                    'events'            => $events,
                    'espacios'          => $espacios,
                    'areasUser'         => $areasUser,
                    'usuarios'          => $usuarios,
                    'fecha_hora_actual' => $fecha_hora_actual,
                    'tipo'              => $tipo
                ]);
            }else{
                return redirect()->route('calendar.oauth');
            }
        }catch(\Exception $ex){ // ADD
            return redirect()->route('calendar.oauth'); // ADD
        }
    }
    public function filtrar_todas_agendas(int $id, Request $request)
    {
        if(request()->ajax()){
            $espacio_data = Espacio::findOrFail($id);
            $espacio_id = $espacio_data->id;

            $usuario    = Auth::user();
            $idUserLog  = $usuario->id;

            // Todas: muestra todas las agendas programadas del usuario logueado, con la lista de los invitados
            $todasQuery = DB::table('agendas')
                ->select(
                    'agendas.id AS agenda_id',
                    'agendas.id_user AS userLog',
                    DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) AS usuario_log'),
                    DB::raw('GROUP_CONCAT((SELECT name FROM users WHERE users.id = agenda_invitados.id_user)) AS invitado'),
                    'agendas.fecha_hora_meet AS fecha_hora',
                    'areas.id AS areas',
                    'areas.nombre AS area_nombre',
                    'espacios.id AS espacios',
                    'espacios.nombre AS espacio_nombre',
                    'espacios.config AS tipo',
                    'espacios.tipo_reunion AS tipo_reunion',
                    'corporativos.nombre AS corporativo_nombre',
                    'agendas.location AS location',
                    'agendas.estado AS estado',
                    DB::raw('COALESCE(agenda_archivos.archivos, \'["No tiene archivos"]\') AS archivos'),
                    'agendas.hangoutLink AS link_meet'
                )
                ->join('areas', 'areas.id', '=', 'agendas.id_area')
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                ->join('corporativos', 'corporativos.id', '=', 'agendas.id_corporativo')
                ->join('users', 'users.id', '=', 'agendas.id_user')
                ->join('agenda_invitados', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                ->leftJoin('agenda_archivos', 'agenda_archivos.id_agenda', '=', 'agendas.id')
                ->where('agendas.id_user', $idUserLog)
                ->where('espacios.id',$espacio_id)
                ->groupBy('agenda_id', 'agendas.id_user', 'agendas.fecha_hora_meet', 'areas.id', 'areas.nombre',
                    'espacios.id', 'espacios.nombre', 'espacios.config', 'espacios.tipo_reunion',
                    'corporativos.nombre', 'agendas.location', 'agendas.estado', 'agenda_archivos.archivos',
                    'agendas.hangoutLink');
            //->get();

            $area_id = $request->input('area');
            $fecha   = $request->input('fecha');
            $user_id = $request->input('usuario');
            $orden   = $request->input('orden');

            Log::info('data recibida para filtro de todas las agendas: '.json_encode('Area filtro: '.$area_id.' Fecha filtro: '. $fecha.' Usuario id:'. $user_id.' Orden: '.$orden));

            if ($area_id) {
                $todasQuery->where('agendas.id_area', $area_id);
            }
            if ($fecha) {
                $fecha = date('Y-m-d', strtotime($fecha));
                $todasQuery->whereDate('agendas.fecha_hora_meet', $fecha);
            }
            if ($user_id) {
                $todasQuery->where('agenda_invitados.id_user', $user_id);
            }
            if ($orden === 'DESC') {
                $todasQuery->orderByDesc('agendas.fecha_hora_meet');
            } else {
                $todasQuery->orderBy('agendas.fecha_hora_meet');
            }

            $todas = $todasQuery->get();

            $todas_por_pagina = $todasQuery->paginate(10);

            $fecha_hora_actual = Carbon::now();

            return response()->json([
                'todas'                 => $todas,
                'todas_por_pagina'      => $todas_por_pagina,
                'fecha_hora_actual'     => $fecha_hora_actual
            ]);
        }
    }
    public function filtrar_agendadas_agendas(int $id, Request $request)
    {
        if(request()->ajax()){
            $espacio_data = Espacio::findOrFail($id);
            $espacio_id = $espacio_data->id;
            $usuario    = Auth::user();
            $idUserLog  = $usuario->id;

            // QUERYS PARA LOS TABS: ------------------------------------------------------
            $agendadasQuery = DB::table('agendas')
                ->select(
                    'agendas.id AS agenda_id',
                    'agendas.id_user AS userLog',
                    DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) AS usuario_log'),
                    DB::raw('GROUP_CONCAT((SELECT name FROM users WHERE users.id = agenda_invitados.id_user)) AS invitado'),
                    'agendas.fecha_hora_meet AS fecha_hora',
                    'areas.id AS areas',
                    'areas.nombre AS area_nombre',
                    'espacios.id AS espacios',
                    'espacios.nombre AS espacio_nombre',
                    'espacios.config AS tipo',
                    'corporativos.nombre AS corporativo_nombre',
                    'agendas.location AS location',
                    'agendas.estado AS estado',
                    'agendas.hangoutLink as link_meet'
                )
                ->join('areas', 'areas.id', '=', 'agendas.id_area')
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                ->join('corporativos', 'corporativos.id', '=', 'agendas.id_corporativo')
                ->join('users', 'users.id', '=', 'agendas.id_user')
                ->join('agenda_invitados', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                ->where('agendas.id_user', $idUserLog, )
                ->where('agendas.fecha_hora_meet', '>=', now())
                ->where('agendas.estado', '=', 'pendiente')
                ->where('espacios.id', $espacio_id)
                ->groupBy('agenda_id', 'agendas.id_user', 'agendas.fecha_hora_meet', 'areas.id',
                    'areas.nombre', 'espacios.id', 'espacios.nombre', 'espacios.config',
                    'corporativos.nombre', 'agendas.location', 'agendas.estado', 'agendas.hangoutLink');
            //->get();

            $area_id = $request->input('area');
            $fecha   = $request->input('fecha');
            $user_id = $request->input('usuario');
            $orden   = $request->input('orden');


            if ($area_id) {
                $agendadasQuery->where('agendas.id_area', $area_id);
            }
            if ($fecha) {
                $fecha = date('Y-m-d', strtotime($fecha));
                $agendadasQuery->whereDate('agendas.fecha_hora_meet', $fecha);
            }
            if ($user_id) {
                $agendadasQuery->where('agenda_invitados.id_user', $user_id);
            }
            if ($orden === 'DESC') {
                $agendadasQuery->orderByDesc('agendas.fecha_hora_meet');
            } else {
                $agendadasQuery->orderBy('agendas.fecha_hora_meet');
            }
            $agendadas = $agendadasQuery->get();

            $agendadas_por_pagina = $agendadasQuery->paginate(10);

            $fecha_hora_actual = Carbon::now();

            return response()->json([
                'agendadas'             => $agendadas,
                'agendadas_por_pagina'  => $agendadas_por_pagina,
                'fecha_hora_actual'     => $fecha_hora_actual
            ]);
        }
    }
    public function filtrar_atendidas_agendas(int $id, Request $request)
    {
        if(request()->ajax()){
            $espacio_data = Espacio::findOrFail($id);
            Log::info('Espacio encontrado en atendidas: '.json_encode($espacio_data));
            $espacio_id = $espacio_data->id;
            $usuario    = Auth::user();
            $idUserLog  = $usuario->id;
            // QUERYS PARA LOS TABS: ------------------------------------------------------
            $atendidasQuery = DB::table('agendas')
                ->select(
                    'agendas.id AS agenda_id',
                    'agendas.id_user AS userLog',
                    DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) AS usuario_log'),
                    DB::raw('GROUP_CONCAT((SELECT name FROM users WHERE users.id = agenda_invitados.id_user)) AS invitado'),
                    'agendas.fecha_hora_meet AS fecha_hora',
                    'areas.id AS areas',
                    'areas.nombre AS area_nombre',
                    'espacios.id AS espacios',
                    'espacios.nombre AS espacio_nombre',
                    'espacios.config AS tipo',
                    'espacios.tipo_reunion AS tipo_reunion',
                    'corporativos.nombre AS corporativo_nombre',
                    'agendas.location AS location',
                    'agendas.estado AS estado'
                )
                ->join('areas', 'areas.id', '=', 'agendas.id_area')
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                ->join('corporativos', 'corporativos.id', '=', 'agendas.id_corporativo')
                ->join('users', 'users.id', '=', 'agendas.id_user')
                ->join('agenda_invitados', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                ->where('agendas.id_user', $idUserLog, )
                ->where('agendas.fecha_hora_meet', '<', now())
                ->where('agendas.estado', '=', 'pendiente')
                ->where('espacios.id', $espacio_id)
                ->groupBy('agenda_id', 'agendas.id_user', 'agendas.fecha_hora_meet', 'areas.id',
                    'areas.nombre', 'espacios.id', 'espacios.nombre', 'espacios.config',
                    'espacios.tipo_reunion', 'corporativos.nombre', 'agendas.location',
                    'agendas.estado');

            $area_id = $request->input('area');
            $fecha   = $request->input('fecha');
            $user_id = $request->input('usuario');
            $orden   = $request->input('orden');


            if ($area_id) {
                $atendidasQuery->where('agendas.id_area', $area_id);
            }
            if ($fecha) {
                $fecha = date('Y-m-d', strtotime($fecha));
                $atendidasQuery->whereDate('agendas.fecha_hora_meet', $fecha);
            }
            if ($user_id) {
                $atendidasQuery->where('agenda_invitados.id_user', $user_id);
            }
            if ($orden === 'DESC') {
                $atendidasQuery->orderByDesc('agendas.fecha_hora_meet');
            } else {
                $atendidasQuery->orderBy('agendas.fecha_hora_meet');
            }

            //->get();
            $atendidas = $atendidasQuery->get();

            $atendidas_por_pagina = $atendidasQuery->paginate(10);

            $fecha_hora_actual = Carbon::now();
            return response()->json([
                'atendidas'             => $atendidas,
                'atendidas_por_pagina'  => $atendidas_por_pagina,
                'fecha_hora_actual'     => $fecha_hora_actual
            ]);
        }
    }
    public function filtrar_concluidas_agendas(int $id, Request $request)
    {
        if(request()->ajax()){
            $espacio_data = Espacio::findOrFail($id);
            $espacio_id = $espacio_data->id;
            $usuario    = Auth::user();
            $idUserLog  = $usuario->id;

            // QUERYS PARA LOS TABS: ------------------------------------------------------
            $concluidasQuery = DB::table('agendas')
                ->select(
                    'agendas.id AS agenda_id',
                    'agendas.id_user AS userLog',
                    DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) AS usuario_log'),
                    DB::raw('GROUP_CONCAT((SELECT name FROM users WHERE users.id = agenda_invitados.id_user)) AS invitado'),
                    'agendas.fecha_hora_meet AS fecha_hora',
                    'areas.id AS areas',
                    'areas.nombre AS area_nombre',
                    'espacios.id AS espacios',
                    'espacios.nombre AS espacio_nombre',
                    'espacios.config AS tipo',
                    'corporativos.nombre AS corporativo_nombre',
                    'agendas.location AS location',
                    'agendas.estado AS estado',
                    DB::raw('(SELECT GROUP_CONCAT(archivos) FROM agenda_archivos WHERE id_agenda = agendas.id) AS archivos')
                )
                ->leftJoin('areas', 'areas.id', '=', 'agendas.id_area')
                ->leftJoin('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                ->leftJoin('corporativos', 'corporativos.id', '=', 'agendas.id_corporativo')
                ->leftJoin('agenda_invitados', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                ->where('agendas.id_user', $idUserLog)
                ->where('agendas.estado', 'terminado')
                ->where('espacios.id', $espacio_id)
                ->groupBy('agenda_id', 'agendas.id_user', 'agendas.fecha_hora_meet', 'areas.id',
                    'areas.nombre', 'espacios.id', 'espacios.nombre', 'espacios.config',
                    'corporativos.nombre', 'agendas.location', 'agendas.estado');

            $area_id = $request->input('area');
            $fecha   = $request->input('fecha');
            $user_id = $request->input('usuario');
            $orden   = $request->input('orden');


            //->get();

            if ($area_id) {
                $concluidasQuery->where('agendas.id_area', $area_id);
            }
            if ($fecha) {
                $fecha = date('Y-m-d', strtotime($fecha));
                $concluidasQuery->whereDate('agendas.fecha_hora_meet', $fecha);
            }
            if ($user_id) {
                $concluidasQuery->where('agenda_invitados.id_user', $user_id);
            }
            if ($orden === 'DESC') {
                $concluidasQuery->orderByDesc('agendas.fecha_hora_meet');
            } else {
                $concluidasQuery->orderBy('agendas.fecha_hora_meet');
            }
            $concluidas = $concluidasQuery->get();

            $concluidas_por_pagina = $concluidasQuery->paginate(10);

            return response()->json([
                'concluidas'             => $concluidas,
                'concluidas_por_pagina'  => $concluidas_por_pagina
            ]);
        }
    }
    public function filtrar_pendientes_por_agendar(int $id, Request $request)
    {
        if(request()->ajax()){
            $user_log       = Auth::user();
            $id_user_log    = $user_log->id;
            $areas_user_log = $user_log->areas();
            $areas_id       = $user_log->areas()->pluck('areas.id')->toArray();

            $espacio_search       = Espacio::find($id);
            $tipo_reunion_espacio = $espacio_search->tipo_reunion;
            $id_search            = $espacio_search->id;

            $area_id = $request->input('area');
            $user_id = $request->input('usuario');
            $log_message = [
                'Area filtro' => $area_id,
                'Usuario id' => $user_id
            ];

            $espacio            = '';
            $user_oto           = '';
            $espacio_grupal     = '';
            $usuarios_de_area   = '';
            if($tipo_reunion_espacio === 'individual'){
                $espacio = DB::table('espacios')
                    ->select('espacios.id as espacio_id', 'espacios.nombre as espacio_name', 'espacios.descripcion as espacio_descripcion', 'espacios.config as config', 'espacios.tipo_reunion as tipo_reunion', 'espacios.frecuencia as frecuencia', 'espacios.adjunto as adjunto', 'cargos.nombre as cargo_name') // 'areas.id AS area_id', 'areas.nombre AS area_name'
                    ->join('espacio_cargo', 'espacio_cargo.id_espacio', '=', 'espacios.id')
                    ->join('cargos', 'cargos.id', '=', 'espacio_cargo.id_cargo')
                    ->join('cargo_user', 'cargo_user.id_cargo', '=', 'cargos.id')
                    ->join('users', 'users.id', '=', 'cargo_user.id_user')
                    ->where('users.id', $id_user_log)
                    ->where('espacios.tipo_reunion', '=', 'individual')->limit(1)
                    ->get();

                $data_de_usuarios_individual = DB::table('users')
                    ->leftJoin('agenda_invitados', 'users.id', '=', 'agenda_invitados.id_user')
                    ->leftJoin('agendas', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                    ->leftJoin('espacios', 'agendas.id_espacio', '=', 'espacios.id')
                    ->leftJoin('areas', 'areas.id', '=', 'agendas.id_area')
                    ->whereIn('areas.id', $areas_id)
                    ->where('espacios.id', function ($query) use($tipo_reunion_espacio) {
                        $query->select('id')
                            ->from('espacios')
                            ->where('tipo_reunion', $tipo_reunion_espacio)->limit(1);
                    })
                    ->where('users.id', '!=', $id_user_log) // Excluir al usuario actual
                    ->groupBy('users.id', 'name', 'email')
                    ->havingRaw('TIMESTAMPDIFF(DAY, MAX(agendas.fecha_hora_meet), NOW()) >= (SELECT frecuencia FROM espacios WHERE tipo_reunion = ? LIMIT 1)', [$tipo_reunion_espacio])
                    ->select('users.id as user_id', 'users.name as name', 'users.email as email', DB::raw('GROUP_CONCAT(areas.id) as area_id'), DB::raw('GROUP_CONCAT(areas.nombre) as area_name'), DB::raw('TIMESTAMPDIFF(DAY, MAX(agendas.fecha_hora_meet), NOW()) as ultimo_acceso'));

                $usuarios_con_agendas_individual = DB::table('users')
                    ->leftJoin('area_user', 'users.id', '=', 'area_user.id_user')
                    ->leftJoin('areas', 'area_user.id_area', '=', 'areas.id')
                    ->whereNotExists(function ($query) use ($areas_id, $tipo_reunion_espacio) {
                        $query->select(DB::raw(1))
                            ->from('agenda_invitados')
                            ->join('agendas', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                            ->join('areas', 'agendas.id_area', '=', 'areas.id')
                            ->join('espacios', 'agendas.id_espacio', '=', 'espacios.id')
                            ->whereRaw('agenda_invitados.id_user = users.id')
                            ->whereIn('areas.id', $areas_id)
                            ->where('espacios.id', function ($query) use ($tipo_reunion_espacio) {
                                $query->select('id')
                                    ->from('espacios')
                                    ->where('tipo_reunion', $tipo_reunion_espacio)->limit(1);
                            });
                    })
                    ->whereExists(function ($query) use ($areas_id) {
                        $query->select(DB::raw(1))
                            ->from('area_user')
                            ->whereColumn('area_user.id_user', 'users.id')
                            ->whereIn('area_user.id_area', $areas_id);
                    })
                    ->where('users.id', '!=', $id_user_log) // Excluir al usuario actual
                    ->groupBy('users.id', 'users.name', 'users.email')
                    ->select('users.id', 'users.name', 'users.email', DB::raw('GROUP_CONCAT(areas.id) as area_id'), DB::raw('GROUP_CONCAT(areas.nombre) as area_name'), DB::raw('IFNULL("No tiene", "Null") as ultimo_acceso'));


                $user_oto = $data_de_usuarios_individual->unionAll($usuarios_con_agendas_individual)->paginate(10);
            }else{
                $espacio_grupal = DB::table('espacios')
                    ->select(
                        'espacios.id as espacio_id',
                        'espacios.nombre as espacio_name',
                        'espacios.descripcion as espacio_descripcion',
                        'espacios.config as config',
                        'espacios.tipo_reunion as tipo_reunion',
                        'espacios.frecuencia as frecuencia',
                        'espacios.adjunto as adjunto',
                        'cargos.nombre as cargo_name',
                        DB::raw('GROUP_CONCAT(DISTINCT areas.id) as area_id'),
                        DB::raw('GROUP_CONCAT(DISTINCT areas.nombre) as area_name')
                    )
                    ->join('espacio_cargo', 'espacio_cargo.id_espacio', '=', 'espacios.id')
                    ->join('cargos', 'cargos.id', '=', 'espacio_cargo.id_cargo')
                    ->join('cargo_user', 'cargo_user.id_cargo', '=', 'cargos.id')
                    ->join('users', 'users.id', '=', 'cargo_user.id_user')
                    ->join('area_user', 'area_user.id_user', '=', 'users.id')
                    ->join('areas', 'areas.id', '=', 'area_user.id_area')
                    ->where('espacios.id', $id_search)
                    ->where('users.id', $id_user_log)
                    ->groupBy('espacios.id','espacios.nombre','espacios.descripcion','espacios.config','espacios.tipo_reunion','espacios.frecuencia','espacios.adjunto','cargos.nombre')
                    ->get(); // first()


                $data_de_usuarios_part_2 = DB::table('users')
                    ->leftJoin('agenda_invitados', 'users.id', '=', 'agenda_invitados.id_user')
                    ->leftJoin('agendas', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                    ->leftJoin('espacios', 'agendas.id_espacio', '=', 'espacios.id')
                    ->leftJoin('areas', 'areas.id', '=', 'agendas.id_area')
                    ->whereIn('areas.id', $areas_id)
                    ->where('espacios.id', function ($query) use($tipo_reunion_espacio) {
                        $query->select('id')
                            ->from('espacios')
                            ->where('tipo_reunion', $tipo_reunion_espacio)->limit(1);
                    })
                    ->where('users.id', '!=', $id_user_log)
                    ->groupBy('users.id', 'name', 'email')
                    ->havingRaw('TIMESTAMPDIFF(DAY, MAX(agendas.fecha_hora_meet), NOW()) >= (SELECT frecuencia FROM espacios WHERE tipo_reunion = ? LIMIT 1)',  [$tipo_reunion_espacio])
                    ->select('users.id as user_id', 'users.name as name', 'users.email as email', DB::raw('GROUP_CONCAT(areas.id) as area_id'), DB::raw('GROUP_CONCAT(areas.nombre) as area_name'), DB::raw('TIMESTAMPDIFF(DAY, MAX(agendas.fecha_hora_meet), NOW()) as ultimo_acceso'));

                $usuarios_con_agendas_part_2 = DB::table('users')
                    ->leftJoin('area_user', 'users.id', '=', 'area_user.id_user')
                    ->leftJoin('areas', 'area_user.id_area', '=', 'areas.id')
                    ->whereNotExists(function ($query) use ($areas_id, $tipo_reunion_espacio) {
                        $query->select(DB::raw(1))
                            ->from('agenda_invitados')
                            ->join('agendas', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                            ->join('areas', 'agendas.id_area', '=', 'areas.id')
                            ->join('espacios', 'agendas.id_espacio', '=', 'espacios.id')
                            ->whereRaw('agenda_invitados.id_user = users.id')
                            ->whereIn('areas.id', $areas_id)
                            ->where('espacios.id', function ($query) use($tipo_reunion_espacio) {
                                $query->select('id')
                                    ->from('espacios')
                                    ->where('tipo_reunion', $tipo_reunion_espacio)->limit(1);
                            });
                    })
                    ->whereExists(function ($query) use ($areas_id) {
                        $query->select(DB::raw(1))
                            ->from('area_user')
                            ->whereColumn('area_user.id_user', 'users.id')
                            ->whereIn('area_user.id_area', $areas_id);
                    })
                    ->where('users.id', '!=', $id_user_log)
                    ->groupBy('users.id', 'users.name', 'users.email')
                    ->select('users.id', 'users.name', 'users.email', DB::raw('GROUP_CONCAT(areas.id) as area_id'), DB::raw('GROUP_CONCAT(areas.nombre) as area_name'), DB::raw('IFNULL("No tiene", "Null") as ultimo_acceso'));

                $usuarios_de_area = $data_de_usuarios_part_2->unionAll($usuarios_con_agendas_part_2)->get();
            }

            return response()->json([
                'espacio'               => $espacio,
                'areas'                 => $areas_user_log,
                'user_oto_paginacion'   => $user_oto,
                'usuarios_de_area'      => $usuarios_de_area,
                'espacio_grupal'        => $espacio_grupal,
            ]);
        }
    }


    /********************************************************************************************************************/
    //Buscador de usuarios por área para todos los tipos de espacio a excepción de OTO
    public function buscar_usuario_por_area(Request $request)
    {
        try {
            $areaId = $request->input('area_id');
            $espacioId = $request->input('espacio_id');
            $area = Area::find($areaId);
            $espacio = Espacio::find($espacioId);
            $tipo_reunion_espacio = $espacio->tipo_reunion;

            $user = Auth::user();
            $id_user_log = $user->id;

            $data_de_usuarios = '';
            $usuarios_con_agendas = '';
            $usuarios = '';
            // if($tipo_reunion_espacio === 'pares'){

                $data_de_usuarios = DB::table('users')
                    ->leftJoin('agenda_invitados', 'users.id', '=', 'agenda_invitados.id_user')
                    ->leftJoin('agendas', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                    ->leftJoin('espacios', 'agendas.id_espacio', '=', 'espacios.id')
                    ->leftJoin('areas', 'areas.id', '=', 'agendas.id_area')
                    ->where('areas.id', '=', $areaId)
                    ->where('espacios.id', function ($query) use ($tipo_reunion_espacio) {
                        $query->select('id')
                            ->from('espacios')
                            ->where('tipo_reunion', '=', $tipo_reunion_espacio)->limit(1);
                    })
                    ->where('users.id', '!=', $id_user_log)
                    ->groupBy('users.id', 'name', 'email')
                    ->havingRaw('TIMESTAMPDIFF(DAY, MAX(agendas.fecha_hora_meet), NOW()) >= (SELECT frecuencia FROM espacios WHERE tipo_reunion = ? LIMIT 1)', [$tipo_reunion_espacio])
                    ->select('users.id as user_id', 'users.name as name', 'users.email as email', DB::raw('TIMESTAMPDIFF(DAY, MAX(agendas.fecha_hora_meet), NOW()) as ultimo_acceso'));

                $usuarios_con_agendas = DB::table('users')
                    ->leftJoin('area_user', 'users.id', '=', 'area_user.id_user')
                    ->leftJoin('areas', 'area_user.id_area', '=', 'areas.id')
                    ->whereNotExists(function ($query) use ($areaId, $tipo_reunion_espacio) {
                        $query->select(DB::raw(1))
                            ->from('agenda_invitados')
                            ->join('agendas', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                            ->join('areas', 'agendas.id_area', '=', 'areas.id')
                            ->join('espacios', 'agendas.id_espacio', '=', 'espacios.id')
                            ->whereRaw('agenda_invitados.id_user = users.id')
                            ->where('areas.id', '=', $areaId)
                            ->where('espacios.id', function ($query) use ($tipo_reunion_espacio) {
                                $query->select('id')
                                    ->from('espacios')
                                    ->where('tipo_reunion', '=', $tipo_reunion_espacio)->limit(1);
                            });
                    })
                    ->whereExists(function ($query) use ($areaId) {
                        $query->select(DB::raw(1))
                            ->from('area_user')
                            ->whereColumn('area_user.id_user', 'users.id')
                            ->where('area_user.id_area', '=', $areaId);
                    })
                    ->where('users.id', '!=', $id_user_log)
                    ->groupBy('users.id', 'users.name', 'users.email')
                    ->select('users.id', 'users.name', 'users.email', DB::raw('IFNULL("No tiene", "Null") as ultimo_acceso'));

                $usuarios = $data_de_usuarios->unionAll($usuarios_con_agendas)->get();

            /*}else{
                $data_de_usuarios = DB::table('users')
                    ->leftJoin('agenda_invitados', 'users.id', '=', 'agenda_invitados.id_user')
                    ->leftJoin('agendas', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                    ->leftJoin('espacios', 'agendas.id_espacio', '=', 'espacios.id')
                    ->leftJoin('areas', 'areas.id', '=', 'agendas.id_area')
                    ->where('areas.id', '=', $areaId)
                    ->where('espacios.id', function ($query) use ($tipo_reunion_espacio) {
                        $query->select('id')
                            ->from('espacios')
                            ->where('tipo_reunion', '=', $tipo_reunion_espacio);
                    })
                    ->where('users.id', '!=', $id_user_log)
                    ->groupBy('users.id', 'name', 'email')
                    ->havingRaw('TIMESTAMPDIFF(DAY, MAX(agendas.fecha_hora_meet), NOW()) >= (SELECT frecuencia FROM espacios WHERE tipo_reunion = ?)', [$tipo_reunion_espacio])
                    ->select('users.id as user_id', 'users.name as name', 'users.email as email', DB::raw('TIMESTAMPDIFF(DAY, MAX(agendas.fecha_hora_meet), NOW()) as ultimo_acceso'));

                $usuarios_con_agendas = DB::table('users')
                    ->leftJoin('area_user', 'users.id', '=', 'area_user.id_user')
                    ->leftJoin('areas', 'area_user.id_area', '=', 'areas.id')
                    ->whereNotExists(function ($query) use ($areaId, $tipo_reunion_espacio) {
                        $query->select(DB::raw(1))
                            ->from('agenda_invitados')
                            ->join('agendas', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                            ->join('areas', 'agendas.id_area', '=', 'areas.id')
                            ->join('espacios', 'agendas.id_espacio', '=', 'espacios.id')
                            ->whereRaw('agenda_invitados.id_user = users.id')
                            ->where('areas.id', '=', $areaId)
                            ->where('espacios.id', function ($query) use ($tipo_reunion_espacio) {
                                $query->select('id')
                                    ->from('espacios')
                                    ->where('tipo_reunion', '=', $tipo_reunion_espacio);
                            });
                    })
                    ->whereExists(function ($query) use ($areaId) {
                        $query->select(DB::raw(1))
                            ->from('area_user')
                            ->whereColumn('area_user.id_user', 'users.id')
                            ->where('area_user.id_area', '=', $areaId);
                    })
                    ->where('users.id', '!=', $id_user_log) // Excluir al usuario actual
                    ->groupBy('users.id', 'users.name', 'users.email')
                    ->select('users.id', 'users.name', 'users.email', DB::raw('IFNULL("No tiene", "Null") as ultimo_acceso'));

                $usuarios = $data_de_usuarios->unionAll($usuarios_con_agendas)->get();
            }*/

            return response()->json([
                'usuarios' => $usuarios
            ]);

        } catch (\Exception $ex) {
            Log::error(['error'=>'Error en el acceso a la data de area: '.$ex->getMessage()]);
            return response()->json(['error' => 'Ocurrió un error en el servidor.'], 500);
        }
    }
    /*******************************************************************************************************************/
    // Vista segun el tipo_de_espacio_por_estado
    public function sessiones_para_estado_espacio(Request $request)
    {
        session_start();
        if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary'; // por default jala calendario del usuario logueado en google accounts
            $optParams = array(
                'maxResults' => 10,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c'),
            );
            $results = $service->events->listEvents($calendarId, $optParams);
            $events = $results->getItems();

            $usuario    = Auth::user();
            $idUserLog  = $usuario->id;
            $areasUser  = User::find($idUserLog)->areas;
            $id_areas = $areasUser->pluck('id')->toArray();

            $usuarios = DB::table('users')
                ->select('users.id as id',
                    'users.name as name',
                    'users.email as email'
                )
                ->join('area_user', 'area_user.id_user', '=', 'users.id')
                ->join('areas', 'areas.id', '=', 'area_user.id_area')
                ->where('areas.id', [$id_areas])
                ->get();

            if ($request->has('sesiones')) {
                $sesiones = $request->input('sesiones'); // atendidas -> 1 ; pendientes -> 2
                session(['sesiones' => $sesiones]);
            }

            return view('oficial.estado_sesiones', [
                'sesiones_redirec'  => $sesiones,
                'areasUser' => $areasUser,
                'usuarios'  => $usuarios,
                'events'    => $events,
                'calendarId'=> $calendarId
            ]);
        }else{
            return redirect()->route('calendar.oauth');
        }
    }

    public function estado_agendas_atendidas(Request $request)
    {
        if(request()->ajax()){
            $usuario    = Auth::user();
            $idUserLog  = $usuario->id;

            // QUERYS PARA LOS TABS: ------------------------------------------------------
            $atendidasQuery = DB::table('agendas')
                ->select(
                    'agendas.id AS agenda_id',
                    'agendas.id_user AS userLog',
                    DB::raw('(SELECT name FROM users WHERE users.id = agendas.id_user) AS usuario_log'),
                    DB::raw('GROUP_CONCAT((SELECT name FROM users WHERE users.id = agenda_invitados.id_user)) AS invitado'),
                    'agendas.fecha_hora_meet AS fecha_hora',
                    'areas.id AS areas',
                    'areas.nombre AS area_nombre',
                    'espacios.id AS espacios',
                    'espacios.nombre AS espacio_nombre',
                    'espacios.config AS tipo',
                    'espacios.tipo_reunion AS tipo_reunion',
                    'corporativos.nombre AS corporativo_nombre',
                    'agendas.location AS location',
                    'agendas.estado AS estado'
                )
                ->join('areas', 'areas.id', '=', 'agendas.id_area')
                ->join('espacios', 'espacios.id', '=', 'agendas.id_espacio')
                ->join('corporativos', 'corporativos.id', '=', 'agendas.id_corporativo')
                ->join('users', 'users.id', '=', 'agendas.id_user')
                ->join('agenda_invitados', 'agenda_invitados.id_agenda', '=', 'agendas.id')
                ->where('agendas.id_user', $idUserLog, )
                ->where('agendas.fecha_hora_meet', '<', now())
                ->where('agendas.estado', '=', 'pendiente')
                ->groupBy('agenda_id', 'userLog', 'fecha_hora', 'areas', 'area_nombre', 'espacios', 'espacio_nombre', 'tipo', 'tipo_reunion', 'corporativo_nombre', 'location', 'estado');

            $area_id = $request->input('area');
            $fecha   = $request->input('fecha');
            $user_id = $request->input('usuario');
            $orden   = $request->input('orden');

            if ($area_id) {
                $atendidasQuery->where('agendas.id_area', $area_id);
            }
            if ($fecha) {
                $fecha = date('Y-m-d', strtotime($fecha));
                $atendidasQuery->whereDate('agendas.fecha_hora_meet', $fecha);
            }
            if ($user_id) {
                $atendidasQuery->where('agenda_invitados.id_user', $user_id);
            }
            if ($orden === 'DESC') {
                $atendidasQuery->orderByDesc('agendas.fecha_hora_meet');
            } else {
                $atendidasQuery->orderBy('agendas.fecha_hora_meet');
            }

            //->get();
            $atendidas = $atendidasQuery->get();

            $atendidas_por_pagina = $atendidasQuery->paginate(10);

            $fecha_hora_actual = Carbon::now();
            return response()->json([
                'atendidas'             => $atendidas,
                'atendidas_por_pagina'  => $atendidas_por_pagina,
                'fecha_hora_actual'     => $fecha_hora_actual
            ]);
        }
    }

    public function  estado_agendas_pendientes(Request $request)
    {
        if(request()->ajax()){
            $usuario_log = Auth::user();
            $id_user_log = $usuario_log->id;
            $username = $usuario_log->name;
            $areasUser = User::find($id_user_log)->areas;
            // Obtener los cargos del usuario autenticado
            $id_areas = $areasUser->pluck('id')->toArray();


            $usuarios_de_area_individual  = $this->datosPorTipoEspacio('individual', $id_areas, $id_user_log);
            $usuarios_de_area_country     = $this->datosPorTipoEspacio('country', $id_areas, $id_user_log);
            $usuarios_de_area_primario    = $this->datosPorTipoEspacio('primario', $id_areas, $id_user_log);
            $usuarios_de_area_compras     = $this->datosPorTipoEspacio('compras', $id_areas, $id_user_log);
            $usuarios_de_area_merco       = $this->datosPorTipoEspacio('merco', $id_areas, $id_user_log);
            $usuarios_de_area_indicadores = $this->datosPorTipoEspacio('indicadores', $id_areas, $id_user_log);
            $usuarios_de_area_sostenibilidad   = $this->datosPorTipoEspacio('sostenibilidad', $id_areas, $id_user_log);

            $espacios_de_usuario_log = DB::table('espacios')
                ->select('espacios.id as espacio_id', 'espacios.nombre as espacio_name', 'espacios.descripcion as espacio_descripcion', 'espacios.config as config', 'espacios.tipo_reunion as tipo_reunion', 'espacios.frecuencia as frecuencia', 'espacios.adjunto as adjunto', 'cargos.nombre as cargo_name') // 'areas.id AS area_id', 'areas.nombre AS area_name'
                ->join('espacio_cargo', 'espacio_cargo.id_espacio', '=', 'espacios.id')
                ->join('cargos', 'cargos.id', '=', 'espacio_cargo.id_cargo')
                ->join('cargo_user', 'cargo_user.id_cargo', '=', 'cargos.id')
                ->join('users', 'users.id', '=', 'cargo_user.id_user')
                ->where('users.id', $id_user_log)
                ->where('espacios.tipo_reunion', '=', 'individual')
                ->get();
            Log::info('Espacios de usuario log'.$espacios_de_usuario_log);

            $espacios_de_usuario_log_grupal = $this->espaciosDeUsuarioLogGrupal($id_user_log);
            Log::info('Espacios con areas concatenadas encontradas: '.json_encode($espacios_de_usuario_log_grupal));

            return response()->json([
                'usuarios_de_area_individual'       => $usuarios_de_area_individual,
                'usuarios_de_area_primario'         => $usuarios_de_area_primario,
                'usuarios_de_area_country'          => $usuarios_de_area_country,
                'usuarios_de_area_compras'          => $usuarios_de_area_compras,
                'usuarios_de_area_merco'            => $usuarios_de_area_merco,
                'usuarios_de_area_indicadores'      => $usuarios_de_area_indicadores,
                'usuarios_de_area_sostenibilidad'   => $usuarios_de_area_sostenibilidad,
                'espacios_de_usuario_log'           => $espacios_de_usuario_log,
                'espacios_de_usuario_log_grupal'    => $espacios_de_usuario_log_grupal
            ]);
        }
    }

}
