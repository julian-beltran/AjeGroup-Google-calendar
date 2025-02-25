@extends('adminlte::page')

@section('title', 'Dashboard | Espacios')

@section('content_header')
@stop

@section('content')
    <div class="row justify-content-center align-items-center">
        <div class="row mt-2">
            <div class="col-lg-3 col-md-6 col-sm-12 mb-1">
                <button type="button" class="btn btn-outline-success float-start w-100" data-bs-toggle="modal" data-bs-target="#addEspacio">Agregar</button>
            </div>
        </div>

        <div class="row d-flex">
            <div id="cargaEspaciosAjax">
            </div>
        </div>
    </div>

    {{--Contenido de modal para agregar espacios--}}
    <div class="modal fade" id="addEspacio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">|
        <div class="modal-dialog modal-lg">
            <form class="saveEspacio" id="formAddEspacio" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar espacio</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="form_result"></span>
                        <div class="row mb-1">
                            <label class="body-titles-container">Corporativo:</label>
                            <select name="id_corporativo" id="id_corporativo" class="form-select form-select-lg mb-3" required>
                                <option selected>Seleccionar</option>
                                @foreach($corporativos as $corporativo)
                                    <option value="{{ $corporativo->id }}">{{ $corporativo->nombre }}</option>
                                @endforeach
                            </select>
                            <span id="id_corporativo_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-1">
                                    <label class="body-titles-container">Nombre:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                                    <span id="nombre_error" class="text-danger"></span>
                                </div>
                                <div class="form-group mb-1">
                                    <label class="body-titles-container">Descripcion: </label>
                                    <input type="text" name="descripcion" id="descripcion" class="form-control" required>
                                    <span id="descripcion_error" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="body-titles-container">Configuraciones: </label>
                                <div class="card pl-2 d-flex">
                                    <div>
                                        <label> <input type="checkbox" name="config[]" value="Aplica frecuencia" id="aplica_frecuencia" onchange="configFrecuencia(this)">Aplica Frecuencia</label>
                                        <label> <input type="checkbox" name="config[]" value="Soporte Obligatorio">Soporte obligatorio</label>
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const individualCheckbox = document.getElementById('individual');
                                            const grupalCheckbox = document.getElementById('grupal');

                                            individualCheckbox.addEventListener('change', function() {
                                                if (this.checked) {
                                                    grupalCheckbox.checked = false;
                                                    grupalCheckbox.disabled = true;
                                                } else {
                                                    grupalCheckbox.disabled = false;
                                                }
                                            });

                                            grupalCheckbox.addEventListener('change', function() {
                                                if (this.checked) {
                                                    individualCheckbox.checked = false;
                                                    individualCheckbox.disabled = true;
                                                } else {
                                                    individualCheckbox.disabled = false;
                                                }
                                            });
                                        });
                                    </script>
                                    <div class="card d-flex pl-2 pr-2">
                                        <label class="body-titles-container">Frecuencia (DIAS): </label>
                                        <input type="text" name="frecuencia" id="frecuencia" maxlength="3" class="form-control mb-2" value="0" readonly>
                                        <script>
                                            function configFrecuencia(checkbox) {
                                                let frecuenciaInput = document.getElementById("frecuencia");
                                                frecuenciaInput.readOnly = !checkbox.checked; // Habilita o deshabilita el campo de frecuencia según el estado del checkbox
                                                frecuenciaInput.value = "0"; //Vacía el campo de frecuencia cuando el checkbox se desmarca
                                            }

                                            /* Evento que permite agregar la frecuencia.: */
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const aplicaFrecuenciaCheckbox = document.getElementById('aplica_frecuencia');

                                                // Maneja el evento change del checkbox de "Aplica Frecuencia"
                                                aplicaFrecuenciaCheckbox.addEventListener('change', function() {
                                                    const frecuenciaInput = document.getElementById("frecuencia");
                                                    frecuenciaInput.readOnly = !this.checked;
                                                    frecuenciaInput.value = "0";
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="card d-flex mb-2 pb-2">
                                <label class="body-titles-container">Tipo de reunion:</label>
                                <div class="d-flex flex-wrap">
                                    <label class="mr-2"><input type="radio" name="tipo_reunion" value="individual">Individual</label>
                                    <label class="mr-2"><input type="radio" name="tipo_reunion" value="primario">Primario</label>
                                    <label class="mr-2"><input type="radio" name="tipo_reunion" value="pares">Pares</label>
                                    <label class="mr-2"><input type="radio" name="tipo_reunion" value="max 10">Max 10</label>
                                    <label class="mr-2"><input type="radio" name="tipo_reunion" value="country">Country</label>
                                    <label class="mr-2"><input type="radio" name="tipo_reunion" value="compras">Compras</label>
                                    <label class="mr-2"><input type="radio" name="tipo_reunion" value="merco">Merco</label>
                                    <label class="mr-2"><input type="radio" name="tipo_reunion" value="ranking">Ranking</label>
                                    <label class="mr-2"><input type="radio" name="tipo_reunion" value="indicadores">Indicadores</label>
                                    <label class="mr-2"><input type="radio" name="tipo_reunion" value="retroalimentacion">Retroalimentacion</label>
                                    <label><input type="radio" name="tipo_reunion" value="sostenibilidad">Sostenibilidad</label>
                                </div>
                            </div>
                            <div class="card d-flex mb-2 pb-2">
                                <label class="body-titles-container">Subir imagen</label>
                                <input type="file" name="imagen_espacio" id="imagen_espacio">
                                <span id="imagen_espacio_error" class="text-danger"></span>
                            </div>
                            <div class="row d-flex flex-wrap">
                                <div class=" card col-lg-6 col-md-12 sol-sm-12 mb-2">
                                    <b class="body-titles-container">¿Qué cargos pueden agendar el espacio?</b>
                                    <div class="row">
                                        @foreach($cargos as $cargo)
                                            <div class="col-6">
                                                <label class="checkboxFont">
                                                    <input type="checkbox" name="cargos[]" value="{{ $cargo->id }}">
                                                    {{ $cargo->nombre }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class=" card col-lg-6 col-md-12 sol-sm-12 mb-2">
                                    <b class="body-titles-container">¿Qué áreas participan en el espacio?</b>
                                    <div class="row">
                                        @foreach($areas as $area)
                                            <div class="col-6">
                                                <label class="checkboxFont">
                                                    <input type="checkbox" name="areas[]" value="{{ $area->id }}">
                                                    {{ $area->nombre }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row w-100">
                                <div class="col-12">
                                    <label class="body-titles-container">Guía para la sesion:</label>
                                    <textarea class="form-control w-100" id="guia" name="guia" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light btn-modal-cancel button-evidencia" id="btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-success button-evidencia" id="btnGuardar">Guardar espacio <i class="far fa-save"></i> </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--Final de contenido modal agregar espacios--}}

    {{--Contenido de modal para editar cargos y areas del espacio--}}
    <div class="modal fade" id="updateEspacioCargoArea" tabindex="-1" aria-labelledby="updateEspacioCargoAreaModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="post" action="{{ route('admin.espacios.update_assign') }}" id="updateEspacioCargoAreaForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateEspacioCargoAreaModal">Modificar Espacio con [ <strong>Id =></strong><span id="id_espacio_cargo_area"></span> ]</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 d-flex">
                            <input type="hidden" name="id_espacio_cargo_area_update" id="id_espacio_cargo_area_update">
                            <div class="form-group col-6">
                                <label for="nombre_espacio">Nombre de espacio: </label>
                                <input type="text" name="nombre_espacio_cargo_area" id="nombre_espacio_cargo_area" class="form-control" readonly>
                            </div>
                            <div class="form-group col-6">
                                <label for="descripcion_espacio">Descripcion: </label>
                                <input type="text" name="descripcion_espacio_cargo_area" id="descripcion_espacio_cargo_area" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-12 d-flex">
                            <div class="col-6">
                                <h5>¿Que cargos pueden asignar el espacio?</h5>
                                <div id="cargosUpdateContainer"></div>
                            </div>
                            <div class="col-6">
                                <h5>¿Que áreas pueden participar?</h5>
                                <div id="areasUpdateContainer"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light btn-modal-cancel button-evidencia" id="btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-success button-evidencia" id="btnUpdateEspacioCargoArea">Asignar datos <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--Final de contenido modal editar cargos y areas del esapcio--}}

    {{--Contenido de modal para editar campos del espacio--}}
    <div class="modal fade" id="updateEspacio" tabindex="-1" aria-labelledby="updateEspacioModal" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ route('admin.espacios.update') }}" id="updateEspacioForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateEspacioModal">Modificar Espacio con [ <strong>Id =></strong><span id="id_espacio"></span> <strong>, Nombre => </strong> <span id="esp_nombre"></span> ]</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex">
                        <div class="col-6">
                            <label for="">Configuraciones: </label>
                            <div class="card pl-2 d-flex">
                                <input type="hidden" name="id_espacio_update" id="id_espacio_update">
                                <div class="card">
                                    <label>
                                        <input type="checkbox" name="config[]" value="Aplica frecuencia" id="aplica_frecuencia_data_modal" onchange="configFrecuencia(this)">Aplica Frecuencia
                                    </label>
                                    <label>
                                        <input type="checkbox" name="config[]" value="Soporte Obligatorio" id="soporte_obligatorio_data_modal">Soporte obligatorio
                                    </label>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const individualCheckboxData = document.getElementById('individual_data_modal');
                                        const grupalCheckboxData = document.getElementById('grupal_data_modal');

                                        individualCheckboxData.addEventListener('change', function() {
                                            if (this.checked) {
                                                grupalCheckboxData.checked = false;
                                                grupalCheckboxData.disabled = true;
                                            } else {
                                                grupalCheckboxData.disabled = false;
                                            }
                                        });
                                        grupalCheckboxData.addEventListener('change', function() {
                                            if (this.checked) {
                                                individualCheckboxData.checked = false;
                                                individualCheckboxData.disabled = true;
                                            } else {
                                                individualCheckboxData.disabled = false;
                                            }
                                        });
                                    });
                                </script>

                                <div class="card d-flex pl-2 pr-2">
                                    <label for="frecuencia_data">Frecuencia (DIAS): </label>
                                    <input type="text" name="frecuencia_data" id="frecuencia_data" maxlength="3" class="form-control mb-2" value="0" readonly>
                                    <script>
                                        function configFrecuencia(checkbox) {
                                            let frecuenciaInputData = document.getElementById('frecuencia_data');
                                            frecuenciaInputData.readOnly = !checkbox.checked; // Habilita o deshabilita el campo de frecuencia según el estado del checkbox
                                            frecuenciaInputData.value = "0"; //Vacía el campo de frecuencia cuando el checkbox se desmarca
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nombre_espacio">Nombre: </label>
                                <input type="text" name="nombre_espacio" id="nombre_espacio" class="form-control">
                                <span id="nombre_espacio_error" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label for="descripcion_espacio">Descripcion: </label>
                                <input type="text" name="descripcion_espacio" id="descripcion_espacio" class="form-control">
                                <span id="descripcion_espacio_error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light btn-modal-cancel button-evidencia" id="btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-success button-evidencia" id="btnUpdateEspacio">Actualizar espacio <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{--Final de contenido modal editar espacios--}}
@stop

@section('css')
    <style>
        *{
            box-shadow: none !important;
        }
        .checkboxFont{
            font-weight: normal !important;
        }
        .swal2-container {
            z-index: 2001;
        }
        .btn-outline-success,
        .btn-edit-espacio{
            background-color: #007A3E !important;
            color: #FFFFFF !important;
        }

    </style>
    {{-- Genera el token para AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--bootstrap 5--}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

    {{--select 2--}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap" rel="stylesheet">

    {{--Estilos para el sidebar--}}
    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css')}}">
    <link rel="stylesheet" href="{{asset('css/modal-save-evidencia.css')}}">
@stop

@section('js')
    {{--bootstrap 5--}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    {{--DataTables--}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script> {{--JQUERY--}}

    {{--select2--}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- Sweetalert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{--Ckeditor CDN--}}
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

    <script type="text/javascript">
        $(function (){

            loadEspacios(); 

            /*Select2*/
            $('.select2').select2();

            /*Agrega el registro del espacio*/
            $('#formAddEspacio').submit(function(event) {
                event.preventDefault();

                Swal.fire({
                    title: '¿Está seguro de agregar el espacio?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData(this); // Crear un objeto FormData con los datos del formulario

                        $.ajax({
                            type: 'post',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('espacio.agregar_espacio') }}",
                            data: formData,
                            dataType: 'json',
                            contentType: false, // No establecer contentType a false para que jQuery no lo sobrescriba
                            processData: false, // No procesar datos para que jQuery no lo transforme en una cadena

                            success: function(data) {
                                if (data.success) {
                                    $('#formAddEspacio')[0].reset();

                                    Swal.fire('¡Espacio agregado!', 'El espacio ha sido agregado correctamente', 'success' ).then(() => {
                                        loadEspacios(); // Prueba recarga si reload // location.reload(); // refresca la pestaña
                                    });

                                } else {
                                    if (data.errors) {
                                        $.each(data.errors, function (key, value) {
                                            var errorMessages = {
                                                'id_corporativo': 'Seleccione un corporativo',
                                                'nombre': 'Ingrese un nombre válido.',
                                                'descripcion': 'Ingrese una descripción válida.',
                                                'imagen_espacio': 'Seleccione enformato JPG, PNG, GIF,JPEG',
                                            };
                                            var errorMessage = errorMessages[key] || value;

                                            // Mostrar el mensaje de error junto al input correspondiente
                                            $('#' + key + '_error').text(errorMessage);
                                        });
                                    }
                                }
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                swal.fire('Error de solicitud', 'La solicitud no se realizó', 'error');
                            }
                        });
                    } else {
                        Swal.fire('¡Espacio no agregado!',
                            'Se ha cancelado el registro del espacio',
                            'error'
                        );
                    }
                });
            });

            /*Limpieza del formulario (modal) al presionar cancelar*/
            $('#addEspacio').on('hidden.bs.modal', function (e) {
                $('#formAddEspacio')[0].reset();
                $('#form_result').html(''); // Limpiar mensajes de error
            });

            /*PARA UTILIZAR CKEDITOR DE TEXTO: */
            ClassicEditor
                .create(document.querySelector('#guia'))
                .catch(error => {
                    console.error(error);
                });
        });

        // Carga de espacios mediante AJAX:
        function loadEspacios(){
            $.get('/admin/espacio/espacios', function(data) {
                if (data.status === 'ok') {
                    $('#cargaEspaciosAjax').html(data.vista);
                }
            });
        };
    </script>
@stop
