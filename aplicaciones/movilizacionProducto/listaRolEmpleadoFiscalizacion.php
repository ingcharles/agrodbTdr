<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorMovilizacionProductos.php';

$conexion = new Conexion();
$cmp = new ControladorMovilizacionProductos();

$identificadorUsuario = $_SESSION['usuario'];
$filaTipoUsuario = pg_fetch_assoc($cmp->obtenerTipoUsuario($conexion, $identificadorUsuario));


$contador = 0;
$itemsFiltrados[] = array();
$res = $cmp->listaRolEmpleadoEmpresa($conexion, $_POST['identificacionEmpleadoH'], $_POST['nombreEmpleadoH'], $_POST['operadorMovilizacionH'], $filaTipoUsuario['codificacion_perfil'], $identificadorUsuario, 'digitadorFaenador');

while($fila = pg_fetch_assoc($res)){
	$itemsFiltrados[] = array('<tr
						id="'.$fila['id_rol_empleado'].'"
						class="item"
						data-rutaAplicacion="movilizacionProducto"
						data-opcion="abrirRolEmpleadoFiscalizacion"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
						<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
						<td>'.$fila['operador_movilizacion'].'</td>
						<td>'.$fila['empleado'].'</td>
						<td>'.$fila['estado'].'</td>
					</tr>');
}

?>
<header>
	<h1>Administrar Digitador Fiscalización para Faenador</h1>
	<nav>
		<?php			
			
		    $ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);			
			while($fila = pg_fetch_assoc($res)){
				$opciones='<a href="#"
					id="' . $fila['estilo'] . '"
					data-destino="detalleItem"
					data-opcion="' . $fila['pagina'] . '"
					data-rutaAplicacion="' . $fila['ruta'] . '"
					>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
				if($filaTipoUsuario['codificacion_perfil'] == 'PFL_USUAR_INT'){
					if($fila["estilo"] != '_nuevo')
					echo $opciones;
				}
				if($filaTipoUsuario['codificacion_perfil'] == 'PFL_USUAR_EXT')
					echo $opciones;
			}
		?>
	</nav>
	<nav>
	<form id="nuevoFiltroMovilizacion" data-rutaAplicacion="movilizacionProducto" data-opcion="listaRolEmpleadoFiscalizacion" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
		<table class="filtro" style='width: 100%;'>
			<tbody>
				<tr>
					<th colspan="4">Consultar Digitador Movilización:</th>
				</tr>
				<tr>
					<td align="left">* Operador Movilización:</td>
					<td colspan="3"><select id="operadorMovilizacionH" name="operadorMovilizacionH" style='width:99%;'>
						
						<?php 
				    	switch ($filaTipoUsuario['codificacion_perfil']){
				    		case 'PFL_USUAR_INT':
				    			echo '<option value="">Seleccione...</option>';
				    			$qEmpresas = $cmp->listaEmpresas($conexion);
				    			while($fila = pg_fetch_assoc($qEmpresas)){
									echo '<option value="' . $fila['id_empresa'] . '">' . $fila['nombre_empresa'] . '</option>';
				    			}
				    		break;
				    	
				    		case 'PFL_USUAR_EXT':
				    			$qResultadoEmpleadoEmpresa = $cmp->consultarRelacionEmpleadoEmpresa($conexion, $identificadorUsuario);
				    			if(pg_num_rows($qResultadoEmpleadoEmpresa) !=0 ){
				    				$qEmpresas = $cmp->listaEmpresas($conexion, pg_fetch_result($qResultadoEmpleadoEmpresa, 0, 'identificador_empresa'));
				    				while($fila = pg_fetch_assoc($qEmpresas)){
									echo '<option value="' . $fila['id_empresa'] . '">' . $fila['nombre_empresa'] . '</option>';
 									}
				    			}
				    		break;
				    	}
						?>
						</select>
					</td>
				</tr>	
				<tr>	
					<td align="left">* Identificación Empleado:</td>
					<td><input id="identificacionEmpleadoH" name="identificacionEmpleadoH" type="text"  style='width:98%;'  maxlength="13"/></td>
					<td align="left">* Nombre Empleado:</td>
					<td><input id="nombreEmpleadoH" name="nombreEmpleadoH" type="text" style='width:98%;'  maxlength="200"/></td>		
				</tr>	
				<tr>
					<td colspan="4" style='text-align:center'><button>Consultar Rol Empleado</button></td>	
				</tr>
				<tr>
					<td colspan="4" style='text-align:center' id="mensajeError"></td>
				</tr>
			</tbody>
		</table>
	</form>
	</nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Operador Movilización</th>
			<th>Empleado</th>				
			<th>Estado</th>							
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);			
	});

	$("#fechaInicio").datepicker({
		changeMonth: true,
	    changeYear: true
	});
  
	$("#fechaFin").datepicker({
	    changeMonth: true,
	    changeYear: true,
	    maxDate:"0"
	});

	$("#nuevoFiltroMovilizacion").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if ($("#nombreEmpleadoH").val().length < 3 && $("#identificacionEmpleadoH").val() == "" && $("#operadorMovilizacionH").val() == "" ) {
	    	error = true;
	    	$("#mensajeError").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
	    }
	
		if($("#identificacionEmpleadoH").val() == "" && $("#nombreEmpleadoH").val() == "" && $("#operadorMovilizacionH").val() == "" ){	
			 error = true;	
				$("#mensajeError").html("Por favor ingrese al menos un campo que contiene (*) para realizar la consulta").addClass('alerta');					
		}
		
		if(!error){
			abrir($(this),event,false);
		}	
	});
</script>