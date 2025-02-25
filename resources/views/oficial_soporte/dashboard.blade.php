@extends('adminlte::page')

@section('title', 'Dashboard | Espacios -> Líder')

@section('content_header')
@stop
@section('content')
    <div class="py-12 mt-2 container col-lg-12 col-md-12 col-sm-12 pb-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="container pt-2" >
                    <div class="cabecera cabecera-dash d-flex justify-content-between align-items-center mb-2">
                        <div class="username-text">
                            <p>¡Bienvenid@ {{$username}} !</p>
                        </div>
                        <div class="input-text">
                            <input type="month" id="mes-filtro" name="mes-filtro" class="input-fecha-cabecera">
                        </div>
                    </div>

                    {{--Contenido parte 1--}}
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
                                <strong class="card-title d-flex" id="data-2">
                                </strong>
                            </div>
                        </div>
                        <div class="card col-2 content-cards ml-2 third-card">
                            <div class="card-body" id="contenido_tercer_kpi">
                                <div class="title-card-counts">
                                    <h5 class="title-card-counts">Atendidas</h5>
                                    <i class="fas fa-comment icon-cards-counts"></i>
                                </div>
                                <strong class="card-title d-flex" id="data-3">
                                </strong>
                            </div>
                        </div>
                        <div class="card col-2 content-cards ml-2 fourt-card">
                            <div class="card-body" id="contenido_cuarto_kpi">
                                <div class="title-card-counts">
                                    <h5 class="title-card-counts">Cerradas</h5>
                                    <i class="fas fa-calendar-check icon-cards-counts"></i>
                                </div>
                                <strong class="card-title d-flex" id="data-4">
                                </strong>
                            </div>
                        </div>
                    </div>

                    {{--Contenido parte 2--}}
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
                                                                {{--<p class="ms-2">{{ $data->fecha_hora }}</p>--}}
                                                            </div>
                                                            <div class="areas d-flex">
                                                                <strong class="ms-2 text-area-cercana">Area: </strong>
                                                                <p class="ms-2 text-area-espacio">{{ $data->area_name }}</p>
                                                            </div>
                                                            <div class="contenedor-slider-cercanas d-flex justify-content-between mb-4">
                                                                <div class="invitados d-flex flex-wrap">
                                                                    {{--<i class="fas fa-user-circle icon-invitados"></i>
                                                                    <span class="text-success ml-1 rounded span-invitados">{{ $data->invitados }}</span>--}}
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

                    {{--Contenido de la parte 3: Sesiones atendidas pero que faltan subir las evidencias de la sesion -> 1: atendidas--}}
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

                    {{--Contenido de la parte 4: Sesiones pendientes por agendar--------------------------------------------------}}
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

    {{-- Inicio del contenido de calendario del modal ---}}
    <div class="modal fade" id="verMisAgendasModal" tabindex="-1" aria-labelledby="verMisAgendasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="verMisAgendasModalLabel">Vista del calendario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modal_calendario_google" style="height: 800px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Inicio del contenido para agregar eventos individuales OTO--}}
    <div class="modal fade modal-right" id="agendarEventoModal" tabindex="-1" aria-labelledby="agendarEventoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-contenido-agendar modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
            <form class="saveAgendaIndividual" id="formAddAgendaIndividual">
                @csrf
                <div class="modal-content modal-content-agendar justify-content-center">
                    <div class="modal-header bg-secondary modal-header-agendar">
                        <h1 class="modal-title fs-5" id="agendarEventoModalLabel">Agendar evento para el usuario: <span id="usuario_id"></span> | <span id="usuario_name"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-content-agendar">
                        <div class="form-group d-flex justify-content-center">
                            <div class="col-5">
                                <input type="hidden" class="form-control" name="id_user" id="idUserLog">
                                <input type="hidden" class="form-control" name="user_name" id="user_name">
                                <input type="hidden" name="user_email" id="user_email" class="form-control">
                                <input type="hidden" class="form-control" name="id_espacio" id="id_espacio">
                                <input type="hidden" class="form-control" name="espacio" id="espacio">
                            </div>
                            <div class="col-5">
                                <input type="hidden" class="form-control" name="descripcion_espacio" id="descripcion_espacio">
                                <input type="hidden" class="form-control" name="id_area" id="id_area">
                                <input type="hidden" class="form-control" name="area_name" id="area_name">
                                <input type="hidden" class="form-control" name="id_corporativo" id="id_corporativo">
                            </div>
                        </div>
                        <div class="">
                            <div id="calendario-modal-google" style="height: 900px;"></div>
                        </div>
                        <div class="d-flex justify-content-around mt-3 mb-1 form-group">
                            <div class="col-6">
                                <select name="location" id="location" class="select-two rounded col-12 evento-time">
                                    <option value="">Tipo de evento</option>
                                    <option value="Presencial - Oficina principal">Presencial</option>
                                    <option value="Virtual - Meet">Virtual</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <input type="datetime-local" class="fecha-hora-pasada rounded time-modal" name="fecha_hora_meet" id="fecha_hora_meet">
                            </div>
                        </div>
                        <div>
                            <span class="error_fecha_hora_meet text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-success btn-agendar-evento">Agendar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('oficial.parte_modal.modal_dashboard')

    {{--******************************************************************************************************************************************************************--}}
    {{-- Modal para ver el proceso de carga de la creacion de los eventos --}}
    <div class="modal fade modal-progreso" id="modalProgreso" tabindex="-1" role="dialog" aria-labelledby="modalProgresoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProgresoLabel">Generando agenda...</h5>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div id="barraProgreso" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="porcentajeProgreso" class="mt-2 text-center">0%</div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal para ver los datos de la agenda y subir evidencia --}}
    <div class="modal fade modal-right" id="agendaEvidenciaModal" tabindex="-1" aria-labelledby="agendaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-contenido modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
            <form method="post" class="sendEvidenciaAgenda" id="sendEvidenciaAgenda" enctype="multipart/form-data">
                @csrf
                <div class="modal-content modal-content-evidencia">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="agendaModalLabel">Detalle de la agenda con ID: <span id="id_agenda_input" class="ml-2"></span> - <span id="fechaHoraMeet_agenda" class="ml-2"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_agenda" id="id_agenda">
                        <div class="row d-flex w-100 justify-content-center">
                            <label>Ingresa el nombre para los archivos</label>
                            <input type="text" class="form-control" name="nombre_archivo" id="nombre_archivo">
                        </div>
                        <div class="row d-flex w-100">
                            <label>Agregar evidencias (archivos)</label>
                            <div class="d-flex">
                                <input type="file" name="agenda_evidencia_file[]" id="agenda_evidencia_file" class="form-control input-file-sub-soporte" required>
                            </div>

                            <div id="fileInputsContainer">
                            </div>
                            <a type="button" id="addFileInput" class="add_files ml-2 mt-2">Agregar más......</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-success button-evidencia">Subir evidencia <i class="fas fa-file ml-2" style="font-size: 20px; font-weight: bold;"></i> </button>
                        <button type="button" class="btn btn-outline-light btn-modal-cancel button-evidencia" data-bs-dismiss="modal">Cerrar <i class="fas fa-times ml-2" style="font-size: 20px; font-weight: bold;" ></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- Modal para ver los datos de la agenda para culminar --}}
    <div class="modal fade modal-right" id="cerrarAgendaModal" tabindex="-1" aria-labelledby="agendaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-contenido modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
            <form method="post" class="culminarAgendaForm" id="culminarAgendaForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content modal-content-evidencia">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="agendaModalLabel">Detalle de la agenda con ID: <span id="id_cerrar_agenda" class="ml-2"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_agenda" id="id_agenda_culminar">
                        <div class="d-flex justify-content-center">
                            <div id="icon-info-culminar-agenda"></div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <p>¿Está segur@ de culminar la agenda?</p>
                        </div>
                        <div class="d-flex justify-content-center align-content-center align-items-center">
                            <div id="fecha_Hora_meet_culminar"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-success button-evidencia">Culminar agenda</button>
                        <button type="button" class="btn btn-outline-light btn-modal-cancel button-evidencia" data-bs-dismiss="modal">Cerrar <i class="fas fa-times ml-2" style="font-size: 20px; font-weight: bold;" ></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    @include('usuarios_vistas.scriptcss.css')

    {{--Estilos para la vista de dashboard lider.css--}}
    <link rel="stylesheet" href="{{asset('css/estilos_dashboard_lider.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css')}}">
    <link rel="stylesheet" href="{{asset('css/modal-save-evidencia.css')}}">
    <link rel="stylesheet" href="{{asset('css/modal_agendar_todos.css')}}">

    <style>
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
            color: green !important; /* Cambia el color del texto de eventos */
        }
        .fc-event:hover {
            border: 2px solid green !important;
            color: green !important;
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

    <script>
        $(function() {
            // SLIDER FOR PART 3: para el carrusel de 1 solo card (sesiones cercanas)
            $("#new-slider_part_one").owlCarousel({
                items :1, // Cantidad de cards por pantalla en dispositivo grande
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
                items :2, // Cantidad de cards por pantalla en dispositivo
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
                items :3, // 3 Cantidad de cards por pantalla en dispositivo
                itemsDesktop:[1199,3], // 3
                itemsDesktopSmall:[980,2],
                itemsMobile : [600,1],
                navigation:true,
                navigationText:["",""],
                pagination:true,
                autoPlay:true
            });

            // Función para ajustar la altura del modal al cargar la página
            function ajustarAlturaModal() {
                var alturaVentana = $(window).height();
                
                $('.modal-right .modal-dialog').css('height', alturaVentana + 'px');
            }
            // Llamar a la función para ajustar la altura del modal cuando se cargue la página
            ajustarAlturaModal();
            // Llamar a la función para ajustar la altura del modal cuando se redimensione la ventana
            $(window).resize(function(){
                ajustarAlturaModal();
            });

            // Bloquea las fechas anteriores a la actual
            function bloquearFechasPasadas(selector) {
                var fechaActual = new Date();
                var anio = fechaActual.getFullYear();
                var mes = ('0' + (fechaActual.getMonth() + 1)).slice(-2); // Agrega un cero delante y toma los últimos dos caracteres
                var dia = ('0' + fechaActual.getDate()).slice(-2); // Agrega un cero delante y toma los últimos dos caracteres
                var horas = ('0' + fechaActual.getHours()).slice(-2); // Agrega un cero delante y toma los últimos dos caracteres
                var minutos = ('0' + fechaActual.getMinutes()).slice(-2); // Agrega un cero delante y toma los últimos dos caracteres

                var fechaHoraActual = anio + '-' + mes + '-' + dia + 'T' + horas + ':' + minutos;

                $(selector).attr('min', fechaHoraActual);
            }

            bloquearFechasPasadas('.fecha-hora-pasada');
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Función para inicializar el calendario
            function initCalendar(calendarId) {
                var modalCalendarEvent = document.getElementById(calendarId);
                var modalCalendar = new FullCalendar.Calendar(modalCalendarEvent, {
                    locale: 'es', // Configura el idioma español
                    initialView: 'dayGridMonth',
                    events: [
                        @foreach ($events as $event)
                        {
                            title: '{{ $event->summary }}',
                            start: '{{ $event->start->dateTime }}',
                            end: '{{ $event->end->dateTime }}',
                            color: 'green',
                        },
                        @endforeach
                    ],
                    locales: 'es',
                    locale: 'es',
                    buttonText: {
                        today: 'Hoy'
                    },
                    viewDidMount: function(view) {
                        var monthHeader = document.querySelector('.fc-toolbar-title');
                        var monthHeaderText = monthHeader.textContent;
                        var capitalizedMonthHeaderText = monthHeaderText.charAt(0).toUpperCase() + monthHeaderText.slice(1);
                        monthHeader.textContent = capitalizedMonthHeaderText;
                    }
                });

                // Renderiza el calendario cuando se muestre el modal correspondiente
                $('#' + calendarId).closest('.modal').on('shown.bs.modal', function () {
                    modalCalendar.render();
                });
            }

            // Inicializa el calendario para cada ID
            initCalendar('calendario-modal-max10');
            initCalendar('calendario-modal-pares');
            initCalendar('calendario-modal-ranking');
            initCalendar('calendario-modal-retroalimentacion');
            initCalendar('calendario-modal-google');
            initCalendar('calendario-modal-agendas');
            initCalendar('calendario-modal-country');
            initCalendar('calendario-modal-primario');
            initCalendar('calendario-modal-compras');
            initCalendar('calendario-modal-merco');
            initCalendar('calendario-modal-indicadores');
            initCalendar('calendario-modal-sostenibilidad');


            // Inicializa el calendario principal si es necesario (no proporcionaste el ID)
            var calendarEl = document.getElementById('calendar');
            if (calendarEl) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: 'es',
                    initialView: 'dayGridMonth',
                });
                calendar.render();
            }
        });


        // Agenda idividual
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

                    /*PRUEBA PARA VER EL PROGESO DE LA GENERACION DE EVENTOS EN GOOGLE CALENDAR Y GMAIL*/
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
                            // $('#formAddAgendaIndividual')[0].reset();
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

        /************************************************************************************************************/
        /********* CODIGO PARA VER DATOS Y AGREGAR EVENTOS PARA: pares, max 10, ranking, retroalimentacion******** */
        /******* MAX 10 -> Big con el equipo *******/
        $(document).on('click', '#agendas_max_10', function (event) {
            event.preventDefault();
            var url     = $(this).data('url');
            let mensaje = 'Envío de los datos al controller de MAX 10';
            
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    
                    $('#espacio_name').text(data.espacio.nombre);
                    // $('#area_name').text(data.area.nombre);
                    $('#id_espacio_grupal').val(data.espacio.id);
                    $('#espacio_grupal').val(data.espacio.nombre);
                    $('#desc_esp_grupal').val(data.espacio.descripcion);
                    $('#id_area_grupal').val(data.area.id);
                    $('#area_name_grupal').val(data.area.nombre);
                    $('#id_corporativo_grupal').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectmax10" class="w-100" style="width: auto; ">';
                        areasUserHtml += '<option value="">Seleccionar área...</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_max_10').html(areasUserHtml);

                    $('#selectmax10').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalGrupal').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /******* Pares -> Café con pares *******/
        $(document).on('click', '#agendas_pares', function (event) {
            event.preventDefault();
            var url     = $(this).data('url');
            let mensaje = 'Envío de los datos al controller de PARES';
            
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    
                    $('#espacio_pares').text(data.espacio.nombre);
                    $('#id_espacio_pares').val(data.espacio.id);
                    $('#espacio_pares_name').val(data.espacio.nombre);
                    $('#desc_esp_pares').val(data.espacio.descripcion);
                    $('#id_area_pares').val(data.area.id);
                    $('#area_name_pares').val(data.area.nombre);
                    $('#id_corporativo_pares').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectpares" class="w-100" style="width: auto; ">';
                        areasUserHtml += '<option value="">Seleccionar área...</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_pares').html(areasUserHtml);

                    $('#selectpares').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalPares').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /******* Ranking -> RANKING *******/
        $(document).on('click', '#agendas_ranking', function (event) {
            event.preventDefault();
            var url     = $(this).data('url');
            let mensaje = 'Envío de los datos al controller de RANKING';
            
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    
                    $('#espacio_ranking').text(data.espacio.nombre);
                    // $('#area_ranking').text(data.area.nombre);
                    $('#id_espacio_ranking').val(data.espacio.id);
                    $('#espacio_ranking_name').val(data.espacio.nombre);
                    $('#desc_esp_ranking').val(data.espacio.descripcion);
                    $('#id_area_ranking').val(data.area.id);
                    $('#area_name_ranking').val(data.area.nombre);
                    $('#id_corporativo_ranking').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectranking" class="w-100" style="width: auto; ">';
                        areasUserHtml += '<option value="">Seleccionar área...</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_ranking').html(areasUserHtml);

                    $('#selectranking').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalRanking').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /******* Retroalimentacion -> Retroalimentación y Acompañamiento *******/
        $(document).on('click', '#retroalimentacion_agendas', function (event) {
            event.preventDefault();
            var url     = $(this).data('url');
            let mensaje = 'Envío de los datos al controller de RETROALIMENTACION';
            
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    
                    $('#espacio_retroalimentacion').text(data.espacio.nombre);
                    // $('#area_retroalimentacion').text(data.area.nombre);
                    $('#id_espacio_retroalimentacion').val(data.espacio.id);
                    $('#espacio_retroalimentacion_name').val(data.espacio.nombre);
                    $('#desc_esp_retroalimentacion').val(data.espacio.descripcion);
                    $('#id_area_retroalimentacion').val(data.area.id);
                    $('#area_name_retroalimentacion').val(data.area.nombre);
                    $('#id_corporativo_retroalimentacion').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectretroalimentacion" class="w-100" style="width: auto; ">';
                        areasUserHtml += '<option value="">Seleccionar área...</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_retroalimentacion').html(areasUserHtml);

                    $('#selectretroalimentacion').select2({
                        theme: "classic"
                    });


                    $('#agendarEventoModalRetroalimentacion').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });

        $('#formAddAgendaGrupalSeleccionUsuarios').submit(function(event) {
            agregarAgendaSeleccionUsuarios('formAddAgendaGrupalSeleccionUsuarios');
        });
        $('#formAddAgendaPares').submit(function(event) {
            agregarAgendaSeleccionUsuarios('formAddAgendaPares');
        });
        $('#formAddAgendaRanking').submit(function(event) {
            agregarAgendaSeleccionUsuarios('formAddAgendaRanking');
        });
        $('#formAddAgendaRetroalimentacion').submit(function(event) {
            agregarAgendaSeleccionUsuarios('formAddAgendaRetroalimentacion');
        });

        /************************************************************************************************************/
        /*Country -> Haciendo posible lo imposible*/
        $(document).on('click', '#country_agendas', function (event) {
            event.preventDefault();
            var url     = $(this).data('url');
            let mensaje = 'Envío de los datos al controller de COUNTRY';
            
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    
                    $('#espacio_country').text(data.espacio.nombre);
                    // $('#area_country').text(data.area.nombre);
                    $('#id_espacio_country').val(data.espacio.id);
                    $('#espacio_country_name').val(data.espacio.nombre);
                    $('#desc_esp_country').val(data.espacio.descripcion);
                    $('#id_area_country').val(data.area.id);
                    $('#area_name_country').val(data.area.nombre);
                    $('#id_corporativo_country').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectcountry" class="w-100" style="width: auto; ">';
                        areasUserHtml += '<option value="">Seleccionar área...</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_country').html(areasUserHtml);

                    $('#selectcountry').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalCountry').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /*Primario -> Grupo primario*/
        $(document).on('click', '#agendas_primario', function(){
            event.preventDefault();
            var url     = $(this).data('url');
            let mensaje = 'Envío de los datos al controller de PRIMARIO';
            
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    
                    $('#espacio_primario').text(data.espacio.nombre);
                    //$('#area_primario').text(data.area.nombre);
                    $('#id_espacio_primario').val(data.espacio.id);
                    $('#espacio_primario_name').val(data.espacio.nombre);
                    $('#desc_esp_primario').val(data.espacio.descripcion);
                    $('#id_area_primario').val(data.area.id);
                    $('#area_name_primario').val(data.area.nombre);
                    $('#id_corporativo_primario').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectprimario" class="w-100" style="width: auto; ">';
                        areasUserHtml += '<option value="">Seleccionar área...</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_primario').html(areasUserHtml);

                    $('#selectprimario').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalPrimario').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /*Compras -> Comité de compras*/
        $(document).on('click', '#agendas_compras', function(){
            event.preventDefault();
            var url     = $(this).data('url');
            let mensaje = 'Envío de los datos al controller de PRIMARIO';
            
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    
                    $('#espacio_compras').text(data.espacio.nombre);
                    // $('#area_compras').text(data.area.nombre);
                    $('#id_espacio_compras').val(data.espacio.id);
                    $('#espacio_compras_name').val(data.espacio.nombre);
                    $('#desc_esp_compras').val(data.espacio.descripcion);
                    $('#id_area_compras').val(data.area.id);
                    $('#area_name_compras').val(data.area.nombre);
                    $('#id_corporativo_compras').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectcompras" class="w-100" style="width: auto; ">';
                        areasUserHtml += '<option value="">Seleccionar área...</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_compras').html(areasUserHtml);

                    $('#selectcompras').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalCompras').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /*Merco -> Merco*/
        $(document).on('click', '#agendas_merco', function(){
            event.preventDefault();
            var url     = $(this).data('url');
            let mensaje = 'Envío de los datos al controller de PRIMARIO';
            
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    
                    $('#espacio_merco').text(data.espacio.nombre);
                    // $('#area_merco').text(data.area.nombre);
                    $('#id_espacio_merco').val(data.espacio.id);
                    $('#espacio_merco_name').val(data.espacio.nombre);
                    $('#desc_esp_merco').val(data.espacio.descripcion);
                    $('#id_area_merco').val(data.area.id);
                    $('#area_name_merco').val(data.area.nombre);
                    $('#id_corporativo_merco').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectmerco" class="w-100" style="width: auto; ">';
                        areasUserHtml += '<option value="">Seleccionar área...</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_merco').html(areasUserHtml);

                    $('#selectmerco').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalMerco').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /*indicadores -> dia de indicadores*/
        $(document).on('click', '#agendas_indicadores', function(){
            event.preventDefault();
            var url     = $(this).data('url');
            let mensaje = 'Envío de los datos al controller de PRIMARIO';
            
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    
                    $('#espacio_indicadores').text(data.espacio.nombre);
                    // $('#area_indicadores').text(data.area.nombre);
                    $('#id_espacio_indicadores').val(data.espacio.id);
                    $('#espacio_indicadores_name').val(data.espacio.nombre);
                    $('#desc_esp_indicadores').val(data.espacio.descripcion);
                    $('#id_area_indicadores').val(data.area.id);
                    $('#area_name_indicadores').val(data.area.nombre);
                    $('#id_corporativo_indicadores').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectindicadores" class="w-100" style="width: auto; ">';
                        areasUserHtml += '<option value="">Seleccionar área...</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_indicadores').html(areasUserHtml);

                    $('#selectindicadores').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalIndicadores').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /*Sostenibilidad -> Comité de sostenibilidad*/
        $(document).on('click', '#agendas_sostenibilidad', function(){
            event.preventDefault();
            var url     = $(this).data('url');
            let mensaje = 'Envío de los datos al controller de PRIMARIO';
            
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    
                    $('#espacio_sostenibilidad').text(data.espacio.nombre);
                    // $('#area_sostenibilidad').text(data.area.nombre);
                    $('#id_espacio_sostenibilidad').val(data.espacio.id);
                    $('#espacio_sostenibilidad_name').val(data.espacio.nombre);
                    $('#desc_esp_sostenibilidad').val(data.espacio.descripcion);
                    $('#id_area_sostenibilidad').val(data.area.id);
                    $('#area_name_sostenibilidad').val(data.area.nombre);
                    $('#id_corporativo_sostenibilidad').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectsostenibilidad" class="w-100" style="width: auto; ">';
                        areasUserHtml += '<option value="">Seleccionar área...</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_sostenibilidad').html(areasUserHtml);

                    $('#selectsostenibilidad').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalSostenibilidad').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });

        $('#formAddAgendaPrimario').submit(function(event) {
            agregarAgendaGrupalArea('formAddAgendaPrimario');
        });
        $('#formAddAgendaCountry').submit(function(event) {
            agregarAgendaGrupalArea('formAddAgendaCountry');
        });
        $('#formAddAgendaCompras').submit(function(event) {
            agregarAgendaGrupalArea('formAddAgendaCompras');
        });
        $('#formAddAgendaMerco').submit(function(event) {
            agregarAgendaGrupalArea('formAddAgendaMerco');
        });
        $('#formAddAgendaIndicadores').submit(function(event) {
            agregarAgendaGrupalArea('formAddAgendaIndicadores');
        });
        $('#formAddAgendaSostenibilidad').submit(function(event) {
            agregarAgendaGrupalArea('formAddAgendaSostenibilidad');
        });
        /************************************************************************************************************/
        function agregarAgendaSeleccionUsuarios(formId) {

            event.preventDefault();
            console.log('Envío de form al controller');
            Swal.fire({
                title: '¿Está seguro de agregar la agenda grupal?',
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
                    console.log('form enviado');
                    $.ajax({
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('anfitrion.dashboard.agregar_evento_seleccion_usuarios') }}",
                        data: $('#' + formId).serialize(),
                         dataType: 'json',
                        success: function(response, xhr){
                            $('#modalProgreso').modal('hide');
                            Swal.fire({
                                title: '¡Agenda agregada!',
                                text: 'La agenda ha sido agregada correctamente a la base de datos y Google Calendar',
                                iconHtml: '<img src="{{ asset('icons/icon_success.png') }}" class="icon_swal_fire">',
                                showConfirmButton: true,
                            }).then(() => {
                                location.reload();
                            });
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
        }
        function agregarAgendaGrupalArea(formId) {
            event.preventDefault();
            Swal.fire({
                title: '¿Está seguro de agregar la agenda grupal?',
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
                        url: "{{ route('evento.dashboard.agregar_evento_grupal_area') }}",
                        data: $('#' + formId).serialize(),
                        dataType: 'json',
                        success: function(response){
                            // $('#formAddAgendaIndividual')[0].reset();
                            $('#modalProgreso').modal('hide');
                            Swal.fire({
                                title: '¡Agenda agregada!',
                                text: 'La agenda ha sido agregada correctamente a la base de datos y Google Calendar',
                                iconHtml: '<img src="{{ asset('icons/icon_success.png') }}" class="icon_swal_fire">',
                                showConfirmButton: true,
                            }).then(() => {
                                location.reload();
                            });

                        },
                        error: function(xhr, status, error) {
                            $('#modalProgreso').modal('hide');
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
        }
        /******************************************************************************************************************************/
        // Busqueda de usuarios por areas:
        // Para ,ax10| pares | ranking | retroalimentacion
        $(document).on('change', '#selectmax10', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_grupal').val();

            
            if (area_id !== "") {

                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        
                        let usuariosHtmlItem = '';
                        let column1 = '';
                        let column2 = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '">' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                            // Agregar cada registro a una columna alternativa
                            if (index % 2 === 0) {
                                column1 += usuariosHtmlItem;
                            } else {
                                column2 += usuariosHtmlItem;
                            }
                            // Reiniciar la variable para el próximo elemento
                            usuariosHtmlItem = '';
                        });

                        // Combinar las dos columnas en una sola cadena HTML
                        let usuariosHtml = '<div style="display: flex;">' +
                            '<div style="flex: 1;">' + column1 + '</div>' +
                            '<div style="flex: 1;">' + column2 + '</div>' +
                            '</div>';
                        $('#lista_usuarios_grupales').html(usuariosHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectpares', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_pares').val();

            
            if (area_id !== "") {

                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        
                        let usuariosHtmlItem = '';
                        let column1 = '';
                        let column2 = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '">' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                            // Agregar cada registro a una columna alternativa
                            if (index % 2 === 0) {
                                column1 += usuariosHtmlItem;
                            } else {
                                column2 += usuariosHtmlItem;
                            }
                            // Reiniciar la variable para el próximo elemento
                            usuariosHtmlItem = '';
                        });

                        // Combinar las dos columnas en una sola cadena HTML
                        let usuariosHtml = '<div style="display: flex;">' +
                            '<div style="flex: 1;">' + column1 + '</div>' +
                            '<div style="flex: 1;">' + column2 + '</div>' +
                            '</div>';
                        $('#lista_usuarios_pares').html(usuariosHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectranking', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_ranking').val();

            
            if (area_id !== "") {

                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        
                        let usuariosHtmlItem = '';
                        let column1 = '';
                        let column2 = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '">' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                            // Agregar cada registro a una columna alternativa
                            if (index % 2 === 0) {
                                column1 += usuariosHtmlItem;
                            } else {
                                column2 += usuariosHtmlItem;
                            }
                            // Reiniciar la variable para el próximo elemento
                            usuariosHtmlItem = '';
                        });

                        // Combinar las dos columnas en una sola cadena HTML
                        let usuariosHtml = '<div style="display: flex;">' +
                            '<div style="flex: 1;">' + column1 + '</div>' +
                            '<div style="flex: 1;">' + column2 + '</div>' +
                            '</div>';
                        $('#lista_usuarios_ranking').html(usuariosHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectretroalimentacion', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_retroalimentacion').val();

            
            if (area_id !== "") {

                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        
                        let usuariosHtmlItem = '';
                        let column1 = '';
                        let column2 = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '">' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                            // Agregar cada registro a una columna alternativa
                            if (index % 2 === 0) {
                                column1 += usuariosHtmlItem;
                            } else {
                                column2 += usuariosHtmlItem;
                            }
                            // Reiniciar la variable para el próximo elemento
                            usuariosHtmlItem = '';
                        });

                        // Combinar las dos columnas en una sola cadena HTML
                        let usuariosHtml = '<div style="display: flex;">' +
                            '<div style="flex: 1;">' + column1 + '</div>' +
                            '<div style="flex: 1;">' + column2 + '</div>' +
                            '</div>';
                        $('#lista_usuarios_retroalimentacion').html(usuariosHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        // primario | pares | compras | merco | indicadores | sostenibilidad
        $(document).on('change', '#selectprimario', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_primario').val();

            
            if (area_id !== "") {

                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        
                        let usuariosHtmlItem = '';
                        let column1 = '';
                        let column2 = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '" checked>' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                            // Agregar cada registro a una columna alternativa
                            if (index % 2 === 0) {
                                column1 += usuariosHtmlItem;
                            } else {
                                column2 += usuariosHtmlItem;
                            }
                            // Reiniciar la variable para el próximo elemento
                            usuariosHtmlItem = '';
                        });

                        // Combinar las dos columnas en una sola cadena HTML
                        let usuariosHtml = '<div style="display: flex;">' +
                            '<div style="flex: 1;">' + column1 + '</div>' +
                            '<div style="flex: 1;">' + column2 + '</div>' +
                            '</div>';
                        $('#lista_usuarios_primario').html(usuariosHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectcountry', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_country').val();

            
            if (area_id !== "") {

                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        
                        let usuariosHtmlItem = '';
                        let column1 = '';
                        let column2 = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '" checked readonly>' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                            // Agregar cada registro a una columna alternativa
                            if (index % 2 === 0) {
                                column1 += usuariosHtmlItem;
                            } else {
                                column2 += usuariosHtmlItem;
                            }
                            // Reiniciar la variable para el próximo elemento
                            usuariosHtmlItem = '';
                        });

                        // Combinar las dos columnas en una sola cadena HTML
                        let usuariosHtml = '<div style="display: flex;">' +
                            '<div style="flex: 1;">' + column1 + '</div>' +
                            '<div style="flex: 1;">' + column2 + '</div>' +
                            '</div>';
                        $('#lista_usuarios_country').html(usuariosHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectcompras', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_compras').val();

            
            if (area_id !== "") {

                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        
                        let usuariosHtmlItem = '';
                        let column1 = '';
                        let column2 = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '" checked readonly>' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                            // Agregar cada registro a una columna alternativa
                            if (index % 2 === 0) {
                                column1 += usuariosHtmlItem;
                            } else {
                                column2 += usuariosHtmlItem;
                            }
                            // Reiniciar la variable para el próximo elemento
                            usuariosHtmlItem = '';
                        });

                        // Combinar las dos columnas en una sola cadena HTML
                        let usuariosHtml = '<div style="display: flex;">' +
                            '<div style="flex: 1;">' + column1 + '</div>' +
                            '<div style="flex: 1;">' + column2 + '</div>' +
                            '</div>';
                        $('#lista_usuarios_compras').html(usuariosHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectmerco', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_merco').val();

            
            if (area_id !== "") {

                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        
                        let usuariosHtmlItem = '';
                        let column1 = '';
                        let column2 = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '" checked readonly>' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                            // Agregar cada registro a una columna alternativa
                            if (index % 2 === 0) {
                                column1 += usuariosHtmlItem;
                            } else {
                                column2 += usuariosHtmlItem;
                            }
                            // Reiniciar la variable para el próximo elemento
                            usuariosHtmlItem = '';
                        });

                        // Combinar las dos columnas en una sola cadena HTML
                        let usuariosHtml = '<div style="display: flex;">' +
                            '<div style="flex: 1;">' + column1 + '</div>' +
                            '<div style="flex: 1;">' + column2 + '</div>' +
                            '</div>';
                        $('#lista_usuarios_merco').html(usuariosHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectindicadores', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_indicadores').val();

            
            if (area_id !== "") {

                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        
                        let usuariosHtmlItem = '';
                        let column1 = '';
                        let column2 = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '" checked readonly>' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                            // Agregar cada registro a una columna alternativa
                            if (index % 2 === 0) {
                                column1 += usuariosHtmlItem;
                            } else {
                                column2 += usuariosHtmlItem;
                            }
                            // Reiniciar la variable para el próximo elemento
                            usuariosHtmlItem = '';
                        });

                        // Combinar las dos columnas en una sola cadena HTML
                        let usuariosHtml = '<div style="display: flex;">' +
                            '<div style="flex: 1;">' + column1 + '</div>' +
                            '<div style="flex: 1;">' + column2 + '</div>' +
                            '</div>';
                        $('#lista_usuarios_indicadores').html(usuariosHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectsostenibilidad', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_sostenibilidad').val();

            
            if (area_id !== "") {

                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        
                        let usuariosHtmlItem = '';
                        let column1 = '';
                        let column2 = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '" checked readonly>' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                            // Agregar cada registro a una columna alternativa
                            if (index % 2 === 0) {
                                column1 += usuariosHtmlItem;
                            } else {
                                column2 += usuariosHtmlItem;
                            }
                            // Reiniciar la variable para el próximo elemento
                            usuariosHtmlItem = '';
                        });

                        // Combinar las dos columnas en una sola cadena HTML
                        let usuariosHtml = '<div style="display: flex;">' +
                            '<div style="flex: 1;">' + column1 + '</div>' +
                            '<div style="flex: 1;">' + column2 + '</div>' +
                            '</div>';
                        $('#lista_usuarios_sostenibilidad').html(usuariosHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });

        /******************************************************************************************************************************/
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

        /*Para el filtro de las 3 cards por mes con ajax*/
        $(document).ready(function() {
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

            // Obtener el valor por defecto del input
            let mes_valor = $('#mes-filtro').val();

            // Llamar a la función para realizar la solicitud AJAX con el valor por defecto
            enviarSolicitudAjax(mes_valor);

            // Manejar el evento de cambio del input #mes-filtro
            $('#mes-filtro').on('change', function() {
                let nuevo_mes_valor = $(this).val();
                enviarSolicitudAjax(nuevo_mes_valor);
            });

            // Limpieza de modal de evidencias
            $('#agendaEvidenciaModal').on('hidden.bs.modal', function () {
                $('#nombre_archivo').val('');
                $('#agenda_evidencia_file').val('');
                $('#fileInputsContainer').empty();
            });
        });
    </script>

@stop

