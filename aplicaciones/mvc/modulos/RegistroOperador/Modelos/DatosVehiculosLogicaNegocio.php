<?php
 /**
 * Lógica del negocio de CodigosPoaModelo
 *
 * Este archivo se complementa con el archivo CodigosPoaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-01-10
 * @uses    CodigosPoaLogicaNegocio
 * @package RegistroOperador
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroOperador\Modelos;
  
  use Agrodb\RegistroOperador\Modelos\IModelo;
  use Agrodb\FormulariosInspeccion\Modelos\Mdtf01Modelo;
  use Agrodb\FormulariosInspeccion\Modelos\Mdtf01LogicaNegocio;
  use Agrodb\FormulariosInspeccion\Modelos\Mdtf01DetalleModelo;
  use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;
  use Agrodb\Token\Modelos\TokenLogicaNegocio;
  use Agrodb\Core\Excepciones\GuardarExcepcionConDatos;
  use Agrodb\Core\JasperReport;
  use Exception;
class DatosVehiculosLogicaNegocio implements IModelo 
{

	private $modeloDatosVehiculos = null;
	private $modeloMdtf01 = null;
	private $modeloMdtf01Detalle = null;
	private $lNegocioMdtf01 = null;
	private $lNegocioOperaciones = null;
	private $lNegocioToken = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
		$this->modeloDatosVehiculos = new DatosVehiculosModelo();
		$this->modeloMdtf01 = new Mdtf01Modelo();
		$this->modeloMdtf01Detalle = new Mdtf01DetalleModelo();
		$this->lNegocioMdtf01 = new Mdtf01LogicaNegocio();
		$this->lNegocioOperaciones = new OperacionesLogicaNegocio();
		$this->lNegocioToken = new TokenLogicaNegocio();
		$this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');	
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DatosVehiculosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDatoVehiculo() != null && $tablaModelo->getIdDatoVehiculo() > 0) {
		return $this->modeloDatosVehiculos->actualizar($datosBd, $tablaModelo->getIdDatoVehiculo());
		} else {
		unset($datosBd["id_dato_vehiculo"]);
		return $this->modeloDatosVehiculos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDatosVehiculos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DatosVehiculosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDatosVehiculos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDatosVehiculos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDatosVehiculos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDatosVehiculos()
	{
	$consulta = "SELECT * FROM ".$this->modeloDatosVehiculos->getEsquema().". datos_vehiculos";
		 return $this->modeloDatosVehiculos->ejecutarSqlNativo($consulta);
	}

	public function buscarDatosVehiculosActivo($arrayParametros)
	{
		$consulta = "SELECT * from g_operadores.datos_vehiculos dv
			INNER JOIN
			(SELECT max(id_dato_Vehiculo) as id_dato_vehiculo 
			from g_operadores.datos_vehiculos 
			where placa_vehiculo = '" . $arrayParametros["placa_vehiculo"] . "'
			and id_tipo_operacion = 101
			and estado_dato_vehiculo = 'activo') tdv
			ON tdv.id_dato_vehiculo = dv.id_dato_vehiculo";
			return $this->modeloDatosVehiculos->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function guardarDatosInspeccionMedioTransporteAI($arrayParametros)
	{

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){

		try{

	        $procesoIngreso = $this->modeloDatosVehiculos->getAdapter()
	        ->getDriver()
	        ->getConnection();
	        $procesoIngreso->beginTransaction();

	        foreach ($arrayParametros['inspeccion'] as $value) {

	            $arrayResultadoInspeccion = array();
	            $arrayChecklistResumenInspeccion = array();

	            foreach ($value->cabecera as $cabeceraLlave => $cabeceraValor) {                
	                $arrayResultadoInspeccion += [
	                    $cabeceraLlave => $cabeceraValor
	                ];
	            }

	            $idOperadorTipoOperacion = $arrayResultadoInspeccion['id_operador_tipo_operacion'];	            
	            $solicitudMedioTransporte = $this->buscarDatosVehiculosPorIdOperadorTipoOperacion($idOperadorTipoOperacion);
	            
	            if(isset($solicitudMedioTransporte->current()->id_dato_vehiculo)){
	                //Realiza la actualizacion de los campos de la tabla de datos_vehiculo
	                $statement = $this->modeloDatosVehiculos->getAdapter()->getDriver()->createStatement();
	                $sqlActualizar = $this->modeloDatosVehiculos->actualizarSql('datos_vehiculos', $this->modeloDatosVehiculos->getEsquema());
	                $sqlActualizar->set(array('origen_inspeccion' => $arrayResultadoInspeccion['origen_inspeccion'], 'estado_checklist' => $arrayResultadoInspeccion['estado_checklist']));
	                $sqlActualizar->where(array('id_operador_tipo_operacion' => $idOperadorTipoOperacion, 'estado_dato_vehiculo' => 'activo'));
	                $sqlActualizar->prepareStatement($this->modeloDatosVehiculos->getAdapter(), $statement);
	               $statement->execute();
	              
	                //$solicitudMedioTransporte = $this->modeloDatosVehiculos->adapter->driver->getLastGeneratedValue($this->modeloDatosVehiculos->getEsquema().'.datos_vehiculos_id_dato_vehiculo_seq');
	                
	                //$solicitudMedioTransporte = $this->actualizarDatosVehiculoInspeccion($arrayResultadoInspeccion);
// 	            }
	            
	            
// 	            if(isset($solicitudMedioTransporte->current()->id_dato_vehiculo)){	                
	                
	                //Actualiza los resumenes de inspecciones anteriores
	                $statement = $this->modeloDatosVehiculos->getAdapter()->getDriver()->createStatement();
	                $sqlActualizar = $this->modeloDatosVehiculos->actualizarSql('mdtf01', $this->modeloMdtf01->getEsquema());
	                $sqlActualizar->set( array('estado_generar_checklist' => 'inactivo'));
	                $sqlActualizar->where(array('id_operador_tipo_operacion' => $idOperadorTipoOperacion,'estado_generar_checklist' => 'activo'));
	                $sqlActualizar->prepareStatement($this->modeloDatosVehiculos->getAdapter(), $statement);
	                $statement->execute();
	                
	                $this->lNegocioMdtf01->actualizarEstadoInspeccionMdtPorIdSolicitud($arrayResultadoInspeccion['id_solicitud']);
	                
	                foreach ($value->checklist_resumen as $checklistResumenLlave => $checklistResumenValor) {
	                    
	                    if (! is_array($checklistResumenValor)) {
	                        $arrayChecklistResumenInspeccion += [
	                            $checklistResumenLlave => $checklistResumenValor
	                        ];
	                    }
	                }
	               
	                //unset($arrayChecklistResumenInspeccion['id_solicitud']);
	                // Guarda el resumen de checklist de inspeccion

	                $statement = $this->modeloDatosVehiculos->getAdapter()->getDriver()->createStatement();
	                $sqlInsertar = $this->modeloDatosVehiculos->guardarSql('mdtf01', $this->modeloMdtf01->getEsquema());
	                $sqlInsertar->columns($this->modeloMdtf01->getColumns());
	                $sqlInsertar->values($arrayChecklistResumenInspeccion, $sqlInsertar::VALUES_MERGE);
	                $sqlInsertar->prepareStatement($this->modeloDatosVehiculos->getAdapter(), $statement);
	                $statement->execute();
	                $idInspeccionMdtf01 = $this->modeloDatosVehiculos->adapter->driver->getLastGeneratedValue($this->modeloMdtf01->getEsquema().'.mdtf01_id_seq');
	               
	                foreach ($value->checklist_resumen->checklist_inspeccion as $item) {
	                   
	                    $item->id_padre = $idInspeccionMdtf01; 
	                    $array = json_decode(json_encode($item),true);
	                    unset($array['id_tablet']);
	                    $statement = $this->modeloDatosVehiculos->getAdapter()->getDriver()->createStatement();
	                    $sqlInsertar = $this->modeloDatosVehiculos->guardarSql('mdtf01_detalle', $this->modeloMdtf01Detalle->getEsquema());
	                    $sqlInsertar->columns($this->modeloMdtf01Detalle->getColumns());
	                    $sqlInsertar->values($array, $sqlInsertar::VALUES_MERGE);
	                    $sqlInsertar->prepareStatement($this->modeloDatosVehiculos->getAdapter(), $statement);
	                    $statement->execute();
	                    
	                }
					$arrayResultadoInspeccion['id_operacion'] = $arrayResultadoInspeccion['id_solicitud'];
	                $this->lNegocioOperaciones->guardarResultadoInspeccion($arrayResultadoInspeccion);
	                
	                //TODO: Hacer que se actualicen el estado de las operaciones por id_operador_tipo_operacion
	                //, poner en el id_solicitud generar_certificado "SI" y hacer el proceso de generacion de certificado
	                             
	            
	            }
	            
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
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDatosVehiculosPorIdOperadorTipoOperacion($idOperadorTipoOperacion)
	{
	    $consulta = "SELECT
                        id_dato_vehiculo
                        , id_area
                        , id_tipo_operacion
                        , id_operador_tipo_operacion
                    FROM
                        g_operadores.datos_vehiculos
                    WHERE
                        id_operador_tipo_operacion = '" . $idOperadorTipoOperacion . "'
                        and estado_dato_vehiculo = 'activo';";
	    
	    return $this->modeloDatosVehiculos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */

	public function obtenerSolicitudesPorGenerarChecklist (){
	    
	    $consulta = "SELECT 
                    	id_dato_vehiculo
                    	, id_operador_tipo_operacion
                    	, estado_dato_vehiculo
                    	, origen_inspeccion
                    	, estado_checklist
                    FROM 
                    	g_operadores.datos_vehiculos
                    WHERE
                    	origen_inspeccion = 'aplicativoMovil'
                    	and estado_checklist = 'generar' and estado_dato_vehiculo = 'activo';";
	    
	    return $this->modeloDatosVehiculos->ejecutarSqlNativo($consulta);
	    
	}
	
	/**
	 * Función para crear el PDF del checklist de inspeccion
	 */
	public function generarChecklistInspeccionMedioTransporte($idSolicitud, $nombreArchivo){
	    $jasper = new JasperReport();
	    $datosReporte = array();
	    
	    $ruta = MED_TRANS_CHECK_LIST_TCPDF . $this->rutaFecha . '/';
	    
	    if (! file_exists($ruta)){
	        mkdir($ruta, 0777, true);
	    }
	    
	    $rutaChecklistMedioTransporte = MED_TRANS_CHECK_LIST . $this->rutaFecha . '/';
	    
	    $datosReporte = array(
	        'rutaReporte' => 'RegistroOperador/vistas/reportes/medioTransporteAI/checklistMedioTransporteAI.jasper',
	        'rutaSalidaReporte' => 'RegistroOperador/archivos/checkList/medioTransporteAI/' . $this->rutaFecha . '/' . $nombreArchivo,
	        'tipoSalidaReporte' => array('pdf'),
	        'parametrosReporte' => array('idSolicitud' => $idSolicitud, 'rutaLogoAgro' => RUTA_IMG_GENE.'agrocalidad.png'),
	        'conexionBase' => 'SI');

	    $jasper->generarArchivo($datosReporte);
	    
	    $rutaChecklist = $rutaChecklistMedioTransporte . $nombreArchivo . '.pdf';
	    
	    return $rutaChecklist;
	}
}
