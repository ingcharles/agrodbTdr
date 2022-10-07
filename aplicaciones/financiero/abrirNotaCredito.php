<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorCertificados.php';

$conexion = new Conexion();
$cc = new ControladorCertificados();
$ca = new ControladorAreas();

$cabeceraNotaCredito = pg_fetch_assoc($cc->abrirNotaCredito($conexion, $_POST['id']));
$detalleNotaCredito = $cc->abrirDetalleNotaCredito($conexion, $_POST['id']);


//$respuestaAutorizacion = $cc->obtenerAutorizacionSRI('2208201601176810572000120320010000068690000000719');

//echo '<pre>';
//	print_r($respuestaAutorizacion->comprobante);
//echo '</pre>';


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>

	<header>
		<img src='aplicaciones/general/img/encabezado.png'>
		<h1>Nota de Crédito</h1>
	</header>
	<div id="estado"></div>

<fieldset id="ordenPago">

	<legend>Nota de crédito <?php echo ' N°: '.$cabeceraNotaCredito['numero_nota_credito'];?> </legend>
	<?php
		echo'<div data-linea="1">
			<label>Localización: </label> '.$cabeceraNotaCredito['localizacion'].'
		</div>
		<div data-linea="2">
			<label>Razón social: </label> '.$cabeceraNotaCredito['razon_social'].'
		</div>
		<div data-linea="2">
			<label>Identificación: </label> '.$cabeceraNotaCredito['identificador_operador'].'
		</div>
		<div data-linea="3">
			<label>Dirección: </label> '.$cabeceraNotaCredito['direccion'].'
		</div>
		<div data-linea="4">
			<label>Fecha de orden: </label> '.$cabeceraNotaCredito['fecha_nota_credito'].'
		</div>
		<div data-linea="5">
			<label># de factura que se modifica: </label> '.$cabeceraNotaCredito['numero_factura'].'
		</div>
		<div data-linea="6">
			<label>Motivo: </label> '.$cabeceraNotaCredito['motivo'].'
		</div>
			<div data-linea="7">
			<label>Total a pagar $  </label> '.$cabeceraNotaCredito['total_pagar'].'
		</div>
		<div data-linea="8">
			<label>Impresión: </label><a href="'.$cabeceraNotaCredito['comprobante_nota_credito'].'" target= "_blank">Descargar comprobante</a>
		</div>';
			
	?>

</fieldset>
		
<fieldset>
	<legend>Detalle</legend>

	   <table id="tablaNotaCredito">
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
				foreach ($detalleNotaCredito as $detalle) {

					echo "
					<tr>
						<td>".$detalle['concepto']."</td>
						<td>".$detalle['cantidad']."</td>
						<td>".$detalle['precioUnitario']."</td>
						<td>".$detalle['descuento']."</td>
						<td>".$detalle['iva']."</td>
						<td>".$detalle['total']."</td>								
					</tr>";
				}
						
			  ?> 
		</table>	
</fieldset>

<fieldset id="datosAutorizacion">
	<legend>Datos de autorización SRI</legend>
		<div data-linea="1"><label>Estado: </label><?php echo $cabeceraNotaCredito['estado_sri'];?></div>
		<div data-linea="2"><label>Número autorización: </label><?php echo ($cabeceraNotaCredito['numero_autorizacion']=='' ? 'Número no disponible': $cabeceraNotaCredito['numero_autorizacion']);?></div>
		<div data-linea="3"><label>Fecha: </label><?php echo ($cabeceraNotaCredito['fecha_autorizacion']==''?'Fecha no disponible':date('j/n/Y (G:i:s)',strtotime($cabeceraNotaCredito['fecha_autorizacion'])));?></div>
		<div data-linea="4"><label>Observación: </label><?php echo ($cabeceraNotaCredito['observacion'] == 'null' ? 'Observación no disponible': $cabeceraNotaCredito['observacion']);?></div>
		<?php 
			if($cabeceraNotaCredito['numero_autorizacion'] != ''){
				echo '<div data-linea="5"><label>Nota de Crédito: </label><a href="'.$cabeceraNotaCredito['ruta_nota_credito'].'" target= "_blank">Descargar RIDE(SRI)</a></div>';
			}
		?>
				
</fieldset>
		

</body>

<script type="text/javascript">
$(document).ready(function(){
	distribuirLineas();
	
});

$("#fecha_deposito").datepicker({
    changeMonth: true,
    changeYear: true
  });

$("#finalizarOrdenPago").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this));
});

</script>
<style type="text/css">
#tablaNotaCredito td, #tablaNotaCredito th
{
	font-size:1em;
	border:1px solid rgba(0,0,0,.1);
	padding:3px 7px 2px 7px;
}
</style>
</html>

