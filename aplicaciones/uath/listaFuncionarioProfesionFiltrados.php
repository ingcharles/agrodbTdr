<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$cd = new ControladorCatastro();
$constg = new Constantes();

$res =$cd->reporteFuncionarioProfesionConsolidado($conexion, $_POST['regimen_laboral'], $_POST['provincia'], 
		$_POST['canton'], $_POST['oficina'], $_POST['coordinacion'], $_POST['direccion'], $_POST['gestion'], 
		$_POST['nivelInstruccion'], $_POST['titulos'], $_POST['carrera'], $_POST['estado']);
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
	
	<form id="filtrar" action="reporteExcelFuncionarioProfesion.php" target="_blank" method="post">
		 <input type="hidden" id="regimen_laboral" name="regimen_laboral" value="<?php echo $_POST['regimen_laboral'];?>" />
		 <input type="hidden" id="provincia" name="provincia" value="<?php echo $_POST['provincia'];?>" />
		 <input type="hidden" id="canton" name="canton" value="<?php echo $_POST['canton'];?>" />
		 <input type="hidden" id="oficina" name="oficina" value="<?php echo $_POST['oficina'];?>" />
		 <input type="hidden" id="coordinacion" name="coordinacion" value="<?php echo $_POST['coordinacion'];?>" />
		 <input type="hidden" id="direccion" name="direccion" value="<?php echo $_POST['direccion'];?>" />
		 <input type="hidden" id="gestion" name="gestion" value="<?php echo $_POST['gestion'];?>" />
		 <input type="hidden" id="nivelInstruccion" name="nivelInstruccion" value="<?php echo $_POST['nivelInstruccion'];?>" />
		 <input type="hidden" id="titulo" name="titulo" value="<?php echo $_POST['titulos'];?>" />
		 <input type="hidden" id="carrera" name="carrera" value="<?php echo $_POST['carrera'];?>" />
		 <input type="hidden" id="estado" name="estado" value="<?php echo $_POST['estado'];?>" />
	 	 <button type="submit" class="guardar">Imprimir</button>	  	 
	</form>
	</div>
	<div id="bandera"></div>
</div>

<div id="tabla">
<table id="tablaReporteContratos" class="soloImpresion">
	<thead>
		<tr>
		    <th>Identificador</th>
		    <th>Nombre</th>
		    <th>Apellido</th>
			<th>R??gimen laboral</th>
			<th>Tipo contrato</th>
			<th>Coordinaci??n</th>
			<th>Direcci??n</th>
			<th>Gesti??n</th>
			<th>Puesto</th>
			<th>Provincia</th>
			<th>Cant??n</th>
			<th>Oficina</th>
			<th>Nivel de Instrucci??n</th>
			<th>Certificado</th>
			<th>T??tulo</th>
			<th>Carrera</th>
		    <th>Instituci??n</th>
		    <th>Estado</th>
			<th>Observaciones</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>
	    <td>'.$fila['identificador'].'</td>
		<td>'.mb_strtoupper($fila['nombre'], 'UTF-8').'</td>
		<td>'.mb_strtoupper($fila['apellido'], 'UTF-8').'</td>
        <td>'.$fila['regimen_laboral'].'</td>
        <td>'.$fila['tipo_contrato'].'</td>
        <td>'.$fila['coordinacion'].'</td>
        <td>'.$fila['direccion'].'</td>
        <td>'.$fila['gestion'].'</td>
		<td>'.$fila['nombre_puesto'].'</td>
		<td>'.$fila['provincia'].'</td>
		<td>'.$fila['canton'].'</td>
		<td>'.$fila['oficina'].'</td>
        <td>'.$fila['nivel_instruccion'].'</td>
        <td>'.$fila['num_certificado'].'</td>
        <td>'.$fila['titulo'].'</td>
        <td>'.$fila['carrera'].'</td>
        <td>'.$fila['institucion'].'</td>
        <td>'.$fila['estado'].'</td>
        <td>'.$fila['observaciones_rrhh'].'</td>
		</tr>';
	 }
	
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>