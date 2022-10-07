<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$mensaje['idVigenciaDocumento'] = '';

try{

	$identificadorUsuario = $_POST['identificadorUsuario'];
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

	
	try {
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		$cvd = new ControladorVigenciaDocumentos();
		
		
		$verificarNombreVigencia = $cvd->verificarNombreVigenciaDocumento($conexion, $nombreVigencia);
		
		if(pg_num_rows($verificarNombreVigencia)==0){		
			
			$verificarCabeceraVigencia = $cvd->verificarCabeceraVigenciaDocumento($conexion, $tipoDocumento, $areaTematica, $idTipoOperacion);
			
			if(pg_num_rows($verificarCabeceraVigencia)==0){				

				if($idTipoProducto == ''){
					
					$nivelLista = 'operacion';
					
					$qIdVigenciaDocumento = $cvd->guardarNuevoVigenciaDocumento($conexion, $nombreVigencia, $tipoDocumento, $areaTematica, $idTipoOperacion, $identificadorUsuario, $identificadorUsuario, $nivelLista, $etapaVigencia);
					$idVigenciaDocumento = pg_fetch_result($qIdVigenciaDocumento, 0, 'id_vigencia_documento');

					$mensaje['idVigenciaDocumento'] = $idVigenciaDocumento;
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';					
					
				}else if($idSubtipoProducto == ''){
					
					$nivelLista = 'tipoProducto';

					$qIdVigenciaDocumento = $cvd->guardarNuevoVigenciaDocumento($conexion, $nombreVigencia, $tipoDocumento, $areaTematica, $idTipoOperacion, $identificadorUsuario, $identificadorUsuario, $nivelLista, $etapaVigencia);
					$idVigenciaDocumento = pg_fetch_result($qIdVigenciaDocumento, 0, 'id_vigencia_documento');
					
					$mensaje['idVigenciaDocumento'] = $idVigenciaDocumento;
					
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
					
					if(count($arrayProductos) != 0){

						$nivelLista = 'subtipoProducto';						

							$qIdVigenciaDocumento = $cvd->guardarNuevoVigenciaDocumento($conexion, $nombreVigencia, $tipoDocumento, $areaTematica, $idTipoOperacion, $identificadorUsuario, $identificadorUsuario, $nivelLista, $etapaVigencia);
							$idVigenciaDocumento = pg_fetch_result($qIdVigenciaDocumento, 0, 'id_vigencia_documento');
							
							$mensaje['idVigenciaDocumento'] = $idVigenciaDocumento;
							
							foreach ($arrayProductos as $producto){
									
								$cvd->guardarNuevoDetalleVigenciaDocumento($conexion, $idVigenciaDocumento, $idTipoProducto, $idSubtipoProducto, $producto);
									
							}
							
							$mensaje['estado'] = 'exito';
							$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';

					}else{
											
						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = 'Seleccione al menos un producto.';
					}				
					
				}
					
			}else{

				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'La configuración de la vigencia ya ha sido registrada.';
					
			}
			
		}else{

			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'El nombre de la vigencia ya ha sido registrado.';
			
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



