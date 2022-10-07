<?php
/**
 * Controlador Metodos
 *
 * Este archivo controla la lógica del negocio del modelo: MetodosModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2022-02-18
 * @uses MetodosControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\MetodosLogicaNegocio;
use Agrodb\Catalogos\Modelos\MetodosModelo;
use Agrodb\Catalogos\Modelos\RangosLogicaNegocio;

class MetodosControlador extends BaseControlador{

	private $lNegocioMetodos = null;

	private $modeloMetodos = null;
	
	private $lNegocioRangos = null;

	private $accion = null;
	
	private $linea = null;

	private $registroRango = null;
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioMetodos = new MetodosLogicaNegocio();
		$this->modeloMetodos = new MetodosModelo();
		
		$this->lNegocioRangos = new RangosLogicaNegocio();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloMetodos = $this->lNegocioMetodos->buscarMetodos();
		$this->tablaHtmlMetodos($modeloMetodos);
		require APP . 'Catalogos/vistas/listaMetodosVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Metodos";
		require APP . 'Catalogos/vistas/formularioMetodosVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Metodos
	 */
	public function guardar(){
		$_POST['identificador_creacion'] = $this->identificador;
		$_POST['tipo_proceso'] = 'guardar';
		
		$resultado = $this->lNegocioMetodos->validarGuardarMetodo($_POST);
		
		if($resultado['validacion']){
			
			$idMetodo = $this->lNegocioMetodos->guardar($_POST);
			
			$arrayParametros[] = array(
				'id_metodo' => $idMetodo,
				'descripcion' =>  $_POST['descripcion']
			);
			
			$this->linea = $this->imprimirLineaRegistroMetodo($arrayParametros);
		}
		
		echo json_encode(array(
			'estado' => $resultado['estado'],
			'mensaje' => $resultado['mensaje'],
			'linea' => $this->linea
		));
		
		
	}
	
	/**
	 * Método para actualizar el registro en la base de datos - metodo
	 */
	public function actualizar(){
		
		$_POST['fecha_modificacion'] = 'now()';
		$_POST['identificador_modificacion'] = $this->identificador;
		$_POST['tipo_proceso'] = 'actualizar';
		
		$resultado = $this->lNegocioMetodos->validarGuardarMetodo($_POST);
		
		if($resultado['validacion']){
			
			$this->lNegocioMetodos->guardar($_POST);
			
		}
		
		echo json_encode(array(
			'estado' => $resultado['estado'],
			'mensaje' => $resultado['mensaje']
		));
		
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Metodos
	 */
	public function editar(){
		$this->accion = "Detalle de metodos";
		$arrayParametros = array('id_metodo' => $_POST['id_metodo'], 'estado' => 'Activo');
		$this->modeloMetodos = $this->lNegocioMetodos->buscar($arrayParametros['id_metodo']);
		$this->registroRango = $this->imprimirLineaRegistroRango($this->lNegocioRangos->buscarLista($arrayParametros));
		require APP . 'AdministracionProductos/vistas/formularioMetodoRangosVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Metodos
	 */
	public function borrar(){
		$this->lNegocioMetodos->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Metodos
	 */
	public function tablaHtmlMetodos($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_metodo'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos\metodos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_metodo'] . '</b></td>
<td>' . $fila['descripcion'] . '</td>
<td>' . $fila['fecha_creacion'] . '</td>
<td>' . $fila['identificador_creacion'] . '</td>
</tr>');
			}
		}
	}
}
