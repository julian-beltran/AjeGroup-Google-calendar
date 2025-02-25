@extends('adminlte::page')

@section('title', 'Agendas | Pendientes')

@section('content_header')
    <b>Agendas ATENDIDAS y PENDIENTES por agendar</b>
@stop
@section('content')
    <section class="container-fluid  col-lg-12 col-md-12 col-sm-12 d-flex align-items-center contenedor-container contenedor-pagina-principal">
        <section class="col-lg-10 col-sm-12 justify-content-center mt-2 second-content-cards contenido-por-parametros contenido-de-cards">
            @if(session('sesiones'))
                <div class="container-fluid row w-100 d-flex justify-content-around">
                    <div class="col-12 d-flex buttons-agendas-filtros justify-content-around content-button-estado">
                        <button type="button" class="btn btn-outline-info mr-1 buttons-select disabled-button" id="todas_redireccion">Todas</button>
                        <button type="button" class="btn btn-outline-info mr-1 buttons-select disabled-button" id="agendadas_redireccion">Agendadas</button>
                        @if($sesiones_redirec === '1')
                            <button type="button" class="btn btn-outline-info active mr-1 buttons-select" id="atendidas_redireccion" value="A">Atendidas</button>
                        @else
                            <button type="button" class="btn btn-outline-infomr-1 buttons-select" id="atendidas_redireccion">Atendidas</button>
                        @endif
                        <button type="button" class="btn btn-outline-info mr-1 buttons-select disabled-button" id="concluidas_redireccion">Concluidas</button>
                        @if($sesiones_redirec === '2')
                            <button type="button" class="btn btn-outline-warning active mr-1 buttons-select" id="pendientes_redireccion" value="P">Pendientes por agendar</button>
                        @else
                            <button type="button" class="btn btn-outline-warning mr-1 buttons-select" id="pendientes_redireccion">Pendientes por agendar</button>
                        @endif
                    </div>
                    <div class="container-fluid row w-100 d-flex mb-2 mt-2 justify-content-around content-filtros-select-parametros">
                        @if($sesiones_redirec === '1')
                            <div class="col-12 d-flex buttons-agendas-filtros justify-content-around align-items-center content-filtros-select content-filtro-estado">
                                <select name="area" id="area_filtro" class="content-filtros-select select_2_view w-100 inputs-de-filtro">
                                    <option value="">Area</option>
                                    @foreach ($areasUser as $areas)
                                        <option value="{{$areas->id}}">{{$areas->nombre}}</option>
                                    @endforeach
                                </select>

                                <input type="date" name="fecha" id="fecha_filtro" class="content-filtros-select w-100 inputs-de-filtro">

                                <select name="usuario" id="usuario_filtro" class="content-filtros-select select_2_view w-100 inputs-de-filtro">
                                    <option value="">Invitado</option>
                                    @foreach ($usuarios as $invitado)
                                        <option value="{{$invitado->id}}">{{$invitado->name}}</option>
                                    @endforeach
                                </select>

                                <select name="ordenar" id="ordenar" class="content-filtros-select select_2_view w-100 inputs-de-filtro">
                                    <option value="">Ordenar</option>
                                    <option value="ASC">De menor a mayor</option>
                                    <option value="DESC">De mayor a menor</option>
                                </select>

                                <button type="button" id="reset_filtro" class="inputs-de-filtro" onclick="restablecerFiltro()"><i class="fas fa-broom"></i></button>
                            </div>
                        @endif
                        @if ($sesiones_redirec === '2')
                            <div style="display: none !important;" class="col-12 d-flex buttons-agendas-filtros justify-content-around align-items-center content-filtros-select content-filtro-estado">
                                <p>No existe filtro</p>
                            </div>
                        @endif
                    </div>
                    @if($sesiones_redirec === '1' )
                        <div class="container-fluid row d-flex w-100">
                            <div class="container-fluid row w-100 d-flex w-100">
                                <div class="w-100 justify-content-center">
                                    <div id="atendidasContainerData" class="w-100 contenido-cards"></div>
                                    <div class="container contenido-paginador-footer" style="display: grid; place-content: center; place-items: center;">
                                        <div class="d-flex justify-content-center align-items-center text-center contenedor-pagina-export">
                                            <div class="content-paginacion" id="pagination_atendidas"> </div>
                                            <div id="atendidas-button-exportar" class="btn-exportar"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="container-fluid row d-flex w-100">
                            <div class="container-fluid row w-100 d-flex w-100">
                                <div class="container-fluid row w-100 d-flex w-100">
                                    <div class="w-100 justify-content-center contenido-cards contenido-cards-param-atendidas">
                                        <div id="pendientesContainerData" class="w-100 contenido-cards"></div>
                                        <div class="container contenido-paginador-footer d-flex justify-content-between" style="overflow-x: auto !important;">
                                            <div class="content-paginacion" id="content-paginador-pendientes"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </section>
        <section class="col-lg-2 w-100 mt-2 first-content-agendas">
            <nav class="w-100">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a href="{{ route('anfitrion.agendas.general_vista') }}" class="btn btn-outline-success estilos-btns-tabs"> Ir a Espacios <i class="fas fa-arrow-right"></i></a>
                </div>
            </nav>
        </section>
    </section>
    {{-- Inicio del contenido para agregar eventos individuales OTO--}}
    <div class="modal fade modal-right" id="agendarEventoModal" tabindex="-1" aria-labelledby="agendarEventoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-contenido-agendar modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
            <form method="post" class="saveAgendaIndividual" id="formAddAgendaIndividual">
                @csrf
                @method('POST')
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
                        <div> {{--id="error_fecha_hora_meet"--}}
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

    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilo_estado_espacios.css')}}">
    <link rel="stylesheet" href="{{asset('css/modal-save-evidencia.css')}}">
    <link rel="stylesheet" href="{{asset('css/modal_agendar_todos.css')}}">

    <style>
        :root{
            --color-ver: #007A3E;
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
        let buttonActivo;

        $(function(){
            // Codigo para los estados de las agendas:
            let buttonActivo = $('.buttons-select.active').val();

            if(buttonActivo === 'A'){
                DataAtendidas();
            }else if(buttonActivo === 'P'){
                DataPendientes();
            }

            // Realizar cambios en los inputs y llamar a las funciones correspondientes
            $('#area_filtro, #fecha_filtro, #usuario_filtro, #ordenar').change(function() {
                buttonActivo = $('.buttons-select.active').val();
                if(buttonActivo === 'A'){
                    DataAtendidas();
                } else if(buttonActivo === 'P'){
                    DataPendientes();
                }
            });

            $('#atendidas_redireccion').on('click', function(){
                window.location.href = '/anfitrion/agendas/estado_espacios?sesiones=1';
            });

            $('#pendientes_redireccion').on('click', function(){
                window.location.href = '/anfitrion/agendas/estado_espacios?sesiones=2';
            });
        });

        function restablecerFiltro(){
            $('#area_filtro').val('').change();
            $('#fecha_filtro').val('');
            $('#usuario_filtro').val('').change();
            $('#ordenar').val('');
        }
        function exportarAtendidas(){
            let area  = $('#area_filtro').val();
            let fecha = $('#fecha_filtro').val();
            let invit = $('#usuario_filtro').val();
            // Realizar la solicitud AJAX
            $.ajax({
                url: "{{ route('agenda.exportar.atendidas_general_excel') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    area: area,
                    fecha: fecha,
                    invitado: invit
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response) {
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(response);
                    link.download = 'Agendas_atendidas_general.xlsx';
                    link.click();
                    Swal.fire('Exportado', 'El archivo ha sido exportado correctamente', 'success');
                },
                error: function () {
                    Swal.fire('Error', 'Los datos no pudieron ser exportados.', 'error');
                }
            });
        }

        function DataAtendidas(url = null) {
                let area_filtro = $('#area_filtro').val();
                let fecha_filtro = $('#fecha_filtro').val();
                let usuario_filtro = $('#usuario_filtro').val();
                let ordenar = $('#ordenar').val();
                let requestUrl = url ? url : "{{ route('agenda.espacio.estado_atendidas') }}";

                $.ajax({
                    url: requestUrl,
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        valor: buttonActivo,
                        area: area_filtro,
                        fecha: fecha_filtro,
                        usuario: usuario_filtro,
                        orden: ordenar
                    },
                    success: function(response) {
                        if (response.atendidas && Array.isArray(response.atendidas)) {
                            if (response.atendidas.length > 0) {
                                let atendidasHtml = '';
                                response.atendidas_por_pagina.data.forEach(function(data) {
                                    let agenda_id = data.agenda_id;
                                    let fechaHora = data.fecha_hora;
                                    let tipo_reu = data.tipo_reunion;
                                    let fecha_actually = new Date();
                                    let fechaHoraServidor = new Date(fechaHora);
                                    let diaSemana   = fechaHoraServidor.toLocaleDateString('es-ES', { weekday: 'long' });
                                    let diaMes      = fechaHoraServidor.getDate();
                                    let mes         = fechaHoraServidor.toLocaleDateString('es-ES', { month: 'long' });
                                    let anio        = fechaHoraServidor.getFullYear();
                                    let hora        = fechaHoraServidor.toLocaleTimeString('es-ES', { hour: 'numeric', minute: '2-digit' });
                                    // Formatear la fecha y hora
                                    let fechaHoraFormateada = diaSemana + ', ' + diaMes + ' de ' + mes + ' - ' + anio + ' | ' + hora;


                                    let invitados = data.invitado.split(',');
                                    let primerosInvitados = invitados.slice(0, 2);
                                    let restantesInvitados = invitados.slice(2);

                                    let dropdownHTML = '';
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

                                    let primerosInvitadosHTML = '';
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
                                                        <div class="fecha_hora_meet row d-flex justify-content-between">
                                                            <div class="col"><p style="margin-left: 5px;">${fechaHoraFormateada}</p></div>
                                                            <div class="col content-right"<span>ID: ${data.agenda_id}</span></div>
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
                                                            <div class="col">
                                                                <strong class="text-soportes">Soportes: </strong>
                                                                <p class="text-soportes-desc">Sin soportes</p>
                                                            </div>

                                                            <div class="col content-right">
                                                                <a href="javascript:void(0)" class="btn btn-outline-light btn-subir-evidencia-card ver-agenda-para-subir-evidencia text-meet-cercana" data-url="/anfitrion/agendas/ver_datos_agenda_evidencia/${data.agenda_id}">Subir reporte <i class="fas fa-cloud-upload-alt"></i></a>
                                                                ${tipo_reu === 'max 10' ? '<a href="javascript:void(0)" class="btn btn-success cerrar_agenda" data-url="/anfitrion/agendas/ver_data_culminacion/'+ data.agenda_id +'" style="margin-left: 2px;">Cerrar <i class="fas fa-times"></i></a>' : ''}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        `;
                                });
                                $('#atendidasContainerData').html(atendidasHtml);

                                // Generar el paginador
                                if (response.atendidas_por_pagina && response.atendidas_por_pagina.links) {
                                    let atendidasPaginationHtml = '<ul class="pagination justify-content-center btns-paginadores-style">';
                                    response.atendidas_por_pagina.links.forEach(function(link) {
                                        atendidasPaginationHtml += '<li class="page-item ' + (link.active ? 'active' : '') + '"><a class="page-link" href="#" data-url="' + link.url + '">' + link.label + '</a></li>';
                                    });
                                    atendidasPaginationHtml += '</ul>';
                                    $('#pagination_atendidas').html(atendidasPaginationHtml);

                                    // Manejar clics en los enlaces del paginador
                                    $('#pagination_atendidas .page-link').click(function(e) {
                                        e.preventDefault();
                                        let nextPageUrl = $(this).data('url');
                                        DataAtendidas(nextPageUrl);
                                    });
                                }

                                // Generar el botón de exportar solo si el botón de "atendidas" está activo
                                if (buttonActivo === 'A') {
                                    let exportarBTN = '<div><a class="btn btn-outline-success btn-exportar-agendas" id="atendidas-button-exportar" onclick="exportarAtendidas()" href="#">Exportar <i class="fas fa-file-excel"></i></a></div>';
                                    $('#atendidas-button-exportar').html(exportarBTN);
                                }
                            } else {
                                $('#atendidasContainerData').html('<div class="card shadow mt-2 mb-2"><p class="text-danger">Aún no cuentas con agendas atendidas</p></div>');
                                $('#pagination_atendidas').html('');
                                $('#atendidas-button-exportar').html('');
                            }
                        } else {
                            $('#atendidasContainerData').html('<p class="text-danger">No hay agendas atendidas disponibles.</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
        }

        function DataPendientes(){
                $.ajax({
                    url: "{{ route('agenda.espacio.estado_pendientes') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                    },
                    success: function(response) {
                        let pendientesHtml = '';
                        if(response && response.espacios_de_usuario_log && response.espacios_de_usuario_log.length > 0) {
                            response.espacios_de_usuario_log.forEach(espacio => {
                                let id_espacio_ind = espacio.espacio_id;
                                let espacio_ind = espacio.espacio_name;
                                let descripcion_ind = espacio.espacio_descripcion;
                                let tipo_reunion_ind = espacio.tipo_reunion;

                                if(response.usuarios_de_area_individual && response.usuarios_de_area_individual.length > 0){

                                    response.usuarios_de_area_individual.forEach(function(user) {
                                        let user_id_ind   = user.user_id;
                                        let user_name_ind = user.name;
                                        let area_id_ind   = user.area_id;
                                        let area_name_ind = user.area_name;
                                        // Construir la card para cada usuario individual
                                        pendientesHtml += `
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="grupo-encabezado">
                                                                <strong class="text-espacio-cercana">${espacio_ind}</strong>
                                                            </div>
                                                            <div class="grupo-encabezado">
                                                                <a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes agenda_indiv_mod_submit" id="agenda_individual" data-url="/anfitrion/agendas/obtener_datos/${user_id_ind}/${id_espacio_ind}/${area_id_ind}">Agendar <i class="far fa-clock"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="invitado">
                                                            <p>${descripcion_ind}</p>
                                                        </div>
                                                        <div class="d-flex">
                                                            <strong class="ms-2 text-area-cercana">Area: </strong>
                                                            <span class="ms-2 text-area-espacio">${area_name_ind}</span>
                                                        </div>
                                                        <div class="invitado mt-2">
                                                            <i class="fas fa-user-circle icon-invitados"></i>
                                                            <span class="span-invitados">${user_name_ind}</span>
                                                        </div>
                                                    </div>
                                                </div>`;
                                    });
                                }
                            });
                        }

                        if (response && response.espacios_de_usuario_log_grupal && response.espacios_de_usuario_log_grupal.length > 0) {

                            response.espacios_de_usuario_log_grupal.forEach(grupo => {
                                let id_espacio_grup         = grupo.espacio_id;
                                let espacio_grup            = grupo.espacio_name;
                                let descripcion_grup        = grupo.espacio_descripcion;
                                let tipo_reunion_grupo      = grupo.tipo_reunion;
                                let area_id_grup            = grupo.area_id;
                                let areas_grup              = grupo.area_name.split(',');

                                if(response.usuarios_de_area_primario || response.usuarios_de_area_primario.length > 0 || response.usuarios_de_area_country || response.usuarios_de_area_country.length > 0 || response.usuarios_de_area_compras || response.usuarios_de_area_compras.length > 0 || response.usuarios_de_area_merco || response.usuarios_de_area_merco.length > 0 || response.usuarios_de_area_indicadores || response.usuarios_de_area_indicadores.length > 0 || response.usuarios_de_area_sostenibilidad || response.usuarios_de_area_sostenibilidad.length > 0){

                                    /**********************************************************************************/
                                    let usersIdsPrimario        = response.usuarios_de_area_primario.map(userP => userP.user_id);
                                    let usersIdsCountry         = response.usuarios_de_area_country.map(userc => userc.user_id);
                                    let usersIdsCompras         = response.usuarios_de_area_compras.map(userCO => userCO.user_id);
                                    let usersIdsMerco           = response.usuarios_de_area_merco.map(userm => userm.user_id);
                                    let usersIdsIndicadores     = response.usuarios_de_area_indicadores.map(userI => userI.user_id);
                                    let usersIdsSostenibilidad  = response.usuarios_de_area_sostenibilidad.map(userS => userS.user_id);

                                    /**********************************************************************************/
                                    let usersPrimarioHTML = '';
                                    let primerosInvitadosPrimarioHTML = '';
                                    response.usuarios_de_area_primario.forEach(function(data) {
                                        let invitadosPrimario = data.name.split(',');
                                        let primerosInvitadosPrimario = invitadosPrimario.slice(0, 2);
                                        let restanteInvitadoPrimario = invitadosPrimario.slice(2);
                                        if (restanteInvitadoPrimario.length > 0) {
                                            usersPrimarioHTML += `
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButtonPrimario" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ver más</button>
                                                   <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonPrimario">
                                            `;
                                            restanteInvitadoPrimario.forEach(function(invitado) {
                                                usersPrimarioHTML += `<a class="dropdown-item" href="#">${invitado}</a>`;
                                            });
                                            usersPrimarioHTML += '</div></div>';
                                        }
                                        primerosInvitadosPrimario.forEach(function(invitado) {
                                            primerosInvitadosPrimarioHTML += `<span class="span-invitados" style="margin: 2px;">${invitado}</span>`;
                                        });
                                    });
                                    /**********************************************************************************/
                                    let usersCountryHTML = '';
                                    let primerosInvitadosCountryHTML = '';
                                    response.usuarios_de_area_country.forEach(function(data) {
                                        let invitadosCountry = data.name.split(',');
                                        let primerosInvitadosCountry = invitadosCountry.slice(0, 2);
                                        let restanteInvitadoCountry = invitadosCountry.slice(2);
                                        if (restanteInvitadoCountry.length > 0) {
                                            usersCountryHTML += `
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButtonCountry" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ver más</button>
                                                   <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonCountry">
                                            `;
                                            restanteInvitadoCountry.forEach(function(invitado) {
                                                usersCountryHTML += `<a class="dropdown-item" href="#">${invitado}</a>`;
                                            });
                                            usersCountryHTML += '</div></div>';
                                        }
                                        primerosInvitadosCountry.forEach(function(invitado) {
                                            primerosInvitadosCountryHTML += `<span class="span-invitados" style="margin: 2px;">${invitado}</span>`;
                                        });
                                    });
                                    /**********************************************************************************/
                                    let usersComprasHTML = '';
                                    let primerosInvitadosComprasHTML = '';
                                    response.usuarios_de_area_compras.forEach(function(data) {
                                        let invitadosCompras = data.name.split(',');
                                        let primerosInvitadosCompras = invitadosCompras.slice(0, 2);
                                        let restanteInvitadoCompras = invitadosCompras.slice(2);
                                        if (restanteInvitadoCompras.length > 0) {
                                            usersComprasHTML += `
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButtonCompras" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ver más</button>
                                                   <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonCompras">
                                            `;
                                            restanteInvitadoCompras.forEach(function(invitado) {
                                                usersComprasHTML += `<a class="dropdown-item" href="#">${invitado}</a>`;
                                            });
                                            usersComprasHTML += '</div></div>';
                                        }
                                        primerosInvitadosCompras.forEach(function(invitado) {
                                            primerosInvitadosComprasHTML += `<span class="span-invitados" style="margin: 2px;">${invitado}</span>`;
                                        });
                                    });
                                    /**********************************************************************************/
                                    let usersMercoHTML = '';
                                    let primerosInvitadosMercoHTML = '';
                                    response.usuarios_de_area_merco.forEach(function(data) {
                                        let invitadosMerco = data.name.split(',');
                                        let primerosInvitadosMerco = invitadosMerco.slice(0, 2);
                                        let restanteInvitadoMerco = invitadosMerco.slice(2);
                                        if (restanteInvitadoMerco.length > 0) {
                                            usersMercoHTML += `
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButtonMerco" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ver más</button>
                                                   <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonMerco">
                                            `;
                                            restanteInvitadoMerco.forEach(function(invitado) {
                                                usersMercoHTML += `<a class="dropdown-item" href="#">${invitado}</a>`;
                                            });
                                            usersMercoHTML += '</div></div>';
                                        }
                                        primerosInvitadosMerco.forEach(function(invitado) {
                                            primerosInvitadosMercoHTML += `<span class="span-invitados" style="margin: 2px;">${invitado}</span>`;
                                        });
                                    });
                                    /**********************************************************************************/
                                    let usersIndicadoresHTML = '';
                                    let primerosInvitadosIndicadoresHTML = '';
                                    response.usuarios_de_area_indicadores.forEach(function(data) {
                                        let invitadosIndicadores = data.name.split(',');
                                        let primerosInvitadosIndicadores = invitadosIndicadores.slice(0, 2);
                                        let restanteInvitadoIndicadores = invitadosIndicadores.slice(2);
                                        if (restanteInvitadoIndicadores.length > 0) {
                                            usersIndicadoresHTML += `
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButtonIndicadores" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ver más</button>
                                                   <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonIndicadores">
                                            `;
                                            restanteInvitadoIndicadores.forEach(function(invitado) {
                                                usersIndicadoresHTML += `<a class="dropdown-item" href="#">${invitado}</a>`;
                                            });
                                            usersIndicadoresHTML += '</div></div>';
                                        }
                                        primerosInvitadosIndicadores.forEach(function(invitado) {
                                            primerosInvitadosIndicadoresHTML += `<span class="span-invitados" style="margin: 2px;">${invitado}</span>`;
                                        });
                                    })
                                    /**********************************************************************************/
                                    let usersSostenibilidadHTML = '';
                                    let primerosInvitadosSostenibilidadHTML = '';
                                    response.usuarios_de_area_sostenibilidad.forEach(function(data) {
                                        let invitadosSostenibilidad = data.name.split(',');
                                        let primerosInvitadosSostenibilidad = invitadosSostenibilidad.slice(0, 2);
                                        let restanteInvitadoSostenibilidad = invitadosSostenibilidad.slice(2);
                                        if (restanteInvitadoSostenibilidad.length > 0) {
                                            usersSostenibilidadHTML += `
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle dropdown-toggle-espacios" type="button" id="dropdownMenuButtonSostenibilidad" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ver más</button>
                                                   <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonSostenibilidad">
                                            `;
                                            restanteInvitadoSostenibilidad.forEach(function(invitado) {
                                                usersSostenibilidadHTML += `<a class="dropdown-item" href="#">${invitado}</a>`;
                                            });
                                            usersSostenibilidadHTML += '</div></div>';
                                        }
                                        primerosInvitadosSostenibilidad.forEach(function(invitado) {
                                            primerosInvitadosSostenibilidadHTML += `<span class="span-invitados" style="margin: 2px;">${invitado}</span>`;
                                        });
                                    });
                                    /**********************************************************************************/
                                    if(tipo_reunion_grupo === 'primario'){
                                        pendientesHtml += `
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="grupo-encabezado">
                                                            <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                        </div>
                                                        <div class="grupo-encabezado">
                                                            ${response.usuarios_de_area_primario.length > 0 ?
                                                                `<a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_primario" data-url="/anfitrion/agendas/obtener_datos_primario/${usersIdsPrimario.join(',')}/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>` :
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
                                                            ${areas_grup.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                       <i class="fas fa-user icon-invitados anvitados-icon" style="margin-right: 5px;"></i>
                                                       ${primerosInvitadosPrimarioHTML}
                                                       ${usersPrimarioHTML}
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
                                                            ${areas_grup.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                                    }else if(tipo_reunion_grupo === 'max 10'){
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
                                                            ${areas_grup.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                                    }else if(tipo_reunion_grupo === 'country'){
                                          pendientesHtml += `
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="grupo-encabezado">
                                                            <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                        </div>
                                                        <div class="grupo-encabezado">
                                                            ${response.usuarios_de_area_country.length>0 ?
                                                                `<a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="country_agendas" data-url="/anfitrion/agendas/obtener_datos_country/${usersIdsPrimario.join(',')}/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>` :
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
                                                            ${areas_grup.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <i class="fas fa-user icon-invitados anvitados-icon" style="margin-right: 5px;"></i><br>
                                                        ${primerosInvitadosCountryHTML}
                                                        ${usersCountryHTML}
                                                    </div>
                                                </div>
                                            </div>`;
                                    }else if(tipo_reunion_grupo === 'compras'){
                                        pendientesHtml += `
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="grupo-encabezado">
                                                            <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                        </div>
                                                        <div class="grupo-encabezado">
                                                            ${response.usuarios_de_area_compras.length>0 ?
                                                                `<a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_compras" data-url="/anfitrion/agendas/obtener_datos_compras/${usersIdsCompras.join(',')}/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>` :
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
                                                            ${areas_grup.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                       <i class="fas fa-user icon-invitados anvitados-icon" style="margin-right: 5px;"></i>
                                                       ${primerosInvitadosComprasHTML}
                                                       ${usersComprasHTML}
                                                    </div>
                                                </div>
                                            </div>`;
                                    }else if(tipo_reunion_grupo === 'merco'){
                                        pendientesHtml += `
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="grupo-encabezado">
                                                            <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                        </div>
                                                        <div class="grupo-encabezado">
                                                            ${response.usuarios_de_area_merco.length>0 ?
                                                                `<a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_merco" data-url="/anfitrion/agendas/obtener_datos_merco/${usersIdsMerco.join(',')}/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>` :
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
                                                            ${areas_grup.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                       <i class="fas fa-user icon-invitados anvitados-icon" style="margin-right: 5px;"></i>
                                                       ${primerosInvitadosMercoHTML}
                                                       ${usersMercoHTML}
                                                    </div>
                                                </div>
                                            </div>`;
                                    }else if(tipo_reunion_grupo === 'ranking'){
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
                                                            ${areas_grup.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                                    }else if(tipo_reunion_grupo === 'indicadores'){
                                         pendientesHtml += `
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="grupo-encabezado">
                                                            <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                        </div>
                                                        <div class="grupo-encabezado">
                                                            ${response.usuarios_de_area_indicadores.length>0 ?
                                                                `<a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_indicadores" data-url="/anfitrion/agendas/obtener_datos_indicadores/${usersIdsIndicadores.join(',')}/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>` :
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
                                                            ${areas_grup.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                       <i class="fas fa-user icon-invitados anvitados-icon" style="margin-right: 5px;"></i>
                                                       ${primerosInvitadosIndicadoresHTML}
                                                       ${usersIndicadoresHTML}
                                                    </div>
                                                </div>
                                            </div>`;
                                    }else if(tipo_reunion_grupo === 'retroalimentacion'){
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
                                                            ${areas_grup.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                                    }else if(tipo_reunion_grupo === 'sostenibilidad'){
                                         pendientesHtml += `
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="grupo-encabezado">
                                                            <strong class="text-espacio-cercana">${espacio_grup}</strong>
                                                        </div>
                                                        <div class="grupo-encabezado">
                                                            ${response.usuarios_de_area_sostenibilidad.length>0 ?
                                                                `<a href="javascript:void(0)" class="btn btn-success btn-agendar-pendientes" id="agendas_sostenibilidad" data-url="/anfitrion/agendas/obtener_datos_sostenibilidad/${usersIdsSostenibilidad.join(',')}/${id_espacio_grup}/${area_id_grup}">Agendar <i class="far fa-clock"></i></a>` :
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
                                                            ${areas_grup.map(area => `<span class="ms-2 text-area-espacio" style="margin-left: 5px; margin-bottom: 2px;">${area}</span>`).join('')}
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                       <i class="fas fa-user icon-invitados anvitados-icon" style="margin-right: 5px;"></i>
                                                       ${primerosInvitadosSostenibilidadHTML}
                                                       ${usersSostenibilidadHTML}
                                                    </div>
                                                </div>
                                            </div>`;
                                    }
                                }else{
                                    console.log('No hay data');
                                }
                            });
                        } else {
                            console.log('No hay espacios grupales o la data está vacía');
                        }
                        $('#pendientesContainerData').append(pendientesHtml);

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
        }

        /***********************************************************************************************/
        // Agendar eventos y subir evidencia #agenda_individual
        $(document).on('click', '#agenda_individual', function (event) {
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
                            // console.log('Response.status');
                            // console.log(response.status);
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
        });

        // Limpieza de los inputs del modal cuando se les minimice:
        var subirEvidenciaModal = document.getElementById('subirEvidenciaModal');

        subirEvidenciaModal.addEventListener('hidden.bs.modal', function () {
            var form = document.getElementById('sendEvidenciaAgenda');
            form.reset();
        });
    </script>
    <!--Incluye codigo para ver datos en los modales-->
    @include('oficial.parte_modal.script')

    <script>
        /***********************************************************************************************/
        // Subir evidencia de las agendas:
        $(document).on('click', '.ver-agenda-para-subir-evidencia', function (event){
            event.preventDefault();
            var url = $(this).data('url');
            // console.log('ID agenda enviada:', url.split('/').pop());

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
                        // $('#sendEvidenciaAgenda').empty();
                        // console.log('id_agenda: '+id_agenda+' \nFecha_hora: '+fecha_hora_meet);

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
            $('#fileInputsContainer').append('<div class="d-flex justify-content-between"><input type="file" name="agenda_evidencia_file[]" class="form-control col-10 mt-2 input-file-sub-soporte" required> <a class="btnQuitarFila col-2 mt-3"><i class="fas fa-times close-icon"></i></a></div>');
        });
        // Quita filas de inputs agregados en el container
        $('#fileInputsContainer').off('click', '.btnQuitarFila').on('click', '.btnQuitarFila', function(){
            $(this).parent().remove();
        });
        // Codigo js para subir la evidencia:
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
        /************************************************************************************************/
        // Cerrar las agendas
        $(document).on('click', '.cerrar_agenda', function (event){
            event.preventDefault();
            var url = $(this).data('url');
            // console.log('ID agenda enviada:', url.split('/').pop());
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
