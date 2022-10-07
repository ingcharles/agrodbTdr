<?php
/**
 * Controlador UnidadesMedidas
 *
 * Este archivo controla la lógica del negocio del modelo:  UnidadesMedidasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-06-23
 * @uses    UnidadesMedidasControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\UnidadesMedidasLogicaNegocio;
use Agrodb\Catalogos\Modelos\UnidadesMedidasModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class UnidadesMedidasControlador extends BaseControlador
{

    private $lNegocioUnidadesMedidas = null;
    private $modeloUnidadesMedidas = null;

    private $accion = null;
    private $listaBotones = null;

    private $formulario = null;
    private $unidadEditable = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioUnidadesMedidas = new UnidadesMedidasLogicaNegocio();
        $this->modeloUnidadesMedidas = new UnidadesMedidasModelo();
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
        // require APP . 'Catalogos/vistas/listaUnidadesMedidasVista.php';
    }

    /**
     * Método de inicio del controlador
     */
    public function listarAdministracionUnidadesMedidas()
    {
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'UnidadesMedidas/nuevo',
                'ruta' => URL_MVC_FOLDER . 'Catalogos',
                'descripcion' => 'Nuevo'
            ),
            array(
                'estilo' => '_actualizar',
                'pagina' => '',
                'ruta' => '',
                'descripcion' => 'Actualizar'
            ),
            array(
                'estilo' => '_seleccionar',
                'pagina' => '',
                'ruta' => '',
                'descripcion' => 'Seleccionar'
            )
        );

        $this->listaBotones = $this->crearAccionBotonesListadoItems($opciones);

        $modeloUnidadesMedidas = $this->lNegocioUnidadesMedidas->buscarUnidadesMedidas();
        $this->tablaHtmlUnidadesMedidas($modeloUnidadesMedidas);
        require APP . 'Catalogos/vistas/listaUnidadesMedidasVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Unidad de Medida";
        $this->unidadEditable = 'Editable';
        
        require APP . 'Catalogos/vistas/formularioUnidadesMedidasVista.php';
    }

    /**
     * Método para registrar en la base de datos -UnidadesMedidas
     */
    public function guardar()
    {
        $this->lNegocioUnidadesMedidas->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: UnidadesMedidas
     */
    public function editar()
    {
        $this->accion = "Editar Unidad de Medida";
        $this->modeloUnidadesMedidas = $this->lNegocioUnidadesMedidas->buscar($_POST["id"]);

        if ($this->modeloUnidadesMedidas->getClasificacion() == 'CRIA_UMED') {
            $this->unidadEditable = 'Editable';
        } else {
            $this->unidadEditable = 'NoEditable';
        }

        require APP . 'Catalogos/vistas/formularioUnidadesMedidasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - UnidadesMedidas
     */
    public function borrar()
    {
        $this->lNegocioUnidadesMedidas->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - UnidadesMedidas
     */
    public function tablaHtmlUnidadesMedidas($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_unidad_medida'] . '"
                	  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/unidadesMedidas"
                	  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                	  data-destino="detalleItem">
                	<td>' . ++ $contador . '</td>
                	<td style="white - space:nowrap; "><b>' . $fila['nombre'] . '</b> ('. $fila['codigo'] .')</td>
                    <td>' . $fila['estado'] . '</td>
                    <td>' . ($fila['clasificacion']=='CRIA_UMED'?'Editable':'No editable') . '</td>
                </tr>'
            );
        }
    }

    public function actualizarUnidadesMedidas()
    {        
        $modeloUnidadesMedidas = $this->lNegocioUnidadesMedidas->buscarUnidadesMedidas();
        $this->tablaHtmlUnidadesMedidas($modeloUnidadesMedidas);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();         
    }
}
