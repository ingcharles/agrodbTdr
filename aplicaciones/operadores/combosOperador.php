<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$tipoIdentificacion = htmlspecialchars($_POST['tipo'], ENT_NOQUOTES, 'UTF-8');
$textoDeBusqueda = htmlspecialchars($_POST['textoDeBusqueda'], ENT_NOQUOTES, 'UTF-8');
$provincia = htmlspecialchars($_POST['provincia'], ENT_NOQUOTES, 'UTF-8');
$area = htmlspecialchars($_POST['area'], ENT_NOQUOTES, 'UTF-8');
$opcion = htmlspecialchars($_POST['tipoProcesoCombo'], ENT_NOQUOTES, 'UTF-8');

switch ($opcion) {
    case 'tipoOperacion':
        $operacionPermitidas = $cc->obtenerTiposOperacionPorIdAreaTematica($conexion, $area);
        echo '<td class="obligatorio">Operación: </td><td>
				<select id="tipoOperacion" name="tipoOperacion" required>
				<option value="Todas">Cualquier operacion</option>';
        while ($operaciones = pg_fetch_assoc($operacionPermitidas)){
            echo '<option value="' . $operaciones['id_tipo_operacion'] . '">' . $operaciones['nombre'] . '</option>';
        }
        echo '</select></td>';
        break;    
    default:
        echo 'Tipo desconocido';
}

?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

</script>
