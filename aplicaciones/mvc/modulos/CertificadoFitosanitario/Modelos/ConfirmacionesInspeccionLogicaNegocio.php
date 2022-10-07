<?php
/**
 * Lógica del negocio de ConfirmacionesInspeccionModelo
 *
 * Este archivo se complementa con el archivo ConfirmacionesInspeccionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    ConfirmacionesInspeccionLogicaNegocio
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

class ConfirmacionesInspeccionLogicaNegocio implements IModelo
{

    private $modeloConfirmacionesInspeccion = null;

    private $lNegocioCertificadoFitosanitario = null;

    private $modeloCertificadoFitosanitario = null;

    private $lNegocioExportadoresProductos = null;

    private $modeloExportadoresProductos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloConfirmacionesInspeccion = new ConfirmacionesInspeccionModelo();
        $this->lNegocioCertificadoFitosanitario = new CertificadoFitosanitarioLogicaNegocio();
        $this->modeloCertificadoFitosanitario = new CertificadoFitosanitarioModelo();
        $this->lNegocioExportadoresProductos = new ExportadoresProductosLogicaNegocio();
        $this->modeloExportadoresProductos = new ExportadoresProductosModelo();
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

            $procesoIngreso = $this->modeloConfirmacionesInspeccion->getAdapter()
                ->getDriver()
                ->getConnection();
            $procesoIngreso->beginTransaction();

            for ($i = 0; $i < count($datos['iAreaInspeccion']); $i ++) {

                $arrayParametrosConfirmarInspeccion = array(
                    'id_solicitud' => $datos['id_solicitud'],
                    'id_area_inspeccion' => $datos['iAreaInspeccion'][$i],
                    'nombre_area_inspeccion' => $datos['nAreaInspeccion'][$i],
                    'fecha_confirmacion_inspeccion' => $datos['nFechaConfirmacionInspeccion'][$i],
                    'hora_confirmacion_inspeccion' => $datos['nHoraConfirmacionInspeccion'][$i],
                    'identificador_inspector' => $_SESSION['usuario'],
                    'id_provincia_inspeccion' => $_SESSION['idProvincia'],
                    'provincia_inspeccion' => $_SESSION['nombreProvincia'],
                    'estado' => 'FechaConfirmada'
                );

                $tablaModelo = new ConfirmacionesInspeccionModelo($arrayParametrosConfirmarInspeccion);
                $datosBd = $tablaModelo->getPrepararDatos();

                if ($tablaModelo->getIdConfirmacionInspeccion() != null && $tablaModelo->getIdConfirmacionInspeccion() > 0) {
                    $this->modeloConfirmacionesInspeccion->actualizar($datosBd, $tablaModelo->getIdConfirmacionInspeccion());
                    $idConfirmacionInspeccion = $tablaModelo->getIdConfirmacionInspeccion();
                } else {
                    unset($datosBd["id_confirmacion_inspeccion"]);
                    $idConfirmacionInspeccion = $this->modeloConfirmacionesInspeccion->guardar($datosBd);
                }

                // Cambia el estado de todos los registros existentes en la solicitud para ese centro de acopio
                $query = "id_certificado_fitosanitario = " . $_POST['id_solicitud'] . " and id_area = " . $_POST['iAreaInspeccion'][$i] . " and id_provincia_area = " . $_SESSION["idProvincia"];

                $exportadorProducto = $this->lNegocioExportadoresProductos->buscarLista($query);

                foreach ($exportadorProducto as $fila) {
                    $arrayParametrosExportadoresProductos = array(
                        'id_exportador_producto' => $fila['id_exportador_producto'],
                        'id_certificado_fitosanitario' => $_POST["id_solicitud"],
                        'fecha_inspeccion' => $datos['nFechaConfirmacionInspeccion'][$i],
                        'hora_inspeccion' => $datos['nHoraConfirmacionInspeccion'][$i],
                        'estado_exportador_producto' => 'FechaConfirmada',
                        'tipo_revision' => 'ConfirmarInspeccion'
                    );
                    $this->lNegocioExportadoresProductos->guardar($arrayParametrosExportadoresProductos);
                }

                // Verifica si todos los productos de la solicitud están en estado FechaConfirmada y cambia el estado a la Solicitud
                $arrayParametros = array(
                    'idSolicitud' => $_POST['id_solicitud'],
                    'estadoExportador' => "('FechaConfirmada', 'Rechazado')",
                    'tipoRevision' => "('ConfirmarInspeccion')"
                );

                $exportadorProducto = $this->lNegocioExportadoresProductos->verificarEstadoPorClasificacionProducto($arrayParametros);

                if (! isset($exportadorProducto->current()->id_exportador_producto)) {
                    $arrayParametros = array(
                        'id_certificado_fitosanitario' => $_POST['id_solicitud'],
                        'estado_certificado' => 'Inspeccion',
                        'tipo_revision' => 'ConfirmarInspeccion'
                    );
                    $this->lNegocioCertificadoFitosanitario->guardar($arrayParametros);
                }
            }

            $procesoIngreso->commit();
            return $idConfirmacionInspeccion;
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
        $this->modeloConfirmacionesInspeccion->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ConfirmacionesInspeccionModelo
     */
    public function buscar($id)
    {
        return $this->modeloConfirmacionesInspeccion->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloConfirmacionesInspeccion->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloConfirmacionesInspeccion->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarConfirmacionesInspeccion()
    {
        $consulta = "SELECT * FROM " . $this->modeloConfirmacionesInspeccion->getEsquema() . ". confirmaciones_inspeccion";
        return $this->modeloConfirmacionesInspeccion->ejecutarSqlNativo($consulta);
    }
}
