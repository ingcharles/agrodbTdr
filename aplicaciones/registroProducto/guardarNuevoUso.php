<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorAuditoria.php';

	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
try{
	$idProducto = htmlspecialchars ($_POST['idProductoInocuidad'],ENT_NOQUOTES,'UTF-8');
	$idUso = htmlspecialchars ($_POST['uso'],ENT_NOQUOTES,'UTF-8');
	$nombreUso = htmlspecialchars ($_POST['nombreUso'],ENT_NOQUOTES,'UTF-8');
	$idEspecie = ($_POST['especie']);
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	$nombreEspecie = htmlspecialchars ($_POST['nombreEspecieUso'],ENT_NOQUOTES,'UTF-8');
	$idArea = htmlspecialchars ($_POST['idAreaU'],ENT_NOQUOTES,'UTF-8');
	$instalacion= htmlspecialchars ($_POST['instalacion'],ENT_NOQUOTES,'UTF-8');
	$aplicado= htmlspecialchars ($_POST['aplicado_a'],ENT_NOQUOTES,'UTF-8');
	
	$productoArea = ($_POST['usoProducto']);
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAuditoria();
		
		$codigoOtroCultivo = $cr->obtenerCodigoOtroCultivo($conexion,'Cultivo','Cultivo');
		$codigosOtroCultivo = pg_fetch_assoc($codigoOtroCultivo);
		
		if($idEspecie == ''){
		    if($aplicado == 'Producto'){
				if($idUso != ''){
    					if(count($productoArea) > 0){
    						for ($i=0;$i<count($productoArea);$i++){
    							$tmp= explode("-", $productoArea[$i]);
    							$idCodigo = $tmp[0];
    							$nombreCodigo = $tmp[1];
    							
    							if(pg_num_rows($cr->buscarProductoOtrosCultivo($conexion, $nombreCodigo,$codigosOtroCultivo['id_tipo_producto'],$codigosOtroCultivo['id_subtipo_producto']))==0){
    							
    								$productoCultivo = $cr->abrirProducto($conexion,$idCodigo);
    								$productoOtroCultivos = pg_fetch_assoc($productoCultivo);
    								
    								$qcultivo = $cr -> guardarProductoOtroCultivos($conexion,$productoOtroCultivos['nombre_comun'],$productoOtroCultivos['nombre_cientifico'],$productoOtroCultivos['estado'],$codigosOtroCultivo['id_subtipo_producto'],$productoOtroCultivos['unidad_medida']);
    								$idqProducto = pg_fetch_result($qcultivo, 0, 'id_producto');
    								$cr -> guardarProductoInocuidadTMP($conexion, $idqProducto);
    								
    								$idProductoUso = pg_fetch_result($cr -> guardarNuevoUso($conexion, $idProducto, $idUso, $idqProducto), 0, 'id_producto_uso');
    									
    								$mensaje['estado'] = 'exito';
    								$mensaje['mensaje'] .= $cr->imprimirUso( $idqProducto, $idUso,$nombreUso,$idCodigo,$nombreCodigo, $idProductoUso);
    												
    							}else{
    								
    								$pCultivo = $cr->buscarProductoOtrosCultivo($conexion, $nombreCodigo,$codigosOtroCultivo['id_tipo_producto'],$codigosOtroCultivo['id_subtipo_producto']);
    								$idProductoCultivo = pg_fetch_result($pCultivo, 0, 'id_producto');
    								
    								
    								if(pg_num_rows($cr->buscarUsoProducto($conexion, $idProducto, $idUso, $idProductoCultivo))==0){
    								
    								    $idProductoUso = pg_fetch_result($cr -> guardarNuevoUso($conexion, $idProducto, $idUso, $idProductoCultivo), 0, 'id_producto_uso');
    								
    									$mensaje['estado'] = 'exito';
    									$mensaje['mensaje'] .= $cr->imprimirUso( $idProductoCultivo, $idUso,$nombreUso,$idCodigo,$nombreCodigo, $idProductoUso);
    								}else{
    									$mensaje['estado'] = 'error';
    									$mensaje['mensaje'] = 'El producto ya esta asignado!';
    									
    								}
    							}
    							
    						}
    						
    					}else{
    						$mensaje['estado'] = 'error';
    						$mensaje['mensaje'] = 'Seleccione un producto!';
    						
    					}
    						
    				}else {
    					$mensaje['estado'] = 'error';
    					$mensaje['mensaje'] = 'Seleccione un uso!';
    					
    				}
    		    }else{
    		        if($aplicado == 'Instalacion'){
    		            if(pg_num_rows($cr->buscarUsoProductoInstalacion($conexion, $idProducto,$idUso, $instalacion, $aplicado))==0){
    		                $idProductoUso = pg_fetch_result($cr -> guardarNuevoUsoInstalacion($conexion, $idProducto, $idUso,$instalacion, $aplicado), 0, 'id_producto_uso');
    		                
    		                $mensaje['estado'] = 'exito';
    		                $mensaje['mensaje'] = $cr->imprimirUso( $idProducto, $idUso, $nombreUso, null, $instalacion, $idProductoUso);
    		            }else{
    		                $mensaje['estado'] = 'error';
    		                $mensaje['mensaje'] = 'La instalación para este producto ya ha sido ingresada.';
    		            }
    		        }
    		    }
			}else{
			    if(pg_num_rows($cr->buscarUsoProductoEspecie($conexion, $idProducto,$idUso, $idEspecie, $nombreEspecie, $aplicado))==0){
			        $idProductoUso = pg_fetch_result($cr -> guardarNuevoUsoEspecie($conexion, $idProducto, $idUso,$idEspecie, $nombreEspecie, $aplicado), 0, 'id_producto_uso');
					$especies = $cc->obtenerEspecieXid($conexion,$idEspecie);
					$especie = pg_fetch_assoc($especies);
						 
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cr->imprimirUso( $idProducto, $idUso, $nombreUso, $idEspecie, $especie['nombre'], $idProductoUso, $nombreEspecie, $idArea);
				}else{
						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = 'La especie para este producto ya ha sido ingresado.';
					}
			 }	
	
			 /*AUDITORIA*/
			  
			 $qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
			 $transaccion = pg_fetch_assoc($qTransaccion);
			 
			 if($transaccion['id_transaccion'] == ''){
			 	$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			 	$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
			 }
			 $ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asociado al producto con id '.$idProducto.' el uso '.$nombreUso);
			  
			 /*FIN AUDITORIA*/
	 
	    		   
	   $conexion->desconectar();
	   echo json_encode($mensaje);
					
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>