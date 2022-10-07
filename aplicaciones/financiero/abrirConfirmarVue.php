<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorFinancieroAutomatico.php';

$conexion = new Conexion();
$cfa = new ControladorFinancieroAutomatico();
$cc = new ControladorCertificados();

$idFinancieroCabecera = $_POST['id'];
$identificador = $_SESSION['usuario'];

$datosCabeceraFinanciero = pg_fetch_assoc($cfa->obtenerCabeceraFinancieroAutomatico($conexion, $idFinancieroCabecera));

$cabeceraOrdenPago = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $datosCabeceraFinanciero['id_orden_pago']));
$detalleOrdenPago = $cc->abrirDetallePago($conexion, $datosCabeceraFinanciero['id_orden_pago']);

?>



<header>
	<h1>Verificar saldo VUE</h1>
</header>

<fieldset id="ordenPago">
	<legend>
		Orden de pago
		<?php echo ' N°: '.$cabeceraOrdenPago['numero_solicitud'];?>
	</legend>
	<?php
	echo'<div data-linea="1">
			<label>Localización: </label> '.$cabeceraOrdenPago['localizacion'].'
		</div>
		<div data-linea="1">
			<label>Identificación: </label> '.$cabeceraOrdenPago['identificador_operador'].'
		</div>
		<div data-linea="2">
			<label>Razón social: </label> '.$cabeceraOrdenPago['razon_social'].'
		</div>		
		<div data-linea="3">
			<label>Dirección: </label> '.$cabeceraOrdenPago['direccion'].'
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
				<th>V Unit.</th>
				<th>Desc.</th>
				<th>Subsidio</th>
				<th>Iva</th>
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
					<td>'.$fila['subsidio']*'1'.'</td>
					<td>'.$fila['iva'].'</td>
					<td>'.$fila['total'].'</td>
				</tr>';
			}
		 ?>
	</table>
</fieldset>

<form id="verificarSaldoVue" data-rutaAplicacion="financiero" data-opcion="verificarSaldoVue" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<input type="hidden" name="idFinancieroCabecera" value="<?php echo $idFinancieroCabecera;?>"/>
	<input type="hidden" name="identificador" value="<?php echo $identificador;?>"/>
	
	<fieldset>
		<legend>Verificación de saldo VUE</legend>
		
		<div data-linea="1">
			<label>Número orden VUE: </label> <?php echo $datosCabeceraFinanciero['id_vue'];?>
		</div>
		
		<div data-linea="2">
			<label>Fecha ingreso saldo anticipado: </label> <?php echo date('Y/m/d h:m',strtotime($datosCabeceraFinanciero['fecha_ingreso_cabcera']))?>
		</div>
		
		<div data-linea="3">
			<label>Valor $: </label> <?php echo $datosCabeceraFinanciero['total_pagar'];?>
		</div>
		
		<button type="submit">Confirmar</button>
	
		<p class="nota">Una vez confiramdo el pago, este sera acreditado como saldo anticipado VUE al usuario.</p>
		
	</fieldset>

</form>

<script>

$(document).ready(function(){
	distribuirLineas();
});

$("#verificarSaldoVue").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this));
});

</script>
</html>
