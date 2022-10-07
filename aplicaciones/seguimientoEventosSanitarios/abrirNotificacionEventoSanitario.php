<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorNotificacionEventoSanitario.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	$cpco = new ControladorNotificacionEventoSanitario();
	
	$identificador=$_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
		
		$perfilAdmin = pg_fetch_result($cu->buscarPerfilUsuario($conexion, $identificador, 'Técnico Notificacion Evento Sanitario'),0,'id_perfil');
	}
	
	$ruta = 'seguimientoEventosSanitarios';
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	
	$patologias = $cpco->listarCatalogos($conexion,'PATOLOGIAS');
	$especies = $cpco->listarCatalogos($conexion,'ESPECIES');

	$idNotificacionEventoSanitario = $_POST['id'];
	
	$eventoSanitario = pg_fetch_assoc($cpco->abrirNotificacionEventoSanitario($conexion, $idNotificacionEventoSanitario));
	
	$tiposPatologiaEspecieAfectada = $cpco->listarTipoPatologiaEspecieAfectada($conexion, $idNotificacionEventoSanitario);
	$tiposPatologiaEspecieAfectadaCon = $cpco->listarTipoPatologiaEspecieAfectada($conexion, $idNotificacionEventoSanitario);


?>

<header>
	<h1>Notificacion de Eventos Sanitarios</h1>
</header>

<div id="estado1"></div>
<div id="estado"></div>

<div class="pestania">

<form id="modificarNotificacionEventoSanitario" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="modificarNotificacionEventoSanitario" data-destino="detalleItem">
	<input type='hidden' id='idNotificacionEventoSanitario' name='idNotificacionEventoSanitario' value="<?php echo $idNotificacionEventoSanitario;?>" />

	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
		
	<div id="informacion">
		<fieldset>
			<legend>Información General</legend>

			<div data-linea="1">
				<label id="lNumero">Número:</label>
				<?php echo $eventoSanitario['numero'];?> 
			</div>
			
			<div data-linea="1">
				<label id="lNumero">ID:</label>
				<?php echo $eventoSanitario['id_notificacion_evento_sanitario'];?> 
			</div>
		
			<div data-linea="2">
				<label id="lFecha">Fecha:</label>
				<?php echo $eventoSanitario['fecha'];?> 
			</div>
		
			<div data-linea="3">
				<label id="lOrigenNotificacion">Origen de la Notificación:</label>
				<?php echo $eventoSanitario['nombre_origen'];?> 
			</div>
		
			<div data-linea="4">
				<label id="lCanalNotificacion">Canal de la Notificación:</label>
				<?php echo $eventoSanitario['nombre_canal'];?> 
			</div>
		</fieldset>
	
		<fieldset>
			<legend>Datos Informante</legend>
		
			<div data-linea="5">
				<label id="lNombre">Nombre:</label>
				<?php echo $eventoSanitario['nombre_informante'];?> 
			</div>
				
			<div data-linea="6">
				<label id="lTelefono">Teléfono:</label>
				<?php echo $eventoSanitario['telefono_informante'];?> 
			</div>
		
			<div data-linea="7">
				<label id="lCelular">Celular:</label>
				<?php echo $eventoSanitario['celular_informante'];?> 
			</div>
		
			<div data-linea="8">
				<label id="lCorreoElectronico">Correo Electrónico:</label>
				<?php echo $eventoSanitario['correo_electronico_informante'];?> 
			</div>
		</fieldset>
	
		<fieldset>
			<legend>Información del Predio</legend>

			<div data-linea="9">
				<label id="lProvincia">Provincia</label>
				<?php echo $eventoSanitario['provincia'];?> 
			</div>
			
			<div data-linea="10">
				<label id="lCanton">Cantón</label>
				<?php echo $eventoSanitario['canton'];?> 
			</div>
			
			<div data-linea="11">	
				<label id="lParroquia">Parroquia</label>
				<?php echo $eventoSanitario['parroquia'];?> 
			</div>
				
			<div data-linea="12">
				<label id="lSitio">Sitio:</label>
				<?php echo $eventoSanitario['sitio_predio'];?> 
			</div>

			<div data-linea="13">
				<label id="lFinca">Finca:</label>
				<?php echo $eventoSanitario['finca_predio'];?> 
			</div>
		</fieldset>

	</div>
	
	<div id="actualizacion">	
			<fieldset>
				<legend>Información General</legend>

				<div data-linea="1">
					<label id="lNumero">Número:</label>
					<?php echo $eventoSanitario['numero'];?> 
				</div>
				
				<div data-linea="1">
					<label id="lNumero">ID:</label>
					<?php echo $eventoSanitario['id_notificacion_evento_sanitario'];?> 
				</div>
		
				<div data-linea="2">
					<label id="lFecha">Fecha:</label>
					<?php echo date('j/n/Y',strtotime($eventoSanitario['fecha']));?> 
				</div>
		
				<div data-linea="3">
					<label id="lOrigenNotificaciona">Origen de la Notificación:</label>
					<?php echo $eventoSanitario['nombre_origen'];?> 
				</div>
		
				<div data-linea="3">
					<label id="lCanalNotificacion">Canal de la Notificación:</label>
					<?php echo $eventoSanitario['nombre_canal'];?> 
				</div>
			</fieldset>
	
			<fieldset>
				<legend>Datos Informante</legend>
		
				<div data-linea="3">
					<label id="lNombre">Nombre:</label>
					<input type="text" id="nombreInformante" name="nombreInformante" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $eventoSanitario['nombre_informante'];?>"/>
				</div>
				
				<div data-linea="3">
					<label id="lTelefono">Teléfono:</label>
					<input type="text" id="telefonoInformante" name="telefonoInformante" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15" value="<?php echo $eventoSanitario['telefono_informante'];?>"/>
				</div>
		
				<div data-linea="4">
					<label id="lCelular">Celular:</label>
					<input type="text" id="celularInformante" name="celularInformante" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15" value="<?php echo $eventoSanitario['celular_informante'];?>"/>
				</div>
		
				<div data-linea="4">
					<label id="lCorreoElectronico">Correo Electrónico:</label>
					<input type="text" id="correoElectronicoInformante" name="correoElectronicoInformante" maxlength="32" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" value ="<?php echo $eventoSanitario['correo_electronico_informante'];?>"/>
				</div>
			</fieldset>
	
			<fieldset>
				<legend>Información del Predio</legend>

				<div data-linea="5">
					<label id="lProvincia">Provincia</label>
					<?php echo $eventoSanitario['provincia'];?> 
				</div>
			
				<div data-linea="5">
					<label id="lCanton">Cantón</label>
					<?php echo $eventoSanitario['canton'];?> 
				</div>
			
				<div data-linea="6">	
					<label id="lParroquia">Parroquia</label>
					<?php echo $eventoSanitario['parroquia'];?> 
				</div>
				
				<div data-linea="7">
					<label id="lSitio">Sitio:</label>
					<input type="text" id="sitioPredio" name="sitioPredio" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $eventoSanitario['sitio_predio'];?>"/>
				</div>

				<div data-linea="7">
					<label id="lFinca">Finca:</label>
					<input type="text" id="fincaPredio" name="fincaPredio" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" value="<?php echo $eventoSanitario['finca_predio'];?>"/>
				</div>
			</fieldset>

		</div>
	</form>

</div>


<div class="pestania">
	<h2>Patología, Especie afectada</h2>

	<form id="nuevaPatologiaEspecieAfectada" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarPatologiaEspecieAfectada" data-destino="detalleItem">
		<input type='hidden' id='idNotificacionEventoSanitario' name='idNotificacionEventoSanitario' value="<?php echo $idNotificacionEventoSanitario;?>" />
		
		<fieldset>
			<legend>Patología, Especie afectada</legend>
			
			<div data-linea="3">
				<label>Patología Denunciada:</label>
                                        <select id="patologiaDenunciada" name="patologiaDenunciada" required="">
						<option value="">Patologia....</option>
						<?php 
							while ($patologia = pg_fetch_assoc($patologias)){
							echo '<option value="' . $patologia['codigo'] . '">' . $patologia['nombre'] . '</option>';
						}
						?>
					</select> 					
			</div>
			
			<div data-linea="4">
				<input type="text" id="nombrePatologia" name="nombrePatologia" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
			</div>
			
			<div data-linea="5">
				<label>Especie Afectada:</label>
                                        <select id="especieAfectada" name="especieAfectada" required="">
						<option value="">Especie....</option>
						<?php 
							while ($especie = pg_fetch_assoc($especies)){
							echo '<option value="' . $especie['codigo'] . '">' . $especie['nombre'] . '</option>';
						}
						?>
					</select> 
                                <input type="text" id="nombreEspecie" name="nombreEspecie" style="display: none"/>
			</div>
			
			<div data-linea="7">
				<label>Animales Enfermos:</label>
                                <input type="text" id="animalesEnfermos" name="animalesEnfermos" maxlength="6" data-er="^[0-9]+$" required=""/>
			</div>
			
			<div data-linea="7">
				<label>Animales Muertos:</label>
                                <input type="text" id="animalesMuertos" name="animalesMuertos" maxlength="6" data-er="^[0-9]+$" required=""/>
			</div>
			
			
			<div>
				<button type="submit" class="mas">Agregar</button>		
			</div>
		</fieldset>
	</form>
	
	<fieldset id="detallePatologiaEspecieAfectadaFS">
		<legend>Patología, Especie afectada</legend>
		<table id="detallePatologiaEspecieAfectada">
			<thead>
				<tr>
					<th width="15%">Patología Denunciada</th>
					<th width="15%">Especie Afectada</th>
					<th width="15%">Animales Enfermos</th>
					<th width="15%">Animales Muertos</th>
					<th width="5%">Eliminar</th>
				</tr>
			</thead>
			<?php 
				while ($tipoPatologiaEspecieAfectada = pg_fetch_assoc($tiposPatologiaEspecieAfectada)){
					echo $cpco->imprimirLineaPatologiaEspecieAfectada(	$tipoPatologiaEspecieAfectada['id_patologia_especie_afectada'], 
																		$tipoPatologiaEspecieAfectada['id_notificacion_evento_sanitario'],
																		$tipoPatologiaEspecieAfectada['nombre_patologia'], 
																		$tipoPatologiaEspecieAfectada['nombre_especie'], 
																		$tipoPatologiaEspecieAfectada['animales_enfermos'], 
																		$tipoPatologiaEspecieAfectada['animales_muertos'],
																		$ruta);
				}
			?>
		</table>
	</fieldset>
	
	<fieldset id="detallePatologiaEspecieAfectadaFSConsulta">
		<legend>Patología, Especie afectada</legend>
		<table id="detallePatologiaEspecieAfectada">
			<thead>
				<tr>
					<th width="15%">Patología Denunciada</th>
					<th width="15%">Especie Afectada</th>
					<th width="15%">Animales Enfermos</th>
					<th width="15%">Animales Muertos</th>
				</tr>
			</thead>
			<?php 
				while ($tipoPatologiaEspecieAfectada1 = pg_fetch_assoc($tiposPatologiaEspecieAfectadaCon)){
					echo $cpco->imprimirLineaPatologiaEspecieAfectadaConsulta(	$tipoPatologiaEspecieAfectada1['id_patologia_especie_afectada'], 
																		$tipoPatologiaEspecieAfectada1['id_notificacion_evento_sanitario'],
																		$tipoPatologiaEspecieAfectada1['nombre_patologia'], 
																		$tipoPatologiaEspecieAfectada1['nombre_especie'], 
																		$tipoPatologiaEspecieAfectada1['animales_enfermos'], 
																		$tipoPatologiaEspecieAfectada1['animales_muertos'],
																		$ruta);
				}
			?>
		</table>
	</fieldset>
</div>

<div class="pestania">

	<h2>Planificación de la Primera Visita</h2>

	<form id="nuevaPlanificacionInspeccionNotificacionEventoSanitario" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarPlanificacionInspeccionNotificacionEventoSanitario" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type='hidden' id='idNotificacionEventoSanitario' name='idNotificacionEventoSanitario' value="<?php echo $idNotificacionEventoSanitario;?>" />

		<fieldset id="planificarNuevaInspeccion">
			<legend>Planificación de Primera Visita</legend>
			<div data-linea="26">
				<label>Fecha Primera Visita:</label>
				<input type="text" id="fechaNuevaInspeccion" name="fechaNuevaInspeccion" />
			</div>				

		</fieldset>	
		
		<fieldset id="planificarEventoSanitarioFS">
			<legend>Planificación de Primera Visita</legend>
			<div data-linea="26">
				<label>Fecha de Primera Visita:</label>
				<?php echo $eventoSanitario['fecha_nueva_inspeccion'];?>
			</div>	

		</fieldset>	
						
		<div data-linea="27">
			<button id="guardarNuevaInspeccion" type="submit" class="guardar">Guardar</button>
		</div>
	</form>	
	
	<fieldset id="planificarNuevaInspeccionCerrado">
		<legend>Planificación de Primera Visita</legend>
			<div data-linea="26">
				<label>
					<?php 
						if($eventoSanitario['estado'] == 'Creado'){
							echo "El proceso no tiene una fecha de primera visita asignada";
						}else{
							if($eventoSanitario['estado'] == 'Pendiente'){
								echo "El proceso ya tiene una fecha de primera visita asignada para el ". date('j/n/Y',strtotime($eventoSanitario['fecha_nueva_inspeccion']));
							}else{
								if($eventoSanitario['es_evento_sanitario'] == 't'){
									$numero = $eventoSanitario['numero_formulario'];
									echo "El proceso de inspecciones ha finalizado - Se ha generado el evento sanitario: '$numero' ";	
								}else {
									echo "El proceso de inspecciones ha finalizado";	
								}
							}
						}
					?>
				</label>
			</div>				

	</fieldset>	
</div>


<script type="text/javascript">

	var usuario = <?php echo json_encode($usuario); ?>;
	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_parroquia= <?php echo json_encode($parroquias); ?>;
	var estado= <?php echo json_encode($eventoSanitario['estado']); ?>;
	var perfil= <?php echo json_encode($perfilAdmin); ?>;
	var fechaInspeccion= new Date(<?php echo json_encode($eventoSanitario['fecha_nueva_inspeccion']); ?>);

	$("document").ready(function(){
		distribuirLineas();	
		construirValidador();
		construirAnimacion($(".pestania"));
		$('#detallePatologiaEspecieAfectadaConsultaFS').hide();
		$('#detallePatologiaEspecieAfectadaFSConsulta').hide();
		$('#planificarEventoSanitarioFS').hide();
		$("#actualizacion").hide();
		$('#nombrePatologia').hide();

		$("#fecha").datepicker({
			changeMonth: true,
			 changeYear: true
		});
		
		$("#fechaInspeccion").datepicker({
			changeMonth: true,
			changeYear: true
		});

		$("#fechaNuevaInspeccion").datepicker({
			changeMonth: true,
			changeYear: true
		});
		
		acciones("#nuevaPatologiaEspecieAfectada","#detallePatologiaEspecieAfectada");
		
		if(usuario == '0'){
			$("#estado1").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#botonGuardar").attr("disabled", "disabled");
		}
		
		if(<?php echo json_encode($eventoSanitario['nueva_inspeccion']); ?>=='Si'){
			$('#siguienteInspeccion').show();
			$('#siguienteInspeccion').addClass('exito'); //exito, advertencia, alerta
		}else{
			$('#siguienteInspeccion').hide();
		}
		
		if(estado == 'Cerrado'){	
			
			/*if(perfil != false){
				$("#modificar").show();
				$("#nuevaPatologiaEspecieAfectada").show();
					$('#detallePatologiaEspecieAfectadaConsultaFS').hide();
					$('#detallePatologiaEspecieAfectadaFS').show();
				$("#nuevaPlanificacionInspeccionNotificacionEventoSanitario").show();
					$('#planificarNuevaInspeccionCerrado').hide();
					$("#planificarNuevaInspeccion").hide();
					$("#planificarEventoSanitarioFS").show();
					$("#guardarNuevaInspeccion").hide();
					$("#subirArchivoInforme").hide();
			}else{*/
				$("#modificar").hide();
				$("#nuevaPatologiaEspecieAfectada").hide();
					$('#detallePatologiaEspecieAfectadaConsultaFS').show();
					$('#detallePatologiaEspecieAfectadaFS').hide();
					$('#detallePatologiaEspecieAfectadaFSConsulta').show();
				$("#nuevaPlanificacionInspeccionNotificacionEventoSanitario").show();
					$('#planificarNuevaInspeccionCerrado').show();
					$("#planificarNuevaInspeccion").hide();
					$("#planificarEventoSanitarioFS").show();
					$("#guardarNuevaInspeccion").hide();
					$("#subirArchivoInforme").hide();
			//}
		}
	});
	
	
	$("#modificar").click(function(){
		$('.bsig').attr("disabled","disabled");
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		$("#informacion").hide();
		$("#actualizacion").show();
		$("#adjuntos").hide();
		$("#adjuntosInforme").hide();		
	});
	
	$("#patologiaDenunciada").change(function(event){
		if($("#patologiaDenunciada option:selected").val()!='0'){
        	$('#nombrePatologia').hide();
    		$("#nombrePatologia").val($("#patologiaDenunciada option:selected").text());
        }else{
        	$("#nombrePatologia").val('');
    	    $('#nombrePatologia').show();
        }
	});
		
	$("#especieAfectada").change(function(){
            
            if($("#especieAfectada option:selected").val()!=='18'){
                $('#nombreEspecie').css("display","none");
                $("#nombreEspecie").val($("#especieAfectada option:selected").text());
            }else{
        	$("#nombreEspecie").val('');
                $('#nombreEspecie').css("display","block");
            }
	});
	
	//Validación y Guardado
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	$("#modificarNotificacionEventoSanitario").submit(function(event){

		$("#modificarNotificacionEventoSanitario").attr('data-opcion', 'modificarNotificacionEventoSanitario');
	    $("#modificarNotificacionEventoSanitario").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if(!$.trim($("#nombreInformante").val()) || !esCampoValido("#nombreInformante")){
			error = true;
			$("#nombreInformante").addClass("alertaCombo");
		}

		/*if(!$.trim($("#telefonoInformante").val()) || !esCampoValido("#telefonoInformante")){
			error = true;
			$("#telefonoInformante").addClass("alertaCombo");
		}*/

		/*if(!$.trim($("#celularInformante").val()) || !esCampoValido("#celularInformante")){
			error = true;
			$("#celularInformante").addClass("alertaCombo");
		}*/

		/*if(!$.trim($("#correoElectronicoInformante").val()) || !esCampoValido("#correoElectronicoInformante")){
			error = true;
			$("#correoElectronicoInformante").addClass("alertaCombo");
		}*/

		if(!$.trim($("#sitioPredio").val()) || !esCampoValido("#sitioPredio")){
			error = true;
			$("#sitioPredio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#fincaPredio").val()) || !esCampoValido("#fincaPredio")){
			error = true;
			$("#fincaPredio").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			abrir($(this),event,false);

			$('.bsig').removeAttr("disabled","disabled");
			$("input").attr("disabled");
			$("select").attr("disabled");
			$("#modificar").removeAttr("disabled");
			$("#informacion").show();
			$("#actualizacion").hide();
		}
	});

		//Planificación de Inspección
	$("#nuevaPlanificacionInspeccionNotificacionEventoSanitario").submit(function(event){

		$("#nuevaPlanificacionInspeccionNotificacionEventoSanitario").attr('data-opcion', 'guardarPlanificacionInspeccionNotificacionEventoSanitario');
	    $("#nuevaPlanificacionInspeccionNotificacionEventoSanitario").attr('data-destino', 'detalleItem');

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($(this));
		}
	});
	
	$("#archivoInforme").click(function(){
    	$("#subirArchivoInforme button").removeAttr("disabled");
    });
	
</script>