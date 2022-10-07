<?php
/**
 * Lógica del negocio de PuertosModelo
 *
 * Este archivo se complementa con el archivo PuertosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-04
 * @uses    PuertosLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;
use Agrodb\Token\Modelos\TokenLogicaNegocio;
use Agrodb\Core\Excepciones\BuscarExcepcion;
use Exception;

class PuertosLogicaNegocio implements IModelo
{

    private $modeloPuertos = null;
    private $lNegocioToken = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloPuertos = new PuertosModelo();
        $this->lNegocioToken = new TokenLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new PuertosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdPuerto() != null && $tablaModelo->getIdPuerto() > 0) {
            return $this->modeloPuertos->actualizar($datosBd, $tablaModelo->getIdPuerto());
        } else {
            unset($datosBd["id_puerto"]);
            return $this->modeloPuertos->guardar($datosBd);
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
        $this->modeloPuertos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return PuertosModelo
     */
    public function buscar($id)
    {
        return $this->modeloPuertos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloPuertos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloPuertos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarPuertos()
    {
        $consulta = "SELECT * FROM " . $this->modeloPuertos->getEsquema() . ". puertos";
        return $this->modeloPuertos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Busca un determinado medio de transporte por nombre.
     *
     * @return ResultSet
     */
    public function buscarPuertoPorNombreXPais($nombreBusqueda, $idPais)
    {
        $where = "upper(unaccent(nombre_puerto)) = upper(unaccent('$nombreBusqueda')) and id_pais = $idPais";
        return $this->modeloPuertos->buscarLista($where);
    }
    
    /**
     * Busca un determinado medio de transporte por nombre.
     *
     * @return ResultSet
     */
    public function buscarPuertoPorNombreXProvincia($nombreBusqueda, $idProvincia)
    {
        $where = "upper(unaccent(nombre_puerto)) = upper(unaccent('$nombreBusqueda')) and id_provincia = $idProvincia";
        return $this->modeloPuertos->buscarLista($where);
    }
    
    /**
     * Busca un puerto por codigo de puerto y pais.
     *
     * @return ResultSet
     */
    public function buscarPuertoPorCodigoXIdPais($codigoBusqueda, $idPais)
    {
        $where = "upper(unaccent(codigo_puerto)) = upper(unaccent('" . $codigoBusqueda . "')) and id_pais = $idPais";
        return $this->modeloPuertos->buscarLista($where);
    }

    /**
     * Obtiene el catálogo de puertos de ingreso y salida.
     *
     * @return ResultSet
     */
    public function obtenerCatalagoPuertosIngresoSalida()
    {
        $arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

        if($arrayToken['estado'] == 'exito'){
            $consulta = "SELECT row_to_json(res) as res FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) as puertosCatalogo FROM (
                SELECT
                     p.id_puerto,
                     p.nombre_puerto AS nombre,
                     (
                             SELECT array_to_json(array_agg(row_to_json(l_a))) FROM (
                                 SELECT
                                     li.nombre,
                                     li.id_puerto
                                 FROM
                                     g_catalogos.lugares_inspeccion li
                                 WHERE
                                     li.id_puerto = p.id_puerto
                     ) l_a) AS lugar_inspeccion
                 FROM
                     g_catalogos.puertos p
                 WHERE
                     p.id_pais = 66 -- Código para Ecuador
                     AND p.nombre_provincia IS NOT NULL
                     AND (SELECT  COUNT(li2.id_puerto) FROM g_catalogos.lugares_inspeccion li2 WHERE li2.id_puerto = p.id_puerto ) != 0
                 ORDER BY
                     1
             ) as listado ) AS res;";

            try {
                $res = $this->modeloPuertos->ejecutarSqlNativo($consulta);
                $array['estado'] = 'exito';
                $array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";				
                $array['cuerpo'] = json_decode($res->current()->res);
                echo json_encode($array);		
            } catch (Exception $ex) {
                $array['estado'] = 'error';
                $array['mensaje'] = 'Error al obtener datos: ' . $ex;
                http_response_code(400);
                echo json_encode($array);
				throw new BuscarExcepcion($ex, array('archivo' => 'PuertosLogicaNegocio', 'metodo' => 'obtenerCatalagoPuertosIngresoSalida', 'consulta' => $consulta));
			}

        } else{
            echo json_encode($arrayToken);
        }
        
    }
}