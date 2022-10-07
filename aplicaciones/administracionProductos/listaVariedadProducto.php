<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorAplicaciones.php';
	
$conexion = new Conexion();	
$cr = new ControladorRequisitos();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<header>
	<h1>Lista Tipos de Operaciones</h1>
	<nav>
		<?php			
			$contador = 0;
			$itemsFiltrados[] = array();
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

<div>
		<div class="elementos"></div>
</div>
<?php 
	$res = $cr->listaOperacionesConVariedades($conexion);
	while($fila = pg_fetch_assoc($res)){
	echo'<article
		 id="'.$fila['id_tipo_operacion'].'"
				class="item"
				data-rutaAplicacion="administracionProductos"
				data-opcion="abrirListaVariedadProducto"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem"
				data-idOpcion ="'.$fila['nombre'].'">
				<span class="ordinal">'.++$contador.'</span>
				<span>'.$fila['nombre'].'<br/></span>
				<aside></aside>
				</article>';
				
       
   }
 ?>
 </body>
<script>
$(document).ready(function(){
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
});
</script>