<?php
 /**
 * Lógica del negocio de ConsultaMedicaModelo
 *
 * Este archivo se complementa con el archivo ConsultaMedicaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-16
 * @uses    ConsultaMedicaLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
  namespace Agrodb\HistoriasClinicas\Modelos;
  
  use Agrodb\HistoriasClinicas\Modelos\IModelo;
 
class ConsultaMedicaLogicaNegocio implements IModelo 
{

	 private $modeloConsultaMedica = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloConsultaMedica = new ConsultaMedicaModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ConsultaMedicaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdConsultaMedica() != null && $tablaModelo->getIdConsultaMedica() > 0) {
		return $this->modeloConsultaMedica->actualizar($datosBd, $tablaModelo->getIdConsultaMedica());
		} else {
		unset($datosBd["id_consulta_medica"]);
		return $this->modeloConsultaMedica->guardar($datosBd);
	}
	}
	
	public function guardarDetalle(Array $datos)
	{
	    try{
	        $this->modeloConsultaMedica = new ConsultaMedicaModelo();
	        $proceso = $this->modeloConsultaMedica->getAdapter()
	        ->getDriver()
	        ->getConnection();
	        if (! $proceso->beginTransaction()){
	            throw new \Exception('No se pudo iniciar la transacción: consulta medica ');
	        }
	        $datos['estado'] = 'Finalizado';
	        if($datos['reposo_medico'] == 'No'){
	            unset($datos["dias_reposo"]);
	            unset($datos["fecha_desde"]);
	            unset($datos["fecha_hasta"]);
	            unset($datos["observaciones"]);
	        }
	        $tablaModelo = new ConsultaMedicaModelo($datos);
	        $datosBd = $tablaModelo->getPrepararDatos();
	        if ($tablaModelo->getIdConsultaMedica() != null && $tablaModelo->getIdConsultaMedica() > 0) {
	            $this->modeloConsultaMedica->actualizar($datosBd, $tablaModelo->getIdConsultaMedica());
	        } else {
	            throw new \Exception('Error en la actualizacion de datos consulta_medica ');
	        }
	        $lnegocioExamenFisico = new ExamenFisicoLogicaNegocio();
	        
	        $arrayExamenFisico = array(
	            'id_consulta_medica' => $datos['id_consulta_medica'],
	            'tension_arterial' => $datos['tension_arterial'],
	            'saturacion_oxigeno' => $datos['saturacion_oxigeno'],
	            'frecuencia_cardiaca' => $datos['frecuencia_cardiaca'],
	            'frecuencia_respiratoria' => $datos['frecuencia_respiratoria'],
	            'talla_mts' => $datos['talla_mts'],
	            'temperatura_c' => $datos['temperatura_c'],
	            'peso_kg' => $datos['peso_kg'],
	            'imc' => $datos['imc'],
	            'interpretacion_imc' => $datos['interpretacion_imc']
	        );
	        
    	        $statement = $this->modeloConsultaMedica->getAdapter()
    	        ->getDriver()
    	        ->createStatement();
	            $sqlInsertar = $this->modeloConsultaMedica->guardarSql('examen_fisico', $this->modeloConsultaMedica->getEsquema());
	            $sqlInsertar->columns($lnegocioExamenFisico->columnas1());
	            $sqlInsertar->values($arrayExamenFisico, $sqlInsertar::VALUES_MERGE);
	            $sqlInsertar->prepareStatement($this->modeloConsultaMedica->getAdapter(), $statement);
	            $statement->execute();
	        
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
		$this->modeloConsultaMedica->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ConsultaMedicaModelo
	*/
	public function buscar($id)
	{
		return $this->modeloConsultaMedica->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloConsultaMedica->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloConsultaMedica->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarConsultaMedica()
	{
	$consulta = "SELECT * FROM ".$this->modeloConsultaMedica->getEsquema().". consulta_medica";
		 return $this->modeloConsultaMedica->ejecutarSqlNativo($consulta);
	}
	/**
	 * consulta personalizada para consultar información personal del paciente
	 *
	 */
	public function buscarInformacionPaciente($arrayParametros){
	    $consulta = "SELECT
						identificador,nombre ||' '||apellido as funcionario,genero, estado_civil, fecha_nacimiento, edad,
       					tipo_sangre, convencional,tiene_discapacidad, carnet_conadis_empleado, representante_familiar_discapacidad,
       					tiene_enfermedad_catastrofica, nombre_enfermedad_catastrofica,carnet_conadis_familiar, mail_personal,
				        mail_institucional,to_char(hc.fecha_creacion,'YYYY-MM-DD') as fecha_creacion,hc.id_historia_clinica,(SELECT nivel_instruccion
						FROM g_uath.datos_academicos where identificador = fe.identificador and estado = 'Aceptado'  ORDER BY id_datos_academicos DESC LIMIT 1) as nivel_instruccion
	        
  					FROM
						g_uath.ficha_empleado fe INNER JOIN g_historias_clinicas.historia_clinica hc ON hc.identificador_paciente=fe.identificador 
					WHERE
						fe.identificador ='". $arrayParametros['identificador_paciente'] ."' and estado_empleado ='activo'  and hc.estado='Registrado' ";
	    return $this->modeloConsultaMedica->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * busqueda personalizada
	 *
	 */
	public function buscarFuncionario($arrayParametros)
	{
	    $sql='';
	    if(isset($arrayParametros['identificador_paciente'])){
	        $sql="fe.identificador='".$arrayParametros['identificador_paciente']."' ";
	    }else{
	        $sql="fe.apellido ilike '%".$arrayParametros['apellido']."%' ";
	    }
	    $consulta ='SELECT id_consulta_medica, hc.identificador_medico,hc.identificador_paciente,
                	fecha_consulta, cm.id_historia_clinica
                	FROM g_historias_clinicas.historia_clinica hc
                	INNER JOIN g_uath.ficha_empleado fe ON hc.identificador_paciente = fe.identificador
                	INNER JOIN g_historias_clinicas.consulta_medica cm ON hc.id_historia_clinica = cm.id_historia_clinica
                	WHERE '.$sql.' order by 1';
	    
	    return $this->modeloConsultaMedica->ejecutarSqlNativo($consulta);
	    
	}
}
