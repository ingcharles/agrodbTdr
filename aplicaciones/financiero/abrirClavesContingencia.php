<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';

$conexion = new Conexion();
$cf = new ControladorFinanciero();

$res = $cf->abrirClavesContingencia($conexion, $_POST['id']);
$listaClaves = pg_fetch_assoc($res);

$identificadorUsuario = $_SESSION['usuario'];

$fechaVigente = pg_fetch_assoc($cf->obtenerFechasContigenciaVigentes($conexion));

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Claves de contingencia</h1>
</header>

	<div id="estado"></div>
	
<form id="clavesContingencia" data-rutaAplicacion="financiero" data-opcion="actualizarClavesContingencia" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" name="idClaveContingencia" value="<?php echo $listaClaves['id_clave_contingencia'];?>" />
	<input type="hidden" id="identificadorUsuario" name="identificadorUsuario" value="<?php echo $identificadorUsuario;?>" />
	
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>

<fieldset>
        <legend>Detalle de vigencia</legend>
        <div data-linea = "1">
        <label>Fecha inicio</label> 
			<input type="text"	id="fechaDesde" name="fechaDesde" value="<?php echo date('d/m/Y', strtotime($listaClaves['fecha_desde']));?>" disabled="disabled" /> 
        </div>
        
         <div data-linea="1">
            <label>Hora inicio</label>
            <input id="horaDesde" name="horaDesde" type="text" placeholder="10:30" data-inputmask="'mask': '99:99'" value="<?php echo date('H:i', strtotime($listaClaves['fecha_desde']));?>" disabled="disabled" />
        </div>
        
        <div data-linea = "2">
        <label>Fecha fin</label> 
			<input type="text"	id="fechaHasta" name="fechaHasta" value="<?php echo date('d/m/Y', strtotime($listaClaves['fecha_hasta']));?>" disabled="disabled" /> 
        </div>
        
        <div data-linea="2">
            <label>Hora fin</label>
            <input id="horaHasta" name="horaHasta" type="text" placeholder="10:30" data-inputmask="'mask': '99:99'" value="<?php echo date('H:i', strtotime($listaClaves['fecha_hasta']));?>" disabled="disabled"/>
        </div>
        
        <div data-linea="3">
		<label>Observación</label> 
			<input type="text" id="observacion" name="observacion" data-er="^[A-Za-z0-9.,/ ]+$" value="<?php echo $listaClaves['observacion'];?>" disabled="disabled"/>
		</div>
             
</fieldset>
	
</form>

</body>

<script type="text/javascript">

var fecha_vigente= <?php echo json_encode($fechaVigente['fecha_desde']); ?>;

$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("select").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});

$(document).ready(function(){
	distribuirLineas();
	construirValidador();
});

$("#fechaDesde").datepicker({
	changeMonth: true,
	changeYear: true,
	minDate: new Date(fecha_vigente),
	onSelect: function(dateText, inst) {
		 $('#fechaHasta').datepicker('option', 'minDate', $("#fechaDesde" ).val()); 
   }
});
	
$("#fechaHasta").datepicker({
	changeMonth: true,
	changeYear: true,
	minDate: new Date(fecha_vigente)
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}
				
$("#clavesContingencia").submit(function(event){
	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#fechaDesde").val()=="" || !esCampoValido("#fecha_desde")){
		error = true;
		$("#fechaDesde").addClass("alertaCombo");
	}

	if($("#fechaHasta").val()=="" || !esCampoValido("#fecha_hasta")){
		error = true;
		$("#fechaHasta").addClass("alertaCombo");
	}

	if($("#observacion").val()=="" || !esCampoValido("#observacion")){
		error = true;
		$("#observacion").addClass("alertaCombo");
	}

	if (!error){
		ejecutarJson(this);
	}else{
		$("#estado").html("Por favor verifique la información ingresada.").addClass("alerta");
	}
});

</script>

</html>
