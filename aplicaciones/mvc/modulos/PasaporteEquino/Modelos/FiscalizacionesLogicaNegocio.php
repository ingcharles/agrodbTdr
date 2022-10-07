<?php
/**
 * Lógica del negocio de FiscalizacionesModelo
 *
 * Este archivo se complementa con el archivo FiscalizacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-03-22
 * @uses    FiscalizacionesLogicaNegocio
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\PasaporteEquino\Modelos\IModelo;

use Agrodb\PasaporteEquino\Modelos\EquinosLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\EquinosModelo;

use Agrodb\PasaporteEquino\Modelos\MovilizacionesLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\MovilizacionesModelo;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class FiscalizacionesLogicaNegocio implements IModelo
{

    private $modeloFiscalizaciones = null;

    private $lNegocioEquinos = null;
    private $modeloEquinos = null;
    
    private $lNegocioMovilizaciones = null;
    private $modeloMovilizaciones = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloFiscalizaciones = new FiscalizacionesModelo();

        $this->lNegocioEquinos = new EquinosLogicaNegocio();
        $this->modeloEquinos = new EquinosModelo();
        
        $this->lNegocioMovilizaciones = new MovilizacionesLogicaNegocio();
        $this->modeloMovilizaciones = new MovilizacionesModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new FiscalizacionesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdFiscalizacion() != null && $tablaModelo->getIdFiscalizacion() > 0) {
            return $this->modeloFiscalizaciones->actualizar($datosBd, $tablaModelo->getIdFiscalizacion());
        } else {
            unset($datosBd["id_fiscalizacion"]);
            return $this->modeloFiscalizaciones->guardar($datosBd);
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
        $this->modeloFiscalizaciones->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return FiscalizacionesModelo
     */
    public function buscar($id)
    {
        return $this->modeloFiscalizaciones->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloFiscalizaciones->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloFiscalizaciones->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarFiscalizaciones()
    {
        $consulta = "SELECT * FROM " . $this->modeloFiscalizaciones->getEsquema() . ". fiscalizaciones";
        return $this->modeloFiscalizaciones->ejecutarSqlNativo($consulta);
    }

    /**
     * Funcionamiento para guardado de datos de fiscalizacion de acuerdo al resultado, e inactivación de pasaportes
     *
     * @param array $datos
     * @return int
     */
    public function guardarFiscalizacion(Array $datos)
    {
        $validacion = array(
            'bandera' => false,
            'estado' => "Fallo",
            'mensaje' => "Ocurrió un error al guardar la información de la fiscalización",
            'contenido' => null
        );
        
        //Buscar nuevamente el estado de la movilizacion, verificar si esta vigente y guardar, caso contrario se rechaza
        $movilizacion = $this->lNegocioMovilizaciones->buscar($datos['id_movilizacion']);
        
        if($movilizacion->getEstadoMovilizacion() == 'Vigente'){
        
            // Guarda la fiscalización
            $idFiscalizacion = $this->guardar($datos);
            
            if ($idFiscalizacion > 0) {
                $validacion['bandera'] = true;
                $validacion['estado'] = 'exito';
                $validacion['mensaje'] = 'Se ha guardado el registro de fiscalización. ';
                
                //Actualizar datos de estado de la movilización
                // crear array parametros
                $arrayParametros = array(
                    'id_movilizacion' => $datos['id_movilizacion'],
                    'estado_fiscalizacion' => 'Fiscalizado',
                    'estado_movilizacion' => $datos['estado_fiscalizacion']
                );
                
                $idMovilizacion = $this->lNegocioMovilizaciones->guardar($arrayParametros);
                
                if($idMovilizacion > 0){
                    // Confirma el resultado para inactivar el pasaporte equino
                    // Resultado: Negativo, Acción correctiva: Inactivar registro de movilización. Poner estado como Finalizado
                    if ($datos['resultado_fiscalizacion'] == 'Negativo' && $datos['accion_correctiva'] == 'Inactivar registro de movilización') {
                        
                        // crear array parametros
                        $arrayParametros = array(
                            'id_equino' => $datos['id_equino'],
                            'estado_equino' => 'Inactivo',
                            'motivo_cambio' => 'Inactivo por fiscalización negativa '.$idFiscalizacion.' en movilización ' . $datos['id_movilizacion'],
                            'fecha_modificacion' => 'now()'
                        );
                        
                        // Actualizar estado del equino
                        $idEquino = $this->lNegocioEquinos->guardar($arrayParametros);
                        
                        if ($idEquino > 0) {
                            $validacion['bandera'] = true;
                            $validacion['estado'] = 'exito';
                            $validacion['mensaje'] .= ' Se ha inactivado al equino.';
                        }else{
                            $validacion['bandera'] = false;
                            $validacion['estado'] = 'Fallo';
                            $validacion['mensaje'] .= ' Ha ocurrido un error al inactivar al equino.';
                        }       
                        
                    }else if ($datos['estado_fiscalizacion'] == 'Finalizado') { 
                        //Cuando finalice la fiscalización el equino se libera
                        
                        // crear array parametros
                        $arrayParametros = array(
                            'id_equino' => $datos['id_equino'],
                            'estado_equino' => 'Activo',
                            'motivo_cambio' => 'Activo por fiscalización finalizada '.$idFiscalizacion.' en movilización ' . $datos['id_movilizacion'],
                            'fecha_modificacion' => 'now()'
                        );
                        
                        // Actualizar estado del equino
                        $idEquino = $this->lNegocioEquinos->guardar($arrayParametros);
                        
                        if ($idEquino > 0) {
                            $validacion['bandera'] = true;
                            $validacion['estado'] = 'exito';
                            $validacion['mensaje'] .= ' Se ha activado al equino.';
                        }else{
                            $validacion['bandera'] = false;
                            $validacion['estado'] = 'Fallo';
                            $validacion['mensaje'] .= ' Ha ocurrido un error al activar al equino.';
                        }
                    }
                } else {
                    $validacion['bandera'] = false;
                    $validacion['estado'] = 'Fallo';
                    $validacion['mensaje'] .= ' No se ha podido actualizar la información de la movilización.';
                }
            } else {
                $validacion['bandera'] = false;
                $validacion['estado'] = 'Fallo';
                $validacion['mensaje'] .= ' No se ha podido crear el registro de fiscalización.';
            }
        }else{
            $validacion['bandera'] = false;
            $validacion['estado'] = 'Fallo';
            $validacion['mensaje'] .= ' La movilización ha expirado, no puede crear un registro de fiscalización.';
        }
        
        return $validacion;
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar movilizaciones para reporte usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarFiscalizacionesReporteFiltradas($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['fecha_inicio']) && ($arrayParametros['fecha_inicio'] != '') ) {
            
            $busqueda .= " m.fecha_inicio_movilizacion >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00'";
        }
        
        if (isset($arrayParametros['fecha_fin']) && ($arrayParametros['fecha_fin'] != '') ) {
            
            $busqueda .= "and m.fecha_fin_movilizacion <= '" . $arrayParametros['fecha_fin'] . " 24:00:00'";
        }
        
        if (isset($arrayParametros['id_provincia']) && ($arrayParametros['id_provincia'] != '') && ($arrayParametros['id_provincia'] != 'Todas')) {
            $busqueda .= " and m.id_provincia_origen = " . $arrayParametros['id_provincia'];
        }
        
        if (isset($arrayParametros['id_canton']) && ($arrayParametros['id_canton'] != '') && ($arrayParametros['id_canton'] != 'Todos')) {
            $busqueda .= " and m.id_canton_origen = " . $arrayParametros['id_canton'];
        }
        
        if (isset($arrayParametros['estado_fiscalizacion']) && ($arrayParametros['estado_fiscalizacion'] != '') && ($arrayParametros['estado_fiscalizacion'] != 'Todos')) {
            
            $busqueda .= " and f.resultado_fiscalizacion in ('" . $arrayParametros['estado_fiscalizacion'] . "')";
        }
        
        $consulta = "  SELECT
                        	m.*,
                            f.*
                        FROM
                        	g_pasaporte_equino.movilizaciones m
                            INNER JOIN g_pasaporte_equino.fiscalizaciones f ON m.id_movilizacion = f.id_movilizacion
                        WHERE
            
                            " . $busqueda . "
                        ORDER BY
                            m.id_movilizacion, f.id_movilizacion, f.id_fiscalizacion ASC;";
        
        //echo $consulta;
        return $this->modeloFiscalizaciones->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta un reporte en Excel de los pasaportes
     *
     * @return array|ResultSet
     */
    public function exportarArchivoExcelFiscalizaciones($datos){
        
        $hoja = new Spreadsheet();
        $documento = $hoja->getActiveSheet();
        $i = 3;
        $j = 2;
        
        $documento->setCellValueByColumnAndRow(1, 1, 'Reporte de Fiscalizaciones Equinas');
        
        $documento->setCellValueByColumnAndRow(1, $j, 'ID');
        $documento->setCellValueByColumnAndRow(2, $j, 'Número movilización');
        
        $documento->setCellValueByColumnAndRow(3, $j, 'Provincia origen');
        $documento->setCellValueByColumnAndRow(4, $j, 'Cantón origen');
        $documento->setCellValueByColumnAndRow(5, $j, 'Parroquia origen');
        $documento->setCellValueByColumnAndRow(6, $j, 'Sitio origen');
        $documento->setCellValueByColumnAndRow(7, $j, 'Identificador operador origen');
        $documento->setCellValueByColumnAndRow(8, $j, 'Nombre operador origen');
        
        $documento->setCellValueByColumnAndRow(9, $j, 'Provincia destino');
        $documento->setCellValueByColumnAndRow(10, $j, 'Cantón destino');
        $documento->setCellValueByColumnAndRow(11, $j, 'Parroquia destino');
        $documento->setCellValueByColumnAndRow(12, $j, 'Sitio destino');
        $documento->setCellValueByColumnAndRow(13, $j, 'Identificador operador destino');
        $documento->setCellValueByColumnAndRow(14, $j, 'Nombre operador destino');
        
        $documento->setCellValueByColumnAndRow(15, $j, 'Tipo fiscalizador');
        $documento->setCellValueByColumnAndRow(16, $j, 'Identificador fiscalizador');
        $documento->setCellValueByColumnAndRow(17, $j, 'Nombre fiscalizador');
        $documento->setCellValueByColumnAndRow(18, $j, 'Provincia fiscalizador');
        
        $documento->setCellValueByColumnAndRow(19, $j, 'Lugar de fiscalización');
        $documento->setCellValueByColumnAndRow(20, $j, 'Resultado de fiscalización');
        $documento->setCellValueByColumnAndRow(21, $j, 'Acción correctiva');
        
        $documento->setCellValueByColumnAndRow(22, $j, 'Motivo');
        $documento->setCellValueByColumnAndRow(23, $j, 'Observación');
        
        $documento->setCellValueByColumnAndRow(24, $j, 'Fecha registro');
        $documento->setCellValueByColumnAndRow(25, $j, 'Fecha fiscalización');
        
        $documento->setCellValueByColumnAndRow(26, $j, 'Estado movilización');
        
        if($datos != ''){
            foreach ($datos as $fila){
                $documento->setCellValueByColumnAndRow(1, $i, $fila['id_movilizacion']);
                $documento->setCellValueByColumnAndRow(2, $i, $fila['numero_movilizacion']);
                
                $documento->setCellValueByColumnAndRow(3, $i, $fila['provincia_origen']);
                $documento->setCellValueByColumnAndRow(4, $i, $fila['canton_origen']);
                $documento->setCellValueByColumnAndRow(5, $i, $fila['parroquia_origen']);
                $documento->setCellValueByColumnAndRow(6, $i, $fila['nombre_ubicacion_origen']);
                $documento->setCellValueByColumnAndRow(7, $i, $fila['identificador_propietario_origen']);
                $documento->setCellValueByColumnAndRow(8, $i, $fila['nombre_propietario_origen']);
                
                $documento->setCellValueByColumnAndRow(9, $i, $fila['provincia_destino']);
                $documento->setCellValueByColumnAndRow(10, $i, $fila['canton_destino']);
                $documento->setCellValueByColumnAndRow(11, $i, $fila['parroquia_destino']);
                $documento->setCellValueByColumnAndRow(12, $i, $fila['nombre_ubicacion_destino']);
                $documento->setCellValueByColumnAndRow(13, $i, $fila['identificador_propietario_destino']);
                $documento->setCellValueByColumnAndRow(14, $i, $fila['nombre_propietario_destino']);
                
                $documento->setCellValueByColumnAndRow(15, $i, $fila['tipo_fiscalizador']);
                $documento->setCellValueByColumnAndRow(16, $i, $fila['identificador_fiscalizador']);
                $documento->setCellValueByColumnAndRow(17, $i, $fila['nombre_fiscalizador']);
                $documento->setCellValueByColumnAndRow(18, $i, $fila['provincia_fiscalizador']);
                
                $documento->setCellValueByColumnAndRow(19, $i, $fila['lugar_fiscalizacion']);
                $documento->setCellValueByColumnAndRow(20, $i, $fila['resultado_fiscalizacion']);
                $documento->setCellValueByColumnAndRow(21, $i, $fila['accion_correctiva']);
                
                $documento->setCellValueByColumnAndRow(22, $i, $fila['motivo']);
                $documento->setCellValueByColumnAndRow(23, $i, $fila['observacion_fiscalizacion']);
                
                $documento->setCellValueByColumnAndRow(24, $i, $fila['fecha_creacion']);
                $documento->setCellValueByColumnAndRow(25, $i, date('Y-m-d',strtotime($fila['fecha_fiscalizacion'])));
                $documento->setCellValueByColumnAndRow(26, $i, $fila['estado_movilizacion']);
                
                $i++;
            }
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="excelFiscalizaciones.xlsx"');
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $writer = IOFactory::createWriter($hoja, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
}