<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificadoCalidad.php';

$conexion = new Conexion();
$cca = new ControladorCertificadoCalidad();
$cc = new ControladorCatalogos();

$idSolicitud = $_POST['id'];
$identificadorUsuario = $_SESSION['usuario'];

$certificadoCalidad = pg_fetch_assoc($cca->obtenerSolicitudCertificadoCalidad($conexion, $idSolicitud));

?>

<header>
	<h1>Solicitud certificado de calidad</h1>
</header>

	<div id="estado"></div>
	
	
	<fieldset>
			<legend>Datos del exportador</legend>
			
			<div data-linea="1">
				<label>Identificación: </label> <?php echo $certificadoCalidad['identificador_exportador']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Razón social: </label> <?php echo $certificadoCalidad['razon_social_exportador']; ?> 
			</div>
			
	</fieldset>
	
	<fieldset>
			<legend>Datos del importador</legend>
			
			<div data-linea="1">
				<label>Nombre: </label> <?php echo $certificadoCalidad['nombre_importador']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Dirección: </label> <?php echo $certificadoCalidad['direccion_importador']; ?> 
			</div>
			
	</fieldset>
	
	<fieldset>
		<legend>Datos generales de exportación</legend>
			<div data-linea="1">
				<label>Fecha de embarque: </label> <?php echo date('j/n/Y',strtotime($certificadoCalidad['fecha_embarque']));?> 
			</div>
			
			<div data-linea="1">
				<label>Número de viaje: </label> <?php echo $certificadoCalidad['numero_viaje']; ?> 
			</div>
			
			<div data-linea="2">
				<label>País de embarque: </label> <?php echo $certificadoCalidad['nombre_pais_embarque']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Puerto embarque: </label> <?php echo $certificadoCalidad['nombre_puerto_embarque']; ?> 
			</div>
			
			<div data-linea="3">
				<label>Medio de transporte: </label> <?php echo $certificadoCalidad['nombre_medio_transporte']; ?> 
			</div>
			
			<div data-linea="4">
				<label>País de destino: </label> <?php echo $certificadoCalidad['nombre_pais_destino']; ?> 
			</div>
			
			<div data-linea="4">
				<label>Puerto de destino: </label> <?php echo $certificadoCalidad['nombre_puerto_destino']; ?> 
			</div>
	</fieldset>
	
	<?php 
	
		$lugarCertificadoCalidad = $cca->obtenerLugarCertificadoCalidad($conexion, $idSolicitud);
	
		while ($lugarCertificado = pg_fetch_assoc($lugarCertificadoCalidad)){
			
			echo '
				<fieldset>
					<legend>Lugar de inspección '.$lugarCertificado['nombre_area_operacion'].'</legend>
		
					<div data-linea="4">
						<label>Nombre provincia: </label> '. $lugarCertificado['nombre_provincia'].'
					</div>
			
					<div data-linea="4">
						<label>Fecha y hora de inspección: </label> '.  date('j/n/Y h:i',strtotime($lugarCertificado['solicitud_fecha_inspeccion'])).'
					</div>
		
				<hr/>';
			
				$loteCertificadoInspeccion = $cca->obtenerLoteCertificadoCalidadIndividual($conexion, $lugarCertificado['id_lugar_inspeccion']);
				
				$cantidadRegistros = pg_num_rows($loteCertificadoInspeccion);
				
				$aux = 0;
				
				$i = 20;
				while($loteCertificado = pg_fetch_assoc($loteCertificadoInspeccion)){
				
				$aux++;
				echo '
					<div data-linea='.++$i.'>
						<label>Número lote: </label> '. $loteCertificado['numero_lote'].'
					</div>
					<div data-linea='.++$i.'>
						<label>Nombre producto: </label> '. $loteCertificado['nombre_producto'].'
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
						<label>Calidad: </label> '. ($loteCertificado['nombre_calidad_producto']=='Calidad...'?'Calidad no disponible':$loteCertificado['nombre_calidad_producto']).'
					</div>';
				
				if($cantidadRegistros != $aux){
					echo '<hr/>';
				}
					
					
				}
			
			echo '</fieldset>';
		}	
	
	?>


<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
	});
	
</script>

