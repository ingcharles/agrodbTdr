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
	$resultadoLaboratorio = $cpco->abrirResultadoLaboratorioVisita($conexion, $idEventoSanitario, $numVisita);
	$laboratorios = $cpco->abrirCatalogoLaboratorios($conexion);
	$laboratorios1 = $cpco->abrirCatalogoLaboratorios($conexion);
	
	/* catalogos*/
	$laboratoriosMuestra = $listaCatalogos->listarCatalogos($conexion,'LABORATORIO');
	$especiesMuestras = $listaCatalogos->listarCatalogos($conexion,'ESPECIES');
	$tiposMuestras = $listaCatalogos->listarCatalogos($conexion,'TIPO_MUESTRA');
	$especiesPoblacion = $listaCatalogos->listarCatalogos($conexion,'ESPECIES');
	$categoriasPoblaciones = $listaCatalogos->listarCatalogos($conexion,'CATEGORIA');
	$especiesPoblacionAves = $listaCatalogos->listarCatalogos($conexion,'AVES');
	$cronologiasFinales = $listaCatalogos->listarCatalogos($conexion,'CRONOLOGIAF');
	$diagnosticosFinales = $listaCatalogos->listarCatalogos($conexion,'DIAGNOSTICOS');
	$especieFinales = $listaCatalogos->listarCatalogos($conexion,'ESPECIES');
	$categoriasFinales = $listaCatalogos->listarCatalogos($conexion,'CATEGORIA');
	$especieFinalesAves = $listaCatalogos->listarCatalogos($conexion,'AVES');
	$tipoVacunacionFinales = $listaCatalogos->listarCatalogos($conexion,'ENFERMEDAD');
	$laboratoriosFinales = $listaCatalogos->listarCatalogos($conexion,'LABORATORIO');
	$enfermedadesFinales = $listaCatalogos->listarCatalogos($conexion,'ENFERMEDAD');
	
		
	/*grid*/
	//$muestra  = $cpco->listarMuestrasDetalleInspeccion($conexion, $idEventoSanitario);
	$muestraConsulta  = $cpco->listarMuestrasDetalleInspeccion($conexion, $idEventoSanitario, $numVisita);
	$poblaciones  = $cpco->listarPoblacionesInspeccion($conexion, $idEventoSanitario, $numVisita);
	$poblacionesConsulta  = $cpco->listarPoblacionesInspeccion($conexion, $idEventoSanitario, $numVisita);
	$poblacionesAves  = $cpco->listarPoblacionesAvesInspeccion($conexion, $idEventoSanitario, $numVisita);
	$poblacionesAvesConsulta  = $cpco->listarPoblacionesAvesInspeccion($conexion, $idEventoSanitario, $numVisita);
	
	$cronologiasFinal  = $cpco->listarCronologiasFinales($conexion, $idEventoSanitario);
	$diagnosticos  = $cpco->listarDiagnosticos($conexion, $idEventoSanitario);
	$diagnosticosConsulta  = $cpco->listarDiagnosticos($conexion, $idEventoSanitario);
	$poblacionesFinales   = $cpco->listarPoblacionesFinales($conexion, $idEventoSanitario);
	$poblacionesFinalesConsulta   = $cpco->listarPoblacionesFinales($conexion, $idEventoSanitario);
	$poblacionesFinalesAves  = $cpco->listarPoblacionesFinalesAves($conexion, $idEventoSanitario);
	$poblacionesFinalesAvesConsulta  = $cpco->listarPoblacionesFinalesAves($conexion, $idEventoSanitario);
	$vacunacionFinales = $cpco->listarVacunacionFinales($conexion, $idEventoSanitario);
	$vacunacionFinalesConsulta = $cpco->listarVacunacionFinales($conexion, $idEventoSanitario);
	
?>

<header>
	<h1>Eventos Sanitarios - Visita de Cierre</h1>
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

</div>

<!-- >div class="pestania">

	<form id="nuevaPoblacionExistente" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarPoblacionExistente" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="< ?php echo $idEventoSanitario;?>" />
		<input type="hidden" id="numeroVisita" name="numeroVisita" value="< ?php echo $eventoSanitario['num_inspeccion']; ?>"/>
		<input type='hidden' id='opcion' name='opcion' value="buscarCategoria" />
	
		<fieldset>
			<legend>Población animal, existente, enferma y muerta</legend>
		
		
			<div data-linea="0">
				<label>Especie:</label>
					<select id="especiePoblacion" name="especiePoblacion" required="required">
						<option value="">Seleccione....</option>
						< ?php 
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
			< ?php 
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
			< ?php 
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

</div-->


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

<!--Datos Finales -->
<div class="pestania">
	<h2>Datos Finales</h2>
	<form id="nuevaCronologiaFinal" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarCronologiaFinal" data-destino="detalleItem">
		
		<fieldset>
			<legend>Cronología</legend>
			<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
					
				<div data-linea="6">
					<label>tipo:</label>
						<select id="tipoCronologiaFinal" name="tipoCronologiaFinal" required="required">
							<option value="">Seleccione....</option>
							<?php 
								while ($cronologia = pg_fetch_assoc($cronologiasFinales)){
								echo '<option value="' . $cronologia['codigo'] . '">' . $cronologia['nombre'] . '</option>';
							}
							?>
						</select>
						<input type="hidden" id="nombreCronologiaFinal" name="nombreCronologiaFinal" />
				</div>
				
				<div data-linea="6">
						<label>Fecha:</label>
						<input type="text" id="fechaCronologiaFinal" name="fechaCronologiaFinal" required="required" />
				</div>
	
				<div>
					<button type="submit" class="mas">Agregar</button>		
				</div>
				
		</fieldset>
	</form>

	<fieldset id="detalleCronologiaFinalFS">
		<legend>Cronología</legend>
		<table id="detalleCronologiaFinal">
			<thead>
				<tr>
					<th width="15%">Tipo</th>
					<th width="15%">Fecha</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($cronologia = pg_fetch_assoc($cronologiasFinal)){
					echo $cpco->imprimirLineaCronologiaFinal(	$cronologia['id_cronologia_final'], 
																$cronologia['id_evento_sanitario'],
																$cronologia['nombre_tipo_cronologia_final'], 
																$cronologia['fecha_cronologia_final'], 
																$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleCronologiaFinalConsultaFS">
		<legend>Cronología</legend>
		<table id="detalleCronologiaFinal">
			<thead>
				<tr>
					<th width="15%">Tipo</th>
					<th width="15%">Fecha</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($cronologia = pg_fetch_assoc($cronologiasFinal)){
					echo $cpco->imprimirLineaCronologiaFinalConsulta(	$cronologia['id_cronologia_final'], 
																		$cronologia['id_evento_sanitario'],
																		$cronologia['nombre_tipo_cronologia_final'], 
																		$cronologia['fecha_cronologia_final'], 
																		$ruta);
				}
			?>
		</table>
	</fieldset>
	
	
	<form id="nuevaDiagnosticoFinal" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarDiagnosticoFinal" data-destino="detalleItem">
		
		<fieldset>
			<legend>Diagnóstico definitivo</legend>
			<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
			
				<div data-linea="2">
					<label>Diagnóstico:</label>
						<select id="diagnosticoFinal" name="diagnosticoFinal" required="required">
							<option value="">Seleccione....</option>
							<?php 
								while ($diagnostico = pg_fetch_assoc($diagnosticosFinales)){
								echo '<option value="' . $diagnostico['codigo'] . '">' . $diagnostico['nombre'] . '</option>';
							}
							?>
						</select>
						<input type="text" id="nombreDiagnosticoFinal" name="nombreDiagnosticoFinal" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
				</div>
				
				<div data-linea="3">
					<label>Enfermedad:</label>
						<select id="enfermedadFinal" name="enfermedadFinal" required="required">
							<option value="">Seleccione....</option>
							<?php 
								while ($enfermedad = pg_fetch_assoc($enfermedadesFinales)){
								echo '<option value="' . $enfermedad['codigo'] . '">' . $enfermedad['nombre'] . '</option>';
							}
							?>
						</select>
						<input type="text" id="nombreEnfermedadFinal" name="nombreEnfermedadFinal" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
				</div>				
				
				<div data-linea="4">
						<label>Descripción:</label>
						<input type="text" id="descripcionDiagnosticoFinal" name="descripcionDiagnosticoFinal" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
				</div>
	
				<div>
					<button type="submit" class="mas">Agregar</button>		
				</div>
				
			
		</fieldset>
	</form>
		
	<fieldset id="detalleDiagnosticoFinalFS">
		<legend>Diagnostico Definitivo</legend>
		<table id="detalleDiagnosticoFinal">
			<thead>
				<tr>
					<th width="15%">Diagnóstico</th>
					<th width="15%">Enfermedad</th>
					<th width="15%">Descripción</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($diagnostico = pg_fetch_assoc($diagnosticos)){
					echo $cpco->imprimirLineaDiagnosticoFinal(	$diagnostico['id_diagnosticos_final'], 
																$diagnostico['id_evento_sanitario'],
																$diagnostico['nombre_diagnostico_final'],
																$diagnostico['enfermedad'],
																$diagnostico['descricion_diagnostico_final'], 
																$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleDiagnosticoFinalConsultaFS">
		<legend>Diagnostico Definitivo</legend>
		<table id="detalleDiagnosticoFinal">
			<thead>
				<tr>
					<th width="15%">Diagnóstico</th>
					<th width="15%">Enfermedad</th>
					<th width="15%">Descripción</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($diagnostico = pg_fetch_assoc($diagnosticosConsulta)){
					echo $cpco->imprimirLineaDiagnosticoFinalConsulta(	$diagnostico['id_diagnosticos_final'], 
																		$diagnostico['id_evento_sanitario'],
																		$diagnostico['nombre_diagnostico_final'],
																		$diagnostico['enfermedad'],
																		$diagnostico['descricion_diagnostico_final'], 
																		$ruta);
				}
			?>
		</table>
	</fieldset>
	
</div>

<!--Datos Finales -->
<div class="pestania">
	<h2>Datos Finales</h2>
	
	<form id="nuevaPoblacionFinal" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarPoblacionFinal" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		<input type='hidden' id='opcion' name='opcion' value="buscarCategoriaFinal" />
			
		<fieldset>
			<legend>Población animal existente, enferma  al cierre del episodio</legend>
			
				<div data-linea="3">
					<label>Especie:</label>
						<select id="especieFinal" name="especieFinal">
							<option value="">Seleccione....</option>
							<?php 
								while ($especie = pg_fetch_assoc($especieFinales)){
								echo '<option value="' . $especie['codigo'] . '">' . $especie['nombre'] . '</option>';
							}
							?>
						</select>
						<input type="text" id="nombreEspecieFinal" name="nombreEspecieFinal" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
				</div>
				
				<div id="categoriaF" data-linea="1">
					
				</div>
				
				<div data-linea="4">
					<label>Existentes al cierre:</label>
					<input type="number" id="existentesPoblacionFinal" name="existentesPoblacionFinal" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" size="15"/>
				</div>
				
				<div data-linea="4">
					<label>Enfermos durante el evento:</label>
					<input type="number" id="enfermosPoblacionFinal" name="enfermosPoblacionFinal" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" size="15"/>
				</div>
				
				<div data-linea="5">
					<label>Muertos durante el evento:</label>
					<input type="number" id="muertosPoblacionFinal" name="muertosPoblacionFinal" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" size="15"/>
				</div>
				
				<div data-linea="5">
					<label>Sacrificados durante el evento:</label>
					<input type="number" id="sacrificadosPoblacionFinal" name="sacrificadosPoblacionFinal" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" size="15"/>
				</div>
				
				<div data-linea="6">
					<label>Matados y Eliminados durante el evento:</label>
					<input type="number" id="matadosPoblacionFinal" name="matadosPoblacionFinal" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" size="15"/>
				</div>
	
				<div>
					<button type="submit" class="mas">Agregar</button>		
				</div>
			
		</fieldset>
	</form>
	
	<fieldset id="detallePoblacionFinalFS">
		<legend>Población animal existente, enferma  al cierre del episodio</legend>
		<table id="detallePoblacionFinal">
			<thead>
				<tr>
					<th width="15%">Especie</th>
					<th width="15%">Categoria</th>
					<th width="15%">Existentes al cierre</th>
					<th width="15%">Enfermos durante el evento</th>
					<th width="15%">Muertos durante el evento</th>
					<th width="15%">Sacrificados durante el evento</th>
					<th width="15%">Matados y eliminados durante el evento</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($poblacion = pg_fetch_assoc($poblacionesFinales)){
					echo $cpco->imprimirLineaPoblacionFinal(	$poblacion['id_poblacion_final'], 
																$poblacion['id_evento_sanitario'],
																$poblacion['nombre_especie_poblacion_final'], 
																$poblacion['nombre_categoria_poblacion_final'], 
																$poblacion['existentes_poblacion_final'], 
																$poblacion['enfermos_poblacion_final'], 
																$poblacion['muertos_poblacion_final'], 
																$poblacion['sacrificados_poblacion_final'], 
																$poblacion['matados_eliminados_poblacion_final'], 
																$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePoblacionFinalConsultaFS">
		<legend>Población animal existente, enferma  al cierre del episodio</legend>
		<table id="detallePoblacionFinal">
			<thead>
				<tr>
					<th width="15%">Especie</th>
					<th width="15%">Categoria</th>
					<th width="15%">Existentes al cierre</th>
					<th width="15%">Enfermos durante el evento</th>
					<th width="15%">Muertos durante el evento</th>
					<th width="15%">Sacrificados durante el evento</th>
					<th width="15%">Matados y eliminados durante el evento</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($poblacion = pg_fetch_assoc($poblacionesFinalesConsulta)){
					echo $cpco->imprimirLineaPoblacionFinalConsulta(	$poblacion['id_poblacion_final'], 
																		$poblacion['id_evento_sanitario'],
																		$poblacion['nombre_especie_poblacion_final'], 
																		$poblacion['nombre_categoria_poblacion_final'], 
																		$poblacion['existentes_poblacion_final'], 
																		$poblacion['enfermos_poblacion_final'], 
																		$poblacion['muertos_poblacion_final'], 
																		$poblacion['sacrificados_poblacion_final'], 
																		$poblacion['matados_eliminados_poblacion_final'], 
																		$ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<!--Datos Finales -->
<div class="pestania">
	<h2>Datos Finales</h2>
	<!-- form id="nuevaVacunacionFinalDatos" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarVacunacionFinalDatos" data-destino="detalleItem">
		<fieldset>
			<legend>Vacunación</legend>
			
			<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="< ?php echo $idEventoSanitario;?>" />
			
				<div data-linea="6">
					<label>Se vacunó al cierre:</label>
						<select id="vacunacionFinal" name="vacunacionFinal" required="required">
							<option value="">Seleccione....</option>
							<option value="Si" < ?php echo ($eventoSanitario['vacunacion_final']=='Si'?'selected':''); ?>>Si</option>
							<option value="No" < ?php echo ($eventoSanitario['vacunacion_final']=='No'?'selected':''); ?>>No</option>
						</select>
				</div>
				<div>
					<button type="submit" class="guardar">Guardar</button>		
				</div>
		</fieldset>
	</form-->

<form id="nuevaVacunacionFinal" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarVacunacionFinal" data-destino="detalleItem">
						
	<fieldset>
		<legend>Datos Vacunación</legend>		
			<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
			
			<div data-linea="6">
					<label>Se vacunó al cierre:</label>
						<select id="vacunacionFinal" name="vacunacionFinal" required="required">
							<option value="">Seleccione....</option>
							<option value="Si" >Si</option>
							<option value="No" >No</option>
						</select>
				</div>
			<div data-linea="6">
				<label id="lEnf">Enfermedad:</label>
					<select id="tipoVacunacionFinal" name="tipoVacunacionFinal" required="required">
						<option value="">Seleccione....</option>
						<?php 
							while ($tipo = pg_fetch_assoc($tipoVacunacionFinales)){
							echo '<option value="' . $tipo['codigo'] . '">' . $tipo['nombre'] . '</option>';
						}
						?>
					</select>
					<input type="hidden" id="nombreTipoVacunacionFinal" name="nombreTipoVacunacionFinal" />
			</div>
			
			<div data-linea="7">
				<label id="lDos">Dosis Aplicadas:</label>
				<input type="number" id="dosisFinal" name="dosisFinal" data-er="^[0-9]+$" required="required"/>
			</div>
			
			<div data-linea="7">
				<label id="lPre">Predios vacunados:</label>
				<input type="number" id="prediosVacunadosFinal" name="prediosVacunadosFinal" maxlength="16" data-er="^[0-9]+$" required="required"/>
			</div>
			
			<div data-linea="8">
				<label id="lLab">Laboratorios:</label>
					<input type="text" id="nombreLaboratorioFinal" name="nombreLaboratorioFinal" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$"/>
			</div>
			
			<div data-linea="8">
				<label id="lLot">Lote:</label>
				<input type="number" id="loteFinal" name="loteFinal" maxlength="16" data-er="^[0-9]+$" required="required" />
			</div>

			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
		
	</fieldset>
</form>	
	
	<fieldset id="detalleVacunacionFinalFS">
		<legend>Datos de Vacunación al cierre del episodio</legend>
		<table id="detalleVacunacionFinal">
			<thead>
				<tr>
					<th width="15%">Tipo</th>
					<th width="15%">Dosis Aplicadas</th>
					<th width="15%">Predios vacunados</th>
					<th width="15%">Laboratorios</th>
					<th width="15%">Lote</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($vacuna = pg_fetch_assoc($vacunacionFinales)){
					echo $cpco->imprimirLineaVacunacionFinal(	$vacuna['id_vacunacion_final'], 
																$vacuna['id_evento_sanitario'],
																$vacuna['nombre_tipo_vacunacion_final'], 
																$vacuna['dosis_aplicada_vacunacion_final'], 
																$vacuna['predios_vacunacion_final'], 
																$vacuna['nombre_laboratorios_vacunacion_final'], 
																$vacuna['lote_vacunacion_final'], 
																$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleVacunacionFinalConsultaFS">
		<legend>Población aves existente, enferma  al cierre del episodio</legend>
		<table id="detalleVacunacionFinal">
			<thead>
				<tr>
					<th width="15%">Tipo</th>
					<th width="15%">Dosis Aplicadas</th>
					<th width="15%">Predios vacunados</th>
					<th width="15%">Laboratorios</th>
					<th width="15%">Lote</th>
				</tr>
			</thead>
			<?php 
				while ($vacuna = pg_fetch_assoc($vacunacionFinalesConsulta)){
					echo $cpco->imprimirLineaVacunacionFinalConsulta(	$vacuna['id_vacunacion_final'], 
																		$vacuna['id_evento_sanitario'],
																		$vacuna['nombre_tipo_vacunacion_final'], 
																		$vacuna['dosis_aplicada_vacunacion_final'], 
																		$vacuna['predios_vacunacion_final'], 
																		$vacuna['nombre_laboratorios_vacunacion_final'], 
																		$vacuna['lote_vacunacion_final'], 
																		$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="adjuntosActa">
		<legend>Adjuntar Acta levantamiento de cuarentena</legend>
		<div data-linea="1">
			<label>Acta:</label>
			<?php echo ($eventoSanitario['ruta_acta_final']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$eventoSanitario['ruta_acta_final'].' target="_blank" class="archivo_cargado" id="archivo_cargado">acta cargada</a>')?>
		</div>
			
		<form id="subirArchivoActa" action="aplicaciones/seguimientoEventosSanitarios/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergenteInforme" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
			<input type="file" name="archivo" id="archivoActa" accept="application/pdf" /> 
			<input type="hidden" name="id" value="<?php echo $eventoSanitario['id_evento_sanitario'];?>" />
			<input type="hidden" name="aplicacion" value="archivoActa" /> 
			<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir archivo</button>
		</form>
		<iframe name="ventanaEmergenteActa" class="ventanaEmergente"></iframe>
	</fieldset>
	
	
	
</div>

<div class="pestania">

<fieldset id="adjuntosInformeCierre">
		<legend>Adjuntar Cierre del evento sanitario</legend>
		<div data-linea="1">
			<label>Informe de cierre:</label>
			<?php echo ($eventoSanitario['ruta_informe_cierre']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$eventoSanitario['ruta_informe_cierre'].' target="_blank" class="archivo_cargado" id="archivo_cargado">cierre cargado</a>')?>
		</div>
			
		<form id="subirArchivoInforme" action="aplicaciones/seguimientoEventosSanitarios/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergenteInforme" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
			<input type="file" name="archivo" id="archivoInformeCierre" accept="application/pdf" /> 
			<input type="hidden" name="id" value="<?php echo $eventoSanitario['id_evento_sanitario'];?>" />
			<input type="hidden" name="aplicacion" value="archivoInformeCierre" /> 
			<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir archivo</button>
		</form>
		<iframe name="ventanaEmergenteInforme" class="ventanaEmergente"></iframe>
	</fieldset>

<h2>Conclusión</h2>
	
<form id="nuevaConclusionFinal" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarConclusionFinal" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		<input type="hidden" id="numeroVisita" name="numeroVisita" value="<?php echo $eventoSanitario['num_inspeccion']; ?>"/>
		
		<fieldset>
			<legend>Conclusión sobre el origen de la enfermedad</legend>
			
				<div data-linea="6">
					<label>Conclusión:</label>
					<input type="text" id="conclusionFinal" name="conclusionFinal" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$" required="required"/>
				</div>
			
		</fieldset>
		
		<div>
			<button type="submit" class="guardar">Guardar y Finalizar Evento Sanitario</button>		
		</div>
		
		<p class="nota">Por favor revise que la información ingresada sea correcta. Una vez guardada no podrá ser modificada.</p>
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


		$('#lLaboratorioMuestras').hide();
		$('#laboratorioMuestras').hide();
		$('#nombreLaboratorioMuestras').hide();

		$('#colecta').hide();
		$('#nuevaMuestra').hide();
		$('#detalleMuestraFS').hide();
		$('#detalleMuestraFSConsulta').show();

		acciones("#infoMuestras","#detalleMuestraConsultaFS");
		acciones("#nuevaExplotacion","#detalleExplotacion");
		acciones("#nuevaPoblacionExistente","#detallePoblacion");
		acciones("#nuevaPoblacionExistenteAves","#detallePoblacionAves");
		acciones("#nuevaCronologiaFinal","#detalleCronologiaFinal");
		acciones("#nuevaDiagnosticoFinal","#detalleDiagnosticoFinal");
		acciones("#nuevaPoblacionFinal","#detallePoblacionFinal");
		acciones("#nuevaPoblacionFinalAves","#detallePoblacionFinalAves");
		acciones("#nuevaVacunacionFinal	","#detalleVacunacionFinal");

		$('#lEnf').hide();
		$('#tipoVacunacionFinal').hide();
		$('#lDos').hide();
		$('#dosisFinal').hide();
		$('#lPre').hide();
		$('#prediosVacunadosFinal').hide();
		$('#lLab').hide();
		$('#nombreLaboratorioFinal').hide();
		$('#lLot').hide();
		$('#loteFinal').hide();
		
		$("#actualizacion").hide();
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
		$('#nombreEnfermedadFinal').hide();
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
    	$("#nombreTipoMuestra").val($("#tipoMuestra option:selected").text());
	});

	function agregarAnalisis(){  
    	if($("#especieMuestra").val()!="" && $("#tipoMuestra").val()!=""){

    		if($("#detalleVacunacionAftosa #r_"+$("#especieMuestra").val()+$("#tipoMuestra").val()).length==0){
   				$("#detalleVacunacionAftosa").append("<tr id='r_"+$("#especieMuestra").val()+$("#tipoMuestra").val()+"'><td>"+$("#especieMuestra  option:selected").text()+"</td><td>"+$("#tipoMuestra  option:selected").text()+"</td><td>"+$("#numeroMuestras").val()+"</td><td>"+$("#fechaColectaMuestra").val()+"</td><td>"+$("#horaColectaMuestra").val()+"</td><td>"+$("#fechaEnvioMuestra").val()+"</td><td>"+$("#horaEnvioMuestra").val()+"</td><td><input id='arrayMuestra' name='arrayEspecieMuestra[]' value='"+$("#especieMuestra option:selected").val()+"' type='hidden'><input id='arrayNombreEspecieMuestra' name='arrayNombreEspecieMuestra[]' value='"+$("#especieMuestra option:selected").text()+"' type='hidden'><input id='arrayTipoMuestra' name='arrayTipoMuestra[]' value='"+$("#tipoMuestra option:selected").val()+"' type='hidden'><input id='arrayNombreTipoMuestra' name='arrayNombreTipoMuestra[]' value='"+$("#nombreTipoMuestra").val()+"' type='hidden'><input id='arrayNumeroMuestras' name='arrayNumeroMuestras[]' value='"+$("#numeroMuestras").val()+"' type='hidden'><input id='arrayFechaColectaMuestra' name='arrayFechaColectaMuestra[]' value='"+$("#fechaColectaMuestra").val()+"' type='hidden'><input id='arrayHoraColectaMuestra' name='arrayHoraColectaMuestra[]' value='"+$("#horaColectaMuestra").val()+"' type='hidden'><input id='arrayFechaEnvioMuestra' name='arrayFechaEnvioMuestra[]' value='"+$("#fechaEnvioMuestra").val()+"' type='hidden'><input id='arrayHoraEnvioMuestra' name='arrayHoraEnvioMuestra[]' value='"+$("#horaEnvioMuestra").val()+"' type='hidden'><button type='button' onclick='quitarAnalisis(\"#r_"+$("#especieMuestra").val()+$("#tipoMuestra").val()+"\")' class='menos'>Quitar</button></td></tr>");
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

    $("#archivoMapa").click(function(){
    	$("#subirArchivoMapa button").removeAttr("disabled");
    });

	$("#archivoImagen").click(function(){
    	$("#subirArchivoImagen button").removeAttr("disabled");
    });

	$("#laboratorioMuestras").change(function(){
    	$("#nombreLaboratorioMuestras").val($("#laboratorioMuestras option:selected").text());
	});

	$("#tipoCronologiaFinal").change(function(){
    	$("#nombreCronologiaFinal").val($("#tipoCronologiaFinal option:selected").text());
	});

	$("#diagnosticoFinal").change(function(){
    	$("#nombreDiagnosticoFinal").val($("#diagnosticoFinal option:selected").text());
	});

	$("#enfermedadFinal").change(function(){
    	$("#nombreEnfermedadFinal").val($("#enfermedadFinal option:selected").text());
	});

	$("#especieFinal").change(function(event){
		$("#nombreEspecieFinal").val($("#especieFinal option:selected").text());

		$('#nuevaPoblacionFinal').attr('data-destino','categoriaF');
		$('#nuevaPoblacionFinal').attr('data-opcion','combosEventoSanitario');
	    $('#opcion').val('buscarCategoriaFinal');
	    		
		abrir($("#nuevaPoblacionFinal"),event,false);  

		$('#nuevaPoblacionFinal').attr('data-destino','detalleItem');
		$('#nuevaPoblacionFinal').attr('data-opcion','guardarPoblacionFinal');

	});

	/*$("#categoriaFinal").change(function(){
    	$("#nombreCategoriaFinal").val($("#categoriaFinal option:selected").text());
	});*/

	$("#especieFinalAves").change(function(){
    	$("#nombreEspecieFinalAves").val($("#especieFinalAves option:selected").text());
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

    /*$("#nuevaVacunacionFinalDatos").submit(function(event){

		$("#nuevaVacunacionFinalDatos").attr('data-opcion', 'guardarVacunacionFinalDatos');
	    $("#nuevaVacunacionFinalDatos").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#vacunacionFinal").val())){
			error = true;
			$("#vacunacionFinal").addClass("alertaCombo");
		}
				
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
			$("#nuevaVacunacionFinal").show();
			$("#detalleVacunacionFinalFS").show();
		}
	});*/

    $("#tipoVacunacionFinal").change(function(){
    	$("#nombreTipoVacunacionFinal").val($("#tipoVacunacionFinal option:selected").text());
	});

    $("#laboratorioFinal").change(function(){
    	$("#nombreLaboratorioFinal").val($("#laboratorioFinal option:selected").text());
	});

    $("#archivoActa").click(function(){
    	$("#subirArchivoActa button").removeAttr("disabled");
    });

	$("#archivoInformeCierre").click(function(){
    	$("#subirArchivoInforme button").removeAttr("disabled");
    });

	//Cierre y Envío a Revisión
	$("#nuevaConclusionFinal").submit(function(event){

		$("#nuevaConclusionFinal").attr('data-opcion', 'guardarCierrePrimeraVisitaTecnico');
	    $("#nuevaConclusionFinal").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#conclusionFinal").val())){
			error = true;
			$("#conclusionFinal").addClass("alertaCombo");
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

	$("#vacunacionFinal").change(function(){
        if($("#vacunacionFinal option:selected").val()=='Si'){
        	$('#lEnf').show();
    		$('#tipoVacunacionFinal').show();
    		$('#tipoVacunacionFinal').attr("required", "required");
    		$('#nombreTipoVacunacionFinal').val('');
    		$('#lDos').show();
    		$('#dosisFinal').show();
    		$('#dosisFinal').val('');
    		$('#lPre').show();
    		$('#prediosVacunadosFinal').show();
    		$('#prediosVacunadosFinal').val('');
    		$('#lLab').show();
    		$('#nombreLaboratorioFinal').show();
    		$('#nombreLaboratorioFinal').val('');
    		$('#lLot').show();
    		$('#loteFinal').show();
    		$('#loteFinal').val('');
        }else{
        	
    		$('#lEnf').hide();
    		$('#tipoVacunacionFinal').hide();
    		$('#tipoVacunacionFinal').val('0');
    		$('#tipoVacunacionFinal').removeAttr("required");
    		$('#nombreTipoVacunacionFinal').val('No Aplica');
    		$('#lDos').hide();
    		$('#dosisFinal').hide();
    		$('#dosisFinal').val('0');
    		$('#lPre').hide();
    		$('#prediosVacunadosFinal').hide();
    		$('#prediosVacunadosFinal').val('0');
    		$('#lLab').hide();
    		$('#nombreLaboratorioFinal').hide();
    		$('#nombreLaboratorioFinal').val('No Aplica');
    		$('#lLot').hide();
    		$('#loteFinal').hide();
    		$('#loteFinal').val('0');
        }
	});
</script>