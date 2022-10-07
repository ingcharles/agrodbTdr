<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEtiquetas.php';

$conexion = new Conexion();
$ce = new ControladorEtiquetas();
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
	<p>Solo se eliminaran <b>solicitudes de etiquetas</b> en estado: <b>Enviado</b>.</p>
	
	<?php
		$solicitudes=explode(",",$_POST['elementos']);
		
		if($solicitudes[0]!=null){
		
		for ($i = 0; $i < count ($solicitudes); $i++) {
			$qDatosSolicitud=$ce->abrirSolicitudEtiquetasEnviada($conexion,$solicitudes[$i]);
			echo '<fieldset>
					<legend>Solicitud '.$qDatosSolicitud[0]['numeroSolicitud'].'</legend>
						<div>
							 <label>Operador: </label>' .$qDatosSolicitud[0]['identificador'].'<br/>' .
									 '<label>Razón Social: </label>' .$qDatosSolicitud[0]['razonSocial']. '<br/>' .
									 '<label>Número Solicitud: </label>' .$qDatosSolicitud[0]['numeroSolicitud']. '<br/>' .
									 '<label>Estado: </label>' .$qDatosSolicitud[0]['estado']. '<br/>' .
									 ($qDatosSolicitud[0]['estado']=='Enviado'?'':'<div id="nEliminar" class="alerta">No se puede eliminar esta solicitud porque esta en uso.</div>').	 
							   '</div>
				  </fieldset>';
			}
		}

	?>
	
 
<form id="notificarEliminarEtiquetas" data-rutaAplicacion="etiquetas" data-opcion="eliminarSolicitarEtiquetas" data-destino="detalleItem">
	<input type="hidden" name="id" value="<?php echo $_POST['elementos'];?>"/>
	<button id="eliminar" type="submit" class="eliminar" >Eliminar</button>
</form>

</body>

<script type="text/javascript">
var array_solicitud= <?php echo json_encode($solicitudes); ?>;

$(document).ready(function(){
	distribuirLineas();
	construirValidador();
	if(array_solicitud == '')
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione un item para eliminar.</div>');
});

$("#notificarEliminarEtiquetas").submit(function(event){
	abrir($(this),event, false);
});
</script>
</html>