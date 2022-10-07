<?php

/**
 * Controlador Proformas
 *
 * Este archivo controla la lógica del negocio del modelo:  ProformasModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     ProformasControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\ProformasLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ProformasModelo;

class ProformasControlador extends BaseControlador
{

    private $lNegocioProformas = null;
    private $modeloProformas = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioProformas = new ProformasLogicaNegocio();
        $this->modeloProformas = new ProformasModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloProformas = $this->lNegocioProformas->buscarProformas();
        $this->tablaHtmlProformas($modeloProformas);
        require APP . 'Laboratorios/vistas/listaProformasVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Proformas";
        require APP . 'Laboratorios/vistas/formularioProformasVista.php';
    }

    /**
     * Método para registrar en la base de datos -Proformas
     */
    public function guardar()
    {
        $_POST['identificador_usuario'] = parent::usuarioActivo();
        $_POST['usuario_interno'] = $this->usuarioInterno;
        $_POST['identificador'] = $_POST['ci_ruc']; //para la columna identificador de la tabla personas
        $idProforma = $this->lNegocioProformas->guardar($_POST);
         $proforma  = new \Agrodb\Laboratorios\Controladores\BandejaInformesControlador();
         $proforma->descargarProforma($idProforma);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Proformas
     */
    public function editar()
    {
        $this->accion = "Editar Proformas";
        $this->modeloProformas = $this->lNegocioProformas->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioProformasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Proformas
     */
    public function borrar()
    {
        $this->lNegocioProformas->borrar($_POST['elementos']);
    }

    /**
     * Muestra el modal de proforma
     */
    public function vistaProforma()
    {
        require APP . 'Laboratorios/vistas/formularioProformasVista.php';
    }

    /**
     * Construye el código HTML para desplegar la lista de - Proformas
     */
    public function tablaHtmlProformas($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_proforma'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/Proformas"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_proforma'] . '</b></td>
<td>'
                . $fila['id_persona'] . '</td>
<td>' . $fila['codigo']
                . '</td>
<td>' . $fila['nom_laboratorio'] . '</td>
</tr>');
        }
    }

}
