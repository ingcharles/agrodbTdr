<?php
/**
 * Controlador Inventarios
 *
 * Este archivo controla la lógica del negocio del modelo:  InventarioModelo y  Vistas
 *
 * @author Agrocalidad
 * @uses     InventariosControlador
 * @package Inventarios
 * @subpackage Controladores
 */

namespace Agrodb\Inventarios\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\Inventarios\Modelos\InventariosLogicaNegocio;
use Agrodb\Inventarios\Modelos\InventariosModeloMouse;


class InventariosControlador extends BaseControlador
{
    
    private $lNegocioInventarios = null;
    
    private $modeloMouseInventarios = null;
    
    private $accion = null;
    
    /**
     * Constructor
     */
    
    function __construct()
    {
        $this->lNegocioInventarios = new InventariosLogicaNegocio();
        $this->modeloMouseInventarios = new InventariosModeloMouse();
        
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
        $modeloInventario = $this->lNegocioInventarios->buscarTodo();        
        $this->generarCuadrosInventarios($modeloInventario, "/inventarios");
        require APP . 'Inventarios/vistas/listaAdministracionInventarioVista.php';
    }
    
    public function nuevoInventario()
    {
        $this->accion = "Nuevo item";
        require APP . 'Inventarios/vistas/formularioInventariosVista.php';
    }
    
    /**
     * Método para registrar en la base de datos -Ratones
     */
    public function guardar()
    {
        $this->lNegocioInventarios->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }
    
    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Inventarios
     */
    public function editar($vista)
    {
        $this->accion = "Editar " . $vista;
        $this->modeloMouseInventarios = $this->lNegocioInventarios->buscar($_POST["id"]);
        require APP . 'Inventarios/vistas/formulario' . ucfirst($vista) . 'Vista.php';
    }
    
    public function generarCuadrosInventarios($tabla, $vista = null)
    {
        $contador = 0;
                
        foreach ($tabla as $fila) {
            
            $this->itemsFiltrados[] = 
                
                '<article
                        id="' . $fila['id_raton'] . '"
		                class="item" 
                        data-rutaAplicacion="' . URL_MVC_FOLDER . 'inventarios/inventarios"
		                data-opcion="editar' . $vista . '" 
                        ondragstart="drag(event)" 
                        draggable="true"
		                data-destino="detalleItem">
		        <span class="ordinal">'.++$contador.'</span>
                <span><b>Tipo: </b>'.$fila['tipo'].'<br/></span>
                <span><b>Modelo: </b>'.$fila['modelo'].'<br/></span>
		        <span><b>Marca: </b>'.$fila['marca'].'<br/></span>
                <aside><small>Conector: </small><b>'.$fila['conector'].'</b></aside>          
          </article>';
          
        
        }
    }
}

?>