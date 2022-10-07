<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorGestionCalidad.php';
//require_once '../../clases/ControladorAuditoria.php';

$conexion = new Conexion();
$cgc = new ControladorGestionCalidad();
//$ca = new ControladorAuditoria();

//Validar sesion
//$conexion->verificarSesion();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
<?php
	$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$tipo = htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
	$hallazgo = htmlspecialchars ($_POST['hallazgo'],ENT_NOQUOTES,'UTF-8');
	$norma = htmlspecialchars ($_POST['norma'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8');
	$tipoAuditor = htmlspecialchars ($_POST['tipoAuditor'],ENT_NOQUOTES,'UTF-8');
	
	$auditor = ($tipoAuditor=='Interno')? htmlspecialchars ($_POST['auditorInterno'],ENT_NOQUOTES,'UTF-8'): htmlspecialchars ($_POST['auditorExterno'],ENT_NOQUOTES,'UTF-8');
	
	$cgc->guardarHallazgo($conexion, $area, $tipo, $hallazgo, $norma, $fecha, $auditor, $_SESSION['usuario']);
	
	echo 'El registro ha sido guardado.';
?>
</body>
<script type="text/javascript">
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
</script>
</html>