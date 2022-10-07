<?php

    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorInscripcionCaravana.php';

    $conexion = new Conexion();
    $ci = new ControladorInscripcionCaravana();

    $inscripcion = $ci->abrirInscripcion($conexion, htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8'));
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
    <h1>Revisión de inscripción</h1>
</header>

<fieldset>
    <legend>Documentos de aplicación</legend>
    <table>
        <?php

            foreach ($documentos as $documento) {

				$opcion = ($item[$documento['literal']] != '0' ? '<a href="' . $item[$documento['literal']] . '" target="_blank">' . $documento['descripcion'] . '</a>': '<div class="alerta">'.$documento['descripcion'].' Documento no disponible.</div>');

                echo '<tr>' .
                    '<td>' . $documento['literal'] . '</td>' .
                    '<td>'.$opcion.'</td>' .
                    '</tr>';
            }
        ?>
    </table>
</fieldset>

<fieldset>
    <legend>Resultado de la aplicacion</legend>
    <form id="actualizarAplicacion" data-rutaAplicacion="inscripcionCaravanas" data-opcion="actualizarInscripcion" data-destino="detalleItem">
        <input type="hidden" name="idInscripcion" value="<?php echo $item['id_inscripcion'];?>">
        <div data-linea="0">
            <label>Estado Actual</label>
            <span>
                <?php echo $item['estado'] ?>
            </span>
        </div>
        <div data-linea="1">
            <label>Nuevo estado</label>
            <select id="nuevoEstado" name="nuevoEstado">
                <option value="Aceptada" selected="selected">Aceptar</option>
                <option value="Rechazada">Rechazar</option>
            </select>
        </div>
        <hr/>
        <div data-linea="2">
            <label>Observación</label>
            <textarea id="observacion" name="observacion"><?php echo $item['observacion'] ?></textarea>
        </div>
        <div>
     	<?php 
     	
     		if($item['estado'] == 'Ingresada'){
     			echo '<button  class="guardar">Guardar aplicación</button>';
     		}
     		
     	?>
           
        </div>
    </form>
</fieldset>

<script type="text/javascript">

     $("document").ready(function () {
        distribuirLineas();
    });


    $("#actualizarAplicacion").submit(function (e) {
        abrir($(this), e, false);
    });
</script>