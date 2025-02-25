let input_mes    = document.getElementById('mes-filtro');
let fecha_actual = new Date();
// Obtener el año y el mes actual 
let fecha_input  = fecha_actual.getFullYear() + '-' + ('0' + (fecha_actual.getMonth() + 1)).slice(-2);
input_mes.value   = fecha_input;
input_mes.max     = fecha_input;

/* ************************************************************************************************ */
