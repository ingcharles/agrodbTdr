<?php
/**
 * Controlador Ingreso
 *
 * Este archivo controla la lógica del negocio del modelo:  IngresoModelo y  Vistas
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses     IngresoControlador
 * @package auditoria
 * @subpackage Controladores
 */
namespace Agrodb\Auditoria\Controladores;

use Agrodb\Auditoria\Modelos\IngresoLogicaNegocio;
use Agrodb\Auditoria\Modelos\IngresoModelo;

class IngresoControlador extends BaseControlador
{

    private $lNegocioIngreso = null;

    private $modeloIngreso = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioIngreso = new IngresoLogicaNegocio();
        $this->modeloIngreso = new IngresoModelo();
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
        $modeloIngreso = $this->lNegocioIngreso->buscarIngreso();
        $this->tablaHtmlIngreso($modeloIngreso);
        require APP . 'auditoria/vistas/listaIngresoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Ingreso";
        require APP . 'auditoria/vistas/formularioIngresoVista.php';
    }

    /**
     * Método para registrar en la base de datos -Ingreso
     */
    public function guardar()
    {
        $this->lNegocioIngreso->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Ingreso
     */
    public function editar()
    {
        $this->accion = "Editar Ingreso";
        $this->modeloIngreso = $this->lNegocioIngreso->buscar($_POST["id"]);
        require APP . 'auditoria/vistas/formularioIngresoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Ingreso
     */
    public function borrar()
    {
        $this->lNegocioIngreso->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Ingreso
     */
    public function tablaHtmlIngreso($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_ingreso'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'auditoria\ingreso"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_ingreso'] . '</b></td>
<td>' . $fila['id_log'] . '</td>
<td>' . $fila['identificador'] . '</td>
<td>' . $fila['fecha_inicio'] . '</td>
</tr>'
                );
            }
        }
    }
}
