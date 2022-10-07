<?php
/**
 * Controlador Importaciones
 *
 * Este archivo controla la lógica del negocio del modelo: ImportacionesModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-07-06
 * @uses ImportacionesControlador
 * @package Importaciones
 * @subpackage Controladores
 */
namespace Agrodb\RevisionSolicitudesVue\Controladores;

use Agrodb\Importaciones\Modelos\ImportacionesLogicaNegocio;
use Agrodb\Importaciones\Modelos\ImportacionesModelo;

class RevisionSolicitudesVueControlador extends BaseControlador{

	private $lNegocioImportaciones = null;

	private $modeloImportaciones = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioImportaciones = new ImportacionesLogicaNegocio();
		$this->modeloImportaciones = new ImportacionesModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function importaciones(){
		$this->cargarPanelBusquedaSolicitud();
		require APP . 'RevisionSolicitudesVue/vistas/listaRevisionSolicitudesVueImportacionesVista.php';
	}

}
