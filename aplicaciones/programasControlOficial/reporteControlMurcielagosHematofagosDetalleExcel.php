<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorProgramasControlOficial.php';
	
	header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	header("Content-Disposition: attachment; filename=PCOMurcielagosHematofagos.xls");
	//con esto evitamos que el navegador lo grabe en su caché
	header("Pragma: no-cache");
	header("Expires: 0");
	
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
				Programas de Control Oficial - Identificación y Supervisión de Refugios de Murciélagos Hematófagos<br>
			</div>
			<div id="direccion"></div>
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
						<th>TIPO DE REFUGIO</th>
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