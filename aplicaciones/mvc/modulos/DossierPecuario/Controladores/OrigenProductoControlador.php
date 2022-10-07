<?php
/**
 * Controlador OrigenProducto
 *
 * Este archivo controla la lógica del negocio del modelo:  OrigenProductoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    OrigenProductoControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\OrigenProductoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\OrigenProductoModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class OrigenProductoControlador extends BaseControlador
{

    private $lNegocioOrigenProducto = null;
    private $modeloOrigenProducto = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioOrigenProducto = new OrigenProductoLogicaNegocio();
        $this->modeloOrigenProducto = new OrigenProductoModelo();
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
        $modeloOrigenProducto = $this->lNegocioOrigenProducto->buscarOrigenProducto();
        $this->tablaHtmlOrigenProducto($modeloOrigenProducto);
        require APP . 'DossierPecuario/vistas/listaOrigenProductoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo OrigenProducto";
        require APP . 'DossierPecuario/vistas/formularioOrigenProductoVista.php';
    }

    /**
     * Método para registrar en la base de datos -OrigenProducto
     */
    public function guardar()
    {
        //Busca los datos de origen
        if($_POST['origen_fabricacion'] == "Extranjero"){
            $query = "origen_fabricacion = '".$_POST['origen_fabricacion']."' and id_fabricante_extranjero = '".$_POST['id_fabricante_extranjero']."' and id_solicitud = '".$_POST['id_solicitud']."'";
        }else{
            $query = "origen_fabricacion = '".$_POST['origen_fabricacion']."' and identificador_fabricante = '".$_POST['identificador_fabricante']."' and id_solicitud = '".$_POST['id_solicitud']."'";
        }
        
        $listaOrigen = $this->lNegocioOrigenProducto->buscarLista($query);
        
        if(isset($listaOrigen->current()->id_origen_producto)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioOrigenProducto->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }         
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: OrigenProducto
     */
    public function editar()
    {
        $this->accion = "Editar OrigenProducto";
        $this->modeloOrigenProducto = $this->lNegocioOrigenProducto->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioOrigenProductoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - OrigenProducto
     */
    public function borrar()
    {
        $this->lNegocioOrigenProducto->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - OrigenProducto
     */
    public function tablaHtmlOrigenProducto($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_origen_producto'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\origenproducto"
                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
                		<td>' . ++ $contador . '</td>
                		<td style="white - space:nowrap; "><b>' . $fila['id_origen_producto'] . '</b></td>
                        <td>' . $fila['id_solicitud'] . '</td>
                        <td>' . $fila['fecha_creacion'] . '</td>
                        <td>' . $fila['origen_fabricacion'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Método para listar los miembros registrados de una asociación
     */
    public function construirDetalleOrigen()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $query = "id_solicitud = $idSolicitud";
        
        $listaDetalles = $this->lNegocioOrigenProducto->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['origen_fabricacion'] != '' ? ($fila['origen_fabricacion'] == 'TitularRegistro' ? 'Titular del Registro' : ($fila['origen_fabricacion'] == 'ContratoNacional' ? 'Elaborador por Contrato Nacional' : 'Extranjero')) : 'NA'). '</td>
                            <td>' . ($fila['nombre_fabricante'] != '' ? $fila['nombre_fabricante'] : 'NA'). '</td>
                            <td>' . ($fila['pais'] != '' ? $fila['pais'] : 'NA'). '</td>
                            <td>' . ($fila['direccion_fabricante'] != '' ? $fila['direccion_fabricante'] : 'NA'). '</td>
                            <td>' . ($fila['tipo_producto_fabricante'] != '' ? $fila['tipo_producto_fabricante'] : 'NA') . '</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') .' onclick="fn_eliminarDetalleOrigen(' . $fila['id_origen_producto'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}