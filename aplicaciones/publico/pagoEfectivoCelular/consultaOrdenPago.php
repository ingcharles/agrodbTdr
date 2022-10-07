<?php	
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorDineroElectronico.php';
	$conexion = new Conexion();
	$de = new ControladorDineroElectronico();
	
	$lcabeceraOrdenPago = $de->abrirOrdenPagoDineroElectronico($conexion, $_POST['numeroSolicitud']);
	/*  generar claves
	$id='1722551049';	
	$valor="Cazp241284";	
	$scr = crc32($id);
	$key = hash('sha512', $scr);	
	echo '</br>encriptacion</br>';
	echo $contra = Encrypter::encrypt($valor, $key);
	echo '</br>';

	$provArea= $de->devolverProvinciaArea($conexion,$id);//identificador del usuario creo orden pago
	$datosProvArea = pg_fetch_assoc($provArea);	
	if(pg_num_rows($provArea)!= 0){
		$provincia = $datosProvArea['nombreprovincia'];
		$idArea = $datosProvArea['idarea'];
		$claveCertificado = $datosProvArea['clavecertificado'];//clave certificado
		//$claveCertificado="Cazp241284";
	}
	echo '</br>descencriptar</br>';
	echo Encrypter::decrypt($claveCertificado, $Key).'</br>';

	
 	$id='0919254342';
 	$scr = crc32($id);
 	$key = hash('sha512', $scr);
 	echo $key;
    echo Encrypter::decrypt('IwXGe+e0u87RJlRwbg5OmhDbwL+bErQG3Z4On+IHcv4=', $keya).'</br>';*/
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
	<header>
			<h1>Pago con Efectivo desde mi Celular</h1>
	</header>
	<div id="estado"></div>
<?php if(pg_num_rows($lcabeceraOrdenPago)!=0){
	$cabeceraOrdenPago = pg_fetch_assoc($lcabeceraOrdenPago);?>
	<fieldset id="ordenPago">
	<legend>Orden de pago <?php echo ' N°: '.$cabeceraOrdenPago['numero_solicitud'];?> </legend>	
	<?php
		$fecha = $de->devolverFecha($cabeceraOrdenPago['fecha_orden_pago']);
		echo'<div data-linea="1">
			<label>DATOS DEL CLIENTE </label> 
		</div>
		<div data-linea="1">
			<label>Número de Identificación: </label> '.$cabeceraOrdenPago['identificador_operador'].'
		</div>
		<div data-linea="2">
			<label>Razón social: </label> '.$cabeceraOrdenPago['razon_social'].'
		</div>
		<div data-linea="3">
			<label>Localización: </label> '.$cabeceraOrdenPago['localizacion'].'
		</div>
		<div data-linea="4">
			<label>Fecha de emisión: </label> '.$fecha.'
		</div>';
		$detalleOrdenPago = $de->abrirDetallePagoDineroElectronico($conexion, $cabeceraOrdenPago['id_pago']);
	?>
</fieldset>
<fieldset>
	<legend>Detalle de Cobro</legend>
	   <table id="tablaOrdenPago">
		<thead>
			<tr>
			<th>Concepto</th>
			<th>Cantidad</th>
			<th>Valor Unitario</th>
			<th>Descuento</th>
			<th>IVA</th>
			<th>Total&nbsp;&nbsp;</th>								
			</tr>
		</thead> 
		<?php
		$vpago=0.00;
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
			echo '<tr>
			<th>TOTAL A PAGAR </th>
			<th> </th><th> </th><th> </th><th> </th><th>$ '.$cabeceraOrdenPago['total_pagar'].' </th>
			</tr>';
			//realizarPagoDineroElectronico
		 ?> 
		</table></br><hr/>	
	<form id="pagoDinero"  data-rutaAplicacion="../../../publico/pagoEfectivoCelular" data-opcion="realizarPagoEfectivoCelular" data-destino="resultados">
			<input type="hidden" name="id_pago" value="<?php echo $cabeceraOrdenPago['id_pago'];?>"/>
			<input type="hidden" name="totalPagar" value="<?php echo $cabeceraOrdenPago['total_pagar'];?>"/>
			<input type="hidden" name="idOperador" value="<?php echo $cabeceraOrdenPago['identificador_operador'];?>"/>
			<input type="hidden" name="numeroFactura" value="<?php echo $cabeceraOrdenPago['numero_factura'];?>"/>
			<input type="hidden" name="identificador" value="<?php echo $cabeceraOrdenPago['identificador_usuario'];?>"/>
			<input type="hidden" name="numeroSolicitud" id="numeroSolicitud" value="<?php echo $cabeceraOrdenPago['numero_solicitud'];?>">
	        <?php if($cabeceraOrdenPago['estado']==3)echo '<button class="botonTama">PAGAR</button>';?>
	        &nbsp;&nbsp;<button buttonAlign="center" class="botonTama" onclick="verificar(id)">CANCELAR</button>
	</form>
</fieldset>
<?php }else{
	echo '<fieldset>
	
	<table id="tablaOrdenPago">
	<thead>
			NO EXISTEN DATOS PARA LA CONSULTA
	</thead>
			</table>				
	</fieldset>
	';	
} 
	?>
</body>
<script type="text/javascript">
$("#pagoDinero").submit(function(e){
	 e.preventDefault();	 
	 abrir($(this), e, false);	    
});
function verificar(id){
	$("#pagoDinero").attr('data-opcion', '');
	location.reload();
}
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