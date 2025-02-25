@extends('adminlte::page')

@section('title', ' Admin agendas lider')

@section('content_header')
@stop

@section('content')
    <main class="container-fluid contenedor-container col-lg-12 col-sm-12 w-100 pt-2 d-flex contenedor-pagina-principal">
        <section class="col-lg-9 col-sm-12 justify-content-center mt-2 second-content-cards contenido-de-cards">
            {{--Contenido de buttons para filtrar--}}
            <strong>
                {{$tipo}}
            </strong>
            <div class="container-fluid row w-100 d-flex justify-content-around contenedor-buttos-spaces">
                <div class="col-12 d-flex buttons-agendas-filtros justify-content-around container-buttons-tabs-filtros">
                    <button type="button" class="btn mr-1 buttons-select" name="todas" id="todas">Todas</button>
                    <button type="button" class="btn mr-1 buttons-select" name="agendadas" id="agendadas">Agendadas</button>
                    <button type="button" class="btn mr-1 buttons-select" name="atendidas" id="atendidas">Atendidas</button>
                    <button type="button" class="btn mr-1 buttons-select" name="concluidas" id="concluidas">Concluidas</button>
                    <button type="button" class="btn mr-1 buttons-select" name="pendientes" id="pendientes">Pendientes por agendar</button>
                </div>
            </div>
            {{--Contenido de inputs para el filtro con ajax--}}
            <div class="container-fluid row w-100 d-flex mt-2 mb-2 justify-content-around content-filtros-select">
                <div class="col-12 d-flex buttons-agendas-filtros justify-content-around">
                    <select name="area" id="area_filtro" class="content-filtros-select select_2_view w-100 inputs-de-filtro">
                        <option value="">Area</option>
                        @foreach ($areasUser as $areas)
                            <option value="{{$areas->id}}">{{$areas->nombre}}</option>
                        @endforeach
                    </select>

                    <input type="date" name="fecha" id="fecha_filtro" class="content-filtros-select rounded inputs-de-filtro">

                    <select name="usuario" id="usuario_filtro" class="content-filtros-select select_2_view w-100 inputs-de-filtro">
                        <option value="">Invitado</option>
                        @foreach ($usuarios as $invitado)
                            <option value="{{$invitado->id}}">{{$invitado->name}}</option>
                        @endforeach
                    </select>

                    <select name="ordenar" id="ordenar" class="select_2_view inputs-de-filtro">
                        <option value="">Ordenar</option>
                        <option value="ASC">De menor a mayor</option>
                        <option value="DESC">De mayor a menor</option>
                    </select>

                    <button id="reset_filtro" class="inputs-de-filtro"><i class="fas fa-broom"></i></button>
                </div>
            </div>
            {{--Contenido de los cards segun la data.--}}
            <div class="container-fluid row w-100 d-flex w-100">
                <div id="todasContainerData" class="w-100 contenido-cards"></div>
                <div id="agendadasContainerData" class="w-100 contenido-cards"></div>
                <div id="atendidasContainerData" class="w-100 contenido-cards"></div>
                <div id="concluidasContainerData" class="w-100 contenido-cards"></div>
                <div id="pendientesContainerData" class="w-100 contenido-cards"></div>
                <div class="container contenido-paginador-footer" style="display: grid; place-content: center; place-items: center;">
                    <div class="d-flex justify-content-center align-items-center text-center contenedor-pagina-export">
                        <div id="pagination_todas" class="contenedor-de-la-paginacion"></div>
                        {{--@can('Ver reportes')--}}
                        <div id="todas-button-exportar" class="btn-exportar"></div>
                        {{--@endcan--}}
                    </div>
                    <div class="d-flex justify-content-center align-items-center text-center contenedor-pagina-export">
                        <div id="pagination_agendadas" class="contenedor-de-la-paginacion"></div>
                        <div id="agendadas-button-exportar" class="btn-exportar"></div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center text-center contenedor-pagina-export">
                        <div id="pagination_atendidas" class="contenedor-de-la-paginacion"></div>
                        {{--@can('Ver reportes')--}}
                        <div id="atendidas-button-exportar" class="btn-exportar"></div>
                        {{--@endcan--}}
                    </div>
                    <div class="d-flex justify-content-center align-items-center text-center contenedor-pagina-export">
                        <div id="pagination_concluidas" class="contenedor-de-la-paginacion"></div>
                        {{--@can('Ver reportes')--}}
                        <div id="concluidas-button-exportar" class="btn-exportar"></div>
                        {{--@endcan--}}
                    </div>
                    <div class="d-flex justify-content-center align-items-center text-center contenedor-pagina-export">
                        <div id="pagination_pendientes_agendar" class="col contenedor-de-la-paginacion"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="col-lg-3 first-content-agendas">
            <strong>Espacios asignados</strong>
            <nav class="w-100">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    @foreach ($espacios as $index => $espacio)
                        <button
                            class="nav-link btn btn-outline-success ver-datos-por-espacio resetear-vista-general estilos-btns-tabs"
                            id="v-pills-{{ $espacio->id }}-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#v-pills-{{ $espacio->id }}"
                            type="button"
                            role="tab"
                            aria-controls="v-pills-{{ $espacio->id }}"
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                            data-url="/anfitrion/espacio/listar_agendas_por_espacio/{{$espacio->id}}"
                        >{{ $espacio->nombre }}</button>
                    @endforeach
                </div>
            </nav>
            <div class="tab-content w-100" id="v-pills-tabContent">
                @foreach ($espacios as $index => $espacio)
                    <div class="card tab-pane fade {{ $index === 0 ? '' : '' }} contenido-de-tabs" id="v-pills-{{ $espacio->id }}" role="tabpanel" aria-labelledby="v-pills-{{ $espacio->id }}-tab" tabindex="0" style="padding-left: 5px;">
                        <div class="title">
                            <strong class="subtitle-content-tabs">Descripcion: </strong>
                            <p class="content-subtitle" style="padding-left: 8px;">{{ $espacio->descripcion }}</p>
                        </div>
                        <div class="row">
                            <strong class="subtitle-content-tabs">Frecuencia: </strong>
                            <p class="content-subtitle" style="padding-left: 15px;">{{ $espacio->frecuencia }} dias</p>
                        </div>
                        <div class="row">
                            <strong class="subtitle-content-tabs">Consideraciones:</strong>
                            @php
                                $configData = json_decode($espacio->config);
                                if (json_last_error() !== JSON_ERROR_NONE) {
                                    $configData = null;
                                }
                            @endphp

                            @if(is_null($configData))
                                <p class="content-subtitle" style="padding-left: 15px;">No tiene consideraciones</p>
                            @else
                                @foreach ($configData as $index => $data)
                                    <p class="content-subtitle" style="padding-left: 15px;">{{ $data }}</p>
                                @endforeach
                            @endif
                        </div>
                        <hr style="border: 1px solid #D3D3D3;">
                        <div class="row">
                            <strong class="subtitle-content-guia">Guía para la sesión: </strong> <br>
                            <div class="boder">
                                {!! $espacio->guia !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

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
                        <div class="modal-body modal-content-calendar-agendar">
                            <div id="calendario-modal-google" style="height: 800px;"></div>
                        </div>
                        <div class="d-flex justify-content-around mt-3 mb-1 form-group">
                            <div class="col-6">
                                <select name="location" id="location" class="select-two rounded col-12 evento-time">
                                    <option value="">Seleccionar Tipo de evento</option>
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

    @include('oficial.parte_modal.modal_agendas')

    {{------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------}}
    <div class="modal fade" id="modalProgreso" tabindex="-1" role="dialog" aria-labelledby="modalProgresoLabel" aria-hidden="true">
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
    <div class="modal fade modal-right" id="subirEvidenciaModal" tabindex="-1" aria-labelledby="agendaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-contenido">
            <form method="post" class="sendEvidenciaAgenda" id="sendEvidenciaAgenda" enctype="multipart/form-data">
                @csrf
                <div class="modal-content modal-content-evidencia">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="agendaModalLabel">Detalle de la agenda con ID: <span id="id_agenda_input" class="ml-2"></span> - <span id="fechaHoraMeet_agenda" class="ml-2"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_agenda" id="id_agenda">
                        <div class="row d-flex w-100">
                            <label>Ingresa el nombre para los archivos</label>
                            <input type="text" class="form-control" name="nombre_archivo" id="nombre_archivo">
                        </div>
                        <div class="row d-flex w-100">
                            <label>Agregar evidencias (archivos)</label>
                            <div class="d-flex">
                                <input type="file" name="agenda_evidencia_file[]" id="agenda_evidencia_file" class="form-control input-file-sub-soporte" placeholder="" required>
                            </div>
                            <div id="fileInputsContainer"></div>
                            <a type="button" id="addFileInput" class="add_files ml-2 mt-2">Agregar más......</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-success button-evidencia">Subir evidencia <i class="fas fa-file ml-2" style="font-size: 20px; font-weight: bold;"></i> </button>
                        <button type="button" class="btn btn btn-outline-light btn-modal-cancel button-evidencia" data-bs-dismiss="modal">Cerrar <i class="fas fa-times ml-2" style="font-size: 20px; font-weight: bold;" ></i></button>
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

    {{--Estilos para la vista en particular--}}
    <link rel="stylesheet" href="{{asset('css/estilos_admin_agendas_lider.css')}}">
    {{--Estilos para el sidebar--}}
    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css')}}">
    {{--Estilo css para el modal de subir evidencia--}}
    <link rel="stylesheet" href="{{asset('css/modal-save-evidencia.css')}}">
    <link rel="stylesheet" href="{{asset('css/modal_agendar_todos.css')}}">

    <style>
        :root{
            --color-ver: #007A3E;
        }
        .modal {
            z-index: 2000 !important;
        }
        .select2-container .select2-dropdown {
            z-index: 100000 !important;
        }
        .swal2-container {
            z-index: 2001;
        }
        .fc-event-title{
            color: var(--color-verde) !important;
        }
        .fc-event:hover {
            border: 2px solid var(--color-verde) !important;
            color: var(--color-verde) !important;
            z-index: 999999 !important;
            width: 200px !important;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5) !important;
        }
    </style>
@stop

@section('js')
    @include('usuarios_vistas.scriptcss.script')
    <script src="{{asset('js/scripts.js')}}"></script>

    <script type="text/javascript">
        // Codigo para cargar datos para paginación:
        $(function(){
            // Parte 1
            function cargarDatosPaginacion(url, container) {
                // Obtener valores de los filtros
                let valor_area = $('#area_filtro').val();
                let valor_fecha = $('#fecha_filtro').val();
                let valor_usuario = $('#usuario_filtro').val();
                let valor_orden = $('#ordenar').val();

                // Realizar la llamada AJAX con los filtros segun el tipo de container que se ha pasado:
                if(container === '#todasContainerData'){
                    // console.log('Accedió al controller de todas');
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            area:       valor_area,
                            fecha:      valor_fecha,
                            usuario:    valor_usuario,
                            orden:      valor_orden,
                        },
                        success: function(response) {
                            $('#agendadas').removeClass('active');
                            $('#atendidas').removeClass('active');
                            $('#concluidas').removeClass('active');
                            $('#pendientes').removeClass('active');

                            $('#agendadasContainerData').html('');
                            $('#atendidasContainerData').html('');
                            $('#concluidasContainerData').html('');
                            $('#pendientesContainerData').html('');

                            $('#pagination_concluidas').html('');
                            $('#pagination_agendadas').html('');
                            $('#pagination_atendidas').html('');
                            $('#pagination_pendientes_agendar').html('');

                            $('#todas').addClass('active');


                            $('#agendadas-button-exportar').html('');
                            $('#atendidas-button-exportar').html('');
                            $('#concluidas-button-exportar').html('');

                            if(response.todas && Array.isArray(response.todas)){
                                if (response.todas.length > 0) {
                                    let todasHtml = '';
                                    response.todas_por_pagina.data.forEach(function(data) {
                                        let estado       = data.estado;
                                        let fechaHora    = data.fecha_hora;
                                        let archivos     = JSON.parse(data.archivos);
                                        let tipo_reunion = data.tipo_reunion;
                                        let agenda_id = data.agenda_id;

                                        let fechaHoraActual = new Date(); // Obtener la fecha y hora actual

                                        // Convertir la fecha y hora del servidor a un objeto de fecha JavaScript
                                        let fechaHoraServidor = new Date(fechaHora);
                                        let diaSemana    = fechaHoraServidor.toLocaleDateString('es-ES', { weekday: 'long' });
                                        let diaMes       = fechaHoraServidor.getDate();
                                        let mes          = fechaHoraServidor.toLocaleDateString('es-ES', { month: 'long' });
                                        let anio         = fechaHoraServidor.getFullYear();
                                        let hora         = fechaHoraServidor.toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit' });

                                        // Formatear la fecha y hora
                                        let fechaHoraFormateada = diaSemana + ', ' + diaMes + ' de ' + mes + ' - ' + anio + ' | ' + hora;

                                        let estadoHTML = '';
                                        if (estado === 'terminado') {
                                            estadoHTML = '<span style="margin-left: 5px;" class="text-success estado-terminada">Cerrada <i class="fas fa-calendar-check"></i></span>';
                                        } else {
                                            if (new Date(fechaHora) >= new Date(response.fecha_hora_actual)) {
                                                estadoHTML = '<span style="margin-left: 5px;" class="text-danger estado-agendada text-estado-cercana">Agendada <i class="far fa-calendar"></i></span>';
                                            }else{
                                                estadoHTML = '<p class="text-estado-atendidas">Atendida <i class="fas fa-comment"></i></p>';
                                            }
                                        }

                                        archivosHTML='';
                                        // Verificar si hay más de un archivo
                                        if (archivos.length > 1) {
                                            archivosHTML += '<div class="dropdown">';
                                            archivosHTML += '<button class="descargable-soporte dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                            archivosHTML += 'Descargar archivos';
                                            archivosHTML += '</button>';
                                            archivosHTML += '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                                            archivos.forEach(function(archivo) {
                                                if (archivo !== null && archivo !== 'No tiene archivos') { //  href="/storage/${archivo}" download>${archivo}</a>
                                                    archivosHTML += `<a class="dropdown-item" href="/${archivo}" download>${archivo}</a>`;
                                                }
                                            });
                                            archivosHTML += '</div>';
                                            archivosHTML += '</div>';
                                        } else {
                                            archivosHTML += '<div class="w-100 d-flex flex-wrap">';
                                            archivos.forEach(function(archivo) {
                                                if (archivo !== null && archivo !== 'No tiene archivos') { // href="/storage/${archivo}" download class="descargable-soporte">${archivo}
                                                    archivosHTML += `<a href="/${archivo}" download class="descargable-soporte">${archivo}</a>`;
                                                }
                                            });
                                            archivosHTML += '</div>';
                                        }

                                        let invitados = data.invitado.split(',');
                                        let primerosInvitados = invitados.slice(0, 2); // Primeros dos invitados
                                        let restantesInvitados = invitados.slice(2);   // Resto de los invitados

                                        let dropdownHTML = ''; // HTML para el dropdown de invitados restantes
                                        if (restantesInvitados.length > 0) {
                                            dropdownHTML = `
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Ver más
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            `;
                                            restantesInvitados.forEach(function(invitado) {
                                                dropdownHTML += `<a class="dropdown-item" href="#">${invitado}</a>`;
                                            });
                                            dropdownHTML += '</div></div>';
                                        }

                                        let primerosInvitadosHTML = ''; // HTML para los primeros dos invitados como <span> individuales
                                        primerosInvitados.forEach(function(invitado) {
                                            primerosInvitadosHTML += `<span class="span-invitados" style="margin: 2px;">${invitado}</span>`;
                                        });

                                        todasHtml += `
                                            <div class="card w-100" style="width: 18rem;">
                                                <form action="">
                                                    @csrf
                                                    <div class="card-body">
                                                        <input type="hidden" name="agenda_id" id="agenda_id" value="${data.agenda_id}">
                                                        <input type="hidden" name="user_id" id="user_id" value="${data.userLog}">
                                                        <input type="hidden" name="area_id" id="area_id" value="${data.areas}">
                                                        <input type="hidden" name="espacio_id" id="espacio_id" value="${data.espacios}">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${data.espacio_nombre}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                ${estadoHTML}
                                                            </div>
                                                        </div>
                                                        <div class="fecha_hora_meet d-flex justify-content-between">
                                                            <div clas="col"><p style="margin-left: 5px;">${fechaHoraFormateada}</p></div>
                                                            <div class="col content-right">ID: ${agenda_id}</div>
                                                        </div>
                                                        <div class="row d-flex justify-content-between">
                                                            <div class="col d-flex">
                                                                <strong class="ms-2 text-area-cercana">Area:</strong>
                                                                <span class="ms-2 text-area-espacio"style="margin-left: 5px;" class="area">${data.area_nombre}</span>
                                                            </div>
                                                            ${(estado === 'terminado' || new Date(fechaHora) <= new Date(response.fecha_hora_actual)) ?
                                                                '' :
                                                                `<div class="col meet content-right">
                                                                    <a href="${data.link_meet}" target="_blank" class="ms-2 btn btn-outline-success text-meet-cercana" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Unirse <i class="fas fa-video"></i></a>
                                                                </div>`
                                                            }
                                                        </div>
                                                        <div class="">
                                                           <i class="fas fa-user icon-invitados anvitados-icon" style="margin-right: 5px;"></i>
                                                           ${primerosInvitadosHTML}
                                                           ${dropdownHTML}
                                                        </div>
                                                        <div class="row d-flex justify-content-between">
                                                            <div class="col">
                                                                <strong>Soportes</strong><br>
                                                                ${archivosHTML}
                                                            </div>
                                                            <div class="col content-right">
                                                                ${(estado === 'terminado' || new Date(fechaHora) >= new Date(response.fecha_hora_actual)) ? '' : `<a href="javascript:void(0)" class="btn btn-outline-light btn-subir-evidencia-card ver-agenda-para-subir-evidencia" data-url="/anfitrion/agendas/ver_datos_agenda_evidencia/${data.agenda_id}">Subir reporte <i class="fas fa-cloud-upload-alt"></i></a>` }
                                                                ${new Date(fechaHora).getTime() < new Date(response.fecha_hora_actual).getTime() && tipo_reunion === 'max 10' && estado === 'pendiente' ?
                                                                    `<a href="javascript:void(0)" class="btn btn-success cerrar_agenda " data-url="/anfitrion/agendas/ver_data_culminacion/${data.agenda_id}">Cerrar <i class="fas fa-times"></i></a>` : '' }
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        `;
                                    });

                                    $('#todasContainerData').html(todasHtml);

                                    // Generar el paginador
                                    let todasPaginationHtml = '<ul class="pagination justify-content-center btns-paginadores-style">';
                                    todasPaginationHtml += response.todas_por_pagina.links.map(function(link) {
                                        return '<li class="page-item ' + (link.active ? 'active' : '') + '"><a class="page-link" href="#" data-url="' + link.url + '">' + link.label + '</a></li>';
                                    }).join('');
                                    todasPaginationHtml += '</ul>';

                                    $('#pagination_todas').html(todasPaginationHtml);
                                    $('#pagination_todas .page-link').click(function(e) {
                                        e.preventDefault();
                                        let todasNextPageUrl = $(this).data('url');
                                        cargarDatosPaginacion(todasNextPageUrl, '#todasContainerData');
                                    });
                                    let exportarBTN = '<div><a class="btn btn-outline-success btn-exportar-agendas" href="#">Exportar <i class="fas fa-file-excel"></i></a></div>';
                                    $('#todas-button-exportar').html(exportarBTN);

                                } else {
                                    $('#todasContainerData').html('<div class="card shadow mt-2 mb-2"><p class="text-danger">Aún no programaste ninguna agenda</p></div>');
                                    $('#pagination_todas').html('');
                                }
                            }else{
                                $('#concluidasContainerData').html('<p class="text-danger">No hay agendas disponibles aún.</p>');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Manejar errores si es necesario
                        }
                    });
                }else if(container === '#agendadasContainerData'){
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            area:       valor_area,
                            fecha:      valor_fecha,
                            usuario:    valor_usuario,
                            orden:      valor_orden,
                        },
                        success: function(response) {
                            $('#todas').removeClass('active');
                            $('#atendidas').removeClass('active');
                            $('#concluidas').removeClass('active');
                            $('#pendientes').removeClass('active');

                            $('#todasContainerData').html('');
                            $('#atendidasContainerData').html('');
                            $('#concluidasContainerData').html('');
                            $('#pendientesContainerData').html('');

                            $('#pagination_todas').html('');
                            $('#pagination_atendidas').html('');
                            $('#pagination_concluidas').html('');
                            $('#pagination_pendientes_agendar').html('');

                            $('#agendadas').addClass('active');

                            $('#todas-button-exportar').html('');
                            $('#atendidas-button-exportar').html('');
                            $('#concluidas-button-exportar').html('');

                            if(response.agendadas && Array.isArray(response.agendadas)){
                                if (response.agendadas.length > 0) {
                                    let agendadasHtml = '';
                                    response.agendadas_por_pagina.data.forEach(function(data) {
                                        let agenda_id = data.agenda_id;
                                        let fechaHoraActual = new Date(); // Obtener la fecha y hora actual
                                        let fechaHora = data.fecha_hora;
                                        // Convertir la fecha y hora del servidor a un objeto de fecha JavaScript
                                        let fechaHoraServidor = new Date(fechaHora);
                                        let diaSemana   = fechaHoraServidor.toLocaleDateString('es-ES', { weekday: 'long' });
                                        let diaMes      = fechaHoraServidor.getDate();
                                        let mes         = fechaHoraServidor.toLocaleDateString('es-ES', { month: 'long' });
                                        let anio        = fechaHoraServidor.getFullYear();
                                        let hora        = fechaHoraServidor.toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit' });

                                        // Formatear la fecha y hora
                                        let fechaHoraFormateada = diaSemana + ', ' + diaMes + ' de ' + mes + ' - ' + anio + ' | ' + hora;

                                        let invitados = data.invitado.split(',');
                                        let primerosInvitados = invitados.slice(0, 2); // Primeros dos invitados
                                        let restantesInvitados = invitados.slice(2);   // Resto de los invitados

                                        let dropdownHTML = ''; // HTML para el dropdown de invitados restantes
                                        if (restantesInvitados.length > 0) {
                                            dropdownHTML = `
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Ver más
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            `;
                                            restantesInvitados.forEach(function(invitado) {
                                                dropdownHTML += `<a class="dropdown-item" href="#">${invitado}</a>`;
                                            });
                                            dropdownHTML += '</div></div>';
                                        }

                                        let primerosInvitadosHTML = ''; // HTML para los primeros dos invitados como <span> individuales
                                        primerosInvitados.forEach(function(invitado) {
                                            primerosInvitadosHTML += `<span class="span-invitados" style="margin: 2px;">${invitado}</span>`;
                                        });


                                        agendadasHtml += `
                                            <div class="card w-100" style="width: 18rem;">
                                                <form action="">
                                                    @csrf
                                                    <div class="card-body">
                                                        <input type="hidden" name="agenda_id" id="agenda_id" value="${data.agenda_id}">
                                                        <input type="hidden" name="user_id" id="user_id" value="${data.userLog}">
                                                        <input type="hidden" name="area_id" id="area_id" value="${data.areas}">
                                                        <input type="hidden" name="espacio_id" id="espacio_id" value="${data.espacios}">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="ml-2 text-espacio-cercana">${data.espacio_nombre}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                ${data.estado === 'terminado' ? '<span style="margin-left: 5px;" class="text-success estado-terminada rounded">Terminado <i class="fas fa-calendar-check"></i> <i class="fas fa-calendar-check"></i></span>' : '<span style="margin-left: 5px;" class="text-danger estado-agendada text-estado-cercana">Agendada <i class="far fa-calendar"></i></span>'}
                                                            </div>
                                                        </div>
                                                        <div class="fecha_hora_meet d-flex justify-content-between">
                                                            <div clas="col"><p style="margin-left: 5px;">${fechaHoraFormateada}</p></div>
                                                            <div class="col content-right">ID: ${agenda_id}</div>
                                                        </div>
                                                        <div class="row d-flex justify-content-between">
                                                            <div class="areas col d-flex">
                                                                <strong class="ms-2 text-area-cercana">Area:</strong>
                                                                <p class="ms-2 text-area-espacio" style="margin-left: 5px;">${data.area_nombre}</p>
                                                            </div>
                                                            ${(new Date(fechaHora) <= new Date(response.fecha_hora_actual)) ?
                                                                '' :
                                                                `<div class="col content-right">
                                                                    <a href="${data.link_meet}" target="_blank" class="btn btn-outline-success text-meet-cercana">Unirse <i class="fas fa-video"></i></a>
                                                                </div>`
                                                            }
                                                        </div>
                                                        <div class="invitados d-flex">
                                                            <i class="fas fa-user icon-invitados" style="margin-right: 5px;"></i>
                                                            ${primerosInvitadosHTML}
                                                            ${dropdownHTML}
                                                        </div>
                                                        <div class="row d-flex justify-content-between">
                                                            <div class="col">
                                                                <strong class="text-soportes">Soportes</strong>
                                                                ${(new Date(fechaHora) <= new Date(response.fecha_hora_actual)) ? '<p class="text-danger">Puede subir sus soportes.</p>' : '<p class="text-soportes-desc">Sin soporte. </p>'}
                                                            </div>
                                                            <div class="col">
                                                                ${(new Date(fechaHora) <= new Date(response.fecha_hora_actual)) ? '<a href="javascript:void(0)" class="btn btn-outline-light btn-subir-evidencia-card ver-agenda-para-subir-evidencia text-meet-cercana" data-url="/anfitrion/agendas/ver_datos_agenda_evidencia/' + data.agenda_id + '">Subir reporte <i class="fas fa-cloud-upload-alt"></i></a>' : ''}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        `;
                                    });
                                    $('#agendadasContainerData').html(agendadasHtml);
                                    // Generar el paginador
                                    let agendadasPaginationHtml = '<ul class="pagination justify-content-center btns-paginadores-style">';
                                    agendadasPaginationHtml += response.agendadas_por_pagina.links.map(function(link) {
                                        return '<li class="page-item ' + (link.active ? 'active' : '') + '"><a class="page-link" href="#" data-url="' + link.url + '">' + link.label + '</a></li>';
                                    }).join('');
                                    agendadasPaginationHtml += '</ul>';

                                    $('#pagination_agendadas').html(agendadasPaginationHtml);
                                    $('#pagination_agendadas .page-link').click(function(e) {
                                        e.preventDefault();
                                        let agendadasNextPageUrl = $(this).data('url');
                                        cargarDatosPaginacion(agendadasNextPageUrl, '#agendadasContainerData');
                                    });
                                    let exportarBTN = '<div><a class="btn btn-outline-success btn-exportar-agendas" href="#">Exportar <i class="fas fa-file-excel"></i></a></div>';
                                    $('#agendadas-button-exportar').html(exportarBTN);
                                } else {
                                    $('#agendadasContainerData').html('<div class="card shadow mt-2 mb-2"><p class="text-danger">Aún no cuentas con agendas programadas</p></div>');
                                    $('#pagination_agendadas').html('');
                                }
                            }else{
                                $('#agendadasContainerData').html('<p class="text-danger">No hay agendas programadas disponibles.</p>');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Manejo de errores
                        }
                    });
                }else if(container === '#atendidasContainerData'){
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            area:       valor_area,
                            fecha:      valor_fecha,
                            usuario:    valor_usuario,
                            orden:      valor_orden,
                        },
                        success: function(response) {
                            $('#todas').removeClass('active');
                            $('#agendadas').removeClass('active');
                            $('#concluidas').removeClass('active');
                            $('#pendientes').removeClass('active');

                            $('#todasContainerData').html('');
                            $('#agendadasContainerData').html('');
                            $('#concluidasContainerData').html('');
                            $('#pendientesContainerData').html('');

                            $('#pagination_todas').html('');
                            $('#pagination_agendadas').html('');
                            $('#pagination_concluidas').html('');
                            $('#pagination_pendientes_agendar').html('');

                            $('#atendidas').addClass('active');

                            $('#todas-button-exportar').html('');
                            $('#agendadas-button-exportar').html('');
                            $('#concluidas-button-exportar').html('');

                            if(response.atendidas && Array.isArray(response.atendidas)){
                                if (response.atendidas.length > 0) {
                                    let atendidasHtml = '';
                                    response.atendidas_por_pagina.data.forEach(function(data) {
                                        let agenda_id = data.agenda_id;
                                        let tipo_reunion_aten = data.tipo_reunion;

                                        let fechaHoraActual = new Date(); // Obtener la fecha y hora actual
                                        let fechaHora = data.fecha_hora;
                                        // Convertir la fecha y hora del servidor a un objeto de fecha JavaScript
                                        let fechaHoraServidor = new Date(fechaHora);
                                        let diaSemana   = fechaHoraServidor.toLocaleDateString('es-ES', { weekday: 'long' });
                                        let diaMes      = fechaHoraServidor.getDate();
                                        let mes         = fechaHoraServidor.toLocaleDateString('es-ES', { month: 'long' });
                                        let anio        = fechaHoraServidor.getFullYear();
                                        let hora        = fechaHoraServidor.toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit' });
                                        // Formatear la fecha y hora
                                        let fechaHoraFormateada = diaSemana + ', ' + diaMes + ' de ' + mes + ' - ' + anio + ' | ' + hora;


                                        let invitados = data.invitado.split(',');
                                        let primerosInvitados = invitados.slice(0, 2); // Primeros dos invitados
                                        let restantesInvitados = invitados.slice(2);   // Resto de los invitados

                                        let dropdownHTML = ''; // HTML para el dropdown de invitados restantes
                                        if (restantesInvitados.length > 0) {
                                            dropdownHTML = `
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Ver más
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            `;
                                            restantesInvitados.forEach(function(invitado) {
                                                dropdownHTML += `<a class="dropdown-item" href="#">${invitado}</a>`;
                                            });
                                            dropdownHTML += '</div></div>';
                                        }

                                        let primerosInvitadosHTML = ''; // HTML para los primeros dos invitados como <span> individuales
                                        primerosInvitados.forEach(function(invitado) {
                                            primerosInvitadosHTML += `<span class="span-invitados" style="margin: 2px;">${invitado}</span>`;
                                        });

                                        atendidasHtml += `
                                            <div class="card w-100" style="width: 18rem;">
                                                <form action="">
                                                    @csrf
                                                    <div class="card-body">
                                                        <input type="hidden" name="agenda_id" id="agenda_id" value="${data.agenda_id}">
                                                        <input type="hidden" name="user_id" id="user_id" value="${data.userLog}">
                                                        <input type="hidden" name="area_id" id="area_id" value="${data.areas}">
                                                        <input type="hidden" name="espacio_id" id="espacio_id" value="${data.espacios}">
                                                         <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-atendida">${data.espacio_nombre}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                ${data.estado === 'terminado' ? '<span style="margin-left: 5px;" class="text-success estado-terminada rounded">Terminado <i class="fas fa-calendar-check"></i></span>' : '<span class="text-estado-atendidas">Atendida <i class="fas fa-comment"></i></span>'}
                                                            </div>
                                                        </div>
                                                        <div class="fecha_hora_meet d-flex justify-content-between">
                                                            <div clas="col"><p style="margin-left: 5px;">${fechaHoraFormateada}</p></div>
                                                            <div class="col content-right">ID: ${agenda_id}</div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="text-area-atendida">Area:</strong>
                                                            <span class="ml-1 text-area-espacio" style="margin-left: 5px;" class="area">${data.area_nombre}</span>
                                                        </div>
                                                        <div class="invitados d-flex">
                                                            <i class="fas fa-user icon-invitados" style="margin-right: 5px;"></i>
                                                            ${primerosInvitadosHTML}
                                                            ${dropdownHTML}
                                                        </div>
                                                        <div class="row d-flex justify-content-between">
                                                            <div class="col d-block">
                                                                <div><strong class="text-soportes">Soportes: </strong></div>
                                                                <div><p class="text-soportes-desc">Sin soportes</p></div>
                                                            </div>
                                                            <div class="col content-right">
                                                                <a href="javascript:void(0)" class="btn btn-outline-light btn-subir-evidencia-card ver-agenda-para-subir-evidencia text-meet-cercana" data-url="/anfitrion/agendas/ver_datos_agenda_evidencia/${data.agenda_id}">Subir reporte <i class="fas fa-cloud-upload-alt"></i></a>
                                                                ${tipo_reunion_aten === 'max 10' ? '<a href="javascript:void(0)" class="btn btn-success cerrar_agenda" data-url="/anfitrion/agendas/ver_data_culminacion/'+ data.agenda_id +'" style="margin-left: 2px;">Cerrar <i class="fas fa-times"></i></a>' : ''}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        `;
                                    });
                                    $('#atendidasContainerData').html(atendidasHtml);

                                    // Generar el paginador
                                    let atendidasPaginationHtml = '<ul class="pagination justify-content-center btns-paginadores-style">';
                                    atendidasPaginationHtml += response.atendidas_por_pagina.links.map(function(link) {
                                        return '<li class="page-item ' + (link.active ? 'active' : '') + '"><a class="page-link" href="#" data-url="' + link.url + '">' + link.label + '</a></li>';
                                    }).join('');
                                    atendidasPaginationHtml += '</ul>';

                                    $('#pagination_atendidas').html(atendidasPaginationHtml);
                                    $('#pagination_atendidas .page-link').click(function(e) {
                                        e.preventDefault();
                                        let atendidasNextPageUrl = $(this).data('url');
                                        cargarDatosPaginacion(atendidasNextPageUrl, '#atendidasContainerData');
                                    });

                                    let exportarBTN = '<div><a class="btn btn-outline-success btn-exportar-agendas" href="#">Exportar <i class="fas fa-file-excel"></i></a></div>';
                                    $('#atendidas-button-exportar').html(exportarBTN);
                                } else {
                                    $('#atendidasContainerData').html('<div class="card shadow mt-2 mb-2"><p class="text-danger">Aún no cuentas con agendas atendidas</p></div>');
                                    $('#pagination_atendidas').html('');
                                }
                            }else{
                                $('#atendidasContainerData').html('<p class="text-danger">No hay agendas atendidas disponibles.</p>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error en la solicitud AJAX:', error);
                        }
                    });
                }else if(container === '#concluidasContainerData'){
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            area:       valor_area,
                            fecha:      valor_fecha,
                            usuario:    valor_usuario,
                            orden:      valor_orden,
                        },
                        success: function(response) {

                            $('#todas').removeClass('active');
                            $('#agendadas').removeClass('active');
                            $('#atendidas').removeClass('active');
                            $('#pendientes').removeClass('active');

                            $('#todasContainerData').html('');
                            $('#agendadasContainerData').html('');
                            $('#atendidasContainerData').html('');
                            $('#pendientesContainerData').html('');

                            $('#pagination_todas').html('');
                            $('#pagination_agendadas').html('');
                            $('#pagination_atendidas').html('');
                            $('#pagination_pendientes_agendar').html('');

                            $('#todas-button-exportar').html('');
                            $('#agendadas-button-exportar').html('');
                            $('#atendidas-button-exportar').html('');

                            $('#concluidas').addClass('active');

                            $('#todas-button-exportar').html('');
                            $('#agendadas-button-exportar').html('');
                            $('#atendidas-button-exportar').html('');

                            // Manejar la respuesta y actualizar la página
                            if (response.concluidas && Array.isArray(response.concluidas)) {
                                if (response.concluidas.length > 0) {
                                    let concluidasHtml = '';
                                    response.concluidas_por_pagina.data.forEach(function(data) {
                                        let agenda_id = data.agenda_id;
                                        let fechaHoraServidor = new Date(data.fecha_hora);
                                        let diaSemana = fechaHoraServidor.toLocaleDateString('es-ES', { weekday: 'long' });
                                        let diaMes = fechaHoraServidor.getDate();
                                        let mes = fechaHoraServidor.toLocaleDateString('es-ES', { month: 'long' });
                                        let anio = fechaHoraServidor.getFullYear();
                                        let hora = fechaHoraServidor.toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit' });

                                        let fechaHoraFormateada = diaSemana + ', ' + diaMes + ' de ' + mes + ' - ' + anio + ' | ' + hora;

                                        let archivosHTML = '';
                                        if (data.archivos) {
                                            let archivos = JSON.parse(data.archivos);

                                            archivosHTML = '<div class="w-100 d-flex flex-wrap">';
                                            archivos.forEach(function(archivo) {
                                                if (archivo !== null && archivo !== 'No tiene archivos') { // <a href="/storage/${archivo}" download class="descargable-soporte">${archivo}</a>
                                                    archivosHTML += `<a href="/${archivo}" download class="descargable-soporte">${archivo}</a>`;
                                                }
                                            });
                                            archivosHTML += '</div>';
                                        } else {
                                            archivosHTML = '<div>No hay soporte</div>'; // Si no hay archivos adjuntos
                                        }

                                        let invitados = data.invitado.split(',');
                                        let primerosInvitados = invitados.slice(0, 2); // Primeros dos invitados
                                        let restantesInvitados = invitados.slice(2);   // Resto de los invitados

                                        let dropdownHTML = '';
                                        if (restantesInvitados.length > 0) {
                                            dropdownHTML = `
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Otros Invitados
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            `;
                                            restantesInvitados.forEach(function(invitado) {
                                                dropdownHTML += `<a class="dropdown-item" href="#">${invitado}</a>`;
                                            });
                                            dropdownHTML += '</div></div>';
                                        }

                                        let primerosInvitadosHTML = '';
                                        primerosInvitados.forEach(function(invitado) {
                                            primerosInvitadosHTML += `<span class="span-invitados" style="margin: 2px;">${invitado}</span>`;
                                        });


                                        concluidasHtml += `
                                            <div class="card w-100" style="width: 18rem;">
                                                <form action="">
                                                    @csrf
                                                    <div class="card-body">
                                                        <input type="hidden" name="agenda_id" id="agenda_id" value="${data.agenda_id}">
                                                        <input type="hidden" name="user_id" id="user_id" value="${data.userLog}">
                                                        <input type="hidden" name="area_id" id="area_id" value="${data.areas}">
                                                        <input type="hidden" name="espacio_id" id="espacio_id" value="${data.espacios}">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="ml-2 text-espacio-cercana">${data.espacio_nombre}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                ${data.estado === 'terminado' ? '<span style="margin-left: 5px;" class="text-success estado-terminada rounded">Cerrada <i class="fas fa-calendar-check"></i></span>' : ''}
                                                            </div>
                                                        </div>
                                                        <div class="fecha_hora_meet d-flex justify-content-between">
                                                            <div clas="col"><p style="margin-left: 5px;">${fechaHoraFormateada}</p></div>
                                                            <div class="col content-right">ID: ${agenda_id}</div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="ms-2 text-area-cercana">Área:</strong>
                                                            <span class="ms-2 text-area-espacio" style="margin-left: 5px;" class="area">${data.area_nombre}</span>
                                                        </div>
                                                        <div class="invitadosd-flex">
                                                            <i class="fas fa-user icon-invitados" style="margin-right: 5px;"></i>
                                                            ${primerosInvitadosHTML}
                                                            ${dropdownHTML}
                                                        </div>

                                                        <div class="row d-flex justify-content-between">
                                                            <div class="col d-block">
                                                                <strong class="text-soportes">Soportes: </strong> <br>
                                                                ${archivosHTML}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        `;
                                    });

                                    // Mostrar los cards en el contenedor
                                    $('#concluidasContainerData').html(concluidasHtml);

                                    // Generar el paginador
                                    let paginationConcluidasHtml = '<ul class="pagination justify-content-center btns-paginadores-style">';
                                    paginationConcluidasHtml += response.concluidas_por_pagina.links.map(function(link) {
                                        return '<li class="page-item ' + (link.active ? 'active' : '') + '"><a class="page-link" href="#" data-url="' + link.url + '">' + link.label + '</a></li>';
                                    }).join('');
                                    paginationConcluidasHtml += '</ul>';

                                    // Mostrar el paginador
                                    $('#pagination_concluidas').html(paginationConcluidasHtml);

                                    // Agregar evento click al paginador
                                    $('#pagination_concluidas .page-link').click(function(e) {
                                        e.preventDefault();
                                        let concluidasNextPageUrl = $(this).data('url');
                                        cargarDatosPaginacion(concluidasNextPageUrl, '#concluidasContainerData');
                                    });

                                    let exportarBTN = '<div><a class="btn btn-outline-success btn-exportar-agendas" href="#">Exportar <i class="fas fa-file-excel"></i></a></div>';
                                    $('#concluidas-button-exportar').html(exportarBTN);
                                } else {
                                    $('#concluidasContainerData').html('<div class="card shadow mt-2 mb-2"><p class="text-danger">Aún no cuentas con agendas concluídas</p></div>');
                                    $('#pagination_concluidas').html('');
                                }
                            } else {
                                // Mostrar mensaje si no hay datos
                                $('#concluidasContainerData').html('<p class="text-danger">No hay agendas concluidas disponibles.</p>');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Manejar errores si es necesario
                            console.error('Error en la solicitud AJAX:', error);
                        }
                    });
                }else if(container === '#pendientesContainerData'){
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            area:       valor_area,
                            usuario:    valor_usuario,
                        },
                        success: function(response) {
                            $('#todas').removeClass('active');
                            $('#agendadas').removeClass('active');
                            $('#atendidas').removeClass('active');
                            $('#concluidas').removeClass('active');

                            $('#todasContainerData').html('');
                            $('#agendadasContainerData').html('');
                            $('#atendidasContainerData').html('');
                            $('#concluidasContainerData').html('');

                            $('#pagination_todas').html('');
                            $('#pagination_agendadas').html('');
                            $('#pagination_atendidas').html('');
                            $('#pagination_concluidas').html('');


                            $('#todas-button-exportar').html('');
                            $('#agendadas-button-exportar').html('');
                            $('#atendidas-button-exportar').html('');
                            $('#concluidas-button-exportar').html('');

                            $('#pendientes').addClass('active');

                            let pendientesHtml = '';
                            let paginationPendientesHtml = '';

                            if (response && response.espacio && response.espacio.length > 0) {

                                response.espacio.forEach(espacio => {
                                    let id_espacio = espacio.espacio_id;
                                    let espacio_name = espacio.espacio_name;
                                    let descripcion = espacio.espacio_descripcion;
                                    let tipo_reunion = espacio.tipo_reunion;
                                    let area_id_otro = espacio.area_id;
                                    let area_name_ot = espacio.area_name;

                                    // Verifica que response.user_oto_paginacion tenga datos y esté estructurado correctamente
                                    if (response.user_oto_paginacion && response.user_oto_paginacion.data && response.user_oto_paginacion.data.length > 0) {
                                        // Itera sobre los usuarios de área individual oto
                                        response.user_oto_paginacion.data.forEach(function (user) {
                                            let user_id = user.user_id;
                                            let user_name = user.name;
                                            let area_id = user.area_id;
                                            let area_name = user.area_name;

                                            pendientesHtml += `
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${espacio_name}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agenda_individual" data-url="/anfitrion/agendas/obtener_datos/${user_id}/${id_espacio}/${area_id}">Agendar <i class="far fa-clock"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="invitado">
                                                            <p>${descripcion}</p>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <span class="ms-2 text-area-espacio">${area_name}</span>
                                                        </div>
                                                        <div class="invitado mt-2">
                                                            <i class="fas fa-user-circle icon-invitados"></i>
                                                            <span class="span-invitados">${user_name}</span>
                                                        </div>
                                                    </div>
                                                </div>`;
                                        });
                                    } else {
                                        console.log('No hay pendiente por programar.');
                                    }
                                });

                                // Generar el paginador solo una vez después de procesar todos los espacios
                                paginationPendientesHtml = '<ul class="pagination justify-content-center btns-paginadores-style">';
                                paginationPendientesHtml += response.user_oto_paginacion.links.map(function(link) {
                                    return '<li class="page-item ' + (link.active ? 'active' : '') + '"><a class="page-link" href="#" data-url="' + link.url + '">' + link.label + '</a></li>';
                                }).join('');
                                paginationPendientesHtml += '</ul>';

                                // Mostrar el paginador
                                $('#pagination_pendientes_agendar').html(paginationPendientesHtml);

                                // Agregar evento click al paginador
                                $('#pagination_pendientes_agendar .page-link').click(function(e) {
                                    e.preventDefault();
                                    let pendientesNextPageUrl = $(this).data('url');
                                    cargarDatosPaginacion(pendientesNextPageUrl, '#pendientesContainerData');
                                });
                            }

                            if(response && response.espacio_grupal && response.espacio_grupal.length > 0){

                                response.espacio_grupal.forEach(grupo => {
                                    let id_espacio_grup     = grupo.espacio_id;
                                    let espacio_grup        = grupo.espacio_name;
                                    let descripcion_grup    = grupo.espacio_descripcion;
                                    let tipo_reunion_grupo  = grupo.tipo_reunion;
                                    let area_id_grup        = grupo.area_id;
                                    let areas               = grupo.area_name.split(',');
                                    let usersIds     = response.usuarios_de_area.map(user => user.user_id);

                                    // console.log('Usersss: ',response.usuarios_de_area);

                                    let usersHTML = '';
                                    let primerosInvitadosHTML = '';
                                    response.usuarios_de_area.forEach(function(data) {
                                        let invitados = data.name.split(',');
                                        let primerosInvitados = invitados.slice(0, 2);
                                        let restanteInvitado = invitados.slice(2);
                                        if (restanteInvitado.length > 0) {
                                            usersHTML += `
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ver más</button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            `;
                                            restanteInvitado.forEach(function(invitado) {
                                                usersHTML += `<a class="dropdown-item" href="#">${invitado}</a>`;
                                            });
                                            usersHTML += '</div></div>';
                                        }
                                        primerosInvitados.forEach(function(invitado) {
                                            primerosInvitadosHTML += `<span class="span-invitados" style="margin: 2px;">${invitado}</span>`;
                                        });
                                    });

                                    let primerosUsuarios = response.usuarios_de_area.slice(0, 2);
                                    let usuariosRestantes = response.usuarios_de_area.slice(2);
                                    let primerosUsuariosHTML = primerosUsuarios.map(user => {
                                        return `<div class="invitado mt-2">
                                                    <i class="fas fa-user-circle icon-invitados"></i>
                                                    <span class="span-invitados">${user.user_name}</span>
                                                </div>`;
                                    }).join('');
                                    // Generar HTML para los usuarios restantes en el dropdown
                                    let dropdownUsuariosHTML = usuariosRestantes.map(user => {
                                        return `<a class="dropdown-item" href="#">${user.user_name}</a>`;
                                    }).join('');


                                    if(tipo_reunion_grupo === 'primario'){
                                            pendientesHtml += `
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                ${response.usuarios_de_area.length > 0 ?
                                                                    `<a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_primario" data-url="/anfitrion/agendas/obtener_datos_primario/${usersIds.join(',')}/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>` :
                                                                    `<a href="javascript:void(0)" class="btn btn-outline-secondary btn-disabled" id="" disabled>Agendar</a>`
                                                                }
                                                            </div>
                                                        </div>
                                                        <div class="descripcion">
                                                            <p>${descripcion_grup}</p>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                ${areas.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <i class="fas fa-user icon-invitados anvitados-icon" style="margin-right: 5px;"></i>
                                                            ${primerosInvitadosHTML}
                                                            ${usersHTML}
                                                        </div>
                                                    </div>
                                                </div>`;
                                    } else if(tipo_reunion_grupo === 'pares'){
                                            pendientesHtml += `
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_pares" data-url="/anfitrion/agendas/obtener_datos_pares/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="descripcion">
                                                            <p>${descripcion_grup}</p>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                ${areas.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>`;
                                    } else if(tipo_reunion_grupo === 'max 10'){
                                            pendientesHtml += `
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_max_10" data-url="/anfitrion/agendas/obtener_datos_max_10/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="descripcion">
                                                            <p>${descripcion_grup}</p>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                ${areas.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>`;
                                    } else if(tipo_reunion_grupo === 'country'){
                                            pendientesHtml += `
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                ${response.usuarios_de_area.length>0 ?
                                                                    `<a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="country_agendas" data-url="/anfitrion/agendas/obtener_datos_country/${usersIds.join(',')}/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>` :
                                                                    `<a href="javascript:void(0)" class="btn btn-outline-secondary btn-disabled" id="" disabled>Agendar</a>`
                                                                }
                                                            </div>
                                                        </div>
                                                        <div class="descripcion">
                                                            <p>${descripcion_grup}</p>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                ${areas.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>`;
                                    } else if(tipo_reunion_grupo === 'compras'){
                                            pendientesHtml += `
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                ${response.usuarios_de_area.length>0 ?
                                                                    `<a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_compras" data-url="/anfitrion/agendas/obtener_datos_compras/${usersIds.join(',')}/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>` :
                                                                    `<a href="javascript:void(0)" class="btn btn-outline-secondary btn-disabled" id="" disabled>Agendar</a>`
                                                                }
                                                            </div>
                                                        </div>
                                                        <div class="descripcion">
                                                            <p>${descripcion_grup}</p>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                ${areas.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <i class="fas fa-user icon-invitados anvitados-icon" style="margin-right: 5px;"></i>
                                                            ${primerosInvitadosHTML}
                                                            ${usersHTML}
                                                        </div>
                                                    </div>
                                                </div>`;
                                    } else if(tipo_reunion_grupo === 'merco'){
                                            pendientesHtml += `
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                ${response.usuarios_de_area.length>0 ?
                                                                    `<a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_merco" data-url="/anfitrion/agendas/obtener_datos_merco/${usersIds.join(',')}/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>` :
                                                                    `<a href="javascript:void(0)" class="btn btn-outline-secondary btn-disabled" id="" disabled>Agendar</a>`
                                                                }
                                                            </div>
                                                        </div>
                                                        <div class="descripcion">
                                                            <p>${descripcion_grup}</p>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                ${areas.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <i class="fas fa-user icon-invitados anvitados-icon" style="margin-right: 5px;"></i>
                                                            ${primerosInvitadosHTML}
                                                            ${usersHTML}
                                                        </div>
                                                    </div>
                                                </div>`;
                                    } else if(tipo_reunion_grupo === 'ranking'){
                                            pendientesHtml += `
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_ranking" data-url="/anfitrion/agendas/obtener_datos_ranking/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="descripcion">
                                                            <p>${descripcion_grup}</p>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                ${areas.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>`;
                                    } else if(tipo_reunion_grupo === 'indicadores'){
                                            pendientesHtml += `
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                ${response.usuarios_de_area.length>0 ?
                                                                    `<a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_indicadores" data-url="/anfitrion/agendas/obtener_datos_indicadores/${usersIds.join(',')}/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>` :
                                                                    `<a href="javascript:void(0)" class="btn btn-outline-secondary btn-disabled" id="" disabled>Agendar</a>`
                                                                }
                                                            </div>
                                                        </div>
                                                        <div class="descripcion">
                                                            <p>${descripcion_grup}</p>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                ${areas.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <i class="fas fa-user icon-invitados anvitados-icon" style="margin-right: 5px;"></i>
                                                            ${primerosInvitadosHTML}
                                                            ${usersHTML}
                                                        </div>
                                                    </div>
                                                </div>`;
                                    } else if(tipo_reunion_grupo === 'retroalimentacion'){
                                            pendientesHtml += `
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="retroalimentacion_agendas" data-url="/anfitrion/agendas/obtener_datos_retroalimentacion/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="descripcion">
                                                            <p>${descripcion_grup}</p>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                ${areas.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>`;
                                    } else if(tipo_reunion_grupo === 'sostenibilidad'){
                                            pendientesHtml += `
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                ${response.usuarios_de_area.length>0 ?
                                                                    `<a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_sostenibilidad" data-url="/anfitrion/agendas/obtener_datos_sostenibilidad/${usersIds.join(',')}/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>` :
                                                                    `<a href="javascript:void(0)" class="btn btn-outline-secondary btn-disabled" id="" disabled>Agendar</a>`
                                                                }
                                                            </div>
                                                        </div>
                                                        <div class="descripcion">
                                                            <p>${descripcion_grup}</p>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong span>Area: </strong>
                                                            <div class="areas d-flex flex-wrap">
                                                                ${areas.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <i class="fas fa-user icon-invitados anvitados-icon" style="margin-right: 5px;"></i>
                                                            ${primerosInvitadosHTML}
                                                            ${usersHTML}
                                                        </div>
                                                    </div>
                                                </div>`;

                                    }

                                });
                            }

                            $('#pendientesContainerData').empty();
                            $('#pendientesContainerData').append(pendientesHtml);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error en la solicitud AJAX:', error);
                        }
                    });
                }
            }
            // Parte 2:
            $('.ver-datos-por-espacio').click(function(){
                let espacioActivo = $('#v-pills-tab .nav-link.active');
                let espacioId = espacioActivo.attr('id').split('-')[2];
                let url = '/anfitrion/agendas/todas/' + espacioId;
                let container = '#todasContainerData';
                cargarDatosPaginacion(url, container);
            });
            $(document).on('click', '#todas', function(){
                let espacioActivo = $('#v-pills-tab .nav-link.active');
                let espacioId = espacioActivo.attr('id').split('-')[2];
                let url = '/anfitrion/agendas/todas/' + espacioId;
                let container = '#todasContainerData';
                cargarDatosPaginacion(url, container);
            });
            $(document).on('click', '#agendadas', function(){
                let espacioActivo = $('#v-pills-tab .nav-link.active');
                let espacioId = espacioActivo.attr('id').split('-')[2];
                let url = '/anfitrion/agendas/agendadas/' + espacioId;
                let container = '#agendadasContainerData';
                cargarDatosPaginacion(url, container);
            });
            $(document).on('click', '#atendidas', function(){
                let espacioActivo = $('#v-pills-tab .nav-link.active');
                let espacioId = espacioActivo.attr('id').split('-')[2];
                let url = '/anfitrion/agendas/atendidas/' + espacioId;
                let container = '#atendidasContainerData';

                cargarDatosPaginacion(url, container);
            });
            $(document).on('click', '#concluidas', function(){
                let espacioActivo = $('#v-pills-tab .nav-link.active');
                let espacioId = espacioActivo.attr('id').split('-')[2];
                let url = '/anfitrion/agendas/concluidas/' + espacioId;
                let container = '#concluidasContainerData';
                cargarDatosPaginacion(url, container);
            });
            $(document).on('click', '#pendientes', function(){
                let espacioActivo = $('#v-pills-tab .nav-link.active');
                let espacioId = espacioActivo.attr('id').split('-')[2];
                let url = '/anfitrion/agendas/pendientes_agendar/' + espacioId;
                let container = '#pendientesContainerData';

                cargarDatosPaginacion(url, container);
            });

            // Parte 3 -> SE REUTILIZARÁ ESTE CONTENIDO PARA #todas, #agendadas, #atendidas, #concluidas, #pendientes
            $('.content-filtros-select select, .content-filtros-select input').change(function() {
                // Obtener la URL del botón activo
                let espacioActivo = $('#v-pills-tab .nav-link.active');
                let espacioId = espacioActivo.attr('id').split('-')[2];
                let url;
                let container;

                if($('#todas').hasClass('active')){
                    url         = '/anfitrion/agendas/todas/' + espacioId;
                    container   = '#todasContainerData';
                }else if($('#agendadas').hasClass('active')){
                    url         = '/anfitrion/agendas/agendadas/' + espacioId;
                    container   = '#agendadasContainerData';
                }else if($('#atendidas').hasClass('active')){
                    url         = '/anfitrion/agendas/atendidas/' + espacioId;
                    container   = '#atendidasContainerData';
                }else if ($('#concluidas').hasClass('active')) {
                    url         = '/anfitrion/agendas/concluidas/' + espacioId;
                    container   = '#concluidasContainerData';
                }else if($('#pendientes').hasClass('active')){
                    url         = '/anfitrion/agendas/pendientes/' + espacioId;
                    container   = '#pendientesContainerData';
                }
                cargarDatosPaginacion(url, container);
            });

            // Limpieza de modal de evidencias
            $('#subirEvidenciaModal').on('hidden.bs.modal', function () {
                $('#nombre_archivo').val('');
                $('#agenda_evidencia_file').val('');
                $('#fileInputsContainer').empty();
            });

            $('#todas-button-exportar').on('click', function () {
                let area  = $('#area_filtro').val();
                let fecha = $('#fecha_filtro').val();
                let invit = $('#usuario_filtro').val();
                let espacioActivo = $('#v-pills-tab .nav-link.active');
                let espacio_id = espacioActivo.attr('id').split('-')[2];

                $.ajax({
                    url: "{{ route('agenda.exportar.todas_excel') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area: area,
                        fecha: fecha,
                        invitado: invit,
                        espacio: espacio_id
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response) {
                        // Crear un enlace para descargar el archivo .excel
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(response);
                        link.download = 'Agendas_todas.xlsx';
                        link.click();
                        Swal.fire('Exportado', 'El archivo ha sido exportado correctamente', 'success');
                    },
                    error: function () {
                        Swal.fire('Error', 'Los datos no pudieron ser exportados.', 'error');
                    }
                });
            });
            $('#agendadas-button-exportar').on('click', function(){
                let area  = $('#area_filtro').val();
                let fecha = $('#fecha_filtro').val();
                let invit = $('#usuario_filtro').val();
                let espacioActivo = $('#v-pills-tab .nav-link.active');
                let espacio_id = espacioActivo.attr('id').split('-')[2];

                $.ajax({
                    url: "{{ route('agenda.exportar.agendadas_excel') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { //se envían los datos para el filtro al controller
                        area: area,
                        fecha: fecha,
                        invitado: invit,
                        espacio: espacio_id
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response) {
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(response);
                        link.download = 'Agendas_programadas.xlsx';
                        link.click();
                        Swal.fire('Exportado', 'El archivo ha sido exportado correctamente', 'success');
                    },
                    error: function () {
                        Swal.fire('Error', 'Los datos no pudieron ser exportados.', 'error');
                    }
                });
            });
            $('#atendidas-button-exportar').on('click', function(){
                let area  = $('#area_filtro').val();
                let fecha = $('#fecha_filtro').val();
                let invit = $('#usuario_filtro').val();
                let espacioActivo = $('#v-pills-tab .nav-link.active');
                let espacio_id = espacioActivo.attr('id').split('-')[2];

                $.ajax({
                    url: "{{ route('agenda.exportar.atendidas_excel') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area: area,
                        fecha: fecha,
                        invitado: invit,
                        espacio: espacio_id
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response) {
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(response);
                        link.download = 'Agendas_atendidas.xlsx';
                        link.click();
                        Swal.fire('Exportado', 'El archivo ha sido exportado correctamente', 'success');
                    },
                    error: function () {
                        Swal.fire('Error', 'Los datos no pudieron ser exportados.', 'error');
                    }
                });
            });
            $('#concluidas-button-exportar').on('click', function(){
                let area  = $('#area_filtro').val();
                let fecha = $('#fecha_filtro').val();
                let invit = $('#usuario_filtro').val();
                let espacioActivo = $('#v-pills-tab .nav-link.active');
                let espacio_id = espacioActivo.attr('id').split('-')[2];

                // Realizar la solicitud AJAX
                $.ajax({
                    url: "{{ route('agenda.exportar.concluidas_excel') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area: area,
                        fecha: fecha,
                        invitado: invit,
                        espacio: espacio_id
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response) {
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(response);
                        link.download = 'Agendas_concluidas.xlsx';
                        link.click();
                        Swal.fire('Exportado', 'El archivo ha sido exportado correctamente', 'success');
                    },
                    error: function () {
                        Swal.fire('Error', 'Los datos no pudieron ser exportados.', 'error');
                    }
                });
            });

            //Limpieza del formulario de filtro:
            $('#reset_filtro'). on('click', function(){
                $('#area_filtro').val('').change();
                $('#fecha_filtro').val('');
                $('#usuario_filtro').val('').change();
                $('#ordenar').val('');

                return false;
            });

        });
        /***********************************************************************************************************************************/
        // Codigo para obtener datos y agregar eventos para espacio OTO
        $(document).on('click', '#agenda_individual', function (event) {
            event.preventDefault();
            var url = $(this).data('url');
            let mensaje= 'Envío de los datos al controller: ';

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
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
                            });console.error(xhr.responseText);
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
            return false;
        });
    </script>
    <!--Incluye codigo para ver datos en los modales-->
    @include('oficial.parte_modal.script')

    <script>
        /***********************************************************************************************************************************/
        // CODIGO JS PARA OBTENER LOS DATOS DE UNA AGENDA Y LUEGO SUBIR LA EVIDENCIA:
        $(document).on('click', '.ver-agenda-para-subir-evidencia', function (event){
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
                        let id_agenda       = data.agenda.id;
                        let fecha_hora_meet = data.agenda.fecha_hora_meet;
                        $('#id_agenda_input').text(id_agenda);
                        $('#fechaHoraMeet_agenda').text(fecha_hora_meet);
                        $('#id_agenda').val(id_agenda);

                        $('#subirEvidenciaModal').modal('show');
                    } else {
                        console.error('La propiedad "agenda" no está definida en la respuesta JSON.');
                    }
                },
                error:function (data){
                    console.log('Error: ', data);
                }
            });
        });
        // Agrega inputs para subir archivos
        $('#addFileInput').off('click').on('click', function () {
            $('#fileInputsContainer').append('<div class="d-flex justify-content-between"><input type="file" name="agenda_evidencia_file[]" id="subir_soporte_agenda" class="form-control col-10 mt-2 input-file-sub-soporte" required> <a class="btnQuitarFila col-2 mt-3"><i class="fas fa-times close-icon"></i></a></div>');
        });
        // Quita las filas de inputs agregados en el container
        $('#fileInputsContainer').off('click', '.btnQuitarFila').on('click', '.btnQuitarFila', function(){
            $(this).parent().remove();
        });
        // Codigo js para subir la evidencia:
        $('#sendEvidenciaAgenda').off('submit').on('submit', function (event){
            event.preventDefault();
            Swal.fire({
                title: '¿Está seguro de subir la evidencia?',
                icon: 'info',
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
                            $('#subirEvidenciaModal').modal('hide');
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
