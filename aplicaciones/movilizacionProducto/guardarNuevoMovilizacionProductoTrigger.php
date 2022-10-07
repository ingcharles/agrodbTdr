<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';

try{
	$conexion = new Conexion();
	$cmp = new ControladorMovilizacionProductos();
	set_time_limit(3000);
	
	try{
		
		$msj='';
		for ($i=1; $i<=30; $i++){
			
			/*$entrada = array("01","23","24");
			$clavesAleatoriasOrigen = array_rand($entrada,(count($entrada) - 1));
			$codigoProvinciaOrigen=$entrada[$clavesAleatoriasOrigen[0]] ;
			$clavesAleatoriasDestino = array_rand($entrada,(count($entrada) - 1));
			$codigoProvinciaDestino=$entrada[$clavesAleatoriasDestino[0]] ;*/
			$codigoProvinciaOrigen='01';
			$codigoProvinciaDestino='01';
			
			$conexion->ejecutarConsulta("BEGIN; LOCK TABLE g_movilizacion_producto.movilizacion IN SHARE ROW EXCLUSIVE MODE;");
			//$conexion->ejecutarConsulta("BEGIN; LOCK TABLE g_movilizacion_producto.movilizacion IN EXCLUSIVE MODE;");
			//$conexion->ejecutarConsulta("BEGIN; LOCK TABLE g_movilizacion_producto.movilizacion IN ACCESS EXCLUSIVE MODE;");
			
			//$conexion->ejecutarConsulta("BEGIN; LOCK TABLE g_movilizacion_producto.movilizacion IN ROW EXCLUSIVE MODE;");
			
			//$conexion->ejecutarConsulta("BEGIN;");
			$secuencialAutogenerado=$cmp->autogenerarNumerosCertificadosMovilizacion($conexion,$codigoProvinciaOrigen,$codigoProvinciaDestino);
			//$conexion->ejecutarConsulta("commit;");
			$secuencialCertificado = str_pad($secuencialAutogenerado, 5, "0", STR_PAD_LEFT);
			$numeroCertificado= $codigoProvinciaOrigen.$codigoProvinciaDestino.$secuencialCertificado.date('dmy');
			
			$fechaRegistroMovilizacion = date('Y-m-d H:i:s');
			
	
			//$numeroCertificado='null';
			//$secuencialCertificado=null;
			$idMovilizacion = $cmp->guardarMovilizacion($conexion,$numeroCertificado,'Pichincha','Oficina Machachi', '6796', '219641', 'TRF-4854', '14646563563', '1722551049',  'vigente', 'aplicaciones/movilizacionProducto/documentos/guias/2017/11/29/Zoosanitaria_232300002291117_29-11-2017_13.54.43.pdf',
					'2017-11-29 12:12:00-05', '2017-11-30 02:12:00-05', $codigoProvinciaOrigen, $codigoProvinciaDestino,$secuencialCertificado,'MAURO LEDESMA', '3','CERTIFICADO SANITARIO PARA LA MOVILIZACIÓN TERRESTRE DE ANIMALES, PRODUCTOS Y SUBPRODUCTOS DE ORIGEN ANIMAL (CSMI)','CAMIONETA COLOR DORADO ','null','1790319857001','100','No fiscalizado',$fechaRegistroMovilizacion) ;
			$idMovilizacion=pg_fetch_result($idMovilizacion, 0, 'id_movilizacion');
			$numeroCertificado=pg_fetch_result($cmp->obtenerNumeroCertificado($conexion,$idMovilizacion), 0, 'numero_certificado');
			$msj.="-->PROCESO # ".$i."  --->  ID MOVILIZACION = ".$idMovilizacion."  --->  NUMERO_CERTIFICADO = ".$numeroCertificado.'<br>';
			//$conexion->ejecutarConsulta("commit;");
			//$conexion->ejecutarConsulta("BEGIN;");
			for ($j=1; $j<=3; $j++){
				$msj.='DETALLE # '.$j.'<br>';
			$idDetalleMovilizacion=$cmp->guardarDetalleMovilizacion($conexion,$idMovilizacion, '8106', '260273', '24354','100', 'D', '34', '26','26');
			/*for ($k=1; $k<=200; $k++){
			}*/
			}
			
				$conexion->ejecutarConsulta("commit;");
			
			$msj.='<br><br>';
		}
	
	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
	
		$err = preg_replace( "/\r|\n/", " ", $conexion->mensajeError);
		$conexion->ejecutarLogsTryCatch($ex.'---ERROR:'.$err);
	} finally {
		$conexion->desconectar();
	}

} catch (Exception $ex) {

	$err = preg_replace( "/\r|\n/", " ", $conexion->mensajeError);
	$conexion->ejecutarLogsTryCatch($ex.'---ERROR:'.$err);
} finally {
	echo $msj;
}
?>