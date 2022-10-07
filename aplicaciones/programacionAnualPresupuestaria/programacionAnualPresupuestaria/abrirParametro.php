<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$conexion = new Conexion();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$parametros = pg_fetch_assoc($cpp->abrirParametros($conexion, $_POST['id']));
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">	
	</head>
	
	<body>
		<header>
			<h1>Parámetros</h1>
		</header>
		
		<form id="parametro" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarParametro" data-accionEnExito="ACTUALIZAR">
			
			<p>
				<button id="modificar" type="button" class="editar">Modificar</button>
				<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
			</p>
			<div id="estado"></div>
			
			<fieldset>
				<legend>Parámetros Anuales</legend>
		
				<div data-linea="1">
					<label>Ejercicio:</label>
					<input type="text" id="ejercicio" name="ejercicio" maxlength="4" readonly="readonly" disabled="disabled" value="<?php echo $parametros['ejercicio'];?>"/>
				</div>
				<div data-linea="1">
				<label>Entidad:</label>
					<input type="text" id="entidad" name="entidad" maxlength="3" data-er="^[0-9]+$" disabled="disabled" value="<?php echo $parametros['entidad'];?>"/>
				</div>
				
				<div data-linea="2">
					<label>Subprograma:</label>
					<input type="text" id="subprograma" name="subprograma" maxlength="2" data-er="^[0-9]+$" disabled="disabled" value="<?php echo $parametros['subprograma'];?>"/>
				</div>
				<div data-linea="2">
					<label>Renglón Aux:</label>
					<input type="text" id="renglonAux" name="renglonAux" maxlength="6" data-er="^[0-9]+$" disabled="disabled" value="<?php echo $parametros['renglon_auxiliar'];?>"/>
				</div>
				
				<div data-linea="3">
					<label>Fuente:</label>
					<input type="text" id="fuente" name="fuente" maxlength="3" data-er="^[0-9]+$" disabled="disabled" value="<?php echo $parametros['fuente'];?>"/>
				</div>
				<div data-linea="3">
					<label>Organismo:</label>
					<input type="text" id="organismo" name="organismo" maxlength="4" data-er="^[0-9]+$" disabled="disabled" value="<?php echo $parametros['organismo'];?>" />
				</div>
				
				<div data-linea="4">
					<label>Correlativo:</label>
					<input type="text" id="correlativo" name="correlativo" maxlength="4" data-er="^[0-9]+$" disabled="disabled" value="<?php echo $parametros['correlativo'];?>"/>
				</div>
				<div data-linea="4">
					<label>Obra:</label>
					<input type="text" id="obra" name="obra" maxlength="3" data-er="^[0-9]+$" disabled="disabled" value="<?php echo $parametros['obra'];?>"/>
				</div>
				
				<div data-linea="5">
					<label>Operación BID:</label>
					<input type="text" id="operacionBid" name="operacionBid" maxlength="2" data-er="^[A-Z]+$" disabled="disabled" value="<?php echo $parametros['codigo_operacion_bid'];?>"/>
				</div>
				<div data-linea="5">
					<label>Proyecto Bid:</label>
					<input type="text" id="proyectoBid" name="proyectoBid" maxlength="2" data-er="^[A-Z]+$" disabled="disabled" value="<?php echo $parametros['codigo_proyecto_bid'];?>"/>
				</div>
				
				<div data-linea="6">
					<label>IVA Ejercicio:</label>
					<input type="text" id="iva" name="iva" maxlength="2" data-er="^[0-9]+$" disabled="disabled" value="<?php echo $parametros['iva'];?>" />
				</div>
				<div data-linea="6">
					
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

			if(!$.trim($("#operacionBid").val()) || !esCampoValido("#operacionBid")){
				error = true;
				$("#operacionBid").addClass("alertaCombo");
			}

			if(!$.trim($("#proyectoBid").val()) || !esCampoValido("#proyectoBid")){
				error = true;
				$("#proyectoBid").addClass("alertaCombo");
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
		
		$("#parametro").submit(function(event){
			event.preventDefault();
		    chequearCampos(this);  	
		});
	
	</script>
</html>