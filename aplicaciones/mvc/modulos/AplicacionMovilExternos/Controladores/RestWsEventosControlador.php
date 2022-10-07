<?php
/**
 * Controlador Noticias
 *
 * Este archivo controla la lógica del negocio del modelo: NoticiasModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2019-06-06
 * @uses NoticiasControlador
 * @package AplicacionMovilExternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilExternos\Controladores;

use Agrodb\AplicacionMovilExternos\Modelos\EventosLogicaNegocio;
use Agrodb\AplicacionMovilExternos\Modelos\EventosModelo;
use Agrodb\AplicacionMovilExternos\Modelos\DetalleEventosLogicaNegocio;
use Agrodb\AplicacionMovilExternos\Modelos\DetalleEventosModelo;

class RestWsEventosControlador extends BaseControlador{

	private $lNegocioEventos = null;

	private $modeloEventos = null;
	
	private $lNegocioDetalleEventos = null;
	
	private $modeloDetalleEventos = null;

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioEventos = new EventosLogicaNegocio();
		$this->modeloEventos = new EventosModelo();
		$this->lNegocioDetalleEventos = new DetalleEventosLogicaNegocio();
		$this->modeloDetalleEventos = new DetalleEventosModelo();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de obtención de eventos 
	 */
	public function obtenerEventos(){
		$eventos = $this->lNegocioEventos->buscarLista("estado = 'activo'");
		echo json_encode($eventos->toArray());
	}
	
	/**
	 * Método de obtención de detalle de eventos por identificador de evento
	 */
	public function obtenerDetalleEventos($idEvento){
		$arrayParametros = array('id_evento' => $idEvento);
		$detalleEventos = $this->lNegocioDetalleEventos->buscarLista("id_evento = '".$arrayParametros['id_evento']."' and estado = 'activo'", 'fecha_evento');
		echo json_encode($detalleEventos->toArray());
	}
}
