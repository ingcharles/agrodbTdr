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

use Agrodb\AplicacionMovilExternos\Modelos\AlertasUsuarioLogicaNegocio;
use Agrodb\AplicacionMovilExternos\Modelos\TiposAlertaLogicaNegocio;

class RestWsAlertasUsuarioControlador extends BaseControlador{

	private $lNegocioAlerta = null;
	private $lNegocioTipoAlerta = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioAlerta = new AlertasUsuarioLogicaNegocio();
		$this->lNegocioTipoAlerta = new TiposAlertaLogicaNegocio();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método que permite obtener los dieferentes tipos de alertas.
	 */
	public function obtenerTiposAlerta() {
		$tiposAlerta = $this->lNegocioTipoAlerta->buscarTiposAlerta();
		echo json_encode($tiposAlerta->toArray());
	}

	/**
	 * Método que permite guardar alertas de usuarios.
	 */
	public function guardarAlerta() {

		$trampas = (array) json_decode(file_get_contents('php://input'));
		$idAlerta = $this->lNegocioAlerta->guardarNuevaAlerta($trampas);
	
	}

}
