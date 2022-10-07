<?php
/**
 * Controlador AnexosPecuarios
 *
 * Este archivo controla la lógica del negocio del modelo:  AnexosPecuariosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-06-23
 * @uses    AnexosPecuariosControlador
 * @package Catalogos
 * @subpackage Controladores
 */
namespace Agrodb\Catalogos\Controladores;

use Agrodb\Catalogos\Modelos\AnexosPecuariosLogicaNegocio;
use Agrodb\Catalogos\Modelos\AnexosPecuariosModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class AnexosPecuariosControlador extends BaseControlador
{

    private $lNegocioAnexosPecuarios = null;
    private $modeloAnexosPecuarios = null;

    private $accion = null;
    private $listaBotones = null;
    
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioAnexosPecuarios = new AnexosPecuariosLogicaNegocio();
        $this->modeloAnexosPecuarios = new AnexosPecuariosModelo();
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
    public function listarAdministracionAnexosPecuarios()
    {
        $opciones = array(
            array(
                'estilo' => '_nuevo',
                'pagina' => 'AnexosPecuarios/nuevo',
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
        
        $modeloAnexosPecuarios = $this->lNegocioAnexosPecuarios->buscarAnexosPecuarios();
        $this->tablaHtmlAnexosPecuarios($modeloAnexosPecuarios);
        require APP . 'Catalogos/vistas/listaAnexosPecuariosVista.php';
    }
    

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Anexo Pecuario";
        require APP . 'Catalogos/vistas/formularioAnexosPecuariosVista.php';
    }

    /**
     * Método para registrar en la base de datos -AnexosPecuarios
     */
    public function guardar()
    {
        $this->lNegocioAnexosPecuarios->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: AnexosPecuarios
     */
    public function editar()
    {
        $this->accion = "Editar Anexo Pecuario";
        $this->modeloAnexosPecuarios = $this->lNegocioAnexosPecuarios->buscar($_POST["id"]);
        require APP . 'Catalogos/vistas/formularioAnexosPecuariosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - AnexosPecuarios
     */
    public function borrar()
    {
        $this->lNegocioAnexosPecuarios->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - AnexosPecuarios
     */
    public function tablaHtmlAnexosPecuarios($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_anexo_pecuario'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Catalogos/AnexosPecuarios"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['grupo_producto'] . '</b></td>
                    <td>' . $fila['proceso_revision'] . '</td>
                    <td>' . $fila['anexo_pecuario'] . '</td>
                    <td>' . $fila['estado_anexo_pecuario'] . '</td>
                </tr>'
            );
        }
    }
    
    public function actualizarAnexosPecuarios()
    {
        $modeloAnexosPecuarios = $this->lNegocioAnexosPecuarios->buscarAnexosPecuarios();
        $this->tablaHtmlAnexosPecuarios($modeloAnexosPecuarios);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }
}