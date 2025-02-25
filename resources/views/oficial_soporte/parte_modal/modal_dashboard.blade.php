{{--******************************************************************************************************************************************************************--}}
    {{--MODALES PARA VER DATOS Y AGREGAR EVENTOS PARA ESPACIOS DE TIPO: PARES | MAX  10 | RANKING | RETROALIMENTACION--}}
    {{-- AGREGA EVENTO DE TIPO MAX 10 -> Una big con el equipo ---}}
    <div class="modal fade" id="agendarEventoModalGrupal" tabindex="-1" aria-labelledby="agendarEventoModalGrupalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="formAddAgendaGrupalSeleccionUsuarios" id="formAddAgendaGrupalSeleccionUsuarios">
                @csrf
                <div class="modal-content">
                    <div class="modal-header modal-header-agendar">
                        <h1 class="modal-title fs-5" id="agendarEventoModalSeleccionUsuariosLabel">Agendar en: <span id="espacio_name"></span> | <span id="area_name"></span></h1>
                        <strong>Seleccionar máximo 10 invitados</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group d-flex justify-content-center">
                            <div class="col-5">
                                <label style="display: none;">espa_id: <input type="hidden" class="form-control" name="id_espacio_grupal" id="id_espacio_grupal" class="id_espacio_max_10"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="espacio_grupal" id="espacio_grupal"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="desc_esp_grupal" id="desc_esp_grupal"></label>
                            </div>
                            <div class="col-5">
                                <label style="display: none;">Corp_id: <input type="hidden" class="form-control" name="id_corporativo_grupal" id="id_corporativo_grupal"></label>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="col">
                                <div class="row d-flex">
                                    <div class="col">
                                        <label for="" class="">Areas disponibles:</label>
                                    </div>
                                    <div class="col" id="areas_del_usuario_logueado_max_10">
                                    </div>
                                </div>
                                <div class="row">
                                    <strong><i class="fas fa-users"></i> Invitados:  </strong> <br>
                                    <div class="row" id="lista_usuarios_grupales" class="">
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div id="calendario-modal-max10" style="height: 600px; width: 420px !important;"></div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-around mt-3 mb-1 form-group">
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
                        <div class="col d-flex justify-content-center">
                            <span class="error_fechaHora_seleccion_usuarios text-danger"></span>
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
    {{-- AGREGA EVENTO DE TIPO PARES -> Café con Pares ---}}
    <div class="modal fade" id="agendarEventoModalPares" tabindex="-1" aria-labelledby="agendarEventoModalParesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="formAddAgendaPares" id="formAddAgendaPares">
                @csrf
                <div class="modal-content">ss
                    <div class="modal-header modal-header-agendar">
                        <h1 class="modal-title fs-5" id="agendarEventoModalParesLabel">Agendar evento para: <span id="espacio_pares"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group d-flex justify-content-center">
                            <div class="col-5">
                                <label style="display: none;">espa_id: <input type="hidden" class="form-control" name="id_espacio_grupal" id="id_espacio_pares"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="espacio_grupal" id="espacio_pares_name"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="desc_esp_grupal" id="desc_esp_pares"></label>
                            </div>
                            <div class="col-5">
                                <label style="display: none;">Corp_id: <input type="hidden" class="form-control" name="id_corporativo_grupal" id="id_corporativo_pares"></label>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="col">
                                <div class="row d-flex">
                                    <div class="col">
                                        <label for="" class="w-100">Areas disponibles:</label>
                                    </div>
                                    <div class="col-6" id="areas_del_usuario_logueado_pares"></div>
                                </div>
                                <div class="row">
                                    <strong><i class="fas fa-users"></i> Invitados:  </strong> <br>
                                    <div class="row d-flex flex-wrap" id="lista_usuarios_pares"></div>
                                </div>
                            </div>

                            <div class="col">
                                <div id="calendario-modal-pares" style="height: 600px; width: 420px !important;"></div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-around mt-3 mb-1 form-group">
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
                        <div class="col d-flex justify-content-center">
                            <span class="error_fechaHora_seleccion_usuarios text-danger"></span>
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
    {{-- AGREGA EVENTO DE TIPO RANKING -> Ranking ---}}
    <div class="modal fade" id="agendarEventoModalRanking" tabindex="-1" aria-labelledby="agendarEventoModalRankingLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="formAddAgendaRanking" id="formAddAgendaRanking">
                @csrf
                <div class="modal-content">
                    <div class="modal-header modal-header-agendar">
                        <h1 class="modal-title fs-5" id="agendarEventoModalRankingLabel">Agendar evento para: <span id="espacio_ranking"></span> | <span id="area_ranking"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group d-flex justify-content-center">
                            <div class="col-5">
                                <label style="display: none;">espa_id: <input type="hidden" class="form-control" name="id_espacio_grupal" id="id_espacio_ranking"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="espacio_grupal" id="espacio_ranking_name"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="desc_esp_grupal" id="desc_esp_ranking"></label>
                            </div>
                            <div class="col-5">
                                <label style="display: none;">Corp_id: <input type="hidden" class="form-control" name="id_corporativo_grupal" id="id_corporativo_ranking"></label>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="col">
                                <div class="row d-flex">
                                    <div class="col">
                                        <label for="" class="">Areas disponibles:</label>
                                    </div>
                                    <div class="col-6" id="areas_del_usuario_logueado_ranking"></div>
                                </div>
                                <div class="row">
                                    <strong><i class="fas fa-users"></i> Invitados:  </strong> <br>
                                    <div class="row d-flex flex-wrap" id="lista_usuarios_ranking"></div>
                                </div>
                            </div>

                            <div class="col">
                                <div id="calendario-modal-ranking" style="height: 600px; width: 420px !important;"></div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-around mt-3 mb-1 form-group">
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
                        <div class="d-flex justify-content-center">
                            <span class="error_fechaHora_seleccion_usuarios text-danger"></span>
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
    {{-- AGREGA EVENTO DE TIPO RETROALIMENTACION -> Retroalimentacion y Acompañamiento ---}}
    <div class="modal fade" id="agendarEventoModalRetroalimentacion" tabindex="-1" aria-labelledby="agendarEventoModalRetroalimentacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="formAddAgendaRetroalimentacion" id="formAddAgendaRetroalimentacion">
                @csrf
                <div class="modal-content">
                    <div class="modal-header modal-header-agendar">
                        <h1 class="modal-title fs-5" id="agendarEventoModalRetroalimentacionLabel">Agendar evento para Retroalimentacion: <span id="espacio_retroalimentacion"></span> | <span id="area_retroalimentacion"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group d-flex justify-content-center">
                            <div class="col-5">
                                <label style="display: none;">espa_id: <input type="hidden" class="form-control" name="id_espacio_grupal" id="id_espacio_retroalimentacion"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="espacio_grupal" id="espacio_retroalimentacion_name"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="desc_esp_grupal" id="desc_esp_retroalimentacion"></label>
                            </div>
                            <div class="col-5">
                                <label style="display: none;">Corp_id: <input type="hidden" class="form-control" name="id_corporativo_grupal" id="id_corporativo_retroalimentacion"></label>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="col">
                                <div class="row d-flex">
                                    <div class="col">
                                        <label for="" class="">Areas disponibles:</label>
                                    </div>
                                    <div class="col-6" id="areas_del_usuario_logueado_retroalimentacion"></div>
                                </div>
                                <div class="row">
                                    <strong><i class="fas fa-users"></i> Invitados:  </strong> <br>
                                    <div class="row d-flex flex-wrap" id="lista_usuarios_retroalimentacion"></div>
                                </div>
                            </div>

                            <div class="col">
                                <div id="calendario-modal-retroalimentacion" style="height: 600px; width: 420px !important;"></div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-around mt-3 mb-1 form-group">
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
                        <div class="col d-flex justify-content-center">
                            <span class="error_fechaHora_seleccion_usuarios text-danger"></span>
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

    {{--*************************** Modal para ver datos y agregar eventos para espacios y para todo el area ***************************--}}
    {{-- TIPO COUNTRY -> Haciendo posible lo imposible ---}}
    <div class="modal fade" id="agendarEventoModalCountry" tabindex="-1" aria-labelledby="agendarEventoModalCountryLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="formAddAgendaCountry" id="formAddAgendaCountry">
                @csrf
                <div class="modal-content">
                    <div class="modal-header modal-header-agendar">
                        <h1 class="modal-title fs-5" id="agendarEventoModalCountryLabel">Agendar evento para Country: <span id="espacio_country"></span> | <span id="area_country"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group d-flex justify-content-center">
                            <div class="col-5">
                                <label style="display: none;">espa_id: <input type="hidden" class="form-control" name="id_espacio_grupal" id="id_espacio_country"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="espacio_grupal" id="espacio_country_name"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="desc_esp_grupal" id="desc_esp_country"></label>
                            </div>
                            <div class="col-5">
                                <label style="display: none;">Corp_id: <input type="hidden" class="form-control" name="id_corporativo_grupal" id="id_corporativo_country"></label>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="col">
                                <div class="row d-flex">
                                    <div class="col">
                                        <label for="" class="">Areas disponibles:</label>
                                    </div>
                                    <div class="col-6" id="areas_del_usuario_logueado_country"></div>
                                </div>
                                <div class="row">
                                    <strong><i class="fas fa-users"></i> Invitados:  </strong> <br>
                                    <div class="row d-flex flex-wrap" id="lista_usuarios_country"></div>
                                </div>
                            </div>

                            <div class="col">
                                <div id="calendario-modal-country" style="height: 600px; width: 420px !important;"></div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-around mt-3 mb-1 form-group">
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
                        <div class="d-flex justify-content-center">
                            <span class="area_agenda_error text-danger"></span>
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
    {{-- TIPO PRIMARIO -> Grupo Primario ---}}
    <div class="modal fade" id="agendarEventoModalPrimario" tabindex="-1" aria-labelledby="agendarEventoModalPrimarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="formAddAgendaPrimario" id="formAddAgendaPrimario">
                @csrf
                <div class="modal-content">
                    <div class="modal-header modal-header-agendar">
                        <h1 class="modal-title fs-5" id="agendarEventoModalPrimarioLabel">Agendar evento para Primario: <span id="espacio_primario"></span> | <span id="area_primario"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group d-flex justify-content-center">
                            <div class="col-5">
                                <label style="display: none;">espa_id: <input type="hidden" class="form-control" name="id_espacio_grupal" id="id_espacio_primario"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="espacio_grupal" id="espacio_primario_name"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="desc_esp_grupal" id="desc_esp_primario"></label>
                            </div>
                            <div class="col-5">
                                <label style="display: none;">Corp_id: <input type="hidden" class="form-control" name="id_corporativo_grupal" id="id_corporativo_primario"></label>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="col">
                                <div class="row d-flex">
                                    <div class="col">
                                        <label for="" class="">Areas disponibles:</label>
                                    </div>
                                    <div class="col-6" id="areas_del_usuario_logueado_primario"></div>
                                </div>
                                <div class="row">
                                    <strong><i class="fas fa-users"></i> Invitados:  </strong> <br>
                                    <div class="row d-flex flex-wrap" id="lista_usuarios_primario"></div>
                                </div>
                            </div>

                            <div class="col">
                                <div id="calendario-modal-primario" style="height: 600px; width: 420px !important;"></div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-around mt-3 mb-1 form-group">
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
                        <div class="col d-flex justify-content-center">
                            <span class="area_agenda_error text-danger"></span>
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
    {{-- TIPO COMPRAS -> Comité de Compras ---}}
    <div class="modal fade" id="agendarEventoModalCompras" tabindex="-1" aria-labelledby="agendarEventoModalComprasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="formAddAgendaCompras" id="formAddAgendaCompras">
                @csrf
                <div class="modal-content">
                    <div class="modal-header modal-header-agendar">
                        <h1 class="modal-title fs-5" id="agendarEventoModalComprasLabel">Agendar evento para Compras: <span id="espacio_compras"></span> | <span id="area_compras"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group d-flex justify-content-center">
                            <div class="col-5">
                                <label style="display: none;">espa_id: <input type="hidden" class="form-control" name="id_espacio_grupal" id="id_espacio_compras"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="espacio_grupal" id="espacio_compras_name"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="desc_esp_grupal" id="desc_esp_compras"></label>
                            </div>
                            <div class="col-5">
                                <label style="display: none;">Corp_id: <input type="hidden" class="form-control" name="id_corporativo_grupal" id="id_corporativo_compras"></label>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="col">
                                <div class="row d-flex">
                                    <div class="col">
                                        <label for="" class="">Areas disponibles:</label>
                                    </div>
                                    <div class="col-6" id="areas_del_usuario_logueado_compras"> </div>
                                </div>
                                <div class="row">
                                    <strong><i class="fas fa-users"></i> Invitados:  </strong> <br>
                                    <div class="row d-flex flex-wrap" id="lista_usuarios_compras"> </div>
                                </div>
                            </div>

                            <div class="col">
                                <div id="calendario-modal-compras" style="height: 600px; width: 420px !important;"></div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-around mt-3 mb-1 form-group">
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
                        <div class="col d-flex justify-content-center">
                            <span class="area_agenda_error text-danger"></span>
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
    {{-- TIPO MERCO-> Merco ---}}
    <div class="modal fade" id="agendarEventoModalMerco" tabindex="-1" aria-labelledby="agendarEventoModalMercoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="formAddAgendaMerco" id="formAddAgendaMerco">
                @csrf
                <div class="modal-content">
                    <div class="modal-header modal-header-agendar">
                        <h1 class="modal-title fs-5" id="agendarEventoModalMercoLabel">Agendar evento para Merco: <span id="espacio_merco"></span> | <span id="area_merco"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group d-flex justify-content-center">
                            <div class="col-5">
                                <label style="display: none;">espa_id: <input type="hidden" class="form-control" name="id_espacio_grupal" id="id_espacio_merco"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="espacio_grupal" id="espacio_merco_name"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="desc_esp_grupal" id="desc_esp_merco"></label>
                            </div>
                            <div class="col-5">
                                <label style="display: none;">Corp_id: <input type="hidden" class="form-control" name="id_corporativo_grupal" id="id_corporativo_merco"></label>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="col">
                                <div class="row d-flex">
                                    <div class="col">
                                        <label for="" class="">Areas disponibles:</label>
                                    </div>
                                    <div class="col-6" id="areas_del_usuario_logueado_merco"> </div>
                                </div>
                                <div class="row">
                                    <strong><i class="fas fa-users"></i> Invitados:  </strong> <br>
                                    <div class="row d-flex flex-wrap" id="lista_usuarios_merco"> </div>
                                </div>
                            </div>

                            <div class="col">
                                <div id="calendario-modal-merco" style="height: 600px; width: 420px !important;"></div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-around mt-3 mb-1 form-group">
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
                        <div class="col d-flex justify-content-center">
                            <span class="area_agenda_error text-danger"></span>
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
    {{-- TIPO INDICADORES-> Día de Indicadores ---}}
    <div class="modal fade" id="agendarEventoModalIndicadores" tabindex="-1" aria-labelledby="agendarEventoModalIndicadoresLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="formAddAgendaIndicadores" id="formAddAgendaIndicadores">
                @csrf
                <div class="modal-content">
                    <div class="modal-header modal-header-agendar">
                        <h1 class="modal-title fs-5" id="agendarEventoModalIndicadoresLabel">Agendar evento para Indicadores: <span id="espacio_indicadores"></span> | <span id="area_indicadores"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group d-flex justify-content-center">
                            <div class="col-5">
                                <label style="display: none;">espa_id: <input type="hidden" class="form-control" name="id_espacio_grupal" id="id_espacio_indicadores"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="espacio_grupal" id="espacio_indicadores_name"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="desc_esp_grupal" id="desc_esp_indicadores"></label>
                            </div>
                            <div class="col-5">
                                <label style="display: none;">Corp_id: <input type="hidden" class="form-control" name="id_corporativo_grupal" id="id_corporativo_indicadores"></label>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="col">
                                <div class="row d-flex">
                                    <div class="col">
                                        <label for="" class="w-100">Areas disponibles:</label>
                                    </div>
                                    <div class="col-6" id="areas_del_usuario_logueado_indicadores"></div>
                                </div>
                                <div class="row">
                                    <strong><i class="fas fa-users"></i> Invitados:  </strong> <br>
                                    <div class="row d-flex flex-wrap" id="lista_usuarios_indicadores"></div>
                                </div>
                            </div>

                            <div class="col">
                                <div id="calendario-modal-indicadores" style="height: 600px; width: 420px !important;"></div>
                            </div>
                        </div>

                        <div class="row d-flex justify-content-around mt-3 mb-1 form-group">
                            <div class="col-6">
                                <select name="location" id="location" class="select-two rounded col-12 evento-time">
                                    <option value="">Tipo de evento</option>
                                    <option value="Presencial - Oficina principal">Presencial</option>
                                    <option value="Virtual - Meet">Virtual</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <input type="datetime-local" class="rounded fecha-hora-pasada time-modal" name="fecha_hora_meet" id="fecha_hora_meet">
                            </div>
                        </div>
                        <div class="col d-flex justify-content-center">
                            <span class="area_agenda_error text-danger"></span>
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
    {{-- TIPO SOSTENIBLIDAD-> Comité de sostenibilidad ---}}
    <div class="modal fade" id="agendarEventoModalSostenibilidad" tabindex="-1" aria-labelledby="agendarEventoModalSostenibilidadLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="formAddAgendaSostenibilidad" id="formAddAgendaSostenibilidad">
                @csrf
                <div class="modal-content">
                    <div class="modal-header modal-header-agendar">
                        <h1 class="modal-title fs-5" id="agendarEventoModalSostenibilidadLabel">Agendar evento para Sostenibilidad: <span id="espacio_sostenibilidad"></span> | <span id="area_sostenibilidad"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group d-flex justify-content-center">
                            <div class="col-5">
                                <label style="display: none;">espa_id: <input type="hidden" class="form-control" name="id_espacio_grupal" id="id_espacio_sostenibilidad"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="espacio_grupal" id="espacio_sostenibilidad_name"></label>
                                <label style="display: none;">espa_name: <input type="hidden" class="form-control" name="desc_esp_grupal" id="desc_esp_sostenibilidad"></label>
                            </div>
                            <div class="col-5">
                                <label style="display: none;">Corp_id: <input type="hidden" class="form-control" name="id_corporativo_grupal" id="id_corporativo_sostenibilidad"></label>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between">
                            <div class="col">
                                <div class="row d-flex">
                                    <div class="col">
                                        <label for="" class="w-100">Areas disponibles:</label>
                                    </div>
                                    <div class="col-6" id="areas_del_usuario_logueado_sostenibilidad">
                                    </div>
                                </div>
                                <div class="row">
                                    <strong><i class="fas fa-users"></i> Invitados:  </strong> <br>
                                    <div class="row d-flex flex-wrap" id="lista_usuarios_sostenibilidad">
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div id="calendario-modal-sostenibilidad" style="height: 600px; width: 420px !important;"></div>
                            </div>
                        </div>

                        <div class=" row d-flex justify-content-around mt-3 mb-1 form-group">
                            <div class="col-6">
                                <select name="location" id="location" class="select-two rounded col-12 evento-time">
                                    <option value="">Tipo de evento</option>
                                    <option value="Presencial - Oficina principal">Presencial</option>
                                    <option value="Virtual - Meet">Virtual</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <input type="datetime-local" class="rounded fecha-hora-pasada time-modal" name="fecha_hora_meet" id="fecha_hora_meet">
                            </div>
                        </div>
                        <div class="col d-flex justify-content-center">
                            <span class="area_agenda_error text-danger"></span>
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