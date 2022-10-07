			
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
                  data-opcion = 'archivoinformeanalisis/guardar' data-destino ="detalleItem"			 
                  data-accionEnExito ="NADA" method="post">			<fieldset>			
        <legend>ArchivoInformeAnalisis</legend>			
        <div data-linea ="1">
            <label for="id_archivo_informe_analisis"> Clave primaria </label> 
            <input type ="text" id="id_archivo_informe_analisis"
                   name ="id_archivo_informe_analisis" value="<?php echo $this->modeloArchivoInformeAnalisis->getIdArchivoInformeAnalisis(); ?>"
                   placeholder ="Clave primaria"
                   required  maxlength="512" />
        </div >

        <div data-linea ="2">
            <label for="id_recepcion_muestras"> Clave primaria tabla recepción de muestras </label> 
            <input type ="text" id="id_recepcion_muestras"
                   name ="id_recepcion_muestras" value="<?php echo $this->modeloArchivoInformeAnalisis->getIdRecepcionMuestras(); ?>"
                   placeholder ="Clave primaria tabla de recepción de muestras"
                   required  maxlength="512" />
        </div >

        <div data-linea ="3">
            <label for="id_informe"> Id de la tabla informe </label> 
            <input type ="text" id="id_informe"
                   name ="id_informe" value="<?php echo $this->modeloArchivoInformeAnalisis->getIdInforme(); ?>"
                   placeholder ="Id de la tabla informe"
                   required  maxlength="512" />
        </div >

        <div data-linea ="7">
            <label for="nombre_informe"> Este nombre es generado de forma automática por el sistema concatenando nombre cliente orden de trabajo + código formato del informe </label> 
            <input type ="text" id="nombre_informe"
                   name ="nombre_informe" value="<?php echo $this->modeloArchivoInformeAnalisis->getNombreInforme(); ?>"
                   placeholder ="Este nombre es generado de forma automática por el sistema concatenando nombre cliente orden de trabajo + código formato del informe"
                   required  maxlength="512" />
        </div >

        <div data-linea = "1" >
            <label for="firmado"> Dato booleano que identifica si el archivo ha sido firmado o no </label>
            <select
                id = "firmado" name = "firmado" required>
                <option value = ""> Seleccionar....</option >
                <?php
                //echo $this->combocombofirmado($this->modeloArchivoInformeAnalisis->getFirmado());
                ?>
            </select>
        </div >

        <div data-linea ="14">
            <label for="alcance"> id_nforme principal </label> 
            <input type ="text" id="alcance"
                   name ="alcance" value="<?php echo $this->modeloArchivoInformeAnalisis->getAlcance(); ?>"
                   placeholder ="id_nforme principal"
                   required  maxlength="512" />
        </div >

        <div data-linea ="15">
            <label for="sustituto"> id del informe principal </label> 
            <input type ="text" id="sustituto"
                   name ="sustituto" value="<?php echo $this->modeloArchivoInformeAnalisis->getSustituto(); ?>"
                   placeholder ="id del informe principal"
                   required  maxlength="512" />
        </div >

        <div data-linea ="15">
            <label for="sustituto"> id del informe principal </label> 
            <input type ="text" id="sustituto"
                   name ="sustituto" value="<?php echo $this->modeloArchivoInformeAnalisis->getSustituto(); ?>"
                   placeholder ="id del informe principal"
                   required  maxlength="512" />
        </div >

        <div data-linea="16">
            <?php echo $this->crearRadioEstadoAI($this->modeloArchivoInformeAnalisis->getEstado()); ?>
        </div>

        <div data-linea="16">
            <?php echo $this->crearRadioEstadoAI($this->modeloArchivoInformeAnalisis->getEstado()); ?>
        </div>

        <div data-linea ="22">
            <label for="orden"> Orden del nodo en arbol </label> 
            <input type ="text" id="orden"
                   name ="orden" value="<?php echo $this->modeloArchivoInformeAnalisis->getOrden(); ?>"
                   placeholder ="Orden del nodo en arbol"
                   required  maxlength="512" />
        </div >

        <div data-linea ="23">			<input type ="hidden" name="id_informe_analisis" id="id_informe_analisis" value ="<?php echo $this->modeloArchivoInformeAnalisis->getIdInformeAnalisis() ?>">			<input type ="hidden" name="id_firma_electronica" id="id_firma_electronica" value ="<?php echo $this->modeloArchivoInformeAnalisis->getIdFirmaElectronica() ?>">			<input type ="hidden" name="fk_id_archivo_informe_analisis" id="fk_id_archivo_informe_analisis" value ="<?php echo $this->modeloArchivoInformeAnalisis->getFkIdArchivoInformeAnalisis() ?>">			<input type ="hidden" name="fecha_creacion" id="fecha_creacion" value ="<?php echo $this->modeloArchivoInformeAnalisis->getFechaCreacion() ?>">			<input type ="hidden" name="fecha_envio" id="fecha_envio" value ="<?php echo $this->modeloArchivoInformeAnalisis->getFechaEnvio() ?>">			<input type ="hidden" name="fecha_aprobado" id="fecha_aprobado" value ="<?php echo $this->modeloArchivoInformeAnalisis->getFechaAprobado() ?>">			<input type ="hidden" name="fecha_firma" id="fecha_firma" value ="<?php echo $this->modeloArchivoInformeAnalisis->getFechaFirma() ?>">			<input type ="hidden" name="descargado" id="descargado" value ="<?php echo $this->modeloArchivoInformeAnalisis->getDescargado() ?>">			<input type ="hidden" name="ruta_archivo" id="ruta_archivo" value ="<?php echo $this->modeloArchivoInformeAnalisis->getRutaArchivo() ?>">			<input type ="hidden" name="nivel" id="nivel" value ="<?php echo $this->modeloArchivoInformeAnalisis->getNivel() ?>">			<input type ="hidden" name="rama" id="rama" value ="<?php echo $this->modeloArchivoInformeAnalisis->getRama() ?>">
            <button type ="submit" class="guardar"> Guardar</button>
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
            //fn_filtrar();
            }

        } else {
            $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
        }
    });
</script>
