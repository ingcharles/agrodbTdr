<?php
 /**
 * Lógica del negocio de PerfilesModelo
 *
 * Este archivo se complementa con el archivo PerfilesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-01-15
 * @uses    PerfilesLogicaNegocio
 * @package Usuarios
 * @subpackage Modelos
 */
  namespace Agrodb\Usuarios\Modelos;
  
  use Agrodb\Usuarios\Modelos\IModelo;
 
class PerfilesLogicaNegocio implements IModelo 
{

	 private $modeloPerfiles = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloPerfiles = new PerfilesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new PerfilesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdPerfil() != null && $tablaModelo->getIdPerfil() > 0) {
		return $this->modeloPerfiles->actualizar($datosBd, $tablaModelo->getIdPerfil());
		} else {
		unset($datosBd["id_perfil"]);
		return $this->modeloPerfiles->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloPerfiles->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return PerfilesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloPerfiles->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloPerfiles->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloPerfiles->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarPerfiles()
	{
	$consulta = "SELECT * FROM ".$this->modeloPerfiles->getEsquema().". perfiles";
		 return $this->modeloPerfiles->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta de los perfiles por aplicación.
	 *
	 * @return array|ResultSet
	 */
	public function buscarPerfilesAplicacion($idAplicacion)
	{
	    $where = "id_aplicacion in ($idAplicacion) and
                  estado=1";
	    return $this->modeloPerfiles->buscarLista($where, 'nombre');
	}

//**************************modificado el 02-03-2021********************************
	/**
	 * VERIFICAR TIPO DE PERFIL DEL OPERADOR
	 */
	public function verificarPerfil($identificador,$aplicacion, $perfil=null){
	    $busqueda='';
	    if($perfil!= ''){
	        $busqueda = "and p.codificacion_perfil = '".$perfil."'";
	    }
	    $sql = "SELECT
					p.nombre, p.codificacion_perfil
			  FROM
					g_usuario.usuarios_perfiles up
					INNER JOIN g_usuario.perfiles p ON up.id_perfil = p.id_perfil
					INNER JOIN g_programas.aplicaciones ap ON ap.id_aplicacion = p.id_aplicacion
			  WHERE
					identificador in ('" . $identificador . "') AND
					ap.codificacion_aplicacion='".$aplicacion."' ".$busqueda." order by 1;";
	    return $this->modeloPerfiles->ejecutarSqlNativo($sql);
	}
}
