<?php
/**
 * Controlador PeriodoVidaUtil
 *
 * Este archivo controla la lógica del negocio del modelo:  PeriodoVidaUtilModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    PeriodoVidaUtilControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\PeriodoVidaUtilLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\PeriodoVidaUtilModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class PeriodoVidaUtilControlador extends BaseControlador
{

    private $lNegocioPeriodoVidaUtil = null;
    private $modeloPeriodoVidaUtil = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioPeriodoVidaUtil = new PeriodoVidaUtilLogicaNegocio();
        $this->modeloPeriodoVidaUtil = new PeriodoVidaUtilModelo();
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
        $modeloPeriodoVidaUtil = $this->lNegocioPeriodoVidaUtil->buscarPeriodoVidaUtil();
        $this->tablaHtmlPeriodoVidaUtil($modeloPeriodoVidaUtil);
        require APP . 'DossierPecuario/vistas/listaPeriodoVidaUtilVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo PeriodoVidaUtil";
        require APP . 'DossierPecuario/vistas/formularioPeriodoVidaUtilVista.php';
    }

    /**
     * Método para registrar en la base de datos -PeriodoVidaUtil
     */
    public function guardar()
    {
        //Busca los datos de periodo de vida util
        $query = "quitar_caracteres_especiales(upper(trim(descripcion_envase))) = quitar_caracteres_especiales(upper(trim('".$_POST['descripcion_envase']."'))) and
        periodo_vida_util = '".$_POST['periodo_vida_util']."' and
        id_unidad_tiempo = '".$_POST['id_unidad_tiempo']."' and
        id_solicitud = '".$_POST['id_solicitud']."'";
        
        $listaUsoEspecie = $this->lNegocioPeriodoVidaUtil->buscarLista($query);
        
        if(isset($listaUsoEspecie->current()->id_periodo_vida_util)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioPeriodoVidaUtil->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: PeriodoVidaUtil
     */
    public function editar()
    {
        $this->accion = "Editar PeriodoVidaUtil";
        $this->modeloPeriodoVidaUtil = $this->lNegocioPeriodoVidaUtil->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioPeriodoVidaUtilVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - PeriodoVidaUtil
     */
    public function borrar()
    {
        $this->lNegocioPeriodoVidaUtil->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - PeriodoVidaUtil
     */
    public function tablaHtmlPeriodoVidaUtil($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_periodo_vida_util'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\periodovidautil"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
                	<td>' . ++ $contador . '</td>
                	<td style="white - space:nowrap; "><b>' . $fila['id_periodo_vida_util'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['descripcion_envase'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar los periodos de vida util
     */
    public function construirDetallePeriodoVidaUtil()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $query = "id_solicitud = '$idSolicitud'";
        
        $listaDetalles = $this->lNegocioPeriodoVidaUtil->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['descripcion_envase'] != '' ? $fila['descripcion_envase'] : '').'</td>
                            <td>' . ($fila['periodo_vida_util'] != '' ? $fila['periodo_vida_util'] . ' ' .($fila['nombre_unidad_periodo_vida'] != '' ? $fila['nombre_unidad_periodo_vida'] : ''): '').'</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') . 'onclick="fn_eliminarDetallePeriodoVidaUtil(' . $fila['id_periodo_vida_util'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}