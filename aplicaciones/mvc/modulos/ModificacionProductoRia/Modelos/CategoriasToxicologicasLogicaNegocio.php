<?php
 /**
 * Lógica del negocio de CategoriasToxicologicasModelo
 *
 * Este archivo se complementa con el archivo CategoriasToxicologicasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    CategoriasToxicologicasLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\ModificacionProductoRia\Modelos\IModelo;
  use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\Catalogos\Modelos\CategoriaToxicologicaLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosInocuidadLogicaNegocio;
 
class CategoriasToxicologicasLogicaNegocio implements IModelo 
{

	 private $modeloCategoriasToxicologicas = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCategoriasToxicologicas = new CategoriasToxicologicasModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
	    try{
    		$tablaModelo = new CategoriasToxicologicasModelo($datos);
    		
    		$procesoIngreso = $this->modeloCategoriasToxicologicas->getAdapter()
    		->getDriver()
    		->getConnection();
    		$procesoIngreso->beginTransaction();
    		
    		$datosBd = $tablaModelo->getPrepararDatos();
    		if ($tablaModelo->getIdCategoriaToxicologica() != null && $tablaModelo->getIdCategoriaToxicologica() > 0) {
                $idCategoriaToxicologica = $this->modeloCategoriasToxicologicas->actualizar($datosBd, $tablaModelo->getIdCategoriaToxicologica());
    		} else {
    		unset($datosBd["id_categoria_toxicologica"]);
    		  $idCategoriaToxicologica = $this->modeloCategoriasToxicologicas->guardar($datosBd);
    	   }
	   
    	   $procesoIngreso->commit();
    	   return $idCategoriaToxicologica;
	    } catch (GuardarExcepcion $ex) {
	        $procesoIngreso->rollback();
	        throw new \Exception($ex->getMessage());
	    }
	   
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{	    

        $this->modeloCategoriasToxicologicas->borrar($id);
	    
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return CategoriasToxicologicasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloCategoriasToxicologicas->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCategoriasToxicologicas->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCategoriasToxicologicas->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCategoriasToxicologicas()
	{
	$consulta = "SELECT * FROM ".$this->modeloCategoriasToxicologicas->getEsquema().". categorias_toxicologicas";
		 return $this->modeloCategoriasToxicologicas->ejecutarSqlNativo($consulta);
	}

}
