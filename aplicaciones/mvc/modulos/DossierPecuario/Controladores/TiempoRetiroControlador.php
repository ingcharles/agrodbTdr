<?php
/**
 * Controlador TiempoRetiro
 *
 * Este archivo controla la lógica del negocio del modelo:  TiempoRetiroModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2021-07-21
 * @uses    TiempoRetiroControlador
 * @package DossierPecuario
 * @subpackage Controladores
 */
namespace Agrodb\DossierPecuario\Controladores;

use Agrodb\DossierPecuario\Modelos\TiempoRetiroLogicaNegocio;
use Agrodb\DossierPecuario\Modelos\TiempoRetiroModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class TiempoRetiroControlador extends BaseControlador
{

    private $lNegocioTiempoRetiro = null;

    private $modeloTiempoRetiro = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioTiempoRetiro = new TiempoRetiroLogicaNegocio();
        $this->modeloTiempoRetiro = new TiempoRetiroModelo();
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
        $modeloTiempoRetiro = $this->lNegocioTiempoRetiro->buscarTiempoRetiro();
        $this->tablaHtmlTiempoRetiro($modeloTiempoRetiro);
        require APP . 'DossierPecuario/vistas/listaTiempoRetiroVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo TiempoRetiro";
        require APP . 'DossierPecuario/vistas/formularioTiempoRetiroVista.php';
    }

    /**
     * Método para registrar en la base de datos -TiempoRetiro
     */
    public function guardar()
    {
        // Busca los datos de periodo de vida util
        $query = "quitar_caracteres_especiales(upper(trim(ingrediente_activo))) = quitar_caracteres_especiales(upper(trim('" . $_POST['ingrediente_activo'] . "'))) and
        id_producto_consumo = '" . $_POST['id_producto_consumo'] . "' and
        tiempo_retiro = '" . $_POST['tiempo_retiro'] . "' and
        id_unidad = '" . $_POST['id_unidad'] . "' and
        id_solicitud = '" . $_POST['id_solicitud'] . "'";

        $listaTiempoRetiro = $this->lNegocioTiempoRetiro->buscarLista($query);

        if (isset($listaTiempoRetiro->current()->id_tiempo_retiro)) {
            Mensajes::fallo(Constantes::ERROR_DUPLICADO);
        } else {
            $this->lNegocioTiempoRetiro->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: TiempoRetiro
     */
    public function editar()
    {
        $this->accion = "Editar TiempoRetiro";
        $this->modeloTiempoRetiro = $this->lNegocioTiempoRetiro->buscar($_POST["id"]);
        require APP . 'DossierPecuario/vistas/formularioTiempoRetiroVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - TiempoRetiro
     */
    public function borrar()
    {
        $this->lNegocioTiempoRetiro->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - TiempoRetiro
     */
    public function tablaHtmlTiempoRetiro($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_tiempo_retiro'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'DossierPecuario\tiemporetiro"
                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
                		<td>' . ++ $contador . '</td>
                		<td style="white - space:nowrap; "><b>' . $fila['id_tiempo_retiro'] . '</b></td>
                        <td>' . $fila['id_solicitud'] . '</td>
                        <td>' . $fila['fecha_creacion'] . '</td>
                        <td>' . $fila['ingrediente_activo'] . '</td>
                    </tr>'
            );
        }
    }
    
    /**
     * Método para listar los periodos de retiro
     */
    public function construirDetalleTiempoRetiro()
    {
        $idSolicitud = $_POST['idSolicitud'];
        $fase = $_POST['fase'];
        
        $listaDetalles = $this->lNegocioTiempoRetiro->obtenerInformacionTiempoRetiro($idSolicitud);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['ingrediente_activo'] != '' ? $fila['ingrediente_activo'] : '').'</td>
                            <td>' . ($fila['producto_consumo'] != '' ? $fila['producto_consumo'] : '').'</td>
                            <td>' . ($fila['tiempo_retiro'] != '' ? $fila['tiempo_retiro'] . ' ' .($fila['nombre_unidad'] != '' ? $fila['nombre_unidad'] : ''): '').'</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" '. ($fase!='editar'?'style="display:none"':'') . 'onclick="fn_eliminarDetalleTiempoRetiro(' . $fila['id_tiempo_retiro'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}