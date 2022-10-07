<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$usuario_seleccionado=$_POST['id'];

$conexion = new Conexion();
$ce = new ControladorCatastro();
$res = $ce->obtenerDatosFamiliares($conexion, $_SESSION['usuario'], strval($usuario_seleccionado));
$_SESSION['usuario_seleccionado']=$usuario_seleccionado;
$familiar = pg_fetch_assoc($res);
$_SESSION['nombre_familiar_seleccionado']=$familiar['nombre'].' '.$familiar['apellido'];
?>

<header>
	<h1>Familiares y Contactos</h1>
</header>

<form id="datosContactos" data-rutaAplicacion="uath" data-opcion="actualizarDatosFamiliares">

	<input type="hidden" id="<?php echo $_SESSION['usuario'];?>" /> 
	<input type="hidden" id="opcion" value="" name="opcion" /> 
	<input type="hidden" id="usuario_seleccionado" value="<?php echo $usuario_seleccionado;?>" name="usuario_seleccionado" />

	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		<button id="adjunto" type="button" class="adjunto" data-rutaaplicacion="uath" data-opcion="listaEnfermedades" data-destino="listadoItems" >Enfermedades</button>
	</p>
	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Información básica</legend>
					
					<div data-linea="1">
						<label>Nombres</label> 
							<input type="text" id="nombreFamiliar" name="nombreFamiliar" value="<?php echo $familiar['nombre'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
					</div>
					<div data-linea="1">
						<label>Apellidos</label> <input type="text" id="apellidoFamiliar" name="apellidoFamiliar" value="<?php echo $familiar['apellido'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"	/>
					</div>
					<div data-linea="2">
						<label>Relación</label> 
							<input type="text" id="relacion" name="relacion" value="<?php echo $familiar['relacion'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
					</div>
					<div data-linea="3">
						<label>Fecha de nacimiento</label> 
							<input type="text" id="nacimiento" name="nacimiento" value="<?php echo date('d/m/Y',strtotime($familiar['fecha_nacimiento']));?>" disabled="disabled" required="required" />
					</div>
					<div data-linea="3">
						<label>Edad</label> 
							<input type="text" id="edad" name="edad" value="<?php echo $familiar['edad'];?>" disabled="disabled" placeholder="Edad" title="23" maxlength="2" data-er="^[0-9]+$"/>
					</div>
					<div data-linea="5">
						<label>Nivel de Instrucción</label> 
							<select name="nivel_instruccion" id="nivel_instruccion" style=" width:100%" disabled="disabled">
								<option value="">Seleccione....</option>
								<option value="Sin instrucción">Sin instrucción</option>
								<option value="Primaria">Primaria</option>
								<option value="Secundaria">Secundaria</option>
								<option value="Bachiller">Bachiller</option>
								<option value="Tercer Nivel">Tercer Nivel</option>
								<option value="Cuarto Nivel">Cuarto Nivel</option>
								<option value="Doctorado">Doctorado</option>
								<option value="PhD">PhD</option>
						</select>
					</div>
					<div data-linea="6">
						<label>Es contacto de emergencia?</label> 
							<input type="checkbox" id="contactoEmergencia" name="contactoEmergencia" <?php echo ($familiar['contacto_emergencia']=='t'? ' checked="true"':'')?> disabled="disabled"/>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>Ubicación</legend>
					<div data-linea="1">
						<label>Calle Principal</label> 
							<input type="text" id="calle_principal" name="calle_principal" value="<?php echo $familiar['calle_principal'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9 ]+$" />
					</div>
					<div data-linea="1">
						<label>Número</label> 
							<input type="text" id="numero" name="numero" value="<?php echo $familiar['numero'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
					</div>
					<div data-linea="2">
						<label>Calle Secundaria</label> 
							<input type="text" id="calle_secundaria" name="calle_secundaria" value="<?php echo $familiar['calle_secundaria'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9 ]+$" />
					</div>
					<div data-linea="3">
						<label>Referencia</label> <input type="text" id="referencia" name="referencia" value="<?php echo $familiar['referencia'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9 ]+$"/>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>Teléfonos</legend>
					<div data-linea="1">
						<label>Convencional</label> 
							<input type="text" id="telefono" name="telefono" value="<?php echo $familiar['telefono'];?>" disabled="disabled"
							placeholder="Ej. (02) 227-2345" data-inputmask="'mask': '(99) 999-9999'"
							data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}" title="(02) 227-2345"
							size="15" required="required" />
					</div>
					<div data-linea="1">
						<label>Celular</label> 
							<input type="text" id="celular" name="celular" value="<?php echo $familiar['celular'];?>" disabled="disabled"
							placeholder="Ej. (09) 9988-8899" data-inputmask="'mask': '(09) 9999-9999'"
							data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" title="(09) 9988-8899"
							size="15" />
					</div>
					<div data-linea="2">
						<label>Oficina</label> 
							<input type="text" id="telefono_oficina" name="telefono_oficina" value="<?php echo $familiar['telefono_oficina'];?>"
							disabled="disabled" placeholder="Ej. (02) 227-2345"
							data-inputmask="'mask': '(99) 999-9999'"
							data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}" title="(02) 227-2345"
							size="15" />
					</div>
					<div data-linea="2">
						<label>Extension</label> 
							<input type="text" id="extension" name="extension"
							value="<?php echo $familiar['extension'];?>" disabled="disabled"
							placeholder="Ej. 9999" data-inputmask="'mask': '[9999]'"
							data-er="[0-9]{0,4}" title="9999" />
					</div>

				</fieldset>
						<fieldset>
					<legend>Datos de discapacidad</legend>
					<div data-linea="4">
					
					    <label>Tiene discapacidad?</label> 
						<select
						name="posee_discapacidad" id="posee_discapacidad" disabled="disabled">
						<option value="NO">No</option>
						<option value="SI">Si</option>
						</select>		
					</div>
					<div data-linea="5">
					    <label>No. Carnet de la persona con discapacidad</label> 
						<input type="text" name="carnet_conadis_familiar" id="carnet_conadis_familiar" value="<?php echo $familiar['numero_carnet_conadis'];?>" disabled="disabled"/>
					</div>
					
				</fieldset>
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">

	$("#datosContactos").submit(function(event){
		event.preventDefault();
		chequearCampos(this);
		
		$("#modificar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});
  
  	$("#adjunto").click(function(event){
		abrir($("#adjunto"),event, false);
  	});
  	
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");	
	});

	$(document).ready(function(){
		$("#nacimiento").datepicker({
		      yearRange: "c-100:c+100",
		      changeMonth: true,
		      changeYear: true
		    });
		$("#telefono").ForceNumericOnly();
		$("#celular").ForceNumericOnly();
		$("#telefono_oficina").ForceNumericOnly();
		$("#extension").ForceNumericOnly();
		$("#edad").ForceNumericOnly();
		construirValidador();
		distribuirLineas();
		cargarValorDefecto("posee_discapacidad","<?php echo $familiar['posee_discapacidad'];?>");
		cargarValorDefecto("nivel_instruccion","<?php echo $familiar['nivel_instruccion'];?>");
		calcularEdad();
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#relacion").val()) || !esCampoValido("#relacion")){
			error = true;
			$("#relacion").addClass("alertaCombo");
		}

		if(!$.trim($("#nombreFamiliar").val()) || !esCampoValido("#nombreFamiliar")){
			error = true;
			$("#nombreFamiliar").addClass("alertaCombo");
		}

		if(!$.trim($("#apellidoFamiliar").val()) || !esCampoValido("#apellidoFamiliar")){
			error = true;
			$("#apellidoFamiliar").addClass("alertaCombo");
		}

		if(!$.trim($("#nacimiento").val()) || !esCampoValido("#nacimiento")){
			error = true;
			$("#nacimiento").addClass("alertaCombo");
		}

		if(!$.trim($("#edad").val()) || !esCampoValido("#edad") || $("#edad").val().length != $("#edad").attr("maxlength")){
			error = true;
			$("#edad").addClass("alertaCombo");
		}

		if(!$.trim($("#calle_principal").val()) || !esCampoValido("#calle_principal")){
			error = true;
			$("#calle_principal").addClass("alertaCombo");
		}

		if(!$.trim($("#numero").val()) || !esCampoValido("#numero")){
			error = true;
			$("#numero").addClass("alertaCombo");
		}

		if(!$.trim($("#calle_secundaria").val()) || !esCampoValido("#calle_secundaria")){
			error = true;
			$("#calle_secundaria").addClass("alertaCombo");
		}

		if(!$.trim($("#referencia").val()) || !esCampoValido("#referencia")){
			error = true;
			$("#referencia").addClass("alertaCombo");
		}

		if(!$.trim($("#telefono").val()) || !esCampoValido("#telefono")){
			error = true;
			$("#telefono").addClass("alertaCombo");
		}

		if(!$.trim($("#celular").val().length!=0) && !esCampoValido("#celular")){
			error = true;
			$("#celular").addClass("alertaCombo");
		}
		if(!$.trim($("#extension").val().length!=0) && !esCampoValido("#extension")){
			error = true;
			$("#extension").addClass("alertaCombo");
		}
		if($("#posee_discapacidad").val()=="SI" && $("#carnet_conadis_familiar").val()==""){
			error = true;
			$("#carnet_conadis_familiar").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			if($('#estado').html()=='Los datos han sido actualizados satisfactoriamente')
				$('#_actualizar').click();
		}
	}

	function calcularEdad()
	{
	    var fecha=$( "#nacimiento" ).val();
       
        var values=fecha.split("/");
        var dia = values[0];
        var mes = values[1];
        var ano = values[2];

        // cogemos los valores actuales
        var fecha_hoy = new Date();
        var ahora_ano = fecha_hoy.getYear();
        var ahora_mes = fecha_hoy.getMonth();
        var ahora_dia = fecha_hoy.getDate();
        
        // realizamos el calculo
        var edad = (ahora_ano + 1900) - ano;
        if ( ahora_mes < (mes - 1))
        {
            edad--;
        }
        if (((mes - 1) == ahora_mes) && (ahora_dia < dia))
        {
            edad--;
        }
        if (edad > 1900)
        {
            edad -= 1900;
        }

        $("#edad").val(edad); 
          
	}

	$('#nacimiento').change(function(event){
    	calcularEdad();
	});

</script>
