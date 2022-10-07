<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorServiciosLinea.php';

	$conexion = new Conexion();
	$csl = new ControladorServiciosLinea();

	$fechaC =  htmlspecialchars ($_POST['elementos'],ENT_NOQUOTES,'UTF-8');
	$localizacion =  htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
	$fecha=str_replace("-", "/",$fechaC);
	$elementos = explode(",", $fecha);
	if($fecha!='' && count($elementos)<2){

?>
<header>
	<h1>Eliminación de Pago</h1>
</header>
	<form id='notificarConfirmacionPago' data-rutaAplicacion='serviciosLinea' data-opcion='eliminarGFConfirmacionPagosC' data-destino="detalleItem">
		<div id="estado"></div>
		<input type="hidden" name="fecha" value="<?php echo $fechaC;?>" />	
		<input type="hidden" name="localizacion" value="<?php echo $localizacion;?>" />	
		<input type="hidden" id="identificadorResponsable" name="identificadorResponsable" value="<?php echo $_SESSION['usuario'];?>" />
		<table>
			<tr style=" border: 0px;">
				<td>
					<fieldset>
						<legend>Está seguro de eliminar</legend>
						<div data-linea="1">
							<label>Localización:</label>
								<?php echo $localizacion;?>
							</div>
							<div data-linea="2">
								<label>Fecha:</label>
								<?php echo $fecha;?>
							</div>
					</fieldset>
				</td>
			</tr>
		</table>
		<p style="text-align: center">
				<button type="submit">Eliminar</button>
		</p>	
	</form>		
	
<script type="text/javascript">
								
	$(document).ready(function(){
		distribuirLineas();
	});
	
	$("#notificarConfirmacionPago").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));	
		if( $('#estado').html()=='Los datos han sido eliminados satisfactoriamente')
			$('#_actualizarSubListadoItems').click();
	});
</script>
<?php 
}
?>