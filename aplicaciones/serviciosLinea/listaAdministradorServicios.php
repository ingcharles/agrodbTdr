<header>
	<h1>Administración de Servicios</h1>
</header>
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php';
$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cu = new ControladorUsuarios();
$arrayPerfil=array('PFL_GESTI_FINAN','PFL_TRANSPORTE');
foreach ($arrayPerfil as $codificacionPerfil ){
	$qVerificarUsuario=$cu->verificarUsuario($conexion, $_SESSION['usuario'],$codificacionPerfil);
	if(pg_num_rows($qVerificarUsuario)>0){
		switch ($codificacionPerfil){
			case 'PFL_GESTI_FINAN':
				?>
				<h2>Dirección Administrativa Financiera - Gestión Financiera</h2>
				<article id="0" class="item" data-rutaAplicacion="serviciosLinea"
					data-opcion="listaGFConfirmacionPagos" draggable="true"
					ondragstart="drag(event)" data-destino="listadoItems">
					<span>Confirmación de Pagos</span>
				</article>
				<?php 
			break;
			case 'PFL_TRANSPORTE':
				?>
				<h2>Dirección Administrativa Financiera - Transportes</h2>
				<article id="1" class="item" data-rutaAplicacion="serviciosLinea"
					data-opcion="listaGARecorridosInstitucionales" draggable="true"
					ondragstart="drag(event)" data-destino="listadoItems">
					<span>Rutas de Transporte Institucional</span>
				</article>
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