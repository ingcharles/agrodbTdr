<?php
 /**
 * Lógica del negocio de NotificarSolicitudModelo
 *
 * Este archivo se complementa con el archivo NotificarSolicitudControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    NotificarSolicitudLogicaNegocio
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
  use Agrodb\InspeccionMusaceas\Modelos\IModelo;
 
class NotificarSolicitudLogicaNegocio implements IModelo 
{

	 private $modeloNotificarSolicitud = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloNotificarSolicitud = new NotificarSolicitudModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new NotificarSolicitudModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdNotificarSolicitud() != null && $tablaModelo->getIdNotificarSolicitud() > 0) {
		return $this->modeloNotificarSolicitud->actualizar($datosBd, $tablaModelo->getIdNotificarSolicitud());
		} else {
		unset($datosBd["id_notificar_solicitud"]);
		return $this->modeloNotificarSolicitud->guardar($datosBd);
	}
	}
	
	public function guardarNotificar(Array $datos)
	{
	    try{
	        $this->modeloNotificarSolicitud = new NotificarSolicitudModelo();
	        $proceso = $this->modeloNotificarSolicitud->getAdapter()
	        ->getDriver()
	        ->getConnection();
	        if (! $proceso->beginTransaction()){
	            throw new \Exception('No se pudo iniciar la transacción: Guardar notificacion');
	        }
	        $tablaModelo = new NotificarSolicitudModelo($datos);
	        $datosBd = $tablaModelo->getPrepararDatos();
	        unset($datosBd["id_notificar_solicitud"]);
	        $idNotificacion= $this->modeloNotificarSolicitud->guardar($datosBd);
	        if (!$idNotificacion)
	        {
	            throw new \Exception('No se registo los datos en la tabla notificacion solicitud');
	        }
	        //*************guadar detalle de solicitud*************
	        if(isset($datos['check'])){
	            $lnegocioDetalleNotificacionInspeccion = new DetalleNotificarInspeccionLogicaNegocio();
	            $lNegocioDetalleSolicitudInspeccion = new DetalleSolicitudInspeccionLogicaNegocio();
	            $lNegocioSolicitudInspeccion = new SolicitudInspeccionLogicaNegocio();
	            foreach ($datos['check'] as $item) {
	                $correo='';
	                $productor = $lNegocioDetalleSolicitudInspeccion->buscar($item);
	                $operador = $lNegocioSolicitudInspeccion->obtenerCorreoOperador($productor->getIdentificadorOperador());
	                $correo = $operador->current()->correo;
	                
	                $datos = array(
	                    'id_notificar_inspeccion' => $idNotificacion,
	                    'correo_productor' => $correo,
	                    'id_detalle_solicitud_inspeccion' =>$item
	                );
	                $statement = $this->modeloNotificarSolicitud->getAdapter()
	                ->getDriver()
	                ->createStatement();
	                $sqlInsertar = $this->modeloNotificarSolicitud->guardarSql('detalle_notificar_inspeccion', $this->modeloNotificarSolicitud->getEsquema());
	                $sqlInsertar->columns($lnegocioDetalleNotificacionInspeccion->columnas());
	                $sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
	                $sqlInsertar->prepareStatement($this->modeloNotificarSolicitud->getAdapter(), $statement);
	                $statement->execute();
	            }
	        }
	        
	        $proceso->commit();
	        return $idNotificacion;
	    }catch (\Exception $ex){
	        $proceso->rollback();
	        throw new \Exception($ex->getMessage());
	        return 0;
	    }
	}
	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloNotificarSolicitud->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return NotificarSolicitudModelo
	*/
	public function buscar($id)
	{
		return $this->modeloNotificarSolicitud->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloNotificarSolicitud->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloNotificarSolicitud->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarNotificarSolicitud()
	{
	$consulta = "SELECT * FROM ".$this->modeloNotificarSolicitud->getEsquema().". notificar_solicitud";
		 return $this->modeloNotificarSolicitud->ejecutarSqlNativo($consulta);
	}

}
