<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$idVigenciaDocumento = $_POST['idVigenciaDocumento'];
	$nombreVigencia = htmlspecialchars ($_POST['nombreVigencia'],ENT_NOQUOTES,'UTF-8');
	$etapaVigencia = $_POST['etapaVigencia'];
	$tipoDocumento = htmlspecialchars ($_POST['tipoDocumento'],ENT_NOQUOTES,'UTF-8');
	$areaTematica = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$idTipoOperacion = $_POST['tipoOperacion'];
	$idTipoProducto = $_POST['tipoProducto'];	
	$idSubtipoProducto = $_POST['subTipoProducto'];
	$arrayProductos = $_POST['producto'];
	$nivelLista = '';
	$productosIngresados = array();
	$identificadorUsuario = $_SESSION['usuario'];
	
	$nombreVigenciaAntiguo = $_POST['nombreVigenciaAntiguo'];
	$tipoOperacionAntiguo = $_POST['tipoOperacionAntiguo'];
	
	$bandera = true;
	
	try {
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		$cvd = new ControladorVigenciaDocumentos();		

		if($idSubtipoProducto != ''){
		
			if(count($arrayProductos) == 0){
				
				$bandera = false;
				
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Seleccione al menos un producto.';
				
			}
			
		}
		
		if($nombreVigencia != $nombreVigenciaAntiguo){
		
			$verificarNombreVigencia = $cvd->verificarNombreVigenciaDocumento($conexion, $nombreVigencia);
			if(pg_num_rows($verificarNombreVigencia)==0){
				
				$cvd->actualizarNombreVigenciaDocumentoXIdVigenciaDocumento($conexion, $idVigenciaDocumento, $nombreVigencia);
				
				if($idTipoOperacion != $tipoOperacionAntiguo){

					if(pg_num_rows($verificarNombreVigencia)==0){
						
						$cvd->actualizarTipoOperacionVigenciaDocumentoXIdVigenciaDocumento($conexion, $idVigenciaDocumento, $idTipoOperacion);
					
					}else{
						
						$bandera = false;
						
						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = 'La operación ya ha sido registrada para una vigencia.';						
					}					
				}
				
			}else{
				
				$bandera = false;
				
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'El nombre de la vigencia ya ha sido registrado.';
			}
		}else{
		
			if($idTipoOperacion != $tipoOperacionAntiguo){

				if(pg_num_rows($verificarNombreVigencia)==0){
			
					$cvd->actualizarTipoOperacionVigenciaDocumentoXIdVigenciaDocumento($conexion, $idVigenciaDocumento, $idTipoOperacion);
						
				}else{
			
					$bandera = false;
					
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'La operación ya ha sido registrada para una vigencia.';
				}
			}
			
		}		
		
		if($bandera){
		
		
			if($idTipoProducto == ''){
	
				$nivelLista = 'operacion';
	
				$cvd->eliminarDetalleVigenciaDocumentoPorIdVigencia($conexion, $idVigenciaDocumento);
				
				$cvd->actualizarCabeceraVigenciaDocumentoXIdVigenciaDocumento($conexion, $idVigenciaDocumento, $tipoDocumento, $areaTematica, $identificadorUsuario, $nivelLista, $etapaVigencia);
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';
	
			}else if($idSubtipoProducto == ""){
				
				$nivelLista = 'tipoProducto';

				$cvd->eliminarDetalleVigenciaDocumentoPorIdVigencia($conexion, $idVigenciaDocumento);
				
				$cvd->actualizarCabeceraVigenciaDocumentoXIdVigenciaDocumento($conexion, $idVigenciaDocumento, $tipoDocumento, $areaTematica, $identificadorUsuario, $nivelLista, $etapaVigencia);
					
				$qSubtipoProducto = $cc->listarSubTipoProductoXtipoProducto($conexion, $idTipoProducto);
	
				while($subtipoProducto = pg_fetch_assoc($qSubtipoProducto)){
	
					$qProducto = $cc->listarProductoXsubTipoProducto($conexion, $subtipoProducto['id_subtipo_producto']);
	
					while($producto = pg_fetch_assoc($qProducto)){
						
						$cvd->guardarNuevoDetalleVigenciaDocumento($conexion, $idVigenciaDocumento, $idTipoProducto, $subtipoProducto['id_subtipo_producto'], $producto['id_producto']);
	
					}
	
				}
	
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';
	
	
			}else if($idSubtipoProducto != ''){				

						$nivelLista = 'subtipoProducto';
	
						$cvd->eliminarDetalleVigenciaDocumentoPorIdVigencia($conexion, $idVigenciaDocumento);
	
						$cvd->actualizarCabeceraVigenciaDocumentoXIdVigenciaDocumento($conexion, $idVigenciaDocumento, $tipoDocumento, $areaTematica, $identificadorUsuario, $nivelLista, $etapaVigencia);
							
						foreach ($arrayProductos as $producto){
								
							$cvd->guardarNuevoDetalleVigenciaDocumento($conexion, $idVigenciaDocumento, $idTipoProducto, $idSubtipoProducto, $producto);
								
						}
	
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente.';							
	
			}

		}
		
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia".$ex;
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos'.$ex;
	echo json_encode($mensaje);
}
?>


