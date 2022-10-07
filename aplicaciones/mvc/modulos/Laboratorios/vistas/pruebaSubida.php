
<header>
    <h1>Nuevo Documento</h1>
</header>

<div id="estado"></div>

<form id="nuevoDocumento" data-rutaAplicacion="registroOperador"
      data-opcion="guardarDocumento" data-destino="detalleItem">

    <fieldset>
        <legend>Detalles del documentoxxx</legend>

        <hr />
        <div data-linea="3"><
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo"
                   accept="application/msword | application/pdf | image/*" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" id="btnSubirArchivo" onclick="fn_subirArchivo()" class="subirArchivo adjunto"
                    data-rutaCarga="aplicaciones/registroOperador/anexos">Subir archivo</button>
        </div>
    </fieldset>

    <button type="submit" class="guardar" disabled="disabled">Guardar
        formulario</button>
</form>
<script type="text/javascript">

    $("document").ready(function () {
<?php echo $this->codigoJS; ?>
        distribuirLineas();
    });

    $("#nuevoDocumento").submit(function (event) {
        event.preventDefault();
        abrir($(this), event, false);
    });

    $("button.previsualizar").click(function () {
        var modelo = $(this).parent().find("select option:selected").attr("data-rutaModelo");
        if (modelo !== "") {
            window.open(modelo);
        } else {
            mostrarMensaje("Este documento no tiene modelos de ejemplo.", "FALLO");
        }

    });

    /********************************************************/


    //$("#btnSubirArchivo").click(function (event) {
    function fn_subirArchivo() {

        nombre_archivo = "<?php echo 'prueba' . (md5(time())); ?>";

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
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    }
    ;

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

    /********************************************************/

</script>
