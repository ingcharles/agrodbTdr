<?php
/**
 * Controlador Equinos
 *
 * Este archivo controla la lógica del negocio del modelo:  EquinosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-02-18
 * @uses    EquinosControlador
 * @package PasaporteEquino
 * @subpackage Controladores
 */
namespace Agrodb\PasaporteEquino\Controladores;

use Agrodb\PasaporteEquino\Modelos\EquinosLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\EquinosModelo;

use Agrodb\PasaporteEquino\Modelos\MiembrosLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\MiembrosModelo;

use Agrodb\PasaporteEquino\Modelos\OrganizacionEcuestreLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\OrganizacionEcuestreModelo;

use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;

use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresModelo;

use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosLogicaNegocio;
use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosModelo;

use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosEspecieLogicaNegocio;
use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosEspecieModelo;

use Agrodb\Catalogos\Modelos\EspeciesLogicaNegocio;
use Agrodb\Catalogos\Modelos\EnfermedadesEquinasLogicaNegocio;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class EquinosControlador extends BaseControlador
{

    private $lNegocioEquinos = null;
    private $modeloEquinos = null;

    private $lNegocioMiembros = null;
    private $modeloMiembros = null;

    private $lNegocioOrganizacionEcuestre = null;
    private $modeloOrganizacionEcuestre = null;

    private $lNegocioOperaciones = null;
    
    private $lNegocioOperadores = null;
    private $modeloOperadores = null;

    private $lNegocioCatastroPredioEquidos = null;
    private $modeloCatastroPredioEquidos = null;
    
    private $lNegocioCatastroPredioEquidosEspecie = null;
    private $modeloCatastroPredioEquidosEspecie = null;
    
    private $lNegocioEspecies = null;
    private $lNegocioEnfermedadesEquinas = null;

    private $accion = null;
    private $menu = null;
    private $formulario = null;
    
    private $idAsociacion = null;
    private $asociacion = null;
    private $miembroAsociacion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioEquinos = new EquinosLogicaNegocio();
        $this->modeloEquinos = new EquinosModelo();

        $this->lNegocioMiembros = new MiembrosLogicaNegocio();
        $this->modeloMiembros = new MiembrosModelo();

        $this->lNegocioOrganizacionEcuestre = new OrganizacionEcuestreLogicaNegocio();
        $this->modeloOrganizacionEcuestre = new OrganizacionEcuestreModelo();

        $this->lNegocioOperaciones = new OperacionesLogicaNegocio();
        
        $this->lNegocioOperadores = new OperadoresLogicaNegocio();
        $this->modeloOperadores = new OperadoresModelo();

        $this->lNegocioCatastroPredioEquidos = new CatastroPredioEquidosLogicaNegocio();
        $this->modeloCatastroPredioEquidos = new CatastroPredioEquidosModelo();
        
        $this->lNegocioCatastroPredioEquidosEspecie = new CatastroPredioEquidosEspecieLogicaNegocio();
        $this->modeloCatastroPredioEquidosEspecie = new CatastroPredioEquidosEspecieModelo();
        
        $this->lNegocioEspecies = new EspeciesLogicaNegocio();
        $this->lNegocioEnfermedadesEquinas = new EnfermedadesEquinasLogicaNegocio();

        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));

        $query = "identificador_organizacion = '" . $_SESSION['usuario'] . "'";
        $this->modeloOrganizacionEcuestre = $this->lNegocioOrganizacionEcuestre->buscarLista($query);
        
        if (!isset($this->modeloOrganizacionEcuestre->current()->id_organizacion_ecuestre)) {// && ($this->modeloOrganizacionEcuestre->current()->id_organizacion_ecuestre == null)
            // print_r("validacion centro concentración");
            $this->tipoUsuario = 'CentroConcentracion';
            
            $operadorCC = $this->lNegocioOperadores->buscar($_SESSION['usuario']);
            
            $this->razonSocialCC = $operadorCC->getRazonSocial();
            $this->provinciaCC = $operadorCC->getProvincia();
            
            // $this->UsuariosPerfilesLogicaNegocio = $this->lNegocioUsuariosPerfiles->buscarLista($query);
        } else {
            // print_r("validacion existe");
            $this->tipoUsuario = 'Asociacion';
            $this->idAsociacion = $this->modeloOrganizacionEcuestre->current()->id_organizacion_ecuestre;
            $this->asociacion = $this->modeloOrganizacionEcuestre->current()->nombre_asociacion;
        }
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $this->menu = 'emisionPasaporte';
        $this->cargarPanelEquinos();

        require APP . 'PasaporteEquino/vistas/listaEquinosVista.php';
    }
    
    /**
     * Método de inicio del controlador
     */
    public function listaLiberacionTraspaso()
    {
        $this->menu = 'liberacionTraspaso';
        $this->cargarPanelEquinos();
        
        require APP . 'PasaporteEquino/vistas/listaEquinosVista.php';
    }
    
    /**
     * Método de inicio del controlador
     */
    public function listaDeceso()
    {
        $this->menu = 'deceso';
        $this->cargarPanelEquinos();
        
        require APP . 'PasaporteEquino/vistas/listaEquinosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo pasaporte equino";
        $this->formulario = 'nuevo';

        require APP . 'PasaporteEquino/vistas/formularioEquinosVista.php';
    }

    /**
     * Método para registrar en la base de datos -Equinos
     */
    public function guardar()
    {
        $resultado = $this->lNegocioEquinos->guardarEquino($_POST);
        
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
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Equinos
     */
    public function editar() //mandar en el campo id -estado y separar para mandar a abrir
    {
        $this->accion = "Editar Equinos";
        $cadena = explode(',', $_POST["id"]);
        
        $_POST["id"] = $cadena[0];
        $this->menu = $cadena[1];
        
        switch($this->menu){
            case 'emisionPasaporte':
                $this->formulario = 'editarEquino';
                break;
            case 'liberacionTraspaso':
                $this->formulario = 'liberacionTraspasoEquino';
                break;
            case 'deceso':
                $this->formulario = 'decesoEquino';
                break;
            default:
                $this->formulario = 'sinAcceso';
                break;
        }        

        $this->modeloEquinos = $this->lNegocioEquinos->buscar($_POST["id"]);
        
        $this->modeloMiembros = $this->lNegocioMiembros->buscar($this->modeloEquinos->idMiembro);
        
        $this->modeloCatastroPredioEquidos = $this->lNegocioCatastroPredioEquidos->buscar($this->modeloEquinos->idCatastroPredioEquidos);
        
        $query = "id_catastro_predio_equidos = ".$this->modeloEquinos->idCatastroPredioEquidos." and id_especie = ".$this->modeloEquinos->idEspecie." and id_categoria = ".$this->modeloEquinos->idCategoria." and id_raza = ".$this->modeloEquinos->idRaza;
        $this->modeloCatastroPredioEquidosEspecie = $this->lNegocioCatastroPredioEquidosEspecie->buscarLista($query);
                
        require APP . 'PasaporteEquino/vistas/formularioEquinosEditarVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Equinos
     */
    public function borrar()
    {
        $this->lNegocioEquinos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Equinos
     */
    public function tablaHtmlEquinos($tabla, $menu)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_equino'] . "," . $menu . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'PasaporteEquino/Equinos"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
                    <td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['pasaporte'] . '</b></td>
                    <td>' . $fila['nombre_equino'] . '</td>
                    <td>' . $fila['nombre_predio'] . '</td>
                    <td>' . $fila['estado_equino'] . '</td>
                </tr>'
            );
        }
    }

    /**
     * Construye el código HTML para desplegar panel de busqueda para los equinos de una asociación
     */
    public function cargarPanelEquinos()
    {
        $this->panelBusquedaEmisionPasaporte = '<table class="filtro">
                                            <tbody>
                                                <tr></tr>
                                                <tr>
                            						<td>*Provincia: </td>
                            						<td colspan=3>
                                                        <select id="idProvinciaMiembroFiltro" name="idProvinciaMiembroFiltro" required style="width: 100%;">' . 
                                                        ( $this->idAsociacion !=null ? $this->comboProvinciasXMiembro() : '' ) .
                                                        '</select>
                                                    </td>
                                                </tr>
                            					<tr>
                            						<td>Miembros: </td>
                            						<td colspan=3>
                                                        <select id="idMiembroFiltro" name="idMiembroFiltro" required style="width: 100%;">
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                            						<td>Pasaporte Equino: </td>
                            						<td colspan=3>
                                                        <input type="text" id="pasaporteFiltro" name="pasaporteFiltro" style="width: 100%;" />
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
        
         $this->panelBusquedaLiberacionTraspaso = '<table class="filtro">
                                            <tbody>
                                                <tr></tr>
                                                <tr>
                            						<td>*Pasaporte Equino: </td>
                            						<td colspan=3>
                                                        <input type="text" id="pasaporteFiltro" name="pasaporteFiltro" required="required" style="width: 100%;" />
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
         
         $this->panelBusquedaDeceso = '<table class="filtro">
                                            <tbody>
                                                <tr></tr>
                                                <tr>
                            						<td>*Provincia: </td>
                            						<td colspan=3>
                                                        <select id="idProvinciaMiembroFiltro" name="idProvinciaMiembroFiltro" required style="width: 100%;">' .
                                                        ( $this->idAsociacion !=null ? $this->comboProvinciasXMiembro() : '' ) .
                                                        '</select>
                                                    </td>
                                                </tr>
                            					<tr>
                            						<td>Miembros: </td>
                            						<td colspan=3>
                                                        <select id="idMiembroFiltro" name="idMiembroFiltro" required style="width: 100%;">
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                            						<td>Pasaporte Equino: </td>
                            						<td colspan=3>
                                                        <input type="text" id="pasaporteFiltro" name="pasaporteFiltro" style="width: 100%;" />
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
     * Consulta las provincias donde un miembro registrado tiene predios
     */
    public function comboProvinciasXMiembro()
    {
        $idAsociacion = $this->idAsociacion;
        $provincia = '<option value="">Seleccione....</option>';

        $combo = $this->lNegocioMiembros->buscarProvinciasMiembro($idAsociacion);

        foreach ($combo as $item) {
            $provincia .= '<option value="' . $item->id_provincia . '">' . $item->provincia . '</option>';
        }

        return $provincia;
    }

    /**
     * Consulta las provincias donde un operador tiene predios en el módulo de Predio de Équidos
     */
    public function comboMiembrosXProvincia()
    {
        $idAsociacion = $this->idAsociacion;
        $idProvincia = $_POST['idProvincia'];
        $predio = '<option value="">Seleccione....</option>';
        
        $combo = $this->lNegocioMiembros->buscarMiembrosXProvincia($idAsociacion, $idProvincia);
        
        foreach ($combo as $item) {
            $predio .= '<option value="' . $item->id_miembro . '">' . $item->identificador_miembro . ' - ' . $item->nombre_miembro . '</option>';
        }
        
        echo $predio;
        exit;
    }
    
    /**
     * Consulta los predios de un miembro por provincia
     */
    public function comboPrediosXMiembrosXProvincia()
    {
        $idAsociacion = $this->idAsociacion;
        $idProvincia = $_POST['idProvinciaFiltro'];
        $idMiembro = $_POST['idMiembroFiltro'];
        $predio = '<option value="">Seleccione....</option>';
        
        $combo = $this->lNegocioMiembros->buscarPrediosXMiembrosXProvincia($idAsociacion, $idProvincia, $idMiembro);
        
        foreach ($combo as $item) {
            $predio .= '<option value="' . $item->id_catastro_predio_equidos . '">' . $item->num_solicitud . ' - ' . $item->nombre_predio . '</option>';
        }
        
        echo $predio;
        exit;
    }
    
    /**
     * Consulta las especies registradas por predio
     */
    public function comboEspeciesXPredio()
    {
        $idPredio = $_POST['idPredio'];
        $especie = '<option value="">Seleccione....</option>';
        
        $combo = $this->lNegocioCatastroPredioEquidosEspecie->buscarEspeciesXPredio($idPredio);
        
        foreach ($combo as $item) {
            $especie .= '<option value="' . $item->id_especie . '">' . $item->nombre_especie . '</option>';
        }
        
        echo $especie;
        exit;
    }
    
    /**
     * Consulta las especies registradas por predio
     */
    public function comboRazasXEspecieXPredio()
    {
        $idPredio = $_POST['idPredio'];
        $idEspecie = $_POST['idEspecie'];
        $especie = '<option value="">Seleccione....</option>';
        
        $combo = $this->lNegocioCatastroPredioEquidosEspecie->comboRazasXEspecieXPredio($idPredio, $idEspecie);
        
        foreach ($combo as $item) {
            $especie .= '<option value="' . $item->id_raza . '">' . $item->nombre_raza . '</option>';
        }
        
        echo $especie;
        exit;
    }
    
    /**
     * Consulta las categorías registradas por predio
     */
    public function comboCategoriasXEspecieXPredio()
    {
        $idPredio = $_POST['idPredio'];
        $idEspecie = $_POST['idEspecie'];
        $idRaza = $_POST['idRaza'];
        $especie = '<option value="">Seleccione....</option>';
        
        $combo = $this->lNegocioCatastroPredioEquidosEspecie->comboCategoriasXEspecieXPredio($idPredio, $idEspecie, $idRaza);
        
        foreach ($combo as $item) {
            $especie .= '<option value="' . $item->id_categoria . '">' . $item->nombre_categoria . '</option>';
        }
        
        echo $especie;
        exit;
    }
    
    /**
     * Método para obtener el número de animales disponibles por predio para asignar un pasaporte
     */
    public function obtenerNumeroEquinosXCategoriasXEspecieXPredio()
    {
        $resultado = $this->lNegocioEquinos->validarNumeroEquinosXCategoriasXEspecieXPredio($_POST);
        
        echo json_encode(array(
            'bandera' => $resultado['bandera'],
            'estado' => $resultado['estado'],
            'mensaje' => $resultado['mensaje'],
            'contenido' => $resultado['contenido'],
            'numero_total' => $resultado['numero_total'],
            'numero_pasaportes' => $resultado['numero_pasaportes'],
            'numero_disponibles' => $resultado['numero_disponibles']
        ));
    }

    /**
     * Método para listar los equinos registrados
     */
    public function listarEquinosFiltrados()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';
        
        $idAsociacion = $this->idAsociacion;
        $menu = $_POST['menu'];
        $idProvincia = (isset($_POST['idProvinciaMiembroFiltro'])?$_POST['idProvinciaMiembroFiltro']:'');
        $idMiembro = (isset($_POST['idMiembroFiltro'])?$_POST['idMiembroFiltro']:'');
        $pasaporte = $_POST['pasaporteFiltro'];
        
        $arrayParametros = array(
            'id_organizacion_ecuestre' => $idAsociacion,
            'id_provincia' => $idProvincia,
            'id_miembro' => $idMiembro,
            'pasaporte' => $pasaporte,
            'menu' => $menu
        );
        //print_r($arrayParametros);
        $equinos = $this->lNegocioEquinos->buscarEquinosFiltrados($arrayParametros);
        
        $this->tablaHtmlEquinos($equinos, $menu);
        
        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $menu,
            'contenido' => $contenido
        ));
    }
    
    /**
     * Método para listar los equinos registrados de una asociación
     */
    public function construirDetalleEquinos()
    {
        $idAsociacion = $this->idAsociacion;
        $idMiembro = $_POST['idMiembro'];
        $idPredio = $_POST['idPredio'];
        $idEspecie = $_POST['idEspecie'];
        $idRaza = $_POST['idRaza'];
        $idCategoria = $_POST['idCategoria'];
                
        $arrayParametros = array(
            /*'id_organizacion_ecuestre' => $idAsociacion,
            'id_miembro' => $idMiembro,*/
            'id_catastro_predio_equidos' => $idPredio,
            'id_especie' => $idEspecie,
            'id_raza' => $idRaza,
            'id_categoria' => $idCategoria,
            'estado_equino' => "'Activo', 'Inactivo', 'Movilizacion'"
        );
        
        $listaDetalles = $this->lNegocioEquinos->buscarDetalleEquinos($arrayParametros);
        
        $i=1;
        
        $tablaDetalles = '<table class="ingresoEquino">';
        
        foreach ($listaDetalles as $fila) {
            
            $tablaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['id_organizacion_ecuestre']==$idAsociacion?'Propio':'Externo'). '</td>
                            <td>' . ($fila['nombre_equino'] != '' ? $fila['nombre_equino'] : 'NA'). '</td>
                            <td>' . ($fila['pasaporte'] != '' ? $fila['pasaporte'] : 'NA'). '</td>
                            <td>' . ($fila['nombre_especie'] != '' ? $fila['nombre_especie'] . ' - ' .$fila['raza'] . ' - ' .$fila['categoria_especie']: 'NA'). '</td>
                            <td>' . ($fila['estado_equino'] != '' ? $fila['estado_equino'] : 'NA'). '</td>
                            <td class="abrir">
                                <button type="button" name="abrir" id="abrir" class="icono"  '. 
                                (($fila['estado_equino']=='Activo'||$fila['estado_equino']=='Inactivo')&&($fila['id_organizacion_ecuestre']==$idAsociacion)?'':'style="display:none"') . ' 
                                    onclick="fn_abrirEquino(' . $fila['id_equino'] . '); return false;"/>
                            </td>
                        </tr>';
        }
        
        $tablaDetalles .= '</table>';
        
        echo $tablaDetalles;
    }    
    
    /**
     * Combo de identificación adicional de Equinos
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboIdentificacionEquino($opcion = null)
    {
        $combo = "";
        if ($opcion == "Chip") {
            $combo .= '<option value="Chip" selected="selected">Chip</option>';
            $combo .= '<option value="Marca">Marca</option>';
            $combo .= '<option value="Tatuaje">Tatuaje</option>';
        } else if ($opcion == "Marca") {
            $combo .= '<option value="Chip" >Chip</option>';
            $combo .= '<option value="Marca" selected="selected">Marca</option>';
            $combo .= '<option value="Tatuaje">Tatuaje</option>';
        } else if ($opcion == "Tatuaje") {
            $combo .= '<option value="Chip" >Chip</option>';
            $combo .= '<option value="Marca">Marca</option>';
            $combo .= '<option value="Tatuaje" selected="selected">Tatuaje</option>';
        } else {
            $combo .= '<option value="Chip">Chip</option>';
            $combo .= '<option value="Marca">Marca</option>';
            $combo .= '<option value="Tatuaje">Tatuaje</option>';
        }
        
        return $combo;
    }
    
    //revisar una funcion nueva q haga el unlink del archivo y lo muestre 
    /**
     * Método para listar los equinos registrados
     */
    public function guardarImagenEquino()
    {
        $estado = 'exito';
        $mensaje = '';
        $contenido = '';
        
        $idEquino = $_POST['id_equino'];
        $bandera = $_POST['bandera'];
        $foto = $_POST['foto'];
        
        $arrayParametros = array(
            'id_equino' => $idEquino
        );
        
        switch ($bandera){
            case 'Frente':
                $arrayParametros['foto_frente'] = $foto;
                break;
            case 'Atras':
                $arrayParametros['foto_atras'] = $foto;
                break;
            case 'Derecha':
                $arrayParametros['foto_derecha'] = $foto;
                break;
            case 'Izquierda':
                $arrayParametros['foto_izquierda'] = $foto;
                break;
            default:
                break;
        }
        
        $contenido = $foto;
        
        $this->lNegocioEquinos->guardar($arrayParametros);
        
        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }
    
    /**
     * Combo de identificación adicional de Equinos
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboLiberacionTraspasoVinculacionEquino($opcion = null)
    {
        $combo = "";
        if ($opcion == "Liberado") {
            $combo .= '<option value="Liberado" selected="selected">Liberado</option>';
            $combo .= '<option value="Traspaso">Traspaso</option>';
            $combo .= '<option value="Vinculacion">Vinculacion</option>';
        } else if ($opcion == "Traspaso") {
            $combo .= '<option value="Liberado" >Liberado</option>';
            $combo .= '<option value="Traspaso" selected="selected">Traspaso</option>';
            $combo .= '<option value="Vinculacion">Vinculacion</option>';
        } else if ($opcion == "Vinculacion") {
            $combo .= '<option value="Liberado" >Liberado</option>';
            $combo .= '<option value="Traspaso" selected="selected">Traspaso</option>';
            $combo .= '<option value="Vinculacion">Vinculacion</option>';
        } else {
            $combo .= '<option value="Liberado">Liberado</option>';
            $combo .= '<option value="Traspaso">Traspaso</option>';
            $combo .= '<option value="Vinculacion">Vinculacion</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de identificación adicional de Equinos
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboLiberacionTraspasoEquino($opcion = null)
    {
        $combo = "";
        if ($opcion == "Liberado") {
            $combo .= '<option value="Liberado" selected="selected">Liberado</option>';
            $combo .= '<option value="Traspaso">Traspaso</option>';
        } else if ($opcion == "Traspaso") {
            $combo .= '<option value="Liberado" >Liberado</option>';
            $combo .= '<option value="Traspaso" selected="selected">Traspaso</option>';
        } else {
            $combo .= '<option value="Liberado">Liberado</option>';
            $combo .= '<option value="Traspaso">Traspaso</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de identificación adicional de Equinos
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboVinculacionEquino($opcion = null)
    {
        $combo = "";
        if ($opcion == "Vinculacion") {
            $combo .= '<option value="Vinculacion" selected="selected">Vinculacion</option>';
        } else {
            $combo .= '<option value="Vinculacion">Vinculacion</option>';
        }
        
        return $combo;
    }
    
    /**
     * Combo de identificación adicional de Equinos
     *
     * @param
     * $respuesta
     * @return string
     */
    public function comboDecesoEquino($opcion = null)
    {
        $combo = "";
        if ($opcion == "Deceso") {
            $combo .= '<option value="Deceso" selected="selected">Deceso</option>';
        } else {
            $combo .= '<option value="Deceso">Deceso</option>';
        }
        
        return $combo;
    }
    
    /**
     * Consulta las provincias donde se encuentran las asociaciones registradas
     */
    public function comboProvinciasXOrganizacionEcuestre()
    {
        $provincia = '<option value="">Seleccione....</option>';
        
        $combo = $this->lNegocioOrganizacionEcuestre->buscarProvinciasOrganizacion();
        
        foreach ($combo as $item) {
            $provincia .= '<option value="' . $item->provincia . '">' . $item->provincia . '</option>';
        }
        
        return $provincia;
    }
    
    /**
     * Consulta los predios que tiene un operador en el módulo de Predio de Équidos por provincia
     */
    public function comboOrganizacionesXProvincia()
    {
        $provincia = $_POST['provincia'];
        $organizacion = '<option value="">Seleccione....</option>';
        
        $query = "provincia = '".$provincia."'";
        $combo = $this->lNegocioOrganizacionEcuestre->buscarLista($query);
        
        foreach ($combo as $item) {
            $organizacion .= '<option value="' . $item->id_organizacion_ecuestre . '">' . $item->nombre_asociacion . '</option>';
        }
        
        echo $organizacion;
        exit;
    }
    
    /**
     * Consulta los predios que tiene un operador en el módulo de Predio de Équidos por provincia
     */
    public function comboMiembroXOrganizacionesXProvincia()
    {
        //$provincia = $_POST['provincia'];
        $idOrganizacionEcuestre = $_POST['idOrganizacionEcuestre'];
        $idMiembroActual = $_POST['idMiembroActual'];
        $miembro = '<option value="">Seleccione....</option>';
        
        //$query = "id_organizacion_ecuestre = '".$idOrganizacionEcuestre."' and id_miembro not in (".$idMiembroActual.")"; //query para sacar el codigo del sitio del predio de equidos
        $combo = $this->lNegocioMiembros->buscarMiembroXAsociacion($idOrganizacionEcuestre, $idMiembroActual );
        
        foreach ($combo as $item) {
            $miembro .= '<option value="' . $item->id_miembro . '">' . $item->identificador_miembro .' - '. $item->nombre_miembro.' - '. $item->num_solicitud. '</option>';
        }
        
        echo $miembro;
        exit;
    }
    
    /**
     * Método para validar identificador adicional del equino
     * */
    public function validarIdentificacionAdicional()
    {
        $tipoIdentificacion = $_POST["tipoIdentificacion"];
        $detalleIdentificacion = $_POST["detalleIdentificacion"];
        
        $validacion = "Exito";
        $nombre = "El identificador ingresado se encuentra disponible.";
        
        // Si encuentra coincidencias mostrará mensaje de error en pantalla para el cambio de identificador
        // Busca si existe el número y tipo en registros de equinos
        $query = "tipo_identificacion = '".$tipoIdentificacion."' and detalle_identificacion = '".$detalleIdentificacion."'";
        $identificador = $this->lNegocioEquinos->buscarLista($query);
        
        if (isset($identificador->current()->detalle_identificacion)) {
            $datos = $identificador->current()->detalle_identificacion;
            
            if (strlen(trim($datos)) > 0) {
                $validacion = "Fallo";
                $nombre = "El tipo y número de identificación adicional elegido ya se encuentra registrado.";
            }
        }
        
        echo json_encode(array(
            'nombre' => $nombre,
            'validacion' => $validacion
        ));
    }
    
    /**
     * Consulta las provincias donde se encuentran las asociaciones registradas
     */
    public function comboEnfermedadesEquinas()
    {
        $enfermedad = '<option value="">Seleccione....</option>';
        
        $query = "estado_enfermedad_equino = 'Activo' order by nombre_enfermedad ASC";
        $combo = $this->lNegocioEnfermedadesEquinas->buscarLista($query);
        
        foreach ($combo as $item) {
            $enfermedad .= '<option value="' . $item->nombre_enfermedad . '">' . $item->nombre_enfermedad . '</option>';
        }
        
        return $enfermedad;
    }
    
    /**
     * Método para obtener los datos del operador
     */
    public function buscarEquinoXPasaporte()
    {
        $resultado = "Fallo";
        $mensaje = "El pasaporte equino no existe.";
        
        $tipoUsuario = $_POST['tipoUsuario'];
        $pasaporteEquino = $_POST['pasaporteEquino'];
        
        //Buscar Equinos de acuerdo al perfil
        $arrayParametros = array(
            'pasaporte' => $pasaporteEquino,
            'identificador' => $_SESSION['usuario'],
            'tipo_usuario' => $tipoUsuario
        );        
        
        //if($tipoUsuario == 'Asociacion'){//Asociación Ecuestre
            $equino = $this->lNegocioEquinos->buscarEquinoXPasaporte($arrayParametros);
            
       // }else{//Centro de Concentración de Animales 
            // Busca los datos de programas de control oficial predio de équidos
            //$equino = $this->lNegocioCatastroPredioEquidos->buscar($idCatastroPredioEquidos);
       // }        
        
        if (isset($equino->current()->id_equino)) {
            
            if($equino->current()->num_equinos_predio > 0){//hay equinos en la ubicación actual
                
                if($equino->current()->ruta_hoja_filiacion != null || $equino->current()->ruta_hoja_filiacion != ''){//validar si tiene ficha de filiacion
                    
                    if($equino->current()->examen != null){//Si hay examenes 
                        
                        if($equino->current()->examen != 'Positivo'){//Si el examen es positivo no se puede movilizar
                            
                            $resultado = "Exito";
                            $mensaje = "El equino solicitado puede ser movilizado.";
                            
                            echo json_encode(array(
                                'resultado' => $resultado,
                                'mensaje' => $mensaje,
                                'idOrganizacionEcuestre' => $equino->current()->id_organizacion_ecuestre,
                                'identificadorOrganizacion' => $equino->current()->identificador_organizacion,
                                'razonSocial' => $equino->current()->razon_social,
                                'nombreAsociacion' => $equino->current()->nombre_asociacion,
                                'idMiembro' => $equino->current()->id_miembro,
                                'identificadorMiembro' => $equino->current()->identificador_miembro,
                                'nombreMiembro' => $equino->current()->nombre_miembro,
                                'idEquino' => $equino->current()->id_equino,
                                'pasaporte' => $equino->current()->pasaporte,
                                'idEspecie' => $equino->current()->id_especie,
                                'idRaza' => $equino->current()->id_raza,
                                'idCategoria' => $equino->current()->id_categoria,
                                'idCatastroPredioEquidos' => $equino->current()->id_catastro_predio_equidos,
                                'ubicacionActual' => $equino->current()->ubicacion_actual,
                                'numSolicitud' => $equino->current()->num_solicitud,
                                'nombrePredio' => $equino->current()->nombre_predio,
                                'cedulaPropietario' => $equino->current()->cedula_propietario,
                                'nombrePropietario' => $equino->current()->nombre_propietario,
                                'idProvincia' => $equino->current()->id_provincia,
                                'provincia' => $equino->current()->provincia,
                                'idCanton' => $equino->current()->id_canton,
                                'canton' => $equino->current()->canton,
                                'idParroquia' => $equino->current()->id_parroquia,
                                'parroquia' => $equino->current()->parroquia,
                                'direccionPredio' => $equino->current()->direccion_predio,
                                'idSitio' => $equino->current()->id_sitio,
                                'idArea' => $equino->current()->id_area,
                                'examen' => $equino->current()->examen
                            ));
                        }else{
                            $resultado = "Fallo";
                            $mensaje = "El equino registra anemia infecciosa y no podrá movilizarse.";
                            
                            echo json_encode(array(
                                'resultado' => $resultado,
                                'mensaje' => $mensaje
                            ));
                        }
                        
                    }else{
                        $resultado = "Fallo";
                        $mensaje = "Debe registrar información de los exámenes de anemia del equino para poder continuar.";
                        
                        echo json_encode(array(
                            'resultado' => $resultado,
                            'mensaje' => $mensaje
                        ));
                    }
                    
                    
                }else{
                    $resultado = "Fallo";
                    $mensaje = "No se ha completado la información del equino.";
                    
                    echo json_encode(array(
                        'resultado' => $resultado,
                        'mensaje' => $mensaje
                    ));
                }
                    
            }else{
                $resultado = "Fallo";
                $mensaje = "No se dispone de equinos para movilizar en la ubicación actual.";
                
                echo json_encode(array(
                    'resultado' => $resultado,
                    'mensaje' => $mensaje
                ));
            }
        }else {
            $resultado = "Fallo";
            
            if($tipoUsuario == 'Asociacion'){
                $mensaje = "El equino no se encuentra ubicado en uno de los predios de su asociación para ser movilizado, se encuentra en una movilización Vigente, está inactivo o su propietario está inactivo.";
            }else{
                $mensaje = "El equino no se encuentra ubicado en su centro de concentración de animales para ser movilizado, se encuentra en una movilización Vigente, está inactivo o su propietario está inactivo.";
            }
            
            echo json_encode(array(
                'resultado' => $resultado,
                'mensaje' => $mensaje
            ));
        }
    }
    
    /**
     * Método para generar el reporte de pasaportes equinos en excel
     */
    public function exportarPasaportesExcel() {
        $idProvinciaFiltro = (isset($_POST["idProvinciaFiltro"])?$_POST["idProvinciaFiltro"]:'');
        $idCantonFiltro = (isset($_POST["idCantonFiltro"])?$_POST["idCantonFiltro"]:'');
        $estadoFiltro = (isset($_POST["estadoFiltro"])?$_POST["estadoFiltro"]:'');
        $fechaInicio = (isset($_POST["fechaInicio"])?$_POST["fechaInicio"]:'');
        $fechaFin =(isset($_POST["fechaFin"])?$_POST["fechaFin"]:'');
        
        $arrayParametros = array(
            'id_provincia' => $idProvinciaFiltro,
            'id_canton' => $idCantonFiltro,
            'estado_equino' => $estadoFiltro,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        );
        
        $pasaportes = $this->lNegocioEquinos->buscarEquinosReporteFiltrados($arrayParametros);
        
        //if(!empty($pasaportes->current())){
            $this->lNegocioEquinos->exportarArchivoExcelPasaportes($pasaportes);
        /*}else{
            echo "No se dispone de datos con los parámetros solicitados. Por favor intente nuevamente.";
        }*/
    }
}