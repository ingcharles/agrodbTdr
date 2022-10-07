<?php
	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	
	$idUsuario = $_SESSION['usuario'];
	$idAplicacion = $_SESSION['idAplicacion'];
	
	$res = $cu-> obtenerAccesoUsuario($conexion, $idUsuario, $idAplicacion);
	
	if(pg_num_rows($res) > 0)
	{
		$accesoUsuario = pg_fetch_assoc($res);
		$valor= $accesoUsuario['nivel_funciones'];
	
	}else{ 
		$valor=0;
	}

?>

	<header>
		<h1>Reportes Financiero</h1>
	</header>
	<article id="1" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte de facturación por provincia de finalización</span>
		<span class="ordinal">1</span>
		
		<aside></aside>
	</article>
	<article id="2" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Informe por item</span>
		<span class="ordinal">2</span>
		<aside></aside>
	</article>
	
	<article id="3" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Informe por depósito </span>
		<span class="ordinal">3</span>
		<aside></aside>
	</article>
	
	<article id="4" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Informe por número de factura</span>
		<span class="ordinal">4</span>
		<aside></aside>
	</article>
	
	<article id="5" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Informe consolidado por partida presupuestaria</span>
		<span class="ordinal">5</span>
		<aside></aside>
	</article>
	
	<article id="6" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte de facturación por diferentes puntos de recaudación</span>
		<span class="ordinal">6</span>
		<aside></aside>
	</article>
	
	<article id="7" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacionNotaCredito" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Informe nota de credito por punto de venta</span>
		<span class="ordinal">7</span>
		<aside></aside>
	</article>
	
	<article id="8" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte de facturación por punto de recaudación</span>
		<span class="ordinal">1</span>
		<aside></aside>
	</article>
	
	<article id="9" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacionNotaCredito" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Informe notas de crédito por punto de venta</span>
		<span class="ordinal">2</span>
		<aside></aside>
	</article>
	
	<article id="10" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacionNotaCredito" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Informe nota de crédito por provincia</span>
		<span class="ordinal">8</span>
		<aside></aside>
	</article>
	
	<article id="11" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Informe de excedentes por provincia</span>
		<span class="ordinal">9</span>
		
		<aside></aside>
	</article>
	
	<article id="12" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Informe de excedentes por punto de recaudación</span>
		<span class="ordinal">3</span>
		
		<aside></aside>
	</article>
	
	<article id="13" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Informe ingreso de caja por provincia</span>
		<span class="ordinal">10</span>
		
		<aside></aside>
	</article>
	
	<article id="14" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Informe ingreso de caja por punto de recaudación</span>
		<span class="ordinal">4</span>
		
		<aside></aside>
	</article>
			
	<article id="15" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte de items facturados por punto de recaudación</span>
		<span class="ordinal">11</span>
		<aside></aside>
	</article>
	
	<!-- INICIO EJAR -->
	
	<article id="16" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacionSaldo" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte de consumo de saldo disponible</span>
		<span class="ordinal">12</span>
		
		<aside></aside>
	</article>
	
	<article id="17" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte de comprobantes de saldo disponible</span>
		<span class="ordinal">13</span>
		<aside></aside>
	</article>
	
	<article id="18" class="item" data-rutaAplicacion="financiero"	data-opcion="filtroRecaudacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte de cuadre de caja diario</span>
		<span class="ordinal">14</span>
		<aside></aside>
	</article>
	
	<!-- INICIO EJAR -->
			
<script type="text/javascript">

//var existePermiso = < ?php echo json_encode($valor);?>;

	$(document).ready(function(){
		
		$("#listadoItems").removeClass("programas");
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un reporte para visualizar.</div>');

	});

	/*if(existePermiso == '1'){

		$("#listadoItems #1").show();
		$("#listadoItems #2").show();
		$("#listadoItems #3").show();
		$("#listadoItems #4").show();
		$("#listadoItems #5").show();
		$("#listadoItems #6").show();
		$("#listadoItems #7").show();
		$("#listadoItems #10").show();
		$("#listadoItems #8").hide();
		$("#listadoItems #9").hide();
		$("#listadoItems #11").show();
		$("#listadoItems #13").show();
		$("#listadoItems #14").hide();
		$("#listadoItems #18").show();

	}else{

		$("#listadoItems #1").hide();
		$("#listadoItems #2").hide();
		$("#listadoItems #3").hide();
		$("#listadoItems #4").hide();
		$("#listadoItems #5").hide();
		$("#listadoItems #6").hide();
		$("#listadoItems #7").hide();
		$("#listadoItems #10").hide();
		$("#listadoItems #11").hide();
		$("#listadoItems #13").hide();
		$("#listadoItems #14").show();
		$("#listadoItems #18").show();


	}*/


</script>