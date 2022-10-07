<header>
    <h1><?php echo $this->accion; ?></h1>
</header><form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
               data-opcion = 'ReactivosLaboratorios/guardar' data-destino ="detalleItem"			 
               data-accionEnExito ="NADA" method="post">	

    <fieldset><legend>Informaci&oacute;n Adicional Reactivo</legend>			
        <div data-linea ="1">
            <label for="nombre"> Nombre </label> 
            <input type ="text" id="nombre"
                   name ="nombre" value="<?php echo $this->modeloReactivosLaboratorios->getNombre(); ?>"
                   placeholder ="Nombre del reactivo"
                   required maxlength="256" />
        </div >
        <div data-linea ="2">
            <label for="cantidad_minima"> Cantidad M&iacute;nima </label> 
            <input type ="number" id="cantidad_minima"
                   name ="cantidad_minima" value="<?php echo $this->modeloReactivosLaboratorios->getCantidadMinima(); ?>"
                   placeholder ="Cantidad m&iacute;nima que debe tener un laboratorio"
                   step="0.000001" value="0.00" placeholder="0.00" min="0.000001" lang="en" />
        </div>

        <div data-linea ="2">
            <label for="cantidad_maxima"> Cantidad M&aacute;xima </label> 
            <input type ="number" id="cantidad_maxima"
                   name ="cantidad_maxima" value="<?php echo $this->modeloReactivosLaboratorios->getCantidadMaxima(); ?>"
                   placeholder ="Cantidad m&aacute; que un laboratorio debe disponer en stock"
                   step="0.000001" value="0.00" placeholder="0.00" min="0.000001" lang="en" />
        </div>

        <div data-linea="3">
            <label for="estado_reactivo"> Estado Reactivo </label> 
            <select id="estado_reactivo" name="estado_reactivo" >
                <option value="">Seleccionar....</option>
                <?php echo $this->comboCatalogo($this->codEstadoReactivo, $this->modeloReactivosLaboratorios->getEstadoReactivo()); ?>
            </select>
        </div>

        <div data-linea ="3">
            <label for="pureza"> Pureza (valor en %) </label> 
            <input type ="number" id="pureza"
                   name ="pureza" value="<?php echo $this->modeloReactivosLaboratorios->getPureza(); ?>"
                   placeholder ="Pureza"
                   maxlength="3" max="100" />
        </div>

        <div data-linea ="4">
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

        <div data-linea ="5">
            <label for="almacenamiento"> Almacenamiento </label> 
            <input type ="text" id="almacenamiento"
                   name ="almacenamiento" value="<?php echo $this->modeloReactivosLaboratorios->getAlmacenamiento(); ?>"
                   placeholder ="Almacenamiento"
                   maxlength="128" />
        </div>

        <div data-linea ="5">
            <label for="ubicacion"> Ubicaci&oacute;n </label> 
            <input type ="text" id="ubicacion"
                   name ="ubicacion" value="<?php echo $this->modeloReactivosLaboratorios->getUbicacion(); ?>"
                   placeholder ="Ubicaci&o&oacute;n del reactivo  dentro del laboratorio"
                   maxlength="256" />
        </div>

        <label for="observaciones"> Observaciones </label> 
        <div data-linea ="6">
            <textarea id="observaciones" name ="observaciones" 
                      value="<?php echo $this->modeloReactivosLaboratorios->getObservaciones(); ?>"
                      placeholder ="Observaciones"> </textarea>
        </div >

        <div data-linea ="7">
            <input type="hidden" id="id_reactivo_laboratorio" name="id_reactivo_laboratorio" 
                   value="<?php echo $this->modeloReactivosLaboratorios->getIdReactivoLaboratorio(); ?>"/>
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
    });
    $("#formulario").submit(function (event) {
        event.preventDefault();
        var error = false;
        if (!error) {
           var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
            //Traemos la lista solo si guardo correctamenre
            if(respuesta.estado == 'exito')
            {
            fn_filtrar();
            }

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>
