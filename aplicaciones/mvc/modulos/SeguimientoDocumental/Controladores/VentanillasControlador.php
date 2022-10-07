<?php
/**
 * Controlador Ventanillas
 *
 * Este archivo controla la lógica del negocio del modelo:  VentanillasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-02-13
 * @uses    VentanillasControlador
 * @package SeguimientoDocumental
 * @subpackage Controladores
 */
namespace Agrodb\SeguimientoDocumental\Controladores;

use Agrodb\SeguimientoDocumental\Modelos\VentanillasLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\VentanillasModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class VentanillasControlador extends BaseControlador
{

    private $lNegocioVentanillas = null;
    private $modeloVentanillas = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioVentanillas = new VentanillasLogicaNegocio();
        $this->modeloVentanillas = new VentanillasModelo();
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
        require APP . 'SeguimientoDocumental/vistas/listaOpcionesAdministracion.php';
    }

    /**
     * Método de inicio del controlador
     */
    public function listarAdministracionVentana()
    {
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'ventanillas/nuevo',
                'ruta' => URL_MVC_FOLDER . 'SeguimientoDocumental',
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
        $modeloVentanillas = $this->lNegocioVentanillas->buscarVentanillasDatos();
        $this->tablaHtmlVentanillas($modeloVentanillas);
        require APP . 'SeguimientoDocumental/vistas/listaVentanillasVista.php';
    }
    
    public function actualizarVentanillas()
    {
        $modeloVentanillas = $this->lNegocioVentanillas->buscarVentanillasDatos();
        $this->tablaHtmlVentanillas($modeloVentanillas);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Ventanilla";
        $this->formulario = 'nuevo';
        require APP . 'SeguimientoDocumental/vistas/formularioVentanillasVista.php';
    }

    /**
     * Método para registrar en la base de datos -Ventanillas
     */
    public function guardar()
    {
        $this->lNegocioVentanillas->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Ventanillas
     */
    public function editar()
    {
        $this->accion = "Editar Ventanillas";
        $this->formulario = 'abrir';
        $this->modeloVentanillas = $this->lNegocioVentanillas->buscar($_POST["id"]);
        require APP . 'SeguimientoDocumental/vistas/formularioVentanillasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Ventanillas
     */
    public function borrar()
    {
        $this->lNegocioVentanillas->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Ventanillas
     */
    public function tablaHtmlVentanillas($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_ventanilla'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'SeguimientoDocumental\ventanillas"
                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
                	<td>' . ++ $contador . '</td>
                	<td style="white - space:nowrap; "><b>' . $fila['nombre'] . '</b></td>
                    <td>' . $fila['codigo_ventanilla'] . '</td>                        
                    <td>' . $fila['unidad_destino'] . '</td>
                    <td>' . $fila['provincia'] . '</td>
					<td>' . $fila['estado_ventanilla'] . '</td>
                </tr>'
            );
        }
    }
}
