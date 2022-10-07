<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorServiciosInformacionTecnica.php';

	$conexion = new Conexion();;
	$csit = new ControladorServiciosInformacionTecnica();
	
	$idEnfermedad=$_POST['elementos'];
	$res = $csit->abrirEnfermedadAnimal($conexion, $idEnfermedad);
	$enfermedad = pg_fetch_assoc($res);
	$usuarioResponsable=$_SESSION['usuario'];
?>
<header>
	<h1>Eliminación Enfermedad Animal</h1>
</header>
	<form id='notificarEnfermedad' data-rutaAplicacion='serviciosInformacionTecnica' data-opcion='eliminarEnfermedadAnimalSAA' data-destino="detalleItem" >
		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />
		<div id="estado"></div>
		<input type="hidden" name="idEnfermedad" value="<?php echo $idEnfermedad;?>" />	
		<p style="text-align: center">
			<button type="submit" >Eliminar</button>
		</p>	
		<table>
			<tr style=" border: 0px;">
				<td>
					<fieldset>
						<legend>Información Enfermedad Animal</legend>
						<div data-linea="1">
							<label>Nombre:</label>
								<?php echo $enfermedad['nombre'];?>
							</div>
							<div data-linea="2">
								<label>Descripción:</label>
								<?php echo $enfermedad['descripcion'];?>
							</div>
							<div data-linea="3">
								<label>Observaciones:</label>
								<?php echo $enfermedad['observacion'];?>
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
	
	$("#notificarEnfermedad").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));	
		if( $('#estado').html()=='Los datos han sido eliminados satisfactoriamente')
			$('#_actualizarSubListadoItems').click();
	});
</script>