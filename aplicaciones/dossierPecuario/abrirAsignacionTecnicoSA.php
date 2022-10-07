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
		
	}
	$tramite=$ce->obtenerTramiteDesdeFlujoTramiteEE($conexion,$id_tramite_flujo);
	$id_tramite=$tramite['id_tramite'];
	
	$fechaActual=new DateTime();
	$fechaTiempo=new DateTime($tramite['fecha_final']);
	$sobreTiempo=false;
	if($fechaActual>$fechaTiempo)
		$sobreTiempo=true;

?>

<header>  
   <h1>Asignación de trámites</h1>
</header>

<div id="estado"></div>


<div id="asignar">
	<form id="frmAsignarTecnico" data-rutaAplicacion="dossierPecuario" data-opcion="atenderFlujosPecuarios" data-accionEnExito = 'ACTUALIZAR'>
		<input type="hidden"  id="id_documento" name="id_documento" value="<?php echo $id_solicitud;?>"/>
		<input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />
      <input type="hidden" id="id_fase" name="id_fase" value="<?php echo $id_fase;?>" />
		<input type="hidden" id="id_tramite_flujo" name="id_tramite_flujo" value="<?php echo $id_tramite_flujo;?>" />
		<input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $id_tramite;?>" />
      <input type="hidden" id="opcion_llamada" name="opcion_llamada" value="asignarTramiteIngreso" />
      
		
		
      <fieldset>
         <legend>Por asignar solicitud : <?php echo $id_solicitud;?></legend>
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
                          ?>" disabled="disabled" />

            
         </div>
         <div data-linea="3">
            <label>Producto :</label>
				<input value="<?php echo $datosGenerales['nombre'];?>" disabled="disabled" />
            
         </div>

         <div data-linea="4">
            <label>Solicitud de registro de nueva CEPA :</label>
            <input value="<?php echo $datosGenerales['nueva_cepa'];?>" disabled="disabled" />
            
         </div>

         <div data-linea="9" class="fase2">
            <label for="director">Asignar director :</label>
            <select name="director" id="director">
               <option value="">Seleccione....</option><?php
         $items = $ce->obtenerAnalistas($conexion,'PFL_DP_DCZ',$tramite['id_division']);
         foreach ($items as $key=>$item){
         echo '<option value="' . $item['identificador'] . '">' . $item['nombre_apellido'] . '</option>';
         }
         ?>
            </select>
         </div>
         <div data-linea="10" class="fase3">
            <label for="tecnico">Asignar técnico :</label>
            <select name="tecnico" id="tecnico">
               <option value="">Seleccione....</option><?php
         $items = $ce->obtenerAnalistas($conexion,'PFL_DP_TSA',$tramite['id_division']);
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
		<button type="submit" class="guardar">Enviar asignación</button>
		
	</form>
</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>

<script type="text/javascript">
	var protocolo=<?php echo json_encode($datosGenerales); ?>;
	var id_fase=<?php echo json_encode($id_fase); ?>;
	var sobreTiempo=<?php echo json_encode($sobreTiempo); ?>;
	


	$("document").ready(function(){

		distribuirLineas();

		if(id_fase==2){
			$('.fase2').show();
			$('.fase3').hide();
		}
		else{
			$('.fase3').show();
			$('.fase2').hide();
		}
		
		if(sobreTiempo)
			$('#verObservacionLimite').show();
		else
			$('#verObservacionLimite').hide();

	});


	$("#frmAsignarTecnico").submit(function(event){
		event.preventDefault();
		
		var error = false;
		if(id_fase==2){
			if(!esNoNuloEsteCampo("#director"))
				error = true;
		}
		if(id_fase==3){
			if(!esNoNuloEsteCampo("#tecnico"))
				error = true;
		}
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
			mostrarMensaje("Tramite ha sido asignado","EXITO");
			$('#asignar').html('Tramite ha sido asignado');
		};
	}


</script>

