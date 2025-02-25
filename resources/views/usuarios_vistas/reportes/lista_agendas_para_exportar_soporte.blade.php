@extends('adminlte::page')

@section('title', 'Dashboard | Exportar agendas')

@section('content_header')
    <h6>Administración de agendas para exportar</h6>
@stop

@section('content')
    <main class="container">
        <div class="d-flex justify-content-center align-items-center">
            <div class="card d-flex col-lg-12 col-md-11 col-sm-11 flex-lg-row flex-md-column flex-sm-column justify-content-around align-items-center">
                <div class="container p-2 w-100">
                    <div class="row justify-content-center align-items-center mb-2 first-content-form">
                        <div class="col-lg-12 col-md-10 col-sm-12 second-content-form">
                            <form action="" class="p-4 rounded">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-lg-4 col-md-3 col-sm-8 text-center mb-2">
                                        <select name="area" id="area_input" class="select2 form-control input-filtro-form">
                                            <option value="">Seleccionar area</option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}" > {{ $area->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-3 col-sm-8 text-center mb-2">
                                        <select name="espacio" id="espacio_input" class="select2 form-control input-filtro-form">
                                            <option value="">Seleccionar espacio</option>
                                            @foreach ($espacios as $espacio)
                                                <option value="{{ $espacio->id }}" > {{ $espacio->nombre }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-5 col-md-4 col-sm-12 col-xs-12 text-center mb-2">
                                        <label for="fecha_desde_input">Fecha desde:</label>
                                        <input type="date" name="fecha_desde_input" id="fecha_desde_input" class="form-control input-filtro-form">
                                    </div>
                                    <div class="col-lg-5 col-md-4 col-sm-12 col-xs-12 text-center mb-2">
                                        <label for="fecha_hasta_input">Fecha hasta:</label>
                                        <input type="date" name="fecha_hasta_input" id="fecha_hasta_input" class="form-control input-filtro-form">
                                    </div>
                                    <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12 text-center mb-2">
                                        <label for="estado_input">Estado:</label>
                                        <select name="estado" id="estado_input" class="select2 form-control">
                                            <option value="">Seleccionar</option>
                                            <option value="pendiente">Pendiente</option>
                                            <option value="terminado">Terminado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-3 col-sm-12 text-center mb-2">
                                        <button type="submit" class="btn btn-outline-light btn-block" id="buscar"><i class="fas fa-search mr-1"></i>Buscar</button>
                                    </div>
                                    <div class="col-md-3 col-sm-12 text-center mb-2">
                                        <button type="reset" class="btn btn-outline-secondary btn-block" id="reset"><i class="fas fa-trash-alt mr-1"></i>Limpiar</button>
                                    </div>
                                    <div class="col-md-3 col-sm-12 text-center mb-2">
                                        <button type="button" class="btn btn-outline-success btn-block" id="exportExcel"><i class="far fa-file-excel mr-1"></i>Excel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table id="example" class="display responsive nowrap datatable_agendas_exportar" style="width:100%">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>AREA</th>
                            <th>ESPACIO</th>
                            <th>FECHA/HORA MEET</th>
                            <th>ANFITRION</th>
                            <th>INVITADO</th>
                            <th>ESTADO</th>
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

    {{-- Modal para ver las evidencias de la agenda y descargarlas --}}
    <div class="modal fade modal-right" id="agendaEvidenciaModal" tabindex="-1" aria-labelledby="agendaEvidenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-contenido">
            @csrf
            <form method="post" class="sendEvidenciaAgenda" id="sendEvidenciaAgenda" enctype="multipart/form-data">
                <div class="modal-content modal-content-evidencia">
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
                        <button type="button" class="btn btn btn-outline-light btn-modal-cancel button-evidencia" data-bs-dismiss="modal">Cerrar <i class="fas fa-times ml-2" style="font-size: 20px; font-weight: bold;" ></i></button>
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
    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css')}}">
    <link rel="stylesheet" href="{{asset('css/modal-save-evidencia.css')}}">
    <style>
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
        .btn-outline-light{
            color: #1F6C0D;
        }
        .table-dark{
            background-color: #11571D !important;
            color: #98FF80 !important;
        }

    </style>
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

            //valores para filtro por default:
            let estado = 'terminado';
            $('#estado_input').val(estado);


            var table = $('.datatable_agendas_exportar').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('agenda.exportar.lista') }}",
                columns: [
                    {data: 'agenda_id', name: 'agenda_id'},
                    {data: 'area_nombre', name: 'area_nombre'},
                    {data: 'espacio', name: 'espacio'},
                    {data: 'fecha_hora', name: 'fecha_hora'},
                    {data: 'anfitrion', name: 'anfitrion'},
                    {data: 'invitado', name: 'invitado'},
                    {data: 'estado', name: 'estado'},
                    { data: null, defaultContent: '', orderable: false, searchable: false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function(data, type, row, meta){
                            var links = '';
                            links += ' <a href="javascript:void(0)" id="ver_evidencia" data-url="/admin/export/ver_evidencia_agenda/' + row.agenda_id + '"  class="btn btn-outline-primary download-evidencia-agenda"><i class="fas fa-eye"></i></a>';
                            return links;
                        },
                    },
                ],
                // Estilo agregado para el estado de la agenda [ pendiente / terminado ] :
                createdRow: function(row, data, dataIndex) {
                    if (data.estado === 'pendiente') {
                        $('td', row).eq(6).css('color', '#BA000B');
                        $('td', row).eq(6).css('background-color', '#FFE8E9');
                        $('td', row).eq(6).css('font-size', '24px');
                        $('td', row).eq(6).css('font-style', 'normal');
                        $('td', row).eq(6).css('font-weight', '400');
                        $('td', row).eq(6).css('line-height', '32px');
                        $('td', row).eq(6).css('text-transform', 'capitalize');
                    } else if (data.estado === 'terminado') {
                        $('td', row).eq(6).css('color', '#1F6C0D');
                        $('td', row).eq(6).css('background-color', '#D4FFCA');
                        $('td', row).eq(6).css('font-size', '24px');
                        $('td', row).eq(6).css('font-style', 'normal');
                        $('td', row).eq(6).css('font-weight', '400');
                        $('td', row).eq(6).css('line-height', '32px');
                        $('td', row).eq(6).css('text-transform', 'capitalize');
                    }
                }
            });

            /*********************************************************************************************************/
            //SELECT2 --> USAGE
            $('.select2').select2({
                theme: "classic"
            });

            /***************************************************************************************/
            //Busqueda de datos por formulario:
            $('#buscar').on('click', function(event){
                event.preventDefault();

                let area = $('#area_input').val();
                let espacio = $('#espacio_input').val();
                let fecha_desde = $('#fecha_desde_input').val();
                let fecha_hasta = $('#fecha_hasta_input').val();
                let estado = $('#estado_input').val();

                console.log('Datos:: '
                    +'\nArea: '    +area
                    +'\nEspacio: ' +espacio
                    +'\nDesde: '   +fecha_desde
                    +'\nHasta: '   +fecha_hasta
                    +'\nEstado: '  +estado
                );

                //Recarga el datatable al enviar los datos
                table.ajax.url("{{ route('agenda.exportar.lista') }}?area=" + area + "&espacio=" + espacio + "&fecha_desde=" + fecha_desde + "&fecha_hasta=" + fecha_hasta + "&estado=" + estado).load(null, 'reload');

                return false;
            });

            /***************************************************************************************/
            //Limpieza del formulario:
            $('#reset'). on('click', function(){
                $('#area_input').val('');
                $('#espacio_input').val('');
                $('#fecha_desde_input').val('');
                $('#fecha_hasta_input').val('');
                $('#estado_input').val('');

                table.ajax.url("{{ route('agenda.exportar.lista') }}").load(null, 'reload');

                return false;
            });

            /***************************************************************************************/
            // Ver los archivos de la agenda (descargable en un modal).
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

            // Limpiar el modal al minimizar
            $('#agendaEvidenciaModal').on('show.bs.modal', function (event) {
                // Limpiar los campos del modal$('#agenda_id').text('ID: '+ agenda.id);
                $('#agenda_id').empty();
                $('#fecha_hora_meet').empty();
                $('#descargablesContainer').empty();
            });
            /***************************************************************************************/
            //para realizar la exportacion de datos en formato excel:
            $('#exportExcel').on('click', function () {
                let area = $('#area_input').val();
                let espacio = $('#espacio_input').val();
                let fecha_desde = $('#fecha_desde_input').val();
                let fecha_hasta = $('#fecha_hasta_input').val();
                let estado = $('#estado_input').val();

                // Realizar la solicitud AJAX
                $.ajax({
                    url: "{{ route('agenda.exportar.excel') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { //se envían los datos para el filtro al controller
                        name: area,
                        espacio_name: espacio,
                        fecha_desde: fecha_desde,
                        fecha_hasta: fecha_hasta,
                        estado: estado
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response) {
                        // Crear un enlace para descargar el archivo .excel
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(response);
                        link.download = 'Agendas_Programadas.xlsx';
                        link.click();
                        Swal.fire('Exportado', 'El archivo ha sido exportado correctamente', 'success');
                    },
                    error: function () {
                        Swal.fire('Error', 'Los datos no pudieron ser exportados.', 'error');
                    }
                });
            });

        });
    </script>


@stop
