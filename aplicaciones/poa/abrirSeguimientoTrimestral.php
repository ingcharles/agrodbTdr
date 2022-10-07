<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
$conexion = new Conexion();
$cpoa1 = new ControladorPAPP();

$datosProceso = $cpoa1->obtenerDatosPOA($conexion, $_POST['id']);
$fila = pg_fetch_assoc($datosProceso);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Seguimiento Trimestral PAPP</h1>
	</header>
	<form id="guardarSeguimientos" data-rutaAplicacion="poa"
		data-opcion="guardarSeguimientoTrimestral" data-destino="detalleItem"
		data-accionEnExito="ACTUALIZAR">
		<div id="estado"></div>
		<input type="hidden" name="idItem" value="<?php echo $_POST['id'];?>" />
		<input type="hidden" id="trimestre" name="trimestre" />

		<fieldset>

			<legend>Objetivo Estratégico</legend>
			<div data-linea="1">
				<?php echo $fila['objetivo'];?>
			</div>
		</fieldset>

		<fieldset>
			<legend>Procesos/Proyectos</legend>
			<div data-linea="1">
				<?php echo $fila['proceso'];?>
			</div>
		</fieldset>

		<fieldset>
			<legend>Subprocesos</legend>
			<div data-linea="1">
				<?php echo $fila['subproceso'];?>
			</div>
		</fieldset>

		<fieldset>
			<legend>Objetivo operativo</legend>
			<div data-linea="1">
				<?php echo $fila['componente'];?>
			</div>
		</fieldset>

		<fieldset>
			<legend>Proyectos y Actividades</legend>
			<div data-linea="1">
				<?php echo $fila['actividad'];?>
			</div>
			<div data-linea="2">
				<?php echo $fila['detalle_actividad'];?>
			</div>
		</fieldset>

		<fieldset>
			<legend>Indicadores</legend>
			<div data-linea="1">
				<?php echo $fila['indicador'];?>
			</div>
			<div data-linea="2">
				<label>Línea Base: </label>
				<?php echo $fila['linea_base'];?>
			</div>
			<div data-linea="3">
				<label>Método de Cálculo: </label>
				<?php echo $fila['metodo_calculo'];?>
			</div>
			<div data-linea="4">
				<label>Tipo de Indicador: </label>
				<?php echo $fila['tipo'];?>
			</div>
			<div>
				<p class="nota">Por favor ingrese el número de elementos realizados si el indicador es de tipo Porcentaje.</p>
			</div>
		</fieldset>

		<!-- SEGUIMIENTO DE METAS PLANIFICADO -->

		<fieldset>
			<legend>Programación de Metas Trimestral</legend>
			<table>
				<tr>
					<th></th>
					<th>Meta definida</th>
					<th class="numeroRealizados"># realizado/solicitado</th>
					<th class="numeroRealizados">% cumplimiento</th>
					<th>Avance de meta</th>
					<th>% Avance</th>
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
					<td><label>Trimestre I: </label>
					</td>
					<td><input type="text" id="meta1" type="text" name="meta1"
						placeholder="0" data-er="^[0-9]+$" class='trim1'
						value="<?php echo $fila['meta1'];?>" size="5" readonly="readonly">
					</td>

					<td class="numeroRealizados"><input class='trim1' type="text"
						id="numeroRealizados1" type="text" name="numeroRealizados1"
						data-er="^[0-9]+$" size="5"
						<?php echo ($existeT1 ? 'value = "' .$seguimientoT1['items_realizados']. '"' : null);?>>
						/
					<input class='trim1' type="text"
						id="numeroPlanificados1" type="text" name="numeroPlanificados1"
						data-er="^[0-9]+$" size="5"
						<?php echo ($existeT1 ? 'value = "' .$seguimientoT1['items_solicitados']. '"' : null);?>>
					</td>
					
					<td class="numeroRealizados"><input class='trim1' type="text"
						id="porcentajeRealizados1" type="text" name="porcentajeRealizados1"
						data-er="^[0-9]+$" size="5" readonly="readonly"
						<?php echo ($existeT1 ? 'value = "' .$seguimientoT1['porcentaje_cumplimiento']. '"' : null);?>>
					</td>

					<td><input class='trim1' id="avanceMeta1" type="text"
						name="avanceMeta1" data-er="^[0-9]+$" size="5"
						<?php echo ($existeT1 ? 'value = "' .$seguimientoT1['avance_meta']. '"' : null);?>>
					</td>

					<td><input class='trim1' type="text" id="porcentajeMeta1"
						type="text" name="porcentajeMeta1" readonly="readonly"
						disabled="disabled" size="5"
						<?php echo ($existeT1 ? 'value = "' .$seguimientoT1['porcentaje_avance']. '"' : null);?>>
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
					<td><label>Trimestre II: </label>
					</td>
					<td><input class="trim2" id="meta2" type="text" name="meta2"
						placeholder="0" data-er="^[0-9]+$"
						value="<?php echo $fila['meta2'];?>" size="5" readonly="readonly" />
					</td>

					<td class="numeroRealizados"><input class='trim2' type="text"
						id="numeroRealizados2" type="text" name="numeroRealizados2"
						data-er="^[0-9]+$" size="5"
						<?php echo ($existeT2 ? 'value = "' .$seguimientoT2['items_realizados']. '"' : null);?>>
						/
					<input class='trim2' type="text"
						id="numeroPlanificados2" type="text" name="numeroPlanificados2"
						data-er="^[0-9]+$" size="5"
						<?php echo ($existeT2 ? 'value = "' .$seguimientoT2['items_solicitados']. '"' : null);?>>
					</td>
					
					<td class="numeroRealizados"><input class='trim2' type="text"
						id="porcentajeRealizados2" type="text" name="porcentajeRealizados2"
						data-er="^[0-9]+$" size="5" readonly="readonly"
						<?php echo ($existeT2 ? 'value = "' .$seguimientoT2['porcentaje_cumplimiento']. '"' : null);?>>
					</td>

					<td><input class='trim2' type="text" id="avanceMeta2" type="text"
						name="avanceMeta2" data-er="^[0-9]+$" size="5"
						<?php echo ($existeT2 ? 'value = "' .$seguimientoT2['avance_meta']. '"' : null);?>>
					</td>

					<td><input class='trim2' type="text" id="porcentajeMeta2"
						type="text" name="porcentajeMeta2" readonly="readonly"
						disabled="disabled" size="5"
						<?php echo ($existeT2 ? 'value = "' .$seguimientoT2['porcentaje_avance']. '"' : null);?>>
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
					<td><label>Trimestre III: </label>
					</td>
					<td><input class="trim3" id="meta3" type="text" name="meta3"
						placeholder="0" data-er="^[0-9]+$"
						value="<?php echo $fila['meta3'];?>" size="5" readonly="readonly" />
					</td>

					<td class="numeroRealizados"><input class='trim3' type="text"
						id="numeroRealizados3" type="text" name="numeroRealizados3"
						data-er="^[0-9]+$" size="5"
						<?php echo ($existeT3 ? 'value = "' .$seguimientoT3['items_realizados']. '"' : null);?>>
						/
					<input class='trim3' type="text"
						id="numeroPlanificados3" type="text" name="numeroPlanificados3"
						data-er="^[0-9]+$" size="5"
						<?php echo ($existeT3 ? 'value = "' .$seguimientoT3['items_solicitados']. '"' : null);?>>
					</td>
					
					<td class="numeroRealizados"><input class='trim3' type="text"
						id="porcentajeRealizados3" type="text" name="porcentajeRealizados3"
						data-er="^[0-9]+$" size="5" readonly="readonly"
						<?php echo ($existeT3 ? 'value = "' .$seguimientoT3['porcentaje_cumplimiento']. '"' : null);?>>
					</td>

					<td><input class='trim3' type="text" id="avanceMeta3" type="text"
						name="avanceMeta3" data-er="^[0-9]+$" size="5"
						<?php echo ($existeT3 ? 'value = "' .$seguimientoT3['avance_meta']. '"' : null);?>>
					</td>

					<td><input class='trim3' type="text" id="porcentajeMeta3"
						type="text" name="porcentajeMeta3" readonly="readonly"
						disabled="disabled" size="5"
						<?php echo ($existeT3 ? 'value = "' .$seguimientoT3['porcentaje_avance']. '"' : null);?>>
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
					
					if($seguimientoT4['observaciones']!=''){
						$observacionesT4 = 1;
					}else{
						$observacionesT4 = 0;
					}
				}
				?>

				<tr>
					<td><label>Trimestre IV: </label>
					</td>
					<td><input class="trim4" id="meta4" type="text" name="meta4"
						placeholder="0" data-er="^[0-9]+$"
						value="<?php echo $fila['meta4'];?>" size="5" readonly="readonly" />
					</td>

					<td class="numeroRealizados"><input class='trim4' type="text"
						id="numeroRealizados4" type="text" name="numeroRealizados4"
						data-er="^[0-9]+$" size="5"
						<?php echo ($existeT4 ? 'value = "' .$seguimientoT4['items_realizados']. '"' : null);?>>
						/
					<input class='trim4' type="text"
						id="numeroPlanificados4" type="text" name="numeroPlanificados4"
						data-er="^[0-9]+$" size="5"
						<?php echo ($existeT4 ? 'value = "' .$seguimientoT4['items_solicitados']. '"' : null);?>>
					</td>
					
					<td class="numeroRealizados"><input class='trim4' type="text"
						id="porcentajeRealizados4" type="text" name="porcentajeRealizados4"
						data-er="^[0-9]+$" size="5" readonly="readonly"
						<?php echo ($existeT4 ? 'value = "' .$seguimientoT4['porcentaje_cumplimiento']. '"' : null);?>>
					</td>
					
					<td><input class='trim4' type="text" id="avanceMeta4" type="text"
						name="avanceMeta4" data-er="^[0-9]+$" size="5"
						<?php echo ($existeT4 ? 'value = "' .$seguimientoT4['avance_meta']. '"' : null);?>>
					</td>

					<td><input class='trim4' type="text" id="porcentajeMeta4"
						type="text" name="porcentajeMeta4" readonly="readonly"
						disabled="disabled" size="5"
						<?php echo ($existeT4 ? 'value = "' .$seguimientoT4['porcentaje_avance']. '"' : null);?>>
					</td>

				</tr>
				
				<tr>
					<td><label>Meta de los Proyectos: </label></td>
					
					<td><input class="numeric" id="total" type="text" name="total"
						disabled="disabled"
						value="<?php echo $fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4'];?>"
						size="5">
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
					
				</tr>
			</table>

		</fieldset>
		
		<fieldset>
			<legend>Oservaciones trimestrales</legend>

			<table>
				<tr>
					<td><label>Trimestre I: </label>
					</td>
					
					<td><input class='trim1' type="text" id="observacionesMeta1"
						type="text" name="observacionesMeta1" data-er="^[A-Za-z0-9]+$" size = "55%"
						<?php echo ($existeT1 ? 'value = "' .$seguimientoT1['observacion_metas']. '"' : null);?>>
					</td>
				</tr>
				
				<tr>
					<td><label>Trimestre II: </label>
					</td>
					<td><input class='trim2' type="text" id="observacionesMeta2"
						type="text" name="observacionesMeta2" data-er="^[A-Za-z0-9]+$"  size = "55%"
						<?php echo ($existeT2 ? 'value = "' .$seguimientoT2['observacion_metas']. '"' : null);?>>
					</td>
				</tr>
				
				<tr>
					<td><label>Trimestre III: </label>
					</td>
					
					<td><input class='trim3' type="text" id="observacionesMeta3"
						type="text" name="observacionesMeta3" data-er="^[A-Za-z0-9]+$"  size = "55%"
						<?php echo ($existeT3 ? 'value = "' .$seguimientoT3['observacion_metas']. '"' : null);?>>
					</td>
				</tr>
				
				<tr>
					<td><label>Trimestre IV: </label>
					</td>
					
					<td><input class='trim4' type="text" id="observacionesMeta4"
						type="text" name="observacionesMeta4" data-er="^[A-Za-z0-9]+$" size = "55%"
						<?php echo ($existeT4 ? 'value = "' .$seguimientoT4['observacion_metas']. '"' : null);?>>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset id="detalleObservaciones">
			<legend>Observaciones realizadas en revisión trimestre</legend>
				<div data-linea="1">
					<input type="text" id="observacion" name="observacion" disabled='disabled' />
				</div>
		</fieldset>

		<button type="submit" class="guardar" id="botonGuardar" disabled="disabled">Guardar</button>

	</form>
</body>


<script type="text/javascript">
var linea_base= <?php echo json_encode($fila['linea_base']); ?>;
var tipoIndicador= <?php echo json_encode($fila['tipo']); ?>;

var existeT1= <?php echo json_encode($existeT1); ?>;
var existeT2= <?php echo json_encode($existeT2); ?>;
var existeT3= <?php echo json_encode($existeT3); ?>;
var existeT4= <?php echo json_encode($existeT4); ?>;

var existePresupuesto= <?php echo json_encode($presupuesto); ?>;

//Detalle de estados para control de actualización
var estadoT1= <?php echo json_encode($estadoT1); ?>;
var estadoT2= <?php echo json_encode($estadoT2); ?>;
var estadoT3= <?php echo json_encode($estadoT3); ?>;
var estadoT4= <?php echo json_encode($estadoT4); ?>;

//Mensajes de revision
var observacionT1= <?php echo json_encode($observacionesT1); ?>;
var observacionT2= <?php echo json_encode($observacionesT2); ?>;
var observacionT3= <?php echo json_encode($observacionesT3); ?>;
var observacionT4= <?php echo json_encode($observacionesT4); ?>;

var observacionMT1= <?php echo json_encode($seguimientoT1['observaciones']); ?>;
var observacionMT2= <?php echo json_encode($seguimientoT2['observaciones']); ?>;
var observacionMT3= <?php echo json_encode($seguimientoT3['observaciones']); ?>;
var observacionMT4= <?php echo json_encode($seguimientoT4['observaciones']); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();

		$("#detalleObservaciones").hide();
		
		$(".trim1").attr("disabled", "disabled");
		$(".trim2").attr("disabled", "disabled");
		$(".trim3").attr("disabled", "disabled");
		$(".trim4").attr("disabled", "disabled");

		var d = new Date();
		var mesActual = d.getMonth()+1;
		var diaActual = d.getDate('dd');		

		/*mesActual=1;
		diaActual=11;*/
		
		//Habilita los campos de seguimiento por trimestres con hasta 10 días de prórroga para ingreso de datos
		if(Number(mesActual) == Number(1) && Number(diaActual) < Number(11)){

			if(existeT4 == false || estadoT4 == 1){
				$(".trim4").removeAttr("disabled", "disabled");
				$("#botonGuardar").removeAttr("disabled", "disabled");
				$("#trimestre").val(4);
			}
			
		}else if(Number(mesActual) > Number(0) && Number(mesActual) < Number(5)){
			if(Number(mesActual) == Number(4) && Number(diaActual) > Number(10)){

				if(existeT2 == false || estadoT2 == 1){
					$(".trim2").removeAttr("disabled", "disabled");
					$("#botonGuardar").removeAttr("disabled", "disabled");
					$("#trimestre").val(2);
				}
			}else{

				if(existeT1 == false || estadoT1 == 1){
					$(".trim1").removeAttr("disabled", "disabled");
					$("#botonGuardar").removeAttr("disabled", "disabled");
					$("#trimestre").val(1);
				}
			}
		}else if(Number(mesActual) > Number(3) && Number(mesActual) < Number(8)){
			if(Number(mesActual) == Number(7) && Number(diaActual) > Number(10)){

				if(existeT3 == false || estadoT3 == 1){
					$(".trim3").removeAttr("disabled", "disabled");
					$("#botonGuardar").removeAttr("disabled", "disabled");
					$("#trimestre").val(3);
				}
			}else{

				if(existeT2 == false || estadoT2 == 1){
					$(".trim2").removeAttr("disabled", "disabled");
					$("#botonGuardar").removeAttr("disabled", "disabled");
					$("#trimestre").val(2);
				}
			}
		}else if(Number(mesActual) > Number(7) && Number(mesActual) < Number(11)){
			if(Number(mesActual) == Number(10) && Number(diaActual) > Number(10)){

				if(existeT4 == false || estadoT4 == 1){
					$(".trim4").removeAttr("disabled", "disabled");
					$("#botonGuardar").removeAttr("disabled", "disabled");
					$("#trimestre").val(4);
				}
			}else{

				if(existeT3 == false || estadoT3 == 1){
					$(".trim3").removeAttr("disabled", "disabled");
					$("#botonGuardar").removeAttr("disabled", "disabled");
					$("#trimestre").val(3);
				}
			}
		}else if(Number(mesActual) > Number(9)){

				if(existeT4 == false || estadoT4 == 1){
					$(".trim4").removeAttr("disabled", "disabled");
					$("#botonGuardar").removeAttr("disabled", "disabled");
					$("#trimestre").val(4);
				}
		}

		//--------------------------------------------------------------

		//Mostrar mensaje de observación para corrección
		if(existeT1 == true && estadoT1 == 1 && observacionT1 == 1){
			$("#detalleObservaciones").show();
			$("#observacion").val(observacionMT1);
		}

		if(existeT2 == true && estadoT2 == 1 && observacionT2 == 1){
			$("#detalleObservaciones").show();
			$("#observacion").val(observacionMT2);
		}

		if(existeT3 == true && estadoT3 == 1 && observacionT3 == 1){
			$("#detalleObservaciones").show();
			$("#observacion").val(observacionMT3);
		}
		
		if(existeT4 == true && estadoT4 == 1 && observacionT4 == 1){
			$("#detalleObservaciones").show();
			$("#observacion").val(observacionMT4);
		}
		
		//Muestra el campo para ingreso de número de items realizados en caso de que el indicador sea porcentaje
		if(tipoIndicador == 'Porcentaje'){
			$(".numeroRealizados").show();
			$("#avanceMeta1").attr("readonly", "readonly");
			$("#avanceMeta2").attr("readonly", "readonly");
			$("#avanceMeta3").attr("readonly", "readonly");
			$("#avanceMeta4").attr("readonly", "readonly");
		}else{
			$(".numeroRealizados").hide();
			$(".numeroRealizados").attr("disabled", "disabled");
		}
		
		var acum=0;
		var acumAvance=0;
		var acumNumeroRealizados=0;
		var acumNumeroPlanificados=0;

		  $("input").blur(function(){
		    $(this).css("background-color","#ffffff");
		    acum=Number($("#meta1").val())+Number($("#meta2").val())+Number($("#meta3").val())+Number($("#meta4").val());

		    $("#total").val(acum);

		    acumAvance=Number($("#avanceMeta1").val())+Number($("#avanceMeta2").val())+Number($("#avanceMeta3").val())+Number($("#avanceMeta4").val());

			var porcentaje = (Number(acumAvance)*100)/(Number(acum));
			
		    if( 100 == Number(porcentaje)){//verde
		    	$("#totalAvance").css("background-color","#30C951");
		    }else if( 30 > Number(porcentaje)){//rojo
		    	$("#totalAvance").css("background-color","#C4313D");
		    }else if( Number(porcentaje) > 30){//amarillo
		    	$("#totalAvance").css("background-color","#F2EF38");
		    }

		    $("#totalAvance").val(acumAvance);

		    var porcentaje = (Number($("#totalAvance").val())*100)/(Number($("#total").val()));
			$("#porcentajeAvance").val(porcentaje.toFixed(2));

			acumNumeroRealizados = Number($("#numeroRealizados1").val())+Number($("#numeroRealizados2").val())+Number($("#numeroRealizados3").val())+Number($("#numeroRealizados4").val());
			$("#totalNumeroRealizados").val(acumNumeroRealizados);

			acumNumeroPlanificados = Number($("#numeroPlanificados1").val())+Number($("#numeroPlanificados2").val())+Number($("#numeroPlanificados3").val())+Number($("#numeroPlanificados4").val());
			$("#totalNumeroPlanificados").val(acumNumeroPlanificados);

			if(Number($("#totalNumeroRealizados").val()) && Number($("#totalNumeroPlanificados").val())){

				if(Number($("#totalNumeroRealizados").val()) > Number($("#totalNumeroPlanificados").val())){
					$("#totalPorcentajeRealizados").val('100');
				}else{
					if(Number($("#totalNumeroRealizados").val()) == Number($("#totalNumeroPlanificados").val()) && Number($("#totalNumeroPlanificados").val()) == 0){
						$("#totalPorcentajeRealizados").val('0');
					}else{
						var porcentajeRealizados = (Number($("#totalNumeroRealizados").val())*100)/(Number($("#totalNumeroPlanificados").val()));
						$("#totalPorcentajeRealizados").val(porcentajeRealizados.toFixed(2));
					}
				}
			}

			//Porcentaje realizados trimestre 1
			var porcentajeRealizados1 = 0;
			
			if(Number($("#numeroRealizados1").val()) && Number($("#numeroPlanificados1").val())){
				if((Number($("#numeroRealizados1").val()) != 0) && (Number($("#numeroPlanificados1").val()) != 0)){
					if(Number($("#numeroRealizados1").val()) > Number($("#numeroPlanificados1").val())){
						$("#porcentajeRealizados1").val('100');
						porcentajeRealizados1 = 100;
	
						var avanceMeta1 = (Number($("#meta1").val())*porcentajeRealizados1/100);
						$("#avanceMeta1").val(avanceMeta1.toFixed(2));
	
						var porcentajeMeta1 = avanceMeta1/Number($("#meta1").val())*100;
						$("#porcentajeMeta1").val(porcentajeMeta1.toFixed(2));
					}else{
						porcentajeRealizados1 = (Number($("#numeroRealizados1").val())*100)/(Number($("#numeroPlanificados1").val()));
						$("#porcentajeRealizados1").val(porcentajeRealizados1.toFixed(2));

						var avanceMeta1 = (Number($("#meta1").val())*porcentajeRealizados1/100);
						$("#avanceMeta1").val(avanceMeta1.toFixed(2));

						var porcentajeMeta1 = avanceMeta1/Number($("#meta1").val())*100;
						$("#porcentajeMeta1").val(porcentajeMeta1.toFixed(2));
					}
				}
			}

			//Porcentaje realizados trimestre 2
			var porcentajeRealizados2 = 0;
			
			if(Number($("#numeroRealizados2").val()) && Number($("#numeroPlanificados2").val())){
				if((Number($("#numeroRealizados2").val()) != 0) && (Number($("#numeroPlanificados2").val()) != 0)){
					if(Number($("#numeroRealizados2").val()) > Number($("#numeroPlanificados2").val())){
						$("#porcentajeRealizados2").val('100');
						porcentajeRealizados2 = 100;
	
						var avanceMeta2 = (Number($("#meta2").val())*porcentajeRealizados2/100);
						$("#avanceMeta2").val(avanceMeta2.toFixed(2));
	
						var porcentajeMeta2 = avanceMeta2/Number($("#meta2").val())*100;
						$("#porcentajeMeta2").val(porcentajeMeta2.toFixed(2));
					}else{
						porcentajeRealizados2 = (Number($("#numeroRealizados2").val())*100)/(Number($("#numeroPlanificados2").val()));
						$("#porcentajeRealizados2").val(porcentajeRealizados2.toFixed(2));

						var avanceMeta2 = (Number($("#meta2").val())*porcentajeRealizados2/100);
						$("#avanceMeta2").val(avanceMeta2.toFixed(2));

						var porcentajeMeta2 = avanceMeta2/Number($("#meta2").val())*100;
						$("#porcentajeMeta2").val(porcentajeMeta2.toFixed(2));
					}
				}
			}

			//Porcentaje realizados trimestre 3
			var porcentajeRealizados3 = 0;
			
			if(Number($("#numeroRealizados3").val()) && Number($("#numeroPlanificados3").val())){
				if((Number($("#numeroRealizados3").val()) != 0) && (Number($("#numeroPlanificados3").val()) != 0)){
					if(Number($("#numeroRealizados3").val()) > Number($("#numeroPlanificados3").val())){
						$("#porcentajeRealizados3").val('100');
						porcentajeRealizados3 = 100;
	
						var avanceMeta3 = (Number($("#meta3").val())*porcentajeRealizados3/100);
						$("#avanceMeta3").val(avanceMeta3.toFixed(2));
	
						var porcentajeMeta3 = avanceMeta3/Number($("#meta3").val())*100;
						$("#porcentajeMeta3").val(porcentajeMeta3.toFixed(2));
					}else{
						porcentajeRealizados3 = (Number($("#numeroRealizados3").val())*100)/(Number($("#numeroPlanificados3").val()));
						$("#porcentajeRealizados3").val(porcentajeRealizados3.toFixed(2));

						var avanceMeta3 = (Number($("#meta3").val())*porcentajeRealizados3/100);
						$("#avanceMeta3").val(avanceMeta3.toFixed(2));

						var porcentajeMeta3 = avanceMeta3/Number($("#meta3").val())*100;
						$("#porcentajeMeta3").val(porcentajeMeta3.toFixed(2));
					}
				}
			}
			/*if(Number($("#numeroRealizados3").val()) && Number($("#numeroPlanificados3").val())){
				if(Number($("#numeroRealizados3").val()) > Number($("#numeroPlanificados3").val())){
					$("#porcentajeRealizados3").val('100');
				}else{
					if(Number($("#numeroRealizados3").val()) == Number($("#numeroPlanificados3").val()) && Number($("#numeroPlanificados3").val()) == 0){
						$("#porcentajeRealizados3").val('0');
					}else{
						var porcentajeRealizados3 = (Number($("#numeroRealizados3").val())*100)/(Number($("#numeroPlanificados3").val()));
						$("#porcentajeRealizados3").val(porcentajeRealizados3.toFixed(2));
					}
				}
			}*/

			//Porcentaje realizados trimestre 4
			var porcentajeRealizados4 = 0;
			
			if(Number($("#numeroRealizados4").val()) && Number($("#numeroPlanificados4").val())){
				if((Number($("#numeroRealizados4").val()) != 0) && (Number($("#numeroPlanificados4").val()) != 0)){
					if(Number($("#numeroRealizados4").val()) > Number($("#numeroPlanificados4").val())){
						$("#porcentajeRealizados4").val('100');
						porcentajeRealizados4 = 100;
	
						var avanceMeta4 = (Number($("#meta4").val())*porcentajeRealizados4/100);
						$("#avanceMeta4").val(avanceMeta4.toFixed(2));
	
						var porcentajeMeta4 = avanceMeta4/Number($("#meta4").val())*100;
						$("#porcentajeMeta4").val(porcentajeMeta4.toFixed(2));
					}else{
						porcentajeRealizados4 = (Number($("#numeroRealizados4").val())*100)/(Number($("#numeroPlanificados4").val()));
						$("#porcentajeRealizados4").val(porcentajeRealizados4.toFixed(2));

						var avanceMeta4 = (Number($("#meta4").val())*porcentajeRealizados4/100);
						$("#avanceMeta4").val(avanceMeta4.toFixed(2));

						var porcentajeMeta4 = avanceMeta4/Number($("#meta4").val())*100;
						$("#porcentajeMeta4").val(porcentajeMeta4.toFixed(2));
					}
				}
			}
		  });
	});


	//Cálculo de avance de meta realizada en indicador numero
	$("#avanceMeta1").change(function(event){
		if($.isNumeric($("#avanceMeta1").val())){
			if(Number($("#avanceMeta1").val()) > Number($("#meta1").val())){
				$("#porcentajeMeta1").val('100');
			}else{
				if(Number($("#avanceMeta1").val()) == Number($("#meta1").val()) && Number($("#meta1").val()) == 0){
					$("#porcentajeMeta1").val('0');
				}else{
					$("#porcentajeMeta1").val(($("#avanceMeta1").val()/$("#meta1").val())*100);
				}
				$("#avanceMeta1").removeClass("alertaCombo");
				$("#estado").html("").removeClass('alerta');
			}
		}else{
			$("#avanceMeta1").addClass("alertaCombo");
			$("#estado").html("Solo puede ingresar números.").addClass('alerta');
		}
	});

	$("#avanceMeta2").change(function(event){
		if($.isNumeric($("#avanceMeta2").val())){
			if(Number($("#avanceMeta2").val()) > Number($("#meta2").val())){
				$("#porcentajeMeta2").val('100');
			}else{
				if(Number($("#avanceMeta2").val()) == Number($("#meta2").val()) && Number($("#meta2").val()) == 0){
					$("#porcentajeMeta2").val('0');
				}else{
					$("#porcentajeMeta2").val(($("#avanceMeta2").val()/$("#meta2").val())*100 );
				}
				$("#avanceMeta2").removeClass("alertaCombo");
				$("#estado").html("").removeClass('alerta');
			}
		}else{
			$("#avanceMeta2").addClass("alertaCombo");
			$("#estado").html("Solo puede ingresar números.").addClass('alerta');
		}
	});

	$("#avanceMeta3").change(function(event){
		if($.isNumeric($("#avanceMeta3").val())){
			if(Number($("#avanceMeta3").val()) > Number($("#meta3").val())){
				$("#porcentajeMeta3").val('100');
			}else{
				if(Number($("#avanceMeta3").val()) == Number($("#meta3").val()) && Number($("#meta3").val()) == 0){
					$("#porcentajeMeta3").val('0');
				}else{
					$("#porcentajeMeta3").val(($("#avanceMeta3").val()/$("#meta3").val())*100 );
				}
				$("#avanceMeta3").removeClass("alertaCombo");
				$("#estado").html("").removeClass('alerta');
			}
		}else{
			$("#avanceMeta3").addClass("alertaCombo");
			$("#estado").html("Solo puede ingresar números.").addClass('alerta');
		}
	});

	$("#avanceMeta4").change(function(event){
		if($.isNumeric($("#avanceMeta4").val())){
			if(Number($("#avanceMeta4").val()) > Number($("#meta4").val())){
				$("#porcentajeMeta4").val('100');
			}else{
				if(Number($("#avanceMeta4").val()) == Number($("#meta4").val()) && Number($("#meta4").val()) == 0){
					$("#porcentajeMeta4").val('0');
				}else{
					$("#porcentajeMeta4").val(($("#avanceMeta4").val()/$("#meta4").val())*100 );
				}
				$("#avanceMeta4").removeClass("alertaCombo");
				$("#estado").html("").removeClass('alerta');
			}
		}else{
			$("#avanceMeta4").addClass("alertaCombo");
			$("#estado").html("Solo puede ingresar números.").addClass('alerta');
		}
	});


	$("#guardarSeguimientos").submit(function(event){
		event.preventDefault();
		ejecutarJson(this);
	});
</script>