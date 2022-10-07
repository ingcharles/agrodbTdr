<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorExpedienteDigital.php';

	$conexion = new Conexion ();
	$ce = new ControladorExpedienteDigital();
	
	$consulta=$ce->listarDetalleServicios($conexion,$_POST['numServicio'],$_POST['identificador'],$_POST['provincia'],1,$_POST['desplazamiento'],$_POST['inicio']);
	$json=$ce->listarDetalles($consulta,$_POST['tipoServicio'],$_POST['inicio'],$_POST['provincia'],$_POST['area']);
	
	//------------imprimir encabezado-------------------------------
	echo $ce->encabezadoDetalleServicio($_POST['tipoServicio']);
	//--------------------------------------------------------------	
?>
<script type="text/javascript">
    $(document).ready(function(){    
       construirPag(<?php echo $json;?>,<?php echo $_POST['desplazamiento'];?>);    	
    });
    //--------------imprimir resultado-----------------------------
    function construirPag(items,itemFinal){	
    	$("#tablaItems tbody").html("");
		for(var contador = 0;contador<=itemFinal;contador++)
		$("#tablaItems tbody").append(items[contador]);
	//-------------------------------------------------------------
    }
</script>