<?php
 /**
 * Lógica del negocio de TecnicoProvinciaModelo
 *
 * Este archivo se complementa con el archivo TecnicoProvinciaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-03-17
 * @uses    TecnicoProvinciaLogicaNegocio
 * @package ProcesosAdministrativosJuridico
 * @subpackage Modelos
 */
  namespace Agrodb\ProcesosAdministrativosJuridico\Modelos;
  
  use Agrodb\ProcesosAdministrativosJuridico\Modelos\IModelo;
 
class TecnicoProvinciaLogicaNegocio implements IModelo 
{

	 private $modeloTecnicoProvincia = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloTecnicoProvincia = new TecnicoProvinciaModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new TecnicoProvinciaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdTecnicoProvincia() != null && $tablaModelo->getIdTecnicoProvincia() > 0) {
		return $this->modeloTecnicoProvincia->actualizar($datosBd, $tablaModelo->getIdTecnicoProvincia());
		} else {
		unset($datosBd["id_tecnico_provincia"]);
		return $this->modeloTecnicoProvincia->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloTecnicoProvincia->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return TecnicoProvinciaModelo
	*/
	public function buscar($id)
	{
		return $this->modeloTecnicoProvincia->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloTecnicoProvincia->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloTecnicoProvincia->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarTecnicoProvincia($arrayParametros)
	{
	    $busqueda='';
	    if(array_key_exists('estadoP', $arrayParametros)){
	        $busqueda .= " and tp.estado in ('".$arrayParametros['estadoP']."')";
	    }
	    if(array_key_exists('estadoS', $arrayParametros)){
	        $busqueda .= " and dtp.estado in ('".$arrayParametros['estadoS']."')";
	    }
	   $consulta = "SELECT 
                        identificador, l.nombre as provincia, l.id_localizacion, codigo_vue
                    FROM 
                        g_procesos_administrativos_juridico.tecnico_provincia tp inner join 
                        g_procesos_administrativos_juridico.detalle_tecnico_provincia dtp on tp.id_tecnico_provincia = dtp.id_tecnico_provincia inner join 
                        g_catalogos.localizacion l on l.id_localizacion = dtp.id_localizacion and
                        identificador = '".$arrayParametros['identificador']."'
                        ".$busqueda.";";
		 return $this->modeloTecnicoProvincia->ejecutarSqlNativo($consulta);
	}

}
