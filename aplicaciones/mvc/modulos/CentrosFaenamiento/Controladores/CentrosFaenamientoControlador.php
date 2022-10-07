<?php
/**
 * Controlador CentrosFaenamiento
 *
 * Este archivo controla la lógica del negocio del modelo:  CentrosFaenamientoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2018-11-21
 * @uses    CentrosFaenamientoControlador
 * @package CentrosFaenamiento
 * @subpackage Controladores
 */
namespace Agrodb\CentrosFaenamiento\Controladores;

use Agrodb\CentrosFaenamiento\Modelos\CentrosFaenamientoLogicaNegocio;
use Agrodb\CentrosFaenamiento\Modelos\CentrosFaenamientoModelo;
use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
use Agrodb\CentrosFaenamiento\Modelos\DetalleCantonProvinciaLogicaNegocio;
use Agrodb\CentrosFaenamiento\Modelos\DetalleCantonProvinciaModelo;


class CentrosFaenamientoControlador extends BaseControlador
{

    private $lNegocioCentrosFaenamiento = null;

    private $modeloCentrosFaenamiento = null;
    
    private $lNegocioDetalleCantonProvincia = null;
    
    private $modeloDetalleCantonProvincia = null;

    private $accion = null;
    
    private $listarCantonProvincia=null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioCentrosFaenamiento = new CentrosFaenamientoLogicaNegocio();
        $this->modeloCentrosFaenamiento = new CentrosFaenamientoModelo();
        
        $this->lNegocioDetalleCantonProvincia = new DetalleCantonProvinciaLogicaNegocio();
        $this->modeloDetalleCantonProvincia = new DetalleCantonProvinciaModelo();
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
        $this->cargarPanelCentroFaenamiento();
        $this->perfilUsuario();
        require APP . 'CentrosFaenamiento/vistas/listarCentrosFaenamientoVista.php';
    }
    
    /**
     * Método para registrar en la base de datos -CentrosFaenamiento
     */
    public function guardar()
    {
        $this->lNegocioCentrosFaenamiento->guardar($_POST);
    }
    /**
     * Método para registrar en la base de datos -CentrosFaenamiento
     */
    public function guardarRegistros()
    {
        $this->lNegocioCentrosFaenamiento->guardarRegistros($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: CentrosFaenamiento
     */
    public function editar()
    {
            $this->accion = "Agregar información";

            $datoAcceso = $_POST["id"];
            $datos = explode('-', $datoAcceso);
            $identificadorOperador = $datos[0];
            $idSitio = $datos[1];
            $idArea = $datos[2];
            $idOperadorTipoOperacion = $datos[3];

            $arrayParametros = array('identificador_operador' =>$identificadorOperador, 
                                        'id_area_tipo_operacion' => 'AI', 
                                        'codigo' => 'FAE', 
                                        'id_sitio'=>$idSitio,
                                        'id_area'=> $idArea,
                                        'id_operador_tipo_operacion'=>$idOperadorTipoOperacion);
              
            $datosCentroFenamiento = $this->lNegocioCentrosFaenamiento->buscarFaenadorPorIdentificadorOperador($arrayParametros);
            $this->modeloCentrosFaenamiento->setOptions((array) $datosCentroFenamiento->current());
            
            if($this->modeloCentrosFaenamiento->getTipoHabilitacion() == 'Intercantonal' || $this->modeloCentrosFaenamiento->getTipoHabilitacion() == 'Interprovincial'){
                $this->listarCantonProvincia = $this->listarCantonProvinciasGuardados($this->modeloCentrosFaenamiento->getTipoHabilitacion(),$this->modeloCentrosFaenamiento->getIdCentroFaenamiento(),$this->modeloCentrosFaenamiento->getProvincia());
            }
            
            require APP . 'CentrosFaenamiento/vistas/formularioCentrosFaenamientoVista.php';
    }

    /**
     * Construye el código HTML para desplegar la lista de - CentrosFaenamiento
     */
    public function tablaHtmlCentrosFaenamiento($tabla)
    {
          
            $contador = 0;
            foreach ($tabla as $fila) {
                
                $codigo = $fila['identificador_operador'] . '-' . $fila['id_sitio'].'-'.$fila['id_area'].'-'.$fila['id_operador_tipo_operacion'];
                
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $codigo . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CentrosFaenamiento\CentrosFaenamiento"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . $fila['codigo'] . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['identificador_operador'] . '</b></td>
          <td>' . $fila['razon_social'] . ' </td>
          <td>' . $fila['nombre_area'] . '</td>
          <td>' . $fila['nombre_lugar'] . '</td>
          <td>' . $fila['provincia'] . '</td>
          <td>' .  $fila['criterio_funcionamiento'] . '</td>
          </tr>
            '
                );
        }
    }

    /**
     * Método para listar los centros de faenamiento por identificador del operador
     * */
    public function listarCentroFaenamientoPorIdentificador()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        $identificadorOperador = $_POST["identificadorOperador"];
        $this->perfilUsuario();
        if(in_array('PFL_ADM_CF_PC', $this->perfilUsuario)){
            if(isset($_POST['provincia'])){
                $arrayParametros = array('identificador_operador' => $identificadorOperador,'provincia' => $_POST['provincia'], 'id_area_tipo_operacion' =>'AI', 'codigo' => 'FAE');
                $centrosFaenamiento = $this->lNegocioCentrosFaenamiento->buscarFaenadorPorIdentificadorOperador($arrayParametros);
                        if($centrosFaenamiento->count()){
                                        $this->tablaHtmlCentrosFaenamiento($centrosFaenamiento);
                                        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
                        }else{
                                        $centro = array();
                                        $this->tablaHtmlCentrosFaenamiento($centro);
                                        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
                                        $mensaje = 'No existen registros';
                                        $estado = 'FALLO';
                        }
            }else{
                $centro = array();
                $this->tablaHtmlCentrosFaenamiento($centro);
                $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
                $mensaje = 'No ha seleccionado una provincia..!!';
                $estado = 'FALLO';
            }
        }else{
            $arrayParametros = array('identificador_operador' => $identificadorOperador,'provincia' => $_SESSION['nombreProvincia'], 'id_area_tipo_operacion' =>'AI', 'codigo' => 'FAE');
            $centrosFaenamiento = $this->lNegocioCentrosFaenamiento->buscarFaenadorPorIdentificadorOperador($arrayParametros);
            
            if($centrosFaenamiento->count()){
                $this->tablaHtmlCentrosFaenamiento($centrosFaenamiento);
                $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
            }else{
                $arrayParametros = array('identificador_operador' => $identificadorOperador, 'id_area_tipo_operacion' =>'AI', 'codigo' => 'FAE');
                $centrosFaenamiento = $this->lNegocioCentrosFaenamiento->buscarFaenadorPorIdentificadorOperador($arrayParametros);
                
                if($centrosFaenamiento->count()){
                    $centro = array();
                    $this->tablaHtmlCentrosFaenamiento($centro);
                    $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
                    $mensaje = 'El Centro de Faenamiento buscado se encuentra fuera de su jurisdicción';
                    $estado = 'FALLO';
                }else{
                    $centro = array();
                    $this->tablaHtmlCentrosFaenamiento($centro);
                    $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
                    $mensaje = 'No existen registros';
                    $estado = 'FALLO';
                }
            }
        }
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    /**
     * crear cantones / Provincias
     */
    public function listarCantonProvincias(){
        
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        if(isset($_POST['tipo_habilitacion'])){
            $contenido = $this->listarCantonProvinciasGuardados($_POST['tipo_habilitacion'], $_POST['id_centro_faenamiento'],$_POST['provincia']);
        }else{
            $estado = 'ERROR';
            $mensaje = 'No existe el tipo de habilitación';
        }
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
    /**
     * crear lista de cantones / Provincias
     */
    public function listarCantonProvinciasGuardados($tipoHabilitacion, $idCentroFaenamiento,$provincia,$disable=null){
            $localizacion = new LocalizacionLogicaNegocio();
            $titulo='';
            if($tipoHabilitacion== 'Intercantonal'){
                $combo = $localizacion->buscarProvinciasEc();
                foreach ($combo as $value) {
                    if($value->nombre == $provincia){
                        $idProvincia = $value->id_localizacion;
                    }
                }
                $combo = $localizacion->buscarCantones($idProvincia);
                $titulo='Cantones a movilizar:';
            }else{
                $combo = $localizacion->buscarProvinciasEc();
                $titulo='Provincias a movilizar:';
            }
            if($disable != ''){
                
            }
            $datos='';
            $i=0;
            $ban=1;
            foreach ($combo as $item) {
                if($ban){
                    $datos .= '<tr>';
                    $datos .= '<td><input id="selectCanProv" name="selectCanProv" type="checkbox" value="todos" onclick="activarDesactivar(this);"> Seleccionar todos</td>';
                    $datos .= '<tr>';
                }$ban=0;
                
                if($i==0){
                    $datos .= '<tr>';
                }
                $verificar = $this->modeloDetalleCantonProvincia->buscarLista('id_centro_faenamiento ='.$idCentroFaenamiento.' and id_localizacion='.$item->id_localizacion);
                if($verificar->count()){
                    $datos .= '<td><input class="cantProv" name="cantonProvincia[]" type="checkbox" checked value="'.$item->id_localizacion.'"> '.$item->nombre.'</td>';
                }else{
                    $datos .= '<td><input class="cantProv" name="cantonProvincia[]" type="checkbox" value="'.$item->id_localizacion.'"> '.$item->nombre.'</td>';
                }
                $i++;
                if($i==3){
                    $datos .= '<tr>';
                    $i=0;
                }
            }
            
            $html = '<label for="cantonProvincia">'.$titulo.'</label>
			<table  style="width: 100%;">
			'.$datos.'
			</table>
         ';
            return $html;
    }
    //**************************validar codigo ingresado 26-05-2021*****************************
    public function validarCodigo(){
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        if(isset($_POST['codigo'])){
            $verificar=$this->lNegocioCentrosFaenamiento->buscarLista("codigo='".$_POST['codigo']."'");
            if($verificar->count() > 0){
                $estado = 'FALLO';
                $mensaje='Código ingresado ya está asignado..!!';
            }
        }else{
            $estado = 'FALLO';
            $mensaje='Error en verificar código ingresado..!!';
        }
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
  
}
