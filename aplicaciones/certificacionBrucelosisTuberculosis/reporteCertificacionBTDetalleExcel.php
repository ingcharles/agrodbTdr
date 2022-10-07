<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorBrucelosisTuberculosis.php';
	
	$conexion = new Conexion();
	$cbt = new ControladorBrucelosisTuberculosis();
	
	header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	header("Content-Disposition: attachment; filename=CertificacionBT.xls");
	//con esto evitamos que el navegador lo grabe en su caché
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$largoCabecera=25;
	
	$numSolicitud = htmlspecialchars ($_POST['bNumSolicitud'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['bFechaCreacion'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['bNombrePredio'],ENT_NOQUOTES,'UTF-8');
	$nombrePropietario = htmlspecialchars ($_POST['bNombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$certificacion = htmlspecialchars ($_POST['bCertificacion'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	
	//Función buscarCertificacionBT
	$certificacionesBTCabecera = $cbt->buscarCertificacionBT($conexion, $numSolicitud, $fecha, $nombrePredio,
														$nombrePropietario, $idProvincia, $idCanton,
														$idParroquia, $certificacion, $estado, null);
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		
		<style type="text/css">
			
			#tablaReporteControlOficial
			{
			font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
				width: 100%;
				margin: 0;
				padding: 0;
			    border-collapse:collapse;
			}
			
			#tablaReporteControlOficial td, #tablaReporteControlOficial th 
			{
			font-size:1em;
			border:0.5px solid #000000;
			padding:1px 3px 1px 3px;
			}
			
			#tablaReporteControlOficial th 
			{
			font-size:1em;
			text-align:left;
			padding-top:3px;
			padding-bottom:2px;
			background-color:#A7C942;
			color:#ffffff;
			}
			
			#textoTitulo{
			font-size:12em;
			text-align: center;
			float:left;
			}
			
			#textoSubtitulo{
			text-align: center;
			float:left;
			}
			
			.formatoTexto{
			 mso-style-parent:style0;
			 mso-number-format:"\@";
			}
			
			.formatoNumeroDecimal4{
			 mso-style-parent:style0;
			 mso-number-format:"0.0000";
			}
			
			#logotexto{
			width: 10%;
			height:80px;
			float: left;
			}
			
			#textoTitulo{
			width: 40%;
			height:80px;
			text-align: center;
			float:left;
			}
			
			#textoPOA{
			width: 40%;
			height:80px;
			text-align: center;
			float:left;
			}
		
			#logoMagap{
			width: 15%;
			height:70px;
			background-image: url(../img/magap_logo.jpg); background-repeat: no-repeat;
			float: left;
			}
			
			#logotexto{
			width: 10%;
			height:80px;
			float: left;
			}
			
			#logoAgrocalidad{
			width: 20%;
			height:80px;
			background-image: url(../img/agrocalidad.png); background-repeat: no-repeat;
			float:left;
			}
			
			#textoTitulo{
			width: 40%;
			height:80px;
			text-align: center;
			float:left;
			}
			
			#direccion{
			width: 8%;
			height:80px;
			background-image: url(../img/direccion.png); background-repeat: no-repeat;
			float: left;
			}
		</style>
	</head>
	
	<body>
		<div id="header">
		   	<div id="logoMagap"></div>
			<div id="texto"></div>
			<div id="logoAgrocalidad"></div>
			<div id="textoPOA">Ministerio de Agricultura y Ganadería<Br>
						Agencia Ecuatoriana de Aseguramiento de la Calidad del Agro Agrocalidad<Br>
							Certificación de Predios Libres de Brucelosis y Tuberculosis<br>
			</div>
			
			<div id="direccion"></div>
			<div id="imprimir"></div>			
			<div id="bandera"></div>
		</div>
		
		<div id="tabla">
		
							<table id="tablaReporteControlOficial" class="soloImpresion">
				<thead>
					<tr>
						<th>ID</th>
					    <th>NÚMERO SOLICITUD</th>
						<th>FECHA REGISTRO</th>
						<th>NOMBRE DEL ENCUESTADO</th>
						<th>NOMBRE DEL PREDIO</th>
						<th>NOMBRE DEL PROPIETARIO</th>
						<th>CÉDULA DEL PROPIETARIO</th>
						<th>TELÉFONO</th>
						<th>CELULAR</th>
						<th>CORREO ELECTRÓNICO</th>
						<th>PROVINCIA</th>
						<th>CANTÓN</th>
						<th>PARROQUIA</th>
						<th>CERTIFICACIÓN FIEBRE AFTOSA</th>
						<th>CERTIFICACIÓN</th>
						<th>UTM X</th>
						<th>UTM Y</th>
						<th>UTM Z</th>
						<th>HUSO/ZONA</th>
						<th>LABORATORIO</th>
						<th>FECHA APROBACIÓN</th> 
						<th>ESTADO</th>
						<th>OBSERVACIONES</th>
						
						<!-- INFORMACION DEL PREDIO -->			
						<th>ID</th>
						<th>ID INFORMACION DEL PREDIO</th>
						<th>INSPECCIÓN</th>
						<th>SUPERFICIE PREDIO (Ha.)</th>
						<th>SUPERFICIE PASTOS</th>
						<th>CERRAMIENTOS</th>
						<th>CONTROL INGRESO PERSONAS</th>
						<th>CONTROL INGRESO ANIMALES</th>
						<th>IDENTIFICACIÓN INDIVIDUAL BOVINOS</th>
						<th>MANGA, EMBUDO, BRETE</th>
						
						
						<!-- PRODUCCION, EXPLOTACION Y DESTINOS REGISTRADOS -->			
						<th>ID</th>
						<th>ID PRODUCCIÓN</th>
						<th>INSPECCIÓN</th>
						<th>TIPO PRODUCCIÓN</th>
						<th>DESTINO PRODUCCIÓN</th>
						<th>TIPO EXPLOTACIÓN</th>	
						
						<!-- INVENTARIO DE ANIMALES -->			
						<th>ID</th>
						<th>ID INVENTARIO ANIMAL</th>
						<th>INSPECCIÓN</th>
						<th>ANIMALES PREDIO</th>
						<th>NÚMERO EXISTENCIAS</th>
						  
						
						<!-- PEDILUVIOS -->			
						<th>ID</th>
						<th>ID PEDILUVIO</th>
						<th>INSPECCIÓN</th>
						<th>PEDILUVIOS EXISTENTES</th>   
						
						<!-- MANEJO ANIMAL -->			
						<th>ID</th>
						<th>ID MANEJO ANIMAL</th>
						<th>INSPECCIÓN</th>
						<th>PASTOS COMUNALES</th>
						<th>ARRIENDA SUS POTREROS</th>
						<th>ARRIENDA OTROS POTREROS</th>
						<th>ESTIÉRCOL COMO ABONO</th>
						<th>LLEVA ANIMALES A FERIAS</th>
						<th>DESINFECTA ANIMALES</th>
						<th>TRABAJADORES TIENEN ANIMALES</th>
						<th>ESTÁN EN PROGRAMA PLBT</th>
						
						<!-- ADQUISICION ANIMALES -->			
						<th>ID</th>
						<th>ID ADQUISICION ANIMAL</th>
						<th>INSPECCIÓN</th>
						<th>PROCEDENCIA ANIMALES</th>
						<th>CATEGORÍA</th>
						
						<!-- PROCEDENCIA AGUA -->			
						<th>ID</th>
						<th>ID PROCEDENCIA AGUA</th>
						<th>INSPECCIÓN</th>
						<th>PROCEDENCIA AGUA</th>
						
						<!-- VETERINARIO -->			
						<th>ID</th>
						<th>ID VETERINARIO</th>
						<th>INSPECCIÓN</th>
						<th>NOMBRE</th>
						<th>TELÉFONO</th>
						<th>CELULAR</th>
						<th>CORREO ELECTRÓNICO</th>
						<th>FRECUENCIA VISITA</th>
						
						<!-- VACUNACION -->			
						<th>ID</th>
						<th>ID VACUNACION</th>
						<th>INSPECCIÓN</th>
						<th>CALENDARIO VACUNACIÓN</th>
						<th>PERÍODO VACUNACIÓN</th>
						<th>VACUNA APLICADA</th>
						<th>PROCEDENCIA VACUNA</th>
						<th>FECHA VACUNACIÓN</th>
						
						<!-- REPRODUCCION -->			
						<th>ID</th>
						<th>ID REPRODUCCIÓN</th>
						<th>INSPECCIÓN</th>
						<th>SISTEMA EMPLEADO</th>
						<th>PROCEDENCIA PAJUELAS</th>
						<th>LUGAR PARA PARICIONES</th>
						<th>REALIZA DESINFECCIÓN</th>
						
						<!-- PATOLOGIAS TUBERCULOSIS -->			
						<th>ID</th>
						<th>ID PATOLOGIA</th>
						<th>INSPECCIÓN</th>
						<th>PÉRDIDA PESO</th>
						<th>PÉRDIDA APETITO</th>
						<th>PROBLEMAS RESPIRATORIOS</th>
						<th>TOS INTERMITENTE</th>
						<th>ABULTAMIENTOS EN CUERPO</th>
						<th>FIEBRE FLUCTUANTE</th>
						
						<!-- PRUEBAS TUBERCULINA -->			
						<th>ID</th>
						<th>ID TUBERCULINA</th>
						<th>INSPECCIÓN</th>
						<th>PRUEBA TUBERCULINA</th>
						<th>RESULTADO</th>
						<th>PRUEBAS</th>
						<th>LABORATORIO</th>
						<th>DESTINO ANIMALES POSITIVOS</th>
						
						<!-- PATOLOGIAS BRUCELOSIS -->			
						<th>ID</th>
						<th>ID PATOLOGIA</th>
						<th>INSPECCIÓN</th>
						<th>RETENCIÓN PLACENTA</th>
						<th>NACIMIENTO TERNEROS DÉBILES</th>
						<th>PROBLEMAS ESTERILIDAD</th>
						<th>METRITIS POST-PARTO</th>
						<th>HINCHAZÓN ARTICULACIONES</th>
						<th>EPIDIDIMITIS Y ORQUITIS MACHOS</th>
						
						<!-- ABORTOS -->			
						<th>ID</th>
						<th>ID ABORTOS</th>
						<th>INSPECCIÓN</th>
						<th>ABORTOS PRESENTADOS</th>
						<th>NÚMERO ABORTOS</th>
						<th>DESTINO TEJIDOS ABORTADOS</th>
						
						<!-- BRUCELOSIS LECHE -->			
						<th>ID</th>
						<th>ID BRUCELOSIS LECHE</th>
						<th>INSPECCIÓN</th>
						<th>PRUEBA BRUCELOSIS EN LECHE</th>
						<th>RESULTADO</th>
						<th>PRUEBA</th>
						<th>LABORATORIO</th>
						
						<!-- BRUCELOSIS SANGRE -->			
						<th>ID</th>
						<th>ID BRUCELOSIS SANGRE</th>
						<th>INSPECCIÓN</th>
						<th>PRUEBA BRUCELOSIS SANGRE</th>
						<th>RESULTADO</th>
						<th>PRUEBA</th>
						<th>LABORATORIO</th>
						<th>DESTINO ANIMALES POSITIVOS</th>
						
						<!-- LABORATORIO -->			
						<th>ID</th>
						<th>ID LABORATORIO</th>
						<th>INSPECCIÓN</th>
						<th>RESULTADO</th>
						<th>OBSERVACIONES</th>
						<th>INFORME</th>
					</tr>
				</thead>
				
			<tbody>
				 <?php	 
					 //Matriz CertificacionBT
					 while($certificacionesBT = pg_fetch_assoc($certificacionesBTCabecera)){
					 	 
					 	echo '<tr>
							    <td class="formatoTexto">'.$certificacionesBT['id_certificacion_bt'].'</td>
						        <td class="formatoTexto">'.$certificacionesBT['num_solicitud'].'</td>
						        <td class="formatoTexto">'.$certificacionesBT['fecha'].'</td>
						    	<td class="formatoTexto">'.$certificacionesBT['nombre_encuestado'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['nombre_predio'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['nombre_propietario'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['cedula_propietario'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['telefono_propietario'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['celular_propietario'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['correo_electronico_propietario'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['provincia'].'</td>
						    	<td class="formatoTexto">'.$certificacionesBT['canton'].'</td>
						        <td class="formatoTexto">'.$certificacionesBT['parroquia'].'</td>
						        <td class="formatoTexto">'.$certificacionesBT['numero_certificado_fiebre_aftosa'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['certificacion_bt'].'</td>
						    	<td class="formatoTexto">'.$certificacionesBT['utm_x'].'</td>
					 			<td class="formatoTexto">'.$certificacionesBT['utm_y'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['utm_z'].'</td>
					 			<td class="formatoTexto">'.$certificacionesBT['huso_zona'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['laboratorio'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['fecha_aprobacion'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['estado'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['observaciones'].'</td>';
										
					 	$idCertificacionBT = $certificacionesBT['id_certificacion_bt'];
					 	
						$informacionPredio = $cbt->abrirInformacionPredioCertificacionBT($conexion, $idCertificacionBT);
						$produccion = $cbt->abrirProduccionCertificacionBT($conexion, $idCertificacionBT);
						$inventario = $cbt->abrirInventarioAnimalCertificacionBT($conexion, $idCertificacionBT);
						$pediluvio = $cbt->abrirPediluvioCertificacionBT($conexion, $idCertificacionBT);
						$manejoAnimal = $cbt->abrirManejoAnimalCertificacionBT($conexion, $idCertificacionBT);
						$adquisicionAnimales = $cbt->abrirAdquisicionAnimalesCertificacionBT($conexion, $idCertificacionBT);
						$procedenciaAgua = $cbt->abrirProcedenciaAguaCertificacionBT($conexion, $idCertificacionBT);
						$veterinario = $cbt->abrirVeterinarioCertificacionBT($conexion, $idCertificacionBT);
						$vacunacion = $cbt->abrirVacunacionCertificacionBT($conexion, $idCertificacionBT);
						$reproduccion = $cbt->abrirReproduccionCertificacionBT($conexion, $idCertificacionBT);
						//Tuberculosis
						$patologiaTuberculosis = $cbt->abrirPatologiaTuberculosisCertificacionBT($conexion, $idCertificacionBT);
						$pruebasTuberculina = $cbt->abrirPruebaTuberculinaCertificacionBT($conexion, $idCertificacionBT);
						//Brucelosis
						$patologia = $cbt->abrirPatologiaBrucelosisCertificacionBT($conexion, $idCertificacionBT);
						$abortos = $cbt->abrirAbortosBrucelosisCertificacionBT($conexion, $idCertificacionBT);
						$pruebasLeche = $cbt->abrirPruebasBrucelosisLecheCertificacionBT($conexion, $idCertificacionBT);
						$pruebasSangre = $cbt->abrirPruebasBrucelosisSangreCertificacionBT($conexion, $idCertificacionBT);
						//Laboratorio
						$resultadoLaboratorio = $cbt->abrirResultadoLaboratorio($conexion, $idCertificacionBT);
						

						
						
						
					 	$mayor = max(pg_num_rows($informacionPredio), pg_num_rows($produccion), 
					 			pg_num_rows($inventario), pg_num_rows($pediluvio),
					 			pg_num_rows($manejoAnimal), pg_num_rows($adquisicionAnimales), 
					 			pg_num_rows($procedenciaAgua), pg_num_rows($veterinario),
					 			pg_num_rows($vacunacion), pg_num_rows($reproduccion), 
					 			pg_num_rows($patologiaTuberculosis), pg_num_rows($pruebasTuberculina),
					 			pg_num_rows($patologia), pg_num_rows($abortos), 
					 			pg_num_rows($pruebasLeche), pg_num_rows($pruebasSangre),
					 			pg_num_rows($resultadoLaboratorio));
					 	
					 	if(pg_num_rows($informacionPredio) != 0){
						 	while ($fila = pg_fetch_assoc($informacionPredio)){
						 		$predio[] = array(id_certificacion_bt_informacion_predio=>$fila['id_certificacion_bt_informacion_predio'],
				 									 id_certificacion_bt=>$fila['id_certificacion_bt'],
									 				 num_inspeccion=>$fila['num_inspeccion'],
									 				 superficie_predio=>$fila['superficie_predio'],
									 				 superficie_pastos=>$fila['superficie_pastos'],
									 				 cerramientos=>$fila['cerramientos'],
									 				 control_ingreso_personas=>$fila['control_ingreso_personas'],
									 				 control_ingreso_animal=>$fila['control_ingreso_animal'],
									 				 identificacion_bovinos=>$fila['identificacion_bovinos'],
									 				 manga_embudo_brete=>$fila['manga_embudo_brete']);
						 	}
					 	}
					 	
					 	if(pg_num_rows($produccion) != 0){
					 		while ($fila = pg_fetch_assoc($produccion)){
					 			$produccionCBT[] = array(id_certificacion_bt_produccion=>$fila['id_certificacion_bt_produccion'],
									 					 id_certificacion_bt=>$fila['id_certificacion_bt'],
									 					 num_inspeccion=>$fila['num_inspeccion'],
			 											 tipo_produccion=>$fila['tipo_produccion'],
			 											 destino_leche=>$fila['destino_leche'],
									 					 tipo_explotacion=>$fila['tipo_explotacion']);
					 		}
					 	}
					 	
					 	if(pg_num_rows($inventario) != 0){
					 		while ($fila = pg_fetch_assoc($inventario)){
					 			$inventarioCBT[] = array(id_certificacion_bt_inventario_animal=>$fila['id_certificacion_bt_inventario_animal'],
					 					id_certificacion_bt=>$fila['id_certificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					animales_predio=>$fila['animales_predio'],
					 					numero_animales_predio=>$fila['numero_animales_predio']);
					 		}
					 	}
					 	
					 	if(pg_num_rows($pediluvio) != 0){
					 		while ($fila = pg_fetch_assoc($pediluvio)){
					 			$pediluvioCBT[] = array(id_certificacion_bt_pediluvio=>$fila['id_certificacion_bt_pediluvio'],
					 					id_certificacion_bt=>$fila['id_certificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					pediluvio=>$fila['pediluvio']);
					 		}
					 	}
					 	
					 	
					 	if(pg_num_rows($manejoAnimal) != 0){
					 		while ($fila = pg_fetch_assoc($manejoAnimal)){
					 			$manejoAnimalCBT[] = array(id_certificacion_bt_manejo_animales_potreros=>$fila['id_certificacion_bt_manejo_animales_potreros'],
									 					id_certificacion_bt=>$fila['id_certificacion_bt'],
									 					num_inspeccion=>$fila['num_inspeccion'],
									 					pastos_comunales=>$fila['pastos_comunales'],
									 					arrienda_potreros=>$fila['arrienda_potreros'],
									 					arrienda_otros_potreros=>$fila['arrienda_otros_potreros'],
									 					estiercol_abono=>$fila['estiercol_abono'],
									 					animales_ferias=>$fila['animales_ferias'],
									 					desinfecta_animales=>$fila['desinfecta_animales'],
									 					trabajadores_animales_predio=>$fila['trabajadores_animales_predio'],
									 					dentro_programa_predios_libres=>$fila['dentro_programa_predios_libres']
					 			);
					 		}
					 	}
					 	
					 	if(pg_num_rows($adquisicionAnimales) != 0){
					 		while ($fila = pg_fetch_assoc($adquisicionAnimales)){
					 			$adquisicion[] = array(id_certificacion_bt_adquisicion_animales=>$fila['id_certificacion_bt_adquisicion_animales'],
									 					id_certificacion_bt=>$fila['id_certificacion_bt'],
									 					num_inspeccion=>$fila['num_inspeccion'],
									 					procedencia_animales=>$fila['procedencia_animales'],
					 									categoria_animales_adquiriente=>$fila['categoria_animales_adquiriente']);
					 		}
					 	}
					 	

					 	if(pg_num_rows($procedenciaAgua) != 0){
					 		while ($fila = pg_fetch_assoc($procedenciaAgua)){
					 			$aguaCBT[] = array(id_certificacion_bt_procedencia_agua=>$fila['id_certificacion_bt_procedencia_agua'],
								 					id_certificacion_bt=>$fila['id_certificacion_bt'],
								 					num_inspeccion=>$fila['num_inspeccion'],
								 					procedencia_agua=>$fila['procedencia_agua']);
					 		}
					 	}
					 		
					 		
					 	if(pg_num_rows($veterinario) != 0){
					 		while ($fila = pg_fetch_assoc($veterinario)){
					 			$veterinarioCBT[] = array(id_certificacion_bt_veterinario=>$fila['id_certificacion_bt_veterinario'],
					 					id_certificacion_bt=>$fila['id_certificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					nombre_veterinario=>$fila['nombre_veterinario'],
					 					telefono_veterinario=>$fila['telefono_veterinario'],
					 					celular_veterinario=>$fila['celular_veterinario'],
					 					correo_electronico_veterinario=>$fila['correo_electronico_veterinario'],
					 					frecuencia_visita_veterinario=>$fila['frecuencia_visita_veterinario']
					 			);
					 		}
					 	}
					 	
					 	
					 	if(pg_num_rows($vacunacion) != 0){
					 		while ($fila = pg_fetch_assoc($vacunacion)){
					 			$vacunacionCBT[] = array(id_certificacion_bt_informacion_vacunacion=>$fila['id_certificacion_bt_informacion_vacunacion'],
					 					id_certificacion_bt=>$fila['id_certificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					calendario_vacunacion=>$fila['calendario_vacunacion'],
					 					motivo_vacunacion=>$fila['motivo_vacunacion'],
					 					vacunas_aplicadas=>$fila['vacunas_aplicadas'],
					 					procedencia_vacunas=>$fila['procedencia_vacunas'],
					 					fecha_vacunacion=>$fila['fecha_vacunacion']
					 			);
					 		}
					 	}
					 	
					 	if(pg_num_rows($reproduccion) != 0){
					 		while ($fila = pg_fetch_assoc($reproduccion)){
					 			$reproduccionCBT[] = array(id_certificacion_bt_reproduccion=>$fila['id_certificacion_bt_reproduccion'],
					 					id_certificacion_bt=>$fila['id_certificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					sistema_empleado=>$fila['sistema_empleado'],
					 					procedencia_pajuelas=>$fila['procedencia_pajuelas'],
					 					lugar_pariciones=>$fila['lugar_pariciones'],
					 					realiza_desinfeccion=>$fila['realiza_desinfeccion']
					 			);
					 		}
					 	}
					 	
					 	//Tuberculosis
					 	
					 	if(pg_num_rows($patologiaTuberculosis) != 0){
					 		while ($fila = pg_fetch_assoc($patologiaTuberculosis)){
					 			$patologiaTuberculosisCBT[] = array(id_certificacion_bt_patologia_tuberculosis=>$fila['id_certificacion_bt_patologia_tuberculosis'],
					 					id_certificacion_bt=>$fila['id_certificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					perdida_peso=>$fila['perdida_peso'],
					 					perdida_apetito=>$fila['perdida_apetito'],
					 					problemas_respiratorios=>$fila['problemas_respiratorios'],
					 					tos_intermitente=>$fila['tos_intermitente'],
					 					abultamiento=>$fila['abultamiento'],
					 					fiebre_fluctuante=>$fila['fiebre_fluctuante']
					 			);
					 		}
					 	}
					 	
					 	if(pg_num_rows($pruebasTuberculina) != 0){
					 		while ($fila = pg_fetch_assoc($pruebasTuberculina)){
					 			$pruebasTuberculinaCBT[] = array(id_certificacion_bt_prueba_tuberculina=>$fila['id_certificacion_bt_prueba_tuberculina'],
					 					id_certificacion_bt=>$fila['id_certificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					pruebas_tuberculina=>$fila['pruebas_tuberculina'],
					 					resultado_tuberculina=>$fila['resultado_tuberculina'],
					 					pruebas_laboratorio=>$fila['pruebas_laboratorio'],
					 					laboratorio=>$fila['laboratorio'],
					 					destino_animales_positivos=>$fila['destino_animales_positivos']
					 			);
					 		}
					 	}
					 	
					 	//Brucelosis
					 		
					 	if(pg_num_rows($patologia) != 0){
					 		while ($fila = pg_fetch_assoc($patologia)){
					 			$patologiaCBT[] = array(id_certificacion_bt_patologia_brucelosis=>$fila['id_certificacion_bt_patologia_brucelosis'],
					 					id_certificacion_bt=>$fila['id_certificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					retencion_placenta=>$fila['retencion_placenta'],
					 					nacimient_terneros_debiles=>$fila['nacimient_terneros_debiles'],
					 					problemas_esterilidad=>$fila['problemas_esterilidad'],
					 					metritis_post_parto=>$fila['metritis_post_parto'],
					 					hinchazon_articulaciones=>$fila['hinchazon_articulaciones'],
					 					epididimitis_orquitis=>$fila['epididimitis_orquitis']
					 			);
					 		}
					 	}
					 		
					 	if(pg_num_rows($abortos) != 0){
					 		while ($fila = pg_fetch_assoc($abortos)){
					 			$abortosCBT[] = array(id_certificacion_bt_abortos_brucelosis=>$fila['id_certificacion_bt_abortos_brucelosis'],
					 					id_certificacion_bt=>$fila['id_certificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					abortos=>$fila['abortos'],
					 					numero_abortos=>$fila['numero_abortos'],
					 					tejidos_abortados=>$fila['tejidos_abortados']
					 			);
					 		}
					 	}
					 	
					 	if(pg_num_rows($pruebasLeche) != 0){
					 		while ($fila = pg_fetch_assoc($pruebasLeche)){
					 			$pruebasLecheCBT[] = array(id_certificacion_bt_prueba_brucelosis_leche=>$fila['id_certificacion_bt_prueba_brucelosis_leche'],
					 					id_certificacion_bt=>$fila['id_certificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					pruebas_brucelosis_leche=>$fila['pruebas_brucelosis_leche'],
					 					resultado_brucelosis_leche=>$fila['resultado_brucelosis_leche'],
					 					pruebas_laboratorio=>$fila['pruebas_laboratorio'],
					 					laboratorio=>$fila['laboratorio']
					 			);
					 		}
					 	}
					 	
					 	if(pg_num_rows($pruebasSangre) != 0){
					 		while ($fila = pg_fetch_assoc($pruebasSangre)){
					 			$pruebasSangreCBT[] = array(id_certificacion_bt_prueba_brucelosis_sangre=>$fila['id_certificacion_bt_prueba_brucelosis_sangre'],
					 					id_certificacion_bt=>$fila['id_certificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					pruebas_brucelosis_sangre=>$fila['pruebas_brucelosis_sangre'],
					 					resultado_brucelosis_sangre=>$fila['resultado_brucelosis_sangre'],
					 					pruebas_laboratorio=>$fila['pruebas_laboratorio'],
					 					laboratorio=>$fila['laboratorio'],
					 					destino_animales_positivos=>$fila['destino_animales_positivos']
					 			);
					 		}
					 	}
					 	
					 	//Laboratorio
					 		
					 	if(pg_num_rows($resultadoLaboratorio) != 0){
					 		while ($fila = pg_fetch_assoc($resultadoLaboratorio)){
					 			$resultadoLaboratorioCBT[] = array(id_certificacion_bt_resultado_laboratorio=>$fila['id_certificacion_bt_resultado_laboratorio'],
					 					id_certificacion_bt=>$fila['id_certificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					resultado_analisis=>$fila['resultado_analisis'],
					 					observaciones=>$fila['observaciones'],
					 					archivo_informe=>$fila['archivo_informe']
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
				 				
				 			echo ' <td class="formatoTexto">'.$predio[$i]['id_certificacion_bt'].'</td>
									<td class="formatoTexto">'.$predio[$i]['id_certificacion_bt_informacion_predio'].'</td>
							        <td class="formatoTexto">'.$predio[$i]['num_inspeccion'].'</td>
							        <td class="formatoTexto">'.$predio[$i]['superficie_predio'].'</td>
		 							<td class="formatoTexto">'.$predio[$i]['superficie_pastos'].'</td>
		 							<td class="formatoTexto">'.$predio[$i]['cerramientos'].'</td>
		 							<td class="formatoTexto">'.$predio[$i]['control_ingreso_personas'].'</td>
		 							<td class="formatoTexto">'.$predio[$i]['control_ingreso_animal'].'</td>
		 							<td class="formatoTexto">'.$predio[$i]['identificacion_bovinos'].'</td>
		 							<td class="formatoTexto">'.$predio[$i]['manga_embudo_brete'].'</td>

									<td class="formatoTexto">'.$produccionCBT[$i]['id_certificacion_bt'].'</td>
									<td class="formatoTexto">'.$produccionCBT[$i]['id_certificacion_bt_produccion'].'</td>
							        <td class="formatoTexto">'.$produccionCBT[$i]['num_inspeccion'].'</td>
							        <td class="formatoTexto">'.$produccionCBT[$i]['tipo_produccion'].'</td>
									<td class="formatoTexto">'.$produccionCBT[$i]['destino_leche'].'</td>
									<td class="formatoTexto">'.$produccionCBT[$i]['tipo_explotacion'].'</td>
					 					
				 					<td class="formatoTexto">'.$inventarioCBT[$i]['id_certificacion_bt'].'</td>
									<td class="formatoTexto">'.$inventarioCBT[$i]['id_certificacion_bt_inventario_animal'].'</td>
							        <td class="formatoTexto">'.$inventarioCBT[$i]['num_inspeccion'].'</td>
							        <td class="formatoTexto">'.$inventarioCBT[$i]['animales_predio'].'</td>
									<td class="formatoTexto">'.$inventarioCBT[$i]['numero_animales_predio'].'</td>						
				 											
				 					<td class="formatoTexto">'.$pediluvioCBT[$i]['id_certificacion_bt'].'</td>
									<td class="formatoTexto">'.$pediluvioCBT[$i]['id_certificacion_bt_pediluvio'].'</td>
							        <td class="formatoTexto">'.$pediluvioCBT[$i]['num_inspeccion'].'</td>
							        <td class="formatoTexto">'.$pediluvioCBT[$i]['pediluvio'].'</td>						
				 											
				 					<td class="formatoTexto">'.$manejoAnimalCBT[$i]['id_certificacion_bt'].'</td>
									<td class="formatoTexto">'.$manejoAnimalCBT[$i]['id_certificacion_bt_manejo_animales_potreros'].'</td>
							        <td class="formatoTexto">'.$manejoAnimalCBT[$i]['num_inspeccion'].'</td>
							        <td class="formatoTexto">'.$manejoAnimalCBT[$i]['pastos_comunales'].'</td>
									<td class="formatoTexto">'.$manejoAnimalCBT[$i]['arrienda_potreros'].'</td>
									<td class="formatoTexto">'.$manejoAnimalCBT[$i]['arrienda_otros_potreros'].'</td>
					 				<td class="formatoTexto">'.$manejoAnimalCBT[$i]['estiercol_abono'].'</td>
		 							<td class="formatoTexto">'.$manejoAnimalCBT[$i]['animales_ferias'].'</td>
		 							<td class="formatoTexto">'.$manejoAnimalCBT[$i]['desinfecta_animales'].'</td>
		 							<td class="formatoTexto">'.$manejoAnimalCBT[$i]['trabajadores_animales_predio'].'</td>
		 							<td class="formatoTexto">'.$manejoAnimalCBT[$i]['dentro_programa_predios_libres'].'</td>
				 											
					 				<td class="formatoTexto">'.$adquisicion[$i]['id_certificacion_bt'].'</td>
									<td class="formatoTexto">'.$adquisicion[$i]['id_certificacion_bt_adquisicion_animales'].'</td>
							        <td class="formatoTexto">'.$adquisicion[$i]['num_inspeccion'].'</td>
							        <td class="formatoTexto">'.$adquisicion[$i]['procedencia_animales'].'</td>
									<td class="formatoTexto">'.$adquisicion[$i]['categoria_animales_adquiriente'].'</td>
					 							
							        <td class="formatoTexto">'.$aguaCBT[$i]['id_certificacion_bt'].'</td>
									<td class="formatoTexto">'.$aguaCBT[$i]['id_certificacion_bt_procedencia_agua'].'</td>
							        <td class="formatoTexto">'.$aguaCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$aguaCBT[$i]['procedencia_agua'].'</td>
					 				
							        <td class="formatoTexto">'.$veterinarioCBT[$i]['id_certificacion_bt'].'</td>
									<td class="formatoTexto">'.$veterinarioCBT[$i]['id_certificacion_bt_veterinario'].'</td>
							        <td class="formatoTexto">'.$veterinarioCBT[$i]['num_inspeccion'].'</td>
								    <td class="formatoTexto">'.$veterinarioCBT[$i]['nombre_veterinario'].'</td>
							        <td class="formatoTexto">'.$veterinarioCBT[$i]['telefono_veterinario'].'</td>
									<td class="formatoTexto">'.$veterinarioCBT[$i]['celular_veterinario'].'</td>
									<td class="formatoTexto">'.$veterinarioCBT[$i]['correo_electronico_veterinario'].'</td>
					 				<td class="formatoTexto">'.$veterinarioCBT[$i]['frecuencia_visita_veterinario'].'</td>
								
							        
									<td class="formatoTexto">'.$vacunacionCBT[$i]['id_certificacion_bt'].'</td>
							        <td class="formatoTexto">'.$vacunacionCBT[$i]['id_certificacion_bt_informacion_vacunacion'].'</td>
						    		<td class="formatoTexto">'.$vacunacionCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$vacunacionCBT[$i]['calendario_vacunacion'].'</td>
							        <td class="formatoTexto">'.$vacunacionCBT[$i]['motivo_vacunacion'].'</td>
						    		<td class="formatoTexto">'.$vacunacionCBT[$i]['vacunas_aplicadas'].'</td>
							        <td class="formatoTexto">'.$vacunacionCBT[$i]['procedencia_vacunas'].'</td>
						    		<td class="formatoTexto">'.$vacunacionCBT[$i]['fecha_vacunacion'].'</td>
					 					
					 				<td class="formatoTexto">'.$reproduccionCBT[$i]['id_certificacion_bt'].'</td>
							        <td class="formatoTexto">'.$reproduccionCBT[$i]['id_certificacion_bt_reproduccion'].'</td>
						    		<td class="formatoTexto">'.$reproduccionCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$reproduccionCBT[$i]['sistema_empleado'].'</td>
							        <td class="formatoTexto">'.$reproduccionCBT[$i]['procedencia_pajuelas'].'</td>
							        <td class="formatoTexto">'.$reproduccionCBT[$i]['lugar_pariciones'].'</td>
						    		<td class="formatoTexto">'.$reproduccionCBT[$i]['realiza_desinfeccion'].'</td>
					 					
					 				<!--//Tuberculosis-->	
					 					
					 				<td class="formatoTexto">'.$patologiaTuberculosisCBT[$i]['id_certificacion_bt'].'</td>
							        <td class="formatoTexto">'.$patologiaTuberculosisCBT[$i]['id_certificacion_bt_patologia_tuberculosis'].'</td>
						    		<td class="formatoTexto">'.$patologiaTuberculosisCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$patologiaTuberculosisCBT[$i]['perdida_peso'].'</td>
							        <td class="formatoTexto">'.$patologiaTuberculosisCBT[$i]['perdida_apetito'].'</td>
						    		<td class="formatoTexto">'.$patologiaTuberculosisCBT[$i]['problemas_respiratorios'].'</td>
							        <td class="formatoTexto">'.$patologiaTuberculosisCBT[$i]['tos_intermitente'].'</td>
						    		<td class="formatoTexto">'.$patologiaTuberculosisCBT[$i]['abultamiento'].'</td>
					 				<td class="formatoTexto">'.$patologiaTuberculosisCBT[$i]['fiebre_fluctuante'].'</td>
					 					
					 				<td class="formatoTexto">'.$pruebasTuberculinaCBT[$i]['id_certificacion_bt'].'</td>
							        <td class="formatoTexto">'.$pruebasTuberculinaCBT[$i]['id_certificacion_bt_prueba_tuberculina'].'</td>
						    		<td class="formatoTexto">'.$pruebasTuberculinaCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$pruebasTuberculinaCBT[$i]['pruebas_tuberculina'].'</td>
							        <td class="formatoTexto">'.$pruebasTuberculinaCBT[$i]['resultado_tuberculina'].'</td>
							        <td class="formatoTexto">'.$pruebasTuberculinaCBT[$i]['pruebas_laboratorio'].'</td>
						    		<td class="formatoTexto">'.$pruebasTuberculinaCBT[$i]['laboratorio'].'</td>
					 				<td class="formatoTexto">'.$pruebasTuberculinaCBT[$i]['destino_animales_positivos'].'</td>
					 					
					 				<!--//Brucelosis-->	
					 					
					 				<td class="formatoTexto">'.$patologiaCBT[$i]['id_certificacion_bt'].'</td>
							        <td class="formatoTexto">'.$patologiaCBT[$i]['id_certificacion_bt_patologia_brucelosis'].'</td>
						    		<td class="formatoTexto">'.$patologiaCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$patologiaCBT[$i]['retencion_placenta'].'</td>
							        <td class="formatoTexto">'.$patologiaCBT[$i]['nacimient_terneros_debiles'].'</td>
						    		<td class="formatoTexto">'.$patologiaCBT[$i]['problemas_esterilidad'].'</td>
							        <td class="formatoTexto">'.$patologiaCBT[$i]['metritis_post_parto'].'</td>
						    		<td class="formatoTexto">'.$patologiaCBT[$i]['hinchazon_articulaciones'].'</td>
					 				<td class="formatoTexto">'.$patologiaCBT[$i]['epididimitis_orquitis'].'</td>
					 					
					 				<td class="formatoTexto">'.$abortosCBT[$i]['id_certificacion_bt'].'</td>
							        <td class="formatoTexto">'.$abortosCBT[$i]['id_certificacion_bt_abortos_brucelosis'].'</td>
						    		<td class="formatoTexto">'.$abortosCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$abortosCBT[$i]['abortos'].'</td>
							        <td class="formatoTexto">'.$abortosCBT[$i]['numero_abortos'].'</td>
							        <td class="formatoTexto">'.$abortosCBT[$i]['tejidos_abortados'].'</td>
					 					
					 				<td class="formatoTexto">'.$pruebasLecheCBT[$i]['id_certificacion_bt'].'</td>
							        <td class="formatoTexto">'.$pruebasLecheCBT[$i]['id_certificacion_bt_prueba_brucelosis_leche'].'</td>
						    		<td class="formatoTexto">'.$pruebasLecheCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$pruebasLecheCBT[$i]['pruebas_brucelosis_leche'].'</td>
							        <td class="formatoTexto">'.$pruebasLecheCBT[$i]['resultado_brucelosis_leche'].'</td>
						    		<td class="formatoTexto">'.$pruebasLecheCBT[$i]['pruebas_laboratorio'].'</td>
							        <td class="formatoTexto">'.$pruebasLecheCBT[$i]['laboratorio'].'</td>
					 					
					 				<td class="formatoTexto">'.$pruebasSangreCBT[$i]['id_certificacion_bt'].'</td>
							        <td class="formatoTexto">'.$pruebasSangreCBT[$i]['id_certificacion_bt_prueba_brucelosis_sangre'].'</td>
						    		<td class="formatoTexto">'.$pruebasSangreCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$pruebasSangreCBT[$i]['pruebas_brucelosis_sangre'].'</td>
							        <td class="formatoTexto">'.$pruebasSangreCBT[$i]['resultado_brucelosis_sangre'].'</td>
						    		<td class="formatoTexto">'.$pruebasSangreCBT[$i]['pruebas_laboratorio'].'</td>
							        <td class="formatoTexto">'.$pruebasSangreCBT[$i]['laboratorio'].'</td>
							        <td class="formatoTexto">'.$pruebasSangreCBT[$i]['destino_animales_positivos'].'</td>
					 					
					 				<!--//Laboratorio-->	
					 					
					 				<td class="formatoTexto">'.$resultadoLaboratorioCBT[$i]['id_certificacion_bt'].'</td>
							        <td class="formatoTexto">'.$resultadoLaboratorioCBT[$i]['id_certificacion_bt_resultado_laboratorio'].'</td>
						    		<td class="formatoTexto">'.$resultadoLaboratorioCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$resultadoLaboratorioCBT[$i]['resultado_analisis'].'</td>
							        <td class="formatoTexto">'.$resultadoLaboratorioCBT[$i]['observaciones'].'</td>
					 				<td class="formatoTexto">'.($resultadoLaboratorioCBT[$i]['archivo_informe']!=''? '<a href='.$resultadoLaboratorioCBT[$i]['archivo_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar informe</a>':'')	.'</td>
					 					
						        		
						        	
					  			</tr>';
				 		
				 			$j++;
					 	}
					 	
					 	$predio = null;
						$produccionCBT = null;
						$inventarioCBT = null;
						$pediluvioCBT = null;
						$manejoAnimalCBT = null;
						$adquisicion = null;
						$aguaCBT = null;
						$veterinarioCBT = null;
						$vacunacionCBT = null;
						$reproduccionCBT = null;
						$patologiaTuberculosisCBT = null;
						$pruebasTuberculinaCBT = null;
						$patologiaCBT = null;
						$abortosCBT = null;
						$pruebasLecheCBT = null;
						$pruebasSangreCBT = null;
						$resultadoLaboratorioCBT = null;
					 }
				 ?>
				
				</tbody>
			</table>
		</div>
	</body>
</html>