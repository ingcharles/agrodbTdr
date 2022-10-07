<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$identificadorUsuarioRegistro = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Reseteo de Cupo de Combustible</h1>
</header>

<div id="estado"></div>

<?php

	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	
	$idGasolinera=$_POST['id'];
	
	$gasolinera = pg_fetch_assoc($cv->abrirGasolinera($conexion, $idGasolinera));
	
	echo'<table>
			<tr>
				<fieldset>
					<legend>Gasolinera '.$gasolinera['nombre'].'</legend>
						<div data-linea="1"><label>Dirección: </label> '.$gasolinera['direccion'].'</div>
						<div data-linea="1"><label>Localización: </label>'.$gasolinera['localización'].' Kms.</div>
						<div data-linea="2"><label>Cupo Mensual: </label>'.$gasolinera['cupo'].'</div>
						<div data-linea="2"><label>Saldo Disponible: </label>'.$gasolinera['saldo_disponible'].'</div>
						<div data-linea="6"><br /></div>
						<div data-linea="3"><label>Costo por Galón de Combustible </label></div>
						<div data-linea="4"><label> - Súper: </label>$'.$gasolinera['super'].'</div>
						<div data-linea="4"><label> - Extra: </label>'.$gasolinera['extra'].'</div>
						<div data-linea="5"><label> - Diesel: </label>$'.$gasolinera['diesel'].'</div>
						<div data-linea="5"><label> - Ecopaís: </label>'.$gasolinera['ecopais'].'</div>		        
	  			</fieldset>
			</tr>
		 </table>';	
	
	echo   '<form id="reseteoCupoCombustible" data-rutaAplicacion="transportes" data-opcion="reseteoCupoCombustible" >
				<input type="hidden" id="identificadorUsuarioRegistro" name="identificadorUsuarioRegistro" value="'. $identificadorUsuarioRegistro .'" />
				<input type="hidden" id="idGasolinera" name="idGasolinera" value="'.$idGasolinera.'"/>
			
				<fieldset>
					<legend>Resetear Cupo y Saldo disponible</legend>
						<div data-linea="1">
							<label>Asignar monto Saldo:</label>
							<input type="text" id="monto" name="monto" required="required" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
						</div>
				</fieldset>
				
				<button id="eliminar" type="submit" class="eliminar" >Resetear Cupo</button>
			
			</form>';
	?>
	
</body>

	<script type="text/javascript">
		$(document).ready(function(){
			distribuirLineas();
		});
	
		function esCampoValido(elemento){
			var patron = new RegExp($(elemento).attr("data-er"),"g");
			return patron.test($(elemento).val());
		}
	
		$("#reseteoCupoCombustible").submit(function(event){
	
			event.preventDefault();
	
			$(".alertaCombo").removeClass("alertaCombo");
			var error = false;
	
			if($("#monto").val()==""|| !esCampoValido("#monto")){
				error = true;
				$("#monto").addClass("alertaCombo");
			}

			if($("#monto").val()=="" || $("#monto").val() < 1){
				error = true;
				$("#monto").addClass("alertaCombo");
			}
			
			if (!error){
				ejecutarJson(this);	
				
			}else{
				$("#estado").html('Por favor revise el formato de la información ingresada').addClass('alerta');
			}		
		});		


		$("#montoSolicitado").change(function(){
			$("#montoSolicitado").removeClass("alertaCombo");
			$("#combustible").removeClass("alertaCombo");

			if($("#combustible option:selected").val() != ''){
				if($("#montoSolicitado").val() != ''){
					if($("#vehiculo option:selected").val() != placaDirEje){
						if(($("#montoSolicitado").val() > 0) && ($("#montoSolicitado").val() <= 25)){
							$("#galonesSolicitados").val(($("#montoSolicitado").val()/($("#combustible option:selected").attr("data-precio"))).toFixed(2));
						}else if($("#montoSolicitado").val() <= 0){
							alert('No pueden generarse órdenes por un valor de $0 o menor');
							$("#montoSolicitado").addClass("alertaCombo");
							$("#montoSolicitado").val("");
						}else{
							alert('No pueden generarse órdenes por un valor mayor a $25');
							$("#montoSolicitado").addClass("alertaCombo");
							$("#montoSolicitado").val("");
						}
					}else{
						if($("#montoSolicitado").val() > 0){
							$("#galonesSolicitados").val(($("#montoSolicitado").val()/($("#combustible option:selected").attr("data-precio"))).toFixed(2));
						}else if($("#montoSolicitado").val() <= 0){
							alert('No pueden generarse órdenes por un valor de $0 o menor');
							$("#montoSolicitado").addClass("alertaCombo");
							$("#montoSolicitado").val("");
						}
					}
				}else{
					alert('Por favor ingrese un valor en el monto de combustible solicitado');
					$("#montoSolicitado").addClass("alertaCombo");
					$("#montoSolicitado").val("");
				}

			}else{
				alert('Por favor seleccione un tipo de combustible');
				$("#combustible").addClass("alertaCombo");
				$("#montoSolicitado").val("");
			}
		});
	</script>
</html>