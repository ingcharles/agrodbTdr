<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
$conexion = new Conexion();
$cpoa1 = new ControladorPAPP();

$datos = $cpoa1->obtenerDatosPOA($conexion, $_POST['id']);
$fila = pg_fetch_assoc($datos);

$datosProvincia = $cpoa1->obtenerNombreArea($conexion, $_SESSION['usuario']);
$fila2 = pg_fetch_assoc($datosProvincia);


$itemsPresupuestarios = $cpoa1->seleccionarItemXIdPOA($conexion, $_POST['id']);
while($fila3 = pg_fetch_assoc($itemsPresupuestarios)){
    $listadoItem[]= array(id_presupuesto=>$fila3['id_presupuesto'],codigo_item=>$fila3['codigo_item'],detalle_gasto=>$fila3['detalle_gasto'],enero=>$fila3['enero'],febrero=>$fila3['febrero'],marzo=>$fila3['marzo'],
			abril=>$fila3['abril'],mayo=>$fila3['mayo'],junio=>$fila3['junio'],julio=>$fila3['julio'],agosto=>$fila3['agosto'],septiembre=>$fila3['septiembre'],
			octubre=>$fila3['octubre'],noviembre=>$fila3['noviembre'],diciembre=>$fila3['diciembre'], id_item_planta=>$fila3['id_item_planta']);
	
	/*$listadoItem[]= array(codigo_item=>$fila3['codigo_item'],detalle_gasto=>$fila3['detalle_gasto'],enero=>$fila3['enero'],febrero=>$fila3['febrero'],marzo=>$fila3['marzo'],
	    abril=>$fila3['abril'],mayo=>$fila3['mayo'],junio=>$fila3['junio'],julio=>$fila3['julio'],agosto=>$fila3['agosto'],septiembre=>$fila3['septiembre'],
	    octubre=>$fila3['octubre'],noviembre=>$fila3['noviembre'],diciembre=>$fila3['diciembre'], id_item_planta=>$fila3['id_item_planta']);*/

}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Registro de Items Presupuestarios</h1>
	</header>

	<div id="estado"></div>


	<fieldset>
		<legend>Área:</legend>
		<label><?php echo $fila2['nombre'];?> </label>

	</fieldset>



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
			<label>Meta de las actividades: </label>
			< ?php 
			$total=$fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4'];

			echo $total;?>
		</div-->
	</fieldset>

	<form id="nuevoItemPOA" data-rutaAplicacion="poa"
		data-opcion="guardarNuevoPresupuesto" data-destino="detalleItem"
		data-accionEnExito="#ventanaAplicacion #filtrar">
		<input type="hidden" id="idItem" name="idItem" value="<?php echo $_POST['id'];?>" />
		<input type="hidden" id="id" name="id" value="<?php echo $_POST['id'];?>" />
		
		<fieldset>
			<legend>Ítem presupuestario:</legend>
				<p class="nota">El detalle del gasto no puede ser el mismo nombre del ítem presupuestario, debe detallar el uso que tendrá.</p>
				
				<div data-linea="1">		
				<label>Nombre del ítem presupuestario: </label>
	
				<select id="itemsPresupusetarios" name="itemsPresupusetarios">
					<option value="">Seleccione....</option>
					<?php 
						$itemPresupuestario = $cpoa1->listarItemPresupuestarioActivo($conexion);
						
						while($fila3 = pg_fetch_assoc($itemPresupuestario)){
			              echo '<option value="' . $fila3['codigo'] . '">' . $fila3['codigo'] .' - '. $fila3['descripcion'] .'</option>';
	
						}                               	
					?>
				</select>
				
			</div>
			
			<div data-linea="2">
				<label>Detalle del gasto: </label>	
				<input type="text" id="detalle_gasto" name="detalle_gasto" />
			</div>

		</fieldset>

		<fieldset id="mesesPresupuesto">
			<legend>Gasto mensual</legend>
			<table>
				<tr>
					<td><label>Enero:</label></td>
					<td><input class="numeric" id="enero" type="text" name="enero" placeholder="0" data-er="^[0-9]+(\.[0-9]{1,2})?$"></td>
					<td><label>Febrero:</label></td>
					<td><input class="numeric" id="febrero" type="text" name="febrero" placeholder="0" data-er="^[0-9]+(\.[0-9]{1,2})?$"/></td>
				</tr>
				<tr>
					<td><label>Marzo: </label></td>
					<td><input class="numeric" id="marzo" type="text" name="marzo" placeholder="0" data-er="^[0-9]+(\.[0-9]{1,2})?$" /></td>

					<td><label>Abril: </label></td>
					<td><input class="numeric" id="abril" type="text" name="abril" placeholder="0" data-er="^[0-9]+(\.[0-9]{1,2})?$" /></td>
				</tr>
				<tr>
					<td><label>Mayo: </label></td>
					<td><input class="numeric" id="mayo" type="text" name="mayo" placeholder="0" data-er="^[0-9]+(\.[0-9]{1,2})?$" /></td>

					<td><label>Junio: </label></td>
					<td><input class="numeric" id="junio" type="text" name="junio" placeholder="0" data-er="^[0-9]+(\.[0-9]{1,2})?$" /></td>
				</tr>
				<tr>
					<td><label>Julio: </label></td>
					<td><input class="numeric" id="julio" type="text" name="julio" placeholder="0" data-er="^[0-9]+(\.[0-9]{1,2})?$" /></td>

					<td><label>Agosto: </label></td>
					<td><input class="numeric" id="agosto" type="text" name="agosto" placeholder="0" data-er="^[0-9]+(\.[0-9]{1,2})?$" /></td>
				</tr>
				<tr>
					<td><label>Septiembre: </label></td>
					<td><input class="numeric" id="septiembre" type="text" name="septiembre" placeholder="0" data-er="^[0-9]+(\.[0-9]{1,2})?$" /></td>

					<td><label>Octubre: </label></td>
					<td><input class="numeric" id="octubre" type="text" name="octubre" placeholder="0" data-er="^[0-9]+(\.[0-9]{1,2})?$" /></td>
				</tr>
				<tr>
					<td><label>Noviembre: </label></td>
					<td><input class="numeric" id="noviembre" type="text" name="noviembre" placeholder="0" data-er="^[0-9]+(\.[0-9]{1,2})?$" /></td>

					<td><label>Diciembre: </label></td>
					<td><input class="numeric" id="diciembre" type="text" name="diciembre" placeholder="0" data-er="^[0-9]+(\.[0-9]{1,2})?$" /></td>
				</tr>
				<tr>
					<td colspan="2"><label>Total gasto: </label></td>
					<td colspan="2"><input class="numeric" id="total" type="text" name="total" disabled="disabled" value="0"></td>
				</tr>
				<tr>
					<td colspan="2"><label>Trimestre 1: </label></td>
					<td colspan="2"><input class="numeric" id="trimestre_1" type="text" name="trimestre_1" disabled="disabled"	value="0"></td>
				</tr>
				<tr>
					<td colspan="2"><label>Trimestre 2: </label></td>
					<td colspan="2"><input class="numeric" id="trimestre_2" type="text" name="trimestre_2" disabled="disabled" value="0"></td>
				</tr>
				<tr>
					<td colspan="2"><label>Trimestre 3: </label></td>
					<td colspan="2"><input class="numeric" id="trimestre_3" type="text" name="trimestre_3" disabled="disabled" value="0"></td>
				</tr>

				<tr>
					<td colspan="2"><label>Trimestre 4: </label></td>
					<td colspan="2"><input class="numeric" id="trimestre_4" type="text" name="trimestre_4" disabled="disabled"	value="0"></td>
				</tr>

			</table>

		</fieldset>
		<button type="submit" class="guardar">Agregar</button>

	</form>

	<fieldset>
		<legend>Items presupuestarios</legend>

		<div>

			<table id="listaItemsPresupuestarios">
			<tr>
				<th colspan="2"></th>
				<th>
					Partida
				</th>
				<th>
					Descripción
				</th>
				<th>
					Total de Gasto
				</th>
			</tr>

				<tbody id="presupuestos">

					<?php
						$cpoa2 = new ControladorPAPP();
						$res1 = $cpoa2->seleccionarItemsPresupuestarios($conexion, $_POST['id']);
	
						while($fila4 = pg_fetch_assoc($res1)){
			
							echo "<tr id='t_".$fila4['id_presupuesto']."'><td>
									<form id='f_".$fila4['id_presupuesto']."' data-rutaAplicacion='poa' data-opcion='quitarItemPresupuesto'  >
										<button type='submit'";
											if($fila4['estado']>=2){
												echo 'disabled="disabled"';
											}
							echo " class='quitar'>Quitar</button>
                                        <input name='id_presupuesto' value='".$fila4['id_presupuesto'] ."' type='hidden'>
										<input name='id_item_planta' value='".$fila4['id_item_planta'] ."' type='hidden'>
										<input name='codigo_item' value='".$fila4['codigo_item'] ."' type='hidden'>
									</form>
								</td>
								<td>
									<button id=".$fila4['id_presupuesto']." name=".$fila4['id_presupuesto']." type='button' class='ver'>Ver</button>
								</td>
								<td>".$fila4['codigo_item']." </td>
								<td> ".$fila4['detalle_gasto']." </td>
								<td class='totalItemPresupuestario'> ".$fila4['total']."</td>
								</tr>";
						}
						
						/*while($fila4 = pg_fetch_assoc($res1)){
						    
						    echo "<tr id='t_".str_replace(' ','',$fila4['id_item_planta'])."_".str_replace('.','',$fila4['codigo_item'])."'><td>
									<form id='f_".str_replace(' ','',$fila4['id_item_planta'])."_".$fila4['codigo_item']."' data-rutaAplicacion='poa' data-opcion='quitarItemPresupuesto'  >
										<button type='submit'";
						    if($fila4['estado']>=2){
						        echo 'disabled="disabled"';
						    }
						    echo " class='quitar'>Quitar</button>
										<input name='id_item_planta' value='".$fila4['id_item_planta'] ."' type='hidden'>
										<input name='codigo_item' value='".$fila4['codigo_item'] ."' type='hidden'>
									</form>
								</td>
								<td>
									<button id=".$fila4['id_item_planta'] ."_".$fila4['codigo_item']." name=".$fila4['id_item_planta'] ."_".$fila4['codigo_item']." type='button' class='ver'>Ver</button>
								</td>
								<td>".$fila4['codigo_item']." </td>
								<td> ".$fila4['detalle_gasto']." </td>
								<td class='totalItemPresupuestario'> ".$fila4['total']."</td>
								</tr>";
						}*/
					?>
				
				</tbody>
			</table>

		</div>
	</fieldset>



</body>


<script type="text/javascript">

var array_items= <?php echo json_encode($listadoItem); ?>;
				
$("#ventanaAplicacion").on("click","#detalleItem #listaItemsPresupuestarios .ver",function(event){
    
	for(var z=0;z<array_items.length;z++){
		$codigo=array_items[z]['id_presupuesto'];		
    
	    if ($(this).attr('name')==$codigo){
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
	    	$("#detalle_gasto").val(array_items[z]['detalle_gasto']);
	    	$('#itemsPresupusetarios').val(array_items[z]['codigo_item']);
   		}
	}

	/*for(var z=0;z<array_items.length;z++){
		$codigo=array_items[z]['id_item_planta']+"_"+array_items[z]['codigo_item'];		
    
	    if ($(this).attr('name')==$codigo){
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
	    	$("#detalle_gasto").val(array_items[z]['detalle_gasto']);
	    	$('#itemsPresupusetarios').val(array_items[z]['codigo_item']);
   		}
	}*/
});

	$("#nuevoItemPOA").submit(function(event){
		event.preventDefault();
		chequearCampos(this);
	});

	$(document).ready(function(){
		distribuirLineas();	
		construirValidador();
		    		
		var acum=0;
		  $("input").focus(function(){
		    $(this).css("background-color","#cccccc");
		  });
		  
		  $("input").blur(function(){
		    $(this).css("background-color","#ffffff");
		    trim1=Number($("#enero").val())+Number($("#febrero").val())+Number($("#marzo").val());
			trim2=Number($("#abril").val())+Number($("#mayo").val())+Number($("#junio").val());
			trim3=Number($("#julio").val())+Number($("#agosto").val())+Number($("#septiembre").val());
			trim4=Number($("#octubre").val())+Number($("#noviembre").val())+Number($("#diciembre").val());

            acum=trim1+trim2+trim3+trim4;
            $("#trimestre_1").val(trim1);
            $("#trimestre_2").val(trim2);
            $("#trimestre_3").val(trim3);
            $("#trimestre_4").val(trim4);
		    $("#total").val(acum);

		   });


		  var totalPresupuesto = 0 ;
		  
		  $('#listaItemsPresupuestarios .totalItemPresupuestario').each(function(){   
				totalPresupuesto += Number($(this).html());
				totalPresupuesto = Math.round((totalPresupuesto)*100)/100;
		    });

		  $('#listaItemsPresupuestarios').append('<td colspan="4" align="center">TOTAL</td><td><label id="totalPresupuesto">'+totalPresupuesto+'</label></td>');
		   
		});

	function calcularTotalPresupuesto(){

		 var totalPresupuesto = 0 ;

		 $('#listaItemsPresupuestarios .totalItemPresupuestario').each(function(){   
				totalPresupuesto += Number($(this).html());
				totalPresupuesto = Math.round((totalPresupuesto)*100)/100;
		    });

		  $('#totalPresupuesto').html(totalPresupuesto);

	}

	$("#listaItemsPresupuestarios").on("submit", "form",function(event){
		event.preventDefault();
		ejecutarJson($(this));
		var texto=$(this).attr('id').substring(2);
		texto=texto.replace(/ /g,'');
		texto="#t_"+texto; 
		$("#presupuestos tr").eq($(texto).index()).remove();
		calcularTotalPresupuesto();
	});
    	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#itemsPresupusetarios").val()) || !esCampoValido("#itemsPresupusetarios")){
			error = true;
			$("#itemsPresupusetarios").addClass("alertaCombo");
		}
		
		if(!$.trim($("#detalle_gasto").val()) || !esCampoValido("#detalle_gasto")){
			error = true;
			$("#detalle_gasto").addClass("alertaCombo");
		}
		
		if(!$.trim($("#enero").val()) || !esCampoValido("#enero")){
			error = true;
			$("#enero").addClass("alertaCombo");
		}
		
		if(!$.trim($("#febrero").val()) || !esCampoValido("#febrero")){
			error = true;
			$("#febrero").addClass("alertaCombo");
		}

		if(!$.trim($("#marzo").val()) || !esCampoValido("#marzo")){
			error = true;
			$("#marzo").addClass("alertaCombo");
		}
		
		if(!$.trim($("#abril").val()) || !esCampoValido("#abril")){
			error = true;
			$("#abril").addClass("alertaCombo");
		}

		if(!$.trim($("#mayo").val()) || !esCampoValido("#mayo")){
			error = true;
			$("#mayo").addClass("alertaCombo");
		}
		
		if(!$.trim($("#junio").val()) || !esCampoValido("#junio")){
			error = true;
			$("#junio").addClass("alertaCombo");
		}

		if(!$.trim($("#julio").val()) || !esCampoValido("#julio")){
			error = true;
			$("#julio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#agosto").val()) || !esCampoValido("#agosto")){
			error = true;
			$("#agosto").addClass("alertaCombo");
		}

		if(!$.trim($("#septiembre").val()) || !esCampoValido("#septiembre")){
			error = true;
			$("#septiembre").addClass("alertaCombo");
		}
		
		if(!$.trim($("#octubre").val()) || !esCampoValido("#octubre")){
			error = true;
			$("#octubre").addClass("alertaCombo");
		}

		if(!$.trim($("#noviembre").val()) || !esCampoValido("#noviembre")){
			error = true;
			$("#noviembre").addClass("alertaCombo");
		}
		
		if(!$.trim($("#diciembre").val()) || !esCampoValido("#diciembre")){
			error = true;
			$("#diciembre").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			var respuesta = JSON.parse(ejecutarJson(form).responseText);			
	       	if (respuesta.estado == 'exito'){
	       		item = respuesta.contenido;

	       		if($("#presupuestos #t_"+item).length==0){
					$("#presupuestos").append("<tr id='t_"+item+"'><td><form id='f_"+item+"' data-rutaAplicacion='poa' data-opcion='quitarItemPresupuesto'><button type='submit' class='quitar'>Quitar</button><input name='id_presupuesto' value='"+item+"' type='hidden'><input name='id_item_planta' value='"+<?php echo $_POST['id'];?>+"' type='hidden'><input name='codigo_item' value='"+$("#itemsPresupusetarios").val()+"' type='hidden'></form></td><td><button id="+item+" name="+item+" type='button' class='ver' >Ver</button></td><td>"+$("#itemsPresupusetarios").val()+" </td><td>"+$("#detalle_gasto").val()+"</td><td class='totalItemPresupuestario'> "+$("#total").val()+" </td></tr>");

					calcularTotalPresupuesto();	
					
					var data = {id_presupuesto : item, codigo_item : $('#itemsPresupusetarios').val(), detalle_gasto : $("#detalle_gasto").val(), enero : $("#enero").val(), enero : $("#enero").val(), febrero : $("#febrero").val(),
						marzo : $("#marzo").val(), abril : $("#abril").val(), mayo : $("#mayo").val(), junio : $("#junio").val(), julio : $("#julio").val(), agosto : $("#agosto").val(),
						septiembre : $("#septiembre").val(), octubre : $("#octubre").val(), noviembre : $("#noviembre").val(), diciembre : $("#diciembre").val(), id_item_planta:$("#idItem").val()};

					//array_items.push(data);	
					//$("#_actualizar").click();
					$("#nuevoItemPOA").attr('data-opcion', 'nuevoItemPresupuestario');
					abrir($("#nuevoItemPOA"),event,false);				
	        	}			
			
			}else{
				//$("#estado").html('Por favor verifique la información, los detalles de gasto no pueden ser iguales.').addClass('alerta');
				$("#estado").html(respuesta.mensaje).addClass('alerta');
			}		       			
		}

		/*else{
			var item = $("#itemsPresupusetarios").val();
			item = item.replace(/[.]/g,'');
			item = item.replace(/ /g,'');
			
			if($("#presupuestos #t_"+< ?php echo $_POST['id'];?>+"_"+item).length==0){
				$("#presupuestos").append("<tr id='t_"+< ?php echo $_POST['id'];?>+"_"+item+"'><td><form id='f_"+< ?php echo $_POST['id'];?>+"_"+$("#itemsPresupusetarios").val().replace(/ /g,'')+"' data-rutaAplicacion='poa' data-opcion='quitarItemPresupuesto'><button type='submit' class='quitar'>Quitar</button><input name='id_item_planta' value='"+< ?php echo $_POST['id'];?>+"' type='hidden'><input name='codigo_item' value='"+$("#itemsPresupusetarios").val()+"' type='hidden'></form></td><td><button id='verElemento' name="+< ?php echo $_POST['id'];?>+"_"+$("#itemsPresupusetarios").val().replace(/ /g,'')+" type='button' class='ver' >Ver</button></td><td>"+$("#itemsPresupusetarios").val()+" </td><td>"+$("#detalle_gasto").val()+"</td><td class='totalItemPresupuestario'> "+$("#total").val()+" </td></tr>");

				calcularTotalPresupuesto();				   
				
				ejecutarJson(form);
				
				var data = {codigo_item : $('#itemsPresupusetarios').val(), detalle_gasto : $("#detalle_gasto").val(), enero : $("#enero").val(), enero : $("#enero").val(), febrero : $("#febrero").val(),
					marzo : $("#marzo").val(), abril : $("#abril").val(), mayo : $("#mayo").val(), junio : $("#junio").val(), julio : $("#julio").val(), agosto : $("#agosto").val(),
					septiembre : $("#septiembre").val(), octubre : $("#octubre").val(), noviembre : $("#noviembre").val(), diciembre : $("#diciembre").val(), id_item_planta:$("#idItem").val()};
					array_items.push(data);		

				$("#_actualizar").click();
			}else{
				$("#estado").html('Por favor verifique la información, los objetivos no pueden ser iguales.').addClass('alerta');
			}				
		}*/
	}

	
</script>