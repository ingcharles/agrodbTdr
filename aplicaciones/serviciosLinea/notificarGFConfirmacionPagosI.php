<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorServiciosLinea.php';

	$conexion = new Conexion();;
	$csl = new ControladorServiciosLinea();

	$idConfirmacionPago =  htmlspecialchars ($_POST['elementos'],ENT_NOQUOTES,'UTF-8');	
	$elementos = explode(",", $idConfirmacionPago);
	if($idConfirmacionPago!='' && count($elementos)<2){
	$qDatosConfirmacionPago=$csl->buscarDatosConfirmacionPago($conexion, $idConfirmacionPago);
	$datos=pg_fetch_assoc($qDatosConfirmacionPago);
?>
<header>
	<h1>Eliminación de Pago</h1>
</header>
	<form id='notificarConfirmacionPago' data-rutaAplicacion='serviciosLinea' data-opcion='eliminarGFConfirmacionPagosI' data-destino="detalleItem" >
		<div id="estado"></div>
		<input type="hidden" name="idConfirmacionPago" value="<?php echo $idConfirmacionPago;?>" />	
		<input type="hidden" id="identificadorResponsable" name="identificadorResponsable" value="<?php echo $_SESSION['usuario'];?>" />
		<table>
			<tr style=" border: 0px;">
				<td>
					<fieldset>
						<legend>Está seguro de eliminar</legend>
						<div data-linea="1">
							<label>Localización:</label>
								<?php echo $datos['localizacion'];?>
							</div>
							<div data-linea="2">
								<label>Fecha:</label>
								<?php echo $datos['fecha_documento'];?>
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