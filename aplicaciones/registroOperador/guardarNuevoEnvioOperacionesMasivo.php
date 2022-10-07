<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

$mensaje = array();

$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

set_time_limit (360);

try{
	
	$idTipoProducto=$_POST['hIdTipoProducto'];
	$idSubtipoProducto=$_POST['hIdSubtipoProducto'];
	$idProducto=$_POST['hIdProducto'];
	$idArea=$_POST['hIdArea'];
		
	$identificador = $_SESSION['usuario'];
	$idSitio=$_POST['idSitio']; 
	$idTipoOperacion=$_POST['tipoOperacion'];
	$idFlujo=$_POST['idFlujo'];
	
	$registro = unserialize(base64_decode($_POST['registro']));
		
	try {
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		$cr = new ControladorRegistroOperador();
		$crs = new ControladorRevisionSolicitudesVUE();
	
		$datos = array();
	
		for($i=0; $i<count($registro); $i++){
			
			$datos= array('idTipoProducto'=>$registro[$i]['idTipoProducto'],'idSubtipoProducto'=>$registro[$i]['idSubtipoProducto'],
					'idProducto'=>$registro[$i]['idProducto'],'idArea'=>$registro[$i]['idArea'],'idSitio'=>$idSitio,
					'idTipoOperacion'=>$idTipoOperacion,'idFlujo'=>$idFlujo);
			
			$ingreso = true;
			$areasOperacion = '';
			
			$areasTipoOperacion = $cc -> obtenerAreasXtipoOperacion($conexion, $datos['idTipoOperacion']);
			
			foreach ($areasTipoOperacion as $areaOperacion){
				
				$vAreaProductoOperacion = $cr->buscarAreasOperacionProductoXSolicitud($conexion, $datos['idTipoOperacion'], '('.$datos['idProducto'].')',$_POST[$areaOperacion['codigo']], $identificador);
				$tmpArea[] = $_POST[$areaOperacion['codigo']];
				if(pg_num_rows($vAreaProductoOperacion)!= 0){
					$ingreso = false;
				}
			}
			
			if($ingreso){
					
				$valores = array();
				$resultado = array();
			
				$qProducto = $cc->obtenerNombreProducto($conexion, $datos['idProducto']);
				$qOperacion = pg_fetch_assoc($cc->obtenerDatosTipoOperacion($conexion, $datos['idTipoOperacion']));
				$qSitio = $cr->abrirSitio($conexion, $datos['idSitio']);
					
				$qIdSolicitud= $cr->guardarNuevaOperacion($conexion, $datos['idTipoOperacion'], $identificador, $datos['idProducto'], pg_fetch_result($qProducto, 0, 'nombre_comun'));
				$idSolicitud = pg_fetch_assoc($qIdSolicitud);
				
				foreach ($areasTipoOperacion as $areaOperacion){
						
					$idAreas = $cr->guardarAreaOperacion($conexion, $_POST[$areaOperacion['codigo']], $idSolicitud['id_operacion']);
					$datosArea = pg_fetch_assoc($cr->ObtenerDatosAreaOperador($conexion, $_POST[$areaOperacion['codigo']]));
						
				}
					
				//TODO: VAMOS A CONSULTAR CON EL ID DE PRODUCTO Y EL ID TIPOOPERACION A LA TABLA DE PRODUCTO_MULTIPLE_VARIEDADES
				$variedad = $cr->buscarVariedadOperacionProducto($conexion, $datos['idTipoOperacion'] , $datos['idProducto']);
				$valores[] = (pg_num_rows($variedad) == '0'?'flujoNormal':'variedad');
					
				$resultado = array_unique($valores);
					
				if(count($resultado) == 1){
					if($resultado[0]=='flujoNormal'){
						$estadoFlujo = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $datos['idFlujo'], '1'));
			
						switch ($estadoFlujo['estado']){
								
							case 'cargarAdjunto':
								$res = $cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],$estadoFlujo['estado']);
								break;
							case 'inspeccion':
								$res = $cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],$estadoFlujo['estado']);
								break;
							case'registrado':
								$fechaActual = date('Y-m-d H-i-s');
								$cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],'registrado', 'No se realizó proceso de inspección, ni cobro de tasas. Proceso ejecutado por sistema GUIA '.$fechaActual.' en base a memorando MAGAP-DSV/AGROCALIDAD-2014-001427-M');
								$cr -> cambiarEstadoAreaXidSolicitud($conexion, $idSolicitud['id_operacion'], 'registrado', 'No se realizó proceso de inspección, ni cobro de tasas. Proceso ejecutado por sistema GUIA '.$fechaActual.' en base a memorando MAGAP-DSV/AGROCALIDAD-2014-001427-M');
								break;
						}
							
						$cargarInformacion = 'FALSE';
							
					}else{
						$res = $cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],'cargarIA');
						$cargarInformacion = 'TRUE';
					}
				}else{
					$res = $cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],'cargarIA');
					$cargarInformacion = 'TRUE';
				}
									
				$areasOperacion = implode(',', $tmpArea);
				$estadoOperacion = $cr->buscarEstadoOperacionArea($conexion, $datos['idTipoOperacion'], $identificador, $areasOperacion);
				$estado = pg_fetch_assoc($estadoOperacion);
					
				//TODO: Verificar cuando ya se quite la aprobación automatica que solo sea para IAV, IAP
				if($estado['estado'] == 'registrado' && $cargarInformacion == 'FALSE' && ($areaProducto == 'IAV' || $areaProducto == 'IAP' || $areaProducto == 'IAF' || $areaProducto == 'IAPA')){
			
					$res = $cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],'registrado', 'Solicitud aprobada por sistema GUIA, operación y área se encuentran en estado registrado');
					$res = $cr -> cambiarEstadoAreaXidSolicitud($conexion, $idSolicitud['id_operacion'], 'registrado', 'Solicitud aprobada por sistema GUIA, operación y área se encuentran en estado registrado');
			
					$idGrupoAsignado= $crs->guardarNuevoInspector($conexion, 'G.U.I.A', 'G.U.I.A', 'Operadores', 'Financiero');
					$crs->guardarGrupo($conexion, $idSolicitud['id_operacion'],pg_fetch_result($idGrupoAsignado, 0, 'id_grupo'), 'Financiero');
			
					$idFinanciero = $crs->asignarMontoSolicitud($conexion, pg_fetch_result($idGrupoAsignado, 0, 'id_grupo'), 'G.U.I.A', 0, 1);
			
					$fechaActual = date('Y-m-d');
			
					$crs->guardarInspeccionFinanciero($conexion, pg_fetch_result($idFinanciero, 0, 'id_financiero'), 'G.U.I.A', 'aprobado', 'Solicitud aprobada por sistema GUIA, operación y área se encuentran en estado registrado', '0', 0, $fechaActual, '0',$numeroFactura);
			
				}
					
							
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] ='La operación, producto y área ya han sido ingresadas.';
				
			}else{
				
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'La operación, producto y área ya han sido ingresadas previamente.';
				
			}
				
		}
		
		echo json_encode($mensaje);
		$conexion->desconectar();
			
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