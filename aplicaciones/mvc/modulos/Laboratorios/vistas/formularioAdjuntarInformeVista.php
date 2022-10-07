<header>
    <h1><?php echo $this->accion; ?></h1>
</header>		
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Laboratorios'			 
      data-opcion = 'ArchivoInformeAnalisis/guardar' data-destino ="detalleItem"			 
      data-accionEnExito ="NADA" method="post"   >			
    <fieldset>			
        <legend>Subir archivos adjuntos al informe</legend>			


        <div id="mensajeAdjunto" class="alerta"></div>
        <div data-linea="1">
           
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo"
                   accept="application/pdf" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <label for="nombre_informe"> Descripción </label> 
            <input type ="text" name="nombre_informe" id="nombre_informe" value ="" />
            <button type="button" id="btnSubirArchivo" onclick="fn_subirArchivo()" class="subirArchivo adjunto"
                    data-rutaCarga="<?php echo URL_DIR_LAB_AD.$this->rutaAdjunto; ?>">Subir archivo</button>


        </div>

    </fieldset >

   

</form>
<script type ="text/javascript">
    $(document).ready(function () {
<?php echo $this->codigoJS ?>
        distribuirLineas();
    });


    function fn_subirArchivo() {

        if ($("#nombre_informe").val() != "") {

            nombre_archivo = "<?php echo 'adjunto' . (md5(time())); ?>";

            var boton = $("#btnSubirArchivo");
            var archivo = boton.parent().find(".archivo");
            var rutaArchivo = boton.parent().find(".rutaArchivo");
            var extension = archivo.val().split('.');
            var estado = boton.parent().find(".estadoCarga");

            if (extension[extension.length - 1].toUpperCase() == 'PDF') {

                subirArchivo(
                        archivo
                        , nombre_archivo
                        , boton.attr("data-rutaCarga")
                        , rutaArchivo
                        , new carga(estado, archivo, boton)
                        );
                //Enviamos el nombre del archivo para procesar el registro en la base de datos
                $.post("<?php echo URL ?>Laboratorios/ArchivoInformeAnalisis/guardarAdjunto",
                        {
                            nombre_archivo: nombre_archivo,
                            id_archivo_informe_analisis:<?php echo $this->modeloArchivoInformeAnalisis->getIdArchivoInformeAnalisis() ?>,
                            nombre_informe: $("#nombre_informe").val(),
                            ruta_archivo: "<?php echo $this->rutaAdjunto; ?>"
                        },
                function (data) {
                    fn_filtrar();
                });
            } else {
                estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
                archivo.val("");
            }
        } else {

            $("#mensajeAdjunto").html("Debe ingresar una descripción del archivo");
        }
    }
    function carga(estado, archivo, boton) {
        this.esperar = function (msg) {
            estado.html("Cargando el archivo...");
            archivo.addClass("amarillo");
        };

        this.exito = function (msg) {
            estado.html("El archivo ha sido cargado.");
            archivo.removeClass("amarillo");
            archivo.addClass("verde");
            boton.attr("disabled", "disabled");
            $("#nuevoDocumento :submit").removeAttr("disabled");
        };

        this.error = function (msg) {
            estado.html(msg);
            archivo.removeClass("amarillo");
            archivo.addClass("rojo");
        };
    }
    
    
</script>
