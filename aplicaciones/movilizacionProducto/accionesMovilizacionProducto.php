<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatastroProducto.php';

$conexion = new Conexion();
$cmp = new ControladorMovilizacionProductos();
$cc = new ControladorCatalogos();
$cro = new ControladorRegistroOperador();
$cp = new ControladorCatastroProducto();

set_time_limit(1000);

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {
    
    case 'listaOficinasProvincias':
                       
        $qOficinasProvincias = $cmp->listaOficinasXprovincias($conexion,$_POST['provinciaEmision'],'3');
        echo '<label>Oficina Emisión: </label>';
        echo '<select id="oficinaEmision" name="oficinaEmision">';
        echo '<option value="">Seleccione...</option>';
        while ($fila = pg_fetch_assoc($qOficinasProvincias)){
            echo '<option value="'. $fila['nombre'].'" >'.$fila['nombre'].'</option>';
        }
        echo '</select>';
        break;
        
    case 'listaSitiosOrigen':
        $buscarSitioOrigen = $cmp->filtrarSitio($conexion, $_POST['identificadorOperadorOrigen'], $_POST['nombreSitioOrigen'], $_POST['provinciaOrigen'],$_POST['nombreOperadorOrigen']);
        echo '<label>Sitio Origen: </label>';
        echo '<select id="sitioOrigen" name="sitioOrigen">';
        echo '<option value="0">Seleccione...</option>';
        while ($fila = pg_fetch_assoc($buscarSitioOrigen)){
            if($_POST['sitioDestino']!=$fila['id_sitio'])
                echo '<option data-operador-origen="'.$fila['identificador'].'" data-codigo-provincia-origen="'. $fila['codigo_provincia'].'"  value="'. $fila['id_sitio'].'" >'.$fila['nombre_lugar'].'</option>';
        }
        echo '</select>';
        break;
        
    case 'listaAreasOperacionesOrigen':
        
        if(isset($_POST['identificacionOperadorOrigen'])){
            
            $qOperacionesOperador = $cro->obtenerOperacionesXOperadorXIdAreaXCodigoOperacion($conexion, $_POST['identificacionOperadorOrigen'], 'SA', " in ('OPT', 'OPI', 'OCC', 'FER', 'FEA', 'PRO', 'COM', 'COD')");
            $operacionesOperador = pg_fetch_result($qOperacionesOperador, 0, 'codigo');
            
        }
        
        echo '<input type="hidden" id="operacionesOperador" name="operacionesOperador" value="'.$operacionesOperador.'" />';		    
        
        $qAreasOrigen = $cmp->listarAreasOperacionesPorSitio($conexion, $_POST['sitioOrigen']);
        echo '<label>Área Origen: </label>
				<select id="areaOrigen" name="areaOrigen" >
				<option value="0" >Seleccione...</option>';
        while ($fila = pg_fetch_assoc($qAreasOrigen)){
            echo '<option data-id-tipo-operacion="'. $fila['id_tipo_operacion'].'" data-nombre-operacion="'. $fila['nombre'].'" value="'. $fila['id_area'].'">'.$fila['nombre_area'].'</option>';
        }
        echo '</select>';
        
        break;
        
    case 'listaSitiosDestino':
        
        $buscarSitioDestino = $cmp->filtrarSitio($conexion, $_POST['identificadorOperadorDestino'], $_POST['nombreSitioDestino'], $_POST['provinciaDestino'],$_POST['nombreOperadorDestino'], null, null, $_POST['tipoDestino']);
        echo '<label>Sitio Destino: </label>
				<select id="sitioDestino" name="sitioDestino">
				<option value="0">Seleccione...</option>';
        while ($fila = pg_fetch_assoc($buscarSitioDestino)){
            if($_POST['sitioOrigen']!=$fila['id_sitio'])
                echo '<option data-operador-destino="'.$fila['identificador'].'" data-codigo-provincia-destino="'. $fila['codigo_provincia'].'" value="'. $fila['id_sitio'].'" >'.$fila['nombre_lugar'].'</option>';
        }
        echo '</select>';

        break;
        
    case 'listaAreasOperacionesDestino':
        
        $qProductoMovilizar = $cmp->listaProductosActosMovilizar($conexion, $_POST['areaOrigen'], $_POST['operacionOrigen'], $_POST['tipoDestino']);
        
        $idProductosOrigen = "";
        
        while ($fila = pg_fetch_assoc($qProductoMovilizar)){
            $idProductosOrigen .= $fila['id_producto'].",";
        }
        
        $idProductosOrigen = rtrim($idProductosOrigen, ",");
        $idProductosOrigenMovilizar = rtrim($idProductosOrigen, ",");
        
        if($_POST['tipoDestino'] == "matadero"){
            
            $idProductosOrigenInocuidad = "";
            
            $idProductosOrigen = ($idProductosOrigen == "") ? "null" : $idProductosOrigen;
            
            $qIdProductosOrigen = $cmp->consultarEquivalenciaProducto($conexion," in(".$idProductosOrigen.")", 'SA', 'AI');
            
            while ($fila = pg_fetch_assoc($qIdProductosOrigen)){
                $idProductosOrigenInocuidad .= $fila['id_producto_dos'].",";
            }
            
            $idProductosOrigen = rtrim($idProductosOrigenInocuidad, ",");
            
        }
        
        $qAreasDestino = $cmp->listarAreasOperacionesPorSitio($conexion, $_POST['sitioDestino'], null, null, $idProductosOrigen, $_POST['tipoDestino']);
        
        $area.='<option value="0" >Seleccione...</option>';
        while ($fila = pg_fetch_assoc($qAreasDestino)){
            $area.='<option data-id-area-operacion="'. $fila['codigo'].$fila['id_area_tematica'].'" data-id-tipo-operacion="'. $fila['id_tipo_operacion'].'" value="'. $fila['id_area'].'">'.$fila['nombre_area'].' - '.$fila['id_area_tematica'].'</option>';
        }
        
        if(pg_num_rows($qAreasDestino)==0)
            $area.= '<option value="0" >Aún no se ha registrado el producto para el área destino seleccionada...</option>';
            
            echo '<label>Área Destino: </label>
			<select id="areaDestino" name="areaDestino" >';
            echo $area;
            echo '</select>';
            echo '<input type="hidden" id="idProductosOrigenMovilizar" name="idProductosOrigenMovilizar" value="'.$idProductosOrigenMovilizar.'" />';
        
            break;
            
    case 'listaIdentificadoresProducto':
        
        $identificadores = identificadoresAgregados();
        $idProductosConVacuna = "";
        $arrayProductosMovilizar = array();

        $arrayIdProductosOrigen = explode(",", $_POST['idProductosOrigenMovilizar']);
        $arrayIdProductosOrigen = array_unique($arrayIdProductosOrigen);
        
        $qProductosDestino = $cmp->listarProductosConVacuna($conexion, 'vacunacion');
        while ($fila = pg_fetch_assoc($qProductosDestino)){
            $idProductosConVacuna .= $fila['id_producto'].",";
        }
        
        $idProductosConVacuna = rtrim($idProductosConVacuna, ",");
        $arrayIdProductosConVacuna = explode(",", $idProductosConVacuna);
        
        $idVacunados = array_intersect($arrayIdProductosOrigen, $arrayIdProductosConVacuna);
        //print_r($idVacunados);
        $idVacunados = implode($idVacunados, ',');
        
        $idNoVacunados = array_diff($arrayIdProductosOrigen, $arrayIdProductosConVacuna);
        //print_r($idNoVacunados);
        $idNoVacunados = implode($idNoVacunados, ',');
        
        $aProductosVacunados = array();
        $aProductosNoVacunados = array();
        
        $banderaCicloCerrado = false;
        
        if(!empty($idVacunados) || $idVacunados != null){
            //CONTROL PARA CICLO CERRADO
            $qOperadorModificacion = $cp->buscarOperadorModificacionIdentificador($conexion, $_POST['identificacionOperadorOrigen']);
            $banderaCicloCerrado = (pg_num_rows($qOperadorModificacion) > 0  && $_POST['identificacionOperadorOrigen'] == $_POST['identificacionOperadorDestino']) ? true : false;
            
            $qCatastroProducto = $cmp->listaLotesProductoMovilizacionVacunados($conexion, $_POST['areaOrigen'], "in (".$idVacunados.")", $_POST['operacionOrigen'], $_POST['unidadComercial'] , "(" . $identificadores . ")"/*, $banderaLote*/, $_POST['tipoDestino'], $banderaCicloCerrado);
            
            $qProductoIdentificadorMovilizarVacunado = $cmp->productosActosMovilizacionVacunados($conexion, $_POST['areaOrigen'], "in (".$idVacunados.")", $_POST['lotesProducto'], $_POST['operacionOrigen'], $_POST['unidadComercial'], "(" . $identificadores . ")", $_POST['tipoDestino'], $banderaCicloCerrado);
            
            while($productoIdentificadorMovilizarVacunado = pg_fetch_assoc($qProductoIdentificadorMovilizarVacunado)){
                
                $aProductosVacunados[] = array ('value' => $productoIdentificadorMovilizarVacunado['identificador_producto'], 'label' => $productoIdentificadorMovilizarVacunado['identificador_producto'].' - '.$productoIdentificadorMovilizarVacunado ['nombre_producto'].' -> '.$productoIdentificadorMovilizarVacunado ['estado_registro'], 'idProducto'=>$productoIdentificadorMovilizarVacunado ['id_producto'], 'nombreProducto'=>$productoIdentificadorMovilizarVacunado ['nombre_producto'], 'areaProducto'=>$_POST['areaOrigen'].$productoIdentificadorMovilizarVacunado ['id_producto'], 'idCatastro'=>$productoIdentificadorMovilizarVacunado ['id_catastro'] );
                
            }
            
        }
                
        if(!empty($idNoVacunados) || $idNoVacunados != null){
        
        $qProductoIdentificadorMovilizarNoVacunado = $cmp->productosActosMovilizacion($conexion,$_POST['areaOrigen'], "in (".$idNoVacunados.")", $_POST['lotesProducto'], $_POST['operacionOrigen'], $_POST['unidadComercial'],"(" . $identificadores . ")");
            
            while($productoIdentificadorMovilizarNoVacunado = pg_fetch_assoc($qProductoIdentificadorMovilizarNoVacunado)){
                $aProductosNoVacunados[] = array ('value' => $productoIdentificadorMovilizarNoVacunado['identificador_producto'], 'label' => $productoIdentificadorMovilizarNoVacunado['identificador_producto'].' - '.$productoIdentificadorMovilizarNoVacunado ['nombre_producto'].' -> '.$productoIdentificadorMovilizarNoVacunado ['estado_registro'], 'idProducto'=>$productoIdentificadorMovilizarNoVacunado ['id_producto'], 'nombreProducto'=>$productoIdentificadorMovilizarNoVacunado ['nombre_producto'], 'areaProducto'=>$_POST['areaOrigen'].$productoIdentificadorMovilizarNoVacunado ['id_producto'], 'idCatastro'=>$productoIdentificadorMovilizarNoVacunado ['id_catastro'] );
                
            }
            
        }
        
        $arrayProductosMovilizar = array_merge($aProductosVacunados, $aProductosNoVacunados);
        
        if(count($arrayProductosMovilizar) > 0){            
            echo '<label>N° Identificador: </label><input type="text" id="identificadorProductoAutocompletar" name="identificadorProductoAutocompletar" maxlength="14"/>';
        }else{
            echo "El operador origen no posee productos a movilizar.";
        }
        
        break;
        
        case 'listaLotesProducto':
            
            $identificadores = identificadoresAgregados();            
            $idProductosConVacuna = "";
                                   
            $arrayProductosMovilizar = array();
            $arrayIdProductosOrigen = explode(",", $_POST['idProductosOrigenMovilizar']);
            $arrayIdProductosOrigen = array_unique($arrayIdProductosOrigen);
            
            $qProductosDestino = $cmp->listarProductosConVacuna($conexion, 'vacunacion');
            while ($fila = pg_fetch_assoc($qProductosDestino)){
                $idProductosConVacuna .= $fila['id_producto'].",";
            }
         
            $idProductosConVacuna = rtrim($idProductosConVacuna, ",");
            $arrayIdProductosConVacuna = explode(",", $idProductosConVacuna);
            
            $idVacunados = array_intersect($arrayIdProductosOrigen, $arrayIdProductosConVacuna);
            $idVacunados = implode($idVacunados, ',');
            
            $idNoVacunados = array_diff($arrayIdProductosOrigen, $arrayIdProductosConVacuna);
            $idNoVacunados = implode($idNoVacunados, ',');
            
            
            $aProductosVacunadosLote = array();
            $aProductosNoVacunadosLote = array();
            
            $banderaCicloCerrado = false;
            
            if(!empty($idVacunados) || $idVacunados != null){
                
                //CONTROL PARA CICLO CERRADO
                $qOperadorModificacion = $cp->buscarOperadorModificacionIdentificador($conexion, $_POST['identificacionOperadorOrigen']);
                $banderaCicloCerrado = (pg_num_rows($qOperadorModificacion) > 0  && $_POST['identificacionOperadorOrigen'] == $_POST['identificacionOperadorDestino']) ? true : false;
                                
                $qCatastroProducto = $cmp->listaLotesProductoMovilizacionVacunados($conexion, $_POST['areaOrigen'], "in (".$idVacunados.")", $_POST['operacionOrigen'], $_POST['unidadComercial'] , "(" . $identificadores . ")"/*, $banderaLote*/, $_POST['tipoDestino'], $banderaCicloCerrado);
               
                while ($fila = pg_fetch_assoc($qCatastroProducto)){
                    $aProductosVacunadosLote[] = array ('totalLote' => $fila['total'], 'idProducto' => $fila['id_producto'], 'numeroLote' =>  $fila['numero_lote'], 'nombreProducto' => $fila['nombre_producto']);              
                }
                
            }
            
            if(!empty($idNoVacunados) || $idNoVacunados != null){
                
                $qCatastroProducto = $cmp->listaLotesProductoMovilizacion($conexion, $_POST['areaOrigen'], "in (".$idNoVacunados.")", $_POST['operacionOrigen'], $_POST['unidadComercial'], "(" . $identificadores . ")"/*, $banderaLote/*, $_POST['tipoDestino']*/);
                
                while ($fila = pg_fetch_assoc($qCatastroProducto)){
                    $aProductosNoVacunadosLote[] = array ('totalLote' => $fila['total'], 'idProducto' => $fila['id_producto'], 'numeroLote' =>  $fila['numero_lote'], 'nombreProducto' => $fila['nombre_producto']);
                }
                
                
            }
            
            $arrayProductosMovilizar = array_merge($aProductosVacunadosLote, $aProductosNoVacunadosLote);

            echo '<label>N° Lote: </label>';
            echo '<select id="lotesProducto" name="lotesProducto" >';
            echo '<option value="">Seleccione...</option>';
            
            foreach ($arrayProductosMovilizar as $llave){
                
                echo '<option data-total-lote="'.$llave['totalLote'].'" data-idProducto = "'.$llave['idProducto'].'"  value="'.$llave['numeroLote'].'">'.$llave['numeroLote'].' - '.$llave['nombreProducto'].'</option>';
                
            }
            
            echo '</select>';
            echo '<input type="hidden" id="banderaCicloCerrado" name="banderaCicloCerrado" value="'.$banderaCicloCerrado.'" />';
            

        break;
        
    case 'listaCantones':
        
        $provincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
        $qListaCantones=$cc->obtenerLocalizacionHijo($conexion,'CANTONES','PROVINCIAS',$provincia);
        echo '<select id="canton" name="canton" style="width:270px">
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
        echo '<select id="parroquia" name="parroquia" style="width:270px">
				<option value="">Seleccione...</option>
				<option value="todos">Todos</option>';
        while ($fila = pg_fetch_assoc($qListaParroquia)){
            echo '<option value="'.$fila['id_localizacion'].'">'.$fila['nombre'].'</option>';
        }
        echo '</select>';
        break;   
        
    case 'placaTransporte':
        
        $placaTrasporte = $_POST['placaTransporte'];
        
        $qNombreDuenioTransporte =  $cmp->obtenerDuenioTrasporteXPlaca($conexion, $placaTrasporte);
                       
        if(pg_num_rows($qNombreDuenioTransporte) > 0){
            $nombreDuenioTransporte = pg_fetch_assoc($qNombreDuenioTransporte);            
            echo '<div data-linea="4"><label>Nombre Propietario: </label><input type="text" name="nombrePropietario" id="nombrePropietario" value="'.$nombreDuenioTransporte['nombre_duenio_transporte'].'" readonly = "readonly" /></div>';            
        }else{
            echo '<div data-linea="4"><label>Nombre Propietario: </label><input type="text" name="nombrePropietario" id="nombrePropietario" placeholder="Ej: Carlos Pérez" maxlength="100" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü ]+$" /></div>';            
        }
        
        break; 
    
    case 'nombreConductor':
        
        $identificadorConductor = $_POST['identificadorConductor'];
        
        $qNombreDuenioTransporte =  $cmp->obtenerConductorXIdentificador($conexion, $identificadorConductor);
        
        if(pg_num_rows($qNombreDuenioTransporte) > 0){
            $nombreDuenioTransporte = pg_fetch_assoc($qNombreDuenioTransporte);
            echo '<div data-linea="4"><label>Nombre Conductor: </label><input type="text" name="nombreConductor" id="nombreConductor" value="'.$nombreDuenioTransporte['nombre_conductor'].'" readonly = "readonly" /></div>';
        }else{
            echo '<div data-linea="4"><label>Nombre Conductor: </label><input type="text" name="nombreConductor" id="nombreConductor"	placeholder="Ej: David Morán" maxlength="100" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü ]+$" /></div>';
        }
        
        break; 
        
    case 'resultadoDetalleIdentifcadoresMovilizacion':
    	$contador=1;
    	$identificadores=$_POST['identificadoresAgregados']==""?"''":rtrim ( $_POST['identificadoresAgregados'], ',' );
    	$qDetalleMovilizacionIdentificadores=$cmp->abrirDetalleMovilizacionIdentificadoresAgregados($conexion, $_POST['idDetalleMovilizacion'], "(" . $identificadores . ")");
    	
    	while($filaIdentificadores=pg_fetch_assoc($qDetalleMovilizacionIdentificadores)){
    		echo $cmp->imprimirIdentificadoresProducto($filaIdentificadores['id_detalle_movilizacion'],$filaIdentificadores['id_detalle_identificadores_movilizacion'], $filaIdentificadores['identificador'],$contador++);
    	}
    	
    	break;
    	
    case 'buscarEmpleadoRol':
        $qEmpleadoEmpresaRol = $cmp->buscarEmpleadoEmpresaRol($conexion, $_POST['operadorMovilizacion'], $_POST['identificadorEmpleado'], $_POST['nombreEmpleado']);
        echo '<label>Empleado: </label>';
        echo '<select id="empleado" name="empleado">';
        echo '<option value="0">Seleccione...</option>';
        while ($fila = pg_fetch_assoc($qEmpleadoEmpresaRol)){
            echo '<option data-identificador-empleado="'.$fila['identificador'].'" value="'. $fila['id_empleado'].'" >'.$fila['nombres']. ' - ' .$fila['identificador'].'</option>';
        }
        echo '</select>';
        break;
        
}

function identificadoresAgregados(){       
    if(is_array($_POST['hIdentificadoresValidar']) && count($_POST['hIdentificadoresValidar']) != 0){
        for($i = 0; $i < count ( $_POST['hIdentificadoresValidar'] ); $i ++) {
            $identificadores.= "'" . $_POST['hIdentificadoresValidar'][$i] . "',";
        }
    }else{
        $identificadores="''";
    }

    return rtrim($identificadores, ',');
}

?>

<script type="text/javascript">

var array_productos_movilizar = <?php echo json_encode($arrayProductosMovilizar); ?>

var operacionesOperador = $("#operacionesOperador").val();

$(document).ready(function(){
    distribuirLineas();        
              
    if($("#tablaDetalleMovilizacion tr").length > 0){

        valorTr = $("#tablaDetalleMovilizacion tr").find('input[name="hSitioDestino"]').val();

        $("#tablaDetalleMovilizacion tr").each(function (event) {
        
            $("#sitioDestino option").each(function(){
                
                if((valorTr != this.value) || this.value == 0){			
                    this.disabled = true;
                }

            });

        });
    }   

});

$("#sitioOrigen").change(function(event){	

    $("#resultadoIdentificador").hide();
    $("#agregarDetalleMovilizacion").hide();
    $("#tablaDetalles").hide();
    $("#identificadorProductoAutocompletar").val("");

	$("#operacionesOperador").hide();
	
	 if($("#sitioOrigen").val()!=0){
		 	 
		$("#sitioDestino").html("");
		$("#sitioDestino").append('<option value="0">Seleccione...</option>');
		$('#codigoProvinciaOrigen').val($('#sitioOrigen option:selected').attr('data-codigo-provincia-origen'));	
		$('#identificacionOperadorOrigen').val($('#sitioOrigen option:selected').attr('data-operador-origen'));		 		
		$('#nuevoMovilizacionProductos').attr('data-destino','resultadoAreasOperacionesOrigen');
		$('#nuevoMovilizacionProductos').attr('data-opcion','accionesMovilizacionProducto');	 
	    $('#opcion').val('listaAreasOperacionesOrigen');
	    event.stopImmediatePropagation();			
		abrir($("#nuevoMovilizacionProductos"),event,false); 	
		 	
	 }
});

$("#sitioDestino").change(function(event){

    //$("#resultadoIdentificador").hide();
    //$("#agregarDetalleMovilizacion").hide();
    //$("#tablaDetalles").hide();
    $("#identificadorProductoAutocompletar").val("");

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#sitioOrigen").val() !== "0"){

		event.preventDefault();
		event.stopImmediatePropagation();

		$("#lotesProducto").html("");
		$("#lotesProducto").append('<option value="">Seleccione...</option>');
		$("#cantidadExistentePorLote").val("");	
		$("#cantidad").val("");	

		$("#resultadoLote").hide();
        $("#resultadoIdentificador").hide();

		
		$('#areaDestino').val(0);
		$('#operacionDestino').val(0);
		$('#operacionDestinoCodigoArea').val('null');
		$('#codigoProvinciaDestino').val($('#sitioDestino option:selected').attr('data-codigo-provincia-destino'));	 		
		$('#identificacionOperadorDestino').val($('#sitioDestino option:selected').attr('data-operador-destino'));		 	

		if($("#sitioDestino").val()!=0){	
			$('#nuevoMovilizacionProductos').attr('data-destino','resultadoAreasOperacionesDestino');
			$('#nuevoMovilizacionProductos').attr('data-opcion','accionesMovilizacionProducto');
			$('#opcion').val('listaAreasOperacionesDestino');		
		    event.stopImmediatePropagation();
			abrir($("#nuevoMovilizacionProductos"),event,false);
		}	

	}else{

		error = true;
		$("#provinciaOrigen").addClass("alertaCombo");
		$("#identificadorOperadorOrigen").addClass("alertaCombo");
		$("#estado").html('Por favor ingrese datos de origen').addClass("alerta");
		
	}
		
});

$("#areaOrigen").change(function(event){
    
    $("#sitioDestino").html("");
    $("#sitioDestino").append('<option value="0">Seleccione...</option>');
    $("#areaDestino").html("");
	$("#areaDestino").append('<option value="0">Seleccione...</option>');

	if($("#areaOrigen").val()!=0){
		//$('#areaDestino').val("0");
		$('#operacionOrigen').val($('#areaOrigen option:selected').attr('data-id-tipo-operacion'));	
		$('#gNombreOperacion').val($('#areaOrigen option:selected').attr('data-nombre-operacion'));	
	}					 		
});


$("#areaDestino").change(function(event){

    event.preventDefault();	
	event.stopImmediatePropagation();	

	if($("#areaDestino").val()!=0){	
				
		if(operacionesOperador.indexOf('OPI') > -1){			

            $("#grupoModoMovilizarLote1").show();
            $("#grupoModoMovilizarLote2").show();
            $("#grupoModoMovilizarLote3").show();
        
            $('input:radio[name=modoMovilizarLote]').prop("checked", false); 

		}else if(operacionesOperador.indexOf('OPT') > -1){

            visualizarDatos("identificador");

		}else if(operacionesOperador.indexOf('FER') > -1){

            visualizarDatos("identificador");
    		
		}else if(operacionesOperador.indexOf('FEA') > -1){

            visualizarDatos("identificador");
    		
		}else if(operacionesOperador.indexOf('PRO') > -1 ){

            visualizarDatos("identificador");

		}else if(operacionesOperador.indexOf('COM') > -1 ){

            visualizarDatos("identificador");

		}else if(operacionesOperador.indexOf('COD') > -1 ){

            visualizarDatos("identificador");

		}		
	}					 		
});

$('input:radio[name=modoMovilizarLote]').change(function(event){

    event.preventDefault();	
    event.stopImmediatePropagation();

    if($('input:radio[name=modoMovilizarLote]:checked').val() == "cantidad"){ 

        quitarTablaDetalleMovilizacion();
        visualizarDatos("loteCantidad");

    }else if($('input:radio[name=modoMovilizarLote]:checked').val() == "areteIdentificador"){ 

        visualizarDatos("loteIdentificador");

    }

});

$(function(){
	
	var data = array_productos_movilizar;
	$("#identificadorProductoAutocompletar").autocomplete({				 
		source: data,
		minLength: 5,
		
		select: function(event, ui){
        	$("#gNombreProducto").val(ui.item.nombreProducto);
        	$("#gIdentificadorProducto").val(ui.item.value);
        	$("#gProducto").val(ui.item.idProducto);
        	$("#gAreaProducto").val(ui.item.areaProducto);
        	$("#gIdCatastro").val(ui.item.idCatastro);
		},change:function(event, ui){
    		if (ui.item == null || ui.item == undefined) {
    			$("#identificadorProductoAutocompletar").val("");
    		}
		}
	});
	
});

$("#canton").change(function(event){
	if($("#canton").val()!=0){
		if($("#canton").val()!='todos'){
		$('#nuevoMovilizacionProductos').attr('data-destino','resultadoParroquias');
		$('#nuevoMovilizacionProductos').attr('data-opcion','accionesMovilizacionProducto');
	    $('#opcion').val('listaParroquias');		
	    event.stopImmediatePropagation();
		abrir($("#nuevoMovilizacionProductos"),event,false); 
		}else{
			$("#parroquia").attr("disabled",true);
		}
	}
});

$("#lotesProducto").change(function(event){  
    
    event.preventDefault();	
	event.stopImmediatePropagation();

    if($('input:radio[name=modoMovilizarLote]:checked').val() == "cantidad"){ 

        if($("#lotesProducto").val() != "" ){
            $("#cantidadExistentePorLote").show();
            $("#cantidadExistentePorLote").val($('#lotesProducto option:selected').attr('data-total-lote'));
            $("#gProducto").val($('#lotesProducto option:selected').attr('data-idProducto'));		
        }else{
            $("#cantidadExistentePorLote").val("");
        }

    }else if($('input:radio[name=modoMovilizarLote]:checked').val() == "areteIdentificador"){

        accionesTipoMovilizacion("identificador");

    }

}); 


function visualizarDatos(categoria){

    switch(categoria){

        case "identificador":

            $("#tipoMovilizacion").val("identificador");
            $("#resultadoIdentificador").show();
            $("#agregarDetalleMovilizacion").show();
            $("#tablaDetalles").show();	
            $("#lotesProducto").hide();

            accionesTipoMovilizacion("identificador");

        break;

        case  "loteCantidad":

            $("#tipoMovilizacion").val("lote");
            $("#resultadoLote").show();
            $("#cantidadLote").show();
            $("#cantidadMovilizar").show();            
            $("#lotesProducto").show();            
            $("#resultadoIdentificador").hide();
            $("#agregarDetalleMovilizacion").hide();
            $("#tablaDetalles").hide();
            $("#identificadorProductoAutocompletar").val("");

            accionesTipoMovilizacion("lote");

        break;

        case  "loteIdentificador":

            $("#tipoMovilizacion").val("identificador");
            $("#resultadoIdentificador").show();
            $("#cantidadLote").hide();
            $("#cantidadMovilizar").hide();            
            $("#lotesProducto").show();  
			$("#agregarDetalleMovilizacion").show();
			$("#tablaDetalles").show();
            $("#cantidadExistentePorLote").val("");
            $("#cantidad").val("");

            accionesTipoMovilizacion("lote");
            
        break;


    }
}

function accionesTipoMovilizacion(categoria){

    switch(categoria){

        case "lote":

            $('#operacionDestino').val($('#areaDestino option:selected').attr('data-id-tipo-operacion'));	
            $('#operacionDestinoCodigoArea').val($('#areaDestino option:selected').attr('data-id-area-operacion'));	
            $('#nuevoMovilizacionProductos').attr('data-destino','resultadoLote');
            $('#nuevoMovilizacionProductos').attr('data-opcion','accionesMovilizacionProducto');
            $('#opcion').val('listaLotesProducto');		
            abrir($("#nuevoMovilizacionProductos"),event,false);

        break;

        case "identificador":

            $('#operacionDestino').val($('#areaDestino option:selected').attr('data-id-tipo-operacion'));	
    		$('#operacionDestinoCodigoArea').val($('#areaDestino option:selected').attr('data-id-area-operacion'));	
    		$('#nuevoMovilizacionProductos').attr('data-destino','resultadoIdentificador');
    		$('#nuevoMovilizacionProductos').attr('data-opcion','accionesMovilizacionProducto');
    		$('#opcion').val('listaIdentificadoresProducto');		
    		abrir($("#nuevoMovilizacionProductos"),event,false);

        break;

    }

}

$("#empleado").change(function(event){  
	if($("#empleado").val()){
		$("#identificacionEmpleado").val($('#empleado option:selected').attr('data-identificador-empleado'));	
	}
});

function quitarDetalleIdentificadoresMovilizacion(fila){
	$('#detalleMovilizacion tbody tr ').each(function (event) {
	if( $(this).find('input[id="hIdDetallee"]').val()==$("#detalleIdentificadoresMovilizacion tbody").find('input[id="hCodigoDetalleMovilizacion"]').eq($(fila).index()).val()){
			var cantidad=parseInt($(this).find("td").eq(4).html());
			var total=cantidad-1;
			$(this).find('input[id="hCantidadd"]').val(total);
			$(this).find("td").eq(4).html(total);
			var identif=$("#detalleIdentificadoresMovilizacion tbody").find('input[id="hDetalleIdentificador"]').eq($(fila).index()).val();
		   $("#identificadoresAgregados").val($("#identificadoresAgregados").val()+"'" +identif + "',"); 
		     		
		}
	 });
	 
	$("#resultadoDetalleIdentifcadoresMovilizacion tr ").eq($(fila).index()).remove();
}

</script>