<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='Laboratorios/guardar' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Laboratorios</legend>

        <div data-linea="1">
            <label for="fk_id_laboratorio"> Direcci&oacute;n </label> 
            <select
                id="fk_id_laboratorio" name="fk_id_laboratorio" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDirecciones($this->modeloLaboratorios->getFkIdLaboratorio());
                ?>
            </select>
        </div>
        <div data-linea="2">
            <label for="id_sistema_guia">Sistema GUIA </label> <select
                id="id_sistema_guia" name="id_sistema_guia" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboLaboratoriosGUIA($this->modeloLaboratorios->getIdSistemaGuia());
                ?>
            </select>
        </div>
        <div data-linea="3">
            <label for="nombre"> Nombre </label> 
            <input type="text" id="nombre" name="nombre"
                   value="<?php echo $this->modeloLaboratorios->getNombre(); ?>"
                   placeholder="Nombre del laboratorio" required maxlength="512" />
        </div>

        <label for="nombre"> Descripci&oacute;n </label>
        <div data-linea="4">
            <textarea name="descripcion"
                      placeholder="Ingrese una descripci&oacute;n"><?php echo $this->modeloLaboratorios->getDescripcion(); ?></textarea>
        </div>

        <div data-linea="5">
            <label for="codigo"> C&oacute;digo </label> 
            <input type="text" id="codigo" name="codigo"
                   value="<?php echo $this->modeloLaboratorios->getCodigo(); ?>"
                   placeholder="" required maxlength="16" />
        </div>

        <div data-linea="5">
            <label for="orden"> C&oacute;digo Especial </label> 
            <input type="text" id="codigo_especial" name="codigo_especial"
                   value="<?php echo $this->modeloLaboratorios->getCodigoEspecial(); ?>"
                   placeholder="C&oacute;digo Especial" maxlength="128"/>
        </div>

        <div data-linea="6">
            <label for="orientacion">Orientaci&oacute;n</label> 
            <select id="orientacion" name="orientacion" required>
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboDespliegue($this->modeloLaboratorios->getOrientacion());
                ?>
            </select>
        </div>
        
        <div data-linea="6">
            <label for="orden"> Orden </label> 
            <input type="number" id="orden" name="orden"
                   value="<?php echo $this->modeloLaboratorios->getOrden(); ?>"
                   placeholder="" required maxlength="3" />
        </div>
        
        <div data-linea="7">
            <label>Estado</label> 
            <select id="estado_registro" name="estado_registro" >
                <?php echo $this->combo2Estados($this->modeloLaboratorios->getEstadoRegistro()); ?>
            </select>
        </div>
        
        <div data-linea="7"></div>

        <div data-linea="8">
            <input type="hidden" name="id_laboratorio" id="id_laboratorio"
                   value="<?php echo $this->modeloLaboratorios->getIdLaboratorio() ?>" />
            <input type="hidden" name="nivel" id="nivel" value="1" />
            <input type="hidden" name="tipo_campo" id="tipo_campo" value="LABORATORIO" />
            <button type="submit" class="guardar"> Guardar</button>
        </div>
        <a href="#" style="float: left" onclick="fn_conf_general()"  >Configurar General</a>
        <a href="#" style="float: right" onclick="fn_orden_trabajo()"  >Configurar orden de trabajo</a>

    </fieldset>
</form>
<?php $contador = 0;
$contador2 = 0;
?>
<!-- Modal para desplegar los campos de configuración general -->
<div class="modal fade" id="modalConfGeneral" role="dialog">
    <div class="modal-dialog">
        <div class="center-block" class="modal-diaoog" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Configuraci&oacute;n General</h4>
                </div>
                <div class="form-horizontal">
                    <div class="modal-body">
                        <form id='frmConfGeneral'>

                            <span class="clearfix"></span>

                            <table id="tblConfiguracionGeneral">
                                <caption>
                                    Seleccionar los campos que requiere que aparezca en la solicitud del laboratorio:  <?php echo $this->modeloLaboratorios->getNombre(); ?>
                                </caption>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Visible</th>
                                        <th>Requerido</th>
                                        <th>Nota</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="cantidadIngredienteActivo"/>N&uacute;mero de ingredientes activos</td>
                                        <td><input type="checkbox" id="chkViscantidadIngredienteActivo" onclick="fn_uncheckRequerido('cantidadIngredienteActivo')"/></td>
                                        <td><input type="checkbox" id="chkReqcantidadIngredienteActivo" onclick="fn_checkRequerido('cantidadIngredienteActivo')"/></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="tipo_solicitud"/>Registro/Post-registro</td>
                                        <td><input type="checkbox" id="chkVistipo_solicitud" onclick="fn_uncheckRequerido('tipo_solicitud')"/></td>
                                        <td><input type="checkbox" id="chkReqtipo_solicitud" onclick="fn_checkRequerido('tipo_solicitud')"/></td>
                                        <td>Si es visible entonces debe ser requerido</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="cronogramaPostregistro"/>Cronograma Post-registro</td>
                                        <td><input type="checkbox" id="chkViscronogramaPostregistro" onclick="fn_uncheckRequerido('cronogramaPostregistro')"/></td>
                                        <td><input type="checkbox" id="chkReqcronogramaPostregistro" onclick="fn_checkRequerido('cronogramaPostregistro')"/></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="provOrigenMuestra"/>Provincia origen muestra</td>
                                        <td><input type="checkbox" id="chkVisprovOrigenMuestra" onclick="fn_uncheckRequerido('provOrigenMuestra')"/></td>
                                        <td><input type="checkbox" id="chkReqprovOrigenMuestra" onclick="fn_checkRequerido('provOrigenMuestra')"/></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="muestreo_nacional"/>Muestreo nacional</td>
                                        <td><input type="checkbox" id="chkVismuestreo_nacional" onclick="fn_uncheckRequerido('muestreo_nacional')"/></td>
                                        <td><input type="checkbox" id="chkReqmuestreo_nacional" onclick="fn_checkRequerido('muestreo_nacional')"/></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="propietarioUsuario"/>¿El propietario de la muestra es el mismo del sistema GUIA?</td>
                                        <td><input type="checkbox" id="chkVispropietarioUsuario" onclick="fn_uncheckRequerido('propietarioUsuario')"/></td>
                                        <td><input type="checkbox" id="chkReqpropietarioUsuario" onclick="fn_checkRequerido('propietarioUsuario')"/></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="fecha_toma"/>Fecha de toma</td>
                                        <td><input type="checkbox" id="chkVisfecha_toma" onclick="fn_uncheckRequerido('fecha_toma')"/></td>
                                        <td><input type="checkbox" id="chkReqfecha_toma" onclick="fn_checkRequerido('fecha_toma')"/></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="responsable_toma"/>Responsable de la toma</td>
                                        <td><input type="checkbox" id="chkVisresponsable_toma" onclick="fn_uncheckRequerido('responsable_toma')"/></td>
                                        <td><input type="checkbox" id="chkReqresponsable_toma" onclick="fn_checkRequerido('responsable_toma')"/></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="id_localizacion"/>Provincia</td>
                                        <td><input type="checkbox" id="chkVisid_localizacion" onclick="fn_uncheckRequerido('id_localizacion')"/></td>
                                        <td><input type="checkbox" id="chkReqid_localizacion" onclick="fn_checkRequerido('id_localizacion')"/></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="fk_id_localizacion"/>Cant&oacute;n</td>
                                        <td><input type="checkbox" id="chkVisfk_id_localizacion" onclick="fn_uncheckRequerido('fk_id_localizacion')"/></td>
                                        <td><input type="checkbox" id="chkReqfk_id_localizacion" onclick="fn_checkRequerido('fk_id_localizacion')"/></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="fk_id_localizacion2"/>Parroquia</td>
                                        <td><input type="checkbox" id="chkVisfk_id_localizacion2" onclick="fn_uncheckRequerido('fk_id_localizacion2')"/></td>
                                        <td><input type="checkbox" id="chkReqfk_id_localizacion2" onclick="fn_checkRequerido('fk_id_localizacion2')"/></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="referencia_ubicacion"/>Referencia</td>
                                        <td><input type="checkbox" id="chkVisreferencia_ubicacion" onclick="fn_uncheckRequerido('referencia_ubicacion')"/></td>
                                        <td><input type="checkbox" id="chkReqreferencia_ubicacion" onclick="fn_checkRequerido('referencia_ubicacion')"/></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="Coordenadas"/>Coordenadas(longitud, latitud, altura)</td>
                                        <td><input type="checkbox" id="chkVisCoordenadas" onclick="fn_uncheckRequerido('Coordenadas')"/></td>
                                        <td><input type="checkbox" id="chkReqCoordenadas" onclick="fn_checkRequerido('Coordenadas')"/></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="4">Otros permisos para el laboratorio</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador2; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="derivacion"/>Derivaci&oacute;n de muestras</td>
                                        <td><input type="checkbox" id="chkVisderivacion" onclick="fn_uncheckRequerido('derivacion')"/></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador2; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="confirmacion"/>Confirmaci&oacute;n de an&aacute;lisis</td>
                                        <td><input type="checkbox" id="chkVisconfirmacion" onclick="fn_uncheckRequerido('confirmacion')"/></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador2; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="idoneaEnProceso"/>Permitir declarar idonea una muestra durante el proceso de an&aacute;lisis</td>
                                        <td><input type="checkbox" id="chkVisidoneaEnProceso" onclick="fn_uncheckRequerido('idoneaEnProceso')"/></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador2; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="acreditacion"/>Acreditaci&oacute;n</td>
                                        <td><input type="checkbox" id="chkVisacreditacion" onclick="fn_uncheckRequerido('acreditacion')"/></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo ++$contador2; ?></td>
                                        <td style="text-align: left"><input type="hidden" value="" id="temperatura"/>Temperatura recepción muestra/s en el laboratorio °C:</td>
                                        <td><input type="checkbox" id="chkVistemperatura" onclick="fn_uncheckRequerido('temperatura')"/></td>
                                        <td><input type="checkbox" id="chkReqtemperatura" onclick="fn_checkRequerido('temperatura')"/></td>
                                        <td>Campo que se muestra en la bandeja de RT en Verificar muestras</td>
                                    </tr>
                                </tbody>
                            </table>

                            <?php
                            if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev')
                            {
                                echo '<label for="orden">Código ejecutable (Visible en desarrollo)</label>
                                    <div data-linea="8">
             <textarea id="atributos" name="atributos" 
                      placeholder="Se puede agregar atributos en formato json " >' . $this->modeloLaboratorios->getAtributos() . '</textarea>
        </div >';
                            } else
                            {
                                echo ' <input type="hidden" id="atributos" name="atributos" value="' . $this->modeloLaboratorios->getAtributos() . '"/>';
                            }
                            ?>

                            <input type="hidden" id="id_laboratorio" name="id_laboratorio" value="<?php echo $this->modeloLaboratorios->getIdLaboratorio() ?>"/>
                            <button type="button" id="bntGuardarConfGeneral"  class="guardar"> Guardar</button>

                        </form>
                    </div>
                </div>
                <div class="modal-footer" align="center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal para desplegar los campos dinámicos del resultados -->
<div class="modal fade" id="modalOrdenTrabajo" role="dialog">
    <div class="modal-dialog">
        <div class="center-block" class="modal-dialog" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Configuración general de la Orden de Trabajo</h4>
                </div>
                <div class="form-horizontal">
                    <div class="modal-body">
                        <form id='frmOrdenTrabajo'>

                            <span class="clearfix"></span>
                            <table id="tblOT" > 
                                <caption>
                                    Seleccionar y llene los siguientes campos que requiere que aparezca en la orden de trabajo del laboratorio:  <?php echo $this->modeloLaboratorios->getNombre(); ?>
                                </caption>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Campo</th>
                                        <th>Visible</th>
                                        <th>Contenido</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td style="text-align: left;">Título de la OT</td>
                                        <td><input type="checkbox" id="chknombreReporte" /></td>
                                        <td><input type="text" id="nombreReporte" name="nombreReporte"  value="" placeholder="Ej. ORDEN DE TRABAJO" /></td>

                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td style="text-align: left;">Código de Orden de Trabajo</td>
                                        <td><input type="checkbox" id="chkcodOrdenTrabajo" /></td>
                                        <td><input type="text" id="codOrdenTrabajo" name="codOrdenTrabajo"  value="" placeholder="Ej. PGC/LA/03-FO08" /></td>

                                    </tr>

                                    <tr>
                                        <td>3</td>
                                        <td style="text-align: left;">Revisión</td>
                                        <td><input type="checkbox" id="chkrevisionOT" /></td>
                                        <td><input type="text"  id="revisionOT" name="revisionOT" value="" placeholder="Ej. Rev. 5" /></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td style="text-align: left;">Ver fecha de recepción</td>
                                        <td><input type="checkbox" id="chkfechaActivacion" /></td>
                                        <td><input type="text"  id="fechaActivacion" name="fechaActivacion"  value="" placeholder="Ej. Fecha de recepción"/></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td style="text-align: left;">Ver No. Factura / Memorando</td>
                                        <td><input type="checkbox" id="chknumeroDocumento" /></td>
                                        <td><input type="text" id="numeroDocumento" name="numeroDocumento"  value="" placeholder="Ej. No. de factura"/></td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td style="text-align: left;">Ver costo</td>
                                        <td><input type="checkbox" id="chkvalorDepositado" /></td>
                                        <td><input type="text"  id="valorDepositado" name="valorDepositado" value="" placeholder="Ej. Por US. $" /></td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td style="text-align: left;">Mensaje pie de página</td>
                                        <td><input type="checkbox" id="chkpiePagina" /></td>
                                        <td><input type="text"  id="piePagina" name="piePagina" value="" placeholder="Ej. Los resultados de los análisis solicitados podrán se usados por AGROCAALIDAD, en caso de que ser ponga en riesgo el estatus fitosanitario, zoosanitario o de inocuidad de los alimentos."/></td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td style="text-align: left;">Firma de cliente</td>
                                        <td><input type="checkbox" id="chkfirmaCliente" /></td>
                                        <td><input type="text"  id="firmaCliente" name="firmaCliente" value="" placeholder="Ej. CLIENTE" /></td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td style="text-align: left">Firma de Recepción</td>
                                        <td><input type="checkbox" id="chkfirmaRecepcion" /></td>
                                        <td><input type="text"  id="firmaRecepcion" name="firmaRecepcion" value="" placeholder="Ej. RECEPCIÓN" /></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;">10</td>
                                        <td>Firma de Responsable Técnico</td>
                                        <td><input type="checkbox" id="chkfirmaRT" /></td>
                                        <td><input type="text"  id="firmaRT" name="firmaRT" value="" placeholder="Ej. RESPONSABLE TÉCNICO" /></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;">11</td>
                                        <td>Código de campo de la muestra</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;"></td>
                                        <td style="text-align: left;">- Aplica para usuario Interno</td>
                                        <td><input type="checkbox" id="chkm_cod_campo" /></td>
                                        <td><input type="text"  id="m_cod_campo" name="m_cod_campo" value="" placeholder="Ej. Código de campo de la muestra" /></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;"></td>
                                        <td style="text-align: left;">- Aplica para usuario Externo</td>
                                        <td><input type="checkbox" id="chkm_cod_campo_e" /></td>
                                        <td><input type="text"  id="m_cod_campo_e" name="m_cod_campo_e" value="" placeholder="Ej. Código de campo de la muestra" /></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;">12</td>
                                        <td>Código de muestra laboratorio</td>
                                        <td><input type="checkbox" id="chkm_cod_lab" /></td>
                                        <td><input type="text"  id="m_cod_lab" name="m_cod_campo" value="" placeholder="Ej. Código de muestra laboratorio" /></td>
                                    </tr>

                                </tbody>
                            </table>
                            <input type="hidden" id="id_orden_trabajo" name="id_laboratorio" value="<?php echo $this->modeloLaboratorios->getIdLaboratorio() ?>"/>
                            <input type="hidden" id="conf_orden_trabajo" name="conf_orden_trabajo" value="<?php echo $this->modeloLaboratorios->getConfOrdenTrabajo(); ?>"/>
                            <button type="button" id="bntGuardarOrdenTrabajo"  class="guardar"> Guardar</button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer" align="center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        distribuirLineas();
        fn_getConfigurarOTGuardados();
        fn_getAtributosGuardados(); //Configuración General
    });
    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;

        if (!error) {
            var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
            //Traemos la lista solo si guardo correctamenre
            if (respuesta.estado == 'exito')
            {
                fn_filtrar();
            }

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    function fn_orden_trabajo(idLaboratorio) {
        var url = "<?php echo URL ?>Laboratorios/Laboratorios/ordenTrabajo";
        var data = {
            idLaboratorio: idLaboratorio
        };
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {

            },
            success: function (html) {
                $('#modalOrdenTrabajo').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(elementoDestino).html('Error al cargar los campos de resultado')
            },
            complete: function () {

            }
        });

    }

    $('#bntGuardarOrdenTrabajo').click(function () {
        fn_formarCadenaJSON();
        var url = "<?php echo URL ?>Laboratorios/Laboratorios/configurarOT";
        $.ajax({
            type: "POST",
            url: url,
            data: $("#frmOrdenTrabajo").serialize(),
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {
            },
            success: function (html) {
                $('#modalOrdenTrabajo').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
            },
            complete: function () {
            }
        });
    });

    /**
     * Crea el código json para guardar los permisos 
     * @returns {json}
     */
    function fn_formarCadenaJSON() {
        var total = $('#tblOT >tbody >tr').length;
        jsonObj = [];
        for (var i = 0; i < total; i++) {

            var permitido = $("#tblOT tbody").find("tr").eq(i).find("td").eq(2).find("input").attr("id");
            var visible = $("#" + permitido).prop("checked") ? 'true' : 'false';
            var nombre = $("#tblOT tbody").find("tr").eq(i).find("td").eq(3).find("input").attr("id");
            var contenido = $("#tblOT tbody").find("tr").eq(i).find("td").eq(3).find("input").val();

            item = {};
            item ["id"] = nombre;
            item ["visible"] = visible;
            item ["contenido"] = contenido;

            jsonObj.push(item);
        }
        $("#conf_orden_trabajo").val(JSON.stringify(jsonObj));
    }
    /**
     * Recupera el código json en formulario para actualizar
     * @returns {json}
     */
    function fn_getConfigurarOTGuardados() {
        var atributos = '<?php echo $this->modeloLaboratorios->getConfOrdenTrabajo(); ?>';
        if (atributos !== '') {
            var jsonObj = jQuery.parseJSON(atributos);
            $.each(jsonObj, function (key, value) {

                if (value.visible === 'true') {
                    $("#chk" + value.id).prop("checked", true);
                } else {
                    $("#chk" + value.id).prop("checked", false);
                }
                $("#" + value.id).prop("value", value.contenido);
            });
        }
    }

    //FORMATEO CONFIGURACION GENERAL DEL CAMPO ATRIBUTOS
    function fn_conf_general() {
        $('#modalConfGeneral').modal('show');
    }

    // Función para formar JSON, llamdo al momento de guardar
    function fn_formarCadenaJSONconfGeneral() {
        var total = $('#tblConfiguracionGeneral >tbody >tr').length;
        jsonObj = [];
        for (var i = 0; i < total; i++) {
            var nombre = $("#tblConfiguracionGeneral tbody").find("tr").eq(i).find("td").eq(1).find("input").attr("id");
            var visible = $("#tblConfiguracionGeneral tbody").find("tr").eq(i).find("td").eq(2).find("input").attr("id");
            var requerido = $("#tblConfiguracionGeneral tbody").find("tr").eq(i).find("td").eq(3).find("input").attr("id");
            var vis = $("#" + visible).prop("checked") ? 'block' : 'none';
            var req = $("#" + requerido).prop("checked") ? 'true' : 'false';

            if (nombre === 'Coordenadas') {
                item = {};
                item ["id"] = 'longitud';
                item ["display"] = vis;
                item ["required"] = req;
                jsonObj.push(item);
                item = {};
                item ["id"] = 'latitud';
                item ["display"] = vis;
                item ["required"] = req;
                jsonObj.push(item);
                item = {};
                item ["id"] = 'altura';
                item ["display"] = vis;
                item ["required"] = req;
                jsonObj.push(item);
            } else {
                item = {};
                item ["id"] = nombre;
                item ["display"] = vis;
                item ["required"] = req;
                jsonObj.push(item);
            }
        }
        $("#atributos").val(JSON.stringify(jsonObj));
    }

    // Función para seleccionar de forma automática visible cuando ha seleccionado en requerido
    function fn_checkRequerido(item) {
        $("#chkVis" + item).prop("checked", true);
    }

    // Funcion para deseleccionar el campo requerido
    function fn_uncheckRequerido(item) {
        $("#chkReq" + item).prop("checked", false);
        if (item === "tipo_solicitud") {
            if ($("#chkVis" + item).is(':checked')) {
                $("#chkReq" + item).prop("checked", true);
            }
        }
    }

    //Funcion para poner check o uncheck en Visible, Requerido segun el parametro guardado
    function fn_getAtributosGuardados() {
        var atributos = '<?php echo $this->modeloLaboratorios->getAtributos(); ?>';
        if (atributos !== '') {
            var jsonObj = jQuery.parseJSON(atributos);
            $.each(jsonObj, function (key, value) {
                if (value.id === 'longitud') {
                    if (value.display === 'block')
                        $("#chkVisCoordenadas").prop("checked", true);
                    if (value.required === 'true')
                        $("#chkReqCoordenadas").prop("checked", true);
                } else {
                    if (value.display === 'block')
                        $("#chkVis" + value.id).prop("checked", true);
                    else
                        $("#chkVis" + value.id).prop("checked", false);
                    if (value.required === 'true')
                        $("#chkReq" + value.id).prop("checked", true);
                    else
                        $("#chkReq" + value.id).prop("checked ", false);
                }
            });
        }
    }

    $('#bntGuardarConfGeneral').click(function () {
        fn_formarCadenaJSONconfGeneral();
        var url = "<?php echo URL ?>Laboratorios/Laboratorios/configurarGeneral";
        $.ajax({
            type: "POST",
            url: url,
            data: $("#frmConfGeneral").serialize(),
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {
            },
            success: function (html) {
                $('#modalConfGeneral').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
            },
            complete: function () {
            }
        });
    });
</script>
