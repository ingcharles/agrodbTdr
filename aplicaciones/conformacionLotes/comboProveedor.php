<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';


$conexion = new Conexion();
$cl = new ControladorLotes();
$cc = new ControladorCatalogos();
$cac = new ControladorAdministrarCatalogos();
$ca = new ControladorAdministrarCaracteristicas();

$producto = htmlspecialchars ($_POST['productos'],ENT_NOQUOTES,'UTF-8');
$opcion = $_POST['opcion'];
$operador = $_POST['usuario'];
$proveedor = $_POST['proveedores'];
$cantidad = $_POST['cantidad'];
$division= $_POST['division'];
$codigoIngreso= $_POST['codigoIngreso'];
$idUnidad = $_POST['unidad'];

//$serie= $_POST['serie'];

switch ($opcion){
	case 'proveedor':		
		$proveedores = $cl->listarProveedoresPorProducto($conexion, $producto,$operador);
		$parametro=pg_fetch_assoc($cl->obtenerParametroxIDProducto($conexion, $producto));
		
		$res=($cl->obtenerProductoCondicion($conexion, $producto));
		$sinCondicion=pg_fetch_assoc($res);
		$totalOperacionesSin=pg_num_rows($res);
		$valdiarSin=1;
		
		if($totalOperacionesSin>0){
    		$res=$cl->OperacionSin($conexion, $operador, $producto, $sinCondicion['id_tipo_operacion']);    		
    		$operacionSin=pg_fetch_assoc($res);
    		if($operacionSin['count']==$totalOperacionesSin){
    		    $valdiarSin=1;
    		} else{
    		    $valdiarSin=0;
    		}  		
    		    		
    		$res=$cl->obtenerProductoCondicion($conexion, $producto,'and');
    		$condicion='';
    		$valdiarAnd=1;
    		$totalOperacionesAnd=pg_num_rows($res);
    		while($condicionAnd=pg_fetch_assoc($res)){
    		    $condicion.=" op.id_tipo_operacion=".$condicionAnd['id_tipo_operacion']. " or";
    		}	
    		
    		$totalAnd=pg_num_rows($res);
    		if($totalAnd>0){
    		    $res=$cl->OperacionAnd($conexion, $operador, $producto, "and (".trim($condicion,'or').")");    		    
    		    $operacionAnd=pg_fetch_assoc($res);
    		    if($operacionAnd['count']==$totalOperacionesAnd){
    		        $valdiarAnd=1;
    		    } else{
    		        $valdiarAnd=0;
    		    }
    		}    		
    		
    		$res=$cl->obtenerProductoCondicion($conexion, $producto,'or');
    		$condicion='';
    		$valdiarOr=1;
    		$totalOperacionesOr=pg_num_rows($res);
    		while($condicionOr=pg_fetch_assoc($res)){
    		    $condicion.=" op.id_tipo_operacion=".$condicionOr['id_tipo_operacion']. " or";
    		}
    		
    		$totalOr=pg_num_rows($res);
    		if($totalOr>0){
    		    $res=$cl->OperacionOr($conexion, $operador, $producto, "and (".trim($condicion,'or').")");
    		    $operacionOr=pg_fetch_assoc($res);
    		    if($operacionOr['count']==$totalOperacionesOr){
    		        $valdiarOr=1;
    		    } else{
    		        $valdiarOr=1;
    		    }
    		}
    		
    		if($valdiarSin==1 && $valdiarAnd==1 &&  $valdiarOr==1 ){
    		      
    		} else{
    		    echo'<script type="text/javascript">$("#estado").html("No tiene las operaciones necesarias para el producto seleccionado.").addClass("alerta");
                    desactivar();				
                    $("#agregarRegistro").attr("disabled","disabled");</script>';
    		}
		
		}//fin
		
		
		if($valdiarSin==1 && $valdiarAnd==1 &&  $valdiarOr==1 ){
		
    		if($parametro>0){
    		    
    		    echo'<input type="hidden" value="'.$parametro['areas'].'" id="CAreas">';
    		    echo'<input type="hidden" value="'.$parametro['proveedores'].'" id="CProveedor">';
    		    echo'<input type="hidden" value="'.$parametro['areas_proveedor'].'" id="CAreaProveedor">';
    		    
    		    
    		    
    		    echo '<div data-linea="5" id="resultadoProveedor" >		    
                        <label for="proveedores">Nombre del Proveedor: </label>
            		 	<select id="proveedores" name="proveedores">
            		 	<option value=>Seleccione....</option>';
    		    
    		    while($fila=pg_fetch_assoc($proveedores)){
    		        echo '<option value="' . $fila['identificador_proveedor'] .'">' . $fila['nombre_proveedor'] . '</option>';		        
    		    }
    		    
    		    echo '</select>                  
        			  <input type="hidden" id="nproveedor" name="nproveedor">
        			  <div id="areaResultado"></div>';
                     
    		        
    		        $fila=pg_num_rows($proveedores);
    		        if($fila<1){
    		            echo"<input type=hidden value=0 id=totalProveedores>";
    		        } else{
    		            echo"<input type=hidden value=1 id=totalProveedores>";
    		        }		        
    		        echo  '</div>';
    		        
    		}
		}
		
		
		
	break;
	
	case 'variedad':
		$variedad = $cl->listarVariedadesProductos($conexion,$producto);
		echo"			
			<label for=variedad>Variedad: </label>
			<select id=variedad name=variedad >
			<option value=>Seleccione....</option>";
		
		while($fila=pg_fetch_assoc($variedad)){
			echo '<option value="' . $fila['id_variedad'] .'">' . $fila['nombre'] . '</option>';
		}
		
		echo "</select>
			  <input type=hidden id=nvariedad name=nvariedad>";
	break;
	
	case 'cantidad':
		
		
		echo " <div data-linea='5'>
				<label for=cantidad>Cant. a Registrar Kg: </label>	
			  <input type=text id=cantidad name=cantidad>
		      </div>";
		
		echo"<div data-linea='5'>
			<label for=unidad>Variedad: </label>
			<select id=unidad name=unidad>
			<option value=>Seleccione....</option>";
		
		$res= $cc->listarUnidadesMedida($conexion);
		while($fila=pg_fetch_assoc($res)){
			echo '<option value="' . $fila['id_unidad_medida'] . '" >'. $fila['nombre'] .'</option>';
		}
		
		echo "</select>
			  <input type=hidden id=nUnidad name=nUnidad></div>";
	break;
	
	case 'area':		
		//$res = $cl->obtenerAreaSitoPorProveedorProducto($conexion,$producto,$proveedor);
		//$fila = $res=pg_fetch_assoc($res);		
		echo "<input type=hidden id=area name=area >
			  <input type=hidden id=nombreArea name=nombreArea >
			  <input type=hidden id=sitio name=sitio >
			  <input type=hidden id=nombreSitio name=nombreSitio >		
		";
		
	break;
	
	case 'unidad':
		$unidad = $cc->obtenerUnidadMedida($conexion, $idUnidad);
		$fila= pg_fetch_assoc($unidad);
		echo'<input type="hidden" id="codigoUnidad" name="codigoUnidad" value="'.$fila['codigo'].'">';
	break;
	
	case 'division':
		//echo"<label>cantidad $cantidad division $division </label>";
		$divisiones= $cantidad/$division;
		
		$codigo= $cl->autogenerarNumeroRegistro($conexion,$operador);
		$ncodigo = $codigo;
		$d=intval($divisiones*100);
		$d=$d/100;
		echo '<input type="hidden" id="serie" value="'.$ncodigo.'">';
		echo '<table style="width:100%" id="tablaDetalle">
				<thead><tr><th>Código de Ingreso</th><th>Cantidad Registro kg.</th><th>Opciones</th></tr></thead>				
				<tbody> 
				<tr><td style="width:30%;"><input type="hidden" name="nuevoCodigo[]"  style="width:60%;" value='.$codigoIngreso.'>'.$codigoIngreso.' </td><td ><input type="text" id="nuevaCantidad" class="nCantidad" onkeypress="soloNumericos()" name="nuevaCantidad[]" style="width:40%;" value="'.$d.'"></td><td style="width:10%"><button class="menos" disabled="disabled">Quitar</button></td></tr>';
		for($i=0;$i<$division-1;$i++){
			$formato=str_pad($ncodigo, 11, "0", STR_PAD_LEFT);
			//echo '<tr><td style="width:30%;">'.$formato.' </td><td style="width:20%;text-align:left"> <input type="text" name="nuevaCantidad[]"></td><td style="width:10%"><button class="menos">Quitar</button></td></tr>';
			echo '<tr><td style="width:30%;"><input type="hidden" name="nuevoCodigo[]"  style="width:60%;" value='.$formato.'>'.$formato.' </td><td > <input type="text" id="nuevaCantidad" class="nCantidad" onkeypress="soloNumericos()" name="nuevaCantidad[]" style="width:40%;" value='.round($divisiones,2).'></td><td style="width:10%"><button class="menos" onclick="delFilaActual(this);return false;">Quitar</button></td></tr>';
			$ncodigo+=1;
		}
					
		echo'</tbody>
			 </table>';	
		
		echo'<div style="text-align:center;width:100%"><button class="guardar" id="guardarDivision">Guardar División</button><div>';
		
	break;
	
	case 'sitioProveedor':
		$producto = $cl->sitiosXidProductoAcopiador($conexion, $producto,$proveedor);
		echo "<label for=areaProveedor>Área Proveedor: </label>
		 	<select id=areaProveedor name=areaProveedor>
		 	<option value=>Seleccione....</option>";
		while($fila=pg_fetch_assoc($producto)){
			echo '<option value="' . $fila['id_area'] .'">' . $fila['nombre_area'] . '</option>';
		}
		
		echo "</select>
			<input type=hidden id=nAreaProveedor name=nAreaProveedor>";
	break;
	
	case 'caracteristica':
	    $res=$ca->obtenerFormulario($conexion, "nuevoProductoProveedor");
	    //$res2=$res;
	    $fila=pg_fetch_assoc($res);
	    if($fila>0){
	       // $fila=pg_fetch_assoc($res);
	        $res=$ca->obtenerCaracteristica($conexion, $producto, $fila['id_formulario']);
	         if(pg_fetch_row($res)>0){
	             echo '<fieldset>
                            <legend>Caracteristícas adicionales</legend>';
	             
	             $res=$ca->obtenerCaracteristica($conexion, $producto, $fila['id_formulario']);
	             $con=0;
	             while ($fila=pg_fetch_assoc($res)){
	                 $con+=1;
	                 echo '<div data-linea="'.$con.'">'; 
	                 echo '<label>'.$fila['etiqueta'].':</label>';
	                 
	                 if ($fila['tipo']=="CB"){
	                     echo'<select name="elCaracteristica[]" required>';	                     
	                     echo '<option value="">Seleccione....</option>';
	                     $res2=$cac->listarItems($conexion, $fila['id_catalogo_negocios'],1);
	                     while ($filas = pg_fetch_assoc($res2)){
	                         echo '<option value="' . $filas['id_item'] . '">' . $filas['nombre'].'</option>';
	                     }
                         echo '</select>';
	                 }  else  if ($fila['tipo']=="RB"){
	                     
	                     $res2=$cac->listarItems($conexion, $fila['id_catalogo_negocios'],1);
	                     echo "<br>";
	                     $cont=1;
	                     echo '<table style="width:100%"><tr>';
	                     while ($filas = pg_fetch_assoc($res2)){
	                         
	                         echo "<td>";
	                         echo '<input type="radio" name="elCaracteristica[]" value="'.$filas['id_item'].'">';
	                         echo $filas['nombre'];
	                         
	                         echo "</td>";
	                         if(($cont%3)==0){
	                             echo "</tr><tr>";
	                         }
	                         
	                         $cont++;
	                     }
	                     echo "</tr></table>";
	                     
	                 }
	                 
	                 echo '<input type="hidden" value="'.$fila['id_elemento'].'" name="idElemento[]">';	                 
                     echo '</div>';
	             }
	             
	             
                 echo '</fieldset>

                ';
	         }
	    }
    break;
}

?>


<style>
.prueba{
width:50% !important;
}

</style>

<script type="text/javascript">

var originalDivision;

$(document).ready(function(){
	$("#cantidad").attr('maxlength','10');
	distribuirLineas();	
	$("#cantidad").numeric();
	$(".nCantidad").numeric();

	if($("#totalProveedores").val()=="0"){
		$("#estado").html("No tiene proveedores registrados para el producto seleccionado.").addClass('alerta');
		$("#proveedores").val("").attr("disabled","disabled");		
		$("#variedad").val("").attr("disabled","disabled");
		$("#cantidad").val("").attr("disabled","disabled");
		$("#unidad").val("").attr("disabled","disabled");
	}	

	if($("#tablaDetalle").length){
		$(".nCantidad").numeric();
		validarTotal(1);
	}

			
	if($("#CAreaProveedor").val()!="3"){
		if ($.trim($("#detalleItem #areaProveedor").val()) == "" ) {
			$("#variedad").attr('disabled',true);
	    }
        
	} else{
		$("#areaProveedor").val("");
		$("#nAreaProveedor").val("");
		$("#resultadoSitioProveedor").hide();
	}
	
});


function parseLocalNum(num) {
   return +(num.replace(",", "."));
}

function soloNumericos(){
	$(".nCantidad").numeric();		 
	if ((event.keyCode < 46) || (event.keyCode > 57) || (event.keyCode == 47))
		event.returnValue = false;	
}

function soloNumeros(){
	$(".nCantidad").numeric();
}

$('.nCantidad').keyup(function(e){	
    if($(this).val().indexOf('.')!=-1){         
        if($(this).val().split(".")[1].length > 2){      
            this.value = this.value.substring(0,this.value.length-1);                        
        }  
     } 
});


$("#proveedores").change(function(event){
	
	if($.trim($("#proveedores").val())){

       	$("#nproveedor").val($("#proveedores option:selected").text());
       	$("#identificacionProveedor").val($("#proveedores").val());


       	var str =$("#productos").val();			
		if(str!=""){			
			if($("#CAreaProveedor").val()!="3"){
				$("#nuevoProductoProveedor").attr('data-destino', 'resultadoSitioProveedor');
		        $("#nuevoProductoProveedor").attr('data-opcion', 'comboProveedor');
		        $("#opcion").val('sitioProveedor');
		        event.stopImmediatePropagation();
		       	abrir($("#nuevoProductoProveedor"), event, false);
			}
			
		}
       	 
	}	else{
		$("#identificacionProveedor").val("");
		$("#areaProveedor").attr("disabled",true).val("");
	}
	
});


$("#areaProveedor").change(function(event){
	if($.trim($("#areaProveedor").val())){
		var cadena= $("#areaProveedor option:selected").text();
		var area = cadena.substring(cadena.length -8, cadena.length);
		$("#nAreaProveedor").val(area);
	}	else{
		$("#nAreaProveedor").val("");
	}
});


$("#variedad").change(function(event){	
	if($.trim($("#variedad").val())){		
		$("#nvariedad").val($("#variedad option:selected").text());
	}	
});


$("#guardarDivision").click(function(event){
	event.preventDefault();
	$(".nuevaCantidad").removeClass("alertaCombo");
	$("#estado").html("");
	$(".alertaCombo").removeClass("alertaCombo");
	var error=false;

	var valor ;
	
	$('#tablaDetalle tbody tr').each(function (rows){
		var rd=$(this).find('td').eq(1).find('input').val();
		if(rd == ""){		
			$(this).find('td').eq(1).find('input').addClass('alertaCombo');
			error=true;	
			$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');			
    						
    	}		
		if(!error){
			if (isNaN (rd)) {				
		        error = true;        
		        $(this).find('td').eq(1).find('input').addClass('alertaCombo');
		        $("#estado").html("La cantidad a registrar debe ser un valor numérico sin caracteres.").addClass('alerta');
		    }
		}
	});

	if(!error){
		validarTotal(2);
	}
	
});

function delFilaActual(r){	
    var i = r.parentNode.parentNode.rowIndex;		    	    
    var table = document.getElementById('tablaDetalle');
    
		var rowCount = table.rows.length;
		var serie = parseInt($("#serie").val());
		var numeroDivisiones=$("#division").val();		
		var cont=0;
		var formato="";		
		if(rowCount > 3 ){			
			table.deleteRow(i);
			$('#tablaDetalle tbody tr').each(function (rows){
				if(cont>=1){
					formato=pad(serie, 11);	
					$(this).find('td').eq(0).html('<input type="hidden" name="nuevoCodigo[]" style="width:60%;" value='+formato+'>'+formato);
				serie+=1;
				}
				cont+=1;
				$("#division").val(cont);
			});		
			
		} else{
			$("#estado").html("La cantidad del registro se debe dividir en al menos 2 registros nuevos").addClass("alerta");
		}		
}

function pad (str, max) {
	  str = str.toString();
	  return str.length < max ? pad("0" + str, max) : str;
}

function validarTotal(op){
	$(".alertaCombo").removeClass("alertaCombo");
	$("#estado").html("").removeClass("alerta").removeClass("exito");
	var rd=0;
	var cont=0;
	var valorOriginal=0;
	$('#tablaDetalle tbody tr').each(function (rows){
		rd=rd+parseFloat($(this).find('td').eq(1).find('input').val());
		if(cont==0){
			valorOriginal=rd.toFixed(2);
		}
		cont+=1;
	});
	var round=rd.toFixed(2);
	var n=parseFloat($("#cantidad").val());
	var nuevaCantidad;
	if(round < n){	
		if(op==1){
			var total= parseFloat(valorOriginal) + parseFloat(n)- parseFloat(round);
			$('#tablaDetalle tbody tr:eq(0) td:eq(1)').html('<input type="text" id="nuevaCantidad" name="nuevaCantidad[]" class="nCantidad" onkeydown="soloNumeros()" value="'+total.toFixed(2)+'" style="width:40%;">');
		} else{
			var total= parseFloat(n)- parseFloat(round);
			$("#estado").html("El total de las nuevos registros ("+round+") no coincide con la cantidad del registro original("+n+") con un faltante de "+total.toFixed(2)).addClass("alerta");
		}
		
	} else if(round > n){
		if(op==1){
			var total= parseFloat(valorOriginal) - (parseFloat(round)-parseFloat(n));
			$('#tablaDetalle tbody tr:eq(0) td:eq(1)').html('<input type="text" id="nuevaCantidad" name="nuevaCantidad[]" class="nCantidad" onkeypress="soloNumeros()" onkeydown="soloNumericos()" value="'+total.toFixed(2)+'" style="width:40%;">');			
		} else{
			var total= parseFloat(round)-parseFloat(n);
			$("#estado").html("El total de los nuevos registros ("+round+") no coincide con la cantidad del registro original("+n+") con un excedente de "+total.toFixed(2)).addClass("alerta");
		}
	} else if(round == n){
		if(op==2){
			$("#estado").html("A guardar").addClass("exito");
			$("#opcion").val("dividir");	        
			
			$("#fechaIngresos").removeAttr("disabled","disabled");
			$("#productos").removeAttr("disabled","disabled");
			$("#proveedores").removeAttr("disabled","disabled");
			$("#variedad").removeAttr("disabled","disabled");
			$("#unidad").removeAttr("disabled","disabled");
			$("#nUnidad").removeAttr("disabled","disabled");
			
			
	    	$("#modificarProductoProveedor").attr('data-destino', 'detalleItem');    	
	        $("#modificarProductoProveedor").attr('data-opcion', 'dividirRegistro');
	        ejecutarJson($("#modificarProductoProveedor"));
		}
	}

}

</script>
		
