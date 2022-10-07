<?php

/**
 * Lógica del negocio de  UsuariosSolicitudModelo
 *
 * Este archivo se complementa con el archivo   UsuariosSolicitudControlador.
 *
 * @author DATASTAR
 * @uses       UsuariosSolicitudLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class UsuariosSolicitudLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new UsuariosSolicitudModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new UsuariosSolicitudModelo($datos);
        if ($tablaModelo->getIdUsuariosSolicitud() != null && $tablaModelo->getIdUsuariosSolicitud() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdUsuariosSolicitud());
        } else
        {
            unset($datos["id_usuarios_solicitud"]);
            return $this->modelo->guardar($datos);
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
     * @return UsuariosSolicitudModelo
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
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarUsuariosSolicitud($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_solicitud']))
            $arrayWhere[] = " id_solicitud = {$arrayParametros['id_solicitud']}";
        if ($arrayWhere)
        {
            $where = "WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        usol.id_usuarios_solicitud,
        usin.identificador,
        usin.nombre,
        usin.apellido,
        usol.id_solicitud,
        prov.id_localizacion,
        prov.nombre AS provincia,
        usol.tipo,
        usol.fecha_inicio,
        usol.fecha_fin,
        usol.estado
        FROM
        g_laboratorios.usuarios_solicitud AS usol
        INNER JOIN g_uath.ficha_empleado AS usin ON usin.identificador = usol.identificador
        LEFT JOIN g_catalogos.localizacion AS prov ON prov.id_localizacion = usol.id_localizacion
        $where
        ORDER BY usol.id_usuarios_solicitud ASC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Buscar usuarios según la aplicación
     * @param type $idUsuario
     * @param type $idAplicacion
     * @return type
     */
    public function buscarUsuarios($idUsuario, $idAplicacion)
    {
        $consulta = "SELECT
        usper.identificador,
        femp.nombre || ' ' || femp.apellido as usuarios
        FROM
        g_usuario.usuarios_perfiles AS usper
        INNER JOIN g_usuario.perfiles AS per ON per.id_perfil = usper.id_perfil
        INNER JOIN g_usuario.usuarios us ON us.identificador = usper.identificador
        INNER JOIN g_uath.ficha_empleado femp ON us.identificador = femp.identificador
        WHERE usper.identificador = '" . $idUsuario . "' AND per.id_aplicacion='" . $idAplicacion . "' AND per.estado=1 AND us.estado=1
        GROUP BY usper.identificador, femp.nombre, femp.apellido";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
}
