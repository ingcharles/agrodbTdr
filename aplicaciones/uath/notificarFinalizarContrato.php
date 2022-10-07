<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorEmpleados.php';


$conexion = new Conexion();
$cc = new ControladorCatastro();
$ce = new ControladorEmpleados();

$identificador=$_SESSION['usuario_seleccionado'];

$qContrato = $cc->obtenerDatosContratoUsuario($conexion, $identificador, 'Parcial');
$qDatosPersonales = $ce ->obtenerFichaEmpleado($conexion, $identificador);



?>

<header>
	<h1>Contratos</h1>
</header>

<form id="finalizarContrato" data-rutaAplicacion="uath" data-opcion="finalizarContrato" data-accionEnExito="#ventanaAplicacion #filtrar">

	
	<div id="estado"></div>
	
				<fieldset>
					<legend>Contratos</legend>	
					<table>	
				<?php 
				$contadorDiv=13;
				While ($contrato = pg_fetch_assoc($qContrato)){
						
				echo '<tr>
						<td>
				    		<input type="checkbox" id="'.$contrato['id_datos_contrato'].'" name="idContratos[]" value="'.$contrato['id_datos_contrato'].'">
						</td>
						<td >
							<label for="'.$contrato['id_datos_contrato'].'"> Número: '. $contrato['numero_contrato'] . ' - Regimen laboral: '. $contrato['regimen_laboral'] . ' <br/>
		                     Tipo Contrato: '. $contrato['tipo_contrato'] . ' - Partida: ' . $contrato['partida_presupuestaria']. ' <br/>
							 Fechas: (Desde ' . $contrato['fecha_inicio']. ' Hasta ' . $contrato['fecha_fin']. ')</label>
						</td>
			`		  </tr>
										<tr><td></td><td >
												<label> Estado: '. $contrato['estado'] . ' - Tipo Contrato: '. $contrato['tipo_contrato'] . ' <br/> Partida: ' . $contrato['partida_presupuestaria']. ' <br/> Fechas: (Desde ' . $contrato['fecha_inicio']. ' Hasta ' . $contrato['fecha_fin']. ')</label>
											</td></tr>	';
								}
						?>
			</table>	

				</fieldset>
	
				<fieldset>
					<legend>Observación</legend>	
					<div data-linea="1">
						<textarea rows="4" id="observacion" name="observacion"></textarea>	 
					</div>	

				</fieldset>
				
				<button id="guardar" type="submit" class="guardar">Guardar</button>
				
</form>

<script type="text/javascript">

	$("#finalizarContrato").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#finalizarContrato input[type=checkbox]:checked").val()== null){
				error = true;
				$("label").addClass("alertaCombo");
		}

		if($("#observacion").val()==""){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}

		if (!error){
			ejecutarJson($(this));
		}
		
	});

	$(document).ready(function(){
		distribuirLineas();
	});
		

</script>
