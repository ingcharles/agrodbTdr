<?php
/**
 * Controlador Certificado
 *
 * Este archivo controla la lógica del negocio del modelo:  CertificadoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2019-02-12
 * @uses    CertificadoControlador
 * @package CertificadoLaboral
 * @subpackage Controladores
 */
namespace Agrodb\CertificadoLaboral\Controladores;

use Agrodb\CertificadoLaboral\Modelos\CertificadoLogicaNegocio;
use Agrodb\CertificadoLaboral\Modelos\CertificadoModelo;
use Agrodb\Core\Constantes;


class CertificadoControlador extends BaseControlador
{

    private $lNegocioCertificado = null;

    private $modeloCertificado = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioCertificado = new CertificadoLogicaNegocio();
        $this->modeloCertificado = new CertificadoModelo();

        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloCertificado = $this->lNegocioCertificado->buscarLista("estado ='creado' and identificador='".$_SESSION['usuario']."'");
        $this->tablaHtmlCertificado($modeloCertificado);
        require APP . 'CertificadoLaboral/vistas/listaCertificadoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Certificado Laboral";
        require APP . 'CertificadoLaboral/vistas/formularioCertificadoVista.php';
    }

    /**
     * Método para desplegar el certificado
     */
    public function editar()
    {
        $this->urlPdf = $_POST['id'];
        require APP . 'CertificadoLaboral/vistas/visorPDF.php';
    }

    /**
     * Construye el código HTML para desplegar la lista de - Certificado
     */
    public function tablaHtmlCertificado($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $date = new \DateTime($fila['fecha_creacion']);
                $date = $date->format('d-m-Y');
                $arrayParametros = array(
                    'estado' => 'activo',
                    'id_formato' => $fila['id_formato']
                );
                $resultado = $this->lNegocioCertificado->buscarTipoCertificado($arrayParametros);
                $tipo = 'Certificado laboral ' . $resultado->current()->tipo;
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['ruta_archivo'] . '"
		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificadoLaboral\Certificado"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++ $contador . '</td>
		  <td style="white - space:nowrap; ">' . $date . '</td>
            <td>' . $tipo .'</td>
            </tr>'
                );
            }
        }
    }

    /**
     * Consulta los tipos de certificados
     */
    public function comboTipoCetificado()
    {
        $certificado = new CertificadoLogicaNegocio();
        $tipoCertificado = "";
        $arrayParametros = array(
            'estado' => 'activo'
        );
        $combo = $certificado->buscarTipoCertificado($arrayParametros);

        foreach ($combo as $item) {
            $tipoCertificado .= '<option value="' . $item->id_formato . '">' . $item->descripcion . '</option>';
        }
        return $tipoCertificado;
    }

    /**
     * visualizar certificado
     */
    public function descargarArchivo()
    {
        $estado = 'exito';
        $mensaje = 'Certificado generado con exito';
        $contenido = '';
        $arrayParametros = array(
            'estado' => 'activo',
            'id_formato' => $_POST['id_formato']
        );
        // ****************verificar tipo de certificado***************************
        $resultado = $this->lNegocioCertificado->buscarTipoCertificado($arrayParametros);
        if ($resultado->count()) {

            // ****************verificar info funcionario***************************
            $datos = $this->lNegocioCertificado->obtenerDatosSolicitante($_SESSION['usuario']);
            if ($datos->count()) {

                // ****************verificar info de talento humano***************************
                $datosrh = $this->lNegocioCertificado->obtenerDatosUath('DGATH');
                if ($datosrh->count()) {

                    $aleatorio = rand(1, 999);
                    $rutaFirma = 'certificado_laboral_'.$_SESSION['usuario'] . '_' . $_POST['id_formato'] . '_' . $aleatorio;
                    $nombreFirma = $datosrh->current()->titulo.' '. $datosrh->current()->nombre;
                    if ($datosrh->current()->genero == 'Masculino') {
                        $cargoFirma = 'DIRECTOR DE ADMINISTRACIÓN DE RECURSOS HUMANOS';
                    } else {
                        $cargoFirma = 'DIRECTORA DE ADMINISTRACIÓN DE RECURSOS HUMANOS';
                    }
                    $arrayDatos = array(
                        'firma' => $_POST['firma'],
                        'titulo' => $resultado->current()->titulo,
                        'textoCertificado' => $resultado->current()->texto_certificado,
                        'fecha_inicial' => $datos->current()->fecha_inicial,
                        'direccion' => $datos->current()->direccion,
                        'puesto' => $datos->current()->puesto,
                        'remuneracion' => $datos->current()->remuneracion,
                        'identificadorRH' => $datosrh->current()->identificador,
                        'nombreRH' => $nombreFirma,
                        'cargoRH' => $cargoFirma,
                        'rutaFirma' => $rutaFirma,
                        'qrFirma' => $datosrh->current()->nombre,
                        'generoRH' => $datosrh->current()->genero,
                        'ciudad' => 'Quito'
                    );

                    if ($_POST['firma'] == 'Si') {
                        // ****************verificar si existe la firma electronica***************************
                        $datosFE = $this->lNegocioCertificado->obtenerFirmaElectronica($datosrh->current()->identificador);
                        if ($datosFE->count()) {
                            $arrayGuardar = array(
                                'id_certificado' => '',
                                'identificador' => $_SESSION['usuario'],
                                'ruta_archivo' => CERT_LAB_URL . "reporte/" . $rutaFirma . ".pdf",
                                'id_formato' => $resultado->current()->id_formato,
                                'id_firma_electronica' => $datosFE->current()->id_firma_electronica,
                                'identificador_uath' => $datosrh->current()->identificador
                            );
                            $this->lNegocioCertificado->guardar($arrayGuardar);
                            $this->lNegocioCertificado->generarCertificado($arrayDatos);
                            $this->urlPdf = CERT_LAB_URL . "reporte/" . $rutaFirma . ".pdf";
                            $contenido = $this->urlPdf;
                        } else {
                            $mensaje = 'No existen registros de la firma electrónica';
                            $estado = 'FALLO';
                        }
                    } else {
                        $arrayGuardar = array(
                            'id_certificado' => '',
                            'identificador' => $_SESSION['usuario'],
                            'ruta_archivo' => CERT_LAB_URL . "reporte/" . $rutaFirma . ".pdf",
                            'id_formato' => $resultado->current()->id_formato,
                            'identificador_uath' => $datosrh->current()->identificador
                        );
                        $this->lNegocioCertificado->guardar($arrayGuardar);
                        $this->lNegocioCertificado->generarCertificado($arrayDatos);
                        $this->urlPdf = CERT_LAB_URL . "reporte/" . $rutaFirma . ".pdf";
                        $contenido = $this->urlPdf;
                    }
                } else {
                    $mensaje = 'No existen registros del director/a de talento humano';
                    $estado = 'FALLO';
                }
            } else {
                $mensaje = 'No existen registros del funcionario';
                $estado = 'FALLO';
            }
        } else {
            $mensaje = 'No existen registros del certificado';
            $estado = 'FALLO';
        }
        echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
    }
    /**
     * proceso automatico para eliminar certificados laborales
     */
    public function procesoAutomatico(){
        echo "\n".'Proceso Automatico de eliminacion de certificados'."\n"."\n";
        $modeloCertificado = $this->lNegocioCertificado->buscarLista("estado ='creado'");
        foreach ($modeloCertificado as $fila) {
            $arrayGuardar = array(
                'id_certificado' => $fila['id_certificado'],
                'estado' => 'eliminado'
            );
            $this->lNegocioCertificado->guardar($arrayGuardar);
            echo $fila['identificador']. ' -> se actualizado su estado (eliminado)'."\n";
        }
        echo "\n";
    }
}
