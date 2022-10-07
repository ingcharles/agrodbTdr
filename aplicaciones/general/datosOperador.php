<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEmpleadoEmpresa.php';
	$conexion = new Conexion();
	$cee = new ControladorEmpleadoEmpresa();
?>	
	
	<header>
		<h1>Mis datos</h1>
	</header>
	
	<article id="0" class="item" data-rutaAplicacion="registroOperador"	data-opcion="datosOperador" data-destino="detalleItem" draggable="true" ondragstart="drag(event)" >
		<div></div>
		<span>Actualizar mis datos</span>
		<aside></aside>
	</article>
	
	<article id="1" class="item" data-rutaAplicacion="registroOperador"	data-opcion="datosUsuario" draggable="true" ondragstart="drag(event)" data-destino="detalleItem">
		<div></div>
		<span>Cambio de clave y nombre de usuario</span>
		<aside></aside>
	</article>
	
	<!-- article id="2" class="item" data-rutaAplicacion="financiero"	data-opcion="descuentoCupos" draggable="true" ondragstart="drag(event)" data-destino="detalleItem">
		<div></div>
		<span>Gestión de descuento de cupos</span>
		<aside></aside>
	</article-->
<?php 
$qEmpresa=$cee->obtenerOperadorEmpresa($conexion, $_SESSION['usuario'],"('OPISA','OPTSA','FERSA','FEASA','FAEAI','OPMSA')" );
if (pg_num_rows($qEmpresa)>0){
	echo '<article id="3" class="item" data-rutaAplicacion="empleadoEmpresa" data-opcion="listaEmpleadoEmpresa" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Administrar empleados</span>
		<aside></aside>
	</article>';
	}
?>
	
	<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("programas");
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una ficha para editarla.</div>');
	});
	</script>