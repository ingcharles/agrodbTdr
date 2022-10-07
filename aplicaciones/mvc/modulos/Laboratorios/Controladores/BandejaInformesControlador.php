<?php

/**
 * Controlador Solicitudes
 *
 * Este archivo controla la logica del negocio del modelo:  SolicitudesModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     SolicitudesControlador
 * @package Laboratorios
 * @subpackage Controladores
 */

namespace Agrodb\Laboratorios\Controladores;

use Agrodb\Core\Constantes;
use Agrodb\Core\Javabridge;
use Agrodb\Core\Mensajes;
use Agrodb\Laboratorios\Modelos\ArchivoInformeAnalisisLogicaNegocio;
use Agrodb\Laboratorios\Modelos\RecepcionMuestrasLogicaNegocio;
use Agrodb\Laboratorios\Modelos\LaboratoriosProvinciaLogicaNegocio;

class BandejaInformesControlador extends BaseControlador {

    private $urlPdf;
    private $lNegocioArchivoInformeAnalisis = null;

    /*
     * Constructor
     */

    function __construct() {
        error_reporting(0);
        parent::__construct();
        $this->lNegocioArchivoInformeAnalisis = new ArchivoInformeAnalisisLogicaNegocio();


        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index() {
        
    }

    /**
     * Ejecuta un método en java
     * public String generarOrdenTrabajo(String idOrden, String plantilla,String esquema) {
     * @param type $idOrdenTrabajo
     */
    public function descargarOt($idOrdenTrabajo) {
        $java = new Javabridge($this->identificador, Constantes::tipo_usuario()->INTERNO);
        //activarFirmaElectronica método ejecutado en java
        $url_ot = $java->exec()->generarOrdenTrabajo($idOrdenTrabajo, JAVA_URL_PLANTILLA_OT, Constantes::ESQUEMA_LABORATORIOS);
        $ot_correcta = strrpos($url_ot, ".pdf");
        if ($ot_correcta === false) {
            echo "<BR>" . Constantes::ERROR_CREAR_ORDEN_TRABAJO;
        } else {
            $this->urlPdf .= URL_MVC_MODULO . "Laboratorios/archivos/" . $url_ot;
            require APP . 'Laboratorios/vistas/visorPDF.php';
        }

        if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
            \ChromePhp::log($this->urlPdf);
            echo "<BR>" . $this->urlPdf;
            echo "<BR>" . $url_ot;
        } else {
            // $this->mensajeActivacion = MENSAJE_ACTIVACION;
        }
    }

    /**
     * Genera las etiquetas en formato PDF
     * @param type $idOrdenTrabajo
     */
    public function generarEtiquetas($idOrdenTrabajo) {
        error_reporting(0);

        $java = new Javabridge($this->identificador, Constantes::tipo_usuario()->INTERNO);
        //activarFirmaElectronica método ejecutado en java
        $url_ot = $java->exec()->generarEtiquetas($idOrdenTrabajo, JAVA_URL_PLANTILLA_ETIQUETAS, Constantes::ESQUEMA_LABORATORIOS);
        $ot_correcta = strrpos($url_ot, ".pdf");
        if ($ot_correcta === false) {
            echo "<BR>" . Constantes::ERROR_CREAR_ETIQUETAS;
        } else {
            echo $this->urlPdf .= URL_MVC_MODULO . "Laboratorios/archivos/" . $url_ot;
            exit();
        }

        
    }

    /**
     * Genera etiquetas
     * @param type $idOrdenTrabajo
     */
    public function generarEtiquetasCvs($idOrdenTrabajo) {
        error_reporting(0);
        $etiquetas = new RecepcionMuestrasLogicaNegocio();
        $where = array("id_orden_trabajo" => $idOrdenTrabajo);
        $resultado = $etiquetas->buscarLista($where);
        $nombreArchivo = "etiquetas" . $idOrdenTrabajo . ".csv";
        $archvioCvs = JAVA_URL_REPORTES . "etiquetas/" . $nombreArchivo;
        $contenidoCsv = "";

        foreach ($resultado as $fila) {
            $contenidoCsv .= $fila->etiqueta_imprimir;
        }

        //Generamos el csv de con todos los datos  
        if (!$handle = fopen($archvioCvs, "w")) {
            echo "No se puede abrir o crear el archivo";
            exit;
        }
        if (fwrite($handle, utf8_decode($contenidoCsv)) === FALSE) {
            echo "No se puede escribir en el archivo";
            exit;
        }

        fclose($handle);
        echo URL_DIR_IDTM . '/etiquetas/' . $nombreArchivo;
        exit();
    }

    /**
     * Ejecuta un método en java
     * generarInforme("5", ruta, "g_laboratorios");
     * @param type $idInforme
     */
    public function descargarInformes() {
        $idAInforme = $_POST['idAInforme']; //id del informe tabla: archivo_informe_analisis
        //Antes de desplegar a la vista previa, los informes del cliente seleccionado actualizamos los estados de los campo de acuerdo al formato seleccionado
      $this->lNegocioArchivoInformeAnalisis->configurarCampoImprimir($idAInforme);
      
      //Verificamos si el informe debe ir con el formato de acreditación
      $resultado = $this->lNegocioArchivoInformeAnalisis->existeAcreditacion($idAInforme);
      
     $contarAcreditado=0;
         foreach ($resultado as $fila) 
        {
            if($fila->acreditado=='SI'){
                if($fila->condiccion=='PAQUETE'){
                    $contarAcreditado="MSG";
                }
            }
        }  
      
      
        $numeroAcreditadas = $this->lNegocioArchivoInformeAnalisis->acreditacion($idAInforme);
        $si = 0;
        $no = 0;
        foreach ($numeroAcreditadas as $fila) {
            if ($fila->acreditado == "SI") {
                $si = $fila->numero;
            } else {
                $no = $fila->numero;
            }
        }
        //En caso de laboratorios con paquetes solo llevan con mensaje sin sello 


        $tipoPlantilla = JAVA_URL_PLANTILLA_INFORME;
        if ($si > 0) {
            //tiene acreditación
            if ($contarAcreditado=='MSG') {
                $r = -1;
            } else {
                $r = $si - $no;
            }
            if ($r > 0) {
                //formato con sello y mensaje
                $tipoPlantilla = JAVA_URL_PLANTILLA_INFORME_ACREDITADO_SMSG;
            } else {
                //formaro unicamente con sello
                $tipoPlantilla = JAVA_URL_PLANTILLA_INFORME_ACREDITADO_MSG;
            }
        } else if ($no == 0) {
            throw new \Exception(Constantes::ERROR_INFORME_VACIO);
        }

        $java = new Javabridge($this->identificador, Constantes::tipo_usuario()->INTERNO);

        //activarFirmaElectronica método ejecutado en java
        $url_ot = $java->exec()->generarInforme($idAInforme, $tipoPlantilla, Constantes::ESQUEMA_LABORATORIOS);
        $ot_correcta = strrpos($url_ot, ".pdf");
        if ($ot_correcta === false) {
            echo "<BR>" . Constantes::ERROR_CREAR_INFORME;
        } else {
            $this->urlPdf .= URL_MVC_MODULO . "Laboratorios/archivos/" . $url_ot;
            require APP . 'Laboratorios/vistas/visorPDF.php';
        }

        if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
            \ChromePhp::log($this->urlPdf);
            echo "<BR>" . $this->urlPdf;
            echo "<BR>" . $url_ot;
        }
    }

    /**
     * Firmar el informe
     * @param type $idAInforme
     */
    public function legalizarInforme() {
        $idAInforme = $_POST['id_archivo_informe_analisis'];
        $modeloAInforme = $this->lNegocioArchivoInformeAnalisis->buscar($idAInforme);
        $java = new Javabridge($this->identificador, Constantes::tipo_usuario()->INTERNO);
        //activarFirmaElectronica método ejecutado en java
        $archivoPDF = $modeloAInforme->getRutaArchivo() . ".pdf";

        $url_ot = $java->exec()->firmarInforme($this->identificador, $archivoPDF, Constantes::RAZON_FIRMA, Constantes::ESQUEMA_LABORATORIOS);
        $ot_correcta = strrpos($url_ot, ".pdf");
        if ($ot_correcta === false) {
            Mensajes::fallo(Constantes::ERROR_FIRMA_INFORME);
            throw new \Exception('Clase Controlador: BandejaInformesControlador. Método: legalizarInforme' . Constantes::ERROR_FIRMA_INFORME);
        } else {
            $datos = array("id_archivo_informe_analisis" => $idAInforme, "firmado" => "SI", "fecha_firma" => "now()", "estado_informe" => "FIRMADO");
            $this->lNegocioArchivoInformeAnalisis->guardar($datos);
            $this->urlPdf .= URL_MVC_MODULO . "Laboratorios/archivos/" . $url_ot;
            require APP . 'Laboratorios/vistas/visorPDF.php';
        }

        
    }

    /**
     * Descarga los informe firmados
     * @param type $idOrdenTrabajo
     */
    public function descargarFirmados() {
        $datosArchivo = $this->lNegocioArchivoInformeAnalisis->buscar($_POST['idAInforme']);
        $this->urlPdf .= URL_MVC_MODULO . "Laboratorios/archivos/informes/firmados/" . $datosArchivo->getRutaArchivo() . "_firmado.pdf";
        require APP . 'Laboratorios/vistas/visorPDF.php';
    }

    public function baseDatos() {
        $where = "id_laboratorios_provincia=" . $_POST['id_laboratorios_provincia'];


        $laboratorio = new LaboratoriosProvinciaLogicaNegocio();
        $datos = $laboratorio->buscar($_POST['id_laboratorios_provincia']);

        $java = new Javabridge($this->identificador, Constantes::tipo_usuario()->INTERNO);
        $url_ot = $java->exec()->generarBaseDatos($datos->getIdLaboratorio(), $where,Constantes::tipo_reporte_xls()->BASEDATOS);
        $ot_correcta = strrpos($url_ot, ".xlsx");
        if ($ot_correcta === false) {
            echo "<BR>" . Constantes::ERROR_CREAR_BD;
        } else {
            $this->urlPdf .= URL_MVC_MODULO . "Laboratorios/archivos/" . $url_ot;
            require APP . 'Laboratorios/vistas/visorPDF.php';
        }

        if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
            \ChromePhp::log($this->urlPdf);
            echo "<BR>urfPDF:  " . $this->urlPdf;
            echo "<BR>Mensaje de java:  " . $url_ot;
        } else {
            
        }
    }
    
    public function reactivos($idReactivo) {
       
         $java = new Javabridge($this->identificador, Constantes::tipo_usuario()->INTERNO);
        $url_ot = $java->exec()->generarSolicitudReactivos($idReactivo);
        $ot_correcta = strrpos($url_ot, ".xlsx");
        if ($ot_correcta === false) {
            echo "<BR>" . Constantes::ERROR_CREAR_BD;
        } else {
            $this->urlPdf .= URL_MVC_MODULO . "Laboratorios/archivos/" . $url_ot;
            require APP . 'Laboratorios/vistas/visorPDF.php';
        }

        if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
            \ChromePhp::log($this->urlPdf);
            echo "<BR>urfPDF:  " . $this->urlPdf;
            echo "<BR>Mensaje de java:  " . $url_ot;
        } else {
            
        }
    }

    /**
     * generarProforma("142", ruta, "g_laboratorios");
     * @param type $idProforma
     */
    public function descargarProforma($idProforma) {
        error_reporting(0);
        $java = new Javabridge($this->identificador, Constantes::tipo_usuario()->INTERNO);
        $url_ot = $java->exec()->generarProforma($idProforma, JAVA_URL_PLANTILLA_PROFORMA, Constantes::ESQUEMA_LABORATORIOS);
        $ot_correcta = strrpos($url_ot, ".pdf");
        if ($ot_correcta === false) {
            echo "<BR>" . Constantes::ERROR_GENERAR_PROFORMA;
        } else {
            $this->urlPdf = URL_MVC_MODULO . "Laboratorios/archivos/" . $url_ot;
            // require APP . 'Laboratorios/vistas/visorPDF.php'; 
            echo $this->urlPdf;
            exit();
        }

        if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
            \ChromePhp::log($this->urlPdf);
            echo "<BR>urfPDF:  " . $this->urlPdf;
            echo "<BR>Mensaje de java:  " . $url_ot;
        } else {
            
        }
    }

}
