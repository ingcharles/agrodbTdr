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
		
	}
	$tramite=$ce->obtenerTramiteDesdeFlujoTramiteEE($conexion,$id_tramite_flujo);
	$id_tramite=$tramite['id_tramite'];
	
	$perfiles= $ce->obtenerPerfiles($conexion,$idUsuario);
	$perfil=new Perfil($perfiles);
	$division=$ce->obtenerDivisionZonal($conexion,$idUsuario);
	$selector_valor='';
	$perfilBuscar='';
	if($perfil->tieneEstePerfil('PFL_RES_CENTRAL')){
		$perfilBuscar='PFL_DP_ARIP';
		$selector_valor='planta_central';
	}	
	else if($perfil->tieneEstePerfil('PFL_RES_DISTRITO')){
		$perfilBuscar='PFL_DP_ADIP';
	}
	else if($perfil->tieneEstePerfil('PFL_EE_DDTA')){
		$perfilBuscar='PFL_DP_ADIP';
	}

	$fechaActual=new DateTime();
	$fechaTiempo=new DateTime($tramite['fecha_final']);
	$sobreTiempo=false;
	if($fechaActual>$fechaTiempo)
		$sobreTiempo=true;

?>

<header>
	<h1>Asignación de tramites a técnicos</h1>
</header>

<div id="estado"></div>


<div id="asignar">
	<form id="frmAsignarTecnico" data-rutaAplicacion="dossierPecuario" data-opcion="atenderFlujosPecuarios" data-accionEnExito = 'ACTUALIZAR'>
		<input type="hidden"  id="id_documento" name="id_documento" value="<?php echo $id_solicitud;?>"/>
		<input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />
      <input type="hidden" id="id_fase" name="id_fase" value="<?php echo $id_fase;?>" />
		<input type="hidden" id="id_tramite_flujo" name="id_tramite_flujo" value="<?php echo $id_tramite_flujo;?>" />
		<input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $id_tramite;?>" />
      <input type="hidden" id="opcion_llamada" name="opcion_llamada" value="asignarTramiteDossier" />
      
      <input type="hidden" id="selector_valor" name="selector_valor" value="<?php echo $selector_valor;?>" />
				
		<fieldset>
			<legend>Por asignar solicitud : <?php echo $id_solicitud;?></legend>
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
                          ?>" disabled="disabled" />


         </div>
         <div data-linea="4">
            <label>Producto :</label>
            <input value="<?php echo $datosGenerales['nombre'];?>" disabled="disabled" />

         </div>

			
			<div data-linea="10" >
				<label for="tecnico" >Asignar técnico:</label>
				<select name="tecnico" id="tecnico">
							<option value="">Seleccione....</option>
							<?php 
							$items = $ce->obtenerAnalistas($conexion,$perfilBuscar,$division);
							
							foreach ($items as $key=>$item){
								echo '<option value="' . $item['identificador'] . '">' . $item['nombre_apellido'] . '</option>';
							}
                            ?>
						</select>
			</div>
			
			
		</fieldset>
		
		<fieldset id="verObservacionLimite">
			<legend>Datos necesarios</legend>
			<div data-linea="1">
            <label>Justificación del retraso de su respuesta :</label>
				<textarea id="retraso" name="retraso" data-distribuir='no' maxlength="512"></textarea>
            
         </div>

		</fieldset>

		<button type="submit" class="guardar">Guardar asignación</button>
		
	</form>
</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>

<script type="text/javascript">
	var protocolo=<?php echo json_encode($datosGenerales); ?>;
	var id_fase=<?php echo json_encode($id_fase); ?>;
	var sobreTiempo=<?php echo json_encode($sobreTiempo); ?>;

	$("document").ready(function(){

		distribuirLineas();

	   if(sobreTiempo)
			$('#verObservacionLimite').show();
		else
			$('#verObservacionLimite').hide();

	});


	$("#frmAsignarTecnico").submit(function(event){
		event.preventDefault();
		
		var error = false;
		
			if(!esNoNuloEsteCampo("#tecnico"))
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
			mostrarMensaje("Tramite a sido asignado","EXITO");
			$('#asignar').html('Tramite a sido asignado');
		};
	}


</script>

