<?php
/**
 * Controlador FormaAdministracion
 *
 * Este archivo controla la lógica del negocio del modelo:  FormaAdministracionModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    FormaAdministracionControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\FormaAdministracionLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\FormaAdministracionModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class FormaAdministracionControlador extends BaseControlador
{

    private $lNegocioFormaAdministracion = null;
    private $modeloFormaAdministracion = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioFormaAdministracion = new FormaAdministracionLogicaNegocio();
        $this->modeloFormaAdministracion = new FormaAdministracionModelo();
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
        $modeloFormaAdministracion = $this->lNegocioFormaAdministracion->buscarFormaAdministracion();
        $this->tablaHtmlFormaAdministracion($modeloFormaAdministracion);
        require APP . 'DossierPecuario/vistas/listaFormaAdministracionVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo FormaAdministracion";
        require APP . 'DossierPecuario/vistas/formularioFormaAdministracionVista.php';
    }

    /**
     * Método para registrar en la base de datos -FormaAdministracion
     */
    public function guardar()
    {
        //Busca los datos de forma de administración en animales
        $query = "id_especie_destino = '".$_POST['id_especie_destino']."' and
        quitar_caracteres_especiales(upper(trim(nombre_especie))) = quitar_caracteres_especiales(upper(trim('".$_POST['nombre_especie']."'))) and
        quitar_caracteres_especiales(upper(trim(caracteristicas_animal))) = quitar_caracteres_especiales(upper(trim('".$_POST['caracteristicas_animal']."'))) and
        quitar_caracteres_especiales(upper(trim(cantidad_producto))) = quitar_caracteres_especiales(upper(trim('".$_POST['cantidad_producto']."'))) and
        id_solicitud = '".$_POST['id_solicitud']."'";
        
        $listaUsoEspecie = $this->lNegocioFormaAdministracion->buscarLista($query);
        
        if(isset($listaUsoEspecie->current()->id_uso_especie)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioFormaAdministracion->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: FormaAdministracion
     */
    public function editar()
    {
        $this->accion = "Editar FormaAdministracion";
        $this->modeloFormaAdministracion = $this->lNegocioFormaAdministracion->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioFormaAdministracionVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - FormaAdministracion
     */
    public function borrar()
    {
        $this->lNegocioFormaAdministracion->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - FormaAdministracion
     */
    public function tablaHtmlFormaAdministracion($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_forma_administracion'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\formaadministracion"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_forma_administracion'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['id_especie_destino'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar las formas de administración en animales
     */
    public function construirDetalleFormaAdministracion()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $listaDetalles = $this->lNegocioFormaAdministracion->obtenerInformacionFormaAdministracion($idSolicitud);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['especie'] != '' ? $fila['especie'] : '').'</td>
                            <td>' . ($fila['nombre_especie'] != '' ? $fila['nombre_especie'] : '').'</td>
                            <td>' . ($fila['caracteristicas_animal'] != '' ? $fila['caracteristicas_animal'] : '').'</td>
                            <td>' . ($fila['cantidad_producto'] != '' ? $fila['cantidad_producto'] : '').'</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') . 'onclick="fn_eliminarDetalleFormaAdministracion(' . $fila['id_forma_administracion'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}