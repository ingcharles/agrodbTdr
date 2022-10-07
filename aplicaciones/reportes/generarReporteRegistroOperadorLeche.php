<?php

	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';

	$conexion = new Conexion();
	$cro = new ControladorRegistroOperador();
	
	$tipoReporte = $_POST['tipoReporte'];
	$idProvincia = $_POST['provincia'];
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$tipoOperacion = $_POST['tipoOperacion'];
	$idProducto = $_POST['producto'];
	
	header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	header("Content-Disposition: attachment; filename=REPORTE.xls");
	//con esto evitamos que el navegador lo grabe en su caché
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$cabecera = "";
	$detalle = "";
	
	switch ($tipoReporte){
	
		case "individual":
								
			$qOperadoresLeche = $cro->obtenerDatosOperadorLecheXProviciaXFecha($conexion, $idProvincia, $fechaInicio, $fechaFin, $tipoOperacion);

			if($tipoOperacion == "AI-ACO"){
				$tituloReporte = 'REPORTE DE CENTROS DE ACOPIO DE LECHE CRUDA INDIVIDUAL';
				$archivoSalida = 'REPORTE_CENTROS_ACOPIO_RO_LECHE.xls';
				
				$cabecera = '<thead>
					<tr>
						<th colspan="9">DATOS GENERALES</th>
						<th colspan="7">ESPECIFICACIONES TÉCNICAS</th>						
						<th>CONTACTO</th>
                        <th colspan="2">COORDENADAS</th>                        
						<th colspan="2">HORARIOS DE RECEPCIÓN</th>
						<th> </th>
                        <th> </th>
					</tr>
					<tr>
					    <th>Identificador operador</th>		
                        <th>Nombre del operador</th>
                        <th>Nombre del producto</th>					
					    <th>Estado</th>
						<th>N° de Registro</th>
					    <th>Fecha de ingreso de Solicitud</th>
					    <th>Fecha de Aprobación del Registro</th>
						<th>Fecha de Caducidad del Registro</th>
						<th>Provincia</th>
						<th>Cantón</th>
						<th>Parroquia</th>
						<th>Nombre del Centro de Acopio</th>
						<th>Dirección</th>
						<th>Teléfono</th>
						<th>Nombre del Administrador/Representante Legal</th>
						<th>N° de Proveedores</th>
						<th>Capacidad Instalada</th>
						<th>Correo Electrónico</th>
						<th>Coordenadas X</th>
						<th>Coordenadas Y</th>
						<th>Matutino</th>
						<th>Vespertino</th>
						<th>Recibe Asesoría del MAG</th>
                        <th>Observaciones</th>				
					</tr>
				</thead>';		
					
				while($fila = pg_fetch_assoc($qOperadoresLeche)) {
				
						$detalle .='<tr><td class="formato">'.$fila['identificador'].'</td>
                        <td>'.$fila['nombre_operador'].'</td>
                        <td>'.$fila['nombre_producto'].'</td>
						<td>'.$fila['estado'].'</td>
						<td>'.$fila['codigo_operador_leche'].'</td>					
						<td>'.$fila['fecha_creacion'].'</td>
						<td>'.$fila['fecha_aprobacion'].'</td>
					    <td>'.$fila['fecha_finalizacion'].'</td>
						<td>'.$fila['provincia'].'</td>
						<td>'.$fila['canton'].'</td>
						<td>'.$fila['parroquia'].'</td>
						<td>'.$fila['nombre_area'].'</td>
						<td>'.$fila['direccion'].'</td>
						<td>'.$fila['telefono'].'</td>
						<td>'.$fila['nombre_operador'].'</td>
						<td>'.$fila['numero_proveedores'].'</td>
						<td>'.$fila['capacidad_instalada'].'</td>
						<td>'.$fila['correo'].'</td>
						<td class="formato">'.$fila['latitud'].'</td>
						<td class="formato">'.$fila['longitud'].'</td>
						<td>'.$fila['hora_recoleccion_maniana'].'</td>
						<td>'.$fila['hora_recoleccion_tarde'].'</td>
						<td>'.$fila['pertenece_mag'].'</td>
                        <td>'.$fila['observacion'].'</td>
						</tr>';
				}
				
			}else{	
				$tituloReporte = 'REPORTE DE MEDIOS DE TRANSPORTE INDIVIDUAL';
				$archivoSalida = 'REPORTE_MEDIO_TRANSPORTE_RO_LECHE.xls';
				
				$cabecera = '<thead>
					<tr>
						<th colspan="9">DATOS GENERALES</th>
						<th colspan="8">ESPECIFICACIONES TÉCNICAS</th>
						<th colspan="2">HORARIOS RECOLECCIÓN</th>
						<th colspan="2">CONTACTO</th>						
						<th> </th>
					</tr>
					<tr>
					    <th>Identificador operador</th>
                        <th>Nombre del operador</th>
                        <th>Nombre del producto</th>
					    <th>N° de Registro</th>
					    <th>Estado</th>
					    <th>Fecha de ingreso de Solicitud</th>
					    <th>Fecha de Aprobación del Registro</th>
						<th>Fecha de Caducidad del Registro</th>
						<th>Provincia</th>
						<th>Cantón</th>
						<th>Parroquia</th>
						<th>Número de Placa/Matricula</th>
						<th>Tipo de Vehículo</th>
						<th>Marca de Carro</th>
						<th>Color</th>
						<th>Año</th>
						<th>Tipo de Tanque</th>
						<th>Capacidad N° de Litros Total</th>
						<th>Hora de Inicio</th>
						<th>Hora de Fin</th>
						<th>Teléfono</th>
						<th>Correo Electrónico</th>
						<th>Observaciones</th>
					</tr>
				</thead>';
				
				while($fila = pg_fetch_assoc($qOperadoresLeche)) {
				
					$detalle .='<tr><td class="formato">'.$fila['identificador'].'</td>
                        <td>'.$fila['nombre_operador'].'</td>
                        <td>'.$fila['nombre_producto'].'</td>
						<td>'.$fila['codigo_operador_leche'].'</td>
						<td>'.$fila['estado'].'</td>
						<td>'.$fila['fecha_creacion'].'</td>
						<td>'.$fila['fecha_aprobacion'].'</td>
					    <td>'.$fila['fecha_finalizacion'].'</td>
						<td>'.$fila['provincia'].'</td>
						<td>'.$fila['canton'].'</td>
						<td>'.$fila['parroquia'].'</td>
						<td>'.$fila['placa_vehiculo'].'</td>
						<td>'.$fila['tipo_vehiculo'].'</td>
						<td>'.$fila['marca_vehiculo'].'</td>
						<td>'.$fila['color_vehiulo'].'</td>
						<td>'.$fila['anio_vehiculo'].'</td>
						<td>'.$fila['tipo_tanque_vehiculo'].'</td>
						<td>'.$fila['capacidad_vehiculo'].'</td>
						<td>'.$fila['hora_inicio_recoleccion'].'</td>
						<td>'.$fila['hora_fin_recoleccion'].'</td>
						<td>'.$fila['telefono'].'</td>
						<td>'.$fila['correo'].'</td>
						<td>'.$fila['observacion'].'</td>
						</tr>';
				}
				
			}
			
		break;
		
		case "consolidado":

			$qOperadoresLecheConsolidado = $cro->obtenerDatosOperadorLecheConsolidadoXProvicia($conexion, $idProvincia, $tipoOperacion, $idProducto);
			
			if($tipoOperacion == "AI-ACO"){
				$tituloReporte = 'REPORTE DE CENTROS DE ACOPIO DE LECHE CRUDA CONSOLIDADO';
				$archivoSalida = 'REPORTE_CENTROS_ACOPIO_RO_LECHE.xls';
			
				$cabecera = '<thead>
					<tr>
					    <th>Mes</th>
					    <th>Año</th>
					    <th>Registrados</th>
					    <th>Por caducar</th>
						<th>Por declarar informacion</th>
					    <th>Inhabilitados</th>
						<th>Inspección</th>
						<th>Asignados a inspección</th>
						<th>Documental</th>
						<th>Asignados a documental</th>
						<th>Por cargar productos</th>
						<th>Por cargar adjuntos</th>
                        <th>Subsanación</th>
						<th>Subsanación producto</th>
					</tr>
				</thead>';
					
				while($fila = pg_fetch_assoc($qOperadoresLecheConsolidado)) {
			
					$detalle .='<tr><td>'.$fila['mes'].'</td>
						<td>'.$fila['anio'].'</td>
						<td>'.$fila['registrado'].'</td>
						<td>'.$fila['porcaducar'].'</td>
						<td>'.$fila['declararicentroacopio'].'</td>
						<td>'.$fila['nohabilitado'].'</td>
						<td>'.$fila['inspeccion'].'</td>
					    <td>'.$fila['asignadoinspeccion'].'</td>
						<td>'.$fila['documental'].'</td>
						<td>'.$fila['asignadodocumental'].'</td>
						<td>'.$fila['cargarproducto'].'</td>
						<td>'.$fila['cargaradjunto'].'</td>
                        <td>'.$fila['subsanacion'].'</td>
						<td>'.$fila['subsanacionproducto'].'</td>
					</tr>';
				}
			
			}else{
				
				$tituloReporte = 'REPORTE DE MEDIOS DE TRANSPORTE CONSOLIDADO';
				$archivoSalida = 'REPORTE_MEDIO_TRANSPORTE_RO_LECHE.xls';
				
				$cabecera = '<thead>
					<tr>
					    <th>Mes</th>
					    <th>Año</th>
					    <th>Registrados</th>
					    <th>Por caducar</th>
						<th>Por declarar informacion</th>
					    <th>Inhabilitados</th>
						<th>Inspección</th>
						<th>Asignados a inspección</th>
						<th>Documental</th>
						<th>Asignados a documental</th>
						<th>Por cargar productos</th>
						<th>Por cargar adjuntos</th>
                        <th>Subsanación</th>
						<th>Subsanación producto</th>
					</tr>
				</thead>';
					
				while($fila = pg_fetch_assoc($qOperadoresLecheConsolidado)) {
						
					$detalle .='<tr><td>'.$fila['mes'].'</td>
						<td>'.$fila['anio'].'</td>
						<td>'.$fila['registrado'].'</td>
						<td>'.$fila['porcaducar'].'</td>
						<td>'.$fila['declarardvehiculo'].'</td>
						<td>'.$fila['nohabilitado'].'</td>
						<td>'.$fila['inspeccion'].'</td>
					    <td>'.$fila['asignadoinspeccion'].'</td>
						<td>'.$fila['documental'].'</td>
						<td>'.$fila['asignadodocumental'].'</td>
						<td>'.$fila['cargarproducto'].'</td>
						<td>'.$fila['cargaradjunto'].'</td>
                        <td>'.$fila['subsanacion'].'</td>
						<td>'.$fila['subsanacionproducto'].'</td>
					</tr>';
				}
				
			}	
			
		break;
		
	}
?>	
	
<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
#tablaReporte
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	display: inline-block;
	width: auto;
	margin: 0;
	padding: 0;
border-collapse:collapse;
}
#tablaReporte td, #tablaReporte th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#tablaReporte th 
{
font-size:1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;
}
#logoMagap{
margin-top: -17px;
width: 15%;
height:120px;
background-image: url(../../aplicaciones/general/img/magap.png);
background-repeat: no-repeat;
float: left;	
}

#logoAgrocalidad{
width: 20%;
height:80px;
background-image: url(../../aplicaciones/general/img/agrocalidad.png); background-repeat: no-repeat;
float: right;
}

#textoTitulo{
width: 60%;
height:80px;
text-align: center;
float:left;
}

@page{
   margin: 5px;
}

.formato{
 mso-style-parent:style0;
 mso-number-format:"\@";
}


</style>
</body>
</head>
<body>
<div id="header">
   	<div id="logoMagap"></div>
	<div id="textoTitulo"><b>AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO</b><br>
	DIRECCIÓN DE INOCUIDAD DE ALIMENTOS<br>
	<?php echo $tituloReporte; ?><br>
	PROVINCIA: <?php echo strtoupper($idProvincia); ?><br>
	</div>
	<div id="logoAgrocalidad"><!-- img src="../../aplicaciones/general/img/magap.png" --></div>
</div>

</head>
<body>
	<div id="tabla">
	<table id="tablaReporte" class="soloImpresion">
		<?php echo $cabecera;?>
		<tbody>		
		<?php 	
			echo $detalle;
		?>		
		</tbody>
	</table>
	</div>	
</body>

<script type="text/javascript"> 

$(document).ready(function(){
	$("#listadoItems").removeClass("comunes");
	$("#listadoItems").addClass("lista");
	
});

</script>

</html>


