<header>
    <h1><?php echo $this->accion; ?></h1>
</header>	

<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'SolicitudRequerimiento/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			
        <legend>Solicitud de reactivos para el laboratorio</legend>	
        <button id="sbm" style="display: none"/>
        <table width="100%" id="tbrequerimiento">
            <thead><tr>
                    <th>#</th>
                    <th title="C&oacute;digo del reactivo">Código</th>
                    <th title="Nombre del reactivo">Reactivo</th>
                    <th title="Saldo en bodega">Saldo en bodega</th>
                    <th title="Unidad de medida del reactivo">Unidad</th>
                    <th title="Cantidad requerida por el Laboratorio">Cantidad a solicitar</th>

                    <th>Eliminar</th>
                </tr></thead>
            <tbody id="tablaCantidades">
                <?php
                echo $this->itemsRequeridos;
                ?>
            </tbody>
        </table>
        <div data-linea ="8">
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
        <hr>

        <label for="observacion"> Observación </label>
        <div data-linea ="9">
            <textarea rows="4" cols="50" id="observacion" name ="observacion"
                      placeholder="Escribir una observación antes de enviar el requerimiento"><?php echo $this->modeloSolicitudCabecera->getObservacion(); ?></textarea>
        </div>
        <div data-linea ="10">
            <button type ="button" id="enviarRequerimiento" class="fas fa-share-square">Enviar solicitud</button>
            <input type="hidden" id="id_solicitud_cabecera" name="id_solicitud_cabecera" value="<?php echo $this->modeloSolicitudCabecera->getIdSolicitudCabecera(); ?>"/>
            <input type="hidden" id="estadoSolicitud" name="estado" value="<?php echo $this->modeloSolicitudCabecera->getEstado(); ?>"/>
            <input type="hidden" id="idLaboratoriosProvincia" value="<?php echo $this->modeloSolicitudCabecera->getIdLaboratoriosProvincia(); ?>"/>
            <input type="hidden" id="idLaboratorio" value="<?php echo $this->modeloSolicitudCabecera->getIdLaboratorio(); ?>"/>
            <input type="hidden" id="idBodega" value="<?php echo $this->modeloSolicitudCabecera->getIdBodega(); ?>"/>
        </div>
    </fieldset >
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
    });
    //Eliminar un item
    function eliminarRequerimiento($idSolicitudRequerimiento) {
        $.ajax({
            type: "POST",
            url: "<?php echo URL ?>Reactivos/SolicitudRequerimiento/borrar/" + $idSolicitudRequerimiento + "/" + $("#id_solicitud_cabecera").val(),
            beforeSend: function () {
                $("#listaSolicitud").css("background", "#FFF url(aplicaciones/general/img/cargando.gif) no-repeat 165px");
            },
            success: function (data) {
                $("#tablaCantidades").html(data);
                $("#listaSolicitud").css("background", "#FFF");
            }
        });
    }
    //Guardar las cantidades
    $("#formulario").submit(function (event) {
        event.preventDefault();
        if (fn_validar() === true) {
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
        }
    });

    // Función para filtrar
    function fn_filtrar() {
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>Reactivos/SolicitudRequerimiento/listaActualizar",
                {
                },
                function (data) {
                    $("#div_filtros").css('display', 'none');
                    $("#tablaItems").empty();
                    $("#tablaItems").html('<thead><tr><th>#</th><th>Laboratorio solicitante</th><th>Tipo</th><th>Solicitado a</th><th>Fecha</th><th>Observación</th><th>Estado</th><th>Descargar</th></tr></thead><tbody></tbody>');
                    construirPaginacion($("#paginacion"), JSON.parse(data));
                });
    }

    function fn_validar() {
        var valid = 1;
        //validar que exist al menos un registro
        var total = $('#tbrequerimiento >tbody >tr').length;
        if (total == 0) {
            mostrarMensaje("Al menos selecciones un reactivo.", "FALLO");
            valid = 0;
        }
        $('#formulario').find('select, textarea, input').each(function () {
            var inpObj = document.getElementById($(this).attr('id'));
            if (inpObj !== null) {
                if (!inpObj.checkValidity()) {
                    document.getElementById("sbm").click();
                    valid = 0;
                    return false;
                }
            }
        });
        if (valid == 0) {
            return false;
        } else {
            return true;
        }
    }

    //Para finalizar se debe enviar la solicitud
    $("#enviarRequerimiento").click(function () {
        if (fn_validar() === true) {
            if (confirm("Está seguro de enviar la solicitud?")) {
                $("#estadoSolicitud").val("SOLICITADO");
                $("#formulario").submit();
            } else {
                return false;
            }
        }
    });

    //cargamos los reactivos
    $("#tablaItems").empty();
    $("#tablaItems").html('<thead><tr><th>#</th><th>Provincia*</th><th>Bodega</th><th>Reactivo</th><th>Cantidad</th><th>Egresos</th><th>Saldo</th><th>Unidad</th><th>Estado</th><th>Agregar</th></tr></thead><tbody></tbody>');
    construirPaginacion($("#paginacion"),<?php echo(json_encode($this->itemsReactivos, JSON_UNESCAPED_UNICODE)); ?>);

    if ($("#id_solicitud_cabecera").val() !== '') {
        $("#div_filtros").css('display', 'block');
        distribuirLineas();
        $('#id_laboratorios_provincia option[value="<?php echo $this->modeloSolicitudCabecera->getIdLaboratoriosProvincia(); ?>"]').prop('selected', true);
        $('#id_bodega option[value="<?php echo $this->modeloSolicitudCabecera->getIdBodega(); ?>"]').prop('selected', true);
    }
</script>
