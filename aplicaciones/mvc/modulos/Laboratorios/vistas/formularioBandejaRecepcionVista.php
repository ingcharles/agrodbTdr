<header>
    <h1>Recibir muestras</h1>
</header>
<fieldset>
    <legend>&Oacute;rdenes de trabajo</legend>

    <table width="100%" id="tablaOrdenTrabajo">
        <thead>
            <tr>
                <th>#</th>
                <th title="C&oacute;digo de la orden de trabajo, se genera la activar la orden">C&oacute;digo de Orden de Trabajo</th>
                <th title="Laboratorio">Laboratorio</th>
                <th title="Fecha en que se activa la orden">Fecha de inicio</th>
                <th title="Estado de la orden de trabajo">Estado</th>
                <th title="Costo de la orden">Costo en Orden</th>
                <th title="Descargar la orden de trabajo">Descargar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->itemsOrdenesTrabajo as $fila)
            {
                echo $fila[0];
            }
            ?>
        </tbody>
    </table>
</fieldset>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='BandejaRecepcion/guardarMuestras' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Recepción de la muestra</legend>

        <table width="100%" id="muestras">
            <thead>
                <tr>
                    <th>#</th>
                    <th title="C&oacute;digo de campo de la muestra"><?php echo $this->obtenerAtributoLaboratorio($this->idLaboratorio, 'm_cod_campo'); ?></th>
                    <th title="An&aacute;lisis solicitado de la muestra">An&aacute;lisis</th>
                    <th title="Conservaci&oacute;n de la muestra">Conservaci&oacute;n de la muestra</th>
                    <th title="Clic para acepta la muestra">Aceptada?</br>Todos<input type='checkbox' value="on" onclick="fn_selectAllCmbByClass(this, 'cls_selectAllCmbByClass')" /></th>
                    <th title="Observaci&oacute;n de la muestra">Observaci&oacute;n</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($this->itemsMuestras as $muestra)
                {
                    echo $muestra[0];
                }
                ?>
            </tbody>
        </table>
        <div  data-linea="1">
            <input type ="hidden" id="idSolicitud" name ="idSolicitud" value="<?php echo $this->idSolicitud; ?>"/>
            <input type ="hidden" id="idLaboratorio" name ="idLaboratorio" value="<?php echo $this->idLaboratorio; ?>"/>
            <input type ="hidden" id="idOrdenTrabajo" name ="idOrdenTrabajo" value="<?php echo $this->idOrdenTrabajo; ?>"/>

            <?php
            if (isset($this->modeloOrdenTrabajo))
            {
                if ($this->modeloOrdenTrabajo->getEstado() == null | $this->modeloOrdenTrabajo->getEstado() == 'REGISTRADA')
                {
                    echo '<button id="btnGuardar" type="submit" class="guardar" title="Mientras no est&eacute; activada la orden puede actualizar los datos"> Registrar Recepción Muestras</button>';     //guardar nuevo/cambios
                }
                if ($this->modeloOrdenTrabajo->getEstado() == 'REGISTRADA')
                {
                    if ($this->modeloSolicitudes->getTipoSolicitud() == \Agrodb\Core\Constantes::tipo_SO()->POSTREGISTRO)
                    {
                        echo '<button id="btnGuardar" type="button" class="guardar" onclick="fn_activarRegistrarPago()" title="Tipo de solicitud: POSTREGISTRO, se puede registrar el pago"> Registrar Pago</button>';
                        echo '<button type="button" id="btnActivarOrden" class="guardar" onclick="fn_activarOrden()" title="Tipo de solicitud: POSTREGISTRO, se puede activar la orden"> Activar Orden</button>';
                    } else
                    {
                        if ($this->modeloSolicitudes->getExoneracion() == 'NO')
                        {
                            if ($this->existePago == 'SI') //la solicitud registra un pago
                            {
                                echo '<button type="button" id="btnActivarOrden" class="guardar" onclick="fn_activarOrden()" title="La orden de trabajo est&aacute; REGISTRADA, la solicitud registra un pago"> Activar Orden</button>';
                            } else if ($this->existePago == 'NO')
                            {
                                echo '<button type="button" id="btnGuardar" class="guardar" onclick="fn_activarRegistrarPago()" title="La orden de trabajo est&aacute; REGISTRADA, la solicitud no registra ing&uacute;n pago"> Registrar Pago</button>';
                            }
                        } else if ($this->modeloSolicitudes->getExoneracion() == 'SI')
                        {
                            echo '<button type="button" id="btnActivarOrden" class="guardar" onclick="fn_activarOrden()" title="Tiene exoneración de pago, se puede activar la orden"> Activar Orden</button>';
                        }
                    }
                }
                if ($this->modeloOrdenTrabajo->getEstado() == 'ACTIVA' & $this->modeloSolicitudes->getTipoSolicitud() == \Agrodb\Core\Constantes::tipo_SO()->POSTREGISTRO & $this->existePago == 'NO')
                {
                    echo '<button type="button" id="btnGuardar" class="guardar" onclick="fn_activarRegistrarPago()"> Registrar Pago</button>';
                }
            }
            ?> 
        </div>
    </fieldset>
    <input type="hidden" name="identificador" id="identificador"
           value="<?php echo $this->usuarioActivo() ?>">
</form>
<script type="text/javascript">
<?php echo $this->codigoJS ?>
    distribuirLineas();
    fn_verificarSI();

    //Para selecionar todos los combos
    function fn_selectAllCmbByClass(thisCheck, nombreClase) {
        var isChecked = $(thisCheck).is(":checked");
        if (isChecked === true) {
            $("." + nombreClase + " option[value='SI']").prop('selected', true);
        } else {
            $("." + nombreClase + " option[value='NO']").prop('selected', true);
        }
        fn_verificarSI();
    }

    //activa la orden de trabajo
    function fn_activarOrden() {
        var url = "BandejaRecepcion/activarOrden";
        $("#formulario").attr('data-opcion', url);
        $("#formulario").submit();
        $("#fEstado").val('');
        $("#fCodigo").val('<?php echo $this->modeloSolicitudes->getCodigo() ?>');
        fn_filtrar();
    }

    //para abrir el formulario de registro de pago
    function fn_activarRegistrarPago() {
        var url = "BandejaRecepcion/verFormularioRegistrarPago";
        $("#formulario").attr('data-opcion', url);
        $("#formulario").attr('pago', 'SI');
        $("#formulario").submit();
    }

    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;

        if (!error) {

            if ($("#formulario").attr("pago") == 'SI')
            {
                $("#fEstado").val('');
                $("#fCodigo").val('<?php echo $this->modeloSolicitudes->getCodigo() ?>');
                abrir($(this), event, false);
                fn_filtrar();
            } else {
                var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
                //Traemos la lista solo si guardo correctamenre
                if (respuesta.estado == 'exito')
                {
                    $("#fEstado").val('');
                    $("#fCodigo").val('<?php echo $this->modeloSolicitudes->getCodigo() ?>');
                    fn_filtrar();
                    fn_actualizar_ot();
                }
            }
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });

    //Actualizar panel derecho
    function  fn_actualizar_ot() {
        var url = "<?php echo URL ?>Laboratorios/BandejaRecepcion/verOrdenesTrabajoJson";
        var itemsOt = new Array();
        var data = {
            id: '<?php echo $this->modeloSolicitudes->getIdSolicitud() ?>'
        };
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {
                $("#tablaOrdenTrabajo tbody").html("");
                $("#muestras tbody").html("");
                $("#btnGuardar").attr("disabled", "disabled");
                $("#btnActivarOrden").attr("disabled", "disabled");
            },
            success: function (json) {
                itemsOt = $.parseJSON(json);
                for (var contador = 0; contador <= itemsOt.length; contador++)
                    $("#tablaOrdenTrabajo tbody").append(itemsOt[contador]);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('estado').html('Error al cargar los campos de resultado')
            },
            complete: function () {

            }
        });
    }

    //para descargar la orden de trabajo si está en estado ACTIVA
    function  fn_descargar_ot(idOrdenTrabajo)
    {
        var url = "<?php echo URL ?>Laboratorios/BandejaInformes/descargarOt/" + idOrdenTrabajo;
        $.ajax({
            type: "POST",
            url: url,
            dataType: "text",
            contentType: "application/x-www-form-urlencoded; charset=latin1",
            beforeSend: function () {

            },
            success: function (html) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#detalleItem").html('Error al cargar los campos de resultado')
            },
            complete: function () {

            }
        });
    }

    //Si ha seleccionado NO
    function fn_verificarSI() {
        var habilitarActivarOrden = '0';
        $('.esAceptada').each(function () {
            if ($(this).val() === 'NO') {
                return false;   //permite salir del bucle
            }
        });
        $('.esAceptada').each(function () {
            if ($(this).val() === 'SI') {
                habilitarActivarOrden = '1';
                return false;   //permite salir del bucle
            }
        });
        if (habilitarActivarOrden === '1') {
            $("#btnActivarOrden").removeAttr("disabled");
        } else {
            $("#btnActivarOrden").attr("disabled", "disabled");
        }
    }
</script>