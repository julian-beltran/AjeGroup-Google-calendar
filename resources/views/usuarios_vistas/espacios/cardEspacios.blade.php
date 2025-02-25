
<div class="row d-flex flex-wrap">
    @foreach($espacios as $espacio)
        <div class="col-md-4">
            <div class="card card-space"> <!-- style="height: 400px; overflow-y: scroll !important;" -->
                <img src="/imageEspacios/{{ $espacio->adjunto }}" class="card-img-top" alt="Imagen del espacio">
                <div class="card-body mt-3">
                    <div class="form-group">
                        <h5 class="card-title"><strong class="title-card-content">ESPACIO:</strong> {{ $espacio->nombre }}</h5>
                    </div> <br>
                    <div class="form-group">
                        <h6 class="card-subtitle"><strong class="title-card-content">Corporativo:</strong> {{ $espacio->corporativo->nombre }}</h6>
                    </div>
                    <div class="form-group">
                        <p class="card-text"><strong class="title-card-content">Descripcion: </strong>{{ $espacio->descripcion }}</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong class="title-card-content">Configuraciones:</strong><br>
                            @foreach (explode(',', $espacio->config) as $index => $configData)
                                @if ($loop->last)
                                    {{ $configData }}
                                @else
                                    {{ $configData }} /
                                @endif
                            @endforeach
                        </li>
                        <li class="list-group-item"><strong class="title-card-content">Frecuencia:</strong> {{ $espacio->frecuencia }} días</li>
                        <li class="list-group-item"><strong class="title-card-content">Cargos:</strong><br>
                            @foreach ($espacio->cargos as $cargo)
                                {{ $cargo->nombre }} <br>
                            @endforeach
                        </li>
                        <li class="list-group-item"><strong class="title-card-content">Areas:</strong><br>
                            @foreach ($espacio->areas as $area)
                                {{ $area->nombre }} <br>
                            @endforeach
                        </li>
                    </ul>
                    <a href="javascript:void(0)" data-url="/admin/espacio/assign_cargos_areas/{{ $espacio->id }}" class="btn btn-outline-secondary show-espacio-cargo-area-modal"><i class="fas fa-sliders-h"></i></a>
                    <a href="javascript:void(0)" data-url="/admin/espacio/setting_espacio/{{ $espacio->id }}" class="btn btn-edit-espacio show-espacio-modal"><i class="fas fa-edit"></i></a>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{--bootstrap 5--}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
{{--bootstrap 5--}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
<script>
    $(function(){
        /*Solicitud AJAX para obtener los cargos y áreas del espacio*/
        $('.show-espacio-cargo-area-modal').on('click', function (){
                let url = $(this).data('url');
                $.ajax({
                   url: url,
                   type: "GET",
                   dataType: "json",
                   success: function (data){
                       // Mostrando datos del espacio en el modal:
                       $('#id_espacio_cargo_area').text(data.espacio.id);
                       $('#id_espacio_cargo_area_update').val(data.espacio.id);
                       $('#nombre_espacio_cargo_area').val(data.espacio.nombre);
                       $('#descripcion_espacio_cargo_area').val(data.espacio.descripcion);

                       // Mostrando datos de los cargos y áreas en el modal
                       let cargosHtml = '';
                       data.cargos.forEach(function(cargo){
                           let checked = data.assignedCargos.some(function(assignedCargo){
                               return assignedCargo.id === cargo.id;
                           });
                           cargosHtml += '<div><label><input type="checkbox" name="cargos[]" value="' + cargo.id + '"' + (checked ? ' checked' : '') + '>' + cargo.nombre + '</label></div>';
                       });
                       $('#cargosUpdateContainer').html(cargosHtml);

                       let areasHtml = '';
                       data.areas.forEach(function(area){
                           let checked = data.assignedAreas.some(function(assignedArea){
                               return assignedArea.id === area.id;
                           });
                           areasHtml += '<div><label><input type="checkbox" name="areas[]" value="' + area.id + '"' + (checked ? ' checked' : '') + '>' + area.nombre + '</label></div>';
                       });
                       $('#areasUpdateContainer').html(areasHtml);


                       // Mostrando el modal:
                       $('#updateEspacioCargoArea').modal('show');
                   },
                    error: function(error){
                        console.log('Error: ', error);
                        Swal.fire('error', 'Solicitud Ajax no enviada', 'error');
                    }
                });
            });

            $('#updateEspacioCargoAreaForm').submit(function (event){
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
                        //      let espacio_id = $('#id_espacio_update_cargo_area');
                        //      console.log('Espacio: '+espacio_id);
                        $.ajax({
                           url: $('#updateEspacioCargoAreaForm').attr('action'),
                           type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data){
                                $('#updateEspacioCargoAreaForm')[0].reset();

                                Swal.fire( '¡Espacio actualizado!', 'El espacio ha sido actualizado correctamente', 'success' ).then(() => {  loadEspacios();  /*location.reload();*/  });

                                $('#updateEspacioCargoArea').modal('hide');
                            },
                            error: function (error){
                                console.log('Error al asignar cargo y area al espacio', error);
                                Swal.fire('Error', 'Ocurrió un error al asignar cargo y area al espacio', 'error' );
                            }
                        });
                    }
                });
            })

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /*Solicitud Ajax para ver los detalles del espacio:*/
            $('.show-espacio-modal').on('click', function(){
                let url = $(this).data('url');
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data){
                        //Mostrando los datos del espacio en el modal:
                        $('#id_espacio').text(data.espacio.id);
                        $('#id_espacio_update').val(data.espacio.id);
                        $('#esp_nombre').text(data.espacio.nombre);
                        $('#nombre_espacio').val(data.espacio.nombre);
                        $('#descripcion_espacio').val(data.espacio.descripcion);
                        $('#frecuencia_data').val(data.espacio.frecuencia);

                        console.log("Configuración JSON:", data.espacio.config);
                        // Convertir el JSON de configuraciones a un objeto JavaScript
                        var configData = JSON.parse(data.espacio.config);
                        console.log("Configuración parseada:", configData);
                        // Iterar sobre las configuraciones del espacio que tenga seleccionada:
                        if (Array.isArray(configData)) {
                            configData.forEach(function(config) {
                                if (config === 'Aplica frecuencia') {
                                    $('#aplica_frecuencia_data_modal').prop('checked', true);
                                } else if (config === 'Individual') {
                                    $('#individual_data_modal').prop('checked', true);
                                } else if (config === 'Grupal') {
                                    $('#grupal_data_modal').prop('checked', true);
                                } else if (config === 'Soporte Obligatorio') {
                                    $('#soporte_obligatorio_data_modal').prop('checked', true);
                                }
                            });
                        }
                        $('#updateEspacio').modal('show');
                    },
                    error: function(error){
                        console.log('Error: ', error);
                    }
                });
            });

            /* Solicitud Ajax para actualizar los detalles del espacio */
            $('#updateEspacioForm').submit(function(event){
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
                        let espacio_id = $('#id_espacio_update').val();
                        console.log('ESPACIO: '+espacio_id);
                        $.ajax({
                            url: $('#updateEspacioForm').attr('action'), // Obtén la URL del atributo action del formulario
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success:function (response){
                                if (response.success) {                                
                                    $('#updateEspacioForm')[0].reset();

                                    Swal.fire('¡Espacio actualizado!',
                                        'El espacio ha sido actualizado correctamente',
                                        'success'
                                    ).then(() => {
                                        loadEspacios(); // Prueba recarga si reload // location.reload(); // refresca la pestaña
                                    });
                                } else {
                                    if (response.errors) {
                                        $.each(response.errors, function (key, value) {
                                            var errorMessages = {
                                                'nombre_espacio': 'Ingrese un nombre válido.',
                                                'descripcion_espacio': 'Ingrese una descripción válida.',
                                            };

                                            var errorMessage = errorMessages[key] || value;

                                            // Mostrar el mensaje de error junto al input correspondiente
                                            $('#' + key + '_error').text(errorMessage);
                                        });
                                    }
                                    Swal.fire('Error',
                                        'Por favor corrige los errores en el formulario',
                                        'error'
                                    );
                                }
                            },
                            error:function (error){
                                console.log('Error al actualizar el espacio', error);
                                Swal.fire('Error', 'Ocurrió un error al actualizar el espacio', 'error' );
                            }
                        });
                    }
                });
            });
    })
</script>
