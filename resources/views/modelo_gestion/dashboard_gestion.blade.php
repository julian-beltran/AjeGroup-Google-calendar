@extends('adminlte::page')

@section('title', 'Modelo de gestión')

@section('content_header')
@stop

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center mt-2">
            <div class="d-flex align-items-start flex-column flex-md-row">
                {{--Contenido de cada button tabs--}}
                <div class="col-lg-8 contenedor-div-tab tab-content mt-1 me-md-3 mb-3 mb-md-0" id="v-pills-tabContent">
                    {{--Contenido de objetivos y estrategias--}}
                    <div class="tab-pane fade show active contenedor-tabs" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <div class="row mb-2 title-content-tab">
                            <strong>Objetivos y estrategia</strong>
                        </div>

                        <div class="row justify-content-center align-content-center d-flex contenido-vision-mision">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="card w-100" style="width: 18rem;">
                                    <img src="{{asset('imgs_gestion/mision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">
                                    {{--<img src="{{asset('enterprise/mision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">--}}
                                    <div class="card-body position-absolute start-0 top-50  translate-middle-y text-center">
                                        <p class="card-text">Misión.</p>
                                    </div>
                                </div>
                                <p>Crear oportunidades que generen de manera sostenible bienestar y salud</p>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="card w-100" style="width: 18rem;">
                                    <img src="{{asset('imgs_gestion/vision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">
                                    {{--<img src="{{asset('enterprise/mision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">--}}
                                    <div class="card-body position-absolute end-0 top-50 translate-middle-y text-center">
                                        <p class="card-text">Visión</p>
                                    </div>
                                </div>
                                <p>Ser líderes en productos y marcas saludables y valoradas.</p>
                            </div>
                        </div>

                        <div class="row justify-content-center align-content-center cultura-contenido">
                            <img src="{{asset('imgs_gestion/cultura_organizacional.png')}}">
                        </div>
                    </div>
                    {{--Contenido de comportamientos--}}
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <div class="row mb-2 title-content-tab">
                            <strong style="font-size: 20px; font-style: normal; font-weight: 600; line-height: 32px; color: #2F3233">Nuestros comportamientosa</strong>
                            <p class="content-yellow">El buen comportamiento empresarial no solo es una cuestión de cortesía, sino también una estrategia
                                <strong>clave para el éxito y la reputación de nuestra generación,</strong>  aquí te dejamos los
                                comportamientos de <strong>AJE</strong>
                            </p>
                        </div>

                        <div class="row justify-content-center align-content-center d-flex flex-wrap contenido-vision-mision">

                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div style="position: relative;">
                                        <img src="{{asset('imgs_gestion/arriesgados.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">
                                        {{--<img src="{{asset('enterprise/mision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">--}}
                                        {{--<div class="position-absolute start-0 top-50 translate-middle-y text-center" style="left: 10px;">
                                            <p class="card-text d-flex align-items-center" style="margin-bottom: 0;">
                                                <span class="texto-comportamientos" style="text-align: left;">Arriesgados.</span>
                                            </p>
                                        </div>--}}
                                    </div>
                                    <div class="card-body body-seccion-comportamientos">
                                        <li class="content-yellow">• Dejamos atrás los miedos</li>
                                        <li class="content-yellow">• Tomamos decisiones hacia el éxito</li>
                                        <li class="content-yellow">• Nos adaptamos a nuevos desafíos</li>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div style="position: relative;">
                                        <img src="{{asset('imgs_gestion/empoderados.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">
                                        {{--<img src="{{asset('enterprise/mision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">--}}
                                        {{--<div class="position-absolute start-0 top-50 translate-middle-y text-center" style="left: 10px;">
                                            <p class="card-text d-flex align-items-center" style="margin-bottom: 0;">
                                                <span class="texto-comportamientos" style="text-align: left;">Empoderados</span>
                                            </p>
                                        </div>--}}
                                    </div>
                                    <div class="card-body body-seccion-comportamientos">
                                        <li class="content-yellow">• Confiamos en nosotros mismos</li>
                                        <li class="content-yellow">• Tomamos decisiones asertivas</li>
                                        <li class="content-yellow">• Inspiramos a los demás</li>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div style="position: relative;">
                                        <img src="{{asset('imgs_gestion/soñadores.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">
                                        {{--<img src="{{asset('enterprise/mision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">--}}
                                        {{--<div class="position-absolute start-0 top-50 translate-middle-y text-center" style="left: 10px;">
                                            <p class="card-text d-flex align-items-center" style="margin-bottom: 0;">
                                                <span class="texto-comportamientos" style="text-align: left;">Soñadores</span>
                                            </p>
                                        </div>--}}
                                    </div>
                                    <div class="card-body body-seccion-comportamientos">
                                        <li class="content-yellow">• Confiamos en nosotros mismos</li>
                                        <li class="content-yellow">• Tomamos decisiones asertivas</li>
                                        <li class="content-yellow">• Inspiramos a los demás</li>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div style="position: relative;">
                                        <img src="{{asset('imgs_gestion/cuestionarios.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">
                                        {{--<img src="{{asset('enterprise/mision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">--}}
                                        {{--<div class="position-absolute start-0 top-50 translate-middle-y text-center" style="left: 10px;">
                                            <p class="card-text d-flex align-items-center" style="margin-bottom: 0;">
                                                <span class="texto-comportamientos" style="text-align: left;">Cuestionamos</span>
                                            </p>
                                        </div>--}}
                                    </div>
                                    <div class="card-body body-seccion-comportamientos">
                                        <li class="content-yellow">• Nos construimos unos a otros</li>
                                        <li class="content-yellow">• Brindamos soluciones</li>
                                        <li class="content-yellow">• Valoramos las diferencias de opiniones</li>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div style="position: relative;">
                                        <img src="{{asset('imgs_gestion/agiles.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">
                                        {{--<img src="{{asset('enterprise/mision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">--}}
                                        {{--<div class="position-absolute start-0 top-50 translate-middle-y text-center" style="left: 10px;">
                                            <p class="card-text d-flex align-items-center" style="margin-bottom: 0;">
                                                <span class="texto-comportamientos" style="text-align: left;">Ágiles</span>
                                            </p>
                                        </div>--}}
                                    </div>
                                    <div class="card-body body-seccion-comportamientos">
                                        <li class="content-yellow">• Buscamos excelencia en la ejecución</li>
                                        <li class="content-yellow">• Somos flexibles</li>
                                        <li class="content-yellow">• Productivos e innovadores</li>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div style="position: relative;">
                                        <img src="{{asset('imgs_gestion/sin_escusas.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">
                                        {{--<img src="{{asset('enterprise/mision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">--}}
                                        {{--<div class="position-absolute start-0 top-50 translate-middle-y text-center" style="left: 10px;">
                                            <p class="card-text d-flex align-items-center" style="margin-bottom: 0;">
                                                <span class="texto-comportamientos" style="text-align: left;">Sin excusas</span>
                                            </p>
                                        </div>--}}
                                    </div>
                                    <div class="card-body body-seccion-comportamientos">
                                        <li class="content-yellow">• Asumimos responsabilidad</li>
                                        <li class="content-yellow">• Respetamos tiempos de entrega</li>
                                        <li class="content-yellow">• Hacemos que las cosas pasen</li>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div style="position: relative;">
                                        <img src="{{asset('imgs_gestion/empaticos.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">
                                        {{--<img src="{{asset('enterprise/mision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">--}}
                                        {{--<div class="position-absolute start-0 top-50 translate-middle-y text-center" style="left: 10px;">
                                            <p class="card-text d-flex align-items-center" style="margin-bottom: 0;">
                                                <span class="texto-comportamientos" style="text-align: left;">Empáticos</span>
                                            </p>
                                        </div>--}}
                                    </div>
                                    <div class="card-body body-seccion-comportamientos">
                                        <li class="content-yellow">• Escuchamos sin juzgar</li>
                                        <li class="content-yellow">• Somos cercanos</li>
                                        <li class="content-yellow">• Reconocemos los logros</li>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div style="position: relative;">
                                        <img src="{{asset('imgs_gestion/de_alto_nivel.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">
                                        {{--<img src="{{asset('enterprise/mision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">--}}
                                        {{--<div class="position-absolute start-0 top-50 translate-middle-y text-center" style="left: 10px;">
                                            <p class="card-text d-flex align-items-center" style="margin-bottom: 0;">
                                                <span class="texto-comportamientos" style="text-align: left;">De alto nivel</span>
                                            </p>
                                        </div>--}}
                                    </div>
                                    <div class="card-body body-seccion-comportamientos">
                                        <li class="content-yellow">• Somos disciplinados</li>
                                        <li class="content-yellow">• Nos enfocamos en el resultado</li>
                                        <li class="content-yellow">• Tenemos metas claras</li>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div style="position: relative;">
                                        <img src="{{asset('imgs_gestion/nos_comunicamos.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">
                                        {{--<img src="{{asset('enterprise/mision.png')}}" class="card-img-top" alt="..." style="object-fit: cover;">--}}
                                        {{--<div class="position-absolute start-0 top-50 translate-middle-y text-center" style="left: 10px;">
                                            <p class="card-text d-flex align-items-center" style="margin-bottom: 0;">
                                                <span class="texto-comportamientos" style="text-align: left;">Nos comunicamos</span>
                                            </p>
                                        </div>--}}
                                    </div>
                                    <div class="card-body body-seccion-comportamientos">
                                        <li class="content-yellow">• Somos precisos</li>
                                        <li class="content-yellow">• Escuchamos atentamente</li>
                                        <li class="content-yellow">• Trabajamos en equipo</li>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{--Contenido de Reunión exitosa--}}
                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                        <div class="row mb-2 title-content-tab">
                            <strong>Pasos para una reunión exitosa</strong>
                            <p>Seguir un orden en tus reuniones proporciona una guía clara y estructurada que facilita la
                                organización, clarifica los objetivos, mejora la eficiencia, productividad, y asegura un
                                rendimiento adecuado de las decisiones tomadas.
                            </p>
                        </div>

                        <div class="row d-flex flex-wrap justify-content-center contenido-vision-mision">

                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div class="card-header card-header-one">
                                        <i class="fas fa-calendar-alt title-yellow"></i><br>
                                        <p class="card-title title-yellow">Planifica e informa a tu equipo</p>
                                    </div>
                                    <div class="card-body body-seccion-reunion-exitosa">
                                        <span class="card-text content-yellow">Evita llegar a un reunión de trabajo
                                            sin planificación y contacta a tu equipo con un e-mail
                                            con el día, la hora y las tomas u objetivos a tratar
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div class="card-header card-header-two reu-exitosa">
                                        <i class="far fa-clock title-i"></i><br>
                                        <p class="card-title text-light title-two">La puntualidad es primordial</p>
                                    </div>
                                    <div class="card-body body-seccion-reunion-exitosa">
                                        <span class="card-text content-yellow">Insiste en la puntualidad, ya que así será posible
                                            discutir todos los temas agregados, sin extender demaiado la reunión
                                            y cumplir el horario establecido
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div class="card-header card-header-three reu-exitosa">
                                        <i class="far fa-calendar title-i"></i><br>
                                        <p class="card-title title-three">Agenda tus contenidos</p>
                                    </div>
                                    <div class="card-body body-seccion-reunion-exitosa">
                                        <span class="card-text content-yellow">Informa con anticipación los temas que se discutirán,
                                            así los asistentes llegarán preparados al encuentro y con distintas
                                            ideas para proponer.
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div class="card-header card-header-four reu-exitosa">
                                        <i class="fas fa-ban title-i"></i><br>
                                        <p class="card-title title-four">Limita los temas según tu agenda</p>
                                    </div>
                                    <div class="card-body body-seccion-reunion-exitosa">
                                        <span class="card-text content-yellow">Procura seguir la agenda tal cual está establecida,
                                            es importante disponer el orden y la prioridad de los temas que se
                                            van a tratar en ésta.
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div class="card-header card-header-five reu-exitosa">
                                        <i class="fas fa-users title-i"></i><br>
                                        <p class="card-title title-five">Reuniones con un poco de humor</p>
                                    </div>
                                    <div class="card-body  body-seccion-reunion-exitosa">
                                        <span class="card-text content-yellow">La reunión de trabajo debe ser una instancia de discusión
                                            positiva, así que procura que al equivocarte, te diviertas, pero sin perder
                                            el enfoque en los avances y resolución de los temas.
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div class="card-header card-header-six">
                                        <i class="fas fa-rocket title-yellow"></i><br>
                                        <p class="card-title title-yellow">Sé dinámico</p>
                                    </div>
                                    <div class="card-body body-seccion-reunion-exitosa">
                                        <span class="card-text content-yellow">Haz de la reunión un espacio efectivo, dinámico y
                                            rápido; enfócate en que los asistentes opinen y expongan sus puntos de
                                            vista, así enriquecerás el encuentro y hará que éste no se vuelva
                                            monótomo y aburrido.
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div class="card-header card-header-seven">
                                        <i class="fas fa-users title-i"></i><br>
                                        <p class="card-title text-light title-two">Trabaja en equipo</p>
                                    </div>
                                    <div class="card-body body-seccion-reunion-exitosa">
                                        <span class="card-text content-yellow">La reunión será exitosa en la medida en que el expositor
                                            tenga liderazgo, interés, seguridad y sobretodo capacidad de organizar,
                                            convocar y ganarse la atención del equipo.
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 sol-sm-4">
                                <div class="card w-100" style="width: 18rem;">
                                    <div class="card-header card-header-eight">
                                        <i class="fas fa-sticky-note title-yellow"></i><br>
                                        <p class="card-title title-yellow">Toma nota</p>
                                    </div>
                                    <div class="card-body  body-seccion-reunion-exitosa">
                                        <span class="card-text content-yellow">Toma nota de los temas discutidos en la reunión, de
                                            esta forma se podrán recordar jerarquizar y llevar a cabo los planes y
                                            medidas propuestas y aceptadas por los asistentes.
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                {{--Contenido de button-tabs--}}
                <div class="col-lg-3 col-md-2 col-sm-12 contenedor-button-tab nav flex-column nav-pills mt-1" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active mb-2 btn-modelo-gestion" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true"> <i class="fas fa-bullseye"></i> Objetivos y estrategias</button>
                    <button class="nav-link mb-2 btn-modelo-gestion" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false"> <i class="fas fa-users"></i> Comportamientos</button>
                    <button class="nav-link mb-2 btn-modelo-gestion" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false"> <i class="far fa-handshake"></i> Reuniones exitosas</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Genera el token para AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--bootstrap 5--}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap" rel="stylesheet">

    {{--Estilos para el sidebar--}}
    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css')}}">
    <link rel="stylesheet" href="#">

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            height: auto !important;
        }
        .container-fluid{
            padding: 0 !important;
            margin: 0 !important;
        }
        .contenedor-div-tab{
             width: 100%;
             height: auto; /*86vh*/
             padding-bottom: 20px !important;
             /*background-color: grey;*/
             padding: 0px 20px;
         }
        .contenedor-div-tab .contenedor-tabs{
            /*display: grid;
            place-conten: center;
            place-items: center;
            text-align: left !important;*/
        }
        .title-content-tab > strong{
            font-size: 25px;
            color: black;
        }

        .contenedor-div-tab .contenido-vision-mision{
            display: flex !important;
            justify-content: center;
            justify-content: space-around;
            align-items: center;
            align-content: center;
        }
        .card-body > p{
            font-weight: bold;
            font-size: 25px;
            color: #FFFFFF; /*red*/
        }
        .texto-comportamientos{
            font-weight: bold;
            font-size: 23px;
            color: red;
            margin-left: 5px;
        }
        /*Inicio contenido*/
        .body-seccion-comportamientos,
        .body-seccion-reunion-exitosa{
            font-size: 12px !important;
            text-align: left !important;
            /*color: green;*/
        }
        /*Fin contenido*/
        /*Colors y backgrounds cards titles reunion exitosa:*/
        .reu-exitosa .title-two,
        .title-three,
        .title-four,
        .title-five,
        .title-i{
            color: white;
        }
        .card-header-one,
        .card-header-six,
        .card-header-eight{
            background-color: #FFD02E;
        }
        .title-yellow{
            color: #5C4700;
        }
        .content-yellow{
            color: #2F3233;
        }
        .card-header-two,
        .card-header-seven{
            background-color: #69488E;
        }
        .card-header-three,
        .card-header-five{
            background-color: #1F792E;
        }
        .card-header-four{
            background-color: #FF8300;
        }
        /***********************Fin styles***********************/
        .cultura-contenido{
            height: 300px !important;
        }
        .cultura-contenido > img{
            height: 100%;
            max-height: 100%;
        }
        /*********************************************Contenido del button*********************************************/
        .contenedor-button-tab{
            width: 100%;
            height: auto; /*86vh*/
            /*background-color: red;*/
            padding: 5px 0;
        }
        .contenedor-button-tab button{
            background-color: white !important;
            color: green !important;
            text-align: left !important;
            border: 1px solid #BFBFBF;
        }
        .contenedor-button-tab button:hover{
            color: #43B02A;
            border: 1px solid #43B02A;
            background: #F1FFEE !important;
        }
        .contenedor-button-tab .nav-link.active {
            background-color: #309B42 !important;
            color: #FFFFFF !important;
        }

        /********************************************/
        /*Contenido css para que el contenedor de buttons-tab pase a la cabecera cuando el
          sitio web sea responsive version mobile y el contendor-div-tabs baje al footer*/
        @media (max-width: 768px) {
            .container-fluid {
                display: flex;
                flex-direction: column;
                align-items: stretch;
            }
            .contenedor-div-tab {
                order: 2; /* Orden para visualizar contenido */
                margin-top: auto;
            }
            .contenedor-button-tab {
                order: 1; /* Orden para visualizar contenido */
                /*position: sticky;*/
                top: 0;
                z-index: 1000;
                background-color: #fff;
                padding: 10px;
            }
        }
        /********************************************/

    </style>

@stop

@section('js')
    {{--JQUERY--}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
@stop

