<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorEventoSanitario.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	$cpco = new ControladorEventoSanitario();
	$listaCatalogos = new ControladorEventoSanitario();
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Técnico Seguimiento de Eventos Sanitarios'),0,'id_perfil');
	}
	
	$ruta = 'seguimientoEventosSanitarios';
	
	$idEventoSanitario = $_POST['id'];
	
	$eventoSanitario = pg_fetch_assoc($cpco->abrirEventoSanitario($conexion, $idEventoSanitario));
	$numMuestraPrimeraVisita = pg_num_rows($cpco->listarMuestrasPorVisita($conexion, $idEventoSanitario, 'Visita 0001'));
	$origenProbable = pg_fetch_assoc($cpco->abrirMedidaSanitaria($conexion, $idEventoSanitario, 'Visita 0001'));
	$laboratorios = $cpco->abrirCatalogoLaboratorios($conexion);
	$laboratorios1 = $cpco->abrirCatalogoLaboratorios($conexion);
	
	if($numMuestraPrimeraVisita != 0){
		$muestraPrimeraVisita = pg_fetch_assoc($cpco->listarMuestrasPorVisita($conexion, $idEventoSanitario, 'Visita 0001'));
	}
	
	$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
	$provincias1 = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	
	/* catalogos*/
	$laboratoriosMuestra = $listaCatalogos->listarCatalogos($conexion,'LABORATORIO');
	$especiesMuestras = $listaCatalogos->listarCatalogos($conexion,'ESPECIES');
	$pruebaMuestras = $listaCatalogos->listarCatalogos($conexion,'PRUEBAS_LAB');
	$tiposMuestras = $listaCatalogos->listarCatalogos($conexion,'TIPO_MUESTRA');
	$origenes = $listaCatalogos->listarCatalogos($conexion,'ORIGENES');
	$especiesPoblacion = $listaCatalogos->listarCatalogos($conexion,'ESPECIES');
	$categoriasPoblaciones = $listaCatalogos->listarCatalogos($conexion,'CATEGORIA');
	$especiesPoblacionAves = $listaCatalogos->listarCatalogos($conexion,'AVES');
	$tipoMovimientos = $listaCatalogos->listarCatalogos($conexion,'MOVIMIENTOS');
	$especieMovimientos = $listaCatalogos->listarCatalogos($conexion,'ESPECIES');
	$tipoMovimientosEgreso = $listaCatalogos->listarCatalogos($conexion,'MOVIMIENTOS');
	$especieMovimientosEgreso = $listaCatalogos->listarCatalogos($conexion,'ESPECIES');
	$especieMovimientosAves = $listaCatalogos->listarCatalogos($conexion,'AVES');
	$movimientoAves = $listaCatalogos->listarCatalogos($conexion,'MOVIMIENTOS');
		
	/*grid*/
	$muestra  = $cpco->listarMuestrasDetalle($conexion, $idEventoSanitario);
	$muestraConsulta  = $cpco->listarMuestrasDetalle($conexion, $idEventoSanitario);
	$origenAnimal  = $cpco->listarOrigenes($conexion, $idEventoSanitario);
	$origenAnimalConsulta  = $cpco->listarOrigenes($conexion, $idEventoSanitario);
	$poblaciones  = $cpco->listarPoblaciones($conexion, $idEventoSanitario);
	$poblacionesConsulta  = $cpco->listarPoblaciones($conexion, $idEventoSanitario);
	$poblacionesAves  = $cpco->listarPoblacionesAves($conexion, $idEventoSanitario);
	$poblacionesAvesConsulta  = $cpco->listarPoblacionesAves($conexion, $idEventoSanitario);
	$ingresos  = $cpco->listarIngresos($conexion, $idEventoSanitario);
	$ingresosConsulta  = $cpco->listarIngresos($conexion, $idEventoSanitario);
	$egresos = $cpco->listarEgresos($conexion, $idEventoSanitario);
	$egresosConsulta = $cpco->listarEgresos($conexion, $idEventoSanitario);
	$movimientosAves  = $cpco->listarMovimientosAves($conexion, $idEventoSanitario);
	$movimientosAvesConsulta  = $cpco->listarMovimientosAves($conexion, $idEventoSanitario);
	
?>

<header>
	<h1>Eventos Sanitarios - Primera visita</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>

<!--Colecta de material - Visitas - población-->
<div class="pestania">
	<h2>Colecta de material</h2>
	
	<form id="nuevaColectaMaterial" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarColectaMaterial" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		<input type='hidden' id='fechaSiguienteInspeccion' name='fechaSiguienteInspeccion' value="<?php echo date('Y-n-j',strtotime($eventoSanitario['fecha_siguiente_visita']));?>" />
		
		<fieldset>
			<legend>Colecta de Material</legend>
			
				<div data-linea="1">
					<label>Número de visita:</label>
					<?php echo $eventoSanitario['num_inspeccion']; ?>
					<input type="hidden" id="numeroVisita" name="numeroVisita" value="<?php echo $eventoSanitario['num_inspeccion']; ?>"/>
				</div>
				
				<div data-linea="2">
					<label>Se colecta Material:</label>
						<select id="colectaMaterial" name="colectaMaterial" required="required" >
							<option value="">Seleccione....</option>
							<option value="Si" <?php echo ($muestraPrimeraVisita['colecta_material']=='Si'?'selected':''); ?>>Si</option>
							<option value="No" <?php echo ($muestraPrimeraVisita['colecta_material']=='No'?'selected':''); ?>>No</option>
						</select> 
				</div>
				
				<div data-linea="3">
					<label>Razón de la colecta o no colecta de muestras:</label>
					<input type="text" id="razonesMuestra" name="razonesMuestra" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$" required="required" <?php echo ($numMuestraPrimeraVisita!=0)?'value="'.$muestraPrimeraVisita['razones_muestra'].'"':'';?>/>
				</div>
				
		</fieldset>
		
		<fieldset id="colecta">
			<legend>Laboratorio</legend>
				
				<div data-linea="5">
					<label>Laboratorio:</label>
						<select id="laboratorioMuestra" name="laboratorioMuestra" required="required" > <!--?php //echo ($numMuestraPrimeraVisita!=0)?'disabled="disabled"':'';?-->
							<option value="">Seleccione....</option>
							<?php 
								while ($laboratorio = pg_fetch_assoc($laboratorios)){
									if($numMuestraPrimeraVisita!=0){ 
										if($muestraPrimeraVisita['laboratorio_muestra']==$laboratorio['id_laboratorio']){
											echo '<option value="' . $laboratorio['id_laboratorio'] . '" selected="selected">' . $laboratorio['nombre'] . '</option>';
										}else{
											echo '<option value="' . $laboratorio['id_laboratorio'] . '">' . $laboratorio['nombre'] . '</option>';
										}
									}else{
										echo '<option value="' . $laboratorio['id_laboratorio'] . '">' . $laboratorio['nombre'] . '</option>';
									}
							}
							?>
						</select> 
						<input type="hidden" id="nombreLaboratorioMuestra" name="nombreLaboratorioMuestra" value="<?php echo $eventoSanitario['oficina'];?>"/>
				</div>	
			
				<div data-linea="55">
					<label>Anexo:</label>
					
					<input type="file" class="archivo" name="anexo" accept="application/pdf" /> 
					
					<input type="hidden" class="rutaArchivo" id="archivoAnexo" name="archivoAnexo" value="" />
					
					<div class="estadoCarga">
						En espera de archivo... (Tamaño máximo; <?php echo ini_get("upload_max_filesize");?>B)
					</div>
					
					<button type="button" class="subirArchivoAnexo" data-rutaCarga="aplicaciones/seguimientoEventosSanitarios/eventoSanitario/anexo">Subir anexo</button>
				</div>
		</fieldset>
		
	<!-- form id="nuevaDetalleMuestra" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarDetalleMuestra" data-destino="detalleItem"-->
		<fieldset id="nuevaMuestra">
			<legend>Información de la muestra</legend>			
			
				<div data-linea="5">
					<label>Especie:</label>
						<select id="especieMuestra" name="especieMuestra" required="required">
							<option value="">Seleccione....</option>
							<?php 
								while ($especie = pg_fetch_assoc($especiesMuestras)){
								echo '<option value="' . $especie['codigo'] . '">' . $especie['nombre'] . '</option>';
							}
							?>
						</select> 
						<input type="hidden" id="nombreEspecieMuestra" name="nombreEspecieMuestra" />
				</div>
				
				<div data-linea="6">
					<label>Pruebas de laboratorio solicitadas:</label>
						<select id="pruebasMuestra" name="pruebasMuestra" required="required">
							<option value="">Seleccione....</option>
							<?php 
								while ($prueba = pg_fetch_assoc($pruebaMuestras)){
								echo '<option value="' . $prueba['codigo'] . '">' . $prueba['nombre'] . '</option>';
							}
							?>
						</select> 
						<input type="hidden" id="nombrePruebasMuestra" name="nombrePruebasMuestra" />
				</div>
				
				<div data-linea="7">
					<label>Tipo muestra:</label>
						<select id="tipoMuestra" name="tipoMuestra" required="required">
							<option value="">Seleccione....</option>
							<?php 
								while ($tipo = pg_fetch_assoc($tiposMuestras)){
								echo '<option value="' . $tipo['codigo'] . '">' . $tipo['nombre'] . '</option>';
							}
							?>
						</select> 
						<input type="text" id="nombreTipoMuestra" name="nombreTipoMuestra" />
				</div>
				
				<div data-linea="8">
					<label>Número de muestras:</label>
					<input type="number" id="numeroMuestras" name="numeroMuestras" required="required" />
				</div>
				
				<div data-linea="9">
					<label>Fecha colecta muestra:</label>
					<input type="text" id="fechaColectaMuestra" name="fechaColectaMuestra" required="required"/>
				</div>
				
								
				<div data-linea="9">
					<label>Fecha envio muestra:</label>
					<input type="text" id="fechaEnvioMuestra" name="fechaEnvioMuestra" required="required"/>
				</div>
				
				
				<div>
					<button type="button" onclick="agregarAnalisis()" class="mas">Agregar análisis</button>		
				</div>
			
		</fieldset>
	
	<fieldset id="detalleMuestraFS">
		<legend>información muestras</legend>
		<table>
			<thead>
				<tr>
					<th width="15%">Especie</th>
					<th width="15%">Prueba Laboratorio Solicitada</th>
					<th width="15%">Tipo muestra</th>
					<th width="15%">Número de muestras</th>
					<th width="15%">Fecha colecta muestra</th>
					<th width="15%">Fecha envio muestra</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<tbody id="detalleVacunacionAftosa">
			</tbody>
		</table>
	</fieldset>
	
	<div>
		<button type="submit" class="mas">Guardar</button>		
	</div>
	
	</form>
	
	<form id="infoMuestras" data-rutaAplicacion="seguimientoEventosSanitarios" data-destino="detalleItem">
	</form>
	
	<fieldset id="detalleMuestraConsultaFS">
		<legend>información muestras</legend>
		<table>
			<thead>
				<tr>
					<th width="15%">Num Visita</th>
					<th width="15%">Especie</th>
					<th width="15%">Prueba Laboratorio Solicitada</th>
					<th width="15%">Tipo muestra</th>
					<th width="15%">Número de muestras</th>
					<th width="15%">Fecha colecta muestra</th>
					<th width="15%">Fecha envio muestra</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($muestraGC = pg_fetch_assoc($muestraConsulta)){
					echo $cpco->imprimirLineaMuestra(	$muestraGC['id_detalle_muestra'],
																$muestraGC['id_muestra'], 
																$muestraGC['id_evento_sanitario'],
																$muestraGC['especie_muestra'], 
																$muestraGC['prueba_muestra'],  
																$muestraGC['tipo_muestra'], 
																$muestraGC['numero_muestras'], 
																$muestraGC['fecha_colecta_muestra'], 
																$muestraGC['fecha_envio_muestra'],
																$ruta,
																$muestraGC['numero_visita']);
				}
			?>
		</table>
	</fieldset>
</div>
<div class="pestania">

	<h2>Población animal existente</h2>
		
	<form id="nuevaPoblacionExistente" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarPoblacionExistente" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		<input type="hidden" id="numeroVisita" name="numeroVisita" value="<?php echo $eventoSanitario['num_inspeccion']; ?>"/>
		<input type='hidden' id='opcion' name='opcion' value="buscarCategoria" />
	
		<fieldset>
			<legend>Población animal, existente, enferma y muerta</legend>
		
		
			<div data-linea="0">
				<label>Especie:</label>
					<select id="especiePoblacion" name="especiePoblacion" required="required">
						<option value="">Seleccione....</option>
						<?php 
							while ($especie = pg_fetch_assoc($especiesPoblacion)){
							echo '<option value="' . $especie['codigo'] . '">' . $especie['nombre'] . '</option>';
						}
						?>
					</select> 
					<input type="hidden" id="nombreEspeciePoblacion" name="nombreEspeciePoblacion" value="<?php echo $eventoSanitario['oficina'];?>"/>
			</div>
			
			<div id="categoria" data-linea="1">
					
			</div>
			
			<div data-linea="2">
				<label>Existentes:</label>
				<input type="number" id="existentesPoblacion" name="existentesPoblacion" required="required"/>
			</div>
			
			<div data-linea="2">
				<label>Enfermos:</label>
				<input type="number" id="enfermosPoblacion" name="enfermosPoblacion" required="required"/>
			</div>
			
			<div data-linea="3">
				<label>Muertos:</label>
				<input type="number" id="muertosPoblacion" name="muertosPoblacion" required="required"/>
			</div>
			
			<div data-linea="3">
				<label>Sacrificados:</label>
				<input type="number" id="sacrificadosPoblacion" name="sacrificadosPoblacion" required="required"/>
			</div>
						
			<div data-linea="4">
				<label>Enfermos sin vacunar:</label>
				<input type="number" id="enfermosSinVacunaPoblacion" name="enfermosSinVacunaPoblacion" required="required"/>
			</div>
			
			<div data-linea="4">
				<label>Total sin vacunar:</label>
				<input type="number" id="totalSinVacunaPoblacion" name="totalSinVacunaPoblacion" required="required"/>
			</div>			
						
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
		
		</fieldset>
	</form>
	
	<fieldset id="detallePoblacionFS">
		<legend>Población animal, existente, enferma y muerta</legend>
		<table id="detallePoblacion">
			<thead>
				<tr>
					<th width="15%">Visita</th>
					<th width="15%">Especie</th>
					<th width="15%">Categoria</th>
					<th width="15%">Existentes</th>
					<th width="15%">Enfermos</th>
					<th width="15%">Muertos</th>
					<th width="15%">Sacrificados</th>
					<th width="15%">Enfermos sin vacunar</th>
					<th width="15%">Total sin vacunar</th>					
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($poblacion = pg_fetch_assoc($poblaciones)){
					echo $cpco->imprimirLineaPoblacion(	$poblacion['id_poblacion_animales'], 
														$poblacion['id_evento_sanitario'],
														$poblacion['numero_visita'], 
														$poblacion['nombre_especie_poblacion'], 
														$poblacion['tipo_especie_poblacion'],
														$poblacion['existentes'], 
														$poblacion['enfermos'],														
														$poblacion['muertos'], 
														$poblacion['sacrificados'],			
														$poblacion['total_sin_vacunar'],
														$poblacion['enfermos_sin_vacunas'],
														$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePoblacionConsultaFS">
		<legend>Población animal, existente, enferma y muerta</legend>
		<table id="detallePoblacion">
			<thead>
				<tr>
					<th width="15%">Visita</th>
					<th width="15%">Especie</th>
					<th width="15%">Categoria</th>
					<th width="15%">Existentes</th>
					<th width="15%">Enfermos</th>
					<th width="15%">Muertos</th>
					<th width="15%">Sacrificados</th>
					<th width="15%">Enfermos sin vacunar</th>
					<th width="15%">Total sin vacunar</th>					
				</tr>
			</thead>
			<?php 
				while ($poblacion = pg_fetch_assoc($poblacionesConsulta)){
					echo $cpco->imprimirLineaPoblacionConsulta(	$poblacion['id_poblacion_animales'], 
																$poblacion['id_evento_sanitario'],
																$poblacion['numero_visita'], 
																$poblacion['nombre_especie_poblacion'], 
																$poblacion['tipo_especie_poblacion'],
																$poblacion['existentes'], 
																$poblacion['enfermos'],														
																$poblacion['muertos'], 
																$poblacion['sacrificados'],		
																$poblacion['total_sin_vacunar'],														
																$poblacion['enfermos_sin_vacunas'],														
																$ruta);
				}
			?>
		</table>
	</fieldset>

<form id="nuevaPrediosAfectados" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarPrediosAfectados" data-destino="detalleItem">
	<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
	<input type="hidden" id="numeroVisita" name="numeroVisita" value="<?php echo $eventoSanitario['num_inspeccion']; ?>"/>
		
		<fieldset>
			<legend>Predios vecinos afectados</legend>
			
			<div data-linea="1">
				<label>Existen predios vecinos afectados:</label>
					<select id="prediosAfectados" name="prediosAfectados" required="required">
						<option value="">Seleccione....</option>
						<option value="Si" <?php echo ($eventoSanitario['otros_predios_afectados']=='Si'?'selected':''); ?>>Si</option>
						<option value="No" <?php echo ($eventoSanitario['otros_predios_afectados']=='No'?'selected':''); ?>>No</option>
					</select> 
			</div>
			
			<div data-linea="8">
				<label id="lCuantosPrediosAfectados">Cuántos:</label>
				<input type="number" id="cuantosPrediosAfectados" name="cuantosPrediosAfectados" required="required" value="<?php echo $eventoSanitario['numero_predios_afectados']; ?>"/>
			</div>
			
			<div>
				<button type="submit" class="guardar">Guardar</button>		
			</div>
		
		</fieldset>
	
	</form>
</div>

<!--movimiento de animales -->
<div class="pestania">
	<h2>Movimiento de animales</h2>

	<form id="nuevaMovimientoAnimales" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarMovimientoAnimales" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		<input type="hidden" id="numeroVisita" name="numeroVisita" value="<?php echo $eventoSanitario['num_inspeccion']; ?>"/>
		
		<fieldset>
			<legend>Movimiento</legend>
		
			<div data-linea="1">
				<label>Hubo ingreso de animales y/o vehiculizantes de enfermedad en 30 días antes del inicio:</label>
			</div>
			<div data-linea="2">
					<select id="movimientoAnimal" name="movimientoAnimal" required="required">
						<option value="">Seleccione....</option>
						<?php 
							while ($tipo = pg_fetch_assoc($tipoMovimientos)){
								if($tipo['codigo'] == $eventoSanitario['movimiento_animal']){
									echo '<option value="' . $tipo['codigo'] . '" selected="selected" >' . $tipo['nombre'] . '</option>';
								}else{
									echo '<option value="' . $tipo['codigo'] . '">' . $tipo['nombre'] . '</option>';
								}
							}
						?>
					</select> 
			</div>
			
			<div>
				<button type="submit" class="guardar" id="guardarMovimientos">Guardar</button>		
			</div>
		
		</fieldset>
	
	</form>

	<form id="nuevaMovimientoIngresos" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarIngresos" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />	
		<input type="hidden" id="numeroVisita" name="numeroVisita" value="<?php echo $eventoSanitario['num_inspeccion']; ?>"/>	
	
			<fieldset id="ingresos">
				<legend>Ingresos</legend>
				
				<input type="hidden" id="tipoMovimientoIngreso" name="tipoMovimientoIngreso" /> 
				<input type="hidden" id="nombreTipoMovimientoIngreso" name="nombreTipoMovimientoIngreso" />
				
				
				<div data-linea="10">
				<label>Provincia</label>
					<select id="provinciaMovimimentoIngreso" name="provinciaMovimimentoIngreso" required="required">
						<option value="">Provincia....</option>
						<?php 
							$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
							foreach ($provincias as $provincia){
								echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
							}
						?>
					</select> 
					
					<input type="hidden" id="nombreProvinciaMovimientoIngreso" name="nombreProvinciaMovimientoIngreso"/>
					
				</div>
				
				<div data-linea="10">
					<label>Cantón</label>
						<select id="cantonMovimientoIngreso" name="cantonMovimientoIngreso" disabled="disabled" required="required">
						</select>
						
						<input type="hidden" id="nombreCantonMovimientoIngreso" name="nombreCantonMovimientoIngreso"/>
				</div>
				
				<div data-linea="11">	
					<label>Parroquia</label>
					<select id="parroquiaMovimientoIngreso" name="parroquiaMovimientoIngreso" disabled="disabled" required="required">
					</select>
					
					<input type="hidden" id="nombreParroquiaMovimientoIngreso" name="nombreParroquiaMovimientoIngreso"/>
				</div>
				
				<div data-linea="11">
					<label>Especie:</label>
						<select id="especieMovimientoIngreso" name="especieMovimientoIngreso" required="required">
							<option value="">Seleccione....</option>
							<?php 
								while ($especie = pg_fetch_assoc($especieMovimientos)){
								echo '<option value="' . $especie['codigo'] . '">' . $especie['nombre'] . '</option>';
							}
							?>
						</select> 
						<input type="hidden" id="nombreEspecieIngreso" name="nombreEspecieIngreso" value="<?php echo $eventoSanitario['oficina'];?>"/>
				</div>
				
				<div data-linea="12">
					<label>Propietario:</label>
					<input type="text" id="propietarioMovimientoIngreso" name="propietarioMovimientoIngreso" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
				</div>
				
				<div data-linea="13">
					<label>Finca - feria, etc:</label>
					<input type="text" id="fincaMovimientoIngreso" name="fincaMovimientoIngreso" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
				</div>
				
				<div data-linea="14">
					<label>Fecha:</label>
					<input type="text" id="fechaMovimientoIngreso" name="fechaMovimientoIngreso" required="required"/>
				</div>
				
				<div data-linea="14">
					<label>Num. animales ingresados:</label>
					<input type="number" id="numMovimientoIngreso" name="numMovimientoIngreso" required="required"/>
				</div>

				<div>
					<button type="submit" class="mas">Agregar</button>		
				</div>
			</fieldset>
		</form>
		
		<fieldset id="detalleIngresosFS">
			<legend>información Ingresos de animales</legend>
			<table id="detalleIngresos">
				<thead>
					<tr>
						<th width="15%">Visita</th>
						<th width="15%">Provincia</th>
						<th width="15%">Cantón</th>
						<th width="15%">Parroquia</th>
						<th width="15%">Especie</th>
						<th width="15%">Propietario</th>
						<th width="15%">Finca - feria, etc.</th>
						<th width="15%">Fecha</th>
						<th width="15%">Num Animales Ingreso</th>
						<th width="5%">Eliminar</th>
					</tr>
				</thead>
				<?php 
					while ($ingreso = pg_fetch_assoc($ingresos)){
						echo $cpco->imprimirLineaIngresos(	$ingreso['id_ingreso'], 
															$ingreso['id_evento_sanitario'],
															$ingreso['numero_visita'],
															$ingreso['nombre_provincia'], 
															$ingreso['nombre_canton'], 
															$ingreso['nombre_parroquia'], 
															$ingreso['nombre_especie'], 
															$ingreso['propietario_movimiento'], 
															$ingreso['finca_movimiento'], 
															$ingreso['fecha_movimiento'], 
															$ruta,
															$ingreso['numero_animales']);
					}
				?>
			</table>
		</fieldset>
			
		<fieldset id="detalleIngresosConsultaFS">
			<legend>información Ingresos de animales</legend>
			<table id="detalleIngresos">
				<thead>
					<tr>
						<th width="15%">Visita</th>
						<th width="15%">Provincia</th>
						<th width="15%">Cantón</th>
						<th width="15%">Parroquia</th>
						<th width="15%">Especie</th>
						<th width="15%">Propietario</th>
						<th width="15%">Finca - feria, etc.</th>
						<th width="15%">Fecha</th>
						<th width="15%">Num Animales Ingreso</th>
					</tr>
				</thead>
				
				<?php 
					while ($ingreso = pg_fetch_assoc($ingresosConsulta)){
						echo $cpco->imprimirLineaIngresosConsulta(	$ingreso['id_ingreso'], 
																	$ingreso['id_evento_sanitario'],
																	$ingreso['numero_visita'],
																	$ingreso['nombre_provincia'], 
																	$ingreso['nombre_canton'], 
																	$ingreso['nombre_parroquia'], 
																	$ingreso['nombre_especie'], 
																	$ingreso['propietario_movimiento'], 
																	$ingreso['finca_movimiento'], 
																	$ingreso['fecha_movimiento'], 
																	$ruta,
																	$ingreso['numero_animales']);
					}
				?>
			</table>
		</fieldset>

	<form id="nuevaMovimientoEgresos" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarEgresos" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		<input type="hidden" id="numeroVisita" name="numeroVisita" value="<?php echo $eventoSanitario['num_inspeccion']; ?>"/>
				
			<fieldset id="egresos">
				<legend>Egresos</legend>
				
				<input type="hidden" id="tipoMovimientoEgreso" name="tipoMovimientoEgreso" />
				<input type="hidden" id="nombreTipoMovimientoEgreso" name="nombreTipoMovimientoEgreso" />
				
				<div data-linea="15">
					<label>Provincia</label>
						<select id="provinciaMovimimentoEgreso" name="provinciaMovimimentoEgreso" required="required">
							<option value="">Provincia....</option>
							<?php 
								$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
								foreach ($provincias as $provincia){
									echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
								}
							?>
						</select> 
					
					<input type="hidden" id="nombreProvinciaMovimientoEgreso" name="nombreProvinciaMovimientoEgreso"/>
					
				</div>
				
			<div data-linea="15">
				<label>Cantón</label>
					<select id="cantonMovimientoEgreso" name="cantonMovimientoEgreso" disabled="disabled" required="required">
					</select>
					
					<input type="hidden" id="nombreCantonMovimientoEgreso" name="nombreCantonMovimientoEgreso"/>
				</div>
				
				<div data-linea="16">	
				<label>Parroquia</label>
					<select id="parroquiaMovimientoEgreso" name="parroquiaMovimientoEgreso" disabled="disabled" required="required">
					</select>
					
					<input type="hidden" id="nombreParroquiaMovimientoEgreso" name="nombreParroquiaMovimientoEgreso"/>
				</div>
				
				<div data-linea="16">
					<label>Especie:</label>
						<select id="especieMovimientoEgreso" name="especieMovimientoEgreso" required="required">
							<option value="">Seleccione....</option>
							<?php 
								while ($especie = pg_fetch_assoc($especieMovimientosEgreso)){
								echo '<option value="' . $especie['codigo'] . '">' . $especie['nombre'] . '</option>';
							}
							?>
						</select> 
						<input type="hidden" id="nombreEspecieEgresos" name="nombreEspecieEgresos" />
				</div>
				
				<div data-linea="17">
					<label>Propietario:</label>
					<input type="text" id="PropietarioMovimientoEgreso" name="PropietarioMovimientoEgreso" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
				</div>
				
				<div data-linea="18">
					<label>Finca - feria, etc:</label>
					<input type="text" id="fincaMovimientoEgreso" name="fincaMovimientoEgreso" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
				</div>

				<div data-linea="19">
					<label>Fecha:</label>
					<input type="text" id="fechaMovimientoEgreso" name="fechaMovimientoEgreso" required="required"/>
				</div>

				<div data-linea="19">
					<label>Num. animales egresados:</label>
					<input type="text" id="numMovimientoEgreso" name="numMovimientoEgreso" required="required"/>
				</div>
				
				<div>
					<button type="submit" class="mas">Agregar</button>		
				</div>
			</fieldset>
		</form>
		
		<fieldset id="detalleEgresosFS">
			<legend>información Egresos de animales</legend>
			<table id="detalleEgresos">
				<thead>
					<tr>
						<th width="15%">Visita</th>
						<th width="15%">Provincia</th>
						<th width="15%">Cantón</th>
						<th width="15%">Parroquia</th>
						<th width="15%">Especie</th>
						<th width="15%">Propietario</th>
						<th width="15%">Finca - feria, etc.</th>
						<th width="15%">Fecha</th>
						<th width="15%">Num Animales Egreso</th>
						<th width="5%">Eliminar</th>
					</tr>
				</thead>
				<?php 
					while ($egreso = pg_fetch_assoc($egresos)){
						echo $cpco->imprimirLineaEgresos(	$egreso['id_egreso'], 
															$egreso['id_evento_sanitario'],
															$egreso['numero_visita'],
															$egreso['nombre_provincia'], 
															$egreso['nombre_canton'], 
															$egreso['nombre_parroquia'], 
															$egreso['nombre_especie'], 
															$egreso['propietario_movimiento'], 
															$egreso['finca_movimiento'], 
															$egreso['fecha_movimiento'], 
															$ruta,
															$egreso['numero_animales']);
					}
				?>
			</table>
		</fieldset>
		
		<fieldset id="detalleEgresosConsultaFS">
			<legend>información Egresos de animales</legend>
			<table id="detalleEgresos">
				<thead>
					<tr>
						<th width="15%">Visita</th>
						<th width="15%">Provincia</th>
						<th width="15%">Cantón</th>
						<th width="15%">Parroquia</th>
						<th width="15%">Especie</th>
						<th width="15%">Propietario</th>
						<th width="15%">Finca - feria, etc.</th>
						<th width="15%">Fecha</th>
						<th width="15%">Num Animales Egreso</th>
					</tr>
				</thead>
				<?php 
					while ($egreso = pg_fetch_assoc($egresosConsulta)){
						echo $cpco->imprimirLineaEgresosConsulta(	$egreso['id_egreso'], 
																	$egreso['id_evento_sanitario'],
																	$egreso['numero_visita'],
																	$egreso['nombre_provincia'], 
																	$egreso['nombre_canton'], 
																	$egreso['nombre_parroquia'], 
																	$egreso['nombre_especie'], 
																	$egreso['propietario_movimiento'], 
																	$egreso['finca_movimiento'], 
																	$egreso['fecha_movimiento'], 
																	$ruta,
																	$egreso['numero_animales']);
					}
				?>
			</table>
		</fieldset>
</div>

<!--Origenes, Medidas, fotografias, mapa, observaciones -->
<div class="pestania">
	<h2>Orígenes, Medidas, fotografías, mapa, observaciones</h2>
	
	<form id="nuevaOrigenes" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarOrigenes" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		<input type="hidden" id="numeroVisita" name="numeroVisita" value="<?php echo $eventoSanitario['num_inspeccion']; ?>"/>	
		
			<fieldset>
				<legend>Origen probable de la enfermedad</legend>
				
				<div data-linea="3">
					<label>Número de visita:</label>
						<?php echo $eventoSanitario['num_inspeccion']; ?>
				</div>
				
				<div data-linea="1">
					<label>Origen probable de la enfermedad:</label>
					<input type="text" id="origenEnfermedad" name="origenEnfermedad" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$" required="required" value="<?php echo $origenProbable['origen_enfermedad']; ?>" />
				</div>
			
				<div data-linea="2">
					<label>Cuarentena del predio?:</label>
						<select id="cuarentenaPredio" name="cuarentenaPredio" required="required">
							<option value="">Seleccione....</option>
							<option value="Si" <?php echo ($origenProbable['cuarentena_predio']=='Si'?'selected':''); ?>>Si</option>
							<option value="No" <?php echo ($origenProbable['cuarentena_predio']=='No'?'selected':''); ?>>No</option>
						</select>
				</div>
				
								
				<div data-linea="6">
					<label id="lNumActa">Número de acta:</label>
					<input type="text" id="numeroActa" name="numeroActa" maxlength="16" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$" required="required" value="<?php echo $origenProbable['numero_acta']; ?>" />
				</div>
				
				
				<div data-linea="4">
					<label>Medidas sanitarias implementadas (Describa):</label>
					<input type="text" id="medidasSanitarias" name="medidasSanitarias" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$" required="required" value="<?php echo $origenProbable['medidas_sanitarias']; ?>" />
				</div>
			
				<div data-linea="5">
					<label>Observaciones:</label>
					<input type="text" id="observacionesOrigenes" name="observacionesOrigenes" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$" required="required" value="<?php echo $origenProbable['observaciones']; ?>" />
				</div>
			
			<div>
				<button type="submit" class="guardar">Guardar</button>		
			</div>
			
			</fieldset>
			
			
		</form>
				
		<fieldset id="adjuntosMapa">
			<legend>Adjuntar Documentos</legend>
			<div data-linea="1">
				<label>Acta inicio de cuarentena, Mapa, Fotos:</label>
				<?php echo ($origenProbable['ruta_fotos']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$origenProbable['ruta_fotos'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Documentos cargados</a>')?>
			</div>
				
			<form id="subirArchivoDocumentos" action="aplicaciones/seguimientoEventosSanitarios/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergenteInforme" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
				<input type="hidden" id="numeroVisita" name="numeroVisita" value="<?php echo $eventoSanitario['num_inspeccion']; ?>"/>
				<input type="file" name="archivo" id="archivoDocumentos" accept="application/pdf" /> 
				<input type="hidden" name="id" value="<?php echo $eventoSanitario['id_evento_sanitario'];?>" />
				<input type="hidden" name="aplicacion" value="archivoDocumentos" /> 
				<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir archivo</button>
			</form>
			<iframe name="ventanaEmergenteInforme" class="ventanaEmergente"></iframe>
		</fieldset>
			
</div>

<div class="pestania">
	<h2>Cierre de Primera Visita</h2>

	<form id="cerrarPrimeraVisita" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarCierrePrimeraVisitaTecnico" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		<input type="hidden" id="numeroVisita" name="numeroVisita" value="<?php echo $eventoSanitario['num_inspeccion']; ?>"/>		
		<input type='hidden' id='numeroSolicitud' name='numeroSolicitud' value="<?php echo $eventoSanitario['numero_formulario'];?>" />
                <input type='hidden' id='movimientoAnimalFinal' name='movimientoAnimalFinal' value="<?php echo $eventoSanitario['movimiento_animal'];?>" />
		
		<fieldset>
			<legend>Resultado del Proceso</legend>
			
			<div data-linea="53">
				<label>Resultado:</label>
					<select id="resultado" name="resultado" required="required" >
						<option value="">Seleccione....</option>
						<option value="visita">Siguiente Visita</option>
						<option value="visitaCierre">Visita de Cierre</option>
						<option value="tomaMuestras">Toma de Muestras</option>
						<!-- option value="rechazado">Rechazado</option-->
					</select>					 					
			</div>
			
			<div data-linea="54">
				<label id="lLaboratorioMuestras">Laboratorio para Análisis:</label>
					<select id="laboratorioMuestras" name="laboratorioMuestras">
						<option value="">Laboratorio....</option>
						
					<?php 
						while($fila = pg_fetch_assoc($laboratorios1)){
							echo "<option value=".$fila['id_laboratorio'].">".$fila['nombre']."</option>";
						}
					?>
					</select>
				
				<input type="hidden" id="nombreLaboratorioMuestras" name="nombreLaboratorioMuestras"  /> 
			</div>
			
			<!-- >div data-linea="55	">
				<label id="lFechaInspeccion" >Fecha inspección:</label>
				<input type="text" id="fechaInspeccion" name="fechaInspeccion" />
			</div-->
			
			<div data-linea="56">
				<label>Observaciones:</label>
				<input type="text" id="observaciones" name="observaciones" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$"  />
			</div>

		</fieldset>
	
		<div data-linea="55">
			<button id="guardarCierre" type="submit" class="guardar">Guardar</button>
		</div>
	</form>
</div>


<script type="text/javascript">

	var usuario = <?php echo json_encode($usuario); ?>;
	var estado= <?php echo json_encode($eventoSanitario['estado']); ?>;
	var perfil= <?php echo json_encode($perfilAdmin); ?>;
	var array_provincia= <?php echo json_encode($provincias); ?>;
	var array_provincia1= <?php echo json_encode($provincias1); ?>;
	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_parroquia= <?php echo json_encode($parroquias); ?>;
	
	$("document").ready(function(){
		distribuirLineas();	
		construirValidador();
		construirAnimacion($(".pestania"));
		
		$("#actualizacion").hide();
		
		$("#ingresos").hide();
		$("#detalleIngresosFS").hide();
		$("#egresos").hide();
		$("#detalleEgresosFS").hide();

		$("#nombreTipoMuestra").hide();
		
		$('#lCuantosPrediosAfectados').hide();
		$('#cuantosPrediosAfectados').hide();
		$('#lNumActa').hide();
		$('#numeroActa').hide();
				
		$('#detalleExplotacionConsultaFS').hide();
		$('#detalleExplotacionAvesConsulta').hide();
		$('#informacionExplotacionAvesConsultaFS').hide();
		$('#detalleCronologiaConsultaFS').hide();
		$('#detalleEspecieAfectadaConsultaFS').hide();
		$('#detalleVacunacionAftosaConsultaFS').hide();
		$('#detalleVacunacionConsultaFS').hide();
		$('#detalleVacunacionAvesConsultaFS').hide();

		$('#lLaboratorioMuestras').hide();
		$('#laboratorioMuestras').hide();
		$('#nombreLaboratorioMuestras').hide();
		$('#lFechaInspeccion').hide();
		$('#fechaInspeccion').hide();

		//$('#detalleMuestraConsultaFS').hide();
		$('#detalleOrigenConsultaFS').hide();
		$('#detallePoblacionConsultaFS').hide();
		$('#detallePoblacionAvesConsultaFS').hide();
		$('#detalleIngresosConsultaFS').hide();
		$('#detalleEgresosConsultaFS').hide();
		$('#detalleCronologiaFinalConsultaFS').hide();
		$('#detalleDiagnosticoFinalConsultaFS').hide();
		$('#detallePoblacionFinalConsultaFS').hide();
		$('#detallePoblacionFinalAvesConsultaFS').hide();
		$('#detalleVacunacionFinalConsultaFS').hide();
		$('#detalleProcedimientoAvesConsultaFS').hide();
		$('#detalleMovimientoAvesConsultaFS').hide();
		
		$('#nombreEspecieAfectada').hide();
		$('#nombreDiagnosticoFinal').hide();
		$('#nombreEspecieFinal').hide();
		$('#nombreCategoriaFinal').hide();

		$('#colecta').hide();
		$('#nuevaMuestra').hide();
		$('#detalleMuestraFS').hide();
		$('#detalleMuestraFSConsulta').hide();		

		$("#fechaSiguienteInspeccion").datepicker({
			changeMonth: true,
			 changeYear: true
		});

		$("#fechaColectaMuestra").datepicker({
			changeMonth: true,
		    changeYear: true,
		    dateFormat: 'yy-mm-dd',
		    onSelect: function(dateText, inst) {

		    	var fecha=new Date($('#fechaSiguienteVisita').datepicker('getDate'));
		    	
			  	$('#fechaColectaMuestra').datepicker('option', 'minDate', $("#fechaSiguienteVisita" ).val()); 
			  	
				
		    }
		});
		
		$("#fechaEnvioMuestra").datepicker({
			changeMonth: true,
			 changeYear: true
		});

		$("#fechaInspeccion").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaOrigen").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaMovimientoIngreso").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaMovimientoEgreso").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaLlegadaMovimientoAves").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaMovimientoAves").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaCronologiaFinal").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		acciones("#nuevaExplotacion","#detalleExplotacion");
		acciones("#infoMuestras","#detalleMuestraConsultaFS");
		acciones("#nuevaOrigenAnimales","#detalleOrigenAnimales");
		acciones("#nuevaPoblacionExistente","#detallePoblacion");
		acciones("#nuevaPoblacionExistenteAves","#detallePoblacionAves");
		acciones("#nuevaMovimientoIngresos","#detalleIngresos");
		acciones("#nuevaMovimientoEgresos","#detalleEgresos");
		acciones("#nuevaMovimientoAves","#detalleMovimientoAves");

		if(usuario == '0'){
			$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#botonGuardar").attr("disabled", "disabled");
		}
		
		if(estado == 'Cerrado'){
			
			
			if(perfil != false){
				$("#modificar").show();
				$("#nuevaExplotacion").show();
					$('#detalleExplotacionFS').hide();
					$('#detalleExplotacion').show();
				/*$("#nuevaPlanificacionInspeccionNotificacionEventoSanitario").show();
					$('#planificarNuevaInspeccionCerrado').hide();
					$("#NuevaPlanificarNuevaInspeccion").hide();
					$("#planificarNuevaInspeccion").hide();
					$("#planificarEventoSanitario").hide();
					$("#planificarEventoSanitarioFS").show();
					$("#guardarNuevaInspeccion").hide();
					$("#subirArchivoInforme").hide();*/
			}else{
				$("#modificar").hide();
				$("#nuevaExplotacion").hide();
					$('#detalleExplotacionFS').show();
					$('#detalleExplotacion').hide();
				/*$("#nuevaPlanificacionInspeccionNotificacionEventoSanitario").show();
					$('#planificarNuevaInspeccionCerrado').show();
					$("#NuevaPlanificarNuevaInspeccion").hide();
					$("#planificarNuevaInspeccion").hide();
					$("#planificarEventoSanitario").hide();
					$("#planificarEventoSanitarioFS").show();
					$("#guardarNuevaInspeccion").hide();
					$("#subirArchivoInforme").hide();*/
			}
		}
		
	if(estado == 'Cerrado'){
			if(perfil != false){
				$("#modificar").show();
				$("#subirArchivoInforme").hide();
			}else{
				$("#modificar").hide();
				$("#subirArchivoInforme").hide();
			}
		}
	});
	
	//Validación y Guardado
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	







	
	$("#colectaMaterial").change(function(event){
		if($("#colectaMaterial option:selected").val()=='No'){
			$('#colecta').hide();
			$('#nuevaMuestra').hide();
			$('#detalleMuestraFS').hide();
			$('#detalleMuestraFSConsulta').hide();

			$('#pruebasMuestra').removeAttr("required");
			$('#laboratorioMuestra').removeAttr("required");
			$('#especieMuestra').removeAttr("required");
			$('#tipoMuestra').removeAttr("required");
			$('#numeroMuestras').removeAttr("required");
			$('#fechaColectaMuestra').removeAttr("required");
			$('#horaColectaMuestra').removeAttr("required");
			$('#fechaEnvioMuestra').removeAttr("required");
			$('#horaEnvioMuestra').removeAttr("required");				
        }else{
        	$('#colecta').show();
    		$('#nuevaMuestra').show();
    		$('#detalleMuestraFS').show();
    		$('#detalleMuestraFSConsulta').show();

    		$('#pruebasMuestra').attr("required", "required");
			$('#laboratorioMuestra').attr("required", "required");
			$('#especieMuestra').attr("required", "required");
			$('#tipoMuestra').attr("required", "required");
			$('#numeroMuestras').attr("required", "required");
			$('#fechaColectaMuestra').attr("required", "required");
			$('#horaColectaMuestra').attr("required", "required");
			$('#fechaEnvioMuestra').attr("required", "required");
			$('#horaEnvioMuestra').attr("required", "required");
        }
	});

	$("#laboratorioMuestra").change(function(){
    	$("#nombreLaboratorioMuestra").val($("#laboratorioMuestra option:selected").text());
	});

	$("#especieMuestra").change(function(){
    	$("#nombreEspecieMuestra").val($("#especieMuestra  option:selected").text());
	});

	$("#tipoMuestra").change(function(){

		if($("#tipoMuestra option:selected").val()!='0'){
        	$('#nombreTipoMuestra').hide();
        	$("#nombreTipoMuestra").val($("#tipoMuestra option:selected").text());
        }else{
        	$("#nombreTipoMuestra").val('');
    	    $('#nombreTipoMuestra').show();
        }
    	
	});

	function agregarAnalisis(){  
    	if($("#especieMuestra").val()!="" && $("#tipoMuestra").val()!=""){

    		if($("#detalleVacunacionAftosa #r_"+$("#especieMuestra").val()+$("#pruebasMuestra").val()+$("#tipoMuestra").val()).length==0){
   				$("#detalleVacunacionAftosa").append("<tr id='r_"+$("#especieMuestra").val()+$("#pruebasMuestra").val()+$("#tipoMuestra").val()+"'><td>"+$("#especieMuestra  option:selected").text()+"</td><td>"+$("#pruebasMuestra  option:selected").text()+"</td><td>"+$("#tipoMuestra  option:selected").text()+"</td><td>"+$("#numeroMuestras").val()+"</td><td>"+$("#fechaColectaMuestra").val()+"</td><td>"+$("#fechaEnvioMuestra").val()+"</td><td><input id='arrayMuestra' name='arrayEspecieMuestra[]' value='"+$("#especieMuestra option:selected").val()+"' type='hidden'><input id='arrayNombreEspecieMuestra' name='arrayNombreEspecieMuestra[]' value='"+$("#especieMuestra option:selected").text()+"' type='hidden'><input id='arrayPruebasMuestra' name='arrayPruebasMuestra[]' value='"+$("#pruebasMuestra option:selected").val()+"' type='hidden'><input id='arrayNombrePruebasMuestra' name='arrayNombrePruebasMuestra[]' value='"+$("#pruebasMuestra option:selected").text()+"' type='hidden'><input id='arrayTipoMuestra' name='arrayTipoMuestra[]' value='"+$("#tipoMuestra option:selected").val()+"' type='hidden'><input id='arrayNombreTipoMuestra' name='arrayNombreTipoMuestra[]' value='"+$("#nombreTipoMuestra").val()+"' type='hidden'><input id='arrayNumeroMuestras' name='arrayNumeroMuestras[]' value='"+$("#numeroMuestras").val()+"' type='hidden'><input id='arrayFechaColectaMuestra' name='arrayFechaColectaMuestra[]' value='"+$("#fechaColectaMuestra").val()+"' type='hidden'><input id='arrayHoraColectaMuestra' name='arrayHoraColectaMuestra[]' value='"+$("#horaColectaMuestra").val()+"' type='hidden'><input id='arrayFechaEnvioMuestra' name='arrayFechaEnvioMuestra[]' value='"+$("#fechaEnvioMuestra").val()+"' type='hidden'><input id='arrayHoraEnvioMuestra' name='arrayHoraEnvioMuestra[]' value='"+$("#horaEnvioMuestra").val()+"' type='hidden'><button type='button' onclick='quitarAnalisis(\"#r_"+$("#especieMuestra").val()+$("#tipoMuestra").val()+"\")' class='menos'>Quitar</button></td></tr>");
    		}
    		
    	}
    }

	function quitarAnalisis(fila){
		$("#detalleVacunacionAftosa tr").eq($(fila).index()).remove();
	}	

	$("#nuevaColectaMaterial").submit(function(event){

		$("#nuevaColectaMaterial").attr('data-opcion', 'guardarColectaMaterial');
	    $("#nuevaColectaMaterial").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#colectaMaterial").val())){
			error = true;
			$("#colectaMaterial").addClass("alertaCombo");
		}
		
		if(!$.trim($("#razonesMuestra").val()) || !esCampoValido("#razonesMuestra")){
			error = true;
			$("#razonesMuestra").addClass("alertaCombo");
		}

		if($("#colectaMaterial option:selected").val() == 'Si'){
			if(!$.trim($("#pruebasMuestra").val()) || !esCampoValido("#pruebasMuestra")){
				error = true;
				$("#pruebasMuestra").addClass("alertaCombo");
			}
	
			if(!$.trim($("#laboratorioMuestra").val())){
				error = true;
				$("#laboratorioMuestra").addClass("alertaCombo");
			}
		}
				
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			//abrir($(this),event,false);
			ejecutarJson($(this));
		}
	});

	$("#origenAnimal").change(function(){
    	$("#nombreOrigenAnimal").val($("#origenAnimal option:selected").text());
	});

	$("#paisOrigen").change(function(event){
    	if($("#paisOrigen option:selected").text() == "Ecuador"){
        	sprovincia ='0';
	    	sprovincia = '<option value="">Seleccione...</option>';
		    for(var i=0;i<array_provincia.length;i++){
			    if ($("#paisOrigen").val()==array_provincia[i]['padre']){
			    	sprovincia += '<option value="'+array_provincia[i]['codigo']+'">'+array_provincia[i]['nombre']+'</option>';
				}
		   	}
    	}else{
    		sprovincia = '<option value="">Seleccione...</option>';
    		sprovincia += '<option value="0">No Aplica</option>';
    	}
    	
	    $('#provinciaOrigen').html(sprovincia);
	    $("#provinciaOrigen").removeAttr("disabled");
	    $("#nombrePaisOrigen").val($("#paisOrigen option:selected").text());	
	});

	$("#provinciaOrigen").change(function(){
    	$("#nombreProvinciaOrigen").val($("#provinciaOrigen option:selected").text());
	});

	
	$("#nuevaPrediosAfectados").submit(function(event){

		$("#nuevaPrediosAfectados").attr('data-opcion', 'guardarPrediosAfectados');
	    $("#nuevaPrediosAfectados").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#prediosAfectados").val())){
			error = true;
			$("#prediosAfectados").addClass("alertaCombo");
		}
		
		if(!$.trim($("#cuantosPrediosAfectados").val())){
			error = true;
			$("#cuantosPrediosAfectados").addClass("alertaCombo");
		}
				
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

	$("#nuevaMovimientoAnimales").submit(function(event){

		$("#nuevaMovimientoAnimales").attr('data-opcion', 'guardarMovimientoAnimales');
	    $("#nuevaMovimientoAnimales").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#movimientoAnimal").val())){
			error = true;
			$("#movimientoAnimal").addClass("alertaCombo");
		}
				
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

	$("#provinciaMovimimentoIngreso").change(function(event){
    	scanton ='0';
		scanton = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provinciaMovimimentoIngreso").val()==array_canton[i]['padre']){
		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#cantonMovimientoIngreso').html(scanton);
	    $("#cantonMovimientoIngreso").removeAttr("disabled");
	    $("#nombreProvinciaMovimientoIngreso").val($("#provinciaMovimimentoIngreso option:selected").text());	
	});

    $("#cantonMovimientoIngreso").change(function(){
    	$("#nombreCantonMovimientoIngreso").val($("#cantonMovimientoIngreso option:selected").text());
        
		sparroquia ='0';
		sparroquia = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#cantonMovimientoIngreso").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'" >'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}

	    $('#parroquiaMovimientoIngreso').html(sparroquia);
		$("#parroquiaMovimientoIngreso").removeAttr("disabled");
	});

    $("#parroquiaMovimientoIngreso").change(function(){
    	$("#nombreParroquiaMovimientoIngreso").val($("#parroquiaMovimientoIngreso option:selected").text());
	});

    $("#tipoMovimientoIngreso").change(function(){
    	$("#nombreTipoMovimientoIngreso").val($("#tipoMovimientoIngreso option:selected").text());
	});

    $("#especieMovimientoIngreso").change(function(){
    	$("#nombreEspecieIngreso").val($("#especieMovimientoIngreso option:selected").text());
	});

	$("#provinciaMovimimentoEgreso").change(function(event){
    	scanton ='0';
		scanton = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provinciaMovimimentoEgreso").val()==array_canton[i]['padre']){
		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#cantonMovimientoEgreso').html(scanton);
	    $("#cantonMovimientoEgreso").removeAttr("disabled");
	    $("#nombreProvinciaMovimientoEgreso").val($("#provinciaMovimimentoEgreso option:selected").text());	
	});

    $("#cantonMovimientoEgreso").change(function(){
    	$("#nombreCantonMovimientoEgreso").val($("#cantonMovimientoEgreso option:selected").text());
        
		sparroquia ='0';
		sparroquia = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#cantonMovimientoEgreso").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'" >'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}

	    $('#parroquiaMovimientoEgreso').html(sparroquia);
		$("#parroquiaMovimientoEgreso").removeAttr("disabled");
	});

    $("#parroquiaMovimientoEgreso").change(function(){
    	$("#nombreParroquiaMovimientoEgreso").val($("#parroquiaMovimientoEgreso option:selected").text());
	});

    $("#tipoMovimientoEgreso").change(function(){
    	$("#nombreTipoMovimientoEgreso").val($("#tipoMovimientoEgreso option:selected").text());
	});

    $("#especieMovimientoEgreso").change(function(){
    	$("#nombreEspecieEgresos").val($("#especieMovimientoEgreso option:selected").text());
	});



    $("#nuevaOrigenes").submit(function(event){

		$("#nuevaOrigenes").attr('data-opcion', 'guardarOrigenes');
	    $("#nuevaOrigenes").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#origenEnfermedad").val())){
			error = true;
			$("#origenEnfermedad").addClass("alertaCombo");
		}
		
		if(!$.trim($("#cuarentenaPredio").val())){
			error = true;
			$("#cuarentenaPredio").addClass("alertaCombo");
		}

		if(!$.trim($("#numeroActa").val())){
			error = true;
			$("#numeroActa").addClass("alertaCombo");
		}

		if(!$.trim($("#medidasSanitarias").val())){
			error = true;
			$("#medidasSanitarias").addClass("alertaCombo");
		}

		if(!$.trim($("#observacionesOrigenes").val())){
			error = true;
			$("#observacionesOrigenes").addClass("alertaCombo");
		}
				
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

    $("#archivoDocumentos").click(function(){
    	$("#subirArchivoDocumentos button").removeAttr("disabled");
    });

	$("#archivoImagen").click(function(){
    	$("#subirArchivoImagen button").removeAttr("disabled");
    });

	$("#laboratorioMuestras").change(function(){
    	$("#nombreLaboratorioMuestras").val($("#laboratorioMuestras option:selected").text());
	});



    $("#resultado").change(function(){
        if($("#resultado option:selected").val()=='tomaMuestras'){
        	$('#lLaboratorioMuestras').show();
    		$('#laboratorioMuestras').show();
    		$('#nombreLaboratorioMuestras').hide();
    		$('#laboratorioMuestras').attr('required','required');    

    		$('#lFechaInspeccion').hide();
    		$('#fechaInspeccion').hide();    	
    		$('#fechaInspeccion').removeAttr('required');
        }else{
        	$('#lLaboratorioMuestras').hide();
    		$('#laboratorioMuestras').hide();
    		$('#nombreLaboratorioMuestras').hide();
    		$('#laboratorioMuestras').removeAttr('required');

    		$('#lFechaInspeccion').show();
    		$('#fechaInspeccion').show();
    		$('#fechaInspeccion').attr('required','required');
        }
	});
	

	//Cierre y Envío a Revisión
	$("#cerrarPrimeraVisita").submit(function(event){
            
		$("#cerrarPrimeraVisita").attr('data-opcion', 'guardarCierrePrimeraVisitaTecnico');
	    $("#cerrarPrimeraVisita").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#resultado").val())){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}

		if(!$.trim($("#observaciones").val())){
			error = true;
			$("#observaciones").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

	$("#prediosAfectados").change(function(){
        if($("#prediosAfectados option:selected").val()=='Si'){
        	$('#lCuantosPrediosAfectados').show();
    		$('#cuantosPrediosAfectados').show();
    		$('#cuantosPrediosAfectados').val('');
    		$('#cuantosPrediosAfectados').attr('required','required');        	
        }else{
        	$('#lCuantosPrediosAfectados').hide();
    		$('#cuantosPrediosAfectados').hide();
    		$('#cuantosPrediosAfectados').val('0');
    		$('#cuantosPrediosAfectados').removeAttr('required');
        }
	});

	$("#especiePoblacion").change(function(event){
    	$("#nombreEspeciePoblacion").val($("#especiePoblacion option:selected").text());

		$('#nuevaPoblacionExistente').attr('data-destino','categoria');
		$('#nuevaPoblacionExistente').attr('data-opcion','combosEventoSanitario');
	    $('#opcion').val('buscarCategoria');
	    		
		abrir($("#nuevaPoblacionExistente"),event,false);  

		$('#nuevaPoblacionExistente').attr('data-destino','detalleItem');
		$('#nuevaPoblacionExistente').attr('data-opcion','guardarPoblacionExistente');  	
	});


	
	$("#movimientoAnimal").change(function(){
            $("#movimientoAnimalFinal").val($("#movimientoAnimal").val());
		$("#tipoMovimientoIngreso").val($("#movimientoAnimal option:selected").val());
		$("#nombreTipoMovimientoIngreso").val($("#movimientoAnimal option:selected").text());
    	
    	$("#tipoMovimientoEgreso").val($("#movimientoAnimal option:selected").val());
    	$("#nombreTipoMovimientoEgreso").val($("#movimientoAnimal option:selected").text());

    	if($("#movimientoAnimal option:selected").text()=='Ingresos'){
    		distribuirLineas();	
    		$("#ingresos").show();
    		$("#egresos").hide();
    		$("#guardarMovimientos").hide();

    		$("#detalleIngresosFS").show();
    		$("#detalleEgresosFS").hide();

    		$("#tipoMovimientoIngreso").val($("#movimientoAnimal option:selected").val());
    		$("#nombreTipoMovimientoIngreso").val($("#movimientoAnimal option:selected").text());
    		        	
        }else if($("#movimientoAnimal option:selected").text()=='Egresos'){
        	distribuirLineas();	
        	$("#ingresos").hide();
        	$("#egresos").show();
        	$("#guardarMovimientos").hide();

        	$("#detalleIngresosFS").hide();
    		$("#detalleEgresosFS").show();
    		
    		("#tipoMovimientoEgreso").val($("#movimientoAnimal option:selected").val());
        	$("#nombreTipoMovimientoEgreso").val($("#movimientoAnimal option:selected").text());
    		        	
        }else if($("#movimientoAnimal option:selected").text()=='Ingresos y Egresos'){
        	distribuirLineas();	
        	$("#ingresos").show();
    		$("#egresos").show();
    		$("#guardarMovimientos").hide();

    		$("#detalleIngresosFS").show();
    		$("#detalleEgresosFS").show();

    		$("#tipoMovimientoIngreso").val($("#movimientoAnimal option:selected").val());
    		$("#nombreTipoMovimientoIngreso").val($("#movimientoAnimal option:selected").text());
        	
        	$("#tipoMovimientoEgreso").val($("#movimientoAnimal option:selected").val());
        	$("#nombreTipoMovimientoEgreso").val($("#movimientoAnimal option:selected").text());
        	
        }else{
        	distribuirLineas();	
        	$("#ingresos").hide();
    		$("#egresos").hide();
    		$("#guardarMovimientos").show();

    		$("#detalleIngresosFS").hide();
    		$("#detalleEgresosFS").hide();
        }	
	});

	//Archivo informe
	$('button.subirArchivoAnexo').click(function (event) {
	
		var boton = $(this);
	    var archivo = boton.parent().find(".archivo");
	    var rutaArchivo = boton.parent().find(".rutaArchivo");
	    var extension = archivo.val().split('.');
	    var estado = boton.parent().find(".estadoCarga");
	    numero = Math.floor(Math.random()*100000000);
	    
	    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	        subirArchivo(archivo, $("#idEventoSanitario").val() +"_"+numero, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)); 
	    } else {
	        estado.html('Formato incorrecto, sólo se admite archivos en formato PDF');
	        archivo.val("");
	    }        
	});

	$("#cuarentenaPredio").change(function(){

		if($("#cuarentenaPredio option:selected").val()=='Si'){
        	$('#lNumActa').show();
        	$("#numeroActa").show();
        	$("#numeroActa").val('');
        }else{
        	$('#lNumActa').hide();
        	$("#numeroActa").hide();
        	$("#numeroActa").val('No Aplica');
        }
    	
	});
	
</script>