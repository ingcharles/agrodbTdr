<?php

require_once '../../../clases/ControladorWebServices.php';

	function buscarPartidasArancelariasPorOperadorComercioExterior($identificadorOperador) {
		
		$controladorWebServices = new ControladorWebServices('GUIA');
						
		$datosPartidas = $controladorWebServices->buscarPartidasArancelariasRoce($identificadorOperador);
	
		return array('mensaje' => $datosPartidas);
	
	}

?>