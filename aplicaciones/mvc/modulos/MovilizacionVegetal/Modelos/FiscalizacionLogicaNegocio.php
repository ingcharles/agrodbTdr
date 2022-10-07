<?php
/**
 * Lógica del negocio de FiscalizacionModelo
 *
 * Este archivo se complementa con el archivo FiscalizacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-02
 * @uses    FiscalizacionLogicaNegocio
 * @package MovilizacionVegetal
 * @subpackage Modelos
 */
namespace Agrodb\MovilizacionVegetal\Modelos;

use Agrodb\MovilizacionVegetal\Modelos\IModelo;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class FiscalizacionLogicaNegocio implements IModelo
{

    private $modeloFiscalizacion = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloFiscalizacion = new FiscalizacionModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new FiscalizacionModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        
        if ($tablaModelo->getIdFiscalizacion() != null && $tablaModelo->getIdFiscalizacion() > 0) {
            return $this->modeloFiscalizacion->actualizar($datosBd, $tablaModelo->getIdFiscalizacion());
        } else {
            unset($datosBd["id_fiscalizacion"]);
            return $this->modeloFiscalizacion->guardar($datosBd);
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
        $this->modeloFiscalizacion->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return FiscalizacionModelo
     */
    public function buscar($id)
    {
        return $this->modeloFiscalizacion->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloFiscalizacion->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloFiscalizacion->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarFiscalizacion()
    {
        $consulta = "SELECT * FROM " . $this->modeloFiscalizacion->getEsquema() . ". fiscalizacion";
        return $this->modeloFiscalizacion->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar Información de fiscalizaciones creadas por movilización con información completa.
     *
     * @return array|ResultSet
     */
    public function buscarFiscalizacionXMovilizacion($idMovilizacion)
    {
        $consulta = "SELECT
                    	f.*
                     FROM
                        g_movilizacion_vegetal.fiscalizacion f
                     WHERE
                    	f.id_movilizacion = " . $idMovilizacion . "
                     ORDER BY
                        f.id_fiscalizacion ASC;";

        $detalleMovilizacion = $this->modeloFiscalizacion->ejecutarSqlNativo($consulta);

        return $detalleMovilizacion;
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar fiscalizaciones a nivel nacional usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarFiscalizacionesNacionalXFiltro($arrayParametros)
    {
        $busqueda = '';

        if (isset($arrayParametros['id_provincia']) && ($arrayParametros['id_provincia'] != '') && ($arrayParametros['id_provincia'] != 'Todas')) {
            $busqueda .= " and m.id_provincia_origen = '" . $arrayParametros['id_provincia'] . "'";
        }

        if (isset($arrayParametros['canton']) && ($arrayParametros['canton'] != '') && ($arrayParametros['canton'] != 'Seleccione....')) {
            $busqueda .= " and so.canton = '" . $arrayParametros['canton'] . "'";
        }

        if (isset($arrayParametros['parroquia']) && ($arrayParametros['parroquia'] != '') && ($arrayParametros['parroquia'] != 'Seleccione....')) {
            $busqueda .= " and so.parroquia = '" . $arrayParametros['parroquia'] . "'";
        }

        if (isset($arrayParametros['estado']) && ($arrayParametros['estado'] != '')) {
            $busqueda .= " and f.resultado_fiscalizacion = '" . $arrayParametros['estado'] . "'";
        }

        $consulta = " SELECT
                        	f.id_fiscalizacion,
                        	m.numero_permiso,
                        	f.identificador_fiscalizador,
                        	f.nombre_fiscalizador,
                            f.provincia_fiscalizacion,
                        	m.id_provincia_origen,
                        	m.provincia_origen,
                        	so.canton canton_origen,
                        	so.parroquia parroquia_origen,
                        	m.id_sitio_origen,
                        	m.sitio_origen,
                        	m.identificador_operador_origen,
                        	m.nombre_operador_origen,
                        	f.resultado_fiscalizacion,
                        	f.accion_correctiva,
                            f.observacion_fiscalizacion,
                        	f.fecha_creacion,
                        	f.fecha_fiscalizacion,
                        	m.estado_fiscalizacion,
                            dm.producto
                        FROM
                        	g_movilizacion_vegetal.fiscalizacion f
                        	INNER JOIN g_movilizacion_vegetal.movilizacion m ON f.id_movilizacion = m.id_movilizacion
                            INNER JOIN g_movilizacion_vegetal.detalle_movilizacion dm ON dm.id_movilizacion = m.id_movilizacion
                        	INNER JOIN g_operadores.sitios so ON m.id_sitio_origen = so.id_sitio
                        WHERE
                            m.fecha_creacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' and
	                        m.fecha_creacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00'
                            " . $busqueda . "
                        ORDER BY
                            m.numero_permiso, f.id_fiscalizacion ASC;";

        //echo $consulta;

        return $this->modeloFiscalizacion->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta un reporte en Excel de las fiscalizaciones
     *
     * @return array|ResultSet
     */
    public function exportarArchivoExcelFiscalizaciones($datos)
    {
        $hoja = new Spreadsheet();
        $documento = $hoja->getActiveSheet();
        $i = 3;
        $j = 2;

        $documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Fiscalizaciones');

        $documento->setCellValueByColumnAndRow(1, $j, 'ID');
        $documento->setCellValueByColumnAndRow(2, $j, 'Número Permiso');
        $documento->setCellValueByColumnAndRow(3, $j, 'Identificación Responsable Fiscalización');
        $documento->setCellValueByColumnAndRow(4, $j, 'Nombre Responsable Fiscalización');
        $documento->setCellValueByColumnAndRow(5, $j, 'Provincia Responsable Fiscalización');
        $documento->setCellValueByColumnAndRow(6, $j, 'Provincia Origen');
        $documento->setCellValueByColumnAndRow(7, $j, 'Cantón Origen');
        $documento->setCellValueByColumnAndRow(8, $j, 'Parroquia Origen');
        $documento->setCellValueByColumnAndRow(9, $j, 'Sitio Origen');
        $documento->setCellValueByColumnAndRow(10, $j, 'Identificación Propietario');
        $documento->setCellValueByColumnAndRow(11, $j, 'Nombre Propietario');
        $documento->setCellValueByColumnAndRow(12, $j, 'Resultado');
        $documento->setCellValueByColumnAndRow(13, $j, 'Acción Correctiva');
        $documento->setCellValueByColumnAndRow(14, $j, 'Observación');
        $documento->setCellValueByColumnAndRow(15, $j, 'Fecha Registro');
        $documento->setCellValueByColumnAndRow(16, $j, 'Fecha Fiscalización');
        $documento->setCellValueByColumnAndRow(17, $j, 'Estado Fiscalización');
        $documento->setCellValueByColumnAndRow(18, $j, 'Producto');

        if ($datos != '') {
            foreach ($datos as $fila) {
                $documento->setCellValueByColumnAndRow(1, $i, $fila['id_fiscalizacion']);
                $documento->getCellByColumnAndRow(2, $i)->setValueExplicit($fila['numero_permiso'], 's');
                $documento->getCellByColumnAndRow(3, $i)->setValueExplicit($fila['identificador_fiscalizador'], 's');
                $documento->setCellValueByColumnAndRow(4, $i, $fila['nombre_fiscalizador']);
                $documento->setCellValueByColumnAndRow(5, $i, $fila['provincia_fiscalizacion']);
                $documento->setCellValueByColumnAndRow(6, $i, $fila['provincia_origen']);
                $documento->setCellValueByColumnAndRow(7, $i, $fila['canton_origen']);
                $documento->setCellValueByColumnAndRow(8, $i, $fila['parroquia_origen']);
                $documento->setCellValueByColumnAndRow(9, $i, $fila['sitio_origen']);
                $documento->getCellByColumnAndRow(10, $i)->setValueExplicit($fila['identificador_operador_origen'], 's');
                $documento->setCellValueByColumnAndRow(11, $i, $fila['nombre_operador_origen']);
                $documento->setCellValueByColumnAndRow(12, $i, $fila['resultado_fiscalizacion']);
                $documento->setCellValueByColumnAndRow(13, $i, $fila['accion_correctiva']);
                $documento->setCellValueByColumnAndRow(14, $i, $fila['observacion_fiscalizacion']);
                $documento->setCellValueByColumnAndRow(15, $i, ($fila['fecha_creacion'] != null ? date('Y-m-d', strtotime($fila['fecha_creacion'])) : ''));
                $documento->setCellValueByColumnAndRow(16, $i, ($fila['fecha_fiscalizacion'] != null ? date('Y-m-d', strtotime($fila['fecha_fiscalizacion'])) : ''));
                $documento->setCellValueByColumnAndRow(17, $i, $fila['estado_fiscalizacion']);
                $documento->setCellValueByColumnAndRow(18, $i, $fila['producto']);

                $i ++;
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="excelFiscalizaciones.xlsx"');
        header("Pragma: no-cache");
        header("Expires: 0");

        $writer = IOFactory::createWriter($hoja, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
}