<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class MoscaF01 extends Servicio
{
    private $tabla = 'f_inspeccion.moscaf01';
    private $tablaDetalleTrampas = 'f_inspeccion.moscaf01_detalle_trampas';
    private $tablaDetalleOrdenes = 'f_inspeccion.moscaf01_detalle_ordenes';

    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'fecha_inspeccion' => $registro->fechaCreacion,
            'usuario_id' => $registro->usuarioId,
            'usuario' => $registro->usuario,
            'tablet_id' => $this->tabletId,
            'tablet_version_base' => $this->databaseVersion,
        );
        $id = pg_fetch_row(
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tabla,
                    $campos
                )
            )
        );
        if($id != null || $id != ''){
            $this->ejecutarServicioDetalleTrampas($id[0], $registro->moscaF01TrampaList);
            $this->ejecutarServicioDetalleOrdenesLaboratorio($id[0], $registro->moscaF01OrdenList);
        } else {
            throw new Exception('Error: ¡Existen datos duplicados!');
        }
    }

    public function ejecutarServicioDetalleTrampas($id,$trampas)
    {
        foreach ($trampas as $trampa) {
        	
        	$condiciones = array(
        		'codigo_trampa' => $trampa->codigoTrampa,
        	);
        	$exposicion = pg_fetch_row(
        		$this->conexion->ejecutarConsulta(
        			$this->construirDiffDias(
        				$this->tablaDetalleTrampas,
        				$condiciones
        				)
        			)
        		);
        	if($exposicion[0] != null || $exposicion[0] != ''){
        	} else {
        		$exposicion[0] = 0;
        	}
        	
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $trampa->id,
                'id_provincia' => $trampa->idProvincia,
                'nombre_provincia' => $trampa->nombreProvincia,
                'id_canton' => $trampa->idCanton,
                'nombre_canton' => $trampa->nombreCanton,
                'id_parroquia' => $trampa->idParroquia,
                'nombre_parroquia' => $trampa->nombreParroquia,
                'id_lugar_instalacion' => $trampa->idLugarInstalacion,
                'nombre_lugar_instalacion' => $trampa->nombreLugarInstalacion,
                'numero_lugar_instalacion' => $trampa->numeroLugarInstalacion,
                'id_tipo_atrayente' => $trampa->idTipoAtrayente,
                'nombre_tipo_atrayente' => $trampa->nombreTipoAtrayente,
                'tipo_trampa' => $trampa->tipoTrampa,
                'codigo_trampa' => $trampa->codigoTrampa,
                'coordenada_x' => $trampa->coordenadaX,
                'coordenada_y' => $trampa->coordenadaY,
                'coordenada_z' => $trampa->coordenadaZ,
                'fecha_instalacion' => $trampa->fechaInstalacion,
                'estado_trampa' => $trampa->estadoTrampa,
            	'exposicion' => $exposicion[0],
                'condicion' => $trampa->condicion,
                'cambio_trampa' => $trampa->cambioTrampa,
                'cambio_plug' => $trampa->cambioPlug,
                'especie_principal' => $trampa->especiePrincipal,
                'estado_fenologico_principal' => $trampa->estadoFenologicoPrincipal,
                'especie_colindante' => $trampa->especieColindante,
                'estado_fenologico_colindante' => $trampa->estadoFenologicoColindante,
                'numero_especimenes' => $trampa->numeroEspecimenes,
                'observaciones' => $trampa->observaciones,
                'envio_muestra' => $trampa->envioMuestra,
                'estado_registro' => $trampa->estadoRegistro,
                'fecha_inspeccion' => $trampa->fechaCreacion,
                'semana' => $trampa->semana,
                'usuario_id' => $trampa->usuarioId,
                'usuario' => $trampa->usuario,
                'tablet_id' => $this->tabletId,
                'tablet_version_base' => $this->databaseVersion,
            );
            
            $id2 = pg_fetch_row(
                $this->conexion->ejecutarConsulta(
                    $this->construirConsulta(
                        $this->tablaDetalleTrampas,
                        $campos
                    )
                )
            );

            if($id2 != null || $id2 != ''){
            } else {
                throw new Exception('Error: ¡Existen datos duplicados!');
            }
        }
    }

    private function ejecutarServicioDetalleOrdenesLaboratorio($id, $ordenes)
    {
        foreach ($ordenes as $orden) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $orden->id,
                'analisis' => $orden->analisis,
                'codigo_muestra' => $orden->codigoMuestra,
                'tipo_muestra' => $orden->tipoMuestra,
            );
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tablaDetalleOrdenes,
                    $campos
                )
            );
        }
    }
}