<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorServiciosInformacionTecnica.php';
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$csit = new ControladorServiciosInformacionTecnica();	
	$usuarioResponsable=$_SESSION['usuario'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Nueva Enfermedad Exótica</h1>
	</header>
	<div id="estado"></div>
	<form id="nuevoEnfermedadesExoticas" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="guardarEnfermedadExoticaSAA" data-destino="detalleItem">
		<input type="hidden" id="usuarioResponsable" name="usuarioResponsable" value="<?php echo $usuarioResponsable;?>" />
		<input type="hidden" id="nombreEnfermedad" name="nombreEnfermedad" /> 
		<fieldset>
			<legend>Enfermedades Exóticas Reportadas y Vigencia</legend>
				<div data-linea="1">
					<label>Enfermedad: </label>
					<select id="enfermedad" name="enfermedad">
						<option value="">Seleccione...</option>
						<?php 
							$qEnfermedad=$cc->listarEnfermedadesAnimales($conexion);
							while($fila=pg_fetch_assoc($qEnfermedad)){
								echo '<option value="'.$fila['id_enfermedad'].'">' . $fila['nombre'] . '</option>';
							}
						?>
					</select>
				</div>
				<div data-linea="2">
					<label>Inicio Vigencia:</label> 
						<input type="text" id="inicioVigencia" name="inicioVigencia" readonly /> 
				</div>
				<div data-linea="2">
					<label>Fin Vigencia:</label> 
						<input type="text" id="finVigencia" name="finVigencia" readonly /> 
				</div>
				<div data-linea="3">
					<label>Observaciones:</label> 
				</div>
				<div data-linea="4">
					<textarea rows="4" cols="50" id="observacion" name="observacion" maxlength="512" ></textarea>
				</div>
		</fieldset>

		<input type="hidden" id=opcionL name="opcionL"  />
		<fieldset>
			<legend>Localización</legend>
				<div data-linea="1" >
					<label>Zona de Origen: </label>
					<select name="zona" id="zona"	style="width: 100%">
						<option value="">Seleccione...</option>
						<?php
						$qListarZonas=$cc->listarZonas($conexion);
						while($fila=pg_fetch_assoc($qListarZonas)){
							echo '<option value="'.$fila['id_zona'].'">'. $fila['nombre'] . '</option>';
						}
						?>
					</select>
				</div>
				<div data-linea="2" id="resultadoPais">
					<label>País: </label>
					<select id="pais" name="pais" style="width: 100%" >
						<option value="">Seleccione...</option>
					</select>
				</div>
				<div data-linea="4">
					<button type="submit" id="agregarDetalleLocalizacion" class="mas" >Agregar Pais</button>
				</div>
				<table id="detalleLocalizacion" style="width:100%"  class="tablaMatriz">
			</table>
		</fieldset>	
		
		<fieldset>
			<legend>Requerimientos de Revisión/Ingreso</legend>
				<div data-linea="1" >
					<label>Tipo: </label>
					<select name="tipoRequerimiento" id="tipoRequerimiento"	style="width: 100%">
						<option value="">Seleccione...</option>
						<?php
							$qRequerimiento=$cc->listarRequerimientoRevisionIngreso($conexion);
							while($fila=pg_fetch_assoc($qRequerimiento)){
								echo '<option value="'.$fila['id_requerimiento'].'">'. $fila['nombre'] . '</option>';
							}
						?>
					</select>
				</div>
				<div data-linea="2" id="resultadoElemento">
					<label>Requerimiento: </label>
					<select id="elementoRevision" name="elementoRevision" style="width: 100%" >
						<option value="">Seleccione...</option>
					</select>
				</div>
				<div data-linea="4">
					<button type="submit" id="agregarDetalleRequerimiento" class="mas" >Agregar Requerimiento</button>
				</div>
				<table id="detalleRequerimiento" style="width:100%" class="tablaMatriz">
				</table>
		</fieldset>	
		<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" >Guardar</button>
	</form>
</body>
<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
	});

	$("#inicioVigencia").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});

	$("#finVigencia").datepicker({
	      changeMonth: true,
	      changeYear: true
	});

	$("#zona").change(function(event){
		if($("#zona").val()!=0){
			$('#nuevoEnfermedadesExoticas').attr('data-destino','resultadoPais');
			$('#nuevoEnfermedadesExoticas').attr('data-opcion','combosServicios');
			$('#opcionL').val('listaPaises');
			abrir($("#nuevoEnfermedadesExoticas"),event,false); 
		}
	 });

	$("#enfermedad").change(function(event){
		if($("#enfermedad").val()!=0){
			$('#nombreEnfermedad').val($('#enfermedad option:selected').text());
		}
	 });
	 
	$("#tipoRequerimiento").change(function(event){
		if($("#tipoRequerimiento").val()!=0){
			$('#nuevoEnfermedadesExoticas').attr('data-destino','resultadoElemento');
			$('#nuevoEnfermedadesExoticas').attr('data-opcion','combosServicios');
			$('#opcionL').val('listaElementos');
			abrir($("#nuevoEnfermedadesExoticas"),event,false); 
		}
	 });
	 
	$("#agregarDetalleLocalizacion").click(function(event){
		event.preventDefault();
	 	$(".alertaCombo").removeClass("alertaCombo");
	 	error = false;

	 	if($("#zona").val()==""){
			error = true;
		    $("#zona").addClass("alertaCombo");
		    $("#estado").html('Por favor seleccione una zona.').addClass("alerta");  
		} 

		if ($("#pais").val()==""){
			error = true;
			$("#pais").addClass("alertaCombo");
		  	$("#estado").html('Por favor seleccione un país.').addClass("alerta");
		}

		if (!error){	 	
	    	var codigo = $("#zona").val() +'_'+ $("#pais").val();
	    	if($("#detalleLocalizacion #r_"+codigo.replace(/ /g,'')).length==0){
    			$("#detalleLocalizacion").append("<tr id='r_"+codigo 
    	   		+"'><td align='center' class='borrar' ><button type='button' class='menos' onclick='quitarDetalleLocalizacion(\"#r_"+codigo+"\")' >Quitar</button></td><td width='100%'><input type='hidden' id='hZona' name='hZona[]' value='"+$("#zona option:selected").val()+"'><input type='hidden' id='hZonaNombre' name='hZonaNombre[]' value='"+$("#zona option:selected").text()+"'><input type='hidden' id='hPais' name='hPais[]' value='"+$("#pais option:selected").val()+"'><input type='hidden' id='hPaisNombre' name='hPaisNombre[]' value='"+$("#pais option:selected").text()+"'>"+$("#zona option:selected").text()+' - '+$("#pais option:selected").text()
    		 	+"</td></tr>");
	    	}else{
			    $("#estado").html("Por favor verifique datos, solo puede ingresar la misma zona y el mismo pais una sola vez.").addClass('alerta');
			}	
		} 
	});

	$("#agregarDetalleRequerimiento").click(function(event){
		event.preventDefault();
	 	$(".alertaCombo").removeClass("alertaCombo");
	 	error = false;

	 	if($("#tipoRequerimiento").val()==""){
			error = true;
		    $("#tipoRequerimiento").addClass("alertaCombo");
		    $("#estado").html('Por favor seleccione una un tipo de requerimiento.').addClass("alerta");  
		} 

		if ($("#elementoRevision").val()==""){
			error = true;
			$("#elementoRevision").addClass("alertaCombo");
		  	$("#estado").html('Por favor seleccione un requerimiento.').addClass("alerta");
		}

		if (!error){	 	
	    	var codigo = $("#tipoRequerimiento").val() +'_'+ $("#elementoRevision").val();
	    	if($("#detalleRequerimiento #r_"+codigo.replace(/ /g,'')).length==0){
	    		$("#detalleRequerimiento").append("<tr id='r_"+codigo 
	    	    +"'><td align='center' class='borrar' ><button type='button' class='menos' onclick='quitarDetalleRequerimiento(\"#r_"+codigo+"\")' >Quitar</button></td><td width='100%'><input type='hidden' id='hTipoRequerimiento' name='hTipoRequerimiento[]' value='"+$("#tipoRequerimiento option:selected").val()+"'><input type='hidden' id='hTipoRequerimientoNombre' name='hTipoRequerimientoNombre[]' value='"+$("#tipoRequerimiento option:selected").text()+"'><input type='hidden' id='hElementoRevision' name='hElementoRevision[]' value='"+$("#elementoRevision option:selected").val()+"'><input type='hidden' id='hElementoRevisionNombre' name='hElementoRevisionNombre[]' value='"+$("#elementoRevision option:selected").text()+"'>"+$("#tipoRequerimiento option:selected").text()+' - '+$("#elementoRevision option:selected").text()
	    		 +"</td></tr>");
	    	}else{
			    $("#estado").html("Por favor verifique datos, solo puede ingresar el mismo tipo y el mismo requerimiento una sola vez.").addClass('alerta');
			}	
		} 
	});

	function quitarDetalleLocalizacion(fila){
			$("#detalleLocalizacion tr").eq($(fila).index()).remove();
	}

	function quitarDetalleRequerimiento(fila){
		$("#detalleRequerimiento tr").eq($(fila).index()).remove();
	}
	
	$("#nuevoEnfermedadesExoticas").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($('#detalleRequerimiento tbody >tr').length==0){
			error = true;
			$("#estado").html('Por favor ingrese al menos un requerimiento.').addClass("alerta");
		}
		
		if($('#detalleLocalizacion tbody >tr').length==0){
			error = true;
			$("#estado").html('Por favor ingrese al menos una localización.').addClass("alerta");
		}
		
		if(!$.trim($("#finVigencia").val())){
			error = true;
			$("#finVigencia").addClass("alertaCombo");
			 $("#estado").html('Por favor ingrese la fecha de fin de vigencia.').addClass("alerta");
		}

		if(!$.trim($("#inicioVigencia").val())){
			error = true;
			$("#inicioVigencia").addClass("alertaCombo");
			 $("#estado").html('Por favor ingrese la fecha de inicio de vigencia.').addClass("alerta");
		}
		
		if(!$.trim($("#enfermedad").val())){
			error = true;
			$("#enfermedad").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione el nombre de la enfermedad.').addClass("alerta");
		}
		
		if (!error){
			$("#nuevoEnfermedadesExoticas").attr('data-opcion', 'guardarEnfermedadExoticaSAA');
			$("#nuevoEnfermedadesExoticas").attr('data-destino', 'detalleItem');
			abrir($("#nuevoEnfermedadesExoticas"),event,false);
		}
	});
</script>