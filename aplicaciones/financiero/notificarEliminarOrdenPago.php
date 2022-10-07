<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorFinanciero.php';
	
	$conexion = new Conexion();
	$cf = new ControladorFinanciero();
	
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

	<p>La <b>Orden de pago</b> a ser eliminado es: </p>
	
	<?php
		$idOrden = ($_POST['elementos']=='' ? 0 : $_POST['elementos']);
		
		echo '<fieldset><legend>Orden de Pago</legend>';
			
		    $res = $cf->abrirOrdenPago ($conexion, $idOrden);
			$orden = pg_fetch_assoc($res);
		
			echo '<div>' .$orden['numero_solicitud'].' - ' .$orden['fecha_orden_pago'].' - '.$orden['localizacion'].'</div>';
		echo'</fieldset>';
		

		$res =$cf->buscarEstadoOrdenPago($conexion,$idOrden);
		$quitarOrdenPago = pg_fetch_assoc($res);
		
		if(!$quitarTaller){
			echo   '<form id="notificarEliminarOrdenPago" data-rutaAplicacion="financiero" data-opcion="eliminarOrdenPago" data-accionEnExito="ACTUALIZAR" >
						<input type="hidden" id="identificadorUsuarioRegistro" name="identificadorUsuarioRegistro" value="'. $identificadorUsuarioRegistro .'" />
	
						<fieldset>
							<legend>Observación</legend>
							<div data-linea="1">
								<textarea id="observacion" name="observacion"></textarea>
							</div>
						</fieldset>
						
						<input type="hidden" name="id" value="'.$idOrden.'"/>
				
		 				<button id="eliminar" type="submit" class="eliminar" >Eliminar Orden</button>
		
					</form>';
					
		}else{
			
			echo '<div id="nEliminar" class="alerta">No se puede eliminar la orden de pago porque esta en uso.</div>';
			
		}
		
	?>
	
</body>

<script type="text/javascript">
var array_orden = <?php echo json_encode($idOrden); ?>;

$("#notificarEliminarOrdenPago").submit(function(event){

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
	if(array_orden == '')
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una orden de pago a eliminar.</div>');

});

</script>

</html>
