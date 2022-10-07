<?php
/**
 * Controlador EfectoBiologico
 *
 * Este archivo controla la lógica del negocio del modelo:  EfectoBiologicoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    EfectoBiologicoControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\EfectoBiologicoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\EfectoBiologicoModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class EfectoBiologicoControlador extends BaseControlador
{

    private $lNegocioEfectoBiologico = null;
    private $modeloEfectoBiologico = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioEfectoBiologico = new EfectoBiologicoLogicaNegocio();
        $this->modeloEfectoBiologico = new EfectoBiologicoModelo();
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
        $modeloEfectoBiologico = $this->lNegocioEfectoBiologico->buscarEfectoBiologico();
        $this->tablaHtmlEfectoBiologico($modeloEfectoBiologico);
        require APP . 'DossierPecuario/vistas/listaEfectoBiologicoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo EfectoBiologico";
        require APP . 'DossierPecuario/vistas/formularioEfectoBiologicoVista.php';
    }

    /**
     * Método para registrar en la base de datos -EfectoBiologico
     */
    public function guardar()
    {
        //Busca los datos de forma de administración en animales
        $query = "id_efecto = '".$_POST['id_efecto']."' and
        quitar_caracteres_especiales(upper(trim(descripcion_efecto_biologico))) = quitar_caracteres_especiales(upper(trim('".$_POST['descripcion_efecto_biologico']."'))) and
        id_solicitud = '".$_POST['id_solicitud']."'";
                
        $listaOrigen = $this->lNegocioEfectoBiologico->buscarLista($query);
        
        if(isset($listaOrigen->current()->id_efecto_biologico)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioEfectoBiologico->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        } 
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: EfectoBiologico
     */
    public function editar()
    {
        $this->accion = "Editar EfectoBiologico";
        $this->modeloEfectoBiologico = $this->lNegocioEfectoBiologico->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioEfectoBiologicoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - EfectoBiologico
     */
    public function borrar()
    {
        $this->lNegocioEfectoBiologico->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - EfectoBiologico
     */
    public function tablaHtmlEfectoBiologico($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_efecto_biologico'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\efectobiologico"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_efecto_biologico'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['id_efecto'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar las formas de administración en animales
     */
    public function construirDetalleEfectoBiologico()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $listaDetalles = $this->lNegocioEfectoBiologico->obtenerInformacionEfectoBiologico($idSolicitud);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['efecto_biologico'] != '' ? $fila['efecto_biologico'] : '').'</td>
                            <td>' . ($fila['descripcion_efecto_biologico'] != '' ? $fila['descripcion_efecto_biologico'] : '').'</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') . 'onclick="fn_eliminarDetalleEfectoBiologico(' . $fila['id_efecto_biologico'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}