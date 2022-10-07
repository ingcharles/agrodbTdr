<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificadoCalidad.php';

$conexion = new Conexion();
$cca = new ControladorCertificadoCalidad();
$cc = new ControladorCatalogos();

$identificadorUsuario = $_SESSION['usuario'];

$idSolicitud = ($_POST['elementos']==''?$_POST['id']:$_POST['elementos']);

$qCertificadoCalidad = $cca->obtenerSolicitudCertificadoCalidadXGrupoLotes($conexion, $idSolicitud);

$informacionLote = pg_fetch_assoc($cca->obtenerDatosInspeccionAgenciaVerificadora($conexion, $idSolicitud));

$resultadoInspeccionAgencia = pg_fetch_assoc($cca->obtenerDatosResultadoAgenciaVerificadora($conexion, $idSolicitud));

?>

<header>
	<h1>Solicitud certificado de calidad</h1>
</header>

	<div id="estado"></div>
	
	<div class="pestania">

<?php 
	
	while ($certificadoCalidad = pg_fetch_assoc($qCertificadoCalidad)){
		
		echo'<fieldset>
				<legend>Solicitud # '.$certificadoCalidad['id_certificado_calidad'].' </legend>
		
				<div data-linea="1">
					<label>Datos exportador </label>
				</div>
			
				<div data-linea="2">
					<label>Identificación: </label> '. $certificadoCalidad['identificador_exportador'].'
				</div>
				
				<div data-linea="3">
					<label>Razón social: </label> '. $certificadoCalidad['razon_social_exportador'].'
				</div>
		
				<hr/>
		
				<div data-linea="4">
					<label>Datos del importador</label>
				</div>
			
				<div data-linea="5">
					<label>Nombre: </label> '. $certificadoCalidad['nombre_importador'].'
				</div>
				
				<div data-linea="6">
					<label>Dirección: </label> '. $certificadoCalidad['direccion_importador'].'
				</div>
				
				<hr/>
				
				<div data-linea="7">
					<label>Datos generales de exportación</label>
				</div>
				
				<div data-linea="8">
					<label>Fecha de embarque: </label> '. date('j/n/Y',strtotime($certificadoCalidad['fecha_embarque'])).'
				</div>
				
				<div data-linea="8">
					<label>Número de viaje: </label> '. $certificadoCalidad['numero_viaje'].'
				</div>
				
				<div data-linea="9">
					<label>País de embarque: </label> '. $certificadoCalidad['nombre_pais_embarque'].'
				</div>
				
				<div data-linea="9">
					<label>Puerto embarque: </label> '. $certificadoCalidad['nombre_puerto_embarque'].'
				</div>
				
				<div data-linea="10">
					<label>Medio de transporte: </label> '. $certificadoCalidad['nombre_medio_transporte'].'
				</div>
				
				<div data-linea="11">
					<label>País de destino: </label> '. $certificadoCalidad['nombre_pais_destino'].'
				</div>
				
				<div data-linea="11">
					<label>Puerto de destino: </label> '. $certificadoCalidad['nombre_puerto_destino'].'
				</div>';
						
				$lugarCertificadoCalidad = $cca->obtenerLugarXGrupoLotes($conexion, $idSolicitud, $certificadoCalidad['id_certificado_calidad']);
	
				$i = 20;
				
				while ($lugarCertificado = pg_fetch_assoc($lugarCertificadoCalidad)){
					
				echo '
						<hr/>
							<div data-linea='.++$i.'>
								<label class="mayusculas">Lugar de inspección '.$lugarCertificado['nombre_area_operacion'].'</label>
							</div>
						<hr/>
					
							<div data-linea='.++$i.'>
								<label>Nombre provincia: </label> '. $lugarCertificado['nombre_provincia'].'
							</div>
					
							<div data-linea='.$i.'>
								<label>Fecha de inspección: </label> '.  date('j/n/Y',strtotime($lugarCertificado['solicitud_fecha_inspeccion'])).'
							</div>
					
						';
				
						$loteCertificadoInspeccion = $cca->obtenerLoteCertificadoCalidad($conexion, $idSolicitud, $lugarCertificado['id_lugar_inspeccion']);
						
						$cantidadRegistros = pg_num_rows($loteCertificadoInspeccion);
						
						$aux = 0;
						while($loteCertificado = pg_fetch_assoc($loteCertificadoInspeccion)){
						
							$aux++;
							echo '
							<div data-linea='.++$i.'>
								<label>Nombre producto: '.$loteCertificado['nombre_producto'].'</label>
							</div>
								
							<div data-linea='.++$i.'>
								<label>Número lote: </label> '. $loteCertificado['numero_lote'].'
							</div>
							
							<div data-linea='.$i.'>
								<label>Valor FOB: </label> '. $loteCertificado['valor_fob'].'
							</div>
							<div data-linea='.++$i.'>
								<label>Peso neto: </label> '. $loteCertificado['peso_neto'] .' '.$loteCertificado['unidad_peso_neto'].'
							</div>
							<div data-linea='.$i.'>
								<label>Peso bruto: </label> '. $loteCertificado['peso_bruto'].' '.$loteCertificado['unidad_peso_bruto'].'
							</div>
							<div data-linea='.++$i.'>
								<label>Variedad: </label> '. $loteCertificado['nombre_variedad_producto'].'
							</div>
							<div data-linea='.$i.'>
								<label>Calidad: </label> '. $loteCertificado['nombre_calidad_producto'].'
							</div>';
						
							if($cantidadRegistros != $aux){
								echo '<hr/>';
							}
						}	
				}
						
				echo'</fieldset>';
		}

?>


</div>

<!-- SECCION DE REVISIÓN DE PRODUCTOS Y ÁREAS PARA IMPORTACION -->

<div class="pestania">

	<fieldset>
			<legend>Resultado de inspección</legend>
				
				<div data-linea="1">
					<label>Lote: </label><?php echo $informacionLote['numero_lote'];?>
				</div>
				
				<div data-linea="1">
					<label>Calidad: </label><?php echo $informacionLote['nombre_calidad_producto'];?>
				</div>
					
				<div data-linea="2">
					<label>Fecha análisis: </label><?php echo  date('j/n/Y',strtotime($resultadoInspeccionAgencia['fecha_inspeccion']));?>
				</div>
				
				<div data-linea="3">
					<label>Exportador: </label><?php echo $informacionLote['razon_social_exportador'];?>
				</div>
				
				<div data-linea="4">
					<label>Comprador: </label> <?php echo $informacionLote['nombre_importador'];?>
				</div>
				
				<div data-linea="5">
					<label>Vapor: </label><?php echo  $resultadoInspeccionAgencia['vapor'];?>
				</div>
				
				<div data-linea="6">
					<label>Muestra inspector: </label><?php echo  $resultadoInspeccionAgencia['muestra_inspector'];?>
				</div>
				
				<div data-linea="6">
					<label>Contra muestra: </label><?php echo  $resultadoInspeccionAgencia['contra_muestra'];?>
				</div>
		</fieldset>
		
		
		<fieldset>
			<legend>Datos generales </legend>
				<div data-linea="1">
					<label>Tipo de inspección: </label><?php echo  $resultadoInspeccionAgencia['tipo_inspeccion'];?>
				</div>
				
				<div data-linea="1">
					<label>Tipo de cacao verificado: </label><?php echo  $resultadoInspeccionAgencia['tipo_cacao'];?>
				</div>
				
				<div data-linea="3">
					<label>Higiene: </label><?php echo  $resultadoInspeccionAgencia['higiene'];?>
				</div>
				
				<div data-linea="3">
					<label>Seguridad alimenticia: </label><?php echo  $resultadoInspeccionAgencia['seguridad_alimenticia'];?>
				</div>
						
		</fieldset>
		
		<fieldset>
			<legend>Clasificación de corte</legend>
			
			<div data-linea="1">
					<label>Buena fermentación: </label><?php echo  $resultadoInspeccionAgencia['buena_fermentacion'];?> (%)
			</div>
			
			<div data-linea="1">
					<label>Ligeramente fermentados: </label><?php echo  $resultadoInspeccionAgencia['ligramente_fermentado'];?> (%)
			</div>
			
			<div data-linea="2">
					<label>Grano violeta: </label><?php echo  $resultadoInspeccionAgencia['grano_violeta'];?> (%)
			</div>
			
			
			<div data-linea="2">
					<label>Grano pizarroso: </label><?php echo  $resultadoInspeccionAgencia['grano_pizarroso'];?> (%)
			</div>
			
			<div data-linea="3">
					<label>Mohos: </label><?php echo  $resultadoInspeccionAgencia['mohos'];?> (%)
			</div>
			
			<div data-linea="3">
					<label>Dañados por insectos: </label><?php echo  $resultadoInspeccionAgencia['danios_insectos'];?> (%)
			</div>
			
			<div data-linea="4">
					<label>Vulnerado: </label><?php echo  $resultadoInspeccionAgencia['vulnerado'];?> (%)
			</div>
			
			<div data-linea="4">
					<label>TOTAL: </label><?php echo  $resultadoInspeccionAgencia['total'];?> (%)
			</div>
			
		</fieldset>
		
		<fieldset>
			<legend>Defectos</legend>
			
			<div data-linea="1">
					<label>Multiples: </label><?php echo  $resultadoInspeccionAgencia['defecto_multiple'];?> (%)
			</div>
			<div data-linea="1">
					<label>Partidos: </label><?php echo  $resultadoInspeccionAgencia['defecto_partido'];?> (%)
			</div>
			<div data-linea="1">
					<label>Plano - granza: </label><?php echo  $resultadoInspeccionAgencia['defecto_plano_granza'];?> (%)
			</div>
			
		</fieldset>
		
		<fieldset>
			<legend>Datos adicionales</legend>
			
			<div data-linea="1">
					<label>Impurezas de cacao: </label><?php echo  $resultadoInspeccionAgencia['impureza_cacao'];?> (%)
			</div>
			<div data-linea="1">
					<label>Materia extraña: </label><?php echo  $resultadoInspeccionAgencia['materia_extrania'];?> (%)
			</div>
			<div data-linea="2">
					<label>Contenido de cacao tipo trinitario (CCN-51): </label><?php echo  $resultadoInspeccionAgencia['tipo_trinitario'];?>
			</div>
			
			<div data-linea="3">
					<label>Peso de cacao de 100 pepas: </label><?php echo  $resultadoInspeccionAgencia['peso_cacao'];?>
			</div>
			
			<div data-linea="3">
					<label># Pepas en 100 gramos: </label><?php echo  $resultadoInspeccionAgencia['numero_pepas'];?>
			</div>
			
			<div data-linea="5">
					<label>Humedad: </label><?php echo  $resultadoInspeccionAgencia['humedad'];?>
			</div>
			
			<div data-linea="6">	
				<label>Observaciones: </label><?php echo  $resultadoInspeccionAgencia['observacion'];?>
			</div>	
			
		</fieldset>

</div>

<div class="pestania">	
	<form id="evaluarResultadoInspeccion" data-rutaAplicacion="certificadoCalidad" data-opcion="guardarInspeccionResponsable" data-destino="detalleItem">
		<input type="hidden" name="inspector" value="<?php echo $identificadorUsuario;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		
		<fieldset>
			<legend>Resultado de inspección</legend>
			<div data-linea="1">
				<label>Estado</label>
				<select id="estadoFinal" name="estadoFinal">
					<option value="">Seleccione....</option>
					<option value="Aprobado">Aprobado</option>
					<option value="Anulado">Anulado</option>
					<option value="Fuera de norma">Fuera de norma</option>			
				</select>
			</div>
				
			<label>Observaciones</label>
			<div data-linea="2">	
				<textarea id="observacion" name="observacion" rows="5" ></textarea>
			</div>	

		</fieldset>
		
		<button type="submit" class="guardar">Enviar resultado</button>
		
	</form> 
	
	
</div>    

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));	
		  
	});

	
	$("#evaluarResultadoInspeccion").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if(!$.trim($("#estadoFinal").val())){
			error = true;
			$("#estadoFinal").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			abrir($(this),event,false);
		}

	});
	
</script>
