<?php
/**
 * Lógica del negocio de ValijasModelo
 *
 * Este archivo se complementa con el archivo ValijasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-02-13
 * @uses    ValijasLogicaNegocio
 * @package SeguimientoDocumental
 * @subpackage Modelos
 */
namespace Agrodb\SeguimientoDocumental\Modelos;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\SeguimientoDocumental\Modelos\IModelo;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ValijasLogicaNegocio implements IModelo
{

    private $modeloValijas = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloValijas = new ValijasModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $datos['identificador'] = $_SESSION['usuario'];

        $tablaModelo = new ValijasModelo($datos);

        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdValija() != null && $tablaModelo->getIdValija() > 0) {
            return $this->modeloValijas->actualizar($datosBd, $tablaModelo->getIdValija());
        } else {
            unset($datosBd["id_valija"]);
            return $this->modeloValijas->guardar($datosBd);
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
        $this->modeloValijas->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ValijasModelo
     */
    public function buscar($id)
    {
        return $this->modeloValijas->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloValijas->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloValijas->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarValijas()
    {
        $consulta = "SELECT * FROM " . $this->modeloValijas->getEsquema() . ". valijas";
        return $this->modeloValijas->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar valijas usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarValijasXFiltro($arrayParametros)
    {
        $busqueda = '';

       if (isset($arrayParametros['numero_valija']) && ($arrayParametros['numero_valija'] != '')) {
            $busqueda .= " and v.numero_valija = '" . $arrayParametros['numero_valija'] . "'";
        }
        
        if (isset($arrayParametros['destinatario']) && ($arrayParametros['destinatario'] != '')) {
            $busqueda .= " and upper(v.destinatario) ilike upper('%" . $arrayParametros['destinatario'] . "%')";
        }
        
        if (isset($arrayParametros['guia_correo']) && ($arrayParametros['guia_correo'] != '')) {
            $busqueda .= " and upper(v.guia_correo) ilike upper('%" . $arrayParametros['guia_correo'] . "%')";
        }
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '')) {
        	$busqueda .= " and v.fecha_creacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' ";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '')) {
        	$busqueda .= " and v.fecha_creacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00' ";
        }

        $consulta = "  SELECT 
                        	v.id_valija, v.numero_valija, v.fecha_creacion, 
                        	v.id_ventanilla, ve.nombre as ventanilla, 
                        	v.identificador, fe.nombre, fe.apellido,
                        	v.guia_correo, v.destinatario, v.direccion, 
                        	v.telefono, v.id_pais, v.pais, 
                        	v.id_provincia, v.provincia, v.id_canton, v.canton, 
                        	v.referencia, v.email, v.descripcion, 
                        	v.fecha_entrega, v.estado_entrega
                        FROM 
                        	g_seguimiento_documental.valijas v 
                        	INNER JOIN g_seguimiento_documental.ventanillas ve ON v.id_ventanilla = ve.id_ventanilla
                            INNER JOIN g_uath.ficha_empleado fe ON v.identificador = fe.identificador 
                        WHERE  
                            v.id_ventanilla = " . $arrayParametros['id_ventanilla'] . " and
                            v.estado_entrega = '" . $arrayParametros['estado_entrega'] . "'" . $busqueda . "
						ORDER BY
							v.numero_valija ASC;";

        return $this->modeloValijas->ejecutarSqlNativo($consulta);
    }
	
	/**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar valijas usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarValijasNacionalXFiltro($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['estado_entrega']) && ($arrayParametros['estado_entrega'] != 'Todos')) {
        	$busqueda .= " and v.estado_entrega = '" . $arrayParametros['estado_entrega'] . "' ";
        }
        
        if (isset($arrayParametros['id_ventanilla']) && ($arrayParametros['id_ventanilla'] != '')) {
            $busqueda .= " and v.id_ventanilla = '" . $arrayParametros['id_ventanilla'] . "'";
        }
        
        if (isset($arrayParametros['numero_valija']) && ($arrayParametros['numero_valija'] != '')) {
            $busqueda .= " and v.numero_valija = '" . $arrayParametros['numero_valija'] . "'";
        }
        
        if (isset($arrayParametros['destinatario']) && ($arrayParametros['destinatario'] != '')) {
            $busqueda .= " and upper(v.destinatario) ilike upper('%" . $arrayParametros['destinatario'] . "%')";
        }
        
        if (isset($arrayParametros['guia_correo']) && ($arrayParametros['guia_correo'] != '')) {
            $busqueda .= " and upper(v.guia_correo) ilike upper('%" . $arrayParametros['guia_correo'] . "%')";
        }
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '')) {
            $busqueda .= " and v.fecha_creacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' ";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '')) {
            $busqueda .= " and v.fecha_creacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00' ";
        }

        $busqueda = ltrim($busqueda, " and");

        $consulta = "  SELECT
                        	v.id_valija, v.numero_valija, v.fecha_creacion,
                        	v.id_ventanilla, ve.nombre as ventanilla,
                        	v.identificador, fe.nombre, fe.apellido,
                        	v.guia_correo, v.destinatario, v.direccion,
                        	v.telefono, v.id_pais, v.pais,
                        	v.id_provincia, v.provincia, v.id_canton, v.canton,
                        	v.referencia, v.email, v.descripcion,
                        	v.fecha_entrega, v.estado_entrega,
                            v.nombre_entrega, v.observaciones,
                            v.unidad_origen, v.remitente
                        FROM
                        	g_seguimiento_documental.valijas v
                        	INNER JOIN g_seguimiento_documental.ventanillas ve ON v.id_ventanilla = ve.id_ventanilla
                            INNER JOIN g_uath.ficha_empleado fe ON v.identificador = fe.identificador
                        WHERE
                            " . $busqueda . "
						ORDER BY
							v.numero_valija ASC;";
        
        return $this->modeloValijas->ejecutarSqlNativo($consulta);
    }
    
    public function buscarNumeroValija($idCodigoVentanilla)
    {
    	$anio = date("Y");
    	$formatoCodigo = 'AGR-ENV-'.$idCodigoVentanilla.'-'.$anio.'-';
    	
        $consulta = "SELECT
                        max(split_part(numero_valija, '".$formatoCodigo."' , 2)::int) as numero 
                     FROM
                        g_seguimiento_documental.valijas
                     WHERE 
                        numero_valija LIKE '%".$formatoCodigo."%';";
        
        $codigo = $this->modeloValijas->ejecutarSqlNativo($consulta);
        
        $fila = $codigo->current();
        
        $codigoValija = array( 'numero' => $fila['numero']);
        
        $incremento = $codigoValija['numero'] + 1;
        $codigoValija = $formatoCodigo.str_pad($incremento, 5, "0", STR_PAD_LEFT);
        
        return $codigoValija;
    }
    
    /**
     * Obtiene el arcjivo excel para ser validado y almacenado en base de datos.
     * @param array $datos
     * @return int
     */
    
    public function leerArchivoExcelValijas($datos){
    	
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
    		$proceso = $this->modeloValijas->getAdapter()->getDriver()->getConnection();
    		
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
    			$datoExceso = $documento->getActiveSheet()->getCell('M3')->getValue();
    			if(!$datoExceso){
    				$lNegocioUsuariosVentanilla = new \Agrodb\SeguimientoDocumental\Modelos\UsuariosVentanillaLogicaNegocio();
    				$datosUsuario = $lNegocioUsuariosVentanilla->buscarDatosUsuarioTecnico($identificador);

    				$lNegocioLozalizacion = new \Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio();
    				$lNegocioArea = new \Agrodb\Estructura\Modelos\AreaLogicaNegocio();

    				for ($i = 3; $i <= count($hojaActual); $i++) {
    				
    					$numeroValija = $this->buscarNumeroValija($datosUsuario['codigoVentanilla']);
    					
    					$pais = ($hojaActual[$i]['E'] == '' ?'null' : trim($hojaActual[$i]['E']));
    					$provincia = ($hojaActual[$i]['F'] == '' ?'null' : trim($hojaActual[$i]['F']));
    					$canton = ($hojaActual[$i]['G'] == '' ?'null' : trim($hojaActual[$i]['G']));
    					
    					//Validar si existe el código de valija de correo, validación unique en base
    					$guiaCorreo = $this->obtenerGuiaCorreoValija(str_replace(" ", "", trim($hojaActual[$i]['A'])));
    					
    					if($guiaCorreo == 'SI'){
    					    continue;
    					}
    					
    					$datosExcel = array(
    						'descripcion' => $hojaActual[$i]['O'],
    						'guia_correo' => $hojaActual[$i]['A'],
    						'destinatario' => $hojaActual[$i]['B'],
    						'direccion' => $hojaActual[$i]['C'],
    						'telefono' => $hojaActual[$i]['D'],
    						'referencia' => $hojaActual[$i]['H'],
    						'email' => $hojaActual[$i]['I'],
    						'numero_valija' => $numeroValija,
    						'id_ventanilla' => $datosUsuario['idVentanilla'],
    						'estado_entrega' => 'Enviado',
    					    'remitente' => $hojaActual[$i]['K'],
    					    'descripcion' => $hojaActual[$i]['L'],
    					);
    					
    					$localizacionPais = $lNegocioLozalizacion->buscarPaisesPorNombre($pais);
    					
    					if($localizacionPais->count() > 0){
    						$datoPais = $localizacionPais->current();
    						$datosExcel += array('id_pais' => $datoPais->id_localizacion, 'pais' => $datoPais->nombre);

    						$localizacionProvincia = $lNegocioLozalizacion->buscarLocalizacionPorNombrePorIdentificadorPadre($datoPais->id_localizacion, $provincia, 1);
    						if($localizacionProvincia->count() > 0){
    							$datoProvincia = $localizacionProvincia->current();
    							$datosExcel += array('id_provincia' => $datoProvincia->id_localizacion, 'provincia' => $datoProvincia->nombre);
    							
    							$localizacionCanton = $lNegocioLozalizacion->buscarLocalizacionPorNombrePorIdentificadorPadre($datoProvincia->id_localizacion, $canton, 2);
    							
    							if($localizacionCanton->count() > 0){
    								$datoCanton = $localizacionCanton->current();
    								$datosExcel += array('id_canton' => $datoCanton->id_localizacion, 'canton' => $datoCanton->nombre);
    							}
    						}
    					}
    					
    					$datosUnidad = $lNegocioArea->buscarAreaPorCodigo($hojaActual[$i]['J']);
    					
    					if($datosUnidad->count() > 0){
    					    $unidadDestino = $datosUnidad->current();
    					    $datosExcel += array('id_unidad_origen' => $unidadDestino->id_area);
    					    $datosExcel += array('unidad_origen' => $unidadDestino->nombre);
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
    
    public function obtenerGuiaCorreoValija($guiaCorreo) {
        $existenciaNumero = $this->modeloValijas->buscarLista("guia_correo = '" . $guiaCorreo . "';");
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
    	$documento->setCellValueByColumnAndRow(1, 2, 'Número');
    	$documento->setCellValueByColumnAndRow(2, 2, 'Número de Trámite');
    	$documento->setCellValueByColumnAndRow(3, 2, 'Destinatario');
    	$documento->setCellValueByColumnAndRow(4, 2, 'Código de Guía');
    	$documento->setCellValueByColumnAndRow(5, 2, 'Estado');
    	
    	foreach ($datos as $fila){
    		$documento->setCellValueByColumnAndRow(1, $i, $j);
    		$documento->setCellValueByColumnAndRow(2, $i, $fila['numero_valija']);
    		$documento->setCellValueByColumnAndRow(3, $i, $fila['destinatario']);
    		$documento->setCellValueByColumnAndRow(4, $i, $fila['guia_correo']);
    		$documento->setCellValueByColumnAndRow(5, $i, $fila['estado_entrega']);
    		$estado = $fila['estado_entrega'];
    		$i++;
    		$j++;
    	}
    	
    	$documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Valijas '.$estado);
    	
    	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    	header('Content-Disposition: attachment;filename="excelValijas.xlsx"');
    	header("Pragma: no-cache");
    	header("Expires: 0");

    	$writer = IOFactory::createWriter($hoja, 'Xlsx');
    	$writer->save('php://output');
    	exit();
    }
    
    public function exportarArchivoExcelAdministrador($datos){
        
        $hoja = new Spreadsheet();
        $documento = $hoja->getActiveSheet();
        $i = 3;
        $j = 2;
        
        $documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Valijas');
        
        $documento->setCellValueByColumnAndRow(1, $j, 'ID');
        $documento->setCellValueByColumnAndRow(2, $j, 'Número');
        $documento->setCellValueByColumnAndRow(3, $j, 'Fecha creación');
        $documento->setCellValueByColumnAndRow(4, $j, 'Ventanilla');
        $documento->setCellValueByColumnAndRow(5, $j, 'Técnico');
        $documento->setCellValueByColumnAndRow(6, $j, 'Guía de Correo');
        $documento->setCellValueByColumnAndRow(7, $j, 'Unidad de Origen');
        $documento->setCellValueByColumnAndRow(8, $j, 'Remitente');        
        $documento->setCellValueByColumnAndRow(9, $j, 'Destinatario');
        $documento->setCellValueByColumnAndRow(10, $j, 'Dirección');
        $documento->setCellValueByColumnAndRow(11, $j, 'Teléfono');
        $documento->setCellValueByColumnAndRow(12, $j, 'País');
        $documento->setCellValueByColumnAndRow(13, $j, 'Provincia');
        $documento->setCellValueByColumnAndRow(14, $j, 'Cantón');
        $documento->setCellValueByColumnAndRow(15, $j, 'Referencia');
        $documento->setCellValueByColumnAndRow(16, $j, 'E-mail');
        $documento->setCellValueByColumnAndRow(17, $j, 'Descripción');
        $documento->setCellValueByColumnAndRow(18, $j, 'Estado');
        $documento->setCellValueByColumnAndRow(19, $j, 'Persona que recibe');
        $documento->setCellValueByColumnAndRow(20, $j, 'Fecha de Entrega');
        $documento->setCellValueByColumnAndRow(21, $j, 'Observaciones');
        
        
        foreach ($datos as $fila){
            $documento->setCellValueByColumnAndRow(1, $i, $fila['id_valija']);
            $documento->setCellValueByColumnAndRow(2, $i, $fila['numero_valija']);
            $documento->setCellValueByColumnAndRow(3, $i, $fila['fecha_creacion']);
            $documento->setCellValueByColumnAndRow(4, $i, $fila['ventanilla']);
            $documento->setCellValueByColumnAndRow(5, $i, $fila['identificador'] . ' - ' . $fila['nombre'] . ' ' . $fila['apellido']);
            $documento->setCellValueByColumnAndRow(6, $i, $fila['guia_correo']);
            $documento->setCellValueByColumnAndRow(7, $i, $fila['unidad_origen']);
            $documento->setCellValueByColumnAndRow(8, $i, $fila['remitente']);
            
            $documento->setCellValueByColumnAndRow(9, $i, $fila['destinatario']);
            $documento->setCellValueByColumnAndRow(10, $i, $fila['direccion']);
            $documento->setCellValueByColumnAndRow(11, $i, $fila['telefono']);
            $documento->setCellValueByColumnAndRow(12, $i, $fila['pais']);
            $documento->setCellValueByColumnAndRow(13, $i, $fila['provincia']);
            $documento->setCellValueByColumnAndRow(14, $i, $fila['canton']);
            $documento->setCellValueByColumnAndRow(15, $i, $fila['referencia']);
            $documento->setCellValueByColumnAndRow(16, $i, $fila['email']);
            $documento->setCellValueByColumnAndRow(17, $i, $fila['descripcion']);
            $documento->setCellValueByColumnAndRow(18, $i, $fila['estado_entrega']);
            $documento->setCellValueByColumnAndRow(19, $i, $fila['nombre_entrega']);
            $documento->setCellValueByColumnAndRow(20, $i, $fila['fecha_entrega']);
            $documento->setCellValueByColumnAndRow(21, $i, $fila['observaciones']);
            $i++;
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="excelValijas.xlsx"');
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $writer = IOFactory::createWriter($hoja, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
    
    public function exportarArchivoExcelAdministradorMensual($datos){
        
        $hoja = new Spreadsheet();
        $documento = $hoja->getActiveSheet();
        $i = 3;
        $j = 2;
        
        $documento->setCellValueByColumnAndRow(1, 1, 'Informe mensual de correspondencia');
        
        $documento->setCellValueByColumnAndRow(1, $j, 'ID');
        $documento->setCellValueByColumnAndRow(2, $j, 'Número de guía');
        $documento->setCellValueByColumnAndRow(3, $j, 'Fecha de expedición');
        $documento->setCellValueByColumnAndRow(4, $j, 'Destinatario');
        $documento->setCellValueByColumnAndRow(5, $j, 'Clase de envío');
        $documento->setCellValueByColumnAndRow(6, $j, 'Lugar de destino');
        $documento->setCellValueByColumnAndRow(7, $j, 'Departamento que hace el envío');
        $documento->setCellValueByColumnAndRow(8, $j, 'Persona que recibe la correspondencia');
        $documento->setCellValueByColumnAndRow(9, $j, 'Fecha de recepción');
        
        
        foreach ($datos as $fila){
            $documento->setCellValueByColumnAndRow(1, $i, $fila['id_valija']);
            $documento->setCellValueByColumnAndRow(2, $i, $fila['guia_correo']);
            $documento->setCellValueByColumnAndRow(3, $i, $fila['fecha_creacion']);
            $documento->setCellValueByColumnAndRow(4, $i, $fila['destinatario']);
            $documento->setCellValueByColumnAndRow(5, $i, $fila['descripcion']);
            $documento->setCellValueByColumnAndRow(6, $i, $fila['canton']);
            $documento->setCellValueByColumnAndRow(7, $i, $fila['unidad_origen'] . ' - ' . $fila['remitente']);
            $documento->setCellValueByColumnAndRow(8, $i, $fila['destinatario']);
            $documento->setCellValueByColumnAndRow(9, $i, $fila['fecha_entrega']);
            $i++;
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="excelValijas.xlsx"');
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $writer = IOFactory::createWriter($hoja, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
    
    /**
     * Calcular Porcentaje de Valijas ingresadas y atendidas.
     *
     * @return array|ResultSet
     */
    public function porcentajeValijasAtendidasIngresadas($arrayParametros)
    {
        $estadosTramites = "'Enviado', 'Entregado'";
        $estadoAtendido = "'Entregado'";
        
        $parametrosAtendido = $arrayParametros;
        $parametrosAtendido['estado_entrega'] = $estadoAtendido;
        
        $parametrosIngresados = $arrayParametros;
        $parametrosIngresados['estado_entrega'] = $estadosTramites;
        
        //Trámites con estado Entregado
        $tramitesAtendidos = $this->contarValijasNacionalXEstado($parametrosAtendido);
        //echo $tramitesAtendidos->current()->num_valijas;
        
        //Todos los trámites registrados
        $tramitesIngresados = $this->contarValijasNacionalXEstado($parametrosIngresados);
        //echo $tramitesIngresados->current()->num_valijas;
        
        $porcentaje = ($tramitesAtendidos->current()->num_valijas * 100)/($tramitesIngresados->current()->num_valijas);
        
        $arrayResultado = array('atendidos' => $tramitesAtendidos->current()->num_valijas,
            'ingresados' => $tramitesIngresados->current()->num_valijas,
            'porcentaje' => $porcentaje
        );
        
        return $arrayResultado;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar trámites a nivel nacional usando filtros.
     *
     * @return array|ResultSet
     */
    public function contarValijasNacionalXEstado($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['id_ventanilla']) && ($arrayParametros['id_ventanilla'] != '')) {
            $busqueda .= "and v.id_ventanilla = '" . $arrayParametros['id_ventanilla'] . "'";
        }
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '')) {
            $busqueda .= " and v.fecha_creacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' ";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '')) {
            $busqueda .= " and v.fecha_creacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00' ";
        }
        
        $consulta = "  SELECT
                        	count(v.id_valija) as num_valijas
                        FROM
                        	g_seguimiento_documental.valijas v
                        WHERE
                        	v.estado_entrega in (" . $arrayParametros['estado_entrega'] . ")". $busqueda . ";";
        
        //echo $consulta;
        return $this->modeloValijas->ejecutarSqlNativo($consulta);
    }
}
