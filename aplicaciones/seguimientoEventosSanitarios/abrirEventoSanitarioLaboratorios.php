<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorEventoSanitario.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	$ces = new ControladorEventoSanitario();
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Laboratorio Seguimiento de Eventos Sanitarios'),0,'id_perfil');
	}
	
	$ruta = 'seguimientoEventosSanitarios';
	
	$idEventoSanitario = $_POST['id'];
	$eventoSanitario = pg_fetch_assoc($ces->abrirEventoSanitario($conexion, $idEventoSanitario));	
	$numVisita = $eventoSanitario['num_inspeccion'];
	
	$muestra = pg_fetch_assoc($ces->listarMuestrasPorVisita($conexion, $idEventoSanitario, $numVisita));
	$detalleMuestra  = $ces->listarMuestrasDetalleInspeccion($conexion, $idEventoSanitario, $numVisita);
	
	$enfermedades = $ces->listarCatalogos($conexion,'ENFERMEDAD');
	$enfermedadesAves = $ces->listarCatalogos($conexion,'ENFER_AVES');
	$pruebasLab = $ces->listarCatalogos($conexion,'PRUEBAS_LAB');
?>

<header>
	<h1>Resultados de Laboratorio para Eventos Sanitarios</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>

<div class="pestania">
	<h2>Identificación y Localización del Predio</h2>

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
		
			<div data-linea="5">
				<label id="lNombre">Nombre del propietario:</label>
				<?php echo $eventoSanitario['nombre_propietario'];?> 
			</div>
				
			<div data-linea="6">
				<label id="lCedula">Número de Cedula:</label>
				<?php echo $eventoSanitario['cedula_propietario'];?> 
			</div>
			
			<div data-linea="6">
				<label id="lTelefono">Teléfono:</label>
				<?php echo $eventoSanitario['telefono_propietario'];?> 
			</div>
		
			<div data-linea="7">
				<label id="lCelular">Celular:</label>
				<?php echo $eventoSanitario['celular_propietario'];?> 
			</div>
		
			<div data-linea="7">
				<label id="lCorreoElectronico">Correo Electrónico:</label>
				<?php echo $eventoSanitario['correo_electronico_propietario'];?> 
			</div>
			
			<div data-linea="8">
				<label id="lNombrePredio">Nombre del Predio:</label>
				<?php echo $eventoSanitario['nombre_predio'];?> 
			</div>
		
			<div data-linea="9">
				<label id="lExtencionPredio">Extención del Predio:</label>
				<?php echo $eventoSanitario['extencion_predio'] .' '. $eventoSanitario['medida'];?> 
			</div>
		</fieldset>

		<fieldset>
			<legend>Ubicación del Predio</legend>

			<div data-linea="11">
				<label id="lProvincia">Provincia</label>
				<?php echo $eventoSanitario['provincia'];?> 
			</div>
			
			<div data-linea="11">
				<label id="lCanton">Cantón</label>
				<?php echo $eventoSanitario['canton'];?> 
			</div>
			
			<div data-linea="12">	
				<label id="lParroquia">Parroquia</label>
				<?php echo $eventoSanitario['parroquia'];?> 
			</div>
				
			<div data-linea="12">
				<label id="lOficina">Oficina:</label>
				<?php echo $eventoSanitario['oficina'];?> 
			</div>
			
			<div data-linea="13">
				<label id="lSitio">Sitio:</label>
				<?php echo $eventoSanitario['sitio_predio'];?> 
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
			
				<div data-linea="14">
					<label id="lZona">Huso o Zona:</label>
					<?php echo $eventoSanitario['huso_zona'];?> 
				</div>
				
				<!-- >div data-linea="10">
					<label>Mapa:</label>
					< ?php echo ($eventoSanitario['imagen_mapa']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$eventoSanitario['imagen_mapa'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Mapa cargado</a>')?>
				</div-->
				
		</fieldset>			

	</div>
</div>

<div class="pestania">
	<h2>Exámenes de Laboratorio y Muestras tomadas</h2>
	
	<fieldset>
			<legend>Colecta de Material</legend>
			
				<div data-linea="1">
					<label>Número de visita:</label>
					<?php echo $eventoSanitario['num_inspeccion']; ?>
				</div>
						
				<div data-linea="3">
					<label>Razones sobre las características de la muestra o no colecta:</label>
					<?php echo $muestra['razones_muestra']; ?>
				</div>
				
				<div data-linea="10">
					<label>Anexo:</label>
					<?php echo ($muestra['anexo']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$muestra['anexo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Anexo cargado</a>')?>
				</div>
		</fieldset>
		
		
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
				</tr>
			</thead>
			<?php 
				while ($muestraGC = pg_fetch_assoc($detalleMuestra)){
					echo $ces->imprimirLineaMuestraConsulta(	$muestraGC['id_detalle_muestra'],
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

	<h2>Resultados de Pruebas de Laboratorio</h2>
	
	<form id="nuevaPruebaLaboratorio" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPruebaLaboratorio" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		
		<input type='hidden' id='idEventoSanitario' name='idEventoSanitario' value="<?php echo $idEventoSanitario;?>" />
		<input type='hidden' id='numeroSolicitud' name='numeroSolicitud' value="<?php echo $eventoSanitario['numero_formulario'];?>" />
		<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $eventoSanitario['num_inspeccion'];?>" />
		<input type='hidden' id='numSolicitud' name='numSolicitud' value="<?php echo $eventoSanitario['numero_formulario'];?>" />

		<fieldset>
			<legend>Pruebas de Laboratorio y Resultados</legend>
			
			<div data-linea="11">
				<label>Visita: </label>
					<select id="muestra" name="muestra" required="required" >
						<?php 
							echo '<option value="' . $eventoSanitario['num_inspeccion'] . '">' . $eventoSanitario['num_inspeccion']  . '</option>';
						?>
					</select> 								
			</div>
			
			<div data-linea="11">
				<label>Fecha Informe:</label>
				<input type="text" id="fechaMuestra" name="fechaMuestra" required="required" />
			</div>
			
			<div data-linea="12">
				<label>Enfermedad:</label>
					<select id="enfermedad" name="enfermedad" required="required" >
						<option value="">Seleccione....</option>
						<?php 
							while ($enfermedad = pg_fetch_assoc($enfermedades)){
								echo '<option value="' . $enfermedad['codigo'] . '">' . $enfermedad['nombre'] . '</option>';
							}
							
							while ($enfermedadAves = pg_fetch_assoc($enfermedadesAves)){
								echo '<option value="' . $enfermedadAves['codigo'] . '">' . $enfermedadAves['nombre'] . '</option>';
							}
						?>							
					</select> 
					
					<input type="hidden" id="nombreEnfermedad" name="nombreEnfermedad"/>	
			</div>
			
			<div data-linea="12">
				<label>Cantidad muestras:</label>
				<input type="text" id="cantidadMuestras" name="cantidadMuestras" required="required" />
			</div>
			
			<div data-linea="13">
				<label># Positivos:</label>
				<input type="text" id="numeroPositivos" name="numeroPositivos" required="required" />
			</div>
			
			<div data-linea="13">
				<label># Negativos:</label>
				<input type="text" id="numeroNegativos" name="numeroNegativos" required="required" />
			</div>
			
			<div data-linea="14">
				<label># Indeterminados:</label>
				<input type="text" id="numeroIndeterminados" name="numeroIndeterminados" required="required" />
			</div>
			
			<div data-linea="14">
				<label># Reactivos:</label>
				<input type="text" id="numeroReactivos" name="numeroReactivos" required="required" />
			</div>
			
			<div data-linea="15">
				<label># Sospechosos:</label>
				<input type="text" id="numeroSospechosos" name="numeroSospechosos" required="required" />
			</div>
			
			<div data-linea="15">
				<label>Prueba de Laboratorio:</label>
					<select id="pruebaLaboratorio" name="pruebaLaboratorio" required="required" >
						<option value="">Seleccione....</option>
						<?php 
							while ($pruebaLab = pg_fetch_assoc($pruebasLab)){
								echo '<option value="' . $pruebaLab['codigo'] . '">' . $pruebaLab['nombre'] . '</option>';
							}
						?>							
					</select> 
					
					<input type="hidden" id="nombrePruebaLaboratorio" name="nombrePruebaLaboratorio" />
			</div>
			
			<div data-linea="16">
				<label>Resultado Análisis:</label>
					<select id="resultadoLaboratorio" name="resultadoLaboratorio" required="required" >
						<option value="">Resultado Análisis....</option>
						<option value="Positivo">Positivo</option>
						<option value="Negativo">Negativo</option>
						<option value="Indeterminado o Sospechoso">Indeterminado o Sospechoso</option>
					</select> 					
			</div>
			
			<div data-linea="17">
				<label>Observaciones:</label>
				<input type="text" id="observacionesMuestra" name="observacionesMuestra" required="required" />
			</div>
			
			<div>
				<button type="button" onclick="agregarAnalisis()" class="mas">Agregar análisis</button>		
			</div>
		</fieldset>
	
	
		<fieldset id="detallePruebaLaboratorioFS">
			<legend>Pruebas de Laboratorio y Resultados Registrados</legend>
			<table >
				<thead id="barraTitulo">
					<tr id="titulo">
					    <th width="15%">Visita</th>
					    <th width="15%">Fecha Informe</th>
					    <th width="15%">Enfermedad</th>
					    <th width="15%">Prueba de Laboratorio</th>
						<th width="15%">Resultado Análisis</th>
						<th width="5%">Eliminar</th>
					</tr>
				</thead>
				<tbody id="detallePruebaLaboratorio">
				</tbody>
			</table>
		</fieldset>
	
		<fieldset>
			<legend>Resultado de los Análisis Realizados</legend>
			
			<div data-linea="53">
				<label>Resultado:</label>
					<select id="resultadoAnalisisLaboratorio" name="resultadoAnalisisLaboratorio" required="required" >
						<option value="">Seleccione....</option>
						<option value="positivo">Positivo</option>
						<option value="negativo">Negativo</option>
						<option value="Indeterminado o Sospechoso">Indeterminado o Sospechoso</option>
					</select>
					 					
			</div>
			
			<div data-linea="54">
				<label>Observaciones:</label>
				<input type="text" id="observaciones" name="observaciones" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü,. ]+$"  />
			</div>
				
			<div data-linea="55">
				<label>Informe de Laboratorio:</label>
				
				<input type="file" class="archivo" name="informe" accept="application/pdf" /> 
				
				<input type="hidden" class="rutaArchivo" id="archivoInforme" name="archivoInforme" value="" />
				
				<div class="estadoCarga">
					En espera de archivo... (Tamaño máximo; <?php echo ini_get("upload_max_filesize");?>B)
				</div>
				
				<button type="button" class="subirArchivoInforme" data-rutaCarga="aplicaciones/seguimientoEventosSanitarios/informeLaboratorios">Subir informe de Laboratorio</button>
			</div>
		</fieldset>
	
		<button type="submit" class="guardar">Guardar Resultados de Laboratorio</button>
		
	</form>
</div>

<script type="text/javascript">

var usuario = <?php echo json_encode($usuario); ?>;
var estado= <?php echo json_encode($eventoSanitario['estado']); ?>;
var perfil= <?php echo json_encode($perfilAdmin); ?>;

	$("document").ready(function(){
		distribuirLineas();	
		construirValidador();
		construirAnimacion($(".pestania"));

		$("#fechaMuestra").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		if(<?php echo json_encode($eventoSanitario['nueva_inspeccion']); ?>=='Si'){
			$('#siguienteInspeccion').show();
			$('#siguienteInspeccion').addClass('exito'); //exito, advertencia, alerta
		}else{
			$('#siguienteInspeccion').hide();
		}
		
		if(usuario == '0'){
			$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#botonGuardar").attr("disabled", "disabled");
		}

	});

	function agregarAnalisis(){  
    	if($("#muestra").val()!="" && $("#pruebaLaboratorio").val()!=""){

    		if($("#detallePruebaLaboratorio #r_"+$("#muestra").val()+$("#pruebaLaboratorio").val()).length==0){
   				$("#detallePruebaLaboratorio").append("<tr id='r_"+$("#muestra").val()+$("#pruebaLaboratorio").val()+"'><td>"+$("#muestra  option:selected").text()+"</td><td>"+$("#fechaMuestra").val()+"</td><td>"+$("#enfermedad  option:selected").text()+"</td><td>"+$("#pruebaLaboratorio  option:selected").text()+"</td><td>"+$("#resultadoLaboratorio  option:selected").text()+"</td><td><input id='arrayMuestra' name='arrayMuestra[]' value='"+$("#muestra option:selected").text()+"' type='hidden'><input id='arrayFechaMuestra' name='arrayFechaMuestra[]' value='"+$("#fechaMuestra").val()+"' type='hidden'><input id='arrayIdEnfermedad' name='arrayIdEnfermedad[]' value='"+$("#enfermedad option:selected").val()+"' type='hidden'><input id='arrayEnfermedad' name='arrayEnfermedad[]' value='"+$("#enfermedad option:selected").text()+"' type='hidden'><input id='arrayCantidadMuestras' name='arrayCantidadMuestras[]' value='"+$("#cantidadMuestras").val()+"' type='hidden'><input id='arrayNumPositivos' name='arrayNumPositivos[]' value='"+$("#numeroPositivos").val()+"' type='hidden'><input id='arrayNumNegativos' name='arrayNumNegativos[]' value='"+$("#numeroNegativos").val()+"' type='hidden'><input id='arrayNumIndeterminados' name='arrayNumIndeterminados[]' value='"+$("#numeroIndeterminados").val()+"' type='hidden'><input id='arrayNumReactivos' name='arrayNumReactivos[]' value='"+$("#numeroReactivos").val()+"' type='hidden'><input id='arrayNumSospechosos' name='arrayNumSospechosos[]' value='"+$("#numeroSospechosos").val()+"' type='hidden'><input id='arrayIdPruebaLaboratorio' name='arrayIdPruebaLaboratorio[]' value='"+$("#pruebaLaboratorio option:selected").val()+"' type='hidden'><input id='arrayPruebaLaboratorio' name='arrayPruebaLaboratorio[]' value='"+$("#pruebaLaboratorio option:selected").text()+"' type='hidden'><input id='arrayResultadoLaboratorio' name='arrayResultadoLaboratorio[]' value='"+$("#resultadoLaboratorio option:selected").val()+"' type='hidden'><input id='arrayObservacionesMuestra' name='arrayObservacionesMuestra[]' value='"+$("#observacionesMuestra").val()+"' type='hidden'><button type='button' onclick='quitarAnalisis(\"#r_"+$("#muestra").val()+$("#pruebaLaboratorio").val()+"\")' class='menos'>Quitar</button></td></tr>");
    		}
    		
    	}
    }

	function quitarAnalisis(fila){
		$("#detallePruebaLaboratorio tr").eq($(fila).index()).remove();
	}

	//Archivo informe
	$('button.subirArchivoInforme').click(function (event) {
	
		var boton = $(this);
	    var archivo = boton.parent().find(".archivo");
	    var rutaArchivo = boton.parent().find(".rutaArchivo");
	    var extension = archivo.val().split('.');
	    var estado = boton.parent().find(".estadoCarga");
	    numero = Math.floor(Math.random()*100000000);
	    
	    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	        subirArchivo(archivo, $("#identificador").val() +"_"+numero, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)); 
	    } else {
	        estado.html('Formato incorrecto, sólo se admite archivos en formato PDF');
	        archivo.val("0");
	    }        
	});

	$("#enfermedad").change(function(){
    	$("#nombreEnfermedad").val($("#enfermedad option:selected").text());
	});

	$("#pruebaLaboratorio").change(function(){
    	$("#nombrePruebaLaboratorio").val($("#pruebaLaboratorio option:selected").text());
	});
	
	//Validación y Guardado
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	//Resultados de Análisis
	$("#nuevaPruebaLaboratorio").submit(function(event){

		$("#nuevaPruebaLaboratorio").attr('data-opcion', 'guardarPruebaLaboratorio');
	    $("#nuevaPruebaLaboratorio").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($('#arrayMuestra').length == 0 ){
			error = true;
			$("#estado").html("Por favor ingrese una o más pruebas de laboratorio realizadas").addClass("alerta");
			$("#detallePruebaLaboratorioFS").addClass("alertaCombo");
		}

		if(!$.trim($("#resultadoAnalisisLaboratorio").val())){
			error = true;
			$("#resultadoAnalisisLaboratorio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#observaciones").val()) || !esCampoValido("#observaciones")){
			error = true;
			$("#observaciones").addClass("alertaCombo");
		}

		if(!$.trim($("#archivoInforme").val())){
			error = true;
			$(".archivo").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

</script>