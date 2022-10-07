<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorTramitesInocuidad.php';

$conexion = new Conexion();

$cc = new ControladorCatalogos();
$cti = new ControladorTramitesInocuidad();
$cr = new ControladorRegistroOperador();

$idSolicitud = $_POST['id'];
$identificadorUsuario = $_SESSION['usuario'];

$tramite = pg_fetch_assoc($cti->obtenerTramiteInocuidad($conexion, $idSolicitud));

$operador = pg_fetch_assoc($cr->buscarOperador($conexion, $tramite['identificador_operador']));

$producto = pg_fetch_assoc($cc ->obtenerTipoSubtipoXProductos($conexion, $tramite['id_producto']));

?>

<header>
	<h1>Tramite</h1>
</header>

	<div id="estado"></div>
	
	<div class="pestania">

	
	<fieldset>
			<legend>Datos del operador</legend>
			
			<div data-linea="1">
				<label>Identificación: </label> <?php echo $operador['identificador']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Razón social: </label> <?php echo ($operador['razon_social']==''?$operador['apellido_representante'].' '.$operador['nombre_representante']:$operador['razon_social']); ?> 
			</div>
			
	</fieldset>
	
	<fieldset>
			<legend>Datos del producto</legend>
			
			<div data-linea="1">
				<label>Tipo producto: </label> <?php echo $producto['nombre_tipo']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Subtipo producto: </label> <?php echo $producto['nombre_subtipo']; ?> 
			</div>
			
			<div data-linea="3">
				<label>Producto: </label> <?php echo $tramite['nombre_producto']; ?> 
			</div>
			
	</fieldset>
	
	<fieldset>
		<legend>Datos generales</legend>
			<div data-linea="1">
				<label>Tipo tramite: </label> <?php echo $tramite['nombre_tipo_tramite'];?> 
			</div>
			
			<div data-linea="2">
				<label>Observación: </label> <?php echo ($tramite['observacion']==''?'Sin observación':$tramite['observacion']); ?> 
			</div>
			
	</fieldset>

</div>


<div class="pestania">	
	<form id="evaluarDocumentosSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarDocumentosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $identificadorUsuario;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="tipoSolicitud" value="tramitesInocuidad"/>
		<input type="hidden" name="tipoInspector" value="Documental"/>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
					
				<div data-linea="1">
					<label>Resultado</label>
						<select id="resultadoDocumento" name="resultadoDocumento">
							<option value="">Seleccione....</option>
							<option value="emisionRespuesta">Aprobado</option>
							<option value="rechazado">Rechazado</option>
							<option value="observado">Observado</option>
						</select>
				</div>
				
				<div data-linea="1">
					<label>Documentos falsos</label>
						<select id="documentoFalso" name="documentoFalso">
							<option value="">Seleccione....</option>
							<option value="si">Si</option>
							<option value="no">No</option>
						</select>
				</div>
					
				<div data-linea="2">
					<label>Observaciones</label>
					<input type="text" id="observacionDocumento" name="observacionDocumento"/>
				</div>
				
		</fieldset>	
		
		<button type="submit" class="guardar">Enviar resultado</button>		
	</form> 
	
	<form id="evaluarTramiteAsistente" data-rutaAplicacion="tramitesInocuidad" data-opcion="evaluarTramiteAsistente" data-accionEnExito="ACTUALIZAR">
	
		<input type="hidden" name="inspector" value="<?php echo $identificadorUsuario;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="estado" value="porEntregar"/>
	
		<fieldset>
			<legend>Resultado de Revisión</legend>
				
				<div data-linea="1">
					<label>Número oficio</label>
						<input type="text" id="numeroOficio" name="numeroOficio"/>
				</div>
				
				<div data-linea="2">
					<label>Observación</label>
						<input type="text" id="observacionAsistente" name="observacionAsistente"/>
				</div>
				
		</fieldset>
		
		<button type="submit" class="guardar">Enviar tramite</button>	
				
	</form>
	
	
</div>    

<script type="text/javascript">

var estado= <?php echo json_encode($tramite['estado']); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));

		$("#evaluarDocumentosSolicitud").hide();
		$("#evaluarTramiteAsistente").hide();

		if(estado == "enviado"){
			$("#evaluarDocumentosSolicitud").show();
		}

		if(estado == "emisionRespuesta"){
			$("#evaluarTramiteAsistente").show();
		}
		

		
	});

	$("#evaluarDocumentosSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccionDocumental(this);
	});

	function chequearCamposInspeccionDocumental(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if(!$.trim($("#resultadoDocumento").val()) || !esCampoValido("#resultadoDocumento")){
			error = true;
			$("#resultadoDocumento").addClass("alertaCombo");
		}

		if(!$.trim($("#documentoFalso").val()) || !esCampoValido("#documentoFalso")){
			error = true;
			$("#documentoFalso").addClass("alertaCombo");
		}

		if(!$.trim($("#observacionDocumento").val()) || !esCampoValido("#observacionDocumento")){
			error = true;
			$("#observacionDocumento").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}

	$("#evaluarTramiteAsistente").submit(function(event){
		event.preventDefault();
		chequearCamposTramiteAsistente(this);
	});

	function chequearCamposTramiteAsistente(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if(!$.trim($("#numeroOficio").val()) || !esCampoValido("#numeroOficio")){
			error = true;
			$("#numeroOficio").addClass("alertaCombo");
		}

		if(!$.trim($("#observacionAsistente").val()) || !esCampoValido("#observacionAsistente")){
			error = true;
			$("#observacionAsistente").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
</script>
