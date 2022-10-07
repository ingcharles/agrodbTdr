<?php
/**
 * Lógica del negocio de JornadaLaboralModelo
 *
 * Este archivo se complementa con el archivo JornadaLaboralControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-06-09
 * @uses JornadaLaboralLogicaNegocio
 * @package JornadaLaboral
 * @subpackage Modelos
 */
namespace Agrodb\JornadaLaboral\Modelos;

use Agrodb\JornadaLaboral\Modelos\IModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\Estructura\Modelos\AreaLogicaNegocio;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;

class JornadaLaboralLogicaNegocio implements IModelo{

	private $modeloJornadaLaboral = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloJornadaLaboral = new JornadaLaboralModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new JornadaLaboralModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdJornadaLaboral() != null && $tablaModelo->getIdJornadaLaboral() > 0){
			return $this->modeloJornadaLaboral->actualizar($datosBd, $tablaModelo->getIdJornadaLaboral());
		}else{
			unset($datosBd["id_jornada_laboral"]);
			
			$lNegocioEmpleado = new FichaEmpleadoLogicaNegocio();			
			$verificacionEmpleado = $lNegocioEmpleado->buscar($datos['identificador']);
			
			if($verificacionEmpleado->getIdentificador() != null){
				$arrayParametros = array('identificador' => $datos['identificador'], 'mes'=> $datos['mes'] );
				
				$existenciaRegistro = $this->buscarLista($arrayParametros);
				
				if($existenciaRegistro->count() == 0){
					return $this->modeloJornadaLaboral->guardar($datosBd);
				}else{
					Mensajes::fallo('Ya existe asignado un horario para el funcionario y mes indicado.');
				}
			}else{
				Mensajes::fallo('El identificador ingresado no se encuentra registrado.');
			}
		}
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloJornadaLaboral->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return JornadaLaboralModelo
	 */
	public function buscar($id){
		return $this->modeloJornadaLaboral->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloJornadaLaboral->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloJornadaLaboral->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarJornadaLaboral(){
		$consulta = "SELECT * FROM " . $this->modeloJornadaLaboral->getEsquema() . ". jornada_laboral";
		return $this->modeloJornadaLaboral->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar horario funcionario usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarHorarioFuncionarioPorFiltro($arrayParametros)
	{
		$busqueda = '';
		
		if (isset($arrayParametros['identificador']) && ($arrayParametros['identificador'] != '')) {
			$busqueda .= "and jl.identificador = '" . $arrayParametros['identificador'] . "'";
		}
		
		if (isset($arrayParametros['estadoRegistro']) && ($arrayParametros['estadoRegistro'] != '')) {
			$busqueda .= "and jl.estado_registro = '" . $arrayParametros['estado_registro'] . "'";
		}
		
		if (isset($arrayParametros['apellido']) && ($arrayParametros['apellido'] != '')) {
			$busqueda .= "and upper(fe.apellido) ilike upper('%" . $arrayParametros['apellido'] . "%')";
		}
		
		if (isset($arrayParametros['nombre']) && ($arrayParametros['nombre'] != '')) {
			$busqueda .= "and upper(fe.nombre) ilike upper('%" . $arrayParametros['nombre'] . "%')";
		}
		
		if (isset($arrayParametros['area']) && ($arrayParametros['area'] != '')) {
			
			$lNegocioArea = new AreaLogicaNegocio();
			
			$nombreArea = $lNegocioArea->buscarAreaPadreHijoPorCodigo($arrayParametros['area']);
			
			$busqueda .= "and c.direccion IN $nombreArea";
		}
		
		$consulta = "  SELECT
                        	fe.identificador,
							fe.apellido ||' '||fe.nombre as nombre,
							jl.mes,
							jl.id_jornada_laboral,
							jl.grupo,
							jl.horario
                        FROM
                        	g_uath.jornada_laboral jl
                        	INNER JOIN g_uath.ficha_empleado fe ON jl.identificador = fe.identificador
                            INNER JOIN g_uath.datos_contrato c ON fe.identificador = c.identificador
                        WHERE
							fe.estado_empleado = 'activo' and 
							c.estado = '1'
							" . $busqueda . ";";

		return $this->modeloJornadaLaboral->ejecutarSqlNativo($consulta);
	}
	
	public function leerArchivoExcelJornadaLaboral($datos){
		
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
			$proceso = $this->modeloJornadaLaboral->getAdapter()->getDriver()->getConnection();
			
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
				$datoExceso = $documento->getActiveSheet()->getCell('I3')->getValue();
				if(!$datoExceso){
					
					//Inicio de lectura fila 3
					for ($i = 3; $i <= count($hojaActual); $i++) {
						
						$identificador = $hojaActual[$i]['D'];
						$mes = $hojaActual[$i]['C'];
						
						$lNegocioEmpleado = new FichaEmpleadoLogicaNegocio();
						
						$verificacionEmpleado = $lNegocioEmpleado->buscar($identificador);
						
						
						if($verificacionEmpleado->getIdentificador() == null){
							continue;
						}
						
						$arrayParametros = array('identificador' => $identificador, 'mes'=> $mes );
						
						$existenciaRegistro = $this->buscarLista($arrayParametros);
						
						if($existenciaRegistro->count() != 0){
							continue;
						}
						
						$datosExcel = array(
							'identificador' => $identificador,
							'grupo' => $hojaActual[$i]['A'],
							'horario' => $hojaActual[$i]['B'],
							'mes' => $mes
						);
						
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
}
