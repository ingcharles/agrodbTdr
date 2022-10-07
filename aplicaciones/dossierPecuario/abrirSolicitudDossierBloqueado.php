<?php
session_start();

	
require_once 'abrirSolicitudDossier.php';


?>

<script type="text/javascript">

		
	$("document").ready(function(){
		
		$('#detalleItem').find('h1').first().html("Dossier en trámite : " + solicitud.id_expediente);

		//desactiva las entradas de datos
		$('section#detalleItem').find('input:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');
		
		$('section#detalleItem').find('button:not(.bsig,.bant,.btnVistaPreviaDossier)').hide();

		$('input:radio[name="boolAcepto"]').prop('disabled',false);

		//Ocultar formulario de Origen del Producto
		$('#frmProcedencia').hide();

		construirAnimacion(".pestania");
		distribuirLineas();
	});



</script>


