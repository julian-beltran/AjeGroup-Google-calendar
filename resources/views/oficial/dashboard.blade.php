@extends('adminlte::page')

@section('title', 'Dashboard | Espacios | Líder')

@section('content_header')
@stop
@section('content')
    <div class="py-12 mt-2 container col-lg-12 col-md-12 col-sm-12 pb-3" xmlns="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="container pt-2" >
                    <div class="cabecera cabecera-dash d-flex justify-content-between align-items-center mb-2">
                        <div class="username-text">
                            <b>¡Bienvenid@ {{$username}} !</b>
                        </div>
                        <div class="input-text">
                            <input type="month" id="mes-filtro" name="mes-filtro" class="input-fecha-cabecera" onchange="filtrarPorMes()">
                        </div>
                    </div>

                    {{--Contenido parte 1: Cards sobre usuarios y cantidad de agendas en estados: agendadas | atendidas | concluidas--}}
                    <div class="row d-flex justify-content-around mb-2 container-cards-count">
			            <div class="card col-2 content-cards first-card">
				            <div class="card-body" id="contenido_primer_kpi">
					            <div class="title-card-counts">
						            <h5 class="title-card-counts">Usuarios de area</h5>
						            <i class="far fa-clock icon-cards-counts"></i>
	                            </div>
                                @if ($totalUsuariosArea_parte_1)
                                    <strong class="card-title d-flex" id="data-1">
                                        <b class="title-body-card">{{ $totalUsuariosArea_parte_1 }}</b>
                                    </strong>
                                @else
                                    <strong class="card-title d-flex" id="data-1">
                                        <b class="title-body-card">0</b>
                                    </strong>
                                @endif
                            </div>
                        </div>
                        <div class="card col-2 content-cards ml-2 second-card">
                            <div class="card-body" id="contenido_segundo_kpi">
                                <div class="title-card-counts">
                                    <h5 class="title-card-counts">Agendadas</h5>
                                    <i class="far fa-calendar icon-cards-counts"></i>
                                </div>
                                <strong class="card-title d-flex" id="data-2"></strong>
                            </div>
                        </div>
                        <div class="card col-2 content-cards ml-2 third-card">
                            <div class="card-body" id="contenido_tercer_kpi">
                                <div class="title-card-counts">
                                    <h5 class="title-card-counts">Atendidas</h5>
                                    <i class="fas fa-comment icon-cards-counts"></i>
                                </div>
                                <strong class="card-title d-flex" id="data-3"></strong>
                            </div>
                        </div>
                        <div class="card col-2 content-cards ml-2 fourt-card">
                            <div class="card-body" id="contenido_cuarto_kpi">
                                <div class="title-card-counts">
                                    <h5 class="title-card-counts">Cerradas</h5>
                                    <i class="fas fa-calendar-check icon-cards-counts"></i>
                                </div>
                                <strong class="card-title d-flex" id="data-4"></strong>
                            </div>
                        </div>
                    </div>

                    {{--Contenido parte 2: Se muestra el slider sobre agendas próximas a llevarse a cabo en un rango de 24Horas--}}
                    <div class="d-flex justify-content-between mt-2 mb-2">
                        <strong class="title-cabeceras">Tus sesiones más cercanas</strong>
                    </div>

                    @if(count($data_slide_seccion_2) > 0)
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="new-slider_part_one" class="owl-carousel w-100">
                                        @foreach ($data_slide_seccion_2 as $key => $data)
                                            <div class="post-slide card-sesiones-cercanas">
                                                <div class="post-content contenido-slider-seccion-2">
                                                    <div class="row">
                                                        <div class="contenedor-slider-cercanas">
                                                            <div class="d-flex justify-content-between mt-1">
                                                                <div class="grupo-encabezado d-flex">
                                                                    <strong class="ml-2 text-espacio-cercana">{{ $data->espacio_name }}</strong>
                                                                </div>
                                                                <div class="grupo-encabezado d-flex">
                                                                    <p class="ml-2 text-estado-cercana fw-bold {{ $data->estado === 'pendiente' ? 'text-danger' : 'text-success' }} border rounded-pill px-2 py-1">Agendada <i class="far fa-calendar"></i></p>
                                                                </div>
                                                            </div>
                                                            <div class="fecha_hora_meet d-flex">
                                                                @php
                                                                    // Conversion de la fecha hora meet al formato: Jueves, 25 de marzo - 2024 | 7:00 am - 8:am
                                                                    $fecha_hora_meet = $data->fecha_hora;

                                                                    $diasSemana = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
                                                                    $meses = array(1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

                                                                    $timestamp = strtotime($fecha_hora_meet);
                                                                    $diaSemana = $diasSemana[date('w', $timestamp)];
                                                                    $diaMes = date('j', $timestamp);
                                                                    $mes = $meses[date('n', $timestamp)];
                                                                    $anio = date('Y', $timestamp);
                                                                    $hora = date('g:i A', $timestamp);

                                                                    $fechaHoraFormateada1 = "$diaSemana, $diaMes de $mes - $anio | $hora";
                                                                @endphp
                                                                <div class="col"><p class="ms-2">{{$fechaHoraFormateada1}}</p></div>
                                                                <div class="col content-right"><span>ID: {{$data->id}}</span></div>
                                                            </div>
                                                            <div class="areas d-flex">
                                                                <strong class="ms-2 text-area-cercana">Area: </strong>
                                                                <p class="ms-2 text-area-espacio">{{ $data->area_name }}</p>
                                                            </div>
                                                            <div class="contenedor-slider-cercanas d-flex justify-content-between mb-4">
                                                                <div class="invitados d-flex flex-wrap">
                                                                    <i class="fas fa-user-circle icon-invitados"></i>
                                                                    @php
                                                                        $invitados = explode(',', $data->invitados);
                                                                        $invitadosCount = count($invitados);
                                                                    @endphp
                                                                    @foreach ($invitados as $key => $invitado)
                                                                        @if ($key < 2)
                                                                            <span class="mr-1 rounded span-invitados" style="margin-bottom: 1px; margin-top: 1px;">{{ $invitado }}</span>
                                                                        @endif
                                                                    @endforeach
                                                                    @if ($invitadosCount > 2)
                                                                        <div class="dropdown" style="z-index: 100;">
                                                                            <button class="btn btn-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButtonCercanas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Ver más
                                                                            </button>
                                                                            <div class="dropdown-menu dropdown-menu-cercanas" aria-labelledby="dropdownMenuButtonCercanas">
                                                                                @foreach ($invitados as $key => $invitado)
                                                                                    @if ($key >= 2)
                                                                                        <span class="dropdown-item">{{ $invitado }}</span>
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="meet">
                                                                    <a href="{{ $data->link_meet }}" target="_blank" class="ms-2 btn btn-outline-success text-meet-cercana" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Unirse <i class="fas fa-video"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-danger">Aun no cuentas con agendas cercanas.</p>
                    @endif

                    {{--Contenido de la parte 3: Sesiones atendidas: Los cuales faltan subir las evidencias de la sesion o cerrar agenda -> 1: atendidas--}}
                    <div class="d-flex justify-content-between mt-2 mb-2">
                        <strong class="title-cabeceras">Sesiones atendidas</strong>
                        <a href="{{route('vista.estado_de_espacios', ['sesiones' => '1'])}}" class="btn-ver-todas-atendidas">Ver todas</a>
                    </div>

                    @if(count($data_slide_seccion_3) > 0)
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="new-slider_part_two" class="owl-carousel w-100">
                                        @foreach ($data_slide_seccion_3 as $data)
                                            <div class="post-slide card-sesiones-atendidas">
                                                <div class="post-content">
                                                    <div class="d-flex justify-content-between mt-1">
                                                        <div class="grupo-encabezado">
                                                            <strong class="text-espacio-atendida">{{ $data->espacio_name}}</strong>
                                                        </div>
                                                        <div class="grupo-encabezado">
                                                            @if($data->estado === 'pendiente')
                                                                <p class="text-estado-atendidas">Atendida <i class="fas fa-comment"></i></p>
                                                            @else
                                                                <p style="color:rgba(49,239,5,0.76); margin-left: 5px; font-weight:bold; border-radius: 16px;padding: 5px;">Cerrada</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="fecha_hora_meet d-flex justify-content-between">
                                                        @php
                                                            // Conversion de la fecha hora meet al formato: Jueves, 25 de marzo - 2024 | 7:00 am - 8:am
                                                            $fecha_hora_meet = $data->fecha_hora;

                                                            $diasSemana = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
                                                            $meses = array(1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

                                                            $timestamp = strtotime($fecha_hora_meet);
                                                            $diaSemana = $diasSemana[date('w', $timestamp)];
                                                            $diaMes = date('j', $timestamp);
                                                            $mes = $meses[date('n', $timestamp)];
                                                            $anio = date('Y', $timestamp);
                                                            $hora = date('g:i A', $timestamp);

                                                            $fechaHoraFormateada2 = "$diaSemana, $diaMes de $mes - $anio | $hora";
                                                        @endphp
                                                        <div class="col"><p style="margin-left: 5px;">{{$fechaHoraFormateada2}}</p></div>
                                                        <div class="col content-right">ID: {{$data->id}}</div>
                                                    </div>
                                                    <div class="areas d-flex">
                                                        <strong class="text-area-atendida">Area: </strong>
                                                        <p class="ml-1 text-area-espacio" style="margin-left: 5px;"> {{ $data->area_name }}</p>
                                                    </div>
                                                    <div class="invitados d-flex">
                                                        <i class="fas fa-user-circle icon-invitados"></i>
                                                        {{--<span class="rounded ml-1 span-invitados"> {{ $data->invitados }}</span>--}}
                                                        @php
                                                            $invitados = explode(',', $data->invitados);
                                                            $invitadosCount = count($invitados);
                                                        @endphp
                                                        @foreach ($invitados as $key => $invitado)
                                                            @if ($key < 2)
                                                                <span class="mr-1 rounded span-invitados" style="margin-bottom: 1px; margin-top: 1px;">{{ $invitado }}</span>
                                                            @endif
                                                        @endforeach
                                                        @if ($invitadosCount > 2)
                                                            <div class="dropdown" style="z-index: 100;">
                                                                <button class="btn btn-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButtonCercanas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Ver más
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-cercanas" aria-labelledby="dropdownMenuButtonCercanas">
                                                                    @foreach ($invitados as $key => $invitado)
                                                                        @if ($key >= 2)
                                                                            <span class="dropdown-item">{{ $invitado }}</span>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-1">
                                                        <div class="evidencia-options">
                                                            <strong class="text-soportes">Soporte: </strong>
                                                            <p class="text-soportes-desc" style="margin-left: 5px;">Sin soporte</p>
                                                        </div>
                                                        <div class="evidencia-options d-flex justify-content-around">
                                                            <div class="mr-1">
                                                                <a href="javascript:void(0)" class="btn btn-outline-light btn-subir-evidencia-card ver-datos-agenda-para-evidencias text-meet-cercana" data-url="/anfitrion/agendas/ver_datos_agenda_evidencia/{{$data->id}}">Subir reporte <i class="fas fa-cloud-upload-alt"></i></a>
                                                            </div>
                                                            @php
                                                                $tipo_reunion = $data->tipo_reunion;
                                                            @endphp
                                                            @if ($tipo_reunion === 'max 10')
                                                                <div>
                                                                    <a href="javascript:void(0)" class="btn btn-success cerrar_agenda " data-url="/anfitrion/agendas/ver_data_culminacion/{{$data->id}}">Cerrar <i class="fas fa-times"></i></a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-danger">Aun no cuentas con agendas atendidas.</p>
                    @endif

                    {{--Contenido de la parte 4: Sesiones pendientes por agendar por espacios:--------------------------------------------------}}
                    <div class="d-flex justify-content-between mt-2 mb-2">
                        <strong  class="title-cabeceras">Sesiones pendientes por agendar</strong>
                        {{--session 2: pendientes--}}
                        <a href="{{route('vista.estado_de_espacios', ['sesiones'=>'2'])}}" class="btn-ver-todas-pendientes">Ver todas</a>
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                @if ($espacios_de_usuario_log->count() > 0 && $espacios_de_usuario_log_grupal->count() > 0)
                                    <div id="new-slider_part_trhee" class="owl-carousel w-100">
                                        @php
                                            $espacio_individual = false;
                                        @endphp
                                        @foreach($espacios_de_usuario_log as $espacio)
                                            @php
                                                $nombre_espacio = $espacio->espacio_name;
                                                $descri_espacio = $espacio->espacio_descripcion;
                                                $config_espacio = $espacio->config;
                                                $tipo_reunion   = $espacio->tipo_reunion;
                                                // $area_id        = $espacio->area_id;
                                                // $area_name      = $espacio->area_name;
                                            @endphp
                                            @if($tipo_reunion === 'individual')
                                                @php
                                                    $espacio_individual = true;
                                                @endphp
                                                @foreach ($usuarios_de_area_individual as $usuario)
                                                    <div class="post-slide card-sesiones-pendientes">
                                                        <div class="post-content mt-1">
                                                            <div class="d-flex justify-content-between">
                                                                <div class="grupo-encabezado">
                                                                    <strong style="margin-left: 5px;">{{ $nombre_espacio }}</strong>
                                                                </div>
                                                                <div class="grupo-encabezado">
                                                                    <a href="javascript:void(0)" class="btn btn-success ver-datos-para-agendar btn-agendar-pendientes" data-url="{{ route('anfitrion.dashboard.ver_datos', ['userId' => $usuario->user_id, 'espacioId' => $espacio->espacio_id, 'areaId' => $usuario->area_id]) }}">Agendar <i class="far fa-clock"></i></a>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <p class="post-description">{{$descri_espacio}}</p>
                                                            </div>
                                                            <div class="areas mb-1">
                                                                <strong class="ms-2 text-area-cercana">Area: </strong>
                                                                <span class="ms-2 text-area-espacio">{{ $usuario->area_name }}</span>
                                                            </div>
                                                            <div class="invitados">
                                                                <i class="fas fa-user-circle icon-invitados"></i>
                                                                <span class="ml-1 rounded span-invitados">{{ $usuario->name }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endforeach
                                        @foreach ($espacios_de_usuario_log_grupal as $espacio)
                                            @php
                                                $nombre_espacio = $espacio->espacio_name;
                                                $descri_espacio = $espacio->espacio_descripcion;
                                                $config_espacio = $espacio->config;
                                                $tipo_reunion   = $espacio->tipo_reunion;
                                                $area_id        = $espacio->area_id;
                                                //   $area_name      = $espacio->area_name;
                                                $areas = explode(',', $espacio->area_name);
                                            @endphp
                                            @if ($tipo_reunion === 'primario')
                                                <div class="post-slide card-sesiones-pendientes">
                                                    <div class="post-content mt-1">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong style="margin-left: 5px;">{{ $nombre_espacio }}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                @if($usuarios_de_area_primario->count() > 0)
                                                                    <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_primario" data-url="{{ route('anfitrion.dashboard.ver_data_primario', ['userIds' => implode(',', $usuarios_de_area_primario->pluck('user_id')->toArray()), 'espacioId' => $espacio->espacio_id, 'areaId' => $area_id]) }}">Agendar</a>
                                                                @else
                                                                    <a href="javascript:void(0)" class="btn btn-outline-secondary btn-disabled" id="" disabled>Agendar</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="post-description">{{$descri_espacio}}</p>
                                                        </div>
                                                        <div class="areasd-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                @foreach ($areas as $area)
                                                                    <span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">{{ $area }}</span> <!-- Cada área dentro de un <span> -->
                                                                @endforeach
                                                            </div>
                                                            {{--<span class="ms-2 text-area-espacio" style="margin-left: 5px;">{{ $area_name   }}</span>--}}
                                                        </div>
                                                        <div class="invitados mt-1">
                                                            <i class="fas fa-user-circle icon-invitados">
                                                                @if ($usuarios_de_area_primario->count() > 0)
                                                                    <strong>Invitados: </strong>
                                                                    <div class="mt-2 d-flex flex-wrap">
                                                                        @foreach ($usuarios_de_area_primario->take(2) as $usuario)
                                                                            <p class="ml-1 rounded span-invitados"> {{ $usuario->name }} | {{$usuario->area_name}}</p>
                                                                        @endforeach
                                                                        @if ($usuarios_de_area_primario->count() > 2)
                                                                            <!-- Dropdown para mostrar usuarios adicionales -->
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    Otros Invitados
                                                                                </button>
                                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                                    @foreach ($usuarios_de_area_primario->slice(2) as $usuario)
                                                                                        <a class="dropdown-item" href="#">{{ $usuario->name }} | {{$usuario->area_name}}</a>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <strong>OBS:</strong>
                                                                    <p class="text-danger">La agenda ya fue programada</p>
                                                                @endif
                                                            </i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($tipo_reunion === 'pares')
                                                <div class="post-slide card-sesiones-pendientes">
                                                    <div class="post-content mt-1">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong  class="text-espacio-cercana" style="margin-left: 5px;">{{ $nombre_espacio }}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_pares" data-url="{{ route('anfitrion.dashboard.ver_data_pares', ['espacioId' => $espacio->espacio_id, 'areaId' => $area_id]) }}">Agendar <i class="far fa-clock"></i></a>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="post-description">{{$descri_espacio}}</p>
                                                        </div>
                                                        <div class="areas d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                @foreach ($areas as $area)
                                                                    <span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">{{ $area }}</span> <!-- Cada área dentro de un <span> -->
                                                                @endforeach
                                                            </div>
                                                            {{--<span class="ms-2 text-area-espacio">{{ $area_name  }}</span>--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($tipo_reunion === 'max 10')
                                                <div class="post-slide card-sesiones-pendientes">
                                                    <div class="post-content mt-1">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana" style="margin-left: 5px;">{{ $nombre_espacio }}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                <a href="javascript:void(0)" class="btn btn-success  btn-agendar-pendientes" id="agendas_max_10" data-url="{{ route('anfitrion.dashboard.ver_datos_max_10', ['espacioId' => $espacio->espacio_id, 'areaId' => $area_id]) }}">Agendar <i class="far fa-clock"></i></a>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="post-description">{{$descri_espacio}}</p>
                                                        </div>
                                                        <div class="areas d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                @foreach ($areas as $area)
                                                                    <span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">{{ $area }}</span> <!-- Cada área dentro de un <span> -->
                                                                @endforeach
                                                            </div>
                                                            {{--<span class="ms-2 text-area-espacio" style="margin-left: 5px;">{{ $area_name  }}</span>--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($tipo_reunion === 'country')
                                                <div class="post-slide card-sesiones-pendientes">
                                                    <div class="post-content mt-1">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana" style="margin-left: 5px;">{{ $nombre_espacio }}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                @if($usuarios_de_area_country->count() > 0)
                                                                    <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="country_agendas" data-url="{{ route('anfitrion.dashboard.ver_data_country', ['userIds' => implode(',', $usuarios_de_area_country->pluck('user_id')->toArray()), 'espacioId' => $espacio->espacio_id, 'areaId' => $area_id]) }}">Agendar <i class="far fa-clock"></i></a>
                                                                @else
                                                                    <a href="javascript:void(0)" class="btn btn-outline-secondary btn-disabled" id="country_agendas" disabled>Agendar</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="post-description">{{$descri_espacio}}</p>
                                                        </div>
                                                        <div class="areas d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                @foreach ($areas as $area)
                                                                    <span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">{{ $area }}</span> <!-- Cada área dentro de un <span> -->
                                                                @endforeach
                                                            </div>
                                                            {{--<span class="ms-2 text-area-espacio" style="margin-left: 5px;">{{ $area_name  }}</span>--}}
                                                        </div>
                                                        <div class="invitados mb-2 mt-1">
                                                            <i class="fas fa-user-circle icon-invitados">
                                                                @if ($usuarios_de_area_country->count() > 0)
                                                                    <strong>Invitados: </strong>
                                                                    <div class="mt-2 d-flex flex-wrap">
                                                                        @foreach ($usuarios_de_area_country->take(2) as $usuario)
                                                                            <p class="span-invitados" style="color: green">{{ $usuario->name }} | {{$usuario->area_name}}</p>
                                                                        @endforeach
                                                                        @if ($usuarios_de_area_country->count() > 2)
                                                                            <!-- Dropdown para mostrar usuarios adicionales -->
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    Otros Invitados
                                                                                </button>
                                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                                    @foreach ($usuarios_de_area_country->slice(2) as $usuario)
                                                                                        <a class="dropdown-item" href="#">{{ $usuario->name }} | {{$usuario->area_name}}</a>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <strong>OBS:</strong>
                                                                    <p class="text-danger">La agenda ya fue programada</p>
                                                                @endif
                                                            </i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($tipo_reunion === 'compras')
                                                <div class="post-slide card-sesiones-pendientes">
                                                    <div class="post-content mt-1">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana" style="margin-left: 5px;">{{ $nombre_espacio }}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                @if($usuarios_de_area_compras->count() > 0)
                                                                    <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_compras" data-url="{{ route('anfitrion.dashboard.ver_data_compras', ['userIds' => implode(',', $usuarios_de_area_compras->pluck('user_id')->toArray()), 'espacioId' => $espacio->espacio_id, 'areaId' => $area_id]) }}">Agendar <i class="far fa-clock"></i></a>
                                                                @else
                                                                    <a href="javascript:void(0)" class="btn btn-secondary btn-disabled" id="" disabled>Agendar</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="post-description">{{$descri_espacio}}</p>
                                                        </div>
                                                        <div class="areas d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                @foreach ($areas as $area)
                                                                    <span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">{{ $area }}</span> <!-- Cada área dentro de un <span> -->
                                                                @endforeach
                                                            </div>
                                                            {{--<span class="ms-2 text-area-espacio" style="margin-left: 5px;">{{ $area_name  }}</span>--}}
                                                        </div>
                                                        <div class="invitados mb-2">
                                                            <i class="fas fa-user-circle icon-invitados">
                                                                @if ($usuarios_de_area_compras->count() > 0)
                                                                    <strong>Invitados: </strong>
                                                                    <div class="mt-2 d-flex flex-wrap">
                                                                        @foreach ($usuarios_de_area_compras->take(2) as $usuario)
                                                                            <p class="span-invitados" style="color: green">{{ $usuario->name }} | {{$usuario->area_name}}</p>
                                                                        @endforeach
                                                                        @if ($usuarios_de_area_compras->count() > 2)
                                                                            <!-- Dropdown para mostrar usuarios adicionales -->
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    Otros Invitados
                                                                                </button>
                                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                                    @foreach ($usuarios_de_area_compras->slice(2) as $usuario)
                                                                                        <a class="dropdown-item" href="#">{{ $usuario->name }} | {{$usuario->area_name}}</a>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <strong>OBS:</strong>
                                                                    <p class="text-danger">La agenda ya fue programada</p>
                                                                @endif
                                                            </i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($tipo_reunion === 'merco')
                                                <div class="post-slide card-sesiones-pendientes">
                                                    <div class="post-content mt-1">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana" style="margin-left: 5px;">{{ $nombre_espacio }}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                @if($usuarios_de_area_merco->count() > 0)
                                                                    <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_merco" data-url="{{ route('anfitrion.dashboard.ver_data_merco', ['userIds' => implode(',', $usuarios_de_area_merco->pluck('user_id')->toArray()), 'espacioId' => $espacio->espacio_id, 'areaId' => $area_id]) }}">Agendar <i class="far fa-clock"></i></a>
                                                                @else
                                                                    <a href="javascript:void(0)" class="btn btn-secondary btn-disabled" id="" disabled>Agendar</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="post-description">{{$descri_espacio}}</p>
                                                        </div>
                                                        <div class="areas d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                @foreach ($areas as $area)
                                                                    <span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">{{ $area }}</span> <!-- Cada área dentro de un <span> -->
                                                                @endforeach
                                                            </div>
                                                            {{--<span class="ms-2 text-area-espacio" style="margin-left: 5px;">{{$espacio->$area_name  }}</span>--}}
                                                        </div>
                                                        <div class="invitados mb-2">
                                                            <i class="fas fa-user-circle icon-invitados">
                                                                @if ($usuarios_de_area_merco->count() > 2)
                                                                    <strong>Invitados: </strong>
                                                                    <div class="mt-2 d-flex flex-wrap">
                                                                        @foreach ($usuarios_de_area_merco->take(2) as $usuario)
                                                                            <p class="span-invitados"> {{ $usuario->name }} | {{$usuario->area_name}}</p>
                                                                        @endforeach
                                                                        @if ($usuarios_de_area_merco->count() > 2)
                                                                            <!-- Dropdown para mostrar usuarios adicionales -->
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    Otros Invitados
                                                                                </button>
                                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                                    @foreach ($usuarios_de_area_merco->slice(2) as $usuario)
                                                                                        <a class="dropdown-item" href="#">{{ $usuario->name }} | {{$usuario->area_name}}</a>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($tipo_reunion === 'ranking')
                                                <div class="post-slide card-sesiones-pendientes">
                                                    <div class="post-content mt-1">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana" style="margin-left: 5px;">{{ $nombre_espacio }}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_ranking" data-url="{{ route('anfitrion.dashboard.ver_data_ranking', ['espacioId' => $espacio->espacio_id, 'areaId' => $area_id]) }}">Agendar <i class="far fa-clock"></i></a>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="post-description">{{$descri_espacio}}</p>
                                                        </div>
                                                        <div class="areas d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                @foreach ($areas as $area)
                                                                    <span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">{{ $area }}</span> <!-- Cada área dentro de un <span> -->
                                                                @endforeach
                                                            </div>
                                                            {{--<span class="ms-2 text-area-espacio" style="margin-left: 5px;">{{ $area_name  }}</span>--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($tipo_reunion === 'indicadores')
                                                <div class="post-slide card-sesiones-pendientes">
                                                    <div class="post-content mt-1">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana" style="margin-left: 5px;">{{ $nombre_espacio }}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                @if($usuarios_de_area_indicadores->count() > 0)
                                                                    <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_indicadores" data-url="{{ route('anfitrion.dashboard.ver_data_indicadores', ['userIds' => implode(',', $usuarios_de_area_indicadores->pluck('user_id')->toArray()), 'espacioId' => $espacio->espacio_id, 'areaId' => $area_id]) }}">Agendar <i class="far fa-clock"></i></a>
                                                                @else
                                                                    <a href="javascript:void(0)" class="btn btn-secondary btn-disabled" id="" disabled>Agendar</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="post-description">{{$descri_espacio}}</p>
                                                        </div>
                                                        <div class="areas d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                @foreach ($areas as $area)
                                                                    <span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">{{ $area }}</span> <!-- Cada área dentro de un <span> -->
                                                                @endforeach
                                                            </div>
                                                            {{--<span class="ms-2 text-area-espacio" style="margin-left: 5px;">{{ $area_name  }}</span>--}}
                                                        </div>
                                                        <div class="invitados mb-2">
                                                            <i class="fas fa-user-circle icon-invitados">
                                                                @if ($usuarios_de_area_indicadores->count() > 0)
                                                                    <strong>Invitados: </strong>
                                                                    <div class="mt-2 d-flex flex-wrap">
                                                                        @foreach ($usuarios_de_area_indicadores->take(2) as $usuario)
                                                                            <p class="span-invitados">{{ $usuario->name }} | {{$usuario->area_name}}</p>
                                                                        @endforeach
                                                                        @if ($usuarios_de_area_indicadores->count() > 2)
                                                                            <!-- Dropdown para mostrar usuarios adicionales -->
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    Otros Invitados
                                                                                </button>
                                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                                    @foreach ($usuarios_de_area_indicadores->slice(2) as $usuario)
                                                                                        <a class="dropdown-item" href="#">{{ $usuario->name }} | {{$usuario->area_name}}</a>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <strong>OBS:</strong>
                                                                    <p class="text-danger">La agenda ya fue programada</p>
                                                                @endif
                                                            </i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($tipo_reunion === 'retroalimentacion')
                                                <div class="post-slide card-sesiones-pendientes">
                                                    <div class="post-content mt-1">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana" style="margin-left: 5px;">{{ $nombre_espacio }}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="retroalimentacion_agendas" data-url="{{ route('anfitrion.dashboard.ver_data_retroalimentacion', ['espacioId' => $espacio->espacio_id, 'areaId' => $area_id]) }}">Agendar <i class="far fa-clock"></i></a>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="post-description">{{$descri_espacio}}</p>
                                                        </div>
                                                        <div class="areas d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                @foreach ($areas as $area)
                                                                    <span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">{{ $area }}</span> <!-- Cada área dentro de un <span> -->
                                                                @endforeach
                                                            </div>
                                                            {{--<span class="ms-2 text-area-espacio" style="margin-left: 5px;">{{ $area_name  }}</span>--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($tipo_reunion === 'sostenibilidad')
                                                <div class="post-slide card-sesiones-pendientes">
                                                    <div class="post-content mt-1">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana" style="margin-left: 5px;">{{ $nombre_espacio }}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                @if($usuarios_de_area_sostenibilidad->count() > 0)
                                                                    <a href="javascript:void(0)" class="btn btn-success" id="agendas_sostenibilidad" data-url="{{ route('anfitrion.dashboard.ver_data_sostenibilidad', ['userIds' => implode(',', $usuarios_de_area_sostenibilidad->pluck('user_id')->toArray()), 'espacioId' => $espacio->espacio_id, 'areaId' => $area_id]) }}">Agendar</a>
                                                                @else
                                                                    <a href="javascript:void(0)" class="btn btn-secondary" id="" disabled>Agendar</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <p class="post-description">{{$descri_espacio}}</p>
                                                        </div>
                                                        <div class="areas d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                @foreach ($areas as $area)
                                                                    <span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">{{ $area }}</span> <!-- Cada área dentro de un <span> -->
                                                                @endforeach
                                                            </div>
                                                            {{--<span class="ms-2 text-area-espacio" style="margin-left: 5px;">{{ $area_name  }}</span>--}}
                                                        </div>
                                                        <div class="invitados mb-2">
                                                            <i class="fas fa-user-circle icon-invitados">
                                                                @if ($usuarios_de_area_sostenibilidad->count() > 0)
                                                                    <strong>Invitados: </strong>
                                                                    <div class="mt-2 d-flex flex-wrap">
                                                                        @foreach ($usuarios_de_area_sostenibilidad->take(2) as $usuario)
                                                                            <p class="span-invitados" style="color: green"> {{ $usuario->name }} | {{$usuario->area_name}}</p>
                                                                        @endforeach
                                                                        @if ($usuarios_de_area_sostenibilidad->count() > 2)
                                                                            <!-- Dropdown para mostrar usuarios adicionales -->
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    Ver más
                                                                                </button>
                                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                                    @foreach ($usuarios_de_area_sostenibilidad->slice(2) as $usuario)
                                                                                        <a class="dropdown-item" href="#">{{ $usuario->name }} | {{$usuario->area_name}}</a>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <strong>OBS:</strong>
                                                                    <p class="text-danger">La agenda ya fue programada</p>
                                                                @endif
                                                            </i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div id="new-slider_part_trhee" class="owl-carousel w-100">
                                        <p class="text-danger">Aún no tiene pendiente por agendar</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('oficial.parte_modal.modal_dashboard')<!--OTO, modprogreso, subirevidencia, cerraragenda-->
    @include('oficial.parte_modal.modal_agendas')<!--Resto modal espacios-->
@stop

@section('css')
    @include('usuarios_vistas.scriptcss.css')

    {{--Estilos para la vista de dashboard lider.css--}}
    <link rel="stylesheet" href="{{asset('css/estilos_dashboard_lider.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css')}}">
    <link rel="stylesheet" href="{{asset('css/modal-save-evidencia.css')}}">
    <link rel="stylesheet" href="{{asset('css/modal_agendar_todos.css')}}">

    <style>
        :root{
            --color-ver: #007A3E;
        }
        body{
            background-color: transparent !important;
        }
        .select2-container {
            z-index: 99999 !important; /* Ajusta este valor según sea necesario */
        }
        .swal2-container {
            z-index: 2001;
        }
        .modal-progreso{
            z-index: 100000; /*1000000000000*/
        }
        .fc-event-title{
            color: var(--color-verde) !important; /* Cambia el color del texto de eventos */
        }
        .fc-event:hover {
            border: 2px solid var(--color-verde) !important;
            color: var(--color-verde) !important;
            z-index: 999999 !important;
            /*width: 200px !important;*/
            width: 200px !important;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5) !important;
        }
    </style>
@stop

@section('js')

    @include('usuarios_vistas.scriptcss.script')
    <script src="{{asset('js/fechas.js')}}"></script>

    <script type="text/javascript">
    
        $(function() {
            // Filtro de datos por mes:
            filtrarPorMes();

            // SLIDER FOR PART 3: para el carrusel de 1 solo card (sesiones cercanas)
            $("#new-slider_part_one").owlCarousel({
                items :1,
                itemsDesktop:[1199,1],
                itemsDesktopSmall:[980,1],
                itemsMobile : [600,1],
                navigation:true,
                navigationText:["",""],
                pagination:true,
                autoPlay:true
            });
            // SLIDER FOT PART 2: para carrusel de atendidas:
            $("#new-slider_part_two").owlCarousel({
                items :2,
                itemsDesktop:[1199,1],
                itemsDesktopSmall:[980,1],
                itemsMobile : [600,1],
                navigation:true,
                navigationText:["",""],
                pagination:true,
                autoPlay:true
            });
            // SLIDER FOR PART 4: para el carrusel de sesiones atendidas y pendiente de programas individuales.
            $("#new-slider_part_trhee").owlCarousel({
                items :3,
                itemsDesktop:[1199,3],
                itemsDesktopSmall:[980,2],
                itemsMobile : [600,1],
                navigation:true,
                navigationText:["",""],
                pagination:true,
                autoPlay:true
            });
            // Automatiza el tamaño de la ventana:
            ajustarAlturaModal();

            $(window).resize(function(){
                ajustarAlturaModal();
            });
        
            bloquearFechasPasadas('.fecha-hora-pasada');
        });

        function filtrarPorMes(){
            let nuevo_mes_valor = $('#mes-filtro').val();
            enviarSolicitudAjax(nuevo_mes_valor);
        }
        function enviarSolicitudAjax(mes_valor) {
            $.ajax({
                url: "{{ route('dashboard.cards') }}",
                method: "POST",
                data: {
                    mes: mes_valor
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function (data) {
                    // Manejar la respuesta del servidor aquí
                    if(data){
                        let label1 = data.usuariosAgendadosArea;
                        let label2 = data.totalUsuariosArea2;
                        let label3 = data.agendas_cumplidas;
                        let label4 = data.total_agendas;
                        let label5 = data.agendas_con_evidencia;
                        let label6 = data.total_agendas_csn_evidencia;


                        let data1HTML = (label1 && label2) ?
                            `<b class="title-body-card">${label1}</b>
                            <p class="card-text mt-4 subtitle-body-card">de ${label2}</p>` :
                            `<b class="title-body-card">0</b>
                            <p class="card-text mt-4 subtitle-body-card">de 0</p>`;

                        let data2HTML = (label3 && label4) ?
                            `<b class="title-body-card">${label3}</b>
                            <p class="card-text mt-4 subtitle-body-card">de ${label4}</p>` :
                            `<b class="title-body-card">0</b>
                            <p class="card-text mt-4 subtitle-body-card">de 0</p>`;

                        let data3HTML = (label5 && label6) ?
                            `<b class="title-body-card">${label5}</b>
                            <p class="card-text mt-4 subtitle-body-card">de ${label6}</p>` :
                            `<b class="title-body-card">0</b>
                            <p class="card-text mt-4 subtitle-body-card">de 0</p>`;

                        $('#data-2').html(data1HTML);
                        $('#data-3').html(data2HTML);
                        $('#data-4').html(data3HTML);
                    }
                },
                error: function(error) {
                    // Manejar errores aquí
                }
            });
        }
        // Bloquea las fechas anteriores a la actual
        function bloquearFechasPasadas(selector) {
            var fechaActual = new Date();
            var anio = fechaActual.getFullYear();
            var mes = ('0' + (fechaActual.getMonth() + 1)).slice(-2);
            var dia = ('0' + fechaActual.getDate()).slice(-2);
            var horas = ('0' + fechaActual.getHours()).slice(-2);
            var minutos = ('0' + fechaActual.getMinutes()).slice(-2);

            var fechaHoraActual = anio + '-' + mes + '-' + dia + 'T' + horas + ':' + minutos;

            $(selector).attr('min', fechaHoraActual);
        }
        // Actualiza altura del modal segun zoom en la ventana
        function ajustarAlturaModal() {
            var alturaVentana = $(window).height();
            $('.modal-right .modal-dialog').css('height', alturaVentana + 'px');
        }

        // Codigo par agendar evento de tipo OTO
        $(document).on('click', '.ver-datos-para-agendar', function (event) {
            event.preventDefault();

            var url = $(this).data('url');
            let mensaje= 'Envío de los datos al controller: ';

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    $('#usuario_id').text(data.usuario.id);
                    $('#usuario_name').text(data.usuario.name);
                    $('#idUserLog').val(data.usuario.id);
                    $('#user_name').val(data.usuario.name);
                    $('#user_email').val(data.usuario.email);
                    $('#id_espacio').val(data.espacio.id);
                    $('#espacio').val(data.espacio.nombre);
                    $('#descripcion_espacio').val(data.espacio.descripcion);
                    $('#id_area').val(data.area.id);
                    $('#area_name').val(data.area.nombre);
                    $('#id_corporativo').val(data.espacio.id_corporativo);

                    $('#agendarEventoModal').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        $('#formAddAgendaIndividual').submit(function(event){
            event.preventDefault();
            Swal.fire({
                title: '¿Está seguro de agregar la agenda individual?',
                iconHtml: '<img src="{{ asset('icons/icon_info.png') }}" class="icon_swal_fire">',
                showCancelButton: true,
                confirmButtonColor: '#20c997',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Confirmar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#modalProgreso').modal('show');
                    var progreso = 0;
                    $('#barraProgreso').css('width', progreso + '%').attr('aria-valuenow', progreso);
                    $('#porcentajeProgreso').text(progreso + '%');

                    $.ajax({
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('anfitrion.dashboard.agregar_evento') }}",
                        data: $('#formAddAgendaIndividual').serialize(),
                        dataType: 'json',
                        success: function(response){
                            if(response.status != 'success'){
                                $('#modalProgreso').modal('hide');
                                Swal.fire({
                                    title: '¡Agenda no agregada!',
                                    text: 'Ocurrió un percance en la creación de la agenda, reviselo con TI',
                                    iconHtml: '<img src="{{ asset('icons/icon_cancel.png') }}" class="icon_swal_fire">',
                                    showConfirmButton: true,
                                });
                            }else{
                                $('#modalProgreso').modal('hide');
                                $('#pais_error').val('');
                                Swal.fire({
                                    title: '¡Agenda agregada!',
                                    text: 'La agenda ha sido agregada correctamente a la base de datos y Google Calendar',
                                    iconHtml: '<img src="{{ asset('icons/icon_success.png') }}" class="icon_swal_fire">',
                                    showConfirmButton: true,
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#modalProgreso').modal('hide');// Progress modal
                            let errorMessage = xhr.responseJSON.error;
                            if (typeof errorMessage === 'object' && !Array.isArray(errorMessage)) {
                                errorHtml = '<ul>';
                                for (let key in errorMessage) {
                                    if (errorMessage.hasOwnProperty(key)) {
                                        if (Array.isArray(errorMessage[key])) {
                                            errorMessage[key].forEach(function(message) {
                                                errorHtml += '<li>' + message + '</li>';
                                            });
                                        } else {
                                            errorHtml += '<li>' + errorMessage[key] + '</li>';
                                        }
                                    }
                                }
                                errorHtml += '</ul>';
                            } else {
                                errorHtml = errorMessage;
                            }
                            Swal.fire({
                                title: '¡Agenda no registrada!',
                                html: '<span style="color: red;">' + errorHtml + '</span>',
                                iconHtml: '<img src="{{ asset('icons/icon_cancel.png') }}" class="icon_swal_fire">',
                                showConfirmButton: true,
                            });
                        },
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            // Procesamiento de la carga
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    // Calcula el progreso
                                    var porcentaje = Math.round((evt.loaded / evt.total) * 100);
                                    // Actualiza la barra de progreso
                                    $('#barraProgreso').css('width', porcentaje + '%').attr('aria-valuenow', porcentaje);
                                    $('#porcentajeProgreso').text(porcentaje + '%');
                                }
                            }, false);
                            return xhr;
                        }
                    });
                } else {
                    Swal.fire({
                        title: '¡Agenda no agregada!',
                        text: 'Se ha cancelado el registro de la agenda',
                        iconHtml: '<img src="{{ asset('icons/icon_cancel.png') }}" class="icon_swal_fire">',
                        showConfirmButton: true,
                    });
                }
            });
        });
    </script>
    <!--Incluye codigo para ver datos en los modales-->
    @include('oficial.parte_modal.script')

    <script>
        // CODIGO JS PARA OBTENER LOS DATOS DE UNA AGENDA Y LUEGO SUBIR LA EVIDENCIA cerrando la agenda:
        $(document).on('click', '.ver-datos-agenda-para-evidencias', function (event){
            event.preventDefault();
            var url = $(this).data('url');


            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function (data){
                    if (data.agenda) {
                        let id_agenda = data.agenda.id;
                        let fecha_hora_meet = data.agenda.fecha_hora_meet;
                        $('#id_agenda_input').text(id_agenda);
                        $('#fechaHoraMeet_agenda').text(fecha_hora_meet);
                        $('#id_agenda').val(id_agenda);

                        $('#agendaEvidenciaModal').modal('show');
                    } else {
                        console.error('La propiedad "agenda" no está definida en la respuesta JSON.');
                    }
                },
                error:function (data){
                    console.log('Error: ', data);
                }
            });
        });
        // Agregar inputs
        $('#addFileInput').off('click').on('click', function () {
            $('#fileInputsContainer').append('<div class="d-flex justify-content-between"><input type="file" name="agenda_evidencia_file[]" class="form-control col-10 mt-2 input-file-sub-soporte" required> <a class="btnQuitarFila col-2 mt-3"><i class="fas fa-times close-icon"></i></a></div>');
        });
        // Quita filas de inputs
        $('#fileInputsContainer').off('click', '.btnQuitarFila').on('click', '.btnQuitarFila', function(){
            $(this).parent().remove();
        });
        // subir la evidencia:
        $('#sendEvidenciaAgenda').off('submit').on('submit', function (event){
            event.preventDefault();

            Swal.fire({
                title: '¿Está seguro de subir la evidencia?',
                iconHtml: '<img src="{{ asset('icons/icon_info.png') }}" class="icon_swal_fire">',
                showCancelButton: true,
                confirmButtonColor: '#20c997',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Confirmar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData(this);

                    $.ajax({
                        url: "{{ route('anfitrion.dashboard.subir_evidencia') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success:function (response){
                            $('#sendEvidenciaAgenda')[0].reset();
                            $('#agendaEvidenciaModal').modal('hide');

                            Swal.fire({
                                title: '¡Evidencia enviada!',
                                text: 'El envío de la evidencia ha sido correcta',
                                iconHtml: '<img src="{{ asset('icons/icon_success.png') }}" class="icon_swal_fire">',
                                showConfirmButton: true,
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error:function (error){
                            console.log('Error al enviar la evidencia', error);
                            Swal.fire({
                                title: '¡Evidencia no enviada!',
                                text: 'Ocurrió un error al enviar la evidencia',
                                iconHtml: '<img src="{{ asset('icons/icon_cancel.png') }}" class="icon_swal_fire">',
                                showConfirmButton: true,
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: '¡Evidencia no enviada!',
                        text: 'Ocurrió un error al enviar la evidencia',
                        iconHtml: '<img src="{{ asset('icons/icon_cancel.png') }}" class="icon_swal_fire">',
                        showConfirmButton: true,
                    });
                }
            });
        });

        // Para cerrar la agenda:
        $(document).on('click', '.cerrar_agenda', function (event){
            event.preventDefault();
            var url = $(this).data('url');

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function (data){
                    if (data.agenda) {
                        let id_agenda = data.agenda.id;
                        let fechaHora = data.agenda.fecha_hora_meet;

                        let fechaHoraServidor = new Date(fechaHora);
                        let diaSemana    = fechaHoraServidor.toLocaleDateString('es-ES', { weekday: 'long' });
                        let diaMes       = fechaHoraServidor.getDate();
                        let mes          = fechaHoraServidor.toLocaleDateString('es-ES', { month: 'long' });
                        let anio         = fechaHoraServidor.getFullYear();
                        let hora         = fechaHoraServidor.toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit' });

                        // Formatear la fecha y hora
                        let fechaHoraFormateada = diaSemana + ', ' + diaMes + ' de ' + mes + ' - ' + anio + ' | ' + hora;

                        $('#id_cerrar_agenda').text(id_agenda);
                        $('#id_agenda_culminar').val(id_agenda);
                        $('#fecha_Hora_meet_culminar').text(fechaHoraFormateada);

                        var urlImagen = "{{ asset('icons/icon_info.png') }}";
                        var imagen = $('<img>');
                        imagen.attr('src', urlImagen);
                        $('#icon-info-culminar-agenda').empty().append(imagen);

                        $('#cerrarAgendaModal').modal('show');
                    } else {
                        console.error('La propiedad "agenda" no está definida en la respuesta JSON.');
                    }
                },
                error:function (data){
                    console.log('Error: ', data);
                }
            });
        });
        $('#culminarAgendaForm').off('submit').on('submit', function (event){
            event.preventDefault();

            Swal.fire({
                title: '¿Está seguro de culminar la agenda?',
                iconHtml: '<img src="{{ asset('icons/icon_info.png') }}" class="icon_swal_fire">',
                showCancelButton: true,
                confirmButtonColor: '#20c997',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Confirmar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData(this);

                    $.ajax({
                        url: "{{ route('anfitrion.culminar_agenda') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success:function (response){

                            $('#culminarAgendaForm')[0].reset();
                            $('#cerrarAgendaModal').modal('hide');
                            Swal.fire({
                                title: '¡Agenda culminada!',
                                text: 'La agenda ha sido culminada correctamente.',
                                iconHtml: '<img src="{{ asset('icons/icon_success.png') }}" class="icon_swal_fire">',
                                showConfirmButton: true,
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error:function (error){
                            console.log('Error al culminar la agenda', error);
                            Swal.fire({
                                title: '¡Agenda no culminada!',
                                text: 'Se ha producido un error al culminar la agenda',
                                iconHtml: '<img src="{{ asset('icons/icon_cancel.png') }}" class="icon_swal_fire">',
                                showConfirmButton: true,
                            });}
                    });
                } else {
                    Swal.fire({
                        title: '¡Agenda no culminada!',
                        text: 'Se ha cancelado la culminacion de la agenda',
                        iconHtml: '<img src="{{ asset('icons/icon_cancel.png') }}" class="icon_swal_fire">',
                        showConfirmButton: true,
                    });
                }
            });
        });
    </script>

@stop
