<?php
 /**
 * Lógica del negocio de DatosContratoModelo
 *
 * Este archivo se complementa con el archivo DatosContratoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    DatosContratoLogicaNegocio
 * @package GUath
 * @subpackage Modelos
 */
  namespace Agrodb\GUath\Modelos;
  
  use Agrodb\GUath\Modelos\IModelo;
  use Agrodb\Token\Modelos\TokenLogicaNegocio;
  use Agrodb\Core\Excepciones\BuscarExcepcion;
  use Exception;
 
class DatosContratoLogicaNegocio implements IModelo 
{

	private $modeloDatosContrato = null;
	private $lNegocioToken = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
		$this->modeloDatosContrato = new DatosContratoModelo();
		$this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DatosContratoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDatosContrato() != null && $tablaModelo->getIdDatosContrato() > 0) {
		return $this->modeloDatosContrato->actualizar($datosBd, $tablaModelo->getIdDatosContrato());
		} else {
		unset($datosBd["id_datos_contrato"]);
		return $this->modeloDatosContrato->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDatosContrato->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DatosContratoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDatosContrato->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDatosContrato->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDatosContrato->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDatosContrato()
	{
	$consulta = "SELECT * FROM ".$this->modeloDatosContrato->getEsquema().". datos_contrato";
		 return $this->modeloDatosContrato->ejecutarSqlNativo($consulta);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarProvinciaContratoUsuario($identificador)
	{
	
		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);
        if ($arrayToken['estado'] == 'exito') {
            // $where = "identificador='$identificador' and estado=a";
			$mensjaeError=null;	

			$consulta = "SELECT 
					identificador, provincia
				FROM 
					g_uath.datos_contrato
				where 
					identificador='$identificador'
					and estado=1";
            try {
				// $res = (object) $this->modeloDatosContrato->buscarLista($where); 
				$res = $this->modeloDatosContrato->ejecutarSqlNativo($consulta);
				$datos = $res->toArray();
				if (count($datos)>0){
					$array['estado'] = 'exito';
					$array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";				
					$array['cuerpo'] =  Array('identificador' => $datos[0]['identificador'], 'provincia' => $datos[0]['provincia']);
					echo json_encode($array);
				} else {
					$array['estado'] = 'error';
					$array['mensaje'] = "El usuario no posee un contrato activo para determinar la provincia donde labora";		
					$array['cuerpo'] = '';					
					$mensjaeError = "El usuario $identificador no posee un contrato activo para determinar la provincia donde labora";
					throw new Exception("El usuario $identificador no posee un contrato activo para determinar la provincia donde labora");
				}
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = $mensjaeError == null ? $ex->getMessage() : $mensjaeError;
				http_response_code(404);
				echo json_encode($array);
				throw new BuscarExcepcion($ex, array('archivo' => 'DatosContratoLogicaNegocio', 'metodo' => 'buscarProvinciaContratoUsuario'));
			}
        } else{
            echo json_encode($arrayToken);
        }
	}

}
