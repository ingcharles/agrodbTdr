<?php
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorExpedienteDigital.php';

	$conexion = new Conexion();
	$ce = new ControladorExpedienteDigital();
//------------------------------------------------------------------------------------------------------
		
	    $operadores = $ce->filtrarRazonSocialUsuariosRucCiNumero($conexion, $_POST['tipoDeBusqueda'],$_POST['textoDeBusqueda'],$_POST['provincia'],$_POST['area'],$_POST['desplazamiento'],$_POST['inicio'],0);
	    if(pg_num_rows($operadores)>0){
	        $json=$ce->listarUsuarios($operadores, $_POST['provincia'], $_POST['area'], $_POST['inicio']);
	    }
?>
<table id="tablaItems">
	   <thead>
    <tr>
        <th>#</th>
        <th>RUC</th>
        <th>Razón social</th>
        <th>Representante</th>
        <th></th>
    </tr>
    </thead>
    <tbody> 
    </tbody>
	</table>
<script type="text/javascript">
    $(document).ready(function(e){   	   
    	construirPag(<?php echo $json;?>,<?php echo $_POST['desplazamiento'];?>);
    });
    function construirPag(items,itemFinal){	
    	$("#tablaItems tbody").html("");
		for(var contador = 0;contador<=itemFinal;contador++)
		$("#tablaItems tbody").append(items[contador]);
    }

</script>