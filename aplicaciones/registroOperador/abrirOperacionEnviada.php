<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$qSolicitud = $cr->abrirOperacionRevision($conexion, $_POST['id']);

$qTipoSubtipo = $cc->obtenerTipoSubtipoXProductos($conexion, $qSolicitud[0]['idProducto']);

$tipo = pg_fetch_result($qTipoSubtipo, 0, 'nombre_tipo');

$subtipo = pg_fetch_result($qTipoSubtipo, 0, 'nombre_subtipo');

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);
?>

<header>
	<h1>Solicitud Operador</h1>
</header>
<div id="estado"></div>

<div class="pestania">

	<fieldset>
		<legend>Registro de Operador</legend>
		<div data-linea="1">
			<label>Tipo de operación: </label> <?php echo $qSolicitud[0]['tipoOperacion']; ?> <br />
		</div>
		<?php 
   	 		if($qSolicitud[0]['nombrePais'] != ''){
    			 echo '<div data-linea="1">
      			<label>País: </label>' .  $qSolicitud[0]['nombrePais'] .'</div>';
			}
		?>
		<div data-linea="5">
			<label>Tipo producto: </label> <?php echo $tipo; ?> 
		</div>
		
		<div data-linea="6">
			<label>Subtipo producto: </label> <?php echo $subtipo; ?> 
		</div>
		
		<div data-linea="7">
			<label>Producto: </label> <?php echo $qSolicitud[0]['producto']; ?> 
		</div>

		<div data-linea="3">
			<label>Razón social: </label> <?php echo $qSolicitud[0]['ruc']; ?> <br />
		</div>

		<div data-linea="4">
			<label>Representante legal: </label> <?php echo $qSolicitud[0]['nombreRepresentante'] . ' ' . $qSolicitud[0]['apellidoRepresentante']; ?> <br />
		</div>

		<div data-linea="8">
			<label>Estado de solicitud: </label> <?php echo $qSolicitud[0]['estado']; ?> <br />
		</div>
		<!-- ?php 
		$inspectores='';
				
			if($qSolicitud[0]['estado'] == 'asignado' || $qSolicitud[0]['estado'] == 'proceso' || $qSolicitud[0]['estado'] == 'finalizado'){
		    $res = $cr->listarInspectoresAsignados($conexion, $_POST['id']);
		
		     echo '
				<div data-linea="6">
				<label>Inspectores asignados: </label>';
		
		     while($fila = pg_fetch_assoc($res)){
		     	echo $fila['apellido'].", ".$fila['nombre']."; ";
		     }
		
		     echo '</div>';
		    }
    	?-->
	</fieldset>

	<?php 
	$numeroAreaProduccion=1;
	foreach ($qSolicitud as $solicitud){
		echo '
		<fieldset>
		<legend>Área de Producción ' . $numeroAreaProduccion . '</legend>
		<div data-linea="3">
		<label>Nombre del sitio: </label> ' . $solicitud['nombreSitio'] . ' <br/>
		</div>
		<div data-linea="4">
		<label>Nombre del área: </label> ' . $solicitud['nombreArea'] . ' <br/>
		</div>
		<div data-linea="4">
		<label>Tipo de área: </label> ' . $solicitud['tipoArea'] . ' <br/>
			</div>
			<div data-linea="5">
			<label>Provincia: </label> ' . $solicitud['provincia'] . ' <br/>
		</div>
		<div data-linea="5">
		<label>Cantón: </label> ' . $solicitud['canton'] . ' <br/>
				</div>
				<div data-linea="6">
				<label>Parroquia: </label> ' . $solicitud['parroquia'] . ' <br/>
				</div>
				<div data-linea="7">
				<label>Dirección: </label> ' . $solicitud['direccionSitio'] . ' <br/>
				</div>
				<div data-linea="8">
				<label>Referencia: </label> ' . $solicitud['referencia'] . ' <br/>
					</div>
					<div data-linea="9">
					<label>Superficie utilizada: </label> ' . $solicitud['superficieArea'] . ' <br/>
					</div>
					<div data-linea="9">
					<label>Croquis: </label>'. ($solicitud['croquis']=='0'? '<span class="alerta">No ha subido ningún archivo</span>':'<a href='.$solicitud['croquis'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>').'
					</div>
					<div data-linea="10" >
					<label>Estado: </label> ' . ($solicitud['estadoArea']=='registrado'? '<span class="exito">'.$solicitud['estadoArea'].'</span>':'<span class="alerta">'.$solicitud['estadoArea'].'</span>'). '<br/>
     		</div>';
		if($solicitud['ruta_archivo']!='0' && $solicitud['observacionArea']!= ''){
					    echo   '<div data-linea="10">
				<label>Informe: </label>'. ($solicitud['ruta_archivo']=='0'? '<span class="alerta">No ha subido ningún archivo</span>':'<a href='.$solicitud['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>').'
				</div>
				<div data-linea="11">
				<label>Observación: </label> ' . $solicitud['observacionArea'] . ' <br/>
				</div>';
					   }
			echo '</fieldset>';
					   $numeroAreaProduccion++;
	}
	?>


</div>
<div class="pestania">

	<form id="evaluarSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarElementosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="tipoSolicitud" value="Operadores"/>
		<input type="hidden" name="tipoInspector" value="Técnico"/>
		<input type="hidden" name="identificadorOperador" value="<?php echo $qSolicitud[0]['identificador'];?>"/> <!-- USUARIO OPERADOR -->
		<input type="hidden" name="idVue" value="<?php echo $qSolicitud[0]['idVue'];?>"/>
		<input type="hidden" name="tipoElemento" value="Áreas"/>
		
		<?php 
			//Obtener el número de elementos a inspeccionar
			//$historial = $ci->listarHistorialSolicitudes($conexion, $_POST['id']);
			
			if (true){
				echo '<fieldset>
						<legend>Historial de revisión</legend>
						
						<table>
							<tr>
								<th>#</th>
								<th>Área</th>
								<th>Estado</th>
							</tr>';
				$i=1;
				//while($registrosHistorial = pg_fetch_assoc($historial)){
				foreach($qSolicitud as $registrosHistorial){
					echo '<tr>
							<td>'.$i.'</td>
							<td>'.$registrosHistorial['nombreArea'].'</td>
							<td>'.$registrosHistorial['estadoArea'].'</td>
						</tr>';
					
					$i++;
				}
				
				echo '	</table>
					</fieldset>';
			}
		?>
		
		<fieldset>
			<legend>Áreas para revisión</legend>
			
			<p class="nota">Por favor marque solamente las áreas que va a evaluar.</p>
			<table>	
				<?php 
				$contadorDiv=13;
					foreach ($qSolicitud as $areas){
						if($areas['estadoArea']=='' || $areas['estadoArea']=='noHabilitado'){
							echo '<tr>
									<td>
										<input type="checkbox" id="'.$areas['idArea'].'" name="listaElementos[]" value="'.$areas['idArea'].'">
									</td>
									<td >
										<label for="'.$areas['idArea'].'">'. $areas['tipoArea'] . ' - '. $areas['nombreSitio'] . ' - ' . $areas['nombreArea'] . '</label>
									</td>
								</tr>';
						}
					}
				?>
			</table>
		</fieldset>	
	
		<fieldset id="subirInforme">
				<legend>Informe de revisión</legend>
					<input type="file" name="informe" id='informe' />
					<input type="hidden" id="archivo" name="archivo" value="0"/>
					<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?> "/>
					
		</fieldset>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
					
				<div data-linea="6">
					<label>Resultado</label>
						<select id="resultado" name="resultado">
							<option value="">Seleccione....</option>
							<option value="registrado">Registrado</option>
							<option value="noHabilitado">No habilitado</option>
						</select>
				</div>	
				<div data-linea="2">
					<label>Observaciones</label>
						<input type="text" id="observacion" name="observacion"/>
				</div>
		</fieldset>
		
		<button type="submit" class="guardar">Enviar resultado</button>
	</form>	
		
</div>
<script type="text/javascript">					
var estado= <?php echo json_encode($qSolicitud[0]['estado']); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));	

		if(estado == "inspeccion" || estado == "asignadoInspeccion"){
			$("#evaluarSolicitud").show();
		}else{
			$("#evaluarSolicitud").hide();
		}
	});

	$("#evaluarSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccion(this);
	});

	$("#archivo").click(function(){
		$("#subirInforme button").removeAttr("disabled");});
	
	$('#subirInforme').change(function(event){
		subirArchivo('informe',<?php echo $_SESSION['usuario'];?>+'-'+<?php echo $_POST['id'];?>+'-'+$('#fecha').val().replace(/ /g,''),'aplicaciones/registroOperador/informeOperacion', 'archivo');
	});
	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposInspeccion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if($("#archivo").val() == 0){
			error = true;
			$("#informe").addClass("alertaCombo");
		}
		
		if(!$.trim($("#resultado").val()) || !esCampoValido("#resultado")){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}
	
		if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
</script>
