<?php

setLocale(LC_CTYPE, 'ES_ec.UTF-8');
/**
 * Configuración
 *
 * @see http://php.net/manual/en/function.define.php
 */
/**
 * Configurar para poder ver los errores en ambiente de desarrollo: Error reporting
 * Cambiar a 'production', cuando se instale en producción
 */
define('ENVIRONMENT', 'development');

if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

/**
 *
 * URL_MVC_FOLDER:
 * Indica el nombre la carpeta que contiene los módulos con arquitectura mvc
 *
 * URL_PROTOCOL:
 * Esto define la parte de protocolo de la URL, cambiar si se tiene un sitio HTTPS seguro. 
 *
 * URL_DOMAIN:
 * El dominio. https://guia.agrocalidad.gob.ec 
 * Si su proyecto se ejecuta con http y https, cambiar
 *
 * URL_SUB_FOLDER:
 * Subcarpeta. Si no se utiliza una subcarpeta (entonces esto será solo "/").
 *
 * URL:
 * La URL final autodetectada (compilación a través de los segmentos anteriores). Si no se quiere usar autodetección,
 * se debe remplazar es línea con una URL completa (y una subcarpeta) y una barra inclinada.
 * 
 * URL_MVC_MODULO:
 * Url que apunta a la carpeta que contiene los módulos con arquitectura mvc
 * 
 * URL_GUIA:
 * url que apunta a la carpeta de aplicaciones sin mvc que contienen recursos js, css  y img del sistema GUIA.
 */

if(PHP_SAPI!=='cli'){
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
}else {
    $host = 'localhost';
    $scriptName = '/agrodbPrueba/aplicaciones/mvc/index.php';
}

define('URL_MVC_FOLDER', 'mvc/'); //Utilizado en las listas  para el enlace editar
define('URL_PROTOCOL', 'http://'); 
define('URL_DOMAIN', $host);
define('URL_SUB_FOLDER', $scriptName);
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER . '/');
define('URL_MVC_MODULO', URL . 'modulos/');
define('URL_GUIA', str_replace('/mvc', '', $scriptName));
define('URL_GUIA_PROYECTO', str_replace('/aplicaciones', '', URL_PROTOCOL . URL_DOMAIN. URL_GUIA));
define('URL_RESOURCE', URL . 'resource/');
define('URL_DIR_FILES', 'aplicaciones/mvc/modulos/Laboratorios/anexos'); //anexos de la solicitud
define('URL_DIR_IDTM', 'aplicaciones/mvc/modulos/Laboratorios/archivos');
define('URL_DIR_REA_EXCEL', 'aplicaciones/mvc/modulos/Reactivos/archivos/excelBodega');
define('URL_DIR_REA_CERTIFICADOS', 'aplicaciones/mvc/modulos/Reactivos/archivos/certificados');
define('URL_DIR_LAB_FIRMAS', 'aplicaciones/mvc/modulos/Laboratorios/archivos/firmas');
define('URL_ADJUNTOS_NO_IDONEAS', 'aplicaciones/mvc/resource/adjuntos/laboratorios'); //Para subir archivos de muestras no idoneas
define('URL_DIR_NO_IDONEAS',  URL_RESOURCE . 'adjuntos/laboratorios/');  //Para descargar los archivos de muestras no idoneas por URL
define('URL_DIR_LAB_FA', 'aplicaciones/mvc/modulos/Laboratorios/archivos/muestrasFA');  //Archivos muestreo de fiebre aftosa
define('URL_DIR_LAB_AD', 'aplicaciones/mvc/modulos/Laboratorios/archivos/informes/firmados/');  //Archivos Adjuntos a los informes
define('URL_IMG', '../general/img/');
define('URL_GENER', URL_PROTOCOL . URL_DOMAIN . URL_GUIA . '/general' . '/');

/**
 * Configuración de la base de datos
 * *********Falta implementar la encriptacion de las credenciales
 */
//$algorithm = MCRYPT_BLOWFISH;
//$key = '1c1d7f192e483bb9cb6cdd0ca6bc9282';
//$mode = MCRYPT_MODE_CBC;
//$iv = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, $mode),
//    MCRYPT_DEV_URANDOM);
//$encrypted_data = base64_decode('474MoQm+G+k');
//$clave = mcrypt_decrypt($algorithm, $key, $encrypted_data, $mode, $iv);

define('DB_DRIVER', 'pdo_pgsql');
define('DB_PORT', '9999');
define('DB_HOST', '192.168.15.135');
define('DB_NAME', 'agrocalidadprueba');
define('DB_USER', 'postgres');
define('DB_PASS', 'A9r@07c@/i.');
define('DB_CHARSET', 'utf8');

/**
 * Configurcion Javabridge
 */
define('URL_JAVA_INI', 'http://localhost:8081/JavaBridge/java/Java.inc');
define("JAVA_HOSTS", "localhost:8081");
define("JAVA_SERVLET", "/AgrodbJava/Laboratorios");
/**
 * Rutas del archivo para los reportes
 */
$rutaBaseReportes = "/var/www/html/agrodbPrueba/aplicaciones/mvc/";
define("JAVA_URL_PLANTILLA_OT", $rutaBaseReportes . "resource/reportes/ordenBasica.jrxml");
define("JAVA_URL_PLANTILLA_INFORME", $rutaBaseReportes . "resource/reportes/informeBasico.jrxml");
define("JAVA_URL_PLANTILLA_ETIQUETAS", $rutaBaseReportes . "resource/reportes/etiquetas.jrxml");
define("JAVA_URL_LOGO", $rutaBaseReportes . "resource/reportes/logo.jpg");
define("JAVA_URL_REPORTES", $rutaBaseReportes . "modulos/Laboratorios/archivos/");
define("JAVA_URL_FIRMAS", $rutaBaseReportes . "modulos/Laboratorios/archivos/firmas/");
define("JAVA_URL_PLANTILLA_PROFORMA", $rutaBaseReportes . "resource/reportes/proforma.jasper");
define("JAVA_URL_PLANTILLA_INFORME_ACREDITADO_SMSG", $rutaBaseReportes . "resource/reportes/informe_acreditado_logo.jrxml");
define("JAVA_URL_PLANTILLA_INFORME_ACREDITADO_MSG", $rutaBaseReportes . "resource/reportes/informe_acreditado_msg.jrxml");

/**
 * Ruta general Imagenes reportes
 */

 define("RUTA_IMG_GENE", $rutaBaseReportes. URL_IMG);

/**
 * Formarto de fecha
 */
define('DATE_FORMAT', 'Y-m-d');

/**
 * configuración certificado
 *
 */
define("CERT_LAB_URL", "aplicaciones/mvc/modulos/CertificadoLaboral/archivos/");
define("CERT_LAB_URL_TCPDF", $rutaBaseReportes . "modulos/CertificadoLaboral/archivos/");
define("AGROCALIDAD_SIG","AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO - AGROCALIDAD");
define("CERT_LAB_URL_IMG", $rutaBaseReportes . "modulos/CertificadoLaboral/vistas/img/");

/**
 * Configuración Bitácora
 */
define("SEG_DOC_BIT_URL", "aplicaciones/mvc/modulos/SeguimientoDocumental/archivos/");
define("SEG_DOC_BIT_URL_TCPDF", $rutaBaseReportes . "modulos/SeguimientoDocumental/archivos/");
define("SEG_DOC_BIT_URL_IMG", $rutaBaseReportes . "modulos/SeguimientoDocumental/vistas/img/");

/**
 * configuración inspeccion de musaceas
 */
define("INSP_MUS_URL", "aplicaciones/mvc/modulos/InspeccionMusaceas/archivos/");

/**
 * configuración certificado de movilización sueros
 *
 */
define("CERT_MOV_SUERO", "aplicaciones/mvc/modulos/MovilizacionSueros/archivos/");
define("CERT_MOV_SUERO_TCPDF", $rutaBaseReportes . "modulos/MovilizacionSueros/archivos/");
define("CERT_MOV_SUERO_IMG", URL_IMG . "movilizacionSueros/");

/**
 * configuración inspeccion ante y post mortem en centros de faenamiento
 *
 */
define("INSP_FORM_AP_CF", "aplicaciones/mvc/modulos/InspeccionAntePostMortemCF/archivos/");
define("INSP_AP_TCPDF", $rutaBaseReportes . "modulos/InspeccionAntePostMortemCF/archivos/");
define("INSP_FORM_AP_CF_IMG", URL_IMG . "inspeccionAntePostMortemCF/");

/**
 * Configuración Certificado Movilización Vegetal
 */
define("MOV_VEG_CERT_URL", "aplicaciones/mvc/modulos/MovilizacionVegetal/archivos/");
define("MOV_VEG_CERT_URL_TCPDF", $rutaBaseReportes . "modulos/MovilizacionVegetal/archivos/");
define("MOV_VEG_CERT_URL_IMG", $rutaBaseReportes . "modulos/MovilizacionVegetal/vistas/img/");

/**
 * Configuración Orden de Pago Financiero
 */
define("FIN_ORD_PAG_URL", "aplicaciones/financiero/documentos/ordenPago/");
define("FIN_ORD_PAG_URL_ALL", $rutaBaseReportes . "../financiero/documentos/ordenPago/");

/**
 * Configuración Registro de entrega de producto.
 */
define("ENT_PROD_CERT_URL", "aplicaciones/mvc/modulos/RegistroEntregaProductos/archivos/");
define("ENT_PROD_CERT_URL_TCPDF", $rutaBaseReportes . "modulos/RegistroEntregaProductos/archivos/");
define("ENT_PROD_CERT_FIRM_URL", "aplicaciones/mvc/modulos/RegistroEntregaProductos/archivos/firmados/");

/**
 * Configuración Importación de fertilizantes.
 */
define("IMP_FERT_DOC_ADJ", "aplicaciones/mvc/modulos/ImportacionFertilizantes/archivos/");
define("IMP_FERT_RUT_COMPL", $rutaBaseReportes . "modulos/ImportacionFertilizantes/archivos/");

/**
 * configuración historias clinicas
 *
 */
define("HIST_CLI_URL", "aplicaciones/mvc/modulos/HistoriasClinicas/archivos/");
define("HIST_CLI_URL_TCPDF", $rutaBaseReportes . "modulos/HistoriasClinicas/archivos/");
define("HIST_CLI_URL_IMG", $rutaBaseReportes . "modulos/HistoriasClinicas/vistas/img/");

/**
 * Configuración Certificación BPA
 */
define("CERT_BPA_URL", "aplicaciones/mvc/modulos/CertificacionBPA/archivos/");
define("CERT_BPA_URL_CERT_EQ", "aplicaciones/mvc/modulos/CertificacionBPA/archivos/equivalentes/");
define("CERT_BPA_URL_ANEX_NAC", "aplicaciones/mvc/modulos/CertificacionBPA/archivos/anexos/");
define("CERT_BPA_URL_PLAC_TEC", "aplicaciones/mvc/modulos/CertificacionBPA/archivos/plantillas/");
define("CERT_BPA_URL_CHECK_TEC", "aplicaciones/mvc/modulos/CertificacionBPA/archivos/checklists/");
define("CERT_BPA_URL_PLAC_OP", "aplicaciones/mvc/modulos/CertificacionBPA/archivos/planes/");
define("CERT_BPA_URL_CERT", $rutaBaseReportes . "modulos/CertificacionBPA/archivos/certificados/");

/**
 * Configuración Administración APP Externos.
 */
define("ADM_APP_URL", "aplicaciones/mvc/modulos/AplicacionMovilExternos/");
define("ADM_APP_ARC_URL", "aplicaciones/mvc/modulos/AplicacionMovilExternos/archivos/");
define("ADM_APP_ARC_NOTI_URL", "aplicaciones/mvc/modulos/AplicacionMovilExternos/archivos/noticias");
define("ADM_APP_ARC_EVEN_URL", "aplicaciones/mvc/modulos/AplicacionMovilExternos/archivos/eventos");

/**
 * Configuración Certificado Fitosanitario
 */
define("CERT_FITO_URL", "aplicaciones/mvc/modulos/CertificadoFitosanitario/archivos/");
define("CERT_FIT_DOC_ADJ", "aplicaciones/mvc/modulos/CertificadoFitosanitario/archivos/anexos/");
define("CERT_FITO_URL_INSP", "aplicaciones/mvc/modulos/CertificadoFitosanitario/archivos/inspecciones/");
define("CERT_FITO_CERT_URL_TCPDF", $rutaBaseReportes . "modulos/CertificadoFitosanitario/archivos/");

/**
 * configuración jurídico
 *
 */
define("PROC_JURI_URL", "aplicaciones/mvc/modulos/ProcesosAdministrativosJuridico/archivos/");
define("PROC_JURI_URL_RAIZ", $rutaBaseReportes . "modulos/ProcesosAdministrativosJuridico/archivos/");

/**
 * configuración emision de origen
 *
 */
define("EMI_CERT_ORIG_URL", "aplicaciones/mvc/modulos/EmisionCertificacionOrigen/archivos/");

/**
 * Configuración Fitosanitario.
 */
define("CON_FIT_DOC_ADJ", "aplicaciones/mvc/modulos/ConfiguracionCertificadoFitosanitarioHub/archivos/");
define("CON_FIT_RUT_COMPL", $rutaBaseReportes . "modulos/ConfiguracionCertificadoFitosanitarioHub/archivos/");

/**
 * Configuración Proveedores Exterior
 */
define("PROV_EXTE_URL", "aplicaciones/mvc/modulos/ProveedoresExterior/archivos/");
define("PROV_EXTE_DOC_ADJ", "aplicaciones/mvc/modulos/ProveedoresExterior/archivos/anexos/");
define("PROV_EXTE_CERT_URL_TCPDF", $rutaBaseReportes . "modulos/ProveedoresExterior/archivos/");
define("PROV_EXTE_CERT", "aplicaciones/mvc/modulos/ProveedoresExterior/archivos/");
define("PROV_EXTE_DOC_INSP", "aplicaciones/mvc/modulos/ProveedoresExterior/archivos/documentosRevisionDocumental/");

/**
 * Configuración Dossier Pecuario
 */
define("DOSS_PEC_URL", "aplicaciones/mvc/modulos/DossierPecuario/archivos/");
define("DOSS_PEC_URL_CERT", $rutaBaseReportes . "modulos/DossierPecuario/archivos/");
define("DOSS_PEC_URL_IMG", $rutaBaseReportes . "modulos/DossierPecuario/vistas/img/");

/**
 * configuración registro control documentos SGC
 *
 */
define("REG_CTR_DOC_SGC", "aplicaciones/mvc/modulos/RegistroControlDocumentos/archivos/");
define("REG_CTR_DOC_SGC_RAIZ", $rutaBaseReportes . "modulos/RegistroControlDocumentos/archivos/");

/**
 * Private key para token agro servicios
 */
define("PRIVATE_KEY_AGROSERVICIOS", "passw0rd_pruebas");

/**
 * Ruta key.pem para token agro servicios
 */
define("RUTA_KEY_AGROSERVICIOS", "modulos/AplicacionMovilInternos/vistas/llave/key.pem");

/**
 * Ruta key.pub para token agro servicios
 */
define("RUTA_PUBLIC_KEY_AGROSERVICIOS", "modulos/AplicacionMovilInternos/vistas/llave/key.pub");

/**
 * Configuración Pasaporte Equino
 */
define("PAS_EQUI_URL", "aplicaciones/mvc/modulos/PasaporteEquino/archivos/");
define("PAS_EQUI_URL_CERT", $rutaBaseReportes . "modulos/PasaporteEquino/archivos/");
define("PAS_EQUI_URL_IMG", $rutaBaseReportes . "modulos/PasaporteEquino/archivos/");
define("PAS_EQUI_URL_IMG_DEF", "aplicaciones/general/img/defecto.jpg");
define("PAS_EQUI_URL_SELL_IMG", $rutaBaseReportes . "modulos/PasaporteEquino/vistas/img/logoSeguridadCSM.gif");

/**
 * Configuración rutas modificacion productos RIA
 */
define("MODI_PROD_RIA_URL", "aplicaciones/mvc/modulos/ModificacionProductoRia/archivos/");
define("MODI_PROD_RIA_URL_REPORTE", $rutaBaseReportes . "modulos/ModificacionProductoRia/archivos/");