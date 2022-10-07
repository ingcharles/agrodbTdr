<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();

$localizacion = htmlspecialchars ($_POST['localizacion'],ENT_NOQUOTES,'UTF-8');


		$gasolineras = $cv->listarGasolineras($conexion, $localizacion, 'ABIERTOS');
	
		echo '<select id="gasolinera" name="gasolinera" required>
					<option value="">Seleccione....</option>';
					while ($fila = pg_fetch_assoc($gasolineras)){
						echo '<option value="'.$fila['id_gasolinera'].'">'.$fila['nombre'].'</option>';
					}
		echo '</select>';
?>

<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();	
	});
</script>