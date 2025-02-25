<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\users\UsersController;
use App\Http\Controllers\espacios\EspacioController;
use App\Http\Controllers\users\RoleController;
use App\Http\Controllers\agendas\AgendaExportarController;
use App\Http\Controllers\agendas\AgendaInivitadoController;
use App\Http\Controllers\agendas\AgendaAnfitrionController;

use App\Http\Controllers\enterprise\ApiController;
use App\Http\Controllers\calendar\DashboardAgendasOffController;
use App\Http\Controllers\gestion\ModeloGestionController;


/*
|--------------------------------------------------------------------------
| Web Routes -> Rutas para las vistas y solicitudes AJAX:
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth/login'); // welcome
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /**********************************************************************************************************************************************/
    // Rutas para el manejo de configuración de usuarios ==> Funciona
    Route::group(['prefix' => 'admin/usuario'], function(){
        Route::get('vista', [UsersController::class, 'index']);
        Route::post('lista', [UsersController::class, 'lista_usuarios']);
        // Route::get('lista', [UsersController::class, 'lista_usuarios'])->name('usuario.lista'); //obtiene la lista de usuarios en datatables
        Route::get('settings/{id}', [UsersController::class, 'administracion_usuario']); // obtiene el id del usuario parala administracion
        Route::put('save', [UsersController::class, 'guardar_administracion_usuario'])->name('usuario.guardar_administracion_user'); // actualizar administracion del usuario
        Route::get('profile', [UsersController::class, 'edit_profile_users'])->name('admin.usuario.profile'); // edicion del perfil del usuario
        Route::post('add', [UsersController::class, 'guardar_usuario'])->name('usuario.guardar_usuario'); // agrega usuarios

        // Edición del usuario

        Route::get('edit/{id}', [UsersController::class, 'edit_usuario'])->name('usuario.edit');
        Route::put('update_usuario',[UsersController::class, 'update_usuario'])->name('usuario.update');
        Route::delete('usuario_delete/{id}', [UsersController::class, 'delete_usuario'])->name('usuario.delete');
        
        // Subir usuarios por excel: 
        Route::post('users_excel', [UsersController::class, 'subir_usuarios_excel']);
    });

    /**********************************************************************************************************************************************/
    // Rutas para la administracion de roles y permisos ==> Funciona
    Route::group(['prefix' => 'admin/usuario/roles'], function () {
        Route::get('lista', [RoleController::class, 'ver_roles_permisos'])->name('usuario.roles.lista'); // Obtiene la lista de roles y permisos
        Route::get('asignar_permiso/{id}', [RoleController::class, 'asignar_permiso'])->name('usuario.roles.asignar'); // solicitud AJAX para obtener datos del rol
        Route::put('update_permisos', [RoleController::class, 'update_permisos'])->name('usuario.roles.update'); //se guardan las asignaciones
        Route::post('saveRoles', [RoleController::class, 'guardar_roles'])->name('usuario.roles.saveRoles'); // Agrega roles
        Route::post('savePermisos', [RoleController::class, 'guardar_permisos'])->name('usuario.roles.savePermisos'); // Agrega permisos
    });

    /**********************************************************************************************************************************************/
    // Rutas para la administracion de Espacios ==> Funciona
    Route::group(['prefix' => 'admin/espacio'], function(){
        Route::get('lista', [EspacioController::class, 'lista_espacios'])->name('espacio.lista'); // obtiene la lista de espacios
        
        Route::get('espacios', [EspacioController::class, 'espacios_data']);

        Route::post('addEspacio', [EspacioController::class, 'agregar_espacio'])->name('espacio.agregar_espacio'); // agrega espacios

        Route::get('assign_cargos_areas/{id}', [EspacioController::class, 'ver_datos_del_espacio_asignar'])->name('espacio.asignar.datos'); // obtiene los datos del espacio para asignar cargos y areas
        Route::put('update_asignacion_datos', [EspacioController::class, 'update_cargos_areas_de_espacio'])->name('admin.espacios.update_assign'); // asigna los cargos y áreas al espacio

        Route::get('setting_espacio/{id}', [EspacioController::class, 'show_espacio'])->name('admin.espacios.edit'); // obtiene los datos del espacio
        Route::put('update_espacio', [EspacioController::class, 'update_datos_de_espacio'])->name('admin.espacios.update'); // actualiza el el espacio
    });

    /**********************************************************************************************************************************************/
    // Rutas para el controlador del anfitrion --> FUNCIONA
    Route::group(['prefix'=>'admin/agenda/anfitrion'], function (){
        Route::get('vista', [AgendaAnfitrionController::class, 'index']);
        Route::post('lista', [AgendaAnfitrionController::class, 'ver_agendas_realizadas']);
        // Route::get('lista', [AgendaAnfitrionController::class, 'ver_agendas_realizadas'])->name('admin.agenda.anfitrion.lista');
        Route::get('ver_datos_agenda/{id}', [AgendaAnfitrionController::class, 'show_datos_agenda'])->name('admin.agenda.anfitrion.show_datos');

        Route::post('subir_evidencias', [AgendaAnfitrionController::class, 'subir_evidencia_agenda'])->name('admin.agenda.anfitrion.subir_evidencia');
        Route::get('ver_evidencias/{id}', [AgendaAnfitrionController::class, 'ver_evidencias_agendas'])->name('admin.agenda.anfitrion.ver_evidencia');
        Route::get('ver_invitados_agenda/{id}', [AgendaAnfitrionController::class, 'ver_invitados_de_agenda'])->name('admin.agenda.anfitrion.ver_invitados');

        // RUTAS PARA ACTUALIZAR Y EDITAR LAS AGENDAS GENERADAS:
        // Ruta para EDITAR y ELIMINAR AGENDAS DE GOOGLE CALENDAR, AGENDAS Y AGENDA_INVITADOS:
        Route::get('edit_agenda/{id}', [AgendaAnfitrionController::class, 'editar_agenda'])->name('admin.agenda.anfitrion.editar_agenda');
        Route::put('update_agenda', [AgendaAnfitrionController::class, 'update_agenda'])->name('admin.agenda.anfitrion.update_agenda');
        Route::delete('delete_agenda/{id}', [AgendaAnfitrionController::class, 'delete_agenda'])->name('admin.agenda.anfitrion.delete_agenda');
    });

    /**********************************************************************************************************************************************/
    // Rutas para el controller de invitados --> FUNCIONA
    Route::group(['prefix'=>'admin/agenda/invitados'], function (){
        Route::get('vista', [AgendaInivitadoController::class, 'index']);
        Route::post('lista', [AgendaInivitadoController::class, 'lista_agendas_programadas']);
        // Route::get('lista', [AgendaInivitadoController::class, 'lista_agendas_programadas'])->name('agenda.programada.lista');
        Route::get('download/{id}', [AgendaInivitadoController::class, 'ver_evidencias_de_agenda'])->name('agenda.programada.evidencias');
    });

    /**********************************************************************************************************************************************/
    // Rutas para exportar agendas: ----> FUNCIONA
    Route::group(['prefix'=>'admin/export'], function (){
        Route::get('vista', [AgendaExportarController::class, 'index']);
        Route::post('lista', [AgendaExportarController::class, 'ver_agendas_para_exportar']);
        // Route::get('lista', [AgendaExportarController::class, 'ver_agendas_para_exportar'])->name('agenda.exportar.lista'); // Obtiene listado en datatables de todas las agendas
        Route::post('exportar_agenda_en_excel', [AgendaExportarController::class, 'exportar_agendas_excel'])->name('agenda.exportar.excel'); // Exporta las agendas segun filtros en excel
        Route::get('ver_evidencia_agenda/{id}', [AgendaExportarController::class, 'show_evidencia_agenda_exportar'])->name('agenda.exportar.agenda'); // Verifica las evidencias de la agenda en un modal
        // Rutas para exportar: todas | agendadas | atendidas
        Route::post('exportar_todas_en_excel', [AgendaExportarController::class, 'exportar_todas_excel'])->name('agenda.exportar.todas_excel');
        Route::post('exportar_agendadas_en_excel', [AgendaExportarController::class, 'exportar_agendadas_excel'])->name('agenda.exportar.agendadas_excel');
        Route::post('exportar_atendidas_en_excel', [AgendaExportarController::class, 'exportar_atendidas_excel'])->name('agenda.exportar.atendidas_excel');
        Route::post('exportar_concluidas_en_excel', [AgendaExportarController::class, 'exportar_concluidas_excel'])->name('agenda.exportar.concluidas_excel');

        // Exportar todas las agendas atendidas en general
        Route::post('exportar_atendidas_general_excel', [AgendaExportarController::class, 'exportar_atendidas_general_excel'])->name('agenda.exportar.atendidas_general_excel');
    });

    /**********************************************************************************************************************************************/
    // Rutas de authenticacion para google calendar en dashboard..:
    Route::get('oauth', [DashboardAgendasOffController::class, 'oauth'])->name('calendar.oauth');
    Route::get('google_list', [DashboardAgendasOffController::class, 'index_google_calendar'])->name('admin.calendar.lista');

    /**********************************************************************************************************************************************/
    // Rutas para la administracion de Pais, Corporativo, Cargo, Area:
    Route::group(['prefix'=>'admin/enterprise'], function (){
        Route::get('lista', [ApiController::class, 'index'])->name('admin.enterprise.vista'); // Vista general
        Route::get('pais', [ApiController::class, 'getPaises'])->name('admin.enterprise.pais');
        Route::get('corporativo', [ApiController::class, 'getCorporativos'])->name('admin.enterprise.corporativo');
        Route::get('cargo', [ApiController::class, 'getCargos'])->name('admin.enterprise.cargo');
        Route::get('area', [ApiController::class, 'getAreas'])->name('admin.enterprise.area');

        // Agregar registros de cada tab:
        Route::post('add_pais', [ApiController::class, 'add_pais'])->name('admin.enterprise.guardar_pais');
        Route::post('add_corporativo', [ApiController::class, 'add_corporativo'])->name('admin.enterprise.guardar_corporativo');
        Route::post('add_cargo', [ApiController::class, 'add_cargo'])->name('admin.enterprise.guardar_cargo');
        Route::post('add_area', [ApiController::class, 'add_area'])->name('admin.enterprise.guardar_area');

        // Para Obtencion de los datos
        Route::get('edit_pais/{id}', [ApiController::class, 'edit_pais'])->name('admin.enterprise.edit_pais');
        Route::get('edit_corporativo/{id}', [ApiController::class, 'edit_corporativo'])->name('admin.enterprise.edit_corporativo');
        Route::get('edit_cargo/{id}', [ApiController::class, 'edit_cargo'])->name('admin.enterprise.edit_cargo');
        Route::get('edit_area/{id}', [ApiController::class, 'edit_area'])->name('admin.enterprise.edit_area');

        // Actualizar los datos
        Route::put('save_pais', [ApiController::class, 'update_pais'])->name('admin.enterprise.update_pais');
        Route::put('save_corporativo', [ApiController::class, 'update_corporativo'])->name('admin.enterprise.update_corporativo');
        Route::put('save_cargo', [ApiController::class, 'update_cargo'])->name('admin.enterprise.update_cargo');
        Route::put('save_area', [ApiController::class, 'update_area'])->name('admin.enterprise.update_area');

        Route::delete('delete_pais/{id}', [ApiController::class, 'delete_pais'])->name('admin.enterprise.detele_pais');
        Route::delete('delete_corporativo/{id}', [ApiController::class, 'delete_corporativo'])->name('admin.enterprise.detele_corporativo');
        Route::delete('delete_cargo/{id}', [ApiController::class, 'delete_cargo'])->name('admin.enterprise.detele_cargo');
        Route::delete('delete_area/{id}', [ApiController::class, 'delete_area'])->name('admin.enterprise.detele_area');
    });

    /**********************************************************************************************************************************************/
    // Ruta para la administracion de todas las agendas del dashboard final, espacios.
    Route::group(['prefix'=>'anfitrion/agendas'], function(){
        // Ruta para el dashboard principal de lider:
        Route::get('dashboard', [DashboardAgendasOffController::class, 'index'])->name('anfitrion.dashboard.index'); // ->listo -> vista principal de dashboard
        // RUTAS PARA LAS SOLICITUDES AJAX DE TARJETAS EN DASHBOARD:
        Route::post('contenido_cards', [DashboardAgendasOffController::class, 'contenidos_cards'])->name('dashboard.cards');

        // Ruta para obtener datos de agenda individual y subir evidencia:
        Route::get('obtener_datos/{userId}/{espacioId}/{areaId}', [DashboardAgendasOffController::class, 'ver_datos_para_agendar_evento'])->name('anfitrion.dashboard.ver_datos'); // ->listo -> otiene data para agregar evento
        Route::post('agregar_evento_individual', [DashboardAgendasOffController::class, 'guardar_agenda_individual'])->name('anfitrion.dashboard.agregar_evento'); // ->listo -> agrega evento


        // Rutas para agregar ageda de espacios de tipo: PARES | MAX 10 | RANKING | RETROALIMENTACION
        Route::get('obtener_datos_pares/{espacioId}/{areaId}', [DashboardAgendasOffController::class, 'ver_data_cafe_con_pares'])->name('anfitrion.dashboard.ver_data_pares');
        Route::get('obtener_datos_max_10/{espacioId}/{areaId}', [DashboardAgendasOffController::class, 'ver_data_agenda_max_10'])->name('anfitrion.dashboard.ver_datos_max_10');
        Route::get('obtener_datos_ranking/{espacioId}/{areaId}', [DashboardAgendasOffController::class, 'ver_data_ranking'])->name('anfitrion.dashboard.ver_data_ranking');
        Route::get('obtener_datos_retroalimentacion/{espacioId}/{areaId}', [DashboardAgendasOffController::class, 'ver_data_retroalimentacion'])->name('anfitrion.dashboard.ver_data_retroalimentacion');
        Route::post('agregar_evento_seleccion_usuarios', [DashboardAgendasOffController::class, 'guardar_agenda_seleccion_usuarios'])->name('anfitrion.dashboard.agregar_evento_seleccion_usuarios'); // Agrega evento de toda la primera seccion ;)

        // segunda seccion: COUNTRY | COMPRAS | MERCO | INDICADORES | SOSTENIBILIDAD
        Route::get('obtener_datos_country/{userIds}/{espacioId}/{areaId}', [DashboardAgendasOffController::class, 'ver_data_country'])->name('anfitrion.dashboard.ver_data_country');
        Route::get('obtener_datos_primario/{userIds}/{espacioId}/{areaId}', [DashboardAgendasOffController::class, 'ver_data_primario'])->name('anfitrion.dashboard.ver_data_primario');
        Route::get('obtener_datos_compras/{userIds}/{espacioId}/{areaId}', [DashboardAgendasOffController::class, 'ver_data_compras'])->name('anfitrion.dashboard.ver_data_compras');
        Route::get('obtener_datos_merco/{userIds}/{espacioId}/{areaId}', [DashboardAgendasOffController::class, 'ver_data_merco'])->name('anfitrion.dashboard.ver_data_merco');
        Route::get('obtener_datos_indicadores/{userIds}/{espacioId}/{areaId}', [DashboardAgendasOffController::class, 'ver_data_indicadores'])->name('anfitrion.dashboard.ver_data_indicadores');
        Route::get('obtener_datos_sostenibilidad/{userIds}/{espacioId}/{areaId}', [DashboardAgendasOffController::class, 'ver_data_sostenibilidad'])->name('anfitrion.dashboard.ver_data_sostenibilidad');
        Route::post('agregar_evento_grupal_area', [DashboardAgendasOffController::class, 'agregar_evento_grupal_por_area'])->name('evento.dashboard.agregar_evento_grupal_area');

        /* Ruta para seleccionar usuarios de un area seleccionada*/
        Route::post('obtener_usuarios_area', [DashboardAgendasOffController::class, 'buscar_usuario_por_area'])->name('agenda.espacio.buscar_usuario_area');

        // Rutas para las evidencias:
        Route::get('ver_datos_agenda_evidencia/{id}', [DashboardAgendasOffController::class, 'ver_datos_de_agenda_para_subir_evidencia']); // ->listo -> obtiene data para subir  evidencia
        Route::post('subir_evidencias', [DashboardAgendasOffController::class, 'subir_evidencia_agenda'])->name('anfitrion.dashboard.subir_evidencia'); // ->listo -> sube la evidencia de una sesión agenda ;)

        // Cerrar agendas de evidencias opcionales MAX 10 -> Big ....
        Route::get('ver_data_culminacion/{id}', [DashboardAgendasOffController::class, 'ver_agenda_para_culimar']);
        Route::post('culminar_agenda', [DashboardAgendasOffController::class, 'culminar_agendas'])->name('anfitrion.culminar_agenda');

        // Rutas para la segunda vista oficial-> control de agendas y demás:
        Route::get('general', [DashboardAgendasOffController::class, 'lista_agendas_general'])->name('anfitrion.agendas.general_vista'); // -> listo
        Route::get('todas/{id}', [DashboardAgendasOffController::class, 'filtrar_todas_agendas']);
        Route::get('agendadas/{id}', [DashboardAgendasOffController::class, 'filtrar_agendadas_agendas']);
        Route::get('atendidas/{id}', [DashboardAgendasOffController::class, 'filtrar_atendidas_agendas']);
        Route::get('concluidas/{id}', [DashboardAgendasOffController::class, 'filtrar_concluidas_agendas']);
        Route::get('pendientes_agendar/{id}', [DashboardAgendasOffController::class, 'filtrar_pendientes_por_agendar']);

        Route::get('estado_espacios', [DashboardAgendasOffController::class, 'sessiones_para_estado_espacio'])->name('vista.estado_de_espacios');

        Route::get('obtener_atendidas', [DashboardAgendasOffController::class, 'estado_agendas_atendidas'])->name('agenda.espacio.estado_atendidas');
        Route::post('obtener_pendientes', [DashboardAgendasOffController::class, 'estado_agendas_pendientes'])->name('agenda.espacio.estado_pendientes');
    });

    /**********************************************************************************************************************************************/
    // Ruta para el modelo de gestion -> INFO
    Route::get('modelo/gestion', [ModeloGestionController::class, 'index'])->name('index_modelo'); // Para modelos de gestión

});
