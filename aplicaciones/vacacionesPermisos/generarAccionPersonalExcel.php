<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';

require_once 'libphp/PHPExcel.php';
require_once 'libphp/PHPExcel/IOFactory.php';

$conexion = new Conexion();
$cc = new ControladorVacaciones();

try {
	
	$id_registro=$_POST['id_registro'];
    
    $filaSolicitud = pg_fetch_assoc($cc->obtenerDatosPermiso($conexion,$id_registro));
    
	$identificadorTH=$_POST['identificadorTH'];
		
	$find = array('/[\-\:\ ]+/', '/&lt;{^&gt;*&gt;/');
	
	$idDocumento="AcccionPersonal".preg_replace($find, '', date('Y-m-d h:i:sa'));
	$rutaDocumento="aplicaciones/vacacionesPermisos/generados/".$idDocumento.".xls";
	
	$filaDirector = pg_fetch_assoc($cc->obtenerNombreDirector($conexion,$identificadorTH));
	
	$cc->actualizarEstadoPermiso($conexion, $id_registro, 'InformeGenerado');
	$cc->actualizarRutaDocumento($conexion, $id_registro, $rutaDocumento);
	
	//Registro de observaciones del proceso
	$cc->agregarObservacion($conexion, 'El usuario '.$_SESSION['usuario'].' ha creado la acción de personal para la solicitud de '.$filaSolicitud['descripcion_subtipo'].' con fecha de salida '
			.$filaSolicitud['fecha_inicio'].', fecha de retorno '.$filaSolicitud['fecha_fin'].' y con '.$filaSolicitud['minutos_utilizados'].' minutos solicitados', $id_registro, $_SESSION['usuario']);
	
	
	$phpExcel = new PHPExcel($rutaDocumento);
	
	$objPHPExcel = PHPExcel_IOFactory::load("excel_templates/accionPersonal.xls");
    $objPHPExcel->setActiveSheetIndex(0);
	
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A14', $filaSolicitud['identificador'])
            ->setCellValue('A10', strtoupper($filaSolicitud['nombre']))
            ->setCellValue('F10', strtoupper($filaSolicitud['apellido']))
            ->setCellValue('A16', $filaSolicitud['descripcion_subtipo']. ' Fecha desde:'.$filaSolicitud['fecha_inicio']. ' Fecha fin:'.$filaSolicitud['fecha_fin'])
            ->setCellValue('C26', $filaSolicitud['direccion'])
            ->setCellValue('C27', $filaSolicitud['coordinacion'])
             ->setCellValue('C28',$filaSolicitud['nombre_puesto'])
             ->setCellValue('D29',$filaSolicitud['oficina'])
             ->setCellValue('D30',$filaSolicitud['remuneracion'])
             ->setCellValue('D31',$filaSolicitud['tipo_contrato'])
             ->setCellValue('B41','Nombre: '.$filaDirector['directorath'])
             ->setCellValue('B42',$filaDirector['puestoth'] );
             
		
	$objPHPExcel->setActiveSheetIndex(0); 

// Redirect output to a client’s web browser (Excel2007) 
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
    header('Content-Disposition: attachment;filename="01simple.xlsx"'); 
    header('Cache-Control: max-age=0'); 

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save("generados/".$idDocumento.".xls");
    unset($sheet2);
	unset($sheet1); 
       
	echo '<div data-linea="5">
			<label>Archivo de accion de personal </label>';
	echo $rutaDocumento==''? '<span class="alerta">No se ha generado ningún informe</span>':'<a href='.$rutaDocumento.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a></div>';


} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();			
	});

	
		 
</script>