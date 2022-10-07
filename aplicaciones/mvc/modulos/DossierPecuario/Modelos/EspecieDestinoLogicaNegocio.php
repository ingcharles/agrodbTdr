<?php
/**
 * Lógica del negocio de EspecieDestinoModelo
 *
 * Este archivo se complementa con el archivo EspecieDestinoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    EspecieDestinoLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\DossierPecuario\Modelos\IModelo;

class EspecieDestinoLogicaNegocio implements IModelo
{

    private $modeloEspecieDestino = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloEspecieDestino = new EspecieDestinoModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new EspecieDestinoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdEspecieDestino() != null && $tablaModelo->getIdEspecieDestino() > 0) {
            return $this->modeloEspecieDestino->actualizar($datosBd, $tablaModelo->getIdEspecieDestino());
        } else {
            unset($datosBd["id_especie_destino"]);
            return $this->modeloEspecieDestino->guardar($datosBd);
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
        $this->modeloEspecieDestino->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return EspecieDestinoModelo
     */
    public function buscar($id)
    {
        return $this->modeloEspecieDestino->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloEspecieDestino->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloEspecieDestino->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarEspecieDestino()
    {
        $consulta = "SELECT * FROM " . $this->modeloEspecieDestino->getEsquema() . ". especie_destino";
        return $this->modeloEspecieDestino->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos de 
     * especies con la información de los items del catálogo
     *
     * @return array|ResultSet
     */
    public function obtenerInformacionEspecieDestino($idSolicitud)
    {
        $consulta = "SELECT
                        ed.*, e.nombre as especie
                    FROM
                        g_dossier_pecuario_mvc.especie_destino ed
                        INNER JOIN g_catalogos.especies e ON ed.id_especie = e.id_especies
                    WHERE
                    	ed.id_solicitud = $idSolicitud
                    ORDER BY
                        especie;";
        
        return $this->modeloEspecieDestino->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para contar los registros creados
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroRegistrosEspecieDestino($idSolicitud)
    {
        $consulta = "SELECT
                        count(id_solicitud) as numero
                    FROM
                        g_dossier_pecuario_mvc.especie_destino
                    WHERE
                    	id_solicitud = $idSolicitud;";

        return $this->modeloEspecieDestino->ejecutarSqlNativo($consulta);
    }

    /**
     * Función para copia de registros de una solicitud para modificación
     */
    public function copiarRegistrosEspecieDestino($idSolicitudOriginal, $idSolicitudNueva)
    {
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => " No existen registros en la tabla Especie Destino. ",
            'contenido' => null
        );

        $query = "id_solicitud = $idSolicitudOriginal ORDER BY 1";
        $esDes = $this->buscarLista($query);

        foreach ($esDes as $especieDest) {
            $arrayEspecieDestino = array(
                'id_solicitud' => $idSolicitudNueva, // null,//
                'id_especie' => $especieDest->id_especie,
                'nombre_especie' => $especieDest->nombre_especie
            );

            // print_r($arrayEspecieDestino);

            $idEfectoBio = $this->guardar($arrayEspecieDestino);

            if ($idEfectoBio > 0) {
                $validacion['estado'] = "exito";
                $validacion['mensaje'] = " Se ha guardado la informacion de la tabla Especie Destino. ";
                $validacion['bandera'] = true;
            } else {
                $validacion['estado'] = "Fallo";
                $validacion['mensaje'] = " No se pudo guardar la informacion de la tabla Especie Destino. ";
                $validacion['bandera'] = false;
            }
        }

        return $validacion;
    }
}