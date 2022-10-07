<?php
/**
 * Controlador UsoEspecie
 *
 * Este archivo controla la lógica del negocio del modelo:  UsoEspecieModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    UsoEspecieControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\UsoEspecieLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\UsoEspecieModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class UsoEspecieControlador extends BaseControlador
{

    private $lNegocioUsoEspecie = null;
    private $modeloUsoEspecie = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioUsoEspecie = new UsoEspecieLogicaNegocio();
        $this->modeloUsoEspecie = new UsoEspecieModelo();
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
        $modeloUsoEspecie = $this->lNegocioUsoEspecie->buscarUsoEspecie();
        $this->tablaHtmlUsoEspecie($modeloUsoEspecie);
        require APP . 'DossierPecuario/vistas/listaUsoEspecieVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo UsoEspecie";
        require APP . 'DossierPecuario/vistas/formularioUsoEspecieVista.php';
    }

    /**
     * Método para registrar en la base de datos -UsoEspecie
     */
    public function guardar()
    {
        //Busca los datos de uso y especie
        $query = "id_uso = '".$_POST['id_uso']."' and
        id_especie = '".$_POST['id_especie']."' and
        quitar_caracteres_especiales(upper(trim(nombre_especie))) = quitar_caracteres_especiales(upper(trim('".$_POST['nombre_especie']."'))) and
        id_solicitud = '".$_POST['id_solicitud']."'";
        
        $listaUsoEspecie = $this->lNegocioUsoEspecie->buscarLista($query);
        
        if(isset($listaUsoEspecie->current()->id_uso_especie)){
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        }else{
            $this->lNegocioUsoEspecie->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: UsoEspecie
     */
    public function editar()
    {
        $this->accion = "Editar UsoEspecie";
        $this->modeloUsoEspecie = $this->lNegocioUsoEspecie->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioUsoEspecieVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - UsoEspecie
     */
    public function borrar()
    {
        $this->lNegocioUsoEspecie->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - UsoEspecie
     */
    public function tablaHtmlUsoEspecie($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_uso_especie'] . '"
            		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\usoespecie"
            		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
            		  data-destino="detalleItem">
            		<td>' . ++ $contador . '</td>
            		<td style="white - space:nowrap; "><b>' . $fila['id_uso_especie'] . '</b></td>
                    <td>' . $fila['id_solicitud'] . '</td>
                    <td>' . $fila['fecha_creacion'] . '</td>
                    <td>' . $fila['id_uso'] . '</td>
                </tr>'
            );
        }
    }
    
    /**
     * Método para listar los usos y especies
     */
    public function construirDetalleUsoEspecie()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $listaDetalles = $this->lNegocioUsoEspecie->obtenerInformacionUsoEspecie($idSolicitud);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['uso'] != '' ? $fila['uso'] : '').'</td>
                            <td>' . ($fila['especie'] != '' ? $fila['especie'] : '').'</td>
                            <td>' . ($fila['nombre_especie'] != '' ? $fila['nombre_especie'] : '').'</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') . 'onclick="fn_eliminarDetalleUsoEspecie(' . $fila['id_uso_especie'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}
