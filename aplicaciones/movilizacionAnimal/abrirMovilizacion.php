<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$data =  htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
list($id_sitio, $id_area) = explode("@", $data);

$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();

$movilizacion = $vdr->listaMovilizacionAnimal($conexion, $id_sitio, $id_area);

//print_r($_POST);

?>

<header>
	<h1>Resgistro movilización</h1>
</header>

<form id='nuevoMovilizacion' data-rutaAplicacion='movilizacionAnimal' data-opcion='guardarNuevoMovilizacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
<div class="pestania">
 	<input type="hidden" id="usuario_responsable" name="usuario_responsable" value="<?php echo $_SESSION['usuario'];?>" />
	<fieldset>
		<legend>Movilización de animales</legend>
			<div data-linea="1">
				<label>Sitio : </label><?php echo $movilizacion[0]['nombre_sitio']; ?> 				 				
			</div>										
			<div data-linea="2">
				<label>Area :</label><?php echo $movilizacion[0]['nombre_area']; ?> 				 				
			</div>
			<div data-linea="2">
				<label>Fecha movilización</label>				
				<input type="text" id="fecha_movilizacion" name="fecha_movilizacion" />
			</div>
			<div data-linea="3">
					<label>Provincia</label>				
					<select id="provincia" name="provincia">
						<option value="0">Seleccione....</option>
						<?php 
							$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
							foreach ($provincias as $provinciaArray){
								echo '<option value="' . $provinciaArray['codigo'] . '">' . $provinciaArray['nombre'] . '</option>';							
							}
						?>
					</select>
					<input type="hidden" id="nombreProvincia" name="nombreProvincia" />	 
			   </div>
			<div data-linea="3">				
					<label>Cantón</label>
					<select id="canton" name="canton" disabled="disabled">
					</select>
					<input type="hidden" id="nombreCanton" name="nombreCanton" />	
			   </div>

			<div data-linea="4">							
				<label>Observación: </label> 
				<input type="text" name="observacion" placeholder="Ej: Observacion"/>
			</div>
	</fieldset>
	
	<?php 
	//detalle para movilizar 	
	$i=1;
	foreach ($movilizacion as $detalleMovilizacion){
		echo '<fieldset>
				<legend>Registro de vacunación N°CERTIFICADO ' . $detalleMovilizacion['num_certificado'] . '</legend>;
			  <div data-linea="7">				
				<label>Fecha vacunación : </label> ' . $detalleMovilizacion['fecha_vacunacion'] . ' <br/>
			  </div>;
			  <div data-linea="7">				
				<label>Fecha vencimiento : </label> ' . $detalleMovilizacion['fecha_vencimiento'] . ' <br/>
			  </div>;	
		      <div data-linea="8">				
				<label>Número de vacunado : </label> ' . $detalleMovilizacion['total_vacunado'] . ' <br/>
			  </div>;
			  <div data-linea="8">				
				<label>Animales disponibles : </label> ' . $detalleMovilizacion['total_movilizado'] . ' <br/>
			    <input type="hidden" name="disponible_movilizar[]" value="'.$detalleMovilizacion['total_movilizado'].'" />
			  </div>;
			  <div data-linea="8">
				<label>Cantidad movilizados : </label>
				<input type="text" id="cantidad_movilizar[]" name="cantidad_movilizar[]" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
			  </div>	
			  <div data-linea="9">			
				<input type="hidden" name="id_vacuna_animal[]" value="'.$detalleMovilizacion['id_vacuna_animal'].'" />
			  </div>';
		$i++;
		echo '</fieldset>';
	}		
		
	?>	
	<button id="btn_guardar" type="button" name="btn_guardar" >Guardar movilizacion</button>
	
</div>


<div id="a2">
	<p>	
	<a href="Reporte8.pdf" target="visor" style="text-decoration: none;" >pdf</a> 
	<br>
	<iframe id = "visor" name="visor" width="550" height="500" src="about:blank" ></iframe>
</p>
</div>




</form>		
<script type="text/javascript">
	var array_canton= <?php echo json_encode($cantones); ?>;
	//Al cargarse la página	
	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));
		$("#fecha_movilizacion").datepicker({
		      changeMonth: true,
		      changeYear: true
		});	
	});
	
	//Para guardar la fiscalización
	$("#btn_guardar").click(function(event){
		 $('#nuevoMovilizacion').attr('data-opcion','guardarNuevoMovilizacion');
		 $('#nuevoMovilizacion').attr('data-destino','res_sitio');
	     event.preventDefault();	
		 abrir($("#nuevoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio	
		 abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);			 		 	
	});

	$("#provincia").change(function(){
    	scanton ='0';
		scanton = '<option value="">Cantón...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option data-latitud="'+array_canton[i]['latitud']+'"data-longitud="'+array_canton[i]['longitud']+'"data-zona="'+array_canton[i]['zona']+'" value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#canton').html(scanton);
	    $("#canton").removeAttr("disabled");

	    if ($("#provincia").val() != ''){
			$("#nombreProvincia").val($('#provincia option:selected').text());				
		}else{
			alert("Debe elegir la provincia de origen.");	
		}
	});
	
	$("#canton").change(function(){
	 	if ($("#canton").val() != ''){
			$("#nombreCanton").val($('#canton option:selected').text());				
		}else{
			alert("Debe elegir el cantón de origen.");	
		}
	});
</script>
