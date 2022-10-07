<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cr = new ControladorRequisitos();
$cc = new ControladorCatalogos();

?>

<header>
	<h1>Asignar variedad de producto</h1>
</header>

<div id="estado"></div>

<div id="mensajeCargando"></div>

<form id='nuevoAsignacionVariedad' data-rutaAplicacion='administracionProductos' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" name="opcion" id="opcion" />
	
	<fieldset>
		<legend>Información del Producto</legend>
			<div data-linea="1">
								<label>Tipo producto:</label>				
								<select id="tipoProducto" name="tipoProducto">
									<option value="0">Seleccione...</option>
									<?php 
										$tipo= $cc-> listaTipoProducto($conexion, 'tipoProducto');
										
										while ($fila = pg_fetch_assoc($tipo)){
								    		echo '<option value="'.$fila['id_tipo_producto']. '" data-grupo="'. $fila['id_area'] . '">'. $fila['nombre'] .'</option>';
								    	}
									?>
								</select>
			</div>
			<div id="resultadoTipoProducto" data-linea="2"></div>
			<div id="resultadosubtipoProducto" data-linea="3"></div>
			<div id="resultadoProducto" data-linea="4"></div>
			<div id="resultadoOperacion" data-linea="5"></div>
	</fieldset>
	<fieldset id="elegirNumeroVariedades">
	<legend>Asignar variedad a producto</legend>
		<div data-linea="6">
			<label>Una:</label><input type="radio" name="siNoMultiple" id="no" value="false"/>
		</div>
		<div data-linea="7">
			<label>Varias:</label><input type="radio" name="siNoMultiple" id="si" value="true"/>
		</div>
	</fieldset>
		<button id="btnGuardar" type="submit" name="btnGuardar">Guardar</button>
</form>

<script type="text/javascript">	
$(document).ready(function(){			
	distribuirLineas();	
	construirValidador();
	$('#elegirNumeroVariedades').hide();
	$('#btnGuardar').hide();
});


$("#tipoProducto").change(function(event){
			 $('#nuevoAsignacionVariedad').attr('data-opcion','accionesAsignacionVariedad');
    		 $('#nuevoAsignacionVariedad').attr('data-destino','resultadoTipoProducto');
    		 $('#opcion').val('tipoProducto');	
    	//	 $("#areaProducto").val($("#tipoProducto option:selected").attr('data-grupo'));	
    		 abrir($("#nuevoAsignacionVariedad"),event,false);	
	});


$("#nuevoAsignacionVariedad").submit(function(event){
    
    event.preventDefault();

    $(".alertaCombo").removeClass("alertaCombo");
  	var error = false;

		if($("#tipoProducto").val()=="0"){	
			error = true;		
			$("#tipoProducto").addClass("alertaCombo");
			//alert(variablejs);
		}

		if($("#subtipoProducto").val()=="0"){	
			error = true;		
			$("#subtipoProducto").addClass("alertaCombo");
		}

		if($("#producto").val()=="0"){	
			error = true;		
			$("#producto").addClass("alertaCombo");
		}

		if($("#tipoOperacion").val()=="0"){	
			error = true;		
			$("#tipoOperacion").addClass("alertaCombo");
		}

		if($("input:radio[name=siNoMultiple]:checked").val() == null){
			   error = true;
			   $("#si").addClass("alertaCombo");
			   $("#no").addClass("alertaCombo");
			  }


		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				 $('#nuevoAsignacionVariedad').attr('data-opcion','guardarMultiplesVariedades');
				ejecutarJson($(this));                             
			}
	});

</script>
