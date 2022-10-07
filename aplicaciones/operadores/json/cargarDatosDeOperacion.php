<?php
    session_start();
    require_once '../../../clases/Conexion.php';
    require_once '../../../clases/ControladorRegistroOperador.php';
    //require_once '../../../clases/ControladorCRC.php';
	set_time_limit (180);

    $mensaje            = array();
    $mensaje['estado']  = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';


    try {

        $tipoResultado = htmlspecialchars($_POST['tipoResultado'], ENT_NOQUOTES, 'UTF-8');
        $identificador = htmlspecialchars($_POST['identificador'], ENT_NOQUOTES, 'UTF-8');
        $tipoOperacion = htmlspecialchars($_POST['tipoOperacion'], ENT_NOQUOTES, 'UTF-8');
        $destino = htmlspecialchars($_POST['destino'], ENT_NOQUOTES, 'UTF-8');
        $area = htmlspecialchars($_POST['area'], ENT_NOQUOTES, 'UTF-8');
        
        //print_r($_POST);

        try {
            //$crc = new TransaccionCRC();
            //if ($crc->validarCRC(true))
            {
                $conexion = new Conexion();
                $cro      = new ControladorRegistroOperador();

                //**********************TRAER LOS DATOS
                $datosPorOeracionJson = $cro->obtenerDatosPorOperacion($conexion, $identificador, $tipoOperacion, $area);
                $datosOperacion = (array)(json_decode($datosPorOeracionJson['array_to_json']));

                //print_r($datosOperacion);

                $mensaje['estado']  = 'exito';
                //$mensaje['mensaje'] = $crc->getMensaje();
                $mensaje['destino'] = "#$destino";
                switch ($tipoResultado) {
                    case 'json': $mensaje['resultado'] = $datosOperacion; break;
                    case 'html': $mensaje['resultado'] = html($datosOperacion, $identificador); break;
                }


                $conexion->desconectar();
            } /*else {
                $mensaje['estado']  = 'error';
                $mensaje['mensaje'] = $crc->getMensaje();
            }*/
            echo json_encode($mensaje);
        } catch (Exception $ex) {
            pg_close($conexion);
            $mensaje['estado']  = 'error';
            $mensaje['mensaje'] = "Error al ejecutar sentencia";
            echo json_encode($mensaje);
        }
    } catch (Exception $ex) {
        $mensaje['estado']  = 'error';
        $mensaje['mensaje'] = 'Error de conexión a la base de datos';
        echo json_encode($mensaje);
    }

    function html($arreglo, $identificador){
        $html = '';
        foreach($arreglo as $item){
            $area = $item;
			
			if(!is_null($area->codigo_transaccional) && $area->codigo_transaccional != ''){
                $codigoMag='<div data-linea="2">'.
                '<label>Código MAG: </label>'.$area->codigo_transaccional.
                '</div>';
            }
			
            $html .=
                '<fieldset>'.
                    '<legend>'.$area->tipo_area.'</legend>'.
                    '<div data-linea="a" class="destacar">'.
                        '<label>Código de área: </label> '.
                        $identificador . '.' . $area->codigo_provincia . $area->codigo_sitio . $area->codigo_area . $area->secuencial .
                    '</div>'.
                    '<!--div data-linea="0">'.
                        '<label>Estado: </label>'.$area->estado.
                    '</div-->'.
                    '<div data-linea="0">'.
                        '<label>ID del sistema: </label>'.$area->id_area.
                    '</div>'.
                    '<div data-linea="1">'.
                        '<label>Nombre del área: </label>'.$area->nombre_area.
                    '</div>'.
					$codigoMag.
                    '<hr/>'.
                    '<div data-linea="5">'.
                        '<label>Nombre del sitio: </label>'. $area->nombre_lugar.
                    '</div>'.
                    '<div data-linea="7">'.
                        '<label>Dirección: </label>'.$area->direccion.
                    '</div>'.
                    '<div data-linea="6">'.
                        '<i>'.$area->parroquia.' ('.$area->canton.' - '.$area->provincia.')</i>'.
                    '</div>'.
                    '<div data-linea="8">'.
                        '<label>Referencias: </label>'.$area->referencia.
                    '</div>'.
                    '<div data-linea="9">'.
                        '<label>Teléfono: </label>'.$area->telefono.
                    '</div>'.
                    '<hr/>'.
                    '<div data-linea="10" class="longitud">'.
                        '<label>Longitud: </label><br/><span>'.$area->longitud.'</span>'.
                    '</div>'.
                    '<div data-linea="10" class="latitud">'.
                        '<label>Latitud: </label><br/><span>'.$area->latitud.'</span>'.
                    '</div>'.
                    '<div data-linea="10" class="zona">'.
                        '<label>Zona: </label><br/><span>'.$area->zona.'</span>'.
                    '</div>'.
                    '<div data-linea="11">';
            if ($area->croquis != 0)
                $html .= '<a href="'.$area->croquis.'" target="_blank" >Ver croquis en ventana externa</a>';
            else
                $html .= 'No se ha cargado croquis';
            $html .=        '</div>'.
                    '<div data-linea="12" class="mapa">'.
                        '<button type="button" class="mostrar">Mostrar/Ocultar mapa</button>'.
                        '<div class="mapa" data-estado="Por cargar mapa" style="display:none;"></div>'.
                    '</div>'.
                    '<hr/>'.
                    '<div data-linea="15">'.
                        '<label>Superficie declarada:</label>'.$area->superficie_utilizada.' '.$area->unidad_medida.'</sup>'.
                    '</div>'.
                    '<hr/>'.
                    '<table class="areas">
                        <tr>
                            <th>#</th>
                            <th><u>Producto</u><br/>Partida</th>
                            <th><u>Tipo</u><br/>Subtipo</th>
                            <th>País</th>
                            <th>Estado</th>
                            <th># Solicitud<br/>Creación</th>

                        </tr>';
            $productos = $area->productos;
            $contadorDeProductos = 0;
            if (!empty($productos)) {
                foreach ($productos as $producto) {
                    $html .= '<tr><td>' . (++$contadorDeProductos) . '</td>'.
                        '<td><u>' . (trim($producto->nombre_comun) !=''? $producto->nombre_comun:'N/A'). ' (' . (trim($producto->nombre_cientifico) !=''? $producto->nombre_cientifico:'N/A'). ')</u><br/>' .
                            (trim($producto->partida_arancelaria) !=''? $producto->partida_arancelaria:'N/A'). '</td>' .
                                '<td><u>' . (trim($producto->tipo) !=''? $producto->tipo:'N/A'). '</u><br/>' . (trim($producto->subtipo) !=''? $producto->subtipo:'N/A'). '</td>' .
                        '<td>'.(trim($producto->nombre_pais)!=''?$producto->nombre_pais:'N/A').'</td>' .
                        '<td>'.
                        '<!--span class="__area_' . ($producto->estado_area==''?'espera':$producto->estado_area) . '">A</span-->' .
                        '<span class="__operacion_'.$producto->estado_operacion.'" title="'.$producto->estado_operacion.'"></span>' .
                        '</td>' .
                        '<td>' . $producto->id_operacion . '<br/>' . $producto->fecha_creacion . '</td></tr>';

                }
            }
            $html .= '</table>'.
                '</fieldset>';

        }
        $html .= '';

        return $html;
    }
?>