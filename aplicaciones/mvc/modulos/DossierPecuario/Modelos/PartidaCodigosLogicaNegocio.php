<?php
/**
 * Lógica del negocio de PartidaCodigosModelo
 *
 * Este archivo se complementa con el archivo PartidaCodigosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    PartidaCodigosLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;
use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;

class PartidaCodigosLogicaNegocio implements IModelo
{

    private $modeloPartidaCodigos = null;
    private $lNegocioProductos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloPartidaCodigos = new PartidaCodigosModelo();
        $this->lNegocioProductos = new ProductosLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new PartidaCodigosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdPartidaCodigo() != null && $tablaModelo->getIdPartidaCodigo() > 0) {
            return $this->modeloPartidaCodigos->actualizar($datosBd, $tablaModelo->getIdPartidaCodigo());
        } else {
            unset($datosBd["id_partida_codigo"]);
            return $this->modeloPartidaCodigos->guardar($datosBd);
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
        $this->modeloPartidaCodigos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return PartidaCodigosModelo
     */
    public function buscar($id)
    {
        return $this->modeloPartidaCodigos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloPartidaCodigos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloPartidaCodigos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarPartidaCodigos()
    {
        $consulta = "SELECT * FROM " . $this->modeloPartidaCodigos->getEsquema() . ". partida_codigos";
        return $this->modeloPartidaCodigos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para contar los registros creados
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroRegistrosPartidaCodigos($idSolicitud)
    {
        $consulta = "SELECT
                        count(id_solicitud) as numero
                    FROM
                        g_dossier_pecuario_mvc.partida_codigos
                    WHERE
                    	id_solicitud = $idSolicitud;";

        return $this->modeloPartidaCodigos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para contar los registros creados
     * con una partida arancelaria
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroRegistrosPartidaCodigosXPartida($idSolicitud, $partidaArancelaria)
    {
        $consulta = "SELECT
                        count(distinct id_solicitud) as numero
                    FROM
                        g_dossier_pecuario_mvc.partida_codigos
                    WHERE
                    	id_solicitud = $idSolicitud and
                        partida_arancelaria = '$partidaArancelaria';";

        return $this->modeloPartidaCodigos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para buscar la partida aracnelaria única
     *
     * @return array|ResultSet
     */
    public function obtenerPartidaUnica($idSolicitud)
    {
        $consulta = "SELECT
                        distinct (partida_arancelaria) 
                    FROM
                        g_dossier_pecuario_mvc.partida_codigos
                    WHERE
                    	id_solicitud = $idSolicitud;";

        // echo $consulta;
        return $this->modeloPartidaCodigos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosPartidaCodigos($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Partida Codigos. ",
            'contenido' => null
        );
        
        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $parC = $this->buscarLista($query);
        
        foreach($parC as $parCod){
            $arrayPartidaCod= array( 'id_solicitud' => $idSolicitudNueva, //null,//
                'partida_arancelaria' => $parCod->partida_arancelaria,
                'id_codigo_complementario' => $parCod->id_codigo_complementario,
                'codigo_complementario' => $parCod->codigo_complementario,
                'id_codigo_suplementario' => $parCod->id_codigo_suplementario,
                'codigo_suplementario' => $parCod->codigo_suplementario
            );
            
            //echo 'Partida Codigos';
            //print_r($arrayPartidaCod);
            
            $idOrigenProd = $this->guardar($arrayPartidaCod);
            
            if($idOrigenProd > 0){
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Partida Codigos. ";
                $validacion['bandera'] = true;
            }else{
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Partida Codigos. ";
                $validacion['bandera'] = false;
            }
        }
        
        return $validacion;
    }
    
    /**
     * Función para crear código de producto por partida arancelaria para crear el producto en el módulo RIA
     */
    public function obtenerRegistrosPartidaCodigosRIA($idSolicitud)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Partida Codigos. ",
            'partida' => null,
            'codigoProducto' => null
        );
        
        // Verificar partida arancelaria y generar código de producto
        $registroPartida = $this->obtenerPartidaUnica($idSolicitud);
        
        if (isset($registroPartida->current()->partida_arancelaria)) {
            $partida = $registroPartida->current()->partida_arancelaria;
            
            if ($partida != 0) {
                $validacion['partida'] = $partida;
                $validacion['codigoProducto'] = $this->lNegocioProductos->generarCodigoProductoPartida($partida);
            } else {
                $validacion['partida'] = null;
                $validacion['codigoProducto'] = 0;
            }
        } else {
            $validacion['partida'] = null;
            $validacion['codigoProducto'] = 0;
        }
        
        return $validacion;
    }
}