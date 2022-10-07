<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
$conexion = new Conexion();
$cpoa1 = new ControladorPAPP();

$id_item=$_POST['id'];

$datos = $cpoa1->obtenerDatosPOA($conexion, $id_item);
$fila = pg_fetch_assoc($datos);

$datosProvincia = $cpoa1->obtenerNombreArea($conexion, $_SESSION['usuario']);
$fila2 = pg_fetch_assoc($datosProvincia);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Revisar matriz presupuesto</h1>
	</header>

	<div id="estado"></div>

	<fieldset>

		<legend>Información</legend>
		<div data-linea="0">
			<label>Estructura / Área: </label>
			<?php echo $fila2['nombre'];?>
		</div>
		<div data-linea="1">
			<label>Objetivo: </label>
			<?php echo $fila['objetivo'];?>
		</div>
		<div data-linea="2">
			<label>Proceso: </label>
			<?php echo $fila['proceso'];?>
		</div>
		<div data-linea="3">
			<label>Subproceso: </label>
			<?php echo $fila['subproceso'];?>
		</div>
		<div data-linea="4">
			<label>Objetivo operativo: </label>
			<?php echo $fila['componente'];?>
		</div>
		<div data-linea="5">
			<label>Actividad: </label>
			<?php echo $fila['actividad'];?>
		</div>
		<div data-linea="6">
			<label>Indicador: </label>
			<?php echo $fila['indicador'];?>
		</div>
		<div data-linea="7">
			<label>Línea Base: </label>
			<?php echo $fila['linea_base'];?>
		</div>
		<div data-linea="8">
			<label>Método de Cálculo: </label>
			<?php echo $fila['metodo_calculo'];?>
		</div>
	</fieldset>
	
	<!-- SEGUIMIENTO DE METAS PLANIFICADO -->

		<fieldset>
			<legend>Programación de Metas Trimestral</legend>
			<table>
				<tr>
					<th></th>
					<th>Meta definida</th>
					<th>Avance de meta</th>
					<th>% Avance</th>
					<th class="numeroRealizados"># realizado/solicitado</th>
					<th class="numeroRealizados">% cumplimiento</th>
					<th>Observaciones</th>
				</tr>

				<?php 
				//Obtener registros de seguimiento trimestral ingresados
				$existeT1 = false;
					
				$qSeguimientoT1 = $cpoa1->listarSeguimientoXTrimestre($conexion, $_POST['id'], 1);
					
				if(pg_num_rows($qSeguimientoT1) > 0){
					$existeT1 = true;
					$seguimientoT1 = pg_fetch_assoc($qSeguimientoT1);
					$estadoT1 = $seguimientoT1['estado'];
				}
				?>

				<tr>
					<td><label>Trim I: </label>
					</td>
					<td><input type="text" id="meta1" type="text" name="meta1"
						placeholder="0" data-er="^[0-9]+$" class='trim1'
						value="<?php echo $fila['meta1'];?>" size="5" disabled="disabled">
					</td>

					<td><input class='trim1' id="avanceMeta1" type="text"
						name="avanceMeta1" data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT1 ? 'value = "' .$seguimientoT1['avance_meta']. '"' : null);?>>
					</td>

					<td><input class='trim1' type="text" id="porcentajeMeta1"
						type="text" name="porcentajeMeta1" disabled="disabled" size="5"
						<?php echo ($existeT1 ? 'value = "' .$seguimientoT1['porcentaje_avance']. '"' : null);?>>
					</td>

					<td class="numeroRealizados"><input class='trim1' type="text"
						id="numeroRealizados1" type="text" name="numeroRealizados1"
						data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT1 ? 'value = "' .$seguimientoT1['items_realizados']. '"' : null);?>>
						/
					<input class='trim1' type="text"
						id="numeroPlanificados1" type="text" name="numeroPlanificados1"
						data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT1 ? 'value = "' .$seguimientoT1['items_solicitados']. '"' : null);?>>
					</td>
					
					<td class="numeroRealizados"><input class='trim1' type="text"
						id="porcentajeRealizados1" type="text" name="porcentajeRealizados1"
						data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT1 ? 'value = "' .$seguimientoT1['porcentaje_cumplimiento']. '"' : null);?>>
					</td>

					<td><input class='trim1' type="text" id="observacionesMeta1"
						type="text" name="observacionesMeta1" data-er="^[A-Za-z0-9]+$"
						size="20" disabled="disabled"
						<?php echo ($existeT1 ? 'value = "' .$seguimientoT1['observacion_metas']. '"' : null);?>>
					</td>
				</tr>

				<?php 
				//Obtener registros de seguimiento trimestral ingresados
				$existeT2 = false;
					
				$qSeguimientoT2 = $cpoa1->listarSeguimientoXTrimestre($conexion, $_POST['id'], 2);
					
				if(pg_num_rows($qSeguimientoT2) > 0){
					$existeT2 = true;
					$seguimientoT2 = pg_fetch_assoc($qSeguimientoT2);
					$estadoT2 = $seguimientoT2['estado'];
				}
				?>

				<tr>
					<td><label>Trim II: </label>
					</td>
					<td><input class="trim2" id="meta2" type="text" name="meta2"
						placeholder="0" data-er="^[0-9]+$"
						value="<?php echo $fila['meta2'];?>" size="5" disabled="disabled" />
					</td>

					<td><input class='trim2' type="text" id="avanceMeta2" type="text"
						name="avanceMeta2" data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT2 ? 'value = "' .$seguimientoT2['avance_meta']. '"' : null);?>>
					</td>

					<td><input class='trim2' type="text" id="porcentajeMeta2"
						type="text" name="porcentajeMeta2" disabled="disabled"
						disabled="disabled" size="5"
						<?php echo ($existeT2 ? 'value = "' .$seguimientoT2['porcentaje_avance']. '"' : null);?>>
					</td>

					<td class="numeroRealizados"><input class='trim2' type="text"
						id="numeroRealizados2" type="text" name="numeroRealizados2"
						data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT2 ? 'value = "' .$seguimientoT2['items_realizados']. '"' : null);?>>
						/
					<input class='trim2' type="text"
						id="numeroPlanificados2" type="text" name="numeroPlanificados2"
						data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT2 ? 'value = "' .$seguimientoT2['items_solicitados']. '"' : null);?>>
					</td>
					
					<td class="numeroRealizados"><input class='trim2' type="text"
						id="porcentajeRealizados2" type="text" name="porcentajeRealizados2"
						data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT2 ? 'value = "' .$seguimientoT2['porcentaje_cumplimiento']. '"' : null);?>>
					</td>

					<td><input class='trim2' type="text" id="observacionesMeta2"
						type="text" name="observacionesMeta2" data-er="^[A-Za-z0-9]+$"
						size="20" disabled="disabled"
						<?php echo ($existeT2 ? 'value = "' .$seguimientoT2['observacion_metas']. '"' : null);?>>
					</td>
				</tr>

				<?php 
				//Obtener registros de seguimiento trimestral ingresados
				$existeT3 = false;
					
				$qSeguimientoT3 = $cpoa1->listarSeguimientoXTrimestre($conexion, $_POST['id'], 3);
					
				if(pg_num_rows($qSeguimientoT3) > 0){
					$existeT3 = true;
					$seguimientoT3 = pg_fetch_assoc($qSeguimientoT3);
					$estadoT3 = $seguimientoT3['estado'];
				}
				?>

				<tr>
					<td><label>Trim III: </label>
					</td>
					<td><input class="trim3" id="meta3" type="text" name="meta3"
						placeholder="0" data-er="^[0-9]+$"
						value="<?php echo $fila['meta3'];?>" size="5" disabled="disabled" />
					</td>

					<td><input class='trim3' type="text" id="avanceMeta3" type="text"
						name="avanceMeta3" data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT3 ? 'value = "' .$seguimientoT3['avance_meta']. '"' : null);?>>
					</td>

					<td><input class='trim3' type="text" id="porcentajeMeta3"
						type="text" name="porcentajeMeta3" disabled="disabled"
						disabled="disabled" size="5"
						<?php echo ($existeT3 ? 'value = "' .$seguimientoT3['porcentaje_avance']. '"' : null);?>>
					</td>

					<td class="numeroRealizados"><input class='trim3' type="text"
						id="numeroRealizados3" type="text" name="numeroRealizados3"
						data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT3 ? 'value = "' .$seguimientoT3['items_realizados']. '"' : null);?>>
						/
					<input class='trim3' type="text"
						id="numeroPlanificados3" type="text" name="numeroPlanificados3"
						data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT3 ? 'value = "' .$seguimientoT3['items_solicitados']. '"' : null);?>>
					</td>
					
					<td class="numeroRealizados"><input class='trim3' type="text"
						id="porcentajeRealizados3" type="text" name="porcentajeRealizados3"
						data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT3 ? 'value = "' .$seguimientoT3['porcentaje_cumplimiento']. '"' : null);?>>
					</td>

					<td><input class='trim3' type="text" id="observacionesMeta3"
						type="text" name="observacionesMeta3" data-er="^[A-Za-z0-9]+$"
						size="20" disabled="disabled"
						<?php echo ($existeT3 ? 'value = "' .$seguimientoT3['observacion_metas']. '"' : null);?>>
					</td>
				</tr>

				<?php 
				//Obtener registros de seguimiento trimestral ingresados
				$existeT4 = false;
					
				$qSeguimientoT4 = $cpoa1->listarSeguimientoXTrimestre($conexion, $_POST['id'], 4);
					
				if(pg_num_rows($qSeguimientoT4) > 0){
					$existeT4 = true;
					$seguimientoT4 = pg_fetch_assoc($qSeguimientoT4);
					$estadoT4 = $seguimientoT4['estado'];
				}
				?>

				<tr>
					<td><label>Trim IV: </label>
					</td>
					<td><input class="trim4" id="meta4" type="text" name="meta4"
						placeholder="0" data-er="^[0-9]+$"
						value="<?php echo $fila['meta4'];?>" size="5" disabled="disabled" />
					</td>

					<td><input class='trim4' type="text" id="avanceMeta4" type="text"
						name="avanceMeta4" data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT4 ? 'value = "' .$seguimientoT4['avance_meta']. '"' : null);?>>
					</td>

					<td><input class='trim4' type="text" id="porcentajeMeta4"
						type="text" name="porcentajeMeta4" disabled="disabled"
						disabled="disabled" size="5"
						<?php echo ($existeT4 ? 'value = "' .$seguimientoT4['porcentaje_avance']. '"' : null);?>>
					</td>

					<td class="numeroRealizados"><input class='trim4' type="text"
						id="numeroRealizados4" type="text" name="numeroRealizados4"
						data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT4 ? 'value = "' .$seguimientoT4['items_realizados']. '"' : null);?>>
						/
					<input class='trim4' type="text"
						id="numeroPlanificados4" type="text" name="numeroPlanificados4"
						data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT4 ? 'value = "' .$seguimientoT4['items_solicitados']. '"' : null);?>>
					</td>
					
					<td class="numeroRealizados"><input class='trim4' type="text"
						id="porcentajeRealizados4" type="text" name="porcentajeRealizados4"
						data-er="^[0-9]+$" size="5" disabled="disabled"
						<?php echo ($existeT4 ? 'value = "' .$seguimientoT4['porcentaje_cumplimiento']. '"' : null);?>>
					</td>

					<td><input class='trim4' type="text" id="observacionesMeta4"
						type="text" name="observacionesMeta4" data-er="^[A-Za-z0-9]+$"
						size="20" disabled="disabled"
						<?php echo ($existeT4 ? 'value = "' .$seguimientoT4['observacion_metas']. '"' : null);?>>
					</td>
				</tr>
				
				<tr>
					<td><label>Meta de los Proyectos: </label>
					</td>
					<td><input class="numeric" id="total" type="text" name="total"
						disabled="disabled"
						value="<?php echo $fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4'];?>"
						size="5">
					</td>
					<td><input class="numeric" id="totalAvance" type="text"
						name="totalAvance" disabled="disabled"
						value="<?php echo $seguimientoT1['avance_meta']+$seguimientoT2['avance_meta']+$seguimientoT3['avance_meta']+$seguimientoT4['avance_meta'];?>"
						size="5">
					</td>
					<td><input class="numeric" id="porcentajeAvance" type="text"
						name="porcentajeAvance" disabled="disabled" size="5"
						value="<?php echo number_format(((($seguimientoT1['avance_meta']+$seguimientoT2['avance_meta']+$seguimientoT3['avance_meta']+$seguimientoT4['avance_meta'])*100)/($fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4'])),  2, '.', '');?>"
						>
					</td>					
					<td class="numeroRealizados"><input class="numeric" id="totalNumeroRealizados" type="text"
						name="totalNumeroRealizados" disabled="disabled" size="5"
						value="<?php echo $seguimientoT1['items_realizados']+$seguimientoT2['items_realizados']+$seguimientoT3['items_realizados']+$seguimientoT4['items_realizados'];?>">
						/
						<input class="numeric" id="totalNumeroPlanificados" type="text"
						name="totalNumeroPlanificados" disabled="disabled" size="5"
						value="<?php echo $seguimientoT1['items_solicitados']+$seguimientoT2['items_solicitados']+$seguimientoT3['items_solicitados']+$seguimientoT4['items_solicitados'];?>">
					</td>
					<td class="numeroRealizados"><input class="numeric" id="totalPorcentajeRealizados" type="text"
						name="totalPorcentajeRealizados" disabled="disabled" size="5" readonly="readonly"
						value="<?php echo number_format(((($seguimientoT1['items_realizados']+$seguimientoT2['items_realizados']+$seguimientoT3['items_realizados']+$seguimientoT4['items_realizados'])*100)/($seguimientoT1['items_solicitados']+$seguimientoT2['items_solicitados']+$seguimientoT3['items_solicitados']+$seguimientoT4['items_solicitados'])), 2, '.', '');?>">
					</td>
				</tr>
			</table>

		</fieldset>

	<form id="devolverSeguimientoRevisar" data-rutaAplicacion="poa" data-opcion="enviarSeguimientoRevisar" data-destino="detalleItem" data-accionEnExito="#ventanaAplicacion #filtrar"> <!-- data-accionEnExito="#ventanaAplicacion #filtrar" -->
		<input type="hidden" name="id_item_planta" value="<?php echo $id_item;?>" />
		<input type="hidden" id="trimestre" name="trimestre"/>
		
		<fieldset id="fs_detalle">
			<legend>Observaciones revisión trimestre</legend>
				<div data-linea="1">
					<input type="text" id="observacion" name="observacion" <?php if($estado==4) echo "disabled='disabled'"; ?> />
				</div>
		</fieldset>
		
		<button type="submit" class="guardar" <?php if($estado==4) echo "disabled='disabled'"; ?>>Devolver Seguimiento Trimestral</button>

	</form>

</body>

<script type="text/javascript">

var array_items= <?php echo json_encode($listadoItem); ?>;
var tipoIndicador= <?php echo json_encode($fila['tipo']); ?>;

$(document).ready(function(){
	distribuirLineas();	
	construirValidador();

	//Muestra el campo para ingreso de número de items realizados en caso de que el indicador sea porcentaje
	if(tipoIndicador == 'Porcentaje'){
		$(".numeroRealizados").show();
	}else{
		$(".numeroRealizados").hide();
		$(".numeroRealizados").attr("disabled", "disabled");
	}

	var d = new Date();
	var mesActual = d.getMonth()+1;
	
	//Habilita los campos de seguimiento por trimestres
	if(Number(mesActual) > 0 && Number(mesActual) < 4){
		$("#trimestre").val(1);
	}else if(Number(mesActual) > 3 && Number(mesActual) < 7){
		$("#trimestre").val(2);
	}else if(Number(mesActual) > 6 && Number(mesActual) < 10){
		$("#trimestre").val(3);
	}else{
		$("#trimestre").val(4);
	}
});

$("#devolverSeguimientoRevisar").submit(function(event){
	event.preventDefault();
	chequearCampos(this);
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

function chequearCampos(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
		error = true;
		$("#observacion").addClass("alertaCombo");
	}
	
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson(form);
		$("#_actualizar").click();			
	}
}
</script>