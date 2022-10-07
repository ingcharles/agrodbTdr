<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';
try {
$conexion = new Conexion();
$cai = new ControladorAccidentesIndicentes();
$constg = new Constantes();

$res =$cai->reporteAccidenteIncidente($conexion,$_POST['zona'],$_POST['identificador'],$_POST['estadoSolicitud'],$_POST['fechaDesde'],$_POST['fechaHasta']);

} catch (Exception $e) {
	echo $e;
}
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
	<div id="textoPOA"><?php echo $constg::NOMBRE_INSTITUCION;?><br> 
	</div>
	<div id="direccionFisica"></div>
	<div id="imprimir">
	<form id="filtrar" action="reporteExcelSso.php" target="_blank" method="post">
		 <input type="hidden" id="zona" name="zona" value="<?php echo $_POST['zona'];?>" />
		 <input type="hidden" id="identificador" name="identificador" value="<?php echo $_POST['identificador'];?>" />
		 <input type="hidden" id="estadoSolicitud" name="estadoSolicitud" value="<?php echo $_POST['estadoSolicitud'];?>" />
		 <input type="hidden" id="fechaDesde" name="fechaDesde" value="<?php echo $_POST['fechaDesde'];?>" />
		 <input type="hidden" id="fechaHasta" name="fechaHasta" value="<?php echo $_POST['fechaHasta'];?>" />
	 	 <button type="submit" class="guardar">Imprimir</button>	  	 
	</form>
	</div>
	<div id="bandera"></div>
</div>
<div id="tabla">
<table id="tablaReporteContratos" class="soloImpresion">
	<thead>
		<tr>
		    <th>Número de Resgistro</th>
		    <th>Estado</th>
		    <th>Fecha del Accidente</th>
			<th>Tipo de Accidente</th>
			<th>Distrito</th>
			<th>Provincia</th>
			<th>Ciudad</th>
			<th>Género</th>
			<th>Indetificación</th>
			<th>Nombres y Apellidos</th>
			<th>Edad</th>
			<th>Lugar del Accidente</th>
			<th>Dirección del Accidente</th>
			<th>Descripción del Accidente</th>
			<th>Descripción de las Lesiones</th>
			<th>Tiempo de Reposo</th>
			
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 while($fila = pg_fetch_assoc($res)){
	 	$distrito=$fila['nombrearea'];
	 	$reposo='';
	 	if($fila['id_area_padre'] == 'DGATH')$distrito='Planta central';
	 	if($fila['reposo_desde'] != '')
	 		$reposo='Desde: '.$fila['reposo_desde'].' - Hasta: '.$fila['reposo_hasta'];
	 	echo '<tr>
	    <td>'.$fila['cod_datos_accidente'].'</td>
		<td>'.strtoupper($fila['estado']).'</td>
		<td>'.$fila['fecha_accidente'].'</td>
        <td>'.mb_strtoupper($fila['tipo_sso'], 'UTF-8').'</td>
        <td>'.$distrito.'</td>
    	<td>'.$fila['provincia'].'</td>
        <td>'.$fila['ciudad'].'</td>
    	<td>'.$fila['genero'].'</td>
    	<td>'.$fila['identificador_accidentado'].'</td>
        <td>'.$fila['funcionario'].'</td>
        <td>'.$fila['edad'].'</td>
        <td>'.$fila['lugar_accidente'].'</td>
        <td>'.$fila['direccion'].'</td>
        <td>'.$fila['describir_accidentado'].'</td>
        <td>'.$fila['descripcion_lesiones'].'</td>
        <td>'.$reposo.'</td>
		</tr>';
	 }
	
	 ?>
	</tbody>
</table>

</div>
</body>
</html>
