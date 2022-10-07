<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProtocolos.php';

$conexion = new Conexion();
$cp = new ControladorProtocolos();

?>

<header>
	<h1>Inspecciones de protocolo</h1>
</header>
<div id="estado"></div>
<div id="mensajeCargando"></div>

<form id="nuevoinspeccionProtocolo" data-rutaAplicacion="inspeccionesDeProtocolo">

	<input type="hidden" id="opcion" name="opcion" value="" />
	<input type="hidden" id="nombreOperacion" name="nombreOperacion" value="" />
	<input type="hidden" id="nombreAreaCodigo" name="nombreAreaCodigo" value="" />
	<input type="hidden" id="nombreProtocolo" name="nombreProtocolo" value="" />
	
	<fieldset id="datosSitio">
		<legend>Datos del operador</legend>
		<div data-linea="1">
			<label>Identificador operador:</label> <input type="text" id="idOperador" name="idOperador" data-resetear='no' />
		</div>
		<div data-linea="2" id="resultadoOperador"></div>
		<div data-linea="3" id="resultadoCodigoArea"></div>
		<div data-linea="5" id="resultadoNombreArea"></div>
	</fieldset>

	<fieldset id="resultadoInspeccion">

		<div data-linea="7">
			<div id="dProducto"></div>
		</div>
		<legend>Resultado de inspeción</legend>
		<div data-linea="8">
			<label>Protocolo:</label> <select id="protocolo" name="protocolo">
				<option value="">Seleccione...</option>
				<?php
				    $qProtocolos = $cp->listarProtocolos($conexion);
				    while ($protocolos = pg_fetch_assoc($qProtocolos)) {
				        echo '<option  value="' . $protocolos['id_protocolo'] . '">' . $protocolos['nombre_protocolo'] . '</option>';
                    }
                ?>
			</select>
		</div>

		<div data-linea="8">
			<label>Estado:</label> <select id="estadoInspeccion" name="estadoInspeccion" required>
				<option value="">Seleccione...</option>
				<option value="aprobado">Aprobado</option>
				<option value="desaprobado">Desaprobado</option>
				<option value="implementacion">En implementación</option>
			</select>
		</div>
	</fieldset>
	<button type="submit" id="agregar" class="mas">Añadir Protocolo</button>

</form>

<fieldset>
	<legend>Protocolos asignados</legend>
	<table id="tabla" style="width: 100%">
		<tr style="font-weight: bold;">
			<th>Protocolo</th>
			<th>Resultado</th>
			<th>Opción</th>
		</tr>
		<tbody id="protocoloComercio">
		</tbody>
	</table>
</fieldset>

<script type="text/javascript">
			
    $('document').ready(function(){
    	acciones("#nuevoinspeccionProtocolo","#protocoloComercio");
    	distribuirLineas();
    	construirValidador();
    });
    
    $("#idOperador").change(function(event){
    
    	if($("#idOperador").val() != ""){	
        	$('#nuevoinspeccionProtocolo').attr('data-destino','resultadoOperador');
        	$('#nuevoinspeccionProtocolo').attr('data-opcion','accionesInspeccionesProtocolo');
            $('#opcion').val('operador');
            event.stopImmediatePropagation();
        	abrir($("#nuevoinspeccionProtocolo"),event,false);
    	}
    	
    });
    
    $("#agregar").click(function(event){
    
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;
    
    	if($("#idOperador").val() == ""){	
    		error = true;		
    		$("#idOperador").addClass("alertaCombo");
    	}

    	if($("#codigoSitio").val() == ""){	
    		error = true;		
    		$("#codigoSitio").addClass("alertaCombo");
    	}

    	if($("#codigoArea").val() == ""){	
    		error = true;		
    		$("#codigoArea").addClass("alertaCombo");
    	}

    	if($("#idOperacion").val() == ""){	
    		error = true;		
    		$("#idOperacion").addClass("alertaCombo");
    	}

    	if($("#protocolo").val() == ""){	
    		error = true;		
    		$("#protocolo").addClass("alertaCombo");
    	}

    	if($("#estadoInspeccion").val() == ""){	
    		error = true;		
    		$("#estadoInspeccion").addClass("alertaCombo");
    	}   	
    	
    	if (error){
    		event.preventDefault();
    		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
    	}else{
    		$('#nuevoinspeccionProtocolo').attr('data-opcion','guardarNuevoInspeccionProtocolo');
    	}
    
    });

</script>







