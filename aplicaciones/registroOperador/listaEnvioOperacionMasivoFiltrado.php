<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAplicaciones.php';


$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cro = new ControladorRegistroOperador();

$identificador=$_SESSION['usuario'];

function reemplazarCaracteres($cadena){
	$cadena = str_replace('á', 'a', $cadena);
	$cadena = str_replace('é', 'e', $cadena);
	$cadena = str_replace('í', 'i', $cadena);
	$cadena = str_replace('ó', 'o', $cadena);
	$cadena = str_replace('ú', 'u', $cadena);
	$cadena = str_replace('ñ', 'n', $cadena);

	$cadena = str_replace('Á', 'A', $cadena);
	$cadena = str_replace('É', 'E', $cadena);
	$cadena = str_replace('Í', 'I', $cadena);
	$cadena = str_replace('Ó', 'O', $cadena);
	$cadena = str_replace('Ú', 'U', $cadena);
	$cadena = str_replace('Ñ', 'N', $cadena);

	return $cadena;
}


$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$idOperacion = htmlspecialchars ($_POST['idOperacion'],ENT_NOQUOTES,'UTF-8');
$idArea = htmlspecialchars ($_POST['idArea'],ENT_NOQUOTES,'UTF-8');
$nombreOperacion = htmlspecialchars ($_POST['nombreOperacion'],ENT_NOQUOTES,'UTF-8');

echo'<header> <nav>';
	$res = $ca->obtenerAccionesPermitidas($conexion, $opcion, $identificador);
	while($fila = pg_fetch_assoc($res)){
	
			echo '<a href="#"
							id="' . $fila['estilo'] . '"
							data-destino="detalleItem"
							data-opcion="' . $fila['pagina'] . '"
							data-rutaAplicacion="' . $fila['ruta'] . '"
							>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
		
	
	}
echo'</nav></header>';

?>
<div>
	<h2><?php echo $nombreOperacion ?> </h2>
</div>
<?php 
	

$qOperadores = $cro->listarProductosXOperacionXAreas($conexion,$idArea,$idOperacion);
$contador = 0;

while($fila = pg_fetch_assoc($qOperadores)){

	switch ($fila['estado']){

		case 'registrado':
			$estado = 'aprobada';
			$clase = 'circulo_verde';
	
			break;
				
		case 'rechazado':
			$estado = 'rechazada';
			$clase = 'circulo_rojo';
			break;
				
		case 'cancelado':
			$estado = 'Cancelado';
			$clase = 'circulo_rojo';
			break;
				
		case 'anulado':
			$estado = 'Anulado';
			$clase = 'circulo_rojo';
			break;
				
		case 'noHabilitado':
			$estado = 'No Habilitado';
			$clase = 'circulo_rojo';
			break;
		case 'registradoObservacion':
			$estado = 'aprobada con observación';
			$clase = 'circulo_amarillo';
			break;
				
		case 'inactivo':
			$estado = 'Inactivo';
			$clase = 'circulo_amarillo';
			break;
				
		case 'inspeccion':
			$estado = 'por asignar inspector';
			$clase = '';
			break;
				
		case 'asignadoInspeccion':
			$estado = 'asigando a inspector';
			$clase = '';
			break;
				
		case 'pago':
			$estado = 'por asignar valor';
			$clase = '';
			break;
				
		case 'temporal':
			$estado = 'Solicitud temporal';
			$clase = 'tmp';
			break;
				
		case 'cargarAdjunto':
			$estado = 'Adjunto';
			$clase = '';
			break;
				
		case 'subsanacion':
			$estado = 'Subsanación';
			$clase = '';
			break;
				
		case 'cargarIA':
			$estado = 'Información';
			$clase = '';
			break;
	
		default:
			$estado = 'ninguna';
			$clase = '';
	}
	
	echo '<article
						id="'.$fila['id_producto'].'"
						class="item"
						data-rutaAplicacion="registroOperador"
						data-opcion="abrirEnvioOperacionesMasivo"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
						<span class="ordinal">'.++$contador.'</span>
						<span><div class= "'.$clase.'"></div> # '.$fila['id_operacion'].'</span><br />
						<span><small>'.(strlen($fila['provincia'])>25?(substr(reemplazarCaracteres($fila['provincia']),0,25).'...'):(strlen($fila['provincia'])>0?$fila['provincia']:'')).'</span><br />
						<span>'.(strlen($fila['nombre'])>30?(substr(reemplazarCaracteres($fila['nombre']),0,30).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'')).' - '.(strlen($fila['nombre_producto'])>25?(substr(reemplazarCaracteres($fila['nombre_producto']),0,25).'...'):(strlen($fila['nombre_producto'])>0?$fila['nombre_producto']:'')).'</small></span>
						<aside> Estado: '.$estado.'</aside>
					</article>';
}
?>

<script type="text/javascript"> 

	$("#listadoItems").addClass("comunes");
	$('#_agrupar').attr('data-rutaaplicacion','registroOperador');
	$('#_agrupar').attr('data-opcion','abrirEnvioOperacionesMasivo');
	//$('#_agrupar').attr('data-idOpcion','abrirEnvioOperacionesMasivo');
	
</script>

