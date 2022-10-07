<?php
/**
 * Controlador CentroFaenamientoTipoInspector
 *
 * Este archivo controla la lógica del negocio del modelo:  CentroFaenamientoTipoInspectorModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2018-11-21
 * @uses    CentroFaenamientoTipoInspectorControlador
 * @package CentrosFaenamiento
 * @subpackage Controladores
 */
namespace Agrodb\CentrosFaenamiento\Controladores;

use Agrodb\CentrosFaenamiento\Modelos\CentroFaenamientoTipoInspectorLogicaNegocio;
use Agrodb\CentrosFaenamiento\Modelos\CentroFaenamientoTipoInspectorModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class CentroFaenamientoTipoInspectorControlador extends BaseControlador
{

    private $lNegocioCentroFaenamientoTipoInspector = null;

    private $modeloCentroFaenamientoTipoInspector = null;

    private $accion = null;

    public $veterinariosAuxiliares = '';

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioCentroFaenamientoTipoInspector = new CentroFaenamientoTipoInspectorLogicaNegocio();
        $this->modeloCentroFaenamientoTipoInspector = new CentroFaenamientoTipoInspectorModelo();
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
        // $modeloCentroFaenamientoTipoInspector = $this->lNegocioCentroFaenamientoTipoInspector->buscarCentroFaenamientoTipoInspector();
        // $this->tablaHtmlCentroFaenamientoTipoInspector($modeloCentroFaenamientoTipoInspector);
        $this->cargarPanelCentroFaenamiento();
        require APP . 'CentrosFaenamiento/vistas/listarCentroFaenamientoTipoInspectorVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo CentroFaenamientoTipoInspector";
        require APP . 'CentrosFaenamiento/vistas/formularioCentroFaenamientoTipoInspectorVista.php';
    }

    /**
     * Método para registrar en la base de datos -CentroFaenamientoTipoInspector
     */
    public function guardar()
    {
        $this->lNegocioCentroFaenamientoTipoInspector->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: CentroFaenamientoTipoInspector
     */
    public function editar()
    {
        $this->accion = "Agregar información";
        
        $datoAcceso = $_POST["id"];
        $datos = explode('-', $datoAcceso);
        $identificadorOperador = $datos[0];
        $idTipoInspector = $datos[1];

        $arrayParametros = array(
            'identificador_operador' => $identificadorOperador,
            'id_tipo_inspector' => $idTipoInspector
        );
        
        $datosCentroFenamientoTipoInspector = $this->lNegocioCentroFaenamientoTipoInspector->buscarCentroFaenamientoTipoInspectorPorIdentificador($arrayParametros);
        $this->modeloCentroFaenamientoTipoInspector->setOptions((array) $datosCentroFenamientoTipoInspector->current());
        if ($datosCentroFenamientoTipoInspector->current()->contador != 0) {
            $centrosFaenamientoAsociados = $this->lNegocioCentroFaenamientoTipoInspector->buscarCentroFaenamientoInspector($arrayParametros);
            $this->listarVeterinariosAuxiliaresAsignados($centrosFaenamientoAsociados);
        }

        require APP . 'CentrosFaenamiento/vistas/formularioCentroFaenamientoTipoInspectorVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - CentroFaenamientoTipoInspector
     */
    /*public function borrar()
    {
        $this->lNegocioCentroFaenamientoTipoInspector->borrar($_POST['elementos']);
    }*/

    /**
     * Construye el código HTML para desplegar la lista de - CentroFaenamientoTipoInspector
     */
    public function tablaHtmlCentroFaenamientoTipoInspector($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['identificador_operador'].'-'.$fila['id_tipo_inspector'] . '"
        		    class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CentrosFaenamiento\centroFaenamientoTipoInspector"
        		    data-opcion="editar" ondragstart="drag(event)" draggable="true"
        		    data-destino="detalleItem">
        		    <td>' . ++ $contador . '</td>
        		    <td style="white - space:nowrap; "><b>' . $fila['identificador_operador'] . '</b></td>
                    <td>' . $fila['razon_social'] . '</td>
                    <td>' . $fila['tipo_inspector'] . '</td>
                    <td>' . $fila['contador'] . '</td>
                    </tr>'
                );
            }
        }
    }

    /**
     * Método para listar los centros de faenamiento tipo inspector por identificador del operador
     */
    public function listarCentroFaenamientoTipoInspector()
    {
        $identificadorOperador = $_POST["identificadorOperador"];
        $arrayParametros = array('identificador_operador' => $identificadorOperador);
        
        $centroFaenamientoTipoInspector = $this->lNegocioCentroFaenamientoTipoInspector->buscarCentroFaenamientoTipoInspectorPorIdentificador($arrayParametros);
        $this->tablaHtmlCentroFaenamientoTipoInspector($centroFaenamientoTipoInspector);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
        exit();
    }

    /**
     * Método para listar los veterinarios o auxiliares asignados por operador
     */
    public function listarVeterinariosAuxiliaresAsignados($centrosFaenamiento)
    {
            $cadena = "";
            
            foreach ($centrosFaenamiento as $item) {
                
                $idCentroFaenamiento = "<input type='hidden' readonly class='id_centro' value='" . $item['id_centro_faenamiento'] . "'>";
                
                $cadena .= "<tr id = '" . $item['id_centro_faenamiento_tipo_inspector'] . "' style='text-align: center;'>
                                <td></td>
                                <td>" . $item['razon_social'] . "" . $idCentroFaenamiento . "</td>
                                <td>" . $item['tipo'] . "</td>
                                <td>" . $item['centro_faenamiento'] . "</td>
                                <td >" . $item['sitio'] . "</td>
                                <td >" . $item['especie'] . "</td>
                                <td>
                                    <form class='borrar' data-rutaaplicacion='". URL_MVC_FOLDER."CentrosFaenamiento' data-opcion='centroFaenamientoTipoInspector/actualizarEstadoAsinacionTipoInspector'>
                                        <input type='hidden' id= 'id_centro_faenamiento_tipo_inspector' name= 'id_centro_faenamiento_tipo_inspector' value='" . $item['id_centro_faenamiento_tipo_inspector'] . "'><button type='submit' class='icono'></button>
                                    </form>
                                </td>
                            </tr>";
            }
            $this->veterinariosAuxiliares = $cadena;
    }

    /**
     *
     * @param
     *            type
     */
    public function guardarDatosCentroFaenamientoTipoInspector()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        $procesoIngreso = false;
        
        $arrayParametros = array(
            'id_tipo_inspector' => $_POST['id_tipo_inspector'],
            'identificador_operador' => $_POST['identificador_operador']
        );

       $centroFaenamientoTipoInspector = $this->lNegocioCentroFaenamientoTipoInspector->buscarCentroFaenamientoTipoInspectorPorIdentificador($arrayParametros);
        
        switch ($centroFaenamientoTipoInspector->current()->tipo_inspector){
            case Constantes::tipo_inspector()->AVES:
            case Constantes::tipo_inspector()->AVESOFICIAL:
                if($centroFaenamientoTipoInspector->current()->contador < 10){
                    $procesoIngreso = true;
                }else{
                    $mensaje = 'No es posible asignar mas de 10 centros de faenamiento.';
                    $estado = 'ERROR';
                }
            
            break;

            case Constantes::tipo_inspector()->MAYORES:
            case Constantes::tipo_inspector()->MAYORESOFICIAL:
            case Constantes::tipo_inspector()->AUXILIAR:
                if($centroFaenamientoTipoInspector->current()->contador < 1){
                    $procesoIngreso = true;
                }else{
                    $mensaje = 'No es posible asignar mas de 1 centro de faenamiento.';
                    $estado = 'ERROR';
                }
            break;
        }

        if($procesoIngreso){
            $this->lNegocioCentroFaenamientoTipoInspector->guardar($_POST);
            $centrosFaenamientoAsociados = $this->lNegocioCentroFaenamientoTipoInspector->buscarCentroFaenamientoInspector($arrayParametros);
            $this->listarVeterinariosAuxiliaresAsignados($centrosFaenamientoAsociados);
            $contenido = $this->veterinariosAuxiliares;
            $mensaje = Constantes::GUARDADO_CON_EXITO;
        }

        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    
    /**
     * Consulta los sitios que tiene un operador de faenamiento
     * @param String
     * @return string Código html para llenar el combo de sitio
     */
    public function comboSitioFaenamiento()
    {
        $arrayParametros = array('identificador_operador' => $_POST['identificadorOperador'], 'tipo_inspector' => $_POST['tipoInspector']);
        $combo = $this->lNegocioCentroFaenamientoTipoInspector->buscarSitioFaenamiento($arrayParametros);
        echo $combo;
        exit();
    }
    
    public function actualizarEstadoAsinacionTipoInspector() {
        $this->lNegocioCentroFaenamientoTipoInspector->actualizarEstadoTipoInspector($_POST);
        Mensajes::exito(Constantes::ELIMINADO_CON_EXITO);
    }
}
