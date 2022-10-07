<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguridadOcupacional.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();	
$so = new ControladorSeguridadOcupacional();

$contador = 0;
$itemsFiltrados[] = array();

function quitar_tildes($cadena) {
	$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹"," ");
	$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","");
	$texto = str_replace($no_permitidas, $permitidas ,$cadena);
	return $texto;
}

$res=$so->buscarLaboratorioMaterialPeligroso($conexion, "",quitar_tildes($_POST['nombreLaboratorio']));

while($fila = pg_fetch_assoc($res)){
	$itemsFiltrados[] = array('<tr
								id="'.$fila['id_laboratorio'].'"
								class="item"
								data-rutaAplicacion="seguridadOcupacional"
								data-opcion="abrirLaboratorioMaterialPeligroso"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="detalleItem">
								<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
								<td>'.$fila['nombre_laboratorio'].'</td>
							</tr>');
}

?>
<header>
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
	<h1>Catálogo Laboratorios</h1>
	
	<nav>
		<form id="filtroLaboratorioMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" data-opcion="listaLaboratorioMaterialPeligroso" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<table class="filtro" style='width: 350px;'>
				<tbody>
					<tr>
						<th colspan="4">Buscar coordinación laboratorio:</th>
					</tr>
					
					<tr>	
						<td style='text-align: left;'>Coordinación laboratorio:</td>
						<td><input id="nombreLaboratorio" name="nombreLaboratorio" type="text"  maxlength="256"/></td>
					</tr>
				
					<tr>
						<td colspan="4" style='text-align:center'><button>Filtrar</button></td>	
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
			<th>Coordinación Laboratorio</th>					
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		//$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);				
	});

	$("#filtroLaboratorioMaterialPeligroso").submit(function(event){
		//event.preventDefault();	
		abrir($(this),event,false);
	});
	
</script>