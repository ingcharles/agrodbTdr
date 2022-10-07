<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEventoSanitario.php';
	require_once '../../clases/ControladorUsuarios.php';
	
	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();
	$cu = new ControladorUsuarios();
	
	$largoCabecera=41;
		
	$numSolicitud = htmlspecialchars ($_POST['bNumSolicitud'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['bFechaCreacion'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$sitioPredio = htmlspecialchars ($_POST['bSitioPredio'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['bFincaPredio'],ENT_NOQUOTES,'UTF-8');
	$sindrome = htmlspecialchars ($_POST['bSindrome'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	
	//Función buscarCertificacionBT
	$eventoSanitarioCabecera = $cpco->buscarEventoSanitarioFiltrado($conexion, $numSolicitud, 
												$fecha, $idProvincia, $idCanton, $idParroquia, 
												$sitioPredio, $nombrePredio, $estado, $sindrome);
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<link href="estilos/estiloapp.css" rel="stylesheet"></link>
	</head>
	
	<body>
		<div id="header">
		   	<div id="logoMagap"></div>
			<div id="texto"></div>
			<div id="logoAgrocalidad"></div>
			<div id="textoTitulo">Ministerio de Agricultura y Ganadería<Br>
						Agencia Ecuatoriana de Aseguramiento de la Calidad del Agro Agrocalidad<Br>
							Eventos Sanitarios<br>
			</div>
			
			<div id="direccion"></div>
			<div id="imprimir">
				<form id="filtrar" action="reporteEventosSanitariosDetalleExcel.php" target="_blank" method="post">
				
				 <input type="hidden" id="bNumSolicitud" name="bNumSolicitud" value="<?php echo $_POST['bNumSolicitud'];?>" />
				 <input type="hidden" id="bFechaCreacion" name="bFechaCreacion" value="<?php echo $_POST['bFechaCreacion'];?>" />
				 <input type="hidden" id="bIdProvincia" name="bIdProvincia" value="<?php echo $_POST['bIdProvincia'];?>" />
				 <input type="hidden" id="bIdCanton" name="bIdCanton" value="<?php echo $_POST['bIdCanton'];?>" />
				 <input type="hidden" id="bIdParroquia" name="bIdParroquia" value="<?php echo $_POST['bIdParroquia'];?>" />	 
				 <input type="hidden" id="bSitioPredio" name="bSitioPredio" value="<?php echo $_POST['bSitioPredio'];?>" />
				 <input type="hidden" id="bFincaPredio" name="bFincaPredio" value="<?php echo $_POST['bFincaPredio'];?>" />
				 <input type="hidden" id="bSindrome" name="bSindrome" value="<?php echo $_POST['bSindrome'];?>" />				 
				 <input type="hidden" id="bEstado" name="bEstado" value="<?php echo $_POST['bEstado'];?>" />
				 
			 	<button type="submit" class="guardar">Exportar Excel</button>	  	 
				</form>
			</div>
			
			<div id="bandera"></div>
		</div>
		
		<div id="tabla">
		
			<table id="tablaReporteControlOficial" class="soloImpresion">
				<thead>
					<tr>
						<th colspan="41">DATOS GENERALES</th>
						<!-- EXPLOTACIONES -->	
						<th colspan="4">IDENTIFICACIÓN DE LA EXPLOTACIÓN</th>
						<!-- CRONOLOGÍA -->	
						<th colspan="4">CRONOLOGÍA</th>
						<!-- ESPECIE -->	
						<th colspan="4">ESPECIE ANIMAL AFECTADA</th>
						<!-- VACUNACIÓN -->	
						<th colspan="9">VACUNACIÓN</th>
						<!-- ORIGEN -->	
						<th colspan="8">ORIGEN DE LOS ANIMALES ENFERMOS</th>
						<!-- COLECTA -->	
						<th colspan="9">COLECTA DE MATERIAL</th>
						<!-- POBLACIÓN -->	
						<th colspan="11">POBLACIÓN ANIMAL EXISTENTE, ENFERMA Y MUERTA</th>
						<!-- INGRESO -->	
						<th colspan="11">INFORMACIÓN DE INGRESO DE ANIMALES</th>
						<!-- EGRESO -->	
						<th colspan="11">INFORMACIÓN DE EGRESO DE ANIMALES</th>
						<!-- ORIGEN -->	
						<th colspan="9">ORIGEN, MEDIDAS, FOTOGRAFÍAS, MAPA, OBSERVACIONES</th>
						<!-- LABORATORIO -->	
						<th colspan="5">RESULTADOS DE PRUEBAS DE LABORATORIO</th>
						<!-- CRONOLOGIA FINAL -->	
						<th colspan="4">DIAGNÓSTICO DEFINITIVO</th>
						<!-- POBLACION FINAL -->	
						<th colspan="9">POBLACIÓN ANIMAL EXISTENTE, ENFERMA Y MUERTA AL CIERRE DEL EPISODIO</th>
						<!-- VACUNACION -->	
						<th colspan="7">VACUNACIÓN AL CIERRE</th>
					</tr>
					
					<tr>
						<th>ID</th>
					    <th>NÚMERO SOLICITUD</th>
						<th>FECHA REGISTRO</th>
						<th>ORIGEN NOTIFICACIÓN</th>
						<th>CANAL NOTIFICACIÓN</th>
						<th>NOMBRE DEL PROPIETARIO</th>
						<th>CÉDULA</th>
						<th>TELÉFONO</th>
						<th>CELULAR</th>
						<th>CORREO ELECTRÓNICO</th>
						<th>NOMBRE PREDIO</th>
						<th>SITIO PREDIO</th>
						<th>EXTENSIÓN PREDIO</th>
						<th>UNIDAD MEDIDA</th>
						<th>TIENE OTROS PREDIOS</th>
						<th>NÚMERO DE PREDIOS</th>
						<th>TIENE MEDIDAS DE BIOSEGURIDAD</th>
						<th>PROVINCIA</th>
						<th>CANTÓN</th>
						<th>PARROQUIA</th>
						<th>OFICINA</th>
						<th>SEMANA</th>
						<th>UTM X</th>
						<th>UTM Y</th>
						<th>UTM Z</th>
						<th>HUSO/ZONA</th>
						<th>SINTOMATOLOGÍA</th>
						<th>LESIONES EN LA NECROPCIA</th>
						<th>ESPECIE PRIMER ANIMAL ENFERMO</th>
						<th>EDAD EN MESES</th>
						<th>INGRESADO</th>
						<th>SÍNDROME PRESUNTIVO</th>
						<th>OTROS PREDIOS AFECTADOS</th>
						<th>MOVIMIENTO ANIMAL</th>
						<th>VACUNACIÓN FINAL</th>
						<th>CONCLUSIÓN FINAL</th>
						<th>LABORATORIO</th> 
						<th>ESTADO</th>
						<th>OBSERVACIONES</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- EXPLOTACIONES -->			
						<th>ESPECIE</th>
						<th>TIPO EXPLOTACIÓN</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- CRONOLOGIA -->			
						<th>TIPO</th>
						<th>FECHA</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- ESPECIE AFECTADA -->			
						<th>ESPECIE</th>
						<th>ESPECIFICACIÓN</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- VACUNACIÓN -->			
						<th>TIPO VACUNACIÓN</th>
						<th>ENFERMEDAD</th>
						<th>FECHA</th>
						<th>LOTE</th>
						<th>NÚM CERTIFICADO</th>
						<th>LABORATORIO</th>
						<th>OBSERVACIONES</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- ORIGEN -->			
						<th>VISITA</th>
						<th>ORIGEN DE LOS ANIMALES ENFERMOS</th>
						<th>PAÍS</th>
						<th>PROVINCIA</th>
						<th>CANTÓN</th>
						<th>FECHA</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- COLECTA DE MATERIAL -->			
						<th>VISITA</th>
						<th>ESPECIE</th>
						<th>PRUEBA LABORATORIO SOLICITADA</th>
						<th>TIPO MUESTRA</th>
						<th>NUM MUESTRAS</th>
						<th>FECHA COLECTA MUESTRA</th>
						<th>FECHA ENVÍO MUESTRA</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- POBLACIÓN -->			
						<th>VISITA</th>
						<th>ESPECIE</th>
						<th>CATEGORÍA</th>
						<th>EXISTENTES</th>
						<th>ENFERMOS</th>
						<th>MUERTOS</th>
						<th>SACRIFICADOS</th>
						<th>ENFERMOS SIN VACUNAR</th>
						<th>TOTAL SIN VACUNAR</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- INGRESOS -->			
						<th>VISITA</th>
						<th>PROVINCIA</th>
						<th>CANTÓN</th>
						<th>PARROQUIA</th>
						<th>ESPECIE</th>
						<th>PROPIETARIO</th>
						<th>FINCA, FERIA, ETC.</th>
						<th>FECHA</th>
						<th>NUM ANIMALES INGRESO</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- EGRESOS -->			
						<th>VISITA</th>
						<th>PROVINCIA</th>
						<th>CANTÓN</th>
						<th>PARROQUIA</th>
						<th>ESPECIE</th>
						<th>PROPIETARIO</th>
						<th>FINCA, FERIA, ETC.</th>
						<th>FECHA</th>
						<th>NUM ANIMALES EGRESO</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- ORIGEN MEDIDAS -->			
						<th>VISITA</th>
						<th>ORIGEN PROBABLE ENFERMEDAD</th>
						<th>CUARENTENA PREDIO</th>
						<th>NUM ACTA</th>
						<th>MEDIDAS SANITARIAS IMPLEMENTADAS</th>
						<th>OBSERVCIONES</th>
						<th>ACTA INICIO CUARENTENA, MAPAS, FOTOS</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- LABORATORIOS -->			
						<th>VISITA</th>
						<th>RESULTADO</th>
						<th>OBSERVACIONES</th>
						<th>INFORME LABORATORIO</th>
						<th>CÉDULA</th>
						
						
						<!-- DIAGNÓSTICO -->			
						<th>DIAGNÓSTICO</th>
						<th>DESCRIPCIÓN</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- POBLACIÓN FINAL -->			
						<th>ESPECIE</th>
						<th>CATEGORÍA</th>
						<th>EXISTENTES</th>
						<th>ENFERMOS</th>
						<th>MUERTOS</th>
						<th>SACRIFICADOS</th>
						<th>MATADOS Y ELIMINADOS</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- VACUNACIÓN FINAL -->			
						<th>TIPO</th>
						<th>DOSIS APLICADAS</th>
						<th>PREDIOS VACUNADOS</th>
						<th>LABORATORIOS</th>
						<th>LOTE</th>						
						<th>CÉDULA</th>
						<th>AUTOR</th>
					</tr>
				</thead>
				
			<tbody>
				 <?php	

						 while($notificacionEventoSanitario = pg_fetch_assoc($eventoSanitarioCabecera)){
							echo '<tr>
									<td class="formatoTexto">'.$notificacionEventoSanitario['id_evento_sanitario'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['numero_formulario'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['fecha'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['nombre_origen'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['nombre_canal'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['nombre_propietario'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['cedula_propietario'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['telefono_propietario'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['celular_propietario'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['correo_electronico_propietario'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['nombre_predio'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['sitio_predio'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['extencion_predio'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['medida'].'</td>
					   				<td class="formatoTexto">'.$notificacionEventoSanitario['otros_predios'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['numero_predios'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['bioseg'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['provincia'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['canton'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['parroquia'].'</td>
				 					<td class="formatoTexto">'.$notificacionEventoSanitario['oficina'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['semana'].'</td>
				 					<td class="formatoTexto">'.$notificacionEventoSanitario['utm_x'].'</td>
				 					<td class="formatoTexto">'.$notificacionEventoSanitario['utm_y'].'</td>
				 					<td class="formatoTexto">'.$notificacionEventoSanitario['utm_z'].'</td>
				 					<td class="formatoTexto">'.$notificacionEventoSanitario['huso_zona'].'</td>
				 					<td class="formatoTexto">'.$notificacionEventoSanitario['sintomatologia'].'</td>
				 					<td class="formatoTexto">'.$notificacionEventoSanitario['leciones_necropsia'].'</td>
				 					<td class="formatoTexto">'.$notificacionEventoSanitario['nombre_especie_primer_animal'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['edad_primer_animal'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['ingresado_primer_animal'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['sindrome_presuntivo'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['otros_predios_afectados'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['movimiento_animal'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['vacunacion_final'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['conclusion_final'].'</td>
				 					<td class="formatoTexto">'.$notificacionEventoSanitario['laboratorio'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['estado'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['observaciones'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $notificacionEventoSanitario['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>';
							
									
							
							$tipoExplotacionConsulta  = $cpco->listarTiposExplotaciones($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$cronologiaConsulta  = $cpco->listarCronologias($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$especieAnimalAfectadaConsulta  = $cpco->listarEspecieAnimalAfactada($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$vacunacionAftosaConsulta  = $cpco->listarVacunacionAftosa($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$origenAnimalConsulta  = $cpco->listarOrigenes($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$muestraConsulta  = $cpco->listarMuestrasDetalle($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$poblacionesConsulta  = $cpco->listarPoblaciones($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$ingresosConsulta  = $cpco->listarIngresos($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$egresosConsulta = $cpco->listarEgresos($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$origenesMedidasConsulta = $cpco->listarOrigenMedida($conexion,  $notificacionEventoSanitario['id_evento_sanitario']);
							$resultadoLaboratorio = $cpco->abrirResultadoLaboratorio($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$cronologiasFinal  = $cpco->listarCronologiasFinales($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$diagnosticosConsulta  = $cpco->listarDiagnosticos($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$poblacionesFinalesConsulta   = $cpco->listarPoblacionesFinales($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							$vacunacionFinalesConsulta = $cpco->listarVacunacionFinales($conexion, $notificacionEventoSanitario['id_evento_sanitario']);
							
							
							$mayor = max(pg_num_rows($tipoExplotacionConsulta), pg_num_rows($cronologiaConsulta), 
									pg_num_rows($especieAnimalAfectadaConsulta), pg_num_rows($vacunacionAftosaConsulta),
									pg_num_rows($origenAnimalConsulta), pg_num_rows($muestraConsulta), 
									pg_num_rows($poblacionesConsulta), pg_num_rows($ingresosConsulta),
									pg_num_rows($egresosConsulta), pg_num_rows($origenesMedidasConsulta), 
									pg_num_rows($resultadoLaboratorio), 
									pg_num_rows($diagnosticosConsulta), pg_num_rows($poblacionesFinalesConsulta), 
									pg_num_rows($vacunacionFinalesConsulta));
								
						
							if(pg_num_rows($tipoExplotacionConsulta) != 0){
								while ($fila = pg_fetch_assoc($tipoExplotacionConsulta)){
									$tipoExplotacion[] = array(especie=>$fila['especie'],
																tipo_explotacion=>$fila['tipo_explotacion'],
									 				identificador=>$fila['identificador']
									);
								}
							}
								
							if(pg_num_rows($cronologiaConsulta) != 0){
								while ($fila = pg_fetch_assoc($cronologiaConsulta)){
									$cronologia[] = array(nombre_tipo_cronologia=>$fila['nombre_tipo_cronologia'],
														  fecha_cronologia=>$fila['fecha_cronologia'],
									 				identificador=>$fila['identificador']);
								}
							}
							
							if(pg_num_rows($cronologiasFinal) != 0){
								while ($fila = pg_fetch_assoc($cronologiasFinal)){
									$cronologia[] = array(nombre_tipo_cronologia=>$fila['nombre_tipo_cronologia_final'],
											fecha_cronologia=>$fila['fecha_cronologia_final'],
											identificador=>$fila['identificador']
									);
								}
							}
							
							if(pg_num_rows($especieAnimalAfectadaConsulta) != 0){
								while ($fila = pg_fetch_assoc($especieAnimalAfectadaConsulta)){
									$especieAfectada[] = array(nombre_especie_afectada=>$fila['nombre_especie_afectada'],
																especificacion_especie_afectada=>$fila['especificacion_especie_afectada'],
									 				identificador=>$fila['identificador']);
								}
							}
							
							if(pg_num_rows($vacunacionAftosaConsulta) != 0){
								while ($fila = pg_fetch_assoc($vacunacionAftosaConsulta)){
									$vacunacionAftosa[] = array(nombre_tipo_vacunacion_aftosa=>$fila['nombre_tipo_vacunacion_aftosa'],
																enfermedad=>$fila['enfermedad'],
																fecha_vacunacion_aftosa=>$fila['fecha_vacunacion_aftosa'],
																lote_vacunacion_aftosa=>$fila['lote_vacunacion_aftosa'],
																numero_certificado_vacunacion_aftosa=>$fila['numero_certificado_vacunacion_aftosa'],
																nombre_laboratorio_vacunacion_aftosa=>$fila['nombre_laboratorio_vacunacion_aftosa'],
																observaciones=>$fila['observaciones'],
									 				identificador=>$fila['identificador']
									);
								}
							}
							
							if(pg_num_rows($origenAnimalConsulta) != 0){
								while ($fila = pg_fetch_assoc($origenAnimalConsulta)){
									$origenAnimal[] = array(numero_visita=>$fila['numero_visita'],
															nombre_origen=>$fila['nombre_origen'],
															nombre_pais=>$fila['nombre_pais'],
															nombre_provincia=>$fila['nombre_provincia'],
															canton=>$fila['canton'],
															fecha_origen=>$fila['fecha_origen'],
									 				identificador=>$fila['identificador']
									);
								}
							}
							
							if(pg_num_rows($muestraConsulta) != 0){
								while ($fila = pg_fetch_assoc($muestraConsulta)){
									$detalleMuestra[] = array(numero_visita=>$fila['numero_visita'],
																especie_muestra=>$fila['especie_muestra'],
																prueba_muestra=>$fila['prueba_muestra'],
																tipo_muestra=>$fila['tipo_muestra'],
																numero_muestras=>$fila['numero_muestras'],
																fecha_colecta_muestra=>$fila['fecha_colecta_muestra'],
																fecha_envio_muestra=>$fila['fecha_envio_muestra'],
									 				identificador=>$fila['identificador']
									);
								}
							}
							
							if(pg_num_rows($poblacionesConsulta) != 0){
								while ($fila = pg_fetch_assoc($poblacionesConsulta)){
									$poblaciones[] = array(numero_visita=>$fila['numero_visita'],
															nombre_especie_poblacion=>$fila['nombre_especie_poblacion'],
															tipo_especie_poblacion=>$fila['tipo_especie_poblacion'],
															existentes=>$fila['existentes'],
															enfermos=>$fila['enfermos'],
															muertos=>$fila['muertos'],
															sacrificados=>$fila['sacrificados'],
															enfermos_sin_vacunas=>$fila['enfermos_sin_vacunas'],
															total_sin_vacunar=>$fila['total_sin_vacunar'],
									 				identificador=>$fila['identificador']
									);
								}
							}
							
							if(pg_num_rows($ingresosConsulta) != 0){
								while ($fila = pg_fetch_assoc($ingresosConsulta)){
									$ingresos[] = array(numero_visita=>$fila['numero_visita'],
														nombre_tipo_movimiento_ingreso=>$fila['nombre_tipo_movimiento_ingreso'],
														nombre_provincia=>$fila['nombre_provincia'],
														nombre_canton=>$fila['nombre_canton'],
														nombre_parroquia=>$fila['nombre_parroquia'],
														nombre_especie=>$fila['nombre_especie'],
														propietario_movimiento=>$fila['propietario_movimiento'],
														finca_movimiento=>$fila['finca_movimiento'],
														fecha_movimiento=>$fila['fecha_movimiento'],
														numero_animales=>$fila['numero_animales'],
									 				identificador=>$fila['identificador']
									);
								}
							}
							
							if(pg_num_rows($egresosConsulta) != 0){
								while ($fila = pg_fetch_assoc($egresosConsulta)){
									$egresos[] = array(numero_visita=>$fila['numero_visita'],
														nombre_tipo_movimiento_ingreso=>$fila['nombre_tipo_movimiento_ingreso'],
														nombre_provincia=>$fila['nombre_provincia'],
														nombre_canton=>$fila['nombre_canton'],
														nombre_parroquia=>$fila['nombre_parroquia'],
														nombre_especie=>$fila['nombre_especie'],
														propietario_movimiento=>$fila['propietario_movimiento'],
														finca_movimiento=>$fila['finca_movimiento'],
														fecha_movimiento=>$fila['fecha_movimiento'],
														numero_animales=>$fila['numero_animales'],
									 				identificador=>$fila['identificador']
									);
								}
							}
							
							if(pg_num_rows($origenesMedidasConsulta) != 0){
								while ($fila = pg_fetch_assoc($origenesMedidasConsulta)){
									$origen[] = array(numero_visita=>$fila['numero_visita'],
											origen_enfermedad=>$fila['origen_enfermedad'],
											cuarentena_predio=>$fila['cuarentena_predio'],
											numero_acta=>$fila['numero_acta'],
											medidas_sanitarias=>$fila['medidas_sanitarias'],
											observaciones=>$fila['observaciones'],
											ruta_fotos=>$fila['ruta_fotos'],
									 				identificador=>$fila['identificador']
									);
								}
							}
							
							if(pg_num_rows($resultadoLaboratorio) != 0){
								while ($fila = pg_fetch_assoc($resultadoLaboratorio)){
									$resultadoLab[] = array(num_inspeccion=>$fila['num_inspeccion'],
															num_muestra=>$fila['num_muestra'],
															resultado_analisis=>$fila['resultado_analisis'],
															archivo_informe=>$fila['archivo_informe'],
									 				identificador=>$fila['identificador']
									);
								}
							}
							
							
							
							if(pg_num_rows($diagnosticosConsulta) != 0){
								while ($fila = pg_fetch_assoc($diagnosticosConsulta)){
									$diagnostico[] = array(nombre_diagnostico_final=>$fila['nombre_diagnostico_final'],
																descricion_diagnostico_final=>$fila['descricion_diagnostico_final'],
									 				identificador=>$fila['identificador']
									);
								}
							}
							
							if(pg_num_rows($poblacionesFinalesConsulta) != 0){
								while ($fila = pg_fetch_assoc($poblacionesFinalesConsulta)){
									$poblacionesFinales[] = array(nombre_especie_poblacion_final=>$fila['nombre_especie_poblacion_final'],
																	nombre_categoria_poblacion_final=>$fila['nombre_categoria_poblacion_final'],
																	existentes_poblacion_final=>$fila['existentes_poblacion_final'],
																	enfermos_poblacion_final=>$fila['enfermos_poblacion_final'],
																	muertos_poblacion_final=>$fila['muertos_poblacion_final'],
																	sacrificados_poblacion_final=>$fila['sacrificados_poblacion_final'],
																	matados_eliminados_poblacion_final=>$fila['matados_eliminados_poblacion_final'],
									 				identificador=>$fila['identificador']
									);
								}
							}

							if(pg_num_rows($vacunacionFinalesConsulta) != 0){
								while ($fila = pg_fetch_assoc($vacunacionFinalesConsulta)){
									$vacunacionFinal[] = array(nombre_tipo_vacunacion_final=>$fila['nombre_tipo_vacunacion_final'],
																dosis_aplicada_vacunacion_final=>$fila['dosis_aplicada_vacunacion_final'],
																predios_vacunacion_final=>$fila['predios_vacunacion_final'],
																nombre_laboratorios_vacunacion_final=>$fila['nombre_laboratorios_vacunacion_final'],
																lote_vacunacion_final=>$fila['lote_vacunacion_final'],
									 				identificador=>$fila['identificador']
									);
								}
							}
							
							//hacer el recorrido con los vectores
							for($i=0;$i<$mayor;$i++){
								$j=1;
							
								if($i>=1){
									echo '	<tr>';
							
									for ($k=0;$k<$largoCabecera;$k++){
										echo '<td></td>';
									}
								}
								 
								echo ' <td class="formatoTexto">'.$tipoExplotacion[$i]['especie'].'</td>
							        <td class="formatoTexto">'.$tipoExplotacion[$i]['tipo_explotacion'].'</td>
									<td class="formatoTexto">'.$tipoExplotacion[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $tipoExplotacion[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
							
						        	<td class="formatoTexto">'.$cronologia[$i]['nombre_tipo_cronologia'].'</td>
									<td class="formatoTexto">'.$cronologia[$i]['fecha_cronologia'].'</td>
									<td class="formatoTexto">'.$cronologia[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $cronologia[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
																						
							        <td class="formatoTexto">'.$especieAfectada[$i]['nombre_especie_afectada'].'</td>
							        <td class="formatoTexto">'.$especieAfectada[$i]['especificacion_especie_afectada'].'</td>
									<td class="formatoTexto">'.$especieAfectada[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $especieAfectada[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
											
									<td class="formatoTexto">'.$vacunacionAftosa[$i]['nombre_tipo_vacunacion_aftosa'].'</td>
									<td class="formatoTexto">'.$vacunacionAftosa[$i]['enfermedad'].'</td>					 
					 				<td class="formatoTexto">'.$vacunacionAftosa[$i]['fecha_vacunacion_aftosa'].'</td>
									<td class="formatoTexto">'.$vacunacionAftosa[$i]['lote_vacunacion_aftosa'].'</td>
							        <td class="formatoTexto">'.$vacunacionAftosa[$i]['numero_certificado_vacunacion_aftosa'].'</td>
							        <td class="formatoTexto">'.$vacunacionAftosa[$i]['nombre_laboratorio_vacunacion_aftosa'].'</td>
									<td class="formatoTexto">'.$vacunacionAftosa[$i]['observaciones'].'</td>
									<td class="formatoTexto">'.$vacunacionAftosa[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $vacunacionAftosa[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
											
							        <td class="formatoTexto">'.$origenAnimal[$i]['numero_visita'].'</td>
									<td class="formatoTexto">'.$origenAnimal[$i]['nombre_origen'].'</td>
							        <td class="formatoTexto">'.$origenAnimal[$i]['nombre_pais'].'</td>
					 				<td class="formatoTexto">'.$origenAnimal[$i]['nombre_provincia'].'</td>
							        <td class="formatoTexto">'.$origenAnimal[$i]['canton'].'</td>									
									<td class="formatoTexto">'.$origenAnimal[$i]['fecha_origen'].'</td>
									<td class="formatoTexto">'.$origenAnimal[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $origenAnimal[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
									
							        <td class="formatoTexto">'.$detalleMuestra[$i]['numero_visita'].'</td>
								    <td class="formatoTexto">'.$detalleMuestra[$i]['especie_muestra'].'</td>
							        <td class="formatoTexto">'.$detalleMuestra[$i]['prueba_muestra'].'</td>
									<td class="formatoTexto">'.$detalleMuestra[$i]['tipo_muestra'].'</td>
									<td class="formatoTexto">'.$detalleMuestra[$i]['numero_muestras'].'</td>
									<td class="formatoTexto">'.$detalleMuestra[$i]['fecha_colecta_muestra'].'</td>
							        <td class="formatoTexto">'.$detalleMuestra[$i]['fecha_envio_muestra'].'</td>
									<td class="formatoTexto">'.$detalleMuestra[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $detalleMuestra[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
																		
						    		<td class="formatoTexto">'.$poblaciones[$i]['numero_visita'].'</td>
									<td class="formatoTexto">'.$poblaciones[$i]['nombre_especie_poblacion'].'</td>
									<td class="formatoTexto">'.$poblaciones[$i]['tipo_especie_poblacion'].'</td>
									<td class="formatoTexto">'.$poblaciones[$i]['existentes'].'</td>
									<td class="formatoTexto">'.$poblaciones[$i]['enfermos'].'</td>
									<td class="formatoTexto">'.$poblaciones[$i]['muertos'].'</td>
									<td class="formatoTexto">'.$poblaciones[$i]['sacrificados'].'</td>
									<td class="formatoTexto">'.$poblaciones[$i]['enfermos_sin_vacunas'].'</td>
									<td class="formatoTexto">'.$poblaciones[$i]['total_sin_vacunar'].'</td>
									<td class="formatoTexto">'.$poblaciones[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $poblaciones[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
																		
									<td class="formatoTexto">'.$ingresos[$i]['numero_visita'].'</td>
									<td class="formatoTexto">'.$ingresos[$i]['nombre_provincia'].'</td>
									<td class="formatoTexto">'.$ingresos[$i]['nombre_canton'].'</td>
									<td class="formatoTexto">'.$ingresos[$i]['nombre_parroquia'].'</td>
									<td class="formatoTexto">'.$ingresos[$i]['nombre_especie'].'</td>
									<td class="formatoTexto">'.$ingresos[$i]['propietario_movimiento'].'</td>
									<td class="formatoTexto">'.$ingresos[$i]['finca_movimiento'].'</td>
									<td class="formatoTexto">'.$ingresos[$i]['fecha_movimiento'].'</td>
									<td class="formatoTexto">'.$ingresos[$i]['numero_animales'].'</td>
									<td class="formatoTexto">'.$ingresos[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $ingresos[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
									
									<td class="formatoTexto">'.$egresos[$i]['numero_visita'].'</td>
									<td class="formatoTexto">'.$egresos[$i]['nombre_provincia'].'</td>
									<td class="formatoTexto">'.$egresos[$i]['nombre_canton'].'</td>
									<td class="formatoTexto">'.$egresos[$i]['nombre_parroquia'].'</td>
									<td class="formatoTexto">'.$egresos[$i]['nombre_especie'].'</td>
									<td class="formatoTexto">'.$egresos[$i]['propietario_movimiento'].'</td>
									<td class="formatoTexto">'.$egresos[$i]['finca_movimiento'].'</td>
									<td class="formatoTexto">'.$egresos[$i]['fecha_movimiento'].'</td>
									<td class="formatoTexto">'.$egresos[$i]['numero_animales'].'</td>
									<td class="formatoTexto">'.$egresos[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $egresos[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
													
									<td class="formatoTexto">'.$origen[$i]['numero_visita'].'</td>
									<td class="formatoTexto">'.$origen[$i]['origen_enfermedad'].'</td>
									<td class="formatoTexto">'.$origen[$i]['cuarentena_predio'].'</td>
									<td class="formatoTexto">'.$origen[$i]['numero_acta'].'</td>
									<td class="formatoTexto">'.$origen[$i]['medidas_sanitarias'].'</td>
									<td class="formatoTexto">'.$origen[$i]['observaciones'].'</td>
									<td class="formatoTexto">'.($origen[$i]['ruta_fotos']==''? 'No ha subido ningún archivo aún':'<a href=../../'.$origen[$i]['ruta_fotos'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo cargado</a>').'</td>
									<td class="formatoTexto">'.$origen[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $origen[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
							        		
									<td class="formatoTexto">'.$resultadoLab[$i]['num_inspeccion'].'</td>
									<td class="formatoTexto">'.$resultadoLab[$i]['num_muestra'].'</td>
									<td class="formatoTexto">'.$resultadoLab[$i]['resultado_analisis'].'</td>
									<td class="formatoTexto">'.($resultadoLab[$i]['archivo_informe']==''? 'No ha subido ningún archivo aún':'<a href=../../'.$resultadoLab[$i]['archivo_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo cargado</a>').'</td>
									<td class="formatoTexto">'.$resultadoLab[$i]['identificador'].'</td>
					 						
											
									<td class="formatoTexto">'.$diagnostico[$i]['nombre_diagnostico_final'].'</td>
									<td class="formatoTexto">'.$diagnostico[$i]['descricion_diagnostico_final'].'</td>
									<td class="formatoTexto">'.$diagnostico[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $diagnostico[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
					 						
					 				<td class="formatoTexto">'.$poblacionesFinales[$i]['nombre_especie_poblacion_final'].'</td>
									<td class="formatoTexto">'.$poblacionesFinales[$i]['nombre_categoria_poblacion_final'].'</td>
									<td class="formatoTexto">'.$poblacionesFinales[$i]['existentes_poblacion_final'].'</td>
									<td class="formatoTexto">'.$poblacionesFinales[$i]['enfermos_poblacion_final'].'</td>
							        <td class="formatoTexto">'.$poblacionesFinales[$i]['muertos_poblacion_final'].'</td>
									<td class="formatoTexto">'.$poblacionesFinales[$i]['sacrificados_poblacion_final'].'</td>
									<td class="formatoTexto">'.$poblacionesFinales[$i]['matados_eliminados_poblacion_final'].'</td>
									<td class="formatoTexto">'.$poblacionesFinales[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $poblacionesFinales[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
											
									<td class="formatoTexto">'.$vacunacionFinal[$i]['nombre_tipo_vacunacion_final'].'</td>
									<td class="formatoTexto">'.$vacunacionFinal[$i]['dosis_aplicada_vacunacion_final'].'</td>
									<td class="formatoTexto">'.$vacunacionFinal[$i]['predios_vacunacion_final'].'</td>
									<td class="formatoTexto">'.$vacunacionFinal[$i]['nombre_laboratorios_vacunacion_final'].'</td>
									<td class="formatoTexto">'.$vacunacionFinal[$i]['lote_vacunacion_final'].'</td>
					 				<td class="formatoTexto">'.$vacunacionFinal[$i]['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $vacunacionFinal[$i]['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
											
					  			</tr>';
									
								$j++;
							}
								
							$tipoExplotacion = null;
							$cronologia = null;
							$especieAfectada = null;
							$vacunacionAftosa = null;
							$origenAnimal = null;
							$detalleMuestra = null;
							$poblaciones = null;
							$ingresos = null;
							$egresos = null;
							$origen = null;
							$resultadoLab = null;
							$cronologiaFinal = null;
							$diagnostico = null;
							$poblacionesFinales = null;
							$vacunacionFinal = null;
						}
				 ?>
				
				</tbody>
			</table>
		</div>
	</body>
</html>