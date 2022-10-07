<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorResoluciones.php';
	
	
	$conexion = new Conexion();
	$cr = new ControladorResoluciones();
	
	
	$datos = array('numeroResolucion' => htmlspecialchars ($_POST['numeroResolucion'],ENT_NOQUOTES,'UTF-8'),
			'nombre' => htmlspecialchars ($_POST['nombre'],ENT_QUOTES,'UTF-8'),
			'fecha' =>  htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8'),
			'estado' =>  htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8'),
			'observacion' =>  htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'));
	
	
	$archivoResolucion = $_POST['archivo1'];
	$archivoAnexo = $_POST['archivo2'];
	
	
	$conexion = new Conexion();
	$cr = new ControladorResoluciones();
	
	$q_resolucion = $cr->buscarResolucion($conexion, $datos['numeroResolucion'], $datos['fecha']);
	
	if(pg_fetch_row($q_resolucion) == 0){
		
		$resolucion = $cr -> guardarResolucion($conexion, $datos['numeroResolucion'],$datos['nombre'], $datos['fecha'], $datos['observacion'], $archivoResolucion, $archivoAnexo, $datos['estado']);
		echo '<input type="hidden" id="' . pg_fetch_result($resolucion, 0, 'id_resolucion') . '" data-rutaAplicacion="resoluciones" data-opcion="abrirResolucion" data-destino="detalleItem"/>';
		
	}else {
		
		echo '<p class="alerta">El número de resolución y fecha ingresada ya ha sido ingresado con anterioridad.</p>';
		exit();
	}
	
	
?>

<script type="text/javascript">

	$("document").ready(function(){

		<?php 	if(pg_fetch_row($q_resolucion) == 0){?>
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
			abrir($("#detalleItem input"),null,true);
		<?php }?>
		
		
	});
		
		
</script>


