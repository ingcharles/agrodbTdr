<?php
    session_start();

    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorCatalogos.php';

    $usuario = $_SESSION['usuario'];

    $conexion = new Conexion();
    $cc = new ControladorCatalogos();
    
    $identificador = $_SESSION['usuario'];
    $documentos = $cc->obtenerTiposDeDocumento($conexion, $identificador);

?>

<header>
    <h1>Nuevo Documento</h1>
</header>

<div id="estado"></div>

<form
    id="nuevoDocumento"
    data-rutaAplicacion="registroOperador"
    data-opcion="guardarDocumento"
    data-destino="detalleItem">

    <fieldset>
        <legend>Detalles del documento</legend>

        <div data-linea="1">
            <label for="tipo">Tipo de documento</label>
            <select id="tipo" name="tipo" style="width: 460px;">
                <?php
                    while ($documento = pg_fetch_assoc($documentos)) {
                        echo '<option value="' . $documento['id_documento_anexo'] . '" data-rutaModelo="' . $documento['ruta_documento_modelo'] . '">' . $documento['nombre_documento'] . '</option>';
                    }
                ?>
            </select>
            <button type="button" class="previsualizar">Previsualizar modelo de ejemplo</button>
        </div>

        <div data-linea="2">
            <label for="descripcion">Descripción</label>
            <input name="descripcion" id="descripcion" type="text"/>
        </div>
        <hr/>
        <div data-linea="3">
            <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0"/>
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*"/>
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/registroOperador/anexos">Subir archivo</button>
        </div>
    </fieldset>

    <button type="submit" class="guardar" disabled="disabled">Guardar formulario</button>
</form>
<script type="text/javascript">

    $("document").ready(function () {
        distribuirLineas();
    });

    $("#nuevoDocumento").submit(function (event) {
        event.preventDefault();
        abrir($(this), event, false);
    });

    $("button.previsualizar").click(function(){
        var modelo = $(this).parent().find("select option:selected").attr("data-rutaModelo");
        if (modelo != ""){
            window.open(modelo);
        } else {
            alert ("Este documento no tiene modelos de ejemplo.");
        }

    });

    /********************************************************/


    $("button.subirArchivo").click(function (event) {
		
		nombre_archivo = "<?php echo $usuario . (md5(time())); ?>";
		
        var boton = $(this);
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
    });

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
