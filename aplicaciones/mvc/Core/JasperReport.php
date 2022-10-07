<?php
/**
 * https://github.com/cossou/JasperPHP
 */
namespace Agrodb\Core;

use JasperPHP\JasperPHP;

/**
 * Generación de reportes Jasper Report PHP
 */
class JasperReport{

	private $jasper = null;

	function __construct(){
		$this->jasper = new JasperPHP();
	}

	/**
	 * Ejecuta método de generación de archivo en jasperReport
	 *
	 * @return type
	 */
	public function generarArchivo($parametros){
		
		$parametros['parametrosReporte'] += array('REPORT_LOCALE' => 'es_ES');
		
		if($parametros['conexionBase'] == 'NO'){
			$conexionBase = array();
		}else{
			$conexionBase = array(
				'driver' => 'postgres',
				'username' => DB_USER,
				'host' => DB_HOST,
				'database' => DB_NAME,
				'password' => DB_PASS,
				'port' => DB_PORT
			);
		}
		
		$this->jasper->process(
				__DIR__ . '/../modulos/'.$parametros['rutaReporte'], // Ruta del formato .jasper
				__DIR__ . '/../modulos/'.$parametros['rutaSalidaReporte'], //Ruta de salida del archivo, nombre del archivo sin extensión
				$parametros['tipoSalidaReporte'], // Tipo de salida del reporte, los valores aceptados son: 'pdf', 'rtf', 'xls', 'xlsx', 'docx', 'odt', 'ods', 'pptx', 'csv', 'html', 'xhtml', 'xml', 'jrprint'
				$parametros['parametrosReporte'], // Parametros del reporte, en el caso de no tener parametros enviar un array vacío array();
				$conexionBase // Conexión a la base de datos
			)->execute();
			
			// Depuración de errores
			/*exec($this->jasper->output().' 2>&1', $output);
			 print_r($output);*/
		
	}
}