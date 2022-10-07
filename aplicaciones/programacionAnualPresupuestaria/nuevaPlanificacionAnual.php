<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$fecha = getdate();
$anio = $fecha['year'];

$identificador=$_SESSION['usuario'];

if($identificador==''){
	$usuario=0;
}else{
	$usuario=1;
	$idAreaFuncionario = $_SESSION['idArea'];
	$nombreProvinciaFuncionario = $_SESSION['nombreProvincia'];
}//$usuario=0;

$conexion = new Conexion();
$ca = new ControladorAreas();
$cc = new ControladorCatalogos();
$cpp = new ControladorProgramacionPresupuestaria();

?>

<header>
	<h1>Planificación Anual</h1>
</header>

<div id="estado1"></div>

<div id="estado"></div>

<form id="nuevaPlanificacionAnual" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarNuevaPlanificacionAnual" data-destino="detalleItem">
	<input type='hidden' id='opcion' name='opcion' />
	
	<fieldset>
		<legend>Planificación Anual</legend>
		
		<div data-linea="1">
			<label>Objetivo Estratégico:</label>
			<select id=objetivoEstrategico name="objetivoEstrategico" required="required" style="width: 78%;">
				<option value="">Seleccione....</option>
				<?php 
					$objetivoEstrategico = $cpp->listarObjetivoEstrategico($conexion);
					
					while($fila = pg_fetch_assoc($objetivoEstrategico)){
						echo '<option value="' . $fila['id_objetivo_estrategico'] . '" >' . $fila['nombre'].' </option>';
					}
				?>
			</select>
			
			<input type='hidden' id='idObjetivoEstrategico' name='idObjetivoEstrategico' />
			<input type='hidden' id='nombreObjetivoEstrategico' name='nombreObjetivoEstrategico' />
		</div>
		
		<div data-linea="2">
			<label id="lAreaN2">N2 - Coordinación/Dirección:</label>
				<select id=areaN2 name="areaN2" required="required">
					<option value="">Seleccione....</option>
					<?php 
						$areasN2 = $ca->buscarEstructuraPlantaCentralProvincias($conexion);
						
						while($fila = pg_fetch_assoc($areasN2)){
							echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'].' </option>';
						}
					?>
				</select>
				
				<input type='hidden' id='nombreAreaN2' name='nombreAreaN2' />
		</div>
		
		<div data-linea="3">			
			<div id="dObjetivoEspecifico"></div>
		</div>
		
		<div data-linea="4">
			<div id="dN4"></div>
		</div>
		
		<div data-linea="5">			
			<div id="dObjetivoOperativo"></div>
		</div>
		
		<div data-linea="6">
			<div id="dGestion"></div>
		</div>
		
		<div data-linea="7">
			<div id="dTipo"></div>
		</div>
		
		<div data-linea="8">			
			<div id="dProcesoProyecto"></div>
		</div>
		
		<div data-linea="9">			
			<label id="lProductoFinal">Producto Final:</label>
				<input type="text" id="productoFinal" name="productoFinal" readonly="readonly"/>
		</div>
		
		<div data-linea="10">			
			<div id="dComponente"></div>
		</div>
		
		<div data-linea="11">			
			<div id="dActividad"></div>
		</div>
		
		<div data-linea="12">			
			<label  id="lProvincia">Provincia:</label>
				<select id="provincia" name="provincia" required="required">
					<option value="">Provincia....</option>
						<?php 	
							$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
							foreach ($provincias as $provincia){
								if($provincia['nombre'] == $_SESSION['nombreProvincia']){
									echo '<option value="' . $provincia['codigo'] . '" selected>' . $provincia['nombre'] . '</option>';
									$idProvincia = $provincia['codigo'];
									$nombreProvincia = $provincia['nombre'];
								}else{
									echo '<option value="' . $provincia['codigo'] . '" >' . $provincia['nombre'] . '</option>';
								}
							}
						?>
				</select> 
			
				<input type="hidden" id="idProvincia" name="idProvincia" value="<?php echo $idProvincia;?>"/>
				<input type="hidden" id="nombreProvincia" name="nombreProvincia" value="<?php echo $nombreProvincia;?>"/>
		</div>
		
		<div data-linea="13">			
			<label  id="lCantidadUsuarios">Cantidad de Usuarios:</label>
				<input type="text" id="cantidadUsuarios" name="cantidadUsuarios" maxlength="4" data-er="^[0-9]+$" />
		</div>
		
		<div data-linea="13">			
			<label  id="lPoblacionObjetivo">Población Objetivo:</label>
				<input type="text" id="poblacionObjetivo" name="poblacionObjetivo" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		
		<div data-linea="14">			
			<label  id="lMedioVerificacion">Medio de Verificación:</label>
				<input type="text" id="medioVerificacion" name="medioVerificacion" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		
		<div data-linea="15">			
			<div id="dResponsable"></div>
		</div>

	</fieldset>

	<button id="botonGuardar" type="submit" class="guardar">Guardar</button>
</form>

<script type="text/javascript">
var usuario = <?php echo json_encode($usuario); ?>;

	$("document").ready(function(){
		$("#lAreaN2").hide();
		$("#areaN2").hide();
		$("#lProductoFinal").hide();
		$("#productoFinal").hide();
		$("#lProvincia").hide();
		$("#provincia").hide();
		$("#lCantidadUsuarios").hide();
		$("#cantidadUsuarios").hide();
		$("#lPoblacionObjetivo").hide();
		$("#poblacionObjetivo").hide();
		$("#lMedioVerificacion").hide();
		$("#medioVerificacion").hide();
		
		if(usuario == '0'){
			$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#botonGuardar").attr("disabled", "disabled");
		}

		distribuirLineas();
		construirValidador();
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	$("#nuevaPlanificacionAnual").submit(function(event){

		$("#nuevaPlanificacionAnual").attr('data-opcion', 'guardarNuevaPlanificacionAnual');
	    $("#nuevaPlanificacionAnual").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#objetivoEstrategico").val())){
			error = true;
			$("#objetivoEstrategico").addClass("alertaCombo");
		}

		if(!$.trim($("#areaN2").val())){
			error = true;
			$("#areaN2").addClass("alertaCombo");
		}

		if(!$.trim($("#objetivoEspecifico").val())){
			error = true;
			$("#objetivoEspecifico").addClass("alertaCombo");
		}

		if(!$.trim($("#areaN4").val())){
			error = true;
			$("#areaN4").addClass("alertaCombo");
		}

		if(!$.trim($("#objetivoOperativo").val())){
			error = true;
			$("#objetivoOperativo").addClass("alertaCombo");
		}

		if(!$.trim($("#gestion").val())){
			error = true;
			$("#gestion").addClass("alertaCombo");
		}

		if(!$.trim($("#tipo").val())){
			error = true;
			$("#tipo").addClass("alertaCombo");
		}

		if(!$.trim($("#procesoProyecto").val())){
			error = true;
			$("#procesoProyecto").addClass("alertaCombo");
		}

		if(!$.trim($("#productoFinal").val())){
			error = true;
			$("#productoFinal").addClass("alertaCombo");
		}

		if(!$.trim($("#componente").val())){
			error = true;
			$("#componente").addClass("alertaCombo");
		}

		if(!$.trim($("#provincia").val())){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}

		if(!$.trim($("#cantidadUsuarios").val()) || !esCampoValido("#cantidadUsuarios")){
			error = true;
			$("#cantidadUsuarios").addClass("alertaCombo");
		}

		if(!$.trim($("#poblacionObjetivo").val()) || !esCampoValido("#poblacionObjetivo")){
			error = true;
			$("#poblacionObjetivo").addClass("alertaCombo");
		}

		if(!$.trim($("#medioVerificacion").val()) || !esCampoValido("#medioVerificacion")){
			error = true;
			$("#medioVerificacion").addClass("alertaCombo");
		}

		if(!$.trim($("#responsable").val())){
			error = true;
			$("#responsable").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			abrir($(this),event,false);
		}
	});

	$("#objetivoEstrategico").change(function (event) {
		$("#lAreaN2").show();
		$("#areaN2").show();
	});
	
	$("#areaN2").change(function (event) {
		$("#idObjetivoEstrategico").val($("#objetivoEstrategico option:selected").val());
		$("#nombreObjetivoEstrategico").val($("#objetivoEstrategico option:selected").text());
		$("#nombreAreaN2").val($("#areaN2 option:selected").text());

		$("#nuevaPlanificacionAnual").attr('data-opcion', 'combosPlanificacionAnual');
	    $("#nuevaPlanificacionAnual").attr('data-destino', 'dObjetivoEspecifico');
	    $("#opcion").val('objetivoEspecifico');

	    abrir($("#nuevaPlanificacionAnual"), event, false); //Se ejecuta ajax
	});

	$("#provincia").change(function(){
		$('#idProvincia').val($("#provincia option:selected").val());
		$('#nombreProvincia').val($("#provincia option:selected").text());
	});
</script>