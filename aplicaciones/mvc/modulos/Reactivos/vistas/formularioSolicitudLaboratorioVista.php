<header>
    <h1><?php echo $this->accion; ?></h1>
</header>	

<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'SolicitudRequerimiento/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">	

    <fieldset>			
        <legend>Datos de la solicitud</legend>	
        <div data-linea ="1">
            <label for="fecha_solicitud"> Fecha solicitud </label>
            <input type="text" id="fecha_solicitud" value="<?php echo $this->modeloSolicitudCabecera->getFechaSolicitud(); ?>" 
                   readonly style="background: transparent; border: 0"/>
        </div>
    </fieldset>
    <fieldset>			
        <legend>Solicitud de reactivos para el laboratorio</legend>	
        <button id="sbm" style="display: none"/>
        <table width="100%" id="tbrequerimiento">
            <thead><tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th title="Puede ser REACTIVO o SOLUCION">Tipo</th>
                    <th>Unidad</th>
                    <th>Saldo</th>
                    <th>Cantidad requerida</th>
                    <th>Eliminar</th>
                </tr></thead>
            <tbody id="tablaCantidades">
                <?php
                echo $this->itemsRequeridos;
                ?>
            </tbody>
        </table>
        <div data-linea ="1">
            <button type ="submit" class="guardar"> Guardar</button>
        </div>

        <label for="observacion"> Observación </label>
        <div data-linea ="2">
            <textarea id="observacion" name ="observacion"
                      placeholder="Escribir una observación antes de enviar el requerimiento"><?php echo $this->modeloSolicitudCabecera->getObservacion(); ?></textarea>
        </div>

        <div data-linea ="3">
            <button type ="button" id="enviarRequerimiento" class="fas fa-share-square">Enviar solicitud</button>
            <input type="hidden" id="id_solicitud_cabecera" name="id_solicitud_cabecera" value="<?php echo $this->modeloSolicitudCabecera->getIdSolicitudCabecera(); ?>"/>
            <input type="hidden" id="estadoSolicitud" name="estado" value="<?php echo $this->modeloSolicitudCabecera->getEstado(); ?>"/>
            <input type="hidden" id="idLaboratoriosProvincia" value="<?php echo $this->modeloSolicitudCabecera->getIdLaboratoriosProvincia(); ?>"/>
            <input type="hidden" id="idLaboratorio" value="<?php echo $this->modeloSolicitudCabecera->getIdLaboratorio(); ?>"/>
            <input type="hidden" id="idBodega" value="<?php echo $this->modeloSolicitudCabecera->getIdBodega(); ?>"/>
        </div>
    </fieldset>
</form>
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
    });

    $("#idSolicitudCabecera").val('<?php echo $this->modeloSolicitudCabecera->getIdSolicitudCabecera(); ?>');

    //Eliminar un item
    function eliminarRequerimiento($idSolicitudRequerimiento) {
        $.ajax({
            type: "POST",
            url: "<?php echo URL ?>Reactivos/SolicitudLaboratorio/borrar/" + $idSolicitudRequerimiento + "/" + $("#id_solicitud_cabecera").val(),
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
                    if ($("#estadoSolicitud").val() == "SOLICITADO") {
                        fn_regresarInicio(respuesta.mensaje);
                    }
                }

            } else {
                $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
            }
        }
    });

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

    function fn_regresarInicio(respuesta) {
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Reactivos/SolicitudRequerimiento";
        var data = {};
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {
                $(elementoDestino).html("<div id='cargando'>Cargando...</div>").fadeIn();
            },
            success: function (html) {
                $("#listadoItems").html(html);
                mostrarMensaje(respuesta,'EXITO');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $(elementoDestino).html(
                        "<div id='error'>¡Ups!... algo no anda bien.<br />"
                        + "Se produjo un " + textStatus + " "
                        + jqXHR.status
                        + ".<br />Disculpe los inconvenientes causados.</div>");
            },
            complete: function () {
            }
        });
    }
</script>
