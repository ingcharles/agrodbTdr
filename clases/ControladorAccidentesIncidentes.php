<?php

class ControladorAccidentesIndicentes{
	public function buscarDatosServidor($conexion,$identificador){

		$res = $conexion->ejecutarConsulta("
				SELECT
				identificador,
				nombre,
				apellido,
				fecha_nacimiento,
				edad,
				nacionalidad,
				genero,
				estado_civil,
				tiene_discapacidad,
				domicilio,
				fotografia,
				estado_empleado,
				convencional,
				celular,
				mail_personal,
				mail_institucional,
				id_localizacion_parroquia,
				id_localizacion_provincia,
				id_localizacion_canton,
				tiene_enfermedad_catastrofica
				FROM
				g_uath.ficha_empleado
				where
				identificador='$identificador';");
		return $res;
	}

	public function obtenerNombreLocalizacion($conexion, $idLocalizacion){

		$res = $conexion->ejecutarConsulta("
				SELECT
				nombre
				FROM
				g_catalogos.localizacion
				WHERE
				id_localizacion = $idLocalizacion;");
		return $res;
	}
	public function obtenerNombrePuesto($conexion, $identificador){

		$res = $conexion->ejecutarConsulta("select
				nombre_puesto
				from
				g_uath.datos_contrato dc
				where
				dc.identificador = '$identificador' and
				dc.estado = 1;");

		return $res;
	}
	public function actualizarDatosFichaEmpleado($conexion, $identificador, $edad){
		$sql="UPDATE
		g_uath.ficha_empleado
		SET
		edad=$edad
		WHERE identificador='$identificador';";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	public function guardarNuevoRegistroSso($conexion, $identificadorAccidente,$escolaridad,$profesion,$horarioTrabajo,
			$tiempoPuesto,$idAreaPadre,$tipoSso,$identificadorRegistro){
		$sql="INSERT INTO
		g_investigacion_accidente_incidente.datos_accidente(
		identificador_accidentado,
		escolaridad,
		profesion,
		horario_trabajo,
		tiempo_puesto,
		id_area_padre,
		tipo_sso,
		fecha_creacion,
		identificador_registro)
		VALUES (
		'$identificadorAccidente',
		'$escolaridad',
		'$profesion',
		'$horarioTrabajo',
		'$tiempoPuesto',
		'$idAreaPadre',
		'$tipoSso',
		now(),
		'$identificadorRegistro') RETURNING cod_datos_accidente;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	public function guardarInformeAccidente($conexion, $codRegistro,$dia,$fechaAccidente,$horaAccidente,$tipoAccidente,
			$lugarAccidente,$direccion,$referencia,$idProvincia,$idCiudad,$idParroquia){
		$sql="INSERT INTO
		g_investigacion_accidente_incidente.registro_accidente(
		cod_datos_accidente,
		dia,
		fecha_accidente,
		hora_accidente,
		tipo_accidente,
		lugar_accidente,
		direccion,
		referencia,
		id_localizacion_provincia,
		id_localizacion_ciudad,
		id_localizacion_parroquia)
		VALUES (
		$codRegistro,
		'$dia',
		'$fechaAccidente',
		'$horaAccidente',
		'$tipoAccidente',
		'$lugarAccidente',
		'$direccion',
		'$referencia',
		$idProvincia,
		$idCiudad,
		$idParroquia);";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	public function guardarCircunstanciasAccidentes($conexion, $codRegistro,$describirAccidente,$trabajoHabitual,$accidenteTrabajo,$partesLesionadas,
			$personaAtendio,$trasladoAccidente,$nombreTestigo,$direccionTestigo,$telefonoTestigo){
		$sql="INSERT INTO
		g_investigacion_accidente_incidente.circunstancias_accidente(
		codigo_datos_accidente,	describir_accidentado,
		trabajo_habitual, accidente_trabajo,
		partes_lesionadas, persona_atendio,
		traslado_accidentado, nombre_testigo,
		direccion_testigo,telefono_testigo)
		VALUES (
		$codRegistro,
		'$describirAccidente',
		'$trabajoHabitual',
		'$accidenteTrabajo',
		'$partesLesionadas',
		'$personaAtendio',
		'$trasladoAccidente',
		'$nombreTestigo',
		'$direccionTestigo',
		'$telefonoTestigo');";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	public function guardarFichaMedica($conexion, $codRegistro,$lugarAccidente,$fechaAtencion,$horaAtencion,$sintomas,
			$otrosDatos,$descripcionLesiones,$trasladoCentroSalud,$nombreMedico,$fechaReposoDesde,$fechaReposoHasta){
		
		$fecha='';
		$fecha2='';
		
		if($fechaReposoDesde !='' ){
			$fecha=",'$fechaReposoDesde','$fechaReposoHasta'";
			$fecha2=",reposo_desde,reposo_hasta";
		}
		if($fechaAtencion !='' ){
			$fecha.= ",'$fechaAtencion'";
			$fecha2.= ",fecha_atencion";
		}
		if($horaAtencion !='' ){
			$fecha.= ",'$horaAtencion'";
			$fecha2.= ",hora_atencion";
		}
		
	$sql="INSERT INTO
			g_investigacion_accidente_incidente.ficha_medica(
			codigo_datos_accidente,lugar_atencion,sintomas,otros_datos,descripcion_lesiones,traslado_centro_salud,
				nombre_medico
				$fecha2
				)
			VALUES ($codRegistro,'$lugarAccidente','$sintomas','$otrosDatos','$descripcionLesiones',
				'$trasladoCentroSalud','$nombreMedico'
				$fecha);";
		
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	public function listarDatosAccidente($conexion,$identificador_registro=NULL, $estado = NULL,$id_area_padre=NULL,
			$tipo_Sso=NULL, $prioridad=NULL,$codRegistro=NULL, $identificadorAccidentado=NULL,$fecha_creacion=NULL,$estadoDos=NULL){

		$identificador_registro = $identificador_registro!="" ? "'" . $identificador_registro . "'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
		$estadoDos = $estadoDos!="" ? "'" . $estadoDos . "'" : "null";
		$id_area_padre = $id_area_padre!="" ? "'" . $id_area_padre . "'" : "null";
		$tipo_Sso = $tipo_Sso!="" ? "'" . $tipo_Sso . "'" : "null";
		$prioridad = $prioridad!="" ? "'" . $prioridad . "'" : "null";
		$codRegistro=$codRegistro!="" ? "'".$codRegistro. "'" : "null";
		$identificadorAccidentado=$identificadorAccidentado!="" ? "'".$identificadorAccidentado. "'" : "null";
		$fecha_creacion=$fecha_creacion!="" ? "'".$fecha_creacion. "'" : "null";
		$estado=strtoupper($estado);
		$estadoDos=strtoupper($estadoDos);
	 $sql="
				SELECT
					*
				FROM
				g_investigacion_accidente_incidente.datos_accidente
				where
				($identificador_registro is NULL or  identificador_registro = $identificador_registro) and
				($estado is NULL or  UPPER(estado) in ($estado) ) and
				($id_area_padre is NULL or  id_area_padre in ($id_area_padre)) and
				($tipo_Sso is NULL or  tipo_sso = $tipo_Sso) and
				($prioridad is NULL or  prioridad = $prioridad) and
				($identificadorAccidentado is NULL or  identificador_accidentado = $identificadorAccidentado) and
				($fecha_creacion is NULL or  fecha_creacion::timestamp::date = $fecha_creacion) and
				($codRegistro is NULL or  cod_datos_accidente = $codRegistro) order by 1;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;

	}
	public function buscarRegistroAccidente($conexion,$idResgistro){
		$res = $conexion->ejecutarConsulta("
				SELECT
				*
				FROM
				g_investigacion_accidente_incidente.registro_accidente
				where
				cod_datos_accidente=$idResgistro;");
		return $res;
	}
	public function buscarCircunstanciasAccidente($conexion,$idResgistro){
		$res = $conexion->ejecutarConsulta("
				SELECT
				*
				FROM
				g_investigacion_accidente_incidente.circunstancias_accidente
				where
				codigo_datos_accidente=$idResgistro;");
		return $res;
	}
	public function buscarFichaAccidente($conexion,$idResgistro){
		$res = $conexion->ejecutarConsulta("
				SELECT
				*
				FROM
				g_investigacion_accidente_incidente.ficha_medica
				where
				codigo_datos_accidente=$idResgistro;");
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------
	public function actualizarRegistroSso($conexion,$idResgistro,$resultado,$observacion,$prioridad){

		//if($observacion=='')$insertar='';
		//else $insertar=",observacion='$observacion'";

		$res = $conexion->ejecutarConsulta("
				UPDATE
				g_investigacion_accidente_incidente.datos_accidente
				SET
				estado='$resultado',
				fecha_modificacion=now(),
				prioridad=$prioridad,
				observacion='$observacion'
				WHERE cod_datos_accidente=$idResgistro;");
		
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------
	public function guardarCitaMedica($conexion,$idResgistro,$fechaCita,$horaCita,$nombreMedico,$direccionMedico,$rutaArchivo,$mailAccidentado){

		$res = $conexion->ejecutarConsulta("
				INSERT INTO
				g_investigacion_accidente_incidente.cita_medica(
				codigo_datos_accidente,
				fecha_cita,
				hora_cita,
				nombre_medico,
				direccion_medico,
				archivo_aviso_accidente,
				fecha_creacion,
				mail_accidentado)
				VALUES (
				$idResgistro,
				'$fechaCita',
				'$horaCita',
				'$nombreMedico',
				'$direccionMedico',
				'$rutaArchivo',
				now(),
				'$mailAccidentado');
				");
		return $res;
	}
	public function buscarCitaMedica($conexion,$idResgistro){
		$res = $conexion->ejecutarConsulta("
				SELECT
				*
				FROM
				g_investigacion_accidente_incidente.cita_medica
				WHERE codigo_datos_accidente=$idResgistro;");
		return $res;
	}
	public function actualizarCitaMedica($conexion,$idResgistro,$fechaCita,$horaCita,$nombreMedico,$direccionMedico,$rutaArchivo){

		$res = $conexion->ejecutarConsulta("
				UPDATE
				g_investigacion_accidente_incidente.cita_medica
				SET
				fecha_cita='$fechaCita',
				nombre_medico='$nombreMedico',
				direccion_medico='$direccionMedico',
				archivo_aviso_accidente='$rutaArchivo',
				hora_cita='$horaCita',
				fecha_modificaion=now()
				WHERE codigo_datos_accidente=$idResgistro;");
		return $res;
	}
	public function guardarCierreCaso($conexion,$idResgistro,$docUnidadIess,$docCertificadoMedico,$responPatron){

		$res = $conexion->ejecutarConsulta("
				UPDATE 
					g_investigacion_accidente_incidente.cierre_caso
   				SET 
					archivo_unidad_riesgos_iess='$docUnidadIess', 
       				archivo_certificado_medico='$docCertificadoMedico', 
       				responsabilidad='$responPatron',	
					fecha_modificacion=now()
 				WHERE codigo_datos_accidente=$idResgistro;");
		return $res;
	}
	
	public function guardarDocumentosHabilitantes($conexion,$idResgistro,$cedulaPapeleta,$cedulaPapeletaRep,$infoReporte){
	
		$res = $conexion->ejecutarConsulta("
				INSERT INTO
				g_investigacion_accidente_incidente.cierre_caso(
				codigo_datos_accidente,
				archivo_cedula_papeleta_accidentado,
				archivo_cedula_papeleta_reporta,
				archivo_informe_reporte,
				fecha_creacion)
				VALUES (
				$idResgistro,
				'$cedulaPapeleta',
				'$cedulaPapeletaRep',
				'$infoReporte',
				now());
				");
				return $res;
	}
	
	
	
	public function actualizarCierreCaso($conexion,$idResgistro,$docUnidadIess,$docCertificadoMedico,$responPatron){

		$res = $conexion->ejecutarConsulta("
				UPDATE
					g_investigacion_accidente_incidente.cierre_caso
				SET
					archivo_unidad_riesgos_iess='$docUnidadIess',
					archivo_certificado_medico='$docCertificadoMedico',
					responsabilidad='$responPatron',
					fecha_modificacion=now()
				WHERE 
					codigo_datos_accidente=$idResgistro;
				");
		return $res;
	}
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	public function actualizarDocumentosHabilitantes($conexion,$idResgistro,$cedulaPapeleta,$cedulaPapeletaRep,$infoReporte){
	
		$res = $conexion->ejecutarConsulta("
				UPDATE
					g_investigacion_accidente_incidente.cierre_caso
				SET
					archivo_cedula_papeleta_accidentado='$cedulaPapeleta',
					archivo_cedula_papeleta_reporta='$cedulaPapeletaRep',
					archivo_informe_reporte='$infoReporte',
					fecha_modificacion=now()
				WHERE 
					codigo_datos_accidente=$idResgistro;
				");
		return $res;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	public function buscarCierreCaso($conexion,$idResgistro){

		$res = $conexion->ejecutarConsulta("
				SELECT
				*
				FROM
				g_investigacion_accidente_incidente.cierre_caso
				where codigo_datos_accidente=$idResgistro;
				");
		return $res;
	}
	public function modificarNuevoRegistroSso($conexion,$idSolicitud,$escolaridad,$profesion,$horarioTrabajo,
			$tiempoPuesto,$tipoSso){
		$sql="
		UPDATE
		g_investigacion_accidente_incidente.datos_accidente
		SET
		escolaridad='$escolaridad',
		profesion='$profesion',
		horario_trabajo='$horarioTrabajo',
		tiempo_puesto='$tiempoPuesto',
		estado='Subsanado',
		tipo_sso='$tipoSso',
		fecha_modificacion=now(),
		prioridad=1
		WHERE
		cod_datos_accidente=$idSolicitud;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	public function modificarInformeAccidente($conexion, $codRegistro,$dia,$fechaAccidente,$horaAccidente,$tipoAccidente,
			$lugarAccidente,$direccion,$referencia,$idProvincia,$idCiudad,$idParroquia){
		$sql="UPDATE
		g_investigacion_accidente_incidente.registro_accidente
		SET
		fecha_accidente='$fechaAccidente',
		tipo_accidente='$tipoAccidente',
		lugar_accidente='$lugarAccidente',
		direccion='$direccion',
		referencia='$referencia',
		id_localizacion_provincia=$idProvincia,
		id_localizacion_ciudad=$idCiudad,
		id_localizacion_parroquia=$idParroquia,
		dia='$dia',
		hora_accidente='$horaAccidente'
		WHERE
		cod_datos_accidente=$codRegistro;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	public function modificarCircunstanciasAccidentes($conexion, $codRegistro,$describirAccidente,$trabajoHabitual,$accidenteTrabajo,$partesLesionadas,
			$personaAtendio,$trasladoAccidente,$nombreTestigo,$direccionTestigo,$telefonoTestigo){
		$sql="UPDATE
		g_investigacion_accidente_incidente.circunstancias_accidente
		SET
		describir_accidentado='$describirAccidente',
		trabajo_habitual='$trabajoHabitual',
		accidente_trabajo='$accidenteTrabajo',
		partes_lesionadas='$partesLesionadas',
		persona_atendio='$personaAtendio',
		traslado_accidentado='$trasladoAccidente',
		nombre_testigo='$nombreTestigo',
		direccion_testigo='$direccionTestigo',
		telefono_testigo='$telefonoTestigo'
		WHERE
		codigo_datos_accidente=$codRegistro;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	public function modificarFichaMedica($conexion, $codRegistro,$lugarAccidente,$fechaAtencion,$horaAtencion,$sintomas,
			$otrosDatos,$descripcionLesiones,$trasladoCentroSalud,$nombreMedico,$fechaReposoDesde,$fechaReposoHasta){
		$fecha='';
		if($fechaReposoDesde !='' )
			$fecha=",reposo_desde = '$fechaReposoDesde',reposo_hasta = '$fechaReposoHasta'";
		if($fechaAtencion !='' )
			$fecha.= ",fecha_atencion='$fechaAtencion'";
		if($horaAtencion !='' )
			$fecha.= ",hora_atencion='$horaAtencion'";
			
	$sql="
		UPDATE
				g_investigacion_accidente_incidente.ficha_medica
				SET
				lugar_atencion='$lugarAccidente',
				sintomas='$sintomas',
				otros_datos='$otrosDatos',
				descripcion_lesiones='$descripcionLesiones',
				traslado_centro_salud='$trasladoCentroSalud',
				nombre_medico ='$nombreMedico'
				$fecha 
		WHERE
				codigo_datos_accidente=$codRegistro;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//---------------------------------------------------------------------------------------------
	public function verificarDatosFichaMedica($conexion, $codAccidente){
		
		$sql="SELECT 
					codigo_datos_accidente
  			  FROM 
					g_investigacion_accidente_incidente.ficha_medica
			  WHERE codigo_datos_accidente=$codAccidente;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
		
	}
	//----------------------------------------------------------------------------------------------
	public function guardarMail($conexion,$identificador,$codDatosAccidente){
		
		$sql="
		INSERT INTO 
				g_investigacion_accidente_incidente.mail_sso( identificador, cod_datos_accidente)
    	VALUES ('$identificador', $codDatosAccidente);";
		
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//----------------------------------------------------------------------------------------------
	public function listarMailEnvio($conexion, $estado){
	
		$res = $conexion->ejecutarConsulta("
				SELECT 
						distinct cod_datos_accidente,
						identificador, 
						estado, 
						fecha_cita,
						hora_cita,
						direccion_medico,
						nombre_medico
 				FROM 
						g_investigacion_accidente_incidente.mail_sso ma,
						g_investigacion_accidente_incidente.cita_medica ct
				WHERE 
						ma.estado=$estado and 
						ma.cod_datos_accidente = ct.codigo_datos_accidente
				limit 5;
				");
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------
	public function actualizarEstadoMail($conexion,$idResgistro,$estadoMail,$mailDestino,$estado){
	
		$res = $conexion->ejecutarConsulta("
				UPDATE 
					g_investigacion_accidente_incidente.mail_sso
   				SET 
					estado=$estado,
					estado_mail='$estadoMail',
					mail_destinatario='$mailDestino'
 				WHERE 
					cod_datos_accidente=$idResgistro;
				");
		return $res;
	}
	//------------------------------------------------------------------------------------------------------------
	public function actualizarArchivoFichaSso($conexion,$idResgistro,$archivoFichaSso){
		$sql="
			UPDATE 
					g_investigacion_accidente_incidente.cierre_caso
			SET 
			    	archivo_ficha_accidente_incidente='$archivoFichaSso'
			WHERE 
					codigo_datos_accidente = $idResgistro ;";
		
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//------------------------------------------------------------------------------------------------------------
	public function reporteAccidenteIncidente($conexion,$zona=NULL,$identificador=NULL,$estado=NULL,$fechaDesde=NULL, $fechaHasta=NULL){
		if($fechaDesde !='' and $fechaHasta != '')$ban=1;
		$zona = $zona!="" ? "'" . $zona . "'" : "null";
		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
		$fechaDesde = $fechaDesde!="" ? "'" . $fechaDesde . "'" : "null";
		$fechaHasta = $fechaHasta!="" ? "'" . $fechaHasta . "'" : "null";
		if($ban)
			$consul='('.$fechaDesde.' is NULL or  (ra.fecha_accidente between '.$fechaDesde.' and '.$fechaHasta.')) and';
		else 
			$consul='('.$fechaDesde.' is NULL or  ra.fecha_accidente >= '.$fechaDesde.') and ('.$fechaHasta.' is NULL or  ra.fecha_accidente <= '.$fechaHasta.') and';
		
	 $sql="SELECT 
			       da.cod_datos_accidente, 
			       da.estado, 
			       ra.fecha_accidente,
			       da.tipo_sso,
			       da.id_area_padre,
			       (Select nombre From g_estructura.area WHERE id_area=da.id_area_padre) as nombrearea,
			       (Select nombre FROM g_catalogos.localizacion WHERE id_localizacion=ra.id_localizacion_provincia) as provincia,
			       (Select nombre FROM g_catalogos.localizacion WHERE id_localizacion=ra.id_localizacion_ciudad) as ciudad,
			       fe.genero,
			       da.identificador_accidentado, 
			       fe.nombre ||' '|| fe.apellido as funcionario,
			       fe.edad,
			       ra.lugar_accidente,
			       ra.direccion,
			       ca.describir_accidentado,
			       fm.descripcion_lesiones,
			       fm.reposo_desde,
			       fm.reposo_hasta 
			
			  FROM 
				g_investigacion_accidente_incidente.datos_accidente da,
				g_investigacion_accidente_incidente.registro_accidente ra,
				g_investigacion_accidente_incidente.circunstancias_accidente ca,
				g_investigacion_accidente_incidente.ficha_medica fm,
				g_uath.ficha_empleado fe
			  WHERE 
				($zona is NULL or  da.id_area_padre = $zona) and
				($identificador is NULL or  da.identificador_accidentado = $identificador) and
				($estado is NULL or  da.estado in ($estado)) and
				$consul	
				da.cod_datos_accidente = ra.cod_datos_accidente and 
				da.cod_datos_accidente = ca.codigo_datos_accidente and
				da.cod_datos_accidente = fm.codigo_datos_accidente and
				da.identificador_accidentado = fe.identificador;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//------------------------------------------------------------------------------------------------------------
	
	public function guardarArchivoFichaSso($conexion,$idResgistro,$archivoFichaSso){
	 $sql="
		INSERT INTO g_investigacion_accidente_incidente.cierre_caso(
            codigo_datos_accidente, archivo_ficha_accidente_incidente)
    	VALUES (
    		$idResgistro, 
    		'$archivoFichaSso');";
	
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	//------------------------------------------------------------------------------------------------------------
}
?>
