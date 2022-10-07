<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPecuario.php';


	$idUsuario= $_SESSION['usuario'];
	$id_solicitud = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];
	$id_fase = $_POST['opcion'];
	$id_tramite_flujo = $_POST['nombreOpcion'];
	$identificador=$idUsuario;

	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	$cp=new ControladorDossierPecuario();

	$datosGenerales=array();
	
	if($id_solicitud!=null){
		$datosGenerales=$cp->obtenerSolicitud($conexion, $id_solicitud);
		$identificador=$datosGenerales['identificador'];
		$motivos=$ce->listarElementosCatalogo($conexion,'P4C0');
		$anexos=$cp->listarArchivosAnexos($conexion,$id_solicitud);
		foreach($anexos as $key=>$value){
			$anexoVector[$value['tipo']]=$value;
		}
		$anexoCertificado=$anexoVector['AP_CAP']['path'];
		$anexoMetodo=$anexoVector['AP_MAAA']['path'];

	}
	$tramiteFlujo=$ce->obtenerFlujoDeTramiteEE($conexion,$id_tramite_flujo);
	$id_tramite=$tramiteFlujo['id_tramite'];

	$perfiles= $ce->obtenerPerfiles($conexion,$idUsuario);

	//****************** ANEXOS **************************************
	$paths=$ce->obtenerRutaAnexos($conexion,'dossierPecuario');
	$pathAnexo=$paths['ruta'];

	$fechaActual=new DateTime();
	$fechaTiempo=new DateTime($tramiteFlujo['fecha_fin']);
	$sobreTiempo=false;
	if($fechaActual>$fechaTiempo)
		$sobreTiempo=true;

?>

<header>
	<h1>
		Evaluación del método analítico
	</h1>
</header>

<div id="estado"></div>


<div id="asignar">
	<form id="frmAsignarTecnico" data-rutaAplicacion="dossierPecuario" data-opcion="atenderFlujosPecuarios" data-accionEnExito = 'ACTUALIZAR'>
		<input type="hidden"  id="id_documento" name="id_documento" value="<?php echo $id_solicitud;?>"/>
		<input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />
      <input type="hidden" id="id_fase" name="id_fase" value="<?php echo $id_fase;?>" />
		<input type="hidden" id="id_tramite_flujo" name="id_tramite_flujo" value="<?php echo $id_tramite_flujo;?>" />
		<input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $id_tramite;?>" />
      <input type="hidden" id="opcion_llamada" name="opcion_llamada" value="evaluarMetodo" />
      	
		
		<fieldset>
			<legend>Datos del producto</legend>
			<div data-linea="1">
				<label>Empresa :</label>
				<input value="<?php echo $datosGenerales['razon_social'];?>" disabled="disabled" />
			</div>
			<div data-linea="2">
				<label>Expediente :</label>
				<input value="<?php echo $datosGenerales['id_expediente'];?>" disabled="disabled" />

			</div>

			<div data-linea="3">
				<label>Motivo :</label>
				<input value="<?php

								  foreach($motivos as $key=>$value){
									  if($value['codigo']==$datosGenerales['tipo_solicitud']){
										  echo $value['nombre'];
									  }
								  }
                              ?>"
					disabled="disabled" />


			</div>
			<div data-linea="4">
				<label>Producto :</label>
				<input value="<?php echo $datosGenerales['nombre'];?>" disabled="disabled" />

			</div>
				
			<div data-linea="8">
				<label>Datos del certificado:</label>
				<a href='<?php echo $anexoCertificado; ?>' target="_blank" >Certificado de análisis del producto</a>
			</div>
         <div data-linea="9">
            <label>Datos del método:</label>
				<a href='<?php echo $anexoMetodo; ?>' target="_blank" >Método analítico de acuerdo al anexo 4 o 5 (Resolución 003)</a>
         </div>
			<div class="justificado observacionAnterior">
				<label for="observacionAnterior">Observacion presedente :</label>
				<textarea id="observacionAnterior" name="observacionAnterior" disabled="disabled">
					<?php
					$obs='';
					if($tramiteFlujo['pendiente']=='O')
						$obs='observado';
					else if($tramiteFlujo['pendiente']=='I')
						$obs='observado internamente';
					else if($tramiteFlujo['pendiente']=='A')
						$obs='aprobado';
					echo 'El trámite ha sido '.$obs.' : '.$tramiteFlujo['observacion'];
                    ?>
				</textarea>
			</div>
			
			
		</fieldset>
		
		<fieldset class="verInformeTecnico">
            <legend>Informe técnico de laboratorio</legend>
             <input type="hidden" id="tipoArchivo" name="tipoArchivo" value="AP_ITL" />
             <div class=" justificado">
                <label for="referencia" class="opcional">Referencia para el documento:</label>
                <input value="<?php echo $anexoVector['AP_ITL']['referencia']; ?>" type="text" id="referencia" name="referencia" placeholder="Incluir la referencia en el documento" class="referencia" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
             </div>
             <div data-linea="2">
                <label>Archivo adjunto</label>      
                <?php
                  $anexo=$anexoVector['AP_ITL']['path'];
                  if($anexo=='0' || $anexo==''){
                  echo '<span class="alerta" id="noHayArchivo">No hay ningún archivo adjunto</span>';
                  echo '<a href="" target="_blank" class="archivo_cargado" id="archivo_cargado" style="display: none;">Archivo Cargado</a>';
                  }
                  else{
                  echo '<span class="alerta" id="noHayArchivo" style="display: none;">No hay ningún archivo adjunto</span>';
                  echo '<a href='.$anexo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';
                  }
                  ?>
             </div>
             <div data-linea="3">
                <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
                <input type="hidden" class="maxCapacidad" value="<?php echo intval($anexosCapacidad['AP_ITL']['nombre2'])*1024*1024; ?>" />
                <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
                <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $anexosCapacidad['AP_ITL']['nombre2'].'M'; ?>B)</div>
                <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
             </div>
             <div data-linea="5">
                <label id="archivoAP_ITL"></label>
             </div>
          </fieldset>
		
		<fieldset>
			<legend>Evaluación</legend>
			<div class="justificado">
				<label for="observacion">Observaciones :</label>
				<textarea id="observacion" name="observacion"></textarea>
			</div>
			<div data-linea="2" id="boolAceptoMetodo">
				<?php
				$noEncontroPerfil=true;
				foreach($perfiles as $miPerfil){
					if($miPerfil['codificacion_perfil']=='PFL_DP_DDIACIA'){
						echo '<input type="radio" id="boolAceptoSI" name="boolAcepto" value="" />Concluir evaluación';
						$noEncontroPerfil=false;
						break;
					}
				}
				if($noEncontroPerfil){
					echo '<input type="radio" id="boolAceptoSI" name="boolAcepto" value="" />Aprobar método';
				}
				?>
				

			</div>	
			<div data-linea="3" class="soloTecnico">
				<input type="radio" id="boolAceptoNO" name="boolAcepto" value="C_M_O" />Observar método
				
			</div>			
				
			<div data-linea="4" class="soloDirector">
				<input type="radio" id="boolAceptoO" name="boolAcepto" value="C_I_O" />Observar evaluación del técnico
				
			</div>
		</fieldset>
		
		<fieldset id="verObservacionLimite">
			<legend>Datos necesarios</legend>
			<div data-linea="1">
            <label>Justificación del retraso de su respuesta :</label>
				<textarea id="retraso" name="retraso" data-distribuir='no' maxlength="512"></textarea>
            
         </div>

		</fieldset>

		<button type="submit" class="guardar">Enviar trámite</button>
		
	</form>
	
	<form id="frmAnexos" data-rutaAplicacion="dossierPecuario" data-opcion="guardarArchivoAnexo" >
		
    </form>
    
</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>

<script type="text/javascript">
	var solicitud=<?php echo json_encode($datosGenerales); ?>;
	var id_fase=<?php echo json_encode($id_fase); ?>;
	var tramiteFlujo=<?php echo json_encode($tramiteFlujo); ?>;
	var perfiles=<?php echo json_encode($perfiles); ?>;
	var sobreTiempo=<?php echo json_encode($sobreTiempo); ?>;

	$("document").ready(function(){

		distribuirLineas();

		if(tramiteFlujo.pendiente=="S"){
			
			$('.observacionAnterior').hide();
		}
		else{
			$('.observacionAnterior').show();
			
		}
		$('.soloDirector').hide();
		if(perfiles!=null){
			for(var i in perfiles){
				if(perfiles[i].codificacion_perfil=='PFL_DP_DDIACIA'){
					$('.soloDirector').show();
					$('.soloTecnico').hide();
					
				}
				
			}
			
		}

		if(sobreTiempo)
			$('#verObservacionLimite').show();
		else
			$('#verObservacionLimite').hide();

	});


	$("#frmAsignarTecnico").submit(function(event){
		event.preventDefault();
		
		var error = false;
		if($('input[name="boolAcepto"]:checked').val()===undefined)
			error = true;

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}

		var esDirector=false;
		if(perfiles!=null){
			for(var i in perfiles){
				if(perfiles[i].codificacion_perfil=='PFL_DP_DDIACIA'){
					esDirector=true;
					break;
				}
				
			}
			
		}

		
		if((!esDirector) &&( $('#archivo_cargado').attr('href')==""))
		{
			mostrarMensaje("Favor adjunte su informe técnico","FALLO");
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

		if (!error){
			
			ejecutarJson($(this),new exitoAsignacion());
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}

	});

	function exitoAsignacion(){
		this.ejecutar=function(){
			mostrarMensaje("Evaluación del tramite ha sido enviada","EXITO");
			$('#asignar').html('Evaluación del tramite ha sido enviada');
		};
	}

	//************** ARCHIVOS ************************
	
	$(".referencia").keyup(function(){
		var el=$(this);
		var fld=el.parent().parent();
		if(el.val().trim()!=""){
			fld.find(".archivo").removeAttr("disabled");
			fld.find("button.subirArchivo").removeAttr("disabled");
		}
		else{
			fld.find(".archivo").attr("disabled", "disabled");
			fld.find("button.subirArchivo").attr("disabled", "disabled");
		}
	});
	
	$('button.subirArchivo').click(function (event) {
		event.preventDefault();

		var boton = $(this);
		var str=boton.parent().parent().find("#referencia").val();

		error=false;
		if(str==null || str=='')
			error=true;
		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();


		$('#a_referencia').val(str);
		str=str.replace(/[^a-zA-Z0-9.]+/g,'');
		var tipoArchivo=boton.parent().parent().find("#tipoArchivo").val();
		tipoArchivo=tipoArchivo==null?"":tipoArchivo;
		$('#a_tipoArchivo').val(tipoArchivo);
		var nombre_archivo = solicitud.identificador+"_DP_"+solicitud.id_solicitud+"_"+tipoArchivo+"_"+str;
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        var maximaCapacidad = boton.parent().find(".maxCapacidad").val();

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new cargaDossier(estado, archivo, boton,rutaArchivo)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
	});
	function cargaDossier(estado, archivo, boton,rutaArchivo) {
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

		var form = $('#frmAnexos'); 

		form.attr('data-rutaAplicacion', 'dossierPecuario');
		form.attr('data-opcion', 'guardarArchivoAnexo');
		form.append('<input type="hidden" id="id_solicitud" name="id_solicitud" value="'+solicitud.id_solicitud+'">');
		form.append('<input type="hidden" id="fase" name="fase" value="'+solicitud.estado+'">');

		form.append('<input type="hidden" id="a_referencia" name="a_referencia" value="'+boton.parent().parent().find("#referencia").val()+'">');
		form.append('<input type="hidden" id="a_rutaArchivo" name="a_rutaArchivo" value="'+boton.parent().find(".rutaArchivo").val()+'">');
		form.append('<input type="hidden" id="a_tipoArchivo" name="a_tipoArchivo" value="'+boton.parent().parent().find("#tipoArchivo").val()+'">');

		ejecutarJson(form);
		var noHayArchivo = boton.parent().parent().find("#noHayArchivo");
		var archivo_cargado = boton.parent().parent().find("#archivo_cargado");
		noHayArchivo.hide();
		archivo_cargado.attr("href",rutaArchivo.val());
		archivo_cargado.show();

	}

</script>

