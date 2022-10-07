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
$idCampoDetalleTrama = $_POST['idCampoDetalleTrama'];

$qCamposDetalle = $cb -> obtenerCamposDetalleXIdCampoDetalleTrama($conexion, $idCampoDetalleTrama);
$camposDetalleTrama = pg_fetch_assoc($qCamposDetalle);

$qCatalogosCampoDetalle = $cb -> obtenerCatalogosCampoDetalleXIdCampo($conexion, $camposDetalleTrama['id_campo_detalle']);

?>

<div id="estado"></div>

	<header>
		<h1>Catálogos de detalle</h1>
	</header>
	
	<form id="regresar" data-rutaAplicacion="conciliacionBancaria" data-opcion="abrirRegistroTrama" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idTrama;?>"/>
		<input type="hidden" name="numeroPestania" value="3"/>
		<button class="regresar">Regresar a Detalle Trama</button>
	</form>
	
	<form id="abrirCampoDetalle" data-rutaAplicacion="conciliacionBancaria" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="idCampoDetalleTrama" value="<?php echo $idCampoDetalleTrama;?>"/>
		<input type="hidden" id="opcion" name="opcion" value="campoDetalleTrama" />
		<fieldset id="campoDetalleTrama">
		<legend>Campo de detalle</legend>
			<div data-linea="1">
				<label>Nombre campo:</label>
				<input type="text" id="nombreCampoDetalleTrama" name="nombreCampoDetalleTrama" value="<?php echo $camposDetalleTrama['nombre_campo_detalle']; ?>" disabled="disabled"/>
			</div>
			<div data-linea="2">
				<label>Posición inicial:</label>
				<input type="text" id="posicionInicialCampoDetalleTrama" name="posicionInicialCampoDetalleTrama" value="<?php echo $camposDetalleTrama['posicion_inicial_campo_detalle']; ?>" disabled="disabled" onkeypress="soloNumeros()"/>
			</div>
			<div data-linea="2">
				<label>Posición final:</label>
				<input type="text" id="posicionFinalCampoDetalleTrama" name="posicionFinalCampoDetalleTrama" value="<?php echo $camposDetalleTrama['posicion_final_campo_detalle']; ?>" disabled="disabled" onkeypress="soloNumeros()"/>
			</div>
			<div data-linea="3">
				<label>Longitud segmento:</label>
				<input type="text" id="longitudSegmentoCampoDetalleTrama" name="longitudSegmentoCampoDetalleTrama" value="<?php echo $camposDetalleTrama['longitud_segmento_campo_detalle']; ?>" disabled="disabled" readonly="readonly"/>
			</div>
			<div data-linea="3">
				<label>Tipo campo:</label>
				<select id="tipoCampoDetalleTrama" name="tipoCampoDetalleTrama" disabled="disabled" >
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
	
	<form id="anadirCatalogoCampoDetalleTrama" data-rutaAplicacion="conciliacionBancaria" data-opcion="guardarCatalogosCampo" >
	<input type="hidden" id="opcion" name="opcion" value="catalogoDetalleCabeceraTrama" />
		<input type="hidden" name="idCampoDetalleTrama" value="<?php echo $camposDetalleTrama['id_campo_detalle'];?>"/>	
		<fieldset id="anadirCatalogoDetalleTrama">
			<legend>Añadir Catálogo a Detalle</legend>
			<div data-linea="4">
				<label>Código:</label>
				<input type="text" id="codigoCatalogoDetalleTrama" name="codigoCatalogoDetalleTrama"/>
			</div>
			<div data-linea="5">
				<label>Nombre:</label>
				<input type="text" id="nombreCatalogoDetalleTrama" name="nombreCatalogoDetalleTrama"/>
			</div>
			<div>
				<button type="submit" class="mas">Agregar campo</button>
			</div>
		</fieldset>
	</form>	
		
	<fieldset id="catalogoDetalleTrama">
		<legend>Catálogo / Detalle</legend>
		<table id="codigoCatalagoDetalle">
			<thead><tr><th>Código</th><th>Nombre</th><th>Opción</th></thead>
				<?php 
					while ($catalogosCampoDetalle = pg_fetch_assoc($qCatalogosCampoDetalle)){
						echo $cb -> imprimirLineaCatalogoCampoDetalle($catalogosCampoDetalle['id_catalogo_campo_detalle'], $catalogosCampoDetalle['codigo_catalogo_campo_detalle'], $catalogosCampoDetalle['nombre_catalogo_campo_detalle']);
					}
				?>
		</table>
	</fieldset>
		
<script type="text/javascript">			

    $(document).ready(function(){	
    	distribuirLineas();	
    	cargarValorDefecto("tipoCampoDetalleTrama","<?php echo $camposDetalleTrama["tipo_campo_detalle"];?>");
    	//acciones("#anadirCatalogoCampoDetalleTrama","#codigoCatalagoDetalle");
    	acciones('#anadirCatalogoCampoDetalleTrama', '#codigoCatalagoDetalle', null, null, new exitoCatalogoCampoDetalleTrama(), null, null, new verificarInputsCatalogoCampoDetalleTrama());
    });  

	$("#abrirCampoDetalle").click(function(){
	    $("#nombreCampoDetalleTrama").removeAttr("disabled");
		$("#posicionInicialCampoDetalleTrama").removeAttr("disabled");
		$("#posicionFinalCampoDetalleTrama").removeAttr("disabled");
		$("#longitudSegmentoCampoDetalleTrama").removeAttr("disabled");
		$("#tipoCampoDetalleTrama").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});
	
	$("#abrirCampoDetalle").submit(function(){
  	 	event.preventDefault();
  	    $(".alertaCombo").removeClass("alertaCombo");
  	  	var error = false;

    	if($("#nombreCampoDetalleTrama").val()==""){
			error = true;
			$("#nombreCampoDetalleTrama").addClass("alertaCombo");
		}

    	if($("#posicionInicialCampoDetalleTrama").val()==""){
			error = true;
			$("#posicionInicialCampoDetalleTrama").addClass("alertaCombo");
		}

    	if($("#posicionFinalCampoDetalleTrama").val()==""){
			error = true;
			$("#posicionFinalCampoDetalleTrama").addClass("alertaCombo");
		}

    	if($("#longitudSegmentoCampoDetalleTrama").val()==""){
			error = true;
			$("#longitudSegmentoCampoDetalleTrama").addClass("alertaCombo");
		}

    	if($("#tipoCampoDetalleTrama").val()==""){
			error = true;
			$("#tipoCampoDetalleTrama").addClass("alertaCombo");
		}

    	if($("#posicionInicialCampoDetalleTrama").val() >= $("#posicionFinalCampoDetalleTrama").val()){
    		error = true;
	   		$("#posicionFinalCampoDetalleTrama").addClass("alertaCombo");	    	
	    }

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				$('#abrirCampoDetalle').attr('data-opcion','modificarCampoRegistroTrama');
				ejecutarJson($(this));                             
		}
	});

	function verificarInputsCatalogoCampoDetalleTrama() {

		this.ejecutar = function () {
	        var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
	
	        if ($("#codigoCatalogoDetalleTrama").val() == "") {
	            $("#codigoCatalogoDetalleTrama").addClass("alertaCombo");
	            error = true;
	        }
	        if ($("#nombreCatalogoDetalleTrama").val() == "") {
	            $("#nombreCatalogoDetalleTrama").addClass("alertaCombo");
	            error = true;
	        }
	        return !error;

	    };

		this.mensajeError = function () {
			mostrarMensaje("Llene todos los datos del formulario", "FALLO");
		}
    }

    function exitoCatalogoCampoDetalleTrama() {
        this.ejecutar = function (msg) {
            mostrarMensaje("Nuevo registro agregado", "EXITO");
            var fila = msg.mensaje;
            $("#codigoCatalogoDetalleTrama").val("");
            $("#nombreCatalogoDetalleTrama").val("");
            $("#codigoCatalagoDetalle").append(fila);
        };
    }

    $("#posicionFinalCampoDetalleTrama").change(function(){
	    longitud = ($("#posicionFinalCampoDetalleTrama").val() - $("#posicionInicialCampoDetalleTrama").val()) + 1;  	
    	$("#longitudSegmentoCampoDetalleTrama").val(longitud);
    });

 	function soloNumeros(){			 
 		if ((event.keyCode < 48) || (event.keyCode > 57))
 			event.returnValue = false;	
 	}

	
</script>	