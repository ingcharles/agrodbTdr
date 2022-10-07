<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';

$cc = new ControladorCatalogos();
$cp = new ControladorCatastroProducto();
$conexion = new Conexion();

$identificadorUsuario=$_SESSION['usuario'];
$banderaTecnicoUsuarioAgrocalidad = 0;
$qResultadoUsuarioTecnico=$cp->verificarTecnicoAgrocalidad($conexion, $identificadorUsuario);
if(pg_num_rows($qResultadoUsuarioTecnico)!=0){
	$banderaTecnicoUsuarioAgrocalidad = 1;
}

$qResultadoDistibuidor=$cp->verificarDistribuidor($conexion, $identificadorUsuario);
if(pg_num_rows($qResultadoDistibuidor)!=0){
	$banderaTecnicoUsuarioAgrocalidad = 1;
}

$banderaEmpleadoEmpresa = 0;
$qResultadoEmpleadoEmpresa=$cp->ListaEmpleadoEmpresa($conexion, $identificadorUsuario);
if(pg_num_rows($qResultadoEmpleadoEmpresa)!=0){
	$banderaEmpleadoEmpresa = 1;	
}
?>
<header>
	<nav>
		<?php
		    $ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificadorUsuario);
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
</header>
<header>
	<h1>Dar de baja</h1>
	<nav>
		<form id="nuevoFiltroCatastro" data-rutaAplicacion="catastroProducto" data-opcion="listaDarBajaCatastroIndividual" data-destino="tabla">
			<?php 
				if($banderaEmpleadoEmpresa == 1){
					$fila = pg_fetch_assoc($qResultadoEmpleadoEmpresa);
					$identificadorEmisor=$fila['identificador_empresa'];
					echo '<input type=hidden name=empleadoEmpresaH id=empleadoEmpresaH value="'.$banderaEmpleadoEmpresa.'"  />';	
				}
			?>
			<input type="hidden" name="tecnicoUsuarioH" id="tecnicoUsuarioH" value="<?php	echo $banderaTecnicoUsuarioAgrocalidad;	?>"   />
			<input type="hidden" id="identificadorResponsableH" name="identificadorResponsableH" value="<?php echo $identificadorUsuario;?>" />
			<table class="filtro">
			
				<tr>
					<td>Identificación operador:</td>
					<td><input id="identificadorSolicitanteH" name="identificadorSolicitanteH" type="text" readonly /></td>
					<td>Nombre operador:</td>
					<td><input name="nombreOperador" id="nombreOperador" type="text" /></td>		
				</tr>
				
				<tr>
					<td>Nombre del sitio:</td>
					<td><input name="nombreSitio" id="nombreSitio" type="text" /></td>
					<td>Provincia:</td>
					<td>
					<select id="provincia" name="provincia" style="width:172px" >
						<option value="0">Seleccione...</option>
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
				
				<td>Fecha inicio:</td>
				<td><input type="text" name="fechaInicio" id="fechaInicio" /></td>
				<td>Fecha fin:</td>
				<td><input type="text" name="fechaFin" id="fechaFin" /></td>
			</tr>
			
				<tr>
					<td colspan="3" ><button>Consultar catastro</button></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="4" id="estadoFiltro"></td>
				</tr>
			</table>
		</form>
	</nav>
</header>
<div id="tabla"></div>

<script>

$(document).ready(function(event){
	$("#listadoItems").removeClass("comunes");
	$("#listadoItems").addClass("lista");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');

	$("#fechaInicio").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate: "0"
	    });
    
	$("#fechaFin").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate: "0"
	    });
	
	var identificadorDueno= <?php echo json_encode($identificadorEmisor); ?>;
	$("#tecnicoUsuarioH").val()==1?$('#identificadorSolicitanteH').attr("readonly", false):$('#identificadorSolicitanteH').val($("#identificadorResponsableH").val()); 
	if($("#empleadoEmpresaH").val()==1){
		$('#identificadorSolicitanteH').attr("readonly", true);			
		$('#identificadorSolicitanteH').val(identificadorDueno);
	}
});

$("#nuevoFiltroCatastro").submit(function(event){
	event.preventDefault();	
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;
	
	if($("#identificadorSolicitanteH").val()=="" && $("#nombreOperador").val()=="" && $("#nombreSitio").val()=="" && $("#provincia").val()==0  ){	
		 error = true;		
		$("#identificadorSolicitanteH").addClass("alertaCombo");
		$("#nombreOperador").addClass("alertaCombo");
		$("#nombreSitio").addClass("alertaCombo");
		$("#provincia").addClass("alertaCombo");
	}
	if(error){
		$("#estadoFiltro").html("Por favor ingrese al menos un campo para realizar la búsqueda").addClass('alerta');	
	}else{  
		$("#estadoFiltro").html('');   
		abrir($(this),event,false);
	}	
});
</script>