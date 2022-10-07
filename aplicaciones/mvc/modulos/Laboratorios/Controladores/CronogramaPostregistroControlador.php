<?php

/**
 * Controlador CronogramaPostregistro
 *
 * Este archivo controla la lógica del negocio del modelo:  CronogramaPostregistroModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     CronogramaPostregistroControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\CronogramaPostregistroLogicaNegocio;
use Agrodb\Laboratorios\Modelos\CronogramaPostregistroModelo;

class CronogramaPostregistroControlador extends BaseControlador
{

    private $lNegocioCronogramaPostregistro = null;
    private $modeloCronogramaPostregistro = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioCronogramaPostregistro = new CronogramaPostregistroLogicaNegocio();
        $this->modeloCronogramaPostregistro = new CronogramaPostregistroModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Laboratorios/vistas/listaCronogramaPostregistroVista.php';
    }

    /**
     * Muestra los registros guardados
     * @param type $anio
     */
    public function filtrar($anio)
    {
        $modeloCronogramaPostregistro = $this->lNegocioCronogramaPostregistro->buscarCronogramaPostregistro($anio, $this->laboratorioUsuario());
        $this->tablaHtmlCronogramaPostregistro($modeloCronogramaPostregistro);
        require APP . 'Laboratorios/vistas/listaCronogramaPostregistroVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Cronograma Postregistro";
        require APP . 'Laboratorios/vistas/formularioCronogramaPostregistroVista.php';
    }

    /**
     * Método para registrar en la base de datos -CronogramaPostregistro
     */
    public function guardar()
    {
        $this->lNegocioCronogramaPostregistro->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: CronogramaPostregistro
     */
    public function editar()
    {
        $this->accion = "Editar CronogramaPostregistro";
        $this->modeloCronogramaPostregistro = $this->lNegocioCronogramaPostregistro->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioCronogramaPostregistroVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - CronogramaPostregistro
     */
    public function borrar()
    {
        $this->lNegocioCronogramaPostregistro->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - CronogramaPostregistro
     */
    public function tablaHtmlCronogramaPostregistro($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_cronograma_postregistro'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/CronogramaPostregistro"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['anio'] . '</b></td>
                  <td>' . $fila['fecha_inicio'] . '</td>
                  <td>' . $fila['fecha_fin'] . '</td>
                  <td>' . $fila['estado_registro'] . '</td>
                  </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }

}
