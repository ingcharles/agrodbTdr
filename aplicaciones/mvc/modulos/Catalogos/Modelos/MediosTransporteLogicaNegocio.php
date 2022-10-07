<?php
/**
 * Lógica del negocio de MediosTransporteModelo
 *
 * Este archivo se complementa con el archivo MediosTransporteControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    MediosTransporteLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class MediosTransporteLogicaNegocio implements IModelo
{

    private $modeloMediosTransporte = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloMediosTransporte = new MediosTransporteModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new MediosTransporteModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdMediosTransporte() != null && $tablaModelo->getIdMediosTransporte() > 0) {
            return $this->modeloMediosTransporte->actualizar($datosBd, $tablaModelo->getIdMediosTransporte());
        } else {
            unset($datosBd["id_medios_transporte"]);
            return $this->modeloMediosTransporte->guardar($datosBd);
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
        $this->modeloMediosTransporte->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return MediosTransporteModelo
     */
    public function buscar($id)
    {
        return $this->modeloMediosTransporte->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloMediosTransporte->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloMediosTransporte->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarMediosTransporte()
    {
        $consulta = "SELECT * FROM " . $this->modeloMediosTransporte->getEsquema() . ". medios_transporte";
        return $this->modeloMediosTransporte->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Busca un determinado medio de transporte por nombre.
     *
     * @return ResultSet 
     */
    public function buscarMedioTransportePorNombre($nombreBusqueda)
    {
        $where = "upper(unaccent(tipo)) = upper(unaccent('$nombreBusqueda'))";
        return $this->modeloMediosTransporte->buscarLista($where);
    }
    
    /**
     * Busca un determinado medio de transporte por codigo.
     * @return ResultSet
     */
    public function buscarMedioTransportePorCodigo($codigoBusqueda)
    {
        $where = "codigo = '$codigoBusqueda'";
        return $this->modeloMediosTransporte->buscarLista($where);
    }
}