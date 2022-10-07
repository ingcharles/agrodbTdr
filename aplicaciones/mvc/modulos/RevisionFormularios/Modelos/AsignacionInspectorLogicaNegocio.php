<?php
/**
 * Lógica del negocio de AsignacionInspectorModelo
 *
 * Este archivo se complementa con el archivo AsignacionInspectorControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-14
 * @uses AsignacionInspectorLogicaNegocio
 * @package RevisionFormularios
 * @subpackage Modelos
 */
namespace Agrodb\RevisionFormularios\Modelos;

use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\RevisionFormularios\Modelos\IModelo;
use Agrodb\CertificadoFitosanitario\Modelos\InspeccionesLogicaNegocio;

class AsignacionInspectorLogicaNegocio implements IModelo{

	private $modeloAsignacionInspector = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloAsignacionInspector = new AsignacionInspectorModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
			
			$statement = $this->modeloAsignacionInspector->getAdapter()
			->getDriver()
			->createStatement();

			$arrayAsignacionInspector = array('identificador_inspector' => $datos['identificador_inspector'],
			    'fecha_asignacion' => $datos['fecha_asignacion'],
			    'identificador_asignante' => $datos['identificador_asignante'],
			    'tipo_solicitud' => $datos['tipo_solicitud'],
			    'tipo_inspector' => $datos['tipo_inspector'],
			    'id_operador_tipo_operacion' => $datos['id_operador_tipo_operacion'],
			    'id_historial_operacion' => $datos['id_historial_operacion']);

			$sqlInsertar = $this->modeloAsignacionInspector->guardarSql('asignacion_inspector', 'g_revision_solicitudes');
			$sqlInsertar->columns($this->columnas());
			$sqlInsertar->values($arrayAsignacionInspector, $sqlInsertar::VALUES_MERGE);
			$sqlInsertar->prepareStatement($this->modeloAsignacionInspector->getAdapter(), $statement);
			$statement->execute();
			$idGrupo = $this->modeloAsignacionInspector->adapter->driver->getLastGeneratedValue('g_revision_solicitudes' . '.asignacion_inspector_id_grupo_seq');
			
			
			$lNegocioGrupoSolicitudes = new GruposSolicitudesLogicaNegocio();
			
			$statement = $this->modeloAsignacionInspector->getAdapter()
			->getDriver()
			->createStatement();
			
			$datosGrupoSolicitudes = array('id_grupo' => $idGrupo,
				'id_solicitud' => $datos['id_solicitud'],
				'estado'=> $datos['estado']);			
			
			$sqlInsertar = $this->modeloAsignacionInspector->guardarSql('grupos_solicitudes', $this->modeloAsignacionInspector->getEsquema());
			$sqlInsertar->columns($lNegocioGrupoSolicitudes->columnas());
			$sqlInsertar->values($datosGrupoSolicitudes, $sqlInsertar::VALUES_MERGE);
			$sqlInsertar->prepareStatement($this->modeloAsignacionInspector->getAdapter(), $statement);
			$statement->execute();
			
			$lNegocioRevisionDocumental = new RevisionDocumentalLogicaNegocio();
			$lNegocioInspeccion = new InspeccionLogicaNegocio();
			
			$statement = $this->modeloAsignacionInspector->getAdapter()
			->getDriver()
			->createStatement();			
			
			$datosRevisionSolicitud = array('id_grupo' => $idGrupo,
				'identificador_inspector' => $datos['identificador_inspector'],
				'fecha_inspeccion' => $datos['fecha_inspeccion'],
				'observacion' => $datos['observacion'],
				'estado'=> $datos['estado_siguiente'],
				'orden'=> $datos['orden']
			);
			
			$tipoInspector = $datos['tipo_inspector'];
			
			if($tipoInspector == "Documental"){		    
			    $datosRevisionSolicitud += ['ruta_archivo_documental' => (isset($datos['ruta_archivo_documental']) ? $datos['ruta_archivo_documental'] : '')];
			    $sqlInsertar = $this->modeloAsignacionInspector->guardarSql('revision_documental', $this->modeloAsignacionInspector->getEsquema());
			    $sqlInsertar->columns($lNegocioRevisionDocumental->columnas());
			    $sqlInsertar->values($datosRevisionSolicitud, $sqlInsertar::VALUES_MERGE);
			    $sqlInsertar->prepareStatement($this->modeloAsignacionInspector->getAdapter(), $statement);
			    $statement->execute();
			}else if($tipoInspector == "Técnico"){
                $datosRevisionSolicitud += ['ruta_archivo' => (isset($datos['ruta_archivo']) ? $datos['ruta_archivo'] : '')];
			    $sqlInsertar = $this->modeloAsignacionInspector->guardarSql('inspeccion', $this->modeloAsignacionInspector->getEsquema());
			    $sqlInsertar->columns($lNegocioInspeccion->columnas());
			    $sqlInsertar->values($datosRevisionSolicitud, $sqlInsertar::VALUES_MERGE);
			    $sqlInsertar->prepareStatement($this->modeloAsignacionInspector->getAdapter(), $statement);
			    $statement->execute();
			}		
			
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloAsignacionInspector->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return AsignacionInspectorModelo
	 */
	public function buscar($id){
		return $this->modeloAsignacionInspector->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloAsignacionInspector->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloAsignacionInspector->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarAsignacionInspector(){
		$consulta = "SELECT * FROM " . $this->modeloAsignacionInspector->getEsquema() . ". asignacion_inspector";
		return $this->modeloAsignacionInspector->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Devuelve las columnas a ser insertadas.
	 *
	 * @return String
	 */
	public function columnas(){
	    $columnas = array(
	        'identificador_inspector',
	        'fecha_asignacion',
	        'identificador_asignante',
	        'tipo_solicitud',
	        'tipo_inspector',
	        'id_operador_tipo_operacion',
	        'id_historial_operacion'
	    );
	    
	    return $columnas;
	}
}
