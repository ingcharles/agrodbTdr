<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastroProducto.php';
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cmp = new ControladorMovilizacionProductos();
	$cgap= new ControladorGestionAplicacionesPerfiles();
	$ca= new ControladorAplicaciones();
	$cu= new ControladorUsuarios();
	$cp = new ControladorCatastroProducto();
	set_time_limit(2000);

	try {
		$idMovilizacion=$_POST['idMovilizacion'];
		$identificadorOperadorOrigen=$_POST['identificacionOperadorOrigen'];
		$identificadorOperadorDestino=$_POST['identificacionOperadorDestino'];
		$sitioOrigen=$_POST['sitioOrigen'];
		$fechaFiscalizacion=$_POST['fechaFiscalizacion'];
		$resultadoFiscalizacion=$_POST['resultado'];
		$accionCorrectiva=$_POST['accionCorrectiva'];
		$observacion=$_POST['campoObservacion'];
		$estado='activo';
		$usuarioResponsable=$_POST['usuarioResponsable'];
		$motivoAnulacion=$_POST['motivoAnulacion'];
		$observacionAnulacion=$_POST['observacionAnulacion'];
		$banderaDobleGuia=$_POST['banderaDobleGuia'];
		$banderaTicket=$_POST['banderaTicket'];
		$banderaMatadero=$_POST['banderaMatadero'];
		$tipoUsuario=$_POST['tipoUsuario'];
		$banderaIdUtilizado='NO';
		$numeroFiscalizacion=$cmp->autogenerarNumerosFiscalizacionMovilizacion($conexion, $idMovilizacion);
		$lugarFiscalizacion = $_POST['lugarFiscalizacion'];
		$cantidadAnimales = $_POST['cantidadAnimales'];
		$usuarioResponsableMovilizacion = $_POST['usuarioResponsableMovilizacion'];
		$justificacion = $_POST['justificacion'];
		
	 switch ($accionCorrectiva){
	 	
	 	case 'fiscalizacion correcta':
	 		$hDetalle = $_POST['hIdDetalle'];
	 		$producto=$_POST['hProducto'];
	 		$cantidad=$_POST['hCantidad'];
	 		$idOperacionOrigen=$_POST['hOperacionOrigen'];
	 		$idOperacionDestino=$_POST['hOperacionDestino'];
	 		$areaOrigen=$_POST['hAreaOrigen'];
	 		$areaDestino=$_POST['hAreaDestino'];
	 		$unidadMedida=$_POST['hUnidadMedida'];
	 		$hDetalleC=$_POST['dIdCatastrosAgregados'];
	 		$hDetalleIC=$_POST['dIdentificadoresAgregados'];
	 		$bandera="SI";
	 		
	 		
	 		$conexion->ejecutarConsulta("begin;");
	 		if($banderaMatadero=="SI"){
	 			if($tipoUsuario=="PFL_USUAR_EXT"){
	 			foreach ($hDetalle as $key=>$valuess) {
	 				if($bandera==''){
	 					break;
	 				}
		 			$filaD=$valuess[0];
		 			foreach($hDetalleIC[$filaD] as $identificadorAgrupado) {
		 				if($bandera==''){
		 					break;
		 				}
		 				$todosIdentificadores = explode(', ',$identificadorAgrupado);
		 				foreach($todosIdentificadores as $cadaIdentificador) {
		 					$qDatosCatastroViejos=$cmp->consultarDatosCatastroValidar($conexion, $areaDestino[$filaD],$unidadMedida[$filaD], $cadaIdentificador);
		 					if(pg_num_rows($qDatosCatastroViejos)==0){
		 						$bandera='';
		 						break;
		 					}
		 				}
			 		}
		 		}
	 				

	 				if($bandera=="SI"){
	 					foreach ($hDetalle as $key=>$values) {
	 						if($banderaIdUtilizado =='SI'){
	 							break;
	 						}
	 						$filaDetalle=$values[0];
	 						foreach ($hDetalleC[$filaDetalle] as $key=>$idCatastro) {
	 							if($banderaIdUtilizado =='SI'){
	 								break;
	 							}
	 							$hDetalleICI = explode(", ", $hDetalleIC[$filaDetalle][$key]);
	 							$cantidadDetalle=count($hDetalleICI);
	 							//GUARDAR TRANSACION DESTINO (SUMA DESTINO(MATADERO))
	 							$qCantidadTotalProductoMD = $cmp->consultarCantidadTotalProducto($conexion, $idOperacionDestino [$filaDetalle], $producto[$filaDetalle],$unidadMedida [$filaDetalle],$idOperacionDestino[$filaDetalle]);
	 							$cantidadTotalMD = $cantidadDetalle + (pg_num_rows($qCantidadTotalProductoMD)!=0?pg_fetch_result($qCantidadTotalProductoMD, 0, 'cantidad_total'):0);
	 							$qConceptoCatastroMD = $cmp->consultaConceptoCatastroXCodigo ( $conexion, 'FAMA' );
	 							$cmp->guardarCatastroTransaccionResta ( $conexion,$idCatastro, $areaDestino [$filaDetalle], pg_fetch_result($qConceptoCatastroMD, 0, 'id_concepto_catastro'), $producto [$filaDetalle], $cantidadDetalle, $cantidadTotalMD, $unidadMedida [$filaDetalle], $usuarioResponsable, $idOperacionDestino[$filaDetalle]);
	 							$sentenciaActualizarDetalleCatastro='';
	 								
	 							foreach ($hDetalleICI as $identificadorIndividual) {
	 								$qIdDetalleCatastro=$cmp->consultarIdentificadoresIdCatastroFiscalizacion($conexion, $identificadorIndividual);
	 								if(pg_num_rows($qIdDetalleCatastro)!=0){
	 								$filaDetalleCatastro=pg_fetch_assoc($qIdDetalleCatastro);
	 								$sentenciaActualizarDetalleCatastro.="UPDATE
										 										g_catastro.detalle_catastros
										 									SET
										 										estado_registro='eliminado',
                                                                                observacion = 'Eliminado por fiscalización en matadero'
										 									WHERE
										 										id_catastro='".$filaDetalleCatastro['id_catastro']."' and
										 										case when identificador_producto is null then  identificador_unico_producto else identificador_producto end ='".$identificadorIndividual."'; ";
	 								}else{
	 									$banderaIdUtilizado='SI';
	 									break;
	 								}
	 							}
	 							if($banderaIdUtilizado=='NO'){
	 								$cmp->actualizarEstadoRegistroDetalleCatastro($conexion,$sentenciaActualizarDetalleCatastro);
	 							}else{
	 								$conexion->ejecutarConsulta("rollback;");
	 								$mensaje['mensaje'] = 'Algún identificador ya fue fiscalizado anteriormente';
	 							}
	 						}
	 					}
	 				
	 					$cmp->guardarNuevoFiscalizacion($conexion, $idMovilizacion, $numeroFiscalizacion, $fechaFiscalizacion, $resultadoFiscalizacion, $accionCorrectiva, $estado, $usuarioResponsable, $observacion, $lugarFiscalizacion, $cantidadAnimales);
		 					$cmp->actualizarEstadoFiscalizacionMovilizacion($conexion, $idMovilizacion, 'Fiscalizado en matadero');
		 					
		 					$mensaje['estado'] = 'exito';
		 					$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
	 					
	 				}else{
	 					$mensaje['estado'] = 'error';
	 					$mensaje['mensaje'] = "Algun producto ya no existe en el destino";
	 				}
	 			}else if($tipoUsuario=="PFL_USUAR_INT"){
	 			    $cmp->guardarNuevoFiscalizacion($conexion, $idMovilizacion, $numeroFiscalizacion, $fechaFiscalizacion, $resultadoFiscalizacion, $accionCorrectiva, $estado, $usuarioResponsable, $observacion, $lugarFiscalizacion, $cantidadAnimales);
	 				$cmp->actualizarEstadoFiscalizacionMovilizacion($conexion, $idMovilizacion, 'Fiscalizado');
	 				$mensaje['estado'] = 'exito';
	 				$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
	 			}
	 		}else{
	 		    $cmp->guardarNuevoFiscalizacion($conexion, $idMovilizacion, $numeroFiscalizacion, $fechaFiscalizacion, $resultadoFiscalizacion, $accionCorrectiva, $estado, $usuarioResponsable, $observacion, $lugarFiscalizacion, $cantidadAnimales);
	 			$cmp->actualizarEstadoFiscalizacionMovilizacion($conexion, $idMovilizacion, 'Fiscalizado');
	 			$mensaje['estado'] = 'exito';
	 			$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
	 		}

	 		$conexion->ejecutarConsulta("commit;");
	 	break;

	 	case 'modificar certificado':

	 		require_once '../../clases/ControladorReportes.php';
	 		$jru = new ControladorReportes();

	 		$identificadoresAgregadoss=$_POST['identificadoresAgregados'];
	 		$identificadoresAgregadosTodos =rtrim ( $identificadoresAgregadoss,',');
	 		$codigoDetalleMovilizacion=$_POST['hIdDetallee'];
	 		$cantidadActual=$_POST['hCantidadd'];
	 		$cantidadAntigua=$_POST['hCantidadDetalle'];
	 		$idProducto=$_POST['hIdProducto'];
	 		$totalProductos=htmlspecialchars ($_POST['totalProductos'],ENT_NOQUOTES,'UTF-8');
	 		$bandera="SI";

	 		
	 		$rutatTicket=pg_fetch_result($cmp->obtenerRutaTicketMovilizacion($conexion, $idMovilizacion),0,'ruta_ticket');
	 		$banderaTicket=$rutatTicket!=''?'SI':'';

	 		for ($i = 0; $i < count ($codigoDetalleMovilizacion); $i++) {
	 			if($bandera==''){
	 				break;
	 			}
	 			if($cantidadAntigua[$i]!=$cantidadActual[$i]){
			 		$qOrigenDestinoMovilizacions=$cmp->consultarDatosOrigenDestinoMovilizacion($conexion,  "(".$identificadoresAgregadosTodos.")",$codigoDetalleMovilizacion[$i]);
			 		while($filax=pg_fetch_assoc($qOrigenDestinoMovilizacions)){
			 			$qDatosCatastroViejos=$cmp->consultarDatosCatastroValidar($conexion, $filax['area_destino'],$filax['unidad_comercial'], $filax['identificador']);
			 			if(pg_num_rows($qDatosCatastroViejos)==0){
			 				$bandera='';
			 				break;
			 			}
			 		}
	 			}
	 			
	 		}
	 		
	 		if($bandera=="SI"){
	 			if($banderaDobleGuia!='SI'){
	 				$conexion->ejecutarConsulta("begin;");
	 				for ($i = 0; $i < count ($codigoDetalleMovilizacion); $i++) {
	 					if($banderaIdUtilizado =='SI'){
	 						break;
	 					}
	 					if($cantidadAntigua[$i]!=$cantidadActual[$i]){
	 						$cantidad=$cantidadAntigua[$i]-$cantidadActual[$i];

	 						if($identificadorOperadorOrigen!=$identificadorOperadorDestino){
	 						$idProductoReproduccion=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORDRE'),0,'id_producto');
	 						$idProductoLechon=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORHON'),0,'id_producto');
	 						$idProductoLechona=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORONA'),0,'id_producto');

	 						//$idSitioOrigen=pg_fetch_result($cmp->obtenerRutaCertificadoMovilizacion($conexion, $idMovilizacion),0,'sitio_origen');
	 						//$identificadorOperadorOrigen=pg_fetch_result($cp->abrirSitio($conexion, $idSitioOrigen), 0, 'identificador_operador');
	 						$qObtenerMaximoControlReproduccionOrigen=$cp->obtenerMaximoControlReproduccion($conexion, $identificadorOperadorOrigen,$idProductoReproduccion);
	 						$qCantidadCatastroOrigen=$cp->obtenerCantidadCatastroXOperador($conexion, $identificadorOperadorOrigen, '('.$idProductoReproduccion.')');
	 						$qCantidadCatastroCriasOrigen=$cp->obtenerCantidadCatastroXOperador($conexion,  $identificadorOperadorOrigen, '('.$idProductoLechon.','.$idProductoLechona.')');
	 						$cantidadCriaOrigen=pg_fetch_result($qCantidadCatastroCriasOrigen, 0, 'cantidad');

	 						//$idSitioDestino=pg_fetch_result($cmp->obtenerRutaCertificadoMovilizacion($conexion, $idMovilizacion),0,'sitio_destino');
	 						//$identificadorOperadorDestino=pg_fetch_result($cp->abrirSitio($conexion, $idSitioDestino), 0, 'identificador_operador');
	 						$qObtenerMaximoControlReproduccionDestino=$cp->obtenerMaximoControlReproduccion($conexion, $identificadorOperadorDestino,$idProductoReproduccion);
	 						$qCantidadCatastroDestino=$cp->obtenerCantidadCatastroXOperador($conexion,  $identificadorOperadorDestino, '('.$idProductoReproduccion.')');
	 						$qCantidadCatastroCriasDestino=$cp->obtenerCantidadCatastroXOperador($conexion,  $identificadorOperadorDestino, '('.$idProductoLechon.','.$idProductoLechona.')');
	 						$cantidadCriaDestino=pg_fetch_result($qCantidadCatastroCriasDestino, 0, 'cantidad');

	 						if($idProducto[$i]==$idProductoReproduccion){
	 							//origen suma
	 							$cantidadReproduccion=$cantidad*28;
	 							if (pg_num_rows($qObtenerMaximoControlReproduccionOrigen)!=0){
	 								$cupoCria=pg_fetch_result($qObtenerMaximoControlReproduccionOrigen, 0, 'cupo_cria')+$cantidadReproduccion;
	 								$cantidadCriaB=pg_fetch_result($qObtenerMaximoControlReproduccionOrigen, 0, 'cantidad_cria');
	 							}else{
	 								$cupoCria=(pg_fetch_result($qCantidadCatastroOrigen, 0, 'cantidad')*28)+$cantidadReproduccion;
	 								$cantidadCriaB=$cantidadCriaOrigen;
	 							}

	 							if($cupoCria<0)
	 								$cupoCria=0;

	 							$cp->guardarControlReproduccion($conexion, $identificadorOperadorOrigen, $idProductoReproduccion, $cupoCria,$cantidadCriaB);

	 							//destino resta
	 							if (pg_num_rows($qObtenerMaximoControlReproduccionDestino)!=0){
	 								$cupoCria=pg_fetch_result($qObtenerMaximoControlReproduccionDestino, 0, 'cupo_cria')-$cantidadReproduccion;
	 								$cantidadCriaB=pg_fetch_result($qObtenerMaximoControlReproduccionDestino, 0, 'cantidad_cria');
	 									
	 							}else{
	 								$cupoCria=pg_fetch_result($qCantidadCatastroCriasDestino, 0, 'cantidad')+(pg_fetch_result($qCantidadCatastroDestino, 0, 'cantidad')*28)-$cantidadReproduccion;
	 								$cantidadCriaB=$cantidadCriaDestino;
	 							}
	 							if($cupoCria<0)
	 								$cupoCria=0;

	 							$cp->guardarControlReproduccion($conexion, $identificadorOperadorDestino, $idProductoReproduccion, $cupoCria,$cantidadCriaB);
	 						}
	 						}
	 						
	 						$cmp->actualizarCantidadDetalleMovilizacion($conexion, $codigoDetalleMovilizacion[$i], $cantidadActual[$i]);

	 						$qOrigenDestinoMovilizacion=$cmp->consultarDatosOrigenDestinoMovilizacion($conexion,  "(" . $identificadoresAgregadosTodos . ")",$codigoDetalleMovilizacion[$i]);
	 						if(pg_num_rows($qOrigenDestinoMovilizacion)>0){
	 							$sentenciaDetalleCatastro='INSERT INTO	g_catastro.detalle_catastros(id_catastro, identificador_producto, identificador_unico_producto ,estado_registro) VALUES ';
	 							$sentenciaActualizarDetalleCatastro='';
	 							while($fila=pg_fetch_assoc($qOrigenDestinoMovilizacion)){
	 									
	 								$qDatosCatastroViejo=$cmp->consultarDatosCatastro($conexion, $fila['area_destino'],  $fila['unidad_comercial'], $fila['identificador']);
	 								$filas=pg_fetch_assoc($qDatosCatastroViejo);
	 								$idCatastroNuevo=$cmp->guardarCatastroProducto($conexion,  $fila['sitio_origen'],$fila['area_origen'], $filas['id_producto'], 1, $usuarioResponsable
	 										, $filas['unidad_medida_peso'], $filas['fecha_modificacion_etapa'],  $filas['unidad_comercial'], $filas['nombre_producto']
	 										, $filas['peso'], $filas['id_especie'], $filas['fecha_nacimiento'], $filas['numero_lote'], $filas['estado_etapa'],$fila['tipo_operacion_origen']);
	
	 								$idCatastroNuevo=pg_fetch_result ( $idCatastroNuevo, 0, 'id_catastro' );
	 									
	 								$identificadorUnicoProducto=$filas['identificador_unico_producto'];
	 								$identificadorProducto=$filas['identificador_producto'];
	 								$identificadorProductoDos=$identificadorProducto != '' ? $identificadorProducto : $identificadorUnicoProducto;
	 								$identificadorProducto = $identificadorProducto != '' ? "'$identificadorProducto'" : 'null';
	 								$sentenciaDetalleCatastro.="($idCatastroNuevo,$identificadorProducto,'$identificadorUnicoProducto','$filas[estado_registro]'),";
		 							$sentenciaActualizarDetalleCatastro.="UPDATE
									 											g_catastro.detalle_catastros
									 										SET
									 											estado_registro='eliminado',
                                                                                observacion = 'Eliminado por fiscalización en matadero'
									 										WHERE
									 											id_catastro='".$filas['id_catastro']."' and
									 											case when identificador_producto is null then  identificador_unico_producto else identificador_producto end ='".$identificadorProductoDos."'; ";

		 							//GUARDAR TRANSACION ORIGEN (RESTA ORIGEN)
		 							$qConceptoCatastroMO = $cmp->consultaConceptoCatastroXCodigo( $conexion, 'MOMO' );
		 							$qCantidadTotalProductoMO = $cmp->consultarCantidadTotalProducto($conexion, $fila ['area_destino'], $fila ['producto'],$fila ['unidad_comercial'],$fila ['tipo_operacion_destino']);
		 							$cantidadTotalMO = (pg_num_rows($qCantidadTotalProductoMO)!=0?pg_fetch_result($qCantidadTotalProductoMO, 0, 'cantidad_total'):0) - 1 ;
		 							$cmp->guardarCatastroTransaccionResta($conexion,$filas['id_catastro'],$fila ['area_destino'], pg_fetch_result($qConceptoCatastroMO, 0, 'id_concepto_catastro'),   $fila ['producto'] , 1 , $cantidadTotalMO, $fila ['unidad_comercial'],$usuarioResponsable, $fila ['tipo_operacion_destino']);

		 							//GUARDAR TRANSACION DESTINO (SUMA DESTINO)
		 							$qCantidadTotalProductoMD = $cmp->consultarCantidadTotalProducto($conexion, $fila ['area_origen'], $fila ['producto'],$fila ['unidad_comercial'],$fila ['tipo_operacion_origen']);
		 							$cantidadTotalMD = 1 + (pg_num_rows($qCantidadTotalProductoMD)!=0?pg_fetch_result($qCantidadTotalProductoMD, 0, 'cantidad_total'):0);
		 							$qConceptoCatastroMD = $cmp->consultaConceptoCatastroXCodigo ( $conexion, 'MOMD' );
		 							$cmp->guardarCatastroTransaccion ( $conexion,$idCatastroNuevo, $fila ['area_origen'], pg_fetch_result($qConceptoCatastroMD, 0, 'id_concepto_catastro'), $fila ['producto'], 1, $cantidadTotalMD, $fila ['unidad_comercial'], $usuarioResponsable, $fila ['tipo_operacion_origen']);
	 							}
	 							$cmp->actualizarEstadoRegistroDetalleCatastro($conexion,$sentenciaActualizarDetalleCatastro);
	 							$cmp->guardarDetalleCatastroProducto($conexion, $sentenciaDetalleCatastro);
	 						}else{
	 							$banderaIdUtilizado='SI';
	 							break;
	 						}
	 					}
	 					$cmp->eliminarDetalleIdentificadorTicketMovilizacion($conexion,$codigoDetalleMovilizacion[$i] ,"(" . $identificadoresAgregadosTodos . ")");
	 					$cmp->eliminarDetalleIdentificadorMovilizacion($conexion,$codigoDetalleMovilizacion[$i], "(" . $identificadoresAgregadosTodos . ")");
	 				}
	 			
	 				$cmp->actualizarCantidadTotalCertificadoMovilizacion($conexion,$idMovilizacion,$totalProductos);

	 				//$idSitioOrigen=pg_fetch_result($cmp->obtenerRutaCertificadoMovilizacion($conexion, $idMovilizacion),0,'sitio_origen');
	 				//$identificadorOperador=pg_fetch_result($cmp->abrirSitio($conexion, $idSitioOrigen), 0, 'identificador_operador');
	 				
	 				$qMaximoFiscalizacion=$cmp->obtenerMaximoFiscalizacionOperador($conexion, $identificadorOperadorOrigen);
	 				$activarModulo=false;
	 				$idMovilizacionFiscalizacion=pg_fetch_result($qMaximoFiscalizacion, 0, 'id_movilizacion_fiscalizacion');
		 			if($idMovilizacionFiscalizacion!=NULL){
		 				if(pg_num_rows($cmp->consultarFiscalizacionAccionCorrectiva($conexion,$idMovilizacionFiscalizacion, 'inactivar emision de certificado'))!=0){
		 					$activarModulo=true;
		 				}
		 			}
	 				
	 				$cmp->guardarNuevoFiscalizacion($conexion, $idMovilizacion, $numeroFiscalizacion, $fechaFiscalizacion, $resultadoFiscalizacion, $accionCorrectiva, $estado, $usuarioResponsable, $observacion, $lugarFiscalizacion, $cantidadAnimales);
	 				$cmp->actualizarEstadoFiscalizacionMovilizacion($conexion, $idMovilizacion, 'Fiscalizado');
	 					
	 				
	 				if($banderaIdUtilizado=='NO'){
		 				$conexion->ejecutarConsulta("commit;");
		 				$mensaje['estado'] = 'exito';
		 				$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
	 				}else{
	 					$conexion->ejecutarConsulta("rollback;");
	 					$mensaje['mensaje'] = 'Algún identificador ya fue fiscalizado anteriormente';
	 						
	 				}
	 				
	 				$rutaCertificado=pg_fetch_result($cmp->obtenerRutaCertificadoMovilizacion($conexion, $idMovilizacion),0,'ruta_certificado');
	 				$rutaCertificadoNuevo=str_replace("aplicaciones/movilizacionProducto/documentos/guias/","",$rutaCertificado);
	 				unlink('documentos/guias/'.$rutaCertificadoNuevo);
	 					
	 				$ReporteJasper='aplicaciones/movilizacionProducto/reportes/reporteMovilizacion.jrxml';
	 				
	 				$parameters['parametrosReporte'] = array(
	 					'id_movilizacion'=> (int)  $idMovilizacion
	 				);
	 				
	 				$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$rutaCertificado,'logoMovilizacion');
	 				
	 				
	 				if($banderaTicket=='SI'){
	 					$rutaTicketNuevo=str_replace("aplicaciones/movilizacionProducto/documentos/ticket/","",$rutatTicket);
	 					unlink('documentos/ticket/'.$rutaTicketNuevo);
	 					$ReporteJasperTicket='aplicaciones/movilizacionProducto/reportes/reporteTicket.jrxml';
	 					
	 					$parametersTicket['parametrosReporte'] = array(
	 						'id_movilizacion'=> (int)  $idMovilizacion
	 					);
	 					
	 					$jru->generarReporteJasper($ReporteJasperTicket,$parametersTicket,$conexion,$rutatTicket,'logoMovilizacionTicket');
	 				}

	 				
	 					
	 				if($activarModulo){
	 					//agregar modulo de movilizacion si tiene el operador tiene como ultima fiscarlizacion inacivar movilizacion
	 					$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_MOVIL_PRODU')");
	 					while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
	 						$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_EMISO_MOVIL')");
	 						$perfilesArray=Array();
	 						while($fila=pg_fetch_assoc($qGrupoPerfiles)){
	 							$perfilesArray[]=array(idPerfil=>$fila['id_perfil'],codigoPerfil=>$fila['codificacion_perfil']);
	 						} 							
	 						if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $identificadorOperador))==0){
	 							$qAplicacionVacunacion=$cgap->guardarGestionAplicacion($conexion, $identificadorOperador,$filaAplicacion['codificacion_aplicacion']);
	 							foreach( $perfilesArray as $datosPerfil){
	 								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $identificadorOperador);
	 								if (pg_num_rows($qPerfil) == 0)
	 									$cgap->guardarGestionPerfil($conexion, $identificadorOperador,$datosPerfil['codigoPerfil']);
	 							}
	 						}else{
	 							foreach( $perfilesArray as $datosPerfil){
	 								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $identificadorOperador);
	 								if (pg_num_rows($qPerfil) == 0)
	 									$cgap->guardarGestionPerfil($conexion, $identificadorOperador,$datosPerfil['codigoPerfil']);
	 							}
	 						}
	 					}
	 				}

	 				
	 			}

	 			if($banderaDobleGuia=='SI'){
	 				$conexion->ejecutarConsulta("begin;");
	 				$idMovilizacionDos=pg_fetch_result($cmp->obtenerIdVueltaCertificadoMovilizacion($conexion, $idMovilizacion), 0, 'id_movilizacion_dos');
	 				$qCantidadDetalleDobleGuia=$cmp->cantidadDetalleMovilizacion($conexion, $idMovilizacionDos, "(".$identificadoresAgregadosTodos.")" );
	 				while($filaDetalle=pg_fetch_assoc($qCantidadDetalleDobleGuia)){
	 					$cantidadNueva=  $filaDetalle['cantidad']- $filaDetalle['cantidad_restada'];
	 					$cmp->actualizarCantidadDetalleMovilizacion($conexion, $filaDetalle['id_detalle_movilizacion'], $cantidadNueva);
	 				}

	 				$qIdDetalleIdentificadores=$cmp->consultarIdDetalleIdentificadoresProducto($conexion, $idMovilizacionDos, "(".$identificadoresAgregadosTodos.")");
	 				while($filaId=pg_fetch_assoc($qIdDetalleIdentificadores)){
	 					$cmp->eliminarDetalleIdentificadorTicketMovilizacion($conexion,$filaId['id_detalle_movilizacion'], "(" . $identificadoresAgregadosTodos . ")");
	 					$cmp->eliminarDetalleIdentificadorMovilizacion($conexion, $filaId['id_detalle_movilizacion'],"(" . $identificadoresAgregadosTodos . ")");
	 				}

	 				for ($i = 0; $i < count ($codigoDetalleMovilizacion); $i++) {
	 					if($cantidadAntigua[$i]!=$cantidadActual[$i]){
	 						$cmp->actualizarCantidadDetalleMovilizacion($conexion, $codigoDetalleMovilizacion[$i], $cantidadActual[$i]);
	 						$cmp->eliminarDetalleIdentificadorTicketMovilizacion($conexion,$codigoDetalleMovilizacion[$i], "(" . $identificadoresAgregadosTodos . ")");
	 						$cmp->eliminarDetalleIdentificadorMovilizacion($conexion, $codigoDetalleMovilizacion[$i],"(" . $identificadoresAgregadosTodos . ")");
	 					}
	 				}

	 				$cmp->actualizarCantidadTotalCertificadoMovilizacion($conexion,$idMovilizacion,$totalProductos);
	 				$cmp->actualizarCantidadTotalCertificadoMovilizacion($conexion,$idMovilizacionDos,$totalProductos);

	 				//$idSitioOrigen=pg_fetch_result($cmp->obtenerRutaCertificadoMovilizacion($conexion, $idMovilizacion),0,'sitio_origen');
	 				//$identificadorOperador=pg_fetch_result($cmp->abrirSitio($conexion, $idSitioOrigen), 0, 'identificador_operador');

	 				$cmp->guardarNuevoFiscalizacion($conexion, $idMovilizacion, $numeroFiscalizacion, $fechaFiscalizacion, $resultadoFiscalizacion, $accionCorrectiva, $estado, $usuarioResponsable, $observacion, $lugarFiscalizacion, $cantidadAnimales);
	 				$cmp->actualizarEstadoFiscalizacionMovilizacion($conexion, $idMovilizacion, 'Fiscalizado');

	 				$qMaximoFiscalizacion=$cmp->obtenerMaximoFiscalizacionOperador($conexion, $identificadorOperadorOrigen);
	 				$activarModulo=false;
	 				$idMovilizacionFiscalizacion=pg_fetch_result($qMaximoFiscalizacion, 0, 'id_movilizacion_fiscalizacion');
		 			if($idMovilizacionFiscalizacion!=NULL){
		 				if(pg_num_rows($cmp->consultarFiscalizacionAccionCorrectiva($conexion, $idMovilizacionFiscalizacion, 'inactivar emision de certificado'))!=0){
		 					$activarModulo=true;
		 				}
	 				}
	 				
	 				$numeroFiscalizacionDobleGuia=$cmp->autogenerarNumerosFiscalizacionMovilizacion($conexion, $idMovilizacionDos);
	 				$cmp->guardarNuevoFiscalizacion($conexion, $idMovilizacionDos, $numeroFiscalizacionDobleGuia, $fechaFiscalizacion, $resultadoFiscalizacion, $accionCorrectiva, $estado, $usuarioResponsable, $observacion, $lugarFiscalizacion, $cantidadAnimales);
	 				$cmp->actualizarEstadoFiscalizacionMovilizacion($conexion, $idMovilizacionDos, 'Fiscalizado');
	 					
	 				if($activarModulo){
	 					$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_MOVIL_PRODU')");
	 					while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
	 						$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_EMISO_MOVIL')");
	 						$perfilesArray=Array();
	 						while($fila=pg_fetch_assoc($qGrupoPerfiles)){
	 							$perfilesArray[]=array(idPerfil=>$fila['id_perfil'],codigoPerfil=>$fila['codificacion_perfil']);
	 						}		
	 						if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $identificadorOperadorOrigen))==0){
	 							$qAplicacionVacunacion=$cgap->guardarGestionAplicacion($conexion, $identificadorOperadorOrigen,$filaAplicacion['codificacion_aplicacion']);
		 						foreach( $perfilesArray as $datosPerfil){
		 							$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $identificadorOperadorOrigen);
		 							if (pg_num_rows($qPerfil) == 0)
		 								$cgap->guardarGestionPerfil($conexion, $identificadorOperadorOrigen,$datosPerfil['codigoPerfil']);
		 						}
	 						}else{
	 							foreach( $perfilesArray as $datosPerfil){
	 								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $identificadorOperadorOrigen);
	 								if (pg_num_rows($qPerfil) == 0)
	 									$cgap->guardarGestionPerfil($conexion, $identificadorOperadorOrigen,$datosPerfil['codigoPerfil']);
	 							}
	 						}
	 					}
	 				}

	 				$conexion->ejecutarConsulta("commit;");

	 				$rutaCertificado=pg_fetch_result($cmp->obtenerRutaCertificadoMovilizacion($conexion, $idMovilizacion),0,'ruta_certificado');
	 				$rutaCertificadoNuevo=str_replace("aplicaciones/movilizacionProducto/documentos/guias/","",$rutaCertificado);
	 				unlink('documentos/guias/'.$rutaCertificadoNuevo);

	 				$ReporteJasper='aplicaciones/movilizacionProducto/reportes/reporteMovilizacion.jrxml';
	 				
	 				$parameters['parametrosReporte'] = array(
	 					'id_movilizacion'=> (int)  $idMovilizacion
	 				);
	 				
	 				$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$rutaCertificado,'logoMovilizacion');
	 					
	 				$rutaCertificadoDos=pg_fetch_result($cmp->obtenerRutaCertificadoMovilizacion($conexion, $idMovilizacionDos),0,'ruta_certificado');
	 				$rutaCertificadoNuevoDos=str_replace("aplicaciones/movilizacionProducto/documentos/guias/","",$rutaCertificadoDos);
	 				unlink('documentos/guias/'.$rutaCertificadoNuevoDos);
	 				
	 				$parametersDos['parametrosReporte'] = array(
	 					'id_movilizacion'=> (int)  $idMovilizacionDos
	 				);
	 				
	 				$jru->generarReporteJasper($ReporteJasper,$parametersDos,$conexion,$rutaCertificadoDos,'logoMovilizacion');
	 				
	 				$anio = date('Y');
	 				$mes = date('m');
	 				$dia = date('d');
	 				
	 				define('RUTA_GUIAS', 'aplicaciones/movilizacionProducto/documentos/guias/' . $anio . '/' . $mes . '/' . $dia . '/');
	 					 				
	 				include '../general/PDFMerger.php';
	 				$pdf = new PDFMerger();
	 				
	 				$tempFileName=$rutaCertificadoNuevo.'.tmp.pdf';
	 				$pdf->addPDF('documentos/guias/'.$rutaCertificadoNuevo, 'all')
	 				->addPDF('documentos/guias/'.$rutaCertificadoNuevoDos, 'all')
	 				->merge('file', $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' . RUTA_GUIAS . '/' .$tempFileName);

	 				unlink('documentos/guias/'.$rutaCertificadoNuevo);
	 				unlink('documentos/guias/'.$rutaCertificadoNuevoDos);

	 				$pdf = new PDFMerger();
	 				$pdf->addPDF('documentos/guias/'.$tempFileName, 'all')
	 				->merge('file', $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' . RUTA_GUIAS . '/' . $rutaCertificadoNuevoDos);

	 				$pdf = new PDFMerger();
	 				$pdf->addPDF('documentos/guias/'.$tempFileName, 'all')
	 				->merge('file', $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' . RUTA_GUIAS.'/'.$rutaCertificadoNuevo);
	 				unlink('documentos/guias/'.$tempFileName);

	 				$mensaje['estado'] = 'exito';
	 				$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
	 			}
	 		}else{
	 			$mensaje['estado'] = 'error';
	 			$mensaje['mensaje'] = "Algun producto ya no existe en el destino";
	 		}
	 	break;

	 	case 'inactivar emision de certificado':

	 		$conexion->ejecutarConsulta("begin;");
	 		//$idSitioOrigen=pg_fetch_result($cmp->obtenerRutaCertificadoMovilizacion($conexion, $idMovilizacion),0,'sitio_origen');
	 		//$identificadorOperador=pg_fetch_result($cmp->abrirSitio($conexion, $idSitioOrigen), 0, 'identificador_operador');
	 		$idPerfilEmisorMovilizacion=pg_fetch_result($ca->obtenerIdPerfil($conexion, 'PFL_EMISO_MOVIL'), 0, 'id_perfil');

	 		$cu->eliminarPerfilUsuario($conexion, $usuarioResponsableMovilizacion, $idPerfilEmisorMovilizacion);
	 		$cmp->guardarNuevoFiscalizacion($conexion, $idMovilizacion, $numeroFiscalizacion, $fechaFiscalizacion, $resultadoFiscalizacion, $accionCorrectiva, $estado, $usuarioResponsable, $observacion, $lugarFiscalizacion, $cantidadAnimales);
	 		$cmp->actualizarEstadoFiscalizacionMovilizacion($conexion, $idMovilizacion, 'Fiscalizado');

	 		$conexion->ejecutarConsulta("commit;");
	 		$mensaje['estado'] = 'exito';
	 		$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
	 		break;
	 			
	 	case 'anular certificado':
	 		$hDetalle = $_POST['hIdDetalle'];
	 		$producto=$_POST['hProducto'];
	 		$cantidad=$_POST['hCantidad'];
	 		$idOperacionOrigen=$_POST['hOperacionOrigen'];
	 		$idOperacionDestino=$_POST['hOperacionDestino'];
	 		$areaOrigen=$_POST['hAreaOrigen'];
	 		$areaDestino=$_POST['hAreaDestino'];
	 		$unidadMedida=$_POST['hUnidadMedida'];
	 		$hDetalleC=$_POST['dIdCatastrosAgregados'];
	 		$hDetalleIC=$_POST['dIdentificadoresAgregados'];
	 		$bandera="SI";
	 		
			 foreach ($hDetalle as $key=>$valuess) {
				 	if($bandera==''){
				 		break;
				 	}
		 			$filaD=$valuess[0];
		 			foreach($hDetalleIC[$filaD] as $identificadorAgrupado) {
		 				if($bandera==''){
		 					break;
		 				}
		 				$todosIdentificadores = explode(', ',$identificadorAgrupado);
		 				foreach($todosIdentificadores as $cadaIdentificador) {
		 					$qDatosCatastroViejos=$cmp->consultarDatosCatastroValidar($conexion, $areaDestino[$filaD],$unidadMedida[$filaD], $cadaIdentificador);
		 					if(pg_num_rows($qDatosCatastroViejos)==0){
		 						$bandera='';
		 						break;
		 					}
		 				}
			 		}
		 		}

	 		if($banderaDobleGuia=='SI')
	 			$bandera="SI";

	 		if($bandera=="SI"){
	 			if($banderaDobleGuia!='SI'){
	 				$conexion->ejecutarConsulta("begin;");
	 				$idProductoReproduccion=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORDRE'),0,'id_producto');
	 				$idProductoLechon=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORHON'),0,'id_producto');
	 				$idProductoLechona=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORONA'),0,'id_producto');
	 					
	 				//$idSitioOrigen=pg_fetch_result($cmp->obtenerRutaCertificadoMovilizacion($conexion, $idMovilizacion),0,'sitio_origen');
	 				//$identificadorOperadorOrigen=pg_fetch_result($cp->abrirSitio($conexion, $idSitioOrigen), 0, 'identificador_operador');

	 				//$idSitioDestino=pg_fetch_result($cmp->obtenerRutaCertificadoMovilizacion($conexion, $idMovilizacion),0,'sitio_destino');
	 				//$identificadorOperadorDestino=pg_fetch_result($cp->abrirSitio($conexion, $idSitioDestino), 0, 'identificador_operador');

	 				foreach ($hDetalle as $key=>$values) {
	 					if($banderaIdUtilizado =='SI'){
	 						break;
	 					}
	 					$qObtenerMaximoControlReproduccionOrigen=$cp->obtenerMaximoControlReproduccion($conexion, $identificadorOperadorOrigen,$idProductoReproduccion);
	 					$qCantidadCatastroCriasOrigen=$cp->obtenerCantidadCatastroXOperador($conexion, $identificadorOperadorOrigen, '('.$idProductoLechon.','.$idProductoLechona.')');
	 					$qCantidadCatastroOrigen=$cp->obtenerCantidadCatastroXOperador($conexion, $identificadorOperadorOrigen, '('.$idProductoReproduccion.')');

	 					$qObtenerMaximoControlReproduccionDestino=$cp->obtenerMaximoControlReproduccion($conexion, $identificadorOperadorDestino,$idProductoReproduccion);
	 					$qCantidadCatastroCriasDestino=$cp->obtenerCantidadCatastroXOperador($conexion, $identificadorOperadorDestino, '('.$idProductoLechon.','.$idProductoLechona.')');
	 					$qCantidadCatastroDestino=$cp->obtenerCantidadCatastroXOperador($conexion,  $identificadorOperadorDestino, '('.$idProductoReproduccion.')');

	 					$filaDetalle=$values[0];

	 					if($producto[$filaDetalle]==$idProductoReproduccion){
	 						//origen suma
	 						$cantidadReproduccion=$cantidad[$filaDetalle]*28;
	 						if (pg_num_rows($qObtenerMaximoControlReproduccionOrigen)!=0){
	 							$cupoCria=pg_fetch_result($qObtenerMaximoControlReproduccionOrigen, 0, 'cupo_cria')+$cantidadReproduccion;
	 							$cantidadCriaB=pg_fetch_result($qObtenerMaximoControlReproduccionOrigen, 0, 'cantidad_cria');
		 						}else{
	 							$cupoCria=(pg_fetch_result($qCantidadCatastroOrigen, 0, 'cantidad')*28)+$cantidadReproduccion;
	 							$cantidadCriaB=pg_fetch_result($qCantidadCatastroCriasOrigen, 0, 'cantidad');
	 						}
	 						if($cupoCria<0)
	 							$cupoCria=0;
	 							
	 						$cp->guardarControlReproduccion($conexion, $identificadorOperadorOrigen, $idProductoReproduccion, $cupoCria,$cantidadCriaB);
	 							
	 						//destino resta
	 						if (pg_num_rows($qObtenerMaximoControlReproduccionDestino)!=0){
	 							$cupoCria=pg_fetch_result($qObtenerMaximoControlReproduccionDestino, 0, 'cupo_cria')-$cantidadReproduccion;
	 							$cantidadCriaB=pg_fetch_result($qObtenerMaximoControlReproduccionDestino, 0, 'cantidad_cria');

	 						}else{
	 							$cupoCria=pg_fetch_result($qCantidadCatastroCriasDestino, 0, 'cantidad')+(pg_fetch_result($qCantidadCatastroDestino, 0, 'cantidad')*28)-$cantidadReproduccion;
	 							$cantidadCriaB=pg_fetch_result($qCantidadCatastroCriasDestino, 0, 'cantidad');
	 						}
	 							
	 						if($cupoCria<0)
	 							$cupoCria=0;
	 							
	 						$cp->guardarControlReproduccion($conexion, $identificadorOperadorDestino, $idProductoReproduccion, $cupoCria,$cantidadCriaB);
	 					}

	 					foreach ($hDetalleC[$filaDetalle] as $key=>$idCatastro) {
	 						if($banderaIdUtilizado =='SI'){
	 							break;
	 						}
	 						$hDetalleICI = explode(", ", $hDetalleIC[$filaDetalle][$key]);
	 						$cantidadDetalle=count($hDetalleICI);

	 						$qConsultaCatastro=$cmp->consultarCatastro($conexion, $idCatastro);
	 						$fila=pg_fetch_assoc($qConsultaCatastro);

	 						$idCatastroNuevo=$cmp->guardarCatastroProducto($conexion,$sitioOrigen,$areaOrigen [$filaDetalle], $fila['id_producto'], $cantidadDetalle, $usuarioResponsable
	 								, $fila['unidad_medida_peso'], $fila['fecha_modificacion_etapa'], $fila['unidad_comercial'], $fila['nombre_producto']
	 								, $fila['peso'], $fila['id_especie'], $fila['fecha_nacimiento'], $fila['numero_lote'], $fila['estado_etapa'], $idOperacionOrigen[$filaDetalle]);

	 							
	 						$idCatastroNuevo=pg_fetch_result ( $idCatastroNuevo, 0, 'id_catastro' );
	 							
	 						//GUARDAR TRANSACION ORIGEN (RESTA ORIGEN)
	 						$qConceptoCatastroMO = $cmp->consultaConceptoCatastroXCodigo( $conexion, 'MOMO' );
	 						$qCantidadTotalProductoMO = $cmp->consultarCantidadTotalProducto($conexion, $areaDestino [$filaDetalle], $producto[$filaDetalle],$fila ['unidad_comercial'],$idOperacionDestino[$filaDetalle]);
	 						$cantidadTotalMO = (pg_num_rows($qCantidadTotalProductoMO)!=0?pg_fetch_result($qCantidadTotalProductoMO, 0, 'cantidad_total'):0) - $cantidadDetalle ;
	 						$cmp->guardarCatastroTransaccionResta($conexion,$idCatastro,$areaDestino [$filaDetalle], pg_fetch_result($qConceptoCatastroMO, 0, 'id_concepto_catastro'),   $producto [$filaDetalle] , $cantidadDetalle, $cantidadTotalMO, $fila ['unidad_comercial'],$usuarioResponsable, $idOperacionDestino[$filaDetalle]);

	 						//GUARDAR TRANSACION DESTINO (SUMA DESTINO)
	 						$qCantidadTotalProductoMD = $cmp->consultarCantidadTotalProducto($conexion, $areaOrigen [$filaDetalle], $producto[$filaDetalle],$fila ['unidad_comercial'],$idOperacionOrigen[$filaDetalle]);
	 						$cantidadTotalMD = $cantidadDetalle + (pg_num_rows($qCantidadTotalProductoMD)!=0?pg_fetch_result($qCantidadTotalProductoMD, 0, 'cantidad_total'):0);
	 						$qConceptoCatastroMD = $cmp->consultaConceptoCatastroXCodigo ( $conexion, 'MOMD' );
	 						$cmp->guardarCatastroTransaccion ( $conexion,$idCatastroNuevo, $areaOrigen [$filaDetalle], pg_fetch_result($qConceptoCatastroMD, 0, 'id_concepto_catastro'), $producto [$filaDetalle], $cantidadDetalle, $cantidadTotalMD, $fila ['unidad_comercial'], $usuarioResponsable, $idOperacionOrigen[$filaDetalle]);

	 						$sentenciaDetalleCatastro='INSERT INTO g_catastro.detalle_catastros(id_catastro, identificador_producto, identificador_unico_producto ,estado_registro) VALUES ';
	 						$sentenciaActualizarDetalleCatastro='';
	 							
	 						foreach ($hDetalleICI as $identificadorIndividual) {
	 							$qIdDetalleCatastro=$cmp->consultarIdentificadoresIdCatastroFiscalizacion($conexion, $identificadorIndividual);
	 							if(pg_num_rows($qIdDetalleCatastro)!=0){
	 							$filaDetalleCatastro=pg_fetch_assoc($qIdDetalleCatastro);
	 							$identificadorUnicoProducto=$filaDetalleCatastro['identificador_unico_producto'];
	 							$identificadorProducto = $filaDetalleCatastro['identificador_producto'];
	 							$identificadorProducto = $identificadorProducto != '' ? "'$identificadorProducto'" : 'null';
	 							$sentenciaDetalleCatastro.="($idCatastroNuevo,$identificadorProducto,'$identificadorUnicoProducto','$filaDetalleCatastro[estado_registro]'),";
	 							$sentenciaActualizarDetalleCatastro.="UPDATE
									 										g_catastro.detalle_catastros
									 									SET
									 										estado_registro='eliminado',
                                                                            observacion = 'Eliminado por fiscalización en matadero'
									 									WHERE
									 										id_catastro='".$filaDetalleCatastro['id_catastro']."' and
									 										case when identificador_producto is null then  identificador_unico_producto else identificador_producto end ='".$identificadorIndividual."'; ";
	 							
	 							}else{
	 								$banderaIdUtilizado='SI';
	 								break;
	 							}
	 						}		
	 						$cmp->guardarDetalleCatastroProducto($conexion, $sentenciaDetalleCatastro);
	 						$cmp->actualizarEstadoRegistroDetalleCatastro($conexion,$sentenciaActualizarDetalleCatastro);		
	 					}
	 				}

	 				$cmp->guardarNuevoFiscalizacion($conexion, $idMovilizacion, $numeroFiscalizacion, $fechaFiscalizacion, $resultadoFiscalizacion, $accionCorrectiva, $estado, $usuarioResponsable,$observacion, $lugarFiscalizacion, $cantidadAnimales);
	 				$cmp->actualizarDatosCertificadoMovilizacion($conexion, $idMovilizacion, 'anulado', $motivoAnulacion, $observacionAnulacion);
	 				$cmp->actualizarEstadoFiscalizacionMovilizacion($conexion, $idMovilizacion, 'Fiscalizado');
	 				if($banderaIdUtilizado=='NO'){
	 					$conexion->ejecutarConsulta("commit;");

	 					$mensaje['estado'] = 'exito';
	 					$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
	 				}else{
	 					$conexion->ejecutarConsulta("rollback;");
	 					$mensaje['mensaje'] = 'Algún identificador ya fue fiscalizado anteriormente';
	 				}
	 			}

	 			if($banderaDobleGuia=='SI'){
	 				$conexion->ejecutarConsulta("begin;");
	 				$cmp->guardarNuevoFiscalizacion($conexion, $idMovilizacion, $numeroFiscalizacion, $fechaFiscalizacion, $resultadoFiscalizacion, $accionCorrectiva, $estado, $usuarioResponsable, $observacion, $lugarFiscalizacion, $cantidadAnimales);
	 				$cmp->actualizarDatosCertificadoMovilizacion($conexion, $idMovilizacion, 'anulado', $motivoAnulacion, $observacionAnulacion);
	 				$idMovilizacionDos=pg_fetch_result($cmp->obtenerIdVueltaCertificadoMovilizacion($conexion, $idMovilizacion), 0, 'id_movilizacion_dos');
	 				$numeroFiscalizacionDobleGuia=$cmp->autogenerarNumerosFiscalizacionMovilizacion($conexion, $idMovilizacionDos);
	 				$cmp->guardarNuevoFiscalizacion($conexion, $idMovilizacionDos, $numeroFiscalizacionDobleGuia, $fechaFiscalizacion, $resultadoFiscalizacion, $accionCorrectiva, $estado, $usuarioResponsable, $observacion, $lugarFiscalizacion, $cantidadAnimales);
	 				$cmp->actualizarDatosCertificadoMovilizacion($conexion, $idMovilizacionDos, 'anulado', $motivoAnulacion, $observacionAnulacion);
	 				$conexion->ejecutarConsulta("commit;");
	 				$mensaje['estado'] = 'exito';
	 				$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
	 			}

	 		}else{
	 			$mensaje['estado'] = 'error';
	 			$mensaje['mensaje'] = "Algun producto ya no existe en el destino";
	 		}
	 		break;
	 		
	 	case 'activar emision de certificado':
	 	    
	 	    $conexion->ejecutarConsulta("begin;");
	 	    
	 	    $idPerfilEmisorMovilizacion=pg_fetch_result($ca->obtenerIdPerfil($conexion, 'PFL_EMISO_MOVIL'), 0, 'id_perfil');
	 	    
	 	    $cu->crearPerfilUsuarioXIdPerfil($conexion, $usuarioResponsableMovilizacion, $idPerfilEmisorMovilizacion);
	 	    $cmp->guardarNuevoFiscalizacion($conexion, $idMovilizacion, $numeroFiscalizacion, $fechaFiscalizacion, $resultadoFiscalizacion, $accionCorrectiva, $estado, $usuarioResponsable, $observacion, $lugarFiscalizacion, $cantidadAnimales, $justificacion);
	 	    $cmp->actualizarEstadoFiscalizacionMovilizacion($conexion, $idMovilizacion, 'Fiscalizado');
	 	    
	 	    $conexion->ejecutarConsulta("commit;");
	 	    $mensaje['estado'] = 'exito';
	 	    $mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
	 	 
	 	break;
	 		
		}
	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
		$err = preg_replace( "/\r|\n/", " ", $mensaje['error']);
		$conexion->ejecutarLogsTryCatch($ex.'---ERROR:'.$err);
	} finally {
		$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
	$err = preg_replace( "/\r|\n/", " ", $mensaje['error']);
	$conexion->ejecutarLogsTryCatch($ex.'---ERROR:'.$err);
} finally {
	echo json_encode($mensaje);
}
?>