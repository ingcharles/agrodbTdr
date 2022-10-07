<?php

// $datosEntradaFitosanitario = array( "datosEntradaFitosanitario" => array(
//     'fecha_desde'    => "",
//     'fecha_hasta'     => "",
//     'estado'  => "aprobado"));

require_once '../../../clases/ControladorFitosanitarioExportacion.php';

$certificadoFitosanitario = new ControladorFitosanitarioExportacion();

// $datosEntradaFitosanitario = array(
// 		'fecha_desde'    => "",
// 		'fecha_hasta'     => "",
// 		'estado'  => "aprobado");

// echo '<pre>';
// print_r($datosEntradaFitosanitario);
// echo '</pre>';

$clientOptions = array('login' => 'pruebaHolanda', 'password' => '$rUe*aHol@nda');

$fecha_desde = '';
$fecha_hasta = '';
$status = 'aprobado';

$cliente = new SoapClient("http://localhost/agrodb/aplicaciones/webServices/fitosanitario/fitosanitarioExportacion.php?wsdl", $clientOptions);

$resultado = $cliente->Certificado_actualizacion_fecha_estado($fecha_desde, $fecha_hasta, $status);

echo '<pre>';
print_r($resultado);
echo '</pre>';

//----------------------------------------------------------------------------------------------------------------

echo '-------------------------------------------------------------------------------------------------------------------------------';

//$identificadorFitosanitario = array('numero_certificado' => $resultado[0]);
// $numeroCertificado =  $resultado[0];

$obtencionXml = $cliente->Recupera_certificado_oficial($numeroCertificado);

$arrayIndividual = $certificadoFitosanitario->xml2array($obtencionXml);

/*$archivo="datos.xml";

$file=fopen($archivo,"w");
fwrite($file,$obtencionXml);*/

echo '<pre>';
print_r($obtencionXml);
echo '</pre>';

echo '-------------------------------------------------------------------------------------------------------------------------------';


//$identificadorFitosanitario = array('numero_certificado' => $resultado[0]);

$confirmacion = $cliente->Confirmacion_certificado($numeroCertificado);

echo $confirmacion;


?>