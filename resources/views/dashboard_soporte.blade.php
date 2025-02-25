{{--<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome />
            </div>
        </div>
    </div>
</x-app-layout> --}}

@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Vista de dashboard principal.</p>

    <div class="custom-modal-backdrop"></div>
    <div class="modal fade" id="loginStateModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header header-modal-google">
                    <h1 class="modal-title fs-5 title-header" id="loginModalLabel">Sincronización necesaria</h1>
                </div>
                <div class="modal-body">
                    <div class="button-modal-google pb-2">
                        <strong class="title-body">!Sincroniza tu cuenta de google¡</strong>
                        <p class="subtitle-body">Esta acción es necesaria para agendar sesiones con tu equipo.</p>
                    </div>
                    <div class="button-modal-google">
                        <button id="loginButton" class="but-conect-google rounded"><i class='bx bxl-google icono-google'></i> Conectar con Google</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap" rel="stylesheet">

    {{--Estilos para el sidebar--}}
    <link rel="stylesheet" href="{{asset('css/estilos_sidebar.css')}}">
    <style>
        .modal {
            z-index: 1050 !important;
        }
        .container{
            background-color: rgba(0, 0, 0, 0.5);
        }
        .custom-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /*ackground-color: rgba(0, 0, 0, 0.5);*/
            background: rgba(47, 50, 51, 0.50);
            z-index: 1050;
        }
        /*Modal header google*/
        .header-modal-google{
            background-color: #F5F5F5;
            color: #2F3233;
        }
        .title-header{
            font-size: 16px;
            font-style: normal;
            font-weight: 600;
            line-height: 24px;
        }
        .modal-body{
            color: #2F3233;
        }
        .title-body{
            text-align: center;
            font-size: 18px;
            font-style: normal;
            font-weight: 600;
            line-height: 24px;
        }
        .subtitle-body{
            text-align: center;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: 24px;
        }
        /*Button conctar con google*/
        .button-modal-google{
            display: grid;
            place-items: center;
            place-content: center;
            align-items: center;
        }
        .but-conect-google{
            /*background-color: forestgreen;
            color: white;
            padding: 5px 10px;*/
            display: flex;
            justify-content: center;
            padding: 12px 16px;
            align-items: center;
            gap: 8px;
            border: 1px solid #309B42;
            font-size: 14px;
            font-style: normal;
            font-weight: 600;
            line-height: 24px;
            color: #1F792E;
            background: #FFFFFF;
        }
        .but-conect-google i{
            font-size: 14px;
            font-style: normal;
            font-weight: 600;
            line-height: 24px;
            color: #1F792E;
        }

        .but-conect-google:hover{
            background-color: #F1FFEE;
        }
        .icono-google{
            color: white;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('loginStateModal');
            var loginButton = document.getElementById('loginButton');

            // Mostrar el modal
            var instance = M.Modal.init(modal, {dismissible: false});
            instance.open();

            // Redirigir al hacer clic en OK
            loginButton.addEventListener('click', function() {
                window.location.href = '/oauth'; // o route('calendar.api');
            });
        });
    </script>
@stop
