<?php
 /**
 * Lógica del negocio de SerieAretesModelo
 *
 * Este archivo se complementa con el archivo SerieAretesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-09-08
 * @uses    SerieAretesLogicaNegocio
 * @package IngresoCuvsAretes
 * @subpackage Modelos
 */
  namespace Agrodb\IngresoCuvsAretes\Modelos;
  
  use Agrodb\IngresoCuvsAretes\Modelos\IModelo;
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use Agrodb\Core\Constantes;
  use Agrodb\Core\Mensajes;
 
class SerieAretesLogicaNegocio implements IModelo 
{

	 private $modeloSerieAretes = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloSerieAretes = new SerieAretesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new SerieAretesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdSerieAretes() != null && $tablaModelo->getIdSerieAretes() > 0) {
		return $this->modeloSerieAretes->actualizar($datosBd, $tablaModelo->getIdSerieAretes());
		} else {
		unset($datosBd["id_serie_aretes"]);
		return $this->modeloSerieAretes->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloSerieAretes->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return SerieAretesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloSerieAretes->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloSerieAretes->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloSerieAretes->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarSerieAretes()
	{
	$consulta = "SELECT * FROM ".$this->modeloSerieAretes->getEsquema().". serie_aretes";
		 return $this->modeloSerieAretes->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Obtiene el arcjivo excel para ser validado y almacenado en base de datos.
	 * @param array $datos
	 * @return int
	 */
	public function leerArchivoExcelIdentificadores($datos){

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
	        
	        $arrayIdentificadores = array();
	        
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
	                        
	                        $identificador = $registroActual;	                        
	                        
	                        if(strlen($identificador) == 11){    
	                      
	                            $arrayIdentificadores [] = $identificador;
	                            
	                        }else{
	                            Mensajes::fallo("El código del identificador debe tener 11 dígitos ". " - " . $i . "A");
	                            $error = true;
	                            break;
	                        }
	                        
	                    } else{
	                        Mensajes::fallo("El número de arete no puede estar vacío". " - " . $i . "A");
	                        $error = true;
	                        break;
	                    }                    
	                    
	                }
	                
	                if(!$error){	                                       
	                    
	                    $arrayIdentificadores = array_unique($arrayIdentificadores);
	                    
	                    /*echo "<pre>";
	                    print_r($arrayIdentificadores);
	                    echo "<pre>";*/
	                    
	                    $identificadores = "";
	                    
	                    $identificadores = "array['".implode("', '", $arrayIdentificadores)."']";
	                    
	                    $this->guardarIdentificadores($identificadores, $observacion . '-' . $identificadorResponsable);
	                    
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
	public function guardarIdentificadores($datosIdentificadores, $observacion)
	{
	    $consulta = "SELECT g_catalogos.insertar_serie_aretes($datosIdentificadores, '" . $observacion . "')";
	    return $this->modeloSerieAretes->ejecutarSqlNativo($consulta);
	}
	

}
