<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorRegistroOperador.php';


	
	$idUsuario= $_SESSION['usuario'];
	$idProtocolo = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];
	$tipo_documento = $_POST['opcion'];
	$id_tramite_flujo = $_POST['nombreOpcion'];
	$identificador=$idUsuario;

	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	$cr = new ControladorRegistroOperador();
	

	$datosGenerales=array();
	$informe=array();
	//Busca el protocolo
	if($idProtocolo!=null && $tipo_documento!=null){
		if($tipo_documento=='EP')
			$datosGenerales=$ce->obtenerProtocolo($conexion, $idProtocolo);
		elseif($tipo_documento=='IF'){
			$datosGenerales=$ce->obtenerProtocoloDesdeInforme($conexion,$idProtocolo);
			$informe=$ce->obtenerInformeFinalEnsayo($conexion,$idProtocolo);
		}
		$identificador=$datosGenerales['identificador'];
		$motivos=$ce->listarElementosCatalogo($conexion,'P1C2');
		
	}
	$tramite=$ce->obtenerTramiteDesdeFlujoTramiteEE($conexion,$id_tramite_flujo);
	$id_tramite=$tramite['id_tramite'];
	
	if($tramite['identificador']=='PFL_RES_CENTRAL')
		$perfilAnalista='PFL_EE_ARIA';
	else
		$perfilAnalista='PFL_EE_ADTA';
	$analistas = $ce->obtenerAnalistas($conexion,$perfilAnalista,$tramite['id_division']);
	$tramites=$ce->consultarTramites($conexion,null,null,$tipo_documento);
	
	$operador=pg_fetch_assoc($cr->listarOperadoresEmpresa($conexion,$identificador),0);

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
	<form id="frmAsignarTecnico" data-rutaAplicacion="ensayoEficacia" data-opcion="atenderFlujos" data-accionEnExito = 'ACTUALIZAR'>
		<input type="hidden"  id="id_documento" name="id_documento" value="<?php echo $idProtocolo;?>"/>
		<input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />
		<input type="hidden" id="id_tramite_flujo" name="id_tramite_flujo" value="<?php echo $id_tramite_flujo;?>" />
		<input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $id_tramite;?>" />
      <input type="hidden" id="opcion_llamada" name="opcion_llamada" value="guardarAsignacionProtocolo" />
		
		<fieldset>
			<legend>Por asignar solicitud : <?php echo $idProtocolo;?></legend>
         
				<div data-linea="1">
					<label>Empresa :</label>
               <label><?php echo $operador['nombre_operador'];?></label>
				</div>
         <div data-linea="3">
            <label>Expediente :</label>
            <label><?php
						 if($tipo_documento=="EP")
							echo $datosGenerales['id_expediente'];
						 if($tipo_documento=="IF")
							 echo $informe['id_expediente'];
						 ?></label>
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
         <div data-linea="7">
            <label>Producto :</label>
            <label><?php echo $datosGenerales['plaguicida_nombre'];?>
            </label>
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
				<tbody>
					
					<?php
					$str='';
					foreach ($analistas as $key=>$tecnico){
						$str=$str. '<tr>'.'<td colspan="6">'.$tecnico['nombre_apellido'].'</td></tr>';
						$tramitesAnalista=array_filter($tramites,function($item) use($tecnico){ return ($item['tecnico_asignado']==$tecnico['identificador']);});
						foreach ($tramitesAnalista as $clave=>$item){							
							
								$codigoHtml='';
								$fecha=new DateTime($item['fecha_inicio']);
								$fecha=$fecha->format('Y-M-d');
								$codigoHtml='<tr><td></td>';
								$codigoHtml=$codigoHtml.'<td>'.$item['documento'].'</td>'.'<td>'.$item['division'].'</td>'.'<td>'.$item['expediente'].
									'</td>'.'<td>'.$fecha.'</td>'.'<td>'.$item['estado'].'</td>';
								$codigoHtml=$codigoHtml.'</tr>';
							
								$str=$str. $codigoHtml;
						}
						
					}
					echo $str;
					?>
				</tbody>
			</table>
			<div data-linea="9">
				<label for="tecnico" >Asignar técnico :</label>
				<select name="tecnico" id="tecnico">
							<option value="">Seleccione....</option>
							<?php 
							
							foreach ($analistas as $key=>$item){
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
	var tipo_documento=<?php echo json_encode($tipo_documento); ?>;
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

		var form=$(this);
		if(tipo_documento=='EP'){
			form.attr('data-opcion',"atenderFlujos");
			$('#opcion_llamada').val('guardarAsignacionProtocolo');
		}
		else if (tipo_documento=='IF'){
			form.attr('data-opcion',"atenderFlujosInformes");
			$('#opcion_llamada').val('guardarAsignacionInforme');
		}
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

