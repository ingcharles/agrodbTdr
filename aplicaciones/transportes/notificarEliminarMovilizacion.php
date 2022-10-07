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

	<p>La siguiente <b>movilización</b> será eliminada: </p>
	
	<?php
		$movilizaciones= $_POST['elementos'];
		
		echo '<fieldset><legend>Mantenimientos</legend>';
		
		$res = $cv->abrirMovilizacion($conexion, $movilizaciones);
		$movilizacion = pg_fetch_assoc($res);
		
		echo '<div>' .$movilizacion['id_movilizacion'].'-' .$movilizacion['tipo_movilizacion'].' '.($movilizacion['estado']==('1' || '2' || '3') ?'<div id="eliminar"></div>':'<div id="nEliminar" class="alerta">No se puede eliminar porque esta en uso.</div>').'  </div>';
		echo'</fieldset>';
	?>
	
<form id="notificarEliminarMovilizacion" data-rutaAplicacion="transportes" data-opcion="eliminarMovilizacion" data-accionEnExito="ACTUALIZAR" >

	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
	<fieldset>
		<legend>Observación</legend>

		<div data-linea="1">
			<textarea id="observacion" name="observacion"></textarea>
		</div>
	</fieldset>
				<input type="hidden" name="id" value="<?php echo $movilizaciones;?>"/>
				<input type="hidden" name="placa" value="<?php echo $movilizacion['placa'];?>"/>
									
	 <button id="eliminar" type="submit" class="eliminar" >Eliminar movilización</button>
	
</form>

</body>

<script type="text/javascript">

var array_movilizacion= <?php echo json_encode($movilizaciones); ?>;			

$("#notificarEliminarMovilizacion").submit(function(event){

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
	if(array_movilizacion == '')
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una orden de movilización a eliminar.</div>');

	if($("#nEliminar").text()){
		$("#notificarEliminarMovilizacion").hide();
		}

	distribuirLineas();
});

	
</script>

</html>