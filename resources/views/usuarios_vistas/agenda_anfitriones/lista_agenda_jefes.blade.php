@extends('adminlte::page')

@section('title', 'Dashboard |Agenda de Jefes de Area')

@section('content_header')
    <h2>Administración de agendas programadas por Jefes de Área</h2>
@stop

@section('content')
    <main>
        <div class="d-flex justify-content-center align-items-center">
            <div class="card first-content-form d-flex col-lg-12 flex-lg-row flex-md-column flex-sm-column justify-content-around align-items-center">
                <div class="p-2">
                    <label class="text-title-cabec">Usuario:</label>
                    <span>{{ $username }}</span>
                </div>
                <div class="p-2">
                    <label class="text-title-cabec">Cargo:</label>
                    <span>
                        @foreach ($cargosUser as $userCargo)
                            {{ $userCargo->nombre }}
                            @if (!$loop->last)
                                -
                            @endif
                        @endforeach
                    </span>
                </div>
                <div class="p-2">
                    <label class="text-title-cabec">Area:</label>
                    <span>
                        @foreach ($areasUser as $userArea)
                            {{ $userArea->nombre }}
                            @if (!$loop->last)
                                -
                            @endif
                        @endforeach
                    </span>
                </div>
            </div>
        </div>

        <div class="d-flex mt-2 justify-content-center align-items-center">
            <div class="card d-flex col-lg-12 flex-lg-row flex-md-column flex-sm-column justify-content-around align-items-center">
                <div class="w-100">
                    <div class="row justify-content-center align-items-center mb-2 first-content-form">
                        <div class="col-lg-12 col-md-11 col-sm-11 second-content-form">
                            <form action="" class="p-2 rounded">
                                <div class="row d-flex">
                                    <div class="col-lg-4 col-md-6 col-sm-12 form-group">
                                        <label for="desde_input">Desde:</label>
                                        <input type="date" name="desde" id="desde_input" class="form-control">
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 form-group">
                                        <label for="hasta_input">Hasta:</label>
                                        <input type="date" name="hasta" id="hasta_input" class="form-control">
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 form-group">
                                        <label for="estado_input">Estado:</label>
                                        <select name="estado" id="estado_input" class="select2 form-control">
                                            <option value="">Seleccionar</option>
                                            <option value="pendiente">Pendiente</option>
                                            <option value="terminado">Terminado</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 form-group">
                                        <label for="espacio_input">Espacio:</label>
                                        <select name="espacio" id="espacio_input" class="select2 form-control">
                                            <option value="">Seleccionar espacio</option>
                                            @foreach ($espacios as $espacio)
                                                <option value="{{ $espacio->id }}">{{ $espacio->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12 form-group">
                                        <label for="area_input">Areas: </label>
                                        <select name="area" id="area_input" class="select2 form-control">
                                            <option value="">Seleccionar area</option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row  text-center d-flex justify-content-center">
                                    <button type="button" onclick="filtrarAgendas()" class="btn btn-outline-light btn-filter mr-2 col-3" id="buscar"><i class="fas fa-search mr-1"></i>Buscar</button>
                                    <button type="button" onclick="restablecerFiltro()" class="btn btn-outline-secondary col-3" id="reset"><i class="fas fa-trash-alt mr-1"></i>Limpiar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table id="example" class="display responsive nowrap datatable_agendas" style="width:100%">
                        <thead class="table-dark">
                        <tr>
                            <th>ID_agenda</th>
                            <th>ANFITRION</th>
                            <th>FECHA/H meet</th>
                            <th>AREA</th>
                            <th>ESPACIO</th>
                            <th>TIPO</th>
                            <th>ESTADO</th>
                            <th>LOCATION</th>
                            <th>CORPORATIVO</th>
                            <th>ACCION</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal para ver los datos de la agenda y enviar evidencia --}}
    <div class="modal fade" id="agendaModal" tabindex="-1" aria-labelledby="agendaModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            @csrf
            <form method="post" class="sendEvidenciaAgenda" id="sendEvidenciaAgenda" enctype="multipart/form-data">
                <div class="modal-content">
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
                            <div id="fileInputsContainer">
                                <input type="file" name="agenda_evidencia_file[]" id="agenda_evidencia_file" class="form-control" required>
                            </div>
                            <a type="button" id="addFileInput" class=" ml-2 mt-2">Agregar más archivos......</a>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-secondary button-evidencia">Subir evidencia <i class="fas fa-file ml-2" style="font-size: 20px; font-weight: bold;"></i> </button>
                        <button type="button" class="btn btn-danger button-evidencia" data-bs-dismiss="modal">Cerrar <i class="fas fa-times ml-2" style="font-size: 20px; font-weight: bold;" ></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- End modal show and send evidencia --}}

    {{-- Modal para ver los invitados de una agenda --}}
    <div class="modal fade" id="agendaInvitadosModal" tabindex="-1" aria-labelledby="agendaInvitadosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="agendaInvitadosModalLabel">Invitados de la agenda con ID: <span id="id_agenda_invitado" class="ml-2"></span> - <span id="fechaHoraMeet_agenda_invitado" class="ml-2"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Anfitrion</th>
                                <th>Invitado</th>
                                <th>Área</th>
                                <th>Espacio</th>
                            </tr>
                            </thead>
                            <tbody class="table-agenda-invitados">
                            <!-- Aquí se agregarán las filas de la tabla dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger button-evidencia" data-bs-dismiss="modal">Cerrar <i class="fas fa-times ml-2" style="font-size: 20px; font-weight: bold;" ></i></button>
                </div>
            </div>
        </div>
    </div>
    {{-- End modal para ver los invitados--}}

    {{-- Modal para ver las evidencias de la agenda y descargarlas --}}
    <div class="modal fade" id="agendaEvidenciaModal" tabindex="-1" aria-labelledby="agendaEvidenciaModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            @csrf
            <form method="post" class="sendEvidenciaAgenda" id="sendEvidenciaAgenda" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="agenciaEvidenciaModalLabel">Evidencias de la agenda: <span id="agenda_id" class="ml-2"></span> - <span id="fecha_hora_meet" class="ml-2"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row d-flex w-100">
                            <label>Archivos de evidencia:</label>
                            <div id="descargablesContainer"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger button-evidencia" data-bs-dismiss="modal">Cerrar <i class="fas fa-times ml-2" style="font-size: 20px; font-weight: bold;" ></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- End modal para ver las evidencias de la agenda y  descacrgarlas --}}


    {{-- Modal para ver los detalles de la agenda --}}
    <div class="modal fade modal-right" id="updateAgenda" tabindex="-1" aria-labelledby="updateAgendaModal" aria-hidden="true">
        <div class="modal-dialog modal-contenido" role="document">
            <form method="POST" action="{{ route('admin.agenda.anfitrion.update_agenda') }}" id="updateAgendaForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content modal-content-evidencia">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateAreaModal">Modificar Agenda con [ <strong>Id =></strong><span id="id_agenda_modal"></span> <strong>, Fecha/Hora meet => </strong> <span id="fecha_hora_meet_span"></span> ]</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <input type="hidden" name="id_agenda" id="modal_id_agenda" class="form-control">
                            <div class="form-group">
                                <label>Titulo: </label>
                                <input type="text" name="agenda_titulo" id="agenda_titulo" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Location: </label>
                                <input type="text" name="agenda_location" id="agenda_location" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>En este espacio podrás seleccionar otra fecha y hora para el evento:</label>
                                <input type="datetime-local" name="input_fecha_hora" id="input_fecha_hora" class="form-control fecha-hora-pasada">
                                <span id="input_fecha_hora_error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success button-evidencia" id="btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-light btn-modal-cancel button-evidencia" id="btnUpdatePais">Actualizar espacio <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- Modal para ver los detalles de la agendas --}}
@stop

@section('css')
    {{--para DataTables--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    {{--select 2--}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- Genera el token para AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- BoxIcons --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css')}}">

    {{--Estilo para los botones del modal:--}}
    <style>
        .button-evidencia{
            display: flex !important;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 200px; /*150*/
        }

        .first-content-form{
            background-color: #FFFFFF ;
            color: #007A3E; /*color: #252C2E;*/
            padding: 0;
            margin: 0;
        }
        .second-content-form{
            background-color: #FFFFFF ;
            color: #007A3E; /*color:  #252C2E;*/
            padding: 0;
            margin: 0;
        }
        .text-title-cabec, .btn-outline-light{
            color: #007A3E;
        }

        /*Para los icons de sweet alert*/
        .icon_swal_fire{
            border: none !important;
            width: 180px; height: 160px;
            display: grid;
            place-items: center;
            align-items: center;
        }
        /*Para el modal*/
        /*Estilos css para modal centrado 100vh: */
        .modal-right{
            display: none;
            position: fixed;
            padding-top: 20px;
            right: 0;
            top: 0;
            width: 100%;
            /*height: 100%;*/
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            overflow: auto;
        }
        .modal-contenido {
            position: relative;
            background-color: #fefefe;
            margin: 0 auto;
            border: 1px solid #888;
            /*height: 100%;*/
            width: 400px;
            display: grid;
            place-content: center;
            place-items: center;
        }
        .modal-content-evidencia .modal-body{
            display: grid;
            place-items: center;
            place-content: center;
        }
        .modal-header{
            color: #007A3E;
            background-color: #FFFFFF ;
        }
        .btn-modal-cancel,
        .btn-subir-evidencia-card{
            color: #007A3E;
        }
        .btn-modal-cancel:hover,
        .btn-subir-evidencia-card:hover{
            border-bottom: 1px solid #007A3E;
            border-right: 1px solid #007A3E;
        }
        /*Estilo de la tabla: */
        .table-dark{
            background-color: #007A3E !important;
            color: #FFFFFF !important;
        }
        .btn-filter{
            border-color: #007A3E;
        }
        /*Swal por encima del modal*/
        .swal2-container {
            z-index: 2001;
        }
    </style>

@stop

@section('js')
    {{--Para DataTableAs--}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    {{--PBootstrap 5--}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    {{--Select2--}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{--SweetAlert--}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{--BoxIcons--}}
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    <script type="text/javascript">
        $(function () {
            // Obtención de la fecha actual para filtro por DEFAULT al abrir o recargar la página:
            // DATETIME - LOCAL: var fecha_actual = new Date().toISOString().slice(0, 16);
            let fecha_actual = new Date().toISOString().split('T')[0];
            // Establecer la fecha actual en los campos de fecha del formulario
            $('#desde_input').val(fecha_actual);
            $('#hasta_input').val(fecha_actual);
            let estado = 'pendiente';
            $('#estado_input').val(estado);

            // Configuración de la tabla DataTable
            var table = $('.datatable_agendas').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 50,
                ajax: {
                    url: "/admin/agenda/anfitrion/lista",
                    type: "post",
                    headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
                    data: function(d){
                        d.fecha_desde = $('#desde_input').val();
                        d.fecha_hasta = $('#hasta_input').val();
                        d.espacio = $('#area_input').val();
                        d.area = $('#espacio_input').val();
                        d.estado = $('#estado_input').val();
                    }
                },
                columns: [
                    {data: 'agenda_id', name: 'agenda_id'},
                    {data: 'usuario_log', name: 'usuario_log'},
                    {data: 'fecha_hora', name: 'fecha_hora'},
                    {data: 'areas', name: 'areas'},
                    {data: 'espacio_nombre', name: 'espacio_nombre'},
                    {data: 'tipo', name: 'tipo'},
                    {data: 'estado', name: 'estado'},
                    {data: 'location', name: 'location'}, // ---------------------------
                    {data: 'corporativo_nombre', name: 'corporativo_nombre'},
                    { data: null, defaultContent: '', orderable: false, searchable: false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function(data, type, row, meta){
                            var links = '';
                            if (row.estado !== 'terminado') { // Para editar y eliminar la agenda:
                                links += `
                                    <a href="javascript:void(0)" data-url="/admin/agenda/anfitrion/edit_agenda/${row.agenda_id}" class="btn btn-outline-primary editar-agenda">
                                        <i style="color: orange;" class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" data-url="/admin/agenda/anfitrion/delete_agenda/${row.agenda_id}" class="btn btn-outline-primary eliminar-agenda">
                                        <i style="color: red;" class="fas fa-times-circle"></i></a>`;
                            }

                            return links;
                        },
                    },
                ],
                // Estilo agregado para el estado de la agenda [ pendiente / terminado ] :
                createdRow: function(row, data, dataIndex) {
                    if (data.estado === 'pendiente') {
                        $('td', row).eq(6).css('color', 'maroon');
                        $('td', row).eq(6).css('background-color', '#ffd6d6');
                    } else if (data.estado === 'terminado') {
                        $('td', row).eq(6).css('color', '#007A3E');
                        $('td', row).eq(6).css('background-color', '#c2f0c2');
                    }
                }
            });

            //SELECT2 --> USAGE
            $('.select2').select2({
                theme: "classic"
            });

            // Solicitud AJAX editar la agenda
            $(document).on('click', '.editar-agenda', function(event){
                event.preventDefault();

                let url = $(this).data('url');
                console.log('url: ',url);
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data){
                        //Mostrando los datos del espacio en el modal:
                        $('#id_agenda_modal').text(data.agenda.id);
                        $('#modal_id_agenda').val(data.agenda.id);
                        let id_agenda = $('#id_agenda_modal').val();
                        console.log('ID AGENDA ENCONTRADA EN MODAL');
                        // $('#id_agenda').val(data.agenda.id);
                        $('#agenda_titulo').val(data.agenda.summary);
                        $('#agenda_location').val(data.agenda.location);
                        $('#fecha_hora_meet_span').text(data.agenda.fecha_hora_meet);

                        $('#updateAgenda').modal('show');
                    },
                    error: function(error){
                        console.log('Error: ', error);
                    }
                });
            });
            // Solicitud AJAX para actualizar la agenda:
            $('#updateAgendaForm').submit(function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de guardar la agenda?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if(result.isConfirmed){
                        let formData = new FormData(this);
                        let id_agenda = $('#id_agenda').val();
                        console.log('AGENDA ID: ',id_agenda);

                        $.ajax({
                            url: $(this).attr('action'),
                            type: $(this).attr('method'),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success:function (response){
                                if (response.success) {
                                    console.log('Administración de la agenda guardada');
                                    $('#updateAgendaForm')[0].reset();

                                    Swal.fire({
                                        title: '¡Agenda actualizada!',
                                        text: 'La agenda ha sido actualizada correctamente.',
                                        iconHtml: '<img src="{{ asset('icons/icon_success.png') }}" class="icon_swal_fire">',
                                        showConfirmButton: true,
                                    }).then(() => {
                                        $('.datatable_agendas').DataTable().ajax.reload();
                                    });

                                    /*Swal.fire('¡Agenda actualizado!',
                                        'La agenda ha sido actualizado correctamente',
                                        'success'
                                    ).then(() => {
                                        $('.datatable_agendas').DataTable().ajax.reload();
                                    });*/

                                    $('#updateAgenda').modal('hide');
                                } else {
                                    if (response.errors) {
                                        $.each(response.errors, function (key, value) {
                                            var errorMessages = {
                                                'input_fecha_hora': 'Elija una fecha/hora diferente.',
                                            };
                                            var errorMessage = errorMessages[key] || value;
                                            // Mostrar el mensaje de error junto al input correspondiente
                                            $('#' + key + '_error').text(errorMessage);
                                        });
                                    }

                                    Swal.fire({
                                        title: '¡Agenda no actualizada!',
                                        text: 'Por favor, corrija el formulario',
                                        iconHtml: '<img src="{{ asset('icons/icon_cancel.png') }}" class="icon_swal_fire">',
                                        showConfirmButton: true,
                                    });
                                    Swal.fire('Error', 'Por favor corrige los errores en el formulario', 'error' );
                                }
                            },
                            error:function (error){
                                console.log('Error al guardar la administración de la agenda', error);
                                Swal.fire({
                                    title: '¡Agenda no actualizada!',
                                    text: 'Ocurrió un error al actualizar la agenda',
                                    iconHtml: '<img src="{{ asset('icons/icon_cancel.png') }}" class="icon_swal_fire">',
                                    showConfirmButton: true,
                                });
                                // Swal.fire('¡Administración no guardad!', 'Ocurrió un error al guardar la administración de la agenda', 'error' );
                            }
                        });
                    }
                });
            });

            // Solicitud AJAX para eliminar la agenda de agendas, GOOGLE, agenda_invitados:
            $(document).on('click', '.eliminar-agenda', function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de eliminar la agenda?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if(result.isConfirmed){
                        let url = $(this).data('url');
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success:function (response){
                                Swal.fire({
                                    title: '¡Agenda Eliminada!',
                                    text: 'La agenda ha sido eliminada correctamente.',
                                    iconHtml: '<img src="{{ asset('icons/icon_success.png') }}" class="icon_swal_fire">',
                                    showConfirmButton: true,
                                }).then(() => {
                                    $('.datatable_agendas').DataTable().ajax.reload();
                                });
                            },
                            error:function (error){
                                console.log('Error al eliminar la agenda', error);
                                Swal.fire({
                                    title: '¡Agenda no eliminada!',
                                    text: 'Se ha producido un error al eliminar la agenda',
                                    iconHtml: '<img src="{{ asset('icons/icon_cancel.png') }}" class="icon_swal_fire">',
                                    showConfirmButton: true,
                                });
                            }
                        });
                    }
                });
            });
            // Bloqueo de fechas anmteriores
            bloquearFechasPasadas('.fecha-hora-pasada');
        });

        function filtrarAgendas(){
            $('.datatable_agendas').DataTable().ajax.reload();
        }
        function restablecerFiltro(){
            $('#desde_input').val('');
            $('#hasta_input').val('');
            $('#area_input').val('').change();
            $('#espacio_input').val('').change();
            $('#estado_input').val('').change();

            filtrarAgendas();
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
    </script>
@stop
