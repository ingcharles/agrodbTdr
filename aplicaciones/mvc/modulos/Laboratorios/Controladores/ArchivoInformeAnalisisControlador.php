<?php

/**
 * Controlador ArchivoInformeAnalisis
 *
 * Este archivo controla la lógica del negocio del modelo:  ArchivoInformeAnalisisModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     ArchivoInformeAnalisisControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Laboratorios\Modelos\ArchivoInformeAnalisisLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ArchivoInformeAnalisisModelo;
use Agrodb\Laboratorios\Modelos\InformesLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ParametrosLaboratoriosLogicaNegocio;
use Agrodb\Laboratorios\Modelos\ParametrosServiciosLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class ArchivoInformeAnalisisControlador extends BaseControlador
{

    private $lNegocioArchivoInformeAnalisis = null;
    private $modeloArchivoInformeAnalisis = null;
    private $mensajeFirmar = null;
    private $estdoFirma = 'disabled="disabled"';
    private $accion = null;
    private $rutaAdjunto = null;
    private $msgAcreditacion = null;
     private $msgAcreditacion2 = null;
    private $idParametroServicio = null;
    private $msgTablaReferencia = null;
    private $idParametro = null; //Mensaje de acreditación cabecera de informe
    private $idParametro2 = null; //Mensaje de acreditación pie de informe
    public $requierePago;         //Si/NO si existe o no el pago de la solicitud
    private $idInforme;
    private $idAInforme;
    /**
     * Constructor
     */

    function __construct()
    {
        parent::__construct();
        $this->lNegocioArchivoInformeAnalisis = new ArchivoInformeAnalisisLogicaNegocio();
        $this->modeloArchivoInformeAnalisis = new ArchivoInformeAnalisisModelo();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloArchivoInformeAnalisis = $this->lNegocioArchivoInformeAnalisis->buscarArchivoInformeAnalisis();
        $this->tablaHtmlArchivoInformeAnalisis($modeloArchivoInformeAnalisis);
        require APP . 'Laboratorios/vistas/listaArchivoInformeAnalisisVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Archivo Informe Analisis";
        require APP . 'Laboratorios/vistas/formularioArchivoInformeAnalisisVista.php';
    }

    /**
     * Método para registrar en la base de datos -ArchivoInformeAnalisis
     */
    public function guardar()
    {
        $this->lNegocioArchivoInformeAnalisis->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
       
    }

    /**
     * Agregar un nuevo informe
     */
    public function agregarInforme()
    {
        $this->lNegocioArchivoInformeAnalisis->crearInforme($_POST, $this->laboratorioUsuario());
        Mensajes::exito(Constantes::INFORME_CREADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ArchivoInformeAnalisis
     */
    public function editar()
    {
        $this->accion = "Editar ArchivoInformeAnalisis";
        $this->modeloArchivoInformeAnalisis = $this->lNegocioArchivoInformeAnalisis->buscar($_POST["id"]);
        require APP . 'Laboratorios/vistas/formularioArchivoInformeAnalisisVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ArchivoInformeAnalisis
     */
    public function borrar()
    {
        $this->lNegocioArchivoInformeAnalisis->borrar($_POST['elementos']);
    }

    /**
     * Funcion para editar el id padre desde la grilla
     */
    public function editarDnD()
    {
        $datos = array('id_archivo_informe_analisis' => $_POST['idArchivoInformeAnalisis'], 'fk_id_archivo_informe_analisis' => $_POST['fkIdArchivoInformeAnalisis']);

        $this->lNegocioArchivoInformeAnalisis->guardar($datos);
    }

    /**
     * Muestra la información de la firma antes de ser enviada
     */
    public function legalizarInforme()
    {
        $this->accion = "Firma informe";
        $objFirma = new \Agrodb\Laboratorios\Modelos\FirmasElectronicasLogicaNegocio();
        $datosFirma = $objFirma->buscarFirmasElectronicas($this->usuarioActivo());
        $this->mensajeFirmar = "";
        $this->estdoFirma = 'disabled="disabled"';
        foreach ($datosFirma as $fila)
        {

            $this->mensajeFirmar = str_replace("%usuario%", $fila->usuario, Constantes::MENSAJE_FIRMAR);
            $this->mensajeFirmar = str_replace("%estado%", $fila->estado, $this->mensajeFirmar);
        }
        if (!empty($this->mensajeFirmar))
        {
            if ($fila->estado == 'ACTIVO')
            {
                $this->estdoFirma = "";
                $this->mensajeFirmar = '<span class="label label-default" >' . $this->mensajeFirmar . '</span>';
            } else
            {
                $this->mensajeFirmar = '<span class="label label-danger" >' . $this->mensajeFirmar . '</span>';
            }
        } else
        {
            $this->mensajeFirmar = '<span class="label label-danger" >' . Constantes::ERROR_MENSAJE_FIRMAR . '</span>';
        }

        $this->modeloArchivoInformeAnalisis = $this->lNegocioArchivoInformeAnalisis->buscar($_POST['idAInforme']);
        require APP . 'Laboratorios/vistas/formularioLegalizarInformeInformeVista.php';
    }

    /**
     * Muestra el formularios para notificar la ruta de descargar de los informes
     */
    public function enviarInforme()
    {
        //si no existe pago pendiente por registras permitir enviar el informe
        $this->requierePago = $this->lNegocioArchivoInformeAnalisis->requiereRegistroPago($_POST['idAInforme']);
        $this->accion = "Enviar informe";
        $this->modeloArchivoInformeAnalisis = $this->lNegocioArchivoInformeAnalisis->buscar($_POST['idAInforme']);
        require APP . 'Laboratorios/vistas/formularioEnviarInformeInformeVista.php';
    }

    /**
     * Procede a enviar la notificación
     */
    public function enviarNotificacionInforme()
    {
        $idArchivoInformeAnalisis = $_POST['id_archivo_informe_analisis'];
        if ($idArchivoInformeAnalisis !== '')
        {
            $_POST['identificador'] = parent::usuarioActivo();  //para copia        
            $this->lNegocioArchivoInformeAnalisis->enviar($_POST);
            Mensajes::exito(Constantes::INFORME_ENVIADO_CON_EXITO);
        } else
        {
            Mensajes::fallo(Constantes::ERROR_ENVIAR_INFORME);
        }
    }

    /**
     * Registra una observación general en el informe
     */
    public function observacion()
    {
        $this->accion = "Observación del análisis";
        $this->modeloArchivoInformeAnalisis = $this->lNegocioArchivoInformeAnalisis->buscar($_POST['idAInforme']);
        require APP . 'Laboratorios/vistas/formularioObservacionInformeVista.php';
    }

    /**
     * Configurar el informe
     */
    public function configurar($idServicio = 0)
    {
        $this->accion = "Configurar informe";
        $this->modeloArchivoInformeAnalisis = $this->lNegocioArchivoInformeAnalisis->buscar($_POST['idAInforme']);
        //buscamos parámetros del laboratorio nensaje de acreditación cabecera del informe
        $parametros = new ParametrosLaboratoriosLogicaNegocio();
        $resultado = $parametros->buscarParametro(Constantes::tipo_parametro()->MENSAJE_ACREDITACION, $this->laboratorioUsuario());
        $this->msgAcreditacion = "";
        foreach ($resultado as $fila)
        {
            $this->msgAcreditacion = $fila['descripcion'];
            $this->idParametro = $fila['id_parametros_laboratorio'];
        }
        
        //buscamos parámetros del laboratorio nensaje de acreditación pie del informe
        $parametros = new ParametrosLaboratoriosLogicaNegocio();
        $resultado = $parametros->buscarParametro(Constantes::tipo_parametro()->MENSAJE_ACREDITACION_PIE, $this->laboratorioUsuario());
        $this->msgAcreditacion2 = "";
        foreach ($resultado as $fila)
        {
            $this->msgAcreditacion2 = $fila['descripcion'];
            $this->idParametro2 = $fila['id_parametros_laboratorio'];
        }

        require APP . 'Laboratorios/vistas/formularioConfigurarInformeVista.php';
    }

    public function buscarTableRef($idServicio = 0)
    {
        // Buscar la tabla de referencia 
        $parametrosServicio = new ParametrosServiciosLogicaNegocio();
        $resultado = $parametrosServicio->buscarParametro(Constantes::tipo_parametro()->CODIGO_REFERENCIA, $idServicio);
        $this->msgTablaReferencia = "";
        foreach ($resultado as $fila)
        {
            $this->msgTablaReferencia = $fila['descripcion'];
            $this->idParametroServicio = $fila['id_parametros_servicio'];
        }
        $datosJson = '"tabla":[{id:"' . $this->idParametroServicio . '",contenido:"' . $this->msgTablaReferencia . '"}]';

        $data = array('id' => $this->idParametroServicio, 'contenido' => $this->msgTablaReferencia);
        echo \Zend\Json\Json::encode($data);

        exit();
    }

    /**
     * Guarda la tabla de referencia del informe
     */
    public function guardarTableRef()
    {
        if (isset($_POST['descripcion']) && $_POST['descripcion'] != "")
        {
            $parametros = new ParametrosServiciosLogicaNegocio();
            $datosP = array("id_parametros_servicio" => $_POST['id_parametros_servicio'], "id_servicio" => $_POST['id_servicio'], "nombre" => "Referencial de resultado de informe",
                "id_laboratorio" => $this->laboratorioUsuario(), "tipo_campo" => Constantes::tipo_parametro()->TABLA_REFERENCIA,
                "codigo" => Constantes::tipo_parametro()->CODIGO_REFERENCIA, "descripcion" => $_POST['descripcion'],
                "obligatorio" => "NO", "orden" => 1);
            $parametros->guardar($datosP);
        }
    }

    /**
     * Guarda la configuración
     */
    public function guardarConfiguracion()
    {
        //Guardamos el contenido para la acreditación en: parametros_laboratorios

        if (isset($_POST['descripcion']) && $_POST['descripcion'] != "")
        {
            $parametros = new ParametrosLaboratoriosLogicaNegocio();
            $datosP = array("id_parametros_laboratorio" => $_POST['id_parametros_laboratorio'], "nombre" => "TEXTO PARA INFORMES CON ACREDITACIÓN (CABECERA DEL INFORME)",
                "id_laboratorio" => $this->laboratorioUsuario(), "codigo" => Constantes::tipo_parametro()->MENSAJE_ACREDITACION, "descripcion" => $_POST['descripcion'],
                "obligatorio" => "NO");
            $parametros->guardar($datosP);
        }
        //TEXTO DE ACREDITACIN PIE DE PÁGINA
         if (isset($_POST['descripcion2']) && $_POST['descripcion2'] != "")
        {
            $parametros = new ParametrosLaboratoriosLogicaNegocio();
            $datosP = array("id_parametros_laboratorio" => $_POST['id_parametros_laboratorio2'], "nombre" => "TEXTO PARA INFORMES CON ACREDITACIÓN (PIE DEL INFORME)",
                "id_laboratorio" => $this->laboratorioUsuario(), "codigo" => Constantes::tipo_parametro()->MENSAJE_ACREDITACION_PIE, "descripcion" => $_POST['descripcion2'],
                "obligatorio" => "NO");
            $parametros->guardar($datosP);
        }

        //Actualizamos la orientación del formato en: informes
        if (isset($_POST['orientacion']) && $_POST['orientacion'] != "")
        {
            $formato = new InformesLogicaNegocio();
            $datosF = array("id_informe" => $_POST['id_informe'], "orientacion" => $_POST['orientacion']);
            $formato->guardar($datosF);
        }

        //Actualizamos el formato en el informe
        $fInforme = array("id_archivo_informe_analisis" => $_POST['id_archivo_informe_analisis'], "id_informe" => $_POST['id_informe'], "agrupar_por" => $_POST['agrupar_por']);
        $this->lNegocioArchivoInformeAnalisis->guardar($fInforme);

        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Despliega formulario para adjuntar documentos
     */
    public function adjuntar()
    {
        $this->accion = "Adjuntar documentos";
        $this->modeloArchivoInformeAnalisis = $this->lNegocioArchivoInformeAnalisis->buscar($_POST['idAInforme']);
        //Creamos la ruta dentro de los informes firmados
        $this->rutaAdjunto = $this->modeloArchivoInformeAnalisis->getRutaArchivo() . '/';
        if (!file_exists(URL_DIR_LAB_AD . $this->rutaAdjunto))
        {
            mkdir(URL_DIR_LAB_AD . $this->rutaAdjunto, 0777, true);
        }
        require APP . 'Laboratorios/vistas/formularioAdjuntarInformeVista.php';
    }

    /**
     * Guarda el archivo adjunto al informe
     */
    public function guardarAdjunto()
    {
        $this->lNegocioArchivoInformeAnalisis->guardarAdjunto($_POST);
        echo Constantes::GUARDADO_CON_EXITO;
    }

    /**
     * Anula el informe y crea un informe sustituto si el usuario lo requiere
     */
    public function anular()
    {
        $this->accion = "Anular el informe";
        $this->modeloArchivoInformeAnalisis = $this->lNegocioArchivoInformeAnalisis->buscar($_POST['idAInforme']);
        require APP . 'Laboratorios/vistas/formularioAnularInformeVista.php';
    }

    /**
     * Actualiza el informe como anulado y crea un nuevo informe relacionado como sustituto
     */
    public function guardarAnulado()
    {
        $this->lNegocioArchivoInformeAnalisis->guardar($_POST);
        if (isset($_POST['sustituto']) && $_POST['sustituto'] == "SI")
        {
            $this->lNegocioArchivoInformeAnalisis->crearInforme($_POST, $this->laboratorioUsuario());
            //buscamos el último informe creado para actualizar el código de sustituto

            $datos = $this->lNegocioArchivoInformeAnalisis->buscarUltimoInforme($_POST['fk_id_archivo_informe_analisis']);
            $datoDb = array();
            foreach ($datos as $fila)
            {
                $datoDb['id_archivo_informe_analisis'] = $fila['id_archivo_informe_analisis'];
                $datoDb['nombre_informe'] = "SUSTITUTO-" . $fila['nombre_informe'];
            }
            if (isset($datoDb['id_archivo_informe_analisis']) && $datoDb['id_archivo_informe_analisis'] != "")
            {
                $datoDb['sustituto'] = $_POST['id_archivo_informe_analisis'];

                $this->lNegocioArchivoInformeAnalisis->guardar($datoDb);
            }
        }
        Mensajes::exito(Constantes::ANULADO_CON_EXITO);
    }

    /**
     * Crea un informe como alcance de un informe seleccionado
     */
    public function agregarAlcance()
    {
        $this->lNegocioArchivoInformeAnalisis->crearInforme($_POST, $this->laboratorioUsuario());

        $datos = $this->lNegocioArchivoInformeAnalisis->buscarUltimoInforme($_POST['fk_id_archivo_informe_analisis']);
        $datoDb = array();
        foreach ($datos as $fila)
        {
            $datoDb['id_archivo_informe_analisis'] = $fila['id_archivo_informe_analisis'];
            $datoDb['nombre_informe'] = "ALCANCE-" . $fila['nombre_informe'];
        }
        if (isset($datoDb['id_archivo_informe_analisis']) && $datoDb['id_archivo_informe_analisis'] != "")
        {
            $datoDb['alcance'] = $_POST['id_archivo_informe_analisis'];

            $this->lNegocioArchivoInformeAnalisis->guardar($datoDb);
        }

        Mensajes::exito(Constantes::INFORME_CREADO_CON_EXITO);
    }

    /**
     * visualizar el archivo adjunto al informe
     */
    public function descargarArchivo()
    {

        $resultado = $this->lNegocioArchivoInformeAnalisis->buscar($_POST['idAInforme']);

        $this->urlPdf = URL_MVC_MODULO . "Laboratorios/archivos/informes/firmados/" . $resultado->getRutaArchivo() . ".pdf";

        require APP . 'Laboratorios/vistas/visorPDF.php';
    }

    /**
     * Elimina fisicamente y de la base de datos el archivo adjunto al informe
     */
    public function eliminarAdjunto()
    {
        //Eliminar primero el archivo pdf
        $resultado = $this->lNegocioArchivoInformeAnalisis->buscar($_POST['idAInforme']);
        $archivo = JAVA_URL_REPORTES . "informes/firmados/" . $resultado->getRutaArchivo() . ".pdf";
        unlink($archivo);
        $this->lNegocioArchivoInformeAnalisis->borrar($_POST['idAInforme']);
        Mensajes::exito(Constantes::ELIMINADO_CON_EXITO);
    }

    /**
     * Construye tabla html
     * @param type $idSeccion
     */
    public function camposSeccionInforme($idSeccion)
    {
        $informe = new InformesLogicaNegocio();
        $opcionesHtml = "";
        $datos = $informe->buscarLista("fk_id_informe = " . $idSeccion . " ORDER BY orden");
        $html = "";
        foreach ($datos as $fila)
        {
            $orden = '<input type="number" id="orden"' . $fila['id_informe'] . ' name="orden"' . $fila['id_informe'] . ' value="' . $fila['orden'] . '" onblur="cambiarOrden(this,' . $fila['id_informe'] . ')"  maxlength="3" min="1" pattern="^[0-9]+" style="width: 50px;"/>';
            $valorEstado = '';
            if ($fila['estado_registro'] == 'ACTIVO')
            {
                $valorEstado = ' checked';
            }
            $estado = '<input type="checkbox" id="estado_registro"' . $fila['id_informe'] . ' name="estado_registro"' . $fila['id_informe'] . '  onclick="cambiarEstado(this,' . $fila['id_informe'] . ')" ' . $valorEstado . ' />';

            $html .= "<tr >'"
                    . "<td>" . $fila['nombre_informe'] . "</td>"
                    . "<td> <input type=\"text\" id=\"campoInforme" . $fila['id_informe'] . "\" name=\"" . $fila['id_informe'] . "\" value=\"" . $fila['nombre_informe'] . "\" onblur='fn_cambiar(this," . $fila['id_informe'] . ")' size='80'/></td>"
                    . "<td>" . $estado . "</td>"
                    . "<td>" . $orden . "</td>"
                    . "</tr>";
        }
        echo $html;
    }

    /**
     * Actualiza en informe
     */
    public function actualizarInforme()
    {
        $informe = new InformesLogicaNegocio();
        $informe->guardar($_POST);
    }

    /**
     * Combo con la orientacion del informe
     * @param type $idInforme
     */
    public function comboOrientacion($idInforme)
    {
        $informe = new InformesLogicaNegocio();
        $resultado = $informe->buscar($idInforme);
        echo parent::comboDespliegue($resultado->getOrientacion());
    }

    /**
     * Lista los informes para enviar
     */
    public function enviarInformes()
    {
        $idCliente = $_POST['idCliente'];

        $arrayParametros = array();
        if (!empty($idCliente))
        {
            $arrayParametros['fk_id_archivo_informe_analisis'] = $idCliente;
            $arrayParametros['estado_informe'] = Constantes::estado_informe()->FIRMADO;
        }

        $modeloLaboratorios = $this->lNegocioArchivoInformeAnalisis->buscarLista($arrayParametros);

        $html = "";

        foreach ($modeloLaboratorios as $fila)
        {
            $html .= "<tr data-tt-id='" . $fila['id_archivo_informe_analisis'] . "'>'"
                    . "<td><span class='folder'>" . $fila['nombre_informe'] . "</td>"
                    . "<td>" . $fila['estado_informe'] . "</td>"
                    . "<td>" . $fila['fecha_firma'] . "</td>"
                    . "<td>" . "<button class='bntGrid far fa-envelope' onclick='fn_enviar_informe(" . $fila['id_archivo_informe_analisis'] . ")'/>" . "</td>"
                    . "<td>" . "<button class='bntGrid fas fa-search' onclick='fn_vistaPrevia(" . $fila['id_archivo_informe_analisis'] . ")'/>" . "</td>"
                    . "</tr>";
        }
        if (empty($html))
        {
            echo "<tr><th colspan='5'>NO EXISTE INFORMES PARA MOSTRAR, PERO LA ORDEN DE TRABAJO SE ENCUENTRA ACTIVA</th></tr>";
        } else
        {
            echo $html;
        }
        exit();
    }

    /**
     * Lista informes para firmar
     */
    public function legalizarInformes()
    {
        $idCliente = $_POST['idCliente'];

        $modeloLaboratorios = $this->lNegocioArchivoInformeAnalisis->buscarInformesFirmar($idCliente);
        $html = "";
        foreach ($modeloLaboratorios as $fila)
        {
            $botonVP = "";
            $botonFirma = "<button class='bntGrid fas fa-file-signature' onclick='fn_legalizar_informe(" . $fila['id_archivo_informe_analisis'] . ")'/>";
            if ($fila['firmado'] == "SI")
            {
                $botonVP = "<button class='bntGrid fas fa-search' onclick='fn_vistaPrevia(" . $fila['id_archivo_informe_analisis'] . ")'/>";
                $botonFirma = "";
            }
            $html .= "<tr data-tt-id='" . $fila['id_archivo_informe_analisis'] . "'>'"
                    . "<td><span class='folder'>" . $fila['nombre_informe'] . "</td>"
                    . "<td>" . $fila['estado_informe'] . "</td>"
                    . "<td>" . $fila['fecha_firma'] . "</td>"
                    . "<td>" . $botonFirma . "</td>"
                    . "<td>" . $botonVP . "</td>"
                    . "</tr>";
        }
        if (empty($html))
        {
            echo "<tr><th colspan='5'>NO EXISTE INFORMES PARA MOSTRAR, PERO LA ORDEN DE TRABAJO SE ENCUENTRA ACTIVA</th></tr>";
        } else
        {
            echo $html;
        }
        exit();
    }
    
    
    
    public function modificarInformeForm()
    {
        $this->accion = "Configurar informe";
        $idAInforme = $_POST['idAInforme'];
        $this->idInforme = $_POST['idInforme'];
        $this->modeloArchivoInformeAnalisis = $this->lNegocioArchivoInformeAnalisis->buscar($idAInforme);
        require APP . 'Laboratorios/vistas/formularioDatosValidadosInformeVista.php';
    }

    public function modificarInformes()
    {
        $idCliente = $_POST['idCliente'];

        $modeloLaboratorios = $this->lNegocioArchivoInformeAnalisis->buscarInformesModificar($idCliente);
        $html = "";
        foreach ($modeloLaboratorios as $fila)
        {
             $botonVP = "<button class='bntGrid fas fa-search' onclick='fn_vistaPrevia(" . $fila['id_archivo_informe_analisis'] . ")'/>";
            $botonEditar = "<button class='bntGrid fas fa-file-signature' onclick='fn_modificar_informe(" . $fila['id_archivo_informe_analisis'] . ",".$fila['id_informe'].")'/>";
           
            $html .= "<tr data-tt-id='" . $fila['id_archivo_informe_analisis'] . "'>'"
                    . "<td><span class='folder'>" . $fila['nombre_informe'] . "</td>"
                    . "<td>" . $fila['estado_informe'] . "</td>"
                    . "<td>" . $fila['fecha_aprobado'] . "</td>"
                    . "<td>" . $fila['fecha_firma'] . "</td>"
                    . "<td>" . $fila['fecha_envio'] . "</td>"
                    . "<td>" . $botonEditar . "</td>"
                    . "<td>" . $botonVP . "</td>"
                    . "</tr>";
        }
        if (empty($html))
        {
            echo "<tr><th colspan='5'>NO EXISTE INFORMES PARA MOSTRAR, PERO LA ORDEN DE TRABAJO SE ENCUENTRA ACTIVA</th></tr>";
        } else
        {
            echo $html;
        }
        exit();
    }

    /**
     * Para consolidar informes
     */
    public function consolidarInformes()
    {
        $idCliente = $_POST['idCliente'];
        $festado = $_POST['festado'];
        $arrayParametros = array();
        if (!empty($idCliente))
        {
            $arrayParametros['fk_id_archivo_informe_analisis'] = $idCliente;
        }

        if (!empty($festado))
        {
            $arrayParametros['estado_informe'] = $festado;
        } else
        {
            $arrayParametros['estado_informe'] = 'ACTIVO';
        }

        $modeloLaboratorios = $this->lNegocioArchivoInformeAnalisis->buscarLista($arrayParametros);

        $html = "";
        $idInformeAnalisis = 0;
        foreach ($modeloLaboratorios as $fila)
        {
            if ($fila['nivel'] == 1)
            {
                $idInformeAnalisis = $fila['id_informe_analisis'];
            }

            $html .= "<tr data-tt-id='" . $fila['id_archivo_informe_analisis'] . "'>'"
                    . "<td><span class='folder'>" . $fila['nombre_informe'] . "</td>"
                    . "<td>" . $fila['fecha_aprobado'] . "</td>"
                    . "<td>" . $fila['estado_informe'] . "</td>"
                    . "<td>" . "<button class='bntGrid fas fa-edit' onclick='fn_observacion(" . $fila['id_archivo_informe_analisis'] . ")'/>" . "</td>"
                    . "<td>" . "<button class='bntGrid fas fa-plus-circle' onclick='fn_alcance(" . $fila['id_archivo_informe_analisis'] . "," . $fila['fk_id_archivo_informe_analisis'] . "," . $fila['id_informe_analisis'] . ")'/>" . "</td>"
                    . "<td>" . "<button class='bntGrid fas fa-paperclip' onclick='fn_adjuntar(" . $fila['id_archivo_informe_analisis'] . ")'/>" . "</td>"
                    . "<td>" . "<button class='bntGrid fas fa-minus' onclick='fn_anular(" . $fila['id_archivo_informe_analisis'] . ")'/>" . "</td>"
                    . "<td>" . "<button class='bntGrid fas fa-plus' onclick='fn_agregar_informe(" . $fila['fk_id_archivo_informe_analisis'] . "," . $fila['id_informe_analisis'] . ")'/>" . "</td>"
                    . "<td>" . "<button class='bntGrid fas fa-search' onclick='fn_vistaPrevia(" . $fila['id_archivo_informe_analisis'] . ")'/>" . "</td>"
                    . "<td>" . "<button class='bntGrid fas fa-cogs' onclick='fn_configurar(" . $fila['id_archivo_informe_analisis'] . ")'/>" . "</td>"
                    . "</tr>";
            $datos = $this->lNegocioArchivoInformeAnalisis->buscarIdPadre($fila['id_archivo_informe_analisis']);
            $html .= $this->tablaHtmlArbol($datos);
        }
        if (empty($html))
        {
            echo "<tr><th colspan='10'>NO EXISTE INFORMES PARA MOSTRAR, PERO LA ORDEN DE TRABAJO CONTINUA ACTIVA</th></tr>";
        } else
        {
            echo $html;
        }
        exit();
    }

    /**
     * Crea un árbol con los reportes del cliente
     * @param type $tabla
     * @param type $vista
     * @return string
     */
    public function tablaHtmlArbol($tabla, $vista = null)
    {
        $html = "";
        foreach ($tabla as $fila)
        {
            $idArchivoInformeAnalisis = $fila['id_archivo_informe_analisis'];
            // buscar los registro que tengan el id_padre
            $datos = $this->lNegocioArchivoInformeAnalisis->buscarIdPadre($idArchivoInformeAnalisis);
            if (count($datos) > 0)
            { // hay hijos
                $html .= "<tr data-tt-id='" . $fila['id_archivo_informe_analisis'] . "' data-tt-parent-id='" . $fila['fk_id_archivo_informe_analisis'] . "'"
                        . 'id="' . $fila['id_archivo_informe_analisis'] . '" class=""'
                        . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Laboratorios/ArchivoInformeAnalisis'"
                        . 'data-opcion="editar' . $vista . '"'
                        . 'data-destino="detalleItem">'
                        . "<td><span class='folder'>" . strip_tags($fila['nombre_informe']) . "</td>"
                        . "<td>" . $fila['fecha_aprobado'] . "</td>"
                        . "<td>" . $fila['estado_informe'] . "</td>"
                        . "<td>" . $fila['fecha_envio'] . "</td>"
                        . "<td>" . $fila['orden'] . "</td>"
                        . "<td></td>"
                        . "<td></td>"
                        . "</tr>";
                $html .= self::tablaHtmlArbol($datos, $vista);
            } else
            { //no hay hijos
                $vistaPrevia = "";
                $anular = "";
                if ($fila->id_recepcion_muestras !== '')
                {
                    
                }
                if ($fila['observacion_estado'] == 'ADJUNTO')
                {
                    $vistaPrevia = "<button class='bntGrid fas fa-search' onclick='fn_verArchivo(" . $fila['id_archivo_informe_analisis'] . ")'/>";
                    $anular = "<button class='icono' onclick='fn_eliminar(" . $fila['id_archivo_informe_analisis'] . ")'/>";
                }
                $nombre = strip_tags($fila['nombre_informe']);
                if ($fila->id_recepcion_muestras !== null)
                {
                    $html .= "<tr data-tt-id='" . $fila['id_archivo_informe_analisis'] . "' data-tt-parent-id='" . $fila['fk_id_archivo_informe_analisis'] . "'"
                            . 'id="' . $fila->id_recepcion_muestras . '" class="item"'
                            . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Laboratorios/Laboratorios'"
                            . 'data-opcion="verDatosMuestra"'
                            . 'data-destino="detalleItem" style="cursor: pointer;">';
                    $html .= "<td style='cursor: pointer;' title='" . $nombre . "'><span class='file' ><b>" . substr($nombre, 0, 40) . "...</b></td>";
                } else
                {
                    $html .= "<tr data-tt-id='" . $fila['id_archivo_informe_analisis'] . "' data-tt-parent-id='" . $fila['fk_id_archivo_informe_analisis'] . "'"
                            . 'id="' . $fila['id_archivo_informe_analisis'] . '" class=""'
                            . "data-rutaAplicacion='" . URL_MVC_FOLDER . "Laboratorios/ArchivoInformeAnalisis'"
                            . 'data-opcion="editar' . $vista . '"'
                            . 'data-destino="detalleItem">';
                    $html .= "<td><span class='file'>" . substr($nombre, 0, 40) . "...</td>";
                }
                $html .= "<td>" . $fila['fecha_aprobado'] . "</td>"
                        . "<td>" . $fila['estado_informe'] . "</td>"
                        . "<td>" . $fila['fecha_envio'] . "</td>"
                        . "<td>" . $fila['orden'] . "</td>"
                        . "<td></td>"
                        . "<td class='borrar'>" . $anular . "</td>"
                        . "<td></td>"
                        . "<td>" . $vistaPrevia . "</td>"
                        . "<td></td>"
                        . "</tr>";
            }
        }
        return $html;
    }

    /**
     * Construye el código HTML para desplegar la lista de - ArchivoInformeAnalisis
     */
    public function tablaHtmlArchivoInformeAnalisis($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_archivo_informe_analisis'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'Laboratorios/ArchivoInformeAnalisis"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_archivo_informe_analisis'] . '</b></td>
<td>'
                . $fila['id_recepcion_muestras'] . '</td>
<td>' . $fila['id_informe']
                . '</td>
<td>' . $fila['id_informe_analisis'] . '</td>
</tr>');
        }
    }

    /**
     * Enviar notificación de informe de análisis
     */
    public function enviar()
    {
        $datosArchivo = $this->lNegocioArchivoInformeAnalisis->buscar($_POST['id_archivo_informe_analisis']);
        $urlArchivo = URL_MVC_MODULO . "Laboratorios/archivos/informes/firmados/" . $datosArchivo->getRutaArchivo() . "_firmado.pdf";
        $_POST['identificador'] = parent::usuarioActivo();
        $_POST['urlArchivo'] = $urlArchivo;
        $this->lNegocioArchivoInformeAnalisis->enviar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

}
