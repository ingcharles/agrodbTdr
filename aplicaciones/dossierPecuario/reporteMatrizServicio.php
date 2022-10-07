<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPecuario.php';

require_once '../../clases/ControladorReportes.php';

$conexion = new Conexion();
$cp = new ControladorDossierPecuario();

$res = $cp-> obtenerMatrizServicio($conexion);

$idSolicitud=10;

$jru = new ControladorReportes();

$fondoCertificado= $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/general/img/fondoCertificadoHorizontal.png';

$ReporteJasper= '/aplicaciones/dossierPecuario/reportes/jrMatrizServicio.jrxml';
$salidaReporte= '/aplicaciones/dossierPecuario/anexos/temp/reporte_m_'.$idSolicitud.'.pdf';

$rutaArchivo= 'anexos/temp/reporte_m_'.$idSolicitud.'.pdf';

?>
<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
#tablaReporteVacunaAnimal {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	display: inline-block;
	width: auto;
	margin: 0;
	padding: 0;
	border-collapse: collapse;
}

#tablaReporteVacunaAnimal td,#tablaReporteVacunaAnimal th {
	font-size: 1em;
	border: 1px solid #98bf21;
	padding: 3px 7px 2px 7px;
}

#tablaReporteVacunaAnimal th {
	font-size: 1em;
	text-align: left;
	padding-top: 5px;
	padding-bottom: 4px;
	background-color: #A7C942;
	color: #ffffff;
}

#logoMagap {
	width: 15%;
	height: 70px;
	background-image: url(../../ensayoEficacia/img/logo1.png);
	background-repeat: no-repeat;
	float: left;
}

#logotexto {
	width: 10%;
	height: 80px;
	float: left;
}

#logoAgrocalidad {
	width: 20%;
	height: 80px;
	background-image:url(../../ensayoEficacia/img/logo2.png);
	background-repeat: no-repeat;
	float: left;
}

#textoPOA {
	width: 40%;
	height: 80px;
	text-align: center;
	float: left;
}

#direccion {
	width: 10%;
	height: 80px;
	background-image: url(img/direccion.png);
	background-repeat: no-repeat;
	float: left;
}

#bandera {
	width: 5%;
	height: 80px;
	background-image: url(img/bandera.png);
	background-repeat: no-repeat;
	float: right;
}

@page {
	margin: 5px;
}

.formato {
	mso-style-parent: style0;
	mso-number-format: "\@";
}

</style>



</head>
<body>
	<div id="header">
		<div id="logoMagap"></div>
		<div id="texto"></div>
		<div id="logoAgrocalidad"></div>
		
		<div id="textoPOA" style="font-size: 16px; font-weight: bold;">
			AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO
			<br>AGROCALIDAD
                  <br>MATRIZ DE SERVICIOS
		</div>
		<div id="direccion"></div>
		<div id="bandera"></div>
            
	</div>
  
   <div>
      <a href="<?php echo $rutaArchivo;?>" download="reporte.pdf"><IMG  src="../../aplicaciones/general/img/descarga.png" ></a>
   </div>




	<div id="tabla">
		<table id="tablaReporteVacunaAnimal" class="soloImpresion">
			<thead>
				<tr>
					
					<th>N° de Expediente</th>
					<th>Fecha ingreso</th>					
					<th>Usuario</th>
					<th>Nombre usuario</th>
					<th>Tipo de trámite</th>
					<th>Producto/Servicio</th>
					<th>Técnico asignado</th>
					<th>Fecha de entrega</th>
					<th>Decisión</th>	
					<th>Tiempo real</th>	
					<th>Tiempo programado</th>	
					<th>Eficiencia</th>	
					<th># observaciones</th>					
				</tr>
			</thead>
			<tbody>
				<?php	
	
while ($registro = pg_fetch_assoc($res)) {
	$presentaciones=null;
	$composiciones=null;
	$usoss=null;
	$formuladores=null;
	//$registros = json_decode($registro[row_to_json], true);
	$registros = $registro;

	if($registros['decision']!=null){
		if($registros['decision']=='A'){
			$registros['decision']='APROBADO';
		}
		else if($registros['decision']=='O'){
			$registros['decision']='OBSERVADO';
		}
		
	}

	echo '<tr>';
	
		echo '<td style="text-aling=right;">'.$registros['id_expediente'].'</td>
				<td>'.$registros['fecha_inicio'].'</td>
				<td>'.$registros['identificador'].'</td>
                <td>'.$registros['razon_social'].'</td>
				<td>'.$registros['nombre'].'</td>
                <td>'.$registros['nombre_producto'].'</td>
                <td>'.$registros['tecnico'].'</td>
                <td>'.$registros['fecha_fin'].'</td>
                <td>'.$registros['decision'].'</td>
                <td>'.$registros['tiempo_real'].'</td>
                <td>'.$registros['plazo'].'</td>
                <td>'.$registros['eficiencia'].'%</td>
				<td>'.$registros['numero_observaciones'].'</td>';
		
	echo '</tr>';
	
}

	 ?>
			</tbody>
		</table>
	</div>
</body>

</html>
