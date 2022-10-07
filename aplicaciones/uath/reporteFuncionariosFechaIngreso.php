<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$ce = new ControladorCatastro();
$cc = new ControladorCatalogos();

?>

<header>
	<h1>Reporte por fecha de ingreso</h1>
	<nav>
	<form id="reporteFuncionarioFecha" data-rutaAplicacion="uath" action="aplicaciones/uath/listaFuncionariosFiltradosFecha.php" target="_blank" method="post" >
		<!-- action="aplicaciones/uath/listaContratosFiltrados.php" target="_blank" method="post" -->
		<table class="filtro">
			<tr>
			     <th>
					Cédula:
				</th>
				
				<td>
					<input id="identificador" type="text" name="identificador" maxlength="10" value="">
				</td>
				<td></td><td></td>
			</tr><tr>
			<tr>
			     <th>
					Apellido:
				</th>
				
				<td>
					<input id="apellido" type="text" name="apellido" maxlength="40" value="">
				</td>
				 <th>
					Nombre:
				</th>
				
				<td>
					<input id="nombre" type="text" name="nombre" maxlength="40" value="">
				</td>
			</tr><tr>
				<th>
					Provincia:
				</th>
				
				<td>
					<select id="provincia" name="provincia" style=" width:100%">
						<option value="">Seleccione....</option>
							<?php 	
								$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
								foreach ($provincias as $provincia){
									echo '<option value="' . $provincia['nombre'] . '" data-codigo="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
								}
							?>
					</select>
				</td>
				
				<th>Modalidad:</th>
				<td>
					<select id="modalidad" name="modalidad" style=" width:100%">
						<option value="">Seleccione....</option>
							<?php 	
							    $modalidad = $cc->obtenerModalidadContratoXRegimen($conexion,"1,3,4,5,6");
							    while ($fila = pg_fetch_assoc($modalidad)){
							        echo '<option value="' . $fila['nombre'] . '" >' . $fila['nombre'] . '</option>';
							    }
							?>
					</select>
				</td>	
								
			</tr>
			<tr>
				<th>Fecha inicio</th>
				<td>
					<input id="fecha_inicio" type="text" name="fecha_inicio" readonly value="">
				</td>	
				
<!-- 				<th>Fecha fin</th> -->
<!-- 				<td> -->
<!-- 					<input id="fecha_fin" type="text" name="fecha_fin" readonly value=""> -->
<!-- 				</td>	 -->
				<td></td>
				<td></td>
			</tr>
			
			<tr>
				<td colspan="5"><button>Buscar</button></td>
			</tr>
		</table>
		</form>
		
	</nav>
</header>
<div id="tabla"></div>
<script>

	$(document).ready(function(){
		
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Presione "Buscar" para generar el reporte.</div>');

		$("#fecha_inicio").datepicker({
		      changeMonth: true,
		      changeYear: true,
		      dateFormat: 'dd-mm-yy',
		      onSelect: function(dateText, inst) {
		  	  	$('#fecha_fin').datepicker('option', 'minDate', $("#fecha_inicio" ).val()); 
		      }
		    });

		$("#fecha_fin").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
	});


	$("#reporteFuncionarioFecha").submit(function(event){
		
		$(".alertaCombo").removeClass("alertaCombo");
		$("#estado").html("");
		var error = true;
		
		if($("#identificador").val()!=""){
			error = false;
		}
		if($("#apellido").val()!=""){
			error = false;
		}
		if($("#nombre").val()!=""){
			error = false;
		}
		if($("#provincia").val()!=""){
			error = false;
		}
		if($("#modalidad").val()!=""){
			error = false;
		}
		if($("#fecha_inicio").val()!=""){
			error = false;
		}
		if (!error){
			$(this).submit();
		}else{
			event.preventDefault();
			$("#estado").html("Seleccione un criterio de búsqueda...!!").addClass('alerta');
			}
	});

</script>