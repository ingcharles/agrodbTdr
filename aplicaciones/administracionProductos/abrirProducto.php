<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorAuditoria.php';
	
	$idProducto = $_POST['idProducto'];
	$areaProducto = $_POST['areaProducto'];
	$identificador = $_SESSION['usuario'];
	
	$fecha1= date('Y-m-d - H-i-s');
	$fecha = str_replace(' ', '', $fecha1);
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();	
	$cc = new ControladorCatalogos();
	$ca = new ControladorAuditoria();
	
	$producto = pg_fetch_assoc($cr->abrirProducto($conexion, $idProducto));
	
	$tipoSubtipoProducto = pg_fetch_assoc($cc->obtenerTipoSubtipoXProductos($conexion, $idProducto));
	
	$tipoProducto = $cc->listarTipoProductosXarea($conexion, $areaProducto);
	
	$qSubtipoProducto = $cc->listarSubProductos($conexion);
	
	//-----------------------INICIO----------------------
	$variedad= $cc->ListarVariedades($conexion);
	
	$variedadxProducto=$cr->ListarVariedadesXProducto($conexion, $idProducto);
	//-----------------------FIN----------------------
	
	while($fila = pg_fetch_assoc($qSubtipoProducto)){
		$subtipoProducto[]= array('idSubtipoProducto'=>$fila['id_subtipo_producto'], 'nombre'=>$fila['nombre'], 'idTipoProducto'=>$fila['id_tipo_producto']);
	}
		
	$qCodigoAdicionales = $cr->listarCodigoComplementarioSuplementario($conexion, $idProducto);
	
	if($areaProducto == 'IAP' || $areaProducto == 'IAV' || $areaProducto == 'IAF' || $areaProducto == 'IAPA'){
		$productoInocuidad = pg_fetch_assoc($cr->buscarProductoInocuidad($conexion,$idProducto));
		$qCodigosInocuidad = $cr->listarCodigoInocuidad($conexion, $idProducto);
	}
	
	$unidades = $cc->listarUnidadesMedida($conexion);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Detalle de producto</h1>
	</header>
	<div id="estado"></div>
	<form id="regresar" data-rutaAplicacion="administracionProductos" data-opcion="abrirSubtipoProducto" data-destino="detalleItem">
		<input type="hidden" name="idSubtipoProducto" value="<?php echo $producto['id_subtipo_producto'];?>"/>
		<input type="hidden" name="areaSubProducto" value="<?php echo $areaProducto;?>"/>
		<button class="regresar">Regresar a Subtipo de Producto</button>
	</form>
	
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="actualizarProducto" data-rutaAplicacion="administracionProductos" data-opcion="modificarProducto" >
					<input type="hidden" id="idProducto" name="idProducto" value="<?php echo $producto['id_producto'];?>">
					<input type="hidden" id="area" name="area" value="<?php echo $areaProducto;?>">
					<input type="hidden" id="partidaOriginal" name="partidaOriginal" value="<?php echo $producto['partida_arancelaria'];?>"/>
					<input name="codigoProducto" id="codigoProducto" type="hidden" value="<?php echo $producto['codigo_producto'];?>"/>
					<input id="identificadorModificacion" name="identificadorModificacion" type="hidden" value="<?php echo $identificador;?>" />
					
					<button id="modificar" type="button" class="editar">Modificar</button>
					<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>					
					
					<fieldset>
						<legend>Producto</legend>
						
						<div data-linea="1">			
							<label>Tipo producto</label> 
							<select id="tipoProducto" name="tipoProducto" disabled="disabled" required>
								<option value="">Tipo producto....</option>
									<?php 
										while ($fila = pg_fetch_assoc($tipoProducto)){
											$opcionesTipoProducto[] =  '<option value="'.$fila['id_tipo_producto']. '" data-grupo="'. $fila['id_area'] . '">'. $fila['nombre'] .'</option>';
										}
									?>
							</select>
						</div>
						
						<div data-linea="2">			
							<label>Subtipo producto</label> 
							<select id="subTipoProducto" name="subTipoProducto" disabled="disabled" required>
							</select>
							<input type="hidden" id="subTipoInicial" name="subTipoInicial" value="<?php echo $tipoSubtipoProducto['id_subtipo_producto'];?>"/>
						</div>
						
							
						<div data-linea="3">
							<label for="nombreProducto">Nombre</label>
							<input id="nombreProducto" name="nombreProducto" type="text" value="<?php echo $producto['nombre_comun'];?>" required="required" disabled="disabled"/>	
						</div>
						
						<div data-linea="3">
							<label for="nombreCientifico">Nombre científico</label>
							<input name="nombreCientifico" id="nombreCientifico" type="text" value="<?php echo $producto['nombre_cientifico'];?>" disabled="disabled"/>
						</div>
						
						<div data-linea="4">
							<label for="partidaArancelaria">Partida Arancelaria</label>
							<input name="partidaArancelaria" id="partidaArancelaria" type="text" maxlength="10" value="<?php echo $producto['partida_arancelaria'];?>" disabled="disabled" />
						</div>
						
						<div data-linea="4">
							<label for="codigoProducto">Código producto</label>
							<!-- input name="codigoProducto" id="codigoProducto" type="text" value="<'?php echo $producto['codigo_producto'];?>" required="required" disabled="disabled"/-->
							<?php echo $producto['codigo_producto'];?>
						</div>
						
						<!-- div data-linea="3">
							<label for="subcodigoProducto">Subcódigo producto</label>
							<input name="subcodigoProducto" id="subcodigoProducto" type="text" value="<-?php echo $producto['subcodigo_producto'];?>" required="required" disabled="disabled"/>
						</div-->
						
						<div data-linea="5">
							<label>Unidad de Medida</label> 
								<select id="unidadMedida" name="unidadMedida" disabled="disabled" required>
									<option value="" selected="selected">Seleccione una unidad....</option>
									<?php 
										while ($fila = pg_fetch_assoc($unidades)){
													echo '<option value="' . $fila['codigo'] . '" >'. $fila['nombre'] .'</option>';
										}
									?>
							</select>
						
						</div>
						
						<div data-linea="6">
						<label>Pertenece a programa</label>
						<select id="pertenecePrograma" name="pertenecePrograma" disabled="disabled" required>
									<option value="" selected="selected">Seleccione....</option>
									<option value="SI">SI</option>
									<option value="NO">NO</option>
								
							</select>
						</div>
						
						<?php 
							if($areaProducto=="SV"){
								echo "<div data-linea=6>
										<label>Trazabilidad</label>
										<select id=trazabilidad name=trazabilidad disabled='disabled' required>
											<option value=NO>NO</option>
											<option value=SI>SI</option>
										</select>
									</div>";
								
								echo "<div data-linea=7>
									<label>Movilización</label>
										<select id=movilizacion name=movilizacion disabled='disabled' required>
											<option value=NO>NO</option>
											<option value=SI>SI</option>
										</select>
								</div>";
								
								echo "<div data-linea='7'>
									<label for='clasificacionProductoSV'>Clasificación</label>
									<select id='clasificacionProductoSV' name='clasificacionProductoSV' disabled='disabled'>
											<option value=''>Seleccione....</option>
											<option value='ornamentales'>Ornamentales</option>
											<option value='musaceas'>Musaceas</option>
											<option value='otros' selected='selected'>Otros</option>
									</select>
								</div>";
							}
						?>
						
						<div id="productoInocuidad">
							<div data-linea="8">
								<label for="composicion">Composición</label>
								<input name="composicion" id="composicion" type="text" disabled="disabled"  value="<?php echo $productoInocuidad['composicion'];?>"/>
							</div>
							<div data-linea="9">
								<label for="formulacion">Formulación</label>
								<input name="formulacion" id="formulacion" type="text"  disabled="disabled" value="<?php echo $productoInocuidad['formulacion'];?>"/>
							</div>
						</div>
						
						<div data-linea="10">
						<label>N° piezas</label>
						<select id="numPiezas" name="numPiezas" disabled="disabled"  required>
									<option value="" selected="selected">Seleccione....</option>
									<option value="0">N/A</option>
									<option value="1" >1 pieza</option>
									<option value="2" >2 piezas</option>
									<option value="4" >4 piezas</option>
							</select>
						</div>
						<div data-linea="11">
							<label>Archivo adjunto</label> <?php echo ($producto['ruta']=='0'? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$producto['ruta'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
						</div>
						
						<div data-linea="12">
							<label for="documento">Documento</label>
							<input type="file" class="archivo" name="documento" id="documento"  disabled="disabled" />
							<input type="hidden" class="rutaArchivo" id="archivo" name="archivo" value="<?php echo $producto['ruta'];?>"/>
							<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
							<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?> "/>
							<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/administracionProductos/producto" disabled="disabled">Subir archivo</button>
						</div>

					</fieldset>
				</form>	
				
				
				<form id="nuevoCodigoInocuidad" data-rutaAplicacion="administracionProductos" data-opcion="guardarNuevoCodigoInocuidad" >
						<input type="hidden" id="idProductoIncouidad" name="idProductoInocuidad" value="<?php echo $producto['id_producto'];?>">
							<fieldset>
								<legend>Presentación</legend>
									
									<div data-linea="1">
										<label>Presentación</label>
											<input name="presentacion" id="presentacion" type="text"  required="required"/>
									</div>
									<div>
										<button type="submit" class="mas">Añadir presentación</button>
									</div>
							</fieldset>
					</form>
					
					<div id="presentacionInocuidad">
						<fieldset>
							<legend>Presentación ingresadas</legend>
							<table id="codigoInocuidad">
								<?php 
								if($areaProducto == 'IAP' || $areaProducto == 'IAV' || $areaProducto == 'IAF' || $areaProducto == 'IAPA'){
									while ($codigosInocuidad = pg_fetch_assoc($qCodigosInocuidad)){
										echo $cr->imprimirCodigoInocuidad($codigosInocuidad['id_producto'], $codigosInocuidad['subcodigo'], $codigosInocuidad['presentacion']);
									}
								}
								?>
							</table>
						</fieldset>
					</div>
				
				<form id="nuevoCodigoSC" data-rutaAplicacion="administracionProductos" data-opcion="guardarNuevoCodigoSC" >
					<input type="hidden" id="idProductoSC" name="idProductoSC" value="<?php echo $producto['id_producto'];?>">
						<fieldset>
							<legend>Código complementario y suplementario</legend>
								<div data-linea="1">
									<label>Código complementario</label>
									<select id="codigoComplementario" name="codigoComplementario">
										<option value="0000">0000</option>
										<option value="0001">0001</option>
										<option value="0002">0002</option>
										<option value="0003">0003</option>
										<option value="0004">0004</option>
										<option value="0005">0005</option>
										<option value="0006">0006</option>
										<option value="0007">0007</option>
										<option value="0008">0008</option>
										<option value="0009">0009</option>
										<option value="0010">0010</option>
										<option value="0011">0011</option>
										<option value="0012">0012</option>
										<option value="0013">0013</option>
										<option value="0014">0014</option>
										<option value="0015">0015</option>
										<option value="0016">0016</option>
										<option value="0017">0017</option>
										<option value="0018">0018</option>
										<option value="0019">0019</option>
										<option value="0020">0020</option>
										<option value="0021">0021</option>
										<option value="0022">0022</option>
										<option value="0023">0023</option>
										<option value="0024">0024</option>
										<option value="0025">0025</option>
									</select>
								</div>
								<div data-linea="1">
									<label>Código suplementario</label>
									<select id="codigoSuplementario" name="codigoSuplementario">
										<option value="0000">0000</option>
										<option value="0001">0001</option>
										<option value="0002">0002</option>
										<option value="0003">0003</option>
										<option value="0004">0004</option>
										<option value="0005">0005</option>
										<option value="0006">0006</option>
										<option value="0007">0007</option>
										<option value="0008">0008</option>
										<option value="0009">0009</option>
										<option value="0010">0010</option>
										<option value="0011">0011</option>
										<option value="0012">0012</option>
										<option value="0013">0013</option>
										<option value="0014">0014</option>
										<option value="0015">0015</option>
										<option value="0016">0016</option>
										<option value="0017">0017</option>
										<option value="0018">0018</option>
										<option value="0019">0019</option>
										<option value="0020">0020</option>
										<option value="0021">0021</option>
										<option value="0022">0022</option>
										<option value="0023">0023</option>
										<option value="0024">0024</option>
										<option value="0025">0025</option>
									</select>
								</div>
								<div>
									<button type="submit" class="mas">Añadir código</button>
								</div>
						</fieldset>
					</form>
					
					<fieldset>
						<legend>Codigos ingresados</legend>
						<table id="codigoSC">
							<?php 
								while ($codigoAdicionales = pg_fetch_assoc($qCodigoAdicionales)){
									echo $cr->imprimirCodigoComplementarioSuplementario($codigoAdicionales['id_producto'], $codigoAdicionales['codigo_complementario'], $codigoAdicionales['codigo_suplementario']);
								}
							?>
						</table>
					</fieldset>
			
			<form id="nuevoVariedadProducto" data-rutaAplicacion="administracionProductos" data-opcion="guardarNuevoVariedadProducto" >
				<input type="hidden" id="idProductoVP" name="idProductoVP" value="<?php echo $producto['id_producto'];?>">
				<input type="hidden" id="nombreVariedad" name="nombreVariedad">
				<fieldset id="variedades">
					<legend>Variedad de Productos</legend>
					<div data-linea="1">
							<label>Variedad</label>
								<select id="variedadProducto" name="variedadProducto">
									<option value="0">Seleccione...</option>
										<?php 
																
										while ($fila = pg_fetch_assoc($variedad)){
					    				echo '<option value="'.$fila['id_variedad'].'">'.$fila['nombre'].'</option>';
					    				}
										?>
								</select>
							<button type="submit" class="mas">Añadir variedad</button>	
					</div>
				</fieldset>
			</form>
			
				<fieldset id=anadirVariedades>
					<legend>Añadir variedades</legend>
					<table id="codigoVP">
						<?php 
					
						while ($fila = pg_fetch_assoc($variedadxProducto)){
							echo $cr->imprimirVariedad($fila['id_producto'],$fila['id_variedad'],$fila['nombre'],$fila['codigo_variedad']);
							
						}
						?>
					</table>
				</fieldset>
			</td>
		</tr>	
	</table>
	
<fieldset>
		<legend>Historial de Producto</legend>
			
			<button type="button" id='mostrarHistorial'>Mostrar/Ocultar</button>   
				<table id="historial">
			   		<thead>
			   			<tr>
			   				<th colspan =2>Primera modificación del Registro</th>
			   			</tr>
						<tr>
					    	<th>Fecha</th>
					     	<th>Acción realizada</th>
					    </tr>
				 	</thead>
					<tbody>
					 	<tr>
					     	<?php 
					     	$qHistorial = $ca->listaHistorial($conexion, $producto['id_producto'], $_SESSION['idAplicacion'], 'ASC', 1);
						     	
				      			while($historial = pg_fetch_assoc($qHistorial)){
							        echo ' <td>'.date('j/n/Y (G:i:s)',strtotime($historial['fecha'])).'</td>
							            <td>'.$historial['accion'].'</td></tr><tr>';
							    }
					     	?>
					    </tr>
					</tbody>
					
					<thead>
						<tr>
			   				<th colspan =2>Última modificación del Registro</th>
			   			</tr>
						<tr>
					    	<th>Fecha</th>
					     	<th>Acción realizada</th>
					    </tr>
				 	</thead>
					<tbody>
					 	<tr>
					     	<?php 
					     		$qHistorial = '';
					     		$historial = '';
					     		
						     	$qHistorial = $ca->listaHistorial($conexion, $producto['id_producto'], $_SESSION['idAplicacion'], 'DESC', 1);
							    while($historial = pg_fetch_assoc($qHistorial)){
							    	echo ' <td>'.date('j/n/Y (G:i:s)',strtotime($historial['fecha'])).'</td>
							            <td>'.$historial['accion'].'</td></tr><tr>';
							    }
					     	?>
					    </tr>
					</tbody>
			  	</table>
 	</fieldset>		
</body>
<script>

	var array_comboTipoProducto = <?php echo json_encode($opcionesTipoProducto);?>;
	var array_comboSubTipoProducto = <?php echo json_encode($subtipoProducto);?>;


						
	$('document').ready(function(){

		cargarValorDefecto("unidadMedida","<?php echo $producto['unidad_medida'];?>");
		cargarValorDefecto("pertenecePrograma","<?php echo $producto['programa'];?>");
		cargarValorDefecto("trazabilidad","<?php echo $producto['trazabilidad'];?>");
		cargarValorDefecto("movilizacion","<?php echo $producto['movilizacion'];?>");
		cargarValorDefecto("clasificacionProductoSV","<?php echo $producto['clasificacion'];?>");
		cargarValorDefecto("numPiezas","<?php echo $producto['numero_piezas'];?>");

		acciones("#nuevoCodigoSC","#codigoSC");
		acciones("#nuevoCodigoInocuidad","#codigoInocuidad");
		acciones("#nuevoVariedadProducto","#codigoVP");

		var area= <?php echo json_encode($areaProducto); ?>;

		$('#productoInocuidad').hide();
		$('#nuevoCodigoInocuidad').hide();
		$('#presentacionInocuidad').hide();
		
		
		if(area == 'IAP' || area == 'IAV' || area == 'IAF' || area == 'IAPA'){
			$('#productoInocuidad').show();
			$('#nuevoCodigoInocuidad').show();
			$('#presentacionInocuidad').show();
			$('#composicion').attr("required", "required");
			$('#formulacion').attr("required", "required");
		}

		if(area=='SV'){
			$("#variedades").show();
			$("#anadirVariedades").show();
		}else{
			$("#variedades").hide();
			$("#anadirVariedades").hide();
		}

		
		distribuirLineas();
		construirValidador();

		for(var i=0; i<array_comboTipoProducto.length; i++){
			 $('#tipoProducto').append(array_comboTipoProducto[i]);
	    }

		cargarValorDefecto("tipoProducto","<?php echo $tipoSubtipoProducto['id_tipo_producto'];?>");
		

		sSubTipoProducto = '<option value="">Subtipo de producto....</option>';
		
		for(var i=0; i<array_comboSubTipoProducto.length; i++){
			if(array_comboSubTipoProducto[i]['idTipoProducto'] == $('#tipoProducto').val()){
				sSubTipoProducto += '<option value="'+array_comboSubTipoProducto[i]['idSubtipoProducto']+'">'+array_comboSubTipoProducto[i]['nombre']+'</option>';
			}

			 $('#subTipoProducto').html(sSubTipoProducto);
			 cargarValorDefecto("subTipoProducto","<?php echo $tipoSubtipoProducto['id_subtipo_producto'];?>");
	    }
		

	});

	$("#variedadProducto").change(function(){	
		$("#nombreVariedad").val($("#variedadProducto option:selected").text());
	});
	    	
	$("#tipoProducto").change(function(){	

		subTipo = '<option value="">Subtipo de producto....</option>';
		for(var i=0; i<array_comboSubTipoProducto.length; i++){
		    if (array_comboSubTipoProducto[i]['idTipoProducto'] == $("#tipoProducto").val()){
		    	subTipo += '<option value="'+array_comboSubTipoProducto[i]['idSubtipoProducto']+'">'+array_comboSubTipoProducto[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#subTipoProducto').html(subTipo);
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$("button.subirArchivo").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#actualizarProducto").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});

	/*$('#documento').change(function(event){
		if($("#nombreProducto").val() != ""){
			subirArchivo('documento',$("#nombreProducto").val().replace(/ /g,''),'aplicaciones/administracionProductos/producto', 'archivo');
		}else{
			alert("Por favor ingrese el nombre del producto para subir el documento.");
			$("#documento").val("");
		}
	});*/

	$('button.subirArchivo').click(function (event) {
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , $("#nombreProducto").val().replace(/ /g,'')+$('#fecha').val().replace(/ /g,'')
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

	$("#nuevoVariedadProducto").submit(function(event){
	    
	    event.preventDefault();

	    $(".alertaCombo").removeClass("alertaCombo");
	  	var error = false;

		if($("#variedadProducto").val()=="0"){	
			error = true;		
			$("#variedadProducto").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor seleccione una variedad.").addClass('alerta');
		}
	});
	
	$("#mostrarHistorial").click(function(){
		 $("#historial").slideToggle();		 
	});
</script>
</html>