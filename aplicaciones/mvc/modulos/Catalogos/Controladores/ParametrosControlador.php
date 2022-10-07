<?php
/**
 * Controlador Parametros
 *
 * Este archivo controla la lógica del negocio del modelo: ParametrosModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2022-02-18
 * @uses ParametrosControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\ParametrosLogicaNegocio;
use Agrodb\Catalogos\Modelos\ParametrosModelo;
use Agrodb\Catalogos\Modelos\MetodosLogicaNegocio;

class ParametrosControlador extends BaseControlador{

	private $lNegocioParametros = null;

	private $modeloParametros = null;
	
	private $lNegocioMetodos = null;

	private $accion = null;
	
	private $linea = null;
	
	private $registroMetodo = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioParametros = new ParametrosLogicaNegocio();
		$this->modeloParametros = new ParametrosModelo();
		
		$this->lNegocioMetodos = new MetodosLogicaNegocio();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloParametros = $this->lNegocioParametros->buscarParametros();
		$this->tablaHtmlParametros($modeloParametros);
		require APP . 'Catalogos/vistas/listaParametrosVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Parametros";
		require APP . 'Catalogos/vistas/formularioParametrosVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Parametros
	 */
	public function guardar(){
		$_POST['identificador_creacion'] = $this->identificador;
		$_POST['tipo_proceso'] = 'guardar';
		
		$resultado = $this->lNegocioParametros->validarGuardarParametro($_POST);
		
		if($resultado['validacion']){
			
			$idParametro = $this->lNegocioParametros->guardar($_POST);
			
			$arrayParametros[] = array(
				'id_parametro' => $idParametro,
				'descripcion' =>  $_POST['descripcion']
			);
			
			$this->linea = $this->imprimirLineaRegistroParametro($arrayParametros);
		}
		
		echo json_encode(array(
			'estado' => $resultado['estado'],
			'mensaje' => $resultado['mensaje'],
			'linea' => $this->linea
		));
		
	}
	
	/**
	 * Método para actualizar el registro en la base de datos - producto
	 */
	public function actualizar(){
		
		$_POST['fecha_modificacion'] = 'now()';
		$_POST['identificador_modificacion'] = $this->identificador;
		$_POST['tipo_proceso'] = 'actualizar';
		
		$resultado = $this->lNegocioParametros->validarGuardarParametro($_POST);
		
		if($resultado['validacion']){

			$this->lNegocioParametros->guardar($_POST);
			
		}
		
		echo json_encode(array(
			'estado' => $resultado['estado'],
			'mensaje' => $resultado['mensaje']
		));
		
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Parametros
	 */
	public function editar(){
		$this->accion = "Detalle de parametros";
		$arrayParametros = array('id_parametro' => $_POST['id_parametro'], 'estado' => 'Activo');
		$this->modeloParametros = $this->lNegocioParametros->buscar($arrayParametros['id_parametro']);
		$this->registroMetodo = $this->imprimirLineaRegistroMetodo($this->lNegocioMetodos->buscarLista($arrayParametros));
		require APP . 'AdministracionProductos/vistas/formularioParametroMetodosVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Parametros
	 */
	public function borrar(){
		$this->lNegocioParametros->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Parametros
	 */
	public function tablaHtmlParametros($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_parametro'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos\parametros"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_parametro'] . '</b></td>
<td>' . $fila['descripcion'] . '</td>
<td>' . $fila['fecha_creacion'] . '</td>
<td>' . $fila['identificador_creacion'] . '</td>
</tr>');
			}
		}
	}
}
