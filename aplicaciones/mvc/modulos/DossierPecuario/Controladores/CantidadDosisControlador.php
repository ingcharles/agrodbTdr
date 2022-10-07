<?php
/**
 * Controlador CantidadDosis
 *
 * Este archivo controla la lógica del negocio del modelo:  CantidadDosisModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    CantidadDosisControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\CantidadDosisLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\CantidadDosisModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class CantidadDosisControlador extends BaseControlador
{

    private $lNegocioCantidadDosis = null;

    private $modeloCantidadDosis = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioCantidadDosis = new CantidadDosisLogicaNegocio();
        $this->modeloCantidadDosis = new CantidadDosisModelo();
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
        $modeloCantidadDosis = $this->lNegocioCantidadDosis->buscarCantidadDosis();
        $this->tablaHtmlCantidadDosis($modeloCantidadDosis);
        require APP . 'DossierPecuario/vistas/listaCantidadDosisVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo CantidadDosis";
        require APP . 'DossierPecuario/vistas/formularioCantidadDosisVista.php';
    }

    /**
     * Método para registrar en la base de datos -CantidadDosis
     */
    public function guardar()
    {
        //Busca los datos de composicion
        $query = "quitar_caracteres_especiales(upper(trim(dosis))) = quitar_caracteres_especiales(upper(trim('".$_POST['dosis']."'))) and
        id_unidad = '".$_POST['id_unidad']."' and
        quitar_caracteres_especiales(upper(trim(nombre_unidad))) = quitar_caracteres_especiales(upper(trim('".$_POST['nombre_unidad']."'))) and
        id_solicitud = '".$_POST['id_solicitud']."'";
        
        $listaCantidadDosis = $this->lNegocioCantidadDosis->buscarLista($query);
        
        if(isset($listaCantidadDosis->current()->id_cantidad_dosis)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioCantidadDosis->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: CantidadDosis
     */
    public function editar()
    {
        $this->accion = "Editar CantidadDosis";
        $this->modeloCantidadDosis = $this->lNegocioCantidadDosis->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioCantidadDosisVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - CantidadDosis
     */
    public function borrar()
    {
        $this->lNegocioCantidadDosis->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - CantidadDosis
     */
    public function tablaHtmlCantidadDosis($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_cantidad_dosis'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\cantidaddosis"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_cantidad_dosis'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_solicitud'] . '</td>
                    <td>' . $fila['dosis'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar la cantidad de dosis
     */
    public function construirDetalleCantidadDosis()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $query = "id_solicitud = $idSolicitud";
        
        $listaDetalles = $this->lNegocioCantidadDosis->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['dosis'] != '' ? $fila['dosis'] : 'NA'). '</td>
                            <td>' . ($fila['nombre_unidad'] != '' ? $fila['nombre_unidad'] : 'NA') . '</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') .' onclick="fn_eliminarDetalleCantidadDosis(' . $fila['id_cantidad_dosis'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}