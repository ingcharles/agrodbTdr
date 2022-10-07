<?php
/**
 * Controlador NotificacionPorPaisAfectado
 *
 * Este archivo controla la lógica del negocio del modelo:  NotificacionPorPaisAfectadoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-09-09
 * @uses    NotificacionPorPaisAfectadoControlador
 * @package NotificacionesFitosanitarias
 * @subpackage Controladores
 */
namespace Agrodb\NotificacionesFitosanitarias\Controladores;

use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionPorPaisAfectadoLogicaNegocio;
use Agrodb\NotificacionesFitosanitarias\Modelos\NotificacionPorPaisAfectadoModelo;

class NotificacionPorPaisAfectadoControlador extends BaseControlador
{

    private $lNegocioNotificacionPorPaisAfectado = null;

    private $modeloNotificacionPorPaisAfectado = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioNotificacionPorPaisAfectado = new NotificacionPorPaisAfectadoLogicaNegocio();
        $this->modeloNotificacionPorPaisAfectado = new NotificacionPorPaisAfectadoModelo();
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
        $modeloNotificacionPorPaisAfectado = $this->lNegocioNotificacionPorPaisAfectado->buscarNotificacionPorPaisAfectado();
        $this->tablaHtmlNotificacionPorPaisAfectado($modeloNotificacionPorPaisAfectado);
        require APP . 'NotificacionesFitosanitarias/vistas/listaNotificacionPorPaisAfectadoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo NotificacionPorPaisAfectado";
        require APP . 'NotificacionesFitosanitarias/vistas/formularioNotificacionPorPaisAfectadoVista.php';
    }

    /**
     * Método para registrar en la base de datos -NotificacionPorPaisAfectado
     */
    public function guardar()
    {
        $this->lNegocioNotificacionPorPaisAfectado->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: NotificacionPorPaisAfectado
     */
    public function editar()
    {
        $this->accion = "Editar NotificacionPorPaisAfectado";
        $this->modeloNotificacionPorPaisAfectado = $this->lNegocioNotificacionPorPaisAfectado->buscar($_POST["id"]);
        require APP . 'NotificacionesFitosanitarias/vistas/formularioNotificacionPorPaisAfectadoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - NotificacionPorPaisAfectado
     */
    public function borrar()
    {
        $this->lNegocioNotificacionPorPaisAfectado->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - NotificacionPorPaisAfectado
     */
    public function tablaHtmlNotificacionPorPaisAfectado($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_notificacion_por_producto'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'NotificacionesFitosanitarias\notificacionporpaisafectado"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_notificacion_por_producto'] . '</b></td>
<td>' . $fila['id_notificacion'] . '</td>
<td>' . $fila['id_localizacion'] . '</td>
<td>' . $fila['nombre_pais'] . '</td>
</tr>'
                );
            }
        }
    }
    
    
}
