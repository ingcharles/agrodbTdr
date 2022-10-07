<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorProtocolos.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();
$cp = new ControladorProtocolos();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$identificadorOperador = htmlspecialchars ($_POST['idOperador'],ENT_NOQUOTES,'UTF-8');
$bandera = 0;

switch ($opcion) {
	
	case 'operador':

		$qOperador = $cro->buscarOperador($conexion, $identificadorOperador);
		if(pg_num_rows($qOperador)==0){
			$bandera = 1;
			echo '<div class="mensaje">El usuario no se encuentra registrado.</div>';
		}else{
			$bandera = 0;
			$qSitios = $cro->listarSitiosXOperador($conexion, $identificadorOperador);
			$qDatosOperador = $cro->obtenerDatosOperador($conexion, $identificadorOperador);
			$datosOperador = pg_fetch_assoc($qDatosOperador);
			$nombreOperador = ($datosOperador['razon_social'] == '') ? $datosOperador['nombre_representante'] . ' ' . $datosOperador['apellido_representante'] : $datosOperador['razon_social'];
		    
			echo '<div data-linea="100"><label>Nombre operador: </label><input type="text" id="nombreOperador" name="nombreOperador" value="' . $datosOperador['razon_social'] . '" readonly data-resetear="no" /><hr/></div>';
			echo '<div data-linea="101">
                <label>Código sitio: </label>
				<select id="codigoSitio" name="codigoSitio" required data-resetear="no" >
				<option value="">Seleccione...</option>';
			while ($sitios = pg_fetch_assoc($qSitios)){
				echo '<option value="'. $sitios['id_sitio'].'" >'.$sitios['nombre_sitio'].'</option>';
			}
			echo '</select>';			
		}
		
	break;

	case 'codigoArea':
	
		$qAreas = $cro->listarAreasXSitio($conexion, $_POST['codigoSitio']);
		echo '<div data-linea="4"><label>Código área: </label>
				<select id="codigoArea" name="codigoArea" required data-resetear="no" >
				<option value="">Seleccione...</option>';
		while ($areas = pg_fetch_assoc($qAreas)){
		    echo '<option data-nombreArea="'. $areas['narea'].'" value="'. $areas['id_area'].'" >'.$areas['narea'].' - '.$areas['nombre_area'].'</option>';
		}
		echo '</select>
				
			  </div>';

	break;		
		
	case 'nombreArea':

		$qOperaciones = $cro->listarOperacionesXArea($conexion, $_POST['codigoArea']);

		echo '<label>Operaciones: </label>';
		echo '<select id="idOperacion" name="idOperacion" required data-resetear="no" >';
		echo '<option value="">Seleccione...</option>';
		while ($fila = pg_fetch_assoc($qOperaciones)){
		    echo '<option data-nombreOperacion="'. $fila['nombre'].'" value="'. $fila['id_tipo_operacion'].'" >'.$fila['nombre'].' - '.$fila['tipo_area'].'</option>';
		}
		echo '</select>';
	break;
	
	default:
	    
	    $qSitiosProtocolos = $cp->listarSitiosConProtocolosAprobados($conexion, $_POST['bIdentificador']);
	    
	    echo '<select id="bCodigoSitio" name="bCodigoSitio" style="width: 91%;">';
	    echo '<option value="">Seleccione...</option>';
	    while ($fila = pg_fetch_assoc($qSitiosProtocolos)){
	        echo '<option value="'. $fila['codigo_sitio'].'" >'.$fila['codigo_sitio'].' - '.$fila['nombre_lugar'].'</option>';
	    }
	    echo '</select>';
	break;
			
}

?>

<script type="text/javascript">

	var bandera = <?php echo $bandera?>

	$(document).ready(function(){
		distribuirLineas();
	});

    if(bandera==1){
    	$("#codigoSitio").hide();
    	$("#resultadoCodigoArea").hide();
    	$("#resultadoNombreArea").hide();
    	$("#resultadoProducto").hide();	
    }    
    	
    $("#codigoSitio").change(function(event){    
    	if($("#codigoSitio").val() != ""){
    		$("#codigoSitio option:not(:selected)").attr("disabled",true);
        	$('#nuevoinspeccionProtocolo').attr('data-destino','resultadoCodigoArea');
        	$('#nuevoinspeccionProtocolo').attr('data-opcion','accionesInspeccionesProtocolo');
            $('#opcion').val('codigoArea');	
            event.stopImmediatePropagation();		
        	abrir($("#nuevoinspeccionProtocolo"),event,false);
    	}    	
    });
    
    $("#codigoArea").change(function(event){    
    	if($("#codigoArea").val() != ""){
    		$("#codigoArea option:not(:selected)").attr("disabled",true);
        	$('#nuevoinspeccionProtocolo').attr('data-destino','resultadoNombreArea');
        	$('#nuevoinspeccionProtocolo').attr('data-opcion','accionesInspeccionesProtocolo');
            $('#opcion').val('nombreArea');	
            $('#nombreAreaCodigo').val($("#codigoArea option:selected").attr("data-nombreArea"));
            event.stopImmediatePropagation();		
        	abrir($("#nuevoinspeccionProtocolo"),event,false);
    	}    
    });
    
    $("#idOperacion").change(function(event){    
    	if($("#idOperacion").val() != ""){
    		$("#idOperacion option:not(:selected)").attr("disabled",true);	
            $('#nombreOperacion').val($("#idOperacion option:selected").attr("data-nombreOperacion"));
    	}    	
    });
    
    $("#protocolo").change(function(event){
        $('#nombreProtocolo').val($("#protocolo option:selected").text());	
        event.stopPropagation();
    });

</script>