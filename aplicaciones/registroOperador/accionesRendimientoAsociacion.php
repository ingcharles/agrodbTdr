<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();


$usuario = $_SESSION['usuario'];
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$numero = htmlspecialchars ($_POST['numero'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {
	
	case 'miembro':
	
		$qMiembros = $cro->obtenerDatosOperadoresOMiembrosAsociacionXIdentificador($conexion, $_POST['numero']);
		
		if(pg_num_rows($qMiembros)==0){

			echo '<label>Nombres:</label>
				<input type="text" id="nombreMiembro" name="nombreMiembro" />
				<label style="margin-top:3px">Apellidos:</label>
				<input style="margin-top:3px" type="text" id="apellidoMiembro" name="apellidoMiembro"/>';
		}else{
			
			$miembro = pg_fetch_assoc($qMiembros);
			
			echo '<label>Nombres:</label>
				<input type="text" id="nombreMiembro" name="nombreMiembro" value="'.$miembro['nombre_miembro_asociacion'].'" readonly/>
				<label style="margin-top:3px">Apellidos:</label>
				<input style="margin-top:3px" type="text" id="apellidoMiembro" name="apellidoMiembro" value="'.$miembro['apellido_miembro_asociacion'].'" readonly />';	
		}
	
	break;
		
}

?>

<script type="text/javascript"> 

	$(document).ready(function(){		
		distribuirLineas(); 
	});

</script>	