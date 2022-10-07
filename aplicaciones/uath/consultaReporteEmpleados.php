<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorAreas.php';
	
$conexion = new Conexion();	
$cc = new ControladorCatastro();
$car = new ControladorAreas();

if ($_POST['nombreEmpleado']!='')
	$nombre=$_POST['nombreEmpleado'];
if($_POST['apellidoEmpleado']!='' )
	$apellido=$_POST['apellidoEmpleado'];
if($_POST['fechaInicio']!='' )
	$fecha_inicio=$_POST['fechaInicio'];
if($_POST['tituloCapacitacion']!='' )
	$titulo_capacitacion=$_POST['tituloCapacitacion'];
if($_POST['fechaFin']!='' )
	$fecha_fin=$_POST['fechaFin'];

if($_POST['identificador']!=''){
	$identificador=$_POST['identificador'];
}

$identificadorTH=$_SESSION['usuario'];


if($identificadorTH==''){
	$usuario=0;
}else{
	$usuario=1;
}



$res = $cc->listaEmpleadosCapacitacion($conexion,$identificador,$apellido,$nombre, $titulo_capacitacion,$fecha_inicio,$fecha_fin);
$contador = 0;
$itemsFiltrados[] = array();


?>
<form id='busquedaReporte' data-rutaAplicacion='uath' data-opcion='abrirReporteEmpleados' data-destino="detalleItem">

<div id="paginacion" class="normal">

</div>
 
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Funcionario</th>
			<th>Capacitacion</th>
			<th>Fechas</th>				
		</tr>
	</thead>
	<tbody>
	</tbody>


<?php 

	$areaUsuarioTH = pg_fetch_assoc($car->areaUsuario($conexion, $identificadorTH));
	
	if($areaUsuarioTH['clasificacion']=='Planta Central'){
		while($fila = pg_fetch_assoc($res)){
		    $fecha_salida=$fila['fecha_fin_capacitacion']!=''?date('d/m/Y',strtotime($fila['fecha_fin_capacitacion'])):'Actualidad';
			$itemsFiltrados[] = array('<tr
										id="'.$fila['identificador'].'"
										class="item"
										data-rutaAplicacion="uath"
										data-opcion="abrirDatosEmpleado"
										ondragstart="drag(event)"
										draggable="true"
										data-destino="detalleItem">
										<td>'.++$contador.'</td>
										<td style="white-space:nowrap;"><b>'.$fila['apellido'].'</b><br/><b>'.$fila['nombre'].'</b></td>
						       			<td>'.$fila['titulo_capacitacion'].'<br/>'.$fila['pais'].'</td>
						                <td>'.date('d/m/Y',strtotime($fila['fecha_inicio_capacitacion'])).'<br/>'.$fecha_salida.'</td>
												
										</tr>');
		}
	}else if($areaUsuarioTH['clasificacion']=='Unidad'){
	
			
		$zonaTH = pg_fetch_assoc($car->buscarArea($conexion, $areaUsuarioTH['id_area_padre']));
		$listaZonas = $car->buscarOficinaTecnicaXArea($conexion, $zonaTH['zona_area']);
	
			
		while($area = pg_fetch_assoc($listaZonas)){
			$zonasFuncionarios[] = $area['id_area'];
	
			$listaAreas = $car->buscarAreasYSubprocesos($conexion, $area['id_area']);
	
			while($areaGestiones = pg_fetch_assoc($listaAreas)){
				$zonasFuncionarios[] = $areaGestiones['id_area'];
			}
		}
	
			
		while($fila = pg_fetch_assoc($res)){
			if (in_array($fila['id_area'], $zonasFuncionarios)) {
				$fecha_salida=$fila['fecha_fin_capacitacion']!=''?date('d/m/Y',strtotime($fila['fecha_fin_capacitacion'])):'Actualidad';
				$itemsFiltrados[] = array('<tr
										id="'.$fila['identificador'].'"
										class="item"
										data-rutaAplicacion="uath"
										data-opcion="abrirDatosEmpleado"
										ondragstart="drag(event)"
										draggable="true"
										data-destino="detalleItem">
										<td>'.++$contador.'</td>
										<td style="white-space:nowrap;"><b>'.$fila['apellido'].'</b><br/><b>'.$fila['nombre'].'</b></td>
						       			<td>'.$fila['titulo_capacitacion'].'<br/>'.$fila['pais'].'</td>
						                <td>'.date('d/m/Y',strtotime($fila['fecha_inicio_capacitacion'])).'<br/>'.$fecha_salida.'</td>
												
										</tr>');
			}
		}
	}
/*while($fila = pg_fetch_assoc($res)){
    $fecha_salida=$fila['fecha_fin_capacitacion']!=''?date('d/m/Y',strtotime($fila['fecha_fin_capacitacion'])):'Actualidad';
	$itemsFiltrados[] = array('<tr
				id="'.$fila['identificador'].'"
				class="item"
				data-rutaAplicacion="uath"
				data-opcion="abrirDatosEmpleado"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td style="white-space:nowrap;"><b>'.$fila['apellido'].'</b><br/><b>'.$fila['nombre'].'</b></td>
       			<td>'.$fila['titulo_capacitacion'].'<br/>'.$fila['pais'].'</td>
                <td>'.date('d/m/Y',strtotime($fila['fecha_inicio_capacitacion'])).'<br/>'.$fecha_salida.'</td>
						
			</tr>');
}*/
?>
</table>

<div id="valores"></div>
	
	

</form>
<script type="text/javascript"> 

	var usuario = <?php echo json_encode($usuario); ?>;

	$("#busquedaReporte").submit(function(event){
		abrir($(this),event,false);
	});
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);

		if(usuario == '0'){
			$("#estadoSesion").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		}
	});

</script>
