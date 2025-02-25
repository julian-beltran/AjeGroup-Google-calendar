<x-mail::message>
    Estimad@ <strong>{{ $user_name }}</strong>

    ** Usted tiene una agenda programada en el espacio "{{ $espacio_nombre }}"
       con fecha y hora: {{ \Carbon\Carbon::parse($fecha_hora_meet)->locale('es_ES')->isoFormat('dddd, D [de] MMMM - YYYY | h:mm A') }}


    ** Detalles de la agenda:
        {{--* **ID Corporativo: {{ $id_corporativo }}--}}
        * **Espacio con ID: {{ $id_espacio }} / Nombre: {{ $espacio_nombre }}
        {{--* **ID Área: {{ $id_area }}--}}
        * **Link meet: {{ $meet_link }}

    ------------------------
    Saludos cordiales,
    {{ config('app.name') }}
</x-mail::message>
