<?php
 /**
 * Lógica del negocio de ServiciosModelo
 *
 * Este archivo se complementa con el archivo ServiciosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-10-10
 * @uses    ServiciosLogicaNegocio
 * @package Financiero
 * @subpackage Modelos
 */
  namespace Agrodb\Financiero\Modelos;
  
  use Agrodb\Financiero\Modelos\IModelo;
 
class ServiciosLogicaNegocio implements IModelo 
{

	 private $modeloServicios = null;

	   
				  
	  
					
	   
								 
	 
											  
	 

	/**
	* Constructor
	* 
	* @retorna void
				  
	 */
	 public function __construct()
	{
	 $this->modeloServicios = new ServiciosModelo();
	}

	/**
	* Guarda el registro actual
	  
	* @param array $datos
										   
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ServiciosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdServicio() != null && $tablaModelo->getIdServicio() > 0) {
		return $this->modeloServicios->actualizar($datosBd, $tablaModelo->getIdServicio());
		} else {
		unset($datosBd["id_servicio"]);
		return $this->modeloServicios->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
												  
	  
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloServicios->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ServiciosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloServicios->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloServicios->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloServicios->buscarLista($where, $order, $count, $offset);
	}

	
/**
     * Ejecuta una consulta(SQL) personalizada .
     * Las direcciones en el sistema GUIA se identifican como Unidad administrativa por el campo id_categoria_servicio =1
     * 1 -> Unidad administrativa 2 -> Tipo de documento 3 -> Items
     *
     * @return array|ResultSet
     */
    public function buscarDirecciones()
    {
        $codigo = Constantes::FILTRO_USADO_PARA;
        
        $where = "id_categoria_servicio=1 AND estado='activo' AND strpos(usado_para, '$codigo')>0 order by codigo";
        return $this->modelo->buscarLista($where);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Los laboratorios en el sistema GUIA se identifican como Unidad administrativa: Análisis de Laboratorio
     * id_servicio_padre = 4
     *
     *
     * @return array|ResultSet
     */
    public function buscarLaboratorios()
    {
        $where = "id_servicio_padre=4 AND estado='activo' order by concepto";
        
        return $this->modelo->buscarLista($where);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
	 * Función cambio de nombre de buscarServicios() a buscarServiciosLaboratorio()
     */
    public function buscarServiciosLaboratorio($idLaboratorio = null)
    {
        $where = "id_servicio_padre=(SELECT id_sistema_guia from g_laboratorios.laboratorios l where l.id_laboratorio=" . $idLaboratorio . ") order by concepto";
        return $this->modelo->buscarLista($where);
    }

	/*																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																															  
	* Ejecuta una consulta(SQL) personalizada .
	*	  
	* @return array|ResultSet
	*/
	public function buscarServicios()
	{
	$consulta = "SELECT * FROM ".$this->modeloServicios->getEsquema().". servicios";
		 return $this->modeloServicios->ejecutarSqlNativo($consulta);
												  
	}																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																															  
}
