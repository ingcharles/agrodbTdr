<?php
/**
 * Controlador Productos
 *
 * Este archivo controla la lógica del negocio del modelo: ProductosModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2022-02-18
 * @uses ProductosControlador
 * @package AdministracionProductos
 * @subpackage Controladores
 */
namespace Agrodb\AdministracionProductos\Controladores;

use Agrodb\Catalogos\Modelos\TipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoProductosModelo;

class AdministracionProductosControlador extends BaseControlador{

	private $lNegocioTipoProductos = null;
	
	private $modeloTipoProductos = null;
	
	private $accion = null;

	private $article = null;
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioTipoProductos = new TipoProductosLogicaNegocio();
		$this->modeloTipoProductos = new TipoProductosModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function laboratorios(){
		
		$arrayParametros = array(
			'id_area' => 'LT'
		);
		
		$tipoProducto = $this->lNegocioTipoProductos->buscarLista($arrayParametros);
		$this->articuloHtmlFormulario($tipoProducto);
		require APP . 'AdministracionProductos/vistas/listaTipoProductosVista.php';
	}
	
	public function nuevo(){
		$this->accion = "Nuevo Tipo producto";
		require APP . 'AdministracionProductos/vistas/formularioTipoProductosVista.php';
	}
	
	public function articuloHtmlFormulario($datos) {
		
		$contador = 0;
		$this->article = "";
		
		foreach ($datos as $fila) {
			
			$this->article .= '<article
									id="' .  $fila['id_tipo_producto'] . '"
									class="item"
									data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/TipoProductos"
									data-opcion="editar"
									ondragstart="drag(event)"
									draggable="true"
									data-destino="detalleItem">
										<span class="ordinal">'.++$contador.'</span>
										<span>'.(strlen($fila['nombre'])>45?(substr($fila['nombre'],0,45).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'Sin asunto')).'</span>
										<aside><small>Laboratorios Tumbaco</small></aside>
								</article>';
		}
	}
}
