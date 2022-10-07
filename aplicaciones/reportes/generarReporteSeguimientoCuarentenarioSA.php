<?php

session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReportesCSV.php';

$conexion = new Conexion();
$cr = new ControladorReportesCSV();

$provincia = $_POST['provincia'];
$estado = $_POST['estado'];
$producto = $_POST['producto'];
$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];
$tituloReporte = "Reporte Seguimiento Cuarentenario SA a Nivel Nacional";
$archivoSalida = "REPORTE_SEGUIMIENTO_CUARENTENARIO_NACIONAL";

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=".$archivoSalida.".xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$cabecera = "";
$detalle = "";


$arrayParametros = array(
					"estado" => $estado,
					"tipoOperacion" => $tipoOperacion					
					);



$operadoresOperaciones = $cr->generarReporteSeguimientoCuarentenarioSA($conexion, $provincia, $estado, $producto, $fechaInicio, $fechaFin);

$cabecera = '<thead>
					<tr>
					    <th>Identificación de VUE</th>
					    <th>CZPM-M</th>
					    <th>Fechas de Ingreso</th>
					    <th>Estado Seguimiento</th>
						<th>Fecha de Cierre</th>
						<th>Cantidad de Producto</th>
						<th>Total de Mercancía Pecuaria</th>
						<th>Producto – Especie</th>
						<th>Provincia de Seguimiento</th>						
					</tr>
				</thead>';
while ($fila = pg_fetch_assoc($operadoresOperaciones)) {

	$detalle .= '<tr><td>'. $fila['id_vue'] . '</td>
					<td>' . $fila['csmt'] . '</td>
					<td>' . $fila['fecha_ingreso_ecuador'] . '</td>
					<td>' . $fila['estado'] . '</td>
					<td>' . $fila['fecha_cierre'] . '</td>
					<td>' . $fila['cantidad'] . '</td>
					<td>' . $fila['cantidad_total_seguimiento'] . '</td>
					<td>' . $fila['nombre_producto'] . '</td>
					<td>' . $fila['provincia_seguimiento'] . '</td>									
				</tr>';
}


?>

<html LANG="es">

<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<style type="text/css">
		#tablaReporte {
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			display: inline-block;
			width: auto;
			margin: 0;
			padding: 0;
			border-collapse: collapse;
		}

		#tablaReporte td,
		#tablaReporte th {
			font-size: 1em;
			border: 1px solid #98bf21;
			padding: 3px 7px 2px 7px;
		}

		#tablaReporte th {
			font-size: 1em;
			text-align: left;
			padding-top: 5px;
			padding-bottom: 4px;
			background-color: #A7C942;
			color: #ffffff;
		}

		#logoMagap {
			margin-top: -17px;
			width: 15%;
			height: 120px;
			background-image: url(../../aplicaciones/general/img/magap.png);
			background-repeat: no-repeat;
			float: left;
		}

		#logoAgrocalidad {
			width: 20%;
			height: 80px;
			background-image: url(../../aplicaciones/general/img/agrocalidad.png);
			background-repeat: no-repeat;
			float: right;
		}

		#textoTitulo {
			width: 60%;
			height: 80px;
			text-align: center;
			float: left;
		}

		@page {
			margin: 5px;
		}

		.formato {
			mso-style-parent: style0;
			mso-number-format: "\@";
		}
	</style>
	</body>
</head>

<body>
	<div id="header">
		<div id="logoMagap"></div>
		<div id="textoTitulo"><b>AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO</b><br>
			COORDINACIÓN GENERAL DE SANIDAD ANIMAL<br>
			<?php echo $tituloReporte; ?><br>
		</div>
	</div>

	</head>

	<body>
		<div id="tabla">
			<table id="tablaReporte" class="soloImpresion">
				<?php echo $cabecera; ?>
				<tbody>
					<?php echo $detalle; ?>
				</tbody>
			</table>
		</div>
	</body>

	<script type="text/javascript">
		$(document).ready(function() {
			$("#listadoItems").removeClass("comunes");
			$("#listadoItems").addClass("lista");

		});
	</script>

</html>