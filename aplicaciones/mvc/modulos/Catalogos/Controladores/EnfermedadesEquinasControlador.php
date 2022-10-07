<?php
/**
 * Controlador EnfermedadesEquinas
 *
 * Este archivo controla la lógica del negocio del modelo:  EnfermedadesEquinasModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-03-07
 * @uses    EnfermedadesEquinasControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\EnfermedadesEquinasLogicaNegocio;
use Agrodb\Catalogos\Modelos\EnfermedadesEquinasModelo;

class EnfermedadesEquinasControlador extends BaseControlador
{

    private $lNegocioEnfermedadesEquinas = null;

    private $modeloEnfermedadesEquinas = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioEnfermedadesEquinas = new EnfermedadesEquinasLogicaNegocio();
        $this->modeloEnfermedadesEquinas = new EnfermedadesEquinasModelo();
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
        $modeloEnfermedadesEquinas = $this->lNegocioEnfermedadesEquinas->buscarEnfermedadesEquinas();
        $this->tablaHtmlEnfermedadesEquinas($modeloEnfermedadesEquinas);
        require APP . 'Catalogos/vistas/listaEnfermedadesEquinasVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo EnfermedadesEquinas";
        require APP . 'Catalogos/vistas/formularioEnfermedadesEquinasVista.php';
    }

    /**
     * Método para registrar en la base de datos -EnfermedadesEquinas
     */
    public function guardar()
    {
        $this->lNegocioEnfermedadesEquinas->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: EnfermedadesEquinas
     */
    public function editar()
    {
        $this->accion = "Editar EnfermedadesEquinas";
        $this->modeloEnfermedadesEquinas = $this->lNegocioEnfermedadesEquinas->buscar($_POST["id"]);
        require APP . 'Catalogos/vistas/formularioEnfermedadesEquinasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - EnfermedadesEquinas
     */
    public function borrar()
    {
        $this->lNegocioEnfermedadesEquinas->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - EnfermedadesEquinas
     */
    public function tablaHtmlEnfermedadesEquinas($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_enfermedad_equino'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos\enfermedadesequinas"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_enfermedad_equino'] . '</b></td>
<td>' . $fila['nombre_enfermedad'] . '</td>
<td>' . $fila['estado_enfermedad_equino'] . '</td>
<td>' . $fila['id_enfermedad_equino'] . '</td>
</tr>'
                );
            }
        }
    }
}
