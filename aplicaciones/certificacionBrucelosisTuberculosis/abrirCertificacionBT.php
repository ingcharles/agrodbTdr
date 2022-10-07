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
	<h1>Predios para Certificación como Libres de Brucelosis y Tuberculosis Bovina</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>

<div class="pestania">
	<h2>Identificación y Localización del Predio</h2>

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
						<label>Informe:</label>
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
				
		<div data-linea="9">
			<button id="modificar" type="button" class="editar">Editar</button>
		</div>
	</div>
	
	<div id="actualizacion">
		<form id="modificarCertificacionBT" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="modificarCertificacionBT" data-destino="detalleItem" >
			<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />	
		
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
					<input type="text" id="numCertFiebreAftosa" name="numCertFiebreAftosa" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $certificacionBT['numero_certificado_fiebre_aftosa'];?>"/>
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
					<input type="text" id="huso" name="huso" maxlength="4" data-er="^[0-9]+$" value="<?php echo $certificacionBT['huso_zona'];?>"/>
				</div>
				
				<div data-linea="20">	
					<input type="hidden" id="latitud" name="latitud" />
					<input type="hidden" id="longitud" name="longitud" />
					<input type="hidden" id="zona" name="zona" />
					<input type="hidden" id="zoom" name="zoom"/>
				</div>
		
			</fieldset>
		
			<div data-linea="21">
					<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
			</div>
		
		</form>
	</div>
</div>

<div class="pestania">

	<h2>Datos Generales del Predio</h2>

	<form id="nuevaInformacionPredio" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarDatosGenerales" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
	
		<fieldset>
			<legend>Información del Predio</legend>
			
			<div data-linea="22">
				<label>Superficie del Predio (Ha.):</label>
				<input type="text" id="superficiePredio" name="superficiePredio" data-er="^[0-9.]+$" />
			</div>
				
			<div data-linea="22">
				<label>Superficie de Pastos (Ha.):</label>
				<input type="text" id="superficiePastos" name="superficiePastos" data-er="^[0-9.]+$" />
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
												$infoPredio['control_ingreso_animal'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleInformacionPredioConsultaFS">
		<legend>Información del Predio Registrada</legend>
		<table id="detalleInformacionPredioConsulta">
			<thead>
				<tr>
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
												$infoPredio['control_ingreso_animal'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<form id="nuevaProduccionExplotacionDestino" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarProduccionExplotacionDestino" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
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
						<option value="2">Lechería</option>
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
															$infoProduccion['tipo_explotacion'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleProduccionExplotacionDestinoConsultaFS">
		<legend>Producción, Explotación y Destinos Registrados</legend>
		<table id="detalleProduccionExplotacionDestinoConsulta">
			<thead>
				<tr>
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
															$infoProduccion['tipo_explotacion'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<form id="nuevoInventarioAnimal" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarInventarioAnimal" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
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
				    <th width="15%">Animales Predio</th>
				    <th width="15%">Número existencias</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoInventario = pg_fetch_assoc($inventario)){
					echo $cbt->imprimirLineaInventarioAnimalCertificacionBT($infoInventario['id_certificacion_bt_inventario_animal'],
															$infoInventario['animales_predio'], $infoInventario['numero_animales_predio'], $ruta);												
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleInventarioAnimalConsultaFS">
		<legend>Inventario de Animales en el Predio Registrados</legend>
		<table id="detalleInventarioAnimalConsulta">
			<thead>
				<tr>
				    <th width="15%">Animales Predio</th>
				    <th width="15%">Número existencias</th>
				</tr>
			</thead>
			<?php 
				while ($infoInventario = pg_fetch_assoc($inventarioConsulta)){
					echo $cbt->imprimirLineaInventarioPredioCertificacionBTConsulta($infoInventario['id_certificacion_bt_inventario_animal'],
															$infoInventario['animales_predio'], $infoInventario['numero_animales_predio'], $ruta);												
				}
			?>
		</table>
	</fieldset>
	
	<form id="nuevoPediluvio" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPediluvio" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
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
				    <th width="15%">Pediluvios existentes</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoPediluvio = pg_fetch_assoc($pediluvio)){
					echo $cbt->imprimirLineaPediluvioCertificacionBT($infoPediluvio['id_certificacion_bt_pediluvio'], 
																$infoPediluvio['pediluvio'], $ruta);												
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePediluvioConsultaFS">
		<legend>Pediluvios Registrados</legend>
		<table id="detallePediluvioConsulta">
			<thead>
				<tr>
				    <th width="15%">Pediluvios existentes</th>
				</tr>
			</thead>
			<?php 
				while ($infoPediluvio = pg_fetch_assoc($pediluvioConsulta)){
					echo $cbt->imprimirLineaPediluvioCertificacionBTConsulta($infoPediluvio['id_certificacion_bt_pediluvio'], 
																$infoPediluvio['pediluvio'], $ruta);												
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Manejo General de Animales y Potreros</h2>

	<form id="nuevoManejoAnimalesPotreros" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarManejoAnimalesPotreros" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
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
				<label>Están dentro del programa de predios libres:</label>
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
																		$infoManejoAnimal['dentro_programa_predios_libres'], $ruta);												
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleManejoAnimalesPotrerosConsultaFS">
		<legend>Manejo de Animales y Potreros Registrados</legend>
		<table id="detalleManejoAnimalesPotrerosConsulta">
			<thead>
				<tr>
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
																		$infoManejoAnimal['dentro_programa_predios_libres'], $ruta);												
				}
			?>
		</table>
	</fieldset>
	
	<form id="nuevaAdquisicionAnimales" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarAdquisicionAnimales" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
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
														$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleAdquisicionAnimalesConsultaFS">
		<legend>Adquisición de Animales para el Predio Registrados</legend>
		<table id="detalleAdquisicionAnimalesConsulta">
			<thead>
				<tr>
				    <th width="15%">Procedencia Animales</th>
				    <th width="15%">Categoría</th>
				</tr>
			</thead>
			<?php 
				while ($infoAdquisicionAnimales = pg_fetch_assoc($adquisicionAnimalesConsulta)){
					echo $cbt->imprimirLineaAdquisicionAnimalesCertificacionBTConsulta($infoAdquisicionAnimales['id_certificacion_bt_adquisicion_animales'], 
														$infoAdquisicionAnimales['procedencia_animales'], 
														$infoAdquisicionAnimales['categoria_animales_adquiriente'], 
														$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<form id="nuevaProcedenciaAgua" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarProcedenciaAgua" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
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
				    <th width="15%">Procedencia Agua</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoProcedenciaAgua = pg_fetch_assoc($procedenciaAgua)){
					echo $cbt->imprimirLineaProcedenciaAguaCertificacionBT($infoProcedenciaAgua['id_certificacion_bt_procedencia_agua'], 
																	$infoProcedenciaAgua['procedencia_agua'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleProcedenciaAguaConsultaFS">
		<legend>Procedencia Agua Registrada</legend>
		<table id="detalleProcedenciaAguaConsulta">
			<thead>
				<tr>
				    <th width="15%">Procedencia Agua</th>
				</tr>
			</thead>
			<?php 
				while ($infoProcedenciaAgua = pg_fetch_assoc($procedenciaAguaConsulta)){
					echo $cbt->imprimirLineaProcedenciaAguaCertificacionBTConsulta($infoProcedenciaAgua['id_certificacion_bt_procedencia_agua'], 
																	$infoProcedenciaAgua['procedencia_agua'], $ruta);
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
											$infoVeterinario['frecuencia_visita_veterinario'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleVeterinarioConsultaFS">
		<legend>Veterinario Registrado</legend>
		<table id="detalleVeterinarioConsulta">
			<thead>
				<tr>
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
											$infoVeterinario['frecuencia_visita_veterinario'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<h3>II. Vacunación</h3>

	<form id="nuevaInformacionVacunacion" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarInformacionVacunacion" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
		<fieldset>
			<legend>Información de Vacunación</legend>
			
			<div data-linea="40">
				<label>Motivo Vacunación:</label>
					<select id="motivoVacunacion" name="motivoVacunacion" required="required" >
						<option value="">Motivo Vacunación....</option>
						<option value="1">Calendario Vacunación</option>
						<option value="2">Primera Vacunación contra Brucelosis</option>
						<option value="3">Última Vacunación contra Brucelosis</option>
					</select>
					
					<input type='hidden' id='nombreMotivoVacunacion' name='nombreMotivoVacunacion' />					
			</div>
			
			<div data-linea="40">
				<label>Vacunas Aplicadas:</label>
					<select id="vacunasAplicadas" name="vacunasAplicadas" required="required" >
						<option value="">Vacunas Aplicadas....</option>
						<option value="1">Triple</option>
						<option value="2">Aftosa</option>
						<option value="3">Cepa 19</option>
						<option value="4">RB51</option>
					</select>
					
					<input type='hidden' id='nombreVacunasAplicadas' name='nombreVacunasAplicadas' />					
			</div>
			
			<div data-linea="41">
				<label>Procedencia Vacunas:</label>
					<select id="procedenciaVacunas" name="procedenciaVacunas" required="required" >
						<option value="">Procedencia Vacunas....</option>
						<option value="1">Almacén Localidad</option>
						<option value="2">Almacén Ciudad</option>
						<option value="3">Veterinario</option>
					</select>
					
					<input type='hidden' id='nombreProcedenciaVacunas' name='nombreProcedenciaVacunas' />					
			</div>
			
			<div data-linea="41">
				<label>Fecha Vacunación:</label>
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
				    <th width="15%">Motivo Vacunación</th>
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
																	$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleInformacionVacunacionConsultaFS">
		<legend>Información de Vacunación</legend>
		<table id="detalleInformacionVacunacionConsulta">
			<thead>
				<tr>
				    <th width="15%">Motivo Vacunación</th>
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
																	$ruta);
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
													$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleReproduccionConsultaFS">
		<legend>Información de Reproducción Animal Registrada</legend>
		<table id="detalleReproduccionConsulta">
			<thead>
				<tr>
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
													$ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Aspectos Sanitarios</h2>
	<h3>IV. Patologías Brucelosis</h3>

	<form id="nuevaPatologiaBrucelosis" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPatologiaBrucelosis" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
		<fieldset>
			<legend>Información de Patologías Animales</legend>
			
			<div data-linea="44">
				<label>Retención de Placenta:</label>
				<select id="retencionPlacenta" name="retencionPlacenta" required="required" >
						<option value="">Retención Placenta....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div data-linea="44">
				<label>Nacimiento Terneros Débiles:</label>
				<select id="nacimientoTernerosDebiles" name="nacimientoTernerosDebiles" required="required" >
						<option value="">Nacimiento Terneros....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div data-linea="45">
				<label>Problemas Esterilidad:</label>
				<select id="problemasEsterilidad" name="problemasEsterilidad" required="required" >
						<option value="">Problemas Esterilidad....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div data-linea="45">
				<label>Metritis Post-Parto:</label>
				<select id="metritisPostParto" name="metritisPostParto" required="required" >
						<option value="">Metritis Post-Parto....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div data-linea="46">
				<label>Hinchazón Articulaciones:</label>
				<select id="hinchazonArticulaciones" name="hinchazonArticulaciones" required="required" >
						<option value="">Hinchazón Articulaciones....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div data-linea="46">
				<label>Epididimitis u Orquitis Machos:</label>
				<select id="epididimitisOrquitis" name="epididimitisOrquitis" required="required" >
						<option value="">Epididimitis u Orquitis....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
		</fieldset>
	</form>		
	
	<fieldset id="detallePatologiaBrucelosisFS">
		<legend>Patología de Brucelosis Registrada</legend>
		<table id="detallePatologiaBrucelosis">
			<thead>
				<tr>
				    <th width="15%">Retención Placenta</th>
				    <th width="15%">Nacimiento Terneros Débiles</th>
				    <th width="15%">Problemas Esterilidad</th>
				    <th width="15%">Metritis Post-Parto</th>
				    <th width="15%">Hinchazón Articulaciones</th>
				    <th width="15%">Epididimitis y Orquitis Machos</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoPatologia = pg_fetch_assoc($patologia)){
					echo $cbt->imprimirLineaPatologiaBrucelosisCertificacionBT($infoPatologia['id_certificacion_bt_patologia_brucelosis'], 
															$infoPatologia['retencion_placenta'], $infoPatologia['nacimient_terneros_debiles'], 
															$infoPatologia['problemas_esterilidad'], $infoPatologia['metritis_post_parto'], 
															$infoPatologia['hinchazon_articulaciones'], $infoPatologia['epididimitis_orquitis'], 
															$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePatologiaBrucelosisConsultaFS">
		<legend>Patología de Brucelosis Registrada</legend>
		<table id="detallePatologiaBrucelosisConsulta">
			<thead>
				<tr>
				    <th width="15%">Retención Placenta</th>
				    <th width="15%">Nacimiento Terneros Débiles</th>
				    <th width="15%">Problemas Esterilidad</th>
				    <th width="15%">Metritis Post-Parto</th>
				    <th width="15%">Hinchazón Articulaciones</th>
				    <th width="15%">Epididimitis y Orquitis Machos</th>
				</tr>
			</thead>
			<?php 
				while ($infoPatologia = pg_fetch_assoc($patologiaConsulta)){
					echo $cbt->imprimirLineaPatologiaBrucelosisCertificacionBTConsulta($infoPatologia['id_certificacion_bt_patologia_brucelosis'], 
															$infoPatologia['retencion_placenta'], $infoPatologia['nacimient_terneros_debiles'], 
															$infoPatologia['problemas_esterilidad'], $infoPatologia['metritis_post_parto'], 
															$infoPatologia['hinchazon_articulaciones'], $infoPatologia['epididimitis_orquitis'], 
															$ruta);
				}
			?>
		</table>
	</fieldset>
	
	
	
		

	<form id="nuevoAbortoBrucelosis" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarAbortoBrucelosis" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
		<fieldset>
			<legend>Información de Abortos Animales</legend>
			
			
			<div data-linea="47">
				<label>Se han producido abortos :</label>
					<select id="abortos" name="abortos" required="required" >
						<option value="">Abortos....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
									
			</div>
			
			<div data-linea="47">
				<label id="lNumeroAbortos">Número de abortos:</label>
				<input type="text" id="numeroAbortos" name="numeroAbortos" data-er="^[0-9]+$" />
			</div>
			
			<div data-linea="48">
				<label id="lTejidosAbortados">Destino de Tejidos Abortados:</label>
					<select id="tejidosAbortados" name="tejidosAbortados" >
						<option value="">Tejidos Abortados....</option>
						<option value="1">Entierra</option>
						<option value="2">Incinera</option>
						<option value="3">Bota a la Basura</option>
						<option value="4">Deja en el Lugar</option>
						<option value="5">Consume</option>
						<option value="0">No Aplica</option>
					</select> 	
					
					<input type="hidden" id="nombreTejidosAbortados" name="nombreTejidosAbortados" />					
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
		</fieldset>
		
		
	</form>
	
	<fieldset id="detalleAbortoBrucelosisFS">
		<legend>Información de Abortos Registrados</legend>
		<table id="detalleAbortoBrucelosis">
			<thead>
				<tr>
				    <th width="15%">Abortos Presentados</th>
				    <th width="15%">Número Abortos</th>
				    <th width="15%">Destino de Tejidos Abortados</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoAbortos = pg_fetch_assoc($abortos)){
					echo $cbt->imprimirLineaAbortosBrucelosisCertificacionBT($infoAbortos['id_certificacion_bt_abortos_brucelosis'], 
															$infoAbortos['abortos'], $infoAbortos['numero_abortos'], 
															$infoAbortos['tejidos_abortados'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleAbortoBrucelosisConsultaFS">
		<legend>Información de Abortos Registrados</legend>
		<table id="detalleAbortoBrucelosisConsulta">
			<thead>
				<tr>
				    <th width="15%">Abortos Presentados</th>
				    <th width="15%">Número Abortos</th>
				    <th width="15%">Destino de Tejidos Abortados</th>
				</tr>
			</thead>
			<?php 
				while ($infoAbortos = pg_fetch_assoc($abortosConsulta)){
					echo $cbt->imprimirLineaAbortosBrucelosisCertificacionBTConsulta($infoAbortos['id_certificacion_bt_abortos_brucelosis'], 
															$infoAbortos['abortos'], $infoAbortos['numero_abortos'], 
															$infoAbortos['tejidos_abortados'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>


<div class="pestania">

	<h2>Aspectos Sanitarios</h2>
	<h3>V. Pruebas Diagnósticas Brucelosis</h3>

	<form id="nuevaPruebaBrucelosisLeche" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPruebaBrucelosisLeche" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
		<fieldset>
			<legend>Pruebas de Brucelosis en Leche</legend>
			
			<div data-linea="49">
				<label>Pruebas de brucelosis en leche:</label>
					<select id="pruebasBrucelosisLeche" name="pruebasBrucelosisLeche" required="required" >
						<option value="">Brucelosis Leche....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
						
					</select> 					
			</div>
			
			<div data-linea="49">
				<label id="lResultadoBrucelosisLeche">Resultado:</label>
					<select id="resultadoBrucelosisLeche" name="resultadoBrucelosisLeche" required="required" >
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
	
	<fieldset id="detallePruebaBrucelosisLecheFS">
		<legend>Prueba de Brucelosis en Leche Registrada</legend>
		<table id="detallePruebaBrucelosisLeche">
			<thead>
				<tr>
				    <th width="15%">Prueba de Brucelosis en Leche</th>
				    <th width="15%">Resultado</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoPruebasLeche = pg_fetch_assoc($pruebasLeche)){
					echo $cbt->imprimirLineaPruebaBrucelosisLecheCertificacionBT($infoPruebasLeche['id_certificacion_bt_prueba_brucelosis_leche'], 
												$infoPruebasLeche['pruebas_brucelosis_leche'], 
												$infoPruebasLeche['resultado_brucelosis_leche'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePruebaBrucelosisLecheConsultaFS">
		<legend>Prueba de Brucelosis en Leche Registrada</legend>
		<table id="detallePruebaBrucelosisLecheConsulta">
			<thead>
				<tr>
				    <th width="15%">Prueba de Brucelosis en Leche</th>
				    <th width="15%">Resultado</th>
				</tr>
			</thead>
			<?php 
				while ($infoPruebasLeche = pg_fetch_assoc($pruebasLecheConsulta)){
					echo $cbt->imprimirLineaPruebaBrucelosisLecheCertificacionBTConsulta($infoPruebasLeche['id_certificacion_bt_prueba_brucelosis_leche'], 
												$infoPruebasLeche['pruebas_brucelosis_leche'], 
												$infoPruebasLeche['resultado_brucelosis_leche'], $ruta);
				}
			?>
		</table>
	</fieldset>
			
	<form id="nuevaPruebaBrucelosisSangre" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPruebaBrucelosisSangre" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
		<fieldset>
			<legend>Pruebas de Brucelosis en Sangre</legend>
			
			<div data-linea="50">
				<label>Pruebas brucelosis en sangre:</label>
					<select id="pruebasBrucelosisSangre" name="pruebasBrucelosisSangre" required="required" >
						<option value="">Brucelosis Leche....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
						
					</select> 					
			</div>
			
			<div data-linea="50">
				<label id="lResultadoBrucelosisSangre">Resultado:</label>
					<select id="resultadoBrucelosisSangre" name="resultadoBrucelosisSangre" >
						<option value="">Resultado....</option>
						<option value="Positivo">Positivo</option>
						<option value="Negativo">Negativo</option>						
					</select> 					
					
			</div>
			
			<div data-linea="51">
				<label id="lPruebasLaboratorio">Pruebas de Laboratorio:</label>
					<select id="pruebasLaboratorio" name="pruebasLaboratorio" >
						<option value="">Pruebas....</option>
						<option value="1">Rosa de Bengala</option>
						<option value="2">ELISA indirecto</option>
						<option value="3">ELISA competitivo</option>
						
					</select>
					
					<input type="hidden" id="nombrePruebasLaboratorio" name="nombrePruebasLaboratorio"  /> 					
			</div>
			
			<div data-linea="52">
				<label id="lLaboratorio">Laboratorio:</label>
					<select id="laboratorio" name="laboratorio">
						<option value="">Laboratorio....</option>
						<option value="1">ANIMALAB</option>
						<option value="2">LIVEXLAB</option>
						<option value="3">VETELAB</option>
						<option value="4">AGROCALIDAD</option>
						<option value="0">Otro</option>
					</select>
			</div>
			
			<div data-linea="52">
				<input type="text" id="nombreLaboratorio" name="nombreLaboratorio"  /> 					
			</div>
			
			<div data-linea="53">
				<label id="lDestinoAnimalesPositivos">Destino animales positivos:</label>
					<select id="destinoAnimalesPositivos" name="destinoAnimalesPositivos" required="required" >
						<option value="">Animales Positivos....</option>
						<option value="1">Permanecen en el Predio</option>
						<option value="2">Camal</option>
						<option value="3">Venta a Comerciante</option>
						<option value="4">Venta en Feria</option>
					</select>
					
					<input type="hidden" id="nombreDestinoAnimalesPositivos" name="nombreDestinoAnimalesPositivos"  /> 					
			</div>
				
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detallePruebaBrucelosisSangreFS">
		<legend>Pruebas de Brucelosis en Sangre Registradas</legend>
		<table id="detallePruebaBrucelosisSangre">
			<thead>
				<tr>
				    <th width="15%">Prueba Sangre</th>
					<th width="15%">Resultado</th>
					<th width="15%">Laboratorio</th>
					<th width="15%">Destino Animales Positivos</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoPruebasSangre = pg_fetch_assoc($pruebasSangre)){
					echo $cbt->imprimirLineaPruebaBrucelosisSangreCertificacionBT($infoPruebasSangre['id_certificacion_bt_prueba_brucelosis_sangre'], 
														$infoPruebasSangre['pruebas_brucelosis_sangre'], $infoPruebasSangre['resultado_brucelosis_sangre'], 
														$infoPruebasSangre['pruebas_laboratorio'], $infoPruebasSangre['laboratorio'], 
														$infoPruebasSangre['destino_animales_positivos'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePruebaBrucelosisSangreConsultaFS">
		<legend>Pruebas de Brucelosis en Sangre Registradas</legend>
		<table id="detallePruebaBrucelosisSangreConsulta">
			<thead>
				<tr>
				    <th width="15%">Prueba Sangre</th>
					<th width="15%">Resultado</th>
					<th width="15%">Laboratorio</th>
					<th width="15%">Destino Animales Positivos</th>
				</tr>
			</thead>
			<?php 
				while ($infoPruebasSangre = pg_fetch_assoc($pruebasSangreConsulta)){
					echo $cbt->imprimirLineaPruebaBrucelosisSangreCertificacionBTConsulta($infoPruebasSangre['id_certificacion_bt_prueba_brucelosis_sangre'], 
														$infoPruebasSangre['pruebas_brucelosis_sangre'], $infoPruebasSangre['resultado_brucelosis_sangre'], 
														$infoPruebasSangre['pruebas_laboratorio'], $infoPruebasSangre['laboratorio'], 
														$infoPruebasSangre['destino_animales_positivos'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Aspectos Sanitarios</h2>
	<h3>VI. Patologías Tuberculosis</h3>

	<form id="nuevaPatologiaTuberculosis" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPatologiaTuberculosis" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
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
				<label>Abultamientos en cuerpo cuello, patas??:</label>
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
																		$infoPatologiaTuberculosis['abultamiento'], $infoPatologiaTuberculosis['fiebre_fluctuante'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePatologiaTuberculosisConsultaFS">
		<legend>Pruebas de Brucelosis en Sangre Registradas</legend>
		<table id="detallePatologiaTuberculosisConsulta">
			<thead>
				<tr>
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
																		$infoPatologiaTuberculosis['abultamiento'], $infoPatologiaTuberculosis['fiebre_fluctuante'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>


<div class="pestania">

	<h2>Aspectos Sanitarios</h2>
	<h3>VII. Pruebas Diagnósticas Tuberculosis</h3>

	<form id="nuevaPruebaTuberculosisLeche" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPruebaTuberculosisLeche" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
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
				    <th width="15%">Prueba de Tuberculosis en Leche</th>
				    <th width="15%">Resultado</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoPruebasLecheTub = pg_fetch_assoc($pruebasLecheTuberculosis)){
					echo $cbt->imprimirLineaPruebaTuberculosisLecheCertificacionBT($infoPruebasLecheTub['id_certificacion_bt_prueba_tuberculosis_leche'], 
																		$infoPruebasLecheTub['pruebas_tuberculosis_leche'], 
																		$infoPruebasLecheTub['resultado_tuberculosis_leche'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePruebaTuberculosisLecheConsultaFS">
		<legend>Prueba de Tuberculosis en Leche Registrada</legend>
		<table id="detallePruebaTuberculosisLecheConsulta">
			<thead>
				<tr>
				    <th width="15%">Prueba de Tuberculosis en Leche</th>
				    <th width="15%">Resultado</th>
				</tr>
			</thead>
			<?php 
				while ($infoPruebasLecheTub = pg_fetch_assoc($pruebasLecheTuberculosisConsulta)){
					echo $cbt->imprimirLineaPruebaTuberculosisLecheCertificacionBTConsulta($infoPruebasLecheTub['id_certificacion_bt_prueba_tuberculosis_leche'], 
																		$infoPruebasLecheTub['pruebas_tuberculosis_leche'], 
																		$infoPruebasLecheTub['resultado_tuberculosis_leche'], $ruta);
				}
			?>
		</table>
	</fieldset>
			
	<form id="nuevaPruebaTuberculina" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPruebaTuberculina" data-destino="detalleItem">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
		<fieldset>
			<legend>Pruebas de Tuberculina</legend>
			
			<div data-linea="50">
				<label id="lPruebasTuberculina">Pruebas de Tuberculina:</label>
					<select id="pruebasTuberculina" name="pruebasTuberculina" required="required" >
						<option value="">Tuberculina....</option>
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
				    <th width="15%">Prueba Tuberculina</th>
					<th width="15%">Resultado</th>
					<th width="15%">Laboratorio</th>
					<th width="15%">Destino Animales Positivos</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoPruebasTuberculina = pg_fetch_assoc($pruebasTuberculina)){
					echo $cbt->imprimirLineaPruebaTuberculinaCertificacionBT($infoPruebasTuberculina['id_certificacion_bt_prueba_tuberculina'], $infoPruebasTuberculina['pruebas_tuberculina'], 
																		$infoPruebasTuberculina['resultado_tuberculina'], $infoPruebasTuberculina['laboratorio'], 
																		$infoPruebasTuberculina['destino_animales_positivos'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePruebaTuberculinaConsultaFS">
		<legend>Pruebas Diagnósticas Tuberculina Registradas</legend>
		<table id="detallePruebaTuberculinaConsulta">
			<thead>
				<tr>
				    <th width="15%">Prueba Tuberculina</th>
					<th width="15%">Resultado</th>
					<th width="15%">Laboratorio</th>
					<th width="15%">Destino Animales Positivos</th>
				</tr>
			</thead>
			<?php 
				while ($infoPruebasTuberculina = pg_fetch_assoc($pruebasTuberculinaConsulta)){
					echo $cbt->imprimirLineaPruebaTuberculinaCertificacionBTConsulta($infoPruebasTuberculina['id_certificacion_bt_prueba_tuberculina'], $infoPruebasTuberculina['pruebas_tuberculina'], 
																		$infoPruebasTuberculina['resultado_tuberculina'], $infoPruebasTuberculina['laboratorio'], 
																		$infoPruebasTuberculina['destino_animales_positivos'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>



<div class="pestania">

	<h2>Finalizar Registro</h2>

	<form id="cerrarCertificacionBT" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarCierreCertificacionBTTecnico" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />
		
		<fieldset>
			<legend>Resultado del Proceso</legend>
			
			<div data-linea="53">
				<label>Resultado:</label>
					<select id="resultado" name="resultado" required="required" >
						<option value="">Seleccione....</option>
						<option value="tomaMuestras">Toma de Muestras</option>
						<option value="plantaCentral">Enviar a Planta Central</option>
						<option value="rechazado">Rechazado</option>
					</select>					 					
			</div>
			
			<div data-linea="54	">
				<label id="lFechaInspeccion" >Fecha inspección:</label>
				<input type="text" id="fechaInspeccion" name="fechaInspeccion" />
			</div>
			
			<div data-linea="55">
				<label>Observaciones:</label>
				<input type="text" id="observaciones" name="observaciones" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
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
			<?php echo $idCertificacionBT['resultado'];?>
		</div>

		<div data-linea="57">
			<label>Observaciones: </label>
			<?php echo $idCertificacionBT['observaciones'];?>
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
		$("#lDestinoAnimalesPositivosTuberculina").hide();
		$("#destinoAnimalesPositivosTuberculina").hide();
		$('#nombreLaboratorioTuberculina').hide();
		
		
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

		if(estado == 'cerrado'){
			if((perfil != false) || (perfil != '')){				
				$("#modificar").show();
				$("#nuevoMotivoCatastroPredioEquidos").show();
					$('#detalleMotivoCatastroPredioEquidosConsultaFS').hide();
					$('#detalleMotivoCatastroPredioEquidosFS').show();
				$("#nuevoTipoActividadCatastroPredioEquidos").show();
					$('#detalleTipoActividadCatastroPredioEquidosConsultaFS').hide();
					$('#detalleTipoActividadCatastroPredioEquidosFS').show();
				$("#nuevaEspecieCatastroPredioEquidos").show();
					$('#detalleEspecieCatastroPredioEquidosConsultaFS').hide();
					$('#detalleEspecieCatastroPredioEquidosFS').show();
				$("#nuevaBioseguridadCatastroPredioEquidos").show();
					$('#detalleBioseguridadCatastroPredioEquidosConsultaFS').hide();
					$('#detalleBioseguridadCatastroPredioEquidosFS').show();
				$("#nuevaSanidadCatastroPredioEquidos").show();
					$('#detalleSanidadCatastroPredioEquidosConsultaFS').hide();
					$('#detalleSanidadCatastroPredioEquidosFS').show();
				$("#nuevoHistorialPatologiasCatastroPredioEquidos").show();
					$('#detalleHistorialPatologiasCatastroPredioEquidosConsultaFS').hide();
					$('#detalleHistorialPatologiasCatastroPredioEquidosFS').show();
				$('#cerrarCatastroPredioEquidos').hide();
				$('#cerrarCatastroPredioEquidosCerrado').hide();
			}else{
				$("#modificar").hide();
				$("#nuevoMotivoCatastroPredioEquidos").hide();
					$('#detalleMotivoCatastroPredioEquidosConsultaFS').show();
					$('#detalleMotivoCatastroPredioEquidosFS').hide();
				$("#nuevoTipoActividadCatastroPredioEquidos").hide();
					$('#detalleTipoActividadCatastroPredioEquidosConsultaFS').show();
					$('#detalleTipoActividadCatastroPredioEquidosFS').hide();
				$("#nuevaEspecieCatastroPredioEquidos").hide();
					$('#detalleEspecieCatastroPredioEquidosConsultaFS').show();
					$('#detalleEspecieCatastroPredioEquidosFS').hide();
				$("#nuevaBioseguridadCatastroPredioEquidos").hide();
					$('#detalleBioseguridadCatastroPredioEquidosConsultaFS').show();
					$('#detalleBioseguridadCatastroPredioEquidosFS').hide();
				$("#nuevaSanidadCatastroPredioEquidos").hide();
					$('#detalleSanidadCatastroPredioEquidosConsultaFS').show();
					$('#detalleSanidadCatastroPredioEquidosFS').hide();
				$("#nuevoHistorialPatologiasCatastroPredioEquidos").hide();
					$('#detalleHistorialPatologiasCatastroPredioEquidosConsultaFS').show();
					$('#detalleHistorialPatologiasCatastroPredioEquidosFS').hide();
				$('#cerrarCatastroPredioEquidos').hide();
				$('#cerrarCatastroPredioEquidosCerrado').show();
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
			
			$("#lDestinoAnimalesPositivosTuberculina").show();
			$("#destinoAnimalesPositivosTuberculina").show();
    		$("#destinoAnimalesPositivosTuberculina").attr('required','required');
    		$("#destinoAnimalesPositivosTuberculina").val('');
    		$("#nombreDestinoAnimalesPositivosTuberculina").val('');
    		
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
			
			$("#lDestinoAnimalesPositivosTuberculina").hide();
			$("#destinoAnimalesPositivosTuberculina").hide();
    		$("#destinoAnimalesPositivosTuberculina").removeAttr('required');
    		$("#destinoAnimalesPositivosTuberculina").val('0');
    		$("#nombreDestinoAnimalesPositivosTuberculina").val('No Aplica');
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
    	}
	});

	$("#laboratorioTuberculina").change(function(){		
        if($("#laboratorioTuberculina option:selected").val()!='0'){
        	$('#nombreLaboratorioTuberculina').hide();
    		$("#nombreLaboratorioTuberculina").val($("#laboratorioTuberculina option:selected").text());
        }else{
            alert();
        	$("#nombreLaboratorioTuberculina").val('');
    	    $('#nombreLaboratorioTuberculina').show();
        }
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





    $("#resultado").change(function(){
        if($("#resultado option:selected").val()=='plantaCentral'){
        	$("#lFechaInspeccion").hide();
        	$("#fechaInspeccion").hide();
        	$("#fechaInspeccion").removeAttr('required');
        }else{
        	$("#lFechaInspeccion").show();
        	$("#fechaInspeccion").show();
        	$("#fechaInspeccion").attr('required','required');
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

		if($("#resultado option:selected").val()!='plantaCentral'){
			if(!$.trim($("#fechaInspeccion").val())){
				error = true;
				$("#fechaInspeccion").addClass("alertaCombo");
			}
		}
		
		if(!$.trim($("#observaciones").val()) || !esCampoValido("#observaciones")){
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
</script>