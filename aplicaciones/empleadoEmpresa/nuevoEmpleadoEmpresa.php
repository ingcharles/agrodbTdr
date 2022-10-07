<?php 
session_start();
require_once '../../clases/Conexion.php';
$conexion = new Conexion();

?>
<header>
	<h1>Nuevo Empleado</h1>
</header>
<form id='nuevoEmpleadoEmpresa' data-rutaAplicacion='empleadoEmpresa'  >
	<input type="hidden" id="opcion" name="opcion" value="">
	<input type="hidden" id="empresa" name="empresa" value="<?php echo $_SESSION['usuario'];	?>" >
	<div id="estado"></div>
	
	<fieldset>
		<legend>Búsqueda de Empleados</legend>
		<div data-linea="1">
			<label>Identificación Empleado:</label>
			<input id="identificadorEmpleado" name="identificadorEmpleado" type="text" placeholder="Ej: 9999999999"  maxlength="13" />
		</div>
		<div data-linea="1">
			<label>Nombres o Apellidos:</label>
			<input name="nombreEmpleado" id="nombreEmpleado"  type="text" placeholder="Ej: David"  maxlength="250"  />
		</div>
		
		<div data-linea="2" style="text-align: center">
			<button type="button" id="buscarEmpleado" name="buscarEmpleado" >Buscar empleado</button>
		</div>
	</fieldset>
	<fieldset>
		<legend>Agregar Empleados</legend>
		  <div data-linea="1" id="resultadoEmpleado">				
				<label>Empleados: </label>
				<select id="campoEmpleadoRol">
					<option value="0">Seleccione...</option>
				</select>
							
		  </div>		   			   			   		   			   			
	</fieldset>	
	<button type="submit" class="guardar">Agregar Empleado</button>
</form>

<script type="text/javascript">			
    $(document).ready(function(){			
		distribuirLineas();		
	});

	$("#buscarEmpleado").click(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if ($("#nombreEmpleado").val().length < 3 && $("#identificadorEmpleado").val()=="") {
			$("#nombreEmpleado").addClass("alertaCombo");
	    	error = true;
	    	$("#estado").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
	    }
	    
		if($("#identificadorEmpleado").val()=="" && $("#nombreEmpleado").val()==""){	
			 error = true;		
			$("#identificadorEmpleado").addClass("alertaCombo");
			$("#nombreEmpleado").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese al menos un campo para realizar la búsqueda.").addClass('alerta');
		}
		
		if (!error){
			$("#estado").html("").removeClass('alerta');
			$('#nuevoEmpleadoEmpresa').attr('data-opcion','accionesEmpleadoEmpresa');
			$('#nuevoEmpleadoEmpresa').attr('data-destino','resultadoEmpleado');
		    $('#opcion').val('obtenerEmpleado');		
			abrir($("#nuevoEmpleadoEmpresa"),event,false); 
		}				 		
	 });
	 
    $("#nuevoEmpleadoEmpresa").submit(function(event){		

    	event.preventDefault();
    	$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#empleado").val()==0 || $("#campoEmpleadoRol").val()==0){	
			 error = true;	
			 $("#empleado").addClass("alertaCombo");
			 $("#campoEmpleadoRol").addClass("alertaCombo");	
			 $("#estado").html("Por favor seleccione el empleado.").addClass('alerta');
		}

		if (!error){
			$(this).attr('data-destino', 'detalleItem'); 
			$(this).attr('data-opcion','guardarEmpleadoEmpresa');   
			ejecutarJson(this);
			if($('#estado').html()=='Los datos han sido ingresados satisfactoriamente')
				$('#_actualizar').click();
		}
	});
</script>