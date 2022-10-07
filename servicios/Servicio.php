<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 4/6/2017
 * Time: 16:46
 */

include_once '../clases/Conexion.php';
include_once '../clases/ControladorAuditoria.php';
include_once 'Log.php';

abstract class Servicio
{
    protected $registros;
    protected $databaseVersion;
    protected $date;
    protected $tabletId;
    private $registrosProcesados = 0;
    private $registrosRecibidos = 0;
    private $estado = 'error';
    protected $conexion;
    protected $log;
    protected $mensaje;
    protected $provincia;
    private $codigo;

    abstract public function ejecutarServicio($registro);

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->log = new Log();
    }

    public function __destruct()
    {
        $this->conexion->desconectar();
    }

    public function down($post)
    {
        $this->procesarVariablePOST($post);
        $res = $this->ejecutarServicio(null);
        $jsonArreglo = json_decode($res['row_to_json'], true);
        $this->estado = 'exito';
        return $jsonArreglo['array_to_json'];
    }

    public function up($post)
    {
        $this->procesarVariablePOST($post);
        if ($this->registrosRecibidos > 0) {
            foreach ($this->registros as $registro) {
                try {
                    $this->conexion->ejecutarConsulta('BEGIN;');
                    $this->ejecutarServicio($registro);
                    $this->conexion->ejecutarConsulta('COMMIT;');
                    $this->log->entrada("Grabando registro " . ($this->registrosProcesados + 1) . " de $this->registrosRecibidos");
                    ++$this->registrosProcesados;
                } catch (Exception $ex) {
                    $this->conexion->ejecutarConsulta('ROLLBACK;');
                    $this->log->entrada("Error en registro " . ($this->registrosProcesados + 1) . " de $this->registrosRecibidos");
                    $this->log->info($this->conexion->mensajeError);
                    $this->mensaje = $ex->getMessage() . $this->conexion->mensajeError;
                    break;
                }
            }
            if ($this->areRegistrosCompletos()) {
                $this->estado = 'exito';
                $this->mensaje = 'Grabado completo';
                $this->codigo = 200;
            }
        } else {
            $this->mensaje = 'Nada que sincronizar';
            $this->codigo = 422;
        }
        return $this->mensaje;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function areRegistrosCompletos()
    {
        return $this->registrosProcesados == $this->registrosRecibidos;
    }

    public function getLog()
    {
        return $this->log->getLog();
    }

    /**
     * @param $post
     */
    private function procesarVariablePOST($post)
    {
        foreach ($post as $clave => $item) {
            switch ($clave) {
                case 'parametroBusqueda':
                    $this->registros = json_decode($item);
                    $this->registrosRecibidos = count($this->registros);
                    break;
                case 'provincia':
                    $this->provincia = $item;
                    $this->log->info('Provincia: ' . $this->provincia);
                    break;
                case 'databaseVersion':
                    $this->databaseVersion = htmlspecialchars($item, ENT_NOQUOTES, 'UTF-8');
                    break;
                case
                'date':
                    $this->date = json_decode($item);
                    break;
                case 'tabletId':
                    $this->tabletId = htmlspecialchars($item, ENT_NOQUOTES, 'UTF-8');
                    break;
            }
        }
    }


    private function construirAsignacion($campos)
    {
        $asignacion = '';
        foreach ($campos as $item => $campo) {
            if ($asignacion != '') {
                $asignacion .= ', ';
            }
            $asignacion .= "$item = $$$campo$$";
        }
        return $asignacion;
    }

    private function construirCondicion($campos)
    {
        $condicionesWhere = '';
        foreach ($campos as $item => $campo) {
            if ($condicionesWhere != '') {
                $condicionesWhere .= ' AND ';
            }
            $condicionesWhere .= "$item = $$$campo$$";
        }
        return $condicionesWhere;
    }

    protected function construirConsulta($tabla, $campos)
    {
        $campos = array_filter($campos, function ($valor) {
            return $valor != null;
        });

        $consulta = "
            INSERT INTO $tabla(
              " . implode(', ', array_keys($campos)) . ")
            SELECT
              $$" . implode("$$, $$", $campos) . "$$
            WHERE NOT EXISTS (
              SELECT
                " . implode(', ', array_keys($campos)) . "
              FROM
                $tabla
              WHERE
                " . $this->construirCondicion($campos) . "
            )
            RETURNING id;
        ";
		
        return $consulta;
    }

    protected function construirActualizacion($tabla, $campos, $condiciones)
    {
        $consulta = "
            UPDATE $tabla
            SET
                " . $this->construirAsignacion($campos) . "
            WHERE
                " . $this->construirCondicion($condiciones) . " RETURNING id;
        ";
        return $consulta;
    }
	
	protected function construirDiffDias($tabla, $condiciones)
    {
    	 $consulta = "
					SELECT 
						EXTRACT(DAY FROM (now() - fecha_inspeccion ) )+1 as dif_dias 
					FROM 
						$tabla WHERE " . $this->construirCondicion($condiciones) . " AND
					    fecha_inspeccion = (SELECT max(fecha_inspeccion) FROM $tabla WHERE " . $this->construirCondicion($condiciones) . ")
			        ";
    	return $consulta;
    }
}
