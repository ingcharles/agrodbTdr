<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>
<header>
    <h1>Aprobaci&oacute;n de Laboratorios</h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Laboratorios'
      data-opcion='Laboratorios/guardar' data-destino="detalleItem"
      data-accionEnExito="NADA" method="post">
    <fieldset>
        <legend>Laboratorios</legend>

        <div data-linea="1">
            <label for="nombre"> Nombre </label> 
            <input type="text" value="<?php echo $this->modeloLaboratorios->getNombre(); ?>"
                   placeholder="Nombre del laboratorio" required maxlength="512" readonly style="background: transparent; border: 0"/>
        </div>
        
        <div data-linea="4">
            <label>Estado</label> 
            <select id="estado_aprobado" name="estado_aprobado" required>
                <option value="POR APROBAR" <?php echo ($this->modeloLaboratorios->getEstadoAprobado() == 'POR APROBAR') ? 'selected' : ''; ?>>POR APROBAR</option>
                <option value="APROBADO" <?php echo ($this->modeloLaboratorios->getEstadoAprobado() == 'APROBADO') ? 'selected' : ''; ?>>APROBADO</option>
            </select>
        </div>

        <label for="nombre"> Observaci&oacute;n </label>
        <div data-linea="2">
            <textarea id="observacion_aprobacion" name="observacion_aprobacion"
                      placeholder="Observación de aprobación"><?php echo $this->modeloLaboratorios->getObservacionAprobacion(); ?></textarea>
        </div>

        <div data-linea="6">
            <input type="hidden" name="id_laboratorio" id="id_laboratorio"
                   value="<?php echo $this->modeloLaboratorios->getIdLaboratorio() ?>" />

            <button type="submit" class="guardar"> Guardar</button>
        </div>

    </fieldset>
</form>

<script type="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
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
