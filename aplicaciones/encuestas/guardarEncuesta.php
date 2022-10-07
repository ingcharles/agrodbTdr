<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEncuestas.php';
require_once '../../clases/ControladorUsuarios.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

	<?php
	$conexion = new Conexion();
	
	$cu =  new ControladorUsuarios();
	
	$res=$cu->obtenerAreaUsuario($conexion, $_SESSION['usuario']);
	
		if(pg_num_rows($cu->buscarPerfilUsuario($conexion, $_SESSION['usuario'], 'Usuario externo')) > 0){
			$area = 'Usuario Externo';
			$idArea ='';
		}else{
			if(pg_num_rows($res) > 0){
				$idArea = pg_fetch_result($res,0,'id_area');
				$area = pg_fetch_result($res,0,'nombre');
			}else{
				$area = 'Usuario Interno';
				$idArea = pg_fetch_result($res,0,'id_area');
			}
		}	
	
		$sentencia = "INSERT INTO g_encuesta.respuestas (id_pregunta,id_opcion,area,id_area) VALUES ";
		foreach ($_POST as $key => $value){
			$idPregunta = explode("resp_",htmlspecialchars($key));
			$idOpcion = htmlspecialchars($value);
			if($idPregunta[0] != 'encuesta')
				$sentencia.= "($idPregunta[1],$idOpcion,'$area','$idArea'),";
		}
		
		
		$ce = new ControladorEncuestas();
	 	$ce->grabarRespuestas($conexion,$sentencia);
		$ce->quitarEncuesta($conexion,$_SESSION['usuario'],$_POST['encuesta'])
	?>
	
	<p>La encuesta ha sido enviada satisfactoriamente.</p>
	<p>¡GRACIAS POR PARTICIPAR!</p>
	
</body>
	<script type="text/javascript">
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
	</script>
</html>
