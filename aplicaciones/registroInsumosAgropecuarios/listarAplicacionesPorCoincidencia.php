<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $inputs = explode('|', htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8'));
    $busqueda = trim($inputs[0]);
    $area = trim($inputs[1] == 'P')?'IAP':((trim($inputs[1] == 'V')?'IAV':null));

    $patron = array('/(á|é|í|ó|ú|ñ|Á|É|Í|Ó|Ú|Ñ)/', '/( )+/');
    $reemplazo = array('_', '|');

    $cadenaDeBusqueda = preg_replace($patron, $reemplazo, $busqueda);

    $conexion = new Conexion();
    $cr = new ControladorRIA();

    $aplicaciones = $cr->listarAplicacionesPorCoincidencia($conexion, $cadenaDeBusqueda, $area);

    $salida = "";
    $coincidenciaExacta = false;

    while ($aplicacion = pg_fetch_assoc($aplicaciones)) {
        if (strtoupper($cadenaDeBusqueda) == strtoupper(preg_replace($patron, $reemplazo, $aplicacion['aplicacion_producto']))) {
            $coincidenciaExacta = true;
        }
        $salida .= '<div><input id="ap_' . $aplicacion['id_aplicacion_producto'] . '" name="idAplicacion" data-resetear="no" type="radio" value="' . $aplicacion['id_aplicacion_producto'] . '" ><label for="ap_' . $aplicacion['id_aplicacion_producto'] . '">' . $aplicacion['aplicacion_producto'] . '</label></div>';
    }
    if (!$coincidenciaExacta) {
        $salida = '<div class="resaltar_nuevo"><input id="ap_nuevo" name="idAplicacion" data-resetear="no" type="radio" value="nuevo_' . $busqueda . '" ><label for="ap_nuevo">' . $busqueda . '</label></div>' . $salida;
    }
    echo '<input type="hidden" name="areaAplicacion" value="'.strtoupper($area).'" data-resetear="no" />'.$salida;
?>

