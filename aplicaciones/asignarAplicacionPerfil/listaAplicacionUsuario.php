
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorLectorTramas.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cu = new ControladorUsuarios();
$cap = new ControladorAplicacionesPerfiles();

$usuario=$_POST['identificacionUsuarioH'];
$res=$cu->verificarUsuario($conexion, $usuario);

$contador = 0;
$itemsFiltrados[] = array();

while($fila = pg_fetch_assoc($res)){
	if($fila['estado']==1)
		$estado='activo';
	else
		$estado='inactivo';
	$itemsFiltrados[] = array('<tr
			id="'.$usuario.'"
			class="item"
			data-rutaAplicacion="asignarAplicacionPerfil"
			data-opcion="abrirAplicacionUsuario"
			ondragstart="drag(event)"
			draggable="true"
			data-destino="detalleItem">
			<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
			<td>'.$usuario.'</td>
			<td>'.$fila['nombre_usuario'].'</td>
			<td>'.$estado.'</td>
			</tr>');
}

?>
<header>
	<h1>Parametros</h1>
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
</header>
<header>
	<nav>
		<form id="listaAplicacionPerfil" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="listaAplicacionUsuario" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion"	value="<?php echo $_POST['opcion']; ?>" />
			<table class="filtro" style='width: 100%;'>
				<tbody>
					<tr>
						<th colspan="4">Consultar Usuario:</th>
					</tr>
					<tr>
						<td align="left">Identificación:</td>
						<td>
						<input type="text" id="identificacionUsuarioH" name="identificacionUsuarioH" maxlength="13"  />

						</td>
					</tr>
					<tr>
						<td colspan="4"><button>Buscar</button></td>
					</tr>
					<tr>
						<td colspan="4" style='text-align: center' id="mensajeError">	
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
			<th>Identificación</th>
			<th>Nombre Usuario</th>
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
	
	$("#listaAplicacionPerfil").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#identificacionUsuarioH").val())){
			error = true;
			$("#identificacionUsuarioH").addClass("alertaCombo");
		}
		
		if(!error){ 
			$("#mensajeError").html('');   
			abrir($(this),event,false);
		}	else{
			$("#mensajeError").html("Por favor ingrese la identificación del usuario").addClass('alerta');	
		}
	});
</script>