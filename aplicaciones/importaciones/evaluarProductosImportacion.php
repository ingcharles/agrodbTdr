<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorImportaciones.php';
require_once 'crearReporteRequisitosImportacion.php';

try{
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
	$idImportacion = ($_POST['idSolicitud']);
	$listaProductos = ($_POST['listaProductos']);
	$archivo = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
	$resultadoImportacion = htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8');
	$observaciones = htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	$productosRevisados=true;
	$pago = 0;
	$rechazado = 0;
		
	try {
		$conexion = new Conexion();
		$ci = new ControladorImportaciones();
		
		//Verifica si la operación está asignada a un inspector, caso contrario se asigna a la persona que realiza la inspección
		$inspectorAsignado = $ci->listarInspectoresAsignados($conexion, $idImportacion);
		
		if(pg_num_rows($inspectorAsignado)==0){
			$res= $ci->guardarNuevoInspector($conexion, $idImportacion, $inspector, $inspector);
			$res= $ci->enviarImportacion($conexion, $idImportacion, 'asignado');
		}
		
		if(count($listaProductos)>0){
			for ($i=0; $i<count($listaProductos);$i++){
				//Guarda estado de producto
				$ci->evaluarProductosImportacion($conexion, $idImportacion, $listaProductos[$i], $resultadoImportacion, $observaciones, $archivo);
				
				//Guarda inspector, calificación y fecha
				$ci->guardarDatosInspeccion($conexion, $idImportacion, $listaProductos[$i], $inspector, $archivo, $resultadoImportacion, $observaciones);
			}
			
			//Consulta el estado de los productos evaluados
			$productos = $ci->abrirProductosImportacion($conexion, $idImportacion);
			
			foreach ($productos as $producto){
				if($producto['estado']=='aprobado'){
					$pago ++;
				}else if($producto['estado']=='rechazado'){
					$rechazado ++;
				}else{
					$productosRevisados = false;
					break;
				}
			}
			
			if($productosRevisados){
				if($pago>$rechazado){
					//CREACION DEL PDF CON REQUISITOS POR PRODUCTO
					$pdf = new PDF();
					$pdf->AliasNbPages();
					$pdf->AddPage();
					$pdf->Body($idImportacion);
					$pdf->SetFont('Times','',12);
					$pdf->Output("archivosRequisitos/".$identificadorOperador."-".$idImportacion.".pdf");
					
					$informeRequisitos = "aplicaciones/importaciones/archivosRequisitos/".$identificadorOperador."-".$idImportacion.".pdf";
					
					//Actualizar registro
					$ci->evaluarImportacion($conexion, $idImportacion, 'pago', $observaciones, $informeRequisitos);
				}else{
					$ci->evaluarImportacion($conexion, $idImportacion, 'rechazado', $observaciones);
				}
			}
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';

		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Debe seleccionar por lo menos un producto.';
		}
		
		$conexion->desconectar();
		echo json_encode($mensaje);
		
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>