<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cm = new ControladorMovilizacionAnimal();
$ppc = new ControladorVacunacionAnimal();
$cantones = $cm->listarLocalizacionLugarEmision($conexion,'CANTONES');
$parroquias = $cm->listarLocalizacionLugarEmision($conexion,'PARROQUIAS');
$coordinaciones = $cm->listarLocalizacionLugarEmision($conexion,'SITIOS');
$sitios = $ppc->listaSitioEmpresas($conexion, $_SESSION['usuario']);



?>
<header>
	<h1>Nuevo emisor de movilización</h1>
</header>
	<form id='nuevoResponsableMovilizacion' data-rutaAplicacion='movilizacionAnimal' data-opcion='guardarResponsableMovilizacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="usuario_responsable" name="usuario_responsable" value="<?php echo $_SESSION['usuario'];?>" />
	<input type="hidden" id="opcion" name="opcion" value="0">	
	<input type="hidden" id="nombre_emisor" name="nombre_emisor" />	
	<input type="hidden" id="id_provincia" name="id_provincia" />
	<input type="hidden" id="nombre_provincia" name="nombre_provincia" />
	<input type="hidden" id="id_canton" name="id_canton" />
	<input type="hidden" id="nombre_canton" name="nombre_canton" />
	<input type="hidden" id="id_parroquia" name="id_parroquia" />
	<input type="hidden" id="nombre_parroquia" name="nombre_parroquia" />
	<input type="hidden" id="lugar_emision" name="lugar_emision" />
	<input type="hidden" id="identificador_autoservicio" name="identificador_autoservicio" />
	
	<div id="estado"></div>
		<fieldset id="seleccionarEmisor">
			<legend>Seleccionar emisor autorizado de movilización</legend>
				<div data-linea="1">
					<label>* Persona autorizada para emitir certificados de movilización animal</label>								
			    </div>
			    <div data-linea="2">
					<label>Tipo emisor</label>				
					<select id="tipoEmisor" name="tipoEmisor">
						<option value="0">Seleccione...</option>
						<?php 
							$responsable = $cm-> listaTipoResponsablesMovilizacionAnimal($conexion);
							while ($fila = pg_fetch_assoc($responsable)){
					    		echo '<option value="' . $fila['id_tipo_lugar_emision'] . '">' . $fila['nombre_lugar_emision'] . '</option>';
					    	}
						?>
					</select>
					<input type="hidden" id="nombreTipoEmisor" name="nombreTipoEmisor" />	 
			    </div>
			    <div data-linea="2">
					<label>Buscar por :</label> 
					<select id="tipoBusqueda" name="tipoBusqueda">
						<option value="0">Seleccionar...</option>
						<option value="1">Identificacion</option>	
						<option value="2">Nombre del emisor</option>						
					</select>
				</div>
				<div data-linea="3">					
					<input type="text" id="responsableMovilizacion" name="responsableMovilizacion" />
				</div>
				<div data-linea="3">
					<button type="button" id="btn_responsable_movilizacion" name="btn_responsable_movilizacion">Buscar responsable movilización</button>
				</div>							
				<div id="res_responsable" data-linea="4"></div>
	    </fieldset>
        <fieldset id="funcionario_emisor">
			<legend>Seleccionar emisor autorizado de movilización</legend>			 
				<div data-linea="1">
					<label>Provincia</label>
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
				<div data-linea="2">
					<label>Cantón</label>
					<select id="canton" name="canton" disabled="disabled">
					</select>
				</div>				
				<div data-linea="3">	
					<label>Parroquia</label>
					<select id="parroquia" name="parroquia" disabled="disabled">
					</select>
				</div>
				<div data-linea="4">	
					<label>Coordinación</label>
					<select id="coordinacion" name="coordinacion" disabled="disabled">
					</select>
				</div>														   		   			   			   		   			   						   		   			   			   		   			   		
		</fieldset>	
		<fieldset id="otro_emisor">
			<legend>Seleccionar emisor autorizado de movilización</legend>			 
				<div data-linea="1">
					<label>Provincia</label>
					<input type="text" id="provincia2" name="provincia2" disabled="disabled" /> 
				</div>
				<div data-linea="2">
					<label>Cantón</label>
					<input type="text" id="canton2" name="canton2" disabled="disabled" /> 
				</div>				
				<div data-linea="3">	
					<label>Parroquia</label>
					<input type="text" id="parroquia2" name="parroquia2" disabled="disabled" />					
				</div>				
				<div data-linea="4">
					<label>Lugar emisión</label> 
					<input type="text" id="lugarEmision" name="lugarEmision"/>					
				</div>
				<div data-linea="5">
					<label>Sitio administración</label> 					
					<select id="cmbSitio" name="cmbSitio" disabled="disabled"> 
				    </select>	
				</div>											   		   			   			   		   			   						   		   			   			   		   			   		
		</fieldset>	
		<button id="btn_guardar" type="button" name="btn_guardar">Guardar emisor movilización</button>

  </form>

<script type="text/javascript">			
var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;
var array_coordinacion= <?php echo json_encode($coordinaciones); ?>;
var array_Sitios = <?php echo json_encode($sitios); ?>;

    $(document).ready(function(){			
		distribuirLineas();	
		$("#funcionario_emisor").hide();
		$("#otro_emisor").hide();	
	});

    $("#especie").change(function(){
    	if ($("#especie").val() != 0){
			$("#nombreEspecie").val($('#especie option:selected').text());				
		}
	});
   
    $("#provincia").change(function(){
    	if ($("#provincia").val() != 0){
			$("#nombre_provincia").val($('#provincia option:selected').text());				
		}
	});

    $("#canton").change(function(){
    	if ($("#canton").val() != 0){
			$("#nombre_canton").val($('#canton option:selected').text());				
		}
	});

    $("#parroquia").change(function(){
    	if ($("#parroquia").val() != 0){
			$("#nombre_parroquia").val($('#parroquia option:selected').text());				
		}
	});

    $("#coordinacion").change(function(){
    	if ($("#coordinacion").val() != 0){
			$("#lugar_emision").val($('#coordinacion option:selected').text());				
		}
	});

    $("#provincia").change(function(){
    	scanton ='0';
		scanton = '<option value="0">Cantón...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#canton').html(scanton);
	    $("#canton").removeAttr("disabled");
	});

    $("#canton").change(function(){
		sparroquia ='0';
		sparroquia = '<option value="0">Parroquia...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}  
	    $('#parroquia').html(sparroquia);
		$("#parroquia").removeAttr("disabled");

		scoordinacion ='0';
		scoordinacion = '<option value="0">Coordinación...</option>';
	    for(var i=0;i<array_coordinacion.length;i++){
		    if ($("#canton").val()==array_coordinacion[i]['padre']){
		    	scoordinacion += '<option value="'+array_coordinacion[i]['codigo']+'">'+array_coordinacion[i]['nombre']+'</option>';
			    } 
	    	}  
	    $('#coordinacion').html(scoordinacion);
		$("#coordinacion").removeAttr("disabled");
	});

    //eventos de los botones
    $("#btn_responsable_movilizacion").click(function(event){
		 event.preventDefault();
		 $('#nuevoResponsableMovilizacion').attr('data-opcion','guardarResponsableMovilizacion');
		 $('#nuevoResponsableMovilizacion').attr('data-destino','res_responsable');
	     $('#opcion').val('1');		     	
		 abrir($("#nuevoResponsableMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio			 		 			 		 	
	}); 

    $("#btn_guardar").click(function(event){
		 event.preventDefault();
		 $('#nuevoResponsableMovilizacion').attr('data-opcion','guardarResponsableMovilizacion');
		 $('#nuevoResponsableMovilizacion').attr('data-destino','res_guardar');
	     $('#opcion').val('10');		     	
		 abrir($("#nuevoResponsableMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio
		 abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);			 		 			 		 	
	});
	
	function chequearCamposGuardar(form){
		$("#estado").html("").addClass('correcto');
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 
        
		if(!$.trim($("#especie").val())){
			error = true;
			$("#especie").addClass("alertaCombo");
		}		
		
		if (!error){
			return true;		
		}else{			
			$("#estado").html("Por favor revise el formato de la información ingresada").addClass('alerta');
			return false;
		}
		
	}
	
</script>