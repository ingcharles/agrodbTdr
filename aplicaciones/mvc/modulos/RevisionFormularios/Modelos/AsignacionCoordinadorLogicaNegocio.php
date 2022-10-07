<?php
 /**
 * Lógica del negocio de AsignacionCoordinadorModelo
 *
 * Este archivo se complementa con el archivo AsignacionCoordinadorControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-25
 * @uses    AsignacionCoordinadorLogicaNegocio
 * @package RevisionFormularios
 * @subpackage Modelos
 */
  namespace Agrodb\RevisionFormularios\Modelos;
  
  use Agrodb\RevisionFormularios\Modelos\IModelo;
  use Agrodb\Core\Excepciones\GuardarExcepcion;
 
class AsignacionCoordinadorLogicaNegocio implements IModelo 
{

	 private $modeloAsignacionCoordinador = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAsignacionCoordinador = new AsignacionCoordinadorModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
	    
	    try{

	        $tablaModelo = new AsignacionCoordinadorModelo($datos);   		
	        $idAsignacionCordinador = 0;
    		$validarAsignacionInspector = $this->buscarAsignacionCoordinador($datos);
    		
    		if(!isset($validarAsignacionInspector->current()->id_asignacion_coordinador)){
    		    $procesoIngreso = $this->modeloAsignacionCoordinador->getAdapter()
    		    ->getDriver()
    		    ->getConnection();
    		    $procesoIngreso->beginTransaction();
    		    
    		    $datosBd = $tablaModelo->getPrepararDatos();
    		    if ($tablaModelo->getIdAsignacionCoordinador() != null && $tablaModelo->getIdAsignacionCoordinador() > 0) {
    		        $this->modeloAsignacionCoordinador->actualizar($datosBd, $tablaModelo->getIdAsignacionCoordinador());
    		        $idAsignacionCordinador = $tablaModelo->getIdAsignacionCoordinador();
    		    } else {
    		        unset($datosBd["id_asignacion_coordinador"]);
    		        $idAsignacionCordinador = $this->modeloAsignacionCoordinador->guardar($datosBd);
    		    }   		    
    		    
    		    $procesoIngreso->commit();
    		    return $idAsignacionCordinador;
    		}else{
    		    return $idAsignacionCordinador;    		    
    		}
    		
	    }catch (GuardarExcepcion $ex){
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
		$this->modeloAsignacionCoordinador->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return AsignacionCoordinadorModelo
	*/
	public function buscar($id)
	{
		return $this->modeloAsignacionCoordinador->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAsignacionCoordinador->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAsignacionCoordinador->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAsignacionCoordinador($arrayParametros)
	{
        $consulta = "SELECT 
                        ac.id_asignacion_coordinador
                        , ac.identificador_inspector
                        , fe.nombre || ' ' ||fe.apellido as nombre_revisor
                        , dc.provincia
                        , ac.fecha_asignacion
                        , ac.identificador_asignante
                        , ac.tipo_solicitud
                        , ac.id_solicitud
                        , ac.tipo_inspector
                        , ac.estado
                    FROM
                        g_revision_solicitudes.asignacion_coordinador ac
                        INNER JOIN g_uath.ficha_empleado fe ON ac.identificador_inspector = fe.identificador
                        INNER JOIN g_uath.datos_contrato dc ON ac.identificador_inspector = dc.identificador
                    WHERE  
                        ac.id_solicitud  = " . $arrayParametros['id_solicitud'] . " 
                        and ac.tipo_solicitud = '" . $arrayParametros['tipo_solicitud'] . "' 
                        and ac.tipo_inspector = '" . $arrayParametros['tipo_inspector'] . "'
                        and dc.estado = 1";
        return $this->modeloAsignacionCoordinador->ejecutarSqlNativo($consulta);
	}
	
}
