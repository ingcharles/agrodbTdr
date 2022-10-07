<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEtiquetas.php';
require_once '../../clases/Constantes.php';
require_once '../general/phpqrcode/qrlib.php';
require_once '../general/fpdf.php';

set_time_limit(5000);
$conexion = new Conexion();
$ce = new ControladorEtiquetas();
$constg = new Constantes();

define('RUTA_MODULO', 'aplicaciones/etiquetas');
define('RUTA_PNG_TEMP',$constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.RUTA_MODULO.'/temp/');

$idSolicitudEtiqueta=htmlspecialchars ($_POST['idSolicitudEtiqueta'],ENT_NOQUOTES,'UTF-8');
//$cantidadEtiquetas=htmlspecialchars ($_POST['saldoEtiqueta'],ENT_NOQUOTES,'UTF-8');
$cantidadEtiquetasImprimir=htmlspecialchars ($_POST['numeroEtiquetasImprimir'],ENT_NOQUOTES,'UTF-8');
$idEtiquetaSitio=htmlspecialchars ($_POST['idEtiquetaSitio'],ENT_NOQUOTES,'UTF-8');
//$cantidadEtiquetasSitio=htmlspecialchars ($_POST['saldoEtiquetasSitio'],ENT_NOQUOTES,'UTF-8');
//$saldoEtiquetas=$cantidadEtiquetas-$cantidadEtiquetasImprimir;
//$saldoEtiquetasSitio=$cantidadEtiquetasSitio-$cantidadEtiquetasImprimir;

if (!file_exists(RUTA_PNG_TEMP))
	mkdir(RUTA_PNG_TEMP, 0777,true);

function eliminarTemporales($dir) {
	$files = array_diff(scandir($dir), array('.','..'));
	foreach ($files as $file) {
		(is_dir("$dir/$file")) ? eliminarTemporales("$dir/$file") : unlink("$dir/$file");
	}
	//ELIMINAR DIRECTORIO return rmdir($dir);
}

try {

	$conexion->ejecutarConsulta("begin;");
	
	$qSaldoEtiquetaBDD=$ce->abrirSolicitudEtiquetasEnviada($conexion, $idSolicitudEtiqueta);
	$saldoEtiquetas=$qSaldoEtiquetaBDD[0]['cantidadEtiqueta']-$cantidadEtiquetasImprimir;
	
	$qSaldoEtiquetaSitioBDD=$ce->obtenerSolicitudesEtiquetasXEtiquetaSitio($conexion, $idSolicitudEtiqueta, $idEtiquetaSitio);
	$saldoEtiquetaSitioBDD=pg_fetch_result($qSaldoEtiquetaSitioBDD, 0, 'saldo_etiqueta_sitio');
	$saldoEtiquetasSitio=$saldoEtiquetaSitioBDD-$cantidadEtiquetasImprimir;

	$qIdEtiquetaDetalle=$ce->guardarEtiquetasDetalle($conexion, $idSolicitudEtiqueta, $cantidadEtiquetasImprimir,'activo',$idEtiquetaSitio);
	$idEtiquetaDetalle=pg_fetch_result($qIdEtiquetaDetalle, 0, 'id_etiqueta_detalle');
	
	$ce->actualizarDatosSolicitudEtiqueta($conexion, 'saldo_etiqueta', $saldoEtiquetas, $idSolicitudEtiqueta);
	$ce->actualizarSaldoEtiquetaSitio($conexion, 'saldo_etiqueta', $saldoEtiquetasSitio, $idEtiquetaSitio);
	for ($j=0;$j<$cantidadEtiquetasImprimir;$j++){
		$secuencial=$ce->autogenerarNumeroEtiquetasOrnamentales($conexion);
		$numeroEtiqueta=str_pad($secuencial, 10, "0", STR_PAD_LEFT);
		$idEtiquetaImpresa=$ce->guardarEtiquetasImpresas($conexion, $idEtiquetaDetalle, $numeroEtiqueta);
	}
	
	$qEtiquetasImprimir=$ce->obtenerEtiquetaImprimir($conexion, $idEtiquetaDetalle);

	
	$errorCorrectionLevel = 'L';
	$matrixPointSize = 4;

	class PDF_Javascript extends FPDF {
		var $javascript;
		var $n_js;
		function IncludeJS($script) {
			$this->javascript=$script;
		}
		function _putjavascript() {
			$this->_newobj();
			$this->n_js=$this->n;
			$this->_out('<<');
			$this->_out('/Names [(EmbeddedJS) '.($this->n+1).' 0 R ]');
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<<');
			$this->_out('/S /JavaScript');
			$this->_out('/JS '.$this->_textstring($this->javascript));
			$this->_out('>>');
			$this->_out('endobj');

		}
		function _putresources() {
			parent::_putresources();
			if (!empty($this->javascript)) {
				$this->_putjavascript();
			}
		}
		function _putcatalog() {
			parent::_putcatalog();
			if (isset($this->javascript)) {
				$this->_out('/Names <</JavaScript '.($this->n_js).' 0 R>>');
			}
		}
	}

	class PDF_AutoPrint extends PDF_Javascript
	{
		function AutoPrint()
		{
			$script = 'print(true);';
			$this->IncludeJS($script);
		}
	}
	
	$pdf = new PDF_AutoPrint('L','mm',array(100,50));
	$pdf->SetAutoPageBreak(true,0);
	$pdf->SetFont('Arial','',9);
	$fila=0;
	
	
	$categoria='semana';
			
switch ($categoria) {
		
	case 'semana':
		$numeroSemana = (int) date("W");
		$qObtenerImagenEtiqueta=$ce->obtenerImagenEtiqueta($conexion, $numeroSemana, $categoria);
		$imagenAleatorioEtiqueta=pg_fetch_result($qObtenerImagenEtiqueta, 0, 'ruta');
	break;
	
	case 'mes':
		$numeroMes =date("n");
		$qObtenerImagenEtiqueta=$ce->obtenerImagenEtiqueta($conexion, $numeroSemana, $categoria);
		$imagenAleatorioEtiqueta=pg_fetch_result($qObtenerImagenEtiqueta, 0, 'ruta');
	break;
	
}
	
	while($filaImprimir=pg_fetch_assoc($qEtiquetasImprimir)){
		$pdf->AddPage();
		$pdf->Image('img/etiqueta.png' , $pdf->SetY($fila) ,$pdf->SetX(0), 100 , 50,'PNG');
		$fila+=5;

		//INICIO GENERACION QR
		$infoQr='CI/RUC: '.$filaImprimir['identificador_operador']."\n".
				'Razón Social: '.utf8_decode($filaImprimir['razon_social'])."\n".
				'N° Etiqueta: '.$filaImprimir['numero_etiqueta']."\n".
				'Fecha de Emisión: '.$filaImprimir['fecha_aprobacion']."\n".
				'Fecha Impresión Etiqueta: '.$filaImprimir['fecha_registro'];

		$rutaImagenQr='temp/'.$filaImprimir['id_etiqueta_impresa'].'.png';
		$filename = RUTA_PNG_TEMP.$filaImprimir['id_etiqueta_impresa'].'.png';
		QRcode::png($infoQr, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
		$pdf->Image($rutaImagenQr , $pdf->SetY($fila) ,$pdf->SetX(2.2), 26, 26,'PNG');
		
		//FIN GENERACION QR

		$fila+=23.2;
		$pdf->SetXY(51,$fila);
		//$pdf->MultiCell(30,3,$filaImprimir['fecha_registro'],0,'J',0 );
		//$fila+=4.9;
		//$pdf->SetXY(51,$fila);
		$pdf->MultiCell(46,3,substr( utf8_decode($filaImprimir['razon_social']),0,62),0,'L',0 );
		$fila+=6.6;
		$pdf->Image($imagenAleatorioEtiqueta , $pdf->SetY($fila) ,$pdf->SetX(10.2), 10, 10,'JPG');
		$fila+=6.5;
		$pdf->SetXY(39.8,$fila);
		$pdf->Cell(40, 4, $filaImprimir['identificador_operador'], 0, 1, 'L');
		$fila+=4.2;
		$pdf->SetXY(36,$fila);
		$pdf->Cell(18, 4, $filaImprimir['numero_etiqueta'], 0, 1, 'L');
		
		$fila=0;
	}

	$rutaArchivo="etiquetas/Etiqueta_detalle_".$idEtiquetaDetalle.".pdf";
	$pdf->AutoPrint();
	$pdf->Output($rutaArchivo,'F');

	eliminarTemporales('temp');

	echo '<iframe id="frame" src="'.RUTA_MODULO.'/'.$rutaArchivo.'" style="height: 100px; width: 0px;"></iframe>';
	
	$conexion->ejecutarConsulta("commit;");

} catch (Exception $ex) {
	$conexion->ejecutarConsulta("rollback;");
} finally {
	$conexion->desconectar();
}
?>
<script>
	$(document).ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		iframe = document.getElementById('frame');
		setTimeout(function() {
			iframe.contentDocument.execCommand ("print", false, null);
			}, 2000);
     });
</script>