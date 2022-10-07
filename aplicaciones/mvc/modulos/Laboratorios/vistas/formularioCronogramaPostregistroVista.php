<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
      data-opcion = 'CronogramaPostregistro/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			
        <legend>Cronograma Post-Registro</legend>
        <div data-linea="1">
            <label>Fecha inicio</label>
            <input type ="date" name="fecha_inicio" id="fecha_inicio" value ="<?php echo $this->modeloCronogramaPostregistro->getFechaInicio() ?>">
        </div>

        <div data-linea="1">
            <label>Fecha final</label>
            <input type ="date" name="fecha_fin" id="fecha_fin" value ="<?php echo $this->modeloCronogramaPostregistro->getFechaFin() ?>">
        </div>

        <div data-linea="1">
            <label>Estado</label> 
            <select id="estado_registro" name="estado_registro" >
                <?php echo $this->combo2Estados($this->modeloCronogramaPostregistro->getEstadoRegistro()); ?>
            </select>
        </div>

        <label for="ingrediente_activo"> Ingrediente activo </label> 
        <div data-linea ="8">
            <textarea id="ingrediente_activo" name="ingrediente_activo" 
                      placeholder="Escribir más de un ingrediente activo separados por un ';'. Ej. Ingrediente 1; Ingrediente 2"><?php echo $this->modeloCronogramaPostregistro->getIngredienteActivo(); ?></textarea>
        </div>

        <label for="observacion">Observaci&oacute;n</label> 
        <div data-linea ="9">
            <textarea id="observacion" name="observacion" 
                      placeholder="Escribir una observaci&oacute;n"><?php echo $this->modeloCronogramaPostregistro->getObservacion(); ?></textarea>
        </div>

        <div data-linea ="10">	
            <input type ="hidden" name="id_laboratorio" id="id_cronograma_postregistro" value ="<?php echo $this->laboratorioUsuario() ?>">
            <input type ="hidden" name="id_cronograma_postregistro" id="id_cronograma_postregistro" value ="<?php echo $this->modeloCronogramaPostregistro->getIdCronogramaPostregistro() ?>">
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form >
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        distribuirLineas();
        $("#formulario").submit(function (event) {
            event.preventDefault();
            if (new Date($("#fecha_fin").val()) < new Date($("#fecha_inicio").val())) {
                mostrarMensaje("La fecha final debe ser mayor o igual a la fecha inicial.", "FALLO");
            } else {
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
            }
        });
    });
</script>
