<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';
require_once '../../clases/ControladorAplicaciones.php';

	
$conexion = new Conexion();	
$cvd = new ControladorVigenciaDocumentos();

$identificadorOperador= $_SESSION['usuario'];

?>

<header>
	<h1>Lista de vigencia de documentos</h1>
	<nav>
		<?php 

		$conexion = new Conexion();
		$ca = new ControladorAplicaciones();
		$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificadorOperador);
		while($fila = pg_fetch_assoc($res)){
			echo '<a href="#"
					id="' . $fila['estilo'] . '"
					data-destino="detalleItem"
					data-opcion="' . $fila['pagina'] . '"
					data-rutaAplicacion="' . $fila['ruta'] . '">'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
		}
	?>
	</nav>
</header>

<?php 
/*style="'.($fila['estado_vigencia_documento']== 'inactivo'?'background-color: #7D97EB;':'background-color: #BD588D;').'">*/
$res = $cvd->listarVigenciaDocumento($conexion);
$contador = 0;
while($fila = pg_fetch_assoc($res)){
	echo '<article
						id="'.$fila['id_vigencia_documento'].'"
						class="item"
						data-rutaAplicacion="administracionVigenciaDocumentos"
						data-opcion="abrirVigenciaDocumento"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">						
					<span class="ordinal">'.++$contador.'</span>
					<span><small>'.$fila['nombre_vigencia_documento'].'<br/></span>
					<aside>Estado: Activa <br/>'.$fila['contacto'].'</small></aside>
				</article>';
}

?>

<script type="text/javascript">	

	$(document).ready(function(event){
		$("#listadoItems").addClass("comunes");
		//$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
	});

	$("#ventanaAplicacion").on("click", "#opcionesAplicacion a", function(e) {
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
	});
	
</script>	