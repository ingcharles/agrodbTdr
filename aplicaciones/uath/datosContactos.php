<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$ce = new ControladorEmpleados();
$res = $ce->obtenerDatosPersonales($conexion, $_SESSION['usuario']);
$empleado = pg_fetch_assoc($res);
?>

<header>
	<h1>Familiares y Contactos</h1>
</header>

<form id="datosContactos" data-rutaAplicacion="uath" data-opcion="guardarDatosContactos">
	<input type="hidden" id="<?php echo $_SESSION['usuario'];?>" />
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	<div id="estado"></div>
	<table class="soloImpresion">
	<tr><td></td>
	<td>
	<fieldset>
		<legend>Información básica</legend>
		<label>Nombres</label> 
			<input type="text" name="nombre" value="<?php echo $empleado['nombre'];?>" disabled="disabled" /> <label>Apellidos</label>
			<input type="text" name="apellido" 	value="<?php echo $empleado['apellido'];?>" disabled="disabled" /> <label>Sexo</label>
		<select name="sexo" disabled="disabled">
			<option value="Femenino">Femenino</option>
			<option value="Masculino">Masculino</option>
		</select> 
		<label>Estado civil</label> 
			<select name="estadoCivil"	disabled="disabled">
				<option value="Soltero" selected="selected">Soltero</option>
				<option value="Casado">Casado</option>
				<option value="Unión libre">Unión libre</option>
				<option value="Divorcioado">Divorcioado</option>
				<option value="Viudo">Viudo</option>
			</select> 
		<label>Fecha de nacimiento</label> 
			<input type="text"	id="nacimiento" name="nacimiento"	value="<?php echo date('j/n/Y',strtotime($empleado['fecha_nacimiento']));?>" disabled="disabled" /> 
		<label>Tipo sanguineo</label> 
			<select	name="sangre" disabled="disabled">
				<option value="A+">A+</option>
				<option value="A-">A-</option>
				<option value="B+">B+</option>
				<option value="B-">B-</option>
				<option value="AB+">AB+</option>
				<option value="AB-">AB-</option>
				<option value="O+" selected="selected">O+</option>
				<option value="O-">O-</option>
			</select> 
		<label>Nacionalidad</label> 
			<select name="nacionalidad"	disabled="disabled">
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
	</fieldset>
	<fieldset>
		<legend>Información étnica</legend>
		<label>Identificación étnica</label> 
			<select name="etnia" disabled="disabled">
				<option value="Afroecuatoriano">Afroecuatoriano</option>
				<option value="Blanco">Blanco</option>
				<option value="Indigena">Indigena</option>
				<option value="Mestizo">Mestizo</option>
				<option value="Montubio">Montubio</option>
				<option value="Mulato">Mulato</option>
				<option value="Negro">Negro</option>
				<option value="Otros">Otros</option>
			</select> 
		<label>Nacionalidad indigena</label> 
			<select name="indigena"	disabled="disabled">
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
	</fieldset>
	</td></tr></table>
</form>

<script type="text/javascript">

	$("#datosPersonales").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
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
		$( "#nacimiento" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		abrir($("#datosPersonales input:hidden"),null,false);
	});

	$('select[name="etnia"]').change(function(){
		if($('select[name="etnia"] option:selected').attr("value")!="Indigena"){
			cargarValorDefecto($('[name="indigena"] option'),"No aplica");
			$('[name="indigena"]').attr("disabled","disabled");
		} else{
			$('[name="indigena"]').removeAttr("disabled");
		}
	});
</script>
