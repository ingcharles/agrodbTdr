<?php
/**
 * Controlador OrganizacionEcuestre
 *
 * Este archivo controla la lógica del negocio del modelo:  OrganizacionEcuestreModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-02-15
 * @uses    OrganizacionEcuestreControlador
 * @package PasaporteEquino
 * @subpackage Controladores
 */
namespace Agrodb\PasaporteEquino\Controladores;

use Agrodb\PasaporteEquino\Modelos\OrganizacionEcuestreLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\OrganizacionEcuestreModelo;

class OrganizacionEcuestreControlador extends BaseControlador
{

    private $lNegocioOrganizacionEcuestre = null;

    private $modeloOrganizacionEcuestre = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioOrganizacionEcuestre = new OrganizacionEcuestreLogicaNegocio();
        $this->modeloOrganizacionEcuestre = new OrganizacionEcuestreModelo();
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
        $modeloOrganizacionEcuestre = $this->lNegocioOrganizacionEcuestre->buscarOrganizacionEcuestre();
        $this->tablaHtmlOrganizacionEcuestre($modeloOrganizacionEcuestre);
        require APP . 'PasaporteEquino/vistas/listaOrganizacionEcuestreVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo OrganizacionEcuestre";
        require APP . 'PasaporteEquino/vistas/formularioOrganizacionEcuestreVista.php';
    }

    /**
     * Método para registrar en la base de datos -OrganizacionEcuestre
     */
    public function guardar()
    {
        $this->lNegocioOrganizacionEcuestre->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: OrganizacionEcuestre
     */
    public function editar()
    {
        $this->accion = "Editar OrganizacionEcuestre";
        $this->modeloOrganizacionEcuestre = $this->lNegocioOrganizacionEcuestre->buscar($_POST["id"]);
        require APP . 'PasaporteEquino/vistas/formularioOrganizacionEcuestreVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - OrganizacionEcuestre
     */
    public function borrar()
    {
        $this->lNegocioOrganizacionEcuestre->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - OrganizacionEcuestre
     */
    public function tablaHtmlOrganizacionEcuestre($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_organizacion_ecuestre'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'PasaporteEquino\organizacionecuestre"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_organizacion_ecuestre'] . '</b></td>
<td>' . $fila['fecha_creacion'] . '</td>
<td>' . $fila['identificador_organizacion'] . '</td>
<td>' . $fila['razon_social'] . '</td>
</tr>'
                );
            }
        }
    }
}
