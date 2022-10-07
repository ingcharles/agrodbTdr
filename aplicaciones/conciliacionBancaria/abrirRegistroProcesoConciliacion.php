<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();


$qRegistroProcesoConciliacion = $cb -> abrirRegistroProcesoConciliacionXIdRegistroProcesoConciliacion($conexion, $idRegistroProcesoConciliacion = ($_POST['id']== "") ? $_POST['idProcesoConciliacion'] : $_POST['id']);
$registroProcesoConciliacion = pg_fetch_assoc($qRegistroProcesoConciliacion);

$qDocumentosRegistroProcesoConciliacion = $cb -> obtenerDocumentosProcesoConciliacionXIdRegistroProcesoConciliacion($conexion, $idRegistroProcesoConciliacion);

$qBancosProcesoConciliacion = $cb -> obtenerBancoProcesoConciliacionXIdRegistroProcesoConciliacion($conexion, $idRegistroProcesoConciliacion);

$qCamposComparar = $cb -> obtenerNombresCamposDocumentosCompararXIdRegistroProcesoConciliacion($conexion, $idRegistroProcesoConciliacion);

?>

<div id="estado"></div>

<div class="pestania">
<header>
	<h1>Nuevo Registro Proceso Conciliación</h1>
</header>

	<form id="abrirRegistroProcesoConciliacion" data-rutaAplicacion="conciliacionBancaria" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idRegistroProcesoConciliacion" name="idRegistroProcesoConciliacion" value="<?php echo $registroProcesoConciliacion['id_registro_proceso_conciliacion'];?>" />
		<fieldset id="informacionProceso">
			<legend>Información de Proceso</legend>
			<div data-linea="1">
				<label>Nombre de proceso:</label>
				<input type="text" id="nombreRegistroProcesoConciliacion" name="nombreRegistroProcesoConciliacion" value="<?php echo $registroProcesoConciliacion['nombre_registro_proceso_conciliacion'];?>" disabled="disabled" />
			</div>
			<hr>
			<div data-linea="2">
				<label>Facturas GUIA:</label>
				<select id="facturaRegistroProcesoConciliacion" name="facturaRegistroProcesoConciliacion" disabled="disabled" >
					<option value="">Seleccione...</option>
					<option value="interno">Servicion internos</option>
					<option value="comercioExterior">Comercio exterior</option>
				</select>
			</div>
			<div data-linea="3">
				<label>Tipo de revisión: </label>
				<select id="tipoRevisionRegistroProcesoConciliacion" name="tipoRevisionRegistroProcesoConciliacion" disabled="disabled" >
					<option value="">Seleccione...</option>
					<option value="comparacionGUIA">Comparación de información de campos con GUIA</option>
				</select>
			</div>			
		</fieldset>
		<div>
			<button id="modificar" type="button" class="editar">Modificar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled" disabled="disabled">Actualizar</button>
		</div>
		
	</form>	
</div>


<div class="pestania">
	<header>
		<h1>Documentos a Utilizar</h1>
	</header>
	<form id="anadirDocumentosUtilizarProcesoConciliacion" data-rutaAplicacion="conciliacionBancaria" >	
	<input type="hidden" id="opcionDocumento" name="opcionDocumento" />
	<input type="hidden" id="idRegistroProcesoConciliacion" name="idRegistroProcesoConciliacion" value="<?php echo $registroProcesoConciliacion['id_registro_proceso_conciliacion'];?>" />
	<input type="hidden" id="nombreDocumentoEntradaUtilizadoProcesoConciliacion" name="nombreDocumentoEntradaUtilizadoProcesoConciliacion" />
		<fieldset id="documentosUtilizar">
			<legend>Documentos a Utilizar</legend>
			<div data-linea="4">
				<label>Tipo de documento: </label>
				<select id="tipoDocumentoUtilizarProcesoConciliacion" name="tipoDocumentoUtilizarProcesoConciliacion">
					<option value="">Seleccione...</option>
					<option value="trama">Tramas</option>
					<option value="documento">Documento</option>
				</select>
			</div>
			<div data-linea="4" id="cargarDocumentoEntradaUtilizarProcesoConciliacion">
				<label>Documento de entrada: </label>
				<select id="documentoEntradaUtilizarProcesoConciliacion" name="documentoEntradaUtilizarProcesoConciliacion">
					<option value="">Seleccione...</option>
				</select>
			</div>
			<div>
				<button type="submit" id="anadirDocumento" class="mas">Agregar documento</button>
			</div>
		</fieldset>
	</form>	
		
	<fieldset id="anadirDocumentosUtilizar">
		<legend>Lista de Documentos a Utilizar</legend>
		<table id="codigoDocumentosUtilizar" style="width: 100%;">
			<thead><tr><th>Tipo de documento</th><th>Documento de entrada</th><th>Opcion</th></tr></thead>
			<tbody>
					<?php 
						while ($documentosRegistroProcesoConciliacion = pg_fetch_assoc($qDocumentosRegistroProcesoConciliacion)){
							echo $cb -> imprimirListaDocumentosUtilizarProcesoConciliacion($documentosRegistroProcesoConciliacion['id_documento_proceso_conciliacion'], $documentosRegistroProcesoConciliacion['tipo_documento_proceso_conciliacion'], $documentosRegistroProcesoConciliacion['id_documento_entrada_proceso_conciliacion'], $documentosRegistroProcesoConciliacion['nombre_documento_entrada_proceso_conciliacion']);							
						}
					?>
			</tbody>
		</table>
	</fieldset>
	
	<header>
		<h1>Entidades Bancarias</h1>
	</header>	
	<form id="anadirBancosUtilizarProcesoConciliacion" data-rutaAplicacion="conciliacionBancaria" >	
	<input type="hidden" id="idRegistroProcesoConciliacion" name="idRegistroProcesoConciliacion" value="<?php echo $registroProcesoConciliacion['id_registro_proceso_conciliacion'];?>" />
	<input type="hidden" id="nombreEntidadBancariaUtilizarProcesoConciliacion" name="nombreEntidadBancariaUtilizarProcesoConciliacion" />	
		<fieldset id="entidadesBancarias">
			<legend>Entidades Bancarias</legend>
			<div data-linea="5">
				<label>Nombre banco: </label>
				<select id="entidadBancariaUtilizarProcesoConciliacion" name="entidadBancariaUtilizarProcesoConciliacion">
					<option value="">Seleccione...</option>
					<?php 
						$qEntidadBancaria = $cc -> listarEntidadesBancariasAgrocalidad($conexion);
						
						while ($entidadBancaria = pg_fetch_assoc($qEntidadBancaria)){
					    	echo '<option value="'.$entidadBancaria['id_banco'].'" data-nombrebanco="'.$entidadBancaria['nombre'].'" >'. $entidadBancaria['nombre'] .'</option>';
					    }
					?>
				</select>
			</div>
			<div>
				<button type="submit" class="mas" id="anadirBanco">Agregar entidad</button>
			</div>
		</fieldset>
	</form>
			
	<fieldset id="anadirentidadesBancarias">
		<legend>Lista de Entidades Bancarias</legend>
		<table id="codigoBancosUtilizar">
			<thead><tr><th>Entidad Bancaria</th><th>Opcion</th></tr></thead>
					<?php 						
						while ($bancosProcesoConciliacion = pg_fetch_assoc($qBancosProcesoConciliacion)){
							echo $cb -> imprimirListaBancosProcesoConciliacion($bancosProcesoConciliacion['id_banco_proceso_conciliacion'], $bancosProcesoConciliacion['nombre']);							
						}
					?>
		</table>	
	</fieldset>
</div>

<!-- COLUMNAS A COMPARAR -->

<div class="pestania">
	<header>
		<h1>Columnas de Comparación</h1>
	</header>
	<form id="anadirCamposCompararProcesoConciliacion" data-rutaAplicacion="conciliacionBancaria" >	
	<input type="hidden" id="opcionCampos" name="opcionCampos" />
	<input type="hidden" id="idDocumento" name="idDocumento" />
	<input type="hidden" id="nombreDocumento" name="nombreDocumento" />
	<input type="hidden" id="tipoColumna" name="tipoColumna" />
	<input type="hidden" id="nombreColumna" name="nombreColumna" />
	
	<fieldset id="camposComparar">
			<legend>Añadir Columnas a Comparar</legend>
			<div data-linea="1">
				<label>Sistema GUIA: </label>
				<select id="sistemaGuiaCamposComparar" name="sistemaGuiaCamposComparar">
					<option value="">Seleccione...</option>
				<option value="sistemaGUIA">Sistema GUIA</option>
				</select>
			</div>
			<div data-linea="1">
				<label>Docs/Reportes: </label>
				<select id="documentoReporteCamposComparar" name="documentoReporteCamposComparar">
					<option value="">Seleccione...</option>
				</select>
			</div>
			<div data-linea="2">
				<label>Datos/Columna: </label>
				<select id="datosColumnaGuiaCamposComparar" name="datosColumnaGuiaCamposComparar">
					<option value="">Seleccione...</option>
					<option value="identificador_operador">identificador_operador</option>
					<option value="numero_solicitud">numero_solicitud</option>
					<option value="fecha_orden_pago">fecha_orden_pago</option>
					<option value="total_pagar">total_pagar</option>
					<option value="observacion">observacion</option>
					<option value="estado">estado integer</option>
					<option value="localizacion">localizacion</option>
					<option value="institucion_bancaria">institucion_bancaria</option>
					<option value="numero_papeleta">numero_papeleta</option>
					<option value="valor_deposito">valor_deposito</option>
					<option value="orden_pago">orden_pago</option>
					<option value="factura">factura</option>
					<option value="numero_factura">numero_factura</option>
					<option value="fecha_facturacion">fecha_facturacion</option>
					<option value="fecha_autorizacion">fecha_autorizacion</option>
					<option value="clave_acceso">clave_acceso</option>
					<option value="tipo_solicitud">tipo_solicitud</option>
					<option value="nombre_provincia">nombre_provincia</option>
					<option value="numero_orden_vue">numero_orden_vue</option>					
				</select>
			</div>
			<div data-linea="2" id="cargarDatosColumnaDocumentosCamposComparar">
				<label>Datos/Columna: </label>
				<select id="datosColumnaDocumentosCamposComparar" name="datosColumnaDocumentosCamposComparar">
					<option value="">Seleccione...</option>
				</select>
			</div>
			<div data-linea="3">
				<label>Actividad a ejecutar: </label>
				<select id="actividadEjecutarCamposComparar" name="actividadEjecutarCamposComparar">
					<option value="">Seleccione...</option>
					<option value="compararTexto">Comparar texto</option>
					<option value="compararValor">Comparar valor</option>
				</select>
			</div>
			
			<div>
				<button type="submit" class="mas" id="anadirCamposComparar">Agregar campo</button>
			</div>
		</fieldset>
		</form>
		
		<fieldset id="camposCompararProcesoConciliacion">
			<legend>Columnas a Comparar</legend>
			<table id="codigocamposCompararProcesoConciliacion">
				<thead><tr><th>Sistema GUIA</th><th>Datos Columna</th><th>Docs reportes</th><th>Datos columna</th><th>Actividad a ejecutarse</th><th colspan="4">Opciones</th></tr></thead>
						<?php 

							while ($camposComparar = pg_fetch_assoc($qCamposComparar)){///TODO:VERIFICAR ESTO
								echo $cb -> imprimirLineaCampoDocumentoCompararProcesoConciliacion($camposComparar['id_campo_comparar_proceso_conciliacion'], $camposComparar['tipo_guia_comparar_proceso_conciliacion'], $camposComparar['campo_guia_comparar_proceso_conciliacion'], $camposComparar['nombre_documento'], $camposComparar['nombre_campo'], $camposComparar['tipo_comparacion_proceso_conciliacion']);
							}
						?>
				</table>
		</fieldset>	

</div>

	
<script type="text/javascript">			

    $(document).ready(function(){	
    	distribuirLineas();	  
    	actualizarBotonesOrdenamiento();  	
    	construirAnimacion($(".pestania"));	
    	cargarValorDefecto("facturaRegistroProcesoConciliacion","<?php echo $registroProcesoConciliacion["factura_registro_proceso_conciliacion"];?>");
    	cargarValorDefecto("tipoRevisionRegistroProcesoConciliacion","<?php echo $registroProcesoConciliacion["tipo_revision_registro_proceso_conciliacion"];?>");
    	//acciones("#anadirDocumentosUtilizarProcesoConciliacion","#codigoDocumentosUtilizar");
    	acciones('#anadirDocumentosUtilizarProcesoConciliacion', '#codigoDocumentosUtilizar', null, null, new exitoDocumentoUtilizar(), null, null, new verificarInputsDocumentosUtilizar());
    	//acciones("#anadirBancosUtilizarProcesoConciliacion","#codigoBancosUtilizar");
    	acciones('#anadirBancosUtilizarProcesoConciliacion', '#codigoBancosUtilizar', null, null, new exitoBancosUtilizar(), null, null, new verificarInputsBancosUtilizar());    	
    	//acciones("#anadirCamposCompararProcesoConciliacion","#codigocamposCompararProcesoConciliacion");  
    	acciones('#anadirCamposCompararProcesoConciliacion', '#codigocamposCompararProcesoConciliacion', null, null, new exitoColumnasComparar(), null, null, new verificarColumnasComparar());  	
    	crearCombo();
    	 
    });


    $(".bant").click(function(){
    	$("#estado").html("");
    });
    
    $(".bsig").click(function(){
    	$("#estado").html("");
    });
    
    $("#abrirColumnasCompararProcesoConciliacion").submit(function(){
    	$("#abrirColumnasCompararProcesoConciliacion").attr('data-opcion', 'hola');
    	ejecutarJson($(this));      
    });
    
    $("#abrirRegistroProcesoConciliacion").submit(function(){

  	 	event.preventDefault();
  	    $(".alertaCombo").removeClass("alertaCombo");
  	  	var error = false;

    	if($("#nombreRegistroProcesoConciliacion").val()==""){
			error = true;
			$("#nombreRegistroProcesoConciliacion").addClass("alertaCombo");
		}

    	if($("#facturaRegistroProcesoConciliacion").val()==""){
			error = true;
			$("#facturaRegistroProcesoConciliacion").addClass("alertaCombo");
		}

    	if($("#tipoRevisionRegistroProcesoConciliacion").val()==""){
			error = true;
			$("#tipoRevisionRegistroProcesoConciliacion").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$('#abrirRegistroProcesoConciliacion').attr('data-opcion','modificarRegistroProcesoConciliacion');
			ejecutarJson($(this));                             
		}
    });        

    $("#modificar").click(function(){
    	$("#nombreRegistroProcesoConciliacion").removeAttr("disabled");
		$("#facturaRegistroProcesoConciliacion").removeAttr("disabled");
		$("#tipoRevisionRegistroProcesoConciliacion").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

    $("#tipoDocumentoUtilizarProcesoConciliacion").change(function (event){
    	if($.trim($("#tipoDocumentoUtilizarProcesoConciliacion").val())!=""){
    		$("#anadirDocumentosUtilizarProcesoConciliacion").attr('data-destino', 'cargarDocumentoEntradaUtilizarProcesoConciliacion');
    	    $("#anadirDocumentosUtilizarProcesoConciliacion").attr('data-opcion', 'combosProcesoConciliacion');
    		$("#opcionDocumento").val($("#tipoDocumentoUtilizarProcesoConciliacion option:selected").val());
    		abrir($("#anadirDocumentosUtilizarProcesoConciliacion"), event, false);
    	}
    });

    /*$("#documentoEntradaUtilizarProcesoConciliacion").change(function (event){
    	$("#nombreDocumentoEntradaUtilizadoProcesoConciliacion").val($("#documentoEntradaUtilizarProcesoConciliacion option:selected").text());  
	});*/
    
    $("#anadirDocumento").click(function(){
    	$("#anadirDocumentosUtilizarProcesoConciliacion").attr('data-opcion', 'guardarDocumentosUtilizarProcesoConciliacion');
    	crearCombo();
    });

    $("#entidadBancariaUtilizarProcesoConciliacion").change(function (event){
		$("#nombreEntidadBancariaUtilizarProcesoConciliacion").val($("#entidadBancariaUtilizarProcesoConciliacion option:selected").attr('data-nombrebanco'));
    });
    
    $("#anadirBanco").click(function(){
    	$("#anadirBancosUtilizarProcesoConciliacion").attr('data-opcion', 'guardarBancosUtilizarProcesoConciliacion');
    });

    //CAMPOS A COMPARAR
    
	$("#documentoReporteCamposComparar").change(function (event){    	
		if($.trim($("#documentoReporteCamposComparar").val())!=""){
			$("#anadirCamposCompararProcesoConciliacion").attr('data-destino', 'cargarDatosColumnaDocumentosCamposComparar');
    	    $("#anadirCamposCompararProcesoConciliacion").attr('data-opcion', 'combosProcesoConciliacion');
    	    $("#opcionCampos").val($("#documentoReporteCamposComparar option:selected").text())/*attr('data-tipoDocumento'))*/;
    	    $("#idDocumento").val($("#documentoReporteCamposComparar option:selected").attr('data-tipoDocumento'));
    	    $("#nombreDocumento").val($("#documentoReporteCamposComparar option:selected").attr('data-nombreDocumento'));
    		abrir($("#anadirCamposCompararProcesoConciliacion"), event, false);
    	}
    });

    /*$("#datosColumnaDocumentosCamposComparar").change(function (event){
    	if($.trim($("#documentoReporteCamposComparar").val())!=""){
    		 $("#tipoColumna").val($("#datosColumnaDocumentosCamposComparar option:selected").attr('data-tipoColumna'));
			 $("#nombreColumna").val($("#datosColumnaDocumentosCamposComparar option:selected").attr('data-nombreColumna'));
        }
    });  */

    /*$("#datosColumnaDocumentosCamposComparar").change(function (event){
    	if($.trim($("#documentoReporteCamposComparar").val())!=""){
    		 
        }
    }); */          	

	function crearCombo(){
		var combo='';
		
		$('#codigoDocumentosUtilizar > tbody  > tr').each(function() {

			var idCombo = $(this).attr('id');
			idCombo = $.trim(idCombo.replace('R', ''));
			var idDocumento =  $(this).find('td').eq(1).find('input').val();   			
			var textoCombo = $(this).find('td').eq(0).html();
			var nombreDocumento = $(this).find('td').eq(1).text();
			combo+="<option value ='"+idCombo+"' data-tipoDocumento='"+idDocumento+"' data-nombreDocumento='"+nombreDocumento+"'>"+textoCombo+"</option>";		
        			
		});
		$("#documentoReporteCamposComparar").append(combo);
	}

 	$("#anadirCamposComparar").click(function(){        
    	$("#anadirCamposCompararProcesoConciliacion").attr('data-opcion', 'guardarCamposCompararProcesoConciliacion');
    });

 	function verificarInputsDocumentosUtilizar() {
		this.ejecutar = function () {
			var error = false;
			$(".alertaCombo").removeClass("alertaCombo");
			
			if ($("#tipoDocumentoUtilizarProcesoConciliacion").val() == "") {
				$("#tipoDocumentoUtilizarProcesoConciliacion").addClass("alertaCombo");
				error = true;
			}

			if ($("#documentoEntradaUtilizarProcesoConciliacion").val() == "") {
				$("#documentoEntradaUtilizarProcesoConciliacion").addClass("alertaCombo");
				error = true;
				}

			return !error;		
		};
	
		this.mensajeError = function () {
			mostrarMensaje("Llene todos los datos del formulario", "FALLO");
		}
	}
	
	function exitoDocumentoUtilizar() {
		this.ejecutar = function (msg) {
			mostrarMensaje("Nuevo registro agregado", "EXITO");
			var fila = msg.mensaje;
			$("#tipoDocumentoUtilizarProcesoConciliacion").val("");
			$("#documentoEntradaUtilizarProcesoConciliacion").val("");
			$("#codigoDocumentosUtilizar").append(fila);
		};
	}

	function verificarInputsBancosUtilizar() {
		this.ejecutar = function () {
			var error = false;
			$(".alertaCombo").removeClass("alertaCombo");
			
			if ($("#entidadBancariaUtilizarProcesoConciliacion").val() == "") {
				$("#entidadBancariaUtilizarProcesoConciliacion").addClass("alertaCombo");
				error = true;
			}

			return !error;		
		};
	
		this.mensajeError = function () {
			mostrarMensaje("Llene todos los datos del formulario", "FALLO");
		}
	}
	
	function exitoBancosUtilizar() {
		this.ejecutar = function (msg) {
			mostrarMensaje("Nuevo registro agregado", "EXITO");
			var fila = msg.mensaje;
			$("#entidadBancariaUtilizarProcesoConciliacion").val("");
			$("#codigoBancosUtilizar").append(fila);
		};
	}


	function verificarColumnasComparar() {
		this.ejecutar = function () {
			var error = false;
			$(".alertaCombo").removeClass("alertaCombo");
			
			if ($("#sistemaGuiaCamposComparar").val() == "") {
				$("#sistemaGuiaCamposComparar").addClass("alertaCombo");
				error = true;
			}

			if ($("#datosColumnaGuiaCamposComparar").val() == "") {
				$("#datosColumnaGuiaCamposComparar").addClass("alertaCombo");
				error = true;
			}

			if ($("#documentoReporteCamposComparar").val() == "") {
				$("#documentoReporteCamposComparar").addClass("alertaCombo");
				error = true;
			}

			if ($("#datosColumnaDocumentosCamposComparar").val() == "") {
				$("#datosColumnaDocumentosCamposComparar").addClass("alertaCombo");
				error = true;
			}
			if ($("#actividadEjecutarCamposComparar").val() == "") {
				$("#actividadEjecutarCamposComparar").addClass("alertaCombo");
				error = true;
			}

			return !error;		
		};
	
		this.mensajeError = function () {
			mostrarMensaje("Llene todos los datos del formulario", "FALLO");
		}
	}
	
	function exitoColumnasComparar() {
		this.ejecutar = function (msg) {
			mostrarMensaje("Nuevo registro agregado", "EXITO");
			var fila = msg.mensaje;
			$("#sistemaGuiaCamposComparar").val("");
			$("#datosColumnaGuiaCamposComparar").val("");
			$("#documentoReporteCamposComparar").val("");
			$("#datosColumnaDocumentosCamposComparar").val("");
			$("#actividadEjecutarCamposComparar").val(""); 
			$("#codigocamposCompararProcesoConciliacion").append(fila);
		};
	}

</script>
	
	