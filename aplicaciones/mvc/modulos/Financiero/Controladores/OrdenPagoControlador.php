<?php
/**
 * Controlador OrdenPago
 *
 * Este archivo controla la lógica del negocio del modelo:  OrdenPagoModelo y  Vistas
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses     OrdenPagoControlador
 * @package financiero
 * @subpackage Controladores
 */
namespace Agrodb\Financiero\Controladores;

use Agrodb\Financiero\Modelos\OrdenPagoLogicaNegocio;
use Agrodb\Financiero\Modelos\OrdenPagoModelo;

class OrdenPagoControlador extends BaseControlador
{

    private $lNegocioOrdenPago = null;

    private $modeloOrdenPago = null;

    private $accion = null;
    
    //public $datosFacturasUsuario = null;  //Facturas del usuario por servicio.

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioOrdenPago = new OrdenPagoLogicaNegocio();
        $this->modeloOrdenPago = new OrdenPagoModelo();
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
        require APP . 'Financiero/vistas/listaFacturaUsuarioVista.php';
    }
    
    /**
     * Método para desplegar las facturas generadas por usuario.
     */
    public function listarFacturasUsuarios()
    {
        $tipoSolicitud = $_POST['tipoSolicitud'];
        $numeroFactura = $_POST['numeroFactura'];
        $numeroOrdenGuia = $_POST['numeroOrdenGuia'];
        $numeroSolicitud = $_POST['numeroSolicitud'];
        $numeroOrdenVue = $_POST['numeroOrdenVue'];
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFin = $_POST['fechaFin'];
        $identificador = $_SESSION['usuario'];
        
        $arrayParametros = array(
            'identificador' => $identificador,
            'tipo_solicitud' => $tipoSolicitud,
            'numero_factura' => $numeroFactura, 
            'numero_solicitud' => $numeroOrdenGuia,
            'id_vue' => $numeroSolicitud,
            'numero_orden_vue' => $numeroOrdenVue,
            'fecha_facturacion' => array($fechaInicio,$fechaFin)
        );
        
        $datosFacturas = $this->lNegocioOrdenPago->buscarFacturasUsuario($arrayParametros);
        $this->tablaHtmlFacturasUsuario($datosFacturas);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo OrdenPago";
        //require APP . 'financiero/vistas/formularioOrdenPagoVista.php';
    }

    /**
     * Método para registrar en la base de datos -OrdenPago
     */
    public function guardar()
    {
        $this->lNegocioOrdenPago->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: OrdenPago
     */
    public function facturasUsuario()
    {
        $this->accion = "Facturas";
        $this->modeloOrdenPago = $this->lNegocioOrdenPago->buscarFacturaPorIdentificador($_POST["id"]);
        require APP . 'Financiero/vistas/facturaUsuarioVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - OrdenPago
     */
    public function borrar()
    {
        $this->lNegocioOrdenPago->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - OrdenPago
     */
    public function tablaHtmlFacturasUsuario($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_pago'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Financiero\ordenPago"
                		  data-opcion="facturasUsuario" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
                		  <td>' . ++ $contador . '</td>
                		  <td style="white - space:nowrap; "><b>' . $fila['numero_solicitud'] . '</b></td>
                          <td>' . $fila['numero_factura'] . '</td>
                          <td>' . $fila['total_pagar'] . '</td>
                          <td>' . $fila['fecha_facturacion'] . '</td>
                    </tr>'
                );
            }
        }
    }
}
