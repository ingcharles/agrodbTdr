<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorServiciosLinea.php';

$conexion = new Conexion();
$csl = new ControladorServiciosLinea();

$contador = 0;
$itemsFiltrados[] = array();

$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
if($_POST['formatoH']=='individual' || $_POST['formatoH']=='')
	$res = $csl->obtenerRegistroConfirmacionPagoIndividual($conexion, $_POST['localizacionH'],$_POST['fechaInicioH'],$_POST['fechaFinH'],$_POST['filtro']);

if($_POST['formatoH']=='consolidado')
	$res = $csl->obtenerRegistroConfirmacionPagoConsolidado($conexion, $_POST['localizacionH'],$_POST['mesH'],$_POST['anioH']);

$eliminar='notificarGFConfirmacionPagosI';
$campoLista='Fecha';
while($fila = pg_fetch_assoc($res)){
	if($_POST['formatoH']=='individual' || $_POST['formatoH']==''){
		$fecha=$fila['fecha_documento_dia'].'-'.$meses[$fila['fecha_documento_mes']-1].'-'.$fila['fecha_documento_anio'];
		$id=$fila['id_confirmacion_pago'];
		$datoOpcion=$fecha;
		$eliminar='notificarGFConfirmacionPagosI';
		$campoLista='Fecha';
	}
	
	if($_POST['formatoH']=='consolidado'){
		$fecha=$meses[$fila['fecha_documento_mes']-1].' '.$fila['fecha_documento_anio'];
		$id=$fila['fecha_documento_mes'].'-'.$fila['fecha_documento_anio'];
		$datoOpcion=$fila['localizacion'];
		$eliminar='notificarGFConfirmacionPagosC';
		$campoLista='Mes';
	}
	
	$itemsFiltrados[] = array('<tr
			id="'.$id.'"
			data-idOpcion="'.$datoOpcion.'"
			data-elementos="'.$_POST['formatoH'].'"
			class="item"
			data-rutaAplicacion="serviciosLinea"
			data-opcion="abrirGFConfirmacionPagos"
			ondragstart="drag(event)"
			draggable="true"
			data-destino="detalleItem">
			<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
			<td>'.$fila['localizacion'].'</td>
			<td>'.$fecha.'</td>
			</tr>');
}
?>
 <header>
	<h1>Confirmación de Pagos Administración</h1>
	<nav>
		<a id="_nuevo" data-rutaaplicacion="serviciosLinea" data-opcion="nuevoGFConfirmacionPagos" data-destino="detalleItem" href="#">Nuevo</a>
		<a id="_actualizarSubListadoItems" data-rutaaplicacion="serviciosLinea" data-opcion="listaGFConfirmacionPagos" data-destino="listadoItems" href="#">Actualizar</a>
		<a id="_seleccionar" data-rutaaplicacion="serviciosLinea" href="#"><div id="cantidadItemsSeleccionados">0</div>Seleccionar</a>
		<a id="_eliminar" data-rutaaplicacion="serviciosLinea" data-opcion="<?php echo $eliminar ;?>" data-destino="detalleItem" href="#">Eliminar</a>				
	</nav>
</header>
<header>
	<nav>
		<form id="nuevoFiltroConfirmacionPagos"	data-rutaAplicacion="serviciosLinea" data-opcion="listaGFConfirmacionPagos"	data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion"	value="<?php echo $_POST['opcion']; ?>" />
			<input type="hidden" name="filtro"	value="1" />
			
			<table class="filtro" style='width: 100%;'>
				<tbody>
					<tr>
						<td align="left">Localización:</td>
						<td colspan="3">
							<select name="localizacionH" id="localizacionH"	style="width: 100%">
								<option value="">Seleccione...</option>
								<?php
								$area = array('Oficina Planta Central','Zona 1','Zona 2','Zona 3','Zona 4','Zona 5','Zona 6','Zona 7');
								for ($i=0; $i<sizeof($area); $i++)
									echo '<option value="'.$area[$i].'">'. $area[$i] . '</option>';
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="left">Formato:</td>
						<td colspan="3">
							<select name="formatoH" id="formatoH" style="width: 100%">
								<option value="">Seleccione...</option>
								<option value="consolidado">Consolidado</option>
								<option value="individual">Individual</option>
							</select>
						</td>
					</tr>
					<tr id="vistaConsolidado">
						<td align="left">Fecha Inicio:</td>
						<td><input type="text" id="fechaInicioH" name="fechaInicioH" readonly="readonly" style='width: 98%;'/></td>
						<td align="left">Fecha Fin:</td>
						<td><input type="text"  id="fechaFinH" name="fechaFinH" readonly="readonly" style='width: 98%;' /></td>
					</tr>
					<tr id="vistaIndividual">
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
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Localización</th>
			<th><?php echo $campoLista;?></th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
		$("#vistaIndividual").hide();
		$("#vistaConsolidado").hide();
	});

	$("#_eliminar").click(function(event){
		var datoOpcion=<?php echo json_encode($datoOpcion);?>;
		$("#_eliminar").attr('data-idOpcion',datoOpcion);
		$("#mensajeError").html("");
		if($("#cantidadItemsSeleccionados").text()>1){	
			$("#mensajeError").html("Por favor seleccione un registro a la vez").addClass('alerta');
				return false;
		}
		if($("#cantidadItemsSeleccionados").text()==0){
			$("#mensajeError").html("Por favor seleccione un registro").addClass('alerta');
			return false;
		}
	});
	
	$("#fechaInicioH").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});

	$("#fechaFinH").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});

	$("#formatoH").change(function(event){
	if($("#formatoH").val()=='consolidado'){
		$("#vistaIndividual").show();
		$("#vistaConsolidado").hide();
	}else if($("#formatoH").val()=='individual') {
		$("#vistaIndividual").hide();
		$("#vistaConsolidado").show();
	}else{
		$("#vistaIndividual").hide();
		$("#vistaConsolidado").hide();
	}
	});
	
	$("#nuevoFiltroConfirmacionPagos").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#localizacionH").val())){
			error = true;
			$("#localizacionH").addClass("alertaCombo");
		}
		
		if(!$.trim($("#formatoH").val())){
			error = true;
			$("#formatoH").addClass("alertaCombo");
		}

		if($("#formatoH").val()=='consolidado'){
			if(!$.trim($("#anoH").val())){
				error = true;
				$("#anoH").addClass("alertaCombo");
			}
			if(!$.trim($("#mesH").val())){
				error = true;
				$("#mesH").addClass("alertaCombo");
			}
		}
			
		if($("#formatoH").val()=='individual'){
			if(!$.trim($("#fechaInicioH").val())){
				error = true;
				$("#fechaInicioH").addClass("alertaCombo");
			}
			if(!$.trim($("#fechaFinH").val())){
				error = true;
				$("#fechaFinH").addClass("alertaCombo");
			}
		}
		
		if(!error){ 
			$("#mensajeError").html('');   
			abrir($(this),event,false);
		}	else{
			$("#mensajeError").html("Por favor seleccione todos los campos").addClass('alerta');	
		}
	});
</script>