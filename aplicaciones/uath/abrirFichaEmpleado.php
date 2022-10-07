<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCatalogos.php';

$academico_seleccionado=$_POST['id'];

$conexion = new Conexion();
$ce = new ControladorCatastro();
$res = $ce->listaFichaEmpleados($conexion,$academico_seleccionado,'','');
$fila = pg_fetch_assoc($res);
$cc = new ControladorCatalogos();
$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');

$identificador=$_SESSION['usuario'];

?>

<header>
	<h1>Modificar Datos Empleado</h1>
</header>

<form id="datosEmpleado" data-rutaAplicacion="uath" data-opcion="guardarFichaEmpleado">
	<input type="hidden" id="<?php echo $academico_seleccionado;?>" data-rutaAplicacion="uath" data-opcion="mostrarFoto" data-destino="fotografia" />
	<input type="hidden" id="archivo" name="archivo" value="<?php echo $fila['fotografia'];?>"/>
	<input type="hidden" id="usuario_seleccionado" name="usuario_seleccionado" value="<?php echo $_SESSION['usuario_seleccionado']?>" />
	<input type="hidden" id="opcion" name="opcion" value="Actualizar"/>

	<div id="mostrarBotones">
		<p>
			<button id="modificar" type="button" class="editar" >Editar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</p>
	</div>

    <div id="estado"></div>
	
	<table class="soloImpresion">
		<tr>
			<td><fieldset>
					<legend>Fotografía</legend>
					<div id="fotografia"></div>
				</fieldset></td>
			<td>
				<fieldset>
					<legend>Información Empleado</legend>
					
					<div data-linea="1">
						<label> Identificador</label>
						<input type="text" name="identificadorEmpleado" id="identificadorEmpleado" placeholder="Ej. 9999" data-inputmask="'mask': '9[99999999999999]'"
							data-er="[0-9]{1,15}" title="99" value="<?php echo $fila['identificador']?>" disabled="disabled" readonly="readonly" />	 
					</div>
	
					<div data-linea="2">
						<label>Apellidos</label> 
							<input type="text" id="apellidoEmpleado" name="apellidoEmpleado" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" value="<?php echo $fila['apellido']?>" disabled="disabled" placeholder="Ej. GUERRA TERÁN" title="GUERRA TERÁN" />
					</div>
					<div data-linea="2">
						<label>Nombres</label> 
							<input type="text" id="nombreEmpleado" name="nombreEmpleado" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" value="<?php echo $fila['nombre']?>" disabled="disabled" placeholder="Ej. PAULO ROBERTO" title="PAULO ROBERTO"/>
					</div>
					
					<div data-linea="3">
						<label>Tipo de documento</label> 
								<select name="tipoDocumento" id="tipoDocumento" disabled="disabled">
								<option value="" >Seleccione....</option>
								<option value="Cedula">Cedula</option>
								<option value="Pasaporte">Pasaporte</option>
						   </select>
					</div>
					
					<div data-linea="3">
						<label>Nacionalidad</label> <select id="nacionalidad" name="nacionalidad"
							disabled="disabled">
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
						<label>Genero</label> 
							<select name="genero" id="genero" disabled="disabled">
								<option value="" >Seleccione....</option>
								<option value="Femenino">Femenino</option>
								<option value="Masculino">Masculino</option>
						   </select>
					</div>
					<div data-linea="4">
						<label>Estado civil</label> 
							<select name="estadoCivil" id="estadoCivil" disabled="disabled">
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
							<input type="text" id="cedulaMilitar" name="cedulaMilitar" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" value="<?php echo $fila['cedula_militar']?>" disabled="disabled" />
					</div>
					
					<div data-linea="5">
						<label>Fecha nacimiento</label> 
							<input type="text" id="fechaNacimiento" name="fechaNacimiento" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" required="required" readonly="readonly" value="<?php echo date('d/m/Y',strtotime($fila['fecha_nacimiento']));?>" disabled="disabled"  />
					</div>
					
					<div data-linea="6">
						<label>Edad</label> 
							<input type="text" id="edad" name="edad" value="<?php echo $fila['edad'];?>" readonly="readonly"  />
					</div>
										
					<div data-linea="6">
						<label>Tipo de sangre</label> 
							<select name="tipoSangre" id="tipoSangre" disabled="disabled">
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
					<label>Identificación étnica</label> <select name="identificacionEtnica" id="identificacionEtnica" disabled="disabled">
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
					<legend>Información adicional</legend>
					
					<div data-linea="4">
						<label>Provincia</label>
								<select id="provincia" name="provincia" disabled="disabled" >
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
							data-er="[0-9]{1,15}" title="99" value="<?php echo $fila['convencional']?>" disabled="disabled" />
					</div>
								
					<div data-linea="8">
						<label>Dirección domiciliaria</label> 
							<input type="text" name="domicilio" id="domicilio" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" value="<?php echo $fila['domicilio']?>" disabled="disabled"/>
					</div>
					
					<div data-linea="9">
						<label>Extension agrocalidad</label> 
							<input type="text" name="extension" id="extension" placeholder="Ej. 9999" data-inputmask="'mask': '9[99999999999999]'"
							data-er="[0-9]{1,15}" title="99" value="<?php echo $fila['extension']?>" disabled="disabled" />
					</div>
					
									
					<div data-linea="9">
						<label>Celular</label> 
							<input type="text" name="celular" id="celular" placeholder="Ej. 0999999999" data-inputmask="'mask': '9[99999999999999]'"
							data-er="[0-9]{1,15}" title="99" value="<?php echo $fila['celular']?>" disabled="disabled"/>
					</div>
					
					<div data-linea="11">
						<label>Mail personal</label> 
							<input type="text" name="mailPersonal" id="mailPersonal" placeholder="Ej. correo@servicio.com" title="99" value="<?php echo $fila['mail_personal']?>" disabled="disabled" />
					</div>
					
					<div data-linea="12">
						<label>Mail institucional </label> 
							<input type="text" name="mailInstitucional" id="mailInstitucional" placeholder="Ej. cuenta@gob.ec" 	title="99" value="<?php echo $fila['mail_institucional']?>" disabled="disabled"/>
					</div>
														
				</fieldset>
				
				<fieldset>
					<legend>Información de discapacidad</legend>
						
					<div data-linea="1">
					    <label>Tiene discapacidad?</label> 
						<select
						name="discapacidad_empleado" disabled="disabled">
						<option value="NO">No</option>
						<option value="SI">Si</option>
						</select>		
					</div>
					<div data-linea="1">
					    <label>No. Carnet</label> 
						<input type="text" name="carnet_conadis_empleado"  value="<?php echo $fila['carnet_conadis_empleado']?>" disabled="disabled"/>
					</div>
					
					<div data-linea="2">
					    <label>Es representante de persona con discapacidad?</label> 
						<select
						name="representante_discapacitado" disabled="disabled">
						<option value="NO">No</option>
						<option value="SI">Si</option>
						</select>		
					</div>
					<div data-linea="3">
					    <label>No. Carnet de la persona con discapacidad</label> 
						<input type="text" name="carnet_conadis_familiar" value="<?php echo $fila['carnet_conadis_familiar']?>" disabled="disabled"/>
					</div>
					<div data-linea="4">
					    <label>Tiene enfermedad catastrófica?</label> 
						<select
						name="enfermedad_catastrofica" disabled="disabled">
						<option value="NO">No</option>
						<option value="SI">Si</option>
						</select>	
					</div>
					<div data-linea="5">
					    <label>Nombre enfermedad catastrófica</label> 
						<select
						name="nombre_enfermedad_catastrofica" disabled="disabled">
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
				<fieldset>
					<legend>Perfil público</legend>					
					<div id="dRutaPerfilPublico" data-linea="1">
						<label>Link: </label>
						<?php 
						if(trim($fila['ruta_perfil_publico']) == "" || $fila['ruta_perfil_publico'] == null){
                            echo 'Aún no se ha generado un enlace de perfil público';	
						}else{
						    echo '<a href="' . $fila['ruta_perfil_publico'] . '" target="_blank">Enlance perfil público</a>';	
						}
						?>
						<hr/>					
					</div>
					<div id="dRutaQrPerfilPublico" data-linea="2">
						<?php 
						if(trim($fila['ruta_qr_perfil_publico']) != "" || $fila['ruta_qr_perfil_publico'] != null){
						    echo '<img src="' . $fila['ruta_qr_perfil_publico'] . '" />';
						}						
						?>
					</div>
				</fieldset>
			</td>
		</tr>
		<tr>
				
	</table>
	
	
</form>

<script type="text/javascript">

var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;
var identificador= <?php echo json_encode($_POST['id']); ?>;

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
  $("#nombreProvincia").removeAttr("disabled");
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

	

	$("#datosEmpleado").submit(function(event){
		error=false;
		event.preventDefault();
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
		if (!error){
			ejecutarJson($(this));

			$.ajax({
				url:'aplicaciones/general/obtenerPerfilPublico.php',
				method: 'post',	
			    data: {identificador: identificador},	    
			    //cache: false,
		    	dataType: "json",
    			async:   true,
			    success: function(datos){
				$("#dRutaPerfilPublico").html(datos.rutaPerfilPublico);
				$("#dRutaQrPerfilPublico").html(datos.rutaQrPerfilPublico);
			    },
			    error: function(jqXHR, textStatus, errorThrown){
			    	$("#cargando").delay("slow").fadeOut();
			    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
			    }
			});
			
			setTimeout(function(){
            	$("#estado").html("").removeClass('alerta');
            },1500);
		}
		
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		if($('select[name="etnia"] option:selected').attr("value")!="Indigena"){
			$('[name="indigena"]').attr("disabled","disabled");
		} else{
			$('[name="indigena"]').removeAttr("disabled");
		}
	});  
  	
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		if($('select[name="etnia"] option:selected').attr("value")!="Indigena"){
			$('[name="indigena"]').attr("disabled","disabled");
		} else{
			$('[name="indigena"]').removeAttr("disabled");
		}
	});
	


	$(document).ready(function(){

		$( "#fechaNacimiento" ).datepicker({
		      yearRange: "c-90:c+0",
		      changeMonth: true,
		      changeYear: true
		    });
		cargarValorDefecto("tipoDocumento","<?php echo $fila['tipo_documento']?>");
		cargarValorDefecto("nacionalidad","<?php echo $fila['nacionalidad'];?>");
		cargarValorDefecto("identificacionEtnica","<?php echo $fila['identificacion_etnica'];?>");
		cargarValorDefecto("nacionalidadIndigena","<?php echo $fila['nacionalidad_indigena'];?>");
		cargarValorDefecto("genero","<?php echo $fila['genero'];?>");
		cargarValorDefecto("estadoCivil","<?php echo $fila['estado_civil'];?>");
		cargarValorDefecto("tipoSangre","<?php echo $fila['tipo_sangre'];?>");
		cargarValorDefecto("provincia","<?php echo $fila['id_localizacion_provincia'];?>");
			
		$('select[name="tipoDocumento"]').find('option[value="<?php echo $fila['tipo_documento'];?>"]').prop("selected","selected");
		$('#numero_contrato').ForceNumericOnly();
		$('#numero_notaria').ForceNumericOnly();
		construirValidador();
		abrir($("#datosEmpleado input:hidden"),null,false);
		distribuirLineas();
		calcularEdad();
		scanton = '<option value="">Canton...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			    }
	   		}
	    $('#canton').html(scanton);
	    cargarValorDefecto("canton","<?php echo $fila['id_localizacion_canton'];?>");
		soficina = '<option value="">Parroquia...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	soficina += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#parroquia').html(soficina);
	    cargarValorDefecto("parroquia","<?php echo $fila['id_localizacion_parroquia'];?>");
	    cargarValorDefecto("discapacidad_empleado","<?php echo $fila['tiene_discapacidad'];?>");
	    cargarValorDefecto("representante_discapacitado","<?php echo $fila['representante_familiar_discapacidad'];?>");
	    cargarValorDefecto("enfermedad_catastrofica","<?php echo $fila['tiene_enfermedad_catastrofica'];?>");
	    cargarValorDefecto("nombre_enfermedad_catastrofica","<?php echo $fila['nombre_enfermedad_catastrofica'];?>");
	});
	
</script>

