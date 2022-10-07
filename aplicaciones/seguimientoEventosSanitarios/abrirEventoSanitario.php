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
	
	$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	$oficinas = $cc->listarSitiosLocalizacion($conexion,'SITIOS');
	
	$idEventoSanitario = $_POST['id'];
	
	$eventoSanitario = pg_fetch_assoc($cpco->abrirEventoSanitario($conexion, $idEventoSanitario));
	
	/* catalogos*/
	$medidas = $listaCatalogos->listarCatalogos($conexion,'MEDIDA');
	$decisiones = $listaCatalogos->listarCatalogos($conexion,'DECISION');
	$bioseguridades = $listaCatalogos->listarCatalogos($conexion,'DECISION');
	$decisionesAves = $listaCatalogos->listarCatalogos($conexion,'DECISION');
	$zonas = $listaCatalogos->listarCatalogos($conexion,'ZONAS');
	$especies = $listaCatalogos->listarCatalogos($conexion,'ESPECIES');
	$tiposCronologias = $listaCatalogos->listarCatalogos($conexion,'CRONOLOGIA');
	$especiesAfectadas  = $listaCatalogos->listarCatalogos($conexion,'ESPECIES');
	$vacunacionesAftosa = $listaCatalogos->listarCatalogos($conexion,'ENFERMEDAD');
	$laboratoriosAftosa = $listaCatalogos->listarCatalogos($conexion,'LABORATORIO');
	$vacunaciones = $listaCatalogos->listarCatalogos($conexion,'VACUNAS');
	$origenes = $listaCatalogos->listarCatalogos($conexion,'ORIGENES');
	$especiesPrimerAnimal = $listaCatalogos->listarCatalogos($conexion,'ESPECIES');
	$patologias = $listaCatalogos->listarCatalogos($conexion,'PATOLOGIAS');
	
	/*grid*/
	$tipoExplotacion  = $cpco->listarTiposExplotaciones($conexion, $idEventoSanitario);
	$tipoExplotacionConsulta  = $cpco->listarTiposExplotaciones($conexion, $idEventoSanitario);
	$cronologia  = $cpco->listarCronologias($conexion, $idEventoSanitario);
	$cronologiaConsulta  = $cpco->listarCronologias($conexion, $idEventoSanitario);
	$especieAnimalAfectada  = $cpco->listarEspecieAnimalAfactada($conexion, $idEventoSanitario);
	$especieAnimalAfectadaConsulta  = $cpco->listarEspecieAnimalAfactada($conexion, $idEventoSanitario);
	$vacunacionAftosa  = $cpco->listarVacunacionAftosa($conexion, $idEventoSanitario);
	$vacunacionAftosaConsulta  = $cpco->listarVacunacionAftosa($conexion, $idEventoSanitario);
	$vacunacionAnimal  = $cpco->listarVacunaciones($conexion, $idEventoSanitario);
	$vacunacionAnimalConsulta  = $cpco->listarVacunaciones($conexion, $idEventoSanitario);
	$origenAnimal  = $cpco->listarOrigenes($conexion, $idEventoSanitario);
	$origenAnimalConsulta  = $cpco->listarOrigenes($conexion, $idEventoSanitario);
	
?>

<header>
	<h1>Eventos Sanitarios</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>

<!-- Informacion general -->
<div class="pestania">
	<h2>Información General</h2>
	
	<form id="modificarEventoSanitario" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="modificarEventoSanitario" data-destino="detalleItem">
	
		<p>
			<button id="modificar" type="button" class="editar">Modificar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</p>
	
	<div id="informacion">
		<fieldset>
			<legend>Información General</legend>

			<div data-linea="1">
				<label id="lNumero">Número:</label>
				<?php echo $eventoSanitario['numero_formulario'];?> 
			</div>
		
			<div data-linea="1">
				<label id="lFecha">Fecha:</label>
				<?php echo $eventoSanitario['fecha'];?> 
			</div>
		
			<div data-linea="2">
				<label id="lOrigenNotificacion">Origen de la Notificación:</label>
				<?php echo $eventoSanitario['nombre_origen'];?> 
			</div>
		
			<div data-linea="3">
				<label id="lCanalNotificacion">Canal de la Notificación:</label>
				<?php echo $eventoSanitario['nombre_canal'];?> 
			</div>
		</fieldset>

		<fieldset>
			<legend>Información de la finca</legend>
		
			<div data-linea="4">
				<label id="lNombre">Nombre del propietario:</label>
				<?php echo $eventoSanitario['nombre_propietario'];?> 
			</div>
				
			<div data-linea="5">
				<label id="lCedula">Número de Cedula:</label>
				<?php echo $eventoSanitario['cedula_propietario'];?> 
			</div>
			
			<div data-linea="5">
				<label id="lTelefono">Teléfono:</label>
				<?php echo $eventoSanitario['telefono_propietario'];?> 
			</div>
		
			<div data-linea="6">
				<label id="lCelular">Celular:</label>
				<?php echo $eventoSanitario['celular_propietario'];?> 
			</div>
		
			<div data-linea="6">
				<label id="lCorreoElectronico">Correo Electrónico:</label>
				<?php echo $eventoSanitario['correo_electronico_propietario'];?> 
			</div>
			
			<div data-linea="7">
				<label id="lNombrePredio">Nombre del Predio:</label>
				<?php echo $eventoSanitario['nombre_predio'];?> 
			</div>
		
			<div data-linea="8">
				<label id="lExtencionPredio">Extención del Predio:</label>
				<?php echo $eventoSanitario['extencion_predio'];?> 
			</div>
		
			<div data-linea="9">
				<label id="lUnidadMedida">Unidad Medida:</label>
				<?php echo $eventoSanitario['medida'];?> 
			</div>
		
			<div data-linea="10">
				<label id = "lOtroPredio">Tiene otro predio:</label>
				<?php echo $eventoSanitario['otros_predios'];?> 
			</div>
		
			<div data-linea="10">
				<label id = "lNumeroPredios">Número  de Predios:</label>
				<?php echo $eventoSanitario['numero_predios'];?>
			</div>
		
			<div data-linea="11">
				<label id = "lBioseguridad">Tiene medidas de Bioseguridad:</label>
				<?php echo $eventoSanitario['bioseg'];?>
			</div>
		</fieldset>

		<fieldset>
			<legend>Ubicación del Predio</legend>

			<div data-linea="12">
				<label id="lProvincia">Provincia</label>
				<?php echo $eventoSanitario['provincia'];?> 
			</div>
			
			<div data-linea="12">
				<label id="lCanton">Cantón</label>
				<?php echo $eventoSanitario['canton'];?> 
			</div>
			
			<div data-linea="13">	
				<label id="lParroquia">Parroquia</label>
				<?php echo $eventoSanitario['parroquia'];?> 
			</div>
				
			<div data-linea="13">
				<label id="lOficina">Oficina:</label>
				<?php echo $eventoSanitario['oficina'];?> 
			</div>
			
			<div data-linea="14">
				<label id="lSitio">Sitio:</label>
				<?php echo $eventoSanitario['sitio_predio'];?> 
			</div>
			
			<div data-linea="14">
				<label id="lSemana">Semana:</label>
				<?php echo $eventoSanitario['semana'];?> 
			</div>
			
		</fieldset>
		
		<fieldset>
			<legend>Coordenadas</legend>
			<div data-linea="15">
				<label id="lUtm_x">UTM X:</label>
				<?php echo $eventoSanitario['utm_x'];?> 
			</div>
			
			<div data-linea="15">
				<label id="lUtm_y">UTM Y:</label>
				<?php echo $eventoSanitario['utm_y'];?> 
			</div>
			
			<div data-linea="15">
				<label id="lUtm_z">UTM Z:</label>
				<?php echo $eventoSanitario['utm_z'];?> 
			</div>
			
			<div data-linea="15">
				<label id="lZona">Huso/Zona:</label>
				<?php echo $eventoSanitario['huso_zona'];?> 
			</div>			
		</fieldset>
		
	</div>
	
	

	<div id="actualizacion">

		
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />	
			<fieldset>
				<legend>Información General</legend>

				<div data-linea="16">
					<label id="lNumero">Número:</label>
					<?php echo $eventoSanitario['numero_formulario'];?> 
				</div>
		
				<div data-linea="17">
					<label id="lFecha">Fecha:</label>
					<?php echo date('j/n/Y',strtotime($eventoSanitario['fecha']));?> 
				</div>
		
				<div data-linea="18">
					<label id="lOrigenNotificaciona">Origen de la Notificación:</label>
					<?php echo $eventoSanitario['nombre_origen'];?> 
				</div>
		
				<div data-linea="19">
					<label id="lCanalNotificacion">Canal de la Notificación:</label>
					<?php echo $eventoSanitario['nombre_canal'];?> 
				</div>
			</fieldset>

			<fieldset>
				<legend>Información de la finca</legend>
		
				<div data-linea="20">
					<label id="lNombre">Nombre del propietario:</label>
					<input type="text" id="nombrePropietario" name="nombrePropietario" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required" value="<?php echo $eventoSanitario['nombre_propietario'];?>"/>
				</div>
				
				<div data-linea="21">
					<label id="lCedula">Número de Cédula:</label>
					<input type="text" id="cedulaPropietario" name="cedulaPropietario" maxlength="13" data-er="^[0-9]+$" value="<?php echo $eventoSanitario['cedula_propietario'];?>"/>
				</div>
				
				<div data-linea="21">
					<label id="lTelefono">Teléfono:</label>
					<input type="text" id="telefonoPropietario" name="telefonoPropietario" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15" value="<?php echo $eventoSanitario['telefono_propietario'];?>"/>
				</div>
		
				<div data-linea="22">
					<label id="lCelular">Celular:</label>
					<input type="text" id="celularPropietario" name="celularPropietario" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15" value="<?php echo $eventoSanitario['celular_propietario'];?>"/>
				</div>
		
				<div data-linea="23">
					<label id="lCorreoElectronico">Correo Electrónico:</label>
					<input type="text" id="correoElectronicoPropietario" name="correoElectronicoPropietario" maxlength="32" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" value ="<?php echo $eventoSanitario['correo_electronico_propietario'];?>"/>
				</div>
				
				<div data-linea="24">
					<label id="lNombrePredio">Nombre del Predio:</label>
					<input type="text" id="nombrePredio" name="nombrePredio" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$" value ="<?php echo $eventoSanitario['nombre_predio'];?>"/>
				</div>
				
				<div data-linea="25">
					<label id="lExtencionPredio">Extención del Predio:</label>
					<input type="text" id="extencionPredio" name="extencionPredio" maxlength="16" data-er="^[0-9]+$" size="15" value ="<?php echo $eventoSanitario['extencion_predio'];?>"/>
				</div>
				
				<div data-linea="25">
					<label id="lUnidadMedida">Unidad Medida:</label>
					<select id="unidadMedida" name="unidadMedida">
						<option value="">Unidad de Medida....</option>
						<?php 
							while ($medida = pg_fetch_assoc($medidas)){
								if( $medida['codigo'] == $eventoSanitario['id_medida']){
									echo '<option selected value="' . $medida['codigo'] . '">' . $medida['nombre'] . '</option>';
								}else{
									echo '<option value="' . $medida['codigo'] . '">' . $medida['nombre'] . '</option>';
								}
							}
						?>
					</select> 
					<input type="hidden" id="medidaPredio" name="medidaPredio" value ="<?php echo $eventoSanitario['medida'];?>"/>
					

				</div>
				
				<div data-linea="26">
					<label  id = "lOtroPredio">Tiene otro predio:</label>
						<select id="otroPredio" name="otroPredio">
							<option value="">Seleccione....</option>
							<option value="Si">Si</option>
							<option value="No">No</option>
						</select> 
				</div>
				
				<div data-linea="26">
					<label id = "lnumeroPredios">Número  de Predios:</label>
					<input type="number" id="numeroPredios" name="numeroPredios" value ="<?php echo $eventoSanitario['numero_predios'];?>">
				</div>
				
				<div data-linea="27">
					<label id = "lBioseguridad">Tiene medidas de Bioseguridad:</label>
						<select id="bioseguridad" name="bioseguridad">
							<option value="">Seleccione....</option>
							<option value="Si">Si</option>
							<option value="No">No</option>
						</select> 
				</div>
			</fieldset>

			<fieldset>
				<legend>Información del Predio</legend>

				<div data-linea="28">
					<label id="lProvincia">Provincia</label>
					<?php echo $eventoSanitario['provincia'];?> 
				</div>
			
				<div data-linea="28">
					<label id="lCanton">Cantón</label>
					<?php echo $eventoSanitario['canton'];?> 
				</div>
			
				<div data-linea="29">	
					<label id="lParroquia">Parroquia</label>
					<?php echo $eventoSanitario['parroquia'];?> 
				</div>
				
				<div data-linea="29">	
					<label>Oficina</label>
					<select id="oficina" name="oficina" disabled="disabled">
						<?php 
							while ($oficina = pg_fetch_assoc($oficinas)){
								echo '<option value="' . $oficina['codigo'] . '">' . $oficina['nombre'] . '</option>';
							}
						?>
					</select>
					<input type="hidden" id="nombreOficina" name="nombreOficina" value="<?php echo $eventoSanitario['oficina'];?>"/>
				</div>
					
				<div data-linea="30">
					<label>Sitio:</label>
					<input type="text" id="sitioPredio" name="sitioPredio" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $eventoSanitario['sitio_predio'];?>"/>
				</div>
					
				<div data-linea="30">
					<label>Semana:</label>
					<select id="semana" name="semana">
						<option value="">Seleccione....</option>
						<?php 
							$semana = 1;
							while ($semana <= 52){
								if($eventoSanitario['semana'] == $semana){
									echo '<option selected value="' . $semana . '">' . $semana . '</option>';
								}else{
									echo '<option value="' . $semana . '">' . $semana . '</option>';
								}
								$semana++;
							}
						?>
					</select> 
				</div>
			</fieldset>
			
			<fieldset>
				<legend>Coordenadas</legend>
				
				<div data-linea="31">
					<label> UTM X:</label>
					<input type="text" id="utmX" name="utmX" maxlength="6" data-er="^[0-9]+$" value="<?php echo $eventoSanitario['utm_x'];?>"/>
				</div>
				
				<div data-linea="31">
					<label>UTM Y:</label>
					<input type="text" id="utmY" name="utmY" maxlength="7" data-er="^[0-9]+$" value="<?php echo $eventoSanitario['utm_y'];?>"/>
				</div>
				
				<div data-linea="31">
					<label>UTM Z:</label>
					<input type="text" id="utmZ" name="utmZ" maxlength="4" data-er="^[0-9]+$" value="<?php echo $eventoSanitario['utm_z'];?>"/>
				</div>
				
				<div data-linea="31">
					<label>Huso/Zona:</label>
						<select id="zonaPredio" name="zonaPredio">
							<option value="">Seleccione....</option>
							<option value="17N">17N</option>
							<option value="17S">17S</option>
							<option value="18N">18N</option>
							<option value="18S">18S</option>
						</select> 
				</div>
				
			</fieldset>

		</div>
	</form>
	
	<!-- fieldset id="adjuntos">
			<legend>Mapa de Ubicación</legend>
	
				<div data-linea="1">
					<label>Mapa:</label>
					< ?php echo ($eventoSanitario['ruta_mapa']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$eventoSanitario['ruta_mapa'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Mapa cargado</a>')?>
				</div>
				
				<form id="subirArchivo" action="aplicaciones/seguimientoEventosSanitarios/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
					
					<input type="file" name="archivo" id="archivo" accept="application/pdf" /> 
					<input type="hidden" name="id" value="< ?php echo $eventoSanitario['ruta_mapa'];?>" />
					<input type="hidden" name="aplicacion" value="archivoMapa" /> 
					
					<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
				</form>
				<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
		</fieldset-->
	

</div>

<!--Explotaciones -->
<div class="pestania">
	<h2>Identificación de la explotación</h2>

	<form id="nuevaExplotacion" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarExplotacion" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		<input type='hidden' id='opcion' name='opcion' value="buscarFinalidad" />
		
		<fieldset>
			<legend>Explotaciones registradas</legend>
			
			<div data-linea="33">
				<label>Especie:</label>
					<select id="especie" name="especie" required="required">
						<option value="">Especie....</option>
						<?php 
							while ($especie = pg_fetch_assoc($especies)){
							echo '<option value="' . $especie['codigo'] . '">' . $especie['nombre'] . '</option>';
						}
						?>
					</select> 
					<input type="hidden" id="nombreEspecie" name="nombreEspecie"/>
			</div>
			
			<div data-linea="33" id="finalidad">
			
			</div>
			

			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
		</fieldset>
	</form>
		
		<fieldset id="detalleExplotacionFS">
			<legend>Explotaciones registradas</legend>
			<table id="detalleExplotacion">
				<thead>
					<tr>
						<th width="15%">Especie</th>
						<th width="15%">FinalidadS</th>
						<th width="5%">Eliminar</th>
					</tr>
				</thead>
				<?php 
					while ($tipoExplotacionG = pg_fetch_assoc($tipoExplotacion)){
						echo $cpco->imprimirLineaTipoExplotacion(	$tipoExplotacionG['id_explotacion'], 
																	$tipoExplotacionG['id_evento_sanitario'],
																	$tipoExplotacionG['especie'], 
																	$tipoExplotacionG['tipo_explotacion'], 
																	$ruta);
					}
				?>
			</table>
		</fieldset>
	
		<fieldset id="detalleExplotacionConsultaFS">
			<legend>Explotaciones registradas</legend>
			<table id="detalleExplotacion">
				<thead>
					<tr>
						<th width="15%">Especie</th>
						<th width="15%">Tipo explotación</th>
					</tr>
				</thead>
				<?php 
					while ($tipoExplotacionCon = pg_fetch_assoc($tipoExplotacionConsulta)){
						echo $cpco->imprimirLineaTipoExplotacionConsulta(	$tipoExplotacion['id_explotacion_registrada'], 
																			$tipoExplotacion['id_evento_sanitario'],
																			$tipoExplotacion['especie'], 
																			$tipoExplotacion['tipo_explotacion'],  
																			$ruta);
					}
				?>
			</table>
		</fieldset>

</div>


<!--Notificacion y cronologia -->
<div class="pestania">
	<h2>Cronología </h2>
	
	<form id="nuevaCronologia" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarCronologia" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		
		<fieldset>
			<legend>Cronología</legend>		
		
			<div data-linea="33">
				<label>Tipo:</label>
					<select id="tipoCronologia" name="tipoCronologia" required="required">
						<option value="">Seleccione....</option>
						<?php 
							while ($tipo = pg_fetch_assoc($tiposCronologias)){
							echo '<option value="' . $tipo['codigo'] . '">' . $tipo['nombre'] . '</option>';
						}
						?>
					</select> 
					<input type="hidden" id="nombreCronologia" name="nombreCronologia" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
			</div>
			
			<div data-linea="34">
				<label>Fecha:</label>
				<input type="text" id="fechaCronologia" name="fechaCronologia" required="required" />
			</div>
			
			<input type="hidden" id="horaCronologia" name="horaCronologia" value="00:00" readonly="readonly"/>
						
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
		
		</fieldset>
	</form>
	
	<fieldset id="detalleCronologiaFS">
		<legend>Cronología</legend>
		<table id="detalleCronologia">
			<thead>
				<tr>
					<th width="15%">Tipo</th>
					<th width="15%">Fecha</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($cronologiaG = pg_fetch_assoc($cronologia)){
					echo $cpco->imprimirLineaCronologia(	$cronologiaG['id_cronologia'], 
															$cronologiaG['id_evento_sanitario'],
															$cronologiaG['nombre_tipo_cronologia'], 
															$cronologiaG['fecha_cronologia'], 
															$cronologiaG['hora_cronologia'], 
															$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleCronologiaConsultaFS">
		<legend>Cronología</legend>
		<table id="detalleCronologia">
			<thead>
				<tr>
					<th width="15%">Tipo</th>
					<th width="15%">Fecha</th>
				</tr>
			</thead>
			<?php 
				while ($cronologiaGC = pg_fetch_assoc($cronologiaConsulta)){
					echo $cpco->imprimirLineaCronologiaConsulta(	$cronologiaGC['id_cronologia'], 
																	$cronologiaGC['id_evento_sanitario'],
																	$cronologiaGC['nombre_tipo_cronologia'], 
																	$cronologiaGC['fecha_cronologia'], 
																	$cronologiaGC['hora_cronologia'], 
																	$ruta);
				}
			?>
		</table>
	</fieldset>

</div>

<!--Especies Afectadas -->
<div class="pestania">
	<h2>Especie animal afectada</h2>
	
<form id="nuevaEspecieAfectada" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarEspecieAfectada" data-destino="detalleItem">
	<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
	
	<fieldset>
		<legend>Especies Afectadas</legend>
		
			<div data-linea="35">
				<label>Especie Afectada:</label>
					<select id="especieAfectada" name="especieAfectada" required="required">
						<option value="">Seleccione....</option>
						<?php 
							while ($especie = pg_fetch_assoc($especiesAfectadas)){
							echo '<option value="' . $especie['codigo'] . '">' . $especie['nombre'] . '</option>';
						}
						?>
					</select> 
					<input type="text" id="nombreEspecieAfectada" name="nombreEspecieAfectada" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
			</div>
			
			<div data-linea="35">
				<label>Especifique:</label>
				<input type="text" id="especifiqueEspecieAfectada" name="especifiqueEspecieAfectada" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
				
	</fieldset>
</form>
	
	<fieldset id="detalleEspecieAfectadaFS">
		<legend>Especie Afectada</legend>
		<table id="detalleEspecieAfectada">
			<thead>
				<tr>
					<th width="15%">Especie</th>
					<th width="15%">Especificación</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($especie = pg_fetch_assoc($especieAnimalAfectada)){
					echo $cpco->imprimirLineaEspecieAfectada($especie['id_especie_afectada_evento_sanitario'],  
																$especie['id_evento_sanitario'],
																$especie['nombre_especie_afectada'], 
																$especie['especificacion_especie_afectada'], 
																$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleEspecieAfectadaConsultaFS">
		<legend>Especie Afectada</legend>
		<table id="detalleEspecieAfectada">
			<thead>
				<tr>
					<th width="15%">Especie</th>
					<th width="15%">Especificación</th>
				</tr>
			</thead>
			<?php 
				while ($especie = pg_fetch_assoc($especieAnimalAfectadaConsulta)){
					echo $cpco->imprimirLineaEspecieAfectadaConsulta(	$especie['id_especie_afectada'], 
																		$especie['id_evento_sanitario'],
																		$especie['nombre_especie_afectada'], 
																		$especie['especificacion_especie_afectada'], 
																		$ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<!--Vacunacion -->
<div class="pestania">
	<h2>Vacunación</h2>
	
	<form id="nuevaVacunacionAftosa" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarVacunacionAftosa" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		
		<fieldset>
			<legend>Vacunación</legend>
			
			<div data-linea="2">
					<label>Tipo Vacunación:</label>
						<select id="vacunacion" name="vacunacion" required="required">
							<option value="">Seleccione....</option>
							<?php 
								while ($vacuna = pg_fetch_assoc($vacunaciones)){
								echo '<option value="' . $vacuna['codigo'] . '">' . $vacuna['nombre'] . '</option>';
							}
							?>
						</select> 
						<input type="hidden" id="nombreVacunacion" name="nombreVacunacion" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
				</div>
				
				<div data-linea="3">
					<label id="lEnfermedad">Enfermedad:</label>
						<select id="vacunacionAftosa" name="vacunacionAftosa" required="required">
							<option value="">Seleccione....</option>
							<?php 
								while ($vacunacion = pg_fetch_assoc($vacunacionesAftosa)){
								echo '<option value="' . $vacunacion['codigo'] . '">' . $vacunacion['nombre'] . '</option>';
							}
							?>
						</select> 
						<input type="text" id="nombreVacunacionAftosa" name="nombreVacunacionAftosa" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
				</div>
				
				<div data-linea="4">
					<label id="lFec">Fecha :</label>
					<input type="text" id="fechaVacunacionAftosa" name="fechaVacunacionAftosa" required="required"/>
				</div>
				
				<div data-linea="4">
					<label id="lLote">Lote:</label>
					<input type="text" id="loteVacunacionAftosa" name="loteVacunacionAftosa" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" size="15" required="required"/>
				</div>
				
				<div data-linea="5">
					<label id="lnumCert">Número certificado:</label>
					<input type="text" id="numeroCertificadoVacunacionAftosa" name="numeroCertificadoVacunacionAftosa" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" size="15" required="required"/>
				</div>
				
				<div data-linea="5">
					<label id="lLab">Laboratorio:</label>
						<input type="text" id="nombreLaboratorioAftosa" name="nombreLaboratorioAftosa" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
				</div>
				
				<div data-linea="6">
					<label id="lObs">Observaciones:</label>
					<input type="text" id="observacionVacunacion" name="observacionVacunacion" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
				</div>
				
				<div>
					<button type="submit" class="mas">Agregar</button>		
				</div>
				
		</fieldset>
	</form>

	<fieldset id="detalleVacunacionAftosaFS">
		<legend>Vacunación</legend>
		<table id="detalleVacunacionAftosa">
			<thead>
				<tr>
					<th width="15%">Tipo Vacunación</th>
					<th width="15%">Enfermedad</th>
					<th width="15%">Fecha</th>
					<th width="15%">Lote</th>
					<th width="15%">Número certificado</th>
					<th width="15%">Laboratorio</th>
					<th width="15%">Observaciones</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($vacunacion = pg_fetch_assoc($vacunacionAftosa)){
					echo $cpco->imprimirLineaVacunacionAftosa(	$vacunacion['id_vacunacion_aftosa'], 
																$vacunacion['id_evento_sanitario'],
																$vacunacion['nombre_tipo_vacunacion_aftosa'], 
																$vacunacion['fecha_vacunacion_aftosa'], 
																$vacunacion['lote_vacunacion_aftosa'], 
																$vacunacion['numero_certificado_vacunacion_aftosa'], 
																$vacunacion['nombre_laboratorio_vacunacion_aftosa'], 
																$ruta,
																$vacunacion['enfermedad'],
																$vacunacion['observaciones']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleVacunacionAftosaConsultaFS">
		<legend>Vacunación</legend>
		<table id="detalleVacunacionAftosa">
			<thead>
				<tr>
					<th width="15%">Tipo Vacunación</th>
					<th width="15%">Enfermedad</th>
					<th width="15%">Fecha</th>
					<th width="15%">Lote</th>
					<th width="15%">Número certificado</th>
					<th width="15%">Laboratorio</th>
					<th width="15%">Observaciones</th>
				</tr>
			</thead>
			<?php 
				while ($vacunacion = pg_fetch_assoc($vacunacionAftosaConsulta)){
					echo $cpco->imprimirLineaVacunacionAftosaConsulta(	$vacunacion['id_vacunacion_aftosa'], 
																		$vacunacion['id_evento_sanitario'],
																		$vacunacion['nombre_tipo_vacunacion_aftosa'], 
																		$vacunacion['fecha_vacunacion_aftosa'], 
																		$vacunacion['lote_vacunacion_aftosa'], 
																		$vacunacion['numero_certificado_vacunacion_aftosa'], 
																		$vacunacion['nombre_laboratorio_vacunacion_aftosa'], 
																		$ruta,
																		$vacunacion['enfermedad'],
																		$vacunacion['observaciones']);
				}
			?>
		</table>
	</fieldset>	

</div>
	
<div class="pestania">
<form id="nuevaOrigenAnimales" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarOrigenAnimales" data-destino="detalleItem">
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		<input type="hidden" id="numeroVisita" name="numeroVisita" value="Visita 0000"/>
		
		<fieldset>
			<legend>Origen de los animales enfermos</legend>
		
			<div data-linea="2" id="paisesOrigen">
				<label>País:</label>
					<select id="paisOrigen" name="paisOrigen" required="required">
						<option value="">Seleccione....</option>
						<?php 
							$paises = $cc->listarSitiosLocalizacion($conexion,'PAIS');
							foreach ($paises as $pais){
								echo '<option value="' . $pais['codigo'] . '">' . $pais['nombre'] . '</option>';
							}
						?>
					</select> 
					<input type="hidden" id="nombrePaisOrigen" name="nombrePaisOrigen" />
			</div>
			
			<div data-linea="2" id="provinciasOrigen">
				<label>Provincia:</label>
					<select id="provinciaOrigen" name="provinciaOrigen" disabled="disabled" required="required">
					</select> 
					<input type="hidden" id="nombreProvinciaOrigen" name="nombreProvinciaOrigen" />
			</div>
			
			<div data-linea="3" id="cantonesOrigen">
				<label>Cantón:</label>
					<select id="cantonOrigen" name="cantonOrigen" disabled="disabled" required="required">
					</select> 
					<input type="hidden" id="nombreCantonOrigen" name="nombreCantonOrigen" />
			</div>
			
			<div data-linea="3">
				<label>Fecha:</label>
				<input type="text" id="fechaOrigen" name="fechaOrigen" required="required" />
			</div>
			
			<div data-linea="1">
				<label>Lugar de Origen:</label>
					<select id="origenAnimal" name="origenAnimal" required="required">
						<option value="">Seleccione....</option>
						<?php 
							while ($origen = pg_fetch_assoc($origenes)){
							echo '<option value="' . $origen['codigo'] . '">' . $origen['nombre'] . '</option>';
						}
						?>
					</select> 
					<input type="hidden" id="nombreOrigenAnimal" name="nombreOrigenAnimal" />
			</div>

			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
		</fieldset>
	</form>
	
	<fieldset id="detalleOrigenFS">
		<legend>Origen Animales</legend>
		<table id="detalleOrigenAnimales">
			<thead>
				<tr>
					<th width="15%">Visita</th>
					<th width="15%">Origen de los animales enfermos</th>
					<th width="15%">País</th>
					<th width="15%">Provincia</th>
					<th width="15%">Cantón</th>
					<th width="15%">Fecha</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($origen = pg_fetch_assoc($origenAnimal)){
					echo $cpco->imprimirLineaOrigen(	$origen['id_origen_animales'],
														$origen['id_evento_sanitario'],
														$origen['nombre_origen'], 
														$origen['nombre_pais'], 
														$origen['nombre_provincia'],
														$origen['canton'],
														$origen['fecha_origen'], 
														$ruta,
														$origen['numero_visita']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleOrigenConsultaFS">
		<legend>Origen Animales</legend>
		<table id="detalleOrigenAnimales">
			<thead>
				<tr>
					<th width="15%">Visita</th>
					<th width="15%">Origen de los animales enfermos</th>
					<th width="15%">País</th>
					<th width="15%">Provincia</th>
					<th width="15%">Cantón</th>
					<th width="15%">Fecha</th>
				</tr>
			</thead>
			<?php 
				while ($origen = pg_fetch_assoc($origenAnimalConsulta)){
					echo $cpco->imprimirLineaOrigenConsulta(	$origen['id_origen_animales'],
																$origen['id_evento_sanitario'],
																$origen['nombre_origen'], 
																$origen['nombre_pais'], 
																$origen['nombre_provincia'],
																$origen['canton'],
																$origen['fecha_origen'],  
																$ruta,
																$origen['numero_visita']);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<form id="nuevaProcedimiento" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarProcedimiento" data-destino="detalleItem">
	<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
	
	<h2>Sintomatología, lesiones, 1er animal enfermo, síndrome</h2>
	
		<fieldset>
			<legend>Sintomatología</legend>
			<div data-linea="1">
				<label>Sintomatología:</label>
				<input type="text" id="sintomatologia" name="sintomatologia" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$" required="required"/>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>Lesiones en la necropsia</legend>
			<div data-linea="2">
				<label>Lesiones en la necropsia:</label>
				<input type="text" id="lecionesNecropsia" name="lecionesNecropsia" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$" required="required"/>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>1er Animal enfermo</legend>
			<div data-linea="3">
				<label>Especie:</label>
					<select id="especiePrimerAnimal" name="especiePrimerAnimal" required="required">
						<option value="">Seleccione....</option>
						<?php 
							while ($especies = pg_fetch_assoc($especiesPrimerAnimal)){
							echo '<option value="' . $especies['codigo'] . '">' . $especies['nombre'] . '</option>';
						}
						?>
					</select>
				<input type="hidden" id="nombreEspeciePrimerAnimal" name="nombreEspeciePrimerAnimal" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$"/>
			</div>
			
			<div data-linea="4">
				<label>Edad en meses:</label>
				<input type="number" id="edadPrimerAnimal" name="edadPrimerAnimal" required="required" />
			</div>
			
			<div data-linea="6">
				<label>Ingresado?:</label>
					<select id="ingresadoPrimerAnimal" name="ingresadoPrimerAnimal" required="required">
						<option value="">Seleccione....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>Sindrome presuntivo</legend>
			<div data-linea="7">
				<label>Sindrome presuntivo:</label>
				<select id="sindromePresuntivo" name="sindromePresuntivo" required="required">
					<option value="">Seleccione....</option>
					<?php 
						while ($patologia = pg_fetch_assoc($patologias)){
							echo '<option value="' . $patologia['nombre'] . '">' . $patologia['nombre'] . '</option>';
						}
					?>
				</select>
			</div>
		</fieldset>
		
		<!-- >fieldset>
			<legend>Planificación de Visita</legend>
			<div data-linea="7">
				<label>Primera Visita:</label>
				<input type="text" id="fechaInspeccion" name="fechaInspeccion" required="required"/>
			</div>
		</fieldset-->
		
		<div>
			<button type="submit" class="guardar">Guardar y Finalizar</button>		
		</div>
		
	</form>

</div>


<script type="text/javascript">

	var usuario = <?php echo json_encode($usuario); ?>;
	var array_provincia= <?php echo json_encode($provincias); ?>;
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

		cargarValorDefecto("otroPredio","<?php echo $eventoSanitario['otros_predios'];?>");
		cargarValorDefecto("bioseguridad","<?php echo $eventoSanitario['bioseg'];?>");
		cargarValorDefecto("zonaPredio","<?php echo $eventoSanitario['huso_zona'];?>");

		$("#actualizacion").hide();
		$("#nombreVacunacionAftosa").hide();
		
		$('#detalleExplotacionConsultaFS').hide();
		$('#detalleCronologiaConsultaFS').hide();
		$('#detalleEspecieAfectadaConsultaFS').hide();
		$('#detalleVacunacionAftosaConsultaFS').hide();
		$('#detalleVacunacionConsultaFS').hide();
		$('#detalleMuestraConsultaFS').hide();
		$('#detalleOrigenConsultaFS').hide();
		$('#detallePoblacionConsultaFS').hide();
		$('#detalleIngresosConsultaFS').hide();
		$('#detalleEgresosConsultaFS').hide();
		$('#detalleCronologiaFinalConsultaFS').hide();
		$('#detalleDiagnosticoFinalConsultaFS').hide();
		$('#detallePoblacionFinalConsultaFS').hide();
		$('#detalleVacunacionFinalConsultaFS').hide();
		$('#detalleOrigenConsultaFS').hide();
		
		$('#nombreEspecieAfectada').hide();
		$('#nombreDiagnosticoFinal').hide();
		$('#nombreEspecieFinal').hide();
		$('#nombreCategoriaFinal').hide();

		$('#lEnfermedad').hide();
		$('#vacunacionAftosa').hide();
		$('#lFec').hide();
		$('#fechaVacunacionAftosa').hide();
		$('#lLote').hide();
		$('#loteVacunacionAftosa').hide();
		$('#lnumCert').hide();
		$('#numeroCertificadoVacunacionAftosa').hide();
		$('#lLab').hide();
		$('#nombreLaboratorioAftosa').hide();
		$('#lObs').hide();
		$('#observacionVacunacion').hide();
		

		$("#fechaInspeccion").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
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
		
		$("#fechaCronologiaFinal").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		acciones("#nuevaExplotacion","#detalleExplotacion");
		acciones("#nuevaCronologia","#detalleCronologia");
		acciones("#nuevaEspecieAfectada","#detalleEspecieAfectada");
		acciones("#nuevaVacunacionAftosa","#detalleVacunacionAftosa");
		acciones("#nuevaVacunacion","#detalleVacunacion");
		acciones("#nuevaOrigenAnimales","#detalleOrigenAnimales");		

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
		$("#adjuntos").hide();
		$("#adjuntosInforme").hide();		
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

		/*if(!$.trim($("#telefonoPropietario").val()) || !esCampoValido("#telefonoPropietario")){
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
		}*/

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

		if(!$.trim($("#numeroPredios").val())){
			error = true;
			$("#numeroPredios").addClass("alertaCombo");
		}
                
                if ($('#numeroPredios').is(':visible')) {
                    if ($('#numeroPredios').val()<=0){
                        error = true;
			$("#numeroPredios").addClass("alertaCombo");
                    }
                } else {
                    $('#numeroPredios').val(0);
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
        
	fn_verificarOtroPredio($("#otroPredio").val());
        
	$("#otroPredio").change(function(event){
            fn_verificarOtroPredio($(this).val());
	});
        
        function fn_verificarOtroPredio(valor) {
            if(valor ==='No') {
                $("#numeroPredios").hide();
                $("#lnumeroPredios").hide();
            } else{
                $("#numeroPredios").show();
                $("#lnumeroPredios").show();
            }
        }
	
	$("#unidadMedida").change(function(){
    	$("#medidaPredio").val($("#unidadMedida option:selected").text());
	});
	
	$("#oficina").change(function(){
    	$("#nombreOficina").val($("#oficina option:selected").text());
	});

	$("#archivoMapa").click(function(){
    	$("#subirArchivoMapa button").removeAttr("disabled");
    });












	$("#especie").change(function(event){
    	$("#nombreEspecie").val($("#especie option:selected").text());

		$('#nuevaExplotacion').attr('data-destino','finalidad');
		$('#nuevaExplotacion').attr('data-opcion','combosEventoSanitario');
	    $('#opcion').val('buscarFinalidad');
	    		
		abrir($("#nuevaExplotacion"),event,false);  

		$('#nuevaExplotacion').attr('data-destino','detalleItem');
		$('#nuevaExplotacion').attr('data-opcion','guardarExplotacion');  	
	});

	
	
	$("#tipoCronologia").change(function(){
    	$("#nombreCronologia").val($("#tipoCronologia option:selected").text());
	});

	$("#especieAfectada").change(function(){
    	$("#nombreEspecieAfectada").val($("#especieAfectada option:selected").text());
	});

	$("#vacunacionAftosa").change(function(){
    	$("#nombreVacunacionAftosa").val($("#vacunacionAftosa option:selected").text());

    		if($("#vacunacionAftosa option:selected").val() =='0'){
    			$("#vacunacionAftosa").val('0');
    			$("#nombreVacunacionAftosa").val('');
    			$("#nombreVacunacionAftosa").show();
    		}else{
    			$("#nombreVacunacionAftosa").hide();
    			$("#nombreVacunacionAftosa").val($("#vacunacionAftosa option:selected").text());
    		}
    	
	});

	$("#laboratorioAftosa").change(function(){
    	$("#nombreLaboratorioAftosa").val($("#laboratorioAftosa option:selected").text());
	});

	$("#vacunacion").change(function(){
		if($("#vacunacion option:selected").text()!='No Vacunada'){

			$('#lEnfermedad').show();
    		$('#vacunacionAftosa').show();
    		$('#vacunacionAftosa').val('');
    		$('#vacunacionAftosa').attr('required','required');
    		$("#nombreVacunacionAftosa").val('');
    		$('#lFec').show();
    		$('#fechaVacunacionAftosa').show();
    		$('#fechaVacunacionAftosa').attr('required','required');
    		$('#lLote').show();
    		$('#loteVacunacionAftosa').show();
    		$('#loteVacunacionAftosa').val('');
    		$('#loteVacunacionAftosa').attr('required','required');
    		$('#lnumCert').show();
    		$('#numeroCertificadoVacunacionAftosa').show();
    		$('#numeroCertificadoVacunacionAftosa').val('');
    		$('#numeroCertificadoVacunacionAftosa').attr('required','required');
    		$('#lLab').show();
    		$('#nombreLaboratorioAftosa').show();
    		$('#nombreLaboratorioAftosa').val('');
    		$('#nombreLaboratorioAftosa').attr('required','required');
    		$('#lObs').show();
    		$('#observacionVacunacion').show();
    		$('#observacionVacunacion').val('');
    		$('#observacionVacunacion').attr('required','required');
    		
    		
    	}else{

    		$('#lEnfermedad').hide();
    		$('#vacunacionAftosa').hide();
    		$('#vacunacionAftosa').val('0');
    		$('#vacunacionAftosa').removeAttr('required');
    		$("#nombreVacunacionAftosa").val('No Aplica');
    		$('#lFec').hide();
    		$('#fechaVacunacionAftosa').val('');
    		$('#fechaVacunacionAftosa').hide();
    		$('#fechaVacunacionAftosa').removeAttr('required');
    		$('#lLote').hide();
    		$('#loteVacunacionAftosa').hide();
    		$('#loteVacunacionAftosa').val('No Aplica');
    		$('#loteVacunacionAftosa').removeAttr('required');
    		$('#lnumCert').hide();
    		$('#numeroCertificadoVacunacionAftosa').hide();
    		$('#numeroCertificadoVacunacionAftosa').val('No Aplica');
    		$('#numeroCertificadoVacunacionAftosa').removeAttr('required');
    		$('#lLab').hide();
    		$('#nombreLaboratorioAftosa').hide();
    		$('#nombreLaboratorioAftosa').val('No Aplica');
    		$('#nombreLaboratorioAftosa').removeAttr('required');
    		$('#lObs').hide();
    		$('#observacionVacunacion').hide();
    		$('#observacionVacunacion').val('No Aplica');
    		$('#observacionVacunacion').removeAttr('required');
    	}

		$("#nombreVacunacion").val($("#vacunacion option:selected").text());
	});

	
	
	$("#enfermedadVacunaAve").change(function(){
    	$("#enfermedadVacunacionAves").val($("#enfermedadVacunaAve option:selected").text());
	});

	$("#tipoVacunacionAve").change(function(){
    	$("#nombreTipoVacunacionAves").val($("#tipoVacunacionAve option:selected").text());
	});

	
	$("#especiePrimerAnimal").change(function(){
    	$("#nombreEspeciePrimerAnimal").val($("#especiePrimerAnimal option:selected").text());
	});

	$("#nuevaProcedimiento").submit(function(event){

		$("#nuevaProcedimiento").attr('data-opcion', 'guardarProcedimiento');
	    $("#nuevaProcedimiento").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#sintomatologia").val()) || !esCampoValido("#sintomatologia")){
			error = true;
			$("#sintomatologia").addClass("alertaCombo");
		}
		
		if(!$.trim($("#lecionesNecropsia").val()) || !esCampoValido("#lecionesNecropsia")){
			error = true;
			$("#lecionesNecropsia").addClass("alertaCombo");
		}
		
		if(!$.trim($("#especiePrimerAnimal").val())){
			error = true;
			$("#especiePrimerAnimal").addClass("alertaCombo");
		}


		if(!$.trim($("#edadPrimerAnimal").val())){
			error = true;
			$("#edadPrimerAnimal").addClass("alertaCombo");
		}
		
		if(!$.trim($("#ingresadoPrimerAnimal").val())){
			error = true;
			$("#ingresadoPrimerAnimal").addClass("alertaCombo");
		}

		if(!$.trim($("#sindromePresuntivo").val()) || !esCampoValido("#sindromePresuntivo")){
			error = true;
			$("#sindromePresuntivo").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			abrir($(this),event,false);
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
		if($("#provinciaOrigen option:selected").text() != "No Aplica"){
			scanton ='0';
			scanton = '<option value="">Seleccione...</option>';
		    for(var i=0;i<array_canton.length;i++){
			    if ($("#provinciaOrigen").val()==array_canton[i]['padre']){
			    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
				}
		   	}
    	}else{
    		scanton = '<option value="">Seleccione...</option>';
    		scanton += '<option value="0">No Aplica</option>';
    	}
		
	    $('#cantonOrigen').html(scanton);
	    $("#cantonOrigen").removeAttr("disabled");
	    $("#nombreProvinciaOrigen").val($("#provinciaOrigen option:selected").text());
	});

	$("#cantonOrigen").change(function(){
		$("#nombreCantonOrigen").val($("#cantonOrigen option:selected").text());
	});

	$("#archivo").click(function(){
    	$("#subirArchivo button").removeAttr("disabled");
    });
</script>