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

	<p>El <b>taller</b> a ser eliminado es: </p>
	
	<?php
		$idTaller = ($_POST['elementos']=='' ? 0 : $_POST['elementos']);
		
		echo '<fieldset><legend>Taller</legend>';
			
		    $res = $cv->abrirTaller($conexion, $idTaller);
			$taller = pg_fetch_assoc($res);
		
			echo '<div>' .$taller['nombre'].' - ' .$taller['contacto'].' - '.$taller['localizacion'].'</div>';
		echo'</fieldset>';
		

		$res =$cv->buscarEstadoTaller($conexion,$idTaller);
		$quitarTaller = pg_fetch_assoc($res);
		
		if(!$quitarTaller){
			echo   '<form id="notificarEliminarTaller" data-rutaAplicacion="transportes" data-opcion="eliminarTaller" data-accionEnExito="ACTUALIZAR" >
						<input type="hidden" id="identificadorUsuarioRegistro" name="identificadorUsuarioRegistro" value="'. $identificadorUsuarioRegistro .'" />
	
						<fieldset>
							<legend>Observación</legend>
							<div data-linea="1">
								<textarea id="observacion" name="observacion"></textarea>
							</div>
						</fieldset>
						
						<input type="hidden" name="id" value="'.$idTaller.'"/>
				
		 				<button id="eliminar" type="submit" class="eliminar" >Eliminar taller</button>
		
					</form>';
					
		}else{
			
			echo '<div id="nEliminar" class="alerta">No se puede eliminar porque esta en uso.</div>';
			
		}
		
	?>
	
	
	
 

</body>

<script type="text/javascript">
var array_taller = <?php echo json_encode($idTaller); ?>;

$("#notificarEliminarTaller").submit(function(event){

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
	
	if(array_taller == '')
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione un taller a eliminar.</div>');

});

</script>

</html>
