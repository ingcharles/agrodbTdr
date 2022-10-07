<?php 
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorRegistroOperador.php';


	$usuario=$_SESSION['usuario'];
	$id_documento = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];
	$tipo_documento = $_POST['opcion'];
	$id_tramite = $_POST['nombreOpcion'];

	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	$cr = new ControladorRegistroOperador();

	$identificador=$usuario;

	

	$datosGenerales=array();
	$zonas=array();
	//Busca el protocolo
	if($id_documento!=null){
		$datosGenerales=$ce->obtenerProtocolo($conexion, $id_documento);
		$identificador=$datosGenerales['identificador'];
		$zonas=$ce->obtenerProtocoloZonasNoAsignado($conexion,$id_documento);
		//Elimina las zonas que ya tienen asignado el supervisor
		foreach($zonas as $key=>$item){
			$resultado=$ce->obtenerInformeFinal($conexion,$item['id_protocolo_zona']);
			if($resultado !=null){		//Ya existe supervisor asignado
				unset($zonas[$key]);
			}
		}

		//Busca si hay un trámite en curso 
		$id_fase=$ce->obtenerFaseDelFlujo($conexion,$id_flujo,'elegirOrganismo');
		$query=$ce->obtenerFlujosDeTramitesProtocoloEE($conexion,$id_fase,$identificador,$id_documento);
		if(pg_num_rows($query)>0){
			$tramiteFlujo=pg_fetch_assoc($query,0);
			$id_tramite_flujo=$tramiteFlujo['id_tramite_flujo'];
			$id_tramite=$tramiteFlujo['id_tramite'];
			
			$ident_ejecutor=$tramiteFlujo['ejecutor'];
		}
	}
	
	$organismosInspeccion=$ce->obtenerOperadoresPorPerfil($conexion,'PFL_EE_OI');
	
	$motivos=$ce->listarElementosCatalogo($conexion,'P1C2');
	$operador=pg_fetch_assoc($cr->listarOperadoresEmpresa($conexion,$identificador),0);

	$fechaActual=new DateTime();
	$fechaTiempo=new DateTime($tramiteFlujo['fecha_fin']);
	$sobreTiempo=false;
	if($fechaActual>$fechaTiempo)
		$sobreTiempo=true;

?>

<header>
	<h1>Elección de organismo de inspección</h1>
</header>

<div id="estado"></div>


<div >
   <form id="frmProceso" data-rutaaplicacion="ensayoEficacia" data-opcion="atenderFlujosInformes" data-accionenexito='ACTUALIZAR'>
      <input type="hidden" id="id_documento" name="id_documento" value="<?php echo $id_documento;?>" />
      <input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />
      <input type="hidden" id="tipo_documento" name="tipo_documento" value="<?php echo $tipo_documento;?>" />
      <input type="hidden" id="id_tramite_flujo" name="id_tramite_flujo" value="<?php echo $id_tramite_flujo;?>" />
      <input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $id_tramite;?>" />

		<input type="hidden" id="id_expediente" name="id_expediente" value="<?php echo $datosGenerales['id_expediente'];?>" />
		<input type="hidden" id="plaguicida_nombre" name="plaguicida_nombre" value="<?php echo $datosGenerales['plaguicida_nombre'];?>" />

      <input type="hidden" id="opcion_llamada" name="opcion_llamada" value="guardarOrganismoInspeccion" />


      <fieldset>
         <legend>Elección de Organismos de Inspección</legend>
         <div data-linea="1">
            <label>Empresa :</label>
            <label><?php echo $operador['nombre_operador'];?>
            </label>
         </div>
         <div data-linea="3">
            <label>Expediente :</label>
            <label><?php echo $datosGenerales['id_expediente'];?>
            </label>
         </div>
         <div data-linea="5">
            <label>Motivo :</label>
            <label>            <?php
            foreach($motivos as $key=>$value){
            if($value['codigo']==$datosGenerales['motivo']){
            echo $value['nombre'];
            }
            }
            ?>

            </label>
         </div>
         <div data-linea="6">
            <label>Producto :</label>
            <label><?php echo $datosGenerales['plaguicida_nombre'];?>
            </label>
         </div>
         <div data-linea="8">
            <label for="zonas">Lugares declarados:</label>
            <select name="zonas" id="zonas">
               <option value="">Seleccione....</option>            <?php
            foreach($zonas as $key=>$value){
					echo "<option value=".$value['id_protocolo_zona'].">".$value['zona']."-".$value['provincia_nombre']."</option>";
				}
            ?>
            </select>
         </div>
      </fieldset>
      
		<fieldset id="frmOrganismo">
         <legend>Elección del Organismo de Inspección</legend>
         <div data-linea="2">
            <label for="organismo">Disponibles:</label>
            <select name="organismo" id="organismo">
               <option value="">Seleccione....</option><?php
            foreach($organismosInspeccion as $key=>$value){
            echo "<option value=".$value['identificador'].">".$value['razon_social']."</option>";
            }
            ?>
            </select>
         </div>
         <div data-linea="3">
            <label for="correo">Correo:</label>
            <input id="correo" name="correo" />
         </div>
         <div class="justificado">
            <label for="observacion" class="opcional">Texto a notificar:</label>
            <textarea name="observacion" id="observacion" placeholder="Información para el organismo de inspección seleccionado" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"></textarea>

         </div>
         <div data-linea="5">
            <button type="submit" class="guardar">Enviar notificación</button>
         </div>
      </fieldset>

		<fieldset id="verObservacionLimite">
			<legend>Información</legend>
			<div data-linea="1">
            <label>Fecha de aprobación :</label>
            <label><?php echo $tramiteFlujo['fecha_inicio'];?></label>
         </div>
			<div data-linea="2">
            <label>Fecha de caducidad :</label>
            <label><?php echo $tramiteFlujo['fecha_fin'];?></label>
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

	var organismosInspeccion=<?php echo json_encode($organismosInspeccion); ?>;
	var zonas=<?php echo json_encode($zonas); ?>;
	var sobreTiempo=<?php echo json_encode($sobreTiempo); ?>;

	$("document").ready(function(){

		distribuirLineas();

		$('#frmOrganismo').hide();

		if(sobreTiempo){
			$('#verObservacionLimite').show();
			$('#zonas').attr('disabled', 'disabled');
		}
		else{
			$('#verObservacionLimite').hide();
			
		}

	});

	$('#zonas').change(function(){
		if($(this).val()=='')
			$('#frmOrganismo').hide();
		else{
			$('#frmOrganismo').show();
			cargarValorDefecto('organismo','');
			$('#correo').val('');
			$('#observacion').val('');

		}
	});

	$('#organismo').change(function () {
		$('#correo').val('');
		$('#observacion').val('');
		if(organismosInspeccion!=null && organismosInspeccion.length>0){
			for(var i in organismosInspeccion){
				if(organismosInspeccion[i].identificador==$(this).val()){
					$('#correo').val(organismosInspeccion[i].correo);
					break;
				}
			}
		}

	});



	//*********************** SUBMIT*********
	$("#frmProceso").submit(function(event){
		event.preventDefault();

		error = false;
		if(!esNoNuloEsteCampo("#organismo"))
			error = true;
		if(!esValidoEsteCampo("#correo"))
			error = true;
		
		if(error){
			mostrarMensaje("Favor llene los campos obligatorios","FALLO");
			return;
		}

		borrarMensaje();

		var form=$(this);		
		form.append("<input type='hidden' id='zonaNombre' name='zonaNombre' value='"+$('#zonas :selected').text()+"' />"); 
		form.append("<input type='hidden' id='organismoNombre' name='organismoNombre' value='"+$('#organismo :selected').text()+"' />"); 

		mostrarMensaje("Epere mientras se procesa la información", "FALLO");
		ejecutarJson($(this),new organismoEnviado());


	});

	function organismoEnviado() {
		this.ejecutar = function(msg) {

			mostrarMensaje("El trámite fue asignado correctamente", "EXITO");
			if(msg.mensaje=='-1'){
				$("#detalleItem").html('<div class="mensajeInicial">El ensayo fue asignado en todas las zonas declaradas.</div>');
				abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
				abrir($("input:hidden"), null, false);
			}
			else{
				mostrarMensaje("Organismo de inspección fue asignado","EXITO");
				$('#asignar').html('Organismo de inspección fue asignado');
				//remueve la zona de la lista
				for(var i in zonas){
					if(zonas[i].id_protocolo_zona==msg.mensaje){
						delete zonas[i];
					}

				}
				$('#opcion_llamada').val('guardarOrganismoInspeccion');
				$('#frmOrganismo').hide();
				cargarValorDefecto('organismo','');
				$('#correo').val('');
				$('#observacion').val('');
				var elemento=$('#zonas');
				elemento.children('option').remove();
				elemento.append($("<option></option>").attr("value","").text("Seleccione...."));
				if(zonas.length>0){
					$.each(zonas, function(key, value) {
						elemento.append($("<option></option>").attr("value",value.id_protocolo_zona).text(value.zona+"-"+value.provincia_nombre));
					});
				}

			}
		};
	}

   </script>
