<?php
/**
 * Controlador FormaFisFarCosProducto
 *
 * Este archivo controla la lógica del negocio del modelo:  FormaFisFarCosProductoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    FormaFisFarCosProductoControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\FormaFisFarCosProductoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\FormaFisFarCosProductoModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class FormaFisFarCosProductoControlador extends BaseControlador
{

    private $lNegocioFormaFisFarCosProducto = null;
    private $modeloFormaFisFarCosProducto = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioFormaFisFarCosProducto = new FormaFisFarCosProductoLogicaNegocio();
        $this->modeloFormaFisFarCosProducto = new FormaFisFarCosProductoModelo();
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
        $modeloFormaFisFarCosProducto = $this->lNegocioFormaFisFarCosProducto->buscarFormaFisFarCosProducto();
        $this->tablaHtmlFormaFisFarCosProducto($modeloFormaFisFarCosProducto);
        require APP . 'DossierPecuario/vistas/listaFormaFisFarCosProductoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo FormaFisFarCosProducto";
        require APP . 'DossierPecuario/vistas/formularioFormaFisFarCosProductoVista.php';
    }

    /**
     * Método para registrar en la base de datos -FormaFisFarCosProducto
     */
    public function guardar()
    {
        $bandera = true;
        
        if($_POST['idGrupoProducto'] != '1'){//Cualquiera excepto alimentos
            
            $numForma = $this->lNegocioFormaFisFarCosProducto->obtenerNumeroRegistrosFormaFisFarCosProducto($_POST['id_solicitud']);
            
            if($numForma->current()->numero == 0){
                $bandera = true;
            }else{
                $bandera = false;
                Mensajes::fallo(Constantes::ERROR_CANTIDAD_ACEPTADA);
            }
        }
        
        if ($bandera){
            //Busca los datos de composicion
            $query = "id_forma = '".$_POST['id_forma']."' and
                id_solicitud = '".$_POST['id_solicitud']."'";
            
            $listaComposicion = $this->lNegocioFormaFisFarCosProducto->buscarLista($query);
            
            if(isset($listaComposicion->current()->id_forma_fis_far_cos_producto)){
                Mensajes::fallo(Constantes::ERROR_DUPLICADO);
            }else{
                $this->lNegocioFormaFisFarCosProducto->guardar($_POST);
                Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
            }
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: FormaFisFarCosProducto
     */
    public function editar()
    {
        $this->accion = "Editar FormaFisFarCosProducto";
        $this->modeloFormaFisFarCosProducto = $this->lNegocioFormaFisFarCosProducto->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioFormaFisFarCosProductoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - FormaFisFarCosProducto
     */
    public function borrar()
    {
        $this->lNegocioFormaFisFarCosProducto->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - FormaFisFarCosProducto
     */
    public function tablaHtmlFormaFisFarCosProducto($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_forma_fis_far_cos_producto'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\formafisfarcosproducto"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_forma_fis_far_cos_producto'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['id_forma'] . '</td>
                </tr>'
            );
        }
    }

    /**
     * Método para listar las formas físicas, farmacéuticas y cosméticas
     */
    public function construirDetalleFormaFisFarCosProducto()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $listaDetalles = $this->lNegocioFormaFisFarCosProducto->obtenerInformacionFormaFisFarCosProducto($idSolicitud);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['formulacion'] != '' ? $fila['formulacion'] : '').'</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') . 'onclick="fn_eliminarDetalleFormaFisFarCosProducto(' . $fila['id_forma_fis_far_cos_producto'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}