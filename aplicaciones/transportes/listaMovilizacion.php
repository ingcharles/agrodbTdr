<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorVehiculos.php';
?>

<header>
		<h1>Movilización</h1>
		<nav>
		<?php 

			$conexion = new Conexion();
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			//data-rutaAplicacion="' . $fila['ruta'] .'"
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

	<?php 
		$cv = new ControladorVehiculos();
		$res = $cv->listarMovilizacion($conexion,'ABIERTOS', $_SESSION['nombreLocalizacion']);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			echo '<article 
						id="'.$fila['id_movilizacion'].'"
						class="item"
						data-rutaAplicacion="transportes"
						data-opcion="abrirMovilizacion" 
						ondragstart="drag(event)"  
						draggable="true" 
						data-destino="detalleItem"
						style="'. (($fila['estado'])==1?'background-color: #7D97EB;':(($fila['estado'])==2?'background-color: #BD588D;':'background-color: #ABCDA6;')).'">
					<span class="ordinal">'.++$contador.'</span>
					<span><small><b>'.$fila['id_movilizacion'].'</b><br/></span>
					<span>'.$fila['tipo_movilizacion'].'<br/></span>
					<span>'.(strlen($fila['descripcion'])>15?(substr($fila['descripcion'],0,15).'...'):(strlen($fila['descripcion'])>0?$fila['descripcion']:'Sin descripción')).'<br/></span>
					<span>'.$fila['placa'].'<br/></span>
					<aside>'.date('j/n/Y (G:i)',strtotime($fila['fecha_solicitud'])).'<br/>'.(($fila['estado'])==1?'Asignar vehículo':(($fila['estado'])==2?'Por imprimir':'Por finalizar')).'</small></aside>
				</article>';
		
		}
	?>
	

<script>
$(document).ready(function(){
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una movilización para revisarla.</div>');
});


$("#_eliminar").click(function(){
	if($("#cantidadItemsSeleccionados").text()>1){
			alert('Por favor seleccione una orden de movilización a la vez');
			return false;
		}
	});
</script>