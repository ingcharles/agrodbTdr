<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'ReactivosLaboratorios/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			

    <fieldset>
        <legend>Reactivo del Laboratorio</legend>

        <?php
        $atributo = ($this->modeloReactivosLaboratorios->getIdReactivoLaboratorio()) ? 'disabled' : 'required';
        echo $this->laboratoriosProvinciaUsuario($this->modeloReactivosLaboratorios->getIdLaboratoriosProvincia(), $atributo);
        ?>

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
                   maxlength="6" name ="cantidad" step="0.000001" value="0.00" placeholder="0.00" min="0.000001" lang="en"/>
        </div>

        <div data-linea ="2">
            <label for="cantidad_maxima"> Cantidad M&aacute;xima </label> 
            <input type ="number" id="cantidad_maxima"
                   name ="cantidad_maxima" value="<?php echo $this->modeloReactivosLaboratorios->getCantidadMaxima(); ?>"
                   placeholder ="Cantidad m&aacute; que un laboratorio debe disponer en stock"
                   maxlength="6" name ="cantidad" step="0.000001" value="0.00" placeholder="0.00" min="0.000001" lang="en"/>
        </div>

        <div data-linea="3">
            <label for="estado_reactivo"> Estado Reactivo </label> 
            <select id="estado_reactivo" name="estado_reactivo">
                <option value="">Seleccionar....</option>
                <?php echo $this->comboCatalogo($this->codEstadoReactivo, $this->modeloReactivosLaboratorios->getEstadoReactivo()); ?>
            </select>
        </div>

        <div data-linea ="3">
            <label for="unidad_medida"> Unidad de medida </label> 
            <input type ="text" id="unidad_medida"
                   name ="unidad_medida" value="<?php echo $this->modeloReactivosLaboratorios->getUnidadMedida(); ?>"
                   placeholder ="Unidad de medida"
                   required maxlength="16" style="text-transform:uppercase;"/>
        </div>

        <div data-linea ="4">
            <label for="ubicacion"> Ubicaci&oacute;n </label> 
            <input type ="text" id="ubicacion"
                   name ="ubicacion" value="<?php echo $this->modeloReactivosLaboratorios->getUbicacion(); ?>"
                   placeholder ="Ubicaci&oacute;n"
                   maxlength="256" />
        </div>

        <label for="observaciones"> Observaciones </label> 
        <div data-linea ="5">
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
