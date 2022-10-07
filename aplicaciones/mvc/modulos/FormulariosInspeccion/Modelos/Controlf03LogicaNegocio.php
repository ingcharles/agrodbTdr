<?php
 /**
 * Lógica del negocio de Controlf03Modelo
 *
 * Este archivo se complementa con el archivo Controlf03Controlador.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Controlf03LogicaNegocio
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\FormulariosInspeccion\Modelos\IModelo;
  use Agrodb\Token\Modelos\TokenLogicaNegocio;
  use Agrodb\Core\Excepciones\GuardarExcepcion;
  use Exception;
use Producto;

class Controlf03LogicaNegocio implements IModelo 
{

	 private $modeloControlf03 = null;
	 private $lNegocioToken = null;

	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloControlf03 = new Controlf03Modelo();
	 $this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Controlf03Modelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloControlf03->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id"]);
		return $this->modeloControlf03->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloControlf03->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Controlf03Modelo
	*/
	public function buscar($id)
	{
		return $this->modeloControlf03->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloControlf03->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloControlf03->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarControlf03()
	{
	$consulta = "SELECT * FROM ".$this->modeloControlf03->getEsquema().". controlf03";
		 return $this->modeloControlf03->ejecutarSqlNativo($consulta);
	}

	/**
	 * Guardar muestreo cabecera y detalle
	 */
	public function guardarEmbalaje(Array $datos, Array $datosLboratorio) {

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){

			try{
			
				$procesoIngreso = $this->modeloControlf03->getAdapter()
					->getDriver()
					->getConnection();
				$procesoIngreso->beginTransaction();
				
				foreach($datos as $registro){

					$statement = $this->modeloControlf03->getAdapter()
					->getDriver()
					->createStatement();

					$campos = array (
						'id_punto_control' => $registro['id_punto_control'],
						'punto_control' => $registro['punto_control'],
						'area_inspeccion' => $registro['area_inspeccion'],
						'identidad_embalaje' => $registro['identidad_embalaje'],
						'id_pais_origen' => $registro['id_pais_origen'],
						'pais_origen' => $registro['pais_origen'],
						'numero_embalajes' => $registro['numero_embalajes'],
						'numero_unidades' => $registro['numero_unidades'],
						'marca_autorizada' => $registro['marca_autorizada'],
						'marca_autorizada_descripcion' => $registro['marca_autorizada_descripcion'],
						'marca_legible' => $registro['marca_legible'],
						'marca_legible_descripcion' => $registro['marca_legible_descripcion'],
						'ausencia_dano_insectos' => $registro['ausencia_dano_insectos'],
						'ausencia_dano_insectos_descripcion' => $registro['ausencia_dano_insectos_descripcion'],
						'ausencia_insectos_vivos' => $registro['ausencia_insectos_vivos'],
						'ausencia_insectos_vivos_descripcion' => $registro['ausencia_insectos_vivos_descripcion'],
						'ausencia_corteza' => $registro['ausencia_corteza'],
						'ausencia_corteza_descripcion' => $registro['ausencia_corteza_descripcion'],
						'razon_social' => $registro['razon_social'],
						'manifesto' => $registro['manifesto'],
						'producto' => $registro['producto'],
						'envio_muestra' => $registro['envio_muestra'],
						'observaciones' => $registro['observaciones'],
						'dicatamen_final' => $registro['dicatamen_final'],
						'usuario' => $registro['usuario'],
						'usuario_id' => $registro['usuario_id'],
						'fecha_inspeccion' => $registro['fecha_creacion'],
						'id_tablet' => $registro['id_tablet'],
						'tablet_id' => $registro['tablet_id'],
						'tablet_version_base' => $registro['tablet_version_base'],
					);

					if($registro['marca_autorizada_descripcion'] == null ) unset($campos["marca_autorizada_descripcion"]);
					if($registro['marca_legible_descripcion'] == null ) unset($campos["marca_legible_descripcion"]);
					if($registro['ausencia_dano_insectos_descripcion'] == null ) unset($campos["ausencia_dano_insectos_descripcion"]);
					if($registro['ausencia_insectos_vivos_descripcion'] == null ) unset($campos["ausencia_insectos_vivos_descripcion"]);
					if($registro['ausencia_corteza_descripcion'] == null ) unset($campos["ausencia_corteza_descripcion"]);
					if($registro['razon_social'] == null ) unset($campos["razon_social"]);
					if($registro['manifesto'] == null ) unset($campos["manifesto"]);
					if($registro['producto'] == null ) unset($campos["producto"]);
					if($registro['observaciones'] == null ) unset($campos["observaciones"]);

					$arrayColumnasCabecera = array_keys($campos);
				
					$sqlInsertar = $this->modeloControlf03->guardarSql('controlf03', $this->modeloControlf03->getEsquema());
					$sqlInsertar->columns($arrayColumnasCabecera);
					$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloControlf03->getAdapter(), $statement);
					$statement->execute();
					$id = $this->modeloControlf03->adapter->driver->getLastGeneratedValue($this->modeloControlf03->getEsquema() . '.Controlf03_id_seq');
	
					foreach($datosLboratorio as $orden){

						$statement = $this->modeloControlf03->getAdapter()
						->getDriver()
						->createStatement();
	
						if ($orden['id_padre'] == $registro['id']){
	
							$campos = array(				
								'id_padre' => $id,
								'id_tablet' => $orden['id_tablet'],
								'actividad_origen' => $orden['actividad_origen'],
								'analisis' => $orden['analisis'],
								'codigo_muestra' => $orden['codigo_muestra'],
								'conservacion' => $orden['conservacion'],
								'tipo_muestra' => $orden['tipo_muestra'],
								'descripcion_sintomas' => $orden['descripcion_sintomas'],
								'fase_fenologica' => $orden['fase_fenologica'],
								'nombre_producto' => $orden['nombre_producto'],
								'peso_muestra' => $orden['peso_muestra'],
								'prediagnostico' => $orden['prediagnostico'],
								'tipo_cliente' => $orden['tipo_cliente'],
							);

							$arrayColumnasCabecera = array_keys($campos);
	
							$sqlInsertar = $this->modeloControlf03->guardarSql('controlf03_detalle_ordenes', 'f_inspeccion');
							$sqlInsertar->columns($arrayColumnasCabecera);
							$sqlInsertar->values($campos, $sqlInsertar::VALUES_MERGE);
							$sqlInsertar->prepareStatement($this->modeloControlf03->getAdapter(), $statement);
							$statement->execute();
							
						}
					}
	
				}
				 
				echo json_encode(array('estado' => 'exito', 'mensaje' => 'Registros almancenados en el Sistema GUIA exitosamente'));
				$procesoIngreso->commit();
			} catch (Exception $ex){
				echo json_encode(array('estado' => 'error', 'mensaje' => $ex->getMessage()));
				$procesoIngreso->rollback();
				throw new GuardarExcepcion($ex, array('origen' => 'Agro servicios', 'ws'=>'RestWsEmbalajeControlador', 'archivo' => 'Controlf03LogicaNegocio', 'metodo' => 'guardarEmbalaje', 'datos' => $datos));
			}
			
		} else{
			echo json_encode($arrayToken);
		}

	}

}
