<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();


$idProcesoConciliacion = $_POST['id'];


$qProcesoConciliacion = $cb->abrirProcesoConciliacionXidProcesoConciliacion($conexion, $idProcesoConciliacion);
$procesoConciliacion = pg_fetch_assoc($qProcesoConciliacion);

$qBancosConciliacion = $cb->abrirTotalesBancosXidProcesoConciliacion($conexion, $idProcesoConciliacion);

$qResultadoConciliacion = $cb->abrirResultadoProcesoConciliacionXidProcesoConciliacion($conexion, $idProcesoConciliacion);
$resultadoConciliacion = pg_fetch_assoc($qResultadoConciliacion);

?>

<div id="estado"></div>

	<header>
		<h1>Análisis de Proceso de Conciliación</h1>
	</header>
		<form id="abrirProcesoConciliacion" data-rutaAplicacion="conciliacionBancaria" data-destino="detalleItem">
		<input type="hidden" id="idDocumento" name="idDocumento" value="<?php echo $idProcesoConciliacion?>" />
			<fieldset id="informacionProceso">
			<legend>Información Conciliación </legend>
				<div data-linea="1">
					<label>Proceso conciliación: </label><?php echo $procesoConciliacion['nombre_registro_proceso_conciliacion'];?>
				</div>
				<div data-linea="2">
					<label>Año: </label><?php echo $procesoConciliacion['anio_proceso_conciliacion'];?>
				</div>
				<div data-linea="2">
					<label>Mes: </label><?php echo $procesoConciliacion['mes_proceso_conciliacion'];?>
				</div>
				<div data-linea="2">
					<label>Día: </label><?php echo $procesoConciliacion['dia_proceso_concilicacion'];?>
				</div>				
				<div data-linea="3">
					<label>Total recaudado: </label><?php echo $procesoConciliacion['total_recaudado'];?>
				</div>
				
				<?php 
				$contador = 3;
					while($fila = pg_fetch_assoc($qBancosConciliacion)){
						$contador++;
						echo '<div data-linea='.$contador.'><label>Total '.$fila['nombre'].': </label>'.$fila['total_banco_proceso_conciliacion'].'<div>';
					}
			
				$qDocumentosProceso = $cb->obtenerDocumentosRutasProcesoConciliacionXIdRegistroProcesoConciliacion($conexion, $idProcesoConciliacion);
											
				while($documentosConciliacion = pg_fetch_assoc($qDocumentosProceso)){
				
					if($documentosConciliacion['tipo_documento_proceso_conciliacion']=="trama"){?>						
						<div data-linea="<?php echo $contador++; ?>">
						<label>Trama Senae-Banred: </label><a href="<?php echo $documentosConciliacion['ruta_documento_proceso_conciliacion'];?>" target="_blank">Descargar archivo tramas</a>
						</div>
				
				<?php }
					
				}
				
				?>
				
			</fieldset>
			
			<fieldset id="informacionProceso">
			<legend>Resultado de Conciliación </legend>
				<div data-linea="1">
				
				<?php 
				
				$valores = explode(",",$resultadoConciliacion['resultado']);
				
				
				foreach ($valores as $valor){
					
					echo '<label>'.$valor.'</label><br>';
				}
				
				?>

				
				</div>	
			</fieldset>
			
			<fieldset id="informacionProceso">
			<legend>Reportes de Conciliación </legend>
				<div data-linea="1">
					<label>Descargar resultado proceso conciliación: </label><a href="<?php echo 'aplicaciones/conciliacionBancaria/'.$resultadoConciliacion['ruta_archivo_conciliacion'];?>" target="_blank">Proceso conciliación</a>					 
				</div>								
			</fieldset>
		</form>

<script type="text/javascript">					

 $(document).ready(function(){	
    	distribuirLineas();	 
 });

 </script>   	