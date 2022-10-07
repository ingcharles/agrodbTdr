<?php	
	require_once '../../general/recaptchalib.php';
	//$verfificadorPublico = "6Lf3gSITAAAAACNXzXpedxbFbo8IuSjCb9NuvD8c"; //--PRUEBAS
	$verfificadorPublico = "6Ldcl_wSAAAAAM6M_O0Pw4DBiF6lvbLlLvpJTtMl"; //--PRODUCCION
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
	<header>
			<h1>Pago con Efectivo desde mi Celular</h1>
	</header>
	<div id="estado"></div>
	<section id="confirmar">
	<fieldset  class="fielsetTamano">
	<legend>Confirmar Pago</legend>
<form id="realizarPagoDinero"  data-rutaAplicacion="../../../publico/pagoEfectivoCelular" data-opcion="finalizarOrdenPagoEfectivoCelular" data-destino="resultados">
           <table class="talert">
            <tr class="tleft">
              <td ><label>* Cédula: </label></td>
              <td> <input id="cedula" value="" name="cedula" type="text" class="botonTama" data-er="^[\d]+$" maxlength="10"/>
              		<div id="lcedula"></div></div></td></tr>
            <tr class="tleft">
                <td><label>* Número Celular: </label></td>
                <td> <input id="celular" value=""  name="celular" type="text" class="botonTama" data-er="^[0-9]+$" maxlength="10"/>
                <div id="lcelular"></div></td></tr>
            <tr class="tleft">
                <td><label>&nbsp;&nbsp;Monto:</label></td><td><label>$&nbsp;<?php echo $_POST['totalPagar'];?></label></td>
                </tr>
            <tr><td colspan="2"></br><hr/><?php echo '<div class="g-recaptcha"  data-sitekey='.$verfificadorPublico.' data-callback="enableBtn" data-expired-callback="disableBtn"></div>';?></td>
                </tr>
            <tr><td ><input id="pagar" type="submit" class="botonTama" value="PAGAR"></td>
                <td ><input id="2" type="submit" class="botonTama" value="CANCELAR" onclick="verificar(id)"></td>
                </tr>                              
            </table>
			<input type="hidden" name="id_pago" value="<?php echo $_POST['id_pago'];?>"/>
			<input type="hidden" name="totalPagar" value="<?php echo $_POST['totalPagar'];?>"/>
			<input type="hidden" name="idOperador" value="<?php echo $_POST['idOperador'];?>"/>
			<input type="hidden" name="numeroFactura" value="<?php echo $_POST['numeroFactura'];?>"/>
			<input type="hidden" name="identificador" value="<?php echo $_POST['identificador'];?>"/>
			<input type="hidden" name="numeroSolicitud" value="<?php echo $_POST['numeroSolicitud'];?>">
	</form>
	</fieldset >
		<fieldset  >
			<legend>ALERTA </legend>
				<table class="talert"></table>
				EL COSTO ADICIONAL IMPUESTO POR EL BANCO CENTRAL DEL ECUADOR PARA LA TRANSACCION ES DE $0,05.....!!</br>
		</fieldset >
	</section>
</body>
<script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
<script type="text/javascript">
$(document).ready(function () { 
	$("#celular").val('');
	$("#cedula").val('');
	$("#pagar").attr("disabled", true);
	//$("#pagar").hide();
});
$("#realizarPagoDinero").on("submit", function(e){ 
	$("#lcelular").text('');
	$("#lcedula").text('');
	 e.preventDefault();
	 var error = true;
	 if($.trim($("#celular").val())==""){
		    error = false;
			$("#celular").addClass("alerta");
			$("#celular").text('Campo vacio...').addClass("alerta");
		   }	   
	 if(!esCampoValidoDine("#celular") || $("#celular").val().length < 10){
			error = false; 
			$("#celular").addClass("alertaCombo");
			$("#lcelular").text('No posee el formato correcto...').addClass("alerta");

			//console.log('error celular ');
			}
	 if(!esCampoValidoDine("#cedula") || $("#cedula").val().length < 10 ){
			error = false;
			$("#cedula").addClass("alertaCombo");
			$("#lcedula").text('No posee el formato correcto...').addClass("alerta");

			//console.log('error cedula ');
			}
	 if(error) {   
		 	$("#lcelular").text('').addClass("alerta");
		 	$("#lcedula").text('').addClass("alerta");
		 	//console.log('correcto');
			abrir($(this), e, false);
		    }

}); 
function verificar(id){
	if(id==2){ 
	$("#realizarPagoDinero").attr('data-opcion', '');
	location.reload();}
}
function esCampoValidoDine(elemento){ 
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}
function enableBtn(){
	$("#pagar").attr("disabled", false);
   }
function disableBtn(){
	$("#pagar").attr("disabled", true);
   }
</script>
<style type="text/css">
#tablaOrdenPago td, #tablaOrdenPago th,#tablaDetalle td, #tablaDetalle th
{
	font-size:1em;
	border:1px solid rgba(0,0,0,.1);
	padding:3px 7px 2px 7px;
}
</style>
</html>