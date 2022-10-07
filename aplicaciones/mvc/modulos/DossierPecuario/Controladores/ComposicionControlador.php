<?php
/**
 * Controlador Composicion
 *
 * Este archivo controla la lógica del negocio del modelo:  ComposicionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    ComposicionControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\ComposicionLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\ComposicionModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ComposicionControlador extends BaseControlador
{

    private $lNegocioComposicion = null;
    private $modeloComposicion = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioComposicion = new ComposicionLogicaNegocio();
        $this->modeloComposicion = new ComposicionModelo();
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
        $modeloComposicion = $this->lNegocioComposicion->buscarComposicion();
        $this->tablaHtmlComposicion($modeloComposicion);
        require APP . 'DossierPecuario/vistas/listaComposicionVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Composicion";
        require APP . 'DossierPecuario/vistas/formularioComposicionVista.php';
    }

    /**
     * Método para registrar en la base de datos -Composicion
     */
    public function guardar()
    {
        //Busca los datos de composicion
        $query = "quitar_caracteres_especiales(upper(trim(cada))) = quitar_caracteres_especiales(upper(trim('".$_POST['cada']."'))) and 
        id_unidad = '".$_POST['id_unidad']."' and 
        quitar_caracteres_especiales(upper(trim(nombre_unidad))) = quitar_caracteres_especiales(upper(trim('".$_POST['nombre_unidad']."'))) and
        id_tipo_componente = '".$_POST['id_tipo_componente']."' and 
        id_nombre_componente = '".$_POST['id_nombre_componente']."' and 
        cantidad = '".$_POST['cantidad']."' and 
        id_unidad_componente = '".$_POST['id_unidad_componente']."' and
        quitar_caracteres_especiales(upper(trim(nombre_unidad_componente))) = quitar_caracteres_especiales(upper(trim('".$_POST['nombre_unidad_componente']."'))) and 
        id_solicitud = '".$_POST['id_solicitud']."'";
        
        $listaComposicion = $this->lNegocioComposicion->buscarLista($query);
        
        if(isset($listaComposicion->current()->id_composicion)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioComposicion->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        } 
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Composicion
     */
    public function editar()
    {
        $this->accion = "Editar Composicion";
        $this->modeloComposicion = $this->lNegocioComposicion->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioComposicionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Composicion
     */
    public function borrar()
    {
        $this->lNegocioComposicion->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Composicion
     */
    public function tablaHtmlComposicion($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_composicion'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\composicion"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_composicion'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['cada'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar las composiciones
     */
    public function construirDetalleComposicion()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $listaDetalles = $this->lNegocioComposicion->obtenerInformacionComposicion($idSolicitud);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . 'Cada ' .($fila['cada'] != '' ? $fila['cada'] : ''). ' ' . ($fila['nombre_unidad'] != '' ? $fila['nombre_unidad'] : ''). 
                            ' contiene (' . ($fila['tipo_componente'] != '' ? $fila['tipo_componente'] : 'NA') . ') '. ($fila['nombre_componente'] != '' ? $fila['nombre_componente'] : 'NA') .
                            ': '. ($fila['cantidad'] != '' ? $fila['cantidad'] : 'NA') . ' ' . ($fila['nombre_unidad_componente'] != '' ? $fila['nombre_unidad_componente'] : '').
                            '<td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') . 'onclick="fn_eliminarDetalleComposicion(' . $fila['id_composicion'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}