<?php
/**
 * Lógica del negocio de CultivosModelo
 *
 * Este archivo se complementa con el archivo CultivosControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-03-24
 * @uses CultivosLogicaNegocio
 * @package PlagasLaboratorio
 * @subpackage Modelos
 */
namespace Agrodb\PlagasLaboratorio\Modelos;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

use Agrodb\PlagasLaboratorio\Modelos\IModelo;

class CultivosLogicaNegocio implements IModelo{

	private $modeloCultivos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloCultivos = new CultivosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new CultivosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCultivo() != null && $tablaModelo->getIdCultivo() > 0){
			return $this->modeloCultivos->actualizar($datosBd, $tablaModelo->getIdCultivo());
		}else{
			unset($datosBd["id_cultivo"]);
			return $this->modeloCultivos->guardar($datosBd);
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
		$this->modeloCultivos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return CultivosModelo
	 */
	public function buscar($id){
		return $this->modeloCultivos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloCultivos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloCultivos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarCultivos(){
		$consulta = "SELECT * FROM " . $this->modeloCultivos->getEsquema() . ". cultivos";
		return $this->modeloCultivos->ejecutarSqlNativo($consulta);
	}
	
	public function buscarCultivoPlagaDetalleXFiltro($arrayParametros) {
		
		$busqueda = '';
		
		if (isset($arrayParametros['id_cultivo']) && ($arrayParametros['id_cultivo'] != '')) {
			$busqueda .= " and c.id_cultivo = '" . $arrayParametros['id_cultivo'] . "' ";
		}
		
		if (isset($arrayParametros['id_plaga']) && ($arrayParametros['id_plaga'] != '')) {
			$busqueda .= " and p.id_plaga = '" . $arrayParametros['id_plaga'] . "'";
		}
		
		if (isset($arrayParametros['id_provincia']) && ($arrayParametros['id_provincia'] != '')) {
			$busqueda .= " and pl.id_provincia = '" . $arrayParametros['id_provincia'] . "'";
		}
		
		$busqueda = ltrim($busqueda, " and");
		
		if(strlen($busqueda) != 0){
			$busqueda = 'WHERE '.$busqueda;
		}
		
		$consulta = "  SELECT
                        	c.nombre_comun,
							c.nombre_cientifico,
							p.nombre_cientifico as nombre_cientifico_plaga,
							p.familia,
							p.numero_primer_informe,
							p.identificado_por,
							p.especimen,
							p.ubicacion_especimen,
							p.confirmacion_diagnostico,
							p.observacion,
							pl.fecha_ingreso,
							pl.numero_reporte,
							pl.nombre_provincia,
							pl.identificado_por as identificador_por_detalle
                        FROM
                        	g_plagas_laboratorio.cultivos c
                        	FULL OUTER JOIN g_plagas_laboratorio.plagas p ON p.id_cultivo = c.id_cultivo 
                            FULL OUTER JOIN g_plagas_laboratorio.plagas_detalle pl ON p.id_plaga = pl.id_plaga
                            ". $busqueda ."
                        ORDER BY
                            c.id_cultivo, p.id_plaga;";
		
		return $this->modeloCultivos->ejecutarSqlNativo($consulta);
	}
	
	public function exportarArchivoExcelCultivoPlagaDetalle($datos){
		
		$hoja = new Spreadsheet();
		$documento = $hoja->getActiveSheet();
		$i = 3;
		$j = 2;
		
		$documento->setCellValueByColumnAndRow(1, 1, 'Reporte resultado de plagas');
		
		$documento->setCellValueByColumnAndRow(1, $j, 'Nombre común cultivo');
		$documento->setCellValueByColumnAndRow(2, $j, 'Nombre científico cultivo');
		$documento->setCellValueByColumnAndRow(3, $j, 'Nombre científico plaga');
		$documento->setCellValueByColumnAndRow(4, $j, 'Familia plaga');
		$documento->setCellValueByColumnAndRow(5, $j, 'Número del primer informe');
		$documento->setCellValueByColumnAndRow(6, $j, 'Identificado por');
		$documento->setCellValueByColumnAndRow(7, $j, 'Dispone de espécimen');
		$documento->setCellValueByColumnAndRow(8, $j, 'Ubicación del espécimen');
		$documento->setCellValueByColumnAndRow(9, $j, 'Confirmado por');
		$documento->setCellValueByColumnAndRow(10, $j, 'Observación');
		$documento->setCellValueByColumnAndRow(11, $j, 'Fecha de ingreso');
		$documento->setCellValueByColumnAndRow(12, $j, 'Numero de reportes ');
		$documento->setCellValueByColumnAndRow(13, $j, 'Provincia');
		$documento->setCellValueByColumnAndRow(14, $j, 'Identificado por');
		
		foreach ($datos as $fila){
			$documento->setCellValueByColumnAndRow(1, $i, $fila['nombre_comun']);
			$documento->setCellValueByColumnAndRow(2, $i, $fila['nombre_cientifico']);
			$documento->setCellValueByColumnAndRow(3, $i, $fila['nombre_cientifico_plaga']);
			$documento->setCellValueByColumnAndRow(4, $i, $fila['familia']);
			$documento->setCellValueByColumnAndRow(5, $i, $fila['numero_primer_informe']);
			$documento->setCellValueByColumnAndRow(6, $i, $fila['identificado_por']);
			$documento->setCellValueByColumnAndRow(7, $i, $fila['especimen']);
			$documento->setCellValueByColumnAndRow(8, $i, $fila['ubicacion_especimen']);
			$documento->setCellValueByColumnAndRow(9, $i, $fila['confirmacion_diagnostico']);
			$documento->setCellValueByColumnAndRow(10, $i, $fila['observacion']);
			$documento->setCellValueByColumnAndRow(11, $i, ($fila['fecha_ingreso']!=null?date('Y-m-d',strtotime($fila['fecha_ingreso'])):''));
			$documento->setCellValueByColumnAndRow(12, $i, $fila['numero_reporte']);
			$documento->setCellValueByColumnAndRow(13, $i, $fila['nombre_provincia']);
			$documento->setCellValueByColumnAndRow(14, $i, $fila['identificador_por_detalle']);
			$i++;
		}
		
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="reporteResultadoPlagas.xlsx"');
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$writer = IOFactory::createWriter($hoja, 'Xlsx');
		$writer->save('php://output');
		exit();
	}
}
