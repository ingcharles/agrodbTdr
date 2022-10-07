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
	<h1>Orden de Combustible a Eliminar</h1>
</header>

<div id="estado"></div>

<?php

	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	
	$idCombustible=$_POST['id'];
	
	$combustible = pg_fetch_assoc($cv->abrirCombustible($conexion, $idCombustible));
	
	echo'<table>
			<tr>
				<fieldset>
					<legend>N° '.$combustible['id_combustible'].'</legend>
						<div data-linea="1"><label>Placa: </label> '.$combustible['placa'].'</div>
						<div data-linea="1"><label>Kilometraje: </label>'.$combustible['kilometraje'].' Kms.</div>
						<div data-linea="2"><label>Fecha de Solicitud: </label>'.$combustible['fecha_solicitud'].'</div>
						<div data-linea="2"><label>Fecha de Liquidación: </label>'.$combustible['fecha_liquidacion'].'</div>
						<div data-linea="3"><label>Gasolinera: </label>'.$combustible['nombregasolinera'].'</div>
						<div data-linea="3"><label>Tipo combustible: </label>'.$combustible['tipo_combustible'].'</div>
						<div data-linea="4"><label>Monto solicitado: </label>$'.$combustible['monto_solicitado'].'</div>
						<div data-linea="4"><label>Galones solicitados: </label>'.$combustible['galones_solicitados'].'</div>
						<div data-linea="5"><label>Valor cancelado: </label>$'.$combustible['valor_liquidacion'].'</div>
						<div data-linea="5"><label>Galones: </label>'.$combustible['cantidad_galones'].'</div>
						<div data-linea="6"><label>Conductor: </label>'.$combustible['apellido'].' '.$combustible['nombreconductor'].'</div>
						<div data-linea="7"><label>Observaciones: </label>'.( $combustible['observacion']!='' ?$combustible['observacion']:'Sin novedad.').'</div>
						<div data-linea="8"><label>Orden Generada: </label> <a href='.$combustible['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">PDF</a></div>	
				        
	  			</fieldset>
			</tr>
		 </table>';	
	
	echo   '<form id="notificarEliminarCombustible" data-rutaAplicacion="transportes" data-opcion="eliminarCombustible" >
				<input type="hidden" id="identificadorUsuarioRegistro" name="identificadorUsuarioRegistro" value="'. $identificadorUsuarioRegistro .'" />
				<input type="hidden" id="idCombustible" name="idCombustible" value="'.$idCombustible.'"/>
			
				<fieldset>
					<legend>Datos de Eliminación</legend>
						<div data-linea="1">
							<label>Usuario solicitante:</label>
							<input type="text" id="usuarioSolicitante" name="usuarioSolicitante" required="required" />
						</div>
						
						<div data-linea="2">
							<label>Número de GLPI:</label>
							<input type="text" name="glpi" id="glpi" required="required" data-er="^[0-9]+$" />
						</div>
				</fieldset>
				
				<button id="eliminar" type="submit" class="eliminar" >Eliminar Combustible</button>
			
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
	
		$("#notificarEliminarCombustible").submit(function(event){
	
			event.preventDefault();
	
			$(".alertaCombo").removeClass("alertaCombo");
			var error = false;
	
			if($("#usuarioSolicitante").val()==""|| !esCampoValido("#usuarioSolicitante")){
				error = true;
				$("#usuarioSolicitante").addClass("alertaCombo");
			}
	
			if($("#glpi").val()==""|| !esCampoValido("#glpi")){
				error = true;
				$("#glpi").addClass("alertaCombo");
			}
			
			if (!error){
				ejecutarJson(this);	
				
			}else{
				$("#estado").html('Por favor revise el formato de la información ingresada').addClass('alerta');
			}		
		});		
	</script>
</html>