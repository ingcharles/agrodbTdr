<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$cm = new ControladorMovilizacionAnimal();
$vdr = new ControladorVacunacionAnimal();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
print_r($_POST);

if($opcion==1){//Búsqueda del evento de movilización = fereias y centro de exposicion 
	$tipoSitio = 1;//htmlspecialchars ($_POST['tipoBusquedaSitio'],ENT_NOQUOTES,'UTF-8');
	$varSitio = '1712154488001';//htmlspecialchars ($_POST['txtSitioBusqueda'],ENT_NOQUOTES,'UTF-8');
	$sitios = $vdr->listaSitioEvento($conexion,$tipoSitio, $varSitio);
	echo '<label>Nombre Evento: </label>';
	echo '<select id="cmbSitio" name="cmbSitio">';
	echo '<option value="0">Seleccione sitio....</option>';
	while ($fila = pg_fetch_assoc($sitios)){
		echo '<option value="'. $fila['id_sitio'].'" data-area="'. $fila['id_area'].'" data-identificador="'. $fila['identificador_operador'].'">'.$fila['identificador_operador'].' - '.$fila['granja'].' - '.$fila['provincia'].' </option>';
	}
	echo '</select>';
	
}

if($opcion==10){			
	$datos = array(
			'id_sitio' => htmlspecialchars ($_POST['cmbSitio'],ENT_NOQUOTES,'UTF-8'),
			'id_area' => htmlspecialchars ($_POST['id_area'],ENT_NOQUOTES,'UTF-8'),
			'identificador_evento' => htmlspecialchars ($_POST['identificador_evento'],ENT_NOQUOTES,'UTF-8'),				
			'nombre_evento' => htmlspecialchars ($_POST['nombre_evento'],ENT_NOQUOTES,'UTF-8'),
		    'fecha_inicio_evento' => htmlspecialchars ($_POST['fecha_inicio'],ENT_NOQUOTES,'UTF-8'), 
		    'fecha_fin_evento' => htmlspecialchars ($_POST['fecha_fin'],ENT_NOQUOTES,'UTF-8'),
			'usuario_reponsable' => htmlspecialchars ($_POST['usuario_responsable'],ENT_NOQUOTES,'UTF-8'),
			'estado' => 'activo'
			);
	
	$dEvento = $cm->guardarInicioEventoMovilizacion($conexion, $datos['id_sitio'], $datos['id_area'], $datos['identificador_evento'], $datos['nombre_evento']				
			, $datos['fecha_inicio_evento'], $datos['fecha_fin_evento'], $datos['usuario_reponsable'], $datos['estado']);
	
	echo " --> guardar evento de movilización";
	$conexion->desconectar();
}	
?>
</body>
<script type="text/javascript">
	var array_area = <?php echo json_encode($areas); ?>;
	
	$("#cmbSitio").change(function(){		
    	if ($("#cmbSitio").val() != 0){			
			$("#id_area").val($('#cmbSitio option:selected').attr('data-area'));				   				
			$("#identificador_evento").val($('#cmbSitio option:selected').attr('data-identificador'));
		}
	});

</script>
</html>






