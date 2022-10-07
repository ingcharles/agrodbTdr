<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new controladorVehiculos();

$identificadorUsuarioRegistro = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Confirmar eliminación</h1>
</header>

<div id="estado"></div>

	<p>La <b>orden de lavado</b> a ser eliminado es: </p>
	
	<?php
	
	$lavadas = ($_POST['elementos']);
	echo '<fieldset><legend>Lavado</legend>';
		
		$res = $cv->abrirMantenimiento($conexion, $lavadas);
		$lavada = pg_fetch_assoc($res);
	
		echo '<div>' .$lavada['id_mantenimiento'].'-' .$lavada['motivo'].'-'.$lavada['placa'].' '.($lavada['estado']=='1'?'<div id="eliminar"></div>':'<div id="nEliminar" class="alerta">No se puede eliminar en estado de liquidación.</div>').'  </div>';
		
		
	echo'</fieldset>';
	?>
	
 

<form id="notificarEliminarLavado" data-rutaAplicacion="transportes" data-opcion="eliminarLavado" data-accionEnExito="ACTUALIZAR" >

	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
	<fieldset>
		<legend>Observación</legend>

		<div data-linea="1">
			<textarea id="observacion" name="observacion"></textarea>
		</div>
	</fieldset>
			<input type="hidden" name="id" value="<?php echo $lavadas;?>"/>
			<input type="hidden" name="placa" value="<?php echo $lavada['placa'];?>"/>
							
	 <button id="eliminar" type="submit" class="eliminar" >Eliminar orden de lavada</button>
	
</form>

</body>

<script type="text/javascript">

var array_lavada= <?php echo json_encode($lavadas); ?>;			

$("#notificarEliminarLavado").submit(function(event){

	 if($("#observacion").val()=="") {
	    	$("#observacion").focus();
	    	$("#observacion").addClass("alertaCombo");
	        alert("Debe ingresar una observación");
	        return false;
	  }else{
		  	event.preventDefault();
			ejecutarJson($(this));
	  }
});


$(document).ready(function(){

	distribuirLineas();
	construirValidador();

	if(array_lavada == ''){
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una orden de lavado.</div>');
	}

	if($("#nEliminar").text()){
		$("#notificarEliminarLavado").hide();
		}

});

</script>

</html>
