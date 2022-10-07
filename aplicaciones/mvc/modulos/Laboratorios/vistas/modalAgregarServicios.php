<div class="modal" tabindex="-1" role="dialog" id="modalDatosMuestra">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Servicios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">

                </div>
                <form id='frmAgregarServicio'>
                    <div id="detalleServicios">
                    </div>
                    <input type="hidden" name="idDetalleS" id="idDetalleS" value=""/>
                    <input type="hidden" name="idLaboratorio2" id="idLaboratorio2" value=""/>
                    <input type="hidden" name="idServicioAgregar" id="idServicioAgregar" value=""/>
                    <input type="hidden" name="idSolicitud" id="idSolicitud" value=""/>
                </form>
                <fieldset>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Servicio</label>
                        <div class="col-lg-6">
                            <select id="servicio2" name="servicio2" disabled="disabled" class="form-control">
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="div_analisis2" style="display: none">
                        <label class="col-lg-4 control-label">An&aacute;lisis</label>
                        <div class="col-lg-6">
                            <select id="analisis2" name="analisis2" disabled="disabled" class="form-control">
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="div_procedimiento2" style="display: none">
                        <label class="col-lg-4 control-label">Procedimiento</label>
                        <div class="col-lg-6">
                            <select id="procedimiento2" name="procedimiento2" disabled="disabled" class="form-control">
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="div_muestra2" style="display: none">
                        <label class="col-lg-4 control-label">Muestra</label>
                        <div class="col-lg-6">
                            <select id="muestra2" name="muestra2" disabled="disabled" class="form-control">
                            </select>
                        </div>
                    </div>

                    </br><div style="width:100%; text-align:center">
                        <button type="button" id="btnGuardarServicios" class="mas"> Agregar</button>
                    </div>

                </fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                
            </div>
            <div id="estadoModal" style="text-align: left; padding-left: 20px"></div>
        </div>
    </div>
</div>
<script>
    var idServicioAgregar = "";
    function fn_llenarServicios() {
        //descheckear si ha seleccionado para editar ya que debe agregar o editar
        $('.chk_detalle').each(function () {
            $(this).prop('checked', false);
        });
        $("#analisis2").html("");
        $("#analisis2").attr("disabled", "disabled");
        $("#procedimiento2").html("");
        $("#procedimiento2").attr("disabled", "disabled");
        $("#div_analisis2").hide();
        $("#div_procedimiento2").hide();
        $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboServicios/" + $("#idLaboratorio2").val(), function (data) {
            $("#servicio2").html(data);
            $("#servicio2").removeAttr("disabled");
        });
    }

    //Cuando seleccionamos un servicio, llenamos el combo de analisis
    $("#servicio2").change(function () {
        idServicioAgregar = "";
        $("#analisis2").html("");
        $("#div_analisis2").hide();
        $("#procedimiento2").html("");
        $("#div_procedimiento2").hide();
        $("#muestra2").html("");
        $("#div_muestra2").hide();
        var datos = $(this).find(':selected').attr('data-id');
        hijosServicioSeleccionado = fn_obtener_parametro('hijos', datos);
        valor = fn_obtener_parametro('valor', datos);
        if (hijosServicioSeleccionado === 'varias') {
            $("#div_analisis2").show();
            $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboServicio/" + $(this).val(), function (data) {
                $("#analisis2").html(data);
                $("#analisis2").removeAttr("disabled");
            });
        } else {
            idServicioAgregar = $(this).val();
        }
    });

    //Cuando seleccionamos un analisis, llenamos el combo de procedimiento
    $("#analisis2").change(function () {
        idServicioAgregar = "";
        var datos = $(this).find(':selected').attr('data-id');
        $("#cantidad2").val("");
        $("#procedimiento2").html("");
        $("#procedimiento2").attr("disabled", "disabled");
        $("#div_procedimiento2").hide();
        $("#div_muestra2").hide();
        hijosAnalisisSeleccionado = fn_obtener_parametro('hijos', datos);
        if (hijosAnalisisSeleccionado === 'varias') {
            $("#div_procedimiento2").show();
            $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboServicio/" + $(this).val(), function (data) {
                $("#procedimiento2").html(data);
                $("#procedimiento2").removeAttr("disabled");
            });
        } else {
            idServicioAgregar = $(this).val();
        }
    });

    //Cuando seleccionamos un procedimiento, llenamos el tipo de muestra
    $("#procedimiento2").change(function () {
        idServicioAgregar = "";
        var datos = $(this).find(':selected').attr('data-id');
        $("#cantidad2").val("");
        $("#muestra2").html("");
        $("#muestra2").attr("disabled", "disabled");
        $("#div_muestra2").hide();
        hijosProcedimientoSeleccionado = fn_obtener_parametro('hijos', datos);
        if (hijosProcedimientoSeleccionado === 'varias') {
            $("#div_muestra2").show();
            $.post("<?php echo URL ?>Laboratorios/Solicitudes/comboServicio/" + $(this).val(), function (data) {
                $("#muestra2").html(data);
                $("#muestra2").removeAttr("disabled");
            });
        } else {
            idServicioAgregar = $(this).val();
        }
    });

    //Cuando seleccionamos un procedimiento
    $("#muestra2").change(function () {
        idServicioAgregar = "";
        idServicioAgregar = $(this).val();
    });

    //Selecciona/deselecciona las muestras segun en check encabezado
    function fn_seleccionarMuestras() {
        if ($("#chkSeleccionarMuestras").is(':checked')) {
            $('.clsSleccionarMuestras').each(function () {
                $(this).prop('checked', true);
            });
        } else {
            $('.clsSleccionarMuestras').each(function () {
                $(this).prop('checked', false);
            });
        }
    }

    $('#btnGuardarServicios').click(function () {
        $("#estadoModal").removeClass();
        if (idServicioAgregar === "") {
            mostrarMensajeModal("Seleccione el servicio.","FALLO");
        } else if ($('[name="muestras[]"]:checked').length === 0) {
            mostrarMensajeModal("Seleccione al menos una muestra","FALLO");
        } else {
            $("#idServicioAgregar").val(idServicioAgregar);
            var url = "<?php echo URL ?>Laboratorios/Solicitudes/anadirServicios";
            $.ajax({
                type: "POST",
                url: url,
                data: $("#frmAgregarServicio").serialize(),
                dataType: "json",
                contentType: "application/x-www-form-urlencoded; charset=latin1",
                beforeSend: function () {
                    
                },
                success: function (data) {
                    if (data.estado === 'exito') {
                        $("#tblDetalleSolicitudesGuardado tbody").html(data.detalle);
                        $("#chkSeleccionarMuestras").prop('checked', false);
                        $('.clsSleccionarMuestras').each(function () {
                            $(this).prop('checked', false);
                        });
                        mostrarMensajeModal(data.mensaje,"EXITO");
                    } else {
                        mostrarMensajeModal(data.mensaje,"FALLO");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                },
                complete: function () {
                }
            });
        }
    });
</script>