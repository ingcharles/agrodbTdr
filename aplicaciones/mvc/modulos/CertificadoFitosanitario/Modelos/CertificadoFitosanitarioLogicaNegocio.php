<?php
/**
 * Lógica del negocio de CertificadoFitosanitarioModelo
 *
 * Este archivo se complementa con el archivo CertificadoFitosanitarioControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    CertificadoFitosanitarioLogicaNegocio
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoFitosanitario\Modelos;

use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosModelo;
use Agrodb\CertificadoFitosanitario\Modelos\DocumentosAdjuntosLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\DocumentosAdjuntosModelo;
use Agrodb\CertificadoFitosanitario\Modelos\PaisesPuertosTransitoLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\PaisesPuertosTransitoModelo;
use Agrodb\CertificadoFitosanitario\Modelos\PuertosDestinoLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\PuertosDestinoModelo;
use Agrodb\RequisitosComercializacion\Modelos\RequisitosLogicaNegocio;
use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;
use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\MediosTransporteLogicaNegocio;
use Agrodb\Catalogos\Modelos\PuertosLogicaNegocio;
use Agrodb\Catalogos\Modelos\TipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\SubtipoProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\UnidadesMedidasCfeLogicaNegocio;
use Agrodb\Catalogos\Modelos\IdiomasLogicaNegocio;
use Agrodb\Catalogos\Modelos\TiposTratamientoLogicaNegocio;
use Agrodb\Catalogos\Modelos\TratamientosLogicaNegocio;
use Agrodb\Catalogos\Modelos\UnidadesDuracionLogicaNegocio;
use Agrodb\Catalogos\Modelos\UnidadesTemperaturaLogicaNegocio;
use Agrodb\Catalogos\Modelos\ConcentracionesTratamientoLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\SitiosLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\AreasLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\ProveedoresLogicaNegocio;
use Agrodb\Protocolos\Modelos\ProtocolosComercializacionLogicaNegocio;
use Agrodb\Protocolos\Modelos\ProtocolosAreasLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\IModelo;
use Agrodb\Core\JasperReport;
use Zend\Db\Sql\Ddl\Column\Integer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Spatie\ArrayToXml\ArrayToXml;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\ConfiguracionCertificadoFitosanitarioHub\Modelos\ConfiguracionFitosanitarioLogicaNegocio;
use Agrodb\WebServices\Modelos\CertificadoFitosanitarioEphytoLogicaNegocio;
use Agrodb\Catalogos\Modelos\UnidadesDuracionModelo;
use Agrodb\Catalogos\Modelos\UnidadesTemperaturaModelo;
use Agrodb\PruebaLaboratorio\Modelos\CabeceraLaboratorioModelo;

class CertificadoFitosanitarioLogicaNegocio implements IModelo
{

    private $modeloCertificadoFitosanitario = null;

    private $modeloExportadoresProductos = null;

    private $lNegocioExportadoresProductos = null;

    private $lNegocioDocumentosAdjuntos = null;

    private $lNegocioPuertosDestino = null;

    private $lNegocioLocalizacion = null;

    private $lNegocioMediosTransporte = null;

    private $lNegocioProductos = null;

    private $lNegocioCfeUnidadesMedidas = null;

    private $lNegocioUnidadesDuracion = null;

    private $lNegocioUnidadesTemperatura = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloCertificadoFitosanitario = new CertificadoFitosanitarioModelo();
        $this->modeloExportadoresProductos = new ExportadoresProductosModelo();
        $this->lNegocioExportadoresProductos = new ExportadoresProductosLogicaNegocio();
        $this->lNegocioDocumentosAdjuntos = new DocumentosAdjuntosLogicaNegocio();
        $this->lNegocioPuertosDestino = new PuertosDestinoLogicaNegocio();
        $this->lNegocioLocalizacion = new LocalizacionLogicaNegocio();
        $this->lNegocioMediosTransporte = new MediosTransporteLogicaNegocio();
        $this->lNegocioProductos = new ProductosLogicaNegocio();
        $this->lNegocioCfeUnidadesMedidas = new UnidadesMedidasCfeLogicaNegocio();
        $this->lNegocioIdiomas = new IdiomasLogicaNegocio();
        $this->lNegocioUnidadesDuracion = new UnidadesDuracionLogicaNegocio();
        $this->lNegocioUnidadesTemperatura = new UnidadesTemperaturaLogicaNegocio();
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

            if (isset($datos['estado_certificado'])) {
                
                switch ($datos['estado_certificado']) {

                    case 'Inspeccion':
                        {
                            $datos['identificador_revision'] = $_SESSION['usuario'];
                            $datos['fecha_revision'] = 'now()';

                            if ($datos['tipo_revision'] == 'Inspeccion') {
                                $datos['observacion_revision'] = 'Inspección realizada';
                            } else {
                                $datos['observacion_revision'] = 'Hora de inspección asignada';
                            }

                            break;
                        }

                    case 'Documental':
                        {
                            $datos['identificador_revision'] = $_SESSION['usuario'];
                            $datos['fecha_revision'] = 'now()';

                            break;
                        }

                    case 'pago':
                        {
                            $datos['identificador_revision'] = $_SESSION['usuario'];
                            $datos['fecha_revision'] = 'now()';

                            break;
                        }

                    case 'Aprobado':
                        {
                            $datos['identificador_revision'] = $_SESSION['usuario'];
                            $datos['fecha_revision'] = 'now()';

                            break;
                        }

                    case 'Reemplazo':
                        {
                            $datos['identificador_revision'] = $_SESSION['usuario'];
                            $datos['fecha_revision'] = 'now()';
                            $datos['observacion_revision'] = 'Solicitud anulada por Reemplazo.';

                            break;
                        }

                    case 'DocumentalReimpresion':
                        {
                            $datos['estado_certificado'] = 'Documental';

                            break;
                        }

                    default:
                        {

                            break;
                        }
                }
                
            } else {

                if (isset($datos['tipo_certificado'])) {
                    
                    //Es una solicitud nueva
                    $tipoSolicitud = $datos['tipo_certificado'];

                    switch ($tipoSolicitud) {

                        case "musaceas":
                        case "ornamentales":
                            $datos['estado_certificado'] = "Documental";
                            break;

                        case "otros":
                            $datos['estado_certificado'] = "ConfirmarInspeccion";
                            break;
                    }

                    if (isset($datos['forma_pago']) && $datos['forma_pago'] == 'saldo') {
                        $datos['descuento'] = 'No';
                    }
                    
                }
                
            }

            $tablaModelo = new CertificadoFitosanitarioModelo($datos);
            $procesoGuardar = false;
            $procesoIngreso = $this->modeloCertificadoFitosanitario->getAdapter()
                ->getDriver()
                ->getConnection();
            $procesoIngreso->beginTransaction();

            $datosBd = $tablaModelo->getPrepararDatos();

            if ($tablaModelo->getIdCertificadoFitosanitario() != null && $tablaModelo->getIdCertificadoFitosanitario() > 0) {
                $this->modeloCertificadoFitosanitario->actualizar($datosBd, $tablaModelo->getIdCertificadoFitosanitario());
                $idCertificadoFitosanitario = $tablaModelo->getIdCertificadoFitosanitario();
            } else {
                unset($datosBd["id_certificado_fitosanitario"]);
                $idCertificadoFitosanitario = $this->modeloCertificadoFitosanitario->guardar($datosBd);
                $procesoGuardar = true;
            }

            if ($procesoGuardar) {

                // Verifica que sea una solicitud de reemplazo y actualiza el estado de solicitud original a "PorReemplazar"             
                if(isset($datos['proceso_solicitud'])){
                    if ($datos['proceso_solicitud'] == "reimpresion") {
                        $arrayParametros = array(
                            'id_certificado_fitosanitario' => $datos['id_certificado_fitosanitario_reemplazo'],
                            'estado_certificado' => 'PorReemplazar'
                        );
                        $this->actualizarEstadoCertificado($arrayParametros);
                    }
                }

                $arrayPaisPuertosDestino = $datos['array_pais_puertos_destino'];
                $arrayPaisPuertosTransito = $datos['array_pais_puertos_transito'];
                $arrayExportadoresProductos = $datos['array_exportadores_productos'];

                // PUERTOS DESTINO
                if (! empty($arrayPaisPuertosDestino)) { // verificar si no esta vacío

                    foreach ($arrayPaisPuertosDestino as $item) {

                        $arrayPuetosDestino = array(
                            'id_certificado_fitosanitario' => $idCertificadoFitosanitario,
                            'id_puerto_pais_destino' => $item['idPuertoPaisDestino'],
                            'nombre_puerto_pais_destino' => $item['nombrePuertoPaisDestino']
                        );

                        $statement = $this->modeloCertificadoFitosanitario->getAdapter()
                            ->getDriver()
                            ->createStatement();

                        $sqlInsertar = $this->modeloCertificadoFitosanitario->guardarSql('puertos_destino', $this->modeloCertificadoFitosanitario->getEsquema());
                        $sqlInsertar->columns(array_keys($arrayPuetosDestino));
                        $sqlInsertar->values($arrayPuetosDestino, $sqlInsertar::VALUES_MERGE);
                        $sqlInsertar->prepareStatement($this->modeloCertificadoFitosanitario->getAdapter(), $statement);
                        $statement->execute();
                    }
                }

                // PAIS PUERTO TRANSITO
                if (! empty($arrayPaisPuertosTransito)) { // verificar si no esta vacío

                    foreach ($arrayPaisPuertosTransito as $item) {

                        $arrayPuertosTransito = array(
                            'id_certificado_fitosanitario' => $idCertificadoFitosanitario,
                            'id_pais_transito' => $item['idPaisTransito'],
                            'nombre_pais_transito' => $item['nombrePaisTransito'],
                            'id_puerto_transito' => $item['idPuertoTransito'],
                            'nombre_puerto_transito' => $item['nombrePuertoTransito'],
                            'id_medio_transporte_transito' => $item['idMedioTransporteTransito'],
                            'nombre_medio_transporte_transito' => $item['nombreMedioTransporteTransito']
                        );

                        $statement = $this->modeloCertificadoFitosanitario->getAdapter()
                            ->getDriver()
                            ->createStatement();

                        $sqlInsertar = $this->modeloCertificadoFitosanitario->guardarSql('paises_puertos_transito', $this->modeloCertificadoFitosanitario->getEsquema());
                        $sqlInsertar->columns(array_keys($arrayPuertosTransito));
                        $sqlInsertar->values($arrayPuertosTransito, $sqlInsertar::VALUES_MERGE);
                        $sqlInsertar->prepareStatement($this->modeloCertificadoFitosanitario->getAdapter(), $statement);
                        $statement->execute();
                    }
                }

                // EXPORTADORES PRODUCTOS
                if (! empty($arrayExportadoresProductos)) { // verificar si no esta vacío

                    foreach ($arrayExportadoresProductos as $item) {

                        $arrayExportadoresProductos = array(
                            'id_certificado_fitosanitario' => $idCertificadoFitosanitario,
                            'identificador_exportador' => $item['identificadorExportador'],
                            'razon_social_exportador' => $item['razonSocialExportador'],
                            'direccion_exportador' => $item['direccionExportador'],
                            'id_tipo_producto' => $item['idTipoProducto'],
                            'nombre_tipo_producto' => $item['nombreTipoProducto'],
                            'id_subtipo_producto' => $item['idSubtipoProducto'],
                            'nombre_subtipo_producto' => $item['nombreSubtipoProducto'],
                            'id_producto' => $item['idProducto'],
                            'nombre_producto' => $item['nombreProducto'],
                            'partida_arancelaria_producto' => $item['partidaArancelariaProducto'],
                            'cantidad_comercial' => $item['cantidadComercial'],
                            'id_unidad_cantidad_comercial' => $item['idUnidadCantidadComercial'],
                            'nombre_unidad_cantidad_comercial' => $item['nombreUnidadCantidadComercial'],
                            'peso_neto' => $item['pesoNeto'],
                            'id_unidad_peso_neto' => $item['idUnidadPesoNeto'],
                            'nombre_unidad_peso_neto' => $item['nombreUnidadPesoNeto']
                        );

                        if (isset($item['certificacionOrganica']) && $item['certificacionOrganica'] != "") {
                            $arrayExportadoresProductos += [
                                'certificacion_organica' => $item['certificacionOrganica']
                            ];
                        }

                        if (isset($item['idTipoTratamiento']) && $item['idTipoTratamiento'] != "") {
                            $arrayExportadoresProductos += [
                                'id_tipo_tratamiento' => $item['idTipoTratamiento']
                            ];
                            $arrayExportadoresProductos += [
                                'nombre_tipo_tratamiento' => $item['nombreTipoTratamiento']
                            ];
                        }

                        if (isset($item['idTratamiento']) && $item['idTratamiento'] != "") {
                            $arrayExportadoresProductos += [
                                'id_tratamiento' => $item['idTratamiento']
                            ];
                            $arrayExportadoresProductos += [
                                'nombre_tratamiento' => $item['nombreTratamiento']
                            ];
                        }

                        if (isset($item['idUnidadDuracion']) && $item['idUnidadDuracion'] != "") {
                            $arrayExportadoresProductos += [
                                'id_unidad_duracion' => $item['idUnidadDuracion']
                            ];
                            $arrayExportadoresProductos += [
                                'nombre_unidad_duracion' => $item['nombreUnidadDuracion']
                            ];
                        }

                        if (isset($item['idUnidadTemperatura']) && $item['idUnidadTemperatura'] != "") {
                            $arrayExportadoresProductos += [
                                'id_unidad_temperatura' => $item['idUnidadTemperatura']
                            ];
                            $arrayExportadoresProductos += [
                                'nombre_unidad_temperatura' => $item['nombreUnidadTemperatura']
                            ];
                        }

                        if (isset($item['idUnidadConcentracion']) && $item['idUnidadConcentracion'] != "") {
                            $arrayExportadoresProductos += [
                                'id_unidad_concentracion' => $item['idUnidadConcentracion']
                            ];
                            $arrayExportadoresProductos += [
                                'nombre_unidad_concentracion' => $item['nombreUnidadConcentracion']
                            ];
                        }

                        if (isset($item['pesoBruto']) && $item['pesoBruto'] != "") {
                            $arrayExportadoresProductos += [
                                'peso_bruto' => $item['pesoBruto']
                            ];
                            $arrayExportadoresProductos += [
                                'id_unidad_peso_bruto' => $item['idUnidadPesoBruto']
                            ];
                            $arrayExportadoresProductos += [
                                'nombre_unidad_peso_bruto' => $item['nombreUnidadPesoBruto']
                            ];
                        }

                        if (isset($item['fechaInspeccion']) && $item['fechaInspeccion'] != "") {
                            $arrayExportadoresProductos += [
                                'fecha_inspeccion' => $item['fechaInspeccion']
                            ];
                            $arrayExportadoresProductos += [
                                'hora_inspeccion' => $item['horaInspeccion']
                            ];
                        }

                        if (isset($item['fechaTratamiento']) && $item['fechaTratamiento'] != "") {
                            $arrayExportadoresProductos += [
                                'fecha_tratamiento' => $item['fechaTratamiento']
                            ];
                        }

                        if (isset($item['duracionTratamiento']) && $item['duracionTratamiento'] != "") {
                            $arrayExportadoresProductos += [
                                'duracion_tratamiento' => $item['duracionTratamiento']
                            ];
                        }

                        if (isset($item['temperaturaTratamiento']) && $item['temperaturaTratamiento'] != "") {
                            $arrayExportadoresProductos += [
                                'temperatura_tratamiento' => $item['temperaturaTratamiento']
                            ];
                        }

                        if (isset($item['productoQuimico']) && $item['productoQuimico'] != "") {
                            $arrayExportadoresProductos += [
                                'producto_quimico' => $item['productoQuimico']
                            ];
                        }

                        if (isset($item['concentracionTratamiento']) && $item['concentracionTratamiento'] != "") {
                            $arrayExportadoresProductos += [
                                'concentracion_tratamiento' => $item['concentracionTratamiento']
                            ];
                        }

                        if (isset($item['codigoCentroAcopio']) && $item['codigoCentroAcopio'] != "") {
                            $arrayExportadoresProductos += [
                                'codigo_centro_acopio' => $item['codigoCentroAcopio']
                            ];
                            $arrayExportadoresProductos += [
                                'id_area' => $item['idArea']
                            ];
                            $arrayExportadoresProductos += [
                                'nombre_area' => $item['nombreArea']
                            ];
                            $arrayExportadoresProductos += [
                                'id_provincia_area' => $item['idProvinciaArea']
                            ];
                            $arrayExportadoresProductos += [
                                'nombre_provincia_area' => $item['nombreProvinciaArea']
                            ];
                        }

                        $arrayExportadoresProductos += [
                            'estado_exportador_producto' => 'Creado'
                        ];

                        $statement = $this->modeloCertificadoFitosanitario->getAdapter()
                            ->getDriver()
                            ->createStatement();

                        $sqlInsertar = $this->modeloCertificadoFitosanitario->guardarSql('exportadores_productos', $this->modeloCertificadoFitosanitario->getEsquema());
                        $sqlInsertar->columns(array_keys($arrayExportadoresProductos));
                        $sqlInsertar->values($arrayExportadoresProductos, $sqlInsertar::VALUES_MERGE);
                        $sqlInsertar->prepareStatement($this->modeloCertificadoFitosanitario->getAdapter(), $statement);
                        $statement->execute();
                    }
                }

                // DOCUMENTOS ADJUNTOS

                if (isset($datos['array_documentos_adjuntos'])) {

                    $arrayDocumentos = $datos['array_documentos_adjuntos'];

                    foreach ($arrayDocumentos as $item) {
                        $arrayParametros = array(
                            'id_certificado_fitosanitario' => $idCertificadoFitosanitario,
                            'tipo_adjunto' => $item['tipo_adjunto'],
                            'ruta_enlace_adjunto' => $item['ruta_enlace_adjunto']
                        );
                    }

                    $tipoAdjunto = $arrayParametros['tipo_adjunto'];
                    $rutaAdjunto = "";
                    $rutaEnlaceAdjunto = $arrayParametros['ruta_enlace_adjunto'];
                } else {
                    $tipoAdjunto = "Documento de respaldo";
                    $rutaAdjunto = $datos['ruta_adjunto'];
                    $rutaEnlaceAdjunto = $datos['ruta_enlace_adjunto'];
                }

                if ($rutaAdjunto != "" || $rutaEnlaceAdjunto != "") {

                    $arrayDocumentosAdjuntos = array(
                        'id_certificado_fitosanitario' => $idCertificadoFitosanitario,
                        'tipo_adjunto' => $tipoAdjunto,
                        'ruta_adjunto' => $rutaAdjunto,
                        'ruta_enlace_adjunto' => $rutaEnlaceAdjunto
                    );

                    $statement = $this->modeloCertificadoFitosanitario->getAdapter()
                        ->getDriver()
                        ->createStatement();

                    $sqlInsertar = $this->modeloCertificadoFitosanitario->guardarSql('documentos_adjuntos', $this->modeloCertificadoFitosanitario->getEsquema());
                    $sqlInsertar->columns(array_keys($arrayDocumentosAdjuntos));
                    $sqlInsertar->values($arrayDocumentosAdjuntos, $sqlInsertar::VALUES_MERGE);
                    $sqlInsertar->prepareStatement($this->modeloCertificadoFitosanitario->getAdapter(), $statement);
                    $statement->execute();
                }
            }

            $procesoIngreso->commit();
            return $idCertificadoFitosanitario;
        } catch (GuardarExcepcion $ex) {
            $procesoIngreso->rollback();
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Guarda el registro de subsanacion
     *
     * @param array $datos
     * @return int
     */
    public function guardarSubsanacion(Array $datos)
    {
        try {
            
            $idCertificadoFitosanitario = $_POST["id_certificado_fitosanitario"];
            $tipoAdjunto = "Documento de respaldo";
            $rutaAdjunto = $_POST['ruta_adjunto'];
            $rutaEnlaceAdjunto = $_POST['ruta_enlace_adjunto'];
            $banderaDocumentoAdjunto = false;
            
            if($rutaAdjunto != "" || $rutaEnlaceAdjunto != ""){
                $banderaDocumentoAdjunto = true;
            }

            $procesoIngreso = $this->modeloCertificadoFitosanitario->getAdapter()
            ->getDriver()
            ->getConnection();
            $procesoIngreso->beginTransaction();

            //Proceso de guardado o actualizacion de documento adjunto
            if($banderaDocumentoAdjunto){
            
                $parametrosAdjunto = array('id_certificado_fitosanitario' => $idCertificadoFitosanitario
                                            , 'tipo_adjunto' => 'Documento de respaldo'
                );
                
                $verificarAdjunto = $this->lNegocioDocumentosAdjuntos->buscarLista($parametrosAdjunto);
            
                if(isset($verificarAdjunto->current()->id_documento_adjunto)){
            
                    $idDocumentoAdjunto = $verificarAdjunto->current()->id_documento_adjunto;
                    
                    $datosDocumentosAdjuntos = array('id_certificado_fitosanitario' =>  $idCertificadoFitosanitario
                                                    , 'id_documento_adjunto' => $idDocumentoAdjunto
                                                    , 'tipo_adjunto' => $tipoAdjunto
                                                    , 'ruta_adjunto' => $rutaAdjunto
                                                    , 'ruta_enlace_adjunto' => $rutaEnlaceAdjunto
                    );
                    
                    $statement = $this->modeloCertificadoFitosanitario->getAdapter()
                    ->getDriver()
                    ->createStatement();
                    
                    $sqlActualizar = $this->modeloCertificadoFitosanitario->actualizarSql('documentos_adjuntos', $this->modeloCertificadoFitosanitario->getEsquema());
                    $sqlActualizar->set($datosDocumentosAdjuntos);
                    $sqlActualizar->where(array('id_certificado_fitosanitario' => $idCertificadoFitosanitario));
                    $sqlActualizar->prepareStatement($this->modeloCertificadoFitosanitario->getAdapter(), $statement);
                    $statement->execute();
                
                }else{
                
                    $datosDocumentosAdjuntos = array('id_certificado_fitosanitario' =>  $idCertificadoFitosanitario
                                                    , 'tipo_adjunto' => $tipoAdjunto
                                                    , 'ruta_adjunto' => $rutaAdjunto
                                                    , 'ruta_enlace_adjunto' => $rutaEnlaceAdjunto
                    );
                    
                    $statement = $this->modeloCertificadoFitosanitario->getAdapter()
                    ->getDriver()
                    ->createStatement();
                    
                    $sqlInsertar = $this->modeloCertificadoFitosanitario->guardarSql('documentos_adjuntos', $this->modeloCertificadoFitosanitario->getEsquema());
                    $sqlInsertar->columns(array_keys($datosDocumentosAdjuntos));
                    $sqlInsertar->values($datosDocumentosAdjuntos, $sqlInsertar::VALUES_MERGE);
                    $sqlInsertar->prepareStatement($this->modeloCertificadoFitosanitario->getAdapter(), $statement);
                    $statement->execute();
                
                }
            
            }
            
            //Proceso de actualizacion del estado los productos por exportador
            $statement = $this->modeloCertificadoFitosanitario->getAdapter()
            ->getDriver()
            ->createStatement();
            
            $datosActualizacionExportadoresProductos = array('estado_exportador_producto' => 'Subsanado');
            
            $sqlActualizar = $this->modeloCertificadoFitosanitario->actualizarSql('exportadores_productos', $this->modeloCertificadoFitosanitario->getEsquema());
            $sqlActualizar->set($datosActualizacionExportadoresProductos);
            $sqlActualizar->where(array('id_certificado_fitosanitario' => $idCertificadoFitosanitario, 'estado_exportador_producto' => 'Subsanacion'));
            $sqlActualizar->prepareStatement($this->modeloCertificadoFitosanitario->getAdapter(), $statement);
            $statement->execute();
            
            //Verificacion de estado de productos y solicitud para obtener cambio de estado : funcion en bbdd
            $arrayVerificarCambioEstado = array('id_certificado_fitosanitario' => $idCertificadoFitosanitario
                , 'estado_certificado' => 'Subsanacion'
            );
            
            $validarExportadorProducto = $this->verificarCambioEstadoSolicitudSubsanacion($arrayVerificarCambioEstado);
            $estadoSiguiente = $validarExportadorProducto->current()->f_verificar_cambio_estado_subsanacion;
            
            $arrayParametrosCertificado = array('id_certificado_fitosanitario' => $idCertificadoFitosanitario
                , 'estado_certificado' => $estadoSiguiente
                , 'fecha_modificacion_certificado' => 'now()'
            );
            
            //Proceso de actualizacion del la cabecera de la solicitud
            $statement = $this->modeloCertificadoFitosanitario->getAdapter()
            ->getDriver()
            ->createStatement();
            
            $sqlActualizar = $this->modeloCertificadoFitosanitario->actualizarSql('certificado_fitosanitario', $this->modeloCertificadoFitosanitario->getEsquema());
            $sqlActualizar->set($arrayParametrosCertificado);
            $sqlActualizar->where(array('id_certificado_fitosanitario' => $idCertificadoFitosanitario));
            $sqlActualizar->prepareStatement($this->modeloCertificadoFitosanitario->getAdapter(), $statement);
            $statement->execute();
            
            $procesoIngreso->commit();            
          
            return $idCertificadoFitosanitario;
        } catch (GuardarExcepcion $ex) {
            $procesoIngreso->rollback();
            throw new \Exception($ex->getMessage());
        }
    }
    
    
    /**
     * Borra el registro actual
     *
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloCertificadoFitosanitario->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return CertificadoFitosanitarioModelo
     */
    public function buscar($id)
    {
        return $this->modeloCertificadoFitosanitario->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloCertificadoFitosanitario->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloCertificadoFitosanitario->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarCertificadoFitosanitario()
    {
        $consulta = "SELECT * FROM " . $this->modeloCertificadoFitosanitario->getEsquema() . ". certificado_fitosanitario";
        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos
     * de localizacion de Ecuador
     *
     * @return array|ResultSet
     */
    public function obtenerLocalizacionEcuador($arrayParametros = null)
    {
        $consulta = "SELECT 
                        id_localizacion
                        , codigo
                        , nombre
                        , codigo_vue
                        , nombre_ingles
                     FROM 
                        g_catalogos.localizacion
                     WHERE 
                        codigo = 'EC'
                        and categoria = 0;";

        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los puertos
     * de acuerdo a un país o provincia seleccionado
     *
     * @return array|ResultSet
     */
    public function obtenerPuertosPorIdPaisPorIdProvincia($arrayParametros)
    {
        $busqueda = "";
        $idLocalizacion = $arrayParametros['idLocalizacion'];

        switch ($arrayParametros['tipoValor']) {

            case "pais":
                $busqueda = "id_pais = " . $idLocalizacion;
                break;

            case "provincia":
                $busqueda = "id_provincia = " . $idLocalizacion;
                break;
        }

        $consulta = "SELECT
                    	id_puerto
                    	, nombre_puerto
                    	, id_pais
                    	, codigo_puerto
                    	, codigo_pais
                    	, tipo_puerto
                    	, nombre_provincia
                    	, id_provincia
                    FROM
                    	g_catalogos.puertos
                    WHERE
                        " . $busqueda . "
                    ORDER BY nombre_puerto ASC;";

        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los puertos
     * de acuerdo a un medio de transporte
     *
     * @return array|ResultSet
     */
    public function obtenerPuertosPorNombreMedioTrasporte($arrayParametros)
    {
        $idLocalizacion = $arrayParametros['idLocalizacion'];
        $nombreMedioTransporte = $arrayParametros['nombreMedioTrasporte'];

        $consulta = "SELECT
                    	id_puerto
                    	, nombre_puerto
                    	, id_pais
                    	, codigo_puerto
                    	, codigo_pais
                    	, tipo_puerto
                    	, nombre_provincia
                    	, id_provincia
                    FROM
                    	g_catalogos.puertos
                    WHERE
                        tipo_puerto = '" . $nombreMedioTransporte . "'
                        and id_pais = '" . $idLocalizacion . "'
                        and nombre_provincia is not null
                    ORDER BY nombre_puerto ASC;";

        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para buscar los nombres de los
     * operadores/solicitantes que tienen un CFE enviado a revisión en un
     * estado en particular para una provincia específica.
     *
     * @return array|ResultSet
     */
    public function buscarSolicitantePorFaseRevision($estado, $idProvinciaRevision)
    {
        $query = "";
        $inner = "";

        if ($estado == 'ConfirmarInspeccion') {
            $query = " and ep.id_provincia_area = $idProvinciaRevision";
            $query .= " and ep.estado_exportador_producto in ('Creado')";
        } else if ($estado == 'Inspeccion') {
            $query = " and ep.id_provincia_area = $idProvinciaRevision";
            $query .= " and ep.estado_exportador_producto in ('FechaConfirmada', 'Subsanado', 'DevueltoTecnico')";
        } else if ($estado == 'Documental') {
            $query = " and pu.id_provincia = $idProvinciaRevision";
            $query .= " and ep.estado_exportador_producto in ('InspeccionAprobada', 'Creado', 'Subsanado')";
            $inner = "INNER JOIN g_catalogos.puertos pu ON cf.id_puerto_embarque = pu.id_puerto";
        } else if ($estado == 'Aprobado') {
            $query .= " and ep.estado_exportador_producto in ('Aprobado')";
        }

        $consulta = "SELECT
                            DISTINCT cf.identificador_solicitante, o.razon_social
                        FROM
                            g_certificado_fitosanitario.certificado_fitosanitario cf
                            INNER JOIN g_certificado_fitosanitario.exportadores_productos ep ON ep.id_certificado_fitosanitario = cf.id_certificado_fitosanitario
                            INNER JOIN g_operadores.operadores o ON cf.identificador_solicitante = o.identificador
                            $inner
                        WHERE
                            cf.estado_certificado in ('$estado')
                            $query
                        ORDER BY
                            o.razon_social ASC; ";

        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para buscar los nombres de los
     * países donde los operadores/solicitantes tienen un CFE enviado a revisión en un
     * estado en particular para una provincia específica.
     *
     * @return array|ResultSet
     */
    public function buscarPaisesSolicitanteXFaseRevision($estado, $identificadorSolicitante, $idProvinciaRevision)
    {
        $consulta = "   SELECT
                            DISTINCT cf.id_pais_destino, cf.nombre_pais_destino
                        FROM
                            g_certificado_fitosanitario.certificado_fitosanitario cf
                        WHERE
                            cf.estado_certificado in ('$estado') and
                            cf.identificador_solicitante = '$identificadorSolicitante'
                        ORDER BY
                            cf.nombre_pais_destino ASC; ";

        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar solicitudes para revisión usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarSolicitudesRevisionFiltradas($arrayParametros)
    {
        $busqueda = '';
        $join = '';
        $campos = '';

        if ($arrayParametros['faseRevision'] == 'ConfirmarInspeccion') {

            $join = " INNER JOIN g_certificado_fitosanitario.exportadores_productos ep ON ep.id_certificado_fitosanitario = cf.id_certificado_fitosanitario ";

            if (isset($arrayParametros['id_provincia_revision']) && ($arrayParametros['id_provincia_revision'] != '')) {
                $busqueda .= " and ep.id_provincia_area = '" . $arrayParametros['id_provincia_revision'] . "'";
            }

            $busqueda .= " and ep.estado_exportador_producto in ('Creado') ";
        } else if ($arrayParametros['faseRevision'] == 'Inspeccion') {

            $join = " INNER JOIN g_certificado_fitosanitario.exportadores_productos ep ON ep.id_certificado_fitosanitario = cf.id_certificado_fitosanitario ";
            $join .= " INNER JOIN g_certificado_fitosanitario.confirmaciones_inspeccion ci ON ci.id_solicitud = cf.id_certificado_fitosanitario ";

            if (isset($arrayParametros['id_provincia_revision']) && ($arrayParametros['id_provincia_revision'] != '')) {
                $busqueda .= " and ep.id_provincia_area = '" . $arrayParametros['id_provincia_revision'] . "'";
            }

            if (isset($arrayParametros['identificador_inspector']) && ($arrayParametros['identificador_inspector'] != '')) {
                $busqueda .= " and ci.identificador_inspector = '" . $arrayParametros['identificador_inspector'] . "' and ci.estado = 'FechaConfirmada'";
            }

            // Verificar que ingresen los datos solamente de los elementos que fueron devueltos desde documental
            $busqueda .= " and ep.estado_exportador_producto in ('FechaConfirmada', 'Subsanado', 'DevueltoTecnico') 
                           and ep.id_area = ci.id_area_inspeccion";
        } else if ($arrayParametros['faseRevision'] == 'Documental') {

            $join = " INNER JOIN g_certificado_fitosanitario.exportadores_productos ep ON ep.id_certificado_fitosanitario = cf.id_certificado_fitosanitario ";
            $join .= " INNER JOIN g_catalogos.puertos pu ON cf.id_puerto_embarque = pu.id_puerto ";

            if (isset($arrayParametros['id_provincia_revision']) && ($arrayParametros['id_provincia_revision'] != '')) {
                $busqueda .= " and pu.id_provincia = '" . $arrayParametros['id_provincia_revision'] . "'";
            }

            $busqueda .= " and ep.estado_exportador_producto in ('Creado', 'InspeccionAprobada', 'Subsanado')";
        } else {
            $join = " INNER JOIN g_certificado_fitosanitario.exportadores_productos ep ON ep.id_certificado_fitosanitario = cf.id_certificado_fitosanitario ";
            $busqueda .= " and ep.estado_exportador_producto in ('Aprobado', 'Rechazado')";
        }

        if (isset($arrayParametros['tipo_certificado']) && ($arrayParametros['tipo_certificado'] != '') && ($arrayParametros['tipo_certificado'] != ' ')) {
            $busqueda .= " and cf.tipo_certificado = '" . $arrayParametros['tipo_certificado'] . "'";
        }

        if (isset($arrayParametros['codigo_certificado']) && ($arrayParametros['codigo_certificado'] != '') && ($arrayParametros['codigo_certificado'] != ' ')) {
            $busqueda .= " and cf.codigo_certificado = '" . $arrayParametros['codigo_certificado'] . "'";
        }

        if (isset($arrayParametros['identificador_solicitante']) && ($arrayParametros['identificador_solicitante'] != '') && ($arrayParametros['identificador_solicitante'] != 'Seleccione....')) {
            $busqueda .= " and cf.identificador_solicitante = '" . $arrayParametros['identificador_solicitante'] . "'";
        }

        if (isset($arrayParametros['id_pais_destino']) && ($arrayParametros['id_pais_destino'] != '') && ($arrayParametros['id_pais_destino'] != 'Seleccione....')) {
            $busqueda .= " and cf.id_pais_destino = '" . $arrayParametros['id_pais_destino'] . "'";
        }

        if (isset($arrayParametros['id_medio_transporte']) && ($arrayParametros['id_medio_transporte'] != '') && ($arrayParametros['id_medio_transporte'] != 'Seleccione....')) {
            $busqueda .= " and cf.id_medio_transporte = '" . $arrayParametros['id_medio_transporte'] . "'";
        }

        if (isset($arrayParametros['fechaInicio']) && ($arrayParametros['fechaInicio'] != '')) {
            $busqueda .= " and cf.fecha_creacion_certificado >= '" . $arrayParametros['fechaInicio'] . "  00:00:00'";
        }

        if (isset($arrayParametros['fechaFin']) && ($arrayParametros['fechaFin'] != '')) {
            $busqueda .= " and cf.fecha_creacion_certificado <= '" . $arrayParametros['fechaFin'] . "  24:00:00'";
        }

        $consulta = "  SELECT
                        	cf.id_certificado_fitosanitario 
                            , case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador
                            , cf.identificador_solicitante
                            , cf.codigo_certificado
                            , cf.tipo_certificado
                            , cf.nombre_pais_destino
					        , cf.estado_certificado " . $campos . "
                            , SPLIT_PART(STRING_AGG(distinct(ep.nombre_producto),','), ',', 1) as nombre_producto 
                        FROM
                        	g_certificado_fitosanitario.certificado_fitosanitario cf 
                            INNER JOIN g_operadores.operadores o ON cf.identificador_solicitante = o.identificador" . $join . "WHERE
                            cf.estado_certificado = '" . $arrayParametros['faseRevision'] . "'
                            " . $busqueda . "
                        GROUP BY 
                            1, 2, 3, 4, 5
                        ORDER BY
                        	cf.fecha_creacion_certificado ASC;";

        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para buscar la información de un grupo de solicitudes CFE.
     *
     * @return array|ResultSet
     */
    public function buscarSolicitudesXTipoXFaseRevision($arrayParametros)
    {
        $consulta = "   SELECT
                            cf.*,
                            o.razon_social
                        FROM
                            g_certificado_fitosanitario.certificado_fitosanitario cf
                            INNER JOIN g_operadores.operadores o ON cf.identificador_solicitante = o.identificador 
                        WHERE
                            cf.id_certificado_fitosanitario in (" . $arrayParametros['id_solicitud'] . ") and
                            cf.estado_certificado in (" . $arrayParametros['estado_certificado'] . ") and
                            cf.tipo_certificado in (" . $arrayParametros['tipo_certificado'] . ")
                        ORDER BY
                            cf.tipo_certificado, cf.codigo_certificado, cf.identificador_solicitante, cf.nombre_pais_destino ASC; ";

        // echo $consulta;
        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para buscar la información de un grupo de solicitudes CFE.
     *
     * @return array|ResultSet
     */
    public function buscarSolicitudesXTipoXFaseRevisionConCertificado($arrayParametros)
    {
        $consulta = "   SELECT
                            cf.*,
                            o.razon_social,
                            da.ruta_adjunto certificado
                        FROM
                            g_certificado_fitosanitario.certificado_fitosanitario cf
                            INNER JOIN g_operadores.operadores o ON cf.identificador_solicitante = o.identificador
                            FULL OUTER JOIN g_certificado_fitosanitario.documentos_adjuntos da ON cf.id_certificado_fitosanitario = da.id_certificado_fitosanitario                            
                        WHERE
                            cf.id_certificado_fitosanitario in (" . $arrayParametros['id_solicitud'] . ") and
                            cf.estado_certificado in (" . $arrayParametros['estado_certificado'] . ") and
                            cf.tipo_certificado in (" . $arrayParametros['tipo_certificado'] . ") and
                            da.tipo_adjunto in ('Certificado Anexo Fitosanitario')
                        ORDER BY
                            cf.tipo_certificado, cf.codigo_certificado, cf.identificador_solicitante, cf.nombre_pais_destino ASC; ";

        // echo $consulta;
        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar certificados fitosanitarios usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarCertificadosFitosanitariosPorFiltro($arrayParametros)
    {
        $identificadorOperador = $arrayParametros['identificadorOperador'];
        $tipoSolicitud = $arrayParametros['tipoSolicitud'];
        $paisDestino = $arrayParametros['paisDestino'];
        $idTipoProducto = $arrayParametros['idTipoProducto'];
        $idSubtipoProducto = $arrayParametros['idSubtipoProducto'];
        $idProducto = $arrayParametros['idProducto'];
        $fechaInicio = $arrayParametros['fechaInicio'];
        $fechaFin = $arrayParametros['fechaFin'];
        $estadoCertificado = $arrayParametros['estadoCertificado'];
        $numeroCertificado = $arrayParametros['numeroCertificado'];
        $busquedaEstado = "";

        $identificadorOperador = ($identificadorOperador == "") ? "NULL" : "'" . $identificadorOperador . "'";
        $tipoSolicitud = ($tipoSolicitud == "") ? "NULL" : "'" . $tipoSolicitud . "'";
        $paisDestino = ($paisDestino == "") ? "NULL" : "'" . $paisDestino . "'";
        $idTipoProducto = ($idTipoProducto == "") ? "NULL" : "'" . $idTipoProducto . "'";
        $idSubtipoProducto = ($idSubtipoProducto == "") ? "NULL" : "'" . $idSubtipoProducto . "'";
        $idProducto = ($idProducto == "") ? "NULL" : "'" . $idProducto . "'";
        $fechaInicio = ($tipoSolicitud == "") ? "NULL" : "'" . $fechaInicio . " 00:00:00'";
        $fechaFin = ($tipoSolicitud == "") ? "NULL" : "'" . $fechaFin . " 24:00:00'";
        $numeroCertificado = ($numeroCertificado == "") ? "NULL" : "'" . $numeroCertificado . "'";

        if ($estadoCertificado == "Subsanacion") {
            $busquedaEstado = "ep.estado_exportador_producto ";
        } else {
            $busquedaEstado = "cf.estado_certificado ";
        }

        $estadoCertificado = ($estadoCertificado == "") ? "NULL" : "'" . $estadoCertificado . "'";

        $consulta = "SELECT DISTINCT
                        cf.id_certificado_fitosanitario
                        , cf.identificador_solicitante
                        , cf.codigo_certificado
                        , cf.tipo_certificado
                        , cf.id_pais_destino
                        , cf.nombre_pais_destino
                        , cf.fecha_creacion_certificado
                        , cf.estado_certificado as estado_certificado
                     FROM
                        g_certificado_fitosanitario.certificado_fitosanitario cf
                     INNER JOIN g_certificado_fitosanitario.exportadores_productos ep ON cf.id_certificado_fitosanitario = ep.id_certificado_fitosanitario
                     WHERE
                        (" . $identificadorOperador . " is NULL or cf.identificador_solicitante = " . $identificadorOperador . ")
                        and (" . $tipoSolicitud . " is NULL or cf.tipo_certificado = " . $tipoSolicitud . ")
                        and (" . $paisDestino . " is NULL or cf.id_pais_destino = " . $paisDestino . ")
                        and (" . $idTipoProducto . " is NULL or ep.id_tipo_producto = " . $idTipoProducto . ")
                        and (" . $idSubtipoProducto . " is NULL or ep.id_subtipo_producto = " . $idSubtipoProducto . ")
                        and (" . $idProducto . " is NULL or ep.id_producto = " . $idProducto . ")
                        and (" . $fechaInicio . " is NULL or cf.fecha_creacion_certificado >= " . $fechaInicio . ")
                        and (" . $fechaFin . " is NULL or cf.fecha_creacion_certificado <= " . $fechaFin . ")
                        and (" . $numeroCertificado . " is NULL or cf.codigo_certificado = " . $numeroCertificado . ")
                        and (" . $estadoCertificado . " is NULL or " . $busquedaEstado . " = " . $estadoCertificado . ")
                    ORDER BY fecha_creacion_certificado DESC;";

        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para verificar
     * los codigos de certificados fitosanitarios generados
     *
     * @return array|ResultSet
     */
    public function verificarCodigoCertificadoFitosanitario($arrayParametros)
    {
        $identificadorSolicitante = $arrayParametros['identificadorSolicitante'];
        $codigoGenerado = $arrayParametros['codigoGenerado'];

        $consulta = "SELECT 
                        id_certificado_fitosanitario
                        , identificador_solicitante
                        , codigo_certificado
                        , tipo_certificado
                     FROM 
                        g_certificado_fitosanitario.certificado_fitosanitario
                     WHERE
                        identificador_solicitante = '" . $identificadorSolicitante . "'
                        and codigo_certificado = '" . $codigoGenerado . "';";

        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para actualizar el
     * estado de la generacion del certificado.
     *
     * @return array|ResultSet
     */
    public function actualizarEstadoGeneracionCertificado($arrayParametros)
    {
        $idCertificadoFitosanitario = $arrayParametros['id_certificado_fitosanitario'];
        $certificado = $arrayParametros['certificado'];

        $consulta = "UPDATE
                         g_certificado_fitosanitario.certificado_fitosanitario
                     SET
                       certificado = '" . $certificado . "'
                     WHERE
                        id_certificado_fitosanitario = '" . $idCertificadoFitosanitario . "';";

        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Función para crear el PDF del certificado
     */
    public function generarCertificado($idSolicitud, $nombreArchivo, $rutaFechaCertificado, $nombreInspector, $provinciaInspector)
    {
        $jasper = new JasperReport();
        $datosReporte = array();
        $ruta = CERT_FITO_CERT_URL_TCPDF . 'certificados/' . $rutaFechaCertificado;
        
        //---local---//
        /*$rutaCertificado = 'http://localhost/agrodbPrueba/' . CERT_FITO_URL . 'certificados/' . $rutaFechaCertificado;
        $rutaAnexo = 'http://localhost/agrodbPrueba/' . CERT_FITO_URL . 'anexos/' . $rutaFechaCertificado;*/
        
        //---Pruebas---//
        $rutaCertificado = 'http://181.112.155.173/agrodbPrueba/' . CERT_FITO_URL . 'certificados/' . $rutaFechaCertificado;
        $rutaAnexo = 'http://181.112.155.173/agrodbPrueba/' . CERT_FITO_URL . 'anexos/' . $rutaFechaCertificado;
        
        /*//---Produccion---//
         $rutaCertificado = 'https://guia.agrocalidad.gob.ec/agrodbPrueba/' . CERT_FITO_URL . 'certificados/' . $rutaFechaCertificado;
         $rutaAnexo = 'https://guia.agrocalidad.gob.ec/agrodbPrueba/' . CERT_FITO_URL . 'anexos/' . $rutaFechaCertificado;*/

        if (! file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }
        
        $datosReporte = array(
            'rutaReporte' => 'CertificadoFitosanitario/vistas/reportes/CertificadoFitosanitario.jasper',
            'rutaSalidaReporte' => 'CertificadoFitosanitario/archivos/certificados/' . $rutaFechaCertificado . 'C' . $nombreArchivo,
            'tipoSalidaReporte' => array(
                'pdf'
            ),
            'parametrosReporte' => array(
                'idSolicitud' => (int) $idSolicitud,
                'nombreInspector' => $nombreInspector,
                'lugarExpedicion' => $provinciaInspector,
                'fondoCertificado' => RUTA_IMG_GENE . 'fondoCertificadoCFE.png',
                'rutaCertificado' => $rutaCertificado . 'C' . $nombreArchivo . '.pdf',
                'rutaAnexo' => $rutaAnexo . 'A' . $nombreArchivo . '.pdf'
            ),
            'conexionBase' => 'SI'
        );

        $jasper->generarArchivo($datosReporte);
    }

    /**
     * Función para crear el PDF del anexo
     */
    public function generarAnexo($idSolicitud, $nombreArchivo, $rutaFechaCertificado, $nombreInspector, $provinciaInspector)
    {
        $jasper = new JasperReport();
        $datosReporte = array();

        $ruta = CERT_FITO_CERT_URL_TCPDF . 'certificados/' . $rutaFechaCertificado;
        
        if (! file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        $datosReporte = array(
            'rutaReporte' => 'CertificadoFitosanitario/vistas/reportes/AnexoCertificadoFitosanitario.jasper',
            'rutaSalidaReporte' => 'CertificadoFitosanitario/archivos/certificados/' . $rutaFechaCertificado . 'A' . $nombreArchivo,
            'tipoSalidaReporte' => array(
                'pdf'
            ),
            'parametrosReporte' => array(
                'idSolicitud' => (int) $idSolicitud,
                'nombreInspector' => $nombreInspector,
                'lugarExpedicion' => $provinciaInspector,
                'fondoCertificadoHorizontal' => RUTA_IMG_GENE . 'fondoCertificadoCFEHorizontal.png'
            ),
            'conexionBase' => 'SI'
        );

        $jasper->generarArchivo($datosReporte);
    }

    /**
     * Funcion para generar el numero de certificado por operador
     */
    function generarCodigoCertificadoRandom($numeroDigitos)
    {
        $codigo = str_pad(mt_rand(0, pow(10, $numeroDigitos)), $numeroDigitos, '0', STR_PAD_LEFT);

        return $codigo . 'P';
    }

    /**
     * Funcion para guardar desestimiento de certificado fitosanitario
     */
    function guardarEstadoCertificadoFitosanitarioDesestimiento(Array $datos)
    {
        try {
           
            $datos['estado_certificado'] = 'Anulado';
            $datos['fecha_modificacion_certificado'] = 'now()';

            $tablaModelo = new CertificadoFitosanitarioModelo($datos);

            $procesoIngreso = $this->modeloCertificadoFitosanitario->getAdapter()
                ->getDriver()
                ->getConnection();
            $procesoIngreso->beginTransaction();

            $datosBd = $tablaModelo->getPrepararDatos();

            $this->modeloCertificadoFitosanitario->actualizar($datosBd, $tablaModelo->getIdCertificadoFitosanitario());
            $idCertificadoFitosanitario = $tablaModelo->getIdCertificadoFitosanitario();

            $datosActualizacionProducto = array(
                'estado_exportador_producto' => 'Anulado'
            );

            $statement = $this->modeloExportadoresProductos->getAdapter()
                ->getDriver()
                ->createStatement();
            $sqlActualizar = $this->modeloExportadoresProductos->actualizarSql('exportadores_productos', $this->modeloExportadoresProductos->getEsquema());
            $sqlActualizar->set($datosActualizacionProducto);
            $sqlActualizar->where(array(
                'id_certificado_fitosanitario' => $idCertificadoFitosanitario
            ));
            $sqlActualizar->prepareStatement($this->modeloExportadoresProductos->getAdapter(), $statement);
            $statement->execute();

            $procesoIngreso->commit();
            return $idCertificadoFitosanitario;
            
        } catch (GuardarExcepcion $ex) {
            $procesoIngreso->rollback();
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para actualizar el
     * estado de una solicitud
     *
     * @return array|ResultSet
     */
    public function actualizarEstadoCertificado($arrayParametros)
    {
        $idCertificadoFitosanitario = $arrayParametros['id_certificado_fitosanitario'];
        $estadoCertificado = $arrayParametros['estado_certificado'];

        $consulta = "UPDATE
                         g_certificado_fitosanitario.certificado_fitosanitario
                     SET
                       estado_certificado = '" . $estadoCertificado . "'
                     WHERE
                        id_certificado_fitosanitario = '" . $idCertificadoFitosanitario . "';";

        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener
     * los datos del catalogo de idiomas
     *
     * @return array|ResultSet
     */
    public function obtenerDatosIdioma($idIdioma)
    {
        $consulta = "SELECT
                        id_idioma
                        , codigo_idioma
                        , nombre_idioma
                        , nombre_idioma_ingles
                        , estado_idioma
                     FROM
                        g_catalogos.idiomas
                     WHERE
                        id_idioma = '" . $idIdioma . "';";

        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /*
     * Proceso de gereracion de XML Ephyto HUB
     */
    function procesoGenerarXmlWebServicesCertificadosFitosanitario()
    {
        $lNegocioConfiguracionFitosanitario = new ConfiguracionFitosanitarioLogicaNegocio();

        $arrayParametros = array(
            'tipo_configuracion_fitosanitario' => 'emision',
            'plataforma_fitosanitario' => 'hub'
        );

        $paisConfiguracionFitosanitario = $lNegocioConfiguracionFitosanitario->buscarLista($arrayParametros);

        echo Constantes::IN_MSG . 'Obtención de configuración de envió ephyto\n';

        $idPaiesDestino = null;

        foreach ($paisConfiguracionFitosanitario as $pais) {
            $idPaiesDestino .= $pais['id_localizacion_fitosanitario'] . ', ';
        }

        echo Constantes::IN_MSG . 'Actualización de certificados por enviar HUB\n';

        $idPaiesDestino = " (" . rtrim($idPaiesDestino, ', ') . ") ";

        $arrayParametros = array(
            'id_pais_destino' => $idPaiesDestino,
            'condicion' => ' IN ',
            'estado' => 'Por atender'
        );

        $this->actualizarEstadoEphytoCertificadoFitosanitario($arrayParametros);

        echo Constantes::IN_MSG . 'Actualización de certificados que no se envian por HUB\n';

        $arrayParametros = array(
            'id_pais_destino' => $idPaiesDestino,
            'condicion' => ' NOT IN ',
            'estado' => 'No aplica config'
        );

        $this->actualizarEstadoEphytoCertificadoFitosanitario($arrayParametros);

        echo Constantes::IN_MSG . 'Generación archivo XML Certificado Fitosanitario\n';

        $this->obtenerCertificadosFitosanitarioPorEnviarEphyto();
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para actualizar el
     * estado de un certificado para envio ephyto
     *
     * @return array|ResultSet
     */
    public function actualizarEstadoEphytoCertificadoFitosanitario($arrayParametros)
    {
        $idPaisDestino = $arrayParametros['id_pais_destino'];
        $condicion = $arrayParametros['condicion'];
        $estado = $arrayParametros['estado'];

        $consulta = "UPDATE
                         g_certificado_fitosanitario.certificado_fitosanitario
                     SET
                       estado_ephyto = '$estado'
                     WHERE
                        id_pais_destino $condicion $idPaisDestino
						and estado_certificado = 'Aprobado'
						and estado_ephyto is null;";

        return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
    }

    /**
     * Obtención de certificado fitosanitario para envio por Ephyto
     */
    public function obtenerCertificadosFitosanitarioPorEnviarEphyto()
    {
        echo Constantes::IN_MSG . 'Obtencion de certificado Fitosanitario para generación de  archivo XML\n';

        $arrayParametros = array(
            'estado_ephyto' => 'Por atender',
            'estado_certificado' => 'Aprobado'
        );

        $certificadoFitosanitario = $this->buscarLista($arrayParametros);

        $certificadoFitosanitarioEphyto = new CertificadoFitosanitarioEphytoLogicaNegocio();
        $coneccion = $certificadoFitosanitarioEphyto->coneccionWebServicesEphyto();

        foreach ($certificadoFitosanitario as $certificado) {

            echo Constantes::IN_MSG . 'Certificado fitosanitario ' . $certificado['codigo_certificado'] . '\n';

            echo Constantes::IN_MSG . 'Actualziar estado envio de XML Certificado fitosanitario Ephyto HUB a W\n';

            $arrayParametros = array(
                'estado_ephyto' => 'W',
                'id_certificado_fitosanitario' => $certificado['id_certificado_fitosanitario']
            );

            $this->actualizar($arrayParametros);

            $datosCertificado = $this->generarXMLCertificadosFitosanitarioPorEnviarEphyto($certificado);

            echo Constantes::IN_MSG . 'Guardado de XML Certificado fitosanitario Adjunto\n';

            $arrayParametros = array(
                'id_certificado_fitosanitario' => $certificado['id_certificado_fitosanitario'],
                'tipo_adjunto' => 'XML Ephyto'
            );

            $verificarAdjunto = $this->lNegocioDocumentosAdjuntos->buscarLista($arrayParametros);

            if ($verificarAdjunto->count()) {
                $arrayParametros += array(
                    'ruta_adjunto' => $datosCertificado['ruta_xml'],
                    'estado_adjunto' => 'Activo',
                    'id_documento_adjunto' => $verificarAdjunto->current()->id_documento_adjunto
                );
            } else {
                $arrayParametros += array(
                    'ruta_adjunto' => $datosCertificado['ruta_xml'],
                    'estado_adjunto' => 'Activo'
                );
            }

            $this->lNegocioDocumentosAdjuntos->guardar($arrayParametros);

            echo Constantes::IN_MSG . 'Envio de XML Certificado fitosanitario Ephyto HUB\n';

            $arrayParametros = array(
                'pais_origen' => $datosCertificado['pais_origen'],
                'pais_destino' => $datosCertificado['pais_destino'],
                'numero_certificado' => $datosCertificado['numero_certificado'],
                'tipo_certificado' => '851',
                'estado_certificado' => '70',
                'contenido_xml' => $datosCertificado['contenido_xml']
            );

            $certificadoFitosanitarioEphyto->envioEphyto($coneccion, $arrayParametros);

            echo Constantes::IN_MSG . 'Actualizar estado envio de XML Certificado fitosanitario Ephyto HUB\n';

            $arrayParametros = array(
                'estado_ephyto' => 'Enviado',
                'id_certificado_fitosanitario' => $certificado['id_certificado_fitosanitario']
            );

            $this->actualizar($arrayParametros);
        }
    }

    /**
     * Generación de archivo XML certificado fitosanitario para envio por Ephyto
     */
    public function generarXMLCertificadosFitosanitarioPorEnviarEphyto($certificado)
    {
        $comun = new \Agrodb\Core\Comun();

        $certificadoFitosanitarioCabecera = array();
        $certificadoFitosanitario = array();

        $numeroCertificado = $certificado['codigo_certificado'];
        $fechaVigencia = date('c', strtotime($certificado['fecha_creacion_certificado'])); // TODO:Verificar esta fecha
        $informacionAdicional = ($certificado['informacion_adicional'] == '' ? 'Sin información' : $certificado['informacion_adicional']);
        $fechaInspeccion = date('c', strtotime($certificado['fecha_revision']));
        $fechaFinVigencia = date('c', strtotime($certificado['fecha_creacion_certificado'])); // TODO:Verificar esta fecha
        $nombreCiudad = $certificado['nombre_provincia_origen']; // TODO: Verificar este campo
        $nombreTecnicoAprobador = $certificado['identificador_revision']; // TODO: Verificar este campo -> ya esta es el de revision documental
        $nombreConsignatario = $certificado['nombre_consignatario'];
        $direccionConsignatario = $certificado['direccion_consignatario'];
        $provinciaOrigen = $certificado['nombre_provincia_origen'];
        $rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');

        $parametrosConsulta = array(
            'id_certificado_fitosanitario' => $certificado['id_certificado_fitosanitario']
        );

        $datosProductoExportador = $this->lNegocioExportadoresProductos->buscarLista($parametrosConsulta);

        $paisOrigen = $this->lNegocioLocalizacion->buscarLista(array(
            'id_localizacion' => $certificado['id_pais_origen']
        ));
		
        $paisDestino = $this->lNegocioLocalizacion->buscarLista(array(
            'id_localizacion' => $certificado['id_pais_destino']
        ));

        $puertoDestino = $this->lNegocioPuertosDestino->buscarLista($parametrosConsulta);

        $medioTransporte = $this->lNegocioMediosTransporte->buscarLista(array(
            'id_medios_transporte' => $certificado['id_medio_transporte']
        ));

        $arrayExportadores = array();
        $certificadoFitosanitarioProductos = array(
            'ram:IncludedSPSConsignmentItem' => array(
                'ram:IncludedSPSTradeLineItem' => array()
            )
        );
        $contador = 1;
        foreach ($datosProductoExportador as $productoExportador) {

            $arrayExportadores[] = array(
                'nombre_exportador' => $productoExportador['razon_social_exportador'],
                'direccion_exportador' => $productoExportador['direccion_exportador']
            );

            $fechaInspeccionProducto = date('c', strtotime($productoExportador['fecha_inspeccion']));
            $fechaTratamientoProducto = date('Y-m-d', strtotime($productoExportador['fecha_tratamiento']));
            $cantidadComercial = $productoExportador['cantidad_comercial'];
            $duracionTratamiento = $productoExportador['duracion_tratamiento'];
            $temperaturaTratamiento = $productoExportador['temperatura_tratamiento'];
            $productoQuimico = $productoExportador['producto_quimico'];
            $nombreTipoTratamiento = $productoExportador['nombre_tipo_tratamiento'];
            $tipoTratamiento = $productoExportador['nombre_tratamiento'];

            $producto = $this->lNegocioProductos->buscar($productoExportador['id_producto']);
            
            $unidadMedidaPeso = $this->lNegocioCfeUnidadesMedidas->buscarLista(array(
                'codigo' => 'KG'//$productoExportador['id_unidad_peso_bruto']//'KG'
            )); // TODO:Pedir a Milton que guarde la unidad de medida en mayuscula
            
            $unidadMedidaCantidadComercial = $this->lNegocioCfeUnidadesMedidas->buscar($productoExportador['id_unidad_cantidad_comercial']);

            if ($productoExportador['id_tratamiento'] != '') {
                $unidadDuracionTratamiento = $this->lNegocioUnidadesDuracion->buscar($productoExportador['id_tratamiento']);
            } else {
                $unidadDuracionTratamiento = new UnidadesDuracionModelo();
            }

            if ($productoExportador['id_unidad_temperatura'] != '') {
                $unidadTemperaturaTratamiento = $this->lNegocioUnidadesTemperatura->buscar($productoExportador['id_unidad_temperatura']);
            } else {
                $unidadTemperaturaTratamiento = new UnidadesTemperaturaModelo();
            }

            $productoCertificado = array(
                'ram:SequenceNumeric' => $contador ++,
                'ram:Description' => 'Ninguno',
                'ram:CommonName' => array(
                    '_attributes' => array(
                        'languageID' => 'es'
                    ),
                    '_value' => $productoExportador['nombre_producto']
                ),
                'ram:ScientificName' => $producto->getNombreCientifico(),
                'ram:IntendedUse' => array(
                    '_attributes' => array(
                        'languageID' => 'es'
                    ),
                    '_value' => 'Ninguno'
                ),
                'ram:NetWeightMeasure' => array(
                    '_attributes' => array(
                        'unitCode' => $unidadMedidaPeso->current()->codigo_hub
                    ),
                    '_value' => $productoExportador['peso_neto']
                ),
                'ram:NetVolumeMeasure' => array(
                    '_attributes' => array(
                        'unitCode' => $unidadMedidaCantidadComercial->getCodigoHub()
                    ),
                    '_value' => $cantidadComercial
                ),
                'ram:AdditionalInformationSPSNote' => array(
                    array(
                        'ram:Subject' => 'ADTLIL',
                        'ram:Content' => array(
                            '_attributes' => array(
                                'languageID' => 'es'
                            ),
                            '_value' => $informacionAdicional // TODO:Preguntar este campo a que se refiere -> Es la información adicional
                        )
                    ),
                    array(
                        'ram:Subject' => 'ADDITLIL',
                        'ram:Content' => $fechaInspeccionProducto
                    ) // :TODO pereguntar para que productos no mas se ingresa // TODO: Verificar el tipo de solicitud si pasa por indpeccion
                ),
                'ram:ApplicableSPSClassification' => array(
                    array(
                        'ram:SystemName' => 'IPPCPCVP',
                        'ram:ClassName' => array(
                            '_attributes' => array(
                                'languageID' => 'es'
                            ),
                            '_value' => $productoExportador['nombre_tipo_producto']
                        )
                    )
                ),
                'ram:PhysicalSPSPackage' => array(
                    array(
                        'ram:LevelCode' => '1',
                        'ram:TypeCode' => 'NA',
                        'ram:ItemQuantity' => $cantidadComercial
                    )
                ),
                'ram:OriginSPSCountry' => array(
                    array(
                        'ram:ID' => $paisOrigen->current()->codigo,
                        'ram:Name' => $paisOrigen->current()->nombre,
                        'ram:SubordinateSPSCountrySubDivision' => array(
                            'ram:Name' => $provinciaOrigen,
                            'ram:HierarchicalLevelCode' => '0'
                        )
                    )
                ),
                'ram:AppliedSPSProcess' => array(
                    'ram:TypeCode' => 'ZZZ',
                    'ram:ApplicableSPSProcessCharacteristic' => array(
                        // array('ram:Description' => 'TTCO', 'ram:ValueMeasure' => $productoQuimico), TODO: Verificar este campo se debe enviar como numero en guia se ingresa como caracter.
                        array(
                            'ram:Description' => array(
                                array(
                                    '_value' => 'TTFT'
                                ),
                                array(
                                    '_attributes' => array(
                                        'languageID' => 'es'
                                    ),
                                    '_value' => 'Fecha: ' . $fechaTratamientoProducto . '; Tipo: ' . $nombreTipoTratamiento . '; Duración: ' . $duracionTratamiento . ' ' . $unidadDuracionTratamiento->getNombreUnidadDuracion() . '; Temperatura: ' . $temperaturaTratamiento . ' ' . $unidadTemperaturaTratamiento->getNombreUnidadTemperatura() . '; Concentración: ' . $productoQuimico . '; Información Adicional: No hay información adicional disponible'
                                )
                            )
                        )
                    )
                )
            );

            if ($productoExportador['peso_bruto'] != '') {
                $pesoBruto = array(
                    'ram:GrossWeightMeasure' => array(
                        '_attributes' => array(
                            'unitCode' => $unidadMedidaPeso->current()->codigo_hub
                        ),
                        '_value' => $productoExportador['peso_bruto']
                    )
                );

                $comun->insertarElementoArrayPosicion($productoCertificado, 'ram:NetVolumeMeasure', $pesoBruto);
            }

            if ($duracionTratamiento != '') {
                $aDuracionTramiento = array(
                    'ram:CompletionSPSPeriod' => array(
                        'ram:StartDateTime' => array(
                            'udt:DateTimeString' => $fechaTratamientoProducto
                        ),
                        'ram:EndDateTime' => array(
                            'udt:DateTimeString' => $fechaTratamientoProducto
                        ),
                        'ram:DurationMeasure' => array(
                            '_attributes' => array(
                                'unitCode' => $unidadDuracionTratamiento->getCodigoUnidadDuracion()
                            ),
                            '_value' => $duracionTratamiento
                        )
                    )
                );

                $comun->insertarElementoArrayPosicion($productoCertificado['ram:AppliedSPSProcess'], 'ram:ApplicableSPSProcessCharacteristic', $aDuracionTramiento);
            } else {
                $aDuracionTramiento = array(
                    'ram:CompletionSPSPeriod' => array(
                        'ram:StartDateTime' => array(
                            'udt:DateTimeString' => $fechaTratamientoProducto
                        ),
                        'ram:EndDateTime' => array(
                            'udt:DateTimeString' => $fechaTratamientoProducto
                        )
                    )
                );

                $comun->insertarElementoArrayPosicion($productoCertificado['ram:AppliedSPSProcess'], 'ram:ApplicableSPSProcessCharacteristic', $aDuracionTramiento);
            }

            if ($temperaturaTratamiento != '') {
                $aTemperaturaTratamiento = array(
                    array(
                        'ram:Description' => 'TTTM',
                        'ram:ValueMeasure' => array(
                            '_attributes' => array(
                                'unitCode' => $unidadTemperaturaTratamiento->getCodigoUnidadTemperatura()
                            ),
                            '_value' => $temperaturaTratamiento
                        )
                    )
                );

                $comun->insertarElementoArrayPosicion($productoCertificado['ram:AppliedSPSProcess']['ram:ApplicableSPSProcessCharacteristic'], '1', $aTemperaturaTratamiento);
            }

            array_push($certificadoFitosanitarioProductos['ram:IncludedSPSConsignmentItem']['ram:IncludedSPSTradeLineItem'], $productoCertificado);
        }

        $arrayExportadores = array_map("unserialize", array_unique(array_map("serialize", $arrayExportadores)));
        $nombresExportador = implode(', ', array_column($arrayExportadores, 'nombre_exportador'));
        $direccionesExportador = implode(', ', array_column($arrayExportadores, 'direccion_exportador'));

        $certificadoFitosanitarioCabecera = array(
            'rootElementName' => 'rsm:SPSCertificate',
            '_attributes' => array(
                'xmlns:udt' => 'urn:un:unece:uncefact:data:standard:UnqualifiedDataType:21',
                'xmlns:ram' => 'urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:21',
                'xmlns:rsm' => 'urn:un:unece:uncefact:data:standard:SPSCertificate:17'
            )
        );

        $certificadoFitosanitario = array(
            'rsm:SPSExchangedDocument' => array(
                'ram:Name' => 'CERTIFICADO FITOSANITARIO DE EXPORTACIÓN',
                'ram:ID' => $numeroCertificado,
                'ram:TypeCode' => '851',
                'ram:StatusCode' => '70',
                'ram:IssueDateTime' => array(
                    'udt:DateTimeString' => $fechaVigencia
                ),
                'ram:IssuerSPSParty' => array(
                    'ram:Name' => 'Organización de Protección Fitosanitaria de Ecuador'
                ),
                'ram:IncludedSPSNote' => array(
                    array(
                        'ram:Subject' => 'SPSFL',
                        'ram:Content' => '5'
                    ),
                    array(
                        'ram:Subject' => 'ADEDL',
                        'ram:Content' => array(
                            '_attributes' => array(
                                'languageID' => 'es'
                            ),
                            '_value' => $informacionAdicional
                        )
                    ),
                    array(
                        'ram:Subject' => 'ADDIEDL',
                        'ram:Content' => $fechaInspeccion
                    )
                ),
                'ram:SignatorySPSAuthentication' => array(
                    'ram:ActualDateTime' => array(
                        'udt:DateTimeString' => $fechaFinVigencia
                    ),
                    'ram:IssueSPSLocation' => array(
                        'ram:Name' => $nombreCiudad
                    ),
                    'ram:ProviderSPSParty' => array(
                        'ram:Name' => 'Ninguno',
                        'ram:SpecifiedSPSPerson' => array(
                            'ram:Name' => $nombreTecnicoAprobador
                        )
                    ),
                    'ram:IncludedSPSClause' => array(
                        'ram:ID' => '1',
                        'ram:Content' => 'Por la presente se certifica que las plantas, productos vegetales u otros artículos reglamentados descritos aquí se han inspeccionado y/o sometido a ensayo de acuerdo con los procedimientos oficiales adecuados y se considera que están libres de las plagas cuarentenarias especificadas por la parte contrante importadora y que cumplan los requisitos fitosanitarios vigentes de la parte contratante importadora, incluidos los relativos a las plagas no cuarentenarias reglamentadas.'
                    )
                )
            ),
            'rsm:SPSConsignment' => array(
                'ram:ConsignorSPSParty' => array(
                    'ram:Name' => $nombresExportador,
                    'ram:SpecifiedSPSAddress' => array(
                        'ram:LineOne' => $direccionesExportador
                    )
                ),
                'ram:ConsigneeSPSParty' => array(
                    'ram:Name' => $nombreConsignatario,
                    'ram:SpecifiedSPSAddress' => array(
                        'ram:LineOne' => $direccionConsignatario
                    )
                ),
                'ram:ExportSPSCountry' => array(
                    'ram:ID' => $paisOrigen->current()->codigo,
                    'ram:Name' => $paisOrigen->current()->nombre
                ),
                'ram:ImportSPSCountry' => array(
                    'ram:ID' => $paisDestino->current()->codigo,
                    'ram:Name' => $paisDestino->current()->nombre
                ),
                'ram:UnloadingBaseportSPSLocation' => array(
                    'ram:Name' => $puertoDestino->current()->nombre_puerto_pais_destino
                ),
                'ram:ExaminationSPSEvent' => array(
                    'ram:OccurrenceSPSLocation' => array(
                        'ram:Name' => 'Ninguno'
                    )
                ),
                'ram:MainCarriageSPSTransportMovement' => array(
                    'ram:ModeCode' => $medioTransporte->current()->codigo_hub,
                    'ram:UsedSPSTransportMeans' => array(
                        'ram:Name' => array(
                            '_attributes' => array(
                                'languageID' => 'es'
                            ),
                            '_value' => $medioTransporte->current()->tipo
                        )
                    )
                )
            )
        );

        $certificadoFitosanitario['rsm:SPSConsignment'] += $certificadoFitosanitarioProductos;

        $resultoXml = ArrayToXml::convert($certificadoFitosanitario, $certificadoFitosanitarioCabecera, true, 'UTF-8', '1.0', [
            'formatOutput' => true
        ]);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = TRUE;
        $dom->loadXML($resultoXml);

        $rutaDominio = Constantes::RUTA_SERVIDOR_OPT . '/' . Constantes::RUTA_APLICACION . '/';
        $rutaArchivo = 'aplicaciones/mvc/modulos/CertificadoFitosanitario/archivos/certificados/' . $rutaFecha . '/';
        $nombreArchivo = $numeroCertificado . '.xml';

        if (! file_exists($rutaDominio . $rutaArchivo)) {
            mkdir($rutaDominio . $rutaArchivo, 0777, true);
        }
        // Save XML as a file
        $dom->save($rutaDominio . $rutaArchivo . $nombreArchivo);

        return array(
            'contenido_xml' => $resultoXml,
            'pais_origen' => $paisOrigen->current()->codigo,
            'pais_destino' => $paisDestino->current()->codigo,
            'numero_certificado' => $numeroCertificado,
            'ruta_xml' => $rutaArchivo . $nombreArchivo
        );
    }

    public function actualizar(Array $datos)
    {
        $tablaModelo = new CertificadoFitosanitarioModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();

        if ($tablaModelo->getIdCertificadoFitosanitario() != null && $tablaModelo->getIdCertificadoFitosanitario() > 0) {
            $this->modeloCertificadoFitosanitario->actualizar($datosBd, $tablaModelo->getIdCertificadoFitosanitario());
        }
    }

    /**
     * Ejecuta un reporte en Excel con los datos de los productos de un subtipo de producto selecionado
     *
     * @return array|ResultSet
     */
    public function exportarArchivoProductos($datos)
    {
        $hoja = new Spreadsheet();
        $documento = $hoja->getActiveSheet();
        $i = 3;

        $estiloArrayTitulo = [
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ],
            'font' => [
                'name' => 'Calibri',
                'bold' => true,
                'size' => 18
            ]
        ];

        $estiloArrayCabecera = [
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => [
                        'argb' => 'FF000000'
                    ]
                ]
            ],
            'font' => [
                'name' => 'Calibri',
                'bold' => true,
                'size' => 11,
                'color' => [
                    'argb' => 'FFFFFFFF'
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FF6495ED'
                ],
                'endColor' => [
                    'argb' => 'FF6495ED'
                ]
            ]
        ];

        $documento->getStyle('A1:D1')->applyFromArray($estiloArrayTitulo);
        $documento->getStyle('A2:D2')->applyFromArray($estiloArrayCabecera);

        $documento->setCellValueByColumnAndRow(1, 1, 'Reporte de productos');
        $documento->mergeCells('A1:D1');
        $documento->getColumnDimension('A')->setAutoSize(true);
        $documento->getColumnDimension('B')->setAutoSize(true);
        $documento->getColumnDimension('C')->setAutoSize(true);
        $documento->getColumnDimension('D')->setAutoSize(true);

        $documento->setCellValue('A2', 'N°');
        $documento->setCellValue('B2', 'Nombre subtipo producto');
        $documento->setCellValue('C2', 'Nombre producto');
        $documento->setCellValue('D2', 'Partida arancelaria');

        if ($datos != '') {

            $contador = 1;

            foreach ($datos as $fila) {
                $documento->setCellValueByColumnAndRow(1, $i, $contador ++);
                $documento->setCellValueByColumnAndRow(2, $i, $fila['nombre']);
                $documento->setCellValueByColumnAndRow(3, $i, $fila['nombre_comun']);
                $documento->getCellByColumnAndRow(4, $i)->setValueExplicit($fila['partida_arancelaria'], 's');
                $i ++;
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporteProductos.xlsx"');
        header("Pragma: no-cache");
        header("Expires: 0");

        $writer = IOFactory::createWriter($hoja, 'Xlsx');
        $writer->save('php://output');
	    exit();
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarCertificadoFitosanitarioPorCodigoCertificadoPorEstado($arrayParametros)
	{
	    $codigoCertificado = $arrayParametros['codigo_certificado'];
	    $estadoCertificado = $arrayParametros['estado_certificado'];
	    
	    $consulta = "SELECT 
                    	cf.id_certificado_fitosanitario
                    	, cf.codigo_certificado
                    	, TO_CHAR((CASE
                    		WHEN cf.tipo_certificado = 'musaceas'THEN cf.fecha_embarque
                    		ELSE cf.fecha_aprobacion_certificado
                    		END), 'YYYY-MM-DD') as fecha_emision
                    	, cf.identificador_solicitante
                    	, CASE WHEN o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social END nombre_operador
                    	, cf.estado_certificado
                    	, (l.nombre || ' / ' || l.nombre_ingles) AS nombre_pais_destino
                    	, STRING_AGG(DISTINCT(ep.nombre_producto), ', ') AS nombre_producto
                    	, STRING_AGG(DISTINCT(da.ruta_adjunto), ', ') AS ruta_adjunto
                    FROM 
                    	g_certificado_fitosanitario.certificado_fitosanitario cf
                    	INNER JOIN g_certificado_fitosanitario.exportadores_productos ep ON cf.id_certificado_fitosanitario = ep.id_certificado_fitosanitario
                    	INNER JOIN g_operadores.operadores o ON cf.identificador_solicitante = o.identificador
                    	INNER JOIN g_certificado_fitosanitario.documentos_adjuntos da ON cf.id_certificado_fitosanitario = da.id_certificado_fitosanitario --AND tipo_adjunto = 'Certificado Anexo Fitosanitario'
                    	INNER JOIN g_catalogos.localizacion l ON cf.id_pais_destino = l.id_localizacion
                    WHERE 
                    	cf.codigo_certificado = '" . $codigoCertificado . "'
                        and cf.estado_certificado = '" . $estadoCertificado . "'
						and da.tipo_adjunto IN ('Certificado Fitosanitario', 'Anexo Certificado')												 
                    	GROUP BY cf.id_certificado_fitosanitario, cf.codigo_certificado, fecha_emision , cf.identificador_solicitante, nombre_operador, cf.estado_certificado, l.nombre, l.nombre_ingles;";
	    
	    return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el estado siguiente
	 * de acuerdo a la subsanacion
	 *
	 * @return array|ResultSet
	 */
	public function verificarCambioEstadoSolicitudSubsanacion($arrayParametros)
	{
	    
	    $idCertificadoFitosanitario = $arrayParametros['id_certificado_fitosanitario'];
	    $estadoCertificado = $arrayParametros['estado_certificado'];
	    
	    $consulta = "SELECT * FROM g_certificado_fitosanitario.f_verificar_cambio_estado_subsanacion(" . $idCertificadoFitosanitario . ", '" . $estadoCertificado . "');";
	    
	    return $this->modeloCertificadoFitosanitario->ejecutarSqlNativo($consulta);
	}
	
}