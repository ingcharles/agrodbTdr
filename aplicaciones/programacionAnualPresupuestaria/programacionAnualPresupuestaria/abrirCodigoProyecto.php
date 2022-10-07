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
	$codigoProyecto = pg_fetch_assoc($cpp->abrirCodigoProyecto($conexion, $idCodigoProyecto));
	$codigoActividades = $cpp->listarCodigoActividad($conexion, $idCodigoProyecto);
	
	$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
?>

	<header>
		<h1>Proyecto</h1>
	</header>

	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirPrograma" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $idPrograma;?>"/>
		<button class="regresar">Regresar a Programa</button>
	</form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="modificarCodigoProyecto" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="modificarCodigoProyecto">
					<input type="hidden" id="idCodigoProyecto" name="idCodigoProyecto" value="<?php echo $idCodigoProyecto;?>">
					<fieldset id="fs_detalle">
						<legend>Proyecto</legend>
						
						<div data-linea="1">
							<label>Nombre:</label>
							<input type="text" id="nombreProyecto" name="nombreProyecto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $codigoProyecto['nombre']?>" disabled="disabled"/>
						</div>
						
						<div data-linea="2">
							<label>Código:</label>
							<input type="text" id="codigoProyecto" name="codigoProyecto" maxlength="3" data-er="^[0-9]+$" value="<?php echo $codigoProyecto['codigo_proyecto']?>" disabled="disabled"/>
						</div>		
						
						<div>
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>
			</td>
			
			<td>
				<form id="nuevoCodigoActividad" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="guardarCodigoActividad" >
					<input type="hidden" id="idPrograma" name="idPrograma" value="<?php echo $idPrograma;?>">
					<input type="hidden" id="idCodigoProyecto" name="idCodigoProyecto" value="<?php echo $idCodigoProyecto;?>">
					
					<fieldset>
						<legend>Actividad</legend>	
						
						<div data-linea="1">
							<label>Nombre:</label>
							<input type="text" id="nombreActividad" name="nombreActividad" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
						</div>
						
						<div data-linea="2">
							<label>Código:</label>
								<input type="text" id="codigoActividad" name="codigoActividad" maxlength="3" data-er="^[0-9]+$" required="required"/>
						</div>
						
						<div data-linea="3">
							<label>Provincia:</label>
								<select id="provincia" name="provincia" required="required">
									<option value="">Provincia....</option>
										<?php 	
											$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
											foreach ($provincias as $provincia){
												echo '<option value="' . $provincia['codigo'] . '" data-geografico="' . $provincia['geografico'] . '">' . $provincia['nombre'] . '</option>';
											}
										?>
								</select> 
							
								<input type="hidden" id="idProvincia" name="idProvincia" />
								<input type="hidden" id="nombreProvincia" name="nombreProvincia" />
								<input type="hidden" id="geograficoProvincia" name="geograficoProvincia" />
						</div>
						<div data-linea="3">
							<label id="lCanton">Cantón:</label>
								<select id="canton" name="canton" disabled="disabled" required="required">
								</select>
								
								<input type="hidden" id="idCanton" name="idCanton" />
								<input type="hidden" id="nombreCanton" name="nombreCanton" />
								<input type="hidden" id="geograficoCanton" name="geograficoCanton" />
						</div>
						<div>
							<button type="submit" class="mas">Agregar</button>		
						</div>

					</fieldset>
				</form>
				
				<fieldset>
					<legend>Actividades Registradas</legend>
					<table id="detalleCodigoActividad">
					
						<thead>
							<tr>
							    <th width="15%">Actividad</th>
								<th width="10%">Código</th>
								<th width="10%">Provincia</th>
								<th width="10%">Geográfico Provincia</th>
								<th width="10%">Cantón</th>
								<th width="10%">Geográfico Cantón</th>
								<th width="10%">Abrir</th>
								<th width="10%">Eliminar</th>
							</tr>
						</thead>
						
						<?php 
							while ($codigoActividad = pg_fetch_assoc($codigoActividades)){
								echo $cpp->imprimirLineaCodigoActividad($codigoActividad['id_codigo_actividad'], 
										$codigoActividad['nombre'], $codigoActividad['codigo_actividad'], 
										$idCodigoProyecto, $idPrograma, 
										$codigoActividad['provincia'], $codigoActividad['geografico_provincia'],
										$codigoActividad['canton'], $codigoActividad['geografico_canton'],
										'programacionAnualPresupuestaria');
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>

<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;

	$('document').ready(function(){
		$("#lCanton").hide();
	    $("#canton").hide();
	    
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
	
	$("#modificarCodigoProyecto").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreProyecto").val()) || !esCampoValido("#nombreProyecto")){
			error = true;
			$("#nombreProyecto").addClass("alertaCombo");
		}

		if(!$.trim($("#codigoProyecto").val()) || !esCampoValido("#codigoProyecto")){
			error = true;
			$("#codigoProyecto").addClass("alertaCombo");
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