<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$fecha = getdate();
	$anio = $fecha['year'];
	
	$conexion = new Conexion();
	$ca = new ControladorAreas();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$idPlanificacionAnual = $_POST['idPlanificacionAnual'];
	$idPresupuesto = $_POST['idPresupuesto'];
	
	$presupuesto = pg_fetch_assoc($cpp->abrirPresupuesto($conexion, $idPresupuesto, $anio));
	$parametros = pg_fetch_assoc($cpp->abrirParametros($conexion, $anio));
		
	$estadoPresupuesto = $presupuesto['estado'];
?>

	<header>
		<h1>Presupuesto</h1>
	</header>

	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirPlanificacionAnual" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idPlanificacionAnual;?>"/>
		<button class="regresar">Regresar a Planificación Anual</button>
	</form>
	
	<table class="soloImpresion" >
		<tr>
			<td>
			
				<fieldset id="informacion">
					<legend>Presupuestos</legend>
					
					<div data-linea="1">
						<label>Ejercicio:</label><?php echo $presupuesto['ejercicio'];?>
					</div>
					
					<div data-linea="1">
						<label>Entidad:</label><?php echo $presupuesto['entidad'];?>
					</div>
					
					<div data-linea="2">
						<label>Unidad Ejecutora:</label><?php echo $presupuesto['unidad_ejecutora'];?>
					</div>
					
					<div data-linea="2">
						<label>Unidad Desconcentrada:</label><?php echo $presupuesto['unidad_desconcentrada'];?>
					</div>
					
					<div data-linea="3">
						<label>Programa:</label> <?php echo $presupuesto['programa'];?>
					</div>
					
					<div data-linea="3">
						<label>Subprograma:</label> <?php echo $presupuesto['subprograma'];?>
					</div>
					
					<div data-linea="4">
						<label>Proyecto:</label> <?php echo $presupuesto['codigo_proyecto'];?>
					</div>
					
					<div data-linea="4">
						<label>Actividad:</label> <?php echo $presupuesto['codigo_actividad'];?>
					</div>
					
					<div data-linea="5">
						<label>Obra:</label> <?php echo $presupuesto['obra'];?>
					</div>
					
					<div data-linea="5">
						<label>Geográfico:</label> <?php echo $presupuesto['geografico'];?>
					</div>
					
					<div data-linea="6">
						<label>Renglón:</label><?php echo $presupuesto['renglon'];?>
					</div>
					
					<div data-linea="6">
						<label>Renglón Auxiliar:</label> <?php echo $presupuesto['renglon_auxiliar'];?>
						<input type='hidden' id='renglonAuxiliar' name='renglonAuxiliar' value="<?php echo $presupuesto['renglon_auxiliar'];?>"/>
					</div>
			
					<div data-linea="7">
						<label>Fuente:</label> <?php echo $presupuesto['fuente'];?>
						<input type='hidden' id='fuente' name='fuente' value="<?php echo $presupuesto['fuente'];?>"/>
					</div>
					
					<div data-linea="7">
						<label>Organismo:</label> <?php echo $presupuesto['organismo'];?>
						<input type='hidden' id='organismo' name='organismo' value="<?php echo $presupuesto['organismo'];?>"/>
					</div>
					
					<div data-linea="8">
						<label>Correlativo:</label> <?php echo $presupuesto['correlativo'];?>
						<input type='hidden' id='correlativo' name='correlativo' value="<?php echo $presupuesto['correlativo'];?>"/>
					</div>
					
					<div data-linea="8">
						<label>CPC:</label><?php echo $presupuesto['cpc'];?>
					</div>
					
					<div data-linea="9">
						<label>Tipo de Compra:</label><?php echo $presupuesto['tipo_compra'];?>
					</div>
					
					<div data-linea="9">
						<label>Procedimiento Sugerido:</label><?php echo $presupuesto['procedimiento_sugerido'];?>
					</div>
					
					<div data-linea="10">
						<label>Detalle Actividad:</label> <?php echo $presupuesto['nombre_actividad'];?>
					</div>
					
					<div data-linea="11">
						<label>Detalle del Gasto:</label><?php echo $presupuesto['detalle_gasto'];?>
					</div>
					
					<div data-linea="12">
						<label>Cantidad Anual:</label><?php echo $presupuesto['cantidad_anual'];?>
					</div>
					
					<div data-linea="12">
						<label>Unidad de Medida:</label><?php echo $presupuesto['unidad_medida'];?>
					</div>
					
					<div data-linea="13">
						<label>Costo (sin IVA):</label><?php echo $presupuesto['costo'];?>
					</div>
					
					<div data-linea="13">
						<label>IVA:</label><?php echo $presupuesto['iva'];?>
					</div>
					
					<div data-linea="23">
						<label>Costo (con IVA):</label><?php echo $presupuesto['costo_iva'];?>
					</div>
					
					<div data-linea="23">
						<label>Cuatrimestre:</label><?php echo $presupuesto['cuatrimestre'];?>
					</div>
					
					<div data-linea="14">
						<label>Tipo de Producto:</label><?php echo $presupuesto['tipo_producto'];?>
					</div>
					
					<div data-linea="14">
						<label>Catálogo Electrónico:</label><?php echo $presupuesto['catalogo_electrico'];?>
					</div>
					
					<div data-linea="15">
						<label>Fondos BID:</label><?php echo $presupuesto['fondos_bid'];?>
					</div>
					
					<div data-linea="15">
						<label>Operación BID:</label><?php echo $presupuesto['operacion_bid'];?>
					</div>
					
					<div data-linea="16">
						<label>Proyecto BID:</label><?php echo $presupuesto['proyecto_bid'];?>
					</div>
					
					<div data-linea="16">
						<label>Tipo de Régimen:</label><?php echo $presupuesto['tipo_regimen'];?>
					</div>
					
					<div data-linea="17">
						<label>Presupuesto:</label><?php echo $presupuesto['tipo_presupuesto'];?>
					</div>
					
					<div data-linea="17">
						<label>Agregar al Pac:</label><?php echo $presupuesto['agregar_pac'];?>
					</div>
						
					<div data-linea="18" id="observacionRevisionPA">
							<label>Observaciones del Revisor:</label> <?php echo $presupuesto['observaciones_revision'];?>
						</div>
					
					<div data-linea="19" id="observacionAprobacionPA">
						<label>Observaciones del Aprobador:</label> <?php echo $presupuesto['observaciones_aprobacion'];?>
					</div>					
				</fieldset>
				
				<form id="modificarPresupuesto" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarPresupuesto">
					<input type='hidden' id='idPlanificacionAnual' name='idPlanificacionAnual' value="<?php echo $idPlanificacionAnual;?>" />
					<input type='hidden' id='idPresupuesto' name='idPresupuesto' value="<?php echo $idPresupuesto;?>" />
					<input type='hidden' id='opcion' name='opcion' />
					
					<fieldset>
						<legend>Presupuestos</legend>
						
						<div data-linea="1">
							<label>Ejercicio:</label><?php echo $presupuesto['ejercicio'];?>
							<input type='hidden' id='ejercicio' name='ejercicio' value="<?php echo $presupuesto['ejercicio'];?>"/>
						</div>
						
						<div data-linea="1">
							<label>Entidad:</label><?php echo $presupuesto['entidad'];?>
							<input type='hidden' id='entidad' name='entidad' value="<?php echo $presupuesto['entidad'];?>" readonly="readonly"/>
						</div>
						
						<div data-linea="2">
							<label>Unidad Ejecutora:</label> <!--revisar sesion usuario x provincia o planta central-->
							<select id=unidadEjecutora name="unidadEjecutora" required="required"  disabled="disabled" >
								<option value="">Seleccione....</option>
								<?php 
									$unidadEjecutora = $cpp->listarUnidadEjeDesXTipo($conexion, 'ejecutora');
									
									while($fila = pg_fetch_assoc($unidadEjecutora)){
										echo '<option value="' . $fila['id_unidad_ejedes'] . '" data-codigo="' . $fila['codigo'] . '" >' . $fila['nombre'].' </option>';
									}
								?>
							</select>
							
							<input type='hidden' id='idUnidadEjecutora' name='idUnidadEjecutora' value="<?php echo $presupuesto['id_unidad_ejecutora'];?>"/>
							<input type='hidden' id='nombreUnidadEjecutora' name='nombreUnidadEjecutora' value="<?php echo $presupuesto['unidad_ejecutora'];?>" />
							<input type='hidden' id='codigoUnidadEjecutora' name='codigoUnidadEjecutora' />
						</div>
						
						<div data-linea="2">
							<label>Unidad Desconcentrada:</label> <!--revisar sesion usuario x provincia o planta central-->
							<select id=unidadDesconcentrada name="unidadDesconcentrada" required="required"  disabled="disabled">
								<option value="">Seleccione....</option>
								<?php 
									$unidadDesconcentrada = $cpp->listarUnidadEjeDesXTipo($conexion, 'desconcentrada');
									
									while($fila = pg_fetch_assoc($unidadDesconcentrada)){
										echo '<option value="' . $fila['id_unidad_ejedes'] . '" >' . $fila['nombre'].' </option>';
									}
								?>
							</select>
							
							<input type='hidden' id='idUnidadDesconcentrada' name='idUnidadDesconcentrada' value="<?php echo $presupuesto['id_unidad_desconcentrada'];?>" />
							<input type='hidden' id='nombreUnidadDesconcentrada' name='nombreUnidadDesconcentrada' value="<?php echo $presupuesto['unidad_desconcentrada'];?>"/>
							<input type='hidden' id='codigoUnidadDesconcentrada' name='codigoUnidadDesconcentrada' />
						</div>
						
						<div data-linea="3">
							<label>Programa:</label> <?php echo $presupuesto['programa'];?>
							<input type='hidden' id='programa' name='programa' value="<?php echo $presupuesto['programa'];?>"/>
						</div>
						
						<div data-linea="3">
							<label>Subprograma:</label> <?php echo $presupuesto['subprograma'];?>
							<input type='hidden' id='subprograma' name='subprograma' value="<?php echo $presupuesto['subprograma'];?>"/>
						</div>
						
						<div data-linea="4">
							<label>Proyecto:</label> <?php echo $presupuesto['codigo_proyecto'];?>
							<input type='hidden' id='codigoProyecto' name='codigoProyecto' value="<?php echo $presupuesto['codigo_proyecto'];?>" />
						</div>
						
						<div data-linea="4">
							<label>Actividad:</label> <?php echo $presupuesto['codigo_actividad'];?>
							<input type='hidden' id='codigoActividad' name='codigoActividad' value="<?php echo $presupuesto['codigo_actividad'];?>"/>
						</div>
						
						<div data-linea="5">
							<label>Obra:</label> <?php echo $presupuesto['obra'];?>
							<input type='hidden' id='obra' name='obra' value="<?php echo $presupuesto['obra'];?>"/>
						</div>
						
						<div data-linea="5">
							<label>Geográfico:</label> <?php echo $presupuesto['geografico'];?>
							<input type='hidden' id='geografico' name='geografico' value="<?php echo $presupuesto['geografico'];?>"/>
						</div>
						
						<div data-linea="6">
							<label>Renglón:</label>
							<select id=renglon name="renglon" required="required" disabled="disabled">
								<option value="">Seleccione....</option>
								<?php 
									$renglon = $cpp->listarRenglon($conexion);
									
									while($fila = pg_fetch_assoc($renglon)){
										echo '<option value="' . $fila['id_renglon'] . '" data-codigo="' . $fila['codigo'] . '" >' . $fila['nombre'] .' - '. $fila['codigo'].' </option>';
									}
								?>
							</select>
							
							<input type='hidden' id='idRenglon' name='idRenglon' value="<?php echo $presupuesto['id_renglon']?>" />
							<input type='hidden' id='nombreRenglon' name='nombreRenglon' />
							<input type='hidden' id='codigoRenglon' name='codigoRenglon' value="<?php echo $presupuesto['renglon']?>"/>
						</div>
						
						<div data-linea="6">
							<label>Renglón Auxiliar:</label> <?php echo $presupuesto['renglon_auxiliar'];?>
							<input type='hidden' id='renglonAuxiliar' name='renglonAuxiliar' value="<?php echo $presupuesto['renglon_auxiliar'];?>"/>
						</div>
				
						<div data-linea="7">
							<label>Fuente:</label> <?php echo $presupuesto['fuente'];?>
							<input type='hidden' id='fuente' name='fuente' value="<?php echo $presupuesto['fuente'];?>"/>
						</div>
						
						<div data-linea="7">
							<label>Organismo:</label> <?php echo $presupuesto['organismo'];?>
							<input type='hidden' id='organismo' name='organismo' value="<?php echo $presupuesto['organismo'];?>"/>
						</div>
						
						<div data-linea="8">
							<label>Correlativo:</label> <?php echo $presupuesto['correlativo'];?>
							<input type='hidden' id='correlativo' name='correlativo' value="<?php echo $presupuesto['correlativo'];?>"/>
						</div>
						
						<div data-linea="8">
							<label>CPC:</label>
							<select id=cpc name="cpc" required="required" disabled="disabled">
								<option value="">Seleccione....</option>
								<?php 
									$cpc = $cpp->listarCPC($conexion);
									
									while($fila = pg_fetch_assoc($cpc)){
										echo '<option value="' . $fila['id_cpc'] . '" data-codigo="' . $fila['codigo'] . '">' . $fila['nombre'] .' - '.$fila['codigo'].' </option>';
									}
								?>
							</select>
							
							<input type='hidden' id='idCPC' name='idCPC' value="<?php echo $presupuesto['id_cpc'];?>"/>
							<input type='hidden' id='nombreCPC' name='nombreCPC' />
							<input type='hidden' id='codigoCPC' name='codigoCPC' value="<?php echo $presupuesto['cpc'];?>" />
						</div>
						
						<div data-linea="9">
							<label>Tipo de Compra:</label>
							<select id=tipoCompra name="tipoCompra" required="required" disabled="disabled" >
								<option value="">Seleccione....</option>
								<?php 
									$tipoCompra = $cpp->listarTipoCompra($conexion);
									
									while($fila = pg_fetch_assoc($tipoCompra)){
										echo '<option value="' . $fila['id_tipo_compra'] . '" >' . $fila['nombre'].' </option>';
									}
								?>
							</select>
							
							<input type='hidden' id='idTipoCompra' name='idTipoCompra' value="<?php echo $presupuesto['id_tipo_compra'];?>"/>
							<input type='hidden' id='nombreTipoCompra' name='nombreTipoCompra' value="<?php echo $presupuesto['tipo_compra'];?>"/>
						</div>
						
						<div data-linea="9">
							<div id="dProcedimientoSugerido">
								<label id="lProcedimientoSugerido">Procedimiento Sugerido:</label>
								<select id="procedimientoSugerido" name="procedimientoSugerido" required="required" disabled="disabled">
									<option value="">Seleccione....</option>
									<?php 
										$procedimientoSugerido = $cpp->listarProcedimientoSugerido($conexion, $presupuesto['id_tipo_compra']);
										
										while($fila = pg_fetch_assoc($procedimientoSugerido)){
											echo '<option value="' . $fila['id_procedimiento_sugerido'] . '" >' . $fila['nombre'].' </option>';
										}
									?>
								</select>
								
								<input type="hidden" id="idProcedimientoSugerido" name="idProcedimientoSugerido" value="<?php echo $presupuesto['id_procedimiento_sugerido'];?>" />
								<input type="hidden" id="nombreProcedimientoSugerido" name="nombreProcedimientoSugerido" value="<?php echo $presupuesto['procedimiento_sugerido'];?>"/>
							</div>
						</div>
						
						<div data-linea="10">
							<label>Detalle Actividad:</label> <?php echo $presupuesto['nombre_actividad'];?>
							<input type='hidden' id='detalleActividad' name='detalleActividad' value="<?php echo $presupuesto['nombre_actividad'];?>"/>
						</div>
						
						<div data-linea="11">
							<label>Detalle del Gasto:</label> 
							<input type='text' id='detalleGasto' name='detalleGasto' maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value = "<?php echo $presupuesto['detalle_gasto'];?>"  disabled="disabled"/>
						</div>
						
						<div data-linea="12">
							<label>Cantidad Anual:</label><?php echo $presupuesto['cantidad_anual'];?> 
							<!-- input type='text' id='cantidadAnual' name='cantidadAnual' maxlength="4" data-er="^[0-9]+$" value="< ?php echo $presupuesto['cantidad_anual'];?>" disabled="disabled"/-->
						</div>
						
						<div data-linea="12">
							<label>Unidad de Medida:</label>
							<select id=unidadMedida name="unidadMedida" required="required" disabled="disabled" >
								<option value="">Seleccione....</option>
								<?php 
									$unidadMedida = $cpp->listarUnidadesMedidaSercop($conexion);
									
									while($fila = pg_fetch_assoc($unidadMedida)){
										echo '<option value="' . $fila['id_unidad_medida'] . '" >' . $fila['nombre'].' </option>';
									}
								?>
							</select>
							
							<input type='hidden' id='idUnidadMedida' name='idUnidadMedida' value="<?php echo $presupuesto['id_unidad_medida']; ?>"/>
							<input type='hidden' id='nombreUnidadMedida' name='nombreUnidadMedida' value="<?php echo $presupuesto['unidad_medida']; ?>" />
						</div>
						
						<div data-linea="13">
							<label>Costo (sin IVA):</label> 
							<input type='text' id='costo' name='costo' maxlength="8" data-er="^[0-9.]+$" value="<?php echo $presupuesto['costo']; ?>" disabled="disabled"/>
						</div>
						
						<div data-linea="13">
							<label>IVA:</label>
							<select id=iva name="iva" required="required" disabled="disabled" >
								<option value="">Seleccione....</option>
								<option value="<?php echo $parametros['iva'];?>"><?php echo $parametros['iva'];?>%</option>
								<option value="0">0%</option>
							</select>
						</div>
						
						<div data-linea="23">
							<label>Costo (con IVA):</label> 
							<input type='text' id='costoIva' name='costoIva' maxlength="8" data-er="^[0-9.]+$" value="<?php echo $presupuesto['costo_iva']; ?>" disabled="disabled" readonly="readonly"/>
						</div>
						
						<div data-linea="23">
							<label>Cuatrimestre:</label>
							<select id=cuatrimestre name="cuatrimestre" required="required" disabled="disabled" >
								<option value="">Seleccione....</option>
								<option value="Cuatrimestre I">Cuatrimestre I</option>
								<option value="Cuatrimestre II">Cuatrimestre II</option>
								<option value="Cuatrimestre III">Cuatrimestre III</option>						
							</select>
							
							<input type='hidden' id='idCuatrimestre' name='idCuatrimestre' value="<?php echo $presupuesto['cuatrimestre']; ?>"/>
							<input type='hidden' id='nombreCuatrimestre' name='nombreCuatrimestre' value="<?php echo $presupuesto['cuatrimestre']; ?>" />
						</div>
						
						<div data-linea="14">
							<label>Tipo de Producto:</label>
							<select id=tipoProducto name="tipoProducto" required="required" disabled="disabled" >
								<option value="">Seleccione....</option>
								<option value="Normalizado">Normalizado</option>
								<option value="No Normalizado">No Normalizado</option>
								<option value="No Aplica">No Aplica</option>					
							</select>
							
							<input type='hidden' id='idTipoProducto' name='idTipoProducto' value="<?php echo $presupuesto['tipo_producto']; ?>"/>
							<input type='hidden' id='nombreTipoProducto' name='nombreTipoProducto' value="<?php echo $presupuesto['tipo_producto']; ?>" />
						</div>
						
						<div data-linea="14">
							<label>Catálogo Electrónico:</label>
							<select id=catalogoElectronico name="catalogoElectronico" required="required" disabled="disabled" >
								<option value="">Seleccione....</option>
								<option value="Si">Si</option>
								<option value="No">No</option>					
							</select>
							
							<input type='hidden' id='idCatalogoElectronico' name='idCatalogoElectronico' value="<?php echo $presupuesto['catalogo_electronico']; ?>" />
							<input type='hidden' id='nombreCatalogoElectronico' name='nombreCatalogoElectronico' value="<?php echo $presupuesto['catalogo_electronico']; ?>" />
						</div>
						
						<div data-linea="15">
							<label>Fondos BID:</label>
							<select id=fondosBID name="fondosBID" required="required" disabled="disabled" >
								<option value="">Seleccione....</option>
								<option value="Si">Si</option>
								<option value="No">No</option>					
							</select>
							
							<input type='hidden' id='idFondosBID' name='idFondosBID' value="<?php echo $presupuesto['fondos_bid']; ?>" />
							<input type='hidden' id='nombreFondosBID' name='nombreFondosBID' value="<?php echo $presupuesto['fondos_bid']; ?>" />
						</div>
						
						<div data-linea="15">
							<label>Operación BID:</label>
							<select id=operacionBID name="operacionBID" required="required" disabled="disabled" >
								<option value="">Seleccione....</option>
								<option value="Si">Si</option>
								<option value="No">No</option>						
							</select>
							
							<input type='hidden' id='idOperacionBID' name='idOperacionBID' value="<?php echo $presupuesto['operacion_bid']; ?>" />
							<input type='hidden' id='nombreOperacionBID' name='nombreOperacionBID' value="<?php echo $presupuesto['operacion_bid']; ?>" />
						</div>
						
						<div data-linea="16">
							<label>Proyecto BID:</label>
							<select id=proyectoBID name="proyectoBID" required="required" disabled="disabled" >
								<option value="">Seleccione....</option>
								<option value="Si">Si</option>
								<option value="No">No</option>						
							</select>
							
							<input type='hidden' id='idProyectoBID' name='idProyectoBID' value="<?php echo $presupuesto['proyecto_bid']; ?>" />
							<input type='hidden' id='nombreProyectoBID' name='nombreProyectoBID' value="<?php echo $presupuesto['proyecto_bid']; ?>" />
						</div>
						
						<div data-linea="16">
							<label>Tipo de Régimen:</label>
							<select id=tipoRegimen name="tipoRegimen" required="required" disabled="disabled" >
								<option value="">Seleccione....</option>
								<option value="Comun">Común</option>
								<option value="Especial">Especial</option>		
								<option value="No Aplica">No Aplica</option>				
							</select>
							
							<input type='hidden' id='idTipoRegimen' name='idTipoRegimen' value="<?php echo $presupuesto['tipo_regimen']; ?>"  />
							<input type='hidden' id='nombreTipoRegimen' name='nombreTipoRegimen' value="<?php echo $presupuesto['tipo_regimen']; ?>"  />
						</div>
						
						<div data-linea="17">
							<label>Presupuesto:</label> <?php echo $presupuesto['tipo_presupuesto'];?>
							<input type='hidden' id='presupuesto' name='presupuesto' value="<?php echo $presupuesto['tipo_presupuesto']; ?>"/>
						</div>
						
						<div data-linea="17">
							<label>Agregar al PAC:</label>
							<select id=agregarPac name="agregarPac" required="required" disabled="disabled" >
								<option value="">Seleccione....</option>
								<option value="Si">Si</option>
								<option value="No">No</option>
							</select>
						</div>	
				
						
						<div data-linea="18" id="observacionRevisionPA">
							<label>Observaciones del Revisor:</label> <?php echo $presupuesto['observaciones_revision'];?>
						</div>
						<br />
						<div data-linea="19" id="observacionAprobacionPA">
							<label>Observaciones del Aprobador:</label> <?php echo $presupuesto['observaciones_aprobacion'];?>
						</div>
				
						<div id ="botonesModificar" data-linea="20">
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>

			</td>
		</tr>
	</table>
<script type="text/javascript">
var estadoPresupuesto = <?php echo json_encode($estadoPresupuesto); ?>;
								
	$('document').ready(function(){
		cargarValorDefecto("unidadEjecutora","<?php echo $presupuesto['id_unidad_ejecutora'];?>");
		cargarValorDefecto("unidadDesconcentrada","<?php echo $presupuesto['id_unidad_desconcentrada'];?>");
		cargarValorDefecto("renglon","<?php echo $presupuesto['id_renglon'];?>");
		cargarValorDefecto("cpc","<?php echo $presupuesto['id_cpc'];?>");
		cargarValorDefecto("tipoCompra","<?php echo $presupuesto['id_tipo_compra'];?>");
		cargarValorDefecto("procedimientoSugerido","<?php echo $presupuesto['id_procedimiento_sugerido'];?>");
		cargarValorDefecto("unidadMedida","<?php echo $presupuesto['id_unidad_medida'];?>");
		cargarValorDefecto("cuatrimestre","<?php echo $presupuesto['cuatrimestre'];?>");
		cargarValorDefecto("tipoProducto","<?php echo $presupuesto['tipo_producto'];?>");
		cargarValorDefecto("catalogoElectronico","<?php echo $presupuesto['catalogo_electronico'];?>");
		cargarValorDefecto("fondosBID","<?php echo $presupuesto['fondos_bid'];?>");
		cargarValorDefecto("operacionBID","<?php echo $presupuesto['operacion_bid'];?>");
		cargarValorDefecto("proyectoBID","<?php echo $presupuesto['proyecto_bid'];?>");
		cargarValorDefecto("tipoRegimen","<?php echo $presupuesto['tipo_regimen'];?>");
		cargarValorDefecto("agregarPac","<?php echo $presupuesto['agregar_pac'];?>");
		cargarValorDefecto("iva","<?php echo $presupuesto['iva'];?>");
		acciones("#nuevaActividad","#detalleActividad");
		distribuirLineas();

		if(estadoPresupuesto == "creado"){
			$("#modificarPresupuesto").show();
			$("#informacion").hide();			
		}else if(estadoPresupuesto == 'rechazado'){
			$("#modificarPresupuesto").show();
			$("#informacion").hide();
		}else{
			$("#modificarPresupuesto").hide();
			$("#informacion").show();
		}

		if(estadoPresupuesto == 'rechazado'){
			$("#observacionRevisionPA").show();
			$("#observacionAprobacionPA").show();
		}
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	$("#modificarPresupuesto").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#unidadEjecutora").val())){
			error = true;
			$("#unidadEjecutora").addClass("alertaCombo");
		}
		
		if(!$.trim($("#unidadDesconcentrada").val())){
			error = true;
			$("#unidadDesconcentrada").addClass("alertaCombo");
		}

		if(!$.trim($("#renglon").val())){
			error = true;
			$("#renglon").addClass("alertaCombo");
		}

		if(!$.trim($("#cpc").val())){
			error = true;
			$("#cpc").addClass("alertaCombo");
		}

		if(!$.trim($("#tipoCompra").val())){
			error = true;
			$("#tipoCompra").addClass("alertaCombo");
		}

		if(!$.trim($("#procedimientoSugerido").val())){
			error = true;
			$("#procedimientoSugerido").addClass("alertaCombo");
		}

		if(!$.trim($("#detalleGasto").val()) || !esCampoValido("#detalleGasto")){
			error = true;
			$("#detalleGasto").addClass("alertaCombo");
		}

		if(!$.trim($("#unidadMedida").val())){
			error = true;
			$("#unidadMedida").addClass("alertaCombo");
		}

		if(!$.trim($("#costo").val()) || !esCampoValido("#costo")){
			error = true;
			$("#costo").addClass("alertaCombo");
		}

		if(!$.trim($("#cuatrimestre").val())){
			error = true;
			$("#cuatrimestre").addClass("alertaCombo");
		}

		if(!$.trim($("#tipoProducto").val())){
			error = true;
			$("#tipoProducto").addClass("alertaCombo");
		}
		
		if(!$.trim($("#catalogoElectronico").val())){
			error = true;
			$("#catalogoElectronico").addClass("alertaCombo");
		}
		
		if(!$.trim($("#fondosBID").val())){
			error = true;
			$("#fondosBID").addClass("alertaCombo");
		}
		
		if(!$.trim($("#operacionBID").val())){
			error = true;
			$("#operacionBID").addClass("alertaCombo");
		}
		
		if(!$.trim($("#proyectoBID").val())){
			error = true;
			$("#proyectoBID").addClass("alertaCombo");
		}
		
		if(!$.trim($("#tipoRegimen").val())){
			error = true;
			$("#tipoRegimen").addClass("alertaCombo");
		}

		if(!$.trim($("#agregarPac").val())){
			error = true;
			$("#agregarPac").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

	$("#unidadEjecutora").change(function(){
		$('#idUnidadEjecutora').val($("#unidadEjecutora option:selected").val());
		$('#nombreUnidadEjecutora').val($("#unidadEjecutora option:selected").text());
	});

	$("#unidadDesconcentrada").change(function(){
		$('#idUnidadDesconcentrada').val($("#unidadDesconcentrada option:selected").val());
		$('#nombreUnidadDesconcentrada').val($("#unidadDesconcentrada option:selected").text());
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
		
		$("#modificarPresupuesto").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#modificarPresupuesto").attr('data-destino', 'dProcedimientoSugerido');
	    $("#opcion").val('procedimientoSugerido');

	    abrir($("#modificarPresupuesto"), event, false); //Se ejecuta ajax
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