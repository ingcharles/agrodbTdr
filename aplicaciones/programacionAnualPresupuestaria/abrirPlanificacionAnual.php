<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	require_once '../../clases/ControladorEstructuraFuncionarios.php';
	
	$conexion = new Conexion();
	$ca = new ControladorAreas();
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	$cpp = new ControladorProgramacionPresupuestaria();
	$cef = new ControladorEstructuraFuncionarios();
		
	$fecha = getdate();
	$anio = $fecha['year'];
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		$idAreaFuncionario = $_SESSION['idArea'];
		$nombreProvinciaFuncionario = $_SESSION['nombreProvincia'];
	}//$usuario=0;
	
	$idProgramacionAnual = $_POST['id'];
	
	$programacionAnual = pg_fetch_assoc($cpp->abrirProgramacionAnual($conexion, $idProgramacionAnual, $identificador));
	
	$estadoProgramacionAnual = $programacionAnual['estado'];
	$parametros = pg_fetch_assoc($cpp->abrirParametros($conexion, $anio));
	$procesoProyecto = pg_fetch_assoc($cpp->abrirProcesoProyecto($conexion, $programacionAnual['id_proceso_proyecto']));
	$componente = pg_fetch_assoc($cpp->abrirComponente($conexion, $programacionAnual['id_componente']));
	$actividad = pg_fetch_assoc($cpp->abrirActividad($conexion, $programacionAnual['id_actividad']));
	$codigoActividad = pg_fetch_assoc($cpp->abrirCodigoActividad($conexion, $actividad['id_codigo_actividad']));
	
	$responsable = $cef->obtenerUsuariosEstructuraXUnidadPrincial($conexion, $programacionAnual['id_area_n2']);
	
	$presupuesto = $cpp->listarPresupuestos($conexion, $idProgramacionAnual, $anio);
	
	$total = pg_fetch_assoc($cpp->numeroPresupuestosYCostoTotalIVA($conexion, $idProgramacionAnual));
	?>
	
	<header>
		<h1>Planificación Anual</h1>
	</header>
	
	<div id="estado1"></div>
	
	<div id="estado"></div>
	
	<div class="pestania">
		<div id="informacion">
			<fieldset>
				<legend>Planificación Anual</legend>
				
				<div data-linea="1">
					<label>Objetivo Estratégico:</label>
					<?php echo $programacionAnual['objetivo_estrategico'];?>
				</div>
				
				<div data-linea="2">
					<label>N2 - Coordinacion/Dirección:</label>
					<?php echo $programacionAnual['area_n2'];?>
				</div>
				
				<div data-linea="3">
					<label>Objetivo Específico:</label>
					<?php echo $programacionAnual['objetivo_especifico'];?>
				</div>
				
				<div data-linea="4">
					<label>N4 - Dirección/Dirección Distrital:</label>
					<?php echo $programacionAnual['area_n4'];?>
				</div>
				
				<div data-linea="5">
					<label>Objetivo Operativo:</label>
					<?php echo $programacionAnual['objetivo_operativo'];?>
				</div>
				
				<div data-linea="6">
					<label>Gestión/Unidad:</label>
					<?php echo $programacionAnual['gestion'];?>
				</div>
				
				<div data-linea="7">
					<label>Tipo:</label>
					<?php echo $programacionAnual['tipo'];?>
				</div>
				
				<div data-linea="8">
					<label>Proceso/Proyecto:</label>
					<?php echo $programacionAnual['proceso_proyecto'];?>
				</div>
				
				<div data-linea="9">
					<label>Producto Final:</label>
					<?php echo $programacionAnual['producto_final'];?>
				</div>
				
				<div data-linea="10">
					<label>Componente:</label>
					<?php echo $programacionAnual['componente'];?>
				</div>
				
				<div data-linea="11">
					<label>Actividad:</label>
					<?php echo $programacionAnual['actividad'];?>
				</div>
				
				<div data-linea="12">
					<label>Provincia:</label>
					<?php echo $programacionAnual['provincia'];?>
				</div>
				
				<div data-linea="13">
					<label>Cantidad de Usuarios:</label>
					<?php echo $programacionAnual['cantidad_usuarios'];?>
				</div>
				
				<div data-linea="13">
					<label>Población Objetivo:</label>
					<?php echo $programacionAnual['poblacion_objetivo'];?>
				</div>
				
				<div data-linea="14">
					<label>Medio de Verificación:</label>
					<?php echo $programacionAnual['medio_verificacion'];?>
				</div>
				
				<div data-linea="15">
					<label>Responsable:</label>
					<?php echo $programacionAnual['nombre_responsable'];?>
				</div>
				
				<div data-linea="16">
					<label>Monto Solicitado:</label>
					<?php echo number_format($total['total'], 2, ',', ' ') .' USD';?>
				</div>
				
				<div data-linea="17" id="observacionRevisionPA">
					<label>Observaciones del Revisor:</label> <?php echo $programacionAnual['observaciones_revision'];?>
				</div>
				<br />
				<div data-linea="18" id="observacionAprobacionPA">
					<label>Observaciones del Aprobador:</label> <?php echo $programacionAnual['observaciones_aprobacion'];?>
				</div>
				
				<div data-linea="19">
					<button id="modificar" type="button" class="editar">Editar</button>
				</div>
		
			</fieldset>
			
		</div>
		<div id="actualizacion">
			<form id="modificarPlanificacionAnual" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarPlanificacionAnual" data-destino="detalleItem">
				<input type='hidden' id='idPlanificacionAnual' name='idPlanificacionAnual' value="<?php echo $idProgramacionAnual;?>" />
				
				<fieldset>
					<legend>Planificación Anual</legend>
					
					<div data-linea="1">
						<label>Objetivo Estratégico:</label>
						<?php echo $programacionAnual['objetivo_estrategico'];?>
					</div>
					
					<div data-linea="2">
						<label>N2 - Coordinacion/Dirección:</label>
						<?php echo $programacionAnual['area_n2'];?>
					</div>
					
					<div data-linea="3">
						<label>Objetivo Específico:</label>
						<?php echo $programacionAnual['objetivo_especifico'];?>
					</div>
					
					<div data-linea="4">
						<label>N4 - Dirección/Dirección Distrital:</label>
						<?php echo $programacionAnual['area_n4'];?>
					</div>
					
					<div data-linea="5">
						<label>Objetivo Operativo:</label>
						<?php echo $programacionAnual['objetivo_operativo'];?>
					</div>
					
					<div data-linea="6">
						<label>Gestión/Unidad:</label>
						<?php echo $programacionAnual['gestion'];?>
					</div>
					
					<div data-linea="7">
						<label>Tipo:</label>
						<?php echo $programacionAnual['tipo'];?>
					</div>
					
					<div data-linea="8">
						<label>Proceso/Proyecto:</label>
						<?php echo $programacionAnual['proceso_proyecto'];?>
					</div>
					
					<div data-linea="9">
						<label>Producto Final:</label>
						<?php echo $programacionAnual['producto_final'];?>
					</div>
					
					<div data-linea="10">
						<label>Componente:</label>
						<?php echo $programacionAnual['componente'];?>
					</div>
					
					<div data-linea="11">
						<label>Actividad:</label>
						<?php echo $programacionAnual['actividad'];?>
					</div>
				
					<div data-linea="12">			
						<label  id="lProvincia">Provincia:</label>
							<select id="provincia" name="provincia" required="required">
								<option value="">Provincia....</option>
									<?php 	
										$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
										foreach ($provincias as $provincia){
											if($provincia['nombre'] == $_SESSION['nombreProvincia']){
												echo '<option value="' . $provincia['codigo'] . '" selected>' . $provincia['nombre'] . '</option>';
												$idProvincia = $provincia['codigo'];
												$nombreProvincia = $provincia['nombre'];
											}else{
												echo '<option value="' . $provincia['codigo'] . '" >' . $provincia['nombre'] . '</option>';
											}
										}
									?>
							</select> 
						
							<input type="hidden" id="idProvincia" name="idProvincia" value="<?php echo $idProvincia;?>"/>
							<input type="hidden" id="nombreProvincia" name="nombreProvincia" value="<?php echo $nombreProvincia;?>"/>
					</div>
					
					<div data-linea="13">			
						<label  id="lCantidadUsuarios">Cantidad de Usuarios:</label>
							<input type="text" id="cantidadUsuarios" name="cantidadUsuarios" maxlength="4" data-er="^[0-9]+$" value="<?php echo $programacionAnual['cantidad_usuarios'];?>"/>
					</div>
					
					<div data-linea="13">			
						<label  id="lPoblacionObjetivo">Población Objetivo:</label>
							<input type="text" id="poblacionObjetivo" name="poblacionObjetivo" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $programacionAnual['poblacion_objetivo'];?>"/>
					</div>
					
					<div data-linea="14">			
						<label  id="lMedioVerificacion">Medio de Verificación:</label>
							<input type="text" id="medioVerificacion" name="medioVerificacion" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $programacionAnual['medio_verificacion'];?>"/>
					</div>
				
					<div data-linea="15">			
						<label id="lResponsable">Responsable:</label>
						<select id="responsable" name="responsable" required>
							<option value="">Seleccione....</option>
								<?php 
									while($fila = pg_fetch_assoc($responsable)){
										if($fila['identificador'] == $programacionAnual['identificador_responsable']){
											echo '<option value="' . $fila['identificador'] . '" selected="selected">' . strtoupper($fila['apellido']) .' '. strtoupper($fila['nombre']).' </option>';
										}else{
											echo '<option value="' . $fila['identificador'] . '" >' . strtoupper($fila['apellido']) .' '. strtoupper($fila['nombre']).' </option>';
										}
									}
									?>
						</select>
			
						<input type="hidden" id="idResponsable" name="idResponsable" value="<?php echo $programacionAnual['identificador_responsable'];?>"/>
						<input type="hidden" id="nombreResponsable" name="nombreResponsable" value="<?php echo $programacionAnual['nombre_responsable'];?>"/>
					</div>
			
				</fieldset>
			
				<div data-linea="16">
					<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
				</div>
			</form>
		
		</div>
	</div>
	
	<div class="pestania">
		<form id="nuevoPresupuesto" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarPresupuesto" data-destino="detalleItem">
			<input type='hidden' id='idPlanificacionAnual' name='idPlanificacionAnual' value="<?php echo $idProgramacionAnual;?>" />
			<input type='hidden' id='idActividad' name='idActividad' value="<?php echo $actividad['id_actividad'];?>" />
			<input type='hidden' id='codActividad' name='codActividad' value="<?php echo $actividad['codigo_actividad'];?>" />
			<input type='hidden' id='nombreActividad' name='nombreActividad' value="<?php echo $actividad['nombre'];?>" />
			<input type='hidden' id='tipoPresupuesto' name='tipoPresupuesto' value="<?php echo $procesoProyecto['financiamiento'];?>" />
			<input type='hidden' id='opcion' name='opcion' />
			
			<fieldset>
				<legend>Presupuestos</legend>
				
				<div data-linea="1">
					<label>Ejercicio:</label><?php echo $parametros['ejercicio'];?>
					<input type='hidden' id='ejercicio' name='ejercicio' value="<?php echo $parametros['ejercicio'];?>"/>
				</div>
				
				<div data-linea="1">
					<label>Entidad:</label><?php echo $parametros['entidad'];?>
					<input type='hidden' id='entidad' name='entidad' value="<?php echo $parametros['entidad'];?>" readonly="readonly"/>
				</div>
				
				<div data-linea="2">
					<label>Unidad Ejecutora:</label> <!--revisar sesion usuario x provincia o planta central-->
					<select id=unidadEjecutora name="unidadEjecutora" required="required" >
						<option value="">Seleccione....</option>
						<?php 
							$unidadEjecutora = $cpp->listarUnidadEjeDesXTipo($conexion, 'ejecutora');
							
							while($fila = pg_fetch_assoc($unidadEjecutora)){
								echo '<option value="' . $fila['id_unidad_ejedes'] . '" data-codigo="' . $fila['codigo'] . '" >' . $fila['nombre'].' </option>';
							}
						?>
					</select>
					
					<input type='hidden' id='idUnidadEjecutora' name='idUnidadEjecutora' />
					<input type='hidden' id='nombreUnidadEjecutora' name='nombreUnidadEjecutora' />
					<input type='hidden' id='codigoUnidadEjecutora' name='codigoUnidadEjecutora' />
				</div>
				
				<div data-linea="2">
					<label>Unidad Desconcentrada:</label> <!--revisar sesion usuario x provincia o planta central-->
					<select id=unidadDesconcentrada name="unidadDesconcentrada" required="required" >
						<option value="">Seleccione....</option>
						<?php 
							$unidadDesconcentrada = $cpp->listarUnidadEjeDesXTipo($conexion, 'desconcentrada');
							
							while($fila = pg_fetch_assoc($unidadDesconcentrada)){
								echo '<option value="' . $fila['id_unidad_ejedes'] . '" >' . $fila['nombre'].' </option>';
							}
						?>
					</select>
					
					<input type='hidden' id='idUnidadDesconcentrada' name='idUnidadDesconcentrada' />
					<input type='hidden' id='nombreUnidadDesconcentrada' name='nombreUnidadDesconcentrada' />
					<input type='hidden' id='codigoUnidadDesconcentrada' name='codigoUnidadDesconcentrada' />
				</div>
				
				<div data-linea="3">
					<label>Programa:</label> <?php echo $procesoProyecto['codigo_programa'];?>
					<input type='hidden' id='programa' name='programa' value="<?php echo $procesoProyecto['codigo_programa'];?>"/>
				</div>
				
				<div data-linea="3">
					<label>Subprograma:</label> <?php echo $parametros['subprograma'];?>
					<input type='hidden' id='subprograma' name='subprograma' value="<?php echo $parametros['subprograma'];?>"/>
				</div>
				
				<div data-linea="4">
					<label>Proyecto:</label> <?php echo $componente['codigo_proyecto'];?>
					<input type='hidden' id='codigoProyecto' name='codigoProyecto' value="<?php echo $componente['codigo_proyecto'];?>" />
				</div>
				
				<div data-linea="4">
					<label>Actividad:</label> <?php echo $actividad['codigo_actividad'];?>
					<input type='hidden' id='codigoActividad' name='codigoActividad' value="<?php echo $actividad['codigo_actividad'];?>"/>
				</div>
				
				<div data-linea="5">
					<label>Obra:</label> <?php echo $parametros['obra'];?>
					<input type='hidden' id='obra' name='obra' value="<?php echo $parametros['obra'];?>"/>
				</div>
				
				<div data-linea="5">
					<label>Geográfico:</label> <?php echo $codigoActividad['geografico_canton'];?>
					<input type='hidden' id='geografico' name='geografico' value="<?php echo $codigoActividad['geografico_canton'];?>"/>
				</div>
				
				<div data-linea="6">
					<label>Renglón:</label>
					<select id=renglon name="renglon" required="required" >
						<option value="">Seleccione....</option>
						<?php 
							$renglon = $cpp->listarRenglon($conexion);
							
							while($fila = pg_fetch_assoc($renglon)){
								echo '<option value="' . $fila['id_renglon'] . '" data-codigo="' . $fila['codigo'] . '" >' . $fila['nombre'] .' - '. $fila['codigo'].' </option>';
							}
						?>
					</select>
					
					<input type='hidden' id='idRenglon' name='idRenglon' />
					<input type='hidden' id='nombreRenglon' name='nombreRenglon' />
					<input type='hidden' id='codigoRenglon' name='codigoRenglon' />
				</div>
				
				<div data-linea="6">
					<label>Renglón Auxiliar:</label> <?php echo $parametros['renglon_auxiliar'];?>
					<input type='hidden' id='renglonAuxiliar' name='renglonAuxiliar' value="<?php echo $parametros['renglon_auxiliar'];?>"/>
				</div>
		
				<div data-linea="7">
					<label>Fuente:</label> <?php echo $parametros['fuente'];?>
					<input type='hidden' id='fuente' name='fuente' value="<?php echo $parametros['fuente'];?>"/>
				</div>
				
				<div data-linea="7">
					<label>Organismo:</label> <?php echo $parametros['organismo'];?>
					<input type='hidden' id='organismo' name='organismo' value="<?php echo $parametros['organismo'];?>"/>
				</div>
				
				<div data-linea="8">
					<label>Correlativo:</label> <?php echo $parametros['correlativo'];?>
					<input type='hidden' id='correlativo' name='correlativo' value="<?php echo $parametros['correlativo'];?>"/>
				</div>
				
				<div data-linea="8">
					<label>CPC:</label>
					<select id=cpc name="cpc" required="required" >
						<option value="">Seleccione....</option>
						<?php 
							$cpc = $cpp->listarCPC($conexion);
							
							while($fila = pg_fetch_assoc($cpc)){
								echo '<option value="' . $fila['id_cpc'] . '" data-codigo="' . $fila['codigo'] . '">' . $fila['nombre'] .' - '. $fila['codigo']. ' </option>';
							}
						?>
					</select>
					
					<input type='hidden' id='idCPC' name='idCPC' />
					<input type='hidden' id='nombreCPC' name='nombreCPC' />
					<input type='hidden' id='codigoCPC' name='codigoCPC' />
				</div>
				
				<div data-linea="9">
					<label>Tipo de Compra:</label>
					<select id=tipoCompra name="tipoCompra" required="required" >
						<option value="">Seleccione....</option>
						<?php 
							$tipoCompra = $cpp->listarTipoCompra($conexion);
							
							while($fila = pg_fetch_assoc($tipoCompra)){
								echo '<option value="' . $fila['id_tipo_compra'] . '" >' . $fila['nombre'].' </option>';
							}
						?>
					</select>
					
					<input type='hidden' id='idTipoCompra' name='idTipoCompra' />
					<input type='hidden' id='nombreTipoCompra' name='nombreTipoCompra' />
				</div>
				
				<div data-linea="9">
					<div id="dProcedimientoSugerido"></div>
				</div>
				
				<div data-linea="10">
					<label>Detalle Actividad:</label> <?php echo $programacionAnual['actividad'];?>
					<input type='hidden' id='detalleActividad' name='detalleActividad' value="<?php echo $programacionAnual['actividad'];?>"/>
				</div>
				
				<div data-linea="11">
					<label>Detalle del Gasto:</label> 
					<input type='text' id='detalleGasto' name='detalleGasto' maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü ]+$" />
				</div>
				
				<div data-linea="12">
					<label>Cantidad Anual:</label> 1
					<input type='hidden' id='cantidadAnual' name='cantidadAnual' maxlength="1" data-er="^[0-9]+$" value="1" />
				</div>
				
				<div data-linea="12">
					<label>Unidad de Medida:</label>
					<select id=unidadMedida name="unidadMedida" required="required" >
						<option value="">Seleccione....</option>
						<?php 
							$unidadMedida = $cpp->listarUnidadesMedidaSercop($conexion);
							
							while($fila = pg_fetch_assoc($unidadMedida)){
								echo '<option value="' . $fila['id_unidad_medida'] . '" >' . $fila['nombre'].' </option>';
							}
							
							//costo data-er="^[0-9.]+$" 
						?>
					</select>
					
					<input type='hidden' id='idUnidadMedida' name='idUnidadMedida' />
					<input type='hidden' id='nombreUnidadMedida' name='nombreUnidadMedida' />
				</div>
				
				<div data-linea="13">
					<label>Costo (sin IVA):</label> 
					<input type='text' id='costo' name='costo' maxlength="15" data-er="^[0-9.]+$"/>
				</div>
				
				<div data-linea="13">
					<label>IVA:</label>
					<select id=iva name="iva" required="required" >
						<option value="">Seleccione....</option>
						<option value="<?php echo $parametros['iva'];?>"><?php echo $parametros['iva'];?>%</option>
						<option value="0">0%</option>
					</select>
				</div>
				
				<div data-linea="14">
					<label>Cuatrimestre:</label>
					<select id=cuatrimestre name="cuatrimestre" required="required" >
						<option value="">Seleccione....</option>
						<option value="Cuatrimestre I">Cuatrimestre I</option>
						<option value="Cuatrimestre II">Cuatrimestre II</option>
						<option value="Cuatrimestre III">Cuatrimestre III</option>						
					</select>
					
					<input type='hidden' id='idCuatrimestre' name='idCuatrimestre' />
					<input type='hidden' id='nombreCuatrimestre' name='nombreCuatrimestre' />
				</div>
				
				<div data-linea="15">
					<label>Tipo de Producto:</label>
					<select id=tipoProducto name="tipoProducto" required="required" >
						<option value="">Seleccione....</option>
						<option value="Normalizado">Normalizado</option>
						<option value="No Normalizado">No Normalizado</option>		
						<option value="No Aplica">No Aplica</option>			
					</select>
					
					<input type='hidden' id='idTipoProducto' name='idTipoProducto' />
					<input type='hidden' id='nombreTipoProducto' name='nombreTipoProducto' />
				</div>
				
				<div data-linea="15">
					<label>Catálogo Electrónico:</label>
					<select id=catalogoElectronico name="catalogoElectronico" required="required" >
						<option value="">Seleccione....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>					
					</select>
					
					<input type='hidden' id='idCatalogoElectronico' name='idCatalogoElectronico' />
					<input type='hidden' id='nombreCatalogoElectronico' name='nombreCatalogoElectronico' />
				</div>
				
				<div data-linea="16">
					<label>Fondos BID:</label>
					<select id=fondosBID name="fondosBID" required="required" >
						<option value="">Seleccione....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>					
					</select>
					
					<input type='hidden' id='idFondosBID' name='idFondosBID' />
					<input type='hidden' id='nombreFondosBID' name='nombreFondosBID' />
				</div>
				
				<div data-linea="16">
					<label>Operación BID:</label>
					<select id=operacionBID name="operacionBID" required="required" >
						<option value="">Seleccione....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>						
					</select>
					
					<input type='hidden' id='idOperacionBID' name='idOperacionBID' />
					<input type='hidden' id='nombreOperacionBID' name='nombreOperacionBID' />
				</div>
				
				<div data-linea="17">
					<label>Proyecto BID:</label>
					<select id=proyectoBID name="proyectoBID" required="required" >
						<option value="">Seleccione....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>						
					</select>
					
					<input type='hidden' id='idProyectoBID' name='idProyectoBID' />
					<input type='hidden' id='nombreProyectoBID' name='nombreProyectoBID' />
				</div>
				
				<div data-linea="17">
					<label>Tipo de Régimen:</label>
					<select id=tipoRegimen name="tipoRegimen" required="required" >
						<option value="">Seleccione....</option>
						<option value="Comun">Común</option>
						<option value="Especial">Especial</option>
						<option value="No Aplica">No Aplica</option>						
					</select>
					
					<input type='hidden' id='idTipoRegimen' name='idTipoRegimen' />
					<input type='hidden' id='nombreTipoRegimen' name='nombreTipoRegimen' />
				</div>
				
				<div data-linea="18">
					<label>Presupuesto:</label> <?php echo $procesoProyecto['financiamiento'];?>
					<input type='hidden' id='presupuesto' name='presupuesto' value="<?php echo $procesoProyecto['financiamiento'];?>"/>
				</div>
					
				<div data-linea="18">
					<label>Agregar al PAC:</label>
					<select id=agregarPac name="agregarPac" required="required" >
						<option value="">Seleccione....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select>
				</div>	
								
				<div>
					<button type="submit" class="mas">Agregar</button>		
				</div>
				
			</fieldset>
		</form>
		
		<fieldset>
			<legend>Presupuestos Registrados</legend>
			<table id="detallePresupuestos">
				<thead>
					<tr>
					    <th width="15%">Actividad</th>
						<th width="15%">Detalle del Gasto</th>
						<th width="10%">Renglo</th>
						<th width="10%">Costo</th>
						<th width="10%">Cuatrimestre</th>
						<th width="10%">Revisado</th>
						<th width="10%">Abrir</th>
						<th width="10%">Eliminar</th>
					</tr>
				</thead>
				<?php 
					while ($presupuestos = pg_fetch_assoc($presupuesto)){
						echo $cpp->imprimirLineaPresupuesto($presupuestos['id_presupuesto'], $presupuestos['nombre_actividad'], $presupuestos['detalle_gasto'], 
															$presupuestos['renglon'], $presupuestos['costo_iva'], $presupuestos['cantidad_anual'], $presupuestos['cuatrimestre'], 
															$presupuestos['id_planificacion_anual'], 'programacionAnualPresupuestaria', $presupuestos['revisado'], $presupuestos['estado']);
					}
				?>
			</table>
		</fieldset>
		
	</div>
	
	<script type="text/javascript">
	var usuario = <?php echo json_encode($usuario); ?>;
	var estadoProgramacionAnual = <?php echo json_encode($estadoProgramacionAnual); ?>;
	
		$("document").ready(function(){
			construirValidador();
			distribuirLineas();
			construirAnimacion($(".pestania"));
			//$('.bsig').attr("disabled","disabled");
			
			//$("#informacion").hide();
			$("#actualizacion").hide();
			$("#observacionRevisionPA").hide();
			$("#observacionAprobacionPA").hide();
			
			acciones("#nuevoPresupuesto","#detallePresupuestos");
			
			if(usuario == '0'){
				$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
				$("#botonGuardar").attr("disabled", "disabled");
			}

			if((estadoProgramacionAnual == 'creado') || (estadoProgramacionAnual == 'rechazado')){
				$("#modificar").show();
				$("#nuevoPresupuesto").show();
			}else{
				$("#modificar").hide();
				$("#nuevoPresupuesto").hide();
			}

			if(estadoProgramacionAnual == 'rechazado'){
				$("#observacionRevisionPA").show();
				$("#observacionAprobacionPA").show();
			}
		});

		$("#responsable").change(function(event){
			$("#idResponsable").val($("#responsable option:selected").val());
			$("#nombreResponsable").val($("#responsable option:selected").text());
		 });

		$("#modificar").click(function(){
			$("input").removeAttr("disabled");
			$("select").removeAttr("disabled");
			$("#actualizar").removeAttr("disabled");
			$(this).attr("disabled","disabled");
			$("#informacion").hide();
			$("#actualizacion").show();
		});

		function esCampoValido(elemento){
			var patron = new RegExp($(elemento).attr("data-er"),"g");
			return patron.test($(elemento).val());
		}

	
		$("#modificarPlanificacionAnual").submit(function(event){
	
			$("#modificarPlanificacionAnual").attr('data-opcion', 'modificarPlanificacionAnual');
		    $("#modificarPlanificacionAnual").attr('data-destino', 'detalleItem');
	
			event.preventDefault();
	
			$(".alertaCombo").removeClass("alertaCombo");
			var error = false;
	
			if(!$.trim($("#provincia").val())){
				error = true;
				$("#provincia").addClass("alertaCombo");
			}
	
			if(!$.trim($("#cantidadUsuarios").val()) || !esCampoValido("#cantidadUsuarios")){
				error = true;
				$("#cantidadUsuarios").addClass("alertaCombo");
			}
	
			if(!$.trim($("#poblacionObjetivo").val()) || !esCampoValido("#poblacionObjetivo")){
				error = true;
				$("#poblacionObjetivo").addClass("alertaCombo");
			}
	
			if(!$.trim($("#medioVerificacion").val()) || !esCampoValido("#medioVerificacion")){
				error = true;
				$("#medioVerificacion").addClass("alertaCombo");
			}
	
			if(!$.trim($("#responsable").val())){
				error = true;
				$("#responsable").addClass("alertaCombo");
			}
			
			if (error){
				$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				ejecutarJson($(this));
			}
		});
	
		$("#objetivoEstrategico").change(function (event) {
			$("#lAreaN2").show();
			$("#areaN2").show();
		});
		
		$("#areaN2").change(function (event) {
			$("#idObjetivoEstrategico").val($("#objetivoEstrategico option:selected").val());
			$("#nombreObjetivoEstrategico").val($("#objetivoEstrategico option:selected").text());
			$("#nombreAreaN2").val($("#areaN2 option:selected").text());
	
			$("#nuevaPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
		    $("#nuevaPlanificacionAnual").attr('data-destino', 'dObjetivoEspecifico');
		    $("#opcion").val('objetivoEspecifico');
	
		    abrir($("#nuevaPlanificacionAnual"), event, false); //Se ejecuta ajax
		});
	
		$("#provincia").change(function(){
			$('#idProvincia').val($("#provincia option:selected").val());
			$('#nombreProvincia').val($("#provincia option:selected").text());
		});

		/*Presupuestos*/

		$("#unidadEjecutora").change(function(){
			$('#idUnidadEjecutora').val($("#unidadEjecutora option:selected").val());
			$('#nombreUnidadEjecutora').val($("#unidadEjecutora option:selected").text());
			$('#codigoUnidadEjecutora').val($("#unidadEjecutora option:selected").attr('data-codigo'));

			$('#cantidadAnual1').val(1);
			$('#cantidadAnual').val(1);
		});

		$("#unidadDesconcentrada").change(function(){
			$('#idUnidadDesconcentrada').val($("#unidadDesconcentrada option:selected").val());
			$('#nombreUnidadDesconcentrada').val($("#unidadDesconcentrada option:selected").text());
			$('#codigoUnidadDesconcentrada').val($("#unidadDesconcentrada option:selected").attr('data-codigo'));
		});

		$("#renglon").change(function(){
			$('#idRenglon').val($("#renglon option:selected").val());
			$('#nombreRenglon').val($("#renglon option:selected").text());
			$('#codigoRenglon').val($("#renglon option:selected").attr('data-codigo'));
		});

		$("#cpc").change(function(){
			$('#idCPC').val($("#cpc option:selected").val());
			$('#nombreCPC').val($("#cpc option:selected").text());
			$('#codigoCPC').val($("#cpc option:selected").attr('data-codigo'));
		});

		$("#tipoCompra").change(function (event) {
			$("#idTipoCompra").val($("#tipoCompra option:selected").val());
			$("#nombreTipoCompra").val($("#tipoCompra option:selected").text());
			
			$("#nuevoPresupuesto").attr('data-opcion', 'combosPlanificacionAnual');
		    $("#nuevoPresupuesto").attr('data-destino', 'dProcedimientoSugerido');
		    $("#opcion").val('procedimientoSugerido');

		    abrir($("#nuevoPresupuesto"), event, false); //Se ejecuta ajax
		});

		$("#unidadMedida").change(function(){
			$('#idUnidadMedida').val($("#unidadMedida option:selected").val());
			$('#nombreUnidadMedida').val($("#unidadMedida option:selected").text());
		});	

		$("#cuatrimestre").change(function(){
			$('#idCuatrimestre').val($("#cuatrimestre option:selected").val());
			$('#nombreCuatrimestre').val($("#cuatrimestre option:selected").text());
		});	

		$("#tipoProducto").change(function(){
			$('#idTipoProducto').val($("#tipoProducto option:selected").val());
			$('#nombreTipoProducto').val($("#tipoProducto option:selected").text());
		});	

		$("#catalogoElectronico").change(function(){
			$('#idCatalogoElectronico').val($("#catalogoElectronico option:selected").val());
			$('#nombreCatalogoElectronico').val($("#catalogoElectronico option:selected").text());
		});	

		$("#fondosBID").change(function(){
			$('#idFondosBID').val($("#fondosBID option:selected").val());
			$('#nombreFondosBID').val($("#fondosBID option:selected").text());
		});	

		$("#operacionBID").change(function(){
			$('#idOperacionBID').val($("#operacionBID option:selected").val());
			$('#nombreOperacionBID').val($("#operacionBID option:selected").text());
		});	

		$("#proyectoBID").change(function(){
			$('#idProyectoBID').val($("#proyectoBID option:selected").val());
			$('#nombreProyectoBID').val($("#proyectoBID option:selected").text());
		});	

		$("#tipoRegimen").change(function(){
			$('#idTipoRegimen').val($("#tipoRegimen option:selected").val());
			$('#nombreTipoRegimen').val($("#tipoRegimen option:selected").text());
		});
	</script>