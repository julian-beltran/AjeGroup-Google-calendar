<script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Función para inicializar el calendario
            function initCalendar(calendarId) {
                var modalCalendarEvent = document.getElementById(calendarId);
                var modalCalendar = new FullCalendar.Calendar(modalCalendarEvent, {
                    locale: 'es',
                    initialView: 'dayGridMonth',
                    events: [
                        @foreach ($events as $event){
                            title: '{{ $event->summary }}',
                            start: '{{ $event->start->dateTime }}',
                            end: '{{ $event->end->dateTime }}',
                            color: 'green',
                        },
                        @endforeach
                    ],
                    locales: 'es',
                    locale: 'es',
                    buttonText: {
                        today: 'Hoy'
                    },
                    viewDidMount: function(view) {
                        var monthHeader = document.querySelector('.fc-toolbar-title');
                        var monthHeaderText = monthHeader.textContent;
                        var capitalizedMonthHeaderText = monthHeaderText.charAt(0).toUpperCase() + monthHeaderText.slice(1);
                        monthHeader.textContent = capitalizedMonthHeaderText;
                    }
                });

                // Renderiza el calendario cuando se muestre el modal correspondiente
                $('#' + calendarId).closest('.modal').on('shown.bs.modal', function () {
                    modalCalendar.render();
                });
            }

            // Inicializa el calendario para cada ID
            initCalendar('calendario-modal-max10');
            initCalendar('calendario-modal-pares');
            initCalendar('calendario-modal-ranking');
            initCalendar('calendario-modal-retroalimentacion');
            initCalendar('calendario-modal-google');
            initCalendar('calendario-modal-agendas');
            initCalendar('calendario-modal-country');
            initCalendar('calendario-modal-primario');
            initCalendar('calendario-modal-compras');
            initCalendar('calendario-modal-merco');
            initCalendar('calendario-modal-indicadores');
            initCalendar('calendario-modal-sostenibilidad');


            // Inicializa el calendario principal si es necesario (no proporcionaste el ID)
            var calendarEl = document.getElementById('calendar');
            if (calendarEl) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: 'es',
                    initialView: 'dayGridMonth',
                });
                calendar.render();
            }
        });

        /************************************************************************************************************/
        /********* CODIGO PARA VER DATOS Y AGREGAR EVENTOS PARA: pares, max 10, ranking, retroalimentacion******** */
        /******* MAX 10 -> Big con el equipo *******/
        $(document).on('click', '#agendas_max_10', function (event) {
            event.preventDefault();
            var url     = $(this).data('url');

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    $('#espacio_name').text(data.espacio.nombre);
                    $('#id_espacio_grupal').val(data.espacio.id);
                    $('#espacio_grupal').val(data.espacio.nombre);
                    $('#desc_esp_grupal').val(data.espacio.descripcion);
                    $('#id_area_grupal').val(data.area.id);
                    $('#area_name_grupal').val(data.area.nombre);
                    $('#id_corporativo_grupal').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectmax10" class="form-control form-control-sm" style="width: 100% !important;">';
                    areasUserHtml += '<option value="">SELECCIONAR AREA</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_max_10').html(areasUserHtml);

                    $('#selectmax10').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalGrupal').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /******* Pares -> Café con pares *******/
        $(document).on('click', '#agendas_pares', function (event) {
            event.preventDefault();
            var url     = $(this).data('url');

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    $('#espacio_pares').text(data.espacio.nombre);
                    $('#id_espacio_pares').val(data.espacio.id);
                    $('#espacio_pares_name').val(data.espacio.nombre);
                    $('#desc_esp_pares').val(data.espacio.descripcion);
                    $('#id_area_pares').val(data.area.id);
                    $('#area_name_pares').val(data.area.nombre);
                    $('#id_corporativo_pares').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectpares" class="form-control form-control-sm" style="width: 100% !important;">';
                    areasUserHtml += '<option value="">SELECCIONAR AREA</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_pares').html(areasUserHtml);

                    $('#selectpares').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalPares').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /******* Ranking -> RANKING *******/
        $(document).on('click', '#agendas_ranking', function (event) {
            event.preventDefault();
            var url     = $(this).data('url');

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    $('#espacio_ranking').text(data.espacio.nombre);
                    $('#id_espacio_ranking').val(data.espacio.id);
                    $('#espacio_ranking_name').val(data.espacio.nombre);
                    $('#desc_esp_ranking').val(data.espacio.descripcion);
                    $('#id_area_ranking').val(data.area.id);
                    $('#area_name_ranking').val(data.area.nombre);
                    $('#id_corporativo_ranking').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectranking" class="form-control form-control-sm" style="width: 100% !important;">';
                    areasUserHtml += '<option value="">SELECCIONAR AREA</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_ranking').html(areasUserHtml);

                    $('#selectranking').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalRanking').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /******* Retroalimentacion -> Retroalimentación y Acompañamiento *******/
        $(document).on('click', '#retroalimentacion_agendas', function (event) {
            event.preventDefault();
            var url     = $(this).data('url');

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    $('#espacio_retroalimentacion').text(data.espacio.nombre);
                    $('#id_espacio_retroalimentacion').val(data.espacio.id);
                    $('#espacio_retroalimentacion_name').val(data.espacio.nombre);
                    $('#desc_esp_retroalimentacion').val(data.espacio.descripcion);
                    $('#id_area_retroalimentacion').val(data.area.id);
                    $('#area_name_retroalimentacion').val(data.area.nombre);
                    $('#id_corporativo_retroalimentacion').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectretroalimentacion" class="form-control form-control-sm" style="width: 100% !important;">';
                    areasUserHtml += '<option value="">SELECCIONAR AREA</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_retroalimentacion').html(areasUserHtml);

                    $('#selectretroalimentacion').select2({
                        theme: "classic"
                    });


                    $('#agendarEventoModalRetroalimentacion').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });

        $('#formAddAgendaGrupalSeleccionUsuarios').submit(function(event) {
            agregarAgendaSeleccionUsuarios('formAddAgendaGrupalSeleccionUsuarios');
        });
        $('#formAddAgendaPares').submit(function(event) {
            agregarAgendaSeleccionUsuarios('formAddAgendaPares');
        });
        $('#formAddAgendaRanking').submit(function(event) {
            agregarAgendaSeleccionUsuarios('formAddAgendaRanking');
        });
        $('#formAddAgendaRetroalimentacion').submit(function(event) {
            agregarAgendaSeleccionUsuarios('formAddAgendaRetroalimentacion');
        });

        /************************************************************************************************************/
        /*Country -> Haciendo posible lo imposible*/
        $(document).on('click', '#country_agendas', function (event) {
            event.preventDefault();
            var url     = $(this).data('url');

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    $('#espacio_country').text(data.espacio.nombre);
                    $('#id_espacio_country').val(data.espacio.id);
                    $('#espacio_country_name').val(data.espacio.nombre);
                    $('#desc_esp_country').val(data.espacio.descripcion);
                    $('#id_area_country').val(data.area.id);
                    $('#area_name_country').val(data.area.nombre);
                    $('#id_corporativo_country').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectcountry" class="form-control form-control-sm" style="width: 100% !important;">';
                    areasUserHtml += '<option value="">SELECCIONAR AREA</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_country').html(areasUserHtml);

                    $('#selectcountry').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalCountry').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /*Primario -> Grupo primario*/
        $(document).on('click', '#agendas_primario', function(){
            event.preventDefault();
            var url     = $(this).data('url');

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    $('#espacio_primario').text(data.espacio.nombre);
                    $('#id_espacio_primario').val(data.espacio.id);
                    $('#espacio_primario_name').val(data.espacio.nombre);
                    $('#desc_esp_primario').val(data.espacio.descripcion);
                    $('#id_area_primario').val(data.area.id);
                    $('#area_name_primario').val(data.area.nombre);
                    $('#id_corporativo_primario').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectprimario" class="form-control form-control-sm" style="width: 100% !important; ">';
                    areasUserHtml += '<option value="">SELECCIONAR AREA</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_primario').html(areasUserHtml);

                    $('#selectprimario').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalPrimario').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /*Compras -> Comité de compras*/
        $(document).on('click', '#agendas_compras', function(){
            event.preventDefault();
            var url     = $(this).data('url');

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    $('#espacio_compras').text(data.espacio.nombre);
                    $('#id_espacio_compras').val(data.espacio.id);
                    $('#espacio_compras_name').val(data.espacio.nombre);
                    $('#desc_esp_compras').val(data.espacio.descripcion);
                    $('#id_area_compras').val(data.area.id);
                    $('#area_name_compras').val(data.area.nombre);
                    $('#id_corporativo_compras').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectcompras" class="form-control form-control-sm" style="width: 100% !important; ">';
                    areasUserHtml += '<option value="">SELECCIONAR AREA</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_compras').html(areasUserHtml);

                    $('#selectcompras').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalCompras').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /*Merco -> Merco*/
        $(document).on('click', '#agendas_merco', function(){
            event.preventDefault();
            var url     = $(this).data('url');

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    $('#espacio_merco').text(data.espacio.nombre);
                    $('#id_espacio_merco').val(data.espacio.id);
                    $('#espacio_merco_name').val(data.espacio.nombre);
                    $('#desc_esp_merco').val(data.espacio.descripcion);
                    $('#id_area_merco').val(data.area.id);
                    $('#area_name_merco').val(data.area.nombre);
                    $('#id_corporativo_merco').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectmerco" class="form-control form-control-sm" style="width: 100% !important;">';
                    areasUserHtml += '<option value="">SELECCIONAR AREA</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_merco').html(areasUserHtml);

                    $('#selectmerco').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalMerco').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /*indicadores -> dia de indicadores*/
        $(document).on('click', '#agendas_indicadores', function(){
            event.preventDefault();
            var url     = $(this).data('url');

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    $('#espacio_indicadores').text(data.espacio.nombre);
                    $('#id_espacio_indicadores').val(data.espacio.id);
                    $('#espacio_indicadores_name').val(data.espacio.nombre);
                    $('#desc_esp_indicadores').val(data.espacio.descripcion);
                    $('#id_area_indicadores').val(data.area.id);
                    $('#area_name_indicadores').val(data.area.nombre);
                    $('#id_corporativo_indicadores').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectindicadores" class="form-control form-control-sm" style="width: 100% !important;; ">';
                    areasUserHtml += '<option value="">SELECCIONAR AREA</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_indicadores').html(areasUserHtml);

                    $('#selectindicadores').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalIndicadores').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });
        /*Sostenibilidad -> Comité de sostenibilidad*/
        $(document).on('click', '#agendas_sostenibilidad', function(){
            event.preventDefault();
            var url     = $(this).data('url');

            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    $('#espacio_sostenibilidad').text(data.espacio.nombre);
                    $('#id_espacio_sostenibilidad').val(data.espacio.id);
                    $('#espacio_sostenibilidad_name').val(data.espacio.nombre);
                    $('#desc_esp_sostenibilidad').val(data.espacio.descripcion);
                    $('#id_area_sostenibilidad').val(data.area.id);
                    $('#area_name_sostenibilidad').val(data.area.nombre);
                    $('#id_corporativo_sostenibilidad').val(data.espacio.id_corporativo);

                    let areasUserHtml = '<select name="id_area_grupal" id="selectsostenibilidad" class="form-control form-control-sm" style="width: 100% !important;">';
                    areasUserHtml += '<option value="">SELECCIONAR AREA</option>';
                    data.areasUser.forEach(function(area){
                        areasUserHtml += '<option value="' + area.id + '">' + area.nombre + '</option>';
                    });
                    areasUserHtml += '</select>';
                    $('#areas_del_usuario_logueado_sostenibilidad').html(areasUserHtml);

                    $('#selectsostenibilidad').select2({
                        theme: "classic"
                    });

                    $('#agendarEventoModalSostenibilidad').modal('show');
                },
                error: function(error) {
                    console.log('Error al obtener los detalles de la agenda:', error);
                }
            });
        });

        $('#formAddAgendaPrimario').submit(function(event) {
            agregarAgendaGrupalArea('formAddAgendaPrimario');
        });
        $('#formAddAgendaCountry').submit(function(event) {
            agregarAgendaGrupalArea('formAddAgendaCountry');
        });
        $('#formAddAgendaCompras').submit(function(event) {
            agregarAgendaGrupalArea('formAddAgendaCompras');
        });
        $('#formAddAgendaMerco').submit(function(event) {
            agregarAgendaGrupalArea('formAddAgendaMerco');
        });
        $('#formAddAgendaIndicadores').submit(function(event) {
            agregarAgendaGrupalArea('formAddAgendaIndicadores');
        });
        $('#formAddAgendaSostenibilidad').submit(function(event) {
            agregarAgendaGrupalArea('formAddAgendaSostenibilidad');
        });
        /************************************************************************************************************/
        function agregarAgendaSeleccionUsuarios(formId) {
            event.preventDefault();
            Swal.fire({
                title: '¿Está seguro de agregar la agenda grupal?',
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
                    console.log('form enviado');
                    $.ajax({
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('anfitrion.dashboard.agregar_evento_seleccion_usuarios') }}",
                        data: $('#' + formId).serialize(),
                         dataType: 'json',
                        success: function(response, xhr){
                            if(response.status != 'success'){
                                $('#modalProgreso').modal('hide');
                                Swal.fire({
                                    title: '¡Agenda no agregada!',
                                    text: 'Ocurrió un percance en la creación de la agenda, reviselo con TI',
                                    iconHtml: '<img src="{{ asset('icons/icon_cancel.png') }}" class="icon_swal_fire">',
                                    showConfirmButton: true,
                                });
                            }else {
                                $('#modalProgreso').modal('hide');
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
                            $('#modalProgreso').modal('hide');
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
                            });
                        },
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var porcentaje = Math.round((evt.loaded / evt.total) * 100);
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
        }
        function agregarAgendaGrupalArea(formId) {
            event.preventDefault();
            Swal.fire({
                title: '¿Está seguro de agregar la agenda grupal?',
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
                        url: "{{ route('evento.dashboard.agregar_evento_grupal_area') }}",
                        data: $('#' + formId).serialize(),
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
                            }else {
                                $('#modalProgreso').modal('hide');
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
                            $('#modalProgreso').modal('hide');
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
                            });
                        },
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            // Procesamiento de la carga
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var porcentaje = Math.round((evt.loaded / evt.total) * 100);
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
        }
        /******************************************************************************************************************************/
        // BUSQUEDA DE USUARIOS POR AREAS:
        // Para Max10| pares | ranking | retroalimentacion
        $(document).on('change', '#selectmax10', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_grupal').val();

            if (area_id !== "") {
                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        let usuariosHtmlItem = '';
                        usuariosHtmlItem += '<div class="d-block">';
                        usuariosHtmlItem += '<select name="users[]" id="selectUsersMax10" multiple="multiple" class="usuariosPorArea form-control form-control-sm" data-placeholder="SELECCIONAR" style="width: 100% !important;">';

                        response.usuarios.forEach(function(user) {
                            usuariosHtmlItem += '<option value="' + user.user_id + '">' + user.name + '</option>';
                        });

                        usuariosHtmlItem += '</select>';
                        usuariosHtmlItem += '</div>';

                        $('#lista_usuarios_grupales').html(usuariosHtmlItem);

                        $('.usuariosPorArea').select2({
                            theme: "classic",
                            allowClear: true,maximumSelectionLength:10,language:{maximumSelected:function(args){return "Solo puedes seleccionar "+args.maximum+" invitados";}}
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectpares', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_pares').val();

            if (area_id !== "") {
                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        let usuariosHtmlItem = '';
                        usuariosHtmlItem += '<div class="d-block">';
                        usuariosHtmlItem += '<select name="users[]" multiple="multiple" class="usuariosPorArea form-control form-control-sm" data-placeholder="SELECCIONAR" style="width: 100% !important;">';

                        response.usuarios.forEach(function(user) {
                            usuariosHtmlItem += '<option value="' + user.user_id + '">' + user.name + '</option>';
                        });

                        usuariosHtmlItem += '</select>';
                        usuariosHtmlItem += '</div>';

                        $('#lista_usuarios_pares').html(usuariosHtmlItem);

                        $('.usuariosPorArea').select2({
                            theme: "classic",
                            allowClear: true
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectranking', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_ranking').val();

            if (area_id !== "") {
                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        let usuariosHtmlItem = '';
                        usuariosHtmlItem += '<div class="d-block">';
                        usuariosHtmlItem += '<select name="users[]" multiple="multiple" class="usuariosPorArea form-control form-control-sm" data-placeholder="SELECCIONAR" style="width: 100% !important;">';

                        response.usuarios.forEach(function(user) {
                            usuariosHtmlItem += '<option value="' + user.user_id + '">' + user.name + '</option>';
                        });

                        usuariosHtmlItem += '</select>';
                        usuariosHtmlItem += '</div>';

                        $('#lista_usuarios_ranking').html(usuariosHtmlItem);

                        $('.usuariosPorArea').select2({
                            theme: "classic",
                            allowClear: true
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectretroalimentacion', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_retroalimentacion').val();

            if (area_id !== "") {
                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        let usuariosHtmlItem = '';
                        usuariosHtmlItem += '<div class="d-block">';
                        usuariosHtmlItem += '<select name="users[]" multiple="multiple" id="paresUsers" class="usuariosPorArea form-control form-control-sm" data-placeholder="SELECCIONAR" style="width: 100% !important;">';

                        response.usuarios.forEach(function(user) {
                            usuariosHtmlItem += '<option value="' + user.user_id + '">' + user.name + '</option>';
                        });

                        usuariosHtmlItem += '</select>';
                        usuariosHtmlItem += '</div>';

                        $('#lista_usuarios_retroalimentacion').html(usuariosHtmlItem);

                        $('.usuariosPorArea').select2({
                            theme: "classic",
                            allowClear: true
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        // primario | pares | compras | merco | indicadores | sostenibilidad
        $(document).on('change', '#selectprimario', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_primario').val();

            if (area_id !== "") {
                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        let usuariosHtmlItem = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '" checked>' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                        });

                        $('#lista_usuarios_primario').html(usuariosHtmlItem);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectcountry', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_country').val();

            if (area_id !== "") {
                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        let usuariosHtmlItem = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '" checked readonly>' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                        });

                        $('#lista_usuarios_country').html(usuariosHtmlItem);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectcompras', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_compras').val();

            if (area_id !== "") {
                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        let usuariosHtmlItem = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '" checked readonly>' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                        });

                        $('#lista_usuarios_compras').html(usuariosHtmlItem);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectmerco', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_merco').val();

            if (area_id !== "") {
                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        let usuariosHtmlItem = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '" checked readonly>' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                        });

                        $('#lista_usuarios_merco').html(usuariosHtmlItem);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectindicadores', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_indicadores').val();

            if (area_id !== "") {
                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {
                        let usuariosHtmlItem = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '" checked readonly>' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                        });

                        $('#lista_usuarios_indicadores').html(usuariosHtmlItem);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
        $(document).on('change', '#selectsostenibilidad', function(){
            let area_id    = $(this).val();
            let espacio_id = $('#id_espacio_sostenibilidad').val();

            if (area_id !== "") {
                $.ajax({
                    url: "{{ route('agenda.espacio.buscar_usuario_area') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        area_id: area_id,
                        espacio_id: espacio_id
                    },
                    success: function(response) {

                        let usuariosHtmlItem = '';
                        response.usuarios.forEach(function(user, index) {
                            usuariosHtmlItem +=
                                '<div class="d-flex flex-wrap">'+
                                    '<label style="padding-left:5px !important;">' +
                                    '<input type="checkbox" name="users[]" value="' + user.user_id + '" checked readonly>' +
                                    '<span style="margin-left: 5px;">' + user.name +'</span>' +
                                    '</label>'+
                                '</div>';
                        });

                        $('#lista_usuarios_sostenibilidad').html(usuariosHtmlItem);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                console.log('error en el acceso al ID del area.');
            }
        });
</script>
