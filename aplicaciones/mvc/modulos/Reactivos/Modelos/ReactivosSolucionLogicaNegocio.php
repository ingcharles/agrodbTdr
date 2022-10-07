<?php

/**
 * Lógica del negocio de  ReactivosSolucionModelo
 *
 * Este archivo se complementa con el archivo   ReactivosSolucionControlador.
 *
 * @author DATASTAR
 * @uses       ReactivosSolucionLogicaNegocio
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Reactivos\Modelos\IModelo;

class ReactivosSolucionLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new ReactivosSolucionModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ReactivosSolucionModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdReactivoSolucion() != null && $tablaModelo->getIdReactivoSolucion() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdReactivoSolucion());
        } else
        {
            unset($datosBd["id_reactivo_solucion"]);
            return $this->modelo->guardar($datosBd);
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
     * @return ReactivosSolucionModelo
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
     * Configura los campos de la tabla de muestras
     */
    public function columnas()
    {
        $columnas = array(
            'id_reactivo_laboratorio',
            'cantidad',
            'observacion',
            'tipo_ingreso',
            'motivo',
            'lote',
            'autorizacion'
        );
        return $columnas;
    }

    /**
     * Configura los campos de la tabla de muestras
     */
    public function columnasRegistroS()
    {
        $columnas = array(
            'id_reactivo_laboratorio',
            'cantidad',
            'tipo_ingreso',
            'motivo',
            'id_solucion'
        );
        return $columnas;
    }

    /**
     * Retortna reactivos de la solucion
     * @param type $idReactivoLaboratorio
     * @return type
     */
    public function buscarReactivosSolucion($idReactivoLaboratorio)
    {
        $consulta = "SELECT
        rsol.id_reactivo_solucion,
        rsol.id_solucion,
        sol.nombre,
        rsol.id_reactivo_laboratorio,
        rlab.nombre,
        rsol.cantidad_requerida,
        rlab.unidad_medida,
        rsol.estado_registro,
        rsol.observacion
        FROM
        g_reactivos.reactivos_laboratorios AS sol
        INNER JOIN g_reactivos.reactivos_solucion AS rsol ON sol.id_reactivo_laboratorio = rsol.id_solucion
        INNER JOIN g_reactivos.reactivos_laboratorios AS rlab ON rlab.id_reactivo_laboratorio = rsol.id_reactivo_laboratorio
        WHERE rsol.id_solucion = $idReactivoLaboratorio";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Guarda en la tabla saldos_laboratorios
     * @param array $datos
     * @return int
     */
    public function guardarSaldosLaboratorios(Array $datos)
    {
        $this->modelo = new ReactivosSolucionModelo();
        $proceso = $this->modelo->getAdapter()
                ->getDriver()
                ->getConnection();
        if (!$proceso->beginTransaction())
        {
            throw new \Exception('No se pudo iniciar la transacción en: Guardar saldos_laboratorios');
        }
        //Buscar datos del reactivo
        $lNReactivosLaboratorio = new ReactivosLaboratoriosLogicaNegocio();
        $buscaReactivoLaboratorio = $lNReactivosLaboratorio->buscar($datos['id_solucion']);
        //Registrar el ingreso en saldos laboratorios
        $statement = $this->modelo->getAdapter()
                ->getDriver()
                ->createStatement();
        $datosSaldosLIngreso = array(
            'id_reactivo_laboratorio' => $datos['id_solucion'], //id del reactivo como solucion
            'cantidad' => $datos['cantidad_requerida'],         //volumen que ingresa
            'tipo_ingreso' => 'INGRESO',
            'motivo' => "INGRESO DE SOLUCION: $buscaReactivoLaboratorio->nombre"
        );
        $tablaModelo = new SaldosLaboratoriosModelo($datosSaldosLIngreso);
        $datosBd = $tablaModelo->getPrepararDatos();
        $sqlInsertar = $this->modelo->guardarSql('saldos_laboratorios', $this->modelo->getEsquema());
        $sqlInsertar->columns($this->columnasRegistroS());
        $sqlInsertar->values($datosBd, $sqlInsertar::VALUES_MERGE);
        $sqlInsertar->prepareStatement($this->modelo->getAdapter(), $statement);
        $statement->execute();
        
        //calcular el factos de descuento segun la cantidad que se va a ingresar
        $cantidadR = $datos['cantidad_requerida'];
        $factor = $cantidadR / $buscaReactivoLaboratorio->getVolumenFinal();    //factor de transformacion para cada reactivo
        //buscar los reactivos requeridos de la solucion
        $lNReactivosSolucion = new ReactivosSolucionLogicaNegocio();
        $buscaReactivosSolucion = $lNReactivosSolucion->buscarLista(array('id_solucion' => $datos['id_solucion']));
        foreach ($buscaReactivosSolucion as $fila)
        {
            $statement = $this->modelo->getAdapter()
                    ->getDriver()
                    ->createStatement();
            $datosSaldosLaboratorios = array(
                'id_solucion' => $datos['id_solucion'],
                'id_reactivo_laboratorio' => $fila->id_reactivo_laboratorio,
                'cantidad' => $fila->cantidad_requerida * $factor,
                'tipo_ingreso' => 'EGRESO',
                'motivo' => "REACTIVO USADO EN SOLUCION: $buscaReactivoLaboratorio->nombre"
            );
            $tablaModelo = new SaldosLaboratoriosModelo($datosSaldosLaboratorios);
            $datosBd = $tablaModelo->getPrepararDatos();

            $sqlInsertar = $this->modelo->guardarSql('saldos_laboratorios', $this->modelo->getEsquema());
            $sqlInsertar->columns($this->columnasRegistroS());
            $sqlInsertar->values($datosBd, $sqlInsertar::VALUES_MERGE);
            $sqlInsertar->prepareStatement($this->modelo->getAdapter(), $statement);
            $statement->execute();
        }
        $proceso->commit();
        return true;
    }

}
