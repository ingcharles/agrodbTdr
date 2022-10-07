<header>
	<h1>Administración de Catálogos</h1>
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
				<h2>Control Zoosanitario - Información de Enfermedades Exóticas</h2>
				<article id="0" class="item" data-rutaAplicacion="serviciosInformacionTecnica"
					data-opcion="listaRequerimientoSAA" draggable="true"
					ondragstart="drag(event)" data-destino="listadoItems">
					<span>Administración de Requerimientos de Revisión - Ingreso</span>
					<aside></aside>
				</article>
				<article id="1" class="item" data-rutaAplicacion="serviciosInformacionTecnica"
					data-opcion="listaEnfermedadAnimalSAA" draggable="true"
					ondragstart="drag(event)" data-destino="listadoItems">
					<span>Administración de Enfermedades Animales</span>
					<aside></aside>
				</article>
				<article id="2" class="item" data-rutaAplicacion="serviciosInformacionTecnica"
					data-opcion="listaZonasPaisesSAA" draggable="true"
					ondragstart="drag(event)" data-destino="listadoItems">
					<span>Administración de Zonas y Paises</span>
					<aside></aside>
				</article>
				<?php 
			break;
			case 'PFL_SANID_VEGET':
				/*?>
				<h2>Control Fitosanitario - Información de Control Fitosanitario</h2>
				<article id="0" class="item" data-rutaAplicacion="serviciosInformacionTecnica"
					data-opcion="listaRevisionIngresoSVA" draggable="true"
					ondragstart="drag(event)" data-destino="listadoItems">
					<span>Administración de Requerimientos de Revisión - Ingreso</span>
				</article>
				<?php */
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