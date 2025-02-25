@extends('adminlte::page')

@section('title', 'Dashboard | Google Calendar API')

@section('content_header')
    <h5>Administración de las agendas de google calendar.</h5>
@stop

@section('content')
    <div class="py-12 mt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="container">

                    <!--
                        <div class="row justify-content-center col-12 d-flex">
                           <!-on trigger modal
                           <div class="col-3">
                               <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Ver modal</button>
                           </div>
                        </div>
                    -->
                    <div class="row justify-content-center">
                        <div id="calendar" style="height: 600px !important;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--
        Inicio del contenido del modal
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Vista del calendario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modal_calendario_google" style="height: 800px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
    --}}

@stop

@section('css')
    {{-- Genera el token para AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--bootstrap 5--}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css')}}">
    <style>
        *{
            list-style: none !important;
        }
        .fc-event {
            font-size: 11px; /*Agrega estilo al cintenido de los calendars por default*/
            color: black;
        }
    </style>
@stop

@section('js')
    {{--bootstrap 5--}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var modalCalendarEl = document.getElementById('modal_calendario_google');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach ($events as $event)
                        {
                            title: '{{ $event->summary }}',
                            start: '{{ $event->start->dateTime }}',
                            end: '{{ $event->end->dateTime }}'
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

            /*var modalCalendar = new FullCalendar.Calendar(modalCalendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach ($events as $event)
                        {
                            title: '{{ $event->summary }}',
                            start: '{{ $event->start->dateTime }}',
                            end: '{{ $event->end->dateTime }}'
                        },
                    @endforeach
                ],
            });*/

            calendar.render();

            // Renderiza el calendario dentro del modal solo cuando se abre el modal
            /*$('#exampleModal').on('shown.bs.modal', function () {
                modalCalendar.render();
            });*/
        });
    </script>

@stop

