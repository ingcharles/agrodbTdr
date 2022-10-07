<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
$conexion = new Conexion();
$cpoa1 = new ControladorPAPP();

$datos=explode( '_', $_POST['id'] );
$id_item=$datos[0];
$estado=$datos[1];

$datos = $cpoa1->obtenerDatosPOA($conexion, $id_item);
$fila = pg_fetch_assoc($datos);

$datosProvincia = $cpoa1->obtenerNombreArea($conexion, $_SESSION['usuario']);
$fila2 = pg_fetch_assoc($datosProvincia);

$itemsPresupuestarios = $cpoa1->seleccionarItemXIdPOA($conexion, $id_item);
while($fila3 = pg_fetch_assoc($itemsPresupuestarios)){
    $listadoItem[]= array(id_presupuesto=>$fila3['id_presupuesto'],codigo_item=>$fila3['codigo_item'],detalle_gasto=>$fila3['detalle_gasto'],enero=>$fila3['enero'],febrero=>$fila3['febrero'],marzo=>$fila3['marzo'],
			abril=>$fila3['abril'],mayo=>$fila3['mayo'],junio=>$fila3['junio'],julio=>$fila3['julio'],agosto=>$fila3['agosto'],septiembre=>$fila3['septiembre'],
			octubre=>$fila3['octubre'],noviembre=>$fila3['noviembre'],diciembre=>$fila3['diciembre'], id_item_planta=>$fila3['id_item_planta'], descripcion=>$fila3['descripcion']);
	
	/*$listadoItem[]= array(codigo_item=>$fila3['codigo_item'],detalle_gasto=>$fila3['detalle_gasto'],enero=>$fila3['enero'],febrero=>$fila3['febrero'],marzo=>$fila3['marzo'],
	    abril=>$fila3['abril'],mayo=>$fila3['mayo'],junio=>$fila3['junio'],julio=>$fila3['julio'],agosto=>$fila3['agosto'],septiembre=>$fila3['septiembre'],
	    octubre=>$fila3['octubre'],noviembre=>$fila3['noviembre'],diciembre=>$fila3['diciembre'], id_item_planta=>$fila3['id_item_planta'], descripcion=>$fila3['descripcion']);*/
	
}


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

	<!--  fieldset>
		<legend>Estructura / Área</legend>
		<label>< ?php echo $fila2['nombre'];?> </label>
	</fieldset-->

	<fieldset>

		<legend>Información</legend>
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
		<!-- div data-linea="4">
			<label>Objetivo operativo: </label>
			< ?php echo $fila['componente'];?>
		</div-->
		<div data-linea="5">
			<label>Actividad: </label>
			<?php echo $fila['actividad'];?>
		</div>
		<!-- div data-linea="6">
			<label>Indicador: </label>
			< ?php echo $fila['indicador'];?>
		</div>
		<div data-linea="7">
			<label>Línea Base: </label>
			< ?php echo $fila['linea_base'];?>
		</div>
		<div data-linea="8">
			<label>Método de Cálculo: </label>
			< ?php echo $fila['metodo_calculo'];?>
		</div>
		<div data-linea="9">
			<label>Meta Trimestre 1: </label>
			< ?php echo $fila['meta1'];?>
		</div>
		<div data-linea="9">
			<label>Meta Trimestre 2: </label>
			< ?php echo $fila['meta2'];?>
		</div>
		<div data-linea="10">
			<label>Meta Trimestre 3: </label>
			< ?php echo $fila['meta3'];?>
		</div>
		<div data-linea="10">
			<label>Meta Trimestre 4: </label>
			< ?php echo $fila['meta4'];?>
		</div>
		<div data-linea="11">
			<label>Meta de las actividades: </label>
			< ?php 
			$total=$fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4'];

			echo $total;?>
		</div-->
	</fieldset>
	<fieldset>
		<legend>Items presupuestarios</legend>

		<div>

			<table id="listaItemsPresupuestarios">
			
		<tr>
			<th></th>
			<th>Partida</th>
			<th>Descripción</th>
			<th>Total de Gasto</th>
		</tr>

				<tbody id="presupuestos">

					<?php
					$res1 = $cpoa1->desplegarItemsPresupuestarios($conexion, $id_item,$estado);

					while($fila4 = pg_fetch_assoc($res1)){

					echo "<tr id='".$fila4['id_presupuesto']."'><td>
                		      <form id='".$fila4['id_presupuesto']."'>
                		      <button type='submit'>Ver</button>
            		              <input name='id_presupuesto' value='".$fila4['id_presupuesto'] ."' type='hidden'>
                                  <input name='id_item_planta' value='".$fila4['id_item_planta'] ."' type='hidden'>
            			          <input name='codigo_item' value='".$fila4['codigo_item'] ."' type='hidden'>
            				    </form>
                				</td><td>".$fila4['codigo_item']."
                				</td><td>".$fila4['detalle_gasto']."
                				</td><td>".$fila4['total']."</tr>";
					
					/*echo "<tr id='".$fila4['id_item_planta']."_".$fila4['codigo_item']."'><td>
                		      <form id='".$fila4['id_item_planta']."_".$fila4['codigo_item']."'>
                		      <button type='submit'>Ver</button>
            		          <input name='id_item_planta' value='".$fila4['id_item_planta'] ."' type='hidden'>
            			     <input name='codigo_item' value='".$fila4['codigo_item'] ."' type='hidden'>
            				</form>
            				</td><td>".$fila4['codigo_item']."
            				</td><td>".$fila4['detalle_gasto']."
            				</td><td>".$fila4['total']."</tr>";*/
            		}

				?>

				</tbody>
			</table>

		</div>
	</fieldset>
	
	<fieldset>
		<legend>Detalle del item presupuestario</legend>
			<div data-linea="1">
				<label>No. Item: </label>
					<input type="text" name="itemPresupuesto" id="itemPresupuesto" readonly="readonly">
			</div>
			<div data-linea="2">
				<label>Nombre: </label>
					<input type="text" id="nombrePresupuestario" name="nombrePresupuestario" readonly="readonly" />
			</div>
	</fieldset>


	<fieldset id="mesesPresupuesto">
		<legend>Gasto mensual</legend>
		<table>
			<tr>
				<td><label>Enero:</label></td>
				<td><input class="numeric" id="enero" type="text" name="enero" readonly="readonly"
					value="0"></td>
				<td><label>Febrero:</label></td>
				<td><input class="numeric" id="febrero" type="text" name="febrero" readonly="readonly"
					value="0" /></td>
			</tr>
			<tr>
				<td><label>Marzo: </label></td>
				<td><input class="numeric" id="marzo" type="text" name="marzo" readonly="readonly"
					value="0" /></td>

				<td><label>Abril: </label></td>
				<td><input class="numeric" id="abril" type="text" name="abril" readonly="readonly"
					value="0" /></td>
			</tr>
			<tr>
				<td><label>Mayo: </label></td>
				<td><input class="numeric" id="mayo" type="text" name="mayo" readonly="readonly"
					value="0" /></td>

				<td><label>Junio: </label></td>
				<td><input class="numeric" id="junio" type="text" name="junio" readonly="readonly"
					value="0" /></td>
			</tr>
			<tr>
				<td><label>Julio: </label></td>
				<td><input class="numeric" id="julio" type="text" name="julio" readonly="readonly"
					value="0" /></td>

				<td><label>Agosto: </label></td>
				<td><input class="numeric" id="agosto" type="text" name="agosto" readonly="readonly"
					value="0" /></td>
			</tr>
			<tr>
				<td><label>Septiembre: </label></td>
				<td><input class="numeric" id="septiembre" type="text" readonly="readonly"
					name="septiembre" value="0" /></td>

				<td><label>Octubre: </label></td>
				<td><input class="numeric" id="octubre" type="text" name="octubre" readonly="readonly"
					value="0" /></td>
			</tr>
			<tr>
				<td><label>Noviembre: </label></td>
				<td><input class="numeric" id="noviembre" type="text" readonly="readonly"
					name="noviembre" value="0" /></td>

				<td><label>Diciembre: </label></td>
				<td><input class="numeric" id="diciembre" type="text" readonly="readonly"
					name="diciembre" value="0" /></td>
			</tr>
			<tr>
				<td colspan="2"><label>Total gasto: </label></td>
				<td colspan="2"><input class="numeric" id="total" type="text"
					name="total" disabled="disabled" value="0"></td>
			</tr>
			<tr>
				<td colspan="2"><label>Trimestre 1: </label></td>
				<td colspan="2"><input class="numeric" id="trimestre_1" type="text"
					name="trimestre_1" disabled="disabled" value="0"></td>
			</tr>
			<tr>
				<td colspan="2"><label>Trimestre 2: </label></td>
				<td colspan="2"><input class="numeric" id="trimestre_2" type="text"
					name="trimestre_2" disabled="disabled" value="0"></td>
			</tr>
			<tr>
				<td colspan="2"><label>Trimestre 3: </label></td>
				<td colspan="2"><input class="numeric" id="trimestre_3" type="text"
					name="trimestre_3" disabled="disabled" value="0"></td>
			</tr>

			<tr>
				<td colspan="2"><label>Trimestre 4: </label></td>
				<td colspan="2"><input class="numeric" id="trimestre_4" type="text"
					name="trimestre_4" disabled="disabled" value="0"></td>
			</tr>


		</table>

	</fieldset>



	<form id="devolverMatrizRevisar" data-rutaAplicacion="poa" data-opcion="enviarMatrizRevisar" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR"> <!-- data-accionEnExito="#ventanaAplicacion #filtrar" -->
		<input type="hidden" id="id_presupuesto" name="id_presupuesto" />
		<input type="hidden" name="id_item_planta" value="<?php echo $id_item;?>" />
		<input type="hidden" id="id_item_presupuesto" name="id_item_presupuesto"/>
		
		<fieldset id="fs_detalle">
			<legend>Observaciones</legend>
				<div data-linea="1">
					<input type="text" id="observacion" name="observacion" <?php if($estado==4) echo "disabled='disabled'"; ?> />
				</div>
		</fieldset>
		
		<button type="submit" class="guardar" <?php if($estado==4) echo "disabled='disabled'"; ?>>Devolver Item presupuestario</button>

	</form>

</body>

<script type="text/javascript">

var array_items= <?php echo json_encode($listadoItem); ?>;

$(document).ready(function(){
	distribuirLineas();	
	construirValidador();
});

$("#devolverMatrizRevisar").submit(function(event){
	event.preventDefault();
	chequearCampos(this);
});

$("#listaItemsPresupuestarios").on("submit","form",function(event){
	  
	event.preventDefault();
		
	for(var z=0;z<array_items.length;z++){
		$codigo=array_items[z]['id_presupuesto'];
		
	    if ($(this).attr('id')==$codigo){
	    	$("#enero").val(array_items[z]['enero']);
	    	$("#febrero").val(array_items[z]['febrero']);
	    	$("#marzo").val(array_items[z]['marzo']);
	    	$("#abril").val(array_items[z]['abril']);
	    	$("#mayo").val(array_items[z]['mayo']);
	    	$("#junio").val(array_items[z]['junio']);
	    	$("#julio").val(array_items[z]['julio']);
	    	$("#agosto").val(array_items[z]['agosto']);
	    	$("#septiembre").val(array_items[z]['septiembre']);
	    	$("#octubre").val(array_items[z]['octubre']);
	    	$("#noviembre").val(array_items[z]['noviembre']);
	    	$("#diciembre").val(array_items[z]['diciembre']);
	    	$("#trimestre_1").val(Number(array_items[z]['enero'])+Number(array_items[z]['febrero'])+Number(array_items[z]['marzo']));
	    	$("#trimestre_2").val(Number(array_items[z]['abril'])+Number(array_items[z]['mayo'])+Number(array_items[z]['junio']));
	    	$("#trimestre_3").val(Number(array_items[z]['julio'])+Number(array_items[z]['agosto'])+Number(array_items[z]['septiembre']));
	    	$("#trimestre_4").val(Number(array_items[z]['octubre'])+Number(array_items[z]['noviembre'])+Number(array_items[z]['diciembre']));
	    	$("#total").val(Number($("#trimestre_1").val())+Number($("#trimestre_2").val())+Number($("#trimestre_3").val())+Number($("#trimestre_4").val()));
	    	$("#id_item_presupuesto").val(array_items[z]['codigo_item']);
	    	$("#itemPresupuesto").val(array_items[z]['codigo_item']);
	    	$("#nombrePresupuestario").val(array_items[z]['descripcion']);
	    	$("#id_presupuesto").val(array_items[z]['id_presupuesto']);
   		}
	}

	/*for(var z=0;z<array_items.length;z++){
		$codigo=array_items[z]['id_item_planta']+"_"+array_items[z]['codigo_item'];
		
	    if ($(this).attr('id')==$codigo){
	    	$("#enero").val(array_items[z]['enero']);
	    	$("#febrero").val(array_items[z]['febrero']);
	    	$("#marzo").val(array_items[z]['marzo']);
	    	$("#abril").val(array_items[z]['abril']);
	    	$("#mayo").val(array_items[z]['mayo']);
	    	$("#junio").val(array_items[z]['junio']);
	    	$("#julio").val(array_items[z]['julio']);
	    	$("#agosto").val(array_items[z]['agosto']);
	    	$("#septiembre").val(array_items[z]['septiembre']);
	    	$("#octubre").val(array_items[z]['octubre']);
	    	$("#noviembre").val(array_items[z]['noviembre']);
	    	$("#diciembre").val(array_items[z]['diciembre']);
	    	$("#trimestre_1").val(Number(array_items[z]['enero'])+Number(array_items[z]['febrero'])+Number(array_items[z]['marzo']));
	    	$("#trimestre_2").val(Number(array_items[z]['abril'])+Number(array_items[z]['mayo'])+Number(array_items[z]['junio']));
	    	$("#trimestre_3").val(Number(array_items[z]['julio'])+Number(array_items[z]['agosto'])+Number(array_items[z]['septiembre']));
	    	$("#trimestre_4").val(Number(array_items[z]['octubre'])+Number(array_items[z]['noviembre'])+Number(array_items[z]['diciembre']));
	    	$("#total").val(Number($("#trimestre_1").val())+Number($("#trimestre_2").val())+Number($("#trimestre_3").val())+Number($("#trimestre_4").val()));
	    	$("#id_item_presupuesto").val(array_items[z]['codigo_item']);
	    	$("#itemPresupuesto").val(array_items[z]['codigo_item']);
	    	$("#nombrePresupuestario").val(array_items[z]['descripcion']);
   		}
	}*/	
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