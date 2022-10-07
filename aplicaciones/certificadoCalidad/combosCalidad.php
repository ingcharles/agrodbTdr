<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();

$idPaisDestino = htmlspecialchars ($_POST['paisDestino'],ENT_NOQUOTES,'UTF-8');
$tipoPuerto = htmlspecialchars ($_POST['medioTransporte'],ENT_NOQUOTES,'UTF-8');
$opcion =  htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');


switch ($opcion){
	case 'paisDestino':
		
		$puertoPais = $cc->listarPuertosPaisTipo($conexion, $idPaisDestino, $tipoPuerto);
			
			echo '
			<label>Puerto destino</label>
				<select id="puertoDestino" name="puertoDestino" style="width:81%;">
				<option value="">Seleccione....</option>';
			while ($fila = pg_fetch_assoc($puertoPais)){
				echo '<option value="'.$fila['id_puerto'].'">'.$fila['nombre_puerto'].'</option>';
			}
			
			echo '</select>
					<input type="hidden" id="nombrePuertoDestino" name="nombrePuertoDestino" />';
		break;
					
	default:
		echo 'Tipo desconocido';
}


?>

<script type="text/javascript">

$("#puertoDestino").change(function(){
	$('#nombrePuertoDestino').val($('#puertoDestino option:selected').text());
});
	 
</script>