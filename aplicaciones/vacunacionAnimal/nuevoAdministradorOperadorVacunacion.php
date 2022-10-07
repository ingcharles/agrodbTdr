<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cv = new ControladorVacunacionAnimal();

?>
<header>
	<h1>Nuevo operador de vacunación</h1>
</header>
	<form id='nuevoAdministradorOperadorVacunacion' data-rutaAplicacion='vacunacionAnimal' data-opcion='guardarNuevoAdministracionVacunacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
		<fieldset>
			<legend>Administración operador de vacunación</legend>
				<div data-linea="1">
					<label>* Administración operador de vacunación, debe seleccionar la especie, para la vacunación animal</label>								
			    </div>
			    <div data-linea="2">
					<label>Especie</label>				
					<select id="especie" name="especie">
						<option value="0">Seleccione...</option>
						<?php 
							$especie = $cc-> listaEspecies($conexion);
							while ($fila = pg_fetch_assoc($especie)){
					    		echo '<option value="' . $fila['id_especies'] . '">' . $fila['nombre'] . '</option>';
					    	}
						?>
					</select>
					<input type="hidden" id="nombreEspecie" name="nombreEspecie" />	 
			    </div>
			    <div data-linea="3">				
					<label>Administrador operador</label>
					<select id="administradorOperador" name="administradorOperador">
						<option value="0">Seleccione....</option>
						<?php 
							$opVacunacion = $cv->listaSeleccionarOpVacunacion($conexion);
							foreach ($opVacunacion as $opArray){
								echo '<option value="' . $opArray['identificador_administrador'] . '">' . $opArray['identificador_administrador'] . ' - ' . $opArray['nombre_administrador'] . '</option>';							
							}
						?>
					</select>
					<input type="hidden" id="nombreVacunador" name="nombreVacunador" />					
			   </div>			   			   			   		   			   			
		</fieldset>	
		<button type="submit" class="guardar">Guardar operador vacunador</button>

  </form>

<script type="text/javascript">	
			
    var array_canton= <?php echo json_encode($cantones); ?>;

    $(document).ready(function(){			
		distribuirLineas();		
	});

    $("#especie").change(function(){
    	if ($("#especie").val() != 0){
			$("#nombreEspecie").val($('#especie option:selected').text());				
		}
	});

    $("#nuevoAdministradorOperadorVacunacion").submit(function(event){		
		event.preventDefault();
		abrir($(this),event,false);
	});
			
	function chequearCamposGuardar(form){
		$("#estado").html("").addClass('correcto');
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 
        //campos 
      	
		if(!$.trim($("#especie").val())){
			error = true;
			$("#especie").addClass("alertaCombo");
		}		
		
		if (!error){
			return true;		
		}else{			
			$("#estado").html("Por favor revise el formato de la información ingresada").addClass('alerta');
			return false;
		}
		
	}
	
</script>