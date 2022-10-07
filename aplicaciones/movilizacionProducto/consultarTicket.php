<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';

$conexion = new Conexion();
$cmp = new ControladorMovilizacionProductos();

$numeroCortoTicket = $_POST['numeroCortoTicket'];
$idSitio = $_POST['idSitio'];
$qConsultarDatosTicket=$cmp->consultarDatosTicket($conexion, $numeroCortoTicket);

$rr=array();
if(pg_num_rows($qConsultarDatosTicket)!=0){
	
	$qConsultarDatosTicketMovilizacionVigente=$cmp->consultarDatosTicketMovilizacion($conexion, $numeroCortoTicket,'vigente', $idSitio);
	
	if(pg_num_rows($qConsultarDatosTicketMovilizacionVigente)!=0){
		
		$filaTicket=pg_fetch_assoc($qConsultarDatosTicketMovilizacionVigente);
		$qProductosActosMovilizar=$cmp->buscarProductosConVacuna($conexion,'vacunacion',$filaTicket['producto']);
		
		$identificadores="''";
		
		if($_POST['nombreArea']=='SA'){
		
			if(pg_num_rows($qProductosActosMovilizar)==0){
				$qProductoIdentificadoMovilizar = $cmp->productosActosMovilizacionTicket($conexion,$filaTicket['area'],  $filaTicket['numero_lote'],$filaTicket['operacion'], $filaTicket['unidad_comercial'],"(" . rtrim ( $identificadores, ',' ) . ")",$filaTicket['identificador_producto']);
			}else{
				$qProductoIdentificadoMovilizar = $cmp->productosActosMovilizacionVacunadosTicket($conexion,$filaTicket['area'],$filaTicket['numero_lote'],$filaTicket['operacion'], $filaTicket['unidad_comercial'],"(" . rtrim ( $identificadores, ',' ) . ")",$filaTicket['identificador_producto']);
			}
		}else{
			//PROGRAMAR PARA SANIDAD VEGETAL CERTIFICACION FITOZANITARIO
			//BUSCAR PRODUCTOS CON CERTIFICACION buscarProductosConVacuna($conexion,'vacunacion',$_POST['producto']);
			$qProductoIdentificadoMovilizar = $cmp->productosActosMovilizacionTicket($conexion,$_POST['gAreaOrigen'],$_POST['gNumeroLote'],$_POST['gOperacionOrigen'],$_POST['gUnidadComercial'],"(" . rtrim ( $identificadores, ',' ) . ")",$filaTicket['identificador_producto']);
			}
			
			if(pg_num_rows($qProductoIdentificadoMovilizar)!=0){
				//echo "hola";
				$codigoEspecie=pg_fetch_result($qProductoIdentificadoMovilizar,0,'codigo');
				$rr[] = array(error=>null,idProducto=>$filaTicket['producto'],nombreProducto=>$filaTicket['nombre_producto'],
						idUnidadComercial=>$filaTicket['unidad_comercial'],nombreUnidadComercial=>$filaTicket['nombre_unidad_comercial'],
						idArea=>$filaTicket['area'],nombreArea=>$filaTicket['nombre_area'],
						identificadorProducto=>$filaTicket['identificador_producto'],
						codigoEspecie=>$codigoEspecie);
			}else{
				$rr[] = array(error=>'Ticket inhabilitado');
			}
	}else{
		
		$qConsultarDatosTicketMovilizacionCaducado=$cmp->consultarDatosTicketMovilizacion($conexion, $numeroCortoTicket,'caducado',$idSitio);
		if(pg_num_rows($qConsultarDatosTicketMovilizacionCaducado)!=0){
		$filaTicket=pg_fetch_assoc($qConsultarDatosTicketMovilizacionCaducado);

		$qProductosActosMovilizar=$cmp->buscarProductosConVacuna($conexion,'vacunacion',$filaTicket['producto']);
		
		$identificadores="''";
		
		if($_POST['nombreArea']=='SA'){
		
			if(pg_num_rows($qProductosActosMovilizar)==0){
				$qProductoIdentificadoMovilizar = $cmp->productosActosMovilizacionTicket($conexion,$filaTicket['area'],  $filaTicket['numero_lote'],$filaTicket['operacion'], $filaTicket['unidad_comercial'],"(" . rtrim ( $identificadores, ',' ) . ")",$filaTicket['identificador_producto']);
			}else{
				$qProductoIdentificadoMovilizar = $cmp->productosActosMovilizacionVacunadosTicket($conexion,$filaTicket['area'],  $filaTicket['numero_lote'],$filaTicket['operacion'], $filaTicket['unidad_comercial'],"(" . rtrim ( $identificadores, ',' ) . ")",$filaTicket['identificador_producto']);
			}
		}else{
			//PROGRAMAR PARA SANIDAD VEGETAL CERTIFICACION FITOZANITARIO
			//BUSCAR PRODUCTOS CON CERTIFICACION buscarProductosConVacuna($conexion,'vacunacion',$_POST['producto']);
			$qProductoIdentificadoMovilizar = $cmp->productosActosMovilizacionTicket($conexion,$_POST['gAreaOrigen'],$_POST['gNumeroLote'],$_POST['gOperacionOrigen'],$_POST['gUnidadComercial'],"(" . rtrim ( $identificadores, ',' ) . ")",$filaTicket['identificador_producto']);
		}
		
			if(pg_num_rows($qProductoIdentificadoMovilizar)!=0){
				$codigoEspecie=pg_fetch_result($qProductoIdentificadoMovilizar,0,'codigo');
		
				$rr[] = array(error=>null,idProducto=>$filaTicket['producto'],nombreProducto=>$filaTicket['nombre_producto'],
						idUnidadComercial=>$filaTicket['unidad_comercial'],nombreUnidadComercial=>$filaTicket['nombre_unidad_comercial'],
						idArea=>$filaTicket['area'],nombreArea=>$filaTicket['nombre_area'],
						identificadorProducto=>$filaTicket['identificador_producto'],
						codigoEspecie=>$codigoEspecie);
			}else{
				$rr[] = array(error=>'Ticket inhabilitado');
			}
		}else{
			$rr[] = array(error=>'Ticket inhabilitado');
		}
	}
}else{
	$rr[] = array(error=>'No existe ticket');
}
echo json_encode($rr);
?>
