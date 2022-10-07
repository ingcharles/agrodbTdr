<?php
/**
 * Controlador DocumentoAnexo
 *
 * Este archivo controla la lógica del negocio del modelo:  DocumentoAnexoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    DocumentoAnexoControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\DocumentoAnexoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\DocumentoAnexoModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class DocumentoAnexoControlador extends BaseControlador
{

    private $lNegocioDocumentoAnexo = null;
    private $modeloDocumentoAnexo = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioDocumentoAnexo = new DocumentoAnexoLogicaNegocio();
        $this->modeloDocumentoAnexo = new DocumentoAnexoModelo();
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
        $modeloDocumentoAnexo = $this->lNegocioDocumentoAnexo->buscarDocumentoAnexo();
        $this->tablaHtmlDocumentoAnexo($modeloDocumentoAnexo);
        require APP . 'DossierPecuario/vistas/listaDocumentoAnexoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo DocumentoAnexo";
        require APP . 'DossierPecuario/vistas/formularioDocumentoAnexoVista.php';
    }

    /**
     * Método para registrar en la base de datos -DocumentoAnexo
     */
    public function guardar()
    {
        $bandera = false;
        
        if($_POST['id_tipo_documento'] != '0'){//Es anexo
            $tipo = 'anexo';
            
            $query = "id_tipo_documento = '".$_POST['id_tipo_documento']."' and
            id_solicitud = '".$_POST['id_solicitud']."'";
            
            //Revisa cuántos documento tiene ingresados. Máximo 7 de anexos, de enlaces sin límite
            $numArchivos = $this->lNegocioDocumentoAnexo->obtenerNumeroDocumentosAnexos($_POST['id_solicitud'], $tipo);
            
            if($numArchivos->current()->numero >= 7){
                $bandera = false;
            }else{
                $bandera = true;
            }
        }else{//Es enlace
            $tipo = 'enlace';
            $bandera = true;
            
            $query = "id_tipo_documento = '".$_POST['id_tipo_documento']."' and
            quitar_caracteres_especiales(upper(trim(ruta_documento))) = quitar_caracteres_especiales(upper(trim('".$_POST['ruta_documento']."'))) and
            id_solicitud = '".$_POST['id_solicitud']."'";
        }
        
        if($bandera == true){
            //Busca los datos de documentos anexos
            $listaUsoEspecie = $this->lNegocioDocumentoAnexo->buscarLista($query);
            
            if(isset($listaUsoEspecie->current()->id_documento_anexo)){
                Mensajes::fallo(Constantes::ERROR_DUPLICADO);
            }else{
                $this->lNegocioDocumentoAnexo->guardar($_POST);
                Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
            }
        }else{
            Mensajes::fallo(Constantes::ERROR_CANTIDAD_ACEPTADA);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: DocumentoAnexo
     */
    public function editar()
    {
        $this->accion = "Editar DocumentoAnexo";
        $this->modeloDocumentoAnexo = $this->lNegocioDocumentoAnexo->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioDocumentoAnexoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - DocumentoAnexo
     */
    public function borrar()
    {
        $this->lNegocioDocumentoAnexo->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - DocumentoAnexo
     */
    public function tablaHtmlDocumentoAnexo($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_documento_anexo'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\documentoanexo"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_documento_anexo'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['id_tipo_documento'] . '</td>
                </tr>'
            );
        }
    }
    
    //obtenerNumeroDocumentosAnexos
    
    /**
     * Método para listar los documentos anexos
     */
    public function construirDetalleDocumentoAnexo()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $tipo = $_POST['tipo'];
        $fase = $_POST['fase'];
        
        $listaDetalles = $this->lNegocioDocumentoAnexo->obtenerInformacionDocumentoAnexo($idSolicitud, $tipo);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['id_tipo_documento'] != '0' ? $fila['documento'] : 'Archivo externo').'</td>
                            <td>' . ($fila['descripcion_documento'] != '' ? $fila['descripcion_documento'] : '').'</td>
                            <td>' . ($fila['id_tipo_documento'] != '0' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$fila['ruta_documento'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Enlace</a>' : '<a href="'.$fila['ruta_documento'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Enlace</a>'). '</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') . 'onclick="fn_eliminarDetalleDocumento'.($fila['id_tipo_documento'] != '0' ? 'Anexo' : 'Externo').'(' . $fila['id_documento_anexo'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}