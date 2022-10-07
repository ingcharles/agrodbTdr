<?php

/**
 * Controlador FirmasElectronicas
 *
 * Este archivo controla la lógica del negocio del modelo:  FirmasElectronicasModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     FirmasElectronicasControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\FirmasElectronicasLogicaNegocio;
use Agrodb\Laboratorios\Modelos\FirmasElectronicasModelo;
use Agrodb\Core\Javabridge;

class FirmasElectronicasControlador extends BaseControlador
{

    private $lNegocioFirmasElectronicas = null;
    private $modeloFirmasElectronicas = null;
    private $accion = null;
    private $cedulaUsuario = null;
    private $estadoFirma = null;
    private $mensajeActivacion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioFirmasElectronicas = new FirmasElectronicasLogicaNegocio();
        $this->modeloFirmasElectronicas = new FirmasElectronicasModelo();
        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    public function token($clave)
    {
        $firmasElectronica = $this->lNegocioFirmasElectronicas->buscarActivarFirma($clave);

        foreach ($firmasElectronica as $fila)
        {
            $this->nombreUsuario = $fila['usuario'];
            $this->estadoFirma = $fila['estado'];
            $this->cedulaUsuario = $fila['identificador'];
        }
        if (!empty($this->cedulaUsuario))
        {
            require APP . 'Laboratorios/vistas/activarFirmasElectronicasVista.php';
        } else
        {
            header('Location: ' . URL);
            throw new \Exception('Se intento ingresar a una Área restringida, para activar la firma electrónica con la siguiente clave:' . $clave);
        }
    }

    /**
     * Registra la contraseña de la firma electrónica desde el aplicativo de java
     * Es necesario ejecutar desde java para luego utilizar el mismo algoritmo para desencriptar y firmar los documentos
     */
    public function activar()
    {
        $java = new Javabridge($_POST["cedula"], "INTERNO");
        //activarFirmaElectronica método ejecutado en java
        $respuesta = $java->exec()->activarFirmaElectronica($_POST["cedula"], $_POST["contrasena1"], "g_laboratorios");

        if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev')
        {
            \ChromePhp::log($respuesta);
            $this->mensajeActivacion = $respuesta;
        } else
        {
            $this->mensajeActivacion = MENSAJE_ACTIVACION;
        }

        require APP . 'Laboratorios/vistas/activarMensajeVista.php';
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloFirmasElectronicas = $this->lNegocioFirmasElectronicas->buscarFirmante();
        $this->tablaHtmlFirmasElectronicas($modeloFirmasElectronicas);
        require APP . 'Laboratorios/vistas/listaFirmasElectronicasVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Firma Electronica";
        require APP . 'Laboratorios/vistas/formularioFirmasElectronicasVista.php';
    }

    /**
     * Método para registrar en la base de datos -FirmasElectronicas
     */
    public function guardar()
    {
        $this->lNegocioFirmasElectronicas->guardar($_POST);
    }

    /**
     * Cambia el estado del firmante
     */
    public function cambiarEstado()
    {

        $this->lNegocioFirmasElectronicas->cambiarEstado($_POST);
    }

    public function reenviarFirma()
    {

        $this->lNegocioFirmasElectronicas->reenviarFirma($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: FirmasElectronicas
     */
    public function editar()
    {
        $this->accion = "Editar Firma Electrónica";
        $this->modeloFirmasElectronicas = $this->lNegocioFirmasElectronicas->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioFirmasElectronicasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - FirmasElectronicas
     */
    public function borrar()
    {
        $this->lNegocioFirmasElectronicas->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - FirmasElectronicas
     */
    public function tablaHtmlFirmasElectronicas($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {

            $btnReenviar = "";
            $valorEstado = '';
            if ($fila['estado'] == 'ACTIVO')
            {
                $valorEstado = ' checked';
            }
            if ($fila['estado'] == 'PENDIENTE DE ACTIVACIÓN')
            {
                $estado = $fila['estado'];
            } else
            {
                $estado = '<input type="checkbox" onclick="cambiarEstado(this,' . $fila['id_firma_electronica'] . ')" ' . $valorEstado . ' />';
            }
            $btnReenviar = "<button class=\"bntGrid far fa-envelope\" onclick=\"fn_reenviar(" . $fila['id_firma_electronica'] . ")\"/>";
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_firma_electronica'] . '"
		  class="item" 
		  draggable="false"
		  >
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['identificador'] . '</b></td>
                  <td>' . $fila['empleado'] . '</td>
                  <td>' . $estado . '</td>
                  <td>' . $btnReenviar . '</td>
                  </tr>'
            );
        }
    }

}
