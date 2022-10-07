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
	<h1>Lista Proceso de Conciliación</h1>
	
	<nav>
		<form id="listaProcesoConciliacion" data-rutaAplicacion="conciliacionBancaria"
			data-opcion="listaProcesoConciliacion"
			data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />

			<table class="filtro">
				<tbody>
					<tr>
						<th>Tipo de proceso:</th>
						<td colspan="6">
						<select id="bTipoProcesoConciliacion" name="bTipoProcesoConciliacion" style ="width: 100%;">
							<option value="" >Seleccione...</option>				
							<option value="interno">Servicion internos</option>
							<option value="comercioExterior">Comercio exterior</option>		
						</select>
						</td>	
						
					</tr>
					<tr>
						<th>Año:</th>
						<td>
						<select id="bAnio" name="bAnio" style ="width: 100%;" >
							<option value="">Seleccione...</option>
							<option value="2017">2017</option>
							<option value="2018">2018</option>
							<option value="2019">2019</option>
							<option value="2020">2020</option>
							<option value="2021">2021</option>
							<option value="2022">2022</option>
							<option value="2023">2023</option>
							<option value="2024">2024</option>
							<option value="2025">2025</option>
						</select>
						</td>
						<th>Mes:</th>
						<td>
						<select id="bMes" name="bMes" style ="width: 100%;" >
							<option value="">Seleccione...</option>
							<option value="1">Enero</option>
							<option value="2">Febrero</option>
							<option value="3">Marzo</option>
							<option value="4">Abril</option>
							<option value="5">Mayo</option>
							<option value="6">Junio</option>
							<option value="7">Julio</option>
							<option value="8">Agosto</option>
							<option value="9">Septiembre</option>
							<option value="10">Octubre</option>
							<option value="11">Noviembre</option>
							<option value="12">Diciembre</option>
						</select>
						</td>
						<th>Día:</th>
						<td>
						<select id="bDia" name="bDia" style ="width: 100%;" >
							<option value ="">Seleccione...</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							<option value="31">31</option>
						</select>
						</td>
					</tr>
					<tr>
						<td id="mensajeError"></td>
						<td colspan="6">
							<button id="buscar">Buscar</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>

	</nav>
		
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


<div class="elementos"></div>

	<?php  
		
	
	if(isset($_POST['bTipoProcesoConciliacion']) || isset($_POST['bAnio']) || isset($_POST['bMes']) || isset($_POST['bDia'])){

	    $res = $cb->listadoProcesoConciliacion($conexion, $_POST['bTipoProcesoConciliacion'], $_POST['bAnio'], $_POST['bMes'], $_POST['bDia']);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
				
			$contenido = '<article
						id="'.$fila['id_proceso_conciliacion'].'"
						class="item"
						data-rutaAplicacion="conciliacionBancaria"
						data-opcion="abrirProcesoConciliacion"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.(strlen($fila['nombre_registro_proceso_conciliacion'])>45?(substr($fila['nombre_registro_proceso_conciliacion'],0,45).'...'):(strlen($fila['nombre_registro_proceso_conciliacion'])>0?$fila['nombre_registro_proceso_conciliacion']:'Sin asunto')).'</span>
					<aside><small><span>Proceso Concilación '.$fila['fecha_conciliacion'].'</span></small></aside>
				</article>';
			?>
				<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					$("div.elementos").append(contenido);
				</script>
				<?php					
		}


	}
	
		
		
		?>
</body>		
<script>
	$(document).ready(function(){
		
		$("#listadoItems").addClass("comunes");
		
		if($("#estado").html()=="El registro de proceso de conciliación se ha eliminado satisfactoriamente" || $("#estado").html()==""){
			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');
		}
		//$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');		
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

	$("#listaProcesoConciliacion").submit(function(event){
        
   		event.preventDefault();
    	$(".alertaCombo").removeClass("alertaCombo");
  		var error = false;  		
  		
		if($("#bTipoProcesoConciliacion").val()==""){	
			error = true;		
			$("#bTipoProcesoConciliacion").addClass("alertaCombo");
		}

		if($("#bAnio").val()==""){	
			error = true;		
			$("#bAnio").addClass("alertaCombo");
		}

		if($("#bAnio").val()==""){	
			error = true;		
			$("#bAnio").addClass("alertaCombo");
		}

		if($("#bMes").val()==""){	
			error = true;		
			$("#bMes").addClass("alertaCombo");
		}

		if($("#bDia").val()==""){	
			error = true;		
			$("#bDia").addClass("alertaCombo");
		}

		if (!error){
			event.preventDefault();
			abrir($('#listaProcesoConciliacion'),event, false);                          
		}
	});
</script>
</html>