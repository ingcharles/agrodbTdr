<?php

/**
 * Controlador AuditoriaLabLog
 *
 * Este archivo controla la lógica del negocio del modelo:  AuditoriaLabLogModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     AuditoriaLabLogControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\AuditoriaLabLogLogicaNegocio;
use Agrodb\Laboratorios\Modelos\AuditoriaLabLogModelo;

class AuditoriaLabLogControlador extends BaseControlador
{

    private $lNegocioAuditoriaLabLog = null;
    private $modeloAuditoriaLabLog = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioAuditoriaLabLog = new AuditoriaLabLogLogicaNegocio();
        $this->modeloAuditoriaLabLog = new AuditoriaLabLogModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {

        require APP . 'Laboratorios/vistas/listaAuditoriaLabLogVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo AuditoriaLabLog";
        require APP . 'Laboratorios/vistas/formularioAuditoriaLabLogVista.php';
    }

    /**
     * Método para registrar en la base de datos -AuditoriaLabLog
     */
    public function guardar()
    {
        $this->lNegocioAuditoriaLabLog->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: AuditoriaLabLog
     */
    public function editar()
    {
        $this->accion = "Editar AuditoriaLabLog";
        $this->modeloAuditoriaLabLog = $this->lNegocioAuditoriaLabLog->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioAuditoriaLabLogVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - AuditoriaLabLog
     */
    public function borrar()
    {
        $this->lNegocioAuditoriaLabLog->borrar($_POST['elementos']);
    }

    public function verAuditoria()
    {
        $datosAudIndorme = $this->lNegocioAuditoriaLabLog->buscarAuditoriaInforme($_POST);
        $this->tablaHtmlAuditoriaInforme($datosAudIndorme);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Construye el código HTML para desplegar la lista de - AuditoriaLabLog
     */
    public function tablaHtmlAuditoriaInforme($tabla)
    {
        $contador = 0;
        $operacion = "";
        if (count($tabla) > 0)
        {

            foreach ($tabla as $fila)
            {
                if ($fila->log_operation == 'UPDATE')
                {
                    $operacion = "ACTUALIZADO";
                } if ($fila->log_operation == 'DELETE')
                {
                    $operacion = "BORRADO";
                }
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_informe_analisis . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/AuditoriaLabLog"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $operacion . '</b></td>
                  <td>' . $fila->campo . '</td>
                  <td>' . $fila->anterior . '</td>
                  <td>' . $fila->nuevo . '</td>
                  <td>' . $fila->log_when . '</td>
                  <td> ' . $fila->usuario_apl . ' </td>
                  </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

}
