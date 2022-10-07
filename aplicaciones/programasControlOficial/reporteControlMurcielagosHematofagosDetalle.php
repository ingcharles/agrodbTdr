<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorProgramasControlOficial.php';
	
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$cpco = new ControladorProgramasControlOficial();
	
	$largoCabecera=18;
	$largoDetalle=9;
	
	$numSolicitud = htmlspecialchars ($_POST['bNumSolicitud'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['bFechaCreacion'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['bNombrePredio'],ENT_NOQUOTES,'UTF-8');
	$nombrePropietario = htmlspecialchars ($_POST['bNombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$sitio = htmlspecialchars ($_POST['bSitio'],ENT_NOQUOTES,'UTF-8');
	$idOficina = htmlspecialchars ($_POST['bIdOficina'],ENT_NOQUOTES,'UTF-8');
	$nuevaInspeccion = htmlspecialchars ($_POST['bNuevaInspeccion'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	
	//Función buscarMurcielagosHematofagos
	$murcielagosHematofagosCabecera = $cpco->buscarControlMurcielagosHematofagos($conexion, $numSolicitud, 
											$fecha, $nombrePredio, 
											$nombrePropietario, $idProvincia, $idCanton, 
											$idParroquia, $sitio, $idOficina, $nuevaInspeccion, 
											$estado);
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
				Programas de Control Oficial - Identificación y Supervisión de Refugios de Murciélagos Hematófagos<br>
			</div>
			
			<div id="direccion"></div>
			<div id="imprimir">
				<form id="filtrar" action="reporteControlMurcielagosHematofagosDetalleExcel.php" target="_blank" method="post">
				
				 <input type="hidden" id="bNumSolicitud" name="bNumSolicitud" value="<?php echo $_POST['bNumSolicitud'];?>" />
				 <input type="hidden" id="bFechaCreacion" name="bFechaCreacion" value="<?php echo $_POST['bFechaCreacion'];?>" />
				 <input type="hidden" id="bNombrePredio" name="bNombrePredio" value="<?php echo $_POST['bNombrePredio'];?>" />
				 <input type="hidden" id="bNombrePropietario" name="bNombrePropietario" value="<?php echo $_POST['bNombrePropietario'];?>" />
				 <input type="hidden" id="bIdProvincia" name="bIdProvincia" value="<?php echo $_POST['bIdProvincia'];?>" />
				 <input type="hidden" id="bIdCanton" name="bIdCanton" value="<?php echo $_POST['bIdCanton'];?>" />
				 <input type="hidden" id="bIdParroquia" name="bIdParroquia" value="<?php echo $_POST['bIdParroquia'];?>" />	 
				 <input type="hidden" id="bSitio" name="bIdSitio" value="<?php echo $_POST['bSitio'];?>" />
				 <input type="hidden" id="bIdOficina" name="bIdOficina" value="<?php echo $_POST['bIdOficina'];?>" />
				 <input type="hidden" id="bNuevaInspeccion" name="bNuevaInspeccion" value="<?php echo $_POST['bNuevaInspeccion'];?>" />
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
						<th>PERSONA QUE CONOCE EL REFUGIO</th>
						<th>TIPO DE REFUGIO</th>
						<th>PROVINCIA</th>
						<th>CANTÓN</th>
						<th>PARROQUIA</th>
						<th>SITIO</th>
					    <th>OFICINA DE AGROCALIDAD</th>		    
						<th>UTM X</th>
						<th>UTM Y</th>
						<th>UTM Z</th>
						<th>ESTADO</th>
						<th>REQUIERE NUEVA INSPECCIÓN</th>
						<th>FECHA NUEVA INSPECCIÓN</th>
									
						<th>ID</th>
						<th>ID INSPECCIÓN</th>
						<th>NÚMERO INSPECCIÓN</th>
						<th>FECHA INSPECCIÓN</th>
						<th>PRESENCIA MH</th>
					    <th>CONTROL REALIZADO</th>
					    <th>NÚM. MACHOS</th>
					    <th>NÚM. HEMBRAS</th>
					    <th>OBSERVACIONES</th>		    
					</tr>
				</thead>
				
			<tbody>
				 <?php	 
					 //Matriz Murciélagos Hematófagos
					 while($murcielagosHematofagos = pg_fetch_assoc($murcielagosHematofagosCabecera)){
					 	 
					 	echo '<tr>
							    <td class="formatoTexto">'.$murcielagosHematofagos['id_murcielagos_hematofagos'].'</td>
						        <td class="formatoTexto">'.$murcielagosHematofagos['num_solicitud'].'</td>
						        <td class="formatoTexto">'.$murcielagosHematofagos['fecha'].'</td>
						    	<td class="formatoTexto">'.$murcielagosHematofagos['nombre_predio'].'</td>
						    	<td class="formatoTexto">'.$murcielagosHematofagos['nombre_propietario'].'</td>
						        <td class="formatoTexto">'.$murcielagosHematofagos['persona_refugio'].'</td>
								<td class="formatoTexto">'.$murcielagosHematofagos['tipo_refugio'].'</td>
						        <td class="formatoTexto">'.$murcielagosHematofagos['provincia'].'</td>
						        <td class="formatoTexto">'.$murcielagosHematofagos['canton'].'</td>
								<td class="formatoTexto">'.$murcielagosHematofagos['parroquia'].'</td>
						        <td class="formatoTexto">'.$murcielagosHematofagos['sitio'].'</td>
						    	<td class="formatoTexto">'.$murcielagosHematofagos['oficina'].'</td>
						        <td class="formatoTexto">'.$murcielagosHematofagos['utm_x'].'</td>
						        <td class="formatoTexto">'.$murcielagosHematofagos['utm_y'].'</td>
						    	<td class="formatoTexto">'.$murcielagosHematofagos['utm_z'].'</td>
						    	<td class="formatoTexto">'.$murcielagosHematofagos['estado'].'</td>
								<td class="formatoTexto">'.$murcielagosHematofagos['nueva_inspeccion'].'</td>			    	
						    	<td class="formatoTexto">'.$murcielagosHematofagos['fecha_nueva_inspeccion'].'</td>';
								
		
					 			//Función buscarMurcielagosHematofagos
								$murcielagosHematofagosInspecciones = $cpco->listarInspeccionMurcielagosHematofagos($conexion, $murcielagosHematofagos['id_murcielagos_hematofagos']);
								
								if(pg_num_rows($murcielagosHematofagosInspecciones) != 0){
									$i=1;
									
									while($inspecciones = pg_fetch_assoc($murcielagosHematofagosInspecciones)){
										
										if($i>1){
											echo '	<tr>';
											
											for ($j=0;$j<$largoCabecera;$j++){
												echo '<td></td>';
											}
										}
										
										echo '  <td class="formatoTexto">'.$inspecciones['id_murcielagos_hematofagos'].'</td>
												<td class="formatoTexto">'.$inspecciones['id_murcielagos_hematofagos_inspecciones'].'</td>
										        <td class="formatoTexto">'.$inspecciones['num_inspeccion'].'</td>
										        <td class="formatoTexto">'.$inspecciones['fecha_inspeccion'].'</td>
										        <td class="formatoTexto">'.$inspecciones['presencia_mh'].'</td>
										    	<td class="formatoTexto">'.$inspecciones['control_realizado'].'</td>
										        <td class="formatoTexto">'.$inspecciones['num_machos'].'</td>
										        <td class="formatoTexto">'.$inspecciones['num_hembras'].'</td>
										        <td class="formatoTexto">'.$inspecciones['observaciones'].'</td>
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