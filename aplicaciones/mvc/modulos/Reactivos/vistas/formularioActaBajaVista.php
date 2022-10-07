			
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			 
      data-opcion = 'Actabaja/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			
        <legend>Datos del Acta</legend>			

        <div data-linea ="5">
            <label for="nombre_acta"> Nombre </label> 
            <input type ="text" id="nombre_acta"
                   name ="nombre_acta" value="<?php echo $this->modeloActabaja->getNombreActa(); ?>"
                   placeholder ="Nombre"
                   required maxlength="256" />
        </div>

        <label for="contenido"> Contenido del acta </label> 
        <div data-linea ="6">
            <textarea id="contenido" name ="contenido" required
                      placeholder ="Contenido del acta"><?php echo $this->modeloActabaja->getContenido(); ?></textarea>
        </div>

        <div data-linea ="7">
            <label for="fecha_registro"> Fecha de registro </label> 
            <input type ="text" id="fecha_registro"
                   name ="fecha_registro" value="<?php echo $this->modeloActabaja->getFechaRegistro(); ?>"
                   placeholder ="Fecha de registro"
                   required  maxlength="512" readonly style="background: transparent; border: 0"/>
        </div>

        <div data-linea ="8">
            <label for="responsable_crea"> Responsable de creaci&oacute;n </label> 
            <input type ="text" id="responsable_crea"
                   name ="responsable_crea" value="<?php echo $this->modeloActabaja->getResponsableCrea(); ?>"
                   placeholder ="Responsable de creacion del acta"
                   required  maxlength="512" readonly style="background: transparent; border: 0"/>
        </div>

        <div data-linea ="9">
            <label for="responsable_aprueba"> Responsable de aprobar </label> 
            <input type ="text" id="responsable_aprueba"
                   name ="responsable_aprueba" value="<?php echo $this->modeloActabaja->getResponsableAprueba(); ?>"
                   placeholder ="Responsable de aprobar la acta"
                   maxlength="512" readonly style="background: transparent; border: 0"/>
        </div>

        <div data-linea = "10" >
            <label for="estado_acta"> Estado de la acta </label> 
            <input type ="text" value="<?php echo $this->modeloActabaja->getEstadoActa(); ?>"
                   placeholder ="Estado del acta"
                   required  maxlength="512" readonly style="background: transparent; border: 0"/>
        </div>
    </fieldset>

    <fieldset>			
        <legend>Acci&oacute;n sobre el acta</legend>			
        <div data-linea ="1">
            <label for="estado_acta"> Seleccione estado </label> 
            <select id="estado_acta" name="estado_acta" required>
                <option value="">Seleccionar....</option>
                <option value="APROBADA">Aprobar</option>
                <option value="NO APROBADA">No Aprobar</option>
                <option value="RETORNADA">Retornar</option>
            </select>
        </div>

        <label for="observacion"> Observaci&oacute;n </label> 
        <div data-linea ="2">
            <textarea id="observacion" name ="observacion" 
                      placeholder ="Observaci&oacute;n"><?php echo $this->modeloActabaja->getObservacion(); ?></textarea>
        </div>
        <div data-linea ="11">
            <input type ="hidden" id="id_acta_baja"
                   name ="id_acta_baja" value="<?php echo $this->modeloActabaja->getIdActaBaja(); ?>"/>
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form>
<script type ="text/javascript">
    $(document).ready(function () {
        construirValidador();
        distribuirLineas();
    });
    $("#formulario").submit(function (event) {
        event.preventDefault();
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
    });
</script>
