<?php
 /**
 * Lógica del negocio de CertificadoMedicoModelo
 *
 * Este archivo se complementa con el archivo CertificadoMedicoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-16
 * @uses    CertificadoMedicoLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
  namespace Agrodb\HistoriasClinicas\Modelos;
  
  use Agrodb\HistoriasClinicas\Modelos\IModelo;
 
class CertificadoMedicoLogicaNegocio implements IModelo 
{

	 private $modeloCertificadoMedico = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCertificadoMedico = new CertificadoMedicoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new CertificadoMedicoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCertificadoMedico() != null && $tablaModelo->getIdCertificadoMedico() > 0) {
		return $this->modeloCertificadoMedico->actualizar($datosBd, $tablaModelo->getIdCertificadoMedico());
		} else {
		unset($datosBd["id_certificado_medico"]);
		return $this->modeloCertificadoMedico->guardar($datosBd);
	}
	}
	public function guardarAdjunto(Array $datos)
	{
	    try{
	        $this->modeloCertificadoMedico = new CertificadoMedicoModelo();
	        $proceso = $this->modeloCertificadoMedico->getAdapter()
	        ->getDriver()
	        ->getConnection();
	        if (! $proceso->beginTransaction()){
	            throw new \Exception('No se pudo iniciar la transacción: certificado_medico ');
	        }
	        if($datos['fecha_salida'] == null){
	            unset($datos["fecha_salida"]);
	        }
	        $tablaModelo = new CertificadoMedicoModelo($datos);
	        $datosBd = $tablaModelo->getPrepararDatos();
	        unset($datosBd["id_certificado_medico"]);
	        $idCertificadoMedico = $this->modeloCertificadoMedico->guardar($datosBd);
	        if (!$idCertificadoMedico)
	        {
	            throw new \Exception('No se registo los datos en la tabla certificado_medico');
	        }
	        
	        $arrayAdjunto = array(
	            'id_certificado_medico' => $idCertificadoMedico,
	            'archivo_adjunto' => $datos['archivo_adjunto']
	        );
	        $this->guardarDetalles($arrayAdjunto);
	        $arrayEmail = array(
	            'id_certificado_medico' => $idCertificadoMedico,
	            'tipoDocumento' => $datos['descripcion_certificado'],
	            'fechaGeneracion' => $datos['fecha_certificado'],
	            'identificadorPaciente' => $datos['identificador_paciente'],
	            'mail_institucional' => $datos['mail_institucional'],
	            'mail_personal' => $datos['mail_personal']
	            
	        );
	        $this->notificarEmail($arrayEmail);
	        
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
		$this->modeloCertificadoMedico->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return CertificadoMedicoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloCertificadoMedico->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCertificadoMedico->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCertificadoMedico->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCertificadoMedico()
	{
	$consulta = "SELECT * FROM ".$this->modeloCertificadoMedico->getEsquema().". certificado_medico";
		 return $this->modeloCertificadoMedico->ejecutarSqlNativo($consulta);
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar detalles
	 *
	 * @return array
	 */
	public function guardarDetalles($datos){
	    $statement = $this->modeloCertificadoMedico->getAdapter()
	    ->getDriver()
	    ->createStatement();
	    $lnegocioAdjuntoCertificadoMedico = new AdjuntosCertificadoMedicoLogicaNegocio();
	    $sqlInsertar = $this->modeloCertificadoMedico->guardarSql('adjuntos_certificado_medico', $this->modeloCertificadoMedico->getEsquema());
	    $sqlInsertar->columns($lnegocioAdjuntoCertificadoMedico->columnas());
	    $sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
	    $sqlInsertar->prepareStatement($this->modeloCertificadoMedico->getAdapter(), $statement);
	    $statement->execute();
	}
	/**
	 * Notificar envío de emails
	 *
	 */
	public function notificarEmail($arrayEmail)
	{
	    $asunto = $arrayEmail['tipoDocumento'].' generado';
	    $familiaLetra = "font-family:Text Me One,Segoe UI, Tahoma, Helvetica, freesans, sans-serif";
	    
	    $cuerpoMensaje = '<table><tbody>
			<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Estimad@,</tr>
            <tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Se le comunica que usted tiene pendiente la revisión del siguiente documento que ha sido generado por el médico ocupacional de Agrocalidad: </td></tr>
			<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Nombre del documento: '. $arrayEmail['tipoDocumento'].'<br><br>Fecha de generación: '. $arrayEmail['fechaGeneracion'].' <br><br></td></tr>
			<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Ingrese al sistema GUIA con sus credenciales, a la opción de menú “Mis Programas”, módulo “Historia Clínica”, ingrese al siguiente link para revisar dicho registro:<br>  </td></tr>
			<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;"><a>https://guia.agrocalidad.gob.ec</a><br>  </td></tr>
            <tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">NOTA: Este correo fue generado automáticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
			</tbody></table>';
	    
	    $arrayMailsDestino = array();
	    if($arrayEmail['mail_institucional']!= ''){
	        $arrayMailsDestino[] =$arrayEmail['mail_institucional'];
	    }else if($arrayEmail['mail_personal'] !=''){
	        $arrayMailsDestino[] =$arrayEmail['mail_institucional'];
	    }
	    
	    $mailsDestino = array_unique($arrayMailsDestino);
	    if (count($mailsDestino) > 0)
	    {
	        $datosCorreo = array(
	            'asunto' => $asunto,
	            'cuerpo' => $cuerpoMensaje,
	            'codigo_modulo' => "PRG_HIST_CLINI",
	            'tabla_modulo' => "g_historias_clinicas.certificado_medico",
	            'id_solicitud_tabla' => $arrayEmail['id_certificado_medico'],
	            'estado' => 'Por enviar'
	        );
	        $modeloCorreos = new \Agrodb\Correos\Modelos\CorreosModelo();
	        $idCorreo = $modeloCorreos->guardar($datosCorreo);
	        
	        //Guardar correo del destino
	        $destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
	        foreach ($mailsDestino as $val)
	        {
	            $datosDestino = array('id_correo' => $idCorreo, 'destinatario_correo' => $val);
	            $destino->guardar($datosDestino);
	        }
	    } 
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
	    $consulta ='SELECT id_certificado_medico, hc.id_historia_clinica, hc.identificador_medico,
                	descripcion_certificado, fecha_certificado, analisis, recomendaciones,
                	fecha_salida, observaciones, cm.fecha_creacion, cm.estado
                	FROM g_historias_clinicas.historia_clinica hc
                	INNER JOIN g_uath.ficha_empleado fe ON hc.identificador_paciente = fe.identificador
                	INNER JOIN g_historias_clinicas.certificado_medico cm ON hc.id_historia_clinica = cm.id_historia_clinica
                	WHERE '.$sql.' order by 1';
	
	    return $this->modeloCertificadoMedico->ejecutarSqlNativo($consulta);
	    
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el secuencial
	 *
	 * @return array
	 */
	public function obtenerSecuencialCertificado($arrayParametros){
	    $consulta = "SELECT
						COALESCE(count(*)::numeric, 0) AS numero
					FROM
						g_historias_clinicas.certificado_medico
					WHERE
						descripcion_certificado = '" . $arrayParametros['descripcion_certificado'] . "';";
	    
	    $resultado = $this->modeloCertificadoMedico->ejecutarSqlNativo($consulta);
	    return $resultado;
	}
}
