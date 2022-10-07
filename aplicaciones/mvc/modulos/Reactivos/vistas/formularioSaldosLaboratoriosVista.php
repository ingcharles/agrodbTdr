<header><h1><?php echo $this->accion; ?></h1></header>	

<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'SaldosLaboratorios/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			
        <legend>Ingreso de reactivos de la solicitud</legend>
        <div id="paginacionRequerimiento" class="normal" style="width: 100%"></div>
        <table width="100%" id="tbrequerimiento">
            <thead><tr>
                    <th></th>
                    <th>#</th>
                    <th title="Nombre del reactivo">Reactivo</th>
                    <th title="Certificado del reactivo">Certificado</th>
                    <th title="Cantidad solicitada por el laboratorio">Cantidad solicitada</th>
                    <th title="Unidad de medida del reactivo">Unidad</br>medida</th>
                    <th title="Lote">Lote</th>
                    <th title="Cantidad recibida">Cantidad recibida</th>
                    <th title="Fecha de caducidad">Fecha de caducidad</th>
                    <th title="Ubicaci&oacute;n del reactivo en el laboratorio">Ubicaci&oacute;n</th>
                    <th title="Solo para eliminar la nueva fila agregada en este formulario">Eliminar</th>
                </tr></thead>
            <tbody id="tablaCantidades">
                <?php
                echo $this->itemsSaldosRequeridos;
                ?>
            </tbody>
        </table>
        <?php if (in_array($this->modeloSolicitudCabecera->getEstado(), array('SOLICITADO', 'EN PROCESO'))): ?>
            <div data-linea ="8">
                <button type ="submit" class="guardar" onclick="fn_fin()"> Guardar</button>
            </div>
        <?php endif; ?>
        <input type="hidden" id="id_solicitud_cabecera" name="id_solicitud_cabecera" value="<?php echo $this->modeloSolicitudCabecera->getIdSolicitudCabecera(); ?>"/>
    </fieldset >
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
    });
    var abrirPanelIz = true;
    // Para agregar un nuevo lote
    function agregar_lote(id) {
        abrirPanelIz = false;
        var url = "SaldosLaboratorios/nuevoLote/" + id + "/" + "<?php echo $this->modeloSolicitudCabecera->getIdSolicitudCabecera(); ?>";
        $("#formulario").attr('data-opcion', url);
        $('#formulario').find('select, textarea, input').each(function () {
            $(this).attr('required', false);
            $(this).removeAttr('step');
            $(this).removeAttr('min');
        });
    }

    function fn_fin() {
        abrirPanelIz = true;
    }

    //Eliminar un item
    function eliminarSaldoLaboratorio(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo URL ?>Reactivos/SaldosLaboratorios/borrar/" + id + "/" + "<?php echo $this->modeloSolicitudCabecera->getIdSolicitudCabecera(); ?>",
            beforeSend: function () {
                $("#paginacionRequerimiento").html("<div id='cargando'>Cargando...</div>");
            },
            success: function (data) {
                $("#paginacionRequerimiento").html("");
                $("#tablaCantidades").html(data);
            }
        });
    }

    $("#formulario").submit(function (event) {
        event.preventDefault();
        if (fn_validar() === true) {
            var error = false;
            if (!error) {
                abrir($(this), event, false);   //requerido para que se mantenga el formulario al crear nuevo lote
                if (abrirPanelIz === true) {
                    abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), "#listadoItems", true);
                }
            } else {
                $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
            }
        }
    });

    function fn_validar() {
        var valid = true;
        //validar que la cantidad recibida no supere a la cantidad solicitada
        var total;

        var jsonObj = jQuery.parseJSON('<?php echo $this->arrayTotales; ?>');
        $.each(jsonObj, function (key, value) {
            $(".agrupa_" + value.id).removeClass('invalid');
            total = 0;
            $(".agrupa_" + value.id).each(function () {
                total = total + parseFloat($(this).val());
            });
            if (total > value.cant_solicitada) {
                valid = false;
                $(".agrupa_" + value.id).addClass('invalid');
                mostrarMensaje("El total ingresado " + total + " no debe supera la cantidad solicitada " + value.cant_solicitada, "FALLO");
            }
        });
        return valid;
    }
</script>