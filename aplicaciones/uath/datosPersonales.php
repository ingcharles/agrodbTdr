<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEmpleados.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$ce = new ControladorEmpleados();
$cc = new ControladorCatalogos();
$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
$res = $ce->obtenerFichaEmpleado($conexion, $_SESSION['usuario']);
$empleado = pg_fetch_assoc($res);
?>

<header>
	<h1>Datos personales</h1>
</header>

<form id="datosPersonales" data-rutaAplicacion="uath" data-opcion="guardarDatosPersonales">

	<input type="hidden" id="<?php echo $_SESSION['usuario'];?>" data-rutaAplicacion="uath" data-opcion="mostrarFoto" data-destino="fotografia" />

	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar"
			disabled="disabled">Actualizar</button>
	</p>
	<div id="estado"></div>
	<!-- table class="soloImpresion">
		<tr>
			<td-->
				<fieldset>
					<legend>Fotografía</legend>
					<div id="fotografia"></div>
				</fieldset>
			<!-- /td>
			<td-->
				<fieldset>
					<legend>Información básica</legend>


					<div data-linea="1">
						<label>Nombres</label> <input type="text" name="nombre"
							value="<?php echo $empleado['nombre'];?>" disabled="disabled" />
					</div>
					<div data-linea="1">
						<label>Apellidos</label> <input type="text" name="apellido"
							value="<?php echo $empleado['apellido'];?>" disabled="disabled" />
					</div>
					<div data-linea="2">
						<label>Fecha Nacimiento</label> <input type="text"
							id="nacimiento" name="nacimiento"
							value="<?php echo $empleado['fecha_nacimiento']==""?"":date('j/n/Y',strtotime($empleado['fecha_nacimiento']));?>"
							disabled="disabled" />
					</div>
					<hr>
					<div data-linea="2">
						<label>Lugar de nacimiento</label> 
					</div><br>
					<div data-linea="3">
						<label>Provincia</label>
								<select id="provinciaNacimiento" name="provinciaNacimiento" disabled="disabled" >
									<option value="">Provincia....</option>
										<?php 	
											$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
											foreach ($provincias as $provincia){
												echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
											}
										?>
								</select> 
								
						<input type="hidden" id="nombreProvinciaNacimiento" name="nombreProvinciaNacimiento" />
				
					</div><div data-linea="3">
	
						<label>Canton</label>
							<select id="cantonNacimiento" name="cantonNacimiento" disabled="disabled" >
							</select>
						<input type="hidden" id="nombreCantonNacimiento" name="nombreCantonNacimiento" />
				
				</div>
				<div data-linea="4">
				
						<label>Parroquia</label>
							<select id="parroquiaNacimiento" name="parroquiaNacimiento" disabled="disabled">
							</select>
						<input type="hidden" id="nombreParroquiaNacimiento" name="nombreParroquiaNacimiento" />
							
					</div>
					<hr>
					<div data-linea="5">
						<label>Edad</label> 
							<input type="text" id="edad" name="edad" value="<?php echo $empleado['edad'];?>" readonly="readonly" />
					</div>
					<div data-linea="5">
						<label>Tipo de documento</label> 
								<select name="tipoDocumento" id="tipoDocumento" disabled="disabled">
								<option value="" >Seleccione....</option>
								<option value="Cedula">Cédula</option>
								<option value="Pasaporte">Pasaporte</option>
						   </select>
					</div>
					<div data-linea="6">
						<label>No.</label> 
							<input type="text" id="identificador" name="identificador" value="<?php echo $empleado['identificador'];?>" readonly="readonly" />
					</div>
					
					<div data-linea="6">
						<label>Sexo</label> <select id="sexo" name="sexo" disabled="disabled">
							<option value="Femenino">Femenino</option>
							<option value="Masculino">Masculino</option>
						</select>
					</div>
					
					
					<div data-linea="7">
						<label>Tipo sanguineo</label> <select name="sangre"
							disabled="disabled">
							<option value="A+">A+</option>
							<option value="A-">A-</option>
							<option value="B+">B+</option>
							<option value="B-">B-</option>
							<option value="AB+">AB+</option>
							<option value="AB-">AB-</option>
							<option value="O+" selected="selected">O+</option>
							<option value="O-">O-</option>
						</select>

					</div>
					
					<div data-linea="7">
						<label>Estado civil</label> <select name="estadoCivil"
							disabled="disabled">
							<option value="" >Seleccione....</option>
								<option value="Casado(a)">Casado(a)</option>
								<option value="Soltero(a)">Soltero(a)</option>
								<option value="Divorciado(a)">Divorciado(a)</option>
								<option value="Viudo(a)">Viudo(a)</option>
								<option value="UnionLibre">Unión libre</option>
						</select>
					</div>
					
					
					<div data-linea="8">
						<label>Nacionalidad</label> <select name="nacionalidad"
							disabled="disabled">
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
				<div data-linea="8">
						<label>Jornada Laboral</label> <select name="jornadaLaboral" id="jornadaLaboral"
							disabled="disabled">
							<option value="" >Seleccione....</option>
								<option value="Diurna">Diurna</option>
								<option value="Vespertina">Vespertina</option>
								<option value="Rotatoria">Rotatoria</option>
						</select>
					</div>
					<div data-linea="9">
						<label>Religión</label> <select name="religion" id="religion"
							disabled="disabled">
							<option value="" >Seleccione....</option>
								<option value="Católica">Católica</option>
								<option value="Evangélica">Evangélica</option>
								<option value="Testigos de Jehová">estigos de Jehová</option>
								<option value="Mormona">Mormona</option>
								<option value="Otras">Otras</option>
						</select>
					</div>
					<div data-linea="9">
						<label>Orientación Sexual</label> <select name="orientacionSexual" id="orientacionSexual"
							disabled="disabled">
							<option value="" >Seleccione....</option>
								<option value="Lesbiana">Lesbiana</option>
								<option value="Gay">Gay</option>
								<option value="Bisexual">Bisexual</option>
								<option value="Heterosexual">Heterosexual</option>
								<option value="No sabe / no responde">No sabe / no responde</option>
						</select>
					</div>
					<div data-linea="10">
						<label>Lateralidad</label> <select name="lateralidad" id="lateralidad"
							disabled="disabled">
							<option value="" >Seleccione....</option>
								<option value="Diestro">Diestro</option>
								<option value="Zurdo">Zurdo</option>
								<option value="Ambidiestro">Ambidiestro</option>
						</select>
					</div>
					<div data-linea="10" id="campoLibretaMilitar">
						<label>Nro. Libreta Militar</label> 
						<input type="text" id="libretaMilitar" name="libretaMilitar" value="<?php echo $empleado['libreta_militar'];?>" maxlength=15 disabled/>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>Información étnica</legend>
						
					<div data-linea="1">
					<label>Identificación étnica</label> <select name="etnia"
						disabled="disabled">
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
						name="indigena" disabled="disabled">
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
				<legend>Dirección Domiciliaria</legend>
				<div data-linea="5">
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
				
					</div><div data-linea="6">
	
						<label>Canton</label>
							<select id="canton" name="canton" disabled="disabled" >
							</select>
						<input type="hidden" id="nombreCanton" name="nombreCanton" />
				
				</div>
				<div data-linea="6">
				
						<label>Parroquia</label>
							<select id="parroquia" name="parroquia" disabled="disabled">
							</select>
						<input type="hidden" id="nombreParroquia" name="nombreParroquia" />
							
					</div>
				<div data-linea="7">
					    <label>Dirección domiciliaria</label> 
							<input type="text" name="domicilio" value="<?php echo $empleado['domicilio'];?>" disabled="disabled" />			
				</div>			
				<div data-linea="8">			
						<label>Teléfono convencional</label> 
							<input type="text" name="convencional" value="<?php echo $empleado['convencional'];?>" disabled="disabled" 
							placeholder="Ej. (02) 227-2345" data-inputmask="'mask': '(99) 999-9999'"
							data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}" size="15" />			
				</div>				
				
				<div data-linea="8">			
						<label>Teléfono celular</label> 
							<input type="text" name="celular" value="<?php echo $empleado['celular'];?>" disabled="disabled"
							placeholder="Ej. (09) 9988-8899" data-inputmask="'mask': '(09) 9999-9999'"
							data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" title="(09) 9988-8899" size="15" />			
					</div>
				<div data-linea="9">
					    <label>E-mail personal</label> 
							<input type="text" name="mailPersonal" value="<?php echo $empleado['mail_personal'];?>" disabled="disabled" />			
				</div>			
				
				</fieldset>
				
				
				
				<fieldset>
					<legend>Información de contacto (Agrocalidad)</legend>
						
					<div data-linea="1">
					    <label>E-mail institucional</label> 
							<input type="text" name="mailInstitucional" value="<?php echo $empleado['mail_institucional'];?>" disabled="disabled" />
					</div>
					<div data-linea="2">					
						<label>Teléfono institucional</label> 
							<input type="text" name="telefonoInstitucional" id="telefonoInstitucional" value="<?php echo $empleado['telefono_institucional'];?>" disabled="disabled" maxlength=10/>			
					</div>
					<div data-linea="3">					
						<label>Extensión</label> 
							<input type="text" name="extension" value="<?php echo $empleado['extension_magap'];?>" disabled="disabled" />			
					</div>
				</fieldset>
				
				<fieldset>
					<legend>Información de discapacidad</legend>
						
					<div data-linea="1">
					    <label>Tiene discapacidad?</label> 
						<select
						name="discapacidad_empleado" id="discapacidad_empleado" disabled="disabled" >
						<option value="NO">No</option>
						<option value="SI">Si</option>
						</select>		
					</div>
					<div data-linea="1">
					    <label>No. Carnet</label> 
						<input type="text" name="carnet_conadis_empleado" id="carnet_conadis_empleado" disabled="disabled" value="<?php echo $empleado['carnet_conadis_empleado'];?>" disabled="disabled" />
					</div>
					
					<div data-linea="2">
					    <label>Es representante de persona con discapacidad?</label> 
						<select
						name="representante_discapacitado" id="representante_discapacitado" disabled="disabled">
						<option value="NO">No</option>
						<option value="SI">Si</option>
						</select>		
					</div>
					<div data-linea="3">
					    <label>No. Carnet de la persona con discapacidad</label> 
						<input type="text" name="carnet_conadis_familiar" id="carnet_conadis_familiar" disabled="disabled" value="<?php echo $empleado['carnet_conadis_familiar'];?>" disabled="disabled" />
					</div>
					<div data-linea="4">
					    <label>Tiene enfermedad catastrófica?</label> 
						<select
						name="enfermedad_catastrofica" id="enfermedad_catastrofica">
						<option value="NO">No</option>
						<option value="SI">Si</option>
						</select>	
					</div>
					<div data-linea="5">
					    <label>Nombre enfermedad catastrófica</label> 
						<select
						name="nombre_enfermedad_catastrofica" id="nombre_enfermedad_catastrofica">
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
					
				
				
				<!--/td>
		</tr>
	</table-->
</form>

<script type="text/javascript">

      var array_canton= <?php echo json_encode($cantones); ?>;
      var array_parroquia= <?php echo json_encode($parroquias); ?>;
      var genero = <?php echo json_encode($empleado['genero']); ?>;

      $("#provincia").change(function(){

	 $('#nombreProvincia').val($("#provincia option:selected").text());
	 
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
									
    $("#provinciaNacimiento").change(function(){

   	 $('#nombreProvinciaNacimiento').val($("#provinciaNacimiento option:selected").text());
   	 
      	  	scanton = '<option value="">Canton...</option>';
   	    for(var i=0;i<array_canton.length;i++){
   		    if ($("#provinciaNacimiento").val()==array_canton[i]['padre']){
   		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
   			    }
   	   		}
   	    $('#cantonNacimiento').html(scanton);
   	    $("#cantonNacimiento").removeAttr("disabled");
   	});

       $("#cantonNacimiento").change(function(){

   	    	 $('#nombreCantonNacimiento').val($("#cantonNacimiento option:selected").text());
   			soficina ='0';
   			soficina = '<option value="">Parroquia...</option>';
   		    for(var i=0;i<array_parroquia.length;i++){
   			    if ($("#cantonNacimiento").val()==array_parroquia[i]['padre']){
   			    	soficina += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
   				    } 
   		    	}
   		    $('#parroquiaNacimiento').html(soficina);
   			$("#parroquiaNacimiento").removeAttr("disabled");
   		});
  		
	$("#datosPersonales").submit(function(event){
		event.preventDefault();
		var error=false;
		
		$(".alertaCombo").removeClass("alertaCombo");
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
		if($("#provinciaNacimiento").val()==""){
			error = true;
			$("#provinciaNacimiento").addClass("alertaCombo");
		}
		if($("#cantonNacimiento").val()==""){
			error = true;
			$("#cantonNacimiento").addClass("alertaCombo");
		}
		if($("#parroquiaNacimiento").val()==""){
			error = true;
			$("#parroquiaNacimiento").addClass("alertaCombo");
		}
		if($("#jornadaLaboral").val()==""){
			error = true;
			$("#jornadaLaboral").addClass("alertaCombo");
		}
		if($("#religion").val()==""){
			error = true;
			$("#religion").addClass("alertaCombo");
		}
		if($("#orientacionSexual").val()==""){
			error = true;
			$("#orientacionSexual").addClass("alertaCombo");
		}
		if($("#lateralidad").val()==""){
			error = true;
			$("#lateralidad").addClass("alertaCombo");
		}
		if($("#nacimiento").val()==""){
			error = true;
			$("#nacimiento").addClass("alertaCombo");
		}
		if($("#estadoCivil").val()==""){
			error = true;
			$("#estadoCivil").addClass("alertaCombo");
		}
		if($("#discapacidad_empleado").val()=="SI" && $("#carnet_conadis_empleado").val()==""){
			error = true;
			$("#carnet_conadis_empleado").addClass("alertaCombo");
		}
		if($("#representante_discapacitado").val()=="SI" && $("#carnet_conadis_familiar").val()==""){
			error = true;
			$("#carnet_conadis_familiar").addClass("alertaCombo");
		}
		if($("#enfermedad_catastrofica").val()=="SI" && $("#nombre_enfermedad_catastrofica").val()==""){
			error = true;
			$("#nombre_enfermedad_catastrofica").addClass("alertaCombo");
		}
		if($("#telefonoInstitucional").val()==""){
			error = true;
			$("#telefonoInstitucional").addClass("alertaCombo");
		}
		if (!error){
			ejecutarJson($(this));
			setTimeout(function(){
            	$("#estado").html("").removeClass('alerta');
            },1500);
		}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
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
		$('select[name="sexo"]').find('option[value="<?php echo $empleado['genero'];?>"]').prop("selected","selected");
		cargarValorDefecto("sexo","<?php echo $empleado['genero'];?>");
		cargarValorDefecto("estadoCivil","<?php echo $empleado['estado_civil'];?>");
		cargarValorDefecto("sangre","<?php echo $empleado['tipo_sangre'];?>");
		cargarValorDefecto("nacionalidad","<?php echo $empleado['nacionalidad'];?>");
		cargarValorDefecto("etnia","<?php echo $empleado['identificacion_etnica'];?>");
		cargarValorDefecto("indigena","<?php echo $empleado['nacionalidad_indigena'];?>");
		cargarValorDefecto("provincia","<?php echo $empleado['id_localizacion_provincia'];?>");
		cargarValorDefecto("tipoDocumento","<?php echo $empleado['tipo_documento'];?>");
		
		cargarValorDefecto("discapacidad_empleado","<?php echo $empleado['tiene_discapacidad'];?>");
		cargarValorDefecto("representante_discapacitado","<?php echo $empleado['representante_familiar_discapacidad'];?>");
		cargarValorDefecto("enfermedad_catastrofica","<?php echo $empleado['tiene_enfermedad_catastrofica'];?>");
		cargarValorDefecto("nombre_enfermedad_catastrofica","<?php echo $empleado['nombre_enfermedad_catastrofica'];?>");

		cargarValorDefecto("provinciaNacimiento","<?php echo $empleado['provincia_nacimiento'];?>");
		cargarValorDefecto("jornadaLaboral","<?php echo $empleado['jornada_laboral'];?>");
		cargarValorDefecto("religion","<?php echo $empleado['religion'];?>");
		cargarValorDefecto("orientacionSexual","<?php echo $empleado['orientacion_sexual'];?>");
		cargarValorDefecto("lateralidad","<?php echo $empleado['lateralidad'];?>");
		
		$( "#nacimiento" ).datepicker({
		      changeMonth: true,
		      changeYear: true,
		      yearRange: '-100:+0'
		    });
		abrir($("#datosPersonales input:hidden"),null,false);
		distribuirLineas();
		scanton = '<option value="">Canton...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			    }
	   		}
	    $('#canton').html(scanton);
	    cargarValorDefecto("canton","<?php echo $empleado['id_localizacion_canton'];?>");
		soficina = '<option value="">Parroquia...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	soficina += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#parroquia').html(soficina);
	    cargarValorDefecto("parroquia","<?php echo $empleado['id_localizacion_parroquia'];?>");


	    scanton = '<option value="">Canton...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provinciaNacimiento").val()==array_canton[i]['padre']){
		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			    }
	   		}
	    $('#cantonNacimiento').html(scanton);
	    cargarValorDefecto("cantonNacimiento","<?php echo $empleado['canton_nacimiento'];?>");
		soficina = '<option value="">Parroquia...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#cantonNacimiento").val()==array_parroquia[i]['padre']){
		    	soficina += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#parroquiaNacimiento').html(soficina);
	    cargarValorDefecto("parroquiaNacimiento","<?php echo $empleado['parroquia_nacimiento'];?>");

	    calcularEdad();
	    //****************verificar genero*********************************
	    if(genero == 'Masculino'){
	    	$("#campoLibretaMilitar").show();
		 }else{
			 $("#campoLibretaMilitar").hide();
		    }
	});

	$('select[name="etnia"]').change(function(){
		if($('select[name="etnia"] option:selected').attr("value")!="Indigena"){
			cargarValorDefecto($('[name="indigena"] option'),"No aplica");
			$('[name="indigena"]').attr("disabled","disabled");
		} else{
			$('[name="indigena"]').removeAttr("disabled");
		}
	});

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
	//***********habilitar libreta militar************
	 $("#sexo").change(function(){
		if($(this).val() == 'Masculino'){
			$("#campoLibretaMilitar").show();
		 }else{
			 $("#campoLibretaMilitar").hide();
			 }
	    	
		});
	
</script>
