<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	

	
	$idUsuario= $_SESSION['usuario'];
	$id_documento = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];
	$id_provincia = $_POST['opcion'];
	$id_tramite_flujo = $_POST['nombreOpcion'];
	$identificador=$idUsuario;

	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	$cr = new ControladorRegistroOperador();
	

	$protocolo=array();
	$informe=array();

	//Busca el protocolo
	if($id_documento!=null){
		$protocolo=$ce->obtenerProtocoloDesdeInforme ($conexion, $id_documento);

		$idProtocolo=$protocolo['id_protocolo'];
		$identificador=$protocolo['identificador'];
		$operador=pg_fetch_assoc($cr->listarOperadoresEmpresa($conexion,$identificador),0);

		$motivos=$ce->listarElementosCatalogo($conexion,'P1C2');
		//verifica si ya reporto la instalación
		$informe=$ce->obtenerInformeFinalEnsayo($conexion,$id_documento);
 
        $notificacionesAnexos=$ce->listarArchivosAnexos($conexion,$idProtocolo,'IF'); 

	}
	
	$estaNotificado='S';
	$tramite=$ce->obtenerTramiteDesdeFlujoTramiteEE($conexion,$id_tramite_flujo);
	if($tramite['status']!=null)
		$estaNotificado=$tramite['status'];
	$tramite_flujo=$ce->obtenerTramiteEE($conexion,$id_tramite_flujo);
	$zonas=$ce->obtenerProtocoloZonas($conexion,$idProtocolo);
	$zona=array();
	foreach($zonas as $key=>$value){
		if($value['provincia'] === $id_provincia){
			$zona=$value;
			break;
		}
	}

	//******************************** ANEXOS *************************************
	$paths=$ce->obtenerRutaAnexos($conexion,'ensayoEficacia');
	$pathAnexo=$paths['ruta'];		//Ruta para los documentos adjuntos

	$fechaActual=new DateTime();
	$fechaTiempo=new DateTime($tramite['fecha_final']);
	$sobreTiempo=false;
	if($fechaActual>$fechaTiempo)
		$sobreTiempo=true;

?>

<header>
	<h1>Ejecución del ensayo de eficacia</h1>
</header>

<div id="estado"></div>


<div >
	<form id="frmProceso" data-rutaAplicacion="ensayoEficacia" data-opcion="atenderFlujosInformes" data-accionEnExito='ACTUALIZAR'>
		<input type="hidden" id="id_documento" name="id_documento" value="<?php echo $id_documento;?>" />
		<input type="hidden" id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>" />
		<input type="hidden" id="id_tramite_flujo" name="id_tramite_flujo" value="<?php echo $id_tramite_flujo;?>" />
		<input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />
		<input type="hidden" id="opcion_llamada" name="opcion_llamada" value="reportarInstalacionEnsayo" />


		<fieldset>
			<legend>Inspección del protocolo</legend>
			<div data-linea="1">
				<label>Empresa :</label>
				<label>
					<?php echo $operador['nombre_operador'];?>
				</label>
			</div>
			<div data-linea="2">
				<label>Expediente :</label>
				<label>
					<?php echo $protocolo['id_expediente'];?>
				</label>
			</div>
			<div data-linea="3">
				<label>Motivo :</label>
				<label>
					<?php
					foreach($motivos as $key=>$value){
						if($value['codigo']==$protocolo['motivo']){
							echo $value['nombre'];
						}
					}
					?>
				</label>
			</div>
			<div data-linea="4">
				<label>Producto :</label>
				<label>
					<?php echo $protocolo['plaguicida_nombre'];?>
				</label>
			</div>
         <div data-linea="5">
            <label>Provincia :</label>
            <label><?php echo $zona['provincia_nombre'];?>
            </label>
         </div>
         <div data-linea="6">
            <label>Cantón :</label>
            <label><?php echo $zona['canton_nombre'];?>
            </label>
         </div>
         <div data-linea="7">
            <label>Parroquia :</label>
            <label><?php echo $zona['parroquia_nombre'];?>
            </label>
         </div>

			<div class="justificado ocultarSobretiempo">
				
					<a href="<?php echo $protocolo['ruta'];?>" target="_blank">Ver protocolo</a>
				
				<br />
				<button id="reportarInstalacion" type="button" class="guardar" >Reportar instalación</button>

			</div>
			<div class="justificado">
				<label id="lblNotificacion"></label><br/>
			</div>

		</fieldset>
		
		<fieldset class="verOrganismos">
			<legend>Notificaciones durante el ensayo</legend>
			<div class="justificado">
				<label for="referencia">Comentarios:</label>
				<input type="text" class="referencia" name="referencia" maxlength="64" value="" />
				<input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
				<input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
				<div class="estadoCarga">
					En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)
				</div>

				<button id="btnNotificar" type="button" class="subirArchivo guardar" data-rutacarga="<?php echo $pathAnexo;?>">Enviar notificación</button>
			</div>
			<table style="width:100%">
				<thead>
					<tr>
						<th width="90%">Comentarios</th>
                  <th width="10%"></th>
					</tr>
				</thead>
				<tbody id="tblNotificaciones">

				</tbody>
			</table>
			
		</fieldset>
		
		<fieldset class="verOrganismos">
			<legend>Emisión de informe de supervición</legend>
			<div data-linea="1">
				<?php
					if($informe!=null && $informe['ruta_informe_inspeccion']!=null){
						echo '<a href="'.$informe['ruta_informe_inspeccion'].'" target="_blank">Informe de inspección</a>';
						echo '<br />';
					}

				?>
				
					
			</div>
			<div class="justificado">
				<label for="referencia">Comentarios:</label>
				<input type="text" class="referencia" name="referencia" maxlength="64" value="" />
				<input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
				<input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
				<div class="estadoCarga">
					En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)
				</div>

				<button id="btnInforme" type="button" class="subirArchivo guardar" data-rutacarga="<?php echo $pathAnexo;?>">Emitir informe</button>

			</div>
		</fieldset>

		<fieldset id="verObservacionLimite">
			<legend>Información</legend>
			<div data-linea="1">
            <label>Fecha de aprobación :</label>
            <label><?php echo $tramite['fecha_inicio'];?></label>
         </div>
			<div data-linea="2">
            <label>Fecha de caducidad :</label>
            <label><?php echo $tramite['fecha_final'];?></label>
         </div>
			<div data-linea="4">
            <label>Resolución :</label>
				<textarea id="retraso" name="retraso" data-distribuir='no' maxlength="512" disabled><?php
					$texto="Estimado Usuario. Su trámite tenía 2 años de validez. Necesita realizar un nuevo ensayo para continuar con el trámite";
					echo $texto; ?></textarea>
            
         </div>
			
		</fieldset>

	</form>
</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>

<script type="text/javascript">
	var idUsuario=<?php echo json_encode($idUsuario); ?>;
	var protocolo=<?php echo json_encode($protocolo); ?>;
	var informe=<?php echo json_encode($informe); ?>;
	var estaNotificado=<?php echo json_encode($estaNotificado); ?>;
	var notificacionesAnexos=<?php echo json_encode($notificacionesAnexos); ?>;

	var sobreTiempo=<?php echo json_encode($sobreTiempo); ?>;


	$("document").ready(function(){

		distribuirLineas();

		if(idUsuario==protocolo.identificador){
			$('section#detalleItem').find('input:enabled').attr('disabled', 'disabled');
			$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
			$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');
			$('section#detalleItem').find('button').hide();
		}
		//verifico si ya notificó la instalación
		switch(estaNotificado){
			case 'S':	//caso de solicitud inicial
				if(idUsuario==protocolo.identificador)
					$('#reportarInstalacion').hide();
				$('#lblNotificacion').html('La instalación del ensayo aún no ha sido reportada');
				break;
			case 'E':	//Se ha iniciado la ejecución
				$('#reportarInstalacion').hide();
				$('#lblNotificacion').html('Instalación reportada el : '+fechaFormato(informe.fecha_instalacion));
				break;
			default:		//I=Informe de ejecucion ha sido cargado
				$('#reportarInstalacion').hide();
				$('#lblNotificacion').html('Instalación reportada el : '+fechaFormato(informe.fecha_instalacion));				
				break;
		}

		try{
			if(idUsuario==protocolo.identificador && estaNotificado!=null && (estaNotificado=='E' || estaNotificado=='I' || estaNotificado=='N')){
				
				var el=$('#lblNotificacion').parent();
				el.append('<button id="btnCrearInforme" type="button" class="guardar ocultarSobretiempo">Propuesa de Informe Final</button>');
			}

			if(idUsuario==protocolo.identificador){
				$('.verOrganismos').hide();
			}
		}catch(e){}

		try{
			verArchivosAnexos(notificacionesAnexos);
		}catch(e){}

		if(sobreTiempo){
			$('#verObservacionLimite').show();		
			$('.ocultarSobretiempo').hide();
			$('.verOrganismos').hide();			
		}
		else{
			$('#verObservacionLimite').hide();
			
		}

	});

	//opcion solo para el operador
	$("body").off("click", "#btnCrearInforme").on("click", "#btnCrearInforme", function (event) {
		event.preventDefault();
		$('#id_documento').removeAttr('disabled');
		$('#id_protocolo').removeAttr('disabled');
		$('#id_tramite_flujo').removeAttr('disabled');
		$('#id_flujo').removeAttr('disabled');
		$('#opcion_llamada').removeAttr('disabled');
		$('#id_documento').removeAttr('disabled');
		$('#id_documento').removeAttr('disabled');

		var form=$('#frmProceso');
		form.attr('data-opcion',"abrirSolicitudInforme");
		form.attr('data-destino',"detalleItem");
		
		abrir(form, event, true);

	});



	//Opcion para el Organismo de inspeccion o para los supervisores
	$('button.subirArchivo').click(function (event) {
		var boton = $(this);
		var referencia = boton.parent().find(".referencia");
		var str=referencia.val().trim();
		str=str.replace(/[^a-zA-Z0-9.]+/g,'');
		str=str.substring(0,32);	//maximo 32 caracteres de la referencia
		var tipoReporte='_IF_NOTA_';
		if(boton.attr('id')=='btnInforme')
			tipoReporte='_IF_INF_';

			nombre_archivo = "<?php echo $idUsuario . tipoReporte.$id_documento.'_'; ?>";
		nombre_archivo=nombre_archivo+str;

        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new cargar(estado, archivo, boton)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
	});

	function cargar(estado, archivo, boton) {
		this.esperar = function (msg) {
			estado.html("Cargando el archivo...");
			archivo.addClass("amarillo");
		};

		this.exito = function (msg) {
			estado.html("El archivo ha sido cargado.");
			archivo.removeClass("amarillo");
			archivo.addClass("verde");
			boton.attr("disabled", "disabled");

			switch(boton.attr('id')){
				case 'btnNotificar':	//Notificacion
					$('#opcion_llamada').val('notificarInformeEnsayo');
					var form=$('#frmProceso');
					form.attr('data-opcion',"atenderFlujosInformes");
					form.attr('data-destino',"detalleItem");
					var referencia = boton.parent().find(".referencia");
					var path = boton.parent().find(".rutaArchivo");
					form.append("<input type='hidden' id='referencia' name='referencia' value='"+referencia.val()+"' />"); // Comentario de la notificacion
					form.append("<input type='hidden' id='path' name='path' value='"+path.val()+"' />"); // ruta del pdf de la notificacion
					ejecutarJson(form,new notificarInforme());
					break;
				case 'btnInforme':	//finaliza
					$('#opcion_llamada').val('emitirInformeEnsayo');
					var form=$('#frmProceso');
					form.attr('data-opcion',"atenderFlujosInformes");
					var referencia = boton.parent().find(".referencia");
					var path = boton.parent().find(".rutaArchivo");
					form.append("<input type='hidden' id='referencia' name='referencia' value='"+referencia.val()+"' />"); // Comentario de la notificacion
					form.append("<input type='hidden' id='path' name='path' value='"+path.val()+"' />"); // ruta del pdf de la notificacion
					ejecutarJson(form,new emitirInforme());
					break;
			}

		};

		this.error = function (msg) {
			estado.html(msg);
			archivo.removeClass("amarillo");
			archivo.addClass("rojo");
		};
	}

	function notificarInforme() {
		this.ejecutar = function(msg) {
			if(msg!=null){
				verArchivosAnexos(msg.datos);
			}
			$('#opcion_llamada').val('enviarCorreo');
			var form=$("#frmProceso");
			form.append("<input type='hidden' id='asuntoCorreo' name='asuntoCorreo' value='Notificación de Ejecución de Ensayo' />");
			ejecutarJson(form);

			mostrarMensaje("Notificación enviada", "EXITO");

		};
	}

	function emitirInforme() {
		this.ejecutar = function(msg) {
			

			mostrarMensaje("Trámite y notificaciones enviados", "EXITO");
			$("#detalleItem").html('<div class="mensajeInicial">Trámite y notificaciones enviados.</div>');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);
		};
	}

	function verArchivosAnexos(items){
		$('#tblNotificaciones > tr').remove();
		var strCodigos='';
		for(var i in items){
			var item=items[i];
			var fila='<tr>'+
			'<td><a href="'+ item.path+'" target="_blank">'+item.referencia+'</a></td>'+
			'<td>' +
				'<form  class="borrar" data-rutaAplicacion="ensayoEficacia" data-opcion="eliminarArchivoAnexo"  >' +
					'<input type="hidden" id="archivo" name="archivo" value="' + item.path + '" >' +
					'<input type="hidden" id="id_documento" name="id_documento" value="' + item.id_protocolo + '" >' +
					'<input type="hidden" id="id_protocolo_anexos" name="id_protocolo_anexos" value="' + item.id_protocolo_anexos + '" >' +
					'<input type="hidden" id="tipo" name="tipo" value="IF" >' +
					'<button type="button" class="icono btnBorraFilaArchivoAnexo derecha"></button>' +
				'</form>' +
			'</td>'+
			'</tr>';

			$('#tblNotificaciones').append(fila);

		}

	}

	$("#tblNotificaciones").off("click",".btnBorraFilaArchivoAnexo").on("click",".btnBorraFilaArchivoAnexo",function(event){
		event.preventDefault();
		var form=$(this).parent();
		
		var param={opcion_llamada:'borrarFilaArchivoAnexo',id_protocolo:form.find("#id_documento").val(),id_protocolo_anexos:form.find("#id_protocolo_anexos").val(),tipo:form.find("#tipo").val()};
		llamarServidor('ensayoEficacia','atenderLlamadaServidor',param,verArchivosAnexos);
	});


	//*********************** SUBMIT*********

	$("#frmProceso").submit(function(event){

		event.preventDefault();
		var form=$(this);
		form.attr('data-opcion',"atenderFlujosInformes");

		var error = false;
		$('#opcion_llamada').val('');
		if (!error){
			ejecutarJson(form);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}


	});


	//************************ FIN SUBMIT ***



	//********************************* REPORTAR INSTALACION **************************************
	$('#reportarInstalacion').click(function (event) {
		event.preventDefault();
		borrarMensaje();
		$('#opcion_llamada').val('reportarInstalacionEnsayo');
		var form=$('#frmProceso');
		form.attr('data-opcion',"atenderFlujosInformes");
		ejecutarJson(form,new instalacionReporada());

	});

	function instalacionReporada() {
		this.ejecutar = function(msg) {
			$('#opcion_llamada').val('enviarCorreo');
			var form=$("#frmProceso");
			form.append("<input type='hidden' id='asuntoCorreo' name='asuntoCorreo' value='Notificación de Instalación de Ensayo' />");
			ejecutarJson(form);

			mostrarMensaje("Notificación enviada", "EXITO");
			if(msg.mensaje==null){
				$("#detalleItem").html('<div class="mensajeInicial">Trámite y notificaciones enviados.</div>');
				abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
				abrir($("input:hidden"), null, false);
			}
			else{
				$('#reportarInstalacion').hide();
				$('#lblNotificacion').html('Instalación reportada el: '+msg.mensaje.fecha_instalacion);

			}
		};
	}



</script>
