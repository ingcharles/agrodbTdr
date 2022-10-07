<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');

$usuario = $_SESSION['usuario'];

$identficadorTecnico = $_SESSION['usuario'];


?>
<header>
	<h1>Nueva Trampa</h1>
</header>

	<div id="estado"></div>
	
	<form id='nuevoAdministracionDeTrampas' data-rutaAplicacion='administracionDeTrampas' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="codigoProgramacionEspecifica" id="codigoProgramacionEspecifica" />
		<input type="hidden" name="identficadorTecnico" id="identficadorTecnico" value="<?php echo $identficadorTecnico;?>" />
		<input type="hidden" name="opcion" id="opcion" />
		<fieldset id="datosGenerales">
			<legend>Datos Generales</legend>
			<div data-linea="1">
				<label>Nombre del área:</label>			
				<select id="areaTrampa" name="areaTrampa">
					<option value="">Seleccione...</option>
					<?php 
					$qAreaTrampa = $cc-> listarAreasTrampas($conexion);
					while ($areaTrampa = pg_fetch_assoc($qAreaTrampa)){
					    echo '<option value="' .$areaTrampa['id_area_trampa']. '" data-codigoprogramacion="' .$areaTrampa['codigo_programacion_especifica'] .'">'. $areaTrampa['nombre_area_trampa'] .'</option>';
					}
					?>
				</select>
			</div>
			<hr>
			<div data-linea="2" style="text-align: center;">
				<input type="radio" name="etapaTrampa" id="etapaTrampaNueva" value="nueva"> Trampa nueva
				<input type="radio" name="etapaTrampa" id="etapaTrampaAntigua" value="antigua">Trampa antigua
			</div>
			<hr>
			<div data-linea="3">
				<label>Fecha de instalación:</label>
				<input type="text" id="fechaInstalacion" name="fechaInstalacion" readonly="readonly"/>
			</div>
		</fieldset>
		
		<fieldset id="datosInstalacion">
		<legend>Datos Instalación</legend>
			<!-- div data-linea="3" id="resultadoCodigoTrampa">
				<label>Código de trampa:</label>
				<input type="text" name="codigoTrampa" id="codigoTrampa" readonly="readonly"/>
			</div-->
			<div data-linea="4">
				<label>Provincia:</label> 
				<select id="provincia" name="provincia">
					<option value="">Provincia....</option>
					<?php 
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
						foreach ($provincias as $provincia){
							echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
						}
					?>
				</select>
			</div>
			<div data-linea="5">
				<label>Cantón:</label> <select id="canton" name="canton"
					disabled="disabled">
					<option value="">Cantón....</option>
				</select>
			</div>
			<div data-linea="6">
				<label>Parroquia:</label> <select id="parroquia" name="parroquia"
					disabled="disabled">
					<option value="">Parroquia....</option>
				</select>
			</div>
			<hr>
			<div data-linea="7">
				<label>Georeferenciación UTM:</label>
			</div>
			<div data-linea="8">
				<label>X: </label><input type="text" id="coordenadaX" name="coordenadaX" maxlength="6">
			</div>
			<div data-linea="8">
				<label>Y: </label><input type="text" id="coordenadaY" name="coordenadaY" maxlength="8">
			</div>
			<div data-linea="8">
				<label>Z: </label><input type="text" id="coordenadaZ" name="coordenadaZ" maxlength="4">
			</div>
			<hr>
			<div data-linea="9">
				<label>Lugar de instalación: </label>
				<select id="lugarInstalacion" name="lugarInstalacion">
					<option value="">Seleccione...</option>
					<?php 
					$qLugarInstalacion = $cc-> listarLugarInstalacion($conexion);
					while ($lugarInstalacion = pg_fetch_assoc($qLugarInstalacion)){
					    echo '<option value="'.$lugarInstalacion['id_lugar_instalacion']. '">'. $lugarInstalacion['nombre_lugar_instalacion'] .'</option>';
					}
					?>
				</select>
			</div>
			<div data-linea="10">
				<label>Número de lugar de instalación: </label>
				<input type="text" id="numeroLugarInstalacion" name="numeroLugarInstalacion" maxlength="4">
			</div>			
		</fieldset>
		<fieldset  id="datosTrampa">
		<legend>Datos Trampa</legend>
			<div data-linea="11">
				<label>Plaga monitoreada: </label>
				<select id="plagaMonitoreada" name="plagaMonitoreada">
					<option value="">Seleccione...</option>
					<?php 
					$qPlagaMonitoreada = $cc-> listarPlagaMonitoreada($conexion);
					while ($plagaMonitoreada = pg_fetch_assoc($qPlagaMonitoreada)){
					    echo '<option value="'.$plagaMonitoreada['id_plaga']. '">'. $plagaMonitoreada['nombre_plaga'] .'</option>';
					}
					?>
				</select>
			</div>
			<div data-linea="12">
				<label>Tipo de trampa: </label>
				<select id="tipoTrampa" name="tipoTrampa">
					<option value="">Seleccione...</option>
					<?php 
					$qTipoTrampa = $cc-> listarTipoTrampa($conexion);
					while ($tipoTrampa = pg_fetch_assoc($qTipoTrampa)){
					    echo '<option value="'.$tipoTrampa['id_tipo_trampa']. '">'. $tipoTrampa['nombre_tipo_trampa'] .'</option>';
					}
					?>
				</select>
			</div>
			<div data-linea="13">
				<label>Tipo de atrayente: </label>
				<select id="tipoAtrayente" name="tipoAtrayente">
					<option value="">Seleccione...</option>
					<?php 
					$qTipoAtrayente = $cc-> listarTipoAtrayente($conexion);
					while ($tipoAtrayente = pg_fetch_assoc($qTipoAtrayente)){
					    echo '<option value="'.$tipoAtrayente['id_tipo_atrayente']. '">'. $tipoAtrayente['nombre_tipo_atrayente'] .'</option>';
					}
					?>
				</select>
			</div>
			<div data-linea="14">
				<label>Estado de la trampa: </label>
				<select id="estadoTrampa" name="estadoTrampa">
					<option value="">Seleccione...</option>
					<option value="activo">Activa</option>
					<option value="inactivo">Inactiva</option>
				</select>
			</div>
			<div data-linea="15">
				<label>Observación: </label>
				<input type="text" id="observacion" name="observacion">
			</div>
		</fieldset>
		<div>
			<button type="submit" class="guardar">Guardar</button>
		</div>
	
	</form>
	
<script type="text/javascript">			

    $(document).ready(function(){	

		distribuirLineas();
		construirValidador();

		$("#numeroLugarInstalacion").numeric();
		$("#coordenadaX").numeric();
		$("#coordenadaY").numeric();
		$("#coordenadaZ").numeric();
		
    });

    var array_canton= <?php echo json_encode($cantones); ?>;
	var array_parroquia= <?php echo json_encode($parroquias); ?>;

	$("#provincia").change(function(){
		scanton ='0';
		scanton = '<option value="">Cantón...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option data-latitud="'+array_canton[i]['latitud']+'"data-longitud="'+array_canton[i]['longitud']+'"data-zona="'+array_canton[i]['zona']+'" value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#canton').html(scanton);
	    $("#canton").removeAttr("disabled");
	  		
		});
    
    $("#canton").change(function(){
		sparroquia ='0';
		sparroquia = '<option value="">Parroquia...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			} 
	    }
	    $('#parroquia').html(sparroquia);
		$("#parroquia").removeAttr("disabled");
	});

    $("#fechaInstalacion").datepicker({ 
    	 changeMonth: true,
    	 changeYear: true,
    	 dateFormat: 'yy-mm-dd'         
    }).datepicker('setDate', 'today');
    
	$("input[name=etapaTrampa]").change(function(){

		if($('input:radio[name=etapaTrampa]:checked').val()=="nueva"){
			
			$('#fechaInstalacion').datepicker('option', 'minDate', 0); 
	    	$('#fechaInstalacion').datepicker('option', 'maxDate', 0); 

		}else if($('input:radio[name=etapaTrampa]:checked').val()=="antigua"){
			
			$('#fechaInstalacion').datepicker('option', 'minDate', null); 
	    	$('#fechaInstalacion').datepicker('option', 'maxDate',null); 
		}
    
    });




    
    $("#areaTrampa").change(function(event){

    	$("#codigoProgramacionEspecifica").val($("#areaTrampa option:selected").attr('data-codigoprogramacion'));
    	
		/*if($("#areaTrampa").val()=="1"){
			$("#numeroLugarInstalacion").prop('disabled', true);
		}else{
			$("#numeroLugarInstalacion").prop('disabled', false);
		}*/			
    	//$('#nuevoAdministracionDeTrampas').attr('data-opcion','generarCodigoTrampa');
		//$('#nuevoAdministracionDeTrampas').attr('data-destino','resultadoCodigoTrampa');
		$('#opcion').val($("#areaTrampa option:selected").val());	

		//abrir($("#nuevoAdministracionDeTrampas"),event,false);	

    });


    
    $("#nuevoAdministracionDeTrampas").submit(function(){

  	 	event.preventDefault();
  	    $(".alertaCombo").removeClass("alertaCombo");
  	  	var error = false;

    	if($("#areaTrampa").val()==""){
			error = true;
			$("#areaTrampa").addClass("alertaCombo");
		}

		if($('input:radio[name=etapaTrampa]:checked').length==""){
			error = true;
			$("#etapaTrampaNueva").addClass("alertaCombo");
			$("#etapaTrampaAntigua").addClass("alertaCombo");
		}
		
    	if($("#fechaInstalacion").val()==""){
			error = true;
			$("#fechaInstalacion").addClass("alertaCombo");
		}

    	if($("#provincia").val()==""){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}

    	if($("#canton").val()==""){
			error = true;
			$("#canton").addClass("alertaCombo");
		}

    	if($("#parroquia").val()==""){
			error = true;
			$("#parroquia").addClass("alertaCombo");
		}

    	if($("#coordenadaX").val()==""){
			error = true;
			$("#coordenadaX").addClass("alertaCombo");
		}

    	if($("#coordenadaY").val()==""){
			error = true;
			$("#coordenadaY").addClass("alertaCombo");
		}

    	if($("#coordenadaZ").val()==""){
			error = true;
			$("#coordenadaZ").addClass("alertaCombo");
		}

    	if($("#lugarInstalacion").val()==""){
			error = true;
			$("#lugarInstalacion").addClass("alertaCombo");
		}

    	//if($("#areaTrampa").val()!="1"){    	
	    	if($("#numeroLugarInstalacion").val()==""){
				error = true;
				$("#numeroLugarInstalacion").addClass("alertaCombo");
			}
    	//}

    	if($("#plagaMonitoreada").val()==""){
			error = true;
			$("#plagaMonitoreada").addClass("alertaCombo");
		}

    	if($("#tipoTrampa").val()==""){
			error = true;
			$("#tipoTrampa").addClass("alertaCombo");
		}

    	if($("#tipoAtrayente").val()==""){
			error = true;
			$("#tipoAtrayente").addClass("alertaCombo");
		}

    	if($("#estadoTrampa").val()==""){
			error = true;
			$("#estadoTrampa").addClass("alertaCombo");
		}

    	if($("#observacion").val()==""){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}else{
				 $('#nuevoAdministracionDeTrampas').attr('data-opcion','guardarNuevoAdministracionDeTrampas');
				ejecutarJson($(this));                             
			}
    });

 

</script>