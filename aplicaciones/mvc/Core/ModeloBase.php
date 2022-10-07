<?php

/**
 * https://docs.zendframework.com/zend-db/adapter/
 */

namespace Agrodb\Core;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Exception;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Agrodb\Core\Excepciones\ActualizarExcepcion;
use Agrodb\Core\Excepciones\BorrarExcepcion;
use Agrodb\Core\Excepciones\BuscarExcepcion;
use Agrodb\Core\Excepciones\ConexionExcepcion;
use Agrodb\Core\Excepciones\GuardarExcepcion;

class ModeloBase extends AbstractTableGateway {

    /**
     *
     * @var null Database Connection
     */
    protected $adapter = null;

    /**
     * Para para validar cuando un campo es requirido
     */
    const REQUERIDO = true;
    const NO_REQUERIDO = false;

    /**
     * Constructor
     *
     * @param string|TableIdentifier|array $table
     * @param AdapterInterface $adapter
     * @param Feature\AbstractFeature|Feature\FeatureSet|Feature\AbstractFeature[]|null $features
     * @param ResultSetInterface|null $resultSetPrototype
     * @param Sql|null $sql
     *
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($schema, $table, $features = null, ResultSetInterface $resultSetPrototype = null, Sql $sql = null) {
        self::openDatabaseConnection($schema);

        if (!(is_string($table) || $table instanceof TableIdentifier || is_array($table))) {
            throw new Exception\InvalidArgumentException('El nombre de la tabla debe ser una cadena o una instancia de Zend\Db\Sql\TableIdentifier');
        }
        $this->table = new TableIdentifier($table, $schema);

        if ($features !== null) {
            if ($features instanceof Feature\AbstractFeature) {
                $features = [
                    $features
                ];
            }
            if (is_array($features)) {
                $this->featureSet = new Feature\FeatureSet($features);
            } elseif ($features instanceof Feature\FeatureSet) {
                $this->featureSet = $features;
            } else {
                throw new Exception\InvalidArgumentException('TableGateway espera que $feature sea una instancia  de AbstractFeature o de FeatureSet, en un array de AbstractFeatures');
            }
        } else {
            $this->featureSet = new Feature\FeatureSet();
        }

        // result prototype
        $this->resultSetPrototype = ($resultSetPrototype) ?: new ResultSet();

        // Sql object (factory for select, insert, update, delete)
        $this->sql = ($sql) ?: new Sql($this->adapter, $this->table);

        // check sql object bound to same table
        if ($this->sql->getTable() != $this->table) {
            throw new Exception\InvalidArgumentException('La tabla dentro del objeto Sql proporcionado debe coincidir con la tabla de este TableGateway');
        }

        $this->initialize();
    }

    /**
     * Open the database connection with the credentials from application/config/config.php
     */
    private function openDatabaseConnection($esquema) {
        try {
            $config = [
                'driver' => DB_DRIVER,
                'hostname' => DB_HOST,
                'database' => DB_NAME,
                'schema' => $esquema,
                'username' => DB_USER,
                'password' => DB_PASS
            ];
            if (!isset($this->adapter)) {

                $this->adapter = new Adapter($config);
            }
        } catch (\Exception $ex) {
            Mensajes::fallo(Constantes::ERROR_CONEXION);
            throw new ConexionExcepcion($ex, $config);
        }
    }

    /**
     * Select
     *
     * @param Where|\Closure|string|array $where
     * @return ResultSet
     */
    public function buscarLista($where, $order = null, $limit = null, $offset = null) {
        try {
            if ($order != null) {
                return self::select($where, $order, $limit, $offset);
            } else {
                return parent::select($where);
            }
        } catch (\Zend\Db\Adapter\Exception\InvalidQueryException $ex) {
            Mensajes::fallo(Constantes::ERROR_SELECCIONAR);
            throw new BuscarExcepcion($ex, $where);
        }
    }

    /**
     * Ejecuta una consulta con parametros de orden, limite y 
     * @param \Closure $where
     * @param type $order
     * @param type $limit
     * @param type $offset
     * @return type
     */
    public function select($where = null, $order = null, $limit = null, $offset = null) {
        if (!$this->isInitialized) {
            $this->initialize();
        }
        $select = null;
        if ($limit != null && $offset != null) {
            $select = $this->sql->select()->order($order)->limit($limit)->offset($offset);
        }if($limit != null){
        	$select = $this->sql->select()->order($order)->limit($limit);
        } else {
            $select = $this->sql->select()->order($order);
        }


        if ($where instanceof \Closure) {
            $where($select);
        } elseif ($where !== null) {
            $select->where($where);
        }

        return $this->selectWith($select);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return Array ResultSet
     */
    public function buscar($where) {
        try {
            return (array) parent::select($where)->current();
        } catch (\Zend\Db\Adapter\Exception\InvalidQueryException $ex) {
            Mensajes::fallo(Constantes::ERROR_SELECCIONAR);
            throw new BuscarExcepcion($ex, $where);
        }
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return Array ResultSet
     */
    public function buscarTodo() {
        try {
            return parent::select();
        } catch (\Zend\Db\Adapter\Exception\InvalidQueryException $ex) {
            Mensajes::fallo(Constantes::ERROR_SELECCIONAR);
            throw new BuscarExcepcion($ex, $where);
        }
    }

    /**
     * Ejecuta una consulta SQL definida por el usuario
     *
     * @param type $consulta
     * @return type ResultInterface
     */
    public function ejecutarConsulta($consulta) {
        try {
            $query = $this->adapter->query($consulta);
            $result = $query->execute();
            return $result;
        } catch (\Zend\Db\Adapter\Exception\InvalidQueryException $ex) {
            Mensajes::fallo(Constantes::ERROR_SELECCIONAR);
            throw new BuscarExcepcion($ex, $consulta);
        }
    }

    public function guardar(Array $tabla) {
        try {
            if ($this->insert($tabla) > 0) {
                return $this->getLastInsertValue();
            } else {
                return false;
            }
        } catch (\Zend\Db\Adapter\Exception\InvalidQueryException $ex) {
            Mensajes::fallo(Constantes::ERROR_GUARDAR);
            throw new GuardarExcepcion($ex, $tabla);
        }
    }

    public function actualizar(Array $tabla, $where) {
        try {

            return $this->update($tabla, $where);
        } catch (\Zend\Db\Adapter\Exception\InvalidQueryException $ex) {
            Mensajes::fallo(Constantes::ERROR_ACTUALIZAR);
            throw new ActualizarExcepcion($ex, $tabla, $where);
        }
    }

    /**
     * Borra un registro de la base de datos
     *
     * @param int $id
     * @return int
     */
    public function borrar($where) {
        try {
            return $this->delete($where);
        } catch (\Zend\Db\Adapter\Exception\InvalidQueryException $ex) {
            Mensajes::fallo(Constantes::ERROR_ELIMINAR);
            throw new BorrarExcepcion($ex, $where);
        }
    }

    /**
     * Permite guardar con la misma conexión los datos de las tablas detalle
     *
     * @param String $tabla
     * @return \Zend\Db\Sql\Insert
     */
    public function guardarSql($table, $schema = null) {
        $this->table = new TableIdentifier($table, $schema);
        return new Insert($this->table);
    }

    /**
     * Permite actualizar con la misma conexión los datos de las tablas detalle
     * 
     * @param type $table
     * @param type $schema
     * @return \Zend\Db\Sql\Update
     */
    public function actualizarSql($table, $schema = null) {
        $this->table = new TableIdentifier($table, $schema);
        return new Update($this->table);
    }

    /**
     * Permite eliminar con la misma conexión los datos de las tablas detalle
     * 
     * @param type $table
     * @param type $schema
     * @return Delete
     */
    public function borrarSql($table, $schema = null) {

        $this->table = new TableIdentifier($table, $schema);
        return new Delete($this->table);
    }

    /**
     * Ejecuta código nativo de SQL
     * @param type código SQL
     * @return Driver\StatementInterface|ResultSet\ResultSet
     * * @throws Exception\InvalidArgumentException
     */
    public function ejecutarSqlNativo($query) {      
        try {
            return $this->adapter->query($query, Adapter::QUERY_MODE_EXECUTE);
        } catch (\Zend\Db\Adapter\Exception\InvalidQueryException $ex) {
            //Mensajes::fallo(Constantes::ERROR_SELECCIONAR);
            throw new BuscarExcepcion($ex);
        }
    }

   

}
