<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cvd = new ControladorVigenciaDocumentos();

$idVigenciaDocumento = $_POST['idVigenciaDocumento'];
$idVigenciaDeclarada = $_POST['idVigenciaDeclarada'];

$qDeclararVigencia = $cvd->obtenerVigenciaDeclaradaPorIdVigenciaDeclarada($conexion, $idVigenciaDeclarada);
$vigenciaDeclarada = pg_fetch_assoc($qDeclararVigencia);

?>

<header>
	<h1>Declarar vigencia documento</h1>
</header>
<div id="estado"></div>
	
<form id="regresar" data-rutaAplicacion="administracionVigenciaDocumentos" data-opcion="abrirVigenciaDocumento" data-destino="detalleItem" >
	<input type="hidden" name="id" value="<?php echo $idVigenciaDocumento;?>"/>
	<button class="regresar">Regresar a Tipo de Producto</button>
</form>
	
<form id=abrirVigenciaDeclarada data-rutaAplicacion="administracionVigenciaDocumentos" data-destino="detalleItem" >

	<input type="hidden" name="idVigenciaDocumento" value="<?php echo $idVigenciaDocumento;?>"/>
	<input type="hidden" id="idVigenciaDeclarada" name="idVigenciaDeclarada" value="<?php echo $idVigenciaDeclarada; ?>">
	
	<fieldset>	
		<legend>Declarar vigencia de documento</legend>			
		<div data-linea="1">			
			<label for="valorTiempoVigencia">*Vigencia: </label> <input type="text" id="valorTiempoVigencia" name="valorTiempoVigencia" value="<?php echo $vigenciaDeclarada['valor_tiempo_vigencia_declarada'];?>"disabled="disabled" >		
		</div>		
		<div data-linea="1" id="">			
			<label for="tipoTiempoVigencia">*Tipo: </label> 
			<select id="tipoTiempoVigencia" name="tipoTiempoVigencia" disabled="disabled" >
				<option value="">Seleccione....</option>
				<option value="anio">Año</option>
				<option value="mes">Mes</option>
				<option value="dia">Día</option>
			</select>		
		</div>	
		<hr/>
		<div data-linea="2">	
		<label>*Observación: </label><input type="text" id="observacionVigencia" name="observacionVigencia" value="<?php echo $vigenciaDeclarada['observacion_vigencia_declarada']; ?>" disabled="disabled" >		
		</div>	
	</fieldset>
	
<button id="modificar" type="button" class="editar">Editar</button>
<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
</form>
	

<script>
$('document').ready(function(){
	distribuirLineas();
	acciones("","");
	cargarValorDefecto("estadoVigenciaDeclarada","<?php echo $vigenciaDeclarada['estado_vigencia_declarada'];?>");	
	cargarValorDefecto("tipoTiempoVigencia","<?php echo $vigenciaDeclarada['tipo_tiempo_vigencia_declarada'];?>");
	cargarValorDefecto("tipoNotificacionVigencia","<?php echo $vigenciaDeclarada['tipo_notificacion_vigencia_declarada'];?>");
});

$('#valorTiempoVigencia').change(function(event){
	
	if($('#valorTiempoVigencia').val() == 0){
		$('#tipoTiempoVigencia').html("");
		$("#tipoTiempoVigencia").append("<option value=''>Seleccione...</option>");
	}else if($('#valorTiempoVigencia').val() == 1){
		$('#tipoTiempoVigencia').html("");
		$("#tipoTiempoVigencia").append("<option value=''>Seleccione...</option>");
		$("#tipoTiempoVigencia").append("<option value='anio'>Año</option>");
		$("#tipoTiempoVigencia").append("<option value='mes'>Mes</option>");
		$("#tipoTiempoVigencia").append("<option value='dia'>Día</option>");
	}else if($('#valorTiempoVigencia').val() > 1){
		$('#tipoTiempoVigencia').html("");
		$("#tipoTiempoVigencia").append("<option value=''>Seleccione...</option>");
		$("#tipoTiempoVigencia").append("<option value='anio'>Años</option>");
		$("#tipoTiempoVigencia").append("<option value='mes'>Meses</option>");
		$("#tipoTiempoVigencia").append("<option value='dia'>Días</option>");
	}
	
});

$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("select").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});

    $("#abrirVigenciaDeclarada").submit(function(){
        
  	 	event.preventDefault();
  	    $(".alertaCombo").removeClass("alertaCombo");
  	  	var error = false;

    	if($("#valorTiempoVigencia").val()==""){
			error = true;
			$("#valorTiempoVigencia").addClass("alertaCombo");
		}

    	if($("#tipoTiempoVigencia").val()==""){
			error = true;
			$("#tipoTiempoVigencia").addClass("alertaCombo");
		}

    	if($("#observacionVigencia").val()==""){
			error = true;
			$("#observacionVigencia").addClass("alertaCombo");
		}
	
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				$('#abrirVigenciaDeclarada').attr('data-opcion','actualizarVigenciaDeclarada');
				ejecutarJson($(this));                             
		}
    });


</script>