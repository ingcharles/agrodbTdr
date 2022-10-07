<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$cd = new ControladorCatastro();
$constg = new Constantes();

$res =$cd->reporteFuncionarioXFecha($conexion, $_POST['identificador'], $_POST['apellido'], $_POST['nombre'], $_POST['provincia'], 
		$_POST['modalidad'], $_POST['fecha_inicio'], $_POST['fecha_fin']);
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
	
	<form id="filtrar" action="reporteExcelFuncionarioFecha.php" target="_blank" method="post">
		 <input type="hidden" id="identificador" name="identificador" value="<?php echo $_POST['identificador'];?>" />
		 <input type="hidden" id="apellido" name="apellido" value="<?php echo $_POST['apellido'];?>" />
		 <input type="hidden" id="nombre" name="nombre" value="<?php echo $_POST['nombre'];?>" />
		 <input type="hidden" id="provincia" name="provincia" value="<?php echo $_POST['provincia'];?>" />
		 <input type="hidden" id="modalidad" name="modalidad" value="<?php echo $_POST['modalidad'];?>" />
		 <input type="hidden" id="fecha_inicio" name="fecha_inicio" value="<?php echo $_POST['fecha_inicio'];?>" />
		 <input type="hidden" id="fecha_fin" name="fecha_fin" value="<?php echo $_POST['fecha_fin'];?>" />
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
			<th>Fecha inicio del funcionario</th>
			<th>Fecha fin del funcionario</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 while($fila = pg_fetch_assoc($res)){
	     $fecha = pg_fetch_assoc($cd->devolverFechaInicial($conexion, $fila['identificador']));
	     $fechaInicial = 'No disponible';
	     if($fecha['descripcion'] == 'unico' || $fecha['descripcion'] == 'continuo 2' || $fecha['descripcion'] == 'continuo 3'){
	         $fechaInicial=$fecha['fecha_inicial'];
	     }
	     
	     if($_POST['fecha_inicio'] != '' ){
	         if($fechaInicial != 'No disponible'){
	             $fechaActual = date('d-m-Y',strtotime($fechaInicial));
	             $fecha_actual = strtotime($fechaActual);
	             $fecha_entrada = strtotime($_POST['fecha_inicio']);
	             if( $fecha_actual >= $fecha_entrada){
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
                    <td>'.$fechaInicial.'</td>
                    <td>'.$fila['fecha_fin'].'</td>
            		</tr>';
	           }
	         }
	     }else{
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
                <td>'.$fechaInicial.'</td>
                <td>'.$fila['fecha_fin'].'</td>
        		</tr>';
	     }
	 }
	
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>