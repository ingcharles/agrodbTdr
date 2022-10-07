<?php
/**
 * Controlador Noticias
 *
 * Este archivo controla la lógica del negocio del modelo: Requisitos de comercialización y productos
 *
 * @author AGROCALIDAD
 * @date   2019-07-05
 * @uses RestWsProductosControlador
 * @package AplicacionMovilExternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilExternos\Controladores;

use Agrodb\RequisitosComercializacion\Modelos\RequisitosComercializacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;

class RestWsProductosControlador extends BaseControlador{

	private $lNegocioRequisitosComercializacion = null;
	
	private $lNegocioProducto = null;

	/**
	 * Constructor
	 */
	function __construct(){
		
		$this->lNegocioRequisitosComercializacion = new RequisitosComercializacionLogicaNegocio();
		$this->lNegocioProducto = new ProductosLogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}
	
	/**
	 * Método de obtención de Productos del área de registros de insumos por tipo de requisito y área
	 */
	public function obtenerProductoRegistroInsumos($nombreProducto, $partidaArancelaria, $idArea){
		
		$nombreProducto = $this->quitarTildes(trim($nombreProducto));
		$partidaArancelaria = trim($partidaArancelaria);

		$arrayParametros = array('nombre_producto' => $nombreProducto,
			'partida_arancelaria' => $partidaArancelaria,
			'id_area' => $idArea);

		$idProductos = $this->lNegocioRequisitosComercializacion->obtenerProductoPorTipoRequisitoAreaNombreProducto($arrayParametros);

		$arrayParametros +=array('producto_excluido' => $idProductos);

		$datosProducto = $this->lNegocioProducto->obtenerDatosProductoPorAreaNombreProductoPartidaArancelaria($arrayParametros);

		echo json_encode($datosProducto->toArray());
	}
	
	/**
	 * Método de obtención de datos especificos de Productos del área de registros de insumos por id producto y área
	 */
	public function obtenerDatosEspecificosProductoRegistroInsumos($idProducto, $idArea){
		
		$arrayParametros = array('id_producto' => $idProducto,
			'id_area' => $idArea);
		
		$datosProducto = $this->lNegocioProducto->obtenerDatosEspecificosProductoPorIdProductoArea($arrayParametros);
		
		echo json_encode($datosProducto->toArray());
	}
}
