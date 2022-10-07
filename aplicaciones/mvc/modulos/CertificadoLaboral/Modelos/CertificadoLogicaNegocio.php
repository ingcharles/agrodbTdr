<?php
/**
 * Lógica del negocio de CertificadoModelo
 *
 * Este archivo se complementa con el archivo CertificadoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-02-12
 * @uses    CertificadoLogicaNegocio
 * @package CertificadoLaboral
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoLaboral\Modelos;

use TCPDF;
use Agrodb\Core\Comun;
use Agrodb\Core\Constantes;
class CertificadoLogicaNegocio implements IModelo
{

    private $modeloCertificado = null;

    private $esBorrador = false;

    private $pdf = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloCertificado = new CertificadoModelo();
        $this->pdf = new \TCPDF();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new CertificadoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdCertificado() != null && $tablaModelo->getIdCertificado() > 0) {
            return $this->modeloCertificado->actualizar($datosBd, $tablaModelo->getIdCertificado());
        } else {
            unset($datosBd["id_certificado"]);
            return $this->modeloCertificado->guardar($datosBd);
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
        $this->modeloCertificado->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return certificadoModelo
     */
    public function buscar($id)
    {
        return $this->modeloCertificado->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloCertificado->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloCertificado->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarCertificado()
    {
        $consulta = "SELECT * FROM " . $this->modeloCertificado->getEsquema() . ". certificado";
        return $this->modeloCertificado->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarTipoCertificado($arrayParametros)
    {
        $busqueda = '';
        if (array_key_exists('id_formato', $arrayParametros)) {
            $busqueda = "and id_formato = " . $arrayParametros['id_formato'] . "";
        }

        $consulta = "SELECT
                    	id_formato,
                        tipo,
                        descripcion,
                        titulo,
                        texto_certificado
                    FROM
                    	g_certificados_uath.formato
                    WHERE
                    	estado='" . $arrayParametros['estado'] . "' " . $busqueda . "
                    ORDER BY
                        1";
        return $this->modeloCertificado->ejecutarSqlNativo($consulta);
    }
    /**
     * crear documento pdf .
     */

    // ************************** Datos firma electrónica ******************************************
    public function obtenerDatosCertificado($identificador)
    {
        $consulta = $this->obtenerFirmaElectronica($identificador);
        
        $id = rtrim($identificador);
        $scr = crc32($id);
        $key = hash('sha256', $scr);
        $claveCifrada = $consulta->current()->clave;
        $comun = new Comun();
        $password = $comun->desencriptarClave($claveCifrada, $key);
        $certificate = 'file://' . $consulta->current()->ruta_certificado;
        
        $info = array(
            'Name' => $consulta->current()->nombre_firma,
            'Location' => $consulta->current()->ubicacion,
            'Reason' => $consulta->current()->razon,
            'ContactInfo' => $consulta->current()->info_contacto
        );
        $datos = array();
        $datos['rutaCertificado'] = $certificate;
        $datos['info'] = $info;
        $datos['password'] = $password;
        return $datos;
    }
    

    // ************************** Datos del funcionario ******************************************
    public function obtenerDatosSolicitante($identificador)
    {
        $consulta = "SELECT
                    	fecha_inicial,
                        direcc as direccion,
                        puesto,
                        remune as remuneracion
                    FROM
                    	g_certificados_uath.devolver_fecha_inicial('" . $identificador . "')";
        return $this->modeloCertificado->ejecutarSqlNativo($consulta);
    }

    // ************************** Datos de la firma electrónica ******************************************
    public function obtenerFirmaElectronica($identificador)
    {
        $consulta = "SELECT
                        id_firma_electronica,
                    	nombre_firma,
                        ubicacion,
                        razon,
                        info_contacto,
                        ruta_certificado,
                        clave
                    FROM
                    	g_certificados_uath.firma_electronica
                    WHERE   
                        identificador = '" . $identificador . "' and 
                        estado = 'activo';";

        return $this->modeloCertificado->ejecutarSqlNativo($consulta);
    }

    // ***************************Generar certificado laboral****************************************
    public function generarCertificado($arrayDatos)
    {
        ob_start();
        // ************************************************** INICIO ***********************************************************

        $margen_superior = 40;
        $margen_inferior = 15;
        $margen_izquierdo = 20;
        $margen_derecho = 17;

        // header('Content-type: application/pdf');

        $doc = new PDF('P', 'mm', 'A4', true, 'UTF-8');

        $tipoLetra = 'times';
        // ******************************************* FIRMA *************************************************************************
        if ($arrayDatos['firma'] === 'Si') {
            $datosCertificado = $this->obtenerDatosCertificado($arrayDatos['identificadorRH']);
            $doc->setSignature($datosCertificado['rutaCertificado'], $datosCertificado['rutaCertificado'], $datosCertificado['password'], '', 1, $datosCertificado['info']);
 }
        $doc->SetLineWidth(0.1);
        $doc->setCellHeightRatio(1.5);
        $doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
        $doc->SetAutoPageBreak(TRUE, $margen_inferior);
        $doc->SetFont($tipoLetra, '', 9);
        $doc->AddPage();

        $fechaSolicitud = date('Y-m-d');
        // ***********************************QR EN FIRMA ELECTRONICA**********************

        $rutaQRF = '
        Location: Quito-Ecuador
        Reason: CERTIFICADOR LABORAL
        ContactInfo: http://www.agrocalidad.gob.ec
        FIRMADO POR:' . $arrayDatos['nombreRH'] . '
        FECHA FIRMADO:' . date('d-m-Y');
        // ***********************************QR EN FIRMA ELECTRONICA**********************
        $rutaQRG = '        
        Institución: AGROCALIDAD
        Recursos Humanos: ' . $arrayDatos['nombreRH'] . '
        Certifica a: ' . $_SESSION['datosUsuario'] . '
        Fecha de emisión: ' . date('d-m-Y');

        // *********************************************************************************
        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(
                0,
                0,
                0
            ),
            'bgcolor' => false,
            'module_width' => 1,
            'module_height' => 1
        );

        // ****************************** INICIA *************************************
        $doc->SetTextColor();
        $doc->SetFont('times', 'B', 13);
        $y = $doc->GetY();
        $doc->writeHTMLCell(0, 0, $margen_izquierdo, $y + 10, '<i><u>' . $arrayDatos['titulo'] . '</u></i>', '', 1, 0, true, 'C', true);
        $doc->SetFont('times', '', 12);
        $doc->Cell(0, 35, $doc->fecha($arrayDatos['ciudad'], 1, $fechaSolicitud), 0, 1, 'R');
        $numero = new \NumberToWords\NumberToWords();
        $valor = $numero->getNumberTransformer('es')->toWords($arrayDatos['remuneracion']);
        $remune = number_format($arrayDatos['remuneracion'], 2, ',', '');
        $fechaCert = $doc->fecha($arrayDatos['ciudad'], 2, $arrayDatos['fecha_inicial']);

        if (isset($arrayDatos['genero'])) {
        	if($arrayDatos['genero'] == 'Masculino'){
        		$encabezado = 'El suscrito, Director';
        	}else{
        		$encabezado = 'La suscrita, Directora';
        	}            
        }else{
        	$encabezado = 'La suscrita, Directora';
        }
        
        $html = $arrayDatos['textoCertificado'];
        $html = str_replace('$encabezado', $encabezado, $html);
        $html = str_replace('$identificadorFuncionario', $_SESSION['usuario'], $html);
        $html = str_replace('$funcionario', $_SESSION['datosUsuario'], $html);
        $html = str_replace('$direccion', $arrayDatos['direccion'], $html);
        $html = str_replace('$fechaInicio', $fechaCert, $html);
        $html = str_replace('$remuneracion', $remune, $html);
        $html = str_replace('$puesto', strtoupper($arrayDatos['puesto']), $html);
        $html = str_replace('$letras', $valor, $html);

        $doc->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'J', false);

        $doc->Cell(0, 34, 'Atentamente,', 0, 1, 'L');
        $y = $doc->GetY();
        $doc->setCellHeightRatio(1.25);
        // ***************************************************************************************************
        if ($arrayDatos['firma'] === 'Si') {
            $doc->write2DBarcode($rutaQRF, 'QRCODE,Q', $margen_izquierdo + 1, $y - 10, 26, 26, $style, 'N');
            $doc->SetFont('times', '', 9);
            $doc->writeHTMLCell(0, '', $margen_izquierdo + 27, $y - 8, 'Firmado electrónicamente por:', 0, 0, 0, true, 'L', true);
            $doc->Ln();
            $doc->SetFont('times', 'B', 11);
            $doc->writeHTMLCell(45, '', $margen_izquierdo + 27, '', $arrayDatos['qrFirma'], 0, 0, 0, true, 'L', true);
            $doc->setSignatureAppearance($margen_izquierdo + 1, $y - 10, 26, 26);
        } else {
            $y = $y - 10;
        }
        // ****************************************************************************************************
        $doc->SetFont('times', '', 12);
        $doc->writeHTMLCell('', '', '', $y + 20, $arrayDatos['nombreRH'], 0, 0, 0, true, 'L', true);
        $doc->Ln();
        $doc->SetFont('times', 'B', 12);
        $doc->Cell(0, 0, $arrayDatos['cargoRH'], 0, 1, 'L');
        $doc->SetFont('times', '', 12);
        $left_column = Constantes::AGROCALIDAD_SIGNIFICADO;

        $y = $doc->GetY();
        $doc->writeHTMLCell(140, '', '', $y, $left_column, 0, 0, 0, true, 'J', true);

        $y = 230;
        $xfull = $doc->getPageWidth() - 25 - $margen_derecho;
        $doc->write2DBarcode($rutaQRG, 'QRCODE,Q', $xfull, $y, 25, 25, $style, 'N');

        // ******************************* FIN DE LA EDICION ****************************************************************************************
        $doc->Output(CERT_LAB_URL_TCPDF . "reporte/" . $arrayDatos['rutaFirma'] . ".pdf", 'F');
        ob_end_clean();
    }

    // ******************funcion consultar datos de director/a de talento humano*********
    public function obtenerDatosUath($area)
    {
        $consulta = "SELECT
                    	erh.identificador,
                        fe.apellido ||' '||fe.nombre as nombre,
                        genero,
                        erh.titulo                       
                    FROM
                    	g_uath.encargo_recursos_humanos erh, g_uath.ficha_empleado fe
                    WHERE 
                        zona_area ='" . $area . "' and
                        estado = 'activo' and
                        fe.identificador = erh.identificador;";

        return $this->modeloCertificado->ejecutarSqlNativo($consulta);
    }
}

// ********clase para tcpdf******************************************
class PDF extends TCPDF
{

    // Page header
    public function Header()
    {
        $this->setJPEGQuality(90);
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 0); 
        $img_file = RUTA_IMG_GENE . "fondoCertificadoBlanco.png";
        $this->Image($img_file, 0, 0, 209, 296, 'JPG', '', '', false, 200, '', false, false, 0);
        $this->SetAutoPageBreak($auto_page_break, $bMargin); 
        $this->setPageMark(); 
    }

    public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false)
    {
        parent::AddPage();
    }

    public function fecha($ciudad, $opt, $fecha)
    {
        $date = new \DateTime($fecha);
        $meses = array(
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        );
        if ($opt == 1) {
            $fechaFinal = $ciudad . ', ' . $date->format('d') . " de " . $meses[$date->format('n') - 1] . " del " . $date->format('Y') . ' ';
        } else if ($opt == 2) {
            $fechaFinal = $date->format('d') . " de " . $meses[$date->format('n') - 1] . " del " . $date->format('Y') . ' ';
        }

        return $fechaFinal;
    }

    public function Footer()
    {
        $this->SetY(- 30);
        $this->SetFont(PDF_FONT_NAME_MAIN, 'I', 8);
        //$this->writeHTMLCell('', '', $this->SetX(20),$this->SetY(- 22),'Dirección: Av. Eloy Alfaro N30-350 y Av. Amazonas, esq.', 0, 0, 0, true, 'L', true);
        //$this->writeHTMLCell('', '', $this->SetX(20),$this->SetY(- 19),'Código postal: 170518 / Quito - Ecuador', 0, 0, 0, true, 'L', true);
        //$this->writeHTMLCell('', '', $this->SetX(20),$this->SetY(- 16),'Teléfono: 593-23828860 - www.agrocalidad.gob.ec', 0, 0, 0, true, 'L', true);
    }
}
