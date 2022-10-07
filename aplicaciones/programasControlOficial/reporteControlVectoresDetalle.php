<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorProgramasControlOficial.php';
	
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$cpco = new ControladorProgramasControlOficial();
	
	$largoCabecera=20;
	$largoDetalleEspeciesAtacadas=5;
	$largoDetalleQuiropterosCapturados=4;
	$largoDetalleQuiropterosTratados=5;
	$largoDetalleSitiosCaptura=6;
	
	
	$numSolicitud = htmlspecialchars ($_POST['bNumSolicitud'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['bFechaCreacion'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['bNombrePredio'],ENT_NOQUOTES,'UTF-8');
	$nombrePropietario = htmlspecialchars ($_POST['bNombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$sitio = htmlspecialchars ($_POST['bSitio'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	
	//Función buscarControlVectores
	$controlVectoresCabecera = $cpco->buscarControlVectores($conexion, $numSolicitud, $fecha, 
															$nombrePredio, $nombrePropietario, 
															$idProvincia, $idCanton, $idParroquia, 
															$sitio, $estado);
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
				Programas de Control Oficial - Control de Vectores con Uso de Mallas Tipo Neblina<br>
			</div>
			
			<div id="direccion"></div>
			<div id="imprimir">
				<form id="filtrar" action="reporteControlVectoresDetalleExcel.php" target="_blank" method="post">
				
				 <input type="hidden" id="bNumSolicitud" name="bNumSolicitud" value="<?php echo $_POST['bNumSolicitud'];?>" />
				 <input type="hidden" id="bFechaCreacion" name="bFechaCreacion" value="<?php echo $_POST['bFechaCreacion'];?>" />
				 <input type="hidden" id="bNombrePredio" name="bNombrePredio" value="<?php echo $_POST['bNombrePredio'];?>" />
				 <input type="hidden" id="bNombrePropietario" name="bNombrePropietario" value="<?php echo $_POST['bNombrePropietario'];?>" />
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
						<th>FASE LUNAR</th>
						<th>DURACIÓN</th>
						<th>FECHA DESDE</th>
						<th>FECHA HASTA</th>
						<th>NOMBRE DEL PREDIO</th>
						<th>NOMBRE DEL PROPIETARIO</th>
						<th>PROVINCIA</th>
						<th>CANTÓN</th>
						<th>PARROQUIA</th>
						<th>SITIO</th>
						<th>SITIO DE CAPTURA</th>
						<th>COBERTURA DEL CORRAL</th>
						<th>UTM X</th>
						<th>UTM Y</th>
						<th>UTM Z</th>
						<th>ESTADO</th>
						<th>OBSERVACIONES</th>
						
						<!-- ESPECIES ATACADAS -->			
						<th>ID</th>
						<th>ID ESPECIES ATACADAS</th>
						<th>ESPECIE</th>
						<th># EXISTENCIAS EN EL PREDIO</th>
						<th># ANIMALES CON MORDEDURAS</th>	
						
						<!-- QUIRÓPTEROS CAPTURADOS -->			
						<th>ID</th>
						<th>ID QUIRÓPTEROS CAPTURADOS</th>
						<th>QUIRÓPTERO</th>
						<th># EXISTENCIAS</th>	
						
						<!-- QUIRÓPTEROS TRATADOS -->			
						<th>ID</th>
						<th>ID QUIRÓPTEROS TRATADOS</th>
						<th># VAMPIROS TRATADOS</th>
						<th># VAMPIROS NO TRATADOS</th>
						<th># VAMPIROS ENVIADOS AL LABORATORIO</th>  
						
						<!-- SITIOS DE CAPTURA -->			
						<th>ID</th>
						<th>ID SITIO CAPTURA</th>
						<th>MALLA</th>
						<th>ESPECIE</th>
						<th># CAPTURAS</th>
						<th>OBSERVACIONES MALLA</th>   
					</tr>
				</thead>
				
			<tbody>
				 <?php	 
					 //Matriz Control Vectores
					 while($controlVectores = pg_fetch_assoc($controlVectoresCabecera)){
					 	 
					 	echo '<tr>
							    <td class="formatoTexto">'.$controlVectores['id_control_vectores'].'</td>
						        <td class="formatoTexto">'.$controlVectores['num_solicitud'].'</td>
						        <td class="formatoTexto">'.$controlVectores['fecha'].'</td>
						    	<td class="formatoTexto">'.$controlVectores['fase_lunar'].'</td>
						    	<td class="formatoTexto">'.$controlVectores['duracion'].'</td>
						        <td class="formatoTexto">'.$controlVectores['fecha_desde'].'</td>
						        <td class="formatoTexto">'.$controlVectores['fecha_hasta'].'</td>
						        <td class="formatoTexto">'.$controlVectores['nombre_predio'].'</td>
								<td class="formatoTexto">'.$controlVectores['nombre_propietario'].'</td>
						        <td class="formatoTexto">'.$controlVectores['provincia'].'</td>
						    	<td class="formatoTexto">'.$controlVectores['canton'].'</td>
						        <td class="formatoTexto">'.$controlVectores['parroquia'].'</td>
						        <td class="formatoTexto">'.$controlVectores['sitio'].'</td>
						    	<td class="formatoTexto">'.$controlVectores['sitio_captura'].'</td>			    	
						    	<td class="formatoTexto">'.$controlVectores['cobertura_corral'].'</td>			    	
						    	<td class="formatoTexto">'.$controlVectores['utm_x'].'</td>
					 			<td class="formatoTexto">'.$controlVectores['utm_y'].'</td>
								<td class="formatoTexto">'.$controlVectores['utm_z'].'</td>
								<td class="formatoTexto">'.$controlVectores['estado'].'</td>
								<td class="formatoTexto">'.$controlVectores['observaciones'].'</td>';
								
					 	
					 	//Función listarEspeciesAtacadasControlVectores
						$controlVectoresEspeciesAtacadas = $cpco->listarEspeciesAtacadasControlVectores($conexion, $controlVectores['id_control_vectores']);
						//Función listarQuiropterosCapturadosControlVectores
						$controlVectoresQuiropterosCapturados = $cpco->listarQuiropterosCapturadosControlVectores($conexion, $controlVectores['id_control_vectores']);
						//Función listarQuiropterosTratadosControlVectores
						$controlVectoresQuiropterosTratados = $cpco->listarQuiropterosTratadosControlVectores($conexion, $controlVectores['id_control_vectores']);
						//Función listarSitiosCapturaControlVectores
					 	$controlVectoresSitiosCaptura = $cpco->listarSitiosCapturaControlVectores($conexion, $controlVectores['id_control_vectores']);
					 	
					 	$mayor = max(pg_num_rows($controlVectoresEspeciesAtacadas), pg_num_rows($controlVectoresQuiropterosCapturados), pg_num_rows($controlVectoresSitiosCaptura));
					 	
					 	if(pg_num_rows($controlVectoresEspeciesAtacadas) != 0){
						 	while ($fila = pg_fetch_assoc($controlVectoresEspeciesAtacadas)){
						 		$especiesAtacadas[] = array(id_control_vectores=>$fila['id_control_vectores'],
						 									 id_control_vectores_especie_atacada=>$fila['id_control_vectores_especie_atacada'],
											 				 especie=>$fila['especie'],
											 				 existencia_predio=>$fila['existencia_predio'],
											 				 animales_mordeduras=>$fila['animales_mordeduras']);
						 	}
					 	}
					 	
					 	if(pg_num_rows($controlVectoresQuiropterosCapturados) != 0){
					 		while ($fila = pg_fetch_assoc($controlVectoresQuiropterosCapturados)){
					 			$quiropterosCapturados[] = array(id_control_vectores=>$fila['id_control_vectores'],
											 					 id_control_vectores_quiropteros_capturados=>$fila['id_control_vectores_quiropteros_capturados'],
											 					 quiroptero=>$fila['quiroptero'],
											 					 num_quiropteros=>$fila['num_quiropteros']);
					 		}
					 	}
					 	
					 	if(pg_num_rows($controlVectoresQuiropterosTratados) != 0){
					 		while ($fila = pg_fetch_assoc($controlVectoresQuiropterosTratados)){
					 			$quiropterosTratados[] = array(id_control_vectores=>$fila['id_control_vectores'],
												 					id_control_vectores_quiropteros_tratados=>$fila['id_control_vectores_quiropteros_tratados'],
												 					vampiros_tratados=>$fila['vampiros_tratados'],
												 					vampiros_no_tratados=>$fila['vampiros_no_tratados'],
					 												vampiros_laboratorio=>$fila['vampiros_laboratorio']
					 			);
					 		}
					 	}
					 	
					 	if(pg_num_rows($controlVectoresSitiosCaptura) != 0){
					 		while ($fila = pg_fetch_assoc($controlVectoresSitiosCaptura)){
					 			$sitiosCaptura[] = array(id_control_vectores=>$fila['id_control_vectores'],
										 					id_control_vectores_sitio_captura=>$fila['id_control_vectores_sitio_captura'],
										 					malla=>$fila['malla'],
										 					especie=>$fila['especie'],
										 					num_capturas_malla=>$fila['num_capturas_malla'],
										 					observaciones_malla=>$fila['observaciones_malla']);
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
				 				
				 			echo ' <td class="formatoTexto">'.$especiesAtacadas[$i]['id_control_vectores'].'</td>
									<td class="formatoTexto">'.$especiesAtacadas[$i]['id_control_vectores_especie_atacada'].'</td>
							        <td class="formatoTexto">'.$especiesAtacadas[$i]['especie'].'</td>
							        <td class="formatoTexto">'.$especiesAtacadas[$i]['existencia_predio'].'</td>
							        <td class="formatoTexto">'.$especiesAtacadas[$i]['animales_mordeduras'].'</td>
						        		
						        	<td class="formatoTexto">'.$quiropterosCapturados[$i]['id_control_vectores'].'</td>
									<td class="formatoTexto">'.$quiropterosCapturados[$i]['id_control_vectores_quiropteros_capturados'].'</td>
							        <td class="formatoTexto">'.$quiropterosCapturados[$i]['quiroptero'].'</td>
							        <td class="formatoTexto">'.$quiropterosCapturados[$i]['num_quiropteros'].'</td>
					 					
					 				<td class="formatoTexto">'.$quiropterosTratados[$i]['id_control_vectores'].'</td>
									<td class="formatoTexto">'.$quiropterosTratados[$i]['id_control_vectores_quiropteros_tratados'].'</td>
							        <td class="formatoTexto">'.$quiropterosTratados[$i]['vampiros_tratados'].'</td>
							        <td class="formatoTexto">'.$quiropterosTratados[$i]['vampiros_no_tratados'].'</td>
									<td class="formatoTexto">'.$quiropterosTratados[$i]['vampiros_laboratorio'].'</td>
							        	
									<td class="formatoTexto">'.$sitiosCaptura[$i]['id_control_vectores'].'</td>
									<td class="formatoTexto">'.$sitiosCaptura[$i]['id_control_vectores_sitio_captura'].'</td>
							        <td class="formatoTexto">'.$sitiosCaptura[$i]['malla'].'</td>
							        <td class="formatoTexto">'.$sitiosCaptura[$i]['especie'].'</td>
							        <td class="formatoTexto">'.$sitiosCaptura[$i]['num_capturas_malla'].'</td>
						    		<td class="formatoTexto">'.$sitiosCaptura[$i]['observaciones_malla'].'</td>
					  			</tr>';
				 		
				 			$j++;
					 	}
					 	
					 	$especiesAtacadas = null;
					 	$quiropterosCapturados = null;
					 	$quiropterosTratados = null;
					 	$sitiosCaptura = null;
					 }
				 ?>
				
				</tbody>
			</table>
		</div>
	</body>
</html>