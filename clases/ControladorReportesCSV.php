<?php

class ControladorReportesCSV
{

    private $etiquetaTodos = 'TODOS';

    private $camposTablaLaboratorio = '';
    private $tablaLaboratorio = '';
    private $condicionLaboratorio = '';

    public $camposLaboratorio = array(
        //'actividad_origen',
        //'analisis',
        'codigo_muestra',
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

    private function obtenerComentariosColumnas($conexion, $tabla)
    {
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

    public function construirEncabezadoReporte($conexion, $tabla, $campos)
    {
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

    public function generarReporteInspeccionProductosImportados($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin)
    {
        if ($incluirDatosLaboratorio) {
            $this->camposTablaLaboratorio = ', fdo.*';
            $this->tablaLaboratorio = ' LEFT JOIN f_inspeccion.controlf01_detalle_ordenes fdo ON (fdo.id_padre = f.id)';
        }
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
                $this->camposTablaLaboratorio
            FROM
                f_inspeccion.controlf01 f
                $this->tablaLaboratorio
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteInspeccionProductosImportadosPorIncumplimiento($conexion, $fechaInicio, $fechaFin, $pais)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $where = '';
        if ($pais !== 'TODOS') {
            $where .= " AND upper(dda.pais_exportacion) = upper('$pais')";
        }

        $consulta = "
                SELECT
                  upper(dda.pais_exportacion) AS pais_exportacion, 
                  -- POSTGRESQL 9.3
                  count(CASE WHEN f.pregunta03 = 'No' THEN 1 END) AS pregunta03,
                  count(CASE WHEN f.pregunta04 = 'No' THEN 1 END) AS pregunta04,
                  count(CASE WHEN f.pregunta05 = 'No' THEN 1 END) AS pregunta05,
                  count(CASE WHEN f.pregunta06 = 'No' THEN 1 END) AS pregunta06,
                  count(CASE WHEN f.pregunta07 = 'No' THEN 1 END) AS pregunta07,
                  count(CASE WHEN f.pregunta08 = 'No' THEN 1 END) AS pregunta08,
                  -- POSTGRESQL 9.4 +
                  -- count(*) FILTER(WHERE f.pregunta03 = 'No') AS pregunta03,
                  -- count(*) FILTER(WHERE f.pregunta04 = 'No') AS pregunta04,
                  -- count(*) FILTER(WHERE f.pregunta05 = 'No') AS pregunta05,
                  -- count(*) FILTER(WHERE f.pregunta06 = 'No') AS pregunta06,
                  -- count(*) FILTER(WHERE f.pregunta07 = 'No') AS pregunta07,
                  -- count(*) FILTER(WHERE f.pregunta08 = 'No') AS pregunta08,
                  sum(fdl_f.ausencia_suelo) AS ausencia_suelo,
                  sum(fdl_f.ausencia_contaminantes) AS ausencia_contaminantes,
                  sum(fdl_f.ausencia_sintomas) AS ausencia_sintomas,
                  sum(fdl_f.ausencia_plagas) AS ausencia_plagas
                FROM
                  f_inspeccion.controlf01 f,
                  g_dda.destinacion_aduanera dda,
                  (
                  SELECT
                    fdl.id_padre,
                    -- POSTGRESQL 9.3
                    CASE WHEN count(CASE WHEN fdl.ausencia_suelo = 'No' THEN 1 END) > 0 THEN 1 ELSE 0 END AS ausencia_suelo,
                    CASE WHEN count(CASE WHEN fdl.ausencia_contaminantes = 'No' THEN 1 END) > 0 THEN 1 ELSE 0 END AS ausencia_contaminantes,
                    CASE WHEN count(CASE WHEN fdl.ausencia_sintomas = 'No' THEN 1 END) > 0 THEN 1 ELSE 0 END AS ausencia_sintomas,
                    CASE WHEN count(CASE WHEN fdl.ausencia_plagas = 'No' THEN 1 END) > 0 THEN 1 ELSE 0 END AS ausencia_plagas
                    -- POSTGRESQL 9.4 +
                    -- CASE WHEN count(*) FILTER(WHERE fdl.ausencia_suelo = 'No') > 0 THEN 1 ELSE 0 END AS ausencia_suelo,
                    -- CASE WHEN count(*) FILTER(WHERE fdl.ausencia_contaminantes = 'No') > 0 THEN 1 ELSE 0 END AS ausencia_contaminantes,
                    -- CASE WHEN count(*) FILTER(WHERE fdl.ausencia_sintomas = 'No') > 0 THEN 1 ELSE 0 END AS ausencia_sintomas,
                    -- CASE WHEN count(*) FILTER(WHERE fdl.ausencia_plagas = 'No') > 0 THEN 1 ELSE 0 END AS ausencia_plagas
                  FROM
                    f_inspeccion.controlf01_detalle_lotes fdl
                  GROUP BY
                    fdl.id_padre
                  ) AS fdl_f
                WHERE
                  f.dda = dda.id_vue
                  AND f.id = fdl_f.id_padre
                  AND f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
                  $where
                GROUP BY
                  dda.pais_exportacion
                ORDER BY
                  dda.pais_exportacion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteCantidadProductosImportadosPorPais($conexion, $fechaInicio, $fechaFin, $pais, $producto, $subtipo)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $where = '';
        if ($pais !== 'TODOS') {
            $where .= " AND upper(dda.pais_exportacion) = upper('$pais')";
        }

        if ($producto !== 'TODOS') {
            $where .= " AND upper(fdps.nombre) = upper('$producto')";
        }

        if ($subtipo !== 'TODOS') {
            $where .= " AND upper(fdps.subtipo) = upper('$subtipo')";
        }

        $consulta = "
                SELECT
                  dda.pais_exportacion,
                  fdps.nombre,
                  f.dictamen_final,
                  sum(fdps.cantidad_declarada) AS cantidad_declarada,
                  sum(fdps.cantidad_ingresada) AS cantidad_ingresada
                FROM
                  f_inspeccion.controlf01 f,
                  f_inspeccion.controlf01_detalle_productos_ingresados fdps,
                  g_dda.destinacion_aduanera dda
                WHERE
                  f.dda = dda.id_vue
                  AND f.id = fdps.id_padre
                  AND f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
                  $where
                GROUP BY
                  dda.pais_exportacion,
                  fdps.nombre,
                  f.dictamen_final
                ORDER BY
                  dda.pais_exportacion,
                  fdps.nombre,
                  f.dictamen_final;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteIncumplimientoEmbalajeMaderaPorPais($conexion, $fechaInicio, $fechaFin, $pais, $puntoControl)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $where = '';
        if ($pais !== 'TODOS') {
            $where .= " AND upper(f.pais_origen) = upper('$pais')";
        }

        if ($puntoControl !== 'TODOS') {
            $where .= " AND upper(f.punto_control) = upper('$puntoControl')";
        }

        $consulta = "SELECT
                      f.id,
                      upper(f.pais_origen) pais_origen, 
                      f.punto_control,
                      -- POSTGRES 9.3
                      count(CASE WHEN f.marca_autorizada = 'No' THEN 1 END) AS marca_autorizada,
                      count(CASE WHEN f.marca_legible = 'No' THEN 1 END) AS marca_legible,
                      count(CASE WHEN f.ausencia_dano_insectos = 'No' THEN 1 END) AS ausencia_dano_insectos,
                      count(CASE WHEN f.ausencia_insectos_vivos = 'No' THEN 1 END) AS ausencia_insectos_vivos,
                      count(CASE WHEN f.ausencia_corteza = 'No' THEN 1 END) AS ausencia_corteza
                      -- POSTGRES 9.4 +
                      -- count(*) filter(where f.marca_autorizada = 'No') AS marca_autorizada,
                      -- count(*) filter(where f.marca_legible = 'No') AS marca_legible,
                      -- count(*) filter(where f.ausencia_dano_insectos = 'No') AS ausencia_dano_insectos,
                      -- count(*) filter(where f.ausencia_insectos_vivos = 'No') AS ausencia_insectos_vivos,
                      -- count(*) filter(where f.ausencia_corteza = 'No') AS ausencia_corteza
                    FROM
                      f_inspeccion.controlf03 f
                    WHERE
                      f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
                      $where
                    GROUP BY
                      f.id,
                      f.pais_origen, 
                      f.punto_control
                    ORDER BY
                      f.pais_origen, 
                      f.punto_control;";
        
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteGeneralTrampeoMosca($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin)
    {
        if ($incluirDatosLaboratorio) {
            $this->camposTablaLaboratorio = ', fdo.*';
            $this->tablaLaboratorio = ' LEFT JOIN f_inspeccion.moscaf01_detalle_ordenes fdo ON (fdo.id_padre = f.id_padre AND fdo.codigo_muestra like CONCAT(f.codigo_trampa,\'%\'))';
        }
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
                $this->camposTablaLaboratorio
            FROM
                f_inspeccion.moscaf01_detalle_trampas f
                $this->tablaLaboratorio
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarProductosInspeccionadosControlF01($conexion)
    {
        $consulta = "
            SELECT
                DISTINCT  fdpi.nombre, fdpi.subtipo
            FROM
                f_inspeccion.controlf01_detalle_productos_ingresados fdpi
            ORDER BY
                fdpi.nombre;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarPuntosControlControlF03($conexion)
    {
        $consulta = "
            SELECT
                DISTINCT f.punto_control
            FROM
                f_inspeccion.controlf03 f
            ORDER BY
                f.punto_control;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteGeneralCaracterizacionFruticola($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin, $criterio = 'fecha_inspeccion')
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
            FROM
                f_inspeccion.moscaf02 f
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.$criterio;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarSubtipoProductoControlF01($conexion)
    {
        $consulta = "
            SELECT
                DISTINCT f.subtipo
            FROM
                f_inspeccion.controlf01_detalle_productos_ingresados f
            ORDER BY
                1;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarActividadesVigilanciaF02($conexion)
    {
        $consulta = "
            SELECT
                DISTINCT f.actividad
            FROM
                f_inspeccion.vigilanciaf02 f
            ORDER BY
                1;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarEspeciesVegetalesVigilanciaF02($conexion)
    {
        $consulta = "
            SELECT
                DISTINCT f.especie_vegetal
            FROM
                f_inspeccion.vigilanciaf02 f
            ORDER BY
                1;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarDiagnosticosVisualesVigilanciaF02($conexion)
    {
        $consulta = "
            SELECT
                DISTINCT f.diagnostico_visual
            FROM
                f_inspeccion.vigilanciaf02 f
            ORDER BY
                1;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarIncidenciasVigilanciaF02($conexion)
    {
        $consulta = "
            SELECT
                DISTINCT f.porcentaje_incidencia
            FROM
                f_inspeccion.vigilanciaf02 f
            ORDER BY
                1;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarSeveridadesVigilanciaF02($conexion)
    {
        $consulta = "
            SELECT
                DISTINCT f.porcentaje_severidad
            FROM
                f_inspeccion.vigilanciaf02 f
            ORDER BY
                1;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteGeneralMonitoreoVigilancia($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin, $actividad, $especie, $diagnostico, $incidencia, $severidad)
    {
        if ($incluirDatosLaboratorio) {
            $this->camposTablaLaboratorio = ', fdo.*';
            $this->tablaLaboratorio = ' LEFT JOIN f_inspeccion.vigilanciaf02_detalle_ordenes fdo ON (fdo.id_padre = f.id)';
        }
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $rangoIncidencia = array(
            'rangoIncidencia1' => 'porcentaje_incidencia < 15',
            'rangoIncidencia2' => 'porcentaje_incidencia >= 15 AND porcentaje_incidencia < 25',
            'rangoIncidencia3' => 'porcentaje_incidencia >= 25');
        $rangoSeveridad = array(
            'rangoSeveridad1' => 'porcentaje_severidad < 15',
            'rangoSeveridad2' => 'porcentaje_severidad >= 15 AND porcentaje_severidad < 25',
            'rangoSeveridad3' => 'porcentaje_severidad >= 25');

        $where = '';
        if ($actividad !== $this->etiquetaTodos) {
            $where .= " AND upper(actividad) = upper('$actividad')";
        }
        if ($especie !== $this->etiquetaTodos) {
            $where .= " AND upper(especie_vegetal) = upper('$especie')";
        }
        if ($diagnostico !== $this->etiquetaTodos) {
            $where .= " AND upper(diagnostico_visual) = upper('$diagnostico')";
        }
        if ($incidencia !== $this->etiquetaTodos) {
            $where .= " AND " . $rangoIncidencia[$incidencia];
        }
        if ($severidad !== $this->etiquetaTodos) {
            $where .= " AND " . $rangoSeveridad[$severidad];
        }

        $consulta = "SELECT
                        f.*
                        $this->camposTablaLaboratorio
                    FROM
                      f_inspeccion.vigilanciaf02 f
                      $this->tablaLaboratorio
                    WHERE
                      f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
                      $where
                    ORDER BY
                      f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;

    }

    public function generarReporteGeneralTrampeoVigilancia($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin)
    {
        if ($incluirDatosLaboratorio) {
            $this->camposTablaLaboratorio = ', fdo.*';
            $this->tablaLaboratorio = ' LEFT JOIN f_inspeccion.vigilanciaf01_detalle_ordenes fdo ON (fdo.id_padre = f.id_padre  AND fdo.codigo_muestra like CONCAT(f.codigo_trampa,\'%\'))';
        }
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
                $this->camposTablaLaboratorio
            FROM
                f_inspeccion.vigilanciaf01_detalle_trampas f
                $this->tablaLaboratorio
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarProvinciasVigilanciaF01($conexion)
    {
        $consulta = "SELECT
                        DISTINCT f.nombre_provincia
                    FROM
                      f_inspeccion.vigilanciaf01_detalle_trampas f
                    ORDER BY
                      1;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }


    public function listarCantonesVigilanciaF01($conexion)
    {
        $consulta = "SELECT
                        DISTINCT f.nombre_canton, f.nombre_provincia
                    FROM
                      f_inspeccion.vigilanciaf01_detalle_trampas f
                    ORDER BY
                      1;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarPlagasVigilanciaF01($conexion)
    {
        $consulta = "SELECT
                        DISTINCT f.diagnostico_visual
                    FROM
                      f_inspeccion.vigilanciaf01_detalle_trampas f
                    ORDER BY
                      1;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarEspeciesVigilanciaF01($conexion)
    {
        $consulta = "SELECT
                        DISTINCT f.especie
                    FROM
                      f_inspeccion.vigilanciaf01_detalle_trampas f
                    ORDER BY
                      1;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteBananoProtocoloEscamas($conexion, $fechaInicio, $fechaFin)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
            FROM
                f_inspeccion.certificacionf03 f
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteInspeccionMoluscosPlagaFincas($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin)
    {
        if ($incluirDatosLaboratorio) {
            $this->camposTablaLaboratorio = ', fdo.*';
            $this->tablaLaboratorio = ' f_inspeccion.certificacionf01_detalle_ordenes fdo';
            $this->condicionLaboratorio = ' fdo.id_padre = f.id';
        }
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*,
                fdg.grupo,
                fdg.numero_caracoles
                $this->camposTablaLaboratorio
            FROM
                f_inspeccion.certificacionf01 f INNER JOIN f_inspeccion.certificacionf01_detalle_grupos fdg ON  f.id = fdg.id_padre
                LEFT JOIN $this->tablaLaboratorio ON $this->condicionLaboratorio     
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'               
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteOrnamentalesProtocoloRoyaBlanca($conexion, $fechaInicio, $fechaFin)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
            FROM
                f_inspeccion.certificacionf05 f
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteChequeoProtocoloEnvioLibreCochinilla($conexion, $fechaInicio, $fechaFin)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
            FROM
                f_inspeccion.certificacionf06 f
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteProtocoloAcaros($conexion, $fechaInicio, $fechaFin)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
            FROM
                f_inspeccion.certificacionf07 f
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
				AND f.estado_f07 = 'activo'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteOrnamentalesProtocoloMinador($conexion, $fechaInicio, $fechaFin)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
            FROM
                f_inspeccion.certificacionf08 f
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
				AND f.estado_f08 = 'activo'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteOrnamentalesProtocoloTrips($conexion, $fechaInicio, $fechaFin)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
            FROM
                f_inspeccion.certificacionf09 f
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
				AND f.estado_f09 = 'activo'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteProtocoloDesvitalizacion($conexion, $fechaInicio, $fechaFin)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*,
                fdp.*
            FROM
                f_inspeccion.certificacionf04 f,
                f_inspeccion.certificacionf04_detalle_productos fdp
            WHERE
                f.id = fdp.id_padre AND
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteGeneralMuestreoFrutos($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin)
    {
        if ($incluirDatosLaboratorio) {
            $this->camposTablaLaboratorio = ', fdo.*';
            $this->tablaLaboratorio = ' LEFT JOIN f_inspeccion.moscaf03_detalle_ordenes fdo ON (fdo.id_padre = f.id)';
        }
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
                $this->camposTablaLaboratorio
            FROM
                f_inspeccion.moscaf03 f
                $this->tablaLaboratorio             
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteCalificacionLotesCacaoGrano($conexion, $fechaInicio, $fechaFin)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
            FROM
                f_inspeccion.certificacionf10 f             
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteInspeccionFitoFrutosMuestreados($conexion, $fechaInicio, $fechaFin)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*,
                fdm.*
            FROM
                f_inspeccion.certificacionf12 f,
                f_inspeccion.certificacionf12_detalle_muestras fdm                
            WHERE
                f.id = fdm.id_padre AND
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteInspeccionCertificacionFitoPPVAR($conexion, $fechaInicio, $fechaFin)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
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
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteInspeccionAgenciasCarga($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        if ($incluirDatosLaboratorio) {
            $this->camposTablaLaboratorio = ', fdo.*';
            $this->tablaLaboratorio = ' LEFT JOIN f_inspeccion.certificacionf13_detalle_ordenes fdo ON (fdo.id_padre = f.id)';
        }
        $consulta = "
            SELECT
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
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteSeguimientoCuarentenario($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin)
    {
        if ($incluirDatosLaboratorio) {
            $this->camposTablaLaboratorio = ', fdo.*';
            $this->tablaLaboratorio = ' LEFT JOIN f_inspeccion.controlf04_detalle_ordenes fdo ON (fdo.id_padre = f.id)';
        }
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                fecha_inspeccion, razon_social, nombre_scpe, usuario, actividad, pais_origen, subtipo_producto, producto, peso, tipo_operacion,
                tipo_cuarentena_condicion_produccion, fase_seguimiento, codigo_lote, numero_seguimientos_planificados, numero_plantas_ingreso,
                numero_plantas_inspeccion, registro_monitoreo_plagas, ausencia_plagas, cantidad_afectada, porcentaje_incidencia, porcentaje_severidad,
                fase_desarrollo_plaga, organo_afectado, distribucion_plaga, envio_muestra, resultado_inspeccion, observaciones, provincia_seguimiento as provincia
                $this->camposTablaLaboratorio
            FROM
                f_inspeccion.controlf04 f
                INNER JOIN g_seguimiento_cuarentenario.seguimientos_cuarentenarios sc ON f.id_seguimiento_cuarentenario::integer = sc.id_seguimiento_cuarentenario
		        INNER JOIN g_dda.destinacion_aduanera da ON sc.id_destinacion_aduanera = da.id_destinacion_aduanera
                $this->tablaLaboratorio
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteTransitoInternacional($conexion, $fechaInicio, $fechaFin)
    {
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*,
                fdp.*
            FROM
                f_inspeccion.controlf02 f,
                f_inspeccion.controlf02_detalle_productos fdp
            WHERE
                fdp.id_padre = f.id AND
                f.fecha_ingreso BETWEEN '$fechaInicio' AND '$fechaFin'
				AND f.estado_cf02 = 'activo'
            ORDER BY
                f.fecha_ingreso;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function generarReporteRechazoEmbalajeMadera($conexion, $incluirDatosLaboratorio, $fechaInicio, $fechaFin)
    {
        if ($incluirDatosLaboratorio) {
            $this->camposTablaLaboratorio = ', fdo.*';
            $this->tablaLaboratorio = ' LEFT JOIN f_inspeccion.certificacionf02_detalle_ordenes fdo ON (fdo.id_padre = f.id)';
        }
        $fechaFin = $this->calcularNuevaFechaFin($fechaFin);
        $consulta = "
            SELECT
                f.*
                $this->camposTablaLaboratorio
            FROM
                f_inspeccion.certificacionf02 f
                $this->tablaLaboratorio
            WHERE
                f.fecha_inspeccion BETWEEN '$fechaInicio' AND '$fechaFin'
            ORDER BY
                f.fecha_inspeccion;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    private function calcularNuevaFechaFin($fechaFin)
    {
        return $fechaFin . ' 23:59:59';
    }
	
	
	    //////////  INICIO ** REPORTE DE OEPRADORES **

    public function generarReporteDeOperadoresPorProvincia($conexion, $arrayParametros)
    {
       
        $provincia = '';
        $tipoProducto = '';
        $subtipoProducto = '';
        $producto = '';
        $fecha = '';        

        if(isset($arrayParametros['fechaInicio'])){
            if($arrayParametros['estado']== 'registrado'){
                $fecha = "and (op.fecha_aprobacion >= '".$arrayParametros['fechaInicio']."' and op.fecha_aprobacion <= '".$arrayParametros['fechaFin']."')";
            } else{
                $fecha = "and (op.fecha_modificacion >= '".$arrayParametros['fechaInicio']."' and op.fecha_modificacion <= '".$arrayParametros['fechaFin']."')";
            }
        }        

        if(isset($arrayParametros['provincia'])){
            $provincia = "and upper(s.provincia) = upper('".$arrayParametros['provincia']."')";
        }

        if(isset($arrayParametros['tipoProducto'])){
            $tipoProducto = "and tpr.id_tipo_producto = ".$arrayParametros['tipoProducto'];
        }

        if(isset($arrayParametros['subtipoProducto'])){
            $subtipoProducto = "and stp.id_subtipo_producto = ".$arrayParametros['subtipoProducto'];
        }

        if(isset($arrayParametros['producto'])){
            $producto = "and op.id_producto = ".$arrayParametros['producto'];
        }

        
        $consulta = "SELECT 
                        distinct o.identificador,
                        RTRIM(REPLACE(REPLACE(REPLACE(o.razon_social,chr(9),''),chr(10),''),chr(13),'')) as razon_social,
                        o.nombre_representante ||' '||o.apellido_representante as nombres_representante,
                        o.nombre_tecnico||' '||o.apellido_tecnico as nombres_tecnico,
                        REPLACE(REPLACE(REPLACE(o.direccion,chr(9),''),chr(10),''),chr(13),'') as direccion,
                        o.telefono_uno ||'-'|| o.telefono_dos as telefonos,
                        o.celular_uno ||'-'||o.celular_dos as celulares,
                        REPLACE(REPLACE(REPLACE(o.correo,chr(9),''),chr(10),''),chr(13),'') as correo,
                        op.id_tipo_operacion,
                        op.estado, 
                        REPLACE(REPLACE(REPLACE(op.observacion,chr(9),''),chr(10),''),chr(13),'') as observacion,
                        op.id_producto,
                        op.id_vue,
                        op.nombre_pais,
                        REPLACE(REPLACE(REPLACE(pr.nombre_comun,chr(9),''),chr(10),''),chr(13),'') as nombre_comun,
                        stp.nombre as subtipo_producto,
                        tpr.nombre as tipo_producto,
                        REPLACE(REPLACE(REPLACE(tp.nombre,chr(9),''),chr(10),''),chr(13),'') as tipo_operacion,
                        op.fecha_creacion,
                        op.fecha_modificacion,
                        CASE WHEN op.estado = 'registrado'  THEN op.fecha_aprobacion
                        END as fecha_aprobacion,
                        REPLACE(REPLACE(REPLACE(a.nombre_area,chr(9),''),chr(10),''),chr(13),'') as nombre_area,
                        REPLACE(REPLACE(REPLACE(a.tipo_area,chr(9),''),chr(10),''),chr(13),'') as tipo_area,
                        a.superficie_utilizada ||' '|| ap.unidad_medida as superficie_utilizada,
                        a.estado as estado_area,
                        a.codigo||a.secuencial as codigo_area,
                        REPLACE(REPLACE(REPLACE(s.nombre_lugar,chr(9),''),chr(10),''),chr(13),'') as nombre_sitio,
                        REPLACE(REPLACE(REPLACE(s.direccion,chr(9),''),chr(10),''),chr(13),'') as direccion_sitio,
                        REPLACE(REPLACE(REPLACE(s.telefono,chr(9),''),chr(10),''),chr(13),'') as telefono,
                        REPLACE(REPLACE(REPLACE(s.referencia,chr(9),''),chr(10),''),chr(13),'') as referencia,
                        s.parroquia,
                        s.canton,
                        s.provincia,
                        s.codigo_provincia||s.codigo as codigo_sitio,
                        s.latitud,
                        s.longitud,
                        s.superficie_total ||' '|| 'm2' as superficie_total	
                    FROM
                        g_operadores.operadores o 
                        INNER JOIN g_operadores.sitios s ON s.identificador_operador = o.identificador
                        INNER JOIN g_operadores.areas a ON a.id_sitio = s.id_sitio
                        INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area
                        INNER JOIN g_operadores.operaciones op ON op.id_operacion = pao.id_operacion
                        INNER JOIN g_catalogos.tipos_operacion tp ON tp.id_tipo_operacion = op.id_tipo_operacion  
                        INNER JOIN g_catalogos.areas_operacion ap ON ap.id_tipo_operacion = tp.id_tipo_operacion
                        LEFT JOIN g_catalogos.productos pr ON pr.id_producto = op.id_producto
                        LEFT JOIN g_catalogos.subtipo_productos stp ON stp.id_subtipo_producto = pr.id_subtipo_producto 
                        LEFT JOIN g_catalogos.tipo_productos tpr ON tpr.id_tipo_producto = stp.id_tipo_producto
                    WHERE
                        op.estado = '".$arrayParametros['estado']."'
                        and op.id_tipo_operacion IN (".$arrayParametros['tipoOperacion'].")
                        $provincia
                        $tipoProducto
                        $subtipoProducto
                        $producto
                        $fecha
                        ";
       
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    //////////  FIN ** REPORTE DE OEPRADORES **
	
	
	
	//////////  INICIO ** REPORTE DE SEGUIMIENTO CUARENTENARIO SA **

    public function generarReporteSeguimientoCuarentenarioSA($conexion, $provincia, $estado, $producto, $fechaInicio, $fechaFin)
    {
        $estadoNotificado = $estado;
        $provincia = $provincia != '' ? "and upper(dd.provincia_seguimiento) = upper('$provincia')" : "";
        $estado = $estado != '' ? "and c.estado = '$estado' " : "";
        $producto = $producto != '' ? "and ddp.id_producto = $producto " : "";


        $union="";
        $consultaNotificados="";
       

        if($estadoNotificado == "" || $estadoNotificado == "notificado"){

            $union="UNION ALL";

            $consultaNotificados = "(SELECT
                                    dd.id_destinacion_aduanera,
                                    dd.id_vue,	
                                    null::character varying csmt,
                                    dd.fecha_arribo fecha_ingreso_ecuador,                        	
                                    'notificado'::character varying estado,
                                    null::timestamp without time zone fecha_cierre,	
                                    (select sum(dpp.unidad) from g_dda.destinacion_aduanera_productos dpp where dpp.id_destinacion_aduanera = dd.id_destinacion_aduanera) cantidad,	
                                    null::integer cantidad_total_seguimiento,
                                    ddp.nombre_producto,
                                    dd.provincia_seguimiento 
                                
                                FROM 
                                    g_dda.destinacion_aduanera dd 
                                    INNER JOIN g_dda.destinacion_aduanera_productos ddp on dd.id_destinacion_aduanera = ddp.id_destinacion_aduanera 
                                WHERE
                                    dd.fecha_arribo >= '$fechaInicio'
                                    and dd.fecha_arribo <= '$fechaFin'
                                    and dd.estado_seguimiento ='TRUE'
                                    and dd.estado='aprobado' 
                                    and dd.tipo_certificado='ANIMAL'
                                    and dd.proposito='Importación'
                                    $provincia
                                    $producto
                                    order by 1 ASC
                                ) 
            ";
        }        

        $consulta="(SELECT 	
                        dd.id_destinacion_aduanera,
                        dd.id_vue,	
                        c.csmt,	
                        c.fecha_ingreso_ecuador,	
                        c.estado,	
                        c.fecha_cierre,		
                        (select sum(dpp.unidad) from g_dda.destinacion_aduanera_productos dpp where dpp.id_destinacion_aduanera = dd.id_destinacion_aduanera) cantidad,
                        (select cs.cantidad_total_seguimiento from g_seguimiento_cuarentenario.detalle_seguimientos_cuarentenarios_sa cs where cs.id_seguimiento_cuarentenario_sa = c.id_seguimiento_cuarentenario_sa order by cs.id_detalle_seguimientos_cuarentenarios_sa desc limit 1 ),	
                        ddp.nombre_producto,
                        dd.provincia_seguimiento 	
                    FROM	
                        g_dda.destinacion_aduanera dd inner join g_seguimiento_cuarentenario.seguimientos_cuarentenarios_sa c on dd.id_destinacion_aduanera = c.id_destinacion_aduanera	
                        INNER JOIN g_dda.destinacion_aduanera_productos ddp on dd.id_destinacion_aduanera = ddp.id_destinacion_aduanera 
                    WHERE
                        c.fecha_ingreso_ecuador >= '$fechaInicio'
                        and c.fecha_ingreso_ecuador <= '$fechaFin'
                        and dd.estado = 'aprobado'
                        and dd.estado_seguimiento = 'false'
                        and dd.tipo_certificado='ANIMAL'
                        $provincia
                        $estado
                        $producto                                                                      
                    ORDER BY 1 ASC )                   
                    $union
                    $consultaNotificados";
       
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    
    }


    //////////  FIN ** REPORTE DE SEGUIMIENTO CUARENTENARIO  SA **
	
}

