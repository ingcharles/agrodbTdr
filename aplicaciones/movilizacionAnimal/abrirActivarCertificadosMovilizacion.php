<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$cm = new ControladorMovilizacionAnimal();

$res = $cm->filtroActivarCertificadosMovilizacion($conexion, $_POST['id']);
$ActivarCertificados= pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Datos Certificado de movilización</h1>
</header>
	
<form id="datosCertificadoMovilizacion" data-rutaAplicacion="movilizacionAnimal" data-opcion="actualizarActivarCertificadoMovilizacion" data-accionEnExito="ACTUALIZAR">
<p>
	<button id="modificar" type="button" class="editar">Modificar</button>
	<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
</p>

<table class="soloImpresion">
<tr><td>
	
<fieldset>
		<legend>Certificado de movilización seleccionado</legend>
		
		<input type="hidden" name="id_serie_documento" value="<?php echo $ActivarCertificados['id_serie_documento'];?>" />
		<input type="hidden" id="estados" name="estados" value="<?php echo $ActivarCertificados['estado'];?>" />
		<div data-linea="1">			
			<label>Especie: </label><?php echo $ActivarCertificados['nombre_especie'];?>
		</div>
		<div data-linea="1">			
			<label>Tipo Documento: </label><?php echo $ActivarCertificados['tipo_documento'];?>
		</div>		
		<div data-linea="2">			
			<label>Fecha Registro: </label><?php echo $ActivarCertificados['fecha_registro'];?>
		</div>
		<div data-linea="2">			
			<label>No. Certificado: </label><?php echo $ActivarCertificados['numero_documento'];?>
		</div>
		
		
		<div data-linea="3">			
			<label>Fecha Modificación: </label><?php echo $ActivarCertificados['fecha_modificacion'];?>
		</div>
		<div data-linea="4">			
			<label>Observación: </label><?php echo $ActivarCertificados['observacion'];?>
		</div>			
		<table>	
		<tr>
			<td>
				<label>Estado</label>
			</td>									
			<td>
				<input type="radio" name="estado" id="estado1" value="ingresado" disabled="disabled">Activo
				
				<input type="radio" name="estado" id="estado2" value="anulado" disabled="disabled">Anulado
				<input type="radio" name="estado" id="estado3" value="inactivo" disabled="disabled">Inactivo
			</td>		
		</tr>												
		</table>
</fieldset>	
</td>
</tr>
</table>
</form>

</body>

<script type="text/javascript">

$("#modificar").click(function(){
	//$("input").removeAttr("disabled");
	//$("select").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	//$("#estado1").removeAttr("disabled");
	//$("#estado2").removeAttr("disabled");
	$("#estado3").removeAttr("disabled");
	//$(this).attr("disabled","disabled");
	
});

$("#datosCertificadoMovilizacion").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this));
});

$(document).ready(function(){
	distribuirLineas();
	if($("#estados").val() == 'ingresado')
		$("#estado1").attr('checked', true);
	if($("#estados").val() == 'anulado')
	 	$("#estado2").attr('checked', true);
	if($("#estados").val() == 'inactivo')
	 	$("#estado3").attr('checked', true);
});


</script>

</html>