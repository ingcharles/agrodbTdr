<?php
/**
 * Controlador ReactivoMaterial
 *
 * Este archivo controla la lógica del negocio del modelo:  ReactivoMaterialModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    ReactivoMaterialControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\ReactivoMaterialLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\ReactivoMaterialModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ReactivoMaterialControlador extends BaseControlador
{

    private $lNegocioReactivoMaterial = null;
    private $modeloReactivoMaterial = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioReactivoMaterial = new ReactivoMaterialLogicaNegocio();
        $this->modeloReactivoMaterial = new ReactivoMaterialModelo();
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
        $modeloReactivoMaterial = $this->lNegocioReactivoMaterial->buscarReactivoMaterial();
        $this->tablaHtmlReactivoMaterial($modeloReactivoMaterial);
        require APP . 'DossierPecuario/vistas/listaReactivoMaterialVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo ReactivoMaterial";
        require APP . 'DossierPecuario/vistas/formularioReactivoMaterialVista.php';
    }

    /**
     * Método para registrar en la base de datos -ReactivoMaterial
     */
    public function guardar()
    {
        //Busca los datos de aplicación en instalaciones
        $query = "quitar_caracteres_especiales(upper(trim(reactivo_material))) = quitar_caracteres_especiales(upper(trim('".$_POST['reactivo_material']."'))) and
        id_solicitud = '".$_POST['id_solicitud']."'";
        
        $listaReactivoMaterial = $this->lNegocioReactivoMaterial->buscarLista($query);
        
        if(isset($listaReactivoMaterial->current()->id_reactivo_material)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioReactivoMaterial->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ReactivoMaterial
     */
    public function editar()
    {
        $this->accion = "Editar ReactivoMaterial";
        $this->modeloReactivoMaterial = $this->lNegocioReactivoMaterial->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioReactivoMaterialVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ReactivoMaterial
     */
    public function borrar()
    {
        $this->lNegocioReactivoMaterial->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ReactivoMaterial
     */
    public function tablaHtmlReactivoMaterial($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_reactivo_material'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\reactivomaterial"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
                	<td>' . ++ $contador . '</td>
                	<td style="white - space:nowrap; "><b>' . $fila['id_reactivo_material'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['reactivo_material'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar los reactivos y materiales
     */
    public function construirDetalleReactivoMaterial()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $query = "id_solicitud = $idSolicitud";
        
        $listaDetalles = $this->lNegocioReactivoMaterial->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['reactivo_material'] != '' ? $fila['reactivo_material'] : 'NA'). '</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') .' onclick="fn_eliminarDetalleReactivoMaterial(' . $fila['id_reactivo_material'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}