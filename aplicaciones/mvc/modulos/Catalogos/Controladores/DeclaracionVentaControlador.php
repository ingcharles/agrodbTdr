<?php
/**
 * Controlador DeclaracionVenta
 *
 * Este archivo controla la lógica del negocio del modelo:  DeclaracionVentaModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-06-23
 * @uses    DeclaracionVentaControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\DeclaracionVentaLogicaNegocio;
use Agrodb\Catalogos\Modelos\DeclaracionVentaModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class DeclaracionVentaControlador extends BaseControlador
{

    private $lNegocioDeclaracionVenta = null;
    private $modeloDeclaracionVenta = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDeclaracionVenta = new DeclaracionVentaLogicaNegocio();
        $this->modeloDeclaracionVenta = new DeclaracionVentaModelo();
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
    public function listarAdministracionDeclaracionVenta()
    {
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'DeclaracionVenta/nuevo',
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
        
        $modeloDeclaracionVenta = $this->lNegocioDeclaracionVenta->buscarDeclaracionVenta();
        $this->tablaHtmlDeclaracionVenta($modeloDeclaracionVenta);
        require APP . 'Catalogos/vistas/listaDeclaracionVentaVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Declaración de Venta";
        require APP . 'Catalogos/vistas/formularioDeclaracionVentaVista.php';
    }

    /**
     * Método para registrar en la base de datos -DeclaracionVenta
     */
    public function guardar()
    {
        $this->lNegocioDeclaracionVenta->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: DeclaracionVenta
     */
    public function editar()
    {
        $this->accion = "Editar Declaración de Venta";
        $this->modeloDeclaracionVenta = $this->lNegocioDeclaracionVenta->buscar($_POST["id"]);
        require APP . 'Catalogos/vistas/formularioDeclaracionVentaVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DeclaracionVenta
     */
    public function borrar()
    {
        $this->lNegocioDeclaracionVenta->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - DeclaracionVenta
     */
    public function tablaHtmlDeclaracionVenta($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_declaracion_venta'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/DeclaracionVenta"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['declaracion_venta'] . '</b></td>
                    <<td>' . $fila['estado_declaracion_venta'] . '</td>
                </tr>'
            );
        }
    }
    
    public function actualizarDeclaracionVenta()
    {
        $modeloDeclaracionVenta = $this->lNegocioDeclaracionVenta->buscarDeclaracionVenta();
        $this->tablaHtmlDeclaracionVenta($modeloDeclaracionVenta);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }
}