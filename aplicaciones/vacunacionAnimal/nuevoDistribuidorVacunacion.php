<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cv = new ControladorVacunacionAnimal();

$adminOperador = $cv->obtenerOperadorVacunador($conexion);

?>
<header>
	<h1>Nuevo punto de distribución</h1>
</header>

	<form id='nuevoDistribuidorVacunacion' data-rutaAplicacion='vacunacionAnimal' data-opcion='guardarNuevoDistribuidorVacunacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
		<fieldset>
			<legend>Administración de los puntos de distribución</legend>
				<div data-linea="1">
					<label>* Seleccionar el administrador de vacunación y el punto de distribución. </label>								
			    </div>
			    <div data-linea="3">				
					<label>Especie</label>
					<select id="especieAdministradorOperador" name="especieAdministradorOperador">
						<option value="0">Seleccione....</option>
						<?php 
							$opEspecieVacunacion = $cc->listaEspecieOperadorVacunador($conexion);
							foreach ($opEspecieVacunacion as $especie){
								echo '<option value="' .$especie['id_especie'].'">'. $especie['nombre_especie'].'</option>';							
							}
						?>
					</select>									
			    </div>
			    <div data-linea="2">				
					<label>Administrador operador</label>
					<select id="cmbOperadorVacunacion" name="cmbOperadorVacunacion" disabled="disabled">
				    </select>					
			    </div>			  			
			    <div data-linea="4">
					<label>Punto de distribución</label>				
					<select id="ptoDistribucion" name="ptoDistribucion">
						<option value="0">Seleccione....</option>
						<?php 
							$seleccionarPtoDistribucion = $cv->listaSeleccionarPtoDistribucion($conexion);
							foreach ($seleccionarPtoDistribucion as $ptoDistribucion){
								echo '<option value="' . $ptoDistribucion['identificador_distribuidor'] . '">' . $ptoDistribucion['nombre_distribuidor'] . '</option>';							
							}
						?>
					</select>						
			    </div>
			   	   			   			   		   			   			
		</fieldset>	
		<button type="submit" class="guardar">Guardar puntos de distribución</button>

  </form>

<script type="text/javascript">	
			
    var array_adminOperador= <?php echo json_encode($adminOperador); ?>;

    $(document).ready(function(){			
		distribuirLineas();		
	});

    $("#especieAdministradorOperador").click(function(event){	
		if($("#especieAdministradorOperador").val() != 0){
			sAdminOperador ='0';
			sAdminOperador = '<option value="0">Seleccionar...</option>';
			for(var i=0;i<array_adminOperador.length;i++){	
				if ($("#especieAdministradorOperador").val()==array_adminOperador[i]['id_especie']){	    
					sAdminOperador += '<option value="'+array_adminOperador[i]['id_administrador_vacunacion']+'"> '+ array_adminOperador[i]['nombre_administrador']+'</option>';
				}			  
			}	   		    
			$('#cmbOperadorVacunacion').html(sAdminOperador);
			$("#cmbOperadorVacunacion").removeAttr("disabled");			
		}					 	
	});       

    $("#nuevoDistribuidorVacunacion").submit(function(event){	        
		event.preventDefault();
		abrir($(this),event,false);
	});

    function chequearCamposGuardar(form){
		$("#estado").html("").addClass('correcto');
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 
        //campos 
      	
		if(!$.trim($("#especieAdministradorOperador").val())){
			error = true;
			$("#especieAdministradorOperador").addClass("alertaCombo");
		}		
		
		if (!error){
			return true;		
		}else{			
			$("#estado").html("Por favor revise el formato de la información ingresada").addClass('alerta');
			return false;
		}
		
	}
	
</script>