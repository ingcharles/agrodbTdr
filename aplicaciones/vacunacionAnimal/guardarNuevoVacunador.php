<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

if($opcion==1){// Busqueda de los sitios por especie
	$id_administrador_vacunacion = htmlspecialchars ($_POST['cmbAdministradorVacunacion'],ENT_NOQUOTES,'UTF-8');
	$puntoDistribucion = $vdr->filtrarPuntoDistribucion($conexion, $id_administrador_vacunacion);
	$vacunadorOficial = $vdr->obtenerVacunadorOficial($conexion);
	echo '<label>Nombre del punto distribución: </label>';
	echo '<select id="cmbPuntoDistribucion" name="cmbPuntoDistribucion">';
	echo '<option value="0">Seleccionar....</option>';
	while ($fila = pg_fetch_assoc($puntoDistribucion)){
		echo '<option value="'. $fila['id_administrador_distribuidor'].'">'.$fila['nombre_distribuidor'].' </option>';
	}
	echo '</select>';
}

if($opcion==3){
	$datos = array(
			'id_administrador_distribuidor' => htmlspecialchars ($_POST['cmbPuntoDistribucion'],ENT_NOQUOTES,'UTF-8'),
			'identificador_vacunador' => htmlspecialchars ($_POST['cmbVacunador'],ENT_NOQUOTES,'UTF-8'),
		    'usuario_creacion' => htmlspecialchars ($_POST['usuario_responsable'],ENT_NOQUOTES,'UTF-8'), 
		    'estado' => 'activo');
				
	$conexion = new Conexion();
	$vdr = new ControladorVacunacionAnimal();
	
	//Guardar datos del vacunador
	//$Vacunador = $vdr->busquedaVacunador($conexion, $datos['identificador']);
	//if(pg_num_rows($Vacunador) == 0 ){
	$dVacunador = $vdr->guardarDatosVacunador($conexion,$datos['id_administrador_distribuidor'], $datos['identificador_vacunador']
				, $datos['usuario_creacion'], $datos['estado']);
	$idVacunador = pg_fetch_result($dVacunador, 0, 'id_administrador_vacunador');
	//}			
	$conexion->desconectar();			
}

?>

<script type="text/javascript">

	var array_vacunadorOficial = <?php echo json_encode($vacunadorOficial); ?>;
	
	$("#cmbPuntoDistribucion").change(function(){ 
		if($("#cmbPuntoDistribucion").val() != 0){
			sVacunadorOficial = '0';
			sVacunadorOficial = '<option value="0">Seleccione...</option>';
			for(var i=0;i<array_vacunadorOficial.length;i++){	
				//if ($("#cmbSitio").val()==array_vacunadorOficial[i]['id_sitio']){	    
					sVacunadorOficial += '<option value="'+array_vacunadorOficial[i]['identificador_vacunador']+'">'+array_vacunadorOficial[i]['identificador_vacunador']+' - '+array_vacunadorOficial[i]['nombre_vacunador']+'</option>';
				//}			  
			}   
		    $('#cmbVacunador').html(sVacunadorOficial);
		 	$("#cmbVacunador").removeAttr("disabled");	
		}	          					 				
	}); 

</script>








