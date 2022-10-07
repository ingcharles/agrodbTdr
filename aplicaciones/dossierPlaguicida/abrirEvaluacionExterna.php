<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPlaguicida.php';
	
	require_once '../../clases/ControladorCatalogos.php';

	require_once '../ensayoEficacia/clases/Perfil.php';
	
	$numeroPestania = $_POST['numeroPestania'];

	$idUsuario= $_SESSION['usuario'];			//Es el usuario logeado en la solicitud
	$id_solicitud = $_POST['id'];
	
	$identificador=$idUsuario;					//Es el duenio del documento, puede variar si ya hay un protocolo y el usuario es alguien de revision, aprobacion, etc..

	$conexion = new Conexion();
	$co = new ControladorRegistroOperador();
	$ce = new ControladorEnsayoEficacia();
	$cg=new ControladorDossierPlaguicida();
	
	$cc=new ControladorCatalogos();


	$datosGenerales=array();

	$perfiles= $ce->obtenerPerfiles($conexion,$identificador);
	$perfil=new Perfil($perfiles);
	$tipoPerfil='';
	if($perfil->tieneEstePerfil('PFL_DA_SALUD'))
		$tipoPerfil='PFL_DA_SALUD';
	if($perfil->tieneEstePerfil('PFL_DG_MAE'))
		$tipoPerfil='PFL_DG_MAE';


	
	$anexosSolicitud=array();
	$operador = array();
	
	$operadoresFabricantes=array();
	
	$informesFinales=array();
	
	$cultivos=array();
	$plagas=array();
	$contieneParaquat=false;

	$items=$cc->listarLocalizacion($conexion,'PAIS');
	$paises=array();
	while ($fila = pg_fetch_assoc($items)){
		$paises[] = array('codigo'=>$fila['id_localizacion'],'nombre'=>$fila['nombre']);
	}

	if($id_solicitud!=null && $id_solicitud!='_nuevo'){

		$datosGenerales=$cg->obtenerEtiquetaSolicitud($conexion, $id_solicitud);

		$datosDossier=$cg->obtenerSolicitud($conexion, $id_solicitud);

		$identificador=$datosGenerales['identificador'];						//El duenio del documento

		 $anexosSolicitud=$cg->obtenerArchivosAnexos($conexion,$id_solicitud);
	}
	
	//busca los datos del operador
	$res = $co->buscarOperador($conexion, $identificador);
	$operador = pg_fetch_assoc($res);

	
	$clonesRegistrados=$ce->obtenerClonesRegistrados($conexion,$identificador);	//para clones


//****************** ANEXOS **************************************
	$paths=$ce->obtenerRutaAnexos($conexion,'dossierPlaguicida');
	$pathAnexo=$paths['ruta'];

?>

<header>
	<h1>Evaluación externa del dossier: <?php echo $datosDossier['id_expediente'];?></h1>
</header>

<div id="estado"></div>




<div>

	<fieldset>
		<legend>Documentos habilitantes</legend>
		<?php
		if(($datosDossier['ruta_dossier']!=null) && (strlen(trim($datosDossier['ruta_dossier']))>0) && (trim($datosDossier['ruta_dossier'])!='0'))
			echo '<a  href="'.$datosDossier['ruta_dossier'].'"	target="_blank">Dossier</a><br/>';
		if(($datosDossier['ruta_solicitud']!=null) && (strlen(trim($datosDossier['ruta_solicitud']))>0)  && (trim($datosDossier['ruta_solicitud'])!='0'))
			echo '<a  href="'.$datosDossier['ruta_solicitud'].'"	target="_blank">Solicitud</a><br/>';
		foreach($anexosSolicitud as $item){
			if(($item['path']!=null) && (strlen(trim($item['path']))>0))
				echo '<a  href="'.$item['path'].'"	target="_blank">'.$item['nombre'].' : '.$item['referencia'].'</a><br/>';
		}

		?>
	</fieldset>

   <form id='frmSolicitudEtiqueta' data-rutaAplicacion='dossierPlaguicida' data-opcion=''>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
		<input type="hidden" id="tipoPerfil" name="tipoPerfil" value="<?php echo $tipoPerfil;?>" />

		<input type="hidden" id="a_referencia" name="a_referencia" value="" />
		<input type="hidden" id="a_rutaArchivo" name="a_rutaArchivo" value="" />
		<input type="hidden" id="a_tipoArchivo" name="a_tipoArchivo" value="" />

		<fieldset>
			<legend>Resultado de la evaluación</legend>
						
			<div class="justificado">
				<label for="salud">Comentario:</label>
				<textarea name="comentario" id="comentario" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
				</textarea>
			</div>
		
			<div data-linea="2">
				<label>
					Informe de evaluación
				</label>

			</div>
			
			
				<div data-linea="3">
					<input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
					<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>
					<input type="file" class="archivo" accept="application/msword | application/pdf | image/*"  />
					<div class="estadoCarga">
						En espera de archivo... (Tamaño máximo <?php echo '2'.'M'; ?>B)
					</div>

               <div>
                  <hr />
                  <label>
                     <input type="radio" name="opcionAprobar" value="C_D_O" />Observar
                  </label>
                  <br />

                  <label>
                     <input type="radio" name="opcionAprobar" value="" />Aprobar
                  </label>
                  
               </div>

					<button type="button" class="subirArchivo guardar" data-rutacarga="<?php echo $pathAnexo;?>" >Enviar trámite</button>
				</div>
		

		</fieldset>

      
   </form>

  
</div>



<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>


<script type="text/javascript">

	
	var solicitud=<?php echo json_encode($datosGenerales); ?>;


	var datosDossier=<?php echo json_encode($datosDossier); ?>;
	var tipoPerfil=<?php echo json_encode($tipoPerfil); ?>;


	var codigoFormulacion='';
	var protocolo={};

	//**************************  VARIABLES GENERALES *****************************


	//****************** CARGA *************************************
	$("document").ready(function(){

		
		distribuirLineas();

	});

	$('#frmSolicitudEtiqueta').submit(function(event){
		event.preventDefault();
		$("#estado").html("");

		var error = false;


		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		var form=$(this);
		form.attr('data-opcion', 'guardarPasosEtiqueta');
		form.attr('data-destino', 'detalleItem');
		form.attr('data-accionEnExito', '');
		
		ejecutarJson(form);

	});


	//***************************** VISTA PREVIA ETIQUETA ***************************************
	$('button.btnVistaPreviaEtiqueta').click(function (event) {

		event.preventDefault();

		var form=$(this).parent();
		form.append("<input type='hidden' id='id_solicitud' name='id_solicitud' value='"+solicitud.id_solicitud+"' />"); // añade el nivel del formulario

		form.attr('data-opcion', 'crearPuntosEtiqueta');

		mostrarMensaje("Generando archivo ... ",'FALLO');
		$('#verReporteEtiqueta').hide();
		ejecutarJson(form,new exitoVistaPreviaEtiqueta());

	});


	function exitoVistaPreviaEtiqueta(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verReporteEtiqueta').show();
			$('#verReporteEtiqueta').attr('href',msg.datos);
		};
	}


	//********************* Archivos *****************************
	$('button.subirArchivo').click(function (event) {
		event.preventDefault();

		if($('input[name="opcionAprobar"]:checked').val()===undefined){
			mostrarMensaje("Favor elija una de las opciones de aceptación","FALLO");
			return;
		}

			var boton = $(this);
			
		
			var nombre_archivo ='OE_'+ solicitud.identificador+"_"+tipoPerfil+solicitud.id_solicitud;
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
					 , new carga(estado, archivo, boton,rutaArchivo)
				 
				);
			} else {
				estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
				archivo.val("");
			}
		
	});
	function carga(estado, archivo, boton,rutaArchivo) {
		this.esperar = function (msg) {
			estado.addClass("rojo");
			estado.html("Cargando el archivo...");
			archivo.addClass("amarillo");
		};

		this.exito = function (msg) {
			estado.removeClass("rojo");
			estado.html("El archivo ha sido cargado.");
			archivo.removeClass("amarillo");
			archivo.addClass("verde");
			boton.attr("disabled", "disabled");

			guardarArchivo(boton);

		};

		this.error = function (msg) {
			estado.html(msg);
			archivo.removeClass("amarillo");

			archivo.val("");
		};
	}

	function guardarArchivo(boton){
		var rutaArchivo = boton.parent().find(".rutaArchivo");

		$('#a_rutaArchivo').val(rutaArchivo.val());
		
		var form=boton.parent().parent().parent();
		form.append("<input type='hidden' id='opcion_llamada' name='opcion_llamada' value='guardarOrganismoExterno' />");
		form.append("<input type='hidden' id='condicion' name='condicion' value='"+$('input:radio[name=opcionAprobar]:checked').val()+"' />");
		form.append("<input type='hidden' id='observacion' name='observacion' value='"+$('#comentario').val()+"' />");
		form.append("<input type='hidden' id='tipoPerfil' name='tipoPerfil' value='"+tipoPerfil+"' />");
		form.append("<input type='hidden' id='rutaAnexo' name='rutaAnexo' value='"+rutaArchivo.val()+"' />");



		form.attr('data-opcion', 'atenderFlujosPlaguicidas');

		mostrarMensaje("Generando archivo ... ",'FALLO');

		ejecutarJson(form,new exitoFinalizarEtiqueta());

	}

	function exitoFinalizarEtiqueta(){
		this.ejecutar=function (msg){
			mostrarMensaje('Trámite enviado','EXITO');
			$("#detalleItem").html('<div class="mensajeInicial">Trámite enviado.</div>');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);

		};
	}
</script>

