<?php

require_once '../../../clases/ControladorWebServices.php';

	function buscarImportacionProductoSensibleCultivares() {
		
		$controladorWebServices = new ControladorWebServices('VUE');
		
		$datosImportacion = $controladorWebServices->buscarProductosSensiblesCultivares();
	
		return array('mensaje' => $datosImportacion);
	
	}

?>