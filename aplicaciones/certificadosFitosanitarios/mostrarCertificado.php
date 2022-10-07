<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
?>

<form id='reporteHistorialTalleres' data-rutaAplicacion='transportes' data-opcion='abrirReporteTaller' data-destino="detalleItem">

<div id="paginacion" class="normal">

</div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Gasolinera</th>
			<th>Direccion</th>
			<th>Contacto</th>
			<th>Telefono</th>
			<th>Localización</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>

<?php
if($_FILES['file']['name'] != '')
{
		
	require_once 'reader/Classes/PHPExcel/IOFactory.php';

	//Funciones extras
		
	function get_cell($cell, $objPHPExcel){
		//select one cell
		$objCell = ($objPHPExcel->getActiveSheet()->getCell($cell));
		//get cell value
		return $objCell->getvalue();
	}
		
	function pp(&$var){
		$var = chr(ord($var)+1);
		return true;
	}

	$name	  = $_FILES['file']['name'] ;
	echo $name;
	
	$tname 	  = $_FILES['file']['tmp_name'];
	$type 	  = $_FILES['file']['type'];

	if($type == 'application/vnd.ms-excel')
	{
		// Extension excel 97
		$ext = 'xls';
	}
	else if($type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
	{
		// Extension excel 2007 y 2010
		$ext = 'xlsx';
	}else{
		// Extension no valida
		echo -1;
		exit();
	}

	$xlsx = 'Excel2007';
	$xls  = 'Excel5';

	//creando el lector
	$objReader = PHPExcel_IOFactory::createReader($$ext);
		
	//cargamos el archivo
	$objPHPExcel = $objReader->load($tname);

	$dim = $objPHPExcel->getActiveSheet()->calculateWorksheetDimension();

	// list coloca en array $start y $end
	list($start, $end) = explode(':', $dim);

	if(!preg_match('#([A-Z]+)([0-9]+)#', $start, $rslt)){
		return false;
	}
	list($start, $start_h, $start_v) = $rslt;
	if(!preg_match('#([A-Z]+)([0-9]+)#', $end, $rslt)){
		return false;
	}
	list($end, $end_h, $end_v) = $rslt;

	//empieza  lectura vertical
	$table = "<table  border='1'>";
	for($v=$start_v; $v<=$end_v; $v++){
		//empieza lectura horizontal
		$table .= "<tr>";
		for($h=$start_h; ord($h)<=ord($end_h); pp($h)){
			$cellValue = get_cell($h.$v, $objPHPExcel);
			$table .= "<td>";
			if($cellValue !== null){
				$table .= $cellValue;
			}
			$table .= "</td>";
		}
		$table .= "</tr>";
	}
	$table .= "</table>";
		
	echo $table;
}

?>

</table>

<div id="valores"></div>
	
	<button type="submit" class="guardar">Generar reporte</button>

</form>



