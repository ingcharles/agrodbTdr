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
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
	
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Administrador Certificación Brucelosis y Tuberculosis'),0,'id_perfil');
	}
	
	$ruta = 'certificacionBrucelosisTuberculosis';
	
	if($_POST['elementos'] != null){		
		$certificacion = explode(",",$_POST['elementos']);
		$idCertificacionBT = $certificacion[0];
		$certificacionBT = pg_fetch_assoc($cbt->abrirCertificacionBT($conexion, $idCertificacionBT));
		
		if($certificacionBT['estado']=='porExpirar'){
			$datos = 1;
		}else{
			$datos = 0;
		}
	}else{
		$datos = 0;
	}
	
?>

<header>
	<h1>Predios para Recertificación como Libres de Brucelosis y Tuberculosis Bovina</h1>
</header>

<div id="estado"></div>

<form id="nuevaRecertificacionBT" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="guardarRecertificacionBT" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type='hidden' id='idCertificacionBT' name='idCertificacionBT' value="<?php echo $idCertificacionBT;?>" />	

	<fieldset>
			<legend>Información de Localización del Predio</legend>
	
			<div data-linea="0">
				<label>N° Solicitud:</label>
				<?php echo $certificacionBT['num_solicitud'];?>
			</div>
				
			<div data-linea="1">
				<label>Fecha:</label>
				<input type="text" id="fecha" name="fecha" required="required"/>
			</div>
			
			<div data-linea="2">
				<label>Nombre del Encuestado:</label>
				<input type="text" id="nombreEncuestado" name="nombreEncuestado" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
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
				<label>Recertificación:</label>
				<?php echo $certificacionBT['certificacion_bt'];?>
			</div>
			
			<div data-linea="4">
				<label id="lFechaMuestreoBrucelosis">Fecha de último muestreo de Brucelosis:</label>
				<input type="text" id="fechaMuestreoBrucelosis" name="fechaMuestreoBrucelosis" />
			</div>
			
			<div data-linea="5">
				<label id="lFechaTuberculinizacion">Fecha de última Tuberculinización:</label>
				<input type="text" id="fechaTuberculinizacion" name="fechaTuberculinizacion" />
			</div>
		
		</fieldset>
		
		<fieldset>
			<legend>Información del Propietario</legend>			
			
			<div data-linea="6">
				<label>Nombre:</label>
				<?php echo $certificacionBT['nombre_propietario'];?>
			</div>
			
			<div data-linea="6">
				<label>Cédula:</label>
				<?php echo $certificacionBT['cedula_propietario'];?>
				<input type="hidden" id="identificador" name="identificador" value="<?php echo $certificacionBT['cedula_propietario'];?>" />
			</div>
			
			<div data-linea="7">
				<label>Teléfono:</label>
				<?php echo $certificacionBT['telefono_propietario'];?>
			</div>
			
			<div data-linea="7">
				<label>Celular:</label>
				<?php echo $certificacionBT['celular_propietario'];?>
			</div>
			
			<div data-linea="8">
				<label>Correo Electrónico:</label>
				<?php echo $certificacionBT['correo_electronico_propietario'];?>
			</div>
			
		</fieldset>
		
		<fieldset>
			<legend>Ubicación y Datos Generales</legend>
	
			<div data-linea="11">
				<label>Provincia</label>
				<?php echo $certificacionBT['provincia'];?>	
			</div>
				
			<div data-linea="11">
				<label>Cantón</label>
					<?php echo $certificacionBT['canton'];?>
				</div>
				
			<div data-linea="13">	
				<label>Parroquia</label>
					<?php echo $certificacionBT['parroquia'];?>
			</div>
						
		</fieldset>
		
		<fieldset>
			<legend>Información del Técnico Responsable</legend>			
			
			<div data-linea="14">
				<label>Técnico Responsable (externo):</label>
				<input type="text" id="nombreTecnicoResponsable" name="nombreTecnicoResponsable" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
		</fieldset>
		
		<fieldset id="adjuntosInforme">
			<legend>Informe</legend>
	
			<div data-linea="12">
				<input type="file" class="archivo" name="informe" accept="application/pdf" /> 
				
				<input type="hidden" class="rutaArchivo" name="archivoInforme" value="" />
				
				<div class="estadoCarga">
					En espera de archivo... (Tamaño máximo; <?php echo ini_get("upload_max_filesize");?>B)
				</div>
				
				<button type="button" class="subirArchivoInforme" data-rutaCarga="aplicaciones/certificacionBrucelosisTuberculosis/informe/recertificacionBT">Subir informe</button>
			</div>
		</fieldset>
	
	<button type="submit" class="guardar">Guardar</button>

</form>


<script type="text/javascript">
var certificacion= <?php echo json_encode($certificacionBT['certificacion_bt']); ?>;
var datos= <?php echo json_encode($datos); ?>;

	$("document").ready(function(){
		distribuirLineas();	
		construirValidador();

		if(datos == '0'){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione un certificado por expirar para continuar.</div>');
		}

		$("#fecha").datepicker({
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

		$("#lFechaTuberculinizacion").hide();
		$("#fechaTuberculinizacion").hide();
		$("#lFechaMuestreoBrucelosis").hide();
		$("#fechaMuestreoBrucelosis").hide();

		if(certificacion == "Brucelosis"){
			$("#lFechaMuestreoBrucelosis").show();
			$("#fechaMuestreoBrucelosis").show();
			$("#fechaMuestreoBrucelosis").attr('required', 'required');
			
			$("#lFechaTuberculinizacion").hide();
			$("#fechaTuberculinizacion").hide();
		}else{
			$("#lFechaMuestreoBrucelosis").hide();
			$("#fechaMuestreoBrucelosis").hide();
			
			$("#lFechaTuberculinizacion").show();
			$("#fechaTuberculinizacion").show();
			$("#fechaTuberculinizacion").attr('required', 'required');
		}
	});
	
	//Validación y Guardado
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	$("#nuevaRecertificacionBT").submit(function(event){
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

		if(!$.trim($("#nombreTecnicoResponsable").val()) || !esCampoValido("#nombreTecnicoResponsable")){
			error = true;
			$("#nombreTecnicoResponsable").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			abrir($(this),event,false);
		}
	});

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

</script>