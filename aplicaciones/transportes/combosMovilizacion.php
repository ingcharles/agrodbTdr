<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();

$idPadre = $_POST['idMotivoMovilizacion'];

		$subActividades = $cc->listarSubActividadesMovilizacion($conexion, 2, $idPadre);
			
		echo '<label>Detalle</label>
				<select id="subActividad" name="subActividad" required="required">
					<option value="" selected="selected" >Seleccione....</option>';
						while ($fila = pg_fetch_assoc($subActividades)){
							echo '<option value="' . $fila['id_actividad_movilizacion'] . '">' . $fila['nombre_actividad'] . '</option>';
						}
		echo '</select>';

?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

</script>
