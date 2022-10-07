<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorInscripcionCaravana.php';

$conexion = new Conexion();
$ci = new ControladorInscripcionCaravana();

$inscripcion = $ci->abrirInscripcion($conexion, $_SESSION['usuario']);
$item = pg_fetch_assoc($inscripcion);

$documentos = array(
    array('literal' => 'a', 'descripcion' => 'Solicitud dirigida al Director Ejecutivo de AGROCALIDAD para postulación como proveedor de caravanas de identificación visual/electrónica en bovinos.'),
    array('literal' => 'b', 'descripcion' => 'Copia del registro único de contribuyentes RUC vigente.'),
    array('literal' => 'c', 'descripcion' => 'Copia de cédula a color.'),
    array('literal' => 'd', 'descripcion' => 'Copia notariada de la escritura de constitución de la empresa.(personas jurídicas).'),
    array('literal' => 'e', 'descripcion' => 'Copia del nombramiento del representante legal  de la empresa, vigente.(personas jurídicas).'),
    array('literal' => 'f', 'descripcion' => 'Copia de registro de importador directo de caravanas de identificación visual/electrónica.'),
    array('literal' => 'g', 'descripcion' => 'Al menos dos certificados de experiencia como proveedor de insumos de identificación animal.'),
    array('literal' => 'h', 'descripcion' => 'Ubicación de la oficina (s) y locales que deberá contener: calle, cantón, provincia, número de teléfono, código postal, dirección de correo electrónico.'),
    array('literal' => 'i', 'descripcion' => 'Carta de compromiso con AGROCALDIDAD para la entrega de caravanas al ganadero, en un plazo máximo de 5 días, debidamente impresas con la numeración asignada y registradas en el sistema SITA.'),
    array('literal' => 'j', 'descripcion' => 'Formulario A, firmando por el representante legal, aceptando los requisitos para las caravanas de identificación visual y electrónico.'),
	array('literal' => 'k', 'descripcion' => 'Formulario B, firmado por el representante legal, aceptando las especificaciones técnicas de fabricación de las caravanas de identificación visual.'),
	array('literal' => 'l', 'descripcion' => 'Formulario C, firmado por el representante legal, aceptando las especificaciones técnicas de fabricación de las caravanas de identificación de radio frecuencia.'),
	array('literal' => 'm', 'descripcion' => 'Formulario D, firmado por el representante legal, aceptando las obligaciones de los  proveedores con AGROCALIDAD.')
);

?>

<header>
    <h1>Inscripción</h1>
</header>

<div class="recuadro"><strong>Objetivo:</strong> Registrar y habilitar a personas naturales o jurídicas como  proveedores de caravanas de identificación individual  (visual/electrónica), para que las asociaciones ganaderas o productores ganaderos adquieran éstos dispositivos de identificación autorizados por AGROCALIDAD.
</div>



<hr/>


<fieldset>
	<legend>Formato de documentos adjuntos.</legend>
		<pre><a href="modelos/instructivoProveedores.doc" target="_blank">Instructivo de calificación de proveedores</a></pre>
		<pre><a href="modelos/formularioAproveedores.docx" target="_blank">Formulario A proveedor</a></pre>
		<pre><a href="modelos/formularioBproveedores.docx" target="_blank">Formulario B proveedor</a></pre>
		<pre><a href="modelos/formularioCproveedores.docx" target="_blank">Formulario C proveedor</a></pre>
		<pre><a href="modelos/formularioDproveedores.docx" target="_blank">Formulario D proveedor</a></pre>
		        
</fieldset>

<hr/>

<?php
    if (!pg_num_rows($inscripcion)) {
        echo '<div class="nota">Para aplicar, debe proveer los siguinetes documentos</div>';
    }
?>

<fieldset>
    <legend>Documentos de aplicación</legend>

    <form
        id="guardarDocumentos"
        data-rutaAplicacion="inscripcionCaravanas"
        data-opcion="grabarInscripcion"
        data-destino="detalleItem">
        <table>

            <?php
            if (pg_num_rows($inscripcion)) {
                //Están cargados los documentos

                foreach ($documentos as $documento) {
                    echo '<tr>' .
                        '<td>' . $documento['literal'] . '</td>' .
                        '<td><a href="' . $item[$documento['literal']] . '" target="_blank">' . $documento['descripcion'] . '</a></td>' .
                        '</tr>';
                }
            } else {
                //No ha cargado los documentos
                foreach ($documentos as $documento) {
                    echo '<tr>' .
                        '<td>' . $documento['literal'] . '</td>' .
                        '<td>' .
                        '<div>' . $documento['descripcion'] . '</div>' .
                        '<div>' .
                        '<input type="hidden" class="rutaArchivo" name="' . $documento['literal'] . '" value="0"/>
                            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
                            <div class="estadoCarga">En espera de archivo... (Tamaño máximo ' . ini_get('upload_max_filesize') . 'B)</div>
                            <button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/inscripcionCaravanas/archivos" >Subir archivo</button>
                            ' .
                        '</div>' .
                        '</td>' .
                        '</tr>';
                }
            }
            ?>
        </table>
        <hr/>
        <?php
        if (pg_num_rows($inscripcion)) {
            echo '<div class="recuadro">El estado de su inscripción, realizada el ' .$item['fecha_inscripcion'] . ', es: <strong>' . $item['estado'] . '.</strong></div>
            <div>' . $item['observacion'] . '</div';
        } else {
            echo '<button class="guardar" disabled="disabled">Enviar aplicación</button>';
        }

        ?>

    </form>
</fieldset>

<script type="text/javascript">

    var usuario = <?php echo json_encode($_SESSION['usuario']);?>;

    $('button.subirArchivo').click(function (event) {
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , usuario + "_" + rutaArchivo.attr("name")
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
            if ($("button.subirArchivo[disabled]").length == 10) {
                $("#guardarDocumentos button.guardar").removeAttr("disabled");
            }
        };

        this.error = function (msg) {
            estado.html(msg);
            archivo.removeClass("amarillo");
            archivo.addClass("rojo");
        };
    }

    $("#guardarDocumentos").submit(function (e) {
        abrir($(this), e, false);
    });
</script>


