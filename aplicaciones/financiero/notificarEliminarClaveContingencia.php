<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorFinanciero.php';
	
	$conexion = new Conexion();
	$cf = new ControladorFinanciero();
	
	$identificadorUsuario = $_SESSION['usuario'];

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

	<p>El <b>período </b> a ser eliminado es: </p>
	
	<?php
		$idClave = ($_POST['elementos']=='' ? 0 : $_POST['elementos']);
		
		echo '<fieldset><legend>Clave Contingencia</legend>';
			
		    $res = $cf->abrirClavesContingencia ($conexion, $idClave);
			$listaClave = pg_fetch_assoc($res);
		
			echo '<div><label>Fecha desde: </label>' .$listaClave['fecha_desde'].' - <label>Fecha hasta: </label>' .$listaClave['fecha_hasta'].'</div>';
		echo'</fieldset>';
		
		$res =$cf->buscarEstadoClaveContingencia($conexion,$idClave);
		$quitarClaveContingencia = pg_fetch_assoc($res);
		
		
		if(!$quitarClaveContingencia){
			echo   '<form id="notificarEliminarClaveContigencia" data-rutaAplicacion="financiero" data-opcion="eliminarClaveContingencia" data-accionEnExito="ACTUALIZAR" >
						<input type="hidden" id="identificadorUsuario" name="identificadorUsuario" value="'.$identificadorUsuario.'" />
						<input type="hidden" id="idClaveContingencia" name="idClaveContingencia" value="'. $listaClave['id_clave_contingencia'] .'" />
	
						<fieldset>
							<legend>Observación</legend>
							<div data-linea="1">
								<textarea id="observacion" name="observacion"></textarea>
							</div>
						</fieldset>
						
						<button id="eliminar" type="submit" class="eliminar" >Eliminar período</button>
		
					</form>';
					
		}else{
			
			echo '<div id="nEliminar" class="alerta">No se puede eliminar el período porque esta en uso.</div>';
			
		}
		
	?>
	
</body>

<script type="text/javascript">
var array_clave = <?php echo json_encode($idClave); ?>;

$("#notificarEliminarClaveContigencia").submit(function(event){

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
	if(array_clave == '')
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una item a eliminar.</div>');

});

</script>

</html>
