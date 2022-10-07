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
				<option value="Banco de Pacífico">Banco de Pacífico</option>
				<option value="Banco de Pichincha">Banco de Pichincha</option>
				<option value="Banco Produbanco">Banco Produbanco</option>
				<option value="Banco Rumiñahui">Banco Rumiñahui</option>
				<option value="Banco Solidario">Banco Solidario</option>
				<option value="Banco Sudamericano">Banco Sudamericano</option>
				<option value="Banco Territorial">Banco Territorial</option>
				<option value="Banco Unibanco">Banco Unibanco</option>
				<option value="Banco Para La Asistencia Comunitaria Finca S.A.">Banco Para La Asistencia Comunitaria Finca S.A.</option>
				<option value="Cofiec">Cofiec</option>
				<option value="Comercial de Manabí">Comercial de Manabí</option>
				<option value="Coop. 11 de Junio">Coop. 11 de Junio</option>
				<option value="Coop. 15 de Abril">Coop. 15 de Abril</option>
				<option value="Coop. 29 de Octubre">Coop. 29 de Octubre</option>
				<option value="Coop. Ahorro Vicentina Manuel Esteban Godoy Ortega Ltda.">Coop. Ahorro Vicentina Manuel Esteban Godoy Ortega Ltda.</option>
				<option value="Coop. Ahorro y Crédito 4 de Octubre Ltda.">Coop. Ahorro y Crédito 4 de Octubre Ltda.</option>
				<option value="Coop. Ahorro y Crédito Construcción Comercio y Producción Ltda.">Coop. Ahorro y Crédito Construcción Comercio y Producción Ltda.</option>
				<option value="Coop. Ahorro y Crédito El Sagrario">Coop. Ahorro y Crédito El Sagrario</option>
				<option value="Coop. Ahorro y Crédito Nacional">Coop. Ahorro y Crédito Nacional</option>
				<option value="Coop. Ahorro y Crédito Prevenciòn Ahorro y Desarrollo">Coop. Ahorro y Crédito Prevenciòn Ahorro y Desarrollo</option>
				<option value="Coop. Ahorro y Crédito Progreso">Coop. Ahorro y Crédito Progreso</option>
				<option value="Coop. Ahorro y Crédito Santa Ana">Coop. Ahorro y Crédito Santa Ana</option>
				<option value="Coop. Alianza del Valle Ltda.">Coop. Alianza del Valle Ltda.</option>
				<option value="Coop. Andalucía">Coop. Andalucía</option>
				<option value="Coop. Atuntaqui">Coop. Atuntaqui</option>
				<option value="Coop. Cacpeco">Coop. Cacpeco</option>
				<option value="Coop. Calceta Ltda.">Coop. Calceta Ltda.</option>
				<option value="Coop. Cámara de Comercio Ambato">Coop. Cámara de Comercio Ambato</option>
				<option value="Coop. Ambato">Coop. Ambato</option>
				<option value="Coop. Cámara de Comercio Quito">Coop. Cámara de Comercio Quito</option>
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
				<option value="Coop. Jesús del Gran Poder">Coop. Jesús del Gran Poder</option>
				<option value="Coop. Juventud Ecuatoriana Progresista Ltda.">Coop. Juventud Ecuatoriana Progresista Ltda.</option>
				<option value="Coop. La Dolorosa">Coop. La Dolorosa</option>
				<option value="Coop. Mego">Coop. Mego</option>
				<option value="Coop. Oscus">Coop. Oscus</option>
				<option value="Coop. Pablo Muñoz Vega">Coop. Pablo Muñoz Vega</option>
				<option value="Coop. Pequeña Empresa de Pastaza">Coop. Pequeña Empresa de Pastaza</option>
				<option value="Coop. Riobamba">Coop. Riobamba</option>
				<option value="Coop. San Francisco de Asís">Coop. San Francisco de Asís</option>
				<option value="Coop. San Jose">Coop. San Jose</option>
				<option value="Coop. San José de Chilcapamba">Coop. San José de Chilcapamba</option>
				<option value="Coop. Tulcán">Coop. Tulcán</option>
				<option value="Cooperativa Coopera">Cooperativa Coopera</option>
				<option value="Cooperativa de Ahorro y Credito de la Pequeña Empresa de Loja Cacpe">Cooperativa de Ahorro y Credito de la Pequeña Empresa de Loja Cacpe</option>
				<option value="Cooperativa de Ahorro y Crédito San Pedro de Taboada">Cooperativa de Ahorro y Crédito San Pedro de Taboada</option>
				<option value="Cooperativa Juventud Ecuatoriana Progresista">Cooperativa Juventud Ecuatoriana Progresista</option>
				<option value="Cooperativa Policía Nacional">Cooperativa Policía Nacional</option>
				<option value="Coopertiva de Ahorro y Credito San Francisco Limitada">Coopertiva de Ahorro y Credito San Francisco Limitada</option>
				<option value="Cooprogreso">Cooprogreso</option>
				<option value="Cooperativa de Ahorro y Crédito Alfonso Jaramillo Arteaga">Cooperativa de Ahorro y Crédito Alfonso Jaramillo Arteaga</option>
				<option value="Cooperativa de Ahorro y Crédito Pequeños Empresarios Zamora">Cooperativa de Ahorro y Crédito Pequeños Empresarios Zamora</option>
				<option value="Coopac Cooperativa de Ahorro y Crédito Campesina Ltda.">Coopac Cooperativa de Ahorro y Crédito Campesina Ltda.</option>
				<option value="Cooperativa de Ahorro y Crédito Tena Ltda.">Cooperativa de Ahorro y Crédito Tena Ltda.</option>
				<option value="Cooperativa de Ahorro y Crédito Santa Anita Ltda.">Cooperativa de Ahorro y Crédito Santa Anita Ltda.</option>
				<option value="Cooperativa de Ahorro y Crédito Mushuc Runa">Cooperativa de Ahorro y Crédito Mushuc Runa</option>
				<option value="Cooperativa de Ahorro y Crédito Galápagos Ltda">Cooperativa de Ahorro y Crédito Galápagos Ltda</option>
				<option value="Cooperativa de Ahorro y Crédito Educadores de Pastaza Ltda">Cooperativa de Ahorro y Crédito Educadores de Pastaza Ltda</option>
				<option value="Cooperativa de Ahorro y Crédito Acción Rural">Cooperativa de Ahorro y Crédito Acción Rural</option>
				<option value="Cooperativa  de ahorro y Crédito OOSCUS">Cooperativa  de ahorro y Crédito OOSCUS</option>				
				<option value="Cooperativa de Ahorro y Credito Cacpe Biblian Limitada">Cooperativa de Ahorro y Credito Cacpe Biblian Limitada</option>
				<option value="Cooperativa de Ahorro y Credito La Merced Ltda.">Cooperativa de Ahorro y Credito La Merced Ltda.</option>
				<option value="Cooperativa Crea Ltda">Cooperativa Crea Ltda</option>
				<option value="Cooperativa de Ahorro y Crédito Malchingui Ltda">Cooperativa de Ahorro y Crédito Malchingui Ltda.</option>
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
		<label>Número de Cuenta</label>
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
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
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
