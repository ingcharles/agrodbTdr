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

	<p>El <b>Ingreso de caja</b> a ser eliminado es: </p>
	
	<?php
		$idOrden = ($_POST['elementos']=='' ? 0 : $_POST['elementos']);
		
		echo '<fieldset><legend>Ingreso de Caja</legend>';
			
		    $res = $cf->abrirOrdenPago ($conexion, $idOrden);
			$orden = pg_fetch_assoc($res);
		
			echo '<div>' .$orden['numero_solicitud'].' - ' .$orden['fecha_orden_pago'].' - '.$orden['localizacion'].'</div>';
		echo '</fieldset>';
		

		$res =$cf->buscarEstadoOrdenPago($conexion,$idOrden);
		$quitarIngresoCaja = pg_fetch_assoc($res);
		
		if(!$quitarIngresoCaja){
			echo   '<form id="notificarEliminarIngresoCaja" data-rutaAplicacion="financiero" data-opcion="eliminarOrdenPago" data-accionEnExito="ACTUALIZAR" >
						<input type="hidden" id="identificadorUsuarioRegistro" name="identificadorUsuarioRegistro" value="'. $identificadorUsuarioRegistro .'" />
	
						<fieldset>
							<legend>Observación</legend>
							<div data-linea="1">
								<textarea id="observacion" name="observacion"></textarea>
							</div>
						</fieldset>
						
						<input type="hidden" name="id" value="'.$idOrden.'"/>
				
		 				<button id="eliminar" type="submit" class="eliminar" >Eliminar Ingreso de caja</button>
		
					</form>';
					
		}else{
			
			echo '<div id="nEliminar" class="alerta">No se puede eliminar el ingreso de caja porque esta en uso.</div>';
			
		}
		
	?>
	
</body>

<script type="text/javascript">
var array_orden = <?php echo json_encode($idOrden); ?>;

$("#notificarEliminarIngresoCaja").submit(function(event){

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
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione un ingreso de caja a eliminar.</div>');

});

</script>

</html>
