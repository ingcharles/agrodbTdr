<?php
session_start();



	require_once 'abrirSolicitudProtocolo.php';


?>

<script type="text/javascript">

		
	$("document").ready(function(){
		
		$('#detalleItem').find('h1').first().html("Protocolo de ensayo de eficacia: "+protocolo.id_expediente);
		//desactiva las entradas de datos
		$('section#detalleItem').find('input:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');
		
		$('section#detalleItem').find('button:not(.bsig,.bant)').hide();

		
		$('.ocultarOtros').hide();

		$('#verReporte').show();
		$('#verReporte').attr('href', protocolo.ruta);
		$('#verReporte').text('Ver pdf del protocolo');
		

	});



</script>


