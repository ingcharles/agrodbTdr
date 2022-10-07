<?php
 /**
 * Lógica del negocio de Moscaf02Modelo
 *
 * Este archivo se complementa con el archivo Moscaf02Controlador.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Moscaf02LogicaNegocio
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;

  use Agrodb\Core\Excepciones\GuardarExcepcion;
  use Agrodb\FormulariosInspeccion\Modelos\IModelo;
  use Agrodb\Token\Modelos\TokenLogicaNegocio;
  use Exception;
 
class Moscaf02LogicaNegocio implements IModelo 
{

	 private $modeloMoscaf02 = null;
	 private $lNegocioToken = null;

	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloMoscaf02 = new Moscaf02Modelo();
	 $this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Moscaf02Modelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloMoscaf02->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id"]);
		return $this->modeloMoscaf02->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloMoscaf02->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Moscaf02Modelo
	*/
	public function buscar($id)
	{
		return $this->modeloMoscaf02->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloMoscaf02->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloMoscaf02->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarMoscaf02()
	{
	$consulta = "SELECT * FROM ".$this->modeloMoscaf02->getEsquema().". moscaf02";
		 return $this->modeloMoscaf02->ejecutarSqlNativo($consulta);
	}

	/**
	 * Guardar caracterizacion
	 */
	public function guardarCaracterizacion(Array $datos) {

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);
			
		if($arrayToken['estado'] == 'exito'){

			try{			
				
				$procesoIngreso = $this->modeloMoscaf02->getAdapter()
					->getDriver()
					->getConnection();
				$procesoIngreso->beginTransaction();
				
				$contador=0;
				
				foreach($datos as $registro){	
	
					$rutaArchivo = 'ruta foto';
					$link = '';
	
					if($registro['imagen'] != '' || $registro['imagen'] != null){
						$rutaArchivo = 'modulos/AplicacionMovilInternos/archivos/fotosMoscaCaracterizacionSv/'.md5(time()).$contador.'.jpg';
						file_put_contents($rutaArchivo, base64_decode($registro['imagen']));
						$rutaArchivo = URL_PROTOCOL . URL_DOMAIN . URL_GUIA .'/mvc/'. $rutaArchivo;
						$link = '<a href="'.$rutaArchivo.'">Foto</a>';
					}else{
						$rutaArchivo = '';
					}

					$campos = array (
						"id_tablet" => $registro['id_tablet'],
						"nombre_asociacion_productor" => $registro['nombre_asociacion_productor'],
						"identificador" => $registro['identificador'],
						"telefono" => $registro['telefono'],
						"codigo_provincia" => $registro['codigo_provincia'],
						"provincia" => $registro['provincia'],
						"codigo_canton" => $registro['codigo_canton'],
						"canton" => $registro['canton'],
						"codigo_parroquia" => $registro['codigo_parroquia'],
						"parroquia" => $registro['parroquia'],
						"sitio" => $registro['sitio'],
						"especie" => $registro['especie'],
						"variedad" => $registro['variedad'],
						"area_produccion_estimada" => $registro['area_produccion_estimada'],
						"coordenada_x" => $registro['coordenada_x'],
						"coordenada_y" => $registro['coordenada_y'],
						"coordenada_z" => $registro['coordenada_z'],
						"fecha_inspeccion" => $registro['fecha_inspeccion'],
						"usuario_id" => $registro['usuario_id'],
						"usuario" => $registro['usuario'],
						"tablet_id" => $registro['tablet_id'],
						"tablet_version_bases" => $registro['tablet_version_base'],
						"imagen" => $link,				
					);

					$tieneNulos = true;

					if(isset($registro['observaciones']) || $registro['observaciones'] != null){
						$tieneNulos = false;
						$campos += ['observaciones' => $registro['observaciones']];
					
					}

					$statement = $this->modeloMoscaf02->getAdapter()->getDriver()->createStatement();
					$sqlInsertar = $this->modeloMoscaf02->guardarSql('moscaf02', $this->modeloMoscaf02->getEsquema());
					$sqlInsertar->columns($this->columnasCabecera($tieneNulos));
					$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloMoscaf02->getAdapter(), $statement);
					$statement->execute();
	
					$contador++;
	
				}
				 
				echo json_encode(array('estado' => 'exito', 'mensaje' => 'Registros almancenados en el Sistema GUIA exitosamente'));
				$procesoIngreso->commit();
			} catch (Exception $ex){
				echo json_encode(array('estado' => 'error', 'mensaje' => $ex->getMessage()));
				$procesoIngreso->rollback();
				throw new GuardarExcepcion($ex, array('origen' => 'Agro servicios', 'ws'=>'RestWsMoscaCaracterizacionControlador', 'archivo' => 'Moscaf02LogicaNegocio', 'metodo' => 'guardarCaracterizacion', 'datos' => $datos));
			}
		} else{
			echo json_encode($arrayToken);
		}

	}
	
	private function columnasCabecera($tieneNulos){
		$campos = array(
			'id_tablet',
			'nombre_asociacion_productor',
			'identificador',
			'telefono',
			'codigo_provincia',
			'provincia',
			'codigo_canton',
			'canton',
			'codigo_parroquia',
			'parroquia',
			'sitio',
			'especie',
			'variedad',
			'area_produccion_estimada',
			'coordenada_x',
			'coordenada_y',
			'coordenada_z',
			'fecha_inspeccion',
			'usuario_id',
			'usuario',
			'tablet_id',
			'tablet_version_base',
			'imagen',
		);

		if (!$tieneNulos){
			$campos[] = 'observaciones';
		} 
	
		return $campos;
	}

}
