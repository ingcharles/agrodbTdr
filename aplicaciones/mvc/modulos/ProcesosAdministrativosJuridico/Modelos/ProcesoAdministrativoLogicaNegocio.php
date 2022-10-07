<?php
 /**
 * Lógica del negocio de ProcesoAdministrativoModelo
 *
 * Este archivo se complementa con el archivo ProcesoAdministrativoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-03-17
 * @uses    ProcesoAdministrativoLogicaNegocio
 * @package ProcesosAdministrativosJuridico
 * @subpackage Modelos
 */
  namespace Agrodb\ProcesosAdministrativosJuridico\Modelos;
  use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
class ProcesoAdministrativoLogicaNegocio implements IModelo 
{

	 private $modeloProcesoAdministrativo = null;
	 private $excelPhp = null;

	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloProcesoAdministrativo = new ProcesoAdministrativoModelo();
	 $this->excelPhp = new ReportesExcelModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ProcesoAdministrativoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdProcesoAdministrativo() != null && $tablaModelo->getIdProcesoAdministrativo() > 0) {
		return $this->modeloProcesoAdministrativo->actualizar($datosBd, $tablaModelo->getIdProcesoAdministrativo());
		} else {
		unset($datosBd["id_proceso_administrativo"]);
		return $this->modeloProcesoAdministrativo->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloProcesoAdministrativo->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ProcesoAdministrativoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloProcesoAdministrativo->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloProcesoAdministrativo->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloProcesoAdministrativo->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarProcesoAdministrativo()
	{
	$consulta = "SELECT * FROM ".$this->modeloProcesoAdministrativo->getEsquema().". proceso_administrativo";
		 return $this->modeloProcesoAdministrativo->ejecutarSqlNativo($consulta);
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener el secuencial
	 *
	 * @return array
	 */
	public function obtenerSecuencialProceso($arrayParametros){
	    $consulta = "SELECT
						COALESCE(count(*)::numeric, 0)+1 AS numero
					FROM
						".$this->modeloProcesoAdministrativo->getEsquema().". proceso_administrativo
					WHERE
						provincia = '" . $arrayParametros['provincia'] . "';";
	    
	    $resultado = $this->modeloProcesoAdministrativo->ejecutarSqlNativo($consulta);
	    return $resultado;
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener primera letra de nombre y apellido
	 *
	 * @return array
	 */
	public function obtenerLetrasNombreApellido($identificador){
	    $consulta = "SELECT
                             substring(nombre, 1, 1)||substring(apellido, 1, 1) as inicial 
                    FROM
                             g_uath.ficha_empleado 
                    WHERE 
                            identificador = '" . $identificador . "';";
	    
	    $resultado = $this->modeloProcesoAdministrativo->ejecutarSqlNativo($consulta);
	    return $resultado;
	}
	
	/**
	 *
	 */
	public function obtenerProcesosAdministrativos($arrayParametros){
	    
	    $busqueda = '';
	    if (array_key_exists('provincia', $arrayParametros)) {
	        $busqueda .= " upper(provincia) = upper('". $arrayParametros['provincia']."')" ;
	    }
	    if (array_key_exists('numero_proceso', $arrayParametros)) {
	        if($arrayParametros['numero_proceso'] != ''){
	            $busqueda .= " and upper(numero_proceso)  = upper('". $arrayParametros['numero_proceso']."')" ;
	        }
	    }
	    if (array_key_exists('area_tecnica', $arrayParametros)) {
	        if($arrayParametros['area_tecnica'] != ''){
	            $busqueda .= " and upper(area_tecnica)  = upper('". $arrayParametros['area_tecnica']."')" ;
	        }
	    }
	    if (array_key_exists('fecha_creacion', $arrayParametros)) {
	        if($arrayParametros['fecha_creacion'] != ''){
	            $busqueda .= " and fecha_creacion::date  = '". $arrayParametros['fecha_creacion']."'" ;
	        }
	    }
	    $consulta =" select
								*
							from
								".$this->modeloProcesoAdministrativo->getEsquema().". proceso_administrativo
							where
								
                                ".$busqueda." order by 1;";
	    return $this->modeloProcesoAdministrativo->ejecutarSqlNativo($consulta);
	}
	/**
	 *
	 */
	public function obtenerConsolidadoProcesosAdministrativos($arrayParametros){
	    
	    $busqueda = '';
	    if (array_key_exists('provincia', $arrayParametros)) {
	        if($arrayParametros['provincia'] != ''){
	        $busqueda .= " and upper(provincia) = upper('". $arrayParametros['provincia']."')" ;
	        }
	    }
	    if (array_key_exists('area_tecnica', $arrayParametros)) {
	        if($arrayParametros['area_tecnica'] != ''){
	            $busqueda .= " and upper(area_tecnica)  = upper('". $arrayParametros['area_tecnica']."')" ;
	        }
	    }
	   
	   $consulta =" select
								*
							from
								".$this->modeloProcesoAdministrativo->getEsquema().". proceso_administrativo
							where
								 fecha_creacion::date between '" . $arrayParametros['fecha_desde'] . "' and '". $arrayParametros['fecha_hasta'] . "' 
                                ".$busqueda." order by 1;";
	    return $this->modeloProcesoAdministrativo->ejecutarSqlNativo($consulta);
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener nombre y apellidos
	 *
	 * @return array
	 */
	public function obtenerNombreApellido($identificador){
	    $consulta = "SELECT
                             nombre||' '||apellido as funcionario
                    FROM
                             g_uath.ficha_empleado
                    WHERE
                            identificador = '" . $identificador . "';";
	    
	    $resultado = $this->modeloProcesoAdministrativo->ejecutarSqlNativo($consulta);
	    return $resultado;
	}
	// *****************************************generar excel********************************************************
	public function crearExcel($arrayDatos,$arrayParametros){
	    $documento = new ReportesExcelModelo();
	    $documento->getProperties()
	    ->setCreator("GUIA")
	    ->setLastModifiedBy('GUIA')
	    ->
	    // sss
	    setTitle('Reporte post mortem')
	    ->setSubject('Reporte')
	    ->setDescription('Este documento fue creado por el sistema GUIA')
	    ->setKeywords('')
	    ->setCategory('');
	    
	    $hoja = $documento->getActiveSheet();
	    $hoja->setTitle("hoja 1");
	    
	    $documento->cuerpoDinamicoHorizontal(6, 'No.', '95A5A6', 1, 10, 0, 2, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'PROVINCIA', '95A5A6', 1, 10, 0, 3, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'ÁREA TECNICA', '95A5A6', 1, 10, 0, 4, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'No. PROCESO ADMINISTRATIVO', '95A5A6', 1, 10, 0, 5, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'FECHA INFORME TECNICO', '95A5A6', 1, 10, 0, 6, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'NOMBRE ACCIONADO', '95A5A6', 1, 10, 0, 7, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'NOMBRE DEL ESTABLECIMIENTO.', '95A5A6', 1, 10, 0, 8, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'FECHA DE INFORME TECNICO', '95A5A6', 1, 10, 0, 9, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'FECHA DE CITACIÓN', '95A5A6', 1, 10, 0, 10, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'FECHA DE AUDIENCIA', '95A5A6', 1, 10, 0, 11, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'FECHA DE PRUEBA COA', '95A5A6', 1, 10, 0, 12, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'FECHA PROVIDENCIA.', '95A5A6', 1, 10, 0, 13, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'FECHA DE DICTAMEN', '95A5A6', 1, 10, 0, 14, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'FECHA DE RESOLUCIÓN', '95A5A6', 1, 10, 0, 15, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'DETALLE DE SANCIÓN IMPUESTA', '95A5A6', 1, 10, 0, 16, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'RESULTADO DEL TRÁMITE', '95A5A6', 1, 10, 0, 17, 0);
	    $documento->cuerpoDinamicoHorizontal(6, 'OBSERVACIONES', '95A5A6', 1, 10, 0, 18, 0);
	    
	    $arrayResultadoActos = array();
	    $verificar = $this->obtenerConsolidadoProcesosAdministrativos($arrayParametros);
	    $lnegocioTipoDocumento = new TipoDocumentoLogicaNegocio();
	    
	    foreach ($verificar as $items){
	        
	        $arrayParametros = array('orden'=>2,'id_proceso_administrativo' => $items['id_proceso_administrativo']);
	        $consulta = $lnegocioTipoDocumento->buscarTipoDocumentoModelo($arrayParametros);
	        if($consulta->count()>0){
	            $fechaCitacion = $consulta->current()->fecha_anexo;
	        }else{
	            $fechaCitacion='';
	        }
	        $arrayParametros = array('orden'=>3,'id_proceso_administrativo' => $items['id_proceso_administrativo']);
	        $consulta = $lnegocioTipoDocumento->buscarTipoDocumentoModelo($arrayParametros);
	        if($consulta->count()>0){
	            $fechaAudiencia = $consulta->current()->fecha_anexo;
	        }else{
	            $fechaAudiencia='';
	        }
	        $arrayParametros = array('orden'=>6,'id_proceso_administrativo' => $items['id_proceso_administrativo']);
	        $consulta = $lnegocioTipoDocumento->buscarTipoDocumentoModelo($arrayParametros);
	        if($consulta->count()>0){
	            $fechaCoa = $consulta->current()->fecha_anexo;
	        }else{
	            $fechaCoa='';
	        }
	        $arrayParametros = array('orden'=>5,'id_proceso_administrativo' => $items['id_proceso_administrativo']);
	        $consulta = $lnegocioTipoDocumento->buscarTipoDocumentoModelo($arrayParametros);
	        if($consulta->count()>0){
	            $fechaProvidencia = $consulta->current()->fecha_anexo;
	        }else{
	            $fechaProvidencia='';
	        }
	        $arrayParametros = array('orden'=>7,'id_proceso_administrativo' => $items['id_proceso_administrativo']);
	        $consulta = $lnegocioTipoDocumento->buscarTipoDocumentoModelo($arrayParametros);
	        if($consulta->count()>0){
	            $fechaDictamen = $consulta->current()->fecha_anexo;
	        }else{
	            $fechaDictamen='';
	        }
	        $arrayParametros = array('orden'=>8,'id_proceso_administrativo' => $items['id_proceso_administrativo']);
	        $consulta = $lnegocioTipoDocumento->buscarTipoDocumentoModelo($arrayParametros);
	        if($consulta->count()>0){
	            $fechaResolucion = $consulta->current()->fecha_anexo;
	        }else{
	            $fechaResolucion='';
	        }
	        
	                $arrayResultadoActos []= array(
	                    'provincia' => $items['provincia'],
	                    'area_tecnica' => $items['area_tecnica'],
	                    'numero_proceso' => $items['numero_proceso'],
	                    'fecha_creacion' => $items['fecha_creacion'],
	                    'nombre_accionado' => $items['nombre_accionado'],
	                    'nombre_establecimiento' => $items['nombre_accionado'],
	                    'fecha_creacion' => date('j/n/Y',strtotime($items['fecha_creacion'])),
	                    'fecha_citacion' => $fechaCitacion,
	                    'fecha_audiencia' => $fechaAudiencia,
	                    'fecha_prueba_coa' => $fechaCoa,
	                    'fecha_providencia' => $fechaProvidencia,
	                    'fecha_dictamen' => $fechaDictamen,
	                    'fecha_resolucion' => $fechaResolucion,
	                    'detalle_sancion' => $items['detalle_sancion'],
	                    'estado' => $items['estado'],
	                    'observacion' => $items['observacion'],
	                );
	        }
	    $contador=7;
	    $item=1;
	    foreach ($arrayResultadoActos as $value) {
	        $documento->cuerpoDinamicoHorizontal($contador, (string)$item, 'ffffff', 1, 10, 0, 2, 0);
	        $documento->cuerpoDinamicoHorizontal($contador, $value['provincia'], 'ffffff', 1, 10, 0, 3, 0);
	        $documento->cuerpoDinamicoHorizontal($contador, $value['area_tecnica'], 'ffffff', 1, 10, 0, 4, 0);
 	        $documento->cuerpoDinamicoHorizontal($contador, $value['numero_proceso'], 'ffffff', 1, 10, 0, 5, 0);
 	        $documento->cuerpoDinamicoHorizontal($contador, $value['fecha_creacion'], 'ffffff', 1, 10, 0, 6, 0);
 	        $documento->cuerpoDinamicoHorizontal($contador, $value['nombre_accionado'], 'ffffff', 1, 10, 0, 7, 0);
 	        $documento->cuerpoDinamicoHorizontal($contador, $value['nombre_establecimiento'], 'ffffff', 1, 10, 0, 8, 0);
 	        $documento->cuerpoDinamicoHorizontal($contador, $value['fecha_creacion'], 'ffffff', 1, 10, 0, 9, 0);
 	        $documento->cuerpoDinamicoHorizontal($contador, $value['fecha_citacion'], 'ffffff', 1, 10, 0, 10, 0);
 	        $documento->cuerpoDinamicoHorizontal($contador, $value['fecha_audiencia'], 'ffffff', 1, 10, 0, 11, 0);
 	        $documento->cuerpoDinamicoHorizontal($contador, $value['fecha_prueba_coa'], 'ffffff', 1, 10, 0, 12, 0);
 	        $documento->cuerpoDinamicoHorizontal($contador, $value['fecha_providencia'], 'ffffff', 1, 10, 0, 13, 0);
 	        $documento->cuerpoDinamicoHorizontal($contador, $value['fecha_dictamen'], 'ffffff', 1, 10, 0, 14, 0);
	        $documento->cuerpoDinamicoHorizontal($contador, $value['fecha_resolucion'], 'ffffff', 1, 10, 0, 15, 0);
	        $documento->cuerpoDinamicoHorizontal($contador, $value['detalle_sancion'], 'ffffff', 1, 10, 0, 16, 0);
 	        $documento->cuerpoDinamicoHorizontal($contador, $value['estado'], 'ffffff', 1, 10, 0, 17, 0);
 	        $documento->cuerpoDinamicoHorizontal($contador, $value['observacion'], 'ffffff', 1, 10, 0, 18, 0);
	        $contador++; $item++;
	    
	   }
	    
 	    $documento->crearCabeceraExcel(3, $arrayDatos['titulo'], 'ffffff', 0, 12, 17);
 	    $documento->crearCabeceraExcel(4, $arrayDatos['subtitulo'], 'ffffff', 0, 10, 8);
	    
	    $documento->getActiveSheet()
	    ->getRowDimension(6)
	    ->setRowHeight(50);
	    $documento->getActiveSheet()
	    ->getColumnDimension('D')
	    ->setAutoSize(true);
	    $documento->getActiveSheet()
	    ->getColumnDimension('E')
	    ->setAutoSize(true);
	    $documento->getActiveSheet()
	    ->getColumnDimension('F')
	    ->setAutoSize(true);
	    $documento->getActiveSheet()
	    ->getColumnDimension('G')
	    ->setAutoSize(true);
	    $documento->getActiveSheet()
	    ->getColumnDimension('H')
	    ->setAutoSize(true);
	    $documento->getActiveSheet()
	    ->getColumnDimension('P')
	    ->setAutoSize(true);
	    $documento->getActiveSheet()
	    ->getColumnDimension('R')
	    ->setAutoSize(true);
	    for($i=7; $i<= 11; $i++){
	        $documento->getActiveSheet()
	        ->getRowDimension($i)
	        ->setRowHeight(20);
	    }

	    
 	    
	    $writer = new Xlsx($documento);
	    $nombreArchivo = PROC_JURI_URL_RAIZ . "documentosProceso/reporteExcel/" . $arrayDatos['nombreArchivo'] . ".xlsx";
	    $writer->save($nombreArchivo);

	}
	
	//*************validar documento adjunto************************
	public function validarAdjunto($consulta){
	    $bandera = false;
	    foreach ($consulta as $dato) {
	        if($dato['ruta_documento']==''){
	            $bandera=true;
	            break;
	        }
	    }
	    return $bandera;
	    
	}
}
