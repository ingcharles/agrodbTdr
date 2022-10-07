<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';


$conexion = new Conexion();
$cl = new ControladorLotes();
$ca = new ControladorAdministrarCaracteristicas();



$producto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
$proveedor = htmlspecialchars ($_POST['cbProveedor'],ENT_NOQUOTES,'UTF-8');
$codigoProducto= htmlspecialchars ($_POST['codigoProducto'],ENT_NOQUOTES,'UTF-8');
$opcion = $_POST['opcion'];
$operador = $_POST['usuario'];
$areaProveedor=$_POST['nSitioProveedor'];


switch ($opcion){
    
    case 'proveedor':
        
        $productoFila = $cl->listarProveedoresPorProducto($conexion, $producto,$usuario);
        $parametro=pg_fetch_assoc($cl->obtenerParametroxIDProducto($conexion, $producto));
        
        if($parametro['areas']==1){
            $producto = $cl->sitiosXidProductoAcopiador($conexion, $codigoProducto,$usuario,"ACO");
            echo '
			<div data-linea="2" id="resultadoSitio" >
			<label for="sitio">Area de acopio: </label>
		 	<select id="sitio" name="sitio">
		 	<option value="">Seleccione....</option>';
            while($fila=pg_fetch_assoc($producto)){
                echo '<option value="' . $fila['codigo'] .'">' . $fila['nombre_area'] . '</option>';
            }
            
            echo '</select>
			      <input type="hidden" id="nSitio" name="nSitio">
                  </div>';
        }
        
        echo'<input type="hidden" value="'.$parametro['areas'].'" id="CAreas">';
        echo'<input type="hidden" value="'.$parametro['proveedores'].'" id="CProveedor">';
        echo'<input type="hidden" value="'.$parametro['areas_proveedor'].'" id="CAreaProveedor">';
        
        echo '<div data-linea="5" id="resultadoProveedor" >
    		  <label for="cbProveedor">Nombre del Proveedor: </label>
    		  <select id="cbProveedor" name="cbProveedor">
    		  <option value="">Seleccione....</option>';
        
        while($fila=pg_fetch_assoc($productoFila)){
            echo '<option value="' . $fila['identificador_proveedor'] .'">' . $fila['nombre_proveedor'] . '</option>';
        }
        
        echo '</select>
			  <input type="hidden" id="nproveedor" name="nproveedor">
			  <div id="areaResultado"></div>';
        
        $fila=pg_num_rows($productoFila);
        if($fila<1){
            echo"<input type=hidden value=0 id=totalProveedores>";
        } else{
            echo"<input type=hidden value=1 id=totalProveedores>";
        }
        
        echo '</div>';        
 
        
        break;
			
	case 'registros':
		//$registro = $cl->listarRegistrosProveedor($conexion,$operador, $proveedor,$producto);
		
		$conexion->ejecutarConsulta("begin;");
		
		$ca->estructurarTabla($conexion, 'v_caracteristica', 'g_trazabilidad.registro', 'id_registro');
		
		$formulario=pg_fetch_assoc($ca->obtenerFormulario($conexion, "nuevoProductoProveedor"));
		//$formulario=pg_fetch_assoc($res);
		
		if($formulario>0){	    
		    
		    $registrosTotal=pg_fetch_assoc($ca->obtenerRegistrosTabla($conexion, 'v_caracteristica',$producto));
		    
		    if($registrosTotal>0){
		        $ca->pivotearColumnas($conexion, 'tmp_c','v_caracteristica', $producto, $formulario['id_formulario'], "'id_registro'", "'etiqueta'", 'nombre');
		        $registro = $cl->listarRegistrosProveedorAreaMasCaracteristicas($conexion,$operador, $proveedor,$producto, $areaProveedor);
		    } else{
		        $registro = $cl->listarRegistrosProveedorArea($conexion,$operador, $proveedor,$producto, $areaProveedor);
		    }
		}		
		
		echo '<label>Seleccione uno o varios Productos</label>
		
				<div class="seleccionTemporal">
					<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" />
			    	<label for="cTemporal">Seleccionar todos </label>
				</div>
		
				<hr>
			 
                <div id="contenedorProducto"><table style="width:100%" id="tablaLotes">
				
				<thead>
				<tr style="text-align:center">
					<th>Seleccionar</th>
					<th>ID</th>
					<th>Codigo Ingreso</th>
					<th>Fecha Ingreso</th>
					<th>Proveedor</th>					
					<th>Cant</th>';
				
		$campos = $cl->obtenerCamposCaracteristicas($conexion, $producto,$formulario['id_formulario']);
		while ($nCampos = pg_fetch_assoc($campos)){
		    echo '<th>'.$nCampos['etiqueta'].'</th>';
		}
		
		echo    '</tr>
				</thead>
				<tbody id="bodyTablaLotes">
		    
				';
		$agregarDiv = 0;
		$cantidadLinea = 0;
		
	
		while ($fila = pg_fetch_row($registro)){
		    
		    echo '<tr id="R'.$fila[0].'" ><td><input id="'.$fila[0].'" type="checkbox" name="'.$fila[0].'" class="productoActivar" data-resetear="no" value="'.$fila[0].'" />
			 	</td>'.
			 	"<td style=text-align:center>".$fila[0]."</td>".
			 	"<td style=text-align:center>".$fila[1]."</td>".
			 	"<td style=text-align:center>".date('Y-m-d',strtotime($fila[2]))."</td>".
			 	"<td style=text-align:center>".$fila[3]."</td>".
			 	"<td style=text-align:center>".$fila[4]."</td>";
		    
		    $con=6;
		    if(count($fila)>6){
		        while($con<count($fila)){
		            if ($fila[$con]!=''){
		                echo "<td style=text-align:center>".$fila[$con]."</td>";
		            } else{
		                echo "<td style=text-align:center>S/N</td>";
		            }
		            $con+=1;
		        }
		    }
		    
		    echo "</tr>";
		}
		
		echo '</tbody></table>';
		
		if(pg_num_rows($registro)==0){			
			echo'<script type="text/javascript">$("#estado").html("El proveedor seleccionado no tiene registros ingresados.").addClass("alerta");
					$("#agregarRegistro").attr("disabled","disabled")</script></script>';
			if($proveedor=="")
				echo '<script type="text/javascript">$("#estado").html("");</script>';
		} else{
			echo'<script type="text/javascript">$("#estado").html("");</script>';
		}		
		echo'</div>';		
		
		$conexion->ejecutarConsulta("commit;");
	break;
	
	
	case 'sitioProveedor':
	    $producto = $cl->sitiosXidProductoAcopiador($conexion, $producto,$proveedor);
	    echo "<label for=sitioProveedor>Área Proveedor: </label>
		 	<select id=sitioProveedor name=sitioProveedor>
		 	<option value=>Seleccione....</option>";
	    while($fila=pg_fetch_assoc($producto)){
	        echo '<option value="' .$fila['nombre_area'].'@'.$fila['id_area'] .'">' . $fila['nombre_area'] . '</option>';
	    }
	    
	    echo "</select>
			<input type=hidden id=nSitioProveedor name=nSitioProveedor>";
    break;
		
}


?>


<script type="text/javascript">

var suma=0;
$(document).ready(function(){
	distribuirLineas();	
	$("#cantidad").numeric();	
	$("#cantidad").attr('maxlength','10');	
	var fecha = new Date();
	var dd=("00" + fecha.getDate()).slice (-2); 
	var mm=("00" + (fecha.getMonth()+1)).slice (-2); 
	var yy=fecha.getFullYear();	
	$("#codigoLote").attr('maxlength','30');


$('#cantidad').keyup(function(e){

    if($(this).val().indexOf('.')!=-1){         
        if($(this).val().split(".")[1].length > 2){ 
            this.value = this.value.substring(0,this.value.length-1);                        
        }  
     }    
    
	});


$('#cantidad').focusout(function(e){
	
    if($(this).val().indexOf('.')!=-1){         
        if($(this).val().split(".")[1].length > 2){  
            this.value = this.value.substring(0,this.value.length-1);                        
        }  
     } 
})
;

$("#paisDestino").change(function(event){
	$("#idPaisDestino").val($("#paisDestino option:selected").text());
});

$("#loteTipo").change(function(event){
	$("#nLoteTipo").val($("#loteTipo option:selected").text());
});

	
});

function obtenerVariedad(){
    $("#loteVariedad").val($('#tablaLotesConformar tbody tr').find("td").eq(5).html());
    $("#idLoteVariedad").val($('#tablaLotesConformar tbody tr').find("td").eq(4).html());    
}

function obtenerFecha(){
	var fecha = new Date();
	var dd=("00" + fecha.getDate()).slice (-2); 
	var mm=("00" + (fecha.getMonth()+1)).slice (-2); 
	var yy=fecha.getFullYear();
	$("#fechaConformacion2").val(yy+"-"+mm+"-"+dd);
}

function parseLocalNum(num) {
   return +(num.replace(",", "."));
}


$("#nombreProveedor").change(function(event){	
	if($.trim($("#nombreProveedor").val())){
		$("#identificacionProveedor").val($("#nombreProveedor").val());	
		$("#nproveedor").val($("#nombreProveedor option:selected").text());
	}	
});

$("#variedad").change(function(event){	
	if($.trim($("#variedad").val())){		
		$("#nvariedad").val($("#variedad option:selected").text());
	}	
});

$("#cTemporal").click(function(e){
	if($('#cTemporal').is(':checked')){
		$('.productoActivar').prop('checked', true);
	}else{
		$('.productoActivar').prop('checked', false);
	}
});

$(".productoActivar").click(function(e){
	if(!$('#productoActivar').is(':checked')){
		$('#cTemporal').prop('checked', false);
	}
});


$("#sitioProveedor").change(function(event){

	event.preventDefault();
	var valor1=$("#sitioProveedor").val();
	var valor2= valor1.split("@");		
	var area = valor2[0].substring(valor2[0].length -8, valor2[0].length);		
	
	if ($.trim($("#sitioProveedor").val()) == "" ) {
		$("#agregarRegistro").attr("disabled","disabled");	        
    } else{
    	$("#nSitioProveedor").val(area);
    	$("#idAreaProveedor").val(valor2[1]);
    	$("#agregarRegistro").removeAttr("disabled");
    	event.stopImmediatePropagation();
		
		var table = document.getElementById('tablaLotesConformar');	    
 		var rowCount = table.rows.length; 		
 		if(rowCount > 1){	 		 
	 		$("#cbProducto").attr("disabled",false);	 		
 		}	 		

 		var filas = $("#bodyTablaLotesConformar tr").length;	 		

 		if(filas>1){
			if($("#CProveedor").val()=="1"){    				
	 			$("#cbProveedor").removeAttr("disabled");    	 	
	 			$("#sitio").removeAttr("disabled");
			}
 		}

 		if($("#CProveedor").val()=="1"){
 			$("#cbProveedor").attr("disabled",false);	
 		} 		
				
 		$("#abrirLote").attr('data-destino','dRegistro');
 		$("#abrirLote").attr('data-opcion', 'comboEditarLote');
 		$("#opcion").val('registros');
 		abrir($("#abrirLote"),event,false);

 		if($("#CProveedor").val()=="1"){
 	 		$("#cbProveedor").attr("disabled",true);
 		}

 		if(filas >1){
	 		if($("#CProveedor").val()=="1"){
	 		//	$("#cbProveedor").attr("disabled",true);    	 			
			} 
 		}

 		if(rowCount > 1){
	 		$("#cbProducto").attr("disabled",true);	 			
 		}
    }
	

});


</script>
		
