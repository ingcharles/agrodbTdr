<?php
/**
 * Controlador TipoComponente
 *
 * Este archivo controla la lógica del negocio del modelo:  TipoComponenteModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-06-23
 * @uses    TipoComponenteControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\TipoComponenteLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoComponenteModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class TipoComponenteControlador extends BaseControlador
{

    private $lNegocioTipoComponente = null;
    private $modeloTipoComponente = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioTipoComponente = new TipoComponenteLogicaNegocio();
        $this->modeloTipoComponente = new TipoComponenteModelo();
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
    public function listarAdministracionTipoComponente()
    {
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'TipoComponente/nuevo',
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
        
        $modeloTipoComponente = $this->lNegocioTipoComponente->buscarTipoComponente();
        $this->tablaHtmlTipoComponente($modeloTipoComponente);
        require APP . 'Catalogos/vistas/listaTipoComponenteVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Tipo de Componente";
        require APP . 'Catalogos/vistas/formularioTipoComponenteVista.php';
    }

    /**
     * Método para registrar en la base de datos -TipoComponente
     */
    public function guardar()
    {
        $this->lNegocioTipoComponente->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: TipoComponente
     */
    public function editar()
    {
        $this->accion = "Editar Tipo de Componente";
        $this->modeloTipoComponente = $this->lNegocioTipoComponente->buscar($_POST["id"]);
        require APP . 'Catalogos/vistas/formularioTipoComponenteVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - TipoComponente
     */
    public function borrar()
    {
        $this->lNegocioTipoComponente->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - TipoComponente
     */
    public function tablaHtmlTipoComponente($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_tipo_componente'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/tipoComponente"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['tipo_componente'] . '</b></td>
                    <td>' . $fila['id_area'] . '</td>
                    <td>' . $fila['estado_tipo_componente'] . '</td>
                </tr>'
                );
        }
    }
    
    public function actualizarTipoComponente()
    {
        $modeloTipoComponente = $this->lNegocioTipoComponente->buscarTipoComponente();
        $this->tablaHtmlTipoComponente($modeloTipoComponente);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }
}