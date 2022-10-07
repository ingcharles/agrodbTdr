<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$conexion = new Conexion();
$cac = new ControladorAdministrarCatalogos();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$marca = htmlspecialchars ($_POST['marca'],ENT_NOQUOTES,'UTF-8');
$clase = htmlspecialchars ($_POST['clase'],ENT_NOQUOTES,'UTF-8');

//
switch ($opcion) {

	case 'marca':
		
	    $qSubcatalogosItem = $cac->obtenerSubcatalogosPorCatalogoPadreCatalogoHijoItemPadre($conexion, 'COD-MARCA-IA','COD-MODEL-IA',$marca);
		
		echo '<label for="modelo">*Modelo: </label>
				<select id="modelo" name="modelo">
					<option value="">Seleccione...</option>';		
					while ($fila = pg_fetch_assoc($qSubcatalogosItem)){
						echo '<option value="'.$fila['id_item']. '">'. $fila['nombre'] .'</option>';
					}
				echo '</select>';
		
	break;

	case 'clase':
	
	    $qSubcatalogosItem = $cac->obtenerSubcatalogosPorCatalogoPadreCatalogoHijoItemPadre($conexion, 'COD-CLASE-IA','COD-TIPOX-IA',$clase);
	
		echo '<label for="tipo">*Tipo: </label>
				<select id="tipo" name="tipo">
					<option value="">Seleccione...</option>';
		while ($fila = pg_fetch_assoc($qSubcatalogosItem)){
			echo '<option value="'.$fila['id_item']. '">'. $fila['nombre'] .'</option>';
		}
		echo '</select>';
	
	break;
	
		
}

?>

<script type="text/javascript"> 

	$(document).ready(function(){		
		distribuirLineas(); 
	});

    /*$("#modelo").change(function(event){
    	event.stopImmediatePropagation();
        if( $("#modelo").val() != ""){
			 $('#declararDatosVehiculo').attr('data-opcion','combosDatosVehiculo');
			 $('#declararDatosVehiculo').attr('data-destino','resultadoModelo');
			 $('#opcion').val('modelo');	
			 abrir($("#declararDatosVehiculo"),event,false);	
        }
	});*/

    $("#clase").change(function(event){
    	event.stopImmediatePropagation();
    	if( $("#clase").val() != ""){
			 $('#declararDatosVehiculo').attr('data-opcion','combosDatosVehiculo');
			 $('#declararDatosVehiculo').attr('data-destino','resultadoClase');
			 $('#opcion').val('clase');	
			 abrir($("#declararDatosVehiculo"),event,false);	
    	}
	});

</script>	