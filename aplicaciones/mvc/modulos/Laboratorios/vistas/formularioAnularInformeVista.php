<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
                  data-opcion = 'ArchivoInformeAnalisis/guardarAnulado' data-destino ="detalleItem"			 
                  data-accionEnExito ="NADA" method="post">			<fieldset>			
        <legend>Anular informe</legend>			

        <label for="observacion_estado"> Observación </label>
        <div data-linea="1">
            <textarea id="observacion_estado" name="observacion_estado" rows="6"><?php echo $this->modeloArchivoInformeAnalisis->getObservacionEstado(); ?></textarea>
        </div>

        <div data-linea="2">
            <label for="sustituto">Requiere crear un informe sustituto</label>
            <select id="sustituto" name="sustituto">
                <?php echo $this->crearComboSINO() ?>
            </select>
        </div>

        <div data-linea ="23">	
            <input type ="hidden" name="estado_informe" id="estado_informe" value ="ANULADO">
            <input type ="hidden" name="id_archivo_informe_analisis" id="id_archivo_informe_analisis" value ="<?php echo $this->modeloArchivoInformeAnalisis->getIdArchivoInformeAnalisis() ?>">			
            <input type ="hidden" name="nombre_informe" id="nombre_informe" value ="<?php echo $this->modeloArchivoInformeAnalisis->getNombreInforme() ?>">
            <input type ="hidden" name="id_informe_analisis" id="id_informe_analisis" value ="<?php echo $this->modeloArchivoInformeAnalisis->getIdInformeAnalisis() ?>">
            <input type ="hidden" name="fk_id_archivo_informe_analisis" id="fk_id_archivo_informe_analisis" value ="<?php echo $this->modeloArchivoInformeAnalisis->getFkIdArchivoInformeAnalisis() ?>">
            <?php
            if ($this->modeloArchivoInformeAnalisis->getEstadoInforme() != 'ANULADO')
            {
                echo ' <button type ="submit" class="btnenviar">Anular informe</button>';
            }
            ?>

        </div >
    </fieldset >
</form >
<script type ="text/javascript">
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
            if(respuesta.estado == 'exito')
            {
            fn_filtrar();
            }

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>
