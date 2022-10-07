<?php
/**
 * Controlador Movilizaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  MovilizacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2022-03-14
 * @uses    MovilizacionesControlador
 * @package PasaporteEquino
 * @subpackage Controladores
 */
namespace Agrodb\PasaporteEquino\Controladores;

use Agrodb\PasaporteEquino\Modelos\MovilizacionesLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\MovilizacionesModelo;
use Agrodb\PasaporteEquino\Modelos\EquinosLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\EquinosModelo;
use Agrodb\PasaporteEquino\Modelos\OrganizacionEcuestreLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\OrganizacionEcuestreModelo;
use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosLogicaNegocio;
use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosModelo;
/*
 * use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosEspecieLogicaNegocio;
 * use Agrodb\ProgramasControlOficial\Modelos\CatastroPredioEquidosEspecieModelo;
 */
use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;
use Agrodb\Usuarios\Modelos\UsuariosPerfilesModelo;

use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresModelo;
use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperacionesModelo;
use Agrodb\RegistroOperador\Modelos\SitiosLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\SitiosModelo;
use Agrodb\RegistroOperador\Modelos\AreasLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\AreasModelo;

/*use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\LocalizacionModelo;*/

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class MovilizacionesControlador extends BaseControlador
{

    private $lNegocioMovilizaciones = null;
    private $modeloMovilizaciones = null;

    private $lNegocioEquinos = null;
    private $modeloEquinos = null;

    private $lNegocioOrganizacionEcuestre = null;
    private $modeloOrganizacionEcuestre = null;

    private $lNegocioCatastroPredioEquidos = null;
    private $modeloCatastroPredioEquidos = null;

    /*
     * private $lNegocioCatastroPredioEquidosEspecie = null;
     * private $modeloCatastroPredioEquidosEspecie = null;
     */
    private $lNegocioUsuariosPerfiles = null;
    private $modeloUsuariosPerfiles = null;

    private $lNegocioOperadores = null;
    private $modeloOperadores = null;

    private $lNegocioOperaciones = null;
    private $modeloOperaciones = null;

    private $lNegocioSitios = null;
    private $modeloSitios = null;

    private $lNegocioAreas = null;
    private $modeloAreas = null;

    /*private $lNegocioLocalizacion = null;
    private $modeloLocalizacion = null;*/

    private $accion = null;
    private $formulario = null;
    private $urlPdf = null;

    private $tipoUsuario = null;
    private $idAsociacion = null;
    private $asociacion = null;
    
    private $razonSocialCC = null;
    private $provinciaCC = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->lNegocioMovilizaciones = new MovilizacionesLogicaNegocio();
        $this->modeloMovilizaciones = new MovilizacionesModelo();

        $this->lNegocioOrganizacionEcuestre = new OrganizacionEcuestreLogicaNegocio();
        $this->modeloOrganizacionEcuestre = new OrganizacionEcuestreModelo();

        $this->lNegocioEquinos = new EquinosLogicaNegocio();
        $this->modeloEquinos = new EquinosModelo();

        $this->lNegocioCatastroPredioEquidos = new CatastroPredioEquidosLogicaNegocio();
        $this->modeloCatastroPredioEquidos = new CatastroPredioEquidosModelo();
        /*
         * $this->lNegocioCatastroPredioEquidosEspecie = new CatastroPredioEquidosEspecieLogicaNegocio();
         * $this->modeloCatastroPredioEquidosEspecie = new CatastroPredioEquidosEspecieModelo();
         */

        $this->lNegocioUsuariosPerfiles = new UsuariosPerfilesLogicaNegocio();
        $this->modeloUsuariosPerfiles = new UsuariosPerfilesModelo();

        $this->lNegocioOperadores = new OperadoresLogicaNegocio();
        $this->modeloOperadores = new OperadoresModelo();

        $this->lNegocioOperaciones = new OperacionesLogicaNegocio();
        $this->modeloOperaciones = new OperacionesModelo();

        $this->lNegocioSitios = new SitiosLogicaNegocio();
        $this->modeloSitios = new SitiosModelo();

        $this->lNegocioAreas = new AreasLogicaNegocio();
        $this->modeloAreas = new AreasModelo();

        /*$this->lNegocioLocalizacion = new LocalizacionLogicaNegocio();
        $this->modeloLocalizacion = new LocalizacionModelo();*/

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
        //$this->paCambioEstadoMovilizacionPasaporteEquino();
        
        $this->cargarPanelMovilizaciones();

        /*$modeloMovilizaciones = $this->lNegocioMovilizaciones->buscarMovilizaciones();
        $this->tablaHtmlMovilizaciones($modeloMovilizaciones);*/
        require APP . 'PasaporteEquino/vistas/listaMovilizacionesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva movilización equina";
        $this->formulario = 'nuevo';

        require APP . 'PasaporteEquino/vistas/formularioMovilizacionesVista.php';
    }

    /**
     * Método para registrar en la base de datos -Movilizaciones
     */
    public function guardar()
    {
        $this->lNegocioMovilizaciones->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Movilizaciones
     */
    public function editar()
    {
        $this->accion = "Detalle Movilización";
        $this->formulario = 'abrir';
        
        $this->modeloMovilizaciones = $this->lNegocioMovilizaciones->buscar($_POST["id"]);
        
        require APP . 'PasaporteEquino/vistas/formularioMovilizacionesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Movilizaciones
     */
    public function borrar()
    {
        $this->lNegocioMovilizaciones->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Movilizaciones
     */
    public function tablaHtmlMovilizaciones($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_movilizacion'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'PasaporteEquino\movilizaciones"
                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
                		<td>' . ++ $contador . '</td>
                		<td style="white - space:nowrap; "><b>' . $fila['numero_movilizacion'] . '</b></td>
                        <td>' . $fila['nombre_ubicacion_origen'] . '</td>
                        <td>' . $fila['nombre_ubicacion_destino'] . '</td>
                        <td>' . $fila['estado_movilizacion'] . '</td>
                    </tr>'
            );
        }
    }

    /**
     * Construye el código HTML para desplegar panel de busqueda para Movilizaciones
     */
    public function cargarPanelMovilizaciones()
    {
        $this->panelBusquedaMovilizaciones = '<table class="filtro" style="width: 100%;">
                                                <input type="hidden" id="identificadorUsuario" name="identificadorUsuario" value="' . $_SESSION['usuario'] . '" readonly="readonly" >
                                                <input type="hidden" id="tipoUsuarioFiltro" name="tipoUsuarioFiltro" value="' . $this->tipoUsuario . '" readonly="readonly" >
                                                    
                                                <tbody>
                                                    <tr>
                                                        <th colspan="2">Consultar de Certificados de Movilización:</th>
                                                    </tr>

                                                    <tr  style="width: 100%;">
                                						<td >Identificación solicitante: </td>
                                						<td>
                                							<input id="identificadorSolicitanteFiltro" type="text" name="identificadorSolicitanteFiltro" style="width: 90%" maxlength="13">
                                						</td>
                                					</tr>
                                							    
                                                    <tr  style="width: 100%;">	    
                                						<td >Nombre solicitante: </td>
                                						<td>
                                							<input id="nombreSolicitanteFiltro" type="text" name="nombreSolicitanteFiltro" style="width: 90%" maxlength="128">
                                						</td>
                                					</tr>
                                							    
                                                    <tr  style="width: 100%;">
                                						<td >Nombre Sitio origen: </td>
                                						<td>
                                							<input id="nombreSitioOrigenFiltro" type="text" name="nombreSitioOrigenFiltro" style="width: 90%" maxlength="128">
                                						</td>
                                					</tr>
                                							    
                                                    <tr  style="width: 100%;">	    
                                						<td >Nº Movilización: </td>
                                						<td>
                                							<input id="numMovilizacionFiltro" type="text" name="numMovilizacionFiltro" style="width: 90%" maxlength="32">
                                						</td>
                                					</tr>
                                							    
                                                    <tr  style="width: 100%;">
                                						<td >Nº Pasaporte equino: </td>
                                						<td>
                                							<input id="numPasaporteFiltro" type="text" name="numPasaporteFiltro" style="width: 90%" maxlength="16">
                                						</td>
                                					</tr>
                                                                
    												<tr  style="width: 100%;">
                                						<td >*Fecha Inicio: </td>
                                						<td>
                                							<input id="fechaInicioFiltro" type="text" name="fechaInicioFiltro" style="width: 90%" readonly="readonly">
                                						</td>
                                                    </tr>
                                							    
                                                    <tr  style="width: 100%;">            
                                						<td >*Fecha Fin: </td>
                                						<td>
                                							<input id="fechaFinFiltro" type="text" name="fechaFinFiltro" style="width: 90%" readonly="readonly">
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
     *            $respuesta
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
     * Método para listar las movilizaciones registradas
     */
    public function listarMovilizacionesFiltradas()
    {
        $estado = 'EXITO';
        $mensaje = '';
        $contenido = '';

        if ($this->tipoUsuario == 'Asociacion') {
            $idAsociacion = $this->idAsociacion;
        } else {
            $idAsociacion = '';
        }
        
        $tipoProceso = $_POST['tipoProceso'];
        $tipoUsuario = $this->tipoUsuario;
        $identificadorSolicitanteFiltro = (isset($_POST['identificadorSolicitanteFiltro']) ? $_POST['identificadorSolicitanteFiltro'] : '');
        $nombreSolicitanteFiltro = (isset($_POST['nombreSolicitanteFiltro']) ? $_POST['nombreSolicitanteFiltro'] : '');
        $nombreSitioOrigenFiltro = (isset($_POST['nombreSitioOrigenFiltro']) ? $_POST['nombreSitioOrigenFiltro'] : '');
        $numMovilizacionFiltro = (isset($_POST['numMovilizacionFiltro']) ? $_POST['numMovilizacionFiltro'] : '');
        $numPasaporteFiltro = (isset($_POST['numPasaporteFiltro']) ? $_POST['numPasaporteFiltro'] : '');
        $fechaInicioFiltro = $_POST['fechaInicioFiltro'];
        $fechaFinFiltro = $_POST['fechaFinFiltro'];

        $arrayParametros = array(
            'tipoProceso' => $tipoProceso,
            'tipoUsuario' => $tipoUsuario,
            'id_asociacion' => $idAsociacion,
            'identificador' => $_SESSION['usuario'],
            'identificador_solicitante' => $identificadorSolicitanteFiltro,
            'nombre_solicitante' => $nombreSolicitanteFiltro,
            'nombre_ubicacion_origen' => $nombreSitioOrigenFiltro,
            'numero_movilizacion' => $numMovilizacionFiltro,
            'pasaporte_equino' => $numPasaporteFiltro,
            'fecha_inicio_movilizacion' => $fechaInicioFiltro,
            'fecha_fin_movilizacion' => $fechaFinFiltro
        );
        
        $equinos = $this->lNegocioMovilizaciones->buscarMovilizacionesFiltradas($arrayParametros);

        $this->tablaHtmlMovilizaciones($equinos);

        $contenido = \Zend\Json\Json::encode($this->itemsFiltrados);

        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }

    /**
     * Combo de tipos de destino para movilizaciones
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboTiposDestinoMovilizacion($opcion = null)
    {
        $combo = "<option value>Seleccione....</option>";

        if ($opcion == "CentroConcentracion") {
            $combo .= '<option value="CentroConcentracion" selected="selected">Centro de Concentración de Animales</option>';
            $combo .= '<option value="Predio">Predio</option>';
        } else if ($opcion == "Predio") {
            $combo .= '<option value="CentroConcentracion" >Centro de Concentración de Animales</option>';
            $combo .= '<option value="Predio" selected="selected">Predio</option>';
        } else {
            $combo .= '<option value="CentroConcentracion" >Centro de Concentración de Animales</option>';
            $combo .= '<option value="Predio">Predio</option>';
        }

        return $combo;
    }

    /**
     * Consulta las provincias donde se tiene predios u operaciones registrados en el módulo de Predio de Équidos y registro de operador
     */
    public function comboProvinciaXPrediosOperacionesRegistradas()
    {
        $tipoDestino = $_POST['tipoDestino'];
        $tipoOperacion = "'MPA','ATM','FEA','FER'";

        $provincia = '<option value="">Seleccione....</option>';

        if ($tipoDestino == 'Predio') {
            $combo = $this->lNegocioCatastroPredioEquidos->buscarProvinciasXPrediosRegistrados();
        } else if ($tipoDestino = 'CentroConcentracion') {
            $combo = $this->lNegocioOperaciones->comboProvinciaXOperacionesRegistradas($tipoOperacion);
        }

        foreach ($combo as $item) {
            $provincia .= '<option value="' . $item->id_provincia . '">' . $item->provincia . '</option>';
        }

        echo $provincia;
        exit();
    }

    /**
     * Método para obtener los datos del sitio de destino
     */
    public function buscarSitiosDestinoPredio()
    {
        $pasaporte = $_POST['pasaporte'];
        $tipoDestino = $_POST['tipoDestino'];
        $idProvincia = $_POST['idProvincia'];
        $provincia = $_POST['provincia'];
        $ubicacionActual = $_POST['ubicacionActual'];
        $codigoSitio = ($_POST['codigoSitio'] != '' ? $_POST['codigoSitio'] : '');
        $nombreSitio = ($_POST['nombreSitio'] != '' ? $_POST['nombreSitio'] : '');

        // Buscar sitio de acuerdo a los datos enviados
        $query = "";
        $queryCodigo = "";
        $queryNombre = "";

        if ($tipoDestino == 'Predio') { // Programas Control Oficial

            if ($codigoSitio != '') {
                $queryCodigo = " and num_solicitud= '" . $codigoSitio . "'";
            }

            if ($nombreSitio != '') {
                $queryNombre = " and upper(nombre_predio) ilike upper('%" . $nombreSitio . "%')";
            }

            $query = "id_provincia=$idProvincia and id_catastro_predio_equidos not in ($ubicacionActual) " . $queryCodigo . $queryNombre;

            $sitio = $this->lNegocioCatastroPredioEquidos->buscarLista($query);

            $combo = '<option value>Seleccione....</option>';
            
            foreach ($sitio as $item) {
                $combo .= '<option value="' . $item->id_catastro_predio_equidos . '">' . $item->nombre_predio . '</option>';
            }
            
            echo $combo;
            exit();
        }
    }
    
    /**
     * Método para obtener los datos del sitio de destino
     */
    public function buscarSitiosDestinoPredioEquidos()
    {
        $resultado = "Fallo";
        $mensaje = "El destino buscado no existe.";
        
        $idProvincia = $_POST['idProvincia'];
        $ubicacionActual = $_POST['ubicacionActual'];
        $idPredio = $_POST['idPredioDestino'];
        
        // Buscar sitio de acuerdo a los datos enviados
        $query = "";
            
        $query = "id_catastro_predio_equidos = ".$idPredio;
        
        $sitio = $this->lNegocioCatastroPredioEquidos->buscarLista($query);
        
        if (isset($sitio->current()->id_catastro_predio_equidos)) {
            $resultado = "Exito";
            $mensaje = "El equino puede ser movilizado al destino seleccionado.";
            
            echo json_encode(array(
                'resultado' => $resultado,
                'mensaje' => $mensaje,
                'idCatastroPredioEquidos' => $sitio->current()->id_catastro_predio_equidos,
                'numSolicitud' => $sitio->current()->num_solicitud,
                'nombrePredio' => $sitio->current()->nombre_predio,
                'cedulaPropietario' => $sitio->current()->cedula_propietario,
                'nombrePropietario' => $sitio->current()->nombre_propietario,
                'idProvincia' => $sitio->current()->id_provincia,
                'provincia' => $sitio->current()->provincia,
                'idCanton' => $sitio->current()->id_canton,
                'canton' => $sitio->current()->canton,
                'idParroquia' => $sitio->current()->id_parroquia,
                'parroquia' => $sitio->current()->parroquia,
                'direccionPredio' => $sitio->current()->direccion_predio,
                'idSitio' => $sitio->current()->id_sitio,
                'idArea' => $sitio->current()->id_area
            ));
        } else {
            $resultado = "Fallo";
            $mensaje = "El sitio seleccionado no existe o es el sitio de origen.";
            
            echo json_encode(array(
                'resultado' => $resultado,
                'mensaje' => $mensaje
            ));
        }

    }

    /**
     * Consulta las provincias donde se tiene predios u operaciones registrados en el módulo de Predio de Équidos y registro de operador
     */
    public function comboSitiosDestinoRegistroOperador()
    {
        $sitio = "";

        $tipoDestino = $_POST['tipoDestino'];
        $tipoOperacion = "'MPA','ATM','FEA','FER'";

        $pasaporte = $_POST['pasaporte'];
        $tipoDestino = $_POST['tipoDestino'];
        $idProvincia = $_POST['idProvincia'];
        $provincia = $_POST['provincia'];
        $ubicacionActual = $_POST['ubicacionActual'];
        $codigoSitio = ($_POST['codigoSitio'] != '' ? $_POST['codigoSitio'] : '');
        $nombreSitio = ($_POST['nombreSitio'] != '' ? $_POST['nombreSitio'] : '');
        $idSitio = ($_POST['idSitio'] != '' ? $_POST['idSitio'] : '');

        // Buscar sitio de acuerdo a los datos enviados
        $identificador = "";
        $codigoProvincia = "";
        $codigo = "";

        if ($tipoDestino == 'CentroConcentracion') { // Registro de Operador

            // Verificar si envían el código de sitio y generar la estructura de parámetros
            if ($codigoSitio != '') {
                $codigoCompleto = explode(".", $codigoSitio);
                $identificador = $codigoCompleto[0];

                if (isset($codigoCompleto[1])) {
                    $codigoUbicacion = str_split($codigoCompleto[1], 2);
                    $codigoProvincia = $codigoUbicacion[0];

                    if (isset($codigoUbicacion[1])) {
                        $codigo = $codigoUbicacion[1];
                    }
                }
            }

            $arrayParametros = array(
                'ubicacionActual' => $ubicacionActual,
                'provincia' => $provincia,
                'identificador_operador' => $identificador,
                'codigo_provincia' => $codigoProvincia,
                'codigo' => $codigo,
                'nombreSitio' => $nombreSitio,
                'tipoOperacion' => $tipoOperacion,
                'idSitio' => $idSitio
            );

            $sitio = '<option value="">Seleccione....</option>';

            if ($tipoDestino = 'CentroConcentracion') {
                $combo = $this->lNegocioSitios->buscarSitioDestinoXOperacion($arrayParametros);
            }

            foreach ($combo as $item) {
                $sitio .= '<option value="' . $item->id_sitio . '">' . $item->nombre_lugar . '</option>';
            }

            echo $sitio;
            exit();
        }
    }

    /**
     * Consulta las provincias donde se tiene predios u operaciones registrados en el módulo de Predio de Équidos y registro de operador
     */
    public function comboAreasDestinoRegistroOperador()
    {
        $sitio = "";

        $tipoDestino = $_POST['tipoDestino'];
        $tipoOperacion = "'MPA','ATM','FEA','FER'";

        $pasaporte = $_POST['pasaporte'];
        $tipoDestino = $_POST['tipoDestino'];
        $idProvincia = $_POST['idProvincia'];
        $provincia = $_POST['provincia'];
        $ubicacionActual = $_POST['ubicacionActual'];
        $codigoSitio = ($_POST['codigoSitio'] != '' ? $_POST['codigoSitio'] : '');
        $nombreSitio = ($_POST['nombreSitio'] != '' ? $_POST['nombreSitio'] : '');
        $idSitio = ($_POST['idSitio'] != '' ? $_POST['idSitio'] : '');
        $idSitioDestino = ($_POST['idSitioDestino'] != '' ? $_POST['idSitioDestino'] : '');

        // Buscar sitio de acuerdo a los datos enviados
        $identificador = "";
        $codigoProvincia = "";
        $codigo = "";

        if ($tipoDestino == 'CentroConcentracion') { // Registro de Operador

            // Verificar si envían el código de sitio y generar la estructura de parámetros
            if ($codigoSitio != '') {
                $codigoCompleto = explode(".", $codigoSitio);
                $identificador = $codigoCompleto[0];

                if (isset($codigoCompleto[1])) {
                    $codigoUbicacion = str_split($codigoCompleto[1], 2);
                    $codigoProvincia = $codigoUbicacion[0];

                    if (isset($codigoUbicacion[1])) {
                        $codigo = $codigoUbicacion[1];
                    }
                }
            }

            $arrayParametros = array(
                'ubicacionActual' => $ubicacionActual,
                'provincia' => $provincia,
                'identificador_operador' => $identificador,
                'codigo_provincia' => $codigoProvincia,
                'codigo' => $codigo,
                'nombreSitio' => $nombreSitio,
                'tipoOperacion' => $tipoOperacion,
                'idSitio' => $idSitio,
                'idSitioDestino' => $idSitioDestino
            );

            $sitio = '<option value="">Seleccione....</option>';

            if ($tipoDestino = 'CentroConcentracion') {
                $combo = $this->lNegocioAreas->buscarAreasDestinoXOperacion($arrayParametros);
            }

            foreach ($combo as $item) {
                $sitio .= '<option value="' . $item->id_area . '">' . $item->nombre_area . '</option>';
            }

            echo $sitio;
            exit();
        }
    }

    /**
     * Combo de medios de transporte para movilizaciones
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboMediosTransporteMovilizaciones($opcion = null)
    {
        $combo = "";
        if ($opcion == "Camion") {
            $combo .= '<option value="Camion" selected="selected">Camión</option>';
            $combo .= '<option value="Camioneta">Camioneta</option>';
            $combo .= '<option value="Trailer">Trailer</option>';
            $combo .= '<option value="Caminando">Caminando</option>';
        } else if ($opcion == "Camioneta") {
            $combo .= '<option value="Camion" >Camión</option>';
            $combo .= '<option value="Camioneta" selected="selected">Camioneta</option>';
            $combo .= '<option value="Trailer">Trailer</option>';
            $combo .= '<option value="Caminando">Caminando</option>';
        } else if ($opcion == "Trailer") {
            $combo .= '<option value="Camion" >Camión</option>';
            $combo .= '<option value="Camioneta">Camioneta</option>';
            $combo .= '<option value="Trailer" selected="selected">Trailer</option>';
            $combo .= '<option value="Caminando">Caminando</option>';
        } else if ($opcion == "Caminando") {
            $combo .= '<option value="Camion" >Camión</option>';
            $combo .= '<option value="Camioneta">Camioneta</option>';
            $combo .= '<option value="Trailer">Trailer</option>';
            $combo .= '<option value="Caminando" selected="selected">Caminando</option>';
        } else {
            $combo .= '<option value="Camion" >Camión</option>';
            $combo .= '<option value="Camioneta">Camioneta</option>';
            $combo .= '<option value="Trailer">Trailer</option>';
            $combo .= '<option value="Caminando">Caminando</option>';
        }

        return $combo;
    }

    /**
     * Método para desplegar el certificado PDF
     */
    public function mostrarReporte()
    {
        $this->urlPdf = $_POST['id'];
        require APP . 'PasaporteEquino/vistas/visorPDF.php';
    }

    /**
     * Función para generar el certificado
     */
    public function guardarMovilizacionEquino()
    {
        $resultado = $this->lNegocioMovilizaciones->guardarMovilizacionEquino($_POST);

        if ($resultado['bandera']) {
            echo json_encode(array(
                'estado' => $resultado['estado'],
                'mensaje' => $resultado['mensaje'],
                'contenido' => $resultado['contenido']
            ));
        } else {
            Mensajes::fallo($resultado['mensaje']);
        }
    }

    /**
     * Función para generar el certificado
     */
    public function generarCertificadoMovilizacion()
    {
        $estado = 'exito';
        $mensaje = 'Certificado generado con éxito';
        $contenido = '';

        if (strlen($_POST['id_movilizacion']) > 0) {
            $this->lNegocioMovilizaciones->generarCertificado($_POST['id_movilizacion'], $_POST['numero_permiso']);

            $contenido = PAS_EQUI_URL . $_POST['pasaporte_equino'] . "/" . $_POST['numero_permiso'] . ".pdf";
        } else {
            $mensaje = 'No se pudo generar el certificado';
            $estado = 'FALLO';
        }

        echo json_encode(array(
            'estado' => $estado,
            'mensaje' => $mensaje,
            'contenido' => $contenido
        ));
    }

    /**
     * Método para obtener los datos del sitio de destino
     */
    public function buscarSitiosDestinoRegistroOperadorPredioEquidos()
    {
        $resultado = $this->lNegocioMovilizaciones->buscarSitiosDestinoRegistroOperadorPredioEquidos($_POST);
        
        echo json_encode(array(
            'bandera' => $resultado['bandera'],
            'resultado' => $resultado['resultado'],
            'mensaje' => $resultado['mensaje'],
            'idCatastroPredioEquidos' => $resultado['idCatastroPredioEquidos'],
            'numSolicitud' => $resultado['numSolicitud'],
            'nombrePredio' => $resultado['nombrePredio'],            
            'cedulaPropietario' => $resultado['cedulaPropietario'],
            'nombrePropietario' => $resultado['nombrePropietario'],            
            'idProvincia' => $resultado['idProvincia'],
            'provincia' => $resultado['provincia'],
            'idCanton' => $resultado['idCanton'],
            'canton' => $resultado['canton'],
            'idParroquia' => $resultado['idParroquia'],
            'parroquia' => $resultado['parroquia'],
            'direccionPredio' => $resultado['direccionPredio'],
            'idSitio' => $resultado['idSitio'],
            'idArea' => $resultado['idArea']
        ));
    }
    
    /**
     * Método para generar el reporte de movilizaciones equinas en excel
     */
    public function exportarMovilizacionesExcel() {
        $idProvinciaFiltro = (isset($_POST["idProvinciaFiltro"])?$_POST["idProvinciaFiltro"]:'');
        $idCantonFiltro = (isset($_POST["idCantonFiltro"])?$_POST["idCantonFiltro"]:'');
        $estadoFiltro = (isset($_POST["estadoFiltro"])?$_POST["estadoFiltro"]:'');
        $fechaInicio = (isset($_POST["fechaInicio"])?$_POST["fechaInicio"]:'');
        $fechaFin =(isset($_POST["fechaFin"])?$_POST["fechaFin"]:'');
        
        $arrayParametros = array(
            'id_provincia' => $idProvinciaFiltro,
            'id_canton' => $idCantonFiltro,
            'estado_movilizacion' => $estadoFiltro,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        );
        
        $movilizaciones = $this->lNegocioMovilizaciones->buscarMovilizacionesReporteFiltradas($arrayParametros);
        
        //if(!empty($movilizaciones->current())){
            $this->lNegocioMovilizaciones->exportarArchivoExcelMovilizaciones($movilizaciones);
        /*}else{
            echo "No se dispone de datos con los parámetros solicitados. Por favor intente nuevamente.";
        }*/
    }
    
    /**
     * Proceso automático para cambiar de estado las movilizaciones expiradas
     */
    public function paCambioEstadoMovilizacionPasaporteEquino(){
        
        echo "\n".'Proceso Automático de cambio de estado de movilizaciones de equinos'."\n"."\n";
        
        $consulta = "   fecha_fin_movilizacion <='".date("Y-m-d H:i:s")."' and
                        estado_movilizacion = 'Vigente'";
        
        $movilizaciones = $this->lNegocioMovilizaciones->buscarLista($consulta);
        
        foreach ($movilizaciones as $fila) {
            $arrayParametros = array(
                'id_movilizacion' => $fila['id_movilizacion'],
                'estado_movilizacion' => 'Finalizado',
                'estado_fiscalizacion' => 'Finalizado'
            );
            
            $this->lNegocioMovilizaciones->guardar($arrayParametros);
            
            echo 'El certificado de movilización ' . $fila['id_movilizacion']. ' cambia de estado a Finalizado'."\n";
            
            $arrayParametros = array(
                'id_equino' => $fila['id_equino'],
                'estado_equino' => 'Activo',
                'motivo_cambio' => 'El equino cambia de estado a Activo por proceso automático.'
            );
            
            $this->lNegocioEquinos->guardar($arrayParametros);
            
            echo 'El equino ' . $fila['id_equino']. ' cambia de estado a Activo por proceso automático'."\n";
        }
        echo "\n";
    }
}