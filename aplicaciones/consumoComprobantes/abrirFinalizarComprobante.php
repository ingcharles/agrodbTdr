<?php
	session_start();
	require_once '../../clases/Conexion.php';
	//require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorFinanciero.php';
	require_once '../../clases/ControladorUsuarios.php';

	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	//$ca = new ControladorAreas();
	$cca = new ControladorCatalogos();
	$cf = new ControladorFinanciero();
	$cu = new ControladorUsuarios();
	
	$cabeceraOrdenPago = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $_POST['id']));
	$detalleOrdenPago = $cc->abrirDetallePago($conexion, $_POST['id']);
	$detalleFormaOrdenPago = $cc->abrirLiquidarOrdenPago($conexion, $_POST['id']);	
	$banco = $cca->listarEntidadesBancariasAgrocalidad($conexion);
	$identificador = $_SESSION['usuario'];
	
	//$saldoDisponible = pg_fetch_assoc($cf->obtenerMaxSaldo($conexion,$cabeceraOrdenPago['identificador_operador']));
	
	//$distritos = $cc -> listarDistritos($conexion);
	//$identificadorUsuarioRegistro = $_SESSION['usuario'];
	
	$numeroEstablecimientos = $cc -> listarEstablecimientos($conexion);
	
	while($fila = pg_fetch_assoc($numeroEstablecimientos)){
		$establecimiento[]= array(numEstablecimiento=>$fila['numero_establecimiento'], ruc=>$fila['ruc']);
	}
	
	$provincia = $_SESSION['nombreProvincia']; 
	$idArea = $_SESSION['idArea'];
	
	$detalleUsoFactura = pg_fetch_assoc($cf->abrirUsoFactura($conexion,$_POST['id']));
	
	$datosUsuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion,$detalleUsoFactura['identificador']));
	
		
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Finalizar Comprobante</h1>
	</header>
	<div id="estado"></div>

<fieldset id="ordenPago">
	<legend>Orden de pago <?php echo ' N°: '.$cabeceraOrdenPago['numero_solicitud'];?> </legend>
	<?php
		echo'<div data-linea="1">
			<label>Localización: </label> '.$cabeceraOrdenPago['localizacion'].'
		</div>
		<div data-linea="2">
			<label>Razón social: </label> '.$cabeceraOrdenPago['razon_social'].'
		</div>
		<div data-linea="2">
			<label>Identificación: </label> '.$cabeceraOrdenPago['identificador_operador'].'
		</div>
		<div data-linea="3">
			<label>Dirección: </label> '.$cabeceraOrdenPago['direccion'].'
		</div>
		<div data-linea="4">
			<label>Fecha de orden: </label> '.$cabeceraOrdenPago['fecha_orden_pago'].'
		</div>
		<div data-linea="5">
			<label>Observación: </label> '.$cabeceraOrdenPago['observacion'].'
		</div>
			<div data-linea="6">
			<label>Total a pagar $  </label> '.$cabeceraOrdenPago['total_pagar'].'
			<input type="hidden" id="totalPagarOrden" name="totalPagarOrden" value="'.$cabeceraOrdenPago['total_pagar'].'" >
		</div>
		<div data-linea="7">
			<label>Orden de pago: </label><a download="'.$cabeceraOrdenPago['numero_solicitud'].'.pdf" href="'.$cabeceraOrdenPago['orden_pago'].'" data-id="'.$cabeceraOrdenPago['id_pago'].'" target= "_blank">Descargar orden de pago</a>
		</div>';
		
		if($cabeceraOrdenPago['comprobante_factura'] != ''){
			echo '<div data-linea="8"><label>Comprobante de factura: </label><a href="'.$cabeceraOrdenPago['comprobante_factura'].'" target= "_blank">Descargar comprobante factura</a></div>';
		}
	?>
</fieldset>

<fieldset>
	<legend>Detalle</legend>
	   <table id="tablaOrdenPago">
		<thead>
			<tr>
			<th>Concepto</th>
			<th>Cantidad</th>
			<th>Valor Unitario</th>
			<th>Descuento</th>
			<th>IVA</th>
			<th>Total</th>								
			</tr>
		</thead> 
		<?php
			while($fila = pg_fetch_assoc($detalleOrdenPago)){ 
				echo'<tr>
						<td>'.$fila['concepto_orden'].' <b>UNIDAD MEDIDA:</b> '.$fila['unidad_medida'].'</td>	
						<td>'.$fila['cantidad'].'</td>	
						<td>'.$fila['precio_unitario']*'1'.'</td>
						<td>'.$fila['descuento'].'</td>
						<td>'.$fila['iva'].'</td>
						<td>'.$fila['total'].'</td>
					</tr>';
			}
		 ?> 
		</table>	
</fieldset>

<fieldset id="datosDetalleDeposito">
	<legend>Datos deposito</legend>
			 <table id="tablaOrdenPago">
				<thead>
					<tr>   		
					<th>Forma de pago</th>
					<th>Entidad recaudadora</th>
					<th>Número de transacción</th>
					<th>Monto</th>
					</tr>
				</thead> 
				<?php
					while($fila = pg_fetch_assoc($detalleFormaOrdenPago)){ 
					echo'<tr>
						<td>'.($fila['institucion_bancaria']!= ''? 'Institución Bancaria':'Pago efectivo').'</td>	
						<td>'.($fila['institucion_bancaria']!= ''? $fila['institucion_bancaria']:'').'</td>
						<td>'.($fila['transaccion']!= ''? $fila['transaccion']:'').'</td>
						<td>'.($fila['valor_deposito']).'</td>
						</tr>';
					}
		 		?> 
			</table>
</fieldset>

<fieldset id="datosAutorizacion">
	<legend>Datos de autorización SRI</legend>
		<div data-linea="1"><label>Estado: </label><?php echo $cabeceraOrdenPago['estado_sri'];?></div>
		<div data-linea="2"><label>Número autorización: </label><?php echo ($cabeceraOrdenPago['numero_autorizacion']=='' ? 'Número no disponible': $cabeceraOrdenPago['numero_autorizacion']);?></div>
		<div data-linea="3"><label>Fecha: </label><?php echo ($cabeceraOrdenPago['fecha_autorizacion']==''?'Fecha no disponible':date('j/n/Y (G:i:s)',strtotime($cabeceraOrdenPago['fecha_autorizacion'])));?></div>
		<div data-linea="4"><label>Observación: </label><?php echo ($cabeceraOrdenPago['observacion_sri'] == 'null' ? 'Observación no disponible': $cabeceraOrdenPago['observacion_sri']);?></div>
		<?php 
			if($cabeceraOrdenPago['numero_autorizacion'] != ''){
				echo '<div data-linea="5"><label>Factura: </label><a href="'.$cabeceraOrdenPago['factura'].'" target= "_blank">Descargar RIDE(SRI)</a></div>';
			}
		?>
</fieldset>

<fieldset id="datosUtilizacion">
	<legend>Datos uso de factura</legend>
		<div data-linea="1"><label>Identificación: </label><?php echo $detalleUsoFactura['identificador'];?></div>
		<div data-linea="2"><label>Nombres: </label><?php echo $datosUsuario['nombre'] .' '.$datosUsuario['apellido'];?></div>
		<div data-linea="3"><label>Provincia: </label><?php echo $detalleUsoFactura['provincia'];?></div>
		<div data-linea="4"><label>Fecha: </label><?php echo date('j/n/Y (G:i:s)',strtotime($detalleUsoFactura['fecha']));?></div>
		<div data-linea="5"><label>Observación: </label><?php echo $detalleUsoFactura['observacion'];?></div>
</fieldset>


<form id='finalizarComprobante' data-rutaAplicacion='consumoComprobantes' data-opcion='guardarDetalleUsoFactura' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<fieldset>
		<legend>Datos de Servicio</legend>
		
		<input name="idPago" value="<?php echo $_POST['id'];?>" type="hidden"/>
		<input name="identificador" value="<?php echo $identificador;?>" type="hidden"/>
		<input name="provincia" value="<?php echo $provincia;?>" type="hidden"/>
		<input name="idArea" value="<?php echo $idArea;?>" type="hidden"/>
		
		<div data-linea="1">
			<label>Observación</label>
				<input	type="text" id="observacion" name="observacion" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$"/>
		</div>
	</fieldset>
	
	<button type="submit" class="guardar">Guardar</button>		
</form>	

</body>

<script type="text/javascript">

var estado = <?php  echo json_encode($cabeceraOrdenPago['estado']);?>;

	$(document).ready(function(){
		distribuirLineas();
	});

	if ("<?php echo $cabeceraOrdenPago['utilizado'];?>" != "t"){
		$("#datosUtilizacion").hide();
		
	}else{
		$(".alertaCombo").removeClass("alertaCombo");
		$("#estado").html("La Factura ya fue utilizada.").addClass("alerta");
		$("#finalizarComprobante").hide();
	}


	$("#finalizarComprobante").submit(function(event){
		event.preventDefault();		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if($('#observacion').val() == ''){
			error = true;
			$("#observacion").addClass("alertaCombo");
			$("#estado").html("Por favor ingresar una observación.").addClass("alerta");
		}

		if (!error){
			$("#estado").html("").removeClass('alerta');
			ejecutarJson($(this));	
		}
	});
	

</script>
<style type="text/css">
#tablaOrdenPago td, #tablaOrdenPago th,#tablaDetalle td, #tablaDetalle th
{
	font-size:1em;
	border:1px solid rgba(0,0,0,.1);
	padding:3px 7px 2px 7px;
}
</style>
</html>
