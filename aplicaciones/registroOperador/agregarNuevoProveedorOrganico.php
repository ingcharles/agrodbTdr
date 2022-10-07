<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$conexion = new Conexion();
	$cro = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos();
	
	$identificadorOperador = $_POST['identificadorOperador'];
	$identificadorProveedor = $_POST['identificadorProveedor'];
	$idTipoTransicion = $_POST['idTipoTransicion'];
	$tipo = $_POST['importador'];
	$idTipoOperacion = $_POST['idTipoOperacion'];
	$idOperadorTipoOperacion = $_POST['idOperadorTipoOperacion'];
	$nombrePais = "";
	
	if (empty($_POST['idPaisOrigen'])) {
		$idPaisOrigen = 'null';
	}else{
		$idPaisOrigen = $_POST['idPaisOrigen'];
		$nombrePais = pg_fetch_result($cc->obtenerNombreLocalizacion($conexion, $idPaisOrigen), 0, 'nombre');
	}
		
	$nombreTipoTransicion = pg_fetch_result($cc->obtenerTipoTransicionXIdTipoTransicion($conexion, $idTipoTransicion), 0, 'nombre_tipo_transicion');
	
	if(empty($identificadorProveedor)){
		$identificadorProveedor = null;
		$nombreProveedor = $_POST['nombreProveedor'];
		$imprimirNombreProveedor = $_POST['nombreProveedor'];
	}else{
		$qDatosProveedor = $cro->obtenerDatosOperador($conexion, $identificadorProveedor);
		$datosProveedor = pg_fetch_assoc($qDatosProveedor);
		
		if($datosProveedor['razon_social'] != "" || $datosProveedor['razon_social'] != ""){
			$imprimirNombreProveedor = $datosProveedor['razon_social'];
		}else{
			$imprimirNombreProveedor = $datosProveedor['nombre_representante'].' ' .$datosProveedor['apellido_representante'];
		}
		
	}
	
	$idProducto = $_POST['producto'];
	$nombreProducto = $_POST['nombreProducto'];
	
	$idOperacion = $_POST['idOperacion'];
	$qOperacion = $cro->abrirOperacionXid($conexion, $idOperacion);
	$operacion = pg_fetch_assoc($qOperacion);
	$idVigencia = 0;
	
	$qTipoOperacion = $cc->obtenerDatosTipoOperacion($conexion, $idTipoOperacion);
	$tipoOperacion = pg_fetch_assoc($qTipoOperacion);
	
	try {
		
		$conexion->ejecutarConsulta("begin;");
		
		$qProveedor = $cro->buscarProductoProveedorOrganico($conexion, $identificadorOperador, $identificadorProveedor, $idProducto, $idTipoTransicion, $idOperadorTipoOperacion, $nombreProveedor);
		$proveedor = pg_fetch_assoc($qProveedor);
		
		if(pg_num_rows($qProveedor) == 0){	   
		    
		    switch ($tipoOperacion['codigo']){
		        
		        case 'COM':
		            
		            $idArea = pg_fetch_result($cro->obtenerDatosAreaXIdOperacion($conexion, $idOperacion), 0, 'id_area');
		            		            
		            $qVerificarAreaTipoOperacionProducto = $cro->obtenerOperacionXIdentificadorTipoOperacionXIdArea($conexion, $identificadorOperador, $operacion['id_tipo_operacion'], $idProducto, $idArea);
		            
		            if(pg_num_rows($qVerificarAreaTipoOperacionProducto) == 0){
		                
		                if($operacion['id_producto'] == null){
		                    
		                    $cro->actualizarProductoOperacion($conexion, $idOperacion, $idProducto, $nombreProducto, $idVigencia);
		                    
		                }else{		                    
		                    
		                    $qIdOperacion= $cro->guardarNuevaOperacionPorTipoOperacion($conexion, $operacion['id_tipo_operacion'], $identificadorOperador, $operacion['id_operador_tipo_operacion'], $operacion['id_historial_operacion'], 'declararProveedor', $idVigencia);
		                    $idOperacion = pg_fetch_result($qIdOperacion, 0, 'id_operacion');
		                    
		                    $cro->actualizarProductoOperacion($conexion, $idOperacion, $idProducto, $nombreProducto, $idVigencia);
		                    
		                    $cro->guardarAreaOperacion($conexion, $idArea, $idOperacion);
		                    
		                }
		                
		            }else{
		                $idOperacion = pg_fetch_result($qVerificarAreaTipoOperacionProducto, 0, 'id_operacion');
		                $cro->actualizarEstadoOperacion($conexion, $idOperacion, 'declararProveedor');
		            }
		            
		        break;
		        
		        default:
		            $idOperacion = null;
		        break;
		        
		    }	    
		    
		    $idProveedor = pg_fetch_result($cro->guardarNuevoProveedor($conexion, $identificadorProveedor, $identificadorOperador, $operacion['id_tipo_operacion'], $idProducto, $nombreProducto, $idPaisOrigen, $nombrePais, $nombreProveedor, $idOperacion, $idTipoTransicion, $tipo, $idOperadorTipoOperacion), 0, 'id_proveedor');
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cro->imprimirProductosProveedoresOrganicos($idProveedor, $imprimirNombreProveedor, $nombreProducto, $nombreTipoTransicion, $operacion['id_operador_tipo_operacion'], $operacion['id_tipo_operacion']);
		
		}else{
			
			if($proveedor['estado_proveedor'] == 'inactivo'){
				
			    switch ($tipoOperacion['codigo']){
			        
			        case 'COM':
			            
			            $idArea = pg_fetch_result($cro->obtenerDatosAreaXIdOperacion($conexion, $idOperacion), 0, 'id_area');
			            
			            $qVerificarAreaTipoOperacionProducto = $cro->obtenerOperacionXIdentificadorTipoOperacionXIdArea($conexion, $identificadorOperador, $operacion['id_tipo_operacion'], $idProducto, $idArea);
			            
			            if(pg_num_rows($qVerificarAreaTipoOperacionProducto) > 0){			                
			                
			                $idOperacion = pg_fetch_result($qVerificarAreaTipoOperacionProducto, 0, 'id_operacion');
			                $cro->actualizarEstadoOperacion($conexion, $idOperacion, 'declararProveedor');
			            }
			            
			        break;
			        
			        default:
			            $idOperacion = null;
			        break;
			            
			    }	    
			    
				$cro->cambiarEstadoProveedor($conexion, $proveedor['id_proveedor'], 'activo');
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $cro->imprimirProductosProveedoresOrganicos($proveedor['id_proveedor'], $imprimirNombreProveedor, $nombreProducto, $nombreTipoTransicion, $operacion['id_operador_tipo_operacion'], $operacion['id_tipo_operacion']);
				
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'El proveedor ya ha sido registrado.';
			}
			
		}
		
		$conexion->ejecutarConsulta("commit;");
		
		
	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	} finally {
		$conexion->desconectar();
	}
	
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
} finally {
	echo json_encode($mensaje);
}

?>
