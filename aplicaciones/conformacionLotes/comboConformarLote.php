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
$ca = new controladorAdministrarCaracteristicas();

$producto = htmlspecialchars ($_POST['cbProducto'],ENT_NOQUOTES,'UTF-8');
$productoID = htmlspecialchars ($_POST['cbProducto'],ENT_NOQUOTES,'UTF-8');
$idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
$codigoProducto= htmlspecialchars ($_POST['codigoProducto'],ENT_NOQUOTES,'UTF-8');
$proveedor = htmlspecialchars ($_POST['cbProveedor'],ENT_NOQUOTES,'UTF-8');
$usuario =$_POST['idUsuario'];
$operador = $_POST['idUsuario'];
$usuario2 =$_POST['idUsuario2'];
$opcion = $_POST['opcion'];
$codigoLote=$_POST['codigoLote'];
$nProducto=$_POST['nProducto'];
$tipoOperacion= $_POST['tipoOperacion'];
$nAreaOperador = $_POST['nAreaOperador'];


$areaProveedor=$_POST['nSitioProveedor'];


switch ($opcion){
	case 'proveedor':
		
		$productoFila = $cl->listarProveedoresPorProducto($conexion, $producto,$usuario);
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
		
    		if($parametro['areas']==1){	
    			
    		    $operacionPermitidas = $cc -> buscarTipoOperacionporIdareaOperador($conexion, 'SV',$producto,$operador);
    			
    			echo '<div data-linea="2" id="resultadoTipoOperacion" >
    			<label for="tipoOperacion">Tipo Operación: </label>
    		 	<select id="tipoOperacion" name="tipoOperacion">
    		 	<option value="">Seleccione....</option>';
    			while($fila=pg_fetch_assoc($operacionPermitidas)){
    			    echo '<option value="' . $fila['id_tipo_operacion'] .'">' . $fila['nombre'] . '</option>';
    			}
    			
    			echo '</select>
    			      <input type="hidden" id="nSitio" name="nSitio">
                      <input type="hidden" id="nAreaOperador" name="nAreaOperador">
                      </div>';
    			
    			echo '<div data-linea="3" id="resultadoSitio" >
                      </div>';
    		}
    		
    		echo'<input type="hidden" value="'.$parametro['areas'].'" id="CAreas">';
    		echo'<input type="hidden" value="'.$parametro['proveedores'].'" id="CProveedor">';
    		echo'<input type="hidden" value="'.$parametro['areas_proveedor'].'" id="CAreaProveedor">';
    	
    		echo '<div data-linea="5" id="resultadoProveedor" >
        		  <label for="cbProveedor" id="lbCbProveedor">Nombre del Proveedor: </label>
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
		
		}
		
	break;
	
	case 'areaOperador':
	    $parametro=pg_fetch_assoc($cl->obtenerParametroxIDProducto($conexion, $producto));
	    if($parametro['areas']==1){
	        $producto = $cl->sitiosXidTipoOperacion($conexion, $codigoProducto,$usuario,$tipoOperacion);
	        
	        $nombreArea= pg_fetch_assoc($cc->buscarNombreAreaporTipoOperacion($conexion, $tipoOperacion));
	        
    	     echo '    	     
    	     <label for="sitio" id="lbSitioOperador">'.$nombreArea['nombre'].': </label>
    	     <select id="sitio" name="sitio">
    	     <option value="">Seleccione....</option>';
    	     while($fila=pg_fetch_assoc($producto)){
    	       echo '<option value="' . $fila['codigo'] .'">' . $fila['nombre_area'] . '</option>';
    	     }
	     }
    break;
	
	case 'sitio':
	    
	    $productoFila = $cl->listarProveedoresPorProducto($conexion, $producto,$usuario);
	    $parametro=pg_fetch_assoc($cl->obtenerParametroxIDProducto($conexion, $producto));
	    
	    if($parametro['areas']==1){
	        $producto = $cl->sitiosXidProductoAcopiador($conexion, $codigoProducto,$usuario);	       	        
	        
	        
	        $operacionPermitidas = $cc -> buscarTipoOperacionporIdareaOperador($conexion, 'SV',$producto,$usuario);
	        
	        echo '<div data-linea="2" id="resultadoTipoOperacion" >
			<label for="tipoOperacion">Tipo operación: </label>
		 	<select id="tipoOperacion" name="tipoOperacion">
		 	<option value="">Seleccione....</option>';
	        while($fila=pg_fetch_assoc($operacionPermitidas)){
	            echo '<option value="' . $fila['id_tipo_operacion'] .'">' . $fila['nombre'] . '</option>';
	        }
	        
	        
	        
	        echo '</select>
			      <input type="hidden" id="nSitio" name="nSitio">
                  </div>';
	    }
	    
	    echo'<input type="hidden" value="'.$parametro['areas'].'" id="CAreas">';
	    echo'<input type="hidden" value="'.$parametro['proveedores'].'" id="CProveedor">';
	    echo'<input type="hidden" value="'.$parametro['areas_proveedor'].'" id="CAreaProveedor">';
	    
	    echo '<div data-linea="5" id="resultadoProveedor" >
    		  <label for="cbProveedor" id="lbCbProveedor">Nombre del Proveedor: </label>
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
	    
	    $conexion->ejecutarConsulta("begin;");
	    
	    $ca->estructurarTabla($conexion, 'v_caracteristica', 'g_trazabilidad.registro', 'id_registro');
	    
	    //$productosResultado=pg_fetch_assoc($ca->obtenerProductosXCaracteristica($conexion, 'v_caracteristica', 'tipo'));
	    
	    $res=$ca->obtenerFormulario($conexion, "nuevoProductoProveedor");
	    $formulario=pg_fetch_assoc($res);
	    
	    if($formulario>0){
	    //echo "entro";
	    // $PivoColumna = $cl->pivotearColumnas($conexion, $producto, $formulario['id_formulario']);
	   
	        $registrosTotal=pg_num_rows($ca->obtenerRegistrosTabla($conexion, 'v_caracteristica',$productoID));
	        
            if($registrosTotal>0){
                $ca->pivotearColumnas($conexion, 'tmp_c','v_caracteristica', $productoID, $formulario['id_formulario'], "'id_registro'", "'etiqueta'", 'nombre');
                $registro = $cl->listarRegistrosProveedorAreaMasCaracteristicas($conexion,$usuario, $proveedor,$productoID, $areaProveedor);
                
            } else{                
                $registro = $cl->listarRegistrosProveedorArea($conexion,$usuario, $proveedor,$productoID, $areaProveedor);
            }	    
	    }
	    
		echo '<label>Seleccione uno o varios Productos</label>		
			 <div class="seleccionTemporal">
				<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" />
		    	<label for="cTemporal">Seleccionar todos </label>
			 </div>
		     <hr>
			 <div id="contenedorProducto">
                <table style="width:100%" id="tablaLotes">				
				<thead>
				<tr style="text-align:center">
					<th>Seleccionar</th>
					<th>ID</th>
					<th>Código Ingreso</th>
					<th>Fecha Ingreso</th>
					<th>Proveedor</th>									
					<th>Cant</th> ';         		
            		
            		/*$campos = $cl->obtenerCamposCaracteristicas($conexion, $producto,$formulario['id_formulario']);		
            		while ($nCampos = pg_fetch_assoc($campos)){
            		    echo '<th>'.$nCampos['etiqueta'].'</th>';
            		}*/
		
        		$ca->estructurarTabla($conexion, 'v_caracteristicaTotal', 'g_trazabilidad.registro', 'id_registro');
        		$registrosTotal=pg_num_rows($ca->obtenerRegistrosTabla($conexion, 'v_caracteristicaTotal', $productoID));
		
        		if($registrosTotal>0){
        		    $campos = $cl->obtenerCamposCaracteristicas($conexion, $productoID,$formulario['id_formulario']);
        		    while ($nCampos = pg_fetch_assoc($campos)){
        		        echo '<th>'.$nCampos['etiqueta'].'</th>';
        		    }
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
				$("#agregarRegistro").attr("disabled","disabled");</script>';
			
			 if($proveedor=="")
			 echo '<script type="text/javascript">$("#estado").html("")</script>';
			 
		} else{
			echo'<script type="text/javascript">$("#estado").html("");</script>';
		}
				
		echo '</div>';
		
		$conexion->ejecutarConsulta("commit;");
		
	break;
	
	case 'borrarRegistros':
		
		echo "";
	break;
	
	case 'variedad':
		/*$variedad = $cl->listarVariedadesProductos($conexion,$producto);
		echo"			
			<label for=variedad>Variedad: </label>
			<select id=variedad name=variedad>
			<option value=>Seleccione....</option>";
		
		while($fila=pg_fetch_assoc($variedad)){
			echo '<option value="' . $fila['id_variedad'] .'">' . $fila['nombre'] . '</option>';
		}
		
		echo "</select>
			  <input type=hidden id=nvariedad name=nvariedad>";*/
	break;
	
	case 'cantidad':
		echo "
				  <label for=cantidad>Cant. a Registrar: </label>	
				  <input type=text id=cantidad name=cantidad class=prueba><label>Kg.</label>
		      ";
	break;

	
	case 'lote':
		
		echo "
	<fieldset>
		<legend>Lote</legend>
		<div data-linea=1>
			<label for=loteNro>Lote Número</label> ";			
			$año = date("Y");
			$nrlote= $cl->autogenerarNumeroLote($conexion,$usuario,$año,$idProducto);
			//$codigoFila = $codigo;				
			$maxAño = $cl->obtenerMaxAño($conexion,$usuario);
			$maxAñoFila = pg_fetch_assoc($maxAño);			
			if((int)$maxAñoFila['anio']<$año){
				$formato=str_pad(1, 5, "0", STR_PAD_LEFT);
			} else{
				$formato=str_pad((int)$nrlote, 5, "0", STR_PAD_LEFT);
			}
			echo "<input type=text id=loteNro name=loteNro value='$año-$formato' disabled=disabled readOnly>				
			<input type=hidden id=serieLote name=serieLote value='$nrlote'>
	
		</div>
		<div data-linea=1>
			<label for=fechaConformacion2>Fecha Conformación:</label>
			<input type=text id=fechaConformacion2 name=fechaConformacion2 disabled=disabled readOnly>
		</div>
		<div data-linea=2>
			<label for=codigoLote>Código Lote:</label>
			<input type=text id=codigoLote name=codigoLote maxlength=30 autofocus>
		</div>
		<div data-linea=2>
			<label for=cantidadLote>Cantidad Lote:</label>
			<input type=text id=cantidadLote name=cantidadLote disabled=disabled readOnly/>
		</div>";
			
		$string=$filaRegistro['producto'];		
				
		echo "<div data-linea=3>
			    <label for=paisDestino>País Destino:</label>
				<select id=paisDestino name=paisDestino>
					<option value=>Seleccione....</option>";
		$pais = $cc->listarLocalizacion($conexion, 'PAIS');
		while($fila=pg_fetch_assoc($pais)){
		    echo '<option value="' . $fila['id_localizacion'] .'">' . $fila['nombre'] . '</option>';
		}
		echo "</select>
			<input type=hidden id=idPaisDestino name=idPaisDestino>
			</div>
			<div data-linea=4>
				<label for=tipoConvencional>Convencional:</label>
				<input type=radio id=tipoConvencional name=tipoProducto value=convencional checked>
			</div>
			<div data-linea=4>
			    <label for=tipoOrganico>Orgánico:</label>
				<input type=radio id=tipoOrganico name=tipoProducto value=organico>
			</div>
			<div data-linea=5>
				<label for=descripcionLote style='vertical-align:top;'>Descripción Lote: </label>
				<textarea rows=4 cols=50 id=descripcionLote name=descripcionLote style=font-family:arial></textarea>
			</div>";
				
		echo "<div id=mensajeError style='width:100%; margin-top:10px; text-align: center;'></div>
		</fieldset>
    
		<!--input type=text id=idUsuario name=idUsuario value=$usuario -->		
		
		<input type=hidden id=codigoAreaOperador name=codigoAreaOperador>
		<input type=hidden id=codigoAreaProveedor name=codigoAreaProveedor>
			";
		
	break;
		
	case 'sitio':
		$producto = $cl->sitiosXidProductoAcopiador($conexion, $codigoProducto,$usuario);
		echo "<label for=sitio>Area de acopio: </label>
		 	<select id=sitio name=sitio>
		 	<option value=>Seleccione....</option>";
		while($fila=pg_fetch_assoc($producto)){
			echo '<option value="' . $fila['codigo'] .'">' . $fila['nombre_area'] . '</option>';
		}
		
		echo "</select>
			<input type=hidden id=nSitio name=nSitio>";
	break;
	
	case 'sitioProveedor':
	    $parametro=pg_fetch_assoc($cl->obtenerParametroxIDProducto($conexion, $producto));
	    if($parametro['areas_proveedor']==2 || $parametro['areas_proveedor']==1){	
	        $producto = $cl->sitiosXidProductoAcopiador($conexion, $codigoProducto,$proveedor,$parametro['areas_proveedor'],$operador);
	    } else{
	        $producto = $cl->sitiosXidProductoAcopiador($conexion, $codigoProducto,$proveedor);
	    }
	    
		echo "<label for=sitioProveedor>Área Proveedor: </label>
		 	<select id=sitioProveedor name=sitioProveedor>
		 	<option value=>Seleccione....</option>";
		while($fila=pg_fetch_assoc($producto)){
			echo '<option value="' .$fila['nombre_area'].'@'.$fila['id_area'] .'">' . $fila['nombre_area'] . '</option>';
		}
		
		echo "</select>
			<input type=hidden id=nSitioProveedor name=nSitioProveedor>";
	break;
	
	case 'caracteristica':
	    $res=$ca->obtenerFormulario($conexion, "nuevoLote");	    
	    $fila=pg_fetch_assoc($res);
	    if($fila>0){
	        
	        $res=$ca->obtenerCaracteristica($conexion, $idProducto, $fila['id_formulario']);	        
	        if(pg_fetch_row($res)>0){
	            echo '<fieldset>
                            <legend>Características adicionales</legend>';
	            
	            $resultado=$ca->obtenerCaracteristica($conexion, $idProducto, $fila['id_formulario']);
	            $con=0;
	            while ($fila=pg_fetch_assoc($resultado)){
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
	                } else  if ($fila['tipo']=="RB"){
	                    
	                    $res2=$cac->listarItems($conexion, $fila['id_catalogo_negocios'],1);
	                    echo "<br>";
	                    $cont=1;
	                    echo '<table style="width:100%"><tr>';
	                    while ($filas = pg_fetch_assoc($res2)){	  
	                        
	                        echo "<td>";
	                        echo '<input type="radio" name="elCaracteristica[]" value="'.$filas['id_item'].'">';
	                        echo $filas['nombre'].$cont;	                        
	                        
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
	        } else {
	            echo pg_fetch_row($res);	             
	        }
	    }
	    
    break;
	
	default:
	echo"";
}

?>


<style>
.prueba{
width:50% !important;
}

</style>

<script type="text/javascript">

var suma=0;
$(document).ready(function(){
	distribuirLineas();	
	$("#cantidad").numeric();	
	$("#cantidad").attr('maxlength','7');	
	var fecha = new Date();
	var dd=("00" + fecha.getDate()).slice (-2); 
	var mm=("00" + (fecha.getMonth()+1)).slice (-2); 
	var yy=fecha.getFullYear();	
	$("#fechaConformacion2").val("hola");
	
	sumarTabla(0);
	obtenerFecha();
		
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

	$("#sitio").change(function(event){

		if($("#sitio").val() != ""){
			event.preventDefault();		
			var val=$("#sitio").val();
			var area= val.split(".");
			$("#nSitio").val(area[1]);
		} else{
			/*$("#cbProveedor").attr("disabled",true).val("");
			$("#sitioProveedor").attr("disabled",true).val("");*/
		}
		
	});


	$("#tipoOperacion").change(function(event){		
		event.preventDefault();
		$("#nAreaOperador").val($("#tipoOperacion option:selected").text());				
		$("#nuevoLote").attr('data-destino','resultadoSitio');
 		$("#nuevoLote").attr('data-opcion', 'comboConformarLote');
 		$("#opcion").val('areaOperador');
 		abrir($("#nuevoLote"),event,false); 		
		distribuirLineas();	
		event.stopImmediatePropagation();
	});
	
		
	$("#cbProveedor").change(function(event){
		event.preventDefault();

		var str =$("#cbProveedor").val();
		var producto;
		var index;		

		$("#bodyTablaLotes").html("");
		
		if ($.trim($("#cbProveedor").val()) != "" ) {

			if(str!=""){
				producto = str.toLowerCase();
				//index = producto.indexOf("pitahaya");
				if($("#CAreaProveedor").val()!=3){
					$("#cbProducto").attr("disabled",false);					
					$("#nuevoLote").attr('data-destino', 'resultadoSitioProveedor');
			        $("#nuevoLote").attr('data-opcion', 'comboConformarLote');
			        $("#opcion").val('sitioProveedor');
			        $("#agregarRegistro").attr("disabled","disabled");
			       	abrir($("#nuevoLote"), event, false);
			       	
				} else{

					if ($.trim($("#cbProveedor").val()) == "" ) {
						$("#agregarRegistro").attr("disabled","disabled");	        
				    } else{
				    	$("#agregarRegistro").removeAttr("disabled");
				    }
				    
					event.stopImmediatePropagation();
					
					var table = document.getElementById('tablaLotesConformar');	    
			 		var rowCount = table.rows.length; 		
			 		if(rowCount > 1){
				 		$("#cbProducto").removeAttr("disabled","disabled");
			 		}
							
			 		$("#nuevoLote").attr('data-destino','dRegistro');
			 		$("#nuevoLote").attr('data-opcion', 'comboConformarLote');
			 		$("#opcion").val('registros');
			 		abrir($("#nuevoLote"),event,false); 

			 		if(rowCount > 1){
				 		$("#cbProducto").attr("disabled","disabled");	 			
			 		}
					
				}
			}
		
				
		       	
		} else{
			$("#sitioProveedor").attr("disabled",true).val("");
			$("#agregarRegistro").attr("disabled",true).val("");
		}
		
		event.stopImmediatePropagation();
		
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
	 		var filas = $("#bodyTablaLotesConformar tr").length;	 	
	 		
	 		if(filas>0){
    			if($("#CProveedor").val()=="1" || $("#CProveedor").val()=="2"){
    	 			$("#cbProveedor").attr("disabled",false);
    	 			$("#cbProducto").attr("disabled",false);
    	 			//$("#sitio").attr("disabled",false);
    			}
    	
	 		}
					
	 		$("#nuevoLote").attr('data-destino','dRegistro');
	 		$("#nuevoLote").attr('data-opcion', 'comboConformarLote');
	 		$("#opcion").val('registros');
	 		abrir($("#nuevoLote"),event,false);

	 		if(filas >0){
    	 		if($("#CProveedor").val()=="1"){
    	 			$("#cbProveedor").attr("disabled",true);
    	 			$("#cbProducto").attr("disabled",true);
    	 			//$("#sitio").attr("disabled",true);
        	 				
    			} 

    	 		if($("#CProveedor").val()=="2"){
    	 			$("#cbProducto").attr("disabled",true);
    			} 
	 		}
	 		
	    }
		

	});
	
	

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
});

$("#paisDestino").change(function(event){
	$("#idPaisDestino").val($("#paisDestino option:selected").text());
});

$("#loteTipo").change(function(event){
	$("#nLoteTipo").val($("#loteTipo option:selected").text());
});




if($("#totalProveedores").val()=="0"){
	$("#estado").html("No tiene proveedores registrados para el producto seleccionado.").addClass('alerta');
	$("#cbProveedor").val("").attr("disabled","disabled");
	$("#agregarRegistro").attr("disabled","disabled");	
}

function sumarTabla(val){
	suma=0;	
    $('#tablaLotesConformar tbody tr').each(function (rows) {    
    var cantidad = $(this).find("td").eq(3).html();    
    var table = document.getElementById('tablaLotesConformar');    
	var rowCount = table.rows.length;
    if (rowCount>val){
    	suma+= parseFloat(cantidad);			        
    }
    });
    
    var conDecimal = suma.toFixed(2);   
    $("#cantidadLote").val(conDecimal);	   
}

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



</script>
		
