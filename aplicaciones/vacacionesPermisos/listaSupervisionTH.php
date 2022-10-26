<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorVacaciones.php';

$identificador=$_SESSION['usuario'];

if($identificador==''){
	$usuario=0;
}else{
	$usuario=1;
}

$conexion = new Conexion();	
$ca = new ControladorAreas();
$cv= new ControladorVacaciones();

if($_POST['identificadorBusq']!='' )
	$identificadorBusq=$_POST['identificadorBusq'];
if($_POST['fecha_desde']!='' )
	$fecha_desde=$_POST['fecha_desde'];
if($_POST['fecha_hasta']!='')
	$fecha_hasta=$_POST['fecha_hasta'];

$contador = 0;
$itemsFiltrados[] = array();


if($_POST['estado_requerimiento']!=''){

	$estado=$_POST['estado_requerimiento'];

	$areaUsuario = pg_fetch_assoc($ca->areaUsuario($conexion, $identificador));
	$areaRecursiva = pg_fetch_assoc($ca->buscarAreaResponsablePorUsuarioRecursivo($conexion, $areaUsuario['id_area']));

	$tipoArea = $areaRecursiva['clasificacion'];
	$arrayAreas = explode(',', $areaRecursiva['path']);

	if($tipoArea == 'Planta Central'){
			
		//$areasRevision = $ca->buscarAreaPadrePorClasificacion($conexion, 'DE', 'Planta Central');
		$areasRevision = $ca->buscarEstructuraPlantaCentral($conexion);

		while ($fila = pg_fetch_assoc($areasRevision)){
			$areaBusqueda .= $fila['id_area']."-";
		}
		$areaBusqueda .="DE";
			

	}else{
		$zona = $arrayAreas[2];

		$areasZona = $ca->buscarOficinaTecnicaXArea($conexion, $zona);

		while ($fila = pg_fetch_assoc($areasZona)){
			$areasRevision = $ca->buscarDivisionEstructura($conexion, $fila['id_area']);
				
			while ($fila = pg_fetch_assoc($areasRevision)){
				$areaBusqueda .= $fila['id_area']."-";
			}
		}

		$areaBusqueda .= $zona;
	}

	$res=$cv->obtenerPermisosRevisionProceso($conexion,$tipo_permiso,$fecha_desde,$fecha_hasta,$id_solicitud,$identificadorBusq,$id_director,$estado,$areaBusqueda);

	while($fila = pg_fetch_assoc($res)){

		$itemsFiltrados[]= array('<tr
				id="'.$fila['id_permiso_empleado'].'"
				class="item"
				data-rutaAplicacion="vacacionesPermisos"
				data-opcion="abrirPermisoTH"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<!--td>'.++$contador.'</td-->
				<td>'.$fila['id_permiso_empleado'].'</td>
				<td style="white-space:nowrap;"><b>'.$fila['apellido'].' '.$fila['nombre']. '</b></td>
       			<td> Desde: '.$fila['fecha_inicio'].'<br/> Hasta: '.$fila['fecha_fin'].'</td>
				<td>'.$estadoRegistro=$fila['estado']=='Aprobado'?'<span class="alerta">Por generar informe</span>':'<a href='.$fila['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Generado</a>'.'</td>
			</tr>');
	}
}

?>

<header>
	<h1>Talento Humano</h1>

		<nav>
	<form id="filtrar" data-rutaAplicacion="vacacionesPermisos" data-opcion="listaSupervisionTH" data-destino="areaTrabajo #listadoItems">
		
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
				<table class="filtro" style='width: 400px;'>
					<tbody>
					<tr>
						<th colspan="3">Buscar:</th>
					</tr>
					<tr>
						<td>Cédula:</td>
						<td> <input id="identificadorBusq" type="text" name="identificadorBusq" maxlength="10" value="<?php echo $_POST['identificadorBusq'];?>">	</td>
					</tr>	
					<tr>
						<td>Fecha inicio:</td>
						<td> <input id="fecha_desde" type="text" name="fecha_desde" maxlength="10" value="<?php echo $_POST['fecha_desde'];?>">	</td>
					</tr>
					<tr>
						<td>Fecha Fin:</td>
						<td> <input id="fecha_hasta" type="text" name="fecha_hasta" maxlength="10" value="<?php echo $_POST['fecha_hasta'];?>">	</td>
					</tr>
					<tr>
						<td>Estado:</td>
						<td> <select
						name="estado_requerimiento" id="estado_requerimiento">
						<option value="">Seleccione un estado....</option>
						<option value="Aprobado">Por generar informe recursos humano</option>
						<option value="InformeGenerado">Con informe generado</option>
						</select></td>
					</tr>
					<tr>
						<td id="mensajeError"></td>
						<td colspan="5"> <button id='buscar'>Buscar</button>	</td>
					</tr>
					</tbody>
					</table>
				</form>
</nav>
</header>

<div id="estadoSesion"></div>
<div id="paginacion" class="normal">
 </div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>Cod</th>
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
//<!--  data-destino="areaTrabajo #listadoItems"-->
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
		//construirPaginacionexp($("#paginacion"),<?php echo 1;?>);
		
		if(usuario == '0'){
			$("#estadoSesion").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#buscar").attr("disabled", "disabled");
		}			
	});

	$( "#fecha_desde" ).datepicker({
	      changeMonth: true,
	      changeYear: true,
	      yearRange: '-100:+0'
	});
	$( "#fecha_hasta" ).datepicker({
	      changeMonth: true,
	      changeYear: true,
	      yearRange: '-100:+0'
	});

	$("#filtrar").submit(function(event){
		event.preventDefault();
		error = false;
		if($('#estado_requerimiento').val() == ''){
			error = true;
			$("#estado_requerimiento").addClass("alerta");
		}
		
		if($('#fecha_hasta').val().length !=0 || $('#estado_requerimiento').val().length!=0)
		{		
			if (!error){
				abrir($('#filtrar'),event, false);
			}
			
		}
		
	});

</script>