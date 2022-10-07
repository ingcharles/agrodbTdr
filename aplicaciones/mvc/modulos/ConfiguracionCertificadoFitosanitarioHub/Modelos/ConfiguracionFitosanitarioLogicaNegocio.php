<?php
/**
 * Lógica del negocio de ConfiguracionFitosanitarioModelo
 *
 * Este archivo se complementa con el archivo ConfiguracionFitosanitarioControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-07-04
 * @uses ConfiguracionFitosanitarioLogicaNegocio
 * @package WsFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\ConfiguracionCertificadoFitosanitarioHub\Modelos;

use Agrodb\ConfiguracionCertificadoFitosanitarioHub\Modelos\IModelo;

class ConfiguracionFitosanitarioLogicaNegocio implements IModelo{

	private $modeloConfiguracionFitosanitario = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloConfiguracionFitosanitario = new ConfiguracionFitosanitarioModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		
		$validacionProceso = true;
		
		$tablaModelo = new ConfiguracionFitosanitarioModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		
			if ($tablaModelo->getIdConfiguracionFitosanitario() != null && $tablaModelo->getIdConfiguracionFitosanitario() > 0){
				$this->modeloConfiguracionFitosanitario->actualizar($datosBd, $tablaModelo->getIdConfiguracionFitosanitario());
			}else{
				unset($datosBd["id_configuracion_fitosanitario"]);
				
				$arrayParametros = array(
					"tipo_configuracion_fitosanitario" => $tablaModelo->getTipoConfiguracionFitosanitario(),
					"id_localizacion_fitosanitario" =>$tablaModelo->getIdLocalizacionFitosanitario()
				);
				
				$verificacionDatos = $this->buscarLista($arrayParametros);
				
				if(!$verificacionDatos->count()){
					$this->modeloConfiguracionFitosanitario->guardar($datosBd);
				}else{
					$validacionProceso = false;
				}
			}
		
		
		return $validacionProceso;
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloConfiguracionFitosanitario->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ConfiguracionFitosanitarioModelo
	 */
	public function buscar($id){
		return $this->modeloConfiguracionFitosanitario->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloConfiguracionFitosanitario->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloConfiguracionFitosanitario->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarConfiguracionFitosanitario(){
		$consulta = "SELECT * FROM " . $this->modeloConfiguracionFitosanitario->getEsquema() . ". configuracion_fitosanitario";
		return $this->modeloConfiguracionFitosanitario->ejecutarSqlNativo($consulta);
	}
}
