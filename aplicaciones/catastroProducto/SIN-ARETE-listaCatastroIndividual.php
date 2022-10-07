<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();	
$cc = new ControladorCatalogos();
$cp = new ControladorCatastroProducto();

$identificadorUsuario=$_SESSION['usuario'];
$filaTipoUsuario=pg_fetch_assoc($cp->obtenerTipoUsuario($conexion, $identificadorUsuario));
$banderaTecnicoUsuarioAgrocalidad = 0;
$banderaEmpleadoEmpresa = 0;

switch ($filaTipoUsuario['codificacion_perfil']){
	case 'PFL_USUAR_INT':
		$qResultadoUsuarioTecnico=$cp->verificarTecnicoAgrocalidad($conexion,$identificadorUsuario);
		if(pg_num_rows($qResultadoUsuarioTecnico)!=0){
			$banderaTecnicoUsuarioAgrocalidad = 1;
		}
	break;

	case 'PFL_USUAR_EXT':
		$qResultadoEmpleadoEmpresa=$cp->consultarRelacionEmpleadoEmpresa($conexion, $identificadorUsuario);
		if(pg_num_rows($qResultadoEmpleadoEmpresa)!=0){
			$qResultadoEmpresaOperador=$cp->consultarEmpresaPorOperacion($conexion, "('OPT')",$identificadorUsuario);
			if(pg_num_rows($qResultadoEmpresaOperador)!=0){
				$banderaTecnicoUsuarioAgrocalidad = 1;
			}else{
				$banderaEmpleadoEmpresa = 1;
			}
		}else{
			$banderaEmpleadoEmpresa=0;
		}
	break;

	default:
		echo  'Usuario desconocido';

}

$contador = 0;
$itemsFiltrados[] = array();
$res = $cp->consultaCatastroIndividual($conexion, $_POST['identificadorSolicitanteH'],$_POST['nombreOperadorSolicitante'],$_POST['nombreSitio'],$_POST['provincia'],$_POST['fechaInicio'],$_POST['fechaFin'],$_SESSION['usuario']);
	
while($fila = pg_fetch_assoc($res)){
	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_catastro'].'"
		class="item"
		data-rutaAplicacion="catastroProducto"
		data-opcion="abrirCatastroIndividual"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem">
		<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
		<td>'.$fila['lugar'].'</td>
		<td>'.$fila['operador'].'</td>
		<td>'.$fila['producto'].'</td>
		<td>'.$fila['cantidad'].'</td>
		<td>'.$fila['fecha_registro'].'</td>	
		</tr>');
}

?>

<header>
	<h1>Administrar catastro</h1>
	<nav>
		<?php			
			
		    $ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);			
			while($fila = pg_fetch_assoc($res)){				
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
					  >'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';							
			}
		?>
	</nav>
	<nav>
	
	<form id="nuevoFiltroCatastro" data-rutaAplicacion="catastroProducto" data-opcion="listaCatastroIndividual" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
		<input type="hidden" name="tecnicoUsuarioH" id="tecnicoUsuarioH" value="<?php echo $banderaTecnicoUsuarioAgrocalidad; ?>" />
		<input type="hidden" id="identificadorResponsableH" name="identificadorResponsableH" value="<?php echo $_SESSION['usuario']; ?>" />
		<?php 
		if($banderaEmpleadoEmpresa == 1){
				$empleadoEmpresa=pg_fetch_result($qResultadoEmpleadoEmpresa, 0, 'identificador_empresa');
				echo '<input type="hidden" name="empleadoEmpresaH" id="empleadoEmpresaH" value="'.$banderaEmpleadoEmpresa.'"  />';	
			}
		?>
		
		<table class="filtro" >
			<tbody>
				<tr>
					<th colspan="4">Consultar Catastro:</th>
				</tr>
				<tr>
					<td>* Identificación Operador:</td>
					<td><input id="identificadorSolicitanteH" name="identificadorSolicitanteH" type="text" readonly="readonly" maxlength="13"/></td>
					<td>* Nombre Operador:</td>
					<td><input name="nombreOperadorSolicitante" id="nombreOperadorSolicitante"  type="text"  maxlength="200" /></td>		
				</tr>
				
				<tr>
					<td>* Nombre del Sitio:</td>
					<td><input name="nombreSitio" id="nombreSitio" type="text" maxlength="200" /></td>
					<td>* Provincia:</td>
					<td>
					<select id="provincia" name="provincia" style="width:99%" >
						<option value="0" >Seleccione...</option>
						<?php 
							$qProvincias = $cc->listarLocalizacion($conexion, "PROVINCIAS");
							while($fila = pg_fetch_assoc($qProvincias)){
								echo '<option value="' . $fila['nombre'] . '">' . $fila['nombre'] . '</option>';
							}
						?>		
					</select>
					</td>
				</tr>
				<tr>
					<td>Fecha Inicio:</td>
					<td><input type="text" name="fechaInicio" id="fechaInicio" /></td>
					<td>Fecha Fin:</td>
					<td><input type="text" name="fechaFin" id="fechaFin" /></td>
				</tr>
				<tr>
					<td colspan="4" style='text-align:center'><button>Consultar Catastro</button></td>	
				</tr>
				<tr>
					<td colspan="4" style='text-align:center' id="mensajeError">
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
			<th>Sitio/Área</th>	
			<th>Operador</th>
			<th>Producto</th>
			<th>Cantidad</th>
			<th title="Fecha de registro">F.Registro</th>
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

		var identificadorDueno= <?php echo json_encode($empleadoEmpresa); ?>;
		$("#tecnicoUsuarioH").val()==1?$('#identificadorSolicitanteH').attr("readonly", false):$('#identificadorSolicitanteH').val($("#identificadorResponsableH").val()); 
		if($("#empleadoEmpresaH").val()==1){
			$('#identificadorSolicitanteH').attr("readonly", true);			
			$('#identificadorSolicitanteH').val(identificadorDueno);
		}		
	});

	$("#fechaInicio").datepicker({
	      changeMonth: true,
	      changeYear: true
	});
  
	$("#fechaFin").datepicker({
	      changeMonth: true,
	      changeYear: true
	});

	$("#_eliminar").click(function(event){
		$("#mensajeError").html("");
		if($("#cantidadItemsSeleccionados").text()>1){	
			$("#mensajeError").html("Por favor seleccione un registro de catastro a la vez.").addClass('alerta');
				return false;
			}
		if($("#cantidadItemsSeleccionados").text()==0){
			$("#mensajeError").html("Por favor seleccione un registro de catastro a eliminar.").addClass('alerta');
			return false;
		}
	});
	
	$("#nuevoFiltroCatastro").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#identificadorSolicitanteH").val()=="" && $("#nombreSitio").val()=="" && $("#provincia").val()==0 && $("#nombreOperadorSolicitante").val().length<3 ){	
			 error = true;		
		 	$("#mensajeError").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
		}

		if($("#identificadorSolicitanteH").val()=="" && $("#nombreOperadorSolicitante").val()=="" && $("#provincia").val()==0 && $("#nombreSitio").val().length<3 ){	
			 error = true;		
		 	$("#mensajeError").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
		}
		
		if($("#identificadorSolicitanteH").val()=="" && $("#nombreOperadorSolicitante").val()=="" && $("#nombreSitio").val()=="" && $("#provincia").val()==0  ){	
			error = true;	
			$("#mensajeError").html("Por favor ingrese al menos un campo que contiene (*) para realizar la consulta").addClass('alerta');		
		}

		if(!error){ 
			$("#mensajeError").html('');   
			abrir($(this),event,false);
		}	
	});
</script>
