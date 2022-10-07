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
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Programas Control Oficial Control de Vectores'),0,'id_perfil');
	}
	
	$ruta = 'programasControlOficial';
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	
	$idControlVectores = $_POST['id'];
	$controlVectores = pg_fetch_assoc($cpco->abrirControlVectores($conexion, $idControlVectores));
	
	$especiesAtacadasControlVectores = $cpco->listarEspeciesAtacadasControlVectores($conexion, $idControlVectores);
	$especiesAtacadasControlVectoresConsulta = $cpco->listarEspeciesAtacadasControlVectores($conexion, $idControlVectores);
	
	$quiropterosCapturadosControlVectores = $cpco->listarQuiropterosCapturadosControlVectores($conexion, $idControlVectores);
	$quiropterosCapturadosControlVectoresConsulta = $cpco->listarQuiropterosCapturadosControlVectores($conexion, $idControlVectores);
	
	$quiropterosTratadosControlVectores = $cpco->listarQuiropterosTratadosControlVectores($conexion, $idControlVectores);
	$quiropterosTratadosControlVectoresConsulta = $cpco->listarQuiropterosTratadosControlVectores($conexion, $idControlVectores);
	
	$sitiosCapturaControlVectores = $cpco->listarSitiosCapturaControlVectores($conexion, $idControlVectores);
	$sitiosCapturaControlVectoresConsulta = $cpco->listarSitiosCapturaControlVectores($conexion, $idControlVectores);
?>

<header>
	<h1>Control de Vectores con uso de Mallas Tipo Neblina</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>

<div class="pestania">
	<h2>Control de Vectores</h2>

<form id="modificarControlVectores" data-rutaAplicacion="programasControlOficial" data-opcion="modificarControlVectores" data-destino="detalleItem">
		<p>
			<button id="modificar" type="button" class="editar">Modificar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</p>
		
	<div id="informacion">
		<fieldset>
			<legend>Información de Identificación del Sitio</legend>
				
				<div data-linea="0">
					<label>N° Solicitud:</label>
					<?php echo $controlVectores['num_solicitud'];?>
				</div>
				
				<div data-linea="1">
					<label>Fecha registro:</label>
					<?php echo date('j/n/Y',strtotime($controlVectores['fecha']));?>
				</div>
				
				<div data-linea="2">
					<label>Fase lunar:</label>
					<?php echo $controlVectores['fase_lunar'];?>
				</div>
				
				<div data-linea="2">
					<label>Duración:</label>
					<?php echo $controlVectores['duracion'];?>
				</div>
				
				<div data-linea="3">
					<label>Fecha desde:</label>
					<?php echo date('j/n/Y',strtotime($controlVectores['fecha_desde']));?>
				</div>
				
				<div data-linea="3">
					<label>Fecha hasta:</label>
					<?php echo date('j/n/Y',strtotime($controlVectores['fecha_hasta']));?>
				</div>
				
				<div data-linea="4">
					<label>Nombre del Predio:</label>
					<?php echo $controlVectores['nombre_predio'];?>
				</div>
				
				<div data-linea="4">
					<label>Nombre del Propietario:</label>
					<?php echo $controlVectores['nombre_propietario'];?>
				</div>
		
			</fieldset>
			
			<fieldset>
				<legend>Ubicación y Datos Generales</legend>
		
				<div data-linea="4">
					<label>Provincia: </label>
					<?php echo $controlVectores['provincia'];?>	
				</div>
					
				<div data-linea="4">
					<label>Cantón: </label>
						<?php echo $controlVectores['canton'];?>
					</div>
					
					<div data-linea="5">	
					<label>Parroquia: </label>
						<?php echo $controlVectores['parroquia'];?>
					</div>
						
				<div data-linea="5">
					<label>Sitio:</label>
					<?php echo $controlVectores['sitio'];?>
				</div>
								
			</fieldset>
			
			<fieldset>
				<legend>Información del Sitio de Captura</legend>
		
				<div data-linea="6">
					<label>Sitio de Captura:</label>
					<?php echo $controlVectores['sitio_captura'];?>	
				</div>
					
				<div data-linea='6'>
					<label>Cobertura Total del Corral: </label>
					<?php echo $controlVectores['cobertura_corral'];?>
				</div>
				
			</fieldset>
			
			<fieldset>
				<legend>Coordenadas</legend>
		
				<div data-linea="7">
					<label>X:</label>
					<?php echo $controlVectores['utm_x'];?>
				</div>
				
				<div data-linea="7">
					<label>Y:</label>
					<?php echo $controlVectores['utm_y'];?>
				</div>
				
				<div data-linea="7">
					<label>Z:</label>
					<?php echo $controlVectores['utm_z'];?>
				</div>
				
				<!-- div data-linea="8">
					<label>Altitud:</label>
					< ?php echo $controlVectores['altitud']?>
				</div-->
				
			</fieldset>
			
			
		</div>
	
	<div id="actualizacion">
			<input type='hidden' id='idControlVectores' name='idControlVectores' value="<?php echo $idControlVectores;?>" />	
		
			<fieldset>
				<legend>Información de Identificación del Refugio</legend>
		
				<div data-linea="10">
					<label>N° Solicitud:</label>
					<?php echo $controlVectores['num_solicitud'];?>
				</div>
				
				<div data-linea="11">
					<label>Fecha:</label>
					<input type="text" id="fecha" name="fecha" value="<?php echo date('j/n/Y',strtotime($controlVectores['fecha']));?>" />
				</div>
				
				<div data-linea="12">
					<label>Fase lunar:</label>
						<select id="faseLunar" name="faseLunar" required="required">
							<option value="">Fase lunar....</option>
							<option value="1">Luna nueva</option>
							<option value="2">Cuarto creciente</option>
							<option value="3">Luna llena</option>
							<option value="4">Cuarto menguante</option>						
						</select>
					
					<input type="hidden" id="nombreFaseLunar" name="nombreFaseLunar" value="<?php echo $controlVectores['fase_lunar'];?>"/>
				</div>
				
				<div data-linea="12">
					<label>Duración:</label>
					<input type="text" id="duracion" name="duracion" value="<?php echo $controlVectores['duracion'];?>"/>
				</div>
		
				<div data-linea="13">
					<label>Fecha Desde:</label>
					<input type="text" id="fechaDesde" name="fechaDesde" value="<?php echo date('j/n/Y',strtotime($controlVectores['fecha_desde']));?>"/>
				</div>
				
				<div data-linea="13">
					<label>Fecha Hasta:</label>
					<input type="text" id="fechaHasta" name="fechaHasta" value="<?php echo date('j/n/Y',strtotime($controlVectores['fecha_hasta']));?>"/>
				</div>
		
				<div data-linea="14">
					<label>Nombre del Predio:</label>
					<input type="text" id="nombrePredio" name="nombrePredio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $controlVectores['nombre_predio'];?>" />
				</div>
				
				<div data-linea="14">
					<label>Nombre del Propietario:</label>
					<input type="text" id="nombrePropietario" name="nombrePropietario" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $controlVectores['nombre_propietario'];?>" />
				</div>
		
			</fieldset>
			
			<fieldset>
				<legend>Ubicación y Datos Generales</legend>
		
				<div data-linea="15">
					<label>Provincia: </label>
					<?php echo $controlVectores['provincia'];?>	
				</div>
					
				<div data-linea="15">
					<label>Cantón: </label>
						<?php echo $controlVectores['canton'];?>
					</div>
					
					<div data-linea="16">	
					<label>Parroquia: </label>
						<?php echo $controlVectores['parroquia'];?>
					</div>
						
				<div data-linea="16">
					<label>Sitio:</label>
					<input type="text" id="sitio" name="sitio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $controlVectores['sitio'];?>"/>
				</div>
				
			</fieldset>
			
			<fieldset>
				<legend>Información del Sitio de Captura</legend>
		
				<div data-linea="6">
					<label>Sitio de Captura:</label>
					<?php echo $controlVectores['sitio_captura'];?>	
				</div>
					
				<div data-linea='6'>
					<label>Cobertura Total del Corral: </label>
					<?php echo $controlVectores['cobertura_corral'];?>
				</div>
					
			</fieldset>
	
			<fieldset>
				<legend>Coordenadas</legend>				
		
				<div data-linea="20">
					<label>X:</label>
					<input type="text" id="x" name="x" maxlength="6" data-er="^[0-9]+$" value="<?php echo $controlVectores['utm_x'];?>"/>
				</div>
				
				<div data-linea="20">
					<label>Y:</label>
					<input type="text" id="y" name="y" maxlength="7" data-er="^[0-9]+$" value="<?php echo $controlVectores['utm_y'];?>"/>
				</div>
				
				<div data-linea="20">
					<label>Z:</label>
					<input type="text" id="z" name="z" maxlength="4" data-er="^[0-9]+$" value="<?php echo $controlVectores['utm_z'];?>"/>
				</div>
				
				<!-- div data-linea="21">
					<label>Altitud:</label>
					<input type="text" id="altitud" name="altitud" maxlength="16" data-er="^[0-9.]+$" value="< ?php echo $controlVectores['altitud'];?>"/>
				</div-->
		
			</fieldset>
	
		</div>
	</form>
	
	<fieldset id="adjuntos">
				<legend>Mapa de Ubicación</legend>
		
					<div data-linea="1">
						<label>Mapa:</label>
						<?php echo ($controlVectores['imagen_mapa']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$controlVectores['imagen_mapa'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Mapa cargado</a>')?>
					</div>
					
					<form id="subirArchivo" action="aplicaciones/programasControlOficial/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
						
						<input type="file" name="archivo" id="archivo" accept="application/pdf" /> 
						<input type="hidden" name="id" value="<?php echo $controlVectores['id_control_vectores'];?>" />
						<input type="hidden" name="aplicacion" value="ControlVectores" /> 
						
						<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
					</form>
					<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
			</fieldset>
			
			<fieldset id="adjuntosInforme">
				<legend>Informe</legend>
		
					<div data-linea="1">
						<label>Informe:</label>
						<?php echo ($controlVectores['ruta_informe']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$controlVectores['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe cargado</a>')?>
					</div>
					
					<form id="subirArchivoInforme" action="aplicaciones/programasControlOficial/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergenteInforme" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
						
						<input type="file" name="archivo" id="archivoInforme" accept="application/pdf" /> 
						<input type="hidden" name="id" value="<?php echo $controlVectores['id_control_vectores'];?>" />
						<input type="hidden" name="aplicacion" value="InformeControlVectores" /> 
						
						<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
					</form>
					<iframe name="ventanaEmergenteInforme" class="ventanaEmergente"></iframe>
			</fieldset>
			
</div>

<div class="pestania">

	<h2>Especies Atacadas en el Predio / Refugio</h2>

	<form id="nuevaEspecieAtacadaControlVectores" data-rutaAplicacion="programasControlOficial" data-opcion="guardarEspecieAtacadaControlVectores" data-destino="detalleItem">
		<input type='hidden' id='idControlVectores' name='idControlVectores' value="<?php echo $idControlVectores;?>" />
		
		<fieldset>
			<legend>Especies Atacadas</legend>
			
			<div data-linea="23">
				<label>Especie:</label>
					<select id="especie" name="especie" required="required" >
						<option value="">Especie....</option>
						<option value="1">Bovinos</option>
						<option value="2">Equinos</option>
						<option value="3">Porcinos</option>
						<option value="4">Ovinos</option>
						<option value="0">Otros</option>	
					</select> 	
								
			</div>
			
			<div data-linea="23">	
				<input type="text" id="nombreEspecie" name="nombreEspecie" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			
			<div data-linea="24">
				<label># existente en el predio:</label>
				<input type="text" id="especieExistente" name="especieExistente" required="required" data-er="^[0-9]+$" />
			</div>	
			
			<div data-linea="24">
				<label># con mordeduras:</label>
				<input type="text" id="especieMordeduras" name="especieMordeduras" required="required" data-er="^[0-9]+$" />
			</div>
									
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detalleEspecieAtacadaControlVectoresFS">
		<legend>Detalle de Especies Atacadas</legend>
		<table id="detalleEspecieAtacadaControlVectores">
			<thead>
				<tr>
				    <th width="15%">Especie</th>
					<th width="15%"># Existentes predio</th>
					<th width="10%"># con Mordeduras</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($especiesAtacadas = pg_fetch_assoc($especiesAtacadasControlVectores)){
					echo $cpco->imprimirLineaEspecieAtacadaControlVectores($especiesAtacadas['id_control_vectores_especie_atacada'], 
																		$especiesAtacadas['id_control_vectores'], $especiesAtacadas['especie'], 
																		$especiesAtacadas['existencia_predio'], $especiesAtacadas['animales_mordeduras'],
																		$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleEspecieAtacadaControlVectoresConsultaFS">
		<legend>Detalle de Especies Atacadas</legend>
		<table id="detalleEspecieAtacadaControlVectoresConsulta">
			<thead>
				<tr>
				    <th width="15%">Especie</th>
					<th width="15%"># Existentes predio</th>
					<th width="10%"># con Mordeduras</th>
				</tr>
			</thead>
			<?php 
				while ($especiesAtacadas = pg_fetch_assoc($especiesAtacadasControlVectoresConsulta)){
					echo $cpco->imprimirLineaEspecieAtacadaControlVectoresConsulta($especiesAtacadas['id_control_vectores_especie_atacada'], 
																		$especiesAtacadas['id_control_vectores'], $especiesAtacadas['especie'], 
																		$especiesAtacadas['existencia_predio'], $especiesAtacadas['animales_mordeduras'],
																		$ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Quirópteros Capturados</h2>

	<form id="nuevoQuiropteroCapturadoControlVectores" data-rutaAplicacion="programasControlOficial" data-opcion="guardarQuiropteroCapturadoControlVectores" data-destino="detalleItem">
		<input type='hidden' id='idControlVectores' name='idControlVectores' value="<?php echo $idControlVectores;?>" />
		
		<fieldset>
			<legend>Especies Capturadas</legend>
			
			<div data-linea="25">
				<label>Quirópteros:</label>
					<select id="quiropteros" name="quiropteros" required="required" >
						<option value="">Quiróptero....</option>
						<option value="1">Hembras Vampiros Gestantes</option>
						<option value="2">Hembras Vampiros Vacías</option>
						<option value="3">Machos Vampiros</option>
					</select> 	
					
					<input type="hidden" id="nombreQuiropteros" name="nombreQuiropteros" />				
			</div>
			
			<div data-linea="25">
				<label># quirópteros:</label>
				<input type="text" id="numeroQuiropteros" name="numeroQuiropteros" required="required" data-er="^[0-9]+$" />
			</div>
									
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detalleQuiropterosCapturadosControlVectoresFS">
		<legend>Detalle de Quirópteros Capturados</legend>
		<table id="detalleQuiropterosCapturadosControlVectores">
			<thead>
				<tr>
				    <th width="15%">Quirópteros</th>
					<th width="15%"># Capturas</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($quiropterosCapturados = pg_fetch_assoc($quiropterosCapturadosControlVectores)){
					echo $cpco->imprimirLineaQuiropterosCapturadosControlVectores($quiropterosCapturados['id_control_vectores_quiropteros_capturados'], 
															$idControlVectores, $quiropterosCapturados['quiroptero'], 
															$quiropterosCapturados['num_quiropteros'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleQuiropterosCapturadosControlVectoresConsultaFS">
		<legend>Detalle de Quirópteros Capturados</legend>
		<table id="detalleQuiropterosCapturadosControlVectoresConsulta">
			<thead>
				<tr>
				    <th width="15%">Quirópteros</th>
					<th width="15%"># Capturas</th>
				</tr>
			</thead>
			<?php 
				while ($quiropterosCapturados = pg_fetch_assoc($quiropterosCapturadosControlVectoresConsulta)){
					echo $cpco->imprimirLineaQuiropterosCapturadosControlVectoresConsulta($quiropterosCapturados['id_control_vectores_quiropteros_capturados'], 
															$idControlVectores, $quiropterosCapturados['quiroptero'], 
															$quiropterosCapturados['num_quiropteros'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Quirópteros Tratados</h2>

	<form id="nuevoQuiropteroTratadoControlVectores" data-rutaAplicacion="programasControlOficial" data-opcion="guardarQuiropteroTratadoControlVectores" data-destino="detalleItem">
		<input type='hidden' id='idControlVectores' name='idControlVectores' value="<?php echo $idControlVectores;?>" />
		
		<fieldset>
			<legend>Especies Tratadas</legend>
			
			<div data-linea="25">
				<label># Vampiros tratados con Anticoagulante:</label>
				<input type="text" id="numeroVampirosTratados" name="numeroVampirosTratados" required="required" data-er="^[0-9]+$" />
			</div>
			
			<div data-linea="26">
				<label># Vampiros no tratados:</label>
				<input type="text" id="numeroVampirosNoTratados" name="numeroVampirosNoTratados" required="required" data-er="^[0-9]+$" />
			</div>
			
			<div data-linea="27">
				<label># Vampiros enviados al Laboratorio:</label>
				<input type="text" id="numeroVampirosLaboratorio" name="numeroVampirosLaboratorio" required="required" data-er="^[0-9]+$" />
			</div>
									
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detalleQuiropterosTratadosControlVectoresFS">
		<legend>Detalle de Quirópteros Tratados</legend>
		<table id="detalleQuiropterosTratadosControlVectores">
			<thead>
				<tr>
				    <th width="15%"># Vampiros tratados con Anticoagulante</th>
					<th width="15%"># Vampiros no tratados</th>
					<th width="15%"># Vampiros enviados al Laboratorio</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($quiropterosTratados = pg_fetch_assoc($quiropterosTratadosControlVectores)){
					echo $cpco->imprimirLineaQuiropterosTratadosControlVectores($quiropterosTratados['id_control_vectores_quiropteros_tratados'], 
															$idControlVectores, $quiropterosTratados['vampiros_tratados'], $quiropterosTratados['vampiros_no_tratados'],
															$quiropterosTratados['vampiros_laboratorio'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleQuiropterosTratadosControlVectoresConsultaFS">
		<legend>Detalle de Quirópteros Tratados</legend>
		<table id="detalleQuiropterosTratadosControlVectoresConsulta">
			<thead>
				<tr>
				    <th width="15%"># Vampiros tratados con Anticoagulante</th>
					<th width="15%"># Vampiros no tratados</th>
					<th width="15%"># Vampiros enviados al Laboratorio</th>
				</tr>
			</thead>
			<?php 
				while ($quiropterosTratados = pg_fetch_assoc($quiropterosTratadosControlVectoresConsulta)){
					echo $cpco->imprimirLineaQuiropterosTratadosControlVectoresConsulta($quiropterosCapturados['id_control_vectores_quiropteros_tratados'], 
															$idControlVectores, $quiropterosTratados['vampiros_tratados'], $quiropterosTratados['vampiros_no_tratados'],
															$quiropterosTratados['vampiros_laboratorio'], $ruta);
				}
			?>
		</table>
	</fieldset>

</div>

<div class="pestania">
	<div id="captura">
		<h2>Sitio de Captura</h2>
	
		<form id="nuevoSitioCapturaControlVectores" data-rutaAplicacion="programasControlOficial" data-opcion="guardarSitioCapturaControlVectores" data-destino="detalleItem">
			<input type='hidden' id='idControlVectores' name='idControlVectores' value="<?php echo $idControlVectores;?>" />
			
			<fieldset>
				<legend>Sitio de Captura</legend>
				
				<div data-linea="26">
					<label>Malla: </label>
					<select id="malla" name="malla" required="required">
						<option value="">Seleccione....</option>
						<option value="Malla 1">Malla 1</option>
						<option value="Malla 2">Malla 2</option>
						<option value="Malla 3">Malla 3</option>
						<option value="Malla 4">Malla 4</option>
						<option value="Malla 5">Malla 5</option>						
					</select>					
				</div>	
				
				<div data-linea="27">
					<label>Especie: </label>
					<select id="especieMalla" name="especieMalla"  required="required">
						<option value="">Seleccione....</option>
						<option value="1">Hematófagos</option>
						<option value="2">Insectívoros</option>
						<option value="3">Frugívoros</option>						
					</select>
					
					<input type="text" id="nombreEspecieMalla" name="nombreEspecieMalla" />
				</div>
				
				<div data-linea="27">
					<label># capturados:</label>
					<input type="text" id="numeroCapturadosMalla" name="numeroCapturadosMalla" required="required" data-er="^[0-9]+$" />
				</div>
				
				<div data-linea="28">
					<label>Observaciones: </label>
					<input type="text" id="observacionesMalla" name="observacionesMalla" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
										
				<div>
					<button type="submit" class="mas">Agregar</button>		
				</div>
				
				
			</fieldset>
		</form>
		
		<fieldset id="detalleSitioCapturaControlVectoresFS">
			<legend>Detalle de Quirópteros Capturados</legend>
			<table id="detalleSitioCapturaControlVectores">
				<thead>
					<tr>
					    <th width="15%">Malla</th>
					    <th width="15%">Especie</th>
						<th width="15%"># Capturados</th>
						<th width="15%">Observaciones</th>
						<th width="5%">Eliminar</th>
					</tr>
				</thead>
				<?php 
					while ($sitioCaptura = pg_fetch_assoc($sitiosCapturaControlVectores)){
						echo $cpco->imprimirLineaSitioCapturaControlVectores($sitioCaptura['id_control_vectores_sitio_captura'],
																$idControlVectores, $sitioCaptura['malla'], $sitioCaptura['especie'],
																$sitioCaptura['num_capturas_malla'], $sitioCaptura['observaciones_malla'], 
																$ruta);
					}
				?>
			</table>
		</fieldset>
		
		<fieldset id="detalleSitioCapturaControlVectoresConsultaFS">
			<legend>Detalle de Quirópteros Capturados</legend>
			<table id="detalleSitioCapturaControlVectoresConsulta">
				<thead>
					<tr>
					    <th width="15%">Malla</th>
					    <th width="15%">Especie</th>
						<th width="15%"># Capturados</th>
						<th width="15%">Observaciones</th>
					</tr>
				</thead>
				<?php 
					while ($sitioCaptura = pg_fetch_assoc($sitiosCapturaControlVectoresConsulta)){
						echo $cpco->imprimirLineaSitioCapturaControlVectoresConsulta($sitioCaptura['id_control_vectores_sitio_captura'],
																$idControlVectores, $sitioCaptura['malla'], $sitioCaptura['especie'],
																$sitioCaptura['num_capturas_malla'], $sitioCaptura['observaciones_malla'], 
																$ruta);
					}
				?>
			</table>
		</fieldset>
	</div>

</div>

<div class="pestania">	
	<div id="cierre">
		<form id="cerrarControlVectores" data-rutaAplicacion="programasControlOficial" data-opcion="guardarCierreControlVectores" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
			<input type='hidden' id='idControlVectores' name='idControlVectores' value="<?php echo $idControlVectores;?>" />
			<input type='hidden' id='tipoRefugio' name='tipoRefugio' value="<?php echo $controlVectores['sitio_captura'];?>" />
						
			<fieldset >
				<legend>Cerrar Proceso</legend>
				
				<div data-linea="28">
					<label>Observaciones: </label>
					<input type="text" id="observacionesControlVectores" name="observacionesControlVectores" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
	
			</fieldset>
					
			<div data-linea="29">
				<button id="guardarCierre" type="submit" class="guardar">Guardar y Finalizar</button>
			</div>
		</form>	
		
		<fieldset id="cerrarControlVectoresConsulta">
			<legend>Cerrar Proceso</legend>
			
			<div data-linea="28">
				<label>Observaciones: </label>
				<?php echo $controlVectores['observaciones'];?>
			</div>

		</fieldset>
	</div>
</div>

<script type="text/javascript">

var usuario = <?php echo json_encode($usuario); ?>;
var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;
var sitioCaptura= <?php echo json_encode($controlVectores['sitio_captura']); ?>;
var estado= <?php echo json_encode($controlVectores['estado']); ?>;
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

		$("#fechaDesde").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		$("#fechaHasta").datepicker({
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

		$('#planificarNuevaInspeccion').hide();
		$("#nombreEspecie").hide();
		$("#nombreEspecieMalla").hide();

		acciones("#nuevaEspecieAtacadaControlVectores","#detalleEspecieAtacadaControlVectores");
		acciones("#nuevoQuiropteroCapturadoControlVectores","#detalleQuiropterosCapturadosControlVectores");
		acciones("#nuevoQuiropteroTratadoControlVectores","#detalleQuiropterosTratadosControlVectores");
		acciones("#nuevoSitioCapturaControlVectores","#detalleSitioCapturaControlVectores");
		
		cargarValorDefecto("faseLunar","<?php echo $controlVectores['id_fase_lunar'];?>");

		if(usuario == '0'){
			$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#botonGuardar").attr("disabled", "disabled");
		}

		$("#captura").hide();
		$("#cierre").hide();

		if(sitioCaptura=="Refugio"){
			$("#captura").hide();
			$("#cierre").show();
		}else if(sitioCaptura=="Potrero"){
			$("#captura").show();
			$("#cierre").show();
		}

		$('#detalleEspecieAtacadaControlVectoresConsultaFS').hide();
		$('#detalleQuiropterosCapturadosControlVectoresConsultaFS').hide();
		$('#detalleQuiropterosTratadosControlVectoresConsultaFS').hide();
		$('#detalleSitioCapturaControlVectoresConsultaFS').hide();
		$("#cerrarControlVectoresConsulta").hide();
		
		if(estado == 'cerrado'){
			if(perfil != false){
				$("#modificar").show();
				$("#nuevaEspecieAtacadaControlVectores").show();
					$('#detalleEspecieAtacadaControlVectoresFS').show();
					$('#detalleEspecieAtacadaControlVectoresConsultaFS').hide();
				$("#nuevoQuiropteroCapturadoControlVectores").show();
					$('#detalleQuiropterosCapturadosControlVectoresFS').show();
					$('#detalleQuiropterosCapturadosControlVectoresConsultaFS').hide();
				$("#nuevoQuiropteroTratadoControlVectores").show();
					$('#detalleQuiropterosTratadosControlVectoresFS').show();
					$('#detalleQuiropterosTratadosControlVectoresConsultaFS').hide();
				$("#nuevoSitioCapturaControlVectores").show();
					$('#detalleSitioCapturaControlVectoresFS').show();
					$('#detalleSitioCapturaControlVectoresConsultaFS').hide();
					$("#cerrarControlVectores").hide();
					$("#cerrarControlVectoresConsulta").show();
					
			}else{
				$("#modificar").hide();
				$("#nuevaEspecieAtacadaControlVectores").hide();
					$('#detalleEspecieAtacadaControlVectoresFS').hide();
					$('#detalleEspecieAtacadaControlVectoresConsultaFS').show();
				$("#nuevoQuiropteroCapturadoControlVectores").hide();
					$('#detalleQuiropterosCapturadosControlVectoresFS').hide();
					$('#detalleQuiropterosCapturadosControlVectoresConsultaFS').show();
				$("#nuevoQuiropteroTratadoControlVectores").hide();
					$('#detalleQuiropterosTratadosControlVectoresFS').hide();
					$('#detalleQuiropterosTratadosControlVectoresConsultaFS').show();
				$("#nuevoSitioCapturaControlVectores").hide();
					$('#detalleSitioCapturaControlVectoresFS').hide();
					$('#detalleSitioCapturaControlVectoresConsultaFS').show();
				$("#cerrarControlVectores").hide();
				$("#cerrarControlVectoresConsulta").show();
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

	$("#modificarControlVectores").submit(function(event){

		$("#modificarControlVectores").attr('data-opcion', 'modificarControlVectores');
	    $("#modificarControlVectores").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#fecha").val())){
			error = true;
			$("#fecha").addClass("alertaCombo");
		}

		if(!$.trim($("#faseLunar").val())){
			error = true;
			$("#faseLunar").addClass("alertaCombo");
		}

		if(!$.trim($("#duracion").val()) || !esCampoValido("#duracion")){
			error = true;
			$("#duracion").addClass("alertaCombo");
		}

		if(!$.trim($("#fechaDesde").val())){
			error = true;
			$("#fechaDesde").addClass("alertaCombo");
		}

		if(!$.trim($("#fechaHasta").val())){
			error = true;
			$("#fechaHasta").addClass("alertaCombo");
		}

		/*if(!$.trim($("#nombrePredio").val()) || !esCampoValido("#nombrePredio")){
			error = true;
			$("#nombrePredio").addClass("alertaCombo");
		}

		if(!$.trim($("#nombrePropietario").val()) || !esCampoValido("#nombrePropietario")){
			error = true;
			$("#nombrePropietario").addClass("alertaCombo");
		}*/


		if(!$.trim($("#sitio").val()) || !esCampoValido("#sitio")){
			error = true;
			$("#sitio").addClass("alertaCombo");
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

	
	$("#faseLunar").change(function(){
    	$("#nombreFaseLunar").val($("#faseLunar option:selected").text());
	});
	
	//Ubicación Provincia, Cantón, Parroquia
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

		soficina ='0';
		soficina = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_oficina.length;i++){
		    if ($("#canton").val()==array_oficina[i]['padre']){
		    	soficina += '<option value="'+array_oficina[i]['codigo']+'">'+array_oficina[i]['nombre']+'</option>';
			    } 
	    	}
	    soficina += '<option value="0">Otro</option>';

	    $('#oficina').html(soficina);
		$("#oficina").removeAttr("disabled");
	});

    $("#parroquia").change(function(){
    	$("#nombreParroquia").val($("#parroquia option:selected").text());
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

    $("#refugio").change(function(){
        if($("#refugio option:selected").val()!='0'){
        	$('#nombreRefugio').hide();
    		$("#nombreRefugio").val($("#refugio option:selected").text());
        }else{
        	$("#nombreRefugio").val('');
    	    $('#nombreRefugio').show();
        }
	});

    $("#especie").change(function(){
        if($("#especie option:selected").val()!='0'){
        	$('#nombreEspecie').hide();
    		$("#nombreEspecie").val($("#especie option:selected").text());
        }else{
        	$("#nombreEspecie").val('');
    	    $('#nombreEspecie').show();
        }
	});

    $("#quiropteros").change(function(){
        $("#nombreQuiropteros").val($("#quiropteros option:selected").text());
	});

    $("#especieMalla").change(function(){
        $("#nombreEspecieMalla").val($("#especieMalla option:selected").text());
	});

  //Cierre Control Vectores
	$("#cerrarControlVectores").submit(function(event){

		$("#cerrarControlVectores").attr('data-opcion', 'guardarCierreControlVectores');
	    $("#cerrarControlVectores").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#observacionesControlVectores").val()) || !esCampoValido("#observacionesControlVectores")){
			error = true;
			$("#observacionesControlVectores").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

	$("#nuevoQuiropteroCapturadoControlVectores").submit(function(event){
		event.preventDefault();

		$("#numeroVampirosTratados").val("");
		$("#numeroVampirosNoTratados").val("");
		$("#numeroVampirosLaboratorio").val("");
		$("#detalleQuiropterosTratadosControlVectores td").html("");
	});

	$("#detalleQuiropterosCapturadosControlVectores tr").submit(function(event){
		alert("Se elimina la información de Quirópteros Tratados");
		event.preventDefault();

		$("#numeroVampirosTratados").val("");
		$("#numeroVampirosNoTratados").val("");
		$("#numeroVampirosLaboratorio").val("");
		$("#detalleQuiropterosTratadosControlVectores td").html("");
	});

	$("#archivo").click(function(){
    	$("#subirArchivo button").removeAttr("disabled");
    });

	$("#archivoInforme").click(function(){
    	$("#subirArchivoInforme button").removeAttr("disabled");
    });
</script>