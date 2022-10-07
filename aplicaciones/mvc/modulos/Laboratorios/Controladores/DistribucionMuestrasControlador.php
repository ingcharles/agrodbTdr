<?php

/**
 * Controlador DistribucionMuestras
 *
 * Este archivo controla la lógica del negocio del modelo:  DistribucionMuestrasModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     DistribucionMuestrasControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\DistribucionMuestrasLogicaNegocio;
use Agrodb\Laboratorios\Modelos\DistribucionMuestrasModelo;
use Agrodb\Laboratorios\Modelos\LaboratoriosModelo;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;

class DistribucionMuestrasControlador extends BaseControlador
{

    private $lNegocioDistribucionMuestras = null;
    private $modeloDistribucionMuestras = null;
    private $accion = null;
    private $modeloLaboratorios = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDistribucionMuestras = new DistribucionMuestrasLogicaNegocio();
        $this->modeloDistribucionMuestras = new DistribucionMuestrasModelo();
        $this->modeloLaboratorios = new LaboratoriosModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        require APP . 'Laboratorios/vistas/listaDistribucionMuestrasVista.php';
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatos()
    {
        
        
        $arrayParametros = array('idDireccion' => $_POST['fDireccion'], 'idLaboratorio' => $_POST['fLaboratorio'], 'idServicio' => $_POST['fServicio'],'id_laboratorios_provincia'=>$_POST['fLaboratorios_provincia']);
        $resultado = $this->lNegocioDistribucionMuestras->buscarDistribucionMuestras($arrayParametros);
        $this->tablaHtmlDistribucionMuestras($resultado);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Para buscar y construir html de la distribución de las muestras
     */
    public function buscar()
    {
        $modeloDistribucionMuestras = $this->lNegocioDistribucionMuestras->buscarDistribucionMuestras();
        $this->tablaHtmlDistribucionMuestras($modeloDistribucionMuestras);
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Distribución Muestras";
        require APP . 'Laboratorios/vistas/formularioDistribucionMuestrasVista.php';
    }

    /**
     * Método para registrar en la base de datos -DistribucionMuestras
     */
    public function guardar()
    {
        $this->lNegocioDistribucionMuestras->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: DistribucionMuestras
     */
    public function editar()
    {
        $this->accion = "Editar Distribución de Muestras";
        $this->modeloDistribucionMuestras = $this->lNegocioDistribucionMuestras->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioDistribucionMuestrasVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DistribucionMuestras
     */
    public function borrar()
    {
        $this->lNegocioDistribucionMuestras->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - DistribucionMuestras
     */
    public function tablaHtmlDistribucionMuestras($tabla)
    {
        $contador = 0;
        if (count($tabla) > 0)
        {
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_distribucion_muestra . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/DistribucionMuestras"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
                  <td>' . $fila->rama_nombre . '</td>
                  <td>' . $fila->provincia_laboratorio . " ($fila->tipo)" . '</td>
                  <td>' . $fila->provincia_muestra . '</td>
                  <td>' . $fila->estado_registro . '</td>
                  </tr>');
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar</td></tr>");
        }
    }

}
