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
	$constg = new Constantes();
	$cop = new ControladorRegistroOperador();
	
	/*--- Prestaña I ---*/
	$tipoSubtipoProducto = pg_fetch_assoc($cc->obtenerTipoSubtipoXProductos($conexion, $idProducto));
	$tipoProducto = $cc->listarTipoProductosXarea($conexion, $areaProducto);
	$qSubtipoProducto = $cc->listarSubProductos($conexion);
	
	while($fila = pg_fetch_assoc($qSubtipoProducto)){
	    $subtipoProducto[]= array('idSubtipoProducto'=>$fila['id_subtipo_producto'], 'nombre'=>$fila['nombre'], 'idTipoProducto'=>$fila['id_tipo_producto']);
	}
	
	$producto = pg_fetch_assoc($cr->abrirProducto($conexion, $idProducto));
	$productoInocuidad = pg_fetch_assoc($cr->buscarProductoInocuidad($conexion,$idProducto));
	
	$qPartidas = $cr->listarPartidasArancelarias($conexion, $idProducto);
	
	if($productoInocuidad['id_operador'] != ''){
	    
	    //Tabla operadores
	    $qOperador = $cop->listarOperadoresEmpresa($conexion,$productoInocuidad['id_operador']);
				
	    if(pg_num_rows($qOperador) == 0){
		    $qOperador = $cc->listarEmpresa($conexion,$productoInocuidad['id_operador']);
		    $operador = pg_fetch_assoc($qOperador);
		}else{
		    $operador = pg_fetch_assoc($qOperador);
		}
	}
	
	/*--- Prestaña II ---*/
	$qIngredienteActivo = $cr->listarTipoIngredienteActivoXArea($conexion, 'IAP', 1000, 0);
	
	while($fila = pg_fetch_assoc($qIngredienteActivo)){
	    $ingredienteActivo[]= array('idIngredienteActivo'=>$fila['id_ingrediente_activo'], 'ingredienteActivo'=>$fila['ingrediente_activo']);
	}
	
	$qAditivo = $cc->listarAditivosXArea($conexion, 'IAP');
	
	while($fila = pg_fetch_assoc($qAditivo)){
	    $aditivo[]= array('idAditivo'=>$fila['id_aditivo_toxicologico'], 'nombre'=>$fila['nombre_comun']);
	}
	
	$unidades = $cc->listarUnidadesMedida($conexion);
	while($fila = pg_fetch_assoc($unidades)){
	    $unidad[]= array('identificador'=>$fila['id_unidad_medida'], 'codigo'=>$fila['codigo'], 'nombre'=>$fila['nombre'], 'tipo'=>$fila['tipo_unidad']);
	}
	
	$qComposicion = $cr->listarComposicionProductosInocuidad($conexion, $idProducto);
	
	/*--- Prestaña III ---*/
	
	$qfFormulador = $cr->listarFabricanteFormulador($conexion,$idProducto);
	
	/*--- Prestaña IV ---*/
	
	$qUso = $cr->listarUsoPlaguicida($conexion,$idProducto);
	
	$declaracionVenta = $cc->listarDeclaracionVenta($conexion);
	
	while($fila = pg_fetch_assoc($declaracionVenta)){
	    $declaracion[]= array('identificador'=>$fila['id_declaracion_venta'], 'nombre'=>$fila['declaracion_venta']);
	}
	/////
	
?>

<body>
	<header>
		<h1>Detalle de producto plaguicida</h1>
	</header>
	
	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="registroProducto" data-opcion="abrirSubtipoProductoPlaguicida" data-destino="detalleItem">
		<input type="hidden" name="idSubtipoProducto" value="<?php echo $producto['id_subtipo_producto'];?>"/>
		<input type="hidden" name="areaSubProducto" value="<?php echo $areaProducto;?>"/>
		<input type="hidden" name="numeroPestania" value="2"/>
		<button class="regresar">Regresar a Subtipo de Producto Plaguicida</button>
	</form>
	
	<!-- ///////////////////////////////////////////////////////// PESTAÑA I /////////////////////////////////////////////////////////////////////// -->	
	
	<div class="pestania" id="ParteI">	
			
		<form id="actualizarProducto" data-rutaAplicacion="registroProducto" data-opcion="modificarProductoPlaguicida" >
				
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
		 		
		 			<div data-linea="1">			
						<label>Tipo producto: </label> 
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
						<label>Subtipo producto: </label> 
						<select id="subTipoProducto" name="subTipoProducto" disabled="disabled" required>
						</select>
						<input type="hidden" id="subTipoInicial" name="subTipoInicial" value="<?php echo $tipoSubtipoProducto['id_subtipo_producto'];?>"/>
					</div>
					
					<div data-linea="3">
						<label for="nombreProducto">Nombre: </label>
						<input id="nombreProducto" name="nombreProducto" type="text" value="<?php echo $producto['nombre_comun'];?>" required="required" disabled="disabled"/>	
					</div>
					<div data-linea="3">
						<label for="numeroRegistro">Número Registro: </label>
						<input name="numeroRegistro" id="numeroRegistro" type="text" maxlength="256" value="<?php echo $productoInocuidad['numero_registro'];?>" required="required" disabled="disabled" />
					</div>
					<div id="div4" data-linea="4">
						<label>RUC: </label> 
						<input id="txtClienteBusqueda" name="txtClienteBusqueda"  type="text" maxlength="13" value="<?php echo $productoInocuidad['id_operador'];?>" required="required" disabled="disabled" />					
					</div>
					
					<div data-linea="4" id="res_cliente">
						<label>Razón social: </label>
						<input type="text" id="razonSocial" name="razonSocial" value= "<?php echo $operador['nombre_operador']?>" required="required" readonly="readonly" disabled="disabled"/>
						<input type="hidden" id="empresa" name="empresa" value= <?php echo $operador['identificador']?> />
					</div>	
					
					<div data-linea="5">
					<label>Fecha de registro: </label>
						<input type="text"	id="fecha_registro" name="fecha_registro" readonly="readonly" value="<?php echo date('j/n/Y',strtotime($productoInocuidad['fecha_registro']));?>" required="required" disabled="disabled" />
					</div>
					
					<div data-linea="5">
						<label>Estado</label>
							<select id="status" name="status" disabled="disabled" required>
								<option value="1">Vigente</option>
								<option value="2">Suspendido</option>
								<option value="3">Caducado</option>
								<option value="4">Cancelado</option>
							</select>
					</div>
					
					<div data-linea="6">
					<label id="lFechaModificacion">Fecha última modificación: </label>
						<?php if (($productoInocuidad['fecha_modificacion'])!= '') echo date('j/n/Y',strtotime($productoInocuidad['fecha_modificacion']));?>
						<!-- input type="text"	id="fecha_modificacion" name="fecha_modificacion"	value="< ?php if (($productoInocuidad['fecha_modificacion'])!= '') echo date('j/n/Y',strtotime($productoInocuidad['fecha_modificacion']));?>" readonly="readonly" disabled="disabled" /-->
					</div>
					
					<div data-linea="6">
					<label id="lFechaRevaluacion">Fecha de reevaluación: </label>
						<input type="text"	id="fecha_revaluacion" name="fecha_revaluacion"	value="<?php if (($productoInocuidad['fecha_revaluacion'])!= '') echo date('j/n/Y',strtotime($productoInocuidad['fecha_revaluacion']));?>" disabled="disabled" readonly="readonly"/>
					</div>
					
					<div data-linea="7">
						<label id="lDeclaracionVenta">Declaración de venta</label> 
							<select id="idDeclaracionVenta" name="idDeclaracionVenta" required disabled="disabled">
								<option value>Seleccione....</option>
								<?php 
									for($i=0;$i<count($declaracion);$i++)
										echo '<option value="' . $declaracion[$i]['identificador'] . '" >'. $declaracion[$i]['nombre'] .'</option>';
								?>
							</select>
							
							<input type="hidden" id="declaracionVenta" name="declaracionVenta" value="<?php echo $productoInocuidad['declaracion_venta'];?>"/>								
					</div>
			</fieldset>	
				
			<fieldset>
				<legend>Características</legend>
							
        			<div data-linea="8">
        				<label>Formulación: </label>
    					<select id="formulacion" name="formulacion" disabled="disabled" required>
    					 	<option value="" selected="selected">Formulación....</option>
    							<?php 
    								$formulaciones = $cr->listarFormulacion($conexion,$areaProducto);
    								
    								while ($fila = pg_fetch_assoc($formulaciones)) {
    									echo '<option value="' . $fila['id_formulacion'] . '" >'. $fila['formulacion'] .'</option>';
    							     }
    							?>
        				 </select>
        				 
        				 <input name="nombreFormulacion" id="nombreFormulacion" type="hidden" />
        			</div>
        			
					<div data-linea="9">
							<label>Categoría toxicológica: </label> 
							<select id="caToxicologica" name="caToxicologica" required disabled="disabled" >
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
					
					<div data-linea="10">
						<label for="periodoReingreso">Período de reingreso: </label>
						<input id="periodoReingreso" name="periodoReingreso" value="<?php echo $productoInocuidad['periodo_reingreso'];?>" disabled="disabled" />
					</div>
					
					<div data-linea="11">
						<label for="estabilidad">Estabilidad: </label>
						<input name="estabilidad" id="estabilidad" type="text" value="<?php echo $productoInocuidad['estabilidad'];?>" disabled="disabled" required="required"/>
					</div>
					
					<div data-linea="12">
						<label for="observacion">Observaciones: </label>
						<input name="observacion" id="observacion" type="text" maxlength="1000" value="<?php echo $productoInocuidad['observacion'];?>" disabled="disabled" />
					</div>
					
					<div data-linea="13">
						<label>Archivo adjunto</label> <?php echo ($producto['ruta']=='0'? '<span class="alerta">No hay ningún archivo adjunto</span>': $producto['ruta']==''? '<span class="alerta">No hay ningún archivo adjunto</span>' : '<a href='.$producto['ruta'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
					</div>
					
					<div data-linea="14">
						<label for="documento">Documento: </label>
						<input type="file" class="archivo" name="informe" accept="application/pdf" disabled="disabled"/>
						<input type="hidden" class="rutaArchivo" name="archivo" value="<?php echo $producto['ruta'];?>"/>
						<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
						<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/registroProducto/producto" >Subir archivo</button>						
					</div>
			</fieldset>					
		</form>	
		
		<form id="nuevaPartida" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevaPartidaPlaguicidas" >
			<input type="hidden" id="idProductoInocuidad" name="idProductoInocuidad" value="<?php echo $producto['id_producto'];?>">
			<input type="hidden" id="areaProducto" name="areaProducto" value="<?php echo $areaProducto;?>">
				
		 	<fieldset>
		   		<legend>Partida Arancelaria</legend>
									
    				<div data-linea="15">
    					<label>Partida: </label>									
    					<input type="text" id="partidaArancelaria" name="partidaArancelaria" required="required" />
    				</div>
    					
    				<div data-linea="16">
    					<button type="submit" class="mas">Añadir</button>
    				</div>
			</fieldset>
		</form>	
		
		<fieldset>
    		<legend>Partidas Arancelarias</legend>
    			<table id="partidasPlaguicidas">
    				<?php 
    				    while ($partidas = pg_fetch_assoc($qPartidas)){
    				        echo $cr->imprimirLineaPartidasArancelarias($partidas['id_partida_arancelaria'], $partidas['id_producto'], $partidas['partida_arancelaria'], $partidas['codigo_producto'], $partidas['estado'], 'registroProducto', $areaProducto);
    					}												
    				?>
    			</table>
		</fieldset>				
	</div>
				
				
	<!-- ///////////////////////////////////////////////////////// PESTAÑA II /////////////////////////////////////////////////////////////////////// -->			
				
	<div class="pestania" id="ParteII">	
		<form id="nuevaComposicion" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevaComposicionPlaguicida" >
			<input type="hidden" id="idProducto" name="idProducto" value="<?php echo $producto['id_producto'];?>">
			<input type="hidden" id="idAreaC" name="idAreaC" value="<?php echo $areaProducto;?>">
		 	
		 	<fieldset>
		   		<legend>Composición producto</legend>
									
        			<div data-linea="17">
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
        				
        			<div data-linea="18">
        				<label>Nombre: </label>
        				<select id="comboComposicion" name="comboComposicion" required>
        					<option value="">Seleccione....</option>
        				</select>
        				
        				<input type="hidden" id="nombreComposicion" name="nombreComposicion" />
        			</div>
        			
        			<div data-linea="19">
        				<label for="concentracion">Concentración</label>
        				<input type="text" id="concentracion" name="concentracion" required="required" />
        			</div>
        			
        			<div data-linea="20">
        					<label>Unidad</label>
        					<select id="unidadMedida" name="unidadMedida" required>
        						<option value="" selected="selected">Unidad....</option>
        						<?php 
        							for($i=0;$i<count($unidad);$i++)
        								echo '<option value="' . $unidad[$i]['codigo'] . '" >'. $unidad[$i]['nombre'] .'</option>';
        						?>
        					</select>
        			</div>
        			
        			<div data-linea="21">
        				<button type="submit" class="mas">Añadir</button>
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
	
	<!-- ///////////////////////////////////////////////////////// PESTAÑA III /////////////////////////////////////////////////////////////////////// -->	
	
	<div class="pestania" id="ParteIII">
		
		<form id="nuevofFormulador" data-rutaAplicacion="registroProducto" data-opcion="guardarFabricanteFormuladorPlaguicida" >
			<input type="hidden" id="idProducto" name="idProducto" value="<?php echo $producto['id_producto'];?>">
			<input type="hidden" id="area" name="area" value="<?php echo $areaProducto;?>">
							
			<fieldset>	
			  	<legend>Fabricante/formulador</legend>
			  	
			  		<div data-linea="22">
        				<label>Tipo: </label>									
        				<select id="tipoFabFor" name="tipoFabFor" required>
        					<option value="">Seleccione....</option>
        					<option value="Fabricante">Fabricante</option>
        					<option value="Formulador">Formulador</option>
        				</select>
        			</div>
        			
					<div data-linea="23">
						<label>Nombre: </label>
							<input type="text" name="nombreFabFor" id="nombreFabFor" required="required"/>
					</div>
										
					<div data-linea="24">
						<label>País origen: </label>
						<select id="pais" name="pais" required>
							<option value="">País....</option>
							<?php 
								$provincias = $cc->listarSitiosLocalizacion($conexion,'PAIS');
								
								foreach ($provincias as $provincia){
									echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
								}
							?>
						</select>
						
						<input type="hidden" name="nombrePais" id="nombrePais" />
					</div>
					
					<div data-linea="25">
						<button type="submit" class="mas">Añadir</button>
					</div>
					
			</fieldset>
		</form>
		
		<fieldset>
    	  	<legend>Fabricante/Formulador ingresado</legend>
    			<table id="productoFF">
    				<?php 
    					while ($fabForm = pg_fetch_assoc($qfFormulador)){
    					    echo $cr->imprimirFabricanteFormuladorPlaguicida($fabForm['id_fabricante_formulador'], $fabForm['id_producto'], $areaProducto, $fabForm['tipo'], $fabForm['nombre'], $fabForm['pais_origen'], $fabForm['estado'], 'registroProducto');
						}
					?>
    			</table>
		</fieldset>				
			
	</div>

	<!-- ///////////////////////////////////////////////////////// PESTAÑA IV /////////////////////////////////////////////////////////////////////// -->
				
	<div class="pestania" id="ParteIV">
		
		<form id="nuevoUso" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoUsoPlaguicida" >
			<input type="hidden" id="idProducto" name="idProducto" value="<?php echo $producto['id_producto'];?>">
								   
			<fieldset>
		   		<legend>Uso autorizado</legend>
					<div data-linea="26">
						<label>Cultivo Nombre Científico: </label>
						<select id="cultivo" name="cultivo" required>
							<option value="">Cultivo....</option>
								 <?php 
								    $cultivos = $cc->listarCultivosXArea($conexion,$areaProducto);
								    while ($cultivo = pg_fetch_assoc($cultivos)){
								        echo '<option value="' . $cultivo['id_cultivo'] . '" data-nombre_comun="'.$cultivo['nombre_comun_cultivo'].'">' . $cultivo['nombre_cientifico_cultivo'] . '</option>';
									}
								 ?>
						</select>
						
						<input type="hidden" name="nombreCientificoCultivo" id="nombreCientificoCultivo" />
					</div>
					
					<div data-linea="26">
						<label>Cultivo Nombre Común: </label>
						<input type="text" name="nombreCultivo" id="nombreCultivo" readonly="readonly" required="required"/>
					</div>	
					
					<div data-linea="27">
						<label>Plaga Nombre Científico: </label>
						<select id="plaga" name="plaga" required>
							<option value="">Plaga....</option>
								 <?php 
										$usos = $cr->listarUsosProductos($conexion,$areaProducto);
										while ($uso = pg_fetch_assoc($usos)){
											echo '<option value="' . $uso['id_uso'] . '" data-nombre_comun="'.$uso['nombre_comun_uso'].'">' . $uso['nombre_uso'] . '</option>';
										}
								 ?>
						</select>
						
						<input type="hidden" name="nombreCientificoPlaga" id="nombreCientificoPlaga" />
					</div>
					
					<div data-linea="27">
						<label>Plaga Nombre Común: </label>
						<input type="text" name="nombrePlaga" id="nombrePlaga" readonly="readonly" required="required"/>
					</div>
					
					<div data-linea="28">
						<label>Dosis: </label>
						<input type="text" name="dosis" id="dosis" required="required" />
					</div>
					
					<div data-linea="28">	
						<select id="unidadMedidaDosis" name="unidadMedidaDosis" required>
    						<option value="">Unidad....</option>
    						<?php 
    							for($i=0;$i<count($unidad);$i++)
    								echo '<option value="' . $unidad[$i]['codigo'] . '" >'. $unidad[$i]['codigo'] .'</option>';
    						?>
    					</select>
					</div>
					
					<div data-linea="29">
						<label>Período de carencia: </label>
						<input type="text" name="periodoCarencia" id="periodoCarencia" required="required" />
					</div>
					
					<div data-linea="30">
						<label>Gasto de agua: </label>
						<input type="text" name="gastoAgua" id="gastoAgua" />
					</div>
					
					<div data-linea="30">
						<select id="unidadMedidaAgua" name="unidadMedidaAgua" >
    						<option value="" selected="selected">Unidad....</option>
    						<?php 
    							for($i=0;$i<count($unidad);$i++)
    								echo '<option value="' . $unidad[$i]['codigo'] . '" >'. $unidad[$i]['codigo'] .'</option>';
    						?>
    					</select>
					</div>
					
					<div data-linea="31">
							<button type="submit" class="mas">Añadir uso</button>
					</div>
			</fieldset>
		</form>
		
		<fieldset>
    		<legend>Uso ingresado</legend>
    			<table>
    				<thead>
    					<tr>
    						<th colspan="2">Cultivo</th>
    					
    						<th colspan="2">Plaga</th>
    					
    						<th colspan="3"></th>
    						<th colspan="1"></th>
    					</tr>
    					<tr>
    						<th>Nombre común</th>
    						<th>Nombre científico</th>
    					
    						<th>Nombre común</th>
    						<th>Nombre científico</th>
    					
    						<th>Período de carencia</th>
    						<th>Gasto de agua</th>
    						<th>Dosis</th>
    						
    						<th></th>
    					</tr>
    				</thead>
    				
    				<tbody id="usoAutorizadoProducto">
    					<?php 
    						while ($usos = pg_fetch_assoc($qUso)){
    						    echo $cr->imprimirLineaUsoPlaguicida($usos['id_uso'], $usos['id_producto'], $usos['plaga_nombre_comun'], 
    						        $usos['plaga_nombre_cientifico'], $usos['cultivo_nombre_comun'], $usos['cultivo_nombre_cientifico'], 
    						        $usos['dosis'], $usos['unidad_dosis'], $usos['periodo_carencia'], $usos['gasto_agua'], 
    						        $usos['unidad_gasto_agua'], 'registroProducto');
    						}												
    					?>
					</tbody>
    			</table>
		</fieldset>
	</div>
			
	<div class="pestania" id="ParteV">	
	
    	<fieldset>
    		<legend>Generar archivos</legend>
    		
    		<div data-linea="32">
    			<form id='certificadoProductosInocuidad' data-rutaAplicacion='registroProducto' method="post">
        			<input type="hidden" id="idProducto" name="idProducto" value="<?php echo $producto['id_producto'];?>">
        			<input type="hidden" id="idArea" name="idArea" value="<?php echo $areaProducto;?>">
        			
        			<div id="cargarMensajeTemporal"></div>
        		
        			<div style="text-align: center;">
        				<button type="submit">Generar certificado de producto</button>
        			</div>
        		
        		</form>
    		</div>
    		
    		<div data-linea="32">
            	<form id='reportePlaguicidasVUE' action="aplicaciones/registroProducto/reporteImprimirExcelProductoPlaguicidaVUE.php" data-rutaAplicacion='registroProducto' target="_blank" method="post">
            		<input type="hidden" id="idProducto" name="idProducto" value="<?php echo $producto['id_producto'];?>">
            		
            		<div style="text-align: center;">
            			<button >Generar matriz VUE</button>
            		</div>
            		
            	</form>
        	</div>
        	
    	</fieldset>
	</div>	

<script type="text/javascript">
	var array_comboTipoProducto = <?php echo json_encode($opcionesTipoProducto);?>;
	var array_comboSubTipoProducto = <?php echo json_encode($subtipoProducto);?>;
	var valorSubtipoProducto = <?php echo json_encode($tipoSubtipoProducto['nombre_subtipo']);?>;
	var array_comboIngredienteActivo = <?php echo json_encode($ingredienteActivo);?>;
	var array_comboAditivo = <?php echo json_encode($aditivo);?>;
	var rutaAcceso = <?php echo json_encode($constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/');?>;

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

		cargarValorDefecto("formulacion","<?php echo $productoInocuidad['id_formulacion'];?>");
		cargarValorDefecto("caToxicologica","<?php echo $productoInocuidad['id_categoria_toxicologica'];?>");
		cargarValorDefecto("declaracionVenta","<?php echo $productoInocuidad['declaracion_venta'];?>");
		//$('#periodoReingreso').val($("#caToxicologica option:selected").attr('data-periodo'));
		cargarValorDefecto("idDeclaracionVenta","<?php echo $productoInocuidad['id_declaracion_venta'];?>");
		cargarValorDefecto("status","<?php echo $producto['estado'];?>");

		$("#nombreFormulacion").val($("#formulacion option:selected").text());
		$("#nombreCategoria").val($("#caToxicologica option:selected").text());

		actualizarBotonesOrdenamiento();
		acciones("#nuevaPartida","#partidasPlaguicidas");
		acciones("#nuevaComposicion","#composicionInocuidad");
		acciones("#nuevofFormulador","#productoFF");
		acciones("#nuevoUso","#usoAutorizadoProducto")

		construirAnimacion($(".pestania"));
	    distribuirLineas();
		construirValidador();		   
 	});
	 
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#actualizarProducto").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreProducto").val())){
			error = true;
			$("#nombreProducto").addClass("alertaCombo");
		}

		if(!$.trim($("#numeroRegistro").val())){
			error = true;
			$("#numeroRegistro").addClass("alertaCombo");
		}

		if(!$.trim($("#fecha_registro").val())){
			error = true;
			$("#fecha_registro").addClass("alertaCombo");
		}

		if(!$.trim($("#declaracionVenta").val())){
			error = true;
			$("#declaracionVenta").addClass("alertaCombo");
		}

		if(!$.trim($("#formulacion").val())){
			error = true;
			$("#formulacion").addClass("alertaCombo");
		}
		
		if(!$.trim($("#txtClienteBusqueda").val())){
			error = true;
			$("#txtClienteBusqueda").addClass("alertaCombo");
		}

		if(!$.trim($("#razonSocial").val())){
			error = true;
			$("#txtClienteBusqueda").addClass("alertaCombo");
		}

		if(!$.trim($("#empresa").val())){
			error = true;
			$("#txtClienteBusqueda").addClass("alertaCombo");
		}

		if(!$.trim($("#caToxicologica").val())){
			error = true;
			$("#caToxicologica").addClass("alertaCombo");
		}

		if(!$.trim($("#idDeclaracionVenta").val())){
			error = true;
			$("#idDeclaracionVenta").addClass("alertaCombo");
		}

		if(!$.trim($("#estabilidad").val())){
			error = true;
			$("#estabilidad").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			var respuesta = JSON.parse(ejecutarJson($("#actualizarProducto")).responseText);

			if(respuesta.estado == 'exito'){

    			$("#actualizarProducto").attr('data-rutaAplicacion','registroProducto');
    			$("#actualizarProducto").attr('data-opcion','abrirProductoPlaguicida');
    			$("#actualizarProducto").attr('data-destino','detalleItem');
    			abrir($("#actualizarProducto"),event,false);

			}else{
				mostrarMensaje(respuesta.mensaje,"FALLO");
			}
		}
		
     });

	$('#idDeclaracionVenta').change(function(event){
    	if($("#idDeclaracionVenta").val() != "0"){
    		$("#declaracionVenta").val($("#idDeclaracionVenta option:selected").text());
    	}else{
    		$("#declaracionVenta").val('');
    	}
	});
	
	$("#fecha_registro").datepicker({
	    changeMonth: true,
	    changeYear: true
	  });
	  
	$("#fecha_revaluacion").datepicker({
	    changeMonth: true,
	    changeYear: true
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
		if($("#caToxicologica option:selected").val() != ''){
    		$("#nombreCategoria").val($("#caToxicologica option:selected").text());
    		//$('#periodoReingreso').val($("#caToxicologica option:selected").attr('data-periodo'));
		}else{
			$("#nombreCategoria").val("");
    		//$('#periodoReingreso').val("");
		}	   
	 });

	$('#formulacion').change(function(){
		if($("#formulacion option:selected").val() != ""){
			$("#nombreFormulacion").val($("#formulacion option:selected").text());
		}else{
			$("#nombreFormulacion").val("");
		}
	});

	$("#idTipoComponente").change(function(){	
		nombreComposicion = '<option value="">Seleccione....</option>';

		if($("#idTipoComponente option:selected").val() !== ""){
    		if($("#idTipoComponente option:selected").text() === "Ingrediente activo"){
        		for(var i=0; i<array_comboIngredienteActivo.length; i++){
        		    nombreComposicion += '<option value="'+array_comboIngredienteActivo[i]['idIngredienteActivo']+'">'+array_comboIngredienteActivo[i]['ingredienteActivo']+'</option>';
        	    }
        	    
        	    $('#comboComposicion').html(nombreComposicion);
        	    $('#comboComposicion').removeAttr('disabled');
    		}else{
    			for(var i=0; i<array_comboAditivo.length; i++){
        		    nombreComposicion += '<option value="'+array_comboAditivo[i]['idAditivo']+'">'+array_comboAditivo[i]['nombre']+'</option>';
        	    }
        	    
        	    $('#comboComposicion').html(nombreComposicion);
        	    $('#comboComposicion').removeAttr('disabled');
    		}
			
			$("#tipoComponente").val($("#idTipoComponente option:selected").text());
		}else{
			$('#comboComposicion').html('');
			$('#nombreComposicion').val('');
			$('#tipoComponente').val('');
		}
	});

	$('#comboComposicion').change(function(){
		if($("#comboComposicion option:selected").val() !== ""){
			$("#nombreComposicion").val($("#comboComposicion option:selected").text());
		}else{
			$("#nombreComposicion").val("");
		}
	});

	$('#pais').change(function(){
		if($("#pais option:selected").val() !== ""){
			$("#nombrePais").val($("#pais option:selected").text());
		}else{
			$("#nombrePais").val("");
		}
	});

	$('#cultivo').change(function(event){
		if($("#cultivo option:selected").val() !== ""){
			$("#nombreCultivo").val($("#cultivo option:selected").attr('data-nombre_comun'));
			$("#nombreCientificoCultivo").val($("#cultivo option:selected").text());
		}else{
			$("#nombreCultivo").val("");
			$("#nombreCientificoCultivo").val("");
		}
	});

	$('#plaga').change(function(event){
		if($("#plaga option:selected").val() !== ""){
			$("#nombrePlaga").val($("#plaga option:selected").attr('data-nombre_comun'));
			$("#nombreCientificoPlaga").val($("#plaga option:selected").text());
		}else{
			$("#nombrePlaga").val("");
			$("#nombreCientificoPlaga").val("");
		}
	});

	 function esCampoValido(elemento){
			var patron = new RegExp($(elemento).attr("data-er"),"g");
			return patron.test($(elemento).val());
		}

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

	 $("#certificadoProductosInocuidad").submit(function(event){
			event.preventDefault();
			$("#estado").html("");

			$("#certificadoProductosInocuidad").attr('data-destino', 'detalleItem');
			$("#certificadoProductosInocuidad").attr('data-opcion', 'reporteImprimirCertificadoProductoPlaguicida');

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
    				$("#estado").html(respuesta.mensaje);
    				$("#cargarMensajeTemporal").html("");
    			}
			}, 1000);
		})
		
	$('#idTipoComponente').change(function(){
		if($("#idTipoComponente").val() != ""){
			$("#tipoComponente").val($("#idTipoComponente option:selected").text());
		}else{
			$("#tipoComponente").val('');
		}
	});
		
	if($("#txtClienteBusqueda").val()==''){
			$("#res_cliente").hide();	
		}

	 $("#txtClienteBusqueda").change(function(event){
		 if($("#txtClienteBusqueda").val() != ''){
			 $('#actualizarProducto').attr('data-opcion','accionesCliente');
			 $('#actualizarProducto').attr('data-destino','res_cliente');
			 $('input[name=opcion]').val('clientePlaguicida');		 
			 abrir($("#actualizarProducto"),event,false); 
			 $('#actualizarProducto').attr('data-opcion','modificarProductoPlaguicida');
			 $('#actualizarProducto').attr('data-destino','');
			 distribuirLineas();
		}else{
			$("#txtClienteBusqueda").addClass("alertaCombo");
			$("#estado").html("Por ingrese un número de RUC o razón social.").addClass("alerta");
		}

	});
</script>