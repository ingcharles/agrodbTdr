<?php
/**
 * Controlador Fiscalizacion
 *
 * Este archivo controla la lógica del negocio del modelo:  FiscalizacionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-09-02
 * @uses    FiscalizacionControlador
 * @package MovilizacionVegetal
 * @subpackage Controladores
 */
namespace Agrodb\MovilizacionVegetal\Controladores;

use Agrodb\MovilizacionVegetal\Modelos\FiscalizacionLogicaNegocio;
use Agrodb\MovilizacionVegetal\Modelos\FiscalizacionModelo;
use Agrodb\MovilizacionVegetal\Modelos\MovilizacionLogicaNegocio;
use Agrodb\MovilizacionVegetal\Modelos\MovilizacionModelo;
use Agrodb\MovilizacionVegetal\Modelos\DetalleMovilizacionLogicaNegocio;
use Agrodb\MovilizacionVegetal\Modelos\DetalleMovilizacionModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class FiscalizacionControlador extends BaseControlador
{

    private $lNegocioFiscalizacion = null;
    private $modeloFiscalizacion = null;
    
    private $lNegocioMovilizacion = null;
    private $modeloMovilizacion = null;
    
    private $lNegocioDetalleMovilizacion = null;
    private $modeloDetalleMovilizacion = null;

    private $accion = null;
    private $formulario = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioFiscalizacion = new FiscalizacionLogicaNegocio();
        $this->modeloFiscalizacion = new FiscalizacionModelo();
        
        $this->lNegocioMovilizacion = new MovilizacionLogicaNegocio();
        $this->modeloMovilizacion = new MovilizacionModelo();
        
        $this->lNegocioDetalleMovilizacion = new DetalleMovilizacionLogicaNegocio();
        $this->modeloDetalleMovilizacion = new DetalleMovilizacionModelo();
        
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
        $this->cargarPanelFiscalizaciones();
        
        require APP . 'MovilizacionVegetal/vistas/listaFiscalizacionVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Fiscalizacion";
        require APP . 'MovilizacionVegetal/vistas/formularioFiscalizacionVista.php';
    }

    /**
     * Método para registrar en la base de datos -Fiscalizacion
     */
    public function guardar()
    {       
        $arrayParametros = [];
        
        $_POST["identificador_fiscalizador"] = $_SESSION["usuario"];
        $_POST["nombre_fiscalizador"] = $_SESSION["datosUsuario"];
        $_POST["provincia_fiscalizacion"] = $_SESSION["nombreProvincia"];
        
        //Buscar nuevamente el estado de la movilizacion, verificar si esta vigente y guardar, caso contrario se rechaza
        $this->modeloMovilizacion = $this->lNegocioMovilizacion->buscar($_POST["id_movilizacion"]);
        
        if($this->modeloMovilizacion->getEstadoMovilizacion() === 'Vigente'){
            //Guardar registro de fiscalización
            $_POST["id_fiscalizacion"] = $this->lNegocioFiscalizacion->guardar($_POST);
            
            //Guardar detalle de movilizaciones actualizado
            if($_POST['accion_correctiva'] === 'Modificar permiso'){
                if (count($_POST['dtxtIdRegistro'])>0){
                    
                    for($i=0; $i < count($_POST['dtxtIdRegistro']); $i++){
                        
                        if($_POST['dtxtCantidad'][$i] != $_POST['dtxtCantidadO'][$i]){      
                            
                            $arrayParametros = array(   'id_detalle_movilizacion' =>  $_POST['dtxtIdRegistro'][$i],
                                                        'cantidad' =>  $_POST['dtxtCantidad'][$i],
                                                        'fecha_modificacion' => 'now()',
                                                        'id_fiscalizacion' =>  $_POST["id_fiscalizacion"]
                            );
                        }
                        
                        if (count($arrayParametros)>0){
                            $this->lNegocioDetalleMovilizacion->guardar($arrayParametros);
                        }
                    }
                    
                }
            }
            
            if($_POST['accion_correctiva'] === 'Anulado'){
                $_POST['estado_movilizacion'] = 'Anulado';                
            }
            
            //Guardar estado de fiscalización en el registro de movilización
            $arrayParametros = array(
                'id_movilizacion' => $_POST['id_movilizacion'],
                'estado_fiscalizacion' => 'Fiscalizado',
                'estado_movilizacion' => $_POST['estado_movilizacion']
            );
            
            $this->lNegocioMovilizacion->guardar($arrayParametros);
            
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }else{
            Mensajes::fallo(Constantes::ERROR_VIGENCIA);
        }        
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Fiscalizacion
     */
    public function editar()
    {
        $this->accion = "Registro de Permiso de Movilización";
        
        $this->modeloMovilizacion = $this->lNegocioMovilizacion->buscar($_POST["id"]);
        $sitio = $this->lNegocioMovilizacion->buscarCantonParroquiaSitios($_POST["id"]);
        
        $this->modeloMovilizacion->setCantonOrigen($sitio['canton_origen']);
        $this->modeloMovilizacion->setParroquiaOrigen($sitio['parroquia_origen']);
        
        $this->modeloMovilizacion->setCantonDestino($sitio['canton_destino']);
        $this->modeloMovilizacion->setParroquiaDestino($sitio['parroquia_destino']);
        
        require APP . 'MovilizacionVegetal/vistas/formularioFiscalizacionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Fiscalizacion
     */
    public function borrar()
    {
        $this->lNegocioFiscalizacion->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Fiscalizacion
     */
    public function tablaHtmlFiscalizacion($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_movilizacion'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'MovilizacionVegetal\fiscalizacion"
                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
                		<td>' . ++ $contador . '</td>
                		<td style="white - space:nowrap; "><b>' . $fila['numero_permiso'] . '</b></td>
                        <td>' . $fila['sitio_origen'] . '</td>
                        <td>' . $fila['sitio_destino'] . '</td>
                        <td>' . $fila['estado_movilizacion'] . '</td>
                        <td>' . $fila['estado_fiscalizacion'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para Movilizaciones
     */
    public function cargarPanelFiscalizaciones()
    {        
        $this->panelBusquedaFiscalizaciones = '<table class="filtro" style="width: 100%;">
                                                <input type="hidden" id="identificadorUsuario" name="identificadorUsuario" value="'.$_SESSION['usuario'].'" readonly="readonly" >
                                                <input type="hidden" id="provinciaTecnico" name="provinciaTecnico" value="'.$_SESSION['nombreProvincia'].'" readonly="readonly" >
                                                    
                                                <tbody>
                                                    <tr>
                                                        <th colspan="2">Consultar permiso de movilización:</th>
                                                    </tr>
                                                    
                                					<tr  style="width: 100%;">
                                						<td >*Identificación operador: </td>
                                						<td>
                                							<input id="identificadorOperador" type="text" name="identificadorOperador" style="width: 90%" maxlength="13">
                                						</td>
                                							    
                                						<td >*Nombre operador: </td>
                                						<td>
                                							<input id="nombreOperador" type="text" name="nombreOperador" style="width: 90%" maxlength="128">
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
                                							    
                                                    <tr style="width: 100%;" colspan=2>
                                						<td >Tipo de Búsqueda: </td>
                                                        <td colspan=3>
                                                            <select id="tipoBusqueda" name="tipoBusqueda" style="width: 97%;" required>' . $this->comboOrigenDestino() . '</select>
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
     * Combo de busqueda para movilizaciones
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboOrigenDestino($opcion = null)
    {
        $combo = "";
        if ($opcion == "Origen") {
            $combo .= '<option value="Origen" selected="selected">Origen</option>';
            $combo .= '<option value="Destino">Destino</option>';
        } else if ($opcion == "Destino") {
            $combo .= '<option value="Origen" >Origen</option>';
            $combo .= '<option value="Destino" selected="selected">Destino</option>';
        } else {
            $combo .= '<option value="Origen">Origen</option>';
            $combo .= '<option value="Destino">Destino</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de resultados de fiscalización
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboResultadoFiscalizacion($opcion = null)
    {
        $combo = "";
        if ($opcion == "Positivo") {
            $combo .= '<option value="Positivo" selected="selected">Positivo</option>';
            $combo .= '<option value="Negativo">Negativo</option>';
        } else if ($opcion == "Negativo") {
            $combo .= '<option value="Positivo" >Positivo</option>';
            $combo .= '<option value="Negativo" selected="selected">Negativo</option>';
        } else {
            $combo .= '<option value="Positivo">Positivo</option>';
            $combo .= '<option value="Negativo">Negativo</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de resultados de fiscalización
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboAccionCorrectivaFiscalizacion($resultado, $opcion = null)
    {
        $combo = "";
        
        switch($resultado){
            case 'Positivo':{
                if ($opcion == "Fiscalización correcta") {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Fiscalización correcta" selected="selected">Fiscalización correcta</option>';
                    $combo .= '<option value="Modificar permiso">Modificar permiso</option>';
                } else if ($opcion == "Modificar permiso") {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Fiscalización correcta" >Fiscalización correcta</option>';
                    $combo .= '<option value="Modificar permiso" selected="selected">Modificar permiso</option>';
                } else {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Fiscalización correcta">Fiscalización correcta</option>';
                    $combo .= '<option value="Modificar permiso">Modificar permiso</option>';
                }
                
                break;
            }
            case 'Negativo':{
                if ($opcion == "Aplicación de medidas fitosanitarias de emergencia") {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Aplicación de medidas fitosanitarias de emergencia" selected="selected">Aplicación de medidas fitosanitarias de emergencia</option>';
                    $combo .= '<option value="Anulado">Anulado</option>';
                }else if ($opcion == "Aplicación de medidas fitosanitarias de emergencia") {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Aplicación de medidas fitosanitarias de emergencia" selected="selected">Aplicación de medidas fitosanitarias de emergencia</option>';
                    $combo .= '<option value="Anulado" selected="selected">Anulado</option>';
                }else {
                    $combo .= '<option value="">Seleccione....</option>';
                    $combo .= '<option value="Aplicación de medidas fitosanitarias de emergencia">Aplicación de medidas fitosanitarias de emergencia</option>';
                    $combo .= '<option value="Anulado">Anulado</option>';
                }
                break;
            }
            
            default:{
                break;
            }
        }
        
        echo $combo;
        exit();
    }
    
    /**
     * Método para listar las movilizaciones registradas
     */
    public function listarMovilizacionesFiscalizacionFiltradas()
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
        $tipoBusqueda = $_POST["tipoBusqueda"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
        
        $arrayParametros = array(
            'identificadorUsuario' => $identificadorUsuario,
            'provinciaTecnico' => $provinciaTecnico,
            'identificador_operador' => $identificadorOperador,
            'nombre_operador' => $nombreOperador,
            'sitio' => $nombreSitio,
            'numero_permiso' => $numPermiso,
            'tipoBusqueda' => $tipoBusqueda,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin
        );
        
        $movilizaciones = $this->lNegocioMovilizacion->buscarMovilizacionesFiscalizacionesXFiltro($arrayParametros);
        
        $this->tablaHtmlFiscalizacion($movilizaciones);
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
    
    /**
     * Método para listar los seguimientos registrados
     */
    public function construirDetalleFiscalizacion($idMovilizacion)
    {
        $listaDestalles = $this->lNegocioFiscalizacion->buscarFiscalizacionXMovilizacion($idMovilizacion);
        
        $this->listaDetalles = '<table style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>NºReg</th>
                                                <th>Fecha</th>
                                                <th>Fiscalizador</th>
                                                <th>Resultado</th>
                                                <th>Acción Correctiva</th>
                                                <th>Observación</th>
                                            </tr>
                                        </thead>';
        
        $i=1;
        
        foreach ($listaDestalles as $fila) {
            
            $this->listaDetalles .=
            '<tr>
                        <td>' . $i++ . '</td>
                        <td>' . ($fila['fecha_creacion'] != '' ? $fila['fecha_creacion'] : ''). '</td>
                        <td>' . ($fila['nombre_fiscalizador'] != '' ? $fila['nombre_fiscalizador'] : '') . '</td>
                        <td>' . ($fila['resultado_fiscalizacion'] != '' ? $fila['resultado_fiscalizacion'] : ''). '</td>
                        <td>' . ($fila['accion_correctiva'] != '' ? $fila['accion_correctiva'] : '') . '</td>
                        <td>' . ($fila['observacion_fiscalizacion'] != '' ? $fila['observacion_fiscalizacion'] : '') . '</td>
                    </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
    
    /**
     * Método para generar el reporte de movilizaciones en excel
     */
    public function exportarFiscalizacionesExcel() {
        $idProvinciaFiltro = $_POST["idProvinciaFiltro"];
        $provinciaFiltro = $_POST["provinciaFiltro"];
        $idCantonFiltro = $_POST["idCantonFiltro"];
        $cantonFiltro = $_POST["cantonFiltro"];
        $idParroquiaFiltro = $_POST["idParroquiaFiltro"];
        $parroquiaFiltro = $_POST["parroquiaFiltro"];
        $estadoFiltro = $_POST["estadoFiltro"];
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];
            
        
        $arrayParametros = array(
            'id_provincia' => $idProvinciaFiltro,
            'provincia' => $provinciaFiltro,
            'id_canton' => $idCantonFiltro,
            'canton' => $cantonFiltro,
            'id_parroquia' => $idParroquiaFiltro,
            'parroquia' => $parroquiaFiltro,
            'estado' => $estadoFiltro,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        );
        
        $fiscalizaciones = $this->lNegocioFiscalizacion->buscarFiscalizacionesNacionalXFiltro($arrayParametros);
        
        $this->lNegocioFiscalizacion->exportarArchivoExcelFiscalizaciones($fiscalizaciones);
    }
}