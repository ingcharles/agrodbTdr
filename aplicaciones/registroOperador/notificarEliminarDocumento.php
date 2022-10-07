<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();

$elementosSeleccionados = $_POST['elementos'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>



	<div id="estado"></div>

	<div id="eliminarDocumento">

		<?php 

		$arrayDocumentos = explode(",",$_POST['elementos']);



		foreach ($arrayDocumentos as $idDocumento){

			$arrayOperaciones = array();

			$documento = pg_fetch_assoc($cr->abrirDocumento($conexion, $idDocumento));
			$estadoSolicitud = $cr->notificarEliminarDocumentoAnexoOperacion($conexion, $idDocumento);

			echo '<fieldset id="fi_'.$idDocumento.'">
		<legend>Documento anexo</legend>
		<div data-linea="1">
		<a href="'.$documento['ruta_documento'].'target="_blank">
		'.$documento['descr'].' ('.$documento['nombre_documento'].')
					</a>
					</div>';
			while ($fila = pg_fetch_assoc($estadoSolicitud)){
				$arrayOperaciones[] = $fila['estado'];
			}

			$arrayOperaciones = (count($arrayOperaciones)!=0 ?array_unique($arrayOperaciones):$arrayOperaciones) ;

			if(count($arrayOperaciones) == 0 ){

				echo '<form id="f_'.$idDocumento.'" data-rutaAplicacion="registroOperador" data-opcion="eliminarDocumento" data-accionEnExito="ACTUALIZAR" >
				<input type="hidden" name="idDocumento" value="'.$idDocumento.'"/>
						<button id="eliminar" type="submit" class="eliminar" >Eliminar documento</button>
						</form>';
					
			}else if(count($arrayOperaciones) == 1 && $arrayOperaciones[0] == 'subsanacion'){

				echo '<form id="f_'.$idDocumento.'" data-rutaAplicacion="registroOperador" data-opcion="eliminarDocumento" data-accionEnExito="ACTUALIZAR" >
						<input type="hidden" name="idDocumento" value="'.$idDocumento.'"/>
								<button id="eliminar" type="submit" class="eliminar" >Eliminar documento</button>
								</form>';
					
			}else{

			echo '<br><div class="alerta">El documento no puede ser eliminado.</div>';

		}

		echo'</fieldset>';
		}

		?>

	</div>

</body>

<script type="text/javascript">

var array_documento = <?php echo json_encode($elementosSeleccionados); ?>;		
			
$(document).ready(function(){
	if(array_documento == ''){
		$("#detalleItem").html('<div class="mensajeInicial">Por favor seleccione uno o varios documentos para eliminar.</div>');
	}
});

$("#eliminarDocumento").on("submit","form",function(event){
  	event.preventDefault();
	ejecutarJson($(this));
	if($("#estado").html()=='El documento ha sido eliminado.'){
		$('#'+$(this).parent().attr('id')).hide();
	}	
});
	
</script>

</html>
