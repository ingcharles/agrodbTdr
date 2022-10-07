<link rel='stylesheet'
      href='<?php echo URL_RESOURCE ?>estilos/bootstrap.min.css'>		
<header>
    <h1><?php echo $this->accion; ?></h1>
</header>			
<form id = 'formulario' data-rutaAplicacion = '<?php echo URL_MVC_FOLDER; ?>Reactivos'			
      data-opcion = 'documentosreactivos/guardar' data-destino ="detalleItem"			
      data-accionEnExito ="NADA" method="post">			
    <fieldset>			
        <legend>Subir un archivo PDF con el certificado del reactivo </legend>	
        <div id="mensajeCertificado"></div>
        <div data-linea="1">
            <div id="mensajeExcel" ></div>
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo"
                   accept="application/excel" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <label for="descripcion"> Descripción </label> 

            <textarea rows="4" cols="50" id="descripcion"
                      name ="descripcion"
                      placeholder="Descripción del certificado"></textarea>

        </div>
        <br>
        <button type="button" id="btnSubirArchivo" onclick="fn_subirArchivo()" class="subirArchivo adjunto"
                data-rutaCarga="<?php echo URL_DIR_REA_CERTIFICADOS ?>">Subir archivo</button>
        <br>
        
        <?php echo $this->certificadoActual ?>

    </fieldset >
</form>
<script type ="text/javascript">
    $(document).ready(function () {
        <?php echo $this->codigoJS; ?>
    });
    function fn_subirArchivo() {

        nombre_archivo = "<?php echo 'certificado' . (md5(time())); ?>";

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
            $.post("<?php echo URL ?>Reactivos/DocumentosReactivos/guardar",
                    {
                        nombre_archivo: nombre_archivo + "." + extension[extension.length - 1],
                        id_reactivo_bodega:<?php echo $this->idReactivoBodega; ?>,
                        descripcion: $("#descripcion").val(),
                        estado: 'ACTIVO'
                    },
            function (data) {
                mostrarMensaje("Archivo subido", "EXITO");
            });
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
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


    $("#eliminar").click(function () {
         $.post("<?php echo URL ?>Reactivos/DocumentosReactivos/borrar",
                    {
                        idDocumentosReactivos:<?php echo $this->idDocumentosReactivos; ?>,
                    },
            function (data) {
                $("#tablaCertificados").empty();
            });

    });

</script>
