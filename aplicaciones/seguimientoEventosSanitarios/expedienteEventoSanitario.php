<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$ca = new ControladorAplicaciones();
	$cc = new ControladorCatalogos();
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Seguimiento de Eventos Sanitarios'),0,'id_perfil');
	}
?>
	
<header>
	<h1>Expediente de Evento Sanitario</h1>
	<nav>
		<form id="listaRecertificacionBT" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="expedienteEventoSanitarioDetalle" data-destino="tabla">
		
			<table class="filtro">
				<tr>
					<th>Número Solicitud:</th>
					<td>
						<input type="text" id="bNumSolicitud" name="bNumSolicitud" required="required"/>
					</td>
					
				</tr>
				
				<tr>
					<td colspan="5"><button>Buscar</button></td>
				</tr>
			</table>
		</form>		
	</nav>

</header>

<div id="tabla"></div>
	
<script>
	var usuario = <?php echo json_encode($usuario); ?>;
	
	$("#listaRecertificacionBT").submit(function(e){
		abrir($(this),e,false);
	});
	
	$(document).ready(function(){
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una orden para visualizar.</div>');
	});	
</script>