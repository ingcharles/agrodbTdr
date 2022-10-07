<?php
/**
 * Controlador Rangos
 *
 * Este archivo controla la lógica del negocio del modelo: RangosModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2022-02-18
 * @uses RangosControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\RangosLogicaNegocio;
use Agrodb\Catalogos\Modelos\RangosModelo;

class RangosControlador extends BaseControlador{

	private $lNegocioRangos = null;

	private $modeloRangos = null;

	private $accion = null;
	
	private $linea = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioRangos = new RangosLogicaNegocio();
		$this->modeloRangos = new RangosModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloRangos = $this->lNegocioRangos->buscarRangos();
		$this->tablaHtmlRangos($modeloRangos);
		require APP . 'Catalogos/vistas/listaRangosVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Rangos";
		require APP . 'Catalogos/vistas/formularioRangosVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Rangos
	 */
	public function guardar(){
		$_POST['identificador_creacion'] = $this->identificador;
		$_POST['tipo_proceso'] = 'guardar';
		
		$resultado = $this->lNegocioRangos->validarGuardarRango($_POST);
		
		if($resultado['validacion']){
			
			$idRango = $this->lNegocioRangos->guardar($_POST);
			
			$arrayParametros[] = array(
				'id_rango' => $idRango,
				'descripcion' =>  $_POST['descripcion']
			);
			
			$this->linea = $this->imprimirLineaRegistroRango($arrayParametros);
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
		
		$resultado = $this->lNegocioRangos->validarGuardarRango($_POST);
		
		if($resultado['validacion']){
			
			$this->lNegocioRangos->guardar($_POST);
			
		}
		
		echo json_encode(array(
			'estado' => $resultado['estado'],
			'mensaje' => $resultado['mensaje']
		));
		
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Rangos
	 */
	public function editar(){
		$this->accion = "Detalle de rangos";
		$arrayParametros = array('id_rango' => $_POST['id_rango'], 'estado' => 'Activo');
		$this->modeloRangos = $this->lNegocioRangos->buscar($arrayParametros['id_rango']);
		require APP . 'AdministracionProductos/vistas/formularioRangosVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Rangos
	 */
	public function borrar(){
		$this->lNegocioRangos->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Rangos
	 */
	public function tablaHtmlRangos($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_rango'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos\rangos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_rango'] . '</b></td>
<td>' . $fila['descripcion'] . '</td>
<td>' . $fila['fecha_creacion'] . '</td>
<td>' . $fila['identificador_creacion'] . '</td>
</tr>');
			}
		}
	}
}
