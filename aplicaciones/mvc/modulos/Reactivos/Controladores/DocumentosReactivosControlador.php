<?php

/**
 * Controlador DocumentosReactivos
 *
 * Este archivo controla la lógica del negocio del modelo:  DocumentosReactivosModelo y  Vistas
 *
 * @author DATASTAR
 * @uses     DocumentosReactivosControlador
 * @package Reactivos
 * @subpackage Controladores
 */

namespace Agrodb\Reactivos\Controladores;

use Agrodb\Reactivos\Modelos\DocumentosReactivosLogicaNegocio;
use Agrodb\Reactivos\Modelos\DocumentosReactivosModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class DocumentosReactivosControlador extends BaseControlador
{

    private $lNegocioDocumentosReactivos = null;
    private $modeloDocumentosReactivos = null;
    private $accion = null;
    private $idReactivoBodega = null;
    private $idDocumentosReactivos = null;
    private $certificadoActual = null;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->lNegocioDocumentosReactivos = new DocumentosReactivosLogicaNegocio();
        $this->modeloDocumentosReactivos = new DocumentosReactivosModelo();
        parent::__construct();
        set_exception_handler(array($this, 'manejadorExcepciones'));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloDocumentosReactivos = $this->lNegocioDocumentosReactivos->buscarDocumentosReactivos();
        $this->tablaHtmlDocumentosReactivos($modeloDocumentosReactivos);
        require APP . 'Reactivos/vistas/listaDocumentosReactivosVista.php';
    }

    /**
     * Consulta si el reactivo tiene un certificado registrado y permite ingresar uno nuevo o actualizarlo
     */
    public function certificado()
    {
        $this->accion = "Subir Certificado de Reactivos";
        $this->certificadoActual = "";
        $this->idReactivoBodega = $_POST["id"];
        $this->idDocumentosReactivos = 0;
        $resultado = $this->lNegocioDocumentosReactivos->buscarLista(array("id_reactivo_bodega" => $this->idReactivoBodega));

        $fila = $resultado->current();
        if (!empty($fila->id_documentos_reactivos))
        {
            //Creamos el código HTML para imprimir la opción de descarga del certificado y poder borrar para subir uno nuevo
            $this->idDocumentosReactivos = $fila->id_documentos_reactivos;
            $this->certificadoActual = '<div id="tablaCertificados" class="row" >
            <div class="col-xs-6 col-md-4">' . Constantes::EXISTE_CERTIFICADO_REACTIVO . '</div>
            <div class="col-xs-6 col-md-4">' . $this->descargaPdf(URL_DIR_REA_CERTIFICADOS . '/' . $fila->nombre_archivo) . '</div>
            <div class="col-xs-6 col-md-4"><button  type="button" id="eliminar">' . Constantes::BOTON_ELIMINAR . '</button></div>
        </div>';
        }

        require APP . 'Reactivos/vistas/formularioDocumentosReactivosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo DocumentosReactivos";
        require APP . 'Reactivos/vistas/formularioDocumentosReactivosVista.php';
    }

    /**
     * Método para registrar en la base de datos -DocumentosReactivos
     */
    public function guardar()
    {
        $this->lNegocioDocumentosReactivos->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: DocumentosReactivos
     */
    public function editar()
    {
        $this->accion = "Editar DocumentosReactivos";
        $this->modeloDocumentosReactivos = $this->lNegocioDocumentosReactivos->buscar($_POST["id"]);
        require APP . 'Reactivos/vistas/formularioDocumentosReactivosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DocumentosReactivos
     */
    public function borrar()
    {
        $this->lNegocioDocumentosReactivos->borrar($_POST['idDocumentosReactivos']);
        Mensajes::exito(Constantes::ELIMINADO_CON_EXITO);
    }

    /**
     * Construye el código HTML para desplegar la lista de - DocumentosReactivos
     */
    public function tablaHtmlDocumentosReactivos($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila)
        {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_documentos_reactivos'] . '"
		  class="item" data-rutaAplicacion="Reactivos/DocumentosReactivos"
		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
		  data-destino="detalleItem">
		  <td>' . ++$contador . '</td>
		  <td style="white - space:nowrap; "><b>' . $fila['id_documentos_reactivos'] . '</b></td>
                  <td>' . $fila['id_solicitud_requerimiento'] . '</td>
                   <td>' . $fila['id_reactivo_bodega'] . '</td>
                   <td>' . $fila['codigo_bodega'] . '</td>
                </tr>');
        }
    }

}
