<?php
 /**
 * Controlador CodigosCompSupl
 *
 * Este archivo controla la lógica del negocio del modelo:  CodigosCompSuplModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-07-21
 * @uses    CodigosCompSuplControlador
 * @package Catalogos
 * @subpackage Controladores
 */
 namespace Agrodb\Catalogos\Controladores;
 use Agrodb\Catalogos\Modelos\CodigosCompSuplLogicaNegocio;
 use Agrodb\Catalogos\Modelos\CodigosCompSuplModelo;
 
class CodigosCompSuplControlador extends BaseControlador 
{

		 private $lNegocioCodigosCompSupl = null;
		 private $modeloCodigosCompSupl = null;
		 private $accion = null;
	/**
		* Constructor
		*/
		 function __construct()
		{
		parent::__construct();
		 $this->lNegocioCodigosCompSupl = new CodigosCompSuplLogicaNegocio();
		 $this->modeloCodigosCompSupl = new CodigosCompSuplModelo();
		 set_exception_handler(array($this, 'manejadorExcepciones'));
		}	/**
		* Método de inicio del controlador
		*/
		public function index()
		{
		 $modeloCodigosCompSupl = $this->lNegocioCodigosCompSupl->buscarCodigosCompSupl();
		 $this->tablaHtmlCodigosCompSupl($modeloCodigosCompSupl);
		 require APP . 'Catalogos/vistas/listaCodigosCompSuplVista.php';
		}	/**
		* Método para desplegar el formulario vacio
		*/
		public function nuevo()
		{
		 $this->accion = "Nuevo CodigosCompSupl"; 
		 require APP . 'Catalogos/vistas/formularioCodigosCompSuplVista.php';
		}	/**
		* Método para registrar en la base de datos -CodigosCompSupl
		*/
		public function guardar()
		{
		  $this->lNegocioCodigosCompSupl->guardar($_POST);
		}	/**
		*Obtenemos los datos del registro seleccionado para editar - Tabla: CodigosCompSupl
		*/
		public function editar()
		{
		 $this->accion = "Editar CodigosCompSupl"; 
		 $this->modeloCodigosCompSupl = $this->lNegocioCodigosCompSupl->buscar($_POST["id"]);
		 require APP . 'Catalogos/vistas/formularioCodigosCompSuplVista.php';
		}	/**
		* Método para borrar un registro en la base de datos - CodigosCompSupl
		*/
		public function borrar()
		{
		  $this->lNegocioCodigosCompSupl->borrar($_POST['elementos']);
		}	/**
		* Construye el código HTML para desplegar la lista de - CodigosCompSupl
		*/
		 public function tablaHtmlCodigosCompSupl($tabla) {
		{
		 $contador = 0;
		  foreach ($tabla as $fila) {
		   $this->itemsFiltrados[] = array(
		  '<tr id="' . $fila['id_codigo_comp_supl'] . '"
		  class="item" data-rutaAplicacion="'.URL_MVC_FOLDER.'Catalogos\codigoscompsupl"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_codigo_comp_supl'] . '</b></td>
<td>'
		  . $fila['fecha_creacion'] . '</td>
<td>' . $fila['id_partida_arancelaria']
		  . '</td>
<td>' . $fila['codigo_complementario'] . '</td>
</tr>');
		}
		}
	}
	/**
	 * Método para obtener loc codigos complementarios suplementarios
	 */
	public function obtenerCodigosSuplemetariosComplementariosPorIdPartida()
	{
	    $comboComplementarioSuplementario = "";
	    $qCodigo = $this->lNegocioCodigosCompSupl->buscarLista(array('id_partida_arancelaria' => $_POST['id_partida_arancelaria'], 'estado' => 'activo'));
	    
	    $comboComplementarioSuplementario = '<option value="">Seleccionar....</option>';
	    
	    foreach ($qCodigo as $codigo){
	        $comboComplementarioSuplementario .= '<option value="' . $codigo->id_codigo_comp_supl . '" data-codigocomplementario="' . $codigo->codigo_complementario . '" data-codigosuplementario="' . $codigo->codigo_suplementario . '" >' . $codigo->codigo_complementario . $codigo->codigo_suplementario . '</option>';
        }
        
        echo json_encode(array(
            'estado' => 'EXITO',
            'comboComplementarioSuplementario' => $comboComplementarioSuplementario
        ));
        
	}	/**
	* Construye el código HTML para desplegar la lista de - CodigosCompSupl
	*/

}
