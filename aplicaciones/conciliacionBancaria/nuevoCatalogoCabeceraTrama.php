<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();

$idTrama = $_POST['idTrama'];
$idCampoCabeceraTrama = $_POST['idCampoCabeceraTrama'];

$qCamposCabecera = $cb -> obtenerCamposCabeceraXIdCampoCabeceraTrama($conexion, $idCampoCabeceraTrama);
$camposCabeceraTrama = pg_fetch_assoc($qCamposCabecera);

$qCatalogosCampoCabecera = $cb -> obtenerCatalogosCampoCabeceraXIdCampo($conexion, $camposCabeceraTrama['id_campo_cabecera']);

?>

<div id="estado"></div>

	<header>
		<h1>Catálogos de cabecera</h1>
	</header>
	
	<form id="regresar" data-rutaAplicacion="conciliacionBancaria" data-opcion="abrirRegistroTrama" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idTrama;?>"/>
		<input type="hidden" name="numeroPestania" value="2"/>
		<button class="regresar">Regresar a Cabecera Trama</button>
	</form>

	<form id="abrirCampoCabecera" data-rutaAplicacion="conciliacionBancaria" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="idCampoCabeceraTrama" value="<?php echo $idCampoCabeceraTrama;?>"/>
		<input type="hidden" id="opcion" name="opcion" value="campoCabeceraTrama" />
		<fieldset id="campoCabeceraTrama">
		<legend>Campo de cabecera</legend>
			<div data-linea="1">
				<label>Nombre campo:</label>
				<input type="text" id="nombreCampoCabeceraTrama" name="nombreCampoCabeceraTrama" value="<?php echo $camposCabeceraTrama['nombre_campo_cabecera']; ?>" disabled="disabled"/>
			</div>
			<div data-linea="2">
				<label>Posición inicial:</label>
				<input type="text" id="posicionInicialCampoCabeceraTrama" name="posicionInicialCampoCabeceraTrama" value="<?php echo $camposCabeceraTrama['posicion_inicial_campo_cabecera']; ?>" disabled="disabled" onkeypress="soloNumeros()"/>
			</div>
			<div data-linea="2">
				<label>Posición final:</label>
				<input type="text" id="posicionFinalCampoCabeceraTrama" name="posicionFinalCampoCabeceraTrama" value="<?php echo $camposCabeceraTrama['posicion_final_campo_cabecera']; ?>" disabled="disabled" onkeypress="soloNumeros()"/>
			</div>
			<div data-linea="3">
				<label>Longitud segmento:</label>
				<input type="text" id="longitudSegmentoCampoCabeceraTrama" name="longitudSegmentoCampoCabeceraTrama" value="<?php echo $camposCabeceraTrama['longitud_segmento_campo_cabecera']; ?>" disabled="disabled" readonly="readonly"/>
			</div>
			<div data-linea="3">
				<label>Tipo campo:</label>
				<select id="tipoCampoCabeceraTrama" name="tipoCampoCabeceraTrama" disabled="disabled" >
					<option value="0">Seleccione...</option>
					<option value="obligatorio">Obligatorio</option>
					<option value="opcional">Opcional</option>
				</select>
			</div>
			<div>
				<button id="modificar" type="button" class="editar">Modificar</button>
				<button id="actualizar" type="submit" class="guardar" disabled="disabled" disabled="disabled">Actualizar</button>
			</div>
		</fieldset>
	</form>
	
	<form id="anadirCatalogoCampoCabeceraTrama" data-rutaAplicacion="conciliacionBancaria" data-opcion="guardarCatalogosCampo" >
		<input type="hidden" id="opcion" name="opcion" value="catalogoCampoCabeceraTrama" />
		<input type="hidden" name="idCampoCabeceraTrama" value="<?php echo $camposCabeceraTrama['id_campo_cabecera'];?>"/>		
		<fieldset id="anadrCatalogoCabeceraTrama">
			<legend>Añadir Catálogo a Cabecera</legend>
			<div data-linea="4">
				<label>Código:</label>
				<input type="text" id="codigoCatalogoCabeceraTrama" name="codigoCatalogoCabeceraTrama"/>
			</div>
			<div data-linea="5">
				<label>Nombre:</label>
				<input type="text" id="nombreCatalogoCabeceraTrama" name="nombreCatalogoCabeceraTrama"/>
			</div>
			<div>
				<button type="submit" class="mas">Agregar campo</button>
			</div>
		</fieldset>		
	</form>
			
	<fieldset id="catalogoCabeceraTrama">
		<legend>Catálogo / Cabecera</legend>
		<table id="codigoCatalagoCabecera">
			<thead><tr><th>Código</th><th>Nombre</th><th>Opción</th></thead>
					<?php 
						while ($catalogosCampoCabecera = pg_fetch_assoc($qCatalogosCampoCabecera)){
							echo $cb -> imprimirLineaCatalogoCampoCabecera($catalogosCampoCabecera['id_catalogo_campo_cabecera'], $catalogosCampoCabecera['codigo_catalogo_campo_cabecera'], $catalogosCampoCabecera['nombre_catalogo_campo_cabecera']);
						}
					?>
		</table>
	</fieldset>

<script type="text/javascript">			
	
 	$(document).ready(function(){	    	
    	distribuirLineas();	  
    	cargarValorDefecto("tipoCampoCabeceraTrama","<?php echo $camposCabeceraTrama["tipo_campo_cabecera"];?>");
    	//acciones("#anadirCatalogoCampoCabeceraTrama","#codigoCatalagoCabecera");
    	acciones('#anadirCatalogoCampoCabeceraTrama', '#codigoCatalagoCabecera', null, null, new exitoCatalogoCampoCabeceraTrama(), null, null, new verificarInputsCatalogoCampoCabeceraTrama());
 	});

	$("#abrirCampoCabecera").click(function(){
	    $("#nombreCampoCabeceraTrama").removeAttr("disabled");
		$("#posicionInicialCampoCabeceraTrama").removeAttr("disabled");
		$("#posicionFinalCampoCabeceraTrama").removeAttr("disabled");
		$("#longitudSegmentoCampoCabeceraTrama").removeAttr("disabled");
		$("#tipoCampoCabeceraTrama").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#abrirCampoCabecera").submit(function(){
  	 	event.preventDefault();
  	    $(".alertaCombo").removeClass("alertaCombo");
  	  	var error = false;

    	if($("#nombreCampoCabeceraTrama").val()==""){
			error = true;
			$("#nombreCampoCabeceraTrama").addClass("alertaCombo");
		}

    	if($("#posicionInicialCampoCabeceraTrama").val()==""){
			error = true;
			$("#posicionInicialCampoCabeceraTrama").addClass("alertaCombo");
		}

    	if($("#posicionFinalCampoCabeceraTrama").val()==""){
			error = true;
			$("#posicionFinalCampoCabeceraTrama").addClass("alertaCombo");
		}

    	if($("#longitudSegmentoCampoCabeceraTrama").val()==""){
			error = true;
			$("#longitudSegmentoCampoCabeceraTrama").addClass("alertaCombo");
		}

    	if($("#tipoCampoCabeceraTrama").val()==""){
			error = true;
			$("#tipoCampoCabeceraTrama").addClass("alertaCombo");
		}

    	if($("#posicionInicialCampoCabeceraTrama").val() >= $("#posicionFinalCampoCabeceraTrama").val()){
    		error = true;
	   		$("#posicionFinalCampoCabeceraTrama").addClass("alertaCombo");	    	
	    }

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				$('#abrirCampoCabecera').attr('data-opcion','modificarCampoRegistroTrama');
				ejecutarJson($(this));                             
		}
	});

	function verificarInputsCatalogoCampoCabeceraTrama() {

		this.ejecutar = function () {
	        var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
	
	        if ($("#codigoCatalogoCabeceraTrama").val() == "") {
	            $("#codigoCatalogoCabeceraTrama").addClass("alertaCombo");
	            error = true;
	        }
	        if ($("#nombreCatalogoCabeceraTrama").val() == "") {
	            $("#nombreCatalogoCabeceraTrama").addClass("alertaCombo");
	            error = true;
	        }
	        return !error;

	    };

		this.mensajeError = function () {
			mostrarMensaje("Llene todos los datos del formulario", "FALLO");
		}
    }

    function exitoCatalogoCampoCabeceraTrama() {
        this.ejecutar = function (msg) {
            mostrarMensaje("Nuevo registro agregado", "EXITO");
            var fila = msg.mensaje;
            $("#codigoCatalogoCabeceraTrama").val("");
            $("#nombreCatalogoCabeceraTrama").val("");
            $("#codigoCatalagoCabecera").append(fila);
        };
    }

    $("#posicionFinalCampoCabeceraTrama").change(function(){
	    longitud = ($("#posicionFinalCampoCabeceraTrama").val() - $("#posicionInicialCampoCabeceraTrama").val()) + 1;  	
    	$("#longitudSegmentoCampoCabeceraTrama").val(longitud);
    });

 	function soloNumeros(){			 
 		if ((event.keyCode < 48) || (event.keyCode > 57))
 			event.returnValue = false;	
 	}

</script>