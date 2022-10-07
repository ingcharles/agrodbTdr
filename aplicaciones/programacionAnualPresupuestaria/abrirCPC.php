<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$conexion = new Conexion();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$cpc = pg_fetch_assoc($cpp->abrirCPC($conexion, $_POST['id']));
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">	
	</head>
	
	<body>
		<header>
			<h1>CPC</h1>
		</header>
		
		<form id="cpc" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarCPC" data-accionEnExito="ACTUALIZAR">
			<input type="hidden" id="idCPC" name="idCPC" value="<?php echo $cpc['id_cpc'];?>" />
			
			<p>
				<button id="modificar" type="button" class="editar">Modificar</button>
				<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
			</p>
			<div id="estado"></div>
			
			<fieldset>
				<legend>CPC</legend>

				<div data-linea="1">
					<label>Nombre:</label>
					<input type="text" id="nombreCPC" name="nombreCPC" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $cpc['nombre'];?>" disabled="disabled" />
				</div>
				
				<div data-linea="2">
					<label>Código:</label>
					<input type="text" id="codigoCPC" name="codigoCPC" maxlength="11" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $cpc['codigo'];?>" disabled="disabled" />
				</div>
				<div data-linea="2">
					<label>Nivel:</label>
					<input type="text" id="nivelCPC" name="nivelCPC" maxlength="9" data-er="^[0-9]+$" value="<?php echo $cpc['nivel'];?>" disabled="disabled" />
				</div>
				
			</fieldset>
		
		</form>
	
	</body>

	<script type="text/javascript">
		
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

			if(!$.trim($("#nombreCPC").val()) || !esCampoValido("#nombreCPC")){
				error = true;
				$("#nombreCPC").addClass("alertaCombo");
			}

			if(!$.trim($("#codigoCPC").val()) || !esCampoValido("#codigoCPC")){
				error = true;
				$("#codigoCPC").addClass("alertaCombo");
			}

			if(!$.trim($("#nivelCPC").val()) || !esCampoValido("#nivelCPC")){
				error = true;
				$("#nivelCPC").addClass("alertaCombo");
			}
			
			if (error){
				$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				ejecutarJson(form);
			}
		}
	
		$("#modificar").click(function(){
			$("input").removeAttr("disabled");
			$("#actualizar").removeAttr("disabled");
			$(this).attr("disabled","disabled");			
		});
		
		$("#cpc").submit(function(event){
			event.preventDefault();
		    chequearCampos(this);  	
		});
	
	</script>
</html>