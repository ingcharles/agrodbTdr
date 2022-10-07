<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorCatalogos.php';

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
	$cc = new ControladorCatalogos();
	
	$idMovilizacion=$_POST['id'];
	
	$movilizacion = pg_fetch_assoc($cv->abrirMovilizacionDetalle($conexion, $idMovilizacion));
	$ruta = $cv->abrirMovilizacionRutasFechas($conexion, $idMovilizacion);
	$estadoMov = $movilizacion['estado'];
	
	$ciudades = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$sitios = $cc->listarSitiosLocalizacion($conexion,'SITIOS');
			
	echo'<table>
			<tr>
				<fieldset>
					<legend> Movilización N° '.$movilizacion['id_movilizacion'].'</legend>
						<div data-linea="1"><label>Placa: </label> '.$movilizacion['placa'].'</div>
						<div data-linea="1"><label>Fecha de Solicitud: </label>'.$movilizacion['fecha_solicitud'].'</div>
						<div data-linea="2"><label>Kilometraje inicial: </label>'.$movilizacion['kilometraje_inicial'].' Kms.</div>
						<div data-linea="2"><label>Kilometraje final: </label>'.$movilizacion['kilometraje_final'].' Kms.</div>
						<div data-linea="3"><label>Tipo movilización: </label>'.$movilizacion['tipo_movilizacion'].'</div>
						<div data-linea="4"><label>Ruta: </label> <br/>';
							while($fila = pg_fetch_assoc($ruta)){
								echo ' - ' . $fila['localizacion'] . ' - <b>Desde:</b> ' . $fila['fecha_desde'] .' - <b>Hasta:</b> ' . $fila['fecha_desde'] . '<br/>';
							}								
				  echo '</div>
						<div data-linea="5"><label>Motivo: </label>'.$movilizacion['descripcion'].'</div>
						<div data-linea="6"><label>Conductor: </label>'.$movilizacion['apellido'].' '.$movilizacion['nombreconductor'].'</div>
						<div data-linea="7"><label>Observaciones </label></div>
						<div data-linea="8"><label> - Movilización: </label>'.( $movilizacion['observacion_movilizacion']!='' ?$movilizacion['observacion_movilizacion']:'Sin novedad.').'</div>
						<div data-linea="9"><label> - Ruta: </label>'.( $movilizacion['observacion_ruta']!='' ?$movilizacion['observacion']:'Sin novedad.').'</div>
						<div data-linea="10"><label> - Ocupante: </label>'.( $movilizacion['observacion_ocupante']!='' ?$movilizacion['observacion']:'Sin novedad.').'</div>
						<div data-linea="11"><label>Orden Generada: </label> <a href='.$movilizacion['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">PDF</a></div>
				</fieldset>
			</tr>
		</table>';

		
		echo   '<form id="agregarRuta" data-rutaAplicacion="transportes" data-opcion="agregarRuta" >
					<input type="hidden" id="identificadorUsuarioRegistro" name="identificadorUsuarioRegistro" value="'. $identificadorUsuarioRegistro .'" />
					<input type="hidden" id="idMovilizacion" name="idMovilizacion" value="'.$idMovilizacion.'"/>
						
					<fieldset>
						<legend>Recorrido</legend>	
						
						
						<div data-linea="3">
							<label>Provincia</label>
								<select id="provincia" name="provincia" >
									<option value="">Provincia....</option>';
									
										$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
										foreach ($provincias as $provincia){
											echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
										}
									
		echo'					</select> 
							
						</div><div data-linea="3">
					
							<label>Ciudad</label>
								<select id="ciudad" name="ciudad" disabled="disabled">
								</select>
								
						</div><div data-linea="4">
								
							<label>Sitio</label>
								<select id="sitio" name="sitio" disabled="disabled">
								</select>
								
						</div><div data-linea="6">
						
							<label>Fecha Validez desde</label> 
								<input type="text" name="fechaDesde" id="fechaDesde" required="required" readonly="readonly"/>
								 
						</div><div data-linea="6">
						
							<label>Fecha Validez hasta</label> 
								<input type="text" name="fechaHasta" id="fechaHasta" required="required" readonly="readonly"/>
						
						</div>
			
					</fieldset>
					
					<button id="agregar" type="submit" >Guardar nueva Ruta</button>
						
				</form>';
	?>
	
</body>

	<script type="text/javascript">

		var array_ciudad= <?php echo json_encode($ciudades); ?>;
		var array_sitio= <?php echo json_encode($sitios); ?>;
		var estadoMov= <?php echo json_encode($estadoMov); ?>;
	
		$(document).ready(function(){
			distribuirLineas();

			$("#fechaDesde").datepicker({
			      changeMonth: true,
			      changeYear: true
			});
			    
			$("#fechaHasta").datepicker({
			      changeMonth: true,
			      changeYear: true
			});

			if(estadoMov==9){
				$("#agregarRuta").hide();
				$("#estado").html('La orden se encuentra eliminada, no se pueden agregar nuevas rutas.').addClass('alerta');
				alert("La orden se encuentra eliminada, no se pueden agregar nuevas rutas.");
			}else if (estadoMov==4){
				$("#agregarRuta").hide();
				$("#estado").html('La orden se encuentra finalizada, no se pueden agregar nuevas rutas.').addClass('alerta');
				alert("La orden se encuentra finalizada, no se pueden agregar nuevas rutas.");
			}
		});

		$("#provincia").change(function(){
	    	sciudad ='0';
			sciudad = '<option value="">Ciudad...</option>';
		    for(var i=0;i<array_ciudad.length;i++){
			    if ($("#provincia").val()==array_ciudad[i]['padre']){
			    	sciudad += '<option value="'+array_ciudad[i]['codigo']+'">'+array_ciudad[i]['nombre']+'</option>';
				    }
		   		}
		    $('#ciudad').html(sciudad);
		    $("#ciudad").removeAttr("disabled");
		});

	    $("#ciudad").change(function(){
			ssitio ='0';
			ssitio = '<option value="">Sitio...</option>';
		    for(var i=0;i<array_sitio.length;i++){
			    if ($("#ciudad").val()==array_sitio[i]['padre']){
			    	ssitio += '<option value="'+array_sitio[i]['nombre']+'">'+array_sitio[i]['nombre']+'</option>';
				    } 
		    	}
		    //ssitio += '<option value="Otro">Otro</option>';
		    $('#sitio').html(ssitio);
			$("#sitio").removeAttr("disabled");
		});
		
		function esCampoValido(elemento){
			var patron = new RegExp($(elemento).attr("data-er"),"g");
			return patron.test($(elemento).val());
		}
	
		$("#agregarRuta").submit(function(event){
	
			event.preventDefault();
	
			$(".alertaCombo").removeClass("alertaCombo");
			var error = false;
	
			if($("#provincia").val()==""){
				error = true;
				$("#provincia").addClass("alertaCombo");
			}
	
			if($("#ciudad").val()==""){
				error = true;
				$("#ciudad").addClass("alertaCombo");
			}

			if($("#sitio").val()==""){
				error = true;
				$("#sitio").addClass("alertaCombo");
			}
	
			if($("#fechaDesde").val()==""){
				error = true;
				$("#fechaDesde").addClass("alertaCombo");
			}
	
			if($("#fechaHasta").val()==""){
				error = true;
				$("#fechaHasta").addClass("alertaCombo");
			}
			
			if (!error){
				ejecutarJson(this);	
				
			}else{
				$("#estado").html('Por favor revise el formato de la información ingresada').addClass('alerta');
			}		
		});		
	</script>
</html>