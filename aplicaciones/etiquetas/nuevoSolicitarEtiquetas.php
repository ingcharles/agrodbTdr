<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEtiquetas.php';

$conexion = new Conexion();
$ce = new ControladorEtiquetas();

$identificadorOperador=$_SESSION['usuario'];
$secuencialAutogenerado=$ce->autogenerarNumeroSolicitudEtiquetasOrnamentales($conexion, $identificadorOperador, date('Y'));
$secuencialSolicitud = str_pad($secuencialAutogenerado, 5, "0", STR_PAD_LEFT);
$numeroSolicitud= date('Y').'-'.$secuencialSolicitud;
?>

<header>
	<h1>Nueva Solicitud de Etiquetas</h1>
</header>

<form id='nuevoSolicitarEtiquetas' data-rutaAplicacion='etiquetas'  data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
	<input type="hidden" name="identificadorOperador" value="<?php echo $identificadorOperador; ?>" />
	<input type="hidden" name="secuencial" value="<?php echo $secuencialAutogenerado; ?>" />
	<input type="hidden" name="anio" value="<?php echo date('Y'); ?>" />	
	<input type="hidden" id="totalEtiquetas" name="totalEtiquetas" value="0" />			
	<input type="hidden" name="opcion" id="opcion" />
	
	<fieldset>		
		<legend>Ingreso de Número de Etiquetas</legend>
			<div data-linea="1">
				<label>Número de Solicitud:  <?php echo $numeroSolicitud;?></label>
				<input type="hidden" id="numeroSolicitud" name="numeroSolicitud" value="<?php echo $numeroSolicitud;?>"   readOnly/>
			</div>
			<div data-linea="2">
			<label>Sitio: </label>
				<select id="sitio" name="sitio">
					<option value="0">Seleccione...</option>
					<?php 
						$qSitiosOperaciones=$ce->buscarSitiosOperadoresPorCodigoyAreaOperacion($conexion, $identificadorOperador, '{ACO,COM}','{SV}');
						 while($fila=pg_fetch_assoc($qSitiosOperaciones)){
							echo '<option data-codigoSitio="'.$fila['codigo_sitio'].'" value="'.$fila['id_sitio'].'">' . $fila['nombre_sitio'] . '</option>';
						}
					?>
				</select>
			</div>	
			<div  data-linea="3" id="resultadoAreas" >
				<label>Área: </label>
				<select id="area" name="area">
					<option value="0">Seleccione...</option>
				</select>
			</div>
			<div data-linea="4">
				<label>Número de Etiquetas: </label> 
				<input type="text" id="numeroEtiquetas" name="numeroEtiquetas" value="" onkeypress='ValidaSoloNumeros()' maxlength="7" data-er="^[0-9]+$"  />
			</div>
			<button type="button" id="agregarDetalle" name="agregarDetalle"  class="mas">Agregar</button>	
	</fieldset>
	
	<fieldset>		
		<legend>Etiquetas de Sitios Agregados</legend>
		<div data-linea="1" >
				<table style="width:100%;">
					<thead>	
						<tr>
							<th># Solicitud</th>
							<th>Nombre del Sitio</th>
							<th>Nombre del Área</th>
							<th># Etiquetas</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="tablaDetalle"></tbody>
				</table>
			</div>
			
	</fieldset>
	<fieldset>		
		<legend>Total de Etiquetas</legend>
		<div data-linea="1">Número de Etiquetas: <label><span class="items">0</span></label></div>
		<div data-linea="2"></div>
		<div data-linea="2"><button type="submit" id="btnGuardar"  name="btnGuardar" > Enviar </button></div>
	</fieldset>
</form>

<script type="text/javascript">		  

$(document).ready(function(){
	distribuirLineas();
});


function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

function ValidaSoloNumeros() {
	 if ((event.keyCode < 48) || (event.keyCode > 57))
	  event.returnValue = false;
}

$("#sitio").change(function(event){
	if(	$("#sitio").val()!=0){
		$('#nuevoSolicitarEtiquetas').attr('data-opcion','accionesEtiquetas');	
	    $('#nuevoSolicitarEtiquetas').attr('data-destino','resultadoAreas');		 
	    $('#opcion').val('listaAreas');		
		abrir($("#nuevoSolicitarEtiquetas"),event,false);
	}
});

$("#nuevoSolicitarEtiquetas").submit(function(event){
	event.preventDefault();	
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if ($('#tablaDetalle >tr').length == 0){
		 error = true;	
		 $("#estado").html('Por favor agregre al menos un registro en la sección etiquetas agregadas.').addClass("alerta");
	}
	
	if (!error){
		$("#estado").html("").removeClass('alerta');	
		$("#nuevoSolicitarEtiquetas").attr('data-destino', 'detalleItem'); 
		$('#nuevoSolicitarEtiquetas').attr('data-opcion','guardarNuevoSolicitarEtiquetas');   
		ejecutarJson("#nuevoSolicitarEtiquetas");
		if($("#estado").html()=='Los datos han sido guardado satisfactoriamente'){
			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un contrato para revisarlo.</div>');
		}
	}	
});
function contarTotalEtiqueta(){
	var totalEtiquetas=0;
	  	$('#tablaDetalle tr').each(function (event) {
			totalEtiquetas+=parseInt($(this).find("input[id='aNumeroEtiquetas']").val());
	   });

	  	$(".items").html(totalEtiquetas);
	  	$("#totalEtiquetas").val(totalEtiquetas);
}
$("#agregarDetalle").click(function(event){
	$(".alertaCombo").removeClass("alertaCombo");
 	var error = false;

 	if($("#sitio").val() == 0 ){	
		error = true;		
		$("#sitio").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione el un sitio.').addClass("alerta");
	}

 	if($("#area").val() == 0 ){	
		error = true;		
		$("#area").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione el una area.').addClass("alerta");
	}

 	if(!esCampoValido("#numeroEtiquetas") ){
		error = true;
		$("#numeroEtiquetas").addClass("alertaCombo");
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}
	
  	$('#tablaDetalle tr').each(function (event) {
		if($(this).find('input[id="aIdArea"]').val()==$("#area").val()){
			error = true;	
			$(this).addClass("alertaCombo");
    		$("#estado").html('Solo puede agregar una vez el mismo sitio.').addClass("alerta");
		}

   });
	
	
   	if (!error){

   		var secuencial=$('#tablaDetalle tr:last-child').attr('id');
   		if(secuencial){
   			var codigo = secuencial.split('_');
   			secuencial=parseInt(codigo[1])+1;
   		}else{
   			secuencial=1;
   			
   		}

 	 	$("#tablaDetalle").append("<tr id='r_"+secuencial
			+"'><td><input id='aNumeroSolicitud' name='aNumeroSolicitud[]' value='"+$("#numeroSolicitud").val()+"' type='hidden'>"+$("#numeroSolicitud").val()
 		    +"</td><td><input id='aCodigoSitio' name='aCodigoSitio[]' value='"+$("#sitio option:selected").attr('data-codigoSitio')+"' type='hidden'><input id='aIdSitio' name='aIdSitio[]' value='"+$("#sitio").val()+"' type='hidden'>"+$("#sitio option:selected").text()
		    +"</td><td><input id='aCodigoArea' name='aCodigoArea[]' value='"+$("#area option:selected").attr('data-codigoArea')+"' type='hidden'><input id='aIdArea' name='aIdArea[]' value='"+$("#area").val()+"' type='hidden'>"+$("#area option:selected").text()
		    +"</td><td><input id='aNumeroEtiquetas' name='aNumeroEtiquetas[]' value='"+$("#numeroEtiquetas").val()+"' type='hidden'>"+$("#numeroEtiquetas").val()
			+"</td><td><button type='button' class='menos' onclick='quitarDetalle(\"#r_"+secuencial+"\")' >Quitar</button></td>"
			+"</td></tr>");	

 	 	contarTotalEtiqueta();
	    $("#estado").html("").removeClass('alerta');
	}
});

function quitarDetalle(fila){
	$("#tablaDetalle tr").eq($(fila).index()).remove();
	contarTotalEtiqueta();
}
</script>	