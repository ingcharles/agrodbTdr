<?php
/**
 * Lógica del negocio de TramitesModelo
 *
 * Este archivo se complementa con el archivo TramitesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-01-15
 * @uses    TramitesLogicaNegocio
 * @package SeguimientoDocumental
 * @subpackage Modelos
 */
namespace Agrodb\SeguimientoDocumental\Modelos;

use Agrodb\SeguimientoDocumental\Modelos\SeguimientosLogicaNegocio;
use Agrodb\SeguimientoDocumental\Modelos\SeguimientosModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\SeguimientoDocumental\Modelos\IModelo;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use TCPDF;
use Agrodb\Core\JasperReport;

class TramitesLogicaNegocio implements IModelo
{

    private $modeloTramites = null;
	
    private $lNegocioSeguimientos = null;
    private $modeloSeguimientos = null;
    
	private $modeloCertificado = null;
    
    private $esBorrador = false;
    
    private $pdf = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloTramites = new TramitesModelo();
        
        $this->lNegocioSeguimientos = new SeguimientosLogicaNegocio();
        $this->modeloSeguimientos = new SeguimientosModelo();
		
		$this->pdf = new \TCPDF();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        switch ($datos['estado_tramite']){
            
            case 'Ingresado':{
                $datos['identificador'] = $_SESSION['usuario'];
                
                if ($datos['factura'] == '') {
                    $datos['factura'] = "No indica";
                }
                
                if ($datos['guia_quipux'] == '') {
                    $datos['guia_quipux'] = "No indica";
                }
                
                if ($datos['anexos'] == '') {
                    $datos['anexos'] = "No indica";
                }
                
                if ($datos['quipux_agr'] == '') {
                    $datos['quipux_agr'] = "No indica";
                }
                
                if(isset($datos['id_unidad_destino'])){
                    $datos['id_unidad_destino_actual'] = $datos['id_unidad_destino'];
                }
                
                break;
            }
            
            case 'Despachado':{
                $datos['estado_tramite'] = 'Despachado';
                
                break;
            }
            
            case 'Seguimiento':{
                $datos['estado_tramite'] = 'Seguimiento';
                
                
                
                break;
            }
            
            case 'Cerrado':{
                if ($datos['documentos_entregados'] == '') {
                    $datos['documentos_entregados'] = "No indica";
                }
                
                if ($datos['fecha_entrega'] == '') {
                    $datos['fecha_entrega'] = 'now()';
                }
                
                if ($datos['observaciones'] == '') {
                    $datos['observaciones'] = "No indica";
                }
                
                $datos['fecha_cierre'] = 'now()';
                
                break;
            }
            
            default:{
                $datos['identificador'] = $_SESSION['usuario'];
                $datos['estado_tramite'] = 'Ingresado';
                
                if ($datos['factura'] == '') {
                    $datos['factura'] = "No indica";
                }
                
                if ($datos['guia_quipux'] == '') {
                    $datos['guia_quipux'] = "No indica";
                }
                
                if ($datos['anexos'] == '') {
                    $datos['anexos'] = "No indica";
                }
                
                if ($datos['quipux_agr'] == '') {
                    $datos['quipux_agr'] = "No indica";
                }
                
                if(isset($datos['id_unidad_destino'])){
                    $datos['id_unidad_destino_actual'] = $datos['id_unidad_destino'];
                }
                
                break;
            }
        }
        
        $tablaModelo = new TramitesModelo($datos);

        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdTramite() != null && $tablaModelo->getIdTramite() > 0) {
            //Si es cierre de trámite, buscar trámites derivados para cierre
            if ($datos['estado_tramite'] == 'Cerrado' && $datos['derivado'] == 'No'){
                if($datos['quipux_agr'] != 'No indica'){
                    $this->cerrarTramitesDerivadosXQuipux($datos['quipux_agr'], $datos['documentos_entregados'], $datos['fecha_entrega'], $datos['observaciones']);
                }
            }
            
            return $this->modeloTramites->actualizar($datosBd, $tablaModelo->getIdTramite());
        } else {
            unset($datosBd["id_tramite"]);
            return $this->modeloTramites->guardar($datosBd);
        }
    }
    
    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardarAdministrador(Array $datos)
    {        
        $tablaModelo = new TramitesModelo($datos);
        
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdTramite() != null && $tablaModelo->getIdTramite() > 0) {
            //Si es cierre de trámite, buscar trámites derivados para cierre
            if ($datos['estado_tramite'] == 'Cerrado' && $datos['derivado'] == 'No'){
                if($datos['quipux_agr'] != 'No indica'){
                    $this->cerrarTramitesDerivadosXQuipux($datos['quipux_agr'], $datos['documentos_entregados'], $datos['fecha_entrega'], $datos['observaciones']);
                }
            }
            
            return $this->modeloTramites->actualizar($datosBd, $tablaModelo->getIdTramite());
        } else {
            unset($datosBd["id_tramite"]);
            return $this->modeloTramites->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     *
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloTramites->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return TramitesModelo
     */
    public function buscar($id)
    {
        return $this->modeloTramites->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloTramites->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloTramites->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarTramites()
    {
        $consulta = "SELECT * FROM " . $this->modeloTramites->getEsquema() . ". tramites";
        return $this->modeloTramites->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar trámites usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarTramitesXFiltro($arrayParametros)
    {
        $busqueda = '';

        if (isset($arrayParametros['numero_tramite']) && ($arrayParametros['numero_tramite'] != '')) {
            $busqueda .= "and t.numero_tramite = '" . $arrayParametros['numero_tramite'] . "'";
        }

        if (isset($arrayParametros['remitente']) && ($arrayParametros['remitente'] != '')) {
            $busqueda .= "and upper(t.remitente) ilike upper('%" . $arrayParametros['remitente'] . "%')";
        }

        if (isset($arrayParametros['destinatario']) && ($arrayParametros['destinatario'] != '')) {
            $busqueda .= "and upper(t.destinatario) ilike upper('%" . $arrayParametros['destinatario'] . "%')";
        }
        
        if (isset($arrayParametros['quipux_agr']) && ($arrayParametros['quipux_agr'] != '')) {
            $busqueda .= "and upper(t.quipux_agr) ilike upper('%" . $arrayParametros['quipux_agr'] . "%')";
        }

        if (isset($arrayParametros['factura']) && ($arrayParametros['factura'] != '')) {
            $busqueda .= "and upper(t.factura) ilike upper('%" . $arrayParametros['factura'] . "%')";
        }
        
        if (isset($arrayParametros['id_unidad_destino']) && ($arrayParametros['id_unidad_destino'] != '')) {
        	$busqueda .= "and t.id_unidad_destino_actual = '" . $arrayParametros['id_unidad_destino'] . "'";
        }
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '')) {
            $busqueda .= " and t.fecha_creacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' ";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '')) {
            $busqueda .= " and t.fecha_creacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00' ";
        }

        $consulta = "  SELECT
                        	t.id_tramite, t.numero_tramite, t.fecha_creacion,
                        	t.id_ventanilla, ve.nombre as ventanilla,
                        	t.identificador, fe.nombre, fe.apellido,	
                        	t.remitente, t.oficio_memo, t.factura,
                        	t.guia_quipux, t.asunto, t.anexos,
                        	t.destinatario, t.id_unidad_destino, t.quipux_agr, t.derivado,
                        	t.estado_tramite, t.documentos_entregados, t.fecha_entrega,
                        	t.observaciones, t.fecha_cierre
                        FROM
                        	g_seguimiento_documental.tramites t
                        	INNER JOIN g_seguimiento_documental.ventanillas ve ON t.id_ventanilla = ve.id_ventanilla
                            INNER JOIN g_uath.ficha_empleado fe ON t.identificador = fe.identificador
                        WHERE
                            t.id_ventanilla = " . $arrayParametros['id_ventanilla'] . " and
                            t.estado_tramite = '" . $arrayParametros['estado_tramite'] . "'" . $busqueda . "
                        ORDER BY
                            t.numero_tramite, t.id_unidad_destino ASC;";
							
        //echo $consulta;
        return $this->modeloTramites->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar trámites usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarTramitesBitacoraEstado($arrayParametros)
    {
        $consulta = "  SELECT
                        	t.id_tramite, t.numero_tramite, t.fecha_creacion,
                        	t.id_ventanilla,
                        	t.identificador,
                        	t.remitente, t.oficio_memo, t.factura,
                        	t.guia_quipux, t.asunto, t.anexos,
                        	t.destinatario, t.id_unidad_destino, t.quipux_agr, t.derivado,
                        	t.estado_tramite, t.documentos_entregados, t.fecha_entrega,
                        	t.observaciones, t.fecha_cierre, t.unidad_destino_actual as unidad_destino
                        FROM
                        	g_seguimiento_documental.tramites t
                        WHERE
                            t.id_ventanilla = " . $arrayParametros['id_ventanilla'] . " and
                            t.estado_tramite IN (" . $arrayParametros['estado_tramite'] . ") and
                            t.id_unidad_destino is not null
                        ORDER BY
                            t.numero_tramite DESC;";
    	
    	/*
    	 $consulta = "  SELECT
                        	t.id_tramite, t.numero_tramite, COALESCE(s.fecha_creacion, t.fecha_creacion) as fecha_creacion,
                        	t.id_ventanilla, ve.nombre as ventanilla,
                        	t.identificador, fe.nombre, fe.apellido,
                        	t.remitente, t.oficio_memo, t.factura,
                        	t.guia_quipux, t.asunto, t.anexos,
                        	t.destinatario, t.id_unidad_destino, t.quipux_agr, t.derivado,
                        	t.estado_tramite, t.documentos_entregados, t.fecha_entrega,
                        	t.observaciones, t.fecha_cierre, a.nombre as unidad_destino
                        FROM
                        	g_seguimiento_documental.tramites t
                        	INNER JOIN g_seguimiento_documental.ventanillas ve ON t.id_ventanilla = ve.id_ventanilla
                            INNER JOIN g_uath.ficha_empleado fe ON t.identificador = fe.identificador
							LEFT JOIN g_seguimiento_documental.seguimientos s ON t.id_tramite = s.id_tramite AND id_seguimiento = (SELECT max(id_seguimiento) FROM g_seguimiento_documental.seguimientos s1 WHERE s1.id_tramite = s.id_tramite)
							LEFT JOIN g_estructura.area a ON COALESCE(s.id_unidad_destino,t.id_unidad_destino) = a.id_area
                        WHERE
                            t.id_ventanilla = " . $arrayParametros['id_ventanilla'] . " and
                            t.estado_tramite IN (" . $arrayParametros['estado_tramite'] . ")
                        ORDER BY
                            COALESCE(s.fecha_creacion, t.fecha_creacion) DESC;";
    	 */

    	return $this->modeloTramites->ejecutarSqlNativo($consulta);
    }

    public function buscarNumeroTramite($idCodigoVentanilla)
    {
    	
    	$anio = date("Y");
    	
    	$formatoCodigo = 'AGR-' . $idCodigoVentanilla . '-' . $anio . '-';
    	
        $consulta = "SELECT
                        max(split_part(numero_tramite, '" . $formatoCodigo . "' , 2)::int) as numero
                     FROM
                        g_seguimiento_documental.tramites
                     WHERE
                        numero_tramite LIKE '%" . $formatoCodigo . "%';";

         $codigo = $this->modeloTramites->ejecutarSqlNativo($consulta);
         $fila = $codigo->current();

		$codigoTramite = array(	'numero' => $fila['numero']);

        $incremento = $codigoTramite['numero'] + 1;
        $codigoTramite = $formatoCodigo . str_pad($incremento, 5, "0", STR_PAD_LEFT);
        
        return $codigoTramite;
    }
    
    public function buscarTramitesDerivadosXQuipux($quipuxAgr)
    {
        $consulta = "   SELECT 
                        	t.id_tramite, t.estado_tramite
                        FROM 
                        	g_seguimiento_documental.tramites t
                        WHERE
                        	t.derivado = 'Si' and
                        	t.quipux_agr = '" . $quipuxAgr . "';";
        
        return $this->modeloTramites->ejecutarSqlNativo($consulta);
    }
    
    public function cerrarTramitesDerivadosXQuipux($quipuxAgr, $docEntregados, $fechaEntrega, $observaciones)
    {
        $consulta = "   UPDATE
                            g_seguimiento_documental.tramites
                        SET
                            estado_tramite = 'Cerrado',
                            documentos_entregados = '".$docEntregados."',
                            fecha_entrega = '".$fechaEntrega."',
                            observaciones = '".$observaciones."',
                            fecha_cierre = now()
                        WHERE
                        	derivado = 'Si' and
                        	quipux_agr = '" . $quipuxAgr . "';";
        
        return $this->modeloTramites->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar trámites a nivel nacional usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarTramitesNacionalXFiltro($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['estado_tramite']) && ($arrayParametros['estado_tramite'] != 'Todos')) {
        	$busqueda .= " and t.estado_tramite = '" . $arrayParametros['estado_tramite'] . "' ";
        }
        
        if (isset($arrayParametros['id_ventanilla']) && ($arrayParametros['id_ventanilla'] != '')) {
            $busqueda .= " and t.id_ventanilla = '" . $arrayParametros['id_ventanilla'] . "'";
        }
        
        if (isset($arrayParametros['id_unidad_destino']) && ($arrayParametros['id_unidad_destino'] != '')) {
            $busqueda .= " and t.id_unidad_destino = '" . $arrayParametros['id_unidad_destino'] . "'";
        }
        
        if (isset($arrayParametros['numero_tramite']) && ($arrayParametros['numero_tramite'] != '')) {
            $busqueda .= " and t.numero_tramite = '" . $arrayParametros['numero_tramite'] . "'";
        }
        
        if (isset($arrayParametros['remitente']) && ($arrayParametros['remitente'] != '')) {
            $busqueda .= " and upper(t.remitente) ilike upper('%" . $arrayParametros['remitente'] . "%')";
        }
        
        if (isset($arrayParametros['destinatario']) && ($arrayParametros['destinatario'] != '')) {
            $busqueda .= " and upper(t.destinatario) ilike upper('%" . $arrayParametros['destinatario'] . "%')";
        }
        
        if (isset($arrayParametros['quipux_agr']) && ($arrayParametros['quipux_agr'] != '')) {
            $busqueda .= " and upper(t.quipux_agr) ilike upper('%" . $arrayParametros['quipux_agr'] . "%')";
        }
        
        if (isset($arrayParametros['factura']) && ($arrayParametros['factura'] != '')) {
            $busqueda .= " and upper(t.factura) ilike upper('%" . $arrayParametros['factura'] . "%')";
        }
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '')) {
            $busqueda .= " and t.fecha_creacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' ";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '')) {
            $busqueda .= " and t.fecha_creacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00' ";
        }
        
        $busqueda = ltrim($busqueda, " and");
        
        $consulta = "  SELECT
                        	t.id_tramite, t.numero_tramite, t.fecha_creacion,
                        	t.id_ventanilla, ve.nombre as ventanilla,
                        	t.identificador, fe.nombre, fe.apellido,
                        	t.remitente, t.oficio_memo, t.factura,
                        	t.guia_quipux, t.asunto, t.anexos,
                        	t.destinatario, t.id_unidad_destino, a.nombre as unidad_destino,
                            t.quipux_agr, t.derivado,
                        	t.estado_tramite, t.documentos_entregados, t.fecha_entrega,
                        	t.observaciones, t.fecha_cierre, t.origen_tramite
                        FROM
                        	g_seguimiento_documental.tramites t
                        	INNER JOIN g_seguimiento_documental.ventanillas ve ON t.id_ventanilla = ve.id_ventanilla
                            INNER JOIN g_uath.ficha_empleado fe ON t.identificador = fe.identificador
                            INNER JOIN g_estructura.area a ON t.id_unidad_destino = a.id_area
                        WHERE
                            ". $busqueda ."
                        ORDER BY
                            t.numero_tramite, t.id_unidad_destino ASC;";
        
        return $this->modeloTramites->ejecutarSqlNativo($consulta);
    }
    
    public function leerArchivoExcelTramites($datos){

    	$rutaArchivo = $datos['archivo'];
    	$extension = explode('.',$rutaArchivo);
    	$identificador = $_SESSION['usuario'];

    	switch (strtolower(end($extension))){
    		case 'xls':
    			$tipo = 'Xls';   //Requiere formato Xls
    			break;
    		case 'xlsx':
    			$tipo = 'Xlsx';   //Requiere formato Xlsx
    			break;
    		default:
    			$tipo = 'Xls';   //Requiere formato Xls
    			break;
    	}
    	
    	try {
    		$proceso = $this->modeloTramites->getAdapter()->getDriver()->getConnection();
    		
    		if (!$proceso->beginTransaction()){
    			throw new \Exception('No se pudo iniciar la transacción en: Guardar tramite');
    		}
    		
	    	$reader = IOFactory::createReader($tipo);
	    	$reader->setReadDataOnly(true);
	    	$reader->setLoadSheetsOnly(0);
	    	$documento = $reader->load(Constantes::RUTA_SERVIDOR_OPT.'/'.Constantes::RUTA_APLICACION.'/'.$rutaArchivo);

    		$hojaActual = $documento->getActiveSheet()->toArray(null, true, true, true);
    		
    		$archivoVacio = $documento->getActiveSheet()->getCell('A3')->getValue();
    		
    		if($archivoVacio){
    			$datoExceso = $documento->getActiveSheet()->getCell('J3')->getValue();
    			if(!$datoExceso){
		    		$lNegocioUsuariosVentanilla = new \Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaLogicaNegocio();
		    		$datosUsuario = $lNegocioUsuariosVentanilla->buscarDatosUsuarioTecnico($identificador);
		    		
		    		$lNegocioArea = new \Agrodb\Estructura\Modelos\AreaLogicaNegocio();
		
		    		//Inicio de lectura fila 7
		    		for ($i = 7; $i <= count($hojaActual); $i++) {
		
		    			$numeroDocumento = $this->buscarNumeroTramite($datosUsuario['codigoVentanilla']);
		    			$arrayAsunto = explode('_', $hojaActual[$i]['C']);
		    			
		    			$numeroQuipux = $this->obtenerQuipuxTramite($hojaActual[$i]['E']);
		    			
		    			if($numeroQuipux == 'SI'){
		    				continue;
		    			}
		    			
		    			$datosExcel = array(
		    				'remitente' => $hojaActual[$i]['A'],
		    				'destinatario' => $hojaActual[$i]['B'],
		    				'quipux_agr' => $hojaActual[$i]['E'],
		    				'oficio_memo' => $hojaActual[$i]['H'],
		    				'estado_tramite' => 'Ingresado',
		    				'id_ventanilla' => $datosUsuario['idVentanilla'],
		    				'numero_tramite' => $numeroDocumento,
		    				'asunto' => (array_key_exists(1, $arrayAsunto)?$arrayAsunto[1]:''),
		    				'factura' => '',
		    				'derivado' => 'No',
		    				'guia_quipux' => '',
		    				'anexos' => '',
		    			    'origen_tramite' => 'Ventanilla'
		    			);

		    			$datosUnidad = $lNegocioArea->buscarAreaPorCodigo(str_replace(" ", "", trim($arrayAsunto[0])));

		    			if($datosUnidad->count() > 0){
		    				$unidadDestino = $datosUnidad->current();
		    				$datosExcel += array('id_unidad_destino' => $unidadDestino->id_area);
		    				$datosExcel += array('id_unidad_destino_actual' => $unidadDestino->id_area);
		    				$datosExcel += array('unidad_destino_actual' => $unidadDestino->nombre);
		    			}
		    			$this->guardar($datosExcel);
		    		}
		    		
		    		$proceso->commit();
		    		Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    			}else{
    				Mensajes::fallo(Constantes::ARCHIVO_MAL_CONSTRUIDO);
    			}
    		}else{
    			Mensajes::fallo(Constantes::ARCHIVO_VACIO);
    		}
    	}catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
    		$proceso->rollback();
    		Mensajes::fallo(Constantes::ERROR_AL_GUARDAR);
    	}
    }
    
    public function leerArchivoExcelTramitesCiudadanos($datos){
        
        $rutaArchivo = $datos['archivo'];
        $extension = explode('.',$rutaArchivo);
        $identificador = $_SESSION['usuario'];
        
        switch (strtolower(end($extension))){
            case 'xls':
                $tipo = 'Xls';   //Requiere formato Xls
                break;
            case 'xlsx':
                $tipo = 'Xlsx';   //Requiere formato Xlsx
                break;
            default:
                $tipo = 'Xls';   //Requiere formato Xls
                break;
        }
        
        try {
            $proceso = $this->modeloTramites->getAdapter()->getDriver()->getConnection();
            
            if (!$proceso->beginTransaction()){
                throw new \Exception('No se pudo iniciar la transacción en: Guardar trámite');
            }
            
            $reader = IOFactory::createReader($tipo);
            $reader->setReadDataOnly(true);
            $reader->setLoadSheetsOnly(0);
            $documento = $reader->load(Constantes::RUTA_SERVIDOR_OPT.'/'.Constantes::RUTA_APLICACION.'/'.$rutaArchivo);
            
            $hojaActual = $documento->getActiveSheet()->toArray(null, true, true, true);
            
            $archivoVacio = $documento->getActiveSheet()->getCell('A3')->getValue();
            
            if($archivoVacio){
                $datoExceso = $documento->getActiveSheet()->getCell('L3')->getValue();
                if(!$datoExceso){
                    $lNegocioUsuariosVentanilla = new \Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaLogicaNegocio();
                    $datosUsuario = $lNegocioUsuariosVentanilla->buscarDatosUsuarioTecnico($identificador);
                    
                    $lNegocioArea = new \Agrodb\Estructura\Modelos\AreaLogicaNegocio();
                    
                    //Inicio de lectura fila 7
                    for ($i = 7; $i <= count($hojaActual); $i++) {
                        
                        $numeroDocumento = $this->buscarNumeroTramite($datosUsuario['codigoVentanilla']);
                        
                        $numeroQuipux = $this->obtenerQuipuxTramite($hojaActual[$i]['B']);
                        
                        if($numeroQuipux == 'SI'){
                            continue;
                        }
                        
                        $datosExcel = array(
                            'remitente' => $hojaActual[$i]['F'],
                            'destinatario' => $hojaActual[$i]['G'],
                            'quipux_agr' => $hojaActual[$i]['B'],
                            'oficio_memo' => $hojaActual[$i]['C'],
                            'estado_tramite' => 'Ingresado',
                            'id_ventanilla' => $datosUsuario['idVentanilla'],
                            'numero_tramite' => $numeroDocumento,
                            'asunto' => $hojaActual[$i]['E'],
                            'factura' => '',
                            'derivado' => 'No',
                            'guia_quipux' => '',
                            'anexos' => '',
                            'origen_tramite' => 'Ciudadano'
                        );
                        
                        $datosUnidad = $lNegocioArea->buscarAreaPorCodigo(str_replace(" ", "", trim($hojaActual[$i]['I'])));
                        
                        if($datosUnidad->count() > 0){
                            $unidadDestino = $datosUnidad->current();
                            $datosExcel += array('id_unidad_destino' => $unidadDestino->id_area);
                            $datosExcel += array('id_unidad_destino_actual' => $unidadDestino->id_area);
                            $datosExcel += array('unidad_destino_actual' => $unidadDestino->nombre);
                        }
                        $this->guardar($datosExcel);
                    }
                    
                    $proceso->commit();
                    Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
                }else{
                    Mensajes::fallo(Constantes::ARCHIVO_MAL_CONSTRUIDO);
                }
            }else{
                Mensajes::fallo(Constantes::ARCHIVO_VACIO);
            }
        }catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            $proceso->rollback();
            Mensajes::fallo(Constantes::ERROR_AL_GUARDAR);
        }
    }
/**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar trámites a nivel nacional usando filtros.
     *
     * @return array|ResultSet
     */
    public function generarPorcentajeTramitesNacionalXFiltro($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['id_ventanilla']) && ($arrayParametros['id_ventanilla'] != '')) {
            $busqueda .= "and t.id_ventanilla = '" . $arrayParametros['id_ventanilla'] . "'";
        }
                
        if ((isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '')) && (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != ''))) {
            $busqueda .= " and
	        t.fecha_creacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' and
	        t.fecha_creacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00' ";
        }
        
        $consulta = "  SELECT
                        	count(t.id_tramite)
                        FROM
                        	g_seguimiento_documental.tramites t
                        WHERE
                        	t.estado_tramite in ('" . $arrayParametros['estado_tramite'] . "')". $busqueda . ";";
        
        //echo $consulta;
        return $this->modeloTramites->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar trámites a nivel nacional usando filtros.
     *
     * @return array|ResultSet
     */
    public function contarTramitesNacionalXEstado($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['id_ventanilla']) && ($arrayParametros['id_ventanilla'] != '')) {
            $busqueda .= "and t.id_ventanilla = '" . $arrayParametros['id_ventanilla'] . "'";
        }
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '')) {
            $busqueda .= " and t.fecha_creacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' ";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '')) {
            $busqueda .= " and t.fecha_creacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00' ";
        }
        
        $consulta = "  SELECT
                        	count(t.id_tramite) as num_tramites
                        FROM
                        	g_seguimiento_documental.tramites t
                        WHERE
                        	t.estado_tramite in (" . $arrayParametros['estado_tramite'] . ")". $busqueda . ";";
        
        //echo $consulta;
        return $this->modeloTramites->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Calcular Porcentaje de Trámites ingresados y atendidos.
     *
     * @return array|ResultSet
     */
    public function porcentajeTramitesAtendidosIngresados($arrayParametros)
    {
        $estadosTramites = "'Ingresado', 'Despachado', 'Seguimiento', 'Cerrado'";
        $estadoAtendido = "'Cerrado'";
        
        $parametrosAtendido = $arrayParametros;
        $parametrosAtendido['estado_tramite'] = $estadoAtendido;
        
        $parametrosIngresados = $arrayParametros;
        $parametrosIngresados['estado_tramite'] = $estadosTramites;
        
        //echo 'Atendidos';
        //Trámites con estado Cerrado
        $tramitesAtendidos = $this->contarTramitesNacionalXEstado($parametrosAtendido);
        //echo $tramitesAtendidos->current()->num_tramites;        
        
        //echo 'Ingresados';
        //Todos los trámites registrados
        $tramitesIngresados = $this->contarTramitesNacionalXEstado($parametrosIngresados);
        //echo $tramitesIngresados->current()->num_tramites;        
        
        $porcentaje = ($tramitesAtendidos->current()->num_tramites * 100)/($tramitesIngresados->current()->num_tramites);
        
        $arrayResultado = array('atendidos' => $tramitesAtendidos->current()->num_tramites,
                                'ingresados' => $tramitesIngresados->current()->num_tramites,
                                'porcentaje' => $porcentaje
                                );
        
        return $arrayResultado;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar trámites usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarTramitesBitacora($tramite)
    {
        $consulta = "  SELECT
                        	t.id_tramite, t.numero_tramite, t.fecha_creacion,
                        	t.guia_quipux, t.oficio_memo, t.asunto, 
                            t.id_unidad_destino, t.unidad_destino_actual as unidad_destino,
                            t.anexos, t.quipux_agr, 
                        	t.estado_tramite
                        FROM
                        	g_seguimiento_documental.tramites t
                        WHERE
                            t.id_tramite IN (" . $tramite . ")
                        ORDER BY
                            t.numero_tramite ASC;";
        
        
        return $this->modeloTramites->ejecutarSqlNativo($consulta);
    }
    
    public function generarCertificado($arrayTramites, $nombreArchivo)//
    {
        ob_start();
        // ************************************************** INICIO ***********************************************************
        
        $margen_superior = 10;
        $margen_inferior = 10;
        $margen_izquierdo = 10;
        $margen_derecho = 10;
        
        $doc = new PDF('L', 'mm', 'A4', true, 'UTF-8');
        
        $tipoLetra = 'times';
        
        $doc->SetLineWidth(0.1);
        $doc->setCellHeightRatio(1.5);
        $doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
        $doc->SetAutoPageBreak(TRUE, $margen_inferior);
        $doc->SetFont($tipoLetra, '', 9);
        $doc->AddPage();
        
        $fechaSolicitud = date('Y-m-d');
        
        // ****************************** INICIA *************************************
        $doc->SetTextColor();
        $doc->SetFont('times', 'B', 13);
        $y = $doc->GetY();
        $doc->writeHTMLCell(0, 0, $margen_izquierdo, $y + 10, '<i><u>Bitácora de Entrega de Trámites</u></i>', '', 1, 0, true, 'C', true);
        
        $doc->SetFont('times', 'B', 8);
        $html = 'Fecha: ';
        
        $doc->SetFont('times', '', 8);        
        $html .= $fechaSolicitud;
        
        $doc->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'J', true);
        $doc->Ln();
        
        // column titles
        $header = array('REGISTRO', 'FECHA', 'GUÍA DE CORREO', 'MEMO/OFICIO/REF', 'ASUNTO', 'UNIDAD DESTINO', 
                        'ANEXOS', 'QUIPUX AGR.', 'FIRMA Y NOMBRE RECIBIDO', 'FECHA/HORA');
        
        // data loading
        $data = $arrayTramites;
        
        // print colored table
        $doc->tabla($header, $data);
        
        
        $doc->Ln();
        $doc->SetFont('times', 'B', 12);
        
        $doc->Output(SEG_DOC_BIT_URL_TCPDF . "bitacora/" . $nombreArchivo . ".pdf", 'F');
        ob_end_clean();
    }
    
    public function obtenerQuipuxTramite($numeroQuipux) {
    	$numeroQuipux = str_replace("¬", "/", $numeroQuipux);
    	$existenciaNumero = $this->modeloTramites->buscarLista("quipux_agr = '" . $numeroQuipux . "';");
    	if($existenciaNumero->count() != 0){
    		$validacionNumero = 'SI';
    	}else{
    		$validacionNumero = 'NO';
    	}
    	return $validacionNumero;
    }
    
    public function exportarArchivoExcel($datos){
        
        $hoja = new Spreadsheet();
        $documento = $hoja->getActiveSheet();
        $i = 3;
        $j = 1;
        $estado = '';
        
        $documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Trámites ');
        
        $documento->setCellValueByColumnAndRow(1, 2, 'Número');
        $documento->setCellValueByColumnAndRow(2, 2, 'Número de Trámite');
        $documento->setCellValueByColumnAndRow(3, 2, 'Asunto');
        $documento->setCellValueByColumnAndRow(4, 2, 'Remitente');
        $documento->setCellValueByColumnAndRow(5, 2, 'Destinatario');
        
        foreach ($datos as $fila){
            $documento->setCellValueByColumnAndRow(1, $i, $j);
            $documento->setCellValueByColumnAndRow(2, $i, $fila['numero_tramite']);
            $documento->setCellValueByColumnAndRow(3, $i, $fila['asunto']);
            $documento->setCellValueByColumnAndRow(4, $i, $fila['remitente']);
            $documento->setCellValueByColumnAndRow(5, $i, $fila['destinatario']);
            $estado = $fila['estado_tramite'];
            $i++;
            $j++;
        }
        
        $documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Trámites '. $estado);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="excelTramites.xlsx"');
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $writer = IOFactory::createWriter($hoja, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
    
    public function exportarArchivoExcelTramiteSeguimiento($datos){
    	
    	$hoja = new Spreadsheet();
    	$documento = $hoja->getActiveSheet();
    	$i = 3;
    	$j = 1;
    	$estado = '';
    	
    	$documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Trámites ');
    	
    	$documento->setCellValueByColumnAndRow(1, 2, 'Número');
    	$documento->setCellValueByColumnAndRow(2, 2, 'Número de Trámite');
    	$documento->setCellValueByColumnAndRow(3, 2, 'Asunto');
    	$documento->setCellValueByColumnAndRow(4, 2, 'Remitente');
    	$documento->setCellValueByColumnAndRow(5, 2, 'Destinatario');
    	$documento->setCellValueByColumnAndRow(6, 2, 'Usuario Actual');
    	$documento->setCellValueByColumnAndRow(7, 2, 'Fecha último seguimiento');
    	$documento->setCellValueByColumnAndRow(8, 2, 'Ubicación Actual');
    	$documento->setCellValueByColumnAndRow(9, 2, 'Observación');
    	
    	foreach ($datos as $fila){
    		$documento->setCellValueByColumnAndRow(1, $i, $j);
    		$documento->setCellValueByColumnAndRow(2, $i, $fila['numero_tramite']);
    		$documento->setCellValueByColumnAndRow(3, $i, $fila['asunto']);
    		$documento->setCellValueByColumnAndRow(4, $i, $fila['remitente']);
    		$documento->setCellValueByColumnAndRow(5, $i, $fila['destinatario']);
    		$documento->setCellValueByColumnAndRow(6, $i, $fila['persona_recibe']);
    		$documento->setCellValueByColumnAndRow(7, $i, $fila['fecha']);
    		$documento->setCellValueByColumnAndRow(8, $i, $fila['unidad_destino_actual']);
    		$documento->setCellValueByColumnAndRow(9, $i, $fila['observaciones_seguimiento']);
    		$estado = $fila['estado_tramite'];
    		$i++;
    		$j++;
    	}
    	
    	$documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Trámites '. $estado);
    	
    	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    	header('Content-Disposition: attachment;filename="excelTramites.xlsx"');
    	header("Pragma: no-cache");
    	header("Expires: 0");
    	
    	$writer = IOFactory::createWriter($hoja, 'Xlsx');
    	$writer->save('php://output');
    	exit();
    }
    
    public function exportarArchivoExcelTramitesAdministrador($datos){
        
        $hoja = new Spreadsheet();
        $documento = $hoja->getActiveSheet();
        $i = 3;
        $j = 2;
        
        $documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Trámites');
        
        $documento->setCellValueByColumnAndRow(1, $j, 'ID');
        $documento->setCellValueByColumnAndRow(2, $j, 'Número');
        $documento->setCellValueByColumnAndRow(3, $j, 'Fecha creación');
        $documento->setCellValueByColumnAndRow(4, $j, 'Ventanilla');
        $documento->setCellValueByColumnAndRow(5, $j, 'Técnico');
        $documento->setCellValueByColumnAndRow(6, $j, 'Remitente');
        $documento->setCellValueByColumnAndRow(7, $j, 'Oficio - Memo');
        $documento->setCellValueByColumnAndRow(8, $j, 'Factura');
        $documento->setCellValueByColumnAndRow(9, $j, 'Guía - Correo');
        $documento->setCellValueByColumnAndRow(10, $j, 'Asunto');
        $documento->setCellValueByColumnAndRow(11, $j, 'Anexos');
        $documento->setCellValueByColumnAndRow(12, $j, 'Destinatario');
        $documento->setCellValueByColumnAndRow(13, $j, 'Unidad de Destino');
        $documento->setCellValueByColumnAndRow(14, $j, 'Quipux Agrocalidad');
        $documento->setCellValueByColumnAndRow(15, $j, 'Derivado');
        $documento->setCellValueByColumnAndRow(16, $j, 'Estado');
        $documento->setCellValueByColumnAndRow(17, $j, 'Documentos entregados');
        $documento->setCellValueByColumnAndRow(18, $j, 'Fecha de entrega');
        $documento->setCellValueByColumnAndRow(19, $j, 'Observaciones');
        $documento->setCellValueByColumnAndRow(20, $j, 'Origen trámite');
        
        foreach ($datos as $fila){
            $documento->setCellValueByColumnAndRow(1, $i, $fila['id_tramite']);
            $documento->setCellValueByColumnAndRow(2, $i, $fila['numero_tramite']);
            $documento->setCellValueByColumnAndRow(3, $i, $fila['fecha_creacion']);
            $documento->setCellValueByColumnAndRow(4, $i, $fila['ventanilla']);
            $documento->setCellValueByColumnAndRow(5, $i, $fila['identificador'] . ' - ' . $fila['nombre'] . ' ' . $fila['apellido']);
            $documento->setCellValueByColumnAndRow(6, $i, $fila['remitente']);
            $documento->setCellValueByColumnAndRow(7, $i, $fila['oficio_memo']);
            $documento->setCellValueByColumnAndRow(8, $i, $fila['factura']);
            $documento->setCellValueByColumnAndRow(9, $i, $fila['guia_quipux']);
            $documento->setCellValueByColumnAndRow(10, $i, $fila['asunto']);
            $documento->setCellValueByColumnAndRow(11, $i, $fila['anexos']);
            $documento->setCellValueByColumnAndRow(12, $i, $fila['destinatario']);
            $documento->setCellValueByColumnAndRow(13, $i, $fila['unidad_destino']);
            $documento->setCellValueByColumnAndRow(14, $i, $fila['quipux_agr']);
            $documento->setCellValueByColumnAndRow(15, $i, $fila['derivado']);
            $documento->setCellValueByColumnAndRow(16, $i, $fila['estado_tramite']);
            $documento->setCellValueByColumnAndRow(17, $i, $fila['documentos_entregados']);
            $documento->setCellValueByColumnAndRow(18, $i, ($fila['fecha_entrega']!=null?date('Y-m-d',strtotime($fila['fecha_entrega'])):''));
            $documento->setCellValueByColumnAndRow(19, $i, $fila['observaciones']);
            $documento->setCellValueByColumnAndRow(20, $i, $fila['origen_tramite']);
            $i++;
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="excelTramitesAdmin.xlsx"');
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $writer = IOFactory::createWriter($hoja, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
    
    public function generarReporteBitacora($tramites, $nombreArchivo) {
    	$jasper = new JasperReport();
    	$datosReporte = array();

    	$datosReporte = array(
    		'rutaReporte' => 'SeguimientoDocumental/vistas/reportes/imprimirBitacora.jasper',
    		'rutaSalidaReporte' => 'SeguimientoDocumental/archivos/bitacora/'.$nombreArchivo,
    		'tipoSalidaReporte' => array('pdf'),
    		'parametrosReporte' => array('numeroTramite' => $tramites),
    		'conexionBase' => 'SI'
    	);
    	
    	$jasper->generarArchivo($datosReporte);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar trámites usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarTramitesSeguimientoXFiltro($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['numero_tramite']) && ($arrayParametros['numero_tramite'] != '')) {
            $busqueda .= "and t.numero_tramite = '" . $arrayParametros['numero_tramite'] . "'";
        }
        
        if (isset($arrayParametros['remitente']) && ($arrayParametros['remitente'] != '')) {
            $busqueda .= "and upper(t.remitente) ilike upper('%" . $arrayParametros['remitente'] . "%')";
        }
        
        if (isset($arrayParametros['destinatario']) && ($arrayParametros['destinatario'] != '')) {
            $busqueda .= "and upper(t.destinatario) ilike upper('%" . $arrayParametros['destinatario'] . "%')";
        }
        
        if (isset($arrayParametros['quipux_agr']) && ($arrayParametros['quipux_agr'] != '')) {
            $busqueda .= "and upper(t.quipux_agr) ilike upper('%" . $arrayParametros['quipux_agr'] . "%')";
        }
        
        if (isset($arrayParametros['factura']) && ($arrayParametros['factura'] != '')) {
            $busqueda .= "and upper(t.factura) ilike upper('%" . $arrayParametros['factura'] . "%')";
        }
        
        if (isset($arrayParametros['id_unidad_destino']) && ($arrayParametros['id_unidad_destino'] != '')) {
            $busqueda .= "and t.id_unidad_destino_actual = '" . $arrayParametros['id_unidad_destino'] . "'";
        }
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '')) {
            $busqueda .= " and t.fecha_creacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' ";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '')) {
            $busqueda .= " and t.fecha_creacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00' ";
        }
        
        $consulta = "    SELECT
                             t.id_tramite, t.numero_tramite, COALESCE(s.fecha_creacion, t.fecha_creacion) as fecha_creacion,
                             t.id_ventanilla, ve.nombre as ventanilla,
                             t.identificador, fe.nombre, fe.apellido,
                             t.remitente, t.oficio_memo, t.factura,
                             t.guia_quipux, t.asunto, t.anexos,
                             t.destinatario, t.id_unidad_destino, t.quipux_agr, t.derivado,
                             t.estado_tramite, t.documentos_entregados, t.fecha_entrega,
                             t.observaciones, t.fecha_cierre, a.nombre as unidad_destino,
                             s.persona_recibe, s.fecha, s.observaciones_seguimiento, t.unidad_destino_actual
                         FROM
                             g_seguimiento_documental.tramites t
                             INNER JOIN g_seguimiento_documental.ventanillas ve ON t.id_ventanilla = ve.id_ventanilla
                             INNER JOIN g_uath.ficha_empleado fe ON t.identificador = fe.identificador
                             LEFT JOIN g_seguimiento_documental.seguimientos s ON t.id_tramite = s.id_tramite AND id_seguimiento = (SELECT max(id_seguimiento) FROM g_seguimiento_documental.seguimientos s1 WHERE s1.id_tramite = s.id_tramite)
                             LEFT JOIN g_estructura.area a ON COALESCE(s.id_unidad_destino,t.id_unidad_destino) = a.id_area
                         WHERE
                             t.id_ventanilla = " . $arrayParametros['id_ventanilla'] . " and
                             t.estado_tramite = '" . $arrayParametros['estado_tramite'] . "'" . $busqueda . "
                         ORDER BY
                            COALESCE(s.fecha_creacion, t.fecha_creacion) DESC;";
        
        
        return $this->modeloTramites->ejecutarSqlNativo($consulta);
    }
    
}

// ********clase para tcpdf******************************************
class PDF extends TCPDF
{    
    // Page header
    public function Header()
    {
        
    }
    
    public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false)
    {
        parent::AddPage();
    }
    
    // Tabla HTML
    public function tabla($header, $data) {    	
    	$lNegocioSeguimientos = new \Agrodb\SeguimientoDocumental\Modelos\SeguimientosLogicaNegocio();
    	
        $num_headers = count($header);
        $this->SetFont('times','',6);
        
        $tbl = '<table style="width: 100%;" cellspacing="0" cellpadding="1" border="1">
                    <tr style="text-align:center;">';
        
        $w = array(9, 5, 7,	8, 26, 10, 6, 16, 7, 6);
        
        for($i = 0; $i < $num_headers; ++$i) {
            $tbl .= '<th style="width: '.$w[$i].'%;"><b>'. $header[$i] .'</b></th>';
        }
        
        $tbl .= "   </tr>";
        
        foreach($data as $row) {
        	//Buscar ultima ruta
        	
        	$seguimiento = $lNegocioSeguimientos->buscarUltimoSeguimientoXTramite($row['id_tramite']);
        	$fila = $seguimiento->current();

            $tbl .= "<tr>";
            $tbl .= "<td>". $row['numero_tramite'] ."</td>";
            $tbl .= "<td>". date('Y-m-d',strtotime($row['fecha_creacion'])) ."</td>";
            $tbl .= "<td>". $row['guia_quipux'] ."</td>";
            $tbl .= "<td>". $row['oficio_memo'] ."</td>";
            $tbl .= "<td>". $row['asunto'] ."</td>";
            $tbl .= "<td>". ($row['estado_tramite']=='Ingresado'?$row['unidad_destino']:$fila['unidad_destino']) ."</td>";
            $tbl .= "<td>". $row['anexos'] ."</td>";
            $tbl .= "<td>". $row['quipux_agr'] ."</td>";
            $tbl .= "<td> </td>";
            $tbl .= "<td> </td>";
            $tbl .= "</tr>";
        }
        $tbl .= "</table>";
        
        $this->writeHTML($tbl, true, false, false, false, '');
    }
    
    // Tabla Color con celdas
    public function tablaColor($header, $data) {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
         $this->SetTextColor(255);
         $this->SetDrawColor(128, 0, 0);
         $this->SetLineWidth(0.3);
         $this->SetFont('', 'B');
         
         // Header
         $w = array(20, 10, 20, 25, 75, 45, 20, 20, 30, 20);
         
         $num_headers = count($header);
         $this->SetFont('times','',6);
         
         for($i = 0; $i < $num_headers; ++$i) {
            $this->MultiCell($w[$i], 1, $header[$i], 1, 'C', 1, 0);
         }
         
         $this->Ln();
         // Color and font restoration
         $this->SetFillColor(224, 235, 255);
         $this->SetTextColor(0);
         $this->SetFont('times','',5);
         // Data
         $fill = 0;
        
        foreach($data as $row) {
             $this->MultiCell($w[0], 1, $row['numero_tramite'], 0, '', 1, 0);
             $this->MultiCell($w[1], 1, date('Y-m-d',strtotime($row['fecha_creacion'])), 0, '', 1, 0);
             $this->MultiCell($w[2], 1, $row['guia_quipux'], 0, '', 1, 0);
             $this->MultiCell($w[3], 1, $row['oficio_memo'], 0, '', 1, 0);
             $this->MultiCell($w[4], 1, $row['asunto'], 0, '', 1, 0);
             $this->MultiCell($w[5], 1, $row['unidad_destino'], 0, '', 1, 0);
             $this->MultiCell($w[6], 1, $row['anexos'], 0, '', 1, 0);
             $this->MultiCell($w[7], 1, $row['quipux_agr'], 0, '', 1, 0);
             $this->MultiCell($w[8], 1, '', 0, '', 1, 0);
             $this->MultiCell($w[9], 1, '', 0, '', 1, 0);
             $this->Ln();
             $fill=!$fill;
         }
         
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    
    public function Footer()
    {
        
    }
}

