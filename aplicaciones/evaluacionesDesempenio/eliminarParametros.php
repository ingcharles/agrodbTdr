<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
require_once '../../aplicaciones/uath/models/salidas.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<header>
	<h1>Eliminar Parámetros</h1>
</header>
<form id="eliminarParametro" 
	  data-rutaAplicacion="evaluacionesDesempenio" 
	  data-opcion="borrarParametros" 
	  data-accionEnExito="ACTUALIZAR">
	  <div id="estado"></div>
	  
	  <input type="hidden" name="codParametro" id="codParametro" value="<?php echo $_POST['elementos'];?>" />
<?php 
if(!empty($_POST['elementos']) and is_numeric($_POST['elementos']))
		 {  
		 	$resultadoParametros = pg_fetch_assoc($ced->listaParametros($conexion, 'ABIERTOS',$_POST['elementos']));
		 	$consultaEva= pg_fetch_result($ced->devolverEvaluacionVigente ($conexion,'',$_POST['elementos'],'' ),0,'vigencia');
		 	
		?>	  
	<fieldset>	
		<legend>Información</legend>		
			<div data-linea="1">
				<label>* Nombre parámetros: </label>	 
				<input type="text" name="nombreParametro" id="nombreParametro" value="<?php echo $resultadoParametros['nombre_parametro'];?>" placeholder="Ej: 2017 Primer Semestre" disabled="disabled"> 
			</div>		
	</fieldset>
	<fieldset>	
		<legend>Duración</legend>		
			<div data-linea="1">
				<label>* Año: </label>	
				<select style='width:100%' name="anio" id="anio" disabled="disabled">
					<option value="" >Seleccione...</option>
					<?php
						for($anio=date("Y"); $anio<=(date("Y")+3); $anio++) {
							echo "<option value=".$anio.">".$anio."</option>";
						}
					?>
				</select> 
			</div>
			<div data-linea="1">
				<label>* Período: </label>	 
				<select style='width:100%' name="periodo" id="periodo" disabled="disabled">
					<option value="" >Seleccione...</option>
					<?php
						$area = array('Mensual','Semestral','Anual');										
						for ($i=0; $i<sizeof($area); $i++){
							echo '<option value="'.$area[$i].'">'. $area[$i] . '</option>';
						}		   					
					?>
				</select>
			</div>	
			<div data-linea="2" id="divSemestre">
				<label> * Semestre: </label>	
				<select style='width:100%' name="semestre" id="semestre" >
					
				</select> 
			</div>
			<div data-linea="2" id="divMesInicio">
				<label> * Mes inicio: </label>	
				<select style='width:100%' name="mesIni" id="mesIni" >
				   
				</select> 
			</div>
			<div data-linea="2" id="divMesFin">
				<label> * Mes fin: </label>	
				<select style='width:100%' name="mesFin" id="mesFin" >
					
				</select> 
			</div>	
			<div data-linea="3">
				<label>* Disponibilidad (días laborables): </label>	 
				<input type="text" name="numDias" id="numDias" value="<?php echo $resultadoParametros['dias_laborables'];?>" placeholder="Ej: 3" disabled="disabled"> 
			</div>	
			<div data-linea="4">
				<label>* Fecha inicio: </label>	 
				<input type="text"
					id="fechaInicio" name="fechaInicio" value="<?php echo $resultadoParametros['fecha_inicio'];?>" readonly disabled="disabled"/>
			</div>	
			<div data-linea="4">
				<label>* Fecha fin: </label>	
				<input type="text"
					id="fechaFin" name="fechaFin" value="<?php echo $resultadoParametros['fecha_fin'];?>" readonly  disabled/> 
			</div>		
				
	</fieldset>
	<fieldset>	
		<legend>Calificación de Resultados</legend>		
			<div data-linea="5">
				<label>* Evaluación por área (tiempo mínimo meses): </label>	
				<select style='width:100%' name="evaluacionArea" id="evaluacionArea" disabled="disabled">
					<option value="" >Seleccione...</option>
					<?php
						for ($i=1; $i<13; $i++){
							echo '<option value="'.$i.'">'. $i . '</option>';
						}		   					
					?>
				</select> 
			</div>		
			<div data-linea="6">
				<label>* Cálculo de resultados por área: </label>	 
				<select style='width:100%' name="calculoResultado" id="calculoResultado" disabled="disabled">
					<option value="" >Seleccione...</option>
					<option value="promedio" >Promedio</option>
					<option value="calificacion" >Calificación Única</option>
					
				</select>
			</div>	
	</fieldset>
	<fieldset>	
	<legend>Notificaciones</legend>		
			<div data-linea="6">
				<label>* Envío de notificaciones: </label>
				<select style='width:100%' name="envioNotificacion" id="envioNotificacion" disabled="disabled">
					<option value="" >Seleccione...</option>
					<option value="Si" >Si</option>
					<option value="No" >No</option>
					
				</select>	 
			</div>	
			<div data-linea="6">
				<label>* Notificaciones: </label>
				<select style='width:100%' name="notificacion" id="notificacion" disabled="disabled">
					<option value="0" >Seleccione...</option>
					<option value="1" >Correos de notificación</option>
				</select>	 
			</div>	
	</fieldset>
	<p>
		<button id="eliminarRes" type="submit" class="guardar" >Eliminar</button>
	</p>
	
</form>
<?php }else{ 
    $mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Debe seleccionar un elemento para eliminar...!!';
	mensajesSalidas($mensaje);
 }?>
<script type="text/javascript">

	var periodoDat= <?php echo json_encode ($resultadoParametros['periodo']);?>;
	var valor_resultado= <?php echo json_encode($consultaEva); ?>;

	$(document).ready(function(){
		$("#eliminarParametro select").attr("disabled","disabled");
		//$('#eliminarRes').attr("disabled","disabled");
		
		if(valor_resultado == 'eliminado' || valor_resultado == 'activo' || valor_resultado == 'proceso' || valor_resultado == 'finalizado' || valor_resultado == 'creado' || valor_resultado == 'excepciones' || valor_resultado == 'cerrado' ){
			$('#eliminarRes').attr("disabled","disabled");
			$("#estado").html("El parámetro se asigno a una evaluación").addClass('alerta');
		}
		
		distribuirLineas();
		construirValidador();
		$("#divSemestre").hide();
		$("#divMesInicio").hide();
		$("#divMesFin").hide();

		if(periodoDat == 'Semestral'){
		  	speriod = '<option value="">Seleccione...</option>';
		  	speriod += '<option value="Primero">Primero</option>';
		  	speriod += '<option value="Segundo">Segundo</option>';
	   		$('#semestre').html(speriod);
	   		$("#divSemestre").show();
	   	 	$('#mesIni').html('');
	   	 	$('#mesFin').html('');
	   }else if(periodoDat == 'Mensual'){
		   $('#semestre').html('');
		   $("#divMesInicio").show();
		   $("#divMesFin").show();
		   $("#mesIni").html(agregarMes());
		   $("#mesFin").html(agregarMes());
	   }
		
		cargarValorDefecto("anio","<?php echo $resultadoParametros['anio'];?>");
		cargarValorDefecto("periodo","<?php echo $resultadoParametros['periodo'];?>");
		cargarValorDefecto("semestre","<?php echo $resultadoParametros['semestre'];?>");
		cargarValorDefecto("evaluacionArea","<?php echo $resultadoParametros['tiempo_minimo_area'];?>");
		cargarValorDefecto("calculoResultado","<?php echo $resultadoParametros['calculo_resultados'];?>");
		cargarValorDefecto("envioNotificacion","<?php echo $resultadoParametros['envio_notificacion'];?>");
		cargarValorDefecto("notificacion","<?php echo $resultadoParametros['cod_notificacion'];?>");
		cargarValorDefecto("mesIni","<?php echo $resultadoParametros['mes_inicio'];?>");
		cargarValorDefecto("mesFin","<?php echo $resultadoParametros['mes_fin'];?>");
		
	});
	
	
	$("#fechaInicio").datepicker({
		beforeShowDay: $.datepicker.noWeekends,
		changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd'
	    
	  });

	$('#fechaFin').datepicker({ 
	        changeMonth: true,
		    changeYear: true,
		    dateFormat: 'yy-mm-dd'
	   });

//-----------------------------------------------------------------------------------------------------------------------
	 $("#eliminarParametro").submit(function(event){
		 event.preventDefault();
		 ejecutarJson($(this));
	 });

	 function agregarMes(){
			speriod = '<option value="">Seleccione...</option>';
		  	speriod += '<option value="Enero">Enero</option>';
		  	speriod += '<option value="Febrero">Febrero</option>';
		  	speriod += '<option value="Marzo">Marzo</option>';
		  	speriod += '<option value="Abril">Abril</option>';
		  	speriod += '<option value="Mayo">Mayo</option>';
		  	speriod += '<option value="Junio">Junio</option>';
		  	speriod += '<option value="Julio">Julio</option>';
		  	speriod += '<option value="Agosto">Agosto</option>';
		  	speriod += '<option value="Septiembre">Septiembre</option>';
		  	speriod += '<option value="Octubre">Octubre</option>';
		  	speriod += '<option value="Noviembre">Noviembre</option>';
		  	speriod += '<option value="Diciembre">Diciembre</option>';
		  	return speriod;
		}
</script>

</body>
</html>

