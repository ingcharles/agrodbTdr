<?php
/**
 * Lógica del negocio de CatastroPredioEquidosModelo
 *
 * Este archivo se complementa con el archivo CatastroPredioEquidosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-16
 * @uses    CatastroPredioEquidosLogicaNegocio
 * @package ProgramasControlOficial
 * @subpackage Modelos
 */
namespace Agrodb\ProgramasControlOficial\Modelos;

use Agrodb\ProgramasControlOficial\Modelos\IModelo;

class CatastroPredioEquidosLogicaNegocio implements IModelo
{

    private $modeloCatastroPredioEquidos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloCatastroPredioEquidos = new CatastroPredioEquidosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new CatastroPredioEquidosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdCatastroPredioEquidos() != null && $tablaModelo->getIdCatastroPredioEquidos() > 0) {
            return $this->modeloCatastroPredioEquidos->actualizar($datosBd, $tablaModelo->getIdCatastroPredioEquidos());
        } else {
            unset($datosBd["id_catastro_predio_equidos"]);
            return $this->modeloCatastroPredioEquidos->guardar($datosBd);
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
        $this->modeloCatastroPredioEquidos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return CatastroPredioEquidosModelo
     */
    public function buscar($id)
    {
        return $this->modeloCatastroPredioEquidos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloCatastroPredioEquidos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloCatastroPredioEquidos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarCatastroPredioEquidos()
    {
        $consulta = "SELECT * FROM " . $this->modeloCatastroPredioEquidos->getEsquema() . ". catastro_predio_equidos";
        return $this->modeloCatastroPredioEquidos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las provincias en donde un operador tiene predios registrados
     *
     * @return array|ResultSet
     */
    public function buscarProvinciasOperador($identificador)
    {
        $consulta = "   SELECT 
                        	distinct id_provincia, provincia
                        FROM 
                        	g_programas_control_oficial.catastro_predio_equidos
                        WHERE
                        	cedula_propietario = '$identificador';";

        return $this->modeloCatastroPredioEquidos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las provincias en donde se tiene predios registrados
     *
     * @return array|ResultSet
     */
    public function buscarProvinciasXPrediosRegistrados()
    {
        $consulta = "   SELECT
                        	distinct id_provincia, provincia
                        FROM
                        	g_programas_control_oficial.catastro_predio_equidos
                        ORDER BY
                            provincia ASC;";

        return $this->modeloCatastroPredioEquidos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las provincias en donde se tiene predios registrados
     *
     * @return array|ResultSet
     */
    public function crearNumeroCertificado($codigoParroquia)
    {
        $formatoCodigo = "PCO-CPE-".$codigoParroquia;
        
        $consulta = "   SELECT
							MAX(num_solicitud) as num_solicitud
						FROM
							g_programas_control_oficial.catastro_predio_equidos
						WHERE
							num_solicitud LIKE '%$formatoCodigo%';";
        
        $numeracion = $this->modeloCatastroPredioEquidos->ejecutarSqlNativo($consulta);
        $fila = $numeracion->current();
        
        $codigoCatastro = array(
            'numero' => $fila['num_solicitud']
        );
        
        if($codigoCatastro['numero'] != null){
            $tmp= explode("-", $codigoCatastro['numero']);
            $incremento = end($tmp)+1;
        }else{
            $incremento = 1;
        }

        $idCodigo = $formatoCodigo .'-'. str_pad($incremento, 4, "0", STR_PAD_LEFT);
        
        return $idCodigo;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las provincias en donde se tiene predios registrados
     *
     * @return array|ResultSet
     */
    public function buscarPRedioXSitioArea($idSitio, $idArea)
    {
        $consulta = "   SELECT
                        	*
                        FROM
                        	g_programas_control_oficial.catastro_predio_equidos
                        WHERE
                            id_sitio = $idSitio and
                            id_area = $idArea
                        ORDER BY
                            provincia ASC;";
        
        return $this->modeloCatastroPredioEquidos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las provincias en donde se tiene pasaportes equinos en predios registrados
     *
     * @return array|ResultSet
     */
    public function buscarProvinciasXPasaportePrediosRegistrados()
    {
        $consulta = "   SELECT
                        	distinct cpe.id_provincia, cpe.provincia
                        FROM
                        	g_programas_control_oficial.catastro_predio_equidos cpe
                            INNER JOIN g_pasaporte_equino.equinos e ON cpe.id_catastro_predio_equidos = e.ubicacion_actual
                        ORDER BY
                            cpe.provincia ASC;";
        
        return $this->modeloCatastroPredioEquidos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las provincias en donde se tiene pasaportes equinos en predios registrados
     *
     * @return array|ResultSet
     */
    public function buscarCantonesXPasaportePrediosRegistrados($idProvincia)
    {
        $consulta = "   SELECT
                        	distinct cpe.id_canton, cpe.canton
                        FROM
                        	g_programas_control_oficial.catastro_predio_equidos cpe
                            INNER JOIN g_pasaporte_equino.equinos e ON cpe.id_catastro_predio_equidos = e.ubicacion_actual
                        WHERE
                            cpe.id_provincia = $idProvincia
                        ORDER BY
                            cpe.canton ASC;";
        
        return $this->modeloCatastroPredioEquidos->ejecutarSqlNativo($consulta);
    }
}