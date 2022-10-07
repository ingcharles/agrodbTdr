<?php
/**
 * Lógica del negocio de RevisionesDocumentalesModelo
 *
 * Este archivo se complementa con el archivo RevisionesDocumentalesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    RevisionesDocumentalesLogicaNegocio
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoFitosanitario\Modelos;

use Agrodb\CertificadoFitosanitario\Modelos\IModelo;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioLogicaNegocio;
// use Agrodb\CertificadoFItosanitario\Modelos\ExportadoresProductosModelo;
use Agrodb\Core\Excepciones\GuardarExcepcion;

class RevisionesDocumentalesLogicaNegocio implements IModelo
{

    private $modeloRevisionesDocumentales = null;

    private $lNegocioExportadoresProductos = null;

    private $lNegocioCertificadoFitosanitario = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloRevisionesDocumentales = new RevisionesDocumentalesModelo();
        $this->lNegocioExportadoresProductos = new ExportadoresProductosLogicaNegocio();
        $this->lNegocioCertificadoFitosanitario = new CertificadoFitosanitarioLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        
        $estadoRevision = $_POST['estado_revision'];
        
        switch ($estadoRevision) {

            case 'DevueltoTecnico':
                
                {
                    
                    try {
                        
                        $procesoIngreso = $this->modeloRevisionesDocumentales->getAdapter()
                        ->getDriver()
                        ->getConnection();
                        $procesoIngreso->beginTransaction();
                 
                        // Guarda registro de la revisión para una provincia, centro de acopio y producto específico
                        if (count($_POST['iProvincia']) > 0) {
                            
                            // Guardar los resultados de revisión documental
                            for ($i = 0; $i < count($_POST['iProvincia']); $i ++) {
                                
                                // Buscar el número de revisión documental para la provincia, área y producto que realizó un inspector
                                $numeroRevision = $this->generarNumeroRevisionDocumental($_POST['id_solicitud'], $_SESSION['usuario'], $_POST['iProvincia'][$i], $_POST['iAreaInspeccion'][$i], $_POST['iProducto'][$i]);
                                
                                $arrayParametrosRevisionDocumental = array(
                                    'identificador_inspector' => $_SESSION['usuario'],
                                    'id_provincia_revision' => $_SESSION['idProvincia'],
                                    'provincia_revision' => $_SESSION['nombreProvincia'],
                                    'id_solicitud' => $_POST['id_solicitud'],
                                    'tipo_solicitud' => $_POST['tipo_certificado'],
                                    'id_provincia_inspeccion' => $_POST['iProvincia'][$i],
                                    'provincia_inspeccion' => $_POST['nProvincia'][$i],
                                    'id_area_inspeccion' => $_POST['iAreaInspeccion'][$i],
                                    'nombre_area_inspeccion' => $_POST['nAreaInspeccion'][$i],
                                    'id_producto_inspeccion' => $_POST['iProducto'][$i],
                                    'nombre_producto_inspeccion' => $_POST['nProducto'][$i],
                                    'observacion_revision' => $_POST['iObservacion'][$i],
                                    'num_revision' => $numeroRevision,
                                    'estado' => $_POST['iEstado'][$i]
                                );
                                
                                $statement = $this->modeloRevisionesDocumentales->getAdapter()
                                ->getDriver()
                                ->createStatement();
                                                                
                                $sqlInsertar = $this->modeloRevisionesDocumentales->guardarSql('revisiones_documentales', $this->modeloRevisionesDocumentales->getEsquema());
                                $sqlInsertar->columns(array_keys($arrayParametrosRevisionDocumental));
                                $sqlInsertar->values($arrayParametrosRevisionDocumental, $sqlInsertar::VALUES_MERGE);
                                $sqlInsertar->prepareStatement($this->modeloRevisionesDocumentales->getAdapter(), $statement);
                                $statement->execute();
                                $procesoGuardar = true;                                
                                
                                if ($procesoGuardar) {
                                    $statement2 = $this->modeloRevisionesDocumentales->getAdapter()
                                    ->getDriver()
                                    ->createStatement();
                                    
                                    $datosActualizacionExportadoresProductos = array('estado_exportador_producto' => $_POST['iEstado'][$i],
                                                                                        'tipo_revision' => 'Documental'
                                                                                    );
                                           
                                    $sqlActualizar = $this->modeloRevisionesDocumentales->actualizarSql('exportadores_productos', $this->modeloRevisionesDocumentales->getEsquema());
                                    $sqlActualizar->set($datosActualizacionExportadoresProductos);
                                    $sqlActualizar->where(array('id_certificado_fitosanitario' => $_POST['id_solicitud'] ,'id_area' => $_POST['iAreaInspeccion'][$i], 'id_provincia_area' => $_POST['iProvincia'][$i], 'id_producto' => $_POST['iProducto'][$i]));
                                    $sqlActualizar->prepareStatement($this->modeloRevisionesDocumentales->getAdapter(), $statement2);
                                    $statement2->execute();
                                    
                                    //Libera el formulario de inspeccionn para poder utilizarlo nuevemante
                                    if($_POST['iEstado'][$i] == "DevueltoTecnico"){
                                        
                                        $arrayDatosRevisionDocumental = array('id_solicitud' => $_POST['id_solicitud']
                                                                                , 'id_area_inspeccion' => $_POST['iAreaInspeccion'][$i]
                                                                                , 'id_producto_inspeccion' => $_POST['iProducto'][$i]
                                                                              );
                                                                               
                                        $datosInspeccionTablet = $this->obtenerFormularioInspeccionTablet($arrayDatosRevisionDocumental);
                                        
                                        if($datosInspeccionTablet->count()){
                                            
                                            $numeroFormulario = $datosInspeccionTablet->current()->formulario_inspeccion_tablet;
                                            
                                            if(isset($numeroFormulario) || $numeroFormulario != ""){
                                                
                                                $statement5 = $this->modeloRevisionesDocumentales->getAdapter()
                                                ->getDriver()
                                                ->createStatement();
                                                
                                                $datosActualizacionFormularioInspeccion = array('utilizado_cfe' => false);
                                                
                                                $sqlActualizar = $this->modeloRevisionesDocumentales->actualizarSql('certificacionf11', 'f_inspeccion');
                                                $sqlActualizar->set($datosActualizacionFormularioInspeccion);
                                                $sqlActualizar->where(array('numero_reporte' => $numeroFormulario));
                                                $sqlActualizar->prepareStatement($this->modeloRevisionesDocumentales->getAdapter(), $statement5);
                                                $statement5->execute();
                                                
                                            }
                                            
                                        }
                                        
                                    }

                                }
                            }
                        }                        
                        
                        $arrayVerificarCambioEstado = array('id_certificado_fitosanitario' => $_POST['id_solicitud']
                            , 'estado_certificado' => 'Documental'
                        );
                        
                        $validarExportadorProducto = $this->verificarCambioEstadoSolicitudDocumental($arrayVerificarCambioEstado);
                        $estadoSiguiente = $validarExportadorProducto->current()->f_verificar_cambio_estado_documental;
                        
                        switch ($estadoSiguiente){
                            
                            case 'pago':
                                
                                //echo "CAMBIA A PAGO";
                                
                                if ($_POST['es_reemplazo'] == 'Si') {
                                    $estadoCertificado = 'Aprobado';
                                } else {
                                    $estadoCertificado = $estadoSiguiente;
                                }
                                
                                $estadoCabecera = $estadoCertificado;
                                $observacion = $_POST['observacion_revision'];
                                
                                if ($estadoCertificado == 'Aprobado' && $_POST['es_reemplazo'] == 'Si') {
                                    $arrayParametrosCertificadoReemplazado = array(
                                        'id_certificado_fitosanitario' => $_POST['id_certificado_reemplazo'],
                                        'estado_certificado' => 'Reemplazado',
                                        'tipo_revision' => 'Documental'
                                    );
                                    
                                    //Proceso de actualizacion del la cabecera de la solicitud para solicitud reemplazada
                                    $statement3 = $this->modeloRevisionesDocumentales->getAdapter()
                                    ->getDriver()
                                    ->createStatement();
                                    
                                    $sqlActualizar = $this->modeloRevisionesDocumentales->actualizarSql('certificado_fitosanitario', $this->modeloRevisionesDocumentales->getEsquema());
                                    $sqlActualizar->set($arrayParametrosCertificadoReemplazado);
                                    $sqlActualizar->where(array('id_certificado_fitosanitario' => $_POST['id_solicitud']));
                                    $sqlActualizar->prepareStatement($this->modeloRevisionesDocumentales->getAdapter(), $statement3);
                                    $statement3->execute();    
                                    
                                }
                                
                                break;
                                
                            case 'Inspeccion':
                                
                                //echo "SE DEVUELVE AL TECNICO";
                                
                                $estadoCabecera = $estadoSiguiente;
                                $observacion = $_POST['observacion_revision'];
                                
                                break;
                                
                            case 'Documental':
                                
                                ///echo "SE MANTIENE EL ESTADO";
                                
                                $estadoCabecera = $estadoSiguiente;
                                $observacion = 'En proceso de revisión documental';

                                break;
                                
                        }                        
                        
                        $arrayParametrosCertificado = array(
                            'id_certificado_fitosanitario' => $_POST['id_solicitud'],
                            'observacion_revision' => $observacion,
                            'estado_certificado' => $estadoCabecera,
                            'tipo_revision' => 'Documental'
                        );
                        
                        //$this->lNegocioCertificadoFitosanitario->guardar($arrayParametrosCertificado);
                        
                        //Proceso de actualizacion del la cabecera de la solicitud
                        $statement4 = $this->modeloRevisionesDocumentales->getAdapter()
                        ->getDriver()
                        ->createStatement();
                        
                        $sqlActualizar = $this->modeloRevisionesDocumentales->actualizarSql('certificado_fitosanitario', $this->modeloRevisionesDocumentales->getEsquema());
                        $sqlActualizar->set($arrayParametrosCertificado);
                        $sqlActualizar->where(array('id_certificado_fitosanitario' => $_POST['id_solicitud']));
                        $sqlActualizar->prepareStatement($this->modeloRevisionesDocumentales->getAdapter(), $statement4);
                        $statement4->execute();                                              
                        
                        $procesoIngreso->commit();                        
                        
                        return $procesoGuardar;   
                        
                    } catch (GuardarExcepcion $ex) {                        
                        $procesoIngreso->rollback();
                        throw new \Exception($ex->getMessage());
                    }                                   
                    
                }
                
                break;

            case 'DocumentalAprobada': //Puede ser para solicitudes nuevas y para renovación
            case 'Subsanacion':
                {

                    try {
                        
                        //Buscar el número de revisión documental para la solicitud que realizó un inspector
                        $numeroRevision = $this->generarNumeroRevisionDocumental($_POST['id_solicitud'], $_SESSION['usuario']);

                        $arrayParametrosRevisionDocumental = array(
                            'identificador_inspector' => $_SESSION['usuario'],
                            'id_provincia_revision' => $_SESSION['idProvincia'],
                            'provincia_revision' => $_SESSION['nombreProvincia'],
                            'id_solicitud' => $_POST['id_solicitud'],
                            'tipo_solicitud' => $_POST['tipo_certificado'],
                            'observacion_revision' => $_POST['observacion_revision'],
                            'num_revision' => $numeroRevision,
                            'estado' => $estadoRevision
                        );
                        
                        $tablaModelo = new RevisionesDocumentalesModelo($arrayParametrosRevisionDocumental);
                        $procesoGuardar = false;
                        $procesoIngreso = $this->modeloRevisionesDocumentales->getAdapter()
                        ->getDriver()
                        ->getConnection();
                        $procesoIngreso->beginTransaction();
                        
                        $datosBd = $tablaModelo->getPrepararDatos();
                        
                        if ($tablaModelo->getIdRevisionDocumental() != null && $tablaModelo->getIdRevisionDocumental() > 0) {
                            $this->modeloRevisionesDocumentales->actualizar($datosBd, $tablaModelo->getIdRevisionDocumental());
                            $idRevisionDocumental = $tablaModelo->getIdImportacionFertilizantes();
                        } else {
                            unset($datosBd["id_revision_documental"]);
                            $idRevisionDocumental = $this->modeloRevisionesDocumentales->guardar($datosBd);
                            $procesoGuardar = true;
                        }
                        
                        if ($procesoGuardar) {
                            
                            //EXPORTADORES_PRODUCTO
                            //Cambia el estado de todos los registros existentes en la solicitud
                            $query = "id_certificado_fitosanitario = " . $_POST['id_solicitud'] . " and estado_exportador_producto not in ('Rechazado')";
                            
                            $exportadorProducto = $this->lNegocioExportadoresProductos->buscarLista($query);
                            
                            foreach ($exportadorProducto as $fila) {
                                $arrayParametrosExportadorProducto = array(
                                    'id_exportador_producto' => $fila['id_exportador_producto'],
                                    'id_certificado_fitosanitario' => $_POST['id_solicitud'],
                                    'observacion_revision' => $_POST['observacion_revision'],
                                    'estado_exportador_producto' => $estadoRevision,
                                    'tipo_revision' => 'Documental'
                                );
                                
                                $this->lNegocioExportadoresProductos->guardar($arrayParametrosExportadorProducto);
                            }                            
                            
                            $arrayVerificarCambioEstado = array('id_certificado_fitosanitario' => $_POST['id_solicitud']
                                , 'estado_certificado' => 'Documental');
                            
                            $validarExportadorProducto = $this->verificarCambioEstadoSolicitudDocumental($arrayVerificarCambioEstado);
                            $estadoSiguiente = $validarExportadorProducto->current()->f_verificar_cambio_estado_documental;
                            
                            switch ($estadoSiguiente){
                                
                                case 'pago':
                                    
                                    //echo "CAMBIA A PAGO O APROBADO SI ES REIMPRESION";
                                    
                                    if ($_POST['es_reemplazo'] == 'Si') {
                                        $estadoCertificado = 'Aprobado';
                                    } else {
                                        $estadoCertificado = $estadoSiguiente;
                                    }
                                    
                                    $arrayParametrosCertificadoFitosanitario = array(
                                        'id_certificado_fitosanitario' => $_POST['id_solicitud'],
                                        'observacion_revision' => $_POST['observacion_revision'],
                                        'estado_certificado' => $estadoCertificado,
                                        'tipo_revision' => 'Documental'
                                    );
                                                                                                          
                                    if ($estadoCertificado == 'Aprobado' && $_POST['es_reemplazo'] == 'Si') {
                                        
                                        $arrayParametrosCertificadoFitosanitario += ['fecha_aprobacion_certificado' => 'now()'];
                                        
                                        $arrayParametrosCertificadoFitosanitarioReemplazo = array(
                                            'id_certificado_fitosanitario' => $_POST['id_certificado_reemplazo'],
                                            'estado_certificado' => 'Reemplazado',
                                            'tipo_revision' => 'Documental'
                                        );
                                        
                                        $this->lNegocioCertificadoFitosanitario->guardar($arrayParametrosCertificadoFitosanitarioReemplazo);
                                    }
                                    
                                break;
                                    
                                case 'Subsanacion':
                                    
                                    //echo "SE DEVUELVE AL OPERADOR SUBSANACION";
                                    
                                    $arrayParametrosCertificadoFitosanitario = array(
                                        'id_certificado_fitosanitario' => $_POST['id_solicitud'],
                                        'observacion_revision' => $_POST['observacion_revision'],
                                        'estado_certificado' => $estadoSiguiente,
                                        'tipo_revision' => 'Documental'
                                    );
                                                                        
                                break;
                                    
                                case 'Documental':
                                    
                                    //echo "SE MANTIENE EL ESTADO";
                                    
                                    $arrayParametrosCertificadoFitosanitario = array(
                                    'id_certificado_fitosanitario' => $_POST['id_solicitud'],
                                    'observacion_revision' => 'En proceso de revisión documental',
                                    'estado_certificado' => $estadoSiguiente,
                                    'tipo_revision' => 'Documental'
                                        );
                                    
                                break;
                                    
                                    
                            }
                            
                            $this->lNegocioCertificadoFitosanitario->guardar($arrayParametrosCertificadoFitosanitario);
                            
                        }
                        
                        $procesoIngreso->commit();
                        return $idRevisionDocumental;
                    } catch (GuardarExcepcion $ex) {
                        $procesoIngreso->rollback();
                        throw new \Exception($ex->getMessage());
                    }

                    break;
                }
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
        $this->modeloRevisionesDocumentales->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return RevisionesDocumentalesModelo
     */
    public function buscar($id)
    {
        return $this->modeloRevisionesDocumentales->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloRevisionesDocumentales->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloRevisionesDocumentales->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarRevisionesDocumentales()
    {
        $consulta = "SELECT * FROM " . $this->modeloRevisionesDocumentales->getEsquema() . ". revisiones_documentales";
        return $this->modeloRevisionesDocumentales->ejecutarSqlNativo($consulta);
    }

    /**
     * Método para generar la numeración de las revisiones documentales realizadas a una solicitud por un inspector
     */
    public function generarNumeroRevisionDocumental($idSolicitud, $identificadorInspector, $idProvincia = null, $idArea = null, $idProducto = null)
    {
        return $this->buscarNumeroRevisionDocumental($idSolicitud, $identificadorInspector, $idProvincia, $idArea, $idProducto);
    }

    public function buscarNumeroRevisionDocumental($idSolicitud, $identificadorInspector, $idProvincia = null, $idArea = null, $idProducto = null)
    {
        $busqueda = '';

        if (isset($idProvincia) && ($idProvincia != '')) {
            $busqueda .= " and id_provincia_inspeccion = '" . $idProvincia . "'";
        }

        if (isset($idArea) && ($idArea != '')) {
            $busqueda .= " and id_area_inspeccion = '" . $idArea . "'";
        }

        if (isset($idProducto) && ($idProducto != '')) {
            $busqueda .= " and id_producto_inspeccion = '" . $idProducto . "'";
        }

        $consulta = "SELECT
                        max(num_revision) as numero
                     FROM
                        g_certificado_fitosanitario.revisiones_documentales
                     WHERE
                        identificador_inspector = '$identificadorInspector' and
                        id_solicitud = $idSolicitud " . $busqueda . ";";

        $codigo = $this->modeloRevisionesDocumentales->ejecutarSqlNativo($consulta);

        $fila = $codigo->current();

        $codigoRevision = array(
            'numero' => $fila['numero']
        );
        $codigoRevision = $codigoRevision['numero'] + 1;

        return $codigoRevision;
    }

    public function buscarResultadoRevisionDocumental($arrayParametros)
    {
        $consulta = "SELECT
                        observacion_revision
                     FROM
                        g_certificado_fitosanitario.revisiones_documentales
                     WHERE
                        id_solicitud = " . $arrayParametros['id_certificado_fitosanitario'] . " and
                        id_area_inspeccion = " . $arrayParametros['id_area'] . " and
                        id_producto_inspeccion = " . $arrayParametros['id_producto'] . " and
                        estado = " . $arrayParametros['estado_exportador_producto'] . " 
                    ORDER BY
                        num_revision DESC
                    LIMIT 1;";

        return $this->modeloRevisionesDocumentales->ejecutarSqlNativo($consulta);
    }
    
    public function buscarTecnicoRevisionDocumental($arrayParametros)
    {
        $consulta = "SELECT
                        rd.id_revision_documental, rd.id_solicitud, rd.identificador_inspector
                        FROM
                        g_certificado_fitosanitario.revisiones_documentales rd
                        INNER JOIN (SELECT
                            max(id_revision_documental) AS id_revision_documental, id_solicitud
                            FROM
                            g_certificado_fitosanitario.revisiones_documentales
                            WHERE
                            id_solicitud = " . $arrayParametros['id_solicitud'] . "
                            GROUP BY id_solicitud) AS rd1 ON rd1.id_revision_documental = rd.id_revision_documental;";

        return $this->modeloRevisionesDocumentales->ejecutarSqlNativo($consulta);
    }
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener el estado siguiente
     * de acuerdo a la revision documental
     *
     * @return array|ResultSet
     */
    public function verificarCambioEstadoSolicitudDocumental($arrayParametros)
    {
        
        $idCertificadoFitosanitario = $arrayParametros['id_certificado_fitosanitario'];
        $estadoCertificado = $arrayParametros['estado_certificado'];
        
        $consulta = "SELECT * FROM g_certificado_fitosanitario.f_verificar_cambio_estado_documental(" . $idCertificadoFitosanitario . ", '" . $estadoCertificado . "');";
        
        return $this->modeloRevisionesDocumentales->ejecutarSqlNativo($consulta);
    }
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener el formulario
     * de inspeccion de tablet
     *
     * @return array|ResultSet
     */
    public function obtenerFormularioInspeccionTablet($arrayParametros)
    {
        
        $idSolicitud = $arrayParametros['id_solicitud'];
        $idAreaInspeccion = $arrayParametros['id_area_inspeccion'];
        $idProductoInspeccion = $arrayParametros['id_producto_inspeccion'];
        
        $consulta = "SELECT
                        MAX(i.id_inspeccion) as id_inspeccion, i.formulario_inspeccion_tablet
                     FROM 
                        g_certificado_fitosanitario.inspecciones i
                     WHERE 
                        i.id_solicitud = " . $idSolicitud . "
                        and i.id_area_inspeccion = " . $idAreaInspeccion . "
                        and i.id_producto_inspeccion = " . $idProductoInspeccion . "
                        and i.estado = 'InspeccionAprobada'
                        GROUP BY i.formulario_inspeccion_tablet;";
        
        return $this->modeloRevisionesDocumentales->ejecutarSqlNativo($consulta);
    }
        
}