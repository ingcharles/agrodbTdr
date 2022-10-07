<header>
    <h1><?php echo $this->accion; ?></h1>
</header>	
<script src="<?php echo URL_RESOURCE ?>js/tinymce/tinymce.min.js"></script>
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'ActividadUso/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">	

    <fieldset>	
        <legend>Datos del procedimiento</legend>	
        <button id="sbm" style="display: none"/>
        <!-- Combo/cuadro de texto del(os) laboratorio(s) del usuario -->
        <?php echo $this->laboratoriosProvinciaUsuario($this->modeloActividadUso->getIdLaboratoriosProvincia(), 'required'); ?>

        <div data-linea="2">
            <label for="id_servicio"> Servicio</label> 
            <select class="easyui-combotree" name="id_servicio" id="id_servicio">
            </select>
        </div>

        <div data-linea ="3">
            <label for="id_reactivo_laboratorio"> Nombre Reactivo</label>
            <select id="id_reactivo_laboratorio" name="id_reactivo_laboratorio" class="easyui-combotree">
            </select>
        </div>

        <div data-linea ="4">
            <label for="tipo_procedimiento"> Tipo Procedimiento </label> 
            <select id="tipo_procedimiento" name="tipo_procedimiento" required>
                <option value="">Seleccionar...</option>
                <?php
                echo $this->tipoProcedimiento($this->modeloActividadUso->getTipoProcedimiento());
                ?>
            </select>
        </div>

        <div data-linea ="4">
            <label for="cantidad"> Cantidad (<span id="unidad">Unidad</span>) </label> 
            <input type ="number" id="cantidad"
                   name ="cantidad" value="<?php echo $this->modeloActividadUso->getCantidad(); ?>"
                   step="0.000001" placeholder="0.00" min="0.000001" lang="en"
                   required  />
        </div>

        <div data-linea="5">
            <label for="estado"> Estado </label> 
            <select name="estado">
                <?php echo $this->combo2Estados($this->modeloActividadUso->getEstado()); ?>
            </select>
        </div>

        <div data-linea="5"></div>

        <label for="observaciones">  Observaci&oacute;n </label> 
        <div data-linea ="6">
            <textarea id="observaciones" name ="observaciones" 
                      placeholder ="Observaciones"><?php echo $this->modeloActividadUso->getObservaciones(); ?></textarea>
        </div>

        <div data-linea ="7">
            <input type="hidden" id="id_actividad_uso" name="id_actividad_uso" 
                   value="<?php echo $this->modeloActividadUso->getIdActividadUso(); ?>"/>
            <input type="hidden" id="id_laboratorio" name="id_laboratorio" 
                   value="<?php echo $this->modeloActividadUso->getIdLaboratorio(); ?>"/>
            <input type="hidden" id="fecha" name="fecha" 
                   value="<?php echo $this->modeloActividadUso->getFecha(); ?>"/>
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form>

<fieldset>	
    <legend>Lista de reactivos para el procedimiento de an&aacute;lisis</legend>
    <i class="fas fa-info-circle"></i><span> Dar doble clic para editar.</span>
    <div id="paginacion" class="normal"></div>
    <table width="100%" id="tablaItems">
        <thead><tr>
                <th>#</th>
                <th title="Reactivo o Soluci&o&oacute;n del laboratorio">Reactivo del Laboratorio</th>
                <th title="Cantidad requerida">Cantidad</th>
                <th title="Unidad de medida">Unidad de medida</th>
                <th title="Estado del registro, si es INACTIVO no se descuenta al realizar el an&aacute;lisis">Estado</th>
                <th>Tipo procedimiento</th>
                <th title="Obseraci&oacute;n sobre el reactivo usado">Observaci&oacute;n</th>
            </tr></thead>
        <tbody>   
            <?php echo $this->listaActividadUso; ?>
        </tbody>
    </table>
</fieldset>


<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
    });

    fn_cargarServicios();

    $("#formulario #id_laboratorios_provincia").change(function () {
        fn_cargarServicios();
    });

    //para cargar los servicios al seleccionar el laboratorio
    function fn_cargarServicios() {
        var idLaboratorio = 0;
        if ($("#formulario #id_laboratorios_provincia").is("select")) {
            idLaboratorio = $("#formulario #id_laboratorios_provincia").find(':selected').attr('data-id');
        } else {
            idLaboratorio = $("#formulario #id_laboratorios_provincia").attr('data-id');
        }
        var idLaboratoriosProvincia = $("#formulario #id_laboratorios_provincia").val();
        if (idLaboratorio !== "" & idLaboratorio !== undefined) {
            $("#id_laboratorio").val(idLaboratorio);
            //Cargamos el combotree segun el laboratorio seleccionado
            $.post("<?php echo URL ?>Reactivos/ActividadUso/buscarServiciosPadre/" + idLaboratorio, function (data) {
                $('#id_servicio').combotree({
                    data: data
                });
                $('#id_servicio').combotree('setValue', '<?php echo $this->modeloActividadUso->getIdServicio(); ?>');
            }, 'json');

            //Cargamos los reactivos del laboratorio
            $.post("<?php echo URL ?>Reactivos/ActividadUso/buscarReactivosLaboratorio/" + idLaboratoriosProvincia, function (data) {
                $('#id_reactivo_laboratorio').combobox({
                    data: data,
                    valueField: 'id',
                    textField: 'text',
                    onClick: function (node) {
                        fn_obtenerDatosReactivoBodega(node.id);
                    }
                });
                $('#id_reactivo_laboratorio').combobox('setValue', '<?php echo $this->modeloActividadUso->getIdReactivoLaboratorio(); ?>');
            }, 'json');
        }
    }

    //Para obtener la unidad de medida del reactivo bodega
    function fn_obtenerDatosReactivoBodega(idReactivo) {
        if (idReactivo !== undefined) {
            $.post("<?php echo URL ?>Reactivos/ActividadUso/obtenerDatosReactivosLaboratorio/" + idReactivo, function (data) {
                $("#unidad").html(data.unidad);
                distribuirLineas();
            }, 'json');
        }
    }

    $("#formulario").submit(function (event) {
        event.preventDefault();
        if ($('#id_servicio').combotree('getValue') === "") {
            mostrarMensaje("Seleccione el reactivo.", "FALLO");
        } else if ($('#id_reactivo_laboratorio').combobox('getValue') === "") {
            mostrarMensaje("Seleccione el reactivo.", "FALLO");
        } else {
            var error = false;
            if (!error) {
                var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
                //Traemos la lista solo si guardo correctamenre
                if (respuesta.estado == 'exito')
                {
                    fn_filtrar();
                    fn_abrirVistaEditar();
                }

            } else {
                $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
            }
        }
    });

    ///Funcion para abrir la vista
    function fn_abrirVistaEditar() {
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Reactivos/ActividadUso/listarProcedimientoAnalisis";
        var data = {
            id: $('#id_servicio').combotree('getValue') + "-" + $("#formulario #id_laboratorios_provincia").val() + "-" + $("#formulario #id_laboratorios_provincia").find(':selected').attr('data-id')
        };
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
                $(elementoDestino).html(html);
                redimensionarVentanaTrabajo();
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
