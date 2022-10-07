<?php
/**
 * Controlador TipoInspector
 *
 * Este archivo controla la lógica del negocio del modelo:  TipoInspectorModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2018-11-21
 * @uses    TipoInspectorControlador
 * @package CentrosFaenamiento
 * @subpackage Controladores
 */
namespace Agrodb\CentrosFaenamiento\Controladores;

use Agrodb\CentrosFaenamiento\Modelos\TipoInspectorLogicaNegocio;
use Agrodb\CentrosFaenamiento\Modelos\TipoInspectorModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class TipoInspectorControlador extends BaseControlador
{

    private $lNegocioTipoInspector = null;

    private $modeloTipoInspector = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioTipoInspector = new TipoInspectorLogicaNegocio();
        $this->modeloTipoInspector = new TipoInspectorModelo();
        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $this->cargarPanelCentroFaenamiento();
        require APP . 'CentrosFaenamiento/vistas/listarTipoInspectorVista.php';
    }

    /**
     * Método para registrar en la base de datos -TipoInspector
     */
    public function guardar()
    {
        $this->lNegocioTipoInspector->guardar($_POST);
        
        if($_POST['resultado'] == 'Registrado'){
        	$arrayParametros = array(
        		'codPerfil' => 'PFL_APM_CF_OPA',
        		'codAplicacion' => 'PRG_A_P_MORTE_CF',
        		'identificador_operador' => $_POST['identificador_operador']
        	);
        	$this->lNegocioTipoInspector->agregarAplicacionesXCodigoAplicacion($arrayParametros);
	        $this->lNegocioTipoInspector->agregarUsuariosXPerfil ($arrayParametros);
        }
        
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: TipoInspector
     */
    public function editar()
    {
        $this->accion = "Editar Tipo Inspector";

        $identificadorOperador = $_POST["id"];

        $arrayParametros = array(
            'identificador_operador' => $identificadorOperador,
            'tipo_inspector' => Constantes::tipo_inspector()->AUXILIAR
        );

        $datosTipoInspector = $this->lNegocioTipoInspector->buscarAuxiliares($arrayParametros);
        $this->modeloTipoInspector->setOptions((array) $datosTipoInspector->current());
        require APP . 'CentrosFaenamiento/vistas/formularioTipoInspectorVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - TipoInspector
     */
    /*public function borrar()
    {
        $this->lNegocioTipoInspector->borrar($_POST['elementos']);
    }*/

    /**
     * Construye el código HTML para desplegar la lista de - TipoInspector
     */
    public function tablaHtmlTipoInspector($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {

                $codigo = $fila['identificador_operador'];

                $this->itemsFiltrados[] = array(
                    '<tr id="' . $codigo . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CentrosFaenamiento\tipoInspector"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['identificador_operador'] . '</b></td>			
			<td>' . $fila['nombre_operador'] . '</td>
			<td>' . $fila['provincia'] . '</td>
			</tr>'
                );
            }
        }
    }

    /**
     * Método para listar los centros de faenamiento por identificador del operador
     */
    public function listarTipoInspector()
    {
        $identificadorOperador = $_POST['identificador_operador'];
        $arrayParametros = array('identificador_operador' => $identificadorOperador);
        $tipoInspector = $this->lNegocioTipoInspector->obtenerDatosOperadorPorIdentificador($arrayParametros);
        $this->tablaHtmlTipoInspector($tipoInspector);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }
}
