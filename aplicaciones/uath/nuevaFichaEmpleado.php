<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorEmpleados.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$ce = new ControladorCatastro();
$cc = new ControladorCatalogos();
$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');

$identificador=$_SESSION['usuario'];
?>

<header>
	<h1>Nuevos Datos Empleado</h1>
</header>

<form id="fichaEmpleado" data-rutaAplicacion="uath" data-opcion="guardarFichaEmpleado" >
	<input type="hidden" id="" class="fotografia" name="fotografia" data-rutaAplicacion="uath" data-opcion="mostrarFoto" data-destino="fotografia" />
	<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario'];?>" /> 
	<input type="hidden" id="archivo" name="archivo" value="" /> 
	<input type="hidden" id="opcion" value="Guardar" name="opcion" />
	
	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td></td>
			<td>
				
				<fieldset>
					<legend>Información Empleado</legend>
					
					<div data-linea="1">
						<label> Identificador</label>
						<input type="text" name="identificadorEmpleado" id="identificadorEmpleado" placeholder="Ej. 1002856050" data-inputmask="'mask': '9[99999999999999]'"
							data-er="[0-9]{1,15}" title="1002856050" />	 
					</div>
	
					<div data-linea="2">
						<label>Apellidos</label> 
							<input type="text" id="apellidoEmpleado" name="apellidoEmpleado" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" placeholder="Ej. GUERRA TERÁN" title="GUERRA TERÁN"/>
					</div>
					<div data-linea="2">
						<label>Nombre</label> 
							<input type="text" id="nombreEmpleado" name="nombreEmpleado" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" placeholder="Ej. PAULO ROBERTO" title="PAULO ROBERTO"/>
					</div>
					
					<div data-linea="3">
						<label>Tipo de documento</label> 
								<select name="tipoDocumento" id="tipoDocumento">
								<option value="" >Seleccione....</option>
								<option value="Cedula">Cédula</option>
								<option value="Pasaporte">Pasaporte</option>
						   </select>
					</div>
					
					<div data-linea="3">
						<label>Nacionalidad</label> <select id="nacionalidad" name="nacionalidad">
							<option value="">Nacionalidad</option>
							<option value="Afgana">Afgana</option>
							<option value="Albanesa">Albanesa</option>
							<option value="Alemana">Alemana</option>
							<option value="Alto volteña">Alto volteña</option>
							<option value="Andorrana">Andorrana</option>
							<option value="Angoleña">Angoleña</option>
							<option value="Argelina">Argelina</option>
							<option value="Argentina">Argentina</option>
							<option value="Australiana">Australiana</option>
							<option value="Austriaca">Austriaca</option>
							<option value="Bahamesa">Bahamesa</option>
							<option value="Bahreina">Bahreina</option>
							<option value="Bangladesha">Bangladesha</option>
							<option value="Barbadesa">Barbadesa</option>
							<option value="Belga">Belga</option>
							<option value="Beliceña">Beliceña</option>
							<option value="Bermudesa">Bermudesa</option>
							<option value="Birmana">Birmana</option>
							<option value="Boliviana">Boliviana</option>
							<option value="Botswanesa">Botswanesa</option>
							<option value="Brasileña">Brasileña</option>
							<option value="Bulgara">Bulgara</option>
							<option value="Burundesa">Burundesa</option>
							<option value="Butana">Butana</option>
							<option value="Camboyana">Camboyana</option>
							<option value="Camerunesa">Camerunesa</option>
							<option value="Canadiense">Canadiense</option>
							<option value="Centroafricana">Centroafricana</option>
							<option value="Chadeña">Chadeña</option>
							<option value="Checoslovaca">Checoslovaca</option>
							<option value="Chilena">Chilena</option>
							<option value="China">China</option>
							<option value="China">China</option>
							<option value="Chipriota">Chipriota</option>
							<option value="Colombiana">Colombiana</option>
							<option value="Congoleña">Congoleña</option>
							<option value="Costarricense">Costarricense</option>
							<option value="Cubana">Cubana</option>
							<option value="Dahoneya">Dahoneya</option>
							<option value="Danes">Danes</option>
							<option value="Dominicana">Dominicana</option>
							<option value="Ecuatoriana">Ecuatoriana</option>
							<option value="Egipcia">Egipcia</option>
							<option value="Emirata">Emirata</option>
							<option value="Escosesa">Escosesa</option>
							<option value="Eslovaca">Eslovaca</option>
							<option value="Española">Española</option>
							<option value="Estona">Estona</option>
							<option value="Etiope">Etiope</option>
							<option value="Fijena">Fijena</option>
							<option value="Filipina">Filipina</option>
							<option value="Finlandesa">Finlandesa</option>
							<option value="Francesa">Francesa</option>
							<option value="Gabiana">Gabiana</option>
							<option value="Gabona">Gabona</option>
							<option value="Galesa">Galesa</option>
							<option value="Ghanesa">Ghanesa</option>
							<option value="Granadeña">Granadeña</option>
							<option value="Griega">Griega</option>
							<option value="Guatemalteca">Guatemalteca</option>
							<option value="Guinesa Ecuatoriana">Guinesa Ecuatoriana</option>
							<option value="Guinesa">Guinesa</option>
							<option value="Guyanesa">Guyanesa</option>
							<option value="Haitiana">Haitiana</option>
							<option value="Holandesa">Holandesa</option>
							<option value="Hondureña">Hondureña</option>
							<option value="Hungara">Hungara</option>
							<option value="India">India</option>
							<option value="Indonesa">Indonesa</option>
							<option value="Inglesa">Inglesa</option>
							<option value="Iraki">Iraki</option>
							<option value="Irani">Irani</option>
							<option value="Irlandesa">Irlandesa</option>
							<option value="Islandesa">Islandesa</option>
							<option value="Israeli">Israeli</option>
							<option value="Italiana">Italiana</option>
							<option value="Jamaiquina">Jamaiquina</option>
							<option value="Japonesa">Japonesa</option>
							<option value="Jordana">Jordana</option>
							<option value="Katensa">Katensa</option>
							<option value="Keniana">Keniana</option>
							<option value="Kuwaiti">Kuwaiti</option>
							<option value="Laosiana">Laosiana</option>
							<option value="Leonesa">Leonesa</option>
							<option value="Lesothensa">Lesothensa</option>
							<option value="Letonesa">Letonesa</option>
							<option value="Libanesa">Libanesa</option>
							<option value="Liberiana">Liberiana</option>
							<option value="Libeña">Libeña</option>
							<option value="Liechtenstein">Liechtenstein</option>
							<option value="Lituana">Lituana</option>
							<option value="Luxemburgo">Luxemburgo</option>
							<option value="Madagascar">Madagascar</option>
							<option value="Malaca">Malaca</option>
							<option value="Malawi">Malawi</option>
							<option value="Maldivas">Maldivas</option>
							<option value="Mali">Mali</option>
							<option value="Maltesa">Maltesa</option>
							<option value="Marfilesa">Marfilesa</option>
							<option value="Marroqui">Marroqui</option>
							<option value="Mauricio">Mauricio</option>
							<option value="Mauritana">Mauritana</option>
							<option value="Mexicana">Mexicana</option>
							<option value="Monaco">Monaco</option>
							<option value="Mongolesa">Mongolesa</option>
							<option value="Nauru">Nauru</option>
							<option value="Neozelandesa">Neozelandesa</option>
							<option value="Nepalesa">Nepalesa</option>
							<option value="Nicaraguense">Nicaraguense</option>
							<option value="Nigerana">Nigerana</option>
							<option value="Nigeriana">Nigeriana</option>
							<option value="Norcoreana">Norcoreana</option>
							<option value="Norirlandesa">Norirlandesa</option>
							<option value="Norteamericana">Norteamericana</option>
							<option value="Noruega">Noruega</option>
							<option value="Omana">Omana</option>
							<option value="Pakistani">Pakistani</option>
							<option value="Panameña">Panameña</option>
							<option value="Paraguaya">Paraguaya</option>
							<option value="Peruana">Peruana</option>
							<option value="Polaca">Polaca</option>
							<option value="Portoriqueña">Portoriqueña</option>
							<option value="Portuguesa">Portuguesa</option>
							<option value="Rhodesiana">Rhodesiana</option>
							<option value="Ruanda">Ruanda</option>
							<option value="Rumana">Rumana</option>
							<option value="Rusa">Rusa</option>
							<option value="Salvadoreña">Salvadoreña</option>
							<option value="Samoa Occidental">Samoa Occidental</option>
							<option value="San marino">San marino</option>
							<option value="Saudi">Saudi</option>
							<option value="Senegalesa">Senegalesa</option>
							<option value="Sikkim">Sikkim</option>
							<option value="Singapur">Singapur</option>
							<option value="Siria">Siria</option>
							<option value="Somalia">Somalia</option>
							<option value="Sovietica">Sovietica</option>
							<option value="Sri Lanka">Sri Lanka</option>
							<option value="Suazilandesa">Suazilandesa</option>
							<option value="Sudafricana">Sudafricana</option>
							<option value="Sudanesa">Sudanesa</option>
							<option value="Sueca">Sueca</option>
							<option value="Suiza">Suiza</option>
							<option value="Surcoreana">Surcoreana</option>
							<option value="Tailandesa">Tailandesa</option>
							<option value="Tanzana">Tanzana</option>
							<option value="Tonga">Tonga</option>
							<option value="Tongo">Tongo</option>
							<option value="Trinidad y Tobago">Trinidad y Tobago</option>
							<option value="Tunecina">Tunecina</option>
							<option value="Turca">Turca</option>
							<option value="Ugandesa">Ugandesa</option>
							<option value="Uruguaya">Uruguaya</option>
							<option value="Vaticano">Vaticano</option>
							<option value="Venezolana">Venezolana</option>
							<option value="Vietnamita">Vietnamita</option>
							<option value="Yemen Rep Arabe">Yemen Rep Arabe</option>
							<option value="Yemen Rep Dem">Yemen Rep Dem</option>
							<option value="Yugoslava">Yugoslava</option>
							<option value="Zaire">Zaire</option>
						</select>
				</div>
					<div data-linea="4">
						<label>Género</label> 
							<select name="genero" id="genero">
								<option value="" >Seleccione....</option>
								<option value="Femenino">Femenino</option>
								<option value="Masculino">Masculino</option>
						   </select>
					</div>
					<div data-linea="4">
						<label>Estado civil</label> 
							<select name="estadoCivil" id="estadoCivil">
								<option value="" >Seleccione....</option>
								<option value="Casado(a)">Casado(a)</option>
								<option value="Soltero(a)">Soltero(a)</option>
								<option value="Divorciado(a)">Divorciado(a)</option>
								<option value="Viudo(a)">Viudo(a)</option>
								<option value="UnionLibre">Unión libre</option>
						   </select>
					</div>
					
					<div data-linea="5">
						<label>Cédula militar</label> 
							<input type="text" id="cedulaMilitar" name="cedulaMilitar" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$"/>
					</div>
					
					<div data-linea="5">
						<label>Fecha nacimiento</label> 
							<input type="text" id="fechaNacimiento" name="fechaNacimiento" required="required" readonly="readonly"   />
					</div>
					
					<div data-linea="6">
						<label>Edad</label> 
							<input type="text" id="edad" name="edad" readonly="readonly" />
					</div>
										
					<div data-linea="6">
						<label>Tipo de sangre</label> 
							<select name="tipoSangre" id="tipoSangre">
								<option value="" >Seleccione....</option>
								<option value="A+">A+</option>
								<option value="A-">A-</option>
								<option value="B+">B+</option>
								<option value="B-">B-</option>
								<option value="AB+">AB+</option>
								<option value="AB-">AB-</option>
								<option value="O+">O+</option>
								<option value="O-">O-</option>
						   </select>
					</div>
					</fieldset>
					<fieldset>
					<legend>Información étnica</legend>
						
					<div data-linea="1">
					<label>Identificación étnica</label> <select name="identificacionEtnica" id="identificacionEtnica">
						<option value="">Seleccione su etnia</option>
						<option value="Afroecuatoriano">Afroecuatoriano</option>
						<option value="Blanco">Blanco</option>
						<option value="Indigena">Indigena</option>
						<option value="Mestizo">Mestizo</option>
						<option value="Montubio">Montubio</option>
						<option value="Mulato">Mulato</option>
						<option value="Negro">Negro</option>
						<option value="Otros">Otros</option>
					</select>
					</div>
					
					<div data-linea="1">
					<label>Nacionalidad indigena</label> <select
						id="nacionalidadIndigena" name="nacionalidadIndigena" disabled="disabled">
						<option value="No aplica">No aplica</option>
						<option value="Achuar">Achuar</option>
						<option value="Andoa">Andoa</option>
						<option value="Awa">Awa</option>
						<option value="Chachi">Chachi</option>
						<option value="Cofán">Cofán</option>
						<option value="Epera">Epera</option>
						<option value="Qichwa">Qichwa</option>
						<option value="Secoya">Secoya</option>
						<option value="Shiwiar">Shiwiar</option>
						<option value="Shuar">Shuar</option>
						<option value="Siona">Siona</option>
						<option value="Tsachila">Tsachila</option>
						<option value="Waorani">Waorani</option>
						<option value="Zápara">Zápara</option>
					</select>
					</div>
				</fieldset>
				<fieldset>
					<legend>Fotografía</legend>
					<div id="fotografia"></div>
				</fieldset>	
					
				<fieldset>
					<legend>Información adicional</legend>
					<div data-linea="4">
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
				
					</div><div data-linea="4">
	
						<label>Canton</label>
							<select id="canton" name="canton" disabled="disabled" >
							</select>
						<input type="hidden" id="nombreCanton" name="nombreCanton" />
				
				</div>
				<div data-linea="5">
				
						<label>Parroquia</label>
							<select id="parroquia" name="parroquia" disabled="disabled">
							</select>
						<input type="hidden" id="nombreParroquia" name="nombreParroquia" />
							
					</div>
					<div data-linea="5">
						<label>Convencional</label> 
							<input type="text" name="convencional" id="convencional" placeholder="Ej. 2222222" data-inputmask="'mask': '9[99999999999999]'"
							data-er="[0-9]{1,15}" title="99" />
					</div>							
					<div data-linea="8">
						<label>Dirección domiciliaria</label> 
							<input type="text" name="domicilio" id="domicilio" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$"/>
					</div>
					
					<div data-linea="9">
						<label>Extension agrocalidad</label> 
							<input type="text" name="extension" id="extension" placeholder="Ej. 9999" data-inputmask="'mask': '9[99999999999999]'"
							data-er="[0-9]{1,15}" title="99" />
					</div>
																	
					<div data-linea="9">
						<label>Celular</label> 
							<input type="text" name="celular" id="celular" placeholder="Ej. 0999999999" data-inputmask="'mask': '9[99999999999999]'"
							data-er="[0-9]{1,15}" title="99" />
					</div>
					
					<div data-linea="11">
						<label>Mail personal</label> 
							<input type="text" name="mailPersonal" id="mailPersonal" placeholder="Ej. correo@servicio.com" title="99" />
					</div>
					
					<div data-linea="12">
						<label>Mail institucional </label> 
							<input type="text" name="mailInstitucional" id="mailInstitucional" placeholder="Ej. cuenta@gob.ec" 	title="99" />
					</div>
																							
				</fieldset>
				
				<fieldset>
					<legend>Información de discapacidad</legend>
						
					<div data-linea="1">
					    <label>Tiene discapacidad?</label> 
						<select
						name="discapacidad_empleado">
						<option value="NO">No</option>
						<option value="SI">Si</option>
						</select>		
					</div>
					<div data-linea="1">
					    <label>No. Carnet</label> 
						<input type="text" name="carnet_conadis_empleado" />
					</div>
					
					<div data-linea="2">
					    <label>Es representante de persona con discapacidad?</label> 
						<select
						name="representante_discapacitado">
						<option value="NO">No</option>
						<option value="SI">Si</option>
						</select>		
					</div>
					<div data-linea="3">
					    <label>No. Carnet de la persona con discapacidad</label> 
						<input type="text" name="carnet_conadis_familiar"/>
					</div>
					<div data-linea="4">
					    <label>Tiene enfermedad catastrófica?</label> 
						<select
						name="enfermedad_catastrofica">
						<option value="NO">No</option>
						<option value="SI">Si</option>
						</select>	
					</div>
					<div data-linea="5">
					    <label>Nombre enfermedad catastrófica</label> 
						<select
						name="nombre_enfermedad_catastrofica">
						<option value="">Seleccione....</option>
						<option value="Malformacion congenitas del corazon o valvulopatias cardiacas">Malformación congénitas del corazón o valvulopatías cardiacas</option>
						<option value="Cancer">Cáncer</option>
						<option value="Tumor cerebral">Tumor cerebral</option>
						<option value="Insuficiencia renal cronica">Insuficiencia renal crónica</option>
						<option value="Insuficiencia renal cronica">Insuficiencia renal crónica</option>
						<option value="Transplante de organos: riñon, higado, medula osea">Transplante de órganos: riñón, higado, médula ósea</option>
						<option value="Secuelas de quemaduras graves">Secuelas de quemaduras graves</option>
						<option value="Malformaciones arterio venosas cerebrales">Malformaciones arterio venosas cerebrales</option>
						<option value="Sindrome de Klippel Trenaunay">Síndrome de Klippel Trenaunay</option>
						<option value="Aneurisma toraco-abdominal">Aneurisma tóraco-abdominal</option>
						</select>
					</div>
				</fieldset>
				
			</td>
		</tr>
	</table>
	
	<p>
		<button id="actualizar" type="submit" class="guardar">Guardar</button>
	</p>
</form>

<script type="text/javascript">

var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;

$("#identificadorEmpleado").change(function(){
	var texto=($("#identificadorEmpleado").val());
	texto=texto.replace(/_/g,'');
	$('[name="fotografia"]').attr("id",texto);
	$('[name="usuario"]').attr("value",texto);
	$('[name="boton"]').removeAttr("disabled");
	
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
});

$("#canton").change(function(){

  	 $('#nombreCanton').val($("#canton option:selected").text());
		soficina ='0';
		soficina = '<option value="">Parroquia...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	soficina += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#parroquia').html(soficina);
		$("#parroquia").removeAttr("disabled");
	});
									

	function calcularEdad()
	{
	    var fecha=$( "#fechaNacimiento" ).val();
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

	$('#fechaNacimiento').change(function(event){
    	calcularEdad();
	});


								
	$(document).ready(function(){
		
			$( "#fechaNacimiento" ).datepicker({
			      yearRange: "c-90:c+0",
			      changeMonth: true,
			      changeYear: true
			    });
	
			construirValidador();
			distribuirLineas();
			abrir($("#fichaEmpleado input:hidden"),null,false);
			$('[name="boton"]').attr("disabled","disabled");		
	
		});


	$('select[name="identificacionEtnica"]').change(function(){
		if($('select[name="identificacionEtnica"] option:selected').attr("value")!="Indigena"){
			cargarValorDefecto($('[name="nacionalidadIndigena"] option'),"No aplica");
			$('[name="nacionalidadIndigena"]').attr("disabled","disabled");
		} else{
			$('[name="nacionalidadIndigena"]').removeAttr("disabled");
		}
	});
	

	$('#fotografia').change(function(event){

		$("#estado").html('');
		var archivoFoto = $("#identificadorEmpleado").val();
		$("#archivo").val("aplicaciones/uath/fotos/"+archivoFoto+".jpg");	
		
	});


	$("#fichaEmpleado").submit(function(event){
		event.preventDefault();
		chequearCampos(this);
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		if(!$.trim($("#identificadorEmpleado").val()) || !esCampoValido("#identificadorEmpleado")){
			error = true;
			$("#identificadorEmpleado").addClass("alertaCombo");
		}
		if(!$.trim($("#nombreEmpleado").val()) || !esCampoValido("#nombreEmpleado")){
				error = true;
				$("#nombreEmpleado").addClass("alertaCombo");
			}
		if(!$.trim($("#apellidoEmpleado").val()) || !esCampoValido("#apellidoEmpleado")){
				error = true;
				$("#apellidoEmpleado").addClass("alertaCombo");
			}
		if(!$.trim($("#tipoDocumento").val()) || !esCampoValido("#tipoDocumento")){
				error = true;
				$("#tipoDocumento").addClass("alertaCombo");
		}
		if(!$.trim($("#fechaNacimiento").val()) || !esCampoValido("#fechaNacimiento")){
			error = true;
			$("#fechaNacimiento").addClass("alertaCombo");
	    }
		if(!$.trim($("#identificacionEtnica").val()) || !esCampoValido("#identificacionEtnica")){
			error = true;
			$("#identificacionEtnica").addClass("alertaCombo");
	    }
		if(!$.trim($("#nacionalidadIndigena").val()) || !esCampoValido("#nacionalidadIndigena")){
			error = true;
			$("#nacionalidadIndigena").addClass("alertaCombo");
	    }
		if(!$.trim($("#domicilio").val()) || !esCampoValido("#domicilio")){
			error = true;
			$("#domicilio").addClass("alertaCombo");
	    }
		if(!$.trim($("#convencional").val()) || !esCampoValido("#convencional")){
			error = true;
			$("#convencional").addClass("alertaCombo");
	    }
		if(!$.trim($("#celular").val()) || !esCampoValido("#celular")){
			error = true;
			$("#celular").addClass("alertaCombo");
	    }
		if(!$.trim($("#mailPersonal").val())){
			error = true;
			$("#mailPersonal").addClass("alertaCombo");
	    }
		if(!$.trim($("#mailInstitucional").val())){
			error = true;
			$("#mailInstitucional").addClass("alertaCombo");
	    }

		if($("#nacionalidad option:selected").val()==""){
			error = true;
			$("#nacionalidad").addClass("alertaCombo");
		}

		if($("#genero option:selected").val()==""){
			error = true;
			$("#genero").addClass("alertaCombo");
		}

		if($("#estadoCivil option:selected").val()==""){
			error = true;
			$("#estadoCivil").addClass("alertaCombo");
		}
		if($("#tipoSangre option:selected").val()==""){
			error = true;
			$("#tipoSangre").addClass("alertaCombo");
		}
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			if($('#estado').html()=='Los datos han sido ingresados satisfactoriamente')
				$('#_actualizar').click();
		}
	}
</script>
