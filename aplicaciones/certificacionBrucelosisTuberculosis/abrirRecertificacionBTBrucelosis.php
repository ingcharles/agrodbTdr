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
	
	$idRecertificacionBT = $_POST['id'];
	$certificacionBT = pg_fetch_assoc($cbt->abrirRecertificacionBT($conexion, $idRecertificacionBT));
	
	$informacionPredio = $cbt->abrirInformacionPredioRecertificacionBT($conexion, $idRecertificacionBT);
	$informacionPredioConsulta = $cbt->abrirInformacionPredioRecertificacionBT($conexion, $idRecertificacionBT);
	
	$inventario = $cbt->abrirInventarioAnimalRecertificacionBT($conexion, $idRecertificacionBT);
	$inventarioConsulta = $cbt->abrirInventarioAnimalRecertificacionBT($conexion, $idRecertificacionBT);
	
	$manejoAnimal = $cbt->abrirManejoAnimalRecertificacionBT($conexion, $idRecertificacionBT);
	$manejoAnimalConsulta = $cbt->abrirManejoAnimalRecertificacionBT($conexion, $idRecertificacionBT);
	
	$adquisicionAnimales = $cbt->abrirAdquisicionAnimalesRecertificacionBT($conexion, $idRecertificacionBT);
	$adquisicionAnimalesConsulta = $cbt->abrirAdquisicionAnimalesRecertificacionBT($conexion, $idRecertificacionBT);
	
	$veterinario = $cbt->abrirVeterinarioRecertificacionBT($conexion, $idRecertificacionBT);
	$veterinarioConsulta = $cbt->abrirVeterinarioRecertificacionBT($conexion, $idRecertificacionBT);
	
	$vacunacion = $cbt->abrirVacunacionRecertificacionBT($conexion, $idRecertificacionBT);
	$vacunacionConsulta = $cbt->abrirVacunacionRecertificacionBT($conexion, $idRecertificacionBT);
	
	$patologia = $cbt->abrirPatologiaBrucelosisRecertificacionBT($conexion, $idRecertificacionBT);
	$patologiaConsulta = $cbt->abrirPatologiaBrucelosisRecertificacionBT($conexion, $idRecertificacionBT);
	
	
	
?>

<header>
	<h1>Predios para Recertificación como Libres de Brucelosis Bovina</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>

<div class="pestania">
	<h2>Identificación y Localización del Predio</h2>

<form id="modificarRecertificacionBT" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="modificarRecertificacionBT" data-destino="detalleItem" >
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
			
			<div data-linea="0">
				<label>N°: </label>
				<?php echo $certificacionBT['num_recertificacion'];?>
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
		
			<div data-linea="44" id="lFechaMuestreoBrucelosisD">
				<label>Fecha de último muestreo de Brucelosis:</label>
				<?php echo $certificacionBT['fecha_muestreo_brucelosis'];?>	
			</div>
			
			<div data-linea="45" id="lFechaTuberculinizacionD">
				<label >Fecha de última Tuberculinización:</label>
				<?php echo $certificacionBT['fecha_tuberculinizacion'];?>	
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
		
		<fieldset>
			<legend>Información del Técnico Responsable</legend>			
			
			<div data-linea="14">
				<label>Técnico Responsable (externo):</label>
				<?php echo $certificacionBT['nombre_tecnico_responsable'];?>
			</div>
		</fieldset>
		
	</div>
	
	<div id="actualizacion">
		<input type='hidden' id='idRecertificacionBT' name='idRecertificacionBT' value="<?php echo $idRecertificacionBT;?>" />
		<input type='hidden' id='certificacion' name='certificacion' value="<?php echo $certificacionBT['certificacion_bt'];?>" />
		<input type='hidden' id='estado' name='estado' value="<?php echo $certificacionBT['estado'];?>" />	
				
			<fieldset>
				<legend>Información de Localización del Predio</legend>
		
				<div data-linea="10">
					<label>N° Solicitud:</label>
					<?php echo $certificacionBT['num_solicitud'];?>
				</div>
				
				<div data-linea="10">
					<label>N°: </label>
					<?php echo $certificacionBT['num_recertificacion'];?>
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
					<?php echo $certificacionBT['numero_certificado_fiebre_aftosa'];?>
				</div>
				
				<div data-linea="13">
					<label>Certificación:</label>
					<?php echo $certificacionBT['certificacion_bt'];?>	
				</div>
				
				<div data-linea="54">
					<label id="lFechaMuestreoBrucelosis">Fecha de último muestreo de Brucelosis:</label>
					<input type="text" id="fechaMuestreoBrucelosis" name="fechaMuestreoBrucelosis" value="<?php echo date('j/n/Y',strtotime($certificacionBT['fecha_muestreo_brucelosis']));?>"/>
				</div>
				
				<div data-linea="55">
					<label id="lFechaTuberculinizacion">Fecha de última Tuberculinización:</label>
					<input type="text" id="fechaTuberculinizacion" name="fechaTuberculinizacion" value="<?php echo date('j/n/Y',strtotime($certificacionBT['fecha_tuberculinizacion']));?>"/>
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
					<?php echo $certificacionBT['telefono_propietario'];?>
				</div>
				
				
				<div data-linea="15">
					<label>Celular:</label>
					<?php echo $certificacionBT['celular_propietario'];?>
				</div>
				
				<div data-linea="16">
					<label>Correo Electrónico:</label>
					<?php echo $certificacionBT['correo_electronico_propietario'];?>
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
					<?php echo $certificacionBT['utm_x'];?>
				</div>
				
				<div data-linea="19">
					<label>Y:</label>
					<?php echo $certificacionBT['utm_y'];?>
				</div>
				
				<div data-linea="19">
					<label>Z:</label>
					<?php echo $certificacionBT['utm_z'];?>
				</div>
				
				<div data-linea="19">
					<label>Huso/Zona:</label>
					<?php echo $certificacionBT['huso_zona'];?>
				</div>
		
			</fieldset>		
			
			<fieldset>
				<legend>Información del Técnico Responsable</legend>			
				
				<div data-linea="14">
					<label>Técnico Responsable (externo):</label>
					<?php echo $certificacionBT['nombre_tecnico_responsable'];?>
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
		</fieldset>
			
		<fieldset id="adjuntosInforme">
			<legend>Informe</legend>
	
				<div data-linea="1">
					<label>Informe:</label>
					<?php echo ($certificacionBT['ruta_informe']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$certificacionBT['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe cargado</a>')?>
				</div>
				
				<form id="subirArchivoInforme" action="aplicaciones/certificacionBrucelosisTuberculosis/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergenteInforme" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
					
					<input type="file" name="archivo" id="archivoInforme" accept="application/pdf" /> 
					<input type="hidden" name="id" value="<?php echo $certificacionBT['id_recertificacion_bt'];?>" />
					<input type="hidden" name="aplicacion" value="InformeRecertificacionBT" /> 
					
					<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
				</form>
				<iframe name="ventanaEmergenteInforme" class="ventanaEmergente"></iframe>
		</fieldset>		
				
</div>

<div class="pestania">

	<h2>Datos Generales del Predio</h2>

	<form id="nuevaInformacionPredio" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarRecertificacionDatosGenerales" data-destino="detalleItem">
		<input type='hidden' id='idRecertificacionBT' name='idRecertificacionBT' value="<?php echo $idRecertificacionBT;?>" />
		<input type='hidden' id='certificacion' name='certificacion' value="<?php echo $certificacionBT['certificacion_bt'];?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
	
		<fieldset>
			<legend>Información del Predio</legend>
			
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
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Cerramientos</th>				    
				    <th width="15%">Control ingreso Personas</th>
				    <th width="15%">Control ingreso Animales</th>
				    <th width="15%">Identificación ind Bovinos</th>
				    <th width="15%">Manga, embudo, brete</th>				    
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php //cambiar
				while ($infoPredio = pg_fetch_assoc($informacionPredio)){
					echo $cbt->imprimirLineaInformacionPredioRecertificacionBT($infoPredio['id_recertificacion_bt_informacion_predio'],
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
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Cerramientos</th>				    
				    <th width="15%">Control ingreso Personas</th>
				    <th width="15%">Control ingreso Animales</th>
				    <th width="15%">Identificación ind Bovinos</th>
				    <th width="15%">Manga, embudo, brete</th>
				</tr>
			</thead>
			<?php 
				while ($infoPredio = pg_fetch_assoc($informacionPredioConsulta)){
					echo $cbt->imprimirLineaInformacionPredioRecertificacionBTConsulta($infoPredio['id_recertificacion_bt_informacion_predio'],
												$infoPredio['cerramientos'], $infoPredio['control_ingreso_personas'],
												$infoPredio['manga_embudo_brete'], $infoPredio['identificacion_bovinos'], 
												$infoPredio['control_ingreso_animal'], $ruta, $infoPredio['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<form id="nuevoInventarioAnimal" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarInventarioAnimalRecertificacion" data-destino="detalleItem">
		<input type='hidden' id='idRecertificacionBT' name='idRecertificacionBT' value="<?php echo $idRecertificacionBT;?>" />
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
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Animales Predio</th>
				    <th width="15%">Número existencias</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php //cambiar
				while ($infoInventario = pg_fetch_assoc($inventario)){
					echo $cbt->imprimirLineaInventarioAnimalRecertificacionBT($infoInventario['id_recertificacion_bt_inventario_animal'],
															$infoInventario['animales_predio'], $infoInventario['numero_animales_predio'], $ruta, $infoInventario['num_inspeccion']);												
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleInventarioAnimalConsultaFS">
		<legend>Inventario de Animales en el Predio Registrados</legend>
		<table id="detalleInventarioAnimalConsulta">
			<thead>
				<tr>
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Animales Predio</th>
				    <th width="15%">Número existencias</th>
				</tr>
			</thead>
			<?php //cambiar
				while ($infoInventario = pg_fetch_assoc($inventarioConsulta)){
					echo $cbt->imprimirLineaInventarioPredioRecertificacionBTConsulta($infoInventario['id_recertificacion_bt_inventario_animal'],
															$infoInventario['animales_predio'], $infoInventario['numero_animales_predio'], $ruta, $infoInventario['num_inspeccion']);												
				}
			?>
		</table>
	</fieldset>
	
</div>

<div class="pestania">

	<h2>Manejo General de Animales y Potreros</h2>

	<form id="nuevoManejoAnimalesPotreros" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarManejoAnimalesPotrerosRecertificacion" data-destino="detalleItem">
		<input type='hidden' id='idRecertificacionBT' name='idRecertificacionBT' value="<?php echo $idRecertificacionBT;?>" />
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
			
			<div data-linea="35">
				<label>Todos los animales del predio están dentro del programa de certificación:</label>
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
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Pastos Comunales</th>
				    <th width="15%">Arrienda sus Potreros</th>
				    <th width="15%">Arrienda otros Potreros</th>
				    <th width="15%">Lleva animales a ferias</th>
				    <th width="15%">Desinfecta animales</th>
				    <th width="15%">Están en Programa Certificación</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php //cambiar
				while ($infoManejoAnimal = pg_fetch_assoc($manejoAnimal)){
					echo $cbt->imprimirLineaManejoAnimalesPotrerosRecertificacionBT($infoManejoAnimal['id_recertificacion_bt_manejo_animales_potreros'], 
																		$infoManejoAnimal['pastos_comunales'], 
																		$infoManejoAnimal['arrienda_potreros'], 
																		$infoManejoAnimal['arrienda_otros_potreros'],
																		$infoManejoAnimal['animales_ferias'], 
																		$infoManejoAnimal['desinfecta_animales'],  
																		$infoManejoAnimal['dentro_programa_predios_libres'], $ruta, $infoManejoAnimal['num_inspeccion']);												
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleManejoAnimalesPotrerosConsultaFS">
		<legend>Manejo de Animales y Potreros Registrados</legend>
		<table id="detalleManejoAnimalesPotrerosConsulta">
			<thead>
				<tr>
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Pastos Comunales</th>
				    <th width="15%">Arrienda sus Potreros</th>
				    <th width="15%">Arrienda otros Potreros</th>
				    <th width="15%">Lleva animales a ferias</th>
				    <th width="15%">Desinfecta animales</th>
				    <th width="15%">Están en Programa Certificación</th>
				</tr>
			</thead>
			<?php //cambiar
			while ($infoManejoAnimal = pg_fetch_assoc($manejoAnimalConsulta)){
					echo $cbt->imprimirLineaManejoAnimalesPotrerosRecertificacionBTConsulta($infoManejoAnimal['id_recertificacion_bt_manejo_animales_potreros'], 
																		$infoManejoAnimal['pastos_comunales'], 
																		$infoManejoAnimal['arrienda_potreros'], 
																		$infoManejoAnimal['arrienda_otros_potreros'],
																		$infoManejoAnimal['animales_ferias'], 
																		$infoManejoAnimal['desinfecta_animales'],  
																		$infoManejoAnimal['dentro_programa_predios_libres'], $ruta, $infoManejoAnimal['num_inspeccion']);												
				}
			?>
		</table>
	</fieldset>
	
	<form id="nuevaAdquisicionAnimales" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarAdquisicionAnimalesRecertificacion" data-destino="detalleItem">
		<input type='hidden' id='idRecertificacionBT' name='idRecertificacionBT' value="<?php echo $idRecertificacionBT;?>" />
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
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Procedencia Animales</th>
				    <th width="15%">Categoría</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php //cambio
				while ($infoAdquisicionAnimales = pg_fetch_assoc($adquisicionAnimales)){
					echo $cbt->imprimirLineaAdquisicionAnimalesRecertificacionBT($infoAdquisicionAnimales['id_recertificacion_bt_adquisicion_animales'], 
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
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Procedencia Animales</th>
				    <th width="15%">Categoría</th>
				</tr>
			</thead>
			<?php //cambio
				while ($infoAdquisicionAnimales = pg_fetch_assoc($adquisicionAnimalesConsulta)){
					echo $cbt->imprimirLineaAdquisicionAnimalesRecertificacionBTConsulta($infoAdquisicionAnimales['id_recertificacion_bt_adquisicion_animales'], 
														$infoAdquisicionAnimales['procedencia_animales'], 
														$infoAdquisicionAnimales['categoria_animales_adquiriente'], 
														$ruta, $infoAdquisicionAnimales['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
</div>

<div class="pestania">

	<h2>Aspectos Sanitarios</h2>
	<h3>I. Veterinario</h3>

	<form id="nuevoVeterinario" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarVeterinarioRecertificacion" data-destino="detalleItem">
		<input type='hidden' id='idRecertificacionBT' name='idRecertificacionBT' value="<?php echo $idRecertificacionBT;?>" />
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
				    <th width="15%">Num. Inspección</th>
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
					echo $cbt->imprimirLineaVeterinarioRecertificacionBT($infoVeterinario['id_recertificacion_bt_veterinario'], 
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
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Nombre</th>
				    <th width="15%">Teléfono</th>
				    <th width="15%">Celular</th>
				    <th width="15%">Correo Electrónico</th>
				    <th width="15%">Frecuencia Visita</th>
				</tr>
			</thead>
			<?php 
				while ($infoVeterinario = pg_fetch_assoc($veterinarioConsulta)){
					echo $cbt->imprimirLineaVeterinarioRecertificacionBTConsulta($infoVeterinario['id_recertificacion_bt_veterinario'], 
											$infoVeterinario['nombre_veterinario'], $infoVeterinario['telefono_veterinario'], 
											$infoVeterinario['celular_veterinario'], $infoVeterinario['correo_electronico_veterinario'], 
											$infoVeterinario['frecuencia_visita_veterinario'], $ruta, $infoVeterinario['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<h3>II. Vacunación</h3>

	<form id="nuevaInformacionVacunacion" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarInformacionVacunacionRecertificacion" data-destino="detalleItem">
		<input type='hidden' id='idRecertificacionBT' name='idRecertificacionBT' value="<?php echo $idRecertificacionBT;?>" />
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
				<label id="lFechaVacunacion">Fecha Vacunación:</label>
				<input type="text" id="fechaVacunacion" name="fechaVacunacion" required="required" />
			</div>
				
			<div data-linea="41">
				<label id="lLoteVacuna">Lote Vacuna Utilizada:</label>
				<input type="text" id="loteVacuna" name="loteVacuna" required="required" />
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
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Calendario Vacunación</th>
				    <th width="15%">Motivo Vacunación</th>
					<th width="15%">Vacuna Aplicada</th>
					<th width="10%">Lote Vacuna</th>
					<th width="10%">Fecha Vacunación</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($infoVacunacion = pg_fetch_assoc($vacunacion)){
					echo $cbt->imprimirLineaInformacionVacunacionRecertificacionBT($infoVacunacion['id_certificacion_bt_informacion_vacunacion'], 
																	$infoVacunacion['motivo_vacunacion'], $infoVacunacion['vacunas_aplicadas'], 
																	$infoVacunacion['lote_vacuna'], $infoVacunacion['fecha_vacunacion'], 
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
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Calendario Vacunación</th>
				    <th width="15%">Motivo Vacunación</th>
					<th width="15%">Vacuna Aplicada</th>
					<th width="10%">Lote Vacuna</th>
					<th width="10%">Fecha Vacunación</th>
				</tr>
			</thead>
			<?php 
				while ($infoVacunacion = pg_fetch_assoc($vacunacionConsulta)){
					echo $cbt->imprimirLineaInformacionVacunacionRecertificacionBTConsulta($infoVacunacion['id_certificacion_bt_informacion_vacunacion'], 
																	$infoVacunacion['motivo_vacunacion'], $infoVacunacion['vacunas_aplicadas'], 
																	$infoVacunacion['lote_vacuna'], $infoVacunacion['fecha_vacunacion'], 
																	$ruta, $infoVacunacion['num_inspeccion'], $infoVacunacion['calendario_vacunacion']);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Aspectos Sanitarios</h2>
	<h3>III. Patologías</h3>

	<form id="nuevaPatologiaBrucelosis" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPatologiaBrucelosisRecertificacion" data-destino="detalleItem">
		<input type='hidden' id='idRecertificacionBT' name='idRecertificacionBT' value="<?php echo $idRecertificacionBT;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		
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
				<label>Metritis Post-Parto:</label>
				<select id="metritisPostParto" name="metritisPostParto" required="required" >
						<option value="">Metritis Post-Parto....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
			</div>
			
			<div data-linea="46">
				<label>Se han producido abortos :</label>
					<select id="abortos" name="abortos" required="required" >
						<option value="">Abortos....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
									
			</div>
			
			<div data-linea="46">
				<label>Presencia fiebre Fluctuante:</label>
				<select id="fiebreBovinos" name="fiebreBovinos" required="required" >
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
	
	<fieldset id="detallePatologiaBrucelosisFS">
		<legend>Patologías Registradas</legend>
		<table id="detallePatologiaBrucelosis">
			<thead>
				<tr>
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Retención Placenta</th>
				    <th width="15%">Nacimiento Terneros Débiles</th>
				    <th width="15%">Metritis Post-Parto</th>
				    <th width="15%">Abortos</th>
				    <th width="15%">Fiebre Fluctuante</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php //cambiar
				while ($infoPatologia = pg_fetch_assoc($patologia)){
					echo $cbt->imprimirLineaPatologiaBrucelosisRecertificacionBT($infoPatologia['id_recertificacion_bt_patologia_brucelosis'], 
															$infoPatologia['retencion_placenta'], $infoPatologia['nacimiento_terneros_debiles'], 
															$infoPatologia['metritis_post_parto'], 
															$infoPatologia['abortos'], $infoPatologia['fiebre'], 
															$ruta, $infoPatologia['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePatologiaBrucelosisConsultaFS">
		<legend>Patologías Registradas</legend>
		<table id="detallePatologiaBrucelosisConsulta">
			<thead>
				<tr>
				    <th width="15%">Num. Inspección</th>
				    <th width="15%">Retención Placenta</th>
				    <th width="15%">Nacimiento Terneros Débiles</th>
				    <th width="15%">Metritis Post-Parto</th>
				    <th width="15%">Abortos</th>
				    <th width="15%">Fiebre Fluctuante</th>
				</tr>
			</thead>
			<?php //cambiar
				while ($infoPatologia = pg_fetch_assoc($patologiaConsulta)){
					echo $cbt->imprimirLineaPatologiaBrucelosisRecertificacionBTConsulta($infoPatologia['id_recertificacion_bt_patologia_brucelosis'], 
															$infoPatologia['retencion_placenta'], $infoPatologia['nacimiento_terneros_debiles'], 
															$infoPatologia['metritis_post_parto'], 
															$infoPatologia['abortos'], $infoPatologia['fiebre'], 
															$ruta, $infoPatologia['num_inspeccion']);
				}
			?>
		</table>
	</fieldset>
</div>	

<div class="pestania">

	<h2>Finalizar Registro</h2>

	<form id="cerrarCertificacionBT" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarCierreRecertificacionBTTecnico" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type='hidden' id='idRecertificacionBT' name='idRecertificacionBT' value="<?php echo $idRecertificacionBT;?>" />
                <input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $certificacionBT['id_certificacion_bt'];?>" />
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
						<option value="">Seleccione....</option>
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
var certificacion= <?php echo json_encode($certificacionBT['certificacion_bt']); ?>;

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

		$("#fechaMuestreoBrucelosis").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		$("#fechaTuberculinizacion").datepicker({
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
		$('#lLaboratorioMuestras').hide();
		$('#laboratorioMuestras').hide();

		$("#lMotivoVacunacion").hide();
		$("#motivoVacunacion").hide();
		$("#lVacunasAplicadas").hide();
		$("#vacunasAplicadas").hide();
		$("#lLoteVacuna").hide();
		$("#loteVacuna").hide();
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

		$("#lFechaTuberculinizacion").hide();
		$("#fechaTuberculinizacion").hide();
		$("#lFechaMuestreoBrucelosis").hide();
		$("#fechaMuestreoBrucelosis").hide();

		if(certificacion == "Brucelosis"){
			$("#lFechaMuestreoBrucelosis").show();
			$("#fechaMuestreoBrucelosis").show();
			$("#fechaMuestreoBrucelosis").attr('required', 'required');
			$("#lFechaMuestreoBrucelosisD").show();
			
			$("#lFechaTuberculinizacion").hide();
			$("#fechaTuberculinizacion").hide();
			$("#lFechaTuberculinizacionD").hide();
		}else{
			$("#lFechaMuestreoBrucelosis").hide();
			$("#fechaMuestreoBrucelosis").hide();
			$("#lFechaMuestreoBrucelosisD").hide();
			
			$("#lFechaTuberculinizacion").show();
			$("#fechaTuberculinizacion").show();
			$("#fechaTuberculinizacion").attr('required', 'required');
			$("#lFechaTuberculinizacionD").show();
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

	$("#modificarRecertificacionBT").submit(function(event){

		$("#modificarRecertificacionBT").attr('data-opcion', 'modificarRecertificacionBT');
	    $("#modificarRecertificacionBT").attr('data-destino', 'detalleItem');

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
    		
			$("#lLoteVacuna").show();
			$("#loteVacuna").show();
			$("#loteVacuna").attr('required','required');
    		$("#loteVacuna").val('');
    		
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

    		$("#lLoteVacuna").hide();
    		$("#loteVacuna").hide();
    		$("#loteVacuna").removeAttr('required');
    		$("#loteVacuna").val('No Aplica');

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

    		$("#lDestinoAnimalesPositivos").hide();
    		$("#destinoAnimalesPositivos").val('');   		
    		
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

    		$("#lDestinoAnimalesPositivos").hide();
			$("#destinoAnimalesPositivos").hide();
			$("#destinoAnimalesPositivos").removeAttr('required');
			$("#destinoAnimalesPositivos").val('0');
			$("#nombreDestinoAnimalesPositivos").hide();
    		$("#nombreDestinoAnimalesPositivos").val('No Aplica');
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

		$("#cerrarCertificacionBT").attr('data-opcion', 'guardarCierreRecertificacionBTTecnico');
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

    $("#trabajadoresAnimalesPredio").change(function(){
        if($("#trabajadoresAnimalesPredio option:selected").val()=='No Aplica'){        	
        	cargarValorDefecto("programaPrediosLibres","No Aplica");
        }    	
	});
</script>