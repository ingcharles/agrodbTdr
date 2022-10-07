<?php

/**
 * Controlador Detallesproformas
 *
 * Este archivo controla la lógica del negocio del modelo:  DetallesproformasModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     DetallesproformasControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\DetallesproformasLogicaNegocio;
use Agrodb\Laboratorios\Modelos\DetallesproformasModelo;

class DetallesproformasControlador extends BaseControlador
{

    private $lNegocioDetallesproformas = null;
    private $modeloDetallesproformas = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDetallesproformas = new DetallesproformasLogicaNegocio();
        $this->modeloDetallesproformas = new DetallesproformasModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloDetallesproformas = $this->lNegocioDetallesproformas->buscarDetallesproformas();
        $this->tablaHtmlDetallesproformas($modeloDetallesproformas);
        require APP . 'Laboratorios/vistas/listaDetallesproformasVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Detallesproformas";
        require APP . 'Laboratorios/vistas/formularioDetallesproformasVista.php';
    }

    /**
     * Método para registrar en la base de datos -Detallesproformas
     */
    public function guardar()
    {
        $this->lNegocioDetallesproformas->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Detallesproformas
     */
    public function editar()
    {
        $this->accion = "Editar Detallesproformas";
        $this->modeloDetallesproformas = $this->lNegocioDetallesproformas->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioDetallesproformasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Detallesproformas
     */
    public function borrar()
    {
        $this->lNegocioDetallesproformas->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Detallesproformas
     */
    public function tablaHtmlDetallesproformas($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_detalle_proforma'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/Detallesproformas"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_detalle_proforma'] . '</b></td>
<td>'
                . $fila['id_proforma'] . '</td>
<td>' . $fila['nom_servicio']
                . '</td>
<td>' . $fila['cantidad'] . '</td>
</tr>');
        }
    }

}
