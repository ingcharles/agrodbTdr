<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();

$resultadoParametros = pg_fetch_assoc($ced->listaParametros($conexion, 'ABIERTOS',$_POST['id']));
$consultaEva= pg_fetch_result($ced->devolverEvaluacionVigente ($conexion,'',$_POST['id'],'' ),0,'vigencia');
?>
<header>
	<h1>Modificación de parámetros de configuración de la evaluación</h1>
</header>
<form id="modificarParametro" 
	  data-rutaAplicacion="evaluacionesDesempenio" 
	  data-opcion="modificarParametros" 
	  data-accionEnExito="ACTUALIZAR">
	  <div id="estado"></div>
	  
	  <input type="hidden" name="codParametro" id="codParametro" value="<?php echo $_POST['id'];?>" />
	  
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
		<button id="modificarRes" type="button" class="editar" <?php echo ($filaSolicitud['estado']=='Aprobado'? ' disabled=disabled':'')?>>Modificar</button>
		<button id="actualizarRes" type="submit" class="guardar" disabled="disabled">Guardar</button>
	</p>
	
</form>

<script type="text/javascript">

	var periodoDat= <?php echo json_encode ($resultadoParametros['periodo']);?>;
	var valor_resultado= <?php echo json_encode($consultaEva); ?>;

	$(document).ready(function(){
		$("#numDias").numeric();
		$("#modificarParametro select").attr("disabled","disabled");
		if(valor_resultado == 'activo' || valor_resultado == 'proceso' || valor_resultado == 'finalizado' || valor_resultado == 'eliminado' || valor_resultado == 'cerrado'){
			$('#modificarRes').attr("disabled","disabled");
			$("#estado").html("El parametro esta asignado a una evaluación y no puede ser modificado..!!").addClass('alerta');
		}else if(valor_resultado == 'creado'){
			$('#modificarRes').removeAttr("disabled","disabled");
			$("#estado").html("");
			
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
	
	$("#modificarRes").click(function(){	
		$("#modificarParametro input").removeAttr("disabled");
		$("#modificarParametro select").removeAttr("disabled");
		$("#actualizarRes").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		
	});
	
	$("#fechaInicio").datepicker({
		beforeShowDay: $.datepicker.noWeekends,
		changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    minDate: 0, 
	    onSelect: function(dateText, inst) {
	    	if($('#numDias').val() != ''){
	    	var fecha=new Date($('#fechaInicio').datepicker('getDate'));
			var numd=$("#numDias").val()*1;
			var i;
			fecha.setDate(fecha.getDate()+1);
			for(i=1; i<=numd;){
				if(fecha.getDay()==1 ||fecha.getDay()==2 || fecha.getDay()==3 || fecha.getDay()==4 || fecha.getDay()==5 ){	
		    		fecha.setDate(fecha.getDate()+1);
		    		i++;
				}else {
					fecha.setDate(fecha.getDate()+1);
					} 
				}
			fecha.setDate(fecha.getDate()-1);
	    	fecha.setMonth(fecha.getMonth());
			fecha.setUTCFullYear(fecha.getUTCFullYear());  
			$('#fechaFin').datepicker("setDate", fecha);
	   		}
	    }
	    
	  });

	$('#fechaFin').datepicker({ 
	        changeMonth: true,
		    changeYear: true,
		    dateFormat: 'yy-mm-dd'
	   });

//-----------------------------------------------------------------------------------------------------------------------
	$("#numDias").on('change', function (){
		if($('#fechaInicio').val() != ''){
			var fecha=new Date($('#fechaInicio').datepicker('getDate'));
			var numd=$("#numDias").val()*1;
			var i;
			fecha.setDate(fecha.getDate()+1);
			for(i=1; i<=numd;){
				if(fecha.getDay()==1 ||fecha.getDay()==2 || fecha.getDay()==3 || fecha.getDay()==4 || fecha.getDay()==5 ){	
		    		fecha.setDate(fecha.getDate()+1);
		    		i++;
				}else {
					fecha.setDate(fecha.getDate()+1);
					} 
				}
			fecha.setDate(fecha.getDate()-1);
	    	fecha.setMonth(fecha.getMonth());
			fecha.setUTCFullYear(fecha.getUTCFullYear());  
			$('#fechaFin').datepicker("setDate", fecha);

		}
	 });
//----------------------------------------------------------------------------------------------------------------------
	$("#periodo").on('change', function (event){
		if($("#periodo").val() == 'Semestral'){
			$("#divSemestre").show();
			$("#divMesInicio").hide();
			$("#divMesFin").hide();
			speriod = '<option value="">Seleccione...</option>';
		  	speriod += '<option value="Primero">Primero</option>';
		  	speriod += '<option value="Segundo">Segundo</option>';
	   		$('#semestre').html(speriod);
	   		$('#mesIni').html('');
	   	 	$('#mesFin').html('');
	   		
		}else if ($("#periodo").val() == 'Mensual'){
			$("#mesIni").html(agregarMes());
			$("#mesFin").html(agregarMes());
			$("#divSemestre").hide();
			$("#divMesInicio").show();
			$("#divMesFin").show();
			$('#semestre').html('');

		}else if ($("#periodo").val() == 'Anual'){
			$("#divSemestre").hide();
			$("#divMesInicio").hide();
			$("#divMesFin").hide();
			$('#mesIni').html('');
	   	 	$('#mesFin').html('');
	   	    $('#semestre').html('');
			
		}
	});
//-----------------------------------------------------------------------------------------------------------------------
	 $("#modificarParametro").submit(function(event){
		 event.preventDefault();
		 $(".alertaCombo").removeClass("alertaCombo");
			var error = false;
			
			if($("#nombreParametro").val()==""){
				error = true;
				$("#nombreParametro").addClass("alertaCombo");
			}
			if($("#anio").val()==""){
				error = true;
				$("#anio").addClass("alertaCombo");
			}
			if($("#periodo").val()==""){
				error = true;
				$("#periodo").addClass("alertaCombo");
			}
			if($("#numDias").val()==""){
				error = true;
				$("#numDias").addClass("alertaCombo");
			}
			if($("#fechaInicio").val()==""){
				error = true;
				$("#fechaInicio").addClass("alertaCombo");
			}
			if($("#fechaFin").val()==""){
				error = true;
				$("#fechaFin").addClass("alertaCombo");
			}
			if($("#periodo").val()=="Semestral"){
				
				if($("#semestre").val()==""){
					error = true;
					$("#semestre").addClass("alertaCombo");
				}
			}
			if($("#evaluacionArea").val()==""){
				error = true;
				$("#evaluacionArea").addClass("alertaCombo");
			}
			if($("#calculoResultado").val()==""){
				error = true;
				$("#calculoResultado").addClass("alertaCombo");
			}
			if($("#envioNotificacion").val()==""){
				error = true;
				$("#envioNotificacion").addClass("alertaCombo");
			}
			if($("#envioNotificacion").val()=="Si"){
				if($("#notificacion").val()=="0"){
					error = true;
					$("#notificacion").addClass("alertaCombo");
				}
			}
			if($("#envioNotificacion").val()=="No"){
				$("#notificacion").val(0);
			}
			
			if (error == false){
				$("#fechaFin").removeAttr('disabled','disabled');
				ejecutarJson($(this));
			}else{
				$("#estado").html("Todos los campos con ( * ) son obligatorios...!").addClass('alerta');
			}	
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



