<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEmpleados.php';

$conexion = new Conexion();
$ce = new ControladorEmpleados();
$res = $ce->obtenerFichaEmpleado($conexion, $_POST['id']);
$empleado= pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<link rel='stylesheet' href='/estilos/estiloapp.css' >
</head>
<body>	
		<iframe name="fotografia" class="ventanaEmergenteEmpleado" src="aplicaciones/general/fotoInicial.php?clase=rostro&titulo=Foto&img=<?php echo ($empleado['fotografia']==''?'../../aplicaciones/uath/fotos/foto.png':'../../'.$empleado['fotografia']);?>"></iframe>
					<form id="foto" action="aplicaciones/uath/subirArchivo.php" method="post" enctype="multipart/form-data" target="fotografia">
						<input type="file" name="archivo" id="archivo" accept="image/jpeg"/>
						<input name="clase" value="rostro" type="hidden"/> 
						<input name="opcion" value="Foto" type="hidden"/>
						<input name="usuario" value="<?php echo $_POST['id'];?>" type="hidden"/> 
						<button type="submit" name="boton" value="Foto" class="adjunto" disabled="disabled">Cambiar imagen</button>	
					</form>

</body>
<script type="text/javascript">
$("#foto input").click(function(){
	$("#foto button").removeAttr("disabled");
});


</script>
</html>
