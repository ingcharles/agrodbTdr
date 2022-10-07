<?php
/**
 * Controlador Miembros
 *
 * Este archivo controla la lógica del negocio del modelo:  MiembrosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-02-15
 * @uses    MiembrosControlador
 * @package PasaporteEquino
 * @subpackage Controladores
 */
namespace Agrodb\PasaporteEquino\Controladores;

use Agrodb\PasaporteEquino\Modelos\MiembrosLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\MiembrosModelo;
use Agrodb\PasaporteEquino\Modelos\OrganizacionEcuestreLogicaNegocio;

use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;

use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosLogicaNegocio;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class MiembrosControlador extends BaseControlador
{

    private $lNegocioMiembros = null;
    private $modeloMiembros = null;
    private $lNegocioOrganizacionEcuestre = null;
    
    private $lNegocioOperaciones = null;
    
    private $lNegocioCatastroPredioEquidos = null;

    private $accion = null;
    private $formulario = null;
    
    private $asociacion = null;
    private $miembroAsociacion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        
        $this->lNegocioMiembros = new MiembrosLogicaNegocio();
        $this->modeloMiembros = new MiembrosModelo();
        $this->lNegocioOrganizacionEcuestre = new OrganizacionEcuestreLogicaNegocio();
        
        $this->lNegocioOperaciones = new OperacionesLogicaNegocio();
        
        $this->lNegocioCatastroPredioEquidos = new CatastroPredioEquidosLogicaNegocio();
        
        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
        
        $arrayParametros = array(
            'identificador_operador' => $_SESSION['usuario'],
            'id_area' => 'SA',
            'codigo_operacion' => 'OEC'
        );
        
        $this->asociacion = $this->lNegocioOperaciones->buscarOperacionSitioOperador($arrayParametros);        
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $this->cargarPanelMiembros();
        
        require APP . 'PasaporteEquino/vistas/listaMiembrosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo asociado";
        $this->formulario = 'nuevo';
        
        require APP . 'PasaporteEquino/vistas/formularioMiembrosVista.php';
    }

    /**
     * Método para registrar en la base de datos Miembros
     */
    public function guardar()
    {   
        $_POST['idAsociacion'] = $this->datosAsociacion();
        
        $resultado = $this->lNegocioMiembros->validarMiembro($_POST);
        
        if($resultado['bandera']){
            echo json_encode(array(
                'estado' => $resultado['estado'],
                'mensaje' => $resultado['mensaje'],
                'contenido' => $resultado['contenido']
            ));
        }else{
            Mensajes::fallo($resultado['mensaje']);
        }
    }
    
    /**
     * Método para buscar/registrar la asociación ecuestre
     */
    public function datosAsociacion()
    {
        //Busca si existe una organizacion ecuestre con el usuario logeado, sino crea una
        $query = "identificador_organizacion = '".$_SESSION['usuario']."'";
        $asociacion = $this->lNegocioOrganizacionEcuestre->buscarLista($query);
        
        if(isset($asociacion->current()->id_organizacion_ecuestre)){
            $idAsociacion = $asociacion->current()->id_organizacion_ecuestre;
        }else{
            $arrayParametros = array(
                'identificador_organizacion' => $this->asociacion->current()->identificador_operador,
                'razon_social' => $this->asociacion->current()->razon_social,
                'nombre_asociacion' => $this->asociacion->current()->nombre_lugar,
                'id_grupo_operacion' => $this->asociacion->current()->id_operador_tipo_operacion,
                'provincia' => $this->asociacion->current()->provincia
            );
            
            $idAsociacion = $this->lNegocioOrganizacionEcuestre->guardar($arrayParametros);
        }  
        
        return $idAsociacion;
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Miembros
     */
    public function editar()
    {
        $this->accion = "Información del Asociado";
        $this->formulario = 'editar';
        
        $this->modeloMiembros = $this->lNegocioMiembros->buscar($_POST["id"]);
        
        //Busca la información del predio en el módulo de Équidos
        $query = "id_catastro_predio_equidos = ".$this->modeloMiembros->getIdCatastroPredioEquidos();
        $this->miembroAsociacion = $this->lNegocioCatastroPredioEquidos->buscarLista($query);
        
        require APP . 'PasaporteEquino/vistas/formularioMiembrosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Miembros
     */
    public function borrar()
    {
        $this->lNegocioMiembros->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Miembros
     */
    public function tablaHtmlMiembros($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_miembro'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'PasaporteEquino/Miembros"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		  <td>' . ++ $contador . '</td>
            		  <td style="white - space:nowrap; "><b>' . $fila['identificador_miembro'] . '</b></td>
                        <td>' . $fila['nombre_miembro'] . '</td>
                        <td>' . $fila['nombre_predio'] . '</td>
                        <td>' . $fila['estado_miembro'] . '</td>
                    </tr>'
            );
        }
    }
    
    /**
     * Construye el código HTML para desplegar panel de busqueda para los miembros de una asociación
     */
    public function cargarPanelMiembros()
    {
        $this->panelBusquedaMiembros = '<table class="filtro">
                                            <tbody>
                                                <tr></tr>
                                                <tr>
                            						<td>*Identificación del Asociado: </td>
                            						<td colspan=3>
                                                        <input type="text" id="identificadorMiembroFiltro" name="identificadorMiembroFiltro" style="width: 100%;" />
                            						</td>
                                                </tr>
                            					<tr>
                            						<td>Nombre del Asociado: </td>
                            						<td colspan=3>
                                                        <input type="text" id="nombreMiembroFiltro" name="nombreMiembroFiltro" style="width: 100%;" />
                            						</td>
                                                </tr>
                                                <tr>
                            						<td>Nombre del sitio: </td>
                            						<td colspan=3>
                                                        <input type="text" id="nombreSitioMiembroFiltro" name="nombreSitioMiembroFiltro" style="width: 100%;" />
                            						</td>
                            					</tr>
                                                <tr></tr>
                            					<tr>
                            						<td colspan="3">
                            							<button type="button" id="btnFiltrar">Buscar</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
    }
    
    /**
     * Método para listar los miembros registrados
     */
    public function listarMiembrosFiltrados()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $identificadorMiembro = $_POST['identificadorMiembroFiltro'];
        $nombreMiembro = $_POST["nombreMiembroFiltro"];
        $nombreSitioMiembro = $_POST['nombreSitioMiembroFiltro'];
        $identificadorAsociacion = $_SESSION['usuario'];
        
        $arrayParametros = array(
            'identificador_miembro' => $identificadorMiembro,
            'nombre_miembro' => $nombreMiembro,
            'nombre_predio' => $nombreSitioMiembro,
            'identificador_organizacion' => $identificadorAsociacion
        );
        
        $solicitudes = $this->lNegocioMiembros->buscarMiembrosFiltrados($arrayParametros);
        
        $this->tablaHtmlMiembros($solicitudes);
        
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
    
    /**
     * Consulta las provincias donde un operador tiene predios en el módulo de Predio de Équidos
     */
    public function comboProvinciasXOperador()
    {
        $identificador = $_POST['identificador'];
        $provincia = '<option value="">Seleccione....</option>';
        
        $combo = $this->lNegocioCatastroPredioEquidos->buscarProvinciasOperador($identificador);
        
        foreach ($combo as $item) {
            $provincia .= '<option value="' . $item->id_provincia . '">' . $item->provincia . '</option>';
        }
        
        echo $provincia;
        exit;
    }
    
    /**
     * Consulta los predios que tiene un operador en el módulo de Predio de Équidos por provincia
     */
    public function comboPrediosXProvincia()
    {
        $identificador = $_POST['identificador'];
        $idProvincia = $_POST['provincia'];
        $predio = '<option value="">Seleccione....</option>';
        
        $query = "cedula_propietario = '$identificador' and id_provincia = $idProvincia";
        $combo = $this->lNegocioCatastroPredioEquidos->buscarLista($query);
        
        foreach ($combo as $item) {
            $predio .= '<option value="' . $item->id_catastro_predio_equidos . '">' . $item->nombre_predio . '</option>';
        }
        
        echo $predio;
        exit;
    }
    
    /**
     * Método para obtener los datos del operador
     */
    public function buscarOperadorXPredio()
    {
        $resultado = "Fallo";
        $mensaje = "El operador no existe.";

        $idCatastroPredioEquidos = $_POST['predio'];
        
        // Busca los datos de programas de control oficial predio de équidos
        $datosOperador = $this->lNegocioCatastroPredioEquidos->buscar($idCatastroPredioEquidos);
        
        if ($datosOperador->nombrePredio != '') {
            $resultado = "Exito";
            $mensaje = "El operador se encuentra registrado.";
            
            echo json_encode(array(
                'resultado' => $resultado,
                'mensaje' => $mensaje,
                'nombrePredio' => $datosOperador->nombrePredio,
                'provincia' => $datosOperador->provincia,
                'canton' => $datosOperador->canton,
                'parroquia' => $datosOperador->parroquia,
                'direccion' => $datosOperador->direccionPredio,
                'nombrePropietario' => $datosOperador->nombrePropietario,
                'cedula' => $datosOperador->cedulaPropietario,
                'telefono' => $datosOperador->telefonoPropietario,
                'correo' => $datosOperador->correoElectronicoPropietario
            ));

        }else {
            $resultado = "El operador no se encuentra registrado.";
            
            echo json_encode(array(
                'resultado' => $resultado,
                'mensaje' => $mensaje
            ));
        }
    }
    
    /**
     * Combo de estados de miembro
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboEstadosMiembro($opcion = null)
    {
        $combo = "";
        if ($opcion == "Activo") {
            $combo .= '<option value="Activo" selected="selected">Activo</option>';
            $combo .= '<option value="Liberado">Liberado/option>';
            $combo .= '<option value="Inactivo">Inactivo</option>';
        } else if ($opcion == "Liberado") {
            $combo .= '<option value="Activo" >Activo</option>';
            $combo .= '<option value="Liberado" selected="selected">Liberado</option>';
            $combo .= '<option value="Inactivo">Inactivo</option>';
        } else if ($opcion == "Inactivo") {
            $combo .= '<option value="Activo" >Activo</option>';
            $combo .= '<option value="Liberado">Liberado</option>';
            $combo .= '<option value="Inactivo" selected="selected">Inactivo</option>';
        } else {
            $combo .= '<option value="Activo">Activo</option>';
            $combo .= '<option value="Liberado">Liberado</option>';
            $combo .= '<option value="Inactivo">Inactivo</option>';
        }
        
        return $combo;
    }
}