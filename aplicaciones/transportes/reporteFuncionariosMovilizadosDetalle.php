<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();
$constg = new Constantes();

$localizacion = htmlspecialchars ($_POST['localizacion'],ENT_NOQUOTES,'UTF-8');
$fechaInicio = htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8');

$completo = $cv->obtenerReporteUsuariosMovilizados($conexion, $localizacion, $fechaInicio, $fechaFin);


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
	<div id="textoPOA">Ministerio de Agricultura, Ganaderia, Acuacultura y Pesca<Br>
						<?php echo $constg::NOMBRE_INSTITUCION;?><Br> 
							Reporte de Movilizaciones Generadas y Funcionarios Movilizados <Br>
							Del <?php echo $fechaInicio . ' al ' . $fechaFin;?><br>
	</div>
	<div id="direccion"></div>
	<div id="imprimir">
	<form id="filtrar" action="reporteFuncionariosMovilizadosDetalleExcel.php" target="_blank" method="post">
	 <input type="hidden" id="localizacion" name="localizacion" value="<?php echo $localizacion;?>" />
	 <input type="hidden" id="fechaInicio" name="fechaInicio" value="<?php echo $fechaInicio;?>" />
	 <input type="hidden" id="fechaFin" name="fechaFin" value="<?php echo $fechaFin;?>" />
	 <button type="submit" class="guardar">Imprimir</button>	  	 
	</form>
	</div>
	<div id="bandera"></div>
</div>
<div id="tabla">
<table id="tablaReportePresupuesto" class="soloImpresion">
	<thead>
		<tr>
		    <th>ID MOVILIZACIÓN</th>
		    <th>TIPO DE MOVILIZACIÓN</th>
			<th>FECHA DE SOLICITUD</th>
			<th>DESCRIPCIÓN</th>
			<th>PLACA</th>
			<th>KM INICIAL</th>
			<th>KM FINAL</th>
			<th>LOCALIZACIÓN</th>
			<th>OBSERVACIÓN DE RUTA</th>
			<th>NÚM DE PASAJEROS</th>
		    <th>CONDUCTOR</th>
		    <th>ESTADO</th>
		</tr>
	</thead>
	<tbody>
	
	 <?php
	 
	 //Matriz completa
	 while($fila = pg_fetch_assoc($completo)){
	 	
	 	echo '	<tr>
				    <td class="formatoTexto">'.$fila['id_movilizacion'].'</td>
			        <td class="formatoTexto">'.$fila['tipo_movilizacion'].'</td>
			        <td class="formatoTexto">'.$fila['fecha_solicitud'].'</td>
			        <td class="formatoTexto">'.$fila['descripcion'].'</td>
			    	<td class="formatoTexto">'.$fila['placa'].'</td>
			    	<td class="formatoTexto">'.$fila['kilometraje_inicial'].'</td>
			        <td class="formatoTexto">'.$fila['kilometraje_final'].'</td>
			        <td class="formatoTexto">'.$fila['localizacion'].'</td>
			        <td class="formatoTexto">'.$fila['observacion_ruta'].'</td>
					<td class="formatoTexto">'.$fila['num_pasajeros'].'</td>
			        <td class="formatoTexto">'.$fila['nombre'].' '.$fila['apellido'].'</td>
			        <td class="formatoTexto">'.(($fila['estado']=="1")? 'Creado':(($fila['estado']=="2")? 'Por Imprimir':(($fila['estado']=="3")? 'Por Finalizar':(($fila['estado']=="4")? 'Finalizado':'Eliminado')))).'</td>
				</tr>';
	 }
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>