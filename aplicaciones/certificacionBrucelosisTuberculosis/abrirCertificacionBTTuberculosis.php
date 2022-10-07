<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorBrucelosisTuberculosis.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	$cbt = new ControladorBrucelosisTuberculosis();
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Certificación Brucelosis y Tuberculosis'),0,'id_perfil');
	}
	
	$ruta = 'certificacionBrucelosisTuberculosis';
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	
	$laboratorios = $cbt->abrirCatalogoLaboratoriosCertificacionBT($conexion);
	
	$idCertificacionBT = $_POST['id'];
	$certificacionBT = pg_fetch_assoc($cbt->abrirCertificacionBT($conexion, $idCertificacionBT));
	
	$informacionPredio = $cbt->abrirInformacionPredioCertificacionBT($conexion, $idCertificacionBT);
	$informacionPredioConsulta = $cbt->abrirInformacionPredioCertificacionBT($conexion, $idCertificacionBT);
	
	$produccion = $cbt->abrirProduccionCertificacionBT($conexion, $idCertificacionBT);
	$produccionConsulta = $cbt->abrirProduccionCertificacionBT($conexion, $idCertificacionBT);
	
	$inventario = $cbt->abrirInventarioAnimalCertificacionBT($conexion, $idCertificacionBT);
	$inventarioConsulta = $cbt->abrirInventarioAnimalCertificacionBT($conexion, $idCertificacionBT);
	
	$pediluvio = $cbt->abrirPediluvioCertificacionBT($conexion, $idCertificacionBT);
	$pediluvioConsulta = $cbt->abrirPediluvioCertificacionBT($conexion, $idCertificacionBT);
	
	$manejoAnimal = $cbt->abrirManejoAnimalCertificacionBT($conexion, $idCertificacionBT);
	$manejoAnimalConsulta = $cbt->abrirManejoAnimalCertificacionBT($conexion, $idCertificacionBT);
	
	$adquisicionAnimales = $cbt->abrirAdquisicionAnimalesCertificacionBT($conexion, $idCertificacionBT);
	$adquisicionAnimalesConsulta = $cbt->abrirAdquisicionAnimalesCertificacionBT($conexion, $idCertificacionBT);
	
	$procedenciaAgua = $cbt->abrirProcedenciaAguaCertificacionBT($conexion, $idCertificacionBT);
	$procedenciaAguaConsulta = $cbt->abrirProcedenciaAguaCertificacionBT($conexion, $idCertificacionBT);
	
	$veterinario = $cbt->abrirVeterinarioCertificacionBT($conexion, $idCertificacionBT);
	$veterinarioConsulta = $cbt->abrirVeterinarioCertificacionBT($conexion, $idCertificacionBT);
	
	$vacunacion = $cbt->abrirVacunacionCertificacionBT($conexion, $idCertificacionBT);
	$vacunacionConsulta = $cbt->abrirVacunacionCertificacionBT($conexion, $idCertificacionBT);
	
	$reproduccion = $cbt->abrirReproduccionCertificacionBT($conexion, $idCertificacionBT);
	$reproduccionConsulta = $cbt->abrirReproduccionCertificacionBT($conexion, $idCertificacionBT);
	
	$patologia = $cbt->abrirPatologiaBrucelosisCertificacionBT($conexion, $idCertificacionBT);
	$patologiaConsulta = $cbt->abrirPatologiaBrucelosisCertificacionBT($conexion, $idCertificacionBT);
	
	$abortos = $cbt->abrirAbortosBrucelosisCertificacionBT($conexion, $idCertificacionBT);
	$abortosConsulta = $cbt->abrirAbortosBrucelosisCertificacionBT($conexion, $idCertificacionBT);
	
	$pruebasLeche = $cbt->abrirPruebasBrucelosisLecheCertificacionBT($conexion, $idCertificacionBT);
	$pruebasLecheConsulta = $cbt->abrirPruebasBrucelosisLecheCertificacionBT($conexion, $idCertificacionBT);
	
	$pruebasSangre = $cbt->abrirPruebasBrucelosisSangreCertificacionBT($conexion, $idCertificacionBT);
	$pruebasSangreConsulta = $cbt->abrirPruebasBrucelosisSangreCertificacionBT($conexion, $idCertificacionBT);
	
	$patologiaTuberculosis = $cbt->abrirPatologiaTuberculosisCertificacionBT($conexion, $idCertificacionBT);
	$patologiaTuberculosisConsulta = $cbt->abrirPatologiaTuberculosisCertificacionBT($conexion, $idCertificacionBT);
	
	$pruebasLecheTuberculosis = $cbt->abrirPruebaTuberculosisLecheCertificacionBT($conexion, $idCertificacionBT);
	$pruebasLecheTuberculosisConsulta = $cbt->abrirPruebaTuberculosisLecheCertificacionBT($conexion, $idCertificacionBT);
	
	$pruebasTuberculina = $cbt->abrirPruebaTuberculinaCertificacionBT($conexion, $idCertificacionBT);
	$pruebasTuberculinaConsulta = $cbt->abrirPruebaTuberculinaCertificacionBT($conexion, $idCertificacionBT);
?>

<header>
	<h1>Predios para Certificación como Libres de Tuberculosis Bovina</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>

<div class="pestania">
	<h2>Identificación y Localización del Predio</h2>
	
	<form id="modificarCertificacionBT" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="modificarCertificacionBT" data-destino="detalleItem" >
			<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
			<input type='hidden' id='certificacion' name='certificacion' value="<?php echo $certificacionBT['certificacion_bt'];?>" />
			<input type='hidden' id='estado' name='estado' value="<?php echo $certificacionBT['estado'];?>" />	
		
		<p>
			<button id="modificar" type="button" class="editar">Modificar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</p>
		
		<div id="informacion">		
			<fieldset>
				<legend>Información de Localización del Predio</legend>
		
				<div data-linea="0">
					<label>N° Solicitud:</label>
					<?php echo $certificacionBT['num_solicitud'];?>
				</div>
				
				<!--<div data-linea="0" >
					<div id='siguienteInspeccion'>
						<label>Fecha Siguiente Inspección:</label>
						< ?php echo date('j/n/Y',strtotime($certificacionBT['fecha_nueva_inspeccion']));?>
					</div>
				</div>-->
					
				<div data-linea="1">
					<label>Fecha:</label>
					<?php echo date('j/n/Y',strtotime($certificacionBT['fecha']));?>
				</div>
				
				<div data-linea="2">
					<label>Nombre del Encuestado:</label>
					<?php echo $certificacionBT['nombre_encuestado'];?>
				</div>
				
				<div data-linea="2">
					<label>Nombre del Predio:</label>
					<?php echo $certificacionBT['nombre_predio'];?>
				</div>
				
				<div data-linea="3">
					<label>Num. Cert. Fiebre Aftosa:</label>
					<?php echo $certificacionBT['numero_certificado_fiebre_aftosa'];?>
				</div>
				
				<div data-linea="3">
					<label>Certificación:</label>
					<?php echo $certificacionBT['certificacion_bt'];?>
				</div>
			
			</fieldset>
			
			<fieldset>
				<legend>Información del Propietario</legend>			
				
				<div data-linea="4">
					<label>Nombre:</label>
					<?php echo $certificacionBT['nombre_propietario'];?>
				</div>
				
				<div data-linea="4">
					<label>Cédula:</label>
					<?php echo $certificacionBT['cedula_propietario'];?>
				</div>
				
				<div data-linea="5">
					<label>Teléfono:</label>
					<?php echo $certificacionBT['telefono_propietario'];?>
				</div>
				
				<div data-linea="5">
					<label>Celular:</label>
					<?php echo $certificacionBT['celular_propietario'];?>
				</div>
				
				<div data-linea="6">
					<label>Correo Electrónico:</label>
					<?php echo $certificacionBT['correo_electronico_propietario'];?>
				</div>
				
			</fieldset>
			
			<fieldset>
				<legend>Ubicación y Datos Generales</legend>
		
				<div data-linea="7">
					<label>Provincia</label>
					<?php echo $certificacionBT['provincia'];?>	
				</div>
					
				<div data-linea="7">
					<label>Cantón</label>
						<?php echo $certificacionBT['canton'];?>
					</div>
					
				<div data-linea="8">	
					<label>Parroquia</label>
						<?php echo $certificacionBT['parroquia'];?>
				</div>
							
			</fieldset>
			
			<fieldset>
				<legend>Coordenadas</legend>
				
				<div data-linea="9">
					<label>X:</label>
					<?php echo $certificacionBT['utm_x'];?>
				</div>
				
				<div data-linea="9">
					<label>Y:</label>
					<?php echo $certificacionBT['utm_y'];?>
				</div>
				
				<div data-linea="9">
					<label>Z:</label>
					<?php echo $certificacionBT['utm_z'];?>
				</div>
				
				<div data-linea="9">
					<label>Huso/Zona:</label>
					<?php echo $certificacionBT['huso_zona'];?>
				</div>
		
			</fieldset>	
								
			<!-- div data-linea="9">
				<button id="modificar" type="button" class="editar">Editar</button>
			</div-->
		</div>
		
		<div id="actualizacion">
			<fieldset>
				<legend>Información de Localización del Predio</legend>
		
				<div data-linea="10">
					<label>N° Solicitud:</label>
					<?php echo $certificacionBT['num_solicitud'];?>
				</div>
		
				<div data-linea="11">
					<label>Fecha:</label>
					<input type="text" id="fecha" name="fecha" value="<?php echo date('j/n/Y',strtotime($certificacionBT['fecha']));?>"/>
				</div>
				
				<div data-linea="12">
					<label>Nombre del Encuestado:</label>
					<input type="text" id="nombreEncuestado" name="nombreEncuestado" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $certificacionBT['nombre_encuestado'];?>"/>
				</div>
				
				<div data-linea="12">
					<label>Nombre del Predio:</label>
					<?php echo $certificacionBT['nombre_predio'];?>
				</div>
				
				<div data-linea="13">
					<label>Num. Cert. Fiebre Aftosa:</label>
					<input type="text" id="numCertFiebreAftosa" name="numCertFiebreAftosa" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $certificacionBT['numero_certificado_fiebre_aftosa'];?>" data-inputmask="'mask': '9999-999-9999999'"/>
				</div>
				
				<div data-linea="13">
					<label>Certificación:</label>
					<?php echo $certificacionBT['certificacion_bt'];?>	
				</div>
				
			</fieldset>
			
			<fieldset>
				<legend>Información del Propietario</legend>
				
				<div data-linea="14">
					<label>Nombre:</label>
					<?php echo $certificacionBT['nombre_propietario'];?>
				</div>
				
				<div data-linea="14">
					<label>Cédula:</label>
					<?php echo $certificacionBT['cedula_propietario'];?>
				</div>
				
				<div data-linea="15">
					<label>Teléfono:</label>
					<input type="text" id="telefonoPropietario" name="telefonoPropietario" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15" value="<?php echo $certificacionBT['telefono_propietario'];?>"/>
				</div>
				
				
				<div data-linea="15">
					<label>Celular:</label>
					<input type="text" id="celularPropietario" name="celularPropietario" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" data-inputmask="'mask': '(99) 9999-9999'" size="16" value="<?php echo $certificacionBT['celular_propietario'];?>"/>
				</div>
				
				<div data-linea="16">
					<label>Correo Electrónico:</label>
					<input type="text" id="correoElectronicoPropietario" name="correoElectronicoPropietario" maxlength="32" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" value="<?php echo $certificacionBT['correo_electronico_propietario'];?>"/>
				</div>
				
			</fieldset>
			
			<fieldset>
				<legend>Ubicación y Datos Generales</legend>
		
				<div data-linea="17">
					<label>Provincia</label>
					<?php echo $certificacionBT['provincia'];?>
				</div>
					
				<div data-linea="17">
				<label>Cantón</label>
					<?php echo $certificacionBT['canton'];?>
				</div>
				
				<div data-linea="18">	
				<label>Parroquia</label>
					<?php echo $certificacionBT['parroquia'];?>
				</div>
				
			</fieldset>
			
			<fieldset>
				<legend>Coordenadas</legend>
		
				<div data-linea="19">
					<label>X:</label>
					<input type="text" id="x" name="x" maxlength="6" data-er="^[0-9]+$" value="<?php echo $certificacionBT['utm_x'];?>"/>
				</div>
				
				<div data-linea="19">
					<label>Y:</label>
					<input type="text" id="y" name="y" maxlength="7" data-er="^[0-9]+$" value="<?php echo $certificacionBT['utm_y'];?>"/>
				</div>
				
				<div data-linea="19">
					<label>Z:</label>
					<input type="text" id="z" name="z" maxlength="4" data-er="^[0-9]+$" value="<?php echo $certificacionBT['utm_z'];?>"/>
				</div>
				
				<div data-linea="19">
					<label>Huso/Zona:</label>
						<select id="huso" name="huso">
							<option value="">Seleccione....</option>
							<option value="17M">17M</option>
							<option value="17N">17N</option>
							<option value="18M">18M</option>
							<option value="18N">18N</option>
						</select>
				</div>
		
			</fieldset>
		
		</div>
	
	</form>
	
	<fieldset id="adjuntos">
		<legend>Mapa de Ubicación</legend>

			<div data-linea="1">
				<label>Mapa:</label>
				<?php echo ($certificacionBT['imagen_mapa']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$certificacionBT['imagen_mapa'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Mapa cargado</a>')?>
			</div>
			
			<form id="subirArchivo" action="aplicaciones/certificacionBrucelosisTuberculosis/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
				
				<input type="file" name="archivo" id="archivo" accept="application/pdf" /> 
				<input type="hidden" name="id" value="<?php echo $certificacionBT['id_certificacion_bt'];?>" />
				<input type="hidden" name="aplicacion" value="certificacionBT" /> 
				
				<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
			</form>
			<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
	</fieldset>
	
	<fieldset id="adjuntosInforme">
		<legend>Informe</legend>

			<div data-linea="1">
				<label>Documentos Habilitantes (Solicitud, carta de compromiso, CUV, formulario de visita):</label>
				<?php echo ($certificacionBT['ruta_informe']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$certificacionBT['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe cargado</a>')?>
			</div>
			
			<form id="subirArchivoInforme" action="aplicaciones/certificacionBrucelosisTuberculosis/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergenteInforme" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
				
				<input type="file" name="archivo" id="archivoInforme" accept="application/pdf" /> 
				<input type="hidden" name="id" value="<?php echo $certificacionBT['id_certificacion_bt'];?>" />
				<input type="hidden" name="aplicacion" value="InformeCertificacionBT" /> 
				
				<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
			</form>
			<iframe name="ventanaEmergenteInforme" class="ventanaEmergente"></iframe>
	</fieldset>	
	
</div>

<div class="pestania">

	<h2>Datos Generales del Predio</h2>

	<form id="nuevaInformacionPredio" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarDatosGenerales" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
	
		<fieldset>
			<legend>Información del Predio</legend>
			
			<div data-linea="22">
				<label>Superficie del Predio (Ha.):</label>
				<input type="text" id="superficiePredio" name="superficiePredio" data-er="^[0-9.]+$" required="required" />
			</div>
				
			<div data-linea="22">
				<label>Superficie de Pastos (Ha.):</label>
				<input type="text" id="superficiePastos" name="superficiePastos" data-er="^[0-9.]+$" required="required" />
			</div>
			
			<div data-linea="23">
				<label>Cerramientos ext. buen estado:</label>
				<select id="cerramientoExterno" name="cerramientoExterno" required="required" >
						<option value="">Cerramiento....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
			
			<div data-linea="23">
				<label>Control ingreso personas:</label>
				<select id="controlIngresoPersonas" name="controlIngresoPersonas" required="required" >
						<option value="">Control Ingreso....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
			
			<div data-linea="24">
				<label>Control ingreso animales:</label>
				<select id="controlIngresoAnimales" name="controlIngresoAnimales" required="required" >
						<option value="">Control Ingreso....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
			
			<div data-linea="24">
				<label>Identificación ind. Bovinos:</label>
				<select id="identificacionBovinos" name="identificacionBovinos" required="required" >
						<option value="">Identificación Bovinos....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
			
			<div data-linea="25">
				<label>Manga, Embudo o Brete buen estado:</label>
				<select id="mangaEmbudoBrete" name="mangaEmbudoBrete" required="required" >
						<option value="">Manga....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
						
		</fieldset>
	</form>
	
	<fieldset id="detalleInformacionPredioFS">
		<legend>Información del Predio Registrada</legend>
		<table id="detalleInformacionPredio">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Superficie del Predio</th>
				    <th width="15%">Superficie de Pastos</th>
				    <th width="15%">Cerramientos</th>				    
				    <th width="15%">Control ingreso Personas</th>
				    <th width="15%">Control ingreso Animales</th>
				    <th width="15%">Identificación ind Bovinos</th>
				    <th width="15%">Manga, embudo, brete</th>				    
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoPredio = pg_fetch_assoc($informacionPredio)){
					echo $cbt->imprimirLineaInformacionPredioCertificacionBT($infoPredio['id_certificacion_bt_informacion_predio'],
												$infoPredio['superficie_predio'], $infoPredio['superficie_pastos'], 
												$infoPredio['cerramientos'], $infoPredio['control_ingreso_personas'],
												$infoPredio['manga_embudo_brete'], $infoPredio['identificacion_bovinos'], 
												$infoPredio['control_ingreso_animal'], $ruta, $infoPredio['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleInformacionPredioConsultaFS">
		<legend>Información del Predio Registrada</legend>
		<table id="detalleInformacionPredioConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Superficie del Predio</th>
				    <th width="15%">Superficie de Pastos</th>
				    <th width="15%">Cerramientos</th>				    
				    <th width="15%">Control ingreso Personas</th>
				    <th width="15%">Control ingreso Animales</th>
				    <th width="15%">Identificación ind Bovinos</th>
				    <th width="15%">Manga, embudo, brete</th>
				</tr>
			</thead>
			<?php 
				while ($infoPredio = pg_fetch_assoc($informacionPredioConsulta)){
					echo $cbt->imprimirLineaInformacionPredioBTConsulta($infoPredio['id_certificacion_bt_informacion_predio'],
												$infoPredio['superficie_predio'], $infoPredio['superficie_pastos'], 
												$infoPredio['cerramientos'], $infoPredio['control_ingreso_personas'],
												$infoPredio['manga_embudo_brete'], $infoPredio['identificacion_bovinos'], 
												$infoPredio['control_ingreso_animal'], $ruta, $infoPredio['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<form id="nuevaProduccionExplotacionDestino" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarProduccionExplotacionDestino" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		
		<fieldset>
			<legend>Producción, Explotación y Destino de Productos</legend>
			
			<div data-linea="26">
				<label>Tipo de Producción:</label>
					<select id="tipoProduccion" name="tipoProduccion" required="required" >
						<option value="">Tipo Producción....</option>
						<option value="1">Leche</option>
						<option value="2">Carne</option>
						<option value="3">Mixta</option>
					</select> 					
					
					<input type="hidden" id="nombreTipoProduccion" name="nombreTipoProduccion" />
			</div>
			
			<div data-linea="26">
				<label>Destino de la Leche:</label>
					<select id="destinoLeche" name="destinoLeche" required="required" >
						<option value="">Destino Leche....</option>
						<option value="1">Consumo en Predio</option>
						<option value="2">Industria Láctea</option>
						<option value="3">Comerciante</option>
						<option value="0">No aplica</option>
					</select> 					
					
					<input type="hidden" id="nombreDestinoLeche" name="nombreDestinoLeche" />
			</div>
			
			<div data-linea="27">
				<label>Tipo de Explotación:</label>
					<select id="tipoExplotacion" name="tipoExplotacion" required="required" >
						<option value="">Tipo Explotación....</option>
						<option value="1">Extensiva</option>
						<option value="2">Intensiva</option>
						<option value="3">Sogueo</option>
					</select> 					
					
					<input type="hidden" id="nombreTipoExplotacion" name="nombreTipoExplotacion" />
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
		</fieldset>
	</form>
	
	<fieldset id="detalleProduccionExplotacionDestinoFS">
		<legend>Producción, Explotación y Destinos Registrados</legend>
		<table id="detalleProduccionExplotacionDestino">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Tipo Producción</th>
				    <th width="15%">Destino Producción</th>
				    <th width="15%">Tipo Explotación</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoProduccion = pg_fetch_assoc($produccion)){
					echo $cbt->imprimirLineaProduccionCertificacionBT($infoProduccion['id_certificacion_bt_produccion'],
															$infoProduccion['tipo_produccion'], 
															$infoProduccion['destino_leche'], 
															$infoProduccion['tipo_explotacion'], $ruta,
															$infoProduccion['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleProduccionExplotacionDestinoConsultaFS">
		<legend>Producción, Explotación y Destinos Registrados</legend>
		<table id="detalleProduccionExplotacionDestinoConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Tipo Producción</th>
				    <th width="15%">Destino Producción</th>
				    <th width="15%">Tipo Explotación</th>
				</tr>
			</thead>
			<?php 
				while ($infoProduccion = pg_fetch_assoc($produccionConsulta)){
					echo $cbt->imprimirLineaProduccionCertificacionBTConsulta($infoProduccion['id_certificacion_bt_produccion'],
															$infoProduccion['tipo_produccion'], 
															$infoProduccion['destino_leche'], 
															$infoProduccion['tipo_explotacion'], $ruta,
															$infoProduccion['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<form id="nuevoInventarioAnimal" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarInventarioAnimal" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		
		<fieldset>
			<legend>Inventario de Animales en el Predio</legend>
			
			<div data-linea="28">
				<label>Animales en el Predio:</label>
					<select id="animalesPredio" name="animalesPredio" required="required" >
						<option value="">Animales Predio....</option>
						<option value="1">Terneros</option>
						<option value="2">Terneras</option>
						<option value="3">Toretes</option>
						<option value="4">Vaconas</option>
						<option value="5">Toros</option>
						<option value="6">Vacas</option>
						<option value="7">Ovinos</option>
						<option value="8">Caprinos</option>
						<option value="9">Porcinos</option>
						<option value="10">Bubalinos</option>
						<option value="11">Caninos</option>
						<option value="12">Felinos</option>
						<option value="13">Equinos</option>
					</select> 					
					
					<input type="hidden" id="nombreAnimalesPredio" name="nombreAnimalesPredio" />
			</div>
			
			<div data-linea="28">
				<label>Número:</label>
				<input type="text" id="numeroAnimalesPredio" name="numeroAnimalesPredio" data-er="^[0-9]+$" required="required"/>
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
		</fieldset>
	</form>
	
	
	
	<fieldset id="detalleInventarioAnimalFS">
		<legend>Inventario de Animales en el Predio Registrados</legend>
		<table id="detalleInventarioAnimal">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Animales Predio</th>
				    <th width="15%">Número existencias</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoInventario = pg_fetch_assoc($inventario)){
					echo $cbt->imprimirLineaInventarioAnimalCertificacionBT($infoInventario['id_certificacion_bt_inventario_animal'],
															$infoInventario['animales_predio'], $infoInventario['numero_animales_predio'], 
															$ruta, $infoInventario['num_inspeccion']);												
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleInventarioAnimalConsultaFS">
		<legend>Inventario de Animales en el Predio Registrados</legend>
		<table id="detalleInventarioAnimalConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Animales Predio</th>
				    <th width="15%">Número existencias</th>
				</tr>
			</thead>
			<?php 
				while ($infoInventario = pg_fetch_assoc($inventarioConsulta)){
					echo $cbt->imprimirLineaInventarioPredioCertificacionBTConsulta($infoInventario['id_certificacion_bt_inventario_animal'],
															$infoInventario['animales_predio'], $infoInventario['numero_animales_predio'],
															$ruta, $infoInventario['num_inspeccion']);													
				}
			?>
		</table>
	</fieldset>
	
	<form id="nuevoPediluvio" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPediluvio" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		
		<fieldset>
			<legend>Pediluvios en el Predio</legend>
			
			<div data-linea="28">
				<label>Pediluvios existentes:</label>
					<select id="pediluvio" name="pediluvio" required="required" >
						<option value="">Pediluvios Predio....</option>
						<option value="1">Vehículos</option>
						<option value="2">Animales</option>
						<option value="3">Humanos</option>
					</select> 					
					
					<input type="hidden" id="nombrePediluvio" name="nombrePediluvio" />
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
		</fieldset>
	</form>
	
	<fieldset id="detallePediluvioFS">
		<legend>Pediluvios Registrados</legend>
		<table id="detallePediluvio">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Pediluvios existentes</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoPediluvio = pg_fetch_assoc($pediluvio)){
					echo $cbt->imprimirLineaPediluvioCertificacionBT($infoPediluvio['id_certificacion_bt_pediluvio'], 
																$infoPediluvio['pediluvio'], $ruta, $infoPediluvio['num_inspeccion']);												
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePediluvioConsultaFS">
		<legend>Pediluvios Registrados</legend>
		<table id="detallePediluvioConsulta">
			<thead>
				<tr>
				   <th width="15%">Inspección</th>
				   <th width="15%">Pediluvios existentes</th>
				</tr>
			</thead>
			<?php 
				while ($infoPediluvio = pg_fetch_assoc($pediluvioConsulta)){
					echo $cbt->imprimirLineaPediluvioCertificacionBTConsulta($infoPediluvio['id_certificacion_bt_pediluvio'], 
																$infoPediluvio['pediluvio'], $ruta, $infoPediluvio['num_inspeccion']);												
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Manejo General de Animales y Potreros</h2>

	<form id="nuevoManejoAnimalesPotreros" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarManejoAnimalesPotreros" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		
		<fieldset>
			<legend>Manejo de Animales y Potreros</legend>
			
			<div data-linea="29">
				<label>Usa Pastos Comunales:</label>
				<select id="pastosComunales" name="pastosComunales" required="required" >
						<option value="">Pastos Comunales....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
				
			<div data-linea="29">
				<label>Arrienda sus Potreros:</label>
				<select id="arriendaPotreros" name="arriendaPotreros" required="required" >
						<option value="">Arrienda Potreros....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
			
			<div data-linea="30">
				<label>Arrienda Potreros de otro Predio:</label>
				<select id="arriendaPotrerosOtroPredio" name="arriendaPotrerosOtroPredio" required="required" >
						<option value="">Arrienda Potreros a Otro Predio....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
			
			<div data-linea="31">
				<label>Utiliza estiercol como abono:</label>
				<select id="utilizaEstiercol" name="utilizaEstiercol" required="required" >
						<option value="">Utiliza estiercol....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
			
			<div data-linea="32">
				<label>Lleva animales a ferias exposición:</label>
				<select id="feriaExposicion" name="feriaExposicion" required="required" >
						<option value="">Feria Exposición....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
			
			<div data-linea="33">
				<label>Desinfecta animales al volver:</label>
				<select id="desinfectaAnimales" name="desinfectaAnimales" required="required" >
						<option value="">Desinfecta Animales....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
			
			<div data-linea="34">
				<label>Los trabajadores tienen animales en el predio:</label>
				<select id="trabajadoresAnimalesPredio" name="trabajadoresAnimalesPredio" required="required" >
						<option value="">Animales Predio....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
			
			<div data-linea="35">
				<label id='idProgramaPrediosLibres'>Están dentro del programa de predios libres:</label>
				<select id="programaPrediosLibres" name="programaPrediosLibres" required="required" >
						<option value="">Programa Predios Libres....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
						
		</fieldset>
	</form>
	
	<fieldset id="detalleManejoAnimalesPotrerosFS">
		<legend>Manejo de Animales y Potreros Registrados</legend>
		<table id="detalleManejoAnimalesPotreros">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Pastos Comunales</th>
				    <th width="15%">Arrienda sus Potreros</th>
				    <th width="15%">Arrienda otros Potreros</th>
				    <th width="15%">Estiércol como abono</th>
				    <th width="15%">Lleva animales a ferias</th>
				    <th width="15%">Desinfecta animales</th>
				    <th width="15%">Trabajadores tienen animales</th>
				    <th width="15%">Están en Programa PLBT</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoManejoAnimal = pg_fetch_assoc($manejoAnimal)){
					echo $cbt->imprimirLineaManejoAnimalesPotrerosCertificacionBT($infoManejoAnimal['id_certificacion_bt_manejo_animales_potreros'], 
																		$infoManejoAnimal['pastos_comunales'], 
																		$infoManejoAnimal['arrienda_potreros'], 
																		$infoManejoAnimal['arrienda_otros_potreros'],
																		$infoManejoAnimal['estiercol_abono'], 
																		$infoManejoAnimal['animales_ferias'], 
																		$infoManejoAnimal['desinfecta_animales'], 
																		$infoManejoAnimal['trabajadores_animales_predio'], 
																		$infoManejoAnimal['dentro_programa_predios_libres'], $ruta,
																		$infoManejoAnimal['num_inspeccion']);												
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleManejoAnimalesPotrerosConsultaFS">
		<legend>Manejo de Animales y Potreros Registrados</legend>
		<table id="detalleManejoAnimalesPotrerosConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Pastos Comunales</th>
				    <th width="15%">Arrienda sus Potreros</th>
				    <th width="15%">Arrienda otros Potreros</th>
				    <th width="15%">Estiércol como abono</th>
				    <th width="15%">Lleva animales a ferias</th>
				    <th width="15%">Desinfecta animales</th>
				    <th width="15%">Trabajadores tienen animales</th>
				    <th width="15%">Están en Programa PLBT</th>
				</tr>
			</thead>
			<?php 
			while ($infoManejoAnimal = pg_fetch_assoc($manejoAnimalConsulta)){
					echo $cbt->imprimirLineaManejoAnimalesPotrerosCertificacionBTConsulta($infoManejoAnimal['id_certificacion_bt_manejo_animales_potreros'], 
																		$infoManejoAnimal['pastos_comunales'], 
																		$infoManejoAnimal['arrienda_potreros'], 
																		$infoManejoAnimal['arrienda_otros_potreros'],
																		$infoManejoAnimal['estiercol_abono'], 
																		$infoManejoAnimal['animales_ferias'], 
																		$infoManejoAnimal['desinfecta_animales'], 
																		$infoManejoAnimal['trabajadores_animales_predio'], 
																		$infoManejoAnimal['dentro_programa_predios_libres'], $ruta,
																		$infoManejoAnimal['num_inspeccion']);												
				}
			?>
		</table>
	</fieldset>
	
	<form id="nuevaAdquisicionAnimales" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarAdquisicionAnimales" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		
		<fieldset>
			<legend>Adquisición de Animales para el Predio</legend>
			
			<div data-linea="36">
				<label>Procedencia de Animales de Reemplazo:</label>
					<select id="procedenciaAnimales" name="procedenciaAnimales" required="required" >
						<option value="">Procedencia Animales....</option>
						<option value="1">Mismo Predio</option>
						<option value="2">Predio Libre</option>
						<option value="3">Vecino</option>
						<option value="4">Feria</option>
						<option value="5">Comerciante</option>
					</select> 					
					
					<input type="hidden" id="nombreProcedenciaAnimales" name="nombreProcedenciaAnimales" />
			</div>
			
			<div data-linea="37">
				<label>Categoría Animales que Adquiere:</label>
					<select id="categoriaAnimalesAdquiere" name="categoriaAnimalesAdquiere" required="required" >
						<option value="">Categoría Animales....</option>
						<option value="1">Terneros</option>
						<option value="2">Terneras</option>
						<option value="3">Toretes</option>
						<option value="4">Vaconas</option>
						<option value="5">Toros</option>
						<option value="6">Vacas</option>
					</select> 					
					
					<input type="hidden" id="nombreCategoriaAnimalesAdquiere" name="nombreCategoriaAnimalesAdquiere" />
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
		</fieldset>
	</form>
	
	
	
	<fieldset id="detalleAdquisicionAnimalesFS">
		<legend>Adquisición de Animales para el Predio Registrados</legend>
		<table id="detalleAdquisicionAnimales">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Procedencia Animales</th>
				    <th width="15%">Categoría</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoAdquisicionAnimales = pg_fetch_assoc($adquisicionAnimales)){
					echo $cbt->imprimirLineaAdquisicionAnimalesCertificacionBT($infoAdquisicionAnimales['id_certificacion_bt_adquisicion_animales'], 
														$infoAdquisicionAnimales['procedencia_animales'], 
														$infoAdquisicionAnimales['categoria_animales_adquiriente'], 
														$ruta, $infoAdquisicionAnimales['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleAdquisicionAnimalesConsultaFS">
		<legend>Adquisición de Animales para el Predio Registrados</legend>
		<table id="detalleAdquisicionAnimalesConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Procedencia Animales</th>
				    <th width="15%">Categoría</th>
				</tr>
			</thead>
			<?php 
				while ($infoAdquisicionAnimales = pg_fetch_assoc($adquisicionAnimalesConsulta)){
					echo $cbt->imprimirLineaAdquisicionAnimalesCertificacionBTConsulta($infoAdquisicionAnimales['id_certificacion_bt_adquisicion_animales'], 
														$infoAdquisicionAnimales['procedencia_animales'], 
														$infoAdquisicionAnimales['categoria_animales_adquiriente'], 
														$ruta, $infoAdquisicionAnimales['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<form id="nuevaProcedenciaAgua" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarProcedenciaAgua" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		
		<fieldset>
			<legend>Procedencia de Agua para el Predio</legend>
			
			<div data-linea="36">
				<label>Procedencia de Agua:</label>
					<select id="procedenciaAgua" name="procedenciaAgua" required="required" >
						<option value="">Procedencia Agua....</option>
						<option value="1">Río</option>
						<option value="2">Acequia</option>
						<option value="3">Pozo</option>
						<option value="4">Cisterna</option>
						<option value="5">Lluvia</option>
					</select> 					
					
					<input type="hidden" id="nombreProcedenciaAgua" name="nombreProcedenciaAgua" />
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
		</fieldset>
	</form>
	
	
	
	<fieldset id="detalleProcedenciaAguaFS">
		<legend>Procedencia Agua Registrada</legend>
		<table id="detalleProcedenciaAgua">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Procedencia Agua</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoProcedenciaAgua = pg_fetch_assoc($procedenciaAgua)){
					echo $cbt->imprimirLineaProcedenciaAguaCertificacionBT($infoProcedenciaAgua['id_certificacion_bt_procedencia_agua'], 
																	$infoProcedenciaAgua['procedencia_agua'], $ruta,
																	$infoProcedenciaAgua['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleProcedenciaAguaConsultaFS">
		<legend>Procedencia Agua Registrada</legend>
		<table id="detalleProcedenciaAguaConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Procedencia Agua</th>
				</tr>
			</thead>
			<?php 
				while ($infoProcedenciaAgua = pg_fetch_assoc($procedenciaAguaConsulta)){
					echo $cbt->imprimirLineaProcedenciaAguaCertificacionBTConsulta($infoProcedenciaAgua['id_certificacion_bt_procedencia_agua'], 
																	$infoProcedenciaAgua['procedencia_agua'], $ruta,
																	$infoProcedenciaAgua['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Aspectos Sanitarios</h2>
	<h3>I. Veterinario</h3>

	<form id="nuevoVeterinario" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarVeterinario" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		
		<fieldset>
			<legend>Información del Veterinario</legend>
			
			<div data-linea="37">
			<label>Nombre:</label>
			<input type="text" id="nombreVeterinario" name="nombreVeterinario" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required" />
		</div>
		
		<div data-linea="37">
			<label>Teléfono:</label>
			<input type="text" id="telefonoVeterinario" name="telefonoVeterinario" maxlength="15" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15"/>
		</div>
		
		
		<div data-linea="38">
			<label>Celular:</label>
			<input type="text" id="celularVeterinario" name="celularVeterinario" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" data-inputmask="'mask': '(99) 9999-9999'" size="16"/>
		</div>
		
		<div data-linea="38">
			<label>Correo Electrónico:</label>
			<input type="text" id="correoElectronicoVeterinario" name="correoElectronicoVeterinario" maxlength="32" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" />
		</div>
			
		<div data-linea="39">
			<label>Frecuencia Visita:</label>
			<select id="frecuenciaVisitaVeterinario" name="frecuenciaVisitaVeterinario" required="required" >
				<option value="">Frecuencia Visita....</option>
				<option value="1">Semanal</option>
				<option value="2">Quincenal</option>
				<option value="3">Mensual</option>
				<option value="4">A Pedido</option>
			</select>
			
			<input type="hidden" id="nombreFrecuenciaVisitaVeterinario" name="nombreFrecuenciaVisitaVeterinario"/> 
		</div>
		
		<div>
			<button type="submit" class="mas">Agregar</button>		
		</div>
						
		</fieldset>
	</form>
	
	<fieldset id="detalleVeterinarioFS">
		<legend>Veterinario Registrado</legend>
		<table id="detalleVeterinario">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Nombre</th>
				    <th width="15%">Teléfono</th>
				    <th width="15%">Celular</th>
				    <th width="15%">Correo Electrónico</th>
				    <th width="15%">Frecuencia Visita</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoVeterinario = pg_fetch_assoc($veterinario)){
					echo $cbt->imprimirLineaVeterinarioCertificacionBT($infoVeterinario['id_certificacion_bt_veterinario'], 
											$infoVeterinario['nombre_veterinario'], $infoVeterinario['telefono_veterinario'], 
											$infoVeterinario['celular_veterinario'], $infoVeterinario['correo_electronico_veterinario'], 
											$infoVeterinario['frecuencia_visita_veterinario'], $ruta, $infoVeterinario['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleVeterinarioConsultaFS">
		<legend>Veterinario Registrado</legend>
		<table id="detalleVeterinarioConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Nombre</th>
				    <th width="15%">Teléfono</th>
				    <th width="15%">Celular</th>
				    <th width="15%">Correo Electrónico</th>
				    <th width="15%">Frecuencia Visita</th>
				</tr>
			</thead>
			<?php 
				while ($infoVeterinario = pg_fetch_assoc($veterinarioConsulta)){
					echo $cbt->imprimirLineaVeterinarioCertificacionBTConsulta($infoVeterinario['id_certificacion_bt_veterinario'], 
											$infoVeterinario['nombre_veterinario'], $infoVeterinario['telefono_veterinario'], 
											$infoVeterinario['celular_veterinario'], $infoVeterinario['correo_electronico_veterinario'], 
											$infoVeterinario['frecuencia_visita_veterinario'], $ruta, $infoVeterinario['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<h3>II. Vacunación</h3>

	<form id="nuevaInformacionVacunacion" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarInformacionVacunacion" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		
		<fieldset>
			<legend>Información de Vacunación</legend>
			
			<div data-linea="70">
				<label>Dispone de Calendario de Vacunación:</label>
					<select id="calendarioVacunacion" name="calendarioVacunacion" required="required" >
						<option value="">Calendario Vacunación....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select>				
			</div>
			
			<div data-linea="40">
				<label id="lMotivoVacunacion">Período de Vacunación:</label>
					<select id="motivoVacunacion" name="motivoVacunacion" required="required" >
						<option value="">Período de Vacunación....</option>
						<option value="1">Primera Vacunación</option>
						<option value="2">Última Vacunación</option>
					</select>
					
					<input type='hidden' id='nombreMotivoVacunacion' name='nombreMotivoVacunacion' />					
			</div>
			
			<div data-linea="40">
				<label id="lVacunasAplicadas">Vacunas Aplicadas:</label>
					<select id="vacunasAplicadas" name="vacunasAplicadas" required="required" >
						<option value="">Vacunas Aplicadas....</option>
						<option value="1">Aftosa</option>
						<option value="2">Cepa 19</option>
						<option value="3">RB51</option>
					</select>
					
					<input type='hidden' id='nombreVacunasAplicadas' name='nombreVacunasAplicadas' />					
			</div>
			
			<div data-linea="41">
				<label id="lProcedenciaVacunas">Procedencia Vacunas:</label>
					<select id="procedenciaVacunas" name="procedenciaVacunas" required="required" >
						<option value="">Procedencia Vacunas....</option>
						<option value="1">Almacén Localidad</option>
						<option value="2">Almacén Ciudad</option>
						<option value="3">Veterinario</option>
						<option value="4">Campaña de vacunación oficial</option>
					</select>
					
					<input type='hidden' id='nombreProcedenciaVacunas' name='nombreProcedenciaVacunas' />					
			</div>
			
			<div data-linea="41">
				<label id="lFechaVacunacion">Fecha Vacunación:</label>
				<input type="text" id="fechaVacunacion" name="fechaVacunacion" required="required" />
			</div>
				
					
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detalleInformacionVacunacionFS">
		<legend>Información de Vacunación</legend>
		<table id="detalleInformacionVacunacion">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Calendario Vacunación</th>
				    <th width="15%">Período Vacunación</th>
					<th width="15%">Vacuna Aplicada</th>
					<th width="10%">Procedencia Vacuna</th>
					<th width="10%">Fecha Vacunación</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoVacunacion = pg_fetch_assoc($vacunacion)){
					echo $cbt->imprimirLineaInformacionVacunacionCertificacionBT($infoVacunacion['id_certificacion_bt_informacion_vacunacion'], 
																	$infoVacunacion['motivo_vacunacion'], $infoVacunacion['vacunas_aplicadas'], 
																	$infoVacunacion['procedencia_vacunas'], $infoVacunacion['fecha_vacunacion'], 
																	$ruta, $infoVacunacion['num_inspeccion'], $infoVacunacion['calendario_vacunacion']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleInformacionVacunacionConsultaFS">
		<legend>Información de Vacunación</legend>
		<table id="detalleInformacionVacunacionConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Calendario Vacunación</th>
				    <th width="15%">Período Vacunación</th>
					<th width="15%">Vacuna Aplicada</th>
					<th width="10%">Procedencia Vacuna</th>
					<th width="10%">Fecha Vacunación</th>
				</tr>
			</thead>
			<?php 
				while ($infoVacunacion = pg_fetch_assoc($vacunacionConsulta)){
					echo $cbt->imprimirLineaInformacionVacunacionCertificacionBTConsulta($infoVacunacion['id_certificacion_bt_informacion_vacunacion'], 
																	$infoVacunacion['motivo_vacunacion'], $infoVacunacion['vacunas_aplicadas'], 
																	$infoVacunacion['procedencia_vacunas'], $infoVacunacion['fecha_vacunacion'], 
																	$ruta, $infoVacunacion['num_inspeccion'], $infoVacunacion['calendario_vacunacion']);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Aspectos Sanitarios</h2>
	<h3>III. Reproducción</h3>

	<form id="nuevaReproduccion" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarReproduccion" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		
		<fieldset>
			<legend>Información de Reproducción Animal</legend>
			
			<div data-linea="42">
				<label>Sistema empleado:</label>
					<select id="sistemaEmpleado" name="sistemaEmpleado" required="required" >
						<option value="">Sistema Empleado....</option>
						<option value="1">Monta Natural</option>
						<option value="2">Inseminación</option>
						<option value="3">Mixta</option>
						<option value="4">Transferencia de Embriones</option>
					</select> 
					
					<input type="hidden" id="nombreSistemaEmpleado" name="nombreSistemaEmpleado" />					
			</div>
			
			<div data-linea="42">
				<label>Procedencia Pajuelas:</label>
					<select id="procedenciaPajuelas" name="procedenciaPajuelas" required="required" >
						<option value="">Procedencia Pajuelas....</option>
						<option value="1">Predio</option>
						<option value="2">Veterinario</option>
						<option value="3">Almacén</option>
						<option value="4">Comerciante</option>
						<option value="5">Organismos gubernamentales</option>
					</select> 
					
					<input type="hidden" id="nombreProcedenciaPajuelas" name="nombreProcedenciaPajuelas" />					
			</div>
			
			<div data-linea="43">
				<label>Lugar para Pariciones:</label>
					<select id="lugarPariciones" name="lugarPariciones" required="required" >
						<option value="">Lugar Pariciones....</option>
						<option value="1">Potreros</option>
						<option value="2">Corral</option>
						<option value="3">Parideras</option>
					</select> 
					
					<input type="hidden" id="nombreLugarPariciones" name="nombreLugarPariciones" />					
			</div>
			
			<div data-linea="43">
				<label>Realiza Desinfección:</label>
					<select id="realizaDesinfeccion" name="realizaDesinfeccion" required="required" >
						<option value="">Desinfección....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
										
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
		</fieldset>
		
		
	</form>
	
	<fieldset id="detalleReproduccionFS">
		<legend>Información de Reproducción Animal Registrada</legend>
		<table id="detalleReproduccion">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Sistema Empleado</th>
				    <th width="15%">Procedencia Pajuelas</th>
				    <th width="15%">Lugar para Pariciones</th>
				    <th width="15%">Realiza Desinfección</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoReproduccion = pg_fetch_assoc($reproduccion)){
					echo $cbt->imprimirLineaReproduccionCertificacionBT($infoReproduccion['id_certificacion_bt_reproduccion'], 
													$infoReproduccion['sistema_empleado'], $infoReproduccion['procedencia_pajuelas'], 
													$infoReproduccion['lugar_pariciones'], $infoReproduccion['realiza_desinfeccion'], 
													$ruta, $infoReproduccion['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleReproduccionConsultaFS">
		<legend>Información de Reproducción Animal Registrada</legend>
		<table id="detalleReproduccionConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Sistema Empleado</th>
				    <th width="15%">Procedencia Pajuelas</th>
				    <th width="15%">Lugar para Pariciones</th>
				    <th width="15%">Realiza Desinfección</th>
				</tr>
			</thead>
			<?php 
				while ($infoReproduccion = pg_fetch_assoc($reproduccionConsulta)){
					echo $cbt->imprimirLineaReproduccionCertificacionBTConsulta($infoReproduccion['id_certificacion_bt_reproduccion'], 
													$infoReproduccion['sistema_empleado'], $infoReproduccion['procedencia_pajuelas'], 
													$infoReproduccion['lugar_pariciones'], $infoReproduccion['realiza_desinfeccion'], 
													$ruta, $infoReproduccion['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Aspectos Sanitarios</h2>
	<h3>IV. Patologías Tuberculosis</h3>

	<form id="nuevaPatologiaTuberculosis" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPatologiaTuberculosis" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		
		<fieldset>
			<legend>Información de Patologías Animales</legend>
			
			<div data-linea="53">
				<label>Pérdida de Peso Inexplicable:</label>
				<select id="perdidaPeso" name="perdidaPeso" required="required" >
						<option value="">Pérdida de Peso....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div data-linea="53">
				<label>Pérdida de Apetito Inexplicable:</label>
				<select id="perdidaApetito" name="perdidaApetito" required="required" >
						<option value="">Pérdida de Apetito....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div data-linea="54">
				<label>Problemas Respiratorios:</label>
				<select id="problemasRespiratorios" name="problemasRespiratorios" required="required" >
						<option value="">Problemas Respiratorios....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div data-linea="54">
				<label>Tos Intermitente:</label>
				<select id="tosIntermitente" name="tosIntermitente" required="required" >
						<option value="">Tos Intermitente....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div data-linea="55">
				<label>Abultamientos en cuello, pecho, ubres u otras partes del cuerpo:</label>
				<select id="abultamiento" name="abultamiento" required="required" >
						<option value="">Abultamiento....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div data-linea="56">
				<label>Fiebre Fluctuante:</label>
				<select id="fiebreFluctuante" name="fiebreFluctuante" required="required" >
						<option value="">Fiebre Fluctuante....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
		</fieldset>
	</form>		

	<fieldset id="detallePatologiaTuberculosisFS">
		<legend>Patologías en Tuberculosis Registradas</legend>
		<table id="detallePatologiaTuberculosis">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Pérdida Peso</th>
					<th width="15%">Pérdida Apetito</th>
					<th width="15%">Problemas Respiratorios</th>
					<th width="15%">Tos Intermitente</th>
					<th width="15%">Abultamientos en Cuerpo</th>
					<th width="15%">Fiebre Fluctuante</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoPatologiaTuberculosis = pg_fetch_assoc($patologiaTuberculosis)){
					echo $cbt->imprimirLineaPatologiaTuberculosisCertificacionBT($infoPatologiaTuberculosis['id_certificacion_bt_patologia_tuberculosis'], 
																		$infoPatologiaTuberculosis['perdida_peso'], $infoPatologiaTuberculosis['perdida_apetito'], 
																		$infoPatologiaTuberculosis['problemas_respiratorios'], $infoPatologiaTuberculosis['tos_intermitente'], 
																		$infoPatologiaTuberculosis['abultamiento'], $infoPatologiaTuberculosis['fiebre_fluctuante'], $ruta,
																		$infoPatologiaTuberculosis['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePatologiaTuberculosisConsultaFS">
		<legend>Pruebas de Brucelosis en Sangre Registradas</legend>
		<table id="detallePatologiaTuberculosisConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Pérdida Peso</th>
					<th width="15%">Pérdida Apetito</th>
					<th width="15%">Problemas Respiratorios</th>
					<th width="15%">Tos Intermitente</th>
					<th width="15%">Abultamientos en Cuerpo</th>
					<th width="15%">Fiebre Fluctuante</th>
				</tr>
			</thead>
			<?php 
				while ($infoPatologiaTuberculosis = pg_fetch_assoc($patologiaTuberculosisConsulta)){
					echo $cbt->imprimirLineaPatologiaTuberculosisCertificacionBTConsulta($infoPatologiaTuberculosis['id_certificacion_bt_patologia_tuberculosis'], 
																		$infoPatologiaTuberculosis['perdida_peso'], $infoPatologiaTuberculosis['perdida_apetito'], 
																		$infoPatologiaTuberculosis['problemas_respiratorios'], $infoPatologiaTuberculosis['tos_intermitente'], 
																		$infoPatologiaTuberculosis['abultamiento'], $infoPatologiaTuberculosis['fiebre_fluctuante'], $ruta,
																		$infoPatologiaTuberculosis['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
</div>


<div class="pestania">

	<h2>Aspectos Sanitarios</h2>
	<h3>V. Pruebas Diagnósticas Tuberculosis</h3>

	<!-- form id="nuevaPruebaTuberculosisLeche" data-rutaAplicacion="< ?php echo $ruta;?>" data-opcion="guardarPruebaTuberculosisLeche" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="< ?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="< ?php echo $certificacionBT['num_inspeccion'];?>" />
		
		<fieldset>
			<legend>Pruebas de Tuberculosis en Leche</legend>
			
			<div data-linea="49">
				<label>Pruebas de tuberculosis en leche:</label>
					<select id="pruebasTuberculosisLeche" name="pruebasTuberculosisLeche" required="required" >
						<option value="">Tuberculosis Leche....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>						
					</select> 					
			</div>
			
			<div data-linea="49">
				<label id="lResultadoTuberculosisLeche">Resultado:</label>
					<select id="resultadoTuberculosisLeche" name="resultadoTuberculosisLeche" >
						<option value="">Resultado....</option>
						<option value="Positivo">Positivo</option>
						<option value="Negativo">Negativo</option>
						
					</select> 					
			</div>			
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
		</fieldset>
	</form>
	
	<fieldset id="detallePruebaTuberculosisLecheFS">
		<legend>Prueba de Tuberculosis en Leche Registrada</legend>
		<table id="detallePruebaTuberculosisLeche">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Prueba de Tuberculosis en Leche</th>
				    <th width="15%">Resultado</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			< ?php 
				while ($infoPruebasLecheTub = pg_fetch_assoc($pruebasLecheTuberculosis)){
					echo $cbt->imprimirLineaPruebaTuberculosisLecheCertificacionBT($infoPruebasLecheTub['id_certificacion_bt_prueba_tuberculosis_leche'], 
																		$infoPruebasLecheTub['pruebas_tuberculosis_leche'], 
																		$infoPruebasLecheTub['resultado_tuberculosis_leche'], $ruta,
																		$infoPruebasLecheTub['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePruebaTuberculosisLecheConsultaFS">
		<legend>Prueba de Tuberculosis en Leche Registrada</legend>
		<table id="detallePruebaTuberculosisLecheConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Prueba de Tuberculosis en Leche</th>
				    <th width="15%">Resultado</th>
				</tr>
			</thead>
			< ?php 
				while ($infoPruebasLecheTub = pg_fetch_assoc($pruebasLecheTuberculosisConsulta)){
					echo $cbt->imprimirLineaPruebaTuberculosisLecheCertificacionBTConsulta($infoPruebasLecheTub['id_certificacion_bt_prueba_tuberculosis_leche'], 
																		$infoPruebasLecheTub['pruebas_tuberculosis_leche'], 
																		$infoPruebasLecheTub['resultado_tuberculosis_leche'], $ruta,
																		$infoPruebasLecheTub['num_inspeccion']);
				}
			?>
		</table>
	</fieldset-->
			
	<form id="nuevaPruebaTuberculina" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPruebaTuberculina" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		
		<fieldset>
			<legend>Pruebas Diagnósticas de Tuberculosis Bovina</legend>
			
			<div data-linea="50">
				<label id="lPruebasTuberculina">Pruebas diagnósticas:</label>
					<select id="pruebasTuberculina" name="pruebasTuberculina" required="required" >
						<option value="">Pruebas diagnósticas....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
						
					</select> 					
			</div>
			
			<div data-linea="50">
				<label id="lResultadoTuberculina">Resultado:</label>
					<select id="resultadoTuberculina" name="resultadoTuberculina" >
						<option value="">Resultado....</option>
						<option value="Positivo">Positivo</option>
						<option value="Negativo">Negativo</option>						
					</select> 					
					
			</div>
			
			<div data-linea="51">
				<label id="lPruebasLaboratorio">Pruebas de Laboratorio:</label>
					<select id="pruebasLaboratorio" name="pruebasLaboratorio" >
						<option value="">Pruebas....</option>
						<option value="1">Prueba en Leche</option>
						<option value="2">Tuberculina anocaudal</option>
						<option value="3">Cervical comparativa</option>
						<option value="4">Gama interferón</option>
						
					</select>
					
					<input type="hidden" id="nombrePruebasLaboratorio" name="nombrePruebasLaboratorio"  /> 					
			</div>
			
			<div data-linea="52">
				<label id="lLaboratorioTuberculina">Laboratorio:</label>
					<select id="laboratorioTuberculina" name="laboratorioTuberculina" >
						<option value="">Laboratorio....</option>
						<option value="1">ANIMALAB</option>
						<option value="2">LIVEXLAB</option>
						<option value="3">VETELAB</option>
						<option value="4">AGROCALIDAD</option>
						<option value="0">Otro</option>
					</select>
					
			</div>
			
			<div data-linea="52">
				<input type="text" id="nombreLaboratorioTuberculina" name="nombreLaboratorioTuberculina"  />
			</div>
			
			<div data-linea="53">
				<label id="lDestinoAnimalesPositivosTuberculina">Destino animales positivos:</label>
					<select id="destinoAnimalesPositivosTuberculina" name="destinoAnimalesPositivosTuberculina" >
						<option value="">Animales Positivos....</option>
						<option value="1">Permanecen en el Predio</option>
						<option value="2">Camal</option>
						<option value="3">Venta a Comerciante</option>
						<option value="4">Venta en Feria</option>
					</select>
					
					<input type="hidden" id="nombreDestinoAnimalesPositivosTuberculina" name="nombreDestinoAnimalesPositivosTuberculina"  /> 					
			</div>
				
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detallePruebaTuberculinaFS">
		<legend>Pruebas Diagnósticas Tuberculina Registradas</legend>
		<table id="detallePruebaTuberculina">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Prueba Tuberculina</th>
					<th width="15%">Resultado</th>
					<th width="15%">Pruebas</th>
					<th width="15%">Laboratorio</th>
					<th width="15%">Destino Animales Positivos</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoPruebasTuberculina = pg_fetch_assoc($pruebasTuberculina)){
					echo $cbt->imprimirLineaPruebaTuberculinaCertificacionBT($infoPruebasTuberculina['id_certificacion_bt_prueba_tuberculina'], $infoPruebasTuberculina['pruebas_tuberculina'], 
																		$infoPruebasTuberculina['resultado_tuberculina'], $infoPruebasTuberculina['laboratorio'], 
																		$infoPruebasTuberculina['destino_animales_positivos'], $ruta, $infoPruebasTuberculina['num_inspeccion'],
																		$infoPruebasTuberculina['pruebas_laboratorio']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePruebaTuberculinaConsultaFS">
		<legend>Pruebas Diagnósticas Tuberculina Registradas</legend>
		<table id="detallePruebaTuberculinaConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
				    <th width="15%">Prueba Tuberculina</th>
					<th width="15%">Resultado</th>
					<th width="15%">Pruebas</th>
					<th width="15%">Laboratorio</th>
					<th width="15%">Destino Animales Positivos</th>
				</tr>
			</thead>
			<?php 
				while ($infoPruebasTuberculina = pg_fetch_assoc($pruebasTuberculinaConsulta)){
					echo $cbt->imprimirLineaPruebaTuberculinaCertificacionBTConsulta($infoPruebasTuberculina['id_certificacion_bt_prueba_tuberculina'], $infoPruebasTuberculina['pruebas_tuberculina'], 
																		$infoPruebasTuberculina['resultado_tuberculina'], $infoPruebasTuberculina['laboratorio'], 
																		$infoPruebasTuberculina['destino_animales_positivos'], $ruta, $infoPruebasTuberculina['num_inspeccion'],
																		$infoPruebasTuberculina['pruebas_laboratorio']);
				}
			?>
		</table>
	</fieldset>
</div>


<div class="pestania">

	<h2>Finalizar Registro</h2>

	<form id="cerrarCertificacionBT" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarCierreCertificacionBTTecnico" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		<input type='hidden' id='certificacion' name='certificacion' value="<?php echo $certificacionBT['certificacion_bt'];?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		<input type='hidden' id='numSolicitud' name='numSolicitud' value="<?php echo $certificacionBT['num_solicitud'];?>" />
		
		<fieldset>
			<legend>Resultado del Proceso</legend>
			
			<div data-linea="53">
				<label>Resultado:</label>
					<select id="resultado" name="resultado" required="required" >
						<option value="">Seleccione....</option>
						<option value="tomaMuestras">Toma de Muestras</option>
						<option value="rechazado">Rechazado</option>
					</select>					 					
			</div>
			
			<div data-linea="54">
				<label id="lLaboratorioMuestras">Laboratorio para Análisis:</label>
					<select id="laboratorioMuestras" name="laboratorioMuestras">
						<?php 
							while($fila = pg_fetch_assoc($laboratorios)){
								echo "<option value=".$fila['id_laboratorio'].">".$fila['nombre']."</option>";
							}
						?>
					</select>
				
				<input type="hidden" id="nombreLaboratorioMuestras" name="nombreLaboratorioMuestras"  /> 
			</div>
			
			<div data-linea="54	">
				<label id="lFechaInspeccion" >Fecha tentativa muestreo:</label>
				<input type="text" id="fechaInspeccion" name="fechaInspeccion" />
			</div>
			
			<div data-linea="55">
				<label>Observaciones:</label>
				<input type="text" id="observaciones" name="observaciones" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
			</div>

		</fieldset>
	
		<div data-linea="55">
			<button id="guardarCierre" type="submit" class="guardar">Guardar</button>
		</div>
	</form>	
	
	<fieldset id="cerrarCertificacionBTConsulta">
		<legend>Resultado del Proceso</legend>
		
		<div data-linea="56">
			<label>Resultado: </label>
			<?php echo $certificacionBT['estado'];?>
		</div>

		<div data-linea="57">
			<label>Observaciones: </label>
			<?php echo $certificacionBT['observaciones'];?>
		</div>
	</fieldset>
</div>

<script type="text/javascript">

var usuario = <?php echo json_encode($usuario); ?>;
var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;
var estado= <?php echo json_encode($certificacionBT['estado']); ?>;
var perfil= <?php echo json_encode($perfilAdmin); ?>;

	$("document").ready(function(){
		distribuirLineas();	
		construirValidador();
		construirAnimacion($(".pestania"));

		cargarValorDefecto("huso","<?php echo $certificacionBT['huso_zona'];?>");

		$("#actualizacion").hide();

		$("#fecha").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		$("#fechaVacunacion").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		$("#fechaInspeccion").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		$("#fechaNuevaInspeccion").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		cargarValorDefecto("provincia","<?php echo $certificacionBT['id_provincia'];?>");

		$('#nombreRaza').hide();
		$("#lFechaInspeccion").hide();
    	$("#fechaInspeccion").hide();
    	$("#lNumeroAbortos").hide();
    	$("#numeroAbortos").hide();
    	$("#lTejidosAbortados").hide();
    	$("#tejidosAbortados").hide();
    	$("#lResultadoBrucelosisLeche").hide();
		$("#resultadoBrucelosisLeche").hide();
		$("#lResultadoBrucelosisSangre").hide();
		$("#resultadoBrucelosisSangre").hide();
		$("#lPruebasLaboratorio").hide();
		$("#pruebasLaboratorio").hide();
		$("#lLaboratorio").hide();
		$("#laboratorio").hide();
		$("#nombreLaboratorio").hide();
		$("#lDestinoAnimalesPositivos").hide();
		$("#destinoAnimalesPositivos").hide();
		$("#lResultadoTuberculosisLeche").hide();
		$("#resultadoTuberculosisLeche").hide();
		$("#lResultadoTuberculina").hide();
		$("#resultadoTuberculina").hide();
		$("#lLaboratorioTuberculina").hide();
		$("#laboratorioTuberculina").hide();
		$("#nombreLaboratorioTuberculina").hide();
		$("#lPruebasLaboratorio").hide();
		$("#pruebasLaboratorio").hide();
		$("#lDestinoAnimalesPositivosTuberculina").hide();
		$("#destinoAnimalesPositivosTuberculina").hide();
		$('#nombreLaboratorioTuberculina').hide();
		$('#lLaboratorioMuestras').hide();
		$('#laboratorioMuestras').hide();

		$("#lMotivoVacunacion").hide();
		$("#motivoVacunacion").hide();
		$("#lVacunasAplicadas").hide();
		$("#vacunasAplicadas").hide();
		$("#lProcedenciaVacunas").hide();
		$("#procedenciaVacunas").hide();
		$("#lFechaVacunacion").hide();
		$('#fechaVacunacion').hide();
		
		
		$('#detalleInformacionPredioConsultaFS').hide();
		$('#detalleProduccionExplotacionDestinoConsultaFS').hide();
		$('#detalleInventarioAnimalConsultaFS').hide();
		$('#detallePediluvioConsultaFS').hide();
		$('#detalleManejoAnimalesPotrerosConsultaFS').hide();
		$('#detalleAdquisicionAnimalesConsultaFS').hide();
		$('#detalleProcedenciaAguaConsultaFS').hide();
		$('#detalleVeterinarioConsultaFS').hide();				
		$('#detalleInformacionVacunacionConsultaFS').hide();
		$('#detalleReproduccionConsultaFS').hide();		
		$('#detallePatologiaBrucelosisConsultaFS').hide();
		$('#detalleAbortoBrucelosisConsultaFS').hide();		
		$('#detallePruebaBrucelosisLecheConsultaFS').hide();
		$('#detallePruebaBrucelosisSangreConsultaFS').hide();
		$('#detallePatologiaTuberculosisConsultaFS').hide();
		$('#detallePruebaTuberculosisLecheConsultaFS').hide();				
		$('#detallePruebaTuberculinaConsultaFS').hide();
		
		$('#cerrarCertificacionBTConsulta').hide();
		
		
		acciones("#nuevaInformacionPredio","#detalleInformacionPredio");
		acciones("#nuevaProduccionExplotacionDestino","#detalleProduccionExplotacionDestino");		
		acciones("#nuevoInventarioAnimal","#detalleInventarioAnimal");
		acciones("#nuevoPediluvio","#detallePediluvio");
		acciones("#nuevoManejoAnimalesPotreros","#detalleManejoAnimalesPotreros");		
		acciones("#nuevaAdquisicionAnimales","#detalleAdquisicionAnimales");
		acciones("#nuevaProcedenciaAgua","#detalleProcedenciaAgua");		
		acciones("#nuevoVeterinario","#detalleVeterinario");		
		acciones("#nuevaInformacionVacunacion","#detalleInformacionVacunacion");		
		acciones("#nuevaReproduccion","#detalleReproduccion");		
		acciones("#nuevaPatologiaBrucelosis","#detallePatologiaBrucelosis");		
		acciones("#nuevoAbortoBrucelosis","#detalleAbortoBrucelosis");
		acciones("#nuevaPruebaBrucelosisLeche","#detallePruebaBrucelosisLeche");
		acciones("#nuevaPruebaBrucelosisSangre","#detallePruebaBrucelosisSangre");		
		acciones("#nuevaPatologiaTuberculosis","#detallePatologiaTuberculosis");
		acciones("#nuevaPruebaTuberculosisLeche","#detallePruebaTuberculosisLeche");		
		acciones("#nuevaPruebaTuberculina","#detallePruebaTuberculina");
		
		
		if(usuario == '0'){
			$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#botonGuardar").attr("disabled", "disabled");
		}

		if((estado == 'activo') || (estado == 'inspeccion')){						
			$("#modificar").show();
			$("#actualizar").show();
			$("#nuevaInformacionPredio").show();
				$('#detalleInformacionPredioConsultaFS').hide();
				$('#detalleInformacionPredioFS').show();				
			$("#nuevaProduccionExplotacionDestino").show();
				$('#detalleProduccionExplotacionDestinoConsultaFS').hide();
				$('#detalleProduccionExplotacionDestinoFS').show();				
			$("#nuevoInventarioAnimal").show();
				$('#detalleInventarioAnimalConsultaFS').hide();
				$('#detalleInventarioAnimalFS').show();				
			$("#nuevoPediluvio").show();
				$('#detallePediluvioConsultaFS').hide();
				$('#detallePediluvioFS').show();				
			$("#nuevoManejoAnimalesPotreros").show();
				$('#detalleManejoAnimalesPotrerosConsultaFS').hide();
				$('#detalleManejoAnimalesPotrerosFS').show();				
			$("#nuevaAdquisicionAnimales").show();
				$('#detalleAdquisicionAnimalesConsultaFS').hide();
				$('#detalleAdquisicionAnimalesFS').show();
			$("#nuevaProcedenciaAgua").show();
				$('#detalleProcedenciaAguaConsultaFS').hide();
				$('#detalleProcedenciaAguaFS').show();
			$("#nuevoVeterinario").show();
				$('#detalleVeterinarioConsultaFS').hide();
				$('#detalleVeterinarioFS').show();
			$("#nuevaInformacionVacunacion").show();
				$('#detalleInformacionVacunacionConsultaFS').hide();
				$('#detalleInformacionVacunacionFS').show();
			$("#nuevaReproduccion").show();
				$('#detalleReproduccionConsultaFS').hide();
				$('#detalleReproduccionFS').show();
			$("#nuevaPatologiaBrucelosis").show();
				$('#detallePatologiaBrucelosisConsultaFS').hide();
				$('#detallePatologiaBrucelosisFS').show();
			$("#nuevaPruebaBrucelosisLeche").show();
				$('#detallePruebaBrucelosisLecheConsultaFS').hide();
				$('#detallePruebaBrucelosisLecheFS').show();
			$("#nuevaPruebaBrucelosisSangre").show();
				$('#detallePruebaBrucelosisSangreConsultaFS').hide();
				$('#detallePruebaBrucelosisSangreFS').show();
			$("#nuevaPatologiaTuberculosis").show();
				$('#detallePatologiaTuberculosisConsultaFS').hide();
				$('#detallePatologiaTuberculosisFS').show();
			$("#nuevaPruebaTuberculosisLeche").show();
				$('#detallePruebaTuberculosisLecheConsultaFS').hide();
				$('#detallePruebaTuberculosisLecheFS').show();
			$("#nuevaPruebaTuberculina").show();
				$('#detallePruebaTuberculinaConsultaFS').hide();
				$('#detallePruebaTuberculinaFS').show();	

				//revisar			
			$('#cerrarCertificacionBTConsulta').hide();
			$('#cerrarCertificacionBT').show();
			
		}else{
			$("#modificar").hide();
			$("#actualizar").hide();
			$("#nuevaInformacionPredio").hide();
				$('#detalleInformacionPredioConsultaFS').show();
				$('#detalleInformacionPredioFS').hide();
			$("#nuevaProduccionExplotacionDestino").hide();			
				$('#detalleProduccionExplotacionDestinoConsultaFS').show();
				$('#detalleProduccionExplotacionDestinoFS').hide();				
			$("#nuevoInventarioAnimal").hide();
				$('#detalleInventarioAnimalConsultaFS').show();
				$('#detalleInventarioAnimalFS').hide();
			$("#nuevoPediluvio").hide();
				$('#detallePediluvioConsultaFS').show();
				$('#detallePediluvioFS').hide();				
			$("#nuevoManejoAnimalesPotreros").hide();
				$('#detalleManejoAnimalesPotrerosConsultaFS').show();
				$('#detalleManejoAnimalesPotrerosFS').hide();				
			$("#nuevaAdquisicionAnimales").hide();
				$('#detalleAdquisicionAnimalesConsultaFS').show();
				$('#detalleAdquisicionAnimalesFS').hide();
			$("#nuevaProcedenciaAgua").hide();
				$('#detalleProcedenciaAguaConsultaFS').show();
				$('#detalleProcedenciaAguaFS').hide();
			$("#nuevoVeterinario").hide();
				$('#detalleVeterinarioConsultaFS').show();
				$('#detalleVeterinarioFS').hide();
			$("#nuevaInformacionVacunacion").hide();
				$('#detalleInformacionVacunacionConsultaFS').show();
				$('#detalleInformacionVacunacionFS').hide();
			$("#nuevaReproduccion").hide();
				$('#detalleReproduccionConsultaFS').show();
				$('#detalleReproduccionFS').hide();
			$("#nuevaPatologiaBrucelosis").hide();
				$('#detallePatologiaBrucelosisConsultaFS').show();
				$('#detallePatologiaBrucelosisFS').hide();
			$("#nuevoAbortoBrucelosis").hide();
				$('#detalleAbortoBrucelosisConsultaFS').show();
				$('#detalleAbortoBrucelosisFS').hide();
			$("#nuevaPruebaBrucelosisLeche").hide();
				$('#detallePruebaBrucelosisLecheConsultaFS').show();
				$('#detallePruebaBrucelosisLecheFS').hide();
			$("#nuevaPruebaBrucelosisSangre").hide();
				$('#detallePruebaBrucelosisSangreConsultaFS').show();
				$('#detallePruebaBrucelosisSangreFS').hide();
			$("#nuevaPatologiaTuberculosis").hide();
				$('#detallePatologiaTuberculosisConsultaFS').show();
				$('#detallePatologiaTuberculosisFS').hide();
			$("#nuevaPruebaTuberculosisLeche").hide();
				$('#detallePruebaTuberculosisLecheConsultaFS').show();
				$('#detallePruebaTuberculosisLecheFS').hide();
			$("#nuevaPruebaTuberculina").hide();
				$('#detallePruebaTuberculinaConsultaFS').show();
				$('#detallePruebaTuberculinaFS').hide();

				//revisar
			$('#cerrarCertificacionBT').hide();
			$('#cerrarCertificacionBTConsulta').show();
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

	$("#modificarCertificacionBT").submit(function(event){

		$("#modificarCertificacionBT").attr('data-opcion', 'modificarCertificacionBT');
	    $("#modificarCertificacionBT").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#fecha").val())){
			error = true;
			$("#fecha").addClass("alertaCombo");
		}

		if(!$.trim($("#nombreEncuestado").val()) || !esCampoValido("#nombreEncuestado")){
			error = true;
			$("#nombreEncuestado").addClass("alertaCombo");
		}

		/*if(!$.trim($("#nombrePredio").val()) || !esCampoValido("#nombrePredio")){
			error = true;
			$("#nombrePredio").addClass("alertaCombo");
		}*/

		if(!$.trim($("#numCertFiebreAftosa").val())){
			error = true;
			$("#numCertFiebreAftosa").addClass("alertaCombo");
		}

		/*if(!$.trim($("#certificacion").val())){
			error = true;
			$("#certificacion").addClass("alertaCombo");
		}*/

		/*if(!$.trim($("#nombrePropietario").val()) || !esCampoValido("#nombrePropietario")){
			error = true;
			$("#nombrePropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#cedulaPropietario").val()) || !esCampoValido("#cedulaPropietario")){
			error = true;
			$("#cedulaPropietario").addClass("alertaCombo");
		}*/

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

		/*if(!$.trim($("#provincia").val())){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}

		if(!$.trim($("#canton").val())){
			error = true;
			$("#canton").addClass("alertaCombo");
		}

		if(!$.trim($("#parroquia").val())){
			error = true;
			$("#parroquia").addClass("alertaCombo");
		}*/

		if(!$.trim($("#x").val())){
			error = true;
			$("#x").addClass("alertaCombo");
		}

		if(!$.trim($("#y").val())){
			error = true;
			$("#y").addClass("alertaCombo");
		}

		if(!$.trim($("#z").val())){
			error = true;
			$("#z").addClass("alertaCombo");
		}

		if(!$.trim($("#huso").val())){
			error = true;
			$("#huso").addClass("alertaCombo");
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

	
	
	//Mapa

	
	//Ubicación Provincia, Cantón, Parroquia, Oficina
	$("#provincia").change(function(event){
    	scanton ='0';
		scanton = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option data-latitud="'+array_canton[i]['latitud']+'"data-longitud="'+array_canton[i]['longitud']+'"data-zona="'+array_canton[i]['zona']+'" value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#canton').html(scanton);
	    $("#canton").removeAttr("disabled");
	    $("#nombreProvincia").val($("#provincia option:selected").text());	
	});

    $("#canton").change(function(){
    	$("#nombreCanton").val($("#canton option:selected").text());
        
		sparroquia ='0';
		sparroquia = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}

	    $('#parroquia').html(sparroquia);
		$("#parroquia").removeAttr("disabled");
	});

    $("#parroquia").change(function(){
    	$("#nombreParroquia").val($("#parroquia option:selected").text());
	});

    $("#explotacion").change(function(){
    	$("#nombreExplotacion").val($("#explotacion option:selected").text());
	});

    $("#oficina").change(function(){
        if($("#oficina option:selected").val()!='0'){
        	$('#nombreOficina').hide();
    		$("#nombreOficina").val($("#oficina option:selected").text());
        }else{
        	$("#nombreOficina").val('');
    	    $('#nombreOficina').show();
        }
	});

    $("#tipoProduccion").change(function(){
    	$("#nombreTipoProduccion").val($("#tipoProduccion option:selected").text());
	});

    $("#destinoLeche").change(function(){
    	$("#nombreDestinoLeche").val($("#destinoLeche option:selected").text());
	});

    $("#tipoExplotacion").change(function(){
    	$("#nombreTipoExplotacion").val($("#tipoExplotacion option:selected").text());
	});
    
    $("#animalesPredio").change(function(){
    	$("#nombreAnimalesPredio").val($("#animalesPredio option:selected").text());
	});

    $("#pediluvio").change(function(){
    	$("#nombrePediluvio").val($("#pediluvio option:selected").text());
	});

	$("#procedenciaAnimales").change(function(){
    	$("#nombreProcedenciaAnimales").val($("#procedenciaAnimales option:selected").text());
	});

	$("#categoriaAnimalesAdquiere").change(function(){
    	$("#nombreCategoriaAnimalesAdquiere").val($("#categoriaAnimalesAdquiere option:selected").text());
	});

	$("#procedenciaAgua").change(function(){
    	$("#nombreProcedenciaAgua").val($("#procedenciaAgua option:selected").text());
	});

	$("#frecuenciaVisitaVeterinario").change(function(){
    	$("#nombreFrecuenciaVisitaVeterinario").val($("#frecuenciaVisitaVeterinario option:selected").text());
	});

	$("#calendarioVacunacion").change(function(){
		if($("#calendarioVacunacion option:selected").val()=='Si'){

			$("#lMotivoVacunacion").show();
			$("#motivoVacunacion").show();
			$("#motivoVacunacion").attr('required','required');
    		$("#motivoVacunacion").val('');
    		
			$("#lVacunasAplicadas").show();
			$("#vacunasAplicadas").show();
			$("#vacunasAplicadas").attr('required','required');
    		$("#vacunasAplicadas").val('');
    		
			$("#lProcedenciaVacunas").show();
			$("#procedenciaVacunas").show();
			$("#procedenciaVacunas").attr('required','required');
    		$("#procedenciaVacunas").val('');
    		
			$("#lFechaVacunacion").show();
			$('#fechaVacunacion').show();
			$("#fechaVacunacion").attr('required','required');
    		$("#fechaVacunacion").val('');			
    	}else{
    		$("#lMotivoVacunacion").hide();
    		$("#motivoVacunacion").hide();
    		$("#motivoVacunacion").removeAttr('required');
    		$("#motivoVacunacion").val('0');
    		$("#nombreMotivoVacunacion").val('No Aplica');
    		
    		$("#lVacunasAplicadas").hide();
    		$("#vacunasAplicadas").hide();
    		$("#vacunasAplicadas").removeAttr('required');
    		$("#vacunasAplicadas").val('0');
    		$("#nombreVacunasAplicadas").val('No Aplica');

    		$("#lProcedenciaVacunas").hide();
    		$("#procedenciaVacunas").hide();
    		$("#procedenciaVacunas").removeAttr('required');
    		$("#procedenciaVacunas").val('0');
    		$("#nombreProcedenciaVacunas").val('No Aplica');

    		$("#lFechaVacunacion").hide();
    		$("#fechaVacunacion").hide();
    		$("#fechaVacunacion").removeAttr('required');
    	}
	});	
	
	$("#motivoVacunacion").change(function(){
    	$("#nombreMotivoVacunacion").val($("#motivoVacunacion option:selected").text());
	});

	$("#vacunasAplicadas").change(function(){
    	$("#nombreVacunasAplicadas").val($("#vacunasAplicadas option:selected").text());
	});

	$("#procedenciaVacunas").change(function(){
    	$("#nombreProcedenciaVacunas").val($("#procedenciaVacunas option:selected").text());
	});

	$("#sistemaEmpleado").change(function(){
    	$("#nombreSistemaEmpleado").val($("#sistemaEmpleado option:selected").text());
	});

	$("#procedenciaPajuelas").change(function(){
    	$("#nombreProcedenciaPajuelas").val($("#procedenciaPajuelas option:selected").text());
	});

	$("#lugarPariciones").change(function(){
    	$("#nombreLugarPariciones").val($("#lugarPariciones option:selected").text());
	});

	$("#abortos").change(function(){
		if($("#abortos option:selected").val()=='Si'){
        	
    		$("#lNumeroAbortos").show();
    		$("#numeroAbortos").show();
    		$("#numeroAbortos").attr('required','required');
    		$("#numeroAbortos").val('');
    		
    		$("#lTejidosAbortados").show();
    		$("#tejidosAbortados").show();
    		$("#tejidosAbortados").attr('required','required');
    		$("#nombreTejidosAbortados").val('');
    	}else{
    		$("#lNumeroAbortos").hide();
    		$("#numeroAbortos").hide();
    		$("#numeroAbortos").removeAttr('required');
    		$("#numeroAbortos").val('0');
    		
    		$("#lTejidosAbortados").hide();
    		$("#tejidosAbortados").hide();
    		$("#tejidosAbortados").removeAttr('required');
    		$("#tejidosAbortados").val('0');
    		$("#nombreTejidosAbortados").val('No Aplica');
    	}
	});	

	$("#tejidosAbortados").change(function(){
    	$("#nombreTejidosAbortados").val($("#tejidosAbortados option:selected").text());
	});

	$("#pruebasBrucelosisLeche").change(function(){
		if($("#pruebasBrucelosisLeche option:selected").val()=='Si'){
        	
    		$("#lResultadoBrucelosisLeche").show();
    		$("#resultadoBrucelosisLeche").show();
    		$("#resultadoBrucelosisLeche").attr('required','required');
    	}else{
    		$("#lResultadoBrucelosisLeche").hide();
    		$("#resultadoBrucelosisLeche").hide();
    		$("#resultadoBrucelosisLeche").removeAttr('required');
    		$("#resultadoBrucelosisLeche").val('No Aplica');
    	}
	});

	$("#pruebasBrucelosisSangre").change(function(){
		if($("#pruebasBrucelosisSangre option:selected").val()=='Si'){
        	
			$("#lResultadoBrucelosisSangre").show();
			$("#resultadoBrucelosisSangre").show();
			$("#resultadoBrucelosisSangre").attr('required','required');
			$("#resultadoBrucelosisSangre").val('');
			
			$("#lPruebasLaboratorio").show();
			$("#pruebasLaboratorio").show();
			$("#pruebasLaboratorio").attr('required','required');
			$("#pruebasLaboratorio").val('');
    		$("#nombrePruebasLaboratorio").val('');
			
			$("#lLaboratorio").show();
			$("#laboratorio").show();
			$("#laboratorio").attr('required','required');
			$("#laboratorio").val('');
			$("#nombrelaboratorio").hide();
    		$("#nombreLaboratorio").val('');
    		
    	}else{
    		$("#lResultadoBrucelosisSangre").hide();
			$("#resultadoBrucelosisSangre").hide();
			$("#resultadoBrucelosisSangre").removeAttr('required');
			$("#resultadoBrucelosisSangre").val('No Aplica');
			
			$("#lPruebasLaboratorio").hide();
			$("#pruebasLaboratorio").hide();
			$("#pruebasLaboratorio").removeAttr('required');
			$("#pruebasLaboratorio").val('0');
    		$("#nombrePruebasLaboratorio").val('No Aplica');
			
			$("#lLaboratorio").hide();
			$("#laboratorio").hide();
			$("#laboratorio").removeAttr('required');
			$("#laboratorio").val('0');
			$("#nombrelaboratorio").hide();
    		$("#nombreLaboratorio").val('No Aplica');
    	}
	});

	$("#resultadoBrucelosisSangre").change(function(){
		if($("#resultadoBrucelosisSangre option:selected").val()=='Positivo'){
        	
			$("#lDestinoAnimalesPositivos").show();
			$("#destinoAnimalesPositivos").show();
    		$("#destinoAnimalesPositivos").attr('required','required');
    		$("#destinoAnimalesPositivos").val('');
    		$("#nombreDestinoAnimalesPositivos").val('');
    		
    	}else{
    		$("#lDestinoAnimalesPositivos").hide();
			$("#destinoAnimalesPositivos").hide();
    		$("#destinoAnimalesPositivos").removeAttr('required');
    		$("#destinoAnimalesPositivos").val('0');
    		$("#nombreDestinoAnimalesPositivos").val('No Aplica');
    	}
	});

	$("#pruebasLaboratorio").change(function(){
    	$("#nombrePruebasLaboratorio").val($("#pruebasLaboratorio option:selected").text());
	});

	$("#laboratorio").change(function(){		
        if($("#laboratorio option:selected").val()!='0'){
        	$('#nombreLaboratorio').hide();
    		$("#nombreLaboratorio").val($("#laboratorio option:selected").text());
        }else{
        	$("#nombreLaboratorio").val('');
    	    $('#nombreLaboratorio').show();
        }
	});

	$("#destinoAnimalesPositivos").change(function(){
    	$("#nombreDestinoAnimalesPositivos").val($("#destinoAnimalesPositivos option:selected").text());
	});

	$("#pruebasTuberculosisLeche").change(function(){
		if($("#pruebasTuberculosisLeche option:selected").val()=='Si'){
        	
    		$("#lResultadoTuberculosisLeche").show();
    		$("#resultadoTuberculosisLeche").show();
    		$("#resultadoTuberculosisLeche").attr('required','required');
    	}else{
    		$("#lResultadoTuberculosisLeche").hide();
    		$("#resultadoTuberculosisLeche").hide();
    		$("#resultadoTuberculosisLeche").removeAttr('required');
    		$("#resultadoTuberculosisLeche option:selected").val('No Aplica');
    	}
	});

	$("#pruebasTuberculina").change(function(){
		if($("#pruebasTuberculina option:selected").val()=='Si'){

			$("#lResultadoTuberculina").show();
			$("#resultadoTuberculina").show();	
			$("#resultadoTuberculina").attr('required','required');
			$("#resultadoTuberculina").val('');
			
			$("#lLaboratorioTuberculina").show();
			$("#laboratorioTuberculina").show();
			$("#laboratorioTuberculina").attr('required','required');
			$("#laboratorioTuberculina").val('');
    		$("#nombreLaboratorioTuberculina").val('');

    		$("#lPruebasLaboratorio").show();
			$("#pruebasLaboratorio").show();
			$("#pruebasLaboratorio").attr('required','required');
			$("#pruebasLaboratorio").val('');
    		$("#nombrePruebasLaboratorio").val('');
			
			/*$("#lDestinoAnimalesPositivosTuberculina").show();
			$("#destinoAnimalesPositivosTuberculina").show();
    		$("#destinoAnimalesPositivosTuberculina").attr('required','required');
    		$("#destinoAnimalesPositivosTuberculina").val('');
    		$("#nombreDestinoAnimalesPositivosTuberculina").val('');*/

    		//Revisar
    		//$("#lDestinoAnimalesPositivosTuberculina").hide();
    		//$("#destinoAnimalesPositivosTuberculina").val(''); 
    		
    	}else{
    		$("#lResultadoTuberculina").hide();
			$("#resultadoTuberculina").hide();
			$("#resultadoTuberculina").removeAttr('required');
			$("#resultadoTuberculina").val('No Aplica');
			
			$("#lLaboratorioTuberculina").hide();
			$("#laboratorioTuberculina").hide();
			$("#laboratorioTuberculina").removeAttr('required');
			$("#laboratorioTuberculina").val('0');
    		$("#nombreLaboratorioTuberculina").val('No Aplica');

    		$("#lPruebasLaboratorio").hide();
			$("#pruebasLaboratorio").hide();
			$("#pruebasLaboratorio").removeAttr('required');
			$("#pruebasLaboratorio").val('0');
    		$("#nombrePruebasLaboratorio").val('No Aplica');
			
			$("#lDestinoAnimalesPositivosTuberculina").hide();
			$("#destinoAnimalesPositivosTuberculina").hide();
    		$("#destinoAnimalesPositivosTuberculina").removeAttr('required');
    		$("#destinoAnimalesPositivosTuberculina").val('0');
    		$("#nombreDestinoAnimalesPositivosTuberculina").val('No Aplica');

    		/*$("#lDestinoAnimalesPositivosTuberculina").hide();
			$("#destinoAnimalesPositivos").hide();
			$("#destinoAnimalesPositivos").removeAttr('required');
			$("#destinoAnimalesPositivos").val('0');
			$("#nombreDestinoAnimalesPositivos").hide();
    		$("#nombreDestinoAnimalesPositivos").val('No Aplica');*/

    		//Revisar
    	}
	});

	$("#resultadoTuberculina").change(function(){
		if($("#resultadoTuberculina option:selected").val()=='Positivo'){

			$("#lDestinoAnimalesPositivosTuberculina").show();
			$("#destinoAnimalesPositivosTuberculina").show();
    		$("#destinoAnimalesPositivosTuberculina").attr('required','required');
    		$("#destinoAnimalesPositivosTuberculina").val('');
    		$("#nombreDestinoAnimalesPositivosTuberculina").val('');
    		
    	}else{
    		$("#lDestinoAnimalesPositivosTuberculina").hide();
			$("#destinoAnimalesPositivosTuberculina").hide();
    		$("#destinoAnimalesPositivosTuberculina").removeAttr('required');
    		$("#destinoAnimalesPositivosTuberculina").val('0');
    		$("#nombreDestinoAnimalesPositivosTuberculina").val('No Aplica');
    		$("#nombreDestinoAnimalesPositivosTuberculina").hide();
    	}
	});

	$("#laboratorioTuberculina").change(function(){		
        if($("#laboratorioTuberculina option:selected").val()!='0'){
        	$('#nombreLaboratorioTuberculina').hide();
    		$("#nombreLaboratorioTuberculina").val($("#laboratorioTuberculina option:selected").text());
        }else{
            $("#nombreLaboratorioTuberculina").val('');
    	    $('#nombreLaboratorioTuberculina').show();
        }
	});

	$("#pruebasLaboratorio").change(function(){
    	$("#nombrePruebasLaboratorio").val($("#pruebasLaboratorio option:selected").text());
	});

	$("#destinoAnimalesPositivosTuberculina").change(function(){
    	$("#nombreDestinoAnimalesPositivosTuberculina").val($("#destinoAnimalesPositivosTuberculina option:selected").text());
	});
	

	$("#motivoCatastro").change(function(){
    	$("#nombreMotivoCatastro").val($("#motivoCatastro option:selected").text());
	});

	$("#actividad").change(function(){
    	$("#nombreTipoActividad").val($("#actividad option:selected").text());
	});

	$("#bioseguridad").change(function(){
    	$("#nombreBioseguridad").val($("#bioseguridad option:selected").text());
	});

	$("#medidaSanitaria").change(function(){
    	$("#nombreMedidaSanitaria").val($("#medidaSanitaria option:selected").text());
	});

	$("#enfermedad").change(function(event){
		if($("#enfermedad option:selected").val()!='0'){
        	$('#nombreEnfermedad').hide();
    		$("#nombreEnfermedad").val($("#enfermedad option:selected").text());
        }else{
        	$("#nombreEnfermedad").val('');
    	    $('#nombreEnfermedad').show();
        }
	});

	$("#vacuna").change(function(event){
		if($("#vacuna option:selected").val()!='0'){
        	$('#nombreVacuna').hide();
    		$("#nombreVacuna").val($("#vacuna option:selected").text());
        }else{
        	$("#nombreVacuna").val('');
    	    $('#nombreVacuna').show();
        }
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
	    		$('#lFechaInspeccion').show();
	    		$('#fechaInspeccion').show();
	    		$('#fechaInspeccion').attr('required','required'); 
	    		
	        }else if ($("#resultado option:selected").val()=='rechazado'){
	        	$('#lLaboratorioMuestras').hide();
	    		$('#laboratorioMuestras').hide();
	    		$('#nombreLaboratorioMuestras').hide();
	    		$('#laboratorioMuestras').removeAttr('required');
	    		$('#lFechaInspeccion').hide();
	    		$('#fechaInspeccion').hide();
	    		$('#fechaInspeccion').removeAttr('required');
	    		alert('Debe ingresar el motivo por el que se rechaza la solicitud en el campo de observaciones');
	        }else{
	        	$('#lLaboratorioMuestras').hide();
	    		$('#laboratorioMuestras').hide();
	    		$('#nombreLaboratorioMuestras').hide();
	    		$('#laboratorioMuestras').removeAttr('required');
	    		$('#lFechaInspeccion').hide();
	    		$('#fechaInspeccion').hide();
	    		$('#fechaInspeccion').removeAttr('required');
	        }
		});


    
  //Cierre y Envío a Revisión
	$("#cerrarCertificacionBT").submit(function(event){

		$("#cerrarCertificacionBT").attr('data-opcion', 'guardarCierreCertificacionBTTecnico');
	    $("#cerrarCertificacionBT").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#resultado").val())){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}

		if($("#resultado option:selected").val()=='tomaMuestras'){
			if(!$.trim($("#fechaInspeccion").val())){
				error = true;
				$("#fechaInspeccion").addClass("alertaCombo");
			}
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

	

    $("#archivo").click(function(){
    	$("#subirArchivo button").removeAttr("disabled");
    });

    $("#archivoInforme").click(function(){
    	$("#subirArchivoInforme button").removeAttr("disabled");
    });

    $("#lProgramaPrediosLibres").hide();
    $("#programaPrediosLibres").hide();
    
    $("#trabajadoresAnimalesPredio").change(function(){
        if($("#trabajadoresAnimalesPredio option:selected").val()=='No Aplica'){        	
        	$("#programaPrediosLibres").hide();
        	$("#programaPrediosLibres").removeAttr('required');
        	cargarValorDefecto("programaPrediosLibres","No Aplica");
        }else if($("#trabajadoresAnimalesPredio option:selected").val()=='No'){        	
        	$("#programaPrediosLibres").hide();
        	$("#programaPrediosLibres").removeAttr('required');
        	cargarValorDefecto("programaPrediosLibres","No");
        }else{
        	$("#programaPrediosLibres").show();
        	$("#programaPrediosLibres").attr('required', 'required');
        	cargarValorDefecto("programaPrediosLibres","");
        }    	
	});
</script>