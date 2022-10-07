<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorProgramasControlOficial.php';
	
	header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	header("Content-Disposition: attachment; filename=PCOCensoPredioEquidos.xls");
	//con esto evitamos que el navegador lo grabe en su caché
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$cpco = new ControladorProgramasControlOficial();
	
	$largoCabecera=22;
	$largoDetalleCatastro=3;
	$largoDetalleTipoActividad=4;
	$largoDetalleEspecie=6;
	$largoDetalleBioseguridad=3;
	$largoDetalleSanidad=10;
	$largoDetalleHistorialPatologias=6;
	
	
	$numSolicitud = htmlspecialchars ($_POST['bNumSolicitud'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['bFechaCreacion'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['bNombrePredio'],ENT_NOQUOTES,'UTF-8');
	$nombrePropietario = htmlspecialchars ($_POST['bNombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$nombreAdministrador = htmlspecialchars ($_POST['bNombreAdministrador'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	
	//Función buscarCatastroPredioEquidos
	$catastroPredioEquidosCabecera = $cpco->buscarCatastroPredioEquidos($conexion, $numSolicitud, $fecha, $nombrePredio,   
																		$nombrePropietario, $nombreAdministrador, 
																		$idProvincia, $idCanton, $idParroquia, $estado);
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
			<div id="textoPOA">Ministerio de Agricultura, Ganaderia, Acuacultura y Pesca<Br>
						Agencia Ecuatoriana de Aseguramiento de la Calidad del Agro Agrocalidad<Br>
				Programas de Control Oficial - Catastro de Predio de Équidos<br>
			</div>
			<div id="direccion"></div>
			<div id="bandera"></div>
		</div>
		
		<div id="tabla">
		
			<table id="tablaReporteControlOficial" class="soloImpresion">
				<thead>
					<tr>
						<th>ID</th>
					    <th>CÓDIGO DE SITIO</th>
						<th>FECHA REGISTRO</th>
						<th>NOMBRE DEL PREDIO</th>
						<th>NOMBRE DEL PROPIETARIO</th>
						<th>CÉDULA DEL PROPIETARIO</th>
						<th>TELÉFONO</th>
						<th>CORREO ELECTRÓNICO</th>
						<th>NOMBRE DEL ADMINISTRADOR</th>
						<th>CÉDULA DEL ADMINISTRADOR</th>
						<th>TELÉFONO</th>
						<th>CORREO ELECTRÓNICO</th>
						<th>PROVINCIA</th>
						<th>CANTÓN</th>
						<th>PARROQUIA</th>
						<th>DIRECCION</th>
						<th>UTM X</th>
						<th>UTM Y</th>
						<th>UTM Z</th>
						<th>EXTENSIÓN (Ha.)</th>
						<th>ESTADO</th>
						<th>OBSERVACIONES</th>
						
						<!-- CATASTRO -->			
						<th>ID</th>
						<th>ID CENSO</th>
						<th>CENSO</th>
						
						<!-- TIPO ACTIVIDAD -->			
						<th>ID</th>
						<th>ID TIPO ACTIVIDAD</th>
						<th>TIPO ACTIVIDAD</th>
						<th>EXTENSIÓN (Ha.)</th>	
						
						<!-- ESPECIE -->			
						<th>ID</th>
						<th>ID ESPECIE</th>
						<th>ESPECIE</th>
						<th>RAZA</th>
						<th>CATEGORÍA</th>
						<th>NÚMERO ANIMALES</th>
						
						<!-- BIOSEGURIDAD -->			
						<th>ID</th>
						<th>ID BIOSEGURIDAD</th>
						<th>BIOSEGURIDAD</th>  
						
						<!-- SANIDAD -->			
						<th>ID</th>
						<th>ID SANIDAD</th>
						<th>PROFESIONAL TÉCNICO</th>
						<th>PESEBRERAS</th>
						<th>ÁREA DE CUARENTENA</th>
						<th>ELIMINACIÓN DE DESECHOS</th>
						<th>CONTROL DE VECTORES</th>
						<th>USO DE ÁPEROS INDIVIDUALES</th>
						<th>REPORTE POSITIVO AIE</th>
						<th>MEDIDA SANITARIA</th>
						
						<!-- HISTORIAL PATOLOGÍAS -->			
						<th>ID</th>
						<th>ID HISTORIAL PATOLOGÍAS</th>
						<th>ENFERMEDAD</th>
						<th>VACUNA</th> 
						<th>LABORATORIO</th>    
					</tr>
				</thead>
				
			<tbody>
				 <?php	 
					 //Matriz Predio Equidos
					 while($catastroPredioEquidos = pg_fetch_assoc($catastroPredioEquidosCabecera)){
					 	 
					 	echo '<tr>
							    <td class="formatoTexto">'.$catastroPredioEquidos['id_catastro_predio_equidos'].'</td>
						        <td class="formatoTexto">'.$catastroPredioEquidos['num_solicitud'].'</td>
						        <td class="formatoTexto">'.$catastroPredioEquidos['fecha'].'</td>
						    	<td class="formatoTexto">'.$catastroPredioEquidos['nombre_predio'].'</td>
								<td class="formatoTexto">'.$catastroPredioEquidos['nombre_propietario'].'</td>
								<td class="formatoTexto">'.$catastroPredioEquidos['cedula_propietario'].'</td>
								<td class="formatoTexto">'.$catastroPredioEquidos['telefono_propietario'].'</td>
								<td class="formatoTexto">'.$catastroPredioEquidos['correo_electronico_propietario'].'</td>
								<td class="formatoTexto">'.$catastroPredioEquidos['nombre_administrador'].'</td>
								<td class="formatoTexto">'.$catastroPredioEquidos['cedula_administrador'].'</td>
								<td class="formatoTexto">'.$catastroPredioEquidos['telefono_administrador'].'</td>
								<td class="formatoTexto">'.$catastroPredioEquidos['correo_electronico_administrador'].'</td>
						        <td class="formatoTexto">'.$catastroPredioEquidos['provincia'].'</td>
						    	<td class="formatoTexto">'.$catastroPredioEquidos['canton'].'</td>
						        <td class="formatoTexto">'.$catastroPredioEquidos['parroquia'].'</td>
						        <td class="formatoTexto">'.$catastroPredioEquidos['direccion_predio'].'</td>			    	
						    	<td class="formatoTexto">'.$catastroPredioEquidos['utm_x'].'</td>
					 			<td class="formatoTexto">'.$catastroPredioEquidos['utm_y'].'</td>
								<td class="formatoTexto">'.$catastroPredioEquidos['utm_z'].'</td>
								<td class="formatoTexto">'.$catastroPredioEquidos['extension'].'</td>
								<td class="formatoTexto">'.$catastroPredioEquidos['estado'].'</td>
								<td class="formatoTexto">'.$catastroPredioEquidos['observaciones'].'</td>';
								
						$motivoCatastroPredioEquidos = $cpco->listarMotivoCatastroPredioEquidos($conexion, $catastroPredioEquidos['id_catastro_predio_equidos']);
						$tipoActividadPredioEquidos = $cpco->listarTipoActividadPredioEquidos($conexion, $catastroPredioEquidos['id_catastro_predio_equidos']);
						$especiePredioEquidos = $cpco->listarEspeciePredioEquidos($conexion, $catastroPredioEquidos['id_catastro_predio_equidos']);
						$bioseguridadPredioEquidos = $cpco->listarBioseguridadPredioEquidos($conexion, $catastroPredioEquidos['id_catastro_predio_equidos']);
						$sanidadPredioEquidos = $cpco->listarSanidadPredioEquidos($conexion, $catastroPredioEquidos['id_catastro_predio_equidos']);
						$historialPatologiaPredioEquidos = $cpco->listarHistorialPatologiasPredioEquidos($conexion, $catastroPredioEquidos['id_catastro_predio_equidos']);
					 	
						
						$mayor = max(pg_num_rows($motivoCatastroPredioEquidos), pg_num_rows($tipoActividadPredioEquidos), 
								pg_num_rows($especiePredioEquidos), pg_num_rows($bioseguridadPredioEquidos), 
								pg_num_rows($sanidadPredioEquidos), pg_num_rows($historialPatologiaPredioEquidos));
					 	
					 	if(pg_num_rows($motivoCatastroPredioEquidos) != 0){
						 	while ($fila = pg_fetch_assoc($motivoCatastroPredioEquidos)){
						 		$motivoCatastro[] = array(id_catastro_predio_equidos=>$fila['id_catastro_predio_equidos'],
						 									 id_catastro_predio_equidos_catastro=>$fila['id_catastro_predio_equidos_catastro'],
											 				 catastro=>$fila['catastro']);
						 	}
					 	}
					 	
					 	if(pg_num_rows($tipoActividadPredioEquidos) != 0){
					 		while ($fila = pg_fetch_assoc($tipoActividadPredioEquidos)){
					 			$tipoActividad[] = array(id_catastro_predio_equidos=>$fila['id_catastro_predio_equidos'],
								 					 id_catastro_predio_equidos_tipo_actividad=>$fila['id_catastro_predio_equidos_tipo_actividad'],
								 					 tipo_actividad=>$fila['tipo_actividad'],
		 											 extension_actividad=>$fila['extension_actividad']);
					 		}
					 	}
					 	
					 	if(pg_num_rows($especiePredioEquidos) != 0){
					 		while ($fila = pg_fetch_assoc($especiePredioEquidos)){
					 			$especie[] = array(id_catastro_predio_equidos=>$fila['id_catastro_predio_equidos'],
									 					id_catastro_predio_equidos_especie=>$fila['id_catastro_predio_equidos_especie'],
									 					nombre_especie=>$fila['nombre_especie'],
									 					nombre_raza=>$fila['nombre_raza'],
									 					nombre_categoria=>$fila['nombre_categoria'],
									 					numero_animales=>$fila['numero_animales']
					 			);
					 		}
					 	}
					 	
					 	if(pg_num_rows($bioseguridadPredioEquidos) != 0){
					 		while ($fila = pg_fetch_assoc($bioseguridadPredioEquidos)){
					 			$bioseguridad[] = array(id_catastro_predio_equidos=>$fila['id_catastro_predio_equidos'],
									 					id_catastro_predio_equidos_bioseguridad=>$fila['id_catastro_predio_equidos_bioseguridad'],
									 					bioseguridad=>$fila['bioseguridad']);
					 		}
					 	}
					 	
					 	if(pg_num_rows($sanidadPredioEquidos) != 0){
					 		while ($fila = pg_fetch_assoc($sanidadPredioEquidos)){
					 			$sanidad[] = array(id_catastro_predio_equidos=>$fila['id_catastro_predio_equidos'],
					 					id_catastro_predio_equidos_sanidad=>$fila['id_catastro_predio_equidos_sanidad'],
					 					profesional_tecnico=>$fila['profesional_tecnico'],
					 					pesebreras=>$fila['pesebreras'],
					 					area_cuarentena=>$fila['area_cuarentena'],
					 					eliminacion_desechos=>$fila['eliminacion_desechos'],
					 					control_vectores=>$fila['control_vectores'],
					 					uso_aperos_individuales=>$fila['uso_aperos_individuales'],
					 					reporte_positivo_aie=>$fila['reporte_positivo_aie'],
					 					medida_sanitaria=>$fila['medida_sanitaria']);
					 		}
					 	}
					 	
					 	if(pg_num_rows($historialPatologiaPredioEquidos) != 0){
					 		while ($fila = pg_fetch_assoc($historialPatologiaPredioEquidos)){
					 			$patologia[] = array(id_catastro_predio_equidos=>$fila['id_catastro_predio_equidos'],
					 					id_catastro_predio_equidos_historial_patologias=>$fila['id_catastro_predio_equidos_historial_patologias'],
					 					enfermedad=>$fila['enfermedad'],
					 					vacuna=>$fila['vacuna'],
					 					laboratorio=>$fila['laboratorio']);
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
				 			
				 			
				 				
				 			echo ' <td class="formatoTexto">'.$motivoCatastro[$i]['id_catastro_predio_equidos'].'</td>
									<td class="formatoTexto">'.$motivoCatastro[$i]['id_catastro_predio_equidos_catastro'].'</td>
							        <td class="formatoTexto">'.$motivoCatastro[$i]['catastro'].'</td>
						        		
						        	<td class="formatoTexto">'.$tipoActividad[$i]['id_catastro_predio_equidos'].'</td>
									<td class="formatoTexto">'.$tipoActividad[$i]['id_catastro_predio_equidos_tipo_actividad'].'</td>
							        <td class="formatoTexto">'.$tipoActividad[$i]['tipo_actividad'].'</td>
							        <td class="formatoTexto">'.$tipoActividad[$i]['extension_actividad'].'</td>
					 					
					 				<td class="formatoTexto">'.$especie[$i]['id_catastro_predio_equidos'].'</td>
									<td class="formatoTexto">'.$especie[$i]['id_catastro_predio_equidos_especie'].'</td>
							        <td class="formatoTexto">'.$especie[$i]['nombre_especie'].'</td>
							        <td class="formatoTexto">'.$especie[$i]['nombre_raza'].'</td>
									<td class="formatoTexto">'.$especie[$i]['nombre_categoria'].'</td>
							        <td class="formatoTexto">'.$especie[$i]['numero_animales'].'</td>
										
									<td class="formatoTexto">'.$bioseguridad[$i]['id_catastro_predio_equidos'].'</td>
							        <td class="formatoTexto">'.$bioseguridad[$i]['id_catastro_predio_equidos_bioseguridad'].'</td>
					 				<td class="formatoTexto">'.$bioseguridad[$i]['bioseguridad'].'</td>
										
							        <td class="formatoTexto">'.$sanidad[$i]['id_catastro_predio_equidos'].'</td>
									<td class="formatoTexto">'.$sanidad[$i]['id_catastro_predio_equidos_sanidad'].'</td>
							        <td class="formatoTexto">'.$sanidad[$i]['profesional_tecnico'].'</td>
								    <td class="formatoTexto">'.$sanidad[$i]['pesebreras'].'</td>
							        <td class="formatoTexto">'.$sanidad[$i]['area_cuarentena'].'</td>
									<td class="formatoTexto">'.$sanidad[$i]['eliminacion_desechos'].'</td>
									<td class="formatoTexto">'.$sanidad[$i]['control_vectores'].'</td>
					 				<td class="formatoTexto">'.$sanidad[$i]['uso_aperos_individuales'].'</td>
									<td class="formatoTexto">'.$sanidad[$i]['reporte_positivo_aie'].'</td>
									<td class="formatoTexto">'.$sanidad[$i]['medida_sanitaria'].'</td>
								
							        
									<td class="formatoTexto">'.$patologia[$i]['id_catastro_predio_equidos'].'</td>
							        <td class="formatoTexto">'.$patologia[$i]['id_catastro_predio_equidos_historial_patologias'].'</td>
						    		<td class="formatoTexto">'.$patologia[$i]['enfermedad'].'</td>
							        <td class="formatoTexto">'.$patologia[$i]['vacuna'].'</td>
						    		<td class="formatoTexto">'.$patologia[$i]['laboratorio'].'</td>
					  			</tr>';
				 		
				 			$j++;
					 	}
					 	
					 	$motivoCatastro = null;
					 	$tipoActividad = null;
					 	$especie = null;
					 	$bioseguridad = null;
					 	$sanidad = null;
					 	$patologia = null;
					 }
				 ?>
				
				</tbody>
			</table>
		</div>
	</body>
</html>