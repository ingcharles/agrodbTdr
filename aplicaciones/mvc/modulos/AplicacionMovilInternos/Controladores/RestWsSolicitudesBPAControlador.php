<?php
/**
 * Controlador Solicitudes BPA
 *
 * Este archivo controla la lógica del negocio del modelo: AlertasModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2022-01-29
 * @uses AplicacionesControlador
 * @package AplicacionMovilInternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilInternos\Controladores;

use Agrodb\CertificacionBPA\Modelos\SolicitudesLogicaNegocio;

class RestWsSolicitudesBPAControlador extends BaseControlador{
    
	private $lNegocioCertificacionBpa = null;
	

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioCertificacionBpa = new SolicitudesLogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}
	
	
	/**
	 * Método de obtención de las provincias
	 */
	public function obtenerSolicitudesBpaInspeccionMovil($provincia){
		$arrayParametos = array('provincia' => $provincia);		
		$this->lNegocioCertificacionBpa->buscarSolicitudesBpaInspeccionMovil($arrayParametos);
	}

	/**
	 * Método que guarda las inspecciones de trampas de vigilancia
	 */
	public function guardarInspeccionBpaMovil(){

		$certificadoBpa = (array) json_decode(file_get_contents('php://input'));
		$this->lNegocioCertificacionBpa->guardarDatosInspeccionMovil($certificadoBpa);
				
	}

}


	
