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

	<p>El <b>vehículo</b> a ser eliminados es: </p>
	
	<?php
		$placas = $_POST['elementos'];
		echo '<fieldset><legend>Vehículos</legend>';
			
		    $res = $cv->abrirVehiculo($conexion, $placas);
			$vehiculo = pg_fetch_assoc($res);
		
			echo '<div>' .$vehiculo['placa'].' - ' .$vehiculo['marca'].' - '.$vehiculo['modelo'].'  '.($vehiculo['estado']=='1'?'<div id="eliminar"></div>':'<div id="nEliminar" class="alerta">No se puede eliminar porque esta en uso.</div>').'  </div>';
		echo'</fieldset>';
	?>
	
 
<form id="notificarEliminarVehiculo" data-rutaAplicacion="transportes" data-opcion="eliminarVehiculo" data-accionEnExito="ACTUALIZAR" >

	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
	<fieldset>
		<legend>Observación</legend>
		<div data-linea="1">
			<textarea id="observacion" name="observacion"></textarea>
		</div>
	</fieldset>
			<input type="hidden" name="id" value="<?php echo $placas;?>"/>
			
	 <button id="eliminar" type="submit" class="eliminar" >Dar de baja vehículo</button>
	
</form>

</body>

<script type="text/javascript">
var array_placa= <?php echo json_encode($placas); ?>;

$("#notificarEliminarVehiculo").submit(function(event){

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

	if(array_placa == '')
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione un vehículo a dar de baja.</div>');

	if($("#nEliminar").text()){
		$("#notificarEliminarVehiculo").hide();
	}

});

</script>

</html>
