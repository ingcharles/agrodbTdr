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

$res = $so->buscarGuiaMaterialPeligroso($conexion, "", $_POST['numeroGuia'], quitar_tildes($_POST['nombreGuia']));

while($fila = pg_fetch_assoc($res)){
	$itemsFiltrados[] = array('<tr
								id="'.$fila['id_guia_material_peligroso'].'"
								class="item"
								data-rutaAplicacion="seguridadOcupacional"
								data-opcion="abrirGuiaMaterialPeligroso"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="detalleItem">
								<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
								<td>'.$fila['nombre_guia_material_peligroso'].'</td>
								<td>'.$fila['numero_guia_material_peligroso'].'</td>
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
	<h1>Catálogo Guías (GRE)</h1>
	<nav>
		<form id="filtroGuiaMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" data-opcion="listaGuiaMaterialPeligroso" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<table class="filtro" style='width: 300px;'>
				<tbody>
					<tr>
						<th colspan="4">Buscar guía material peligroso:</th>
					</tr>
	
					<tr>	
						<td style='text-align: left;'>Nombre guía:</td>
						<td><input id="nombreGuia" name="nombreGuia" type="text"  maxlength="1024"/></td>
					</tr>
					
					<tr>
						<td style='text-align: left;'>Número guía:</td>
						<td><input id="numeroGuia" name="numeroGuia" type="text"  maxlength="32"/></td>		
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
			<th>Nombre</th>
			<th>Número Guía</th>					
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
	
	$("#filtroGuiaMaterialPeligroso").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#laboratorios").val()=="" ){
			error = true;	
			$("#mensajeError").html("Por favor seleccione un laboratorio").addClass('alerta');
		}
		
		if(!error){
			abrir($(this),event,false);
		}	
		
	});
</script>