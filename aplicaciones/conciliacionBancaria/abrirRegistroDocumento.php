<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();

$qDocumento = $cb -> abrirDocumentoXIdDocumento($conexion, $idDocumento = ($_POST['id']== "") ? $_POST['idDocumento'] : $_POST['id']);
$documento = pg_fetch_assoc($qDocumento);

$qCamposDocumento = $cb -> obtenerCamposDocumentoXIdDocumento($conexion, $documento['id_documento']);

?>



<div id="estado"></div>
	
<div class="pestania">	

<header>
		<h1>Modificar Documento</h1>
	</header>
		<form id="abrirRegistroDocumento" data-rutaAplicacion="conciliacionBancaria" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" id="idDocumento" name="idDocumento" value="<?php echo $documento['id_documento'];?>" />
			<fieldset id="informacionDocumento">
				<legend>Información de Documento</legend>
				<div data-linea="1">
					<label>Nombre Documento:</label>
					<input type="text" id="nombreDocumento" name="nombreDocumento" value="<?php echo $documento["nombre_documento"];?>" disabled="disabled" />
				</div>
				<div data-linea="2">
					<label>Tipo de documento: </label>
					<select id="tipoDocumento" name="tipoDocumento" disabled="disabled" >
						<option value="">Seleccione...</option>
						<option value="estadoCuenta">Estado de Cuenta</option>
						<option value="reporteTransacciones">Reporte de Transacciones</option>
					</select>
				</div>
			</fieldset>
			
			<fieldset id="parametrosLectura">
				<legend>Parámetros de lectura</legend>
					<div data-linea="3">
						<label>Formato de entrada:</label>
						<select id="formatoEntradaDocumento" name="formatoEntradaDocumento" disabled="disabled" >
							<option value="">Seleccione...</option>
							<option value="xls">.xls</option>
							<option value="csv">.csv</option>
							<option value="xml">.xml</option>
						</select>
					</div>
					<div data-linea="3">
						<label>Número de columnas:</label>
						<input type="text" id="numeroColumnasDocumento" name="numeroColumnasDocumento" value="<?php echo $documento["numero_columnas_documento"];?>" disabled="disabled" />
					</div>
					<div data-linea="4">
						<label>Fila inicio lectura:</label>
						<input type="text" id="filaInicioLecturaDocumento" name="filaInicioLecturaDocumento" value="<?php echo $documento["fila_inicio_lectura_documento"];?>" disabled="disabled" />
					</div>
					<div data-linea="4">
						<label>Columna inicio lectura:</label>
						<input type="text" id="columnaInicioLecturaDocumento" name="columnaInicioLecturaDocumento" value="<?php echo $documento["columna_inicio_lectura_documento"];?>" disabled="disabled" />
					</div>
			</fieldset>
			<div>
				<button id="modificar" type="button" class="editar">Modificar</button>
				<button id="actualizar" type="submit" class="guardar" disabled="disabled" disabled="disabled">Actualizar</button>
			</div>
		</form>
</div>

<div class="pestania">	

	<form id="abrirCabeceraDocumento" data-rutaAplicacion="conciliacionBancaria" data-opcion="guardarCamposDocumento" >
	<input type="hidden" id="idDocumento" name="idDocumento" value="<?php echo $documento['id_documento'];?>" />
		<fieldset id="informacionDocumento">
			<legend>Añadir Columnas</legend>
			<div data-linea="5">
				<label>Nombre campo:</label>
				<input type="text" id="nombreCampoDocumento" name="nombreCampoDocumento" />
			</div>
			<div data-linea="6">
				<label>Posición columna: </label>
				<input type="text" id="posicionCampoDocumento" name="posicionCampoDocumento" />
			</div>
			<div data-linea="7">
				<label>Tipo campo:</label>
				<select id="tipoCampoDocumento" name="tipoCampoDocumento" >
					<option value="">Seleccione...</option>
					<option value="obligatorio">Obligatorio</option>
					<option value="opcional">Opcional</option>
				</select>
			</div>
			<div>
				<button type="submit" class="mas">Agregar campo</button>
			</div>
		</fieldset>
	</form>
	
	<fieldset id="anadirCampoCabeceraDocumento">
	<legend>Columnas de documento</legend>
		<table id="codigoDocumento">
				<thead><tr><th>Nombre</th><th>Posic. Columna</th><th>TipoCampo</th><th colspan="4">Opciones</th></tr></thead>
						<?php 
							while ($camposDocumento = pg_fetch_assoc($qCamposDocumento)){
								echo $cb -> imprimirLineaCampoDocumento($camposDocumento['id_campo_documento'], $camposDocumento['nombre_campo_documento'], $camposDocumento['posicion_campo_documento'], $camposDocumento['tipo_campo_documento']);
							}
						?>
				</table>
	</fieldset>
	
</div>


<script type="text/javascript">			

    $(document).ready(function(){	
    	distribuirLineas();	    	
    	construirAnimacion($(".pestania"));
    	cargarValorDefecto("tipoDocumento","<?php echo $documento["tipo_documento"];?>");
    	cargarValorDefecto("formatoEntradaDocumento","<?php echo $documento["formato_entrada_documento"];?>");
    	//acciones("#abrirCabeceraDocumento","#codigoDocumento");
    	acciones('#abrirCabeceraDocumento', '#codigoDocumento', null, null, new exitoCampoDocumento(), null, null, new verificarInputsCampoDocumento());
    	actualizarBotonesOrdenamiento("#codigoDocumento"); 
    });

    $("#modificar").click(function(){
    	$("#nombreDocumento").removeAttr("disabled");
		$("#tipoDocumento").removeAttr("disabled");
		$("#formatoEntradaDocumento").removeAttr("disabled");
		$("#numeroColumnasDocumento").removeAttr("disabled");
		$("#filaInicioLecturaDocumento").removeAttr("disabled");
		$("#columnaInicioLecturaDocumento").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

    $("#abrirRegistroDocumento").submit(function(){

  	 	event.preventDefault();
  	    $(".alertaCombo").removeClass("alertaCombo");
  	  	var error = false;

    	if($("#nombreDocumento").val()==""){
			error = true;
			$("#nombreDocumento").addClass("alertaCombo");
		}

    	if($("#tipoDocumento").val()==""){
			error = true;
			$("#tipoDocumento").addClass("alertaCombo");
		}

    	if($("#formatoEntradaDocumento").val()==""){
			error = true;
			$("#formatoEntradaDocumento").addClass("alertaCombo");
		}

    	if($("#numeroColumnasDocumento").val()==""){
			error = true;
			$("#numeroColumnasDocumento").addClass("alertaCombo");
		}

    	if($("#filaInicioLecturaDocumento").val()==""){
			error = true;
			$("#filaInicioLecturaDocumento").addClass("alertaCombo");
		}

    	if($("#columnaInicioLecturaDocumento").val()==""){
			error = true;
			$("#columnaInicioLecturaDocumento").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				$('#abrirRegistroDocumento').attr('data-opcion','modificarRegistroDocumento');
				ejecutarJson($(this));                             
		}
    });

    function verificarInputsCampoDocumento() {

		this.ejecutar = function () {
	        var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
	
	        if ($("#nombreCampoDocumento").val() == "") {
	            $("#nombreCampoDocumento").addClass("alertaCombo");
	            error = true;
	        }
	        if ($("#posicionCampoDocumento").val() == "") {
	            $("#posicionCampoDocumento").addClass("alertaCombo");
	            error = true;
	        }
	        if ($("#tipoCampoDocumento").val() == "") {
	            $("#tipoCampoDocumento").addClass("alertaCombo");
	            error = true;
	        }
	        return !error;

	    };

		this.mensajeError = function () {
			mostrarMensaje("Llene todos los datos del formulario", "FALLO");
		}
    }

    function exitoCampoDocumento() {
        this.ejecutar = function (msg) {
            mostrarMensaje("Nuevo registro agregado", "EXITO");
            var fila = msg.mensaje;
            $("#nombreCampoDocumento").val("");
            $("#posicionCampoDocumento").val("");
            $("#tipoCampoDocumento").val("");
            $("#codigoDocumento").append(fila);
        };
    }
    
    
</script>