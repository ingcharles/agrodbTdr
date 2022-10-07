<?php
/**
 * Controlador FormaAdministracionAlimento
 *
 * Este archivo controla la lógica del negocio del modelo:  FormaAdministracionAlimentoModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    FormaAdministracionAlimentoControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\FormaAdministracionAlimentoLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\FormaAdministracionAlimentoModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class FormaAdministracionAlimentoControlador extends BaseControlador
{

    private $lNegocioFormaAdministracionAlimento = null;
    private $modeloFormaAdministracionAlimento = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioFormaAdministracionAlimento = new FormaAdministracionAlimentoLogicaNegocio();
        $this->modeloFormaAdministracionAlimento = new FormaAdministracionAlimentoModelo();
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
        $modeloFormaAdministracionAlimento = $this->lNegocioFormaAdministracionAlimento->buscarFormaAdministracionAlimento();
        $this->tablaHtmlFormaAdministracionAlimento($modeloFormaAdministracionAlimento);
        require APP . 'DossierPecuario/vistas/listaFormaAdministracionAlimentoVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo FormaAdministracionAlimento";
        require APP . 'DossierPecuario/vistas/formularioFormaAdministracionAlimentoVista.php';
    }

    /**
     * Método para registrar en la base de datos -FormaAdministracionAlimento
     */
    public function guardar()
    {
        //Busca los datos de forma de administración en alimento
        $query = "quitar_caracteres_especiales(upper(trim(dosis_alimento))) = quitar_caracteres_especiales(upper(trim('".$_POST['dosis_alimento']."'))) and
        quitar_caracteres_especiales(upper(trim(forma_administracion))) = quitar_caracteres_especiales(upper(trim('".$_POST['forma_administracion']."'))) and
        id_solicitud = '".$_POST['id_solicitud']."'";
        
        $listaUsoEspecie = $this->lNegocioFormaAdministracionAlimento->buscarLista($query);
        
        if(isset($listaUsoEspecie->current()->id_forma_administracion_alimento)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioFormaAdministracionAlimento->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: FormaAdministracionAlimento
     */
    public function editar()
    {
        $this->accion = "Editar FormaAdministracionAlimento";
        $this->modeloFormaAdministracionAlimento = $this->lNegocioFormaAdministracionAlimento->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioFormaAdministracionAlimentoVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - FormaAdministracionAlimento
     */
    public function borrar()
    {
        $this->lNegocioFormaAdministracionAlimento->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - FormaAdministracionAlimento
     */
    public function tablaHtmlFormaAdministracionAlimento($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_forma_administracion_alimento'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\formaadministracionalimento"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
                	<td>' . ++ $contador . '</td>
                	<td style="white - space:nowrap; "><b>' . $fila['id_forma_administracion_alimento'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['dosis_alimento'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar las formas de administración en alimentos
     */
    public function construirDetalleFormaAdministracionAlimento()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $query = "id_solicitud = '$idSolicitud'";
        
        $listaDetalles = $this->lNegocioFormaAdministracionAlimento->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['dosis_alimento'] != '' ? $fila['dosis_alimento'] : '').'</td>
                            <td>' . ($fila['forma_administracion'] != '' ? $fila['forma_administracion'] : '').'</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') . 'onclick="fn_eliminarDetalleFormaAdministracionAlimento(' . $fila['id_forma_administracion_alimento'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}