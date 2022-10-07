<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 05/04/18
 * Time: 22:22
 */
require_once "../servicios/ServiceReportesDAO.php";
require_once '../../../clases/Conexion.php';
require_once '../Util.php';

$filename = "detalleRegistros";
$file_ending = "xls";

$conexion = new Conexion();
$servicio = new ServiceReportesDAO();
$util = new Util();

$objeto = $_GET['where'];

$objeto = json_decode($objeto);

$sqlWHERE = $util->getSQLWhere($objeto);


$result = $servicio->reporteDetallado($conexion,$sqlWHERE);
//header info for browser
header("Content-Type: application/xls; charset=UTF-8");
header("Content-Disposition: attachment; filename=$filename.xls");
header("Pragma: no-cache");
header("Expires: 0");
/*******Start of Formatting for Excel*******/
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
//start of printing column names as names of MySQL fields
for ($i = 0; $i < pg_num_fields($result); $i++) {
    echo pg_field_name($result,$i) . "\t";
}
print("\n");
//end of printing column names
//start while loop to get data
while($row = pg_fetch_row($result))
{
    $schema_insert = "";
    for($j=0; $j<pg_num_fields($result);$j++)
    {
        if(!isset($row[$j]))
            $schema_insert .= "NULL".$sep;
        elseif ($row[$j] != "")
            $schema_insert .= "$row[$j]".$sep;
        else
            $schema_insert .= "".$sep;
    }
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert));
    print "\n";
}
?>