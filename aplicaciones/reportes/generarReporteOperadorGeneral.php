<?php

session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReportesCSV.php';

$conexion = new Conexion();
$cr = new ControladorReportesCSV();

$provincia = $_POST['provincia'];
$estado = $_POST['estado'];
$tipoOperacion = $_POST['tipoOperacion'];
$tipoProducto = $_POST['tipoProducto'];
$subtipoProducto = $_POST['subtipoProducto'];
$producto = $_POST['producto'];
$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];
$tituloReporte = $_POST['tituloReporte'];
$archivoSalida = $_POST['archivoSalida'];

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

if (isset($_POST['provincia'])) {
	$arrayParametros += array("provincia" => $provincia );
}

if (isset($_POST['fechaInicio'])) {
	$arrayParametros += array("fechaInicio" => $fechaInicio );
	$arrayParametros += array("fechaFin" => $fechaFin );
}

if (isset($_POST['provincia'])) {
	$arrayParametros += array("provincia" => $provincia );
}

if (isset($_POST['tipoProducto'])) {
	$arrayParametros += array("tipoProducto" => $tipoProducto );
}

if (isset($_POST['subtipoProducto'])) {
	$arrayParametros += array("subtipoProducto" => $subtipoProducto );
}

if (isset($_POST['producto'])) {
	$arrayParametros += array("producto" => $producto );
}

$operadoresOperaciones = $cr->generarReporteDeOperadoresPorProvincia($conexion, $arrayParametros);

$cabecera = '<thead>
					<tr>
					    <th>Identificador (Ruc/cédula)</th>
					    <th>Razón Social</th>
					    <th>Nombres del Representante</th>
					    <th>Nombres del Ténico</th>
						<th>Dirección</th>
						<th>Teléfonos</th>
						<th>Celular</th>
						<th>Correo</th>
						<th>Identificador Tipo Operación</th>
						<th>Estado</th>
						<th>Observación</th>
						<th>Producto</th>
						<th>Identificador Vue</th>
						<th>Nombre país</th>
						<th>Nombre común</th>
						<th>Subtipo Producto</th>
						<th>Tipo Producto</th>
						<th>Tipo Operación</th>
						<th>Fecha creación</th>
						<th>Fecha modificación</th>
						<th>Fecha aprobación</th>
						<th>Nombre área</th>
						<th>Tipo área</th>
						<th>Superficie utilizada</th>
						<th>Estado área</th>
						<th>Código área</th>
						<th>Nombre sitio</th>
						<th>Dirección sitio</th>
						<th>Teléfono</th>
						<th>Referencia</th>
						<th>Parroquia</th>
						<th>Cantón</th>
						<th>Provincia</th>
						<th>Código Sitio</th>
						<th>Latitud</th>
						<th>Longitud</th>
						<th>Superficie Total</th>
					</tr>
				</thead>';
while ($fila = pg_fetch_assoc($operadoresOperaciones)) {

	$detalle .= '<tr><td>&nbsp;' . $fila['identificador'] . '</td>						
										<td>' . $fila['razon_social'] . '</td>
										<td>' . $fila['nombres_representante'] . '</td>
										<td>' . $fila['nombres_tecnico'] . '</td>
										<td>' . $fila['direccion'] . '</td>
										<td>' . $fila['telefonos'] . '</td>
										<td>' . $fila['celulares'] . '</td>
										<td>' . $fila['correo'] . '</td>
										<td>' . $fila['tipo_operacion'] . '</td>
										<td>' . $fila['estado'] . '</td>
										<td>' . $fila['observacion'] . '</td>
										<td>' . $fila['id_producto'] . '</td>
										<td>' . $fila['id_vue'] . '</td>
										<td>' . $fila['nombre_pais'] . '</td>
										<td>' . $fila['nombre_comun'] . '</td>
										<td>' . $fila['subtipo_producto'] . '</td>
										<td>' . $fila['tipo_producto'] . '</td>
										<td>' . $fila['tipo_operacion'] . '</td>
										<td>&nbsp;' . $fila['fecha_creacion'] . '</td>
										<td>&nbsp;' . $fila['fecha_modificacion'] . '</td>
										<td>&nbsp;' . $fila['fecha_aprobacion'] . '</td>
										<td>' . $fila['nombre_area'] . '</td>
										<td>' . $fila['tipo_area'] . '</td>
										<td>' . $fila['superficie_utilizada'] . '</td>
										<td>' . $fila['estado_area'] . '</td>
										<td>' . $fila['codigo_area'] . '</td>
										<td>' . $fila['nombre_sitio'] . '</td>
										<td>' . $fila['direccion_sitio'] . '</td>
										<td>' . $fila['telefono'] . '</td>
										<td>' . $fila['referencia'] . '</td>
										<td>' . $fila['parroquia'] . '</td>
										<td>' . $fila['canton'] . '</td>
										<td>' . $fila['provincia'] . '</td>
										<td>&nbsp;' . $fila['codigo_sitio'] . '</td>
										<td>' . $fila['latitud'] . '</td>
										<td>' . $fila['longitud'] . '</td>
										<td>' . $fila['superficie_total'] . '</td>
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
			COORDINACIÓN GENERAL DE SANIDAD VEGETAL <br>
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