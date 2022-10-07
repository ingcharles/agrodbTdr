<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorEmpleados.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Reporte certificados</h1>
</header>

<?php
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$ce = new ControladorEmpleados();
	
	$certificados= ($_POST['valoresFiltrados']);
	
	//print_r($_POST);
	//print_r($_SESSION);
	
	$qDetalleCertificado = array();
	
	$identificadorAgencia = $_SESSION['usuario'];

?>
	
<table id="historial">
   	<thead>
		<tr>
		    <th>#</th>
		     <th>Exportador</th>
		     <th>Motivo</th>
		     <th>Estado</th>
		     
		 </tr>
	 </thead>
	<tbody>
		 	<tr>
		     <?php 
		      $registro = 0;
		      for ($i=0; $i<count($certificados); $i++) {
		     	echo '<tr>';
		     		//VALIDAR
		     		
		     		//Obtener registros cabecera temporales
		     		$cabeceraCertificados = pg_fetch_assoc($cc->buscarDatosCertificadoOperador($conexion, $certificados[$i]));
   		     		
		     		//Obtener registros de detalle temporales
		     		$qDetalleCertificado = $cc->buscarDatosCertificadoOperadorDetalle($conexion, $certificados[$i]);
		     		
		     		while ($fila = pg_fetch_assoc($qDetalleCertificado)){
		     			//$detalleCertificado[]= $fila;
		     			$detalleCertificado[]= array('idProducto'=>$fila['id_producto']);
		     		}
	     		
		     		$resultado = $cc -> validarDatosCertificado($conexion,$cabeceraCertificados,$detalleCertificado,$identificadorAgencia);
		     			     		
		     		if(!$resultado[0]){
		     			//Imprimir el error (Tabla)
		     			echo'<td>'.$i.'</td>';
		     			echo'<td>'.$cabeceraCertificados['identificador_exportador'].'</td>';
		     			echo'<td>'.$resultado[1].'</td>';
		     			echo'<td>No enviado</td>';
		     			//$cc -> insertarDatosCertificado($conexion,$cabeceraCertificados['id_tmp_fitosanitario']);
		     		}else{
		     			//Insertamos valores a tablas
		     			//$cc -> insertarDatosCertificado($conexion,$cabeceraCertificados);
		     		}
		     	echo '</tr>';
		     	}
		     	
     	?>
		</tr>
	</tbody>
</table>
	
</body>
<script type="text/javascript">
</script>
</html>
