<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$ca = new ControladorAreas();

$nombreLocalizacion = htmlspecialchars ($_POST['localizacionAsignacion'],ENT_NOQUOTES,'UTF-8');

if(strstr($nombreLocalizacion, 'Coordinación') ){
	$nombreProvincia = strstr($nombreLocalizacion, ' ');
}else {
	$nombreProvincia = 'Gestión Administrativa';
}

$qFuncionario = $ca -> obtenerUsuarioAdministradorXProvincia($conexion, $nombreProvincia);

if(pg_num_rows($qFuncionario) != 0){
	echo '<div data-linea="8">
			<label>Responsable</label>
				'. pg_fetch_result($qFuncionario, 0, 'nombre') .' '. pg_fetch_result($qFuncionario, 0, 'apellido') .'
				<input type="hidden" id="identificadorResponsable" name="identificadorResponsable" value="'. pg_fetch_result($qFuncionario, 0, 'identificador') .'" />
		</div>';
}else{
	echo 'No existe un Administrador de Transportes asignado.
	
	<input type="hidden" id="identificadorResponsable" name="identificadorResponsable" />';
}
?>

<script type="text/javascript">

</script>