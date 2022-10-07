<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorNotificacionEventoSanitario.php';
	
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$cpco = new ControladorNotificacionEventoSanitario();
	
	$largoCabecera=19;

	$numSolicitud = htmlspecialchars ($_POST['bNumSolicitud'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['bFechaCreacion'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$sitio = htmlspecialchars ($_POST['bSitioPredio'],ENT_NOQUOTES,'UTF-8');
	$finca = htmlspecialchars ($_POST['bFincaPredio'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	
	$NotificacionEventoSanitarioCabecera = $cpco->buscarNotificacionEventoSanitarioFiltrado($conexion, $numSolicitud, $fecha, $idProvincia,$idCanton, 
																							$idParroquia, $sector, $finca, $estado);
	
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
				Programas de Seguimiento de Eventos Sanitarios - Notificaciones Eventos Sanitarios<br>
			</div>
			
			<div id="direccion"></div>
			<div id="imprimir">
				<form id="filtrar" action="reporteNotificacionEventoSanitarioDetalleExcel.php" target="_blank" method="post">
				
				 <input type="hidden" id="bNumSolicitud" name="bNumSolicitud" value="<?php echo $_POST['bNumSolicitud'];?>" />
				 <input type="hidden" id="bFechaCreacion" name="bFechaCreacion" value="<?php echo $_POST['bFechaCreacion'];?>" />
				 <input type="hidden" id="bIdProvincia" name="bIdProvincia" value="<?php echo $_POST['bIdProvincia'];?>" />
				 <input type="hidden" id="bIdCanton" name="bIdCanton" value="<?php echo $_POST['bIdCanton'];?>" />
				 <input type="hidden" id="bIdParroquia" name="bIdParroquia" value="<?php echo $_POST['bIdParroquia'];?>" />	 
				 <input type="hidden" id="bSitioPredio" name="bSitioPredio" value="<?php echo $_POST['bSitioPredio'];?>" />
				 <input type="hidden" id="bFincaPredio" name="bFincaPredio" value="<?php echo $_POST['bFincaPredio'];?>" />
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
						<th>ORIGEN DE LA NOTIFICACIÓN</th>
						<th>CANAL DE LA NOTIFICACIÓN</th>
						<th>NOMBRE</th>
						<th>TELÉFONO</th>
						<th>CELULAR</th>
						<th>CORREO ELECTRÓNICO</th>
						<th>PROVINCIA</th>
						<th>CANTÓN</th>
						<th>PARROQUIA</th>
						<th>SITIO</th>
						<th>FINCA</th>
						<th>ESTADO</th>
						<th>EVENTO SANITARIO</th>
						<th>JUSTIFICACION</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
						
						<!-- PATOLOGIA, ESPECIE AFECTADA -->			
						<th>ID</th>
						<th>PATOLOGÍA DENUNCIADA</th>
						<th>ESPECIE AFECTADA</th>
						<th>ANIMALES ENFERMOS</th>
						<th>ANIMALES MUERTOS</th>
						<th>CÉDULA</th>
						<th>AUTOR</th>
					</tr>
				</thead>
				
				<tbody>
					 <?php	

						 while($notificacionEventoSanitario = pg_fetch_assoc($NotificacionEventoSanitarioCabecera)){
							echo '<tr>
									<td class="formatoTexto">'.$notificacionEventoSanitario['id_notificacion_evento_sanitario'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['numero'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['fecha'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['nombre_origen'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['nombre_canal'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['nombre_informante'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['telefono_informante'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['celular_informante'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['correo_electronico_informante'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['provincia'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['canton'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['parroquia'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['sitio_predio'].'</td>			    	
									<td class="formatoTexto">'.$notificacionEventoSanitario['finca_predio'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['estado'].'</td>
									<td class="formatoTexto">'.($notificacionEventoSanitario['es_evento_sanitario'] == 't'? 'Si': 'No').'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['justificacion_evento_sanitario'].'</td>
									<td class="formatoTexto">'.$notificacionEventoSanitario['identificador'].'</td>';
					 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $notificacionEventoSanitario['identificador']));
					 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>';;
									
							
							$tiposPatologiaEspecieAfectada = $cpco->listarTipoPatologiaEspecieAfectada($conexion, $notificacionEventoSanitario['id_notificacion_evento_sanitario']);
								
						
							if(pg_num_rows($tiposPatologiaEspecieAfectada) != 0){
								$i=1;
								
								while($inspecciones = pg_fetch_assoc($tiposPatologiaEspecieAfectada)){
										
										if($i>1){
											echo '	<tr>';
											
											for ($j=0;$j<$largoCabecera;$j++){
												echo '<td></td>';
											}
										}
										
										echo '  <td class="formatoTexto">'.$inspecciones['id_patologia_especie_afectada'].'</td>
												<td class="formatoTexto">'.$inspecciones['nombre_patologia'].'</td>
										        <td class="formatoTexto">'.$inspecciones['nombre_especie'].'</td>
										        <td class="formatoTexto">'.$inspecciones['animales_enfermos'].'</td>
										    	<td class="formatoTexto">'.$inspecciones['animales_muertos'].'</td>
												<td class="formatoTexto">'.$inspecciones['identificador'].'</td>';
								 				$usuario = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $inspecciones['identificador']));
								 		echo ' <td class="formatoTexto">'.$usuario['nombre'].' '.$usuario['apellido'].'</td>
								  			</tr>';
											
										$i++;
									}
							}else{
									for ($k=0;$k<$largoDetalle;$k++){
										echo '<td></td>';
									}
									
									echo '</tr>';
								}
					 }
				 ?>
				
				</tbody>
			</table>
		</div>
	</body>
</html>