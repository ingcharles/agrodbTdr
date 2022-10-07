<?php
/**
 * Lógica del negocio de InspeccionModelo
 *
 * Este archivo se complementa con el archivo InspeccionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    InspeccionLogicaNegocio
 * @package AdministrarOperacionesGuia
 * @subpackage Modelos
 */
namespace Agrodb\RevisionFormularios\Modelos;

class InspeccionLogicaNegocio implements IModelo
{
    
    private $modeloInspeccion = null;
    
    
    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloInspeccion = new InspeccionModelo();
    }
    
    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new InspeccionModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdInspeccion() != null && $tablaModelo->getIdInspeccion() > 0) {
            return $this->modeloInspeccion->actualizar($datosBd, $tablaModelo->getIdInspeccion());
        } else {
            unset($datosBd["id_inspeccion"]);
            return $this->modeloInspeccion->guardar($datosBd);
        }
    }
    
    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloInspeccion->borrar($id);
    }
    
    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return InspeccionModelo
     */
    public function buscar($id)
    {
        return $this->modeloInspeccion->buscar($id);
    }
    
    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloInspeccion->buscarTodo();
    }
    
    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where=null, $order=null, $count=null, $offset=null)
    {
        return $this->modeloInspeccion->buscarLista($where, $order, $count, $offset);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarInspeccion()
    {
        $consulta = "SELECT * FROM ".$this->modeloInspeccion->getEsquema().". inspeccion";
        return $this->modeloInspeccion->ejecutarSqlNativo($consulta);
    }
    /**
     * Columnas para guardar junto con el formulario
     * @return string[]
     */
    public function columnas()
    {
        $columnas = array(
            'id_grupo',
            'identificador_inspector',
            'fecha_inspeccion',
            'observacion',
            'estado',
            'orden',
            'ruta_archivo'
        );
        return $columnas;
    }
}
