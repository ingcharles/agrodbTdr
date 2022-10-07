<?php
session_start();

require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorEnsayoEficacia.php';


require_once 'abrirSolicitudDossier.php';

require_once '../ensayoEficacia/clases/Perfil.php';

$conexion = new Conexion();
$ce=new ControladorEnsayoEficacia();

$tipo_documento='DF';

$usuario=$_SESSION['usuario'];

$id_documento = $_POST['id'];
$id_flujo = $_POST['idFlujo'];
$id_fase = $_POST['opcion'];


$id_tramite_flujo = $_POST['nombreOpcion'];
$tramiteFlujo=$ce->obtenerTramiteEE($conexion,$id_tramite_flujo);

//verifica si ya fue subsanado
$subsanados=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,$tipo_documento);

//identifico el perfil de la aprobacion
$perfiles= $ce->obtenerPerfiles($conexion,$usuario);
$perfil=new Perfil($perfiles);

$esPerfilCoordinador=false;
if(($perfil->tieneEstePerfil('PFL_EE_CRIA')))
	$esPerfilCoordinador=true;

$fechaActual=new DateTime();
$fechaTiempo=new DateTime($tramiteFlujo['fecha_fin']);
$sobreTiempo=false;
if($fechaActual>$fechaTiempo)
	$sobreTiempo=true;

?>

<div id="P0" class="pestania" style="display: block;">
	<div class="justificado">
		<label>Observaciones:</label>
		<textarea id="observacionAnterior" maxlength="2048">
			<?php
			echo htmlspecialchars( $tramiteFlujo['observacion']);
            ?>

		</textarea>
	</div>
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
			<input type="radio" name="opcionAprobar" value="C_I_O" />Retornar el trámite con observación interna
		</label>
		<br />
		
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
	var esPerfilCoordinador=<?php echo json_encode($esPerfilCoordinador); ?>;

	var sobreTiempo=<?php echo json_encode($sobreTiempo); ?>;


	$("document").ready(function () {

		$('#detalleItem').find('h1').first().html("Aprobar solicitud de dossier : "+solicitud.id_expediente);

		//deshabilita casillas
		$('section#detalleItem').find('input:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('div.ocultarOtros').hide();

		$('section#detalleItem').find('button:not(.bsig,.bant,.btnVistaPreviaDossier)').hide();

		$('section#detalleItem').find('textarea.habilitado').prop('disabled', false);

		var pestaniaReferente=$('#P1');
		pestaniaReferente.before($('#P0'));


		construirAnimacion(".pestania");

		pestaniaReferente=$('#P9');
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
		mostrarPuntosSubsanados();

		if(sobreTiempo)
			$('#verObservacionLimite').show();
		else
			$('#verObservacionLimite').hide();
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
		mostrarMensaje("Preparando documentación, favor espere...","");
		llamarServidor('dossierFertilizante','atenderFlujosFertilizantes',param,resultadoFlujo);

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


		form.attr('data-opcion', 'crearCertificado');

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
