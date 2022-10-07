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
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	$oficinas = $cc->listarSitiosLocalizacion($conexion,'SITIOS');
	
	$idEventoSanitario = $_POST['id'];
		
	$eventoSanitario = pg_fetch_assoc($cpco->abrirEventoSanitario($conexion, $idEventoSanitario));
	$numVisita = $eventoSanitario['num_inspeccion'];
	$origenProbable = pg_fetch_assoc($cpco->abrirMedidaSanitaria($conexion, $idEventoSanitario, $numVisita));
	$resultadoLaboratorio = $cpco->abrirResultadoLaboratorio($conexion, $idEventoSanitario);
	//$resultadoLaboratorio = $cpco->abrirResultadoLaboratorioVisita($conexion, $idEventoSanitario, $numVisita);
	$laboratorios = $cpco->abrirCatalogoLaboratorios($conexion);
	$laboratorios1 = $cpco->abrirCatalogoLaboratorios($conexion);
	
	$numMuestraPrimeraVisita = pg_num_rows($cpco->listarMuestrasPorVisita($conexion, $idEventoSanitario, $numVisita));
	
	if($numMuestraPrimeraVisita != 0){
		$muestraPrimeraVisita = pg_fetch_assoc($cpco->listarMuestrasPorVisita($conexion, $idEventoSanitario, $numVisita));
	}
	
	/* catalogos*/
	$laboratoriosMuestra = $listaCatalogos->listarCatalogos($conexion,'LABORATORIO');
	$pruebaMuestras = $listaCatalogos->listarCatalogos($conexion,'PRUEBAS_LAB');
	$tiposMuestras = $listaCatalogos->listarCatalogos($conexion,'TIPO_MUESTRA');
	$especiesMuestras = $listaCatalogos->listarCatalogos($conexion,'ESPECIES');
	$tiposMuestras = $listaCatalogos->listarCatalogos($conexion,'TIPO_MUESTRA');
	$especiesPoblacion = $listaCatalogos->listarCatalogos($conexion,'ESPECIES');
	$categoriasPoblaciones = $listaCatalogos->listarCatalogos($conexion,'CATEGORIA');
	$especiesPoblacionAves = $listaCatalogos->listarCatalogos($conexion,'AVES');
	
	$provinciasOrigenes = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
	
	/*grid*/
	//$muestra  = $cpco->listarMuestrasDetalleInspeccion($conexion, $idEventoSanitario);
	$muestraConsulta  = $cpco->listarMuestrasDetalleInspeccion($conexion, $idEventoSanitario, $numVisita);	
	$poblaciones  = $cpco->listarPoblacionesInspeccion($conexion, $idEventoSanitario, $numVisita);
	$poblacionesConsulta  = $cpco->listarPoblacionesInspeccion($conexion, $idEventoSanitario, $numVisita);
	$poblacionesAves  = $cpco->listarPoblacionesAvesInspeccion($conexion, $idEventoSanitario, $numVisita);
	$poblacionesAvesConsulta  = $cpco->listarPoblacionesAvesInspeccion($conexion, $idEventoSanitario, $numVisita);
	
?>

<header>
	<h1>Eventos Sanitarios - Visitas</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>


<div class="pestania">

	<h2>Observaciones Visita Anterior</h2>
	
	<fieldset>
			
		<legend>Observaciones Remitidas para la visita</legend>
			<div data-linea="0">
				<label id="lObservacionAnterior">Observaciones / Indicaciones:</label>
				<?php echo $eventoSanitario['observaciones'];?> 
			</div>
	</fieldset>


	<h2>Resultados de Pruebas de Laboratorio</h2>
	
	<?php 
		if(pg_num_rows($resultadoLaboratorio) > 0){	
			while($resultado = pg_fetch_assoc($resultadoLaboratorio)){
				echo "
				<fieldset>
					<legend>Resultado del Proceso ".$resultado['num_inspeccion'] ."</legend>
					
					<div data-linea='53'>
						<label>Resultado: </label>". $resultado['resultado_analisis']."				 					
					</div>
					
					<div data-linea='54'>
						<label>Observaciones: </label>". $resultado['observaciones']."
					</div>
						
					<div data-linea='12'>
						<label>Informe de Laboratorio: </label>";
						echo ($resultado['archivo_informe']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$resultado['archivo_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe cargado</a>');
					echo "</div>";
				
				$resultadoLaboratorioDetalle = $cpco->abrirResultadoLaboratorioDetalle($conexion, $idEventoSanitario, $resultado['id_resultado_laboratorio']);
				
				echo "	<table id='detallePruebaLaboratorio'>
							<thead id='barraTitulo'>
								<tr id='titulo'>
								    <th width='15%'># Muestras</th>
									<th width='15%'># Positivos</th>
								    <th width='15%'>Fecha Informe</th>
								    <th width='15%'>Enfermedad</th>
								    <th width='15%'>Prueba de Laboratorio</th>
									<th width='15%'>Resultado Análisis</th>
								</tr>
							</thead>";
						
				while ($resultadoDetalle = pg_fetch_assoc($resultadoLaboratorioDetalle)){
					echo "<tr>
							<td width='30%'>" .
							$resultadoDetalle['cantidad_muestras'].
							"</td>
							<td width='30%'>" .
							$resultadoDetalle['num_positivos'].
							"</td>
							<td width='30%'>" .
							$resultadoDetalle['fecha_muestra'].
							"</td>
							<td width='30%'>" .
							$resultadoDetalle['enfermedad'].
							"</td>
							<td width='30%'>" .
							$resultadoDetalle['prueba_laboratorio'].
							"</td>
							<td width='30%'>" .
							$resultadoDetalle['resultado'].
							"</td>
							</tr>";
				}
							
				echo "		</table>
				</fieldset>";
			}
		}else{
			echo "<fieldset>
					<legend>Resultado del Proceso </legend>
						<label>No se disponen de resultados de laboratorio.</label>
					</fieldset>";
		}
	?>
</div>
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
					<input type="text" id="razonesMuestra" name="razonesMuestra" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$" required="required" <?php echo ($numMuestraPrimeraVisita!=0)?'value="'.$muestraPrimeraVisita['razones_muestra'].'" readonly="readonly"':'';?>/>
				</div>
				
		</fieldset>
		
		<fieldset id="colecta">
			<legend>Laboratorio</legend>
				
				<div data-linea="5">
					<label>Laboratorio:</label>
						<select id="laboratorioMuestra" name="laboratorioMuestra" required="required" <?php echo ($numMuestraPrimeraVisita!=0)?'disabled="disabled"':'';?>>
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

</div>

<!--Origenes, Medidas, fotografias, mapa, observaciones -->
<div class="pestania">
	<h2>Orígenes, Medidas, fotografías, observaciones</h2>
	
	<form id="nuevaOrigenes" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarOrigenes" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		<input type="hidden" id="numeroVisita" name="numeroVisita" value="<?php echo $eventoSanitario['num_inspeccion']; ?>"/>	
		
			<fieldset>
				<legend>Origen probable de la enfermedad</legend>
				<div data-linea="1">
					<!-- label>Origen probable de la enfermedad:</label-->
					<input type="hidden" id="origenEnfermedad" name="origenEnfermedad" required="required" value="No Aplica" />
				</div>
			
				<div data-linea="2">
					<!--label>Cuerentena del predio?:</label-->
					<input type="hidden" id="cuarentenaPredio" name="cuarentenaPredio" required="required" value="No" />
				</div>
				
				<div data-linea="6">
					<label>Número de visita:</label>
						<?php echo $eventoSanitario['num_inspeccion']; ?>
				</div>
				
				<div data-linea="3">
					<!--label>Número de acta:</label-->
					<input type="hidden" id="numeroActa" name="numeroActa" required="required" value="No Aplica" />
				</div>
				
				<div data-linea="4">
					<label>Medidas sanitarias implementadas (Describa):</label>
					<input type="text" id="medidasSanitarias" name="medidasSanitarias" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required" value="<?php echo $origenProbable['medidas_sanitarias']; ?>" />
				</div>
			
				<div data-linea="5">
					<label>Observaciones:</label>
					<input type="text" id="observacionesOrigenes" name="observacionesOrigenes" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required" value="<?php echo $origenProbable['observaciones']; ?>" />
				</div>
			
			<div>
				<button type="submit" class="guardar">Guardar</button>		
			</div>
			
			</fieldset>
			
			
		</form>
				
		<fieldset id="adjuntosMapa">
			<legend>Adjuntar Documentos</legend>
			<div data-linea="1">
				<label>Fotos:</label>
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
			
			<!--div data-linea="54	">
				<label id="lFechaInspeccion" >Fecha inspección:</label>
				<input type="text" id="fechaInspeccion" name="fechaInspeccion" />
			</div-->
			
			<div data-linea="55">
				<label>Observaciones:</label>
				<input type="text" id="observaciones" name="observaciones" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
			</div>

		</fieldset>
	
		<div data-linea="55">
			<button id="guardarCierre" type="submit" class="guardar">Guardar</button>
		</div>
	</form>
</div>

<script type="text/javascript">

	var usuario = <?php echo json_encode($usuario); ?>;
	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_parroquia= <?php echo json_encode($parroquias); ?>;
	var array_oficina= <?php echo json_encode($oficinas); ?>;
	var estado= <?php echo json_encode($eventoSanitario['estado']); ?>;
	var perfil= <?php echo json_encode($perfilAdmin); ?>;
	var canton= <?php echo json_encode($eventoSanitario['id_canton']); ?>;
	var idOficina= <?php echo json_encode($eventoSanitario['id_oficina']); ?>;



	$("document").ready(function(){
		distribuirLineas();	
		construirValidador();
		construirAnimacion($(".pestania"));
		$("#actualizacion").hide();

		$('#lLaboratorioMuestras').hide();
		$('#laboratorioMuestras').hide();
		$('#nombreLaboratorioMuestras').hide();

		$("#nombreTipoMuestra").hide();

		$('#colecta').hide();
		$('#nuevaMuestra').hide();
		$('#detalleMuestraFS').hide();
		$('#detalleMuestraFSConsulta').show();

		acciones("#infoMuestras","#detalleMuestraConsultaFS");
		
		$('#detalleExplotacionConsultaFS').hide();
		$('#detalleExplotacionAvesConsulta').hide();
		$('#informacionExplotacionAvesConsultaFS').hide();
		$('#detalleCronologiaConsultaFS').hide();
		$('#detalleEspecieAfectadaConsultaFS').hide();
		$('#detalleVacunacionAftosaConsultaFS').hide();
		$('#detalleVacunacionConsultaFS').hide();
		$('#detalleVacunacionAvesConsultaFS').hide();
		
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
		
		
		$("#fecha").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaDiagnostico").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaCronologia").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaVacunacionAftosa").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaVacunacion").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaVacunacionAves").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaInicioAves").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaFinAves").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaColectaMuestra").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaEnvioMuestra").datepicker({
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
		acciones("#nuevaPoblacionExistente","#detallePoblacion");
		acciones("#nuevaPoblacionExistenteAves","#detallePoblacionAves");
		

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
		
		if(canton!=-1){
			soficina ='0';
			soficina = '<option value="">Seleccione...</option>';
			for(var i=0;i<array_oficina.length;i++){
				if (canton==array_oficina[i]['padre']){
					if(idOficina == array_oficina[i]['codigo']){
						soficina += '<option selected value="'+array_oficina[i]['codigo']+'">'+array_oficina[i]['nombre']+'</option>';
					}else{
						soficina += '<option value="'+array_oficina[i]['codigo']+'">'+array_oficina[i]['nombre']+'</option>';
					}
				}
			}
			if(idOficina == 0){
				soficina += '<option selected value="0">Otro</option>';
			}else{
				soficina += '<option value="0">Otro</option>';
			}

			$('#oficina').html(soficina);
			$("#oficina").removeAttr("disabled");
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
	
	
	$("#modificar").click(function(){
		$('.bsig').attr("disabled","disabled");
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		$("#informacion").hide();
		$("#actualizacion").show();
	});
	
	//Validación y Guardado
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	$("#modificarEventoSanitario").submit(function(event){

		$("#modificarEventoSanitario").attr('data-opcion', 'modificarEventoSanitario');
	    $("#modificarEventoSanitario").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if(!$.trim($("#nombrePropietario").val()) || !esCampoValido("#nombrePropietario")){
			error = true;
			$("#nombrePropietario").addClass("alertaCombo");
		}
		
		if(!$.trim($("#cedulaPropietario").val()) || !esCampoValido("#cedulaPropietario")){
			error = true;
			$("#cedulaPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#telefonoPropietario").val()) || !esCampoValido("#telefonoPropietario")){
			error = true;
			$("#telefonoPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#celularPropietario").val()) || !esCampoValido("#celularPropietario")){
			error = true;
			$("#celularPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#correoElectronicoPropietario").val()) || !esCampoValido("#correoElectronicoPropietario")){
			error = true;
			$("#correoElectronicoPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#nombrePredio").val()) || !esCampoValido("#nombrePredio")){
			error = true;
			$("#nombrePredio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#extencionPredio").val()) || !esCampoValido("#extencionPredio")){
			error = true;
			$("#extencionPredio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#unidadMedida").val())){
			error = true;
			$("#unidadMedida").addClass("alertaCombo");
		}
		
		if(!$.trim($("#otroPredio").val())){
			error = true;
			$("#otroPredio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#bioseguridad").val())){
			error = true;
			$("#bioseguridad").addClass("alertaCombo");
		}		

		if(!$.trim($("#oficina").val())){
			error = true;
			$("#oficina").addClass("alertaCombo");
		}
		
		if(!$.trim($("#semana").val())){
			error = true;
			$("#semana").addClass("alertaCombo");
		}
		
		if(!$.trim($("#zonaPredio").val())){
			error = true;
			$("#zonaPredio").addClass("alertaCombo");
		}


		if(!$.trim($("#sitioPredio").val()) || !esCampoValido("#sitioPredio")){
			error = true;
			$("#sitioPredio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#utmX").val()) || !esCampoValido("#utmX")){
			error = true;
			$("#utmX").addClass("alertaCombo");
		}
		
		if(!$.trim($("#utmY").val()) || !esCampoValido("#utmY")){
			error = true;
			$("#utmY").addClass("alertaCombo");
		}
		
		if(!$.trim($("#utmZ").val()) || !esCampoValido("#utmZ")){
			error = true;
			$("#utmZ").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			abrir($(this),event,false);

			$('.bsig').removeAttr("disabled","disabled");
			$("input").attr("disabled");
			$("select").attr("disabled");
			$("#modificar").removeAttr("disabled");
			$("#informacion").show();
			$("#actualizacion").hide();
		}
	});
	
	$("#otroPredio").change(function(event){
		if($("#otroPredio option:selected").val() =='0'){
			$("#numeroPredios").val('0');
			$("#numeroPredios").hide();
			$("#lnumeroPredios").hide();
		}else{
			$("#numeroPredios").val('');
			$("#numeroPredios").show();
			$("#lnumeroPredios").show();
		}
	});
	
	$("#unidadMedida").change(function(){
    	$("#medidaPredio").val($("#unidadMedida option:selected").text());
	});
	
	$("#oficina").change(function(){
    	$("#nombreOficina").val($("#oficina option:selected").text());
	});

	$("#archivoInforme").click(function(){
    	$("#subirArchivoInforme button").removeAttr("disabled");
    });
	
	$("#explotacionAves").change(function(event){
		if($("#explotacionAvicola option:selected").val()=='0'){
        	$('#informacionExplotacionAves').hide();
			$('#informacionExplotacionAvesFS').hide();
        }else{
    	    $('#informacionExplotacionAves').show();
			$('#informacionExplotacionAvesFS').show();
        }
	});
	
	$("#explotacionAvesCon").change(function(event){
		if($("#explotacionAvesCon option:selected").val()=='0'){
        	$('#informacionExplotacionAvesConsulta').hide();
			$('#informacionExplotacionAvesConsultaFS').hide();
        }else{
    	    $('#informacionExplotacionAvesConsulta').show();
			$('#informacionExplotacionAvesConsultaFS').show();
        }
	});

















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

	$("#especiePoblacion").change(function(event){
    	$("#nombreEspeciePoblacion").val($("#especiePoblacion option:selected").text());

		$('#nuevaPoblacionExistente').attr('data-destino','categoria');
		$('#nuevaPoblacionExistente').attr('data-opcion','combosEventoSanitario');
	    $('#opcion').val('buscarCategoria');
	    		
		abrir($("#nuevaPoblacionExistente"),event,false);  

		$('#nuevaPoblacionExistente').attr('data-destino','detalleItem');
		$('#nuevaPoblacionExistente').attr('data-opcion','guardarPoblacionExistente');  	
	});

	$("#categoriaPoblacion").change(function(){
    	$("#nombreCategoriasPoblacion").val($("#categoriaPoblacion option:selected").text());
	});

	$("#especiePoblacionAves").change(function(){
    	$("#nombreEspeciePoblacionAves").val($("#especiePoblacionAves option:selected").text());
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
    
    $("#archivoMapa").click(function(){
    	$("#subirArchivoMapa button").removeAttr("disabled");
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
        }else{
        	$('#lLaboratorioMuestras').hide();
    		$('#laboratorioMuestras').hide();
    		$('#nombreLaboratorioMuestras').hide();
    		$('#laboratorioMuestras').removeAttr('required');
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
</script>
