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

    $fabricantes = $cr->listarFabricantePorCoincidencia($conexion, $cadenaDeBusqueda);

    $salida = "";
    $coincidenciaExacta = false;
    while ($fabricante = pg_fetch_assoc($fabricantes)) {
        if (strtoupper($cadenaDeBusqueda) == strtoupper(preg_replace($patron, $reemplazo, $fabricante['nombre']))) {
            $coincidenciaExacta = true;
        }
        $salida .= '<div><input id="f_' . $fabricante['id_fabricante'] . '" name="idFabricante" data-resetear="no" type="radio" value="' . $fabricante['id_fabricante'] . '" ><label for="f_' . $fabricante['id_fabricante'] . '">' . $fabricante['nombre'] . '</label></div>';
    }
    if (!$coincidenciaExacta) {
        $salida = '<div class="resaltar_nuevo"><input id="nuevo" name="idFabricante" data-resetear="no" type="radio" value="nuevo_' . $busqueda . '" ><label for="nuevo">' . $busqueda . '</label></div>' . $salida;
    }
    echo $salida;
?>

