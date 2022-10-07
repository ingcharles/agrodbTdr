<?php

/**
 * Lógica del negocio de  UsuariosPerfilesModelo
 *
 * Este archivo se complementa con el archivo   UsuariosPerfilesControlador.
 *
 * @author DATASTAR
 * @uses       UsuariosPerfilesLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Usuarios\Modelos;

use Agrodb\Usuarios\Modelos\IModelo;

class UsuariosPerfilesLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new UsuariosPerfilesModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new UsuariosPerfilesModelo($datos);
        if ($tablaModelo->getIdentificador() != null && $tablaModelo->getIdentificador() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdentificador());
        } else
        {
            unset($datos["identificador"]);
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
     * @return UsuariosPerfilesModelo
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
    public function buscarUsuariosPerfiles($idUsuario, $idAplicacion = null)
    {
        $param = "";
        if ($idAplicacion !== null)
        {
            $param = " AND per.id_aplicacion= $idAplicacion ";
        }
        $consulta = "SELECT
						usper.identificador,
						per.nombre as perfil,
						per.codificacion_perfil,
						femp.nombre || ' ' || femp.apellido as usuario
					FROM
						g_usuario.usuarios_perfiles AS usper
						INNER JOIN g_usuario.perfiles AS per ON per.id_perfil = usper.id_perfil
						INNER JOIN g_usuario.usuarios us ON us.identificador = usper.identificador
						INNER JOIN g_uath.ficha_empleado femp ON us.identificador = femp.identificador
					WHERE 
						usper.identificador = '$idUsuario' AND per.estado=1 AND us.estado=1 $param";

        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * 
     * @param type $identificador
     * @return type
     */
    public function buscarDatosoperador($identificador)
    {
        $consulta = "SELECT * FROM g_operadores.operadores WHERE identificador = '$identificador'";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    public function borrarPorIdentificadorPerfil($identificador, $idPerfil)
    {
    	$this->modelo->borrarPorIdentificadorPerfil($identificador, $idPerfil);
    }
	
	/**
     * Ejecuta una consulta(SQL) personalizada para obtener un listado de usuarios
     * con datos a partir de un perfil y aplicación.
     *
     * @return array|ResultSet
     */
    public function buscarUsuariosXAplicacionPerfil($identificador, $codificacionPerfil, $idAplicacion = null)
    {
        $param = "";
        if ($idAplicacion !== null){
            $param = " AND per.id_aplicacion= $idAplicacion ";
        }
        
        $consulta = "SELECT
						usper.identificador,
						per.nombre as perfil,
						per.codificacion_perfil,
						femp.nombre || ' ' || femp.apellido as usuario
					FROM
						g_usuario.usuarios_perfiles AS usper
						INNER JOIN g_usuario.perfiles AS per ON per.id_perfil = usper.id_perfil
						INNER JOIN g_usuario.usuarios us ON us.identificador = usper.identificador
						INNER JOIN g_uath.ficha_empleado femp ON us.identificador = femp.identificador
					WHERE
						usper.identificador = '$identificador' AND 
                        per.estado=1 AND 
                        us.estado=1 AND
                        per.codificacion_perfil = '$codificacionPerfil' $param";
        
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
	
	
	/**
     * 
     * @param type $identificador
     * @return type
     */
    public function buscarPerfinInterno($arrayParametros)
    {

        $identificador = trim(pg_escape_string($arrayParametros['identificador']));

        $consulta = "SELECT 
                        p.id_perfil
                    FROM 
                        g_usuario.perfiles p, g_usuario.usuarios_perfiles up
                    WHERE
                        p.codificacion_perfil='PFL_USUAR_INT'
                        and up.identificador='$identificador'
                        and p.id_perfil = up.id_perfil;";

        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    /**
     *
     * @param type $codigoperfil
     * @return type
     */
    public function buscarUsuariosInternosPorPerfil($arrayParametros)
    {
        
        $codigoPerfil = $arrayParametros['codigo_perfil'];
        
        $consulta = "SELECT
                    	DISTINCT(fe.identificador)
                    	, fe.nombre
                    	, fe.apellido
                    	, dc.provincia
                    FROM
                    	g_usuario.usuarios u,
                    	g_usuario.usuarios_perfiles up,
                    	g_uath.ficha_empleado fe,
                    	g_uath.datos_contrato dc
                    WHERE
                    	u.identificador = fe.identificador 
                    	and u.estado = 1
                        and dc.estado = 1
                    	and u.identificador = up.identificador
                    	and u.identificador = dc.identificador
                    	and up.id_perfil = (SELECT
                    						id_perfil
                    					FROM
                    						g_usuario.perfiles
                    					WHERE 
                    						codificacion_perfil  in " . $codigoPerfil . "
                    						and estado = 1)";
        
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
	
	 /**
     * Ejecuta una consulta(SQL) personalizada para obtener un listado de usuarios
     * con datos a partir de un perfil y aplicación.
     *
     * @return array|ResultSet
     */
    public function buscarUsuariosXPerfilProvincia($codificacionPerfil, $idProvincia)
    {
        $consulta = "SELECT
						usper.identificador,
						per.nombre as perfil,
						per.codificacion_perfil,
						femp.nombre || ' ' || femp.apellido as usuario
					FROM
						g_usuario.usuarios_perfiles usper
						INNER JOIN g_usuario.perfiles per ON per.id_perfil = usper.id_perfil
						INNER JOIN g_usuario.usuarios us ON us.identificador = usper.identificador
                        INNER JOIN g_uath.ficha_empleado femp ON us.identificador = femp.identificador
						INNER JOIN g_estructura.funcionarios f ON f.identificador = femp.identificador
					WHERE
                        per.estado=1 AND
                        us.estado=1 AND
                        f.id_provincia = $idProvincia AND
                        per.codificacion_perfil = '$codificacionPerfil'";
        
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener un listado de usuarios
     * con datos a partir de un perfil y aplicación.
     *
     * @return array|ResultSet
     */
    public function buscarUsuariosIEXPerfilProvincia($codificacionPerfil, $idProvincia)
    {
        $consulta = "SELECT
						distinct usper.identificador,
						per.nombre as perfil,
						per.codificacion_perfil,
						femp.nombre || ' ' || femp.apellido as usuario
					FROM
						g_usuario.usuarios_perfiles usper
						INNER JOIN g_usuario.perfiles per ON per.id_perfil = usper.id_perfil
						INNER JOIN g_usuario.usuarios us ON us.identificador = usper.identificador
                        INNER JOIN g_uath.ficha_empleado femp ON us.identificador = femp.identificador
                        INNER JOIN g_uath.datos_contrato dc ON us.identificador = dc.identificador
                        INNER JOIN g_catalogos.localizacion l ON dc.provincia = l.nombre
					WHERE
                        per.estado=1 AND
                        us.estado=1 AND
                        l.id_localizacion = $idProvincia AND
                        per.codificacion_perfil = '$codificacionPerfil' AND
						dc.estado=1";
        
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
}