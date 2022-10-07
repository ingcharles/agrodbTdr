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

	<p>El <b>siguiente mantenimiento</b> va a ser eliminado: </p>
	
	<?php
		
	$mantenimientos = explode(",",$_POST['elementos']);
	echo '<fieldset><legend>Mantenimiento</legend>';
		
	for ($i = 0; $i < count ($mantenimientos); $i++) {
		$res = $cv->abrirMantenimiento($conexion, $mantenimientos[$i]);
		$mantenimiento = pg_fetch_assoc($res);
	
		echo '<div>'.$mantenimiento['placa'].' - '.$mantenimiento['marca'].' - '.$mantenimiento['motivo'].'</div>';
	}
	
	echo'</fieldset>';
	
	?>
	
 

<form id="notificarEliminarMantenimiento" data-rutaAplicacion="transportes" data-opcion="eliminarMantenimiento" data-accionEnExito="ACTUALIZAR" >

	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
	<fieldset>
		<legend>Observación</legend>

		<div data-linea="1">
			<textarea id="observacion" name="observacion"></textarea>
		</div>
	</fieldset>
	
			<?php 
					for ($i = 0; $i < count ($mantenimientos); $i++) {
						echo'<input type="hidden" name="id[]" value="'.$mantenimientos[$i].'"/>';
						
						$res = $cv->abrirMantenimiento($conexion, $mantenimientos[$i]);
						$mantenimiento = pg_fetch_assoc($res);
					
						echo'<input type="hidden" name="placa[]" value="'.$mantenimiento['placa'].'"/>';
					}
			?>
	
				
	 <button id="eliminar" type="submit" class="eliminar" >Dar de baja mantenimiento</button>
	
</form>

</body>

<script type="text/javascript">

$("#notificarEliminarMantenimiento").submit(function(event){

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

});
	
</script>

</html>
