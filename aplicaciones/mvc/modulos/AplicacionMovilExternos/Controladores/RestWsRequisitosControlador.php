<?php
/**
 * Controlador Noticias
 *
 * Este archivo controla la lógica del negocio del modelo: Requisitos de comercialización y productos
 *
 * @author AGROCALIDAD
 * @date   2019-06-06
 * @uses RestWsRequisitosControlador
 * @package AplicacionMovilExternos
 * @subpackage Controladores
 */
namespace Agrodb\AplicacionMovilExternos\Controladores;

use Agrodb\RequisitosComercializacion\Modelos\RequisitosComercializacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\SubtipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;

class RestWsRequisitosControlador extends BaseControlador{

	private $lNegocioRequisitosComercializacion = null;
	
	private $lNegocioSubtipoProducto = null;
	
	private $lNegocioProducto = null;

	/**
	 * Constructor
	 */
	function __construct(){
		
		$this->lNegocioRequisitosComercializacion = new RequisitosComercializacionLogicaNegocio();
		$this->lNegocioSubtipoProducto = new SubtipoProductosLogicaNegocio();
		$this->lNegocioProducto = new ProductosLogicaNegocio();
		
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de obtención de Productos por tipo de requisito y área
	 */
	public function obtenerProductoRequisito($localizacion, $nombreProducto, $idArea, $tipoRequisito){
		
		$nombreProducto = $this->quitarTildes(trim($nombreProducto));
		
		$arrayParametros = array('id_localizacion' => $localizacion,
								 'nombre_producto' => $nombreProducto,
								 'id_area' => $idArea,
								 'tipo_requisito' => $tipoRequisito);

		$productosRequisito = $this->lNegocioRequisitosComercializacion->obtenerProductoPorLocalizacionNombreAreaTipoRequisito($arrayParametros);

		echo json_encode($productosRequisito->toArray());
	}
	
	/**
	 * Método de obtención de requisitos por producto, tipo requisito y país
	 */
	public function obtenerRequisitosPorPais($idProducto, $tipoRequisito, $idPais){
		$arrayParametros = array('id_localizacion' => $idPais,
			'id_producto' => $idProducto,
			'tipo_requisito' => $tipoRequisito);
		
		$requisitos = $this->lNegocioRequisitosComercializacion->obtenerRequisitoPorProductoTipoRequisitoLocalizacion($arrayParametros);
		
		echo json_encode($requisitos->toArray());
	}
	
	/**
	 * Método de obtención de país por producto y tipo requisito
	 */
	public function obtenerPaisProducto($idProducto, $tipoRequisito){
		$arrayParametros = array('id_producto' => $idProducto,
			'tipo_requisito' => $tipoRequisito);
		
		$paisRequisitos = $this->lNegocioRequisitosComercializacion->obtenerPaisPorProductoTipoRequisito($arrayParametros);
		
		echo json_encode($paisRequisitos->toArray());
	}
	
	/**
	 * Método de obtención subtipo de producto por codificación de subtipo 
	 */
	public function obtenerSubtipoProducto(){
		
		$arrayParametros = array('codificacion_subtipo' => 'PRD_MASCOTA');
		
		$subtipoProducto = $this->lNegocioSubtipoProducto->obtenerSubtipoProductoPorCodificacion($arrayParametros);
		
		echo json_encode($subtipoProducto->toArray());
	}
	
	/**
	 * Método de obtención de productos por identificador de subtipo
	 */
	public function obtenerProductosPorSubtipoProducto($idSubtipoProducto){
		
		$arrayParametros = array('id_subtipo_producto' => $idSubtipoProducto);
		
		$productos = $this->lNegocioProducto->obtenerProductoPorSubtipoProducto($arrayParametros);
		
		echo json_encode($productos->toArray());
	}
	
	/**
	 * Método de obtención de datos generales del producto
	 */
	public function obtenerDatosProductos($idProducto){
		
		$arrayParametros = array('id_producto' => $idProducto);
		
		$datosProductos = $this->lNegocioProducto->obtenerDatosProducto($arrayParametros);
		
		echo json_encode($datosProductos->toArray());
	}
}
