<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorControlEpidemiologico.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$ce = new ControladorControlEpidemiologico();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();

$notificacion = pg_fetch_assoc($ce->abrirNotificacion($conexion, $_POST['id']));

$especies = $cc->listarEspecies($conexion);

$qSitios = $cr->listarSitios($conexion, $notificacion['identificador_operador']);
?>

<header>
	<h1>Notificación epidemiológica</h1>
</header>


<div id="estado"></div>

<form id='actualizarNotificacion' data-rutaAplicacion='controlEpidemiologico' data-opcion='actualizarNotificacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	
	
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<fieldset>
	
		<input type="hidden" id="idNotificacion" name="idNotificacion" value="<?php echo $notificacion['id_notificacion'];?>" />
		
		<legend>Información del notificante</legend>
			
			<div data-linea="1">
				<label>Número de identificación: </label>
				<input type="text" id="identificadorNotificante" name="identificadorNotificante" disabled="disabled" placeholder="Número de identificación" data-er="^[0-9]+$" maxlength="10" value="<?php echo $notificacion['identificador_notificante'];?>"/>
			</div>
			
			<div data-linea="2">			
				<label>Nombres: </label>
					<input type="text" id="nombreNotificante" name="nombreNotificante" disabled="disabled" placeholder="Ej: Juan" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" value="<?php echo $notificacion['nombre_notificante'];?>"/>
			</div>
			
			<div data-linea="2">			
				<label>Apellidos: </label>
					<input type="text" id="apellidoNotificante" name="apellidoNotificante" disabled="disabled" placeholder="Ej: Pérez" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" value="<?php echo $notificacion['apellido_notificante'];?>"/>
			</div>
			
			<div data-linea="3">
				<label>Teléfono: </label>
					<input type="text" id="telefonoNotificante" name="telefonoNotificante" disabled="disabled" placeholder="Ej: (02) 456-9857" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" value="<?php echo $notificacion['telefono_notificante'];?>" />
			</div>
			
			<div data-linea="3">
				<label>Celular: </label>
					<input type="text" id="celularNotificante" name="celularNotificante" disabled="disabled" placeholder="Ej: (09) 9456-9857" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 9999-9999'" value="<?php echo $notificacion['celular_notificante'];?>"/>
			</div>
			
	</fieldset>
	
	<fieldset>
		<legend>Información del Propietario y Ubicación</legend>
			
			<div data-linea="1">			
				<label>Cédula/RUC: </label> <?php echo $notificacion['identificador_operador'];?>
					<input type="hidden" id="identificadorOperador" name="identificadorOperador" disabled="disabled" placeholder="Ej: 1915632485" data-er="^[0-9]+$" value="<?php echo $notificacion['identificador_operador'];?>"/>
			</div>
			
			<div data-linea="2">
				<label>Sitio</label>
				<select id="sitio" name="sitio" disabled="disabled">
					<option value="">Sitio....</option>
						<?php				
							while ($fila = pg_fetch_assoc($qSitios)){
								echo '<option value="'.$fila['codigo'].'"><b>'.$fila['nombre_lugar'].'</b>-'.$fila['provincia'].'-'.$fila['canton'].'-'.$fila['parroquia'].'</option>';
							}
						?>
				</select>
			 </div>
			
			<!-- <div data-linea="5">	
				<label>Nombre del sitio: </label> < ?php echo $notificacion['nombre_lugar'];?>
			</div>
			
			<div data-linea="7">
				<label>Provincia: </label> < ?php echo $notificacion['provincia'];?>
			</div>
			
			<div data-linea="7">
				<label>Cantón: </label> < ?php echo $notificacion['canton'];?>
			</div>
			
			<div data-linea="8">	
				<label>Parroquia: </label> < ?php echo $notificacion['parroquia'];?>
			</div>
			
			<div data-linea="9">
				<label>Dirección: </label> < ?php echo $notificacion['direccion'];?>
			</div>
			
			<div data-linea="10">	
				<label>Referencia: </label> < ?php echo $notificacion['referencia'];?>
			</div> -->
			
	</fieldset>
		
	<fieldset>
		<legend>Información epidemiológica</legend>
			<div data-linea="1">			
				<label>Especie afectada: </label> 
					<select id="especie" name="especie" disabled="disabled">
						<option value="">Especie....</option>
						<?php 
							foreach ($especies as $especie){
								echo '<option value="' . $especie['codigo'] . '">' . $especie['nombre'] . '</option>';
							}
						?>
					</select> 
					
					<input type="hidden" id="nombreEspecie" name="nombreEspecie" />
			</div>
			
			<div data-linea="1">			
				<label>Población afectada: </label>
					<input type="text" id="poblacionAfectada" name="poblacionAfectada" disabled="disabled" placeholder="Ej: 15" data-er="^[0-9]+$" value="<?php echo $notificacion['poblacion_afectada'];?>" />
			</div>
			
			<div data-linea="2">			
				<label>Patología denunciada: </label>
					<input type="text" id="patologia" name="patologia" disabled="disabled" placeholder="Ej: Fiebre aftosa" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $notificacion['patologia_notificada'];?>"/>
			</div>			
			
	</fieldset>

</form>

<script type="text/javascript">
	var estado= <?php echo json_encode($estado); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
		cargarValorDefecto("sitio","<?php echo $notificacion['codigo_sitio'];?>");
		cargarValorDefecto("especie","<?php echo $notificacion['id_especie'];?>");
	});

	$("#especie").change(function(){
    	$("#nombreEspecie").val($("#especie option:selected").text());
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#actualizarNotificacion").submit(function(event){
		event.preventDefault();
		chequearCampos(this);
	});

	 /*VALIDACION*/
    function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

    function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#identificadorNotificante").val()) || !esCampoValido("#identificadorNotificante") || $("#identificadorNotificante").val().length != $("#identificadorNotificante").attr("maxlength")){
			error = true;
			$("#identificadorNotificante").addClass("alertaCombo");
		}

		if(!$.trim($("#nombreNotificante").val()) || !esCampoValido("#nombreNotificante")){
			error = true;
			$("#nombreNotificante").addClass("alertaCombo");
		}

		if(!$.trim($("#apellidoNotificante").val()) || !esCampoValido("#apellidoNotificante")){
			error = true;
			$("#apellidoNotificante").addClass("alertaCombo");
		}

		if(!$.trim($("#telefonoNotificante").val()) || !esCampoValido("#telefonoNotificante")){
			error = true;
			$("#telefonoNotificante").addClass("alertaCombo");
		}

		if(!$.trim($("#celularNotificante").val()) || !esCampoValido("#celularNotificante")){
			error = true;
			$("#celularNotificante").addClass("alertaCombo");
		}
		
		if(!$.trim($("#especie").val()) || !esCampoValido("#especie")){
			error = true;
			$("#especie").addClass("alertaCombo");
		}

		if(!$.trim($("#poblacionAfectada").val()) || !esCampoValido("#poblacionAfectada")){
			error = true;
			$("#poblacionAfectada").addClass("alertaCombo");
		}

		if(!$.trim($("#patologia").val()) || !esCampoValido("#patologia")){
			error = true;
			$("#patologia").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}

</script>