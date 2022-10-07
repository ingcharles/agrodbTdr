<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorServiciosInformacionTecnica.php';

	$conexion = new Conexion();;
	$csit = new ControladorServiciosInformacionTecnica();
	
	$idEnfermedad=$_POST['elementos'];
	$res = $csit->abrirEnfermedadExotica($conexion, $idEnfermedad);
	$enfermedad = pg_fetch_assoc($res);
	$usuarioResponsable=$_SESSION['usuario'];
?>
<header>
	<h1>Eliminación Enfermedad Exótica</h1>
</header>
	<form id='notificarEnfermedad' data-rutaAplicacion='serviciosInformacionTecnica' data-opcion='eliminarEnfermedadExoticaSAA' data-destino="detalleItem" >
		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />
		<div id="estado"></div>
		<input type="hidden" name="idEnfermedadExotica" value="<?php echo $idEnfermedad;?>" />	
		<p style="text-align: center">
			<button type="submit" >Eliminar</button>
		</p>	
		<table>
			<tr style=" border: 0px;">
				<td>
					<fieldset>
						<legend>Enfermedades Exóticas Reportadas y Vigencia</legend>
						<div data-linea="1">
							<label>Enfermedad:</label>
							<?php echo $enfermedad['nombre_enfermedad'];?>
						</div>
						<div data-linea="2">
							<label>Fecha Inicio:</label>
							<?php echo $enfermedad['inicio_vigencia'];?>
						</div>
						<div data-linea="2">
							<label>Fehca Fin:</label>
							<?php echo $enfermedad['fin_vigencia'];?>
						</div>
						<div data-linea="3">
							<label>Estado:</label>
							<?php echo $enfermedad['estado'];?>
						</div>	
						<div data-linea="4">
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