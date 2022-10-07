<?php
/**
 * Controlador MotivosDenuncia
 *
 * Este archivo controla la lógica del negocio del modelo: MotivosDenunciaModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2019-06-06
 * @uses MotivosDenunciaControlador
 * @package AplicacionMovilExternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilExternos\Controladores;

use Agrodb\AplicacionMovilExternos\Modelos\MotivosDenunciaLogicaNegocio;
use Agrodb\AplicacionMovilExternos\Modelos\MotivosDenunciaModelo;

class RestWsMotivosDenunciaControlador extends BaseControlador{

	private $lNegocioMotivosDenuncia = null;

	private $modeloMotivosDenuncia = null;

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioMotivosDenuncia = new MotivosDenunciaLogicaNegocio();
		$this->modeloMotivosDenuncia = new MotivosDenunciaModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}
	
	/**
	 * Método de obtención de motivos de denuncia.
	 */
	public function obtenerMotivosDenuncia(){
		$motivoDenuncia = $this->lNegocioMotivosDenuncia->buscarMotivosDenuncia();
		echo json_encode($motivoDenuncia->toArray());
	}
}
