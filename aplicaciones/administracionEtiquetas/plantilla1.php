<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../general/fpdf.php';
require_once '../general/phpqrcode/qrlib.php';
require_once '../../clases/Constantes.php';

$conexion = new Conexion();
$cl = new ControladorLotes();
$cc = new ControladorCatalogos();

define('RUTA_MODULO', 'aplicaciones/conformacionLotes/');
define('RUTA_PNG_TEMP',RUTA_SERVIDOR_OPT.'/'.RUTA_APLICACION.'/'.RUTA_MODULO.'/temp/');


$nrLote = $_POST['numeroLote'];
$codigoLote = $_POST['loteCodigo'];
$fechaEtiquetado = $_POST['fechaEtiqueta'];
$cantidad = $_POST['cantidad'];
$peso = $_POST['peso'];
$nroEtiqueta = $_POST['nroEtiqueta'];
$tipo = $_POST['tipo'];
$tipox=htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
$pais = $_POST['pais'];
$tipoProducto = $_POST['tipoProducto'];
$producto = $_POST['producto'];
$exportador = $_POST['exportador'];
$variedad = $_POST['variedad'];
$opcion= $_POST['opcion'];
$idLote= $_POST['idLote'];
$operador= $_POST['usuario'];
$nOperador= htmlspecialchars ($_POST['exportador'],ENT_NOQUOTES,'UTF-8');
$nombreOperador= $_POST['exportador'];
$idProducto = $_POST['idProducto'];
$tamanioEtiqueta = $_POST['tamanioEtiqueta'];
$lote=$codigoLote.'  -  '.$nrLote;
switch ($opcion){
	case 'guardarEtiqueta':		
		try {
			
			$rutaArchivo="etiquetas/".$operador."-".$nrLote.".pdf";
			$idEtiqueta=password_hash($operador."-".$Lote,PASSWORD_DEFAULT);

			$resultadoIdEtiquetado=$cl->guardarLoteEtiquetado($conexion,$idLote,$nrLote,$codigoLote,$fechaEtiquetado,$cantidad,$peso,$nroEtiqueta,$operador,RUTA_MODULO.$rutaArchivo,$idEtiqueta);
			$cl->estadoLote($conexion,$idLote);
			
			$idEtiquetado= pg_fetch_row($resultadoIdEtiquetado);

			if (!file_exists(RUTA_PNG_TEMP))
			mkdir(RUTA_PNG_TEMP, 0777,true);

			function eliminarTemporales($dir) {
				$files = array_diff(scandir($dir), array('.','..'));
				foreach ($files as $file) {
					(is_dir("$dir/$file")) ? eliminarTemporales("$dir/$file") : unlink("$dir/$file");
				}
				//ELIMINAR DIRECTORIO return rmdir($dir);
			}
			
			$res=$cc->ObtenerProductoPorId($conexion,$idProducto);
			$nombreProducto= pg_fetch_assoc($res);
			
			$string=$nombreProducto['nombre_comun'];
			
			if(stristr($string, 'cacao') === FALSE) {
								
				//$pdf = new FPDF('P','mm',array(210,297));
				
				
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 4;				
				
				$res=$cl->areasXlote($conexion,$idLote);
				$areas= pg_fetch_assoc($res);
				
				$resul=$cl->proveedoresXlote($conexion, $idLote);
				$proveedores= pg_fetch_assoc($resul);
				
				$datosOperador= $operador.'.'.$areas['area_operador'].'-'.$nombreOperador;
				$datosProveedor= $proveedores['identificador_proveedor'].'.'.$areas['area_proveedor'].'-'.$proveedores['nombre_proveedor'];
				
				//INICIO GENERACION QR
				
				//echo password_hash("rasmuslerdorf", PASSWORD_DEFAULT)."\n";
				
				$infoQr='Lote Envío:'.$nrLote."\n".
						'Acopiador:'.$datosOperador."\n".
						'Proveedor: '.$datosProveedor."\n".
						'Producto:'.$producto ."\n".				
						'Lote Producción:'.$codigoLote."\n".
						'Fecha de Empaque:'.$fechaEtiquetado."\n".
						'Destino:'.$pais ."\n".
						'Identificación Etiqueta:'.$idEtiqueta
						;
						
						
						$rutaImagenQr='temp/'.$nrLote.$i.'png';
						$filename = RUTA_PNG_TEMP.$nrLote.$i.'png';
						QRcode::png($infoQr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
						//$pdf->Image($rutaImagenQr , $pdf->SetY(10.5) ,$pdf->SetX(0.7), 29, 29,'PNG');
						
						//FIN GENERACION QR				
						
							
							$pdf = new FPDF('L','mm',array(100,50));
							$pdf->SetAutoPageBreak(true,0);
							$pdf->SetFont('Arial','',8);
							$fila=0;	
							$nCajas=1;
							
							for($i=0;$i<=$nroEtiqueta-1;$i++){
								
								
						
								
								$pdf->AddPage();								
								$pdf->Image('img/EtiquetaTrazabilidadPitahaya.png' , $pdf->SetY($fila) ,$pdf->SetX(0), 100 , 50,'PNG');
								$pdf->Image($rutaImagenQr , $pdf->SetY(10.5) ,$pdf->SetX(0.7), 29, 29,'PNG');
								
								$pdf->SetFont('Arial','',7);
								$fila+=12;
								$pdf->SetXY(77,$fila);
								$pdf->MultiCell(80,3,$nrLote,0,'L',0 );
								
								$fila+=5;
								$pdf->SetXY(31,$fila);
								//$pdf->MultiCell(80,2,$operador.'.'.$areas['area_operador'].'-'.substr(utf8_decode($nombreOperador),0,27),0,'L',0 );
								$pdf->MultiCell(70,2.8,substr(utf8_decode($datosOperador),0,110),0,'L',0 );
								
								$fila+=9;
								$pdf->SetXY(31,$fila);
								//$pdf->MultiCell(80,3,$proveedores['identificador_proveedor'].'.'.$areas['area_proveedor'].'-'.substr(utf8_decode($proveedores['nombre_proveedor']),0,27),0,'L',0 );
								$pdf->MultiCell(70,2.8,substr(utf8_decode($datosProveedor),0,110),0,'L',0 );
								
								
								$fila+=9;
								$pdf->SetXY(31,$fila);
								$pdf->MultiCell(30,2,substr(utf8_decode($producto),0,21),0,'L',0 );
								
								$pdf->SetXY(62,$fila);
								$pdf->MultiCell(100,2,$fechaEtiquetado,0,'L',0 );
								
								$fila+=3;
								$pdf->SetXY(43,$fila);
								$pdf->MultiCell(55,2.7,substr(utf8_decode($pais),0,70),0,'L',0 );
								
								$fila+=7;
								$pdf->SetXY(57,$fila);
								$pdf->MultiCell(40,1,utf8_decode($codigoLote),0,'L',0 );
								
								$fila+=2;
								$pdf->SetXY(73,$fila);
								
								$pdf->MultiCell(50,2,$nCajas.'-'.$nroEtiqueta,0,'L',0 );
								
								$fila=0;
								$nCajas+=1;
							}
						
						
						$pdf->Output($rutaArchivo,'F');
				
			} else{

				$pdf = new FPDF('L','mm',array(100,50));			
				$pdf->SetAutoPageBreak(true,0);
				$pdf->SetFont('Arial','',8);
				$fila=0;				
	
				$errorCorrectionLevel = 'L';
				$matrixPointSize = 4;
				
				//INICIO GENERACION QR
				
				//echo password_hash("rasmuslerdorf", PASSWORD_DEFAULT)."\n";
				
				$infoQr='Exportador:'.$operador .'-'. $nOperador ."\n".
						'Producto:'.$producto .'-'. $tipoProducto .' - '. $variedad .' - '. $tipox ."\n".
						'Peso:'.$peso .'kg.'. "\n".
						'Destino:'.$pais ."\n".
						'Lote:'.$lote ."\n".
						'Identificación Etiqueta:'.$idEtiqueta
						;
				
				
				$rutaImagenQr='temp/'.$nrLote.$i.'png';
				$filename = RUTA_PNG_TEMP.$nrLote.$i.'png';
				QRcode::png($infoQr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
				//$pdf->Image($rutaImagenQr , $pdf->SetY(10.5) ,$pdf->SetX(0.7), 29, 29,'PNG');
	
				//FIN GENERACION QR
	
				for($i=0;$i<=$nroEtiqueta-1;$i++){
					$pdf->AddPage();
					$pdf->Image('img/EtiquetaTrazabilidad.png' , $pdf->SetY($fila) ,$pdf->SetX(0), 100 , 50,'PNG');
					
					$pdf->Image($rutaImagenQr , $pdf->SetY(10.5) ,$pdf->SetX(0.7), 29, 29,'PNG');
					
					$fila+=18;
					$pdf->SetXY(31,$fila);
					$pdf->MultiCell(80,3,$operador.' - '.substr(utf8_decode($nombreOperador),0,26),0,'L',0 );
	
					$fila+=9;
					$pdf->SetXY(31,$fila);
					$pdf->MultiCell(100,2,utf8_decode($producto).' - '.utf8_decode($tipoProducto),0,'L',0 );
	
					$fila+=5;
					$pdf->SetXY(31,$fila);
					//$pdf->MultiCell(100,2,$variedad.' - '. $tipo,0,'L',0 );
					$pdf->MultiCell(100,2,$variedad.' - '.substr(utf8_decode($tipo),0,39), 0,'L',0 );
	
					$fila+=5;
					$pdf->SetXY(39,$fila);
					$pdf->MultiCell(30,2,$peso."kg",0,'L',0 );
	
					$pdf->SetXY(63,$fila);
					$pdf->MultiCell(38,3,substr(utf8_decode($pais),0,50),0,'L',0 );
	
					$fila+=8;
					$pdf->SetXY(31,$fila);
					$pdf->MultiCell(100,3,utf8_decode($lote),0,'L',0 );
	
					$fila=0;
				}
				
				$pdf->Output($rutaArchivo,'F');
			
			}
			
			//$pdf->AutoPrint();
			

			echo '<embed id="visor" src="'.RUTA_MODULO.$rutaArchivo.'" width="550" height="620">';

			eliminarTemporales('temp');

		} catch(Exception $e) {
			echo $e->getMessage();
			echo "<script>console.log('" . preg_replace( "/\r|\n/", " ", $conexion->mensajeError) . "'); </script>";
		}


		break;

}

?>

<script type="text/javascript">
    $('document').ready(function() {        
    	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);        
    });
</script>