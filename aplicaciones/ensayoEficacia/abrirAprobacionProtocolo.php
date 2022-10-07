<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	
	require_once 'abrirSolicitudProtocolo.php';

	require_once './clases/Perfil.php';

	$conexion = new Conexion();
	$ce=new ControladorEnsayoEficacia();
	$tipo_documento='EP';
	
	$usuario=$_SESSION['usuario'];

	$id_documento = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];
	$id_fase = $_POST['opcion'];
	

	$id_tramite_flujo = $_POST['nombreOpcion'];
	$tramite=$ce->obtenerTramiteEE($conexion,$id_tramite_flujo);

	//verifica si ya fue subsanado
	$subsanados=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,'EP');

	//identifico el perfil de la aprobacion
	$perfiles= $ce->obtenerPerfiles($conexion,$usuario);
	$perfil=new Perfil($perfiles);

	$fechaActual=new DateTime();
	$fechaTiempo=new DateTime($tramite['fecha_fin']);
	$sobreTiempo=false;
	if($fechaActual>$fechaTiempo)
		$sobreTiempo=true;

?>

<div id="P0" class="pestania" style="display: block;">

	<fieldset>
		<legend>Información precedente</legend>
		<div data-linea="1">
			<label>Protocolo : </label>
			<?php
			if($datosGenerales!=null && $datosGenerales['ruta']!=null)
				echo '<a href='.$datosGenerales['ruta'].' target="_blank">ver archivo</a>';
					?>
		</div>
		<div data-linea="2">
			<label>Observaciones:</label>
			<textarea class="justificado" data-distribuir="no" id="observacionAnterior" maxlength="2048"><?php
				echo htmlspecialchars( $tramite['observacion']);
					?></textarea>
		</div>
	</fieldset>

</div>

<fieldset id="cuadroAprobaciones">
	<legend>Aprobaciones</legend>
	<div id="areaComentario" style="display: block;" hidden="hidden">
		<label>Comentarios:</label>
		<textarea id="observacionSiguiente" class="habilitado" maxlength="2048"></textarea>
	</div>
	<hr />
	<div id="divEnviar" hidden="hidden">		
		<label>
			<input type="radio" name="opcionAprobar" value="C_I_O" />Retornar el trámite a subsanación interna
		</label>
		<br />
	
		<label>
			<input type="radio" name="opcionAprobar" value="" />Enviar protocolo
		</label>
		<br />
		<button id="btnEnviarTramite" type="button" class="guardar guardarObservaciones">Enviar trámite</button>
	</div>
</fieldset>

<fieldset id="verObservacionLimite">
	<legend>Datos necesarios</legend>
	<div data-linea="1">
      <label>Justificación del retraso de su respuesta :</label>
		<textarea id="retraso" name="retraso" class="habilitado" data-distribuir='no' maxlength="512"></textarea>
            
   </div>

</fieldset>

<script type="text/javascript">
		
	var id_flujo=<?php echo json_encode($id_flujo); ?>;
	var id_fase=<?php echo json_encode($id_fase); ?>;
	var id_documento=<?php echo json_encode($id_documento); ?>;
	var id_tramite_flujo=<?php echo json_encode($id_tramite_flujo); ?>;
	var idUsuario=<?php echo json_encode($idUsuario); ?>;
	var identificador=<?php echo json_encode($identificador); ?>;
	var subsanados=<?php echo json_encode($subsanados); ?>;
		
	var sobreTiempo=<?php echo json_encode($sobreTiempo); ?>;

	$("document").ready(function () {

		$('#detalleItem').find('h1').first().html("Aprobar solicitud de protocolo : "+protocolo.id_expediente);

		//deshabilita casillas
		$('section#detalleItem').find('input:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('div.ocultarOtros').hide();
		
		$('section#detalleItem').find('button:not(.bsig,.bant)').hide();
		$('section#detalleItem').find('textarea.habilitado').prop('disabled', false);

		var pestaniaReferente=$('#P1');
		pestaniaReferente.before($('#P0'));


		construirAnimacion(".pestania");

		pestaniaReferente=$('#P8');
		var elemento=pestaniaReferente.find('#btnFinalizar');
		elemento.before($('#cuadroAprobaciones'));

		elemento.before($('#verObservacionLimite'));

		elemento.before($('#btnEnviarTramite'));
		

		if(idUsuario==identificador){
			$('#areaComentario,#divEnviar,#btnEnviarTramite').hide();			//Oculta las secciones
		}
		else{
			$('#areaComentario,#divEnviar,#btnEnviarTramite').show();			//Visualiza las secciones
			$('input:radio[name="opcionAprobar"]').removeAttr('disabled');		//permite enviar los informes
			$('#observacionSiguiente').removeAttr('disabled');						//permite enviar los informes

		}

		if(sobreTiempo)
			$('#verObservacionLimite').show();
		else
			$('#verObservacionLimite').hide();

		mostrarPuntosSubsanados();

		distribuirLineas();
	});



	//*******************************************************

	$(".guardarObservaciones").click(function (event) {
		event.preventDefault();

		var error = false;
		if($('input[name="opcionAprobar"]:checked').val()===undefined)
			error = true;

		if(error){
			mostrarMensaje("Elija una de las opciones","FALLO");
			return;
		}

		if(sobreTiempo){
			if(!esNoNuloEsteCampo("#retraso"))
			{
				mostrarMensaje("Favor ingrese su justificación","FALLO");
				return;
			}
		}

		borrarMensaje();

		var param={opcion_llamada:'guardarAprobacionesSolicitud',
			id_flujo:id_flujo,
			id_tramite_flujo:id_tramite_flujo,
			id_documento:id_documento,
			condicion:$('input:radio[name=opcionAprobar]:checked').val(),
			retraso:$('#retraso').val(),
			observacion:$('#observacionSiguiente').val()
		};

		mostrarMensaje('Generando documentación...','');

		llamarServidor('ensayoEficacia','atenderFlujos',param,resultadoFlujo);

	});

	function resultadoFlujo(items){
		
		if(items!=null){
			
			mostrarMensaje('Trámite enviado','EXITO');
			$("#detalleItem").html('<div class="mensajeInicial">Trámite enviado.</div>');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);
			
		}else{

			mostrarMensaje('Errores al enviar la transacçión y no pudo ser compleada','FALLO');
		}

	}

	function resultadoCorreo(items){
	}

	function mostrarPuntosSubsanados(){
		if(subsanados!=null && subsanados.length>0){
			for(var i in subsanados){
				var obs=subsanados[i];
				$('#'+obs.elemento).css({backgroundColor: '#2BC253'});

				var div = $('#'+obs.elemento).parent();

				var id="obs_EP_"+obs.elemento+"_"+obs.id_tramite_flujo;
				var str='<input type="text" value"" class="observacionRealizadaVer" data-distribuir="no" id="'+id+'" name="'+id+'" disabled="disabled"/>';

				div.append(str);
				
				$('#'+id).val("Observacion: "+obs.observacion);
			}
		}
	}

	//*******************************************************

   </script>
