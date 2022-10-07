<?php
/**
 * Controlador PlagasDetalle
 *
 * Este archivo controla la lógica del negocio del modelo: PlagasDetalleModelo y Vistas
 *
 * @author AGROCALIDAD
 * @date   2021-03-24
 * @uses PlagasDetalleControlador
 * @package PlagasLaboratorio
 * @subpackage Controladores
 */
namespace Agrodb\PlagasLaboratorio\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\PlagasLaboratorio\Modelos\PlagasDetalleLogicaNegocio;
use Agrodb\PlagasLaboratorio\Modelos\PlagasDetalleModelo;

class PlagasDetalleControlador extends BaseControlador{

	private $lNegocioPlagasDetalle = null;

	private $modeloPlagasDetalle = null;

	private $accion = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->lNegocioPlagasDetalle = new PlagasDetalleLogicaNegocio();
		$this->modeloPlagasDetalle = new PlagasDetalleModelo();
		set_exception_handler(array(
			$this,
			'manejadorExcepciones'));
	}

	/**
	 * Método de inicio del controlador
	 */
	public function index(){
		$modeloPlagasDetalle = $this->lNegocioPlagasDetalle->buscarPlagasDetalle();
		$this->tablaHtmlPlagasDetalle($modeloPlagasDetalle);
		require APP . 'PlagasLaboratorio/vistas/listaPlagasDetalleVista.php';
	}

	/**
	 * Método para desplegar el formulario vacio
	 */
	public function nuevo(){
		$this->accion = "Nuevo PlagasDetalle";
		require APP . 'PlagasLaboratorio/vistas/formularioPlagasDetalleVista.php';
	}

	/**
	 * Método para registrar en la base de datos -PlagasDetalle
	 */
	public function guardar(){
		$_POST['fecha_creacion'] = 'now()';
		$_POST['identificacion_creacion'] = $this->identificador;
		
		$horaIngreso = substr($_POST['hora_ingreso'], 0, 2);
		$minutosIngreso = substr($_POST['hora_ingreso'], 3, 2);
		
		$fechaIngreso = new \DateTime($_POST['fecha_ingreso']);
		date_time_set($fechaIngreso,$horaIngreso,$minutosIngreso);
		
		$_POST['fecha_ingreso'] = date_format($fechaIngreso, 'Y-m-d H:i:s');
		
		$this->lNegocioPlagasDetalle->guardar($_POST);
		
		$arrayParametros[] = array('identificado_por'=>$_POST['identificado_por'],
								   'nombre_provincia'=>$_POST['nombre_provincia'],
								   'numero_reporte'=>$_POST['numero_reporte'],
								   'fecha_ingreso'=>$_POST['fecha_ingreso']);
		
		$linea = $this->imprimirLineaDetallePlaga($arrayParametros);
		
		echo json_encode(array(
			'estado' => 'exito',
			'mensaje' => Constantes::GUARDADO_CON_EXITO,
			'linea' => $linea
		));
	}

	/**
	 * Obtenemos los datos del registro seleccionado para editar - Tabla: PlagasDetalle
	 */
	public function editar(){
		$this->accion = "Editar PlagasDetalle";
		$this->modeloPlagasDetalle = $this->lNegocioPlagasDetalle->buscar($_POST["id"]);
		require APP . 'PlagasLaboratorio/vistas/formularioPlagasDetalleVista.php';
	}

	/**
	 * Método para borrar un registro en la base de datos - PlagasDetalle
	 */
	public function borrar(){
		$this->lNegocioPlagasDetalle->borrar($_POST['elementos']);
	}

	/**
	 * Construye el código HTML para desplegar la lista de - PlagasDetalle
	 */
	public function tablaHtmlPlagasDetalle($tabla){
		{
			$contador = 0;
			foreach ($tabla as $fila){
				$this->itemsFiltrados[] = array(
					'<tr id="' . $fila['id_plaga_detalle'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'PlagasLaboratorio\plagasdetalle"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_plaga_detalle'] . '</b></td>
<td>' . $fila['id_plaga'] . '</td>
<td>' . $fila['numero_reporte'] . '</td>
<td>' . $fila['id_provincia'] . '</td>
</tr>');
			}
		}
	}
}
