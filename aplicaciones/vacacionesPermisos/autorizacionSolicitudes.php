<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';

$identificador=$_SESSION['usuario'];

if($identificador==''){
	$usuario=0;
}else{
	$usuario=1;
}

$conexion = new Conexion();	
$cv = new ControladorVacaciones();

$estado_solicitud='creado';
$contador = 0;
$itemsFiltrados[] = array();

if($identificador != ''){
	$res = $cv->obtenerSolicitudes($conexion,$tipo_permiso,$fecha_desde,$fecha_hasta,$id_solicitud,'',$identificador,$estado_solicitud);
	
	while($fila = pg_fetch_assoc($res)){
			$itemsFiltrados[] = array('<tr
				id="'.$fila['id_permiso_empleado'].'"
				class="item"
				data-rutaAplicacion="vacacionesPermisos"
				data-opcion="aprobarSolicitud"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td style="white-space:nowrap;"><b>'.$fila['apellido'].' '.$fila['nombre'].'</b></td>
       			<td> Desde: '.$fila['fecha_inicio'].'<br/> Hasta: '.$fila['fecha_fin'].'</td>
				<td>'.$fila['estado'].'</td>
			</tr>');
	}
}
	
?>
<header>
	<h1>Aprobación solicitudes</h1>
</header>
 
 <div id="estado"></div>
 <div id="paginacion" class="normal">
 </div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Funcionario</th>
			<th>Fechas</th>
			<th>Estado</th>				
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script>	
var usuario = <?php echo json_encode($usuario); ?>;
$(document).ready(function(){
	$("#listadoItems").removeClass("comunes");
	$("#listadoItems").addClass("lista");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
	construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);

	if(usuario == '0'){
		$("#estado").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
	}			
});	
</script>