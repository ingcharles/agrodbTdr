<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';
$conexion = new Conexion();
$cap = new ControladorAplicacionesPerfiles();

$idOpcion=$_POST['idOpcion'];
$idAplicacion=$_POST['idAplicacion'];
$qAccionesOpcion=$cap->buscarAccionesOpcion($conexion, $idOpcion,$idAplicacion);
$qDatosOpcion=$cap->buscarOpcion($conexion, $idOpcion,$idAplicacion);
$datosOpcion=pg_fetch_assoc($qDatosOpcion);

?>
<header>
	<h1>Modificar Información Opción</h1>
</header>
<div id="estado"></div>
<form id="regresar" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="abrirAplicacion" data-destino="detalleItem">
	<input type="hidden" name="id" value="<?php echo $idAplicacion;?>"/>
	<button class="regresar">Regresar Aplicación</button>
</form>

<form id="modificarOpcion" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="actualizarOpcion" >
	<input type="hidden" id="idOpcion" name="idOpcion" value="<?php echo $idOpcion;?>" /> 
	<input type="hidden" id="idAplicacion" name="idAplicacion" value="<?php echo $idAplicacion;?>" /> 
	
	<div>
		<button id="modificar" type="button" class="editar">Editar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</div>	

	<fieldset>
		<legend>Datos Opción</legend>	
		<div data-linea="1">
			<label>Descripción: </label> 
			<input type="text" id="nombreOpcion" name="nombreOpcion" value="<?php echo $datosOpcion['nombre_opcion'];?>" maxlength="256" disabled />
		</div>
		<div data-linea="1">
			<label>Estilo: </label> 
			<input type="text" id="estiloOpcion" name="estiloOpcion" value="<?php echo $datosOpcion['estilo'];?>" maxlength="32" disabled />
		</div>
		<div data-linea="2">
			<label>Pagina: </label> 
			<input type="text" id="paginaOpcion" name="paginaOpcion" value="<?php echo $datosOpcion['pagina'];?>"  maxlength="1024" disabled />
		</div>
		<div data-linea="2">
			<label>Orden: </label> 
			<input type="text" id="ordenOpcion" name="ordenOpcion" value="<?php echo $datosOpcion['orden'];?>" data-er="^[0-9]+$" maxlength="1024" disabled />
		</div>
	</fieldset>
</form>

<form id="nuevoAccionOpcion" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="guardarNuevoAccion" >
	<input type="hidden" id="idAplicacion" name="idAplicacion" value="<?php echo $idAplicacion?>" />
	<input type="hidden" id="idOpcion" name="idOpcion" value="<?php echo $idOpcion?>" />
	<fieldset>
		<legend>Nueva Acción</legend>
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
			<input type="text" id="descripcionAccion" name="descripcionAccion" maxlength="2" />
		</div>
		<div data-linea="2">
			<label>Pagina: </label>
			<input type="text" id="paginaAccion" name="paginaAccion"  maxlength="256"  />	
		</div>
		<div data-linea="2">
			<label>orden: </label> 
			<input type="text" id="ordenAccion" name="ordenAccion" data-er="^[0-9]+$" maxlength="2" />
		</div>
		<div data-linea="3">
			<button type="submit" class="mas">Agregar</button>
		</div>
	</fieldset>
</form>
	
<fieldset>
	<legend>Acciones Opción</legend>
		<table id="camposAcciones" style="width:100%;">
			<tr>
				<th>Estilo</th>
				<th>Descripción</th>
				<th>Pagina</th>
				<th>Orden</th>
				<th>Abrir</th>
				<th>Eliminar</th>
			</tr>
			<?php 
				while ($fila = pg_fetch_assoc($qAccionesOpcion)){
					echo $cap->imprimirLineaAccionesOpcion($fila['id_accion'], $fila['descripcion'],$fila['pagina'],$fila['estilo'],$fila['orden']);
				}
			?>
	</table>
</fieldset>
<script type="text/javascript">
						
	$('document').ready(function(){
		distribuirLineas();
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});
	
	acciones("#nuevoAccionOpcion","#camposAcciones",null,null,null,null,null,new validarInputs());

    function validarInputs() {
	    this.ejecutar = function () {

	        var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
	        
			if ($("#estiloAccion").val()=="0"){
				error = true;
		        $("#estiloAccion").addClass("alertaCombo");
		        $("#estado").html('Por favor ingrese el nombre de la opción').addClass("alerta");
			}

			if ($("#descripcionAccion").val()==""){
				error = true;
		        $("#descripcionAccion").addClass("alertaCombo");
		        $("#estado").html('Por favor ingrese el nombre de la opción').addClass("alerta");
			}
			
			if(!esCampoValido("#ordenAccion") ){
				error = true;
				$("#ordenAccion").addClass("alertaCombo");
				$("#estado").html("Por favor revise el formato del orden de la acción.").addClass("alerta");
			}
	        return !error;   
	    };
	}

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
    
	$("#modificarOpcion").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!esCampoValido("#ordenOpcion")){
			error = true;
			$("#ordenOpcion").addClass("alertaCombo");
			$("#estado").html("Por favor revise el formato del orden de la opción.").addClass("alerta");
		}
		
		if($.trim($("#paginaOpcion").val())=="" ){
			error = true;
			$("#paginaOpcion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la página de la opción.").addClass("alerta");
		}
		
		if($.trim($("#estiloOpcion").val())=="" ){
			error = true;
			$("#estiloOpcion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el estilo de la opción.").addClass("alerta");
		}

		if($.trim($("#nombreOpcion").val())=="" ){
			error = true;
			$("#nombreOpcion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la descripción de la opción.").addClass("alerta");
		}

		if (!error){
			ejecutarJson($(this));
		}
	});
</script>