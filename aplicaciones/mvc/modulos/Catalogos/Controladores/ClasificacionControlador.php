<?php
/**
 * Controlador Clasificacion
 *
 * Este archivo controla la lógica del negocio del modelo:  ClasificacionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-06-23
 * @uses    ClasificacionControlador
 * @package AdministracionCatalogosRIA
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\ClasificacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\ClasificacionModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ClasificacionControlador extends BaseControlador
{

    private $lNegocioClasificacion = null;
    private $modeloClasificacion = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioClasificacion = new ClasificacionLogicaNegocio();
        $this->modeloClasificacion = new ClasificacionModelo();
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
        //require APP . 'Catalogos/vistas/listaCatalogosAdministracion.php';
    }
    
    /**
     * Método de inicio del controlador
     */
    public function listarAdministracionClasificacion()
    {
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'Clasificacion/nuevo',
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
        
        $modeloClasificacion = $this->lNegocioClasificacion->buscarClasificacion();
        $this->tablaHtmlClasificacion($modeloClasificacion);
        require APP . 'Catalogos/vistas/listaClasificacionVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Clasificación";
        require APP . 'Catalogos/vistas/formularioClasificacionVista.php';
    }

    /**
     * Método para registrar en la base de datos -Clasificacion
     */
    public function guardar()
    {
        $this->lNegocioClasificacion->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Clasificacion
     */
    public function editar()
    {
        $this->accion = "Editar Clasificación";
        $this->modeloClasificacion = $this->lNegocioClasificacion->buscar($_POST["id"]);
        require APP . 'Catalogos/vistas/formularioClasificacionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Clasificacion
     */
    public function borrar()
    {
        $this->lNegocioClasificacion->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Clasificacion
     */
    public function tablaHtmlClasificacion($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_clasificacion'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/clasificacion"
                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
                		  <td>' . ++ $contador . '</td>
                    <td>' . $fila['clasificacion'] . '</td>
                    <td>' . $fila['estado_clasificacion'] . '</td>
                </tr>'
            );
        }
    }
    
    public function actualizarClasificaciones()
    {
        $modeloClasificacion = $this->lNegocioClasificacion->buscarClasificacion();
        $this->tablaHtmlClasificacion($modeloClasificacion);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }
}
