<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

header("Content-type: application/octet-stream");

$ext   = '.xls';
$nomReporte = 'REPORTE RPIAPA '.$fecha.$ext;
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename='".$nomReporte."'");
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$rpiapa = new ControladorCatalogos();


$res = $rpiapa-> reporteImprimirProductosInsumosPlantasAutoconsumo($conexion);

//print_r($_POST);

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
	background-image: url(img/magap_logo.jpg);
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
	background-image:
		url(http://localhost/agrodb/aplicaciones/notificacionEnfermedades/img/logoAgro.png);
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


</body>
</head>
<body>
	<div id="header">
		<div id="logoMagap"></div>
		<div id="texto"></div>
		<div id="logoAgrocalidad"></div>
		<table class="soloImpresion">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</thead>
		</table>
		<div id="textoPOA" style="font-size: 16px; font-weight: bold;">
			MINISTERIO DE AGRICULTURA, GANADERIA, ACUACULTURA Y PESCA<br>
			AGROCALIDAD<br> REPORTE DE PRODUCTOS DE INSUMOS AGRICOLAS
			<br>
		</div>
		<div id="direccion"></div>
		<div id="bandera"></div>
	</div>




	<div id="tabla">
		<table id="tablaReporteVacunaAnimal" class="soloImpresion">
			<thead>
				<tr>
					<th>N° de Registro</th>
					<th>Fecha Registro</th>
					<th>Estado</th>
					<th>Id Operador</th>
					<th>Razón Social</th>
					<th>Subtipo de Producto</th>
					<th>Nombre Comercial</th>
					<th>Presentación</th>
					<th>Composición de Producto</th>
					<th>Formulación</th>
					<th>Dosis</th>
					<th>Categoría Toxicológica</th>
					<th>Período de Reingreso</th>
					<th>Período de Carencia</th>
					<th>Uso Autorizado</th>
					<th>Frabricante/Formulador</th>
					<th>Observación</th>
					

				</tr>
			</thead>
			<tbody>
				<?php	
	
while ($registro = pg_fetch_assoc($res)) {
	$presentaciones=null;
	$composiciones=null;
	$usoss=null;
	$formuladores=null;
	$registros = json_decode($registro[row_to_json], true);
	echo '<tr>';
	
		echo '<td style="text-aling=right;">'.$registros['numero_registro'].'</td>
				<td>'.$registros['fecha'].'</td>
				<td>'.$registros['estado'].'</td>
				<td class="formato">'.$registros['id_operador'].'</td>
				<td>'.$registros['razon_social'].'</td>
				<td>'.$registros['subtipo_producto'].'</td>
				<td>'.$registros['nombre_comun'].'</td>';
		
		echo'<td>';
			foreach ((array)$registros['presentacion'] as $presentacion) {
				 $presentaciones.=$presentacion['presentacion'].' '.$presentacion['unidad_medida'].' + ';
			}
			echo rtrim ($presentaciones, ' +' );
		echo '</td>';
		
		echo'<td>';
			foreach ((array)$registros['composicion'] as $composicion) { 
				$composiciones.= $composicion['ingrediente_activo'].' '.$composicion['concentracion'].' '.$composicion['unidad_medida'].', ';
			}
			echo rtrim ($composiciones, ' ,' );
		echo '</td>';
		
		echo '<td>'.$registros['formulacion'].'</td>
				<td>'.$registros['dosis'].'</td>
				<td>'.$registros['categoria_toxicologica'].'</td>
				<td>'.$registros['periodo_reingreso'].'</td>
				<td>'.$registros['periodo_carencia_retiro'].'</td>';
				
		echo'<td>';
			foreach ((array)$registros['usos'] as $usos) { 
				$usoss.=$usos['nombre_uso'].' Aplicado a '. $usos['nombre_producto_inocuidad'].', ';
			}
			echo rtrim ($usoss, ' ,' );
		echo '</td>';
		
		echo'<td>';
			foreach ((array)$registros['formulador'] as $formulador) { 
				$formuladores.=$formulador['nombre_ff'].' - '.$formulador['pais_origen'].', ';	
			}
			echo rtrim ($formuladores, ' ,' );
		echo '</td>';
		
		echo '<td>'.$registros['observacion'].'</td>';
		
	echo '</tr>';
	
}

	 ?>
			</tbody>
		</table>
	</div>

</html>
