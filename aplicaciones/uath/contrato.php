<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';



$conexion = new Conexion();
$cc = new ControladorCatastro();
$res = $cc->obtenerDatosContrato($conexion, $_POST['id']);
$contrato = pg_fetch_assoc($res);


?>

<header>
	<h1>Datos Contrato</h1>
</header>

<form id="datosContrato" data-rutaAplicacion="uath" data-opcion="guardarContrato">
	<input type="hidden" id="<?php echo $_SESSION['usuario'];?>" />

	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	<div id="estado"></div>
	<table class="soloImpresion">
	<tr><td>
	</td><td>
	<fieldset>
		<legend>Contrato</legend>
		<label>Tipo de Contrato</label> 
			<input type="text" name="tipo_contrato" value="<?php echo $contrato['tipo_contrato'];?>" disabled="disabled" /> 
		<label>Número Contrato</label>
			<input type="text" name="numero_contrato" 	value="<?php echo $contrato['numero_contrato'];?>" disabled="disabled" /> 
		<label>Fecha Inicio</label>
			<input type="text"	id="fecha_inicio" name="fecha_inicio"	value="<?php echo date('j/n/Y',strtotime($contrato['fecha_inicio']));?>" disabled="disabled" />
		<label>Fecha Fin</label>
			<input type="text"	id="fecha_fin" name="fecha_fin"	value="<?php echo date('j/n/Y',strtotime($contrato['fecha_fin']));?>" disabled="disabled" />
		<label>Observación</label>
			<input type="text" name="obsevacion" value="<?php echo $contrato['obsevacion'];?>" disabled="disabled" />
		<label>Archivo Contrato</label>
			<input type="text" name="archivo_contrato" value="<?php echo $contrato['archivo_contrato'];?>" disabled="disabled" />
		<label>Regimen Laboral</label>
			<input type="text" name="regimen_laboral" value="<?php echo $contrato['regimen_laboral'];?>" disabled="disabled" />
		<label>Número Notaría</label>
			<input type="text" name="numero_notaria" value="<?php if($contrato['numero_notaria']==''){echo '0';} else {echo $contrato['numero_notaria'];};?>" disabled="disabled" />
		<label>Lugar Notaria</label>
			<input type="text" name="lugar_notaria" value="<?php echo $contrato['lugar_notaria'];?>" disabled="disabled" />
		<label>Fecha Declaración</label>
			<input type="text"	id="fecha_declaracion" name="fecha_declaracion"	value="<?php echo date('j/n/Y',strtotime($contrato['fecha_declaracion']));?>" disabled="disabled" />
		
	</fieldset>
	</td></tr></table>
</form>

<script type="text/javascript">

	$("#datosContrato").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
		$("input").attr("disabled","disabled");
		$("select").attr("disabled","disabled");
		$("#modificar").removeAttr("disabled");
		$("#actualizar").attr("disabled","disabled");
	});
  
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		
	});

	$(document).ready(function(){
		
		$( "#fecha_inicio" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		$( "#fecha_fin" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		   $( "#fecha_declaracion" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		   
		abrir($("#datosContrato input:hidden"),null,false);
	});

	
</script>
