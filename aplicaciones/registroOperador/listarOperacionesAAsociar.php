<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();

$ca = new ControladorAplicaciones('registroOperador', 'asociarDocumentoAnexo');

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

	<header>
		<h1>Cargar anexos</h1>
	</header>

	<div id="contendorArticulos">

		<?php

		$cr = new ControladorRegistroOperador();

		$res = $cr->listarOperacionesQueRequierenAnexos($conexion, $_SESSION['usuario']);

		while($fila = pg_fetch_assoc($res)){

			$clase = '';
			if($fila['estado']=='cargarAdjunto'){
				$estado = 'Cargar documentos';
			}else{
				$estado = 'Subsanar documentos';
			}

			$nombreArea = $cr->buscarNombreAreaPorSitioPorTipoOperacion($conexion, $fila['id_tipo_operacion'], $identificadorOperador, $fila['id_sitio'], $fila['id_operacion']);

			$codigoSitio = $fila['id_sitio'].'-'.$categoria;
			$nombreSitio = $fila['nombre_lugar'];
			$contenido = '<article
				id="'.$fila['id_operacion'].'"
				class="item"
				data-rutaAplicacion="registroOperador"
				data-opcion="asociarDocumentoAnexo"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<span><small> # '.$fila['id_tipo_operacion'].'-'.$fila['id_sitio'].' </small></span>
                		<span><small>'.(strlen($fila['provincia'])>14?(substr($cr->reemplazarCaracteres($fila['provincia']),0,14).'...'):(strlen($fila['provincia'])>0?$fila['provincia']:'')).'</small></span><br />
								<span><small>'.(strlen($fila['nombre_tipo_operacion'])>30?(substr($cr->reemplazarCaracteres($fila['nombre_tipo_operacion']),0,30).'...'):(strlen($fila['nombre_tipo_operacion'])>0?$fila['nombre_tipo_operacion']:'')).'<b> en </b> '.
								(strlen($nombreArea)>42?(substr($cr->reemplazarCaracteres($nombreArea),0,42).'...'):(strlen($nombreArea)>0?$nombreArea:'')).'</small></span>
						<aside class= "estadoOperador"><small> Estado: '.$estado.'<span><div class= "'.$clase.'"></div></span></small></aside>
					</article>';
			?>
				<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					var subcategoria = <?php echo json_encode($codigoSitio);?>;
					var nombreSitio = <?php echo json_encode($nombreSitio);?>;
					if($("#"+subcategoria).length == 0){
						$("#contendorArticulos").append("<div id= "+subcategoria+"><h2>"+nombreSitio+"</h2><div class='subElementos'></div></div>");
					}
						$("#"+subcategoria+" div.subElementos").append(contenido);
				</script>
		<?php
		}
		?>
	</div>
</body>
<script>
    $(document).ready(function () {
        $("#listadoItems").addClass("comunes");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
    });

</script>
</html>
