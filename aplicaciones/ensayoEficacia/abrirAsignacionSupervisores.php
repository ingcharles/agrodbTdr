<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	

	
	$idUsuario= $_SESSION['usuario'];
	$idProtocolo = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];
	$id_fase = $_POST['opcion'];
	$id_tramite_flujo = $_POST['nombreOpcion'];
	$identificador=$idUsuario;

	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	$cr = new ControladorRegistroOperador();
	

	$datosGenerales=array();
	$zonas=array();
	//Busca el protocolo
	if($idProtocolo!=null){
		$datosGenerales=$ce->obtenerProtocolo($conexion, $idProtocolo);
		$identificador=$datosGenerales['identificador'];
		$motivos=$ce->listarElementosCatalogo($conexion,'P1C2');
		$zonas=$ce->obtenerProtocoloZonas($conexion,$idProtocolo);
		//Elimina las zonas que ya tienen asignado el supervisor
		foreach($zonas as $key=>$item){
			$resultado=$ce->obtenerInformeFinal($conexion,$item['id_protocolo_zona']);
			if($resultado !=null){		//Ya existe supervisor asignado
				unset($zonas[$key]);
			}
		}
	}
	$tramite=$ce->obtenerTramiteDesdeFlujoTramiteEE($conexion,$id_tramite_flujo);
	$id_tramite=$tramite['id_tramite'];

		$perfilAnalista='PFL_EE_SE';
	$analistas = $ce->obtenerAnalistas($conexion,$perfilAnalista);


	$tramites=$ce->consultarTramites($conexion,null,null,'EP');

	$operador=pg_fetch_assoc($cr->listarOperadoresEmpresa($conexion,$identificador),0);
?>

<header>
	<h1>Asignación de supervisor de ensayos</h1>
</header>

<div id="estado"></div>


<div id="asignar">
	<form id="frmAsignarTecnico" data-rutaaplicacion="ensayoEficacia" data-opcion="atenderFlujosInformes" data-accionenexito='ACTUALIZAR'>
		<input type="hidden" id="id_documento" name="id_documento" value="<?php echo $idProtocolo;?>" />
		<input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />
		<input type="hidden" id="id_tramite_flujo" name="id_tramite_flujo" value="<?php echo $id_tramite_flujo;?>" />
		<input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $id_tramite;?>" />
		<input type="hidden" id="id_division" name="id_division" value="<?php echo $tramite['id_division'];?>" />
		<input type="hidden" id="id_protocolo_zona" name="id_protocolo_zona" value="" />
		<input type="hidden" id="opcion_llamada" name="opcion_llamada" value="guardarAsignacionSupervisor" />

		<fieldset>
			<legend>
				Por asignar solicitud : <?php echo $idProtocolo;?>
			</legend>

			<div data-linea="1">
				<label>Empresa :</label>
				<label>
					<?php echo $operador['nombre_operador'];?>
				</label>
			</div>
			<div data-linea="3">
				<label>Expediente :</label>
				<label>
					<?php echo $datosGenerales['id_expediente'];?>
				</label>
			</div>
			<div data-linea="5">
				<label>Motivo :</label>
				<label>
					<?php
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
				<label>
					<?php echo $datosGenerales['plaguicida_nombre'];?>
				</label>
			</div>
			<div data-linea="7">
				<label for="zona">Seleccione la zona del ensayo :</label>
				<select name="zona" id="zona"></select>
			</div>
			<div data-linea="8">
				<label>Historial de trámites:</label>
			</div>
			<table>
				<thead>
					<tr>
						<th></th>
						<th>Tipo de trámite</th>
						<th>Distrital</th>
						<th>Espediente</th>
						<th>Fecha de asignacion</th>
						<th>Estado</th>
					</tr>
				</thead>
				<tbody id="tablaTecnicos"></tbody>
			</table>

			<div data-linea="9">
				<label for="tecnico">Asignar supervisor :</label>
				<select name="tecnico" id="tecnico"></select>
			</div>

		</fieldset>
		<button type="submit" class="guardar">Guardar asignación</button>

	</form>
</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>

<script type="text/javascript">
	var protocolo=<?php echo json_encode($datosGenerales); ?>;
	var zonas=<?php echo json_encode($zonas); ?>;
	var analistas=<?php echo json_encode($analistas); ?>;
	var tramites=<?php echo json_encode($tramites); ?>;
	

	$("document").ready(function(){
		distribuirLineas();

		verZonas(zonas);
	});

	function verZonas(items){
		$('#zona').children('option').remove();
		$('#zona').append($("<option></option>").attr("value","").text("Seleccione...."));
		for(var i in items){
			$('#zona').append($("<option></option>").attr("value",items[i].provincia).attr("data-zona",items[i].id_protocolo_zona).text(items[i].provincia_nombre));
		}
	}

	$('#zona').change(function(){
		$('#id_protocolo_zona').val($(this).find(':selected').data('zona'));
		$('#tecnico').children('option').remove();
		$('#tecnico').append($("<option></option>").attr("value","").text("Seleccione...."));
		for(var i in analistas){
			if(analistas[i].id_provincia==$(this).val()){
				$('#tecnico').append($("<option></option>").attr("value",analistas[i].identificador).text(analistas[i].nombre_apellido));


			}
		}
		verTablaTecnicos();
	});

	function verTablaTecnicos(){
		$('#tablaTecnicos > tr').remove();
		for (var i in analistas){
			if(analistas[i].id_provincia!=$('#zona').val())
				continue;
			$('#tablaTecnicos').append( '<tr>'+'<td colspan="6">'+analistas[i].nombre_apellido +'</td></tr>');
			var codigoHtml='';
			for (var k in tramites){
				codigoHtml='';
				if(analistas[i].identificador==tramites[k].tecnico_asignado){
					codigoHtml='<tr><td></td>';
					codigoHtml=codigoHtml+'<td>'+tramites[k].documento+'</td><td>'+tramites[k].division+'</td><td>'+tramites[k].expediente;
					codigoHtml=codigoHtml+'</td><td>'+tramites[k].fecha_inicio+'</td><td>'+tramites[k].estado+'</td>';
					codigoHtml=codigoHtml+'</tr>';
					$('#tablaTecnicos').append( codigoHtml);
				}
			}
		}
	}

	$("#frmAsignarTecnico").submit(function(event){
		event.preventDefault();
		var error = false;
		if(!esNoNuloEsteCampo("#tecnico"))
			error = true;
		if(!esNoNuloEsteCampo("#zona"))
			error = true;

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		if (!error){
			ejecutarJson($(this),new exitoAsignacion());
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}

	});

	function exitoAsignacion(){
		this.ejecutar=function(msg){
			if(msg.mensaje=='-1'){
				abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
				abrir($("input:hidden"), null, false);
				$("#detalleItem").html('El ensayo fue asignado en todas las zonas declaradas');
			}
			else{
				mostrarMensaje("Supervisor fue asignado","EXITO");
				$('#asignar').html('Supervisor fue asignado');
				//remueve la zona de la lista
				for(var i in zonas){
					if(zonas[i].provincia==msg.mensaje){
						delete zonas[i];
					}

				}
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

