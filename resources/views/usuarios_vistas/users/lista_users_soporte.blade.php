@extends('adminlte::page')

@section('title', 'Lista de Usuarios')

@section('content_header')
    <h5>Administración de usuarios</h5>
@stop

@section('content')
    <main>
        <div class="d-flex justify-content-center align-items-center">
            <div class="card d-flex col-lg-12 col-md-10 col-sm-10 flex-lg-row flex-md-column flex-sm-column justify-content-around align-items-center">
                <div class="p-2 w-100">
                    <div class="row justify-content-center align-items-center mb-2">
                        <div class="col-lg-12 col-md-11 col-sm-11">
                            <form action="" class="bg-light p-4 rounded">
                                <div class="row d-flex">
                                    <div class="col-6 form-group">
                                        <label for="name">Nombre:</label>
                                        <input type="text" name="name" id="nameInput" class="form-control" placeholder="Ingrese el nombre">
                                    </div>
                                    <div class="col-6 form-group">
                                        <label for="email">Email:</label>
                                        <input type="text" name="email" id="emailInput" class="form-control" placeholder="Ingrese el correo electrónico">
                                    </div>
                                </div>
                                <div class="row text-center d-flex">
                                    <div class="col-md-6 mb-3">
                                        <button type="submit" class="btn btn-outline-success mr-2 col-3" id="buscar"><i class="fas fa-search mr-1"></i>Buscar</button>
                                        <button type="reset" class="btn btn-secondary col-3" id="reset"><i class="fas fa-trash-alt mr-1"></i>Limpiar</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-outline-light btn-modal-cancel button-evidencia button-cancel-user float-end" data-bs-toggle="modal" data-bs-target="#usuarioAddModal">Agregar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table id="example" class="display responsive nowrap datatable_usuarios_admin" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>ROLES</th>
                                <th>CARGOS</th>
                                <th>AREAS</th>
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


    {{-- Modal para agregar usuarios --}}
    <div class="modal fade modal-right" id="usuarioAddModal" tabindex="-1" aria-labelledby="usuarioAddModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-contenido modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
            <form class="saveUsuario sendEvidenciaAgenda" id="formAddUsuario" enctype="multipart/form-data">
                @csrf
                <div class="modal-content modal-content-user">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="usuarioAddLabel">Agregar usuario</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body contenido-body-user">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="col-12">
                            <label for="nombre">Nombres:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control w-100" required>
                            <span id="nombre_error" class="text-danger"></span>
                        </div>
                        <div class="col-12">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" class="form-control w-100" required>
                            <span id="email_error" class="text-danger"></span>
                        </div>
                        <div class="col-12">
                            <label for="password">password:</label>
                            <input type="password" name="password" id="password" class="form-control w-100" required>
                            <span id="password_error" class="text-danger"></span>
                        </div>
                        <div class="col-12">
                            <label for="confirm_password">Confirmar password:</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control w-100" required>
                            <span id="confirm_password_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-success button-evidencia button-add-user">Guardar <i class="fas fa-user" style="font-size: 20px; font-weight: bold;"></i> </button>
                        <button type="button" class="btn btn-outline-light btn-modal-cancel button-evidencia button-cancel-user" data-bs-dismiss="modal">Cerrar <i class="fas fa-times ml-2" style="font-size: 20px; font-weight: bold;" ></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- End modal add user --}}

    {{-- Modal para ver los datos de la administracion del usuario --}}
    <div class="modal fade modal-right" id="usuarioAdminModal" tabindex="-1" aria-labelledby="usuarioAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-contenido-config-user modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
            <form method="POST" action="{{ route('usuario.guardar_administracion_user') }}" class="saveAdministracionUsuario" id="saveAdministracionUsuario" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content modal-content-config-user d-flex justify-content-center">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="usuarioModalLabel">Administracion del usuario: <span id="idUser" class="ml-2"></span> - <span id="nombreUser" class="ml-2"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body justify-content-center">
                        <input type="hidden" name="id_user_config" id="id_user_config" value="">
                        <div class="row justify-content-center d-flex">
                            <div class="col-5 card">
                                <h5 class="card-header">Asignar Rol</h5>
                                <div class="card-body" id="rolesContainer"></div>
                            </div>
                            <div class="col-5 card ml-2">
                                <h5 class="card-header">Asignar Cargo</h5>
                                <div class="card-body" id="cargosContainer"></div>
                            </div>
                        </div>
                        <div class="row justify-content-center d-flex">
                            <div class="col-5 card">
                                <h5 class="card-header">Asignar Area (s)</h5>
                                <div class="card-body" id="areasContainer"></div>
                            </div>
                            <div class="col-5 card ml-2">
                                <h5 class="card-header">Asignar Corporativo</h5>
                                <div class="card-body" id="corporativosContainer"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-secondary button-evidencia">Guardar Datos <i class="fas fa-save ml-2" style="font-size: 20px; font-weight: bold;"></i> </button>
                        <button type="button" class="btn btn-danger button-evidencia" data-bs-dismiss="modal">Cerrar <i class="fas fa-times ml-2" style="font-size: 20px; font-weight: bold;" ></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- End modal administración de usuario --}}

    {{-- Contenido de modal para editar registros --}}
    <div class="modal fade modal-right" id="updateUsuario" tabindex="-1" aria-labelledby="updateUsuarioModal" aria-hidden="true">
        <div class="modal-dialog modal-contenido modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
            <form method="POST" action="{{ route('usuario.update') }}" id="updateUsuarioForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content modal-content-evidencia">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateUsuarioModal">Modificar Usuario</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body justify-content-center">
                        <div class="col-10">
                            <input type="hidden" name="id_usuario" id="id_usuario" class="form-control">
                            <div class="form-group">
                                <label for="nombre_espacio">Nombre: </label>
                                <input type="text" name="nombre_input" id="nombre_input" class="form-control">
                                <span id="nombre_input_error" class="text-danger"></span>
                            </div>
                            <div>
                                <label>Email</label>
                                <input type="text" name="email_input" id="email_input" class="form-control">
                                <span id="email_input_error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success button-evidencia" id="btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-light btn-modal-cancel button-evidencia" id="btnUpdateUsuario">Actualizar usuario<i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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

    {{--Estilos para el sidebar--}}
    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css')}}">
    <link rel="stylesheet" href="{{asset('css/modal-save-evidencia.css')}}">
    <style>
        .swal2-container {
            z-index: 2001;
        }
        .table-dark,
        .btn-outline-success{
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

            var table = $('.datatable_usuarios_admin').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('usuario.lista') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    { data: 'roles', name: 'roles',
                        render: function(data, type, row) {
                            var roles = '';
                            data.forEach(function(role) {
                                roles += role.name + '<br>';
                            });
                            return roles;
                        }
                    },
                    { data: 'cargos', name: 'cargos',
                        render: function(data, type, row) {
                            var cargos = '';
                            data.forEach(function(cargo) {
                                cargos += cargo.nombre + '<br>';
                            });
                            return cargos;
                        }
                    },
                    { data: 'areas', name: 'areas',
                        render: function(data, type, row) {
                            var areas = '';
                            data.forEach(function(area) {
                                areas += area.nombre + '<br>';
                            });
                            return areas;
                        }
                    },
                    { data: 'corporativos', name: 'corporativos',
                        render: function(data, type, row) {
                            var corporativos = '';
                            data.forEach(function(corporativo) {
                                corporativos += corporativo.nombre + '<br>';
                            });
                            return corporativos;
                        }
                    },
                    { data: null, defaultContent: '', orderable: false, searchable: false },
                ],
                columnDefs: [
                    {
                        targets: -1,
                        render: function(data, type, row, meta){
                            return '<a href="javascript:void(0)" id="show" data-url="/admin/usuario/settings/' + row.id + '" class="btn btn-outline-secondary show-usuario mb-1"><i style="font-size: 20px; font-weight: bold;" class="fas fa-user-cog"></i></a> &nbsp;&nbsp; '+
                                   '<a href="javascript:void(0)" id="show" data-url="/admin/usuario/edit/' + row.id + '" class="btn btn-outline-info edit-usuario mb-1"><i style="font-size: 20px; color: green; font-weight: bold;" class="bx bx-edit-alt"></i></a>' +
                                   '<a href="javascript:void(0)" style="margin-left: 10px;" id="delete" data-url="/admin/usuario/usuario_delete/' + row.id + '" class="btn btn-outline-danger delete-usuario-modal mb-1"><i style="font-size: 20px; font-weight: bold;" class="fas fa-trash"></i></a>';
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
            //*******************BUSQUEDA DE USUARIOS****************************************************
            $('#buscar').on('click', function(event){
                event.preventDefault();
                let nombre = $('#nameInput').val();
                let email = $('#emailInput').val();

                console.log('DATOS: '
                    +'\nNombre usuario: '+nombre
                    +'\nEmail usuario: '+email
                );
                //Recarga el datatable al enviar los datos
                table.ajax.url("{{ route('usuario.lista') }}?name=" + nombre + "&email=" + email).load(null, 'reload');

                return false;
            });
            //*******************LIMPIEZA DEL FORMULARIO*************************************************
            $('#reset').on('click', function(){
                $('#nameInput').val('');
                $('#emailInput').val('');


                //recarga el datatable
                table.ajax.url("{{ route('usuario.lista') }}").load(null, 'reload');

                return false;
            });
            //Administración del usuario cargos, roles, corporativo en radio button y areas en checkbox
            $(document).on('click', '.show-usuario', function (event){
                event.preventDefault();
                var url = $(this).data('url');
                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(data){
                        $('#usuarioAdminModal').modal('show');

                        // Mostrar datos en el modal
                        $('#idUser').text(data.user.id);
                        $('#nombreUser').text(data.user.name);
                        $('#id_user_config').val(data.user.id);

                        // Mostrar roles en radio buttons
                        let rolesHtml = '';
                        data.roles.forEach(function(role){
                            let checked = data.assignedRoles.some(function(assignedRole){
                                return assignedRole.id === role.id;
                            });
                            rolesHtml += '<div><label><input type="radio" name="roles" value="' + role.id + '"' + (checked ? ' checked' : '') + '>' + role.name + '</label></div>';
                        });
                        $('#rolesContainer').html(rolesHtml);

                        // Mostrar cargos en radio buttons
                        let cargosHtml = '';
                        data.cargos.forEach(function(cargo){
                            let checked = data.assignedCargos.some(function(assignedCargo){
                                return assignedCargo.id === cargo.id;
                            });
                            cargosHtml += '<div><label><input type="radio" name="cargos" value="' + cargo.id + '"' + (checked ? ' checked' : '') + '>' + cargo.nombre + '</label></div>';
                        });
                        $('#cargosContainer').html(cargosHtml);

                        // Mostrar áreas en checkboxes
                        let areasHtml = '';
                        data.areas.forEach(function(area){
                            let checked = data.assignedAreas.some(function(assignedArea){
                                return assignedArea.id === area.id;
                            });
                            areasHtml += '<div><label><input type="checkbox" name="areas[]" value="' + area.id + '"' + (checked ? ' checked' : '') + '>' + area.nombre + '</label></div>';
                        });
                        $('#areasContainer').html(areasHtml);

                        // Mostrar corporativos en radio buttons
                        let corporativosHtml = '';
                        data.corporativos.forEach(function(corporativo){
                            let checked = data.assignedCorporativos.some(function(assignedCorporativo){
                                return assignedCorporativo.id === corporativo.id;
                            });
                            corporativosHtml += '<div><label><input type="radio" name="corporativos" value="' + corporativo.id + '"' + (checked ? ' checked' : '') + '>' + corporativo.nombre + '</label></div>';
                        });
                        $('#corporativosContainer').html(corporativosHtml);

                    },
                    error: function(error){
                        console.log('Error al enviar la solicitud ajax.');
                        Swal.fire(
                            '¡Datos no encontrados!',
                            'Ocurrió un error alenviar la solicitud AJAX',
                            'error'
                        );
                    },
                });
            });
            //*******************************************************************************************
            //Envío del formulario para hacer update
            $('#saveAdministracionUsuario').submit(function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de guardar la configuración?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if(result.isConfirmed){
                        let formData = new FormData(this);
                        let id_user = $('#id_user_config').val();

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
                                if(response.error){
                                    Swal.fire({
                                        title: '¡Administración No Realizada!',
                                        html: `<span style="color: red;">${response.message}</span>`,
                                        icon: 'error'
                                    });
                                }else{
                                    console.log('Administración de usuario guardada');

                                    $('.datatable_usuarios_admin').DataTable().ajax.reload();

                                    Swal.fire({
                                        title: '¡Administración Realizada!',
                                        html: `<span style="color: darkgreen;">${response.message}</span>`,
                                        icon: 'success'
                                    });

                                    $('#usuarioAdminModal').modal('hide');
                                }
                            },
                            error:function (error){
                                console.log('Error al guardar la administración del usuario', error);
                                Swal.fire('¡Administración no guardad!',
                                    'Ocurrió un error al guardar la administración del usuario',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
            //*******************************************************************************************
            //Agregar usuario -> AJAX
            $('#formAddUsuario').submit(function(event) {
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de agregar el usuario?',
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
                            url: "{{ route('usuario.guardar_usuario') }}",
                            data: $('#formAddUsuario').serialize(),
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    $('#formAddUsuario')[0].reset();
                                    Swal.fire('¡Usuario agregado!',
                                        'El usuario ha sido agregado correctamente a la base de datos',
                                        'success'
                                    ).then(() => {
                                        $('.datatable_usuarios_admin').DataTable().ajax.reload();
                                    });
                                } else {
                                    if (response.errors) {
                                        //Muestra los errores de validacion en los span que tienen id = " _error"
                                        $.each(response.errors, function(key, value) {
                                            // Define mensajes de error personalizados para cada campo
                                            var errorMessages = {
                                                'nombre': 'ingrese un nombre valido.',
                                                'email': 'El email ingresado ya existe.',
                                                'password': 'La contraseña debe tener al menos 8 caracteres.',
                                                'confirm_password': 'Las contraseñas no coinciden.'
                                            };

                                            var errorMessage = errorMessages[key] || value;

                                            $('#' + key + '_error').text(errorMessage);
                                        });
                                    }
                                    Swal.fire('Error',
                                        'Por favor corrige los errores en el formulario',
                                        'error'
                                    );
                                }
                            },
                            error: function(response){
                                swal.fire('Error de solicitud', 'La solicitud no se realizó', 'error');
                            }
                        });
                    } else {
                        Swal.fire('¡Usuario no agregado!',
                            'Se ha cancelado el registro del usuario',
                            'error'
                        );
                    }
                });
            });

            // Ver los datos
            $(document).on('click', '.edit-usuario', function(event){
                event.preventDefault();

                let url = $(this).data('url');
                console.log('url: ',url);
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data){
                        //Mostrando los datos del espacio en el modal:
                        console.log('Datos del usuario');
                        console.log(data.user);
                        $('#id_usuario').val(data.user.id);
                        $('#nombre_input').val(data.user.name);
                        $('#email_input').val(data.user.email);

                        $('#updateUsuario').modal('show');
                    },
                    error: function(error){
                        console.log('Error: ', error);
                    }
                });
            });

            // Actualizar los datos
            $('#updateUsuarioForm').submit(function(event){
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
                        
                        for (var pair of formData.entries()) {
                            console.log(pair[0] + ': ' + pair[1]);
                        }

                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'POST', // Usamos POST y el método _method: PUT
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response){
                                if (response.success) {
                                    console.log('Administración de usuario guardada');
                                    $('#updateUsuarioForm')[0].reset();

                                    Swal.fire('Usuario actualizado!', 'El Usuario ha sido actualizado correctamente', 'success')
                                        .then(() => { 
                                            $('.datatable_usuarios_admin').DataTable().ajax.reload();
                                        });

                                    $('#updateUsuario').modal('hide');
                                } else {
                                    if (response.errors) {
                                        $.each(response.errors, function (key, value) {
                                            var errorMessages = {
                                                'nombre_input': 'Ingrese un nombre.',
                                                'email_input': 'Ingrese un email.',
                                            };
                                            var errorMessage = errorMessages[key] || value;
                                            $('#' + key + '_error').text(errorMessage);
                                        });
                                    }
                                    Swal.fire('Error', 'Por favor corrige los errores en el formulario', 'error' );
                                }
                            },
                            error: function (error){
                                console.log('Error al guardar la administración del usuario', error);
                                Swal.fire('¡Administración no guardada!', 'Ocurrió un error al guardar la administración del usuario', 'error' );
                            }
                        });
                    }
                });
            });

            // Eliminar el usuario: 
            $(document).on('click', '.delete-usuario-modal', function(event){
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
                                Swal.fire('¡Usuario Eliminado!',
                                    'El usuario ha sido eliminado correctamente',
                                    'success'
                                ).then(() => {
                                    $('.datatable_usuarios_admin').DataTable().ajax.reload();
                                });
                            },
                            error:function (error){
                                console.log('Error al eliminar el usuario', error);
                                Swal.fire('¡Eliminacion no realizada!', 'Ocurrió un error al eliminar el usuario', 'error' );
                            }
                        });
                    }
                });
            });

        });
    </script>


@stop
