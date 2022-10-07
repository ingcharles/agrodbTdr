<?php
 /**
 * Lógica del negocio de DatosIngresoModelo
 *
 * Este archivo se complementa con el archivo DatosIngresoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-08-14
 * @uses    DatosIngresoLogicaNegocio
 * @package FormularioBoleta
 * @subpackage Modelos
 */
  namespace Agrodb\FormularioBoleta\Modelos;
  
  use Agrodb\FormularioBoleta\Modelos\IModelo;
 
class DatosIngresoLogicaNegocio implements IModelo 
{

	 private $modeloDatosIngreso = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDatosIngreso = new DatosIngresoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DatosIngresoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDatosIngreso() != null && $tablaModelo->getIdDatosIngreso() > 0) {
		return $this->modeloDatosIngreso->actualizar($datosBd, $tablaModelo->getIdDatosIngreso());
		} else {
		unset($datosBd["id_datos_ingreso"]);
		return $this->modeloDatosIngreso->guardar($datosBd);
	}
	}
	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardarFormulario(Array $datos)
	{
	    try{
	        $this->modeloDatosIngreso = new DatosIngresoModelo();
	        $proceso = $this->modeloDatosIngreso->getAdapter()
	        ->getDriver()
	        ->getConnection();
	        if (! $proceso->beginTransaction()){
	            throw new \Exception('No se pudo iniciar la transacción: actualizar historia clinica ');
	        }
	        $codigo =$this->generarCodigo();
	        $datos['genero']=$datos['genero'][0];
	        $datos['medio_transporte']=$datos['medio_transporte'][0];
	        $datos['codigo_boleta']=$codigo;
	        $tablaModelo = new DatosIngresoModelo($datos);
	        $datosBd = $tablaModelo->getPrepararDatos();
	        if ($tablaModelo->getIdDatosIngreso() != null && $tablaModelo->getIdDatosIngreso() > 0) {
	            $this->modeloDatosIngreso->actualizar($datosBd, $tablaModelo->getIdDatosIngreso());
	        } else {
	            unset($datosBd["id_datos_ingreso"]);
	            $idDatosPrueba =  $this->modeloDatosIngreso->guardar($datosBd);
	        }
	        
 	//********************************************************************************************
 	       $lnegocioRespuestasIngreso = new RespuestasIngresoLogicaNegocio();
	       foreach ($datos['respuestas'] as $value) {
	            $array = explode('-', $value);
                $arrayElemento = array(
                    'id_preguntas_ingreso' => $array[0],
                    'id_datos_ingreso' => $idDatosPrueba,
                    'respuesta' =>  $array[1],
                    'num_hombres' => isset($array[2]) ? ($array[2]!='' ? intval($array[2]):null):null,
                    'num_mujeres' => isset($array[3]) ? ($array[3]!='' ? intval($array[3]):null):null
                );
               
                unset($array);
                $statement = $this->modeloDatosIngreso->getAdapter()
                ->getDriver()
                ->createStatement();
                $sqlInsertar = $this->modeloDatosIngreso->guardarSql('respuestas_ingreso', $this->modeloDatosIngreso->getEsquema());
                $sqlInsertar->columns($lnegocioRespuestasIngreso->columnas());
                $sqlInsertar->values($arrayElemento, $sqlInsertar::VALUES_MERGE);
                $sqlInsertar->prepareStatement($this->modeloDatosIngreso->getAdapter(), $statement);
                $statement->execute();
            }
            
	        $proceso->commit();
	        return $codigo;
	    }catch (\Exception $ex){
	        $proceso->rollback();
	        throw new \Exception($ex->getMessage());
	        return false;
	    }

	}
	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDatosIngreso->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DatosIngresoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDatosIngreso->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDatosIngreso->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDatosIngreso->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDatosIngreso()
	{
	$consulta = "SELECT * FROM ".$this->modeloDatosIngreso->getEsquema().". datos_ingreso";
		 return $this->modeloDatosIngreso->ejecutarSqlNativo($consulta);
	}
	public function generarCodigo(){
	    $secuencial = $this->obtenerSecuencial();
	    $secuencial= str_pad($secuencial->current()->numero, 6, "0", STR_PAD_LEFT);
	    return date("d-m-Y").'-'.$secuencial;
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el numero de registros
	 *
	 * @return array
	 */
	public function obtenerSecuencial(){
	    $consulta = "SELECT
						COALESCE(count(*)::numeric, 0) AS numero
					FROM
						g_formulario_boleta.datos_ingreso;";
	    $resultado = $this->modeloDatosIngreso->ejecutarSqlNativo($consulta);
	    return $resultado;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener las respuestas
	 *
	 * @return array
	 */
	public function obtenerRespuestas($idDatosIngreso,$idPreguntasIngreso){
	  $consulta = "SELECT
					    respuesta, num_hombres, num_mujeres
					FROM
                        g_formulario_boleta.respuestas_ingreso ri inner join 
						g_formulario_boleta.datos_ingreso di on ri.id_datos_ingreso = di.id_datos_ingreso
                        inner join g_formulario_boleta.preguntas_ingreso pi on pi.id_preguntas_ingreso = ri.id_preguntas_ingreso
                    WHERE di.id_datos_ingreso = ".$idDatosIngreso." and pi.id_preguntas_ingreso = ".$idPreguntasIngreso.";";
	    $resultado = $this->modeloDatosIngreso->ejecutarSqlNativo($consulta);
	    return $resultado;
	}
	
	public function obtenerPaises(){
	    $consulta = "SELECT 
                            id_localizacion, nombre
                     FROM 
                            g_catalogos.localizacion
	                 WHERE 
                            categoria=0
	                 ORDER BY nombre ASC ;";
	    $resultado = $this->modeloDatosIngreso->ejecutarSqlNativo($consulta);
	    return $resultado;
	}
	
	public function obtenerPuertos($idPais){
	   $consulta = "SELECT 
                        nombre_puerto, id_puerto, id_pais
                     FROM 
                        g_catalogos.puertos
	                 WHERE 
                        id_pais=".$idPais."
                     ORDER BY 1;";
	    $resultado = $this->modeloDatosIngreso->ejecutarSqlNativo($consulta);
	    return $resultado;
	    
	}
	
}
