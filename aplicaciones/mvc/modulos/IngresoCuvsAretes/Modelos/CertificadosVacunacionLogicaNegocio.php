<?php
 /**
 * Lógica del negocio de CertificadosVacunacionModelo
 *
 * Este archivo se complementa con el archivo CertificadosVacunacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-09-08
 * @uses    CertificadosVacunacionLogicaNegocio
 * @package IngresoCuvsAretes
 * @subpackage Modelos
 */
  namespace Agrodb\IngresoCuvsAretes\Modelos;
  
  use Agrodb\IngresoCuvsAretes\Modelos\IModelo;
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use Agrodb\Core\Constantes;
  use Agrodb\Core\Mensajes;
 
class CertificadosVacunacionLogicaNegocio implements IModelo 
{

	 private $modeloCertificadosVacunacion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCertificadosVacunacion = new CertificadosVacunacionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new CertificadosVacunacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCertificadoVacunacion() != null && $tablaModelo->getIdCertificadoVacunacion() > 0) {
		return $this->modeloCertificadosVacunacion->actualizar($datosBd, $tablaModelo->getIdCertificadoVacunacion());
		} else {
		unset($datosBd["id_certificado_vacunacion"]);
		return $this->modeloCertificadosVacunacion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloCertificadosVacunacion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return CertificadosVacunacionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloCertificadosVacunacion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCertificadosVacunacion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCertificadosVacunacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCertificadosVacunacion()
	{
	$consulta = "SELECT * FROM ".$this->modeloCertificadosVacunacion->getEsquema().". certificados_vacunacion";
		 return $this->modeloCertificadosVacunacion->ejecutarSqlNativo($consulta);
	}
		
	/**
	 * Obtiene el arcjivo excel para ser validado y almacenado en base de datos.
	 * @param array $datos
	 * @return int
	 */
	public function leerArchivoExcelCuvs($datos){
	    
	    $identificadorResponsable = $_SESSION['usuario'];
	    $observacion = $datos['observacion'];
	    $rutaArchivo = $datos['archivo'];
	    $extension = explode('.',$rutaArchivo);
	    
	    $error = false;
	    
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
	        
	        $arrayCuvs = array();
	        
	        $reader = IOFactory::createReader($tipo);
	        $reader->setReadDataOnly(true);
	        $reader->setLoadSheetsOnly(0);
	        $documento = $reader->load(Constantes::RUTA_SERVIDOR_OPT.'/'.Constantes::RUTA_APLICACION.'/'.$rutaArchivo);
	        
	        $hojaActual = $documento->getActiveSheet()->toArray(null, true, true, true);
	        
	        $archivoVacio = $documento->getActiveSheet()->getCell('A2')->getValue();
	        
	        if($archivoVacio){
	            
	            $datoExceso = $documento->getActiveSheet()->getCell('B2')->getValue();
	            
	            if(!$datoExceso){
	                
	                for ($i = 2; $i <= count($hojaActual); $i++) {
	                    
	                    $registroActual = trim($hojaActual[$i]['A']);
	                    
	                    if($registroActual !== ''){
	                        
	                        $cuv = $registroActual;
	                        
	                        if(strlen($cuv) == 20){
	                            
	                            $arrayCuvs [] = $cuv;
	                            
	                        }else{
	                            Mensajes::fallo("El código del certificado debe tener 20 dígitos ". " - " . $i . "A");
	                            $error = true;
	                            break;
	                        }
	                        
	                    } else{
	                        Mensajes::fallo("El número de certificado no puede estar vacío". " - " . $i . "A");
	                        $error = true;
	                        break;
	                    }
	                    
	                }
	                
	                if(!$error){
	                    
	                    $arrayCuvs = array_unique($arrayCuvs);
	                    
	                    /*echo "<pre>";
	                     print_r($arrayCuvs);
	                     echo "<pre>";*/
	                    
	                    $cuvs = "";
	                    
	                    $cuvs = "array['".implode("', '", $arrayCuvs)."']";
	                    
	                    $this->guardarCuvs($cuvs, $observacion . '-' . $identificadorResponsable);
	                    
	                    Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
	                }
	                
	            }else{
	                Mensajes::fallo(Constantes::ARCHIVO_MAL_CONSTRUIDO);
	            }
	        }else{
	            Mensajes::fallo(Constantes::ARCHIVO_VACIO);
	        }
	    }catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
	        Mensajes::fallo(Constantes::ERROR_AL_GUARDAR);
	    }
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 */
	public function guardarCuvs($datosCuvs, $observacion)
	{
	    $consulta = "SELECT g_catalogos.insertar_serie_cuvs($datosCuvs, '" . $observacion . "')";
	    return $this->modeloCertificadosVacunacion->ejecutarSqlNativo($consulta);
	}
	
	                
}
