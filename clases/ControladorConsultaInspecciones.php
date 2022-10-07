<?php
class ControladorConsultaInspecciones{

	private $etiquetaTodos = 'TODOS';
	private $camposTablaLaboratorio = '';
	private $tablaLaboratorio = '';
	private $condicionLaboratorio = '';

	public $camposLaboratorio = array(
			//'actividad_origen',
			//'analisis',
			'codigo_muestra',
			//'numero_reporte',
			//'conservacion',
			//'tipo_muestra',
			//'descripcion_sintomas',
			//'fase_fenologica',
			//'nombre_producto',
			//'peso_muestra',
			//'prediagnostico',
			//'tipo_cliente',
			//'aplicacion_producto_quimico',
	);

	private function calcularNuevaFechaFin($fechaFin){
		return $fechaFin . ' 23:59:59';
	}

	private function obtenerComentariosColumnas($conexion, $tabla){
		
		$consulta = "SELECT
						attname,
						description
					FROM
						pg_attribute,
						pg_class,
						pg_description
					WHERE
						attrelid=pg_class.oid
						AND objoid = pg_class.oid
						AND relname='$tabla'
						AND attstattarget <> 0
						AND objsubid = attnum;";
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
		
	}

	public function construirEncabezadoReporte($conexion, $tabla, $campos){
        $html = '';
        $arreglo = array();
        if (is_array($tabla)) {
            foreach ($tabla as $tab) {
                $res = $this->obtenerComentariosColumnas($conexion, $tab);
                while ($fila = pg_fetch_assoc($res)) {
                    $arreglo[$fila['attname']] = $fila['description'];
                }
            }
        } else {
            $res = $this->obtenerComentariosColumnas($conexion, $tabla);
            while ($fila = pg_fetch_assoc($res)) {
                $arreglo[$fila['attname']] = $fila['description'];
            }
        }
        foreach ($campos as $campo) {
            if (array_key_exists($campo, $arreglo)) {
                if (in_array($campo, $this->camposLaboratorio))
                    $html .= '<th class="lab">';
                else
                    $html .= '<th>';
                $html .= $arreglo[$campo] . '</th>';
            }
        }
        return $html;
    }

	private function filtroGenerarReporteTecnicos($tipoFormulario, $identificadorUsuario,$nombreUsuario,$campoRUC,$campoNombre){
		
		$busqueda="";
		
		if(isset($tipoFormulario) ){
			$identificadorUsuario = $identificadorUsuario!="" ? "'" . $identificadorUsuario . "'" : "NULL";
			$nombreUsuario = $nombreUsuario!="" ? "'%" . $nombreUsuario . "%'" : "NULL";
			$busqueda=" AND ($identificadorUsuario is NULL or $campoRUC = $identificadorUsuario) AND ($nombreUsuario is NULL or $campoNombre ilike $nombreUsuario) ";
		}else{
			$busqueda=" AND $campoRUC='$identificadorUsuario'";
		}
		
		return $busqueda;
		
	}

	public function generarReporteInspeccionMoluscosPlagaFincas($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin, $identificadorUsuario, $nombreUsuario,$tipoFormulario){
		
		if ($incluirDatosLaboratorio) {
			$this->camposTablaLaboratorio = ', fdo.*';
			$this->tablaLaboratorio = ' f_inspeccion.certificacionf01_detalle_ordenes fdo';
			$this->condicionLaboratorio = ' fdo.id_padre = f.id';
		}

		$busqueda=$this->filtroGenerarReporteTecnicos($tipoFormulario,$identificadorUsuario,$nombreUsuario,'f.ruc','f.razon_social');
		$fechaFin = $this->calcularNuevaFechaFin($fechaFin);
		
		$consulta = "SELECT
						f.*,
						fdg.grupo,
						fdg.numero_caracoles
						$this->camposTablaLaboratorio
					FROM
						f_inspeccion.certificacionf01 f INNER JOIN f_inspeccion.certificacionf01_detalle_grupos fdg ON f.id = fdg.id_padre
						LEFT JOIN $this->tablaLaboratorio ON $this->condicionLaboratorio 
					WHERE
						f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'						
						$busqueda
					ORDER BY
						f.fecha_inspeccion;";
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}

	public function generarReporteRechazoEmbalajeMadera($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin , $identificadorUsuario, $nombreUsuario, $tipoFormulario){
		if ($incluirDatosLaboratorio) {
			$this->camposTablaLaboratorio = ', fdo.*';
			$this->tablaLaboratorio = ' LEFT JOIN f_inspeccion.certificacionf02_detalle_ordenes fdo ON (fdo.id_padre = f.id)';
		}

		$busqueda=$this->filtroGenerarReporteTecnicos($tipoFormulario,$identificadorUsuario,$nombreUsuario,'f.ruc_exportador','f.razon_social_exportador');
		$fechaFin = $this->calcularNuevaFechaFin($fechaFin);

		$consulta = "SELECT
						f.*
						$this->camposTablaLaboratorio
					FROM
						f_inspeccion.certificacionf02 f
						$this->tablaLaboratorio
					WHERE
						f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
						$busqueda
					ORDER BY
						f.fecha_inspeccion;";

		$res = $conexion->ejecutarConsulta($consulta);

		return $res;
	}

	public function generarReporteCalificacionLotesCacaoGrano($conexion, $fechaInicio, $fechaFin, $identificadorUsuario, $nombreUsuario, $tipoFormulario){
		
		$busqueda=$this->filtroGenerarReporteTecnicos($tipoFormulario,$identificadorUsuario,$nombreUsuario,'f.ruc','f.exportador');
		$fechaFin = $this->calcularNuevaFechaFin($fechaFin);
		
		$consulta = "SELECT
						f.*
					FROM
						f_inspeccion.certificacionf10 f
					WHERE
						f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
						$busqueda
					ORDER BY
						f.fecha_inspeccion;";
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
		
	}

	public function generarReporteInspeccionCertificacionFitoPPVAR($conexion,$fechaInicio, $fechaFin, $identificadorUsuario, $nombreUsuario, $tipoFormulario){
	    
	    $busqueda=$this->filtroGenerarReporteTecnicos($tipoFormulario,$identificadorUsuario,$nombreUsuario,'f.ruc','f.exportador');
	    $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
	    
	    $consulta = "SELECT
						f.*,
						fdo.*,
						fde.*,UPPER(l.nombre) pais_destino
					FROM
						f_inspeccion.certificacionf11 f
						LEFT JOIN f_inspeccion.certificacionf11_detalle_resultados fdo ON (fdo.id_padre = f.id),
						f_inspeccion.certificacionf11_detalle_envios fde,
						g_catalogos.localizacion l
						
					WHERE
						f.id = fde.id_padre AND
						l.id_localizacion=fde.pais_destino::int AND
						f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin' AND
                        f.estado_f11 = 'activo'
						$busqueda
					ORDER BY
					f.fecha_inspeccion;";
						
						$res = $conexion->ejecutarConsulta($consulta);
						return $res;
						
	}

	public function generarReporteInspeccionAgenciasCarga($conexion,$incluirDatosLaboratorio, $fechaInicio, $fechaFin, $identificadorUsuario, $nombreUsuario, $tipoFormulario){

		if ($incluirDatosLaboratorio) {
			$this->camposTablaLaboratorio = ', fdo.*';
			$this->tablaLaboratorio = ' LEFT JOIN f_inspeccion.certificacionf13_detalle_ordenes fdo ON (fdo.id_padre = f.id)';
		}
		
		$busqueda=$this->filtroGenerarReporteTecnicos($tipoFormulario,$identificadorUsuario,$nombreUsuario,'fdg.ruc_exportador','fdg.exportador');
		$fechaFin = $this->calcularNuevaFechaFin($fechaFin);

		$consulta ="SELECT
						f.*
						$this->camposTablaLaboratorio,
						fdg.*,
                        fdr.plaga, fdr.individuos, fdr.estado, fdr.analisis_laboratorio
					FROM
						f_inspeccion.certificacionf13 f
						$this->tablaLaboratorio
                        LEFT JOIN f_inspeccion.certificacionf13_detalle_resultados fdr ON (fdr.id_padre = f.id),
						f_inspeccion.certificacionf13_detalle_guias fdg
					WHERE
						f.id = fdg.id_padre AND
						f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
						$busqueda
					ORDER BY
						f.fecha_inspeccion;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
		
	}

	public function generarReporteInspeccionFitoFrutosMuestreados($conexion, $fechaInicio, $fechaFin, $identificadorUsuario, $nombreUsuario, $tipoFormulario){
		
		$busqueda=$this->filtroGenerarReporteTecnicos($tipoFormulario,$identificadorUsuario,$nombreUsuario,'f.ruc_empresa_tratamiento','f.razon_social_empresa_tratamiento');
		$fechaFin = $this->calcularNuevaFechaFin($fechaFin);

		$consulta ="SELECT
						f.*,
						fdm.*
					FROM
						f_inspeccion.certificacionf12 f,
						f_inspeccion.certificacionf12_detalle_muestras fdm
					WHERE
						f.id = fdm.id_padre AND
						f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
						$busqueda
					ORDER BY
						f.fecha_inspeccion;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
		
	}

	private function filtroGenerarReporte($identificadorUsuario,$nombreUsuario,$campoRUC,$campoNombre){
		
		$busqueda="";
		
		if(isset($identificadorUsuario) && isset($nombreUsuario) ){
			$identificadorUsuario = $identificadorUsuario!="" ? "'" . $identificadorUsuario . "'" : "NULL";
			$nombreUsuario = $nombreUsuario!="" ? "'%" . $nombreUsuario . "%'" : "NULL";
			$busqueda=" AND ($identificadorUsuario is NULL or $campoRUC = $identificadorUsuario) AND ($nombreUsuario is NULL or $campoNombre ilike $nombreUsuario) ";
		}else{
			$busqueda=" AND $campoRUC='$identificadorUsuario'";
		}
		
		return $busqueda;
		
	}

	public function generarReporteOrnamentalesProtocoloRoyaBlanca($conexion, $fechaInicio, $fechaFin, $identificadorUsuario=null, $nombreUsuario=null){
		$busqueda=$this->filtroGenerarReporte($identificadorUsuario,$nombreUsuario,'f.ruc','f.razon_social');
		$fechaFin = $this->calcularNuevaFechaFin($fechaFin);
		
		$consulta ="SELECT
						f.*
					FROM
						f_inspeccion.certificacionf05 f
					WHERE
						f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
						$busqueda
					ORDER BY
						f.fecha_inspeccion;";

		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
		
	}

	public function generarReporteProtocoloAcaros($conexion, $fechaInicio, $fechaFin, $identificadorUsuario=null, $nombreUsuario=null){
		
		$busqueda=$this->filtroGenerarReporte($identificadorUsuario,$nombreUsuario,'f.ruc','f.razon_social');
		$fechaFin = $this->calcularNuevaFechaFin($fechaFin);

		$consulta = "SELECT
						f.*
					FROM
						f_inspeccion.certificacionf07 f
					WHERE
						f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
						AND f.estado_f07 = 'activo'
						$busqueda
					ORDER BY
						f.fecha_inspeccion;";
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
		
	}

	public function generarReporteOrnamentalesProtocoloMinador($conexion, $fechaInicio, $fechaFin, $identificadorUsuario=null, $nombreUsuario=null){
		
		$busqueda=$this->filtroGenerarReporte($identificadorUsuario,$nombreUsuario,'f.ruc','f.razon_social');
		$fechaFin = $this->calcularNuevaFechaFin($fechaFin);

		$consulta ="SELECT
						f.*
					FROM
						f_inspeccion.certificacionf08 f
					WHERE
						f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
						AND f.estado_f08 = 'activo'
						$busqueda
					ORDER BY
						f.fecha_inspeccion;";
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
		
	}

	public function generarReporteOrnamentalesProtocoloTrips($conexion, $fechaInicio, $fechaFin, $identificadorUsuario=null, $nombreUsuario=null){
		
		$busqueda=$this->filtroGenerarReporte($identificadorUsuario,$nombreUsuario,'f.ruc','f.razon_social');
		$fechaFin = $this->calcularNuevaFechaFin($fechaFin);
		
		$consulta ="SELECT
						f.*
					FROM
						f_inspeccion.certificacionf09 f
					WHERE
						f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
						AND f.estado_f09 = 'activo'
						$busqueda
					ORDER BY
						f.fecha_inspeccion;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
		
	}

	public function generarReporteProtocoloDesvitalizacion($conexion, $fechaInicio, $fechaFin, $identificadorUsuario=null, $nombreUsuario=null){
		
		$busqueda=$this->filtroGenerarReporte($identificadorUsuario,$nombreUsuario,'f.ruc_operador','f.nombre_operador');
		$fechaFin = $this->calcularNuevaFechaFin($fechaFin);
		
		$consulta ="SELECT
						f.*,
						fdp.*
					FROM
						f_inspeccion.certificacionf04 f,
						f_inspeccion.certificacionf04_detalle_productos fdp
					WHERE
						f.id = fdp.id_padre AND
						f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
						$busqueda
					ORDER BY
						f.fecha_inspeccion;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
		
	}

	public function consultaOperadoresActivarModuloConsultaInspecciones($conexion){
	
		$consulta ="SELECT 
						DISTINCT t1.ruc identificador_operador
					FROM 	(
							SELECT  distinct(f1.ruc) ruc FROM  f_inspeccion.certificacionf01 f1
								UNION ALL
							SELECT distinct(f2.ruc_exportador) ruc FROM f_inspeccion.certificacionf02 f2
								UNION ALL
							SELECT  distinct(f4.ruc_operador) ruc FROM  f_inspeccion.certificacionf04 f4
								UNION ALL
							SELECT  distinct(f5.ruc) ruc FROM  f_inspeccion.certificacionf05 f5
								UNION ALL
							SELECT  distinct(f7.ruc) ruc FROM  f_inspeccion.certificacionf07 f7
								UNION ALL
							SELECT  distinct(f8.ruc) ruc FROM  f_inspeccion.certificacionf08 f8
								UNION ALL
							SELECT  distinct(f9.ruc) ruc FROM  f_inspeccion.certificacionf09 f9
								UNION ALL
							SELECT  distinct(f10.ruc) ruc FROM  f_inspeccion.certificacionf10 f10
								UNION ALL
							SELECT  distinct(f11.ruc) ruc FROM  f_inspeccion.certificacionf11 f11
								UNION ALL
							SELECT  distinct(f12.ruc_empresa_tratamiento) ruc FROM  f_inspeccion.certificacionf12 f12
								UNION ALL
							SELECT  distinct(f13.ruc_agencia_carga) ruc FROM  f_inspeccion.certificacionf13 f13
						) as t1 
					WHERE 
						NOT EXISTS (
							SELECT ar.identificador
							FROM g_programas.aplicaciones a ,g_programas.aplicaciones_registradas ar, g_gestion_aplicaciones_perfiles.aplicaciones ap
							WHERE a.id_aplicacion= ar.id_aplicacion AND ap.codificacion_aplicacion=a.codificacion_aplicacion AND a.codificacion_aplicacion='PRG_CONSU_INSPE' AND t1.ruc=ar.identificador  AND ap.identificador=ar.identificador
						) ;";
	
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	
	}
	
}