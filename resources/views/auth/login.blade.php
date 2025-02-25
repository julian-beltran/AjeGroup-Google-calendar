<x-guest-layout class="w-100">
    <div class="container-fluid contenedor-total d-flex flex-column flex-lg-row col-lg-12 col-md-12 col-sm-12 w-100 justify-content-center align-items-center" style="width: 100% !important;">
        <div class="row w-100 vh-100" style="display: flex !important; width: 100% !important; margin: 0 !important;">
            <div class="col-lg-6 col-md-6 col-sm-12 d-flex align-items-center justify-content-center contenedor-imagen-1" style="">
                <div class="w-100 contenedor-imagen-2">
                    {{--<img src="{{asset('icons/login.png')}}" alt="" class="logo-img">
                    <img src="{{asset('enterprise/login_agendas.jpeg')}}" alt="login-alt" class="logo-img">
                    --}}
                    <div class="logo-img"></div>
                    <img src="{{asset('enterprise/vector.png')}}" alt="brand" class="title-login-img">

                    <div class="texto">
                        <strong class="texto-login">¡Bienvenido al modelo de gestión de AJE!</strong>
                        <p class="texto-login">La herramienta que fomenta la sinergia en los equipos, el enfoque hacia el logro de los objetivos y la evaluación de los indicadores de cumplimiento</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-center align-items-center contenedor-form-1">
                <div class="w-100 contenedor-form-2">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="encabezado">
                            <h2><strong>Inicio de sesión</strong></h2>
                            <p>Completa los datos para acceder a nuestra plataforma de gestión AJE.</p>
                        </div>

                        <div class="errors-validation">
                            <x-validation-errors class="mb-1" />
                            @if (session('status'))
                                <div class="mb-4 font-medium text-sm text-green-600">
                                    {{ session('status') }}
                                </div>
                            @endif
                        </div>

                        <div class="input-container">
                            <label for="email" class="input-icon">
                                <i class='bx bx-user-circle'></i>
                            </label>
                            <x-input id="email" class="block mt-1 w-full pl-12" type="email" name="email" placeholder="Correo Electronico" :value="old('email')" required autofocus autocomplete="username" />
                        </div>
                        <div class="mt-4 input-container">
                            <label for="password" class="input-icon">
                                <i class='bx bx-lock'></i>
                            </label>
                            <x-input id="password" class="block mt-1 w-full pl-12" type="password" name="password" placeholder="Contraseña" required autocomplete="current-password" />
                        </div>
                        <div class="block">
                            <label for="remember_me" class="flex items-center">
                                <x-checkbox id="remember_me" name="remember" />
                                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>
                        </div>
                        <div class="flex items-center justify-end">
                            {{--@if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                    {{ __('Olvidaste tu contraseña?') }}
                                </a>
                            @endif--}}
                            <x-button id="loginButton" class="ms-4 button-login">
                                {{ __('Iniciar sesión') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const loginButton = document.getElementById('loginButton');

            // Función para comprobar si ambos campos están vacíos
            function checkInputs() {
                const emailValue = emailInput.value.trim();
                const passwordValue = passwordInput.value.trim();

                if (emailValue !== '' && passwordValue !== '') {
                    loginButton.removeAttribute('disabled');
                } else {
                    loginButton.setAttribute('disabled', 'disabled');
                }
            }

            // Agregar evento de escucha a los campos de entrada
            emailInput.addEventListener('input', checkInputs);
            passwordInput.addEventListener('input', checkInputs);
        });
    </script>
</x-guest-layout>
