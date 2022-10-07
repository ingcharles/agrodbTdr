<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

require_once '../ensayoEficacia/clases/Conversiones.php';

header("Content-type: application/octet-stream");
$fecha=new DateTime();
$fecha=$fecha->format('Y_m_d_H_i_s');
$ext   = '.xls';
$nomReporte = 'gerencial_IF_'.$fecha.$ext;
//indicamos al navegador que se está devolviendo un archivo

header("Content-Disposition: attachment; filename=".$nomReporte);
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$ce = new ControladorEnsayoEficacia();
$conversion=new Conversiones();

$res = $ce-> obtenerMatrizServicioInformes($conexion);

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
                  <br>MATRIZ DE SERVICIOS DE INFORMES FINALES
		</div>
		<div id="direccion"></div>
		<div id="bandera"></div>
            
	</div>
  
	<div id="tabla">
		<table id="tablaReporteVacunaAnimal" class="soloImpresion">
			<thead>
				<tr>
					
					<th>N° de Expediente</th>
					<th>Fecha ingreso</th>					
					<th>Año</th>
					<th>Mes</th>
					
					<th>Empresa</th>
					<th>Tipo de trámite</th>
					<th>Producto</th>
					<th>Fecha de asignación</th>
					<th>Identificador</th>
					<th>Evaluador</th>
					<th>Fecha de entrega</th>
					<th>Entrega operador</th>
					<th>Año</th>
					<th>Mes</th>
					<th>Decisión</th>
					<th>Tiempo real</th>	
					<th>Tiempo programado</th>	
					<th>Eficiencia</th>	
					<th># observaciones</th>
					<th>Comentario</th>
					<th>Justificación retraso</th>
				</tr>
			</thead>
			<tbody>
				<?php	
	
while ($registro = pg_fetch_assoc($res)) {
	$presentaciones=null;
	$composiciones=null;
	$usoss=null;
	$formuladores=null;
	
	$registros = $registro;

	if($registros['decision']!=null){
		if($registros['decision']=='A'){
			$registros['decision']='APROBADO';
		}
		else if($registros['decision']=='O'){
			$registros['decision']='OBSERVADO';
		}
		
	}
	$fechaSolicitud=new DateTime($registros['fecha_solicitud']);
	$index=intval($fechaSolicitud->format('n'));
	$index--;
	$fechaMes='';
	if($index>=0){
		$fechaMes=$conversion->mes($index);
	}
	$fechaInicio=new DateTime($registros['fecha_inicio']);
	$fechaFin=new DateTime($registros['fecha_fin']);

	$fechaOperador='';
	$fechaAnio='';
	$fechaOperadorMes='';
	$ciIdentificador='';
	$nombresEvaluador='';
	if($registros['identificador']==$registros['operador']){
		$ciIdentificador=$registros['identificador'];
		$nombresEvaluador="Operador";
		$fecha=new DateTime($registros['fecha_inicio']);
		$fechaOperador=$fecha->format('Y-m-d');
		$fechaAnio=$fecha->format('Y');
		$index=intval($fecha->format('n'));
		$index--;
		if($index>=0){
			$fechaOperadorMes=$conversion->mes($index);
		}
	}
	else{
		
		$ciIdentificador=$registros['perfil_identificador'];
		$nombresEvaluador=$registros['nombres_evaluador'];
	}

	

	echo '<tr>';
	
		echo '<td style="text-aling=right;">'.$registros['id_expediente'].'</td>
				<td>'.$fechaSolicitud->format('Y-m-d').'</td>
				<td>'.$fechaSolicitud->format('Y').'</td>
				<td>'.$fechaMes.'</td>				
				
            <td>'.$registros['razon_social'].'</td>
				<td>'.$registros['nombre'].'</td>
            <td>'.$registros['nombre_producto'].'</td>
				<td>'.$fechaInicio->format('Y-m-d').'</td>
				<td class="formato">'.$ciIdentificador.'</td>
            <td>'.$nombresEvaluador.'</td>
            <td>'.$fechaFin->format('Y-m-d').'</td>
				<td>'.$fechaOperador.'</td>	
				<td>'.$fechaAnio.'</td>	
				<td>'.$fechaOperadorMes.'</td>	
            <td>'.$registros['decision'].'</td>
            <td>'.$registros['tiempo_real'].'</td>
            <td>'.$registros['plazo'].'</td>
            <td>'.$registros['eficiencia'].'%</td>
				<td>'.$registros['numero_observaciones'].'</td>
				<td>'.$registros['observacion'].'</td>
				<td>'.$registros['retraso'].'</td>'
		;
		
	echo '</tr>';
	
}

	 ?>
			</tbody>
		</table>
	</div>
</body>

</html>
