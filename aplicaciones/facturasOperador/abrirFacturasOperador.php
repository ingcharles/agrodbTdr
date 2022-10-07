<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/GoogleAnalitica.php';
require_once '../../clases/Constantes.php';

$conexion = new Conexion();
//$cro = new ControladorRegistroOperador();
$cc = new ControladorCertificados();
$constg = new Constantes();

$idGrupoFacturas = explode(",",($_POST['elementos']==''?$_POST['id']:$_POST['elementos']));

?>

<header>
	<h1>Facturas</h1>
</header>

	<fieldset>
		<legend>Comprobantes de facturación</legend>
		
		<table style="width:100%">
			<thead>
				<tr>					
					<th>N° factura</th>
					<th>Archivo factura PDF</th>
					<th>Archivo factura XML</th>
					<th>Fecha facturación</th>	
				</tr>
		</thead>
		
	<?php 

		foreach ($idGrupoFacturas as $factura){

			$ordenPago = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $factura));
			
			$rutaXml = explode($constg::RUTA_APLICACION.'/', $ordenPago['ruta_xml']);
		
			echo '<tr>					
					<td>'.$ordenPago['numero_establecimiento'].'-'.$ordenPago['punto_emision'].'-'.$ordenPago['numero_factura'].'</td>
					<td><a href="'.$ordenPago['factura'].'" target= "_blank">Archivo </a></td>
					<td><a download="'.$ordenPago['clave_acceso'].'.xml" href="'.$rutaXml['1'].'">Archivo </a></td>
					<td>'.date('d/m/Y G:i',strtotime($ordenPago['fecha_facturacion'])).'</td>
				</tr>';			
		}
	
	?>
		</table>
	</fieldset>