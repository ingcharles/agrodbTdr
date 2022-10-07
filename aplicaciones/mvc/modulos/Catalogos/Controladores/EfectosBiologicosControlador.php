<?php
/**
 * Controlador EfectosBiologicos
 *
 * Este archivo controla la lógica del negocio del modelo:  EfectosBiologicosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-06-23
 * @uses    EfectosBiologicosControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\EfectosBiologicosLogicaNegocio;
use Agrodb\Catalogos\Modelos\EfectosBiologicosModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class EfectosBiologicosControlador extends BaseControlador
{

    private $lNegocioEfectosBiologicos = null;
    private $modeloEfectosBiologicos = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioEfectosBiologicos = new EfectosBiologicosLogicaNegocio();
        $this->modeloEfectosBiologicos = new EfectosBiologicosModelo();
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
    public function listarAdministracionEfectosBiologicos()
    {
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'EfectosBiologicos/nuevo',
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
        
        $modeloEfectosBiologicos = $this->lNegocioEfectosBiologicos->buscarEfectosBiologicos();
        $this->tablaHtmlEfectosBiologicos($modeloEfectosBiologicos);
        require APP . 'Catalogos/vistas/listaEfectosBiologicosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Efecto Biológico no deseado";
        require APP . 'Catalogos/vistas/formularioEfectosBiologicosVista.php';
    }

    /**
     * Método para registrar en la base de datos -EfectosBiologicos
     */
    public function guardar()
    {
        $this->lNegocioEfectosBiologicos->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: EfectosBiologicos
     */
    public function editar()
    {
        $this->accion = "Editar Efecto Biológico no deseado";
        $this->modeloEfectosBiologicos = $this->lNegocioEfectosBiologicos->buscar($_POST["id"]);
        require APP . 'Catalogos/vistas/formularioEfectosBiologicosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - EfectosBiologicos
     */
    public function borrar()
    {
        $this->lNegocioEfectosBiologicos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - EfectosBiologicos
     */
    public function tablaHtmlEfectosBiologicos($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_efecto_biologico'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/efectosBiologicos"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['efecto_biologico'] . '</b></td>
                    <td>' . $fila['estado_efecto_biologico'] . '</td>
                </tr>'
            );
        }
    }
    
    public function actualizarEfectosBiologicos()
    {
        $modeloEfectosBiologicos = $this->lNegocioEfectosBiologicos->buscarEfectosBiologicos();
        $this->tablaHtmlEfectosBiologicos($modeloEfectosBiologicos);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }
}