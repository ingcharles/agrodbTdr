<?php	
//header('Content-Type: text/html; charset=ISO-8859-1');	
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorDineroElectronico.php';
	//require_once 'nusoap.php';
	$conexion = new Conexion();
	$de = new ControladorDineroElectronico();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
	<header>
			<h1>Pago con Dinero Electrónico</h1>
	</header>
	<div id="estado"></div>

<fieldset id="ordenPago">
<section id="pruebadinero">
</section>
	<legend>Informar </legend>
	<form id="pruebapago"  data-rutaAplicacion="../../../publico/dineroElectronico" data-opcion="finalizarOrdenPagoDineroElectronico" data-destino="pruebadinero">
	
	        <input type="hidden" name="id_pago" value="<?php echo $_POST['id_pago'];?>"/>
			<input type="hidden" name="totalPagar" value="<?php echo $_POST['totalPagar'];?>"/>
			<input type="hidden" name="idOperador" value="<?php echo $_POST['idOperador'];?>"/>
			<input type="hidden" name="numeroFactura" value="<?php echo $_POST['numeroFactura'];?>"/>
			<input type="hidden" name="identificador" value="<?php echo $_POST['identificador'];?>"/>
			<input type="hidden" name="numeroSolicitud" value="<?php echo $_POST['numeroSolicitud'];?>">
			<button id="cobrar2" class="botonTama">xml</button>
	</form>
	<?php
	echo '<script> alert("sss"); $("#pruebapagoxx").submit();</script>';
  /*$monto=1481.70;
	$cobroPre = $de->cobroDineroElectronicoPre($monto,'0201798907','0983507492');
	//$cobroPre = $de->cobroDineroElectronicoPre($_POST['totalPagar'], $_POST['cedula'], $_POST['celular']);
	if($cobroPre['codigo']==1){
		//$resultTransac=$de->cobroDineroElectronicoConfirm($_POST['totalPagar'], $_POST['cedula'], $_POST['celular']);
		$resultTransac= $de->cobroDineroElectronicoConfirm($monto,'0201798907', '0983507492');
		if($resultTransac['codigo']==1){
			echo $resultTransac['text'];
			
		}else echo $resultTransac['text'];
	}else {
		$mensaje = $cobroPre['text'];
		if($cobroPre['codigoId']=='104')$mensaje="Número de cédula no se encuentra registrado en dinero electrónico";
		if($cobroPre['codigoId']=='034')$mensaje="Número de móvil no se encuentra registrado en dinero electrónico";
	
		echo $mensaje;
		//echo $cobroPre['codigo'];
	}
	/* ACREDITAR A LA CUENTA DE AGROCALIDAD
	* GENERAR FACTURA AUTOMATICAMENTE
	* CREAR UNA NUEVA FORMA DE PAGO: FORMA DE PAGO=  DINERO ELECTRONICO,
	* NUMERO DE TRANSACCION=CONSUMIR DEL BCE, FECHA DE DEPOSITO= BCE
	* VALOR DEPOSITADO=ORDEN DE PAGO
	* FACTURAS EMITIR AUTOMATICAMENTE CON PFX
	* 0980391763 inactivar
	* 1722551049 Eddy
	* 0994023167 acciones("#pruebapago","#pruebadinero");
	*/
    
	?>			
	<form id="realizarPagoDinero"  data-rutaAplicacion="../../../publico/dineroElectronico" data-opcion="pagoDineroElectronico" >
	<button id="cobrar2" class="botonTama">SALIR</button>
	</form>
</fieldset>
</body>
<script type="text/javascript">


$("#pruebapago").on("submit", function(e){ 
	
	e.preventDefault();
	abrir($(this), e, false);

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



