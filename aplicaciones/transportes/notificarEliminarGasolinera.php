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

	<p>La <b>gasolinera</b> a ser eliminada es: </p>
	
	<?php
		$idGasolinera = ($_POST['elementos']=='' ? 0 : $_POST['elementos']);
		
		echo '<fieldset><legend>Gasolinera</legend>';
			
		    $res = $cv->abrirGasolinera($conexion, $idGasolinera);
			$gasolinera = pg_fetch_assoc($res);
		
			echo '<div>' .$gasolinera['nombre'].' - ' .$gasolinera['contacto'].' - '.$gasolinera['localizacion'].'</div>';
		echo'</fieldset>';
		

		$res =$cv->buscarEstadoGasolinera($conexion,$idGasolinera);
		$quitarGasolinera = pg_fetch_assoc($res);
		
		if(!$quitarGasolinera){
			echo   '<form id="notificarEliminarGasolinera" data-rutaAplicacion="transportes" data-opcion="eliminarGasolinera" data-accionEnExito="ACTUALIZAR" >
						<input type="hidden" id="identificadorUsuarioRegistro" name="identificadorUsuarioRegistro" value="'. $identificadorUsuarioRegistro .'" />
	
						<fieldset>
							<legend>Observación</legend>
							<div  data-linea="1">
								<textarea id="observacion" name="observacion"></textarea>
							</div>
						</fieldset>
						
						<input type="hidden" name="id" value="'.$idGasolinera.'"/>
				
		 				<button id="eliminar" type="submit" class="eliminar" >Eliminar Gasolinera</button>
		
					</form>';
					
		}else{
			
			echo '<div id="nEliminar" class="alerta">No se puede eliminar porque esta en uso.</div>';
			
		}
		
	?>
	
	
	
 

</body>

<script type="text/javascript">
var array_gasolinera = <?php echo json_encode($idGasolinera); ?>;

$("#notificarEliminarGasolinera").submit(function(event){

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
	
	if(array_gasolinera == '')
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una gasolinera a eliminar.</div>');

});

</script>

</html>
