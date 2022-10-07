<?php
session_start();

require_once '../../../clases/ControladorServiciosGubernamentales.php';
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorRegistroOperador.php';
require_once '../../../clases/ControladorUsuarios.php';

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$identificadorConsulta = $_POST['numero'];
$tipoIdentificacion = $_POST['clasificacion'];
$tipoAcceso = false;
$conexion = new Conexion();

switch ($tipoIdentificacion){
    
    case 'Cédula':
        $tipoAcceso = true;
        $rutaWebervices = 'https://www.bsg.gob.ec/sw/RC/BSGSW03_Consultar_Ciudadano?wsdl';
        break;
        
    case 'Natural':
    case 'Juridica':
    case 'Publica':
        $tipoAcceso = true;
        $rutaWebervices = 'https://www.bsg.gob.ec/sw/SRI/BSGSW01_Consultar_RucSRI?wsdl';
        break;
}


try{
    $webServices = new ControladorServiciosGubernamentales();
    try {
        
        if($tipoAcceso){
            try {
                $resultadoAutenticacion = $webServices->consultarWebServicesAutenticacion($rutaWebervices);
            } catch (Exception $e) {
                echo $e;
            }
            $cabeceraSeguridad = $webServices->crearCabeceraSeguridadWebServices($resultadoAutenticacion);
            
            switch ($tipoIdentificacion){
                
                case 'Cédula':
                    $resultadoConsulta = $webServices->consultarWebServicesCedula($cabeceraSeguridad, $identificadorConsulta);
                    break;
                    
                case 'Natural':
                case 'Juridica':
                case 'Publica':
                    $resultadoConsulta = $webServices->consultarWebServicesRUC($cabeceraSeguridad, $identificadorConsulta, 'obtenerCompleto');
                    break;
            }
                        
            if($resultadoConsulta['CodigoError'] == '000'){
                $cr = new ControladorRegistroOperador();
                $cu = new ControladorUsuarios();
                $codigo = $cu->generarCodigoAcceso(8);
                $tipoPreg='';
                switch ($tipoIdentificacion){
                    
                    case 'Cédula':
                        $datos = formatearNombres($resultadoConsulta['Nombre']);
                        $arrayDatos = array(
                            'identificador' => $identificadorConsulta,
                            'razon' => $datos['apellidos'].' '.$datos['nombres'],
                            'nombres' => $datos['nombres'],
                            'apellidos' => $datos['apellidos'],
                            'codigo' => $codigo
                            );
                        $tipoPreg='cédula';
                        break;
                        
                    case 'Natural':
                    case 'Juridica':
                    case 'Publica':
                        $arrayDatos = array(
                            'identificador' => $identificadorConsulta,
                            'razon' => str_replace("'", "", $resultadoConsulta['razonSocial']) ,
                            'nombres' => '',
                            'apellidos' => '',
                            'codigo' => $codigo
                            );
                        $tipoPreg='RUC';
                        $resultadoConsulta['actividadGeneral'] =  $resultadoConsulta['actividadEconomica']['actividadGeneral'];
                        $resultadoConsulta['fechaInicioActividades'] = date("d/m/Y", strtotime($resultadoConsulta['fechaInicioActividades']));
                        break;
                }
                $consulta = $cr->guardarCrearOperador($conexion,$arrayDatos);
                $idCrearOperador = pg_fetch_result($consulta, 0, 'id_crear_operador');
                $preguntas = $cr->obtenerPreguntasCrearOperador($conexion, $tipoPreg);
                $preg = array();
                while($item = pg_fetch_assoc($preguntas))
                {
                    $preg [$item['id_preguntas_crear_operador']]= [$item['pregunta'],$item['descripcion'],$item['cod_pregunta'] ];  
                }
                
                $index = array_rand($preg, 2);
                $arrayDatos['pregunta1']=$preg[$index[0]][0];
                $arrayDatos['pregunta2']=$preg[$index[1]][0];
                $arrayDatos['idPregunta1']=$index[0];
                $arrayDatos['idPregunta2']=$index[1];
                $arrayDatos['descripPregunta1']=$preg[$index[0]][1];
                $arrayDatos['descripPregunta2']=$preg[$index[1]][1];
                $arrayDatos['id']=$idCrearOperador;
                foreach ($index as $item){
                    $arrayDetalle = array(
                        'idCrearOperador' => $idCrearOperador,
                        'idPreguntasCrearOperador' => $item,
                        'respuestaPregunta' => rtrim($resultadoConsulta[$preg[$item][2]])
                    );
                   $cr->guardarDetalleCrearOperador($conexion, $arrayDetalle);
                }
                $mensaje['mensaje'] = $resultadoConsulta['Error'];
                unset($arrayDatos['codigo']);
                $mensaje['valores'] = $arrayDatos;
                $mensaje['estado'] = 'exito';
                
            }else{
                $mensaje['estado'] = 'error';
                $mensaje['mensaje'] ='fallo';
                $mensaje['mensaje'] = $resultadoConsulta['Error'];
            }
        }
        
        echo json_encode($mensaje);
    } catch (Exception $ex){
        $mensaje['estado'] = 'error';
        $mensaje['mensaje'] = 'Error al ejecutar sentencia';
        echo json_encode($mensaje);
        $conexion->ejecutarLogsTryCatch($ex);
    }
} catch (Exception $ex) {
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Error de conexión a la base de datos';
    echo json_encode($mensaje);
    $conexion->ejecutarLogsTryCatch($ex);
}

//*****************formatear los nombres y apellidos********************//
function formatearNombres($nombre){
     $stringParts = explode(" ",$nombre);
     $num = count($stringParts);
    switch($num) {
        case 2:
            $nomb= $stringParts[1];
            $apell= $stringParts[0];
            break;
        case 3:
            $nomb= $stringParts[1].' '.$stringParts[2];
            $apell= $stringParts[0];
            break;
        case 4:
            $nomb= $stringParts[2].' '.$stringParts[3];
            $apell= $stringParts[0].' '.$stringParts[1];
            break;
        case 5:
            $nomb= $stringParts[2].' '.$stringParts[3].' '.$stringParts[4];
            $apell= $stringParts[0].' '.$stringParts[1];
            break;
        case 6:
            $nomb= $stringParts[4].' '.$stringParts[5];
            $apell= $stringParts[0].' '.$stringParts[1].' '.$stringParts[2].' '.$stringParts[3];
            break;
        default:
            $nomb= $stringParts[2].' '.$stringParts[3];
            $apell= $stringParts[0].' '.$stringParts[1];
    }
    
    return array(
        nombres => $nomb, 
        apellidos => $apell);
}
?>