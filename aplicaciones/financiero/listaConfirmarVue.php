<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorFinancieroAutomatico.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones('financiero', 'abrirConfirmarVue', null, 60);
$cfa = new ControladorFinancieroAutomatico();


$qSolicitudesConfirmar = $cfa->obtenerTipoSolicitudPorEstadoFinancieroAutomatico($conexion, 'saldoVue', 'is null');

while ($solicitudesConfirmar = pg_fetch_assoc($qSolicitudesConfirmar)){
	echo $ca->imprimirArticulo($solicitudesConfirmar['id_financiero_cabecera'], ++$contador, 'Número orden pago: '.$solicitudesConfirmar['numero_solicitud'].'</br> Valor: '.$solicitudesConfirmar['total_pagar'], 'Id Orden VUE: '.$solicitudesConfirmar['id_vue']);
}

?>


<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Confirmar saldo VUE</h1>
	</header>
</body>
<script>

$(document).ready(function(){
	$("#listadoItems").removeClass("programas");
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para visualizar.</div>');	
});

</script>
</html>
