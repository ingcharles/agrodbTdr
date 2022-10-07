<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorMovilizacionProductos.php';

$conexion = new Conexion();
$cp = new ControladorCatastroProducto();
$cc = new ControladorCatalogos();
$cro = new ControladorRegistroOperador();
$cmp = new ControladorMovilizacionProductos();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {
	
	case 'listaSitios':
		echo '<label >Nombre del Sitio: </label>';
		echo '<select id="sitio" name="sitio">';
		echo '<option value="0">Seleccione...</option>';
		$buscarSitioOrigen = $cp->filtrarSitiosCatastro($conexion, $_POST['identificadorSolicitante'], $_POST['nombreSitioOrigen'], $_POST['areaTematica']);
		while ($fila = pg_fetch_assoc($buscarSitioOrigen)){
			echo '<option data-identificador-operador="'. $fila['identificador'].'"  value="'. $fila['id_sitio'].'" >'.$fila['nombre_lugar'].'</option>';
		}
		echo '</select>';
	break;
	
	case 'listaOperaciones':
	    $identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
	    $idAreaTematica = htmlspecialchars ($_POST['areaTematica'],ENT_NOQUOTES,'UTF-8');
	    $idSitio = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
	    	    
	    if(isset($identificadorOperador)){	        
	        $qOperacionesOperador = $cro->obtenerOperacionesXOperadorXIdAreaXCodigoOperacion($conexion, $identificadorOperador, 'SA', " in ('OCC', 'OPT', 'OPI', 'FER', 'FEA', 'PRO', 'COM', 'COD')");
	        $operacionesOperador = pg_fetch_result($qOperacionesOperador, 0, 'codigo');	        
	    }
	    
	    echo '<input type="hidden" id="operacionesOperador" name="operacionesOperador" value="' . $operacionesOperador . '" />';
	    
	    $qOperacion = $cp->listarOperacionesXoperadorYsitio($conexion, $identificadorOperador, $idAreaTematica, $idSitio, "('PRO', 'COM')");
	    echo '<label>Operación: </label>
			<select id="operacion" name="operacion">
				<option value="0">Seleccione...</option>';
	    while ($fila = pg_fetch_assoc($qOperacion)){
	        echo '<option value="'.$fila['id_tipo_operacion'].'">'.$fila['nombre'].'</option>';
	    }
	    echo '</select>';
	break;
	
	case 'listaAreas':
	    $idSitio = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
	    $idTipoOperacion = htmlspecialchars ($_POST['operacion'],ENT_NOQUOTES,'UTF-8');
	    $qAreasSitios = $cp->listarAreasXsitiosOperacion($conexion, $idSitio, $idTipoOperacion);
	    echo '<label>Nombre del Área: </label>';
	    echo '<select id="area" name="area">';
	    echo '<option value="">Seleccione...</option>';
	    while ($fila = pg_fetch_assoc($qAreasSitios)){
	        echo '<option value="'. $fila['id_area'].'">'.$fila['nombre_area'].'  </option>';
	    }
	    echo '</select>';
	break;
	    
	case 'listarProductos':
	    $idArea = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	    $idAreaTematica = htmlspecialchars ($_POST['areaTematica'],ENT_NOQUOTES,'UTF-8');
	    $ProductosAreas=$cp->listarProductosXareas($conexion, $idArea, $idAreaTematica);
	    echo '<label>Producto: </label>';
	    echo '<select id="producto" name="producto">';
	    echo '<option value="">Seleccione...</option>';
	    
	    while ($fila = pg_fetch_assoc($ProductosAreas)){
	        if($_POST['controlUsuario'] == 'usuario' && ($fila['codigo_producto'] == 'PORHON' || $fila['codigo_producto'] == 'PORONA'))
	            echo '<option data-codigo-producto="'. $fila['codigo_producto'].'" data-unidad-medida="'. $fila['unidad_medida'].'" data-dias-inicio-etapa="'. $fila['dias_inicio_etapa'].'" data-dias-fin-etapa="'. $fila['dias_fin_etapa'].'" data-codigo-especie="'. $fila['codigo_especie'].'" data-id-especie="'. $fila['id_especie'].'"  value="'. $fila['id_producto'].'">'.$fila['nombre_subtipo']. '-'. $fila['nombre_comun'].'  </option>';
	            
            if(!isset($_POST['controlUsuario']))
                echo '<option data-codigo-producto="'. $fila['codigo_producto'].'" data-unidad-medida="'. $fila['unidad_medida'].'" data-dias-inicio-etapa="'. $fila['dias_inicio_etapa'].'" data-dias-fin-etapa="'. $fila['dias_fin_etapa'].'" data-codigo-especie="'. $fila['codigo_especie'].'" data-id-especie="'. $fila['id_especie'].'"  value="'. $fila['id_producto'].'">'.$fila['nombre_subtipo']. '-'. $fila['nombre_comun'].'  </option>';
	    }
	    echo '</select>';
	    
	break;
	    
	case 'controlReproduccion':
	    
	    $identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
	    
	    if($_POST['codigoProductoTemp'] == 'PORHON' || $_POST['codigoProductoTemp'] == 'PORONA'){
	        $idProductoReproduccion = pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORDRE'), 0, 'id_producto');
	        $idProductoLechon = pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORHON'), 0, 'id_producto');
	        $idProductoLechona = pg_fetch_result($cp->obtenerIdProductoXCodigoProducto($conexion, 'PORONA'), 0, 'id_producto');
	        
	        $qObtenerMaximoControlReproduccion = $cp->obtenerMaximoControlReproduccion($conexion, $identificadorOperador, $idProductoReproduccion);
	        $qCantidadCatastroMadres = $cp->obtenerCantidadCatastroXOperador($conexion, $identificadorOperador, '('.$idProductoReproduccion.')');
	        $qCantidadCatastroCrias = $cp->obtenerCantidadCatastroXOperador($conexion, $identificadorOperador, '('.$idProductoLechon.','.$idProductoLechona.')');
	        $cupoCria = 0;
	        
	        if (pg_num_rows($qObtenerMaximoControlReproduccion) != 0){
	            if(pg_fetch_result($qCantidadCatastroMadres, 0, 'cantidad') == 0 && pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cupo_cria') == 0 ){
	                $cupoCria = 14 - pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cantidad_cria');
	            }else{
	                $cupoCria = pg_fetch_result($qObtenerMaximoControlReproduccion, 0, 'cupo_cria');
	            }
	        }else{
	            if(pg_fetch_result($qCantidadCatastroMadres, 0, 'cantidad') != 0){
	                $cupoCria = (pg_fetch_result($qCantidadCatastroMadres, 0, 'cantidad') * 28) - pg_fetch_result($qCantidadCatastroCrias, 0, 'cantidad');
	            }else{
	                $cupoCria = 14;
	            }
	        }
	        
	        if($cupoCria < 0){
	            $cupoCria = 0;
	        }
	            
	        echo '<input type="hidden"  id="cantidadCupo" name="cantidadCupo" value="'. $cupoCria.'" />';
	    }
	
    break;
	
    ///PARA ABRIR CATASTRO
    	
	case 'listaDetalleCatastro':
		$idCatastro = htmlspecialchars ($_POST['idCatastro'],ENT_NOQUOTES,'UTF-8');
		$idConceptoCatastro = htmlspecialchars ($_POST['conceptoCatastro'],ENT_NOQUOTES,'UTF-8');
		$qDetalleCatastro=$cp->abrirDetalleCatatroIndividualProducto($conexion, $idCatastro);
		$contador=1;
		while ($fila = pg_fetch_assoc($qDetalleCatastro)){
			echo $cp->imprimirIdentificadoresProducto($fila['id_producto'], $fila['identificador_producto'],$fila['id_detalle_catastro'],$idConceptoCatastro,$idCatastro,$contador++);
		}
	break;
	
	case 'listaDetalleRango':
		$idCatastro = htmlspecialchars ($_POST['idCatastro'],ENT_NOQUOTES,'UTF-8');
		$qDetalleCatastro=$cp->abrirDetalleCatatroIndividualProducto($conexion, $idCatastro);
		echo '<label>Inicio Rango: </label>';
		echo '<select id="inicioRango" name="inicioRango">';
		echo '<option value="">Seleccione...</option>';
		while ($fila = pg_fetch_assoc($qDetalleCatastro)){	
			echo '<option value="'. $fila['identificador_producto'].'">'. $fila['identificador_producto'] .'  </option>';
		}
		echo '</select>';
	break;
	
	case 'listaDetalleRango1':
	
		$idCatastro = htmlspecialchars ($_POST['idCatastro'],ENT_NOQUOTES,'UTF-8');
		$qDetalleCatastro=$cp->abrirDetalleCatatroIndividualProducto($conexion, $idCatastro);
		echo '<label>Fin Rango: </label>';
		echo '<select id="finRango" name="finRango">';
		echo '<option value="">Seleccione...</option>';
		while ($fila = pg_fetch_assoc($qDetalleCatastro)){
			echo '<option value="'. $fila['identificador_producto'].'">'. $fila['identificador_producto'] .'  </option>';
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
		
	case 'buscarSitioCatastroIdentificador':
	    
	    $identificadorProducto = $_POST['identificadorProducto'];			   
	    
	    $qProducto = $cp->buscarCatastroIdentificadorProductoXIdentificadorProductoXEstado($conexion, $identificadorProducto, 'inactivo');
	    $producto = pg_fetch_assoc($qProducto);
	    
	    if(pg_num_rows($qProducto) == 0){
	        
	        echo 'El identificador del producto no se encuentra registrado.';
	    
	    }else{
	        
	        echo '<table id="tablaProductoMovilizar" style="width: 100%">
        			<thead>
        			<tr><th>Producto</th><th>Sitio</th><th>Provincia</th><th>Id. productor</th><th>Estado</th><th>Vigencia vacuna</th></tr>
        			</thead>
                    <tbody><tr><td>' . $producto['nombre_producto'] . '</td><td>' . $producto['nombre_lugar'] . '<input type="hidden" id="idSitioOrigen" name="idSitioOrigen" value="' . $producto['id_sitio'] . '"/></td><td>' . $producto['provincia'] . '</td><td>' . $producto['identificador_operador'] . '</td><td>' . $producto['estado_registro'] . '</td><td>' . $producto['fecha_vencimiento'] . '</td></tr></tbody>
        			</table><input type="hidden" id="identificacionOperadorOrigen" name="identificacionOperadorOrigen" value="' . $producto['identificador_operador'] . '" /> 
                    <input type="hidden" id="idCatastro" name="idCatastro" value="' . $producto['id_catastro'] . '" />
                    <input type="hidden" id="idProducto" name="idProducto" value="' . $producto['id_producto'] . '" /> 
                    <input type="hidden" id="idSitioOrigen" name="idSitioOrigen" value="' . $producto['id_sitio'] . '" /> 
                    <input type="hidden" id="idAreaOrigen" name="idAreaOrigen" value="' . $producto['id_area'] . '" /> 
                    <input type="hidden" id="idOperacionOrigen" name="idOperacionOrigen" value="' . $producto['id_tipo_operacion'] . '" /> 
                    <input type="hidden" id="unidadComercial" name="unidadComercial" value="' . $producto['unidad_comercial'] . '" /> ';			        
	    }
	
	break;
			
	case 'buscarSitioIdentificadorOperador':
	
	    $identificadorOperador = $_POST['identificadorOperador'];
	    $provincia = $_POST['provincia'];
	    
	    $qSitiosOperador = $cp->buscarSitiosXIdentificadorOperadorXProvincia($conexion, $identificadorOperador, $provincia);
	    
	    echo '<label>Sitio Destino: </label> 
	         <select id="idSitioDestino" name="idSitioDestino">
	         <option value="">Seleccione...</option>';
	    while ($fila = pg_fetch_assoc($qSitiosOperador)){
	        echo '<option value="'.$fila['id_sitio'].'">'.$fila['nombre_lugar'].'</option>';
	    }

	    echo '</select><input type="hidden" id="identificacionOperadorDestino" name="identificacionOperadorDestino" value="' . $identificadorOperador . '" />';	
	    
	break;		

	case 'listaAreaXSitioProducto':
	    
	    $qAreasDestino = $cmp->listarAreasOperacionesPorSitio($conexion, $_POST['idSitioDestino'], null, null, $_POST['idProducto']);
		
	    
	    $area.='<option value="">Seleccione...</option>';
	    while ($fila = pg_fetch_assoc($qAreasDestino)){
	        $area.='<option value="'.$fila['id_area'].'" data-id-tipo-operacion="'. $fila['id_tipo_operacion'].'">'.$fila['nombre_area'].' - '.$fila['id_area_tematica'].'</option>';
	    }
	    
		if(pg_num_rows($qAreasDestino)==0){
		    $area.= '<option value="0" >Aún no se ha registrado el producto para el área destino seleccionada...</option>';
		}
		
		echo '<label>Destino: </label>
	     <select id="idAreaDestino" name="idAreaDestino" >';
		echo $area;
		echo '</select>';
		
	break;

	case 'identificadoresCantidadCatastro':

		$banderaCantidadCatastro = false;

		$qBuscarSerieArete = $cc->buscarSerieArete ($conexion, 6 , strtoupper($_POST['identificadorInicial']));
			
			if (pg_num_rows ( $qBuscarSerieArete ) == 1) {
				if (pg_fetch_result($qBuscarSerieArete, 0, 'estado') == 'utilizado'){
					$idendificadorError = ' #' . strtoupper($_POST['identificadorInicial']) . ' utilizado';
				}else{
					$banderaCantidadCatastro = true;
				}
			}else{
				$idendificadorError = ' #' . strtoupper($_POST['identificadorInicial']) . ' no existe';
		}

		if (!$banderaCantidadCatastro) {

			echo 'El identificador es incorrecto: ' . $idendificadorError;
		
		}else{
		
			$arrayIdentificadoresCantidadCatastro = array(); 

			$qIdentificadoresCantidadCatastro = $cp->obtenerIdentificadoresPorCantidadCatastro($conexion, $_POST['cantidadCatastro'], $_POST['identificadorInicial']);
			
			while($identificadoresCantidadCatastro = pg_fetch_assoc($qIdentificadoresCantidadCatastro)){
				$arrayIdentificadoresCantidadCatastro[] = ($identificadoresCantidadCatastro['numero_arete']);
			}

			echo '<button type="button" name="agregarIdentificadoresCatastro" id="agregarIdentificadoresCatastro" > Agregar identificadores </button>';

			/*echo "<pre>";
			print_r($arrayIdentificadoresCantidadCatastro);
			echo "<pre>";*/
		}

	break;	
	    
   default:
   echo 'Tipo desconocido';
}

?>
<script type="text/javascript">
	
	$(document).ready(function(event){		
		distribuirLineas();	
		
	});

	$("#sitio").change(function(event){  	
		if($("#sitio").val() != 0){			
			$("#identificadorOperador").val($('#sitio option:selected').attr('data-identificador-operador'));		
			$('#nuevoCatastroProducto').attr('data-destino','resultadoOperaciones');
			$('#nuevoCatastroProducto').attr('data-opcion','accionesCatastro');
			$('#opcion').val('listaOperaciones');
			event.stopImmediatePropagation();
			abrir($("#nuevoCatastroProducto"),event,false);
			$("#sitio").removeClass("alertaCombo");
			distribuirLineas();				
		}	 		
	 }); 

	$("#operacion").change(function(event){
		if($("#operacion").val() != 0){
			$('#nuevoCatastroProducto').attr('data-destino','resultadoAreas');	
			$('#nuevoCatastroProducto').attr('data-opcion','accionesCatastro');
			$('#opcion').val('listaAreas');	
			event.stopImmediatePropagation();
			abrir($("#nuevoCatastroProducto"),event,false);	
			$("#operacion").removeClass("alertaCombo");
			distribuirLineas();
		}
	 }); 

	$("#area").change(function(event){
		if($("#area").val() != 0){
			$('#nuevoCatastroProducto').attr('data-destino','resultadoProductos');	
			$('#nuevoCatastroProducto').attr('data-opcion','accionesCatastro');
			$('#opcion').val('listarProductos');
			event.stopImmediatePropagation();	
			abrir($("#nuevoCatastroProducto"),event,false);
			$("#area").removeClass("alertaCombo");
			distribuirLineas();
		}			 		
	}); 

	$("#producto").change(function(event){
		if($("#producto").val()!=0){

			var operacionesOperador = $("#operacionesOperador").val();

			$("#codigoProductoTemp").val($('#producto option:selected').attr('data-codigo-producto'));

			//('select[name="unidadMedida"]').find('option[data-unidad-medida="'+$('#producto option:selected').attr('data-unidad-medida')+'"]').prop("selected","selected");	
			$("#diasInicioEtapa").val($('#producto option:selected').attr('data-dias-inicio-etapa'));	
			$("#diasFinEtapa").val($('#producto option:selected').attr('data-dias-fin-etapa'));
			$("#codigoEspecie").val($('#producto option:selected').attr('data-codigo-especie'));
		 	$("#idEspecie").val($('#producto option:selected').attr('data-id-especie'));
		 	
			//Tipo de catastro
			
		 	if(operacionesOperador.indexOf('OCC') > -1){
                mostrarTipoCatastro('lote');
			}else if(operacionesOperador.indexOf('OPI') > -1){
                mostrarTipoCatastro('lote');
			}else if(operacionesOperador.indexOf('OPT') > -1){
                mostrarTipoCatastro('identificador');
			}else if(operacionesOperador.indexOf('COM') > -1){
                mostrarTipoCatastro('identificador');
			}else if(operacionesOperador.indexOf('COD') > -1){
                mostrarTipoCatastro('identificador');
			}else if(operacionesOperador.indexOf('PRO') > -1){
                mostrarTipoCatastro('identificador');
			}
			
			$('#nuevoCatastroProducto').attr('data-destino','resultadoReproduccion');	
			$('#nuevoCatastroProducto').attr('data-opcion','accionesCatastro');
			$('#opcion').val('controlReproduccion');	
			event.stopImmediatePropagation();
			abrir($("#nuevoCatastroProducto"),event,false);		

			$("#producto").removeClass("alertaCombo");
			
		}	
		
		distribuirLineas();	
	});

	$("#agregarIdentificadoresCatastro").click(function(event){
    
        event.preventDefault();
        
        $("#estado").html("").removeClass('alerta');
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;
        
        arrayIdentificadoresCantidadCatastro = [];
        
        arrayIdentificadoresCantidadCatastro = <?php echo json_encode($arrayIdentificadoresCantidadCatastro); ?>;
        
        cantidadCampos = $('#detalleIdentificadoresCatastro tr').find('td').find('input[name="identificador[]"]').length;
        cantidadCatastro = <?php echo json_encode($_POST['cantidadCatastro']); ?>;
        
        if((cantidadCatastro <= cantidadCampos) || cantidadCatastro == 0){
        
        	$.each(arrayIdentificadoresCantidadCatastro, function (indice, valor) { 
        	$('#detalleIdentificadoresCatastro tr').find('td').find('input[name="identificador[]"]').eq(indice).val(valor)
        
        	});
        
        }else{
        
        	error = true;
        	$("#cantidadCatastro").addClass("alertaCombo");
        	$("#estado").html("Ingrese una cantidad válida dentro del rango a catastrar.").addClass('alerta');
        
        }
    
    });
	 
    $(".icono").click(function(event){
    		$('#imprimirIdentificadoresProducto').attr('data-opcion','darBajaDetalleCatastro'); 
    		$("#imprimirIdentificadoresProducto").attr('data-accionEnExito', 'ACTUALIZAR');
    });
    
    $("#canton").change(function(event){
    	if($("#canton").val()!=0){
    		if($("#canton").val()!='todos'){
    		$('#nuevoFiltroRegistroCatastro').attr('data-destino','resultadoParroquias');
    		$('#nuevoFiltroRegistroCatastro').attr('data-opcion','accionesCatastro');
    	    $('#opcion').val('listaParroquias');		
    	    event.stopImmediatePropagation();
    		abrir($("#nuevoFiltroRegistroCatastro"),event,false); 
    		}else{
    			$("#parroquia").attr("disabled",true);
    		}
    	}
    });	
    
    $("#idSitioDestino").change(function(event){
    	if($("#idSitioDestino").val()!=0){
    		event.stopImmediatePropagation();
    		$('#abrirActivarPorcino').attr('data-destino','resultadoSitio');
    		$('#abrirActivarPorcino').attr('data-opcion','accionesCatastro');
    	    $('#opcion').val('listaAreaXSitioProducto');			    	
    		abrir($("#abrirActivarPorcino"),event,false); 
    	}
    });
    
    $("#idAreaDestino").change(function(event){
    	if($("#idAreaDestino").val()!=0){	
    		$('#operacionDestino').val($('#idAreaDestino option:selected').attr('data-id-tipo-operacion'));	
    	}					 		
    });

	 
</script>