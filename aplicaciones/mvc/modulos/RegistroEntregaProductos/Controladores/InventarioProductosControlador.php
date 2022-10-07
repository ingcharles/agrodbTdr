<?php
/**
 * Controlador InventarioProductos
 *
 * Este archivo controla la lógica del negocio del modelo:  InventarioProductosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-01-03
 * @uses    InventarioProductosControlador
 * @package RegistroEntregaProductos
 * @subpackage Controladores
 */
namespace Agrodb\RegistroEntregaProductos\Controladores;

use Agrodb\RegistroEntregaProductos\Modelos\InventarioProductosLogicaNegocio;
use Agrodb\RegistroEntregaProductos\Modelos\InventarioProductosModelo;

use Agrodb\Catalogos\Modelos\ProductosDistribucionLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosDistribucionModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class InventarioProductosControlador extends BaseControlador
{

    private $lNegocioInventarioProductos = null;
    private $modeloInventarioProductos = null;
    
    private $lNegocioProductosDistribucion = null;
    private $modeloProductosDistribucion = null;

    private $accion = null;
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        
        $this->lNegocioInventarioProductos = new InventarioProductosLogicaNegocio();
        $this->modeloInventarioProductos = new InventarioProductosModelo();
        
        $this->lNegocioProductosDistribucion = new ProductosDistribucionLogicaNegocio();
        $this->modeloProductosDistribucion = new ProductosDistribucionModelo();
        
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
        $this->cargarPanelInventario();
        
        $query = "estado='Activo' order by id_inventario, nombre_producto_distribucion, tipo_registro DESC";
        
        $modeloInventarioProductos = $this->lNegocioInventarioProductos->buscarLista($query);
        $this->tablaHtmlInventarioProductos($modeloInventarioProductos);
        
        require APP . 'RegistroEntregaProductos/vistas/listaInventarioProductosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Producto de Inventario";
        $this->formulario = "Nuevo";
        
        require APP . 'RegistroEntregaProductos/vistas/formularioInventarioProductosVista.php';
    }

    /**
     * Método para registrar en la base de datos -InventarioProductos
     */
    public function guardar()
    {
        $this->lNegocioInventarioProductos->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: InventarioProductos
     */
    public function editar()
    {
        $this->accion = "Editar Producto de Inventario";
        $this->formulario = "Editar";
        
        $this->modeloInventarioProductos = $this->lNegocioInventarioProductos->buscar($_POST["id"]);
        
        require APP . 'RegistroEntregaProductos/vistas/formularioInventarioProductosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - InventarioProductos
     */
    public function borrar()
    {
        $this->lNegocioInventarioProductos->borrar($_POST['elementos']);
    }
    
    public function actualizarLista()
    {
        $query = "estado='Activo'";
        
        $modeloInventarioProductos = $this->lNegocioInventarioProductos->buscarLista($query);
        $this->tablaHtmlInventarioProductos($modeloInventarioProductos);
        
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }

    /**
     * Construye el código HTML para desplegar la lista de - InventarioProductos
     */
    public function tablaHtmlInventarioProductos($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_inventario'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroEntregaProductos/inventarioProductos"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true" data-destino="detalleItem">
                    	<td>' . ++ $contador . '</td>
                    	<td style="white - space:nowrap; "><b>' . $fila['nombre_producto_distribucion'] .'</b></td>
                        <td>' . $fila['cantidad'] . ' ' . $fila['unidad'] . '</td>
                        <td>' . $fila['tipo_registro'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para Inventario
     */
    public function cargarPanelInventario()
    {
        
        $this->panelBusquedaInventario = '<table class="filtro" style="width: 450px;">
                                                <tbody  style="width: 100%;">
                                                    <tr>
                                                        <th >Consultar productos:</th>
                                                    </tr>
                                                    
                                					<tr  style="width: 100%;">
                                						<td >*Producto: </td>
                                						<td colspan=3 >
                                							<select id="idProductoInventario" name="idProductoInventario" style="width: 97%;" required>' . $this->comboProductosDistribucion(null) . '</select>
                                						</td>
                                					</tr>
                                							    
                                                    <tr  style="width: 100%;">
                                						<td >*Estado: </td>
                                						<td colspan=3>
                                                            <select id="estadoInventario" name="estadoInventario" style="width: 97%;" required>' . $this->comboActivoInactivo("Activo") . '</select>
                                						</td>
                                                    </tr>
                                							    
                                                    <tr  style="width: 100%;">
                                						<td >*Tipo de Registro: </td>
                                						<td colspan=3>
                                                            <select id="tipoRegistroInventario" name="tipoRegistroInventario" style="width: 97%;" required>' . $this->comboTipoRegistro("Nuevo") . '</select>
                                						</td>
                                					</tr>
                                                              
                                					<tr>
                                						<td colspan="2" style="text-align: end;">
                                							<button id="btnFiltrar">Consultar</button>
                                						</td>
                                					</tr>
                                				</tbody>
                                			</table>';
    }
    
    /**
     * Consulta los productos de distribución del catálogo y construye el combo
     *
     * @param Integer $idProductoDistribucion
     * @return string
     */
    public function comboProductosDistribucion($idProductoDistribucion = null)
    {
        $productos = "";
        
        $consulta = "estado='activo'";
        
        $combo = $this->lNegocioProductosDistribucion->buscarLista($consulta);
        
        foreach ($combo as $item)
        {
            if ($idProductoDistribucion == $item['id_producto_distribucion'])
            {
                $productos .= '<option value="' . $item->id_producto_distribucion . '" data-unidad="' . $item->unidad . '" selected>' . $item->producto_distribucion . '</option>';
            } else
            {
                $productos .= '<option value="' . $item->id_producto_distribucion . '" data-unidad="' . $item->unidad . '">' . $item->producto_distribucion . '</option>';
            }
        }
        return $productos;
    }
    
    /**
     * Combo de estados para movilizaciones
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboTipoRegistro($opcion = null)
    {
        $combo = "";
        if ($opcion == "nuevo") {
            $combo .= '<option value="nuevo" selected="selected">Nuevo</option>';
            $combo .= '<option value="devolucion">Devolución</option>';
        } else if ($opcion == "devolucion") {
            $combo .= '<option value="nuevo" >Nuevo</option>';
            $combo .= '<option value="devolucion">Devolución</option>';
        } else {
            $combo .= '<option value="nuevo">Nuevo</option>';
            $combo .= '<option value="devolucion">Devolución</option>';
        }
        
        return $combo;
    }
    
    /**
     * Método para listar los inventarios registrados
     */
    public function listarInventariosFiltrados()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idProductoInventario = $_POST["idProductoInventario"];
        $estadoInventario = $_POST["estadoInventario"];
        $tipoRegistroInventario = $_POST["tipoRegistroInventario"];
        
        $arrayParametros = array(
            'id_producto_distribucion' => $idProductoInventario,
            'estado' => $estadoInventario,
            'tipo_registro' => $tipoRegistroInventario
        );
        
        $inventario = $this->lNegocioInventarioProductos->buscarInventarioXFiltro($arrayParametros);
        
        $this->tablaHtmlInventarioProductos($inventario);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
}