<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorProgramasControlOficial.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	$cpco = new ControladorProgramasControlOficial();
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Programas Control Oficial Inspección Ovinos, Caprinos y Camélidos Sudamericanos'),0,'id_perfil');
	}//$usuario=0;
	
	$ruta = 'programasControlOficial';
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	
	$razaOvinos = array('Ramboulliet', 'Corriedale', 'Poll Dorset', 'Sufolk', 'Mestiza');
	$razaCaprinos = array('Alpina', 'Saanen', 'Nubiana', 'Toggenburg', 'Mestiza');
	$razaCamelidos = array('Sin raza');
	$categoriaOvinos = array('Cordero', 'Cordera', 'Maltón', 'Maltona', 'Borrego', 'Borrega');
	$categoriaCaprinos = array('Cabrito', 'Cabrita', 'Maltón', 'Maltona', 'Cabro', 'Cabra');
	$categoriaCamelidos = array('Alpaca', 'Llama', 'Guanaco', 'Huarizo', 'Misty', 'Vicuña');
	
	$idInspeccionOCCS = $_POST['id'];
	$inspeccionOCCS = pg_fetch_assoc($cpco->abrirInspeccionOCCS($conexion, $idInspeccionOCCS));
	
	$tiposExplotacionInspeccionOCCS = $cpco->listarTipoExplotacionInspeccionOCCS($conexion, $idInspeccionOCCS);
	$tiposExplotacionInspeccionOCCSConsulta = $cpco->listarTipoExplotacionInspeccionOCCS($conexion, $idInspeccionOCCS);
	
	$especieInspeccionOCCS = $cpco->listarEspecieInspeccionOCCS($conexion, $idInspeccionOCCS);
	$especieInspeccionOCCSConsulta = $cpco->listarEspecieInspeccionOCCS($conexion, $idInspeccionOCCS);
	
	$bioseguridadInspeccionOCCS = $cpco->listarBioseguridadInspeccionOCCS($conexion, $idInspeccionOCCS);
	$bioseguridadInspeccionOCCSConsulta = $cpco->listarBioseguridadInspeccionOCCS($conexion, $idInspeccionOCCS);
	
	$enfermedadInspeccionOCCS = $cpco->listarHistorialPatologiasInspeccionOCCS($conexion, $idInspeccionOCCS);
	$enfermedadInspeccionOCCSConsulta = $cpco->listarHistorialPatologiasInspeccionOCCS($conexion, $idInspeccionOCCS);
	
?>

<header>
	<h1>Catastro de Explotaciones Ovinas, Caprinas y de Camélidos Sudamericanos</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>

<div class="pestania">
	<h2>Información del Predio</h2>

	<form id="modificarInspeccionOCCS" data-rutaAplicacion="programasControlOficial" data-opcion="modificarInspeccionOCCS" data-destino="detalleItem">
		
	
		<p>
			<button id="modificar" type="button" class="editar">Modificar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</p>
		
	<div id="informacion">
	
		<fieldset>
			<legend>Información de Identificación del Predio</legend>
	
			<div data-linea="0">
				<label>N° Solicitud:</label>
				<?php echo $inspeccionOCCS['num_solicitud'];?>
			</div>
			
			<div data-linea="1">
				<label>Fecha:</label>
				<?php echo date('j/n/Y',strtotime($inspeccionOCCS['fecha']));?>
			</div>
			
			<div data-linea="2">
				<label>Nombre del Predio:</label>
				<?php echo $inspeccionOCCS['nombre_predio'];?>
			</div>
			
			<div data-linea="2">
				<label>Nombre del Propietario:</label>
				<?php echo $inspeccionOCCS['nombre_propietario'];?>
			</div>
			
			<div data-linea="3">
				<label>Cédula del Propietario:</label>
				<?php echo $inspeccionOCCS['cedula_propietario'];?>
			</div>
			
			<div data-linea="3">
				<label>Teléfono:</label>
				<?php echo $inspeccionOCCS['telefono'];?>
			</div>
			
			<div data-linea="4">
				<label>Correo Electrónico:</label>
				<?php echo $inspeccionOCCS['correo_electronico'];?>
			</div>
			
			<div data-linea="4">
				<label>Nombre Asociación:</label>
				<?php echo $inspeccionOCCS['nombre_asociacion'];?>
			</div>
	
		</fieldset>
		
		<fieldset>
			<legend>Ubicación y Datos Generales</legend>
	
			<div data-linea="5">
				<label>Provincia</label>
				<?php echo $inspeccionOCCS['provincia'];?>	
			</div>
				
			<div data-linea="5">
				<label>Cantón</label>
					<?php echo $inspeccionOCCS['canton'];?>
				</div>
				
			<div data-linea="6">	
				<label>Parroquia</label>
					<?php echo $inspeccionOCCS['parroquia'];?>
			</div>
					
			<div data-linea="6">
				<label>Sector:</label>
				<?php echo $inspeccionOCCS['sector'];?>
			</div>
			
		</fieldset>
		
		<fieldset>
			<legend>Coordenadas</legend>
			
			<div data-linea="7">
				<label>X:</label>
				<?php echo $inspeccionOCCS['utm_x'];?>
			</div>
			
			<div data-linea="7">
				<label>Y:</label>
				<?php echo $inspeccionOCCS['utm_y'];?>
			</div>
			
			<div data-linea="7">
				<label>Z:</label>
				<?php echo $inspeccionOCCS['utm_z'];?>
			</div>
			
			<!--div data-linea="8">
				<label>Altitud:</label>
				< ?php echo $inspeccionOCCS['altitud'];?>
			</div-->
	
		</fieldset>			

	</div>
	
	<div id="actualizacion">
			<input type='hidden' id='idInspeccionOCCS' name='idInspeccionOCCS' value="<?php echo $idInspeccionOCCS;?>" />	
		
			<fieldset>
				<legend>Información de Identificación del Predio</legend>
		
				<div data-linea="10">
					<label>N° Solicitud:</label>
					<?php echo $inspeccionOCCS['num_solicitud'];?>
				</div>
				
				<div data-linea="11">
					<label>Fecha:</label>
					<input type="text" id="fecha" name="fecha" value="<?php echo date('j/n/Y',strtotime($inspeccionOCCS['fecha']));?>" />
				</div>
				
				<div data-linea="12">
					<label>Nombre del Predio:</label>
					<input type="text" id="nombrePredio" name="nombrePredio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $inspeccionOCCS['nombre_predio'];?>" />
				</div>
				
				<div data-linea="12">
					<label>Nombre del Propietario:</label>
					<input type="text" id="nombrePropietario" name="nombrePropietario" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $inspeccionOCCS['nombre_propietario'];?>" />
				</div>
						
				<div data-linea="13">
					<label>Cédula del Propietario:</label>
					<input type="text" id="cedulaPropietario" name="cedulaPropietario" maxlength="13" data-er="^[0-9]+$" value="<?php echo $inspeccionOCCS['cedula_propietario'];?>" />
				</div>
		
				<div data-linea="13">
					<label>Teléfono:</label>
					<input type="text" id="telefono" name="telefono" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15"  value="<?php echo $inspeccionOCCS['telefono'];?>" />
				</div>
		
				<div data-linea="14">
					<label>Correo Electrónico:</label>
					<input type="text" id="correoElectronico" name="correoElectronico" maxlength="32" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" value="<?php echo $inspeccionOCCS['correo_electronico'];?>" />
				</div>
				
				<div data-linea="14">
					<label>Nombre Asociación:</label>
					<input type="text" id="nombreAsociacion" name="nombreAsociacion" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $inspeccionOCCS['nombre_asociacion'];?>" />
				</div>
		
			</fieldset>
			
			<fieldset>
				<legend>Ubicación y Datos Generales</legend>
		
				<div data-linea="15">
					<label>Provincia</label>
					<?php echo $inspeccionOCCS['provincia'];?>	
				</div>
					
				<div data-linea="15">
					<label>Cantón</label>
						<?php echo $inspeccionOCCS['canton'];?>
					</div>
					
				<div data-linea="16">	
					<label>Parroquia</label>
						<?php echo $inspeccionOCCS['parroquia'];?>
				</div>
						
				<div data-linea="16">
					<label>Sector:</label>
					<input type="text" id="sector" name="sector" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $inspeccionOCCS['sector'];?>"/>
				</div>
				
			</fieldset>
			
			<fieldset>
				<legend>Coordenadas</legend>
				
		
				<div data-linea="17">
					<label>X:</label>
					<input type="text" id="x" name="x" maxlength="6" data-er="^[0-9]+$" value="<?php echo $inspeccionOCCS['utm_x'];?>"/>
				</div>
				
				<div data-linea="17">
					<label>Y:</label>
					<input type="text" id="y" name="y" maxlength="7" data-er="^[0-9]+$" value="<?php echo $inspeccionOCCS['utm_y'];?>"/>
				</div>
				
				<div data-linea="17">
					<label>Z:</label>
					<input type="text" id="z" name="z" maxlength="4" data-er="^[0-9]+$" value="<?php echo $inspeccionOCCS['utm_z'];?>"/>
				</div>
				
				<!--div data-linea="18">
					<label>Altitud:</label>
					<input type="text" id="altitud" name="altitud" maxlength="16" data-er="^[0-9.]+$" value="< ?php echo $inspeccionOCCS['altitud'];?>"/>
				</div-->
		
			</fieldset>	
		
		</div>
	</form>
	
	<fieldset id="adjuntos">
			<legend>Mapa de Ubicación</legend>
	
				<div data-linea="1">
					<label>Mapa:</label>
					<?php echo ($inspeccionOCCS['imagen_mapa']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$inspeccionOCCS['imagen_mapa'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Mapa cargado</a>')?>
				</div>
				
				<form id="subirArchivo" action="aplicaciones/programasControlOficial/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
					
					<input type="file" name="archivo" id="archivo" accept="application/pdf" /> 
					<input type="hidden" name="id" value="<?php echo $inspeccionOCCS['id_inspeccion_occs'];?>" />
					<input type="hidden" name="aplicacion" value="InspeccionOCCS" /> 
					
					<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
				</form>
				<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
		</fieldset>
		
		<fieldset id="adjuntosInforme">
			<legend>Informe</legend>
	
				<div data-linea="1">
					<label>Informe:</label>
					<?php echo ($inspeccionOCCS['ruta_informe']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$inspeccionOCCS['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe cargado</a>')?>
				</div>
				
				<form id="subirArchivoInforme" action="aplicaciones/programasControlOficial/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergenteInforme" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
					
					<input type="file" name="archivo" id="archivoInforme" accept="application/pdf" /> 
					<input type="hidden" name="id" value="<?php echo $inspeccionOCCS['id_inspeccion_occs'];?>" />
					<input type="hidden" name="aplicacion" value="InformeInspeccionOCCS" /> 
					
					<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
				</form>
				
				<iframe name="ventanaEmergenteInforme" class="ventanaEmergente"></iframe>
		</fieldset>
</div>

<div class="pestania">

	<h2>Tipo de Explotación</h2>

	<form id="nuevaExplotacionInspeccionOCCS" data-rutaAplicacion="programasControlOficial" data-opcion="guardarExplotacionInspeccionOCCS" data-destino="detalleItem">
		<input type='hidden' id='idInspeccionOCCS' name='idInspeccionOCCS' value="<?php echo $idInspeccionOCCS;?>" />
		
		<fieldset>
			<legend>Tipo de Explotación Realizada</legend>
			
			<div data-linea="21">
				<label>Tipo de Explotación:</label>
					<select id="explotacion" name="explotacion" required="required" >
						<option value="">Tipo Explotación....</option>
						<option value="1">Leche</option>
						<option value="2">Carne</option>
						<option value="3">Lana</option>
						<option value="4">Mixto</option>
						<option value="0">Otro</option>
					</select> 					
			</div>
			
			<div data-linea="21">		
					<input type="text" id="nombreExplotacion" name="nombreExplotacion" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
			</div>
			
			<div data-linea="22">
				<label>Superficie explotación (Ha.):</label>
				<input type="text" id="superficieExplotacion" name="superficieExplotacion" data-er="^[0-9.]+$" />
			</div>
				
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detalleExplotacionInspeccionOCCSFS">
		<legend>Explotaciones Registradas</legend>
		<table id="detalleExplotacionInspeccionOCCS">
			<thead>
				<tr>
				    <th width="15%">Tipo Explotación</th>
					<th width="15%">Superficie Explotación (ha.)</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($tiposExplotacion = pg_fetch_assoc($tiposExplotacionInspeccionOCCS)){
					echo $cpco->imprimirLineaTipoExplotacionInspeccionOCCS($tiposExplotacion['id_inspeccion_occs_tipo_explotacion'], 
														$tiposExplotacion['explotacion'], $tiposExplotacion['superficie_explotacion'], 
														$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleExplotacionInspeccionOCCSConsultaFS">
		<legend>Explotaciones Registradas</legend>
		<table id="detalleExplotacionInspeccionOCCSConsulta">
			<thead>
				<tr>
				    <th width="15%">Tipo Explotación</th>
					<th width="15%">Superficie Explotación (ha.)</th>
				</tr>
			</thead>
			<?php 
				while ($tiposExplotacionConsulta = pg_fetch_assoc($tiposExplotacionInspeccionOCCSConsulta)){
					echo $cpco->imprimirLineaTipoExplotacionInspeccionOCCSConsulta($tiposExplotacionConsulta['id_inspeccion_occs_tipo_explotacion'], 
														$tiposExplotacionConsulta['explotacion'], $tiposExplotacionConsulta['superficie_explotacion'], 
														$ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Especies y Explotación</h2>

	<form id="nuevaEspecieInspeccionOCCS" data-rutaAplicacion="programasControlOficial" data-opcion="guardarEspecieInspeccionOCCS" data-destino="detalleItem">
		<input type='hidden' id='idInspeccionOCCS' name='idInspeccionOCCS' value="<?php echo $idInspeccionOCCS;?>" />
		
		<fieldset>
			<legend>Especie y Cantidad existente</legend>
			
			<div data-linea="23">
				<label>Especie:</label>
					<select id="especie" name="especie" required="required" >
						<option value="">Especie....</option>
						<option value="1">Ovinos</option>
						<option value="2">Caprinos</option>
						<option value="3">Camélidos Sudamericanos</option>
					</select> 
					
					<input type='hidden' id='nombreEspecie' name='nombreEspecie' />					
			</div>
			
			<div data-linea="23">
				<label>Razas:</label>
					<select id="raza" name="raza" required="required" disabled="disabled">
					</select> 					
			</div>
			
			<div data-linea="24">
				<input type='text' id='nombreRaza' name='nombreRaza' maxlength="16" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			
			<div data-linea="25">
				<label>Categoría:</label>
					<select id="categoria" name="categoria" required="required" disabled="disabled">
					</select>
					
					<input type='hidden' id='nombreCategoria' name='nombreCategoria' /> 					
			</div>
			
			<div data-linea="25">
				<label>Número animales:</label>
				<input type="number" id="numeroAnimales" name="numeroAnimales" data-er="^[0-9]+$" required="required" />
			</div>
				
					
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detalleEspecieInspeccionOCCSFS">
		<legend>Especie y Cantidad Existente Registrada</legend>
		<table id="detalleEspecieInspeccionOCCS">
			<thead>
				<tr>
				    <th width="15%">Especie</th>
					<th width="15%">Raza</th>
					<th width="10%">Categoría</th>
					<th width="10%">Número Animales</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($inspecciones = pg_fetch_assoc($especieInspeccionOCCS)){
					echo $cpco->imprimirLineaEspecieInspeccionOCCS($inspecciones['id_inspeccion_occs_especie'], $idInspeccionOCCS, 
														$inspecciones['especie'], $inspecciones['raza'], $inspecciones['categoria'], 
														$inspecciones['numero_animales'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleEspecieInspeccionOCCSConsultaFS">
		<legend>Especie y Cantidad Existente Registrada</legend>
		<table id="detalleEspecieInspeccionOCCSConsulta">
			<thead>
				<tr>
				    <th width="15%">Especie</th>
					<th width="15%">Raza</th>
					<th width="10%">Categoría</th>
					<th width="10%">Número Animales</th>
				</tr>
			</thead>
			<?php 
				while ($inspecciones = pg_fetch_assoc($especieInspeccionOCCSConsulta)){
					echo $cpco->imprimirLineaEspecieInspeccionOCCSConsulta($inspecciones['id_inspeccion_occs_especie'], $idInspeccionOCCS, 
														$inspecciones['especie'], $inspecciones['raza'], $inspecciones['categoria'], 
														$inspecciones['numero_animales'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Bioseguridad, Sanidad, Infraestructura y Manejo Animal</h2>

	<form id="nuevaBioseguridadInspeccionOCCS" data-rutaAplicacion="programasControlOficial" data-opcion="guardarBioseguridadInspeccionOCCS" data-destino="detalleItem">
		<input type='hidden' id='idInspeccionOCCS' name='idInspeccionOCCS' value="<?php echo $idInspeccionOCCS;?>" />
		
		<fieldset>
			<legend>Información de Bioseguridad, Sanidad, Infraestructura y Manejo</legend>
			
			<div data-linea="27">
				<label>Calendario Vacunación:</label>
					<select id="calendarioVacunacion" name="calendarioVacunacion" required="required" >
						<option value="">Vacunación....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 					
			</div>
			
			<div data-linea="27" >
				<label id="dlVacuna">Vacuna:</label>
				<input type="text" id="vacuna" name="vacuna" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
						
			<div data-linea="28">
				<label>Calendario Desparasitación:</label>
					<select id="calendarioDesparacitacion" name="calendarioDesparacitacion" required="required" >
						<option value="">Desparasitación....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 					
			</div>
			
			<div data-linea="28" >
				<label id="lFrecuencia">Frecuencia:</label>
				<input type="text" id="frecuencia" name="frecuencia" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			
			<hr />
			<div data-linea="29">
				<label>Asesoramiento Técnico:</label>
					<select id="asesoramientoTecnico" name="asesoramientoTecnico" required="required" >
						<option value="">Asesoramiento Técnico....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 					
			</div>
			
			<div data-linea="30" >
				<label id="lAsesoramientoTecnico1">Nombre:</label>
				<input type="text" id="nombreAsesor" name="nombreAsesor" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			
			<div data-linea="30" >
				<label id="lAsesoramientoTecnico2">Profesión:</label>
				<input type="text" id="profesionAsesor" name="profesionAsesor" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			
			<hr />
			
			<div data-linea="31">
				<label>Identificación Individual:</label>
					<select id="identificacionIndividual" name="identificacionIndividual" required="required" >
						<option value="">Identificación Individual....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 					
			</div>
			
			<div data-linea="31" >
				<label id="lTipoIdentificacion">Tipo de Identificación:</label>
				<input type="text" id="tipoIdentificacion" name="tipoIdentificacion" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			
			<div data-linea="32">
				<label>Tipo de Manejo:</label>
					<select id="tipoAlimentacion" name="tipoAlimentacion" required="required" >
						<option value="">Tipo Manejo....</option>
						<option value="1">Pastoreo</option>
						<option value="2">Mixta</option>
						<option value="0">Otros</option>
					</select> 
			</div>
			
			<div data-linea="32">
				<input type="text" id="nombreTipoAlimentacion" name="nombreTipoAlimentacion" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			
			<div data-linea="33">
				<label>Corral de Manejo:</label>
					<select id="corralManejo" name="corralManejo" required="required" >
						<option value="">Registros Productivos....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 					
			</div>
		
			<hr />
			
			<div data-linea="34">
				<label>Registros Productivos:</label>
					<select id="registrosProductivos" name="registrosProductivos" required="required" >
						<option value="">Registros Productivos....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 					
			</div>
			
			<div data-linea="34">
				<label>Tipo de Producción:</label>
					<select id="tipoProduccion" name="tipoProduccion" required="required" >
						<option value="">Tipo Producción....</option>
						<option value="1">Traspatio</option>
						<option value="2">Artesanal</option>
						<option value="3">Familiar</option>
						<option value="4">Comunitaria</option>
						<option value="5">Industrial</option>
					</select> 
					
					<input type="hidden" id="nombreTipoProduccion" name="nombreTipoProduccion" />					
			</div>
			
			<div data-linea="35">
				<label>Sector Perteneciente:</label>
					<select id="sectorPerteneciente" name="sectorPerteneciente" required="required" >
						<option value="">Sector Perteneciente....</option>
						<option value="1">Público</option>
						<option value="2">Privado</option>
						<option value="3">Mixto</option>
						<option value="0">Otros</option>
					</select> 			
					
							
			</div>
			
			<div data-linea="35">
				<input type="text" id="nombreSectorPerteneciente" name="nombreSectorPerteneciente" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detalleBioseguridadInspeccionOCCSFS">
		<legend>Información de Bioseguridad, Sanidad, Infraestructura y Manejo Registrado</legend>
		<table id="detalleBioseguridadInspeccionOCCS">
			<thead>
				<tr>
				    <th width="15%">Vacuna</th>
					<th width="15%">Frecuencia</th>
					<th width="10%">Tipo Identificación</th>
					<th width="10%">Tipo Alimentación</th>
					<th width="10%">Tipo Producción</th>
					<th width="10%">Sector</th>
					<th width="5%">Abrir</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($bioseguridad = pg_fetch_assoc($bioseguridadInspeccionOCCS)){
					echo $cpco->imprimirLineaBioseguridadInspeccionOCCS($bioseguridad['id_inspeccion_occs_bioseguridad'], $idInspeccionOCCS, 
														$bioseguridad['vacuna'], $bioseguridad['frecuencia'], $bioseguridad['tipo_identificacion'], 
														$bioseguridad['tipo_alimentacion'], $bioseguridad['tipo_produccion'], 
														$bioseguridad['sector_perteneciente'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleBioseguridadInspeccionOCCSConsultaFS">
		<legend>Información de Bioseguridad, Sanidad, Infraestructura y Manejo Registrado</legend>
		<table id="detalleBioseguridadInspeccionOCCSConsulta">
			<thead>
				<tr>
				    <th width="15%">Vacuna</th>
					<th width="15%">Frecuencia</th>
					<th width="10%">Tipo Identificación</th>
					<th width="10%">Tipo Alimentación</th>
					<th width="10%">Tipo Producción</th>
					<th width="10%">Sector</th>
					<th width="5%">Abrir</th>
				</tr>
			</thead>
			<?php 
				while ($bioseguridad = pg_fetch_assoc($bioseguridadInspeccionOCCSConsulta)){
					echo $cpco->imprimirLineaBioseguridadInspeccionOCCSConsulta($bioseguridad['id_inspeccion_occs_bioseguridad'], $idInspeccionOCCS, 
														$bioseguridad['vacuna'], $bioseguridad['frecuencia'], $bioseguridad['tipo_identificacion'], 
														$bioseguridad['tipo_alimentacion'], $bioseguridad['tipo_produccion'], 
														$bioseguridad['sector_perteneciente'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Historial de Patologías en el Predio</h2>

	<form id="nuevoHistorialPatologiasInspeccionOCCS" data-rutaAplicacion="programasControlOficial" data-opcion="guardarHistorialPatologiasInspeccionOCCS" data-destino="detalleItem">
		<input type='hidden' id='idInspeccionOCCS' name='idInspeccionOCCS' value="<?php echo $idInspeccionOCCS;?>" />
		
		<fieldset>
			<legend>Enfermedades Presentadas</legend>
			
			<div data-linea="40">
				<label>Tipo de Enfermedad:</label>
					<select id="enfermedad" name="enfermedad" required="required" >
						<option value="">Tipo Enfermedad....</option>
						<option value="1">Endoparásitos</option>
						<option value="2">Abortos</option>
						<option value="3">Vesículas</option>
						<option value="4">Ectoparásitos</option>
						<option value="5">Cojeras</option>
						<option value="6">Ninguna</option>
						<option value="0">Otros</option>
					</select> 					
			</div>
			
			<div data-linea="40">
				<input type="text" id="nombreEnfermedad" name="nombreEnfermedad" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
			</div>
				
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detalleHistorialPatologiasInspeccionOCCSFS">
		<legend>Enfermedades Registradas</legend>
		<table id="detalleHistorialPatologiasInspeccionOCCS">
			<thead>
				<tr>
				    <th width="15%">Enfermedad</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($enfermedad = pg_fetch_assoc($enfermedadInspeccionOCCS)){
					echo $cpco->imprimirLineaHistorialPatologiasInspeccionOCCS($enfermedad['id_inspeccion_occs_historial_patologias'], 
														$idInspeccionOCCS, $enfermedad['enfermedad'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleHistorialPatologiasInspeccionOCCSConsultaFS">
		<legend>Enfermedades Registradas</legend>
		<table id="detalleHistorialPatologiasInspeccionOCCSConsulta">
			<thead>
				<tr>
				    <th width="15%">Enfermedad</th>
				</tr>
			</thead>
			<?php 
				while ($enfermedad = pg_fetch_assoc($enfermedadInspeccionOCCSConsulta)){
					echo $cpco->imprimirLineaHistorialPatologiasInspeccionOCCSConsulta($enfermedad['id_inspeccion_occs_historial_patologias'], 
														$idInspeccionOCCS, $enfermedad['enfermedad'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>



<div class="pestania">

	<h2>Finalizar Censo</h2>

	<form id="cerrarInspeccionOCCS" data-rutaAplicacion="programasControlOficial" data-opcion="guardarCierreInspeccionOCCS" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type='hidden' id='idInspeccionOCCS' name='idInspeccionOCCS' value="<?php echo $idInspeccionOCCS;?>" />
		
		<fieldset>
			<legend>Cerrar Censo</legend>
			
			<div data-linea="41">
				<label>Observaciones:</label>
				<input type="text" id="observaciones" name="observaciones" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
			</div>

		</fieldset>
	
		<div data-linea="44">
			<button id="guardarNuevaInspeccion" type="submit" class="guardar">Guardar</button>
		</div>
	</form>	
	
	<fieldset id="cerrarInspeccionOCCSConsulta">
		<legend>Cerrar Censo</legend>
		
		<div data-linea="28">
			<label>Observaciones: </label>
			<?php echo $inspeccionOCCS['observaciones'];?>
		</div>

	</fieldset>
</div>

<script type="text/javascript">

var usuario = <?php echo json_encode($usuario); ?>;
var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;
var estado= <?php echo json_encode($inspeccionOCCS['estado']); ?>;
var perfil= <?php echo json_encode($perfilAdmin); ?>;

var array_razaOvinos= <?php echo json_encode($razaOvinos); ?>;
var array_razaCaprinos= <?php echo json_encode($razaCaprinos); ?>;
var array_razaCamelidos= <?php echo json_encode($razaCamelidos); ?>;

var array_categoriaOvinos= <?php echo json_encode($categoriaOvinos); ?>;
var array_categoriaCaprinos= <?php echo json_encode($categoriaCaprinos); ?>;
var array_categoriaCamelidos= <?php echo json_encode($categoriaCamelidos); ?>;

	$("document").ready(function(){
		distribuirLineas();	
		construirValidador();
		construirAnimacion($(".pestania"));

		$("#actualizacion").hide();

		$("#fecha").datepicker({
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

		$('#nombreExplotacion').hide();
		$('#nombreRaza').hide();
		$('#nombreEnfermedad').hide();
		$('#nombreTipoAlimentacion').hide();
		$('#nombreSectorPerteneciente').hide();
			
		$('#divVacuna').hide();
		$('#divFrecuencia').hide();
		$('#divAsesoramientoTecnico1').hide();
		$('#divAsesoramientoTecnico2').hide();
		$('#divTipoIdentificacion').hide();
		$('#divRegistrosProductivos').hide();
		$('#divSectorPerteneciente').hide();
		
		$('#detalleExplotacionInspeccionOCCSConsultaFS').hide();
		$('#detalleEspecieInspeccionOCCSConsultaFS').hide();
		$('#detalleBioseguridadInspeccionOCCSConsultaFS').hide();
		$('#detalleHistorialPatologiasInspeccionOCCSConsultaFS').hide();
		$("#cerrarInspeccionOCCSConsulta").hide();
		
		acciones("#nuevaExplotacionInspeccionOCCS","#detalleExplotacionInspeccionOCCS");
		acciones("#nuevaEspecieInspeccionOCCS","#detalleEspecieInspeccionOCCS");
		acciones("#nuevaBioseguridadInspeccionOCCS","#detalleBioseguridadInspeccionOCCS");
		acciones("#nuevoHistorialPatologiasInspeccionOCCS","#detalleHistorialPatologiasInspeccionOCCS");

		if(usuario == '0'){
			$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#botonGuardar").attr("disabled", "disabled");
		}

		if(<?php echo json_encode($murcielagosHematofagos['nueva_inspeccion']); ?>=='Si'){
			$('#siguienteInspeccion').show();
			$('#siguienteInspeccion').addClass('exito'); //exito, advertencia, alerta
		}else{
			$('#siguienteInspeccion').hide();
		}

		if(estado == 'cerrado'){
			if(perfil != false){
				$("#modificar").show();
				$("#nuevaExplotacionInspeccionOCCS").show();
					$('#detalleExplotacionInspeccionOCCSConsultaFS').hide();
					$('#detalleExplotacionInspeccionOCCSFS').show();
				$("#nuevaEspecieInspeccionOCCS").show();
					$('#detalleEspecieInspeccionOCCSConsultaFS').hide();
					$('#detalleEspecieInspeccionOCCSFS').show();
				$("#nuevaBioseguridadSanidadInspeccionOCCS").show();
					$('#detalleBioseguridadInspeccionOCCSConsultaFS').hide();
					$('#detalleBioseguridadInspeccionOCCSFS').show();
				$("#nuevoHistorialPatologiasInspeccionOCCS").show();
					$('#detalleHistorialPatologiasInspeccionOCCSConsultaFS').hide();
					$('#detalleHistorialPatologiasInspeccionOCCSFS').show();
				$("#cerrarInspeccionOCCS").hide();
				$("#cerrarInspeccionOCCSConsulta").show();
			}else{
				$("#modificar").hide();
				$("#nuevaExplotacionInspeccionOCCS").hide();
					$('#detalleExplotacionInspeccionOCCSConsultaFS').show();
					$('#detalleExplotacionInspeccionOCCSFS').hide();
				$("#nuevaEspecieInspeccionOCCS").hide();
					$('#detalleEspecieInspeccionOCCSConsultaFS').show();
					$('#detalleEspecieInspeccionOCCSFS').hide();
				$("#nuevaBioseguridadInspeccionOCCS").hide();
					$('#detalleBioseguridadInspeccionOCCSConsultaFS').show();
					$('#detalleBioseguridadInspeccionOCCSFS').hide();
				$("#nuevoHistorialPatologiasInspeccionOCCS").hide();
					$('#detalleHistorialPatologiasInspeccionOCCSConsultaFS').show();
					$('#detalleHistorialPatologiasInspeccionOCCSFS').hide();
				$("#cerrarInspeccionOCCS").hide();
				$("#cerrarInspeccionOCCSConsulta").show();
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

	$("#modificarInspeccionOCCS").submit(function(event){

		$("#modificarInspeccionOCCS").attr('data-opcion', 'modificarInspeccionOCCS');
	    $("#modificarInspeccionOCCS").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#fecha").val())){
			error = true;
			$("#fecha").addClass("alertaCombo");
		}

		if(!$.trim($("#nombrePredio").val()) || !esCampoValido("#nombrePredio")){
			error = true;
			$("#nombrePredio").addClass("alertaCombo");
		}

		if(!$.trim($("#nombrePropietario").val()) || !esCampoValido("#nombrePropietario")){
			error = true;
			$("#nombrePropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#cedulaPropietario").val()) || !esCampoValido("#cedulaPropietario")){
			error = true;
			$("#cedulaPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#telefono").val()) || !esCampoValido("#telefono")){
			error = true;
			$("#telefono").addClass("alertaCombo");
		}

		if(!$.trim($("#correoElectronico").val()) || !esCampoValido("#correoElectronico")){
			error = true;
			$("#correoElectronico").addClass("alertaCombo");
		}

		if(!$.trim($("#sector").val()) || !esCampoValido("#sector")){
			error = true;
			$("#sector").addClass("alertaCombo");
		}

		if(!$.trim($("#x").val()) || !esCampoValido("#x")){
			error = true;
			$("#x").addClass("alertaCombo");
		}

		if(!$.trim($("#y").val()) || !esCampoValido("#y")){
			error = true;
			$("#y").addClass("alertaCombo");
		}

		if(!$.trim($("#z").val()) || !esCampoValido("#z")){
			error = true;
			$("#z").addClass("alertaCombo");
		}

		/*if(!$.trim($("#altitud").val()) || !esCampoValido("#altitud")){
			error = true;
			$("#altitud").addClass("alertaCombo");
		}*/

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
    	if($("#explotacion option:selected").val()!='0'){
        	$('#nombreExplotacion').hide();
    		$("#nombreExplotacion").val($("#explotacion option:selected").text());
			$('#nombreExplotacion').removeAttr("required");
        }else{
        	$("#nombreExplotacion").val('');
    	    $('#nombreExplotacion').show();
			$('#nombreExplotacion').attr("required", "required");
        }
	});

	//Especies, Razas y Categorías de Animales en el Predio
	$("#especie").change(function(event){
    	sraza ='0';
		sraza = '<option value="">Raza...</option>';

		switch($("#especie option:selected").text()) {
		    case 'Ovinos':
		    	for(var i=0;i<array_razaOvinos.length;i++){
				    sraza += '<option value="'+(i+1)+'">'+array_razaOvinos[i]+'</option>';
			   	}
		    	sraza += '<option value="'+0+'">Otra</option>';
		    	
		        break;
		    case 'Caprinos':
		    	for(var i=0;i<array_razaCaprinos.length;i++){
				    sraza += '<option value="'+(i+1)+'">'+array_razaCaprinos[i]+'</option>';
			   	}
		    	sraza += '<option value="'+0+'">Otra</option>';
		    	
		        break;
		    case 'Camélidos Sudamericanos':
		    	for(var i=0;i<array_razaCamelidos.length;i++){
				    sraza += '<option value="'+(i+1)+'">'+array_razaCamelidos[i]+'</option>';
			   	}
		    	
		        break;
		    default:
		}
	    
	   	
	    $('#raza').html(sraza);
	    $("#raza").removeAttr("disabled");

	    $("#nombreEspecie").val($("#especie option:selected").text());	
	}); 

	$("#raza").change(function(event){
		if($("#raza option:selected").val()!='0'){
        	$('#nombreRaza').hide();
    		$("#nombreRaza").val($("#raza option:selected").text());
        }else{
        	$("#nombreRaza").val('');
    	    $('#nombreRaza').show();
        }
		
    	scategoria ='0';
    	scategoria = '<option value="">Categoría...</option>';

		switch($("#especie option:selected").text()) {
		    case 'Ovinos':
		    	for(var i=0;i<array_categoriaOvinos.length;i++){
		    		scategoria += '<option value="'+(i+1)+'">'+array_categoriaOvinos[i]+'</option>';
			   	}
		    	
		        break;
		    case 'Caprinos':
		    	for(var i=0;i<array_categoriaCaprinos.length;i++){
		    		scategoria += '<option value="'+(i+1)+'">'+array_categoriaCaprinos[i]+'</option>';
			   	}
		    	
		        break;
		    case 'Camélidos Sudamericanos':
		    	for(var i=0;i<array_categoriaCamelidos.length;i++){
		    		scategoria += '<option value="'+(i+1)+'">'+array_categoriaCamelidos[i]+'</option>';
			   	}
		    	
		        break;
		    default:
		}
	    
	   	
	    $('#categoria').html(scategoria);
	    $("#categoria").removeAttr("disabled");	
	});

	$("#categoria").change(function(event){
    	$("#nombreCategoria").val($("#categoria option:selected").text());	
	}); 

	$("#enfermedad").change(function(){
        if($("#enfermedad option:selected").val()!='0'){
        	$('#nombreEnfermedad').hide();
    		$("#nombreEnfermedad").val($("#enfermedad option:selected").text());
        }else{
        	$("#nombreEnfermedad").val('');
    	    $('#nombreEnfermedad').show();
        }
	});

	$("#tipoAlimentacion").change(function(){
        if($("#tipoAlimentacion option:selected").val()!='0'){
        	$('#nombreTipoAlimentacion').hide();
    		$("#nombreTipoAlimentacion").val($("#tipoAlimentacion option:selected").text());
        }else{
        	$("#nombreTipoAlimentacion").val('');
    	    $('#nombreTipoAlimentacion').show();
        }
	});

	$("#tipoProduccion").change(function(){
        $("#nombreTipoProduccion").val($("#tipoProduccion option:selected").text());
	});
	
	$("#sectorPerteneciente").change(function(){
        if($("#sectorPerteneciente option:selected").val()!='0'){
        	$('#nombreSectorPerteneciente').hide();
    		$("#nombreSectorPerteneciente").val($("#sectorPerteneciente option:selected").text());
        }else{
        	$("#nombreSectorPerteneciente").val('');
    	    $('#nombreSectorPerteneciente').show();
        }
	});

	//Cierre Inspección OCCS
	$("#cerrarInspeccionOCCS").submit(function(event){

		$("#cerrarInspeccionOCCS").attr('data-opcion', 'guardarCierreInspeccionOCCS');
	    $("#cerrarInspeccionOCCS").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

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