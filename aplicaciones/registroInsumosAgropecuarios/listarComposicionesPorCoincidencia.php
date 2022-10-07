<hr />
<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $busqueda = trim(htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8'));

    $patron = array('/(á|é|í|ó|ú|ñ|Á|É|Í|Ó|Ú|Ñ)/', '/( )+/');
    $reemplazo = array('_', '|');
    $cadenaDeBusqueda = preg_replace($patron, $reemplazo, $busqueda);

    $conexion = new Conexion();
    $cr = new ControladorRIA();

    $composiciones = $cr->listarComposicionesPorCoincidencia($conexion, $cadenaDeBusqueda);

    if (pg_num_rows($composiciones) == 0) {
        echo '<div>No se encontraron coincidencias de "' . $busqueda . '" en la base de datos.</div>';
    } else {
        while ($composicion = pg_fetch_assoc($composiciones)) {

            echo '<div><input id="co_' . $composicion['id_composicion'] . '" name="id_composicion" type="radio" value="' . $composicion['id_composicion'] . '" data-area="' . substr($composicion['id_area'], -1)  . '" ><label for="co_' . $composicion['id_composicion'] . '">' . $composicion['nombre'] . '</label></div>';
        }
    }
?>

