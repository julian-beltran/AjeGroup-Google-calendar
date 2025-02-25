@extends('adminlte::page')

@section('title', 'Roles | Permisos')

@section('content_header')
    <h3>Administración de roles y permisos</h3>
@stop

@section('content')
    <main>
        <fieldset class="border rounded mb-3 pl-2 pr-2">
            <legend class="ml-2">Administración de los roles</legend>
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-3 col-md-3 float-start">
                    <form method="post">
                        <div class="row mb-3">
                            <div class="col-md-8 d-flex justify-content-start">
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addRoleModal">Agregar Rol</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-8 col-md-8">
                    <table class="display responsive nowrap tabla_administracion_roles_permisos" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>NOMBRE</th>
                                <th>PERMISOS DEL ROL</th>
                                <th>CONFIG</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $rol)
                                <tr>
                                    <td>{{ $rol->id }}</td>
                                    <td>{{ $rol->name }}</td>
                                    <td>
                                        @foreach($permisosAsignados[$rol->id] as $permiso)
                                            {{ $permiso->name }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{--<a href="{{ route('usuario.roles.asignar', $rol->id) }}"  class="btn btn-outline-secondary"> <i style="font-size: 20px; font-weight: bold;" class="fas fa-cogs"></i></a>--}}
                                        <a href="javascript:void(0)" data-url="/admin/usuario/roles/asignar_permiso/{{ $rol->id }}" class="btn btn-outline-secondary asignar_permisos_rol"><i class="fas fa-user-cog"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </fieldset>

        <fieldset class="border rounded mb-3 pl-2 pr-2">
            <legend class="ml-2">Administración de los permisos</legend>
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-8 col-md-8">
                    <table class="display responsive nowrap tabla_administracion_roles_permisos" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>NOMBRE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permisos as $permiso)
                                <tr>
                                    <td>{{ $permiso->id }}</td>
                                    <td>{{ $permiso->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-3 col-md-4">
                    <form method="post">
                        <div class="row mb-3">
                            <div class="col-md-8 d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addPermisoModal">Agregar Permiso</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </fieldset>
    </main>


    {{--Contenido de modal para agregar roles--}}
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" class="guardar_rol" id="guardar_rol">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Rol</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-1">
                            <label>Nombre:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-success" id="guardar_rol">Guardar registro</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--Final de contenido modal agregar--}}

    {{--Contenido de modal para agregar permisos--}}
    <div class="modal fade" id="addPermisoModal" tabindex="-1" aria-labelledby="addPermisoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" class="guardar_permiso" id="guardar_permiso">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Permiso</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-1">
                            <label>Nombre:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-success" id="guardar_permiso">Guardar registro</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--Final de contenido modal agregar--}}

    {{--Contenido de modal para agregar permisos--}}
    <div class="modal fade" id="assignPermisoModal" tabindex="-1" aria-labelledby="assignPermisoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('usuario.roles.update') }}" class="asignarPermisoAlRol" id="asignarPermisoAlRol">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="assignPermisoModalLabel">Asignar permisos al rol con<strong> ID: </strong><span id="id_rol_assign"></span> | <strong>NOMBRE:</strong><span id="nombre_rol_assign"></span> </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-1 d-flex">
                            <input type="text" name="id_rol" id="id_rol" class="form-control" readonly>
                            <input type="text" name="nombre_rol" id="nombre_rol" class="form-control" readonly>
                        </div>
                        <label>Lista de Permisos:</label>
                        <div id="container_permisos_checked">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-success" id="asignar_permisos">Asignar Permisos</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--Final de contenido modal agregar--}}
@stop

@section('css')
    {{--para DataTables--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    {{--select 2--}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap" rel="stylesheet">

    {{--Estilos para el sidebar--}}
    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css')}}">
    <style>
        .table-dark{
            background-color: #007A3E !important;
            color: #FFFFFF !important;
        }
        .btn-outline-success{
            background-color: #007A3E !important;
            color: #FFFFFF !important;
        }
    </style>
@stop

@section('js')
    {{--Para DataTables--}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    {{--Para Bootstrap 5--}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    {{--Select2--}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{--SweetAlert--}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{--BoxIcons--}}
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    <script type="text/javascript">
        $(function (){
            /*DataTables****************************************************************************************/
            let tabel_admin = $('.tabla_administracion_roles_permisos').DataTable({
                responsive: true,
                autoWidth: false,
                bDestroy: true,
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
                "language": {
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtro de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "lengthMenu": "Mostrar _MENU_ registros por página"
                }
            });

            /************************************************************************************************/
            // Para agregar Rol
            $('#guardar_rol').submit(function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de agregar el rol?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if(result.isConfirmed){
                        let formData = new FormData(this);

                        $.ajax({
                            url: "{{ route('usuario.roles.saveRoles') }}",
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success:function (response){
                                console.log('Rol agregado');

                                location.reload();
                                //$('.tabla_administracion_roles_permisos').DataTable().ajax.reload();

                                $('#addRolModal').modal('hide');

                                Swal.fire('¡Rol guardada!',
                                    'El registro dle rol ha sido correcta',
                                    'success'
                                );
                            },
                            error:function (error){
                                console.log('Error al guardar el rol', error);
                                Swal.fire('¡Rol no guardada!',
                                    'Ocurrió un error al guardar el rol',
                                    'error'
                                );
                            }
                        });
                    }else{
                        Swal.fire(
                            '¡Guardado cancelado!',
                            'El envío del formulario ha sido cancelado',
                            'error'
                        );
                    }
                });


            });
            /************************************************************************************************/
            // Para agregar permiso
            $('#guardar_permiso').submit(function(event){
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de subir el permiso?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if(result.isConfirmed){
                        let formData = new FormData(this);

                        $.ajax({
                            url: "{{ route('usuario.roles.savePermisos') }}",
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success:function (response){
                                console.log('Permiso agregado');

                                location.reload();
                                //$('.tabla_administracion_roles_permisos').DataTable().ajax.reload();

                                $('#addPermisoModal').modal('hide');

                                Swal.fire('¡Permiso guardado!',
                                    'El registro del permiso ha sido correcta',
                                    'success'
                                );
                            },
                            error:function (error){
                                console.log('Error al guardar el permiso', error);
                                Swal.fire('¡permiso no guardado!',
                                    'Ocurrió un error al guardar el permiso',
                                    'error'
                                );
                            }
                        });
                    }else{
                        Swal.fire(
                            '¡Registro cancelado!',
                            'El envío del formulario ha sido cancelado',
                            'error'
                        );
                    }
                });


            });

            // Para ver los permisos del rol :
            $(document).on('click', '.asignar_permisos_rol', function(){
                let url = $(this).data('url');
                console.log('ID_ROL => ', url);
                $.ajax({
                    url: url,
                    dataType: "json",
                    success: function(data){
                        // Mostrar datos del rol en el modal : id y name
                        $('#id_rol_assign').text(data.rol.id);
                        $('#nombre_rol_assign').text(data.rol.name);
                        $('#id_rol').val(data.rol.id);
                        $('#nombre_rol').val(data.rol.name);

                        // Mostrar permisos en el modal
                        let permisosHtml = '';
                        data.permisos.forEach(function(permiso){
                            let checked = data.permisosAsignados.some(function(assignedPermission){
                                return assignedPermission.id === permiso.id;
                            });
                            permisosHtml += '<div><label><input type="checkbox" name="permisos[]" value="' + permiso.id + '"' + (checked ? 'checked' : '') + '>' + permiso.name + '</label></div>';
                        });

                        $('#container_permisos_checked').html(permisosHtml);

                        $('#assignPermisoModal').modal('show');
                    },
                    error: function(error){
                        console.log('Error al enviar la solicitud AJAX.');
                        Swal.fire('Error', 'Ocurrió un error al obtener los permisos asignados al rol.', 'error');
                    },
                });
            });

            // Para realizar la actualización de los pernisos del rol:
            $('#asignarPermisoAlRol').submit(function(event){
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
                                console.log('Administración del rol guardada');

                                location.reload();
                                // $('.datatable_usuarios_admin').DataTable().ajax.reload();

                                $('#assignPermisoModal').modal('hide');

                                Swal.fire('¡Administración guardada!', 'La administración del rol ha sido correcta', 'success' );
                            },
                            error:function (error){
                                console.log('Error al guardar la administración del rol', error);
                                Swal.fire('¡Administración no guardada!', 'Ocurrió un error al guardar la administración del rol', 'error' );
                            }
                        });
                    }
                });
            });

        });
    </script>
@stop
