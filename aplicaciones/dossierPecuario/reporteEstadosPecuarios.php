<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPecuario.php';

header("Content-type: application/octet-stream");
$fecha=new DateTime();
$fecha=$fecha->format('Y_m_d_H_i_s');
$ext   = '.xls';
$nomReporte = 'estados_RIP_'.$fecha.$ext;
//indicamos al navegador que se está devolviendo un archivo

header("Content-Disposition: attachment; filename=".$nomReporte);
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$cp = new ControladorDossierPecuario();

$queryReporte = $cp-> obtenerRegistrosPecuarios($conexion, $_SESSION['idAplicacion']);

?>
<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
#tablaReporteFormatoBase {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	display: inline-block;
	width: auto;
	margin: 0;
	padding: 0;
	border-collapse: collapse;
}

#tablaReporteFormatoBase td,#tablaReporteFormatoBase th {
	font-size: 1em;
	border: 1px solid #98bf21;
	padding: 3px 7px 2px 7px;
}

#tablaReporteFormatoBase th {
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

#textoEncabezado {
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
		<div id="logotexto"></div>
		<div id="logoAgrocalidad"></div>
		
		<div id="textoEncabezado" style="font-size: 16px; font-weight: bold;">
			AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO
			<br>AGROCALIDAD
         <br>REPORTE DE ESTADOS DE SOLICITUDES DE REGISTRO DE PRODUCTOS PECUARIOS
		</div>
		<div id="direccion"></div>
		<div id="bandera"></div>
            
	</div>
  
	<div id="tabla">
		<table id="tablaReporteFormatoBase" class="soloImpresion">
			<thead>
				<tr>
					<th>Fecha inicio</th>
					<th>Fecha de registro</th>
					<th>N° de Solicitud</th>
					<th>N° de Expediente</th>
					<th>N° de Registro</th>					
					<th>Estado</th>
					<th>Remitente</th>
					<th>Ejecutor</th>
					<th>RUC</th>
					<th>Razón Social</th>
					<th>Subtipo de Producto</th>
					<th>Nombre Comercial</th>
					<th>Sitio</th>
					<th>Distrital</th>
					
				</tr>
			</thead>
			<tbody>
				<?php	
	
while ($registro = pg_fetch_assoc($queryReporte)) {
	$presentaciones=null;
	$composiciones=null;
	$usoss=null;
	$formuladores=null;
	
	$registros = $registro;
	echo '<tr style="text-aling=right;">';
	$fechaTxt='';
	if($registros['fecha_inicio']!=null){
	   $fechaTest=new DateTime($registros['fecha_inicio']);
	   $fechaTxt=$fechaTest->format('Y-m-d');
	}
	$filaTabla='<td>'.$fechaTxt.'</td>';
	$fechaTxt='';
	if($registros['fecha_registro']!=null){
	    $fechaTest=new DateTime($registros['fecha_registro']);
	    $fechaTxt=$fechaTest->format('Y-m-d');
	}

	if($registros['estado']==null)
		$registros['estado']='Solicitud';
	$filaTabla=$filaTabla.'<td>'.$fechaTxt.'</td>';
	
	$filaTabla=$filaTabla.'<td>'.$registros['id_solicitud'].'</td>
				<td>'.$registros['id_expediente'].'</td>
				<td>'.$registros['id_certificado'].'</td>
				<td>'.$registros['estado'].'</td>
                <td>'.$registros['nombre_remitente'].'</td>
                <td>'.$registros['nombre_responsable'].'</td>
				<td class="formato">'.$registros['identificador'].'</td>
				<td>'.$registros['razon_social'].'</td>
				<td>'.$registros['subtipo_producto'].'</td>
				<td>'.$registros['nombre_producto'].'</td>
				<td>'.$registros['sitio'].'</td>
				<td>'.$registros['provincia'].'</td>';
	echo $filaTabla;
		
	echo '</tr>';
	
}

	 ?>
			</tbody>
		</table>
	</div>
</body>

</html>
