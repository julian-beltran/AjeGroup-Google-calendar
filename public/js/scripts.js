$(document).ready(function (){
    $('.select_2_view, #invitado_select_view').select2({
        theme: "classic"
    });

    // llamado a funcion de bloquear fechas:
    bloquearFechasPasadas('.fecha-hora-pasada');

    // Codigo para ajustar la altura del modal:
    function ajustarAlturaModal() {
        var alturaVentana = $(window).height();
        // console.log('Altura de ventana: ', alturaVentana);
        $('.modal-right .modal-dialog').css('height', alturaVentana + 'px');
    }
    // Llamar a la función para ajustar la altura del modal cuando se cargue la página
    ajustarAlturaModal();
    // Llama a la dunción para ajustar la altura del modal cuando se redimensione la ventana
    $(window).resize(function(){
        ajustarAlturaModal();
    });

    // Bloquear fechas y horas menores a la actual:
    function bloquearFechasPasadas(selector) {
        var fechaActual = new Date();
        var anio = fechaActual.getFullYear();
        var mes = ('0' + (fechaActual.getMonth() + 1)).slice(-2);
        var dia = ('0' + fechaActual.getDate()).slice(-2);
        var horas = ('0' + fechaActual.getHours()).slice(-2);
        var minutos = ('0' + fechaActual.getMinutes()).slice(-2);

        var fechaHoraActual = anio + '-' + mes + '-' + dia + 'T' + horas + ':' + minutos;

        $(selector).attr('min', fechaHoraActual);
    }

});
