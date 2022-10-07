<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/Constantes.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	
	$idProducto = $_POST['idProducto'];
	$areaProducto = $_POST['areaProducto'];
	$identificador = $_SESSION['usuario'];

	$conexion = new Conexion();
	$cr = new ControladorRequisitos();	
	$cc = new ControladorCatalogos();
	$cop = new ControladorRegistroOperador();
	$constg = new Constantes();
	
	$producto = pg_fetch_assoc($cr->abrirProducto($conexion, $idProducto));
	$productoInocuidad = pg_fetch_assoc($cr->buscarProductoInocuidad($conexion,$idProducto));
	
	if($productoInocuidad['id_operador'] != ''){
		$qOperador = $cop->listarOperadoresEmpresa($conexion,$productoInocuidad['id_operador']);
		$operador = pg_fetch_assoc($qOperador);
	}
	
	$tipoSubtipoProducto = pg_fetch_assoc($cc->obtenerTipoSubtipoXProductos($conexion, $idProducto));
	$tipoProducto = $cc->listarTipoProductosXarea($conexion, $areaProducto);
	$qSubtipoProducto = $cc->listarSubProductos($conexion);
	
	while($fila = pg_fetch_assoc($qSubtipoProducto)){
		$subtipoProducto[]= array('idSubtipoProducto'=>$fila['id_subtipo_producto'], 'nombre'=>$fila['nombre'], 'idTipoProducto'=>$fila['id_tipo_producto']);
	}
		
	$qCodigoAdicionales = $cr->listarCodigoComplementarioSuplementario($conexion, $idProducto);
	
	if($areaProducto == 'IAP' || $areaProducto == 'IAV' || $areaProducto == 'IAF' || $areaProducto == 'IAPA'){
		
		$qCodigosInocuidad = $cr->listarCodigoInocuidad($conexion, $idProducto);
		$qfFormulador = $cr->listarFabricanteFormulador($conexion,$idProducto);
		$qComposicion = $cr->listarComposicionProductosInocuidad($conexion, $idProducto);
		$qUso = $cr->listarUsos($conexion,$idProducto);
	}
	
	$unidades = $cc->listarUnidadesMedida($conexion);
	while($fila = pg_fetch_assoc($unidades)){
		$unidad[]= array('identificador'=>$fila['id_unidad_medida'], 'codigo'=>$fila['codigo'], 'nombre'=>$fila['nombre'], 'tipo'=>$fila['tipo_unidad']);
	}
	
	/*$tipoListaProducto = $cc-> listarTipoProductos($conexion);
	while($fila = pg_fetch_assoc($tipoListaProducto)){
		$tlistaProducto[]= array('idTipoProducto'=>$fila['id_tipo_producto'], 'nombre'=>$fila['nombre'], 'idArea'=>$fila['id_area']);
	}
	
	$listaProductos = $cr-> listarProductos($conexion);
	while($fila = pg_fetch_assoc($listaProductos)){
		$listaProducto[]= array('idProducto'=>$fila['id_producto'], 'nombre'=>$fila['nombre_comun'], 'idSubTipoProducto'=>$fila['id_subtipo_producto']);
	}*/
	
	$especie = pg_fetch_assoc($cc->especiesMovilizacion($conexion));
	
	$declaracionVenta = $cc->listarDeclaracionVenta($conexion);
	
	while($fila = pg_fetch_assoc($declaracionVenta)){
	    $declaracion[]= array('identificador'=>$fila['id_declaracion_venta'], 'nombre'=>$fila['declaracion_venta']);
	}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Detalle de Producto Materia Prima</h1>
	</header>
	
	<div id="estado"></div>
	<form id="regresar" data-rutaAplicacion="registroProducto" data-opcion="abrirSubtipoProductoMateriaPrima" data-destino="detalleItem">
		<input type="hidden" name="idSubtipoProducto" value="<?php echo $producto['id_subtipo_producto'];?>"/>
		<input type="hidden" name="areaSubProducto" value="<?php echo $areaProducto;?>"/>
		<input type="hidden" name="numeroPestania" value="2"/>
		<button class="regresar">Regresar a Subtipo de Producto</button>
	</form>
	
			  <div class="pestania" id="ParteI">	
			
			<form id="actualizarProducto" data-rutaAplicacion="registroProducto" data-opcion="modificarProductoMateriaPrima" >
				
					<button id="modificar" type="button" class="editar">Editar</button>
					<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
				
					<input type="hidden" id="idProducto" name="idProducto" value="<?php echo $producto['id_producto'];?>">
					<input type="hidden" id="nombreSubtipoProducto" name="nombreSubtipoProducto" value="<?php echo $tipoSubtipoProducto['nombre_subtipo'];?>" />
					<input type="hidden" id="idSubtipoProducto" name="idSubtipoProducto" value="<?php echo $tipoSubtipoProducto['id_subtipo_producto'];?>" />
					<input type="hidden" id="area" name="area" value="<?php echo $areaProducto;?>">
					<input type="hidden" id="areaProducto" name="areaProducto" value="<?php echo $areaProducto;?>">
					<input type="hidden" id="partidaOriginal" name="partidaOriginal" value="<?php echo $producto['partida_arancelaria'];?>"/>
					<input type="hidden" id="codigoProducto" name="codigoProducto"  value="<?php echo $producto['codigo_producto'];?>"/>
					<input type="hidden" id="operador" name="operador" value="<?php echo $productoInocuidad['id_operador'];?>"/>
					<input type="hidden" name="opcion" value="0">
					<input type="hidden" id="archivoSalida" name="archivoSalida" value="0">
					<input id="identificadorModificacion" name="identificadorModificacion" type="hidden" value="<?php echo $identificador;?>" />																												  
					
									
					<fieldset>
			 		<legend>Producto</legend>
			 		
			 			<div data-linea="40">			
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
						<div data-linea="41">			
							<label>Subtipo producto</label> 
							<select id="subTipoProducto" name="subTipoProducto" disabled="disabled" required>
							</select>
							<input type="hidden" id="subTipoInicial" name="subTipoInicial" value="<?php echo $tipoSubtipoProducto['id_subtipo_producto'];?>"/>
						</div>
						<div data-linea="1">
							<label for="nombreProducto">Nombre</label>
							<input id="nombreProducto" name="nombreProducto" type="text" value="<?php echo $producto['nombre_comun'];?>" disabled="disabled" required="required"/>	
						</div>
						<div data-linea="1">
							<label for="nombreCientifico">Nombre científico</label>
							<input name="nombreCientifico" id="nombreCientifico" type="text" value="<?php echo $producto['nombre_cientifico'];?>" disabled="disabled"/>
						</div>
						<div data-linea="2">
							<label for="partidaArancelaria">Partida Arancelaria</label>
							<input name="partidaArancelaria" id="partidaArancelaria" type="text"  data-er="^[0-9]{10}" data-inputmask="'mask': '9999999999'" value="<?php echo $producto['partida_arancelaria'];?>" disabled="disabled" />
						</div>
						<div data-linea="2">
							<label>Unidad de Medida</label> 
								<select id="unidadMedida" name="unidadMedida" disabled="disabled" >
									<option value="" selected="selected">Unidad....</option>
									<?php 
										for($i=0;$i<count($unidad);$i++)
											echo '<option value="' . $unidad[$i]['codigo'] . '" >'. $unidad[$i]['nombre'] .'</option>';
									?>
								</select>
						</div>
						<div data-linea="3">
							<label for="codigoProducto">Código producto</label>
							<!-- input name="codigoProducto" id="codigoProducto" type="text" value="<'?php echo $producto['codigo_producto'];?>" required="required" disabled="disabled"/-->
							<?php echo $producto['codigo_producto'];?>
						</div>
						
						<!-- div data-linea="3">
							<label for="subcodigoProducto">Subcódigo producto</label>
							<input name="subcodigoProducto" id="subcodigoProducto" type="text" value="<-?php echo $producto['subcodigo_producto'];?>" required="required" disabled="disabled"/>
						</div-->
						<div data-linea="3">
							<label for="numeroRegistro">Número Registro</label>
							<input name="numeroRegistro" id="numeroRegistro" type="text" maxlength="256" value="<?php echo $productoInocuidad['numero_registro'];?>" disabled="disabled" />
						</div>
						<div data-linea="4">
						<label>Fecha de registro</label>
							<input type="text"	id="fecha_registro" name="fecha_registro"	value="<?php echo date('j/n/Y',strtotime($productoInocuidad['fecha_registro']));?>" disabled="disabled" required="required" />
						</div>
						<div data-linea="4">
						<label id="lFechaRevaluacion">Fecha reevaluación</label>
							<input type="text"	id="fecha_revaluacion" name="fecha_revaluacion"	value="<?php if (($productoInocuidad['fecha_revaluacion'])!= '') echo date('j/n/Y',strtotime($productoInocuidad['fecha_revaluacion']));?>" disabled="disabled" />
						</div>
						<div data-linea="5">
							<label>Estado</label>
								<select id="status" name="status" disabled="disabled">
									<option value="1" selected="selected">Vigente</option>
									<?php 
									if ($areaProducto == 'IAV'){
									    echo '<option value="9">Eliminado</option>';
									}else{
									   echo '<option value="2">Suspendido</option>
        									<option value="3">Caducado</option>
        									<option value="4">Cancelado</option>';
									}
									?>
									
									
								</select>
						</div>
						<div data-linea="6">
							<label id="lDeclaracionVenta">Declaración de venta</label> 
								<select id="idDeclaracionVenta" name="idDeclaracionVenta" disabled="disabled">
									<option value="0">Seleccione....</option>
									<?php 
										for($i=0;$i<count($declaracion);$i++)
													echo '<option value="' . $declaracion[$i]['identificador'] . '" >'. $declaracion[$i]['nombre'] .'</option>';
									?>
								</select>
								
								<input type="hidden" id="declaracionVenta" name="declaracionVenta" value="<?php echo $productoInocuidad['declaracion_venta'];?>"/>								
						</div>
						<div id="div7" data-linea="7">
								<label>RUC Empresa</label> 
								<input id="txtClienteBusqueda" name="txtClienteBusqueda"  type="text" maxlength="13" value="<?php echo $productoInocuidad['id_operador'];?>" disabled="disabled" />
															
						</div>
						
						<div data-linea="8" id="res_cliente">
							<label>Razón social: </label> <?php echo $operador['nombre_operador']?>
							<input type="hidden" id="empresa" name="empresa" value= <?php echo $operador['identificador']?> />
							<input type="hidden" id="razonSocial" name="razonSocial" value= "<?php echo $operador['nombre_operador']?>"  readonly="readonly" disabled="disabled"/>
						</div>	
						
						<div data-linea="9">
							<label for="observacion">Observaciones/Modificaciones:</label>
							<input name="observacion" id="observacion" type="text" maxlength="1000" value="<?php echo $productoInocuidad['observacion'];?>" disabled="disabled" />
						</div>
						<div data-linea="10">
							<label>Archivo adjunto</label> <?php echo ($producto['ruta']=='0'? '<span class="alerta">No hay ningún archivo adjunto</span>': $producto['ruta']==''? '<span class="alerta">No hay ningún archivo adjunto</span>' : '<a href='.$producto['ruta'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
						</div>
						<div data-linea="11">
							<label for="documento">Documento</label>
							<!-- input type="file" name="documento" id="documento"  disabled="disabled" /-->
							<input type="file" class="archivo" name="informe" accept="application/pdf" disabled="disabled"/>
							<input type="hidden" class="rutaArchivo" name="archivo" value="<?php echo $producto['ruta'];?>"/>
							<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
							<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/registroProducto/producto" >Subir archivo</button>
							
						</div>
			</fieldset>		
			<fieldset>
			 <legend>Caracteristicas</legend>
						
						<!-- div id="productoInocuidad"-->
							
							<div data-linea="1">
								<label>Formulación</label>
									<select id="formulacion" name="formulacion" disabled="disabled" >
									 	<option value="" selected="selected">Formulación....</option>
											<?php 
												$formulaciones = $cr->listarFormulacion($conexion,$areaProducto);
												
												while ($fila = pg_fetch_assoc($formulaciones)) {
													echo '<option value="' . $fila['id_formulacion'] . '" >'. $fila['formulacion'] .'</option>';
											}
											?>
								   </select>
							</div>
							<input name="nombreFormulacion" id="nombreFormulacion" type="hidden" />
						<!-- /div-->
						<div data-linea="2">
							<label for="dosis">Dosis</label>
							<input name="dosis" id="dosis" type="text" maxlength="100" value="<?php echo $productoInocuidad['dosis'];?>" disabled="disabled" />
						</div>
						<div data-linea="2">
							<label for="unidadMedidaDosis">Unidad Dosis</label>
							<select id="unidadMedidaDosis" name="unidadMedidaDosis" disabled="disabled" >
							</select>
						</div>
						<div data-linea="3">
								<label>Categoría toxicológica</label> 
								<select id="caToxicologica" name="caToxicologica" disabled="disabled" >
									<option value="" selected="selected">Categoría....</option>
									<?php 
										$categoriaToxicologica = $cr->listarCategoriaToxicologica($conexion, $areaProducto);
										while ($fila = pg_fetch_assoc($categoriaToxicologica)){
											 echo '<option value="' . $fila['id_categoria_toxicologica'] . '" data-periodo= "' . $fila['periodo_reingreso'] . '">'. $fila['categoria_toxicologica'] .'</option>';
										 }
									?>
								</select>
								<input name="nombreCategoria" id="nombreCategoria" type="hidden" />
						</div>
						<div data-linea="4">
							<label for="periodoReingreso">Período de reingreso/vida útil</label>
							<input id="periodoReingreso" name="periodoReingreso"  value="<?php echo $productoInocuidad['periodo_reingreso'];?>" disabled="disabled"/>
						</div>
						
						<div data-linea="5">
							<label for="periodoCarencia">Período carencia/retiro</label>
							<input name="periodoCarencia" id="periodoCarencia" type="text" value="<?php echo $productoInocuidad['periodo_carencia_retiro'];?>" disabled="disabled" />
						</div>
					</fieldset>					
				</form>	
				
				</div>
				
		<div class="pestania" id="ParteII">	
			<form id="nuevoComposicion" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoComposicion" >
				<input type="hidden" id="idProductoIncouidad" name="idProductoInocuidad" value="<?php echo $producto['id_producto'];?>">
				<input type="hidden" id="cConcentracion" name="cConcentracion" value="<?php echo $productoInocuidad['composicion'];?>">
				<input type="hidden" id="idAreaC" name="idAreaC" value="<?php echo $areaProducto;?>">
			 	<fieldset>
			   		<legend>Composición producto</legend>
					
						<div data-linea="1" id="dComposicion">
							<label for="comboComposicion">Composición actual (Base anterior)</label>
								<?php echo $productoInocuidad['composicion'];?>
						</div>
							
						<div data-linea="60" class="IAV">
							<label>Tipo: </label>
							<select id="idTipoComponente" name="idTipoComponente" required="required">
								<option value="">Tipo de componente....</option>
    										 <?php 
    											$tipoComp = $cr->listarTipoComponente($conexion, $areaProducto);
    										      
    										      while ($tipoComponente = pg_fetch_assoc($tipoComp)){
    										          echo '<option value="' .$tipoComponente['id_tipo_componente'].'" >' . $tipoComponente['tipo_componente'] . '</option>';
    												}
    										 ?>
							</select>
								
							<input type="hidden" name="tipoComponente" id="tipoComponente" />
	        			</div>
							
						<div data-linea="2">
						<label>Nombre: </label>
							<select id="ingredienteActivo" name="ingredienteActivo" required>
								<option value="">Nombre....</option>
									 <?php 
											$ingredienteActivos = $cr->listarIngredienteActivo($conexion,$areaProducto);
											while ($ingredienteActivo = pg_fetch_assoc($ingredienteActivos)){
												echo '<option value="' .$ingredienteActivo['id_ingrediente_activo'].'" data-ingrediente= "'.$ingredienteActivo['ingrediente_quimico']. '" >' . $ingredienteActivo['ingrediente_activo'] . '</option>';
											}
									 ?>
							</select>
						</div>
						<input name="nombreIngredienteActivo" id="nombreIngredienteActivo" type="hidden" />
						<input id="ingredienteQuimico" name="ingredienteQuimico" readonly="readonly" type="hidden"/>
						<div data-linea="3">
							<label for="concentracion">Concentración</label>
								<input name="concentracion" id="concentracion" type="text" required="required" />
						</div>
						<div data-linea="4">
								<label>Unidad</label>
								<select id="unidadMedidaConcentracion" name="unidadMedidaConcentracion" required>
									<option value="" selected="selected">Unidad....</option>
									<?php 
										for($i=0;$i<count($unidad);$i++)
											echo '<option value="' . $unidad[$i]['codigo'] . '" >'. $unidad[$i]['nombre'] .'</option>';
									?>
									<option value="Otro" >Otro</option>
								</select>
						</div>
						
						<div data-linea="5" class="nombreUMedConcentracion">
								<label>Nombre Unidad</label>
								<input type="text" id="nombreUMedConcentracion" name="nombreUMedConcentracion" size="128"/>
						</div>
						
						<div data-linea="6">
							<button type="submit" class="mas">Añadir ingrediente</button>
						</div>
				</fieldset>
			</form>	
		<fieldset>
			<legend>Composición ingresada</legend>
			<table id="composicionInocuidad">
				<?php 
					while ($composicionProductoInocuidad = pg_fetch_assoc($qComposicion)){
						echo $cr->imprimirLineaComposicion($composicionProductoInocuidad['id_producto'], $composicionProductoInocuidad['id_composicion'], $composicionProductoInocuidad['tipo_componente'], $composicionProductoInocuidad['ingrediente_activo'], $composicionProductoInocuidad['concentracion'], $composicionProductoInocuidad['unidad_medida'], $areaProducto);
					}				
				?>
			</table>
		</fieldset>
	</div>
		
		<div class="pestania" id="ParteIII">
			<form id="nuevoCodigoInocuidad" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoCodigoInocuidad" >
				<input type="hidden" id="idProductoIncouidad" name="idProductoInocuidad" value="<?php echo $producto['id_producto'];?>">
						
			<fieldset>
				<legend>Presentación</legend>	
									<div data-linea="1">
										<label>Presentación</label>
											<input name="presentacion" id="presentacion" type="text"  required="required"/>
									</div>
									<div data-linea="2">
    									<label>Unidad</label>
    									<select id="unidadPresentacion" name="unidadPresentacion" required>
    										<option value="" selected="selected">Unidad....</option>
    										<?php 
    											for($i=0;$i<count($unidad);$i++)
    												echo '<option value="' . $unidad[$i]['codigo'] . '" >'. $unidad[$i]['nombre'] .'</option>';
    										?>
											<option value="Otro" >Otro</option>
    									</select>
									</div>
									
									<div data-linea="5" class="nombreUMedPresentacion">
                						<label>Nombre Unidad</label>
                						<input type="text" id="nombreUMedPresentacion" name="nombreUMedPresentacion" size="128"/>
                					</div>
									
									<div data-linea="3">
										<button type="submit" class="mas">Añadir presentación</button>
									</div>
			</fieldset>
		</form>
		<fieldset>
					<legend>Presentación ingresada</legend>
							<table id="codigoInocuidad">
								<?php 
									if($areaProducto == 'IAP' || $areaProducto == 'IAV' || $areaProducto == 'IAF' || $areaProducto == 'IAPA'){
										while ($codigosInocuidad = pg_fetch_assoc($qCodigosInocuidad)){
											echo $cr->imprimirCodigoInocuidad($codigosInocuidad['id_producto'], $codigosInocuidad['subcodigo'], $codigosInocuidad['presentacion'], $codigosInocuidad['unidad_medida'], $codigosInocuidad['nombre_unidad_medida'], $areaProducto);
										}
											}
								?>
							</table>
									
		</fieldset>
		</div>
				
		<div class="pestania" id="ParteVI">	
				<form id="nuevoCodigoSC" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoCodigoSC" >
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
									</select>
								</div>
								<div data-linea="2">
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
									</select>
								</div>
								<div data-linea="3">
									<button type="submit" class="mas">Añadir código</button>
								</div>
					</fieldset>				
				</form>
				<fieldset>	
						<legend>Códigos ingresados</legend>
						<table id="codigoSC">
								<?php 
									while ($codigoAdicionales = pg_fetch_assoc($qCodigoAdicionales)){
										echo $cr->imprimirCodigoComplementarioSuplementario($codigoAdicionales['id_producto'], $codigoAdicionales['codigo_complementario'], $codigoAdicionales['codigo_suplementario']);
									}
								?>
						</table>
				</fieldset>
		</div>
				
			<div class="pestania" id="ParteV">	
				<form id="nuevofFormulador" data-rutaAplicacion="registroProducto" data-opcion="guardarfFormulador" >
					<input type="hidden" id="idProductoIncouidad" name="idProductoInocuidad" value="<?php echo $producto['id_producto'];?>">
					<input type="hidden" id="idAreaF" name="idAreaF" value="<?php echo $areaProducto;?>">
									
					<fieldset>	
					  	<legend>Fabricante/formulador</legend>
							<div data-linea="1">
								<label>Fabricante/formulador</label>
									<input name="formulador" id="formulador" type="text"  required="required"/>
							</div>
												
							<div data-linea="2">
								<label>País origen</label>
								<select id="paisOrigen" name="paisOrigen" required>
									<option value="">País....</option>
									<?php 
										$provincias = $cc->listarSitiosLocalizacion($conexion,'PAIS');
										foreach ($provincias as $provincia){
											echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
										}
									?>
								</select>
							</div>
							
							<input name="nombrePaisFabricante" id="nombrePaisFabricante" type="hidden" />
							
							<div data-linea="5" class="IAV">
							<label>Tipo</label>
								<select id="tipoFabricante" name="tipoFabricante">
									<option value="Titular del registro">Titular del registro</option>
									<option value="Elaborador por Contrato Nacional">Elaborador por Contrato Nacional</option>
									<option value="Extranjero">Extranjero</option>
								</select>
							</div>
							
							<div data-linea="3">
								<button type="submit" class="mas">Añadir</button>
							</div>
							
					</fieldset>
				</form>
				<fieldset>
					  	<legend>Fabricante/Formulador ingresado</legend>
							<table id="productoFF">
								<?php 
									while ($fabricanteFormulador = pg_fetch_assoc($qfFormulador)){
										echo $cr->imprimirfabricanteFormulador($fabricanteFormulador['id_producto'], $fabricanteFormulador['id_fabricante_formulador'], $fabricanteFormulador['nombre'],$fabricanteFormulador['id_pais_origen'],$fabricanteFormulador['pais_origen'],$fabricanteFormulador['tipo'], $areaProducto);
										}
									?>
							</table>
				</fieldset>	
		</div>
			
		<div class="pestania" id="ParteVI">	
			<form id="nuevoUso" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoUso" >
				<input type="hidden" id="idProductoIncouidad" name="idProductoInocuidad" value="<?php echo $producto['id_producto'];?>">
				<input type="hidden" name="opcion" value="0">
				<input type="hidden" id="idAreaU" name="idAreaU" value="<?php echo $areaProducto;?>">
								   
			<fieldset>
		   		<legend>Uso autorizado</legend>
		   		
		   			<div data-linea="11">
						<label>Aplicado a:</label>
						<select id="aplicado_a" name="aplicado_a" required>
							<option value="">Seleccione....</option>
                            <?php echo ($areaProducto == 'IAV'? '<option value="Especie">Especie</option>':'')?>
                            <option value="Instalacion">Instalación</option>
                            <?php echo ($areaProducto == 'IAF'? '<option value="Producto">Producto</option>':'')?>
						</select>
					</div>
					
					<div data-linea="1">
						<label>Uso</label>
						<select id="uso" name="uso" required>
							<option value="">Uso....</option>
								 <?php 
										$usos = $cr->listarUsosProductos($conexion,$areaProducto);
										while ($uso = pg_fetch_assoc($usos)){
											echo '<option value="' . $uso['id_uso'] . '">' . $uso['nombre_uso'] . '</option>';
										}
								 ?>
						</select>
					</div>
					<div data-linea="2">
						<input name="nombreUso" id="nombreUso" type="hidden" />
					</div>	
					<div data-linea="10" class="UsoEspecie">
						<label id="lespecie">Especie</label>
						<select id="especie" name="especie">
							<option value="">Especie....</option>
								 <?php 
										$especies = $cc->especiesMovilizacion($conexion);
										while ($especie = pg_fetch_assoc($especies)){
											echo '<option value="' . $especie['id_especies'] . '">' . $especie['nombre'] . '</option>';
										}
								 ?>
						</select>
					</div>
					
					<div data-linea="5" class="IAV UsoEspecie">
						<label>Nombre Especie</label>
						<input type="text" id="nombreEspecieUso" name="nombreEspecieUso" size="128"/>
					</div>
					
					<div data-linea="6" class="IAV UsoInstalacion">
						<label>Instalación</label>
						<input type="text" id="instalacion" name="instalacion" size="512"/>
					</div>
					
					<div data-linea="3" class="IAF UsoProducto">	
						<label id="lproducto">Producto</label> 
								<input type="text" id="txtProductoBusqueda" name="txtProductoBusqueda" />		
					</div>
					<div data-linea="4" id="res_producto"></div>
					
					<div data-linea="8">
							<button type="submit" class="mas">Añadir uso</button>
					</div>
			</fieldset>
			</form>
			<fieldset>
					<legend>Uso ingresado</legend>
						<table id="usoAutorizadoProducto">
								<?php 
									while ($usosProducto = pg_fetch_assoc($qUso)){
										if($usosProducto['id_especie'] == ''){
										   echo $cr->imprimirUso($usosProducto['id_producto'],$usosProducto['id_uso'],$usosProducto['nombre_uso'], $usosProducto['id_aplicacion_producto'], $usosProducto['nombre'], $usosProducto['id_producto_uso'], $usosProducto['nombre_especie'], $areaProducto);
										}else{
										    echo $cr->imprimirUso($usosProducto['id_producto'],$usosProducto['id_uso'],$usosProducto['nombre_uso'], $usosProducto['id_especie'], $usosProducto['nombre'], $usosProducto['id_producto_uso'], $usosProducto['nombre_especie'], $areaProducto);
										}
									}												
								?>
						</table>
			</fieldset>
	</div>	
		
	
</body>

<script type="text/javascript">

	var array_comboTipoProducto = <?php echo json_encode($opcionesTipoProducto);?>;
	var array_comboSubTipoProducto = <?php echo json_encode($subtipoProducto);?>;
	//var array_tipoListaProducto = < ?php echo json_encode($tlistaProducto);?>;
	//var array_comboProducto = < ?php echo json_encode($listaProducto);?>;
	var valorSubtipoProducto = <?php echo json_encode($tipoSubtipoProducto['nombre_subtipo']);?>;
	var rutaAcceso = <?php echo json_encode($constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/');?>;

	$("#botonCertificado").attr('disabled', 'disabled');
	
	$("#fecha_registro").datepicker({
	    changeMonth: true,
	    changeYear: true
	  });
	$("#fecha_revaluacion").datepicker({
	    changeMonth: true,
	    changeYear: true
	  });
	  
	$('document').ready(function(){

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

		cargarValorDefecto("idDeclaracionVenta","<?php echo $productoInocuidad['id_declaracion_venta'];?>");
		cargarValorDefecto("unidadMedida","<?php echo $producto['unidad_medida'];?>");
		cargarValorDefecto("status","<?php echo $producto['estado'];?>");
		cargarValorDefecto("formulacion","<?php echo $productoInocuidad['id_formulacion'];?>");
		cargarValorDefecto("caToxicologica","<?php echo $productoInocuidad['id_categoria_toxicologica'];?>");
		cargarValorDefecto("declaracionVenta","<?php echo $productoInocuidad['declaracion_venta'];?>");
		
		if(area == 'IAF'){
			$('#periodoReingreso').val($("#caToxicologica option:selected").attr('data-periodo'));
		}

		$("#nombreFormulacion").val($("#formulacion option:selected").text());
		$("#nombreCategoria").val($("#caToxicologica option:selected").text());
	
		acciones("#nuevoCodigoSC","#codigoSC");
		acciones("#nuevoCodigoInocuidad","#codigoInocuidad");
		acciones("#nuevoComposicion","#composicionInocuidad");
		acciones("#nuevofFormulador","#productoFF");
		acciones("#nuevoCategoriaToxicologica","#categoriaToxicologica");
		acciones("#nuevoUso","#usoAutorizadoProducto");

		var area= <?php echo json_encode($areaProducto); ?>;
		$('#nuevoCodigoInocuidad').hide();
		$('#presentacionInocuidad').hide();
		
		if(area == 'IAP' || area == 'IAV' || area == 'IAF' || area == 'IAPA'){
			$('#nuevoCodigoInocuidad').show();
			$('#presentacionInocuidad').show();
			$('#composicion').attr("required", "required");
		}
		if(area == 'IAP'){
			$('#lDeclaracionVenta').hide();
			$('#declaracionVenta').hide();
			$('#lFechaRevaluacion').hide();
			$('#fecha_revaluacion').hide();
			$('#lespecie').hide();
			$('#especie').hide();
			//$('#aplicacionArea').attr("required", "required");
			//$('#tipoProductoArea').attr("required", "required");
			//$('#subTipoProductoArea').attr("required", "required");
			//$('#productoArea').attr("required", "required");
		}
		if(area == 'IAV'){
			//$('#larea').hide();
			//$('#aplicacionArea').hide();
			//$('#lTipoProductoArea').hide();
			//$('#tipoProductoArea').hide();
			//$('#lsubTipoProductoArea').hide();
			//$('#subTipoProductoArea').hide();
			$('#lproducto').hide();
			//$('#productoArea').hide();
			$('#especie').attr("required", "required");
			$('#txtProductoBusqueda').hide();
			$('#res_producto').hide();
		}
		if(area == 'IAF'){
			$('#lespecie').hide();
			$('#especie').hide();
		}
		
		var array_unidades= <?php echo json_encode($unidad); ?>;
		sunidadMedida ='0';
		sunidadMedida = '<option value="">Unidad...</option>';
	    for(var i=0;i<array_unidades.length;i++){
		    if ($("#unidadMedidaDosis").val()!=array_unidades[i]['identificador']){
		    	sunidadMedida += '<option value="'+array_unidades[i]['codigo']+'">'+array_unidades[i]['nombre']+'</option>';
			    }
	   		}
	    $('#unidadMedidaDosis').html(sunidadMedida);
	    cargarValorDefecto("unidadMedidaDosis","<?php echo $productoInocuidad['unidad_dosis'];?>");
	    construirAnimacion($(".pestania"));	

	    if($("#cConcentracion").val() == ''){
			$("#dComposicion").hide();
		}

		if($("#txtClienteBusqueda").val()==''){
			$("#res_cliente").hide();	
		}
		
		/*if(area == 'IAV' ){
			$(".IAV").show();
			$("#idTipoComponente").attr('required','required');
		}else{
			$(".IAV").hide();
			$("#idTipoComponente").removeAttr('required');
		}*/
		
		$('.nombreUMedConcentracion').hide();
	    $('.nombreUMedPresentacion').hide();
		
		if(<?php echo $producto['estado'];?> != 1){
			$("#botonCertificado").attr('disabled', 'disabled');
		}else{
			$("#botonCertificado").removeAttr('disabled');
		}
		
	    distribuirLineas();
		construirValidador();
  });
  
  $('#idTipoComponente').change(function(){
		if($("#idTipoComponente").val() != ""){
			$("#tipoComponente").val($("#idTipoComponente option:selected").text());
		}else{
			$("#tipoComponente").val('');
		}
	});

	$('#unidadMedidaConcentracion').change(function(){
		if($("#unidadMedidaConcentracion option:selected").val() == "Otro"){
			$('.nombreUMedConcentracion').show();
			$('#nombreUMedConcentracion').attr('required', 'required');
		}else{
			$('.nombreUMedConcentracion').hide();
			$('#nombreUMedConcentracion').val('');
			$('#nombreUMedConcentracion').removeAttr('required');
		}
	});

	$('#unidadPresentacion').change(function(){
		if($("#unidadPresentacion option:selected").val() == "Otro"){
			$('.nombreUMedPresentacion').show();
			$('#nombreUMedPresentacion').attr('required', 'required');
		}else{
			$('.nombreUMedPresentacion').hide();
			$('#nombreUMedPresentacion').val('');
			$('#nombreUMedPresentacion').removeAttr('required');
		}
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

	$("#caToxicologica").change(function(){
		$("#nombreCategoria").val($("#caToxicologica option:selected").text());
		if(area == 'IAF'){
			$('#periodoReingreso').val($("#caToxicologica option:selected").attr('data-periodo'));
			$('#periodoReingreso').attr("readonly", "readonly");
			$('#periodoReingreso').attr("required", "required");
		}else if (area == 'IAV'){
			$('#periodoReingreso').removeAttr("readonly");
			$('#periodoReingreso').attr("required", "required");
		}   
	 });

	$('#formulacion').change(function(){
		if($("#formulacion").val() != ""){
			$("#nombreFormulacion").val($("#formulacion option:selected").text());
		}
	});

	$('#fformulador').change(function(){
		if($("#fformulador").val() != ""){
			$("#nombreFormulador").val($("#fformulador option:selected").text());
		}
	});

	$('#paisOrigen').change(function(){
		if($("#paisOrigen").val() != ""){
			$("#nombrePaisFabricante").val($("#paisOrigen option:selected").text());
		}
	});

	$('#ingredienteActivo').change(function(){
		if($("#ingredienteActivo").val() != ""){
			$("#nombreIngredienteActivo").val($("#ingredienteActivo option:selected").text());
			$('#ingredienteQuimico').val($("#ingredienteActivo option:selected").attr('data-ingrediente'));
		}
	});

	$('#uso').change(function(){
		if($("#uso").val() != ""){
			$("#nombreUso").val($("#uso option:selected").text());
		}
	});

	$('#aplicacionProducto').change(function(event){
		if($("#aplicacionProducto").val() != ""){
			$("#nombreAplicacionProducto").val($("#aplicacionProducto option:selected").text());
		}
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		$("#tipoProducto").attr("disabled","disabled");
		$("#subTipoProducto").attr("disabled","disabled");
	
	});

	$("#actualizarProducto").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreProducto").val())){
			error = true;
			$("#nombreProducto").addClass("alertaCombo");
		}
		
		if(!$.trim($("#fecha_registro").val())){
				error = true;
				$("#fecha_registro").addClass("alertaCombo");
			}

		/*if(!$.trim($("#partidaArancelaria").val()) || !esCampoValido("#partidaArancelaria")){
			error = true;
			$("#partidaArancelaria").addClass("alertaCombo");
		}

		if(!$.trim($("#unidadMedida").val())){
			error = true;
			$("#unidadMedida").addClass("alertaCombo");
		}

		if(valorSubtipoProducto.toUpperCase() == 'MATERIAS PRIMAS' || valorSubtipoProducto.toUpperCase() == 'INGREDIENTE ACTIVO GRADO TÉCNICO'){
			error = false;
		}else{
			if(!$.trim($("#numeroRegistro").val())){
				error = true;
				$("#numeroRegistro").addClass("alertaCombo");
			}

			if(!$.trim($("#fecha_registro").val())){
				error = true;
				$("#fecha_registro").addClass("alertaCombo");
			}

			if(!$.trim($("#txtClienteBusqueda").val())){
				error = true;
				$("#txtClienteBusqueda").addClass("alertaCombo");
			}

			if(!$.trim($("#formulacion").val())){
				error = true;
				$("#formulacion").addClass("alertaCombo");
			}

			if(!$.trim($("#observacion").val())){
				error = true;
				$("#observacion").addClass("alertaCombo");
			}
		}  */

		

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
			$("#actualizarProducto").attr('data-rutaAplicacion','registroProducto');
			$("#actualizarProducto").attr('data-opcion','abrirProductoMateriaPrima');
			$("#actualizarProducto").attr('data-destino','detalleItem');
			abrir($("#actualizarProducto"),event,false);
		}
		
     });

	 function esCampoValido(elemento){
			var patron = new RegExp($(elemento).attr("data-er"),"g");
			return patron.test($(elemento).val());
		}

	/*$('#documento').change(function(event){
		if($("#nombreProducto").val() != ""){
			subirArchivo('documento',$("#nombreProducto").val().replace(/ /g,''),'aplicaciones/registroProducto/producto', 'archivo');
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

	        if (extension[extension.length - 1].toUpperCase() == 'PDF' || $("#nombreProducto").val() != "") {

	            subirArchivo(
	                archivo
	                , ($("#nombreProducto").val()+$("#subTipoProducto").val()).replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-')
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo
	                , new carga(estado, archivo, boton)
	            );
	        } else {
	            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
	            archivo.val("");
	        }
	    });

	/*$("#txtClienteBusqueda").change(function(event){
		 if($("#txtClienteBusqueda").val() != ''){
			 $('#actualizarProducto').attr('data-opcion','accionesCliente');
			 $('#actualizarProducto').attr('data-destino','res_cliente');
			 //$('#opcion').val('cliente');
			 $('input[name=opcion]').val('clientePlaguicida');			 
			 abrir($("#actualizarProducto"),event,false); 
			 distribuirLineas();
			 $('#actualizarProducto').attr('data-opcion','modificarProducto');
			 $('#actualizarProducto').attr('data-destino','');
		}else{
			$("#txtClienteBusqueda").addClass("alertaCombo");
			$("#estado").html("Por ingrese un número de RUC o razón social.").addClass("alerta");
			}

		});*/
	
	$("#txtProductoBusqueda").change(function(event){
		 if($("#txtProductoBusqueda").val() != ''){
			 $('#nuevoUso').attr('data-opcion','accionesCliente');
			 $('#nuevoUso').attr('data-destino','res_producto');
			 $('input[name=opcion]').val('producto');
			 abrir($("#nuevoUso"),event,false); 
			 distribuirLineas();
			 $('#nuevoUso').attr('data-opcion','guardarNuevoUso');
			 $('#nuevoUso').attr('data-destino','');
		}else{
			$("#txtProductoBusqueda").addClass("alertaCombo");
			$("#estado").html("Por ingrese un producto.").addClass("alerta");
			}

		});
		
		$('#idDeclaracionVenta').change(function(event){
		if($("#idDeclaracionVenta").val() != "0"){
			$("#declaracionVenta").val($("#idDeclaracionVenta option:selected").text());
		}else{
			$("#declaracionVenta").val('');
		}
	});

    	$(".UsoEspecie").hide();
    	$(".UsoInstalacion").hide();
    	$(".UsoProducto").hide();
    	$("#res_producto").html('');    	
    	
    	$('#aplicado_a').change(function(event){
    		if($("#aplicado_a option:selected").val() == "Especie"){
    			$(".UsoEspecie").show();
    			$(".UsoInstalacion").hide();
    			$(".UsoProducto").hide();
    			$("#res_producto").html('');  
    
    			$("#especie").attr('required', 'required');
    			$("#instalacion").removeAttr('required');
    			
    		}else if($("#aplicado_a option:selected").val() == "Instalacion"){
    			$(".UsoEspecie").hide();
    			$(".UsoInstalacion").show();
    			$(".UsoProducto").hide();
    			$("#res_producto").html('');  
    
    			$("#especie").removeAttr('required');
    			$("#instalacion").attr('required', 'required');
    			
    		}else if($("#aplicado_a option:selected").val() == "Producto"){
    			$(".UsoEspecie").hide();
    			$(".UsoInstalacion").hide();
    			$(".UsoProducto").show();
    			$("#res_producto").html('');  
    
    			$("#especie").removeAttr('required');
    			$("#instalacion").removeAttr('required');
    			
    		}else{
    			$(".UsoEspecie").hide();
    			$(".UsoInstalacion").hide();
    			$(".UsoProducto").hide();
    			$("#res_producto").html('');  
    
    			$("#especie").removeAttr('required');
    			$("#instalacion").removeAttr('required');
    			$("#instalacion").removeAttr('required');
    		}
    	});
		
		$("#certificadoProductosInocuidad").submit(function(event){
		event.preventDefault();
		$("#estado").html("");

		$("#certificadoProductosInocuidad").attr('data-destino', 'detalleItem');
		$("#certificadoProductosInocuidad").attr('data-opcion', 'reporteImprimirCertificadoProductoVeterinarioFertilizante');

		$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();
		
		setTimeout(function(){    			
			var respuesta = JSON.parse(ejecutarJson($("#certificadoProductosInocuidad")).responseText);

			if(respuesta.estado === 'exito'){
    			$("#idProducto").val(respuesta.mensaje);
    			
    			if($("#idProducto").val() != ''){
    				
					$("#archivoSalida").val(rutaAcceso+respuesta.salidaReporte);

    				$.post("aplicaciones/mvc/FirmaDocumentos/Documentos/guardar",
    					{
    						archivo_entrada: $("#archivoSalida").val(),
    						archivo_salida: $("#archivoSalida").val(),
    						identificador: '1722773189',
    						razon_documento: 'Certificado de producto',
    						tabla_origen: 'g_catalogos.productos',
    						campo_origen: 'ruta_certificado',
    						id_origen: $("#idProducto").val(),
    						estado:'Atendida',
    						proceso_firmado: 'SI'
    					},
    				      	function (data) {
    				        	if (data.estado === 'EXITO') {
    				        		$("#certificadoProductosInocuidad").attr('data-rutaAplicacion','registroProducto');
    			    				$("#certificadoProductosInocuidad").attr('data-opcion','mostrarCertificadoPDF');
    			    				$("#certificadoProductosInocuidad").attr('data-destino','detalleItem');
    			    				abrir($("#certificadoProductosInocuidad"),event,false);
    			    				$("#estado").html("Se ha generado el certificado");
								}else{
									mostrarMensaje(data.mensaje,"FALLO");
									$("#cargarMensajeTemporal").html("");
								}
    				        }, 'json');
    			}else{
    				$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
    			}
			}else{
				$("#estado").html('No se ha podido generar el certificado.');
				$("#cargarMensajeTemporal").html("");
			}
		}, 1000);
	})

	/*$("#aplicacionArea").change(function(){
		stipoProducto ='0';
    	stipoProducto = '<option value="">Tipo producto...</option>';
	    for(var i=0;i<array_tipoListaProducto.length;i++){
		    if ($("#aplicacionArea").val()==array_tipoListaProducto[i]['idArea']){
		    	stipoProducto += '<option value="'+array_tipoListaProducto[i]['idTipoProducto']+'">'+array_tipoListaProducto[i]['nombre']+'</option>';
			    }
	   		}
	    $('#tipoProductoArea').html(stipoProducto);
	    $("#tipoProductoArea").removeAttr("disabled");
	});

	$("#tipoProductoArea").change(function(){
		ssubtipoProducto ='0';
		ssubtipoProducto = '<option value="">Subtipo producto...</option>';
	    for(var i=0;i<array_comboSubTipoProducto.length;i++){
		    if ($("#tipoProductoArea").val()==array_comboSubTipoProducto[i]['idTipoProducto']){
		    	ssubtipoProducto += '<option value="'+array_comboSubTipoProducto[i]['idSubtipoProducto']+'">'+array_comboSubTipoProducto[i]['nombre']+'</option>';
			    }
	   		}
	    $('#subTipoProductoArea').html(ssubtipoProducto);
	    $("#subTipoProductoArea").removeAttr("disabled");
	});

	$("#subTipoProductoArea").change(function(){
		sProducto ='0';
		sProducto = '<option value="">Producto...</option>';
	    for(var i=0;i<array_comboProducto.length;i++){
	   	    if ($("#subTipoProductoArea").val() == array_comboProducto[i]['idSubTipoProducto']){
		    	sProducto += '<option value="'+array_comboProducto[i]['idProducto']+'-'+array_comboProducto[i]['nombre']+'">'+array_comboProducto[i]['nombre']+'</option>';
			    }
	   		}
	    $('#productoArea').html(sProducto);
	    $("#productoArea").removeAttr("disabled");
	});*/

	</script>
</html>
