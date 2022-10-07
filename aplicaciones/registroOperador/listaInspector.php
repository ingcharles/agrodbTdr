<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';


$conexion = new Conexion();
$cu = new controladorUsuarios();

$area = $cu->obtenerAreaUsuario($conexion, $_SESSION['usuario']);

$inspectores = $cu->obtenerUsuariosXareaPerfil($conexion, pg_fetch_result($area, 0, 'id_area'), 'Inspector Técnico');


?>

<header>
	<h1>Operaciones</h1>
	<nav>
	<PRE><?php //print_r($_SESSION);?></PRE>
	<form id="listaInspector" data-rutaAplicacion="registroOperador" data-opcion="listaInspectorFiltrado" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Que se encuentre asignada al</th>

				<td>inspector:</td>
				<td>
					<select id="inspectores" name="inspectores">
							<option value="" >Seleccione....</option>
							<option value="" >Por asignar</option>
							<?php 
								while($fila = pg_fetch_assoc($inspectores)){
									echo '<option value="' . $fila['identificador'] . '">' . $fila['apellido'] . ', ' . $fila['nombre'] . '</option>';					
								}
							?>
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
	$("#listaInspector").submit(function(e){
		abrir($(this),e,false);
	});
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
	});
</script>
