<script src="<?php echo URL_RESOURCE ?>js/tinymce/tinymce.min.js"></script>		
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>		
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
      data-opcion = 'ArchivoInformeAnalisis/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post" lang="" >			
    <fieldset>			
        <legend>Observación general del informe</legend>			

        <label for="observacion_general"> Observación </label>
        <div data-linea="8">
            <textarea id="observacion_general" name="observacion_general">
                <?php echo $this->modeloArchivoInformeAnalisis->getObservacionGeneral(); ?>
            </textarea>
        </div>

        <div data-linea ="23">			
            <input type ="hidden" name="id_archivo_informe_analisis" id="id_archivo_informe_analisis" value ="<?php echo $this->modeloArchivoInformeAnalisis->getIdArchivoInformeAnalisis() ?>">			
            <button type ="submit" class="guardar"> Guardar</button>
        </div>
    </fieldset>
</form>
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        construirValidador();
        distribuirLineas();

        tinymce.init({
            selector: '#observacion_general',
            language: 'es',
            height: 80,
            menubar: false,
            plugins: [
                'advlist lists link image'
            ],
            toolbar: 'insert | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist'
        });
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
