<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';

$conexion = new Conexion();
$cl = new ControladorLotes();
$idLote= $_POST['id'];
$res = $cl->ObtenerLote($conexion,$_POST['id']);
$filaRegistro = pg_fetch_assoc($res);
$cc = new ControladorCatalogos();
$ca = new ControladorAdministrarCaracteristicas();
$usuario=$_SESSION['usuario'];

?>

<header>	
	<?php 
	if($filaRegistro['estado']=='1'){
		echo "<h1>Editar Lote</h1>";
		
	} else{
		echo "<h1>Ver Lote</h1>";
	}
?>	
</header>

<div id="estado"></div>

<form id="abrirLote" data-rutaAplicacion="conformacionLotes" >
	<input type="hidden" id="opcion" name="opcion" />
	<input type="hidden" id="usuario" name="usuario" value='<?php echo $usuario; ?>'/>
	<input type="hidden" id="productoNombre" name="productoNombre" value='<?php echo $filaRegistro['producto']; ?>'/>
	<input type="hidden" id="idAreaProveedor" name="idAreaProveedor" value='<?php echo $filaRegistro['id_area_proveedor']; ?>'>
	<p>
	<?php 
	if($filaRegistro['estado']=='1'){
		echo "<button id=modificar type=button class=editar>Modificar</button>";
		
		$parametro=pg_fetch_assoc($cl->obtenerParametroxIDProducto($conexion, $filaRegistro['id_producto']));
		
		if($parametro>0){
		    echo '<div id="productoFlujo">';
    		echo'<input type="hidden" value="'.$parametro['areas'].'" id="CAreas">';
    		echo'<input type="hidden" value="'.$parametro['proveedores'].'" id="CProveedor">';
    		echo'<input type="hidden" value="'.$parametro['areas_proveedor'].'" id="CAreaProveedor">';
    		echo '</div>';
		}
	}
	?>				
	</p>
	<fieldset>
	<legend>Datos de ingreso:</legend>
	<div data-linea="1" >
		<label for="cbProducto" style="width:20%">Nombre del producto: </label>
		<select id="cbProducto" name="cbProducto" style="width:70%" disabled >
				<option value="">Seleccione....</option>
				<?php 
					$productos = $cl->listarProductosTrazabilidad($conexion,$usuario);
					while ($produFila = pg_fetch_assoc($productos)){
						echo '<option value="' . $produFila['id_producto'] . '">' . $produFila['nombre_comun'] . '</option>';
					}					
				?>					
		</select>		
		<input type="hidden" id="idProducto" name="idProducto" value=<?php echo $filaRegistro['id_producto']?> >
	</div>	
	<?php 
	$string=$filaRegistro['producto'];
	
	
			
	
	if ($parametro['proveedores']=="1" ){
	    
	    $proveedor = $cl->obtenerProveedorLote($conexion, $idLote);
	    
	    echo'
		<div data-linea="2" id="resultadoProveedor">
		<label for="cbProveedor" style="width:20%">Nombre del proveedor: </label>
		<select id="cbProveedor" name="cbProveedor" style="width:70%" disabled="disabled" >			
		';
	    
	    $registros = $cl->listarProveedoresPorProducto($conexion,$filaRegistro['id_producto'],$usuario);
	    while ($fila = pg_fetch_assoc($proveedor)){
	        echo '<option value="' . $fila['identificador_proveedor'] . '">' . $fila['nombre_proveedor'] . '</option>';
	    }
	    
	    echo'</select>
		</div>
		';
	    
	   
	   // $proveedorLote = pg_fetch_assoc( $cl->obtenerProveedorLote($conexion, $idLote));
	   
	    if($parametro['areas_proveedor']!="3"){
	        
	        $producto = $cl->sitiosXidProductoAcopiador($conexion, $filaRegistro['id_producto'],$filaRegistro['identificador_proveedor'],"ACO");
	    
	    echo "<div data-linea=7 id=resultadoSitioProveedor>
        <label for=sitioProveedor>Área Proveedorsss:</label>
		 	<select id=sitioProveedor name=sitioProveedor disabled>
		 	<option value=''>Seleccione....</option>";
	    while($fila=pg_fetch_assoc($producto)){
	        echo '<option value="' .$fila['nombre_area'].'@'.$fila['id_area'] .'">' . $fila['nombre_area'] . '</option>';
	    }
	    
	    echo "</select>
			<input type=hidden id=nSitioProveedor name=nSitioProveedor>
            </div>";
	    
	    }
	    
	    
	} else{
	    
	    echo'
		<div data-linea="2" id="resultadoProveedor">
		<label for="cbProveedor" style="width:20%">Nombre del proveedor: </label>
		<select id="cbProveedor" name="cbProveedor" style="width:70%" disabled="disabled" >
				<option value="">Seleccione....</option>
		';
	    
	    $registros = $cl->listarProveedoresPorProducto($conexion,$filaRegistro['id_producto'],$usuario);
	    while ($fila = pg_fetch_assoc($registros)){
	        echo '<option value="' . $fila['identificador_proveedor'] . '">' . $fila['nombre_proveedor'] . '</option>';
	    }
	    
	    echo'</select>
		</div>
		';
	    
	    echo '<div data-linea="7" id="resultadoSitioProveedor" > </div>';
	}
	
	?>
	
	</fieldset>
	<fieldset>
		
		<legend>Selección de ingresos:</legend>		
		<div data-linea="1">
			<div id="dRegistro">
			<?php 
			$string=$filaRegistro['producto'];
			$conexion->ejecutarConsulta("begin;");
			$res=$ca->obtenerFormulario($conexion, "nuevoProductoProveedor");
			$formulario=pg_fetch_assoc($res);
			
			//$PivoColumna = $cl->pivotearColumnas($conexion, $filaRegistro['id_producto'], $formulario['id_formulario']);
			
			//$productos
			
			//$registro = $cl->listarRegistrosProveedorArea($conexion,$usuario, $proveedor,$producto, $areaProveedor);
					
			if ($parametro['proveedores']=="1" ){
			    
			    if($parametro['areas_proveedor']=="1"){
			    
			    //$proveedores = pg_fetch_assoc($cl->listarProveedoresPorProducto($conexion,$filaRegistro['id_producto'],$usuario));
			    
			    /*$registro = $cl->listarRegistrosProveedorArea($conexion,$usuario, $filaRegistro['identificador_proveedor'],$filaRegistro['id_producto'], $areaProveedor['area_proveedor']);
				
				echo '<label>Seleccione uno o varios Productos</label>
		
					<div class="seleccionTemporal">
						<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" disabled/>
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
        					<th>Cant</th>';
        				
        				$ca->estructurarTabla($conexion, 'v_caracteristica', 'g_trazabilidad.registro', 'id_registro');
        				$registrosTotal=pg_fetch_assoc($ca->obtenerRegistrosTabla($conexion, 'v_caracteristica', $filaRegistro['id_producto']));
        				
        				if($registrosTotal>0){
            				$campos = $cl->obtenerCamposCaracteristicas($conexion, $filaRegistro['id_producto'],$formulario['id_formulario']);
            				while ($nCampos = pg_fetch_assoc($campos)){
            				    echo '<th>'.$nCampos['etiqueta'].'</th>';
            				}
        				}
    				
    				echo '</tr>
    				</thead>
				<tbody id="bodyTablaLotes">
		
				';
				$agregarDiv = 0;
				$cantidadLinea = 0;
				while ($fila = pg_fetch_row($registro)){
					
					echo '<tr><td><input id="'.$fila[0].'" type="checkbox" name="'.$fila[0].'" class="productoActivar" data-resetear="no" value="'.$fila[0].'" disabled/>
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
				
			$conexion->ejecutarConsulta("commit;");*/
					
					
			}
			}
			?>
			</div>			
		</div>		
		
	</fieldset>	
	<?php 
		if($filaRegistro['estado']=='1'){
			echo "<button class=mas disabled=disabled id=agregarRegistro onclick='addFilasprueba(contenedorProducto);return false;'>Agregar</button>";
		}
	?>
	</form>
	
	<fieldset>
		
		<legend>Registros que conforman el Lote:</legend>
		<div data-linea="1">
			<div id="dRegistroConformados">
				<?php
				
				$string=$filaRegistro['producto'];
				$conexion->ejecutarConsulta("begin;");
				
				$ca->estructurarTabla($conexion, 'v_caracteristica', 'g_trazabilidad.registro', 'id_registro');
				
				$formulario=pg_fetch_assoc($res=$ca->obtenerFormulario($conexion, "nuevoProductoProveedor"));
			
				if($formulario>0){
				   
				    $registrosTotal=pg_fetch_assoc($ca->obtenerRegistrosTabla($conexion, 'v_caracteristica', $filaRegistro['id_producto']));
				    
				    if($registrosTotal>0){
				        $ca->pivotearColumnas($conexion, 'tmp_c','v_caracteristica', $filaRegistro['id_producto'], $formulario['id_formulario'], "'id_registro'", "'etiqueta'", 'nombre');
				    }
				}
				
				echo '
				<table style="width:100%" id="tablaLotesConformar" >
				<thead>
				<tr>
					<th>ID</th>
					<th>Código Ingreso</th>
					<th>Proveedor</th>
					<th>Cantidad</th>';
				
				if($registrosTotal>0){
				   
    				$campos = $cl->obtenerCamposCaracteristicas($conexion, $filaRegistro['id_producto'],$formulario['id_formulario']);
    				
    				while ($nCampos = pg_fetch_assoc($campos)){
    				    echo '<th>'.$nCampos['etiqueta'].'</th>';
    				}
				}
				echo'<th>Eliminar</th></tr>
				</thead>
				<tbody>';
				if($registrosTotal>0){
				    
				    $registro = $cl->ObtenerRegistrosConformadosMasCaracteristicas($conexion,$idLote,$usuario);				    
				} else{
				    
				    $registro = $cl->ObtenerRegistrosConformados($conexion, $idLote, $usuario);
				}
				 
				while ($fila = pg_fetch_row($registro)){
				
					echo '<tr style="text-align:center">'.
						 	"<td style=text-align:center>".$fila[0]."</td>".
						 	"<td style=text-align:center>".$fila[1].
						 	"<input type='hidden' name='dProveedor[]' value='$fila[4]'>".
						 	"<input type='hidden' name='dArea[]' value='$fila[5]'></td>".						 	
						 	"<td style=text-align:center>".$fila[2]."</td>".
						 	"<td style=text-align:center>".$fila[3]."</td>";
					
					$con=7;
					if(count($fila)>7){
					    while($con<count($fila)){
					        if ($fila[$con]!=''){
					            echo "<td style=text-align:center>".$fila[$con]."</td>";
					        } else{
					            echo "<td style=text-align:center>S/N</td>";
					        }
					        $con+=1;
					    }
					}
					
					echo '<td class="borrar">
							<button type=submit class="icono" onclick="delFilaActual(this);return false;" disabled="disabled"></button>
							</td></tr>';
				}
					
				echo '</tbody>
				</table>';
				
				$conexion->ejecutarConsulta("commit;");
				?>				
				
			
				<div style='text-align:center' id="mensajeErrorConformarLote"></div>
			</div>			
		</div>		
		
	</fieldset>	
	
	<form id="loteConformado" data-rutaAplicacion="conformacionLotes" data-accionEnExito="ACTUALIZAR">
		<fieldset>
			<legend>Lote</legend>
			<div data-linea="1">
				<input type ="hidden" id="idUsuario" name="idUsuario" value=<?php echo $_SESSION['usuario']?>>
				<div id="cargarRegistros"></div>
				<label for="loteNro">Lote Número</label> 
				<input type="text" id="loteNro" name="loteNro" value=<?php echo $filaRegistro['numero_lote']?> disabled="disabled">				
				<input type="hidden" id="serieLote" name="serieLote" value=<?php echo $filaRegistro['serie_lote']?>>
		
			</div>
			<div data-linea="1">
				<label for="fechaConformacion2">Fecha conformación:</label>
				<input type="text" id="fechaConformacion2" name="fechaConformacion2" value=<?php echo $filaRegistro['fecha_conformacion']?> disabled="disabled">
			</div>
			<div data-linea="2">
				<label for="codigoLotes">Código Lote:</label>
				<input type="text" id="codigoLotes" name="codigoLotes" value="<?php echo $filaRegistro['codigo_lote']?>" disabled="disabled">
				<input type="hidden" id="codigoComprobacion" name="codigoComprobacion" value="<?php echo $filaRegistro['codigo_lote']?>" disabled="disabled">
			</div>
			<div data-linea="2">
				<label for="cantidadLote">Cantidad Lote:</label>
				<input type="text" id="cantidadLote" name="cantidadLote" value="<?php echo $filaRegistro['cantidad']?>" disabled="disabled" readOnly>
				<input type="hidden" id="cantidadLoteh" name="cantidadLoteh" value="<?php echo $filaRegistro['cantidad']?>">
			</div>
			
			<?php					
			
			$string=$filaRegistro['producto'];			
				
			echo '<div data-linea="3">
			<label for="paisDestino">País Destino:</label>
			<select id="paisDestino" name="paisDestino" disabled="disabled">
			<option value=>Seleccione....</option>';
			
			$pais = $cc->listarLocalizacion($conexion,'PAIS');
			while($fila=pg_fetch_assoc($pais)){
				echo '<option value="' . $fila['id_localizacion'] .'">' . $fila['nombre'] . '</option>';
			}
			
			echo '</select>
			<input type="hidden" id="idPaisDestino" name="idPaisDestino" value="'.$filaRegistro['pais'].'">
			</div>
			<div data-linea="4">
				<label for="tipoConvencional">Convencional:</label>';
			
			echo "<input type=radio id=tipoConvencional name=tipoProducto value=convencional disabled=disabled";
			if($filaRegistro['tipo_producto']=="convencional"){
				echo " checked >";			
			}  else{
				echo " >";
			}			
			
			echo '</div>

			<div data-linea="4">
			    <label for="tipoOrganico">Orgánico:</label>';
					
			echo "<input type=radio id=tipoOrganico name=tipoProducto value=organico disabled=disabled";
			if($filaRegistro['tipo_producto']=="organico"){
				echo " checked >" ;
			} else{
				echo " >";
			}
					
			echo '</div>';
			
			
			?>
			
			<div data-linea="5">
				<label for="descripcionLote" style="vertical-align:top;">Descripción Lote: </label>
				<textarea rows="4" cols="50" id="descripcionLote" name="descripcionLote" style="font-family:arial" disabled="disabled"><?php echo $filaRegistro['descripcion']?></textarea>
			</div>
		<div id="mensajeError" style="width:100%; margin-top:10px; text-align: center;"></div>
		</fieldset>		
		<input type="hidden" id="idLote" name="idLote" value="<?php echo $idLote;?>" >
		<input type="hidden" id="idProducto2" name="idProducto2" value="<?php echo $filaRegistro['id_producto']?>" >
		<input type ="hidden" id="nProducto" name="nProducto" value="<?php echo $filaRegistro['producto']?>">
		<?php 
		if($filaRegistro['estado']=='1'){
			echo "<button id=actualizar type=submit class=guardar disabled=disabled>Actualizar</button>";
		}
		?>	
		
	</form>
<script type="text/javascript">

	var variedad="";
	var con=0;
	var suma=0;
	var rDuplicado=0;
	var productoConformar="";

	$(document).ready(function(){
		distribuirLineas();
		$("#codigoLotes").attr('maxlength','30');		
		cargarValorDefecto("cbProducto","<?php echo $filaRegistro['id_producto'];?>");
		cargarValorDefecto("paisDestino","<?php echo $filaRegistro['id_localizacion'];?>");
		cargarValorDefecto("loteTipo","<?php echo $filaRegistro['id_tipo_lote'];?>");				
		$("#idProducto").val($("#cbProducto").val());
		productoConformar = <?php echo $filaRegistro['id_producto'];?>;
		
		$("#modificar").click(function(event){
			var str =$("#cbProducto option:selected").text();
			var producto;
			var index;		

			if($("#CProveedor").val()!="1"){
				$("#cbProveedor").attr("disabled",false);			
		  	}	

			$("#sitioProveedor").attr("disabled",false);
			
			$(this).attr("disabled","disabled");
			$("#actualizar").removeAttr("disabled");			
			$("#codigoLotes").removeAttr("disabled");
			$("#codigoComprobacion").removeAttr("disabled");			
			$("#loteTipo").removeAttr("disabled");
			$("#paisDestino").removeAttr("disabled");
			$("#tipoConvencional").removeAttr("disabled");
			$("#tipoOrganico").removeAttr("disabled");
			$("#descripcionLote").removeAttr("disabled");
			$("#dRegistroConformados button").removeAttr("disabled");
			
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
			 		$("#cbProducto").removeAttr("disabled","disabled");	 		
		 		}	 		

		 		var filas = $("#bodyTablaLotesConformar tr").length;

		 		if(filas>0){
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
			 		$("#cbProducto").attr("disabled","disabled");	 			
		 		}
		    }
		});
		
//////////////// SUBMIT
		$("#loteConformado").submit(function(event){
			event.preventDefault();		 
			
		    $(".alertaCombo").removeClass("alertaCombo");
		    var error = false;
		    if ($.trim($("#detalleItem #codigoLotes").val()) == "" ) {
		        error = true;
		        $("#detalleItem #codigoLotes").addClass("alertaCombo");		        
		    }

		    var str =$("#productoNombre").val();
			var producto;
			var index;  
		    
		    if ($.trim($("#detalleItem #paisDestino").val()) == "" ) {
		        error = true;
		        $("#detalleItem #paisDestino").addClass("alertaCombo");
		    }
		    var cantidad = parseFloat($("#detalleItem #cantidadLote").val());		    
		    if (cantidad < 1) {
		        error = true;
		        $("#detalleItem #mensajeError").html("Debe existir al menos un registro para conformar el Lote").addClass('alerta');	        
		    }
		    		    
		    if (!error){
			    var table = document.getElementById('tablaLotesConformar');
		     	var rowCount = table.rows.length;	
		        $("#cargarRegistros").html("");
		        
		        $("#tablaLotesConformar tbody tr").each(function(rows){			
			                
		    		if(rowCount>0){
			            var val=$(this).find("td").eq(0).html();
			            			        	        
				        $("#cargarRegistros").append("<input type='hidden' name='idRegistro[]' value='"+val+"'>");				        
		    		}
	    		});		        
	    		
	    		$("#loteConformado").attr('data-destino', 'abrirLote');    	
		        $("#loteConformado").attr('data-opcion', 'actualizarLote');
		        ejecutarJson($(this));
		    } else {
		        mostrarMensaje("Por favor revise los campos obligatorios.","FALLO");
		    }
		});
	////////////////////// Fin Submit
		
		$("#paisDestino").change(function(event){
			$("#idPaisDestino").val($("#paisDestino option:selected").text());
		});

		$("#loteTipo").change(function(event){
			$("#nLoteTipo").val($("#loteTipo option:selected").text());
		});	
	});	

	$("#cbProveedor").change(function(event){
		
		/*$("#agregarRegistro").removeAttr('disabled');
 		$("#abrirLote").attr('data-destino','dRegistro');
 		$("#abrirLote").attr('data-opcion', 'comboEditarLote');
 		$("#opcion").val('registros');
 		abrir($("#abrirLote"),event,false); 	*/

		$("#bodyTablaLotes").html("");
		
		if ($.trim($("#cbProveedor").val()) != "" ) {

				if($("#CAreaProveedor").val()!=3){
					
					$("#abrirLote").attr('data-destino', 'resultadoSitioProveedor');
			        $("#abrirLote").attr('data-opcion', 'comboEditarLote');
			        $("#opcion").val('sitioProveedor');
			        $("#abrirLote").attr("disabled","disabled");
			       	abrir($("#abrirLote"), event, false);


			       	/*$("#agregarRegistro").removeAttr('disabled');
			 		$("#abrirLote").attr('data-destino','dRegistro');
			 		$("#abrirLote").attr('data-opcion', 'comboEditarLote');
			 		$("#opcion").val('registros');
			 		abrir($("#abrirLote"),event,false);*/
			       	
			       	
				} else{

					if ($.trim($("#cbProveedor").val()) == "" ) {
						$("#agregarRegistro").attr("disabled","disabled");	        
				    } else{
				    	$("#agregarRegistro").removeAttr("disabled");
				    }
				    
					event.stopImmediatePropagation();
					
					var table = document.getElementById('tablaLotesConformar');	    
			 		var rowCount = table.rows.length; 		
			 		if(rowCount > 2){
				 		$("#cbProducto").removeAttr("disabled","disabled");
			 		}						
			 
			 		$("#agregarRegistro").removeAttr('disabled');
			 		$("#abrirLote").attr('data-destino','dRegistro');
			 		$("#abrirLote").attr('data-opcion', 'comboEditarLote');
			 		$("#opcion").val('registros');
			 		abrir($("#abrirLote"),event,false);

			 		if(rowCount > 2){
				 		$("#cbProducto").attr("disabled","disabled");	 			
			 		}
			    }		
		       	
		} else{
			$("#sitioProveedor").attr("disabled",true).val("");
			$("#agregarRegistro").attr("disabled",true).val("");
		}
		
		event.stopImmediatePropagation();
 		
	 });

	 

	var producto="";
	function addFilasprueba(tableID){
		var cont=0;
		var array2="";

		if($("#CProveedor").val()=="1"){
			$("#cbProveedor").attr("disabled",true);
	  	}

		if($("#CAraProveedor").val()=="1"){
			$("#sitioProveedor").attr("disabled",true);
	  	}

 		if($('#tablaLotes input:checkbox:checked').length < 1){
 			$("#estado").html("Seleccione uno o más registros para conformar el lote").addClass("alerta");
 		}
 		
 		else{
 			$("#estado").html("");
 		}
		
		$('#tablaLotes input:checkbox:checked').each(function(){
			$("#cbProducto").attr("disabled","disabled");


			var str =$("#nProducto").val();
			var nombreProducto;
			var index;				
			//$("#sitioProveedor").attr("disabled","disabled");
		    var array = $(this).parent().siblings().map(function(){        
		      return $(this).text().trim();
		    }).get();  
		   
		   var thArray = [];
		   var arrayConformar = [];

            $('#tablaLotes > thead > tr > th').each(function(){
                thArray.push($(this).text());
            });
            
            var banderaVariedad="vacio";
            var columnaVariedad=0;
            var cadena = "vacia";

			var i = 0;
            
            thArray.forEach(function(element){       
                if(element.toUpperCase() =='VARIEDAD'){
                	banderaVariedad="existe";
                	columnaVariedad= i ;
                	cadena = element;
                }
                i++;                
            });      

            $('#tablaLotesConformar > thead > tr > th').each(function(){
                arrayConformar.push($(this).text());
            });

            var variedadConformar="";

            i=0;
            arrayConformar.forEach(function(element){
                if(element.toUpperCase() =='VARIEDAD'){
                    //alert("entro");
                	banderaVariedadConformar="existe";
                	columnaVariedadConformar= i ;
                	cadenaConformar = element;
                	variedad = $('#tablaLotesConformar tbody tr:eq(1) td:eq('+i+')').text();
                	
                }
                i++;
            });
			    
		    	if(productoConformar != $("#cbProducto").val().toLowerCase()){
		    		$("#estado").html("No puede conformar un lote con distintos productos.").addClass('alerta');		    	
		    	} else{
			    	
			    	var validacion=false;

			    	if(banderaVariedad=="existe"){
			    		
    		    		if(variedad != array[columnaVariedad-1]){
    		    			
    		    			$("#estado").html("Uno o más registros no fueron agregados al tener distintas variedades.").addClass('alerta');

    		    			validacion=true;
    		    		}
			    	}
			    	
		    		if(!validacion){		    			

		    			verificarRegistro(array[0]);
		    			
			    		if(rDuplicado==1){			    			
			    			$("#estado").html("Uno o varios registros ya se encuentran agregados.").addClass('alerta');			    
			    			console.log("entro duplicado");			
			    		}
			    		else{
			    			
			    			$("#conformarLote").removeAttr("disabled");	

			    			var nColumnas = $("#tablaLotes tr:last td").length;
							var caracteristicas="";			    
						    inicio=5;
						    
						    if(nColumnas>6){
							    limite = nColumnas - 6;
							    for(i=0;i<=limite-1;i++){
							    	caracteristicas+='<td>'+array[inicio]+'</td>';
							    	inicio+=1;							    	
							    }
						    }

						    dProveedor = $("#cbProveedor").val();
							dArea= $("#idAreaProveedor").val();

							var validarAreaUnica = false;

						    /*var cadena = '<tr><td>'+array[0]+'</td><td>'+array[1]+'</td><td>'+array[3]+'</td><td>'+array[4]+'</td>'+caracteristicas+'<td class="borrar"><button class="icono" onclick="delFilaActual(this);return false"></button></td></tr>';			    
							$("#tablaLotesConformar tbody").append(cadena);*/

							if($("#CAreaProveedor").val()=="1"){

    							$('#tablaLotesConformar tbody tr').each(function (rows){
    							    valProveedor = $(this).find("td").eq(1).find('input[name="dProveedor[]"]').val();
    							    valArea = $(this).find("td").eq(1).find('input[name="dArea[]"]').val();		
    
    							    if(valProveedor==dProveedor){
    								    if(valArea!=dArea){
    								    	validarAreaUnica = true;
    								    }
    							    }
    							    
    							    console.log(valProveedor +" - " + valArea );
    						    });
    
    						    if(!validarAreaUnica){
        						    var cadena = '<tr><td>'+array[0]+'</td><td>'+array[1]+'<input type="hidden" value="'+dProveedor+'" name="dProveedor[]">'+'<input type="hidden" value="'+dArea+'" name="dArea[]">'+
        						    '</td><td>'+array[3]+'</td><td>'+array[4]+'</td>'+caracteristicas+'<td class="borrar"><button class="icono" onclick="delFilaActual(this);return false"></button></td></tr>';			    
        							$("#tablaLotesConformar tbody").append(cadena);
    
    						    } else{
    						    	mostrarMensaje("No puede conformar un lote con diferentes areas del proveedor.","FALLO");
    						    }

							} else{
								var cadena = '<tr><td>'+array[0]+'</td><td>'+array[1]+
    						    '</td><td>'+array[3]+'</td><td>'+array[4]+'</td>'+caracteristicas+'<td class="borrar"><button class="icono" onclick="delFilaActual(this);return false"></button></td></tr>';			    
    							$("#tablaLotesConformar tbody").append(cadena);
							}
			    		
				    	}
			    	
			    		
		    		}
		 		    
		    	}
		   
		    sumarTabla(0);
		  });
		
		}
		
	function delFilaActual(r){		    		    
	    var i = r.parentNode.parentNode.rowIndex;
	    var table = document.getElementById('tablaLotesConformar');
	    var rowCount = table.rows.length;		    

	    if(rowCount==2){
	    	$("#estado").html("Debe existir al menos un registro.").addClass('alerta');
	    } else{
	    	table.deleteRow(i);
	    }		    
 		sumarTabla(0);
	}
		
		function sumarTabla(val){
			 suma=0;
			 var table = document.getElementById('tablaLotesConformar');
		     var rowCount = table.rows.length;	
			    $('#tablaLotesConformar tbody tr').each(function (index2) {    
			    var cantidad = $(this).find("td").eq(3).html();			   
			    if (rowCount>val){
			    	suma+= parseFloat(cantidad);			        
			    }
			    });			    
			    var conDecimal = suma.toFixed(2);
			   $("#cantidadLote").val(conDecimal);
			   $("#cantidadLoteh").val(conDecimal);
	   }
		   			
	   function verificarRegistro(produ){
		   $('#tablaLotesConformar tbody tr').each(function (rows) {
			   var rd= $(this).find('td').eq(0).html();        	   
			    if (rows>0){
			    	if(rd == produ){			    		
			    		rDuplicado=1;
			    		return false;
			    	} else{
			    		rDuplicado=0;			    		
			    	}			        
			    }
			});
	   }
	
		
 </script>
