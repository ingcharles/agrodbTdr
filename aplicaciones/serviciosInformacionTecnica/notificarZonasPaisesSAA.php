<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorServiciosInformacionTecnica.php';

	$conexion = new Conexion();;
	$csit = new ControladorServiciosInformacionTecnica();
	
	$idZona=$_POST['elementos'];
	$res = $csit->abrirZona($conexion, $idZona);
	$zona = pg_fetch_assoc($res);
	$usuarioResponsable=$_SESSION['usuario'];
?>
<header>
	<h1>Eliminación Zonas/Paises</h1>
</header>
	<form id='notificarZonas' data-rutaAplicacion='serviciosInformacionTecnica' data-opcion='eliminarZonasSAA' data-destino="detalleItem" >
		<div id="estado"></div>
		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />
		<input type="hidden" name="idZona" value="<?php echo $idZona;?>" />	
		<p style="text-align: center">
			<button type="submit" >Eliminar</button>
		</p>	
		
					<fieldset>
						<legend>Información de Zonas</legend>
						<div data-linea="1">
							<label>Nombre:</label>
								<?php echo $zona['nombre'];?>
							</div>
					</fieldset>
			
	</form>		
<script type="text/javascript">
								
	$(document).ready(function(){
		distribuirLineas();
	});
	
	$("#notificarZonas").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));	
		if( $('#estado').html()=='Los datos han sido eliminados satisfactoriamente')
			$('#_actualizarSubListadoItems').click();
	});
</script>