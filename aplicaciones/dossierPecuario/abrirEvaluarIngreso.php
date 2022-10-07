<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPecuario.php';

	require_once '../ensayoEficacia/clases/Perfil.php';

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
	

	//Busca el protocolo
	if($id_solicitud!=null){
		$datosGenerales=$cp->obtenerSolicitud($conexion, $id_solicitud);
		$identificador=$datosGenerales['identificador'];
		$motivos=$ce->listarElementosCatalogo($conexion,'P4C0');
		$anexos=$cp->listarArchivosAnexos($conexion,$id_solicitud);
		foreach($anexos as $key=>$value){
			$anexoVector[$value['tipo']]=$value;
		}
		$anexo=$anexoVector['AP_CAP']['path'];

	}
	$tramiteFlujo=$ce->obtenerFlujoDeTramiteEE($conexion,$id_tramite_flujo);
	$id_tramite=$tramiteFlujo['id_tramite'];

	$perfiles= $ce->obtenerPerfiles($conexion,$idUsuario);
	$perfil=new Perfil($perfiles);

	if($perfil->tieneEstePerfil('PFL_DP_TSA')){
		$textoOpcionSI='Acepto ingreso del producto al país';
		$textoOpcionNO='No acepto ingreso. Trámite observado';
	}
	else if($perfil->tieneEstePerfil('PFL_DP_CGSA'))
	{
		$textoOpcionSI='Enviar trámite al operador';
		$textoOpcionNO='Retornar evaluación al Director';
	}
	else if($perfil->tieneEstePerfil('PFL_DP_DCZ')){
		$textoOpcionSI='Enviar trámite al Coordinador';
		$textoOpcionNO='Retornar evaluación al técnico';
	}

	$fechaActual=new DateTime();
	$fechaTiempo=new DateTime($tramiteFlujo['fecha_fin']);
	$sobreTiempo=false;
	if($fechaActual>$fechaTiempo)
		$sobreTiempo=true;

	
?>

<header>
	<h1>Evaluación de ingreso del producto al país</h1>
</header>

<div id="estado"></div>


<div id="asignar">
	<form id="frmAsignarTecnico" data-rutaAplicacion="dossierPecuario" data-opcion="atenderFlujosPecuarios" data-accionEnExito = 'ACTUALIZAR'>
		<input type="hidden"  id="id_documento" name="id_documento" value="<?php echo $id_solicitud;?>"/>
		<input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />
      <input type="hidden" id="id_fase" name="id_fase" value="<?php echo $id_fase;?>" />
		<input type="hidden" id="id_tramite_flujo" name="id_tramite_flujo" value="<?php echo $id_tramite_flujo;?>" />
		<input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $id_tramite;?>" />
      <input type="hidden" id="opcion_llamada" name="opcion_llamada" value="evaluarIngreso" />

		<input type="hidden" id="cepa" name="cepa" value="<?php echo $datosGenerales['nueva_cepa'];?>" />
		<input type="hidden" id="anexo" name="anexo" value="<?php echo $anexo;?>" />
				
      <fieldset>
         <legend>Evaluando ingreso al país de solicitud : <?php echo $id_solicitud;?></legend>
			<div data-linea="1">
				<label>Empresa :</label>
				<input value="<?php echo $datosGenerales['razon_social'];?>" disabled="disabled" />
			</div>

			<div data-linea="2">
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
			<div data-linea="3">
				<label>Producto :</label>
				<input value="<?php echo $datosGenerales['nombre'];?>" disabled="disabled" />

			</div>
			<div data-linea="7">
				<label>CEPA a evaluar :</label>
            <input value="<?php echo $datosGenerales['nueva_cepa'];?>" disabled="disabled" />
			</div>

         

         <div data-linea="9">
            <label>Documento habilitante :</label>
            <a href='<?php echo $anexo; ?>' target="_blank" class="archivo_cargado" id="archivo_cargado">Certificado de análisis del producto</a>
         </div>
         <div class="justificado observacionAnterior">
            <label for="observacionAnterior">Mensaje de : <?php echo $tramiteFlujo['nombre'].' '.$tramiteFlujo['apellido']; ?></label>
            <textarea id="observacionAnterior" name="observacionAnterior" disabled="disabled">
					<?php

					echo trim(htmlspecialchars($tramiteFlujo['observacion']));
                    ?>
				</textarea>
         </div>
         <div class="justificado">
            <label for="observacion">Observaciones :</label>
            <textarea id="observacion" name="observacion"></textarea>
         </div>
         <div>
            <hr />
            <input type="radio" id="boolAceptoSI" name="boolAcepto" value="SI" />
            <label for="boolAceptoSI"><?php echo $textoOpcionSI; ?></label>
            <br />
            <input type="radio" id="boolAceptoNO" name="boolAcepto" value="NO" />
            <label for="boolAceptoNO"><?php echo $textoOpcionNO; ?></label>

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
</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>

<script type="text/javascript">
	var protocolo=<?php echo json_encode($datosGenerales); ?>;
	var id_fase=<?php echo json_encode($id_fase); ?>;
	var tramiteFlujo=<?php echo json_encode($tramiteFlujo); ?>;
	var sobreTiempo=<?php echo json_encode($sobreTiempo); ?>;

	$("document").ready(function(){

	

		distribuirLineas();
		
		if(tramiteFlujo.pendiente=="S"){

			$('.observacionAnterior').hide();
		}
		else{
			if(tramiteFlujo.observacion==null || tramiteFlujo.observacion.trim().length==0)
				$('.observacionAnterior').hide();
			else
				$('.observacionAnterior').show();
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


</script>

