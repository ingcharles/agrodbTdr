<?php
/**
 * Lógica del negocio de PeriodoVidaUtilModelo
 *
 * Este archivo se complementa con el archivo PeriodoVidaUtilControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    PeriodoVidaUtilLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;

class PeriodoVidaUtilLogicaNegocio implements IModelo
{

    private $modeloPeriodoVidaUtil = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloPeriodoVidaUtil = new PeriodoVidaUtilModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new PeriodoVidaUtilModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdPeriodoVidaUtil() != null && $tablaModelo->getIdPeriodoVidaUtil() > 0) {
            return $this->modeloPeriodoVidaUtil->actualizar($datosBd, $tablaModelo->getIdPeriodoVidaUtil());
        } else {
            unset($datosBd["id_periodo_vida_util"]);
            return $this->modeloPeriodoVidaUtil->guardar($datosBd);
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
        $this->modeloPeriodoVidaUtil->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return PeriodoVidaUtilModelo
     */
    public function buscar($id)
    {
        return $this->modeloPeriodoVidaUtil->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloPeriodoVidaUtil->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloPeriodoVidaUtil->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarPeriodoVidaUtil()
    {
        $consulta = "SELECT * FROM " . $this->modeloPeriodoVidaUtil->getEsquema() . ". periodo_vida_util";
        return $this->modeloPeriodoVidaUtil->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosPeriodoVidaUtil($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Periodo Vida Util. ",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $perVidUt = $this->buscarLista($query);
        
        foreach($perVidUt as $periodoVida){
            $arrayPeriodoVidaUtil= array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'descripcion_envase' => $periodoVida->descripcion_envase,
                'periodo_vida_util' => $periodoVida->periodo_vida_util,
                'id_unidad_tiempo' => $periodoVida->id_unidad_tiempo,
                'nombre_unidad_periodo_vida' => $periodoVida->nombre_unidad_periodo_vida
            );
            
            //echo 'Periodo Vida Util';
            //print_r($arrayPeriodoVidaUtil);
            
            $idPerVidUt = $this->guardar($arrayPeriodoVidaUtil);
            
            if($idPerVidUt > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Periodo Vida Util. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Periodo Vida Util. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
    
    /**
     * Función para crear el producto en el módulo RIA
     */
    public function obtenerRegistrosPeriodoVidaUtilRIA($idSolicitud, $grupoProducto)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Periodo Vida Util. ",
            'periodoVidaUtil' => null
        );
        
        if ($grupoProducto != 'FM') {
            // Período de vida util (tabla Periodo Vida Util)
            $query = "id_solicitud = $idSolicitud ORDER BY 1 LIMIT 1";
            $perVida = $this->buscarLista($query);
            
            if (isset($perVida->current()->id_periodo_vida_util)) {
                
                $descripcionEnvase = $perVida->current()->descripcion_envase;
                $periodoVidaUtil = $perVida->current()->periodo_vida_util;
                $unidad = $perVida->current()->nombre_unidad_periodo_vida;
            } else {
                $descripcionEnvase = "NA";
                $periodoVidaUtil = "NA";
                $unidad = "NA";
            }
            
            $periodoVidaTotal = "Desc. Envase: " . $descripcionEnvase . ", Periodo vida útil: " . $periodoVidaUtil . " " . $unidad;
            
            $validacion['periodoVidaUtil'] = substr($periodoVidaTotal, 0, 2048);
        }
        
        return $validacion;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para contar los registros creados
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroRegistrosPeriodVidaUtil($idSolicitud)
    {
        $consulta = "SELECT
                        count(id_solicitud) as numero
                    FROM
                        g_dossier_pecuario_mvc.periodo_vida_util
                    WHERE
                    	id_solicitud = $idSolicitud;";
        
        return $this->modeloPeriodoVidaUtil->ejecutarSqlNativo($consulta);
    }
}