<?php
/**
 * Controlador DistribucionProductos
 *
 * Este archivo controla la lógica del negocio del modelo:  DistribucionProductosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-01-03
 * @uses    DistribucionProductosControlador
 * @package RegistroEntregaProductos
 * @subpackage Controladores
 */
namespace Agrodb\RegistroEntregaProductos\Controladores;

use Agrodb\RegistroEntregaProductos\Modelos\DistribucionProductosLogicaNegocio;
use Agrodb\RegistroEntregaProductos\Modelos\DistribucionProductosModelo;

use Agrodb\RegistroEntregaProductos\Modelos\InventarioProductosLogicaNegocio;
use Agrodb\RegistroEntregaProductos\Modelos\InventarioProductosModelo;

use Agrodb\Catalogos\Modelos\ProductosDistribucionLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosDistribucionModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class DistribucionProductosControlador extends BaseControlador
{

    private $lNegocioDistribucionProductos = null;
    private $modeloDistribucionProductos = null;
    
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
        
        $this->lNegocioDistribucionProductos = new DistribucionProductosLogicaNegocio();
        $this->modeloDistribucionProductos = new DistribucionProductosModelo();
        
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
        $this->cargarPanelDistribucion();
        
        $modeloInventarioProductos = $this->lNegocioDistribucionProductos->listarDistribucionesConDatos();
        $this->tablaHtmlDistribucionProductos($modeloInventarioProductos);
        
        require APP . 'RegistroEntregaProductos/vistas/listaDistribucionProductosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Distribución de Productos";
        $this->formulario = "Nuevo";
        
        require APP . 'RegistroEntregaProductos/vistas/formularioDistribucionProductosVista.php';
    }

    /**
     * Método para registrar en la base de datos -DistribucionProductos
     */
    public function guardar()
    {        
        $this->lNegocioDistribucionProductos->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: DistribucionProductos
     */
    public function editar()
    {
        $this->accion = "Editar Distribución de Productos";
        $this->formulario = "Editar";
        
        $this->modeloDistribucionProductos = $this->lNegocioDistribucionProductos->buscar($_POST["id"]);
        
        require APP . 'RegistroEntregaProductos/vistas/formularioDistribucionProductosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DistribucionProductos
     */
    public function borrar()
    {
        $estado = 'exito';
        $mensaje = 'Registro eliminado con éxito';
        $contenido = '';
        
        $this->lNegocioDistribucionProductos->borrar($_POST['idDistribucion']);
        
        Mensajes::exito(Constantes::ELIMINADO_CON_EXITO);
        
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }

    /**
     * Construye el código HTML para desplegar la lista de - DistribucionProductos
     */
    public function tablaHtmlDistribucionProductos($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_distribucion'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroEntregaProductos/DistribucionProductos"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
            		    <td>' . ++ $contador . '</td>
            		    <td style="white - space:nowrap; "><b>' . $fila['producto'] . '</b></td>
                        <td>' . $fila['entidad'] . '</td>
                        <td>' . $fila['provincia'] . '</td>
						<td>' . $fila['cantidad_asignada'] . $fila['unidad'] . '</td>
                        <td>' . $fila['cantidad_disponible'] . $fila['unidad'] . '</td>
                        <td>' . $fila['tipo_registro'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para Distribuciones
     */
    public function cargarPanelDistribucion()
    {
        
        $this->panelBusquedaDistribucion = '<table class="filtro" style="width: 450px;">
                                                <tbody  style="width: 100%;">
                                                    <tr>
                                                        <th >Consultar productos:</th>
                                                    </tr>
            
                                					<tr  style="width: 100%;">
                                						<td >*Producto: </td>
                                						<td colspan=3 >
                                							<select id="idProductoDistribucion" name="idProductoDistribucion" style="width: 97%;" required>' . $this->comboProductosInventario(null) . '</select>
                                						</td>
                                					</tr>

                                                    <tr  style="width: 100%;">
                                						<td >Provincia: </td>
                                						<td colspan=3 >
                                							<select id="idProvinciaDistribucion" name="idProvinciaDistribucion" style="width: 97%;" >
                                                                <option>Seleccione....</option>
                                                                ' . $this->comboProvinciasEc(null) . 
                                                            '</select>
                                						</td>
                                					</tr>

                                                    <tr  style="width: 100%;">
                                						<td >Entidad: </td>
                                						<td colspan=3 >
                                							<select id="entidadDistribucion" name="entidadDistribucion" style="width: 97%;" required>
                                                                <option>Seleccione....</option>
                                                                ' . $this->comboEntidades(null) . 
                                                            '</select>
                                						</td>
                                					</tr>
                                							    
                                                    <tr  style="width: 100%;">
                                						<td >*Estado: </td>
                                						<td colspan=3>
                                                            <select id="estadoDistribucion" name="estadoDistribucion" style="width: 97%;" required>' . $this->comboActivoInactivo("Activo") . '</select>
                                						</td>
                                                    </tr>
                                                                
                                                    <tr  style="width: 100%;">
                                						<td >*Tipo de Registro: </td>
                                						<td colspan=3>
                                                            <select id="tipoRegistroDistribucion" name="tipoRegistroDistribucion" style="width: 97%;" required>' . $this->comboTipoRegistro("Nuevo") . '</select>
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
     * Consulta los productos de distribución del inventario y construye el combo
     *
     * @param Integer $idProductoDistribucion
     * @return string
     */
    public function comboProductosInventario($idProductoDistribucion = null)
    {
        $productos = "";
        
        $combo = $this->lNegocioInventarioProductos->buscarInventarioDisponible();
        
        foreach ($combo as $item)
        {
            if ($idProductoDistribucion == $item['id_producto_distribucion'])
            {
                $productos .= '<option value="' . $item->id_producto_distribucion . '" data-cantidad="' . $item->cantidad . '" selected>' . $item->nombre_producto_distribucion . '</option>';
            } else
            {
                $productos .= '<option value="' . $item->id_producto_distribucion . '" data-cantidad="' . $item->cantidad . '">' . $item->nombre_producto_distribucion . '</option>';
            }
        }
        return $productos;
    }  
    
    /**
     * Consulta los productos de distribución del inventario y construye el combo
     *
     * @param Integer $idProductoDistribucion
     * @return string
     */
    public function comboProductosInventarioActualizado()
    {
        $productos = "<option value=''>Seleccionar....</option>";
        
        $combo = $this->lNegocioInventarioProductos->buscarInventarioDisponible();
        
        foreach ($combo as $item)
        {
            $productos .= '<option value="' . $item->id_producto_distribucion . '" data-cantidad="' . $item->cantidad . '">' . $item->nombre_producto_distribucion . '</option>';
        }
        
        echo $productos;
        exit();
    } 
    
    /**
     * Combo de entidades
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboEntidades($opcion = null)
    {
        $combo = "";
        if ($opcion == "Agrocalidad") {
            $combo .= '<option value="Agrocalidad" selected="selected">Agrocalidad</option>';
            $combo .= '<option value="MAG">Ministerio de Agricultura y Ganadería</option>';
        } else if ($opcion == "MAG") {
            $combo .= '<option value="Agrocalidad" >Agrocalidad</option>';
            $combo .= '<option value="MAG" selected="selected">Ministerio de Agricultura y Ganadería</option>';
        } else {
            $combo .= '<option value="Agrocalidad">Agrocalidad</option>';
            $combo .= '<option value="MAG">Ministerio de Agricultura y Ganadería</option>';
        }
        
        return $combo;
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
     * Método para agregar un registro y una fila en el grid de distribuciones
     */
    public function agregarDistribucion()
    {
        $estado = 'exito';
        $mensaje = 'Registro agregado con éxito';
        $contenido = '';
        
        //Buscar cantidad disponible del producto
        $inventarioProducto = $this->lNegocioInventarioProductos->buscarInventarioDisponibleXProducto($_POST['id_producto']);
        
        if ($_POST['cantidad_asignada'] <= $inventarioProducto->current()->cantidad ){
            
            $_POST['cantidad_disponible'] = $_POST['cantidad_asignada'];
            $idRegistro = $this->lNegocioDistribucionProductos->guardar($_POST);
            
            //Una vez guardado el registro de asignación se reduce el valor del inventario del producto
            $arrayParametros = array(
                'id_producto_distribucion' => $_POST['id_producto'],
                'cantidad' => $_POST['cantidad_asignada']
            );
            
            $this->lNegocioInventarioProductos->disminuirCantidadProductos($arrayParametros);
            
            //Impresión en el grid del registro creado
            $contenido .= "<tr id=" . $idRegistro . ">
                                <td>" . $_POST['entidad'] . "</td>
                                <td>" . $_POST['provincia'] . "</td>
                                <td>" . $_POST['producto'] . "</td>
                                <td>" . $_POST['cantidad_asignada'] . "</td>
                                <td class='borrar'>
                                    <button type='button' class='icono' name='eliminar' id='eliminar' onclick='quitarProductos(".$idRegistro."); return false;'/>
                                </td>
                           </tr>";
        }else{
            $estado = 'error';
            $mensaje = 'No se dispone de la cantidad requerida del producto para la asignación';
            $contenido = '';
        }
        
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    
    /**
     * Método para agregar un registro y una fila en el grid de distribuciones
     */
    public function eliminarDistribucion()
    {
        //Buscar registro de distribución con ID
        $distribucion = $this->lNegocioDistribucionProductos->buscar($_POST['idDistribucion']);
        
        //Buscar la unidad de medida del producto en el catálogo original
        $producto = $this->lNegocioProductosDistribucion->buscar($distribucion->idProducto);
        
        //Crear nuevo registro de inventario del Producto
        $arrayParametros = array(
            'id_producto_distribucion' => $distribucion->idProducto,
            'nombre_producto_distribucion' => $distribucion->producto,
            'cantidad' => $distribucion->cantidadAsignada,
            'tipo_registro' => 'devolucion',
            'unidad' => $producto->unidad
        );
        
        $this->lNegocioInventarioProductos->guardar($arrayParametros);
        
        //Eliminar el registro actual
        $this->lNegocioDistribucionProductos->borrar($_POST['idDistribucion']);
    }
    
    /**
     * Método para agregar un registro y una fila en el grid de distribuciones
     */
    public function modificarDistribucion()
    {
        //Verifica la cantidad requerida para el producto
        if($_POST['cantidad_disponible'] <= 0){
            $nuevaCantidadInventario = $_POST['cantidad_disponible_original'];
            $_POST['estado'] = 'Inactivo';
        }else{
            $nuevaCantidadInventario = $_POST['cantidad_disponible_original'] - $_POST['cantidad_disponible'];
        }
        
        //Actualizar el registro de distribución
        $this->lNegocioDistribucionProductos->guardar($_POST);
        
        //Buscar registro de distribución con ID
        $distribucion = $this->lNegocioDistribucionProductos->buscar($_POST['id_distribucion']);
        
        //Buscar la unidad de medida del producto en el catálogo original
        $producto = $this->lNegocioProductosDistribucion->buscar($distribucion->idProducto);
        
        //Crear nuevo registro de inventario del Producto
        $arrayParametros = array(
            'id_producto_distribucion' => $distribucion->idProducto,
            'nombre_producto_distribucion' => $distribucion->producto,
            'cantidad' => $nuevaCantidadInventario,
            'tipo_registro' => 'devolucion',
            'unidad' => $producto->unidad
        );
        
        $this->lNegocioInventarioProductos->guardar($arrayParametros);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }
    
    /**
     * Método para listar los inventarios registrados
     */
    public function listarDistribucionesFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idProductoDistribucion = $_POST["idProductoDistribucion"];
        $idProvinciaDistribucion = $_POST["idProvinciaDistribucion"];
        $entidadDistribucion = $_POST["entidadDistribucion"];
        $estadoDistribucion = $_POST["estadoDistribucion"];
        $tipoRegistroDistribucion = $_POST["tipoRegistroDistribucion"];
        
        $arrayParametros = array(
            'id_producto' => $idProductoDistribucion,
            'id_provincia' => $idProvinciaDistribucion,
            'entidad' => $entidadDistribucion,
            'estado' => $estadoDistribucion,
            'tipo_registro' => $tipoRegistroDistribucion
        );
        
        $distribucion = $this->lNegocioDistribucionProductos->buscarDistribucionXFiltro($arrayParametros);
        
        $this->tablaHtmlDistribucionProductos($distribucion);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
}