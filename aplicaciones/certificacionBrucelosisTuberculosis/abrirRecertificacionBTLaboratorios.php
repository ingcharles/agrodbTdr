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
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Técnico Laboratorio Certificación Brucelosis y Tuberculosis'),0,'id_perfil');
	}
	
	$ruta = 'certificacionBrucelosisTuberculosis';
	
	$idRecertificacionBT = $_POST['id'];
	$certificacionBT = pg_fetch_assoc($cbt->abrirRecertificacionBT($conexion, $idRecertificacionBT));
	
?>

<header>
	<h1>Resultados de Laboratorio para Predios para Recertificación como Libres de Brucelosis y Tuberculosis Bovina</h1>
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
			
			<div data-linea="0" >
				<div id='siguienteInspeccion'>
					<label>Fecha Toma de Muestras:</label>
					<?php echo date('j/n/Y',strtotime($certificacionBT['fecha_nueva_inspeccion']));?>
				</div>
			</div>
				
			<!-- div data-linea="1">
				<label>Fecha:</label>
				< ?php echo date('j/n/Y',strtotime($certificacionBT['fecha']));?>
			</div-->
			
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
			
			<div data-linea="10">
				<label>Mapa:</label>
				<?php echo ($certificacionBT['imagen_mapa']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$certificacionBT['imagen_mapa'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Mapa cargado</a>')?>
			</div>
	
		</fieldset>			

	</div>
</div>

<div class="pestania">

	<h2>Resultados de Pruebas de Laboratorio</h2>
	
	<form id="nuevaPruebaLaboratorio" data-rutaAplicacion="<?php echo $ruta;?>" data-opcion="guardarPruebaLaboratorioRecertificacion" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		
		<input type='hidden' id='idRecertificacionBT' name='idRecertificacionBT' value="<?php echo $idRecertificacionBT;?>" />
		<input type='hidden' id='numeroSolicitud' name='numeroSolicitud' value="<?php echo $certificacionBT['num_solicitud'];?>" />
		<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
		<input type='hidden' id='numInspeccion' name='numInspeccion' value="<?php echo $certificacionBT['num_inspeccion'];?>" />
		<input type='hidden' id='numSolicitud' name='numSolicitud' value="<?php echo $certificacionBT['num_solicitud'];?>" />

		<fieldset>
			<legend>Pruebas de Laboratorio y Resultados</legend>
			
			<div data-linea="11">
				<label>Visita para: </label>
					<select id="muestra" name="muestra" required="required" >
						<?php 
							/*for ($i=1; $i<11; $i++){
								echo '<option value="Visita' . $i . '">Visita ' . $i  . '</option>';
							}*/
							echo '<option value="' . $certificacionBT['num_inspeccion'] . '">' . $certificacionBT['num_inspeccion']  . '</option>';
						?>
					</select> 								
			</div>
			
			<div data-linea="11">
				<label>Fecha de fin de análisis:</label>
				<input type="text" id="fechaMuestra" name="fechaMuestra" required="required" />
			</div>
			
			<div data-linea="12">
				<label>Enfermedad:</label>
					<select id="enfermedad" name="enfermedad" required="required" >
						<?php 
							if ($certificacionBT['certificacion_bt'] == 'Brucelosis'){
								echo "<option value='Brucelosis'>Brucelosis</option>";		
							}else{
								echo "<option value='Tuberculosis'>Tuberculosis</option>";
							}
						
						?>								
					</select> 	
			</div>
			
			<div data-linea="12">
				<label>Cantidad muestras:</label>
                                <input type="number" id="cantidadMuestras" name="cantidadMuestras" required="required" />
			</div>
			
			<div data-linea="13">
				<label># Positivos:</label>
				<input type="number" id="numeroPositivos" name="numeroPositivos" required="required" />
			</div>
			
			<div data-linea="13">
				<label># Negativos:</label>
				<input type="number" id="numeroNegativos" name="numeroNegativos" required="required" />
			</div>
			
			<div data-linea="14">
				<label># Indeterminados:</label>
				<input type="number" id="numeroIndeterminados" name="numeroIndeterminados" required="required" />
			</div>
			
			<div data-linea="14">
				<label># Reactivos:</label>
				<input type="number" id="numeroReactivos" name="numeroReactivos" required="required" />
			</div>
			
			<div data-linea="15">
				<label># Sospechosos:</label>
				<input type="number" id="numeroSospechosos" name="numeroSospechosos" required="required" />
			</div>
			
			<div data-linea="15">
				<label>Prueba de Laboratorio:</label>
					<select id="pruebaLaboratorio" name="pruebaLaboratorio" required="required" >
						<option value="">Prueba Laboratorio....</option>
						<?php 
							if ($certificacionBT['certificacion_bt'] == 'Brucelosis'){
								echo ' <option value="1">Rosa de Bengala</option>
										<option value="2">ELISA indirecto</option>
										<option value="3">ELISA competitivo</option>
										<option value="4">MilkRing Test MRT</option>
										<option value="5">ELISA indirecto en leche</option>';		
							}else{
								echo ' <option value="6">Prueba en Leche</option>
										<option value="7">Tuberculina anocaudal</option>
										<option value="8">Cervical comparativa</option>
										<option value="9">Gama interferón</option>';
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
				<label>Observaciones visita analizada:</label>
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
			<legend>Resultado Global de los Análisis Realizados</legend>
			
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
				<input type="text" id="observaciones" name="observaciones" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
			</div>
				
			<div data-linea="55">
				<label>Informe de Laboratorio:</label>
				
				<input type="file" class="archivo" name="informe" accept="application/pdf" /> 
				
				<input type="hidden" class="rutaArchivo" id="archivoInforme" name="archivoInforme" value="" />
				
				<div class="estadoCarga">
					En espera de archivo... (Tamaño máximo; <?php echo ini_get("upload_max_filesize");?>B)
				</div>
				
				<button type="button" class="subirArchivoInforme" data-rutaCarga="aplicaciones/certificacionBrucelosisTuberculosis/informeLaboratorios/RecertificacionBTLaboratorios">Subir informe de Laboratorio</button>
			</div>
		</fieldset>
	
		<button type="submit" class="guardar">Guardar Resultados de Laboratorio</button>
		
	</form>
</div>

<script type="text/javascript">

var usuario = <?php echo json_encode($usuario); ?>;
var estado= <?php echo json_encode($certificacionBT['estado']); ?>;
var perfil= <?php echo json_encode($perfilAdmin); ?>;
var certificacion= <?php echo json_encode($certificacionBT['certificacion_bt']); ?>;

var certificacion = '<?php echo json_encode($certificacionBT['certificacion_bt']); ?>';

	$("document").ready(function(){
		distribuirLineas();	
		construirValidador();
		construirAnimacion($(".pestania"));

		$("#fechaMuestra").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		if(<?php echo json_encode($certificacionBT['nueva_inspeccion']); ?>=='Si'){
			$('#siguienteInspeccion').show();
			$('#siguienteInspeccion').addClass('exito'); //exito, advertencia, alerta
		}else{
			$('#siguienteInspeccion').hide();
		}
		
		if(usuario == '0'){
			$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#botonGuardar").attr("disabled", "disabled");
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

	function agregarAnalisis(){  
            $("#estado").html("");
            $("#estado").removeClass();
            $("#cantidadMuestras").removeClass();
            var suma = parseInt($("#numeroPositivos").val()) + parseInt($("#numeroNegativos").val()) + parseInt($("#numeroIndeterminados").val()) + parseInt($("#numeroReactivos").val()) + parseInt($("#numeroSospechosos").val());
            if (suma !== parseInt($("#cantidadMuestras").val())){
                $("#cantidadMuestras").addClass("alertaCombo");
                mostrarMensaje("La suma de las cantidades debe ser igual a " + $("#cantidadMuestras").val(),"FALLO");
            } else {
            if($("#muestra").val()!="" && $("#pruebaLaboratorio").val()!=""){

                    if($("#detallePruebaLaboratorio #r_"+$("#muestra").val()+$("#pruebaLaboratorio").val()).length==0){
                                    $("#detallePruebaLaboratorio").append("<tr id='r_"+$("#muestra").val()+$("#pruebaLaboratorio").val()+"'><td>"+$("#muestra  option:selected").text()+"</td><td>"+$("#fechaMuestra").val()+"</td><td>"+$("#enfermedad  option:selected").text()+"</td><td>"+$("#pruebaLaboratorio  option:selected").text()+"</td><td>"+$("#resultadoLaboratorio  option:selected").text()+"</td><td><input id='arrayMuestra' name='arrayMuestra[]' value='"+$("#muestra option:selected").text()+"' type='hidden'><input id='arrayFechaMuestra' name='arrayFechaMuestra[]' value='"+$("#fechaMuestra").val()+"' type='hidden'><input id='arrayEnfermedad' name='arrayEnfermedad[]' value='"+$("#enfermedad option:selected").val()+"' type='hidden'><input id='arrayCantidadMuestras' name='arrayCantidadMuestras[]' value='"+$("#cantidadMuestras").val()+"' type='hidden'><input id='arrayNumPositivos' name='arrayNumPositivos[]' value='"+$("#numeroPositivos").val()+"' type='hidden'><input id='arrayNumNegativos' name='arrayNumNegativos[]' value='"+$("#numeroNegativos").val()+"' type='hidden'><input id='arrayNumIndeterminados' name='arrayNumIndeterminados[]' value='"+$("#numeroIndeterminados").val()+"' type='hidden'><input id='arrayNumReactivos' name='arrayNumReactivos[]' value='"+$("#numeroReactivos").val()+"' type='hidden'><input id='arrayNumSospechosos' name='arrayNumSospechosos[]' value='"+$("#numeroSospechosos").val()+"' type='hidden'><input id='arrayIdPruebaLaboratorio' name='arrayIdPruebaLaboratorio[]' value='"+$("#pruebaLaboratorio option:selected").val()+"' type='hidden'><input id='arrayPruebaLaboratorio' name='arrayPruebaLaboratorio[]' value='"+$("#pruebaLaboratorio option:selected").text()+"' type='hidden'><input id='arrayResultadoLaboratorio' name='arrayResultadoLaboratorio[]' value='"+$("#resultadoLaboratorio option:selected").val()+"' type='hidden'><input id='arrayObservacionesMuestra' name='arrayObservacionesMuestra[]' value='"+$("#observacionesMuestra").val()+"' type='hidden'><button type='button' onclick='quitarAnalisis(\"#r_"+$("#muestra").val()+$("#pruebaLaboratorio").val()+"\")' class='menos'>Quitar</button></td></tr>");

                                    //$("#detallePruebaLaboratorio").append("<tr id='r_"+$("#muestra").val()+$("#pruebaLaboratorio").val()+"'><td>"+$("#muestra  option:selected").text()+"</td><td>"+$("#fechaMuestra").val()+"</td><td>"+$("#enfermedad  option:selected").text()+"</td><td>"+$("#cantidadMuestras").val()+"</td><td>"+$("#numeroPositivos").val()+"</td><td>"+$("#numeroNegativos").val()+"</td><td>"+$("#numeroIndeterminados").val()+"</td><td>"+$("#numeroReactivos").val()+"</td><td>"+$("#numeroSospechosos").val()+"</td><td>"+$("#pruebaLaboratorio  option:selected").text()+"</td><td>"+$("#resultadoLaboratorio  option:selected").text()+"</td><td>"+$("#observacionesMuestra").val()+"</td><td><input id='arrayMuestra' name='arrayMuestra[]' value='"+$("#muestra option:selected").text()+"' type='hidden'><input id='arrayFechaMuestra' name='arrayFechaMuestra[]' value='"+$("#fechaMuestra").val()+"' type='hidden'><input id='arrayEnfermedad' name='arrayEnfermedad[]' value='"+$("#enfermedad option:selected").val()+"' type='hidden'><input id='arrayCantidadMuestras' name='arrayCantidadMuestras[]' value='"+$("#cantidadMuestras").val()+"' type='hidden'><input id='arrayNumPositivos' name='arrayNumPositivos[]' value='"+$("#numeroPositivos").val()+"' type='hidden'><input id='arrayNumNegativos' name='arrayNumNegativos[]' value='"+$("#numeroNegativos").val()+"' type='hidden'><input id='arrayNumIndeterminados' name='arrayNumIndeterminados[]' value='"+$("#numeroIndeterminados").val()+"' type='hidden'><input id='arrayNumReactivos' name='arrayNumReactivos[]' value='"+$("#numeroReactivos").val()+"' type='hidden'><input id='arrayNumSospechosos' name='arrayNumSospechosos[]' value='"+$("#numeroSospechosos").val()+"' type='hidden'><input id='arrayIdPruebaLaboratorio' name='arrayIdPruebaLaboratorio[]' value='"+$("#pruebaLaboratorio option:selected").val()+"' type='hidden'><input id='arrayPruebaLaboratorio' name='arrayPruebaLaboratorio[]' value='"+$("#pruebaLaboratorio option:selected").text()+"' type='hidden'><input id='arrayResultadoLaboratorio' name='arrayResultadoLaboratorio[]' value='"+$("#resultadoLaboratorio option:selected").val()+"' type='hidden'><input id='arrayObservacionesMuestra' name='arrayObservacionesMuestra[]' value='"+$("#observacionesMuestra").val()+"' type='hidden'><button type='button' onclick='quitarAnalisis(\"#r_"+$("#muestra").val()+$("#pruebaLaboratorio").val()+"\")' class='menos'>Quitar</button></td></tr>");
                                    //$("#detallePruebaLaboratorio").append("<tr id='r_"+$("#muestra").val()+$("#pruebaLaboratorio").val()+"'><td>"+$("#muestra  option:selected").text()+"</td><td>"+$("#fechaMuestra").val()+"</td><td>"+$("#enfermedad  option:selected").text()+"</td><td>"+$("#pruebaLaboratorio  option:selected").text()+"</td><td>"+$("#resultadoLaboratorio  option:selected").text()+"</td><td><input id='arrayMuestra' name='arrayMuestra[]' value='"+$("#muestra option:selected").text()+"' type='hidden'><input id='arrayFechaMuestra' name='arrayFechaMuestra[]' value='"+$("#fechaMuestra").val()+"' type='hidden'><input id='arrayEnfermedad' name='arrayEnfermedad[]' value='"+$("#enfermedad option:selected").val()+"' type='hidden'><input id='arrayIdPruebaLaboratorio' name='arrayIdPruebaLaboratorio[]' value='"+$("#pruebaLaboratorio option:selected").val()+"' type='hidden'><input id='arrayPruebaLaboratorio' name='arrayPruebaLaboratorio[]' value='"+$("#pruebaLaboratorio option:selected").text()+"' type='hidden'><input id='arrayResultadoLaboratorio' name='arrayResultadoLaboratorio[]' value='"+$("#resultadoLaboratorio option:selected").val()+"' type='hidden'><button type='button' onclick='quitarAnalisis(\"#r_"+$("#muestra").val()+$("#pruebaLaboratorio").val()+"\")' class='menos'>Quitar</button></td></tr>");
                    }

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
	
	//Validación y Guardado
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	//Resultados de Análisis
	$("#nuevaPruebaLaboratorio").submit(function(event){

		$("#nuevaPruebaLaboratorio").attr('data-opcion', 'guardarPruebaLaboratorioRecertificacion');
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


	$("#motivoCatastro").change(function(){
    	$("#nombreMotivoCatastro").val($("#motivoCatastro option:selected").text());
	});

</script>