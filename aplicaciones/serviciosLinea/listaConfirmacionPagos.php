<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorServiciosLinea.php';

$conexion = new Conexion();
$csl = new ControladorServiciosLinea();

$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$res = $csl->obtenerRegistroConfirmacionPagoUsuario($conexion, $_POST['mesH'],$_POST['anioH'],$_SESSION['usuario'],$_POST['filtro']);
	
while($fila = pg_fetch_assoc($res)){
	$fecha=$meses[$fila['fecha_documento_mes']-1].' '.$fila['fecha_documento_anio'];
	$contenido ='<article
		    		id="'.$fila['fecha_documento_mes'].'"
		    		data-elementos="'.$fila['fecha_documento_anio'].'"
					class="item"
					data-rutaAplicacion="serviciosLinea"
					data-opcion="abrirConfirmacionPagos" 
		    		ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<span >Pagos Confirmados</span>
					<aside style="padding-left: 5px;" ><strong>Mes: </strong>'	.$fecha.'</aside>
				</article>';
?>
<script type="text/javascript">
	var contenido = <?php echo json_encode($contenido);?>;
	$("#listado div.elementos").append(contenido);
</script>
<?php	
}
?>
<header>
	<h1>Confirmación de Pagos</h1>
	<nav>
		<a id="_actualizarSubListadoItems" data-rutaaplicacion="serviciosLinea" data-opcion="listaConfirmacionPagos" data-destino="listadoItems" href="#">Actualizar</a>
		<a id="_seleccionar" data-rutaaplicacion="serviciosLinea" href="#"><div id="cantidadItemsSeleccionados">0</div>Seleccionar</a>
	</nav>
</header>
<header>
	<nav>
		<form id="nuevoFiltroConfirmacionPagosUsuario"	data-rutaAplicacion="serviciosLinea" data-opcion="listaConfirmacionPagos" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion"	value="<?php echo $_POST['opcion']; ?>" />
			<input type="hidden" name="filtro"	value="1" />
			<table class="filtro" style='width: 100%;'>
				<tbody>
					<tr>
						<td align="left">Año:</td>
						<td>
							<select name="anoH" id="anoH" style="width: 100%">
							<option value="">Seleccione...</option>
							<?php
								for($i=2017;$i<=2020;$i++)
					   				echo '<option  value="' . $i . '">'.$i. '</option>';
							?>
							</select>
						</td>
						<td align="left">Mes:</td>
						<td>
							<select name="mesH" id="mesH"	style="width: 100%">
							<option value="">Seleccione...</option>
							<?php
								$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
								for ($i=0; $i<sizeof($meses); $i++)
									echo '<option value="'.str_pad(($i+1), 2, '0', STR_PAD_LEFT).'">'. $meses[$i] . '</option>';
							?>
							</select>
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
<div id="listado">
	<div class="elementos"></div>
</div>
<script>	
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para revisarlo.</div>');								
	});
	
	$("#nuevoFiltroConfirmacionPagosUsuario").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#anoH").val())){
			error = true;
			$("#anoH").addClass("alertaCombo");
		}
		
		if(!$.trim($("#mesH").val())){
			error = true;
			$("#mesH").addClass("alertaCombo");
		}
		
		if(!error){ 
			$("#mensajeError").html('');   
			abrir($(this),event,false);
		}	else{
			$("#mensajeError").html("Por favor seleccione todos los campos").addClass('alerta');	
		}
	});
</script>