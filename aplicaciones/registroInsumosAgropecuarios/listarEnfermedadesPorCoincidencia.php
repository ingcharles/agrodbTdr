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

    $enfermedades = $cr->listarEnfermedadesPorCoincidencia($conexion, $cadenaDeBusqueda);

    if (pg_num_rows($enfermedades) == 0) {
        echo '<div>No se encontraron coincidencias de "' . $busqueda . '" en la base de datos.</div>';
    } else {
        while ($enfermedad = pg_fetch_assoc($enfermedades)) {
            echo '<div><input id="en_' . $enfermedad['id_enfermedad'] . '" name="idEnfermedad" type="radio" value="' . $enfermedad['id_enfermedad'] . '" ><label for="en' . $enfermedad['id_enfermedad'] . '">' . $enfermedad['nombre'] . '</label></div>';
        }
    }
?>

