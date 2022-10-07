<?php
/**
 * Controlador Movilizacion
 *
 * Este archivo controla la lógica del negocio del modelo:  MovilizacionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-09-02
 * @uses    MovilizacionControlador
 * @package MovilizacionVegetal
 * @subpackage Controladores
 */
namespace Agrodb\MovilizacionVegetal\Controladores;

use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosModelo;
use Agrodb\Catalogos\Modelos\SubtipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\SubtipoProductosModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\MovilizacionVegetal\Modelos\DetalleMovilizacionLogicaNegocio;
use Agrodb\MovilizacionVegetal\Modelos\DetalleMovilizacionModelo;
use Agrodb\MovilizacionVegetal\Modelos\MovilizacionLogicaNegocio;
use Agrodb\MovilizacionVegetal\Modelos\MovilizacionModelo;
use Agrodb\RegistroOperador\Modelos\AreasLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\AreasModelo;
use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresModelo;
use Agrodb\RequisitosComercializacion\Modelos\RequisitosLogicaNegocio;
use Agrodb\RequisitosComercializacion\Modelos\RequisitosModelo;
use Agrodb\Core\JasperReport;

class MovilizacionControlador extends BaseControlador
{

    private $lNegocioMovilizacion = null;
    private $modeloMovilizacion = null;
    
    private $lNegocioDetalleMovilizacion = null;
    private $modeloDetalleMovilizacion = null;
    
    private $lNegocioOperadores = null;
    private $modeloOperadores = null;
    
    private $lNegocioAreas = null;
    private $modeloAreas = null;
    
    private $lNegocioSubtipoProductos = null;
    private $modeloSubtipoProductos = null;
    
    private $lNegocioProductos = null;
    private $modeloProductos = null;
    
    private $lNegocioRequisitos = null;
    private $modeloRequisitos = null;

    private $accion = null;
    private $urlPdf = null;
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        
        $this->lNegocioMovilizacion = new MovilizacionLogicaNegocio();
        $this->modeloMovilizacion = new MovilizacionModelo();
        
        $this->lNegocioDetalleMovilizacion = new DetalleMovilizacionLogicaNegocio();
        $this->modeloDetalleMovilizacion = new DetalleMovilizacionModelo();
        
        $this->lNegocioOperadores = new OperadoresLogicaNegocio();
        $this->modeloOperadores = new OperadoresModelo();
        
        $this->lNegocioAreas = new AreasLogicaNegocio();
        $this->modeloAreas = new AreasModelo();
        
        $this->lNegocioSubtipoProductos = new SubtipoProductosLogicaNegocio();
        $this->modeloSubtipoProductos = new SubtipoProductosModelo();
        
        $this->lNegocioProductos = new ProductosLogicaNegocio();
        $this->modeloProductos = new ProductosModelo();
        
        $this->lNegocioRequisitos = new RequisitosLogicaNegocio();
        $this->modeloRequisitos = new RequisitosModelo();
        
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
        $this->cargarPanelMovilizaciones();
        
        require APP . 'MovilizacionVegetal/vistas/listaMovilizacionVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Permiso de Movilización";
        $this->formulario = 'nuevo';
        
        require APP . 'MovilizacionVegetal/vistas/formularioMovilizacionVista.php';
    }

    /**
     * Método para registrar en la base de datos Movilizacion
     */
    public function guardar()
    {
        switch($_POST["estado_movilizacion"]){
            case '':{
                $numero = $this->generarCodigoMovilizacion($_POST["codigo_provincia_origen"], $_POST["codigo_provincia_destino"]);
                
                $anio = date('Y');
                $mes = date('m');
                $dia = date('d');
                
                if ($_POST["id_movilizacion"] === '') {
                    $_POST['numero_permiso'] = $numero;
                    $_POST['secuencial_movilizacion'] = substr($numero, -5);
                    $_POST['ruta_certificado'] = MOV_VEG_CERT_URL . "certificado/" . $anio . "/" . $mes . "/" . $dia . "/" . $numero . ".pdf";
                }
                  
                $_POST["id_movilizacion"] = $this->lNegocioMovilizacion->guardar($_POST);
          
                //Guardar registro de detalles
                if (count($_POST['iSubtipoProducto'])>0){
                    
                    for($i=0; $i < count($_POST['iSubtipoProducto']); $i++){
                        $arrayParametros = array(   'id_movilizacion' =>  $_POST["id_movilizacion"],
                                                    'id_area_origen' =>  $_POST['iAreaOrigen'][$i],
                                                    'area_origen' =>  $_POST['nAreaOrigen'][$i],
                                                    'id_area_destino' =>  $_POST['iAreaDestino'][$i],
                                                    'area_destino' => $_POST['nAreaDestino'][$i],
                                                    'id_subtipo_producto' =>  $_POST['iSubtipoProducto'][$i],
                                                    'subtipo_producto' =>  $_POST['nSubtipoProducto'][$i],
                                                    'id_producto' =>  $_POST['iProducto'][$i],
                                                    'producto' => $_POST['nProducto'][$i],
                                                    'unidad' => $_POST['iUnidad'][$i],
                                                    'cantidad' =>  $_POST['iCantidad'][$i]
                                                );
                        
                        $this->lNegocioDetalleMovilizacion->guardar($arrayParametros);
                    }
                    
                }
                
                break;
            }
            
            default:{
                break;
            }
        }       

    }   

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Movilizacion
     */
    public function editar()
    {
        $this->accion = "Movilización";
        $this->formulario = 'abrir';
        
        $this->modeloMovilizacion = $this->lNegocioMovilizacion->buscar($_POST["id"]);
        $sitio = $this->lNegocioMovilizacion->buscarCantonParroquiaSitios($_POST["id"]);
        
        $this->modeloMovilizacion->setCantonOrigen($sitio['canton_origen']);
        $this->modeloMovilizacion->setParroquiaOrigen($sitio['parroquia_origen']);
        
        $this->modeloMovilizacion->setCantonDestino($sitio['canton_destino']);
        $this->modeloMovilizacion->setParroquiaDestino($sitio['parroquia_destino']);
        
        require APP . 'MovilizacionVegetal/vistas/formularioMovilizacionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Movilizacion
     */
    public function borrar()
    {
        $this->lNegocioMovilizacion->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de Movilizacion
     */
    public function tablaHtmlMovilizacion($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_movilizacion'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'MovilizacionVegetal\movilizacion"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                    		  <td>' . ++ $contador . '</td>
                    <td>' . $fila['numero_permiso'] . '</td>
                    <td>' . $fila['sitio_origen'] . '</td>
                    <td>' . $fila['sitio_destino'] . '</td>
                    <td>' . $fila['estado_movilizacion'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    
    /**
     * Método para listar la información del usuario logueado
     */
    public function consultarTipoUsuario($identificador)
    {
        $consulta = "identificador='".$identificador."'";
        
        $usuario = $this->lNegocioOperadores->buscarLista($consulta);
        $fila = $usuario->current();
        
        $usuario = array('identificador' => $fila->identificador,
                         'razon_social' => $fila->razon_social
        );
        
        return $usuario;
    }
        
    /**
     * Construye el código HTML para desplegar panel de busqueda para Movilizaciones
     */
    public function cargarPanelMovilizaciones()
    {
        $operador = false;
        //cambiar validacion a  ===
        if($_SESSION['nombreProvincia'] === null){//Es operador
            $identificadorOperador = $this->consultarTipoUsuario($_SESSION['usuario']);
            $operador = true;
        }
                
        $this->panelBusquedaMovilizaciones = '<table class="filtro" style="width: 100%;">
                                                <input type="hidden" id="identificadorUsuario" name="identificadorUsuario" value="'.$_SESSION['usuario'].'" readonly="readonly" >
                                                <input type="hidden" id="provinciaTecnico" name="provinciaTecnico" value="'.$_SESSION['nombreProvincia'].'" readonly="readonly" >
                                				
                                                <tbody>
                                                    <tr>
                                                        <th colspan="2">Consultar permiso de movilización:</th>
                                                    </tr>

                                					<tr  style="width: 100%;">
                                						<td >*Identificación operador: </td>
                                						<td>
                                							<input id="identificadorOperador" type="text" name="identificadorOperador" value="'.($_SESSION['nombreProvincia']===null? $_SESSION['usuario']:'').'" '.($operador == true? "readonly='readonly'":"") .' style="width: 90%" maxlength="13">
                                						</td>

                                						<td >*Nombre operador: </td>
                                						<td>
                                							<input id="nombreOperador" type="text" name="nombreOperador" value="'.($operador == true? ($identificadorOperador['razon_social']!=''? $identificadorOperador['razon_social']:''):'').'" '.($operador == true? "readonly='readonly'":"") .' style="width: 90%" maxlength="128">
                                						</td>
                                					</tr>

                                                    <tr  style="width: 100%;">
                                						<td >*Nombre Sitio: </td>
                                						<td>
                                							<input id="nombreSitio" type="text" name="nombreSitio" style="width: 90%" maxlength="128">
                                						</td>

                                						<td >*Nº Permiso: </td>
                                						<td>
                                							<input id="numPermiso" type="number" name="numPermiso" style="width: 90%" maxlength="16">
                                						</td>
                                					</tr>

                                                    <tr  style="width: 100%;" colspan=2>
                                						<td >*Estado: </td>
                                						<td colspan=3>
                                                            <select id="estadoMovilizacion" name="estadoMovilizacion" style="width: 97%;" required>' . $this->comboEstadosMovilizaciones() . '</select>
                                						</td>
                                					</tr>

    												<tr  style="width: 100%;">
                                						<td >Fecha Inicio: </td>
                                						<td>
                                							<input id="fechaInicio" type="text" name="fechaInicio" style="width: 90%" readonly="readonly">
                                						</td>

                                						<td >Fecha Fin: </td>
                                						<td>
                                							<input id="fechaFin" type="text" name="fechaFin" style="width: 90%" readonly="readonly">
                                						</td>
                                					</tr>

                                					<tr>
                                						<td colspan="2" style="text-align: end;">
                                							<button id="btnFiltrar">Consultar</button>
                                						</td>
                                					</tr>
                                				</tbody>
                                			</table>';
    }
    
    /**
     * Combo de estados para movilizaciones
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboEstadosMovilizaciones($opcion = null)
    {
        $combo = "";
        if ($opcion == "Vigente") {
            $combo .= '<option value="Vigente" selected="selected">Vigente</option>';
            $combo .= '<option value="Caducado">Caducado</option>';
            $combo .= '<option value="Anulado">Anulado</option>';
        } else if ($opcion == "Caducado") {
            $combo .= '<option value="Vigente" >Vigente</option>';
            $combo .= '<option value="Caducado" selected="selected">Caducado</option>';
            $combo .= '<option value="Anulado">Anulado</option>';
        } else if ($opcion == "Anulado") {
            $combo .= '<option value="Vigente" >Vigente</option>';
            $combo .= '<option value="Caducado">Caducado</option>';
            $combo .= '<option value="Anulado" selected="selected">Anulado</option>';
        } else {
            $combo .= '<option value="Vigente" selected="selected">Vigente</option>';
            $combo .= '<option value="Caducado">Caducado</option>';
            $combo .= '<option value="Anulado">Anulado</option>';
        }
        
        return $combo;
    }
    
    /**
     * Método para obtener los datos del operador de origen
     * */
    public function buscarOperadoresOrigen(){
        
        $arrayParametros = array('nombre_provincia' =>  $_POST["provincia"],
                                 'identificador' =>  $_POST["identificador_operador"],
                                 'razon_social' =>  $_POST["nombre_operador"],
                                 'area' =>  'SV'
        );
        
        $operadores = $this->lNegocioOperadores->obtenerOperadorSitioMovilizacionOrigen($arrayParametros);

        $comboSitio = "";
        $comboSitio .= '<option value="">Seleccione....</option>';
        
        foreach ($operadores as $item)
        {
            $comboSitio .= '<option value="' . $item->id_sitio . '" data-identificador="'. $item->identificador.'" data-nombre="'. $item->razon.'" data-nombre_sitio="'. $item->sitio.'" data-codigo_sitio="'. $item->identificador . '.'. $item->codigo_provincia . $item->codigo .'"  data-codigo_provincia="'. $item->codigo_provincia.'">' . $item->identificador .'-'. $item->razon  .'-'. $item->sitio . '</option>';
        }

        echo $comboSitio;
        exit();
    }
    
    /**
     * Método para obtener los datos del operador de destino
     * */
    public function buscarOperadoresDestino(){
        
        $arrayParametros = array('nombre_provincia' =>  $_POST["provincia"],
                                 'identificador' =>  $_POST["identificador_operador"],
                                 'razon_social' =>  $_POST["nombre_operador"],
                                 'area' =>  'SV',
                                 'id_sitio_origen' => $_POST["id_sitio_origen"]
        );
        
        $operadores = $this->lNegocioOperadores->obtenerOperadorSitioMovilizacionDestino($arrayParametros);
        
        $comboSitio = "";
        $comboSitio .= '<option value="">Seleccione....</option>';
        
        foreach ($operadores as $item)
        {
            $comboSitio .= '<option value="' . $item->id_sitio . '" data-identificador="'. $item->identificador.'" data-nombre="'. $item->razon.'" data-nombre_sitio="'. $item->sitio.'" data-codigo_sitio="'. $item->identificador . '.'. $item->codigo_provincia . $item->codigo .'" data-codigo_provincia="'. $item->codigo_provincia.'">' . $item->identificador .'-'. $item->razon  .'-'. $item->sitio . '</option>';
        }
        
        echo $comboSitio;
        exit();
    }
    
    /**
     * Método para generar la numeración de las movilizaciones
     */
    public function generarCodigoMovilizacion($codSitioOrigen, $codSitioDestino)
    {
        
        return $this->lNegocioMovilizacion->buscarNumeroMovilizacion($codSitioOrigen, $codSitioDestino);
    }
    
    /**
     * Método para listar las movilizaciones registradas
     */
    public function listarMovilizacionesFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $identificadorUsuario = $_POST["identificadorUsuario"];
        $provinciaTecnico = $_POST["provinciaTecnico"];
        $identificadorOperador = $_POST["identificadorOperador"];
        $nombreOperador = $_POST["nombreOperador"];
        $nombreSitio = $_POST["nombreSitio"];
        $numPermiso = $_POST["numPermiso"];
        $estadoMovilizacion = $_POST["estadoMovilizacion"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        
        $arrayParametros = array(
            'identificadorUsuario' => $identificadorUsuario,
            'provinciaTecnico' => $provinciaTecnico,
            'identificador_operador_origen' => $identificadorOperador,
            'nombre_operador_origen' => $nombreOperador,
            'sitio_origen' => $nombreSitio,
            'numero_permiso' => $numPermiso,
            'estado_movilizacion' => $estadoMovilizacion,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin
        );
        
        $movilizaciones = $this->lNegocioMovilizacion->buscarMovilizacionesXFiltro($arrayParametros);
        
        $this->tablaHtmlMovilizacion($movilizaciones);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
    
    /**
     * Método para obtener las áreas del sitio de origen del operador
     * */
    public function buscarAreasOperadoresOrigen(){
        
        $arrayParametros = array('id_sitio' =>  $_POST["id_sitio_origen"],
                                 'area' =>  'SV'                                 
                                );

        $operador = $_POST["identificador_operador"];
        $areas = $this->lNegocioAreas->obtenerAreasOperadorMovilizacionOrigen($arrayParametros);
        
        $comboArea = "";
        $comboArea .= '<option value="">Seleccione....</option>';
        
        foreach ($areas as $item)
        {
            $comboArea .= '<option value="' . $item->id_area . '" data-nombre="'. $item->nombre_area.'" data-codigo_area="'. $operador.'.'.$item->codigo_area.'">' . $item->nombre_area . '</option>';
        }
        
        echo $comboArea;
        exit();
    }
    
    /**
     * Método para obtener las áreas del sitio de origen del operador
     * */
    public function buscarAreasOperadoresDestino(){
        
        $consulta = "id_sitio = ".$_POST["id_sitio_destino"]." and id_area not in (".$_POST["id_area_origen"].")";
        
        $operador = $_POST["identificador_operador"];
        $codigo = $_POST["codigo_sitio"];
        $areas = $this->lNegocioAreas->buscarLista($consulta);
        
        $comboArea = "";
        $comboArea .= '<option value="">Seleccione....</option>';
        
        foreach ($areas as $item)
        {
            $comboArea .= '<option value="' . $item->id_area . '" data-nombre="'. $item->nombre_area.'" data-codigo_area="'. $codigo.$item->codigo.$item->secuencial. '">' . $item->nombre_area . '</option>';
        }
        
        echo $comboArea;
        exit();
    }
    
    
    /**
     * Método para obtener los subtipos de producto de las áreas del sitio de origen del operador
     * */
    public function buscarSubtipoProductoAreasOperadoresOrigen(){
        
        $id_area_origen = $_POST["id_area_origen"];
        
        $arrayParametros = array(
            'id_area_origen' => $id_area_origen,
            'area' => 'SV'
        );
        
        $subtipoProductos = $this->lNegocioSubtipoProductos->obtenerSubtipoProductoAreasOperadoresOrigen($arrayParametros);
        
        $comboSubtipoProducto = "";
        $comboSubtipoProducto .= '<option value="">Seleccione....</option>';
        
        foreach ($subtipoProductos as $item)
        {
            $comboSubtipoProducto .= '<option value="' . $item->id_subtipo_producto . '" >' . $item->nombre . '</option>';
        }
        
        echo $comboSubtipoProducto;
        exit();
    }
    
    /**
     * Método para obtener los productos de los subtipos de producto de las áreas del sitio de origen del operador
     * */
    public function buscarProductoAreasOperadoresOrigen(){
        
        $id_area_origen = $_POST["id_area_origen"];
        $id_subtipo_producto = $_POST["id_subtipo_producto"];
        
        $arrayParametros = array(
            'id_area_origen' => $id_area_origen,
            'id_subtipo_producto' => $id_subtipo_producto,
            'area' => 'SV'
        );
        
        $productos = $this->lNegocioProductos->obtenerProductoAreasOperadoresOrigen($arrayParametros);
        
        $comboProductos = "";
        $comboProductos .= '<option value="">Seleccione....</option>';
        
        foreach ($productos as $item)
        {
            $comboProductos .= '<option value="' . $item->id_producto . '" >' . $item->nombre_comun . '</option>';
        }
        
        echo $comboProductos;
        exit();
    }
    
    /**
     * Método para buscar los cantones y parroquias de los sitios de origen y destino
     */
    public function obtenerCantonParroquiaSitios($idMovilizacion)
    {
        return $this->lNegocioMovilizacion->buscarCantonParroquiaSitios($idMovilizacion);
    }
    
    /**
     * Combo de unidades para movilizaciones
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboUnidadesMovilizaciones($opcion = null)
    {
        $combo = "";
        if ($opcion == "Unidad") {
            $combo .= '<option value="Unidad" selected="selected">Unidad</option>';
            $combo .= '<option value="Kilogramos">Kilogramos</option>';
            $combo .= '<option value="Toneladas">Toneladas</option>';
        } else if ($opcion == "Kilogramos") {
            $combo .= '<option value="Unidad" >Unidad</option>';
            $combo .= '<option value="Kilogramos" selected="selected">Kilogramos</option>';
            $combo .= '<option value="Toneladas">Toneladas</option>';
        } else if ($opcion == "Toneladas") {
            $combo .= '<option value="Unidad" >Unidad</option>';
            $combo .= '<option value="Kilogramos">Kilogramos</option>';
            $combo .= '<option value="Toneladas" selected="selected">Toneladas</option>';
        } else {
            $combo .= '<option value="Unidad">Unidad</option>';
            $combo .= '<option value="Kilogramos">Kilogramos</option>';
            $combo .= '<option value="Toneladas">Toneladas</option>';
        }
        
        return $combo;
    }
    
    /**
     * Método para obtener los productos de los subtipos de producto de las áreas del sitio de origen del operador
     * */
    public function buscarRequisitoMovilizacion(){
        
        $id_producto = $_POST["id_producto"];
        
        $arrayParametros = array(
            'id_producto' => $id_producto,
            'tipo' => 'Movilización'
        );
        
        $requisitos = $this->lNegocioRequisitos->obtenerRequisitosImpresosProducto($arrayParametros);//buscarLista
        
        $detalleImpreso = "";
        
        foreach ($requisitos as $item)
        {
            $detalleImpreso .= '<b>' . $item->nombre . '</b> ';
            $detalleImpreso .= '<p>' . $item->detalle . '</p>';
        }
        
        echo $detalleImpreso;
        exit();
    }
    
    /**
     * Función para generar el certificado 
     */
    public function generarCertificadoMovilizacion()
    {
        $estado = 'exito';
        $mensaje = 'Certificado generado con éxito';
        $contenido = '';
        
        $anio = date('Y');
        $mes = date('m');
        $dia = date('d');
        
        $this->guardar();

        if (strlen($_POST['id_movilizacion'])>0) {
            $this->lNegocioMovilizacion->generarCertificado($_POST['id_movilizacion'], $_POST['numero_permiso']);
            
            $contenido = MOV_VEG_CERT_URL . "certificado/" . $anio . "/" . $mes . "/" . $dia . "/" . $_POST['numero_permiso'] . ".pdf";

        } else {
            $mensaje = 'No se pudo generar el certificado';
            $estado = 'FALLO';
        }
        
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    
    
    /**
     * Método para desplegar el certificado PDF
     */
    public function mostrarReporte()
    {
        $this->urlPdf = $_POST['id'];
        require APP . 'MovilizacionVegetal/vistas/visorPDF.php';
    }
    
    /**
     * Método para generar el reporte de movilizaciones en excel
     */
    public function exportarMovilizacionesExcel() {
        $idProvinciaFiltro = $_POST["idProvinciaFiltro"];
        $provinciaFiltro = $_POST["provinciaFiltro"];        
        $idTipoProductoFiltro = $_POST["idTipoProductoFiltro"];        
        $idSubtipoProductoFiltro = $_POST["idSubtipoProductoFiltro"];       
        $estadoFiltro = $_POST["estadoFiltro"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        
        $arrayParametros = array(
            'id_provincia' => $idProvinciaFiltro,
            'provincia' => $provinciaFiltro,        
            'id_tipo_producto' => $idTipoProductoFiltro,
            'id_subtipo_producto' => $idSubtipoProductoFiltro,            
            'estado' => $estadoFiltro,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        );
        
        $movilizaciones = $this->lNegocioMovilizacion->buscarMovilizacionesNacionalXFiltro($arrayParametros);
        
        $this->lNegocioMovilizacion->exportarArchivoExcelMovilizaciones($movilizaciones);
    }
    
    /**
     * Proceso automático para cambiar de estado las movilizaciones expiradas
     */
    public function paCambioEstadoMovilizacion(){
        
        echo "\n".'Proceso Automático de cambio de estado de movilizaciones'."\n"."\n";
        
        $consulta = "   fecha_fin_movilizacion <='".date("Y-m-d")."' and
                        hora_fin_movilizacion <= '".date("H:i:s")."' and
                        estado_movilizacion = 'Vigente'";
        
        $movilizaciones = $this->lNegocioMovilizacion->buscarLista($consulta);
        
        foreach ($movilizaciones as $fila) {
            $arrayParametros = array(
                                        'id_movilizacion' => $fila['id_movilizacion'],
                                        'estado_movilizacion' => 'Caducado'
                                    );
            
            $this->lNegocioMovilizacion->guardar($arrayParametros);
            
            echo 'El certificado de movilización ' . $fila['id_movilizacion']. ' - ' . $fila['numero_permiso']. ' cambia de estado a Caducado'."\n";
        }
        echo "\n";
    }
}