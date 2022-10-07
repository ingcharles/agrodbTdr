<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';


$conexion = new Conexion();
$cpoa = new ControladorPAPP();

$res = $cpoa->abrirObjetivo($conexion, $_POST['id']);
$objetivo = pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Detalle de objetivo estratégico</h1>
	</header>
	
	<form id="objetivo" data-rutaAplicacion="poa" data-opcion="actualizarObjetivo">
	<input type="hidden" name="idObjetivo" value="<?php echo $objetivo['id_objetivo'];?>"/>

	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	<div id="estado"></div>
	
				<fieldset>
					<legend>Detalle</legend>
						<div data-linea="1">			
							<label>Objetivo: </label>
						</div>
						<div data-linea="2">
								<input type="text" id="descripcion" name="descripcion" disabled="disabled" value="<?php echo $objetivo['descripcion'];?>" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
						</div>
				</fieldset>

</form>

</body>

<script type="text/javascript">
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		
	});
	
	$("#objetivo").submit(function(event){
		/*if($("#descripcion").val()==""){
	    	$("#descripcion").focus();
	    	$("#descripcion").css("background-color","#ed4e76");
	        alert("Debe ingresar una descripción");
	    }
	    else{*/
	    	event.preventDefault();
	    	chequearCampos(this);
	    	//ejecutarJson($(this));
	    	//$("#_actualizar").click();
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
			$("#_actualizar").click();
		}
	}

</script>

</html>