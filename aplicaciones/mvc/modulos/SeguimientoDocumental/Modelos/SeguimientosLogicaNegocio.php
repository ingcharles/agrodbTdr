<?php
/**
 * Lógica del negocio de SeguimientosModelo
 *
 * Este archivo se complementa con el archivo SeguimientosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-01-15
 * @uses    SeguimientosLogicaNegocio
 * @package SeguimientoDocumental
 * @subpackage Modelos
 */
namespace Agrodb\SeguimientoDocumental\Modelos;

use Agrodb\SeguimientoDocumental\Modelos\IModelo;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SeguimientosLogicaNegocio implements IModelo
{

    private $modeloSeguimientos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloSeguimientos = new SeguimientosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $datos['fecha_creacion'] = 'now()';
        $datos['identificador'] = $_SESSION['usuario'];
        $datos['estado_seguimiento'] = 'Creado';
        
        $tablaModelo = new SeguimientosModelo($datos);

        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdSeguimiento() != null && $tablaModelo->getIdSeguimiento() > 0) {
            return $this->modeloSeguimientos->actualizar($datosBd, $tablaModelo->getIdSeguimiento());
        } else {
            unset($datosBd["id_seguimiento"]);
            return $this->modeloSeguimientos->guardar($datosBd);
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
        $this->modeloSeguimientos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return SeguimientosModelo
     */
    public function buscar($id)
    {
        return $this->modeloSeguimientos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloSeguimientos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloSeguimientos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarSeguimientos()
    {
        $consulta = "SELECT * FROM " . $this->modeloSeguimientos->getEsquema() . ". seguimientos";
        return $this->modeloSeguimientos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar Información de seguimientos creados por trámite con información completa.
     *
     * @return array|ResultSet
     */
    public function buscarSeguimientosXTramite($idTramite)
    {
        $consulta = "SELECT 
                    	s.id_seguimiento, s.id_tramite, s.fecha_creacion, 
                    	s.id_ventanilla, s.identificador, 
                    	s.fecha, s.persona_recibe, 
                        s.id_unidad_destino, a.nombre as unidad_destino, 
                    	s.observaciones_seguimiento, s.estado_seguimiento
                    FROM 
                    	g_seguimiento_documental.seguimientos s
                    	INNER JOIN g_estructura.area a ON s.id_unidad_destino = a.id_area
                    WHERE
                    	s.id_tramite = '". $idTramite ."';";
        
        $ventanillas = $this->modeloSeguimientos->ejecutarSqlNativo($consulta);
        
        return $ventanillas;
    }
    
    public function exportarArchivoExcel($datos) {
    	
    	$hoja = new Spreadsheet();
    	$documento = $hoja->getActiveSheet();
    	$i = 3;
    	$j = 1;
    	
    	$documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Seguimientos ');
    	
    	$documento->setCellValueByColumnAndRow(1, 2, 'Número');
    	$documento->setCellValueByColumnAndRow(2, 2, 'Fecha');
    	$documento->setCellValueByColumnAndRow(3, 2, 'Recibido por');
    	$documento->setCellValueByColumnAndRow(4, 2, 'Dirección de Destino');
    	$documento->setCellValueByColumnAndRow(5, 2, 'Observaciones');
    	
    	foreach ($datos as $fila){
    		$documento->setCellValueByColumnAndRow(1, $i, $j);
    		$documento->setCellValueByColumnAndRow(2, $i, $fila['fecha']);
    		$documento->setCellValueByColumnAndRow(3, $i, $fila['persona_recibe']);
    		$documento->setCellValueByColumnAndRow(4, $i, $fila['unidad_destino']);
    		$documento->setCellValueByColumnAndRow(5, $i, $fila['observaciones_seguimiento']);
    		$i++;
    		$j++;
    	}
    	
    	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    	header('Content-Disposition: attachment;filename="excelSeguimientos.xlsx"');
    	header("Pragma: no-cache");
    	header("Expires: 0");
    	
    	$writer = IOFactory::createWriter($hoja, 'Xlsx');
    	$writer->save('php://output');
    	exit();
    	
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar Información de seguimientos creados por trámite con información completa.
     *
     * @return array|ResultSet
     */
    public function buscarUltimoSeguimientoXTramite($idTramite)
    {
    	$consulta = "	SELECT 
							s.*, a.nombre as unidad_destino 
						FROM
							g_seguimiento_documental.seguimientos s
						INNER JOIN
							g_estructura.area a ON a.id_area = s.id_unidad_destino
						WHERE
							s.id_tramite = ". $idTramite ."
						ORDER BY
							s.fecha_creacion DESC
						LIMIT 1;";
    	
    	$seguimiento = $this->modeloSeguimientos->ejecutarSqlNativo($consulta);
    	
    	return $seguimiento;
    }
}
