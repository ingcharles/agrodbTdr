<header>
	<h1>Consulta de Servicios</h1>
</header>
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cu = new ControladorUsuarios();

$arrayPerfil=array('PFL_SANID_ANIMA','PFL_SANID_VEGET');
foreach ($arrayPerfil as $codificacionPerfil ){
	$qVerificarUsuario=$cu->verificarUsuario($conexion, $_SESSION['usuario'],$codificacionPerfil);
	if(pg_num_rows($qVerificarUsuario)>0){
		switch ($codificacionPerfil){
			case 'PFL_SANID_ANIMA':
				?>
				<h2>Sanidad Animal - Control Zoosanitario</h2>
				<article id="0" class="item" data-rutaAplicacion="serviciosInformacionTecnica"
					data-opcion="listaEnfermedadExoticaSA" draggable="true"
					ondragstart="drag(event)" data-destino="listadoItems">
					<span>Información de Enfermedades Exóticas</span>
					<aside></aside>
				</article>
				
				<?php 
			break;
			case 'PFL_SANID_VEGET':
				?>
				<!-- <h2>Sanidad Vegetal - Control Fitosanitario</h2>
				<article id="0" class="item" data-rutaAplicacion="serviciosInformacionTecnica"
					data-opcion="listaControFitosanitarioSV" draggable="true"
					ondragstart="drag(event)" data-destino="listadoItems">
					<span>Información de Control Fitosanitario</span>
				</article> -->
				<?php 
			break;
		}
	}
}
?>
<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
	});
</script>