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
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Programas Control Oficial Catastro de Predio de Equidos'),0,'id_perfil');
	}
	
	$ruta = 'programasControlOficial';
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	
	//Especies
	$especie = $cc->listarEspeciesXCodigo($conexion, "'EQUIN', 'EQUID'");
	
	//Raza
	$querRazaEquinos = $cc->listarRazaXCodigoEspecie($conexion, "'EQUIN'");
	$razaEquinos='';
	
	while ($fila = pg_fetch_assoc($querRazaEquinos)){
	    $razaEquinos .=  '<option value="'.$fila['id_raza']. '" >'. $fila['raza'] .'</option>';
	}
	
	$queryRazaEquidos = $cc->listarRazaXCodigoEspecie($conexion, "'EQUID'");
	$razaEquidos='';
	
	while ($fila = pg_fetch_assoc($queryRazaEquidos)){
	    $razaEquidos .=  '<option value="'.$fila['id_raza']. '" >'. $fila['raza'] .'</option>';
	}
	
	//Raza
	$queryEquinos = $cc->listarCategoriaXCodigoEspecie($conexion, "'EQUIN'");
	$categoriaEquinos='';
	
	while ($fila = pg_fetch_assoc($queryEquinos)){
	    $categoriaEquinos .=  '<option value="'.$fila['id_categoria_especie']. '" >'. $fila['categoria_especie'] .'</option>';
	}
	
	$queryEquidos = $cc->listarCategoriaXCodigoEspecie($conexion, "'EQUID'");
	$categoriaEquidos='';
	
	while ($fila = pg_fetch_assoc($queryEquidos)){
	    $categoriaEquidos .=  '<option value="'.$fila['id_categoria_especie']. '" >'. $fila['categoria_especie'] .'</option>';
	}
	/*$razaEquinos = array(  'Andaluz', 'Appaloosa', 'Árabe', 'Cuarto de Milla', 'Hannoveriano', 
							'Inglés Pura Sangre', 'Lusitano', 'Mestizo', 'Paso Fino Colombiano', 'Percherón',
							'Peruano de Paso', 'Pony', 'Pura Raza Española', 'Silla Argentino');
	$razaEquidos = array('Sin raza');
	
	$categoriaEquinos = array('Hembras Adultas', 'Machos Adultos Enteros', 'Machos Castrados', 'Potrancas', 'Potros');
	$categoriaEquidos = array('Asnos Machos', 'Asnos Hembra', 'Mulares Machos', 'Mulares Hembras', 'Burdéganos Machos', 'Burdéganos Hembras', 'Cebras Macho', 'Cebras Hembra');*/
	
	$opcionBioseguridad = array('Pediluvios', 'Rodiluvios', 'Arco de Desinfección');
	$sinOpcionBioseguridad = array('No Aplica');

	
	$idCatastroPredioEquidos = $_POST['id'];
	$catastroPredioEquidos = pg_fetch_assoc($cpco->abrirCatastroPredioEquidos($conexion, $idCatastroPredioEquidos));
	
	$motivoCatastroPredioEquidos = $cpco->listarMotivoCatastroPredioEquidos($conexion, $idCatastroPredioEquidos);
	$motivoCatastroPredioEquidosConsulta = $cpco->listarMotivoCatastroPredioEquidos($conexion, $idCatastroPredioEquidos);
	
	$tipoActividadPredioEquidos = $cpco->listarTipoActividadPredioEquidos($conexion, $idCatastroPredioEquidos);
	$tipoActividadPredioEquidosConsulta = $cpco->listarTipoActividadPredioEquidos($conexion, $idCatastroPredioEquidos);
	
	$especiePredioEquidos = $cpco->listarEspeciePredioEquidos($conexion, $idCatastroPredioEquidos);
	$especiePredioEquidosConsulta = $cpco->listarEspeciePredioEquidos($conexion, $idCatastroPredioEquidos);
	
	$bioseguridadPredioEquidos = $cpco->listarBioseguridadPredioEquidos($conexion, $idCatastroPredioEquidos);
	$bioseguridadPredioEquidosConsulta = $cpco->listarBioseguridadPredioEquidos($conexion, $idCatastroPredioEquidos);
	
	$sanidadPredioEquidos = $cpco->listarSanidadPredioEquidos($conexion, $idCatastroPredioEquidos);
	$sanidadPredioEquidosConsulta = $cpco->listarSanidadPredioEquidos($conexion, $idCatastroPredioEquidos);
	
	$historialPatologiaPredioEquidos = $cpco->listarHistorialPatologiasPredioEquidos($conexion, $idCatastroPredioEquidos);
	$historialPatologiaPredioEquidosConsulta = $cpco->listarHistorialPatologiasPredioEquidos($conexion, $idCatastroPredioEquidos);
	
?>

<header>
	<h1>Catastro de Predios de Équidos</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>

<div class="pestania">
	<h2>Información del Predio</h2>

	<form id="modificarCatastroPredioEquidos" data-rutaAplicacion="programasControlOficial" data-opcion="modificarCatastroPredioEquidos" data-destino="detalleItem" >
		<p>
			<button id="modificar" type="button" class="editar">Modificar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</p>
	
	<div id="informacion">
	
		<fieldset>
			<legend>Información de Identificación del Predio</legend>
	
			<div data-linea="0">
				<label>Código de Sitio:</label>
				<?php echo $catastroPredioEquidos['num_solicitud'];?>
			</div>
			
			<div data-linea="1">
				<label>Fecha:</label>
				<?php echo date('j/n/Y',strtotime($catastroPredioEquidos['fecha']));?>
			</div>
			
			<div data-linea="2">
				<label>Nombre del Predio:</label>
				<?php echo $catastroPredioEquidos['nombre_predio'];?>
			</div>
		
		</fieldset>
		
		<fieldset>
			<legend>Información del Propietario</legend>			
			
			<div data-linea="3">
				<label>Cédula del Propietario:</label>
				<?php echo $catastroPredioEquidos['cedula_propietario'];?>
			</div>
			
			<div data-linea="3">
				<label>Nombre del Propietario:</label>
				<?php echo $catastroPredioEquidos['nombre_propietario'];?>
			</div>			
			
			<div data-linea="4">
				<label>Teléfono:</label>
				<?php echo $catastroPredioEquidos['telefono_propietario'];?>
			</div>
			
			<div data-linea="4">
				<label>Correo Electrónico:</label>
				<?php echo $catastroPredioEquidos['correo_electronico_propietario'];?>
			</div>
			
		</fieldset>
		
		<fieldset>
			<legend>Información del Administrador</legend>			
		
			<div data-linea="5">
				<label>Cédula:</label>
				<?php echo $catastroPredioEquidos['cedula_administrador'];?>
			</div>
			
			<div data-linea="5">
				<label>Nombre:</label>
				<?php echo $catastroPredioEquidos['nombre_administrador'];?>
			</div>	
			
			<div data-linea="6">
				<label>Teléfono:</label>
				<?php echo $catastroPredioEquidos['telefono_administrador'];?>
			</div>
			
			<div data-linea="6">
				<label>Correo Electrónico:</label>
				<?php echo $catastroPredioEquidos['correo_electronico_administrador'];?>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>Ubicación y Datos Generales</legend>
	
			<div data-linea="7">
				<label>Provincia</label>
				<?php echo $catastroPredioEquidos['provincia'];?>	
			</div>
				
			<div data-linea="7">
				<label>Cantón</label>
					<?php echo $catastroPredioEquidos['canton'];?>
				</div>
				
			<div data-linea="8">	
				<label>Parroquia</label>
					<?php echo $catastroPredioEquidos['parroquia'];?>
			</div>
					
			<div data-linea="8">
				<label>Dirección:</label>
				<?php echo $catastroPredioEquidos['direccion_predio'];?>
			</div>
			
		</fieldset>
		
		<fieldset>
			<legend>Coordenadas</legend>
			
			<div data-linea="9">
				<label>X:</label>
				<?php echo $catastroPredioEquidos['utm_x'];?>
			</div>
			
			<div data-linea="9">
				<label>Y:</label>
				<?php echo $catastroPredioEquidos['utm_y'];?>
			</div>
			
			<div data-linea="9">
				<label>Z:</label>
				<?php echo $catastroPredioEquidos['utm_z'];?>
			</div>
			
			<!--div data-linea="10">
				<label>Altitud:</label>
				< ?php echo $catastroPredioEquidos['altitud'];?>
			</div-->
			
			<div data-linea="10">
				<label>Extensión (Ha.):</label>
				<?php echo $catastroPredioEquidos['extension'];?>
			</div>
	
		</fieldset>			
		
		
	</div>
	
	<div id="actualizacion">
			<input type='hidden' id='idCatastroPredioEquidos' name='idCatastroPredioEquidos' value="<?php echo $idCatastroPredioEquidos;?>" />	
		
			<fieldset>
				<legend>Información de Identificación del Predio</legend>
		
				<div data-linea="11">
					<label>N° Solicitud:</label>
					<?php echo $catastroPredioEquidos['num_solicitud'];?>
				</div>
				
				<div data-linea="12">
					<label>Fecha:</label>
					<input type="text" id="fecha" name="fecha" value="<?php echo date('j/n/Y',strtotime($catastroPredioEquidos['fecha']));?>" />
				</div>
				
				<div data-linea="13">
					<label>Nombre del Predio:</label>
					<input type="text" id="nombrePredio" name="nombrePredio" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $catastroPredioEquidos['nombre_predio'];?>" />
				</div>
			
			</fieldset>
		
			<fieldset id="datosConsultaWebServices">
				<legend>Información del Propietario</legend>
				
				<div data-linea="14">
					<label>Cédula del Propietario:</label>
					<input type="text" id="numeroPropietario" name="numero" maxlength="13" data-er="^[0-9]+$" value="<?php echo $catastroPredioEquidos['cedula_propietario'];?>" />
					<input type="hidden" id="cedulaPropietario" name="cedulaPropietario" maxlength="13" data-er="^[0-9]+$" value="<?php echo $catastroPredioEquidos['cedula_propietario'];?>"/>
					<input type="hidden" id="clasificacionPropietario" name="clasificacion" value="Cédula"/>
				</div>
				
				<div data-linea="14">
					<label>Nombre del Propietario:</label>
					<input type="text" id="nombrePropietario" name="nombrePropietario" readonly="readonly" value="<?php echo $catastroPredioEquidos['nombre_propietario'];?>" />
				</div>				
		
				<div data-linea="15">
					<label>Teléfono:</label>
					<input type="text" id="telefonoPropietario" name="telefonoPropietario" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15"  value="<?php echo $catastroPredioEquidos['telefono_propietario'];?>" />
				</div>
		
				<div data-linea="15">
					<label>Correo Electrónico:</label>
					<input type="text" id="correoElectronicoPropietario" name="correoElectronicoPropietario" maxlength="32" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" value="<?php echo $catastroPredioEquidos['correo_electronico_propietario'];?>" />
				</div>
			
			</fieldset>
		
			<fieldset id="datosConsultaWebServicesAdministrador">
				<legend>Información del Administrador</legend>
					
				<div data-linea="16">
					<label>Cédula:</label>
					<input type="text" id="numeroAdministrador" name="numero" maxlength="13" data-er="^[0-9]+$" value="<?php echo $catastroPredioEquidos['cedula_administrador'];?>" />
					<input type="hidden" id="cedulaAdministrador" name="cedulaAdministrador" maxlength="13" data-er="^[0-9]+$" value="<?php echo $catastroPredioEquidos['cedula_administrador'];?>"/>
					<input type="hidden" id="clasificacionAdministrador" name="clasificacion" value="Cédula"/>
				</div>
				
				<div data-linea="16">
					<label>Nombre:</label>
					<input type="text" id="nombreAdministrador" name="nombreAdministrador" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $catastroPredioEquidos['nombre_administrador'];?>" />
				</div>			
		
				<div data-linea="17">
					<label>Teléfono:</label>
					<input type="text" id="telefonoAdministrador" name="telefonoAdministrador" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15"  value="<?php echo $catastroPredioEquidos['telefono_administrador'];?>" />
				</div>
		
				<div data-linea="17">
					<label>Correo Electrónico:</label>
					<input type="text" id="correoElectronicoAdministrador" name="correoElectronicoAdministrador" maxlength="32" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" value="<?php echo $catastroPredioEquidos['correo_electronico_administrador'];?>" />
				</div>
		
			</fieldset>
			
			<fieldset>
				<legend>Ubicación y Datos Generales</legend>
		
				<div data-linea="18">
					<label>Provincia</label>
					<?php echo $catastroPredioEquidos['provincia'];?>	
				</div>
					
				<div data-linea="18">
					<label>Cantón</label>
						<?php echo $catastroPredioEquidos['canton'];?>
					</div>
					
				<div data-linea="19">	
					<label>Parroquia</label>
						<?php echo $catastroPredioEquidos['parroquia'];?>
				</div>
				
						
				<div data-linea="19">
					<label>Dirección:</label>
					<input type="text" id="direccionPredio" name="direccionPredio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $catastroPredioEquidos['direccion_predio'];?>"/>
				</div>
				
			</fieldset>
			
			<fieldset>
				<legend>Coordenadas</legend>
				
		
				<div data-linea="20">
					<label>X:</label>
					<input type="text" id="x" name="x" maxlength="6" data-er="^[0-9]+$" value="<?php echo $catastroPredioEquidos['utm_x'];?>"/>
				</div>
				
				<div data-linea="20">
					<label>Y:</label>
					<input type="text" id="y" name="y" maxlength="7" data-er="^[0-9]+$" value="<?php echo $catastroPredioEquidos['utm_y'];?>"/>
				</div>
				
				<div data-linea="20">
					<label>Z:</label>
					<input type="text" id="z" name="z" maxlength="4" data-er="^[0-9]+$" value="<?php echo $catastroPredioEquidos['utm_z'];?>"/>
				</div>
				
				<!-- div data-linea="21">
					<label>Altitud:</label>
					<input type="text" id="altitud" name="altitud" maxlength="16" data-er="^[0-9.]+$" value="< ?php echo $catastroPredioEquidos['altitud'];?>"/>
				</div-->
				
				<div data-linea="21">
					<label>Extensión (Ha.):</label>
					<input type="text" id="extension" name="extension" maxlength="16" data-er="^[0-9.]+$" value="<?php echo $catastroPredioEquidos['extension'];?>"/>
				</div>
		
			</fieldset>

	</div>
	</form>
	
	<fieldset id="adjuntos">
			<legend>Mapa de Ubicación</legend>
	
				<div data-linea="1">
					<label>Mapa:</label>
					<?php echo ($catastroPredioEquidos['imagen_mapa']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$catastroPredioEquidos['imagen_mapa'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Mapa cargado</a>')?>
				</div>
				
				<form id="subirArchivo" action="aplicaciones/programasControlOficial/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
					
					<input type="file" name="archivo" id="archivo" accept="application/pdf" /> 
					<input type="hidden" name="id" value="<?php echo $catastroPredioEquidos['id_catastro_predio_equidos'];?>" />
					<input type="hidden" name="aplicacion" value="CatastroPredioEquidos" /> 
					
					<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
				</form>
				<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
		</fieldset>
		
		<fieldset id="adjuntosInforme">
			<legend>Informe</legend>
	
				<div data-linea="1">
					<label>Informe:</label>
					<?php echo ($catastroPredioEquidos['ruta_informe']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$catastroPredioEquidos['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe cargado</a>')?>
				</div>
				
				<form id="subirArchivoInforme" action="aplicaciones/programasControlOficial/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergenteInforme" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
					
					<input type="file" name="archivo" id="archivoInforme" accept="application/pdf" /> 
					<input type="hidden" name="id" value="<?php echo $catastroPredioEquidos['id_catastro_predio_equidos'];?>" />
					<input type="hidden" name="aplicacion" value="InformeCatastroPredioEquidos" /> 
					
					<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
				</form>
				<iframe name="ventanaEmergenteInforme" class="ventanaEmergente"></iframe>
		</fieldset>
				
</div>

<div class="pestania">

	<h2>Motivo del Catastro</h2>

	<form id="nuevoMotivoCatastroPredioEquidos" data-rutaAplicacion="programasControlOficial" data-opcion="guardarMotivoCatastroPredioEquidos" data-destino="detalleItem">
		<input type='hidden' id='idCatastroPredioEquidos' name='idCatastroPredioEquidos' value="<?php echo $idCatastroPredioEquidos;?>" />
		
		<fieldset>
			<legend>Información del Catastro</legend>
			
			<div data-linea="21">
				<label>Motivo del Catastro:</label>
					<select id="motivoCatastro" name="motivoCatastro" required="required" >
						<option value="">Motivo Catstro....</option>
						<option value="1">Rutina</option>
						<option value="2">Solicitud</option>
						<option value="3">Vigilancia Pasiva</option>
					</select> 					
					
					<input type="hidden" id="nombreMotivoCatastro" name="nombreMotivoCatastro" />
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
						
		</fieldset>
	</form>
	
	<fieldset id="detalleMotivoCatastroPredioEquidosFS">
		<legend>Motivos de Catastro Registrados</legend>
		<table id="detalleMotivoCatastroPredioEquidos">
			<thead>
				<tr>
				    <th width="15%">Motivo de Catastro</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($motivoCatastro = pg_fetch_assoc($motivoCatastroPredioEquidos)){
					echo $cpco->imprimirLineaMotivoCatastroPredioEquidos($motivoCatastro['id_catastro_predio_equidos_catastro'], 
														$idPredioEquidos, $motivoCatastro['catastro'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleMotivoCatastroPredioEquidosConsultaFS">
		<legend>Explotaciones Registradas</legend>
		<table id="detalleMotivoCatastroPredioEquidosConsulta">
			<thead>
				<tr>
				    <th width="15%">Motivo de Catastro</th>
				</tr>
			</thead>
			<?php 
				while ($motivoCatastro = pg_fetch_assoc($motivoCatastroPredioEquidosConsulta)){
					echo $cpco->imprimirLineaMotivoCatastroPredioEquidosConsulta($motivoCatastro['id_catastro_predio_equidos_catastro'], 
														$idPredioEquidos, $motivoCatastro['catastro'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Tipo de Actividad</h2>

	<form id="nuevoTipoActividadCatastroPredioEquidos" data-rutaAplicacion="programasControlOficial" data-opcion="guardarTipoActividadCatastroPredioEquidos" data-destino="detalleItem">
		<input type='hidden' id='idCatastroPredioEquidos' name='idCatastroPredioEquidos' value="<?php echo $idCatastroPredioEquidos;?>" />
		
		<fieldset>
			<legend>Tipo de Actividad Realizada</legend>
			
			<div data-linea="21">
				<label>Tipo de Actividad:</label>
					<select id="actividad" name="actividad" required="required" >
						<option value="">Tipo Actividad....</option>
						<option value="1">Adiestramiento</option>
						<option value="2">Carreras</option>
						<option value="3">Crianza</option>
						<option value="4">Endurance</option>
						<option value="5">Equinoterapia</option>
						<option value="6">Equitación</option>
						<option value="7">Exposición</option>
						<option value="8">Hospedaje</option>
						<option value="9">Polo</option>
						<option value="10">Prueba Completa</option>
						<option value="11">Recreación</option>
						<option value="12">Reproducción</option>
						<option value="13">Salto</option>
						<option value="14">Trabajo</option>
						<option value="15">Vaulting</option>
					</select> 					
					
					<input type="hidden" id="nombreTipoActividad" name="nombreTipoActividad" />
			</div>
			
			<div data-linea="21">
				<label>Extensión Actividad (Ha.):</label>
				<input type="text" id="extensionActividad" name="extensionActividad" data-er="^[0-9.]+$" />
			</div>
				
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detalleTipoActividadCatastroPredioEquidosFS">
		<legend>Actividades Registradas</legend>
		<table id="detalleTipoActividadCatastroPredioEquidos">
			<thead>
				<tr>
				    <th width="15%">Tipo Actividad</th>
					<th width="15%">Extensión Actividad (ha.)</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($tipoActividad = pg_fetch_assoc($tipoActividadPredioEquidos)){
					echo $cpco->imprimirLineaTipoActividadPredioEquidos($tipoActividad['id_catastro_predio_equidos_tipo_actividad'], 
														$idCatastroPredioEquidos, $tipoActividad['tipo_actividad'], 
														$tipoActividad['extension_actividad'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleTipoActividadCatastroPredioEquidosConsultaFS">
		<legend>Actividades Registradas</legend>
		<table id="detalleTipoActividadCatastroPredioEquidosConsulta">
			<thead>
				<tr>
				    <th width="15%">Tipo Actividad</th>
					<th width="15%">Extensión Actividad (ha.)</th>
				</tr>
			</thead>
			<?php 
				while ($tipoActividad = pg_fetch_assoc($tipoActividadPredioEquidosConsulta)){
					echo $cpco->imprimirLineaTipoActividadPredioEquidosConsulta($tipoActividad['id_catastro_predio_equidos_tipo_actividad'], 
														$idCatastroPredioEquidos, $tipoActividad['tipo_actividad'], 
														$tipoActividad['extension_actividad'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Especies y Cantidad</h2>

	<form id="nuevaEspecieCatastroPredioEquidos" data-rutaAplicacion="programasControlOficial" data-opcion="guardarEspecieCatastroEquidos" data-destino="detalleItem">
		<input type='hidden' id='idCatastroPredioEquidos' name='idCatastroPredioEquidos' value="<?php echo $idCatastroPredioEquidos;?>" />
		
		<fieldset>
			<legend>Especie y Cantidad existente</legend>
			
			<div data-linea="23">
				<label>Especie:</label>
					<select id="especie" name="especie" required="required" >
						<option value="">Especie....</option>
						<?php 
							while ($fila = pg_fetch_assoc($especie)){
								echo  '<option value="'.$fila['id_especies']. '" data-codigo="'. $fila['codigo'] . '">'. $fila['nombre'] .'</option>';
							}
						?>
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
				<input type="text" id="numeroAnimales" name="numeroAnimales" data-er="^[0-9]+$" />
			</div>
				
					
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detalleEspecieCatastroPredioEquidosFS">
		<legend>Especie y Cantidad Existente Registrada</legend>
		<table id="detalleEspecieCatastroPredioEquidos">
			<thead>
				<tr>
				    <th width="15%">Especie</th>
					<th width="15%">Raza</th>
					<th width="10%">Categoría</th>
					<th width="10%">Número Animales</th>
					<!-- th width="5%">Eliminar</th-->
				</tr>
			</thead>
			<?php 
				while ($especie = pg_fetch_assoc($especiePredioEquidos)){
				    echo $cpco->imprimirLineaEspeciePredioEquidosConsulta($especie['id_catastro_predio_equidos_especie'], $idCatastroPredioEquidos, 
																	$especie['nombre_especie'], $especie['nombre_raza'], $especie['nombre_categoria'],
																	$especie['numero_animales'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleEspecieCatastroPredioEquidosConsultaFS">
		<legend>Especie y Cantidad Existente Registrada</legend>
		<table id="detalleEspecieCatastroPredioEquidosConsulta">
			<thead>
				<tr>
				    <th width="15%">Especie</th>
					<th width="15%">Raza</th>
					<th width="10%">Categoría</th>
					<th width="10%">Número Animales</th>
				</tr>
			</thead>
			<?php 
				while ($especie = pg_fetch_assoc($especiePredioEquidosConsulta)){
					echo $cpco->imprimirLineaEspeciePredioEquidosConsulta($especie['id_catastro_predio_equidos_especie'], $idCatastroPredioEquidos, 
																	$especie['nombre_especie'], $especie['nombre_raza'], $especie['nombre_categoria'],
																	$especie['numero_animales'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Bioseguridad</h2>

	<form id="nuevaBioseguridadCatastroPredioEquidos" data-rutaAplicacion="programasControlOficial" data-opcion="guardarBioseguridadPredioEquidos" data-destino="detalleItem">
		<input type='hidden' id='idCatastroPredioEquidos' name='idCatastroPredioEquidos' value="<?php echo $idCatastroPredioEquidos;?>" />
		
		<fieldset>
			<legend>Información de Bioseguridad</legend>
			
			<div data-linea="26">
				<label>Dispone de Bioseguridad?:</label>
					<select id="disponeBioseguridad" name="disponeBioseguridad" required="required" >
						<option value="">Dispone Bioseguridad....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 
								
			</div>
			
			<div data-linea="26">
				<label id="lBioseguridad">Bioseguridad:</label>
					<select id="bioseguridad" name="bioseguridad" required="required" disabled="disabled">
						<option value="">Bioseguridad...</option>
					</select> 
					
					<input type="hidden" id="nombreBioseguridad" name="nombreBioseguridad" />					
			</div>
			
			<div>
				<button id="guardarBioseguridad" type="submit" class="mas">Agregar</button>		
			</div>
		</fieldset>
		
		
	</form>
	
	<fieldset id="detalleBioseguridadCatastroPredioEquidosFS">
		<legend>Información de Bioseguridad Registrada</legend>
		<table id="detalleBioseguridadCatastroPredioEquidos">
			<thead>
				<tr>
				    <th width="15%">Bioseguridad</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($bioseguridad = pg_fetch_assoc($bioseguridadPredioEquidos)){
					echo $cpco->imprimirLineaBioseguridadPredioEquidos($bioseguridad['id_catastro_predio_equidos_bioseguridad'], 
																$idCatastroPredioEquidos, $bioseguridad['bioseguridad'], 
																$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleBioseguridadCatastroPredioEquidosConsultaFS">
		<legend>Información de Bioseguridad Registrada</legend>
		<table id="detalleBioseguridadCatastroPredioEquidosConsulta">
			<thead>
				<tr>
				    <th width="15%">Bioseguridad</th>
				</tr>
			</thead>
			<?php 
				while ($bioseguridad = pg_fetch_assoc($bioseguridadPredioEquidosConsulta)){
					echo $cpco->imprimirLineaBioseguridadPredioEquidosConsulta($bioseguridad['id_catastro_predio_equidos_bioseguridad'], 
																$idCatastroPredioEquidos, $bioseguridad['bioseguridad'], 
																$ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Sanidad, Infraestructura y Manejo Animal</h2>

	<form id="nuevaSanidadCatastroPredioEquidos" data-rutaAplicacion="programasControlOficial" data-opcion="guardarSanidadPredioEquidos" data-destino="detalleItem">
		<input type='hidden' id='idCatastroPredioEquidos' name='idCatastroPredioEquidos' value="<?php echo $idCatastroPredioEquidos;?>" />
		
		<fieldset>
			<legend>Información de Sanidad, Infraestructura y Manejo Animal</legend>
			
			<div data-linea="27">
				<label>Profesional Técnico Responsable:</label>
				<input type="text" id="profesionalTecnico" name="profesionalTecnico" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
					
		<hr />
			
			<div data-linea="28">
				<label>Pesebreras:</label>
					<select id="pesebreras" name="pesebreras" required="required" >
						<option value="">Pesebreras....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
									
			</div>
			
			<div data-linea="28">
				<label>Área de Cuarentena:</label>
					<select id="areaCuarentena" name="areaCuarentena" required="required" >
						<option value="">Área Cuarentena....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 					
			</div>
			
			<div data-linea="29">
				<label>Eliminación de Desechos:</label>
					<select id="eliminacionDesechos" name="eliminacionDesechos" required="required" >
						<option value="">Eliminación Desechos....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 					
			</div>
			
			<div data-linea="29">
				<label>Control de Vectores:</label>
					<select id="controlVectores" name="controlVectores" required="required" >
						<option value="">Control Vectores....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 					
			</div>
			
			<div data-linea="30">
				<label>Uso de Aperos Individuales:</label>
					<select id="usoAperosIndividuales" name="usoAperosIndividuales" required="required" >
						<option value="">Uso Aperos....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 					
			</div>
			
			<div data-linea="30">
				<label>Reporte Positivo AIE:</label>
					<select id="reportePositivoAIE" name="reportePositivoAIE" required="required" >
						<option value="">Reporte AIE....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 					
			</div>
			
			<div data-linea="31">
				<label>Medida Sanitaria Aplicada:</label>
					<select id="medidaSanitaria" name="medidaSanitaria" >
						<option value="">Medida Sanitaria....</option>
						<option value="1">Marcaje</option>
						<option value="2">Sacrificio</option>
					</select> 
					
					<input type='hidden' id='nombreMedidaSanitaria' name='nombreMedidaSanitaria' />					
			</div>
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
		</fieldset>
		
		
	</form>
	
	<fieldset id="detalleSanidadCatastroPredioEquidosFS">
		<legend>Información de Sanidad, Infraestructura y Manejo Registrado</legend>
		<table id="detalleSanidadCatastroPredioEquidos">
			<thead>
				<tr>
				    <th width="15%">Profesional Técnico</th>
				    <th width="15%">Pesebreras</th>
				    <th width="15%">Área Cuarentena</th>
				    <th width="15%">Eliminación Desechos</th>
				    <th width="15%">Control Vectores</th>
					<th width="15%">Uso Aperos</th>
					<th width="15%">Reporte Positivo AIE</th>
					<th width="15%">Medida Sanitaria</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($sanidad = pg_fetch_assoc($sanidadPredioEquidos)){
					echo $cpco->imprimirLineaSanidadPredioEquidos($sanidad['id_catastro_predio_equidos_sanidad'],	$idCatastroPredioEquidos,  
														$sanidad['profesional_tecnico'], $sanidad['pesebreras'], $sanidad['area_cuarentena'], 
														$sanidad['eliminacion_desechos'], $sanidad['control_vectores'], $sanidad['uso_aperos_individuales'], 
														$sanidad['reporte_positivo_aie'], $sanidad['medida_sanitaria'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleSanidadCatastroPredioEquidosConsultaFS">
		<legend>Información de Sanidad, Infraestructura y Manejo Registrado</legend>
		<table id="detalleSanidadCatastroPredioEquidosConsulta">
			<thead>
				<tr>
				    <th width="15%">Profesional Técnico</th>
				    <th width="15%">Pesebreras</th>
				    <th width="15%">Área Cuarentena</th>
				    <th width="15%">Eliminación Desechos</th>
				    <th width="15%">Control Vectores</th>
					<th width="15%">Uso Aperos</th>
					<th width="15%">Reporte Positivo AIE</th>
					<th width="15%">Medida Sanitaria</th>
				</tr>
			</thead>
			<?php 
				while ($sanidad = pg_fetch_assoc($sanidadPredioEquidosConsulta)){
					echo $cpco->imprimirLineaSanidadPredioEquidosConsulta($sanidad['id_catastro_predio_equidos_sanidad'],	$idCatastroPredioEquidos,  
														$sanidad['profesional_tecnico'], $sanidad['pesebreras'], $sanidad['area_cuarentena'], 
														$sanidad['eliminacion_desechos'], $sanidad['control_vectores'], $sanidad['uso_aperos_individuales'], 
														$sanidad['reporte_positivo_aie'], $sanidad['medida_sanitaria'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>


<div class="pestania">

	<h2>Historial de Patologías en el Predio</h2>

	<form id="nuevoHistorialPatologiasCatastroPredioEquidos" data-rutaAplicacion="programasControlOficial" data-opcion="guardarHistorialPatologiasCatastroPredioEquidos" data-destino="detalleItem">
		<input type='hidden' id='idCatastroPredioEquidos' name='idCatastroPredioEquidos' value="<?php echo $idCatastroPredioEquidos;?>" />
		
		<fieldset>
			<legend>Enfermedades Presentadas</legend>
			
			<div data-linea="37">
				<label>Tipo de Enfermedad:</label>
					<select id="enfermedad" name="enfermedad" required="required" >
						<option value="">Tipo Enfermedad....</option>
						<option value="1">Ectoparasitosis</option>
						<option value="2">Endoparasitosis</option>
						<option value="3">Hormigueo</option>
						<option value="4">Intoxicación</option>
						<option value="5">Laminitis</option>
						<option value="6">Neumonía</option>
						<option value="7">Piroplasmosis</option>
						<option value="8">Problemas Podales</option>
						<option value="9">Tétanos</option>
						<option value="10">No Aplica</option>
						<option value="0">Otra</option>
						
					</select> 					
			</div>
			
			<div data-linea="37">
				<input type="text" id="nombreEnfermedad" name="nombreEnfermedad" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
			</div>
			
			<hr />
			
			<div data-linea="38">
				<label>Vacunas:</label>
					<select id="vacuna" name="vacuna" required="required" >
						<option value="">Vacuna....</option>
						<option value="1">EEE</option>
						<option value="2">EEO</option>
						<option value="3">EEV</option>
						<option value="4">Influenza</option>
						<option value="5">Oeste del Nilo</option>
						<option value="6">Rinoneumonía</option>
						<option value="7">Tétanos</option>
						<option value="8">Artritis Viral</option>
						<option value="9">No Aplica</option>
						<option value="0">Otra</option>
						
					</select> 					
			</div>
			
			<div data-linea="38">
				<input type="text" id="nombreVacuna" name="nombreVacuna" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
			</div>
			
			<div data-linea="39">
				<label>Laboratorio:</label>
				<input type="text" id="laboratorio" name="laboratorio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
			</div>
				
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detalleHistorialPatologiasCatastroPredioEquidosFS">
		<legend>Historial de Patologías Registradas</legend>
		<table id="detalleHistorialPatologiasCatastroPredioEquidos">
			<thead>
				<tr>
				    <th width="15%">Enfermedad</th>
					<th width="15%">Vacuna</th>
					<th width="15%">Laboratorio</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($enfermedad = pg_fetch_assoc($historialPatologiaPredioEquidos)){
					echo $cpco->imprimirLineaHistorialPatologiaPredioEquidos($enfermedad['id_catastro_predio_equidos_historial_patologias'], 
																	$idCatastroPredioEquidos, $enfermedad['enfermedad'], 
																	$enfermedad['vacuna'], $enfermedad['laboratorio'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleHistorialPatologiasCatastroPredioEquidosConsultaFS">
		<legend>Historial de Patologías Registradas</legend>
		<table id="detalleHistorialPatologiasCatastroPredioEquidosConsulta">
			<thead>
				<tr>
				    <th width="15%">Enfermedad</th>
					<th width="15%">Vacuna</th>
					<th width="15%">Laboratorio</th>
				</tr>
			</thead>
			<?php 
				while ($enfermedad = pg_fetch_assoc($historialPatologiaPredioEquidosConsulta)){
					echo $cpco->imprimirLineaHistorialPatologiaPredioEquidosConsulta($enfermedad['id_catastro_predio_equidos_historial_patologias'], 
																	$idCatastroPredioEquidos, $enfermedad['enfermedad'], 
																	$enfermedad['vacuna'], $enfermedad['laboratorio'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>



<div class="pestania">

	<h2>Finalizar Catastro</h2>

	<form id="cerrarCatastroPredioEquidos" data-rutaAplicacion="programasControlOficial" data-opcion="guardarCierreCatastroPredioEquidos" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type='hidden' id='idCatastroPredioEquidos' name='idCatastroPredioEquidos' value="<?php echo $idCatastroPredioEquidos;?>" />
		
		<fieldset>
			<legend>Cerrar Catastro</legend>
			
			<div data-linea="41">
				<label>Observaciones:</label>
				<input type="text" id="observaciones" name="observaciones" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
			</div>

		</fieldset>
	
		<div data-linea="44">
			<button id="guardarCatastro" type="submit" class="guardar">Guardar</button>
		</div>
	</form>	
	
	<fieldset id="cerrarCatastroPredioEquidosConsulta">
		<legend>Cerrar Catastro</legend>
		
		<div data-linea="28">
			<label>Observaciones: </label>
			<?php echo $catastroPredioEquidos['observaciones'];?>
		</div>

	</fieldset>
</div>

<script type="text/javascript">

var usuario = <?php echo json_encode($usuario); ?>;
var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;
var estado= <?php echo json_encode($catastroPredioEquidos['estado']); ?>;
var perfil= <?php echo json_encode($perfilAdmin); ?>;

var array_razaEquinos= <?php echo json_encode($razaEquinos); ?>;
var array_razaEquidos= <?php echo json_encode($razaEquidos); ?>;

var array_categoriaEquinos= <?php echo json_encode($categoriaEquinos); ?>;
var array_categoriaEquidos= <?php echo json_encode($categoriaEquidos); ?>;

var array_opcionBioseguridad= <?php echo json_encode($opcionBioseguridad); ?>;
var array_sinOpcionBioseguridad= <?php echo json_encode($sinOpcionBioseguridad); ?>;

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

		$('#nombreRaza').hide();
		$('#nombreEnfermedad').hide();
		$('#nombreVacuna').hide();

		
		$('#detalleMotivoCatastroPredioEquidosConsultaFS').hide();
		$('#detalleTipoActividadCatastroPredioEquidosConsultaFS').hide();
		$('#detalleEspecieCatastroPredioEquidosConsultaFS').hide();
		$('#detalleBioseguridadCatastroPredioEquidosConsultaFS').hide();
		$('#detalleSanidadCatastroPredioEquidosConsultaFS').hide();
		$('#detalleHistorialPatologiasCatastroPredioEquidosConsultaFS').hide();
		$('#cerrarCatastroPredioEquidos').show();
		$('#cerrarCatastroPredioEquidosCerrado').hide();
		$('#cerrarCatastroPredioEquidosConsulta').hide();
		
		acciones("#nuevoMotivoCatastroPredioEquidos","#detalleMotivoCatastroPredioEquidos");
		acciones("#nuevoTipoActividadCatastroPredioEquidos","#detalleTipoActividadCatastroPredioEquidos");
		acciones("#nuevaEspecieCatastroPredioEquidos","#detalleEspecieCatastroPredioEquidos");
		acciones("#nuevaBioseguridadCatastroPredioEquidos","#detalleBioseguridadCatastroPredioEquidos");
		acciones("#nuevaSanidadCatastroPredioEquidos","#detalleSanidadCatastroPredioEquidos");
		acciones("#nuevoHistorialPatologiasCatastroPredioEquidos","#detalleHistorialPatologiasCatastroPredioEquidos");

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
				$('#cerrarCatastroPredioEquidosConsulta').show();
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
		$("#adjuntos").hide();
		$("#adjuntosInforme").hide();		
	});
	
	//Validación y Guardado
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	$("#modificarCatastroPredioEquidos").submit(function(event){

		$("#modificarCatastroPredioEquidos").attr('data-opcion', 'modificarCatastroPredioEquidos');
	    $("#modificarCatastroPredioEquidos").attr('data-destino', 'detalleItem');

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

		if(!$.trim($("#telefonoPropietario").val()) || !esCampoValido("#telefonoPropietario")){
			error = true;
			$("#telefonoPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#correoElectronicoPropietario").val()) || !esCampoValido("#correoElectronicoPropietario")){
			error = true;
			$("#correoElectronicoPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#nombreAdministrador").val()) || !esCampoValido("#nombreAdministrador")){
			error = true;
			$("#nombreAdministrador").addClass("alertaCombo");
		}

		if(!$.trim($("#cedulaAdministrador").val()) || !esCampoValido("#cedulaAdministrador")){
			error = true;
			$("#cedulaAdministrador").addClass("alertaCombo");
		}

		if(!$.trim($("#telefonoAdministrador").val()) || !esCampoValido("#telefonoAdministrador")){
			error = true;
			$("#telefonoAdministrador").addClass("alertaCombo");
		}

		if(!$.trim($("#correoElectronicoAdministrador").val()) || !esCampoValido("#correoElectronicoAdministrador")){
			error = true;
			$("#correoElectronicoAdministrador").addClass("alertaCombo");
		}

		if(!$.trim($("#direccionPredio").val()) || !esCampoValido("#direccionPredio")){
			error = true;
			$("#direccionPredio").addClass("alertaCombo");
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

		if(!$.trim($("#extension").val()) || !esCampoValido("#extension")){
			error = true;
			$("#extension").addClass("alertaCombo");
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

	//Planificación de Inspección
	$("#cerrarCatastroPredioEquidos").submit(function(event){

		$("#cerrarCatastroPredioEquidos").attr('data-opcion', 'guardarCierreCatastroPredioEquidos');
	    $("#cerrarCatastroPredioEquidos").attr('data-destino', 'detalleItem');

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

	//Especies, Razas y Categorías de Animales en el Predio
	$("#especie").change(function(event){
    	sraza = '<option value="">Raza...</option>';

		switch($("#especie option:selected").attr("data-codigo")) {
		    case 'EQUIN':
		    	sraza += array_razaEquinos;		    	
		        break;
		    case 'EQUID':
		    	sraza += array_razaEquidos;
		        break;
		    default:
		    	sraza = '<option value="">Raza...</option>';
	    		break;
		}	    
	   	
	    $('#raza').html(sraza);
	    $("#raza").removeAttr("disabled");

	    scategoria ='0';
    	scategoria = '<option value="">Categoría...</option>';

    	switch($("#especie option:selected").attr("data-codigo")) {
	    case 'EQUIN':
	    	scategoria += array_categoriaEquinos;		    	
	        break;
	    case 'EQUID':
	    	scategoria += array_categoriaEquidos;
	        break;
	    default:
	    	scategoria = '<option value="">Categoría...</option>';
    		break;
		}	    
	   	
	    $('#categoria').html(scategoria);
	    $("#categoria").removeAttr("disabled");

	    $("#nombreEspecie").val($("#especie option:selected").text());	
	}); 

	$("#raza").change(function(event){
		if($("#raza option:selected").text()!='Otra'){
        	$('#nombreRaza').hide();
    		$("#nombreRaza").val($("#raza option:selected").text());
        }else{
        	$("#nombreRaza").val('');
    	    $('#nombreRaza').show();
        }	    		
	});

	$("#categoria").change(function(event){
    	$("#nombreCategoria").val($("#categoria option:selected").text());	
	}); 

	$("#motivoCatastro").change(function(){
    	$("#nombreMotivoCatastro").val($("#motivoCatastro option:selected").text());
	});

	$("#actividad").change(function(){
    	$("#nombreTipoActividad").val($("#actividad option:selected").text());
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
    		
    		if ($("#vacuna option:selected").text()=='No Aplica'){
    			$("#laboratorio").val('No Aplica');
    			$("#laboratorio").attr('readonly', 'readonly');
    		}else{
    			$("#laboratorio").removeAttr('readonly');
    		}
        }else{
        	$("#nombreVacuna").val('');
    	    $('#nombreVacuna').show();
        }
	});

	$("#disponeBioseguridad").change(function(event){
    	sbioseguridad ='0';
		sbioseguridad = '<option value="">Bioseguridad...</option>';

		switch($("#disponeBioseguridad option:selected").text()) {
		    case 'Si':
		    	for(var i=0;i<array_opcionBioseguridad.length;i++){
		    		sbioseguridad += '<option value="'+(i+1)+'">'+array_opcionBioseguridad[i]+'</option>';
			   	}

		    	$('#bioseguridad').html(sbioseguridad);
			    $("#bioseguridad").removeAttr("disabled");
			    $("#guardarBioseguridad").removeAttr("disabled");
			    $("#detalleBioseguridadCatastroPredioEquidosFS").show();
		    	
		        break;
		        
		    case 'No':
		    	for(var i=0;i<array_sinOpcionBioseguridad.length;i++){
		    		sbioseguridad += '<option value="'+(i+1)+'">'+array_sinOpcionBioseguridad[i]+'</option>';
			   	}

		    	$('#bioseguridad').html(sbioseguridad);
			    $("#bioseguridad").removeAttr("disabled");
			    $("#guardarBioseguridad").removeAttr("disabled");
			    $("#detalleBioseguridadCatastroPredioEquidosFS").show();

		        break;
		    default:
		}
	    
	}); 

	$("#bioseguridad").change(function(){
		$("#nombreBioseguridad").val($("#bioseguridad option:selected").text());
	});

	/*$("#nuevaBioseguridadCatastroPredioEquidos").submit(function(event){
		event.preventDefault();

		if($("#bioseguridad option:selected").text() == 'No Aplica'){
			$("#detalleBioseguridadCatastroPredioEquidos td").html("");
		}
	});*/

	$("#archivo").click(function(){
    	$("#subirArchivo button").removeAttr("disabled");
    });

	$("#archivoInforme").click(function(){
    	$("#subirArchivoInforme button").removeAttr("disabled");
    });

	$("#nuevaEspecieCatastroPredioEquidos button").click(function(){
    	$("#detalleEspecieCatastroPredioEquidos tbody").html("");
    });

	$("#numeroPropietario").change(function(event){

		$("#cedulaPropietario").val($("#numeroPropietario").val());
		
		if($("#numeroPropietario").val().length == $("#numeroPropietario").attr("maxlength")){
			$("#clasificacionPropietario").val('Juridica');
			
		}else if($("#numeroPropietario").text().lenght == 10){
			$("#clasificacionPropietario").val('Cédula');
		}else{
			$("#clasificacionPropietario").val('Cédula');
		}
		
		event.preventDefault();
		var $botones = $("form").find("button[type='submit']"),
    	serializedData = $("#datosConsultaWebServices").serialize(),
    	url = "aplicaciones/general/consultaWebServices.php";
		
    	$botones.attr("disabled", "disabled");
    	$('#nombrePropietario').val('');

	     resultado = $.ajax({
		    url: url,
		    type: "post",
		    data: serializedData,
		    dataType: "json",
		    async:   true,
		    beforeSend: function(){
		    	$("#estado").html('').removeClass();
		    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
			},
			
		    success: function(msg){
		    	if(msg.estado=="exito"){
			    	$(msg.valores).each(function(i){
			    		if($("#clasificacionPropietario").val() == 'Cédula'){
			    			$('#nombrePropietario').val(this.Nombre);
			    		}else if($("#clasificacionPropietario").val() == 'Juridica'){
			    			$('#nombrePropietario').val(this.razonSocial);
			    		}else{
			    			$('#nombrePropietario').val(this.Nombre);
			    		}
			    		
				    });	
		    	}else{
		    		mostrarMensaje(msg.mensaje,"FALLO");
			    }
		   },
		    error: function(jqXHR, textStatus, errorThrown){
		    	$("#cargando").delay("slow").fadeOut();
		    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
		    },
	        complete: function(){
	        	$("#cargando").delay("slow").fadeOut();
	        	$botones.removeAttr("disabled");	
	        }
		});

	});


	$("#numeroAdministrador").change(function(event){

		$("#cedulaAdministrador").val($("#numeroAdministrador").val());
		
		if($("#numeroAdministrador").val().length == $("#numeroAdministrador").attr("maxlength")){
			$("#clasificacionAdministrador").val('Juridica');
		}else if($("#numeroAdministrador").text().lenght == 10){
			$("#clasificacionAdministrador").val('Cédula');
		}else{
			$("#clasificacionAdministrador").val('Cédula');
		}
		
		event.preventDefault();
		var $botones = $("form").find("button[type='submit']"),
    	serializedData = $("#datosConsultaWebServicesAdministrador").serialize(),
    	url = "aplicaciones/general/consultaWebServices.php";
		
    	$botones.attr("disabled", "disabled");
    	$('#nombreAdministrador').val('');

	     resultado = $.ajax({
		    url: url,
		    type: "post",
		    data: serializedData,
		    dataType: "json",
		    async:   true,
		    beforeSend: function(){
		    	$("#estado").html('').removeClass();
		    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
			},
			
		    success: function(msg){
		    	if(msg.estado=="exito"){
			    	$(msg.valores).each(function(i){
			    		if($("#clasificacionAdministrador").val() == 'Cédula'){
			    			$('#nombreAdministrador').val(this.Nombre);
			    		}else if($("#clasificacionAdministrador").val() == 'Juridica'){
			    			$('#nombreAdministrador').val(this.razonSocial);
			    		}else{
			    			$('#nombreAdministrador').val(this.Nombre);
			    		}
			    		
				    });	
		    	}else{
		    		mostrarMensaje(msg.mensaje,"FALLO");
			    }
		   },
		    error: function(jqXHR, textStatus, errorThrown){
		    	$("#cargando").delay("slow").fadeOut();
		    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
		    },
	        complete: function(){
	        	$("#cargando").delay("slow").fadeOut();
	        	$botones.removeAttr("disabled");	
	        }
		});

	});
</script>