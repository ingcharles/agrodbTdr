<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorConsultaInspecciones.php';

$conexion = new Conexion();
$cci = new ControladorConsultaInspecciones();

$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];
$identificadorUsuario = $_POST['identificadorUsuario'];
$tipoProtocolo = $_POST['tipoProtocolo'];
switch ($tipoProtocolo) {

	case 'protocoloRoya':

		$tituloReporte = 'Reporte de Ornamentales Protocolo Roya Blanca';
		$archivoSalida = 'REPORTE_ORNAMENTALES_PROTOCOLO_ROYA_BLANCA.xls';
	
		$campos = array(
			'numero_reporte',
		    'fecha_inspeccion',
		    'ruc',
		    'razon_social',
		    'sitio_produccion',
		    'provincia',
		    'canton',
		    'parroquia',
		    'pregunta1',
		    'pregunta2',
		    'pregunta3',
		    'pregunta4',
		    'pregunta5',
		    'pregunta6',
		    'pregunta7',
		    'pregunta8',
		    'pregunta9',
		    'pregunta10',
		    'pregunta11',
		    'pregunta12',
		    'pregunta13',
		    'pregunta14',
		    'pregunta15',
		    'resultado',
		    'observaciones',
		    'usuario',
		    'representante',
		);

		$res = $cci->generarReporteOrnamentalesProtocoloRoyaBlanca($conexion, $fechaInicio, $fechaFin, $identificadorUsuario);
		
		$arrayTabla=array('certificacionf05');

	break;

case 'protocoloAcaros':

	$tituloReporte = 'Reporte de Ornamentales Protocolo Ácaros';
	$archivoSalida = 'REPORTE_PROTOCOLO_ACAROS.xls';

	$campos = array(
			'numero_reporte',
		    'fecha_inspeccion',
		    'ruc',
		    'razon_social',
		    'sitio_produccion',
		    'provincia',
		    'canton',
		    'parroquia',
		    'pregunta1',
		    'pregunta2',
		    'pregunta3',
		    'pregunta4',
		    'pregunta5',
		    'pregunta6',
		    'pregunta7',
		    'pregunta8',
		    'pregunta9',
		    'pregunta10',
		    'pregunta11',
		    'resultado',
		    'observaciones',
		    'usuario',
		    'representante'
		);

		$res = $cci->generarReporteProtocoloAcaros($conexion, $fechaInicio, $fechaFin, $identificadorUsuario);
		$arrayTabla=array('certificacionf07');

break;
case 'protocoloMinador':
	
	$tituloReporte = 'Reporte de Ornamentales Protocolo Minador';
	$archivoSalida = 'REPORTE_PROTOCOLO_MINADOR.xls';
	

	$campos = array(
			'numero_reporte',
		    'fecha_inspeccion',
		    'ruc',
		    'razon_social',
		    'sitio_produccion',
		    'provincia',
		    'canton',
		    'parroquia',
		    'pregunta1',
		    'pregunta2',
		    'pregunta3',
		    'pregunta4',
		    'pregunta5',
		    'pregunta6',
		    'pregunta7',
		    'pregunta8',
		    'pregunta9',
		    'pregunta10',
		    'pregunta11',
		    'pregunta12',
		    'pregunta13',
		    'pregunta14',
		    'pregunta15',
		    'pregunta16',
		    'pregunta17',
		    'pregunta18',
		    'pregunta19',
		    'resultado',
		    'observaciones',
		    'usuario',
		    'representante'
		);

		$res = $cci->generarReporteOrnamentalesProtocoloMinador($conexion, $fechaInicio, $fechaFin, $identificadorUsuario);
		$arrayTabla=array('certificacionf08');
		
	break;
	case 'protocoloTrips':
		
		$tituloReporte = 'Reporte de Ornamentales Protocolo Trips';
		$archivoSalida = 'REPORTE_ORNAMENTALES_PROTOCOLO_TRIPS.xls';
	
		$campos = array(
				'numero_reporte',
			    'fecha_inspeccion',
			    'ruc',
			    'razon_social',
			    'sitio_produccion',
			    'provincia',
			    'canton',
			    'parroquia',
			    'pregunta1',
			    'pregunta2',
			    'pregunta3',
			    'pregunta4',
			    'pregunta5',
			    'pregunta6',
			    'pregunta7',
			    'pregunta8',
			    'pregunta9',
			    'pregunta10',
			    'pregunta11',
			    'pregunta12',
			    'pregunta13',
			    'pregunta14',
			    'pregunta15',
			    'pregunta16',
			    'pregunta17',
			    'pregunta18',
			    'pregunta19',
			    'pregunta20',
			    'resultado',
			    'observaciones',
			    'usuario',
			    'representante',
			);

		$res = $cci->generarReporteOrnamentalesProtocoloTrips($conexion, $fechaInicio, $fechaFin, $identificadorUsuario);
		$arrayTabla=array('certificacionf09');
		
	break;

	case 'protocoloDesvitalizacion':

		$tituloReporte = 'Reporte de Protocolo de desvitalización';
		$archivoSalida = 'REPORTE_PROTOCOLO_DESVITALIZACION.xls';

		$campos = array(
			'numero_reporte',
		    'fecha_inspeccion',
		    'ruc_operador',
		    'nombre_operador',
		    'sitio_acopiador',
		    'provincia',
		    'canton',
		    'parroquia',
		    'pregunta1',
		    'pregunta2',
		    'pregunta3',
		    'pregunta4',
		    'ingrediente_activo',
		    'marca_comercial',
		    'registro_agrocalidad',
		    'formulacion',
		    'fecha_caducidad',
		    'fecha_preparacion',
		    'fecha_validez',
		    'producto',
		    'cantidad_tallos',
		    'cantidad_inspeccionada',
		    'concentracion',
		    'dosificacion',
		    'volumen_solucion',
		    'numero_recipientes',
		    'volumen_total',
		
		    'pregunta5',
		    'pregunta6',
		    'pregunta7',
		    'pregunta8',
		    'pregunta9',
		    'producto',
		
		    'pregunta10',
		    'pregunta11',
		    'pregunta12',
		    'pregunta13',
		    'pregunta14',
		    'pregunta15',
		    'pregunta16',
		    'observaciones',
		    'dictamen_final',
		    'representante_operador',
		    'usuario'
		);

		$res = $cci->generarReporteProtocoloDesvitalizacion($conexion, $fechaInicio, $fechaFin, $identificadorUsuario);
		
		$arrayTabla=array('certificacionf04', 'certificacionf04_detalle_productos', 'certificacionf04_detalle_cantidades');

	break;
	default:
		echo 'Tipo desconocido';
	break;


}

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=$archivoSalida");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");
?>

<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

<style type="text/css">
h1,h2 {
	margin: 0;
	padding: 0;
}

#tablaReporte {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	display: inline-block;
	width: auto;
	margin: 1em 0;
	padding: 0;
	border-collapse: collapse;
}

#tablaReporte td,#tablaReporte th {
	font-size: 1.2em;
	border: 1px solid #98bf21;
	padding: 3px 7px 2px 7px;
}

#tablaReporte th {
	text-align: left;
	padding-top: 5px;
	padding-bottom: 4px;
	background-color: #A7C942;
	color: #ffffff;
}

@page {
	margin: 5px;
}

.formato {
	mso-style-parent: style0;
	mso-number-format: "\@";
}

.formatoNumero {
	mso-style-parent: style0;
	mso-number-format: "0.000000";
}

.colorCelda {
	background-color: #FFE699;
}
</style>

</head>
<body>
	<h1><?php echo $tituloReporte  ;?></h1>
	<h2>Período	<?= $fechaInicio ?>	- <?= $fechaFin ?></h2>
	<div id="tabla">
		<table id="tablaReporte" class="soloImpresion">
			<thead>
				<?php
				echo $cci->construirEncabezadoReporte($conexion, $arrayTabla, $campos);
				?>
			</thead>
			<tbody>
				<?php

				$var = 0;
				$auxPago = 0;
				$aux1Pago = 0;
				$auxColor = 'pintado';
				$auxImpresion = 0;

				While ($fila = pg_fetch_assoc($res)) {
		            echo '<tr>';
		            foreach ($campos as $campo) {
		                if(substr($campo,0,3) == 'ruc'){
		                    echo "<td>&nbsp;" . $fila[$campo] . "</td>";
		                } else {
		                    echo "<td>" . $fila[$campo] . "</td>";
		                }
}
echo '</tr>';
		        }
		        ?>

			</tbody>
		</table>

	</div>

</body>
</html>




?>
