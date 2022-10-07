<?php
/**
 * Controlador Denuncia
 *
 * Este archivo controla la lógica del negocio del modelo: DenunciaModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2019-06-06
 * @uses DenunciaControlador
 * @package AplicacionMovilExternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilExternos\Controladores;

use Agrodb\AplicacionMovilExternos\Modelos\DenunciaLogicaNegocio;
use Agrodb\AplicacionMovilExternos\Modelos\DenunciaModelo;

class RestWsDenunciaControlador extends BaseControlador{

	private $lNegocioDenuncia = null;

	private $modeloDenuncia = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioDenuncia = new DenunciaLogicaNegocio();
		$this->modeloDenuncia = new DenunciaModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método quee permite guardar denuncias.
	 */
	public function guardarDenuncia() {
		$idDenuncia = $this->lNegocioDenuncia->guardarNuevaDenuncia($_POST);
		echo $idDenuncia;
	}
}
