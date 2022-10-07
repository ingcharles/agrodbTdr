<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cro= new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$identificador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
$tipoArea= htmlspecialchars ($_POST['areaOperacion'],ENT_NOQUOTES,'UTF-8');
$tipoProducto= htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
$subTipoProducto= htmlspecialchars ($_POST['subTipoProducto'],ENT_NOQUOTES,'UTF-8');
$sitios= htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
$identificadorOperador =  htmlspecialchars ($_POST['numero'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {

	case 'tipoOperacion':
		$operadores = $cc->listarOperaciones($conexion,$tipoArea);
		echo '<label>Operación </label>
				<select id="tipoOperacion" name="tipoOperacion" >
				<option value="" >Seleccione...</option>';
		while ($fila = pg_fetch_assoc($operadores)){
			echo '<option  value="'. $fila['id_tipo_operacion'].'" data-flujo='.$fila['id_flujo_operacion'].'>'.$fila['nombre'].'</option>';
		}
		echo '</select>';
	break;

	case 'tipoProducto':
		$tipoProductoArea = $cc-> listarTipoProductosXarea($conexion, $tipoArea);	
		echo '<label>Tipo de producto</label>
				<select id="tipoProducto" name="tipoProducto">
				<option value="" >Seleccione...</option>';	
		while ($fila = pg_fetch_assoc($tipoProductoArea)){
			echo  '<option  value="'. $fila['id_tipo_producto'].'">'.$fila['nombre'].'</option>';
		}	
		echo '</select>';

	break;

	case 'subTipoProducto':
		$subTipoProducto = $cc->listarSubTipoProductoXtipoProducto($conexion, $tipoProducto);
		echo '<label>Subtipo de producto</label>
				<select id="subTipoProducto" name="subTipoProducto"  >
				<option value="">Seleccione....</option>';
		while ($fila = pg_fetch_assoc($subTipoProducto)){
			echo '<option value="'.$fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
		}
		echo '</select>';
	break;

	case 'producto':
		$producto = $cc->listarProductoXsubTipoProducto($conexion, $subTipoProducto);
		echo '<label>Seleccione uno o varios Productos</label>
				<div class="seleccionTemporal">
				<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" />
				<label>Seleccionar todos</label>
				</div>
				<hr>
			 <div id="contenedorProducto"><table id="seleccionProducto" style="border-collapse: initial;"><tr>';
		$agregarDiv = 0;
		$cantidadLinea = 0;
		while ($fila = pg_fetch_assoc($producto)){
		    echo '<td><input id="'.$fila['id_producto'].'" type="checkbox" name="producto[]" class="productoActivar" data-resetear="no" data-nombreProducto="'.$fila['nombre_comun'].'" value="'.$fila['id_producto'].'" />
			 	<label for="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</label></td>';
			$agregarDiv++;

			if(($agregarDiv % 3) == 0){
				echo '</tr><tr>';
				$cantidadLinea++;
			}

			if($cantidadLinea == 9)
				echo '<script type="text/javascript">$("#contenedorProducto").css({"height": "250px", "overflow": "auto"}); </script>';
		}
		echo '</tr></table></div>';
	break;

	case 'sitio':
		$resultadoSitio = $cro->abrirSitio($conexion, $sitios);
		$datosSitios=pg_fetch_assoc($resultadoSitio);

		echo '<fieldset id="fsSitiosExistentes">
			<legend>Información del sitio, áreas y operaciones</legend>
			<input type="hidden" id="nombreProvincia" nombre="nombreProvincia" value="'.$datosSitios['provincia']. '">	
			<div data-linea="1"><label>Nombre del sitio: </label>'.$datosSitios['nombre_lugar'].'</div>
			<div data-linea="1"><label>Provincia: </label>'.$datosSitios['provincia'].'</div>
			<div data-linea="2"><label>Cantón: </label>'.$datosSitios['canton'].'</div>
			<div data-linea="2"><label>Parroquia: </label>'.$datosSitios['parroquia'].'</div>
			<div data-linea="3"><label>Dirección: </label>'.$datosSitios['direccion'].'</div>
			<div data-linea="3"><label>Latitud: </label>'.$datosSitios['latitud'].'</div>
			<div data-linea="4"><label>Longitud: </label>'.$datosSitios['longitud'].'</div>
			<div data-linea="4"><label>Zona: </label>'.$datosSitios['zona'].'</div>';
		$resultadoOperaciones=$cro->buscarAreaOperacionXSitio($conexion, $identificador, $datosSitios['id_sitio']);
		if(pg_num_rows($resultadoOperaciones)>0){
			$linea=5;
			$i=1;
			echo '<hr/>';
			while($fila = pg_fetch_assoc($resultadoOperaciones)){
				echo '<div data-linea='.$linea.'> Operacion # '.$i. ': '. $fila['nombre'].' - '.$fila['tipo_area'].' - '.$fila['nombre_area']. ' - ' .$fila['nombre_comun'].  '</div>' ;
				$i++;
				$linea++;
			}
		}
		echo '</fieldset>';
	break;
	
	case 'verificarCoincidenciaOperador':
	   	   	    
	    $contador = 1;
	    
	    $qOperacionesAreaOperador = $cro->obtenerOperacionesAreaTematicoPorOperador($conexion, $identificadorOperador);
	    
	    if(pg_num_rows($qOperacionesAreaOperador) > 0){
	        
	        echo '<button id="botonocultamuestra">Mostrar/Ocutar información</button>
                <div id="divocultamuestra" style="display:none;"><table width="100%" id="detalleOperacionesOperador">
				<thead>
        			<tr>
                        <th>N°</th>
                        <th>Id. Operador</th>
                        <th>Operador</th>
                        <th>Operación</th>
                        <th>Sitio</th>
                        <th>Área</th>
                    </tr>
                </thead>';
	       
    	    while($operacionesAreaOperador = pg_fetch_assoc($qOperacionesAreaOperador))
    	    echo '<tr>
                    <td>'.$contador++.'</td>
                    <td>'.$operacionesAreaOperador['identificador_operador'].'</td>
                    <td>'.$operacionesAreaOperador['nombre_operador'].'</td>
                    <td>'.$operacionesAreaOperador['nombre_tipo_operacion'].'</td>
                    <td>'.$operacionesAreaOperador['nombre_lugar'].'</td>
                    <td>'.$operacionesAreaOperador['nombre_area'].'</td>
                </tr>';
	    
	    }
	    
	    echo '</table></div>';
	    
	break;
}

?>
<script type="text/javascript"> 
	 $(document).ready(function(){		
		 distribuirLineas(); 
	 });
	 
	 $("#botonocultamuestra").click(function(){
		event.preventDefault();	

		$("#estado").html('').removeClass();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		$("#divocultamuestra").each(function() {
        	visualizar = $(this).css("display");
            if(visualizar == "block") {
                $(this).fadeOut('slow',function() {
                	$(this).css("display", "none");
                });
            }else{
                $(this).fadeIn('slow',function() {
                	$(this).css("display", "block");
                });
            }
        });
        
	});

	 $("#tipoOperacion").change(function(event){

		 if($("#tipoOperacion").length != 0){
			$("#resultadoTipoProducto").html('');
			$("#resultadoSubTipoProducto").html('');
			$("#resultadoProducto").html('');
		 }
	 	 
		 if($("#tipoOperacion").val() != 0){	
			 $("#idFlujo").val($('#tipoOperacion option:selected').attr('data-flujo'));		 		 
			 $('#datosOperadorSitio').attr('data-opcion','accionesOperadorMasivo');
    		 $('#datosOperadorSitio').attr('data-destino','resultadoTipoProducto');
    		 $('#opcion').val('tipoProducto');	
    		 event.stopImmediatePropagation();	
    		 abrir($("#datosOperadorSitio"),event,false);	
    		 $("#resultadoProducto").html('');			
		}	
	});
		 
	$("#tipoProducto").change(function(event){

		 if($("#tipoOperacion").length != 0){
			$("#resultadoSubTipoProducto").html('');
			$("#resultadoProducto").html('');
		 }

		if($("#tipoProducto").val() != 0){			 		 
			 $('#datosOperadorSitio').attr('data-opcion','accionesOperadorMasivo');
    		 $('#datosOperadorSitio').attr('data-destino','resultadoSubTipoProducto');
    		 $('#opcion').val('subTipoProducto');
    		 event.stopImmediatePropagation();		
    		 abrir($("#datosOperadorSitio"),event,false);	 	 			
		}
	});

	$("#subTipoProducto").change(function(event){

		if($("#subTipoProducto").length != 0){
			$("#resultadoProducto").html('');
		 }
		 
		if($("#subTipoProducto").val() != 0){		
			$('#nombreProducto').val($("#producto option:selected").text());	 		 
			 $('#datosOperadorSitio').attr('data-opcion','accionesOperadorMasivo');
    		 $('#datosOperadorSitio').attr('data-destino','resultadoProducto');
    		 $('#opcion').val('producto');
    		 event.stopImmediatePropagation();		
    		 abrir($("#datosOperadorSitio"),event,false);		
		}			 	
	});

	$("#cTemporal").click(function(e){
		if($('#cTemporal').is(':checked')){
			$('.productoActivar').prop('checked', true);
		}else{
			$('.productoActivar').prop('checked', false);
		}
	});
	
</script>