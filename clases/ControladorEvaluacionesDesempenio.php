<?php

class ControladorEvaluacionesDesempenio{
	public function listaEvaluaciones ($conexion, $estado){
		switch ($estado){
			case 'ABIERTOS': $estado = 'WHERE estado = 1 '; break;
			case 'TODOS': $estado = ''; break;
			case 'CERRADOS': $estado = "WHERE estado = 0 and vigencia='finalizado'"; break;
			case 'EXCEPCION': $estado = "WHERE vigencia='excepciones'"; break;
			case 'RESULTADOS': $estado = "WHERE vigencia in ('excepciones','finalizado')"; break;
			case 'FINAL': $estado = "WHERE vigencia in ('cerrado','excepciones')"; break;
		}
		
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_evaluacion_desempenio.evaluaciones 
											$estado order by 1;");
		return $res;
	}
//----------------------------------------------------------------------------------------------------------------------------
    public function devolverEvaluacionActiva($conexion){
    	
    	$res = $conexion->ejecutarConsulta("SELECT
    			 vigencia
    			FROM
    			g_evaluacion_desempenio.evaluaciones
    			WHERE estado = 1 and vigencia in ('activo','proceso','excepciones','finalizado');");
    	return $res;
    	
    }
	
	
//----------------------------------------------------------------------------------------------------------------------------
	public function guardarEvaluacion ($conexion, $nombre, $identificador, $codigo,$objetivo,$codParametro,$estadoCatastro){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
													g_evaluacion_desempenio.evaluaciones(nombre, codigo, creado_por, fecha_creacion, estado, objetivo, cod_parametro, estado_catastro)
											VALUES 
													('$nombre','$codigo','$identificador',now(),1,'$objetivo',$codParametro,'$estadoCatastro')
											RETURNING 
												    id_evaluacion;");
				return $res;
	}
	
	public function buscarCodigoEvaluacion ($conexion){
	
	
		$res = $conexion->ejecutarConsulta("SELECT
												max(id_evaluacion)+1 as numero
											FROM
												g_evaluacion_desempenio.evaluaciones;");
				return $res;
	}
	
	public function abrirEvaluacion ($conexion, $idEvaluacion, $estado){
		
		switch ($estado){
			case 'ABIERTOS': $estado = 1; break;
		}
	
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_evaluacion_desempenio.evaluaciones
											WHERE 
												id_evaluacion = $idEvaluacion
												and estado = $estado;");
		return $res;
	}
	
	
	public function abrirTipoEvaluacion ($conexion, $idEvaluacion, $tipo){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_evaluacion_desempenio.tipos_evaluaciones tp
											WHERE
												id_tipo = $idEvaluacion
												and tipo = '$tipo'
												and estado = 1;");
		return $res;
	}
	
	public function guardarAplicantes ($conexion, $identificadorEvaluador, $identificadorEvaluado, $idTipoEvaluacion, $estado, $tipo, $idEvaluacion){
		//$this->verificarAplicanteRegistrado($conexion, $responsable, $responsable,$autoevaluacion ['id_tipo_evaluacion'], $autoevaluacion ['tipo']);
		if(pg_num_rows($this->verificarAplicanteRegistrado($conexion, $identificadorEvaluador, $identificadorEvaluado,$idTipoEvaluacion, $tipo,$idEvaluacion)) == 0)
		if($identificadorEvaluador!=0 and $identificadorEvaluado!=0){
		$sql="INSERT INTO 
						g_evaluacion_desempenio.aplicantes
						(identificador_evaluador, identificador_evaluado, id_tipo_evaluacion, estado, tipo, id_evaluacion) 
				 VALUES ('$identificadorEvaluador','$identificadorEvaluado',$idTipoEvaluacion,'$estado','$tipo',$idEvaluacion);";			
		$res = $conexion->ejecutarConsultaLOGS($sql);
		return $res; }
	}
	
	
	public function guardarAplicantesNoAsignados ($conexion, $identificadorEvaluador,$idEvaluacion){
	
			
			$sql="INSERT INTO
						g_evaluacion_desempenio.aplicantes_no_asignados(identificador_evaluador, id_evaluacion)
				VALUES ('$identificadorEvaluador',$idEvaluacion);";
			
			$res = $conexion->ejecutarConsulta($sql);
			return $res; 
	}
	
	public function abrirEvaluacionDisponibleUsuario ($conexion, $identificador, $vigencia=NULL, $idEvaluacion=NULL,$estadoExcepcion=NULL){
		$vigencia = $vigencia != "" ? "'" .  $vigencia  . "'" : "NULL";
		$idEvaluacion = $idEvaluacion != "" ? "'" .  $idEvaluacion  . "'" : "NULL";
		$estadoExcepcion = $estadoExcepcion != "" ? "'" .  $estadoExcepcion  . "'" : "NULL";
		
		$res = $conexion->ejecutarConsulta("SELECT
												distinct(a.*),
												g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
											FROM
												g_evaluacion_desempenio.aplicantes a,
												g_evaluacion_desempenio.tipos_evaluaciones te,
												g_evaluacion_desempenio.evaluaciones e,
												g_uath.ficha_empleado fe,
												g_evaluacion_desempenio.tb_evaluacion_tipos tip
											WHERE
												identificador_evaluador = '$identificador' and
												a.id_tipo_evaluacion = te.id_tipo_evaluacion and
												tip.id_evaluacion = e.id_evaluacion and
												a.id_evaluacion= e.id_evaluacion and
												a.estado = true and
												te.estado = 1 and
												e.estado = 1 and
												($vigencia is NULL or  a.vigencia = $vigencia) and
												($idEvaluacion is NULL or  a.id_evaluacion = $idEvaluacion) and
												($estadoExcepcion is NULL or  a.estado_excepcion = $estadoExcepcion) and
												fe.identificador = a.identificador_evaluado;");
		return $res;
		
	}
//-----------------------------------------------------------------------------------------------------------------------	
	public function abrirAplicante ($conexion, $idAplicante){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_evaluacion_desempenio.aplicantes
											WHERE
												id_aplicante = $idAplicante;");
		return $res;
	}
//-----------------------------------------------------------------------------------------------------------------------	
	public function obtenerPreguntas($conexion, $idTipoEvaluacion){

		$res = $conexion->ejecutarConsulta("select
												p.id_pregunta,
												p.id_tipo_evaluacion,
												p.tipo_pregunta,
												p.descripcion,
												t.nombre,
												t.objetivo
											from
												g_evaluacion_desempenio.preguntas p,
												g_evaluacion_desempenio.tipos_evaluaciones t
											where
												p.id_tipo_evaluacion = t.id_tipo_evaluacion and
												p.id_tipo_evaluacion = $idTipoEvaluacion
											order by 
												1,3;");
				return $res;
	}
//-----------------------------------------------------------------------------------------------------------------------	
	public function obtenerOpciones($conexion, $idTipoEvaluacion){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_evaluacion_desempenio.opciones
											where
												id_tipo_evaluacion = $idTipoEvaluacion
											order by
												id_pregunta,ponderacion");
				return $res;
	}
//-----------------------------------------------------------------------------------------------------------------------	
	public function obtenerTiposPreguntasEvaluacion($conexion, $idTipoEvaluacion){
		$res = $conexion->ejecutarConsulta("select 
												tipo_pregunta as tipo,
												orden
											from 
												g_evaluacion_desempenio.preguntas	
											 where
												id_tipo_evaluacion = $idTipoEvaluacion
											group by 
												tipo_pregunta, orden
											order by 
												orden;");
				return $res;
	}
//-----------------------------------------------------------------------------------------------------------------------	
	public function grabarRespuestas($conexion, $sentencia){
		$res = $conexion->ejecutarConsulta(rtrim($sentencia, ","));
		return $res;
	}
	
	public function quitarEvaluacionDisponible($conexion, $idAplicante, $idTipoEvaluacion){
		$res = $conexion->ejecutarConsulta("update
												g_evaluacion_desempenio.aplicantes
											set
												estado = false,
												fecha_fin = now()
											where
												id_aplicante = $idAplicante and
												id_tipo_evaluacion = $idTipoEvaluacion;");
	
				//$this->actualizarNotificacion($conexion, $identificador);
	
				return $res;
	}
//-----------------------------------------------------------------------------------------------------------------------	
	public function actualizarAplicante($conexion, $idAplicante, $idTipoEvaluacion){
		$res = $conexion->ejecutarConsulta("update
												g_evaluacion_desempenio.aplicantes
											set
												fecha_inicio = now()
											where
												id_aplicante = $idAplicante and
												id_tipo_evaluacion = $idTipoEvaluacion;");
	
		//$this->actualizarNotificacion($conexion, $identificador);
	
		return $res;
	}
	
	public function actualizarNotificacion($conexion, $identificador,$numero){
		
		$res = $conexion->ejecutarConsulta("select
												g_programas.actualizarnotificaciones($numero,'$identificador',(SELECT
																												a.id_aplicacion
																											FROM
																												g_programas.aplicaciones a
																											WHERE
																												a.codificacion_aplicacion='PRG_EVADESEMPENO'));");
				return $res;
	}
	
	public function obtenerResultadoFuncionario($conexion, $identificador, $tipoEvaluacion, $variacion=0,$idEvaluacion=NULL){
	
		$res = $conexion->ejecutarConsulta("select 
											(SUM(ponderacion) *(select  (ponderacion+$variacion)/100::decimal from  g_evaluacion_desempenio.tipos_evaluaciones where id_tipo_evaluacion = $tipoEvaluacion))/
											(count(id_respuesta)*(select  max(ponderacion) from  g_evaluacion_desempenio.opciones where id_tipo_evaluacion = $tipoEvaluacion)) as valor
										from 
											g_evaluacion_desempenio.respuestas
										where
											identificador_evaluado = '$identificador'
											and id_tipo_evaluacion = $tipoEvaluacion and 
											id_evaluacion=$idEvaluacion;");
				return $res;
	}
	
	public function guardarEvaluacionCumplimineto ($conexion, $idArea, $valorGpr, $valorPresupuesto, $total, $idEvaluacion){
							
		$res = $conexion->ejecutarConsulta("INSERT INTO g_evaluacion_desempenio.valores_gpr_presupuesto(id_area, valor_gpr, valor_presupuesto, valor_total,id_evaluacion)
															VALUES ('$idArea',$valorGpr,$valorPresupuesto,$total,$idEvaluacion);");
		return $res;
	}
	
	public function eliminarEvaluacionCumplimineto ($conexion, $idEvaluacion){
			
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_evaluacion_desempenio.valores_gpr_presupuesto
											WHERE 
												id_evaluacion = '$idEvaluacion';");
		return $res;
	}
	
	public function buscarEvalucionCumplimientoArea ($conexion, $idArea, $idEvaluacion){
					
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM
												g_evaluacion_desempenio.valores_gpr_papp
											WHERE
												id_area = '$idArea'
												and id_evaluacion = $idEvaluacion;");
		return $res;
	}
	
	public function guardarResultadoEvaluacion ($conexion, $identificador,$idArea, $superior, $inferior, $par, $autoevaluacion,$areaCumplimiento,$idEvaluacion,$nombre, $resultadoIndividual){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO g_evaluacion_desempenio.resultados_evaluacion(identificador, id_area, resultado_superior, resultado_inferior, resultado_par, resultado_autoevaluacion,id_area_cumplimiento, id_evaluacion, nombre, resultado_individual)
														VALUES ('$identificador','$idArea',$superior,$inferior,$par,$autoevaluacion,'$areaCumplimiento',$idEvaluacion, '$nombre', $resultadoIndividual);");
		return $res;
	}
	
	public function crearRegistroResultadoEvaluacion ($conexion, $identificador,$idArea, $idEvaluacion,$nombre,$areaCumplimiento ){
		
		if(pg_num_rows($this->verificarResultadoEvaluacion($conexion, $identificador, $idEvaluacion))==0){
			$this->actualizarNotificacion($conexion, $identificador,1);
			$res = $conexion->ejecutarConsulta("INSERT INTO g_evaluacion_desempenio.resultados_evaluacion(identificador, id_area, id_evaluacion, nombre, id_area_cumplimiento)
					VALUES ('$identificador','$idArea',$idEvaluacion, '$nombre', '$areaCumplimiento');");
		return $res;
		}
	}
	
	
	public function actualizarResultadoCumplimiento ($conexion,$idArea,$idEvaluacion,$valorCumplimiento){
						
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_evaluacion_desempenio.resultados_evaluacion 
											SET 
												resultado_cumplimiento = $valorCumplimiento
											WHERE
												id_evaluacion = $idEvaluacion and
												id_area_cumplimiento = '$idArea';");
		return $res;
	}
	
	//---------------------------------------------------------------------------------------------------------
	public function actualizarResultadoCumplim ($conexion,$identificador,$idEvaluacion,$valorIndividual){
	
		$res = $conexion->ejecutarConsulta("UPDATE
				g_evaluacion_desempenio.resultados_evaluacion
				SET
				resultado_individual = $valorIndividual
				WHERE
				id_evaluacion = $idEvaluacion and
				identificador = '$identificador';");
				return $res;
	}
	
	
	public function listaResultadoEvaluacion ($conexion, $idEvaluacion){
		
		$res = $conexion->ejecutarConsulta("SELECT
												re.*,
												fe.apellido || ' '||fe.nombre as nombres,
												a.nombre as nombre_area
											FROM
												g_evaluacion_desempenio.resultados_evaluacion re,
												g_uath.ficha_empleado fe,
												g_estructura.area a
											WHERE 
												re.identificador = fe.identificador and
												re.id_area = a.id_area and
												id_evaluacion  = $idEvaluacion
												order by nombre_area;");
				return $res;
	}
	
	public function listaResultadoEvaluacionNotas($conexion, $idEvaluacion,$provincia){
		$provincia = $provincia != "" ? "'" .  $provincia  . "'" : "NULL";
		
		$res = $conexion->ejecutarConsulta("SELECT
				re.*,
				fe.apellido || ' '||fe.nombre as nombres,
				a.nombre as nombre_area, provincia
				FROM
				g_evaluacion_desempenio.resultados_evaluacion re,
				g_uath.ficha_empleado fe,
				g_estructura.area a,
				g_uath.datos_contrato c
				WHERE
				re.identificador = fe.identificador and
				re.id_area = a.id_area and
				id_evaluacion  = $idEvaluacion and
				c.estado= 1 and
				($provincia is NULL or  provincia = $provincia) and
				c.identificador= fe.identificador 
				order by nombre_area;");
				return $res;
	}
	
	public function verificarResultadosEvaluacion ($conexion,$idEvaluacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(*) as valor
											FROM
												g_evaluacion_desempenio.resultados_evaluacion
											WHERE
												id_evaluacion = $idEvaluacion;");
				return $res;
	}
	
	public function verificarCumplimientoEvaluacion ($conexion,$idEvaluacion){
		$res = $conexion->ejecutarConsulta("SELECT
												count(*) as valor
											FROM
												g_evaluacion_desempenio.valores_gpr_presupuesto
											WHERE
												id_evaluacion = $idEvaluacion;");
		return $res;
	}
	
	public function listarValoresGprPresupuesto($conexion,$idEvaluacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_evaluacion_desempenio.valores_gpr_presupuesto
											WHERE
												id_evaluacion = $idEvaluacion;");
		return $res;
	}
	
	
	public function listarTipoEvaluacion ($conexion, $idEvaluacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_evaluacion_desempenio.tipos_evaluaciones
											WHERE
												id_tipo = $idEvaluacion
											order by 1;");
		return $res;
	}
	
	public function listarResultadoEvaluacionIndividual ($conexion, $idTipoEvaluacion,$idEvaluacion, $identificador, $ponderacionSuperior=0,$ponderacioInferior=0,$ponderacionPares=0){
		
		$ponderacionSuperior = $ponderacionSuperior!="" ? "'" . $ponderacionSuperior . "'" : "0";
		$ponderacioInferior = $ponderacioInferior!="" ? "'" . $ponderacioInferior . "'" : "0";
		$ponderacionPares = $ponderacionPares!="" ? "'" . $ponderacionPares . "'" : "0";

		$res = $conexion->ejecutarConsulta("SELECT 
												identificador,
												nombre,
												((resultado_superior)*10000)/(SELECT ponderacion+$ponderacionSuperior FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE id_tipo_evaluacion = (SELECT id_tipo_evaluacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE tipo = 'superior' and id_tipo = $idTipoEvaluacion)) as superior,
												((resultado_inferior)*10000)/(SELECT ponderacion+$ponderacioInferior FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE id_tipo_evaluacion = (SELECT id_tipo_evaluacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE tipo = 'inferior' and id_tipo = $idTipoEvaluacion)) as inferior,
												((resultado_par)*10000)/(SELECT ponderacion+$ponderacionPares FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE id_tipo_evaluacion = (SELECT id_tipo_evaluacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE tipo = 'pares' and id_tipo = $idTipoEvaluacion)) as pares,
												
												(SELECT ponderacion+$ponderacionSuperior FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE id_tipo_evaluacion = (SELECT id_tipo_evaluacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE tipo = 'superior' and id_tipo = $idTipoEvaluacion)) as superiorponderacion,
												(SELECT ponderacion+$ponderacioInferior FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE id_tipo_evaluacion = (SELECT id_tipo_evaluacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE tipo = 'inferior' and id_tipo = $idTipoEvaluacion)) as inferiorponderacion,
												(SELECT ponderacion+$ponderacionPares FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE id_tipo_evaluacion = (SELECT id_tipo_evaluacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE tipo = 'pares' and id_tipo = $idTipoEvaluacion)) as paresponderacion,
												(SELECT ponderacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE id_tipo_evaluacion = (SELECT id_tipo_evaluacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE tipo = 'autoevaluacion' and id_tipo = $idTipoEvaluacion)) as autoevaluacionponderacion,
												(SELECT ponderacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE id_tipo_evaluacion =  (SELECT id_tipo_evaluacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE tipo = 'individual' and id_tipo = $idTipoEvaluacion)) as individualponderacion,
												25 as cumplimientoponderacion,	
				
												((resultado_autoevaluacion)*10000)/(SELECT ponderacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE id_tipo_evaluacion = (SELECT id_tipo_evaluacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE tipo = 'autoevaluacion' and id_tipo = $idTipoEvaluacion)) as autoevaluacion,
												((resultado_individual)*10000)/(SELECT ponderacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE id_tipo_evaluacion =  (SELECT id_tipo_evaluacion FROM g_evaluacion_desempenio.tipos_evaluaciones WHERE tipo = 'individual' and id_tipo = $idTipoEvaluacion)) as individual,
												((resultado_cumplimiento)*10000)/25 as cumplimiento,								
												((total)*10000)/100 as total
											FROM
												g_evaluacion_desempenio.resultados_evaluacion re
											WHERE
												id_evaluacion = $idEvaluacion and
												identificador = '$identificador';");
		return $res;
	}
	
	public function listarAplicantesEvaluacion ($conexion, $idEvaluacion){

		$res = $conexion->ejecutarConsulta("SELECT
												distinct(identificador_evaluador) as identificador,
												f.id_area	
											FROM 
												g_evaluacion_desempenio.aplicantes a,
												g_estructura.funcionarios f,
												g_evaluacion_desempenio.tipos_evaluaciones te,
												g_evaluacion_desempenio.tb_evaluacion_tipos tip
											WHERE
												a.identificador_evaluador = f.identificador and
												te.id_tipo_evaluacion = a.id_tipo_evaluacion and
												tip.id_evaluacion = $idEvaluacion and
												te.estado=1 
											ORDER BY 
												2;");
		return $res;
	}
	
	public function obtenerResultadoEvaluacionIndividual($conexion, $ponderacion, $identificadorEvaluado, $idEvaluacion){
			$sql="select
						(sum(valor_total)*($ponderacion)::decimal)/((SELECT Count(*) AS resultado FROM g_evaluacion_desempenio.aplicantes_individual_respuesta where id_evaluacion = $idEvaluacion and identificador_evaluado= '$identificadorEvaluado')*100)/100 as valor
				  from
						g_evaluacion_desempenio.aplicantes_individual_respuesta
				  where
						id_evaluacion = $idEvaluacion and 
						identificador_evaluado= '$identificadorEvaluado';";
			
		$res = $conexion->ejecutarConsulta($sql);
				return $res;
	}
	
	public function guardarAplicantesIndividual ($conexion, $identificadorEvaluador, $identificadorEvaluado, $estado, $idEvaluacion){

		if(pg_num_rows($this->verificarAplicanteIndividual($conexion, $identificadorEvaluador, $identificadorEvaluado,$idEvaluacion))==0){
		
		$res = $conexion->ejecutarConsultaLOGS("INSERT INTO 
				g_evaluacion_desempenio.aplicantes_individual(identificador_evaluador, identificador_evaluado, estado, id_evaluacion)
				VALUES ('$identificadorEvaluador','$identificadorEvaluado','$estado', $idEvaluacion);");
		return $res;
		}
	}
	
	
	public function listarAplicantesEvaluacionIndividual ($conexion, $identificador,$vigencia,$idEvaluacion=NULL){
		
		$idEvaluacion = $idEvaluacion != "" ? "'" .  $idEvaluacion  . "'" : "NULL";
		$vigencia = $vigencia != "" ? "'" .  $vigencia  . "'" : "NULL";

		$res = $conexion->ejecutarConsulta("SELECT
												ind.identificador_evaluado, ind.id_evaluacion,
												g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos,
												coordinacion, direccion, gestion
											FROM
												g_evaluacion_desempenio.aplicantes_individual ind,
												g_uath.ficha_empleado fe,
												g_uath.datos_contrato co
											WHERE
												ind.identificador_evaluador='$identificador' and 
												ind.estado=TRUE and
												ind.identificador_evaluado=fe.identificador and
												fe.identificador=co.identificador and
												co.estado=1 and
												($idEvaluacion is NULL or  ind.id_evaluacion = $idEvaluacion) and 
												($vigencia is NULL or  ind.vigencia = $vigencia) order by 3;");	
				return $res;
				
								
				
				
	}
	
	public function actualizarAplicanteIndividualFechaInicio($conexion, $idAplicanteIndividual){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_evaluacion_desempenio.aplicantes_individual
											SET
												fecha_inicio = now()
											WHERE
												id_aplicante_individual = '$idAplicanteIndividual';");
	
		return $res;
	}
	
	public function listarValoresDetalleEvaluacionIndividual ($conexion,$idEvaluacion,$identificadorAplicante){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_evaluacion_desempenio.aplicantes_individual_respuesta air,
												g_evaluacion_desempenio.aplicantes_individual ai
											WHERE
												air.id_evaluacion = $idEvaluacion
												and air.id_aplicante_individual=$identificadorAplicante
												and air.id_aplicante_individual=ai.id_aplicante_individual;");
				return $res;
	}
	

	public function guardarEvaluacionIndividual ($conexion, $aplicanteIndividual, $idFuncion, $nombreFuncion, $valorMeta,$valorCumplimiento,$valorTotal, $idEvaluacion, $identificadorEvaluado){
		$res = $conexion->ejecutarConsultaLOGS("
				INSERT INTO 
						g_evaluacion_desempenio.aplicantes_individual_respuesta
										(id_aplicante_individual, id_funcion, indicador_funcion, valor_meta, valor_cumplimiento, valor_total, id_evaluacion, identificador_evaluado)
				VALUES ($aplicanteIndividual, $idFuncion, '$nombreFuncion', $valorMeta,$valorCumplimiento,$valorTotal, $idEvaluacion,'$identificadorEvaluado');");
		return $res;
	}
	
	
	public function actualizarEstadoAplicantesIndividual($conexion, $idAplicanteIndividual){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_evaluacion_desempenio.aplicantes_individual
											SET
												estado = FALSE, fecha_fin = now()
											WHERE
												id_aplicante_individual = '$idAplicanteIndividual';");
	
		return $res;
	}
//--------------------------------------------------------------------------------------------------------------------------------------
	public function verificarResultadoEvaluacion($conexion, $identificador, $idEvaluacion){
	
		$res = $conexion->ejecutarConsulta("SELECT *
				FROM
				g_evaluacion_desempenio.resultados_evaluacion
				WHERE
				identificador='$identificador' AND
				id_evaluacion='$idEvaluacion';");
				return $res;
	}
//--------------------------------------------------------------------------------------------------------------------------------------	
	public function verificarAplicanteRegistrado($conexion, $identificadorEvaluador, $identificadorEvaluado,$idTipoEvaluacion, $tipo, $idEvaluacion){
	
		$res = $conexion->ejecutarConsulta("SELECT *
					  FROM 
							g_evaluacion_desempenio.aplicantes 
					  WHERE 
							identificador_evaluador='$identificadorEvaluador' AND 
					  		identificador_evaluado='$identificadorEvaluado' AND 
							id_evaluacion=$idEvaluacion;");					
		return $res;
	}
//---------------------------------------------------------------------------------------------------------------------------------------
	public function verificarAplicanteIndividual($conexion, $identificadorEvaluador, $identificadorEvaluado,$idTipoEvaluacion){
	
		$res = $conexion->ejecutarConsulta("SELECT *
				FROM
				g_evaluacion_desempenio.aplicantes_individual
				WHERE
				identificador_evaluador='$identificadorEvaluador' AND
				identificador_evaluado='$identificadorEvaluado' AND
				id_evaluacion=$idTipoEvaluacion AND
				estado=TRUE;");
				return $res;
	}
	
	//-------------verificar competencias asignadas---------------------------------------------------------
	public function verificarCompetenciasConductuales($conexion, $identificadorEvaluado, $idTipoEvaluacion=NULL,$idEvaluacion=NULL){

		$idTipoEvaluacion = $idTipoEvaluacion!="" ? "'" . $idTipoEvaluacion . "'" : "0";
	 	$sql="SELECT 
					distinct tipo 
			  FROM 
			  		g_evaluacion_desempenio.aplicantes 
    		  WHERE 
    		  		id_tipo_evaluacion= $idTipoEvaluacion and 
    		  		identificador_evaluado='$identificadorEvaluado' and
					id_evaluacion=$idEvaluacion;";		
		$res = $conexion->ejecutarConsulta($sql);
		
		return $res;
	}
	//------------------------------------------------------------------------------------------------------------
	public function obternerPonderacionCompetencias($conexion, $casoCompetencia, $idTipo, $tipo){
		$sql="SELECT 
					tipo, 
					ponderacion
  			  FROM 
  			  		g_evaluacion_desempenio.ponderacion_competencias 
  			  where 
  			  		id_tipo=$idTipo and 
  			  		caso_competencia='$casoCompetencia' and
					tipo = '$tipo';";
		$res = $conexion->ejecutarConsulta($sql);
		
		return $res;
		
	}
	//--------------------------------------------------------------------------------------------------------------
	public function verificarEvaluacionesServidores($conexion, $identificador, $idEvaluacion){
		$sql="select 
					count(*) as valor 
			  from 
			  		g_evaluacion_desempenio.aplicantes ap, 
			  		g_evaluacion_desempenio.tipos_evaluaciones te, 
			  		g_evaluacion_desempenio.evaluaciones ev,
			  		g_evaluacion_desempenio.tb_evaluacion_tipos tip
			  where 
					(ap.identificador_evaluador='$identificador' or ap.identificador_evaluado='$identificador') 
					and ap.id_tipo_evaluacion = te.id_tipo_evaluacion 
					and ev.id_evaluacion = tip.id_evaluacion and ev.id_evaluacion =$idEvaluacion ;";
		
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
	
	
	
	public function devolverNivelAreas($conexion, $identificadorUsuario){
	
		$areaUsuario = pg_fetch_assoc($conexion->ejecutarConsulta("select
				a.*
				from
				g_estructura.area as a,
				g_estructura.funcionarios as f
				where
				a.id_area = f.id_area
				and f.identificador = '$identificadorUsuario'"));
		$idAreaFuncionario=$areaUsuario['id_area'];
	
		//---devolver niveles del área consultada-----------------------------------------------------------------------
		$areaRecursiva = pg_fetch_assoc($conexion->ejecutarConsulta("WITH RECURSIVE area_cte(id, nombre_area, path,clasificacion) AS (
				SELECT
				tn.id_area,tn.nombre,tn.id_area::TEXT AS path,tn.clasificacion
				FROM
				g_estructura.area AS tn
				WHERE
				tn.id_area_padre IS NULL and estado=1
				UNION ALL
				SELECT
				c.id_area, c.nombre,(p.path || ',' || c.id_area::TEXT),c.clasificacion
				FROM
				area_cte AS p,g_estructura.area AS c
				WHERE
				c.id_area_padre = p.id and
				c.estado=1
		)SELECT
				*
				FROM
				area_cte AS n
				WHERE
				n.id='$idAreaFuncionario'
				ORDER BY n.id ASC;"));
	
				$tipoArea = $areaRecursiva['clasificacion'];
				$arrayAreas = explode(',', $areaRecursiva['path']);
				$numAreas = sizeof($arrayAreas)-1;
				
		$resultConsulta = array(
		'arrayAreas' => $arrayAreas,
		'numAreas' =>	$numAreas
		);
		return $resultConsulta;
	}
	
	
	
	public function verificarResponsable($conexion, $identificador){
	
	$sqlScript="select
			*
		from g_estructura.responsables res,
			g_estructura.area ar
		where
			res.identificador='$identificador' and
			res.responsable = true and
			ar.id_area = res.id_area and res.id_area not in ('Z1','Z2','Z3','Z4','Z5','Z6','Z7') order by 1";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	//----------------------------------------------------------------------------------------------
	public function datosFuncionario($conexion, $identificador){
	
		$sqlScript="SELECT
						apellido ||' '||nombre as user
					FROM
						g_uath.ficha_empleado
					WHERE
						identificador='$identificador'";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	//-----------------------------------------------------------------------------------------------
	public function actualizarResultadoEvaluacion($conexion, $identificador,$idEvaluacion, $superior, $inferior, $par, $autoevaluacion,$resultadoIndividual){
		
			$res = $conexion->ejecutarConsultaLOGS("
					UPDATE 
						g_evaluacion_desempenio.resultados_evaluacion 
					SET 
						resultado_superior=$superior, 
						resultado_inferior=$inferior, 
						resultado_par=$par, 
						resultado_autoevaluacion=$autoevaluacion, 
						resultado_individual=$resultadoIndividual
					WHERE 
						identificador='$identificador' AND id_evaluacion=$idEvaluacion;");
			return $res;
			
	}
   //-------------------------------------------------------------------------------------------------
	public function devolverFuncionariosActivos($conexion){
		$sql="SELECT 
					c.identificador,  
       				c.gestion, c.id_gestion
  			  FROM 
					g_uath.datos_contrato c,
					g_usuario.usuarios_perfiles p
					 
			  where 
					c.estado= 1 and c.identificador = p.identificador
					and id_perfil= 2 order by 3;";
		$res = $conexion->ejecutarConsulta($sql);
		return $res;
	}
 //---------------------------------------------------------------------------------------------------------
	public function modificarParametros($conexion, $cod_parametro,$nombreParametro,$anio,$periodo,$semestre,$numDias,$fechaInicio, $fechaFin,$evaluacionArea,$calculoResultado,$envioNotificacion,$notificacion){
	
		$res = $conexion->ejecutarConsultaLOGS("
					UPDATE 
							g_evaluacion_desempenio.parametros_evaluacion
				    SET 
						nombre_parametro='$nombreParametro', anio=$anio, periodo='$periodo', semestre='$semestre', 
				        dias_laborables=$numDias, fecha_inicio='$fechaInicio', fecha_fin='$fechaFin', tiempo_minimo_area=$evaluacionArea, 
				        calculo_resultados='$calculoResultado', envio_notificacion='$envioNotificacion', cod_notificacion=$notificacion  
				 	WHERE 
						cod_parametro=$cod_parametro;");
		return $res;
	}
	//---------------------------------------------------------------------------------------------------------
	public function guardarParametros ($conexion, $identificador,$nombreParametro,$anio,$periodo,$semestre,$numDias,$fechaInicio, $fechaFin,$evaluacionArea,$calculoResultado,$envioNotificacion,$notificacion,$mesIni,$mesFin){
	
		$res = $conexion->ejecutarConsultaLOGS("INSERT INTO g_evaluacion_desempenio.parametros_evaluacion(
				nombre_parametro, anio, periodo, semestre, dias_laborables,fecha_inicio, fecha_fin, tiempo_minimo_area, calculo_resultados,envio_notificacion, identificador,mes_inicio, mes_fin,cod_notificacion )
				VALUES ('$nombreParametro',$anio, '$periodo', '$semestre',$numDias,'$fechaInicio','$fechaFin',$evaluacionArea,'$calculoResultado','$envioNotificacion','$identificador','$mesIni','$mesFin',$notificacion);");
		return $res;
	}
 //--------------------------------------------------------------------------------------------------------------		
	public function listaParametros ($conexion,$estado,$codParametros=NULL){
		$datos='WHERE ';
		switch ($estado){
			case 'ABIERTOS': $estado = 'estado = true '; break;
			case 'TODOS': $estado = ''; break;
			case 'CERRADOS': $estado = 'estado = false '; break;
		}
		$datos.=$estado;
		if($codParametros != ''){
			$datos.= " and cod_parametro = $codParametros ";
		}
		
		$res = $conexion->ejecutarConsulta("SELECT
					*
				FROM
					g_evaluacion_desempenio.parametros_evaluacion
					$datos order by 1;");
				return $res;
	}
	//--------------------------------------------------------------------------------------------------------------
	public function listaParametrosSinUso ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
											 *
											FROM
											  g_evaluacion_desempenio.parametros_evaluacion peva
											where 
											  peva.cod_parametro not in  
											  (select cod_parametro from g_evaluacion_desempenio.evaluaciones where vigencia in ('activo','proceso','finalizado','excepciones','cerrado') ) and
											  peva.estado = true;");
		return $res;
	}
//--------------------------------------------------------------------------------------------------------------
	public function modificarEvaluacion ($conexion,$codEvaluacion ,$nombre, $objetivo,$codParametro,$estadoCatastro){
	
		$res = $conexion->ejecutarConsultaLOGS("
				UPDATE 
						g_evaluacion_desempenio.evaluaciones
			   	SET 
						nombre='$nombre', cod_parametro='$codParametro', estado_catastro='$estadoCatastro', objetivo='$objetivo'
			 	WHERE 
						id_evaluacion=$codEvaluacion;");
		return $res;
	}
//------------------------------------------------------------------------------------------------------------------
public function registroModificaciones($conexion,$identificador,$codigo,$tabla){
		
		$res = $conexion->ejecutarConsultaLOGS("
				INSERT INTO g_evaluacion_desempenio.modificaciones_log(identificador, cod_parametro, nametable)
    			VALUES ('$identificador', $codigo,'$tabla');");
		return $res;
  }
//-----------------------------------------------------------------------------------------------------------------
public function devolverSubTipo($conexion){
  
  	$sqlScript="SELECT 
  						id_tipo, id_tipo_evaluacion
  				FROM 
  						g_evaluacion_desempenio.tipos_evaluaciones 
  				where 
  						estado=1;";
  	$res = $conexion->ejecutarConsulta($sqlScript);
  	return $res;
  }
//------------------------------------------------------------------------------------------------------------------
  public function guardarTipoEvaluacion($conexion,$idEvaluacion,$idTipo){
  	$res = $conexion->ejecutarConsulta("
  			INSERT INTO g_evaluacion_desempenio.tb_evaluacion_tipos(
            id_evaluacion, id_tipo)
    		VALUES ($idEvaluacion, $idTipo);");
  	return $res;
  }
//--------------------------------------------------------------------------------------------------------------------
  public function devolverEvaluacion ($conexion,$idEvaluacion=NULL){
  	  
  	$res = $conexion->ejecutarConsulta("
  			SELECT 
  				tip.id_tipo,
  				eva.*
  			FROM 
  				g_evaluacion_desempenio.tb_evaluacion_tipos tip,
  				g_evaluacion_desempenio.evaluaciones eva
  			WHERE 
  				tip.id_evaluacion= eva.id_evaluacion and eva.id_evaluacion=$idEvaluacion;");
  	return $res;
  }
  //--------------------------------------------------------------------------------------------------------------
  public function activarInactivarCatastroOpcion($conexion,$estadoOpcion,$nombreOpcion){
  
  	$res = $conexion->ejecutarConsultaLOGS("
  			UPDATE
  				g_programas.opciones
  			SET
  				estado_opcion='$estadoOpcion'
  			WHERE
  				nombre_opcion='$nombreOpcion';");
  	return $res;
  }
  //--------------------------------------------------------------------------------------------------------------------
  public function devolverEvaluacionVigente ($conexion,$estado=NULL, $codParametro=NULL, $idEvaluacion=NULL ){
  
  	$estado = $estado != "" ? "'" .  $estado  . "'" : "NULL";
  	$codParametro = $codParametro != "" ? "'" .  $codParametro  . "'" : "NULL";
  	$idEvaluacion = $idEvaluacion != "" ? "'" .  $idEvaluacion  . "'" : "NULL";
 
  	$res = $conexion->ejecutarConsulta("
  			SELECT 	
  					eva.id_evaluacion, eva.nombre, par.cod_notificacion,par.fecha_inicio, par.fecha_fin, eva.vigencia, eva.estado_catastro
  			FROM 
  					g_evaluacion_desempenio.evaluaciones eva,
  					g_evaluacion_desempenio.parametros_evaluacion par
  			where 
  					eva.cod_parametro = par.cod_parametro and 
  					($estado is NULL or  eva.estado = $estado) and
  					($codParametro is NULL or  par.cod_parametro = $codParametro) and
  					($idEvaluacion is NULL or  eva.id_evaluacion = $idEvaluacion);");
  	return $res;
  }
  //-------------------------------------------------------------------------------------------------------------------
  public function actualizarEvaluacion($conexion,$codEvaluacion,$estadoVigencia=NULL,$estado=NULL){
  	
   	if($estadoVigencia != ''){
  		$data='vigencia ='."'$estadoVigencia'";
  		if($estado == 0 or $estado == 1){
  			$data.=',estado ='.$estado;
  		}
   	}else if($estado == 0 or $estado == 1){
  		$data='estado ='.$estado;
   	}
  	
  	$res = $conexion->ejecutarConsulta("
  			UPDATE
  				g_evaluacion_desempenio.evaluaciones
  			SET 
  				$data
  			WHERE
  				id_evaluacion=$codEvaluacion;");
  	return $res;
  }
  //--------------------------------------------------------------------------------------------------------------
  public function inactivarActivarParametros ($conexion,$idParametro,$estado){
  
  	$res = $conexion->ejecutarConsulta("UPDATE
  					g_evaluacion_desempenio.parametros_evaluacion
  			SET
  					estado = $estado
  			WHERE
  					cod_parametro = $idParametro;");
  			return $res;
  }
  //--------------------------------------------------------------------------------------------------------------
  public function inactivarActivarAplicantes($conexion,$identificador=NULL,$estadoVigencia=NULL, $idEvaluacion){
  	
  	$data='';
  	if($idEvaluacion != ''){
  		$data .='id_evaluacion ='.$idEvaluacion;
  		if($identificador != '')
  			$data.='identificador_evaluador ='."'$identificador'";
  	}else if($identificador != ''){
  			$data.=' identificador_evaluador ='."'$identificador'";
  		}
  	
  	$res = $conexion->ejecutarConsulta("
  			UPDATE 
  				g_evaluacion_desempenio.aplicantes
   			SET 
  				vigencia='$estadoVigencia'
  			WHERE $data;");
  	return $res;
  	
  }
  //--------------------------------------------------------------------------------------------------------------
  public function inactivarActivarAplicantesIndividual($conexion,$identificador=NULL,$estadoVigencia=NULL, $idEvaluacion){
  	
  	if($identificador != ''){
  		$data=' and identificador_evaluador ='."'$identificador'";
  	}
  	
  	$res = $conexion->ejecutarConsulta("
  			UPDATE
  				g_evaluacion_desempenio.aplicantes_individual
  			SET
  				vigencia='$estadoVigencia'
  			WHERE
  			id_evaluacion=$idEvaluacion $data;");
  	return $res;
  }
  //-.------------------------------------------------------------------------------------------------------------
  
  public function buscarDatosServidor($conexion,$identificador){
  
  	$res = $conexion->ejecutarConsulta("
  			SELECT 
				* 
			FROM 
				g_uath.ficha_empleado fe, 
				g_uath.datos_contrato dc 
			WHERE 
				fe.identificador='$identificador' and 
				dc.identificador = fe.identificador and
				dc.estado=1;");
  	return $res;
  }
  //--------------------------------------------------------------------------------------------------------------
  public function obtenerResponsabilidad($conexion, $identificador){
  
  	$res = $conexion->ejecutarConsulta("
				SELECT 
					res.id_area, ar.nombre  
				FROM 
					g_estructura.responsables res,
					g_estructura.area ar 
				WHERE 
					res.identificador='$identificador' and 
					res.id_area = ar.id_area and 
					res.responsable = true;");
  	return $res;
  }
//---------------------------------------------------------------------------------------------------------------------
  public function obtenerNombrePuesto($conexion, $identificador){
  
  	$res = $conexion->ejecutarConsulta("select
  			*
  			from
  			g_uath.datos_contrato dc
  			where
  			dc.identificador = '$identificador' and
  			dc.estado = 1;");
  
  			return $res;
  }
  //------------------------------------------------------------------------------------------------------------------
  public function guardarExcepcion($conexion,$identificadorEvaluador,$fechaInicio,$fechaFin,$identificador,$motivo,$observacion,$envioNotificacion,$notificacion,$idEvaluacion){
    	$res = $conexion->ejecutarConsultaLOGS("
  			INSERT INTO 
  				g_evaluacion_desempenio.excepciones_evaluacion(identificador_evaluador, fecha_inicio,fecha_fin,identificador, motivo, observacion, 
            	envio_notificacion, id_notificacion,id_evaluacion,estado)
    		VALUES ('$identificadorEvaluador','$fechaInicio','$fechaFin','$identificador','$motivo','$observacion','$envioNotificacion', $notificacion,$idEvaluacion,'activo');");
  	return $res;
  }
  //-------------------------------------------------------------------------------------------------------------
  public function obtenerExcepciones($conexion, $idevaluacion){
 
  	$res = $conexion->ejecutarConsulta("
  				SELECT 
  				       fe.apellido || ' '||fe.nombre as nombres,
  				       provincia, canton, oficina, coordinacion, direccion, gestion,ee.identificador_evaluador
  				FROM 
  					g_evaluacion_desempenio.excepciones_evaluacion ee,
  					g_uath.ficha_empleado fe,
  					g_uath.datos_contrato co
  				WHERE
  					ee.id_evaluacion=$idevaluacion and 
  					ee.identificador_evaluador=fe.identificador and 
  					co.identificador = ee.identificador_evaluador and 
  					co.estado = 1 and 
  					ee.estado=true;");
  
  			return $res;
  }
  //-------------------------------------------------------------------------------------------------------------
  public function obtenerExcepcionesFuncionarios($conexion, $identificador,$provincia=NULL){
  
  	$provincia = $provincia != "" ? "'" .  $provincia  . "'" : "NULL";
  	$res = $conexion->ejecutarConsulta("
  			SELECT
  			fe.apellido || ' '||fe.nombre as nombres,
  			fe.apellido,
  			fe.nombre,
  			provincia, canton, oficina, coordinacion, direccion, gestion,
  			mail_personal, mail_institucional, provincia, canton, oficina, coordinacion, direccion, gestion, nombre_puesto
  			FROM
  			g_uath.ficha_empleado fe,
  			g_uath.datos_contrato co
  			WHERE
  			co.identificador='$identificador' and
  			co.identificador = fe.identificador and
  			($provincia is NULL or  provincia = $provincia) and
  			co.estado = 1;");
  			return $res;
  }
  //--------------------------------------------------------------------------------------------------------------
  public function activarExcepcionAplicantes($conexion,$identificador_evaluador, $identificador_evaluado,$tipo,$idEvaluacion){
  	 
  	$res = $conexion->ejecutarConsulta("
						  UPDATE 
  								g_evaluacion_desempenio.aplicantes
						  SET 
  							   estado_excepcion='activo'
						  WHERE 
  								identificador_evaluador='$identificador_evaluador' and
  								identificador_evaluado='$identificador_evaluado' and
  								tipo='$tipo' and
  								id_evaluacion=$idEvaluacion ;");
		return $res;
  }
  //--------------------------------------------------------------------------------------------------------------
  public function activarExcepcionAplicantesIndividual($conexion,$identificador_evaluador, $identificador_evaluado,$idEvaluacion){
  
  	$res = $conexion->ejecutarConsulta("
  			UPDATE
  			g_evaluacion_desempenio.aplicantes_individual
  			SET
  			estado_excepcion='activo'
  			WHERE
  			identificador_evaluador='$identificador_evaluador' and
  			identificador_evaluado='$identificador_evaluado' and
  			id_evaluacion=$idEvaluacion ;");
  	return $res;
  }
  
  //--------------------------------------------------------------------------------------------------------------
  
  public function excepcionAplicantesIndividual($conexion,$vigencia, $estadoExcepcion,$idEvaluacion,$cambiarEstado,$identificador){
  	
  	if($estadoExcepcion == '')
  	
  	$res = $conexion->ejecutarConsulta("
  			UPDATE
  				g_evaluacion_desempenio.aplicantes_individual
  			SET
  				vigencia='$vigencia',
  				estado_excepcion='$cambiarEstado'
  			WHERE
  				identificador_evaluador='$identificador' and
	  			id_evaluacion=$idEvaluacion and 
  				estado_excepcion='$estadoExcepcion';");
  	return $res;
  }
 //---------------------------------------------------------------------------------------------------------------------------
  public function excepcionAplicantesIndividualActualizar($conexion,$estadoExcepcion,$idEvaluacion,$cambiarEstado,$identificadorEvaluador,$identificadorEvaluado){
  
  	$res = $conexion->ejecutarConsulta("
  			UPDATE
  				g_evaluacion_desempenio.aplicantes_individual
  			SET
  				estado_excepcion='$cambiarEstado'
  			WHERE
  				identificador_evaluador='$identificadorEvaluador' and
  				identificador_evaluado='$identificadorEvaluado' and
  				id_evaluacion=$idEvaluacion and
  				estado_excepcion='$estadoExcepcion';");
  	return $res;
  }
  
  //--------------------------------------------------------------------------------------------------------------
  public function excepcionAplicantes($conexion,$vigencia, $estadoExcepcion,$idEvaluacion,$cambiarEstado,$identificador){
  
  	$res = $conexion->ejecutarConsulta("
  			UPDATE
  				g_evaluacion_desempenio.aplicantes
  			SET
  				vigencia='$vigencia',
  				estado_excepcion='$cambiarEstado'
  			WHERE
  				identificador_evaluador='$identificador' and
	  			id_evaluacion=$idEvaluacion and 
  				estado_excepcion='$estadoExcepcion' ;");
	 return $res;
  }
  //--------------------------------------------------------------------------------------------------------------
  public function excepcionAplicantesActualizar($conexion, $estadoExcepcion,$idEvaluacion,$cambiarEstado,$identificadorEvaluador,$identificadorEvaluado){
  
  	$res = $conexion->ejecutarConsulta("
  			UPDATE
  			g_evaluacion_desempenio.aplicantes
  			SET
  			estado_excepcion='$cambiarEstado'
  			WHERE
  			identificador_evaluador='$identificadorEvaluador' and
  			identificador_evaluado='$identificadorEvaluado' and
  			id_evaluacion=$idEvaluacion and
  			estado_excepcion='$estadoExcepcion' ;");
  	return $res;
  }
  //------------------------------------------------------------------------------------------------------------------
  
  public function devolverExcepcionesVigente($conexion, $idEvaluacion){
  
  	$res = $conexion->ejecutarConsulta("
  			SELECT 
  					*
 			FROM 
  				g_evaluacion_desempenio.excepciones_evaluacion
  			WHERE
  					id_evaluacion=$idEvaluacion 
  					 ;");
  	return $res;
  }
  //------------------------------------------------------------------------------------------------------------------
  public function actualizarExcepcionesVigente($conexion,$idExcepcion,$estado){
  	$res = $conexion->ejecutarConsulta("
  			UPDATE 
  				g_evaluacion_desempenio.excepciones_evaluacion
   			SET 
  				estado='$estado'
 			WHERE 
  				id_excepcion_evaluacion=$idExcepcion;");
  	return $res;
  }
  //------------------------------------------------------------------------------------------------------------------
  //------------------------------------------------------------------------------------------------------------------
  public function devolverListaAplicantesExcepciones($conexion,$idEvaluacion,$estado=NULL){
 
  	$res = $conexion->ejecutarConsulta("
  			SELECT 
				identificador_evaluado,
  				identificador_evaluador
  			
			  FROM 
			  	g_evaluacion_desempenio.aplicantes 
			  WHERE 
  				id_evaluacion= $idEvaluacion AND 
			  	estado_excepcion='$estado' and 
			  	estado = false and
  			  	vigencia='finalizado';");
  	return $res;
  }
//------------------------------------------------------------------------------------------------------------------
  public function devolverListaAplicantesIndividualExcepciones($conexion,$idEvaluacion,$estado=NULL){
  	
  	$res = $conexion->ejecutarConsulta("
  			SELECT 
				identificador_evaluado,
  				identificador_evaluador
			  FROM 
			  g_evaluacion_desempenio.aplicantes_individual 
			  WHERE id_evaluacion= $idEvaluacion AND 
			  estado_excepcion='$estado' and 
			  estado = false and 
  			  vigencia='finalizado';");
  	return $res;
  }
  //------------------------------------------------------------------------------------------------------------------
  
  public function abrirEvaluacionPendienteUsuario ($conexion, $identificador=NULL, $vigencia=NULL, $idEvaluacion=NULL, $provincia=NULL){
  	$vigencia = $vigencia != "" ? "'" .  $vigencia  . "'" : "NULL";
  	$idEvaluacion = $idEvaluacion != "" ? "'" .  $idEvaluacion  . "'" : "NULL";
  	$identificador = $identificador != "" ? "'" .  $identificador  . "'" : "NULL";
  	$provincia = $provincia != "" ? "'" .  $provincia  . "'" : "NULL";
  	
  	$res = $conexion->ejecutarConsulta("SELECT
  			distinct(a.*),
  			g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
  			FROM
  			g_evaluacion_desempenio.aplicantes a,
  			g_evaluacion_desempenio.tipos_evaluaciones te,
  			g_evaluacion_desempenio.evaluaciones e,
  			g_uath.ficha_empleado fe,
  			g_evaluacion_desempenio.tb_evaluacion_tipos tip,
  			g_uath.datos_contrato co
  			WHERE
  			($identificador is NULL or  identificador_evaluador = $identificador) and
  			a.id_tipo_evaluacion = te.id_tipo_evaluacion and
  			tip.id_evaluacion = e.id_evaluacion and
  			a.id_evaluacion= e.id_evaluacion and
  			co.identificador = fe.identificador and
  			a.estado = true and
  			($vigencia is NULL or  a.vigencia = $vigencia) and
  			($idEvaluacion is NULL or  a.id_evaluacion = $idEvaluacion) and
  			($provincia is NULL or  provincia = $provincia) and
  			fe.identificador = a.identificador_evaluado and
  			co.estado = 1 order by 2;");
  			return $res;
  
  }
  //-----------------------------------------------------------------------------------------------------------------------------
  public function filtrarFuncionariosPendientes($conexion, $identificador=NULL, $idEvaluacion=NULL){
  	$res=$conexion->ejecutarConsulta("
  			SELECT a.identificador_evaluado, a.tipo,
				g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos,
				coordinacion, direccion, gestion
			  FROM 
				  g_evaluacion_desempenio.aplicantes a, 
				  g_uath.ficha_empleado fe,
				  g_uath.datos_contrato co
			  WHERE 
			  identificador_evaluador='$identificador' AND 
  			  id_evaluacion=$idEvaluacion AND 
			  a.estado=true and 
			  identificador_evaluado=fe.identificador and
			  fe.identificador=co.identificador and
			  co.estado=1 order by 3;");
  	return $res;
  }
  //-----------------------------------------------------------------------------------------------------------------------------
  public function filtrarFuncionariosEvaluacion($conexion, $identificador=NULL, $idEvaluacion=NULL){
  	$res=$conexion->ejecutarConsulta("
  			SELECT a.identificador_evaluado, a.tipo,
  			fe.apellido||' '||  fe.nombre AS nombres_completos,
  			coordinacion, direccion, gestion
  			FROM
  			g_evaluacion_desempenio.aplicantes a,
  			g_uath.ficha_empleado fe,
  			g_uath.datos_contrato co
  			WHERE
  			identificador_evaluador='$identificador' AND
  			id_evaluacion=$idEvaluacion AND
  			identificador_evaluado=fe.identificador and
  			fe.identificador=co.identificador and
  			a.tipo <> 'autoevaluacion' and
  			co.estado=1;");
  			return $res;
  }
  //----------------------------------------------------------------------------------------------------------------------------------
   
}

?>
