<?php

/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 9/6/2017
 * Time: 22:33
 */
class CertificacionF04 extends Servicio
{
    private $tabla = 'f_inspeccion.certificacionf04';
    private $tablaDetalleProductos = 'f_inspeccion.certificacionf04_detalle_productos';


    public function ejecutarServicio($registro)
    {
        $campos = array(
            'id_tablet' => $registro->id,
            'numero_reporte' => $registro->numeroReporte,
            'ruc_operador' => $registro->rucOperador,
            'nombre_operador' => $registro->nombreOperador,
            'id_sitio_acopiador' => $registro->idSitioAcopiador,
            'sitio_acopiador' => $registro->sitioAcopiador,
            'provincia' => $registro->provincia,
            'canton' => $registro->canton,
            'parroquia' => $registro->parroquia,
            'pregunta1' => $registro->pregunta01,
            'pregunta2' => $registro->pregunta02,
            'pregunta3' => $registro->pregunta03,
            'pregunta4' => $registro->pregunta04,
            'ingrediente_activo' => $registro->ingredienteActivo,
            'marca_comercial' => $registro->marcaComercial,
            'formulacion' => $registro->formulacion,
            'registro_agrocalidad' => $registro->registroAgrocalidad,
            'fecha_caducidad' => $registro->fechaCaducidad,
            'fecha_preparacion' => $registro->fechaPreparacion,
            'fecha_validez' => $registro->fechaValidez,
            'pregunta5' => $registro->pregunta05,
            'pregunta6' => $registro->pregunta06,
            'pregunta7' => $registro->pregunta07,
            'pregunta8' => $registro->pregunta08,
            'pregunta9' => $registro->pregunta09,
            'pregunta10' => $registro->pregunta10,
            'pregunta11' => $registro->pregunta11,
            'pregunta12' => $registro->pregunta12,
            'pregunta13' => $registro->pregunta13,
            'pregunta14' => $registro->pregunta14,
            'pregunta15' => $registro->pregunta15,
            'pregunta16' => $registro->pregunta16,
            'observaciones' => $registro->observaciones,
            'dictamen_final' => $registro->dictamenFinal,
            'representante_operador' => $registro->representanteOperador,
            'usuario' => $registro->usuario,
            'usuario_id' => $registro->usuarioId,
            'fecha_inspeccion' => $registro->fechaCreacion,
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
        if ($id != null || $id != '') {
            $this->ejecutarServicioDetalleProductos($id[0], $registro->certificacionF04ProductoList);
        } /*else {
            throw new Exception('Error: ¡Existen datos duplicados!');
        }*/
    }

    private function ejecutarServicioDetalleProductos($id, $productos)
    {
        foreach ($productos as $producto) {
            $campos = array(
                'id_padre' => $id,
                'id_tablet' => $producto->id,
                'producto' => $producto->producto,
                'cantidad_tallos' => $producto->cantidadTallos,
                'cantidad_inspeccionada' => $producto->cantidadInspeccionada,
                'concentracion' => $producto->concentracion,
                'dosificacion' => $producto->dosificacion,
                'volumen_solucion' => $producto->volumenSolucion,
                'numero_recipientes' => $producto->numeroRecipientes,
                'volumen_total' => $producto->volumenTotal,
            );
            $this->conexion->ejecutarConsulta(
                $this->construirConsulta(
                    $this->tablaDetalleProductos,
                    $campos
                )
            );
        }
    }
}
