<?php

class ControladorCronogramaVacaciones{


	public function obtenerCronogramaVacacionesPorIdCronogramaVacacion ($conexion, $idCronogramaVacacion){
				
		$consulta = "SELECT 
								cv.id_cronograma_vacacion
								, cv.id_configuracion_cronograma_vacacion
								, cv.identificador_funcionario
								, cv.nombre_funcionario
								, cv.fecha_ingreso_institucion
								, cv.nombre_puesto
								, cv.id_area_padre
								, cv.identificador_backup
								, cv.total_dias_planificados
								, cv.anio_cronograma_vacacion
								, cv.numero_periodos
							FROM 
								g_vacaciones.cronograma_vacaciones cv
							WHERE 
								id_cronograma_vacacion = " . $idCronogramaVacacion . ";";

		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}


	public function obtenerPeriodoCronogramaVacacionesPorIdCronogramaVacacion($conexion, $idCronogramaVacacion) {
		
		$consulta = "SELECT 
						pcv.id_periodo_cronograma_vacacion
						, pcv.id_cronograma_vacacion
						, pcv.numero_periodo
						, to_char(pcv.fecha_inicio, 'TMDay, ' || ' dd ') || 'de ' || to_char(pcv.fecha_inicio, 'TMmonth') || ' de ' || to_char(pcv.fecha_inicio, 'yyyy') as fecha_inicio
						, to_char(pcv.fecha_fin, 'TMDay, ' || ' dd ') || 'de ' || to_char(pcv.fecha_fin, 'TMmonth') || ' de ' || to_char(pcv.fecha_fin, 'yyyy') as fecha_fin
						, pcv.total_dias
						, pcv.estado_registro
						, pcv.estado_reprogramacion
					FROM 
						g_vacaciones.periodo_cronograma_vacaciones pcv
					WHERE
						id_cronograma_vacacion = " . $idCronogramaVacacion . ";";

			$res = $conexion->ejecutarConsulta($consulta);
			return $res;

		}
	
		public function obtenerConfiguracionCronogramaVacaciones($conexion) {
		
			$consulta = "SELECT 
							DISTINCT id_configuracion_cronograma_vacacion
							, anio_configuracion_cronograma_vacacion
							, descripcion_configuracion_vacacion
							, identificador_configuracion_cronograma_vacacion
							, estado_configuracion_cronograma_vacacion
							, identificador_director_ejecutivo
							, ruta_consolidado_excel
							, ruta_consolidado_pdf
							, observacion
							, fecha_modificacion
							, fecha_creacion
						FROM 
							g_vacaciones.configuracion_cronograma_vacaciones;";

				$res = $conexion->ejecutarConsulta($consulta);
				return $res;

		}


}