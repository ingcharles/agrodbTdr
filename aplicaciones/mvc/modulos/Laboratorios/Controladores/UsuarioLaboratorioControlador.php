<?php

/**
 * Controlador UsuarioLaboratorio
 *
 * Este archivo controla la lógica del negocio del modelo:  UsuarioLaboratorioModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     UsuarioLaboratorioControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\UsuarioLaboratorioLogicaNegocio;
use Agrodb\Laboratorios\Modelos\UsuarioLaboratorioModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class UsuarioLaboratorioControlador extends BaseControlador
{

    private $lNegocioUsuarioLaboratorio = null;
    private $modeloUsuarioLaboratorio = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioUsuarioLaboratorio = new UsuarioLaboratorioLogicaNegocio();
        $this->modeloUsuarioLaboratorio = new UsuarioLaboratorioModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Laboratorios/vistas/listaUsuarioLaboratorioVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Usuario Laboratorio";
        require APP . 'Laboratorios/vistas/formularioUsuarioLaboratorioVista.php';
    }

    /**
     * Método para registrar en la base de datos -UsuarioLaboratorio
     */
    public function guardar()
    {
        $this->lNegocioUsuarioLaboratorio->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: UsuarioLaboratorio
     */
    public function editar()
    {
        $this->accion = "Editar Usuario Laboratorio";
        $this->modeloUsuarioLaboratorio = $this->lNegocioUsuarioLaboratorio->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioUsuarioLaboratorioVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - UsuarioLaboratorio
     */
    public function borrar()
    {
        $this->lNegocioUsuarioLaboratorio->borrar($_POST['elementos']);
    }

    /**
     * Búsqueda por filtro
     */
    public function buscarDatos()
    {
        $arrayParametros = array(
            'fDireccion' => $_POST['fDireccion'],
            'fLaboratorio' => $_POST['fLaboratorio'],
            'fUsuario' => $_POST['fUsuario'],
            'fidLaboratoriosProvincia' => $_POST['fidLaboratoriosProvincia']
        );
        $modeloUsuarioLaboratorio = $this->lNegocioUsuarioLaboratorio->buscarUsuarioLaboratorio($arrayParametros);
        $this->tablaHtmlUsuarioLaboratorio($modeloUsuarioLaboratorio);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Buscar los perfiles de los usuarios
     * @param type $idUsuario
     * @param type $perfil
     */
    public function buscarUsuarioPerfiles($idUsuario)
    {
        $usuarioLab = "No existe el usuario buscado";
        $combo = $this->usuarioPerfiles($idUsuario);
        $perfiles = "<option value=''>Seleccionar...</option>";
        foreach ($combo as $item)
        {
            $perfiles .= '<option value="' . $item['perfil'] . '">' . $item['perfil'] . '</option>';
            $usuarioLab = $item['usuario'];
        }
        echo json_encode(array('nombre' => $usuarioLab, 'perfil' => $perfiles));
    }

    /**
     * Construye el código HTML para desplegar la lista de - UsuarioLaboratorio
     */
    public function tablaHtmlUsuarioLaboratorio($tabla)
    {
        $contador = 0;
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_usuario_laboratorio . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/UsuarioLaboratorio"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                    <td>' . ++$contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila->direccion . '</b></td>
                    <td>' . $fila->laboratorio . '</td>
                    <td>' . $fila->usuario . '</td>
                    <td>' . $fila->perfil . '</td>
                    <td>' . $fila->prov_laboratorio . '</td>
                    <td>' . $fila->estado . '</td>
                </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

}
