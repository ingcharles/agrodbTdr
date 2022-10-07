<?php
/**
 * Controlador PaisesPuertosTransito
 *
 * Este archivo controla la lógica del negocio del modelo:  PaisesPuertosTransitoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-04
 * @uses    PaisesPuertosTransitoControlador
 * @package CertificadoFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\CertificadoFitosanitario\Controladores;

use Agrodb\CertificadoFitosanitario\Modelos\PaisesPuertosTransitoLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\PaisesPuertosTransitoModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class PaisesPuertosTransitoControlador extends BaseControlador
{

    private $lNegocioPaisesPuertosTransito = null;
    private $modeloPaisesPuertosTransito = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioPaisesPuertosTransito = new PaisesPuertosTransitoLogicaNegocio();
        $this->modeloPaisesPuertosTransito = new PaisesPuertosTransitoModelo();
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
        $modeloPaisesPuertosTransito = $this->lNegocioPaisesPuertosTransito->buscarPaisesPuertosTransito();
        $this->tablaHtmlPaisesPuertosTransito($modeloPaisesPuertosTransito);
        require APP . 'CertificadoFitosanitario/vistas/listaPaisesPuertosTransitoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo PaisesPuertosTransito";
        require APP . 'CertificadoFitosanitario/vistas/formularioPaisesPuertosTransitoVista.php';
    }

    /**
     * Método para registrar en la base de datos -PaisesPuertosTransito
     */
    public function guardar()
    {
        $idCertificadoFitosanitario = $_POST['id_certificado_fitosanitario_pais_puerto_transito'];
        $idPaisTransito = $_POST['id_pais_transito'];
        $nombrePaisTransito = $_POST['nombre_pais_transito'];
        $idPuertoTransito = $_POST['id_puerto_transito'];
        $nombrePuertoTransito = $_POST['nombre_puerto_transito'];
        $idMediotransporteTransito = $_POST['id_medio_transporte_transito'];
        $nombreMediotransporteTransito = $_POST['nombre_medio_transporte_transito'];

        $validacion = "Fallo";
        $resultado = "El país y puerto de transito ya ha sido registrado.";

        $arrayParametros = array(
            'id_certificado_fitosanitario' => $idCertificadoFitosanitario,
            'id_pais_transito' => $idPaisTransito,
            'nombre_pais_transito' => $nombrePaisTransito,
            'id_puerto_transito' => $idPuertoTransito,
            'nombre_puerto_transito' => $nombrePuertoTransito,
            'id_medio_transporte_transito' => $idMediotransporteTransito,
            'nombre_medio_transporte_transito' => $nombreMediotransporteTransito
        );

        $verificarPaisPuertoTransito = $this->lNegocioPaisesPuertosTransito->obtenerPaisesPuertosTransito($arrayParametros);

        if (! isset($verificarPaisPuertoTransito->current()->id_pais_puerto_transito)) {

            $validacion = "Exito";
            $resultado = "";

            $datosPaisPuertoTransito = $this->lNegocioPaisesPuertosTransito->guardar($arrayParametros);

            $filaPaisPuertoTransito = $this->generarFilaPaisPuertosTransito($datosPaisPuertoTransito);

            echo json_encode(array(
                'validacion' => $validacion,
                'resultado' => $resultado,
                'filaPaisPuertoTransito' => $filaPaisPuertoTransito
            ));
        } else {
            echo json_encode(array(
                'validacion' => $validacion,
                'resultado' => $resultado
            ));
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: PaisesPuertosTransito
     */
    public function editar()
    {
        $this->accion = "Editar PaisesPuertosTransito";
        $this->modeloPaisesPuertosTransito = $this->lNegocioPaisesPuertosTransito->buscar($_POST["id"]);
        require APP . 'CertificadoFitosanitario/vistas/formularioPaisesPuertosTransitoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - PaisesPuertosTransito
     */
    public function borrar()
    {
        $this->lNegocioPaisesPuertosTransito->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - PaisesPuertosTransito
     */
    public function tablaHtmlPaisesPuertosTransito($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_pais_puerto_transito'] . '"
                class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificadoFitosanitario\paisespuertostransito"
                data-opcion="editar" ondragstart="drag(event)" draggable="true"
                data-destino="detalleItem">
                <td>' . ++ $contador . '</td>
                <td style="white - space:nowrap; "><b>' . $fila['id_pais_puerto_transito'] . '</b></td>
	 
                <td>' . $fila['id_certificado_fitosanitario'] . '</td>
                <td>' . $fila['id_pais_transito'] . '</td>
			
                <td>' . $fila['nombre_pais_transito'] . '</td>
                </tr>'
            );
        }
    }

    /**
     * Método para listar paises y puertos de transito del certificado fitosanitario
     */
    public function generarFilaPaisPuertosTransito($idPaisPuertoTransito)
    {
        $filaPaisPuertosTransito = $this->lNegocioPaisesPuertosTransito->buscar($idPaisPuertoTransito);

        $idPaisPuertoTransito = $filaPaisPuertosTransito->getIdPuertoTransito();
        $nombrePaisPuertoTransito = $filaPaisPuertosTransito->getNombrePaisTransito();
        $nombrePuertoTransito = $filaPaisPuertosTransito->getNombrePuertoTransito();
        $nombreMedioTransporteTransito = $filaPaisPuertosTransito->getNombreMedioTransporteTransito();

        $this->listaDetalles = '
                        <tr id="' . $idPaisPuertoTransito . '">
                            <td>' . ($nombrePaisPuertoTransito != '' ? $nombrePaisPuertoTransito : '') . '</td>
                            <td>' . ($nombrePuertoTransito != '' ? $nombrePuertoTransito : '') . '</td>
                            <td>' . ($nombreMedioTransporteTransito != '' ? $nombreMedioTransporteTransito : '') . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarDetallePaisPuertosTransito(' . $idPaisPuertoTransito . '); return false;"/></td>
                        </tr>';

        return $this->listaDetalles;
    }

}