<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAreas.php';

//poner en todos los combos style="width:100%"

$conexion = new Conexion();
$ce = new ControladorCatastro();
$cc = new ControladorCatalogos();
$ca = new ControladorAreas();

$regimenLaboral =$cc->obtenerRegimenLaboral($conexion);
$grupoOcupacional =$ce->obtenerGrupoOcupacional($conexion);
$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$oficinas = $cc->listarSitiosLocalizacion($conexion,'SITIOS');
$qPresupuesto = $ce->obtenerDatosPresupuesto($conexion);

while ($fila = pg_fetch_assoc($qPresupuesto)){
		$presupuesto[] = array(nombre=>$fila['nombre'], partidaPresupuestaria=> $fila['partida_presupuestaria'],fuente=>$fila['fuente'], regimenLaboral=>$fila['regimen_laboral']);
}

$identificador=$_SESSION['usuario_seleccionado'];

?>
<header>
	<h1>Datos Contrato</h1>
</header>

<form id="datosContrato" data-rutaAplicacion="uath" data-opcion="guardarContrato" data-accionEnExito="#ventanaAplicacion #filtrar">
	<input type="hidden" id="fecha_valida" name="fecha_valida" />
		
	<div id="estado"></div>
	
	<div class="pestania">
	
	<fieldset>
		<legend>Datos personales</legend>
		<div data-linea="1">
		<label>Cédula</label> 
				<input type="text" id="identificadorEmpleado" name="identificadorEmpleado" value="<?php echo $contrato['identificador']?>"/>
		</div>
		<div data-linea="1">
				<button id="buscarIdentificador" type="button">Buscar</button>
			</div>
		
		<div id="datosPersonalesEmpleado"></div>
		
	</fieldset>
	</div>
	
	<div class="pestania">
	<table class="soloImpresion">
		<tr>
			<td>
				<fieldset>
					<legend>Contrato</legend>
					<div data-linea="1">
						<label>Regimen Laboral</label> 
							<select name="regimen_laboral" id="regimen_laboral" style=" width:100%">
								<option value="">Seleccione....</option>
								<?php 	
									while($regimen = pg_fetch_assoc($regimenLaboral)){
										echo '<option value="' . $regimen['id_regimen_laboral'] . '">' . $regimen['nombre'] . '</option>';
									}
								?>
						</select>
						
						<input type="hidden" id="nombreRegimenLaboral" name="nombreRegimenLaboral" />
					</div>
					
					<div data-linea="2"  id="dModalidadContrato"></div>
					
					<div data-linea="3" id="dPresupuesto"></div>
					<div data-linea="4">
						<label>Rol</label> 
							<select name="rol" id="rol" style=" width:100%">
								<option value="">Seleccione....</option>
								<option value="Dirección">Dirección</option>
								<option value="Ejecución y Coordinación de procesos">Ejecución y Coordinación de procesos</option>
								<option value="Ejecución y Supervisión de procesos">Ejecución y Supervisión de procesos</option>
								<option value="Ejecución de procesos">Ejecución de procesos</option>
								<option value="Ejecución de procesos de apoyo y tecnológico">Ejecución de procesos de apoyo y tecnológico</option>
								<option value="Apoyo administrativo">Apoyo administrativo</option>
								
						</select>
						
						<input type="hidden" id="nombreRol" name="nombreRol" />
					</div>
					<div data-linea="5">
						<label>Información del puesto</label> 
							<input type="text" name="informacion_puesto" id="informacion_puesto" maxlength=128/>
					</div>
					<div data-linea="6">
						<label>Se acoge a la opción pluriempleo ART 12 LOSEP</label> 
							<input type="checkbox" name="pluriempleo" id="pluriempleo" value="Si"/>
					</div>
					<div data-linea="7">
						<label>Fecha de ingreso al Sector Público</label> 
							<input type="text" name="fecha_ingreso_sector_publico" id="fecha_ingreso_sector_publico" readonly/>
					</div>
					<hr />

					
					<div data-linea="8">
						<label>N° Contrato/Acción de Personal</label> 
							<input type="text" name="numero_contrato" id="numero_contrato" data-er="^[UATH0-9 -\/]+$"/>
					</div>
					
					<div data-linea="9">
						<label>Fecha Inicio</label> 
							<input type="text" id="fecha_inicio" name="fecha_inicio" />
					</div>

					<div data-linea="9">
						<label>Fecha Finalización</label> 
							<input type="text" id="fecha_fin" name="fecha_fin" disabled="disabled" />
					</div>
					<hr />
					
					<div data-linea="10">
						<label>Provincia</label>
								<select id="provincia" name="provincia" >
									<option value="">Provincia....</option>
										<?php 	
											$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
											foreach ($provincias as $provincia){
												echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
											}
										?>
								</select> 
								
						
						<input type="hidden" id="nombreProvincia" name="nombreProvincia" />
					</div>

					<div data-linea="10">
							<label id='lCanton'>Cantón</label>
							<select id="canton" name="canton" disabled="disabled">
							</select>
							
							<input type="hidden" id="nombreCanton" name="nombreCanton" />
					</div>
					
										
					<div data-linea="11">
							<label id='lOficina'>Oficina</label>
							<select id="oficina" name="oficina" disabled="disabled">
							</select>
						
						<input type="hidden" id="nombreOficina" name="nombreOficina" />
					</div>
					
					<div data-linea="32" id="dCoordinacion"></div>
					
					<div data-linea="33" id="dDireccionOficina"></div>
					
						<div data-linea="34" id="dGestionUnidad"></div>
				
					
					
						<div data-linea="35" id="dPuesto"></div>

					
						<div data-linea="36" id="dGrupoOcupacional"></div>
					
					
					<div data-linea="37">
						<label id='lRemuneracion'>Remuneración</label> 
							<input type="text" id="remuneracion" name="remuneracion" readonly="readonly"/>
					</div>
					
					<div data-linea="37">
						<label id='lGrado'>Grado</label> 
							<input type="text" id="grado" name="grado" readonly="readonly"/>
					</div>
					<hr />
					
					<div data-linea="24">
						<label>Provincia</label>
								<select id="provinciaNotaria" name="provinciaNotaria" >
									<option value="">Provincia....</option>
										<?php 	
											$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
											foreach ($provincias as $provincia){
												echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
											}
										?>
								</select> 
								
						
						<input type="hidden" id="nombreProvinciaNotaria" name="nombreProvinciaNotaria" />
					</div>

					<div data-linea="24">
							<label id='lCantonNotaria'>Cantón</label>
							<select id="cantonNotaria" name="cantonNotaria" disabled="disabled">
							</select>
							
							<input type="hidden" id="nombreCantonNotaria" name="nombreCantonNotaria" />
					</div>
					<div data-linea="17">
						<label>N° de notaria</label> 
							<input type="text" name="numero_notaria" id='numero_notaria' placeholder="Ej. 9999" data-inputmask="'mask': '9[99999]'" pattern="[0-9]{1,5}"  title="999999" />
					</div>
					
					<div data-linea="17">
						<label>Fecha Declaración</label> 
							<input type="text" id="fecha_declaracion" name="fecha_declaracion" />
					</div>
					
					<div data-linea="14">
						<label>Lugar</label> 
							<input type="text" id="lugar_notaria" name="lugar_notaria" />
					</div>
					
					<div data-linea="15">
						<label>Estado</label> 
							<select name="condicion"  id="condicion">
								<option value="">Seleccione un estado....</option>
								<option value="1">Vigente</option>
								<option value="2">Caducado</option>
								<option value="3">Finalizado</option> 
								<option value="4">Inactivo</option> 
							</select>
					</div>
					<div data-linea="16">
						<label>Impedimentos</label> 
							<input type="text" name="impedimento" maxlength=512 />
					</div>	
					<div data-linea="17">
						<label>Observación</label> 
							<input type="text" name="observacion" />
					</div>	
					<div data-linea="23">
					<label>Archivo Contrato</label> 
						<!-- input type="file" name="archivo_contrato" id='archivo_contrato' accept="application/msword | application/pdf | image/*" /-->
						
						<input type="hidden"  class="rutaArchivo" name="archivo" value="0" /> 
						<input type="file" class="archivo" name="informe" accept="application/msword | application/pdf | image/*"/>
						<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
						<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/uath/archivosContratos" >Subir archivo</button> 
					</div>

				</fieldset>
					<button id="guardarForm" type="button">Guardar</button>
				</td>
		</tr>
	</table>
	</div>
</form>

<script type="text/javascript">

	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_oficina= <?php echo json_encode($oficinas); ?>;
	var array_presupuesto= <?php echo json_encode($presupuesto); ?>;
	var tipoContrato='';
	$("#tipo_contrato").change(function(){
		cargarValorDefecto("presupuesto", "");
	});

	$("#regimen_laboral").change(function(event){
		$('#nombreRegimenLaboral').val($("#regimen_laboral option:selected").text());

		$("#datosContrato").attr('data-opcion', 'combosContrato');
	    $("#datosContrato").attr('data-destino', 'dModalidadContrato');
	    abrir($("#datosContrato"), event, false); //Se ejecuta ajax       
	});

	$("#rol").change(function(event){
		$('#nombreRol').val($("#rol option:selected").text());
	});

	$("#presupuesto").change(function(event){
		 $("#fuente").val( $("#presupuesto option:selected").attr("data-fuente"));
		if($('#regimen_laboral').val()=='Sujetos LOSEP Nivel Jerárquico Superior Nombramiento' ||
			$('#regimen_laboral').val()=='Sujetos LOSEP Nombramiento' || $('#tipo_contrato').val()=='Contratos Indefinidos'){
			$('#partida_individual').show();
			$('#etiqueta_partida_individual').show();
			
		}else{
			$('#partida_individual').hide();
			$('#etiqueta_partida_individual').hide();
		}
		$("#fecha_fin").attr("disabled","disabled");
		$("#fecha_fin").val('');
		$("#fecha_inicio").val('');
        
	});
	
	$('select[name="tipoDocumento"]').find('option[value="<?php echo $fila['tipo_documento'];?>"]').prop("selected","selected");
    
	 $("#provincia").change(function(){

		 $('#nombreProvincia').val($("#provincia option:selected").text());
		 
	    	scanton ='0';
	    	scanton = '<option value="">Canton...</option>';
		    for(var i=0;i<array_canton.length;i++){
			    if ($("#provincia").val()==array_canton[i]['padre']){
			    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
				    }
		   		}
		    $('#canton').html(scanton);
		    $("#canton").removeAttr("disabled");
		    $("#lCanton").show();
		    $("#canton").show();
		});

	    $("#canton").change(function(){

	    $('#nombreCanton').val($("#canton option:selected").text());
			soficina ='0';
			soficina = '<option value="">Oficina...</option>';
		    for(var i=0;i<array_oficina.length;i++){
			    if ($("#canton").val()==array_oficina[i]['padre']){
			    	soficina += '<option value="'+array_oficina[i]['codigo']+'">'+array_oficina[i]['nombre']+'</option>';
				    } 
		    	}
		    $('#oficina').html(soficina);
			$("#oficina").removeAttr("disabled");
			$("#lOficina").show();
			$("#oficina").show();
		});

	    $("#provinciaNotaria").change(function(){

			 $('#nombreProvinciaNotaria').val($("#provinciaNotaria option:selected").text());
			 
		    	scanton ='0';
		    	scanton = '<option value="">Canton...</option>';
			    for(var i=0;i<array_canton.length;i++){
				    if ($("#provinciaNotaria").val()==array_canton[i]['padre']){
				    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
					    }
			   		}
			    $('#cantonNotaria').html(scanton);
			    $("#cantonNotaria").removeAttr("disabled");
			    $("#lCantonNotaria").show();
			    $("#cantonNotaria").show();
			});

		    $("#cantonNotaria").change(function(){

		    $('#nombreCantonNotaria').val($("#cantonNotaria option:selected").text());
				
			});

    $("#oficina").change(function(event){
	    	$('#nombreOficina').val($("#oficina option:selected").text());

	    	$('#dCoordinacion').html('');
	    	$('#dDireccionOficina').html('');
	    	$('#dGestionUnidad').html('');
	    	$('#dPuesto').html('');
	    	$('#dGrupoOcupacional').html('');
	    	$("#lRemuneracion").hide();
	    	$("#remuneracion").hide();
	    	$("#lGrado").hide();
	    	$("#grado").hide();

			$("#datosContrato").attr('data-opcion', 'combosCoordinacion');
		    $("#datosContrato").attr('data-destino', 'dCoordinacion');
		    abrir($("#datosContrato"), event, false); //Se ejecuta ajax 
		});


	$("#datosContrato").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if($("#regimen_laboral").val()==""){
			error = true;
			$("#regimen_laboral").addClass("alertaCombo");
		}
		if($("#partida_presupuestaria").val()==""){
			error = true;
			$("#partida_presupuestaria").addClass("alertaCombo");
		}
		if($("#puesto_institucional").val()==""){
			error = true;
			$("#puesto_institucional").addClass("alertaCombo");
		}
		
		if($("#fecha_ingreso_sector_publico").val()==""){
			error = true;
			$("#fecha_ingreso_sector_publico").addClass("alertaCombo");
		}
		if($("#remuneracion").val()==""){
			error = true;
			$("#remuneracion").addClass("alertaCombo");
		}
		if($("#grado").val()==""){
			error = true;
			$("#grado").addClass("alertaCombo");
		}
		if($("#presupuesto").val()==""){
			error = true;
			$("#presupuesto").addClass("alertaCombo");
		}
		if($("#fuente").val()==""){
			error = true;
			$("#fuente").addClass("alertaCombo");
		}
		if($("#regimen_laboral option:selected").val()==1 || $("#regimen_laboral option:selected").val()==3){
			if($("#partida_individual").val()==""){
				error = true;
				$("#partida_individual").addClass("alertaCombo");
			}
		}
		if($("#numero_contrato").val()=="" || !esCampoValido("#numero_contrato")){
			error = true;
			$("#numero_contrato").addClass("alertaCombo");
		}
		if($("#fecha_inicio").val()==""){
			error = true;
			$("#fecha_inicio").addClass("alertaCombo");
		}
		if($("#grupo_ocupacional").val()==""){
			error = true;
			$("#grupo_ocupacional").addClass("alertaCombo");
		}
		if($("#provincia").val()==""){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}
		if($("#canton").val()==""){
			error = true;
			$("#canton").addClass("alertaCombo");
		}
		if($("#oficina").val()==""){
			error = true;
			$("#oficina").addClass("alertaCombo");
		}
		if($("#direccion").val()==""){
			error = true;
			$("#direccion").addClass("alertaCombo");
		}
		if($("#coordinacion").val()==""){
			error = true;
			$("#coordinacion").addClass("alertaCombo");
		}
		if($("#provinciaNotaria").val()==""){
			error = true;
			$("#provinciaNotaria").addClass("alertaCombo");
		}
		if($("#cantonNotaria").val()==""){
			error = true;
			$("#cantonNotaria").addClass("alertaCombo");
		}
		if($("#condicion").val()==""){
			error = true;
			$("#condicion").addClass("alertaCombo");
		}
		if(($("#condicion").val()=="3")&&($("#fecha_salida").val()=="")){
			error = true;
			$("#fecha_salida").addClass("alertaCombo");
		}
		if(($("#condicion").val()=="3")&&($("#terminacion_laboral").val()=="")){
			error = true;
			$("#terminacion_laboral").addClass("alertaCombo");
		}

		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}
});

	 
	$('button.subirArchivo').click(function (event) {	
		
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

        	if($("#fecha_inicio").val() !=""){
	        	
        		subirArchivo(
    	                archivo
    	                , $("#numero_contrato").val()+'_'+$("#fecha_inicio").val().replace(/[_\W]+/g, "-")
    	                , boton.attr("data-rutaCarga")
    	                , rutaArchivo
    	                , new carga(estado, archivo, boton)
    	            );
	            
			}else{
				 alert("Debe seleccionar una fecha de ingreso!");
				 archivo.val("");
			} 
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });
  
	$(document).ready(function(){

		$('#lPartidaIndividual').hide();
		$('#partida_individual').hide();
		
		$("#lCanton").hide();
		$("#canton").hide();
		$("#lCantonNotaria").hide();
		$("#cantonNotaria").hide();
		$("#lOficina").hide();
		$("#oficina").hide();
		$('#lCoordinacion').hide();
		$('#coordinacion').hide();

		$("#lRemuneracion").hide();
    	$("#remuneracion").hide();
    	$("#lGrado").hide();
    	$("#grado").hide();
    	
		//$("#numero_contrato").ForceNumericOnly();
		$("#remuneracion").val( $("#grupo_ocupacional option:selected").attr("data-remuneracion"));
		$("#grado").val( $("#grupo_ocupacional option:selected").attr("data-grado"));
			    
		$( "#fecha_fin" ).datepicker({
			changeMonth: true,
		      changeYear: true
		    });
	    
		 $( "#fecha_declaracion" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		
		construirValidador();
		
		
		distribuirLineas();
		construirAnimacion($(".pestania"));
		$('.bsig').attr("disabled","disabled");		
		
	});

	$('#fecha_inicio').removeClass('hasDatepicker');
	
	$("#fecha_ingreso_sector_publico").datepicker({
		changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    maxDate: 0
	  });
		
	$("#fecha_inicio" ).datepicker({
	      changeMonth: true,
	      changeYear: true,
	      onSelect: function(dateText, inst) {
	    	  
	    	  var fin_anio="31/12/";

	    	  if($("#nombreModalidadContrato").val() == 'Nombramiento Definitivo'){
              	fin_anio=fin_anio.concat(String((new Date()).getFullYear()+10));
	    	  }else{
	    		  fin_anio=fin_anio.concat(String((new Date()).getFullYear()));
	    	  }
	    	  
              diciembre=fin_anio.split("/");
              $("#estado").html('');
              
              var dateDiciembre=new Date(diciembre[2],(diciembre[1]-1),diciembre[0]);
              var fecha=new Date($('#fecha_inicio').datepicker('getDate'));

  			if($('select[name="regimen_laboral"] option:selected').attr("value")=="Sujetos LOSEP Contratos" && $('select[name="tipo_contrato"] option:selected').attr("value")=="Contrato Ocasionales"
				&& ($('select[name="presupuesto"] option:selected').attr("value").indexOf("Presupuesto general")==0)){	
	    	  fecha.setDate(fecha.getDate()-parseInt($('#dias').val()));
	    	  fecha.setMonth(fecha.getMonth()-parseInt($('#meses').val()));
	    	  fecha.setUTCFullYear(fecha.getUTCFullYear()+(2-parseInt($('#anios').val())));                              
              
              if(fecha>=dateDiciembre)
              {
            	  $('input#fecha_fin').datepicker("setDate", dateDiciembre);    
            	  $('input#fecha_fin').datepicker('option', 'maxDate', dateDiciembre);       
              }
              else{
            	  $('input#fecha_fin').datepicker("setDate", fecha);    
            	  $('input#fecha_fin').datepicker('option', 'maxDate', fecha);
                  }
               $('input#fecha_fin').removeAttr("disabled");
			}
		    else if($('select[name="tipo_contrato"] option:selected').attr("value")=="Nombramiento Provisional Prueba"){			    	  
		    	  fecha.setDate(fecha.getDate());
		    	  fecha.setMonth(fecha.getMonth()+3);
		    	  fecha.setUTCFullYear(fecha.getUTCFullYear());  
		    	  $('input#fecha_fin').datepicker("setDate", fecha); 	
			      $('input#fecha_fin').datepicker('option', 'maxDate', fecha);
			      $('input#fecha_fin').removeAttr("disabled");
			}
		    else{
		    	$("input#fecha_fin").datepicker( "option", "changeMonth", true );
		    	$("input#fecha_fin").datepicker( "option", "changeYear", true );
		    	$('input#fecha_fin').removeAttr("disabled");
		    	$('input#fecha_fin').datepicker("setDate", dateDiciembre);    
          	    $('input#fecha_fin').datepicker('option', 'maxDate', dateDiciembre);
		     }

			 fecha1=$("#fecha_inicio").val().split("/");
			 fecha2=$("#fecha_fin").val().split("/");
			 var fecha1Date=new Date(fecha1[2],(fecha1[1]-1),fecha1[0]);
	         var fecha2Date=new Date(fecha2[2],(fecha2[1]-1),fecha2[0]);
	         var diferencia= (fecha2Date-fecha1Date)/(1000*60*60*24);
	         $("#guardarForm").removeAttr("disabled");
			if((diferencia<31) && (fecha<dateDiciembre)&&(String(fecha).indexOf(String(fecha1Date)))){
	         $("#fecha_valida").val('NO');
	         alert('El servidor excede con el número máximo de días de contrato permitido.');
	         $("#estado").html('El servidor excede con el número máximo de días de contrato permitido.').addClass("alerta");
	         $("#guardarForm").attr("disabled","disabled");    
	          }
			else if(diferencia>31){
		    	 $("#fecha_valida").val('SI');
			} 
			else{
				$("#fecha_valida").val('SI');
			}
	   }
  });

	 $("#buscarIdentificador").click(function (event) {
		 $("#estado").html("");

		 if($("#identificadorEmpleado").val()!=""){
		 	event.preventDefault();
		 	
			$("#datosContrato").attr('data-opcion', 'inputsPersonales');
		    $("#datosContrato").attr('data-destino', 'datosPersonalesEmpleado');
		    $("#datosContrato").removeAttr('data-accionEnExito');
		    
		    abrir($("#datosContrato"), event, false);

		    if($("#apellidoEmpleado").val()!="")
		    	construirAnimacion($(".pestania"));
	        else
	        	$('.bsig').attr("disabled","disabled");
		 }else{
			 $("#estado").html('Debe ingresar un número de cédula para proceder.').addClass("alerta");
		 }
	});

	 $("#guardarForm").click(function (event) {
		 event.preventDefault();
         if($("#fecha_valido").val()=="NO"){
        	 $("#estado").html('El servidor excede con el número máximo de días de contrato permitido.').addClass("alerta"); 	
         }
         else{
			$("#datosContrato").attr('data-opcion', 'guardarContrato');
			$("#datosContrato").attr('data-accionEnExito','#ventanaAplicacion #filtrar');
  			$("#datosContrato").submit();
  			
  			
         }
	});

</script>
