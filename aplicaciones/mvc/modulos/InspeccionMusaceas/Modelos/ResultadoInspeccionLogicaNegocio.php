<?php
 /**
 * Lógica del negocio de ResultadoInspeccionModelo
 *
 * Este archivo se complementa con el archivo ResultadoInspeccionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    ResultadoInspeccionLogicaNegocio
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
  use Agrodb\InspeccionMusaceas\Modelos\IModelo;
 
class ResultadoInspeccionLogicaNegocio implements IModelo 
{

	 private $modeloResultadoInspeccion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloResultadoInspeccion = new ResultadoInspeccionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
    		$tablaModelo = new ResultadoInspeccionModelo($datos);
    		$datosBd = $tablaModelo->getPrepararDatos();
    		if ($tablaModelo->getIdResultadoInspeccion() != null && $tablaModelo->getIdResultadoInspeccion() > 0) {
    		return $this->modeloResultadoInspeccion->actualizar($datosBd, $tablaModelo->getIdResultadoInspeccion());
    		} else {
    		unset($datosBd["id_resultado_inspeccion"]);
    		return $this->modeloResultadoInspeccion->guardar($datosBd);
    	}
	}
	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardarResultado(Array $datos)
	{
	    try{
	        
	        $resultado = $datos['resultado'];
	        if($resultado[0] == 'apTotal'){
	            $datos['resultado'] ='Aprobación total';
	        }else if($resultado[0] == 'apParcial'){
	            $datos['resultado'] ='Aprobación parcial';
	        }else{
	            $datos['resultado'] ='Desaprobación total';
	        }
	        
	        $datos['identificador_operador'] =$_SESSION['usuario'];
	        //**
	        $this->modeloResultadoInspeccion = new ResultadoInspeccionModelo();
	        $proceso = $this->modeloResultadoInspeccion->getAdapter()
	        ->getDriver()
	        ->getConnection();
	        if (! $proceso->beginTransaction()){
	            throw new \Exception('No se pudo iniciar la transacción: Guardar resultado de inspección');
	        }
	        $tablaModelo = new ResultadoInspeccionModelo($datos);
	        $datosBd = $tablaModelo->getPrepararDatos();
	        unset($datosBd["id_resultado_inspeccion"]);
	        
	        $idResultado = $this->modeloResultadoInspeccion->guardar($datosBd);
	        if (!$idResultado)
	        {
	            throw new \Exception('No se registo los datos en la tabla resultado inspección');
	        }
	        //*************guadar detalle de solicitud*************
	        if(isset($datos['check'])){
	            $lnegocioDetalleResultadoInspeccion = new DetalleResultadoInspeccionLogicaNegocio();
	            foreach ($datos['check'] as $item) {
	                $datos = array(
	                    'id_resultado_inspeccion' => $idResultado,
	                    'id_detalle_solicitud_inspeccion' => $item
	                );
	                $statement = $this->modeloResultadoInspeccion->getAdapter()
	                ->getDriver()
	                ->createStatement();
	                $sqlInsertar = $this->modeloResultadoInspeccion->guardarSql('detalle_resultado_inspeccion', $this->modeloResultadoInspeccion->getEsquema());
	                $sqlInsertar->columns($lnegocioDetalleResultadoInspeccion->columnas());
	                $sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
	                $sqlInsertar->prepareStatement($this->modeloResultadoInspeccion->getAdapter(), $statement);
	                $statement->execute();
	            }
	        }
	        
	        $proceso->commit();
	        return true;
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
		$this->modeloResultadoInspeccion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ResultadoInspeccionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloResultadoInspeccion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloResultadoInspeccion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloResultadoInspeccion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarResultadoInspeccion()
	{
	$consulta = "SELECT * FROM ".$this->modeloResultadoInspeccion->getEsquema().". resultado_inspeccion";
		 return $this->modeloResultadoInspeccion->ejecutarSqlNativo($consulta);
	}
	
	public function fecha($fecha)
	{
	    $date = new \DateTime($fecha);
	    $meses = array(
	        "Enero",
	        "Febrero",
	        "Marzo",
	        "Abril",
	        "Mayo",
	        "Junio",
	        "Julio",
	        "Agosto",
	        "Septiembre",
	        "Octubre",
	        "Noviembre",
	        "Diciembre"
	    );
	        
	    $fechaFinal = $date->format('d') . " de " . $meses[$date->format('n') - 1] . " del " . $date->format('Y') . ' ';
	    return $fechaFinal;
	}
   
}
