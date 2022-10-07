<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$identificadorUsuario=$_SESSION['usuario'];
?>
<header>
	<h1>Reporte de transacciones de catastro por identificador del operador</h1>
	<nav>
		<form id="nuevoFiltroTransaccionesCatastro" data-rutaAplicacion="catastroProducto" action="aplicaciones/catastroProducto/reporteImprimirTransaccionesCatastro.php" target="_self" method="post" >
		<input type="hidden" id="opcion" name="opcion" value="">

			<table class="filtro" >
				<tr>
					<th colspan="4">Filtros para el reporte de transacciones de catastro por identificación del operador</th>
				</tr>
				<tr>
					<td align="left">Provincia:</td>
					<td colspan="3">
						<select id="provincia" name="provincia"  style="width:250px">
						<option value="0">Seleccione...</option>
						<option value="todos">Todos</option>
						<?php 
							$qProvincias = $cc->listarLocalizacion($conexion, "PROVINCIAS");
							while($fila = pg_fetch_assoc($qProvincias)){
								echo '<option value="' . $fila['id_localizacion'] . '">' . $fila['nombre'] . '</option>';
							}
						?>		
						</select>
					</td>		
				</tr>
			
				<tr>
					<td align="left">Identificación Propietario:</td>
					<td><input id="identificacionPropietario" type="text" name="identificacionPropietario" style="width:250px"></td>
				</tr>
				
				<tr>
					<td colspan="4" style='text-align:center'><button  class="guardar" >Generar Reporte</button></td>
				</tr>
				<tr>
					<td colspan="4" style='text-align:center' id="estado"></td>
				</tr>
				
			</table>
		</form>
	</nav>
</header>

<script>

	$("#nuevoFiltroTransaccionesCatastro").submit(function(event){
			
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if($("#provincia").val()==0){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}
		
		if($("#identificacionPropietario").val()==""){
			error = true;
			$("#identificacionPropietario").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Ingresar información en campos obligatorios.").addClass('alerta');		
			event.preventDefault();
		}else{ 
			ejecutarJson(form);    
		}
	});
</script>