<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAreas.php';

$id_datos_contrato=$_POST['id'];

$conexion = new Conexion();
$cc = new ControladorCatastro();
$ce = new ControladorCatalogos();
$ca = new ControladorAreas();

$res = $cc->obtenerDatosContrato($conexion, $id_datos_contrato);
$contrato = pg_fetch_assoc($res);

$regimenLaboral =$ce->obtenerRegimenLaboral($conexion);


$qCanton = $ce->obtenerIdLocalizacion($conexion, $contrato['canton'], 'CANTONES');
$qCantonNotaria = $ce->obtenerIdLocalizacion($conexion, $contrato['canton_notaria'], 'CANTONES');
$canton = pg_fetch_assoc($qCanton);
$cantonNotaria = pg_fetch_assoc($qCantonNotaria);
$grupoOcupacional =$cc->obtenerGrupoOcupacional($conexion);
$cantones= $ce->listarSitiosLocalizacion($conexion,'CANTONES');
$oficinas = $ce->listarSitiosLocalizacion($conexion,'SITIOS');

$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central')", "(1,3)");

$puesto = $cc -> obtenerDatosPuesto($conexion);
$qPresupuesto = $cc->obtenerDatosPresupuesto($conexion);

while ($fila = pg_fetch_assoc($qPresupuesto)){
	$presupuesto[] = array(nombre=>$fila['nombre'], partidaPresupuestaria=> $fila['partida_presupuestaria'],fuente=>$fila['fuente'], regimenLaboral=>$fila['regimen_laboral']);
}



?>
<header>
	<h1>Datos Contrato</h1>
</header>

<form id="datosContrato" data-rutaAplicacion="uath" data-opcion="actualizarContrato" data-accionEnExito="#ventanaAplicacion #filtrar">
	<input type="hidden" id="id_datos_contrato" name="id_datos_contrato" value="<?php echo $id_datos_contrato?>" />
	<input type="hidden" id="idGestion" name="idGestion" value="<?php echo $contrato['id_gestion']?>" />
	


	<div id="mostrarBotones">
		<p>
			<button id="modificar" type="button" class="editar">Editar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</p>
	</div>
	
	<div id="estado"></div>
	<table class="soloImpresion">
	<tr><td>
	<fieldset>
		<legend>Datos personales</legend>
		<div data-linea="1">
		<label>Identificador</label> 
				<input type="text" id="identificador" name="identificador" readonly="readonly" value="<?php echo $contrato['identificador']?>"/>
		</div>
		<div data-linea="2">
		<label>Apellido</label> 
				<input type="text" id="apellido" name="apellido" readonly="readonly" value="<?php echo $contrato['apellido']?>"/>
		</div>
		<div data-linea="2">
		<label>Nombre</label> 
				<input type="text" id="nombre" name="nombre" readonly="readonly" value="<?php echo $contrato['nombre']?>"/>
		</div>
	</fieldset>
	</td><td>

	<fieldset>
		<legend>Contrato</legend>
		<div data-linea="1">
			 <label>Regimen Laboral</label> 
				<select name="regimen_laboral" id="regimen_laboral" style=" width:100%" disabled="disabled" >
					<option value="">Seleccione....</option>
					<?php 	
						while($regimen = pg_fetch_assoc($regimenLaboral)){
							if($regimen['nombre'] == $contrato[regimen_laboral]){
								echo '<option value="' . $regimen['id_regimen_laboral'] . '" selected="selected">' . $regimen['nombre'] . '</option>';
							}else{
								echo '<option value="' . $regimen['id_regimen_laboral'] . '">' . $regimen['nombre'] . '</option>';
							}
						}
					?>
				</select>
				
				<input type="hidden" id="nombreRegimenLaboral" name="nombreRegimenLaboral" value="<?php echo $contrato['regimen_laboral']?>"/>
		</div>
		
		<div data-linea="2" id="dModalidadContrato">
			<label>Modalidad Contrato</label> 
				<select name="tipo_contrato"  id="tipo_contrato" disabled="disabled" >
				</select>
				
				<input type="hidden" id="nombreModalidadContrato" name="nombreModalidadContrato" value="<?php echo $contrato['tipo_contrato']?>"/>
		</div>
		
		
		<hr id="separacion"/>

		
		<div data-linea="3" id="dPresupuesto">
			<label>Presupuesto</label>
				<select name="presupuesto" id="presupuesto" disabled="disabled">
				</select>
		</div>
		
		<div data-linea="44"  id="dFuente">
			<label>Fuente</label> 
				<input type="text" id="fuente" name="fuente" readonly="readonly" value="<?php echo $contrato['fuente']?>"/>
		</div>
		
		<div data-linea="45"  id="dPartida">
			<label id="lPartidaIndividual">Partida individual</label> 
				<input type="text" id="partida_individual" name="partida_individual" value="<?php echo $contrato['partida_individual']?>" disabled="disabled"/>
		</div>
		<div data-linea="46">
						<label>Rol</label> 
							<select name="rol" id="rol" style=" width:100%" disabled="disabled">
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
		<div data-linea="47">
			<label>Información del puesto</label> 
				<input type="text" name="informacion_puesto" id="informacion_puesto" maxlength=128 value="<?php echo $contrato['informacion_puesto']?>" disabled="disabled"/>
		</div>
		<div data-linea="48">
			<label>Se acoge a la opción pluriempleo ART 12 LOSEP</label> 
				<input type="checkbox" name="pluriempleo" id="pluriempleo" value="Si" disabled="disabled"/>
		</div>
		<div data-linea="49">
			<label>Fecha de ingreso al Sector Público</label> 
				<input type="text" name="fecha_ingreso_sector_publico" id="fecha_ingreso_sector_publico" readonly value="<?php echo $contrato['fecha_ingreso_sector_publico']?>" disabled="disabled"/>
		</div>
	
		<hr />
		

		<div data-linea="8">
			<label>N° Contrato/Acción de Personal</label> 
				<input type="text" name="numero_contrato" id="numero_contrato" disabled="disabled" value="<?php echo $contrato['numero_contrato']?>" data-er="^[UATH0-9 -\/]+$"/>
		</div>
	
		
		<div data-linea="9">		
			<label>Inicio nombramiento/contrato</label>
				<input type="text"	id="fecha_inicio" name="fecha_inicio" value="<?php echo date('Y-m-d',strtotime($contrato['fecha_inicio']));?>" required="required"  disabled="disabled" readonly/>
		</div>
		
		<div data-linea="9">
			<label>Fin nombramiento/contrato</label>
				<input type="text"	id="fecha_fin" name="fecha_fin"	 value="<?php echo $contrato['fecha_fin']==""?"":date('Y-m-d',strtotime($contrato['fecha_fin']));?>" disabled="disabled"  readonly/>
		</div>
		
		<hr />
		
		<div data-linea="10">
			<label>Provincia</label>
					<select id="provincia" name="provincia" disabled="disabled">
						<option value="">Provincia....</option>
							<?php 
								
								$provincias = $ce->listarSitiosLocalizacion($conexion,'PROVINCIAS');
								foreach ($provincias as $provincia){
									if($provincia['nombre'] == $contrato['provincia']){
										echo '<option value="' . $provincia['codigo'] . '" selected="selected">' . $provincia['nombre'] . '</option>';
									}else{
										echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
									}
								}
							?>
					</select> 
								
				<input type="hidden" id="nombreProvincia" name="nombreProvincia" />
				


			</div><div data-linea="10">
	
				<label id='lCanton'>Cantón</label>
					<select id="canton" name="canton" disabled="disabled" >
					</select>
				<input type="hidden" id="nombreCanton" name="nombreCanton" />


		
			</div>
			
			<div data-linea="11">
		
				<label>Oficina</label>
					<select id="oficina" name="oficina" disabled="disabled">
					</select>
				<input type="hidden" id="nombreOficina" name="nombreOficina" />


					
			</div>
			
			<div data-linea="32" id="dCoordinacion">
				<label id='lCoordinacion'>Coordinación</label> 
					<select id="coordinacion" name="coordinacion" disabled="disabled">
						<option value="" >Seleccione...</option>
						<?php 
							while($fila = pg_fetch_assoc($area)){
								if($fila['nombre'] == $contrato['coordinacion']){
									echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" selected="selected">' . $fila['nombre'] . '</option>';
								}else{
									echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" >' . $fila['nombre'] . '</option>';
								}
							}
						?>
					</select>
					
					<input type="hidden" id="nombreCoordinacion" name="nombreCoordinacion" value="<?php echo $contrato['coordinacion'];?>"/>
			</div>
			
			<div data-linea="33"  id="dDireccionOficina">
				<label id='lDireccion'>Dirección - Oficina Técnica</label> 
					<select id="direccion" name="direccion" disabled="disabled" style="width:100%">
					</select>
					
					<input type="hidden" id="nombreDireccion" name="nombreDireccion" value="<?php echo $contrato['direccion'];?>"/>
			</div>

			
			
			<div data-linea="34" id="dGestionUnidad">
				<label id='lGestion'>Gestión - Unidad</label> 
					<select id="gestion" name="gestion" disabled="disabled" style="width:100%">
					</select>
					
					<input type="hidden" id="nombreGestion" name="nombreGestion" value="<?php echo $contrato['gestion'];?>"/>

			</div>
		
			<hr id='divisor'/>
			<div data-linea="35" id="dPuesto">
				<label id='lPuesto'>Puesto institucional</label> 
					<select id="puesto_institucional" name="puesto_institucional" disabled="disabled" style="width:100%">
					</select>
			</div>

		
			<div data-linea="36" id="dGrupoOcupacional">
				<label id='lGrupoOcupacional'>Grupo ocupacional</label> 
					<select id="grupo_ocupacional" name="grupo_ocupacional" disabled="disabled" style="width:100%">
					</select>
			</div>
	
		<div data-linea="37" id="dRemuneracion">
			<label id='lRemuneracion'>Remuneración</label> 
				<input type="text" id="remuneracion" name="remuneracion" value="<?php echo $contrato['remuneracion']?>" readonly="readonly"/>
		</div>
		
		<div data-linea="37">
			<label id='lGrado'>Grado</label> 
				<input type="text" id="grado" name="grado" value="<?php echo $contrato['grado']?>" readonly="readonly"/>
		</div>
		
		<hr id="separador"/>
		<div data-linea="24">
			<label>Provincia</label>
					<select id="provinciaNotaria" name="provinciaNotaria" disabled="disabled">
						<option value="">Provincia....</option>
							<?php 	
							$provincias = $ce->listarSitiosLocalizacion($conexion,'PROVINCIAS');
							foreach ($provincias as $provincia){
							    if($provincia['nombre'] == $contrato['provincia_notaria']){
							        echo '<option value="' . $provincia['codigo'] . '" selected="selected">' . $provincia['nombre'] . '</option>';
							    }else{
							        echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
							    }
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
		<div data-linea = "13">
			<label>N° notaria</label>
				<input type="text" name="numero_notaria" id='numero_notaria' value="<?php echo $contrato['numero_notaria'] ?>" disabled="disabled" />
		</div>
		

		<div data-linea = "13">
			<label>Fecha Declaración</label>
				<input type="text"	id="fecha_declaracion" name="fecha_declaracion" value="<?php echo $contrato['fecha_declaracion']==""?"":date('Y-m-d',strtotime($contrato['fecha_declaracion'])); ?>"  disabled="disabled" readonly/>
		</div>
		
		<div data-linea = "14">
			<label>Lugar</label>
				<input type="text" name="lugar_notaria" value="<?php echo $contrato['lugar_notaria'] ?>" disabled="disabled"/>
		</div>
		<hr/>
		<div data-linea="15">
			<label>Estado</label> 
				<select name="condicion"  id="condicion" disabled="disabled">
					<option value="">Seleccione un estado....</option>
					<option value="1">Vigente</option>
					<option value="2">Caducado</option>
					<option value="3">Finalizado</option> 
					<option value="4">Inactivo</option> 
				</select>
		</div>
		
		<div data-linea="16">
			<label id="etiqueta_terminacion_laboral">Terminación laboral</label> 
				<select name="terminacion_laboral"  id="terminacion_laboral" disabled="disabled">
					<option value="">Seleccione un motivo....</option>
					<option value="Cumplimiento del plazo">Cumplimiento del plazo</option>
					<option value="Renuncia Voluntaria">Renuncia Voluntaria</option>
					<option value="Supresion de Puesto">Supresión de Puesto</option>
					<option value="Compra de Renuncia">Compra de Renuncia</option>
					<option value="Muerte">Muerte</option>
					<option value="Destitución">Destitución</option>
					<option value="Calificacion irregular o insuficiente">Calificación irregular o insuficiente</option>
					<option value="Finalizar nombramiento provisional">Finalizar nombramiento provisional</option>
					<option value="Terminación unilateral de contrato">Terminación unilateral de contrato</option> 
				</select>
		</div>
		<div data-linea="16">
			<label id="etiqueta_fecha_salida">Fecha Salida</label>
			<input type="text"	id="fecha_salida" name="fecha_salida" value="<?php echo $contrato['fecha_salida']==""?"":date('Y-m-d',strtotime($contrato['fecha_salida']));?>" disabled="disabled" readonly/>
		</div>
		<div data-linea="17">
						<label>Impedimentos</label> 
							<input type="text" name="impedimento" maxlength=512 value="<?php echo $contrato['impedimento'] ?>" disabled="disabled"/>
					</div>
		<div data-linea = "18">
			<label>Observación</label>
				<input type="text" name="observacion"  value="<?php echo $contrato['observacion'] ?>" disabled="disabled"/>
		</div>
		<div data-linea = "19">
			<label id="etiqueta_calificacion">Calificación</label>
				<input type="text" name="calificacion" id="calificacion"  value="<?php echo $contrato['nota'] ?>" disabled="disabled"/>
		</div>
		<div data-linea = "19">
			<label id="etiqueta_escala_calificacion">Escala calificación</label>
				<input type="text" name="escala_calificacion" id="escala_calificacion"  value="<?php echo $contrato['escala_calificacion'] ?>" disabled="disabled"/>
		</div>
		<div data-linea = "20">
			<label>Archivo Contrato</label>
				<?php echo $contrato['archivo_contrato']=='0'? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$contrato['archivo_contrato'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
		</div>	
		
		<div data-linea = "21">
			<!--input type="file" name="archivo_contrato" id='archivo_contrato' disabled="disabled"/-->
			<input type="file" class="archivo" name="informe" accept="application/pdf"/>
			<input type="hidden"  class="rutaArchivo" name="archivo" value="<?php echo $contrato['archivo_contrato'];?>"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/uath/archivosContratos" >Subir archivo</button>
		</div>		
		
	</fieldset>
	</td></tr></table>
</form>

<script type="text/javascript">

	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_oficina= <?php echo json_encode($oficinas); ?>;
	var array_presupuesto= <?php echo json_encode($presupuesto); ?>;
	var regimenLaboral= <?php echo json_encode($contrato['regimen_laboral']); ?>;
	var pluriempleo= <?php echo json_encode($contrato['pluriempleo']); ?>;

	$( "#gestion" ).change(function() {
		$("#idGestion").val($('#gestion option:selected').val());
	});
	
	$( "#calificacion" ).change(function() {

		if(parseFloat($( "#calificacion" ).val())>=91.0){
			$( "#escala_calificacion" ).val("Excelente");
		}else if((parseFloat($( "#calificacion" ).val())>=81.0)){
			$( "#escala_calificacion" ).val("Muy bueno");
		}else if(parseFloat($( "#calificacion" ).val())>=71.0){
			$( "#escala_calificacion" ).val("Satisfactorio");
		}else if(parseFloat($( "#calificacion" ).val())>=61.0){
			$( "#escala_calificacion" ).val("Deficiente");
		}else {
			$( "#escala_calificacion" ).val("Inaceptable");
		}
		
	});

	$("#condicion").change(function(){
        
        var texto=$("#condicion option:selected").text();
        if( texto.localeCompare("Finalizado")==0){
        	$("#terminacion_laboral").show();
			$("#etiqueta_terminacion_laboral").show();
			$("#etiqueta_fecha_salida").show();
			$("#fecha_salida").show();
			if($("#tipo_contrato").val().localeCompare("Nombramiento Provisional Prueba")==0){
		      	$("#etiqueta_calificacion").show();
				$("#etiqueta_escala_calificacion").show();
				$("#calificacion").show();
				$("#escala_calificacion").show();
			} 
		}else{
			$("#terminacion_laboral").hide();
			$("#calificacion").hide();
			$("#escala_calificacion").hide();
			$("#etiqueta_terminacion_laboral").hide();
			$("#etiqueta_calificacion").hide();
			$("#etiqueta_escala_calificacion").hide();
			$("#etiqueta_fecha_salida").hide();
			$("#fecha_salida").hide();
		}              
    });
	
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
		    $("#oficina").attr("disabled","disabled");

		    $('#dCoordinacion').html('');
	    	$('#dDireccionOficina').html('');
	    	$('#dGestionUnidad').html('');
	    	$('#dPuesto').html('');
	    	$('#dGrupoOcupacional').html('');
	    	$("#lRemuneracion").hide();
	    	$("#remuneracion").hide();
	    	$("#lGrado").hide();
	    	$("#grado").hide();
	    	$("#separador").hide();
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
		});
	 $("#cantonNotaria").change(function(){
			
	    	$('#nombreCantonNotaria').val($("#cantonNotaria option:selected").text());
	 });
	
	    $("#canton").change(function(){
	
	    	$('#nombreCanton').val($("#canton option:selected").text());


			soficina ='0';
			soficina = '<option value="">Sitio...</option>';

		    for(var i=0;i<array_oficina.length;i++){
			    if ($("#canton").val()==array_oficina[i]['padre']){
			    	soficina += '<option value="'+array_oficina[i]['codigo']+'">'+array_oficina[i]['nombre']+'</option>';
			    } 
		    }
		    
		    $('#oficina').html(soficina);
			$("#oficina").removeAttr("disabled");
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
		
	    $('#coordinacion').change(function(event){
			$("#nombreCoordinacion").val($('#coordinacion option:selected').text());
					
			$("#datosContrato").attr('data-opcion', 'combosDireccion');
		    $("#datosContrato").attr('data-destino', 'dDireccionOficina');
		    abrir($("#datosContrato"), event, false); //Se ejecuta ajax
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

	$("#grupo_ocupacional").change(function(event){
		 $("#remuneracion").val( $("#grupo_ocupacional option:selected").attr("data-remuneracion"));
		 $("#grado").val( $("#grupo_ocupacional option:selected").attr("data-grado"));
		 $("#remuneracion").removeAttr("readonly");
	});

	$("#regimen_laboral").change(function(event){
		$('#nombreRegimenLaboral').val($("#regimen_laboral option:selected").text());

		$("#datosContrato").attr('data-opcion', 'combosContrato');
	    $("#datosContrato").attr('data-destino', 'dModalidadContrato');
	    abrir($("#datosContrato"), event, false); //Se ejecuta ajax       
	});
	
	$("#datosContrato").submit(function(event){
		$("#datosContrato").attr('data-opcion', 'actualizarContrato');
		$("#datosContrato").attr('data-accionEnExito','#ventanaAplicacion #filtrar');

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
		
		if($("#fecha_ingreso_sector_publico").val()==""){
			error = true;
			$("#fecha_ingreso_sector_publico").addClass("alertaCombo");
		}
		
		if($("#regimen_laboral option:selected").val()==3){
			if($("#partida_individual").val()==""){
				error = true;
				$("#partida_individual").addClass("alertaCombo");
			}
		}
		
		if($("#numero_contrato").val()=="" || !esCampoValido("#numero_contrato")){
			error = true;
			$("#numero_contrato").addClass("alertaCombo");
		}
		if($("#grupo_ocupacional").val()==""){
			error = true;
			$("#grupo_ocupacional").addClass("alertaCombo");
		}
		if($("#fecha_inicio").val()==""){
			error = true;
			$("#fecha_inicio").addClass("alertaCombo");
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
  
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("textarea").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
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
				  alert('Ingrese una fecha de inicio y fecha de finalización');
				  archivo.val("");
			  }           
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });
	

	$('#fecha_inicio').removeClass('hasDatepicker');

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
        var dateDiciembre=new Date(diciembre[2],(diciembre[1]-1),diciembre[0]);
		   if($("#regimen_laboral").val()=="Sujetos LOSEP Contratos" && $("#tipo_contrato").val()=="Contrato Ocasionales"
				&& ($("#presupuesto").val().indexOf("Presupuesto general")==0)){	
	    	  var fecha=new Date($('#fecha_inicio').datepicker('getDate'));
	    	  fecha.setDate(fecha.getDate()-parseInt($('#dias').val()));
	    	  fecha.setMonth(fecha.getMonth()-parseInt($('#meses').val()));
	    	  fecha.setUTCFullYear(fecha.getUTCFullYear()+(2-parseInt($('#anios').val())));  
            if(fecha>=dateDiciembre){
        	  	$('input#fecha_fin').datepicker("setDate", dateDiciembre);    
        	  	$('input#fecha_fin').datepicker('option', 'maxDate', dateDiciembre);     
            }else{
        	  	$('input#fecha_fin').datepicker("setDate", fecha);    
        	  	$('input#fecha_fin').datepicker('option', 'maxDate', fecha);
              }
           $('input#fecha_fin').removeAttr("disabled");
		      
			}
		    else if($("#tipo_contrato").val()=="Nombramiento Provisional Prueba"){	
		    	  var fecha=new Date($('#fecha_inicio').datepicker('getDate'));
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
	   }
});
	

	$(document).ready(function(){

		if(regimenLaboral != '20 Grados Losep Nombramiento'){
			$("#dPartida").hide();
		}
		
		$( "#fecha_inicio" ).datepicker({
			dateFormat: 'yy-mm-dd',
		      changeMonth: true,
		      changeYear: true
		    });
		$( "#fecha_ingreso_sector_publico" ).datepicker({
			dateFormat: 'yy-mm-dd',
		      changeMonth: true,
		      changeYear: true
		    });
		$( "#fecha_fin" ).datepicker({
			dateFormat: 'yy-mm-dd',
		      changeMonth: true,
		      changeYear: true
		    });
		$( "#fecha_salida" ).datepicker({
			dateFormat: 'yy-mm-dd',
		      changeMonth: true,
		      changeYear: true
		    });
	    
	    $( "#fecha_declaracion" ).datepicker({
	    	  dateFormat: 'yy-mm-dd',
		      changeMonth: true,
		      changeYear: true
		    });

		construirValidador();
		distribuirLineas();
		//cargarValorDefecto("presupuesto","<?php echo $contrato['presupuesto']?>");
		cargarValorDefecto("fuente","<?php echo $contrato['fuente']?>");
		cargarValorDefecto("condicion","<?php echo $contrato['estado']?>");
		cargarValorDefecto("terminacion_laboral","<?php echo $contrato['motivo_terminacion_laboral']?>");
		cargarValorDefecto("rol","<?php echo $contrato['rol']?>");
		

		$('<option value="<?php echo $contrato['tipo_contrato'];?>"><?php echo $contrato['tipo_contrato'];?></option>').appendTo('#tipo_contrato');
		$('<option value="<?php echo $canton['id_localizacion'];?>"><?php echo $canton['nombre'];?></option>').appendTo('#canton');
		$('<option value="<?php echo $cantonNotaria['id_localizacion'];?>"><?php echo $cantonNotaria['nombre'];?></option>').appendTo('#cantonNotaria');
		$('<option value="<?php echo $contrato['id_oficina'];?>"><?php echo $contrato['oficina'];?></option>').appendTo('#oficina');
		$('<option value="<?php echo $contrato['direccion'];?>"><?php echo $contrato['direccion'];?></option>').appendTo('#direccion');
		$('<option value="<?php echo $contrato['gestion'];?>"><?php echo $contrato['gestion'];?></option>').appendTo('#gestion');
		$('<option value="<?php echo $contrato['nombre_puesto'];?>"><?php echo $contrato['nombre_puesto'];?></option>').appendTo('#puesto_institucional');
		$('<option value="<?php echo $contrato['presupuesto'].' - '.$contrato['partida_presupuestaria'];?>"><?php echo $contrato['presupuesto'].' - '.$contrato['partida_presupuestaria'];?></option>').appendTo('#presupuesto');
		$('<option value="<?php echo $contrato['grupo_ocupacional'];?>"><?php echo $contrato['grupo_ocupacional'];?></option>').appendTo('#grupo_ocupacional');
				
		 $('#nombreProvincia').val($("#provincia option:selected").text());
		 $('#nombreCanton').val($("#canton option:selected").text());
		 $('#nombreOficina').val($("#oficina option:selected").text());
		 $('#nombreProvinciaNotaria').val($("#provinciaNotaria option:selected").text());
		 $('#nombreCantonNotaria').val($("#cantonNotaria option:selected").text());
		 $('#nombreRol').val($("#rol option:selected").text());
		


			
			$('form').keypress(function(e){   
			    if(e == 13){
			      return false;
			    }
			});

			  $('input').keypress(function(e){
			    if(e.which == 13){
			      return false;
			    }
			  });
			  $("#terminacion_laboral").hide();
			  $("#etiqueta_terminacion_laboral").hide();
			  $("#fecha_salida").hide();
			  $("#etiqueta_fecha_salida").hide();
			  	
              if($('select[name="condicion"] option:selected').attr("value").localeCompare("3")==0){
			  	$("#terminacion_laboral").show();
			  	$("#etiqueta_terminacion_laboral").show(); 	
			  	$("#fecha_salida").show();
			  	$("#etiqueta_fecha_salida").show();
			  		if($("#tipo_contrato").val().localeCompare("Nombramiento Provisional Prueba")==0){
			      	$("#etiqueta_calificacion").show();
					$("#etiqueta_escala_calificacion").show();
					$("#calificacion").show();
					$("#escala_calificacion").show();
			  }
			  }
 			  else{
				  $("#calificacion").hide();
				  $("#escala_calificacion").hide();
				  $("#etiqueta_calificacion").hide();
				  $("#etiqueta_escala_calificacion").hide();
			  }

          	cargarValorDefecto("tipo_contrato","<?php echo $contrato['tipo_contrato']?>");

          	if(pluriempleo == 'Si'){
				$("#pluriempleo").prop('checked',true);
            }
			  
	});

	$('#tipo_contrato').change(function(event){
		 $("#calificacion").hide();
		  $("#escala_calificacion").hide();
		  $("#etiqueta_calificacion").hide();
		  $("#etiqueta_escala_calificacion").hide();
		if($('select[name="condicion"] option:selected').attr("value").localeCompare("3")==0){
		  	$("#terminacion_laboral").show();
		  	$("#etiqueta_terminacion_laboral").show(); 	
		  	$("#fecha_salida").show();
		  	$("#etiqueta_fecha_salida").show();
		  		if($("#tipo_contrato").val().localeCompare("Nombramiento Provisional Prueba")==0){
		      	$("#etiqueta_calificacion").show();
				$("#etiqueta_escala_calificacion").show();
				$("#calificacion").show();
				$("#escala_calificacion").show();
		        }
		  }
			  		  
	});

	$("#rol").change(function(event){
		$('#nombreRol').val($("#rol option:selected").text());
	});
		
</script>

