<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
	
	$conexion = new Conexion();
	$cpoa = new ControladorPAPP();
	
	$res = $cpoa->abrirProceso($conexion, $_POST['id']);
	$proceso = pg_fetch_assoc($res);
	
	$fecha = getdate();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Proceso / Proyecto</h1>
	</header>
	
	<div id="estado"></div>
	
	<form id="proceso" data-rutaAplicacion="poa" data-opcion="actualizarProceso" data-destino="detalleItems" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" name="idProceso" value="<?php echo $proceso['id_proceso'];?>"/>

	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<fieldset id="fs_detalle">
		<legend>Detalle</legend>
			<div data-linea="1">
				<label>Descripción </label>
					<input type="text" id="descripcion" name="descripcion" disabled="disabled" value="<?php echo $proceso['descripcion'];?>" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
	</fieldset>
	
</form>

<!-- form id="componente" data-rutaAplicacion="poa" data-opcion="guardarNuevoComponente"  data-destino="detalleItems" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idProcesos" name="idProcesos"	value="< ?php echo $proceso['id_proceso'];?>" />
	<input type="hidden" name="anio" value="< ?php echo $fecha['year'];?>"/>
	
		<fieldset>
			<legend>Objetivos operativos</legend>
			<div>
				
				<input type="text" name="descripcionProceso" id="descripcionProceso" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü ]+$" />
						<button type="submit" class="mas">Agregar</button>
			</div>
		</fieldset>
</form-->

	
<!-- fieldset>
	<legend>Objetivos operativos</legend>
	
		<div data-linea="1">
		
			<table id="listadoDeComponentes">
					
				<thead>
					<tr>						
						<th></th>
						<th>Objetivos operativos</th>
					<tr>
				</thead> 
				
				<tbody id="subproceso">
					< ?php
						$cpoa1 = new ControladorPAPP();
						$res1 = $cpoa1->seleccionarComponentes($conexion, $_POST['id']);
													
						while($fila = pg_fetch_assoc($res1)){
							
							echo "<tr id='t_".str_replace(' ','',$fila['descripcion'])."".$fila['id_proceso']."'>
									<td> 
										<form id='f_".str_replace(' ','',$fila['descripcion'])."".$fila['id_proceso']."' data-rutaAplicacion='poa' data-opcion='quitarComponente'  >
											<button type='submit' class='menos'>Quitar</button>
											<input name='componente_id' value='".$fila['id_proceso'] ."' type='hidden'>
											<input name='componente_dato' value='".$fila['descripcion'] ."' type='hidden'>
										</form>
									</td>
									<td>"
										.$fila['descripcion']."
									</td>
								</tr>";
						}
					?>
				
				</tbody>
			</table>
			
		</div>
</fieldset-->


</body>

<script type="text/javascript">
$(document).ready(function(){
	distribuirLineas();
	construirValidador();
});

$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
	
});

$("#proceso").submit(function(event){
	event.preventDefault();
	chequearCamposSubproceso(this);
});

$("#listadoDeComponentes").on("submit","form",function(event){
  
	event.preventDefault();
	ejecutarJson($(this));
	var texto=$(this).attr('id').substring(2);
	texto=texto.replace(/ /g,'');
	texto="#t_"+texto;
	$("#subproceso tr").eq($(texto).index()).remove();
		
});

$("#componente").submit(function(event){
	event.preventDefault();
	chequearCamposComponente(this);
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

function chequearCamposSubproceso(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#descripcion").val()) || !esCampoValido("#descripcion")){
		error = true;
		$("#descripcion").addClass("alertaCombo");
	}
	
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson(form);
		if($("#estado").html()=='El Proceso ha sido actualizado satisfactoriamente'){
			$("#_actualizar").click();
		}
	}
}

function chequearCamposComponente(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#descripcionProceso").val()) || !esCampoValido("#descripcionProceso")){
		error = true;
		$("#descripcionProceso").addClass("alertaCombo");
	}
	
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		if($("#subproceso #t_"+$("#descripcionProceso").val().replace(/ /g,'')+$("#idProcesos").val()).length==0){
			  $("#subproceso").append("<tr id='t_"+$("#descripcionProceso").val().replace(/ /g,'')+$("#idProcesos").val()+"'><td><form id='f_"+$("#descripcionProceso").val().replace(/ /g,'')+$("#idProcesos").val()+"' data-rutaAplicacion='poa' data-opcion='quitarComponente'><button type='submit' class='menos'>Quitar</button><input name='componente_id' value='"+$("#idProcesos").val()+"' type='hidden'><input name='componente_dato' value='"+$("#descripcionProceso").val()+"' type='hidden'></form></td><td>"+$("#descripcionProceso").val()+"</td></tr>");
			  ejecutarJson(form);
				
				if($("#estado").html()=='El Proceso ha sido actualizado satisfactoriamente'){
					$("#_actualizar").click();
				}
		}else{
			$("#estado").html('Por favor verifique la información, los objetivos no pueden ser iguales.').addClass('alerta');
		}
	}
}

</script>
</html>