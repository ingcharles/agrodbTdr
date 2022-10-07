<?php
 /**
 * Controlador SeguimientosCuarentenarios
 *
 * Este archivo controla la lógica del negocio del modelo:  SeguimientosCuarentenariosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022/02/02
 * @uses    SeguimientosCuarentenariosControlador
 * @package SeguimientoCuarentenario
 * @subpackage Controladores
 */
 namespace Agrodb\SeguimientoCuarentenario\Controladores;
 use Agrodb\SeguimientoCuarentenario\Modelos\SeguimientosCuarentenariosLogicaNegocio;
 use Agrodb\SeguimientoCuarentenario\Modelos\SeguimientosCuarentenariosModelo;
 
class SeguimientosCuarentenariosControlador extends BaseControlador 
{

		 private $lNegocioSeguimientosCuarentenarios = null;
		 private $modeloSeguimientosCuarentenarios = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioSeguimientosCuarentenarios = new SeguimientosCuarentenariosLogicaNegocio();
		 $this->modeloSeguimientosCuarentenarios = new SeguimientosCuarentenariosModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloSeguimientosCuarentenarios = $this->lNegocioSeguimientosCuarentenarios->buscarSeguimientosCuarentenarios();
		 $this->tablaHtmlSeguimientosCuarentenarios($modeloSeguimientosCuarentenarios);
		 require APP . 'SeguimientoCuarentenario/vistas/listaSeguimientosCuarentenariosVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo SeguimientosCuarentenarios"; 
		 require APP . 'SeguimientoCuarentenario/vistas/formularioSeguimientosCuarentenariosVista.php';
		}	/**
		* Método para registrar en la base de datos -SeguimientosCuarentenarios
		*/
		public function guardar()
		{
		  $this->lNegocioSeguimientosCuarentenarios->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: SeguimientosCuarentenarios
		*/
		public function editar()
		{
		 $this->accion = "Editar SeguimientosCuarentenarios"; 
		 $this->modeloSeguimientosCuarentenarios = $this->lNegocioSeguimientosCuarentenarios->buscar($_POST["id"]);
		 require APP . 'SeguimientoCuarentenario/vistas/formularioSeguimientosCuarentenariosVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - SeguimientosCuarentenarios
		*/
		public function borrar()
		{
		  $this->lNegocioSeguimientosCuarentenarios->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - SeguimientosCuarentenarios
		*/
		 public function tablaHtmlSeguimientosCuarentenarios($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_seguimiento_cuarentenario'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'SeguimientoCuarentenario\seguimientoscuarentenarios"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_seguimiento_cuarentenario'] . '</b></td>
<td>'
		  . $fila['id_destinacion_aduanera'] . '</td>
<td>' . $fila['estado']
		  . '</td>
<td>' . $fila['numero_seguimientos'] . '</td>
</tr>');
		}
		}
	}

}
