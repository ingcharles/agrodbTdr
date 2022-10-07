<?php
/**
 * Controlador DocumentosAdjuntos
 *
 * Este archivo controla la lógica del negocio del modelo:  DocumentosAdjuntosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-08-04
 * @uses    DocumentosAdjuntosControlador
 * @package CertificadoFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\CertificadoFitosanitario\Controladores;

use Agrodb\CertificadoFitosanitario\Modelos\DocumentosAdjuntosLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\DocumentosAdjuntosModelo;

class DocumentosAdjuntosControlador extends BaseControlador
{

    private $lNegocioDocumentosAdjuntos = null;
    private $modeloDocumentosAdjuntos = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDocumentosAdjuntos = new DocumentosAdjuntosLogicaNegocio();
        $this->modeloDocumentosAdjuntos = new DocumentosAdjuntosModelo();
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
        $modeloDocumentosAdjuntos = $this->lNegocioDocumentosAdjuntos->buscarDocumentosAdjuntos();
        $this->tablaHtmlDocumentosAdjuntos($modeloDocumentosAdjuntos);
        require APP . 'CertificadoFitosanitario/vistas/listaDocumentosAdjuntosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo DocumentosAdjuntos";
        require APP . 'CertificadoFitosanitario/vistas/formularioDocumentosAdjuntosVista.php';
    }

    /**
     * Método para registrar en la base de datos -DocumentosAdjuntos
     */
    public function guardar()
    {
        $this->lNegocioDocumentosAdjuntos->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: DocumentosAdjuntos
     */
    public function editar()
    {
        $this->accion = "Editar DocumentosAdjuntos";
        $this->modeloDocumentosAdjuntos = $this->lNegocioDocumentosAdjuntos->buscar($_POST["id"]);
        require APP . 'CertificadoFitosanitario/vistas/formularioDocumentosAdjuntosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DocumentosAdjuntos
     */
    public function borrar()
    {
        $this->lNegocioDocumentosAdjuntos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - DocumentosAdjuntos
     */
    public function tablaHtmlDocumentosAdjuntos($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_documento_adjunto'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificadoFitosanitario\documentosadjuntos"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                    		  <td>' . ++ $contador . '</td>
                    		  <td style="white - space:nowrap; "><b>' . $fila['id_documento_adjunto'] . '</b></td>
                    <td>' . $fila['id_certificado_fitosanitario'] . '</td>
                    <td>' . $fila['tipo_adjunto'] . '</td>
                    <td>' . $fila['ruta_adjunto'] . '</td>
                    </tr>'
                );
            }
        }
    }

}