<?php
session_start();
require_once '../../clases/Conexion.php';

$conexion = new Conexion();

?>

<div id="estado"></div>

	<header>
		<h1>Nuevo Documento</h1>
	</header>
	<form id="nuevoRegistroDocumento" data-rutaAplicacion="conciliacionBancaria" data-opcion="guardarNuevoRegistroDocumento" data-destino="detalleItem">
		<fieldset id="informacionDocumento">
		<legend>Información de Documento</legend>
			<div data-linea="1">
				<label>Nombre documento:</label>
				<input type="text" id="nombreDocumento" name="nombreDocumento"/>
			</div>
			<div data-linea="2">
				<label>Tipo de documento:</label>
				<select id="tipoDocumento" name="tipoDocumento">
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
				<select id="formatoEntradaDocumento" name="formatoEntradaDocumento">
					<option value="">Seleccione...</option>
					<option value="xls">.xls</option>
					<option value="csv">.csv</option>
					<option value="xml">.xml</option>
				</select>
			</div>
			<div data-linea="3">
				<label>Número de columnas:</label>
				<input type="text" id="numeroColumnasDocumento" name="numeroColumnasDocumento"/>
			</div>
			<div data-linea="4">
				<label>Fila inicio lectura:</label>
				<input type="text" id="filaInicioLecturaDocumento" name="filaInicioLecturaDocumento"/>
			</div>
			<div data-linea="4">
				<label>Columna inicio lectura:</label>
				<input type="text" id="columnaInicioLecturaDocumento" name="columnaInicioLecturaDocumento"/>
			</div>
			
		</fieldset>
		<div>
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</form>
	
<script type="text/javascript">			

    $(document).ready(function(){	
    	distribuirLineas();	
    	$("#numeroColumnasDocumento").numeric();
    	$("#filaInicioLecturaDocumento").numeric();
    	$("#columnaInicioLecturaDocumento").numeric();
    });    	

    $("#nuevoRegistroDocumento").submit(function(){

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

    	if($("#FormatoEntradaDocumento").val()==""){
			error = true;
			$("#FormatoEntradaDocumento").addClass("alertaCombo");
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
			//ejecutarJson($(this));   
			abrir($(this), event, false);                          
		}
		
    });


</script>	