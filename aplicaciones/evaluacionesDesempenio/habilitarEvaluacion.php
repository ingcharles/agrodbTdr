<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
require_once '../../aplicaciones/uath/models/salidas.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();


?>
<header>
	<h1>Habilitar evaluación</h1>
</header>

<?php 
	if(!empty($_POST['elementos']) and is_numeric($_POST['elementos']) )
		{
			$resultadoParametros = pg_fetch_assoc($ced->listaParametros($conexion, 'ABIERTOS',$_POST['elementos']));
			$consultaEva= pg_fetch_result($ced->devolverEvaluacionVigente ($conexion,'',$_POST['elementos'],'' ),0,'vigencia');

?>
<form id="activarEvaluacion" 
	  data-rutaAplicacion="evaluacionesDesempenio" 
	  data-opcion="activarEvaluacion" 
	  data-accionEnExito="ACTUALIZAR">
	  <div id="estado"></div>
	  
	  <input type="hidden" name="codParametro" id="codParametro" value="<?php echo $_POST['elementos'];?>" />
	  
	<fieldset>	
		<legend>Servidor</legend>		
			<div data-linea="1">
				<label><strong>* Servidor:</strong></label>	 
				<input type="text"
					id="identificadorUsuario" name="identificadorUsuario"
					onchange="buscarDatos(id);  return false;" maxlength="10" />
			</div>
			
	</fieldset>
	<div id="resultadoBusqueda">
	<fieldset>	
	<legend>Información</legend>
			<div data-linea="2">
				<label><strong>Nombre:</strong></label>	 
				<label for="nombre"></label> 
			</div>
			<div data-linea="2">
				<label><strong>Apellido: </strong></label>	 
				<label for="apellido"></label>
			</div>
			<hr color="black" size=1 width="100%">
			<div data-linea="3">
				<label><strong>Provincia: </strong></label>	 
				<label for="provincia"></label>
			</div>
			<div data-linea="3">
				<label><strong>Cantón: </strong></label>	 
				<label for="canton"></label>
			</div>
			<div data-linea="4">
				<label><strong>Oficina: </strong></label>	 
				<label for="oficina"></label>
			</div>
			<hr color="black" size=1 width="100%">
			<div data-linea="5">
				<label><strong>Coordinación: </strong></label>	 
				<label for="coordinacion"></label> 
			</div>
			<div data-linea="6">
				<label><strong>Dirección: </strong></label>	 
				<label for="direccion"></label> 
			</div>
			<div data-linea="7">
				<label><strong>Gestión: </strong></label>	 
				<label for="gestion"></label>
			</div>
			<hr color="black" size=1 width="100%">
			<div data-linea="8">
				<label><strong>Responsable: </strong></label>
				<label for="responsable"></label>	 
			</div>
					
	</fieldset>
	<fieldset>	
		<legend>Evaluaciones Pendientes</legend>		
			<div data-linea="1" style="vertical-align:'middle';">
				<label><strong>Funcionarios a Cargo: </strong></label>
				<label for="superior1"></label>
			</div>
			<div data-linea="1">
				<label for="superior"></label>
			</div>
			<hr color="black" size=1 width="100%">
			<div data-linea="2">
				<label><strong>Jefe Directo: </strong></label>	
				<label for="inferior1"></label> 
			</div>	
			<div data-linea="2">
				<label for="inferior"></label>
			</div>	
			<hr color="black" size=1 width="100%">
			<div data-linea="3" >
				<label><strong>Pares: </strong></label>	
				<label for="pares1"></label>
			</div>
			<div data-linea="3" >
				<label for="pares"></label>
			</div>
			<hr color="black" size=1 width="100%">
			<div data-linea="4" >
				<label><strong>Autoevaluación: </strong></label>
			</div>
			<div data-linea="4" >
				<label for="autoevaluacion"></label>	
			</div>
			<hr color="black" size=1 width="100%">
			<div data-linea="5" >
				<label><strong>Individual: </strong></label>	
				<label for="individual1"></label>
			</div>	
			<div data-linea="5" >
				<label for="individual"></label>
			</div>
	</fieldset>
	<fieldset>	
		<legend>Tiempo de reapertura</legend>		
			<div data-linea="1">
				<label>* Fecha inicio: </label>	
				<input type="text"
					id="fechaInicio" name="fechaInicio" value="" readonly />
			</div>	
			<div data-linea="1">
				<label>* Fecha fin: </label>	
				<input type="text"
					id="fechaFin" name="fechaFin" value="" readonly />
			</div>	
			<div data-linea="3">
				<label>* Motivo: </label>	
				<select style='width:100%' name="motivo" id="motivo" >
					<option value="" >Seleccione...</option>
					<?php
						$area = array('Vacaciones servidor',);										
						for ($i=0; $i<sizeof($area); $i++){
							echo '<option value="'.$area[$i].'">'. $area[$i] . '</option>';
						}		   					
					?>
				</select>
			</div>		
			<div data-linea="6">
				<label>Observación: </label>	
				<input type="text"
					id="observacion" name="observacion" value=""  /> 
			</div>	
	</fieldset>
	<fieldset>	
	<legend>Notificaciones</legend>		
			<div data-linea="1">
				<label>* Envío de notificaciones: </label>
				<select style='width:100%' name="envioNotificacion" id="envioNotificacion" >
					<option value="" >Seleccione...</option>
					<option value="Si" >Si</option>
					<option value="No" >No</option>
					
				</select>	 
			</div>	
			<div data-linea="1">
				<label>* Notificaciones: </label>
				<select style='width:100%' name="notificacion" id="notificacion" >
					<option value="" >Seleccione...</option>
					<option value="1" >Aviso reapertura de evaluación</option>
				</select>	 
			</div>	
	</fieldset>
	<p>
		<button id="actualizarRes" type="submit" class="guardar" >Habilitar</button>
	</p>
	</div>
</form>
<?php }else{ 
    $mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Debe seleccionar una evaluación...!!';
	mensajesSalidas($mensaje);
 }?>

<script type="text/javascript">

//------------------------------------------------------------------------------------------------------------
	$(document).ready(function(){
		 $("#numDias").numeric();
		 $("#actualizarRes").attr('disabled','disabled'); 
		 $("#resultadoBusqueda").hide();
		 
		distribuirLineas();
		construirValidador();
		
	});
//--------------------------------------------------------------------------------------------------------------------	
	$("#fechaInicio").datepicker({
		changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    minDate: 0, 
	    onSelect: function(dateText, inst) {
	    	$('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio").val());
	    	    	
	    }
	    
	  });
//---------------------------------------------------------------------------------------------------------------------
	$('#fechaFin').datepicker({ 
	        changeMonth: true,
		    changeYear: true,
		    dateFormat: 'yy-mm-dd'
	   });

//----------------------------------------------------------------------------------------------------------------------
	 $("#activarEvaluacion").submit(function(event){
		 event.preventDefault();
		 $(".alertaCombo").removeClass("alertaCombo");
			var error = false;
			
			if($("#servidor").val()==""){
				error = true;
				$("#servidor").addClass("alertaCombo");
			}
			if($("#motivo").val()==""){
				error = true;
				$("#motivo").addClass("alertaCombo");
			}
			if($("#fechaInicio").val()==""){
				error = true;
				$("#fechaInicio").addClass("alertaCombo");
			}
			if($("#fechaFin").val()==""){
				error = true;
				$("#fechaFin").addClass("alertaCombo");
			}
			
			if($("#envioNotificacion").val()==""){
				error = true;
				$("#envioNotificacion").addClass("alertaCombo");
			}
			if($("#notificacion").val()==""){
				error = true;
				$("#notificacion").addClass("alertaCombo");
			}
			
			if (error == false){
				$("#fechaFin").removeAttr('disabled','disabled');
				ejecutarJson($(this));
			}else{
				$("#estado").html("Todos los campos con ( * ) son obligatorios...!").addClass('alerta');
			}	
	 });
//--------------------------------------------------------------------------------------------------------------------
	 function buscarDatos(id){
		 	limpiar();
	        var valor = $('#identificadorUsuario').val();
	        var idEva = $('#codParametro').val();
	        $('#estado').html('').addClass("alerta");
	        if(valor.length>=9){
	           var consulta = $.ajax({
	              type:'POST',
	              url:'aplicaciones/evaluacionesDesempenio/buscarDatosEvaluacion.php',
	              data:{identificador:valor, idEvaluacion:idEva},
	              dataType:'JSON'
	           });
	           consulta.done(function(data){
	              if(data.error!==undefined){
	            	  $('#estado').html(data.error).addClass("alerta");
	            	  limpiar();
	            	  $("#resultadoBusqueda").hide();
	                 return false;
	              } else {
		              var bande=0;
	            	 if(data.nombre!==undefined){ $("label[for='nombre']").text(data.nombre);}
	            	 if(data.apellido!==undefined){ $("label[for='apellido']").text(data.apellido);}
		             if(data.provincia!==undefined){$("label[for='provincia']").text(data.provincia);}
	                 if(data.canton!==undefined){$("label[for='canton']").text(data.canton);}
	                 if(data.oficina!==undefined){$("label[for='oficina']").text(data.oficina);}
	                 if(data.coordinacion!==undefined){$("label[for='coordinacion']").text(data.coordinacion);}
	                 if(data.direccion!==undefined){$("label[for='direccion']").text(data.direccion);}
	                 if(data.gestion!==undefined){$("label[for='gestion']").text(data.gestion);}
	                 if(data.responsable!==undefined){$("label[for='responsable']").append(data.responsable);}
	                 if(data.superior!==undefined){$("label[for='superior']").append(data.superior); bande=1;}
	                 if(data.superior1!==undefined){$("label[for='superior1']").append(data.superior1);}
	                 if(data.inferior!==undefined){$("label[for='inferior']").append(data.inferior); bande=1;}
	                 if(data.inferior1!==undefined){$("label[for='inferior1']").append(data.inferior1);}
	                 if(data.pares!==undefined){$("label[for='pares']").append(data.pares); bande=1;}
	                 if(data.pares1!==undefined){$("label[for='pares1']").append(data.pares1);}
	                 if(data.autoevaluacion!==undefined){$("label[for='autoevaluacion']").append(data.autoevaluacion); bande=1;}
	                 if(data.individual!==undefined){$("label[for='individual']").append(data.individual); bande=1;}
	                 if(data.individual1!==undefined){$("label[for='individual1']").append(data.individual1);}
	                 
	                 $("#resultadoBusqueda").show();
	                 distribuirLineas();
	                 if(bande){
		                 $("#actualizarRes").removeAttr('disabled','disabled');
		                 
	                 }
	                 if(data.existeEvaluacion==0){
	                	 $('#estado').html('No dispone de  evaluaciones pendientes ó estan activas..!!').addClass("alerta");
		                 $("#resultadoBusqueda").hide();
	                 }
	                 
	                 return true;
	              }
	           });
	           consulta.fail(function(){
	              $('#estado').html('No existe ningun registro..!!').addClass("alerta");
	              $("#actualizarRes").attr('disabled','disabled');
	              $("#resultadoBusqueda").hide();
	              return false;
	           });     
	        } else {
	           $('#estado').html('La longitud debe ser mayor a 9 caracteres...').addClass("alerta");
	           $("#actualizarRes").attr('disabled','disabled');
	           $("#resultadoBusqueda").hide();
	           return false;
	        }
	        
	 }     
//------------------------------------------------------------------------------------------------------------------
	  function limpiar(){
          $("label[for='nombre']").text('');
          $("label[for='apellido']").text('');
          $("label[for='provincia']").text('');
          $("label[for='canton']").text('');
          $("label[for='oficina']").text('');
          $("label[for='coordinacion']").text('');
          $("label[for='direccion']").text('');
          $("label[for='gestion']").text('');
          $("label[for='responsable']").text('');
          $("label[for='superior']").text('');
          $("label[for='inferior']").text('');
          $("label[for='pares']").text('');
          $("label[for='autoevaluacion']").text('');
          $("label[for='individual']").text('');
          $("label[for='superior1']").text('');
          $("label[for='inferior1']").text('');
          $("label[for='pares1']").text('');
          $("label[for='individual1']").text('');
          $("#actualizarRes").attr('disabled','disabled');
	 }
//--------------------------------------------------------------------------------------------------------------------
</script>