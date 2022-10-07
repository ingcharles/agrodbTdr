<?php
/**
 * Controlador Solicitud
 *
 * Este archivo controla la lógica del negocio del modelo:  SolicitudModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    SolicitudControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\SolicitudLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\SolicitudModelo;
/*use Agrodb\DossierPecuario\Modelos\SecuenciaRevisionLogicaNegocio;

use Agrodb\DossierPecuario\Modelos\OrigenProductoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\PresentacionComercialLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\ComposicionLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\FormaFisFarCosProductoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\UsoEspecieLogicaNegocio;*/

use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;

use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\LocalizacionModelo;
/*use Agrodb\Catalogos\Modelos\GrupoProductoLogicaNegocio;*/
use Agrodb\Catalogos\Modelos\SubtipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
//use Agrodb\Catalogos\Modelos\ProductosInocuidadLogicaNegocio;

use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;
use Agrodb\GUath\Modelos\FichaEmpleadoModelo;

use Agrodb\ProveedoresExterior\Modelos\ProveedorExteriorLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\ProveedorExteriorModelo;

use Agrodb\ProveedoresExterior\Modelos\ProductosProveedorLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\ProductosProveedorModelo;

/*use Agrodb\Estructura\Modelos\ResponsablesLogicaNegocio;

use Agrodb\Correos\Modelos\CorreosLogicaNegocio;*/

use Agrodb\Financiero\Modelos\OrdenPagoLogicaNegocio;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class SolicitudControlador extends BaseControlador
{

    private $lNegocioSolicitud = null;
    private $modeloSolicitud = null;
    
    private $modeloOperadores = null;
    private $modeloSubtipoProducto = null;
    
    private $lNegocioSecuenciaRevision = null;
    
    private $lNegocioOrigenProducto = null;
    private $lNegocioPresentacionComercial = null;
    private $lNegocioComposicion = null;
    private $lNegocioFormaFisFarCosProducto = null;
    private $lNegocioUsoEspecie = null;
    
    private $lNegocioOperadores = null;
    private $lNegocioLocalizacion = null;
    private $modeloLocalizacion = null;
    private $lNegocioGrupoProducto = null;
    private $lNegocioSubtipoProductos = null;
    private $lNegocioProductos = null;
    private $lNegocioProductosInocuidad = null;
    
    private $lNegocioFichaEmpleado = null;
    private $modeloFichaEmpleado = null;
    
    private $lNegocioProveedorExterior = null;
    private $modeloProveedorExterior = null;
    private $lNegocioProductosProveedor = null;
    private $modeloProductosProveedor = null;
    
    private $lNegocioResponsables = null;
    
    private $lNegocioCorreos = null;
    private $lNegocioDestinatarios = null;
    
    private $lNegocioOrdenPago = null;
        
    private $accion = null;
    private $formulario = null;
    private $solicitante = null;
    private $montoPago = null;
    private $rutaPago = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioSolicitud = new SolicitudLogicaNegocio();
        $this->modeloSolicitud = new SolicitudModelo();
        //$this->lNegocioSecuenciaRevision = new SecuenciaRevisionLogicaNegocio();

        $this->lNegocioOperadores = new OperadoresLogicaNegocio();
        
        /*$this->lNegocioOrigenProducto = new OrigenProductoLogicaNegocio();
        $this->lNegocioPresentacionComercial = new PresentacionComercialLogicaNegocio();
        $this->lNegocioComposicion = new ComposicionLogicaNegocio();
        $this->lNegocioFormaFisFarCosProducto = new FormaFisFarCosProductoLogicaNegocio();
        $this->lNegocioUsoEspecie = new UsoEspecieLogicaNegocio();*/

        $this->lNegocioLocalizacion = new LocalizacionLogicaNegocio();
        $this->modeloLocalizacion = new LocalizacionModelo();
        /*$this->lNegocioGrupoProducto = new GrupoProductoLogicaNegocio();*/
        $this->lNegocioSubtipoProductos = new SubtipoProductosLogicaNegocio();
        $this->lNegocioProductos = new ProductosLogicaNegocio();
        //$this->lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
        
        $this->lNegocioFichaEmpleado = new FichaEmpleadoLogicaNegocio();
        $this->modeloFichaEmpleado = new FichaEmpleadoModelo();
        
        $this->lNegocioProveedorExterior = new ProveedorExteriorLogicaNegocio();
        $this->modeloProveedorExterior = new FichaEmpleadoModelo();
        
        $this->lNegocioProductosProveedor = new ProductosProveedorLogicaNegocio();
        $this->modeloProductosProveedor = new ProductosProveedorModelo();
        
        /*$this->lNegocioResponsables = new ResponsablesLogicaNegocio();
        
        $this->lNegocioCorreos = new CorreosLogicaNegocio();*/
        
        $this->lNegocioOrdenPago = new OrdenPagoLogicaNegocio();
        
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
        //$this->paReducionTiempoSolicitudesSubsanacion();
        
        $this->articleHtmlSolicitudes();
        
        require APP . 'DossierPecuario/vistas/listaSolicitudVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Solicitud de Registro de Producto";
        $this->formulario = 'nuevo';

        require APP . 'DossierPecuario/vistas/formularioSolicitudVista.php';
    }
    
    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevaModificacion()
    {
        $this->accion = "Nueva Solicitud de Modificación de Registro de Producto";
        $this->formulario = 'modificacion';
        
        require APP . 'DossierPecuario/vistas/formularioSolicitudModificacionVista.php';
    }
    
     /**
     * Método para registrar en la base de datos -Solicitud
     */
    public function guardar()
    {
        //Solicitudes nuevas
        if (! isset($_POST['id_solicitud'])) {
            if($_POST['tipo_solicitud'] == 'Registro'){
                $resultado = $this->lNegocioSolicitud->guardarNuevaSolicitud($_POST);
            }else{
                $resultado = $this->lNegocioSolicitud->generarSolicitudReemplazo($_POST);
            }
            
        //Solicitudes existentes
        }else{
            $resultado = $this->lNegocioSolicitud->actualizarSolicitud($_POST);
        }
        
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
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Solicitud
     */
    public function editar()
    {
        $this->modeloSolicitud = $this->lNegocioSolicitud->buscar($_POST["id"]);
        
        $this->accion = "Editar Solicitud de Registro de Producto ".$this->modeloSolicitud->getGrupoProducto();
        $this->formulario = 'editar';        
        
        if($this->modeloSolicitud->getIdentificadorTecnico() != null){
            $this->modeloFichaEmpleado = $this->lNegocioFichaEmpleado->buscar($this->modeloSolicitud->getIdentificadorTecnico());
        }
        
        if($this->modeloSolicitud->getIdProvinciaRevision() != null){
            $this->modeloLocalizacion = $this->lNegocioLocalizacion->buscar($this->modeloSolicitud->getIdProvinciaRevision());
        }
        
        if($this->modeloSolicitud->getIdentificador() != null){
            $razonSocial = $this->lNegocioOperadores->buscar($this->modeloSolicitud->getIdentificador());
            $this->solicitante = $razonSocial->identificador . " - " . $razonSocial->razonSocial;
        }else{
            $this->solicitante = null;
        }

        require APP . 'DossierPecuario/vistas/formularioSolicitud' . $this->modeloSolicitud->getGrupoProducto() . 'Vista.php';
    }
    
    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Solicitud
     */
    public function abrir()    
    {
        $this->modeloSolicitud = $this->lNegocioSolicitud->buscar($_POST["id"]);
        
        $this->accion = "Solicitud de Registro de Producto ".$this->modeloSolicitud->getGrupoProducto();
        $this->formulario = 'abrir';
                
        if($this->modeloSolicitud->getIdentificadorTecnico() != null){
            $this->modeloFichaEmpleado = $this->lNegocioFichaEmpleado->buscar($this->modeloSolicitud->getIdentificadorTecnico());
        }
        
        if($this->modeloSolicitud->getIdProvinciaRevision() != null){
            $this->modeloLocalizacion = $this->lNegocioLocalizacion->buscar($this->modeloSolicitud->getIdProvinciaRevision());
        }
        
        if($this->modeloSolicitud->getIdentificador() != null){
            $razonSocial = $this->lNegocioOperadores->buscar($this->modeloSolicitud->getIdentificador());
            $this->solicitante = $razonSocial->identificador . " - " . $razonSocial->razonSocial;
        }
        
        if($this->modeloSolicitud->getEstadoSolicitud() == "verificacion"){
            $query = "id_solicitud = '".$this->modeloSolicitud->getIdSolicitud()."' and tipo_solicitud='dossierPecuario'";
            $financiero = $this->lNegocioOrdenPago->buscarLista($query);
            $this->montoPago = $financiero->current()->total_pagar;
            $this->rutaPago = $financiero->current()->orden_pago;
        }

        require APP . 'DossierPecuario/vistas/formularioSolicitud' . $this->modeloSolicitud->getGrupoProducto() . 'Vista.php';
    }
    
    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Solicitud
     */
    public function abrirTecnico()
    {
        $this->modeloSolicitud = $this->lNegocioSolicitud->buscar($_POST["id"]);
        
        $this->accion = "Revisión de solicitud de Registro de Producto ".$this->modeloSolicitud->getGrupoProducto();
        $this->formulario = 'abrirTecnico';       
        
        if($this->modeloSolicitud->getIdentificador() != null){
            $razonSocial = $this->lNegocioOperadores->buscar($this->modeloSolicitud->getIdentificador());
            $this->solicitante = $razonSocial->identificador . " - " . $razonSocial->razonSocial;
        }
        
        require APP . 'DossierPecuario/vistas/formularioSolicitud' . $this->modeloSolicitud->getGrupoProducto() . 'Vista.php';
    }
    
    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Solicitud
     */
    public function asignarTecnico()
    {
        $this->accion = "Asignación de Solicitudes para revisión";
        
        $this->modeloSolicitud = $this->lNegocioSolicitud->buscar($_POST["id"]);
        
        $this->modeloOperadores = $this->lNegocioOperadores->buscar($this->modeloSolicitud->getIdentificador());
        $this->modeloSubtipoProducto = $this->lNegocioSubtipoProductos->buscar($this->modeloSolicitud->getIdSubtipoProducto());
        
        require APP . 'DossierPecuario/vistas/formularioSolicitudAsignarTecnicoVista.php';
    }
    
    /**
     * Método para desplegar el formulario de confirmación de eliminación
     */
    public function confirmarEliminacion()
    {
        $this->accion = "Eliminar Solicitud de Registro de Producto";
        
        require APP . 'DossierPecuario/vistas/formularioConfirmacionEliminacionSolicitudVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Solicitud
     */
    public function borrar()
    {
        $this->lNegocioSolicitud->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Solicitud
     */
    public function tablaHtmlSolicitud($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_solicitud'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario/solicitud"
                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
                	<td>' . ++ $contador . '</td>
                	<td style="white - space:nowrap; "><b>' . $fila['id_solicitud'] . '</b></td>
                    <td>' . $fila['id_expediente'] . '</td>
                    <td>' . $fila['codigo_producto_final'] . '</td>
                    <td>' . $fila['secuencial'] . '</td>
                </tr>'
            );
        }
    }

    /**
     * Construye el código HTML para desplegar las Solicitudes en forma de artículos
     */
    public function articleHtmlSolicitudes()
    {
        $consultaCabecera = $this->lNegocioSolicitud->buscarEstadoSolicitudes($_SESSION['usuario']); // $identificador
        $contador = 1;
        $this->article = "";
        $opcion = 'editar';

        foreach ($consultaCabecera as $fila1) {

            switch ($fila1['estado_solicitud']) {
                case 'Creado':
                    $this->article .= "<h2> Solicitudes creadas </h2>";
                    $opcion = 'editar';
                    break;

                case 'verificacion':
                    $this->article .= "<h2> Solicitudes por pagar </h2>";
                    $opcion = 'abrir';
                    break;
                    
                case 'pago':
                    $this->article .= "<h2> Solicitudes por asignar tasa de pago </h2>";
                    $opcion = 'abrir';
                    break;
                    
                case 'Recibido':
                    $this->article .= "<h2> Solicitudes para asignación de técnico </h2>";
                    $opcion = 'abrir';
                    break;
                    
                case 'EnTramite':
                    $this->article .= "<h2> Solicitudes en proceso de revisión del Técnico </h2>";
                    $opcion = 'abrir';
                    break;

                case 'Subsanacion':
                    $this->article .= "<h2> Solicitudes remitidas para Subsanación </h2>";
                    $opcion = 'editar';
                    break;

                case 'Aprobado':
                    $this->article .= "<h2> Solicitudes Aprobadas </h2>";
                    $opcion = 'abrir';
                    break;

                case 'Rechazado':
                    $this->article .= "<h2> Solicitudes Rechazadas </h2>";
                    $opcion = 'abrir';
                    break;

                default:
                    $this->article .= "<h2> Solicitudes en estado " . $fila1['estado_solicitud'] . "</h2>";
                    $opcion = 'abrir';
                    break;
            }

            $query = "identificador = '" . $_SESSION['usuario'] . "' and estado_solicitud = '" . $fila1['estado_solicitud'] . "' ";

            $consulta = $this->lNegocioSolicitud->buscarLista($query);

            foreach ($consulta as $fila) {

                $this->article .= '<article id="' . $fila['id_solicitud'] . '" class="item"
            								data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario/Solicitud"
            								data-opcion="'.$opcion.'" ondragstart="drag(event)"
                                            draggable="true" data-destino="detalleItem">
        								<span><small>' . $fila['nombre_producto'] . ' </small></span><br/>
                                        <span><small><b>Solicitud: </b>' . $fila['tipo_solicitud'] . ' </small></span><br/>
        					 			<span class="ordinal">' . $contador ++ . '</span>
        								<aside><small><b>Solicitud: </b>' . $fila['id_solicitud'] . '</small><br/>
                                        <small>'.($fila['estado_solicitud']=='Aprobado'?'<b>Reg: </b>' . $fila['codigo_producto_final']:'<b>Exp: </b>' . $fila['id_expediente']) . '</small></aside>
    								</article>';
            }

            // data-opcion="' . ($fila['estado']=='subsanacion'?'editar':'abrir') . '" ondragstart="drag(event)"
        }
    }

    /**
     * Método para obtener los datos del operador
     */
    public function obtenerDatosOperador()
    {
        $validacion = "Fallo";
        $resultado = "El operador no existe.";

        $identificador = $_POST["identificador"];

        // Busca los datos de registro del operador
        $datosOperador = $this->lNegocioOperadores->buscar($identificador);

        if ($datosOperador->identificador != '') {
            $validacion = "Exito";
            $resultado = "El operador se encuentra registrado.";

            $datosProvincia = $this->lNegocioLocalizacion->buscarProvinciaXNombre($datosOperador->provincia);

            $datosPais = $this->lNegocioLocalizacion->buscarPaisesPorNombre('Ecuador');

            echo json_encode(array(
                'resultado' => $resultado,
                'razon_social' => $datosOperador->razonSocial,
                'id' => $datosOperador->identificador,
                'direccion' => $datosOperador->direccion,
                'id_provincia' => $datosProvincia->current()->id_localizacion,
                'provincia' => $datosOperador->provincia,
                'canton' => $datosOperador->canton,
                'parroquia' => $datosOperador->parroquia,
                'telefono' => $datosOperador->telefonoUno,
                'celular' => $datosOperador->celularUno,
                'correo' => $datosOperador->correo,
                'nombre_representante' => $datosOperador->nombreRepresentante . ' ' . $datosOperador->apellidoRepresentante,
                'id_pais' => $datosPais->current()->id_localizacion,
                'pais' => $datosPais->current()->nombre,
                'validacion' => $validacion
            ));
        } else {
            $resultado = "El operador no se encuentra registrado.";

            echo json_encode(array(
                'resultado' => $resultado,
                'validacion' => $validacion
            ));
        }
    }

    

    /**
     * Método para validar el nombre del producto a registrar
     */
    /*public function validarNombreProducto($nombreProducto)
    {
        $validacion = "Exito";
        $nombre = "El nombre del producto elegido se encuentra disponible.";

        // Si encuentra coincidencias mostrará mensaje de error en pantalla para el cambio de nombre
        // Busca si existe el nombre en solicitudes vigentes del Dossier Pecuario
        $producto = $this->lNegocioSolicitud->buscarProductoDossierRIA($nombreProducto);

        if (isset($producto->current()->nombre_producto)) {
            $datos = $producto->current()->nombre_producto;

            if (strlen(trim($datos)) > 0) {
                $validacion = "Fallo";
                $nombre = "El nombre del producto elegido ya se encuentra registrado o en proceso de registro.";
            }
        }

        echo json_encode(array(
            'nombre' => $nombre,
            'validacion' => $validacion
        ));
    }*/
    
    /**
     * Método para validar el nombre del producto a registrar
     */
    public function validarNombreProducto()
    {
        $nombreProducto = $_POST["nombre"];
        
        $validacion = "Exito";
        $nombre = "El nombre del producto elegido se encuentra disponible.";
        
        // Si encuentra coincidencias mostrará mensaje de error en pantalla para el cambio de nombre
        // Busca si existe el nombre en solicitudes vigentes del Dossier Pecuario
        $producto = $this->lNegocioSolicitud->buscarProductoDossierRIA($nombreProducto);
        
        if (isset($producto->current()->nombre_producto)) {
            $datos = $producto->current()->nombre_producto;
            
            if (strlen(trim($datos)) > 0) {
                $validacion = "Fallo";
                $nombre = "El nombre del producto elegido ya se encuentra registrado o en proceso de registro.";
            }
        }
        
        echo json_encode(array(
            'nombre' => $nombre,
            'validacion' => $validacion
        ));
    }

    /**
     * Combo de origen de producto completo
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboOrigenProductoCompleto($opcion = null)
    {
        $combo = "";
        if ($opcion == "TitularRegistro") {
            $combo .= '<option value="TitularRegistro" selected="selected">Titular del Registro</option>';
            $combo .= '<option value="ContratoNacional">Elaborador por Contrato Nacional</option>';
            $combo .= '<option value="Extranjero">Extranjero</option>';
        } else if ($opcion == "ContratoNacional") {
            $combo .= '<option value="TitularRegistro" >Titular del Registro</option>';
            $combo .= '<option value="ContratoNacional" selected="selected">Elaborador por Contrato Nacional</option>';
            $combo .= '<option value="Extranjero">Extranjero</option>';
        } else if ($opcion == "Extranjero") {
            $combo .= '<option value="TitularRegistro" >Titular del Registro</option>';
            $combo .= '<option value="ContratoNacional">Elaborador por Contrato Nacional</option>';
            $combo .= '<option value="Extranjero" selected="selected">Extranjero</option>';
        } else {
            $combo .= '<option value="TitularRegistro">Titular del Registro</option>';
            $combo .= '<option value="ContratoNacional">Elaborador por Contrato Nacional</option>';
            $combo .= '<option value="Extranjero">Extranjero</option>';
        }

        return $combo;
    }

    /**
     * Combo de origen de producto completo
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboOrigenProductoParcial($opcion = null)
    {
        $combo = "";
        if ($opcion == "TitularRegistro") {
            $combo .= '<option value="TitularRegistro" selected="selected">Titular del Registro</option>';
            $combo .= '<option value="ContratoNacional">Elaborador por Contrato Nacional</option>';
        } else if ($opcion == "ContratoNacional") {
            $combo .= '<option value="TitularRegistro" >Titular del Registro</option>';
            $combo .= '<option value="ContratoNacional" selected="selected">Elaborador por Contrato Nacional</option>';
        } else {
            $combo .= '<option value="TitularRegistro">Titular del Registro</option>';
            $combo .= '<option value="ContratoNacional">Elaborador por Contrato Nacional</option>';
        }

        return $combo;
    }

    /**
     * Método para obtener los datos del operador
     */
    public function obtenerDatosOperadorContratoNacional()
    {
        $validacion = "Fallo";
        $resultado = "El operador no existe.";

        $identificador = $_POST["identificador"];

        // Busca los datos de registro del operador
        $datosOperador = $this->lNegocioOperadores->obtenerDatosOperadorXOperacionDossierPecuario($identificador);

        if (isset($datosOperador->current()->identificador)) {
            $resultado = "El operador se encuentra registrado.";
            $validacion = "Exito";

            $datosProvincia = $this->lNegocioLocalizacion->buscarProvinciaXNombre($datosOperador->current()->provincia);

            $datosPais = $this->lNegocioLocalizacion->buscarPaisesPorNombre('Ecuador');

            echo json_encode(array(
                'resultado' => $resultado,
                'razon_social' => $datosOperador->current()->razon_social,
                'id' => $datosOperador->current()->identificador,
                'direccion' => $datosOperador->current()->direccion,
                'id_provincia' => $datosProvincia->current()->id_localizacion,
                'provincia' => $datosOperador->current()->provincia,
                'canton' => $datosOperador->current()->canton,
                'parroquia' => $datosOperador->current()->parroquia,
                'telefono' => $datosOperador->current()->telefono_uno,
                'celular' => $datosOperador->current()->celular_uno,
                'correo' => $datosOperador->current()->correo,
                'nombre_representante' => $datosOperador->current()->nombre_representante . ' ' . $datosOperador->current()->apellido_representante,
                'id_pais' => $datosPais->current()->id_localizacion,
                'pais' => $datosPais->current()->nombre,
                'validacion' => $validacion
            ));
        } else {
            $resultado = "El operador no se encuentra registrado o no tiene las operaciones necesarias registradas.";

            echo json_encode(array(
                'resultado' => $resultado,
                'validacion' => $validacion
            ));
        }
    }
    
    /**
     * Construye el combo para desplegar la lista de Proveedores en el Exterior por estado (Módulo Proveedores Exterior)
     */
    public function comboProveedoresExterior($estado)
    {
        $sentencia = "estado_solicitud in (" . $estado . ") order by nombre_fabricante ASC;";
        
        $comboProveedor = '<option value="">Seleccionar...</option>';
        $proveedor = $this->lNegocioProveedorExterior->buscarLista($sentencia);
        
        $comboProveedor = array();
        
        foreach ($proveedor as $item){
            if(($item['nombre_fabricante']!==null) && ($item['nombre_fabricante']!=='')){
                $comboProveedor[] = array ('value' => $item->codigo_aprobacion_solicitud.' -> '.$item->nombre_fabricante, 'label' => $item->codigo_aprobacion_solicitud.' -> '.$item->nombre_fabricante);
            }
        }
        //revisar los datos aun no se pone nada
        echo json_encode(array(
            'mensaje' => $comboProveedor
        ));
    }
    
    /**
     * Método para obtener los datos del Proveedor en el Exterior (Módulo Proveedores Exterior)
     */
    public function obtenerDatosProveedorExterior()
    {
        $validacion = "Fallo";
        $resultado = "El proveedor no existe.";
        
        $codigoAprobacionSolicitud = $_POST["codigo_aprobacion_solicitud"];
        
        // Busca los datos del proveedor en el exterior
        $datosProveedor = $this->lNegocioProveedorExterior->buscarInformacionProveedorDossier($codigoAprobacionSolicitud);
        
        if (isset($datosProveedor->current()->id_proveedor_exterior)) {
            $resultado = "El proveedor se encuentra registrado.";
            $validacion = "Exito";
            
            echo json_encode(array(
                'resultado' => $resultado,
                'id' => $datosProveedor->current()->id_proveedor_exterior,
                'nombre_fabricante' => $datosProveedor->current()->nombre_fabricante,
                'id_pais' => $datosProveedor->current()->id_pais_fabricante,
                'pais' => $datosProveedor->current()->nombre_pais_fabricante,
                'direccion' => $datosProveedor->current()->direccion_fabricante,
                'tipo_producto' => substr($datosProveedor->current()->tipo_producto, 0, 2048),  
                'validacion' => $validacion
            ));
        } else {
            $resultado = "El proveedor no se encuentra registrado.";
            
            echo json_encode(array(
                'resultado' => $resultado,
                'validacion' => $validacion
            ));
        }
    }
     
    /**
     * Combo de fases de revisión
     *
     * @param $respuesta
     * @return string
     */
    public function comboEstadosRevisionDossierPecuarioTecnico($opcion=null)
    {
        $combo = "";
        
        if ($opcion == "Aprobado") {
            $combo .= '<option value="Aprobado" selected="selected">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Subsanacion">Subsanación</option>';
        }else if ($opcion == "Rechazado") {
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado" selected="selected">Rechazado</option>';
            $combo .= '<option value="Subsanacion">Subsanación</option>';
        }else if ($opcion == "Subsanacion") {
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Subsanacion" selected="selected">Subsanación</option>';
        }else {
            $combo .= '<option value="Aprobado">Aprobado</option>';
            $combo .= '<option value="Rechazado">Rechazado</option>';
            $combo .= '<option value="Subsanacion">Subsanación</option>';
        }
        return $combo;
    }
    
    /**
     * Combo de tipo solicitud
     *
     * @param
     *            $respuesta
     * @return string
     */
    public function comboTipoSolicitud($opcion = null)
    {
        $combo = "";
        if ($opcion == "Modificacion") {
            $combo .= '<option value="Modificacion" selected="selected">Modificación</option>';
            $combo .= '<option value="Reevaluacion">Reevaluación</option>';
        } else if ($opcion == "Reevaluacion") {
            $combo .= '<option value="Modificacion" >Modificacion</option>';
            $combo .= '<option value="Reevaluacion" selected="selected">Reevaluación</option>';
        } else {
            $combo .= '<option value="Modificacion">Modificacion</option>';
            $combo .= '<option value="Reevaluacion">Reevaluación</option>';
        }
        
        return $combo;
    }
    
    /**
     * Proceso automático para disminuir el tiempo asignado de solicitudes en estado de subsanación
     */
    public function paReducionTiempoSolicitudesSubsanacion(){
        
        echo "\n".'Proceso Automático de decremento de tiempo de solicitudes en subsanación'."\n"."\n";
        
        $consulta = "  tipo_solicitud in ('Registro', 'Reevaluacion') and estado_solicitud = 'Subsanacion'";
        
        if(date('w') != 0 && date('w') !=6){//0, 6 fin de semana
            $solicitudes = $this->lNegocioSolicitud->buscarLista($consulta);
            
            foreach ($solicitudes as $fila) {   
                
                $tiempo = $fila['tiempo_subsanacion'] - 1;
                
                $arrayParametros = array(
                    'id_solicitud' => $fila['id_solicitud'],
                    'tiempo_subsanacion' => $tiempo
                );
                
                $this->lNegocioSolicitud->guardar($arrayParametros);
                
                echo 'La Solicitud de Dossier Pecuario ' . $fila['id_solicitud']. ' cambia de ' . $fila['tiempo_subsanacion']. ' a ' . $tiempo. '.\n';
                echo date('w');
            }
        }else{
            echo "fin de semana";
        }
        echo "\n";
    }
    
    /**
     * Método para generar el reporte de solicitudes de dossier pecuario en excel
     */
    public function exportarReporteSolicitudSecuenciaRevisionExcel() {
        $numeroTramite = $_POST["numeroTramiteFiltro"];
        $identificador = $_POST["identificadorFiltro"];
        $fechaInicio = $_POST["fechaInicioFiltro"];
        $fechaFin = $_POST["fechaFinFiltro"];
        
        $arrayParametros = array(
            'id_expediente' => $numeroTramite,            
            'identificador' => $identificador,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        );
        
        $solicitudes = $this->lNegocioSolicitud->buscarSolicitudesSecuenciaRevisionXFiltro($arrayParametros);
        
        $this->lNegocioSolicitud->exportarArchivoExcelSolicitudesSecuenciaRevision($solicitudes);
    }
    
    /**
     * Consulta los productos veterinarios del operador que estén aprobados y disponibles para modificación y construye el combo
     */
    public function comboProductosModificacion()
    {
        $combo = $this->lNegocioSolicitud->buscarProductosModificables($_SESSION['usuario']);
        
        foreach ($combo as $item) {
            $productos .= '<option value="' . $item->id_solicitud . '">' . $item->nombre_producto . '</option>';
        }
        return $productos;
    }
}