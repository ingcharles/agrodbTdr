<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCertificados.php';
require_once("http://localhost:8080/JavaBridge/java/Java.inc");
require("php-jru/php-jru.php");

echo '<pre>';
print_r ($_POST);
echo '</pre>';



$identificador = $_SESSION['usuario'];
$valor  = $_POST['valoresFiltrados'];

$idFitosanitario = ($valor);
/*
for ($i = 0; $i < count ($idFitosanitario); $i++) {
	echo $idFitosanitario[$i];
}	
*/

//Obtener Fecha de Hoy
$fecha = time ();
$fecha_partir1=date ( "h" , $fecha ) ;
$fecha_partir2=date ( "i" , $fecha ) ;
$fecha_partir4=date ( "s" , $fecha ) ;
$fecha_partir3=$fecha_partir1-1;
$reporte="Reporte_";
$filename = $reporte. date("Y-m-d")."_". $fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';

//Ruta del reporte compilado Jasper generado por IReports
$jru = new JRU();
	
	$Reporte=''.$_SERVER['DOCUMENT_ROOT'].'/agrodb/aplicaciones/certificadosFitosanitarios/reportes/certificadosPrueba.jasper';
	$SalidaReporte=''.$_SERVER['DOCUMENT_ROOT'].'/agrodb/aplicaciones/certificadosFitosanitarios/documentos/'.$filename;
	
	$arreglo = array(96,98,99);
	
	
 	$prueba2 = implode(',', $_POST['valoresFiltrados']);
	//echo $prueba2; 
	print_r($arreglo) ;
	 
//	$a = 161; 
	$parameters = new java('java.util.HashMap');
	$parameters ->put("valor",$arreglo);

 /*		
	for ($i = 0; $i < 1; $i++) {
		$parameters = new java('java.util.HashMap');
		$parameters ->put("valor",101);
	}
*/	
	$Conexion= new JdbcConnection('org.postgresql.Driver','jdbc:postgresql://localhost:5432/agrocalidad','postgres','postgres');
	
	$jru->runReportToPdfFile($Reporte,$SalidaReporte,$parameters,$Conexion->getConnection());
	
?>


	
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>

<body>	
<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Certificados</h1>
</header>
<p>	
	<a href="aplicaciones/certificadosFitosanitarios/listaDocumentos.php?archivo=<?php echo $filename ?>" target="visor" style="text-decoration: none;" >pdf</a> 
	<iframe id = "visor" name="visor" width="550" height="500" src="about:blank" ></iframe>
</p>
</body>
<script type="text/javascript">


</script>
</html>
	
