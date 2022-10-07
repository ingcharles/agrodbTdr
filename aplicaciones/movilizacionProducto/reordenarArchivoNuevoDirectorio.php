<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$mensaje['rutaCertificado']='';


$conexion = new Conexion();
$cmp = new ControladorMovilizacionProductos();

set_time_limit(2000);


$datosMovilizacion=$cmp->consultarCertificadosReimpresion($conexion);
$contador=1;
while($fila=pg_fetch_assoc($datosMovilizacion)){
	echo '<b>PROCESO # '.$contador.'</b><br>';

	$rutasGuias='aplicaciones/movilizacionProducto/documentos/guias/'.$fila['fecha'].'/';
	$rutasTickets='aplicaciones/movilizacionProducto/documentos/ticket/'.$fila['fecha'].'/';
	//define('RUTA_GUIAS',);
	//define('RUTA_TICKETS',);

	$archivoGuias=str_replace('aplicaciones/movilizacionProducto/', '', $rutasGuias);
	$archivoTicket=str_replace('aplicaciones/movilizacionProducto/', '', $rutasTickets);

	if(!file_exists($archivoGuias)){
		mkdir($archivoGuias, 0777,true);
	}

	$rutaAntigua=dirname(__FILE__).'/documentos/guias/'.$fila['nombre_archivo'];
	if(is_file($rutaAntigua)){
		echo 'ID MOVILIZACION ---> '.$fila['id_movilizacion'].'<br>';
		$rutaNueva=dirname(__FILE__).'/'.$archivoGuias.$fila['nombre_archivo'];
		rename ($rutaAntigua, $rutaNueva);
		$cmp->actualizarRutaArchivoMovilizacion($conexion, $fila['id_movilizacion'], $rutasGuias.$fila['nombre_archivo']);
		echo 'Actualizada la ruta del certificado<br>';

		if($fila['ruta_ticket']!=''){
			if (!file_exists($archivoTicket))
				mkdir($archivoTicket,0777,true);
				
			$rutaAntiguaTicket=dirname(__FILE__).'/documentos/ticket/'.$fila['nombre_ticket'];
			if(is_file($rutaAntiguaTicket)){
				$rutaNuevaTicket=dirname(__FILE__).'/'.$archivoTicket.$fila['nombre_ticket'];
				rename($rutaAntiguaTicket, $rutaNuevaTicket);
				$cmp->actualizarRutaArchivoTicketMovilizacion($conexion, $fila['id_movilizacion'], $rutasTickets.$fila['nombre_ticket']);
				echo 'Actualizada la ruta del ticket';
			}
		}
	}else{
		'NO EXISTE EL ARCHIVO';
	}
	echo '<br><br>';

	$contador++;
}
?>



