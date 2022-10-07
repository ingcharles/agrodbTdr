<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();
$cc = new ControladorCapacitacion();

$identificador = $_SESSION['usuario'];
$estadoInicio = 1;
$estadoFin = 20;

?>

<header>
	<h1>Administración capacitación</h1>
	<nav>
		<?php			
		$ca = new ControladorAplicaciones();
		$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificador);
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

<div id="D6">
	<h2>Solicitudes ingresadas</h2>
	<div class="elementos"></div>
</div>

<div id="D7">
	<h2>Solicitudes modificadas</h2>
	<div class="elementos"></div>
</div>

<div id="D11">
	<h2>Solicitudes aprobadas por el director</h2>
	<div class="elementos"></div>
</div>

<div id="D0">
	<h2>Solicitudes rechazadas por el director</h2>
	<div class="elementos"></div>
</div>

<div id="D12">
	<h2>Solicitudes aprobadas por talento humano</h2>
	<div class="elementos"></div>
</div>

<div id="D1">
	<h2>Solicitudes rechazadas por talento humano</h2>
	<div class="elementos"></div>
</div>

<div id="D8">
	<h2>Solicitudes subsanadas por talento humano</h2>
	<div class="elementos"></div>
</div>

<div id="D13">
	<h2>Solicitudes con certificación financiera</h2>
	<div class="elementos"></div>
</div>

<div id="D14">
	<h2>Solicitudes con informe favorable</h2>
	<div class="elementos"></div>
</div>

<div id="D15">
	<h2>Solicitudes con replicación asignada</h2>
	<div class="elementos"></div>
</div>

<div id="D16">
	<h2>Solicitudes con replicación calificada</h2>
	<div class="elementos"></div>
</div>

<div id="D17">
	<h2>Solicitudes para entrega de formato replica</h2>
	<div class="elementos"></div>
</div>

<div id="D19">
	<h2>Solicitudes para generar procedimiento/manual</h2>
	<div class="elementos"></div>
</div>

<div id="D20">
	<h2>Solicitudes con procedimiento/manual</h2>
	<div class="elementos"></div>
</div>
<?php 

$res = $cc->obtenerRequerimientos($conexion, null, null, null, null, $identificador, null, $estadoInicio, $estadoFin);

	while($fila = pg_fetch_assoc($res)){
	$categoria='';
		switch ($fila['estado_requerimiento']){
			case 0: $pagina="abrirRequerimiento"; $categoria = 'D0'; break;			
			case 1: $pagina="abrirRequerimiento"; $categoria = 'D1'; break;			
			case 6: $pagina="abrirRequerimiento"; $categoria = 'D6'; break;			
			case 7: $pagina="abrirRequerimiento"; $categoria = 'D7'; break;			
			case 8: $pagina="abrirRequerimiento"; $categoria = 'D8'; break;
			case 11: $pagina="abrirRequerimiento"; $categoria = 'D11'; break;
			case 12: $pagina="abrirRequerimiento"; $categoria = 'D12'; break;
			case 13: $pagina="certificarDireccionFinanciera"; $categoria = 'D13'; break;
			case 14: $pagina="abrirInforme"; $categoria = 'D14'; break;
			case 15: $pagina="revisionAsignarReplicantes"; $categoria = 'D15'; break;
			case 16: $pagina="verCalificacionReplicaTodos"; $categoria = 'D16'; break;
			case 17: $pagina="abrirRequerimiento"; $categoria = 'D17'; break;
			case 19: $pagina="abrirRequerimiento"; $categoria = 'D19'; break;
			case 20: $pagina="abrirRequerimiento"; $categoria = 'D20'; break;
		}
		if($categoria!=''){
		$contenido = '<article
							id="'.$fila['id_requerimiento'].'"
							class="item"
							data-rutaAplicacion="capacitacion"
							data-opcion="'.$pagina.'"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span><small>'.(strlen($fila['nombre_evento'])>50?(substr($fila['nombre_evento'],0,50).'...'):(strlen($fila['nombre_evento'])>0?$fila['nombre_evento']:'')).'</small></br></span>							
							<span></span>
							<aside><small> Desde: '.$fila['fecha_inicio'].'<br/> Hasta: '.$fila['fecha_fin'].'</small></aside>
							</article>';
					
		?>
					<script type="text/javascript">
						var contenido = <?php echo json_encode($contenido);?>;
						var categoria = <?php echo json_encode($categoria);?>;
						$("#"+categoria+" div.elementos").append(contenido);
					</script>
		<?php	
			}
		}
		?>

<script>	
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una solicitud para revisarla.</div>');

		$("#D0 div> article").length == 0 ? $("#D0").remove():"";
		$("#D1 div> article").length == 0 ? $("#D1").remove():"";
		$("#D6 div> article").length == 0 ? $("#D6").remove():"";
		$("#D7 div> article").length == 0 ? $("#D7").remove():"";
		$("#D8 div> article").length == 0 ? $("#D8").remove():"";
		$("#D11 div> article").length == 0 ? $("#D11").remove():"";
		$("#D12 div> article").length == 0 ? $("#D12").remove():"";
		$("#D13 div> article").length == 0 ? $("#D13").remove():"";
		$("#D14 div> article").length == 0 ? $("#D14").remove():"";		
		$("#D15 div> article").length == 0 ? $("#D15").remove():"";	
		$("#D16 div> article").length == 0 ? $("#D16").remove():"";	
		$("#D17 div> article").length == 0 ? $("#D17").remove():"";	
		$("#D19 div> article").length == 0 ? $("#D19").remove():"";
		$("#D20 div> article").length == 0 ? $("#D20").remove():"";						
	});
</script>