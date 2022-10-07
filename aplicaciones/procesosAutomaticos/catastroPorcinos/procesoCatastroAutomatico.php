<?php

if($_SERVER['REMOTE_ADDR']==''){
	//if(1){
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorMonitoreo.php';
	require_once '../../../clases/ControladorCatalogos.php';
	require_once '../../../clases/ControladorRegistroOperador.php';
	require_once '../../../clases/ControladorCatastroProducto.php';
	

	define ( 'IN_MSG', '<br/> >>> ' );
	define ( 'OUT_MSG', '<br/> <<< ' );
	define ( 'PRO_MSG', '<br/> ... ' );

	$conexion = new Conexion ();
	$cp = new ControladorCatastroProducto ();
	$cr = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos ();
	$cm = new ControladorMonitoreo();
	set_time_limit(1000);

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_CATAS_PRO');

	if($resultadoMonitoreo){
	//if(1){
		/*
		 * 6 Porcinos 19453 LECHON PORHON
		* 6 Porcinos 19454 CERDO LEVANTE POROTE
		* 6 Porcinos 19458 VERRACO PORACO - ETAPA FINAL
		* 6 Porcinos 23445 LECHONA PORONA
		* 6 Porcinos 23443 CERDA LEVANTE PORATE
		* 6 Porcinos 19459 CERDA MADRE PORDRE - ETAPA FINAL
		*
		* 7 Equinos 23405 POTRO EQUTRO
		* 7 Equinos 23406 CABALLO EQULLO - ETAPA FINAL
		* 7 Equinos 23407 POTRA EQUTRA
		* 7 Equinos 23408 YEGUA EQUGUA - ETAPA FINAL
		*
		* 3 Bovinos 23436 TERNERO BOVERO
		* 3 Bovinos 23438 TORETE BOVETE
		* 3 Bovinos 23440 TORO BOVORO - ETAPA FINAL
		* 3 Bovinos 23437 TERNERA BOVERA
		* 3 Bovinos 23439 VACONA BOVONA
		* 3 Bovinos 23441 VACA BOVACA - ETAPA FINAL
		*
		* 8 Aves 23457 POLLITO BB PARA ENGORDE PDEPBE
		* 8 Aves 23458 POLLO ENGORDADO PDEPEN - ETAPA FINAL
		*
		* 8 Aves 23460 PAVO BB PARA ENGORDE PAVPBE
		* 8 Aves 23461 PAVO ENGORDADO PAVPEN - ETAPA FINAL
		*
		* 8 Aves 23465 POLLITA BB PARA POSTURA GAPPBP
		* 8 Aves 23466 POLLONA POSTURA GAPPPO
		* 8 Aves 23467 GALLINA POSTURA GAPGPO - ETAPA FINAL
		*
		* 8 Aves 23468 POLLITA BB REPRODUCCION GRPABR
		* 8 Aves 23469 POLLONA REPRODUCCION GRPPAR
		* 8 Aves 23470 GALLINA REPRODUCCION GRPGAR - ETAPA FINAL
		*
		* 8 Aves 23471 POLLITO BB REPRODUCCION GRPPBR
		* 8 Aves 23472 POLLO REPRODUCCION GRPPOR
		* 8 Aves 23473 GALLO REPRODUCCION GRPGOR - ETAPA FINAL
		*
		* 8 Aves 23474 POLLITA BB REPRODUCCION GRLABR
		* 8 Aves 23475 POLLONA REPRODUCCION GRLPAR
		* 8 Aves 23476 GALLINA REPRODUCCION GRLGAR - ETAPA FINAL
		*
		* 8 Aves 23477 POLLITO BB REPRODUCCION GRLOBR
		* 8 Aves 23478 POLLO REPRODUCCION GRLPOR
		* 8 Aves 23479 GALLO REPRODUCCION GRLGOR - ETAPA FINAL
		*/

		echo '<h1>ACTUALIZACION AUTOMATICA DE PRODUCTO</h1>';
		echo '<p> <strong>INICIO PROCESO DE ACTUALIZACION</strong>';

		$qEdadCatastro = $cp->procesoActualizacionCatastroAutomatico ( $conexion );

		// fila1=PORCINOS
		// fila2=EQUINOS
		// fila3=BOVINOS
		// fila4=AVES
		// fila5=AVES
		// fila6=AVES
		// fila7=AVES
		// fila8=AVES

		$contador = 1;
		$contadorActualizacion=1;

		while ( $filaCatastro = pg_fetch_assoc ( $qEdadCatastro ) ) {

			$qCatastroActualizado = $cp->consultarActualizacionCatastroAutomatico ( $conexion, $filaCatastro ['id_producto'] );
			$filaCatastroActualizado = pg_fetch_assoc ( $qCatastroActualizado );

			if ($filaCatastro ['codigo'] == 'PORACO' || $filaCatastro ['codigo'] == 'PORDRE' ||
					$filaCatastro ['codigo'] == 'EQULLO' || $filaCatastro ['codigo'] == 'EQUGUA' ||
					$filaCatastro ['codigo'] == 'BOVORO' || $filaCatastro ['codigo'] == 'BOVACA' ||
					$filaCatastro ['codigo'] == 'PDEPEN' ||
					$filaCatastro ['codigo'] == 'PAVPEN' ||
					$filaCatastro ['codigo'] == 'GAPGPO' ||
					$filaCatastro ['codigo'] == 'GRPGAR' ||	$filaCatastro ['codigo'] == 'GRPGOR' ||
					$filaCatastro ['codigo'] == 'GRLGAR' ||	$filaCatastro ['codigo'] == 'GRLGOR') {
				echo '<b>' . PRO_MSG . 'Proceso #' . $contador ++ . ' - Producto a ser actualizado a etapa final ' . $filaCatastro ['producto'] . ' - ID catastro ' . $filaCatastro ['id_catastro'] . '</b>';
				echo IN_MSG . 'Inicio del proceso de actualizacion de producto a etapa final';
				$cp->actualizarEtapaFinalCatastroAutomatico ( $conexion, $filaCatastro ['id_catastro'], 'etapaFinal' );
				echo OUT_MSG . 'Fin del proceso de actualizacion de producto a etapa final';
			}

			if (($filaCatastro ['codigo'] == 'PORHON'|| $filaCatastro ['codigo'] == 'POROTE' || $filaCatastro ['codigo'] == 'PORONA' || $filaCatastro ['codigo'] == 'PORATE' ||
					$filaCatastro ['codigo'] == 'EQUTRO' || $filaCatastro ['codigo'] == 'EQUTRA' ||
					$filaCatastro ['codigo'] == 'BOVERO' || $filaCatastro ['codigo'] == 'BOVETE' || $filaCatastro ['codigo'] == 'BOVERA' || $filaCatastro ['codigo'] == 'BOVONA' ||
					$filaCatastro ['codigo'] == 'PDEPBE' ||
					$filaCatastro ['codigo'] == 'PAVPBE' ||
					$filaCatastro ['codigo'] == 'GAPPBP' ||	$filaCatastro ['codigo'] == 'GAPPPO' ||
					$filaCatastro ['codigo'] == 'GRPABR' ||	$filaCatastro ['codigo'] == 'GRPPAR' ||	$filaCatastro ['codigo'] == 'GRPPBR' || $filaCatastro ['codigo'] == 'GRPPOR' ||
					$filaCatastro ['codigo'] == 'GRLABR' || $filaCatastro ['codigo'] == 'GRLPAR' || $filaCatastro ['codigo'] == 'GRLOBR' || $filaCatastro ['codigo'] == 'GRLPOR')
					&& $filaCatastro ['dias_transcurridos'] == $filaCatastroActualizado ['dias_cambio_etapa']) {

				echo '<b>' . PRO_MSG . 'Proceso #' . $contador ++ . ' - Producto a ser actualizado ' . $filaCatastro ['producto'] . ' - ID catastro ' . $filaCatastro ['id_catastro'] . '</b>';
				echo IN_MSG . 'Inicio y envio solicitud de producto a ser actualizado ';

				$cp->actualizarCatastroAutomatico ( $conexion, $filaCatastroActualizado ['id_producto_etapa'], $filaCatastroActualizado ['nombre_producto_etapa'], $filaCatastro ['id_catastro'] );

				if ($filaCatastroActualizado ['estado_etapa'] == 'etapaFinal') {
					echo IN_MSG . 'Inicio del proceso de actualizacion de producto a etapa final';
					$cp->actualizarEtapaFinalCatastroAutomatico ( $conexion, $filaCatastro ['id_catastro'], $filaCatastroActualizado ['estado_etapa'] );
					echo OUT_MSG . 'Fin del proceso de actualizacion de producto a etapa final';
				}
				echo OUT_MSG . 'Fin del envio de solicitud de producto a ser actualizado';

				echo IN_MSG . 'Envio solicitud para la resta de producto a ser actualizado';
				// TODO: Busco el ultima transaccion de catastro para sacar la cantidad total
				$qConsultarCantidadTotalProductoResta = $cp->consultarCantidadTotalProducto ( $conexion, $filaCatastro ['id_area'], $filaCatastro ['id_producto'], $filaCatastro ['unidad_comercial'], $filaCatastro ['id_tipo_operacion'] );
				//$filaCantidadTotalResta = pg_fetch_assoc ( $qConsultarCantidadTotalProductoResta );
				$cantidadTotalResta =(pg_num_rows($qConsultarCantidadTotalProductoResta)!=0 ? pg_fetch_result($qConsultarCantidadTotalProductoResta, 0, 'cantidad_total'):0) - $filaCatastro ['cantidad'];

				$qConsultaConceptoCatastroXCodigo = $cp->consultaConceptoCatastroXCodigo ( $conexion, 'ACPR' );
				$filaConceptoResta = pg_fetch_assoc ( $qConsultaConceptoCatastroXCodigo );

				// TODO: Guarda los datos de la transacion total de catastro
				$cp->guardarCatastroTransaccionResta ( $conexion,$filaCatastro ['id_catastro'] ,$filaCatastro ['id_area'], $filaConceptoResta ['id_concepto_catastro'], $filaCatastro ['id_producto'], $filaCatastro ['cantidad'], $cantidadTotalResta, $filaCatastro ['unidad_comercial'], $filaCatastro ['identificador_responsable'], $filaCatastro ['id_tipo_operacion'] );
				echo OUT_MSG . 'Fin envio solicitud enviada de producto a ser actualizado';

				echo IN_MSG . 'Envio solicitud para la suma de producto ya actualizado';
				$qConsultarCantidadTotalProductoSuma = $cp->consultarCantidadTotalProducto ( $conexion, $filaCatastro ['id_area'], $filaCatastroActualizado ['id_producto_etapa'], $filaCatastro ['unidad_comercial'], $filaCatastro ['id_tipo_operacion'] );
				//$filaCantidadTotalSuma = pg_fetch_assoc ( $qConsultarCantidadTotalProductoSuma );
				$cantidadTotalSuma = (pg_num_rows($qConsultarCantidadTotalProductoSuma)!=0 ? pg_fetch_result($qConsultarCantidadTotalProductoSuma, 0, 'cantidad_total'):0) + $filaCatastro ['cantidad'];

				$qConsultaConceptoCatastroXCodigo = $cp->consultaConceptoCatastroXCodigo ( $conexion, 'ACPS' );
				$filaConceptoSuma = pg_fetch_assoc ( $qConsultaConceptoCatastroXCodigo );

				// TODO: Guarda los datos de la transacion total de catastro
				$cp->guardarCatastroTransaccion ( $conexion, $filaCatastro ['id_catastro'], $filaCatastro ['id_area'], $filaConceptoSuma ['id_concepto_catastro'], $filaCatastroActualizado ['id_producto_etapa'], $filaCatastro ['cantidad'], $cantidadTotalSuma, $filaCatastro ['unidad_comercial'], $filaCatastro ['identificador_responsable'], $filaCatastro ['id_tipo_operacion'] );
				echo OUT_MSG . 'Fin envio solicitud enviada de producto ya actualizado';


				//buscar identificador operador
				//TODO: CREAR NEUVAS PERACIONES
				$qOperaciones = $cp->buscarOperaciones ( $conexion, $filaCatastro ['id_tipo_operacion'], $filaCatastro ['identificador_operador'], $filaCatastroActualizado ['id_producto_etapa'], 'registrado' );

				$nuevaOperacion = '';
				while ( $filaNOperacion = pg_fetch_assoc ( $qOperaciones ) ) {
					$nuevaOperacion .= "'" . $filaNOperacion ['id_operacion'] . "',";
				}
				
				if ($nuevaOperacion == '')
					$nuevaOperacion = 0;
				else
					$nuevaOperacion=rtrim ( $nuevaOperacion, ',' );

				$qAreaOperacion = $cp->buscarAreasOperaciones ( $conexion, "(" . $nuevaOperacion . ")", $filaCatastro ['id_area'] ); // Buscar responsable

				if (pg_num_rows ( $qAreaOperacion ) == 0) {

					$qAOperacion = $cp->buscarOperaciones ( $conexion, $filaCatastro ['id_tipo_operacion'], $filaCatastro ['identificador_operador'], $filaCatastro ['id_producto'], 'registrado' );

					if (pg_num_rows ( $qAOperacion ) != 0) {
						$anteriorOperacion = '';
						while ( $filaAOperacion = pg_fetch_assoc ( $qAOperacion ) ) {
							$anteriorOperacion .= "'" . $filaAOperacion ['id_operacion'] . "',";
						}

						if ($anteriorOperacion == '')
							$anteriorOperacion = 0;
						else
							$anteriorOperacion=rtrim ( $anteriorOperacion, ',' );

						if(pg_num_rows($cp->buscarAreasOperaciones ( $conexion, "(" . $anteriorOperacion . ")", $filaCatastro ['id_area'] )) >0){
						    
							$idOperacion = pg_fetch_result ( $cp->buscarAreasOperaciones ( $conexion, "(" . $anteriorOperacion . ")", $filaCatastro ['id_area'] ), 0, 'id_operacion' ); // Buscar responsable
							$filaNOperacion = pg_fetch_assoc ( $cp->consultarOperacion ( $conexion, $idOperacion ) );
							$fechaActual = date ( 'Y-m-d H-i-s' );
							echo IN_MSG . 'Envio solicitud (operacion) para el nuevo producto ';
							
						    //Buscar el identificador de la operacion minima
						    $qDatosOperacionAsociada = $cr->obtenerIdOperadorTipoOperacionHistorialXIdentificadorTipoOperacionSitio($conexion,$filaNOperacion ['identificador_operador'], $filaNOperacion ['id_tipo_operacion'], $filaCatastro ['id_sitio'], " not in ('eliminado')");
						    
						    if(pg_num_rows($qDatosOperacionAsociada) == 0){
						        $idOperadorTipoOperacion = pg_fetch_result($cr->guardarTipoOperacionPorIndentificadorSitio($conexion, $filaNOperacion ['identificador_operador'], $filaCatastro ['id_sitio'], $filaNOperacion ['id_tipo_operacion']), 0, 'id_operador_tipo_operacion');
						        $historicoOperacion = pg_fetch_result($cr->guardarDatosHistoricoOperacion($conexion,$idOperadorTipoOperacion), 0, 'id_historial_operacion');
						        $nuevaOperacionTipoOperador = true;
						    }else{
						        $datosOperacionAsociada = pg_fetch_assoc($qDatosOperacionAsociada);
						        $idOperadorTipoOperacion = $datosOperacionAsociada['id_operador_tipo_operacion'];
						        $historicoOperacion = $datosOperacionAsociada['id_historial_operacion'];
						        $nuevaOperacionTipoOperador = false;
						    }	
						    
							$qIdSolicitud = $cr->guardarNuevaOperacion($conexion,$filaNOperacion ['id_tipo_operacion'],$filaNOperacion ['identificador_operador'], $filaCatastroActualizado ['id_producto_etapa'], $filaCatastroActualizado ['nombre_producto_etapa'], $idOperadorTipoOperacion, $historicoOperacion);
							//$qIdSolicitud = $cp->guardarNuevaOperacion ( $conexion, $filaNOperacion ['id_tipo_operacion'], $filaNOperacion ['identificador_operador'], $filaNOperacion ['estado'], 'No se realizo proceso de inspeccion, ni cobro de tasas. Proceso ejecutado por sistema GUIA ' . $fechaActual . ' en base a memorando MAGAP-DSV/AGROCALIDAD-2014-001427-M', $filaNOperacion ['informe'], $filaCatastroActualizado ['id_producto_etapa'], $filaCatastroActualizado ['nombre_producto_etapa'], $filaNOperacion ['id_vue'], $filaNOperacion ['fecha_creacion'], $filaNOperacion ['id_pais'], $filaNOperacion ['nombre_pais'], $filaNOperacion ['subpartida_producto_vue'], $filaNOperacion ['codigo_producto_vue'], $filaNOperacion ['fecha_aprobacion'] );
							
							$fechaActual = date('Y-m-d H-i-s');
							$cr -> enviarOperacion($conexion, pg_fetch_result ( $qIdSolicitud, 0, 'id_operacion' ),$filaNOperacion ['estado'], 'No se realizó proceso de inspección, ni cobro de tasas. Proceso ejecutado por sistema GUIA '.$fechaActual.' en base a memorando MAGAP-DSV/AGROCALIDAD-2014-001427-M');
														
							echo OUT_MSG . 'Fin solicitud (operacion) para el nuevo producto ';
							$areasOperacion = $cc->obtenerAreasXtipoOperacion ( $conexion, $filaCatastro ['id_tipo_operacion'] );

							echo IN_MSG . 'Envio solicitud (Area operacion) para el nuevo producto ';
							foreach ( $areasOperacion as $areaOperacion ) {
								$cp->guardarAreaOperacion ( $conexion, $filaCatastro ['id_area'], pg_fetch_result ( $qIdSolicitud, 0, 'id_operacion' ), $filaNOperacion ['estado'], 'No se realizo proceso de inspeccion, ni cobro de tasas. Proceso ejecutado por sistema GUIA ' . $fechaActual . ' en base a memorando MAGAP-DSV/AGROCALIDAD-2014-001427-M' );
							}
							
							if($nuevaOperacionTipoOperador){
							    $cr->actualizarIdentificadorOperacionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion, pg_fetch_result ( $qIdSolicitud, 0, 'id_operacion' ));
							    $cr->guardarAreaPorIdentificadorTipoOperacion($conexion, $filaCatastro ['id_area'], $idOperadorTipoOperacion);
							    $cr-> actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $filaNOperacion ['estado']);
							}
							
							
							echo OUT_MSG . 'Fin solicitud (Area operacion) para el nuevo producto ';
						}

					}
				}

				echo OUT_MSG . 'Fin del proceso de actualizacion';
				echo '<b>' . PRO_MSG . 'Fin proceso producto actualizado a ' . $filaCatastroActualizado ['nombre_producto_etapa'] . '</b>';




				$idProductoReproduccion=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORDRE'),0,'id_producto');
				echo '<br/><br/><strong>INICIO ACTUALIZACION CUPOS CRIAS MADRES</strong>';

				if($filaCatastroActualizado ['id_producto_etapa']==$idProductoReproduccion){

					echo '<b>' . PRO_MSG . 'Proceso #' . $contadorActualizacion ++ . ' inicio proceso de actualizacion  </b>';


					$qCantidadCatastro=$cp->obtenerCantidadCatastroXOperador($conexion, $filaCatastro ['identificador_operador'], '('.$idProductoReproduccion.')');
					$qObtenerMaximoControlReproduccion=$cp->obtenerMaximoControlReproduccion($conexion, $filaCatastro ['identificador_operador'],$idProductoReproduccion);

					$idProductoLechon=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORHON'),0,'id_producto');
					$idProductoLechona=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORONA'),0,'id_producto');

					$qCantidadCatastroCrias=$cp->obtenerCantidadCatastroXOperador($conexion,   $filaCatastro ['identificador_operador'], '('.$idProductoLechon.','.$idProductoLechona.')');
					$cantidadCria=pg_fetch_result($qCantidadCatastroCrias, 0, 'cantidad');

					echo IN_MSG . 'Envio solicitud proceso de actualizacion ';
					$cantidadReproduccion=$filaCatastro ['cantidad']*28;

					if (pg_num_rows($qObtenerMaximoControlReproduccion)!=0){
						$cupoCria=pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cupo_cria')+$cantidadReproduccion;
						$cantidadCriaB=pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cantidad_cria');
					}else{
						$cantidadCriaB=$cantidadCria;
						$cupoCria=(pg_fetch_result($qCantidadCatastro, 0, 'cantidad')*28)+$cantidadReproduccion;

					}
					if($cupoCria<0)
						$cupoCria=0;

					$cp->guardarControlReproduccion($conexion, $filaCatastro ['identificador_operador'], $idProductoReproduccion, $cupoCria,$cantidadCriaB);
					echo OUT_MSG . 'Fin solicitud proceso de actualizacion ';
					echo '<b> '.PRO_MSG.'Fin proceso de actualizacion</b>';
				}

				echo '<br/><strong>FIN ACTUALIZACION CUPOS CRIAS MADRES</strong>';

			}
		}

		echo '<br/><br/><strong>FIN PROCESO DE ACTUALIZACION</strong></p>';



		echo '<br><hr/><h1>ACTUALIZACION CUPO CRIAS DE MADRE POR AÑO</h1>';
		echo '<p> <strong>INICIO PROCESO DE ACTUALIZACION</strong>';

		$idProductoReproduccion=pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORDRE'),0,'id_producto');
		$qBuscarCatastroAnoMadresCria = $cp->buscarCatastroAnoMadresCria($conexion, $idProductoReproduccion);

		if(pg_num_rows($qBuscarCatastroAnoMadresCria)!=0){

	$contador=1;
	$numeroActualizacion=0;
	while ($fila = pg_fetch_assoc($qBuscarCatastroAnoMadresCria) ) {
		
		$qObtenerMaximoControlReproduccion=$cp->obtenerMaximoControlReproduccion($conexion, $fila['identificador_operador'],$idProductoReproduccion);
		$qCantidadCatastro=$cp->obtenerCantidadCatastroXOperador($conexion, $fila['identificador_operador'], '('.$idProductoReproduccion.')');
		$cantidadCriaB=pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cantidad_cria');
		if (!$cantidadCriaB){
			$cantidadCriaB=0;
		}
		if(pg_num_rows($qObtenerMaximoControlReproduccion)!=0){
			$cupoCria=pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cupo_cria')+$fila['existentes'];
		}else{
			$cupoCria=(pg_fetch_result($qCantidadCatastro, 0, 'cantidad')*28)+$fila['existentes'];
		}
		if($fila['numero_actualizacion_cupo']!=''){
			$numeroActualizacion=$fila['numero_actualizacion_cupo'];
		}
		$numeroActualizacion=$numeroActualizacion+1;

															  

		echo '<b>' . PRO_MSG . 'Proceso  #' . $contador++ . ' - '.' Id Catastro ' . $fila['id_catastro'] . '</b>';
		echo IN_MSG . 'Inicio del envio de la solicitud a actualizar cupo crias de madre';

		$cp->actualizarNumeroVecesCupo($conexion, $fila['id_catastro'],$numeroActualizacion );
		$cp->guardarControlReproduccion($conexion, $fila['identificador_operador'], $idProductoReproduccion, $cupoCria,$cantidadCriaB);
		echo OUT_MSG . 'Fin del envio de solicitud';
	}

}
		echo '<br/><strong>FIN</strong></p>';


		$conexion->desconectar ();
	}

}else {

	$s=microtime(true);
	$s1=microtime(true);
	$t=$s1-$s;
	$xcadenota = date("d/m/Y").", ".date("H:i:s");
	$xcadenota.= ";REMOTE ".$_SERVER['REMOTE_ADDR'];
	$xcadenota.= ";HTTP ".$_SERVER['HTTP_REFERER'];
	$xcadenota.= "; ".$t." seg\n";
	$arch = fopen("../../aplicaciones/uath/lib_logs/logs/catastro_producto_".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);
}
?>