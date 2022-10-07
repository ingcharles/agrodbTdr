<?php		
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorTramitesInocuidad.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<?php

	$conexion = new Conexion();
	$cti = new ControladorTramitesInocuidad();
	
	$datos = array('identificadorOperador' => htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8'), 
				'producto' => htmlspecialchars ($_POST['producto'],ENT_NOQUOTES,'UTF-8'),
				'nombreProducto' =>  htmlspecialchars ($_POST['nombreProducto'],ENT_NOQUOTES,'UTF-8'), 
				'tipoTramite' => htmlspecialchars ($_POST['tipoTramite'],ENT_NOQUOTES,'UTF-8'),
				'nombreTipoTramite' => htmlspecialchars ($_POST['nombreTipoTramite'],ENT_NOQUOTES,'UTF-8'), 
				'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
				'identificadorUsuario' => htmlspecialchars ($_POST['identificadorUsuario'],ENT_NOQUOTES,'UTF-8'));
	
		$numeroTramite = pg_fetch_result($cti->buscarSecuencialNumeroTramite($conexion),0,'numero');
	
		$idTramite = $cti -> guardarTramite($conexion, $numeroTramite, $datos['identificadorOperador'],$datos['producto'],$datos['nombreProducto'],$datos['tipoTramite'],
											$datos['nombreTipoTramite'],'enviado',$datos['observacion']);
		
		
		
		$fechaActual = date('Y-m-d');
		$diaActual=date("w", strtotime($fechaActual));

		if($diaActual == '5'){
			$fechaDespacho = date ('Y-m-d h:m:s',strtotime ( '+3 day' , strtotime ($fechaActual)));
		}else if ($diaActual == '6'){
			$fechaDespacho = date ('Y-m-d h:m:s',strtotime ( '+2 day' , strtotime ($fechaActual)));
		}else{
			$fechaDespacho = date ('Y-m-d h:m:s',strtotime ( '+1 day' , strtotime ($fechaActual)));
		}
		
		$idSeguimientoTramite = $cti-> guardarSeguimientoTramite($conexion, $numeroTramite, $datos['identificadorUsuario'], $fechaDespacho);
		
		echo '<input type="hidden" id="'.$numeroTramite.'" data-rutaAplicacion="tramitesInocuidad" data-opcion="imprimirTramite" data-destino="detalleItem"/>';
		
	?>
	
</body>

<script type="text/javascript">

	/*$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);	
		abrir($("input:hidden"),null,false);
	});*/
		
		
</script>

</html>
