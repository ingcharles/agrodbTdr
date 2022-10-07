<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAuditoria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{	

	$identificadorAsociacion = $_SESSION['usuario'];	
	$identificadorMiembroAsociacion = $_POST['numero'];
	$nombreMiembroAsociacion = $_POST['nombreMiembro'];
	$apellidoMiembroAsociacion = $_POST['apellidoMiembro'];	
	$idArea = $_POST['idArea'];
	$idSitio = $_POST['idSitio'];
	$superficieSitio = $_POST['superficieSitio'];
	$superficie = $_POST['superficie'];
	$rendimiento = $_POST['rendimiento'];
	$agencia = $_POST['agencia'];
	$idOperadorTipoOperacion = $_POST['idOperadorTipoOperacion'];
	$idHistorialOperacion = $_POST['idHistorialOperacion'];
	$estadoOperacion = $_POST['estadoOperacion'];

	if( $_POST['codigoMagap']!=''){
		$codigoMagap= $_POST['codigoMagap'];
	}else{
		$codigoMagap="";
	}
	
	//print_r($rendimiento);
	//print_r($superficie);
	$bandera = false;
	$banderaAgencia = false;
	$banderaSupera = false;
	$operacionesRecibidas = 0;
	$arrayOperaciones = array();

	foreach($superficie as $llaveSuperficie => $valorSuperficie) {
		foreach($rendimiento as $llaveRendimiento => $valorRendimiento) {
			if($llaveSuperficie == $llaveRendimiento){
			    if (trim($valorSuperficie) != "" && trim($valorRendimiento) != ""){
					$sumaSuperficieProductos += $valorSuperficie;
					$operacionesRecibidas++;
					$arrayOperaciones[$llaveSuperficie] = array('superficie'=>$valorSuperficie, 'rendimiento'=>$valorRendimiento);
				}
			}
		}
	}
	
	$contarActualizar = 0;

	if(isset($agencia)){
		foreach($agencia as $llaveAgencia => $valorAgencia){
			if (!empty($valorAgencia)){
				$contarActualizar++;
			}
		}
	}
	
	//echo $sumaSuperficieProductos;
	//echo 'totaldatos: '. $operacionesRecibidas;

	//TODO:PARA VELIDAR MIEMBRO SE HARIA POR ID MIEMBRO ASOCIACION SI EXISTE; SI NO EXISTE SE REGISTRA SI EXISTE ES ACTUALIZACION
	
	try {

		$conexion = new Conexion();
		$cro = new ControladorRegistroOperador();
		$ca = new ControladorAuditoria();

		$numeroOperaciones = pg_fetch_result($cro->obtenerNumeroOperacionesPorArea($conexion, $idArea), 0, 'numero_operaciones');		
		
	
		if(!empty($identificadorMiembroAsociacion) || !empty($codigoMagap) || !empty($nombreMiembroAsociacion) || !empty($apellidoMiembroAsociacion)){
			
		    
		    $superficieArea = pg_fetch_result($cro->ObtenerDatosAreaOperador($conexion, $idArea), 0, 'superficie_utilizada');
		    
		    if($sumaSuperficieProductos <= $superficieArea){
			
			//TODO:VERIFICO SI EXISTE EL MIEMBRO
			
			$qDatosMiembroAsociacion = $cro->verificarIdentificadorMiembro($conexion, $identificadorMiembroAsociacion, $identificadorAsociacion);
			$datosMiembroAsociacion = pg_fetch_assoc($qDatosMiembroAsociacion);
			
			if(pg_num_rows($qDatosMiembroAsociacion)>0){//echo "<br>Existe el miembros<br>";
			
				$qObtenerMiembro = $cro->obtenerMiembroXIdentificadorXIdSitio($conexion, $identificadorMiembroAsociacion, $idSitio);
				

				if(pg_num_rows($qObtenerMiembro)>0){//echo "<br>El miembro es duenio del sitioxxx<br>";
					
				    $idMiembroAsociacion = pg_fetch_result($qObtenerMiembro, 0, 'id_miembro_asociacion');
				    
					$qVerificarDatosMiembro = $cro->verificarDatosMiembroAsociacion($conexion, $idMiembroAsociacion, $nombreMiembroAsociacion, $apellidoMiembroAsociacion, $codigoMagap);
					
					if(pg_num_rows($qVerificarDatosMiembro)==0){
						
						//echo "Actualizó la informacion, entonces actualizo datos del miembro";
					
						$cro->actualizarCabeceraMiembroAsociacionXIdMiembro($conexion, $idMiembroAsociacion, $identificadorMiembroAsociacion, $nombreMiembroAsociacion, $apellidoMiembroAsociacion, $codigoMagap);
						$cro->actualizarDatosMiembroAsociacionXIdentificadorMiembro($conexion, $identificadorMiembroAsociacion, $nombreMiembroAsociacion, $apellidoMiembroAsociacion);
							
						if($operacionesRecibidas == $numeroOperaciones){
						    
						    $bandera = true;
						    
						}
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = "Su información ha sido actualizada";
					
					}					
					
				}else{
					
					//echo "El miembro no es dueño del sitio, verificar si el sitio es de otro miembro";
					
					//if($operacionesRecibidas == $numeroOperaciones){
					
						$codigoMiembroAsociacion = $datosMiembroAsociacion['codigo_miembro_asociacion'];
						$identificadorMiembroAsociacion = $datosMiembroAsociacion['identificador_miembro_asociacion'];
						$nombreMiembroAsociacion = $datosMiembroAsociacion['nombre_miembro_asociacion'];
						$apellidoMiembroAsociacion = $datosMiembroAsociacion['apellido_miembro_asociacion'];
						
						$qObtenerMiembroXIdSitio = $cro->obtenerMiembroAsociacionXIdSitio($conexion, $idSitio);
						
						if(pg_num_rows($qObtenerMiembroXIdSitio)>0){//echo "El sitio es de otro dueño, reemplazar(actualizar) los datos del miembro";
													
							$cro->actualizarRegistroMiembroAsociacionXIdSitio($conexion, $codigoMiembroAsociacion, $identificadorMiembroAsociacion, $identificadorAsociacion, $nombreMiembroAsociacion, $apellidoMiembroAsociacion, $codigoMagap, $idSitio);
							
						}else{//echo "El sitio no es de otro dueño guardar los datos del miembro";
												
							$idMiembroAsociacion = pg_fetch_result($cro->guardarMiembroAsociacion($conexion, $codigoMiembroAsociacion, $identificadorMiembroAsociacion, $identificadorAsociacion, $nombreMiembroAsociacion, $apellidoMiembroAsociacion, $codigoMagap, $idSitio), 0, 'id_miembro_asociacion');
												
						}
						
						if($operacionesRecibidas == $numeroOperaciones){
						
						  $bandera = true;
						  
						}
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = "Su información ha sido actualizada";
						
					/*}else{

						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = "Por favor registre todos los rendimientos de los productos para poder actualizar el miembro de asociación";
						
					}*/
					
				}
			}else{//echo "no existe el miembro, verifica si el sitio es de otro miembro";
						
			//echo "generar Codigo de miembro";
			
				//if($operacionesRecibidas == $numeroOperaciones){
				
					$codigo = pg_fetch_assoc($cro->generarCodigoMiembroAsociacion($conexion, '%ORG-%'));
					
					if($codigo['valor'] != ''){
						$tmp= explode("-", $codigo['valor']);
						$incremento = end($tmp)+1;
						$codigoMiembroAsociacion = 'ORG-'.str_pad($incremento, 5, "0", STR_PAD_LEFT);
					}else{
						$incremento = 1;
						$codigoMiembroAsociacion = 'ORG-'.str_pad($incremento, 5, "0", STR_PAD_LEFT);
					}
				
					$qObtenerMiembroXIdSitio = $cro->obtenerMiembroAsociacionXIdSitio($conexion, $idSitio);
				
					if(pg_num_rows($qObtenerMiembroXIdSitio)>0){//echo "El sitio es de otro dueño, reemplazar(actualizar) los datos del miembrosss";
					
						$cro->actualizarRegistroMiembroAsociacionXIdSitio($conexion, $codigoMiembroAsociacion, $identificadorMiembroAsociacion, $identificadorAsociacion, $nombreMiembroAsociacion, $apellidoMiembroAsociacion, $codigoMagap, $idSitio);
					
					}else{//echo "El sitio no es de otro dueño guardar los datos del miembro";
											
						$idMiembroAsociacion = pg_fetch_result($cro->guardarMiembroAsociacion($conexion, $codigoMiembroAsociacion, $identificadorMiembroAsociacion, $identificadorAsociacion, $nombreMiembroAsociacion, $apellidoMiembroAsociacion, $codigoMagap, $idSitio), 0, 'id_miembro_asociacion');
											
					}
					
					if($operacionesRecibidas == $numeroOperaciones){
					
					   $bandera = true;
					}
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = "Su información ha sido actualizada";
				
				/*}else{

					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "Por favor registre todos los rendimientos de los productos para poder actualizar el miembro de asociación";
					
				}*/
				
			}
				
							
			
					
				if($operacionesRecibidas == $numeroOperaciones){
					
					foreach ($arrayOperaciones as $llaveOperacion => $valorOperacion){

						$qVerificarOperacionRendimiento = $cro->buscarOperacionesRendimientoXidOperacion($conexion, $llaveOperacion);
						$verificarOperacionRendimiento = pg_fetch_assoc($qVerificarOperacionRendimiento);
						
						if (pg_num_rows($qVerificarOperacionRendimiento) > 0){
							
							if($valorOperacion['superficie'] != $verificarOperacionRendimiento['superficie_miembro'] || $valorOperacion['rendimiento'] != $verificarOperacionRendimiento['rendimiento']){
																
								//echo "actualiza";
								
								$bandera = true;
								$cro -> actualizarRendimientoAsociacionDetalle($conexion, $llaveOperacion, $valorOperacion['rendimiento'], $valorOperacion['superficie']);
								
								//Verifica si existe id_operacion en la tabla operaciones_organico.
								
								$qVerificarOperacionOrganico = $cro-> verificarOperacionOrganico($conexion, $llaveOperacion);
								
								if(pg_num_rows($qVerificarOperacionOrganico) > 0){
									//echo '<br>ACTUALIZAR A INACTIVO EL REGISTRO'.$llaveOperacion;
									$cro->eliminarOperacionOrganicoXidOperacion($conexion, $llaveOperacion);									
								}								
								
							}else{ 
						    
							    if($estadoOperacion != 'registrado'){
							        $bandera = true;
							    }
							    
							    if(isset($agencia)){
							    	foreach($agencia as $llaveAgencia => $valorAgencia){
							    		
							    		if($llaveAgencia == $llaveOperacion){
							    			
							    			if($valorAgencia == 'actualizar'){
							    				//echo "se actualiza a revisión documental x que cambia agencia la operacion".$llaveAgencia;
							    				//echo "cambio de agencia";
							    				$banderaAgencia = true;
							    				
							    				$qVerificarOperacionOrganico = $cro-> verificarOperacionOrganico($conexion, $llaveOperacion);
							    				
							    				if(pg_num_rows($qVerificarOperacionOrganico) > 0){
							    					
							    					//echo '<br>ACTUALIZAR A INACTIVO EL REGISTRO'.$llaveOperacion;
							    					$cro->eliminarOperacionOrganicoXidOperacion($conexion, $llaveOperacion);
							    					
							    				}
							    				
							    			}
							    			
							    		}
							    		
							    	}
							    }
							}
								
						}else{
								
							$bandera = true;
							
							//echo "<<<< se guardo se guardan datos";
							$cro -> guardarRendimientoAsociacionDetalle($conexion, $idMiembroAsociacion, $llaveOperacion, $idArea, $valorOperacion['rendimiento'], $superficieSitio, $valorOperacion['superficie']);								
								
						}					
							
					}

				}else{

					foreach ($arrayOperaciones as $llaveOperacion => $valorOperacion){
					
						$qVerificarOperacionRendimiento = $cro->buscarOperacionesRendimientoXidOperacion($conexion, $llaveOperacion);
						$verificarOperacionRendimiento = pg_fetch_assoc($qVerificarOperacionRendimiento);
							
						if (pg_num_rows($qVerificarOperacionRendimiento) > 0){
					
							if($valorOperacion['superficie'] != $verificarOperacionRendimiento['superficie_miembro'] || $valorOperacion['rendimiento'] != $verificarOperacionRendimiento['rendimiento']){
									
								//echo "SE ACTUALIZA";
								
								$cro -> actualizarRendimientoAsociacionDetalle($conexion, $llaveOperacion, $valorOperacion['rendimiento'], $valorOperacion['superficie']);
					
								//Verifica si existe id_operacion en la tabla operaciones_organico.
								
								$qVerificarOperacionOrganico = $cro-> verificarOperacionOrganico($conexion, $llaveOperacion);
								
								if(pg_num_rows($qVerificarOperacionOrganico) > 0){
									//echo '<br>ACTUALIZAR A INACTIVO EL REGISTRO'.$llaveOperacion;
									$cro->eliminarOperacionOrganicoXidOperacion($conexion, $llaveOperacion);
								}
								
								$mensaje['estado'] = 'exito';
								$mensaje['mensaje'] = "Su información ha sido actualizada";
								
							}else{
								
								$mensaje['estado'] = 'exito';
								$mensaje['mensaje'] = "Su información ha sido actualizada";
								
							}							
												
						}else{
					
							//echo "se guarda";
							
							$cro -> guardarRendimientoAsociacionDetalle($conexion, $idMiembroAsociacion, $llaveOperacion, $idArea, $valorOperacion['rendimiento'], $superficieSitio, $valorOperacion['superficie']);			

							$mensaje['estado'] = 'exito';
							$mensaje['mensaje'] = "Su información ha sido actualizada";
							
						}
							
					}					

				}

			}else{
			    $banderaSupera = true;
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "El total de la supeficie por producto excede la registrada en el área " . $superficieArea . ".";
			}
				
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Debe registrar el miembro de la asociación";
		}
		
	
		if($bandera || $banderaAgencia){
			//echo "revision documental";
			//ACTUALIZA A ESTADO DE REVISION DOCUMENTAL
							
			$idflujoOperacion = pg_fetch_assoc($cro->obtenerIdFlujoXOperacion($conexion, $llaveOperacion));
			$idFlujoActual = pg_fetch_assoc($cro->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'cargarRendimiento'));
			$estado = pg_fetch_assoc($cro->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));
			
			$cro->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);
				
			switch ($estado['estado']){
				
    			case 'cargarAdjunto':
    				$cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
    			break;				
		
			}
				
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = "Su información ha sido actualizada por favor cargue sus anexos requeridos";
		
		}else{	
		    
		    if(!$banderaSupera){
		    
		    $mensaje['estado'] = 'exito';
		    $mensaje['mensaje'] = "Su información ha sido actualizada";}
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
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}