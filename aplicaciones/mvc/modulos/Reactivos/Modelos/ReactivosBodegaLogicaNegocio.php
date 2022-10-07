<?php

/**
 * Lógica del negocio de  ReactivosBodegaModelo
 *
 * Este archivo se complementa con el archivo   ReactivosBodegaControlador.
 * https://phpspreadsheet.readthedocs.io/en/develop/topics/reading-files/
 * 
 * @author DATASTAR
 * @uses       ReactivosBodegaLogicaNegocio
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Reactivos\Modelos\IModelo;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ReactivosBodegaLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new ReactivosBodegaModelo();
        $this->startRow = 2;
        $this->columns = range('A', 'D');
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $inicio = $datos['inicio'];
        $fin = $datos['fin'];
        $archivoExcel = APP . "Reactivos/archivos/excelBodega/" . $datos['archivo'] . "." . $datos['extension'];
        switch (strtolower($datos['extension']))
        {
            case 'xls':
                $tipo = 'Xls';   //Requiere formato Xls
                break;
            case 'xlsx':
                $tipo = 'Xlsx';   //Requiere formato Xlsx
                break;
            default:
                $tipo = 'Xls';   //Requiere formato Xls
                break;
        }

        try {
            $inputFileType = $tipo;
            $reader = IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);
            $reader->setLoadSheetsOnly(0);
            $filterSubset = new Filtro($inicio, $fin, range('A', 'D'));
            $reader->setReadFilter($filterSubset);
            $spreadsheet = $reader->load($archivoExcel);
            $loadedSheetNames = $spreadsheet->getActiveSheet();
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $this->modelo = new ReactivosBodegaModelo();
            $proceso = $this->modelo->getAdapter()
                    ->getDriver()
                    ->getConnection();
            if (!$proceso->beginTransaction())
            {
                throw new \Exception('No se pudo iniciar la transacción en: Guardar reactivos_laboratorios');
            }
            foreach ($sheetData as $key => $value)
            {
                $statement = $this->modelo->getAdapter()
                        ->getDriver()
                        ->createStatement();
                //Se agrega los datos del excel a los datos existentes
                $datos['codigo_bodega'] = $value['A'];
                $datos['nombre'] = $value['B'];
                $datos['unidad'] = $value['C'];
                $datos['cantidad'] = $value['D'];
                $datos['observaciones'] = null;

                if ($datos['codigo_bodega'] != null)
                {
                    $tablaModelo = new ReactivosBodegaModelo($datos);
                    $datosBd = $tablaModelo->getPrepararDatos();

                    //Buscar si está registrado el código
                    $where = array("codigo_bodega" => $tablaModelo->getCodigoBodega(), 'id_bodega' => $tablaModelo->getIdBodega());
                    $resultado = $this->modelo->buscarLista($where);
                    $fila = $resultado->current();
                    if (empty($fila->codigo_bodega))
                    {
                        //si no existe el registro entonces guardar
                        $datosBd["cantidad_anterior"] = 0;
                        $sqlInsertar = $this->modelo->guardarSql('reactivos_bodega', $this->modelo->getEsquema());
                        $sqlInsertar->columns($this->columnas());
                        $sqlInsertar->values($datosBd, $sqlInsertar::VALUES_MERGE);
                        $sqlInsertar->prepareStatement($this->modelo->getAdapter(), $statement);
                        $statement->execute();
                    } else
                    {
                        //si existe el registro entonces actualizar
                        $id = $fila->id_reactivo_bodega;
                        $datosBd['cantidad_anterior'] = $fila->cantidad;

                        $sqlActualizar = $this->modelo->actualizarSql('reactivos_bodega', $this->modelo->getEsquema());
                        $sqlActualizar->set($datosBd);
                        $sqlActualizar->where(array('id_reactivo_bodega' => $id));
                        $sqlActualizar->prepareStatement($this->modelo->getAdapter(), $statement);
                        $statement->execute();
                    }
                }
            }
            $proceso->commit();
            return true;
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            die('Error al cargar el archivo de excel: ' . $e->getMessage());
        }
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modelo->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return ReactivosBodegaModelo
     */
    public function buscar($id)
    {
        return $this->modelo->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modelo->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modelo->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarReactivosBodega($arrayParametros)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['codigo']))
        {
            $arrayWhere[] = " UPPER(codigo_bodega) LIKE '%" . strtoupper($arrayParametros['codigo']) . "%'";
        }
        if (!empty($arrayParametros['nombre']))
        {
            $arrayWhere[] = " UPPER(rbod.nombre) LIKE '%" . strtoupper($arrayParametros['nombre']) . "%'";
        }
        if (!empty($arrayParametros['id_bodega']))
        {
            if (is_array($arrayParametros['id_bodega']))
            {
                $arrayWhere[] = " rbod.id_bodega IN (" . implode(",", $arrayParametros['id_bodega']) . ")";
            } else
            {
                $arrayWhere[] = " rbod.id_bodega = {$arrayParametros['id_bodega']}";
            }
        }
        if ($arrayWhere)
        {
            $where = " WHERE" . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        rbod.id_reactivo_bodega,
        rbod.id_bodega,
        rbod.codigo_bodega,
        rbod.nombre,
        rbod.cantidad_anterior,
        rbod.cantidad,
        rbod.fecha_actualizacion,
        rbod.unidad,
        rbod.observaciones,
        rbod.descripcion,
        rbod.estado,
        bod.nombre_bodega,
        bod.estado,
        prov.nombre AS provincia_bodega
        FROM
        g_reactivos.reactivos_bodega AS rbod
        INNER JOIN g_reactivos.bodegas AS bod ON bod.id_bodega = rbod.id_bodega
        INNER JOIN g_catalogos.localizacion AS prov ON prov.id_localizacion = bod.id_localizacion
        $where
        ORDER BY rbod.nombre";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Buscar los saldos del reactivo
     * @param type $idLaboratorio
     * @param type $filtro
     * @return type
     */
    public function buscarReactivosSaldos($idLaboratorio, $filtro)
    {
        $consulta = "SELECT
        rbo.id_reactivo_bodega,
        rbo.codigo_bodega,
        rbo.nombre,
        rbo.cantidad AS saldo_bodega,
        rbo.unidad,
        COALESCE(slab.cantidad,0) AS saldo_laboratorio,
        rla.id_laboratorio,
        rla.id_reactivo_laboratorio
        FROM
        g_reactivos.reactivos_bodega rbo
        LEFT  JOIN g_reactivos.reactivos_laboratorios rla ON rbo.id_reactivo_bodega = rla.id_reactivo_bodega
        LEFT JOIN g_reactivos.solicitud_requerimiento solr ON rla.id_reactivo_laboratorio = solr.id_reactivo_laboratorio
        LEFT JOIN g_reactivos.saldos_laboratorios slab ON solr.id_solicitud_requerimiento = slab.id_solicitud_requerimiento
        WHERE upper(rbo.nombre) like '%" . strtoupper($filtro) . "%' and  (rla.id_reactivo_laboratorio = " . $idLaboratorio . " OR rla.id_reactivo_laboratorio IS NULL)  LIMIT 6 OFFSET 0";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Para obtener el id_bodega del que le corresponde al usuario
     * @param type $identificador
     * @return type
     */
    public function buscarUsuarioBodegas($identificador)
    {
        $consulta = "SELECT
        prov.nombre AS provincia,
        usulab.identificador,
        labprov.id_localizacion,
        bod.id_bodega,
        bod.nombre_bodega
        FROM
        g_laboratorios.usuario_laboratorio AS usulab
        INNER JOIN g_laboratorios.laboratorios_provincia AS labprov ON labprov.id_laboratorios_provincia = usulab.id_laboratorios_provincia
        INNER JOIN g_catalogos.localizacion AS prov ON prov.id_localizacion = labprov.id_localizacion
        INNER JOIN g_reactivos.bodegas AS bod ON bod.id_localizacion = labprov.id_localizacion
        WHERE
        usulab.identificador = '$identificador' AND usulab.estado='ACTIVO'
        GROUP BY
        prov.nombre,
        usulab.identificador,
        labprov.id_localizacion,
        bod.id_bodega,
        bod.nombre_bodega";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Busca la bodega según la localización del laboratorio
     * @param type $idLaboratoriosProvincia
     * @return type
     */
    public function buscarBodegaPorLaboratorioProvincia($idLaboratoriosProvincia)
    {
        $consulta= "SELECT
        prov.nombre AS provincia,
        labprov.id_localizacion,
        bod.id_bodega,
        bod.nombre_bodega,
        labprov.id_laboratorios_provincia
        FROM
        g_laboratorios.laboratorios_provincia AS labprov
        INNER JOIN g_catalogos.localizacion AS prov ON prov.id_localizacion = labprov.id_localizacion
        INNER JOIN g_reactivos.bodegas AS bod ON bod.id_localizacion = labprov.id_localizacion
        WHERE labprov.id_laboratorios_provincia = $idLaboratoriosProvincia";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    public function buscarReactivosPorLaboratorioProvincia($idLaboratoriosProvincia)
    {
        $consulta= "SELECT
        rbod.id_reactivo_bodega,
        rbod.id_bodega,
        rbod.codigo_bodega,
        rbod.nombre
        FROM
        g_reactivos.reactivos_bodega AS rbod
        INNER JOIN g_reactivos.bodegas AS bod ON bod.id_bodega = rbod.id_bodega
        INNER JOIN g_laboratorios.laboratorios_provincia AS lprov ON lprov.id_localizacion = bod.id_localizacion
        WHERE lprov.id_laboratorios_provincia = $idLaboratoriosProvincia";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Columnas de la tabla g_reactivos.reactivos_bodega
     * @return string
     */
    public function columnas()
    {
        $columnas = array(
            'codigo_bodega',
            'nombre',
            'unidad',
            'cantidad',
            'cantidad_anterior',
            'descripcion',
            'observaciones',
            'id_bodega'
        );
        return $columnas;
    }

}

class Filtro implements IReadFilter
{

    private $startRow = 0;
    private $endRow = 0;
    private $columns = [];

    public function __construct($startRow, $endRow, $columns)
    {
        $this->startRow = $startRow;
        $this->endRow = $endRow;
        $this->columns = $columns;
    }

    public function readCell($column, $row, $worksheetName = '')
    {
        if ($row >= $this->startRow && $row <= $this->endRow)
        {
            if (in_array($column, $this->columns))
            {
                return true;
            }
        }

        return false;
    }

}
