<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
$conexion = new Conexion();
$cl = new ControladorLotes();

?>
<header>
	<nav>
		<form id="filtrarLotesConformados" data-rutaAplicacion='conformacionLote' action="aplicaciones/conformacionLotes/reporteImprimirLotes.php" target="_self" method="post">
			<input type="hidden" name="opcion" id="opcion" value="0" />
			<table class="filtro" style='width:100%;'>
				<tbody>
					<tr>
						<th colspan="4">Reporte de Lotes Conformados</th>					
					</tr>
					
					<tr>
						<th><label for="identificacionOperador">Identificación Operador:</label></th>
						<th colspan="3">
							<input type="text" id="identificacionOperador" name="identificacionOperador" style='width:100%;'>
						</th>
					</tr>
					<tr>
						<th><label for="nombreOperador">Nombre Operador:</label></th>
						<th colspan="3">
							<input type="text" id="nombreOperador" name="nombreOperador" style='width:100%;'>
							
						</th>
					</tr>
					<tr>
						<th><label for="nombreProducto">Nombre Producto:</label></th>
						<th colspan="3">
							<select id="nombreProducto" name="nombreProducto" style='width:100%;'>
								<option value="">Selecione....</option>
								<?php
    								$res = $cl->listarProductosTrazabilidadTodos($conexion);
    								while ($fila=pg_fetch_assoc($res)){
    								    echo '<option value="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</option>';
    								}
								?>
							</select>
						</th>
					</tr>
					<tr>
						<th><label for="numeroLote">Lote Nro:</label></th>
						<th colspan="3">
							<input type="text" id="numeroLote" name="numeroLote" style='width:100%;'>
						</th>
					</tr>
					<tr>
						<th><label for="codigoLote">Código Lote:</label></th>
						<th colspan="3">
							<input type="text" id="codigoLote" name=codigoLote style='width:100%;'>
						</th>
					</tr>					
					<tr>
						<th><label for="fechaInicio">Fecha Inicio:</label></th>
						<th><input id="fechaInicio" type="text" name="fechaInicio"></th>
						<th><label for="fechaFin">Fecha Fin:</label></th>
						<th><input id="fechaFin" type="text" name="fechaFin"></th>
					</tr>
					<tr>
						<td colspan="4" style='text-align:center'><button type="submit" class="guardar" >Generar Reporte Excel</button></td>
					</tr>
					
					<tr>
						<td colspan="4"  align="center" id="estado" ></td>
					</tr>
				</tbody>
			</table>
		</form>	
	</nav>
</header>
<script>
								
	
						
	$(document).ready(function(){

		distribuirLineas();
		$("#fechaInicio").datepicker({
		      changeMonth: true,
		      changeYear: true,
		      maxDate:"0"
		});
		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true,
		      maxDate:"0"
		});

		$("#identificacionOperador").numeric();		

		$("#fechaInicio").attr("readOnly","readOnly");
		$("#fechaFin").attr("readOnly","readOnly");

		fecha = fechaActual();
		$("#fechaInicio").val(fecha);
		$("#fechaFin").val(fecha);

		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');
		
				
	});
	
	function fechaActual() {
	  	var date = new Date();
	  	var year = date.getFullYear();
	 	var month = (1 + date.getMonth()).toString();
	 	month = month.length > 1 ? month : '0' + month;
	  	var day = date.getDate().toString();
	  	day = day.length > 1 ? day : '0' + day;
	 	return  day + '/' + month + '/' +  year;
	}	

	
	
	$("#filtrarLotesConformados").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		 
		if ($("#nombreOperador").val().length <= 2 && $("#identificacionOperador").val()=="" && $("#numeroLote").val()=="" && $("#codigoLote").val()=="" ){ //&& $("#fechaInicio").val()=="" && $("#fechaFin").val()==""
			error = true;
	    	$("#estado").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
	    }

		if($("#identificacionOperador").val()=="" && $("#nombreOperador").val()=="" && $("#numeroLote").val()=="" && $("#codigoLote").val()=="" ){ 
			error = true;
			$("#identificacionOperador").addClass("alertaCombo");
			$("#nombreOperador").addClass("alertaCombo");
			$("#numeroLote").addClass("alertaCombo");
			$("#codigoLote").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese algún filtro para generar el reporte.").addClass('alerta');
		}

		if ($("#identificacionOperador").val().length >= 1 && $("#identificacionOperador").val().length < 10 ){
			error = true;	
			$("#identificacionOperador").addClass("alertaCombo");
			$("#estado").html("El número de cédula esta incompleto").addClass('alerta');
		}


		if ($("#identificacionOperador").val().length > 10 && $("#identificacionOperador").val().length < 13 ){
			error = true;	
			$("#identificacionOperador").addClass("alertaCombo");
			$("#estado").html("El número de ruc esta incompleto").addClass('alerta');
		}
				
		
		if (error){			
			event.preventDefault();
		}else{ 
			$("#estado").html("").removeClass('alerta');                
			ejecutarJson(form);      	
		}
	});

		
</script>