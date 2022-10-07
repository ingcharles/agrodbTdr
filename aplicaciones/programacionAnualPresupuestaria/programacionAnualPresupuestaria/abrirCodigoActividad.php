<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$idPrograma = $_POST['idPrograma'];
	$idCodigoProyecto = $_POST['idCodigoProyecto'];
	$idCodigoActividad = $_POST['idCodigoActividad'];
	$codigoActividad = pg_fetch_assoc($cpp->abrirCodigoActividad($conexion, $idCodigoActividad));
	
	$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
?>

	<header>
		<h1>Actividad</h1>
	</header>

	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirCodigoProyecto" data-destino="detalleItem">
		<input type="hidden" name="idPrograma" value="<?php echo $idPrograma;?>"/>
		<input type="hidden" name="idCodigoProyecto" value="<?php echo $idCodigoProyecto;?>"/>
		<button class="regresar">Regresar a Proyecto</button>
	</form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="modificarCodigoActividad" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarCodigoActividad">
					<input type="hidden" id="idCodigoActividad" name="idCodigoActividad" value="<?php echo $idCodigoActividad;?>">
					<fieldset id="fs_detalle">
						<legend>Actividad</legend>
						
						<div data-linea="1">
							<label>Nombre:</label>
							<input type="text" id="nombreActividad" name="nombreActividad" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $codigoActividad['nombre']?>" disabled="disabled"/>
						</div>
						
						<div data-linea="2">
							<label>Código:</label>
							<input type="text" id="codigoActividad" name="codigoActividad" maxlength="3" data-er="^[0-9]+$" value="<?php echo $codigoActividad['codigo_actividad']?>" disabled="disabled"/>
						</div>		
						
						<div data-linea="3">
							<label>Provincia:</label>
								<select id="provincia" name="provincia" disabled="disabled" required="required">
									<option value="">Provincia....</option>
										<?php 	
											$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
											foreach ($provincias as $provincia){
												echo '<option value="' . $provincia['codigo'] . '" data-geografico="' . $provincia['geografico'] . '">' . $provincia['nombre'] . '</option>';
											}
										?>
								</select> 
							
								<input type="hidden" id="idProvincia" name="idProvincia" value="<?php echo $codigoActividad['id_provincia']?>"/>
								<input type="hidden" id="nombreProvincia" name="nombreProvincia" value="<?php echo $codigoActividad['provincia']?>"/>
								<input type="hidden" id="geograficoProvincia" name="geograficoProvincia" value="<?php echo $codigoActividad['geografico_provincia']?>"/>
						</div>
						<div data-linea="3">
							<label id="lCanton">Cantón:</label>
								<select id="canton" name="canton" disabled="disabled" required="required">
								</select>
								
								<input type="hidden" id="idCanton" name="idCanton" value="<?php echo $codigoActividad['id_canton']?>"/>
								<input type="hidden" id="nombreCanton" name="nombreCanton" value="<?php echo $codigoActividad['canton']?>"/>
								<input type="hidden" id="geograficoCanton" name="geograficoCanton" value="<?php echo $codigoActividad['geografico_canton']?>"/>
						</div>
						<div>
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
		</tr>
	</table>

<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
						
	$('document').ready(function(){
		cargarValorDefecto("provincia","<?php echo $codigoActividad['id_provincia'];?>");
		$('<option value="<?php echo $codigoActividad['id_canton'];?>" data-geografico="<?php echo $codigoActividad['geografico_canton'];?>"><?php echo $codigoActividad['canton'];?></option>').appendTo('#canton');
		acciones("#nuevoCodigoActividad","#detalleCodigoActividad");
		distribuirLineas();
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	$("#modificarCodigoActividad").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreActividad").val()) || !esCampoValido("#nombreActividad")){
			error = true;
			$("#nombreActividad").addClass("alertaCombo");
		}

		if(!$.trim($("#codigoActividad").val()) || !esCampoValido("#codigoActividad")){
			error = true;
			$("#codigoActividad").addClass("alertaCombo");
		}

		if(!$.trim($("#provincia").val())){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}

		if(!$.trim($("#canton").val())){
			error = true;
			$("#canton").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});

	$("#provincia").change(function(){

		$('#idProvincia').val($("#provincia option:selected").val());
		$('#nombreProvincia').val($("#provincia option:selected").text());
		$('#geograficoProvincia').val($("#provincia option:selected").attr('data-geografico'));
		 
	    	scanton ='0';
	    	scanton = '<option value="">Canton...</option>';
		    for(var i=0;i<array_canton.length;i++){
			    if ($("#provincia").val()==array_canton[i]['padre']){
			    	scanton += '<option value="'+array_canton[i]['codigo']+'" data-geografico="'+array_canton[i]['geografico']+'">'+array_canton[i]['nombre']+'</option>';
				    }
		   		}
		    $('#canton').html(scanton);
		    $("#canton").removeAttr("disabled");
		    
		    $("#lCanton").show();
		    $("#canton").show();
	});

	$("#canton").change(function(){

		$('#idCanton').val($("#canton option:selected").val());
		$('#nombreCanton').val($("#canton option:selected").text());
		$('#geograficoCanton').val($("#canton option:selected").attr('data-geografico'));
	});
</script>