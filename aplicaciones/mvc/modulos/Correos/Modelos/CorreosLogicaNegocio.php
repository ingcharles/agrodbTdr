<?php

/**
 * Lógica del negocio de CorreosModelo
 *
 * Este archivo se complementa con el archivo CorreosControlador.
 *
 * @author DATASTAR
 * @uses CorreosLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */
namespace Agrodb\Correos\Modelos;

use Agrodb\Correos\Modelos\IModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class CorreosLogicaNegocio implements IModelo{

	private $modeloCorreos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloCorreos = new CorreosModelo();
	}
	
	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new CorreosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCorreo() != null && $tablaModelo->getIdCorreo() > 0){
			return $this->modeloCorreos->actualizar($datosBd, $tablaModelo->getIdCorreo());
		}else{
			unset($datosBd["id_correo"]);
			return $this->modeloCorreos->guardar($datosBd);
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
		$this->modeloCorreos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return CorreosModelo
	 */
	public function buscar($id){
		return $this->modeloCorreos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloCorreos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloCorreos->buscarLista($where, $order, $count, $offset);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarCorreos(){
		$consulta = "SELECT * FROM " . $this->modeloCorreos->getEsquema() . ". correos";
		return $this->modeloCorreos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Recibe la información del correo, destinatarios y documentos adjuntos para el envío
	 * de correos electrónicos automáticos
	 */
	public function crearCorreoElectronico($arrayCorreo, $arrayDestinatario, $arrayAdjuntos = null){
		$lNegocioCorreos = new CorreosLogicaNegocio();
		$lNegocioDestinatarios = new DestinatariosLogicaNegocio();
		$lNegocioDocumentosAdjuntos = new DocumentosAdjuntosLogicaNegocio();

		$mailsDestino = array_unique($arrayDestinatario);

		if (count($mailsDestino) > 0){
			// Guarda el correo
			$idCorreo = $lNegocioCorreos->guardar($arrayCorreo);

			// Guardar destinatarios
			foreach ($mailsDestino as $destino){
				$arrayParametros = array(
					'id_correo' => $idCorreo,
					'destinatario_correo' => $destino);

				$lNegocioDestinatarios->guardar($arrayParametros);
			}

			// Guardar documentos anexos
			if (isset($arrayAdjuntos)){
				$documentos = array_unique($arrayAdjuntos);

				if (count($documentos) > 0){
					foreach ($documentos as $rutaDocumento){
						$arrayParametros = array(
							'id_correo' => $idCorreo,
							'ruta_documento_adjunto' => $rutaDocumento);

						$lNegocioDocumentosAdjuntos->guardar($arrayParametros);
					}
				}
			}
		}else{
			throw new \Exception(Constantes::EMAIL_NCM_VACIO);
		}
	}

	/**
	 * Notificar firma electrónica
	 *
	 * @param type $id
	 * @param type $cedula
	 * @param type $claveTemporal
	 * @throws \Exception
	 */
	public function notificarFirmaElectronica($id, $cedula, $claveTemporal){
		$empleado = new \Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio();
		$empleado = $empleado->buscar($cedula);
		if (empty($empleado->getMailInstitucional()) && empty($empleado->getMailPersonal())){
			throw new \Exception(Constantes::EMAIL_FE_VACIO);
		}else{
			$datosCorreo = array(
				'asunto' => Constantes::EMAIL_FE_ASUNTO,
				'cuerpo' => Constantes::EMAIL_FE_MENSAJE . "<br>" . URL . "Laboratorios/FirmasElectronicas/token/" . $claveTemporal,
				'codigo_modulo' => "PRG_LABORATORIOS",
				'tabla_modulo' => "g_laboratorios.firmas_electronicas",
				'id_solicitud_tabla' => $id,
				'estado' => 'Por enviar');
			$idCorreo = $this->modeloCorreos->guardar($datosCorreo);
			// Guardar correo del destino
			$datosDestino = array();
			if (empty($empleado->getMailInstitucional())){
				$datosDestino = array(
					'id_correo' => $idCorreo,
					'destinatario_correo' => $empleado->getMailPersonal());
			}else{
				$datosDestino = array(
					'id_correo' => $idCorreo,
					'destinatario_correo' => $empleado->getMailInstitucional());
			}
			$destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
			$destino->guardar($datosDestino);
		}
	}

	/**
	 * Envía la notificación de las muestras no idoóneas al cliente con copia al usuario activo
	 *
	 * @param type $id
	 *        	id_solicitud
	 * @param type $usuarioGuia
	 *        	identificador del usuario que registro la solicitud
	 * @param type $urlArchivo
	 *        	path donde esta subido el archivo
	 * @param type $identificadorUsActivo
	 *        	identificador del usuario activo
	 * @throws \Exception
	 */
	public function notificarMuestraNoIdonea($id, $usuarioGuia, $urlArchivo, $identificadorUsActivo){
		// Buscar mail de usuario activo
		$empleado = new \Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio();
		$empleado = $empleado->buscar($identificadorUsActivo);
		$arrayMailsDestino = array();
		if (! empty($empleado->getMailInstitucional())){
			$arrayMailsDestino[] = $empleado->getMailInstitucional();
		}else{
			$arrayMailsDestino[] = $empleado->getMailPersonal();
		}
		// Buscar mail del cliente
		$cliente = new \Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio();
		$buscaDatosUsuario = $cliente->buscarDatosUsuario($usuarioGuia);
		$datos = $buscaDatosUsuario->current();
		if (! empty($datos->email)){
			$arrayMailsDestino[] = $datos->email;
		}
		$mailsDestino = array_unique($arrayMailsDestino);
		if (count($mailsDestino) > 0){
			$datosCorreo = array(
				'asunto' => Constantes::EMAIL_MNI_ASUNTO,
				'cuerpo' => Constantes::EMAIL_MNI_MENSAJE . "<br>" . $urlArchivo,
				'codigo_modulo' => "PRG_LABORATORIOS",
				'tabla_modulo' => "g_laboratorios.solicitudes",
				'id_solicitud_tabla' => $id,
				'estado' => 'Por enviar');
			$idCorreo = $this->modeloCorreos->guardar($datosCorreo);
			// Guardar correo del destino
			$destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
			foreach ($mailsDestino as $val){
				$datosDestino = array(
					'id_correo' => $idCorreo,
					'destinatario_correo' => $val);
				$destino->guardar($datosDestino);
			}
		}else{
			throw new \Exception(Constantes::EMAIL_INF_VACIO);
		}
	}

	/**
	 * Notificar envío de inorme de análisis
	 *
	 * @param type $id
	 *        	//g_laboratorios.archivo_informe_analisis.id_archivo_informe_analisis
	 * @param type $identificadorCreaSol
	 *        	//identificador que crea la solicitud g_laboratorios.solicitudes.usuario_guia
	 * @param type $urlArchivo
	 * @throws \Exception
	 */
	public function notificarInforme($id, $identificadorCreaSol, $urlArchivo, $destinatarioCorreoCopia, $identificadorUsActivo){
		// Buscar mail de usuario activo
		$empleado = new \Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio();
		$empleado = $empleado->buscar($identificadorUsActivo);
		$arrayMailsDestino = array();
		if (! empty($empleado->getMailInstitucional())){
			$arrayMailsDestino[] = $empleado->getMailInstitucional();
		}else{
			$arrayMailsDestino[] = $empleado->getMailPersonal();
		}
		if (! empty($identificadorCreaSol)){
			// Buscar mail del cliente
			$cliente = new \Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio();
			$buscaDatosUsuario = $cliente->buscarDatosUsuario($identificadorCreaSol);
			$datos = $buscaDatosUsuario->current();
			if (! empty($datos->email)){
				$arrayMailsDestino[] = $datos->email;
			}
		}
		// aumentar mail copias
		$arrayMailsDestino = array_merge($arrayMailsDestino, $destinatarioCorreoCopia);
		$mailsDestino = array_unique($arrayMailsDestino);
		if (count($mailsDestino) > 0){
			$datosCorreo = array(
				'asunto' => Constantes::EMAIL_INF_ASUNTO,
				'cuerpo' => Constantes::EMAIL_INF_MENSAJE . "<br>" . $urlArchivo,
				'codigo_modulo' => "PRG_LABORATORIOS",
				'tabla_modulo' => "g_laboratorios.archivo_informe_analisis",
				'id_solicitud_tabla' => $id,
				'estado' => 'Por enviar');
			$idCorreo = $this->modeloCorreos->guardar($datosCorreo);
			// Guardar correo del destino
			$destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
			foreach ($mailsDestino as $val){
				$datosDestino = array(
					'id_correo' => $idCorreo,
					'destinatario_correo' => $val);
				$destino->guardar($datosDestino);
			}
		}else{
			throw new \Exception(Constantes::EMAIL_INF_VACIO);
		}
	}

	/**
	 * Notificar por correo de forma manual
	 *
	 * @param type $id
	 * @param type $identificador
	 *        	//identificador usuario interno/externo
	 * @param type $asunto
	 * @param type $cuerpo
	 * @throws \Exception
	 */
	public function notificarClienteManual($id, $identificador, $asunto, $cuerpo){
		$cliente = new \Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio();
		$buscaDatosUsuario = $cliente->buscarDatosUsuario($identificador);
		$datos = $buscaDatosUsuario->current();
		if (empty($datos->email)){
			throw new \Exception(Constantes::EMAIL_MNI_VACIO);
		}else{
			$datosCorreo = array(
				'asunto' => $asunto,
				'cuerpo' => $cuerpo,
				'codigo_modulo' => "PRG_LABORATORIOS",
				'tabla_modulo' => "g_laboratorios.solicitudes",
				'id_solicitud_tabla' => $id,
				'estado' => 'Por enviar');
			$idCorreo = $this->modeloCorreos->guardar($datosCorreo);
			// Guardar correo del destino
			$datosDestino = array(
				'id_correo' => $idCorreo,
				'destinatario_correo' => $datos->email);
			$destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
			$destino->guardar($datosDestino);
		}
	}

	/**
	 *
	 * @param type $id
	 * @param type $usuarioGuiaSolPrincipal
	 * @param type $identificadorUsActivo
	 * @throws \Exception
	 */
	public function notificar($id, $usuarioGuiaSolPrincipal, $identificadorUsActivo, $tipoSolicitud){
		switch ($tipoSolicitud) {
			case Constantes::tipo_SO()->DERIVACION:
				$asunto = Constantes::EMAIL_DOT_ASUNTO;
				$cuerpo = Constantes::EMAIL_DOT_MENSAJE;
				$vacio = Constantes::EMAIL_DOT_VACIO;
			break;
			case Constantes::tipo_SO()->CONFIRMACION:
				$asunto = Constantes::EMAIL_CAN_ASUNTO;
				$cuerpo = Constantes::EMAIL_CAN_MENSAJE;
				$vacio = Constantes::EMAIL_CAN_VACIO;
			break;
		}
		// Buscar mail de usuario activo
		$empleado = new \Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio();
		$empleado = $empleado->buscar($identificadorUsActivo);
		$arrayMailsDestino = array();
		if (! empty($empleado->getMailInstitucional())){
			$arrayMailsDestino[] = $empleado->getMailInstitucional();
		}else{
			$arrayMailsDestino[] = $empleado->getMailPersonal();
		}
		// Buscar mail del cliente
		$cliente = new \Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio();
		$buscaDatosUsuario = $cliente->buscarDatosUsuario($usuarioGuiaSolPrincipal);
		$datos = $buscaDatosUsuario->current();
		if (! empty($datos->email)){
			$arrayMailsDestino[] = $datos->email;
		}
		$mailsDestino = array_unique($arrayMailsDestino);
		if (count($mailsDestino) > 0){
			$datosCorreo = array(
				'asunto' => $asunto,
				'cuerpo' => $cuerpo,
				'codigo_modulo' => "PRG_LABORATORIOS",
				'tabla_modulo' => "g_laboratorios.solicitudes",
				'id_solicitud_tabla' => $id,
				'estado' => 'Por enviar');
			$idCorreo = $this->modeloCorreos->guardar($datosCorreo);
			// Guardar correo del destino
			$destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
			foreach ($mailsDestino as $val){
				$datosDestino = array(
					'id_correo' => $idCorreo,
					'destinatario_correo' => $val);
				$destino->guardar($datosDestino);
			}
		}else{
			throw new \Exception($vacio);
		}
	}

	/**
	 *
	 * @param type $id
	 * @param type $usuarioGuiaSolPrincipal
	 * @param type $identificadorUsActivo
	 * @throws \Exception
	 */
	public function notificarClienteDenuncia($idTabla, $correoDenunciante){
		$datosCorreo = array(
			'asunto' => Constantes::EMAIL_CONF_DEN_ASUNTO,
			'cuerpo' => Constantes::EMAIL_DEN_MENSAJE,
			'codigo_modulo' => "PRG_AGR_MOV_EXT",
			'tabla_modulo' => "a_movil_externos.denuncia",
			'id_solicitud_tabla' => $idTabla,
			'estado' => 'Por enviar');

		$idCorreo = $this->modeloCorreos->guardar($datosCorreo);
		// Guardar correo del destino
		$destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
		$datosDestino = array(
			'id_correo' => $idCorreo,
			'destinatario_correo' => $correoDenunciante);
		$destino->guardar($datosDestino);
	}

	/**
	 *
	 * @param type $id
	 * @param type $usuarioGuiaSolPrincipal
	 * @param type $identificadorUsActivo
	 * @throws \Exception
	 */
	public function notificaPlanificacionDenuncia($idTabla, $datosDenuncia, $nombreMotivoDenuncia){

		$datosCorreo = array(
			'asunto' => Constantes::EMAIL_RECP_DEN_ASUNTO,
			'cuerpo' => 'Estimado, </br>
						Se ha receptado una denuncia por medio del aplicativo “AGRO Móvil”:<br/><br/>
						Los datos ingresados son:
						<ul>
							<li><b>Motivo: </b>'.$nombreMotivoDenuncia.'</li>
							<li><b>Descripción: </b>'.$datosDenuncia['descripcion'].'</li>
							<li><b>Lugar: </b>'.$datosDenuncia['lugar'].'</li>
							<li><b>Nombre: </b>'.$datosDenuncia['nombre_denunciante'].'</li>
							<li><b>Correo: </b>'.$datosDenuncia['correo_denunciante'].'</li>
							<li><b>Teléfono: </b>'.$datosDenuncia['telefono'].'</li>
						</ul>',
			'codigo_modulo' => "PRG_AGR_MOV_EXT",
			'tabla_modulo' => "a_movil_externos.denuncia",
			'id_solicitud_tabla' => $idTabla,
			'estado' => 'Por enviar');
		
		$idCorreo = $this->modeloCorreos->guardar($datosCorreo);
		// Guardar correo del destino
		$destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
		$datosDestino = array(
			'id_correo' => $idCorreo,
			'destinatario_correo' =>  Constantes::CORREO_DESTINATARIO_PLANIFICACION);
		$destino->guardar($datosDestino);
		
		if($datosDenuncia['ruta_archivo'] != ''){
			$adjunto = new \Agrodb\Correos\Modelos\DocumentosAdjuntosLogicaNegocio();
			$datosAdjunto = array(
				'id_correo' => $idCorreo,
				'ruta_documento_adjunto' => Constantes::RUTA_SERVIDOR_OPT.'/'.Constantes::RUTA_APLICACION.'/aplicaciones/mvc/'.$datosDenuncia['ruta_archivo']);
			$adjunto->guardar($datosAdjunto);
		}
		
	}
	
	/**
	 *
	 * @param type $id
	 * @param type $usuarioGuiaSolPrincipal
	 * @param type $identificadorUsActivo
	 * @throws \Exception
	 */
	public function notificarClienteordenPago($datosDenuncia){
		$datosCorreo = array(
			'asunto' => Constantes::EMAIL_CONF_REC_ASUNTO,
			'cuerpo' => $datosDenuncia['cuerpo'],
			'codigo_modulo' => "PRG_FACT_OPE",
			'tabla_modulo' => "g_financiero.orden_pago",
			'id_solicitud_tabla' => $datosDenuncia['id_tabla'],
			'estado' => 'Por enviar');

		$idCorreo = $this->modeloCorreos->guardar($datosCorreo);
		// Guardar correo del destino
		$destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
		$datosDestino = array(
			'id_correo' => $idCorreo,
			'destinatario_correo' => $datosDenuncia['correo']);
		$destino->guardar($datosDestino);

		if($datosDenuncia['ruta_archivo'] != ''){
			$adjunto = new \Agrodb\Correos\Modelos\DocumentosAdjuntosLogicaNegocio();
			$datosAdjunto = array(
				'id_correo' => $idCorreo,
				'ruta_documento_adjunto' => Constantes::RUTA_SERVIDOR_OPT.'/'.Constantes::RUTA_APLICACION.'/'.$datosDenuncia['ruta_archivo']);
			$adjunto->guardar($datosAdjunto);
		}
		
	}
	
	/**
	 *
	 * @param type correoDestino
	 * @param type asunto
	 * @param type cuerpo
	 * @param type estado
	 * @param type modulo
	 * @param type tabla
	 * @param type solicitud
	 * @param type ruta_archivo
	 * @throws \Exception
	 */
	public function guardarCorreoDestinatarioAdjunto($datosDenuncia){

		$datosCorreo = array(
			'asunto' => $datosDenuncia['asunto'],
			'cuerpo' => $datosDenuncia['cuerpo'],
			'codigo_modulo' => $datosDenuncia['codigo_modulo'],
			'tabla_modulo' => $datosDenuncia['tabla_modulo'],
			'id_solicitud_tabla' => $datosDenuncia['id_tabla'],
			'estado' => 'Por enviar');

		$idCorreo = $this->modeloCorreos->guardar($datosCorreo);
		// Guardar correo del destino
		$destino = new \Agrodb\Correos\Modelos\DestinatariosLogicaNegocio();
		$datosDestino = array(
			'id_correo' => $idCorreo,
			'destinatario_correo' => $datosDenuncia['correo']);
		$destino->guardar($datosDestino);

		if(isset($datosDenuncia['ruta_archivo'])){
			$adjunto = new \Agrodb\Correos\Modelos\DocumentosAdjuntosLogicaNegocio();
			$datosAdjunto = array(
				'id_correo' => $idCorreo,
				'ruta_documento_adjunto' => Constantes::RUTA_SERVIDOR_OPT.'/'.Constantes::RUTA_APLICACION.'/'.$datosDenuncia['ruta_archivo']);
			$adjunto->guardar($datosAdjunto);
		}

	}
}
