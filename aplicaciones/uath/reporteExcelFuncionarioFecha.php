<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorCatastro.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=Reporte_Funcionarios_x_fecha.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");


$conexion = new Conexion();
$cd = new ControladorCatastro();
$constg = new Constantes();

$res =$cd->reporteFuncionarioXFecha($conexion, $_POST['identificador'], $_POST['apellido'], 
		$_POST['nombre'], $_POST['provincia'], $_POST['modalidad']);

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">


#tablaReporteContratos 
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	width: 100%;
	margin: 0;
	padding: 0;
    border-collapse:collapse;
}

#tablaReporteContratos td, #tablaReporteContratos th 
{
font-size:1em;
border:1px solid #98bf21;
padding:1px 3px 1px 3px;
}

#tablaReporteContratos th 
{
font-size:1em;
text-align:left;
padding-top:3px;
padding-bottom:2px;
background-color:#A7C942;
color:#ffffff;
}

#logoMagap{
width: 15%;
height:70px;
background-image: url(imgPOA/magap_logo.jpg); background-repeat: no-repeat;
float: left;
}

#logotexto{
width: 10%;
height:80px;
float: left;
}

#logoAgrocalidad{
width: 20%;
height:80px;
background-image: url(imgPOA/agrocalidad.png); background-repeat: no-repeat;
float:left;
}

#textoPOA{
width: 40%;
height:80px;
text-align: center;
float:left;
}

#direccion{
width: 10%;
height:80px;
background-image: url(imgPOA/direccion.png); background-repeat: no-repeat;
float: left;
}

#bandera{
width: 5%;
height:80px;
background-image: url(imgPOA/bandera.png); background-repeat: no-repeat;
float: right;
}



</style>
</head>
<body>
<div id="header">
   	<div id="logoMagap"></div>
	<div id="texto"></div>
	<div id="logoAgrocalidad"></div>
	<div id="textoPOA"><?php echo $constg::NOMBRE_INSTITUCION;?><br> 
	AGROCALIDAD TALENTO HUMANO<br>
	
	</div>
	<div id="direccion"></div>
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
