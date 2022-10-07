<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'ReactivosSolucion/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			

    <fieldset>
        <legend>Reactivos de la soluci&oacute;n</legend>

        <div data-linea ="1">
            <label for="nombre"> Soluci&oacute;n </label> 
            <input type ="text" value="<?php echo $this->modeloReactivosLaboratorios->getNombre(); ?>"
                   readonly style="background: transparent; border: 0"/>
        </div>

        <div data-linea ="1">
            <label for="nombre"> Volumen final de la soluci&oacute;n </label> 
            <input type ="text" value="<?php echo $this->modeloReactivosLaboratorios->getVolumenFinal() . " " . $this->modeloReactivosLaboratorios->getUnidadMedida(); ?>"
                   readonly style="background: transparent; border: 0"/>
        </div>

        <div data-linea ="2">
            <label for="especificacion"> Reactivo </label> 
            <select id="id_reactivo_laboratorio" name="id_reactivo_laboratorio" 
                    class="easyui-combotree" style="width: 250px">
            </select>
        </div>

        <div data-linea ="3">
            <label for="cantidad_requerida"> Cantidad requerida (<span id="unidad">Unidad</span>) </label>
            <input type ="number" id="cantidad_requerida" required
                   name ="cantidad_requerida" value="<?php echo $this->modeloReactivosSolucion->getCantidadRequerida(); ?>"
                   placeholder ="cantidad_requerida" step="0.000001" value="0.00" placeholder="0.00" min="0.000001" lang="en"/>
        </div>

        <div data-linea = "3" >
            <label for="estado_registro"> Estado del registro </label> 
            <select id="estado_registro" name="estado_registro" required>
                <?php echo $this->combo2Estados($this->modeloReactivosSolucion->getEstadoRegistro()); ?>
            </select>
        </div>

        <label for="observacion"> Observaci&oacute;n </label> 
        <div data-linea ="4">
            <textarea id="observacion" name ="observacion" 
                      placeholder ="Observación sobre el reactivo usado en la solución" maxlength="512"><?php echo $this->modeloReactivosSolucion->getObservacion(); ?></textarea>
        </div>

        <div data-linea ="8">
            <input type="hidden" id="id_solucion" name="id_solucion" 
                   value="<?php echo $this->modeloReactivosLaboratorios->getIdReactivoLaboratorio(); ?>"/>
            <input type="hidden" id="id_reactivo_solucion" name="id_reactivo_solucion" 
                   value="<?php echo $this->modeloReactivosSolucion->getIdReactivoSolucion(); ?>"/>
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form>

<fieldset>	
    <legend>Lista de reactivos para la soluci&oacute;n</legend>
    <i class="fas fa-info-circle"></i><span> Dar doble clic para editar.</span>
    <div id="paginacion" class="normal"></div>
    <table width="100%">
        <thead><tr>
                <th>#</th>
                <th title="Reactivo del laboratorio">Reactivo Laboratorio</th>
                <th title="Unidad de medida">Unidad</th>
                <th title="Cantidad que se usa en la soluci&oacute;n">Cantidad Requerida</th>
                <th title="Estado del registro, si es INACTIVO no se descuenta al realizar el an&aacute;lisis">Estado</th>
                <th title="Obseraci&oacute;n sobre el reactivo usado">Observaci&oacute;n</th>
            </tr></thead>
        <tbody>   
            <?php echo $this->listaReactivosSolucion; ?>
        </tbody>
    </table>
</fieldset>


<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
    });

    $('#id_reactivo_laboratorio').combobox({
        data: <?php echo $this->listaReactivosLaboratorios; ?>,
        valueField: 'id',
        textField: 'text',
        onClick: function (node) {
            fn_obtenerDatosReactivoLaboratorio(node.id);
        }
    });

    $('#id_reactivo_laboratorio').combobox('setValue', '<?php echo $this->modeloReactivosSolucion->getIdReactivoLaboratorio(); ?>');

    //Para obtener los datos del reactivo laboratorio
    function fn_obtenerDatosReactivoLaboratorio(idReactivo) {
        if (idReactivo !== undefined) {
            $.post("<?php echo URL ?>Reactivos/ReactivosSolucion/obtenerDatosReactivoLaboratorio/" + idReactivo, function (data) {
                $("#unidad").html(data.unidad);
                distribuirLineas();
            }, 'json');
        }
    }

    $("#formulario").submit(function (event) {
        event.preventDefault();
        if ($('#id_reactivo_laboratorio').combobox('getValue') === "") {
            mostrarMensaje("Seleccione el reactivo.", "FALLO");
        } else {
            var error = false;
            if (!error) {
                var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
                //Traemos la lista solo si guardo correctamenre
                if (respuesta.estado == 'exito')
                {
                    fn_abrirVistaEditar(respuesta.mensaje);
                    fn_filtrar();
                }
            } else {
                $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
            }
        }
    });
    
    ///Funcion para abrir la vista
    function fn_abrirVistaEditar(respuesta) {
        var elementoDestino = "#detalleItem";
        var url = "<?php echo URL ?>Reactivos/ReactivosSolucion";
        var data = {
            id_reactivo_laboratorio: $("#id_solucion").val()
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
                mostrarMensaje(respuesta,'EXITO');
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
