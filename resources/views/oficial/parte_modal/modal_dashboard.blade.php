{{-- Inicio del contenido para agregar eventos individuales OTO--}}
<div class="modal fade modal-right" id="agendarEventoModal" tabindex="-1" aria-labelledby="agendarEventoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-contenido-agendar modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
        <form class="saveAgendaIndividual" id="formAddAgendaIndividual">
            @csrf
            <div class="modal-content modal-content-agendar justify-content-center">
                <div class="modal-header bg-secondary modal-header-agendar">
                    <h1 class="modal-title fs-5" id="agendarEventoModalLabel">Agendar evento para el usuario: <span id="usuario_id"></span> | <span id="usuario_name"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-content-agendar">
                    <div class="form-group d-flex justify-content-center">
                        <div class="col-5">
                            <input type="hidden" class="form-control" name="id_user" id="idUserLog">
                            <input type="hidden" class="form-control" name="user_name" id="user_name">
                            <input type="hidden" name="user_email" id="user_email" class="form-control">
                            <input type="hidden" class="form-control" name="id_espacio" id="id_espacio">
                            <input type="hidden" class="form-control" name="espacio" id="espacio">
                        </div>
                        <div class="col-5">
                            <input type="hidden" class="form-control" name="descripcion_espacio" id="descripcion_espacio">
                            <input type="hidden" class="form-control" name="id_area" id="id_area">
                            <input type="hidden" class="form-control" name="area_name" id="area_name">
                            <input type="hidden" class="form-control" name="id_corporativo" id="id_corporativo">
                        </div>
                    </div>
                    <div class="">
                        <div id="calendario-modal-google" style="height: 900px;"></div>
                    </div>
                    <div class="d-flex justify-content-around mt-3 mb-1 form-group">
                        <div class="col-6">
                            <select name="location" id="location" class="select-two rounded col-12 evento-time">
                                <option value="">Tipo de evento</option>
                                <option value="Presencial - Oficina principal">Presencial</option>
                                <option value="Virtual - Meet">Virtual</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <input type="datetime-local" class="fecha-hora-pasada rounded time-modal" name="fecha_hora_meet" id="fecha_hora_meet">
                        </div>
                    </div>
                    <div>
                        <span class="error_fecha_hora_meet text-danger"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-outline-success btn-agendar-evento">Agendar</button>
                </div>
            </div>
        </form>
    </div>
</div>
{{--******************************************************************************************************************************************************************--}}
{{-- Modal para ver el proceso de carga de la creacion de los eventos --}}
<div class="modal fade modal-progreso" id="modalProgreso" tabindex="-1" role="dialog" aria-labelledby="modalProgresoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProgresoLabel">Generando agenda...</h5>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div id="barraProgreso" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div id="porcentajeProgreso" class="mt-2 text-center">0%</div>
            </div>
        </div>
    </div>
</div>
{{-- Modal para ver los datos de la agenda y subir evidencia --}}
<div class="modal fade modal-right" id="agendaEvidenciaModal" tabindex="-1" aria-labelledby="agendaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-contenido modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
        <form method="post" class="sendEvidenciaAgenda" id="sendEvidenciaAgenda" enctype="multipart/form-data">
            @csrf
            <div class="modal-content modal-content-evidencia">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="agendaModalLabel">Detalle de la agenda con ID: <span id="id_agenda_input" class="ml-2"></span> - <span id="fechaHoraMeet_agenda" class="ml-2"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_agenda" id="id_agenda">
                    <div class="row d-flex w-100 justify-content-center">
                        <label>Ingresa el nombre para los archivos</label>
                        <input type="text" class="form-control" name="nombre_archivo" id="nombre_archivo">
                    </div>
                    <div class="row d-flex w-100">
                        <label>Agregar evidencias (archivos)</label>
                        <div class="d-flex">
                            <input type="file" name="agenda_evidencia_file[]" id="agenda_evidencia_file" class="form-control input-file-sub-soporte" required>
                        </div>

                        <div id="fileInputsContainer">
                        </div>
                        <a type="button" id="addFileInput" class="add_files ml-2 mt-2">Agregar más......</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-success button-evidencia">Subir evidencia <i class="fas fa-file ml-2" style="font-size: 20px; font-weight: bold;"></i> </button>
                    <button type="button" class="btn btn-outline-light btn-modal-cancel button-evidencia" data-bs-dismiss="modal">Cerrar <i class="fas fa-times ml-2" style="font-size: 20px; font-weight: bold;" ></i></button>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- Modal para ver los datos de la agenda para culminar --}}
<div class="modal fade modal-right" id="cerrarAgendaModal" tabindex="-1" aria-labelledby="agendaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-contenido modal-dialog-slideout-right modal-dialog-vertical-centered" role="document">
        <form method="post" class="culminarAgendaForm" id="culminarAgendaForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-content modal-content-evidencia">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="agendaModalLabel">Detalle de la agenda con ID: <span id="id_cerrar_agenda" class="ml-2"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_agenda" id="id_agenda_culminar">
                    <div class="d-flex justify-content-center">
                        <div id="icon-info-culminar-agenda"></div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <p>¿Está segur@ de culminar la agenda?</p>
                    </div>
                    <div class="d-flex justify-content-center align-content-center align-items-center">
                        <div id="fecha_Hora_meet_culminar"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-success button-evidencia">Culminar agenda</button>
                    <button type="button" class="btn btn-outline-light btn-modal-cancel button-evidencia" data-bs-dismiss="modal">Cerrar <i class="fas fa-times ml-2" style="font-size: 20px; font-weight: bold;" ></i></button>
                </div>
            </div>
        </form>
    </div>
</div>
