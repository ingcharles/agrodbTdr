<?php
/**
 * Controlador ViaAdministracion
 *
 * Este archivo controla la lógica del negocio del modelo:  ViaAdministracionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-06-23
 * @uses    ViaAdministracionControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\ViaAdministracionLogicaNegocio;
use Agrodb\Catalogos\Modelos\ViaAdministracionModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ViaAdministracionControlador extends BaseControlador
{

    private $lNegocioViaAdministracion = null;
    private $modeloViaAdministracion = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioViaAdministracion = new ViaAdministracionLogicaNegocio();
        $this->modeloViaAdministracion = new ViaAdministracionModelo();
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
        
    }
    
    /**
     * Método de inicio del controlador
     */
    public function listarAdministracionViaAdministracion()
    {
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'ViaAdministracion/nuevo',
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
        
        $modeloViaAdministracion = $this->lNegocioViaAdministracion->buscarViaAdministracion();
        $this->tablaHtmlViaAdministracion($modeloViaAdministracion);
        require APP . 'Catalogos/vistas/listaViaAdministracionVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Vía de Administración";
        require APP . 'Catalogos/vistas/formularioViaAdministracionVista.php';
    }

    /**
     * Método para registrar en la base de datos -ViaAdministracion
     */
    public function guardar()
    {
        $this->lNegocioViaAdministracion->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ViaAdministracion
     */
    public function editar()
    {
        $this->accion = "Editar Vía de Administración";
        $this->modeloViaAdministracion = $this->lNegocioViaAdministracion->buscar($_POST["id"]);
        require APP . 'Catalogos/vistas/formularioViaAdministracionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ViaAdministracion
     */
    public function borrar()
    {
        $this->lNegocioViaAdministracion->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ViaAdministracion
     */
    public function tablaHtmlViaAdministracion($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_via_administracion'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/ViaAdministracion"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['via_administracion'] . '</b></td>
                    <td>' . $fila['estado_via_administracion'] . '</td>
                </tr>'
            );
        }
    }
    
    public function actualizarViaAdministracion()
    {
        $modeloViaAdministracion = $this->lNegocioViaAdministracion->buscarViaAdministracion();
        $this->tablaHtmlViaAdministracion($modeloViaAdministracion);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }
}