<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPlaguicida.php';

	require_once 'abrirSolicitudDossier.php';

	$conexion = new Conexion();
	$ce=new ControladorEnsayoEficacia();
	$cg=new ControladorDossierPlaguicida();

	$id_documento = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];

	//Busca si hay un trámite en curso de subsanación
	$id_fase=$ce->obtenerFaseDelFlujo($conexion,$id_flujo,'subsanarDossier');
	
	$resultado=$cg->obtenerFlujosDeTramitesSolicitudDG($conexion,$identificador,$id_fase,$id_documento);
	$tramite_flujo=pg_fetch_assoc($resultado,0);
	$id_tramite_flujo = $tramite_flujo['id_tramite_flujo'];

	$tipo_documento='DG';
	$observaciones=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,$tipo_documento);

	//recupera los ingredientes
	$ias=$cg->obtenerIngredientesSolicitud($conexion, $id_documento);
	$subsanadosIa=array();
	foreach($ias as $item){
		$obsIa=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,$tipo_documento,null,$item['id_solicitud_ia']);
		if(count($obsIa)>0){
			foreach($obsIa as $observacion){
				$observacion["ingrediente_activo"]=$item["ingrediente_activo"];
				$subsanadosIa[]=$observacion;
				
			}
		}
	}
	unset($ias);

?>

<div id="P0" class="pestania" style="display: block;">
   <label>Observaciones:</label>
   <textarea id="observacionAnterior" maxlength="2048">
		<?php
		echo $tramite_flujo['observacion'];
		?>

	</textarea>
</div>

<script type="text/javascript">

	var observaciones=<?php echo json_encode($observaciones); ?>;
	var id_flujo=<?php echo json_encode($id_flujo); ?>;
	var id_fase=<?php echo json_encode($id_fase); ?>;
	var id_documento=<?php echo json_encode($id_documento); ?>;
	var id_tramite_flujo=<?php echo json_encode($id_tramite_flujo); ?>;
	var idUsuario=<?php echo json_encode($idUsuario); ?>;
	var identificador=<?php echo json_encode($identificador); ?>;
	var subsanadosIa=<?php echo json_encode($subsanadosIa); ?>;

	$("document").ready(function(){

		$('#detalleItem').find('h1').first().html("Subsanación del dossier : "+solicitud.id_expediente);
		
		//verifica si existen observaciones para abrir todo el documento
		var todo=true;
		for(var i in observaciones){
			var obs=observaciones[i];
			if(obs.ver=='T'){	//Encontro al menos una con codigo T=todo
				todo=false;
				var elemento=$('#'+obs.elemento);
				var div = elemento.parent();
				var id="obs_EP_"+obs.elemento+"_"+obs.id_tramite_flujo;
				var str='<input type="text" value"" class="observacionRealizadaVer" data-distribuir="no" id="'+id+'" name="'+id+'" disabled="disabled"/>';
				div.append(str);
				$('#'+id).val("Observacion: "+obs.observacion);
				$('#btnFinalizar').hide();
			}
		}

		if(todo){
			//desactiva las entradas de datos	
			$('section#detalleItem').find("input:not([type=hidden])").attr('disabled', 'disabled');		
			$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
			$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');		
			$('section#detalleItem').find('button:not(.bsig,.bant,.btnVistaPrevia,.verCampoObservar)').hide();
			
		}

		$('input:checkbox[name="boolAcepto"]').prop('disabled',false);
		//desactiva todos los botones
		var pestaniaReferente=$('#P1');
		pestaniaReferente.before($('#P0'));

		construirAnimacion(".pestania");

		activarObservaciones();

		activarActualizacion();
		
		//Desactivo los IA	
		$(".frmAbrirIa").each(function() {
			var form=$(this);		
			form.append("<input type='hidden' id='idFlujo' name='idFlujo' value='"+<?php echo json_encode($id_flujo); ?>+"' />");
			form.append("<input type='hidden' id='nombreOpcion' name='nombreOpcion' value='"+<?php echo json_encode($id_tramite_flujo); ?>+"' />");
			form.append("<input type='hidden' id='paginaRegreso' name='paginaRegreso' value='abrirSubsanarDossier' />");
			form.append("<input type='hidden' id='esDossierBloqueado' name='esDossierBloqueado' value='1' />");
		});
		var areaIA=$('#tablaIngredienteActivos');
		//recupera las observacioes anteriores
		if(subsanadosIa!=null){
			for(var i in subsanadosIa){
				$id="obs_GI_ant_"+subsanadosIa[i].id_tramite_observacion
				areaIA.append('<input type="text" id="'+$id+'" name="'+$id+'" data-distribuir="no" class="observacionRealizadaVer" value="'+subsanadosIa[i].ingrediente_activo+'('+subsanadosIa[i].elemento+") "+subsanadosIa[i].observacion+'" disabled="disabled"/>');
			}
		}

	});


	function activarObservaciones() {
		if(observaciones!=null && observaciones.length>0){
			for(var i in observaciones){
				var obs=observaciones[i];
				var elemento=$('#'+obs.elemento);
				var div = elemento.parent();
				var id="obs_EP_"+obs.elemento+"_"+obs.id_tramite_flujo;
				var str='<input type="text" value"" class="observacionRealizadaVer" data-distribuir="no" id="'+id+'" name="'+id+'" disabled="disabled"/>';
				div.append(str);
				$('#'+id).val("Observacion: "+obs.observacion);
				switch(obs.elemento_tipo){
					case null:
					case '':
						if(elemento.prop("disabled") == true){
							elemento.prop('disabled', false);
							elemento.off('click');					//Para impedir modificar el resto del documento al click
							elemento.off('change');					//Para impedir modificar el resto del documento al cambio
						}
						break;
					case 'referencia':
						var strElementos=obs.ver.trim();
						
						elementos=$(strElementos);
						elementos.show();
						elementos.prop('disabled', false);
						break;
					case 'subgrupo':
						var strElementos=obs.ver.trim();
						var padre=elemento.parent().parent();
						elementos=padre.find(strElementos);
						elementos.show();
						elementos.prop('disabled', false);
						break;
				}

				if(obs.elemento=='tablaIngredienteActivos'){
					//Desactivo los IA	
					$(".frmAbrirIa").each(function() {
						var form=$(this);		
						form.append("<input type='hidden' id='idFlujo' name='idFlujo' value='"+<?php echo json_encode($id_flujo); ?>+"' />");
						form.append("<input type='hidden' id='nombreOpcion' name='nombreOpcion' value='"+<?php echo json_encode($id_tramite_flujo); ?>+"' />");
						form.append("<input type='hidden' id='paginaRegreso' name='paginaRegreso' value='abrirSubsanarDossier' />");
						form.append("<input type='hidden' id='esDossierBloqueado' name='esDossierBloqueado' value='2' />");
					});
				}

			}
		}
	}

	function activarActualizacion(){
		var elemento=$('#frmFinalizarSolicitud12');
		var btn=elemento.find('button#btnFinalizar')[0];
		elemento.append('<button id="btnSubsanar" type="button" class="guardar">Enviar subsanación</button>');

	}

	//*******************************************************
	$("body").off("click", "#btnSubsanar").on("click", "#btnSubsanar", function (event) {
		event.preventDefault();
		//verifica la aceptacion
		if($('#boolAcepto').is(':checked')!=true){
			mostrarMensaje('Debe aceptar las condiciones para continuar','FALLO');
			return;
		}
		borrarMensaje();
		$('input:checkbox[name="boolAcepto"]').prop('disabled',true);

		var param={opcion_llamada:'guardarSubsanacionesSolicitud',
			id_flujo:id_flujo,
			id_tramite_flujo:id_tramite_flujo,
			ruta:$('#verReporte').attr('href'),
			id_documento:id_documento};

		$('section#detalleItem').find("input:enabled").each(function(j) {

			if ($(this).attr("id") !== undefined) {
				item=$(this);
				param["subsan_"+item.attr("id")]=item.val();
			}
		});

		$('section#detalleItem').find("textarea:enabled").each(function(j) {
			if ($(this).attr("id") !== undefined) {
				item=$(this);
				param["subsan_"+item.attr("id")]=item.val();
			}
		});
		$('section#detalleItem').find("select:enabled").each(function(j) {
			if ($(this).attr("id") !== undefined) {
				item=$(this);
				param["subsan_"+item.attr("id")]=item.val();
			}

		});

		mostrarMensaje('Generando documentación...','');
		llamarServidor('dossierPlaguicida','atenderFlujosPlaguicidas',param,resultadoFlujo);

	});

	function resultadoFlujo(items){
		if(items!=null){
			mostrarMensaje('Trámite enviado','EXITO');
			$("#detalleItem").html('<div class="mensajeInicial">Trámite enviado.</div>');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);
		}else{
			mostrarMensaje('Favor revise si todos los puntos fueron subsanados, la transacción no pudo ser compleada','FALLO');
		}
	}

	//*******************************************************

	$('.bsig').click(function(event) {
		event.preventDefault();
		activarObservaciones();
	});

	$('.bant').click(function(event) {
		event.preventDefault();
		activarObservaciones();
	});


</script>


