<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';
$conexion = new Conexion();
$cap = new ControladorAplicacionesPerfiles();

$idAccion=$_POST['idAccion'];
$qDatosAccion=$cap->buscarAccion($conexion, $idAccion);
$datosAccion=pg_fetch_assoc($qDatosAccion);
?>
<header>
	<h1>Modificar Información Acción</h1>
</header>
<div id="estado"></div>
<form id="regresar" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="abrirOpcion" data-destino="detalleItem">
	<input type="hidden" id="idAplicacion" name="idAplicacion" value="<?php echo $datosAccion['id_aplicacion'];?>" /> 
	<input type="hidden" name="idOpcion" value="<?php echo $datosAccion['id_opcion'];?>"/>
	<button class="regresar">Regresar Opción</button>
</form>

<form id="modificarAccion" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="actualizarAccion" >
	<input type="hidden" id="idAccion" name="idAccion" value="<?php echo $idAccion;?>" /> 
	
	<div>
		<button id="modificar" type="button" class="editar">Editar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</div>	
	
	<fieldset>
		<legend>Datos Acción</legend>	
			<div data-linea="1">
				<label>Estilo: </label> 
					<select id="estiloAccion" name="estiloAccion"  >
						<option value="0">Seleccione...</option>
						<option value="_nuevo">Nuevo</option>
						<option value="_actualizar">Actualizar</option>
						<option value="_seleccionar">Seleccionar</option>
						<option value="_eliminar">Eliminar</option>
						<option value="_asignar">Asignar</option>
						<option value="_agrupar">Agrupar</option>
						<option value="">TODO(vacio)</option>
					</select>
			</div>
			<div data-linea="1">
				<label>Descripción: </label> 
				<input type="text" id="descripcionAccion" name="descripcionAccion" value="<?php echo $datosAccion['descripcion'];?>" maxlength="8" disabled />
			</div>
			<div data-linea="2">
				<label>Pagina: </label> 
				<input type="text" id="paginaAccion" name="paginaAccion" value="<?php echo $datosAccion['pagina'];?>"  maxlength="1024" disabled />
			</div>
			<div data-linea="2">
				<label>Orden: </label> 
				<input type="text" id="ordenAccion" name="ordenAccion" value="<?php echo $datosAccion['orden'];?>" data-er="^[0-9]+$"  maxlength="1024" disabled />
			</div>
	</fieldset>
</form>
				
<script type="text/javascript">
	$('document').ready(function(){
		distribuirLineas();
		cargarValorDefecto("estiloAccion","<?php echo $datosAccion['estilo'];?>");
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#estiloAccion").change(function(event){
    	 if($("#estiloAccion").val()!="0"){
         	if($("#estiloAccion").val()!="")
         		$("#descripcionAccion").val($("#estiloAccion option:selected").text());
         	else
         		$("#descripcionAccion").val('TODO');     	    	
         }else{
         	$("#descripcionAccion").val("");
         }
    });
    
	$("#modificarAccion").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;


		if(!esCampoValido("#ordenAccion")){
			error = true;
			$("#ordenAccion").addClass("alertaCombo");
			$("#estado").html("Por favor revise el formato del orden de la acción.").addClass("alerta");
		}
		
		if($.trim($("#ordenAccion").val())=="" ){
			error = true;
			$("#ordenAccion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el orden de la acción.").addClass("alerta");
		}
		
		
		if($.trim($("#descripcionAccion").val())=="" ){
			error = true;
			$("#descripcionAccion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la descripción de la acción.").addClass("alerta");
		}
		
		if($.trim($("#estiloAccion").val())=="0" ){
			error = true;
			$("#estiloAccion").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione el estilo de la acción.").addClass("alerta");
		}

		if (!error){
			ejecutarJson($(this));
		}
	});

	$("#regresar").submit(function(event){
		event.preventDefault();
		abrir($("#regresar"),event,false);
	});
	</script>