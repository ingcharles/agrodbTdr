<?php
/**
 * Controlador CategoriaToxicologica
 *
 * Este archivo controla la lógica del negocio del modelo:  CategoriaToxicologicaModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-06-23
 * @uses    CategoriaToxicologicaControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\CategoriaToxicologicaLogicaNegocio;
use Agrodb\Catalogos\Modelos\CategoriaToxicologicaModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class CategoriaToxicologicaControlador extends BaseControlador
{

    private $lNegocioCategoriaToxicologica = null;
    private $modeloCategoriaToxicologica = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioCategoriaToxicologica = new CategoriaToxicologicaLogicaNegocio();
        $this->modeloCategoriaToxicologica = new CategoriaToxicologicaModelo();
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
        
    }
    
    /**
     * Método de inicio del controlador
     */
    public function listarAdministracionCategoriaToxicologica()
    {
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'CategoriaToxicologica/nuevo',
                'ruta' => URL_MVC_FOLDER . 'Catalogos',
                'descripcion' => 'Nuevo'
            ),
            array(
                'estilo' => '_actualizar',
                'pagina' => '',
                'ruta' => '',
                'descripcion' => 'Actualizar'
            ),
            array(
                'estilo' => '_seleccionar',
                'pagina' => '',
                'ruta' => '',
                'descripcion' => 'Seleccionar'
            )
        );
        
        $this->listaBotones = $this->crearAccionBotonesListadoItems($opciones);
        
        $modeloCategoriaToxicologica = $this->lNegocioCategoriaToxicologica->buscarCategoriaToxicologica();
        $this->tablaHtmlCategoriaToxicologica($modeloCategoriaToxicologica);
        require APP . 'Catalogos/vistas/listaCategoriaToxicologicaVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Categoría Toxicológica";
        require APP . 'Catalogos/vistas/formularioCategoriaToxicologicaVista.php';
    }

    /**
     * Método para registrar en la base de datos -CategoriaToxicologica
     */
    public function guardar()
    {
        $this->lNegocioCategoriaToxicologica->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: CategoriaToxicologica
     */
    public function editar()
    {
        $this->accion = "Editar Categoría Toxicológica";
        $this->modeloCategoriaToxicologica = $this->lNegocioCategoriaToxicologica->buscar($_POST["id"]);
        require APP . 'Catalogos/vistas/formularioCategoriaToxicologicaVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - CategoriaToxicologica
     */
    public function borrar()
    {
        $this->lNegocioCategoriaToxicologica->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - CategoriaToxicologica
     */
    public function tablaHtmlCategoriaToxicologica($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_categoria_toxicologica'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/CategoriaToxicologica"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
                	<td>' . ++ $contador . '</td>
                	<td style="white - space:nowrap; "><b>' . $fila['categoria_toxicologica'] . '</b></td>
                    <td>' . $fila['id_area'] . '</td>
                    <td>' . $fila['estado_categoria_toxicologica'] . '</td>
                </tr>'
            );
        }
    }
    
    public function actualizarCategoriaToxicologica()
    {
        $modeloCategoriaToxicologica = $this->lNegocioCategoriaToxicologica->buscarCategoriaToxicologica();
        $this->tablaHtmlCategoriaToxicologica($modeloCategoriaToxicologica);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }
}