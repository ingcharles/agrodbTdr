<?php
/**
 * Lógica del negocio de SolicitudModelo
 *
 * Este archivo se complementa con el archivo SolicitudControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-02
 * @uses    SolicitudLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;
use Agrodb\DossierPecuario\Modelos\SecuenciaRevisionLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\OrigenProductoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\PresentacionComercialLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\ComposicionLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\FormaFisFarCosProductoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\UsoEspecieLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\PartidaCodigosLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\FormaAdministracionLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\FormaAdministracionAlimentoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\FormaAplicacionInstalacionesLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\DosisViaAdministracionLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\PeriodoRetiroLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\CantidadDosisLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\EfectoBiologicoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\EspecieDestinoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\ReactivoMaterialLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\PeriodoVidaUtilLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\TiempoRetiroLogicaNegocio;

use Agrodb\Estructura\Modelos\ResponsablesLogicaNegocio;
use Agrodb\Estructura\Modelos\AreaLogicaNegocio;
use Agrodb\Estructura\Modelos\FuncionariosLogicaNegocio;

use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;

use Agrodb\Correos\Modelos\CorreosLogicaNegocio;

use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosInocuidadLogicaNegocio;
use Agrodb\Catalogos\Modelos\ComposicionInocuidadLogicaNegocio;
use Agrodb\Catalogos\Modelos\CodigosInocuidadLogicaNegocio;
use Agrodb\Catalogos\Modelos\CodigosAdicionalesPartidasLogicaNegocio;
use Agrodb\Catalogos\Modelos\FabricanteFormuladorLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductoInocuidadUsoLogicaNegocio;
use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\DeclaracionVentaLogicaNegocio;
use Agrodb\Catalogos\Modelos\FormulacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\EspeciesLogicaNegocio;
use Agrodb\Catalogos\Modelos\ViaAdministracionLogicaNegocio;
use Agrodb\Catalogos\Modelos\CategoriaToxicologicaLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosConsumiblesLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoComponenteLogicaNegocio;
use Agrodb\Catalogos\Modelos\IngredienteActivoInocuidadLogicaNegocio;
use Agrodb\Catalogos\Modelos\UsosLogicaNegocio;

use Agrodb\FirmaDocumentos\Modelos\DocumentosLogicaNegocio;

use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;

use Agrodb\Core\JasperReport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Agrodb\Core\Excepciones\GuardarExcepcion;

class SolicitudLogicaNegocio implements IModelo
{

    private $modeloSolicitud = null;
    private $modeloSolicitudReemplazo = null;
    private $lNegocioSecuenciaRevision = null;
    private $lNegocioOrigenProducto = null;
    private $lNegocioPresentacionComercial = null;
    private $lNegocioComposicion = null;
    private $lNegocioFormaFisFarCosProducto = null;
    private $lNegocioUsoEspecie = null;
    private $lNegocioPartidaCodigos = null;
    private $lNegocioFormaAdministracion = null;
    private $lNegocioFormaAdministracionAlimento = null;
    private $lNegocioFormaAplicacionInstalaciones = null;
    private $lNegocioDosisViaAdministracion = null;
    private $lNegocioPeriodoRetiro = null;
    private $lNegocioCantidadDosis = null;
    private $lNegocioEfectoBiologico = null;
    private $lNegocioEspecieDestino = null;
    private $lNegocioReactivoMaterial = null;
    private $lNegocioPeriodoVidaUtil = null;
    
    private $lNegocioTiempoRetiro = null;
    private $lNegocioResponsables = null;
    private $lNegocioArea = null;
    private $lNegocioFuncionarios = null;

    private $lNegocioOperadores = null;

    private $lNegocioProductos = null;
    private $lNegocioProductosInocuidad = null;
    private $lNegocioComposicionInocuidad = null;
    private $lNegocioCodigosInocuidad = null;
    private $lNegocioCodigosAdicionalesPartidas = null;
    private $lNegocioFabricanteFormulador = null;
    private $lNegocioProductoInocuidadUso = null;
    private $lNegocioLocalizacion = null;
    private $lNegocioDeclaracionVenta = null;
    private $lNegocioFormulacion = null;
    private $lNegocioEspecies = null;
    private $lNegocioViaAdministracion = null;
    private $lNegocioCategoriaToxicologica = null;
    private $lNegocioProductosConsumibles = null;
    private $lNegocioTipoComponente = null;
    private $lNegocioIngredienteActivoInocuidad = null;
    private $lNegocioUsos = null;

    private $lNegocioCorreos = null;
    private $lNegocioDocumentos = null;
    private $lNegocioFichaEmpleado = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloSolicitud = new SolicitudModelo();
        $this->modeloSolicitudReemplazo = new SolicitudModelo();

        $this->lNegocioSecuenciaRevision = new SecuenciaRevisionLogicaNegocio();

        $this->lNegocioOrigenProducto = new OrigenProductoLogicaNegocio();
        $this->lNegocioPresentacionComercial = new PresentacionComercialLogicaNegocio();
        $this->lNegocioComposicion = new ComposicionLogicaNegocio();
        $this->lNegocioFormaFisFarCosProducto = new FormaFisFarCosProductoLogicaNegocio();
        $this->lNegocioUsoEspecie = new UsoEspecieLogicaNegocio();
        $this->lNegocioPartidaCodigos = new PartidaCodigosLogicaNegocio();
        $this->lNegocioFormaAdministracion = new FormaAdministracionLogicaNegocio();
        $this->lNegocioFormaAdministracionAlimento = new FormaAdministracionAlimentoLogicaNegocio();
        $this->lNegocioFormaAplicacionInstalaciones = new FormaAplicacionInstalacionesLogicaNegocio();
        $this->lNegocioDosisViaAdministracion = new DosisViaAdministracionLogicaNegocio();
        $this->lNegocioPeriodoRetiro = new PeriodoRetiroLogicaNegocio();
        $this->lNegocioCantidadDosis = new CantidadDosisLogicaNegocio();
        $this->lNegocioEfectoBiologico = new EfectoBiologicoLogicaNegocio();
        $this->lNegocioEspecieDestino = new EspecieDestinoLogicaNegocio();
        $this->lNegocioReactivoMaterial = new ReactivoMaterialLogicaNegocio();
        $this->lNegocioPeriodoVidaUtil = new PeriodoVidaUtilLogicaNegocio();
        $this->lNegocioTiempoRetiro = new TiempoRetiroLogicaNegocio();

        $this->lNegocioResponsables = new ResponsablesLogicaNegocio();
        $this->lNegocioArea = new AreaLogicaNegocio();
        $this->lNegocioFuncionarios = new FuncionariosLogicaNegocio();

        $this->lNegocioOperadores = new OperadoresLogicaNegocio();

        $this->lNegocioProductos = new ProductosLogicaNegocio();
        $this->lNegocioProductosInocuidad = new ProductosInocuidadLogicaNegocio();
        $this->lNegocioComposicionInocuidad = new ComposicionInocuidadLogicaNegocio();
        $this->lNegocioCodigosInocuidad = new CodigosInocuidadLogicaNegocio();
        $this->lNegocioCodigosAdicionalesPartidas = new CodigosAdicionalesPartidasLogicaNegocio();
        $this->lNegocioFabricanteFormulador = new FabricanteFormuladorLogicaNegocio();
        $this->lNegocioProductoInocuidadUso = new ProductoInocuidadUsoLogicaNegocio();

        $this->lNegocioLocalizacion = new LocalizacionLogicaNegocio();
        $this->lNegocioDeclaracionVenta = new DeclaracionVentaLogicaNegocio();
        $this->lNegocioFormulacion = new FormulacionLogicaNegocio();
        $this->lNegocioEspecies = new EspeciesLogicaNegocio();
        $this->lNegocioViaAdministracion = new ViaAdministracionLogicaNegocio();
        $this->lNegocioCategoriaToxicologica = new CategoriaToxicologicaLogicaNegocio();
        $this->lNegocioProductosConsumibles = new ProductosConsumiblesLogicaNegocio();
        $this->lNegocioTipoComponente = new TipoComponenteLogicaNegocio();
        $this->lNegocioIngredienteActivoInocuidad = new IngredienteActivoInocuidadLogicaNegocio();
        $this->lNegocioUsos = new UsosLogicaNegocio();

        $this->lNegocioCorreos = new CorreosLogicaNegocio();
        $this->lNegocioDocumentos = new DocumentosLogicaNegocio();
        $this->lNegocioFichaEmpleado = new FichaEmpleadoLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        try {

            $tablaModelo = new SolicitudModelo($datos);

            $procesoIngreso = $this->modeloSolicitud->getAdapter()
                ->getDriver()
                ->getConnection();
            $procesoIngreso->beginTransaction();

            $this->modeloSolicitud->guardarSql('solicitud', $this->modeloSolicitud->getEsquema());

            $datosBd = $tablaModelo->getPrepararDatos();

            if ($tablaModelo->getIdSolicitud() != null && $tablaModelo->getIdSolicitud() > 0) {
                $this->modeloSolicitud->actualizar($datosBd, $tablaModelo->getIdSolicitud());
                $idSolicitud = $tablaModelo->getIdSolicitud();
            } else {
                unset($datosBd["id_solicitud"]);
                $idSolicitud = $this->modeloSolicitud->guardar($datosBd);
            }

            $procesoIngreso->commit();
            return $idSolicitud;
        } catch (GuardarExcepcion $ex) {
            $procesoIngreso->rollback();
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Borra el registro actual *
     *
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloSolicitud->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return SolicitudModelo
     */
    public function buscar($id)
    {
        return $this->modeloSolicitud->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloSolicitud->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloSolicitud->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarSolicitud()
    {
        $consulta = "SELECT * FROM " . $this->modeloSolicitud->getEsquema() . ". solicitud";
        return $this->modeloSolicitud->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca los estados de las solicitudes del usuario para despliegue
     *
     * @return array|ResultSet
     */
    public function buscarEstadoSolicitudes($identificador)
    {
        $consulta = "   SELECT
                            DISTINCT estado_solicitud
                        FROM
                            g_dossier_pecuario_mvc.solicitud
                        WHERE
                            identificador in ('$identificador') and
                            estado_solicitud not in ('Eliminado')
                        GROUP BY
                            estado_solicitud; ";

        return $this->modeloSolicitud->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca un producto por nombre en las solicitudes de Dossier Pecuario activas
     * y en los productos registrados en el módulo Registro de Productos RIA (catalogo)
     *
     * @return array|ResultSet
     */
    public function buscarProductoDossierRIA($nombreProducto)
    {
        
        $consulta = "   SELECT
                        	s.nombre_producto as nombre_producto
                        FROM
                        	g_dossier_pecuario_mvc.solicitud s
                        WHERE
                        	quitar_caracteres_especiales(upper(trim(s.nombre_producto))) ilike quitar_caracteres_especiales(upper(trim('$nombreProducto'))) and
                            s.estado_solicitud not in ('Rechazado', 'Eliminado')
                                
                        UNION
                        
                        SELECT
                        	p.nombre_comun as nombre_producto
                        FROM
                        	g_catalogos.productos p
                            INNER JOIN g_catalogos.subtipo_productos sp ON p.id_subtipo_producto = p.id_subtipo_producto
                        	INNER JOIN g_catalogos.tipo_productos tp ON tp.id_tipo_producto = sp.id_tipo_producto
                        WHERE
                        	quitar_caracteres_especiales(upper(trim(p.nombre_comun))) ilike quitar_caracteres_especiales(upper(trim('$nombreProducto'))) and
                            p.estado not in ('9') and
                            tp.codificacion_tipo_producto = 'TIPO_VETERINARIO'; ";
        
        return $this->modeloSolicitud->ejecutarSqlNativo($consulta);
    }

    /**
     * Genera el número de expediente de la solicitud de registro de producto
     *
     * @return array|ResultSet
     */
    public function generarCodigoExpediente()
    {
        $anio = date("Y");

        $formatoCodigo = "RIP-" . $anio . "-";
        $codigoBase = 'RIP-' . $anio;

        $consulta = "SELECT
						max(split_part(id_expediente, '$formatoCodigo' , 2)::int) as numero
					FROM
						g_dossier_pecuario_mvc.solicitud
					WHERE id_expediente LIKE '$codigoBase%';";

        $codigo = $this->modeloSolicitud->ejecutarSqlNativo($consulta);
        $fila = $codigo->current();

        $idExpediente = array(
            'numero' => $fila['numero']
        );

        $incremento = $idExpediente['numero'] + 1;
        $idExpediente = $formatoCodigo . str_pad($incremento, 5, "0", STR_PAD_LEFT);

        return $idExpediente;
    }

    /**
     * Función para crear el PDF del certificado
     */
    public function generarDocumentoExpediente($idSolicitud)
    {
        $jasper = new JasperReport();
        $datosReporte = array();

        $solicitud = $this->buscar($idSolicitud);

        $idExpediente = $solicitud->getIdExpediente();

        $ruta = DOSS_PEC_URL_CERT . $idExpediente . "/";
        
        if (! file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        $datosReporte = array(
            'rutaReporte' => 'DossierPecuario/vistas/reportes/Expediente' . $this->modeloSolicitud->getGrupoProducto() . '.jasper',
            'rutaSalidaReporte' => 'DossierPecuario/archivos/' . $idExpediente . '/Expediente_' . $idExpediente,
            'tipoSalidaReporte' => array(
                'pdf'
            ),
            'parametrosReporte' => array(
                'idSolicitud' => (int) $idSolicitud,
                'rutaFondo' => RUTA_IMG_GENE . 'fondoCertificado.png'
            ),
            'conexionBase' => 'SI'
        );

        $jasper->generarArchivo($datosReporte);

        $contenido = DOSS_PEC_URL . $idExpediente . "/" . "Expediente_" . $idExpediente . ".pdf";

        return $contenido;
    }

    /**
     * Función para crear el PDF del certificado
     */
    public function generarDocumentoCertificado($idSolicitud)
    {
        $jasper = new JasperReport();
        $datosReporte = array();

        $solicitud = $this->buscar($idSolicitud);
        
        $provincia = $this->lNegocioLocalizacion->buscar($solicitud->getIdProvinciaRevision());
        
        //Verificación de técnicos para Coord Pichincha y Planta Central
        if($provincia->nombre == 'Pichincha'){
            $idTecnico = $solicitud->getIdentificadorTecnico();
            
            //Buscar si el tecnico esta en la estructura dentro de un listado de areas de cria
            $oficina = $this->verificarOficinaTecnico($idTecnico);
            
            //Si es CRIA (Planta Central) firmante Coord. CRIA, caso contrario Coord. Provincial
            if($oficina == 'CRIA'){
                $ubicacion = 'General de Registro de Insumos Agropecuarios';
            }else{
                $ubicacion = $provincia->nombre;
            }
        }else{
            $ubicacion = $provincia->nombre;
        } 
        
        $nombreCoordinador = $this->lNegocioResponsables->buscarResponsableProvincial($ubicacion);

        $idExpediente = $solicitud->getIdExpediente();

        $ruta = DOSS_PEC_URL_CERT . $idExpediente . "/";

        if (! file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        $datosReporte = array(
            'rutaReporte' => 'DossierPecuario/vistas/reportes/Certificado' . $this->modeloSolicitud->getGrupoProducto() . '.jasper',
            'rutaSalidaReporte' => 'DossierPecuario/archivos/' . $idExpediente . '/Certificado_' . $idExpediente,
            'tipoSalidaReporte' => array(
                'pdf'
            ),
            'parametrosReporte' => array(
                'idSolicitud' => (int) $idSolicitud,
                'idArea' => $nombreCoordinador->current()->id_area,
                'ruta' => URL_GUIA_PROYECTO . '/' . DOSS_PEC_URL . $idExpediente . '/Certificado_' . $idExpediente . '.pdf',
                'rutaFondo' => RUTA_IMG_GENE . 'fondoCertificado.png'
            ),
            'conexionBase' => 'SI'
        );

        $jasper->generarArchivo($datosReporte);

        $contenido = DOSS_PEC_URL_CERT . $idExpediente . "/" . "Certificado_" . $idExpediente . ".pdf";
        
        //Firma electrónica de documento creado
        $arrayDocumento = array(
            'archivo_entrada' => $contenido,
            'archivo_salida' => $contenido,
            'identificador' => $nombreCoordinador->current()->identificador,
            'razon_documento' => 'Certificado de '. $solicitud->getTipoSolicitud() . ' de Producto',
            'tabla_origen' => 'g_dossier_pecuario_mvc.solicitud',
            'campo_origen' => 'ruta_certificado',
            'id_origen' => $idSolicitud,
            'estado' => 'Atendida',
            'proceso_firmado' => 'SI'
        );
        
        $this->lNegocioDocumentos->guardar($arrayDocumento);

        return $contenido;
    }

    /**
     * Función para crear el PDF del certificado
     */
    public function generarDocumentoPuntosMinimos($idSolicitud)
    {
        $jasper = new JasperReport();
        $datosReporte = array();

        $solicitud = $this->buscar($idSolicitud);
        $provincia = $this->lNegocioLocalizacion->buscar($solicitud->getIdProvinciaRevision());

        //Verificación de técnicos para Coord Pichincha y Planta Central
        if($provincia->nombre == 'Pichincha'){
            $idTecnico = $solicitud->getIdentificadorTecnico();
            
            //Buscar si el tecnico esta en la estructura dentro de un listado de areas de cria
            $oficina = $this->verificarOficinaTecnico($idTecnico);
            
            //Si es CRIA (Planta Central) firmante Coord. CRIA, caso contrario Coord. Provincial
            if($oficina == 'CRIA'){
                $ubicacion = 'General de Registro de Insumos Agropecuarios';
            }else{
                $ubicacion = $provincia->nombre;
            }
        }else{
            $ubicacion = $provincia->nombre;
        }        
        
        $nombreCoordinador = $this->lNegocioResponsables->buscarResponsableProvincial($ubicacion);

        $idExpediente = $solicitud->getIdExpediente();
        $ruta = DOSS_PEC_URL_CERT . $idExpediente . "/";
        
        if (! file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        $datosReporte = array(
            'rutaReporte' => 'DossierPecuario/vistas/reportes/PuntosMinimos' . $this->modeloSolicitud->getGrupoProducto() . '.jasper',
            'rutaSalidaReporte' => 'DossierPecuario/archivos/' . $idExpediente . '/PuntosMinimos_' . $idExpediente,
            'tipoSalidaReporte' => array(
                'pdf'
            ),
            'parametrosReporte' => array(
                'idSolicitud' => (int) $idSolicitud,
                'idArea' => $nombreCoordinador->current()->id_area,
                'ruta' => URL_GUIA_PROYECTO . '/' . DOSS_PEC_URL . $idExpediente . '/PuntosMinimos_' . $idExpediente . '.pdf',
                'rutaFondo' => RUTA_IMG_GENE . 'fondoCertificado.png'
            ),
            'conexionBase' => 'SI'
        );

        $jasper->generarArchivo($datosReporte);

        $contenido = DOSS_PEC_URL_CERT . $idExpediente . "/" . "PuntosMinimos_" . $idExpediente . ".pdf";
        
        //Firma electrónica de documento creado
        $arrayDocumento = array(
            'archivo_entrada' => $contenido,
            'archivo_salida' => $contenido,
            'identificador' => $nombreCoordinador->current()->identificador,
            'razon_documento' => 'Certificado de Puntos Minimos de Producto',
            'tabla_origen' => 'g_dossier_pecuario_mvc.solicitud',
            'campo_origen' => 'ruta_puntos_minimos',
            'id_origen' => $idSolicitud,
            'estado' => 'Atendida',
            'proceso_firmado' => 'SI'
        );
        
        $this->lNegocioDocumentos->guardar($arrayDocumento);

        return $contenido;
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar solicitudes usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarSolicitudesFiltradas($arrayParametros)
    {
        $busqueda = '';

        if (isset($arrayParametros['id_expediente']) && ($arrayParametros['id_expediente'] != '')) {
            $busqueda .= " upper(s.id_expediente) = upper('" . $arrayParametros['id_expediente'] . "')";

            if ($arrayParametros['filtro'] == 'tecnico') {
                $busqueda .= " and s.estado_solicitud = '" . $arrayParametros['estado_solicitud'] . "'";
                $busqueda .= " and s.id_provincia_revision = '" . $arrayParametros['id_provincia_revision'] . "'";
                $busqueda .= " and s.identificador_tecnico = '" . $arrayParametros['identificador_tecnico'] . "'";
            }
        } else {
            if (isset($arrayParametros['estado_solicitud']) && ($arrayParametros['estado_solicitud'] != '')) {
                $busqueda .= " s.estado_solicitud = '" . $arrayParametros['estado_solicitud'] . "'";

                if (isset($arrayParametros['id_provincia_revision']) && ($arrayParametros['id_provincia_revision'] != '')) {
                    $busqueda .= " and s.id_provincia_revision = '" . $arrayParametros['id_provincia_revision'] . "'";
                }

                if (isset($arrayParametros['identificador_tecnico']) && ($arrayParametros['identificador_tecnico'] != '') && ($arrayParametros['identificador_tecnico'] != 'Seleccione....')) {
                    $busqueda .= " and s.identificador_tecnico = '" . $arrayParametros['identificador_tecnico'] . "'";
                }
            }
        }

        $consulta = "  SELECT
                        	*
                        FROM
                        	g_dossier_pecuario_mvc.solicitud s
                            INNER JOIN g_operadores.operadores o ON s.identificador = o.identificador
                            INNER JOIN g_catalogos.localizacion l ON l.id_localizacion = s.id_provincia_revision
                        WHERE
                            " . $busqueda . "
                        ORDER BY
                        	s.id_expediente ASC;";

        // echo $consulta;
        return $this->modeloSolicitud->ejecutarSqlNativo($consulta);
    }

    public function guardarNuevaSolicitud($datos)
    {
        $validacion = array(
            'bandera' => false,
            'estado' => "Fallo",
            'mensaje' => "Ocurrió un error al guardar la solicitud de dossier",
            'contenido' => null
        );

        $datos['identificador_titular'] = $datos['identificador'];
        $datos['id_expediente'] = $this->generarCodigoExpediente();

        if ($datos['codificacion_subtipo_producto'] == 'FM') {
            $datos['nombre_producto'] = $datos['id_expediente'];
            
            $validacion['estado'] = "exito";
            $validacion['mensaje'] = "Se ha generado la solicitud correctamente.";
            $validacion['bandera'] = true;
        } else {
            $producto = $this->buscarProductoDossierRIA($datos['nombre_producto']);

            if (! empty($producto->current())) {
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = "El nombre del producto elegido ya se encuentra registrado o en proceso de registro.";
                $validacion['bandera'] = false;
            } else {
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = "Se ha generado la solicitud correctamente.";
                $validacion['bandera'] = true;
            }
        }

        if ($validacion['bandera']) {
            $validacion['contenido'] = $this->guardar($datos);

            // Función de registro de histórico
            $this->lNegocioSecuenciaRevision->guardarHistoricoUsuario($validacion['contenido'], 'Creado');
        }

        return $validacion;
    }

    public function generarSolicitudReemplazo($registro)
    {
        $datos = array();

        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => "Solicitud guardada con éxito",
            'contenido' => null
        );

        $solicitud = $this->buscar($registro['idSolicitud']);

        $datos['id_expediente'] = $this->generarCodigoExpediente();
        $datos['codigo_producto_final'] = $solicitud->codigoProductoFinal;
        $datos['identificador'] = $_SESSION['usuario'];
        $datos['id_provincia_operador'] = $registro['id_provincia_operador'];
        $datos['tipo_solicitud'] = $registro['tipo_solicitud'];
        $datos['id_grupo_producto'] = $solicitud->idGrupoProducto;
        $datos['grupo_producto'] = $solicitud->grupoProducto;
        $datos['id_subtipo_producto'] = $solicitud->idSubtipoProducto;
        $datos['codificacion_subtipo_producto'] = $solicitud->codificacionSubtipoProducto;
        $datos['nombre_producto'] = $solicitud->nombreProducto;
        $datos['id_clasificacion'] = $solicitud->idClasificacion;
        $datos['linea_biologica'] = $solicitud->lineaBiologica;
        $datos['ph'] = $solicitud->ph;
        $datos['viscosidad'] = $solicitud->viscosidad;
        $datos['densidad'] = $solicitud->densidad;
        $datos['metodo_biologico'] = $solicitud->metodoBiologico;
        $datos['metodo_microbiologico'] = $solicitud->metodoMicrobiologico;
        $datos['control_inocuidad'] = $solicitud->controlInocuidad;
        $datos['agente_etiologico'] = $solicitud->agenteEtiologico;
        $datos['metodo_fabricacion'] = $solicitud->metodoFabricacion;
        $datos['caracteristicas_fis_quim_org'] = $solicitud->caracteristicasFisQuimOrg;
        $datos['caracteristicas_fis_quim'] = $solicitud->caracteristicasFisQuim;
        $datos['pruebas_biologicas'] = $solicitud->pruebasBiologicas;
        $datos['pruebas_microbiologicas'] = $solicitud->pruebasMicrobiologicas;
        $datos['metodo_bromatologico'] = $solicitud->metodoBromatologico;
        $datos['metodo_fis_quim'] = $solicitud->metodoFisQuim;
        $datos['esquema_vacunacion'] = $solicitud->esquemaVacunacion;
        $datos['tiempo_inmunidad'] = $solicitud->tiempoInmunidad;
        $datos['tiempo_minimo_inmunidad'] = $solicitud->tiempoMinimoInmunidad;
        $datos['requiere_preparacion'] = $solicitud->requierePreparacion;
        $datos['detalle_preparacion'] = $solicitud->detallePreparacion;
        $datos['duracion_maxima'] = $solicitud->duracionMaxima;
        $datos['id_tiempo_duracion_maxima'] = $solicitud->idTiempoDuracionMaxima;
        $datos['nombre_unidad_tiempo_duracion'] = $solicitud->nombreUnidadTiempoDuracion;
        $datos['duracion_maxima_reconstitucion'] = $solicitud->duracionMaximaReconstitucion;
        $datos['condiciones_almacenamiento_abierto'] = $solicitud->condicionesAlmacenamientoAbierto;
        $datos['farmacocinetica'] = $solicitud->farmacocinetica;
        $datos['farmacodinamica'] = $solicitud->farmacodinamica;
        $datos['efectos_colaterales'] = $solicitud->efectosColaterales;
        $datos['toxicidad'] = $solicitud->toxicidad;
        $datos['id_categoria_toxicologica'] = $solicitud->idCategoriaToxicologica;
        $datos['temperatura_almacenamiento'] = $solicitud->temperaturaAlmacenamiento;
        $datos['humedad_almacenamiento'] = $solicitud->humedadAlmacenamiento;
        $datos['recomendacion_conservacion'] = $solicitud->recomendacionConservacion;
        $datos['control_residuos'] = $solicitud->controlResiduos;
        $datos['principios_tecnica'] = $solicitud->principiosTecnica;
        $datos['deteccion_antigenos'] = $solicitud->deteccionAntigenos;
        $datos['muestras_usadas'] = $solicitud->muestrasUsadas;
        $datos['pruebas_fis_quim'] = $solicitud->pruebasFisQuim;
        $datos['inocuidad_esterilidad'] = $solicitud->inocuidadEsterilidad;
        $datos['sensibilidad'] = $solicitud->sensibilidad;
        $datos['especificidad'] = $solicitud->especificidad;
        $datos['datos_repetibilidad'] = $solicitud->datosRepetibilidad;
        $datos['datos_especificidad'] = $solicitud->datosEspecificidad;
        $datos['datos_sensibilidad'] = $solicitud->datosSensibilidad;
        $datos['determinacion_anticuerpos'] = $solicitud->determinacionAnticuerpos;
        $datos['determinacion_microorganismos'] = $solicitud->determinacionMicroorganismos;
        $datos['determinacion_estados_fisiologicos'] = $solicitud->determinacionEstadosFisiologicos;
        $datos['determinacion_datos_clinicos'] = $solicitud->determinacionDatosClinicos;
        $datos['modo_uso'] = $solicitud->modoUso;
        $datos['resultado_interpretacion'] = $solicitud->resultadoInterpretacion;
        $datos['precauciones_generales'] = $solicitud->precaucionesGenerales;
        $datos['variacion_calidad'] = $solicitud->variacionCalidad;
        $datos['id_declaracion_venta'] = $solicitud->idDeclaracionVenta;
        $datos['observaciones_producto'] = $solicitud->observacionesProducto;
        $datos['id_solicitud_original'] = $registro['idSolicitud'];
        $datos['identificador_titular'] = $solicitud->identificador;
        $datos['id_provincia_revision'] = $registro['id_provincia_operador'];

        // print_r($datos);

        $idSolicitud = $this->guardar($datos);

        if ($idSolicitud > 0) {
            $validacion['estado'] = "exito";
            $validacion['mensaje'] = "Se ha guardado la información de la tabla Solicitud. ";
            $validacion['bandera'] = true;
            $validacion['contenido'] = $idSolicitud;
        } else {
            $validacion['estado'] = "Fallo";
            $validacion['mensaje'] = "No se pudo guardar la información de la tabla Solicitud. ";
            $validacion['bandera'] = false;
            $validacion['contenido'] = null;
        }

        // Tablas detalle
        if ($validacion['bandera']) {

            // Tabla cantidad dosis
            $validacionCantDos = $this->lNegocioCantidadDosis->copiarRegistrosCantidadDosis($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionCantDos['estado'];
            $validacion['mensaje'] .= $validacionCantDos['mensaje'];
            $validacion['bandera'] = $validacionCantDos['bandera'];

            // Tabla composicion
            $validacionComp = $this->lNegocioComposicion->copiarRegistrosComposicion($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionComp['estado'];
            $validacion['mensaje'] .= $validacionComp['mensaje'];
            $validacion['bandera'] = $validacionComp['bandera'];

            // Tabla dosis via administracion
            $validacionDosViaAdm = $this->lNegocioDosisViaAdministracion->copiarRegistrosDosisViaAdministracion($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionDosViaAdm['estado'];
            $validacion['mensaje'] .= $validacionDosViaAdm['mensaje'];
            $validacion['bandera'] = $validacionDosViaAdm['bandera'];

            // Tabla efecto biológico
            $validacionEfectoBio = $this->lNegocioEfectoBiologico->copiarRegistrosEfectoBiologico($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionEfectoBio['estado'];
            $validacion['mensaje'] .= $validacionEfectoBio['mensaje'];
            $validacion['bandera'] = $validacionEfectoBio['bandera'];

            // Tabla especie_destino
            $validacionEspecieDestino = $this->lNegocioEspecieDestino->copiarRegistrosEspecieDestino($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionEspecieDestino['estado'];
            $validacion['mensaje'] .= $validacionEspecieDestino['mensaje'];
            $validacion['bandera'] = $validacionEspecieDestino['bandera'];

            // Tabla forma_administracion
            $validacionFormaAdministracion = $this->lNegocioFormaAdministracion->copiarRegistrosFormaAdministracion($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionFormaAdministracion['estado'];
            $validacion['mensaje'] .= $validacionFormaAdministracion['mensaje'];
            $validacion['bandera'] = $validacionFormaAdministracion['bandera'];

            // Tabla forma_administracion_alimento
            $validacionFormaAdministracionAlimento = $this->lNegocioFormaAdministracionAlimento->copiarRegistrosFormaAdministracionAlimento($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionFormaAdministracionAlimento['estado'];
            $validacion['mensaje'] .= $validacionFormaAdministracionAlimento['mensaje'];
            $validacion['bandera'] = $validacionFormaAdministracionAlimento['bandera'];

            // Tabla forma_aplicacion_instalaciones
            $validacionFormaAplicacionInstalaciones = $this->lNegocioFormaAplicacionInstalaciones->copiarRegistrosFormaAplicacionInstalaciones($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionFormaAplicacionInstalaciones['estado'];
            $validacion['mensaje'] .= $validacionFormaAplicacionInstalaciones['mensaje'];
            $validacion['bandera'] = $validacionFormaAplicacionInstalaciones['bandera'];

            // Tabla forma_fis_far_cos_producto
            $validacionFormaFisFarCosProducto = $this->lNegocioFormaFisFarCosProducto->copiarRegistrosFormaFisFarCosProducto($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionFormaFisFarCosProducto['estado'];
            $validacion['mensaje'] .= $validacionFormaFisFarCosProducto['mensaje'];
            $validacion['bandera'] = $validacionFormaFisFarCosProducto['bandera'];

            // Tabla origen_producto
            $validacionOrigenProducto = $this->lNegocioOrigenProducto->copiarRegistrosOrigenProducto($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionOrigenProducto['estado'];
            $validacion['mensaje'] .= $validacionOrigenProducto['mensaje'];
            $validacion['bandera'] = $validacionOrigenProducto['bandera'];

            // Tabla partida_codigos
            $validacionPartidaCodigos = $this->lNegocioPartidaCodigos->copiarRegistrosPartidaCodigos($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionPartidaCodigos['estado'];
            $validacion['mensaje'] .= $validacionPartidaCodigos['mensaje'];
            $validacion['bandera'] = $validacionPartidaCodigos['bandera'];

            // Tabla periodo_retiro
            $validacionPeriodoRetiro = $this->lNegocioPeriodoRetiro->copiarRegistrosPeriodoRetiro($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionPeriodoRetiro['estado'];
            $validacion['mensaje'] .= $validacionPeriodoRetiro['mensaje'];
            $validacion['bandera'] = $validacionPeriodoRetiro['bandera'];

            // Tabla periodo_vida_util
            $validacionPeriodoVidaUtil = $this->lNegocioPeriodoVidaUtil->copiarRegistrosPeriodoVidaUtil($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionPeriodoVidaUtil['estado'];
            $validacion['mensaje'] .= $validacionPeriodoVidaUtil['mensaje'];
            $validacion['bandera'] = $validacionPeriodoVidaUtil['bandera'];

            // Tabla presentacion_comercial
            $validacionPresentacionComercial = $this->lNegocioPresentacionComercial->copiarRegistrosPresentacionComercial($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionPresentacionComercial['estado'];
            $validacion['mensaje'] .= $validacionPresentacionComercial['mensaje'];
            $validacion['bandera'] = $validacionPresentacionComercial['bandera'];

            // Tabla reactivo_material
            $validacionReactivoMaterial = $this->lNegocioReactivoMaterial->copiarRegistrosReactivoMaterial($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionReactivoMaterial['estado'];
            $validacion['mensaje'] .= $validacionReactivoMaterial['mensaje'];
            $validacion['bandera'] = $validacionReactivoMaterial['bandera'];

            // Tabla tiempo_retiro
            $validacionTiempoRetiro = $this->lNegocioTiempoRetiro->copiarRegistrosTiempoRetiro($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionTiempoRetiro['estado'];
            $validacion['mensaje'] .= $validacionTiempoRetiro['mensaje'];
            $validacion['bandera'] = $validacionTiempoRetiro['bandera'];

            // Tabla uso_especie
            $validacionUsoEspecie = $this->lNegocioUsoEspecie->copiarRegistrosUsoEspecie($registro['idSolicitud'], $idSolicitud);

            $validacion['estado'] = $validacionUsoEspecie['estado'];
            $validacion['mensaje'] .= $validacionUsoEspecie['mensaje'];
            $validacion['bandera'] = $validacionUsoEspecie['bandera'];

            // Función de registro de histórico
            $this->lNegocioSecuenciaRevision->guardarHistoricoUsuario($idSolicitud, 'Creado');
        }

        return $validacion;
    }

    /**
     * **********
     */
    public function actualizarSolicitud($datos)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => "Solicitud guardada con éxito",
            'contenido' => null
        );

        if (isset($datos['estado_solicitud'])) {

            switch ($datos['estado_solicitud']) {
                // Estado actual que cambia
                case 'Creado':
                    {
                        $solicitud = $this->buscar($datos['id_solicitud']);
                        $datos['tipo_solicitud'] = $solicitud->getTipoSolicitud();
                        
                        if ($datos['tipo_solicitud'] == 'Registro' || $datos['tipo_solicitud'] == 'Reevaluacion' || $datos['tipo_solicitud'] == 'Modificacion') {//|| $datos['tipo_solicitud'] == 'Modificacion'
                            $datos['estado_solicitud'] = 'pago';
                        }/*else{//cambio temporal hasta que se ponga en vigencia el nuevo tarifario
                            $datos['estado_solicitud'] = 'Recibido';
                        }*/

                        // Función para revisión de Campos Obligatorios de grids y cabecera
                        $revision = $this->verificarSolicitudCompleta($datos['id_solicitud']);

                        if ($revision['error'] == true) { // Hay error, faltan campos/grids
                            $validacion['estado'] = "fallo";
                            $validacion['bandera'] = false;
                            $validacion['mensaje'] = "La solicitud está incompleta: " . $revision['mensaje'];
                        } else { // No hay error, esta completo
                                 // Función para creación de Documento Expediente de Registro de Producto
                            $ruta = $this->generarDocumentoExpediente($datos['id_solicitud']);
                            $datos['ruta_expediente'] = $ruta;

                            $validacion['bandera'] = true;

                            // Función de registro de histórico
                            $this->lNegocioSecuenciaRevision->guardarHistoricoUsuario($datos['id_solicitud'], $datos['estado_solicitud']);
                        }

                        break;
                    }

                // Estado actual que cambia
                case 'Recibido':
                    {
                        $datos['estado_solicitud'] = 'EnTramite';
                        $datos['identificador_revisor'] = $_SESSION['usuario'];

                        $validacion['bandera'] = true;

                        // Función de registro de histórico
                        $this->lNegocioSecuenciaRevision->guardarHistoricoAdministrador($datos['id_solicitud'], $datos['estado_solicitud'], $datos['identificador_tecnico']);

                        break;
                    }

                // Estado actual que cambia
                case 'EnTramite':
                    { // admin
                        $datos['identificador_revisor'] = $_SESSION['usuario'];

                        if ($datos['fase_revision'] == 'AsignarTecnico') {
                            $datos['estado_solicitud'] = 'EnTramite';

                            // Función de registro de histórico
                            $this->lNegocioSecuenciaRevision->guardarHistoricoAdministrador($datos['id_solicitud'], $datos['estado_solicitud'], $datos['identificador_tecnico']);
                        }

                        $validacion['bandera'] = true;

                        break;
                    }

                // Estado remitido
                case 'Aprobado':
                    {
                        $solicitud = $this->buscar($datos['id_solicitud']);

                        $datos['identificador_revisor'] = $_SESSION['usuario'];
                        $datos['fecha_revision'] = 'now()';
                        $datos['ruta_certificado'] = DOSS_PEC_URL . $solicitud->getIdExpediente() . "/" . "Certificado_" . $solicitud->getIdExpediente() . ".pdf";                        
                        $datos['ruta_puntos_minimos'] = DOSS_PEC_URL . $solicitud->getIdExpediente() . "/" . "PuntosMinimos_" . $solicitud->getIdExpediente() . ".pdf";
                        

                        if ($datos['tipo_solicitud'] == 'Registro') {
                            // Busca el código del subtipo de producto para los formatos
                            $codigoSubtipo = $solicitud->getCodificacionSubtipoProducto();

                            // Función para creación de número de registro único de Producto RIA
                            $numRegistro = $this->lNegocioProductosInocuidad->generarCodigoProducto($codigoSubtipo);
                            $datos['codigo_producto_final'] = $numRegistro;
                            
                            //Cambiar nombre de Fórmula Maestra
                            if($solicitud->getCodificacionSubtipoProducto() == 'FM'){
                                $datos['nombre_producto'] = $datos['codigo_producto_final'];
                            }
                        } else {
                            // Cambia de estado a la solicitud de origen
                            $idSolicitudOriginal = $solicitud->getIdSolicitudOriginal();

                            $arrayParametrosOriginal = array(
                                'id_solicitud' => $idSolicitudOriginal,
                                'estado_solicitud' => 'Modificado'
                            );

                            $this->guardar($arrayParametrosOriginal);

                            // Cambio de usuario al nuevo titular
                            if ($solicitud->getCambioTitular() == 'Si') {
                                $datos['identificador'] = $solicitud->getIdentificadorTitular();
                            }
                        }

                        // Función de registro de histórico
                        $this->lNegocioSecuenciaRevision->guardarHistoricoTecnico($datos['id_solicitud'], $datos['estado_solicitud'], $datos['observacion_revision']);

                        $validacion['bandera'] = true;

                        break;
                    }

                // Estado remitido
                case 'Rechazado':
                    {
                        $datos['identificador_revisor'] = $_SESSION['usuario'];
                        $datos['fecha_revision'] = 'now()';

                        // Función de registro de histórico
                        $this->lNegocioSecuenciaRevision->guardarHistoricoTecnico($datos['id_solicitud'], $datos['estado_solicitud'], $datos['observacion_revision']);

                        $validacion['bandera'] = true;

                        break;
                    }

                // Estado actual que cambia (usuario) y asignado (técnico)
                case 'Subsanacion':
                    { // Usuario y técnico
                        if ($datos['fase_revision'] == 'Operador') {
                            $datos['estado_solicitud'] = 'EnTramite';

                            // Función para revisión de Campos Obligatorios de grids y cabecera
                            $revision = $this->verificarSolicitudCompleta($datos['id_solicitud']);

                            if ($revision['error'] == true) { // Hay error, faltan campos/grids
                                $validacion['estado'] = "fallo";
                                $validacion['bandera'] = false;
                                $validacion['mensaje'] = "La solicitud está incompleta: " . $revision['mensaje'];
                            } else { // No hay error, esta completo
                                     // Función para creación de Documento Expediente de Registro de Producto
                                $ruta = $this->generarDocumentoExpediente($datos['id_solicitud']);
                                $datos['ruta_expediente'] = $ruta;

                                $validacion['bandera'] = true;

                                // Función de registro de histórico
                                $this->lNegocioSecuenciaRevision->guardarHistoricoUsuario($datos['id_solicitud'], $datos['estado_solicitud']);
                            }
                        } else {
                            $datos['identificador_revisor'] = $_SESSION['usuario'];
                            $datos['fecha_revision'] = 'now()';

                            // Función de registro de histórico
                            $this->lNegocioSecuenciaRevision->guardarHistoricoTecnico($datos['id_solicitud'], $datos['estado_solicitud'], $datos['observacion_revision']);

                            // Función de envío de correo
                            $this->enviarCorreo($datos['id_solicitud']);

                            $validacion['bandera'] = true;
                        }

                        break;
                    }

                default:
                    {
                        $validacion['bandera'] = false;
                        $validacion['estado'] = 'Fallo';
                        $validacion['mensaje'] = 'La opción seleccionada no es correcta';
                        break;
                    }
            }
        }

        if ($validacion['bandera']) {
            $validacion['contenido'] = $this->guardar($datos);

            if (isset($datos['estado_solicitud']) && $datos['estado_solicitud'] == 'Aprobado') {
                // Función para guardado de registro en modulo ria
                $validacion = $this->crearNuevoProductoRIA($datos['id_solicitud']);
                // Crear Certificado
                $ruta = $this->generarDocumentoCertificado($datos['id_solicitud']);
                // Crear Puntos Minimos
                $ruta = $this->generarDocumentoPuntosMinimos($datos['id_solicitud']);
                // Crear Expediente
                $ruta = $this->generarDocumentoExpediente($datos['id_solicitud']);
            }
        }

        return $validacion;
    }

    /**
     * Función para verificación de ingreso de campos y registros obligatorios para la solicitud
     * Envía True si se presenta un error
     */
    public function verificarSolicitudCompleta($idSolicitud)
    {
        // Sin error
        $error = false;
        $mensaje = '';

        $consulta = "id_solicitud = $idSolicitud";

        $registro = $this->buscarLista($consulta);
        $solicitud = $registro->current();

        // Información general de todas las solicitudes
        // Paso 1
        if ($solicitud->id_subtipo_producto == '') {
            $error = true;
            $mensaje .= 'Tipo producto, ';
        }

        if ($solicitud->nombre_producto == '') {
            $error = true;
            $mensaje .= 'Nombre producto, ';
        }

        // Paso 2
        $origen = $this->lNegocioOrigenProducto->obtenerNumeroRegistrosOrigenProducto($idSolicitud);

        if ($origen->current()->numero == 0) {
            $error = true;
            $mensaje .= 'Origen producto, ';
        }
        
        // Paso 4
        $composicion = $this->lNegocioComposicion->obtenerNumeroRegistrosComposicion($idSolicitud);
        
        if ($composicion->current()->numero == 0) {
            $error = true;
            $mensaje .= 'Composición, ';
        }

        switch ($solicitud->id_grupo_producto) {
            // Alimentos
            case '1':
                {
                    // Paso 3
                    if ($solicitud->id_clasificacion == '') {
                        $error = true;
                        $mensaje .= 'Clasificación, ';
                    }
                    
                    $presentacion = $this->lNegocioPresentacionComercial->obtenerNumeroRegistrosPresentacionComercial($idSolicitud);

                    if ($presentacion->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Presentación Comercial, ';
                    }

                    // Paso 5
                    $forma = $this->lNegocioFormaFisFarCosProducto->obtenerNumeroRegistrosFormaFisFarCosProducto($idSolicitud);

                    if ($forma->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Forma física, farmacéutica o cosmética, ';
                    }

                    // Paso 6
                    $usoEspecie = $this->lNegocioUsoEspecie->obtenerNumeroRegistrosUsoEspecie($idSolicitud);

                    if ($usoEspecie->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Especie de destino y usos, ';
                    }

                    // Paso 7
                    /*if ($solicitud->requiere_preparacion == '') {
                        $error = true;
                        $mensaje .= 'Requiere preparación, ';
                    } else if ($solicitud->id_clasificacion == 'Si') {
                        if ($solicitud->detalle_preparacion == '') {
                            $error = true;
                            $mensaje .= 'Detalle de preparación, ';
                        }
                    }*/
                    
                    // Paso 8
                    $vidaUtil = $this->lNegocioPeriodoVidaUtil->obtenerNumeroRegistrosPeriodVidaUtil($idSolicitud);
                    
                    if ($vidaUtil->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Período de vida útil, ';
                    }

                    // Paso 9
                    if ($solicitud->id_declaracion_venta == '') {
                        $error = true;
                        $mensaje .= 'Declaración de venta, ';
                    }

                    break;
                }

            // Biologicos
            case '2':
                {
                    // Paso 3
                    if ($solicitud->id_clasificacion == '') {
                        $error = true;
                        $mensaje .= 'Clasificación, ';
                    }
                    
                    $presentacion = $this->lNegocioPresentacionComercial->obtenerNumeroRegistrosPresentacionComercial($idSolicitud);

                    if ($presentacion->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Presentación Comercial, ';
                    }

                    // Paso 5
                    $forma = $this->lNegocioFormaFisFarCosProducto->obtenerNumeroRegistrosFormaFisFarCosProducto($idSolicitud);

                    if ($forma->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Forma física, farmacéutica o cosmética, ';
                    }

                    // Paso 6
                    $usoEspecie = $this->lNegocioUsoEspecie->obtenerNumeroRegistrosUsoEspecie($idSolicitud);

                    if ($usoEspecie->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Especie de destino y usos, ';
                    }

                    $dosisVia = $this->lNegocioDosisViaAdministracion->obtenerNumeroRegistrosDosisViaAdministracion($idSolicitud);

                    if ($dosisVia->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Dosis y vía de administración, ';
                    }

                    // Paso 7
                    /*if ($solicitud->requiere_preparacion == '') {
                        $error = true;
                        $mensaje .= 'Requiere preparación, ';
                    } else if ($solicitud->id_clasificacion == 'Si') {
                        if ($solicitud->detalle_preparacion == '') {
                            $error = true;
                            $mensaje .= 'Detalle de preparación, ';
                        }
                    }*/
                    
                    // Paso 8
                    $vidaUtil = $this->lNegocioPeriodoVidaUtil->obtenerNumeroRegistrosPeriodVidaUtil($idSolicitud);
                    
                    if ($vidaUtil->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Período de vida útil, ';
                    }

                    // Paso 9
                    if ($solicitud->id_declaracion_venta == '') {
                        $error = true;
                        $mensaje .= 'Declaración de venta, ';
                    }

                    break;
                }

            //Cosmeticos
            case '3':
                {
                    // Paso 3
                    if ($solicitud->id_clasificacion == '') {
                        $error = true;
                        $mensaje .= 'Clasificación, ';
                    }
                    
                    $presentacion = $this->lNegocioPresentacionComercial->obtenerNumeroRegistrosPresentacionComercial($idSolicitud);

                    if ($presentacion->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Presentación Comercial, ';
                    }

                    // Paso 5
                    $forma = $this->lNegocioFormaFisFarCosProducto->obtenerNumeroRegistrosFormaFisFarCosProducto($idSolicitud);

                    if ($forma->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Forma física, farmacéutica o cosmética, ';
                    }

                    // Paso 6
                    $usoEspecie = $this->lNegocioUsoEspecie->obtenerNumeroRegistrosUsoEspecie($idSolicitud);

                    if ($usoEspecie->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Especie de destino y usos, ';
                    }
                    
                    // Paso 8
                    $vidaUtil = $this->lNegocioPeriodoVidaUtil->obtenerNumeroRegistrosPeriodVidaUtil($idSolicitud);
                    
                    if ($vidaUtil->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Período de vida útil, ';
                    }

                    // Paso 9
                    if ($solicitud->id_declaracion_venta == '') {
                        $error = true;
                        $mensaje .= 'Declaración de venta, ';
                    }

                    break;
                }

            //Farmacologicos
            case '4':
                {
                    // Paso 3
                    if ($solicitud->id_clasificacion == '') {
                        $error = true;
                        $mensaje .= 'Clasificación, ';
                    }
                    
                    $presentacion = $this->lNegocioPresentacionComercial->obtenerNumeroRegistrosPresentacionComercial($idSolicitud);
                    
                    if ($presentacion->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Presentación Comercial, ';
                    }
                    
                    // Paso 5
                    $forma = $this->lNegocioFormaFisFarCosProducto->obtenerNumeroRegistrosFormaFisFarCosProducto($idSolicitud);
                    
                    if ($forma->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Forma física, farmacéutica o cosmética, ';
                    }
                    
                    // Paso 6
                    $usoEspecie = $this->lNegocioUsoEspecie->obtenerNumeroRegistrosUsoEspecie($idSolicitud);
                    
                    if ($usoEspecie->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Especie de destino y usos, ';
                    }
                    
                    $dosisVia = $this->lNegocioDosisViaAdministracion->obtenerNumeroRegistrosDosisViaAdministracion($idSolicitud);
                    
                    if ($dosisVia->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Dosis y vía de administración, ';
                    }
                    
                    // Paso 8
                    $vidaUtil = $this->lNegocioPeriodoVidaUtil->obtenerNumeroRegistrosPeriodVidaUtil($idSolicitud);
                    
                    if ($vidaUtil->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Período de vida útil, ';
                    }
                    
                    // Paso 9
                    if ($solicitud->id_declaracion_venta == '') {
                        $error = true;
                        $mensaje .= 'Declaración de venta, ';
                    }

                    break;
                }

            //Fórmula Maestra
            case '5':
                {
                    
                    break;
                }

            //Kits de Diagnóstico
            case '6':
                {
                    // Paso 3
                    if ($solicitud->id_clasificacion == '') {
                        $error = true;
                        $mensaje .= 'Clasificación, ';
                    }
                    
                    $presentacion = $this->lNegocioPresentacionComercial->obtenerNumeroRegistrosPresentacionComercial($idSolicitud);
                    
                    if ($presentacion->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Presentación Comercial, ';
                    }
                    
                    // Paso 5
                    $forma = $this->lNegocioFormaFisFarCosProducto->obtenerNumeroRegistrosFormaFisFarCosProducto($idSolicitud);
                    
                    if ($forma->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Forma física, farmacéutica o cosmética, ';
                    }
                    
                    // Paso 6
                    $usoEspecie = $this->lNegocioUsoEspecie->obtenerNumeroRegistrosUsoEspecie($idSolicitud);
                    
                    if ($usoEspecie->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Especie de destino y usos, ';
                    }
                    
                    // Paso 7
                    $vidaUtil = $this->lNegocioPeriodoVidaUtil->obtenerNumeroRegistrosPeriodVidaUtil($idSolicitud);
                    
                    if ($vidaUtil->current()->numero == 0) {
                        $error = true;
                        $mensaje .= 'Período de vida útil, ';
                    }
                    
                    // Paso 9
                    if ($solicitud->id_declaracion_venta == '') {
                        $error = true;
                        $mensaje .= 'Declaración de venta, ';
                    }

                    break;
                }

            default:
                {
                    break;
                }
        }

        return array(
            'error' => $error,
            'mensaje' => $mensaje
        );
    }

    /**
     * Función para enviar correo electrónico
     */
    public function enviarCorreo($idSolicitud)
    {
        $solicitud = $this->buscar($idSolicitud);
        $identificador = $solicitud->getIdentificador();

        $operador = $this->lNegocioOperadores->buscar($identificador);
        $correo = $operador->getCorreo();

        $arrayCorreo = array(
            'asunto' => 'Trámite de Dossier Pecuario observado',
            'cuerpo' => 'Agrocalidad informa que su trámite de Dossier Pecuario '. $solicitud->getNombreProducto() . ' - ' . $solicitud->getIdExpediente() .' ha sido observado y requiere de cambios en la información remitida. Por favor acceda a su cuenta en el Sistema GUIA, módulo Dossier Pecuario, para verificar el detalle de la revisión',
            'estado' => 'Por enviar',
            'codigo_modulo' => 'PRG_DOS_PEC_MVC',
            'tabla_modulo' => 'g_dossier_pecuario_mvc.solicitud',
            'id_solicitud_tabla' => $idSolicitud
        );

        $arrayDestinatario = array(
            $correo
        );

        return $this->lNegocioCorreos->crearCorreoElectronico($arrayCorreo, $arrayDestinatario);
    }

    /**
     * Función para verificación y guardado del nuevo producto en el Módulo Registro de Productos RIA
     * Envía True si se presenta un error
     */
    public function crearNuevoProductoRIA($idSolicitud)
    {
        $idProducto = null;

        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => "Se ha guardado el producto en el módulo RIA",
            'contenido' => null
        );

        $arrayProducto = null;
        $arrayProductoInocuidad = null;

        $consulta = "id_solicitud = $idSolicitud";

        $registro = $this->buscarLista($consulta);
        $solicitud = $registro->current();

        // Preparar información para guardar/reemplazar producto

        // Información genérica para todos los grupos de productos:
        // -Partidas Arancelarias
        // -Declaración de Venta
        // -Forma física, farmacéutica, cosmética

        // Verificar declaración de venta y obtener el nombre
        // Para fórmula maestra es 0 para el elemento Prohibida su venta en almacenes de expendio
        if ($solicitud->codificacion_subtipo_producto != 'FM') {
            $idDeclaracionVenta = $solicitud->id_declaracion_venta;
            
            $declaracion = $this->lNegocioDeclaracionVenta->buscar($idDeclaracionVenta);
            
            if (! empty($declaracion)) {
                $declaracionVenta = $declaracion->declaracionVenta;
            } else {
                $declaracionVenta = "NA";
            }
        } else {
            $query = "declaracion_venta = 'Prohibida su venta en almacenes de expendio' LIMIT 1";
            $declaracion = $this->lNegocioDeclaracionVenta->buscarLista($query);
            
            if (isset($declaracion->current()->id_declaracion_venta)) {
                $idDeclaracionVenta = $declaracion->current()->id_declaracion_venta;
                $declaracionVenta = $declaracion->current()->declaracion_venta;
            } else {
                $idDeclaracionVenta = 0;
                $declaracionVenta = "NA";
            }
        }

        

        // Verificar partida arancelaria y generar código de producto
        $partidaCodigo = $this->lNegocioPartidaCodigos->obtenerRegistrosPartidaCodigosRIA($idSolicitud);
        
        // Forma física, farmacéutica, cosmética -> Formulación
        // Para fórmula maestra guardar por defecto 0 Sin formulación
        $formaFisFarCos = $this->lNegocioFormaFisFarCosProducto->obtenerRegistrosFormaFisFarCosRIA($idSolicitud, $solicitud->id_grupo_producto);

        // Período de Vida Útil
        // Todos los productos menos fórmula maestra
        $periodoVidaUtil = $this->lNegocioPeriodoVidaUtil->obtenerRegistrosPeriodoVidaUtilRIA($idSolicitud, $solicitud->id_grupo_producto);
        

        // Información específica por cada grupo de producto
        switch ($solicitud->id_grupo_producto) {
            // Alimentos
            case '1':
                {
                    // Forma de Administración en Animales (tabla forma Administración)
                    $formaAdministracion = $this->lNegocioFormaAdministracion->obtenerRegistrosFormaAdministracionRIA($idSolicitud);

                    
                    // Forma de Administración en Alimento (tabla forma Administración Alimento)
                    $formAdminAlim = $this->lNegocioFormaAdministracionAlimento->obtenerRegistrosFormaAdministracionAlimentoRIA($idSolicitud);

                    $dosisTotal = "Especie: " . $formaAdministracion['especie'] . ", Nombre especie: " . $formaAdministracion['nombreEspecie'] . 
                                  ", Carac. Animal: " . $formaAdministracion['caracteristicasAnimal'] . ", Cant. Prod: " . $formaAdministracion['cantidadProducto'] . 
                                  ", Dosis: " . $formAdminAlim['dosisAlimento'] . ", Forma Admin: " . $formAdminAlim['formaAdministracion'];

                    $dosis = substr($dosisTotal, 0, 2048);

                    
                    // Categoría Toxicológica
                    $categoriaToxicologica = $this->lNegocioCategoriaToxicologica->obtenerRegistrosCategoriaToxicologicaRIA($solicitud->id_grupo_producto);
                    

                    // Período de retiro (tabla Periodo Retiro)
                    $perRetiro = $this->lNegocioPeriodoRetiro->obtenerRegistrosPeriodoRetiroRIA($idSolicitud);

                    $periodoRetiroTotal =   "Especie: " . $perRetiro['especie'] . ", Nombre especie: " . $perRetiro['nombreEspecie'] . 
                                            ", Producto consumo: " . $perRetiro['productoConsumo'] . 
                                            ", Tiempo retiro: " . $perRetiro['tiempoRetiro'] . " " . $perRetiro['nombreUnidadTiempo'];

                    $periodoRetiro = substr($periodoRetiroTotal, 0, 1024);

                    break;
                }
            // Biologicos
            case '2':
                {
                    // Dosis y vías de administración (tabla Dosis Vía Administración)
                    $dosisViaAdministracion = $this->lNegocioDosisViaAdministracion->obtenerRegistrosDosisViaAdministracionRIA($idSolicitud);
                    
                    $dosisTotal = "Especie: " . $dosisViaAdministracion['especie'] . ", Nombre especie: " . $dosisViaAdministracion['nombreEspecie'] . 
                    ", Carac. Animal: " . $dosisViaAdministracion['caracteristicasAnimal'] . ", Cant. Dosis: " . $dosisViaAdministracion['cantidadDosis'] .
                    " " . $dosisViaAdministracion['nombreUnidadDosis'] . ", Por: " . $dosisViaAdministracion['cantidad'] . " " . $dosisViaAdministracion['nombreUnidad'] . 
                    " Cada: " . $dosisViaAdministracion['duracion'] . " " . $dosisViaAdministracion['nombreUnidadTiempo'] . ", Vía Admin: " . $dosisViaAdministracion['viaAdministracion'];

                    $dosis = substr($dosisTotal, 0, 2048);
                    
                    // Categoría Toxicológica
                    $categoriaToxicologica = $this->lNegocioCategoriaToxicologica->obtenerRegistrosCategoriaToxicologicaRIA($solicitud->id_grupo_producto);
                    
                    $periodoRetiro = "No Aplica";

                    break;
                }
                
            // Cosmeticos
            case '3':
                {
                    // Forma de Administración en Animales (tabla forma Administración)
                    $formaAdministracion = $this->lNegocioFormaAdministracion->obtenerRegistrosFormaAdministracionRIA($idSolicitud);

                    // Forma de Administración en Instalaciones (tabla Forma Aplicacion Instalaciones)
                    $formaAplicacionInst = $this->lNegocioFormaAplicacionInstalaciones->obtenerRegistrosFormaAplicacionInstalacionesRIA($idSolicitud);
                    

                    $dosisTotal = "Especie: " . $formaAdministracion['especie'] . ", Nombre especie: " . $formaAdministracion['nombreEspecie'] . 
                    ", Carac. Animal: " . $formaAdministracion['caracteristicasAnimal'] . ", Cant. Prod: " . $formaAdministracion['cantidadProducto'] . 
                    ", Dosis: " . $formaAplicacionInst['dosisInst'] . ", Forma Admin: " . $formaAplicacionInst['formaAdministracion'];

                    $dosis = substr($dosisTotal, 0, 2048);

                    // Categoría Toxicológica
                    $categoriaToxicologica = $this->lNegocioCategoriaToxicologica->obtenerRegistrosCategoriaToxicologicaRIA($solicitud->id_grupo_producto, $solicitud->id_categoria_toxicologica);
                    
                    
                    $periodoRetiro = "No Aplica";

                    break;
                }

            // Farmacologicos
            case '4':
                {
                    // Dosis y vías de administración (tabla Dosis Vía Administración)
                    $dosisViaAdministracion = $this->lNegocioDosisViaAdministracion->obtenerRegistrosDosisViaAdministracionRIA($idSolicitud);
                    
                    $dosisTotal = "Especie: " . $dosisViaAdministracion['especie'] . ", Nombre especie: " . $dosisViaAdministracion['nombreEspecie'] . 
                    ", Carac. Animal: " . $dosisViaAdministracion['caracteristicasAnimal'] . ", Cant. Dosis: " . $dosisViaAdministracion['cantidadDosis'] . 
                    " " . $dosisViaAdministracion['nombreUnidadDosis'] . ", Por: " . $dosisViaAdministracion['cantidad'] . " " . $dosisViaAdministracion['nombreUnidad'] . 
                    " Cada: " . $dosisViaAdministracion['duracion'] . " " . $dosisViaAdministracion['nombreUnidadTiempo'] . ", Vía Admin: " . $dosisViaAdministracion['viaAdministracion'];

                    $dosis = substr($dosisTotal, 0, 2048);

                    // Categoría Toxicológica
                    $categoriaToxicologica = $this->lNegocioCategoriaToxicologica->obtenerRegistrosCategoriaToxicologicaRIA($solicitud->id_grupo_producto, $solicitud->id_categoria_toxicologica);
                    

                    // Período de retiro (tabla Periodo Retiro)
                    $perRetiro = $this->lNegocioPeriodoRetiro->obtenerRegistrosPeriodoRetiroRIA($idSolicitud);
                    
                    $periodoRetiroTotal = "Especie: " . $perRetiro['especie'] . ", Nombre especie: " . $perRetiro['nombreEspecie'] . 
                    ", Producto consumo: " . $perRetiro['productoConsumo'] . ", Tiempo retiro: " . $perRetiro['tiempoRetiro'] . 
                    " " . $perRetiro['nombreUnidadTiempo'];

                    $periodoRetiro = substr($periodoRetiroTotal, 0, 1024);

                    break;
                }
            // Formulas Maestras
            case '5':
                {
                    // Dosis
                    $dosis = "No Aplica";

                    // Categoría Toxicológica
                    $categoriaToxicologica = $this->lNegocioCategoriaToxicologica->obtenerRegistrosCategoriaToxicologicaRIA($solicitud->id_grupo_producto);
                    

                    // Período de retiro (tabla Tiempo Retiro)
                    $perRetiro = $this->lNegocioTiempoRetiro->obtenerRegistrosTiempoRetiroRIA($solicitud->id_grupo_producto);
                    

                    $periodoRetiroTotal = "Ingrediente activo: " . $perRetiro['ingredienteActivo'] . ", Producto consumo: " . $perRetiro['productoConsumo'] . 
                    ", Tiempo retiro: " . $perRetiro['tiempoRetiro'] . " " . $perRetiro['nombreUnidadTiempo'];

                    $periodoRetiro = substr($periodoRetiroTotal, 0, 1024);

                    break;
                }
            // Kits de Diagnostico
            case '6':
                {
                    // Dosis
                    $dosis = substr($solicitud->modo_uso, 0, 2048);

                    // Categoría Toxicológica
                    $categoriaToxicologica = $this->lNegocioCategoriaToxicologica->obtenerRegistrosCategoriaToxicologicaRIA($solicitud->id_grupo_producto);
                    
                    // Período de Retiro
                    $periodoRetiro = "No Aplica";

                    break;
                }

            default:
                {
                    // Dosis
                    $dosis = "No Aplica";
                    
                    // Período de Retiro
                    $periodoRetiro = "No Aplica";
                    
                    break;
                }
        }

        if ($solicitud->codificacion_subtipo_producto != 'FM') {
            $nombreProducto = $solicitud->nombre_producto;
        } else {
            $nombreProducto = $solicitud->codigo_producto_final;
        }
        
        // Tabla productos
        $arrayProducto = array(
            'id_subtipo_producto' => $solicitud->id_subtipo_producto,
            'nombre_comun' => $nombreProducto,
            'partida_arancelaria' => $partidaCodigo['partida'],
            'codigo_producto' => $partidaCodigo['codigoProducto'],
            'estado' => 1,
            'ruta' => $solicitud->ruta_puntos_minimos,
            'programa' => 'NO',
            'trazabilidad' => 'NO',
            'movilizacion' => 'NO',
            'id_dossier_pecuario' => $solicitud->id_solicitud
        );

        $arrayProductoInocuidad = array(
            'numero_registro' => $solicitud->codigo_producto_final,
            'id_declaracion_venta' => $idDeclaracionVenta,
            'declaracion_venta' => $declaracionVenta,
            'id_operador' => $solicitud->identificador,
            'id_formulacion' => $formaFisFarCos['idFormulacion'],
            'formulacion' => $formaFisFarCos['formulacion'],
            'periodo_reingreso' => $periodoVidaUtil['periodoVidaUtil'],
            'dosis' => $dosis,
            'id_categoria_toxicologica' => $categoriaToxicologica['idCategoriaToxicologica'],
            'categoria_toxicologica' => $categoriaToxicologica['categoriaToxicologica'],
            'periodo_carencia_retiro' => $periodoRetiro
        );

        if ($solicitud->tipo_solicitud == 'Registro') {
            $arrayProductoInocuidad['fecha_registro'] = 'now()';//$solicitud->fecha_revision;
            $arrayProducto['identificador_creacion'] = $solicitud->identificador_tecnico;

            // Validar nombre de producto
            $nombre = $this->lNegocioProductos->buscarProductoRegistroProductoRIA($solicitud->nombre_producto);

            if (! empty($nombre->current())) {
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = "El nombre del producto elegido ya se encuentra registrado.";
                $validacion['bandera'] = false;

                $idProducto = null;
            } else {
                $idProducto = $this->lNegocioProductos->guardar($arrayProducto);
            }
        } else { // Modificación, Reevaluación
            $arrayProducto['fecha_modificacion'] = $solicitud->fecha_revision;
            $arrayProducto['identificador_modificacion'] = $solicitud->identificador_tecnico;

            if ($solicitud->tipo_solicitud == 'Reevaluacion') {
                $arrayProductoInocuidad['fecha_revaluacion'] = $solicitud->fecha_revision;
            } else { // Modificacion
                $arrayProductoInocuidad['fecha_modificacion'] = $solicitud->fecha_revision;
                $arrayProductoInocuidad['observacion'] = "Fecha de última modificación: " . date('j/n/Y',strtotime($solicitud->fecha_revision));
            }

            // Buscar la solicitud que se va a reemplazar y cambiar de estado más adelante
            // Buscar el id del producto en el módulo de RIA
            $query = "numero_registro = '$solicitud->codigo_producto_final' LIMIT 1";
            $productoInocuidad = $this->lNegocioProductosInocuidad->buscarLista($query);

            if (isset($productoInocuidad->current()->id_producto)) {
                $idProducto = $productoInocuidad->current()->id_producto;

                $arrayProducto['id_producto'] = $idProducto;

                $this->lNegocioProductos->guardar($arrayProducto);

                // Eliminar información de tablas anexas del producto RIA
                $this->lNegocioComposicionInocuidad->borrarTodo($idProducto);
                $this->lNegocioCodigosInocuidad->borrarTodo($idProducto);
                $this->lNegocioCodigosAdicionalesPartidas->borrarTodo($idProducto);
                $this->lNegocioFabricanteFormulador->borrarTodo($idProducto);
                $this->lNegocioProductoInocuidadUso->borrarTodo($idProducto);
            } else {
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = "El código de identificación del producto es incorrecto o no existe.";
                $validacion['bandera'] = false;

                $idProducto = null;
            }
        }

        if ($idProducto != null) {
            // Tabla productos inocuidad
            $arrayProductoInocuidad['id_producto'] = $idProducto;

            // $idProductoInocuidad = $this->lNegocioProductosInocuidad->guardar($arrayProductoInocuidad);
            $idProductoInocuidad = $this->lNegocioProductosInocuidad->guardarProductoRIA($arrayProductoInocuidad, $solicitud->tipo_solicitud);

            if ($idProductoInocuidad > 0) {
                $validacion['estado'] = "exito";
                $validacion['mensaje'] .= " Se ha guardado la información de la tabla Productos Inocuidad. ";
                $validacion['bandera'] = true;
            } else {
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] .= " No se pudo guardar la información de la tabla Productos Inocuidad. ";
                $validacion['bandera'] = false;
            }

            // echo 'composicion';

            // Composición del producto (tabla composicion)
            $query = "id_solicitud = $idSolicitud ORDER BY 1";
            $comp = $this->lNegocioComposicion->buscarLista($query);

            if (! empty($comp)) {
                foreach ($comp as $composicion) {
                    $idTipoComponente = $composicion->id_tipo_componente;
                    $idNombreComponente = $composicion->id_nombre_componente;
                    $cantidad = $composicion->cantidad;
                    $nombreUnidad = $composicion->nombre_unidad;
                    $nombreUnidadComponente = $composicion->nombre_unidad_componente;

                    $tipo = $this->lNegocioTipoComponente->buscar($idTipoComponente);

                    if (! empty($tipo)) {
                        $tipoComponente = $tipo->tipoComponente;
                    } else {
                        $tipoComponente = "NA";
                    }

                    $ingred = $this->lNegocioIngredienteActivoInocuidad->buscar($idNombreComponente);

                    if (! empty($ingred)) {
                        $nombreComponente = $ingred->ingredienteActivo;
                    } else {
                        $nombreComponente = "NA";
                    }

                    // Tabla composicion inocuidad
                    $arrayComposicion = array(
                        'id_producto' => $idProducto, // null,//
                        'id_tipo_componente' => $idTipoComponente,
                        'tipo_componente' => $tipoComponente,
                        'id_ingrediente_activo' => $idNombreComponente,
                        'ingrediente_activo' => $nombreComponente,
                        'concentracion' => $cantidad,
                        'unidad_medida' => $nombreUnidadComponente,
                        'nombre_unidad_medida' => $nombreUnidadComponente
                    );

                    // print_r($arrayComposicion);

                    $idComposicion = $this->lNegocioComposicionInocuidad->guardar($arrayComposicion);

                    if ($idComposicion > 0) {
                        $validacion['estado'] = "exito";
                        $validacion['mensaje'] .= " Se ha guardado la información de la tabla Composición Inocuidad. ";
                        $validacion['bandera'] = true;
                    } else {
                        $validacion['estado'] = "Fallo";
                        $validacion['mensaje'] .= " No se pudo guardar la información de la tabla Composición Inocuidad. ";
                        $validacion['bandera'] = false;
                    }
                }
            }

            // echo 'presentacion';

            // Presentación del producto (tabla presentacion comercial)
            $query = "id_solicitud = $idSolicitud ORDER BY 1";
            $pres = $this->lNegocioPresentacionComercial->buscarLista($query);

            if (! empty($pres)) {
                foreach ($pres as $presentacionComercial) {
                    $subcodigoProducto = $presentacionComercial->subcodigo_producto;
                    $presentacion = $presentacionComercial->presentacion;
                    $cantidad = $presentacionComercial->cantidad;
                    $nombreUnidad = $presentacionComercial->nombre_unidad;

                    // Tabla codigos inocuidad
                    $arrayPresentacion = array(
                        'id_producto' => $idProducto, // null,//
                        'subcodigo' => $subcodigoProducto,
                        'presentacion' => $presentacion . ", Cant: " . $cantidad,
                        'unidad_medida' => substr($nombreUnidad, 0, 32),
                        'nombre_unidad_medida' => $nombreUnidad
                    );

                    // print_r($arrayPresentacion);

                    $idPresentacion = $this->lNegocioCodigosInocuidad->guardarProductoRIA($arrayPresentacion);

                    if ($idPresentacion > 0) {
                        $validacion['estado'] = "exito";
                        $validacion['mensaje'] .= " Se ha guardado la información de la tabla Códigos Inocuidad - Presentación. ";
                        $validacion['bandera'] = true;
                    } else {
                        $validacion['estado'] = "Fallo";
                        $validacion['mensaje'] .= " No se pudo guardar la información de la tabla Códigos Inocuidad - Presentación. ";
                        $validacion['bandera'] = false;
                    }
                }
            }

            // Códigos complementarios y suplementarios del producto (tabla codigos adicionales partidas)
            $query = "id_solicitud = $idSolicitud ORDER BY 1";
            $codCompSup = $this->lNegocioPartidaCodigos->buscarLista($query);

            if (! empty($codCompSup)) {
                foreach ($codCompSup as $codPartCompSup) {
                    $idCodigoComplementario = $codPartCompSup->id_codigo_complementario;
                    $codigoComplementario = $codPartCompSup->codigo_complementario;
                    $idCodigoSuplementario = $codPartCompSup->id_codigo_suplementario;
                    $codigoSuplementario = $codPartCompSup->codigo_suplementario;

                    // Tabla codigos inocuidad
                    $arrayCodCompSupl = array(
                        'id_producto' => $idProducto, // null,//
                        'id_codigo_complementario' => $idCodigoComplementario,
                        'codigo_complementario' => $codigoComplementario,
                        'id_codigo_suplementario' => $idCodigoSuplementario,
                        'codigo_suplementario' => $codigoSuplementario
                    );

                    // print_r($arrayCodCompSupl);

                    $idCodCompSup = $this->lNegocioCodigosAdicionalesPartidas->guardarProductoRIA($arrayCodCompSupl);

                    if ($idCodCompSup > 0) {
                        $validacion['estado'] = "exito";
                        $validacion['mensaje'] .= " Se ha guardado la información de la tabla Códigos Adicionales - Partidas. ";
                        $validacion['bandera'] = true;
                    } else {
                        $validacion['estado'] = "Fallo";
                        $validacion['mensaje'] .= " No se pudo guardar la información de la tabla Códigos Adicionales - Partidas. ";
                        $validacion['bandera'] = false;
                    }
                }
            }

            // Fabricante y formulador del producto (tabla fabricante formulador)
            $query = "id_solicitud = $idSolicitud ORDER BY 1";
            $origen = $this->lNegocioOrigenProducto->buscarLista($query);

            if (! empty($origen)) {
                foreach ($origen as $origenProducto) {
                    $nombre = $origenProducto->nombre_fabricante;
                    $tipo = $origenProducto->origen_fabricacion;
                    $idPaisOrigen = $origenProducto->id_pais;
                    $paisOrigen = $origenProducto->pais;

                    // Tabla fabricante formulador
                    $arrayFabricanteFormulador = array(
                        'id_producto' => $idProducto, // null,//
                        'nombre' => $nombre,
                        'tipo' => $tipo,
                        'id_pais_origen' => $idPaisOrigen,
                        'pais_origen' => $paisOrigen
                    );

                    // print_r($arrayFabricanteFormulador);

                    $idFabForm = $this->lNegocioFabricanteFormulador->guardar($arrayFabricanteFormulador);

                    if ($idFabForm > 0) {
                        $validacion['estado'] = "exito";
                        $validacion['mensaje'] .= " Se ha guardado la información de la tabla Fabricante Formulador. ";
                        $validacion['bandera'] = true;
                    } else {
                        $validacion['estado'] = "Fallo";
                        $validacion['mensaje'] .= " No se pudo guardar la información de la tabla Fabricante Formulador. ";
                        $validacion['bandera'] = false;
                    }
                }
            }

            //Esta hay que cambiar por los nuevos datos de instalaciones y de especies
            // Uso autorizado del producto (tabla producto inocuidad uso)
            
            if($solicitud->codificacion_subtipo_producto == 'FM'){
                $query = "id_solicitud = $idSolicitud ORDER BY 1";
                $usos = $this->lNegocioEspecieDestino->buscarLista($query);
    
                // Uso para Fórmula Maestra
                $query1 = "upper(nombre_uso) = upper('Fórmula Maestra') ORDER BY 1 LIMIT 1";
                $catUso = $this->lNegocioUsos->buscarLista($query1);
    
                if (isset($catUso->current()->id_uso)) {
                    $idUso = $catUso->current()->id_uso;
                } else {
                    $idUso = 0;
                }
    
                if (! empty($usos)) {
                    foreach ($usos as $usoEspecie) {
                        $idEspecie = $usoEspecie->id_especie;
                        $nombreEspecie = $usoEspecie->nombre_especie;
    
                        // Tabla producto inocuidad uso
                        $arrayUsoEspecie = array(
                            'id_producto' => $idProducto, // null,//
                            'id_uso' => $idUso,
                            'id_especie' => $idEspecie,
                            'nombre_especie' => $nombreEspecie,
                            'aplicado_a' => 'Especie'
                        );
    
                        $idUsoEspecie = $this->lNegocioProductoInocuidadUso->guardar($arrayUsoEspecie);
    
                        if ($idUsoEspecie > 0) {
                            $validacion['estado'] = "exito";
                            $validacion['mensaje'] .= " Se ha guardado la información de la tabla Inocuidad Uso - Uso Especie. ";
                            $validacion['bandera'] = true;
                        } else {
                            $validacion['estado'] = "Fallo";
                            $validacion['mensaje'] .= " No se pudo guardar la información de la tabla Inocuidad Uso - Uso Especie. ";
                            $validacion['bandera'] = false;
                        }
                    }
                }
            
            }else{
                // Uso autorizado del producto (tabla producto inocuidad uso)
                $query = "id_solicitud = $idSolicitud ORDER BY 1";
                $usos = $this->lNegocioUsoEspecie->buscarLista($query);
                
                if (! empty($usos)) {
                    foreach ($usos as $usoEspecie) {
                        $idUso = $usoEspecie->id_uso;
                        $idEspecie = $usoEspecie->id_especie;
                        $nombreEspecie = $usoEspecie->nombre_especie;
                        
                        // Tabla producto inocuidad uso
                        $arrayUsoEspecie = array(
                            'id_producto' => $idProducto, // null,//
                            'id_uso' => $idUso,
                            'id_especie' => $idEspecie,
                            'nombre_especie' => $nombreEspecie,
                            'aplicado_a' => 'Especie'
                        );
                        
                        $idUsoEspecie = $this->lNegocioProductoInocuidadUso->guardar($arrayUsoEspecie);
                        
                        if ($idUsoEspecie > 0) {
                            $validacion['estado'] = "exito";
                            $validacion['mensaje'] .= " Se ha guardado la información de la tabla Inocuidad Uso - Uso Especie. ";
                            $validacion['bandera'] = true;
                        } else {
                            $validacion['estado'] = "Fallo";
                            $validacion['mensaje'] .= " No se pudo guardar la información de la tabla Inocuidad Uso - Uso Especie. ";
                            $validacion['bandera'] = false;
                        }
                    }
                }
            }

            if ($validacion['estado'] == "exito") {
                $validacion['mensaje'] = "Se ha guardado el producto en el Módulo RIA exitosamente.";
                $validacion['bandera'] = true;
            }
        } else {
            $validacion['estado'] = "Fallo";
            $validacion['mensaje'] .= " Ha ocurrido un error al guardar el detalle del producto.";
            $validacion['bandera'] = false;
        }

        return $validacion;
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar solicitudes junto con su secuencia de revisión completa.
     *
     * @return array|ResultSet
     */
    public function buscarSolicitudesSecuenciaRevisionXFiltro($arrayParametros)
    {
        $busqueda = '';

        if (isset($arrayParametros['id_expediente']) && ($arrayParametros['id_expediente'] != '') && ($arrayParametros['id_expediente'] != 'Todas')) {
            $busqueda .= " and s.id_expediente = '" . $arrayParametros['id_expediente'] . "'";
        }

        if (isset($arrayParametros['identificador']) && ($arrayParametros['identificador'] != '') && ($arrayParametros['identificador'] != 'Seleccione....')) {
            $busqueda .= " and s.identificador = '" . $arrayParametros['identificador'] . "'";
        }

        $consulta = " SELECT
                            s.id_solicitud,
                        	s.id_expediente,
                            s.codigo_producto_final,
                        	s.tipo_solicitud,
                        	s.identificador,
                        	o.razon_social,
                        	sp.nombre as subtipo_producto,
                        	s.nombre_producto,
                        	s.fecha_creacion,
                        	sr.identificador_ejecutor,
                        	sr.nombre_ejecutor,
                        	sr.perfil,
                        	sr.provincia,
                        	sr.fecha_creacion fecha_despacho,
                        	sr.estado_revision,
                        	sr.comentario_revision,
                        	sr.nombre_tecnico_asignado,
                            sr.accion
                        FROM
                        	g_dossier_pecuario_mvc.solicitud s
                        	INNER JOIN g_dossier_pecuario_mvc.secuencia_revision sr ON s.id_solicitud = sr.id_solicitud
                            INNER JOIN g_operadores.operadores o ON s.identificador = o.identificador
                            INNER JOIN g_catalogos.subtipo_productos sp ON s.id_subtipo_producto = sp.id_subtipo_producto
                        WHERE
                            s.fecha_creacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' and
	                        s.fecha_creacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00'
                            " . $busqueda . "
                        ORDER BY
                            s.id_expediente, sr.id_secuencia_revision ASC;";

        // echo $consulta;

        return $this->modeloSolicitud->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los productos que pueden ser modificados
     *
     * @return array|ResultSet
     */
    public function buscarProductosModificables($identificador)
    {
        $consulta = "SELECT 
                        s.id_solicitud, 
                        s.nombre_producto
                     FROM 
                        g_dossier_pecuario_mvc.solicitud s
                     WHERE
                        s.identificador='$identificador' 
                        and s.estado_solicitud='Aprobado'
                        and s.id_solicitud not in 
                        (   SELECT 
                                ss.id_solicitud_original
                            FROM 
                                g_dossier_pecuario_mvc.solicitud ss
                            WHERE
                                ss.identificador='$identificador' 
                                and ss.estado_solicitud not in ('Aprobado', 'Eliminado', 'Rechazado')
                                and ss.tipo_solicitud in ('Modificacion', 'Reevaluacion')
                        );";

        return $this->modeloSolicitud->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta un reporte en Excel de las movilizaciones
     *
     * @return array|ResultSet
     */
    public function exportarArchivoExcelSolicitudesSecuenciaRevision($datos)
    {
        $hoja = new Spreadsheet();
        $documento = $hoja->getActiveSheet();
        $i = 3;
        $j = 2;

        $documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Solicitudes generadas Dossier Pecuario');

        $documento->setCellValueByColumnAndRow(1, $j, 'Nº de Solicitud');
        $documento->setCellValueByColumnAndRow(2, $j, 'Nº Expediente');
        $documento->setCellValueByColumnAndRow(3, $j, 'Nº Registro');
        $documento->setCellValueByColumnAndRow(4, $j, 'Tipo Solicitud');
        $documento->setCellValueByColumnAndRow(5, $j, 'Identificador Operador');
        $documento->setCellValueByColumnAndRow(6, $j, 'Razón Social Operador');
        $documento->setCellValueByColumnAndRow(7, $j, 'Subtipo de Producto');
        $documento->setCellValueByColumnAndRow(8, $j, 'Nombre del Producto');
        $documento->setCellValueByColumnAndRow(9, $j, 'Fecha de creación de la solicitud');
        $documento->setCellValueByColumnAndRow(10, $j, 'Identificador ejecutor');
        $documento->setCellValueByColumnAndRow(11, $j, 'Nombre Ejecutor');
        $documento->setCellValueByColumnAndRow(12, $j, 'Perfil');
        $documento->setCellValueByColumnAndRow(13, $j, 'Provincia');
        $documento->setCellValueByColumnAndRow(14, $j, 'Fecha de Despacho');
        $documento->setCellValueByColumnAndRow(15, $j, 'Estado');
        $documento->setCellValueByColumnAndRow(16, $j, 'Comentario de la revisión');
        $documento->setCellValueByColumnAndRow(17, $j, 'Técnico asignado Registros');
        $documento->setCellValueByColumnAndRow(18, $j, 'Acción');

        if ($datos != '') {
            foreach ($datos as $fila) {
                $documento->setCellValueByColumnAndRow(1, $i, $fila['id_solicitud']);
                $documento->setCellValueByColumnAndRow(2, $i, $fila['id_expediente']);
                $documento->setCellValueByColumnAndRow(3, $i, $fila['codigo_producto_final']);
                $documento->setCellValueByColumnAndRow(4, $i, $fila['tipo_solicitud']);
                $documento->getCellByColumnAndRow(5, $i)->setValueExplicit($fila['identificador'], 's');
                $documento->setCellValueByColumnAndRow(6, $i, $fila['razon_social']);
                $documento->setCellValueByColumnAndRow(7, $i, $fila['subtipo_producto']);
                $documento->setCellValueByColumnAndRow(8, $i, $fila['nombre_producto']);
                $documento->setCellValueByColumnAndRow(9, $i, ($fila['fecha_creacion'] != null ? date('Y-m-d', strtotime($fila['fecha_creacion'])) : ''));
                $documento->getCellByColumnAndRow(10, $i)->setValueExplicit($fila['identificador_ejecutor'], 's');
                $documento->setCellValueByColumnAndRow(11, $i, $fila['nombre_ejecutor']);
                $documento->setCellValueByColumnAndRow(12, $i, $fila['perfil']);
                $documento->setCellValueByColumnAndRow(13, $i, $fila['provincia']);
                $documento->setCellValueByColumnAndRow(14, $i, ($fila['fecha_despacho'] != null ? date('Y-m-d', strtotime($fila['fecha_despacho'])) : ''));
                $documento->setCellValueByColumnAndRow(15, $i, $fila['estado_revision']);
                $documento->setCellValueByColumnAndRow(16, $i, $fila['comentario_revision']);
                $documento->setCellValueByColumnAndRow(17, $i, $fila['nombre_tecnico_asignado']);
                $documento->setCellValueByColumnAndRow(18, $i, $fila['accion']);

                $i ++;
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="excelSolicitudesSecuenciaRevision.xlsx"');
        header("Pragma: no-cache");
        header("Expires: 0");

        $writer = IOFactory::createWriter($hoja, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
    
    public function verificarOficinaTecnico($identificador)
    {
        $mensaje = '';
        $idAreaPadre= 'DRIP';
        
        //Buscar lista de áreas dentro de CRIA        
        $areas = $this->lNegocioArea->buscarEstructuraXCodigoPadre($idAreaPadre);
        
        //Verificar si el usuario se encuentra dentro de CRIA
        $sentencia = "id_area in " . $areas . " and identificador='".$identificador."' and estado=1 LIMIT 1;";
        
        $usuario = $this->lNegocioFuncionarios->buscarLista($sentencia);
        
        if (isset($usuario->current()->identificador)) {
            $mensaje = 'CRIA';
        }else{
            $mensaje = 'Pichincha';
        }

        return $mensaje;
    }
}