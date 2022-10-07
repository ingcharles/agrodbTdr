<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorBrucelosisTuberculosis.php';
	
	$conexion = new Conexion();
	$cbt = new ControladorBrucelosisTuberculosis();
	
	$largoCabecera=27;
	
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
	$recertificacionesBTCabecera = $cbt->buscarRecertificacionBT($conexion, $numSolicitud, $fecha, $nombrePredio, 
														$nombrePropietario, $idProvincia, $idCanton, 
														$idParroquia, $certificacion, $estado)

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
						Expediente de Recertificación de Predios Libres de Brucelosis y Tuberculosis<br>
			</div>
			
			<div id="direccion"></div>
			<div id="imprimir">
				<form id="filtrar" action="expedienteRecertificacionBTDetalleExcel.php" target="_blank" method="post">
				
				 <input type="hidden" id="bNumSolicitud" name="bNumSolicitud" value="<?php echo $_POST['bNumSolicitud'];?>" />
				 <input type="hidden" id="bFechaCreacion" name="bFechaCreacion" value="<?php echo $_POST['bFechaCreacion'];?>" />
				 <input type="hidden" id="bNombrePredio" name="bNombrePredio" value="<?php echo $_POST['bNombrePredio'];?>" />
				 <input type="hidden" id="bNombrePropietario" name="bNombrePropietario" value="<?php echo $_POST['bNombrePropietario'];?>" />
				 <input type="hidden" id="bNombreAsociacion" name="bNombreAsociacion" value="<?php echo $_POST['bNombreAsociacion'];?>" />
				 <input type="hidden" id="bIdProvincia" name="bIdProvincia" value="<?php echo $_POST['bIdProvincia'];?>" />
				 <input type="hidden" id="bIdCanton" name="bIdCanton" value="<?php echo $_POST['bIdCanton'];?>" />
				 <input type="hidden" id="bIdParroquia" name="bIdParroquia" value="<?php echo $_POST['bIdParroquia'];?>" />	 
				 <input type="hidden" id="bSitio" name="bIdSitio" value="<?php echo $_POST['bSitio'];?>" />
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
						<th>NÚMERO RECERTIFICACIÓN</th>
						<th>FECHA MUESTREO BRUCELOSIS</th>
						<th>FECHA TUBERCULINIZACIÓN</th>
						<th>NOMBRE TÉCNICO RESPONSABLE</th>
						<th>LABORATORIO</th>
						<th>FECHA APROBACIÓN</th> 
						<th>ESTADO</th>
						<th>OBSERVACIONES</th>
						
						<!-- INFORMACION DEL PREDIO -->			
						<th>ID</th>
						<th>ID INFORMACION DEL PREDIO</th>
						<th>INSPECCIÓN</th>
						<th>CERRAMIENTOS</th>
						<th>CONTROL INGRESO PERSONAS</th>
						<th>CONTROL INGRESO ANIMALES</th>
						<th>IDENTIFICACIÓN INDIVIDUAL BOVINOS</th>
						<th>MANGA, EMBUDO, BRETE</th>
						
						
						<!-- INVENTARIO DE ANIMALES -->			
						<th>ID</th>
						<th>ID INVENTARIO ANIMAL</th>
						<th>INSPECCIÓN</th>
						<th>ANIMALES PREDIO</th>
						<th>NÚMERO EXISTENCIAS</th>
						  
						
						<!-- MANEJO ANIMAL -->			
						<th>ID</th>
						<th>ID MANEJO ANIMAL</th>
						<th>INSPECCIÓN</th>
						<th>PASTOS COMUNALES</th>
						<th>ARRIENDA SUS POTREROS</th>
						<th>ARRIENDA OTROS POTREROS</th>
						<th>LLEVA ANIMALES A FERIAS</th>
						<th>DESINFECTA ANIMALES</th>
						<th>ESTÁN EN PROGRAMA PLBT</th>
						
						<!-- ADQUISICION ANIMALES -->			
						<th>ID</th>
						<th>ID ADQUISICION ANIMAL</th>
						<th>INSPECCIÓN</th>
						<th>PROCEDENCIA ANIMALES</th>
						<th>CATEGORÍA</th>
						
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
						<th>LOTE VACUNA</th>
						<th>FECHA VACUNACIÓN</th>
						
						<!-- PATOLOGIAS -->			
						<th>ID</th>
						<th>ID PATOLOGIA</th>
						<th>INSPECCIÓN</th>
						<th>RETENCIÓN DE PLACENTA</th>
						<th>NACIMIENTO TERNEROS DÉBILES</th>
						<th>METRITIS POST-PARTO</th>
						<th>ABORTOS</th>
						<th>FIEBRE FLUCTUANTE</th>
						
						<!-- LABORATORIO -->			
						<th>ID</th>
						<th>ID LABORATORIO</th>
						<th>INSPECCIÓN</th>
						<th>RESULTADO</th>
						<th>OBSERVACIONES</th>
						<!-- >th>INFORME</th-->
					</tr>
				</thead>
				
			<tbody>
				 <?php	 
					 //Matriz CertificacionBT
					 while($certificacionesBT = pg_fetch_assoc($recertificacionesBTCabecera)){
					 	 
					 	echo '<tr>
							    <td class="formatoTexto">'.$certificacionesBT['id_recertificacion_bt'].'</td>
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
								<td class="formatoTexto">'.$certificacionesBT['num_recertificacion'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['fecha_muestreo_brucelosis'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['fecha_tuberculinizacion'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['nombre_tecnico_responsable'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['laboratorio'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['fecha_aprobacion'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['estado'].'</td>
								<td class="formatoTexto">'.$certificacionesBT['observaciones'].'</td>';
										
					 	$idRecertificacionBT = $certificacionesBT['id_recertificacion_bt'];
					 	
						//Recertificacion
						$rInformacionPredio = $cbt->abrirInformacionPredioRecertificacionBT($conexion, $idRecertificacionBT);
						$rInventario = $cbt->abrirInventarioAnimalRecertificacionBT($conexion, $idRecertificacionBT);
						$rManejoAnimal = $cbt->abrirManejoAnimalRecertificacionBT($conexion, $idRecertificacionBT);
						$rAdquisicionAnimales = $cbt->abrirAdquisicionAnimalesRecertificacionBT($conexion, $idRecertificacionBT);
						$rVeterinario = $cbt->abrirVeterinarioRecertificacionBT($conexion, $idRecertificacionBT);
						$rVacunacion = $cbt->abrirVacunacionRecertificacionBT($conexion, $idRecertificacionBT);
						$rPatologia = $cbt->abrirPatologiaBrucelosisRecertificacionBT($conexion, $idRecertificacionBT);
						//Laboratorio
						$rResultadoLaboratorio = $cbt->abrirResultadoLaboratorioRecertificacion($conexion, $idRecertificacionBT);
												
					 	$mayor = max(pg_num_rows($rInformacionPredio), pg_num_rows($rInventario), 
					 			pg_num_rows($rManejoAnimal), pg_num_rows($rAdquisicionAnimales),
					 			pg_num_rows($rVeterinario), pg_num_rows($rVacunacion), 
					 			pg_num_rows($rPatologia), pg_num_rows($rResultadoLaboratorio));
					 	
					 	if(pg_num_rows($rInformacionPredio) != 0){
						 	while ($fila = pg_fetch_assoc($rInformacionPredio)){
						 		$predio[] = array(
						 				id_recertificacion_bt_informacion_predio=>$fila['id_recertificacion_bt_informacion_predio'],
	 									 id_recertificacion_bt=>$fila['id_recertificacion_bt'],
						 				 num_inspeccion=>$fila['num_inspeccion'],
						 				 cerramientos=>$fila['cerramientos'],
						 				 control_ingreso_personas=>$fila['control_ingreso_personas'],
						 				 control_ingreso_animal=>$fila['control_ingreso_animal'],
						 				 identificacion_bovinos=>$fila['identificacion_bovinos'],
						 				 manga_embudo_brete=>$fila['manga_embudo_brete']);
						 	}
					 	}
					 	
					 	if(pg_num_rows($rInventario) != 0){
					 		while ($fila = pg_fetch_assoc($rInventario)){
					 			$inventarioCBT[] = array(
					 					id_recertificacion_bt_inventario_animal=>$fila['id_recertificacion_bt_inventario_animal'],
					 					id_recertificacion_bt=>$fila['id_recertificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					animales_predio=>$fila['animales_predio'],
					 					numero_animales_predio=>$fila['numero_animales_predio']);
					 		}
					 	}
					 	
					 	if(pg_num_rows($rManejoAnimal) != 0){
					 		while ($fila = pg_fetch_assoc($rManejoAnimal)){
					 			$manejoAnimalCBT[] = array(
					 					id_recertificacion_bt_manejo_animales_potreros=>$fila['id_recertificacion_bt_manejo_animales_potreros'],
					 					id_recertificacion_bt=>$fila['id_recertificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					pastos_comunales=>$fila['pastos_comunales'],
					 					arrienda_potreros=>$fila['arrienda_potreros'],
					 					arrienda_otros_potreros=>$fila['arrienda_otros_potreros'],
					 					animales_ferias=>$fila['animales_ferias'],
					 					desinfecta_animales=>$fila['desinfecta_animales'],
					 					dentro_programa_predios_libres=>$fila['dentro_programa_predios_libres']
					 			);
					 		}
					 	}
					 	
					 	if(pg_num_rows($rAdquisicionAnimales) != 0){
					 		while ($fila = pg_fetch_assoc($rAdquisicionAnimales)){
					 			$adquisicion[] = array(
					 					id_recertificacion_bt_adquisicion_animales=>$fila['id_recertificacion_bt_adquisicion_animales'],
					 					id_recertificacion_bt=>$fila['id_recertificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					procedencia_animales=>$fila['procedencia_animales'],
	 									categoria_animales_adquiriente=>$fila['categoria_animales_adquiriente']);
					 		}
					 	}
					 	

					 	if(pg_num_rows($rVeterinario) != 0){
					 		while ($fila = pg_fetch_assoc($rVeterinario)){
					 			$veterinarioCBT[] = array(
					 					id_recertificacion_bt_veterinario=>$fila['id_recertificacion_bt_veterinario'],
					 					id_recertificacion_bt=>$fila['id_recertificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					nombre_veterinario=>$fila['nombre_veterinario'],
					 					telefono_veterinario=>$fila['telefono_veterinario'],
					 					celular_veterinario=>$fila['celular_veterinario'],
					 					correo_electronico_veterinario=>$fila['correo_electronico_veterinario'],
					 					frecuencia_visita_veterinario=>$fila['frecuencia_visita_veterinario']
					 			);
					 		}
					 	}
					 	
					 	
					 	if(pg_num_rows($rVacunacion) != 0){
					 		while ($fila = pg_fetch_assoc($rVacunacion)){
					 			$vacunacionCBT[] = array(
					 					id_recertificacion_bt_informacion_vacunacion=>$fila['id_recertificacion_bt_informacion_vacunacion'],
					 					id_recertificacion_bt=>$fila['id_recertificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					calendario_vacunacion=>$fila['calendario_vacunacion'],
					 					motivo_vacunacion=>$fila['motivo_vacunacion'],
					 					vacunas_aplicadas=>$fila['vacunas_aplicadas'],
					 					lote_vacuna=>$fila['lote_vacuna'],
					 					fecha_vacunacion=>$fila['fecha_vacunacion']
					 			);
					 		}
					 	}
					 	
					 	if(pg_num_rows($rPatologia) != 0){
					 		while ($fila = pg_fetch_assoc($rPatologia)){
					 			$patologiaBrucelosisCBT[] = array(
					 					id_recertificacion_bt_patologia_brucelosis=>$fila['id_recertificacion_bt_patologia_brucelosis'],
					 					id_recertificacion_bt=>$fila['id_recertificacion_bt'],
					 					num_inspeccion=>$fila['num_inspeccion'],
					 					retencion_placenta=>$fila['retencion_placenta'],
					 					nacimiento_terneros_debiles=>$fila['nacimiento_terneros_debiles'],
					 					metritis_post_parto=>$fila['metritis_post_parto'],
					 					abortos=>$fila['abortos'],
					 					fiebre=>$fila['fiebre']
					 			);
					 		}
					 	}					 	
					 	
					 	//Laboratorio
					 		
					 	if(pg_num_rows($rResultadoLaboratorio) != 0){
					 		while ($fila = pg_fetch_assoc($rResultadoLaboratorio)){
					 			$resultadoLaboratorioCBT[] = array(
					 					id_recertificacion_bt_resultado_laboratorio=>$fila['id_recertificacion_bt_resultado_laboratorio'],
					 					id_recertificacion_bt=>$fila['id_recertificacion_bt'],
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
				 				
				 			echo ' <td class="formatoTexto">'.$predio[$i]['id_recertificacion_bt'].'</td>
									<td class="formatoTexto">'.$predio[$i]['id_recertificacion_bt_informacion_predio'].'</td>
							        <td class="formatoTexto">'.$predio[$i]['num_inspeccion'].'</td>
		 							<td class="formatoTexto">'.$predio[$i]['cerramientos'].'</td>
		 							<td class="formatoTexto">'.$predio[$i]['control_ingreso_personas'].'</td>
		 							<td class="formatoTexto">'.$predio[$i]['control_ingreso_animal'].'</td>
		 							<td class="formatoTexto">'.$predio[$i]['identificacion_bovinos'].'</td>
		 							<td class="formatoTexto">'.$predio[$i]['manga_embudo_brete'].'</td>

									<td class="formatoTexto">'.$inventarioCBT[$i]['id_recertificacion_bt'].'</td>
									<td class="formatoTexto">'.$inventarioCBT[$i]['id_recertificacion_bt_inventario_animal'].'</td>
							        <td class="formatoTexto">'.$inventarioCBT[$i]['num_inspeccion'].'</td>
							        <td class="formatoTexto">'.$inventarioCBT[$i]['animales_predio'].'</td>
									<td class="formatoTexto">'.$inventarioCBT[$i]['numero_animales_predio'].'</td>						
				 											
				 					<td class="formatoTexto">'.$manejoAnimalCBT[$i]['id_recertificacion_bt'].'</td>
									<td class="formatoTexto">'.$manejoAnimalCBT[$i]['id_recertificacion_bt_manejo_animales_potreros'].'</td>
							        <td class="formatoTexto">'.$manejoAnimalCBT[$i]['num_inspeccion'].'</td>
							        <td class="formatoTexto">'.$manejoAnimalCBT[$i]['pastos_comunales'].'</td>
									<td class="formatoTexto">'.$manejoAnimalCBT[$i]['arrienda_potreros'].'</td>
									<td class="formatoTexto">'.$manejoAnimalCBT[$i]['arrienda_otros_potreros'].'</td>
		 							<td class="formatoTexto">'.$manejoAnimalCBT[$i]['animales_ferias'].'</td>
		 							<td class="formatoTexto">'.$manejoAnimalCBT[$i]['desinfecta_animales'].'</td>
		 							<td class="formatoTexto">'.$manejoAnimalCBT[$i]['dentro_programa_predios_libres'].'</td>
				 											
					 				<td class="formatoTexto">'.$adquisicion[$i]['id_recertificacion_bt'].'</td>
									<td class="formatoTexto">'.$adquisicion[$i]['id_recertificacion_bt_adquisicion_animales'].'</td>
							        <td class="formatoTexto">'.$adquisicion[$i]['num_inspeccion'].'</td>
							        <td class="formatoTexto">'.$adquisicion[$i]['procedencia_animales'].'</td>
									<td class="formatoTexto">'.$adquisicion[$i]['categoria_animales_adquiriente'].'</td>
					 							
							        <td class="formatoTexto">'.$veterinarioCBT[$i]['id_recertificacion_bt'].'</td>
									<td class="formatoTexto">'.$veterinarioCBT[$i]['id_recertificacion_bt_veterinario'].'</td>
							        <td class="formatoTexto">'.$veterinarioCBT[$i]['num_inspeccion'].'</td>
								    <td class="formatoTexto">'.$veterinarioCBT[$i]['nombre_veterinario'].'</td>
							        <td class="formatoTexto">'.$veterinarioCBT[$i]['telefono_veterinario'].'</td>
									<td class="formatoTexto">'.$veterinarioCBT[$i]['celular_veterinario'].'</td>
									<td class="formatoTexto">'.$veterinarioCBT[$i]['correo_electronico_veterinario'].'</td>
					 				<td class="formatoTexto">'.$veterinarioCBT[$i]['frecuencia_visita_veterinario'].'</td>
								
							        
									<td class="formatoTexto">'.$vacunacionCBT[$i]['id_recertificacion_bt'].'</td>
							        <td class="formatoTexto">'.$vacunacionCBT[$i]['id_recertificacion_bt_informacion_vacunacion'].'</td>
						    		<td class="formatoTexto">'.$vacunacionCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$vacunacionCBT[$i]['calendario_vacunacion'].'</td>
							        <td class="formatoTexto">'.$vacunacionCBT[$i]['motivo_vacunacion'].'</td>
						    		<td class="formatoTexto">'.$vacunacionCBT[$i]['vacunas_aplicadas'].'</td>
							        <td class="formatoTexto">'.$vacunacionCBT[$i]['lote_vacuna'].'</td>
						    		<td class="formatoTexto">'.$vacunacionCBT[$i]['fecha_vacunacion'].'</td>
					 					
					 				<td class="formatoTexto">'.$patologiaBrucelosisCBT[$i]['id_recertificacion_bt'].'</td>
							        <td class="formatoTexto">'.$patologiaBrucelosisCBT[$i]['id_recertificacion_bt_patologia_brucelosis'].'</td>
						    		<td class="formatoTexto">'.$patologiaBrucelosisCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$patologiaBrucelosisCBT[$i]['retencion_placenta'].'</td>
							        <td class="formatoTexto">'.$patologiaBrucelosisCBT[$i]['nacimiento_terneros_debiles'].'</td>
						    		<td class="formatoTexto">'.$patologiaBrucelosisCBT[$i]['metritis_post_parto'].'</td>
							        <td class="formatoTexto">'.$patologiaBrucelosisCBT[$i]['abortos'].'</td>
						    		<td class="formatoTexto">'.$patologiaBrucelosisCBT[$i]['fiebre'].'</td>
					 					
					 				<!--//Laboratorio-->	
					 					
					 				<td class="formatoTexto">'.$resultadoLaboratorioCBT[$i]['id_recertificacion_bt'].'</td>
							        <td class="formatoTexto">'.$resultadoLaboratorioCBT[$i]['id_recertificacion_bt_resultado_laboratorio'].'</td>
						    		<td class="formatoTexto">'.$resultadoLaboratorioCBT[$i]['num_inspeccion'].'</td>
					 				<td class="formatoTexto">'.$resultadoLaboratorioCBT[$i]['resultado_analisis'].'</td>
							        <td class="formatoTexto">'.$resultadoLaboratorioCBT[$i]['observaciones'].'</td>
					 				<!--td class="formatoTexto">'.$resultadoLaboratorioCBT[$i]['archivo_informe'].'</td-->
					 					
						        		
						        	
					  			</tr>';
				 		
				 			$j++;
					 	}
					 	
					 	$predio = null;
						$inventarioCBT = null;
						$manejoAnimalCBT = null;
						$adquisicion = null;
						$veterinarioCBT = null;
						$vacunacionCBT = null;
						$patologiaTuberculosisCBT = null;
						$resultadoLaboratorioCBT = null;
					 }
				 ?>
				
				</tbody>
			</table>
		</div>
	</body>
</html>