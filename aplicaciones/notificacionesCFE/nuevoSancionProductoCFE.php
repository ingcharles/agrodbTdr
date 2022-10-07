<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cfe = new ControladorFitosanitarioExportacion();

?>

<header>
	<h1>Sanción de producto CFE</h1>
</header>
	<div id="estado"></div>
	<div id="mensajeCargando"></div>
	
<form id='nuevoSancionProductoCFE' data-rutaAplicacion='notificacionesCFE' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

<input type="hidden" name="opcion" id="opcion" />		
	
<fieldset id="datosOperador">
<legend>Datos del Operador</legend>
	<div data-linea="1">
	<label>Número de Cédula/RUC:</label>
	<input type="text" id="identificadorExportador" name="identificadorExportador" maxlength="13" />
	</div>
	<div data-linea="1">
	<button type="button" class="buscarExportador" id="buscarExportador">Buscar</button>
	</div>
	<hr/>
	<div id="resultadoNotificacion" data-linea="5"></div>
	<div id="resultadoTipoProducto" data-linea="6"></div>
	<div id="resultadoSubtipoProducto" data-linea="8"></div>
	<div id="resultadoProducto" data-linea="9"></div>
	
</fieldset>

<fieldset id="datosSancion">


<legend>Datos de Sanción</legend>
	<div data-linea="5">

		<label>Fecha inicio:</label>
		<input type="text" id="fechaInicioSancion" name="fechaInicioSancion"/>
	</div>
	<div data-linea="5">
		<label>Fecha fin:</label>
		<input type="text" id="fechaFinSancion" name="fechaFinSancion"/>
	</div>
	<div data-linea="6">
		<label>Motivo:</label>
		<input type="text" id="motivoSancion" name="motivoSancion"/>
	</div>
	<div data-linea="7">
		<label>Observación:</label>
		<input type="text" id="observacionSancion" name="observacionSancion"/>
	</div>
</fieldset>

<button type="submit" class="guardar" id="guardar">Guardar sanción</button>

</form>

<script type="text/javascript">			
var contador = 0;

    $(document).ready(function(){	
    	$("#identificadorExportador").numeric();
		$("#guardar").hide();

		$("form").keypress(function(e) {
	        if (e.which == 13) {
	            return false;
	        }
	    });
	    		
		distribuirLineas();	

	});

    $("#buscarExportador").click(function(event){
    	 
    	 $('#nuevoSancionProductoCFE').attr('data-opcion','accionesSancionProducto');
    	 $('#nuevoSancionProductoCFE').attr('data-destino','resultadoNotificacion');
    	 $('#opcion').val('sanciones');
    	 abrir($("#nuevoSancionProductoCFE"),event,false);

  	 
    });
	
   $("#nuevoSancionProductoCFE").submit(function(event){
    	event.preventDefault();
    	chequearCampos(this);
    });


	$("#fechaInicioSancion").datepicker({
		dateFormat: 'dd/mm/yy',
        changeMonth : true,
        changeYear : true,	
        onSelect: function(dateText, inst) {
        	var sFecha =$("#fechaInicioSancion").val();
        	var aFecha = sFecha.split("/");
        	var Fecha = new Date(aFecha[2], aFecha[1], aFecha[0]);
        	var dias =6; //los dias que quieres aumentar
        	Fecha.setTime(Fecha.getTime()+dias*24*60*60*1000); 
        	Fecha=(("00"+Fecha.getDate()).slice(-2))+"/"+(("00"+Fecha.getMonth()).slice(-2))+"/"+Fecha.getFullYear();
        	
        	var fecha=new Date($('#fechaInicioSancion').datepicker('getDate'));
        	
        	fecha.setMonth(fecha.getMonth());
    		fecha.setUTCFullYear(fecha.getUTCFullYear());  
    		$('#fechaFinSancion').datepicker('option', 'minDate', fecha);
    		$("#fechaFinSancion").val(Fecha);
            
        }    
	});
   
	 $("#fechaFinSancion").datepicker({
		 dateFormat: 'dd/mm/yy',
         changeMonth : true,
         changeYear : true,
 	});

 
    function chequearCampos(form){
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;

    	if($("#identificadorExportador").val()=="" || $("#identificadorExportador").val()==0){	
    		error = true;		
    		$("#identificadorExportador").addClass("alertaCombo");
    	}

    	if($("#idTipoProducto").val()=="" || $("#idTipoProducto").val()==0){	
    		error = true;		
    		$("#idTipoProducto").addClass("alertaCombo");
    	}

    	if($("#idSubtipoProducto").val()=="" || $("#idSubtipoProducto").val()==0){	
    		error = true;		
    		$("#idSubtipoProducto").addClass("alertaCombo");
    	}

    	if($("#idProducto").val()=="" || $("#idProducto").val()==0){	
    		error = true;		
    		$("#idProducto").addClass("alertaCombo");
    	}

    	if($("#idPais").val()=="" || $("#idPais").val()==0){	
    		error = true;		
    		$("#idPais").addClass("alertaCombo");
    	}
    	
    	if($("#motivoSancion").val()==""){	
    		error = true;		
    		$("#motivoSancion").addClass("alertaCombo");
    	}

    	if($("#observacionSancion").val()==""){	
    		error = true;		
    		$("#observacionSancion").addClass("alertaCombo");
    	}

    	if($("#fecha_inicio_sancion").val()==""){	
    		error = true;		
    		$("#fecha_inicio_sancion").addClass("alertaCombo");
    	}

    	if($("#fecha_fin_sancion").val()==""){	
    		error = true;		
    		$("#fecha_fin_sancion").addClass("alertaCombo");
    	}
    	

    	if (error){
    		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
    	}else{
    		$("#nuevoSancionProductoCFE").attr('data-opcion', 'guardarNuevoSancionCFE');
    	    $("#nuevoSancionProductoCFE").attr('data-destino', 'detalleItem');
    	    
    		ejecutarJson(form);
    	}
    }; 
</script>