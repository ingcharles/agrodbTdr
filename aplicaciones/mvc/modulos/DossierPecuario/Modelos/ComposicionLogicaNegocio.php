<?php
/**
 * Lógica del negocio de ComposicionModelo
 *
 * Este archivo se complementa con el archivo ComposicionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    ComposicionLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;

class ComposicionLogicaNegocio implements IModelo
{

    private $modeloComposicion = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloComposicion = new ComposicionModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ComposicionModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdComposicion() != null && $tablaModelo->getIdComposicion() > 0) {
            return $this->modeloComposicion->actualizar($datosBd, $tablaModelo->getIdComposicion());
        } else {
            unset($datosBd["id_composicion"]);
            return $this->modeloComposicion->guardar($datosBd);
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
        $this->modeloComposicion->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ComposicionModelo
     */
    public function buscar($id)
    {
        return $this->modeloComposicion->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloComposicion->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloComposicion->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarComposicion()
    {
        $consulta = "SELECT * FROM " . $this->modeloComposicion->getEsquema() . ". composicion";
        return $this->modeloComposicion->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos del operador
     * de acuerdo al identificador del operador
     *
     * @return array|ResultSet
     */
    public function obtenerInformacionComposicion($idSolicitud)
    {
        $consulta = "SELECT 
                        c.*, tc.tipo_componente, ia.ingrediente_activo as nombre_componente
                    FROM 
                        g_dossier_pecuario_mvc.composicion c
                        INNER JOIN g_catalogos.tipo_componente tc ON tc.id_tipo_componente = c.id_tipo_componente
                        INNER JOIN g_catalogos.ingrediente_activo_inocuidad ia ON ia.id_ingrediente_activo = c.id_nombre_componente
                    WHERE
                    	c.id_solicitud = $idSolicitud;";
        
        return $this->modeloComposicion->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para contar los registros creados
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroRegistrosComposicion($idSolicitud)
    {
        $consulta = "SELECT
                        count(id_solicitud) as numero
                    FROM
                        g_dossier_pecuario_mvc.composicion
                    WHERE
                    	id_solicitud = $idSolicitud;";
        
        return $this->modeloComposicion->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosComposicion($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Composicion. ",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $comp = $this->buscarLista($query);
        
        foreach($comp as $composicion){
            $arrayComposicion = array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'cada' => $composicion->cada,
                'id_unidad' => $composicion->id_unidad,
                'nombre_unidad' => $composicion->nombre_unidad,
                'id_tipo_componente' => $composicion->id_tipo_componente,
                'id_nombre_componente' => $composicion->id_nombre_componente,
                'cantidad' => $composicion->cantidad,
                'id_unidad_componente' => $composicion->id_unidad_componente,
                'nombre_unidad_componente' => $composicion->nombre_unidad_componente
            );
            
            //print_r($arrayComposicion);
            
            $idComposicion = $this->guardar($arrayComposicion);
            
            if($idComposicion > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Composicion. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Composicion. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
}