<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorImportaciones.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$paisEmbarque = htmlspecialchars ($_POST['paisEmbarque'],ENT_NOQUOTES,'UTF-8');

$qPuerto = $cc -> listarPuertosPorPais($conexion, $paisEmbarque);

echo '<div data-linea="8">
		<label>Puerto Embarque</label>
			<select id="puertoEmbarque" name="puertoEmbarque" style="width:76%;">
			<option value="">Seleccione....</option>';

while ($fila = pg_fetch_assoc($qPuerto)){
	echo '<option value="'.$fila['id_puerto'].'">'.$fila['nombre_puerto'].'</option>';
}	
	
echo 	'	</select>
	  </div>';
?>

<script type="text/javascript">
	$("#puertoEmbarque").change(function(){	
		$('#nombrePuertoEmbarque').val($("#puertoEmbarque option:selected").text());
	});
</script>