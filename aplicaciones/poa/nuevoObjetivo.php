<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$fecha = getdate();
?>

<header>
	<h1>Nuevo objetivo estratégico</h1>
</header>
<form id="nuevoObjetivo" data-rutaAplicacion="poa" data-opcion="guardarNuevoObjetivo" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
	<input type="hidden" name="anio" value="<?php echo $fecha['year'];?>"/>
	
	<fieldset>
		<legend>Descripción del nuevo objetivo</legend>
		<div data-linea="1">
			<input type="text" id="descripcion" name="descripcion" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
	</fieldset>
	
	<button type="submit" class="guardar">Generar Objetivo</button>

</form>
<script type="text/javascript">

	$("#nuevoObjetivo").submit(function(event){
	   /* if($("#descripcion").val()==""){
	    	$("#descripcion").focus();
	    	$("#descripcion").css("background-color","#ed4e76");
	        alert("Debe ingresar una descripción");
	        return false;
	    }
	    else{*/
	    	event.preventDefault();
	    	chequearCampos(this);
	    	//ejecutarJson($(this));
			//abrir($(this),event,false);
	    //}    
	});

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
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
		}
	}
	
</script>