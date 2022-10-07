<link rel='stylesheet' href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<script src="<?php echo URL_RESOURCE ?>js/bootstrap.min.js" type="text/javascript"></script>
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'ReactivosLaboratorios/guardarSolucion' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			

    <fieldset>
        <legend>Soluciones</legend>

        <!-- Combo/cuadro de texto del(os) laboratorio(s) del usuario -->
        <?php
        $atributo = ($this->modeloReactivosLaboratorios->getIdReactivoLaboratorio()) ? 'disabled' : 'required';
        echo $this->laboratoriosProvinciaPrincipal($this->modeloReactivosLaboratorios->getIdLaboratoriosProvincia(), $atributo);
        ?>

        <div data-linea ="1">
            <label for="nombre"> Nombre </label> 
            <input type ="text" id="nombre"
                   name ="nombre" value="<?php echo $this->modeloReactivosLaboratorios->getNombre(); ?>"
                   placeholder ="Nombre"
                   required maxlength="256" />
        </div>

        <div data-linea ="1">
            <label for="cantidad_minima"> Cantidad M&iacute;nima </label> 
            <input type ="number" id="cantidad_minima"
                   name ="cantidad_minima" value="<?php echo $this->modeloReactivosLaboratorios->getCantidadMinima(); ?>"
                   placeholder ="Canditdad M&iacute;nima" 
                   step="0.000001" value="0.00" placeholder="0.00" min="0.000001" lang="en"/>
        </div>

        <div data-linea ="2">
            <label for="cantidad_maxima"> Cantidad M&aacute;xima </label> 
            <input type ="number" id="cantidad_maxima"
                   name ="cantidad_maxima" value="<?php echo $this->modeloReactivosLaboratorios->getCantidadMaxima(); ?>"
                   placeholder ="Cantidad M&aacute;xima" 
                   step="0.000001" value="0.00" placeholder="0.00" min="0.000001" lang="en"/>
        </div>

        <div data-linea="2">
            <label for="estado_reactivo"> Estado Soluci&oacute;n </label> 
            <select id="estado_reactivo" name="estado_reactivo">
                <option value="">Seleccionar....</option>
                <?php echo $this->comboCatalogo($this->codEstadoReactivo, $this->modeloReactivosLaboratorios->getEstadoReactivo()); ?>
            </select>
        </div>

        <div data-linea ="3">
            <label for="pureza"> Pureza </label> 
            <input type ="text" id="pureza"
                   name ="pureza" value="<?php echo $this->modeloReactivosLaboratorios->getPureza(); ?>"
                   placeholder ="Pureza"
                   maxlength="254"/>
        </div>

        <div data-linea ="3">
            <label for="especificacion"> Especificaci&oacute;n </label> 
            <input type ="text" id="especificacion"
                   name ="especificacion" value="<?php echo $this->modeloReactivosLaboratorios->getEspecificacion(); ?>"
                   placeholder ="Especificaci&oacute;n"
                   maxlength="128" />
        </div>

        <div data-linea ="4">
            <label for="presentacion"> Presentaci&oacute;n </label> 
            <input type ="text" id="presentacion"
                   name ="presentacion" value="<?php echo $this->modeloReactivosLaboratorios->getPresentacion(); ?>"
                   placeholder ="Presentaci&oacute;n"
                   maxlength="128" />
        </div>

        <div data-linea ="4">
            <label for="almacenamiento"> Almacenamiento </label> 
            <input type ="text" id="almacenamiento"
                   name ="almacenamiento" value="<?php echo $this->modeloReactivosLaboratorios->getAlmacenamiento(); ?>"
                   placeholder ="Almacenamiento"
                   maxlength="128" />
        </div>

        <div data-linea ="5">
            <label for="unidad_medida"> Unidad de medida </label> 
            <input type ="text" id="unidad_medida"
                   name ="unidad_medida" value="<?php echo $this->modeloReactivosLaboratorios->getUnidadMedida(); ?>"
                   placeholder ="Unidad de medida"
                   required maxlength="16" style="text-transform:uppercase;"/>
        </div>

        <div data-linea ="5">
            <label for="volumen_final"> Volumen final </label> 
            <input type ="number" id="volumen_final"
                   name ="volumen_final" value="<?php echo $this->modeloReactivosLaboratorios->getVolumenFinal(); ?>"
                   placeholder ="Volumen final"
                   required maxlength="8" step="0.000001" value="0.00" placeholder="0.00" min="0.000001" lang="en"/>
        </div>

        <div data-linea ="6">
            <label for="ubicacion"> Ubicaci&oacute;n </label> 
            <input type ="text" id="ubicacion"
                   name ="ubicacion" value="<?php echo $this->modeloReactivosLaboratorios->getUbicacion(); ?>"
                   placeholder ="Ubicaci&oacute;n del reactivo dentro del laboratorio"
                   maxlength="256" />
        </div>    

        <div data-linea = "6" >
            <label for="estado_registro"> Estado del registro </label> 
            <select id="estado_registro" name="estado_registro" >
                <?php echo $this->combo2Estados($this->modeloReactivosLaboratorios->getEstadoRegistro()); ?>
            </select>
        </div>

        <label for="observaciones"> Observaciones </label> 
        <div data-linea ="7">
            <textarea id="observaciones" name ="observaciones" 
                      placeholder ="Observaciones"> <?php echo $this->modeloReactivosLaboratorios->getObservaciones(); ?></textarea>
        </div>

        <div data-linea ="8">
            <input type="hidden" id="id_reactivo_laboratorio" name="id_reactivo_laboratorio" 
                   value="<?php echo $this->modeloReactivosLaboratorios->getIdReactivoLaboratorio(); ?>"/>
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form>

<fieldset>	
    <legend>Lista de reactivos para la soluci&oacute;n</legend>
    <button type="button" onclick="fn_verReactivos()" class="mas"> Agregar Reactivos</button>
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

    var agregarReactivos = false;

    //Para abrir el formulario de agregar reactivos a la solucion
    function fn_verReactivos() {
        if ($("#id_reactivo_laboratorio").val() === '') {
            mostrarMensaje("Guardar los datos de la solución para agregar los reactivos", "FALLO");
        } else {
            agregarReactivos = true;
            var url = "ReactivosSolucion";
            $("#formulario").attr('data-opcion', url);
            $("#formulario").submit();
        }
    }

    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;
        if (!error) {
            if (agregarReactivos === true) {
                abrir($(this), event, false);
            } else {
                var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
                //Traemos la lista solo si guardo correctamenre
                if (respuesta.estado == 'exito')
                {
                    fn_filtrar();
                }
            }
        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>
