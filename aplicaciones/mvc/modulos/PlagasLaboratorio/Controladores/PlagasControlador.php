<?php
/**
 * Controlador Plagas
 *
 * Este archivo controla la lógica del negocio del modelo: PlagasModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-03-24
 * @uses PlagasControlador
 * @package PlagasLaboratorio
 * @subpackage Controladores
 */
namespace Agrodb\PlagasLaboratorio\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\PlagasLaboratorio\Modelos\PlagasLogicaNegocio;
use Agrodb\PlagasLaboratorio\Modelos\PlagasModelo;
use Agrodb\PlagasLaboratorio\Modelos\PlagasDetalleLogicaNegocio;

class PlagasControlador extends BaseControlador{

	private $lNegocioPlagas = null;

	private $modeloPlagas = null;
	
	private $lNegocioPlagasDetalle = null;

	private $accion = null;
	
	private $registroPlagasDetalle = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioPlagas = new PlagasLogicaNegocio();
		$this->modeloPlagas = new PlagasModelo();
		$this->lNegocioPlagasDetalle = new PlagasDetalleLogicaNegocio();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloPlagas = $this->lNegocioPlagas->buscarPlagas();
		$this->tablaHtmlPlagas($modeloPlagas);
		require APP . 'PlagasLaboratorio/vistas/listaPlagasVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo Plagas";
		require APP . 'PlagasLaboratorio/vistas/formularioPlagasVista.php';
	}
	
	/**
	 * Método para desplegar el formulario vacio
	 */
	public function abrir(){
		$this->accion = "Registro de resultados";
		$arrayParametros = array('id_plaga' => $_POST["id_plaga"]);
		$this->modeloPlagas = $this->lNegocioPlagas->buscar($arrayParametros['id_plaga']);
		$this->registroDetallePlagas = $this->imprimirLineaDetallePlaga($this->lNegocioPlagasDetalle->buscarLista($arrayParametros));
		require APP . 'PlagasLaboratorio/vistas/formularioPlagasDetallePlagasVista.php';
	}

	/**
	 * Método para registrar en la base de datos -Plagas
	 */
	public function guardar(){
		$_POST['fecha_creacion'] = 'now()';
		$_POST['identificacion_creacion'] = $this->identificador;
		$idPlaga = $this->lNegocioPlagas->guardar($_POST);
		
		$arrayParametros[] = array(
			'id_plaga' => $idPlaga,
			'nombre_cientifico' =>  $_POST['nombre_cientifico']);
		
		$linea = $this->imprimirLineaPlaga($arrayParametros);
		
		echo json_encode(array(
			'estado' => 'exito',
			'mensaje' => Constantes::GUARDADO_CON_EXITO,
			'linea' => $linea
		));
	}
	
	/**
	 * Método para registrar en la base de datos -Plagas
	 */
	public function actualizar(){
		$_POST['fecha_modificacion'] = 'now()';
		$_POST['identificacion_modificacion'] = $this->identificador;
		$this->lNegocioPlagas->guardar($_POST);
		Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: Plagas
	 */
	public function editar(){
		$this->accion = "Editar Plagas";
		$this->modeloPlagas = $this->lNegocioPlagas->buscar($_POST["id"]);
		require APP . 'PlagasLaboratorio/vistas/formularioPlagasVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - Plagas
	 */
	public function borrar(){
		$this->lNegocioPlagas->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - Plagas
	 */
	public function tablaHtmlPlagas($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array('
						<tr id="' . $fila['id_plaga'] . '" class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'PlagasLaboratorio\plagas"
						  	data-opcion="editar" ondragstart="drag(event)" draggable="true" data-destino="detalleItem">
						  	<td>' . ++ $contador . '</td>
						  	<td style="white - space:nowrap; "><b>' . $fila['id_plaga'] . '</b></td>
						  	<td>' . $fila['id_cultivo'] . '</td>
							<td>' . $fila['familia'] . '</td>
							<td>' . $fila['nombre_cientifico'] . '</td>
						</tr>');
			}
		}
	}
}
