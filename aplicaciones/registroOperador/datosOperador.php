<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorRegistroOperador.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();
$ccert = new ControladorCertificados();
$cr = new ControladorRegistroOperador();

if (strlen($_POST['id'])<10){
	$identificador = $_SESSION['usuario'];
}else{
	$identificador = $_POST['id'];
}

$res = $cr->buscarOperador($conexion, $identificador);
$operador = pg_fetch_assoc($res);

$datosFacturacion = pg_fetch_assoc($ccert -> listaComprador($conexion,$identificador));


$cantonesT = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquiasT = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
?>

<header>
	<h1>Datos del Operador</h1>
</header>

<form id="datosPersonales" data-rutaAplicacion="registroOperador" data-opcion="actualizarDatosOperador">
	<input type="hidden" value="<?php echo $operador['identificador'];?>" id="identificador" name="identificador" />
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	<div id="estado"></div>
	<table class="soloImpresion">
	<tr><td>
	<fieldset>
		<legend>Información general</legend>
			<div data-linea="1">
				<label for="razon" class="opcional">Razón social</label> 
					<input value="<?php echo $operador['razon_social'];?>" name="razon" type="text" id="razon" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			
			<div data-linea="2">
				<label for="nombreLegal">Representante legal</label> 
					<input value="<?php echo $operador['nombre_representante'];?>" name="nombreLegal" type="text" id="nombreLegal" placeholder="Nombres" maxlength="200" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/>
			</div>
			<div data-linea="2"> 
					<input value="<?php echo $operador['apellido_representante'];?>" name="apellidoLegal" type="text" id="apellidoLegal" placeholder="Apellidos" maxlength="250" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/>
			</div>
			
			<div data-linea="3">
				<label for="nombreTecnico">Representante técnico</label> 
					<input value="<?php echo $operador['nombre_tecnico'];?>" name="nombreTecnico" type="text" id="nombreTecnico" placeholder="Nombres" maxlength="200" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/>
			</div>
			<div data-linea="3"> 
					<input value="<?php echo $operador['apellido_tecnico'];?>" name="apellidoTecnico" type="text" id="apellidoTecnico" placeholder="Apellidos" maxlength="250" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/>
			</div>
	</fieldset>
	
	<fieldset>
		<legend>Datos de oficina</legend>
		<div data-linea="1">
			<label for="provincia">Provincia</label>
			<select name="provincia" id="provincia" disabled="disabled">
				<option value="">Provincia....</option>
				<?php 
					$provincias = $cc->listarSitiosLocalizacion($conexion, 'PROVINCIAS');
					
					foreach ($provincias as $provincia){
						if(strtoupper($provincia['nombre']) == strtoupper($operador['provincia'])){
							echo '<option value="' . $provincia['codigo'] . '" selected="selected">' . $provincia['nombre'] . '</option>';
						}else{
							echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
						}
					}
				?>
			</select>
		</div>

		
		<div data-linea="1">
			<label for="canton">Cantón</label>
			<select name="canton" id="canton" disabled="disabled">
				<option value="">Canton....</option>
					<?php 
						$cantones = $cc->obtenerHijosLocalizacion($conexion, $operador['provincia'], 'PROVINCIAS', 'CANTONES', 'Ecuador', 'PAIS');
						
						foreach ($cantones as $canton){
							if(strtoupper($canton['nombre']) == strtoupper($operador['canton'])){
								echo '<option value="' . $canton['codigo'] . '" selected="selected">' . $canton['nombre'] . '</option>';
							}else{
								echo '<option value="' . $canton['codigo'] . '">' . $canton['nombre'] . '</option>';
							}
						}
					?>
				
			</select>
		</div>

		
		<div data-linea="1">
			<label for="parroquia">Parroquia</label>
			<select name="parroquia" id="parroquia" disabled="disabled">
			
			<option value="">Parroquia....</option>
					<?php 
						$parroquias = $cc->obtenerHijosLocalizacion($conexion, $operador['canton'], 'CANTONES', 'PARROQUIAS', $operador['provincia'], 'PROVINCIAS');
						
						foreach ($parroquias as $parroquia){
							if(strtoupper($parroquia['nombre']) == strtoupper($operador['parroquia'])){
								echo '<option value="' . $parroquia['codigo'] . '" selected="selected">' . $parroquia['nombre'] . '</option>';
							}else{
								echo '<option value="' . $parroquia['codigo'] . '">' . $parroquia['nombre'] . '</option>';
							}
						}
					?>
				
			</select>
		</div>

		
		<div data-linea="2">
			<label for="direccion">Dirección</label> 
			<input value="<?php echo $operador['direccion'];?>" name="direccion" type="text" id="direccion" class="cuadroTextoCompleto" maxlength="200" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü -\/]+$" disabled="disabled"/>

		</div>
		
		<div data-linea="3">
			<label for="telefono1">Teléfonos</label> 
			<input value="<?php echo $operador['telefono_uno'];?>" name="telefono1" type="text" id="telefono1" placeholder="Principal" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'"  disabled="disabled"/>
		</div>
		<div data-linea="3"> 
			<input value="<?php echo $operador['telefono_dos'];?>" name="telefono2" type="text" id="telefono2" placeholder="Secundario" maxlength="50" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'"  disabled="disabled"/>

		</div>
		
		<div data-linea="4">
			<label for="celular1">Celular</label> 
			<input value="<?php echo $operador['celular_uno'];?>" name="celular1" type="text" id="celular1" placeholder="Principal" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" data-inputmask="'mask': '(99) 9999-9999'" size="15" disabled="disabled"/>
		</div>
		<div data-linea="4"> 
			<input value="<?php echo $operador['celular_dos'];?>" name="celular2" type="text" id="celular2" placeholder="Secundario" maxlength="30" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" data-inputmask="'mask': '(99) 9999-9999'" size="15" disabled="disabled"/> 
		</div>
		
		<div data-linea="5">
			<label for="fax">Fax</label> 
			<input value="<?php echo $operador['fax'];?>" name="fax" type="text" id="fax" placeholder="Secundario" maxlength="30" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}" data-inputmask="'mask': '(99) 999-9999'" size="15" disabled="disabled"/>
		</div>
		
		<div data-linea="6">
			<label for="correo">Correo técnico</label> 
			<input value="<?php echo trim($operador['correo']);?>" name="correo" type="text" id="correo" maxlength="128" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" disabled="disabled"/>
		</div>
		
		<div data-linea="7">
			<label for="correo">Correo facturación electrónica</label> 
			<input value="<?php echo trim($datosFacturacion['correo']);?>" name="correoFacturacion" type="text" id="correoFacturacion" maxlength="128" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" disabled="disabled"/>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Datos de operación</legend>
		<div data-linea="1">	
			<label for="registroOrquideas">Registro orquídeas</label> 
				<input value="<?php echo $operador['registro_orquideas'];?>" name="registroOrquideas" type="text" id="registroOrquideas" placeholder="Código Orquídeas" maxlength="200" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/> 
		</div>
		
		<div data-linea="1">
			<label for="registroMadera">Registro madera</label> 
				<input value="<?php echo $operador['registro_madera'];?>" name="registroMadera" type="text" id="registroMadera" placeholder="Código Madera" maxlength="200" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled"/> 
		</div>
		
		<div data-linea="2">	
			<label for="gs1">GS1 </label><?php echo $operador['gs1'];?>
		</div>
		 
	</fieldset>
	
	<?php if($operador['tipo_actividad'] == "" || $operador['tipo_actividad'] == null){	
		echo '<fieldset>
			<legend>Infomación operador</legend>
			<div data-linea="1">	
				<label >Es usted un operador: </label>
			</div>	
			<div data-linea="2">
				<input type="radio" id="operadorIndividual" name="tipoActividad" value="individual" disabled="disabled" > Individual<br>
				<input type="radio" id="operadorGrupal" name="tipoActividad" value="grupal" disabled="disabled" > Grupal<br>
				<input type="radio" id="operadorGrupalSic" name="tipoActividad" value="grupal-SIC" disabled="disabled" > Grupal-SIC
			</div>	 
		</fieldset>';
	}else{
		echo '<fieldset>
			<legend>Infomación operador</legend>
			<div data-linea="1">
				<label >Tipo de operador: </label>' . $operador['tipo_actividad'] . '
				<input value="' . $operador['tipo_actividad'] . '" name="tipoActividad" type="hidden" readonly="readonly"/> 
			</div>
		</fieldset>';
	}?>
	</td></tr></table>
</form>

<script type="text/javascript">
var array_canton= <?php echo json_encode($cantonesT); ?>;
var array_parroquia= <?php echo json_encode($parroquiasT);?>;
var tipo_actividad= <?php echo json_encode($operador['tipo_actividad']);?>;
var identificador = <?php echo json_encode($identificador);?>;

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();	

		if(tipo_actividad == "individual")	{
			$("#operadorIndividual").attr('checked', true);
		}else if(tipo_actividad == "grupal"){
			$("#operadorGrupal").attr('checked', true);
		}else if(tipo_actividad == "" || tipo_actividad == null){
			bandera = true;
		}
		
	});

	$("#provincia").change(function(){
    	scanton ='0';
		scanton = '<option value="">Cantón...</option>';
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
		sparroquia = '<option value="">Parroquia...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#parroquia').html(sparroquia);
		$("#parroquia").removeAttr("disabled");
	});
	
	
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		$("#razon").attr("disabled","disabled");		
	});

	
	$("#datosPersonales").submit(function(event){
		event.preventDefault();
		//ejecutarJson(this);
		chequearCampos(this);
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
				
		if(!$.trim($("#nombreLegal").val()) || !esCampoValido("#nombreLegal")){
			error = true;
			$("#nombreLegal").addClass("alertaCombo");
		}

		if(!$.trim($("#apellidoLegal").val()) || !esCampoValido("#apellidoLegal")){
			error = true;
			$("#apellidoLegal").addClass("alertaCombo");
		}

		if(!$.trim($("#direccion").val()) || !esCampoValido("#direccion")){
			error = true;
			$("#direccion").addClass("alertaCombo");
		}

		if(!$.trim($("#provincia").val())){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}
		
		if(!$.trim($("#canton").val())){
			error = true;
			$("#canton").addClass("alertaCombo");
		}

		if(!$.trim($("#parroquia").val())){
			error = true;
			$("#parroquia").addClass("alertaCombo");
		}

		if(!$.trim($("#telefono1").val()) || !esCampoValido("#telefono1")){
			error = true;
			$("#telefono1").addClass("alertaCombo");
		}

		if($("#telefono2").val().length!=0 && !esCampoValido("#telefono2")){
			error = true;
			$("#telefono2").addClass("alertaCombo");
		}

		if(!$.trim($("#celular1").val()) || !esCampoValido("#celular1")){
			error = true;
			$("#celular1").addClass("alertaCombo");
		}

		if($("#celular2").val().length!=0 && !esCampoValido("#celular2")){
			error = true;
			$("#celular2").addClass("alertaCombo");
		}
		
		if(!$.trim($("#correo").val()) || !esCampoValido("#correo")){
			error = true;
			$("#correo").addClass("alertaCombo");
		}
		
		if(!$.trim($("#correoFacturacion").val()) || !esCampoValido("#correoFacturacion")){
			error = true;
			$("#correoFacturacion").addClass("alertaCombo");
		}	
		if(identificador.length == 13 ){
			verificarRazonSocial();
		}

		if (error == true){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#razon").removeAttr("disabled");
			ejecutarJson(form);
			$("#razon").attr("disabled",true);
		}
	}
	function verificarRazonSocial(){
        		event.preventDefault();
            	url = "../agrodb/aplicaciones/general/consultaWebServices.php";
             resultado = $.ajax({
        	    url: url,
        	    type: "post",
        	    data: {clasificacion:"Natural",numero:identificador},
        	    dataType: "json",
        	    async:   true,
        	    beforeSend: function(){
        		},
        		success: function(msg){
        	    	if(msg.estado=="exito"){
        	    		$("#razon").val(msg.valores.razonSocial);
        	    	}
        	   },
        	    error: function(jqXHR, textStatus, errorThrown){
        	    },
                complete: function(){
                }
        	});
		}
	
</script>
