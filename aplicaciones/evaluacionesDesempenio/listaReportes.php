<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();
$ce = new ControladorCatalogos();

$res = $ced->listaEvaluaciones($conexion, 'FINAL');
?>
<header>
	<h1>Responsables</h1>
	<nav>	
	<form id="generarReporte" action="aplicaciones/evaluacionesDesempenio/generarReporte.php" target="_blank" method="post" data-rutaAplicacion="evaluacionesDesempenio">
		  <div id="estado"></div>
		<table class="filtro">	
			<tr>
				<th>* Evaluación:</th>
					<td>
					<select style='width:100%' name="idEvaluacion" id="idEvaluacion" >
					<option value="" >Seleccione...</option>
						<?php
						   while($fila = pg_fetch_assoc($res)){
								echo '<option value="'.$fila['id_evaluacion'].'">'. $fila['nombre'] . '</option>';
							}		   					
						?>
				    </select>
					</td>
			</tr>		
			<tr>
				<th>* Tipo Reporte:</th>
					<td>
					<select style='width:100%' name="tipo" id="tipo" >
					<option value="" >Seleccione...</option>
						<?php
						$tipo = array('Servidores, ubicación y estructura organizacional','Servidores con evaluaciones pendientes','Resultado servidores');										
						for ($i=0; $i<sizeof($tipo); $i++){
							echo '<option value='.$i.'>'. $tipo[$i] . '</option>';
						}		   					
						?>
				   </select>
					</td>
				
			</tr>														
			<tr>
				<th>* Provincia:</th>
				<td>
					<select style='width:100%' name="provincia" id="provincia" >
					<option value="" >Seleccione...</option>
						<?php  //$provincia['codigo']
								$provincias = $ce->listarSitiosLocalizacion($conexion,'PROVINCIAS');
								foreach ($provincias as $provincia){
										echo '<option value="' . $provincia['nombre'] . '">' . $provincia['nombre'] . '</option>';
								}
							?>
					 <option value="Todas" >Todas</option>
				</select>
				</td>					
			</tr>								
		</table>
				<button>Generar Reporte</button>
	</form>		
	</nav>
</header>

<div id="tabla"></div>

<script type="text/javascript">

$(document).ready(function(){
	$("#detalleItem").html('<div class="mensajeInicial">Presione generar el reporte.</div>');
});							

$("#generarReporte").submit(function(event){
	
	 $(".alertaCombo").removeClass("alertaCombo");
	 $("#estado").html("");
		var error = false;
		
		if($("#idEvaluacion").val()==""){
			error = true;
			$("#idEvaluacion").addClass("alertaCombo");
		}
		if($("#tipo").val()==""){
			error = true;
			$("#tipo").addClass("alertaCombo");
		}
		if($("#provincia").val()==""){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}
		
		if (error == false){
		switch ($("#tipo").val()) {
    		case '0':
    			$('#generarReporte').attr('action', 'aplicaciones/evaluacionesDesempenio/generarReporte.php');
        break;
    		case '1':
    			$('#generarReporte').attr('action', 'aplicaciones/evaluacionesDesempenio/generarReportePendiente.php');
        break;
    		case '2':
    			$('#generarReporte').attr('action', 'aplicaciones/evaluacionesDesempenio/generarReporteResultados.php');
        break;
		}	
			
		$(this).submit();
		}else{
			 event.preventDefault();
			$("#estado").html("Todos los campos con ( * ) son obligatorios...!").addClass('alerta');
		}	
});

</script>	

	
