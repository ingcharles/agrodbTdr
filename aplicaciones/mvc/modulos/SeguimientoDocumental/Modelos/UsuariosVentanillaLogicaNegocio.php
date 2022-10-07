<?php
/**
 * Lógica del negocio de UsuariosVentanillaModelo
 *
 * Este archivo se complementa con el archivo UsuariosVentanillaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-01-15
 * @uses    UsuariosVentanillaLogicaNegocio
 * @package SeguimientoDocumental
 * @subpackage Modelos
 */
namespace Agrodb\SeguimientoDocumental\Modelos;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\SeguimientoDocumental\Modelos\IModelo;

class UsuariosVentanillaLogicaNegocio implements IModelo
{

    private $modeloUsuariosVentanilla = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloUsuariosVentanilla = new UsuariosVentanillaModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
    	$ejecutarAsignacion = false;
    	
    	$lNegocioGestionAplicacion = new \Agrodb\GestionAplicacionPerfiles\Modelos\AplicacionesLogicaNegocio();
    	$lNegocioGestionPerfil = new \Agrodb\GestionAplicacionPerfiles\Modelos\PerfilesLogicaNegocio();
    	$lNegocioUsuariosPerfiles = new \Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio();
    	
    	$tablaModelo = new UsuariosVentanillaModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        
        $gestionAplicacion = $lNegocioGestionAplicacion->buscarLista("identificador = '" . $datos['identificador'] . "' and codificacion_aplicacion = '" . Constantes::SEGUIMIENTO_DOCUMENTAL . "';");
        
        //Asignar Aplicación
        if($gestionAplicacion->count() == 0){
        	$arrayParametros = array('identificador' => $datos['identificador'], 'codificacion_aplicacion' => Constantes::SEGUIMIENTO_DOCUMENTAL);
        	$lNegocioGestionAplicacion->guardar($arrayParametros);
        }
        
                
        if ($tablaModelo->getIdUsuarioVentanilla() != null && $tablaModelo->getIdUsuarioVentanilla() > 0) {
        	if($tablaModelo->getIdPerfil() != $datos['id_perfil_antiguo']){
        		$lNegocioUsuariosPerfiles->borrarPorIdentificadorPerfil($datos['identificador'], $datos['id_perfil_antiguo']);
        		$ejecutarAsignacion = true;
        	}
        	
        	if($datos['estado_usuarios_ventanilla'] == 'Activo'){
	        	//Buscar registros existentes y cambiar de estado a inactivo
	        	$otrasVentanillas = $this->modeloUsuariosVentanilla->buscarLista("identificador = '" . $datos['identificador'] . "';");
	        	if($otrasVentanillas->count() != 0){
	        		//recorrer para todos los registros
	        		foreach ($otrasVentanillas as $fila) {
	        			$this->cambiarEstadoUsuarioVentanilla($fila['id_usuario_ventanilla'], $fila['identificador'], 'Inactivo') ;
	        			
	        			if($fila['id_perfil'] != $datos['id_perfil']){
	        				$lNegocioUsuariosPerfiles->borrarPorIdentificadorPerfil($fila['identificador'], $fila['id_perfil']);
	        			}
	        		}
	        	}
	        	
	        	$ejecutarAsignacion = true;
        	}else{
        		$lNegocioUsuariosPerfiles->borrarPorIdentificadorPerfil($datos['identificador'], $datos['id_perfil']);
        	}
        	
        	$this->modeloUsuariosVentanilla->actualizar($datosBd, $tablaModelo->getIdUsuarioVentanilla());
        	Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        } else {
        	unset($datosBd["id_usuario_ventanilla"]);
        	
        	$existeRegVentanilla = $this->modeloUsuariosVentanilla->buscarLista("identificador = '" . $datos['identificador'] . "' and id_ventanilla = ". $datos['id_ventanilla'] .";");
        	
        	if($existeRegVentanilla->count() == 0){
        		//Buscar registros existentes y cambiar de estado a inactivo
        		$otrasVentanillas = $this->modeloUsuariosVentanilla->buscarLista("identificador = '" . $datos['identificador'] . "';");
        		if($otrasVentanillas->count() != 0){
        			//recorrer para todos los registros
        			foreach ($otrasVentanillas as $fila) {
        				$this->cambiarEstadoUsuarioVentanilla($fila['id_usuario_ventanilla'], $fila['identificador'], 'Inactivo') ;
        				
        				if($fila['id_perfil'] != $datos['id_perfil']){
        					$lNegocioUsuariosPerfiles->borrarPorIdentificadorPerfil($fila['identificador'], $fila['id_perfil']);
        				}
        			}
        		}
        		
        		if($datos['estado_usuarios_ventanilla'] == 'Activo'){
        			$ejecutarAsignacion = true;
        		}
        		
        		$this->modeloUsuariosVentanilla->guardar($datosBd);
        		
        		Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        		
        	}else{	
        		Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        		exit();
        	}        	
        }
        
        
        if($ejecutarAsignacion == true){
	        $gestionPerfil = $lNegocioGestionPerfil->buscarLista("identificador = '" . $datos['identificador'] . "' and codificacion_perfil = '" . $datos['codificacion_perfil'] . "';");
	        if($gestionPerfil->count() == 0){
	        	$arrayParametros = array('identificador' => $datos['identificador'], 'codificacion_perfil' => $datos['codificacion_perfil']);
	        	$lNegocioGestionPerfil->guardar($arrayParametros);
	        }else{
	        	$arrayParametros = array('id_perfil' => $gestionPerfil->current()->id_perfil ,'estado' => 'false', 'fecha_registro'=>'now()');
	        	$lNegocioGestionPerfil->guardar($arrayParametros);
	        }
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
        $this->modeloUsuariosVentanilla->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return UsuariosVentanillaModelo
     */
    public function buscar($id)
    {
        return $this->modeloUsuariosVentanilla->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloUsuariosVentanilla->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloUsuariosVentanilla->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarUsuariosVentanilla()
    {
        $consulta = "SELECT * FROM " . $this->modeloUsuariosVentanilla->getEsquema() . ". usuarios_ventanilla";
        return $this->modeloUsuariosVentanilla->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar Usuarios por Ventanillas con información completa.
     *
     * @return array|ResultSet
     */
    public function buscarUsuariosVentanillaDatos($arrayParametros)
    {
        $consulta = "SELECT
                        uv.id_usuario_ventanilla,
                    	uv.identificador, fe.nombre, fe.apellido,
                    	uv.id_ventanilla, v.nombre as ventanilla,
                    	uv.id_perfil, p.nombre as perfil,
                    	uv.estado_usuarios_ventanilla
                    FROM
                    	g_seguimiento_documental.usuarios_ventanilla uv 
                    	INNER JOIN g_seguimiento_documental.ventanillas v ON uv.id_ventanilla = v.id_ventanilla
                    	INNER JOIN g_uath.ficha_empleado fe ON uv.identificador = fe.identificador
                    	INNER JOIN g_usuario.perfiles p ON uv.id_perfil = p.id_perfil
                    WHERE
                        uv.id_ventanilla =". $arrayParametros['id_ventanilla'].";";
        
        $ventanillas = $this->modeloUsuariosVentanilla->ejecutarSqlNativo($consulta);
        
        return $ventanillas;
    }
    
    public function buscarVentanillas()
    {
        $ventanillas = new VentanillasLogicaNegocio();
        
        return $ventanillas->buscarVentanillasDatos();
    }
    
    public function buscarVentanillasEstado($estado)
    {
    	$ventanillas = new VentanillasLogicaNegocio();
    	
    	return $ventanillas->buscarVentanillasDatosEstado($estado);
    }
   
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar Información de Usuarios por Ventanilla por Usuario con información completa.
     *
     * @return array|ResultSet
     */
    public function buscarDatosUsuarioTecnico($identificador)
    {
        $consulta = "SELECT
                        uv.id_usuario_ventanilla,
                    	uv.identificador, fe.nombre, fe.apellido,
                    	uv.id_ventanilla, v.nombre as ventanilla,
                        v.id_unidad_destino, v.codigo_ventanilla,
                    	uv.id_perfil, p.nombre as perfil,
                    	uv.estado_usuarios_ventanilla
                    FROM
                    	g_seguimiento_documental.usuarios_ventanilla uv
                    	INNER JOIN g_seguimiento_documental.ventanillas v ON uv.id_ventanilla = v.id_ventanilla
                    	INNER JOIN g_uath.ficha_empleado fe ON uv.identificador = fe.identificador
                    	INNER JOIN g_usuario.perfiles p ON uv.id_perfil = p.id_perfil
                    WHERE
                        uv.identificador = '". $identificador ."' and
						uv.estado_usuarios_ventanilla = 'Activo';";
        
        $ventanillas = $this->modeloUsuariosVentanilla->ejecutarSqlNativo($consulta);
        $fila = $ventanillas->current();
        
        $usuarioVentanilla = array( 'idVentanilla' => $fila->id_ventanilla,
				'ventanillaUsuario' => $fila->ventanilla,
				'idUnidadDestino' => $fila->id_unidad_destino,
				'codigoVentanilla' => $fila->codigo_ventanilla,
				'nombreUsuarioVentanilla' => $fila->nombre. ' ' . $fila->apellido,
				'idPerfilUsuario' => $fila->id_perfil,
				'perfilUsuario' => $fila->perfil
        	);
        
        return $usuarioVentanilla;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar Información de Usuarios por Ventanilla por Usuario con información completa.
     *
     * @return array|ResultSet
     */
    public function buscarDatosUsuarioRegistro($identificador, $idVentanilla)
    {
    	$consulta = "SELECT
                        uv.id_usuario_ventanilla,
                    	uv.identificador, fe.nombre, fe.apellido,
                    	uv.id_ventanilla, v.nombre as ventanilla,
                        v.id_unidad_destino, v.codigo_ventanilla,
                    	uv.id_perfil, p.nombre as perfil,
                    	uv.estado_usuarios_ventanilla
                    FROM
                    	g_seguimiento_documental.usuarios_ventanilla uv
                    	INNER JOIN g_seguimiento_documental.ventanillas v ON uv.id_ventanilla = v.id_ventanilla
                    	INNER JOIN g_uath.ficha_empleado fe ON uv.identificador = fe.identificador
                    	INNER JOIN g_usuario.perfiles p ON uv.id_perfil = p.id_perfil
                    WHERE
                        uv.identificador = '". $identificador ."' and
						uv.id_ventanilla = ". $idVentanilla .";";
    	
    	$ventanillas = $this->modeloUsuariosVentanilla->ejecutarSqlNativo($consulta);
    	$fila = $ventanillas->current();
    	
    	$usuarioVentanilla = array( 'idVentanilla' => $fila->id_ventanilla,
    		'ventanillaUsuario' => $fila->ventanilla,
    		'idUnidadDestino' => $fila->id_unidad_destino,
    		'codigoVentanilla' => $fila->codigo_ventanilla,
    		'nombreUsuarioVentanilla' => $fila->nombre. ' ' . $fila->apellido,
    		'idPerfilUsuario' => $fila->id_perfil,
    		'perfilUsuario' => $fila->perfil
    	);
    	
    	return $usuarioVentanilla;
    }
    
    public function cambiarEstadoUsuarioVentanilla($idUsuarioVentanilla, $identificador, $estado)
    {
    	$consulta = "   UPDATE
                            g_seguimiento_documental.usuarios_ventanilla
                        SET
                            estado_usuarios_ventanilla = '$estado'
                        WHERE
                        	id_usuario_ventanilla = $idUsuarioVentanilla and
                        	identificador = '" . $identificador . "';";
    	
    	return $this->modeloUsuariosVentanilla->ejecutarSqlNativo($consulta);
    }
}
