<?php 

	//header('Location: ../../../../agrodbOut.html');
	
require_once '../../../clases/Conexion.php';
require_once '../../../clases/GoogleAnalitica.php';
require_once '../../../clases/ControladorDineroElectronico.php';

$conexion = new Conexion();
$de = new ControladorDineroElectronico();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<link rel='stylesheet' href='../pagoEfectivoCelular/estilos/estilo.css'>
	<script src="../../general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="../../general/funciones/agrdbfunc.js" type="text/javascript"></script>
	<script src="../../general/funciones/jquery-ui-1.10.2.custom.js" type="text/javascript"></script>
</head>
<body id="paginabusqueda">
	<section id="busqueda">
		<fieldset>
		<legend>SERVICIO </legend>		
		<hr />
			<form id="consultaDinero"  data-rutaAplicacion="../../../publico/pagoEfectivoCelular" data-opcion="consultaOrdenPago" data-destino="resultados">			         
				<label for="valor">Num de Orden de Pago</label> 
					<input id="numeroSolicitud" value="" name="numeroSolicitud" type="text" data-er="^[AGR]*-\d{4}-([0-9]{9})$" maxlength="18"/> 
				<div id="lnumeroSolicitud"></div>	
				<hr />
					<button id="cobrar" >CONSULTAR</button>					
			</form></br>
			<form action="../../publico/index.html" ><button >SALIR</button></form>
			<hr />
			<table>
			  <tr><td >
				Las solicitudes de VUE no pueden ser canceladas con Efectivo desde mi Celular
				</td></tr></table>
				<table><tr>
				<td class="acerca">
					<p align="center">Sistema Gestionador Unificado de Información</p>
					<p align="center">Agrocalidad</p>
					<p align="center">Gestión Tecnológica</p>
				</td>				
			</tr></table>
		</fieldset>
	</section>
	<section id="resultados">
			Ingrese los datos de busqueda en la parte izquierda.
	</section>
</body>
<script type="text/javascript">
$("#consultaDinero").submit(function(e){
	 e.preventDefault();
	 var error = true;
    $("#lnumeroSolicitud").text('');
	 if($.trim($("#numeroSolicitud").val())==""){
		    error = false;
			$("#numeroSolicitud").addClass("alerta");
			$("#lnumeroSolicitud").text('Campo vacio...').addClass("alerta");
			$("#resultados").html('Ingrese los datos de busqueda en la parte izquierda.');
		   }	

	 if(!esCampoValido("#numeroSolicitud")){
			error = false;
			$("#numeroSolicitud").addClass("alertaCombo");
			$("#lnumeroSolicitud").text('No posee el formato correcto.').addClass("alerta");
			$("#resultados").html('Ingrese los datos de busqueda en la parte izquierda.');
			}

	 if(error) { 
			abrir($(this), e, false);
		    }
});

$("#consultaConexion").submit(function(e){
	 e.preventDefault();
	 abrir($(this), e, false);
});
		
$(document).ready(function () {
	$("#numeroSolicitud").val('');
}); 	
</script>
</html>