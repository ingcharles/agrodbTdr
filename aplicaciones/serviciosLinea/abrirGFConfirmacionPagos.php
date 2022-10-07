<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosLinea.php';

$conexion = new Conexion();
$csl = new ControladorServiciosLinea();


$formato=$_POST['elementos'];
if($formato=='individual' || $formato==''){
	$fecha=$_POST['opcion'];
	$idConfirmacionPago=$_POST['id'];
	echo '<header>
			<h1>Modificar Información de Pagos</h1>
			<h3 style="text-align: right;">';
	echo str_replace('-'," de ",$fecha);
	echo '</h3>';
	
	$qMatriz=$csl->buscarIdentificadorBeneficiarioMatriz($conexion, $idConfirmacionPago);
	while($filas=pg_fetch_assoc($qMatriz)){
		echo '<fieldset id="'.$filas['identificador_beneficiario'].'"><legend><label >'.$filas['identificador_beneficiario'].' - '.$filas['nombre_beneficiario'].' </label></legend>';
		echo '<table class="tablaMatriz">
				<thead><tr>
				<th>CUR</th>
				<th>Descripción</th>
				<th>Fecha</th>
				<th>Monto</th>
				<th>Banco</th>
				<th>Eliminar</th>
				</tr></thead><tbody>';
		$qRegistroDetalleConfirmacionPago=$csl->buscarDetalleConfirmacionPago($conexion, $idConfirmacionPago,$filas['identificador_beneficiario']);
		while($fila=pg_fetch_assoc($qRegistroDetalleConfirmacionPago)){
			echo $csl->imprimirLineaDetalleConfirmacionPagos($fila['id_detalle_confirmacion_pago'] ,$fila['num_trans_cur'], $fila['descripcion'],$fila['fecha_pago'],number_format($fila['monto_pago'],2,".",""),$fila['banco']);
		}
		echo '</tbody></table></fieldset>';
	}
	echo '</header>';
}
if($formato=='consolidado'){
	$fecha=$_POST['id'];
	$idConfirmacionPago=$_POST['opcion'];
	echo '<header>
			<h1>Modificar Información de Pagos</h1>
			<h3 style="text-align: right;">';
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$rr=explode("-", $fecha);
	echo $meses[$rr[0]-1]." de ".$rr[1];
	echo '</h3>';

	$qMatriz=$csl->buscarConfirmacionPagoConsolidado($conexion, $idConfirmacionPago,$fecha);
	while($filas=pg_fetch_assoc($qMatriz)){
		echo '<fieldset id="'.$filas['identificador_beneficiario'].'"><legend><label >'.$filas['identificador_beneficiario'].' - '.$filas['nombre_beneficiario'].' </label></legend>';
		echo '<table class="tablaMatriz">
				<thead><tr>
				<th>CUR</th>
				<th>Descripción</th>
				<th>Fecha</th>
				<th>Monto</th>
				<th>Banco</th>
				<th>Eliminar</th>
				</tr></thead><tbody>';
		$qRegistroDetalleConfirmacionPago=$csl->buscarDetalleConfirmacionPagoConsolidado($conexion, $idConfirmacionPago,$fecha,$filas['identificador_beneficiario']);
		while($fila=pg_fetch_assoc($qRegistroDetalleConfirmacionPago)){
			echo $csl->imprimirLineaDetalleConfirmacionPagos($fila['id_detalle_confirmacion_pago'] ,$fila['num_trans_cur'], $fila['descripcion'],$fila['fecha_pago'],number_format($fila['monto_pago'],2,".",""),$fila['banco']);
		}
		echo '</tbody></table></fieldset>';
	}
	echo '</header>';
}
?>
<script>	
	$(document).ready(function(){
		distribuirLineas();
		acciones(null,".tablaMatriz");
	});

	$(".icono").click(function(event){
		if ($(this).parents('fieldset').find('tbody >tr').length == 1){
			$(this).parents('fieldset').hide();	
		}	

		if ($('.tablaMatriz >tbody >tr').length == 1){
			$("#_actualizarSubListadoItems").click();
		}
	});

</script>