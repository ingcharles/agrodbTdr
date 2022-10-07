<?php
session_start();

	require_once '../../clases/Conexion.php';

	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPecuario.php';

	require_once 'abrirSolicitudDossier.php';

	require_once '../ensayoEficacia/clases/Perfil.php';

	$conexion = new Conexion();
	$ce=new ControladorEnsayoEficacia();
	$cp=new ControladorDossierPecuario();
	$tipo_documento='DP';

	$usuario=$_SESSION['usuario'];

	$id_documento = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];
	$id_fase = $_POST['opcion'];


	$id_tramite_flujo = $_POST['nombreOpcion'];

	$tramiteFlujo=$cp->obtenerTramiteFlujoDP($conexion,$id_tramite_flujo);

	//verifica si ya fue subsanado
	$subsanados=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,$tipo_documento);

	//identifico el perfil de la aprobacion
	$perfiles= $ce->obtenerPerfiles($conexion,$usuario);
	$perfil=new Perfil($perfiles);

	$esPerfilCoordinador=false;
	if(($perfil->tieneEstePerfil('PFL_EE_DDTA')) || ($perfil->tieneEstePerfil('PFL_EE_CRIA')))
		$esPerfilCoordinador=true;

	$fechaActual=new DateTime();
	$fechaTiempo=new DateTime($tramiteFlujo['fecha_fin']);
	$sobreTiempo=false;
	if($fechaActual>$fechaTiempo)
		$sobreTiempo=true;

?>

<div class="pestania" id="P0" style="display: block;">
	<fieldset>
		<legend>Resultado de revisión</legend>
		
		<label>Observaciones:</label>
		<div data-linea="100">    		
    		<textarea id="observacionAnterior" maxlength="2048"><?php echo $tramiteFlujo['observacion']; ?></textarea>
		</div>

		<div data-linea="101">
			<label>Archivo adjunto</label> <?php echo ($tramiteFlujo['ruta_archivo']=='0'? '<span class="alerta">No hay ningún archivo adjunto</span>': $tramiteFlujo['ruta_archivo']==''? '<span class="alerta">No hay ningún archivo adjunto</span>' : '<a href='.$tramiteFlujo['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
		</div>
	</fieldset>
</div>

<fieldset id="cuadroAprobaciones">
	<legend>Aprobaciones</legend>
	<div data-linea="1" >
		<label>Importante:</label>
		<?php
		if($tramiteFlujo['pendiente']=='O'){
			$str='<label">';
			$str=$str.'El analista realizó observaciones a éste trámite';
			$str=$str.'</label>';
			echo $str;
		}
		else{
			$str='<label">';
			$str=$str.'El analista no realizó ningúna observación a éste trámite';
			$str=$str.'</label>';
			echo $str;
		}

        ?>
	</div>
	<div class="justificado" id="areaComentario" hidden="hidden">
		<label>Comentarios:</label>
		<textarea id="observacionSiguiente" class="habilitado" maxlength="2048"></textarea>
	</div>
	<hr />
	<div data-linea="3" class="divEnviar" hidden="hidden">
		<label>
			<input type="radio" name="opcionAprobar" value="C_I_O" />Retornar el trámite con observación interna
		</label>
	</div>
	<div data-linea="5" class="divEnviar" hidden="hidden">
		<?php
		if($esPerfilCoordinador){
				$str='<label class="btnCoordinador">';
				if($tramiteFlujo['pendiente']=='O')
					$str=$str.'<input type="radio" name="opcionAprobar" value="C_D_O" />Enviar trámite observado al operador';
				else
					$str=$str.'<input type="radio" name="opcionAprobar" value="" />Enviar trámite aprobado al operador';
				$str=$str.'</label>';
				$str=$str.'<br />';
				echo $str;
			}
		else{
			$str='<label class="btnDirector">';
			if($tramiteFlujo['pendiente']=='O')
				$str=$str.'<input type="radio" name="opcionAprobar" value="" />Enviar trámite observado';
			else
				$str=$str.'<input type="radio" name="opcionAprobar" value="" />Enviar trámite aprobado';
			$str=$str.'</label>';
			$str=$str.'<br />';
			echo $str;

		}

        ?>


	</div>
	<div data-linea="7" class="divEnviar" hidden="hidden">
		<button id="btnEnviarTramite" type="button" class="enviar guardarObservaciones">Enviar trámite</button>
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
	var esPerfilCoordinador=<?php echo json_encode($esPerfilCoordinador); ?>;

	var sobreTiempo=<?php echo json_encode($sobreTiempo); ?>;



	$("document").ready(function () {

		$('#detalleItem').find('h1').first().html("Aprobar solicitud de dossier : "+solicitud.id_expediente);

		//deshabilita casillas
		$('section#detalleItem').find('input:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('div.ocultarOtros').hide();

		$('section#detalleItem').find('div.noRevision').hide();
		
		$('section#detalleItem').find('button:not(.bsig,.bant,.btnVistaPreviaDossier)').hide();

		$('section#detalleItem').find('textarea.habilitado').prop('disabled', false);
		
		var pestaniaReferente=$('#P1');
		pestaniaReferente.before($('#P0'));


		construirAnimacion(".pestania");

		pestaniaReferente=$('#P10');
		var elemento=pestaniaReferente.find('#btnFinalizar');
		elemento.before($('#cuadroAprobaciones'));

		elemento.before($('#verObservacionLimite'));

		elemento.before($('#btnEnviarTramite'));


		if(idUsuario==identificador){
			$('#areaComentario,.divEnviar,#btnEnviarTramite').hide();			//Oculta las secciones
		}
		else{
			$('#areaComentario,.divEnviar,#btnEnviarTramite').show();			//Visualiza las secciones
			$('input:radio[name="opcionAprobar"]').removeAttr('disabled');		//permite enviar los informes
			$('#observacionSiguiente').removeAttr('disabled');						//permite enviar los informes

		}

		if(sobreTiempo)
			$('#verObservacionLimite').show();
		else
			$('#verObservacionLimite').hide();
		
		distribuirLineas();

		mostrarPuntosSubsanados();

		//Ocultar formulario de Origen del Producto
		$('#frmProcedencia').hide();	
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
		mostrarMensaje("Preparando documentación, favor espere...","FALLO");
		llamarServidor('dossierPecuario','atenderFlujosPecuarios',param,resultadoFlujo);

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

	//***************************** VISTA PREVIA CERTIFICADO***************************************
	$('button.btnVistaPreviaCertificado').click(function (event) {

		event.preventDefault();

		var form=$(this).parent();
		form.append("<input type='hidden' id='id_solicitud' name='id_solicitud' value='"+solicitud.id_solicitud+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='id_protocolo' name='id_protocolo' value='"+$('#protocolo').val()+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='producto_nombre' name='producto_nombre' value='"+$('#producto_nombre').val()+"' />");
		form.append("<input type='hidden' id='normativa' name='normativa' value='"+$('#normativa').val()+"' />");
		form.append("<input type='hidden' id='ingrediente_activo' name='ingrediente_activo' value='"+$('#producto_ia').html()+"' />");
		form.append("<input type='hidden' id='ingredientes_paises' name='ingredientes_paises' value='"+$('#producto_pais').val()+"' />");
		form.append("<input type='hidden' id='usos' name='usos' value='"+$('#producto_uso').val()+"' />");
		form.append("<input type='hidden' id='formulacion' name='formulacion' value='"+$('#producto_formulacion').val()+"' />");
		form.append("<input type='hidden' id='formuladores_paises' name='formuladores_paises' value='"+$('#producto_pais_producto').val()+"' />");

		form.attr('data-opcion', 'crearCertificadoPecuario');

		mostrarMensaje("Generando archivo ... ",'FALLO');
		$('#verReporteCertificado').hide();
		ejecutarJson(form,new exitoVistaPreviaCertificado());

	});


	function exitoVistaPreviaCertificado(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verReporteCertificado').show();
			$('#verReporteCertificado').attr('href',msg.datos);
		};
	}

</script>
