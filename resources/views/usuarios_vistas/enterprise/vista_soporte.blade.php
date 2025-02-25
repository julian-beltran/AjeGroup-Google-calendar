@extends('adminlte::page')

@section('title', 'Office Config')

@section('content_header')
@stop

@section('content')
    <div class="row justify-content-center align-items-center">
        <div class="row mt-2">
            <div class="card shadow mb-1 pb-2">
                <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active btn-hover" id="pais-tab" data-bs-toggle="tab" data-bs-target="#pais" type="button" role="tab" aria-controls="pais" aria-selected="true">Pais</button>
                    </li>
                    <li class="nav-item tabs-styles-btn" role="presentation">
                        <button class="nav-link btn-hover" id="corporativo-tab" data-bs-toggle="tab" data-bs-target="#corporativo" type="button" role="tab" aria-controls="corporativo" aria-selected="false">Corporativo</button>
                    </li>
                    <li class="nav-item tabs-styles-btn" role="presentation">
                        <button class="nav-link btn-hover" id="cargo-tab" data-bs-toggle="tab" data-bs-target="#cargo" type="button" role="tab" aria-controls="cargo" aria-selected="false">Cargo</button>
                    </li>
                    <li class="nav-item tabs-styles-btn" role="presentation">
                        <button class="nav-link btn-hover" id="area-tab" data-bs-toggle="tab" data-bs-target="#area" type="button" role="tab" aria-controls="area" aria-selected="false">Area</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="pais" role="tabpanel" aria-labelledby="pais-tab">
                        <div class="col-lg-12 col-md-10 col-sm-10 d-flex flex-wrap mt-4">
                            <div class="card col-lg-3 col-md-4 col-sm-12  pb-2">
                                <form class="savePais" id="formAddPais">
                                    @csrf
                                    <fieldset>
                                        <legend>Agregar País</legend>
                                        <div class="form-group">
                                            <label>Nombre de Pais</label>
                                            <input type="text" name="pais" id="pais" class="form-control">
                                            <span id="pais_error" class="text-danger"></span>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-outline-success w-100">Guardar datos</button>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                            <div class="card col-lg-8 col-md-8 col-sm-12 ml-2">
                                <table class="display responsive nowrap datatable_pais" style="width:100%">
                                    <thead class="table-dark table-cabecera">
                                        <tr>
                                            <td>ID</td>
                                            <td>PAIS</td>
                                            <td>ACTION</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="corporativo" role="tabpanel" aria-labelledby="corporativo-tab">
                        <div class="col-lg-12 col-md-10 col-sm-10 d-flex flex-wrap mt-4">
                            <div class="card col-lg-3 col-md-4 col-sm-12 pb-2">
                                <form class="saveCorporativo" id="formAddCorporativo">
                                    @csrf
                                    <fieldset>
                                        <legend>Agregar Corporativo</legend>
                                        <div class="form-group">
                                            <label></label>
                                            <select name="pais" id="pais" class="select2 w-100" style="width: 100%;">
                                                <option selected>Seleccionar Pais</option>
                                                @foreach($paises as $pais)
                                                    <option value="{{$pais->id}}">{{$pais->nombre}}</option>
                                                @endForeach
                                                <span id="pais_error" class="text-danger"></span>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Nombre del Corporativo</label>
                                            <input type="text" name="corporativo" id="corporativo" class="form-control">
                                            <span id="corporativo_error" class="text-danger"></span>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-outline-success w-100">Guardar datos</button>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                            <div class="card col-lg-8 col-md-8 col-sm-12 ml-2">
                                <table class="display responsive nowrap datatable_corporativo" style="width:100%">
                                    <thead class="table-dark">
                                    <tr>
                                        <td>ID</td>
                                        <td>CORPORATIVO</td>
                                        <td>PAIS</td>
                                        <td>ACTION</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="cargo" role="tabpanel" aria-labelledby="cargo-tab">
                        <div class="col-lg-12 col-md-10 col-sm-10 d-flex flex-wrap mt-4">
                            <div class="card col-lg-3 col-md-12 col-12 pb-2">
                                <form class="saveCargo" id="formAddCargo">
                                    @csrf
                                    <fieldset>
                                        <legend>Agregar Cargo</legend>
                                        <div class="form-group">
                                            <label>Nombre del Cargo</label>
                                            <input type="text" name="cargo" id="cargo" class="form-control">
                                            <span id="cargo_error" class="text-danger"></span>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-outline-success w-100">Guardar datos</button>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                            <div class="card col-lg-8 col-md-12 col-12 ml-2">
                                <table class="display responsive nowrap datatable_cargo" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <td>ID</td>
                                            <td>CARGO</td>
                                            <td>ACTION</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="area" role="tabpanel" aria-labelledby="area-tab">
                        <div class="col-lg-12 col-md-10 col-sm-10 d-flex flex-wrap mt-4">
                            <div class="card col-lg-3 col-md-12 col-12 pb-2">
                                <form class="saveArea" id="formAddArea">
                                    @csrf
                                    <fieldset>
                                        <legend>Agregar Area</legend>
                                        <div class="form-group">
                                            <label>Nombre del Area</label>
                                            <input type="text" name="area" id="area" class="form-control">
                                            <span id="area_error" class="text-danger"></span>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-outline-success w-100">Guardar datos</button>
                                        </div>
                                    </fieldset>
                                </form>
                            </div>
                            <div class="card col-lg-8 col-md-12 col-12 ml-2">
                                <table class="display responsive nowrap datatable_area" style="width:100%" >
                                    <thead class="table-dark">
                                        <tr>
                                            <td>ID</td>
                                            <td>AREA</td>
                                            <td>ACTION</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Contenido de modal para editar campos--}}
    <div class="modal fade modal-right" id="updatePais" tabindex="-1" aria-labelledby="updatePaisModal" aria-hidden="true">
        <div class="modal-dialog modal-contenido modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
            <form method="post" action="{{ route('admin.enterprise.update_pais') }}" id="updatePaisForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content modal-content-evidencia">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updatePaisModal">Modificar Pais con [ <strong>Id =></strong><span id="id_pais_modal"></span> <strong>, Nombre => </strong> <span id="pais_nombre"></span> ]</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body justify-content-center">
                        <div class="col-10">
                            <input type="hidden" name="id_pais" id="id_pais" class="form-control">
                            <div class="form-group">
                                <label for="nombre_espacio">Nombre: </label>
                                <input type="text" name="nombre_input" id="nombre_input" class="form-control">
                                <span id="nombre_input_error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success button-evidencia" id="btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-light btn-modal-cancel button-evidencia" id="btnUpdatePais">Actualizar País<i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--Contenido de modal para editar --}}
    <div class="modal fade modal-right" id="updateCorporativo" tabindex="-1" aria-labelledby="updateCorporativoModal" aria-hidden="true">
        <div class="modal-dialog modal-contenido modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
            <form method="post" action="{{ route('admin.enterprise.update_corporativo') }}" id="updateCorporativoForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content modal-content-evidencia">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateCorporativoModal">Modificar Corporativo con [ <strong>Id =></strong><span id="id_corporativo_modal"></span> <strong>, Nombre => </strong> <span id="corporativo_nombre"></span> ]</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body justify-content-center">
                        <input type="hidden" name="id_corporativo" id="id_corporativo" class="form-control">
                        <div class="form-group col-12">
                            <label>Nombre: </label>
                            <input type="text" name="nombre_input_corporativo" id="nombre_input_corporativo" class="form-control">
                            <span id="nombre_input_corporativo_error" class="text-danger"></span>
                        </div>
                        <div class="card shadow col-12">
                            <label>Asignar País: </label>
                            <div id="containerPais"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success button-evidencia" id="btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-light btn-modal-cancel button-evidencia" id="btnUpdateCorporativo">Actualizar Corporativo <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--Contenido de modal para editar --}}
    <div class="modal fade modal-right" id="updateCargo" tabindex="-1" aria-labelledby="updateCargoModal" aria-hidden="true">
        <div class="modal-dialog modal-contenido modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
            <form method="post" action="{{ route('admin.enterprise.update_cargo') }}" id="updateCargoForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content  modal-content-evidencia">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateCargoModal">Modificar Cargo con [ <strong>Id =></strong><span id="id_cargo_modal"></span> <strong>, Nombre => </strong> <span id="cargo_nombre"></span> ]</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body justify-content-center">
                        <div class="col-12">
                            <input type="hidden" name="id_cargo" id="id_cargo" class="form-control">
                            <div class="form-group">
                                <label for="nombre_espacio">Nombre: </label>
                                <input type="text" name="nombre_input_cargo" id="nombre_input_cargo" class="form-control">
                                <span id="nombre_input_cargo_error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success button-evidencia" id="btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-light btn-modal-cancel button-evidencia" id="btnUpdatePais">Actualizar Cargo <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--Contenido de modal para editar --}}
    <div class="modal fade modal-right" id="updateArea" tabindex="-1" aria-labelledby="updateAreaModal" aria-hidden="true">
        <div class="modal-dialog modal-contenido modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
            <form method="post" action="{{ route('admin.enterprise.update_area') }}" id="updateAreaForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content modal-content-evidencia">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateAreaModal">Modificar Area con [ <strong>Id =></strong><span id="id_area_modal"></span> <strong>, Nombre => </strong> <span id="area_nombre"></span> ]</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body justify-content-center">
                        <div class="col-12 w-100">
                            <input type="hidden" name="id_area" id="id_area" class="form-control">
                            <div class="form-group">
                                <label for="nombre_espacio">Nombre: </label>
                                <input type="text" name="nombre_input_area" id="nombre_input_area" class="form-control">
                                <span id="nombre_input_area_error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success button-evidencia" id="btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-light btn-modal-cancel button-evidencia" id="btnUpdatePais">Actualizar Area <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--Final de contenido modal editar--}}
@stop

@section('css')
    {{-- Genera el token para AJAX
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    --}}
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

    {{--Estilos para el sidebar--}}
    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal-save-evidencia.css') }}">
    <style>
        /* Estilo para los tab activos*/
        .card .nav-link.active {
            background-color: green !important;
            color: #fff !important;
        }
        /* Estilos para los tabs inactivos */
        .card .nav-link:not(.active) {
            background-color: transparent;
            /*border: 1px solid green;*/
            color: green;
        }
        .btn-hover:hover{
            background-color: green !important;
            color: white !important;
        }

        .table-dark,
        .btn-outline-success{
            background-color: #11571D !important;
            color: #98FF80 !important;
        }
    </style>

@stop

@section('js')
    {{--
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    --}}
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
        $(function (){
            /****************************************************************************************************************************************************************************************************************************************************/
            // Codigo js y ajax para el tab de Pais
            let tablePais = $('.datatable_pais').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.enterprise.pais') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'nombre',name: 'nombre'},
                    { data: null, defaultContent: '', orderable: false, searchable: false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function(data, type, row, meta){
                            return '<a href="javascript:void(0)" id="show" data-url="/admin/enterprise/edit_pais/' + row.id + '" class="btn btn-outline-info show-pais-modal-edit mb-1"><i style="font-size: 20px; font-weight: bold;" class="fas fa-pencil-alt"></i></a>'+
                                '<a href="javascript:void(0)" style="margin-left: 10px;" id="delete" data-url="/admin/enterprise/delete_pais/' + row.id + '" class="btn btn-outline-danger delete-pais-modal mb-1"><i style="font-size: 20px; font-weight: bold;" class="fas fa-trash"></i></a>';
                        },
                    },
                ],
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "Mostrando 0 a 0 de 0 registros",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    loadingRecords: "Cargando...",
                    zeroRecords: "No se encontraron registros",
                    emptyTable: "No hay datos disponibles en la tabla",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Último"
                    },
                    aria: {
                        sortAscending: ": Activar para ordenar la columna en orden ascendente",
                        sortDescending: ": Activar para ordenar la columna en orden descendente"
                    }
                }
            });

            // AJAX para agregar pais:
            $('#formAddPais').submit(function(event){
                event.preventDefault();
                Swal.fire({
                    title: '¿Está seguro de agregar el Pais?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'post',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('admin.enterprise.guardar_pais') }}",
                            data: $('#formAddPais').serialize(),
                            dataType: 'json',
                            success: function(response){
                                if(response.success){
                                    $('#formAddPais')[0].reset();
                                    $('#pais_error').val('');
                                    Swal.fire('¡Pais agregado!',
                                        'El Pais ha sido agregado correctamente a la base de datos',
                                        'success'
                                    ).then(() => {
                                        $('.datatable_pais').DataTable().ajax.reload();
                                    });
                                }else{
                                    if(response.errors){
                                        $.each(response.errors, function(key, value) {
                                            var errorMessages = {
                                                'pais': 'Ingrese otro País .',
                                            };
                                            var errorMessage = errorMessages[key] || value;
                                            $('#' + key + '_error').text(errorMessage); // Pinta el error en el span
                                        });
                                    }
                                    Swal.fire('Error', 'Por favor corrige los errores en el formulario', 'error');
                                }
                            },
                            error: function(response){
                                swal.fire('Error de solicitud', 'La solicitud no se realizó', 'error');
                            }
                        });
                    } else {
                        Swal.fire('¡Pais no agregado!',
                            'Se ha cancelado el registro del pais',
                            'error'
                        );
                    }
                });
            });

            $(document).on('click', '.show-pais-modal-edit', function(event){
                event.preventDefault();

                let url = $(this).data('url');
                console.log('url: ',url);
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data){
                        //Mostrando los datos del espacio en el modal:
                        $('#id_pais_modal').text(data.pais.id);
                        $('#id_pais').val(data.pais.id);
                        $('#pais_nombre').text(data.pais.nombre);
                        $('#nombre_input').val(data.pais.nombre);

                        $('#updatePais').modal('show');
                    },
                    error: function(error){
                        console.log('Error: ', error);
                    }
                });
            });

            $('#updatePaisForm').submit(function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de guardar la actualización?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if(result.isConfirmed){
                        let formData = new FormData(this);
                        let id_pais = $('#id_pais').val();

                        $.ajax({
                            url: $(this).attr('action'), // Obtén la URL del atributo action del formulario
                            type: $(this).attr('method'), // Obtén el método del formulario
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success:function (response){
                                if (response.success) {
                                    console.log('Administración de pais guardada');
                                    $('#updatePaisForm')[0].reset();

                                    Swal.fire('¡Pais actualizado!',
                                        'El pais ha sido actualizado correctamente',
                                        'success'
                                    ).then(() => {
                                        $('.datatable_pais').DataTable().ajax.reload();
                                    });

                                    $('#updatePais').modal('hide');
                                } else {
                                    if (response.errors) {
                                        $.each(response.errors, function (key, value) {
                                            var errorMessages = {
                                                'nombre_input': 'Ingrese un país diferente.',
                                            };

                                            var errorMessage = errorMessages[key] || value;

                                            // Mostrar el mensaje de error junto al input correspondiente
                                            $('#' + key + '_error').text(errorMessage);
                                        });
                                    }
                                    Swal.fire('Error', 'Por favor corrige los errores en el formulario', 'error' );
                                }
                            },
                            error:function (error){
                                console.log('Error al guardar la administración del país', error);
                                Swal.fire('¡Administración no guardad!', 'Ocurrió un error al guardar la administración del país', 'error' );
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.delete-pais-modal', function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de eliminar el registro?',
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
                                Swal.fire('¡Pais Eliminado!',
                                    'El pais ha sido eliminado correctamente',
                                    'success'
                                ).then(() => {
                                    $('.datatable_pais').DataTable().ajax.reload();
                                });
                            },
                            error:function (error){
                                console.log('Error al eliminar el país', error);
                                Swal.fire('¡Eliminacion no realizada!', 'Ocurrió un error al eliminar el país', 'error' );
                            }
                        });
                    }
                });
            });

            /****************************************************************************************************************************************************************************************************************************************************/
            // Codigo js y ajax para el tab de corporativo
            let tableCorporativo = $('.datatable_corporativo').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.enterprise.corporativo') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'nombre',name: 'nombre'},
                    {data: 'nombre_pais',name: 'nombre_pais'},
                    { data: null, defaultContent: '', orderable: false, searchable: false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function(data, type, row, meta){
                            return '<a href="javascript:void(0)" id="show" data-url="/admin/enterprise/edit_corporativo/' + row.id + '" class="btn btn-outline-info show-corporativo-modal-edit mb-1"><i style="font-size: 20px; font-weight: bold;" class="fas fa-pencil-alt"></i></a>'+
                                '<a href="javascript:void(0)" style="margin-left: 10px;" id="delete" data-url="/admin/enterprise/delete_corporativo/' + row.id + '" class="btn btn-outline-danger delete-corporativo-modal mb-1"><i style="font-size: 20px; font-weight: bold;" class="fas fa-trash"></i></a>';
                        },
                    },
                ],
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "Mostrando 0 a 0 de 0 registros",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    loadingRecords: "Cargando...",
                    zeroRecords: "No se encontraron registros",
                    emptyTable: "No hay datos disponibles en la tabla",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Último"
                    },
                    aria: {
                        sortAscending: ": Activar para ordenar la columna en orden ascendente",
                        sortDescending: ": Activar para ordenar la columna en orden descendente"
                    }
                }
            });

            // AJAX para agregar corporativos:
            $('#formAddCorporativo').submit(function(event){
                event.preventDefault();
                Swal.fire({
                    title: '¿Está seguro de agregar el Corporativo?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'post',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('admin.enterprise.guardar_corporativo') }}",
                            data: $('#formAddCorporativo').serialize(),
                            dataType: 'json',
                            success: function(response){
                                if(response.success){
                                    $('#formAddCorporativo')[0].reset();
                                    $('#pais_error').val('');
                                    $('#corporativo_error').val('');
                                    Swal.fire('¡Corporativo agregado!',
                                        'El Corporativo ha sido agregado correctamente a la base de datos',
                                        'success'
                                    ).then(() => {
                                        $('.datatable_corporativo').DataTable().ajax.reload();
                                    });
                                }else{
                                    if(response.errors){
                                        $.each(response.errors, function(key, value) {
                                            var errorMessages = {
                                                'pais': 'Selecione otro pais.',
                                                'corporativo': 'Ingrese otro Corporativo.',
                                            };
                                            var errorMessage = errorMessages[key] || value;
                                            $('#' + key + '_error').text(errorMessage); // Pinta el error en el span
                                        });
                                    }
                                    Swal.fire('Error', 'Por favor corrige los errores en el formulario', 'error');
                                }
                            },
                            error: function(response){
                                swal.fire('Error de solicitud', 'La solicitud no se realizó', 'error');
                            }
                        });
                    } else {
                        Swal.fire('¡Corporativo no agregado!', 'Se ha cancelado el registro del Corporativo', 'error' );
                    }
                });
            });

            $(document).on('click', '.show-corporativo-modal-edit', function(event){
                event.preventDefault();

                let url = $(this).data('url');
                console.log('url: ',url);
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data){
                        //Mostrando los datos del espacio en el modal:
                        let id_corp = data.corporativo.id;
                        let nombre = data.corporativo.nombre;
                        console.log(id_corp, '|', nombre);
                        $('#id_corporativo_modal').text(data.corporativo.id);
                        $('#id_corporativo').val(data.corporativo.id);
                        $('#corporativo_nombre').text(data.corporativo.nombre);
                        $('#nombre_input_corporativo').val(data.corporativo.nombre);

                        let paisHtml = '';
                        data.paises.forEach(function(pais) {
                            let checked = data.assignedPaisId === pais.id;
                            paisHtml += '<div><label><input type="radio" name="pais" value="' + pais.id + '"' + (checked ? ' checked' : '') + '>' + pais.nombre + '</label></div>';
                        });
                        $('#containerPais').html(paisHtml);


                        $('#updateCorporativo').modal('show');
                    },
                    error: function(error){
                        console.log('Error: ', error);
                    }
                });
            });

            $('#updateCorporativoForm').submit(function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de guardar la actualización?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if(result.isConfirmed){
                        let formData = new FormData(this);
                        let id_corporativo = $('#id_corporativo').val();

                        $.ajax({
                            url: $(this).attr('action'), // Obtén la URL del atributo action del formulario
                            type: $(this).attr('method'), // Obtén el método del formulario
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success:function (response){
                                if (response.success) {
                                    console.log('Administración del corporativo guardada');
                                    $('#updateCorporativoForm')[0].reset();

                                    Swal.fire('¡Corporativo actualizado!',
                                        'El corporativo ha sido actualizado correctamente',
                                        'success'
                                    ).then(() => {
                                        $('.datatable_corporativo').DataTable().ajax.reload();
                                    });

                                    $('#updateCorporativo').modal('hide');
                                } else {
                                    if (response.errors) {
                                        $.each(response.errors, function (key, value) {
                                            var errorMessages = {
                                                'nombre_input_corporativo': 'Ingrese un corporativo diferente.',
                                            };
                                            var errorMessage = errorMessages[key] || value;
                                            // Mostrar el mensaje de error junto al input correspondiente
                                            $('#' + key + '_error').text(errorMessage);
                                        });
                                    }
                                    Swal.fire('Error', 'Por favor corrige los errores en el formulario', 'error' );
                                }
                            },
                            error:function (error){
                                console.log('Error al guardar la administración del corporativo', error);
                                Swal.fire('¡Administración no guardad!', 'Ocurrió un error al guardar la administración del corporativo', 'error' );
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.delete-corporativo-modal', function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de eliminar el registro?',
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
                                Swal.fire('¡Corporativo Eliminado!',
                                    'El corporativo ha sido eliminado correctamente',
                                    'success'
                                ).then(() => {
                                    $('.datatable_corporativo').DataTable().ajax.reload();
                                });
                            },
                            error:function (error){
                                console.log('Error al eliminar el corporativo', error);
                                Swal.fire('¡Eliminacion no realizada!', 'Ocurrió un error al eliminar el corporativo', 'error' );
                            }
                        });
                    }
                });
            });

            /****************************************************************************************************************************************************************************************************************************************************/
            // Codigo js y ajax para el tab de cargo
            let tableCargo = $('.datatable_cargo').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.enterprise.cargo') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'nombre',name: 'nombre'},
                    { data: null, defaultContent: '', orderable: false, searchable: false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function(data, type, row, meta){
                            return '<a href="javascript:void(0)" id="show" data-url="/admin/enterprise/edit_cargo/' + row.id + '" class="btn btn-outline-info show-cargo-modal-edit mb-1"><i style="font-size: 20px; font-weight: bold;" class="fas fa-pencil-alt"></i></a>'+
                                '<a href="javascript:void(0)" style="margin-left: 10px;" id="delete" data-url="/admin/enterprise/delete_cargo/' + row.id + '" class="btn btn-outline-danger delete-cargo-modal mb-1"><i style="font-size: 20px; font-weight: bold;" class="fas fa-trash"></i></a>';
                        },
                    },
                ],
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "Mostrando 0 a 0 de 0 registros",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    loadingRecords: "Cargando...",
                    zeroRecords: "No se encontraron registros",
                    emptyTable: "No hay datos disponibles en la tabla",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Último"
                    },
                    aria: {
                        sortAscending: ": Activar para ordenar la columna en orden ascendente",
                        sortDescending: ": Activar para ordenar la columna en orden descendente"
                    }
                }
            });

            // AJAX para agregar cargos:
            $('#formAddCargo').submit(function(event){
                event.preventDefault();
                Swal.fire({
                    title: '¿Está seguro de agregar el Cargo?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'post',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('admin.enterprise.guardar_cargo') }}",
                            data: $('#formAddCargo').serialize(),
                            dataType: 'json',
                            success: function(response){
                                if(response.success){
                                    $('#formAddCargo')[0].reset();
                                    $('#cargo_error').val('');
                                    Swal.fire('¡Cargo agregado!',
                                        'El Cargo ha sido agregado correctamente a la base de datos',
                                        'success'
                                    ).then(() => {
                                        $('.datatable_cargo').DataTable().ajax.reload();
                                    });
                                }else{
                                    if(response.errors){
                                        $.each(response.errors, function(key, value) {
                                            var errorMessages = {
                                                'cargo': 'Ingrese otro Cargo.',
                                            };
                                            var errorMessage = errorMessages[key] || value;
                                            $('#' + key + '_error').text(errorMessage); // Pinta el error en el span
                                        });
                                    }
                                    Swal.fire('Error', 'Por favor corrige los errores en el formulario', 'error');
                                }
                            },
                            error: function(response){
                                swal.fire('Error de solicitud', 'La solicitud no se realizó', 'error');
                            }
                        });
                    } else {
                        Swal.fire('¡Cargo no agregado!', 'Se ha cancelado el registro del Cargo', 'error' );
                    }
                });
            });

            $(document).on('click', '.show-cargo-modal-edit', function(event){
                event.preventDefault();

                let url = $(this).data('url');
                console.log('url: ',url);
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data){
                        //Mostrando los datos del espacio en el modal:
                        $('#id_cargo_modal').text(data.cargo.id);
                        $('#id_cargo').val(data.cargo.id);
                        $('#cargo_nombre').text(data.cargo.nombre);
                        $('#nombre_input_cargo').val(data.cargo.nombre);

                        $('#updateCargo').modal('show');
                    },
                    error: function(error){
                        console.log('Error: ', error);
                    }
                });
            });

            $('#updateCargoForm').submit(function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de guardar la actualización?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if(result.isConfirmed){
                        let formData = new FormData(this);
                        let id_cargo = $('#id_cargo').val();

                        $.ajax({
                            url: $(this).attr('action'), // Obtén la URL del atributo action del formulario
                            type: $(this).attr('method'), // Obtén el método del formulario
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success:function (response){
                                if (response.success) {
                                    console.log('Administración de cargo guardada');
                                    $('#updateCargoForm')[0].reset();

                                    Swal.fire('¡Cargo actualizado!',
                                        'El cargo ha sido actualizado correctamente',
                                        'success'
                                    ).then(() => {
                                        $('.datatable_cargo').DataTable().ajax.reload();
                                    });

                                    $('#updateCargo').modal('hide');
                                } else {
                                    if (response.errors) {
                                        $.each(response.errors, function (key, value) {
                                            var errorMessages = {
                                                'nombre_input_cargo': 'Ingrese un cargo diferente.',
                                            };

                                            var errorMessage = errorMessages[key] || value;

                                            // Mostrar el mensaje de error junto al input correspondiente
                                            $('#' + key + '_error').text(errorMessage);
                                        });
                                    }
                                    Swal.fire('Error', 'Por favor corrige los errores en el formulario', 'error' );
                                }
                            },
                            error:function (error){
                                console.log('Error al guardar la administración del cargo', error);
                                Swal.fire('¡Administración no guardad!', 'Ocurrió un error al guardar la administración del cargo', 'error' );
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.delete-cargo-modal', function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de eliminar el registro?',
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
                                Swal.fire('¡Cargo Eliminado!',
                                    'El cargo ha sido eliminado correctamente',
                                    'success'
                                ).then(() => {
                                    $('.datatable_cargo').DataTable().ajax.reload();
                                });
                            },
                            error:function (error){
                                console.log('Error al eliminar el cargo', error);
                                Swal.fire('¡Eliminacion no realizada!', 'Ocurrió un error al eliminar el cargo', 'error' );
                            }
                        });
                    }
                });
            });

            /****************************************************************************************************************************************************************************************************************************************************/
            // Codigo js y ajax para el tab de Areas
            let tableArea = $('.datatable_area').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.enterprise.area') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'nombre',name: 'nombre'},
                    { data: null, defaultContent: '', orderable: false, searchable: false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function(data, type, row, meta){
                            return '<a href="javascript:void(0)" id="show" data-url="/admin/enterprise/edit_area/' + row.id + '" class="btn btn-outline-info show-area-modal-edit mb-1"><i style="font-size: 20px; font-weight: bold;" class="fas fa-pencil-alt"></i></a>'+
                                '<a href="javascript:void(0)" style="margin-left: 10px;" id="delete" data-url="/admin/enterprise/delete_area/' + row.id + '" class="btn btn-outline-danger delete-area-modal mb-1"><i style="font-size: 20px; font-weight: bold;" class="fas fa-trash"></i></a>';
                        },
                    },
                ],
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "Mostrando 0 a 0 de 0 registros",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    loadingRecords: "Cargando...",
                    zeroRecords: "No se encontraron registros",
                    emptyTable: "No hay datos disponibles en la tabla",
                    paginate: {
                        first: "Primero",
                        previous: "Anterior",
                        next: "Siguiente",
                        last: "Último"
                    },
                    aria: {
                        sortAscending: ": Activar para ordenar la columna en orden ascendente",
                        sortDescending: ": Activar para ordenar la columna en orden descendente"
                    }
                }
            });

            // AJAX para agregar cargos:
            $('#formAddArea').submit(function(event){
                event.preventDefault();
                Swal.fire({
                    title: '¿Está seguro de agregar el Area?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'post',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('admin.enterprise.guardar_area') }}",
                            data: $('#formAddArea').serialize(),
                            dataType: 'json',
                            success: function(response){
                                if(response.success){
                                    $('#formAddArea')[0].reset();
                                    $('#area_error').val('');
                                    Swal.fire('¡Area agregado!',
                                        'El Area ha sido agregado correctamente a la base de datos',
                                        'success'
                                    ).then(() => {
                                        $('.datatable_area').DataTable().ajax.reload();
                                    });
                                }else{
                                    if(response.errors){
                                        $.each(response.errors, function(key, value) {
                                            var errorMessages = {
                                                'area': 'Ingrese otro Area.',
                                            };
                                            var errorMessage = errorMessages[key] || value;
                                            $('#' + key + '_error').text(errorMessage); // Pinta el error en el span
                                        });
                                    }
                                    Swal.fire('Error', 'Por favor corrige los errores en el formulario', 'error');
                                }
                            },
                            error: function(response){
                                swal.fire('Error de solicitud', 'La solicitud no se realizó', 'error');
                            }
                        });
                    } else {
                        Swal.fire('¡Area no agregado!', 'Se ha cancelado el registro del Area', 'error' );
                    }
                });
            });

            $(document).on('click', '.show-area-modal-edit', function(event){
                event.preventDefault();

                let url = $(this).data('url');
                console.log('url: ',url);
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data){
                        //Mostrando los datos del espacio en el modal:
                        $('#id_area_modal').text(data.area.id);
                        $('#id_area').val(data.area.id);
                        $('#area_nombre').text(data.area.nombre);
                        $('#nombre_input_area').val(data.area.nombre);

                        $('#updateArea').modal('show');
                    },
                    error: function(error){
                        console.log('Error: ', error);
                    }
                });
            });

            $('#updateAreaForm').submit(function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de guardar la actualización?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if(result.isConfirmed){
                        let formData = new FormData(this);
                        let id_area = $('#id_area').val();

                        $.ajax({
                            url: $(this).attr('action'), // Obtén la URL del atributo action del formulario
                            type: $(this).attr('method'), // Obtén el método del formulario
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success:function (response){
                                if (response.success) {
                                    console.log('Administración de area guardada');
                                    $('#updateAreaForm')[0].reset();

                                    Swal.fire('¡Area actualizado!',
                                        'El area ha sido actualizado correctamente',
                                        'success'
                                    ).then(() => {
                                        $('.datatable_area').DataTable().ajax.reload();
                                    });

                                    $('#updateArea').modal('hide');
                                } else {
                                    if (response.errors) {
                                        $.each(response.errors, function (key, value) {
                                            var errorMessages = {
                                                'nombre_input_area': 'Ingrese un área diferente.',
                                            };

                                            var errorMessage = errorMessages[key] || value;

                                            // Mostrar el mensaje de error junto al input correspondiente
                                            $('#' + key + '_error').text(errorMessage);
                                        });
                                    }
                                    Swal.fire('Error', 'Por favor corrige los errores en el formulario', 'error' );
                                }
                            },
                            error:function (error){
                                console.log('Error al guardar la administración del area', error);
                                Swal.fire('¡Administración no guardada!', 'Ocurrió un error al guardar la administración del area', 'error' );
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.delete-area-modal', function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de eliminar el registro?',
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
                                Swal.fire('¡Area Eliminado!',
                                    'El area ha sido eliminado correctamente',
                                    'success'
                                ).then(() => {
                                    $('.datatable_area').DataTable().ajax.reload();
                                });
                            },
                            error:function (error){
                                console.log('Error al eliminar el area', error);
                                Swal.fire('¡Eliminacion no realizada!', 'Ocurrió un error al eliminar el area', 'error' );
                            }
                        });
                    }
                });
            });

            //SELECT 2 PLUGIN:
            $('.select2').select2({
                theme: "classic",
                width: 'resolve'
            });
        });
    </script>
@stop
