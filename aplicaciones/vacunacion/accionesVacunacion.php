<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$va = new ControladorVacunacion();
$cc = new ControladorCatalogos();
$cro = new ControladorRegistroOperador();
set_time_limit(1000);

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

$arrayIdentificador = json_decode($_POST['arrayIdentificador']);

switch ($opcion) {
	case 'buscarCertificado':
		$certificadoValorado =$va->buscarNumeroCertificado($conexion, $_POST['numeroCertificado'], $_POST['especie']);
		if( pg_num_rows($certificadoValorado)!=0){
			echo '<label>N° Certificado: </label>';
			echo '<select id="certificadoVacunacion" name="certificadoVacunacion">';
			$contador=0;
			while ($fila = pg_fetch_assoc($certificadoValorado)){
				switch ($fila['estado']) {
					case 'inactivo':
						$certificado.=" ".$fila['numero_documento'];
						$estado.=" inactivo";
					break;
		
					case 'utilizado':
						$certificado.=" ".$fila['numero_documento'];
						$estado.=" utilizado";
					break;
		
					case 'anulado':
						$certificado.=" ".$fila['numero_documento'];
						$estado.=" anulado";
					break;		
				}
		
				if ($fila['estado']=='creado'){
					echo '<option value="'. $fila['numero_documento'].'">'.$fila['numero_documento'].'</option>';
				}else{
					if($contador<1)
					echo '<option value="0">Seleccione...</option>';
					echo  '<script type="text/javascript"> $("#campoBusquedaCertificado").addClass("alertaCombo"); $("#estado").html("El número del certificado de vacunación "+ "'.$certificado.'" +" , esta como --> " + "'.$estado.'").addClass("alerta"); </script>';
				}
				$contador++;
			}
			echo '</select>';
		}else{
			echo '<label>N° Certificado: </label>';
			echo '<select id="certificadoVacunacion" name="certificadoVacunacion">';
			echo '<option value="0">Seleccione...</option>';
			echo '</select>';
			echo  '<script type="text/javascript"> $("#campoBusquedaCertificado").addClass("alertaCombo"); $("#estado").html("El número del certificado de vacunación , esta como --> Inexistente ( no registrado )").addClass("alerta"); </script>';
		}
		break;

		case 'listaSitios':
		        
		    
			$buscarSitioOrigen = $va->filtrarSitiosVacunacion($conexion, $_POST['identificadorSolicitante'], $_POST['nombreSitio']);
			echo '<label>Nombre del Sitio: </label>';
			echo '<select id="sitio" name="sitio">';
			echo '<option value="0">Seleccione...</option>';
			while ($fila = pg_fetch_assoc($buscarSitioOrigen)){
				echo '<option data-identificador-operador="'. $fila['identificador'].'"  value="'. $fila['id_sitio'].'" >'.$fila['nombre_lugar'].'</option>';
			}
			echo '</select>';	
		break;
		
		case 'listaAreas':
			$qResultadoAreas=$va->listarAreasXsitiosOperacion($conexion, $_POST['sitio'], $_POST['operacion']);
			echo '<label>Área: </label>';
			echo '<select id="areas" name="areas">';
			echo '<option value="0">Seleccione...</option>';
			while ($fila = pg_fetch_assoc($qResultadoAreas)){
				echo '<option  value="'.$fila['id_area'].'">'.$fila['nombre_area'].'</option>';
			}
			echo '</select>';
		break;
		
		case 'listaProductos':	
			$qProdustosActosVacunacion=$va->buscarProductosNoActosVacunacion($conexion, 'vacunacion');
			$productosActosVacunar = "";
			while ($filas = pg_fetch_assoc($qProdustosActosVacunacion)){
				$productosActosVacunar .= "" . $filas ['id_producto'] . ","; 
			}
			
			$identificadores = identificadoresAgregados();
			$aListarIdentificadoresProducto = array();
			$qListarIdentificadoresProducto = $va->listaIdentificadoresProductoVacunacion($conexion, $_POST['gArea'],"(" . rtrim ( $productosActosVacunar, ',' ) . ")",$_POST['gNumeroLote'],$_POST['gOperacion'],$_POST['unidadMedida'],"(" . rtrim ( $identificadores, ',' ) . ")");

			//$cantidadIdentificadoresProducto = pg_num_rows($qListarIdentificadoresProducto);
			
			while($fila = pg_fetch_assoc($qListarIdentificadoresProducto)){
				$aListarIdentificadoresProducto[] = array ('value' => $fila['identificador_producto'], 'label' => $fila['identificador_producto'].' - '.$fila ['nombre_producto'].' -> '.$fila ['estado_registro'], 'idProducto' => $fila ['id_producto'], 'nombreProducto' => $fila ['nombre_producto']);
			}
			
			/*echo "<pre>";
			print_r($aListarIdentificadoresProducto);
			echo "<pre>";*/
			
			if(count($aListarIdentificadoresProducto) == 0){
			echo "No existen registros con el operador ingresado";
			}else{
			    //echo '<label>Cantidad Existente: </label>' . $cantidadIdentificadoresProducto . '<br><br>';
			    echo '<hr/><label>N° Identificador: </label><input type=text id="identificadorProductoAutocompletar" name="identificadorProductoAutocompletar" maxlength="14"/>';			    		    
			}
			
		break;

		case 'listaOperaciones':
		    
		    if(isset($_POST['identificadorOperador'])){
		        
		        $qOperacionesOperador = $cro->obtenerOperacionesXOperadorXIdAreaXCodigoOperacion($conexion, $_POST['identificadorOperador'], 'SA', " in ('OPT', 'OPI', 'COM', 'COD', 'PRO')");
		        $operacionesOperador = pg_fetch_result($qOperacionesOperador, 0, 'codigo');
		        
		    }
		    
		    echo '<input type="hidden" id="operacionesOperador" name="operacionesOperador" value="'.$operacionesOperador.'" />';		    
		    
			$qOperacion = $va->listarOperacionesXoperadorYsitio($conexion, $_POST['sitio']);
			echo '<label>Operación: </label>';
			echo '<select id="operacion" name="operacion">';
			echo '<option value="0">Seleccione...</option>';
			while ($fila = pg_fetch_assoc($qOperacion)){
				echo '<option value="'.$fila['id_tipo_operacion'].'">'.$fila['nombre'].'</option>';
			}
			echo '</select>';
		break;

		case 'listaLotesProducto':
			
			$qProdustosActosVacunacion=$va->buscarProductosNoActosVacunacion($conexion, 'vacunacion');
			$productosActosVacunar = "";
			while ($filas = pg_fetch_assoc($qProdustosActosVacunacion)){
				$productosActosVacunar .= "" . $filas ['id_producto'] . ",";
			}
						
			$identificadores = identificadoresAgregados();
			$catastroProducto = $va->listaLotesProductoVacunacion($conexion, $_POST['gArea'],"(" . rtrim ( $productosActosVacunar, ',' ) . ")", $_POST['gOperacion'],$_POST['unidadMedida'],"(" . rtrim ( $identificadores, ',' ) . ")");		
			echo '<label>N° Lote: </label>';
			echo '<select id="lotesProducto" name="lotesProducto" >';
			echo '<option value="0">Seleccione...</option>';
			while ($fila = pg_fetch_assoc($catastroProducto)){
				if($fila['total']>=0)
				    echo '<option  data-total="'.$fila['total'].'" data-idProducto = "'.$fila['id_producto'].'" data-nombreProducto = "'.$fila['nombre_producto'].'" value="'.$fila['numero_lote'].'">'.$fila['numero_lote'].' - '.$fila['nombre_producto'].'</option>';
			}	
			echo '</select>';
		break;
				
		case 'vacunadorTecnico':
			$tecnicoVacunador = $va->filtrarTecnicoVacunador($conexion, $_POST['identificacionVacunador'], $_POST['nombreVacunador']);
			echo '<label>Vacunador: </label>';
			echo '<select id="vacunador" name="vacunador">';
			while ($fila = pg_fetch_assoc($tecnicoVacunador)){
				echo '<option  value="'. $fila['identificador'].'" >'.$fila['nombres']. ' - ' .$fila['identificador'].'</option>';
			}
			echo '<option value="">Seleccione...</option>';
			echo '</select>';
		break;
				
		case 'buscarEmpleadoRol':
			$qEmpleadoEmpresaRol = $va->buscarEmpleadoEmpresaRol($conexion, $_POST['operadorVacunacion'],$_POST['identificadorEmpleado'],$_POST['nombreEmpleado']);
			echo '<label>Empleado: </label>';
			echo '<select id="empleado" name="empleado">';
			echo '<option value="0">Seleccione...</option>';
			while ($fila = pg_fetch_assoc($qEmpleadoEmpresaRol)){
				echo '<option  data-identificador-empleado="'.$fila['identificador'].'" value="'. $fila['id_empleado'].'" >'.$fila['nombres']. ' - ' .$fila['identificador'].'</option>';
			}
			echo '</select>';
		break;
		
		case 'listaCantones':
			$provincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
		
			$qListaCantones=$cc->obtenerLocalizacionHijo($conexion,'CANTONES','PROVINCIAS',$provincia);
			echo '
			<select id="canton" name="canton" style="width:250px">
				<option value="">Seleccione...</option>
				<option value="todos">Todos</option>';
			while ($fila = pg_fetch_assoc($qListaCantones)){
				echo '<option value="'.$fila['id_localizacion'].'">'.$fila['nombre'].'</option>';
			}
			echo '</select>';
			break;
		
		case 'listaParroquias':
			$canton = htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8');
		
		
			$qListaParroquia=$cc->obtenerLocalizacionHijo($conexion,'PARROQUIAS','CANTONES',$canton);
			echo '
			<select id="parroquia" name="parroquia" style="width:250px">
				<option value="">Seleccione...</option>
				<option value="todos">Todos</option>';
			while ($fila = pg_fetch_assoc($qListaParroquia)){
				echo '<option value="'.$fila['id_localizacion'].'">'.$fila['nombre'].'</option>';
			}
			echo '</select>';
			break;
		
		case 'listaTipoProductos':
			$areaTematica = htmlspecialchars ($_POST['areaTematica'],ENT_NOQUOTES,'UTF-8');
		
		
			$qTipoProductos=$cc->listarTipoProductosXarea($conexion, $areaTematica);
			echo '
			<select id="tipoProducto" name="tipoProducto" style="width:250px">
				<option value="">Seleccione...</option>';
		
			while($fila = pg_fetch_assoc($qTipoProductos)){
				echo '<option value="' . $fila['id_tipo_producto'] . '">' . $fila['nombre'] . '</option>';
			}
			echo '</select>';
			break;
		
		case 'listaSubTipoProductos':
			$tipoProductos = htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
		
		
			$qSubTipoProductos=$cc->listarSubTipoProductoXtipoProducto($conexion, $tipoProductos);
			echo '
			<select id="subTipoProducto" name="subTipoProducto" style="width:250px">
				<option value="">Seleccione...</option>';
		
			while($fila = pg_fetch_assoc($qSubTipoProductos)){
				echo '<option value="' . $fila['id_subtipo_producto'] . '">' . $fila['nombre'] . '</option>';
			}
			echo '</select>';
			break;
			
        
        case 'buscarOperador':
            
            $identificadorComerciante = htmlspecialchars ($_POST['identificadorComerciante'],ENT_NOQUOTES,'UTF-8');
            $qOperacionesOperador = $cro->obtenerDatosOperadorXIdAreaXCodigoOperacion($conexion, $identificadorComerciante, 'SA', " in ('COM', 'COD')");
            $nombreOperador = pg_fetch_result($qOperacionesOperador, 0, 'nombre_operador');
            
            if(pg_num_rows($qOperacionesOperador) > 0){
                echo '<label>Comerciante: </label>'.$nombreOperador;       
                echo '<input type="hidden" id="validadorComerciante" name="validadorComerciante" value="correcto" />';
            }else{
                echo "No existen registros con el operador ingresado"; 
                echo '<input type="hidden" id="validadorComerciante" name="validadorComerciante" value="incorrecto" />';
            }
            break;

}

function identificadoresAgregados(){
	
	if(is_array($_POST['hIdentificadoresValidar']) && count ($_POST['hIdentificadoresValidar']) != 0 ){
		for($i = 0; $i < count ($_POST['hIdentificadoresValidar']); $i ++) {
			$identificadores.= "'" . $_POST['hIdentificadoresValidar'][$i] . "',";
		}
	}else{	
		$identificadores="''";
	}

	return $identificadores;
}
?>

<script type="text/javascript">     

var array_datos_identificadores = <?php echo json_encode($aListarIdentificadoresProducto); ?>;

if($.isEmptyObject(array_datos_identificadores)){
	array_datos_identificadores = <?php echo json_encode($arrayIdentificador); ?>;
}
	
	var operacionesOperador = $("#operacionesOperador").val();
	
	$(document).ready(function(){		
		 distribuirLineas();	 
	});

	if($("#certificadoVacunacion").val()!=0 && $("#fechaVacunacion").val()==""){
		$("#fechaVacunacion").focus();
	}
	
    $("#sitio").change(function(event){ 
        if($("#sitio").val()!=0){
        	$("#identificadorOperador").val($('#sitio option:selected').attr('data-identificador-operador'));
	    	$("#areas").val("");
	    	$('#nuevoVacunacion').attr('data-destino','resultadoOperaciones');
	    	$('#nuevoVacunacion').attr('data-opcion','accionesVacunacion');
		    $('#opcion').val('listaOperaciones');
		    event.stopImmediatePropagation();	
			abrir($("#nuevoVacunacion"),event,false);
        }
	});  
    
	$("#areas").change(function(event){

		event.preventDefault();	
		event.stopImmediatePropagation();

		if($("#areas").val()!=0){

			$('#gArea').val($('#areas option:selected').val());
		
			if(operacionesOperador.indexOf('OPI') > -1){
		
				accionesTipoVacunacion('lote');
				
			}else if(operacionesOperador.indexOf('OPT') > -1){			
				
				accionesTipoVacunacion('identificador');
				
			}else if(operacionesOperador.indexOf('COM') > -1){
			
				accionesTipoVacunacion('identificador');

			}else if(operacionesOperador.indexOf('COD') > -1){
			
				accionesTipoVacunacion('identificador');
		 		
			}else if(operacionesOperador.indexOf('PRO') > -1){
			
				accionesTipoVacunacion('identificador');
		 		
			}

		}
		
 	});
 	
	$("#operacion").change(function(event){			
		if($("#operacion").val()!=0){			
			if(operacionesOperador.indexOf('OPI') > -1){				
				$("#tipoVacunacion").val("lote");
				$("#resultadoProductoVacunacion").hide();
    		 }else if(operacionesOperador.indexOf('OPT') > -1){
    				$("#tipoVacunacion").val("identificador");
    				$("#resultadoLotesProducto").hide();
    				$("#cantidadLote").hide();
    				$("#cantidadVacuna").hide();
    				$("#cantidadVacuna").val("");
    		 }
			
			$("#gOperacion").val($('#operacion option:selected').val());
	 		$("#nuevoVacunacion").attr('data-destino','resultadoAreas');	
	 		$("#nuevoVacunacion").attr('data-opcion','accionesVacunacion');
	 		$("#opcion").val('listaAreas');
	 		event.stopImmediatePropagation();	
	 		abrir($("#nuevoVacunacion"),event,false);
	 		distribuirLineas();
		}		
	}); 

	$("#lotesProducto").change(function(event){  
		if($("#lotesProducto").val()!=0){			
			$("#cantidadExistentePorLote").show();
			$("#cantidadExistentePorLote").val($('#lotesProducto option:selected').attr('data-total'));
			$("#gProducto").val($('#lotesProducto option:selected').attr('data-idProducto'));
			$("#gNombreProducto").val($('#lotesProducto option:selected').attr('data-nombreProducto'));
			$("#gNumeroLote").val($('#lotesProducto option:selected').val());		
		}else{
			$("#cantidadExistentePorLote").val("");
		}
	}); 

	$("#empleado").change(function(event){  
		if($("#empleado").val()){
			$("#identificacionEmpleado").val($('#empleado option:selected').attr('data-identificador-empleado'));	
		}
	});

	$("#canton").change(function(event){
		if($("#canton").val()!=0){
			if($("#canton").val()!='todos'){
			$("#nuevoFiltroAretesVacunacion").attr('data-destino','resultadoParroquias');
			$("#nuevoFiltroAretesVacunacion").attr('data-opcion','accionesVacunacion');
		    $("#opcion").val('listaParroquias');		
		    event.stopImmediatePropagation();
			abrir($("#nuevoFiltroAretesVacunacion"),event,false); 
			}else{
				$("#parroquia").attr("disabled",true);
			}
		}
	 });

	$("#tipoProducto").change(function(event){
		if($("#tipoProducto").val()!=0){
			$("#nuevoFiltroAretesVacunacion").attr('data-destino','resultadoSubTipoProductos');
			$("#nuevoFiltroAretesVacunacion").attr('data-opcion','accionesVacunacion');
		    $("#opcion").val('listaSubTipoProductos');	
		    event.stopImmediatePropagation();	
			abrir($("#nuevoFiltroAretesVacunacion"),event,false); 
		}
	 });
	
	$(function(){
		
		var data = array_datos_identificadores;
		$("#identificadorProductoAutocompletar").autocomplete({				 
			source: data,
			minLength: 5,
			
			select: function(event, ui){
            	$("#gNombreProducto").val(ui.item.nombreProducto);
            	$("#gIdentificadorProducto").val(ui.item.value);
            	$("#gProducto").val(ui.item.idProducto);
			},change:function(event, ui){
        		if (ui.item == null || ui.item == undefined) {
        			$("#identificadorProductoAutocompletar").val("");
        		}
			}
		});
		
	});
 

	function accionesTipoVacunacion(categoria){

		switch(categoria){

			case "lote":

				$("#visualizarIdentificadorRango1").hide();		
				$("#visualizarIdentificadorRango2").hide();		
				$("#visualizarIdentificadorRango3").hide();		
				$("#tipoVacunacion").val("lote");
				$("#cantidadLote").show();
				$("#cantidadVacuna").show();
				$("#lotesProducto").val(0);
				$("#lotesProducto").show();
				$("#cantidadExistentePorLote").val("");
				$("#tablaDetallesLote").show();		
				
				$("#nuevoVacunacion").attr('data-opcion','accionesVacunacion');	
			    $("#nuevoVacunacion").attr('data-destino','resultadoLotesProducto');		 
			    $("#opcion").val('listaLotesProducto');			
				abrir($("#nuevoVacunacion"),event,false);

			break;

			case "identificador":

				$("#visualizarIdentificadorRango1").show();	
				$("#visualizarIdentificadorRango2").show();	
				$("#visualizarIdentificadorRango3").show();				
				$("#tipoVacunacion").val("identificador");				

				$("#tablaDetalles").show();				
				$("#lotesProducto").hide();

		 		$("#nuevoVacunacion").attr('data-opcion','accionesVacunacion');	
		 		$("#nuevoVacunacion").attr('data-destino','resultadoProductoVacunacion');		 
		 	    $("#opcion").val('listaProductos');		 	  	
		 		abrir($("#nuevoVacunacion"),event,false)

			break;

		}

	}

</script>
