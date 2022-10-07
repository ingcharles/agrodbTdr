<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosLinea.php';
require_once '../../clases/ControladorCatalogos.php';
$conexion = new Conexion();
$csl = new ControladorServiciosLinea();
$cc = new ControladorCatalogos();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$opcionPaso = htmlspecialchars ($_POST['opcionPaso'],ENT_NOQUOTES,'UTF-8');


switch ($opcion) {
	case 'listaOficinas':
		$qOficinas = $cc->obtenerLocalizacionHijo($conexion,'SITIOS','CANTONES' ,$_POST['canton']);
		echo '<label>Oficina: </label>';
		echo '<select id="oficina" name="oficina">';
		echo '<option value="">Seleccione...</option>';
		while ($fila = pg_fetch_assoc($qOficinas)){
			echo '<option value="'. $fila['id_localizacion'].'" >'.$fila['nombre'].'</option>';
		}
		echo '</select>';
						
							$sector = array('Aloag','Machachi','Norte','Sur','Valle de los Chillos','Valle de Tumbaco');										
							for ($i=0; $i<sizeof($sector); $i++)
								$listaSectores[]= array(sector=>$sector[$i]);
						
					
	break;
			
	case 'listaCantones':
		$qListaCantones=$cc->obtenerLocalizacionHijo($conexion,'CANTONES','PROVINCIAS',$_POST['provincia']);
		echo '<label>Cantón: </label>';
		echo '<select id="canton" name="canton" style="width:270px">
				<option value="">Seleccione...</option>';
			
		while ($fila = pg_fetch_assoc($qListaCantones)){
			echo '<option value="'.$fila['id_localizacion'].'">'.$fila['nombre'].'</option>';
		}
		echo '</select>';
	break;
	
}
switch ($opcionPaso) {	
	case 'listaOficinasH':
		$qOficinas = $cc->obtenerLocalizacionHijo($conexion,'SITIOS','CANTONES' ,$_POST['cantonH']);
		echo '<select id="oficinaH" name="oficinaH" style="width:250px">';
		echo '<option value="">Seleccione...</option>';
		while ($fila = pg_fetch_assoc($qOficinas)){
			echo '<option value="'. $fila['id_localizacion'].'" >'.$fila['nombre'].'</option>';
		}
		echo '</select>';
		break;
	
	case 'listaCantonesH':
		$qListaCantones=$cc->obtenerLocalizacionHijo($conexion,'CANTONES','PROVINCIAS',$_POST['provinciaH']);
		
		echo '<select id="cantonH" name="cantonH" style="width:250px">
				<option value="">Seleccione...</option>';
			
		while ($fila = pg_fetch_assoc($qListaCantones)){
			echo '<option value="'.$fila['id_localizacion'].'">'.$fila['nombre'].'</option>';
		}
		echo '</select>';
		break;
}

?>
<script type="text/javascript">
	

	$(document).ready(function(){
		distribuirLineas();
	});

	$("#canton").change(function(event){
		if($("#canton").val()!=0){
			$('#nuevoRecorridosInstitucionales').attr('data-destino','resultadoOficinas');
			$('#nuevoRecorridosInstitucionales').attr('data-opcion','accionesServiciosLinea');
		    $('#opcion').val('listaOficinas');		
		    event.stopImmediatePropagation();
			abrir($("#nuevoRecorridosInstitucionales"),event,false); 
		}
	});

	$("#cantonH").change(function(event){
		if($("#cantonH").val()!=0){
			$('#nuevoFiltroRutasTransporte').attr('data-destino','resultadoOficinasH');
			$('#nuevoFiltroRutasTransporte').attr('data-opcion','accionesServiciosLinea');
		    $('#opcionPaso').val('listaOficinasH');		
		    event.stopImmediatePropagation();
			abrir($("#nuevoFiltroRutasTransporte"),event,false); 
		}
	});

	$("#oficina").change(function(event){
		if($("#oficina").val()!=0){
			if($("#oficina option:selected").text()=='Oficina Planta Central' || $("#oficina option:selected").text()=='Laboratorios Tumbaco'){
				var listaSectores = <?php echo json_encode($listaSectores);?>;
				sListaSector = '<option value="">Seleccione...</option>';
				for(var i=0; i<listaSectores.length; i++){
					sListaSector += '<option value="'+listaSectores[i]['sector']+'">'+listaSectores[i]['sector']+'</option>';
					 $('#sector').html(sListaSector);
				}
			}else{
				$('#sector').html('<option value="">Seleccione...</option><option value="Norte">Norte</option><option value="Sur">Sur</option>');
			}
		}
	});
</script>