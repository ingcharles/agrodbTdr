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
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Programas Control Oficial Murciélagos Hematófagos'),0,'id_perfil');
	}//$usuario=0;
	
	$ruta = 'programasControlOficial';
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	$oficina = $cc->listarSitiosLocalizacion($conexion,'SITIOS');
	
	$idMurcielagosHematofagos = $_POST['id'];
	$murcielagosHematofagos = pg_fetch_assoc($cpco->abrirControlMurcielagosHematofagos($conexion, $idMurcielagosHematofagos));
	
	$inspeccionesMurcielagosHematofagos = $cpco->listarInspeccionMurcielagosHematofagos($conexion, $idMurcielagosHematofagos);
	$inspeccionesMurcielagosHematofagosConsulta = $cpco->listarInspeccionMurcielagosHematofagos($conexion, $idMurcielagosHematofagos);
?>

<header>
	<h1>Identificación y Supervisión de Refugios de Murciélagos Hematófagos</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>

<div class="pestania">
	<h2>Refugios de Murciélagos Hematófagos</h2>

	<form id="modificarControlMurcielagosHematofagos" data-rutaAplicacion="programasControlOficial" data-opcion="modificarControlMurcielagosHematofagos" data-destino="detalleItem"> <!--  data-accionEnExito="ACTUALIZAR" -->
		<p>
			<button id="modificar" type="button" class="editar">Modificar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</p>
		
	<div id="informacion">
		<fieldset>
			<legend>Información de Identificación del Refugio</legend>
				
				<div data-linea="0">
					<label>N° Solicitud:</label>
					<?php echo $murcielagosHematofagos['num_solicitud'];?>
				</div>
				
				<div data-linea="0" >
					<div id='siguienteInspeccion'>
						<label>Fecha Siguiente Inspección:</label>
						<?php echo date('j/n/Y',strtotime($murcielagosHematofagos['fecha_nueva_inspeccion']));?>
					</div>
				</div>
				
				<div data-linea="1">
					<label>Fecha registro:</label>
					<?php echo date('j/n/Y',strtotime($murcielagosHematofagos['fecha']));?>
				</div>
				
				<div data-linea="2">
					<label>Nombre del Predio:</label>
					<?php echo $murcielagosHematofagos['nombre_predio'];?>
				</div>
				
				<div data-linea="2">
					<label>Nombre del Propietario:</label>
					<?php echo $murcielagosHematofagos['nombre_propietario'];?>
				</div>
				
				<div data-linea="3">
					<label>Persona que conoce el refugio:</label>
					<?php echo $murcielagosHematofagos['persona_refugio'];?>
				</div>
				
				<div data-linea="3">
					<label>Tipo de Refugio</label>
					<?php echo $murcielagosHematofagos['tipo_refugio'];?>
				</div>
		
			</fieldset>
			
			<fieldset>
				<legend>Ubicación y Datos Generales</legend>
		
				<div data-linea="4">
					<label>Provincia</label>
					<?php echo $murcielagosHematofagos['provincia'];?>	
				</div>
					
				<div data-linea="4">
					<label>Cantón</label>
						<?php echo $murcielagosHematofagos['canton'];?>
					</div>
					
					<div data-linea="5">	
					<label>Parroquia</label>
						<?php echo $murcielagosHematofagos['parroquia'];?>
					</div>
						
				<div data-linea="5">
					<label>Sitio:</label>
					<?php echo $murcielagosHematofagos['sitio'];?>
				</div>
				
				<div data-linea="6">	
					<label>Oficina Agrocalidad</label>
					<?php echo $murcielagosHematofagos['oficina'];?>
				</div>
				
			</fieldset>
			
			<fieldset>
				<legend>Coordenadas</legend>
		
				<div data-linea="7">
					<label>X:</label>
					<?php echo $murcielagosHematofagos['utm_x'];?>
				</div>
				
				<div data-linea="7">
					<label>Y:</label>
					<?php echo $murcielagosHematofagos['utm_y'];?>
				</div>
				
				<div data-linea="7">
					<label>Z:</label>
					<?php echo $murcielagosHematofagos['utm_z'];?>
				</div>
				
				<!-- div data-linea="8">
					<label>Altitud:</label>
					< ?php echo $murcielagosHematofagos['altitud'];?>
				</div-->
				
			</fieldset>
			
		</div>
	
	<div id="actualizacion">
			<input type='hidden' id='idMurcielagosHematofagos' name='idMurcielagosHematofagos' value="<?php echo $idMurcielagosHematofagos;?>" />	
		
			<fieldset>
				<legend>Información de Identificación del Refugio</legend>
		
				<div data-linea="10">
					<label>N° Solicitud:</label>
					<?php echo $murcielagosHematofagos['num_solicitud'];?>
				</div>
				
				<div data-linea="11">
					<label>Fecha:</label>
					<input type="text" id="fecha" name="fecha" value="<?php echo date('j/n/Y',strtotime($murcielagosHematofagos['fecha']));?>" />
				</div>
				
				<div data-linea="12">
					<label>Nombre del Predio:</label>
					<input type="text" id="nombrePredio" name="nombrePredio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $murcielagosHematofagos['nombre_predio'];?>" />
				</div>
				
				<div data-linea="12">
					<label>Nombre del Propietario:</label>
					<input type="text" id="nombrePropietario" name="nombrePropietario" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $murcielagosHematofagos['nombre_propietario'];?>" />
				</div>
				
				<div data-linea="13">
					<label>Persona que conoce el refugio:</label>
					<input type="text" id="personaRefugio" name="personaRefugio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $murcielagosHematofagos['persona_refugio'];?>" />
				</div>
				
				<div data-linea="21">
				<label>Tipo de Refugio:</label>
					<select id="refugio" name="refugio" required="required" >
						<option value="">Refugio....</option>
						<option value="1">Alcantarilla</option>
						<option value="2">Casa Abandonada</option>
						<option value="3">Caverna</option>
						<option value="4">Hueco de Árbol</option>						
						<option value="5">Puente</option>
						<option value="0">Otros</option>
					</select>
			</div>	
			
			<div data-linea="21">	
				<input type="text" id="nombreRefugio" name="nombreRefugio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $murcielagosHematofagos['tipo_refugio'];?>" />
			</div>
		
			</fieldset>
			
			<fieldset>
				<legend>Ubicación y Datos Generales</legend>
		
				<div data-linea="14">
					<label>Provincia</label>
					<?php echo $murcielagosHematofagos['provincia'];?>	
				</div>
					
				<div data-linea="14">
					<label>Cantón</label>
						<?php echo $murcielagosHematofagos['canton'];?>
					</div>
					
				<div data-linea="15">	
					<label>Parroquia</label>
						<?php echo $murcielagosHematofagos['parroquia'];?>
				</div>
						
				<div data-linea="15">
					<label>Sitio:</label>
					<input type="text" id="sitio" name="sitio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $murcielagosHematofagos['sitio'];?>"/>
				</div>
				
				<div data-linea="16">	
					<label>Oficina Agrocalidad</label>
						<select id="oficina" name="oficina" disabled="disabled">
							<?php echo '<option value="' . $murcielagosHematofagos['id_oficina'] . '">' . $murcielagosHematofagos['oficina'] . '</option>'; ?>
						</select>
				</div>
				
				<div data-linea="16">	
					<input type="text" id="nombreOficina" name="nombreOficina" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $murcielagosHematofagos['oficina'];?>"/>
				</div>
				
			</fieldset>
			
			<fieldset>
				<legend>Coordenadas</legend>
				
		
				<div data-linea="17">
					<label>X:</label>
					<input type="text" id="x" name="x" maxlength="6" data-er="^[0-9]+$" value="<?php echo $murcielagosHematofagos['utm_x'];?>"/>
				</div>
				
				<div data-linea="17">
					<label>Y:</label>
					<input type="text" id="y" name="y" maxlength="7" data-er="^[0-9]+$" value="<?php echo $murcielagosHematofagos['utm_y'];?>"/>
				</div>
				
				<div data-linea="17">
					<label>Z:</label>
					<input type="text" id="z" name="z" maxlength="4" data-er="^[0-9]+$" value="<?php echo $murcielagosHematofagos['utm_z'];?>"/>
				</div>
				
				<!-- div data-linea="18">
					<label>Altitud:</label>
					<input type="text" id="altitud" name="altitud" maxlength="16" data-er="^[0-9.]+$" value="< ?php echo $murcielagosHematofagos['altitud'];?>" />
				</div-->
		
			</fieldset>
		
		</div>
	
	</form>
	
	<fieldset id="adjuntos">
				<legend>Mapa de Ubicación</legend>
		
					<div data-linea="1">
						<label>Mapa:</label>
						<?php echo ($murcielagosHematofagos['imagen_mapa']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$murcielagosHematofagos['imagen_mapa'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Mapa cargado</a>')?>
					</div>
					
					<form id="subirArchivo" action="aplicaciones/programasControlOficial/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
						
						<input type="file" name="archivo" id="archivo" accept="application/pdf" /> 
						<input type="hidden" name="id" value="<?php echo $murcielagosHematofagos['id_murcielagos_hematofagos'];?>" />
						<input type="hidden" name="aplicacion" value="MurcielagosHematofagos" /> 
						
						<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
					</form>
					<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
			</fieldset>
			
			<fieldset id="adjuntosInforme">
				<legend>Informe</legend>
		
					<div data-linea="1">
						<label>Informe:</label>
						<?php echo ($murcielagosHematofagos['ruta_informe']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$murcielagosHematofagos['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe cargado</a>')?>
					</div>
					
					<form id="subirArchivoInforme" action="aplicaciones/programasControlOficial/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergenteInforme" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
						
						<input type="file" name="archivo" id="archivoInforme" accept="application/pdf" /> 
						<input type="hidden" name="id" value="<?php echo $murcielagosHematofagos['id_murcielagos_hematofagos'];?>" />
						<input type="hidden" name="aplicacion" value="InformeMurcielagosHematofagos" /> 
						
						<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
					</form>
					<iframe name="ventanaEmergenteInforme" class="ventanaEmergente"></iframe>
			</fieldset>
			
			
</div>

<div class="pestania">

	<h2>Inspecciones</h2>

	<form id="nuevaInspeccionMurcielagosHematofagos" data-rutaAplicacion="programasControlOficial" data-opcion="guardarInspeccionMurcielagosHematofagos" data-destino="detalleItem">
		<input type='hidden' id='idMurcielagosHematofagos' name='idMurcielagosHematofagos' value="<?php echo $idMurcielagosHematofagos;?>" />
		
		<fieldset>
			<legend>Control Realizado</legend>
			
			<div data-linea="20">
				<label>Inspección #:</label>
					<select id="inspeccion" name="inspeccion" required="required" >
						<option value="">Inspección....</option>
						<?php 
							for ($i=1; $i<31; $i++){
								echo '<option value="Inspección ' . $i . '"> Inspección ' . $i  . '</option>';
							}
						?>
					</select> 					
			</div>
			
			<div data-linea="20">
				<label>Fecha Inspección:</label>
				<input type="text" id="fechaInspeccion" name="fechaInspeccion" required="required" />
			</div>
				
			
			
			<hr />
			
			<div data-linea="22">
				<label>Presencia MH: </label>
				<select id="presenciaMH" name="presenciaMH" required="required" >
					<option value="">Seleccione....</option>
					<option value="Si">Si</option>
					<option value="No">No</option>
					<option value="Sospecha">Sospecha</option>						
				</select>
			</div>	
			
			<div data-linea="22">
				<label>Control Realizado: </label>
				<select id="controlRealizado" name="controlRealizado" required="required" >
					<option value="">Seleccione....</option>
					<option value="Si">Si</option>
					<option value="No">No</option>						
				</select>
			</div>
			
			<div data-linea="23">
				<label id="MHEtiqueta" >MH Tratados -> </label>
			</div>			
			
			<div data-linea="23">
				<label id="MHEtiquetaMacho">Macho: </label>
				<input type="text" id="numMurcielagosMacho" name="numMurcielagosMacho" data-er="^[0-9.]+$" required="required" />
			</div>
			
			<div data-linea="23">
				<label id="MHEtiquetaHembra" >Hembra: </label>
				<input type="text" id="numMurcielagosHembra" name="numMurcielagosHembra" data-er="^[0-9.]+$" required="required" />
			</div>
							
			<hr />
			
			<div data-linea="24">
				<label>Observaciones: </label>
				<input type="text" id="observacionesInspeccion" name="observacionesInspeccion" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
						
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
			
			
		</fieldset>
	</form>
	
	<fieldset id="detalleInspeccionMurcielagosHematofagosFS">
		<legend>Inspecciones Registradas</legend>
		<table id="detalleInspeccionMurcielagosHematofagos">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
					<th width="15%">Fecha</th>
					<th width="10%">Presencia MH</th>
					<th width="10%">Control Realizado</th>
					<th width="5%"># Machos</th>
					<th width="5%"># Hembras</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($inspecciones = pg_fetch_assoc($inspeccionesMurcielagosHematofagos)){
					echo $cpco->imprimirLineaInspeccionMurcielagosHematofagos($inspecciones['id_murcielagos_hematofagos_inspecciones'], 
														$inspecciones['id_murcielagos_hematofagos'], $inspecciones['num_inspeccion'], $inspecciones['fecha_inspeccion'], 
														$inspecciones['presencia_mh'], $inspecciones['control_realizado'], $inspecciones['num_machos'], 
														$inspecciones['num_hembras'], $inspecciones['observaciones'], $ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detalleInspeccionMurcielagosHematofagosConsultaFS">
		<legend>Inspecciones Registradas</legend>
		<table id="detalleInspeccionMurcielagosHematofagosConsulta">
			<thead>
				<tr>
				    <th width="15%">Inspección</th>
					<th width="15%">Fecha</th>
					<th width="10%">Presencia MH</th>
					<th width="10%">Control Realizado</th>
					<th width="5%"># Machos</th>
					<th width="5%"># Hembras</th>
					<th width="10%">Observaciones</th>
				</tr>
			</thead>
			<?php 
				while ($inspecciones = pg_fetch_assoc($inspeccionesMurcielagosHematofagosConsulta)){
					echo $cpco->imprimirLineaInspeccionMurcielagosHematofagosConsulta($inspecciones['id_murcielagos_hematofagos_inspecciones'], 
														$inspecciones['id_murcielagos_hematofagos'], $inspecciones['num_inspeccion'], $inspecciones['fecha_inspeccion'], 
														$inspecciones['presencia_mh'], $inspecciones['control_realizado'], $inspecciones['num_machos'], 
														$inspecciones['num_hembras'], $inspecciones['observaciones'], $ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Planificación de Inspecciones</h2>

	<form id="nuevaPlanificacionInspeccionMurcielagosHematofagos" data-rutaAplicacion="programasControlOficial" data-opcion="guardarPlanificacionInspeccionMurcielagosHematofagos" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type='hidden' id='idMurcielagosHematofagos' name='idMurcielagosHematofagos' value="<?php echo $idMurcielagosHematofagos;?>" />
		
		<fieldset>
			<legend>Nueva Inspección</legend>
			
			<div data-linea="25">
				<label>Requiere nueva Inspección:</label>
					<select id="nuevaInspeccion" name="nuevaInspeccion" required="required" >
						<option value="">Requiere inspección....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 					
			</div>

		</fieldset>
										
		<fieldset id="planificarNuevaInspeccion">
			<legend>Planificación de Inspección</legend>
				<div data-linea="26">
					<label>Fecha nueva Inspección:</label>
					<input type="text" id="fechaNuevaInspeccion" name="fechaNuevaInspeccion" />
				</div>				

		</fieldset>	
						
		<div data-linea="27">
			<button id="guardarNuevaInspeccion" type="submit" class="guardar">Guardar</button>
		</div>
	</form>	
	
	<fieldset id="planificarNuevaInspeccionCerrado">
		<legend>Planificación de Inspección</legend>
			<div data-linea="26">
				<label>
					<?php 
						if($murcielagosHematofagos['estado'] == 'inspeccion'){
							echo "El proceso ya tiene una fecha de inspección asignada para el ". date('j/n/Y',strtotime($murcielagosHematofagos['fecha_nueva_inspeccion']));
						}else{
							echo "El proceso de inspecciones ha finalizado";	
						}
					?>
				</label>
			</div>				

	</fieldset>	
</div>

<script type="text/javascript">

var usuario = <?php echo json_encode($usuario); ?>;
var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;
var array_oficina= <?php echo json_encode($oficina); ?>;
var estado= <?php echo json_encode($murcielagosHematofagos['estado']); ?>;
var perfil= <?php echo json_encode($perfilAdmin); ?>;
var fechaInspeccion= new Date(<?php echo json_encode($murcielagosHematofagos['fecha_nueva_inspeccion']); ?>);
var fechaHoy = new Date();

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

		$('#nombreOficina').hide();
		$('#MHEtiqueta').hide();
    	$('#MHEtiquetaMacho').hide();
    	$('#numMurcielagosMacho').hide();
    	$('#MHEtiquetaHembra').hide();
    	$('#numMurcielagosHembra').hide();
		$('#detalleInspeccionMurcielagosHematofagosConsultaFS').hide();
		$('#planificarNuevaInspeccion').hide();
    	$('#guardarNuevaInspeccion').hide();
    	$('#planificarNuevaInspeccionCerrado').hide();

		acciones("#nuevaInspeccionMurcielagosHematofagos","#detalleInspeccionMurcielagosHematofagos");

		cargarValorDefecto("refugio","<?php echo $murcielagosHematofagos['id_tipo_refugio'];?>");

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


		if((estado == 'inspeccion') || (estado == 'cerrado')){
			if(perfil != false){
				$("#modificar").show();
				$("#nuevaInspeccionMurcielagosHematofagos").show();
				$('#detalleInspeccionMurcielagosHematofagosConsultaFS').hide();
				$('#detalleInspeccionMurcielagosHematofagosFS').show();
				$("#nuevaPlanificacionInspeccionMurcielagosHematofagos").show();
				$('#planificarNuevaInspeccionCerrado').hide();
			}else{
				$("#modificar").hide();
				$("#nuevaInspeccionMurcielagosHematofagos").hide();
				$('#detalleInspeccionMurcielagosHematofagosConsultaFS').show();
				$('#detalleInspeccionMurcielagosHematofagosFS').hide();
				$("#nuevaPlanificacionInspeccionMurcielagosHematofagos").hide();
				$('#planificarNuevaInspeccionCerrado').show();

				//Configuración de servidor con formato de fechas
				/*if(fechaInspeccion>fechaHoy){
					$("#modificar").show();
					$("#nuevaInspeccionMurcielagosHematofagos").show();
					$('#detalleInspeccionMurcielagosHematofagosConsultaFS').hide();
					$('#detalleInspeccionMurcielagosHematofagosFS').show();
					$("#nuevaPlanificacionInspeccionMurcielagosHematofagos").show();
					$('#planificarNuevaInspeccionCerrado').hide();
				}*/
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

	$("#modificarControlMurcielagosHematofagos").submit(function(event){

		$("#modificarControlMurcielagosHematofagos").attr('data-opcion', 'modificarControlMurcielagosHematofagos');
	    $("#modificarControlMurcielagosHematofagos").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#fecha").val())){
			error = true;
			$("#fecha").addClass("alertaCombo");
		}

		if(!$.trim($("#personaRefugio").val()) || !esCampoValido("#personaRefugio")){
			error = true;
			$("#personaRefugio").addClass("alertaCombo");
		}

		if(!$.trim($("#refugio").val())){
			error = true;
			$("#refugio").addClass("alertaCombo");
		}

		if(!$.trim($("#nombreRefugio").val())){
			error = true;
			$("#nombreRefugio").addClass("alertaCombo");
		}		

		if(!$.trim($("#sitio").val()) || !esCampoValido("#sitio")){
			error = true;
			$("#sitio").addClass("alertaCombo");
		}

		if(!$.trim($("#oficina").val())){
			error = true;
			$("#oficina").addClass("alertaCombo");
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

	//Planificación de Inspección
	$("#nuevaPlanificacionInspeccionMurcielagosHematofagos").submit(function(event){

		$("#nuevaPlanificacionInspeccionMurcielagosHematofagos").attr('data-opcion', 'guardarPlanificacionInspeccionMurcielagosHematofagos');
	    $("#nuevaPlanificacionInspeccionMurcielagosHematofagos").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nuevaInspeccion").val())){
			error = true;
			$("#nuevaInspeccion").addClass("alertaCombo");
		}

		if($("#nuevaInspeccion option:selected").val() == 'Si'){
			if(!$.trim($("#fechaNuevaInspeccion").val())){
				error = true;
				$("#fechaNuevaInspeccion").addClass("alertaCombo");
			}
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

    $("#controlRealizado").change(function(){
        if($("#controlRealizado option:selected").val()=='No'){
        	$('#MHEtiqueta').hide();
        	$('#MHEtiquetaMacho').hide();
        	$('#numMurcielagosMacho').hide();
        	$('#MHEtiquetaHembra').hide();
        	$('#numMurcielagosHembra').hide();

        	$('#numMurcielagosMacho').val('0');
        	$('#numMurcielagosHembra').val('0');
        }else{
        	$('#MHEtiqueta').show();
        	$('#MHEtiquetaMacho').show();
        	$('#numMurcielagosMacho').show();
        	$('#MHEtiquetaHembra').show();
        	$('#numMurcielagosHembra').show();

        	$('#numMurcielagosMacho').val('');
        	$('#numMurcielagosHembra').val('');
        }
	});

    $("#nuevaInspeccion").change(function(){
        if($("#nuevaInspeccion option:selected").val()=='No'){
        	$('#planificarNuevaInspeccion').hide();
        	$('#guardarNuevaInspeccion').show();
        }else if($("#nuevaInspeccion option:selected").val()=='Si'){
    	    $('#planificarNuevaInspeccion').show();
    	    $('#guardarNuevaInspeccion').show();
        }else{
        	$('#planificarNuevaInspeccion').hide();
        	$('#guardarNuevaInspeccion').hide();
        }
	});

    $("#archivo").click(function(){
    	$("#subirArchivo button").removeAttr("disabled");
    });

    $("#archivoInforme").click(function(){
    	$("#subirArchivoInforme button").removeAttr("disabled");
    });
</script>