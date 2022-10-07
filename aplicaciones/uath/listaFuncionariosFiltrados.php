<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$cd = new ControladorCatastro();
$constg = new Constantes();

$res =$cd->reporteFuncionarioConsolidado($conexion, $_POST['regimen_laboral'], $_POST['provincia'], 
		$_POST['canton'], $_POST['oficina'], $_POST['coordinacion'], $_POST['direccion'], $_POST['gestion'], 
		$_POST['estado_civil'], $_POST['genero'], $_POST['identificacion_etnica'], $_POST['discapacidad']);
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
	
	<form id="filtrar" action="reporteExcelFuncionario.php" target="_blank" method="post">
		 <input type="hidden" id="regimen_laboral" name="regimen_laboral" value="<?php echo $_POST['regimen_laboral'];?>" />
		 <input type="hidden" id="provincia" name="provincia" value="<?php echo $_POST['provincia'];?>" />
		 <input type="hidden" id="canton" name="canton" value="<?php echo $_POST['canton'];?>" />
		 <input type="hidden" id="oficina" name="oficina" value="<?php echo $_POST['oficina'];?>" />
		 <input type="hidden" id="coordinacion" name="coordinacion" value="<?php echo $_POST['coordinacion'];?>" />
		 <input type="hidden" id="direccion" name="direccion" value="<?php echo $_POST['direccion'];?>" />
		 <input type="hidden" id="gestion" name="gestion" value="<?php echo $_POST['gestion'];?>" />
		 <input type="hidden" id="estado_civil" name="estado_civil" value="<?php echo $_POST['estado_civil'];?>" />
		 <input type="hidden" id="genero" name="genero" value="<?php echo $_POST['genero'];?>" />
		 <input type="hidden" id="identificacion_etnica" name="identificacion_etnica" value="<?php echo $_POST['identificacion_etnica'];?>" />
		 <input type="hidden" id="discapacidad" name="discapacidad" value="<?php echo $_POST['tiene_discapacidad'];?>" />
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
		    <th>Género</th>
		    <th>Estado Civil</th>
		    <th>Cédula Militar</th>
		    <th>Fecha de Nacimiento</th>
		    <th>Edad</th>
		    <th>Tipo de Sangre</th>
		    <th>Identificación Étnica</th>
		    <th>Nacionalidad Indígena</th>
		    <th>Discapacidad</th>
		    <th>Carnet CONADIS</th>
		    <th>Enfermedad Catastrófica</th>
		    <th>Nacionalidad</th>
		    <th>Provincia</th>
		    <th>Cantón</th>
		    <th>Domicilio</th>
		    <th>Teléfono</th>
		    <th>Celular</th>
		    <th>Correo personal</th>   		    
			<th>Régimen laboral</th>
			<th>Tipo Contrato</th>
			<th>Coordinación</th>
			<th>Dirección</th>
			<th>Gestión</th>
			<th>Oficina</th>
			<th>Puesto</th>
			<th>Correo Institucional</th>
			<th>Extensión</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>
	    <td>'.$fila['identificador'].'</td>
		<td>'.mb_strtoupper($fila['nombre'], 'UTF-8').'</td>
		<td>'.mb_strtoupper($fila['apellido'], 'UTF-8').'</td>
        <td>'.$fila['genero'].'</td>
        <td>'.$fila['estado_civil'].'</td>
        <td>'.$fila['cedula_militar'].'</td>
        <td>'.$fila['fecha_nacimiento'].'</td>
        <td>'.$fila['edad'].'</td>
        <td>'.$fila['tipo_sangre'].'</td>
		<td>'.$fila['identificacion_etnica'].'</td>
		<td>'.$fila['nacionalidad_indigena'].'</td>
		<td>'.$fila['tiene_discapacidad'].'</td>
		<td>'.$fila['carnet_conadis_empleado'].'</td>
        <td>'.$fila['nombre_enfermedad_catastrofica'].'</td>
        <td>'.$fila['nacionalidad'].'</td>
        <td>'.$fila['provincia'].'</td>
        <td>'.$fila['canton'].'</td>
        <td>'.$fila['domicilio'].'</td>
        <td>'.$fila['convencional'].'</td>
        <td>'.$fila['celular'].'</td>
        <td>'.$fila['mail_personal'].'</td>
        <td>'.$fila['regimen_laboral'].'</td>
        <td>'.$fila['tipo_contrato'].'</td>
        <td>'.$fila['coordinacion'].'</td>
        <td>'.$fila['direccion'].'</td>
        <td>'.$fila['gestion'].'</td>
        <td>'.$fila['oficina'].'</td>
        <td>'.$fila['nombre_puesto'].'</td>
        <td>'.$fila['mail_institucional'].'</td>
        <td>'.$fila['extension_magap'].'</td>
		</tr>';
	 }
	
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>