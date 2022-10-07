<?php
 /**
 * Lógica del negocio de TiposEnvaseModelo
 *
 * Este archivo se complementa con el archivo TiposEnvaseControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    TiposEnvaseLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
  use Agrodb\Token\Modelos\TokenLogicaNegocio;
  use Agrodb\Core\Excepciones\BuscarExcepcion;
  use \Exception;
 
class TiposEnvaseLogicaNegocio implements IModelo 
{

	 private $modeloTiposEnvase = null;
	 private $lNegocioToken = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloTiposEnvase = new TiposEnvaseModelo();
	 $this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new TiposEnvaseModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdEnvase() != null && $tablaModelo->getIdEnvase() > 0) {
		return $this->modeloTiposEnvase->actualizar($datosBd, $tablaModelo->getIdEnvase());
		} else {
		unset($datosBd["id_envase"]);
		return $this->modeloTiposEnvase->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloTiposEnvase->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return TiposEnvaseModelo
	*/
	public function buscar($id)
	{
		return $this->modeloTiposEnvase->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloTiposEnvase->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloTiposEnvase->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarTiposEnvase()
	{
	$consulta = "SELECT * FROM ".$this->modeloTiposEnvase->getEsquema().". tipos_envase";
		 return $this->modeloTiposEnvase->ejecutarSqlNativo($consulta);
	}

	/**
     * Busca el catálogo de envases por área
     *
     * @return ResultSet 
     */
    public function obtenerCatalogoEnvases($idArea){
        $arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);
        if ($arrayToken['estado'] == 'exito') {
            $where = "estado='activo' and id_area='$idArea'";
            try {
				$res = (object) $this->modeloTiposEnvase->buscarLista($where, 'nombre_envase'); 
				$array['estado'] = 'exito';
				$array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";				
				$array['cuerpo'] =  $res->toArray();
				echo json_encode($array);		
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = 'Error al obtener datos: ' . $ex;
				http_response_code(400);
				echo json_encode($array);
				throw new BuscarExcepcion($ex, array('archivo' => 'TiposEnvaseLogicaNegocio', 'metodo' => 'obtenerCatalogoEnvases'));
			}
        } else{
            echo json_encode($arrayToken);
        }
    }

}
