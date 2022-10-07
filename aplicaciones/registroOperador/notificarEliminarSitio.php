<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
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

	<p>El <b>sitio</b> a ser eliminado es: </p>
	
	<?php
		$idSitio = $_POST['elementos'];
		echo '<fieldset><legend>Sitio</legend>';
			
		    $res = $cr->abrirSitio($conexion, $idSitio);
			$sitio = pg_fetch_assoc($res);
			
			$res = $cr->listarAreaOperador($conexion, $idSitio);
			
			while($fila = pg_fetch_assoc($res)){
				if($fila['estado']=='enviado'){
					$estado=1;
				}
			}
			echo '<div>' .$sitio['nombre_lugar'].' - ' .$sitio['parroquia'].' - '.$sitio['direccion'].'  '.($estado==1?'<div id="eliminar"></div>':'<div id="nEliminar" class="alerta">No se puede eliminar porque esta en uso.</div>').'  </div>';
		echo'</fieldset>';
	?>
	
 
<form id="notificarEliminarSitio" data-rutaAplicacion="registroOperador" data-opcion="eliminarSitio" data-accionEnExito="ACTUALIZAR" >

	<input type="hidden" name="id" value="<?php echo $sitio;?>"/>
			
	 <button id="eliminar" type="submit" class="eliminar" >Eliminar sitio</button>
	
</form>

</body>

<script type="text/javascript">
var array_sitio= <?php echo json_encode($sitio); ?>;

$("#notificarEliminarSitio").submit(function(event){

	 if($("#observacion").val()=="") {
	    	$("#observacion").focus();
	    	$("#observacion").css("background-color","red");
	        alert("Debe ingresar una observación");
	        return false;
	  }else{
		  	event.preventDefault();
			ejecutarJson($(this));
	  }
});

$(document).ready(function(){

	if(array_placa == '')
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione un sitio para eliminar.</div>');

	if($("#nEliminar").text()){
		$("#notificarEliminarSitio").hide();
	}

});

</script>

</html>
