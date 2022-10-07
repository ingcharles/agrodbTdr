<?php
/**
 * Controlador PresentacionComercial
 *
 * Este archivo controla la lógica del negocio del modelo:  PresentacionComercialModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    PresentacionComercialControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\PresentacionComercialLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\PresentacionComercialModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class PresentacionComercialControlador extends BaseControlador
{

    private $lNegocioPresentacionComercial = null;
    private $modeloPresentacionComercial = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioPresentacionComercial = new PresentacionComercialLogicaNegocio();
        $this->modeloPresentacionComercial = new PresentacionComercialModelo();
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
        $modeloPresentacionComercial = $this->lNegocioPresentacionComercial->buscarPresentacionComercial();
        $this->tablaHtmlPresentacionComercial($modeloPresentacionComercial);
        require APP . 'DossierPecuario/vistas/listaPresentacionComercialVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo PresentacionComercial";
        require APP . 'DossierPecuario/vistas/formularioPresentacionComercialVista.php';
    }

    /**
     * Método para registrar en la base de datos -PresentacionComercial
     */
    public function guardar()
    {
        //Busca los datos de partida y códigos
        $query = "quitar_caracteres_especiales(upper(trim(presentacion))) = quitar_caracteres_especiales(upper(trim('".$_POST['presentacion']."'))) and 
        cantidad = '".$_POST['cantidad']."' and 
        id_unidad = '".$_POST['id_unidad']."' and
        quitar_caracteres_especiales(upper(trim(nombre_unidad))) = quitar_caracteres_especiales(upper(trim('".$_POST['nombre_unidad']."')))  and 
        id_solicitud = '".$_POST['id_solicitud']."'";
        
        $listaOrigen = $this->lNegocioPresentacionComercial->buscarLista($query);
        
        if(isset($listaOrigen->current()->id_presentacion_comercial)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $_POST['subcodigo_producto'] = $this->generarSubcodigoPresentacion($_POST['id_solicitud']);
            
            $this->lNegocioPresentacionComercial->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: PresentacionComercial
     */
    public function editar()
    {
        $this->accion = "Editar PresentacionComercial";
        $this->modeloPresentacionComercial = $this->lNegocioPresentacionComercial->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioPresentacionComercialVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - PresentacionComercial
     */
    public function borrar()
    {
        $this->lNegocioPresentacionComercial->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - PresentacionComercial
     */
    public function tablaHtmlPresentacionComercial($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_presentacion_comercial'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\presentacioncomercial"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_presentacion_comercial'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['subcodigo_producto'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para generar la numeración de las presentaciones
     */
    public function generarSubcodigoPresentacion($idSolicitud)
    {
        return $this->lNegocioPresentacionComercial->generarSubcodigoPresentacion($idSolicitud);
    }
    
    /**
     * Método para listar los miembros registrados de una asociación
     */
    public function construirDetallePresentacionComercial()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        $grupoProducto = $_POST['grupoProducto'];
        
        $query = "id_solicitud = $idSolicitud";
        
        $listaDetalles = $this->lNegocioPresentacionComercial->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        //Grupo 2 Biológicos
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['subcodigo_producto'] != '' ? $fila['subcodigo_producto'] : 'NA'). '</td>
                            <td>' . ($fila['presentacion'] != '' ? $fila['presentacion'] : 'NA'). '</td>
                            <td>' . ($fila['cantidad'] != '' ? $fila['cantidad'] . ' ' . ($fila['nombre_unidad'] != '' ? $fila['nombre_unidad'] : 'NA') : 'NA'). '</td>'.
                            ($grupoProducto==2?'<td>' . ($fila['dosis_envase'] != '' ? $fila['dosis_envase'] : 'NA'). '</td>':'')
                            .'<td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono"  '. ($fase!='editar'?'style="display:none"':'') . ' onclick="fn_eliminarDetallePresentacionComercial(' . $fila['id_presentacion_comercial'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}