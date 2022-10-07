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

use Agrodb\AplicacionMovilExternos\Modelos\NoticiasLogicaNegocio;
use Agrodb\AplicacionMovilExternos\Modelos\NoticiasModelo;

class RestWsNoticiasControlador extends BaseControlador{

	private $lNegocioNoticias = null;

	private $modeloNoticias = null;

	/**
	 * Constructor
	 */
	function __construct(){
		$this->lNegocioNoticias = new NoticiasLogicaNegocio();
		$this->modeloNoticias = new NoticiasModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de obtención de noticias basado en una carga offset, fectch
	 */
	public function obtenerNoticias($incremento){
		$arrayParametros = array('incremento' => $incremento);
		$noticias = $this->lNegocioNoticias->obtenerNoticiasOffset($arrayParametros);
		echo json_encode($noticias->toArray());
	}
	
	/**
	 * Método de obtención de noticias destacadas
	 */
	public function obtenerNoticiasDestacadas(){
		$noticiasDestacadas = $this->lNegocioNoticias->buscarLista("estado = 'activo'", 'visitas desc', 5);
		echo json_encode($noticiasDestacadas->toArray());
	}
	
	/**
	 * Método de para la actualización de la cantidad de visitas
	 */
	public function actualizarCantidadVisitas($idNoticia){
		$noticia = $this->lNegocioNoticias->buscar($idNoticia);
		$cantidadVista = $noticia->getVisitas()+1;
		$datos = array('id_noticia' => $idNoticia, 'visitas' => $cantidadVista);
		$this->lNegocioNoticias->guardar($datos);
	}
}
