<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cca = new ControladorCatalogos();

$qProvincias = $cca->listarLocalizacion($conexion, 'PROVINCIAS');

?>

<form id='abrirActivarPorcino' data-rutaAplicacion='catastroProducto' data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" name="opcion" /> 
	<input type="hidden" id="operacionDestino" name="operacionDestino" />
	<input type="hidden" id="identificadorProductoActivar" name="identificadorProductoActivar" />
	<div id="estado"></div>
	
	<fieldset>
		<legend>Producto a movilizar</legend>
		<div data-linea="1">
			<label>Ingrese identificador: </label> <input type="text"
				id="identificadorProducto" name="identificadorProducto" value="" />
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Datos de origen</legend>
		<div id="dOperaciones" style="width: 100%"></div>
	</fieldset>
	
	<fieldset>
		<legend>Datos de destino</legend>
		<div data-linea="1">
			<label>Provincia: </label> <select id="provincia" name="provincia">
				<option value="">Seleccione...</option>
					<?php
                    while ($fila = pg_fetch_assoc($qProvincias)) {
                        echo '<option value="' . $fila['nombre'] . '">' . $fila['nombre'] . '</option>';
                    }
                    ?>
			</select>
		</div>
		<div data-linea="1">
			<label>Identificación Operador: </label> <input type="text"
				id="identificadorOperador" name="identificadorOperador" value="" />
		</div>
		<hr>
		<div id="resultadoOperador" data-linea="2">
			<label>Sitio Destino: </label> <select id="idSitioDestino"
				name="idSitioDestino">
				<option value="">Seleccione...</option>
			</select>
		</div>
		<div id="resultadoSitio" data-linea="3">
			<label>Área Destino: </label> <select id="idAreaDestino"
				name="idAreaDestino">
				<option value="">Seleccione...</option>
			</select>
		</div>
		<button type="submit" id="btnGuardar" name="btnGuardar"
			class="guardar">Guardar</button>
	</fieldset>
	
</form>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

	$("#identificadorProducto").change(function(event){
		event.stopImmediatePropagation();
		$("#identificadorProductoActivar").val($("#identificadorProducto").val());
		$("#identificadorOperador").val("");
		$("#idSitioDestino").html("");
		$("#idSitioDestino").append("<option value=''>Seleccione...</option>");
		$("#areaDestino").html("");
		$("#areaDestino").append("<option value=''>Seleccione...</option>");
 		$("#abrirActivarPorcino").attr('data-destino','dOperaciones');
 		$("#abrirActivarPorcino").attr('data-opcion', 'accionesCatastro');
 		$("#opcion").val('buscarSitioCatastroIdentificador');
		abrir($("#abrirActivarPorcino"),event,false); 
		$("#identificadorProducto").val("");
	 });

	$("#identificadorOperador").change(function(event){

    	if($("#identificadorOperador").val() != $("#identificacionOperadorOrigen").val()){    		
    		event.stopImmediatePropagation();
     		$("#abrirActivarPorcino").attr('data-destino','resultadoOperador');
     		$("#abrirActivarPorcino").attr('data-opcion', 'accionesCatastro');
     		$("#opcion").val('buscarSitioIdentificadorOperador');
    		abrir($("#abrirActivarPorcino"),event,false); 
    
    	}else{
        	
    		$("#identificadorOperador").val("");
    		
    	}	
    		
	 });

	$("#provincia").change(function(event){
		$("#identificadorOperador").val("");
	});

	$("#abrirActivarPorcino").submit(function(event){
		
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($('#tablaProductoMovilizar tr').length == 0) {	
			error = true;
			$("#identificadorProducto").addClass("alertaCombo");	
		}

		if ($("#provincia").val()==""){
		   error = true;
	       $("#provincia").addClass("alertaCombo");
		}
		
    	if ($("#idSitioDestino").val()==""){
		   error = true;
	       $("#idSitioDestino").addClass("alertaCombo");
		}

        if ($("#idAreaDestino").val()==""){
         	 error = true;
             $("#idAreaDestino").addClass("alertaCombo");
        }
 
 		if($("#identificadorOperador").val()==""){
			error = true;
			$("#identificadorOperador").addClass("alertaCombo");
		} 		
		
		if (!error){
			$('#abrirActivarPorcino').attr('data-opcion','guardarActivarPorcinoCambioDuenio');
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}		
	});

</script>