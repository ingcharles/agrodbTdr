<?php
 /**
 * Lógica del negocio de Moscaf03Modelo
 *
 * Este archivo se complementa con el archivo Moscaf03Controlador.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Moscaf03LogicaNegocio
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\FormulariosInspeccion\Modelos\IModelo;
  use Agrodb\Token\Modelos\TokenLogicaNegocio;
  use Agrodb\Core\Excepciones\GuardarExcepcionConDatos;
  use Exception;
 
class Moscaf03LogicaNegocio implements IModelo 
{

	 private $modeloMoscaf03 = null;
	 private $lNegocioToken = null;

	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloMoscaf03 = new Moscaf03Modelo();
	 $this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Moscaf03Modelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloMoscaf03->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id"]);
		return $this->modeloMoscaf03->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloMoscaf03->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Moscaf03Modelo
	*/
	public function buscar($id)
	{
		return $this->modeloMoscaf03->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloMoscaf03->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloMoscaf03->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarMoscaf03()
	{
	$consulta = "SELECT * FROM ".$this->modeloMoscaf03->getEsquema().". moscaf03";
		 return $this->modeloMoscaf03->ejecutarSqlNativo($consulta);
	}


	/**
	 * Guardar muestreo cabecera y detalle
	 */
	public function guardarMuestreo(Array $datos, Array $datosLboratorio) {

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){

			try{
			
				$procesoIngreso = $this->modeloMoscaf03->getAdapter()
					->getDriver()
					->getConnection();
				$procesoIngreso->beginTransaction();
	
				$statement = $this->modeloMoscaf03->getAdapter()
					->getDriver()
					->createStatement();
				
				$contador=0;
				
				foreach($datos as $registro){
	
					$rutaArchivo = 'ruta foto';
					$link = '';
	
					if($registro['imagen'] != ''){
						$rutaArchivo = 'modulos/AplicacionMovilInternos/archivos/fotosMoscaFrutaSV/'.md5(time()).$contador.'.jpg';
						file_put_contents($rutaArchivo, base64_decode($registro['imagen']));
						$rutaArchivo = URL_PROTOCOL . URL_DOMAIN . URL_GUIA .'/mvc/'. $rutaArchivo;
						$link = '<a href="'.$rutaArchivo.'">Foto</a>';
					}else{
						$rutaArchivo = '';
					}
	
					$campos = array (
						"id_tablet" => $registro['id_tablet'],
						"codigo_provincia" => $registro['codigo_provincia'],
						"nombre_provincia" => $registro['nombre_provincia'],
						"codigo_canton" => $registro['codigo_canton'],
						"nombre_canton" => $registro['nombre_canton'],
						"codigo_parroquia" => $registro['codigo_parroquia'],
						"nombre_parroquia" => $registro['nombre_parroquia'],
						"semana" => $registro['semana'],
						"coordenada_x" => $registro['coordenada_x'],
						"coordenada_y" => $registro['coordenada_y'],
						"coordenada_z" => $registro['coordenada_z'],
						"fecha_inspeccion" => $registro['fecha_inspeccion'],
						"usuario_id" => $registro['usuario_id'],
						"usuario" => $registro['usuario'],
						"tablet_id" => $registro['tablet_id'],
						"tablet_version_base" => $registro['tablet_version_base'],
						"sitio" => $registro['sitio'],
						"envio_muestra" => $registro['envio_muestra'],
						"ruta_foto" => $link,
					);		
				
					$sqlInsertar = $this->modeloMoscaf03->guardarSql('moscaf03', $this->modeloMoscaf03->getEsquema());
					$sqlInsertar->columns($this->columnasCabecera());
					$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloMoscaf03->getAdapter(), $statement);
					$statement->execute();
					$id = $this->modeloMoscaf03->adapter->driver->getLastGeneratedValue($this->modeloMoscaf03->getEsquema() . '.moscaf03_id_seq');
	
					$statement2 = $this->modeloMoscaf03->getAdapter()
							->getDriver()
							->createStatement();
	
					foreach($datosLboratorio as $orden){
	
						if ($orden['id_padre'] == $registro['id']){
	
							$campos = array(				
								'id_padre' => $id,
								'id_tablet' => $orden['id_tablet'],
								'aplicacion_producto_quimico' => $orden['aplicacion_producto_quimico'],
								'codigo_muestra' => $orden['codigo_muestra'],
								'descripcion_sintomas' => $orden['descripcion_sintomas'],
								'especie_vegetal' => $orden['especie_vegetal'],
								'sitio_muestreo' => $orden['sitio_muestreo'],
								'numero_frutos_colectados' => $orden['numero_frutos_colectados'],
							);
	
							$sqlInsertar = $this->modeloMoscaf03->guardarSql('moscaf03_detalle_ordenes', 'f_inspeccion');
							$sqlInsertar->columns($this->columnasOrden());
							$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloMoscaf03->getAdapter(), $statement2);
							$statement2->execute();
							
						}
					}
	
					$contador++;
	
				}
				 
				echo json_encode(array('estado' => 'exito', 'mensaje' => 'Registros almancenados en el Sistema GUIA exitosamente'));
				$procesoIngreso->commit();
			} catch (Exception $ex){
				echo json_encode(array('estado' => 'error', 'mensaje' => $ex->getMessage()));
				$procesoIngreso->rollback();
				throw new GuardarExcepcionConDatos($ex);
			}
			
		} else{
			echo json_encode($arrayToken);
		}

	}

	private function columnasCabecera(){
		return array(
			'id_tablet',
			'codigo_provincia',
			'nombre_provincia',
			'codigo_canton',
			'nombre_canton',
			'codigo_parroquia',
			'nombre_parroquia',
			'semana',
			'coordenada_x',
			'coordenada_y',
			'coordenada_z',
			'fecha_inspeccion',
			'usuario_id',
			'usuario',
			'tablet_id',
			'tablet_version_base',
			'sitio',
			'envio_muestra',
			'ruta_foto',
		);
	}

	private function columnasOrden(){
		return array(
			'id_tablet',
			'aplicacion_producto_quimico',
			'codigo_muestra',
			'descripcion_sintomas',
			'especie_vegetal',
			'sitio_muestreo',
			'numero_frutos_colectados',
		);
	}

}
