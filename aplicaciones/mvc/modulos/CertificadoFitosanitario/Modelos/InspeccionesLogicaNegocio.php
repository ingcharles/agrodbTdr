<?php
/**
 * Lógica del negocio de InspeccionesModelo
 *
 * Este archivo se complementa con el archivo InspeccionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    InspeccionesLogicaNegocio
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoFitosanitario\Modelos;

use Agrodb\CertificadoFitosanitario\Modelos\IModelo;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioModelo;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosModelo;
use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\FormulariosInspeccion\Modelos\Certificacionf11Modelo;
use Agrodb\FormulariosInspeccion\Modelos\Certificacionf11LogicaNegocio;

class InspeccionesLogicaNegocio implements IModelo
{

    private $modeloInspecciones = null;
    private $lNegocioCertificadoFitosanitario = null;
    private $modeloCertificadoFitosanitario = null;
    private $lNegocioExportadoresProductos = null;
    private $modeloExportadoresProductos = null;
    private $lNegocioCertificacionf11 = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloInspecciones = new InspeccionesModelo();
        $this->lNegocioCertificadoFitosanitario = new CertificadoFitosanitarioLogicaNegocio();
        $this->modeloCertificadoFitosanitario = new CertificadoFitosanitarioModelo();
        $this->lNegocioExportadoresProductos = new ExportadoresProductosLogicaNegocio();
        $this->modeloExportadoresProductos = new ExportadoresProductosModelo();
        $this->lNegocioCertificacionf11 = new Certificacionf11LogicaNegocio();
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

            $procesoIngreso = $this->modeloInspecciones->getAdapter()
                ->getDriver()
                ->getConnection();
            $procesoIngreso->beginTransaction();
            
            // Guarda registro de la inspección para un producto y un centro de acopio específico
            // Guardar los resultados de inspección
            for ($i = 0; $i < count($_POST['iAreaInspeccion']); $i ++) {

                // Buscar el número de inspección para el área y producto que realizó un inspector
                $numeroInspeccion = $this->buscarNumeroInspeccion($_POST['id_solicitud'], $_POST['iAreaInspeccion'][$i], $_POST['iProducto'][$i], $_SESSION['usuario']);

                $arrayParametrosInspeccion = array(
                    'identificador_inspector' => $_SESSION['usuario'],
                    'id_provincia_inspeccion' => $_SESSION['idProvincia'],
                    'provincia_inspeccion' => $_SESSION['nombreProvincia'],
                    'id_solicitud' => $_POST['id_solicitud'],
                    'id_area_inspeccion' => $_POST['iAreaInspeccion'][$i],
                    'nombre_area_inspeccion' => $_POST['nAreaInspeccion'][$i],
                    'id_producto_inspeccion' => $_POST['iProducto'][$i],
                    'nombre_producto_inspeccion' => $_POST['nProducto'][$i],
                    'fecha_confirmacion_inspeccion' => $_POST['nFechaInspeccion'][$i],
                    'hora_confirmacion_inspeccion' => $_POST['nHoraInspeccion'][$i],
                    'ruta_archivo_inspeccion' => $_POST['ruta_archivo_inspeccion'],
                    'observacion_inspeccion' => $_POST['iObservacion'][$i],
                    'num_inspeccion' => $numeroInspeccion,
                    'estado' => $_POST['iEstado'][$i]
                );

                if (isset($_POST['iFormularioInspeccion'][$i]) && isset($_POST['iFormularioInspeccion'][$i]) != "") {
                    
                    $arrayParametrosInspeccion += [
                        'formulario_inspeccion_tablet' => $_POST['iFormularioInspeccion'][$i]
                    ];
                    
                    $numeroReporteInspeccion = $this->lNegocioCertificacionf11->buscarLista(array('numero_reporte' => $_POST['iFormularioInspeccion'][$i]));
                    
                    if(isset($numeroReporteInspeccion->current()->id)){
                    
                        if (isset($_POST['iEstado'][$i]) && $_POST['iEstado'][$i] == "InspeccionAprobada") {
                            
                            $arrayParametrosFormularioInspeccion = array('id' => $numeroReporteInspeccion->current()->id
                                                , 'utilizado_cfe' => true
                                            );
                            
                            $this->lNegocioCertificacionf11->guardar($arrayParametrosFormularioInspeccion);
                            
                        }
                    
                    }
                    
                }
                
                //Proceso de ingreso de inspeccion realizada
                $statement = $this->modeloInspecciones->getAdapter()
                ->getDriver()
                ->createStatement();
                
                $sqlInsertar = $this->modeloInspecciones->guardarSql('inspecciones', $this->modeloInspecciones->getEsquema());
                $sqlInsertar->columns(array_keys($arrayParametrosInspeccion));
                $sqlInsertar->values($arrayParametrosInspeccion, $sqlInsertar::VALUES_MERGE);
                $sqlInsertar->prepareStatement($this->modeloInspecciones->getAdapter(), $statement);
                $statement->execute();
                $procesoGuardar = true;
               
                //if ($procesoGuardar) {
                $statement2 = $this->modeloInspecciones->getAdapter()
                ->getDriver()
                ->createStatement();
                
                $datosActualizacionExportadoresProductos = array('estado_exportador_producto' => $_POST['iEstado'][$i]
                    , 'observacion_revision' => $_POST['iObservacion'][$i]
                    , 'tipo_revision' => 'Inspeccion'
                );
                
                $sqlActualizar = $this->modeloInspecciones->actualizarSql('exportadores_productos', $this->modeloInspecciones->getEsquema());
                $sqlActualizar->set($datosActualizacionExportadoresProductos);
                $sqlActualizar->where(array('id_certificado_fitosanitario' => $_POST['id_solicitud'], 'id_area' => $_POST['iAreaInspeccion'][$i], 'id_provincia_area' => $_SESSION["idProvincia"], 'id_producto' => $_POST['iProducto'][$i]));
                $sqlActualizar->prepareStatement($this->modeloInspecciones->getAdapter(), $statement2);
                $statement2->execute();                    
                //}
                
            }

            $arrayVerificarCambioEstado = array('id_certificado_fitosanitario' => $_POST['id_solicitud']
                , 'estado_certificado' => 'Inspeccion');
            
            $validarExportadorProducto = $this->verificarCambioEstadoSolicitudInspeccion($arrayVerificarCambioEstado);
            $estadoSiguiente = $validarExportadorProducto->current()->f_verificar_cambio_estado_inspeccion;
            
            switch ($estadoSiguiente){
                
                case 'Documental':
                    //echo "CAMBIA A DOCUMENTAL";
                    $estadoCabecera = $estadoSiguiente;
                    $observacion = 'Inspección aprobada';
                    
                break;
                
                case 'Subsanacion':                    
                    //echo "SE DEVUELVE AL USAURIO";
                    $estadoCabecera = $estadoSiguiente;
                    $observacion = 'Solicitud subsanada';
                break;
                
                case 'Rechazado':
                    //echo "SE RECHAZA LA SOLICITUD";
                    $estadoCabecera = $estadoSiguiente;
                    $observacion = 'Inspección rechazada';
                break;
                
                case 'Inspeccion':
                    //echo "SE MANTIENE EL ESTADO";
                    $estadoCabecera = $estadoSiguiente;
                    $observacion = 'En proceso de inspección';
                break;  
                
            }
            
            /*$arrayParametros = array(
                'id_certificado_fitosanitario' => $_POST['id_solicitud'],
                'estado_certificado' => $estadoCabecera,
                'tipo_revision' => 'Inspeccion',
                'observacion_revision' => $observacion
            );
            
            $this->lNegocioCertificadoFitosanitario->guardar($arrayParametros);*/
            
            $arrayParametrosCertificado = array('id_certificado_fitosanitario' => $_POST['id_solicitud']
                , 'estado_certificado' => $estadoCabecera
                , 'tipo_revision' => 'Inspeccion'
                , 'observacion_revision' => $observacion
            );
            
            //Proceso de actualizacion del la cabecera de la solicitud
            $statement3 = $this->modeloInspecciones->getAdapter()
            ->getDriver()
            ->createStatement();
            
            $sqlActualizar = $this->modeloInspecciones->actualizarSql('certificado_fitosanitario', $this->modeloInspecciones->getEsquema());
            $sqlActualizar->set($arrayParametrosCertificado);
            $sqlActualizar->where(array('id_certificado_fitosanitario' => $_POST['id_solicitud']));
            $sqlActualizar->prepareStatement($this->modeloInspecciones->getAdapter(), $statement3);
            $statement3->execute();
            
            $procesoIngreso->commit();

            return $procesoGuardar;
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
        $this->modeloInspecciones->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return InspeccionesModelo
     */
    public function buscar($id)
    {
        return $this->modeloInspecciones->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloInspecciones->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloInspecciones->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarInspecciones()
    {
        $consulta = "SELECT * FROM " . $this->modeloInspecciones->getEsquema() . ". inspecciones";
        return $this->modeloInspecciones->ejecutarSqlNativo($consulta);
    }

    public function buscarNumeroInspeccion($idSolicitud, $idArea, $idProducto, $identificadorInspector)
    {
        $consulta = "SELECT
                        max(num_inspeccion) as numero
                     FROM
                        g_certificado_fitosanitario.inspecciones
                     WHERE 
                        identificador_inspector = '$identificadorInspector' and
                        id_solicitud = $idSolicitud and
                        id_area_inspeccion = $idArea and
                        id_producto_inspeccion = $idProducto;";

        $codigo = $this->modeloInspecciones->ejecutarSqlNativo($consulta);

        $fila = $codigo->current();

        $codigoInspeccion = array(
            'numero' => $fila['numero']
        );
        $codigoInspeccion = $codigoInspeccion['numero'] + 1;

        return $codigoInspeccion;
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar Información de detalles de inspección creados por certificado con información completa.
     *
     * @return array|ResultSet
     */
    public function buscarInspeccionXSolicitud($idSolicitud)
    {
        
        $consulta = "SELECT
                    	*
                    FROM
                    	g_certificado_fitosanitario.inspecciones i
                    WHERE
                    i.id_inspeccion in (SELECT ti.id_inspeccion
                    						FROM(SELECT 
                    								 MAX(id_inspeccion) AS id_inspeccion
                    								 , id_area_inspeccion 
                    							 FROM 
                    							 	g_certificado_fitosanitario.inspecciones 
                    							 WHERE 
                    								 id_solicitud = " . $idSolicitud . " 
                    								 and estado in ('InspeccionAprobada', 'Rechazado')
                    								 GROUP BY id_area_inspeccion) ti)
                    ORDER BY
                    i.provincia_inspeccion, i.nombre_producto_inspeccion, i.id_inspeccion ASC;";

        $inspecciones = $this->modeloInspecciones->ejecutarSqlNativo($consulta);

        return $inspecciones;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener el estado siguiente
     * de acuerdo a la inspeccion
     *
     * @return array|ResultSet
     */
    public function verificarCambioEstadoSolicitudInspeccion($arrayParametros)
    {
        
        $idCertificadoFitosanitario = $arrayParametros['id_certificado_fitosanitario'];
        $estadoCertificado = $arrayParametros['estado_certificado'];
        
        $consulta = "SELECT * FROM g_certificado_fitosanitario.f_verificar_cambio_estado_inspeccion(" . $idCertificadoFitosanitario . ", '" . $estadoCertificado . "');";
        
        return $this->modeloInspecciones->ejecutarSqlNativo($consulta);
    }

}