<?php
 /**
 * Lógica del negocio de MovilizacionModelo
 *
 * Este archivo se complementa con el archivo MovilizacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-02
 * @uses    MovilizacionLogicaNegocio
 * @package MovilizacionVegetal
 * @subpackage Modelos
 */
  namespace Agrodb\MovilizacionVegetal\Modelos;
  
  use Agrodb\Core\JasperReport;
  use Agrodb\MovilizacionVegetal\Modelos\IModelo;
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  
 
class MovilizacionLogicaNegocio implements IModelo 
{

	 private $modeloMovilizacion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloMovilizacion = new MovilizacionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{    
	    switch ($datos['estado_movilizacion']){
	        
	        case '':{
	            $datos['identificador'] = $_SESSION['usuario'];
	            
	            $datos['estado_movilizacion'] = 'Vigente';
	            $datos['estado_fiscalizacion'] = 'No fiscalizado';
	            
	            $datos['hora_fin_movilizacion'] = $datos['hora_inicio_movilizacion'];
	            
	            break;
	        }
	        
	        default:{
	            break;
	        }
	    }
	    
		$tablaModelo = new MovilizacionModelo($datos);
		
		$datosBd = $tablaModelo->getPrepararDatos();
		
		if ($tablaModelo->getIdMovilizacion() != null && $tablaModelo->getIdMovilizacion() > 0) {
		  return $this->modeloMovilizacion->actualizar($datosBd, $tablaModelo->getIdMovilizacion());
		} else {
		  unset($datosBd["id_movilizacion"]);
		  return $this->modeloMovilizacion->guardar($datosBd);
	   }
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloMovilizacion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return MovilizacionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloMovilizacion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloMovilizacion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloMovilizacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarMovilizacion()
	{
	$consulta = "SELECT * FROM ".$this->modeloMovilizacion->getEsquema().". movilizacion";
		 return $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
	}

	public function buscarNumeroMovilizacion($codSitioOrigen, $codSitioDestino)
	{
	    
	    $anio = date("y");
	    $mes = date("m");
	    $dia = date("d");
	    
	    $formatoCodigo = $codSitioOrigen . $codSitioDestino . $dia . $mes . $anio;
	    
	    $consulta = "SELECT
                        max(secuencial_movilizacion) as numero
                     FROM
                        g_movilizacion_vegetal.movilizacion;";
	    
	    $codigo = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
	    $fila = $codigo->current();
	    
	    $codigoMovilizacion = array('numero' => $fila['numero']);
	    
	    $incremento = $codigoMovilizacion['numero'] + 1;
	    $codigoMovilizacion = $formatoCodigo . str_pad($incremento, 5, "0", STR_PAD_LEFT);
	    
	    return $codigoMovilizacion;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar movilizaciones usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarMovilizacionesXFiltro($arrayParametros)
	{
	    $busqueda = '';
	    	    
	    if (isset($arrayParametros['provinciaTecnico']) && ($arrayParametros['provinciaTecnico'] != '')) {
	        $busqueda .= "and upper(m.provincia_destino) ilike upper('%" . $arrayParametros['provinciaTecnico'] . "%')";
	    }
	    
	    $consulta = "  SELECT 
                        	m.id_movilizacion, m.identificador, m.numero_permiso, 
                        	m.id_provincia_origen, m.provincia_origen, m.identificador_operador_origen, m.nombre_operador_origen, m.sitio_origen,
                        	m.id_provincia_destino, m.provincia_destino, m.identificador_operador_destino, m.nombre_operador_destino, m.sitio_destino,
                        	m.estado_movilizacion
                        FROM 
                        	g_movilizacion_vegetal.movilizacion m
                        WHERE
                            m.estado_movilizacion = '".$arrayParametros['estado_movilizacion']."'
                            ".($arrayParametros['identificador_operador_origen'] != '' ? " and m.identificador_operador_origen ilike '".$arrayParametros['identificador_operador_origen']."%'" : "")."
                            ".($arrayParametros['nombre_operador_origen'] != '' ? " and upper(m.nombre_operador_origen) ilike upper('%".$arrayParametros['nombre_operador_origen']."%')" : "")."
                            ".($arrayParametros['sitio_origen'] != '' ? " and upper(m.sitio_origen) ilike upper('%".$arrayParametros['sitio_origen']."%')" : "")."
                            ".($arrayParametros['numero_permiso'] != '' ? " and m.numero_permiso ilike '%".$arrayParametros['numero_permiso']."%'" : "")."
                        	".($arrayParametros['fechaInicio'] != '' ? " and m.fecha_creacion >= '" . $arrayParametros['fechaInicio'] . " 00:00:00' " : "")."
                            ".($arrayParametros['fechaFin'] != '' ? " and m.fecha_creacion <= '" . $arrayParametros['fechaFin'] . " 24:00:00' " : "")."
                            ".$busqueda."
                        ORDER BY	
                        	m.numero_permiso ASC;";
	    
	    //echo $consulta;
	    return $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Función para crear el PDF del certificado
	 */
	public function generarCertificado($idMovilizacion, $nombreArchivo) {
	    $jasper = new JasperReport();
	    $datosReporte = array();
	    
	    $anio = date('Y');
	    $mes = date('m');
	    $dia = date('d');
	    
	    $ruta = MOV_VEG_CERT_URL_TCPDF . 'certificado/' . $anio . '/' . $mes . '/' . $dia . '/';
	    
	    if (! file_exists($ruta)){
	        mkdir($ruta, 0777, true);
	    }
	    
	    $datosReporte = array(
	        'rutaReporte' => 'MovilizacionVegetal/vistas/reportes/reporteCertificado.jasper',
	        'rutaSalidaReporte' => 'MovilizacionVegetal/archivos/certificado/'. $anio . '/' . $mes . '/' . $dia . '/' .$nombreArchivo,
	        'tipoSalidaReporte' => array('pdf'),
	        'parametrosReporte' => array(   'idMovilizacion' => $idMovilizacion,
                            	            'selloFirma'=> MOV_VEG_CERT_URL_IMG.'logoSeguridadCSM.gif',
	                                        'fondoCertificado'=> RUTA_IMG_GENE.'fondoCertificado.png'),
	        'conexionBase' => 'SI'
	    );
	    
	    $jasper->generarArchivo($datosReporte);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar movilizaciones usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarMovilizacionesFiscalizacionesXFiltro($arrayParametros)
	{
	    $busqueda = '';
	    
	    if (isset($arrayParametros['provinciaTecnico']) && ($arrayParametros['provinciaTecnico'] != '')) {
	        $busqueda .= "and upper(m.provincia_destino) ilike upper('%" . $arrayParametros['provinciaTecnico'] . "%')";
	    }
	    
	    if ((isset($arrayParametros['tipoBusqueda'])) && (isset($arrayParametros['tipoBusqueda']) != '')) {
	        
	        if($arrayParametros['tipoBusqueda'] === 'Origen'){
	            $busqueda .= ($arrayParametros['identificador_operador'] != '' ? " and m.identificador_operador_origen ilike '".$arrayParametros['identificador_operador']."%'" : "").
                             ($arrayParametros['nombre_operador'] != '' ? " and upper(m.nombre_operador_origen) ilike upper('%".$arrayParametros['nombre_operador']."%')" : "").
                             ($arrayParametros['sitio'] != '' ? " and upper(m.sitio_origen) ilike upper('%".$arrayParametros['sitio']."%')" : "")
                              ;
	        }else{
                $busqueda .= ($arrayParametros['identificador_operador'] != '' ? " and m.identificador_operador_destino ilike '".$arrayParametros['identificador_operador']."%'" : "").
            	            ($arrayParametros['nombre_operador'] != '' ? " and upper(m.nombre_operador_destino) ilike upper('%".$arrayParametros['nombre_operador']."%')" : "").
            	            ($arrayParametros['sitio'] != '' ? " and upper(m.sitio_destino) ilike upper('%".$arrayParametros['sitio']."%')" : "")
            	            ;
	        }
	    }                                                      
	    
	    $consulta = "  SELECT
                        	m.id_movilizacion, m.identificador, m.numero_permiso,
                        	m.id_provincia_origen, m.provincia_origen, m.identificador_operador_origen, m.nombre_operador_origen, m.sitio_origen,
                        	m.id_provincia_destino, m.provincia_destino, m.identificador_operador_destino, m.nombre_operador_destino, m.sitio_destino,
                        	m.estado_movilizacion, m.estado_fiscalizacion
                        FROM
                        	g_movilizacion_vegetal.movilizacion m
                        WHERE
                            m.estado_movilizacion in ('Vigente', 'Caducado', 'Anulado')
                            ".$busqueda."
                            ".($arrayParametros['numero_permiso'] != '' ? " and m.numero_permiso ilike '%".$arrayParametros['numero_permiso']."%'" : "")."
                        	".($arrayParametros['fechaInicio'] != '' ? " and m.fecha_creacion >= '" . $arrayParametros['fechaInicio'] . " 00:00:00' " : "")."
                            ".($arrayParametros['fechaFin'] != '' ? " and m.fecha_creacion <= '" . $arrayParametros['fechaFin'] . " 24:00:00' " : "")."                            
                        ORDER BY
                        	m.numero_permiso ASC;";
	    
	    //echo $consulta;
	    return $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
	}
	
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar información de cantones y parroquias de origen y destino.
	 *
	 * @return array|ResultSet
	 */
	public function buscarCantonParroquiaSitios($idMovilizacion)
	{
	    $consulta = "  SELECT
                        	so.canton canton_origen, 
            				so.parroquia parroquia_origen,                            
            				sd.canton canton_destino, 
            				sd.parroquia parroquia_destino
                        FROM
                        	g_movilizacion_vegetal.movilizacion m
                            INNER JOIN g_operadores.sitios so ON m.id_sitio_origen = so.id_sitio
                            INNER JOIN g_operadores.sitios sd ON m.id_sitio_destino = sd.id_sitio
                        WHERE
                            m.id_movilizacion = $idMovilizacion;";	    
	    
	    $sitio = $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
	    $fila = $sitio->current();
	    
	    $datosSitio = array(    'canton_origen' => $fila->canton_origen,
	                            'parroquia_origen' => $fila->parroquia_origen,
                    	        'canton_destino' => $fila->canton_destino,
                    	        'parroquia_destino' => $fila->parroquia_destino
                    	    );
	    
	    return $datosSitio;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar movilizaciones a nivel nacional usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarMovilizacionesNacionalXFiltro($arrayParametros)
	{
	    $busqueda = '';
	    
	    if (isset($arrayParametros['id_provincia']) && ($arrayParametros['id_provincia'] != '') && ($arrayParametros['id_provincia'] != 'Todas')) {
            $busqueda .= " and m.id_provincia_origen = '" . $arrayParametros['id_provincia'] . "'";
        }
	    
	    if (isset($arrayParametros['id_tipo_producto']) && ($arrayParametros['id_tipo_producto'] != '') && ($arrayParametros['id_tipo_producto'] != 'Seleccione....')) {
	        $busqueda .= " and tp.id_tipo_producto = " . $arrayParametros['id_tipo_producto'] . "";
	    }
	    
	    if (isset($arrayParametros['id_subtipo_producto']) && ($arrayParametros['id_subtipo_producto'] != '') && ($arrayParametros['id_subtipo_producto'] != 'Seleccione....')) {
	        $busqueda .= " and sp.id_subtipo_producto = " . $arrayParametros['id_subtipo_producto'] . "";
	    }

	    if (isset($arrayParametros['estado']) && ($arrayParametros['estado'] != '') && ($arrayParametros['estado'] != 'Todos')) {
	        $busqueda .= " and m.estado_movilizacion = '" . $arrayParametros['estado'] . "'";
	    }
	 
	    //$busqueda = ltrim($busqueda, " and");
	    
	    $consulta = " SELECT
                            m.id_movilizacion, 
                        	m.numero_permiso, 
                        	m.provincia_emision,  
                        	m.canton_emision, 
                        	m.oficina_emision, 
                        	(
                        	SELECT
                        		t.nombre
                        	FROM
                        		g_operadores.operaciones o
                        		INNER JOIN g_operadores.productos_areas_operacion pao ON o.id_operacion = pao.id_operacion
                        		INNER JOIN g_catalogos.tipos_operacion t ON o.id_tipo_operacion = t.id_tipo_operacion
                        	WHERE
                        		pao.id_area = dm.id_area_origen and
                        		o.id_producto = dm.id_producto and
                        		o.identificador_operador = m.identificador_operador_origen and
                        		t.id_area = 'SV'
                            LIMIT 1                                
                        	) as operacion_origen,
                        	m.id_provincia_origen,
                        	m.provincia_origen,  
                        	(
                        	SELECT
                        		l.id_localizacion
                        	FROM
                        		g_catalogos.localizacion l
                        	WHERE
                        		l.nombre = so.canton
                        	LIMIT 1
                        	) as id_canton_origen,
                        	so.canton canton_origen,
                        	(
                        	SELECT
                        		l.id_localizacion
                        	FROM
                        		g_catalogos.localizacion l
                        	WHERE
                        		l.nombre = so.parroquia
                        	LIMIT 1
                        	) as id_parroquia_origen,
                        	so.parroquia parroquia_origen,
                        	m.id_sitio_origen, 
                        	m.sitio_origen, 
                        	m.identificador_operador_origen, 
                        	m.nombre_operador_origen,
                        	m.provincia_destino, 
                        	sd.canton canton_destino,
                        	sd.parroquia parroquia_destino, 
                                m.sitio_destino, 
                        	m.identificador_operador_destino, 
                        	m.nombre_operador_destino,
                        	m.identificador identificador_responsable, 
                        	(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  FROM g_uath.ficha_empleado rsv WHERE m.identificador = rsv.identificador )
                        	 else (SELECT UPPER(( oa.nombre_representante::TEXT||' '::TEXT) || oa.apellido_representante::TEXT ) FROM g_operadores.operadores oa WHERE m.identificador = oa.identificador   ) end nombre_responsable
                        	 FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up
                        	 WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=m.identificador limit 1), 
                        	tp.id_tipo_producto,
                        	dm.id_subtipo_producto,
                        	dm.subtipo_producto,
                        	dm.id_producto,
                        	dm.producto,
                        	dm.area_origen,
                            dm.area_destino,
                        	dm.cantidad,
                        	m.identificador_conductor, 
                        	m.nombre_conductor,
                        	m.medio_transporte, 
                        	m.placa_transporte,  
                        	m.observacion_transporte,
                        	m.fecha_inicio_movilizacion,   
                        	m.fecha_fin_movilizacion,
                        	m.estado_movilizacion
                        FROM 
                        	g_movilizacion_vegetal.movilizacion m
                        	INNER JOIN g_movilizacion_vegetal.detalle_movilizacion dm ON m.id_movilizacion = dm.id_movilizacion
                        	INNER JOIN g_operadores.sitios so ON m.id_sitio_origen = so.id_sitio
                            INNER JOIN g_operadores.sitios sd ON m.id_sitio_destino = sd.id_sitio
                        	INNER JOIN g_catalogos.subtipo_productos sp ON dm.id_subtipo_producto = sp.id_subtipo_producto
                        	INNER JOIN g_catalogos.tipo_productos tp ON tp.id_tipo_producto = sp.id_tipo_producto
                        WHERE
                            m.fecha_creacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' and
	                        m.fecha_creacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00'
                            ". $busqueda ."
                        ORDER BY
                            m.numero_permiso ASC;";
	    
	    //echo $consulta;
	    
	    return $this->modeloMovilizacion->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta un reporte en Excel de las movilizaciones
	 *
	 * @return array|ResultSet
	 */
	public function exportarArchivoExcelMovilizaciones($datos){
	    
	    $hoja = new Spreadsheet();
	    $documento = $hoja->getActiveSheet();
	    $i = 3;
	    $j = 2;
	    
	    $documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Movilizaciones');
	    
	    $documento->setCellValueByColumnAndRow(1, $j, 'ID');
	    $documento->setCellValueByColumnAndRow(2, $j, 'Número Permiso');
	    $documento->setCellValueByColumnAndRow(3, $j, 'Provincia Emisión');
	    $documento->setCellValueByColumnAndRow(4, $j, 'Oficina Emisión');
	    $documento->setCellValueByColumnAndRow(5, $j, 'Operación Origen');
	    $documento->setCellValueByColumnAndRow(6, $j, 'Provincia Origen');
	    $documento->setCellValueByColumnAndRow(7, $j, 'Cantón Origen');
	    $documento->setCellValueByColumnAndRow(8, $j, 'Parroquia Origen');
	    $documento->setCellValueByColumnAndRow(9, $j, 'Sitio Origen');
	    $documento->setCellValueByColumnAndRow(10, $j, 'Área Origen');
	    $documento->setCellValueByColumnAndRow(11, $j, 'Identificación Operador Origen');
	    $documento->setCellValueByColumnAndRow(12, $j, 'Razón Social Operador Origen');
	    $documento->setCellValueByColumnAndRow(13, $j, 'Provincia Destino');
	    $documento->setCellValueByColumnAndRow(14, $j, 'Cantón Destino');
	    $documento->setCellValueByColumnAndRow(15, $j, 'Parroquia Destino');
	    $documento->setCellValueByColumnAndRow(16, $j, 'Sitio Destino');
	    $documento->setCellValueByColumnAndRow(17, $j, 'Área Destino');
	    $documento->setCellValueByColumnAndRow(18, $j, 'Identificación Operador Destino');
	    $documento->setCellValueByColumnAndRow(19, $j, 'Razón Social Operador Destino');
	    $documento->setCellValueByColumnAndRow(20, $j, 'Identificación Usuario Responsable');
	    $documento->setCellValueByColumnAndRow(21, $j, 'Nombre Usuario Responsable');
	    $documento->setCellValueByColumnAndRow(22, $j, 'Subtipo Producto');
	    $documento->setCellValueByColumnAndRow(23, $j, 'Producto');
	    $documento->setCellValueByColumnAndRow(24, $j, 'Cantidad');
	    $documento->setCellValueByColumnAndRow(25, $j, 'Identificación Conductor');
	    $documento->setCellValueByColumnAndRow(26, $j, 'Nombre Conductor');
	    $documento->setCellValueByColumnAndRow(27, $j, 'Medio Transporte');
	    $documento->setCellValueByColumnAndRow(28, $j, 'Placa de Transporte');
	    $documento->setCellValueByColumnAndRow(29, $j, 'Observación');
	    $documento->setCellValueByColumnAndRow(30, $j, 'Fecha Inicio Vigencia');
	    $documento->setCellValueByColumnAndRow(31, $j, 'Fecha Fin Vigencia');
	    $documento->setCellValueByColumnAndRow(32, $j, 'Estado');
	    
	    if($datos != ''){
    	    foreach ($datos as $fila){
    	        $documento->setCellValueByColumnAndRow(1, $i, $fila['id_movilizacion']);    	        
    	        $documento->getCellByColumnAndRow(2, $i)->setValueExplicit($fila['numero_permiso'], 's');
    	        $documento->setCellValueByColumnAndRow(3, $i, $fila['provincia_emision']);
    	        $documento->setCellValueByColumnAndRow(4, $i, $fila['oficina_emision']);
    	        $documento->setCellValueByColumnAndRow(5, $i, $fila['operacion_origen']);
    	        $documento->setCellValueByColumnAndRow(6, $i, $fila['provincia_origen']);
    	        $documento->setCellValueByColumnAndRow(7, $i, $fila['canton_origen']);
    	        $documento->setCellValueByColumnAndRow(8, $i, $fila['parroquia_origen']);
    	        $documento->setCellValueByColumnAndRow(9, $i, $fila['sitio_origen']);
    	        $documento->setCellValueByColumnAndRow(10, $i, $fila['area_origen']);
    	        $documento->getCellByColumnAndRow(11, $i)->setValueExplicit($fila['identificador_operador_origen'], 's');
    	        $documento->setCellValueByColumnAndRow(12, $i, $fila['nombre_operador_origen']);
    	        $documento->setCellValueByColumnAndRow(13, $i, $fila['provincia_destino']);
    	        $documento->setCellValueByColumnAndRow(14, $i, $fila['canton_destino']);
    	        $documento->setCellValueByColumnAndRow(15, $i, $fila['parroquia_destino']);
    	        $documento->setCellValueByColumnAndRow(16, $i, $fila['sitio_destino']);
    	        $documento->setCellValueByColumnAndRow(17, $i, $fila['area_destino']);
    	        $documento->getCellByColumnAndRow(18, $i)->setValueExplicit($fila['identificador_operador_destino'], 's');
    	        $documento->setCellValueByColumnAndRow(19, $i, $fila['nombre_operador_destino']);
    	        $documento->getCellByColumnAndRow(20, $i)->setValueExplicit($fila['identificador_responsable'], 's');
    	        $documento->setCellValueByColumnAndRow(21, $i, $fila['nombre_responsable']);
    	        $documento->setCellValueByColumnAndRow(22, $i, $fila['subtipo_producto']);
    	        $documento->setCellValueByColumnAndRow(23, $i, $fila['producto']);
    	        $documento->setCellValueByColumnAndRow(24, $i, $fila['cantidad']);
    	        $documento->getCellByColumnAndRow(25, $i)->setValueExplicit($fila['identificador_conductor'], 's');
    	        $documento->setCellValueByColumnAndRow(26, $i, $fila['nombre_conductor']);
    	        $documento->setCellValueByColumnAndRow(27, $i, $fila['medio_transporte']);
    	        $documento->setCellValueByColumnAndRow(28, $i, $fila['placa_transporte']);
    	        $documento->setCellValueByColumnAndRow(29, $i, $fila['observacion_transporte']);
    	        $documento->setCellValueByColumnAndRow(30, $i, ($fila['fecha_inicio_movilizacion']!=null?date('Y-m-d',strtotime($fila['fecha_inicio_movilizacion'])):''));
    	        $documento->setCellValueByColumnAndRow(31, $i, ($fila['fecha_fin_movilizacion']!=null?date('Y-m-d',strtotime($fila['fecha_fin_movilizacion'])):''));
    	        $documento->setCellValueByColumnAndRow(32, $i, $fila['estado_movilizacion']);
    	        
    	        $i++;
    	    }
	    }
	    
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment;filename="excelMovilizaciones.xlsx"');
	    header("Pragma: no-cache");
	    header("Expires: 0");
	    
	    $writer = IOFactory::createWriter($hoja, 'Xlsx');
	    $writer->save('php://output');
	    exit();
	}
}
