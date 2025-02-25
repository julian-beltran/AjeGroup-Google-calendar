@extends('adminlte::page')

@section('title', 'Agenda | Usuarios invitados')

@section('content_header')
    <h2>Administración de agendas programadas para usuarios invitados</h2>
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
                <div class=" w-100">
                    <div class="row justify-content-center align-items-center mb-2 first-content-form">
                        <div class="col-lg-12 col-md-12 col-sm-12 second-content-form">
                            <form action="" class="p-2 rounded">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 form-group ml-3">
                                        <label for="estado_input">Estado:</label>
                                        <select name="estado" id="estado_input" class="select2 form-control">
                                            <option value="">Seleccionar</option>
                                            <option value="pendiente">Pendiente</option>
                                            <option value="terminado">Terminado</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 form-group ml-3">
                                        <label for="desde_input">Desde:</label>
                                        <input type="date" name="desde" id="desde_input" class="form-control">
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 form-group ml-3">
                                        <label for="hasta_input">Hasta:</label>
                                        <input type="date" name="hasta" id="hasta_input" class="form-control">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 form-group ml-3">
                                        <label for="espacio_input">Espacio</label>
                                        <select name="espacio" id="espacio_input" class="select2 form-control">
                                            <option value="">Seleccionar espacio</option>
                                            @foreach ($espacios as $espacio)
                                                <option value="{{ $espacio->id }}">{{ $espacio->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row text-center d-flex justify-content-center">
                                    <button type="submit" class="btn btn-outline-light mr-2 col-3" id="buscar"><i class="fas fa-search mr-1"></i>Buscar</button>
                                    <button type="reset" class="btn btn-outline-secondary col-3" id="reset"><i class="fas fa-trash-alt mr-1"></i>Limpiar</button>
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
                            <th>INVITADO</th>
                            <th>AREA</th>
                            <th>ESPACIO</th>
                            <th>ESTADO</th>
                            <th>LINK / MEET</th>
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
    <main class="d-flex justify-content-center align-items-center">
    </main>

    {{-- Modal para ver las evidencias de la agenda y descargarlas --}}
    <div class="modal fade" id="agendaEvidenciaModal" tabindex="-1" aria-labelledby="agendaEvidenciaModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            @csrf
            <form method="post" class="sendEvidenciaAgenda" id="sendEvidenciaAgenda" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="vehiculoModalLabel">Evidencias de la agenda: <span id="agenda_id" class="ml-2"></span> - <span id="fecha_hora_meet" class="ml-2"></span></h1>
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

    <style>
        main{
            padding: 0;
            margin: 0;
        }
        /*Estilos para los botones del modal*/
        .button-evidencia{
            display: flex !important;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 200px; /*150*/
        }


        .first-content-form{
            background-color: #D4FFCA ;
            color: #1F6C0D; /*color: #252C2E;*/
            border: 1px solid #1F6C0D;
            padding: 0;
            margin: 0;
        }
        .second-content-form{
            background-color: #D4FFCA ;
            color: #1F6C0D; /*color:  #252C2E;*/
            padding: 0;
            margin: 0;
        }
        .text-title-cabec, .btn-outline-light{
            color: #1F6C0D;
        }

        .table-dark{
            background-color: #11571D !important;
            color: #98FF80 !important;
        }
    </style>
    <link rel="stylesheet" href="{{asset('css/estilos_generales.css')}}">
@stop

@section('js')
    {{--Para DataTables--}}
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
            // Fecha actual para los filtros por default:
            let fecha_actual = new Date().toISOString().split('T')[0];
            let estado = 'pendiente';
            // muestra de fecha actual en los inputs de filtro
            $('#estado_input').val(estado);
            // $('#desde_input').val(fecha_actual);
            // $('#hasta_input').val(fecha_actual);

            function limpiarFormulario() {
                $('#nombre_archivo').val('');
                $('#fileInputsContainer').empty();
                $('#fileInputsContainer').append('<input type="file" name="agenda_evidencia_file[]" id="agenda_evidencia_file" class="form-control" required>');
            }

            function adjuntarEventos() {
                /*************************************************************************************************************************/
                //Para ver el modal de las evidencias de la agenda y descargarlas.
                $(document).on('click', '.download-evidencia-agenda', function (event) {
                    event.preventDefault();
                    var url = $(this).data('url');

                    $.ajax({
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        success: function(data) {
                            $('#agendaEvidenciaModal').modal('show');
                            let agenda = data.agenda;
                            let descargables = data.descargables;

                            // Mostrar los datos de la agenda en el modal
                            $('#agenda_id').text('ID: '+ agenda.id);
                            $('#fecha_hora_meet').text('Fecha/Hora meet: ' + agenda.fecha_hora_meet);


                            // Limpiar cualquier contenido anterior en el modal
                            $('#descargablesContainer').empty();

                            // Agregar los enlaces de descarga al modal
                            if (descargables.length > 0) {
                                $.each(descargables, function(index, descargable) {
                                    $('#descargablesContainer').append(`<a href="${descargable.url}" target="_blank">${descargable.nombre}</a><br>`);
                                });
                            } else {
                                $('#descargablesContainer').text('No se encontraron archivos.');
                            }
                        },
                        error: function(error) {
                            console.log('Error al obtener los detalles de la agenda:', error);
                        }
                    });
                });

                //Limpieza del formulario de datos descargables:
                $('#agendaEvidenciaModal').on('hidden.bs.modal', function () {
                    // Limpiar el contenido del modal
                    $('#agenda_id').empty();
                    $('#fecha_hora_meet').empty();
                    $('#descargablesContainer').empty();
                });

                //Solicitud AJAX para el filtro
                $('#buscar').on('click', function(event) {
                    event.preventDefault();
                    let estado = $('#estado_input').val();
                    let fecha_desde = $('#desde_input').val();
                    let fecha_hasta = $('#hasta_input').val();
                    let espacio = $('#espacio_input').val();

                    console.log('DATOS: ' +
                        '\nEstado: ' + estado +
                        '\nFecha desde: ' + fecha_desde +
                        '\nFecha hasta: ' + fecha_hasta +
                        '\nEspacio: ' + espacio
                    );
                    // Recarga el datatable al enviar los datos al controller
                    table.ajax.url("{{ route('agenda.programada.lista') }}?estado=" + estado + "&desde=" + fecha_desde + "&hasta=" + fecha_hasta + "&espacio=" + espacio).load(null, 'reload');

                    return false;
                });

                // Lipieza del form de busqueda
                $('#reset').on('click', function(){
                    $('#desde_input').val('');
                    $('#hasta_input').val('');
                    $('#area_input').val('');
                    $('#espacio_input').val('');
                    $('#estado_input').val();

                    //recarga el datatable
                    table.ajax.url("{{ route('agenda.programada.lista') }}").load(null, 'reload');

                    return false;
                });
                /*********************************************************************************************************/
                //SELECT2 --> USAGE
                $('.select2').select2({
                    theme: "classic"
                });

            }

            var table = $('.datatable_agendas').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('agenda.programada.lista') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'nombre_anfitrion', name: 'nombre_anfitrion'},
                    {data: 'fecha_hora', name: 'fecha_hora'},
                    {data: 'nombre_invitado', name: 'nombre_invitado'},
                    {data: 'area_nombre', name: 'area_nombre'},
                    {data: 'espacio_nombre', name: 'espacio_nombre'},
                    {data: 'estado', name: 'estado'},
                    {data: 'meet_link', name: 'meet_link'},
                    { data: null, defaultContent: '', orderable: false, searchable: false },
                ],
                columnDefs: [
                    {
                        targets: -2, // Aplicar al enlace de Google Meet (segundo desde el final)
                        render: function(data, type, row, meta){
                            var meetLink = row.meet_link;
                            if(meetLink){
                                //return '<a href="' + meetLink + '" target="_blank">'+meetLink+'</a>'; // Unirse a la reunión
                                return (row.estado === 'terminado') ? 'Ya se llevó a cabo.' : '<a href="' + meetLink + '" target="_blank">'+meetLink+'</a>'; // Unirse a la reunión
                            } else {
                                return 'N/A'; // Otra lógica si no hay enlace disponible
                            }
                        },
                    },
                    {
                        targets: -1, // Botón para ver las evidencias de la agenda
                        render: function(data, type, row, meta){
                            var links = '';
                            links += ' <a href="javascript:void(0)" class="btn btn-outline-primary download-evidencia-agenda" data-url="/admin/agenda/invitados/download/' + row.id + '"><i class="fas fa-eye"></i></a>';
                            return links;
                        },
                    },
                ],
                createdRow: function(row, data, dataIndex){ // Estilo agregado para el estado de la agenda
                    if(data.estado === 'pendiente'){
                        $('td', row).eq(6).css('color', 'maroon');
                        $('td', row).eq(6).css('background-color', '#ffd6d6');
                    }else if(data.estado === 'terminado'){
                        $('td', row).eq(6).css('color', 'darkgreen');
                        $('td', row).eq(6).css('background-color', '#c2f0c2');
                    }
                },

                drawCallback: function() {
                    adjuntarEventos();
                }
            });

            adjuntarEventos();
        });
    </script>
@stop

