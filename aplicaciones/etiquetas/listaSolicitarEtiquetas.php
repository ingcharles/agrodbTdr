<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorEtiquetas.php';

$conexion = new Conexion();
$ce = new ControladorEtiquetas();

?>
	
<header>
	<h1>Solicitar etiquetas</h1>
	<nav>
		<form id="filtrar" data-rutaAplicacion="etiquetas" data-opcion="listaSolicitarEtiquetas" data-destino="areaTrabajo #listadoItems" >
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<table class="filtro" style="width: 400px;" >
				<tbody>
					<tr>
						<th colspan="3">Buscar Solicitud</th>
					</tr>
					
					<tr>
						<td align="left">Número de Solicitud</td>
						<td> <input id="busquedaNumeroSolicitud" type="text" name="busquedaNumeroSolicitud" maxlength="10" ></td>
					</tr>
					
					<tr>
						<td align="left">Estado Solicitud</td>
						<td>
							<select id="busquedaEstadoSolicitud" name="busquedaEstadoSolicitud" style='width:76%;'>
							<option value="" >Seleccione....</option>
							<option value="Aprobado">Aprobado</option>
							<option value="Enviado">Enviado</option>
							<option value="Por Pagar">Por Pagar</option>
							</select>
						</td>
					<tr>
						<td align="left">Fecha</td>
						<td><input id="busquedaFecha" type="text" name="busquedaFecha" maxlength="10"  maxlength="10" data-inputmask="'mask': '99/99/9999'" data-er="^(?:(?:0?[1-9]|1\d|2[0-8])(\/|-)(?:0?[1-9]|1[0-2]))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:31(\/|-)(?:0?[13578]|1[02]))|(?:(?:29|30)(\/|-)(?:0?[1,3-9]|1[0-2])))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(29(\/|-)0?2)(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|[2468][048]|[13579][26]))$"  data-inputmask="'mask': '99/99/9999'" readonly>	</td>
					</tr>
					
					<tr>
						<td colspan="5"> <button id="buscar"> Buscar </button>	</td>
					</tr>
				</tbody>
			</table> 
		</form>
	</nav>
</header>

<header>
	<nav>
		<?php 
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion,$_POST["opcion"], $_SESSION['usuario']);			
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
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th># Items</th>
			<th># Solicitud</th>
			<th>Estado</th>
			<th># Etiquetas Disponibles</th>
			</tr>
	</thead>
	<tbody>
	</tbody>
</table>
	<?php 
		$contador = 0;
		$itemsFiltrados[] = array();
		$res = $ce->listarSolicitudesEtiquetas($conexion, $_SESSION['usuario'],$_POST['busquedaNumeroSolicitud'],$_POST['busquedaEstadoSolicitud'],$_POST['busquedaFecha']);
	
		while($fila = pg_fetch_assoc($res)){
			if($fila['estado']=="Aprobado" && $fila['saldo_etiqueta']!=0 || $fila['estado']=="Por Pagar" || $fila['estado']=="Enviado"){
				if($fila['estado']=="Por Pagar")
					$claseColor='claseColor';
				else
					$claseColor='';
			
				$itemsFiltrados[] = array('<tr
					id="'.$fila['id_etiqueta'].'"
					class="item '.$claseColor.'"
					data-rutaAplicacion="etiquetas"
					data-opcion="abrirSolicitarEtiquetas"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
					<td>'.$fila['numero_solicitud'].'</td>
					<td>'.$fila['estado'].'</td>
					<td>'.$fila['saldo_etiqueta'].'</td>
					</tr>');
			}
		}
	?>
<script>
	$(document).ready(function(){
		
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		//$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un contrato para revisarlo.</div>');
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);

		$("#busquedaFecha").datepicker({
		      changeMonth: true,
		      changeYear: true,   
		});	

		colors = ['#ef3e56', '#c7c7c7' ];
		var i = 0;
		animate_loop = function() {      
		$('.claseColor').animate({backgroundColor:colors[(i++)%colors.length]
			}, 700, function(){
				animate_loop();
			});
		};
		animate_loop();	
		
	});

	$("#filtrar").submit(function(event){
		abrir($(this),event, false);
	});
</script>