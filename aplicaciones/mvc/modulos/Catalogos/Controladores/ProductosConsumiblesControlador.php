<?php
/**
 * Controlador ProductosConsumibles
 *
 * Este archivo controla la lógica del negocio del modelo:  ProductosConsumiblesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-06-23
 * @uses    ProductosConsumiblesControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\ProductosConsumiblesLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosConsumiblesModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ProductosConsumiblesControlador extends BaseControlador
{

    private $lNegocioProductosConsumibles = null;
    private $modeloProductosConsumibles = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioProductosConsumibles = new ProductosConsumiblesLogicaNegocio();
        $this->modeloProductosConsumibles = new ProductosConsumiblesModelo();
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
    public function listarAdministracionProductosConsumibles()
    {
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'ProductosConsumibles/nuevo',
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
        
        $modeloProductosConsumibles = $this->lNegocioProductosConsumibles->buscarProductosConsumibles();
        $this->tablaHtmlProductosConsumibles($modeloProductosConsumibles);
        require APP . 'Catalogos/vistas/listaProductosConsumiblesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Producto Consumible";
        require APP . 'Catalogos/vistas/formularioProductosConsumiblesVista.php';
    }

    /**
     * Método para registrar en la base de datos -ProductosConsumibles
     */
    public function guardar()
    {
        $this->lNegocioProductosConsumibles->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ProductosConsumibles
     */
    public function editar()
    {
        $this->accion = "Editar Producto Consumible";
        $this->modeloProductosConsumibles = $this->lNegocioProductosConsumibles->buscar($_POST["id"]);
        require APP . 'Catalogos/vistas/formularioProductosConsumiblesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ProductosConsumibles
     */
    public function borrar()
    {
        $this->lNegocioProductosConsumibles->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ProductosConsumibles
     */
    public function tablaHtmlProductosConsumibles($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_producto_consumible'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/ProductosConsumibles"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['producto_consumible'] . '</b></td>
                    <td>' . $fila['estado_producto_consumible'] . '</td>
                </tr>'
            );
        }
    }
    
    public function actualizarProductosConsumibles()
    {
        $modeloProductosConsumibles = $this->lNegocioProductosConsumibles->buscarProductosConsumibles();
        $this->tablaHtmlProductosConsumibles($modeloProductosConsumibles);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }
}