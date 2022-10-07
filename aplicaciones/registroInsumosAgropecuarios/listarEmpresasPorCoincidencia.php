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

    $empresas = $cr->listarEmpresasPorCoincidencia($conexion, $cadenaDeBusqueda);

    if (pg_num_rows($empresas) == 0) {
        echo '<div>No se encontraron coincidencias de "' . $busqueda . '" en la base de datos.</div>';
    } else {
        while ($empresa = pg_fetch_assoc($empresas)) {
            echo '<div><input type="radio" id="em_' . $empresa['identificador'] . '" name="id_empresa" value="' . $empresa['identificador'] . '" /><label for="em_' . $empresa['identificador'] . '">' . $empresa['razon_social'] . ' <strong>(' . $empresa['identificador'] . ')</strong></label></div>';
        }
    }
?>

