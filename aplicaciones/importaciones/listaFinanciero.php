<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cu = new controladorUsuarios();
$inspectores = $cu->obtenerUsuariosPorProvincia($conexion, $_SESSION['nombreProvincia']);
?>

<header>
	<h1>Importaciones</h1>
	<nav>
	<form id="listaFinanciero" data-rutaAplicacion="importaciones" data-opcion="listaFinancieroFiltrado" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Que se encuentre en</th>

				<td>estado de pago:</td>
				<td>
					<select id="estados" name="estados">
							<option value="" >Seleccione....</option>
							<option value="pago" >Asignar monto pago</option> <!-- Usar estado pago para reemplazar el aprobado de la revision del inspector -->
							<option value="verificacion" >Verificar pago</option>
							<!-- <option value="confirmado" >Pago confirmado</option> -->
					</select>
					
					<input type="hidden" name="opcion" value= "	<?php echo $_POST["opcion"];?>">
				</td>
						
			</tr>

			<tr>
				<td colspan="5"><button>Filtrar lista</button></td>
			</tr>
		</table>
		</form>
		
	</nav>
</header>

<div id="tabla"></div>
<script>
	$("#listaFinanciero").submit(function(e){
		abrir($(this),e,false);
	});
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
	});
</script>