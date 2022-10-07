<header><h1><?php echo $this->accion; ?></h1></header>	
<fieldset>			
    <legend>Saldos por Lote</legend>
    <i class="fas fa-info-circle"></i><span> Dar doble clic para ver el k&aacute;rdex.</span>
    <div id="paginacion" class="normal" style="width: 100%"></div>
    <table width="100%" id="tbrequerimiento">
        <thead><tr>
                <th>#</th>
                <th>Reactivo</th>
                <th>Unidad medida</th>
                <th>Lote</th>
                <th>Ingresos</th>
                <th>Egresos</th>
                <th>Saldo</th>
                <th>Fecha Caducidad</th>
                <th>Registrar salida</th>
            </tr></thead>
        <tbody>
            <?php
            echo $this->itemsSaldosLote;
            ?>
        </tbody>
    </table>
</fieldset>

<!-- Modal para desplegar el ingreso de reactivos usados en la solución -->
<div class="modal fade" id="modalRegistroSalida" role="dialog">
    <div class="modal-dialog">
        <div class="center-block" class="modal-dialog" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Registrar la salida del reactivo</h4>
                </div>
                <div class="form-horizontal">
                    <div class="modal-body">
                        <form id = 'frmResultados' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
                              data-opcion = 'SaldosLaboratorios/guardarSalidaReactivo' data-destino ="detalleItem"			 
                              data-accionEnExito ="NADA" method="post">	

                            <span class="clearfix"></span>
                            <div>
                                <table width="100%">
                                    <thead><tr>
                                            <th>Reactivo</th>
                                            <th>Unidad medida</th>
                                            <th>Lote</th>
                                            <th>Ingresos</th>
                                            <th>Egresos</th>
                                            <th>Saldo</th>
                                            <th>Fecha Caducidad</th>
                                        </tr></thead>
                                    <tbody id="datosLote">
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group" >
                                <label for="cod_catalago" class="col-lg-4 control-label"> Raz&oacute;n salida</label>
                                <div class="col-lg-6">
                                    <select id="cod_catalago" name="cod_catalago" class="form-control" required>
                                        <option value="">Seleccionar....</option>
                                        <?php echo $this->comboCatalogo($this->codBaja, null); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" >
                                <label for="cantidad" class="col-lg-4 control-label"> Cantidad</label>
                                <div class="col-lg-6">
                                    <input type ="number" id="cantidad" class="form-control"
                                           name ="cantidad" step="0.000001" value="0.00" placeholder="0.00" min="0.000001" lang="en"/>
                                </div>
                            </div>

                            <div class="form-group" >
                                <label for="observacion" class="col-lg-4 control-label"> Observaci&oacute;n</label>
                                <div class="col-lg-6">
                                    <textarea id="observacion" name ="observacion" class="form-control"
                                              placeholder ="Observaci&oacute;n"></textarea>
                                </div>
                            </div>
                            <button type ="submit" class="guardar"> Guardar</button>
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
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
    });

    function fn_verModalRegistroSalida(idReactivoLaboratorio, lote) {
        var url = "aplicaciones/" + "mvc/Reactivos"
                + "/" + "SaldosLaboratorios/editarSaldo" + ".php";
        var data = {
            id_reactivo_laboratorio: idReactivoLaboratorio,
            lote: lote
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
                $("#datosLote").html(html);
                $('#modalRegistroSalida').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
            },
            complete: function () {

            }
        });
    }

    $("#frmResultados").submit(function (event) {
        event.preventDefault();
        $('#modalRegistroSalida').modal('hide');
        $(".modal-backdrop").remove();
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
</script>