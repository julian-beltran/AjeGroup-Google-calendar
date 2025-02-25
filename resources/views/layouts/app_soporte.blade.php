<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- FAVICON => favicons/favicon.ico -->
        <link rel="shortcut icon" href="{{ asset('/favicons/favicon.ico') }}" type="image/x-icon">
        {{--Bootstrap 5--}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        {{--Bootstrap 5--}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        {{--SCRIPTS--}}
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        {{--SweetAlert--}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(function(){
                $('#addEventoFormCalendar').submit(function(event){
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
                            // let formData = new FormData(this);
                            $.ajax({
                                url: $(this).attr('action'), // Obtén la URL del atributo action del formulario
                                type: $(this).attr('method'),
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                    'Accept': 'application/json'
                                },
                                data: $('#addEventoFormCalendar').serialize(),
                                dataType: 'json',
                                success: function(response) {
                                    $('#addEventoFormCalendar')[0].reset();
                                    Swal.fire('¡Evento creado!', response.message, 'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                },
                                error: function(response){
                                    swal.fire('Error de solicitud', 'La solicitud no se realizó', 'error');
                                }
                            });
                        } else {
                            Swal.fire('¡Envio cancelado!',
                                'Se ha cancelado el registro del evento',
                                'error'
                            );
                        }
                    });

                })
            });
        </script>

        @livewireScripts
    </body>
</html>
