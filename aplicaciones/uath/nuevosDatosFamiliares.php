<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$ce = new ControladorCatastro();

$qFamiliares = $ce->obtenerDatosFamiliares($conexion, $_SESSION['usuario']);

while($fila = pg_fetch_assoc($qFamiliares)){
	$familiares[]= $fila['identificador_familiar'];
}
//print_r($familiares);
?>

<header>
	<h1>
		Nuevos Familiares y Contactos
	</h1>
</header>

<form id="datosFamiliar" data-rutaAplicacion="uath" data-opcion="guardarDatosFamiliares">

	<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario'];?>" /> 
	<input type="hidden" id="opcion" value="" name="opcion" />

	<p>
		<button id="guardar" type="submit" class="guardar">Guardar</button>
	</p>
	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Información básica</legend>
                    <div data-linea="1">
						<label>Tipo de documento</label> 
							<select name="tipo_documento" id="tipo_documento" style=" width:100%">
								<option value="">Seleccione....</option>
								<option value="Cédula">Cédula</option>
								<option value="Pasaporte">Pasaporte</option>
						</select>
					</div>
					<div data-linea="1">
						<label>Cedula</label> 
							<input type="text" id="cedula" name="cedula" placeholder="Ej. Identidad" 
							pattern="[0-9A-Za-zñÑÁáÉéÍíÓóÚúÜü]{10}" title="0533556677" maxlength="10" data-er="^[0-9A-Za-zñÑÁáÉéÍíÓóÚúÜü]+$"/>
					</div>
					
					<div data-linea="2">
						<label>Relación</label> 
							<input type="text" id="relacion" name="relacion" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>
					</div>
					
					<div data-linea="3">
						<label>Nombres</label> 
							<input type="text" id="nombreFamiliar" name="nombreFamiliar" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>
					</div>
					<div data-linea="3">
						<label>Apellidos</label> 
							<input type="text" id="apellidoFamiliar" name="apellidoFamiliar" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>
					</div>
					
					<div data-linea="4">
						<label>Fecha de nacimiento</label> 
							<input type="text" id="nacimiento" name="nacimiento" required="required">
					</div>
					<div data-linea="4">
						<label>Edad</label> 
							<input type="text" id="edad" name="edad" placeholder="Edad" title="23" maxlength="2" data-er="^[0-9]+$" />
					</div>
					<div data-linea="5">
						<label>Nivel de Instrucción</label> 
							<select name="nivel_instruccion" id="nivel_instruccion" style=" width:100%">
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
							<input type="checkbox" id="contactoEmergencia" name="contactoEmergencia"/>
					</div>
				</fieldset>
				<fieldset>
					<legend>Ubicación</legend>
					<div data-linea="1">
						<label>Calle Principal</label> 
							<input type="text" id="calle_principal" name="calle_principal" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9 ]+$"/>
					</div>
					<div data-linea="1">
						<label>Número</label> 
							<input type="text" id="numero" name="numero" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
					</div>
					<div data-linea="3">
						<label>Calle Secundaria</label> 
							<input type="text" id="calle_secundaria" name="calle_secundaria" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9 ]+$" />
					</div>
					<div data-linea="4">
						<label>Referencia</label> 
							<input type="text" id="referencia" name="referencia" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9 ]+$"/>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>Teléfonos</legend>
					<div data-linea="1">
						<label>Convencional</label> 
							<input type="text" id="telefono" name="telefono" placeholder="Ej. (02) 227-2345" data-inputmask="'mask': '(99) 999-9999'"
							data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}" title="(02) 227-2345" size="15"/>
					</div>
					<div data-linea="1">
						<label>Celular</label> 
							<input type="text" id="celular" name="celular" placeholder="Ej. (09) 9988-8899" data-inputmask="'mask': '(09) 9999-9999'"
							data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" title="(09) 9988-8899" size="15" />
					</div>
					<div data-linea="3">
						<label>Oficina</label> 
							<input type="text" id="telefono_oficina" name="telefono_oficina" placeholder="Ej. (02) 227-2345" data-inputmask="'mask': '(99) 999-9999'" 
							data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}" title="(02) 227-2345" size="15" />
					</div>
					<div data-linea="3">
						<label>Extension</label> <input type="text" id="extension" name="extension" maxlength="6" placeholder="Ej. 9999"
							data-er="[0-9]{0,4}" title="9999" />
					</div>
					</fieldset>
					<fieldset>
					<legend>Datos de discapacidad</legend>
					<div data-linea="4">
					
					    <label>Tiene discapacidad?</label> 
						<select
						name="representante_discapacitado" id="representante_discapacitado">
						<option value="NO">No</option>
						<option value="SI">Si</option>
						</select>		
					</div>
					<div data-linea="5">
					    <label>No. Carnet de la persona con discapacidad</label> 
						<input type="text" name="carnet_conadis_familiar" id="carnet_conadis_familiar"/>
					</div>
					
				</fieldset>
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">

var array_Familiares= <?php echo json_encode($familiares); ?>;

  $(document).ready(function(){
		$( "#nacimiento" ).datepicker({
		      yearRange: "c-100:c+0",
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
	});

  $("#datosFamiliar").submit(function(event){
		var validar = 0;
		
		event.preventDefault();

		if(array_Familiares != null){
			for(var i=0; i<array_Familiares.length; i++){
				if (array_Familiares[i] == $("#cedula").val()){
					alert('El familiar ingresado ya ha sido registrado.');
					validar=1;
					break;
				}
			}	
		}
		

		if(validar==0){
			chequearCampos(this);
		}
	});

  function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		if(!$.trim($("#tipo_documento").val())){
			error = true;
			$("#tipo_documento").addClass("alertaCombo");
		}
		if(!$.trim($("#cedula").val()) || !esCampoValido("#cedula")|| $("#cedula").val().length != $("#cedula").attr("maxlength")){
			error = true;
			$("#cedula").addClass("alertaCombo");
		}

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

		if(!$.trim($("#nivel_instruccion").val()) ){
			error = true;
			$("#nivel_instruccion").addClass("alertaCombo");
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
        $("#edad").val(fecha);
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
