<?php

/**
 * Lógica del negocio de  UsuariosModelo
 *
 * Este archivo se complementa con el archivo   UsuariosControlador.
 *
 * @author AGROCALIDAD
 * @fecha 2018-10-03
 * @uses       UsuariosLogicaNegocio
 * @package usuarios
 * @subpackage Modelos
 */

namespace Agrodb\Usuarios\Modelos;

use Agrodb\Usuarios\Modelos\IModelo;

class UsuariosLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new UsuariosModelo();
    }

    /**
     * Guarda el registro actual
     * 
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        if ($datos['estado'] === 'Activo') {
            $datos['estado'] = 1;
        } else if ($datos['estado'] === 'Inactivo') {
            $datos['estado'] = 3;
        }

        $datos['clave'] = md5($datos['identificador']);
        $datos['observacion_usuario'] = $datos['observacion_usuario'] . '¬' . $_SESSION['usuario'];

        $tablaModelo = new UsuariosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdentificador() != null && $tablaModelo->getIdentificador() > 0) {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdentificador());
        } else {
            unset($datosBd["identificador"]);
            return $this->modelo->guardar($datosBd);
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
        $this->modelo->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return UsuariosModelo
     */
    public function buscar($id)
    {
        $datosUsuario = $this->modelo->buscar($id);
        $this->obtenerNomenclaturaEstado($datosUsuario);
        return $datosUsuario;
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
    public function buscarUsuarios()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". usuarios";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    public function obtenerNomenclaturaEstado($datosUsuario)
    {
        if ($datosUsuario->getEstado() == '1') {
            $datosUsuario->setEstadoNomenclatura("Activo");
        } else if ($datosUsuario->getEstado() == '3') {
            $datosUsuario->setEstadoNomenclatura("Inactivo");
        }
    }

    /**
     * Método para generar código con encriptación md5
     */
    public function generarCodigoMD5($longitud)
    {
        $cadena = "[^A-Z0-9]";
        return substr(
            preg_replace($cadena, "", md5(rand())) .
                preg_replace($cadena, "", md5(rand())) .
                preg_replace($cadena, "", md5(rand())),
            0,
            $longitud
        );
    }
	
	public function buscarTipoUsuario($arrayParametros){
        
       $consulta = "SELECT
						p.codificacion_perfil
						,up.identificador
					FROM
						g_usuario.perfiles p,
						g_usuario.usuarios_perfiles up
					WHERE
						p.id_perfil = up.id_perfil and
						p.codificacion_perfil in ('PFL_USUAR_INT','PFL_REGIST_OPERA','PFL_USUAR_CIV_PR') and
						up.identificador = '" . $arrayParametros['identificador'] . "';";
       
       return $this->modelo->ejecutarSqlNativo($consulta);
    
    }
	
	public function  buscarCorreoElectronicoUsuario($arrayParametros){
        
        $tipoUsuario = $arrayParametros['tipoUsuario'];
        $identificador = $arrayParametros['identificador'];
        
        if($tipoUsuario == 'PFL_USUAR_INT' || $tipoUsuario == 'PFL_USUAR_CIV_PR'){
            
        	$campo = ($tipoUsuario == 'PFL_USUAR_INT')?'mail_institucional':'mail_personal';
        	
            $consulta = "SELECT
							" . $campo . " as correo_usuario
						FROM
							g_uath.ficha_empleado
						WHERE
							identificador = '$identificador'";
            
        }else if($tipoUsuario = 'PFL_REGIST_OPERA'){
            
            $consulta = "SELECT
                            correo as correo_usuario
                         FROM
                            g_operadores.operadores
                         WHERE
                            identificador = '$identificador'";
            
        }      
        
        return $this->modelo->ejecutarSqlNativo($consulta);
        
    }
    
    public function  actualizarCorreoElectronicoUsuario($arrayParametros){
        
        $tipoUsuario = $arrayParametros['tipoUsuario'];
        $identificador = $arrayParametros['identificador'];
        $correoUsuario = $arrayParametros['correoUsuario'];
        
        if($tipoUsuario == 'PFL_USUAR_INT' || $tipoUsuario == 'PFL_USUAR_CIV_PR'){
        	
        	$campo = ($tipoUsuario == 'PFL_USUAR_INT')?'mail_institucional':'mail_personal';
           
            $consulta = "UPDATE g_uath.ficha_empleado SET
							" . $campo . " = '$correoUsuario'
						WHERE
							identificador = '$identificador'";
            
        }else if($tipoUsuario = 'PFL_REGIST_OPERA'){
            
            $consulta = "UPDATE g_operadores.operadores SET
                            correo = '$correoUsuario'
                         WHERE
                            identificador = '$identificador'";
            
        }
        
        return $this->modelo->ejecutarSqlNativo($consulta);
        
    }
	
	/**
     * Método autenticar usuario
     */
    public function buscarUsuarioInterno($arrayParametros)
    {
        $identificador = trim(pg_escape_string($arrayParametros['identificador']));
        $clave = pg_escape_string($arrayParametros['clave']);

        $consulta = "SELECT 
                        u.identificador, u.clave, u.estado, f.nombre ||' '|| f.apellido nombre , fotografia, 'interno'::text as tipo
                    FROM 
                        g_usuario.usuarios u, g_uath.ficha_empleado f
                    WHERE
                        u.identificador = f.identificador
                        and u.identificador = '$identificador'                        
                        ;";
        
        return $this->modelo->ejecutarSqlNativo($consulta);        
        
        
    }

    /**
     * Método autenticar usuario
     */
    public function buscarUsuarioExterno($arrayParametros)
    {
        $identificador = trim(pg_escape_string($arrayParametros['identificador']));
      
        $consulta = "SELECT 
                        identificador
                    FROM 
                        g_uath.ficha_empleado
                    WHERE 
                        identificador = '$identificador';
                    ";

        $usuario = $this->modelo->ejecutarSqlNativo($consulta); 

        if(isset($usuario->current()->identificador)){
            return $this->buscarUsuarioInterno($arrayParametros);
        } else{

        $consulta = "SELECT 
                        u.identificador, u.clave, u.estado, o.nombre_representante ||' ' || o.apellido_representante nombre, ''::text as fotografia, 'externo'::text as tipo
                    FROM 
                        g_usuario.usuarios u, g_operadores.operadores o
                    WHERE
                        u.identificador = o.identificador
                        and u.identificador = '$identificador'                        
                        ;";
        
        return $this->modelo->ejecutarSqlNativo($consulta); 
        }
        
    }


}
