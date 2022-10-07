<?php

/**
 * Controlador UsuariosSolicitud
 *
 * Este archivo controla la lógica del negocio del modelo:  UsuariosSolicitudModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     UsuariosSolicitudControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\UsuariosSolicitudLogicaNegocio;
use Agrodb\Laboratorios\Modelos\UsuariosSolicitudModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class UsuariosSolicitudControlador extends BaseControlador
{

    private $lNegocioUsuariosSolicitud = null;
    private $modeloUsuariosSolicitud = null;
    private $accion = null;
    public $itemsFiltrados = array();

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioUsuariosSolicitud = new UsuariosSolicitudLogicaNegocio();
        $this->modeloUsuariosSolicitud = new UsuariosSolicitudModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Laboratorios/vistas/listaUsuariosSolicitudVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $idSolicitud = $_POST['elementos'];
        $this->accion = "Usuarios de la Solicitud";
        $arrayParametros = array();
        if (!empty($idSolicitud))
            $arrayParametros['id_solicitud'] = $idSolicitud;
        $this->modeloUsuariosSolicitud->setIdSolicitud($idSolicitud);
        $buscaUsuariosSolicitud = $this->lNegocioUsuariosSolicitud->buscarUsuariosSolicitud($arrayParametros);
        $this->tablaHtmlUsuariosSolicitud($buscaUsuariosSolicitud);
        require APP . 'Laboratorios/vistas/formularioUsuariosSolicitudVista.php';
    }

    /**
     * Buscar el usuario interno para agregar en tabla usuarios_solicitud
     * @param String $idUsuarios identificador del usuario a buscar
     */
    public function buscarUsuarios($idUsuarios = null)
    {
        if ($idUsuarios != "")
        {
            $buscaUsuarios = $this->lNegocioUsuariosSolicitud->buscarUsuarios($idUsuarios, $_SESSION['idAplicacion']);
            $existe = 'NO';
            $nombre = '';
            $mensaje = Constantes::INF_USUARIO_SOLICITUD; //sms si el usuario no está habilitado para usar Laboratorios
            $fila = $buscaUsuarios->current();
            if (!empty($fila->identificador))
            {
                $existe = 'SI';
                $nombre = $fila->usuarios;
            }
            echo json_encode(array('existe' => $existe, 'nombre' => $nombre, 'mensaje' => $mensaje));
        }
    }

    /**
     * Método para registrar en la base de datos -UsuariosSolicitud
     */
    public function guardar()
    {
        $_POST['tipo'] = Constantes::tipo_US()->RESPALDO;
        $this->lNegocioUsuariosSolicitud->guardar($_POST);
        $idSolicitud = $_POST['id_solicitud'];
        $this->accion = "Usuarios de la Solicitud";
        $arrayParametros = array();
        if (!empty($idSolicitud))
            $arrayParametros['id_solicitud'] = $idSolicitud;
        $this->modeloUsuariosSolicitud->setIdSolicitud($idSolicitud);
        $buscaUsuariosSolicitud = $this->lNegocioUsuariosSolicitud->buscarUsuariosSolicitud($arrayParametros);
        $this->tablaHtmlUsuariosSolicitud($buscaUsuariosSolicitud);
        require APP . 'Laboratorios/vistas/formularioUsuariosSolicitudVista.php';
    }

    /**
     * Despliega el formulario con los datos para editar
     */
    public function editar()
    {
        $this->accion = "Usuarios de la Solicitud";
        $this->modeloUsuariosSolicitud = $this->lNegocioUsuariosSolicitud->buscar($_POST["id"]);
        $idSolicitud = $this->modeloUsuariosSolicitud->getIdSolicitud();
        $arrayParametros = array();
        if (!empty($idSolicitud))
            $arrayParametros['id_solicitud'] = $idSolicitud;
        $this->modeloUsuariosSolicitud->setIdSolicitud($idSolicitud);
        $buscaUsuariosSolicitud = $this->lNegocioUsuariosSolicitud->buscarUsuariosSolicitud($arrayParametros);
        $this->tablaHtmlUsuariosSolicitud($buscaUsuariosSolicitud);
        require APP . 'Laboratorios/vistas/formularioUsuariosSolicitudVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - UsuariosSolicitud
     */
    public function borrar($id)
    {
        $this->modeloUsuariosSolicitud = $this->lNegocioUsuariosSolicitud->buscar($id);
        $this->lNegocioUsuariosSolicitud->borrar($id);
        $idSolicitud = $this->modeloUsuariosSolicitud->getIdSolicitud();
        $arrayParametros = array();
        if (!empty($idSolicitud))
            $arrayParametros['id_solicitud'] = $idSolicitud;
        $this->modeloUsuariosSolicitud->setIdSolicitud($idSolicitud);
        $buscaUsuariosSolicitud = $this->lNegocioUsuariosSolicitud->buscarUsuariosSolicitud($arrayParametros);
        $this->tablaHtmlUsuariosSolicitud($buscaUsuariosSolicitud);
        echo $this->itemsFiltrados;
    }

    /**
     * Construye el código HTML para desplegar la lista de - UsuariosSolicitud
     */
    public function tablaHtmlUsuariosSolicitud($tabla)
    {
        $contador = 0;
        $this->itemsFiltrados = "";
        foreach ($tabla as $fila)
        {
            $eliminar = "";
            $classItem = "";
            if ($fila->tipo == Constantes::tipo_US()->RESPALDO)
            {
                $eliminar = '<button type ="button" class="far fa-trash-alt" onclick="eliminar(' . $fila->id_usuarios_solicitud . ')"></button>';
                $classItem = "item";
            }
            $this->itemsFiltrados.= '<tr id="' . $fila->id_usuarios_solicitud . '"
		  class="' . $classItem . '" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/UsuariosSolicitud"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila->nombre . ' ' . $fila->apellido . '</b></td>
                  <td>' . $fila->tipo . '</td>
                  <td>' . $fila->fecha_inicio . '</td>
                  <td>' . $fila->fecha_fin . '</td>
                  <td>' . $fila->provincia . '</td>
                  <td>' . $fila->estado . '</td>
                  <td>' . $eliminar . '</td>
                  </tr>';
        }
    }

}
