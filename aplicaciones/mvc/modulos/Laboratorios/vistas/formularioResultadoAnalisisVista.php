<link rel='stylesheet' href='<?php echo URL_MVC_MODULO ?>Laboratorios/vistas/estilos/estiloModal.css'>
<script src="<?php echo URL_RESOURCE ?>js/js_comunes.js" type="text/javascript"></script> 
<header>
    <h1>An&aacute;lisis de la muestra</h1>
</header>

<fieldset>
    <legend>Ingresar an&aacute;lisis de la muestra</legend>
    <fieldset>
        <legend class='legendBusqueda'>Filtros de b&uacute;squeda</legend>
        <div data-linea="1">
            <label for="fCodigoMuestra">C&oacute;digo de la muestra</label> 
            <input type="text" id="fCodigoMuestra" name="fCodigoMuestra" placeholder="C&oacute;digo de la muestra" value="<?php echo $this->fCodigoMuestra; ?>"/>
        </div>

        <div data-linea="1">
            <label for="fAnalisisMuestra">An&aacute;lisis</label> 
            <input type="text" id="fAnalisisMuestra" name="fAnalisisMuestra" placeholder="Nombre an&aacute;lisis" value="<?php echo $this->fAnalisisMuestra; ?>"/>
        </div>

        <button type="button" id="btnFiltrarRA" class="fas fa-search"> Filtrar lista</button>
        <button type="button" id="btnLimpiarRA" class="fas fa-times"> Limpiar filtros</button>
    </fieldset>
    <div id="divRespuestaHorizontal">
        <!-- aqui mensajes, ejemplo: al hacer el descuento automatico si no existe una receta -->
        <?php echo $this->respuestaHtml; ?>
    </div>
    <div id="div_datos" class="normal" style="width: 100%">
        <!-- Esta tabla es temporal ya que se reemplaza con lo que se envía desde el controlador -->
        <table style='width: 100%'>
            <thead>
                <tr>
                    <th>#</th>
                    <th title="C&oacute;digo de la muestra">C&oacute;digo muestra</th>
                    <th title="C&oacute;digo de campo de la muestra">C&oacute;digo de campo</th>
                    <th title="Nombre del an&aacute;lisis">An&aacute;lisis</th>
                    <th title="Fecha de inicio del an&aacute;lisis">Fecha inicio An&aacute;lisis</th>
                    <th title="Estado de la muestra">Estado</th>
                </tr>
            <tbody>
                <tr><td colspan="5">Dar clic en Filtrar lista para mostrar los datos</td></tr>
            </tbody>
            </thead>
        </table>
    </div>
</fieldset>



<!-- Modal para desplegar los campos dinámicos del resultados -->
<div class="modal fade" id="modalResultado" role="dialog">
    <div class="modal-dialog">
        <div class="center-block" class="modal-dialog" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ingreso de resultados</h4>
                </div>
                <div class="form-horizontal">
                    <div class="modal-body">
                        <form id='frmResultados'>
                            <button id="sbm" style="display: none"/>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group" >
                                        <label for="" class="col-lg-4 control-label"> C&oacute;digo muestra:</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="codMuestra" class="form-control" value="" readonly/>
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label for="" class="col-lg-4 control-label"> An&aacute;lisis:</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="nomAnalisis" class="form-control" value="" readonly/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div id="divCamposResultado" class="center-block"></div>
                            </div>
                            <div class="row">
                                <input type="hidden" id="id_orden_trabajo" name="id_orden_trabajo" value="<?php echo $this->idOrdenTrabajo ?>"/>
                                <input type="hidden" id="idServicio" name="idServicio" value=""/>
                                <input type="hidden" id="idRecepcionMuestras" name="idRecepcionMuestras" value=""/>
                                <input type="hidden" id="rama" name="rama" value=""/>
                                <input type="hidden" id="numResultado" name="numResultado" value=""/>
                                <button type="button" id="bntGuardarResultadoAnalisis" class="guardar"> Guardar</button>
                            </div>
                        </form>
                        <div id="divRespuesta" style="display: none">
                            <!-- aqui mensajes, ejemplo: al hacer el descuento automatico si no existe una receta -->
                        </div>
                        <div id="divResultadosGuardados" style="overflow-x: auto;">
                            <!-- aqui lista de analisis de de la muestra -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer" align="center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
                <div id="estadoModal" style="text-align: left; padding-left: 20px"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        $('.checklist').fSelect();
        $("#btnFiltrarRA").click(function () {
            fn_filtrarRA();
        });

        //Para Limpiar los filtros
        $("#btnLimpiarRA").click(function () {
            $("#fCodigoMuestra").val('');
            $("#fAnalisisMuestra").val('')
        });
    });


    fn_filtrarRA();

    // Función para filtrar
    function fn_filtrarRA() {
        $("#div_datos").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Laboratorios/ResultadoAnalisis/listarDatos/" + $("#id_orden_trabajo").val(),
                {
                    fCodigoMuestra: $("#fCodigoMuestra").val(),
                    fAnalisisMuestra: $("#fAnalisisMuestra").val(),
                    idLaboratoriosProvincia: $("#id_laboratorios_provincia").val()
                },
        function (data) {
            $("#div_datos").html(data);
            $('.checklist').fSelect();
        });
    }

    //para ver en modal para el ingreso de resultados tipo VERTICAL
    function fn_camposResultado(idRecepcionMuestras, idServicio, s_rama) {
        $("#estadoModal").html("");
        $("#estadoModal").removeClass();
        $('#idServicio').val(idServicio);
        $('#idRecepcionMuestras').val(idRecepcionMuestras);
        $('#rama').val(s_rama);
        var url = "<?php echo URL ?>Laboratorios/ResultadoAnalisis/verCamposResultado";
        var data = {
            idRecepcionMuestras: idRecepcionMuestras,
            idServicio: idServicio,
            rama: s_rama
        };
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {

            },
            success: function (data) {
                if (data.estado === 'EXITO') {
                    $('#modalResultado').modal('show');
                    $("#divRespuesta").html('');
                    $("#divRespuesta").css('display', 'none');
                    $("#codMuestra").val(data.codigo);
                    $("#nomAnalisis").val(data.analisis);
                    $("#divCamposResultado").html(data.formulario);
                    $("#divResultadosGuardados").html(data.lista);
                } else {
                    mostrarMensaje(data.mensaje, "FALLO");
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#div_datos").html("Error al cargar los campos de resultado");
            },
            complete: function () {

            }
        });
    }

    //al cerrar el modal debe refrescar el filtro
    $("#modalResultado").on("hidden.bs.modal", function () {
        fn_filtrarRA();
    });

    //Guardar el resultado del modal tipo VERTICAL
    $("#bntGuardarResultadoAnalisis").click(function () {
        $("#estadoModal").removeClass();
        if (fn_validar('frmResultados') === 1) {
            var url = "<?php echo URL ?>Laboratorios/ResultadoAnalisis/guardarTipoVertical";
            var data = $("#frmResultados").serialize();
            data = data + "&idLaboratoriosProvincia=" + $("#id_laboratorios_provincia").val();
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "json",
                contentType: "application/x-www-form-urlencoded; charset=latin1",
                beforeSend: function () {
                    $("#divResultadosGuardados").html("<div id='cargando'>Cargando...</div>");
                },
                success: function (data) {
                    if (data.estado === 'EXITO') {
                        $("#divCamposResultado").html(data.formulario);
                        $("#divResultadosGuardados").html(data.lista);
                        if (data.respuesta !== "") {
                            $("#divRespuesta").html(data.respuesta);
                            $("#divRespuesta").css('display', 'block');
                        }
                        $("#numResultado").val("");
                        mostrarMensajeModal(data.mensaje, "EXITO");
                    } else {
                        $("#divResultadosGuardados").html("");
                        mostrarMensajeModal(data.mensaje, "FALLO");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    mostrarMensaje("Ocurrió un error al guardar el registro. Revisar los campos.", "FALLO");
                },
                complete: function () {

                }
            });
        }
    });

    //anular un resultado de analisis
    function fn_anular(numResultado) {
        var respuesta = confirm("Confirmar si desea anular este análisis.");
        if (respuesta) {
            var url = "<?php echo URL ?>Laboratorios/ResultadoAnalisis/anularAnalisis";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    idRecepcionMuestras: $("#idRecepcionMuestras").val(),
                    numResultado: numResultado,
                    idServicio: $("#idServicio").val(),
                    rama: $("#rama").val()
                },
                dataType: "json",
                contentType: "application/x-www-form-urlencoded; charset=latin1",
                beforeSend: function () {
                    $("#divResultadosGuardados").html("<div id='cargando'>Cargando...</div>");
                },
                success: function (data) {
                    $("#divRespuesta").html('');
                    $("#divRespuesta").css('display', 'none');
                    $("#divCamposResultado").html(data.formulario);
                    $("#divResultadosGuardados").html(data.lista);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                },
                complete: function () {

                }
            });
        }
    }

    //VERTICAL: Al dar clic en icono Editar se muestra los datos ingresados
    function fn_editar(numResultado) {
        $("#estadoModal").html("");
        $("#estadoModal").removeClass();
        $("#divRespuesta").html('');
        $("#divRespuesta").css('display', 'none');
        $('#numResultado').val(numResultado);
        var url = "<?php echo URL ?>Laboratorios/ResultadoAnalisis/verCamposResultadoEditar";
        var data = {
            idRecepcionMuestras: $("#idRecepcionMuestras").val(),
            idServicio: $("#idServicio").val(),
            rama: $("#rama").val(),
            numResultado: $("#numResultado").val()
        };
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {
                $("#divCamposResultado").html("<div id='cargando'>Cargando...</div>");
            },
            success: function (html) {
                $("#divCamposResultado").html(html);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#div_datos").html("Error al cargar los campos de resultado");
            },
            complete: function () {

            }
        });
    }

    //Actualizar el campo g_laboratorios.recepcion_muestras.acreditado
    function fn_actualizarAcreditado(idRecepcionMuestras, campo) {
        var url = "<?php echo URL ?>Laboratorios/ResultadoAnalisis/actualizarAcreditado/" + idRecepcionMuestras;
        var data = {
            acreditado: $(campo).val()
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
                $("#divCamposResultado").html(html);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#div_datos").html("Error al cargar los campos de resultado");
            },
            complete: function () {

            }
        });

    }
    //guardar los datos de cada análisis
    function fn_guardarAnalisis(idServicio) {
        var frm = "formMuestra" + idServicio;
        var btn = "sbm" + idServicio;
        if (fn_validarResultadoHorizontal(frm, btn) === 1) {
            var url = "<?php echo URL ?>Laboratorios/ResultadoAnalisis/guardarTipoHorizontal";
            $.ajax({
                type: "POST",
                url: url,
                data: $("#formMuestra" + idServicio).serialize(),
                dataType: "text",
                contentType: "application/x-www-form-urlencoded; charset=latin1",
                beforeSend: function () {

                },
                success: function (html) {
                    $("#msg" + idServicio).html('<strong>Guardado con éxito<strong>');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#msg" + idServicio).html('<strong>Error: <strong>El resultado del análisis no se guardó');
                },
                complete: function () {

                }
            });
        }
    }

    //Funcion para validar los campos por cada muestra para tipo horizotnal 
    function fn_validarResultadoHorizontal(nombreElemento, btn) {
        var continuar = 1;
        $('#' + nombreElemento).find('select, textarea, input').each(function () {
            var inpObj = document.getElementById($(this).attr('id'));
            if (inpObj !== null) {
                try {
                    if (!inpObj.checkValidity()) {
                        document.getElementById(btn).click();
                        continuar = 0;
                        return false;
                    }
                }
                catch (err) {
                }
            }
        });
        return continuar;
    }
</script>

