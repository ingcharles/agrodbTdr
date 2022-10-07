<?php
/**
 * Controlador FormaAplicacionInstalaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  FormaAplicacionInstalacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    FormaAplicacionInstalacionesControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\FormaAplicacionInstalacionesLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\FormaAplicacionInstalacionesModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class FormaAplicacionInstalacionesControlador extends BaseControlador
{

    private $lNegocioFormaAplicacionInstalaciones = null;
    private $modeloFormaAplicacionInstalaciones = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioFormaAplicacionInstalaciones = new FormaAplicacionInstalacionesLogicaNegocio();
        $this->modeloFormaAplicacionInstalaciones = new FormaAplicacionInstalacionesModelo();
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
        $modeloFormaAplicacionInstalaciones = $this->lNegocioFormaAplicacionInstalaciones->buscarFormaAplicacionInstalaciones();
        $this->tablaHtmlFormaAplicacionInstalaciones($modeloFormaAplicacionInstalaciones);
        require APP . 'DossierPecuario/vistas/listaFormaAplicacionInstalacionesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo FormaAplicacionInstalaciones";
        require APP . 'DossierPecuario/vistas/formularioFormaAplicacionInstalacionesVista.php';
    }

    /**
     * Método para registrar en la base de datos -FormaAplicacionInstalaciones
     */
    public function guardar()
    {
        //Busca los datos de aplicación en instalaciones
        $query = "quitar_caracteres_especiales(upper(trim(dosis))) = quitar_caracteres_especiales(upper(trim('".$_POST['dosis']."'))) and
        quitar_caracteres_especiales(upper(trim(forma_administracion))) = quitar_caracteres_especiales(upper(trim('".$_POST['forma_administracion']."'))) and
        id_solicitud = '".$_POST['id_solicitud']."'";
        
        $listaAplicacionInstalaciones = $this->modeloFormaAplicacionInstalaciones->buscarLista($query);
        
        if(isset($listaAplicacionInstalaciones->current()->id_forma_aplicacion_instalacion)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->modeloFormaAplicacionInstalaciones->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: FormaAplicacionInstalaciones
     */
    public function editar()
    {
        $this->accion = "Editar FormaAplicacionInstalaciones";
        $this->modeloFormaAplicacionInstalaciones = $this->lNegocioFormaAplicacionInstalaciones->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioFormaAplicacionInstalacionesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - FormaAplicacionInstalaciones
     */
    public function borrar()
    {
        $this->lNegocioFormaAplicacionInstalaciones->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - FormaAplicacionInstalaciones
     */
    public function tablaHtmlFormaAplicacionInstalaciones($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_forma_aplicacion_instalacion'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\formaaplicacioninstalaciones"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_forma_aplicacion_instalacion'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['dosis'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar las formas de aplicación en instalaciones
     */
    public function construirDetalleFormaAplicacionInstalaciones()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $query = "id_solicitud = $idSolicitud";
        
        $listaDetalles = $this->lNegocioFormaAplicacionInstalaciones->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['dosis'] != '' ? $fila['dosis'] : 'NA'). '</td>
                            <td>' . ($fila['forma_administracion'] != '' ? $fila['forma_administracion'] : 'NA'). '</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') .' onclick="fn_eliminarDetalleFormaAplicacionInstalaciones(' . $fila['id_forma_aplicacion_instalacion'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}
