<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorServiciosInformacionTecnica.php';

	$conexion = new Conexion();;
	$csit = new ControladorServiciosInformacionTecnica();
	
	$idRequerimiento=$_POST['elementos'];
	$res = $csit->abrirRequerimientoRevisionIngreso($conexion, $idRequerimiento);
	$requerimiento = pg_fetch_assoc($res);
	$usuarioResponsable=$_SESSION['usuario'];
?>
<header>
	<h1>Eliminación Tipo de Requerimiento</h1>
</header>
	<form id='notificarRequerimiento' data-rutaAplicacion='serviciosInformacionTecnica' data-opcion='eliminarRequerimientoSAA' data-destino="detalleItem" >
		<div id="estado"></div>
		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />
		<input type="hidden" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" />	
		<p style="text-align: center">
			<button type="submit" id="EliminarRequerimiento" name="EliminarRequerimiento" >Eliminar</button>
		</p>	
		<table>
			<tr style=" border: 0px;">
				<td>
					<fieldset>
						<legend>Información Tipo de requerimiento</legend>
						<div data-linea="1">
							<label>Nombre:</label>
								<?php echo $requerimiento['nombre'];?>
							</div>
							<div data-linea="2">
								<label>Descripción:</label>
								<?php echo $requerimiento['descripcion'];?>
							</div>
					</fieldset>
				</td>
			</tr>
		</table>
	</form>		
<script type="text/javascript">
								
	$(document).ready(function(){
		distribuirLineas();
	});
	
	$("#notificarRequerimiento").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));	
		if( $('#estado').html()=='Los datos han sido eliminados satisfactoriamente')
			$('#_actualizarSubListadoItems').click();
	});
</script>