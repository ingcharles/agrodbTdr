<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();

$qTrama = $cb -> abrirTramaXIdTrama($conexion, $idTrama = ($_POST['id']== "") ? $_POST['idTrama'] : $_POST['id']);
$trama = pg_fetch_assoc($qTrama);

$qCabeceraTrama = $cb -> abrirCabeceraTramaXIdTrama($conexion, $idTrama);
$cabeceraTrama = pg_fetch_assoc($qCabeceraTrama);

$qCamposCabecera = $cb -> obtenerCamposCabeceraXIdCabeceraTrama($conexion, $cabeceraTrama['id_cabecera_trama']);

$qDetalleTrama = $cb -> abrirDetalleTramaXIdTrama($conexion, $idTrama);
$detalleTrama = pg_fetch_assoc($qDetalleTrama);

$qCamposDetalle = $cb -> obtenerCamposDetalleXIdDetalleTrama($conexion, $detalleTrama['id_detalle_trama']);

$numeroPestania = $_POST['numeroPestania'];

?>

	<div id="estado"></div>
	
	<div class="pestania">	
	<header>
		<h1>Modificar Trama</h1>
	</header>
		<form id="abrirRegistroTrama" data-rutaAplicacion="conciliacionBancaria" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
			<input type="hidden" id="idTrama" name="idTrama" value="<?php echo $trama['id_trama'];?>" />
			<input type="hidden" id="opcion" name="opcion" value="registroTrama" />
			<fieldset id="informacionTrama">
				<legend>Información de Trama</legend>
				<div data-linea="1">
					<label>Nombre trama:</label>
					<input type="text" id="nombreTrama" name="nombreTrama" value="<?php echo $trama['nombre_trama'];?>" disabled="disabled" />
				</div>
				<div data-linea="2">
					<label>Separador: </label>
					<select id="separadorTrama" name="separadorTrama" disabled="disabled">
						<option value="0">Seleccione...</option>
						<option value=" ">Espacio en blanco</option>
						<option value="">Sin separador</option>
						<option value="|">| (Barra)</option>
						<option value=",">, (Coma)</option>
						<option value="-">- (Guión)</option>
					</select>
				</div>
			</fieldset>
			
			<fieldset id="documentosEntradaSalida">
				<legend>Documentos Entrada / Salida</legend>
				<div data-linea="3">
					<label>Formato de entrada: </label>
					<select id="formatoEntradaTrama" name="formatoEntradaTrama" disabled="disabled">
						<option value="">Seleccione...</option>
						<option value="xls">.xls</option>
						<option value="csv">.csv</option>
						<option value="xml">.xml</option>
						<option value="txt">.txt</option>
					</select>
				</div>
				<div data-linea="3">
					<label>Formato de salida: </label>
					<select id="formatoSalidaTrama" name="formatoSalidaTrama" disabled="disabled">
						<option value="">Seleccione...</option>
						<option value="txt">.txt</option>
						<option value="xls">.xls</option>
					</select>
				</div>
			</fieldset>
			<div>
				<button id="modificarTrama" type="button" class="editar">Modificar</button>
				<button id="actualizarTrama" type="submit" class="guardar" disabled="disabled" disabled="disabled">Actualizar</button>
			</div>
		</form>
	</div>
	
	<div class="pestania">
	<header>
		<h1>Cabecera Trama</h1>
	</header>	
		<form id="abrirCabeceraTrama" data-rutaAplicacion="conciliacionBancaria" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
			<input type="hidden" id="idCabeceraTrama" name="idCabeceraTrama" value="<?php echo $cabeceraTrama['id_cabecera_trama'];?>" />
			<input type="hidden" id="opcion" name="opcion" value="cabeceraRegistroTrama" />
			<fieldset id="cabeceraTrama">
					<legend>Cabecera de trama</legend>
					<div data-linea="4">
						<label>Código segmento:</label>
						<input type="text" id="codigoSegmentoCabeceraTrama" name="codigoSegmentoCabeceraTrama" value="<?php echo $cabeceraTrama['codigo_segmento_cabecera_trama']; ?>" disabled="disabled" />
					</div>
					<div data-linea="4">
						<label>Tamaño segmento:</label>
						<input type="text" id="tamanioSegmentoCabeceraTrama" name="tamanioSegmentoCabeceraTrama" value="<?php echo $cabeceraTrama['tamanio_segmento_cabecera_trama']; ?>" disabled="disabled" />
					</div>
					<div>
						<button id="modificarCabeceraTrama" type="button" class="editar">Modificar</button>
						<button id="actualizarCabeceraTrama" type="submit" class="guardar" disabled="disabled" disabled="disabled" >Actualizar</button><!-- onClick="verificarRegistro();" -->
					</div>
			</fieldset>
			</form>	
			<form id="anadirCampoCabeceraTrama" data-rutaAplicacion="conciliacionBancaria" data-opcion="guardarCamposTrama" >
				<input type="hidden" id="opcion" name="opcion" value="campoCabeceraTrama" />
				<input type="hidden" id="idCabeceraTrama" name="idCabeceraTrama" value="<?php echo $cabeceraTrama['id_cabecera_trama'];?>" />
				<input type="hidden" id="idTrama" name="idTrama" value="<?php echo $trama['id_trama'];?>" />				
				<fieldset id="anadirCampoCabeceraTramas">
					<legend>Añadir campo de cabecera</legend>
					<div data-linea="5">
						<label>Nombre campo:</label>
						<input type="text" id="nombreCampoCabeceraTrama" name="nombreCampoCabeceraTrama"/>
					</div>
					<div data-linea="6">
						<label>Posición inicial:</label>
						<input type="text" id="posicionInicialCampoCabeceraTrama" name="posicionInicialCampoCabeceraTrama" onkeypress="soloNumeros()" onkeypress="calcularLongitud()"/>
					</div>
					<div data-linea="6">
						<label>Posición final:</label>
						<input type="text" id="posicionFinalCampoCabeceraTrama" name="posicionFinalCampoCabeceraTrama" onkeypress="soloNumeros()" onkeypress="calcularLongitud()"/>
					</div>
					<div data-linea="7">
						<label>Longitud segmento:</label>
						<input type="text" id="longitudSegmentoCampoCabeceraTrama" name="longitudSegmentoCampoCabeceraTrama" readonly="readonly"/>
					</div>
					<div data-linea="7">
						<label>Tipo campo:</label>
						<select id="tipoCampoCabeceraTrama" name="tipoCampoCabeceraTrama" >
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
				<fieldset id="campoCabeceraTrama">
					<legend>Campos de cabecera</legend>
					<table id="codigoCabeceraTrama">
					<thead><tr><th>Nombre</th><th>Long.</th><th>P. Inicial</th><th>P. Final</th><th>TipoCampo</th><th colspan="4">Opciones</th></tr></thead>
							<?php 
								while ($camposCabecera = pg_fetch_assoc($qCamposCabecera)){
									echo $cb -> imprimirLineaCampoCabecera($camposCabecera['id_campo_cabecera'], $camposCabecera['nombre_campo_cabecera'], $camposCabecera['longitud_segmento_campo_cabecera'], $camposCabecera['posicion_inicial_campo_cabecera'], $camposCabecera['posicion_final_campo_cabecera'], $camposCabecera['tipo_campo_cabecera'], $trama['id_trama']);
								}
							?>
					</table>
				</fieldset>
	</div>
	
	<div class="pestania">
	<header>
		<h1>Detalle Trama</h1>
	</header>
		<form id="abrirDetalleTrama" data-rutaAplicacion="conciliacionBancaria" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
			<input type="hidden" id="idDetalleTrama" name="idDetalleTrama" value="<?php echo $detalleTrama['id_detalle_trama'];?>" />
			<input type="hidden" id="opcion" name="opcion" value="detalleRegistroTrama" />
			<fieldset id="detalleTrama">
					<legend>Detalle de trama</legend>
					<div data-linea="4">
						<label>Código segmento:</label>
						<input type="text" id="codigoSegmentoDetalleTrama" name="codigoSegmentoDetalleTrama" value="<?php echo $detalleTrama['codigo_segmento_detalle_trama'];?>" disabled="disabled" />
					</div>
					<div data-linea="4">
						<label>Tamaño segmento:</label>
						<input type="text" id="tamanioSegmentoDetalleTrama" name="tamanioSegmentoDetalleTrama" value="<?php echo $detalleTrama['tamanio_segmento_detalle_trama'];?>" disabled="disabled" />
					</div>
					<div>
						<button id="modificarDetalleTrama" type="button" class="editar">Modificar</button>
						<button id="actualizarDetalleTrama" type="submit" class="guardar" disabled="disabled" disabled="disabled">Actualizar</button>
					</div>
			</fieldset>
		</form>
		<form id="anadirCampoDetalleTrama" data-rutaAplicacion="conciliacionBancaria" data-opcion="guardarCamposTrama" >
			<input type="hidden" id="opcion" name="opcion" value="campoDetalleTrama" />
			<input type="hidden" id="idDetalleTrama" name="idDetalleTrama" value="<?php echo $detalleTrama['id_detalle_trama'];?>" />
			<input type="hidden" id="idCabeceraTrama" name="idCabeceraTrama" value="<?php echo $cabeceraTrama['id_cabecera_trama'];?>" />				
			<fieldset id="anadirCampoDetalleTramas">
				<legend>Añadir campo de detalle</legend>
				<div data-linea="5">
					<label>Nombre campo:</label>
					<input type="text" id="nombreCampoDetalleTrama" name="nombreCampoDetalleTrama"/>
				</div>
				<div data-linea="6">
					<label>Posición inicial:</label>
					<input type="text" id="posicionInicialCampoDetalleTrama" name="posicionInicialCampoDetalleTrama" onkeypress="soloNumeros()"/>
				</div>
				<div data-linea="6">
					<label>Posición final:</label>
					<input type="text" id="posicionFinalCampoDetalleTrama" name="posicionFinalCampoDetalleTrama" onkeypress="soloNumeros()"/>
				</div>
				<div data-linea="7">
					<label>Longitud segmento:</label>
					<input type="text" id="longitudSegmentoCampoDetalleTrama" name="longitudSegmentoCampoDetalleTrama" readonly="readonly"/>
				</div>
				<div data-linea="7">
					<label>Tipo campo:</label>
					<select id="tipoCampoDetalleTrama" name="tipoCampoDetalleTrama" >
						<option value="">Seleccione...</option>
						<option value="obligatorio">Obligatorio</option>
						<option value="opcional">Opcional</option>
					</select>
				</div>
				<div data-linea="8">
					<label>Campo forma pago:</label>
						<select id="campoFormaPagoCampoCabeceraTrama" name="campoFormaPagoCampoCabeceraTrama" >
							<option value="">Seleccione...</option>
							<option value="banco">Banco</option>
							<option value="transaccion">Transacción</option>
							<option value="valorDeposito">Valor depositado</option>
							<option value="numeroCuenta">Número de cuenta</option>
						</select>
					</div>
				<div>
					<button type="submit" class="mas">Agregar campo</button>
				</div>
			</fieldset>
		</form>
			<fieldset id="campoDetalleTrama">
				<legend>Campos de detalle</legend>
				<table id="codigoDetalleTrama">
					<thead><tr><th>Nombre</th><th>Long.</th><th>P. Inicial</th><th>P. Final</th><th>TipoCampo</th><th colspan="4">Opciones</th></tr></thead>
						<?php 
							while ($camposDetalle = pg_fetch_assoc($qCamposDetalle)){
								echo $cb -> imprimirLineaCampoDetalle($camposDetalle['id_campo_detalle'], $camposDetalle['nombre_campo_detalle'], $camposDetalle['longitud_segmento_campo_detalle'], $camposDetalle['posicion_inicial_campo_detalle'], $camposDetalle['posicion_final_campo_detalle'], $camposDetalle['tipo_campo_detalle']/*, $cabeceraTrama['id_cabecera_trama']*/, $trama['id_trama']);
							}
						?>
					</table>
			</fieldset>
	</div>
	
<script type="text/javascript">			

	var numeroPestania = <?php echo json_encode($numeroPestania);?>;
					
    $(document).ready(function(){	 
    	distribuirLineas();	  
    	actualizarBotonesOrdenamiento();  	
    	construirAnimacion($(".pestania"),numeroPestania);
    	cargarValorDefecto("separadorTrama","<?php echo $trama["separador_trama"];?>");
    	cargarValorDefecto("formatoEntradaTrama","<?php echo $trama["formato_entrada_trama"];?>");
    	cargarValorDefecto("formatoSalidaTrama","<?php echo $trama["formato_salida_trama"];?>");
    	$("#tamanioSegmentoCabeceraTrama").numeric();
    	$("#tamanioSegmentoDetalleTrama").numeric();
    	//acciones("#anadirCampoCabeceraTrama","#codigoCabeceraTrama");
    	acciones('#anadirCampoCabeceraTrama', '#codigoCabeceraTrama', null, null, new exitoCampoCabeceraTrama(), null, null, new verificarInputsCampoCabeceraTrama());
    	//acciones("#anadirCampoDetalleTrama","#codigoDetalleTrama");
    	acciones('#anadirCampoDetalleTrama', '#codigoDetalleTrama', null, null, new exitoCampoDetalleTrama(), null, null, new verificarInputsCampoDetalleTrama());
    	actualizarBotonesOrdenamiento("#codigoCabeceraTrama"); 
    	actualizarBotonesOrdenamiento("#codigoDetalleTrama"); 
    });
    
    $("#modificarTrama").click(function(){
    	$("#nombreTrama").removeAttr("disabled");
		$("#separadorTrama").removeAttr("disabled");
		$("#formatoEntradaTrama").removeAttr("disabled");
		$("#formatoSalidaTrama").removeAttr("disabled");
		$("#actualizarTrama").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

    $("#abrirRegistroTrama").submit(function(){

  	 	event.preventDefault();
  	    $(".alertaCombo").removeClass("alertaCombo");
  	  	var error = false;

    	if($("#nombreTrama").val()==""){
			error = true;
			$("#nombreTrama").addClass("alertaCombo");
		}

    	if($("#separadorTrama").val()==""){
			error = true;
			$("#separadorTrama").addClass("alertaCombo");
		}

    	if($("#formatoEntradaTrama").val()==""){
			error = true;
			$("#formatoEntradaTrama").addClass("alertaCombo");
		}

    	if($("#formatoSalidaTrama").val()==""){
			error = true;
			$("#formatoSalidaTrama").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				$('#abrirRegistroTrama').attr('data-opcion','modificarRegistroTrama');
				ejecutarJson($(this));                             
		}
    });

    $("#modificarCabeceraTrama").click(function(){
    	$("#codigoSegmentoCabeceraTrama").removeAttr("disabled");
		$("#tamanioSegmentoCabeceraTrama").removeAttr("disabled");
		$("#actualizarCabeceraTrama").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

    $("#abrirCabeceraTrama").submit(function(){

  	 	event.preventDefault();
  	    $(".alertaCombo").removeClass("alertaCombo");
  	  	var error = false;

    	if($("#codigoSegmentoCabeceraTrama").val()==""){
			error = true;
			$("#codigoSegmentoCabeceraTrama").addClass("alertaCombo");
		}

    	if($("#tamanioSegmentoCabeceraTrama").val()==""){
			error = true;
			$("#tamanioSegmentoCabeceraTrama").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				$('#abrirCabeceraTrama').attr('data-opcion','modificarRegistroTrama');
				ejecutarJson($(this));                             
		}
    });

    $("#modificarDetalleTrama").click(function(){
    	$("#codigoSegmentoDetalleTrama").removeAttr("disabled");
		$("#tamanioSegmentoDetalleTrama").removeAttr("disabled");
		$("#actualizarDetalleTrama").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

    $("#abrirDetalleTrama").submit(function(){

  	 	event.preventDefault();
  	    $(".alertaCombo").removeClass("alertaCombo");
  	  	var error = false;

    	if($("#codigoSegmentoDetalleTrama").val()==""){
			error = true;
			$("#codigoSegmentoDetalleTrama").addClass("alertaCombo");
		}

    	if($("#tamanioSegmentoDetalleTrama").val()==""){
			error = true;
			$("#tamanioSegmentoDetalleTrama").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				$('#abrirDetalleTrama').attr('data-opcion','modificarRegistroTrama');
				ejecutarJson($(this));                             
		}
    });


	function verificarInputsCampoCabeceraTrama() {

		this.ejecutar = function () {
	        var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
	
	        if ($("#nombreCampoCabeceraTrama").val() == "") {
	            $("#nombreCampoCabeceraTrama").addClass("alertaCombo");
	            error = true;
	        }
	        if ($("#posicionInicialCampoCabeceraTrama").val() == "") {
	            $("#posicionInicialCampoCabeceraTrama").addClass("alertaCombo");
	            error = true;
	        }
	        if ($("#posicionFinalCampoCabeceraTrama").val() == "") {
	            $("#posicionFinalCampoCabeceraTrama").addClass("alertaCombo");
	            error = true;
	        }
	        if ($("#longitudSegmentoCampoCabeceraTrama").val() == "") {
	            $("#longitudSegmentoCampoCabeceraTrama").addClass("alertaCombo");
	            error = true;
	        }
	        if ($("#tipoCampoCabeceraTrama").val() == "") {
	            $("#tipoCampoCabeceraTrama").addClass("alertaCombo");
	            error = true;
	        }
	        if($("#posicionInicialCampoCabeceraTrama").val() >= $("#posicionFinalCampoCabeceraTrama").val()){
		        $("#posicionFinalCampoCabeceraTrama").addClass("alertaCombo");
	    		error = true;
	        }
	        
	        return !error;

	    };

		this.mensajeError = function () {			
			mostrarMensaje("Revise los datos del formulario", "FALLO");			
		}
    }

    function exitoCampoCabeceraTrama() {
        this.ejecutar = function (msg) {
            mostrarMensaje("Nuevo registro agregado", "EXITO");
            var fila = msg.mensaje;
            $("#nombreCampoCabeceraTrama").val("");
            $("#posicionInicialCampoCabeceraTrama").val("");
            $("#posicionFinalCampoCabeceraTrama").val("");
            $("#longitudSegmentoCampoCabeceraTrama").val("");
            $("#tipoCampoCabeceraTrama").val("");
            $("#codigoCabeceraTrama").append(fila);
        };
    }

    function verificarInputsCampoDetalleTrama() {

		this.ejecutar = function () {
	        var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
	
	        if ($("#nombreCampoDetalleTrama").val() == "") {
	            $("#nombreCampoDetalleTrama").addClass("alertaCombo");
	            error = true;
	        }
	        if ($("#posicionInicialCampoDetalleTrama").val() == "") {
	            $("#posicionInicialCampoDetalleTrama").addClass("alertaCombo");
	            error = true;
	        }
	        if ($("#posicionFinalCampoDetalleTrama").val() == "") {
	            $("#posicionFinalCampoDetalleTrama").addClass("alertaCombo");
	            error = true;
	        }
	        if ($("#longitudSegmentoCampoDetalleTrama").val() == "") {
	            $("#longitudSegmentoCampoDetalleTrama").addClass("alertaCombo");
	            error = true;
	        }
	        if ($("#tipoCampoDetalleTrama").val() == "") {
	            $("#tipoCampoDetalleTrama").addClass("alertaCombo");
	            error = true;
	        }
	        if($("#posicionInicialCampoDetalleTrama").val() >= $("#posicionFinalCampoDetalleTrama").val()){	
	   			$("#posicionFinalCampoDetalleTrama").addClass("alertaCombo");
	    		error = true;
	        }
	        
	        return !error;

	    };

		this.mensajeError = function () {
			mostrarMensaje("Revise los datos del formulario", "FALLO");
		}
    }

    function exitoCampoDetalleTrama() {
        this.ejecutar = function (msg) {
            mostrarMensaje("Nuevo registro agregado", "EXITO");
            var fila = msg.mensaje;
            $("#nombreCampoDetalleTrama").val("");
            $("#posicionInicialCampoDetalleTrama").val("");
            $("#posicionFinalCampoDetalleTrama").val("");
            $("#longitudSegmentoCampoDetalleTrama").val("");
            $("#tipoCampoDetalleTrama").val("");
            $("#codigoDetalleTrama").append(fila);
        };
    }

    $("#posicionFinalCampoCabeceraTrama").change(function(){
	    longitud = ($("#posicionFinalCampoCabeceraTrama").val() - $("#posicionInicialCampoCabeceraTrama").val()) + 1;  	
    	$("#longitudSegmentoCampoCabeceraTrama").val(longitud);
    });

    $("#posicionFinalCampoDetalleTrama").change(function(){
		longitud = ($("#posicionFinalCampoDetalleTrama").val() - $("#posicionInicialCampoDetalleTrama").val()) + 1;  	
		$("#longitudSegmentoCampoDetalleTrama").val(longitud);
	});

 	function soloNumeros(){			 
 		if ((event.keyCode < 48) || (event.keyCode > 57))
 			event.returnValue = false;	
 	}
 	    
</script>