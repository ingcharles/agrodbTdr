<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
	<h1>Lista Administración de Trampas</h1>
	<nav>
		<?php			
		$contador = 0;
		//$itemsFiltrados[] = array();
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


<div class="elementos"></div>

	<?php  
		
		$res = $cb->listadoTramas($conexion);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			
			$contenido = '<article 
						id="'.$fila['id_trama'].'"
						class="item"
						data-rutaAplicacion="conciliacionBancaria"
						data-opcion="abrirRegistroTrama" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span>Trama</span></br>
					<span>'.(strlen($fila['nombre_trama'])>45?(substr($fila['nombre_trama'],0,45).'...'):(strlen($fila['nombre_trama'])>0?$fila['nombre_trama']:'Sin asunto')).'</span>
					<aside></aside>			
				</article>';
			?>
				<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					$("div.elementos").append(contenido);
				</script>
				<?php					
		}
		
		?>
</body>		
<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		//alert ($("#estado").html());
		if($("#estado").html()=="La trama se ha eliminado satisfactoriamente" || $("#estado").html()==""){
			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>')
		}				
	});


	$("#_eliminar").click(function(event){
		//$("#mensajeError").html("");
		if($("#cantidadItemsSeleccionados").text()>1){	
			//$("#mensajeError").html("Por favor seleccione un registro de catastro a la vez.").addClass('alerta');
				return false;
			}
		if($("#cantidadItemsSeleccionados").text()==0){
			//$("#mensajeError").html("Por favor seleccione un registro de catastro a eliminar.").addClass('alerta');
			return false;
		}
	});
  
</script>
</html>