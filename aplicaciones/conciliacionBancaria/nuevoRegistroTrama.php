<?php
session_start();
require_once '../../clases/Conexion.php';

$conexion = new Conexion();

?>

<header>
	<h1>Nueva Trama</h1>
</header>

	<div id="estado"></div>
	
	<form id="nuevoRegistroTrama" data-rutaAplicacion="conciliacionBancaria" data-opcion="guardarNuevoRegistroTrama" data-destino="detalleItem">

		<fieldset id="informacionTrama">
			<legend>Información de trama</legend>
			<div data-linea="1">
				<label>Nombre trama:</label>
				<input type="text" id="nombreTrama" name="nombreTrama"/>
			</div>
			<div data-linea="2">
				<label>Separador: </label>
				<select id="separadorTrama" name="separadorTrama">
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
				<select id="formatoEntradaTrama" name="formatoEntradaTrama">
					<option value="0">Seleccione...</option>
					<option value="xls">.xls</option>
					<option value="csv">.csv</option>
					<option value="xml">.xml</option>
					<option value="txt">.txt</option>
				</select>
			</div>
			<div data-linea="3">
				<label>Formato de salida: </label>
				<select id="formatoSalidaTrama" name="formatoSalidaTrama">
					<option value="0">Seleccione...</option>
					<option value="txt">.txt</option>
					<option value="xls">.xls</option>
				</select>
			</div>
		</fieldset>
		<fieldset id="cabeceraTrama">
			<legend>Cabecera de trama</legend>
			<div data-linea="4">
				<label>Código segmento:</label>
				<input type="text" id="codigoSegmentoCabeceraTrama" name="codigoSegmentoCabeceraTrama"/>
			</div>
			<div data-linea="4">
				<label>Tamaño segmento:</label>
				<input type="text" id="tamanioSegmentoCabeceraTrama" name="tamanioSegmentoCabeceraTrama"/>
			</div>
		</fieldset>
		<fieldset id="detalleTrama">
			<legend>Detalle de trama</legend>
			<div data-linea="5">
				<label>Código segmento:</label>
				<input type="text" id="codigoSegmentoDetalleTrama" name="codigoSegmentoDetalleTrama"/>
			</div>
			<div data-linea="5">
				<label>Tamaño segmento:</label>
				<input type="text" id="tamanioSegmentoDetalleTrama" name="tamanioSegmentoDetalleTrama"/>
			</div>
		</fieldset>	
		<div>
			<button type="submit" class="guardar">Guardar</button>
		</div>
		
	</form>
	
<script type="text/javascript">			

    $(document).ready(function(){	

    	$("#tamanioSegmentoCabeceraTrama").numeric();
    	$("#tamanioSegmentoDetalleTrama").numeric();
		distribuirLineas();
		construirValidador();
		
    });

    $("#nuevoRegistroTrama").submit(function(){

  	 	event.preventDefault();
  	    $(".alertaCombo").removeClass("alertaCombo");
  	  	var error = false;

    	if($("#nombreTrama").val()==""){
			error = true;
			$("#nombreTrama").addClass("alertaCombo");
		}

    	/*if($("#separadorTrama").val()==""){
			error = true;
			$("#separadorTrama").addClass("alertaCombo");
		}*/

    	if($("#formatoEntradaTrama").val()==""){
			error = true;
			$("#formatoEntradaTrama").addClass("alertaCombo");
		}

    	if($("#formatoSalidaTrama").val()==""){
			error = true;
			$("#formatoSalidaTrama").addClass("alertaCombo");
		}

    	if($("#codigoSegmentoCabeceraTrama").val()==""){
			error = true;
			$("#codigoSegmentoCabeceraTrama").addClass("alertaCombo");
		}

    	if($("#tamanioSegmentoCabeceraTrama").val()==""){
			error = true;
			$("#tamanioSegmentoCabeceraTrama").addClass("alertaCombo");
		}

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
			abrir($(this), event, false);                          
		}

    });
</script>