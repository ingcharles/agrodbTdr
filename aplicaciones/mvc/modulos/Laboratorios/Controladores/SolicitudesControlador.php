<?php

/**
 * Controlador Solicitudes
 *
 * Este archivo controla la lógica del negocio del modelo:  SolicitudesModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     SolicitudesControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Core\Mensajes;
use Agrodb\Core\Constantes;
use Agrodb\Laboratorios\Modelos\SolicitudesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\SolicitudesModelo;
use Agrodb\Laboratorios\Modelos\DetalleSolicitudesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\MuestrasLogicaNegocio;
use Agrodb\Laboratorios\Modelos\LaboratoriosModelo;
use Agrodb\Laboratorios\Modelos\LaboratoriosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\MuestrasModelo;
use Agrodb\Laboratorios\Modelos\ServiciosModelo;
use Agrodb\Laboratorios\Modelos\ServiciosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\DetalleSolicitudesModelo;
use Agrodb\Financiero\Modelos\ClientesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\PersonasLogicaNegocio;
use Agrodb\Laboratorios\Modelos\UsuarioLaboratorioLogicaNegocio;
use Agrodb\Laboratorios\Modelos\TiemposRespuestaLogicaNegocio;
use Agrodb\Laboratorios\Modelos\DistribucionMuestrasLogicaNegocio;

class SolicitudesControlador extends FormularioDinamico
{

    private $lNegocioSolicitudes = null;
    private $lNegocioMuestras = null;
    private $modeloSolicitudes = null;
    private $lNegocioDetallesolicitudes = null;
    private $modeloDetallesolicitudes = null;
    private $lNegocioLaboratorios = null;
    private $lNegocioServicios = null;
    private $modeloLaboratorios = null;
    private $modeloMuestras = null;
    private $accion = null;
    private $rutArcExo = null; //ruta del archivo de exoneracion
    public $detalleSolicitudesGuardado;
    public $datosSolicitud = null;  //datos de la solicitud
    public $tablaInformesSolicitud = null; //para la tabla con los informes
    private $estados;       //para el filtro de estado
    private $datosDireccion = null;
    private $datosLaboratorio = null;
    private $datos = array();

    /*
     * Constructor
     */

    function __construct()
    {
        parent::__construct();
        $this->rutArcExo = URL_DIR_FILES . '/exoneracion'; //ruta del archivo de exoneracion
        //estados de la solicitud
        $this->estados = array(
            Constantes::estado_SO()->REGISTRADA,
            Constantes::estado_SO()->ENVIADA,
            Constantes::estado_SO()->RECIBIDA,
            Constantes::estado_SO()->EN_PROCESO,
            Constantes::estado_SO()->FINALIZADA,
        );
        $this->lNegocioSolicitudes = new SolicitudesLogicaNegocio();
        $this->modeloSolicitudes = new SolicitudesModelo();
        $this->lNegocioDetallesolicitudes = new DetalleSolicitudesLogicaNegocio();
        $this->lNegocioMuestras = new MuestrasLogicaNegocio();
        $this->lNegocioLaboratorios = new LaboratoriosLogicaNegocio();
        $this->lNegocioServicios = new ServiciosLogicaNegocio();
        // Ingresamos el código del usuario
        $this->modeloLaboratorios = new LaboratoriosModelo();
        $this->modeloMuestras = new MuestrasModelo();
        $this->modeloDetallesolicitudes = new DetalleSolicitudesModelo();

        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     * Buscar las solicitudes que puede visualizar segun tabla usuarios_solicitud
     */
    public function index()
    {
        require APP . 'Laboratorios/vistas/listaSolicitudesVista.php';
    }

    /**
     * Búsqueda por filtro
     */
    public function listarDatos()
    {
        if (!empty($_POST['estado']))
        {
            $estado = $_POST['estado'];
        } else
        {
            $estado = $this->estados;
        }
        $arrayParametros = array(
            'estado' => $estado,
            'codigo' => $_POST['codigo'],
            'identificador' => $this->identificador);
        $modeloSolicitudes = $this->lNegocioSolicitudes->buscarSolicitudes($arrayParametros);
        $this->tablaHtmlSolicitudes($modeloSolicitudes);
        echo \Zend\Json\Json::encode($this->itemsFiltrados);
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Solicitud";
        require APP . 'Laboratorios/vistas/formularioSolicitudesVista.php';
    }

    /**
     * Retorna los datos del laboratorio
     * @param type $idLaboratorio
     */
    public function getDatosLaboratorio($idLaboratorio)
    {
        //buscar los servicios predeterminados del laboratorio
        $serPredeterminados = array();
        $lNegocioServicios = new ServiciosLogicaNegocio();
        $buscaServicios = $lNegocioServicios->buscarServiciosCodigoEspecial($idLaboratorio, Constantes::SER_PREDETERMINADO);
        foreach ($buscaServicios as $filaS)
        {
            $serPredeterminados[] = $filaS->id_servicio;
        }

        //datos del laboratorio
        $result = $this->lNegocioLaboratorios->buscarLista(array('id_laboratorio' => $idLaboratorio));
        $fila = $result->current();
        echo json_encode(array(
            'atributos' => $fila->atributos,
            'ingredienteActivo' => $this->casoEspecialLaboratorio($idLaboratorio, Constantes::LAB_INGREDIENTE_ACTIVO),
            'serPredeterminados' => implode(',', $serPredeterminados)));
    }

    /**
     * Método para registrar en la base de datos -Solicitudes
     */
    public function guardar()
    {
        $_POST['usuarioInterno'] = $this->usuarioInterno;
        $_POST['usuario_guia'] = $this->identificador;
        if (empty($_POST['tipo_solicitud']))
        {
            $_POST['tipo_solicitud'] = Constantes::tipo_SO()->ENVIADA_CLIENTE;
        }
        if (empty($_POST['id_localizacion']))
        {
            $_POST['id_localizacion'] = 259;    //Obligatorio Pichincha
        }
        $_POST['codigo'] = " "; //El código se actualiza con un trigger
        $this->lNegocioSolicitudes->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO . ". Seleccione y dar clic en Finalizar para enviar la Solicitud");
    }

    /**
     * Crea una solicitud para enviada por un modulo externo
     * @param type $moduloExterno
     * @param type $idProcesoExterno
     */
    public function crear($moduloExterno, $idProcesoExterno)
    {
        $datos = array("tipo_solicitud" => Constantes::tipo_SO()->MODULO_EXTERNO, "modulo_externo" => $moduloExterno, "id_proceso_externo" => $idProcesoExterno, "usuario_guia" => $this->usuarioActivo(), "codigo" => "temp");
        $idSolicitud = $this->lNegocioSolicitudes->crear($datos);
        $arrayParametros = array('idSolicitud' => $idSolicitud);
        $modeloSolicitudes = $this->lNegocioSolicitudes->buscarSolicitudes($arrayParametros);
         $this->cajaSolicitud($modeloSolicitudes);
       
    }
   

    /**
     * Crea una solicitud para enviada por un modulo externo
     * @param type $moduloExterno
     * @param type $idProcesoExterno
     */
    public function crearSolicitudMultiusuario()
    {
        $datos = array(
            "tipo_solicitud" => Constantes::tipo_SO()->MULTIUSUARIO,
            "usuario_guia" => $this->usuarioActivo(),
            "codigo" => "temp",
            "muestreo_nacional" => "SI");
        $this->lNegocioSolicitudes->crearSolicitudMultiusuario($datos);
        echo Constantes::GUARDADO_CON_EXITO;
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Solicitudes
     */
    public function editar()
    {
        $idSolicitud = $_POST["id"];
        if ($idSolicitud != null)
        {
            $this->modeloSolicitudes = $this->lNegocioSolicitudes->buscar($idSolicitud);
            if ($this->modeloSolicitudes->getEstado() == Constantes::estado_SO()->REGISTRADA)
            {
                //
                if ($this->modeloSolicitudes->getTipoSolicitud() == Constantes::tipo_SO()->MULTIUSUARIO)
                {
                    $buscaUsuariosSolicitud = $this->lNegocioSolicitudes->buscarSolicitudes(array('idSolicitud' => $idSolicitud));
                    $fila = $buscaUsuariosSolicitud->current();
                    if ($fila->tipo == 'RESPALDO')
                    {
                        $fechaActual = date('Y-m-d');
                        if ($fechaActual >= $fila->fecha_inicio & $fechaActual <= $fila->fecha_fin)
                        {
                            $this->accion = "Editar Solicitud Multiusuario";
                            //obtener detalle
                            $modeloDetallesolicitudes = $this->lNegocioDetallesolicitudes->listaDetalleSolicitudes($idSolicitud);
                            $this->tablaHtmlDetalleSolicitudes($modeloDetallesolicitudes);
                            require APP . 'Laboratorios/vistas/formularioSolicitudesVista.php';
                        } else
                        {
                            echo Constantes::NO_PERMISO_MULTIUSUARIO;
                        }
                    } else
                    {
                        $this->accion = "Editar Solicitud Multiusuario";
                        //obtener detalle
                        $modeloDetallesolicitudes = $this->lNegocioDetallesolicitudes->listaDetalleSolicitudes($idSolicitud);
                        $this->tablaHtmlDetalleSolicitudes($modeloDetallesolicitudes);
                        require APP . 'Laboratorios/vistas/formularioSolicitudesVista.php';
                    }
                } else
                {
                    $this->accion = "Editar Solicitud";
                    //obtener detalle
                    $modeloDetallesolicitudes = $this->lNegocioDetallesolicitudes->listaDetalleSolicitudes($idSolicitud);
                    $this->tablaHtmlDetalleSolicitudes($modeloDetallesolicitudes);
                    require APP . 'Laboratorios/vistas/formularioSolicitudesVista.php';
                }
            } else
            {
                $this->verDatosSolicitud($idSolicitud);
            }
        }
    }

    /**
     * Obtener datos de la muestra segun el laboratorio
     * Si ya existe una muestra del laboratorio seleccionado entonces se muestra los datos guardados
     */
    public function getDatosMuestra($idSolicitud = "", $idLaboratorio = "")
    {
        if ($idSolicitud != "")
        {
            $this->modeloMuestras = $this->lNegocioMuestras->buscarMuestraLaboratorio($idSolicitud, $idLaboratorio);
        }
        require APP . 'Laboratorios/vistas/ubicacionMuestraVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Solicitudes
     */
    public function borrar()
    {
        $this->lNegocioSolicitudes->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Solicitudes
     */
    public function cajaSolicitud($tabla)
    {
        if (count($tabla) > 0)
        {
            $caja='';
            $contador = 0;
            foreach ($tabla as $fila)
            {            
                  $caja=  '<article id="' . $fila->id_solicitud . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/Solicitudes"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
                 <div id="cajaMexterno"> 
                  <p>Solicitud ID: '.$fila->id_solicitud.'</p>
                  <p>Código: '.$fila->codigo.'</p>
                  <p>Tipo: '.$fila->tipo_solicitud.'</p>
                  <p>Fecha de registro: '.$fila->fecha_registro.'</p>
                  <p>Estado: '.$fila->estado.'</p>
                  <p>Fecha de entrega: '.$fila->fecha_final_estimada.'</p>
                  </div>
                  <aside style="width: 100%;">Informe: ' . $this->botonInformes($fila->id_solicitud) . '</aside>'
                  . '</article>';
                
            }
        }
        echo $caja;
    }
    
    
     /**
     * Construye el código HTML para desplegar la lista de - Solicitudes
     */
    public function tablaHtmlSolicitudes($tabla)
    {
        if (count($tabla) > 0)
        {
            $contador = 0;
            foreach ($tabla as $fila)
            {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila->id_solicitud . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/Solicitudes"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila->codigo . '</b></td>
                <td>' . $fila->tipo_solicitud . '</td>
                <td>' . $fila->fecha_registro . '</td>
                <td>' . $fila->fecha_final_estimada . '</td>
                <td style="text-align:center">' . $this->botonInformes($fila->id_solicitud) . '</td>
                <td>' . $fila->estado . '</td>
                </tr>'
                );
            }
        } else
        {
            $this->itemsFiltrados[] = array("<tr><td colspan='5'>No existen datos para mostrar. Verificar el filtro de b&uacute;squeda.</td></tr>");
        }
    }

    /**
     * Obtiene los ids de los servicios guardados segun el laboratorio a editar
     * Tablas: muestras
     */
    public function getTotalMuestras($idSolicitud, $idLaboratorio)
    {
        $buscaTotalMuestras = $this->lNegocioSolicitudes->totalMuestras($idSolicitud, $idLaboratorio);
        $servicios = "";
        $cantidades = "";
        foreach ($buscaTotalMuestras as $row)
        {
            $servicios.=$row['id_servicio'] . ',';
            $cantidades.=$row['id_servicio'] . '-' . $row['total_muestras'] . ',';
        }
        echo json_encode(array('servicios' => $servicios, 'cantidades' => $cantidades));
    }

    /**
     * Elimina el registro detalle_solicitudes
     * @param type $idSolicitud
     * @param type $idDetalleSolicitudes
     */
    public function borrarDetalleSolicitudes($idSolicitud, $idDetalleSolicitudes)
    {
        $mensaje = array();
        $result = $this->lNegocioSolicitudes->eliminarAnalisisRegistrado($idDetalleSolicitudes);
        $fila = $result->current();
        if ($fila->resultado == '')
        {
            //obtener detalle
            $modeloDetallesolicitudes = $this->lNegocioDetallesolicitudes->listaDetalleSolicitudes($idSolicitud);
            $this->tablaHtmlDetalleSolicitudes($modeloDetallesolicitudes);
            echo json_encode(array(
                "estado" => "exito",
                "mensaje" => Constantes::ELIMINADO_CON_EXITO,
                "detalle" => $this->detalleSolicitudesGuardado));
        } else
        {
            $mensaje['estado'] = 'ERROR';
            $mensaje['mensaje'] = $fila->resultado;
            echo json_encode($mensaje);
        }
    }

    /**
     * Retorna los datos del servicio
     * @param type $idServicio
     */
    public function datosServicio($idServicio)
    {
        $lNegocioServicios = new ServiciosLogicaNegocio();
        $buscaServicio = $lNegocioServicios->buscarLista(array('id_servicio' => $idServicio));
        $fila = $buscaServicio->current();
        if ($fila)
        {
            $fila->requisitos = str_replace("{RUTAIDTM}", URL_DIR_IDTM, $fila->requisitos);
        }
        echo json_encode(array(
            'requisitos' => $fila->requisitos,
            'serMarbetes' => $this->casoEspecialServicio($idServicio, Constantes::SER_MARBETES),
            'serFAExcel' => $this->casoEspecialServicio($idServicio, Constantes::SER_FA_EXCEL),
            'serPredeterminado' => $this->casoEspecialServicio($idServicio, Constantes::SER_PREDETERMINADO)
        ));
    }

    /**
     * Obtiene los datos del servicio con el tiempo de respuesta
     * @param type $idServicio
     */
    public function datosServicioTiempoRespuesta()
    {
        $idLocalizacionMuestra = isset($_POST['idLocalizacionMuestra']) ? $_POST['idLocalizacionMuestra'] : '';
        $idServicio = $_POST['idServicio'];
        $idServicioUltimoNivel = $_POST['idServicioUltimoNivel'];
        $cantidad = $_POST['cantidad'];
        $lNegocioServicios = new ServiciosLogicaNegocio();

        if (empty($idLocalizacionMuestra))
        {
            $idLocalizacionMuestra = Constantes::LAB_LN_TUMBACO;   //Obligatorio Pichincha
        }
        //buscar el registro de la tabla servicios
        $buscaServicio = $lNegocioServicios->buscar($idServicioUltimoNivel);
        $rama = $buscaServicio->getRama();
        $tiempoRespuesta = '';
        if ($this->usuarioInterno)
        {
            $tipoUsuario = 'INTERNO';
        } else
        {
            $tipoUsuario = 'EXTERNO';
        }

        //buscar el tipo
        $lNDistribucionMuestras = new DistribucionMuestrasLogicaNegocio();
        $arrayParametros = array('idServicio' => $idServicio, 'idLocalizacionMuestra' => $idLocalizacionMuestra);
        $buscaTipo = $lNDistribucionMuestras->buscarDistribucionMuestras($arrayParametros);
        $resultTipo = $buscaTipo->current();
        $idLaboratoriosProvincia = 0;
        if (!$resultTipo)
        {
            $tipoLaboratorio = 'LN';
        } else
        {
            $tipoLaboratorio = $resultTipo->tipo;
            $idLaboratoriosProvincia = $resultTipo->id_laboratorios_provincia; //Es necesario para cuando el tiempo esta configurado a nivel de provinicia 
        }

        //buscar el tiempo de respuesta segun el tipo de usuario, tipo de laboratorio, 
        $lNegocioTiemposRespuesta = new TiemposRespuestaLogicaNegocio();
        $result = $lNegocioTiemposRespuesta->buscarTiempos($tipoUsuario, $rama, $tipoLaboratorio, $idLaboratoriosProvincia);
        foreach ($result as $fila)
        {
            $condicion = "return " . str_replace("#", $cantidad, $fila['condicion']) . ";";
            if (eval($condicion))
            {
                $tiempoRespuesta = $fila['tiempo_respuesta'];
            }
        }
        if ($tiempoRespuesta == '')
        {
            $estado = 'ERROR';
            $mensaje = Constantes::ERROR_TIEMPO_RESPUESTA;
        } else
        {
            $estado = 'EXITO';
            $mensaje = '';
        }
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'tiempoRespuesta' => $tiempoRespuesta));
    }

    /**
     * Forma la tabla de detalle que contiene los tipos de analisis guardados
     * que se muestra en el paso 1
     * @param type $tabla
     */
    public function tablaHtmlDetalleSolicitudes($tabla)
    {
        $contador = 0;
        $html = "";
        foreach ($tabla as $fila)
        {
            $idServicio = "<input type='hidden' name='id_servicio_guardados[]' name='id_servicio_guardados[]' value='$fila->id_servicio'/>";
            $url = URL . "Laboratorios/Laboratorios/agregarServicios/$fila->id_solicitud/$fila->id_laboratorio";
            $botonAgregarServicios = "<button type='button' class='far fa-window-restore' "
                    . "onClick=fn_agregarServiciosModal($fila->id_solicitud,$fila->id_detalle_solicitud,$fila->id_servicio,$fila->total_muestras,$fila->id_laboratorio,'" . $url . "')/>";
            $html.=
                    "<tr id='{$fila->id_detalle_solicitud}'>"
                    . "<td>" . ++$contador . $idServicio . "</td>"
                    . "<td>{$fila->nom_laboratorio}</td>"
                    . "<td>{$fila->nom_servicio}</td>"
                    . "<td style='text-align: center'>{$fila->total_muestras}</td>"
                    . "<td style='text-align: right'>$ {$fila->valor}</td>"
                    . "<td style='text-align: right'>$ <span class='list_costo_guardado'>" . round($fila->valor_total, 2) . "</span></td>"
                    . "<td style='text-align: center'><input type='checkbox' id='{$fila->id_detalle_solicitud}' class='chk_detalle' value='$fila->id_detalle_solicitud' onclick='fn_checkEditar(this,{$fila->id_laboratorio})'/></td>"
                    . "<td style='text-align: center'><button type='button' name='eliDetSol' id='eliDetSol' class='fas fa-minus' onClick='fn_eliDetSol($fila->id_solicitud, $fila->id_detalle_solicitud)'/></td>"
                    . "<td style='text-align: center'>$botonAgregarServicios</td>"
                    . "</tr>";
        }
        $this->detalleSolicitudesGuardado = $html;
    }

    public function anadirServicios()
    {
        $idSolicitud = $_POST['idSolicitud'];
        if (!isset($_POST['muestras']))
        {
            Mensajes::fallo("Seleccione al menos una muestra");
        } else
        {
            $muestras = implode(',', $_POST['muestras']);   //ejm: M1,M3,M4
            $idServicio = $_POST['idServicioAgregar'];
            $idDetalleSolicitud = $_POST['idDetalleS'];
            $this->lNegocioSolicitudes->anadirServicios($idSolicitud, $idDetalleSolicitud, $idServicio, $muestras);
            //obtener detalle
            $modeloDetallesolicitudes = $this->lNegocioDetallesolicitudes->listaDetalleSolicitudes($idSolicitud);
            $this->tablaHtmlDetalleSolicitudes($modeloDetallesolicitudes);
            echo json_encode(array(
                "estado" => "exito",
                "mensaje" => Constantes::GUARDADO_CON_EXITO,
                "detalle" => $this->detalleSolicitudesGuardado));
        }
    }

    /**
     * Despliega la vista para finalizar la solicitud
     */
    public function finalizar()
    {
        $this->accion = "Finalizar y enviar solicitud";
        $idSolicitud = $_POST["elementos"];
        if ($idSolicitud != null)
        {
            //verificar que tenga registrado detalle
            $buscaDetallesolicitudes = $this->lNegocioDetallesolicitudes->listaDetalleSolicitudes($idSolicitud);
            if (count($buscaDetallesolicitudes) == 0)
            {
                echo "<script> mostrarMensaje('".Constantes::INF_NO_ANALISIS."', 'fallo'); </script>";
            } else
            {
                $this->modeloSolicitudes = $this->lNegocioSolicitudes->buscar($idSolicitud);
                if ($this->modeloSolicitudes->getEstado() == Constantes::estado_SO()->REGISTRADA)
                {
                    //buscar datos de la muestra, necesario para comboLaboratoriosProvincia en caso de facturación
                    $buscaMuestras = $this->lNegocioMuestras->buscarLista(array('id_solicitud' => $this->modeloSolicitudes->getIdSolicitud()));
                    $fila = (array) $buscaMuestras->current();
                    $this->modeloMuestras = new MuestrasModelo($fila);
                    $this->verDatosSolicitud($idSolicitud); //funcion comun presenta datos de la solicitud
                    require APP . 'Laboratorios/vistas/finalizarEnviarSolicitudVista.php';
                } else
                {
                     Mensajes::fallo("La solicitud debe estar en estado " . Constantes::estado_SO()->REGISTRADA . " para poder enviar.");
                    
                }
            }
        }
    }

    /**
     * Para buscar si existe un usuario recaudador de la provincia a donde va a dejar la muestra
     * @param type $idLaboratorioProvincia
     */
    public function buscarUsuarioRecaudador($idLaboratorioProvincia)
    {
        $estado = 'EXITO';
        $mensaje = '';
        //buscar el usuario recaudador de la provincia 
        $lNUsuarioLaboratorio = new UsuarioLaboratorioLogicaNegocio();
        $buscaUsuarioLaboratorio = $lNUsuarioLaboratorio->buscarRecaudadorDeProvincia($idLaboratorioProvincia);
        $usuarioLaboratorio = $buscaUsuarioLaboratorio->current();
        if (!isset($usuarioLaboratorio->id_usuario_laboratorio))
        {
            $estado = 'ERROR';
            $mensaje = 'No existe un usuario recaudador para la provincia seleccionada. Favor comunicarse con el administrador del sistema.';
        }
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje));
    }

    /**
     * Finalizar la solicitud
     * La información para la orden de pago se guarda en 
     * - g_financiero_automatico.financiero_cabecera -> Cabecera
     * - g_financiero_automatico.financiero_detalle -> Detalle
     * Una vez insertados los datos, se dispone de un cron el cual genera la orden de pago.
     */
    public function guardarFinalizar()
    {
        $_POST['usuarioInterno'] = $this->usuarioInterno;   // true/false
        $_POST['identificador'] = parent::usuarioActivo();
        //enviar a guardar si la solicitud tiene analisis solicitados
        $buscaDetalleSolicitudes = $this->lNegocioDetallesolicitudes->buscarLista(array('id_solicitud' => $_POST['id_solicitud']));
        if (count($buscaDetalleSolicitudes) > 0)
        {
            $this->lNegocioSolicitudes->guardarFinalizar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        } else
        {
            Mensajes::info(Constantes::INF_NO_ANALISIS);
        }
    }

    /**
     * Obtener datos de la persona
     * @param type $ciRuc
     */
    public function getDatosPersona($ciRuc)
    {
        $cliente = new ClientesLogicaNegocio();
        $buscaCliente = $cliente->buscar("'$ciRuc'");
        echo json_encode(array(
            'nombre' => $buscaCliente->getRazonSocial(),
            'direccion' => $buscaCliente->getDireccion(),
            'telefono' => $buscaCliente->getTelefono(),
            'email' => $buscaCliente->getCorreo()
        ));
    }

    /**
     * Obtener datos de la persona utilizado para la proforma
     * @param type $ciRuc
     */
    public function getDatosPersonaProforma($ciRuc)
    {
        $persona = new PersonasLogicaNegocio();
        $buscaPersona = $persona->buscarPersona($ciRuc);
        $fila = $buscaPersona->current();
        if ($fila == null)
        {
            echo json_encode(array('id_persona' => NULL, 'nombre' => NULL, 'direccion' => NULL, 'telefono' => NULL, 'email' => NULL));
        } else
        {
            echo json_encode($fila);
        }
    }

    /**
     * Despliega la vista de la proforma
     */
    public function vistaProforma()
    {
        require APP . 'Laboratorios/vistas/proformaVista.php';
    }

    /**
     * Construye la tabla con los datos del memo, retorna tipo JSON
     * @param type $memo
     */
    public function buscarDatosMemo($memo)
    {
        $buscaDatosMemo = $this->lNegocioSolicitudes->buscarDatosMemo($memo);
        $contador = 0;
        $tabla = "";
        $total = 0;
        $numMuestras = 0;
        foreach ($buscaDatosMemo as $fila)
        {
            $tabla.= '<tr>
		<td>' . ++$contador . '</td>
		<td style="text-align: center"><b>' . $fila->codigo . '</b></td>
                <td style="text-align: center">' . $fila->fecha_registro . '</td>
                <td style="text-align: center">' . $fila->oficio_exoneracion . '</td>
                <td style="text-align: right">' . $fila->num_muestras_exoneradas . '</td>
                <td style="text-align: right">' . $fila->num_muestras . '</td>
                </tr>';
            $numMuestras = $fila->num_muestras_exoneradas;
            $total = $total + $fila->num_muestras;
        }
        $saldo = $numMuestras - $total;
        echo json_encode(array('tabla' => $tabla, 'total' => $total, 'saldo' => $saldo));
    }

    /*     * ************************** */
    /* FIEBRE AFTOSA */
    /*     * ************************** */

    /**
     * FIEBRE AFTOSA
     * Método para desplegar el formulario
     */
    public function nuevoFA()
    {
        $this->accion = "Nueva Solicitud Fiebre Aftosa";
        $datos = $_POST;
        //Datos de la direccion
        $this->datosDireccion = new LaboratoriosModelo();
        $this->datosDireccion = $this->lNegocioLaboratorios->buscar($_POST['direccion']);
        $this->datos['idDireccion'] = $this->datosDireccion->getIdLaboratorio();
        $this->datos['nomDireccion'] = $this->datosDireccion->getNombre();
        //Datos del laboratorio
        $this->datosLaboratorio = new LaboratoriosModelo();
        $this->datosLaboratorio = $this->lNegocioLaboratorios->buscar($_POST['id_laboratorio']);
        //Datos del servicio
        $idServicio = $_POST['servicio'];
        $this->datosServicio = new ServiciosModelo();
        $this->datosServicio = $this->lNegocioServicios->buscar($idServicio);

        $this->modeloSolicitudes->setMuestreoNacional('SI');

        $this->modeloDetallesolicitudes->setIdServicio($idServicio);
        $this->modeloMuestras->setIdLaboratorio($_POST['id_laboratorio']);
        require APP . 'Laboratorios/vistas/formularioSolicitudesFAVista.php';
    }

    /**
     * Guarda los datos en la tabla g_laboratorios.fiebre_aftosa
     */
    public function guardarFA()
    {
        //Datos de la solicitud
        $_POST['tipo_solicitud'] = Constantes::tipo_SO()->MUESTRA_CIEGA;
        $_POST['usuario_guia'] = $this->identificador;
        $_POST['codigo'] = " "; //El código se actualiza con un trigger
        $_POST['exoneracion'] = 'SI';
        $_POST['estado'] = Constantes::estado_SO()->ENVIADA;

        $_POST['observacion'] = 'MUESTRA CIEGA';

        $_POST['opPropietario'] = 1; //mismo de la solicitud
        //Datos muestra
        $_POST['id_localizacion'] = 259;    //Obligatorio Pichincha
        $_POST['referencia_ubicacion'] = 'MUESTRA CIEGA';

        $_POST['observacion'] = 'MUESTRA CIEGA';

        //Datos detalle muestra
        $_POST['m_texto703'] = '0';
        $_POST['m_texto711'] = 'MUESTRA CIEGA - CENSO NACIONAL';

        //Datos tipo analisis
        $_POST['a_texto714'] = '0-MUESTREO NACIONAL';

        $this->lNegocioSolicitudes->guardarFA($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /*     * ************************** */
    /* DERIVACION */
    /*     * ************************** */

    private $muestras;      //ids de las muestras seleccionadas para derivar o confirmar
    private $numMuestras;   //numero de muestras seleccionadas para derivar o confirmar
    private $notificarCliente;  //si ha seleccionado para notificar al cliente
    private $usuario_guia_sol_principal;

    /**
     * Método para desplegar el formulario
     */
    public function nuevoDerivacion()
    {
        $this->accion = "Nueva Solicitud por Derivaci&oacute;n";
        $this->muestras = implode(',', $_POST['muestras']);
        $this->numMuestras = count($_POST['muestras']);
        $this->notificarCliente = isset($_POST['chkNotificarCliente']) ? $_POST['chkNotificarCliente'] : 'NO';
        $this->usuario_guia_sol_principal = $_POST['usuario_guia'];
        //datos de la solicitud
        $this->modeloSolicitudes->setTipoSolicitud(Constantes::tipo_SO()->DERIVACION);
        $this->modeloSolicitudes->setRequiereNuevaMuestra(isset($_POST['chkNuevaMuestra']) ? $_POST['chkNuevaMuestra'] : 'NO');
        $this->modeloSolicitudes->setExoneracion(isset($_POST['chkNuevoPago']) ? 'NO' : 'SI');
        require APP . 'Laboratorios/vistas/formularioSolicitudesDerivacionVista.php';
    }

    /**
     * Crear una solicitud por derivación
     */
    public function guardarDerivacion()
    {
        $_POST['usuarioInterno'] = $this->usuarioInterno;
        $_POST['usuario_guia'] = $this->identificador;
        $_POST['tipo_solicitud'] = Constantes::tipo_SO()->DERIVACION;
        $_POST['codigo'] = " "; //El código se actualiza con un trigger
        $this->lNegocioSolicitudes->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO . ".<b> Ir a Solicitudes para finalizar y enviar la solicitud.</b>");
    }

    /*     * ************************** */
    /* CONFIRMACION DE ANALISIS */
    /*     * ************************** */

    /**
     * Método para desplegar el formulario
     */
    public function nuevoConfirmacionAnalisis()
    {
        $this->accion = "Nueva Solicitud por Confirmaci&oacute;n de an&aacute;lisis";
        $this->muestras = implode(',', $_POST['muestras']);
        $this->numMuestras = count($_POST['muestras']);
        $this->notificarCliente = isset($_POST['chkNotificarCliente']) ? $_POST['chkNotificarCliente'] : 'NO';
        $this->usuario_guia_sol_principal = $_POST['usuario_guia'];
        //datos de la solicitud
        $this->modeloSolicitudes->setTipoSolicitud(Constantes::tipo_SO()->CONFIRMACION);
        $this->modeloSolicitudes->setRequiereNuevaMuestra(isset($_POST['chkNuevaMuestra']) ? $_POST['chkNuevaMuestra'] : 'NO');
        $this->modeloSolicitudes->setExoneracion(isset($_POST['chkNuevoPago']) ? 'NO' : 'SI');
        require APP . 'Laboratorios/vistas/formularioSolicitudesConfirmacionVista.php';
    }

    /**
     * Crea la solicitud de Confirmación de Análisis
     */
    public function guardarConfirmacionAnalisis()
    {
        $_POST['usuarioInterno'] = $this->usuarioInterno;
        $_POST['usuario_guia'] = $this->identificador;
        $_POST['tipo_solicitud'] = Constantes::tipo_SO()->CONFIRMACION;
        $_POST['codigo'] = " "; //El código se actualiza con un trigger
        $this->lNegocioSolicitudes->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO . ".<b> Ir a Solicitudes para finalizar y enviar la solicitud.</b>");
    }

    /*     * ************************** */
    /* MARBETES */
    /*     * ************************** */

    /**
     * Construye la tabla TIPO DE ANALISIS SOLICITADO
     */
    public function camposAnalisisBarbetes()
    {
        $html = "<fieldset><legend>TIPO DE ANÁLISIS SOLICITADO</legend>
            <table id='detalleSolicitud'>
                <thead>
                    <tr>
                        <th>ANÁLISIS SOLICITADO</th>
                        <th>Identificación de la muestra *</th>
                        <th>Cultivo *</th>
                        <th>Variedad *</th>
                        <th>N°. lote *</th>
                        <th>Peso </th>
                        <th>N°. marbetes *</th>
                    </tr>
                </thead>
                <tbody>";
        for ($i = 1; $i <= $_POST['cantidadLotes']; $i++)
        {
            $html.="<tr>        
            <td>14</td>
            <td>
                <input id='codigo_usu_muestra' name='codigo_usu_muestra_34[]' placeholder='' value='' maxlength='32' required type='text'>
            </td>
            <td>
                <input id='a_texto409_34_$i' name='a_texto409_34_$i' placeholder='Ej: Trigo' required value='' type='text'>
            </td>
            <td>
                <input id='a_texto410_34_$i' name='a_texto410_34_$i' placeholder='Ej: Imbabura' size='8' required value='' type='text'>
            </td>
            <td>
                <input id='a_texto412_34_$i' name='a_texto412_34_$i' placeholder='Ej: 5' size='5' required value='' type='text'>
            </td>
            <td>
                <input id='a_texto413_34_$i' name='a_texto413_34_$i' placeholder='Ej: NA  (No Aplica)' size='5' value='' type='text'>
            </td>
            <td>
                <input id='a_texto1355_34_$i' name='a_texto1355_34_$i' placeholder='No informa' required value='' type='number' min='1' class='clsNumMarbetes'>
            </td>
        </tr>";
        }
        $html.="</tbody>
            </table></fieldset>";
        echo $html;
    }

}
