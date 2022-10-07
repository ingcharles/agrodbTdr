<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorProgramasControlOficial.php';
	
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$cpco = new ControladorProgramasControlOficial();
	
	$largoCabecera=18;
	$largoDetalleEspeciesAtacadas=5;
	$largoDetalleQuiropterosCapturados=4;
	$largoDetalleQuiropterosTratados=5;
	$largoDetalleSitiosCaptura=6;
	
	
	$numSolicitud = htmlspecialchars ($_POST['bNumSolicitud'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['bFechaCreacion'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['bNombrePredio'],ENT_NOQUOTES,'UTF-8');
	$nombrePropietario = htmlspecialchars ($_POST['bNombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$nombreAsociacion = htmlspecialchars ($_POST['bNombreAsociacion'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$sitio = htmlspecialchars ($_POST['bSitio'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	
	//Función buscarInspeccionOCCS
	$inspeccionOCCSCabecera = $cpco->buscarInspeccionOCCS($conexion, $numSolicitud, $fecha, $nombrePredio,  
														  $nombrePropietario, $nombreAsociacion, $idProvincia, 
														  $idCanton, $idParroquia, $sector, $estado);
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
				Programas de Control Oficial - Catastro de Ovinos, Caprinos y Camélidos Sudamericanos<br>
			</div>
			
			<div id="direccion"></div>
			<div id="imprimir">
				<form id="filtrar" action="reporteInspeccionOCCSDetalleExcel.php" target="_blank" method="post">
				
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
						<th>NOMBRE DEL PREDIO</th>
						<th>NOMBRE DEL PROPIETARIO</th>
						<th>CÉDULA DEL PROPIETARIO</th>
						<th>TELÉFONO</th>
						<th>CORREO ELECTRÓNICO</th>
						<th>NOMBRE DE LA ASOCIACIÓN</th>
						<th>PROVINCIA</th>
						<th>CANTÓN</th>
						<th>PARROQUIA</th>
						<th>SECTOR</th>
						<th>UTM X</th>
						<th>UTM Y</th>
						<th>UTM Z</th>
						<th>ESTADO</th>
						<th>OBSERVACIONES</th>
						
						<!-- TIPO EXPLOTACIÓN -->			
						<th>ID</th>
						<th>ID TIPO EXPLOTACIÓN</th>
						<th>EXPLOTACIÓN</th>
						<th>SUPERFICIE EXPLOTACIÓN (Ha.)</th>
						
						<!-- ESPECIES -->			
						<th>ID</th>
						<th>ID ESPECIE</th>
						<th>ESPECIE</th>
						<th>RAZA</th>
						<th>CATEGORÍA</th>
						<th># ANIMALES</th>	
						
						<!-- BIOSEGURIDAD -->			
						<th>ID</th>
						<th>ID BIOSEGURIDAD</th>
						<th>CALENDARIO DE VACUNACIÓN</th>
						<th>VACUNA</th>
						<th>CALENDARIO DE DESPARASITACIÓN</th>
						<th>FRECUENCIA</th>
						<th>ASESORAMIENTO TÉCNICO</th>
						<th>NOMBRE ASESOR TÉCNICO</th>
						<th>PROFESIÓN ASESOR TÉCNICO</th>
						<th>IDENTIFICACIÓN INDIVIDUAL</th>
						<th>TIPO DE IDENTIFICACIÓN</th>
						<th>TIPO DE ALIMENTACIÓN</th>
						<th>CORRAL MANEJO</th>
						<th>REGISTROS PRODUCTIVOS</th>
						<th>TIPO DE PRODUCCIÓN</th>
						<th>SECTOR PERTENECIENTE</th>
						  
						
						<!-- HISTORIAL PATOLOGÍAS -->			
						<th>ID</th>
						<th>ID HISTORIAL PATOLOGÍAS</th>
						<th>ENFERMEDADES</th>   
					</tr>
				</thead>
				
			<tbody>
				 <?php	 
					 //Matriz Inspección OCCS
					 while($inspeccionOCCS = pg_fetch_assoc($inspeccionOCCSCabecera)){
					 	 
					 	echo '<tr>
							    <td class="formatoTexto">'.$inspeccionOCCS['id_inspeccion_occs'].'</td>
						        <td class="formatoTexto">'.$inspeccionOCCS['num_solicitud'].'</td>
						        <td class="formatoTexto">'.$inspeccionOCCS['fecha'].'</td>
						    	<td class="formatoTexto">'.$inspeccionOCCS['nombre_predio'].'</td>
								<td class="formatoTexto">'.$inspeccionOCCS['nombre_propietario'].'</td>
								<td class="formatoTexto">'.$inspeccionOCCS['cedula_propietario'].'</td>
								<td class="formatoTexto">'.$inspeccionOCCS['telefono'].'</td>
								<td class="formatoTexto">'.$inspeccionOCCS['correo_electronico'].'</td>
								<td class="formatoTexto">'.$inspeccionOCCS['nombre_asociacion'].'</td>
						        <td class="formatoTexto">'.$inspeccionOCCS['provincia'].'</td>
						    	<td class="formatoTexto">'.$inspeccionOCCS['canton'].'</td>
						        <td class="formatoTexto">'.$inspeccionOCCS['parroquia'].'</td>
						        <td class="formatoTexto">'.$inspeccionOCCS['sector'].'</td>			    	
						    	<td class="formatoTexto">'.$inspeccionOCCS['utm_x'].'</td>
					 			<td class="formatoTexto">'.$inspeccionOCCS['utm_y'].'</td>
								<td class="formatoTexto">'.$inspeccionOCCS['utm_z'].'</td>
								<td class="formatoTexto">'.$inspeccionOCCS['estado'].'</td>
								<td class="formatoTexto">'.$inspeccionOCCS['observaciones'].'</td>';
								
					 	
						$tiposExplotacionInspeccionOCCS = $cpco->listarTipoExplotacionInspeccionOCCS($conexion, $inspeccionOCCS['id_inspeccion_occs']);
						$especieInspeccionOCCS = $cpco->listarEspecieInspeccionOCCS($conexion, $inspeccionOCCS['id_inspeccion_occs']);
						$bioseguridadInspeccionOCCS = $cpco->listarBioseguridadInspeccionOCCS($conexion, $inspeccionOCCS['id_inspeccion_occs']);
						$enfermedadInspeccionOCCS = $cpco->listarHistorialPatologiasInspeccionOCCS($conexion, $inspeccionOCCS['id_inspeccion_occs']);
	
					 	$mayor = max(pg_num_rows($tiposExplotacionInspeccionOCCS), pg_num_rows($especieInspeccionOCCS), pg_num_rows($bioseguridadInspeccionOCCS), pg_num_rows($enfermedadInspeccionOCCS));
					 	
					 	if(pg_num_rows($tiposExplotacionInspeccionOCCS) != 0){
						 	while ($fila = pg_fetch_assoc($tiposExplotacionInspeccionOCCS)){
						 		$tiposExplotacion[] = array(id_inspeccion_occs=>$fila['id_inspeccion_occs'],
						 									 id_inspeccion_occs_tipo_explotacion=>$fila['id_inspeccion_occs_tipo_explotacion'],
											 				 explotacion=>$fila['explotacion'],
											 				 superficie_explotacion=>$fila['superficie_explotacion']);
						 	}
					 	}
					 	
					 	if(pg_num_rows($especieInspeccionOCCS) != 0){
					 		while ($fila = pg_fetch_assoc($especieInspeccionOCCS)){
					 			$especie[] = array(id_inspeccion_occs=>$fila['id_inspeccion_occs'],
								 					 id_inspeccion_occs_especie=>$fila['id_inspeccion_occs_especie'],
								 					 especie=>$fila['especie'],
		 											 raza=>$fila['raza'],
		 											 categoria=>$fila['categoria'],
								 					 numero_animales=>$fila['numero_animales']);
					 		}
					 	}
					 	
					 	if(pg_num_rows($bioseguridadInspeccionOCCS) != 0){
					 		while ($fila = pg_fetch_assoc($bioseguridadInspeccionOCCS)){
					 			$bioseguridad[] = array(id_inspeccion_occs=>$fila['id_inspeccion_occs'],
									 					id_inspeccion_occs_bioseguridad=>$fila['id_inspeccion_occs_bioseguridad'],
									 					calendario_vacunacion=>$fila['calendario_vacunacion'],
									 					vacuna=>$fila['vacuna'],
									 					calendario_desparacitacion=>$fila['calendario_desparacitacion'],
									 					frecuencia=>$fila['frecuencia'],
									 					asesoramiento_tecnico=>$fila['asesoramiento_tecnico'],
									 					nombre_asesor_tecnico=>$fila['nombre_asesor_tecnico'],
									 					profesion=>$fila['profesion'],
									 					identificacion_individual=>$fila['identificacion_individual'],
									 					tipo_identificacion=>$fila['tipo_identificacion'],
									 					tipo_alimentacion=>$fila['tipo_alimentacion'],
									 					corral_manejo=>$fila['corral_manejo'],
									 					registros_productivos=>$fila['registros_productivos'],
		 												tipo_produccion=>$fila['tipo_produccion'],
		 												sector_perteneciente=>$fila['sector_perteneciente']
					 			);
					 		}
					 	}
					 	
					 	if(pg_num_rows($enfermedadInspeccionOCCS) != 0){
					 		while ($fila = pg_fetch_assoc($enfermedadInspeccionOCCS)){
					 			$enfermedad[] = array(id_inspeccion_occs=>$fila['id_inspeccion_occs'],
									 					id_inspeccion_occs_historial_patologias=>$fila['id_inspeccion_occs_historial_patologias'],
									 					enfermedad=>$fila['enfermedad']);
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
				 				
				 			echo ' <td class="formatoTexto">'.$tiposExplotacion[$i]['id_inspeccion_occs'].'</td>
									<td class="formatoTexto">'.$tiposExplotacion[$i]['id_inspeccion_occs_tipo_explotacion'].'</td>
							        <td class="formatoTexto">'.$tiposExplotacion[$i]['explotacion'].'</td>
							        <td class="formatoTexto">'.$tiposExplotacion[$i]['superficie_explotacion'].'</td>
						        		
						        	<td class="formatoTexto">'.$especie[$i]['id_inspeccion_occs'].'</td>
									<td class="formatoTexto">'.$especie[$i]['id_inspeccion_occs_especie'].'</td>
							        <td class="formatoTexto">'.$especie[$i]['especie'].'</td>
							        <td class="formatoTexto">'.$especie[$i]['raza'].'</td>
									<td class="formatoTexto">'.$especie[$i]['categoria'].'</td>
									<td class="formatoTexto">'.$especie[$i]['numero_animales'].'</td>
					 					
					 				<td class="formatoTexto">'.$bioseguridad[$i]['id_inspeccion_occs'].'</td>
									<td class="formatoTexto">'.$bioseguridad[$i]['id_inspeccion_occs_bioseguridad'].'</td>
							        <td class="formatoTexto">'.$bioseguridad[$i]['calendario_vacunacion'].'</td>
							        <td class="formatoTexto">'.$bioseguridad[$i]['vacuna'].'</td>
									<td class="formatoTexto">'.$bioseguridad[$i]['calendario_desparacitacion'].'</td>
							        <td class="formatoTexto">'.$bioseguridad[$i]['frecuencia'].'</td>
									<td class="formatoTexto">'.$bioseguridad[$i]['asesoramiento_tecnico'].'</td>
							        <td class="formatoTexto">'.$bioseguridad[$i]['nombre_asesor_tecnico'].'</td>
					 				<td class="formatoTexto">'.$bioseguridad[$i]['profesion'].'</td>
							        <td class="formatoTexto">'.$bioseguridad[$i]['identificacion_individual'].'</td>
									<td class="formatoTexto">'.$bioseguridad[$i]['tipo_identificacion'].'</td>
							        <td class="formatoTexto">'.$bioseguridad[$i]['tipo_alimentacion'].'</td>
								    <td class="formatoTexto">'.$bioseguridad[$i]['corral_manejo'].'</td>
							        <td class="formatoTexto">'.$bioseguridad[$i]['registros_productivos'].'</td>
									<td class="formatoTexto">'.$bioseguridad[$i]['tipo_produccion'].'</td>
									<td class="formatoTexto">'.$bioseguridad[$i]['sector_perteneciente'].'</td>
								
							        
									<td class="formatoTexto">'.$enfermedad[$i]['id_inspeccion_occs'].'</td>
							        <td class="formatoTexto">'.$enfermedad[$i]['id_inspeccion_occs_historial_patologias'].'</td>
						    		<td class="formatoTexto">'.$enfermedad[$i]['enfermedad'].'</td>
					  			</tr>';
				 		
				 			$j++;
					 	}
					 	
					 	$tiposExplotacion = null;
					 	$especie = null;
					 	$bioseguridad = null;
					 	$enfermedad = null;
					 }
				 ?>
				
				</tbody>
			</table>
		</div>
	</body>
</html>