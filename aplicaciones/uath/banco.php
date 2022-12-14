<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$cc = new ControladorCatastro();
$res = $cc->obtenerDatosBanco($conexion, $_SESSION['usuario']);
$banco = pg_fetch_assoc($res);
?>

<header>
	<h1>Cuenta Bancaria</h1>
</header>

<form id="datosBanco" data-rutaAplicacion="uath" data-opcion="guardarBanco">
	<input type="hidden" id="<?php echo $_SESSION['usuario'];?>" />

	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	<div id="estado"></div>
	<table class="soloImpresion">
	<tr><td>
	</td><td>
	<fieldset>
		<legend>Datos Bancarios</legend>
		<div data-linea="1">
		<label>Institucion</label> 
			<select id="institucion" name="institucion" disabled="disabled">
				<option value="">Seleccione....</option>
				<option value="Banco Amazonas">Banco Amazonas</option>
				<option value="Banco del Austro">Banco del Austro</option>
				<option value="Banco Capital S.A. Corfinsa">Banco Capital S.A. Corfinsa</option>
				<option value="Banco Central">Banco Central</option>
				<option value="Banco Coopnacional">Banco Coopnacional</option>
				<option value="Banco Procredit">Banco Procredit</option>
				<option value="Banco Produbanco">Banco Produbanco</option>
				<option value="Banco Proamerica">Banco Proamerica</option>
				<option value="Banco Bolivariano">Banco Bolivariano</option>
				<option value="Banco Promerica">Banco Promerica</option>
				<option value="Banco Citibank">Banco Citibank</option>
				<option value="Banco del Litoral">Banco del Litoral</option>
				<option value="Banco Ecuatoriano de la Vivienda">Banco Ecuatoriano de la Vivienda</option>
				<option value="Banco del Fomento">Banco del Fomento</option>
				<option value="Banco de Guayaquil">Banco de Guayaquil</option>
				<option value="Banco Internacional">Banco Internacional</option>
				<option value="Banco Lloyds Bank">Banco Lloyds Bank</option>
				<option value="Banco de Loja">Banco de Loja</option>
				<option value="Banco Machala">Banco Machala</option>
				<option value="Banco Mm Jaramillo">Banco Mm Jaramillo</option>
				<option value="Banco de Pac??fico">Banco de Pac??fico</option>
				<option value="Banco de Pichincha">Banco de Pichincha</option>
				<option value="Banco Produbanco">Banco Produbanco</option>
				<option value="Banco Rumi??ahui">Banco Rumi??ahui</option>
				<option value="Banco Solidario">Banco Solidario</option>
				<option value="Banco Sudamericano">Banco Sudamericano</option>
				<option value="Banco Territorial">Banco Territorial</option>
				<option value="Banco Unibanco">Banco Unibanco</option>
				<option value="Banco Para La Asistencia Comunitaria Finca S.A.">Banco Para La Asistencia Comunitaria Finca S.A.</option>
				<option value="Cofiec">Cofiec</option>
				<option value="Comercial de Manab??">Comercial de Manab??</option>
				<option value="Coop. 11 de Junio">Coop. 11 de Junio</option>
				<option value="Coop. 15 de Abril">Coop. 15 de Abril</option>
				<option value="Coop. 29 de Octubre">Coop. 29 de Octubre</option>
				<option value="Coop. Ahorro Vicentina Manuel Esteban Godoy Ortega Ltda.">Coop. Ahorro Vicentina Manuel Esteban Godoy Ortega Ltda.</option>
				<option value="Coop. Ahorro y Cr??dito 4 de Octubre Ltda.">Coop. Ahorro y Cr??dito 4 de Octubre Ltda.</option>
				<option value="Coop. Ahorro y Cr??dito Construcci??n Comercio y Producci??n Ltda.">Coop. Ahorro y Cr??dito Construcci??n Comercio y Producci??n Ltda.</option>
				<option value="Coop. Ahorro y Cr??dito El Sagrario">Coop. Ahorro y Cr??dito El Sagrario</option>
				<option value="Coop. Ahorro y Cr??dito Nacional">Coop. Ahorro y Cr??dito Nacional</option>
				<option value="Coop. Ahorro y Cr??dito Prevenci??n Ahorro y Desarrollo">Coop. Ahorro y Cr??dito Prevenci??n Ahorro y Desarrollo</option>
				<option value="Coop. Ahorro y Cr??dito Progreso">Coop. Ahorro y Cr??dito Progreso</option>
				<option value="Coop. Ahorro y Cr??dito Santa Ana">Coop. Ahorro y Cr??dito Santa Ana</option>
				<option value="Coop. Alianza del Valle Ltda.">Coop. Alianza del Valle Ltda.</option>
				<option value="Coop. Andaluc??a">Coop. Andaluc??a</option>
				<option value="Coop. Atuntaqui">Coop. Atuntaqui</option>
				<option value="Coop. Cacpeco">Coop. Cacpeco</option>
				<option value="Coop. Calceta Ltda.">Coop. Calceta Ltda.</option>
				<option value="Coop. C??mara de Comercio Ambato">Coop. C??mara de Comercio Ambato</option>
				<option value="Coop. Ambato">Coop. Ambato</option>
				<option value="Coop. C??mara de Comercio Quito">Coop. C??mara de Comercio Quito</option>
				<option value="Coop. Chone Ltda.">Coop. Chone Ltda.</option>
				<option value="Coop. Comercio Ltda. Portoviejo">Coop. Comercio Ltda. Portoviejo</option>
				<option value="Coop. Cotocollao">Coop. Cotocollao</option>
				<option value="Coop. de Ahorro y Credito 11 de Junio">Coop. de Ahorro y Credito 11 de Junio</option>
				<option value="Coop. de Ahorro y Credito 15 de Abril">Coop. de Ahorro y Credito 15 de Abril</option>
				<option value="Coop. de Credito 15 de Abril">Coop. de Credito 15 de Abril</option>
				<option value="Coop. de Ahorro y Credito 23 de Julio">Coop. de Ahorro y Credito 23 de Julio</option>
				<option value="Coop. de Ahorro y Credito 9 de Octubre">Coop. de Ahorro y Credito 9 de Octubre</option>
				<option value="Coop. de Ahorro y Credito Codesarrollo">Coop. de Ahorro y Credito Codesarrollo</option>
				<option value="Coop. de Ahorro y Credito Comercio">Coop. de Ahorro y Credito Comercio</option>
				<option value="Coop. de Ahorro y Credito Jardin Azuayo">Coop. de Ahorro y Credito Jardin Azuayo</option>
				<option value="Coop. de Ahorro y Credito Manuel Esteban Godoy Ortega">Coop. de Ahorro y Credito Manuel Esteban Godoy Ortega</option>
				<option value="Coop. de Ahorro y Credito Padre Julian Lorente">Coop. de Ahorro y Credito Padre Julian Lorente</option>
				<option value="Coop. de Ahorro y Credito Progreso">Coop. de Ahorro y Credito Progreso</option>
				<option value="Coop. de Ahorro y Credito San Francisco De Asis">Coop. de Ahorro y Credito San Francisco De Asis</option>
				<option value="Coop. de Ahorro y Credito San Jose">Coop. de Ahorro y Credito San Jose</option>
				<option value="Coop. de Ahorro y Credito Santa Rosa">Coop. de Ahorro y Credito Santa Rosa</option>
				<option value="Coop. Guaranda">Coop. Guaranda</option>
				<option value="Coop. Jes??s del Gran Poder">Coop. Jes??s del Gran Poder</option>
				<option value="Coop. Juventud Ecuatoriana Progresista Ltda.">Coop. Juventud Ecuatoriana Progresista Ltda.</option>
				<option value="Coop. La Dolorosa">Coop. La Dolorosa</option>
				<option value="Coop. Mego">Coop. Mego</option>
				<option value="Coop. Oscus">Coop. Oscus</option>
				<option value="Coop. Pablo Mu??oz Vega">Coop. Pablo Mu??oz Vega</option>
				<option value="Coop. Peque??a Empresa de Pastaza">Coop. Peque??a Empresa de Pastaza</option>
				<option value="Coop. Riobamba">Coop. Riobamba</option>
				<option value="Coop. San Francisco de As??s">Coop. San Francisco de As??s</option>
				<option value="Coop. San Jose">Coop. San Jose</option>
				<option value="Coop. San Jos?? de Chilcapamba">Coop. San Jos?? de Chilcapamba</option>
				<option value="Coop. Tulc??n">Coop. Tulc??n</option>
				<option value="Cooperativa Coopera">Cooperativa Coopera</option>
				<option value="Cooperativa de Ahorro y Credito de la Peque??a Empresa de Loja Cacpe">Cooperativa de Ahorro y Credito de la Peque??a Empresa de Loja Cacpe</option>
				<option value="Cooperativa de Ahorro y Cr??dito San Pedro de Taboada">Cooperativa de Ahorro y Cr??dito San Pedro de Taboada</option>
				<option value="Cooperativa Juventud Ecuatoriana Progresista">Cooperativa Juventud Ecuatoriana Progresista</option>
				<option value="Cooperativa Polic??a Nacional">Cooperativa Polic??a Nacional</option>
				<option value="Coopertiva de Ahorro y Credito San Francisco Limitada">Coopertiva de Ahorro y Credito San Francisco Limitada</option>
				<option value="Cooprogreso">Cooprogreso</option>
				<option value="Cooperativa de Ahorro y Cr??dito Alfonso Jaramillo Arteaga">Cooperativa de Ahorro y Cr??dito Alfonso Jaramillo Arteaga</option>
				<option value="Cooperativa de Ahorro y Cr??dito Peque??os Empresarios Zamora">Cooperativa de Ahorro y Cr??dito Peque??os Empresarios Zamora</option>
				<option value="Coopac Cooperativa de Ahorro y Cr??dito Campesina Ltda.">Coopac Cooperativa de Ahorro y Cr??dito Campesina Ltda.</option>
				<option value="Cooperativa de Ahorro y Cr??dito Tena Ltda.">Cooperativa de Ahorro y Cr??dito Tena Ltda.</option>
				<option value="Cooperativa de Ahorro y Cr??dito Santa Anita Ltda.">Cooperativa de Ahorro y Cr??dito Santa Anita Ltda.</option>
				<option value="Cooperativa de Ahorro y Cr??dito Mushuc Runa">Cooperativa de Ahorro y Cr??dito Mushuc Runa</option>
				<option value="Cooperativa de Ahorro y Cr??dito Gal??pagos Ltda">Cooperativa de Ahorro y Cr??dito Gal??pagos Ltda</option>
				<option value="Cooperativa de Ahorro y Cr??dito Educadores de Pastaza Ltda">Cooperativa de Ahorro y Cr??dito Educadores de Pastaza Ltda</option>
				<option value="Cooperativa de Ahorro y Cr??dito Acci??n Rural">Cooperativa de Ahorro y Cr??dito Acci??n Rural</option>
				<option value="Cooperativa  de ahorro y Cr??dito OOSCUS">Cooperativa  de ahorro y Cr??dito OOSCUS</option>				
				<option value="Cooperativa de Ahorro y Credito Cacpe Biblian Limitada">Cooperativa de Ahorro y Credito Cacpe Biblian Limitada</option>
				<option value="Cooperativa de Ahorro y Credito La Merced Ltda.">Cooperativa de Ahorro y Credito La Merced Ltda.</option>
				<option value="Cooperativa Crea Ltda">Cooperativa Crea Ltda</option>
				<option value="Cooperativa de Ahorro y Cr??dito Malchingui Ltda">Cooperativa de Ahorro y Cr??dito Malchingui Ltda.</option>
				<option value="Delbank S. A.">Delbank S. A.</option>
				<option value="Mutualista Ambato">Mutualista Ambato</option>
				<option value="Mutualista Azuay">Mutualista Azuay</option>
				<option value="Mutualista Imbabura">Mutualista Imbabura</option>
				<option value="Mutualista Pichincha">Mutualista Pichincha</option>
			</select>
		</div>
		<div data-linea="2"> 
		<label>Tipo de Cuenta</label>
			<select id="tipo_cuenta" name="tipo_cuenta"	disabled="disabled">
				<option value="">Seleccione....</option>
				<option value="Ahorros">Ahorros</option>
				<option value="Corriente">Corriente</option>
			</select>
		</div>
		
		<div data-linea="2">
		<label>N??mero de Cuenta</label>
			<input type="text" id="numero_cuenta" name="numero_cuenta" value="<?php echo $banco['numero_cuenta'];?>" disabled="disabled" data-er="^[0-9]+$" title="Ejemplo: 999555444"/>
		</div>
				
	</fieldset>
	</td></tr></table>
</form>

<script type="text/javascript">

	$(document).ready(function(){
		cargarValorDefecto("tipo_cuenta","<?php echo $banco['tipo_cuenta'];?>");
		cargarValorDefecto("institucion","<?php echo $banco['institucion'];;?>");
		distribuirLineas();
		construirValidador();
	});

	$("#datosBanco").submit(function(event){
		event.preventDefault();
		chequearCampos(this);
	});
  
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#institucion").val()) || !esCampoValido("#institucion")){
			error = true;
			$("#institucion").addClass("alertaCombo");
		}

		if(!$.trim($("#tipo_cuenta").val()) || !esCampoValido("#tipo_cuenta")){
			error = true;
			$("#tipo_cuenta").addClass("alertaCombo");
		}

		if(!$.trim($("#numero_cuenta").val()) || !esCampoValido("#numero_cuenta")){
			error = true;
			$("#numero_cuenta").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la informaci??n ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			$("#estado").html("Los datos han sido actualizados satisfactoriamente.").addClass('correcto');
			$("input").attr("disabled","disabled");
			$("select").attr("disabled","disabled");
			$("#modificar").removeAttr("disabled");
			$("#actualizar").attr("disabled","disabled");
		}
	}

	
</script>
